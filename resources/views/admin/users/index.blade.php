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
		<th>Email</th>
		<th>名稱</th>
		<th>男/女</th>
		<th>是否為VIP</th>
		<th>是否為免費方案</th>
		<th>升級時的帳單編號</th>
		<th>VIP資料建立時間</th>
		<th>VIP資料更新時間</th>
		<th>變更男/女</th>
		<th>提供/取消VIP權限</th>
	</tr>
	@forelse ($users as $user)
	<tr>
		<td>{{ $user->email }}</td>
		<td>
            <a href="advInfo/{{ $user->id }}" target="_blank">{{ $user->name }}</a>
        </td>
		<td>{{ $user->gender_ch }}</td>
		@if($user->isVip)
			<td>是</td>
			<td>@if($user->vip_data->free == 1) 是 @else 否 @endif</td>
			<td>{{ $user->vip_order_id }}</td>
		@else
			<td>否</td>
			<td>@if(isset($user->vip_data))@if($user->vip_data->free == 1) 是 @else 否 @endif @else 無資料 @endif</td>
			<td>@if(isset($user->vip_order_id)){{ $user->vip_order_id }}@else 無資料 @endif</td>
		@endif
		<td>@if(isset($user->vip_data->created_at)){{ $user->vip_data->created_at }}@else 無資料 @endif</td>
		<td>@if(isset($user['updated_at'])){{ $user['updated_at'] }}@else 無資料 @endif</td>
		<td>
			<form method="POST" action="genderToggler" class="user_profile">{!! csrf_field() !!}
			<input type="hidden" name='user_id' value="{{ $user->id }}">
			<input type="hidden" name='gender_now' value="{{ $user->engroup }}">
			<button type="button" class="btn btn-warning" onclick="$('.user_profile').submit()">變更</button></form>
		</td>
		<td>
		<form method="POST" action="VIPToggler" class="vip">{!! csrf_field() !!}
			<input type="hidden" name='user_id' value="{{ $user->id }}">
			<input type="hidden" name='isVip' value="@if($user->isVip) 1 @else 0 @endif">
			<button type="button" class="btn btn-info" onclick="$('.vip').submit()">@if($user->isVip) 取消權限 @else 提供權限 @endif</button></form>
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