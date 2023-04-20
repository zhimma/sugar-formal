@extends('admin.main')
@section('app-content')
    <head>
        <link href="//vjs.zencdn.net/7.3.0/video-js.min.css" rel="stylesheet">
        <script src="//vjs.zencdn.net/7.3.0/video.min.js"></script>
    </head>
    <body style="padding: 15px;">
        <h1>視訊錄影紀錄</h1>
        <br>
        <table class='table table-bordered table-hover'>
            <tr>
                <td><h3>使用者</h3></td>
                <td><h3>視訊時間</h3></td>
            </tr>
            @foreach($record as $r)
            <tr>
                <td>
                    <div id="profile-video-{{$r->id}}">
                        <video id="record-video-{{$r->id}}" class="video-js vjs-big-play-centered"><video>
                    </div>
                </td>
                <td>{{$r->created_at}}</td>
            </tr>
            <script>
                const player_{{$r->id}} = videojs('record-video-{{$r->id}}',{
                    sources:[{ src: "{{asset('storage/' . $r->user_video)}}"}],
                    loop:false,
                    muted:false,
                    width:"600",
                    height:"400px",
                    controls:true
                });
            </script>
            @endforeach
        </table>
    </body>
@stop