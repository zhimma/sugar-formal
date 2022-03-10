<style>
    /* always show scrollbars */
    .new_poptk::-webkit-scrollbar {
        -webkit-appearance: none;
        width: 5px;
    }

    .new_poptk::-webkit-scrollbar-thumb {
        border-radius: 5px;
        background-color: #8a9fef;
        box-shadow: 0 0 1px rgba(255, 255, 255, .5);
    }
</style>

<div class="blbg" id="blbg" style="display: none;"></div>
<div class="bl bl_tab" id="tab01">
  <div class="bltitle">提示</div>
  <div class="blnr bltext"></div>
  <a id="" onclick="$('.blbg').click();" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>

<div class="bl gtab" id="tab02">
<a href="" class="gxbut"></a>
</div>

<div class="bl gtab" id="tab_loading">
{{--    <a href="" class="gxbut"></a>--}}
{{--    <div class="loading"></div>--}}
    <div class="loading"><span class="loading_text">loading</span></div>
</div>

<div class="bl bla gtab" id="tab_message">
    <a href="" class="gxbut"></a>
</div>

<div class="bl bl_tab" id="tab_other">
    <div class="bltitle">提示</div>
    <div class="n_blnr01">
        <div class="blnr bltext"></div>
        <div class="n_bbutton">
            <a class="n_bllbut n_bllbut_tab_other" href="javascript:">確認</a>
{{--            <span><a onclick="$('.blbg').click();" class="n_right" href="javascript:">返回</a></span>--}}
        </div>
    </div>
    <a id="" onclick="$('.blbg').click();" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>

<div class="bl bl_tab" id="tab04">
    <div class="bltitle">提示</div>
    <div class="n_blnr01">
        <div class="blnr bltext"></div>
        <div class="n_bbutton">
	        <span><a class="n_left" href="javascript:">確認</a></span>
	        <span><a onclick="$('.blbg').click();" class="n_right" href="javascript:">返回</a></span>
	    </div>
    </div>
    <a id="" onclick="$('.blbg').click();" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>

{{-- 20201019 新增公版 confirm 提示框--}}
<div class="bl bl_tab " id="common_confirm">
    <div class="bltitle"><font>提示</font></div>
    <div class="new_poptk">
        <p style="-webkit-user-modify: read-only;outline: none;white-space: pre-line; padding: 0 5%"></p>
        <div class="n_bbutton">
            <span><a class="n_left">確定</a></span>
            <span><a class="n_right" onclick="$('.blbg').click();">取消</a></span>
        </div>
    </div>
    <a id="" onclick="$('.blbg').click();" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>

<div class="bl bl_tab" id="tab06">	
    <div class="bltitle">提示</div>	
    <div class="n_blnr01">	
        <div class="blnr bltext"></div>	
        <div class="remove_callback"></div>	
        <div class="n_bbutton">	
	        <span><a class="n_left" onclick="$.fn.fileuploader.defaults.dialogs.remove_pic(true)" style="cursor:pointer">送出</a></span>	
	        <span><a class="n_right" onclick="$('#tab06').hide();$('.blbg').hide()" style="cursor:pointer">返回</a></span>	
	    </div>	
    </div>	
    <a id="" onclick="$('.blbg').click();" class="bl_gb"><img src="/new/images/gb_icon.png"></a>	
</div>

<div class="bl bl_tab" id="tab07">
    <div class="bltitle">提示</div>
    <div class="n_blnr01 matop10">
        <div class="blnr bltext"></div>
        <div class="linktext"></div>
    </div>
    <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>

<div class="bl bl_tab" id="onlyForVipPleaseUpgrade">
    <div class="bltitle">提示</div>
    <div class="n_blnr01 matop10">
        <div class="blnr bltext">
            @if(isset($user) && $user->engroup==2)
                此功能目前僅開放給VIP使用，<a href="/dashboard_img"><span style="color: red;">請上傳一張大頭照+三張生活照</span></a>，<a href="/dashboard/vipSelect"><span style="color: red;">或點此升級VIP</span></a>
            @else
                此功能目前僅開放VIP使用，<a href="/dashboard/vipSelect"><span style="color: red;">請點此升級</span></a>
            @endif
        </div>
        <div class="linktext"></div>
    </div>
    <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>

