@extends('admin.main')
@section('app-content')
<style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
</style>
<body style="padding: 15px;">
    <h1>{{ $user->name }}{{ $logType=='Warned' ? '曾被警示':'曾被封鎖' }}紀錄列表</h1>
    共 {{ $dataLog->count() }} 筆資料
    <table class='table table-bordered table-hover'>
        <tr>
            <td>{{ $logType=='Warned' ? '警示時間':'封鎖時間' }}</td>
            <td>原因</td>
            @if($logType=='Banned')
                <td>到期日</td>
            @endif
        </tr>
        @foreach($dataLog as $detail)
            <tr>
                <td>{{ $detail->created_at }}</td>
                <td>{{ $detail->reason }}</td>
                @if($logType=='Banned')
                <td>{{ $detail->expire_date }}</td>
                @endif
            </tr>
        @endforeach
    </table>
    {!! $dataLog->links('pagination::sg-pages') !!}
</body>
@stop