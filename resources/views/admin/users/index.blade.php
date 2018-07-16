@include('partials.header')

<body>
會員查詢：
<form method="POST" action="users/search">
	{!! csrf_field() !!}
	Email:<input type="email" name='search' require>
	<input type='submit' value='送出'>
</form>
@if(isset($email))
<table class='table'>
	<tr>
		<td>Email</td>
		<td>名稱</td>
		<td>男/女</td>
		<td>是否為VIP</td>
		<td>升級 vip 的相關資料</td>
		<td>升級時的帳單編號</td>
		<td>升級時卡號的後四碼</td>
		<td>變更男/女</td>
	</tr>
	<tr>
		<td>{{ $email }}</td>
		<td>{{ $name }}</td>
		<td>{{ $gender_ch }}</td>
		<td>@if($is_vip) 是 @else 否 @endif</td>
		<td>@if($is_vip)  @else 無資料 @endif</td>
		<td>@if($is_vip) {{ $vip_order_id }} @else 無資料 @endif</td>
		<td>@if($is_vip)  @else 無資料 @endif</td>
		<td>
			<form method="POST" action="genderToggler">{!! csrf_field() !!}
			<input type="hidden" name='user_id' value="{{ $user_id }}">
			<input type="hidden" name='gender_now' value="{{ $gender }}">
			<input type='submit' value='變更'></form>
		</td>
	</tr>
</table>
@endif
</body>
</html>