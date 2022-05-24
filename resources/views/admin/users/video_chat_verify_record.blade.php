@extends('admin.main')
@section('app-content')
    <head>
        <link href="//vjs.zencdn.net/7.3.0/video-js.min.css" rel="stylesheet">
        <script src="//vjs.zencdn.net/7.3.0/video.min.js"></script>
    </head>
    <body style="padding: 15px;">
        <h1>視訊驗證紀錄</h1>
        <br>
        <h2>使用者</h2>
        <div id="profile-video">
            <video id="record-video" class="video-js vjs-big-play-centered"><video>
        </div>
        <h2>管理員</h2>
        <div id="profile-video2">
            <video id="record-video2" class="video-js vjs-big-play-centered"><video>
        </div>
    </body>
    <script>
        const player = videojs('record-video',{
            sources:[{ src: "{{asset('storage/' . $record->user_video)}}"}],
            loop:true,
            muted:true,
            width:"600",
            height:"400px",
            controls:true
        });
        const player2 = videojs('record-video2',{
            sources:[{ src: "{{asset('storage/' . $record->admin_video)}}"}],
            loop:true,
            muted:true,
            width:"600",
            height:"400px",
            controls:true
        });
    </script>
@stop