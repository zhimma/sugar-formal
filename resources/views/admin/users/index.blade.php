@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
<h1>會員搜尋(變更男女、VIP資料)</h1>
<form method="POST" action="{{ route('users/manager') }}" class="search_form">
	{!! csrf_field() !!}
	<div class="form-group">
		<label for="email" class="">Email</label>	
		<input type="text" name='search' class="" style="width:300px;" id="email" required>
	</div>
	<button type="button" class="btn btn-primary" onclick="$('.search_form').submit()">送出</button>
</form><br>
@if(isset($users))
<table class='table table-bordered table-hover'>
	<tr>
		<th>ID</th>
		<th>Email</th>
		<th>名稱</th>
		<th>男/女</th>
		<th>是否為VIP</th>
		<th>是否為免費方案</th>
		<th>升級時的帳單編號</th>
		<th>付費方式</th>
		<th>VIP資料建立時間</th>
		<th>VIP資料更新時間</th>
		<th>變更男/女</th>
		<th>提供/取消VIP權限</th>
	</tr>
	@forelse ($users as $user)
		@php
			$result['isBlocked'] = \App\Models\SimpleTables\banned_users::where('member_id', 'like', $user->id)->get()->first();
            if(!isset($result['isBlocked'])){
                $result['isBlocked'] = \App\Models\BannedUsersImplicitly::select(\DB::raw('id, "隱性" as type'))->where('target', 'like', $user->id)->get()->first();
            }
            $userInfo=\App\Models\User::findById($user->id);
            $user['name'] = $userInfo->name;
            $user['engroup'] = $userInfo->engroup;
            $user['last_login'] = $userInfo->last_login;
            $user['vip'] = \App\Models\Vip::vip_diamond($userInfo->id);
            $user['tipcount'] = \App\Models\Tip::TipCount_ChangeGood($userInfo->id);
            $user['exchange_period'] = $userInfo->exchange_period;
            $user['warnedicon'] = \App\Models\User::warned_icondata($user->id);

		@endphp
	<tr>
		<td>{{ $user->id }}</td>
		<td>{{ $user->email }}</td>
		<td @if($result['isBlocked']) style="background-color:#FFFF00" @endif>
			<a href="{{ route('users/advInfo', $user->id) }}" target='_blank'>
				<p @if($user['engroup'] == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
					{{ $user->name }}
					@if($user['vip'])
						@if($user['vip']=='diamond_black')
							<img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
						@else
							@for($z = 0; $z < $user['vip']; $z++)
								<img src="/img/diamond.png" style="height: 16px;width: 16px;">
							@endfor
						@endif
					@endif
					@if(isset($user['tipcount']))
						@for($i = 0; $i < $user['tipcount']; $i++)
							👍
						@endfor
					@else
						{{ logger('reportedUsers, line 80 tipcount does not exists, user id: ' . $result['reported_id']) }}
					@endif
					@if(!is_null($result['isBlocked']))
						@if(!is_null($result['isBlocked']['expire_date']))
							@if(round((strtotime($result['isBlocked']['expire_date']) - getdate()[0])/3600/24)>0)
								{{ round((strtotime($result['isBlocked']['expire_date']) - getdate()[0])/3600/24 ) }}天
							@else
								此會員登入後將自動解除封鎖
							@endif
						@elseif(isset($result['isBlocked']['type']))
							(隱性)
						@else
                            (永久)
						@endif
					@endif
					@if($user['warnedicon']['isAdminWarned']==1 OR $user['warnedicon']['isWarned']==1)
						<img src="/img/warned_red.png" style="height: 16px;width: 16px;">
					@endif
					@if($user['warnedicon']['isWarned']==0 AND $user['warnedicon']['WarnedScore']>10 AND $user['warnedicon']['auth_status']==1)
						<img src="/img/warned_black.png" style="height: 16px;width: 16px;">
					@endif
				</p>
			</a>
		</td>
		<td>{{ $user->gender_ch }}</td>
		@if($user->isVip)
			<td>是 @if($user->vip_data->expiry!="0000-00-00 00:00:00") (到期日: {{ substr($user->vip_data->expiry, 0, 10) }}) @endif</td>
			<td>@if($user->vip_data->free == 1) 是 @else 否 @endif</td>
			<td>{{ $user->vip_order_id }}</td>
			<td>
				@if ($user->vip_data->payment_method == 'CREDIT')
				 	信用卡
				@elseif ($user->vip_data->payment_method == 'ATM')
					ATM
				@elseif ($user->vip_data->payment_method == 'CVS')
					超商代碼
				@elseif ($user->vip_data->payment_method == 'BARCODE')
					超商條碼
				@endif
			</td>
		@else
			<td>否</td>
			<td>@if(isset($user->vip_data))@if($user->vip_data->free == 1) 是 @else 否 @endif @else 無資料 @endif</td>
			<td>@if(isset($user->vip_order_id)){{ $user->vip_order_id }}@else 無資料 @endif</td>
			<td>@if(isset( $user->vip_data->payment_method))
					@if ($user->vip_data->payment_method == 'CREDIT')
						信用卡
					@elseif ($user->vip_data->payment_method == 'ATM')
						ATM
					@elseif ($user->vip_data->payment_method == 'CVS')
						超商代碼
					@elseif ($user->vip_data->payment_method == 'BARCODE')
						超商條碼
					@endif
				@else 無資料
				@endif
			</td>
		@endif
		<td>@if(isset($user->vip_data->created_at))
				<a href="{{ route('stats/vip_log', $user->id) }}" target="_blank">
{{--				<a href="{{ url('admin/order#'.$user->email) }}" target="_blank">--}}
					{{ $user->vip_data->created_at }}</a>@else 無資料 @endif</td>
		<td>@if(isset($user['updated_at'])){{ $user['updated_at'] }}@else 無資料 @endif</td>
		<td>
			<form method="POST" action="genderToggler" class="user_profile">{!! csrf_field() !!}
			<input type="hidden" name='user_id' value="{{ $user->id }}">
			<input type="hidden" name='gender_now' value="{{ $user->engroup }}">
			<button type="submit" class="btn btn-warning">變更</button></form>
		</td>
		<td>
		<form method="POST" action="VIPToggler" class="vip">{!! csrf_field() !!}
			<input type="hidden" name='user_id' value="{{ $user->id }}">
			<input type="hidden" name='isVip' value="@if($user->isVip) 1 @else 0 @endif">
			<button type="submit" class="btn btn-info">@if($user->isVip) 取消權限 @else 提供權限 @endif</button></form>
		</td>
	</tr>
	@empty
	<tr>
	找不到資料
	</tr>
	@endforelse
</table>
@endif
</body>
</html>
@stop