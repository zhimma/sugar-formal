@extends('admin.main')
@section('app-content')
    <body style="padding: 15px;">
        <h1>使用者紀錄查看</h1>
        <br>
        <table class='table table-bordered table-hover'>
            <tr>
                <td>使用者ID</td>
                <td>註冊花費時間(秒)</td>
            </tr>
            @foreach($user_record as $record)
                <tr>
                    <td>{{$record->user_id}}</td>
                    <td>{{$record->cost_time_of_registering}} 秒</td>
                </tr>
            @endforeach
        </table>
    </body>
@stop