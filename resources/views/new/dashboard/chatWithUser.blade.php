@extends('new.layouts.website')
@section('app-content')
<link href="https://fonts.googleapis.com/css?family=Roboto:400|700" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.2/photoswipe.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.2/default-skin/default-skin.min.css">
<link rel="stylesheet" href="{{ asset('css/photoswipe/styles.css') }}">
<link rel="stylesheet" href="{{ asset('css/photoswipe/prittyprint.css') }}">
<style>
    .chatWith>img {
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

    .dropbtn:hover,
    .dropbtn:focus {
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
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
        border-radius: 5px;
    }

    .dropdown-content a {
        color: #e44e71;
        padding: 6px 6px 0 12px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content.show {
        right: 0;
        margin-top: 35px;
        padding: 0;
    }

    .dropdown-content a:hover {
        background-color: #e44e71;
        color: #ffffff;
    }

    .show {
        display: block;
    }

    .alert {
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
        color: #fd5678;
        /*padding-top: 2px;*/
    }

    .shdel.unsend {
        border: #fd5678 1px solid;
    }

    .shdel.unsend,
    .shdel.unsend>span,
    .shdel.specific_reply {
        width: auto;
    }

    .specific_reply {
        margin-right: 5px;
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
        color: #fd5678;
        /*padding-top: 2px;*/
    }

    .message_fixed {
        position: fixed;
    }

    .blur_img {
        filter: blur(2px);
        -webkit-filter: blur(2px);
    }

    .fileuploader-icon-remove:after {
        content: none !important;
    }

    .xxi {
        min-height: 500px;
        width: 92%;
        margin: 0 auto
    }

    @media (max-width:1024px) {
        .xxi {
            min-height: 920px;
        }
    }

    @media (max-width:992px) {
        .xxi {
            min-height: 560px;
        }
    }

    @media (max-width:760px) {
        .xxi {
            min-height: 360px;
        }
    }

    img {
        max-width: 100%;
    }

    .new_pot1 ::-webkit-scrollbar {
        /*滚动条整体样式*/
        width: 4px;
        /*高宽分别对应横竖滚动条的尺寸*/
        height: 1px;
    }

    .new_pot1 ::-webkit-scrollbar-thumb {
        /*滚动条里面小方块*/
        border-radius: 100px;
        background: #8a9fef;
    }

    .new_pot1 ::-webkit-scrollbar-track {
        /*滚动条里面轨道*/
        border-radius: 100px;
        background: rgba(255, 255, 255, 0.6);
    }

    .message::-webkit-scrollbar {
        display: none;
    }

    .message {
        -ms-overflow-style: none;
    }

    .message {
        overflow: -moz-hidden-unscrollable;
        /*注意！若只打 hidden，chrome 的其它 hidden 會出問題*/
        height: 100%;
    }

    .message {
        height: 100%;
        width: calc(100vw + 18px);
        /*瀏覽器滾動條的長度大約是 18px*/
        overflow: auto;
    }

    .se_text_bot_add_bottom {
        bottom: 143px;
    }

    .pad_bot {
        padding-bottom: 20px;
    }

    @media (max-width: 450px) {
        .pad_bot {
            padding-bottom: 0px;
        }
    }
    
    div .unsent_msg p {
        color: #999;
    }

    .shdel.specific_reply {
        right: 20px;
        border: #fd5678 1px solid;
        white-space:nowrap;
    }

    .specific_msg_box {
        display: none;
        background-color: #ffdbdb
            /*#aaa*/
        ;
        position: relative;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
    }

    .specific_msg_close {
        width: 20px;
        position: absolute;
        top: 2px;
        right: 0px;
    }

    .specific_msg_close a,
    .specific_msg_close a:visited,
    .specific_msg_close a:hover,
    .specific_msg_close a:active,
    .specific_msg_close a:focus {
        color: #fff;
        font-size: 8px;
        text-decoration: none;
    }

    .specific_msg {
        color: #626262;
        width: calc(100% - 20px);
        line-height: 25px;
        padding: 5px;
        font-size: 14px;
        font-weight: normal;

        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        line-clamp: 1;
        -webkit-box-orient: vertical;
        word-wrap: break-word;
        word-break: break-all;
    }

    .specific_msg img {
        height: 15px;
        width: 15px;
    }

    .specific_msg>img {
        margin-right: 20px;
    }

    .show .parent_msg_box {
        background-color: #ffdbdb;
    }

    .send .parent_msg_box {
        background-color: #F0F0F0;
    }

    .parent_msg_box {
        width: calc(100% + 16px);
        /*margin: 0 10px;*/
        margin: 0 0 8px -8px;
        padding: 4px 8px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        color: #ababab;
        border-bottom: #ababab 1px solid;

        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        line-clamp: 1;
        -webkit-box-orient: vertical;
        word-wrap: break-word;
        word-break: break-all;
    }

    .msg_has_parent {
        padding-top: 0 !important;
    }

    .msg_has_parent .msg_input {
        top: 28px;
    }

    .parent_msg_box img {
        margin-right: 10px;
        height: 15px;
        width: 15px;
        float: initial !important;
    }

    .msg_content {
        display: block;
    }
</style>
<script>

    function banned(id,sid,name){
        let is_banned = {{ $is_banned ? 1 : 0 }};

        if(is_banned){
            return  c5('您目前被站方封鎖，無檢舉權限');
        }

        $("input[name='uid']").val(sid);
        $("input[name='id']").val(id);
        $(".banned_name").html('');
        $(".banned_name").append("<span>檢舉" + name + "</span>")
        $(".announce_bg").show();
        $("#show_banned_ele").show();
        $('body').css("overflow", "hidden");
    }

    function show_banned_close(){
        $(".announce_bg").hide();
        $("#show_banned_ele").hide();
        $('body').css("overflow", "auto");
    }

        Echo.private('Chat.{{ $to->id }}.{{ auth()->user()->id }}')
            .listen('Chat', (e) => {
                // Received
                if(!e.message.error) {
                    realtime_from(e);
                    sendReadMessage(e.message.id);
                }
            })
            .listenForWhisper('sendMsg', (e) => {
                realtime_from_msg(e);
                sendReadMessage(e.message.client_id,true);
                 
            }) ;
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
           })
            .listenForWhisper('sendMsg', (e) => {
                realtime_to_msg(e);
            });    

        Echo.private('ChatRead.{{ auth()->user()->id }}.{{ $to->id }}')
            .listen('ChatRead', (e) => {
                $('#is_read.' + e.message_id).html("已讀");
            }).listenForWhisper('readMsg', (e) => {
                $('#is_read.client_' + e.message_client_id).html("已讀");
            });
        Echo.private('ChatReadSelf.{{ auth()->user()->id }}')
            .listen('ChatReadSelf', (e) => {
                if(unread>0) unread--;
                if(unread2>0) unread2--;
                $('#unreadCount').text(unread);
                $('#unreadCount2').text(unread2);
            })
            ; 

        Echo.private('ChatUnsend.{{ $to->id }}.{{ auth()->user()->id }}')
            .listen('ChatUnsend', (e) => {
                if(!e.message.error) {
                    realtime_unsend_another(e);
                }
                else {
                    c5('收回失敗：'+e.message.content);
                    return false;                    
                }
            })
            .listenForWhisper('unsendMsg', (e) => {
                if(!e.message.error) {
                    realtime_unsend_another(e);
                }
                else {
                    c5('收回失敗：'+e.message.content);
                    return false;                    
                }
            })         
            ;
        Echo.private('ChatUnsend.{{ auth()->user()->id }}.{{ $to->id }}')
            .listen('ChatUnsend', (e) => {
                if(e.message.error){
                    c5('收回失敗：'+e.message.content);
                    return false;
                }
                else {
                    realtime_unsend_self(e);
                }
           })
           .listenForWhisper('unsendMsg', (e) => {
                if(e.message.error){
                    c5('收回失敗：'+e.message.content);
                    return false;
                }
                else {
                    realtime_unsend_self(e);
                }
            }); 

        Echo.private('ChatRead.{{ $to->id }}.{{ auth()->user()->id }}');            
