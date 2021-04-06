@extends('admin.main')
@section('app-content')
<style>
.table > tbody > tr > td, .table > tbody > tr > th{
    vertical-align: middle;
}
.table > tbody > tr > th{
    text-align: center;
}
</style>
<body style="padding: 15px;">
<h1>可疑名單列表</h1>
{{--@php--}}
{{--print_r($suspiciousUser);--}}
{{--@endphp--}}
@if(isset($suspiciousUser))
<div>
    <table class="table-hover table table-bordered">
        <tr>
            <td width="12%">標題(一句話形容自己）</td>
            <td width="12%">email</td>
            <td width="12%">暱稱</td>
            <td width="14%">關於我</td>
            <td width="12%">期待的約會模式</td>
            <td width="12%">大頭照</td>
            <td width="5%">移除</td>
        </tr>
            @foreach ($suspiciousUser as $row)
                <tr>
                    <td>{{$row->title }}</td>
                    <td><a href="/admin/users/advInfo/{{ $row->id }}" target="_blank">{{ $row->email }}</a></td>
                    <td>{{$row->name}}</td>
                    <td>{{$row->about}}</td>
                    <td>{{$row->style }}</td>
                    <td><img src="{{$row->pic}}" style="width: 100px;"></td>
                    <td>
                        <button class="btn_sid btn btn-danger" data-sid="{{$row->id}}" data-uid="{{$row->id}}">移除</button>
                    </td>
                </tr>
            @endforeach
    </table>
    {!! $suspiciousUser->appends(request()->input())->links('pagination::sg-pages') !!}
</div>
@endif
<form id="sid_toggle" action="{{ route('users/suspicious_user_toggle') }}" method="post">
    {!! csrf_field() !!}
    <input type="hidden" name="sid" id="sid" value="">
    <input type="hidden" name="uid" id="uid" value="">
</form>

<script>

    $('.btn_sid').on('click', function(){

        $('#sid').val($(this).data('sid'));
        $('#uid').val($(this).data('uid'));

        let sid = $(this).data('sid'),
            r = false;

        if(sid==''){
            r = confirm('是否確定加入可疑名單?');
        }else{
            r = confirm('是否確定移除可疑名單?');
        }

        if(r==true){
            $('#sid_toggle').submit();
        }

    });

</script>


@stop