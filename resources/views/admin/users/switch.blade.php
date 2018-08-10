@extends('admin.main')
@section('app-content')
<style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
</style>
<body style="padding: 15px;">
<h1>切換會員身份</h1>
<form method="POST" action="{{ route('users/switch/search') }}" class="search_form">
	{!! csrf_field() !!}
	<table class="table table-bordered table-hover" style="width: 50%">
        <tr>
            <th>
                <label for="email" class="">Email</label>
            </th>
            <td>
                <input type="text" name='email' class="form-control" style="width:300px;" id="email">
            </td>
        </tr>
        <tr>
            <th>
                <label for="name" class="">暱稱</label>
            </th>
            <td>
                <input type="text" name='name' class="form-control" style="width:300px;" id="name">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <button type="button" class="btn btn-primary" onclick="$('.search_form').submit()">送出</button>
            </td>
        </tr>
    </table>
</form><br>
@if(isset($user))
<table class='table table-hover table-bordered'>
	<tr>
		<td>Email</td>
		<td>名稱</td>
		<td>切換</td>
	</tr>
	<tr>
		<td>{{ $user->email }}</td>
		<td>{{ $user->name }}</td>
		<td>
            <a href="{{ route('users/switch/to', $user->id) }}" class="btn btn-success">切換</a>
		</td>
	</tr>
</table>
@endif
</body>
</html>
@stop