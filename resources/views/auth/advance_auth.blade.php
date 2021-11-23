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
                .xy_input {width:60% !important;}
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
            .margin_top_one_line {margin-top:1em;}
            .xy_input {border-radius:5px;}
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
                            <div>您好，這是花園網的進階認證，將驗證您的以下資料，必須全部正確才能通過驗證。</div>
                            
                            <div class="bolder red">請注意必須填門號申請人的資料，預付卡無法驗證</div>

                            </h3>
						</div>
                        @endif
						<div class="de_input">
							
							@if($user->isAdvanceAuth())

								<div class="center">已完成驗證，<a href="{!! url('dashboard') !!}" class="red">按此開始使用網站</a></div>
                            @elseif($init_check_msg)
                                <div class="center">{!!$init_check_msg!!}</div>
							@else
								@if(isset($_GET['status']))
									@if($_GET['status']=='false')
										<span style="color:red">資料輸入錯誤，請重新驗證</span>
									@elseif($_GET['status']=='age_failed')
										<span style="color:red">年齡未滿18歲，不得進行驗證</span>
									@endif
								@endif
								<form id="advance_auth_form" name="advance_auth_form" class="m-form m-form--fit" method="POST" action="/advance_auth_process">
									<input type="hidden" name="_token" value="{{ csrf_token() }}" >
									<input type="hidden" name="userId" value="{{$user->id}}">

									<div class="de_input mtb20">
										<div class="zybg_new02 @if(in_array('i',Session::get('error_code')??[])) has_error @endif">                                       
											<input name="id_serial" id="id_serial" type="text" class="xy_input xy_left wbd" placeholder="請輸入身分證字號"  autocomplete="off">
										</div>
										<div class="zybg_new02 @if(in_array('p',Session::get('error_code')??[])) has_error @endif">
											<select name="phone_type" class="zy_select">
												<option>台灣</option>
											</select>
											<input @if($user->isPhoneAuth()) value="{{$user->getAuthMobile(true)}}" @endif style="@if($user->isPhoneAuth()) display:none;  @endif" name="phone_number" id="phone_number" type="text" class="xy_input xy_left" placeholder="請輸入手機號碼"  autocomplete="off">
                                            @if($user->isPhoneAuth())
                                            <input autocomplete="off" value="{{$user->getAuthMobile(true)}} (已驗證)" class="xy_input xy_left only_show"  disabled>
                                            @endif
										</div>

										<div class="zybg_new02 birthday_selector @if(in_array('b',Session::get('error_code')??[])) has_error @endif">
											<em>生日</em>
                                            <div class="se_zlman left">
                                              <select data-parsley-errors-messages-disabled name="year" id="year"  class="xy_input select_xx04 sel_year">
                                              </select>
                                            </div>
                                            <div class="se_zlman left">
                                              <select data-parsley-errors-messages-disabled name="month" id="month"  class="xy_input select_xx04 sel_month">
                                              </select>
                                            </div>  
                                            <div class="se_zlman left">
                                              <select data-parsley-errors-messages-disabled name="day" id="day"  class="xy_input select_xx04 sel_day">
                                              </select>
                                            </div>                                              
                                        </div>

										<button type="text" class="n_zybg_right btn_yz advanceAuthSubmit" onclick="tab_agree();return false;">驗證</button>
									</div>
								</form>
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
#tab01 .n_bbutton {width:initial;}
#tab01 .n_bbutton span {float:initial;}
#tab01 .n_fengs {text-align:center;}
</style>
<!--弹出-->
<div class="blbg" onclick="gmBtn1()" ></div>
@if(!$user->isAdvanceAuth())
<div class="bl bl_tab" id="tab_confirm">
    <div class="bltitle">提示</div>
    <div class="n_blnr01">
        <div class="blnr bltext">
        本站會將您的門號以及生日同步更新到會員基本資料，
        <span class="bolder red">身分證字號則只用在本次驗證並不會紀錄</span>
		<div>此驗證依照以下條款進行</div>
		<div><a target="_blank" href="{{url('advance_auth_midclause')}}">{{url('advance_auth_midclause')}}</a></div>
		<div>請詳細閱讀後選擇</div>
        </div>
        <div class="n_bbutton">
            <span><a class="n_left" href="#" onclick="" >同意</a></span>
            <span><a onclick="gmBtn1()" class="n_right" href="javascript:">不同意</a></span>
        </div>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div> 
