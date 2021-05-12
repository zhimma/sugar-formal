 <!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="format-detection" content="telephone=no" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>手機驗證</title>
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
            .chbottom{ position:relative;bottom:0;}

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
    @include('new.layouts.navigation')
		<!---->
		<div class="container matop120 yzheight">
			<div class="row">
                <div class="col-sm-2 col-xs-2 col-md-2 dinone" style="margin-top: 40px !important;">
                    @include('new.dashboard.panel')
                </div>
				<div class="col-sm-12 col-xs-12 col-md-10">
                    <div class="dengl matbot140">
                        <div class="zhuce"><h2>手機驗證</h2>
{{--                           <h3 style="line-height:1.2;">請用您的智慧型手機<br>進行本人的身份驗證</h3>--}}
                        </div>
                            <div class="de_input">
{{--                                 <div class="yanzheng_1">驗證1</div>--}}
{{--                               @php--}}
{{--                                   $data = \App\Models\SimpleTables\warned_users::where('member_id', $user->id)->where(function ($query){--}}
{{--                                        $query->whereNull('expire_date')->orWhere('expire_date', '>=', \Carbon\Carbon::now());--}}
{{--}                                   )->first();--}}
{{--                                    if ($data) {--}}
{{--                                        $isAdminWarned = 1;--}}
{{--                                    } else {--}}
{{--                                        $isAdminWarned = 0;--}}
{{--                                    }--}}
{{--                               @endphp--}}
                                @if($user->isPhoneAuth() /*or $isAdminWarned*/)
                                    <div>已完成驗證，<a href="{!! url('dashboard') !!}" class="red">按此開始使用網站</a></div>
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
                                    @if($user->engroup == 1)
                                        <div class="de_input pink">
                                            <span>如果不願意採用手機認證，可以選擇透過信用卡付費 30 元認證，此費用單純做為本站註冊認證使用，並非 VIP 帳號付費，所享有權利與手機驗證相同。不同意請勿採用此方式認證，</span>
                                            <a href="#" onclick="beforeSwipeCardAlert()">請按我，進行信用卡付費。</a>
                                            <form id="mobile_verify_paymentForm" class="m-form m-form--fit" action="{{ route('mobileAutoVerify_ec') }}" method=post style="display: none;">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                                <input type="hidden" name="userId" value="{{$user->id}}">
                                                <button type="submit" class="new_gvip_input paySubmit" style="border-style: none; outline: none;">請按我，進行信用卡付費。</button>
                                            </form>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        <div class="vipbongn new_wyz">
                                <h2>驗證說明</h2>
                                @if($user->engroup == 1)
                                <h3><span class="left">●</span><var class="newtishi">手機驗證後，能加強帳號及身份的真實性。</var></h3>
                                <h3><span class="left">●</span><var class="newtishi">手機驗證能夠解除警示紀錄，假若無法解除，請點右下方站長line@詢問。</var></h3>
                                <h3><span class="left">●</span><var class="newtishi">您所提供的手機門號不會用在本站手機驗證以外的用途。</var></h3>
                                <h3><span class="left">●</span><var class="newtishi">您所提供的資料於驗證完成後會嚴加保管，方便您日後快速地重新驗證。</var></h3>
                                <h3><span class="left">●</span><var class="newtishi">請放心，驗證過程所有資料，僅用於驗證，驗證完成後刪除。</var></h3>
                                @else
                                <h3><span class="left">●</span><var class="newtishi">手機驗證後，能加強帳號及身份的真實性。</var></h3>
                                <h3><span class="left">●</span><var class="newtishi">手機驗證能夠解除警示紀錄，假若無法解除，請點右下方站長line@詢問。</var></h3>
                                <h3><span class="left">●</span><var class="newtishi">根據本站統計，daddy們對於有通過手機驗證的baby，會更主動聯絡。</var></h3>
                                <h3><span class="left">●</span><var class="newtishi">您所提供的手機門號不會用在本站手機驗證以外的用途。</var></h3>
                                <h3><span class="left">●</span><var class="newtishi">您所提供的資料於驗證完成後會嚴加保管，方便您日後快速地重新驗證。</var></h3>
                                <h3><span class="left">●</span><var class="newtishi">請放心，驗證過程所有資料，僅用於驗證，驗證完成後刪除。</var></h3>
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

<div class="blbg" onclick="gmBtn1()" ></div>
<div class="bl bl_tab " id="beforeSwipeCard" >
    <div class="bltitle"><span>提示</span></div>
    <div class="n_blnr01 matop10">
        <div class="n_fengs" >
            <p>● 您選擇的是一次性支付。金額是30 元。</p>
            <p>● 您申請 完成繳費後，經確認繳費程序完成，則將通過本站身分認證，開啟本站相關服務設定，即視同您已經開始使用所購買之本站服務。</p>
            <p>● 刷卡後將不予退費。</p>
            <p>如同意以上所有條款請點確認，不同意點取消</p>
        </div>

        <div class="n_bbutton">
            <span><a class="n_left" onclick="sendSwipeCardSummit()">確定</a></span>
            <span><a class="n_right" onclick="gmBtn1()">取消</a></span>
        </div>
    </div>
    <a  onclick="gmBtn1()" class="bl_gb"><img src="/auth/images/gb_icon.png"></a>
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
         <div class="n_fengs" style="text-align:center;width:100%;">請輸入手機號碼以獲取驗證碼</div>
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

<div class="bl bl_tab bl_tab_male_alert" id="tab04" style="display: none;">
    <div class="bltitle_a"><span>提示</span></div>
    <div class="n_blnr02 matop10">
        <!-- <div class="n_fengs" style="text-align:center;width:100%;">請點選</div> -->
        <div class="n_fengs" style="text-align:center;width:100%;">請先完成手機驗證，加強身分真實性；或請直接<a href="{!! url('dashboard/vipSelect') !!}" class="red">點此升級 VIP</a>，開始使用網站喔！</div>
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
                // $(".bot").addClass('chbottom');
            });


            @if(Session::has('message'))
                @if(Session::get('message')=='male_alert')
                    $(".blbg").show();
                    $(".bl_tab_male_alert").css('display','block');
                @endif
            <?php session()->forget('message');?>
            @endif

            function beforeSwipeCardAlert() {
                $(".blbg").show();
                $("#beforeSwipeCard").show();
            }
            function sendSwipeCardSummit() {
                $("#mobile_verify_paymentForm").submit();
            }
        </script>

	</body>








</html>


