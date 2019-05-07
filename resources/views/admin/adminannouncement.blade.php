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
                    <td style="word-break: break-all; width: 50%;">{{ $a->content }}</td>
                    <td>@if($a->en_group == 1) 男 @else 女 @endif</td>
                    <td>{{ $a->sequence }}</td>
                    <td class="created_at">{{ $a->created_at }}</td>
                    <td class="updated_at">{{ $a->updated_at }}</td>
                    <td>
                        <a class='text-white btn btn-primary' href="{{ route('admin/announcement/edit', $a->id) }}">修改</a>
                        <a class='text-white btn btn-danger' href="#" onclick="deleteAnnounce( {{ $a->id }} )">刪除</a>
                    </td>
                </tr>
            @endforeach
        </table>
    <a href="{{ route('admin/announcement/new') }}" class='new text-white btn btn-success'>新增公告</a>
</body>
<script>
    function deleteAnnounce(id) {
        let c = confirm('確定要刪除這則公告？');
        if(c === true){
            window.location = "{{ route('admin/announcement/delete') }}/" + id;
        }
        else{
            return 0;
        }
    }
    function setForm(td, type) {
        console.log(type);
        if(type === 'edit'){
            let type = td.getElementsByClassName('type')[0];
            type.value = "edit";
        }
        else if(type === 'delete'){
            let type = td.getElementsByClassName('type')[0];
            type.value = "delete";
        }
    }
    function submitForm() {
        var allInputs = myForm.getElementsByTagName('input');

        for (var i = 0; i < allInputs.length; i++) {
            var input = allInputs[i];

            if (input.name && !input.value) {
                input.name = '';
            }
        }
        return true;
    }
</script>
@stop
