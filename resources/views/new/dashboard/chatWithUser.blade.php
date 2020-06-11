@extends('new.layouts.website')
<style>
    .chatWith > img {
        width: 40px;
        height: 40px;
        float: left;
        border-radius: 100px;
        object-fit: cover;
    }

    .dropbtn {
        /*background-color: #3498DB;*/
        /*color: white;*/
        /*padding: 16px;*/
        /*font-size: 16px;*/
        border: none;
        cursor: pointer;
    }

    .dropbtn:hover, .dropbtn:focus {
        /*background-color: #2980B9;*/
    }

    .dropdown {
        position: relative;
        display: inline-block;
        float: right;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        color: #e44e71;
        background-color: #ffe4e7;
        min-width: 100px;
        overflow: auto;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
        border-radius: 5px;
    }

    .dropdown-content a {
        color: #e44e71;
        padding: 6px 6px 0 12px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content.show{
        right: 0;
        margin-top: 35px;
        padding: 0;
    }
    .dropdown-content a:hover {background-color: #e44e71; color: #ffffff;}

    .show {display: block;}
    .alert{
        padding: 0 !important;
        border: none !important;
        box-shadow: none !important;
        position: inherit;
        float: left;
    }
    .shdel_word {
        background-color: #ffffff;
        border-radius: 11px;
        width: auto !important;
        height: auto !important;
        bottom: -4px !important;
        border: #fd5678 1px solid;

    }
    .shdel_word>span {
        padding-right: 1px;
        padding-left: 1px;
        font-size: 11px;
        color: #fd5678;
    }
    .shdel_word>i {
        font-size: 11pt;
        color: #fd5678 ;
        /*padding-top: 2px;*/
    }

    .shdel {
        background-color: #ffffff;
        border-radius: 11px;
        /*width: auto !important;*/
        height: auto !important;
        bottom: -4px !important;
        /*border: #fd5678 1px solid;*/

    }
    .shdel>span {
        padding-right: 1px;
        padding-left: 1px;
        font-size: 11px;
        color: #fd5678;
    }
    .shdel>i {
        font-size: 11pt;
        color: #fd5678 ;
        /*padding-top: 2px;*/
    }
    .message_fixed{
        position: fixed;
    }
</style>
@section('app-content')
    <div class="container matop70 chat">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                @if(isset($to))
                    <div class="shouxq">
                        <a href="{!! url('dashboard/chat2/'.csrf_token().\Carbon\Carbon::now()->timestamp) !!}"><img src="/new/images/xq_06.png" class="xlimg"></a><span><a href="/dashboard/viewuser/{{$to->id}}" style="color: #fd5678;">{{$to->name}}</a></span>
                        @if($user->engroup==1)
                            <?php $orderNumber = \App\Models\Vip::lastid() . $user->id; $code = Config::get('social.payment.code');?>
                            <form action="{{ Config::get('social.payment.actionURL') }}" style="float: right; position: relative;" method="POST" id="form1">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                <input type="hidden" name="userId" value="{{ $user->id }}">
                                <input type="hidden" name="to" value="@if(isset($to)) {{ $to->id }} @endif">
                                <input type=hidden name="MerchantNumber" value="761404">
                                <input type=hidden name="OrderNumber" value="{{ $orderNumber }}">
                                <input type=hidden name="OrgOrderNumber" value="SG-車馬費({{ $user->id }})">
                                <input type=hidden name="ApproveFlag" value="1">
                                <input type=hidden name="DepositFlag" value="1">
                                <input type=hidden name="iphonepage" value="0">
                                <input type=hidden name="Amount" value={{ Config::get('social.payment.tip-amount') }}>
                                <input type=hidden name="op" value="AcceptPayment">
                                <input type=hidden name="checksum" value="{{ md5("761404".$orderNumber.$code.Config::get('social.payment.tip-amount')) }}">
                                <input type=hidden name="ReturnURL" value="{{ route('chatpay') }}">
                                <button type="button" class="paypay" onclick="checkPay('form1')"><a class="nnn_adbut">車馬費2</a></button>
                            </form>
                            <form class="" style="float: right; position: relative;" action="{{ route('chatpay_ec') }}" method=post id="ecpay">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                <input type="hidden" name="userId" value="{{ $user->id }}">
                                <input type="hidden" name="to" value="@if(isset($to)) {{ $to->id }} @endif">
                                <button type="button" class="paypay" onclick="checkPay('ecpay')"><a class="nnn_adbut">車馬費1</a></button>
                            </form>
                        @else
                            <button style="float: right; position: relative;" type="button" class="paypay" onclick="c2('這是Daddy主動發起的，請提醒Daddy按此按紐發動車馬費邀請！')"><a class="nnn_adbut" style="margin-top: -5px">車馬費2</a></button>
                            <button style="float: right; position: relative;" type="button" class="paypay" onclick="c2('這是Daddy主動發起的，請提醒Daddy按此按紐發動車馬費邀請！')"><a class="nnn_adbut" style="margin-top: -5px">車馬費1</a></button>
                        @endif
                    </div>
                @else
                    {{ logger('Chat with non-existing user: ' . url()->current()) }}
                @endif
                <div class="message">
                    @php
                        $date_temp='';
                    @endphp
                    @if(!empty($messages))
                        @foreach ($messages as $message)
                            @php
                                $msgUser = \App\Models\User::findById($message->from_id);
                                \App\Models\Message::read($message, $user->id);
                            @endphp

                            @if($date_temp != substr($message['created_at'],0,10)) <div class="sebg matopj10">{{substr($message['created_at'],0,10)}}</div>@endif
                            @if($message['sys_notice']==0)
                            <div class="@if($message['from_id'] == $user->id) show @else send @endif">
                                <div class="msg @if($message['from_id'] == $user->id) msg1 @endif">
                                    @if($message['from_id'] == $user->id)
                                        <img src="@if(file_exists( public_path().$user->meta_()->pic ) && $user->meta_()->pic != ""){{$user->meta_()->pic}} @elseif($user->engroup==2)/new/images/female.png @else/new/images/male.png @endif">
                                    @else
                                        <a class="chatWith" href="{{ url('/dashboard/viewuser/' . $msgUser->id ) }}">
                                        <img src="@if(file_exists( public_path().$msgUser->meta_()->pic ) && $msgUser->meta_()->pic != ""){{$msgUser->meta_()->pic}} @elseif($msgUser->engroup==2)/new/images/female.png @else/new/images/male.png  @endif">
                                        </a>
                                    @endif
                                    <p>
                                        <i class="msg_input"></i>{!! nl2br($message['content']) !!}
                                        @if($message['from_id'] != $user->id)
                                            <a href="javascript:void(0)" class="" onclick="banned('{{$msgUser->id}}','{{$msgUser->name}}');" title="檢舉">
{{--                                                <span class="shdel_word"><span>檢舉</span><span>--}}
                                                <img src="/new/images/ban.png" class="shdel" alt="檢舉">
                                            </a>
                                        @endif
                                        <font class="sent_ri @if($message['from_id'] == $user->id)dr_l @if(!$isVip) novip @endif @else dr_r @endif">
                                            <span>{{ substr($message['created_at'],11,5) }}</span>
                                            @if(!$isVip && $message['from_id'] == $user->id)
                                                <span style="color:lightgrey;">已讀/未讀</span>
                                                <img src="/new/images/icon_35.png" style="position: absolute;float: left;left: 10px; top:20px;-moz-transform:rotate(-25deg);-webkit-transform:rotate(-30deg);">
                                            @else
                                            <span>@if($message['read'] == "Y" && $message['from_id'] == $user->id) 已讀 @elseif($message['read'] == "N" && $message['from_id'] == $user->id) 未讀 @endif</span>
                                            @endif


                                        </font>
                                    </p>
                                </div>
                            </div>
                            @elseif($message['from_id'] == $user->id)
                                <div class="send">
                                    <div class="msg">
                                        <img src="/new/images/admin-avatar.jpg">
                                        <p style="background: #DDF3FF;">
                                            <i class="msg_input_blue"></i>
                                            {!! nl2br($message['content']) !!}
                                            <font class="sent_ri @if(!$isVip) novip @endif dr_r">
                                                <span>{{ substr($message['created_at'],11,5) }}</span>
                                            </font>
                                        </p>
                                    </div>
                                </div>
                            @endif
                            @php
                                $date_temp = substr($message['created_at'],0,10);
                            @endphp
                        @endforeach
                    @endif
                    <div style="text-align: center;">
                        {!! $messages->appends(request()->input())->links('pagination::sg-pages2') !!}
                    </div>
                </div>
                @if(isset($to))
                    <div class="se_text_bot" id="message_input">
                        <form style="margin: 0 auto;" method="POST" action="/dashboard/chat2/{{ \Carbon\Carbon::now()->timestamp }}" id="chatForm">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                            <input type="hidden" name="userId" value="{{$user->id}}">
                            <input type="hidden" name="to" value="{{$to->id}}">
                            <input type="hidden" name="m_time" @if(isset($m_time)) value="{{ $m_time }}" @else value="" @endif>
                            <input type="hidden" name="{{ \Carbon\Carbon::now()->timestamp }}" value="{{ \Carbon\Carbon::now()->timestamp }}">
                            <textarea name="msg" cols="" rows="" class="se_text msg" id="msg" placeholder="請輸入" required></textarea>
                            <div class="message_fixed"></div>
                            <input type="submit" id="msgsnd" class="se_tbut matop20 msgsnd" value="回覆">
                        </form>
                    </div>
                @else
                    <div class="se_text_bot">
                        此會員資料已不存在。
                    </div>
                    {{ logger('Chat with non-existing user: ' . url()->current()) }}
                @endif
            </div>
        </div>
    </div>
    <div class="bl bl_tab" id="tab_payAlert">
        <div class="bltitle bltitle_fixed"><span>車馬費說明</span></div>
        <a id="" onclick="$('.blbg').click();" class="bl_gb bl_gb_fixed"><img src="/new/images/gb_icon.png"></a>
        <div class="n_blnr01 matop20">
            <div class="n_fengs">
            @if(isset($tippopup))
                {!! $tippopup !!}
            @endif
            </div>
            <div class="n_bbutton">
                <span><a class="n_left" href="javascript:">確認</a></span>
                <span><a onclick="$('.blbg').click();" class="n_right" href="javascript:">取消</a></span>
            </div>
        </div>

    </div>

    <div class="bl bl_tab" id="show_banned">
        <div class="bltitle banned_name"></div>
        <div class="n_blnr01">
            <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ route('reportPost') }}">
                {!! csrf_field() !!}
                <input type="hidden" name="aid" value="{{$user->id}}">
                <input type="hidden" name="uid" value="">
                <textarea name="content" cols="" rows="" class="n_nutext" placeholder="請輸入檢舉理由"></textarea>
{{--                <div class="n_bbutton">--}}
{{--                    <button type="submit" class="n_bllbut" style="border-style: none;">送出</button>--}}
{{--                </div>--}}
                <div class="n_bbutton" style="width: 100%; text-align: center;">
                    <button type="submit" class="n_right" style="border-style: none; background: #8a9ff0; color:#ffffff; float: unset; margin-left: 0px; margin-right: 20px;">送出</button>
                    <button type="reset" class="n_left" style="border: 1px solid #8a9ff0; background: #ffffff; color:#8a9ff0; float: unset; margin-right: 0px;" onclick="$('#show_banned').hide();$('.announce_bg').hide()">返回</button>
                </div>
            </form>
        </div>
        <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>