<div class="bl bl_tab" id="line_notify_set_failure">
    <div class="bltitle">提示</div>
    <div class="n_blnr01 matop10">
        <div class="blnr bltext">iPhone 的 Safari 在 Line 的綁定容易出問題。如果您在綁定過程中失敗，請改用 Google Chrome 嘗試看看。</div>
        <a class="n_bllbut matop30" onclick="gmBtnNoReload()" style="padding: 0 38px;">我知道了，繼續綁定Line</a>
        <hr size="8px" align="center" width="100%" style="border-top: 2px solid #8a9ff0;">
        <div class="blnr bltext">若有其他問題，<a href="https://lin.ee/rLqcCns" target="_blank">請點此&nbsp;<img src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png" alt="加入好友" height="36" border="0" style="height: 26px; float: unset;"></a>&nbsp;或點右下聯絡我們加站長line。</div>
    </div>
    <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>

<div class="bl bl_tab" id="tab_block">
    <div class="bltitle"><span>是否要封鎖他</span></div>
    <div class="n_blnr01 matop20" style="padding-top: 20px !important; margin-top: 0px !important;">
        <!-- <div class="n_fengs"><img src="/new/images/iconff_03.png"><span>對方不會知道您封鎖他 </span></div>
        <div class="n_fengs"><img src="/new/images/iconff_06.png"><span>會將對方顯示為退會的用戶</span></div>        <div class="n_fengs"><img src="/new/images/iconff_08.png"><span>可從設定頁面的[已封鎖用戶名單]中解除</span></div> -->
        <div class="n_fengs" style="padding-right: 0px !important; display: inline-grid; margin: 0 auto; width: 100%;">
        @if(isset($blockadepopup))
            {!! $blockadepopup !!}
        @endif
        </div>
        <a class="n_bllbut matop30 but_block">封鎖</a>
    </div>
    <a id="" onclick="$('.blbg').click();" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>

<div class="announce_bg" id="announce_bg" onclick="gmBtnNoReload()" style="display:none;"></div>
<div class="bl bl_tab" id="tab05">
    <div class="bltitle">提示</div>
    <div class="n_blnr01 matop10">
    <div class="blnr bltext"></div>
    <a class="n_bllbut matop30" onclick="gmBtnNoReload()">確定</a>
    </div>
    <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>

<div class="bl bl_tab" id="tabPopM">
    <div class="bltitle">提示</div>
    <div class="n_blnr01 matop10">
    <div class="blnr bltext"></div>
    <a class="n_bllbut matop30" href="javascript:location.reload()">確定</a>
    </div>
    <a id="" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>

<div class="bl bl_tab" id="tab08">
    <div class="bltitle">提示</div>
    <div class="n_blnr01">
        <div class="blnr bltext"></div>
        <div class="n_bbutton">
            <span><a class="n_left" href="javascript:" >確認</a></span>
            <span><a onclick="gmBtnNoReload()" class="n_right" href="javascript:">返回</a></span>
        </div>
    </div>
    <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>

<div class="bl bl_tab" id="popSus">
    <div class="bltitle">重要聲明</div>
    <div class="n_blnr01">
        <div class="blnr bltext" style="text-align: left;color: #666666;">
            <p style="font-size: 14px;">1. 本區資料皆由會員自主上傳，採用與否由會員自行決定。資料僅提供給VIP會員參考。</p>
            <p style="font-size: 14px;">2. 本站不對資料正確性做任何保證，也不介入資料的蒐集與驗證。</p>
            <p style="font-size: 14px;">3. 本區資料屬機密資料，請勿外流。隨意外流將面臨法律責任！</p>
        </div>
        <div class="n_bbutton">
            <span><a class="n_left" onclick="$('#popSus').hide();$('.blbg').hide();window.location.replace('?s=false');" >同意</a></span>
            <span><a onclick="window.history.back();" class="n_right">不同意</a></span>
        </div>
    </div>
</div>

<div class="bl bl_tab" id="popEvaluation">
    <div class="bltitle">提示</div>
    <div class="n_blnr01">
        <div class="blnr bltext" style="text-align: left;color: #666666;">
            <div class="n_fstext" style="font-size: 14px;">1. 如對評價內容有疑義，請於一周內連絡站長仲裁。</div>
            <div class="n_fstext" style="font-size: 14px;">2. 針對負評相關的證據，例如line對話紀錄或者截圖請保留兩周，以便站方查核。</div>
        </div>
        <a class="n_bllbut matop30" onclick="gmBtnNoReload()">確定</a>
    </div>
    <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>

