@extends('admin.main')
@section('app-content')
    <head>
        <link href="//vjs.zencdn.net/7.3.0/video-js.min.css" rel="stylesheet">
        <script src="//vjs.zencdn.net/7.3.0/video.min.js"></script>
    </head>
    <body style="padding: 15px;">
        <h1>視訊驗證紀錄</h1>
        <br>
        <table class='table table-bordered table-hover'>
            <tr>
                <td>暱稱</td>
                <td>Email</td>
                <td>最新視訊時間</td>
                <td>查看</td>
            </tr>
            @foreach($user_video_verify_record as $record)
                <tr>
                    <td>{{$record->name}}</td>
                    <td>{{$record->email}}</td>
                    <td>{{$record->created_at}}</td>
                    <td>
                        <a class='text-white btn btn-success' href="{{route('users/video_chat_verify_record') . '?user_id=' . $record->user_id}}">查看</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </body>
@stop