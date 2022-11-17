@php
    header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
@endphp
@extends('new.layouts.website')
@section('style')
<style>
    .btn_imga1>.btn_back {
        width: 100%;
        background: url(/new/images/fanhui_1.png) no-repeat 0 0;
        background-size: 100% 100%;
        height: 34px;
        padding-left: 6px;
    }
    .toug_back img {
        height: 14px;
        vertical-align: middle;
        margin-top: -3px;
        margin-right: 2px;
    }
    .toug_back:hover {
        color: #ffffff;
        box-shadow: inset 0px 13px 10px -10px #f83964, inset 0px -10px 10px -20px #f83964;
    }
    .toug_back {
        background: #fe92a8;
        border-radius: 100em;
        height: 21px;
        width: 21px;
        line-height: 19px;
        color: #ffffff;
        text-align: center;
        float: right;
        font-size: 13px;
        margin-top: 10px;
        margin-right: 16px;
    }
    .btn_imga1 {
        width: 86px;
        height: auto;
        margin: 7px -8px 0 0;
        background: none;
        padding: 0;
        line-height: 32px;
    }
    .xzgn {
        position: absolute;
        right: 0;
        cursor: pointer;
        z-index: 2;
    }
    .fabiao1 {
        width: 150px;
        background: linear-gradient(to right, #fff6f7, #ffd8e3);
        border-radius: 10px;
        display: none;
        padding: 5px 0;
        box-shadow: 0 5px 10px #e398a4;
        position: absolute;
        right: 0;
        cursor: pointer;
        z-index: 3;
        color: #333333;
        text-align: left;
    }
    .fabiao1 a {
        width: 90%;
        display: table;
        margin: 0 auto;
        line-height: 30px;
        border-bottom: #fd5678 1px dashed;
        color: #333333;
        cursor: pointer;
    }
    .fabiao1 a:hover {
        color: #ee5472;
        background: #ffc9d8;
    }
    .showslide {
        left: 0;
        top: 48px;
        z-index: 101;
    }
    .fadeinboxs {
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0);
        position: fixed;
        left: 0;
        display: none;
        top: 0;
        z-index: 100;
    }
    .btn_imga1:hover {
        box-shadow: unset;
    }
    .btn_imga1:hover>.btn_back {
        width: 100%;
        background: url(/new/images/fanhui_2.png) no-repeat 0 0;
        background-size: 100% 100%;
        height: 34px;
        padding-left: 6px;
    }
    .fabiao2 {
        width: 90px;
        background: linear-gradient(to right, #fff6f7, #ffd8e3);
        border-radius: 10px;
        display: none;
        padding: 5px 0;
        box-shadow: 0 5px 10px #e398a4;
        position: absolute;
        right: 0;
        cursor: pointer;
        z-index: 3;
        color: #333333;
        text-align: center;
    }
    .fabiao2 a {
        width: 90%;
        display: table;
        margin: 0 auto;
        line-height: 30px;
        border-bottom: #fd5678 1px dashed;
        color: #333333;
        cursor: pointer;
    }
    .fabiao2 a:hover {
        color: #ee5472;
        background: #ffc9d8;
    }
    .ys_inbut {
        margin-right: 40px;
        margin-bottom: 20px;
    }
</style>
@if($user->isVip())
<script>
let is_truth_icon_pic = new Image();
is_truth_icon_pic.src="{{asset('/new/images/zz_zt2.png')}}";
</script>
@endif
@stop
@push('javascript')
<script>
    $('.userlogo1').click(function(){
        event.stopPropagation()
        if($(this).hasClass('')){
            $(this).removeClass('')
            $('.fadeinboxs').fadeOut()
            $('.showslide').fadeOut()
        }else{
            $(this).addClass('')
            $('.fadeinboxs').fadeIn()
            $('.showslide').fadeIn()
        }
    });
    $('.userlogo').click(function(){
        event.stopPropagation()
        if($(this).hasClass('')){
            $(this).removeClass('')
            $('.fadeinboxs').fadeOut()
            $('.showslide1').fadeOut()
        }else{
            $(this).addClass('')
            $('.fadeinboxs').fadeIn()
            $('.showslide1').fadeIn()
        }
    });
    $('body').click(function(){
        $('.showslide').fadeOut()
        $('.showslide1').fadeOut()
        $('.fadeinboxs').fadeOut()
    })
    $('.setDay').click(function(){
        let day = $(this).data('day');
        $('#daysSelect').val(day).trigger('change');
        $('#day_text').text($(this).text());
    })
</script>
@endpush
@section('app-content')
<style>
    .blur_img {
        filter: blur(1px);
        -webkit-filter: blur(1px);
    }

    .page>li {
        display: none !important;
    }

    .pagination>li>a:focus,
    .pagination>li>a:hover,
    .pagination>li>span:focus,
    .pagination>li>span:hover {
        z-index: 3;
        /* color: #23527c !important; */
        background-color: #FF8888 !important;
        /* border-color: #ddd !important; */
        /* border-color:#ee5472 !important; */
        /* color:white !important; */
    }

    .sjright {
        right: 40px;
        position: absolute;
    }

    .shou_but {
        margin-top: 8px !important;
        /*right: 80px;*/
        left: 80px;
        position: absolute;
        z-index: 1;
    }

    @media (max-width: 767px) {
        .sjright {
            right: 20px;
        }

        .shou_but {
            margin-top: 8px !important;
            /*right: 40px !important;*/
            left: 10px !important;
            position: absolute;
        }
    }

    .select_cont {
        width: 94%;
        margin: 20px auto;
    }

    .select_cont select {
        border-radius: 6px;
        border: 1px solid #ddd;
        cursor: pointer;
        padding: 5px 35px 5px 10px;
        appearance: none;
        -moz-appearance: none;
        -webkit-appearance: none;
        background: url(../../new/images/sjx_down.png)no-repeat right center #f5f5f5;
        background-size: auto 100%;
    }

    .send:after,
    .show:after,
    .msg:after,
    .select_cont:after {
        content: "";
        clear: both;
        display: table;
    }

    .select_cont option {
        text-align: center;
    }
    
    .lebox5 {
        background: url({{ asset('/new/images/off.png') }}) no-repeat right #94a5b4;
        background-position:98%;
        background-size:22px;
        padding:0px 20px;
        color:#fff;
        font-size:16px;
        position:relative;
        line-height:40px;
        cursor:pointer;
        text-align: center;
    }

    .lebox5.on {
        background: url({{ asset('/new/images/on.png') }}) no-repeat right #94a5b4;
        background-position:98%;
        background-size:22px;
        position:relative;
        cursor:pointer;
        color:#fff;
    }
    
    img.ys_gt1 {z-index:5 !important;}
    .msg_select_cont {
        width: auto;
        margin-right: 3%;
        float: right;
        margin-top: 0;
        margin-bottom: 20px;
    }

    @media (max-width:450px) {
        .mad_tit{display: table; text-align: center; float: inherit; margin-left: inherit;}
    }
    .mad_tit font{ font-weight: normal !important}


    @media (max-width:450px) {
        .le_span{text-align: left;padding-left:40px;}
    }
    @media (max-width:320px) {
        .le_span{text-align: left;padding-left:50px;}
    }


    .sjleft_b{ color: #fff !important;}
    .d_dw{ position: relative;}
    .denglu_nn{width: 12px; height: 12px; position: absolute; bottom: 0; right: 0px; background: #38b549; box-shadow: 0 2px 3px rgba(56,181,73,0.8);  border-radius: 100px;}

    .deng_nn{background: linear-gradient(to TOP,#ff9225,#ffb86e);box-shadow: 2px 2px 0px #ff721d; border-radius: 100px;}
    .deng_nn img{height: 7px !important;width: 7px !important;margin: 0 auto;display: table;margin-top: 4px;}
    /*.sjpic{width: 65px; height: 65px;}*/
    /*.sjpic img{width: 65px; height: 65px;}*/


    .righ_nre{width: 109px; float: right;}
    .righ_nre h3 {font-size: 12px !important;text-align: right ;color: #999999;line-height: 25px}
    .righ_nre h4 {font-size: 12px;text-align: right ;color: #fe92a8; line-height: 25px; height: 25px;
        -webkit-box-orient: vertical;text-overflow: ellipsis;overflow: hidden;width: 100%;display:block;white-space: nowrap; -webkit-line-clamp: 1;}
    .sjleftzz{ margin-left: 0;
        /*width: calc(100% - 110PX);*/
        float: left;
        text-overflow: ellipsis;
    }

    .sjleftzz {
        width: 60%;
        height: 50px;
        float: left;
        line-height: 25px;
        margin-left: 10px;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: block;
    }

    .sjleftzz span {
        font-size: 14px;
        text-align: left !important;
        height: 25px;
        float: left;
        -webkit-line-clamp: 3;
        white-space: nowrap;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        overflow: hidden !important;
    }

    .sjleftzz font {
        font-size: 14px;
        height: 25px;
        overflow: hidden;
        text-align: left;
        color: #999999;
        display: block;
        text-overflow: ellipsis;
        /*white-space: nowrap*/
    }

    .sjleftzz font img {
        height: 20px
    }

    @media (max-width:450px) {
        .sjleftzz {
            width: 55%;
        }
    }

    @media (max-width:375px) {
        .sjleftzz {
            width: 55%;
        }
    }

    @media (max-width:360px) {
        .sjleftzz {
            width: 52%;
        }
    }

    @media (max-width:320px) {
        .sjleftzz {
            width: 45%;
        }
    }

    .denglu_nn{left:50px;bottom: 6px;}
    .si_bg{
        margin-left: 16px;
    }
    @media (max-width: 767px){
        .si_bg {width: 95%; /* margin-left: 13%; */}
    }
    @media (max-width: 450px){
        .si_bg {width: 92%; /* margin-left: 13%; */}
    }

    .se_seccner {
        width: 100%;
        display: table;
    }

    /* 10-10 */
    .se_seccner{width: 100%; display: table;}
    .se_input{width:auto;  border: #fabbcc 1px solid; height: 25px; border-radius: 3px;  background: #fff; font-size: 12px; float: left;margin-left: 10px;}
    .se_in01{width:220px; border: none; background: transparent; height: 24px; color: #000;}
    .se_button_a{ background: #fabbcc;border-radius: 3px;  height: 25px; color: #fff; float: right; width:60px; text-align: center; line-height: 24px;}


    .se_rig_ff{width: calc(100% - 300px); float: right;}

    .serit_aa{height:24px;background: url(/new/images/fengsuo.png) no-repeat;background-size: cover;line-height:24px; margin-left:5px; border-radius:100px;
        box-shadow:3px 4px 10px #d5ddec;color: #87909c !important;text-align: center;padding: 0 5px;cursor: pointer;display: table; float: right;}
    .serit_aa img{ height:16px;margin-right:2px; margin-top:0px;}
    .serit_aa:hover{height: 26px;background: url(/new/images/fengsuo_h.png) no-repeat; background-size:cover;
        box-shadow:3px 4px 10px #d5ddec;}


    @media (max-width:824px){
        .se_input{width: auto; }
        .se_rig_ff{width: calc(100% - 300px); float: right;}
        .se_in01{width: 220px; border: none; background: transparent; height: 24px; color: #000;}
        .se_button_a{ background: #fabbcc;border-radius: 3px;  height: 25px; color: #fff; float: right; width:60px; text-align: center; line-height: 24px;}

    }

    @media (max-width:540px){
        .serit_aa{ font-size: 12px;}
        .serit_aa img{ height:14px;margin-right:2px; margin-top:-2px;}
        .se_input{width: auto; }
        .se_rig_ff{width: calc(100% - 135px); float: right;}
        .se_in01{width: 90px; border: none; background: transparent; height: 24px; color: #000;}
        .se_button_a{ background: #fabbcc;border-radius: 3px;  height: 25px; color: #fff; float: right; width:32px; text-align: center; line-height: 24px;}

    }

    .righ_nre h3{font-size: 12px !important;}
    .sjleft {
        margin-bottom: 5px;
    }
    .ys_gt,.ys_gt1 {
        left:-10px;
    }
</style>
<!--引导弹出层-->
<script type="text/javascript" src="/new/intro/intro.js"></script>
<link href="/new/intro/introjs.css" rel="stylesheet">
<link rel="stylesheet" href="/new/intro/cover.css">
<div class="container matop70 chat">
    <div class="row">
        <div class="col-sm-2 col-xs-2 col-md-2 dinone">
            @include('new.dashboard.panel')
        </div>
        <div class="col-sm-12 col-xs-12 col-md-10">

            <div class="shou" style="text-align: center;position: relative;">
                @if($isVip || $user->engroup==2)
                <a class="toug_back btn_imga1 userlogo1 xzgn" style="float: left; left: 0; ">
                    <div class="btn_back">刪除<img src="/new/images/jiant_a.png"></div>
                </a>
                <div class="fabiao1 showslide">
                    @if($message_with_user_count >= 60)
                        <a class="" onclick="MutilpleDelete()">大量刪除訊息</a>
                    @endif
                    @if($user->is_admin_chat_channel_open)
                        @if(($isVip && ($user->engroup==1 || $user->engroup==2)) || (!$isVip && $user->engroup==2))
                            <a class="admin_delete">刪除所有站長來訊</a>
                        @endif
                    @endif
                    @if($user->engroup==1)
                        @if($isVip)
                            <a class="exchange_period_delete_{{$exchange_period_name[0]->id}}">刪除所有{{$exchange_period_name[0]->name}}</a>
                            <a class="exchange_period_delete_{{$exchange_period_name[2]->id}}">刪除所有{{$exchange_period_name[2]->name}}</a>
                            <a class="exchange_period_delete_{{$exchange_period_name[1]->id}}">刪除所有{{$exchange_period_name[1]->name}}</a>
                        @endif
                    @elseif($user->engroup==2)
                        @if(($isVip && ($user->engroup==1 || $user->engroup==2)) || (!$isVip && $user->engroup==2))
                            <a class="vvip_delete">刪除所有VVIP會員</a>
                            <a class="vip_delete">刪除所有VIP會員</a>
                            <a class="novip_delete">刪除所有試用會員</a>
                        @endif
                    @endif
                    @if(($isVip && ($user->engroup==1 || $user->engroup==2)) || (!$isVip && $user->engroup==2))
                        <a class="alert_delete">刪除所有警示會員</a>
                    @endif
                    @if(($isVip && ($user->engroup==1 || $user->engroup==2)) || (!$isVip && $user->engroup==2))
                        <a class="banned_delete">刪除被站方封鎖會員</a>
                    @endif
                </div>
                @endif
                <div class="fadeinboxs"></div>
                {{-- <div class="sj_iconleft">
                    <a href="{{route('viewChatNotice')}}"><img src="/new/images/ncion_03.png"></a>
                </div> --}}
                <span style="border-bottom: unset;">收件夾</span>
                <font>Inbox</font>
                <a class="toug_back btn_imga1 userlogo xzgn" style="top: 0;"> 
                    <div class="btn_back"><c id="day_text">7天内</c><img src="/new/images/jiant_a.png"></div>
                </a>
                <div class="fabiao2 showslide1" style="display: none;top: 48px; z-index: 101;">
                    <a class="setDay" data-day="7">7天内</a>
                    <a class="setDay" data-day="30">30天内</a>
                    <a class="setDay" data-day="all">全部</a>
                </div>
                <!-- <div class="sj_iconright"><img src="/new/images/sj_icon2.png"></div> -->
            </div>
            <div class="n_shtab" style="position: relative;">
                <h2 data-step="1" data-highlightClass="yd1a" data-tooltipClass="yd1"
                    data-intro="<p>不同等級會員可以有不同的信件讀取權限。</p>
                        <p>試用會員：信件可保存30天，通訊人數限制10人。</p>
                        <p>VIP 會員：信件可保存180天，無限制通訊人數。</p>
                        <h2>@if($isVip)您目前是 @if($user->isVVip()){{$letter_vvip}}@else{{$letter_vip}}@endif，所以不限制通訊人數，且信件可保存180天。@else您目前是 {{$letter_normal_member}}，所以限制通訊人數10，且信件保存30天。 @endif</h2><em></em><em></em>">
                    @if($isVip)
                    <span>您目前為@if($user->isVVip()){{$letter_vvip}}@else{{$letter_vip}}@endif</span>訊息可保存天數：180，可通訊人數:無限數
                    @else
                    <span>您目前為{{$letter_normal_member}}</span>訊息可保存天數：30，可通訊人數:10
                    @endif
                </h2>
                @if($user->engroup==2)
                <a href="javascript:void(0)" class="right ys_inbut" style="margin-right: 10px;position: absolute;right: 0px;top: 7px;"><img src="/new/images/zz_ztt.png"><span>{{ $user->show_can_message ? '收起罐頭訊息' : '顯示罐頭訊息' }}</span></a>
                @endif
            </div>
            <div class="d-table">
                <div class="select_cont msg_select_cont" style="display:none">
                    <select id="daysSelect" class="right">
                        {{-- <option value="7">訊息</option>--}}
                        <option value="7">7天内</option>
                        <option value="30">30天内</option>
                        <option value="all">全部</option>
                    </select>
                </div>
                
            </div>
            <div class="sjlist_li">
                <div class="leftsidebar_box">
                    <dl class="system_log">
                        @if($user->is_admin_chat_channel_open)
                            @if($user->id != 1049)
                            <dt class="lebox0" data-step="4" data-position="top" data-highlightClass="yd4a"
                                data-tooltipClass="yd4" data-intro="<p>會員可以在此處與站長對話。</p>
                                            <em></em><em></em>">

                                <span class="le_span">站長來訊</span>
                            </dt>
                            <dd>
                                <div class="loading warning" id="sjlist_admin_warning"><span
                                        class="loading_text">loading</span></div>
                                <ul class="sjlist sjlist_admin">
                                </ul>
                                <div class="page page_admin fenye" style="text-align: center;"></div>
                            </dd>
                            @endif
                        @endif
                        @if($user->engroup==1)
                            <!--男性介面-->
                            <dt class="lebox1 lebox_exchange_period_{{$exchange_period_name[0]->id}}" data-step="{{2+$exchange_period_name[0]->id}}"
                                data-position="top" data-highlightClass="yd4a" data-tooltipClass="yd4"
                                data-intro="<p>
                                            此區會員找尋長期包養關係，如若發現短期或是直接外約+line的，請直接檢舉。
                                            </p><em></em><em></em>">
                                <span class="le_span">{{$exchange_period_name[0]->name}}</span>
                            </dt>
                            <dd>
                                <div class="loading warning" id="sjlist_exchange_period_warning_{{$exchange_period_name[0]->id}}"><span
                                        class="loading_text">loading</span></div>
                                <ul class="sjlist sjlist_exchange_period_{{$exchange_period_name[0]->id}}">
                                </ul>
                                <div class="page page_exchange_period_{{$exchange_period_name[0]->id}} fenye" style="text-align: center;"></div>
                            </dd>

                            <dt class="lebox2 lebox_exchange_period_{{$exchange_period_name[2]->id}}" data-step="{{2+$exchange_period_name[2]->id}}"
                                data-position="top" data-highlightClass="yd4a" data-tooltipClass="yd4"
                                data-intro="<p>
                                            本區會員主要希望單次約會為主。如果是找尋長期包養關係建議避開此區會員。
                                            </p><em></em><em></em>">
                                <span class="le_span">{{$exchange_period_name[2]->name}}</span>
                            </dt>
                            <dd>
                                <div class="loading warning" id="sjlist_exchange_period_warning_{{$exchange_period_name[2]->id}}"><span
                                        class="loading_text">loading</span></div>
                                <ul class="sjlist sjlist_exchange_period_{{$exchange_period_name[2]->id}}">
                                </ul>
                                <div class="page page_exchange_period_{{$exchange_period_name[2]->id}} fenye" style="text-align: center;"></div>
                            </dd>

                            <dt class="lebox3 lebox_exchange_period_{{$exchange_period_name[1]->id}}" data-step="{{2+$exchange_period_name[1]->id}}"
                                data-position="top" data-highlightClass="yd4a" data-tooltipClass="yd4"
                                data-intro="<p>
                                            此區會員可接受長期或短期的包養關係。如果有發現直接要 line的狀況，請向站方檢舉。
                                            </p><em></em><em></em>">
                                <span class="le_span">{{$exchange_period_name[1]->name}}</span>
                            </dt>
                            <dd>
                                <div class="loading warning" id="sjlist_exchange_period_warning_{{$exchange_period_name[1]->id}}"><span
                                        class="loading_text">loading</span></div>
                                <ul class="sjlist sjlist_exchange_period_{{$exchange_period_name[1]->id}}">
                                </ul>
                                <div class="page page_exchange_period_{{$exchange_period_name[1]->id}} fenye" style="text-align: center;"></div>
                            </dd>
                        <!--男性介面-->

                        {{-- @if(($isVip && ($user->engroup==1 || $user->engroup==2)) || (!$isVip &&
                        $user->engroup==2))--}}
                        {{-- <span class="alert_delete shou_but">全部刪除</span>--}}
                        {{-- @endif--}}
                        {{-- <dt class="lebox4 lebox_alert" data-position="top" data-highlightClass="yd5a"
                            data-tooltipClass="yd5" data-step="6" --}} {{--
                            data-intro="警示原因會有多種，也許是被檢舉也許是站長設定為警示。站方強烈不建議與此區會員互動，若一定要跟此區會員互動請務必提高十二萬分警覺。<em></em><em></em>">
                            --}}
                            {{-- 警示會員</dt>--}}
                        {{-- <dd>--}}
                            {{-- <div class="loading warning" id="sjlist_alert_warning"><span
                                    class="loading_text">loading</span></div>--}}
                            {{-- <ul class="sjlist sjlist_alert">--}}
                                {{-- </ul>--}}
                            {{-- </dd>--}}

                        @endif

                        @if($user->engroup==2)
                            <!--女性介面-->
                            <dt class="leboxVVIP" data-position="top" data-highlightClass="yd4a"
                                data-tooltipClass="yd4" data-intro="<p>站方建議盡量多與
                            VVIP 會員互動。本區會員的素質最佳，投訴率低於 0.1%。</p>
                                    <em></em><em></em>">
                                    <span class="le_span">VVIP會員(財力驗證)</span>
                            </dt>
                            <dd>
                                <div class="loading warning" id="sjlist_vvip_warning"><span
                                            class="loading_text">loading</span></div>
                                <ul class="sjlist sjlist_vvip">
                                </ul>
                                <div class="page page_vvip fenye" style="text-align: center;"></div>
                            </dd>

                            <dt class="lebox1" data-step="3" data-position="top" data-highlightClass="yd4a"
                                data-tooltipClass="yd4" data-intro="<p>站方建議盡量多與
                                    VIP 會員互動。本區會員的素質最佳，投訴率低於 0.1%。</p>
                                            <em></em><em></em>">

                                <span class="le_span">VIP會員(基本認證)</span>
                            </dt>
                            <dd>
                                <div class="loading warning" id="sjlist_vip_warning"><span
                                        class="loading_text">loading</span></div>
                                <ul class="sjlist sjlist_vip">
                                </ul>
                                <div class="page page_vip fenye" style="text-align: center;"></div>
                            </dd>

                            <dt class="lebox2" data-position="top" data-highlightClass="yd4a" data-tooltipClass="yd4"
                                data-step="4" data-intro="未付費的會員賴帳機率高於VIP 50倍<em></em><em></em>">

                                <span class="le_span">試用會員(無驗證)</span>
                            </dt>
                            <dd>
                                <div class="loading warning" id="sjlist_novip_warning"><span
                                        class="loading_text">loading</span></div>
                                <ul class="sjlist sjlist_novip">
                                </ul>
                                <div class="page page_novip fenye" style="text-align: center;"></div>
                            </dd>
                            <!--女性介面 END -->

                            {{-- @if(($isVip && ($user->engroup==1 || $user->engroup==2)) || (!$isVip &&
                            $user->engroup==2))--}}
                            {{-- <span class="alert_delete shou_but">全部刪除</span>--}}
                            {{-- @endif--}}
                            {{-- <dt class="lebox3 lebox_alert" data-position="top" data-highlightClass="yd5a"
                                data-tooltipClass="yd5" data-step="5" --}} {{--
                                data-intro="警示原因會有多種，也許是被檢舉也許是站長設定為警示。站方強烈不建議與此區會員互動，若一定要跟此區會員互動請務必提高十二萬分警覺。<em></em><em></em>">
                                --}}
                                {{-- 警示會員</dt>--}}
                            {{-- <dd>--}}
                                {{-- <div class="loading warning" id="sjlist_alert_warning"><span
                                        class="loading_text">loading</span></div>--}}
                                {{-- <ul class="sjlist sjlist_alert">--}}
                                    {{-- </ul>--}}
                                {{-- </dd>--}}

                        @endif

                        <dt class="@if($user->engroup==2)lebox3 @else lebox4 @endif lebox_alert" data-position="top"
                            data-highlightClass="yd5a" data-tooltipClass="yd5" @if($user->engroup==2)data-step="5" @else
                            data-step="6" @endif
                            data-intro="被多人或站方檢舉，互動過程請提高十二萬分警覺。<em></em><em></em>">

                            <span class="le_span">警示會員</span>
                        </dt>
                        <dd>
                            <div class="loading warning" id="sjlist_alert_warning"><span
                                    class="loading_text">loading</span></div>
                            <ul class="sjlist sjlist_alert">
                            </ul>
                            <div class="page page_warned fenye" style="text-align: center;"></div>
                        </dd>
                        <dt class="lebox5">
                            <span class="le_span">已被站方封鎖會員</span>
                        </dt>
                        <dd>
                            <div class="loading warning" id="sjlist_banned_warning"><span
                                    class="loading_text">loading</span></div>
                            <ul class="sjlist sjlist_banned">
                            </ul>
                            <div class="page page_banned fenye" style="text-align: center;"></div>
                        </dd>
                    </dl>
                </div>
            </div>

            <input name="rows" type="hidden" id="rows" value="">

            <div class="zixun" style="display: none;">
                <div class="yd2a" data-position="top" data-highlightClass="yd2b" data-tooltipClass="yd2" data-step="2"
                    data-intro="信件顯示時間為：7天內，30天內， 以及全部<em></em><em></em>">
                    <span><input type="radio" name="RadioGroup1" value="7" id="RadioGroup1_0" checked>7天內訊息</span>
                    <span><input type="radio" name="RadioGroup1" value="30" id="RadioGroup1_1">30天內訊息</span>
                    <span><input type="radio" name="RadioGroup1" value="all" id="RadioGroup1_2">全部訊息</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bl bl_tab" id="tab03">
    <div class="bltitle">設定</div>
    <div class="blnr02 ">
        <h2>信息通知</h2>
        <select name="notifmessage" id="notifmessage" class="blinput">
            <option value="收到即通知" @if($user->meta_()->notifmessage=='收到即通知') selected @endif>收到即通知</option>
            <option value="每天通知一次" @if($user->meta_()->notifmessage=='每天通知一次') selected @endif>每天通知一次</option>
            <option value="不通知" @if($user->meta_()->notifmessage=='不通知') selected @endif>不通知</option>
        </select>
        <h2>收信設定</h2>
        <select name="notifhistory" id="notifhistory" class="blinput">
            <option value="顯示試用會員信件" @if($user->meta_()->notifhistory=='顯示普通會員信件') selected @endif>顯示試用會員信件</option>
            <option value="顯示VIP會員信件" @if($user->meta_()->notifhistory=='顯示VIP會員信件') selected @endif>顯示VIP會員信件</option>
            <option value="顯示全部會員信件" @if($user->meta_()->notifhistory=='顯示全部會員信件') selected @endif>顯示全部會員信件</option>
        </select>

        <a class="blbut" href="">更新資料</a>
    </div>
    <a id="" onclick="$('.blbg').click();" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>

<div class="bl bl_tab" id="show_banned_ele">
    <div class="bltitle banned_name"><span></span></div>
    <div class="n_blnr01 ">
        <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ route('reportPost') }}">
            {!! csrf_field() !!}
            <input type="hidden" name="aid" value="{{$user->id}}">
            <input type="hidden" name="uid" value="">
            <textarea name="content" cols="" rows="" class="n_nutext" placeholder=""></textarea>
            <div class="n_bbutton">
                <button type="submit" class="n_bllbut" style="border-style: none;">送出</button>
            </div>
        </form>
    </div>
    <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>

<script>
    let showMsg = false;
        let isLoading = 1;
        var total = 0;//總筆數
        var no_row_li='';
        no_row_li = '<li class="li_no_data"><div class="listicon02 nodata"><img src="/new/images/xj.png" class="list_img"><span>您目前尚無訊息</span></div></li>';
        var userIsVip = '{{ $isVip }}';
        var userGender = '{{ $user->engroup }}';

    //vvip
    var Page_vvip = {
        page : 1,
        row  : 10,
        DrawPage:function(total){
            var total_page  = Math.ceil(total/Page_vvip.row) == 0 ? 1 : Math.ceil(total/Page_vvip.row);
            var span_u      = 0;
            var str         = '';
            var i,active,prev_active,last_active;

            if(total_page==1){
                str   = '';
            }else if(Page_vvip.page==1){
                str =`<a href="javascript:" class="" data-p="next">上一頁</a>
                    <span class="new_page">${Page_vvip.page}/${total_page}</span>
                    <a href="javascript:" class="page-link" data-p="last">下一頁</a>`;
            }else if(Page_vvip.page==total_page){
                str =`<a href="javascript:" class="page-link" data-p="next">上一頁</a>
                    <span class="new_page">${Page_vvip.page}/${total_page}</span>
                    <a href="javascript:" class="" data-p="last">下一頁</a>`;
            }else{
                str = `
                    <a href="javascript:" class="page-link" data-p="next">上一頁</a>
                    <span class="new_page">${Page_vvip.page}/${total_page}</span>
                    <a href="javascript:" class="page-link" data-p="last">下一頁</a>
                `;
            }

            $('.page_vvip').html(str);
            $('.warning').hide();

            $('.page_vvip a.page-link').click(function(){
                $('.warning').show();
                $('.sjlist_vvip').children().css('display', 'none');

                switch($(this).data('p')) {
                    case 'next': Page_vvip.page = parseInt(Page_vvip.page) - 1; break;
                    case 'last': Page_vvip.page = parseInt(Page_vvip.page) + 1; break;
                    default: Page_vvip.page = parseInt($(this).data('p'));
                }
                Page_vvip.DrawPage(total);

                // date= $('input[name=RadioGroup1]:checked').val();
                date= $("#daysSelect option:selected").val();

                if(date==7){
                    $('.sjlist_vvip>.date7.vvipMember').slice((Page_vvip.page-1)*Page_vvip.row, Page_vvip.page*Page_vvip.row).css('display', '');
                }else if(date==30){
                    $('.sjlist_vvip>.common30.vvipMember').slice((Page_vvip.page-1)*Page_vvip.row, Page_vvip.page*Page_vvip.row).css('display', '');
                }else{
                    $('.sjlist_vvip>.vvipMember').slice((Page_vvip.page-1)*Page_vvip.row, Page_vvip.page*Page_vvip.row).css('display', '');
                }

                $('.sjlist_vvip>.li_no_data').remove();

                if($('.sjlist_vvip>li:visible').length == 0 && isLoading == 0){
                    $('#sjlist_vvip_warning').hide();
                    $('.sjlist_vvip').append(no_row_li);
                }
            });
        }
    };

        // admin
        var Page_admin = {
            page : 1,
            row  : 10,
            DrawPage:function(total){
                var total_page  = Math.ceil(total/Page_admin.row) == 0 ? 1 : Math.ceil(total/Page_admin.row);
                var span_u      = 0;
                var str         = '';
                var i,active,prev_active,last_active;

                if(total_page==1){
                    str   = '';
                }else if(Page_admin.page==1){
                    str =`<a href="javascript:" class="" data-p="next">上一頁</a>
                    <span class="new_page">${Page_admin.page}/${total_page}</span>
                    <a href="javascript:" class="page-link" data-p="last">下一頁</a>`;
                }else if(Page_admin.page==total_page){
                    str =`<a href="javascript:" class="page-link" data-p="next">上一頁</a>
                    <span class="new_page">${Page_admin.page}/${total_page}</span>
                    <a href="javascript:" class="" data-p="last">下一頁</a>`;
                }else{
                    str = `
                    <a href="javascript:" class="page-link" data-p="next">上一頁</a>
                    <span class="new_page">${Page_admin.page}/${total_page}</span>
                    <a href="javascript:" class="page-link" data-p="last">下一頁</a>
                `;
                }

                $('.page_admin').html(str);
                $('.warning').hide();

                $('.page_admin a.page-link').click(function(){
                    $('.warning').show();
                    $('.sjlist_admin').children().css('display', 'none');

                    switch($(this).data('p')) {
                        case 'next': Page_admin.page = parseInt(Page_admin.page) - 1; break;
                        case 'last': Page_admin.page = parseInt(Page_admin.page) + 1; break;
                        default: Page_admin.page = parseInt($(this).data('p'));
                    }
                    Page_admin.DrawPage(total);

                    // date= $('input[name=RadioGroup1]:checked').val();
                    date= $("#daysSelect option:selected").val();

                    if(date==7){
                        $('.sjlist_admin>.date7.adminMember').slice((Page_admin.page-1)*Page_admin.row, Page_admin.page*Page_admin.row).css('display', '');
                    }else if(date==30){
                        $('.sjlist_admin>.common30.adminMember').slice((Page_admin.page-1)*Page_admin.row, Page_admin.page*Page_admin.row).css('display', '');
                    }else{
                        $('.sjlist_admin>.adminMember').slice((Page_admin.page-1)*Page_admin.row, Page_admin.page*Page_admin.row).css('display', '');
                    }

                    $('.sjlist_admin>.li_no_data').remove();

                    if($('.sjlist_admin>li:visible').length == 0 && isLoading == 0){
                        $('#sjlist_admin_warning').hide();
                        $('.sjlist_admin').append(no_row_li);
                    }
                });
            }
        };
        //vip
        var Page = {
            page : 1,
            row  : 10,
            DrawPage:function(total){
                var total_page  = Math.ceil(total/Page.row) == 0 ? 1 : Math.ceil(total/Page.row);
                var span_u      = 0;
                var str         = '';
                var i,active,prev_active,last_active;

                if(total_page==1){
                    str   = '';
                }else if(Page.page==1){
                    str =`<a href="javascript:" class="" data-p="next">上一頁</a>
                    <span class="new_page">${Page.page}/${total_page}</span>
                    <a href="javascript:" class="page-link" data-p="last">下一頁</a>`;
                }else if(Page.page==total_page){
                    str =`<a href="javascript:" class="page-link" data-p="next">上一頁</a>
                    <span class="new_page">${Page.page}/${total_page}</span>
                    <a href="javascript:" class="" data-p="last">下一頁</a>`;
                }else{
                    str = `
                    <a href="javascript:" class="page-link" data-p="next">上一頁</a>
                    <span class="new_page">${Page.page}/${total_page}</span>
                    <a href="javascript:" class="page-link" data-p="last">下一頁</a>
                `;
                }

                $('.page_vip').html(str);
                $('.warning').hide();

                $('.page_vip a.page-link').click(function(){
                    $('.warning').show();
                    $('.sjlist_vip').children().css('display', 'none');

                    switch($(this).data('p')) {
                        case 'next': Page.page = parseInt(Page.page) - 1; break;
                        case 'last': Page.page = parseInt(Page.page) + 1; break;
                        default: Page.page = parseInt($(this).data('p'));
                    }
                    Page.DrawPage(total);

                    // date= $('input[name=RadioGroup1]:checked').val();
                    date= $("#daysSelect option:selected").val();

                    if(date==7){
                        $('.sjlist_vip>.date7.vipMember').slice((Page.page-1)*Page.row, Page.page*Page.row).css('display', '');
                    }else if(date==30){
                        $('.sjlist_vip>.common30.vipMember').slice((Page.page-1)*Page.row, Page.page*Page.row).css('display', '');
                    }else{
                        $('.sjlist_vip>.vipMember').slice((Page.page-1)*Page.row, Page.page*Page.row).css('display', '');
                    }

                    $('.sjlist_vip>.li_no_data').remove();

                    if($('.sjlist_vip>li:visible').length == 0 && isLoading == 0){
                        $('#sjlist_vip_warning').hide();
                        $('.sjlist_vip').append(no_row_li);
                    }
                });
            }
        };

        var Page_noVip = {
            page : 1,
            row  : 10,
            DrawPage:function(total){
                var total_page  = Math.ceil(total/Page_noVip.row) == 0 ? 1 : Math.ceil(total/Page_noVip.row);
                var span_u      = 0;
                var str         = '';
                var i,active,prev_active,last_active;

                if(total_page==1){
                    str   = '';
                }else if(Page_noVip.page==1){
                    str =`<a href="javascript:" class="" data-p="next">上一頁</a>
                    <span class="new_page">${Page_noVip.page}/${total_page}</span>
                    <a href="javascript:" class="page-link" data-p="last">下一頁</a>`;
                }else if(Page_noVip.page==total_page){
                    str =`<a href="javascript:" class="page-link" data-p="next">上一頁</a>
                    <span class="new_page">${Page_noVip.page}/${total_page}</span>
                    <a href="javascript:" class="" data-p="last">下一頁</a>`;
                }else{
                    str = `
                    <a href="javascript:" class="page-link" data-p="next">上一頁</a>
                    <span class="new_page">${Page_noVip.page}/${total_page}</span>
                    <a href="javascript:" class="page-link" data-p="last">下一頁</a>
                `;
                }

                $('.page_novip').html(str);
                $('.warning').hide();

                $('.page_novip a.page-link').click(function(){
                    $('.warning').show();
                    $('.sjlist_novip').children().css('display', 'none');

                    switch($(this).data('p')) {
                        case 'next': Page_noVip.page = parseInt(Page_noVip.page) - 1; break;
                        case 'last': Page_noVip.page = parseInt(Page_noVip.page) + 1; break;
                        default: Page_noVip.page = parseInt($(this).data('p'));
                    }
                    Page_noVip.DrawPage(total);
                    // date= $('input[name=RadioGroup1]:checked').val();
                    date= $("#daysSelect option:selected").val();

                    if(date==7) {
                        $('.sjlist_novip>.date7.novipMember').slice((Page_noVip.page - 1) * Page_noVip.row, Page_noVip.page * Page_noVip.row).css('display', '');
                    }else if(date==30){
                        $('.sjlist_novip>.common30.novipMember').slice((Page_noVip.page - 1) * Page_noVip.row, Page_noVip.page * Page_noVip.row).css('display', '');
                    }else{
                        $('.sjlist_novip>.novipMember').slice((Page_noVip.page - 1) * Page_noVip.row, Page_noVip.page * Page_noVip.row).css('display', '');
                    }

                    $('.sjlist_novip>.li_no_data').remove();

                    if($('.sjlist_novip>li:visible').length == 0 && isLoading == 0){
                        $('#sjlist_novip_warning').hide();
                        $('.sjlist_novip').append(no_row_li);
                    }
                });
            }
        };

        @foreach($exchange_period_name as $row)
            var  Page_exchange_period_{{$row->id}}= {
            page : 1,
            row  : 10,
            DrawPage:function(total){
                var total_page  = Math.ceil(total/Page_exchange_period_{{$row->id}}.row) == 0 ? 1 : Math.ceil(total/Page_exchange_period_{{$row->id}}.row);
                var span_u      = 0;
                var str         = '';
                var i,active,prev_active,last_active;

                {{--var new_page = Page_exchange_period_{{$row->id}}.page / total_page;--}}
                var current_page = Page_exchange_period_{{$row->id}}.page;
                if(total_page==1){
                    str   = '';
                }else if(Page_exchange_period_{{$row->id}}.page==1){
                    str =`<a href="javascript:" class="" data-p="next">上一頁</a>
                    <span class="new_page">${current_page}/${total_page}</span>
                    <a href="javascript:" class="page-link" data-p="last">下一頁</a>`;
                }else if(Page_exchange_period_{{$row->id}}.page==total_page){
                    str =`<a href="javascript:" class="page-link" data-p="next">上一頁</a>
                    <span class="new_page">${current_page}/${total_page}</span>
                    <a href="javascript:" class="" data-p="last">下一頁</a>`;
                }else{
                    str = `
                    <a href="javascript:" class="page-link" data-p="next">上一頁</a>
                    <span class="new_page">${current_page}/${total_page}</span>
                    <a href="javascript:" class="page-link" data-p="last">下一頁</a>
                `;
                }

                $('.page_exchange_period_{{$row->id}}').html(str);
                $('.warning').hide();

                $('.page_exchange_period_{{$row->id}} a.page-link').click(function(){
                    $('.warning').show();
                    $('.sjlist_exchange_period_{{$row->id}}').children().css('display', 'none');

                    switch($(this).data('p')) {
                        case 'next': Page_exchange_period_{{$row->id}}.page = parseInt(Page_exchange_period_{{$row->id}}.page) - 1; break;
                        case 'last': Page_exchange_period_{{$row->id}}.page = parseInt(Page_exchange_period_{{$row->id}}.page) + 1; break;
                        default: Page_exchange_period_{{$row->id}}.page = parseInt($(this).data('p'));
                    }
                    Page_exchange_period_{{$row->id}}.DrawPage(total);
                    // date= $('input[name=RadioGroup1]:checked').val();
                    date= $("#daysSelect option:selected").val();

                    if(date==7) {
                        $('.sjlist_exchange_period_{{$row->id}}>.date7.exchange_period_member_{{$row->id}}').slice((Page_exchange_period_{{$row->id}}.page - 1) * Page_exchange_period_{{$row->id}}.row, Page_exchange_period_{{$row->id}}.page * Page_exchange_period_{{$row->id}}.row).css('display', '');
                    }else if(date==30){
                        $('.sjlist_exchange_period_{{$row->id}}>.common30.exchange_period_member_{{$row->id}}').slice((Page_exchange_period_{{$row->id}}.page - 1) * Page_exchange_period_{{$row->id}}.row, Page_exchange_period_{{$row->id}}.page * Page_exchange_period_{{$row->id}}.row).css('display', '');
                    }else{
                        $('.sjlist_exchange_period_{{$row->id}}>.exchange_period_member_{{$row->id}}').slice((Page_exchange_period_{{$row->id}}.page - 1) * Page_exchange_period_{{$row->id}}.row, Page_exchange_period_{{$row->id}}.page * Page_exchange_period_{{$row->id}}.row).css('display', '');
                    }

                    $('.sjlist_exchange_period_{{$row->id}}>.li_no_data').remove();

                    if($('.sjlist_exchange_period_{{$row->id}}>li:visible').length == 0 && isLoading == 0){
                        $('#sjlist_exchange_period_warning_{{$row->id}}').hide();
                        $('.sjlist_exchange_period_{{$row->id}}').append(no_row_li);
                    }
                });
            }
        };
        @endforeach

        var Page_warned = {
            page : 1,
            row  : 10,
            DrawPage:function(total){
                var total_page  = Math.ceil(total/Page_warned.row) == 0 ? 1 : Math.ceil(total/Page_warned.row);
                var span_u      = 0;
                var str         = '';
                var i,active,prev_active,last_active;

                if(total_page==1){
                    str   = '';
                }else if(Page_warned.page==1){
                    str =`<a href="javascript:" class="" data-p="next">上一頁</a>
                    <span class="new_page">${Page_warned.page}/${total_page}</span>
                    <a href="javascript:" class="page-link" data-p="last">下一頁</a>`;
                }else if(Page_warned.page==total_page){
                    str =`<a href="javascript:" class="page-link" data-p="next">上一頁</a>
                    <span class="new_page">${Page_warned.page}/${total_page}</span>
                    <a href="javascript:" class="" data-p="last">下一頁</a>`;
                }else{
                    str = `
                    <a href="javascript:" class="page-link" data-p="next">上一頁</a>
                    <span class="new_page">${Page_warned.page}/${total_page}</span>
                    <a href="javascript:" class="page-link" data-p="last">下一頁</a>
                `;
                }


                $('.page_warned').html(str);
                $('.warning').hide();
                $('.page_warned a.page-link').click(function(){
                    $('.warning').show();

                    $('.sjlist_alert').children().css('display', 'none');

                    switch($(this).data('p')) {
                        case 'next': Page_warned.page = parseInt(Page_warned.page) - 1; break;
                        case 'last': Page_warned.page = parseInt(Page_warned.page) + 1; break;
                        default: Page_warned.page = parseInt($(this).data('p'));
                    }
                    Page_warned.DrawPage(total);
                    // date= $('input[name=RadioGroup1]:checked').val();
                    date= $("#daysSelect option:selected").val();

                    if(date==7) {
                        $('.sjlist_alert>.date7.alertMember').slice((Page_warned.page - 1) * Page_warned.row, Page_warned.page * Page_warned.row).css('display', '');
                    }else if(date==30){
                        $('.sjlist_alert>.common30.alertMember').slice((Page_warned.page - 1) * Page_warned.row, Page_warned.page * Page_warned.row).css('display', '');
                    }else{
                        $('.sjlist_alert>.alertMember').slice((Page_warned.page - 1) * Page_warned.row, Page_warned.page * Page_warned.row).css('display', '');
                    }

                    $('.sjlist_alert>.li_no_data').remove();

                    if($('.sjlist_alert>li:visible').length == 0){
                        if(!isLoading) {
                            $('#sjlist_alert_warning').hide();
                            $('.sjlist_alert').append(no_row_li);
                        }
                    }
                });
            }
        };
        
        
       var Page_banned = {
            page : 1,
            row  : 10,
            DrawPage:function(total){
                var total_page  = Math.ceil(total/Page_banned.row) == 0 ? 1 : Math.ceil(total/Page_banned.row);
                var span_u      = 0;
                var str         = '';
                var i,active,prev_active,last_active;

                if(total_page==1){
                    str   = '';
                }else if(Page_banned.page==1){
                    str =`<a href="javascript:" class="" data-p="next">上一頁</a>
                    <span class="new_page">${Page_banned.page}/${total_page}</span>
                    <a href="javascript:" class="page-link" data-p="last">下一頁</a>`;
                }else if(Page_banned.page==total_page){
                    str =`<a href="javascript:" class="page-link" data-p="next">上一頁</a>
                    <span class="new_page">${Page_banned.page}/${total_page}</span>
                    <a href="javascript:" class="" data-p="last">下一頁</a>`;
                }else{
                    str = `
                    <a href="javascript:" class="page-link" data-p="next">上一頁</a>
                    <span class="new_page">${Page_banned.page}/${total_page}</span>
                    <a href="javascript:" class="page-link" data-p="last">下一頁</a>
                `;
                }


                $('.page_banned').html(str);
                $('.warning').hide();
                $('.page_banned a.page-link').click(function(){
                    $('.warning').show();

                    $('.sjlist_banned').children().css('display', 'none');

                    switch($(this).data('p')) {
                        case 'next': Page_banned.page = parseInt(Page_banned.page) - 1; break;
                        case 'last': Page_banned.page = parseInt(Page_banned.page) + 1; break;
                        default: Page_banned.page = parseInt($(this).data('p'));
                    }
                    Page_banned.DrawPage(total);
                    // date= $('input[name=RadioGroup1]:checked').val();
                    date= $("#daysSelect option:selected").val();

                    if(date==7) {
                        $('.sjlist_banned>.date7.bannedMember').slice((Page_banned.page - 1) * Page_banned.row, Page_banned.page * Page_banned.row).css('display', '');
                    }else if(date==30){
                        $('.sjlist_banned>.common30.bannedMember').slice((Page_banned.page - 1) * Page_banned.row, Page_banned.page * Page_banned.row).css('display', '');
                    }else{
                        $('.sjlist_banned>.bannedMember').slice((Page_banned.page - 1) * Page_banned.row, Page_banned.page * Page_banned.row).css('display', '');
                    }

                    $('.sjlist_banned>.li_no_data').remove();

                    if($('.sjlist_banned>li:visible').length == 0){
                        if(!isLoading) {
                            $('#sjlist_banned_warning').hide();
                            $('.sjlist_banned').append(no_row_li);
                        }
                    }
                });
            }
        };        


        // var page = 1;//初始資料
        // var row = 10;//預設產出資料筆數
        //var total = 0;//總筆數
        //非VIP預設撈取全部資料，在model則會再判斷為七天內資料
        var date = 'all'

        if(window.location.hash) {
            // Fragment exists
            var hash = window.location.hash.substring(1);
            date = hash;
            //alert(hash);
        }
        // VIP資訊量較大顧預設為撈取七天
        if(userIsVip == 1 && !hash){
            date=7;
        }

        function startOfWeek(dt)
        {
            var diff = dt.getDate() - dt.getDay() + (dt.getDay() === 0 ? -6 : 1);
            return new Date(dt.setDate(diff));
        }
        function startOfMonth(dt)
        {
            return new Date(dt.getFullYear(), dt.getMonth(), 1);
        }
        function liContent(pic,user_name,content,created_at,read_n,i,user_id,isVip,show,isWarned,isBanned,exchange_period,isBlur=false,is_truth=false, isCan = false, cityAndArea, message_user_note){
            showMsg = show;
            var li='';
            var ss =((i+1)>Page.row)?'display:none;':'display:none;';
            var username = '{{$user->name}}';
            var engroup = '{{$user->engroup}}';
            var showCanMsg = '{{$user->show_can_message}}';

            var url = '{{ route("chat2WithUser", ":id") }}';
            url = url.replace(':id', user_id);
            var del_url = '{!! url("/dashboard/chat2/deleterow/:uid/:sid") !!}';

            var sid = '{{$user->id}}';
            del_url = del_url.replace(':uid', sid);
            del_url = del_url.replace(':sid', user_id);

            if(user_id==1049) {
                li += `
                <li class="row_data hy_bg02" style="${ss}" id="${user_id}">
                `;
            }else{
                li += `
                <li class="row_data ${(isCan && !is_truth)? "can":""} ${(showCanMsg=='0' && isCan && !isVVIP)? "d-none":""}" style="${ss}" id="${user_id}">
                `;
            }

            if(show==0){
                li += `
                     <div class="vipOnlyAlert" style="width: 100%">
                   `;
            }

            li += `<div class="si_bg ys_pr">`;

            var styBlur = isBlur? "blur_img" : "";
            

            if(show==1) {

                li += `<a href="${url}" target="_self">
                    <div style="width: 70px; float: left;">
                    `;
                if(is_truth) {
                    li += `<img src="/new/images/zz_zt2.png" class="ys_gt1">`;
                }else if(isCan) {
                    li += `<img src="/new/images/zz_zt1.png" class="ys_gt">`;
                }

                li += `<div class="sjpic ${styBlur} shanx" id="${user_id}" style="width: 65px; height: 65px;">
                        <img src="${pic}" style="margin-top: 5px;width: 65px; height: 65px;">
                        <div class="onlineStatusChatView"></div>
                       </div>
                        </div>
                        <div style="width: calc(100% - 75px); float: right;">
                        <div class="sjleftzz">
                        <div class="sjtable ${user_id}">${(read_n != 0 && isBanned == 0 ? `<i class="number ${user_id}">${read_n}</i>` : '')}<span class="ellipsis" style="width: 60%;">${user_name}</span></div>
                  `;
            }else if(show==0) {
                li += `<a href="javascript:void(0)" target="_self">
                        <div style="width: 70px; float: left;">`;
                if (show == 0 && engroup == 2) {
                    li += `<img src="/new/images/zz_zt2.png" class="ys_gt1">`;
                }
                li += `<div class="sjpic ${styBlur} shanx" id="${user_id}" style="width: 65px; height: 65px;">
                    <img src="${pic}" style="margin-top: 5px;width: 65px; height: 65px;">
                    <div class="onlineStatusChatView"></div>
                </div>
                </div>
                    <div style="width: calc(100% - 75px); float: right;">
                <div class="sjleft">
                    <div class="sjtable ${user_id}">${(read_n != 0 && isBanned == 0 ? `<i class="number ${user_id}">${read_n}</i>` : '')}<span class="ellipsis" style="width: 60%;">${user_name}</span></div>
                  `;
            }

            if(show==1) {
                li += `<span class="box">
                        <font class="ellipsis ${user_id}">${content}</font>
                       </span>
                       </div></a>
                   `;
            }else if(show==0 && isBanned==0) {
                li += `<font>
                        <div  data-toggle="popover" data-content="試用會員只能看到舊的十筆訊息，如果想要看新的訊息請刪除舊的通訊紀錄。" style="width: 100%"><img src="/new/images/icon_35.png"></div>
                       </font>
                       </div></a>
                   `;
            }

            li += `<div class="righ_nre">
                <h3 style="font-size: 12px !important;">${created_at}</h3>
                <h4 style="margin-top: -3px;" data-toggle="popover" data-content="${cityAndArea}"><img src="/new/images/zs_jt11.png" style="height:16px; margin-right: 3px;">${cityAndArea}</h4>
            </div>

            <div class="se_seccner">`;

            // if(show==1) {
                li += `<div class="se_input">
                        <input placeholder="您尚未留下備註" class="se_in01" id="massage_user_note_${user_id}" value="${message_user_note}"><a href="javascript:void(0)" class="se_button_a" onclick="massage_user_note('${user_id}');">確定</a>
                        </div>`;
            // }

            li += `<div class="se_rig_ff">`;

            if (userIsVip == 1) {
                li += `<a href="javascript:void(0)" class="serit_aa" onclick="block('${user_id}');"><img src="/new/images/ncion_11.png">封鎖</a>
                  `;
            }

            li += `<a href="javascript:void(0)" class="serit_aa" onclick="chk_delete('${del_url}');"><img src="/new/images/del_03n.png">刪除</a>
                   </div>
                   </div>
                    `;

            //vipOnlyAlert end
            if(show==0){
                li += `</div>
                   `;
            }

            li +=`</li>`;


            return li;
        }



        let dt = new Date();
        let temp_week =  new Date(startOfWeek(dt));
        let temp_month =  new Date(startOfMonth(dt));

        let this_week = temp_week.getFullYear() + '-' + ("0" + (temp_week.getMonth() + 1)).slice(-2) + '-' + ("0" + (temp_week.getDate())).slice(-2);
        let this_month = temp_month.getFullYear() + '-' + ("0" + (temp_month.getMonth() + 1)).slice(-2) + '-' + ("0" + (temp_month.getDate())).slice(-2);

        let before7days = new Date(dt.getTime() - (7 * 24 * 60 * 60 * 1000));
        let before30days = new Date(dt.getTime() - (30 * 24 * 60 * 60 * 1000));

        var this_7daysBefore = before7days.getFullYear() + '-' + ("0" + (before7days.getMonth()+1)).slice(-2) + '-' + ("0" + (before7days.getDate())).slice(-2);
        var this_30daysBefore = before30days.getFullYear() + '-' + ("0" + (before30days.getMonth()+1)).slice(-2) + '-' + ("0" + (before30days.getDate())).slice(-2);

        let usersList;
        {{-- Echo.join('Online').here(function (users){
            usersList = users;
        }); --}}

        var counter=1;
        //ajax資料
        function LoadTable(){
            div = '';
            const dateTime = Date.now();
            const timestamp = Math.floor(dateTime / 1000);
            
            // alert(date);
            $.ajax({
                url: '{{ route('showMessages') }}?{{ csrf_token() }}={{ now()->timestamp }}' + timestamp.toString(),
                type: 'POST',
                dataType: 'json',
                // headers: {
                //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                // },
                data: {
                    _token:"{{ csrf_token() }}",
                    date : date,
                    uid : '{{ $user->id }}',
                    isVip : '{{ $isVip }}',
                    userAgent: "Agent: " + String(navigator.userAgent) + " Platform: " + String(navigator.platform),
                },
                beforeSend:function(){//表單發送前做的事
                    isLoading = 1;
                    @if($user->engroup==2)
                        $('.sjlist_vvip').html('');
                        $('.sjlist_vip').html('');
                        $('.sjlist_novip').html('');
                        $('.page_vvip').hide();
                        $('.page_vip').hide();
                        $('.page_novip').hide();
                        
                        $('.sjlist_alert').html('');
                        $('.sjlist_banned').html('');
                        $('.page_banned').hide();
                    @elseif($user->engroup==1)
                        @foreach($exchange_period_name as $row)
                        $('.sjlist_exchange_period_{{$row->id}}').html('');
                        $('.page_exchange_period_{{$row->id}}').hide();
                        @endforeach
                    @endif

                    $('.sjlist_admin').html('');
                    $('.sjlist_alert').html('');
                    $('.sjlist_banned').html('');
                    $('.page_admin').hide();
                    $('.page_warning').hide();
                    $('.page_warned').hide();
                    $('.warning').show();
                },
                complete: function () {
                    isLoading = 0;
                    //alert($('.sjlist_vip>li:visible').length);

                },
                success:function(res){
                    isLoading = 0;
                    // console.log(res.msg);
                    var li = '';//樣板容器
                    // var p = page;
                    // var data = res.list;        //回傳資料
                    // var data_num = data.length; //資料筆數
                    // page=page+data_num;
                    // //若有資料時
                    //console.log(res.msg);
                    var rr=0;
                    //total=res.msg.length;
                    let msgLength = Object.keys(res.msg).length;
                    if(msgLength > 0){
                        $('#rows').val(msgLength);
                    }

                    hide_vip_counts = 0;
                    hide_vip_counts = $('#rows').val() - 10;

                    $.each(res.msg,function(i,e) {
                       if(e.user_id==1049 || e.isBanned==1) {
                           //hide_vip_counts = $('#rows').val() - 10 - 1;
						   if(i>=hide_vip_counts) hide_vip_counts = hide_vip_counts - 1;
                       }
                    });

                    $.each(res.msg,function(i,e) {
                        var isBlur = true;
                        if('{{$user->meta_()->isWarned == 1 || $user->aw_relation}}' == true){
                            isBlur = true;
                        }else{
                            var blurryAvatar = e.blurry_avatar? e.blurry_avatar.split(',') : '';
                            if(blurryAvatar.length > 1){
                                var nowB = '{{$isVip? "VIP" : "general"}}';
                                if( blurryAvatar.indexOf(nowB) != -1){
                                    // console.log(blurryAvatar);
                                    isBlur = true;
                                } else {
                                    isBlur = false;
                                }
                            } else {
                                isBlur = false;
                            }
                        }
                        let pic = e.pic_blur;
                        if(!e.isblur || !e.pic_blur) {
                            pic = e.pic;
                        }
                        
                        
                        rr += parseInt(e.read_n);
                        if (userIsVip != 1 && i < hide_vip_counts && hide_vip_counts > 0 ) {
                            if(e.user_id == 1049 || e.isBanned==1){
                                //hide_vip_counts = hide_vip_counts-1;
                                if (e && e.user_id) li = liContent(pic, e.user_name, e.content, e.created_at, e.read_n, i, e.user_id, e.isVip, 1,e.isWarned,e.isBanned,e.exchange_period,e.isblur,e.is_truth, e.isCan, e.cityAndArea, e.message_user_note);
                            }else {							
                                if (e && e.user_id) li = liContent(pic, e.user_name, e.content, e.created_at, e.read_n, i, e.user_id, e.isVip, 0,e.isWarned,e.isBanned,e.exchange_period,e.isblur,e.is_truth, e.isCan, e.cityAndArea, e.message_user_note);
                            }
                        }else {
							//if(e.isBanned==1) hide_vip_counts = hide_vip_counts+1;
                            if (e && e.user_id) li = liContent(pic, e.user_name, e.content, e.created_at, e.read_n, i, e.user_id, e.isVip, 1,e.isWarned,e.isBanned,e.exchange_period,e.isblur,e.is_truth, e.isCan, e.cityAndArea, e.message_user_note);
                        }

                        var has_vvip_msg_count=0;
                        if (typeof e.created_at !== 'undefined') {
                            if (e.created_at.substr(0, 10) >= this_7daysBefore) {
                                if(e.isBanned==1) {
                                    $('.sjlist_banned').append(li).find('.row_data').addClass('date7 bannedMember common30');
                                }
                                else if (e.isWarned==1) {
                                    $('.sjlist_alert').append(li).find('.row_data').addClass('date7 alertMember common30');
                                }else if (e.isVVIP == 1 && userGender==2) {
                                    has_vvip_msg_count+=1;
                                    $('.sjlist_vvip').append(li).find('.row_data').addClass('date7 vvipMember common30');
                                }else if (e.from_id==1049 || e.to_id==1049) {
                                    $('.sjlist_admin').append(li).find('.row_data').addClass('date7 adminMember common30');
                                }else if (e.isVip == 1 && userGender==2) {
                                    $('.sjlist_vip').append(li).find('.row_data').addClass('date7 vipMember common30');
                                }else if (e.isVip == 0 && userGender==2) {
                                    $('.sjlist_novip').append(li).find('.row_data').addClass('date7 novipMember common30');
                                }

                                @if($user->engroup==1)
                                    @foreach($exchange_period_name as $row)
                                        if (userGender==1 && e.exchange_period=='{{$row->id}}' && e.user_id != 1049 && e.isWarned == 0 && e.isBanned==0){
                                            $('.sjlist_exchange_period_{{$row->id}}').append(li).find('.row_data').addClass('date7 exchange_period_member_{{$row->id}} common30');
                                        }
                                    @endforeach
                                @endif

                            }else if (e.created_at != '' && e.created_at.substr(0, 10) >= this_30daysBefore) {
                                if(e.isBanned==1) {
                                    $('.sjlist_banned').append(li).find('.row_data').addClass('date30 bannedMember common30');
                                }
                                else if (e.isWarned==1){
                                    $('.sjlist_alert').append(li).find('.row_data').addClass('date30 alertMember common30');
                                }else if (e.isVVIP == 1 && userGender==2) {
                                    has_vvip_msg_count+=1;
                                    $('.sjlist_vvip').append(li).find('.row_data').addClass('date30 vvipMember common30');
                                }else if (e.from_id==1049 || e.to_id==1049) {
                                    $('.sjlist_admin').append(li).find('.row_data').addClass('date30 adminMember common30');
                                }else if (e.isVip == 1 && userGender==2) {
                                    $('.sjlist_vip').append(li).find('.row_data').addClass('date30 vipMember common30');
                                }else if (e.isVip == 0 && userGender==2)  {
                                    $('.sjlist_novip').append(li).find('.row_data').addClass('date30 novipMember common30');
                                }

                                @if($user->engroup==1)
                                    @foreach($exchange_period_name as $row)
                                        if (userGender==1 && e.exchange_period=='{{$row->id}}' && e.user_id != 1049 && e.isWarned == 0 && e.isBanned==0){
                                            $('.sjlist_exchange_period_{{$row->id}}').append(li).find('.row_data').addClass('date30 exchange_period_member_{{$row->id}} common30');
                                        }
                                    @endforeach
                                @endif

                            } else {
                                if(e.isBanned==1) {
                                    $('.sjlist_banned').append(li).find('.row_data').addClass('dateAll bannedMember');
                                }
                                else if (e.isWarned==1) {
                                    $('.sjlist_alert').append(li).find('.row_data').addClass('dateAll alertMember');
                                }else if (e.isVVIP == 1 && userGender==2) {
                                    has_vvip_msg_count+=1;
                                    $('.sjlist_vvip').append(li).find('.row_data').addClass('dateAll vvipMember');
                                }else if (e.from_id==1049 || e.to_id==1049) {
                                    $('.sjlist_admin').append(li).find('.row_data').addClass('dateAll adminMember');
                                }else if (e.isVip == 1 && userGender==2) {
                                    $('.sjlist_vip').append(li).find('.row_data').addClass('dateAll vipMember');
                                }else if (e.isVip == 0 && userGender==2)  {
                                    $('.sjlist_novip').append(li).find('.row_data').addClass('dateAll novipMember');
                                }

                                @if($user->engroup==1)
                                    if (userGender==1 && e.user_id == 1049){
                                        $('.sjlist_exchange_period_1').append(li).find('.row_data').addClass('dateAll exchange_period_member_1');
                                    }
                                    @foreach($exchange_period_name as $row)
                                        if (userGender==1 && e.exchange_period=='{{$row->id}}' && e.user_id != 1049 && e.isWarned == 0 && e.isBanned==0){
                                            $('.sjlist_exchange_period_{{$row->id}}').append(li).find('.row_data').addClass('dateAll exchange_period_member_{{$row->id}}');
                                        }
                                    @endforeach
                                @endif
                            }
                        }

                        @if($isVip)
                            $.each(usersList, function(i2, e2){
                                console.log(e2.id == e.user_id);
                                if(e2.id == e.user_id){
                                    setUserOnlineStatus(1, e2.id);
                                }
                            });
                        @else
                            setUserOnlineStatus("Non-VIP", e.user_id);
                        @endif
                    });

                    setTimeout(function(){
                        if(date==7){
                            $("input[name*='RadioGroup1'][value='7']").prop("checked", true);
                        }else if(date==30){
                            $("input[name*='RadioGroup1'][value='30']").prop("checked", true);
                        }else if(date=='all'){
                            $("input[name*='RadioGroup1'][value='all']").prop("checked", true);
                        }

                        $('.dateAll').hide();
                        $('.date30').hide();
                        $('.date7').hide();

                        if(userIsVip != 1){
                            // alert(hash);
                            if(!hash){
                                $("input[name*='RadioGroup1'][value='7']").prop("checked", true);
                            }
                            if(hash==7 || !hash) {

                                @if($user->engroup==2)

                                    let vvip_counts = $('.date7.vvipMember').length;
                                    if (vvip_counts > 10) {
                                        $('.page_vvip').show();
                                    }
                                    Page.DrawPage(vvip_counts);
                                    $('.sjlist_vvip>.date7.vvipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                                    let vip_counts = $('.date7.vipMember').length;
                                    if (vip_counts > 10) {
                                        $('.page_vip').show();
                                    }
                                    Page.DrawPage(vip_counts);
                                    $('.sjlist_vip>.date7.vipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                                    let novip_counts = $('.date7.novipMember').length;
                                    if (novip_counts > 10) {
                                        $('.page_novip').show();
                                    }
                                    Page_noVip.DrawPage(novip_counts);
                                    $('.sjlist_novip>.date7.novipMember').slice((Page_noVip.page - 1) * Page_noVip.row, Page_noVip.page * Page_noVip.row).css('display', '');

                                @elseif($user->engroup==1)
                                    @foreach($exchange_period_name as $row)

                                    let exchange_period_counts_{{$row->id}} = $('.date7.exchange_period_member_{{$row->id}}').length;
                                    if (exchange_period_counts_{{$row->id}} > 10) {
                                        $('.page_exchange_period_{{$row->id}}').show();
                                    }
                                    Page_exchange_period_{{$row->id}}.DrawPage(exchange_period_counts_{{$row->id}});
                                    $('.sjlist_exchange_period_{{$row->id}}>.date7.exchange_period_member_{{$row->id}}').slice((Page_exchange_period_{{$row->id}}.page - 1) * Page_exchange_period_{{$row->id}}.row, Page_exchange_period_{{$row->id}}.page * Page_exchange_period_{{$row->id}}.row).css('display', '');

                                    @endforeach
                                @endif

                                let admin_counts = $('.date7.adminMember').length;
                                if (admin_counts > 10) {
                                    $('.page_admin').show();
                                }
                                Page_admin.DrawPage(admin_counts);
                                $('.sjlist_admin>.date7.adminMember').slice((Page_admin.page - 1) * Page_admin.row, Page_admin.page * Page_admin.row).css('display', '');

                                let alert_counts = $('.date7.alertMember').length;
                                if (alert_counts > 10) {
                                    $('.page_warned').show();
                                }
                                Page_warned.DrawPage(alert_counts);
                                $('.sjlist_alert>.date7.alertMember').slice((Page_warned.page - 1) * Page_warned.row, Page_warned.page * Page_warned.row).css('display', '');


                                let banned_counts = $('.date7.bannedMember').length;
                                if (banned_counts > 10) {
                                    $('.page_banned').show();
                                }
                                Page_banned.DrawPage(banned_counts);
                                $('.sjlist_banned>.date7.bannedMember').slice((Page_banned.page - 1) * Page_banned.row, Page_banned.page * Page_banned.row).css('display', '');
                            }else if(hash==30){
                                @if($user->engroup==2)

                                    let vvip_counts = $('.common30.vvipMember').length;
                                    if (vvip_counts > 10) {
                                        $('.page_vvip').show();
                                    }
                                    Page.DrawPage(vvip_counts);
                                    $('.sjlist_vvip>.common30.vvipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                                    let vip_counts = $('.common30.vipMember').length;
                                    if (vip_counts > 10) {
                                        $('.page_vip').show();
                                    }
                                    Page.DrawPage(vip_counts);
                                    $('.sjlist_vip>.common30.vipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                                    let novip_counts = $('.common30.novipMember').length;
                                    if (novip_counts > 10) {
                                        $('.page_novip').show();
                                    }
                                    Page_noVip.DrawPage(novip_counts);
                                    $('.sjlist_novip>.common30.novipMember').slice((Page_noVip.page - 1) * Page_noVip.row, Page_noVip.page * Page_noVip.row).css('display', '');

                                @elseif($user->engroup==1)
                                        @foreach($exchange_period_name as $row)

                                        let exchange_period_counts_{{$row->id}} = $('.common30.exchange_period_member_{{$row->id}}').length;
                                        if (exchange_period_counts_{{$row->id}} > 10) {
                                            $('.page_exchange_period_{{$row->id}}').show();
                                        }
                                        Page_exchange_period_{{$row->id}}.DrawPage(exchange_period_counts_{{$row->id}});
                                        $('.sjlist_exchange_period_{{$row->id}}>.common30.exchange_period_member_{{$row->id}}').slice((Page_exchange_period_{{$row->id}}.page - 1) * Page_exchange_period_{{$row->id}}.row, Page_exchange_period_{{$row->id}}.page * Page_exchange_period_{{$row->id}}.row).css('display', '');

                                        @endforeach

                                @endif
                                
                                let admin_counts = $('.common30.adminMember').length;
                                if (admin_counts > 10) {
                                    $('.page_admin').show();
                                }
                                Page_admin.DrawPage(admin_counts);
                                $('.sjlist_admin>.common30.adminMember').slice((Page_admin.page - 1) * Page_admin.row, Page_admin.page * Page_admin.row).css('display', '');

                                let alert_counts = $('.common30.alertMember').length;
                                if (alert_counts > 10) {
                                    $('.page_alert').show();
                                }
                                Page_warned.DrawPage(alert_counts);
                                $('.sjlist_alert>.common30.alertMember').slice((Page_warned.page - 1) * Page_warned.row, Page_warned.page * Page_warned.row).css('display', '');

                                let banned_counts = $('.common30.bannedMember').length;
                                if (banned_counts > 10) {
                                    $('.page_banned').show();
                                }
                                Page_banned.DrawPage(banned_counts);
                                $('.sjlist_banned>.common30.bannedMember').slice((Page_banned.page - 1) * Page_banned.row, Page_banned.page * Page_banned.row).css('display', '');
                            }else if(hash=='all'){
                                @if($user->engroup==2)
                                    let vvip_counts = $('.vvipMember').length;
                                    if (vvip_counts > 10) {
                                        $('.page_vvip').show();
                                    }
                                    Page.DrawPage(vvip_counts);
                                    $('.sjlist_vvip>.vvipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                                    let vip_counts = $('.vipMember').length;
                                    if (vip_counts > 10) {
                                        $('.page_vip').show();
                                    }
                                    Page.DrawPage(vip_counts);
                                    $('.sjlist_vip>.vipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                                    let novip_counts = $('.novipMember').length;
                                    if (novip_counts > 10) {
                                        $('.page_novip').show();
                                    }
                                    Page_noVip.DrawPage(novip_counts);
                                    $('.sjlist_novip>.novipMember').slice((Page_noVip.page - 1) * Page_noVip.row, Page_noVip.page * Page_noVip.row).css('display', '');

                                @elseif($user->engroup==1)
                                        @foreach($exchange_period_name as $row)

                                        let exchange_period_counts_{{$row->id}} = $('.exchange_period_member_{{$row->id}}').length;
                                        if (exchange_period_counts_{{$row->id}} > 10) {
                                            $('.page_exchange_period_{{$row->id}}').show();
                                        }
                                        Page_exchange_period_{{$row->id}}.DrawPage(exchange_period_counts_{{$row->id}});
                                        $('.sjlist_exchange_period_{{$row->id}}>.exchange_period_member_{{$row->id}}').slice((Page_exchange_period_{{$row->id}}.page - 1) * Page_exchange_period_{{$row->id}}.row, Page_exchange_period_{{$row->id}}.page * Page_exchange_period_{{$row->id}}.row).css('display', '');

                                        @endforeach
                                @endif
                                
                                let admin_counts = $('.adminMember').length;
                                if (admin_counts > 10) {
                                    $('.page_admin').show();
                                }
                                Page_admin.DrawPage(admin_counts);
                                $('.sjlist_admin>.adminMember').slice((Page_admin.page - 1) * Page_admin.row, Page_admin.page * Page_admin.row).css('display', '');

                                let alert_counts = $('.alertMember').length;
                                if (alert_counts > 10) {
                                    $('.page_warned').show();
                                }
                                Page_warned.DrawPage(alert_counts);
                                $('.sjlist_alert>.alertMember').slice((Page_warned.page - 1) * Page_warned.row, Page_warned.page * Page_warned.row).css('display', '');
                            
                                let banned_counts = $('.bannedMember').length;
                                if (banned_counts > 10) {
                                    $('.page_banned').show();
                                }
                                Page_banned.DrawPage(banned_counts);
                                $('.sjlist_banned>.bannedMember').slice((Page_banned.page - 1) * Page_banned.row, Page_banned.page * Page_banned.row).css('display', '');    
                            }

                        }else{
                            if (date == 7) {
                                $('.row_data').hide();

                                @if($user->engroup==2)
                                    let vvip_counts = $('.date7.vvipMember').length;
                                    if (vvip_counts > 10) {
                                        $('.page_vvip').show();
                                    }
                                    Page.DrawPage(vvip_counts);
                                    $('.sjlist_vvip>.date7.vvipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                                    let vip_counts = $('.date7.vipMember').length;
                                    if (vip_counts > 10) {
                                        $('.page_vip').show();
                                    }
                                    Page.DrawPage(vip_counts);
                                    $('.sjlist_vip>.date7.vipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                                    let novip_counts = $('.date7.novipMember').length;
                                    if (novip_counts > 10) {
                                        $('.page_novip').show();
                                    }
                                    Page_noVip.DrawPage(novip_counts);
                                    $('.sjlist_novip>.date7.novipMember').slice((Page_noVip.page - 1) * Page_noVip.row, Page_noVip.page * Page_noVip.row).css('display', '');

                                @elseif($user->engroup==1)
                                    @foreach($exchange_period_name as $row)

                                    let exchange_period_counts_{{$row->id}} = $('.date7.exchange_period_member_{{$row->id}}').length;
                                    if (exchange_period_counts_{{$row->id}} > 10) {
                                        $('.page_exchange_period_{{$row->id}}').show();
                                    }
                                    Page_exchange_period_{{$row->id}}.DrawPage(exchange_period_counts_{{$row->id}});
                                    $('.sjlist_exchange_period_{{$row->id}}>.date7.exchange_period_member_{{$row->id}}').slice((Page_exchange_period_{{$row->id}}.page - 1) * Page_exchange_period_{{$row->id}}.row, Page_exchange_period_{{$row->id}}.page * Page_exchange_period_{{$row->id}}.row).css('display', '');

                                    @endforeach
                                @endif
                                
                                let admin_counts = $('.date7.adminMember').length;
                                if (admin_counts > 10) {
                                    $('.page_admin').show();
                                }
                                Page_admin.DrawPage(admin_counts);
                                $('.sjlist_admin>.date7.adminMember').slice((Page_admin.page - 1) * Page_admin.row, Page_admin.page * Page_admin.row).css('display', '');

                                let alert_counts = $('.date7.alertMember').length;
                                if (alert_counts > 10) {
                                    $('.page_warned').show();
                                }
                                Page_warned.DrawPage(alert_counts);
                                $('.sjlist_alert>.date7.alertMember').slice((Page_warned.page - 1) * Page_warned.row, Page_warned.page * Page_warned.row).css('display', '');

                                let banned_counts = $('.date7.bannedMember').length;
                                if (banned_counts > 10) {
                                    $('.page_banned').show();
                                }
                                Page_banned.DrawPage(banned_counts);
                                $('.sjlist_banned>.date7.bannedMember').slice((Page_banned.page - 1) * Page_banned.row, Page_banned.page * Page_banned.row).css('display', '');
                            } else if (date == 30) {
                                $('.row_data').hide();

                                @if($user->engroup==2)

                                    let vvip_counts = $('.common30.vvipMember').length;
                                    if (vvip_counts > 10) {
                                        $('.page_vvip').show();
                                    }
                                    Page.DrawPage(vvip_counts);
                                    $('.sjlist_vvip>.common30.vvipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                                    let vip_counts = $('.common30.vipMember').length;
                                    if (vip_counts > 10) {
                                        $('.page_vip').show();
                                    }
                                    Page.DrawPage(vip_counts);
                                    $('.sjlist_vip>.common30.vipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                                    let novip_counts = $('.common30.novipMember').length;
                                    if (novip_counts > 10) {
                                        $('.page_novip').show();
                                    }
                                    Page_noVip.DrawPage(novip_counts);
                                    $('.sjlist_novip>.common30.novipMember').slice((Page_noVip.page - 1) * Page_noVip.row, Page_noVip.page * Page_noVip.row).css('display', '');

                                @elseif($user->engroup==1)
                                    @foreach($exchange_period_name as $row)

                                        let exchange_period_counts_{{$row->id}} = $('.common30.exchange_period_member_{{$row->id}}').length;
                                        if (exchange_period_counts_{{$row->id}} > 10) {
                                            $('.page_exchange_period_{{$row->id}}').show();
                                        }
                                        Page_exchange_period_{{$row->id}}.DrawPage(exchange_period_counts_{{$row->id}});
                                        $('.sjlist_exchange_period_{{$row->id}}>.common30.exchange_period_member_{{$row->id}}').slice((Page_exchange_period_{{$row->id}}.page - 1) * Page_exchange_period_{{$row->id}}.row, Page_exchange_period_{{$row->id}}.page * Page_exchange_period_{{$row->id}}.row).css('display', '');

                                    @endforeach
                                @endif
                                
                                let admin_counts = $('.common30.adminMember').length;
                                if (admin_counts > 10) {
                                    $('.page_admin').show();
                                }
                                Page_admin.DrawPage(admin_counts);
                                $('.sjlist_admin>.common30.adminMember').slice((Page_admin.page - 1) * Page_admin.row, Page_admin.page * Page_admin.row).css('display', '');                                

                                let alert_counts = $('.common30.alertMember').length;
                                if (alert_counts > 10) {
                                    $('.page_warned').show();
                                }
                                Page_warned.DrawPage(alert_counts);
                                $('.sjlist_alert>.common30.alertMember').slice((Page_warned.page - 1) * Page_warned.row, Page_warned.page * Page_warned.row).css('display', '');
                                
                                
                                let banned_counts = $('.common30.bannedMember').length;
                                if (banned_counts > 10) {
                                    $('.page_banned').show();
                                }
                                Page_banned.DrawPage(banned_counts);
                                $('.sjlist_banned>.common30.bannedMember').slice((Page_banned.page - 1) * Page_banned.row, Page_banned.page * Page_banned.row).css('display', '');                                
                            } else {

                                @if($user->engroup==2)
                                    let vvip_counts = $('.vvipMember').length;
                                    if (vvip_counts > 10) {
                                        $('.page_vvip').show();
                                    }
                                    Page.DrawPage(vvip_counts);
                                    $('.sjlist_vvip>.vvipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                                    let vip_counts = $('.vipMember').length;
                                    if (vip_counts > 10) {
                                        $('.page_vip').show();
                                    }
                                    Page.DrawPage(vip_counts);
                                    $('.sjlist_vip>.vipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                                    let novip_counts = $('.novipMember').length;
                                    if (novip_counts > 10) {
                                        $('.page_novip').show();
                                    }
                                    Page_noVip.DrawPage(novip_counts);
                                    $('.sjlist_novip>.novipMember').slice((Page_noVip.page - 1) * Page_noVip.row, Page_noVip.page * Page_noVip.row).css('display', '');

                                @elseif($user->engroup==1)
                                    @foreach($exchange_period_name as $row)

                                        let exchange_period_counts_{{$row->id}} = $('.exchange_period_member_{{$row->id}}').length;
                                        if (exchange_period_counts_{{$row->id}} > 10) {
                                            $('.page_exchange_period_{{$row->id}}').show();
                                        }
                                        Page_exchange_period_{{$row->id}}.DrawPage(exchange_period_counts_{{$row->id}});
                                        $('.sjlist_exchange_period_{{$row->id}}>.exchange_period_member_{{$row->id}}').slice((Page_exchange_period_{{$row->id}}.page - 1) * Page_exchange_period_{{$row->id}}.row, Page_exchange_period_{{$row->id}}.page * Page_exchange_period_{{$row->id}}.row).css('display', '');

                                    @endforeach
                                @endif
                                
                                let admin_counts = $('.adminMember').length;
                                if (admin_counts > 10) {
                                    $('.page_admin').show();
                                }
                                Page_admin.DrawPage(admin_counts);
                                $('.sjlist_admin>.adminMember').slice((Page_admin.page - 1) * Page_admin.row, Page_admin.page * Page_admin.row).css('display', '');

                                let alert_counts = $('.alertMember').length;
                                if (alert_counts > 10) {
                                    $('.page_warned').show();
                                }
                                Page_warned.DrawPage(alert_counts);
                                $('.sjlist_alert>.alertMember').slice((Page_warned.page - 1) * Page_warned.row, Page_warned.page * Page_warned.row).css('display', '');
                            
                            
                                let banned_counts = $('.bannedMember').length;
                                if (banned_counts > 10) {
                                    $('.page_banned').show();
                                }
                                Page_banned.DrawPage(banned_counts);
                                $('.sjlist_banned>.bannedMember').slice((Page_banned.page - 1) * Page_banned.row, Page_banned.page * Page_banned.row).css('display', '');    
    
                            }
                        }

                        $('.warning').hide();

                        @if($user->engroup==2)
                            $('.sjlist_vvip>.li_no_data').remove();
                            $('.sjlist_admin>.li_no_data').remove();
                            $('.sjlist_vip>.li_no_data').remove();
                            $('.sjlist_novip>.li_no_data').remove();
                            $('.sjlist_alert>.li_no_data').remove();
                            if ($('.sjlist_vvip>li:visible').length == 0) {
                                $('#sjlist_vvip_warning').hide();
                                $('.sjlist_vvip').append(no_row_li);
                            }

                            var has_vvip_msg_count=$('.sjlist_vvip>li').not('.li_no_data').not('.d-none').length;
                            //alert('筆數：'+has_vvip_msg_count + ' ,test筆數：'+ $('.sjlist_vvip>li:visible').length);
                            if ($('.sjlist_vvip>li').not('.li_no_data').length > 0) {
                                if(!$('.leboxVVIP').hasClass('on')){
                                    $('.sjlist_vvip>.li_no_data').hide();
                                    $('.leboxVVIP').toggleClass('on');
                                    $('.leboxVVIP').next('dd').slideToggle("slow");
                                }
                            }

                            if ($('.sjlist_admin>li:visible').length == 0) {
                                $('#sjlist_admin_warning').hide();
                                $('.sjlist_admin').append(no_row_li);
                            }
                            if ($('.sjlist_vip>li:visible').length == 0) {
                                $('#sjlist_vip_warning').hide();
                                $('.sjlist_vip').append(no_row_li);
                            }
                            if ($('.sjlist_novip>li:visible').length == 0) {
                                $('#sjlist_novip_warning').hide();
                                $('.sjlist_novip').append(no_row_li);
                            }
                            if ($('.sjlist_alert>li:visible').length == 0) {
                                $('#sjlist_alert_warning').hide();
                                $('.sjlist_alert').append(no_row_li);
                            }
                            if ($('.sjlist_banned>li:visible').length == 0) {
                                $('#sjlist_banned_warning').hide();
                                $('.sjlist_banned').append(no_row_li);
                            }                            
                        @elseif($user->engroup==1)
                            $('.sjlist_admin>.li_no_data').remove();
                            if ($('.sjlist_admin>li:visible').length == 0) {
                                $('#sjlist_admin_warning').hide();
                                $('.sjlist_admin').append(no_row_li);
                            }
                            @foreach($exchange_period_name as $row)
                            $('.sjlist_exchange_period_{{$row->id}}>.li_no_data').remove();
                            if ($('.sjlist_exchange_period_{{$row->id}}>li:visible').length == 0) {
                                $('#sjlist_exchange_period_warning_{{$row->id}}').hide();
                                $('.sjlist_exchange_period_{{$row->id}}').append(no_row_li);
                            }
                            @endforeach
                            if ($('.sjlist_alert>li:visible').length == 0) {
                                $('#sjlist_alert_warning').hide();
                                $('.sjlist_alert').append(no_row_li);
                            }
                            
                            if ($('.sjlist_banned>li:visible').length == 0) {
                                $('#sjlist_banned_warning').hide();
                                $('.sjlist_banned').append(no_row_li);
                            }                            
                        @endif
                    }, 300);

                    $('[data-toggle="popover"]').popover({
                        animated: 'fade',
                        placement: 'bottom',
                        trigger: 'hover',
                        html: true,
                        content: function () { return '<h4>' + $(this).data('content') + '</h4>'; }
                    });
                }
            })
                .done(function() {
                    // if(page-1>=total){
                    //     $('.listMoreBtn').attr('disabled', 'true').removeClass('cursor-pointer').html('NO MORE');
                    // }else{
                    //     $('.listMoreBtn').removeAttr('disabled').addClass('cursor-pointer').html('MORE');
                    // }
                    // check li rows

                });

        }

        LoadTable();

        // $('#daysSelect').on('change', function() {
        //   alert( this.value );
        //   console.log($("#daysSelect option:selected").val())
        // });

        $('.ys_inbut').on('click', function() {

            let text = $(this).find('span').text();
            if(text == '顯示罐頭訊息') {
                $(this).find('span').text('收起罐頭訊息');
            } else if(text == '收起罐頭訊息') {
                $(this).find('span').text('顯示罐頭訊息');
            }

            $('.row_data.can').toggleClass('d-none');
            $('.row_data.can').next('.li_no_data').toggle();

            $.ajax({
				type: 'POST',
				url: "/dashboard/toggleShowCanMessage",
				data:{
					_token: '{{csrf_token()}}',
					user_id: '{{$user->id}}',
				},
				dataType:"json"
			});
           
        });

        $('#daysSelect').on('change', function() {
            date= $("#daysSelect option:selected").val();
            window.location.hash = '#'+ date;
            @if($user->engroup==1)
                    @foreach($exchange_period_name as $row)
                        Page_exchange_period_{{$row->id}}.page=1;
                        $('.page_exchange_period_{{$row->id}}').hide();
                    @endforeach
            @elseif($user->engroup==2)
                Page.page=1;
                Page_noVip.page=1;
                $('.page_vip').hide();
                $('.page_novip').hide();
                
                $('.page_warned').hide();
                $('.page_banned').hide();                
            @endif

            $('.warning').show();
            if(userIsVip==1){
                LoadTable();
            }else{

                 if (date == 7) {
                    $('.row_data').hide();
                 @if($user->engroup==2)

                     let vvip_counts = $('.date7.vvipMember').length;
                     if (vvip_counts > 10) {
                         $('.page_vvip').show();
                     }
                     Page.DrawPage(vvip_counts);
                     $('.sjlist_vvip>.date7.vvipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                     let vip_counts = $('.date7.vipMember').length;
                     if (vip_counts > 10) {
                        $('.page_vip').show();
                     }
                     Page.DrawPage(vip_counts);
                     $('.sjlist_vip>.date7.vipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                     let novip_counts = $('.date7.novipMember').length;
                     if (novip_counts > 10) {
                        $('.page_novip').show();
                     }
                     Page_noVip.DrawPage(novip_counts);
                     $('.sjlist_novip>.date7.novipMember').slice((Page_noVip.page - 1) * Page_noVip.row, Page_noVip.page * Page_noVip.row).css('display', '');

                 @elseif($user->engroup==1)
                         @foreach($exchange_period_name as $row)
                            let exchange_period_counts_{{$row->id}} = $('.date7.exchange_period_member_{{$row->id}}').length;
                            if (exchange_period_counts_{{$row->id}} > 10) {
                                $('.page_exchange_period_{{$row->id}}').show();
                            }
                            Page_exchange_period_{{$row->id}}.DrawPage(exchange_period_counts_{{$row->id}});
                            $('.sjlist_exchange_period_{{$row->id}}>.date7.exchange_period_member_{{$row->id}}').slice((Page_exchange_period_{{$row->id}}.page - 1) * Page_exchange_period_{{$row->id}}.row, Page_exchange_period_{{$row->id}}.page * Page_exchange_period_{{$row->id}}.row).css('display', '');
                         @endforeach

                 @endif

                    let admin_counts = $('.date7.adminMember').length;
                    if (admin_counts > 10) {
                        $('.page_admin').show();
                    }
                    Page_admin.DrawPage(admin_counts);
                    $('.sjlist_admin>.date7.adminMember').slice((Page_admin.page - 1) * Page_admin.row, Page_admin.page * Page_admin.row).css('display', '');

                    let alert_counts = $('.date7.alertMember').length;
                    if (alert_counts > 10) {
                        $('.page_warned').show();
                    }
                    Page_warned.DrawPage(alert_counts);
                    $('.sjlist_alert>.date7.alertMember').slice((Page_warned.page - 1) * Page_warned.row, Page_warned.page * Page_warned.row).css('display', '');

                    let banned_counts = $('.date7.bannedMember').length;
                    if (banned_counts > 10) {
                        $('.page_banned').show();
                    }
                    Page_banned.DrawPage(banned_counts);
                    $('.sjlist_banned>.date7.bannedMember').slice((Page_banned.page - 1) * Page_banned.row, Page_banned.page * Page_banned.row).css('display', '');

                 } else if (date == 30) {
                    $('.row_data').hide();

                     @if($user->engroup==2)
                         let vvip_counts = $('.common30.vvipMember').length;
                         if (vvip_counts > 10) {
                             $('.page_vvip').show();
                         }
                         Page.DrawPage(vvip_counts);
                         $('.sjlist_vvip>.common30.vvipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                         let vip_counts = $('.common30.vipMember').length;
                         if (vip_counts > 10) {
                            $('.page_vip').show();
                         }
                         Page.DrawPage(vip_counts);
                         $('.sjlist_vip>.common30.vipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                         let novip_counts = $('.common30.novipMember').length;
                         if (novip_counts > 10) {
                            $('.page_novip').show();
                         }
                         Page_noVip.DrawPage(novip_counts);
                         $('.sjlist_novip>.common30.novipMember').slice((Page_noVip.page - 1) * Page_noVip.row, Page_noVip.page * Page_noVip.row).css('display', '');
                     @elseif($user->engroup==1)
                         @foreach($exchange_period_name as $row)
                             let exchange_period_counts_{{$row->id}} = $('.common30.exchange_period_member_{{$row->id}}').length;
                             if (exchange_period_counts_{{$row->id}} > 10) {
                                 $('.page_exchange_period_{{$row->id}}').show();
                             }
                             Page_exchange_period_{{$row->id}}.DrawPage(exchange_period_counts_{{$row->id}});
                             $('.sjlist_exchange_period_{{$row->id}}>.common30.exchange_period_member_{{$row->id}}').slice((Page_exchange_period_{{$row->id}}.page - 1) * Page_exchange_period_{{$row->id}}.row, Page_exchange_period_{{$row->id}}.page * Page_exchange_period_{{$row->id}}.row).css('display', '');
                         @endforeach
                     @endif

                    let admin_counts = $('.common30.adminMember').length;
                    if (admin_counts > 10) {
                        $('.page_admin').show();
                    }
                    Page_admin.DrawPage(admin_counts);
                    $('.sjlist_admin>.common30.adminMember').slice((Page_admin.page - 1) * Page_admin.row, Page_admin.page * Page_admin.row).css('display', '');

                    let alert_counts = $('.common30.alertMember').length;
                    if (alert_counts > 10) {
                        $('.page_warned').show();
                    }
                    Page_warned.DrawPage(alert_counts);
                    $('.sjlist_alert>.common30.alertMember').slice((Page_warned.page - 1) * Page_warned.row, Page_warned.page * Page_warned.row).css('display', '');

                    let banned_counts = $('.common30.bannedMember').length;
                    if (banned_counts > 10) {
                        $('.page_banned').show();
                    }
                    Page_banned.DrawPage(banned_counts);
                    $('.sjlist_banned>.common30.bannedMember').slice((Page_banned.page - 1) * Page_banned.row, Page_banned.page * Page_banned.row).css('display', '');
                 }else{
                     @if($user->engroup==2)
                         let vvip_counts = $('.vvipMember').length;
                         if (vvip_counts > 10) {
                             $('.page_vvip').show();
                         }
                         Page.DrawPage(vvip_counts);
                         $('.sjlist_vvip>.vvipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                         let vip_counts = $('.vipMember').length;
                         if (vip_counts > 10) {
                             $('.page_vip').show();
                         }
                         Page.DrawPage(vip_counts);
                         $('.sjlist_vip>.vipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                         let novip_counts = $('.novipMember').length;
                         if (novip_counts > 10) {
                             $('.page_novip').show();
                         }
                         Page_noVip.DrawPage(novip_counts);
                         $('.sjlist_novip>.novipMember').slice((Page_noVip.page - 1) * Page_noVip.row, Page_noVip.page * Page_noVip.row).css('display', '');

                     @elseif($user->engroup==1)
                         @foreach($exchange_period_name as $row)
                             let exchange_period_counts_{{$row->id}} = $('.exchange_period_member_{{$row->id}}').length;
                             if (exchange_period_counts_{{$row->id}} > 10) {
                                 $('.page_exchange_period_{{$row->id}}').show();
                             }
                             Page_exchange_period_{{$row->id}}.DrawPage(exchange_period_counts_{{$row->id}});
                             $('.sjlist_exchange_period_{{$row->id}}>.exchange_period_member_{{$row->id}}').slice((Page_exchange_period_{{$row->id}}.page - 1) * Page_exchange_period_{{$row->id}}.row, Page_exchange_period_{{$row->id}}.page * Page_exchange_period_{{$row->id}}.row).css('display', '');
                         @endforeach
                     @endif

                     let admin_counts = $('.adminMember').length;
                     if (admin_counts > 10) {
                         $('.page_admin').show();
                     }
                     Page_admin.DrawPage(admin_counts);
                     $('.sjlist_admin>.adminMember').slice((Page_admin.page - 1) * Page_admin.row, Page_admin.page * Page_admin.row).css('display', '');

                     let alert_counts = $('.alertMember').length;
                     if (alert_counts > 10) {
                         $('.page_warned').show();
                     }
                     Page_warned.DrawPage(alert_counts);
                     $('.sjlist_alert>.alertMember').slice((Page_warned.page - 1) * Page_warned.row, Page_warned.page * Page_warned.row).css('display', '');

                    let banned_counts = $('.bannedMember').length;
                     if (banned_counts > 10) {
                         $('.page_banned').show();
                     }
                     Page_banned.DrawPage(alert_counts);
                     $('.sjlist_banned>.bannedMember').slice((Page_banned.page - 1) * Page_banned.row, Page_banned.page * Page_banned.row).css('display', '');
                 }
                    $('.warning').hide();

                    @if($user->engroup==2)
                        $('.sjlist_vvip>.li_no_data').remove();
                        $('.sjlist_admin>.li_no_data').remove();
                        $('.sjlist_vip>.li_no_data').remove();
                        $('.sjlist_novip>.li_no_data').remove();
                        $('.sjlist_alert>.li_no_data').remove();
                        if ($('.sjlist_vvip>li:visible').length == 0 && isLoading == 0) {
                            $('#sjlist_vvip_warning').hide();
                            $('.sjlist_vvip').append(no_row_li);
                        }
                        if ($('.sjlist_admin>li:visible').length == 0 && isLoading == 0) {
                            $('#sjlist_admin_warning').hide();
                            $('.sjlist_admin').append(no_row_li);
                        }
                        if ($('.sjlist_vip>li:visible').length == 0 && isLoading == 0) {
                            $('#sjlist_vip_warning').hide();
                            $('.sjlist_vip').append(no_row_li);
                        }
                        if ($('.sjlist_novip>li:visible').length == 0 && isLoading == 0) {
                            $('#sjlist_novip_warning').hide();
                            $('.sjlist_novip').append(no_row_li);
                        }
                        if ($('.sjlist_alert>li:visible').length == 0) {
                            if(!isLoading) {
                                $('#sjlist_alert_warning').hide();
                                $('.sjlist_alert').append(no_row_li);
                            }
                        }
                    @elseif($user->engroup==1)
                        $('.sjlist_admin>.li_no_data').remove();
                        if ($('.sjlist_admin>li:visible').length == 0 && isLoading == 0) {
                            $('#sjlist_admin_warning').hide();
                            $('.sjlist_admin').append(no_row_li);
                        }
                        @foreach($exchange_period_name as $row)
                            $('.sjlist_exchange_period_{{$row->id}}>.li_no_data').remove();
                            if ($('.sjlist_exchange_period_{{$row->id}}>li:visible').length == 0 && isLoading == 0) {
                                $('#sjlist_exchange_period_warning_{{$row->id}}').hide();
                                $('.sjlist_exchange_period_{{$row->id}}').append(no_row_li);
                            }
                        @endforeach
                    @endif
                    
                    if ($('.sjlist_banned>li:visible').length == 0) {
                        if(!isLoading) {
                            $('#sjlist_banned_warning').hide();
                            $('.sjlist_banned').append(no_row_li);
                        }
                    }

                    var has_vvip_msg_count=$('.sjlist_vvip>li').not('.li_no_data').not('.d-none').length;
                    // alert('days select =>'+has_vvip_msg_count);
                    if(has_vvip_msg_count>0){
                        if(!$('.leboxVVIP').hasClass('on')){
                            $('.sjlist_vvip>.li_no_data').hide();
                            $('.leboxVVIP').toggleClass('on');
                            $('.leboxVVIP').next('dd').slideToggle("slow");
                        }
                    }
            }
        });

        function chk_delete(url) {
            c4('確定要刪除嗎?');
            $(".n_left").on('click', function () {
                $("#tab04").hide();
                c5('刪除成功');
                window.location=url;
            });
            return false;
        }

        function block(sid){
            c4('確定要封鎖嗎?');
            var sid = sid;
            $(".n_left").on('click', function() {
                url=
                $.post('{{ route('postBlockAJAX') }}', {
                    uid: '{{ $user->id }}',
                    sid: sid,
                    _token: '{{ csrf_token() }}'
                }, function (data) {
                    $("#tab04").hide();
                    c5('封鎖成功');
                    window.location.reload();
                });
            });
            return false;
        }

    function massage_user_note(sid){
        let massage_user_note_content = $('#massage_user_note_' + sid).val();
        $.post('{{ route('messageUserNoteAJAX') }}', {
            user_id: '{{ $user->id }}',
            target_id: sid,
            massage_user_note_content: massage_user_note_content,
            _token: '{{ csrf_token() }}'
        }, function (data) {
            c5('備註已更新');
        });
        return false;
    }

        function banned(sid,name){
            $("input[name='uid']").val(sid);
            $(".banned_name").append("<span>" + name + "</span>")
            $(".announce_bg").show();
            $("#show_banned_ele").show();
        }

        // $('.all_delete').on('click', function() {

        //     var IDs = [];
        //     $('.system_log dd').find('li.row_data').each(function(){ IDs.push(this.id); });
        //     // alert(IDs);
        //     // alert($('.sjlist_vip.row_data>li:visible').length);
        //     if($.trim(IDs) !== '') {
        //         c8('確定要全部刪除嗎?');
        //         deleteRowAll(IDs);
        //     }else{
        //         c5('沒有可刪除資料');
        //     }
        // });

        $('.vvip_delete').on('click', function() {

            var IDs = [];
            $(".sjlist_vvip").find("li").each(function(){ IDs.push(this.id); });
            // alert(IDs);
            // alert($('.sjlist_vip.row_data>li:visible').length);
            if($.trim(IDs) !== '') {
                c8('確定要全部刪除嗎?');
                deleteRowAll(IDs);
            }else{
                c5('沒有可刪除資料');
            }
        });

        $('.admin_delete').on('click', function() {

            var IDs = [];
            $(".sjlist_admin").find("li").each(function(){ IDs.push(this.id); });
            // alert(IDs);
            // alert($('.sjlist_vip.row_data>li:visible').length);
            if($.trim(IDs) !== '') {
                c8('確定要全部刪除嗎?');
                deleteRowAll(IDs);
            }else{
                c5('沒有可刪除資料');
            }
        });

        $('.vip_delete').on('click', function() {

            var IDs = [];
            $(".sjlist_vip").find("li").each(function(){ IDs.push(this.id); });
            // alert(IDs);
            // alert($('.sjlist_vip.row_data>li:visible').length);
            if($.trim(IDs) !== '') {
                c8('確定要全部刪除嗎?');
                deleteRowAll(IDs);
            }else{
                c5('沒有可刪除資料');
            }
        });
        $('.novip_delete').on('click', function() {
            // c4('確定要全部刪除嗎?');
            var IDs = [];
            $(".sjlist_novip").find("li").each(function(){ IDs.push(this.id); });

            // alert(IDs);
            if($.trim(IDs) !== '') {
                c8('確定要全部刪除嗎?');
                deleteRowAll(IDs);
            }else{
                c5('沒有可刪除資料');
            }
        });
        $('.alert_delete').on('click', function() {
            // c4('確定要全部刪除嗎?');
            var IDs = [];
            $(".sjlist_alert").find("li").each(function(){ IDs.push(this.id); });

            if($.trim(IDs) !== '') {
                c8('確定要全部刪除嗎?');
                deleteRowAll(IDs);
            }else{
                c5('沒有可刪除資料');
            }
        });
        
        $('.banned_delete').on('click', function() {
            // c4('確定要全部刪除嗎?');
            var IDs = [];
            $(".sjlist_banned").find("li").each(function(){ IDs.push(this.id); });

            if($.trim(IDs) !== '') {
                c8('確定要全部刪除嗎?');
                deleteRowAll(IDs);
            }else{
                c5('沒有可刪除資料');
            }
        });        

        @foreach($exchange_period_name as $row)
            $('.exchange_period_delete_{{$row->id}}').on('click', function() {
                // c4('確定要全部刪除嗎?');
                var IDs = [];
                $(".sjlist_exchange_period_{{$row->id}}").find("li").each(function(){ IDs.push(this.id); });

                // alert(IDs);
                if($.trim(IDs) !== '') {
                    c8('確定要全部刪除嗎?');
                    deleteRowAll(IDs);
                }else{
                    c5('沒有可刪除資料');
                }
            });
        @endforeach

        function deleteRowAll(IDs) {
            var del_url = '{!! url("/dashboard/chat2/deleterowall/:uid/:sid") !!}';

            var sid = '{{$user->id}}';
            del_url = del_url.replace(':uid', IDs);
            del_url = del_url.replace(':sid', sid);
            $(".n_left").on('click', function() {

                $("#tab08").hide();
                c5('刪除成功');
                window.location=del_url;
            });
            return false;

        }

        function showChatSet() {
            $(".blbg").show();
            $("#tab03").show();
        }

</script>

@stop

@section('javascript')

<style>
    .box {
        width: 100%;
    }

    .ellipsis {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .comt {
        top: 8px;
        position: relative;
    }

    .popover {
        background: #e2e8ff !important;
        color: #6783c7;
    }

    .popover.right .arrow:after {
        border-right-color: #e2e8ff;
    }

    .popover.bottom .arrow:after {
        border-bottom-color: #e2e8ff;
    }

    .online {
        background: #17bb4a;
        border: #ffffff 2px solid;
        width: 15px;
        height: 15px;
        border-radius: 100px;
        position: absolute;
        right: 0;
        bottom: 0;
        display: block;
        z-index: 5;
    }

    .nonVip {
        width: 15px;
        height: 15px;
        background: linear-gradient(to TOP, #ff9225, #ffb86e);
        border-radius: 100px;
        box-shadow: 2px 2px 0px #ff721d;
        border-radius: 100px;
        position: absolute;
        right: 0;
        bottom: 0;
        display: block;
        z-index: 5;
    }

    .nonVip img {
        max-width: 100%;
        max-height: 100%;
        height: 7px;
        margin: 0 auto;
        display: table;
        margin-top: 4px;
    }

    .shanx {
        position: relative;
        overflow: inherit !important;
    }
</style>

<script>
    var step1,step3,step4,step5,step6,step7,step8,step9,step10;

        $('.blbut').on('click', function() {
            $("#tab03").hide();
            $.post('{{ route('chatSet') }}', {
                uid: '{{ $user->id }}',
                notifmessage:$('#notifmessage').val(),
                notifhistory:$('#notifhistory').val(),
                _token: '{{ csrf_token() }}'
            }, function (data) {
                //$("#tab03").hide();
                show_message('資料更新成功');
                //window.location.reload();
            });
        });

        // $('.lebox1,.lebox2,.lebox3').toggleClass('on');

        // $('.lebox1').removeClass('off');
        // $('.lebox1').removeClass('on');

        // $('.lebox1,.lebox2,.lebox3,.lebox_alert').removeClass('off');
        // $('.lebox1,.lebox2,.lebox3,.lebox_alert').removeClass('on');

{{--        @if($user->engroup==2)--}}
{{--        $('.lebox1').toggleClass('on');--}}
{{--        $('.lebox2,.lebox3,.lebox_alert').toggleClass('off');--}}
{{--        $('.lebox2,.lebox3,.lebox_alert').next('dd').slideToggle("slow");--}}
{{--        @elseif($user->engroup==1)--}}
{{--        $('.lebox1,.lebox2,.lebox3,.lebox_alert').toggleClass('off');--}}
{{--        $('.lebox1,.lebox2,.lebox3,.lebox_alert').next('dd').slideToggle("slow");--}}
{{--        @endif--}}
        // $('.lebox1,.lebox2,.lebox3,.lebox_alert').toggleClass('off');
        // $(".leftsidebar_box dd").show();
        // $('.lebox2,.lebox3,.lebox_alert').next('dd').slideToggle("slow");

                $('.leboxVVIP,.lebox0,.lebox1,.lebox2,.lebox3,.lebox_alert,.lebox5').toggleClass('off');
                $('.leboxVVIP,.lebox0,.lebox1,.lebox2,.lebox3,.lebox_alert,.lebox5').next('dd').slideToggle("slow");

        $('.leboxVVIP,.lebox0,.lebox1,.lebox2,.lebox3,.lebox_alert,.lebox5').click(function(e) {
            if ($(this).hasClass('off')) {
                $(this).removeClass('off');
                $(this).toggleClass('on');
            }else if($(this).hasClass('on')){
                $(this).removeClass('on');
                $(this).toggleClass('off');
            }

            $(this).next('dd').slideToggle("slow");
            @if($user->engroup==2)
            $('.sjlist_vvip>.li_no_data').remove();
            $('.sjlist_vip>.li_no_data').remove();
            $('.sjlist_novip>.li_no_data').remove();

            if ($('.sjlist_vvip>li:visible').length == 0 && isLoading == 0) {
                $('#sjlist_vvip_warning').hide();
                $('.sjlist_vvip').append(no_row_li);
            }
            if ($('.sjlist_vip>li:visible').length == 0 && isLoading == 0) {
                $('#sjlist_vip_warning').hide();
                $('.sjlist_vip').append(no_row_li);
            }
            if ($('.sjlist_novip>li:visible').length == 0 && isLoading == 0) {
                $('#sjlist_novip_warning').hide();
                $('.sjlist_novip').append(no_row_li);
            }
            @elseif($user->engroup==1)
                @foreach($exchange_period_name as $row)
                $('.sjlist_exchange_period_{{$row->id}}>.li_no_data').remove();
                if ($('.sjlist_exchange_period_{{$row->id}}>li:visible').length == 0) {
                    if(!isLoading) {
                        $('#sjlist_exchange_period_warning_{{$row->id}}').hide();
                        $('.sjlist_exchange_period_{{$row->id}}').append(no_row_li);
                    }
                }
                @endforeach
            @endif

            $('.sjlist_admin>.li_no_data').remove();
            if ($('.sjlist_admin>li:visible').length == 0 && isLoading == 0) {
                $('#sjlist_admin_warning').hide();
                $('.sjlist_admin').append(no_row_li);
            }

            $('.sjlist_alert>.li_no_data').remove();
            if ($('.sjlist_alert>li:visible').length == 0) {
                console.log(isLoading);  {{-- 此行勿刪，若刪除將導致頁面產生錯誤 --}}
                if(!isLoading) {
                    $('#sjlist_alert_warning').hide();
                    $('.sjlist_alert').append(no_row_li);
                }
            }
            else{
                if($(this).hasClass('on') && $(this).hasClass('lebox_alert')){
                    c5('此為警示會員，要與此區會員交流請務必小心。');
                    @if($user->engroup==1)
                        @if($user->checkTourRead('chat',6))
                            //c5('此為警示會員，要與此區會員交流請務必小心。');
                        @endif
                        if(step6==1){
                            //c5('此為警示會員，要與此區會員交流請務必小心。');
                        }
                    @elseif($user->engroup==2)
                        @if($user->checkTourRead('chat',5))
                            //c5('此為警示會員，要與此區會員交流請務必小心。');
                        @endif
                        if(step5==1){
                            //c5('此為警示會員，要與此區會員交流請務必小心。');
                        }
                    @endif
                }
            }
            
            
           $('.sjlist_banned>.li_no_data').remove();
            if ($('.sjlist_banned>li:visible').length == 0) {
                console.log(isLoading);  {{-- 此行勿刪，若刪除將導致頁面產生錯誤 --}}
                if(!isLoading) {
                    $('#sjlist_banned_warning').hide();
                    $('.sjlist_banned').append(no_row_li);
                }
            }
            else{
                if($(this).hasClass('on') && $(this).hasClass('lebox5')){
                    c5('此為被站長封鎖的會員，如果您們已經交換聯絡方式，請多加注意。');
                }
            }            
        });
        $(document).on('DOMNodeInserted', 'img.lazy', function() {
            $(this).lazyload({
                effect: 'fadeIn'
            });
        });

        function letTourRead(page,step){
            $.post('{{ route('letTourRead') }}', {
                uid: '{{ $user->id }}',
                page: page,
                step: step,
                _token: '{{ csrf_token() }}'
            }, function (data) {

            });
        }
        @if($user->login_times >= 3)
            {{--$(function(){--}}
            {{--    --}}
            {{--    @if($user->checkTourRead('chat',1)==0)--}}
            {{--    if(step1 != 1) {--}}
                    // $('#announcement').hide();
                    // $('.announce_bg').hide();
                    // introJs().setOption('showButtons', true).start();
                    // step1=1;
                    // letTourRead('chat',1);
                // }

{{--                @endif--}}
{{--            });--}}

            $('.lebox1').click(function(){
                @if($user->checkTourRead('chat',3)==0)
                if(step3 != 1) {
                    $('#announcement').hide();
                    $('.announce_bg').hide();
                    introJs().goToStep(2).start();
                    step3 = 1;
                    letTourRead('chat',3);
                }
                @endif
            });

            $('.lebox2').click(function(){
                @if($user->checkTourRead('chat',4)==0)
                if(step4 != 1) {
                    $('#announcement').hide();
                    $('.announce_bg').hide();
                    introJs().goToStep(3).start();
                    step4 = 1;
                    letTourRead('chat',4);
                }
                @endif
            });

            $('.lebox3').click(function(){
                @if($user->checkTourRead('chat',5)==0)
                if(step5 != 1) {
                    $('#announcement').hide();
                    $('.announce_bg').hide();
                    introJs().goToStep(4).start();
                    step5 = 1;
                    letTourRead('chat',5);
                }
                @endif
            });

            @if($user->engroup==1)
                $('.lebox4').click(function(){
                    @if($user->checkTourRead('chat',6)==0)
                    if(step6 != 1) {
                        $('#announcement').hide();
                        $('.announce_bg').hide();
                        introJs().goToStep(5).start();
                        step6 = 1;
                        letTourRead('chat',6);
                    }
                    @endif
                });

                {{--function yd3() {--}}
                {{--    @if($user->checkTourRead('chat',7)==0)--}}
                {{--    if (step7 != 1) {--}}
                {{--        $('#announcement').hide();--}}
                {{--        $('.announce_bg').hide();--}}
                {{--        introJs().goToStep(6).start();--}}
                {{--        $('div[data-toggle="popover"]').popover('disable');--}}
                {{--        step7 = 1;--}}
                {{--        letTourRead('chat',7);--}}
                {{--    }--}}
                {{--    @endif--}}
                {{--}--}}

            @else
                {{--function yd3() {--}}
                {{--    @if($user->checkTourRead('chat',6)==0)--}}
                {{--    if (step6 != 1) {--}}
                {{--        $('#announcement').hide();--}}
                {{--        $('.announce_bg').hide();--}}
                {{--        introJs().goToStep(5).start();--}}
                {{--        $('div[data-toggle="popover"]').popover('disable');--}}
                {{--        step6 = 1;--}}
                {{--        letTourRead('chat',6);--}}
                {{--    }--}}
                {{--    @endif--}}
                {{--}--}}
            @endif
        @endif
        function MutilpleDelete() {
            $('#deleteMutipleMessagePopUp').show();
            $('#announce_bg').show();
        }
</script>

@stop
