@extends('admin.main')
@section('app-content')
<style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
</style>
<body style="padding: 15px;">
<h1>指紋 Hash 值：{{ $fingerprint }}</h1>
@if($isFingerprintBanned)
    <form action="{{ route('unbanFingerprint') }}" method="POST">
        {!! csrf_field() !!}
        <input type="hidden" value="{{ $fingerprint }}" name="fp">
        <button type="submit" class='btn text-white btn-success'>解除封鎖此指紋</button>
    </form>
@else
    <form action="{{ route('banFingerprint') }}" method="POST">
        {!! csrf_field() !!}
        <input type="hidden" value="{{ $fingerprint }}" name="fp">
        <button type="submit" class='btn btn-info'>隱性封鎖此指紋</button>
    </form>
@endif
共 {{ $users->count() }} 筆資料
<table class='table table-bordered table-hover'>
    <tr>
        <td>Email
            @if(request()->orderBy == 'email' && request()->order == 'asc')
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'email', 'order' => 'desc']) }}">▲</a>
            @else
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'email', 'order' => 'asc']) }}">▼</a>
            @endif
        </td>
        <td>封鎖方式
            @if(request()->orderBy == 'type' && request()->order == 'asc')
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'type', 'order' => 'desc']) }}">▲</a>
            @else
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'type', 'order' => 'asc']) }}">▼</a>
            @endif
        </td>
        <td>封鎖日期
            @if(request()->orderBy == 'banned_at' && request()->order == 'asc')
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'banned_at', 'order' => 'desc']) }}">▲</a>
            @else
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'banned_at', 'order' => 'asc']) }}">▼</a>
            @endif
        </td>
        <td>帳號建立時間
            @if(request()->orderBy == 'created_at' && request()->order == 'asc')
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'created_at', 'order' => 'desc']) }}">▲</a>
            @else
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'created_at', 'order' => 'asc']) }}">▼</a>
            @endif
        </td>
        <td>最近上站時間
            @if(request()->orderBy == 'last_login' && request()->order == 'asc')
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'last_login', 'order' => 'desc']) }}">▲</a>
            @else
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'last_login', 'order' => 'asc']) }}">▼</a>
            @endif
        </td>
        <td>暱稱
            @if(request()->orderBy == 'name' && request()->order == 'asc')
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'name', 'order' => 'desc']) }}">▲</a>
            @else
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'name', 'order' => 'asc']) }}">▼</a>
            @endif
        </td>
        <td>標題
            @if(request()->orderBy == 'title' && request()->order == 'asc')
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'title', 'order' => 'desc']) }}">▲</a>
            @else
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'title', 'order' => 'asc']) }}">▼</a>
            @endif
        </td>
        <td>被檢舉次數</td>
        <td>操作</td>
    </tr>
    @forelse($users as $user)
        @if(isset($user['email']))
            @php
                $user['count'] = \App\Services\AdminService::countReported($user['user_id']);
                $user['fp'] = isset($user['fp']) ? ($user['fp'] != '' ? $user['fp'] : '無資料') : '無資料';
            @endphp
            <tr>
                <td><a @if($user['engroup'] == '2') style="color: #F00;" @else  style="color: #000fff;" @endif href="{{ route('users/advInfo', $user['user_id']) }}" target="_blank">{{ $user['email'] }}</a></td>
                <td>{{ $user['type'] }}</td>
                <td>{{ $user['banned_at'] }}</td>
                <td>{{ $user['created_at'] }}</td>
                <td>{{ $user['last_login'] }}</td>
                <td>{{ $user['name'] }}</td>
                <td>{{ $user['title'] }}</td>
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