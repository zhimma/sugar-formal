@extends('admin.main')
@section('app-content')
<style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
</style>
<body style="padding: 15px;">
<h1>VVIP 待取消名單</h1>
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
        <th>類型</th>
        <th>訂單編號</th>
		<th>待刷退金額</th>
        <th>動作</th>
	</tr>
    @forelse($list as $item)
        @if(!$item->user)
            <tr>
                <td>{{ $item->member_id }}</td>
                <td colspan="4">會員資料已刪除</td>
            </tr>
            @continue
        @endif
        <tr>
            <td>{{ $item->user->id }}</td>
            <td>{{ $item->user->email }}</td>
            <td>{{ $item->user->name }}</td>
            <td>{{ $item->service_name }}</td>
            <td>{{ $item->order_id }}</td>
            <td>{{ $item->refund_amount }}</td>
            <td>
                <form action="{{ route('users/VVIP_cancellation/save') }}" method="post">
                    @csrf
                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                    <input type="submit" class="btn btn-success" value="已完成取消">
                </form>
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