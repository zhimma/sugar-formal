@extends('admin.main')
@section('app-content')
<style>
.table > tbody > tr > td, .table > tbody > tr > th{
    vertical-align: middle;
}
</style>
<body style="padding: 15px;">
    <h1>站長公告</h1>
    <form action="{{ route('admin/announcement/save') }}" id='message' method='POST'>
        {!! csrf_field() !!}
        <input type="hidden" name="'id" value="{{ $announce->id }}">
        <textarea name="content" id="content" class="form-control" cols="80" rows="5">{{ $announce->content }}</textarea><br>
        <button type='submit' class='text-white btn btn-primary'>修改</button>
    </form>
</body>
@stop
