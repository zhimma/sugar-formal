@extends('admin.main')
@section('app-content')
    <body style="padding: 15px;">
        <h1>寄退信Log查詢</h1>
        <table class='table table-bordered table-hover'>
            @foreach($mail_log as $log)
                <tr>
                    <td>類型</td><td>{{$log->notificationtype}}</td>
                </tr>
                <tr>
                    <td>信件</td><td>{{json_encode($log->mail)}}</td>
                </tr>
                <tr>
                    <td>內容</td><td>{{json_encode($log->content)}}</td>
                </tr>
                <tr>
                    <td></td>
                </tr>
            @endforeach
        </table>
    </body>
@stop