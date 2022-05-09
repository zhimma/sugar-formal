@extends('admin.main')
@section('app-content')
    <body style="padding: 15px;">
        <h1>註冊停留時間</h1>
        <br>
        <table class='table table-bordered table-hover'>
            <tr>
                <td>使用者ID</td>
                <td>暱稱</td>
                <td>信箱</td>
                <td>註冊花費時間(秒)</td>
            </tr>
            @foreach($user_record as $record)
                <tr>
                    <td>{{$record->user_id}}</td>
                    <td>{{$record->name}}</td>
                    <td>{{$record->email}}</td>
                    <td>{{$record->cost_time_of_first_dataprofile}} 秒</td>
                </tr>
            @endforeach
        </table>
        {!! $user_record->appends(request()->input())->links('pagination::sg-pages') !!}
    </body>
@stop