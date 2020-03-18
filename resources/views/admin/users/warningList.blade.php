@extends('admin.main')
@section('app-content')
    <style>
        .table > tbody > tr > td, .table > tbody > tr > th{
            vertical-align: middle;
        }
    </style>
    <body style="padding: 15px;">
    <h1>警示名單</h1>
    共 {{ $users->total() }} 筆資料
    <table class='table table-bordered table-hover'>
        <tr>
            <td>永久封鎖會員 ID</td>
            <td>永久封鎖會員 Email(暱稱)</td>
            <td>最新上站時間</td>
            <td>近三天發文數</td>
            <td>比對目標</td>
            <td>封鎖日期</td>
        </tr>
        @forelse($users as $user)
            <tr>
                <td>{{ $user->user_id }}</td>
                <td><a href="advInfo/{{ $user->user_id }}" target="_blank">{{ $user->email }}({{ $user->name }})</a></td>
                <td>{{ $user->last_login }}</td>
                <td>{{ $user->count }}</td>
                <td>
                    @if(isset($user->target))
                        <a href="advInfo/{{ $user->target->id }}" target="_blank">{{ $user->target->name }}</a>
                    @else
                        {{ logger('Empty data found in warningList, user id: ' . $user->user_id) }}
                    @endif
                </td>
                <td>{{ $user->created_at }}</td>
            </tr>
        @empty
            <tr>
                找不到資料
            </tr>
        @endforelse
    </table>
    {!! $users->links() !!}
    </body>
@stop