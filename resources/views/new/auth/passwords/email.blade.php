@extends('new.layouts.website')

@section('app-content')

<div class="head_3">
	<div class="container">
		<div class="col-sm-12 col-xs-12 col-md-12">
			<div class="commonMenu">
				<div class="menuTop">
					<img src="/new/images/icon_41.png" class="logo" />
                    <div class="ndlrfont"><a href="">註冊</a>丨<a href="">登入</a></div>
					<!--<span id="menuButton"><img src="images/icon.png" class="he_img"></span>-->
				</div>
				<!--<ul id="menuList" class="change marg30">
                    <div class="comt"><img src="images/t.png"></div>
                    <div class="coheight">
					<div class="heyctop">測試系統賬號</div>
					<div class="helist">
						<ul>
							<li><a href=""><img src="images/icon_38.png">搜索</a></li>
							<li><a href=""><img src="images/icon_45.png">訊息</a><span>10</span></li>
							<li><a href=""><img src="images/icon_46.png">名單</a></li>
							<li><a href=""><img src="images/icon_48.png">我的</a></li>
						</ul>
					</div>
					<a href="" class="tcbut">LOGOUT</a>
                    </div>
				</ul>-->
			</div>
		</div>
	</div>
</div>
<!---->
<div class="container matop70">
	<div class="row">
		<div class="col-sm-12 col-xs-12 col-md-12">
		    <div class="wd_xsy">
		    	@include('partials.errors')
				@include('partials.status')
               	<div class="wxsy_title">忘記密碼</div>
               	<div class="wxsy_k">
               		<form class="m-login__form m-form" method="POST" action="/password/email" onsubmit="return check()">
               			{!! csrf_field() !!}
               			<div class="wo_input01 dlmarbot">
               				<input name="email" type="email" class="zcinput" placeholder="帳號 (您的E-mail)" autocomplete="off">
               			</div>
	                    <div class="wordbg">
	                        <h3 class="wiword">請注意：</h3>
	                        <h3 class="yzfont">1. 每次更改密碼連結的有效時間為60分鐘，請務必把握時間。</h3>
	                        <h3 class="yzfont">2. 若您收到多封更改密碼的信件，請以最新那封為主，舊的信都會失效。</h3>
	                    </div>
	                    <input class="dlbut" type="submit" value="更改密碼" onclick="tips()" style="border-style: none;">
                    </form>
               </div>
            </div>
		</div>
	</div>
</div>

<div class="blbg" onclick="gmBtn1()"></div>
<div class="bl_tab" id="tab01">
    <div class="bltitle">提示</div>
    <div class="blnr bltext">我們已經通過電子郵件發送您的密碼重置鏈接！</div>
    <a id="" onclick="gmBtn1()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>

<script>
   
   	function check() {
   		let email = $("input[name='email']").val()

   		//checkEmail is defined in common.js
   		return checkEmail(email)
   	}
	function tips() {

		if(!check()){
			$(".bltext").text('請輸入電子郵件')
		}
		else{
			$(".bltext").text('我們已經通過電子郵件發送您的密碼重置鏈接！')
		}
		$(".blbg").show()
        $("#tab01").show()
    }
    function gmBtn1(){
        $(".blbg").hide()
        $(".bl_tab").hide()	
			
    }
    
</script>

@stop


