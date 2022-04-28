@extends('admin.main')
@section('app-content')
    <body style="padding: 15px;">
        <h1>停留時間</h1>
        <br>
        <table class='table table-bordered table-hover'>
            <tr class='record_title'>
                <td>
                    <h3>註冊停留時間</h3>
                </td>
            </tr>
            <tr class='record_table' style='display:none'>
                <td>
                    <table class='table table-bordered table-hover'>
                        <tr>
                            <td>使用者ID</td>
                            <td>暱稱</td>
                            <td>信箱</td>
                            <td>註冊花費時間(秒)</td>
                        </tr>
                        @foreach($user_record as $record)
                            <tr>
                                <td>{{$record->user_id}}</td>
                                <td>{{$record->name}}</td>
                                <td>{{$record->email}}</td>
                                <td>{{$record->cost_time_of_registering}} 秒</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        </table>
    </body>
    <script>
        $('.record_title').on('click', function(){
            $(this).next().toggle();
        });
    </script>
@stop