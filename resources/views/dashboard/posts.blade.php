<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="format-detection" content="telephone=no" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>投稿</title>
		<!-- Bootstrap -->
		<link href="/posts/css/bootstrap.min.css" rel="stylesheet">
		<link href="/posts/css/bootstrap-theme.min.css" rel="stylesheet">
		<!-- owl-carousel-->
		<!--    css-->
		<link rel="stylesheet" href="/posts/css/style.css">
		<link rel="stylesheet" href="/posts/css/swiper.min.css">
		<script src="/posts/js/bootstrap.min.js"></script>
		<script src="/posts/js/jquery-2.1.1.min.js" type="text/javascript"></script>
		<script src="/posts/js/main.js" type="text/javascript"></script>
		<script src='/plugins/tinymce/tinymce.js' referrerpolicy="origin"></script>
		<script>
			 if (navigator.userAgent.match(/Android/i)
                || navigator.userAgent.match(/webOS/i)
            || navigator.userAgent.match(/iPhone/i)
            || navigator.userAgent.match(/iPad/i)
            || navigator.userAgent.match(/iPod/i)
            || navigator.userAgent.match(/BlackBerry/i)
            || navigator.userAgent.match(/Windows Phone/i)
            ) {
                tinymce.init({
				selector: '#contents',
				language: 'zh_TW',
				plugins: "autosave",
				mobile: {
					theme: 'mobile',
				},
				branding: false
				});
            }
            else {
                tinymce.init({
				selector: '#contents',
				language: 'zh_TW',
				plugins: "autosave",
				autosave_ask_before_unload: true,
				autosave_interval: "5s",
				branding: false
				});
            }
			
		</script>
	<style>
		.icon_pointer{
			cursor:pointer;
		}
		.tox.tox-tinymce {
			border-radius: 20px;
		}
		body#tinymce {
			font-size: 16px !important;
		}

		.toug_back:hover{
			color:white !important; text-decoration:none !important
		}

		.tou_tx, .tc_text{
			font-style:normal !important;
		}

	</style>
	</head>

	<body>
	<div class="head_3 head hetop">
		<div class="container">
			<div class="col-sm-12 col-xs-12 col-md-12">
				<a href="{!! url('') !!}" >
					<img src="/new/images/icon_41.png" class="logo" />
				</a>
				@if (Auth::user() && Request::path() != '/activate' && Request::path() != '/activate/send-token')
					@if(Session::has('original_user'))
						<div class="ndlrfont">
							<a href="{{ route('escape') }}" class="m-nav__link m-dropdown__toggle">
								回到原使用者
							</a></div>
					@endif
					@if(!str_contains(url()->current(), 'dashboard') && !str_contains(url()->current(), 'contact') && !str_contains(url()->current(), 'notification') && !str_contains(url()->current(), 'feature') && !str_contains(url()->current(), 'terms') && !str_contains(url()->current(), 'activate') && Auth::user() /*&& Request::path() != '/activate' && Request::path() != '/activate/send-token'*/)
					<div class="ndlrfont">
						<a href="{!! url('dashboard/search') !!}"><img src="/new/images/icon_38.png"></a>
						<span class="getNum">
							<a href="{!! url('dashboard/chat2/'.csrf_token().\Carbon\Carbon::now()->timestamp) !!}">
								<img src="/new/images/icon_45.png">
							</a>
							<span>{{ \App\Models\Message::unread($user->id) }}</span>
						</span>
						<a href="{!! url('dashboard') !!}"><img src="/new/images/icon_48.png"></a>
					</div>
					@endif
				@else
					<div class="ndlrfont"><a href="{!! url('/checkAdult') !!}">註冊</a>丨<a href="{!! url('login') !!}">登入</a></div>
				@endif
			</div>
		</div>
	</div>
	<div class="head heicon">
			<div class="container">
				<div class="col-sm-12 col-xs-12 col-md-12">
					<div class="commonMenu">
						<div class="menuTop">
							<a href="{!! url('') !!}" >
								<img src="/new/images/icon_41.png" class="logo" />
							</a>
							@if (Auth::user() && Request::path() != '/activate' && Request::path() != '/activate/send-token')
							<span id="menuButton"><img src="/new/images/icon.png" class="he_img"></span>
							@else
							<div class="ndlrfont"><a href="{!! url('/checkAdult') !!}">註冊</a>丨<a href="{!! url('login') !!}">登入</a></div>
							@endif
						</div>
						@if (Auth::user() && Request::path() != '/activate' && Request::path() != '/activate/send-token')
						<ul id="menuList" class="change marg30">
                            <div class="comt"><img src="/new/images/t.png"></div>
                            <div class="coheight">
{{--							<div class="heyctop">@if (str_contains(url()->current(), 'dashboard')) {{ $user->name }} @elseif (isset($cur)) {{ $cur->name }} @endif @if (((isset($cur) && $cur->isVip() && $cur->engroup == '1')) || ($user->isVip() && str_contains(url()->current(), 'dashboard'))) (VIP) @endif</div>--}}
							<div class="heyctop">{{ $user->name }}@if($user->isVip()) (VIP) @endif</div>
							<div class="helist">
								<ul>
									<li>
										<a href="{!! url('dashboard') !!}"><img src="/new/images/icon_48.png">個人資料</a>
									</li>
									<li>
										<a href="{!! url('dashboard/search') !!}"><img src="/new/images/icon_38.png">搜索</a>
									</li>
									<li>
										<a href="{!! url('dashboard/chat2/'.csrf_token().\Carbon\Carbon::now()->timestamp) !!}"><img src="/new/images/icon_45.png">收件夾</a><span>{{ \App\Models\Message::unread($user->id) }}</span>
									</li>
									<li>
					                   <a href="{!! url('dashboard/browse') !!}"><img src="/new/images/icon_46.png">瀏覽資料</a>
					                </li>
								</ul>
							</div>
							<a href="{!! url('logout') !!}" class="tcbut">LOGOUT</a>
                            </div>
						</ul>
						@endif
					</div>
				</div>
			</div>
		</div>
