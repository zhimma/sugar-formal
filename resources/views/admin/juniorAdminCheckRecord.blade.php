@extends('admin.main')
@section('app-content')
    <body style="padding: 15px;">
        <h1>初階站長審查檢核統計</h1>
        <div class="col col-12 col-sm-12 col-md-8 col-lg-6">
            <form id="search_form">
                <table class="table-hover table table-bordered">
                    <tr>
                        <th>開始時間</th>
                        <td>
                            <input type='text' id="datepicker_1" name="date_start" data-date-format='yyyy-mm-dd' value="@if(isset($_GET['date_start'])){{ $_GET['date_start'] }}@endif" class="form-control">
                        </td>
                    <tr>
                        <th>結束時間</th>
                        <td>
                            <input type='text' id="datepicker_2" name="date_end" data-date-format='yyyy-mm-dd' value="@if(isset($_GET['date_end'])){{ $_GET['date_end'] }}@endif" class="form-control">
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
                        <th>Admin操作人員</th>
                        <td>
                            @foreach($operator_list as $operator)
                                <input class="operator_list" type="checkbox" name="operator[]" value="{{$operator->user_id}}" @if(in_array($operator->user_id, Request()->get('operator',[]))) checked @endif><span>{{$operator->email}}</span><br>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input id="search_log" type="submit" class='text-white btn btn-primary submit' value="查詢">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div id="junior_admin_log">
        </div>
    </body>
    <script>

        let date = new Date();
        let year = date.getFullYear();
        let month = date.getMonth() + 1;
        let day = date.getDate();
        let today = new Date();
        let minus_date = new Date(today);
        $( document ).ready(function() {
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
        });

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
        
        function set_end_date() {
            $('#datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
            $('.datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
        }

        function str_pad(n) {
            return String("00" + n).slice(-2);
        }

        $('#search_log').click(function(e) {
            e.preventDefault();
            let start_time = $('#datepicker_1').val();
            let end_time = $('#datepicker_2').val();
            let operator_list = [];
            $('.operator_list').each(function(){
                if($(this).prop('checked'))
                {
                    operator_list.push($(this).val());
                }
            });
            if(operator_list == 0){
                alert('請勾選Admin操作人員');
                return false;
            }
            $.ajax({
                type: 'POST',
                url: '{{route('juniorAdminCheckRecordShow')}}',
                data: {
                    _token: '{{csrf_token()}}',
                    start_time: start_time,
                    end_time: end_time,
                    operator_list: operator_list
                },
                success: function(res){
                    $('#junior_admin_log').html(res);
                }
            });
        });
    </script>
@stop