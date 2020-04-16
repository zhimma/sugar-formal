@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
    <h1>會員被檢舉次數</h1>
    @if (isset($errors))
        @if ($errors->count() > 0)
        @else
            <h3 style="text-align: left;">搜尋</h3>
            <form action="{{ route('users/reported/count') }}" id='message' method='POST'>
                {!! csrf_field() !!}
                <table class="table-hover table table-bordered" style="width: 60%;">
                    <tr>
                        <th>開始時間</th>
                        <td>
                            <input type='text' id="datepicker_1" name="date_start" data-date-format='yyyy-mm-dd' value="{{ $date_start or "" }}" class="form-control">
                        </td>
                    <tr>
                        <th>結束時間</th>
                        <td>
                            <input type='text' id="datepicker_2" name="date_end" data-date-format='yyyy-mm-dd' value="{{ $date_end or "" }}" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th>預設時間選項</th>
                        <td>
                            <a class="text-white btn btn-success today">今天</a>
                            <a class="text-white btn btn-success last3days">最近3天</a>
                            <a class="text-white btn btn-success last10days">最近10天</a>
                            <a class="text-white btn btn-success last30days">最近30天</a>
                            <a class="text-white btn btn-success last90days">最近90天</a>
                            <a class="text-white btn btn-success last180days">最近180天</a>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button class='text-white btn btn-primary submit'>送出</button>
                        </td>
                    </tr>
                </table>
            </form>
            @if(isset($reportedUsers))
                共有 {{ count($reportedUsers) }} 筆資料
                <table class="table-hover table table-bordered">
                    <tr>
                        <th>被檢舉者</th>
                        <th>回覆被檢舉者</th>
                        <th>封鎖被檢舉者</th>
                        <th>人數</th>
                        <th width="20%">檢舉訊息</th>
                        <th width="20%">檢舉會員</th>
                        <th width="20%">檢舉照片</th>
                    </tr>
                    @foreach( $reportedUsers as $id => $reportedUser)
                    <tr>
                        <td @if($users[$id]['isBlocked']) style="background-color:#FFFF00" @endif>
                            <a href="{{ route('users/advInfo', [$id]) }}" target='_blank'>
                                <p @if($users[$id]['engroup'] == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                                    {{ $users[$id]['name'] }}
                                    @if($users[$id]['vip'] )
                                        <i class="m-nav__link-icon fa fa-diamond"></i>
                                    @endif
                                    @if(!is_null($users[$id]['isBlocked']))
                                        @if(!is_null($users[$id]['isBlocked']['expire_date']))
                                            ({{ round((strtotime($users[$id]['isBlocked']['expire_date']) - getdate()[0])/3600/24 ) }}天)
                                        @elseif(isset($users[$id]['isBlocked']['type']))
                                            (隱性)
                                        @else
                                            (永久)
                                        @endif
                                    @endif
                                </p>
                            </a>
                        </td>
                        <td>
                            <a class='btn btn-dark' href="{{ route('admin/send', [$id]) }}" target="_blank" >撰寫</a>
                        </td>
                        <td>
                            <a class="btn btn-danger ban-user" href="{{ route('toggleUserBlock', [$id]) }}">封鎖</a>
                        </td>
                        <td>
                            <form action=" {{ route('users/reported/details', ['reported_id'=>$id]) }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="date_start" value="{{ $date_start or "" }}">
                                <input type="hidden" name="date_end" value="{{ $date_end or ""}}">
                                <input type="submit" class="btn" value="{{ $reportedUser['count'] }}">
                            </form>
                        </td>
                        <td>
                            @foreach( $reportedUser['messages'] as $msg )
                                <div @if($users[$msg->to_id]['isBlocked']) style="background-color:#FFFF00" @endif>
                                    <a href="{{ route('users/advInfo', [$msg->to_id]) }}" target='_blank'>
                                        <p @if($users[$msg->to_id]['engroup'] == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                                            {{ "( ".$users[$msg->to_id]['name'] }}
                                            @if( $users[$msg->to_id]['vip'] )
                                                <i class="m-nav__link-icon fa fa-diamond"></i>
                                            @endif
                                            @if(!is_null($users[$msg->to_id]['isBlocked']))
                                                @if(!is_null($users[$msg->to_id]['isBlocked']['expire_date']))
                                                    ({{ round((strtotime($users[$msg->to_id]['isBlocked']['expire_date']) - getdate()[0])/3600/24 ) }}天)
                                                @elseif(isset($users[$msg->to_id]['isBlocked']['type']))
                                                    (隱性)
                                                @else
                                                    (永久)
                                                @endif
                                            @endif
                                            {{ " ".$msg->created_at->format('Y/m/d')." )"}}
                                        </p>
                                    </a>
                                </div>
                            @endforeach
                        </td>
                        <td>
                            @foreach( $reportedUser['reports'] as $reports )
                                <div @if($users[$reports->member_id]['isBlocked']) style="background-color:#FFFF00" @endif>
                                    <a href="{{ route('users/advInfo', [$reports->member_id]) }}" target='_blank'>
                                        <p @if($users[$reports->member_id]['engroup'] == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                                            {{ "( ".$users[$reports->member_id]['name'] }}
                                            @if( $users[$reports->member_id]['vip'] )
                                                <i class="m-nav__link-icon fa fa-diamond"></i>
                                            @endif
                                            @if(!is_null($users[$reports->member_id]['isBlocked']))
                                                @if(!is_null($users[$reports->member_id]['isBlocked']['expire_date']))
                                                    ({{ round((strtotime($users[$reports->member_id]['isBlocked']['expire_date']) - getdate()[0])/3600/24 ) }}天)
                                                @elseif(isset($users[$reports->member_id]['isBlocked']['type']))
                                                    (隱性)
                                                @else
                                                    (永久)
                                                @endif
                                            @endif
                                            {{ " ".$reports->created_at->format('Y/m/d')." )"}}
                                        </p>
                                    </a>
                                </div>
                            @endforeach
                        </td>
                        <td>
                            @foreach( $reportedUser['avatars'] as $avatar )
                                <div @if($users[$avatar->reporter_id]['isBlocked']) style="background-color:#FFFF00" @endif>
                                    <a href="{{ route('users/advInfo', [$avatar->reporter_id]) }}" target='_blank'>
                                        <p @if($users[$avatar->reporter_id]['engroup'] == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                                            {{ "( ".$users[$avatar->reporter_id]['name'] }}
                                            @if( $users[$avatar->reporter_id]['vip'] )
                                                <i class="m-nav__link-icon fa fa-diamond"></i>
                                            @endif
                                            @if(!is_null($users[$avatar->reporter_id]['isBlocked']))
                                                @if(!is_null($users[$avatar->reporter_id]['isBlocked']['expire_date']))
                                                    ({{ round((strtotime($users[$avatar->reporter_id]['isBlocked']['expire_date']) - getdate()[0])/3600/24 ) }}天)
                                                @elseif(isset($users[$avatar->reporter_id]['isBlocked']['type']))
                                                    (隱性)
                                                @else
                                                    (永久)
                                                @endif
                                            @endif
                                            {{ " ".$avatar->created_at->format('Y/m/d')." )"}}
                                        </p>
                                    </a>
                                </div>
                            @endforeach

                            @foreach( $reportedUser['pics'] as $pic )
                                <div @if($users[$pic->reporter_id]['isBlocked']) style="background-color:#FFFF00" @endif>
                                    <a href="{{ route('users/advInfo', [$pic->reporter_id]) }}" target='_blank'>
                                        <p @if($users[$pic->reporter_id]['engroup'] == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                                            {{ "( ".$users[$pic->reporter_id]['name'] }}
                                            @if( $users[$pic->reporter_id]['vip'] )
                                                <i class="m-nav__link-icon fa fa-diamond"></i>
                                            @endif
                                            @if(!is_null($users[$pic->reporter_id]['isBlocked']))
                                                @if(!is_null($users[$pic->reporter_id]['isBlocked']['expire_date']))
                                                    ({{ round((strtotime($users[$pic->reporter_id]['isBlocked']['expire_date']) - getdate()[0])/3600/24 ) }}天)
                                                @elseif(isset($users[$pic->reporter_id]['isBlocked']['type']))
                                                    (隱性)
                                                @else
                                                    (永久)
                                                @endif
                                            @endif
                                            {{ " ".$pic->created_at->format('Y/m/d')." )"}}
                                        </p>
                                    </a>
                                </div>
                            @endforeach
                        </td>
                    </tr>
                    @endforeach
                </table>
            @endif
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
        $('.last90days').click(
            function () {
                minus_date.setDate(minus_date.getDate() - 89);
                $('#datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
                $('.datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
                set_end_date();
                minus_date.setDate(minus_date.getDate() + 89);
            });
        $('.last180days').click(
            function () {
                minus_date.setDate(minus_date.getDate() - 179);
                $('#datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
                $('.datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
                set_end_date();
                minus_date.setDate(minus_date.getDate() + 179);
            });
        
    });
    function set_end_date(){
        $('#datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
        $('.datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
    }
    function str_pad(n) {
        return String("00" + n).slice(-2);
    }
</script>
@stop
