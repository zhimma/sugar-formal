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
    <form action="{{ route('admin/announcement/save') }}" id='message' method='POST'>
        {!! csrf_field() !!}
        <h3>男性會員</h3>
        <textarea name="engroup_1" class="form-control" cols="80" rows="5">@foreach($announce as $a) @if($a->en_group == 1){{ $a->content }}@endif @endforeach</textarea>
        <h3>女性會員</h3>
        <textarea name="engroup_2" class="form-control" cols="80" rows="5">@foreach($announce as $a) @if($a->en_group == 2){{ $a->content }}@endif @endforeach</textarea>
        <button type='submit' class='text-white btn btn-primary'>修改</button>
    </form>
</body>
@stop
