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
{{ $email }}
{{ $name }}
{{ $engroup }}
</form>
</body>
</html>