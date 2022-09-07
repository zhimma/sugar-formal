@extends('admin.main')
@section('app-content')
<style>
.table > tbody > tr > td, .table > tbody > tr > th{
    vertical-align: middle;
}
h3{
    text-align: left;
}
</style>
<body style="padding: 15px;">
    <h1>頁面停留時間-頁面名稱設定</h1>
    <br>
    <h3>頁面名稱列表</h3>
    <table class="table-bordered table-hover center-block" style="width: 100%;" id="table">
        <tr>
            <th class="text-center">網址</th>
            <th class="text-center">名稱</th>
            <th class="text-center">操作</th>
        </tr>
        @foreach($row_list as $key =>$row)
            <tr class="template">
                <td><a href="{{ $row->url }}" target="_blank">{{ $row->url }}</a></td>
                <td width="20%">{{ $row->name }}</td>
                <td width="20%">
                    <div style="display: inline-flex;">
                        <a class='text-white btn btn-primary' href="{{ $row->id?route('admin/stay_online_record_page_name_form',['id'=>$row->id]):route('admin/stay_online_record_page_name_switch',['url'=>$row->url])}}">修改</a>
                        &nbsp;&nbsp;&nbsp;
                        @if($row->id && $row->name)
                        <a class='text-white btn btn-danger' href="{{ route('admin/stay_online_record_page_name_delete',['id'=>$row->id])}}" onclick="return deleteRow();">刪除</a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
    <br>
    <a href="{{ route('admin/user_page_online_time_view')}}" class="text-white btn btn-primary">返回</a>
    <a href="{{ route('admin/stay_online_record_page_name_form') }}" class='new text-white btn btn-success'>新增頁面名稱</a>
</body>
<script>
    function deleteRow() {
        let c = confirm('確定要刪除此頁面名稱？');
        return c;
    }
</script>
@stop
