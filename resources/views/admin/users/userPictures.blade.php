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
<table class="table-hover table table-bordered">
    <tr>
        <td>會員名稱</td>
        <td>照片</td>
        <td>
            <button class="btn btn-warning">不顯示</button>
            <button class="btn btn-danger">刪除</button>
        </td>
    </tr>
    @foreach ($allPics as $pic)
        <tr>
            <td>@if($pic->member_id) {{ $userNames[$pic->member_id] }} @else {{ $userNames[$pic->user_id] }} @endif</td>
            <td><img src="{{ url($pic->pic) }}" width="150px"></td>
            <td>

            </td>
        </tr>
    @endforeach
</table>
</body>
@stop