@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
<h1>修改會員密碼</h1>
<form method="POST" action="{{ route('users/changePassword') }}" class="search_form">
	{!! csrf_field() !!}
	<div class="form-group">
		<table class="table table-hovered table-bordered" style="width: 50%;">
			<tr>
				<th><label for="email">會員Email</label></th>
				<td>
					<input type="email" name="email" id="email" style="width:300px;" required>
				</td>
			</tr>
			<tr>
				<th><label for="password">密碼</label></th>
				<td>
					<input type="text" name='password' style="width:300px;" id="password">(若留白則設為123456)
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="submit" class="btn btn-primary" value="送出">
				</td>
			</tr>
		</table>
	</div>
</form>
</body>
</html>
@stop