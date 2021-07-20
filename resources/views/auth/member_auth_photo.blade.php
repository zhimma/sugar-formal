<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="format-detection" content="telephone=no" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>會員驗證</title>
		<!-- Bootstrap -->
		<link href="/auth/css/bootstrap.min.css" rel="stylesheet">
		<link href="/auth/css/bootstrap-theme.min.css" rel="stylesheet">
		<!-- owl-carousel-->
		<!--    css-->
		<link rel="stylesheet" href="/auth/css/style.css">
		<link rel="stylesheet" href="/auth/css/swiper.min.css">
		<script src="/auth/js/bootstrap.min.js"></script>
		<script src="/auth/js/jquery-2.1.1.min.js" type="text/javascript"></script>
		<script src="/auth/js/main.js" type="text/javascript"></script>
		<script>
			$(document).ready(() =>{
				var formData = new FormData();
				var xhr = new XMLHttpRequest();
				xhr.open("get", "{{ route('getUnread', $user->id) }}", true);
				xhr.onload = function (e) {
					var response = e.currentTarget.response;
					console.log(response);
					$('#unreadCount').text(response);
					$('#unreadCount2').text(response);
				}
				xhr.send(formData);  /* Send to server */
			});
			Echo.private('NewMessage.{{ $user->id }}')
				.listen('NewMessage', (e) => {
					let unread = parseInt($('#unreadCount').text(), 10);
					let unread2 = parseInt($('#unreadCount2').text(), 10);
					unread++;
					unread2++;
					$('#unreadCount').text(unread);
					$('#unreadCount2').text(unread2);
					@if(request()->route()->getName() == 'chat2View')
					if($('.sjtable.' + e.from_id).find('.number.' + e.from_id).length === 0){
						$('.sjtable.' + e.from_id).prepend('<i class="number ' + e.from_id + '">1</i>');
					}
					else{
						let chatUnread = parseInt($('.number.' + e.from_id).text(), 10);
						chatUnread++;
						$('.number.' + e.from_id).text(chatUnread);
					}
					if(showMsg){
						$('.ellipsis.' + e.from_id).text(e.content);
					}
					@endif
				});
		</script>
	</head>
	<body>
		<div class="head_3">
			<div class="container">
				<div class="col-sm-12 col-xs-12 col-md-12">
					<div class="commonMenu">
						<div class="menuTop">
							<img src="/auth/images/icon_41.png" class="logo" />
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
		<div class="container matop120 yzheight">
			<div class="row">
				<div class="col-sm-12 col-xs-12 col-md-12">
					    <div class="dengl matbot140">
                                   <div class="zhuce"><h2>會員驗證</h2></div>
                                   <div class="de_input">
                                         <div class="yanzheng_1">驗證1</div>
                                         <div class="zybg_new_bg">
                                               <div class="zybg_new">
                                                   <select name="" class="zy_select"><option>台灣</option><option>大陸</option></select>
                                                   <input name="" type="text" class="xy_input" placeholder="請輸入手機號碼">
                                               </div>
                                               <a class="zybg_right">驗證成功</a>
                                         </div>
                                         <div class="zybg_new02">
                                               <input name="" type="text" class="xy_input xy_left" placeholder="請輸入驗證碼">
                                               <a class="xy_yanx">獲取驗證碼</a>
                                         </div>
                                      </div>
                                      
                                      <div class="zy_line"></div>
                                   <!--    <div class="de_input">
                                          <div class="yanzheng_1">驗證2</div>
                                          <div class="zy_kuang">
                                              <img src="images/photo_01.png">
                                              <span>上傳照片</span>
                                          </div>
                                          <a onclick="cl()" class="dlbut yx_butco">驗證</a>
                                      </div>
                                     -->   
                                     <div class="de_input">
                                          <div class="yanzheng_1">驗證1</div>
                                          <div class="zy_kuang yx_font">
                                              <img src="/auth/images/photo_02.png">
                                              <span>上傳照片</span>
                                          </div>
                                          <a href="" class="dlbut">驗證</a>
                                      </div>
                                    
                        </div>
				</div>
			</div>
		</div>

		<div class="bot foot_pc">
			<a href="">站長開講</a> 丨
			<a href=""> 網站使用</a> 丨
			<a href=""> 使用條款</a> 丨
			<a href=""> 聯絡我們</a>
			<img src="/auth/images/bot_10.png">
		</div>





	</body>

</html>