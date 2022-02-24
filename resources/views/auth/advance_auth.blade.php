<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="format-detection" content="telephone=no" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>進階驗證</title>
		<!-- Bootstrap -->
		<link href="/auth/css/bootstrap.min.css" rel="stylesheet">
		<link href="/auth/css/bootstrap-theme.min.css" rel="stylesheet">
		<!-- owl-carousel-->
		<!--    css-->
		<link rel="stylesheet" href="/auth/css/style.css">
		<link rel="stylesheet" href="/auth/css/button_new.css">
		<link rel="stylesheet" href="/auth/css/swiper.min.css">
		<script src="/auth/js/jquery-2.1.1.min.js" type="text/javascript"></script>
        <script src="/auth/js/bootstrap.min.js"></script>
		<script src="/auth/js/main.js" type="text/javascript"></script>
        <script src="/new/js/birthday.js" type="text/javascript"></script>
        <script>           
            $(function(){
                $.ms_DatePicker();            
            });              
        </script>
        <style>
            .select_xx04,.se_zlman {width:initial !important;}
            .se_zlman {margin-left:3%;margin-right:3%;}
            .birthday_selector em {margin-right:3%;}
            select.select_xx04 {border:none;background:transparent;}
            @media (max-width: 470px) {
                .select_xx04{
                    font-size:12px !important;
                    text-align-last: center;
                    text-align: center;
                    -ms-text-align-last: center;
                    -moz-text-align-last: center;                    
                }
                .se_zlman {margin-left:0 !important;margin-right:0 !important;}
                .birthday_selector em {margin-right:0 !important;}                 
            } 
            
            @media (max-width: 370px) {
                .birthday_selector em {
                    padding-right:10px !important;
                    margin-left:10px !important;
                }
                
                .birthday_selector select.xy_input {
                    margin-left:0 !important;
                }
                
                .birthday_selector select.select_xx04 {
                    padding:0 !important;
                }
            }
            .bolder {
                font-weight:bolder;
            } 
            .red {
                color:red;
            } 

            #tab_confirm.left .bltext,#tab_general_confirm  .bltext {
                text-align: left;
            }
            
            #tab_confirm .bltext .bolder.red ol,#tab_confirm .bltext .bolder.red ol li ,.new_wyz .bolder.red ol,.new_wyz .bolder.red ol li {list-style: inside decimal;}
            
            #tab_confirm .bltext .bolder.red ol li {text-align:left;}
            .margin_top_one_line {margin-top:1em;}
            .xy_input {border-radius:5px;}
            .xy_input.only_show {width:60% !important;color:#666666;}
            .center {text-align:center;}
            img.adv_auth_icon {margin:0;}
            div.blnr {padding:0;}
            @media (hover: none) {
                .n_left:hover {
                    box-shadow:none !important;
                }            
            }
            .n_left:active {
                box-shadow:inset 0px 15px 10px -10px #4c6ded, inset 0px -10px 10px -20px #4c6ded !important;            
            }
            #tab01 a .obvious {color:red;float:none;}
            input.only_show {color:#777;}
            div.has_error input,div.has_error select.select_xx04 {border:2px red solid;background:#FFECEC !important;}               
            #tab_confirm div.n_blnr01  div.blnr {display:initial;}
            #tab_confirm div.n_blnr01 div.blnr a {overflow-wrap:break-word;}
            @media (max-height: 470px) {
                #tab_confirm {
                    top: 3% !important;
                } 
                
                #tab_confirm .n_blnr01 {
                    padding-top:10px !important;
                }
            }  
            
            .i_am_student {margin-top:5%;}
            .i_am_student a:active,.i_am_student a:visited,.i_am_student a:focus {text-decoration:none;}
            .i_am_student .remind-regular {color:blue;font-weight:bolder;font-size:16px;width:initial;float:initial}
        </style>   	
        </head>

	<body style="background:#ffffff">
        @include('new.layouts.navigation')
		<!---->
		<div class="container matop70 nn_yzheight">
			<div class="row">
				<div class="col-sm-2 col-xs-2 col-md-2 dinone">
                    @include('new.dashboard.panel')
				</div>
				<div class="col-sm-12 col-xs-12 col-md-10">
					<div class="dengl matbot140 zh_top">
						<div class="zhuce">
							<h2>進階驗證</h2>
						</div>

                        @if(!$user->isAdvanceAuth() && !$init_check_msg??null)
						<div class="vipbongn new_wyz">
							<h2>驗證說明</h2>
							<h3>
                            
                            @if($is_edu_mode??null)
                            <div>還不能辦個人門號的學生可以透過校內 email 方式通過驗證
                            ，請輸入您的學校 email。
                            </div>
                            
                            @else
                            <div>您好，這是花園網的進階認證，將驗證您的以下資料，必須全部正確才能通過驗證。</div>
                            <div class="bolder red">
                                <ol>
                                <li>輸入資料必須符合該門號的登記資料,否則驗證會失敗</li>
                                <li>預付卡無法驗證</li>
                                <li>身分證字號則只用在本次驗證後刪除，本站不會留存</li>
                                </ol>
                            </div>
                            <div class="i_am_student"><a href="{{url('goto_advance_auth_email')}}">我是學生未滿20歲，沒有辦個人門號，<span class="remind-regular">請點我</span></a></div>
                            @endif
                            </h3>
						</div>
                        @endif
						<div class="de_input">
							
							@if($user->isAdvanceAuth())

								<div class="center">已完成驗證，<a href="{!! url('dashboard') !!}" class="red">按此開始使用網站</a></div>
                            @elseif($init_check_msg)
                                <div class="center">{!!$init_check_msg!!}</div>
							@else
                                @if($is_edu_mode??null)
                                    @include('auth.advance_auth_form_part-email')
                                @else
                                    @include('auth.advance_auth_form_part-pid')
                                @endif
                            @endif
						</div>
					</div>
				</div>
			</div>
		</div>

		@include('/new/partials/footer')
        
     

	</body>
</html>


@if(request()->msg=='canceled_ban' && $user->isAdvanceAuth() || Session::has('error_code')  || Session::has('message') || !$user->isAdvanceAuth() || $init_check_msg)
   
<style>
#tab01 .n_bbutton,#tab_general_alert .n_bbutton {width:initial;}
#tab01 .n_bbutton span,#tab_general_alert .n_bbutton span {float:initial;}
#tab01 .n_fengs {text-align:center;}
#tab_general_alert .n_fengs a:hover,#tab_general_alert .n_fengs a:focus {color: #333333;    text-decoration: none;}
</style>
<!--弹出-->
<div class="blbg" onclick="gmBtn1()" ></div>
@if(!$user->isAdvanceAuth())
<div class="bl bl_tab" id="tab_confirm">
    <div class="bltitle">提示</div>
    <div class="n_blnr01">
        <div class="blnr bltext">
        </div>
        <div class="n_bbutton">
            <span><a class="n_left" href="#" onclick="" >同意</a></span>
            <span><a onclick="gmBtn1()" class="n_right" href="javascript:">不同意</a></span>
        </div>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div> 
@endif
<div class="bl bl_tab " id="tab_general_alert" >
    <div class="bltitle" style="margin-top: -1px;"><span>提示</span></div>
    <div class="n_blnr01 matop10">
        <div class="n_fengs" >
        </div>
        <div class="n_bbutton">
            <span><a class="n_left" onclick="gmBtn1()">確定</a></span>
        </div>
    </div>
    <a  onclick="gmBtn1()" class="bl_gb"><img src="/auth/images/gb_icon.png"></a>
</div>
<div class="bl bl_tab" id="tab_general_confirm">
    <div class="bltitle">提示</div>
    <div class="n_blnr01">
        <div class="blnr bltext">
        </div>
        <div class="n_bbutton">
            <span><a class="n_left" href="#" onclick="" ></a></span>
            <span><a onclick="gmBtn1()" class="n_right" href="javascript:"></a></span>
        </div>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div> 
<div class="bl bl_tab " id="tab01" >
    <div class="bltitle" style="margin-top: -1px;"><span>提示</span></div>
    <div class="n_blnr01 matop10">
        <div class="n_fengs" >
            @if(Session::has('error_code'))
                @if(implode('_',Session::get('error_code')??[])!='b18')請輸入正確@endif
                @for($i=0;$i<count(Session::get('error_code'));$i++)
                @if(Session::get('error_code')[$i]!='b18')
                {{$i?'/':''}}{!!Session::get('error_code_msg')[Session::get('error_code')[$i]]!!}
                @endif
                @endfor
                @if(implode('_',Session::get('error_code')??[])!='b18')<br>@endif
                @if(in_array('b18',Session::get('error_code')))
                年齡未滿18歲，不得進行驗證
                @endif
                @php Session::forget('error_code')  @endphp
                @php Session::forget('error_code_msg')  @endphp         
            @elseif(request()->msg=='canceled_ban' && $user->isAdvanceAuth())     
                您已完成進階驗證，成功解除封鎖/警示
            @elseif(Session::has('message'))
                {!!implode('<br>',Session::get('message')??[])!!}
                @php Session::forget('message')  @endphp
            @elseif($init_check_msg??null)
            {!!$init_check_msg!!}
            @elseif(!$user->isAdvanceAuth())
                您好，您即將進入本站的進階身分驗證資訊系統。
                通過驗證將獲得本站的<img src="{{asset('new/images/b_7.png')}}" class="adv_auth_icon" />進階驗證標籤<img src="{{asset('new/images/b_7.png')}}"  class="adv_auth_icon" />               
            @endif 
        </div>
        <div class="n_bbutton">
            <span><a class="n_left" onclick="gmBtn1()">確定</a></span>
        </div>
    </div>
    <a  onclick="gmBtn1()" class="bl_gb"><img src="/auth/images/gb_icon.png"></a>
</div>

<script>
    $(function(){
        $(".blbg").hide();
        $(".bl").hide();
    });
    function cl() {
        $(".blbg").show();
        $("#tab01").show();
    }
    
    function gmBtn1(){
        $(".blbg").hide();
        $(".bl").hide();
        @if(!$user->isPhoneAuth() && !($is_edu_mode??null) && !$user->isAdvanceAuth())
        location.href='{{url("goto_member_auth")}}';
        @endif
    }

</script>
@if($is_edu_mode??null)
    @include('auth.advance_auth_js_part-email')
@else
    @include('auth.advance_auth_js_part-pid')
@endif

<script> 
    $(function(){
        cl();                       
    });            
</script> 
@endif


