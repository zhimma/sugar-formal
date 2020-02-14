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
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/bootstrap-theme.min.css" rel="stylesheet">
		<!-- owl-carousel-->
		<!--    css-->
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/swiper.min.css">
		<script src="js/bootstrap.min.js"></script>
		<script src="js/jquery-2.1.1.min.js" type="text/javascript"></script>
		<script src="js/main.js" type="text/javascript"></script>

	</head>
	<body>
		<div class="head_3">
			<div class="container">
				<div class="col-sm-12 col-xs-12 col-md-12">
					<div class="commonMenu">
						<div class="menuTop">
							<img src="images/icon_41.png" class="logo" />
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
                                              <img src="images/photo_02.png">
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
			<img src="images/bot_10.png">
		</div>



<div class="blbg" onclick="gmBtn1()" style="display: block;"></div>
<div class="bl bl_tab bl_tab_01" id="tab04" style="display: block;">
    <div class="bltitle_a"><span>提示</span></div>
    <div class="n_blnr02 matop10">
         <div class="n_fengs" style="text-align:center;width:100%;">恭喜您</div>
         <div class="n_fengs" style="text-align:center;width:100%;">完成身份驗證囉！</div>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb01"><img src="images/gb_icon.png"></a>
</div>





<script>
   
	function cl() {
		 $(".blbg").show()
         $("#bl").show()
    }
		   
	function c2() {
		 $(".blbg").show()
         $("#bl").show()
    }

	
    function gmBtn1(){
        $(".blbg").hide()
        $(".bl").hide()	
			
    }
    
	
</script>




	</body>

</html>