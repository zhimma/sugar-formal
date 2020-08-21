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
			
			.upload_width{
				width:80% !important;
			}
			#photo .delBtn{
				position:relative;
				text-align:right !important;
				right:10%;

			}
            .chbottom{ position:fixed;bottom:0;}

		</style>
		<style>

			/*@media (min-width:993px){*/
			/*.chbottom{ position:absolute;bottom:0;}*/
			/*}*/
            /*@media (min-width:823px) and (max-width:823px){*/
            /*    .chbottom{ position: absolute;bottom:unset;}*/
            /*}*/
			/*@media (min-width:761px) and (max-width:822px) {*/
			/*.chbottom{ position:absolute;  !*bottom:0;*!}*/
			/*}*/
			@media (max-width:760px) {

                .chbottom{ position:static;bottom:0;}
			}

            /*@media (min-width:1024px) and (min-height:768px){*/
            /*    .chbottom{ position:absolute;bottom:unset;}*/
            /*}*/
            /*@media (min-width:1024px) and (min-height:1366px) and (max-width:1024px) and (max-height:1366px){*/
            /*    .chbottom{ position:absolute;bottom:0;}*/
            /*}*/
            @media (min-width:812px) and (min-height:375px) and (max-width:812px) and (max-height:375px){
                .chbottom{ position:static;bottom:0;}
            }
            @media (min-width:823px) and (min-height:411px) and (max-width:823px) and (max-height:411px){
                .chbottom{ position:static;bottom:0;}
            }

		</style>
	</head>
	<body>
		<div class="head_3">
			<div class="container">
				<div class="col-sm-12 col-xs-12 col-md-12">
					<div class="commonMenu">
						<div class="menuTop">
                            <a href="{!! url('') !!}" >
							<img src="/auth/images/icon_41.png" class="logo" />
                            </a>
                            <!-- <div class="ndlrfont"><a href="">註冊</a>丨<a href="">登入</a></div> -->
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
                                   <div class="zhuce"><h2>會員驗證</h2>
                                       <h3 style="line-height:1.2;">請用您的智慧型手機<br>進行本人的身份驗證</h3>
                                   </div>
                                   <div class="de_input">
                                         <div class="yanzheng_1">驗證1</div>
                                       @if($user->isPhoneAuth())
                                           <div>已完成驗證</div>
                                           @else
                                         <div class="zybg_new_bg">
                                               <div class="zybg_new">
                                                   <select name="" class="zy_select"><option>台灣</option><option>大陸</option></select>
                                                   <input name="" type="text" id="mobile" class="xy_input" placeholder="手機號碼">
                                               </div>
                                               <a id="get_auth_code" class="zybg_right" style="cursor:pointer">獲取驗證碼</a>
                                         </div>
                                         <div class="zybg_new02">
                                               <input name="" type="text" id="checkcode" class="xy_input xy_left" placeholder="請輸入驗證碼">
                                               <a id="auth_phone1" class="xy_yanx"><div style="text-align:center">驗證</div></a>
                                         </div>
                                           @endif
                                        </div>
                                      
{{--                                      <div class="zy_line"></div>--}}
{{--									  <form id="auth_all" enctype="multipart/form-data">--}}
{{--                                          {!! csrf_field() !!}--}}
{{--                                      <div class="de_input">--}}
{{--                                          <div class="yanzheng_1">驗證2</div>--}}
{{--                                          @if($user->isImgAuth())--}}
{{--                                              <div>已完成驗證</div>--}}
{{--                                          @else--}}
{{--                                          <div id="photo" class="zy_kuang">--}}
{{--										  	  --}}
{{--                                              <img id="prev_img" src="/auth/images/photo_01.png" style="cursor:pointer;">--}}
{{--											  <!-- <div class="delBtn" style="display:none;"><img id="del" src="/new/images/gb_icon01.png" style="cursor:pointer;width:30px;height:30px;right:0"></div> -->--}}
{{--											  <span>請用手機拍攝照片，並於十分鐘內上傳</span>--}}
{{--											  <!-- <div classs="mask"> -->--}}
{{--											  	<input type='hidden' id="imgInp" name="image"/>--}}
{{--											  <!-- </div> -->--}}
{{--                                          </div>--}}
{{--                                          <a id="auth_photo2" class="dlbut yx_butco" style="cursor:pointer">驗證</a>--}}
{{--                                          <br>--}}
{{--										  <a onclick="history.go(-1)" id="auth_photo" class="dlbut yx_butco" style="cursor:pointer;background-color:#fe92a8">取消</a>--}}
{{--                                              @endif--}}
{{--                                      </div>--}}
{{--									  </form>--}}
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

		@include('/new/partials/footer')



        <div class="blbg" onclick="gmBtn1()" style="display: none;"></div>
{{--		<div class="blbg tab_phone" onclick="gmBtn1()" style="display: none;"></div>--}}
<div class="bl bl_tab " id="tab01" style="display: none;">
    <div class="bltitle"><span>提示</span></div>
    <div class="n_blnr01 matop10">
        <div class="n_fengs" style="text-align:center;width:100%;">恭喜您</div>
        <div class="n_fengs" style="text-align:center;width:100%;">完成身份驗證囉！</div>
{{--         <div class="n_fengs" style="text-align:center">手機驗證成功--}}
{{--             <br>下一步：請用手機拍照，在10分鐘內上傳！<br>(備註：照片只給站方做認證用，認證完即可刪除，不用留存)--}}
{{--         </div>--}}
        <a class="n_bllbut matop30" id="complete_auth" onclick="gmBtn1()" style="cursor:pointer">確定</a>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb"><img src="/auth/images/gb_icon.png"></a>
