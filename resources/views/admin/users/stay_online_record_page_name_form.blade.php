@extends('admin.main')
@section('app-content')
    <style>
        .table > tbody > tr > td, .table > tbody > tr > th{
            vertical-align: middle;
        }
        h3{
            text-align: left;
        }
                
        table th, table td {
            padding: 20px;
        }  

        form input {padding:5px;}
    </style>
    <body style="padding: 15px;">
    <h1>{{request()->id?'修改':'新增'}}頁面名稱</h1>
    <form  method="post">
        {!! csrf_field() !!}
        <table class="table-bordered table-hover center-block text-center" style="width: 70%;" id="table">
            <tr>
                <th class="text-center">網頁</th>
                <td style="text-align: left;">
                    <input name="url" placeholder="請輸入網址" style="width: 100%;" value="{{$entry->url}}">
                </td>
            </tr>
            <tr>
                <th class="text-center">頁面名稱</th>
                <td style="text-align: left;">
                    <div>
                        <input name="name" placeholder="請輸入頁面名稱" style="width: 100%;" value="{{$entry->name}}">
                    </div>
                </td>
            </tr>
            <tr>
                <th class="text-center">操作</th>
                <td>
                    <a href="{{ request()->rtn=='record'?route('admin/user_page_online_time_view'):route('admin/stay_online_record_page_name_view')}}" class="text-white btn btn-primary">返回</a>
                    <input type="submit" class='text-white btn btn-success' value="送出">
                </td>
            </tr>
        </table>
    </form>
    </body>
@stop
