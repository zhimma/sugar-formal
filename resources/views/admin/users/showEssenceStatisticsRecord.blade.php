@extends('admin.main')
@section('app-content')
<style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
</style>
<body style="padding: 15px;">
    <h1>精華文章統計資料</h1>
    <div class="col col-12 col-sm-12 col-md-8 col-lg-6" style="display: none;">
        <form action="{{ route('admin/getEssenceStatisticsRecord') }}" method="GET">
            {!! csrf_field() !!}
            <table class="table-hover table table-bordered">
                <tr>
                    <th>Admin操作人員</th>
                    <td><input type="email" class="form-control" name="operator" value="@if(isset($_GET['operator'])){{ $_GET['operator'] }}@endif"></td>
                </tr>
                <tr>
                    <th>開始時間</th>
                    <td>
                        <input type='text' id="datepicker_1" name="date_start" data-date-format='yyyy-mm-dd' value="@if(isset($_GET['date_start'])){{ $_GET['date_start'] }}@endif" class="form-control" required>
                    </td>
                <tr>
                    <th>結束時間</th>
                    <td>
                        <input type='text' id="datepicker_2" name="date_end" data-date-format='yyyy-mm-dd' value="@if(isset($_GET['date_end'])){{ $_GET['date_end'] }}@endif" class="form-control" required>
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
                        <input type="submit" class='text-white btn btn-primary submit' value="查詢">
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <table id="table_userLogin_log" class="table table-hover table-bordered">
        @foreach($getLogs as $key => $log)
            <tr>
                <td>
                    <span id="btn_showDetail_{{ $log['user_id'] }}" class="btn_showLogUser btn btn-primary" data-sectionName="showDetail_{{ $log['user_id'] }}">+</span>
                    <a href="/admin/users/advInfo/{{ $log['user_id'] }}" target="_blank"><span>帳號：{{  $log['user_name'] }}</span></a>
                <table>
                        <tr class="showLog" id="showDetail_{{ $log['user_id'] }}">
                            <td>
                                @php
                                    $log_list=array_get($log,'log_list',[]);
                                @endphp
                                <table class="table table-bordered" style="display: block; max-height: 500px; overflow-x: scroll;">
                                    <thead>
                                    <tr class="info">
                                        <td>精華文章ID</td>
                                        <td>精華文章標題</td>
                                        <td>文章作者</td>
                                        <td>發訊時間</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($log_list as $detail)
                                        <tr>
                                            <td>{{ $detail['essence_posts_id'] }}</td>
                                            <td>{{ $detail['essence_posts_title'] }}</td>
                                            <td><a href="/admin/users/advInfo/{{ $detail['user_id'] }}" target="_blank">{{ $detail['user_email'] }}</a></td>
                                            <td>{{ $detail['message_send_time'] }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        @endforeach
        @if(count($getLogs)==0)
            目前暫時無資料
        @endif
    </table>
</body>

<script>
    let date = new Date();
    let year = date.getFullYear();
    let month = date.getMonth() + 1;
    let day = date.getDate();
    let today = new Date();
    let minus_date = new Date(today);
    jQuery(document).ready(function() {
        jQuery("#datepicker_1").datepicker({
            dateFormat: 'yy-mm-dd',
            todayHighlight: !0,
            orientation: "bottom left",
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        }).val();
        jQuery("#datepicker_2").datepicker({
            dateFormat: 'yy-mm-dd',
            todayHighlight: !0,
            orientation: "bottom left",
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        }).val();

        $('.today').click(
            function() {
                $('#datepicker_1').val(year + '-' + str_pad(month) + '-' + str_pad(day));
                $('.datepicker_1').val(year + '-' + str_pad(month) + '-' + str_pad(day));
                set_end_date();
            });
        $('.last3days').click(
            function() {
                var days = 3; // Days you want to subtract
                var date = new Date();
                var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                $('#datepicker_1').val(last.getFullYear() + '-' + str_pad(last.getMonth()  + 1 ) + '-' + str_pad(last.getDate()));
                $('.datepicker_1').val(date.getFullYear() + '-' + str_pad(date.getMonth()) + '-' + str_pad(date.getDate()));
                set_end_date();
            });
        $('.last10days').click(
            function() {
                var days = 10; // Days you want to subtract
                var date = new Date();
                var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                $('#datepicker_1').val(last.getFullYear() + '-' + str_pad(last.getMonth() + 1 ) + '-' + str_pad(last.getDate()));
                $('.datepicker_1').val(date.getFullYear() + '-' + str_pad(date.getMonth()) + '-' + str_pad(date.getDate()));
                set_end_date();
            });
        $('.last30days').click(
            function() {
                var start_date = new Date(new Date().setDate(date.getDate() - 30));
                $('#datepicker_1').val(start_date.getFullYear() + '-' + parseInt(start_date.getMonth()+1) + '-' + start_date.getDate());
                $('.datepicker_1').val(start_date.getFullYear() + '-' + parseInt(start_date.getMonth()+1) + '-' + start_date.getDate());
                set_end_date();
            });

        $('#table_userLogin_log .hidden').hide();
        $('#table_userLogin_log td').click(function(){
            if($(this).find('.hidden').is(":visible")){
                $(this).find('.hidden').hide();
            }else{
                $(this).find('.hidden').show()
            }
        });

        $('.showLog').hide();
        $('.btn_showLogUser').click(function(){
            var sectionName =$(this).attr('data-sectionName');
            //alert(sectionName);
            if( $('#'+sectionName).css('display')=='none'){
                $('#'+sectionName).show();
                $('#btn_'+sectionName).text('-');
            }else{
                $('#'+sectionName).hide();
                $('#btn_'+sectionName).text('+');
            }
        });
    });

    function set_end_date() {
        $('#datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
        $('.datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
    }

    function str_pad(n) {
        return String("00" + n).slice(-2);
    }
</script>
@stop