<div class="bl bl_tab" id="popSusNew">
    <div class="bltitle">新增銀行帳號</div>
    <div class="n_blnr01">
        <input type="text" name="n_input" class="blinput" style="padding:0px 5px;" placeholder="請輸入銀行帳號"/>
        <div class="n_bbutton">
            <span><a class="n_left" href="javascript:" >確定</a></span>
            <span><a onclick="$('#popSusNew').hide();$('.blbg').hide()" class="n_right" href="javascript:">取消</a></span>
        </div>
    </div>
    <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>

<div class="announce_bg" id="announce_bg" onclick="gmBtnNoReload()" style="display:none;"></div>
<div class="bl bl_tab tab_postsForumAlert" id="tab_postsForumAlert">
    <div class="bltitle">討論區重要規定</div>
    <div class="n_blnr01 matop10">
        <div class="n_fengs">
            <p>1: 禁止在討論區中留下任何個人聯絡資訊，若有違規永久禁止使用討論區!</p>
            <p>2: 如果想要交朋友，請在討論區發起版聚交流，版聚時的會員私下互動皆與站方無關</p>
            <p>3: 其他規定請參考<a href="/dashboard/post_detail/69"><font color="red">官方討論區</font></a></p>
        </div>
        <a class="n_bllbut matop30">進入討論區</a>
    </div>
    <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>

@php
    $anonymous_chat_announcement = \App\Models\AdminCommonText::where('category_alias', 'anonymous_chat')->where('alias', 'announcement')->first();
    if($anonymous_chat_announcement) {
        $anonymous_chat_announcement = $anonymous_chat_announcement->content;
    }else{
        $anonymous_chat_announcement = '';
    }
@endphp
<div class="bl bl_tab tab_anonymousChatAlert" id="tab_anonymousChatAlert">
    <div class="bltitle">聊天室重要規定</div>
    <div class="n_blnr01 matop10">
        <div class="n_fengs">{!! isset($anonymous_chat_announcement)?$anonymous_chat_announcement:'' !!}
        </div>
        <a class="n_bllbut matop30">進入聊天室</a>
    </div>
    <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>


{{--@if(str_contains(url()->current(), 'dashboard') && Session::has('banned_reason'))--}}
{{--    <div class="blbg banned_bg" style="display:block"></div>--}}
{{--    <div class="gg_tab" id="tab_banned_alert" style="display: block; z-index: 999;">--}}
{{--        <div class="ggtitle">封鎖提示</div>--}}
{{--        <div class="ggnr01 ">--}}
{{--            <div class="gg_nr">您因為 {{ Session::get('banned_reason') }} 被站長封鎖{{ Session::get('expire_diff_in_days') }}，如有問題請點右下聯絡我們加站長 line 反應。</div>--}}
{{--            <div class="gg_bg"><a class="gg_page"></a><a class="ggbut" onclick="gmBtn1_banned()">確定</a><a class="gg_pager"></a></div>--}}
{{--        </div>--}}
{{--        <a id="" onclick="gmBtn1_banned()" class="bl_gb"><img src="/new/images/gb_icon01.png"></a>--}}
{{--    </div>--}}
{{--    <script>--}}
{{--        $(".bl_tab").hide();--}}
{{--        $(".announce_bg").hide();--}}
{{--        function banned_alert() {--}}
{{--            $(".announce_bg").hide();--}}
{{--            $(".banned_bg").show();--}}
{{--            $("#tab_banned_alert").show();--}}
{{--        }--}}
{{--        function gmBtn1_banned(){--}}
{{--            $(".banned_bg").hide();--}}
{{--            $(".gg_tab").hide();--}}
{{--                {{Auth::logout()}}--}}
{{--             window.location = "/logout";--}}
{{--        }--}}
{{--        $(document).on('click','.banned_bg',function(event) {--}}
{{--            $(".banned_bg").hide();--}}
{{--            $(".announce_bg").hide();--}}
{{--            $(".gg_tab").hide();--}}
{{--                {{Auth::logout()}}--}}
{{--            window.location = "/logout";--}}
{{--        });--}}
{{--    </script>--}}
{{--@endif--}}
<style>
    .linkcolor{color: pink;}
    .linkcolor:hover{color: white;}
    .n_fstext {
        width: 90%;
        margin: 0 auto;
        display: table;
        line-height: 25px;
    }
</style>