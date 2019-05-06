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
    <h1>新增站長公告</h1>
    <form action="{{ route('admin/announcement/process') }}" id='message' method='POST' onsubmit="return submitForm();">
        {!! csrf_field() !!}
        <table class="table-bordered table-hover center-block text-center" id="table">
            <tr>
                <th class="text-center">內容</th>
                <th class="text-center">性別</th>
                <th class="text-center">排序(預設為1)</th>
            </tr>
            <tr class="template">
                <td>
                    <textarea name="content_word" class="form-control" cols="80" rows="5" class="content">公告內容</textarea>
                </td>
                <td>
                    <select name="en_group" id="" class="en_group">
                        <option value="1">男</option>
                        <option value="2">女</option>
                    </select>
                </td>
                <td>
                    <input type="number" value="1" name="sequence" min="1">
                </td>
            </tr>
        </table>
        <input type="button" value="送出" class='new text-white btn btn-success'>
    </form>
</body>
@stop
