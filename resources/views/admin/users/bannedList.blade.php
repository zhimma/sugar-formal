@extends('admin.main')
@section('app-content')
<style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
</style>
<body style="padding: 15px;">
<h1>會員封鎖清單</h1>
共 {{ $list->total() }} 筆記錄
<table class='table table-bordered table-hover'>
	<tr>
        <td>會員ID</td>
		<td>Email</td>
		<td>名稱</td>
        <td>封鎖時間</td>
        <td>傳訊給誰</td>
        <td>封鎖原因</td>
        <td>訊息內容</td>
        <td>到期日(自動解除)</td>
		<td>解除封鎖</td>
	</tr>
	@forelse($list as $user)
    <tr>
        <td>{{ $user->member_id }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->created_at }}</td>
        <td>{{ $user->recipient_name }}</td>
        <td>{{ $user->reason }}</td>
        <td>{{ $user->message_content }}</td>
        <td>{{ $user->expire_date }}</td>
        <td>
            <form action="userUnblock" method="POST">{!! csrf_field() !!}
                <input type="hidden" value="{{ $user->member_id }}" name="user_id">
                <button type="submit" class='text-white btn btn-success'>解除</button>
            </form>
        </td>
    </tr>
    @empty
    <tr>
        找不到資料
    </tr>
    @endforelse
</table>
{{ $list->links() }}
</body>
</html>
@stop