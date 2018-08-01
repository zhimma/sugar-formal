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
<h1>站內訊息管理</h1>
<h3 style="text-align: left;">搜尋</h3>
<form action="" id='message' method='POST'>
    {!! csrf_field() !!}
    <table class="table-hover table table-bordered" style="width: 50%;">
        <tr>
            <th width="15%">
                <label for="msg">訊息內容</label>
            </th>
            <td>
                <input type="text" name="msg" class="">
            </td>
        </tr>
        <tr>
            <th>時間</th>
            <td>
                今天 最近3天 最近10天 最近30天
                <br>
                <input name="date" class="" name="date-start" style="width: 20%;"> ~ <input name="date" class="" name="date-end" style="width: 20%;">
            </td>
        </tr>
    </table>
    <button type='submit' class='text-white btn btn-primary'>送出</button>
</form>
</body>
@stop