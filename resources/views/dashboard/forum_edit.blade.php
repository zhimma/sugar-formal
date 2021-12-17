@extends('new.layouts.website')

	<link rel="stylesheet" href="/posts/css/style.css">
	<script src="/posts/js/jquery-2.1.1.min.js" type="text/javascript"></script>

	@section('app-content')
		<div class="container matop70">
			<div class="row">
				<div class="col-sm-2 col-xs-2 col-md-2 dinone">
					@include('new.dashboard.panel')
				</div>
				<div class="col-sm-12 col-xs-12 col-md-10">
					<div class="shou">
						<span><img src="/posts/images/tg_15.png" style="height: 26px; margin-right: 5px; margin-bottom: 8px;">@if(!isset($forumInfo)) 新增討論區 @else 修改討論區 @endif</span>
						<font>@if(!isset($forumInfo)) Publish @else Edit @endif</font>
						<a href="@if(!isset($forumInfo)) /dashboard/forum @else {{url()->previous()}}@endif" class="toug_back btn_img">
							<div class="btn_back"></div>
{{--							<img src="/posts/images/back_icon.png">返回--}}
						</a>
					</div>
					<div class="two_tg">
						<div class="tow_input">
							<form action="/dashboard/doForum" id="posts" method="POST">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="type" value="main">
								<input type="hidden" name="action" @if(!isset($forumInfo)) value="create" @else value="update" @endif>
								<input type="hidden" name="forum_id" value="@if(isset($forumInfo)) {{$forumInfo->id}}@endif">
								<input name="title" id="title" type="text" class="tw_input" placeholder="#標題" value="{{$user->name}}" maxlength="13">
								<textarea name="sub_title" id="contents" cols="" rows="" class="tw_textinput" placeholder="#主題說明" maxlength="55" required></textarea>
								<div class="dengl_but matop30" onclick="send_posts_btn()">確定</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	@stop

<script>
	$(document).ready(function(){
		$(".tw_input").keyup(function() {
			let title = $("#title").val();
			let name = '{{$user->name}}';
			let result = title.includes(name);
			// alert(2);
			if (result==false){
				ccc('個人討論區的名稱，需以您的暱稱為開頭');
				$(".n_bllbut_tab_other").on('click', function() {
					$(".blbg").hide();
					$(".bl").hide();
					$(".gg_tab").hide();
				});
				$("#title").val(name)
			}
		});


		let maxLength = 54;
		$('#contents').on('keydown keyup change', function(){
			var char = $(this).val();
			var charLength = $(this).val().length;
			if(charLength > maxLength){
				c5('主題說明最多輸入54個字元。');
				$(this).val(char.substring(0, maxLength));
			}
		});
	});

	function send_posts_btn() {
		var title = $("#title").val();
		let name = '{{$user->name}}';
		if (title == '') {
			c5('您的標題不可以為空！');
			return false;
		}

		var content =$('#contents').val();
		if(content.length <=0 ){
			c5('您的內容不可以為空！');
			return false;
		}
		$("#posts").submit();
	}

	$(document).on('click','.blbg',function(event) {
		$(".blbg").hide();
		$(".bl").hide();
		$(".gg_tab").hide();
	});
</script>