</script>
<div class="container matop70 chat">
    <div class="row">
        <div class="col-sm-2 col-xs-2 col-md-2 dinone">
            @include('new.dashboard.panel')
        </div>
        <div class="col-sm-12 col-xs-12 col-md-10">
            @if(isset($to))
            {{-- <div class="fbuttop"></div>--}}
            <div class="shouxq" style="display: flex;">
                @if(isset($admin))
                @if($to->id == $admin->id)
                <a class="nnn_adbut" href="{!! url('dashboard/personalPage') !!}">
                    <img class="nnn_adbut_img" src="{{ asset('/new/images/back_icon.png') }}" style="height: 15px;">返回
                </a>
                <span style="flex: 6; text-align: center;">
                    @if($to->id != $admin->id)
                    <a href="/dashboard/viewuser/{{$to->id}}" style="color: #fd5678;">{{$to->name}}</a>
                    @else
                    <span style="color: #fd5678;">系統來訊通知</span>
                    @endif
                </span>
                @else

                <a class="nnn_adbut"
                    href="{{ !empty(session()->get('goBackPage_chat2')) ? session()->get('goBackPage_chat2') : \Illuminate\Support\Facades\URL::previous() }}"><img
                        class="nnn_adbut_img" src="{{ asset('/new/images/back_icon.png') }}"
                        style="height: 15px;">返回</a>
                <span style="flex: 6; text-align: center;">
                    @if($toUserIsBanned)
                        <a type="button" style="color: #fd5678;" onclick="c5('{{'此人已被站方封鎖'}}'),setTimeout(function(){window.location.href = ' {{ !empty(session()->get	('goBackPage_chat2')) ? session()->get('goBackPage_chat2') : \Illuminate\Support\Facades\URL::previous() }} '},3000)">{{$to->name}}</a>
                    @else
                        <a href="/dashboard/viewuser/{{$to->id}}" style="color: #fd5678;">
                            <span class="se_rea">{{$to->name}}
                                @if($isVip)
                                    @if($to->isOnline())
                                        <div class="onlineStatus"></div>
                                    @endif
                                @else
                                    <div class="onlineStatusNonVip"><img src="/new/images/wsx.png"></div>
                                @endif
                            </span>
                        </a>
                    @endif
                </span>
                @if($user->engroup==1)
                <form class="" style="float: right; position: relative; text-align: right;"
                    action="{{ route('chatpay_ec') }}" method=post id="ecpay">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="userId" value="{{ $user->id }}">
                    <input type="hidden" name="to" value="@if(isset($to)) {{ $to->id }} @endif">
                    <button type="button" class="paypay" onclick="checkPay('ecpay')"><a
                            class="nnn_adbut">車馬費</a></button>
                </form>
                @else
                <button style="float: right; position: relative;" type="button" class="paypay"
                    onclick="c5('這是Daddy主動發起的，請提醒Daddy按此按紐發動車馬費邀請！')"><a class="nnn_adbut"
                        style="margin-top: -15px">車馬費</a></button>
                @endif
                @endif
                @endif
            </div>
            @else
            {{ logger('Chat with non-existing user: ' . url()->current()) }}
            @endif
            <div class="message pad_bot">
                @php
                $date_temp='';
                $isBlurAvatar = \App\Services\UserService::isBlurAvatar($to, $user);
                @endphp
                @if(!empty($messages))
                @foreach ($messages as $message)
                @php
                $parentMsg = null;
                $msgUser = \App\Models\User::findById($message->from_id);
                \App\Models\Message::read($message, $user->id);
                if($message->parent_msg??null) $parentMsg = \App\Models\Message::find($message->parent_msg);
                if(!($parentMsg??null) && $message->parent_client_id??null) $parentMsg = \App\Models\Message::where('client_id',$message->parent_client_id)->first();
                if($parentMsg??null) {
                if($parentMsg->from_id==$user->id) $parentMsgSender=$user;
                else {
                $parentMsgSender = \App\Models\User::findById($parentMsg->from_id);
                $isBlurParentSender = \App\Services\UserService::isBlurAvatar($parentMsgSender, $user);
                }
                }
                @endphp

                @if($date_temp != substr($message['created_at'],0,10)) <div class="sebg matopj10">
                    {{substr($message['created_at'],0,10)}}</div>@endif

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
                @elseif( ($message['sys_notice']==0 || $message['sys_notice']== null) && $message['unsend']==0)
                @if($isVip && $message['from_id'] == $user->id)
                @if((!isset($admin) || $to->id != $admin->id) && !isset($to->banned )&& !isset($to->implicitlyBanned))
                <form method="post" class="unsend_form" id="unsend_form_{{$message['id']}}"
                    action="{{route('unsendChat')}}">
                    @endif
                    @endif

                    <div class="@if($message['from_id'] == $user->id) show @else send @endif" @if($message['from_id']
                        !=$user->id) id="chat_msg_{{$message['id']}}" @endif>
                        <div class="msg @if($message['from_id'] == $user->id) msg1 @endif">
                            @if($message['from_id'] == $user->id)
                                <img
                                src="@if(file_exists( public_path().$user->meta->pic ) && $user->meta->pic != ""){{$user->meta->pic}} @elseif($user->engroup==2)/new/images/female.png @else/new/images/male.png @endif">
                            @else
                                @if($toUserIsBanned)
                                    <a type="button" style="color: #fd5678;" onclick="c5('{{'此人已被站方封鎖'}}'),setTimeout(function(){window.location.href = ' {{ !empty(session()->get	('goBackPage_chat2')) ? session()->get('goBackPage_chat2') : \Illuminate\Support\Facades\URL::previous() }} '},3000)">{{$to->name}}</a>
                                @else
                                    <a class="chatWith" href="{{ url('/dashboard/viewuser/' . $msgUser->id ) }}">
                                @endif
                                    <img class="@if($isBlurAvatar) blur_img @endif"
                                        src="@if(file_exists( public_path().$msgUser->meta->pic ) && $msgUser->meta->pic != ""){{$msgUser->meta->pic}} @elseif($msgUser->engroup==2)/new/images/female.png @else/new/images/male.png  @endif">
                                </a>
                            @endif
                            <p class="@if($parentMsg??null) msg_has_parent @endif">
                                @if($parentMsg??null)
                                <span class="parent_msg_box">
                                    @if(($parentMsg['from_id']??null) == $user->id)
                                    <img
                                        src="@if(file_exists( public_path().$user->meta->pic ) && $user->meta->pic != ""){{$user->meta->pic}} @elseif($user->engroup==2)/new/images/female.png @else/new/images/male.png @endif">
                                    @else
                                    <img class="@if($isBlurParentSender??null) blur_img @endif"
                                        src="@if(file_exists( public_path().$parentMsgSender->meta->pic ) && $parentMsgSender->meta->pic != ""){{$parentMsgSender->meta->pic}} @elseif($parentMsgSender->engroup==2)/new/images/female.png @else/new/images/male.png  @endif">
                                    @endif
                                    @if(!is_null(json_decode($parentMsg['pic'],true)))
                                    <img src="{{ json_decode($parentMsg['pic'],true)[0]['file_path'] }}"
                                        class="n_pic_lt">
                                    @endif
                                    {!! nl2br($parentMsg->content) !!}
                                </span>
                                @endif
                                @if(!is_null(json_decode($message['pic'],true)))
                                <i class="msg_input"></i>
                                <span id="page" class="marl5">
                                    <span class="justify-content-center">
                                        <span class="gutters-10 pswp--loaded" data-pswp="">
                                            <span style="width: 150px;">
                                                @foreach(json_decode($message['pic'],true) as $key => $pic)
                                                @if(isset($pic['file_path']))
                                                <a href="{{$pic['file_path'] }}" target="_blank"
                                                    data-pswp-index="{{ $key }}" class="pswp--item">
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
                                    <font
                                        class="sent_ri @if($message['from_id'] == $user->id)dr_l @if(!$isVip) novip @endif @else dr_r @endif">
                                        <span>{{ ($message['created_at']??null)?substr($message['created_at'],11,5):'&nbsp;' }}</span>
                                        @if(!$isVip && $message['from_id'] == $user->id)
                                        <span style="color:lightgrey;">已讀/未讀</span>
                                        <img src="/new/images/icon_35.png"
                                            style="position: absolute;float: left;left: 10px; top:20px;-moz-transform:rotate(-25deg);-webkit-transform:rotate(-30deg);">
                                        @else
                                        <span>@if($message['read'] == "Y" && $message['from_id'] == $user->id) 已讀
                                            @elseif($message['read'] == "N" && $message['from_id'] == $user->id) 未讀
                                            @endif</span>
                                        @endif
                                    </font>
                                </span>
                                @if($message['from_id'] != $user->id)
                                <a href="javascript:void(0)" class=""
                                    onclick="banned('{{$message['id']}}','{{$msgUser->id}}','{{$msgUser->name}}');"
                                    title="檢舉">
                                    <span class="shdel"
                                        style="border: #fd5678 1px solid; width: auto;"><span>檢舉</span></span>
                                </a>
                                @endif
                                @if((!isset($admin) || $to->id != $admin->id) && !isset($to->banned )&&
                                !isset($to->implicitlyBanned))
                                <a href="javascript:void(0)" class="specific_reply_doer" onclick="return false;"
                                    title="回覆" data-id="{{$message['id']}}" data-client_id="{{$message['client_id']}}">
                                    <span class="shdel specific_reply"><span>回覆</span></span>
                                </a>
                                @if($message['from_id'] == $user->id)

                                <a href="javascript:void(0)" class="unsend_a" data-id="{{$message['id']}}"
                                      data-client_id="{{$message['client_id']}}"  onclick="chatUnsend(this);return false;" title="收回">
                                    <span class="shdel unsend"><span>收回</span></span>
                                </a>
                                @endif
                                @endif
                                @else
                                <i class="msg_input"></i>
                                <span class="msg_content">{!! nl2br($message['content']) !!}</span>

                                @if($message['from_id'] != $user->id)
                                <a href="javascript:void(0)" class=""
                                    onclick="banned('{{$message['id']}}','{{$msgUser->id}}','{{$msgUser->name}}');"
                                    title="檢舉">
                                    <span class="shdel_word"><span>檢舉</span></span>
                                </a>

                                @endif
                                @if((!isset($admin) || $to->id != $admin->id) && !isset($to->banned )&&
                                !isset($to->implicitlyBanned))
                                @if($message['from_id'] == $user->id)
                                <a href="javascript:void(0)" onclick="chatUnsend(this);return false;" class="unsend_a"
                                    data-id="{{$message['id']}}"  data-client_id="{{$message['client_id']}}" title="收回">
                                    <span class="shdel_word unsend"><span>收回</span></span>
                                </a>
                                @endif
                                <a href="javascript:void(0)" class="specific_reply_doer" onclick=" return false;"
                                    title="回覆" data-id="{{$message['id']}}"  data-client_id="{{$message['client_id']}}">
                                    <span class="shdel_word specific_reply"><span>回覆</span></span>
                                </a>
                                @endif
                                <font
                                    class="sent_ri @if($message['from_id'] == $user->id)dr_l @if(!$isVip) novip @endif @else dr_r @endif">
                                    <span>{{ ($message['created_at']??null)?substr($message['created_at'],11,5):'&nbsp;' }}</span>
                                    @if(!$isVip && $message['from_id'] == $user->id)
                                    <span style="color:lightgrey;">已讀/未讀</span>
                                    <img src="/new/images/icon_35.png"
                                        style="position: absolute;float: left;left: 10px; top:20px;-moz-transform:rotate(-25deg);-webkit-transform:rotate(-30deg);">
                                    @else
                                    <span>@if($message['read'] == "Y" && $message['from_id'] == $user->id) 已讀
                                        @elseif($message['read'] == "N" && $message['from_id'] == $user->id) 未讀
                                        @endif</span>
                                    @endif
                                </font>
                                @endif

                            </p>
                        </div>
                    </div>
                    @if($isVip && $message['from_id'] == $user->id)
                    @if((!isset($admin) || $to->id != $admin->id) && !isset($to->banned )&&
                    !isset($to->implicitlyBanned))
                </form>
                @endif
                @endif
                @elseif($message['unsend']==1 )
                <div class="">
                    <div class="sebg matopj10  unsent_msg">
                        <p>
                            @if($message['from_id'] == $user->id)
                            您已收回訊息
                            @else
                            {{$to->name}}已收回訊息
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
                <form style="margin: 0 auto;" method="POST"
                    action="/dashboard/chat2/{{ \Carbon\Carbon::now()->timestamp }}" id="chatForm">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="userId" value="{{$user->id}}">
                    <input type="hidden" name="to" value="{{$to->id}}">
                    <input type="hidden" name="m_time" @if(isset($m_time)) value="{{ $m_time }}" @else value="" @endif>
                    <input type="hidden" name="{{ \Carbon\Carbon::now()->timestamp }}"
                        value="{{ \Carbon\Carbon::now()->timestamp }}">
                    <textarea name="msg" cols="" rows="" class="se_text msg" id="msg" placeholder="請輸入"
                        required></textarea>
                    <div class="message_fixed"></div>
                    <input type="submit" id="msgsnd" class="se_tbut matop20 msgsnd" value="回覆">
                </form>
            </div>--}}

            @if((!isset($admin) || $to->id != $admin->id) && !isset($to->banned )&& !isset($to->implicitlyBanned))
            <div class="se_text_bot" id="message_input" style="padding-right: 3%; padding-left:3%;">
                @if(($to->engroup) === ($user->engroup))
                @else
                <form style="margin: 0 auto;" method="POST"
                    action="/dashboard/chat2/{{ \Carbon\Carbon::now()->timestamp }}" id="chatForm" name="chatForm">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="userId" value="{{$user->id}}">
                    <input type="hidden" name="to" value="{{$to->id}}">
                    <input type="hidden" name="m_time" @if(isset($m_time)) value="{{ $m_time }}" @else value="" @endif>
                    <input type="hidden" name="{{ \Carbon\Carbon::now()->timestamp }}"
                        value="{{ \Carbon\Carbon::now()->timestamp }}">
                    <input type="hidden" name="parent" id="message_parent" class="message_parent" value="">
                                <input type="hidden" name="parent_client" id="message_parent_client"  class="message_parent_client"  value="" >
                                <input type="hidden" name="client_id" id="chatFormClientId"   class="client_id"  value="" >
                    <div class="xin_left specific_msg_box" id="specific_msg_box">
                        <div class="specific_msg"></div>
                        <div class="specific_msg_close"><a href="javascript:void(0)"
                                onclick="resetSpecificMsgElt();return false;">Ｘ</a></div>
                    </div>
                    <div class="xin_left">
                        <a class="xin_nleft" onclick="tab_uploadPic();"><img src="/new/images/moren_pic.png"></a>
                        <textarea id="msg" name="msg" rows="1" class="xin_input" placeholder="請輸入"></textarea>
                    </div>
                    <a onclick="chatForm_submit();" class="xin_right" style="border: none;"><img
                            src="/new/images/fasong.png" style="margin-top:6px;"></a>
                </form>
                @endif
            </div>
            @endif
            @else
            <div class="se_text_bot">
                此會員資料已不存在。
            </div>
            {{ logger('Chat with non-existing user: ' . url()->current()) }}
            @endif
        </div>
    </div>
