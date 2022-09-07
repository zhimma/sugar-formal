@extends('admin.main')
@section('app-content')
    <body style="padding: 15px;">
        <h1>上線停留時間</h1>
        <br>
        <table class='table table-bordered table-hover'>
            <tr>
                <td>使用者ID</td>
                <td>暱稱</td>
                <td>信箱</td>
                <td>上線停留時間(秒)</td>
            </tr>
            @foreach($user_online_record as $record)
                <tr>
                    <td>{{$record->user?$record->user->id:null}}</td>
                    <td>{{$record->user?$record->user->name:null}}</td>
                    <td>{{$record->user?$record->user->email:null}}</td>
                    <td>{{$record->stay_online_time}} 秒</td>
                </tr>
            @endforeach
        </table>
        {!! $user_online_record->appends(request()->input())->links('pagination::sg-pages') !!}
    </body>
@stop