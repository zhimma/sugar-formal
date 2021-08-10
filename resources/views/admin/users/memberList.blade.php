@extends('admin.main')
@section('app-content')
<style>
    .table>tbody>tr>td,
    .table>tbody>tr>th {
        vertical-align: middle;
    }

    .table>tbody>tr>th {
        text-align: center;
    }

    .range-wrap {
        position: relative;
        margin: 0 auto 3rem;
    }
    .range {
        width: 100%;
    }
    .bubble {
        background: black;
        color: white;
        padding: 4px 12px;
        position: absolute;
        border-radius: 4px;
        left: 50%;
        transform: translate(-50%, 20px);
    }
    .bubble::after {
        content: "";
        position: absolute;
        width: 2px;
        height: 2px;
        background: black;
        top: -1px;
        left: 50%;
    }
</style>

<body style="padding: 15px;">

    <h1>會員列表查詢</h1>

    <div class="col col-12 col-sm-12 col-md-8 col-lg-6">
    <form action="{{ route('searchMemberList') }}" id='message' method='post'>
        {!! csrf_field() !!}
        <table class="table-hover table table-bordered">

            <tr>
                <th>會員性別</th>
                <td>
                    <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="gender" id="inlineRadio1" value="1" @if(isset($_POST['gender']) && $_POST['gender']=='1' ) checked="checked" @endif style="margin-left: unset;">
                        <label class="form-check-label" for="inlineRadio1">男</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="gender" id="inlineRadio2" value="2"  @if(isset($_POST['gender']) && $_POST['gender']=='2' ) checked="checked" @elseif(!isset($_POST['gender'])) checked="checked" @endif style="margin-left: unset;">
                        <label class="form-check-label" for="inlineRadio2">女</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="gender" id="inlineRadio3" value="0"  @if(isset($_POST['gender']) && $_POST['gender']=='0' ) checked="checked" @endif style="margin-left: unset;">
                        <label class="form-check-label" for="inlineRadio3">Both</label>
                    </div>
                </td>
            </tr>
            <tr>
                <th>開始時間</th>
                <td>
                    <input type='text' id="datepicker_1" name="date_start" data-date-format='yyyy-mm-dd' value="@if(isset($_POST['date_start'])){{ $_POST['date_start'] }}@endif" class="form-control" required>
                </td>
            <tr>
                <th>結束時間</th>
                <td>
                    <input type='text' id="datepicker_2" name="date_end" data-date-format='yyyy-mm-dd' value="@if(isset($_POST['date_end'])){{ $_POST['date_end'] }}@endif" class="form-control" required>
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
                <th>排序方式</th>
                <td>
                    <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="time" id="inlineRadio4" value="created_at" @if(isset($_POST['time']) && $_POST['time']=='created_at' ) checked="checked" @endif style="margin-left: unset;" />
                        <label class="form-check-label" for="inlineRadio4">註冊時間</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" name="time" id="inlineRadio5" value="last_login" @if(isset($_POST['time']) && $_POST['time']=='last_login' ) checked="checked" @endif style="margin-left: unset;" />
                        <label class="form-check-label" for="inlineRadio5">上線時間</label>
                    </div>
                </td>
            </tr>
            <tr>
                <th>查詢使用者數</th>
                <td>
                    <div class="range-wrap">
                        <output class="bubble"></output>
                        <input type="range" name="users_counts" min="30" max="1000" value=@if(isset($_POST['users_counts']))"{{$_POST['users_counts']}}"@else"30"@endif class="form-control-range range" id="myRange">
                    </div>
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
    @if(isset($results) && count($results)>0)
        <table class="table-hover table table-bordered" style="word-break: break-word;">
            <tr>
                <th width="14%">email</th>
                <th width="5%">暱稱</th>
                <th width="12%">一句話形容自己</th>
                <th width="3%">性別</th>
                <th width="5%">VIP月數</th>
                <th width="8%">註冊時間</th>
                <th width="8%">上線時間</th>
                <th width="5%">被檢舉次數</th>
                <th width="10%">評價星等</th>
                <th width="5%">封鎖次數</th>
                <th width="25%">PR分數(歷程)</th>
            </tr>
            @foreach($results as $row)
                @php
                    $result['isBlocked'] = \App\Models\SimpleTables\banned_users::where('member_id', 'like', $row['user_id'])->get()->first();
                    if(!isset($result['isBlocked'])){
                        $result['isBlocked'] = \App\Models\BannedUsersImplicitly::select(\DB::raw('id, "隱性" as type'))->where('target', 'like', $row['user_id'])->get()->first();
                    }
                    $userInfo=\App\Models\User::findById($row['user_id']);
                    $user['name'] = $userInfo->name;
                    $user['engroup'] = $userInfo->engroup;
                    $user['last_login'] = $userInfo->last_login;
                    $user['vip'] = \App\Models\Vip::vip_diamond($userInfo->id);
                    $user['tipcount'] = \App\Models\Tip::TipCount_ChangeGood($userInfo->id);
                    $user['exchange_period'] = $userInfo->exchange_period;
                    $user['warnedicon'] = \App\Models\User::warned_icondata($row['user_id']);

                @endphp
                <tr>
                    <td><a href="{{ route('users/advInfo', $row['user_id']) }}" target='_blank' >{{$row['email']}}</a></td>
                    <td @if($result['isBlocked']) style="background-color:#FFFF00" @endif>
                        <a href="{{ route('users/advInfo', $row['user_id']) }}" target='_blank'>
                            <p @if($user['engroup'] == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                                {{$row['name']}}
                                @if($user['vip'])
                                    @if($user['vip']=='diamond_black')
                                        <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                    @else
                                        @for($z = 0; $z < $user['vip']; $z++)
                                            <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                        @endfor
                                    @endif
                                @endif
                                @if(isset($user['tipcount']))
                                    @for($i = 0; $i < $user['tipcount']; $i++)
                                        👍
                                    @endfor
                                @else
                                    {{ logger('reportedUsers, line 80 tipcount does not exists, user id: ' . $result['reported_id']) }}
                                @endif
                                @if(!is_null($result['isBlocked']))
                                    @if(!is_null($result['isBlocked']['expire_date']))
                                        @if(round((strtotime($result['isBlocked']['expire_date']) - getdate()[0])/3600/24)>0)
                                            {{ round((strtotime($result['isBlocked']['expire_date']) - getdate()[0])/3600/24 ) }}天
                                        @else
                                            此會員登入後將自動解除封鎖
                                        @endif
                                    @elseif(isset($result['isBlocked']['type']))
                                        (隱性)
                                    @else
                                        (永久)
                                    @endif
                                @endif
                                @if($user['warnedicon']['isAdminWarned']==1 OR $user['warnedicon']['isWarned']==1)
                                    <img src="/img/warned_red.png" style="height: 16px;width: 16px;">
                                @endif
                                @if($user['warnedicon']['isWarned']==0 AND $user['warnedicon']['WarnedScore']>10 AND $user['warnedicon']['auth_status']==1)
                                    <img src="/img/warned_black.png" style="height: 16px;width: 16px;">
                                @endif
                            </p>
                        </a>
                    </td>
                    <td>{{$row['title']}}</td>
                    <td>@if($row['engroup']==2)女@else男@endif</td>
                    <td>{{\App\Models\Vip::vipMonths($row['user_id'])}}</td>
                    <td>{{substr($row['created_at'],0,10)}}</td>
                    <td>{{substr($row['last_login'],0,10)}}</td>
                    <td>{{\App\Models\Reported::cntr($row['user_id'])}}</td>
                    <td>
                        @php
                            $userRating = \App\Models\User::rating($row['user_id']);
                        @endphp
                        @for ($i = 1; $i <= 5; $i++)
                            @if(intval($userRating) >= $i)
                                <img src="/new/images/sxx_1.png" style="height: 20px;">
                            @elseif(strstr($userRating, '.') && ctype_digit($userRating) == false)
                                <img src="/new/images/sxx_2.png" style="height: 20px;">
                                @break
                            @endif
                        @endfor
                        @for ($i = 1; $i <= 5 - round($userRating); $i++)
                            <img src="/new/images/sxx_4.png" style="height: 20px;">
                        @endfor
                    </td>
                    <td>{{\App\Models\Blocked::countBlocked($row['user_id'])}}</td>
                    <td>
                        {{\App\Models\User::PR($row['user_id'])}}<br>
                        @php
                        $pr = DB::table('pr_log')->where('user_id',$row['user_id'])->orderBy('created_at','desc')->first();
                        @endphp
                        @if(isset($pr))
                            {{$pr->pr_log}}
                        @endif
                    </td>

                </tr>
            @endforeach
        </table>
    @else
        查無資料
    @endif
</body>
<script>

    const allRanges = document.querySelectorAll(".range-wrap");
    allRanges.forEach(wrap => {
        const range = wrap.querySelector(".range");
        const bubble = wrap.querySelector(".bubble");

        range.addEventListener("input", () => {
            setBubble(range, bubble);
        });
        setBubble(range, bubble);
    });

    function setBubble(range, bubble) {
        const val = range.value;
        const min = range.min ? range.min : 0;
        const max = range.max ? range.max : 100;
        const newVal = Number(((val - min) * 100) / (max - min));
        bubble.innerHTML = val;

        // Sorta magic numbers based on size of the native UI thumb
        bubble.style.left = `calc(${newVal}% + (${8 - newVal * 0.15}px))`;
    }

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
        // $('.submit').click(
        //     function() {
        //         $('#msg2').val($('#msg').val());
        //         $('#message').submit();
        //     });
        $('.delete-btn').on('click', function(e) {
            if (!confirm('確定要刪除選取的訊息?')) {
                e.preventDefault();
            }
        });
        $('#reported_meg').click(function(event) {
            let date_start = $('#datepicker_1').val() ? $('#datepicker_1').val() : '1970-01-01';
            let date_end = $('#datepicker_2').val() ? $('#datepicker_2').val() : $.datepicker.formatDate('yy-mm-dd', new Date());
            let href = $(this).attr('href');
            $(this).attr('href', href + "?date_start=" + date_start + "&date_end=" + date_end);
        });
        // $('a[data-toggle=modal], button[data-toggle=modal]').click(function () {
        //     var data_id = '';
        //     if (typeof $(this).data('id') !== 'undefined') {
        //         data_id = $(this).data('id');
        //         $("#exampleModalLabel").html('封鎖 '+ $(this).data('name'))
        //     }
        //     $("#send_blockade").attr('href', data_id);
        // })
        // $('.advertising').on('click', function(e) {
        //     $('.m-reason').val('廣告');
        // });
        // $('.improper-behavior').on('click', function(e) {
        //     $('.m-reason').val('非徵求包養行為');
        // });
        // $('.improper-words').on('click', function(e) {
        //     $('.m-reason').val('用詞不當');
        // });
        // $('.improper-photo').on('click', function(e) {
        //     $('.m-reason').val('照片不當');
        // });
    });

    

    function selectAll() {
        $('.boxes').each(
            function() {
                if ($(this).is(':checked')) {
                    $(this).prop("checked", false);
                } else {
                    $(this).selected();
                }
            });

    }

    function set_end_date() {
        $('#datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
        $('.datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
    }

    function str_pad(n) {
        return String("00" + n).slice(-2);
    }

    function toggleBanned(id) {
        //  http://sugar.formal/5814
        let url = "{{ url("") }}";
        window.open(url + '/admin/users/toggleUserBlock/' + id);
        history.go(0);
    }
    // let count = 0;
    // function setDays(a, key) {
    //     if (count === 0) {
    //         let href = a.href;
    //         if(key === '') {
    //             let reason = $('.m-reason').val();
    //             $('.ban-user' + key).attr("href", href + '/' + $('.days' + key).val() + '&' + reason);
    //         }else{
    //             $('.ban-user' + key).attr("href", href + '/' + $('.days' + key).val());
    //         }
    //     }
    //     count++;
    // }
</script>
@stop