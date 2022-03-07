@extends('admin.main')
@section('app-content')
    <body style="padding: 15px;">
        <h1>寄退信Log查詢</h1>
        <br>
        <div>
            <span>開始日期</span>
            <input class='datepicker' id='start_date'></input>
        </div>
        <div>
            <span>結束日期</span>
            <input class='datepicker' id='end_date'></input>
        </div>
        <br>
        <button type="button" id='search'>查詢</button>
        <br>
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
    <script>

        $(function(){
            $(".datepicker").datepicker();
        });

        $("#search").click(function(){
            url = "/admin/maillog?start_date=" + $('input[id=start_date]').val() + "&end_date=" + $('input[id=end_date]').val();
            window.location.href = url;
        });

    </script>
@stop