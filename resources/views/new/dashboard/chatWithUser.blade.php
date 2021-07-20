@extends('new.layouts.website')
<link href="https://fonts.googleapis.com/css?family=Roboto:400|700" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.2/photoswipe.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.2/default-skin/default-skin.min.css">
<link rel="stylesheet" href="{{ asset('css/photoswipe/styles.css') }}">
<link rel="stylesheet" href="{{ asset('css/photoswipe/prittyprint.css') }}">
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
        /*bottom: -4px !important;*/
        border: #fd5678 1px solid;
        position: relative;
        top: 20px;
        right: -10px;
        display: flex;
        float: right;

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
    .blur_img {
        filter: blur(2px);
        -webkit-filter: blur(2px);
    }
    .fileuploader-icon-remove:after {content: none !important;}
    .xxi{min-height:500px;width:92%; margin:0 auto}
    @media (max-width:1024px) {
        .xxi{min-height:920px;}
    }
    @media (max-width:992px) {
        .xxi{min-height:560px;}
    }

    @media (max-width:760px) {
        .xxi{min-height:360px;}
    }
    img{
        max-width: 100%;
    }
    .new_pot1  ::-webkit-scrollbar {
        /*滚动条整体样式*/
        width :4px;  /*高宽分别对应横竖滚动条的尺寸*/
        height: 1px;
    }
    .new_pot1  ::-webkit-scrollbar-thumb {
        /*滚动条里面小方块*/
        border-radius: 100px;
        background: #8a9fef;
    }
    .new_pot1  ::-webkit-scrollbar-track {
        /*滚动条里面轨道*/
        border-radius: 100px;
        background:rgba(255,255,255,0.6);
    }

    .message::-webkit-scrollbar {
        display: none;
    }
    .message {
        -ms-overflow-style: none;
    }
    .message {
        overflow: -moz-hidden-unscrollable; /*注意！若只打 hidden，chrome 的其它 hidden 會出問題*/
        height: 100%;
    }

    .message {
        height: 100%;
        width: calc(100vw + 18px); /*瀏覽器滾動條的長度大約是 18px*/
        overflow: auto;
    }
    .se_text_bot_add_bottom {
        bottom: 143px;
    }
    .pad_bot{ padding-bottom: 20px;}
    @media (max-width: 450px){
        .pad_bot{ padding-bottom:0px;}
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
{{--                    <div class="fbuttop"></div>--}}
                    <div class="shouxq" style="display: flex;">
                        <a class="nnn_adbut" href="{{ !empty(session()->get('goBackPage_chat2')) ? session()->get('goBackPage_chat2') : \Illuminate\Support\Facades\URL::previous() }}"><img class="nnn_adbut_img" src="{{ asset('/new/images/back_icon.png') }}" style="height: 15px;">返回</a>
                        <span style="flex: 6; text-align: center;">
                            <a href="/dashboard/viewuser/{{$to->id}}" style="color: #fd5678;">
                                <span class="se_rea">{{$to->name}}
{{--                                    <div id="onlineStatus"></div>--}}
                                    @if($isVip)
                                        @if($to->isOnline())
                                            <div class="onlineStatus"></div>
                                        @endif
                                    @else
                                        <div class="onlineStatusNonVip"><img src="/new/images/wsx.png"></div>
                                    @endif
                                </span>
                            </a>
                        </span>
                        @if($user->engroup==1)
                            <form class="" style="float: right; position: relative; text-align: right;" action="{{ route('chatpay_ec') }}" method=post id="ecpay">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                <input type="hidden" name="userId" value="{{ $user->id }}">
                                <input type="hidden" name="to" value="@if(isset($to)) {{ $to->id }} @endif">
                                <button type="button" class="paypay" onclick="checkPay('ecpay')"><a class="nnn_adbut">車馬費</a></button>
                            </form>
                        @else
                            <button style="float: right; position: relative;" type="button" class="paypay" onclick="c5('這是Daddy主動發起的，請提醒Daddy按此按紐發動車馬費邀請！')"><a class="nnn_adbut" style="margin-top: -15px">車馬費</a></button>
                        @endif
                    </div>
                @else
                    {{ logger('Chat with non-existing user: ' . url()->current()) }}
                @endif
                <div class="message pad_bot" >
                    @php
                        $date_temp='';
                        $isBlurAvatar = \App\Services\UserService::isBlurAvatar($to, $user);
                    @endphp
                    @if(!empty($messages))
                        @foreach ($messages as $message)
                            @php
                                $msgUser = \App\Models\User::findById($message->from_id);
                                \App\Models\Message::read($message, $user->id);
                            @endphp

                            @if($date_temp != substr($message['created_at'],0,10)) <div class="sebg matopj10">{{substr($message['created_at'],0,10)}}</div>@endif

                            @if($message['sys_notice']==1 || $msgUser->id == 1049)
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
                            @elseif($message['sys_notice']==0)
                            <div class="@if($message['from_id'] == $user->id) show @else send @endif">
                                <div class="msg @if($message['from_id'] == $user->id) msg1 @endif">
                                    @if($message['from_id'] == $user->id)
                                        <img src="@if(file_exists( public_path().$user->meta->pic ) && $user->meta->pic != ""){{$user->meta->pic}} @elseif($user->engroup==2)/new/images/female.png @else/new/images/male.png @endif">
                                    @else
                                        <a class="chatWith" href="{{ url('/dashboard/viewuser/' . $msgUser->id ) }}">
                                        <img class="@if($isBlurAvatar) blur_img @endif" src="@if(file_exists( public_path().$msgUser->meta->pic ) && $msgUser->meta->pic != ""){{$msgUser->meta->pic}} @elseif($msgUser->engroup==2)/new/images/female.png @else/new/images/male.png  @endif">
                                        </a>
                                    @endif
                                    <p>
                                        @if(!is_null(json_decode($message['pic'],true)))
                                            <i class="msg_input"></i>
                                            <span id="page" class="marl5">
                                                <span class="justify-content-center">
                                                    <span class="gutters-10 pswp--loaded" data-pswp="">
                                                        <span style="width: 150px;">
                                                            @foreach(json_decode($message['pic'],true) as $key => $pic)
                                                                @if(isset($pic['file_path']))
                                                                    <a href="{{$pic['file_path'] }}" target="_blank" data-pswp-index="{{ $key }}" class="pswp--item">
                                                                        <img src="{{ $pic['file_path'] }}" class="n_pic_lt">
                                                                    </a>
                                                                @else
                                                                    {{ logger("Message pic failed, user id: " . $user->id) }}
                                                                    {{ logger("to id: " . $to->id) }}
                                                                @endif
                                                            @endforeach
                                                         </span>
                                                    </span>
                                                 </span>
                                                <font class="sent_ri @if($message['from_id'] == $user->id)dr_l @if(!$isVip) novip @endif @else dr_r @endif">
                                                    <span>{{ substr($message['created_at'],11,5) }}</span>
                                                    @if(!$isVip && $message['from_id'] == $user->id)
                                                        <span style="color:lightgrey;">已讀/未讀</span>
                                                        <img src="/new/images/icon_35.png" style="position: absolute;float: left;left: 10px; top:20px;-moz-transform:rotate(-25deg);-webkit-transform:rotate(-30deg);">
                                                    @else
                                                        <span>@if($message['read'] == "Y" && $message['from_id'] == $user->id) 已讀 @elseif($message['read'] == "N" && $message['from_id'] == $user->id) 未讀 @endif</span>
                                                    @endif
                                                </font>
                                            </span>
                                            @if($message['from_id'] != $user->id)
                                                <a href="javascript:void(0)" class="" onclick="banned('{{$message['id']}}','{{$msgUser->id}}','{{$msgUser->name}}');" title="檢舉">
                                                    <span class="shdel" style="border: #fd5678 1px solid; width: auto;"><span>檢舉</span></span>
                                                </a>
                                            @endif
                                        @else
                                            <i class="msg_input"></i>{!! nl2br($message['content']) !!}
                                            @if($message['from_id'] != $user->id)
                                                <a href="javascript:void(0)" class="" onclick="banned('{{$message['id']}}','{{$msgUser->id}}','{{$msgUser->name}}');" title="檢舉">
                                                    <span class="shdel_word"><span>檢舉</span></span>
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
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @endif
                            @php
                                $date_temp = substr($message['created_at'],0,10);
                            @endphp
                        @endforeach
                    @endif
                    <div style="text-align: center; padding-bottom: 20px;">
                        {!! $messages->appends(request()->input())->links('pagination::sg-pages2') !!}
                    </div>
                </div>
                @if(isset($to))
                    {{--<div class="se_text_bot" id="message_input">
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
                    </div>--}}
                    <div class="se_text_bot"  id="message_input" style="padding-right: 3%; padding-left:3%;">
                        <form style="margin: 0 auto;" method="POST" action="/dashboard/chat2/{{ \Carbon\Carbon::now()->timestamp }}" id="chatForm" name="chatForm">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                            <input type="hidden" name="userId" value="{{$user->id}}">
                            <input type="hidden" name="to" value="{{$to->id}}">
                            <input type="hidden" name="m_time" @if(isset($m_time)) value="{{ $m_time }}" @else value="" @endif>
                            <input type="hidden" name="{{ \Carbon\Carbon::now()->timestamp }}" value="{{ \Carbon\Carbon::now()->timestamp }}">
                            <div class="xin_left">
                                <a class="xin_nleft" onclick="tab_uploadPic();"><img src="/new/images/moren_pic.png"></a>
                                <textarea id="msg" name="msg" rows="1" class="xin_input" placeholder="請輸入"></textarea>
                            </div>
                            <button type="submit" class="xin_right" style="border: none;"><img src="/new/images/fasong.png"></button>
                            {{--<div class="message_fixed"></div>--}}
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

    <div class="bl_tab_aa" id="show_banned">
        <div class="bl_tab_bb">
            <div class="bltitle banned_name"></div>
            <div class="new_pot new_poptk_nn new_pot001 ">
                <div class="fpt_pic new_po000">
                    <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ route('reportMsg') }}" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <input type="hidden" name="aid" value="{{$user->id}}">
                        <input type="hidden" name="uid" value="">
                        <input type="hidden" name="id" value="">
                        <textarea name="content" cols="" rows="" class="n_nutext" placeholder="{{$report_reason}}" required></textarea>
                        <input id="images" type="file" name="images" accept="image/*">
                        <div class="n_bbutton" style="width: 100%; text-align: center;">
                            <button type="submit" class="n_right" style="border-style: none; background: #8a9ff0; color:#ffffff; float: unset; margin-left: 0px; margin-right: 20px;">送出</button>
                            <button type="reset" class="n_left" style="border: 1px solid #8a9ff0; background: #ffffff; color:#8a9ff0; float: unset; margin-right: 0px;" onclick="show_banned_close()">返回</button>
                        </div>
                    </form>
                </div>
            </div>
            <a onclick="show_banned_close()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
        </div>
    </div>
    @if($to)
        <div class="bl_tab_aa" id="tab_uploadPic" style="display: none;">
            <form id="form_uploadPic" action="/dashboard/chat2/{{ \Carbon\Carbon::now()->timestamp }}" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                <input type="hidden" name="userId" value="{{ $user->id }}">
                <input type="hidden" name="from" value="{{ $user->id }}">
                <input type="hidden" name="to" value="{{ $to->id }}">
                <input type="hidden" name="msg" value="">
                <input type="hidden" name="m_time" @if(isset($m_time)) value="{{ $m_time }}" @else value="" @endif>
                <input type="hidden" name="{{ \Carbon\Carbon::now()->timestamp }}" value="{{ \Carbon\Carbon::now()->timestamp }}">
                <div class="bl_tab_bb">
                    <div class="bltitle"><span style="text-align: center; float: none;">上傳照片</span></div>
                    <div class="new_pot1 new_poptk_nn new_height_mobile ">
                        <div class="fpt_pic">
                            <input id="images" type="file" name="images" accept="image/*">
                            <div class="alert_tip" style="color:red;"></div>
                            <div class="n_bbutton" style="margin-top:0px;">
                                <a class="n_bllbut" onclick="form_uploadPic_submit()">送出</a>
                            </div>
                        </div>
                    </div>
                    <a onclick="tab_uploadPic_close()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
                </div>
            </form>
        </div>
    @endif
