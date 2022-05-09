@extends('admin.main')
@section('app-content')
    <body style="padding: 15px;">
        <h1>停留時間</h1>
        <br>
        <table class='table table-bordered table-hover'>
            <tr>
                <td>
                    <a href="{{route('admin/user_regist_time_view')}}">
                        <h3>註冊停留時間</h3>
                    </a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="{{route('admin/user_visited_time_view')}}">
                        <h3>拜訪停留時間</h3>
                    </a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="{{route('admin/user_online_time_view')}}">
                        <h3>上線停留時間</h3>
                    </a>
                </td>
            </tr>
        </table>
    </body>
@stop