@endif
<div class="bl bl_tab " id="tab01" >
    <div class="bltitle" style="margin-top: -1px;"><span>提示</span></div>
    <div class="n_blnr01 matop10">
        <div class="n_fengs" >
            @if(Session::has('error_code'))
                @if(implode('_',Session::get('error_code')??[])!='b18')請輸入正確@endif
                @for($i=0;$i<count(Session::get('error_code'));$i++)
                @if(Session::get('error_code')[$i]!='b18')
                {{$i?'/':''}}{{Session::get('error_code_msg')[Session::get('error_code')[$i]]}}
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
<script src="/new/js/birthday.js" type="text/javascript"></script>
<script>
    $.ms_DatePicker();
    $(function(){
        $(".blbg").hide();
        $(".bl").hide();
    });
    function cl() {
        $(".blbg").show();
        $("#tab01").show();
    }
    
    function tab_confirm_send() {
        $("#tab_confirm").show().find('.bltext').click().html('您輸入的資料如下'
            +'<div>身分證字號：'+$('#id_serial').val()+'</div>'
            +'<div>手機號碼：'+$('#phone_number').val()+'</div>'
            +'<div>生日：'+$('#year').val()+'/'+$('#month').val()+'/'+$('#day').val()+'</div>'
            +'<div>{{$user_pause_during_msg}}所以請務必確定資料正確！</div>'
        ).parent().find('.n_left').blur().html('確定送出').attr('onclick','document.advance_auth_form.submit();this.setAttribute("onclick", "return false;");return false;')
        .parent().parent().find('.n_right').html('返回修改');        
    }
    
    function tab_agree() {
        $(".blbg").show();
        clear_error_appear();
        if(check_val()) {
            $("#tab_confirm").show().find('.bltext').html(
                    '本站會將您的門號以及生日同步更新到會員基本資料，'+
                    '<span class="bolder red">身分證字號則只用在本次驗證並不會紀錄</span>'+
                    '<div class="margin_top_one_line">此驗證依照以下條款進行</div>'+
                    '<div><a target="_blank" href="{{url('advance_auth_midclause')}}">{{url('advance_auth_midclause')}}</a></div>'+
                    '<div class="margin_top_one_line">請詳細閱讀後選擇</div>'
                )
                .parent().find('.n_left').html('同意').attr('onclick','tab_confirm_send();return false;')
                .parent().parent().find('.n_right').html('不同意');
        }
        else cl();      
        
        return false;
    }
    
    function clear_error_appear() {
        $('#id_serial').parent().removeClass('has_error');
        $('#phone_number').parent().removeClass('has_error');
        $('#year,#month,#day').parent().parent().removeClass('has_error');        
    }
    
    function check_val() {
        var empty_str = '';
        var age_error_msg = '';
        var error_msg = '';
        if($('#id_serial').val()=='' || !checkIdSerial($('#id_serial').val())) {
            $('#id_serial').parent().addClass('has_error');
            empty_str='/身分證字號';
        }
        if($('#phone_number').val()=='' || !checkPhoneNumber($('#phone_number').val())) {
            $('#phone_number').parent().addClass('has_error');
            empty_str+='/門號';
        }
        if($('#year').val()=='' || $('#month').val()=='' || $('#day').val()=='') {
            $('#year,#month,#day').parent().parent().addClass('has_error');
            empty_str+='/生日';
        }
        else {
            year = $('#year').val();
            month = $('#month').val();
            day = $('#day').val();
            var now = new Date();
            nowyear=now.getFullYear();
            
            nowmonth=now.getMonth();
            nowday = now.getDate();
            
            age=nowyear-year;
            
            if(month>nowmonth || month==nowmonth && day>nowday){
                age--;
            }  

            if(age<18) {
                $('#year,#month,#day').parent().parent().addClass('has_error');
                age_error_msg = '年齡未滿18歲，不得進行驗證';
            }
        }
        
        if(empty_str!='') {
            error_msg = '請輸入正確'+empty_str.replace('/','');
            if(age_error_msg!='')  error_msg+='<br>';
        }
        
        if(age_error_msg!='') {
            error_msg+=age_error_msg;
        }
        
        if(error_msg=='') {
            return true;
        }
        else {
            $('#tab01 .n_fengs').html(error_msg);
            return false;
        }
        
    }
    function gmBtn1(){
        $(".blbg").hide();
        $(".bl").hide();
        @if(!$user->isPhoneAuth())
        location.href='{{url("member_auth")}}';
        @endif
    }
    
    function checkPhoneNumber(phone_number) {
        return phone_number.match('^09[0-9]{8}$');
    }

    function checkIdSerial(id_serial) {
        var id = id_serial.trim();
        var check_id_rs = true;

        if (id.length != 10) {
            check_id_rs = false;
        }


        var regionCode = id.charCodeAt(0);
        if (regionCode < 65 | regionCode > 90) {
            check_id_rs = false;
        }

        var sexCode = id.charCodeAt(1);
        if (sexCode != {{$user->engroup+48}}) {
            check_id_rs = false;
        }

        var splitCode = id.slice(2)
        for (var i in splitCode) {
            var scode = splitCode.charCodeAt(i);
            if (scode < 48 | scode > 57) {
                check_id_rs = false;
            }
        }
        if(check_id_rs) {
            var letterConverter = "ABCDEFGHJKLMNPQRSTUVXYWZIO"
            var weightArr = [1, 9, 8, 7, 6, 5, 4, 3, 2, 1, 1]

            id_checking = String(letterConverter.indexOf(id[0])+10)
                    + id.slice(1);

            total = 0
            for (let i = 0; i < id_checking.length; i++) {
                c = parseInt(id_checking[i])
                w = weightArr[i]
                total += c * w
            }

            check_id_rs = total % 10 == 0
        }
        return check_id_rs
    }

</script>

<script> 
    $(function(){
        cl();                       
    });            
</script> 
@endif