<style>
input[type='radio'],input[type='checkbox']{width:18px;height: 18px;vertical-align:middle;opacity: 0;}
</style>
<script src="/posts/js/jquery-1.8.3.min.js"></script>
<script text="type/javascript" src="/posts/js/input.js"></script>

		<!---->
		<div class="container matop70">
			<div class="row">
				<div class="col-sm-2 col-xs-2 col-md-2 dinone">
				@if(isset($user))

<div class="leftbg">
	<div class="leftimg">
		<img src="@if(file_exists( public_path().$user->meta_()->pic )){{$user->meta_()->pic}} @else/img/male-avatar.png @endif">
		<h2 style="word-break: break-all;">@if (str_contains(url()->current(), 'dashboard')) {{ $user->name }} @elseif (isset($cur)) {{ $cur->name }} @endif
				@if (((isset($cur) && $cur->isVip() && $cur->engroup == '1')) || ($user->isVip() && str_contains(url()->current(), 'dashboard'))) (VIP) @endif</h2>
					</div>
					<div class="leul">
						<ul>
							<li>
								<a href="{!! url('dashboard') !!}"><img src="/new/images/icon_48.png">個人資料</a>
							</li>
							<li>
								<a href="{!! url('dashboard/search') !!}"><img src="/new/images/icon_38.png">搜索</a>
							</li>
							<li>
								<a href="{!! url('dashboard/chat2/'.csrf_token().\Carbon\Carbon::now()->timestamp) !!}"><img src="/new/images/icon_45.png">收件夾</a><span>{{ \App\Models\Message::unread($user->id) }}</span></li>
							<li>
							<a href="{!! url('dashboard/browse') !!}"><img src="/new/images/icon_46.png">瀏覽資料</a>
							</li>
							<li>
								<a href="{!! url('logout') !!}"><img src="/new/images/iconout.png">退出</a>
							</li>
						</ul>
					</div>
				</div>


				@endif


				</div>
				<div class="col-sm-12 col-xs-12 col-md-10">
					 <div class="two_tg">
                           <div class="two_gtitle"><img src="/posts/images/tg_15.png">投稿
						   <a href="/dashboard/posts_list" class="toug_back">返回</a>
						   </div>
                           <div class="tow_input">
                                 
								<form action="/dashboard/doPosts" id="posts" method="POST">
								<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
								<!-- <h1>投稿</h1>
								<input type="text" name="title">
								<div>
									<textarea name="contents" id="contents"></textarea>
								</div>
								<input type="checkbox" name="anonymous">匿名於站內發布</br>
								<input type="checkbox" name="combine">站內發布並與本站帳號連結</br>
								<input type="checkbox" name="agreement">同意站方匿名行銷使用(男會員贈送一個月vip，女會員給一個 tag)</br>
								<input type="submit"> -->

								<input name="title" type="text" id="title" class="tw_input"  placeholder="#標題">
                                 <textarea  name="contents" id="contents" cols="" rows="" class="tw_textinput" placeholder="#内容" style="border-radius:20px"></textarea>
                                 <div class="ti_kuang">
                                       <div class="ti_title">點這裡變更身分</div>
                                       <h2 class="matop15"><i class='input_style radio_bg'><input type="radio"  name="is_anonymous" id="is_anonymous" value="anonymous"></i>匿名於站內發布</h2>
                                       <h2><i class='input_style radio_bg'><input type="radio" name="is_anonymous" id="is_anonymous" value="combine"></i>站內發布與本站帳號連結</h2>
                                 </div>
                                 <div class="ticheckbox"><i class='input_style radio_bg'><input type="checkbox" name="agreement" id="agreement"></i>同意站方匿名行銷使用</div>
								<a  class="dlbut icon_pointer"  onclick="cl()">確定</a>
								</form>
                           </div>
                     </div>
				</div>

			</div>
		</div>

		<div class="bot chbottom">
			<a href="">站長開講</a> 丨
			<a href=""> 網站使用</a> 丨
			<a href=""> 使用條款</a> 丨
			<a href=""> 聯絡我們</a>
			<img src="/posts/images/bot_10.png">
		</div>
        
        




		
 <!--弹框-->       
