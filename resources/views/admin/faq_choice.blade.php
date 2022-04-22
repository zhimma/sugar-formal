@extends('admin.main')
@section('app-content')
<link rel="stylesheet" href="{{asset('/css/faq_admin_common.css')}}">
<style>
.table > tbody > tr > td, .table > tbody > tr > th{
    vertical-align: middle;
}
h3{
    text-align: left;
}
</style>
<body style="padding: 15px;">
    <h1>FAQ選項</h1>
    <h2>題目「{{$service->getQuertionTitleLayout()}}」的選項</h2>
<a href="{{ route('admin/faq_choice/new/GET',$service->question_entry()->id) }}" class='new text-white btn btn-success'>新增選項</a>    
    <table class="table-bordered table-hover center-block text-center" style="width: 100%;" id="table">
        <tr>
            <th width="40%" class="text-center">名稱</th>
            <th width="8%" class="text-center">正解</th>
            <th width="10%" class="text-center">建立時間</th>
            <th width="10%" class="text-center">更新時間</th>
            <th width="14%" class="text-center">操作</th>
        </tr>
        @foreach($entry_list as $entry)
                <tr class="template">
                    <td style="word-break: break-all; width: 50%;">{{$entry->name}}</td>
                    <td>{{$service->getIsAnswerWord($entry->is_answer)}}</td>
                    <td class="created_at">{{ $entry->created_at }}</td>
                    <td class="updated_at">{{ $entry->updated_at }}</td>
                    <td>
                        <a class='text-white btn btn-primary' href="{{ route('admin/faq_choice/edit', $entry->id) }}">修改</a>
                        <a class='text-white btn btn-danger' href="#" onclick="deleteRow( {{ $entry->id }} )">刪除</a>
                    </td>
                </tr>
        @endforeach
    </table> 
    <a href="{{ route('admin/faq') }}{{$service->getEngroupVipQueryString('?')}}" class="text-white btn btn-primary">返回題目</a>    
</body>
<script>
    function deleteRow(id) {
        let topic_title = $('#main > h1:first').html();
        if(topic_title==undefined || topic_title==null) topic_title = '資料';
        let c = confirm('確定要刪除這筆'+topic_title+'？');
        if(c === true){
            window.location = "{{ route('admin/faq_choice/delete') }}/" + id;
        }
        else{
            return 0;
        }
    } 
</script>
@stop
