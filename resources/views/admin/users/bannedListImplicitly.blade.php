@extends('admin.main')
@section('app-content')
<style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
</style>
<body style="padding: 15px;">
<h1>隱性封鎖清單</h1>
共 {{ $users->count() }} 筆資料
<table class='table table-bordered table-hover'>
	<tr>
        <td>Hash 值</td>
		<td>Email</td>
		<td>封鎖方式</td>
        <td>封鎖日期</td>
        <td>帳號建立時間</td>
        <td>最近上站時間</td>
        <td>暱稱</td>
        <td>標題</td>
        <td>被檢舉次數</td>
	</tr>
	@forelse($users as $user)
        @php
            $user->data = User::findById($r->user_id);
            $user->count = $this->admin->countReported($r->user_id);
        @endphp
        @if(isset($user->data))
            <tr>
                <td>{{ isset($user->fp) ? $user->fp->fp : '無資料' }}</td>
                <td><a href="advInfo/{{ $user->data->user_id }}" target="_blank">{{ $user->data->email }}</a></td>
                <td>{{ $user->type }}</td>
                <td>{{ $user->banned_at }}</td>
                <td>{{ $user->data->created_at }}</td>
                <td>{{ $user->data->last_login }}</td>
                <td>{{ $user->data->name }}</td>
                <td>{{ $user->data->title }}</td>
                <td>{{ $user->count }}</td>
            </tr>
        @else
            <tr>
                <td colspan="9">無會員資料</td>
            </tr>
        @endif
    @empty
    <tr>
        找不到資料
    </tr>
    @endforelse
</table>
{!! $users->render() !!}
</body>
@stop