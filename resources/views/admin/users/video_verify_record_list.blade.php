@extends('admin.main')
@section('app-content')
    <head>
        <link href="//vjs.zencdn.net/7.3.0/video-js.min.css" rel="stylesheet">
        <script src="//vjs.zencdn.net/7.3.0/video.min.js"></script>
    </head>
    <body style="padding: 15px;">
        <h1>視訊錄影影片紀錄</h1>
        <br>
        <table class='table table-bordered table-hover'>
            <tr>
                <td>名字</td>
                <td>Email</td>
                <td>驗證原因</td>
                <td>視訊紀錄</td>
                <td>最新視訊時間</td>
                <td>功能</td>
            </tr>
            @foreach($user_video_verify_record as $record)
                <tr>
                    <td
                        @if($record->engroup == 1)
                            style="color:blue"
                        @elseif($record->engroup == 2)
                            style="color:red"
                        @endif
                    >
                        {{$record->name}}
                    </td>
                    <td>
                        <a href="/admin/users/advInfo/{{$record->id}}" target="_blank">{{$record->email}}</a>
                    </td>
                    <td>
                        
                        {{$record->id}}
                        @if($record->warned_users->video_auth ?? false)
                            站方警示
                        @else
                            主動申請
                        @endif
                    </td>
                    <td>
                        @if($record->video_verify_record->count())
                        <a class='text-white btn btn-success' href="{{route('users/video_verify_record') . '?user_id=' . $record->id}}" target="_blank">查看</a>
                        @endif
                    </td>
                    <td>{{$record->video_verify_record->first()?->created_at}}</td>
                    <td>
                        @if($record->video_verify_auth_status == 1)
                            已完成視訊驗證
                        @else
                            @if(($record->backend_user_details->first()->has_upload_video_verify ?? false) == 1)
                                <form action="{{route('users/video_verify_record_pass')}}" method="POST" style="display:inline">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="user_id" value="{{$record->id}}" />
                                    <input type="submit" class="btn btn-primary" value="通過">
                                </form>
                                <form action="{{route('users/video_verify_record_fail')}}" method="POST" style="display:inline">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="user_id" value="{{$record->id}}" />
                                    <input type="submit" class="btn btn-danger" value="不通過">
                                </form>
                            @else
                                等待會員重錄
                            @endif
                        @endif
                        <a href="/admin/users/message/to/{{$record->id}}" target="_blank" class="btn btn-dark">發送站長訊息</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </body>
@stop