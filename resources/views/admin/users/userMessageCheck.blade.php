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
                        value="@if(isset($_GET['date_start'])){{$_GET['date_start']}}@else{{ old('date_start') }}@endif"
                        class="form-control datepick">
                </td>
            <tr>
                <th>訊息結束時間</th>
                <td>
                    <input type='text' id="datepicker_2" name="message_date_end"
                        value="@if(isset($_GET['date_end'])){{$_GET['date_end']}}@else{{ old('date_end') }}@endif"
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
                        value="@if(isset($_GET['date_start'])){{$_GET['date_start']}}@else{{ old('date_start') }}@endif"
                        class="form-control datepick">
                </td>
            <tr>
                <th>登入結束時間</th>
                <td>
                    <input type='text' id="datepicker_4" name="date_end"
                        value="@if(isset($_GET['date_end'])){{$_GET['date_end']}}@else{{ old('date_end') }}@endif"
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
                    <input type="radio" name="en_group" value="1" @if(isset($_GET['en_group']) && $_GET['en_group']==1)
                        checked @endif>男
                    <input type="radio" name="en_group" value="2" @if(isset($_GET['en_group']) && $_GET['en_group']==2)
                        checked @endif>女
                </td>
            </tr>
            <tr>
                <th>訊息發送人數</th>
                <td>
                    <input type="text" name="total" value="{{ request()->get('total') }}">
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

    @if($data->isNotEmpty())
    <div>
        <table class="table-hover table table-bordered">
            <tr>
                <td width="12%">會員資訊</td>
                <td width="12%">關於我&約會模式</td>
            </tr>
            @foreach ($data as $key =>$fromUser)
            <tr id="list">
                <td style="{{ $fromUser->isBanned($fromUser->id) ? 'background-color:#FFFF00' : ($fromUser->isAdminWarned() ? 'background-color:#B0FFB1' : '') }}">
                    email: <a {{ $fromUser->is_real }} href="{{ route('users/advInfo', ['id' => $fromUser->id]) }}" target="_blank">{{ $fromUser->email }}<a>
                    <br>
                    暱稱: {{ $fromUser->name }}
                    <br>
                    抬頭: {{ $fromUser->title }}
                    {!! $fromUser->isPhoneAuth() ? '<br>通過手機驗證' : '' !!}
                    {!! $fromUser->is_real ? '' : '<br>是本人' !!}
                    {!! $fromUser->isAdvAuthUsable ? '' : '<br>進階驗證' !!}
                    <br>
                </td>
                <td>
                    <p class="about-me" title="{{ $fromUser->user_meta->about }}">關於我: {{ strLimit($fromUser->user_meta->about, 20) }}</p>
                    <br>
                    <p class="date-mode" title="{{ $fromUser->user_meta->style }}">約會模式: {{ strLimit($fromUser->user_meta->style, 20) }}</p>
                </td>
                <td>
                    @foreach ($fromUser->toUser as $countKey => $toUser)
                    <div style="{{ $fromUser->isBanned($toUser->id) ? 'background-color:#FFFF00' : ($toUser->isAdminWarned() ? 'background-color:#B0FFB1' : '') }}">
                    email: <a href="{{ route('users/advInfo', ['id' => $toUser->id]) }}" target="_blank">{{ $toUser->email }}</a> ({{ $toUser->count }})
                    </div>
                    @endforeach
                </td>
            </tr>
            @endforeach
        </table>
        {!! $data->isNotEmpty() ? $data->links('pagination::sg-pages') : "" !!}
        <br>
    </div>
    @endif

</body>