</div>
    <img src="{{asset('new/owlcarousel/assets/ajax-loader.gif')}}" style="display:none;visible:hidden;z-index:-999;width:0;height:0;" >
<div class="bl bl_tab tab_payAlert" id="tab_payAlert">
    <div class="bltitle bltitle_fixed"><span>車馬費說明</span></div>
    <a id="" onclick="$('.blbg').click();" class="bl_gb bl_gb_fixed"><img src="/new/images/gb_icon.png"></a>
    <div class="n_blnr01 matop20 tip_adjust">
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

<div class="bl_tab_aa" id="show_banned_ele">
    <div class="bl_tab_bb">
        <div class="bltitle banned_name"></div>
        <div class="new_pot new_poptk_nn new_pot001 ">
            <div class="fpt_pic new_po000">
                <form id="reportMsgForm" class="m-form m-form--fit m-form--label-align-right" method="POST"
                    action="{{ route('reportMsg') }}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <input type="hidden" name="aid" value="{{$user->id}}">
                    <input type="hidden" name="uid" value="">
                    <input type="hidden" name="id" value="">
                    <textarea name="content" cols="" rows="" class="n_nutext" placeholder="{{$report_reason}}"
                        required></textarea>
                    <input id="images" type="file" name="images">
                    <div class="n_bbutton" style="width: 100%; text-align: center;">
                        <button type="submit" class="n_right"
                            style="border-style: none; background: #8a9ff0; color:#ffffff; float: unset; margin-left: 0px; margin-right: 20px;">送出</button>
                        <button type="reset" class="n_left"
                            style="border: 1px solid #8a9ff0; background: #ffffff; color:#8a9ff0; float: unset; margin-right: 0px;"
                            onclick="show_banned_close()">返回</button>
                    </div>
                </form>
            </div>
        </div>
        <a onclick="show_banned_close()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>
