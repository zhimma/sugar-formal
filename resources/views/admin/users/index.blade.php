<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>後台 - 會員管理</title>
</head>
<body>
會員查詢：
<form method="POST" action="users/search">
	{!! csrf_field() !!}
	Email:<input type="email" name='search' require>
	<input type='submit' value='送出'>
</form>
@if(isset($email)){{ $email }}@endif
@if(isset($name)){{ $name }}@endif
@if(isset($engroup)){{ $engroup }}@endif
<table>
	<tr>
		<td>Email</td>
		<td>名稱</td>
		<td>男/女</td>
		<td>是否為VIP</td>
		<td>升級 vip 的相關資料</td>
		<td>升級時的帳單編號</td>
		<td>升級時卡號的後四碼</td>
	</tr>
	<tr>
	</tr>
</table>
</body>
</html>