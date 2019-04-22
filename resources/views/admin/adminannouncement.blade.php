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
    <h1>站長公告</h1>
    <table class="table-bordered table-hover center-block text-center" id="table">
        <tr>
            <th class="text-center">內容</th>
            <th class="text-center">性別</th>
            <th class="text-center">排序(預設為1)</th>
            <th class="text-center">建立時間</th>
            <th class="text-center">更新時間</th>
            <th class="text-center">操作</th>
        </tr>
        @foreach($announce as $a)
            <tr class="template">
                <form action="{{ route('admin/announcement/save') }}" id='message' method='POST'>
                <td>
                    <textarea name="content" class="form-control" cols="80" rows="5">{{ $a->content }}</textarea>
                </td>
                <td>
                    <select name="en_group" id="">
                        <option value="1" @if($a->en_group == 1) selected @endif>男</option>
                        <option value="2" @if($a->en_group == 2) selected @endif>女</option>
                    </select>
                </td>
                <td>
                    <input type="number" value="{{ $a->sequence }}" name="sequence">
                </td>
                <td>{{ $a->created_at }}</td>
                <td>{{ $a->updated_at }}</td>
                <td>
                    <input type="hidden" value="{{ $a->id }}">
                    <button type='submit' class='text-white btn btn-primary' value="edit">修改</button>
                    <button type='submit' class='text-white btn btn-danger' value="delete">刪除</button>
                </td>
                </form>
            </tr>
        @endforeach
    </table>
    <button onclick="newRow();" class='text-white btn btn-success'>新增一筆公告</button>
</body>
<script>
    function newRow(){
        let $tr    = $('#table tr:last');
        let $clone = $tr.clone();
        $tr.after($clone);
        $clone.find("form").reset();
    }
</script>
@stop
