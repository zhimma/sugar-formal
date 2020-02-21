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
    <div class="n_blnr01 matop20">
        <!-- <div class="n_fengs"><img src="/new/images/iconff_03.png"><span>對方不會知道您封鎖他 </span></div>
        <div class="n_fengs"><img src="/new/images/iconff_06.png"><span>會將對方顯示為退會的用戶</span></div>        <div class="n_fengs"><img src="/new/images/iconff_08.png"><span>可從設定頁面的[已封鎖用戶名單]中解除</span></div> -->
        <div class="n_fengs">
        @if(isset($blockadepopup))
            {!! $blockadepopup !!}
        @endif
        </div>
        <a class="n_bllbut matop30 but_block" href="">封鎖</a>
    </div>
    <a id="" onclick="$('.blbg').click();" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>

<div class="announce_bg" id="announce_bg" onclick="gmBtnNoReload()" style="display:none;"></div>
<div class="bl bl_tab" id="tab05">
    <div class="bltitle">提示</div>
    <div class="blnr bltext"></div>
    <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>
