<div class="blbg" id="blbg" style="display: none;"></div>
<div class="bl bl_tab" id="tab01">
  <div class="bltitle">提示</div>
  <div class="blnr bltext"></div>
  <a id="" onclick="$('.blbg').click();" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>

<div class="bl gtab" id="tab02">
<a href="" class="gxbut"></a>
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
    <div class="blnr bltext"></div>
    <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>
@if(str_contains(url()->current(), 'dashboard'))
    @php
        if(!isset($user)){
            exit();
        }
        $banned_users = \App\Models\SimpleTables\banned_users::where('member_id',$user->meta_()->user_id)->where(
            function ($query) {
                $query->whereNull('expire_date')->orWhere('expire_date', '>=', \Carbon\Carbon::now());
            })
        ->get();
    @endphp
    @if(count($banned_users) > 0)
        @php
            $diff_in_days = '';
            $banned_user = $banned_users->first();
            if(isset($banned_user->expire_date)){
                $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $banned_user->expire_date);
                $now = \Carbon\Carbon::now();

                $diff_in_days = ' ' . $to->diffInDays($now) . ' 天';
            }
            $reason = $banned_user->reason == '自動封鎖' ? '系統原因' : $banned_user->reason;
        @endphp
        <div class="blbg banned_bg" style="display:block"></div>
        <div class="gg_tab" id="tab_banned_alert" style="display: block;">
            <div class="ggtitle">封鎖提示</div>
            <div class="ggnr01 ">
                <div class="gg_nr">您因為 {{ $reason }} 被站長封鎖{{ $diff_in_days }}，如有問題請點右下聯絡我們加站長 line 反應。</div>
                <div class="gg_bg"><a class="gg_page"></a><a class="ggbut" onclick="gmBtn1_banned()">確定</a><a class="gg_pager"></a></div>
            </div>
            <a id="" onclick="gmBtn1_banned()" class="bl_gb"><img src="/new/images/gb_icon01.png"></a>
        </div>
        <script>
            $(".bl_tab").hide();
            $(".announce_bg").hide();
            function banned_alert() {
                $(".announce_bg").hide();
                $(".banned_bg").show();
                $("#tab_banned_alert").show();
            }
            function gmBtn1_banned(){
                $(".banned_bg").hide();
                $(".gg_tab").hide();
{{--                {{Auth::logout()}}--}}
                window.location = "/logout";
            }
            $(document).on('click','.banned_bg',function(event) {
                $(".banned_bg").hide();
                $(".announce_bg").hide();
                $(".gg_tab").hide();
{{--                {{Auth::logout()}}--}}
{{--                window.location = "/";--}}
            });
        </script>
    @endif
@endif