</div>




{{--<div class="blbg tab_photo" onclick="gmBtn1()" style="display: none;"></div>--}}
<div class="bl bl_tab bl_tab_01" id="tab04" style="display: none;">
    <div class="bltitle_a"><span>提示</span></div>
    <div class="n_blnr02 matop10">
         <div class="n_fengs" style="text-align:center;width:100%;">恭喜您</div>
         <div class="n_fengs" style="text-align:center;width:100%;">完成身份驗證囉！</div>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb01"><img src="/auth/images/gb_icon.png"></a>
</div>

	
	
	
{{--<div class="blbg tab_checkcode_empty" onclick="gmBtn1()" style="display: none;"></div>--}}
<div class="bl bl_tab bl_tab_checkcode_empty" id="tab04" style="display: none;">
    <div class="bltitle_a"><span>提示</span></div>
    <div class="n_blnr02 matop10">
         <!-- <div class="n_fengs" style="text-align:center;width:100%;">請點選</div> -->
         <div class="n_fengs" style="text-align:center;width:100%;">請輸入手機號碼及獲取驗證碼取得驗證碼</div>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb01"><img src="/auth/images/gb_icon.png"></a>
</div>



{{--<div class="blbg tab_error_checkcode" onclick="gmBtn1()" style="display: none;"></div>--}}
<div class="bl bl_tab bl_tab_error_checkcode" id="tab04" style="display: none;">
    <div class="bltitle_a"><span>提示</span></div>
    <div class="n_blnr02 matop10">
         <!-- <div class="n_fengs" style="text-align:center;width:100%;">請點選</div> -->
         <div class="n_fengs" style="text-align:center;width:100%;">請輸入正確的驗證碼</div>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb01"><img src="/auth/images/gb_icon.png"></a>
</div>



{{--<div class="blbg tab_auth_success" onclick="gmBtn1()" style="display: none;"></div>--}}
<div class="bl bl_tab bl_tab_auth_success" id="tab04" style="display: none;">
    <div class="bltitle_a"><span>提示</span></div>
    <div class="n_blnr02 matop10">
         <!-- <div class="n_fengs" style="text-align:center;width:100%;">請點選</div> -->
         <div class="n_fengs" style="text-align:center;width:100%;">驗證成功，3秒後將導回個人資料頁面</div>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb01"><img src="/auth/images/gb_icon.png"></a>
</div>

{{--<div class="blbg tab_auth_already" onclick="gmBtn1()" style="display: none;"></div>--}}
<div class="bl bl_tab bl_tab_auth_already" id="tab04" style="display: none;">
    <div class="bltitle_a"><span>提示</span></div>
    <div class="n_blnr02 matop10">
         <!-- <div class="n_fengs" style="text-align:center;width:100%;">請點選</div> -->
         <div class="n_fengs" style="text-align:center;width:100%;">驗證失敗，您已經驗證過了</div>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb01"><img src="/auth/images/gb_icon.png"></a>
</div>

{{--<div class="blbg tab_auth_fail_10" onclick="gmBtn1()" style="display: none;"></div>--}}
<div class="bl bl_tab bl_tab_auth_fail_10" id="tab04" style="display: none;">
    <div class="bltitle_a"><span>提示</span></div>
    <div class="n_blnr02 matop10">
         <!-- <div class="n_fengs" style="text-align:center;width:100%;">請點選</div> -->
         <div class="n_fengs" style="text-align:center;width:100%;">驗證失敗，請檢查圖片是否為10分鐘內拍攝的照片</div>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb01"><img src="/auth/images/gb_icon.png"></a>
