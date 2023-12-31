@extends('admin.main')
@section('app-content')
    <script src="/js/jquery.twzipcode.min.js" type="text/javascript"></script>
    <!--照片查看-->
    <link type="text/css" rel="stylesheet" href="/new/css/app.css">
    <link rel="stylesheet" type="text/css" href="/new/css/swiper2.min.css" />
    <script type="text/javascript" src="/new/js/swiper.min.js"></script>
    <!--照片查看-->
    <style>
        .table>tbody>tr>td,
        .table>tbody>tr>th {
            vertical-align: middle;
        }

        .table>tbody>tr>th {
            text-align: center;
        }

        .btn-gray {
            cursor: default;
            color: #fff;
            background-color: #5a5a5a;
            border-color: #5a5a5a;
            opacity: .65;

        }

        #blockade .form-group {
            clear: both;
        }

        #autoban_pic_gather .autoban_pic_unit {
            float: left;
            margin: 10px;
        }

        #autoban_pic_gather .autoban_pic_unit img {
            width: 80px;
            min-width: 80px;
        }

        #autoban_pic_gather input {
            display: none;
        }

        #autoban_pic_gather .autoban_pic_unit label {
            padding: 0 10px 10px 10px;
        }

        #autoban_pic_gather .autoban_pic_unit label span {
            display: block;
            text-align: center;
            font-size: 4px;
        }

        #autoban_pic_gather .autoban_pic_unit input:checked+label {
            background: #1E90FF;
        }

        th.titleColumn {
            width: 300px;
            min-width: 300px;
        }

        ul.elist {
            display: flex;
            justify-content: flex-start;
            flex-wrap: wrap;
            align-items: flex-start;
            width: 1600px;
            padding: 0;
        }

        ul.elist li {
            width: 200px;
            padding: 10px;
            height: : 100px;
            border: 1px solid #e9ecef;
            align-self: stretch;
        }

         .text-box{
             width: fit-content;
             overflow: hidden;
             display: -webkit-box;
             -webkit-line-clamp: 1;   /*省略第n行後的文字*/
             -webkit-box-orient: vertical;  /*設定元素是垂直布局*/
         }
         
         .newer_manual_time_detail_tb th {font-weight:549;}
        .newer_manual_time_detail_tb td {text-align:center;}
    </style>

    <body style="padding: 15px;">
        <h1>會員檢查 step 3</h1>
        <form id="searchForm" action="{{ route('users.message.check') }}" method="GET">
            {!! csrf_field() !!}
            <table class="table-hover table table-bordered" style="width: 50%;">
                <tr>
                    <th>訊息開始時間</th>
                    <td>
                        <input type='text' id="datepicker_1" name="message_date_start"
                            value="@if (isset($_GET['message_date_start'])) {{ $_GET['message_date_start'] }}@else{{ old('message_date_start') }} @endif"
                            class="form-control datepick">
                    </td>
                <tr>
                    <th>訊息結束時間</th>
                    <td>
                        <input type='text' id="datepicker_2" name="message_date_end"
                            value="@if (isset($_GET['message_date_end'])) {{ $_GET['message_date_end'] }}@else{{ old('message_date_end') }} @endif"
                            class="form-control datepick">
                    </td>
                </tr>
                <tr>
                    <th>預設訊息時間選項</th>
                    <td class="for-message">
                        <a class="text-white btn btn-success today">今天</a>
                        <a class="text-white btn btn-success last3days">最近3天</a>
                        <a class="text-white btn btn-success last10days">最近10天</a>
                        <a class="text-white btn btn-success last15days">最近15天</a>
                        <a class="text-white btn btn-success last30days">最近30天</a>
                    </td>
                </tr>
                <tr>
                    <th>登入開始時間</th>
                    <td>
                        <input type='text' id="datepicker_3" name="date_start"
                            value="{{ request()->get('date_start') ?? old('date_start') }}"
                            class="form-control datepick">
                    </td>
                <tr>
                    <th>登入結束時間</th>
                    <td>
                        <input type='text' id="datepicker_4" name="date_end"
                            value="{{ request()->get('date_end') ?? old('date_end') }}"
                            class="form-control datepick">
                    </td>
                </tr>
                <tr>
                    <th>預設登入時間選項</th>
                    <td class="for-login">
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
                        <input type="radio" name="en_group" value="1"
                            @if (isset($_GET['en_group']) && $_GET['en_group'] == 1) checked @endif>男
                        <input type="radio" name="en_group" value="2"
                            @if (isset($_GET['en_group']) && $_GET['en_group'] == 2) checked @endif>女
                    </td>
                </tr>
                <tr>
                    <th>訊息發送人數</th>
                    <td>
                        <input type="text" name="total" value="{{ request()->get('total') }}">
                    </td>
                </tr>
                <tr>
                    <th>訊息統計</th>
                    <td>
                        <input type="text" name="message_count_by_total" value="{{ request()->get('message_count_by_total') ?? 1 }}">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="submit" class="btn btn-primary">查詢</button> 或
                        <!-- <button type="submit" class="btn btn-info" name="hidden" value="1">查詢並顯示隱藏的照片</button> -->
                        <button type="reset" class="btn btn-default reset_btn" value="Reset">清除重選</button>
                    </td>
                </tr>
            </table>
        </form>

        @if ($data->isNotEmpty())
            <div>
                <table class="table-hover table table-bordered" width="200%">
                    <tr>
                        <th class="titleColumn" width="20%">會員資訊</td>
                        <th class="titleColumn" width="20%">關於我&約會模式</td>
                    </tr>
                    @foreach ($data as $key => $fromUser)
                        <tr>
                            <td
                                style="{{ $fromUser->isBanned($fromUser->id) ? 'background-color:#FFFF00' : ($fromUser->isAdminWarned() ? 'background-color:#B0FFB1' : '') }}">
                                email: <a {{ $fromUser->is_real }}
                                    href="{{ route('users/advInfo', ['id' => $fromUser->id]) }}"
                                    target="_blank">{{ $fromUser->email }}<a>
                                        <br>
                                        暱稱: {{ $fromUser->name  }} ({{ floor(( strtotime(date('Y-m-d H:i:s')) - strtotime($fromUser->created_at)) / (60 * 60 * 24)) }})
                                        <br>
                                        抬頭: {{ $fromUser->title }}
                                        <br>
                                        @if($fromUser->isPhoneAuth()) (手機) @endif
                                        @if($fromUser->is_real==0) (本人) @endif
                                        @if($fromUser->isAdvAuthUsable==0) (進階) @endif
                                        <br>
                                        總訊息數: {{ $fromUser->messageCount }}
                                        <br>
                                        新手教學時間: 
                                        @if($fromUser->engroup==1)
                                        {{ $fromUser->newer_manual_stay_online_time->time }}
                                         @else
                                         {{var_carrier('totalTime',$fromUser->getFemaleNewerManualTotalTime())}}    
                                         @endif
                                         @if($fromUser->engroup==2 && var_carrier('totalTime')>0)
                                        <span id="btn_showDetail_newer_manual_time_{{$fromUser->id}}" class="btn_showLogUser btn btn-primary" >+</span>
                                        <script>
                                        $('#btn_showDetail_newer_manual_time_{{$fromUser->id}}').click(function(){
                                            
                                            if( $('#newer_manual_time_detail_tb_{{$fromUser->id}}').css('display')=='none'){
                                                $('#newer_manual_time_detail_tb_{{$fromUser->id}}').show();
                                                $('#btn_showDetail_newer_manual_time_{{$fromUser->id}}').text('-');
                                            }else{
                                                
                                            
                                                $('#newer_manual_time_detail_tb_{{$fromUser->id}}').hide();
                                                $('#btn_showDetail_newer_manual_time_{{$fromUser->id}}').text('+');

                                            }
                                        });  
                                        </script>
                                        @endif
                                        @if($fromUser->engroup==2 && var_carrier('totalTime')>0)
                                        <table id="newer_manual_time_detail_tb_{{$fromUser->id}}" class="newer_manual_time_detail_tb table table-hover table-bordered" style="display:none;">
                                            <tr>
                                                <th>1-1</th><th>1-2</th><th>1-3</th>
                                            </tr>
                                            <tr>
                                                <td @if(var_carrier('halfTotalTime',var_carrier('totalTime')*0.5) < var_carrier('step_time1_1',($fromUser->female_newer_manual_time_list->where('step','1_1')->sum('time')))) style="background:red;font-weight:bolder;"   @endif>
                                                    {{var_carrier('step_time1_1')}}                                            
                                                </td>
                                                <td @if(var_carrier('halfTotalTime')< var_carrier('step_time1_2',$fromUser->female_newer_manual_time_list->where('step','1_2')->sum('time'))) style="background:red;font-weight:bolder;"   @endif>
                                                    {{var_carrier('step_time1_2')}}
                                                </td>
                                                <td @if(var_carrier('halfTotalTime')< var_carrier('step_time1_3',$fromUser->female_newer_manual_time_list->where('step','1_3')->sum('time'))) style="background:red;font-weight:bolder;"   @endif>
                                                    {{var_carrier('step_time1_3')}}
                                                </td>
                                            </tr>
                                            <tr style="border-top:3px solid;">
                                                <th>2-1</th><th>2-2</th><th>2-3</th>
                                            </tr>
                                            <tr>
                                               <td @if(var_carrier('halfTotalTime')< var_carrier('step_time2_1',$fromUser->female_newer_manual_time_list->where('step','2_1')->sum('time'))) style="background:red;font-weight:bolder;"   @endif>
                                                    {{var_carrier('step_time2_1')}}
                                               </td>
                                                <td @if(var_carrier('halfTotalTime')< var_carrier('step_time2_2',$fromUser->female_newer_manual_time_list->where('step','2_2')->sum('time'))) style="background:red;font-weight:bolder;"   @endif>
                                                    {{var_carrier('step_time2_2')}}
                                                </td>
                                                <td @if(var_carrier('halfTotalTime')< var_carrier('step_time2_3',$fromUser->female_newer_manual_time_list->where('step','2_3')->sum('time'))) style="background:red;font-weight:bolder;"   @endif>
                                                     {{var_carrier('step_time2_3')}}
                                                </td>
                                            </tr>
                                            <tr style="border-top:3px solid;">
                                                <th>3-1</th><th>3-2</th><th>3-3</th>
                                            </tr>
                                            <tr>
                                               <td @if(var_carrier('halfTotalTime')< var_carrier('step_time3_1',$fromUser->female_newer_manual_time_list->where('step','3_1')->sum('time'))) style="background:red;font-weight:bolder;"   @endif>
                                                    {{var_carrier('step_time3_1')}}
                                               </td>
                                                <td @if(var_carrier('halfTotalTime')< var_carrier('step_time3_2',$fromUser->female_newer_manual_time_list->where('step','3_2')->sum('time'))) style="background:red;font-weight:bolder;"   @endif>
                                                    {{var_carrier('step_time3_2')}}
                                                </td>
                                                <td @if(var_carrier('halfTotalTime')< var_carrier('step_time3_3',$fromUser->female_newer_manual_time_list->where('step','3_3')->sum('time'))) style="background:red;font-weight:bolder;"   @endif>
                                                     {{var_carrier('step_time3_3')}}
                                                </td>
                                            </tr>

                                        </table>
                                        @endif
                                        
                                        <br>
                                        訊息統計: {{ $fromUser->messageCountByTotal}} / {{ $fromUser->messageCountByTotalPeople }}
                            </td>
                            <td>
                                <p class="about-me text-box" title="{{ $fromUser->user_meta->about }}">關於我:
                                    {{ strLimit($fromUser->user_meta->about, 20) }}</p>
                                <br>
                                <p class="date-mode text-box" title="{{ $fromUser->user_meta->style }}">約會模式:
                                    {{ strLimit($fromUser->user_meta->style, 20) }}</p>
                            </td>
                            <td>
                                <ul class="elist">
                                    @foreach ($fromUser->toUser as $key => $toUser)
                                        <li
                                            style="{{ $fromUser->isBanned($toUser->id) ? 'background-color:#FFFF00' : ($toUser->isAdminWarned() ? 'background-color:#B0FFB1' : '') }}">
                                            <a href="{{ route('users/advInfo', ['id' => $toUser->id]) }}"
                                                target="_blank">{{ $toUser->email }}</a> ({{ $toUser->count }})
                                            </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                </table>
                {!! $data->isNotEmpty() ? $data->links('pagination::sg-pages') : '' !!}
                <br>
            </div>
        @endif

    </body>

    <script>
        let date = new Date();
        let year = date.getFullYear();
        let month = date.getMonth() + 1;
        let day = date.getDate();
        let today = new Date();
        let minus_date = new Date(today);

        function datePickInit() {
            const elems = $('.datepick');

            for (let i = elems.length; i--;) {
                const elem = elems[i];

                $(elem).datetimepicker({
                    dateFormat: 'yyyy-mm-dd',
                    timeFormat: 'H:i',
                    todayHighlight: !0,
                    orientation: "bottom left",
                    templates: {
                        leftArrow: '<i class="la la-angle-left"></i>',
                        rightArrow: '<i class="la la-angle-right"></i>'
                    }
                });
            }
        }

        function str_pad(n) {
            return String("00" + n).slice(-2);
        }

        function todays() {
            return year + '-' + str_pad(month) + '-' + str_pad(day);
        }

        function fewDays(days) {
            const last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));

            return last.getFullYear() + '-' + str_pad(last.getMonth() + 1) + '-' + str_pad(last.getDate());
        }

        function threeDays() {
            return fewDays(3);
        }

        function tenDays() {
            return fewDays(10);
        }

        function fifteenDays() {
            return fewDays(15);
        }

        function thirtyDays() {
            var start_date = new Date(new Date().setDate(date.getDate() - 30));

            return start_date.getFullYear() + '-' + str_pad(start_date.getMonth() + 1) + '-' + str_pad(start_date
                .getDate());
        }

        jQuery(document).ready(function() {
            datePickInit();

            $('.today').click(function() {
                const _self = $(this),
                    parents = _self.parent();

                if (parents.hasClass('for-message')) {
                    $('input[name="message_date_start"]').val(todays() + ' 00:00');
                    $('input[name="message_date_end"]').val(todays() + ' 23:59');

                    return false;
                }

                $('input[name="date_start"]').val(todays() + ' 00:00');
                $('input[name="date_end"]').val(todays() + ' 23:59');
            });
            $('.last3days').click(function() {
                const _self = $(this),
                    parents = _self.parent();

                if (parents.hasClass('for-message')) {
                    $('input[name="message_date_start"]').val(threeDays() + ' 00:00');
                    $('input[name="message_date_end"]').val(todays() + ' 23:59');

                    return false;
                }

                $('input[name="date_start"]').val(threeDays() + ' 00:00');
                $('input[name="date_end"]').val(todays() + ' 23:59');
            });
            $('.last10days').click(function() {
                const _self = $(this),
                    parents = _self.parent();

                if (parents.hasClass('for-message')) {
                    $('input[name="message_date_start"]').val(tenDays() + ' 00:00');
                    $('input[name="message_date_end"]').val(todays() + ' 23:59');

                    return false;
                }

                $('input[name="date_start"]').val(tenDays() + ' 00:00');
                $('input[name="date_end"]').val(todays() + ' 23:59');
            });
            $('.last15days').click(function() {
                const _self = $(this),
                    parents = _self.parent();

                if (parents.hasClass('for-message')) {
                    $('input[name="message_date_start"]').val(fifteenDays() + ' 00:00');
                    $('input[name="message_date_end"]').val(todays() + ' 23:59');

                    return false;
                }

                $('input[name="date_start"]').val(fifteenDays() + ' 00:00');
                $('input[name="date_end"]').val(todays() + ' 23:59');
            });
            $('.last30days').click(function() {
                const _self = $(this),
                    parents = _self.parent();

                if (parents.hasClass('for-message')) {
                    $('input[name="message_date_start"]').val(thirtyDays() + ' 00:00');
                    $('input[name="message_date_end"]').val(todays() + ' 23:59');

                    return false;
                }

                $('input[name="date_start"]').val(thirtyDays() + ' 00:00');
                $('input[name="date_end"]').val(todays() + ' 23:59');
            });
        });

        $('.reset_btn').on('click', function() {
            $('input:radio').removeAttr('checked');
            $('.datepick').removeAttr('value');
            $('input:text').removeAttr('value');
        });
    </script>
@stop
