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
    <h3>男性公告</h3>
    <table class="table-bordered table-hover center-block text-center" style="width: 100%;" id="table">
        <tr>
            <th width="40%" class="text-center">內容</th>
            <th width="8%" class="text-center">排序(預設為1)</th>
            <th width="8%" class="text-center">普通會員/VIP</th>
            <th width="10%" class="text-center">在第幾次login時才跳出此公告</th>
            <th width="10%" class="text-center">建立時間</th>
            <th width="10%" class="text-center">更新時間</th>
            <th width="14%" class="text-center">操作</th>
        </tr>
        @foreach($announce as $a)
            @if($a->en_group == 1)
                <tr class="template">
                    <td style="word-break: break-all; width: 50%;">{!! nl2br($a->content) !!}</td>
                    <td>{{ $a->sequence }}</td>
                    <td>@if($a->isVip==0)普通會員 @elseif($a->isVip==1)VIP @endif
                        @if($a->is_new_7==1) <div> (未滿7天) </div> @endif
                    </td>
                    <td>{{ $a->login_times_alert }}</td>
                    <td class="created_at">{{ $a->created_at }}</td>
                    <td class="updated_at">{{ $a->updated_at }}</td>
                    <td>
                        <a class='text-white btn btn-info' href="{{ route('admin/announcement/read', $a->id) }}" target="_blank">不再顯示的會員</a>
                        <a class='text-white btn btn-primary' href="{{ route('admin/announcement/edit', $a->id) }}">修改</a>
                        <a class='text-white btn btn-danger' href="#" onclick="deleteAnnounce( {{ $a->id }} )">刪除</a>
                    </td>
                </tr>
            @endif
        @endforeach
    </table>
    <h3>女性公告</h3>
    <table class="table-bordered table-hover center-block text-center" style="width: 100%;" id="table">
        <tr>
            <th width="40%" class="text-center">內容</th>
            <th width="8%" class="text-center">排序(預設為1)</th>
            <th width="8%" class="text-center">普通會員/VIP</th>
            <th width="10%" class="text-center">在第幾次login時才跳出此公告</th>
            <th width="10%" class="text-center">建立時間</th>
            <th width="10%" class="text-center">更新時間</th>
            <th width="14%" class="text-center">操作</th>
        </tr>
        @foreach($announce as $a)
            @if($a->en_group == 2)
                <tr class="template">
                    <td style="word-break: break-all; width: 50%;">{!! nl2br($a->content) !!}</td>
                    <td>{{ $a->sequence }}</td>
                    <td>@if($a->isVip==0)普通會員 @elseif($a->isVip==1)VIP @endif
                    @if($a->is_new_7==1) <div> (未滿7天) </div> @endif
                    </td>
                    <td>{{ $a->login_times_alert }}</td>
                    <td class="created_at">{{ $a->created_at }}</td>
                    <td class="updated_at">{{ $a->updated_at }}</td>
                    <td>
                        <a class='text-white btn btn-info' href="{{ route('admin/announcement/read', $a->id) }}" target="_blank">不再顯示的會員</a>
                        <a class='text-white btn btn-primary' href="{{ route('admin/announcement/edit', $a->id) }}">修改</a>
                        <a class='text-white btn btn-danger' href="#" onclick="deleteAnnounce( {{ $a->id }} )">刪除</a>
                    </td>
                </tr>
            @endif
        @endforeach
    </table>
    <a href="{{ route('admin/announcement/new/GET') }}" class='new text-white btn btn-success'>新增公告</a>
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
