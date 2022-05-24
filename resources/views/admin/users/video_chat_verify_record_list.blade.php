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
            @foreach($user_video_verify_record as $record)
                <tr>
                    <td>
                        <h3>{{$record->id}}</h3>
                    </td>
                    <td>
                        <a href="{{route('users/video_chat_verify_record') . '?verify_record_id=' . $record->id}}">
                            <h3>{{$record->user_id}}</h3>
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
    </body>
@stop