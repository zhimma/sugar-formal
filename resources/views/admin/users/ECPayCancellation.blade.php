@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
<h1>綠界 VIP 付費取消資料</h1>
<form method="POST" action="{{ route('users/manager') }}" class="search_form">
	{!! csrf_field() !!}
	<table class="table-hover table table-bordered" style="width: 50%;">
		<tr>
			<th>月份</th>
			<td>
				<input type='text' id="datepicker_1" name="month" data-date-format='yyyy-mm' value="{{ Request::old('body') }}" class="form-control">
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<button type="button" class="btn btn-primary" onclick="$('.search_form').submit()">送出</button>
			</td>
		</tr>
	</table>
</form><br>
@if(isset($contents))
	{{ $contents }}
{{--<table class='table table-bordered table-hover'>--}}
{{--	<tr>--}}
{{--		<th>Email</th>--}}
{{--		<th>名稱</th>--}}
{{--		<th>男/女</th>--}}
{{--		<th>是否為VIP</th>--}}
{{--		<th>是否為免費方案</th>--}}
{{--		<th>升級時的帳單編號</th>--}}
{{--		<th>VIP資料建立時間</th>--}}
{{--		<th>VIP資料更新時間</th>--}}
{{--		<th>變更男/女</th>--}}
{{--		<th>提供/取消VIP權限</th>--}}
{{--	</tr>--}}
{{--	@forelse ($users as $user)--}}
{{--	<tr>--}}
{{--		<td>{{ $user->email }}</td>--}}
{{--		<td>--}}
{{--            <a href="advInfo/{{ $user->id }}" target="_blank">{{ $user->name }}</a>--}}
{{--        </td>--}}
{{--		<td>{{ $user->gender_ch }}</td>--}}
{{--		@if($user->isVip)--}}
{{--			<td>是 @if($user->vip_data->expiry!="0000-00-00 00:00:00") (到期日: {{ substr($user->vip_data->expiry, 0, 10) }}) @endif</td>--}}
{{--			<td>@if($user->vip_data->free == 1) 是 @else 否 @endif</td>--}}
{{--			<td>{{ $user->vip_order_id }}</td>--}}
{{--		@else--}}
{{--			<td>否</td>--}}
{{--			<td>@if(isset($user->vip_data))@if($user->vip_data->free == 1) 是 @else 否 @endif @else 無資料 @endif</td>--}}
{{--			<td>@if(isset($user->vip_order_id)){{ $user->vip_order_id }}@else 無資料 @endif</td>--}}
{{--		@endif--}}
{{--		<td>@if(isset($user->vip_data->created_at))<a href="{{ route('stats/vip_log', $user->id) }}" target="_blank">{{ $user->vip_data->created_at }}</a>@else 無資料 @endif</td>--}}
{{--		<td>@if(isset($user['updated_at'])){{ $user['updated_at'] }}@else 無資料 @endif</td>--}}
{{--		<td>--}}
{{--			<form method="POST" action="genderToggler" class="user_profile">{!! csrf_field() !!}--}}
{{--			<input type="hidden" name='user_id' value="{{ $user->id }}">--}}
{{--			<input type="hidden" name='gender_now' value="{{ $user->engroup }}">--}}
{{--			<button type="submit" class="btn btn-warning">變更</button></form>--}}
{{--		</td>--}}
{{--		<td>--}}
{{--		<form method="POST" action="VIPToggler" class="vip">{!! csrf_field() !!}--}}
{{--			<input type="hidden" name='user_id' value="{{ $user->id }}">--}}
{{--			<input type="hidden" name='isVip' value="@if($user->isVip) 1 @else 0 @endif">--}}
{{--			<button type="submit" class="btn btn-info">@if($user->isVip) 取消權限 @else 提供權限 @endif</button></form>--}}
{{--		</td>--}}
{{--	</tr>--}}
{{--	@empty--}}
{{--	<tr>--}}
{{--	找不到資料--}}
{{--	</tr>--}}
{{--	@endforelse--}}
{{--</table>--}}
@endif
</body>
</html>
@stop