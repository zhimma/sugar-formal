@extends('admin.main')
@section('app-content')
<style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
</style>
<body style="padding: 15px;">
<h1>隱性封鎖清單</h1>
共 {{ $users->total() }} 筆資料
<table class='table table-bordered table-hover'>
	<tr>
        <td>會員ID</td>
		<td>Email(暱稱)</td>
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
            @foreach($user->target as $t)
                @if(isset($t))
                    <a href="advInfo/{{ $t->id }}" target="_blank">{{ $t->name }}</a>
                @else
                    {{ logger('Empty data found in warningList, user id: ' . $user->user_id) }}
                @endif
                @if(!$loop->last)
                    ,
                @endif
            @endforeach
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