<!-- Modal -->
<div class="modal fade" id="banModal" tabindex="-1" role="dialog" aria-labelledby="banModal" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="banModal_title">封鎖</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/admin/users/toggleUserBlock" method="POST" id="clickToggleUserBlock">
                {!! csrf_field() !!}
                <input type="hidden" value="" name="user_id" id="blockUserID">
                <input type="hidden" value="member_check_step1" name="page">
                <input type="hidden" name="vip_pass" value=0>
                <input type="hidden" name="adv_auth" value=0>
                <div class="modal-body">
                    封鎖時間
                    <select name="days" class="days">
                        <option value="3">三天</option>
                        <option value="7">七天</option>
                        <option value="15">十五天</option>
                        <option value="30">三十天</option>
                        <option value="X" selected>永久</option>
                    </select>
                    <hr>
                    封鎖原因
                    <div id='banReason'>
                    </div>
                    <br><br>
                    <textarea class="form-control m-reason" name="reason" id="msg" rows="4"
                        maxlength="200">廣告</textarea>
                    <label style="margin:10px 0px;">
                        <input type="checkbox" name="addreason" style="vertical-align:middle;width:20px;height:20px;" />
                        <sapn style="vertical-align:middle;">加入常用封鎖原因</sapn>
                    </label>
                    <hr>
                    <div id="auto_ban_title">
                        新增自動封鎖條件
                    </div>
                    <div class="form-group">
                        <label for="cfp_id" id='cfp_id_title'>CFP_ID</label>
                        <select multiple class="form-control" id="cfpid" name="cfp_id[]">
                        </select>
                    </div>
                    <div class="form-group">
                        <label id='pic_title'>照片</label>
                        <div id="autoban_pic_gather">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ip">IP</label>
                        <table id="table_userLogin_log" class="table table-hover table-bordered">
                        </table>
                    </div>
                    <hr>
                    <div id="add_auto_ban_title">
                        新增自動封鎖關鍵字(永久封鎖)
                    </div>
                    <input placeholder="1.請輸入封鎖關鍵字" onfocus="this.placeholder=''" onblur="this.placeholder='1.請輸入封鎖關鍵字'"
                        class="form-control" type="text" name="addautoban[]" rows="1">
                    <input placeholder="2.請輸入封鎖關鍵字" onfocus="this.placeholder=''" onblur="this.placeholder='2.請輸入封鎖關鍵字'"
                        class="form-control" type="text" name="addautoban[]" rows="1">
                    <input placeholder="3.請輸入封鎖關鍵字" onfocus="this.placeholder=''" onblur="this.placeholder='3.請輸入封鎖關鍵字'"
                        class="form-control" type="text" name="addautoban[]" rows="1">
                </div>
                <div class="modal-footer" id="modal-footer">
                    <button type="submit" class="btn btn-outline-success ban-user">送出</button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">取消</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal -->

<!--照片查看-->
<div class="big_img">
    <!-- 自定義分頁器 -->
    <div class="swiper-num">
        <span class="active"></span>
        /
        <span class="total"></span>
    </div>
    <div class="swiper-container2">
        <div class="swiper-wrapper">
        </div>
    </div>
    <div class="swiper-pagination2"></div>
