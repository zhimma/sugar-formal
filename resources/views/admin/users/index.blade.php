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
	<button type="button" class="btn btn-success" onclick="$('.search_form').submit()">送出</button>
</form><br>
@if(isset($users))
<table class='table'>
	<tr>
		<td>Email</td>
		<td>名稱</td>
		<td>男/女</td>
		<td>是否為VIP</td>
		<td>是否為免費方案</td>
		<td>升級時的帳單編號</td>
		<td>升級時卡號的後四碼</td>
		<td>VIP資料建立時間</td>
		<td>VIP資料更新時間</td>
		<td>變更男/女</td>
		<td>提供/取消VIP權限</td>
	</tr>
	@forelse ($users as $user)
	<tr>
		<td>{{ $user->email }}</td>
		<td>{{ $user->name }}</td>
		<td>{{ $user->gender_ch }}</td>
		@if($user->isVip)
			<td>是</td>
			<td>@if($user->vip_data->free == 1) 是 @else 否 @endif</td>
			<td>{{ $user->vip_order_id }}</td>
			<td>暫無記錄</td>
		@else
			<td>否</td>
			<td>@if(isset($user->vip_data))@if($user->vip_data->free == 1) 是 @else 否 @endif @else 無資料 @endif</td>
			<td>@if(isset($user->vip_order_id)){{ $user->vip_order_id }}@else 無資料 @endif</td>
			<td>無資料</td>
		@endif
		<td>@if(isset($user->vip_data->created_at)){{ $user->vip_data->created_at }}@else 無資料 @endif</td>
		<td>@if(isset($user->vip_data->updated_at)){{ $user->vip_data->updated_at }}@else 無資料 @endif</td>
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