@extends('admin.main')
@section('app-content')
    <body style="padding: 15px;">
    <h1>等待更多資料(發回)</h1>
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
                        <td>{{$check_extend_data->created_at}}</td>
                        <td>{{$check_extend_data->operator_user->email}}</td>
                    </tr>
                @endforeach
            </tbody>
        <table>
    </body>
@stop