</div>
<!--照片查看-->

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

        return start_date.getFullYear() + '-' + str_pad(start_date.getMonth() + 1) + '-' + str_pad(start_date.getDate());
    }

    jQuery(document).ready(function () {
        datePickInit();

        $('.today').click(function () {
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
        $('.last3days').click(function () {
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
        $('.last10days').click(function () {
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
        $('.last15days').click(function () {
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
        $('.last30days').click(function () {
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

    $('.reset_btn').on('click', function () {
        // $('input:radio').removeAttr('checked');
        // $('#datepicker_1, #datepicker_2').removeAttr('value');
        $('form').reset();
    });

    function list()
    {
        $.ajax({
            url : `{{ route("users.message.check") }}`,
            type: 'get',
            data: $("form").serialize(),
            success: function (data) {
                const length = data.length;

                let str = '';

                for(let i = length; i--;) {
                    const dataChild = data[i];

                    str += `<td>
                            email: ${dataChild.email}
                            暱稱: ${dataChild.name}
                            抬頭: ${dataChild.title}
                            ${dataChild.isPhoneAuth ? '通過手機驗證' : ''}
                            ${dataChild.is_real ? '是本人' : ''}
                        </td>
                        <td>
                            關於我: ${dataChild.user_meta.about}
                            約會模式: ${dataChild.user_meta.style}
                        </td>`;
                }

                $("tr#list").append(str);
            },
        })
    }

    // $('form').submit(function () {
    //     list();

    //     return false;
    // });

    $('.btn_sid').on('click', function () {

        $('#sid').val($(this).data('sid'));
        $('#uid').val($(this).data('uid'));
        var reason = $(this).siblings('.reason').val();
        $('#reason').val(reason);

        let sid = $(this).data('sid'),
            r = false;

        if (sid == '') {
            r = confirm('是否確定加入可疑名單?');
        } else {
            r = confirm('是否確定移除可疑名單?');
        }

        if (r == true) {
            $('#sid_toggle').submit();
        }

    });

    $('.check_and_next_page').on('click', function () {
        r = confirm('是否確定本頁檢查完畢?');
        if (r == true) {
            $('#check_and_next_page').submit();
        }
    });

    //照片查看
    $(function () {
        var mySwiper = new Swiper('.swiper-container2', {
            pagination: '.swiper-pagination2',
            paginationClickable: true,
            onInit: function (swiper) {
                var active = swiper.activeIndex;
                $(".swiper-num .active").text(active);
            },
            onSlideChangeEnd: function (swiper) {
                var active = swiper.realIndex + 1;
                $(".swiper-num .active").text(active);
            }
        });

        $(".img_select").on("click", function () {
            var imgBox = $(this).closest(".evaluation_zoomIn").find("li");
            var i = $(imgBox).index(this);
            $(".big_img .swiper-wrapper").html("")

            for (var j = 0, c = imgBox.length; j < c; j++) {
                $(".big_img .swiper-wrapper").append('<div class="swiper-slide"><div class="cell"><img src="' + imgBox.eq(j).find("img").attr("src") + '" / ></div></div>');
            }
            mySwiper.updateSlidesSize();
            mySwiper.updatePagination();
            $(".big_img").css({
                "z-index": 1001,
                "opacity": "1"
            });
            //分页器
            var num = $(".swiper-pagination2 span").length;
            $(".swiper-num .total").text(num);
            $(".swiper-num .active").text(i + 1);

            mySwiper.slideTo(i, 0, false);
            return false;
        });

        $(".swiper-container2").click(function () {
            $(this).parent(".big_img").css({
                "z-index": "-1",
                "opacity": "0"
            });
        });
    });
    //照片查看

    //封鎖相關
    $('.ban_user').on('click', function () {
        uid = $(this).attr('data-uid');
        init_ban_modal();
        $('#banModal_title').append('封鎖');
        $('#auto_ban_title').append('新增自動封鎖條件');
        $('#cfp_id_title').append('CFP_ID');
        $('#pic_title').append('照片');
        $('#add_auto_ban_title').append('新增自動封鎖關鍵字 ( 永久封鎖 )');

        ban_form(uid);
    });

    $('.advance_auth_ban_user').on('click', function () {
        uid = $(this).attr('data-uid');
        advance_auth_status = $(this).attr('data-advance_auth_status');
        if (advance_auth_status == 1) {
            return false;
        }
        else {
            init_ban_modal();
            $('#banModal_title').append('封鎖 ( 驗證封鎖 )');
            $('#auto_ban_title').append('新增自動封鎖條件 ( 驗證封鎖 )');
            $('#cfp_id_title').append('CFP_ID ( 驗證封鎖 )');
            $('#pic_title').append('照片 ( 驗證封鎖 )');
            $('#add_auto_ban_title').append('新增自動封鎖關鍵字 ( 驗證封鎖 )');

            $("#clickToggleUserBlock input[name='adv_auth']").val(1);
            ban_form(uid);
        }
    });

    function init_ban_modal() {
        $('#banModal_title').empty();
        $('#auto_ban_title').empty();
        $('#cfp_id_title').empty();
        $('#pic_title').empty();
        $('#add_auto_ban_title').empty();

        $('#banReason').empty();
        $('#cfpid').empty();
        $('#autoban_pic_gather').empty();
        $('#table_userLogin_log').empty();
    }

    function ban_form(id) {
        this.uid = id;
        console.log(this.uid);
        $.ajax({
            type: 'GET',
            url: '/admin/message/check',
            data: { uid: this.uid },
            success: function (response) {

                $('#blockUserID').val(uid);

                response.banReason.forEach(function (value) {
                    $('#banReason').append('<a class="text-white btn btn-success banReason">' + value.content + '</a>');
                });

                $('.banReason').click(function () {
                    $('#msg').empty();
                    $('#msg').append($(this).text());
                });

                response.cfp_id.forEach(function (value) {
                    $('#cfpid').append('<option value=' + value.cfp_id + '>' + value.cfp_id + '</option>');
                });

                hstr = pic_tpl(response.meta);
                $('#autoban_pic_gather').append(hstr);

                response.member_pic.forEach(function (value) {
                    hstr = pic_tpl(value);
                    $('#autoban_pic_gather').append(hstr);
                });

                response.userLogin_log.forEach(function (value) {
                    htmlstr = '';
                    htmlstr = htmlstr + '<tr class="loginItem" id="showloginTimeIP' + value.loginMonth + '" data-sectionName="loginTimeIP' + value.loginMonth + '">';
                    htmlstr = htmlstr + '<td>';
                    htmlstr = htmlstr + '<span>' + value.loginMonth + ' [' + value.Ip.Ip_group.length + ']' + '</span>';
                    htmlstr = htmlstr + '</td>';
                    htmlstr = htmlstr + '</tr>';
                    htmlstr = htmlstr + '<tr class="showLog" id="loginTimeIP' + value.loginMonth + '">';
                    htmlstr = htmlstr + '<td>';
                    htmlstr = htmlstr + '<select multiple class="form-control" name="ip[]">';

                    for (let i = 0; i < value.Ip.Ip_group.length; i++) {
                        htmlstr = htmlstr + '<option value="' + value.Ip.Ip_group[i].ip + '">' + '[' + value.Ip.Ip_group[i].loginTime + ']  ' + value.Ip.Ip_group[i].ip + '</option>';
                    }

                    htmlstr = htmlstr + '</select>';
                    htmlstr = htmlstr + '</td>';
                    htmlstr = htmlstr + '</tr>';
                    $('#table_userLogin_log').append(htmlstr);
                });

                $('.showLog').hide();
                $('.loginItem').click(function () {
                    var sectionName = $(this).attr('data-sectionName');
                    $('.showLog').hide();
                    $('#' + sectionName).show();
                });

            },
            error: function (response) {
                alert('取得資料失敗');
            }
        });
    }

    function pic_tpl(picture) {
        html_str = '';
        if (picture.pic ?? false) {
            html_str = html_str + '<div class="autoban_pic_unit">';
            html_str = html_str + '<input type="checkbox" id="' + picture.pic.replace('/', '',) + '" name="pic[]" value="' + picture.pic + '" />';
            html_str = html_str + '<label for="' + picture.pic.replace('/', '',) + '">';
            html_str = html_str + '<span>';

            if ((picture.operator ?? false) || ((picture.deleted_at ?? false) && picture.deleted_at != '0000-00-00 00:00:00')) {
                html_str = html_str + '已刪';
            }
            else {
                html_str = html_str + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            }

            html_str = html_str + '</span>';
            html_str = html_str + '<img src="' + picture.pic + '" onerror="this.src=' + 'img/filenotexist.png' + '" />';
            html_str = html_str + '</label>';
            html_str = html_str + '</div>';
        }
        return html_str;
    }
    //封鎖相關


</script>
@stop