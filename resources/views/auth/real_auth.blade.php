@extends('new.layouts.website')
@section('style')
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<link rel="stylesheet" href="/new/css/iconfont.css">
<style>
.gaor_nr01 .system .ag_ttile {background:url(../../alert/images/z_dk.png) no-repeat right, linear-gradient(90deg,#ffb5be,#fff8f9) !important;}
.real_auth_bg{width:100%; height:100%;width: 100%;height: 100%;position: fixed;top: 0px;left: 0;background: rgba(0,0,0,0.5);z-index: 9;display:none;}
.bltext-real_auth { text-align:center; word-break:break-word;}
.bltext-real_auth ul,.bltext-real_auth li {text-align:left;list-style: disc;list-style-type: disc;}
.bltext-real_auth ul {margin-left:7%;margin-top:1em;margin-bottom:1em;}


</style>
@stop
@section('app-content')
    <style>
    a.ga_3_passed:hover,a.ga_3_passed:visited,a.ga_3_passed:focus  {
        text-decoration:none !important;
        color: #333 !important;
        background: linear-gradient(to top,#bababa,#f8f8f8) !important;
        box-shadow: 0 5px 10px rgb(123 123 123 / 30%) !important;
        cursor:default;
    }
    </style>
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="shou"><span>本人認證/美顏推薦/名人認證</span>
                    <a href="{{$service->getReturnBackUrlInRealAuthPage()}}" class="toug_back btn_img" style=" position: absolute; right:20px;">
                        <div class="btn_back"></div>
                    </a>
                </div>
                <div class="renz">
                本認證主要讓站方協助認證本人資訊，可以讓站方幫助你更好的尋找優質 daddy，擺脫放鳥/騙炮/沒意義的邀約等等不愉快的經驗。
                </div>
                
                
                <div class="gaoji_rz">
                    <img src="{{asset('alert/images/renz_24.png')}}" class="gao_bitaoti">
                    <div class="gao_font">本人認證是由站方對女會員進行本人的驗證，可以大大增加 daddy 對約見的信任度。</div>
                    <div class="gaor_nr01">
                             <dl class="system">
                                 <dt class="ag_ttile">※ 申請條件</dt>
                                 <dd style="display: none;">
                                     <div class="gaor_nr01_li"><img src="{{asset('alert/images/renz_12.png')}}"><font>申請條件無限制</font></div>
                                </dd>
                            </dl>
                    </div>
                    
                    
                    <div class="gaor_nr01">
                             <dl class="system">
                                 <dt class="ag_ttile">※ 通過這個認證我有什麼好處? </dt>
                                 <dd style="display: none;" class="matop_5 mabot_5">
                                     <div class="gaor_nr01_li"><img src="{{asset('alert/images/renz_12.png')}}"><font class="gaor_font">本人認證通過後，會獲得官方認證的藍勾勾
                                            <span style="color: #20b0c9; font-weight: bold; font-size: 16px;">「 <img src="{{asset('new/images/zz_zss.png')}}" style="border-radius: 100px; box-shadow:1px 2px 10px rgba(77,152,252,1); height:20px;"> 」</span>
                                            通常daddy會更加信任你。</font>
                                    </div>
                                     <div class="gaor_nr01_li"><img src="{{asset('alert/images/renz_13.png')}}"><font class="gaor_font">投訴立即處理。</font>
                                     <div class="ga_rtable">任何投訴都會在36小時內得到處理。</div>
                                     </div>
                                     <div class="gaor_nr01_li"><img src="{{asset('alert/images/renz_14.png')}}"><font class="gaor_font">初步的法務支援。</font>
                                     <div class="ga_rtable">站方有法務律師會免費提供初步的法律諮詢與建議。</div>
                                     </div>
                                </dd>
                            </dl>
                    </div>
                
                    <div class="ga_button">
                        @if($service->isPassedByAuthTypeId(1))
                        <a href="javascript:void(0)" class="ga_3 ga_3_passed">已完成本人認證</a>    
                        @elseif($service->isSelfAuthWaitingCheck())
                        <a href="{{url('user_video_chat_verify')}}" class="ga_1">等待審核中 - 重錄視訊</a>    
                        @elseif($service->isSelfAuthApplyNotVideoYet())
                        <a href="{{url('user_video_chat_verify')}}" class="ga_1">前往視訊頁面</a>
                        @else
                        <a href="javascript:void(0)" class="ga_1" onclick="real_auth_popup(1);">立即進行本人認證</a>
                        <a href="{{url('/dashboard/personalPage')}}" class="ga_2">放棄</a>
                        @endif
                    </div>
                    
                    
                
                </div>
                <div class="gaoji_rz">
                    <img src="{{asset('alert/images/renz_23.png')}}" class="gao_bitaoti">
                    <div class="gao_font">這是專門為符合條件，尋找高素質高收入daddy的妳所安排，請先確認妳符合以下條件：</div>
                    
                    <div class="gaor_nr01">
                             <dl class="system">
                                 <dt class="ag_ttile">※ 申請條件</dt>
                                 <dd style="display: none;">
                                     <div class="gaor_nr01_li"><img src="{{asset('alert/images/renz_12.png')}}"><font>未曾從事過特種行業，全職兼職皆不可</font></div>
                                     <div class="gaor_nr01_li"><img src="{{asset('alert/images/renz_13.png')}}"><font>BMI低於24</font></div>
                                     <div class="gaor_nr01_li"><img src="{{asset('alert/images/renz_14.png')}}"><font>是單身或者非同居狀態且男朋友無法看你的手機(加分項非必須)</font></div>
                                     <div class="gaor_nr01_li"><img src="{{asset('alert/images/renz_15.png')}}"><font>line 經常保持暢通(加分項非必須)</font></div>
                                </dd>
                            </dl>
                        </div>
                    
                    <div class="gaor_nr01">
                             <dl class="system">
                                 <dt class="ag_ttile">※ 通過這個認證我有什麼好處? </dt>
                                 <dd style="display: none;" class="matop_5 mabot_5">
                                     <!-- <div class="gaor_nr01_li"><img src="{{asset('alert/images/renz_12.png')}}"><font class="gaor_font">美顏推薦的標籤
                                            <span style="color: #20b0c9; font-weight: bold; font-size: 16px;">「<span class="ewnz"><img src="{{asset('alert/images/w3.png')}}">美顏推薦</span> 」</span>
                                            </font>
                                    </div> -->
                                     <div class="gaor_nr01_li">
                                         <img src="{{asset('alert/images/renz_12.png')}}"><font class="gaor_font">詐騙保險</font>
                                         <div class="ga_rtable">只要你跟本站vvip daddy約見。約見過程中如果遭受不公平的待遇，站方會補償你5000~15000的金額。視情節輕重而定。</div>
                                     </div>
                                     <div class="gaor_nr01_li"><img src="{{asset('alert/images/renz_13.png')}}"><font class="gaor_font">專有客服通道，可諮詢任何問題。必要時由站長親自回覆。</font></div>
                                     <div class="gaor_nr01_li">
                                            <img src="{{asset('alert/images/renz_14.png')}}"><font class="gaor_font">投訴立即處理。</font>
                                            <div class="ga_rtable">任何投訴都會在36小時內得到處理。</div>
                                     </div>
                                     <div class="gaor_nr01_li">
                                            <img src="{{asset('alert/images/renz_15.png')}}"><font class="gaor_font">完整的法務支援</font>
                                            <div class="ga_rtable">站方有法務律師會免費全程提供你任何需要的法律諮詢。</div>
                                     </div>
                                    
                                </dd>
                            </dl>
                    </div>
                    <div class="ga_button">
                    @if($service->isPassedByAuthTypeId(2))
                        <a href="{{route('beauty_auth')}}" class="ga_1">已通過認證 - 編修認證表</a>
                    @elseif($service->isBeautyAuthWaitingCheck())
                        <a href="{{route('beauty_auth')}}" class="ga_1">認證表審核中 - 編修認證表</a>                     
                    @elseif($service->isSelfAuthApplyNotVideoYet())
                        <a href="{{url('user_video_chat_verify')}}" class="ga_1">前往視訊頁面</a>
                    @elseif($service->isAllowUseBeautyAuthForm())
                        <a href="{{route('beauty_auth')}}" class="ga_1">填寫認證表</a>
                    @else                        
                        <a class="ga_1" onclick="real_auth_popup(2);return false;">我符合，申請美顏推薦</a>
                        <a href="{{url('/dashboard/personalPage')}}" class="ga_2">放棄</a>
                    @endif                    
                    </div>
                </div>
                <div class="gaoji_rz ga_top40 ga_bot70">
                        <img src="{{asset('new/images/mrrenz_25.png')}}" class="gao_bitaoti ga_top">
                        <div class="gao_font">如果你是特殊人物/具有公開身分。想尋找優質daddy不想曝光者。</div>
                        
                        
                        <div class="gaor_nr01">
                                 <dl class="system">
                                     <dt class="ag_ttile">※ 申請條件</dt>
                                     <dd style="display: none;">
                                          <div class="gaor_nr01_li"><img src="{{asset('alert/images/renz_12.png')}}"><font>FB/IG 超過 5000 人追蹤</font></div>
                                          <div class="gaor_nr01_li"><img src="{{asset('alert/images/renz_13.png')}}"><font>曾參與超過三場以上走秀/演出</font></div>
                                          <div class="gaor_nr01_li"><img src="{{asset('alert/images/renz_14.png')}}"><font>公眾人物</font></div>
                                          <div class="gaor_nr01_li"><img src="{{asset('alert/images/renz_15.png')}}"><font>公認校花/系花</font></div>
                                          <div class="gaor_nr01_li"><img src="{{asset('alert/images/renz_16.png')}}"><font>其他特殊條件</font></div>
                                    </dd>
                                </dl>
                            </div>
                        
                        
                        <div class="gaor_nr01">
                                 <dl class="system">
                                     <dt class="ag_ttile">※ 通過這個認證我有什麼好處? </dt>
                                     <dd style="display: none;" class="matop_5 mabot_5">
                                         <!-- <div class="gaor_nr01_li">
                                                <img src="{{asset('alert/images/renz_12.png')}}">
                                                <font class="gaor_font">獲得名人認證的標籤
                                                <span style="color: #20b0c9; font-weight: bold; font-size: 16px;">「<span class="ewnz"><img src="{{asset('alert/images/w2.png')}}">名人認證</span> 」</span>
                                                </font>
                                        </div> -->
                                         <div class="gaor_nr01_li">
                                             <img src="{{asset('alert/images/renz_12.png')}}">
                                             <font class="gaor_font">本站有北中南共計百位以上身價破億的 vvip。配合妳的需求由站長親自主持妳的資料推薦方式。讓你可以兼顧隱私與效率中，找到最優值的高階 daddy。
                                             </font>
                                         </div>
                                         <div class="gaor_nr01_li">
                                             <img src="{{asset('alert/images/renz_13.png')}}">
                                            <font class="gaor_font">其他美顏推薦的所有功能。</font>
                                        </div>
                                        
                                        
                                    </dd>
                                </dl>
                        </div>                       
                        
                        
                        <div class="ga_button">
                        @if($service->isPassedByAuthTypeId(3))
                            <a href="{{route('famous_auth')}}" class="ga_1">已通過認證 - 編修認證表</a>
                        @elseif($service->isFamousAuthWaitingCheck())
                            <a href="{{route('famous_auth')}}" class="ga_1">審核中 - 編修認證表</a>                     
                        @else                                
                        <a  class="ga_1" onclick="location.href='{{route('famous_auth')}}';return false;" >我符合，進行名人認證</a>
                        <a href="{{url('/dashboard/personalPage')}}" class="ga_2">放棄</a>                            
                        @endif    
                        </div>
                    </div>
            </div>

        </div>
    </div>
    
    <div class="real_auth_bg" onclick="gmBtnNoReload()" style="display:none;"></div>
    <div class="bl bl_tab" id="real_auth_1_tab" style="display: none;">
        <div class="bltitle">提示</div>
        <div class="n_blnr01 matop10">
            <div class="blnr bltext-real_auth">
                您好：本人驗證會分三步驟
                <ul>
                    <li>第一步請您先確認基本資料/照片符合您的現況</li>
                    <li>第二步請您做進階驗證</li>
                    <li>第三步會做站方視訊驗證約兩分鐘</li>
                </ul>
                整體大概會占用您 10~15分鐘的時間。
            </div>
            <div class="n_bbutton">
                <span><a class="n_left" href="javascript:void(0)" onclick="goto_real_auth_forward(1)" >確定</a></span>
                <span><a onclick="real_auth_tab_close(this)" class="n_right" href="javascript:void(0)">取消</a></span>
            </div>  
        </div>
        <a onclick="real_auth_tab_close(this);" class="bl_gb"><img src="{{asset('/new/images/gb_icon.png')}}"></a>
    </div>
    <div class="bl bl_tab" id="real_auth_2_tab" style="display: none;">
        <div class="bltitle">提示</div>
        <div class="n_blnr01 matop10">
            <div class="blnr bltext-real_auth">
                您好：美顏推薦會分三步驟
                <ul>
                    <li>第一步請您先確認基本資料/照片符合您的現況</li>
                    <li>第二步請您做進階驗證</li>
                    <li>第三步會做站方視訊約三分鐘的驗證</li>
                </ul>
                整體大概會占用您 10~15分鐘的時間。
                如果美顏推薦沒通過則獲得本人認證的標籤

            </div>
            <div class="n_bbutton">
                <span><a class="n_left" href="javascript:void(0)" onclick="goto_real_auth_forward(2)" >確定</a></span>
                <span><a onclick="real_auth_tab_close(this)" class="n_right" href="javascript:void(0)">取消</a></span>
            </div>  
        </div>
        <a onclick="real_auth_tab_close(this);" class="bl_gb"><img src="{{asset('/new/images/gb_icon.png')}}"></a>
    </div>    
    <form id="real_auth_forward_form" method="post"  style="display:none;">{!! csrf_field() !!}<div></div></form>
@stop

@section('javascript')
<script type="text/javascript">
			$(function() {
				$(".gaor_tit dd").hide();
				$(".gaor_tit dt").click(function() {
		
				});
			})
		
			$('.system dt').click(function(e) {
				$(this).toggleClass('on');
				$(this).next('dd').slideToggle();
			});
</script>



<script type="text/javascript">
			$(function() {
				$(".gaor_tit dd").hide();
				$(".gaor_tit dt").click(function() {
		
				});
			})
		
			$('.system_log dt').click(function(e) {
				$(this).toggleClass('on');
				$(this).next('dd').slideToggle();
			});
</script>


<script>
   
	function cl() {
		 $(".blbg").show()
         $("#tab01").show()
    }
    function gmBtn1(){
        $(".blbg").hide()
        $(".bl_tab").hide()	
			
    }
    
</script>
<script>
    function real_auth_popup(auth_type) {
        $('#real_auth_'+auth_type+'_tab').show();
        $(".real_auth_bg").show();
    }

    function real_auth_tab_close(dom) {
        $(dom).closest('.bl_tab').hide();

        $(".real_auth_bg").hide();
    }
    
    function goto_real_auth_forward(auth_type) {
        $('#real_auth_forward_form')
        .attr('action','{{route('real_auth_forward')}}')
        .children('div').eq(0)
        .html('<input type="hidden" name="real_auth" value="'+auth_type+'">');
        $('#real_auth_forward_form').submit();
    };
</script>
@stop