</div>

<div class="bl bl_tab" id="messageBoard_enter_limit" style="display: none;">
    <div class="bltitle"><span>提示</span></div>
    <div class="n_blnr01 ">
        <div class="new_tkfont">您目前未達標準<br>不可使用留言板功能</div>
        <div class="new_tablema">
            <table>
                @if($user->engroup==2)
                <tr>
                    <td class="new_baa new_baa1">須通過手機驗證</td>
                    <td class="new_baa1">@if($user->isPhoneAuth())<img src="/new/images/ticon_01.png">@else<img
                            src="/new/images/ticon_02.png">@endif</td>
                </tr>
                @endif
                <tr>
                    <td class="new_baa">需為VIP會員</td>
                    <td class="">@if(!$user->isVip())<img src="/new/images/ticon_02.png">@else<img
                            src="/new/images/ticon_01.png">@endif</td>
                </tr>
            </table>
        </div>
    </div>
    <a id="" onClick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>


@if($to)
<div class="bl_tab_aa" id="tab_uploadPic" style="display: none;">
    <form id="form_uploadPic" action="{{ route('realTimeChat') }}" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="userId" value="{{ $user->id }}">
        <input type="hidden" name="from" value="{{ $user->id }}">
        <input type="hidden" name="to" value="{{ $to->id }}">
        <input type="hidden" name="msg" value="">
        <input type="hidden" name="m_time" @if(isset($m_time)) value="{{ $m_time }}" @else value="" @endif>
        <input type="hidden" name="{{ \Carbon\Carbon::now()->timestamp }}"
            value="{{ \Carbon\Carbon::now()->timestamp }}">
        <input type="hidden" name="parent" class="message_parent" value="">
                <input type="hidden" name="client_id" class="client_id" value="">
        <div class="bl_tab_bb">
            <div class="bltitle"><span style="text-align: center; float: none;">上傳照片</span></div>
            <div class="new_pot1 new_poptk_nn new_height_mobile ">
                <div class="fpt_pic">
                    <input id="images" type="file" name="images">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.2/photoswipe-ui-default.min.js" charset="utf-8">