</div>
	
{{--<div class="blbg tab_auth_fail" onclick="gmBtn1()" style="display: none;"></div>--}}
<div class="bl bl_tab bl_tab_auth_fail" id="tab04" style="display: none;">
    <div class="bltitle_a"><span>提示</span></div>
    <div class="n_blnr02 matop10">
         <!-- <div class="n_fengs" style="text-align:center;width:100%;">請點選</div> -->
         <div class="n_fengs" style="text-align:center;width:100%;">驗證失敗</div>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb01"><img src="/auth/images/gb_icon.png"></a>
</div>

{{--<div class="blbg tab_auth_fail_date" onclick="gmBtn1()" style="display: none;"></div>--}}
<div class="bl bl_tab bl_tab_auth_fail_date" id="tab04" style="display: none;">
    <div class="bltitle_a"><span>提示</span></div>
    <div class="n_blnr02 matop10">
         <!-- <div class="n_fengs" style="text-align:center;width:100%;">請點選</div> -->
         <div class="n_fengs" style="text-align:center;width:100%;">驗證失敗，沒有日期資訊</div>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb01"><img src="/auth/images/gb_icon.png"></a>
</div>

{{--<div class="blbg tab_has_send" onclick="gmBtn1()" style="display: none;"></div>--}}
<div class="bl bl_tab bl_tab_has_send" id="tab04" style="display: none;">
    <div class="bltitle_a"><span>提示</span></div>
    <div class="n_blnr02 matop10">
         <!-- <div class="n_fengs" style="text-align:center;width:100%;">請點選</div> -->
         <div class="n_fengs" style="text-align:center;width:100%;">已發送驗證碼至手機</div>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb01"><img src="/auth/images/gb_icon.png"></a>
</div>

{{--<div class="blbg tab_has_send_error" onclick="gmBtn1()" style="display: none;"></div>--}}
<div class="bl bl_tab bl_tab_has_send_error" id="tab04" style="display: none;">
    <div class="bltitle_a"><span>提示</span></div>
    <div class="n_blnr02 matop10">
         <!-- <div class="n_fengs" style="text-align:center;width:100%;">請點選</div> -->
         <div class="n_fengs tab_has_send_error_msg" style="text-align:center;width:100%;"></div>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb01"><img src="/auth/images/gb_icon.png"></a>
