@extends('admin.main')
@section('app-content')
<style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
</style>
<body style="padding: 15px;">
<h1>VVIP 保證金清單</h1>
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
<table class='table table-hover table-bordered'>
	<tr>
        <th>ID</th>
		<th>Email</th>
		<th>名稱</th>
		<th>額度</th>
        <th>動作</th>
	</tr>
    @forelse($list as $deposit)
	<tr>
        <td>{{ $deposit->user->id }}</td>
		<td>{{ $deposit->user->email }}</td>
		<td>{{ $deposit->user->name }}</td>
        <td>{{ $deposit->balance }}</td>
		<td>
            <a href="{{ route('users/VVIP_margin_deposit/edit', $user->id) }}" class="btn btn-success">修改</a>
		</td>
	</tr>
    @empty
    <tr>
        無資料
    </tr>
    @endforelse
</table>
</body>
</html>
@stop