</script>
<script src="{{ asset('new/js/photoswipe-simplify.min.js') }}" charset="utf-8"></script>
<script src="{{ asset('new/js/heic2any.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('new/js/resize_before_upload.js') }}" type="text/javascript"></script>
<script>
    photoswipeSimplify.init({
        history: false,
        focus: false,
    });
</script>
<script>
    @if (($to->engroup) === ($user->engroup))
        c5('同性會員無法發送訊息!');
        setTimeout("window.location.href = ' {{ !empty(session()->get('goBackPage_chat2')) ? session()->get('goBackPage_chat2') : \Illuminate\Support\Facades\URL::previous() }} '", 3000);
    @endif
    
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
    
    $.ajaxSetup({ cache: false, contentType: false,processData: false});
    $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
        // you can use originalOptions.type || options.type to restrict specific type of requests
       if(options.data instanceof FormData==true) return;
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

    $(document).keypress(function(e) {
        if((e.keyCode == 13 && e.shiftKey)){
            //換行
            var content = $("#msg").val();
            $("#msg").append(content+"\n");

        }else if(e.keyCode == 13){
            e.preventDefault();
            var msg_str = $("#msg").val().replace(/\r\n|\n/g,"").replace(/\s+/g, "");
            if(msg_str.length>400) {
                c5('訊息輸入至多400個字');
                e.preventDefault();
                return false;
            }
            if(msg_str.length>0){
                $('#chatForm').submit();
            }else{
                $('.xin_input').css('height', '38px');
                $('.xin_nleft, .xin_right').css('margin-top', '0px');
            }
        }
    });
    
    $(document).keyup(function(e) {
        if(e.keyCode == 13){
            if($('#tab05').css('display')!='none') {
                $('#msg').focus();
                $('#tab05').hide();
                $('.announce_bg').hide();
            }
        }
    });

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

    // $('#chatForm').submit(function (e) {
    //     let content = $('#msg').val(), msgsnd = $('.msgsnd');
    //
    //     if($.trim(content) == "" ){
    //         $('.alert').remove();
    //         // $("<a style='color: red; font-weight: bold;' class='alert'>請勿僅輸入空白！</a>").insertAfter($('.msg'));
    //         $( ".message_fixed" ).html();
    //         $( ".message_fixed" ).append( "<div><a style='color: red; font-weight: bold;' class='alert'>請勿僅輸入空白！</a></div>" );
    //         msgsnd.prop('disabled', true);
    //         return checkForm;
    //     }
    //     else {
    //         $('.alert').remove();
    //         return checkForm;
    //     }
    // });

    function chatForm_submit() {
        $('#msg').focus();
        let content = $('#msg').val(), msgsnd = $('.msgsnd');
        var msg_str = $("#msg").val().replace(/\r\n|\n/g,"").replace(/\s+/g, "");
        if(msg_str.length>400) {
            c5('訊息輸入至多400個字');
            return false;
        }else{
            $('#chatForm').submit();
        }

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
    }
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
                url: "/dashboard/chat2/deleteMsgByUser/"+msgID+"?{{csrf_token()}}={{now()->timestamp}}",
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
        $('.se_text_bot').css('bottom',$('.se_text_bot_add_bottom').height() -70);
    }
    if($(window).width()<=912){
        $('.bot').hide();
    }
    $('.message').css('width',$('.shouxq').width()-20);
    $('.se_text').css('width',$('.shouxq').width());

    if(window.matchMedia("(max-width: 823px)").matches && window.matchMedia("(max-height: 823px)").matches){
        $('.se_text_bot').removeClass('se_text_bot_add_bottom');
        $('.bot').hide();

        message_max_height = message_height - $('.heicon').height() - 50;
        bl_gb_fixed_top = $(window).height() / 5 + 10;
        $('.bltitle_fixed').css('width',$('.tab_payAlert').width()+1);
        $('.bl_gb_fixed').css('top',bl_gb_fixed_top);
        $('.tab_payAlert').css('height','70%');
        $('.bltitle_fixed').css('position','fixed');

    }
    if(window.matchMedia("(min-width: 812px)").matches && window.matchMedia("(max-height: 375px)").matches){
        $('.se_text_bot').removeClass('se_text_bot_add_bottom');
        $('.bot').hide();

        message_max_height = message_height - $('.heicon').height() - 50;
        bl_gb_fixed_top = $(window).height() / 5 + 10;
        $('.bltitle_fixed').css('width',$('.tab_payAlert').width()+1);
        $('.bl_gb_fixed').css('top','40px');
        $('.bl_gb_fixed').css('right','33%');
        $('.tab_payAlert').css('height','70%');
        $('.bltitle_fixed').css('position','fixed');

    }
    if(window.matchMedia("(min-width: 823px)").matches && window.matchMedia("(max-height: 411px)").matches){
        $('.se_text_bot').removeClass('se_text_bot_add_bottom');
        $('.bot').hide();

        message_max_height = message_height - $('.heicon').height() - 50;
        bl_gb_fixed_top = $(window).height() / 5 + 10;
        $('.bltitle_fixed').css('width',$('.tab_payAlert').width()+1);
        $('.bl_gb_fixed').css('top','40px');
        $('.bl_gb_fixed').css('right','33%');
        $('.tab_payAlert').css('height','70%');
        $('.bltitle_fixed').css('position','fixed');

    }
    if(window.matchMedia("(min-width: 1024px)").matches && window.matchMedia("(max-height: 690px)").matches){
        bl_gb_fixed_top = $(window).height() / 10 - 5;
        bl_gb_fixed_right = $(window).width() / 3 - 5;
        //alert(bl_gb_fixed_right);
        $('.bltitle_fixed').css('width',$('.tab_payAlert').width());
        $('.bl_gb_fixed').css('top',bl_gb_fixed_top);
        $('.bl_gb_fixed').css('right',bl_gb_fixed_right);
        $('.matop20').css('margin-top','40px !important');
        $('.bltitle_fixed').css('position','fixed');
    }
    $('.message').css('height',message_max_height-40);
    $('.message').css('max-height',message_max_height-40);

    $(document).ready(function () {
        $('#msg').focus();
        $(window).resize(function() {
            // alert($('.tab_payAlert').width());
            var message_max_height,bl_gb_fixed_top,bl_gb_fixed_right;
            var message_height = $(window).height() - $('#message_input').height() - $('.shouxq').height();
            var footer_height = $('.bot').height();
            if($(window).height()<=601){
                message_max_height = message_height - $('.hetop').height() - 50;
            }else{
                message_max_height = message_height - footer_height - $('.hetop').height()- 30;
                //$('.se_text_bot').addClass('se_text_bot_add_bottom');
                $('.se_text_bot').css('bottom',$('.se_text_bot_add_bottom').height() -70);

            }
            if($(window).width()<=912){
                $('.bot').hide();
            }
            $('.message').css('width',$('.shouxq').width()-20);
            $('.se_text').css('width',$('.shouxq').width());
            // if( /Android|iPhone/i.test(navigator.userAgent) ) {
            if(window.matchMedia("(max-width: 823px)").matches && window.matchMedia("(max-height: 823px)").matches){
                $('.se_text_bot').removeClass('se_text_bot_add_bottom');
                $('.bot').hide();

                message_max_height = message_height - $('.heicon').height() - 50;
                bl_gb_fixed_top = $(window).height() / 5 + 10;
                //alert(bl_gb_fixed_top);
                $('.bltitle_fixed').css('width', $('.tab_payAlert').width()+1);
                $('.bl_gb_fixed').css('top',bl_gb_fixed_top);
                $('.tab_payAlert').css('height','70%');
                $('.bltitle_fixed').css('position','fixed');

            }
            if(window.matchMedia("(min-width: 812px)").matches && window.matchMedia("(max-height: 375px)").matches){
                $('.se_text_bot').removeClass('se_text_bot_add_bottom');
                $('.bot').hide();

                message_max_height = message_height - $('.heicon').height() - 50;
                bl_gb_fixed_top = $(window).height() / 5 + 10;
                $('.bltitle_fixed').css('width',$('.tab_payAlert').width()+1);
                $('.bl_gb_fixed').css('top','40px');
                $('.bl_gb_fixed').css('right','33%');
                $('.tab_payAlert').css('height','70%');
                $('.bltitle_fixed').css('position','fixed');

            }
            if(window.matchMedia("(min-width: 823px)").matches && window.matchMedia("(max-height: 411px)").matches){
                $('.se_text_bot').removeClass('se_text_bot_add_bottom');
                $('.bot').hide();

                message_max_height = message_height - $('.heicon').height() - 50;
                bl_gb_fixed_top = $(window).height() / 5 + 10;
                $('.bltitle_fixed').css('width',$('.tab_payAlert').width()+1);
                $('.bl_gb_fixed').css('top','40px');
                $('.bl_gb_fixed').css('right','33%');
                $('.tab_payAlert').css('height','70%');
                $('.bltitle_fixed').css('position','fixed');

            }
            if(window.matchMedia("(min-width: 1024px)").matches && window.matchMedia("(max-height: 690px)").matches){
                bl_gb_fixed_top = $(window).height() / 10 - 5;
                bl_gb_fixed_right = $(window).width() / 3 - 5;
                //alert(bl_gb_fixed_right);
                $('.bltitle_fixed').css('width',$('.tab_payAlert').width());
                $('.bl_gb_fixed').css('top',bl_gb_fixed_top);
                $('.bl_gb_fixed').css('right',bl_gb_fixed_right);
                $('.matop20').css('margin-top','40px !important');
                $('.bltitle_fixed').css('position','fixed');

            }
            // if(window.matchMedia("(min-width: 1024px)").matches){
            //     bl_gb_fixed_top = $(window).height() / 10;
            //     $('.bl_gb_fixed').css('top',bl_gb_fixed_top);
            // }
            $('.message').css('height',message_max_height -40);
            $('.message').css('max-height',message_max_height -40);
        });
    });

    $(window).scroll(function() {
        if($(window).scrollTop() + $(window).height() > $(document).height()-80) {
            if(window.matchMedia("(max-width: 767px)").matches){
                $('.se_text_bot').removeClass('se_text_bot_add_bottom');
            }else {
                //$('.se_text_bot').addClass('se_text_bot_add_bottom');
                $('.se_text_bot').css('bottom',$('.se_text_bot_add_bottom').height() -10); //-76
            }
        }
        else{
            $('.se_text_bot').removeClass('se_text_bot_add_bottom');
        }
    });



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

    $('.n_bllbut').click(function(){
        $('#msg').focus();
    });
    
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
        $("#tab_loading").hide();
        $('body').css("overflow", "auto").css("fixed", "");
    }
    function form_uploadPic_submit(){
        if(rbupld_image_handling_numSet[1]>0) {
            alert('請等照片選取完畢再送出');
            return false;
        }
        var num_of_images=$('.fileuploader-items-list .fileuploader-item').length;
        if(num_of_images==0) {
            $('.alert_tip').text();
            $('.alert_tip').text('請選擇照片');
        }else{
            $('#form_uploadPic').submit();
        }
    }
    $(document).ready(function () {
        images_uploader = $('input[name="images"]').fileuploader({
            //extensions: ['jpg', 'png', 'jpeg', 'bmp'],
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
            beforeResize: function(listEl,parentEl, newInputEl, inputEl) {
                var now_tab = listEl.closest('.bl_tab_bb');
                disableCloseTabAct(now_tab);
                var btn_elt = now_tab.find('.n_bbutton .n_bllbut');
                if(btn_elt.length==0) {
                    btn_elt = now_tab.find('.n_bbutton .n_right');
                }
                if(btn_elt.length)
                    btn_elt.css({ 'cursor': 'default','color':'#d6d6d6'}).attr('onclick','return false;'+btn_elt.attr('onclick')).html('選取照片中\<!--'+btn_elt.html()+'--\>');
            },             
            afterResize: function(listEl,parentEl, newInputEl, inputEl) {
                $(".announce_bg").show();
                var now_tab = listEl.closest('.bl_tab_bb');
                activeCloseTabAct(now_tab);
                var btn_elt = now_tab.find('.n_bbutton .n_bllbut');
                if(btn_elt.length==0) {
                    btn_elt = now_tab.find('.n_bbutton .n_right');
                }
                if(btn_elt.length)
                    btn_elt.css({'color':'','cursor':''}).css('color','#ffffff').attr('onclick',btn_elt.attr('onclick').replace('return false;','')).html(btn_elt.html().replace('選取照片中\<!--','').replace('--\>',''));
            }, 
            beforeSubmit: function(e,cur_uploader_api) {        
                var nowElt = $(e.target); 
                var nowFormElt = nowElt;
                var index = cur_uploader_api.rbupld_uploader_index;
                if(nowFormElt.attr('id')=='reportMsgForm') return;
                var fileElt = nowFormElt.find('input[type=file]');
                var fileSelected = cur_uploader_api.getChoosedFiles();
                disableCloseTabAct(nowElt);
                var btn_elt = nowElt.find('.n_bbutton .n_bllbut'); 
                if(btn_elt.length==0) {
                    btn_elt = nowElt.find('.n_bbutton .n_right');
                }
                if(btn_elt.length) {
                    btn_elt.css({ 'cursor': 'default','color':'#d6d6d6'}).attr('onclick','return false;'+btn_elt.attr('onclick')).html(btn_elt.html()+'中');
                }
                
                var msg_data =getClientMsgData();
                
                nowElt.find('.client_id').val(msg_data.client_id);

                if(fileSelected.length>rbupld_not_support_file_numSet[index])                
                {
                    var pic_num = 0;
                    nowElt.parent().find('input[type=file]').each(function(){
                        var curFiles = $(this).prop('files');
                        pic_num = pic_num+curFiles.length;    
                    });  

                    msg_data.pic = pic_num;

                    var msg_carrier = {
                        message:msg_data
                    };
                    Echo.private('Chat.{{ auth()->user()->id }}.{{ $to->id }}')
                        .whisper('sendMsg', {
                            message: msg_data
                        }) ;   
                 
                    realtime_to_msg(msg_carrier);
                    $('.announce_bg').show();
                }
                tab_uploadPic_close(); 
                $('#chatForm .xin_nleft').attr('onclick','return false;').find('img').attr('src','{{asset("new/owlcarousel/assets/ajax-loader.gif")}}');
            },  
            afterSubmit: function(e) {        
                var nowElt = $(e.target);  
                if(nowElt.attr('id')=='reportMsgForm') return;
                $('#chatForm .xin_nleft').attr('onclick','tab_uploadPic();').find('img').attr('src','{{asset("new/images/moren_pic.png")}}');
                
     
                activeCloseTabAct(nowElt);
                var btn_elt = nowElt.find('.n_bbutton .n_bllbut');    
                if(btn_elt.length==0) {
                    btn_elt = nowElt.find('.n_bbutton .n_right');
                }
                if(btn_elt.length) {
                    btn_elt.css({'color':'','cursor':''}).css('color','#ffffff').attr('onclick',btn_elt.attr('onclick').replace('return false;','')).html(btn_elt.html().replace('中',''));
                }
                resetSpecificMsgElt();
            }, 
            beforeSubmitedSuccess:function(data,status,xhr,ajaxObj,cur_uploader_api) {
                if(data.error!=undefined && data.error==401) {
                    show_pop_message('上傳失敗：'+data.content);
                }
                
            },
            afterSubmitedSuccess: function(data,status,xhr,ajaxObj,cur_uploader_api) {
                var target_client_id=null;
                var rtn_msg = '';
                var rtn_error = 0;
                if(data.content!=undefined) rtn_msg=data.content;
                if(data.error!=undefined) rtn_error=data.error;

                if(ajaxObj.data.client_id==undefined ) {
                    target_client_id=ajaxObj.data.get('client_id');
                }
                else  {
                    target_client_id = ajaxObj.data.client_id;
                }
                
                var target_elt = $('#unsend_form_client_' + target_client_id);
                var error_msg = '';

                if(target_client_id!=undefined && target_client_id!=null) 
                {
                    if(rtn_msg!='' && rtn_msg.indexOf('發訊頻率太高')>=0 
                        && rtn_msg.indexOf('秒限一則訊息')>=0 
                        && rtn_msg.indexOf('請降低發訊頻率')>=0 
                        ) 
                    {
                          
                        error_msg='照片傳送失敗，建議可等照片上傳完再傳送其他訊息。';  
                    }
                    else if(rtn_error>0){
                        error_msg=rtn_msg;
                    }
                
                    if(error_msg!='') {     
                        if(target_elt.length>0) {
                            
                            target_elt.after(
                                        '<div class="">'
                                           +'<div class="sebg matopj10  unsent_msg">'
                                               +'<p>'+error_msg+'</p>'                              
                                            +'</div>' 
                                        +'</div>');
                            target_elt.remove();

                           var msg_carrier = {
                                message:{id:'',client_id:target_client_id}
                            };            

                            realtime_unsend_self(msg_carrier);                            
                        }
                    }
                }
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
        resize_before_upload($(images_uploader.eq(0)),400,600,'#show_banned_ele');
        resize_before_upload($(images_uploader.eq(1)),400,600,'#tab_uploadPic','json','c5');
        $(".announce_bg").attr('onclick',$(".announce_bg").attr('onclick')+";$('.bl_tab_aa').hide();");
    });
</script>
@if($to)
@include('new.dashboard.chat_to')
@include('new.dashboard.chat_from')
@include('new.dashboard.chatUnsend_self')
@include('new.dashboard.chatUnsend_another')
<script>
    if($('#chatForm').length){
            document.getElementById("chatForm").onsubmit = function(event) {
                submit();
                event.preventDefault();
                return false;
            }
        }
        
        function chatUnsend(elt) {
            @if($isVip)
            var nowelt = $(elt);
            var formelt = nowelt.closest('form');
            var action = formelt.attr('action');
            
            var formData = new FormData();
            var xhr = new XMLHttpRequest();
            
            
            formData.append('unsend_msg', nowelt.data('id'));
            formData.append('unsend_msg_client', nowelt.data('client_id'));
            formData.append("_token", "{{ csrf_token() }}");
            xhr.open("post", action, true);
            xhr.onload = function (e) {
                var response = e.currentTarget.response; 
            }
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.send(formData); 
            var msg_carrier = {
                message:{id:nowelt.data('id'),client_id:nowelt.data('client_id')}
            };            
            
            Echo.private('ChatUnsend.{{ auth()->user()->id }}.{{ $to->id }}')
                .whisper('unsendMsg', msg_carrier);             
            
            realtime_unsend_self(msg_carrier);
            @else
            c5('非VIP無法收回訊息');
            @endif
            return false;
        }
        
        function submit(){
            
            var msg_data =getClientMsgData();
            if(msg_data.content!='' && msg_data.content!=undefined) 
            {
                var msg_carrier = {
                    message:msg_data
                }
                Echo.private('Chat.{{ auth()->user()->id }}.{{ $to->id }}')
                    .whisper('sendMsg', {
                        message: msg_data
                    }) ;             
                realtime_to_msg(msg_carrier); 
            } ;

            var msg_text = document.getElementById("msg").value;
            if(msg_text!='' && msg_text!=undefined) 
            {
                var formData = new FormData();
                var xhr = new XMLHttpRequest();
                var parent_id = document.getElementById('message_parent').value;
                var parent_client_id = document.getElementById('message_parent_client').value;
                formData.append("msg", msg_text);
            formData.append("from", "{{ auth()->user()->id }}");
            formData.append("to", "{{ $to->id }}");
            formData.append('parent', parent_id);
            formData.append('parent_client', parent_client_id);
            formData.append('client_id',msg_data.client_id);
            formData.append("_token", "{{ csrf_token() }}");
            xhr.open("post", "{{ route('realTimeChat') }}", true);
            xhr.onload = function (e) {
                var response = e.currentTarget.response;
                    var rentry = null;
                    try { 
                        rentry = JSON.parse(response);
                    }
                    catch (e) {
                        
                        var logout_keyword = ['註冊','登入','忘記密碼','還沒有帳號' ,'免費註冊','login','name="login"','id="login"'];
                        var logout_all_finded = false;
                        
                        for(var lgi=0;lgi<logout_keyword.length;lgi++) {
                            if(lgi==0 || logout_all_finded) logout_all_finded = (response.indexOf(logout_keyword[lgi])>=0);
                        }    

                        var postmsg_error_show_msg = '傳送失敗 ';
                        if(logout_all_finded) postmsg_error_show_msg+='。您已登出或基於帳號安全由系統自動登出，請重新登入。'
                        else postmsg_error_show_msg+= e.name+'-'+e.message;
                        show_pop_message(postmsg_error_show_msg);
                        return;
                    }   
                    if(rentry.error!=undefined && rentry.error) {
                        if(rentry.error==401) {
                            show_pop_message('傳送失敗：'+rentry.content);
                        }
                        else {
                            c5(rentry.content);
                            $('.n_bllbut').focus();
                        }
                    }
                
            }
            xhr.onerror = function(e) {
                c5('傳送失敗!');
                $('.n_bllbut').focus();
            }
            xhr.send(formData);  /* Send to server */
            }
            document.getElementById("msg").value = '';
            resetSpecificMsgElt();
            return false;
        }

        function sendReadMessage(messageId,client=false){
            var formData = new FormData();
            var xhr = new XMLHttpRequest();
            if(client) {
                formData.append("messageClientId", messageId);
            }
            else {
                formData.append("messageId", messageId);
            }

            formData.append("_token", "{{ csrf_token() }}");
            xhr.open("post", "{{ route('realTimeChatRead') }}", true);
            xhr.onload = function (e) {
                var response = e.currentTarget.response;
            }
            xhr.send(formData);  /* Send to server */
            if(client) {
                Echo.private('ChatRead.{{ $to->id }}.{{ auth()->user()->id }}')
                    .whisper('readMsg', {message_client_id:messageId});         
            }
        }
      
        @if($to_forbid_msg_data??null)
            $(document).on('click','#chatForm button[type=submit],#chatForm .xin_nleft',function(){
                if($('.send').length==0) {
                    event.preventDefault();                    
                    tab_uploadPic_close();                                   
                    show_pop_message('新進甜心只接收 vip 信件，{{$to_forbid_msg_data["user_type_str"]}}會員要於 {{$to_forbid_msg_data["end_date"]}}後方可發信給這位女會員');
                    return false;
                }
            });
        @endif

            $(document).on('click','.specific_reply_doer',function() {
                var now_elt = $(this);
                var now_id = now_elt.attr('data-id');
                var now_client_id = now_elt.attr('data-client_id');
                var now_elt_parent = now_elt.parent();
                var now_msg_pic_elt =  now_elt_parent.find('.marl5 .justify-content-center .pswp--loaded span a');
                var now_msg_html = '';
                if(now_msg_pic_elt.length>0) now_msg_html=now_msg_pic_elt.html();
                var now_msg_sender_img = now_elt_parent.parent().find('img').first().clone();
                $('#specific_msg_box').show().find('.specific_msg').html(now_elt_parent.find('.msg_content').text()+now_msg_html).prepend(now_msg_sender_img);
                $('.message_parent').val(now_id);
                $('.message_parent_client').val(now_client_id);
                $('#msg').focus();
            });
            
            function resetSpecificMsgElt() {
                $('.message_parent').val('');
                $('#message_parent_client').val('');
                $('.specific_msg').html('');
                $('#specific_msg_box').hide();                
            }
            
            function disableCloseTabAct(elt) {
                var org_bg_action = $(".announce_bg").attr('onclick');           
                $(".announce_bg").attr('onclick','return false;'+org_bg_action);                
                var now_close_btn = elt.find('a.bl_gb');
                now_close_btn.attr('onclick','return false;'+now_close_btn.attr('onclick')); 
            }
            
            function activeCloseTabAct(elt) {
                var now_bg_action = $(".announce_bg").attr('onclick');
                $(".announce_bg").attr('onclick',now_bg_action.replace('return false;',''));                
                var now_close_btn = elt.find('a.bl_gb');
                if(now_close_btn.length>0 && now_close_btn.attr('onclick').length>0)
                    now_close_btn.attr('onclick',now_close_btn.attr('onclick').replace('return false;',''));                
            }   
            
            function getClientMsgData() {
                var nowDate = new Date();
                var parent_id_elt = document.getElementById('message_parent');
                var parent_client_id_elt = document.getElementById('message_parent_client');
                var parent_elt = $('.specific_msg_box .specific_msg');
                var parent_message = {};

                var client_msg_data = {
                    content:document.getElementById("msg").value
                    ,from_id :{{ auth()->user()->id }}
                    ,to_id :{{ $to->id }}
                    ,created_at:''//nowDate.getFullYear() + "-" + (nowDate.getMonth() + 1) + "-" + nowDate.getDate() + " " + nowDate.getHours() + ":" + nowDate.getMinutes() + ":" + nowDate.getSeconds()
                    ,id:''
                    ,pic:''
                    ,client_id:document.getElementById('chatFormClientId').value?document.getElementById('chatFormClientId').value:generateSelfClientId()
                }  ;
                
                if(parent_elt.length && parent_elt.html()!='') {
                    parent_message = {
                        content:parent_elt.text()
                        ,pic:JSON.stringify([{file_path:parent_elt.find('img').eq(1).attr('src')}])
                        ,id:parent_id_elt.value
                        ,client_id:parent_client_id_elt.value
                    } 
                    
                    if($('#unsend_form_'+parent_id_elt.value).length || $('#unsend_form_client_'+parent_client_id_elt.value).length)
                    {
                        client_msg_data.parent_msg_sender_blurryAvatar = {{\App\Services\UserService::isBlurAvatar($user,$to)?1:0}};
                        client_msg_data.parent_msg_sender_isAvatarHidden = {{$user->meta->isAvatarHidden?1:0}};   
                        client_msg_data.parent_msg_sender_id = {{$user->id}};
                    
                    }
                    else if($('#chat_msg_'+parent_id_elt.value).length || $('#chat_msg_client_'+parent_client_id_elt.value).length) 
                    {
                        client_msg_data.parent_msg_sender_id = {{$to->id}};
                    }
                    client_msg_data.parent_message = parent_message;
                    client_msg_data.parent=parent_id_elt.value;
                    client_msg_data.parent_client=parent_client_id_elt.value;
                    client_msg_data.parent_msg_sender_pic=parent_elt.find('img').first().attr('src');                
                    
                };

                return client_msg_data;                
            }
            
            function generateSelfClientId(){
                var nowDate = new Date();
                var rcode1 = Math.floor(Math.random()*1000).toString(36);
                var rcode2 = Math.floor(Math.random()*1000).toString(36);
                var init_str = nowDate.getTime().toString()+'{{auth()->user()->id}}';
                var str1 = parseInt(init_str.substr(0, Math.floor(init_str.length/2)),10).toString(36);
                var str2 = parseInt(init_str.substr(Math.floor(init_str.length/2), init_str.length),10).toString(36);
                return rcode1+str1+str2+rcode2;
            }
</script>
@endif
<style>
    @media (max-width:450px) {
        .fpt_pic {
            height: 345px !important;
        }
        .new_pot001{
            height: 365px !important;
        }
    }

    @media (max-width:320px) {
        .fpt_pic {
            height: 230px !important;
        }
    }
</style>
@stop