</div>


        <script src="https://cdn.jsdelivr.net/npm/exif-js"></script>
        <script>
            @if($user->isPhoneAuth())
            $("#photo").addClass('yx_font');
            $("#auth_photo2").removeClass('yx_butco');
            // $("#photo").append('<input type="file" id="imgInp">');
            $("#photo").find('input').attr('type','file').css('display','none');
            @endif
            $('.zybg_right').css('width',$('.zybg_new02').width() - $('.zybg_new').width() -1 );
            $('.xy_yanx').css('width',$('.zybg_new02').width() - $('.zybg_new').width());
            $(window).resize(function() {
                $('.zybg_right').css('width',$('.zybg_new02').width() - $('.zybg_new').width() -1 );
                $('.xy_yanx').css('width',$('.zybg_new02').width() - $('.zybg_new').width());
            });
            function cl() {
                $(".blbg").show();
                $("#tab01").show();
            }

            function c2() {
                $(".blbg").show();
                $("#tab02").show();
            }

            function gmBtn1(){
                $(".blbg").hide();
                $(".bl").hide();
            }


            $("#get_auth_code").on('click', function(){
                if($("#mobile").val().length<10){
                    $(".blbg").show();
                    $(".tab_checkcode_empty").css('display', 'block');
                    $(".bl_tab_checkcode_empty").css('display', 'block');
                }else {
                    $.ajax({
                        type: 'POST',
                        url: '/Common/get_message',
                        data: {
                            _token: '{{csrf_token()}}',
                            'mobile': $("#mobile").val(),
                        },
                        success: function (res) {
                            console.log(res);
                            res = JSON.parse(res);
                            if (res.code == '200') {
                                $(".blbg").show();
                                $(".tab_has_send").css('display', 'block');
                                $(".bl_tab_has_send").css('display', 'block');
                            } else {
                                $(".blbg").show();
                                $(".tab_has_send_error_msg").text(res.msg_info);
                                $(".tab_has_send_error").css('display', 'block');
                                $(".bl_tab_has_send_error").css('display', 'block');
                            }
                        }
                    });
                }
            });

            $("#auth_phone1").on('click', function(){
                $.ajax({
                    type: 'POST',
                    url: '/Common/checkcode_during',
                    data:{
                        _token: '{{csrf_token()}}',
                        'checkcode': $("#checkcode").val(),
                    },
                    success: function(res) {
                        res = JSON.parse(res);
                        console.log(res);
                        if(res.code=='200'){
                            console.log('123');
                            $(".blbg").show();
                            $("#tabl_phone").css('display', 'block');
                            $("#tab01").css('display','block');

                            $("#photo").addClass('yx_font');
                            $("#auth_photo").removeClass('yx_butco');
                            // $("#photo").append('<input type="file" id="imgInp">');
                            $("#photo").find('input').attr('type','file').css('display','none');
                        }else if(res.code=='600'){
                            $(".blbg").show();
                            $(".tab_error_checkcode").css('display', 'block');
                            $(".bl_tab_error_checkcode").css('display','block');
                        }else{
                            $(".blbg").show();
                            $(".tab_checkcode_empty").css('display', 'block');
                            $(".bl_tab_checkcode_empty").css('display', 'block');
                        }

                    }
                });
            });

            $("#auth_photo2").on('click', function(){
                // console.log($('#imgInp')['context']['images'][1]['src']);
                var formData = new FormData();
                var reader = $('#imgInp')['context']['images'][1]['src'];
                // alert(reader);
                // console.log($('#imgInp'));
                //判斷式
                // var image = new Image();
                //
                // image.onload = function() {
                //     EXIF.getData(reader, function(){
                //         var _dataTxt = EXIF.pretty(reader);
                //         var _dataJson = JSON.stringify(EXIF.getAllTags(reader));
                //
                //         alert(_dataTxt);
                //         // demoLog.html(_dataJson);
                //         // demoVal.val(_dataTxt);
                //     });
                // };

                var data = {
                    _token: '{{csrf_token()}}',
                    "reader": reader,
                };
                $.ajax({
                    type: 'POST',
                    url: '/Common/save_img',
                    // cache: false,
                    // contentType: false,
                    // processData: false,
                    data:data,
                    success: function(res) {
                        alert(res);
                        res = JSON.parse(res);
                        // alert(res);
                        if(res.code=='200'){
                            // 	$("#tabl_phone").css('display', 'block');
                            // 	$("#tab01").css('display','block');

                            // 	$("#photo").addClass('yx_font');
                            // 	$("#auth_photo").removeClass('yx_butco');
                            $(".blbg").show();
                            $(".tab_auth_success").css('display', 'block');
                            $(".bl_tab_auth_success").css('display','block');
                            setTimeout("window.location.href='/dashboard';",3000);

                        }else if(res.code=='201'){
                            $(".blbg").show();
                            $(".tab_auth_already").css('display', 'block');
                            $(".bl_tab_auth_already").css('display','block');
                        }else if(res.code=='400'){
                            $(".blbg").show();
                            $(".tab_auth_fail_10").css('display', 'block');
                            $(".bl_tab_auth_fail_10").css('display','block');
                        }else if(res.code=='404'){
                            $(".blbg").show();
                            $(".tab_auth_fail").css('display', 'block');
                            $(".bl_tab_auth_fail").css('display','block');
                        }else if(res.code=='600'){
                            // alert(res.msg);
                            $(".blbg").show();
                            $(".tab_auth_fail_date").css('display', 'block');
                            $(".bl_tab_auth_fail_date").css('display','block');
                        }

                    }
                });
            });

            // $("#auth_photo").on('click', function(){
            // 	$("#auth_all").submit();
            // });
            // window.onload=getExif;
            function readURL(input) {
                // var Orientation = null;
                if (input.files && input.files[0]) {

                    // EXIF.getData(input.files[0], function () {
                    //     EXIF.getAllTags(input.files[0]);
                    //     Orientation = EXIF.getTag(input.files[0], 'DateTimeOriginal');
                    // });
                    // alert(Orientation);
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#prev_img').attr('src', e.target.result).addClass('upload_width');
                        //   $(".delBtn").css('display','block');
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#imgInp").change(function() {
                readURL(this);
            });
            $(".delBtn").click(function(){
                $("#prev_img").attr('src','/auth/images/photo_01.png').removeClass('upload_width');
            });

            $("#prev_img").click(function () {
                $("#imgInp").trigger('click');
            });

            $('#complete_auth').click(function () {
                location.reload();
            });
            $(document).ready(function(){
                $(".bot").addClass('chbottom');
            });

        </script>

	</body>








</html>


