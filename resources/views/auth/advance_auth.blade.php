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
							<!--<h3 style="line-height:1.2;">請用您的智慧型手機<br>進行本人的身份驗證</h3>-->
						</div>


						<div class="vipbongn new_wyz">
							<h2>驗證說明</h2>
							<h3>您好，這是花園網的進階認證，需要輸入正確資訊才能通過驗證，您輸入的個人資訊只供本站驗證用，不做其他用途。</h3>
						</div>
						<div class="de_input">
							
							@if($user->isAdvanceAuth())
								<div>已完成驗證，<a href="{!! url('dashboard') !!}" class="red">按此開始使用網站</a></div>
							@else
								<form id="advance_auth_form" class="m-form m-form--fit" method="POST" action="/advance_auth_process">
									<input type="hidden" name="_token" value="{{ csrf_token() }}" >
									<input type="hidden" name="userId" value="{{$user->id}}">

									<div class="de_input mtb20">
										<div class="zybg_new02">
											<input name="id_serial" id="id_serial" type="text" class="xy_input xy_left wbd" placeholder="請輸入身分證字號">
										</div>
										<div class="zybg_new02">
											<select name="phone_type" class="zy_select">
												<option>台灣</option>
											</select>
											<input name="phone_number" id="phone_number" type="text" class="xy_input xy_left" placeholder="請輸入手機號碼">
										</div>

										<div class="zybg_new02">
											<em>生日</em>
											<input name="birth" id="birth" type="date" class="zy_select w100" />
										</div>

										<button type="text" class="n_zybg_right btn_yz advanceAuthSubmit">驗證</button>
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


<!--弹出-->
<div class="blbg" onclick="gmBtn1()" ></div>
<div class="bl bl_tab " id="tab01" >
	<div class="bltitle" style="margin-top: -1px;"><span>提示</span></div>
	<div class="n_blnr01 matop10">
		<div class="n_fengs" >
			<p>● 您選擇的是一次性支付。金額是30 元。</p>
			<p>● 您申請 完成繳費後，經確認繳費程序完成，則將通過本站身分認證，開啟本站相關服務設定，即視同您已經開始使用所購買之本站服務。</p>
			<p>● 刷卡後將不予退費。</p>
			<p>如同意以上所有條款請點確認，不同意點取消</p>
		</div>
		<div class="n_bbutton">
			<span><a class="n_left" onclick="gmBtn1()">確定</a></span>
			<span><a class="n_right" onclick="gmBtn1()">取消</a></span>
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
    }
</script>

