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
                <input name="date" id="datepicker_1" name="date-start" style="width: 20%;"> ~ <input name="date" id="datepicker_2" name="date-end" style="width: 20%;">
            </td>
        </tr>
        <tr>
            <td colspan="2"><button type='submit' class='text-white btn btn-primary'>送出</button></td>
        </tr>
    </table>
</form>
@if(isset($results))
<table class="table-hover table table-bordered">
    <tr>
        <td>發送者</td>
        <td>收訊者</td>
        <td>內容</td>
        <td>已讀</td>
        <td>發送時間</td>
    </tr>
    @forelse ($results as $result)
        <tr>
            <td>{{ $users[$result->from_id] }}</td>
            <td>{{ $users[$result->to_id] }}</td>
            <td>{{ $result->content }}</td>
            <td>{{ $result->read }}</td>
            <td>{{ $result->created_at }}</td>
        </tr>
    @empty
        沒有資料
    @endforelse
</table>
@endif
</body>
<script>
    jQuery(document).ready(function(){
        jQuery("#datepicker_1").datepicker(
            {
                todayHighlight: !0,
                orientation: "bottom left",
                templates: {
                    leftArrow: '<i class="la la-angle-left"></i>',
                    rightArrow: '<i class="la la-angle-right"></i>'
                }
            }
        );
        jQuery("#datepicker_2").datepicker(
            {
                todayHighlight: !0,
                orientation: "bottom left",
                templates: {
                    leftArrow: '<i class="la la-angle-left"></i>',
                    rightArrow: '<i class="la la-angle-right"></i>'
                }
            }
        );
    });
</script>
@stop