@extends('new.layouts.website')
@section('style')
	<link rel="stylesheet" href="/posts/css/style.css">
	<script src="/posts/js/jquery-2.1.1.min.js" type="text/javascript"></script>
	<script src="/posts/js/bootstrap.min.js"></script>
@endsection
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
{{--						<a href="{{url()->previous()}}" class="toug_back"><img src="/posts/images/back_icon.png">返回</a>--}}
					</div>
					<div class="two_tg">
						<div class="tow_input">
							<form action="/dashboard/doForumPosts" id="posts" method="POST">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="type" value="main">
								<input type="hidden" name="action" value="update">
								<input type="hidden" name="id" value="{{ $postInfo->id }}">
								<input type="hidden" name="redirect_path" value="{{  url()->previous() }}">
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
@section('javascript')
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
@endsection