<div class="blbg" onclick="gmBtn1()"></div>

<div class="bl bl_tab bl_tab_01" id="tab01">
    <div class="bltitle_a"><span class="font14" style="text-align:center;float:none !important">提示</span></div>
    <div class="n_blnr02 matop10">
         <div class="n_fengs" style="text-align:center;width:100%;">投稿成功，<br>待審核通過便會發佈哦！</div>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb01"><img src="/posts/images/gb_icon.png"></a>
</div>

<div class="bl bl_tab bl_tab_01" id="tab02">
    <div class="bltitle_a"><span class="font14" style="text-align:center;float:none !important">提示</span></div>
    <div class="n_blnr02 matop10">
         <div class="n_fengs" style="text-align:center;width:100%;">投稿失敗，<br>請勾選投稿身份！</div>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb01"><img src="/posts/images/gb_icon.png"></a>
</div>

<div class="bl bl_tab bl_tab_01" id="tab_title">
    <div class="bltitle_a"><span class="font14" style="text-align:center;float:none !important">提示</span></div>
    <div class="n_blnr02 matop10">
         <div class="n_fengs" style="text-align:center;width:100%;">您的標題不可以為空！</div>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb01"><img src="/posts/images/gb_icon.png"></a>
</div>

<div class="bl bl_tab bl_tab_01" id="tab_contents">
    <div class="bltitle_a"><span class="font14" style="text-align:center;float:none !important">提示</span></div>
    <div class="n_blnr02 matop10">
         <div class="n_fengs" style="text-align:center;width:100%;">您的內容不可以為空！</div>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb01"><img src="/posts/images/gb_icon.png"></a>
</div>

<script>
// $(".blbg").show();
//          	$("#tab01").show();

	function cl() {
		var title = $("#title").val();
		// var contents = $("#contents").val();

		if (title == "") {
			$(".blbg").show();
         	$("#tab_title").show();
			return false;
		}


		var content = tinyMCE.editors[$('#contents').attr('id')].getContent();
		if(content.length <=0 ){
			$(".blbg").show();
			$("#tab_contents").show();
			return false;
		}
		// if (contents == "") {
		// 	$(".blbg").show();
        //  	$("#tab_contents").show();
		// 	return false;
		// }
		// console.log($("body#tinymce").html());return false;
		// console.log($("#is_anonymous").parent('.radio_bg_check').size())
		// $("#agreement").parent('.checkbox_bg_check').size()>0
		// console.log($("#is_anonymous").val()); return false;
		
		if($("#is_anonymous").parent('.radio_bg_check').size()){
			$(".blbg").show();
         	$("#tab01").show();
			$("#posts").submit();
		}else{
			$(".blbg").show();
			$("#tab02").show();
		}
		
    }

	
	$(document).ready(function(){
		if($("#is_anonymous").parent('.checkbox_bg_check').size()>0){
			$(this).attr('')
		}
	});

    function gmBtn1(){
        $(".blbg").hide()
        $(".bl").hide()	
			
    }
	
	/*check input can't be empty*/
	// function verify(){
	// 	var title = $("#title").val();
	// 	var contents = $("#contents").val();

	// 	if (title == "") {
	// 		alert("請填寫標題");
	// 		return false;
	// 	}
	// 	if (contents == "") {
	// 		alert("請填寫內容");
	// 		return false;
	// 	}
	// }
	
</script>


	</body>

</html>
