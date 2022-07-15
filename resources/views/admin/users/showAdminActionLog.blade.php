@extends('admin.main')
@section('app-content')
<style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
</style>
<body style="padding: 15px;">
    <h1>Admin後台操作記錄</h1>
    <div class="col col-12 col-sm-12 col-md-8 col-lg-6">
        <form action="{{ route('admin/getAdminActionLog') }}" method="GET">
            {!! csrf_field() !!}
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
                            <input type="checkbox" name="operator[]" value="{{$operator->operator}}" @if(in_array($operator->operator, Request()->get('operator',[]))) checked @endif><span>{{$operator->operator_email}}</span><br>
                        @endforeach
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
                    <span class="OperatorItem" data-sectionName="showOperator_{{ $log['operator'] }}">操作人員：{{  $log['operator_name']. ' ['. $log['dataCount'] .']' .' Email： '.$log['operator_email']  }}</span>
                    <table>
                        @php
                            $operator_by_date=array_get($log,'operator_by_date',[]);
                        @endphp
                        @if(count($operator_by_date)>0)
                            @foreach($operator_by_date as $gpKey =>$group)
                                <tr class="showOperator showOperator_{{ $log['operator'] }}">
                                    <td class="showDetail" id="showLogByDate{{ $group['log_by_date'] }}_operator_{{$log['operator']}}" data-sectionName="showLogByDateDetail{{ $group['log_by_date'] }}_operator_{{$log['operator']}}" style="margin-left: 20px;min-width: 130px;">
                                        <span id="btn_showLogByDateDetail{{ $group['log_by_date'] }}_operator_{{$log['operator']}}" class="btn btn-primary">+</span>
                                        {{  $group['log_by_date'] .'('. $group['count_by_date'] .')' }}
                                    </td>
                                </tr>
                                <tr class="showLog" id="showLogByDateDetail{{ $group['log_by_date'] }}_operator_{{$log['operator']}}">
                                    <td>
                                        @php
                                            $logInLog=\App\Models\AdminActionLog::selectRaw('admin_action_log.*, users.email, (select email from users where id = admin_action_log.target_id) AS target_acc, `warned_users`.* ')
                                                ->selectRaw('IF((select count(*) from banned_users where banned_users.member_id=admin_action_log.target_id) >0,1,0) AS is_banned')
                                                ->selectRaw('IF((select count(*) from warned_users where warned_users.member_id=admin_action_log.target_id) >0,1,0) AS is_warned')
                                                ->leftJoin('users', 'users.id', '=', 'admin_action_log.operator')
                                                ->leftJoin('warned_users', 'warned_users.member_id', '=', 'admin_action_log.target_id')
                                                ->where('admin_action_log.operator', $log['operator'])
                                                ->where('admin_action_log.created_at','like', '%'.$group['log_by_date'].'%')
                                                ->orderBy('admin_action_log.created_at', 'desc')
                                                ->get();
                                        @endphp
                                        <table class="table table-bordered" style="display: block; max-height: 500px; overflow-x: scroll;">
                                            <thead>
                                            <tr class="info">
                                                <td>序號</td>
                                                <td>Admin操作人員</td>
                                                <td>目標帳號</td>
                                                <td>動作</td>
                                                <td>IP</td>
                                                <td>過期時間</td>
                                                <td>操作時間</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($logInLog as $detail)
                                                @php
                                                    $backgroud_color='';
                                                    if($detail->is_banned==1)
                                                        $backgroud_color='yellow';
                                                    elseif ($detail->is_warned==1 && \Carbon\Carbon::now()->lt($detail->expire_date))
                                                        $backgroud_color='#62ff07d1';
                                                @endphp
                                                <tr style="background-color:{{$backgroud_color}}">
                                                    <td>{{ $detail->operator }}</td>
                                                    <td><a href="/admin/users/advInfo/{{ $detail->operator }}" target="_blank">{{ $detail->email }}</a></td>
                                                    <td><a href="/admin/users/advInfo/{{ $detail->target_id }}" target="_blank">{{ $detail->target_acc }}</a></td>
                                                    <td>{{ $detail->act }}</td>
                                                    <td>{{ $detail->ip }}</td>
                                                    <td>{{ $detail->expire_date }}</td>
                                                    <td>{{ $detail->created_at }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </td>
            </tr>
        @endforeach
        @if(count($getLogs)==0)
            暫無資料
        @endif
    </table>
</body>

<script>
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
        $('.OperatorItem').click(function(){
            var sectionName =$(this).attr('data-sectionName');
            if( $('.'+sectionName).css('display')=='none'){
                $('.'+sectionName).show();
            }else{
                $('.'+sectionName).hide();
            }
        });

        $('.showOperator').hide();
        $('.showDetail').click(function(){
            var sectionName =$(this).attr('data-sectionName');
            if( $('#'+sectionName).css('display')=='none'){
                $('#'+sectionName).show();
                $('#btn_'+sectionName).text('-');
            }else{
                $('#'+sectionName).hide();
                $('#btn_'+sectionName).text('+');
            }
        });

        $('form').on('submit', function(event) {
            event.preventDefault();

            var searchIDs = [];
            $("input:checkbox:checked").map(function(){
                searchIDs.push($(this).val());
            });
            console.log(searchIDs.length);

            if($('#datepicker_1').val()==''){
                alert('請輸入開始時間');
                return false;
            }else if($('#datepicker_2').val()==''){
                alert('請輸入結束時間');
                return false;
            }else if(searchIDs==0){
                alert('請勾選Admin操作人員');
                return false;
            }else{
                this.submit();
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