@stop
@section('javascript')
<link href="{{ asset('css/jquery.fileuploader.min.css') }}" media="all" rel="stylesheet">
<link href="{{ asset('new/css/fileupload.css') }}" media="all" rel="stylesheet">
<link href="{{ asset('css/font/font-fileuploader.css') }}" media="all" rel="stylesheet">
<script src="{{ asset('js/jquery.fileuploader.js') }}" type="text/javascript"></script>
<script src="https://rawgit.com/google/code-prettify/master/loader/run_prettify.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.2/photoswipe.min.js" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.2/photoswipe-ui-default.min.js" charset="utf-8"></script>
<script src="{{ asset('new/js/photoswipe-simplify.min.js') }}" charset="utf-8"></script>
<script>
    photoswipeSimplify.init({
        history: false,
        focus: false,
    });
</script>
<script>
    function readyNumber() {

        $('textarea').each(function () {
            this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
        }).on('input', function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
            textAreaHeight=38;
            var textAreaHeight = parseInt(this.scrollHeight)-38;
            $(".xin_nleft").css('margin-top',textAreaHeight + 'px');
            $(".xin_right").css('margin-top',textAreaHeight + 'px');
        })
    }

    readyNumber();
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
        $( ".message_fixed" ).append( "<div><a href='{!! url('dashboard/new_vip') !!}' style='color: red;' class='tips'>成為VIP即可知道對方是否讀取信件哦！</a></div>" );
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

    {{-- setTimeout(function() {
        window.location.reload();
    }, 300000); --}}

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
                    $("#tab04").hide();
                    c4('刪除成功');
                    window.location.reload();
                });
            });
        });
    @endif

    function userDeleteMessage(msgID) {
        c4('確定要刪除嗎?');
        $(".n_left").on('click', function() {
            $("#tab04").hide();
            $("#blbg").hide();
            $.ajax({
                type: 'POST',
                url: "/dashboard/chat2/deleteMsgByUser/"+msgID,
                data:{
                    _token: '{{csrf_token()}}',
                },
                dataType:"json",
                success: function(res){
                    c5(res.msg);
                    location.reload();
                }
            });
        });
    }

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
        message_max_height = message_height - footer_height - $('.hetop').height() - 30;
        //$('.se_text_bot').addClass('se_text_bot_add_bottom');
        $('.se_text_bot').css('bottom',$('.se_text_bot_add_bottom').height() -80);
    }
    $('.message').css('width',$('.shouxq').width()-20);
    $('.se_text').css('width',$('.shouxq').width());
    if(window.matchMedia("(max-width: 823px)").matches && window.matchMedia("(max-height: 823px)").matches){
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
            message_max_height = message_height - footer_height - $('.hetop').height() - 30;
            //$('.se_text_bot').addClass('se_text_bot_add_bottom');
            $('.se_text_bot').css('bottom',$('.se_text_bot_add_bottom').height() -80);

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
        if($(window).scrollTop() + $(window).height() > $(document).height()-80) {
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

    function banned(id,sid,name){
        $("input[name='uid']").val(sid);
        $("input[name='id']").val(id);
        $(".banned_name").html('');
        $(".banned_name").append("<span>檢舉" + name + "</span>")
        $(".announce_bg").show();
        $("#show_banned").show();
        $('body').css("overflow", "hidden");
    }

    function show_banned_close(){
        $(".announce_bg").hide();
        $("#show_banned").hide();
        $('body').css("overflow", "auto");
    }

    @if (Session::has('message'))
        {{--c5('{{ Session::get('message') }}');--}}
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


    $(".announce_bg").click(function(){
        $('body').css("overflow", "auto");
    });

    function tab_uploadPic() {
        $(".announce_bg").show();
        $("#tab_uploadPic").show();
        $('body').css("overflow", "hidden");
    }
    function tab_uploadPic_close() {
        $(".announce_bg").hide();
        $("#tab_uploadPic").hide();
        $('body').css("overflow", "auto");
    }
    function form_uploadPic_submit(){
        var num_of_images=$('.fileuploader-items-list .fileuploader-item').length;
        if(num_of_images==0) {
            $('.alert_tip').text();
            $('.alert_tip').text('請選擇照片');
        }else{
            $('#form_uploadPic').submit();
        }
    }
    $(document).ready(function () {
        $('input[name="images"]').fileuploader({
            extensions: ['jpg', 'png', 'jpeg', 'bmp'],
            changeInput: ' ',
            theme: 'thumbnails',
            enableApi: true,
            addMore: true,
            limit: 5,
            thumbnails: {
                box: '<div class="fileuploader-items">' +
                    '<ul class="fileuploader-items-list">' +
                    '<li class="fileuploader-thumbnails-input"><div class="fileuploader-thumbnails-input-inner" style="background: url({{ asset("new/images/addpic.png") }}); background-size:100%"></div></li>' +
                    '</ul>' +
                    '</div>',
                item: '<li class="fileuploader-item">' +
                    '<div class="fileuploader-item-inner">' +
                    '<div class="type-holder">${extension}</div>' +
                    '<div class="actions-holder">' +
                    '<button type="button" class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="fileuploader-icon-remove"></i></button>' +
                    '</div>' +
                    '<div class="thumbnail-holder">' +
                    '${image}' +
                    '<span class="fileuploader-action-popup"></span>' +
                    '</div>' +
                    '<div class="content-holder"><h5>${name}</h5><span>${size2}</span></div>' +
                    '<div class="progress-holder">${progressBar}</div>' +
                    '</div>' +
                    '</li>',
                item2: '<li class="fileuploader-item">' +
                    '<div class="fileuploader-item-inner">' +
                    '<div class="type-holder">${extension}</div>' +
                    '<div class="actions-holder">' +
                    '<a href="${file}" class="fileuploader-action fileuploader-action-download" title="${captions.download}" download><i class="fileuploader-icon-download"></i></a>' +
                    '<button type="button" class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="fileuploader-icon-remove"></i></button>' +
                    '</div>' +
                    '<div class="thumbnail-holder">' +
                    '${image}' +
                    '<span class="fileuploader-action-popup"></span>' +
                    '</div>' +
                    '<div class="content-holder"><h5 title="${name}">${name}</h5><span>${size2}</span></div>' +
                    '<div class="progress-holder">${progressBar}</div>' +
                    '</div>' +
                    '</li>',
                startImageRenderer: true,
                canvasImage: false,
                _selectors: {
                    list: '.fileuploader-items-list',
                    item: '.fileuploader-item',
                    start: '.fileuploader-action-start',
                    retry: '.fileuploader-action-retry',
                    remove: '.fileuploader-action-remove'
                },
                onItemShow: function(item, listEl, parentEl, newInputEl, inputEl) {
                    var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                        api = $.fileuploader.getInstance(inputEl.get(0));

                    plusInput.insertAfter(item.html)[api.getOptions().limit && api.getChoosedFiles().length >= api.getOptions().limit ? 'hide' : 'show']();

                    if(item.format == 'image') {
                        item.html.find('.fileuploader-item-icon').hide();
                    }

                    if (api.getListEl().length > 0) {
                        $('.fileuploader-thumbnails-input-inner').css('background-image', 'url({{ asset("new/images/addpic.png") }})');
                    }
                },
                onItemRemove: function(html, listEl, parentEl, newInputEl, inputEl) {
                    var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                        api = $.fileuploader.getInstance(inputEl.get(0));

                    html.children().animate({'opacity': 0}, 200, function() {
                        html.remove();

                        if (api.getOptions().limit && api.getChoosedFiles().length - 1 < api.getOptions().limit)
                            plusInput.show();
                    });

                    if (api.getFiles().length == 1) {
                        $('.fileuploader-thumbnails-input-inner').css('background-image', 'url({{ asset("new/images/addpic.png") }})');
                    }
                }
            },
            dialogs: {
                alert:function(message) {
                    alert(message);
                },
                // confirm:function(message, confirm) {
                //     popUpTrueOrFalse(message, function () {
                //         confirm();
                //         gmBtn2();
                //     })
                // }
            },
            dragDrop: {
                container: '.fileuploader-thumbnails-input'
            },
            afterRender: function(listEl, parentEl, newInputEl, inputEl) {
                var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                    api = $.fileuploader.getInstance(inputEl.get(0));

                plusInput.on('click', function() {
                    api.open();
                });

                api.getOptions().dragDrop.container = plusInput;
            },
            editor: {
                cropper: {
                    showGrid: true,
                },
            },
            captions: {
                confirm: '確認',
                cancel: '取消',
                name: '檔案名稱',
                type: '類型',
                size: '容量',
                dimensions: '尺寸',
                duration: '持續時間',
                crop: '裁切',
                rotate: '旋轉',
                sort: '分類',
                download: '下載',
                remove: '刪除',
                drop: '拖曳至此上傳檔案',
                open: '打開',
                removeConfirmation: '確認要刪除檔案嗎?',
                errors: {
                    filesLimit: function(options) {
                        return '最多上傳 ${limit} 張圖片.'
                    },
                    filesType: '檔名: ${name} 不支援此格式, 只允許 ${extensions} 檔案類型上傳.',
                    fileSize: '${name} 檔案太大, 請確認容量需小於 ${fileMaxSize}MB.',
                    filesSizeAll: '上傳的所有檔案過大, 請確認未超過 ${maxSize} MB.',
                    fileName: '${name} 已有選取相同名稱的檔案.',
                }
            }
        });

        $(".announce_bg").on("click", function() {
            $('.bl_tab_aa').hide();
        });
    });
</script>
@if($to)
    @include('new.dashboard.chat_to')
    @include('new.dashboard.chat_from')
    <script>
        document.getElementById("chatForm").onsubmit = function(event) {
            submit();
            event.preventDefault();
            return false;
        }
        function submit(){
            var formData = new FormData();
            var xhr = new XMLHttpRequest();
            formData.append("msg", document.getElementById("msg").value);
            formData.append("from", "{{ auth()->user()->id }}");
            formData.append("to", "{{ $to->id }}");
            formData.append("_token", "{{ csrf_token() }}");
            xhr.open("post", "{{ route('realTimeChat') }}", true);
            xhr.onload = function (e) {
                var response = e.currentTarget.response;
            }
            xhr.send(formData);  /* Send to server */
            document.getElementById("msg").value = '';
        }

        function sendReadMessage(messageId){
            var formData = new FormData();
            var xhr = new XMLHttpRequest();
            formData.append("messageId", messageId);
            formData.append("_token", "{{ csrf_token() }}");
            xhr.open("post", "{{ route('realTimeChatRead') }}", true);
            xhr.onload = function (e) {
                var response = e.currentTarget.response;
            }
            xhr.send(formData);  /* Send to server */
        }
        Echo.private('Chat.{{ $to->id }}.{{ auth()->user()->id }}')
            .listen('Chat', (e) => {
                // Received
                if(!e.message.error) {
                    realtime_from(e);
                    sendReadMessage(e.message.id);
                }
            });
        Echo.private('Chat.{{ auth()->user()->id }}.{{ $to->id }}')
            .listen('Chat', (e) => {
                if(e.message.error){
                    c5(e.message.content);
                    return false;
                }
                else {
                    // Sent
                    realtime_to(e);
                }
           });
        Echo.private('ChatRead.{{ auth()->user()->id }}.{{ $to->id }}')
            .listen('ChatRead', (e) => {
                $('#is_read.' + e.message_id).html("已讀");
            });
        Echo.private('ChatReadSelf.{{ auth()->user()->id }}')
            .listen('ChatReadSelf', (e) => {
                let unread = parseInt($('#unreadCount').text(), 10);
                unread--;
                $('#unreadCount').text(unread);
            });
    </script>
@endif
<style>
    @media (max-width:450px) {
        .fpt_pic{height:230px !important;}
    }
    @media (max-width:320px) {
        .fpt_pic{height: 230px !important;}
    }
</style>
@stop