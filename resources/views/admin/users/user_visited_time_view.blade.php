@extends('admin.main')
@section('app-content')
    <body style="padding: 15px;">
        <h1>拜訪停留時間</h1>
        <br>
        <table class='table table-bordered table-hover'>
            <tr>
                <td>使用者ID</td>
                <td>訪問ID</td>
                <td>拜訪停留時間(秒)</td>
            </tr>
            @foreach($user_visited_record as $record)
                <tr>
                    <td>{{$record->member_id}}</td>
                    <td>{{$record->visited_id}}</td>
                    <td>{{$record->visited_time}} 秒</td>
                </tr>
            @endforeach
        </table>
        {!! $user_visited_record->appends(request()->input())->links('pagination::sg-pages') !!}
    </body>
@stop