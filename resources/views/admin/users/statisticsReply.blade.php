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
<h1>會員訊息統計</h1>
@if (isset($errors))
    <h3 style="text-align: left;">搜尋</h3>
    <form action="{{ url('/admin/statistics') }}" id='message' method='POST'>
        {!! csrf_field() !!}
        <table class="table-hover table table-bordered" style="width: 50%;">
            <tr>
                <th>開始時間</th>
                <td>
                    <input type='text' id="datepicker_1" name="date_start" data-date-format='yyyy-mm-dd' value="@if(isset($date_start)){{ $date_start }}@endif" class="form-control">
                </td>
            <tr>
                <th>結束時間</th>
                <td>
                    <input type='text' id="datepicker_2" name="date_end" data-date-format='yyyy-mm-dd' value="@if(isset($date_end)){{ $date_end }}@endif" class="form-control">
                </td>
            </tr>
            <tr>
                <th>預設時間選項</th>
                <td>
                    <a class="text-white btn btn-success today">今天</a>
                    <a class="text-white btn btn-success last3days">最近3天</a>
                    <a class="text-white btn btn-success last10days">最近10天</a>
                    <a class="text-white btn btn-success last30days">最近30天</a>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <button class='text-white btn btn-primary submit'>送出</button>
                </td>
            </tr>
        </table>
    </form>
    @if(isset($count) && isset($percentage))
        <table class='table table-bordered table-hover'>
            <tr>
                <td>項目</td>
                <td>比例</td>
            </tr>
            <tr>
                <td>車馬費邀請後女生回應比</td>
                <td>{{ $count['tipMessage']['replied']."/".$count['tipMessage']['totalInvitation']}}</td>
                <td>{{ ($percentage['tipMessage']*100)."%" }}</td>
            </tr>
            <tr>
                <td>普通男會員發訊給女會員的回應比</td>
                <td>{{ $count['NormalMale']['replied']."/".$count['NormalMale']['messages']}}</td>
                <td>{{ ($percentage['NormalMale']*100)."%" }}</td>
            </tr>
            <tr>
                <td>vip 男會員發訊給女會員的回應比</td>
                <td>{{ $count['VipMale']['replied']."/".$count['VipMale']['messages']}}</td>
                <td>{{ ($percentage['VipMale']*100)."%" }}</td>
            </tr>
            <tr>
                <td>優選男會員發訊給女會員的回應比</td>
                <td>{{ $count['RecommendMale']['replied']."/".$count['RecommendMale']['messages']}}</td>
                <td>{{ ($percentage['RecommendMale']*100)."%" }}</td>
            </tr>
            <tr>
                <td>有VIP且為雙北的女生平均收到的訊息數</td>
                <td>{{ $count['TaipeiAndVip']['messages']."/".$count['TaipeiAndVip']['users'] }}</td>
                <td>{{ ($percentage['TaipeiAndVip']*100)."%" }}</td>
            </tr>
            <tr>
                <td>無VIP且為雙北的女生平均收到的訊息數</td>
                <td>{{ $count['TaipeiAndNotVip']['messages']."/".$count['TaipeiAndNotVip']['users'] }}</td>
                <td>{{ ($percentage['TaipeiAndNotVip']*100)."%" }}</td>
            </tr>
            <tr>
                <td>VIP的女生平均收到的訊息數</td>
                <td>{{ $count['Vip']['messages']."/".$count['Vip']['users'] }}</td>
                <td>{{ ($percentage['Vip']*100)."%" }}</td>
            </tr>
            <tr>
                <td>VIP的女生平均收到的訊息數</td>
                <td>{{ $count['NotVip']['messages']."/".$count['NotVip']['users'] }}</td>
                <td>{{ ($percentage['NotVip']*100)."%" }}</td>
            </tr>
        </table>
    @endif
@endif
</body>
<script>
    let date = new Date();
    let year = date.getFullYear();
    let month = date.getMonth() + 1;
    let day = date.getDate();
    let today = new Date(year, month, day);
    let minus_date = new Date(today);
    jQuery(document).ready(function(){
        jQuery("#datepicker_1").datepicker(
            {
                dateFormat: 'yy-mm-dd',
                todayHighlight: !0,
                orientation: "bottom left",
                templates: {
                    leftArrow: '<i class="la la-angle-left"></i>',
                    rightArrow: '<i class="la la-angle-right"></i>'
                }
            }
        ).val();
        jQuery("#datepicker_2").datepicker(
            {
                dateFormat: 'yy-mm-dd',
                todayHighlight: !0,
                orientation: "bottom left",
                templates: {
                    leftArrow: '<i class="la la-angle-left"></i>',
                    rightArrow: '<i class="la la-angle-right"></i>'
                }
            }
        ).val();

        $('.today').click(
            function(){
                $('#datepicker_1').val(year + '-' + str_pad(month) + '-' + str_pad(day));
                $('.datepicker_1').val(year + '-' + str_pad(month) + '-' + str_pad(day));
                set_end_date();
            });
        $('.last3days').click(
            function () {
                minus_date.setDate(minus_date.getDate() - 2);
                $('#datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
                $('.datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
                set_end_date();
                minus_date.setDate(minus_date.getDate() + 2);
            });
        $('.last10days').click(
            function () {
                minus_date.setDate(minus_date.getDate() - 9);
                $('#datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
                $('.datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
                set_end_date();
                minus_date.setDate(minus_date.getDate() + 9);
            });
        $('.last30days').click(
            function () {
                minus_date.setDate(minus_date.getDate() - 29);
                $('#datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
                $('.datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
                set_end_date();
                minus_date.setDate(minus_date.getDate() + 29);
            });
        $('.delete-btn').on('click',function(e){
            if(!confirm('確定要刪除選取的訊息?')){
                e.preventDefault();
            }
        });
    });
    function selectAll () {
        $('.boxes').each(
            function () {
                if($(this).is(':checked')){
                    $(this).prop("checked", false);
                }
                else{
                    $(this).selected();
                }
            });

    }
    function set_end_date(){
        $('#datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
        $('.datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
    }
    function str_pad(n) {
        return String("00" + n).slice(-2);
    }

</script>
@stop
