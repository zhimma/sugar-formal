@include('partials.header')

<body style="padding: 15px;">
會員查詢：
<form method="POST" action="users/search" class="search_form">
	{!! csrf_field() !!}
	<div class="form-group">
		<label for="email" class="">Email</label>	
		<input type="email" name='search' class="" style="width:300px;" id="email" require>
	</div>
	<button type="button" class="btn btn-success" onclick="$('.search_form').submit()">送出</button>
</form>
@if(isset($email))
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
	</tr>
	<tr>
		<td>{{ $email }}</td>
		<td>{{ $name }}</td>
		<td>{{ $gender_ch }}</td>
		@if($isVip)
			<td>是</td>
			<td>@if($vip_free == 1) 是 @else 否 @endif</td>
			<td>{{ $vip_order_id }}</td>
			<td>暫無記錄</td>
			<td>{{ $vip_create_time }}</td>
			<td>{{ $vip_update_time }}</td>
		@else
			<td>否</td>
			<td>無資料</td>
			<td>無資料</td>
			<td>無資料</td>
			<td>無資料</td>
			<td>無資料</td>
		@endif
		<td>
			<form method="POST" action="genderToggler" class="user_profile">{!! csrf_field() !!}
			<input type="hidden" name='user_id' value="{{ $user_id }}">
			<input type="hidden" name='gender_now' value="{{ $gender }}">
			<button type="button" class="btn btn-warning" onclick="$('.user_profile').submit()">變更</button></form>
		</td>
	</tr>
</table>
@endif
</body>
</html>