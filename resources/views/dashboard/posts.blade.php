@extends('new.layouts.website')

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="format-detection" content="telephone=no" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title>投稿列表</title>
	<!-- Bootstrap -->
	<link href="/posts/css/bootstrap.min.css" rel="stylesheet">
	<link href="/posts/css/bootstrap-theme.min.css" rel="stylesheet">
	<!-- owl-carousel-->
	<!--    css-->
	<link rel="stylesheet" href="/posts/css/style.css">
	<link rel="stylesheet" href="/posts/css/swiper.min.css">
	<script src="/posts/js/jquery-2.1.1.min.js" type="text/javascript"></script>
	<script src="/posts/js/bootstrap.min.js"></script>

	@section('app-content')
		<div class="container matop70">
			<div class="row">
				<div class="col-sm-2 col-xs-2 col-md-2 dinone">
					@include('new.dashboard.panel')
				</div>
				<div class="col-sm-12 col-xs-12 col-md-10">
					<div class="shou">
						<span><img src="/posts/images/tg_15.png" style="height: 26px; margin-right: 5px; margin-bottom: 8px;">發表文章</span>
						<font>Publish</font>
						<a href="/dashboard/posts_list" class="toug_back"><img src="/posts/images/back_icon.png">返回</a>
					</div>
					<div class="two_tg">
						<div class="tow_input">
							<form action="/dashboard/doPosts" id="posts" method="POST">
								<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
								<input type="hidden" name="type" value="main">
								<input name="title" id="title" type="text" class="tw_input" placeholder="#標題">
								<textarea name="contents" id="contents" cols="" rows="" class="tw_textinput" placeholder="#内容"></textarea>
								{{--<div class="ti_kuang">
									<div class="ti_title">點這裡變更分身</div>
									<h2 class="matop15"><i class='input_style input_radio radio_bg'><input type="radio" name="is_anonymous" value="anonymous"></i>匿名於站內發布</h2>
									<h2><i class='input_style input_radio radio_bg'><input type="radio" name="is_anonymous" value="combine"></i>站內發布與本站帳號連結</h2>
								</div>
								<div class="ticheckbox"><i class='input_style radio_bg'><input type="checkbox" name="agreement" id="agreement"></i>同意站方匿名行銷使用</div>--}}
								<div class="dlbut matop30" onclick="send_posts_btn()">確定</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	@stop

<style>
	input[type='radio'],input[type='checkbox']{width:18px;height: 18px;vertical-align:middle;opacity: 0;}
</style>
<script>
	$(document).ready(function(){
		$('.input_radio').on('click',function(){
			check('input[type="radio"]','nradio_bg_check');
		});
		$('input[type="checkbox"]').on('click',function(){
			check('input[type="checkbox"]','checkbox_bg_check');
		});

	});
	function check(el,cl){
		$(el).each(function(){
			$(this).parent('i').removeClass(cl);

			var checked = $(this).prop('checked');
			if(checked){
				$(this).parent('i').addClass(cl);
			}
		})
	}

	function send_posts_btn() {

		var title = $("#title").val();
		if (title == '') {
			c5('您的標題不可以為空！');
			return false;
		}
		var content =$('#contents').val();
		if(content.length <=0 ){
			c5('您的內容不可以為空！');
			return false;
		}
		c5('發表成功！');
		$("#posts").submit();
	}

	$(document).on('click','.blbg',function(event) {
		$(".blbg").hide();
		$(".bl").hide();
		$(".gg_tab").hide();
	});
</script>
<style>
	.blnr{padding-bottom: 14px;}
</style>