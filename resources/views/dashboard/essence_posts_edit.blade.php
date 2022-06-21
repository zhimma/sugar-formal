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
						<span><img src="/posts/images/tg_15.png" style="height: 26px; margin-right: 5px; margin-bottom: 8px;">{{ $editType=='all' ? '修改文章' : '修改內容'}}</span>
						<font>Publish</font>
						<a href="{{url()->previous()}}" class="toug_back btn_img">
							<div class="btn_back"></div>
						</a>
						{{--<a href="{{url()->previous()}}" class="toug_back"><img src="/posts/images/back_icon.png">返回</a>--}}
					</div>
					<div class="two_tg">
						<div class="tow_input">
							<form action="/dashboard/essence_doPosts?{{ csrf_token() }}={{ \Carbon\Carbon::now()->timestamp }}" id="posts" method="POST">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="type" value="main">
								<input type="hidden" name="action" value="update">
								<input type="hidden" name="post_id" value="{{ $postInfo->id }}">
								<input type="hidden" name="redirect_path" value="{{  url()->previous() }}">
								@if(auth()->user()->id==1049)
									<div class="tw_input" >
										<label>文章類別</label>
										<select name="category" style="border: none;">
											<option value="">請選擇</option>
											<option value="1" @if($postInfo->category==1) selected @endif>教學經驗文</option>
											<option value="2" @if($postInfo->category==2) selected @endif>包養故事文</option>
											<option value="3" @if($postInfo->category==3) selected @endif>平台經驗/介紹文</option>
										</select>
									</div>
									<div class="tw_input" >
										<label>分享對象</label>
										<select name="share_with" style="border: none;">
											<option value="">請選擇</option>
											<option value="1" @if($postInfo->share_with==1) selected @endif>男會員</option>
											<option value="2" @if($postInfo->share_with==2) selected @endif>女會員</option>
											@foreach($posts as $post)
												<option value="forum_{{ $post->f_id }}" @if($postInfo->share_with=='forum_'. $post->f_id) selected @endif>{{ $post->f_title }}</option>
											@endforeach
										</select>
									</div>
								@endif
								@if($editType=='all')
									<input name="title" id="title" type="text" class="tw_input" placeholder="#標題" value="{{ $postInfo->title  }}">
								@endif
								<textarea name="contents" id="contents" cols="" rows="" class="tw_textinput" placeholder="#内容" required>{{ $postInfo->contents }}</textarea>
								<div class="dengl_but matop30" onclick="send_posts_btn()" style="margin-top: 10px;">確定</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	@stop

<script>
	function send_posts_btn() {
		var editType = '{{ $editType }}';
		if(editType=='all'){
			var title = $("#title").val();
			if (title == '') {
				c5('您的標題不可以為空！');
				return false;
			}
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