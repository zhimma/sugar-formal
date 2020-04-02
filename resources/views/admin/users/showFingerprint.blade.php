@extends('admin.main')
@section('app-content')
<style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
</style>
<body style="padding: 15px;">
<h1>指紋 Hash 值：{{ $fingerprint }}</h1>
共 {{ $users->count() }} 筆資料
<table class='table table-bordered table-hover'>
    <tr>
        <td>Email</td>
        <td>封鎖方式</td>
        <td>封鎖日期</td>
        <td>帳號建立時間</td>
        <td>最近上站時間</td>
        <td>暱稱</td>
        <td>標題</td>
        <td>被檢舉次數</td>
        <td>操作</td>
    </tr>
    @forelse($users as $user)
        @php
            $user['data'] = \App\Models\User::findById($user['user_id']);
            $user['count'] = \App\Services\AdminService::countReported($user['user_id']);
        @endphp
        @if(isset($user['data']))
            <tr>
                <td><a href="{{ route('users/advInfo', $user['user_id']) }}" target="_blank">{{ $user['data']->email }}</a></td>
                <td>{{ $user['type'] }}</td>
                <td>{{ $user['banned_at'] }}</td>
                <td>{{ $user['data']->created_at }}</td>
                <td>{{ $user['data']->last_login }}</td>
                <td>{{ $user['data']->name }}</td>
                <td>{{ $user['data']->title }}</td>
                <td>{{ $user['count'] }}</td>
                <td>
                    @if($user['type'] == "")
                        <form action="{{ route('banningUserImplicitly') }}" method="POST">
                            {!! csrf_field() !!}
                            <input type="hidden" value="{{ $user['user_id'] }}" name="user_id">
                            <input type="hidden" value="{{ $user['fp'] }}" name="fp">
                            <input type="hidden" value="{{ url()->full() }}" name="page">
                            <button type="submit" class='btn btn-info'>隱性封鎖</button>
                        </form>
                        <form action="/admin/users/toggleUserBlock" method="POST">
                            {!! csrf_field() !!}
                            <input type="hidden" value="{{ $user['user_id'] }}" name="user_id">
                            <input type="hidden" value="{{ url()->full() }}" name="page">
                            <input type="hidden" name="days" value="X">
                            <button type="submit" class='btn btn-danger'>永久封鎖</button>
                        </form>
                    @else
                        <form action="{{ route('unbanAll') }}" method="POST">
                            {!! csrf_field() !!}
                            <input type="hidden" value="{{ $user['user_id'] }}" name="user_id">
                            <input type="hidden" value="{{ url()->full() }}" name="page">
                            <button type="submit" class='btn btn-success'>解除封鎖</button>
                        </form>
                    @endif
                </td>
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
</body>
@stop