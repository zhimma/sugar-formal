@extends('admin.main')
@section('app-content')
    <script src="/js/jquery.twzipcode.min.js" type="text/javascript"></script>
    <style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
    .table > tbody > tr > th{
        text-align: center;
    }
    </style>
    <body style="padding: 15px;">
        <h1>八大判斷訓練設定頁</h1>
        <form action="{{ route('admin/special_industries_judgment_training_setup_set') }}" method="POST">
            {!! csrf_field() !!}
            <table class="table-hover table table-bordered" style="width: 50%;">
                <tr>
                    <th>開始時間</th>
                    <td>
                        <input type='text' id="datepicker_1" name="date_start" data-date-format='yyyy-mm-dd' value="{{ old('date_start') }}" class="form-control">
                    </td>
                <tr>
                    <th>結束時間</th>
                    <td>
                        <input type='text' id="datepicker_2" name="date_end" data-date-format='yyyy-mm-dd' value="{{ old('date_end') }}" class="form-control">
                    </td>
                </tr>
                <tr>
                    <th>預設時間選項</th>
                    <td>
                        <a class="text-white btn btn-success today">今天</a>
                        <a class="text-white btn btn-success last3days">最近3天</a>
                        <a class="text-white btn btn-success last10days">最近10天</a>
                        <a class="text-white btn btn-success last15days">最近15天</a>
                        <a class="text-white btn btn-success last30days">最近30天</a>
                    </td>
                </tr>
        
                <tr>
                    <th>性別</th>
                    <td>
                        <input type="radio" name="en_group" value="1">男</input>
                        <input type="radio" name="en_group" value="2">女</input>
                    </td>
                    <tr>
                        <th>測驗標題</th>
                        <td>
                            <input name="test_title"></input>
                        </td>
                    </tr>
                    <tr>
                        <th>選項</th>
                        <td>
                            <input type="checkbox" name="is_banned" value="1"><label>已被封鎖</label>
                            <br>
                            <input type="checkbox" name="is_warned" value="1"><label>已被警示</label>
                            <br>
                            <input type="checkbox" name="is_ever_banned" value="1"><label>曾被封鎖，已解鎖</label>
                            <br>
                            <input type="checkbox" name="is_ever_warned" value="1"><label>曾被警示，已解除</label>
                        </td>
                    </tr>
                    <tr>
                        <th>選取模式</th>
                        <td>
                            <input type="radio" name="select_member_count" value="0" checked></input>全選
                            <br>
                            <input type="radio" name="select_member_count" value="1"></input>隨機選取<input name="select_count"></input>人
                        </td>
                    </tr>
                    <tr>
                        <th>正常會員人數</th>
                        <td>
                            <input type="radio" name="normal_member_count" value="0" checked></input>持平
                            <br>
                            <input type="radio" name="normal_member_count" value="1"></input>增加
                            <input type="radio" name="normal_member_count" value="-1"></input>減少<input name="member_count"></input>人
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button type="submit" class="btn btn-primary">新增</button>
                        </td>
                    </tr>
                </tr>
            </table>
        </form>
        <h1>題目列表</h1>
        <table class="table-hover table table-bordered" style="width: 50%;">
            @foreach($test_topic as $test)
                <tr>
                    <th>
                        題目編號{{$test->topic_id}}-設定({{$test->title}})
                        ({{$test->topic_count}}人)
                        ({{Carbon\Carbon::parse($test->start_time)->toDateString()}}~{{Carbon\Carbon::parse($test->end_time)->toDateString()}})
                        (
                        @if($test->gender == 1)
                        男
                        @elseif($test->gender == 2)
                        女
                        @else
                        男女
                        @endif
                        )
                        <br>
                        (非正常會員 : {{$test->topic_count - $test->normal_count}}人)
                        (正常會員 : {{$test->normal_count}}人)
                    </th>
                    <td align="center">
                        <button onclick="location.href='special_industries_judgment_training_test?topic_id={{$test->topic_id}}'">測試</button>
                    </td>
                    <td align="center">
                        <button onclick="location.href='special_industries_judgment_training_hide?topic_id={{$test->topic_id}}'">刪除</button>
                    </td>
                <tr>
            @endforeach
        </table>
    </body>
    <script>
        $('.twzipcode').twzipcode({
            'detect': true, 'css': ['form-control twzip', 'form-control twzip', 'zipcode']
        });
        let date = new Date();
        let year = date.getFullYear();
        let month = date.getMonth() + 1;
        let day = date.getDate();
        let today = new Date(year, month, day);
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
                function () {
                    $('#datepicker_1').val(year + '-' + str_pad(month) + '-' + str_pad(day));
                    $('.datepicker_1').val(year + '-' + str_pad(month) + '-' + str_pad(day));
                    set_end_date();
                });
            $('.last3days').click(
                function () {
                    var days = 3; // Days you want to subtract
                    var date = new Date();
                    var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                    $('#datepicker_1').val(last.getFullYear() + '-' + str_pad(last.getMonth() + 1) + '-' + str_pad(last.getDate()));
                    $('.datepicker_1').val(date.getFullYear() + '-' + str_pad(date.getMonth()) + '-' + str_pad(date.getDate()));
                    set_end_date();
                });
            $('.last10days').click(
                function () {
                    var days = 10; // Days you want to subtract
                    var date = new Date();
                    var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                    $('#datepicker_1').val(last.getFullYear() + '-' + str_pad(last.getMonth() + 1) + '-' + str_pad(last.getDate()));
                    $('.datepicker_1').val(date.getFullYear() + '-' + str_pad(date.getMonth()) + '-' + str_pad(date.getDate()));
                    set_end_date();
                });
            $('.last15days').click(
                function () {
                    var days = 15; // Days you want to subtract
                    var date = new Date();
                    var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                    $('#datepicker_1').val(last.getFullYear() + '-' + str_pad(last.getMonth() + 1) + '-' + str_pad(last.getDate()));
                    $('.datepicker_1').val(date.getFullYear() + '-' + str_pad(date.getMonth()) + '-' + str_pad(date.getDate()));
                    set_end_date();
                });
            $('.last30days').click(
                function () {
                    var start_date = new Date(new Date().setDate(date.getDate() - 30));
                    $('#datepicker_1').val(start_date.getFullYear() + '-' + parseInt(start_date.getMonth()+1) + '-' + start_date.getDate());
                    $('.datepicker_1').val(start_date.getFullYear() + '-' + parseInt(start_date.getMonth()+1) + '-' + start_date.getDate());
                    set_end_date();
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