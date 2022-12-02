@extends('admin.main')
@section('app-content')
    <body style="padding: 15px;">
    <h1>等待更多資料會員列表</h1>
        <table class="table-hover table table-bordered">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>暱稱</th>
                    <th>被列入等待時間</th>
                    <th>哪位站長</th>
                </tr>
            </thead>
            <tbody>
                @foreach($check_extend_list as $check_extend_data)
                    <tr>
                        <td>{{$check_extend_data->user->email}}</td>
                        <td>{{$check_extend_data->user->name}}</td>
                        @if($check_extend_data->check_extend_admin_action_log->first() ?? false)
                            <td>{{$check_extend_data->check_extend_admin_action_log->first()->created_at}}</td>
                            <td>{{$check_extend_data->check_extend_admin_action_log->first()->operator_user->email}}</td>
                        @else
                            <td>未紀錄</td>
                            <td>未紀錄</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        <table>
    </body>
@stop