@stop
@section('javascript')
<script>
    $(".nnn_adbut").click(function(){
        if($(this).hasClass("adbut_on")){
            $(this).removeClass("adbut_on");
        }else{
            $(this).addClass("adbut_on");
        }

    });
    $.ajaxSetup({ cache: false });
    $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
        // you can use originalOptions.type || options.type to restrict specific type of requests
        options.data = jQuery.param($.extend(originalOptions.data||{}, {
            timeStamp: new Date().getTime()
        }));
    });
    d = new Date('{{ \App\Models\Message::$date }}');
            @if(isset($m_time))
    let m_time = '{{ $m_time }}';
            @else
    let m_time = '';
    @endif
        let isVip = '{{$user->isVip()}}';
    if(isVip==0){
        $( ".message_fixed" ).append( "<div><a href='{!! url('dashboard/vip') !!}' style='color: red;' class='tips'>成為VIP即可知道對方是否讀取信件哦！</a></div>" );
    }

    $('#msg').keyup(function(e) {
        let msgsnd = $('.msgsnd');

        if(e.key == " "){
            $('.alert').remove();
            // $("<div><a style='color: red; font-weight: bold;' class='alert'>請勿僅輸入空白！</a></div>").insertAfter(this);
            $( ".message_fixed" ).html();
            $( ".message_fixed" ).append( "<div><a style='color: red; font-weight: bold;' class='alert'>請勿僅輸入空白！</a></div>" );
            msgsnd.prop('disabled', true);
        }else if(e.key == "Backspace" && $.trim($("#msg").val()).length > 0){
            $('.alert').remove();
            msgsnd.prop('disabled', !checkForm());
        }else{
            $('.alert').remove();
            msgsnd.prop('disabled', !checkForm());
        }
    });

    setTimeout(function() {
        window.location.reload();
    }, 300000);

    $('#chatForm').submit(function () {
        let content = $('#msg').val(), msgsnd = $('.msgsnd');
        if($.trim(content) == "" ){
            $('.alert').remove();
            // $("<a style='color: red; font-weight: bold;' class='alert'>請勿僅輸入空白！</a>").insertAfter($('.msg'));
            $( ".message_fixed" ).html();
            $( ".message_fixed" ).append( "<div><a style='color: red; font-weight: bold;' class='alert'>請勿僅輸入空白！</a></div>" );
            msgsnd.prop('disabled', true);
            return checkForm;
        }
        else {
            $('.alert').remove();
            return checkForm;
        }
    });
    function checkForm(){
                @if(isset($m_time))
        let m_time = '{{ $m_time }}';
                @else
        let m_time = '';
        @endif
        if(m_time) {
            let intervalSecs = 60;
                    @if(isset($m_time))
            let m_time = '{{ $m_time }}';
                    @else
            let m_time = '';
            @endif
            // Split timestamp into [ Y, M, D, h, m, s ]
            let t = m_time.split(/[- :]/);
            // Apply each element to the Date function
            m_time = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4], t[5]));
            m_time.setHours(m_time.getHours() - 8);
            let now = new Date();
            let diff = now.getTime() - m_time.getTime();
            let diffInSec = Math.floor(diff / 1000);
            //return diffInSec >= intervalSecs;
            return true;
        }
        else{
            return true;
        }
    }

    $(".blbg").hide();
    @if(isset($to))
        $('.delete-btn').on('click',function(){
            c4('確定要刪除嗎?');
            var ct_time = $(this).data('ct_time');
            var content = $(this).data('content');
            var id = $(this).data('id');
            $(".n_left").on('click', function() {
                $.post('{{ route('delete2Single') }}', {
                    uid: '{{ $user->id }}',
                    sid: '{{ $to->id }}',
                    ct_time: ct_time,
                    content: content,
                    id: id,
                    _token: '{{ csrf_token() }}'
                }, function (data) {
                    //window.location.reload();
                    $("#tab04").hide();
                    c2('刪除成功');
                });
            });
        });
    @endif

    function  checkPay(id){
        $(".blbg").show();
        $('#tab_payAlert').show();
        $(".n_left").on('click', function() {
            //alert(1);
            $(".blbg").hide();
            $('#tab_payAlert').hide();
            $('#'+id).submit();
        });
    }


    //alert($('#message_input').height());
    var message_max_height,bl_gb_fixed_top,bl_gb_fixed_right;
    var message_height = $(window).height() - $('#message_input').height() - $('.shouxq').height();
    var footer_height = $('.bot').height();
    if($(window).height()<=601){
        message_max_height = message_height - $('.hetop').height() - 50;
    }else{
        message_max_height = message_height - footer_height - $('.hetop').height() - 140;
        $('.se_text_bot').addClass('se_text_bot_add_bottom');
    }

    $('.message').css('width',$('.shouxq').width()-20);
    $('.se_text').css('width',$('.shouxq').width());
    if(window.matchMedia("(max-width: 767px)").matches && window.matchMedia("(max-height: 823px)").matches){
        $('.se_text_bot').removeClass('se_text_bot_add_bottom');
        $('.bot').hide();

        message_max_height = message_height - $('.heicon').height() - 50;
        bl_gb_fixed_top = $(window).height() / 5 + 10;
        $('.bltitle_fixed').css('width',$('#tab_payAlert').width()+1);
        $('.bl_gb_fixed').css('top',bl_gb_fixed_top);
        $('#tab_payAlert').css('height','70%');

    }
    if(window.matchMedia("(min-width: 1024px)").matches && window.matchMedia("(max-height: 690px)").matches){
        bl_gb_fixed_top = $(window).height() / 10 - 5;
        bl_gb_fixed_right = $(window).width() / 3 - 5;
        //alert(bl_gb_fixed_right);
        $('.bltitle_fixed').css('width',$('#tab_payAlert').width());
        $('.bl_gb_fixed').css('top',bl_gb_fixed_top);
        $('.bl_gb_fixed').css('right',bl_gb_fixed_right);
        $('.matop20').css('margin-top','40px !important');
    }

    $('.message').css('max-height',message_max_height);

    $(window).resize(function() {
        var message_max_height,bl_gb_fixed_top,bl_gb_fixed_right;
        var message_height = $(window).height() - $('#message_input').height() - $('.shouxq').height();
        var footer_height = $('.bot').height();
        if($(window).height()<=601){
            message_max_height = message_height - $('.hetop').height() - 50;
        }else{
            message_max_height = message_height - footer_height - $('.hetop').height() - 140;
            $('.se_text_bot').addClass('se_text_bot_add_bottom');

        }
        $('.message').css('width',$('.shouxq').width()-20);
        $('.se_text').css('width',$('.shouxq').width());
        // if( /Android|iPhone/i.test(navigator.userAgent) ) {
        if(window.matchMedia("(max-width: 767px)").matches && window.matchMedia("(max-height: 823px)").matches){
            $('.se_text_bot').removeClass('se_text_bot_add_bottom');
            $('.bot').hide();

            message_max_height = message_height - $('.heicon').height() - 50;
            bl_gb_fixed_top = $(window).height() / 5 + 10;
            //alert(bl_gb_fixed_top);
            $('.bltitle_fixed').css('width',$('#tab_payAlert').width()+1);
            $('.bl_gb_fixed').css('top',bl_gb_fixed_top);
            $('#tab_payAlert').css('height','70%');

        }
        if(window.matchMedia("(min-width: 1024px)").matches && window.matchMedia("(max-height: 690px)").matches){
            bl_gb_fixed_top = $(window).height() / 10 - 5;
            bl_gb_fixed_right = $(window).width() / 3 - 5;
            //alert(bl_gb_fixed_right);
            $('.bltitle_fixed').css('width',$('#tab_payAlert').width());
            $('.bl_gb_fixed').css('top',bl_gb_fixed_top);
            $('.bl_gb_fixed').css('right',bl_gb_fixed_right);
            $('.matop20').css('margin-top','40px !important');
        }
        // if(window.matchMedia("(min-width: 1024px)").matches){
        //     bl_gb_fixed_top = $(window).height() / 10;
        //     $('.bl_gb_fixed').css('top',bl_gb_fixed_top);
        // }
        $('.message').css('max-height',message_max_height);
    });

    $(window).scroll(function() {
        if($(window).scrollTop() + $(window).height() > $(document).height()-50) {
            if(window.matchMedia("(max-width: 767px)").matches){
                $('.se_text_bot').removeClass('se_text_bot_add_bottom');

            }else {
                $('.se_text_bot').addClass('se_text_bot_add_bottom');
            }
        }
        else{
            $('.se_text_bot').removeClass('se_text_bot_add_bottom');
        }
    });

    function banned(sid,name){
        $("input[name='uid']").val(sid);
        $(".banned_name").append("<span>檢舉" + name + "</span>")
        $(".announce_bg").show();
        $("#show_banned").show();
    }

    @if (Session::has('message'))
        c3('{{ Session::get('message') }}');
    @endif

    @if (isset($errors) && $errors->count() > 0)
        @foreach ($errors->all() as $error)
            c5('{{ $error }}');
        @endforeach
    @endif

    function dropFun() {
        document.getElementById("myDropdown").classList.toggle("show");
    }
    window.onclick = function(event) {
        if (!event.target.matches('.dropbtn')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            var i;
            for (i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
</script>
@stop