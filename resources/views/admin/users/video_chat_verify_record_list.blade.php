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
                <td><h3>暱稱</h3></td>
                <td><h3>Email</h3></td>
                <td><h3>最新視訊時間</h3></td>
                <td><h3>查看</h3></td>
            </tr>
            @foreach($user_video_verify_record as $record)
                <tr>
                    <td><h3>{{$record->name}}</h3></td>
                    <td><h3>{{$record->email}}</h3></td>
                    <td><h3>{{$record->created_at}}</h3></td>
                    <td>
                        <a href="{{route('users/video_chat_verify_record') . '?user_id=' . $record->user_id}}">
                            <h3>查看</h3>
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
    </body>
@stop