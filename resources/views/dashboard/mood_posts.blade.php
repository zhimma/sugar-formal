@extends('new.layouts.website')

	<link rel="stylesheet" href="/posts/css/style.css">
	<link rel="stylesheet" href="/posts/css/font/font_n/iconfont.css">
	<link rel="stylesheet" href="/posts/css/font/iconfont.css">
	<link rel="stylesheet" href="/posts/css/taolunqu/iconfont.css">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400|700" rel="stylesheet">
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
						<a href="{{url()->previous()}}" class="toug_back btn_img">
							<div class="btn_back"></div>
						</a>
					</div>
					<div class="two_tg">
						<div class="tow_input">
							<form action="/mood/doPosts?{{ csrf_token() }}={{ \Carbon\Carbon::now()->timestamp }}" id="posts" method="POST" enctype="multipart/form-data">
								<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
								<input type="hidden" name="type" value="main">
								<input name="title" id="title" type="text" class="tw_input" placeholder="#標題">
								<textarea name="contents" id="contents" cols="" rows="" class="tw_textinput" placeholder="#内容" required></textarea>
								<div class="dengl_but matop30" onclick="send_posts_btn()" style="margin-top: 10px;">確定</div>
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