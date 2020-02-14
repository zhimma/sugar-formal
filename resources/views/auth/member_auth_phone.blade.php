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
		<script src="/auth/js/jquery-2.1.1.min.js" type="text/javascript"></script>
		<script src="/auth/js/bootstrap.min.js"></script>
		<script src="/auth/js/main.js" type="text/javascript"></script>
		<style>
			#photo>input {
				display:none;
			}
		</style>
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
                                                   <input name="" type="text" id="phone" class="xy_input" placeholder="請輸入手機號碼">
                                               </div>
                                               <a id="auth_phone" class="zybg_right">驗證</a>
                                         </div>
                                         <div class="zybg_new02">
                                               <input name="" type="text" id="checkcode" class="xy_input xy_left" placeholder="請輸入驗證碼">
                                               <a id="get_auth_code" class="xy_yanx">獲取驗證碼</a>
                                         </div>
                                        </div>
                                      
                                      <div class="zy_line"></div>
									  <form id="auth_all">
                                      <div class="de_input">
                                          <div class="yanzheng_1">驗證2</div>
                                          <div id="photo" class="zy_kuang">
                                              <img id="prev_img" src="/auth/images/photo_01.png">
                                              <span>上傳照片</span>
											  <input type='file' id="imgInp" />
                                          </div>
                                          <a id="auth_photo" class="dlbut yx_butco">驗證</a>
                                      </div>
									  </form>
										<!-- <form runat="server">
										<input type='file' id="imgInp" />
										<img id="blah" src="#" alt="your image" />
										</form> -->
                                      
                                      <!-- <div class="de_input">
                                          <div class="yanzheng_1">驗證1</div>
                                          <div class="zy_kuang yx_font">
                                              <img src="/auth/images/photo_02.png">
                                              <span>上傳照片</span>
                                          </div>
                                          <a href="" class="dlbut">驗證</a>
                                      </div> -->
                                     
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




		<div class="blbg tab_phone" onclick="gmBtn1()" style="display: none;"></div>
<div class="bl bl_tab " id="tab01" style="display: none;">
    <div class="bltitle"><span>提示</span></div>
    <div class="n_blnr01 matop10">
         <div class="n_fengs" style="text-align:center">手機驗證成功<br>下一步：拍攝照片上傳完成身份驗證！</div>
        <a class="n_bllbut matop30" id="" onclick="gmBtn1()" >確定</a>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb"><img src="/auth/images/gb_icon.png"></a>
</div>




<div class="blbg tab_photo" onclick="gmBtn1()" style="display: none;"></div>
<div class="bl bl_tab bl_tab_01" id="tab04" style="display: none;">
    <div class="bltitle_a"><span>提示</span></div>
    <div class="n_blnr02 matop10">
         <div class="n_fengs" style="text-align:center;width:100%;">恭喜您</div>
         <div class="n_fengs" style="text-align:center;width:100%;">完成身份驗證囉！</div>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb01"><img src="/auth/images/gb_icon.png"></a>
</div>

	
	
	
	







	</body>

</html>



<script>

function cl() {
	$(".blbg").show()
	$("#tab01").show()
}
	   
function c2() {
	$(".blbg").show()
	$("#tab02").show()
}

function gmBtn1(){
	$(".blbg").hide()
	$(".bl").hide()		
}
    

$("#get_auth_code").on('click', function(){
	$.ajax({
		type: 'POST',
		url: '/Common/get_message',
		data:{
			_token: '{{csrf_token()}}',
			'phone': $("#phone").val(),
		},
		success: function(res) {
			console.log(res);
		}
	});
});

$("#auth_phone").on('click', function(){
	$.ajax({
		type: 'POST',
		url: '/Common/checkcode_during',
		data:{
			_token: '{{csrf_token()}}',
			'checkcode': $("#checkcode").val(),
		},
		success: function(res) {
			res = JSON.parse(res);
			if(res.code=='200'){
				$("#tabl_phone").css('display', 'block');
				$("#tab01").css('display','block');

				$("#photo").addClass('yx_font');
				$("#auth_photo").removeClass('yx_butco');
			}
			
		}
	});
});

$("#auth_photo").on('click', function(){
	console.log($('#imgInp')['context']['images'][1]['src']);
	var formData = new FormData();
	formData = $('#imgInp')['context']['images'][1]['src'];
// console.log(formData);
	var data = {
			_token: '{{csrf_token()}}',
			"reader": formData,
			"name":'123',

		}
	$.ajax({
		type: 'POST',
		url: '/Common/upload_img',
		// cache: false,
		// contentType: false,
		// processData: false,
		data:data,
		success: function(res) {
			// res = JSON.parse(res);
			// if(res.code=='200'){
			// 	$("#tabl_phone").css('display', 'block');
			// 	$("#tab01").css('display','block');

			// 	$("#photo").addClass('yx_font');
			// 	$("#auth_photo").removeClass('yx_butco');
			// }
			
		}
	});
});

// $("#auth_photo").on('click', function(){
// 	$("#auth_all").submit();
// });

function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#prev_img').attr('src', e.target.result).css('width','300px');
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}

$("#imgInp").change(function() {
  readURL(this);
});
</script>

