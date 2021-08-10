@extends('admin.main')
@section('app-content')
<style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
</style>
<body style="padding: 15px;">
<h1>{{ $user->name }} 帳號登入紀錄</h1>
共 {{ $loginLog->count() }} 筆資料
<table class='table table-bordered table-hover'>
    <tr>
        <td>登入時間</td>
        <td>IP</td>
        <td>登入裝置</td>
    </tr>
    @foreach($loginLog as $detail)
        <tr>
            <td>{{ $detail->created_at }}</td>
            <td>{{ $detail->ip }}</td>
            <?php
                if(preg_match("/(iPod|iPhone)/", $detail->userAgent))
                    $device = '手機';
                else if(preg_match("/iPad/", $detail->userAgent))
                    $device = '平板';
                else if(preg_match("/android/i", $detail->userAgent))
                    $device = '手機';
                else
                    $device = '電腦';
            ?>
            <td>
                {{ $device }}
            </td>
        </tr>
    @endforeach
</table>
</body>
@stop