@extends('new.layouts.website')

@section('style')
	<script src="/js/app_1.js" type="text/javascript"></script>
	<!--    css-->
{{--	<link href="/posts/css/bootstrap.min.css" rel="stylesheet">--}}
{{--	<link href="/posts/css/bootstrap-theme.min.css" rel="stylesheet">--}}
	<link rel="stylesheet" href="/posts/css/style.css">
	<link rel="stylesheet" href="/posts/css/iconfont.css">
	<style>
		img{
			width: auto;
			height: auto;
			max-width: 100%;
			max-height: 100%;
		}
		.show{
			margin-top: unset !important;
		}

		.toug_back:hover{ color:white !important; text-decoration:none !important}
		.commonMenu{z-index: 10001;}
		.blbg_new{width:100%; height:100%;width: 100%;height: 100%;position: fixed;top: 0px;left: 0;background: rgba(0,0,0,0.5);z-index: 9;display:none;}
		.adminReply{
			background-color:#ddf3ff;
		}
	</style>
@endsection

@section('app-content')
		<div class="container matop70">
			<div class="row">
				<div class="col-sm-2 col-xs-2 col-md-2 dinone">
					@include('new.dashboard.panel')
				</div>
				<div class="col-sm-12 col-xs-12 col-md-10">
					<div class="shou">
						<span>文章詳情</span>
						<font>Article</font>
						<a href="{{ $goBackPage }}" class="toug_back btn_img">
							<div class="btn_back"></div>
						</a>
					</div>
					<div>
						<div class="toug_xq" style="position: relative; {{ $postDetail->uid==1049 ? 'background:#ddf3ff;' : ''}} @if($postDetail->top==1) background:#ffcf869e !important; @endif">
							<div class="tougao_xnew">
								<a href="/dashboard/viewuser/{{$postDetail->uid}}?via_by_essence_article_enter={{ $postDetail->pid }}">
									<div class="tou_img_1">
										<div class="tou_tx_img"><img src="@if(file_exists( public_path().$postDetail->umpic ) && $postDetail->umpic != ""){{$postDetail->umpic}} @elseif($postDetail->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
										<span>{{ $postDetail->uname }}<i class="tou_fi">{{ date('Y-m-d H:i',strtotime($postDetail->pupdated_at)) }}</i></span>
									</div>
								</a>
								{{--<div class="tog_time">{{ date('Y-m-d H:i',strtotime($postDetail->pcreated_at)) }}</div>--}}
							</div>
							@if(auth()->user()->id ==1049 || $postDetail->uid == auth()->user()->id)
{{--								<div class="ap_but" style="margin-top: 10px; margin-right:5px;">--}}
{{--									<a id="repostLink" href="/dashboard/postsEdit/{{ $postDetail->pid }}/all"><span class="iconfont icon-xiugai_nn"></span>修改</a>--}}
{{--									<a onclick="postDelete({{ $postDetail->pid }})"><span class="iconfont icon-lajitong"></span>刪除</a>--}}
{{--								</div>--}}
								<div class="ap_butnew" style="margin-top: 10px; margin-right:10px;">
									@if($postDetail->pdeleted_at == null)
										@if(auth()->user()->id == 1049)
											<a onclick="postVerifyStatus({{ $postDetail->pid }})" class="sc_cc verify_text">{{ $postDetail->pverify_status==0 ? '尚未審核' : ($postDetail->pverify_status==1 ? '通過':'取消通過' ) }}</a>
										@endif
										<a onclick="postDelete({{ $postDetail->pid }})" class="sc_cc"><img src="/posts/images/del_03n.png">刪除</a>
										<a id="repostLink" href="/dashboard/essence_postsEdit/{{ $postDetail->pid }}/all" class="sc_cc"><img src="/posts/images/xiugai.png">修改</a>
									@endif
									@if($postDetail->pdeleted_at != null && auth()->user()->id == 1049)
										<a onclick="recover_post({{ $postDetail->pid }});" class="sc_cc">回復文章</a>
									@endif
								</div>
							@endif
							<div id="ptitle" class="xq_text">{{ $postDetail->ptitle }}</div>
							<div id="pcontents" class="xq_text01">{!! \App\Models\Posts::showContent($postDetail->pcontents) !!}</div>
							{{--<div class="xq_textbot"><img src="/posts/images/tg_10.png"></div>--}}
						</div>
						<div class="botline_fnr" style="margin-bottom:0px;"></div>
						<!--  -->
						<style>
							.dropup,
							.dropdown {
								position: absolute;
								right: 0;
								top: 0;
							}
							.tgxq_nr li{
								padding: unset;
							}
						</style>
						<!--  -->
					</div>
				</div>
			</div>
		</div>
		{{--<div class="botfasont">
			<div class="container">
				<div class="row">
					<div class="col-sm-12 col-xs-12 col-md-10" style=" float: right ;">
						<div class="bot_wid">
							<div class="bot_wid_nr">
								<form action="/dashboard/posts_reply" id="posts" method="POST">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
									<input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
									<input type="hidden" name="article_id" value="{{ $postDetail->pid }}">
									<input type="hidden" name="reply_id" id ="reply_id" value="{{ $postDetail->pid }}">
									<input type="hidden" name="tag_user_id" id ="tag_user_id" value="">
									<div class="bot_nnew">
										<div id="tagAcc" class="blue" style=" padding-top: 2px; margin-left:8px;"></div>
										<textarea id="contents" name="contents" rows="1" class="select_xx05 bot_input" placeholder="回應此篇文章"></textarea>
									</div>
									<button id="response_send" type="submit" class="bot_cir_1" style="border: none;"></button>
									--}}{{--<button id="response_send" type="submit" class="bot_cir" style="border: none;"><i class="iconfont icon-fasong bot_fs"></i></button>--}}{{--
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>--}}

		<!--弹框-->
		<div class="blbg_new" onclick="gmBtn1()" style="display: none;"></div>
		<div class="bl bl_tab bl_tab_01" id="tab_title" style="display: none;">
			<div class="bltitle_a"><span class="font14" style="text-align:center;float:none !important">提示</span></div>
			<div class="n_blnr02 matop10">
				<div class="n_fengs" style="text-align:center;width:100%;">{{ Session::get('message') }}</div>
			</div>
			<a onclick="gmBtn1()" class="bl_gb01"><img src="/posts/images/gb_icon.png"></a>
		</div>
@stop

@section('javascript')
	<script>

		function readyNumber() {

			$('textarea').each(function () {
				this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
			}).on('input', function () {
				this.style.height = 'auto';
				this.style.height = (this.scrollHeight) + 'px';
				//alert((this.scrollHeight) + 'px');

				var textAreaHeight = parseInt(this.scrollHeight);
				if($("#tagAcc").text()!==''){
					textAreaHeight = parseInt(this.scrollHeight+22);
				}
				if(textAreaHeight<50){
					textAreaHeight=50;
				}
				$(".bot_nnew").css('height',textAreaHeight + 'px');
				$("#response_send").css('margin-top',(textAreaHeight-34) + 'px');
			})
		}

		readyNumber();

		function gmBtn1(){
			$(".blbg_new").hide();
			$(".bl").hide();
		}

		function show(index) {
			$('.needToHide_'+index).show();
			document.getElementById('btn_'+index).innerHTML = "收起更多>";
			document.getElementById('btn_'+index).href = "javascript:hide("+index+");";
		}

		function hide(index) {
			$('.needToHide_'+index).hide();
			document.getElementById('btn_'+index).innerHTML = "展開更多>";
			document.getElementById('btn_'+index).href = "javascript:show("+index+");";
		}

		function postReply(pid, tag_name, tag_user_id) {
			$('#reply_id').val(pid);
			$('#tag_user_id').val(tag_user_id);
			$('#tagAcc').text('@'+tag_name);
		}

		function postDelete(pid) {
			c4('確定要刪除嗎?');
			$(".n_left").on('click', function() {
				$.ajax({
					url: '/dashboard/essence_posts_delete?{{ csrf_token() }}={{now()->timestamp}} ',
					method: 'POST',
					data: {
						_token: "{{ csrf_token() }}",
						'user_id': "{{ auth()->user()->id }}",
						'pid': pid
					},
					dataType: 'json',
					success: function(data) {
						c5(data.msg);
						window.location.href=data.redirectTo;
					}
				});
			});
		}

		$(document).on('click','.announce_bg, #tab05',function() {
			window.location.reload();
		});

		$('#response_send').on('click', function() {

			{{--var checkUserVip='{{ $checkUserVip }}';--}}
			{{--var checkProhibit='{{ $user->prohibit_posts }}';--}}
			{{--var checkAccess='{{ $user->access_posts }}';--}}
			{{--if(checkUserVip==0) {--}}
			{{--	c5('此功能目前開放給連續兩個月以上的VIP會員使用');--}}
			{{--	return false;--}}
			{{--}else if(checkProhibit==1){--}}
			{{--	c5('您好，您目前被站方禁止發言，若有疑問請點右下角，聯繫站長Line@');--}}
			{{--	return false;--}}
			{{--}else if(checkAccess==1){--}}
			{{--	c5('您好，您目前被站方限制使用討論區，若有疑問請點右下角，聯繫站長Line@');--}}
			{{--	return false;--}}
			{{--}else{--}}
				var form = $(this).closest("form");
				if($("#contents").val().length ==0){
					c5('請輸入文字再送出');
					return false;
				}
				form.submit();
			// }
		});

		$('.bot_wid_nr').on('click', function() {

			{{--var checkUserVip='{{ $checkUserVip }}';--}}
			{{--var checkProhibit='{{ $user->prohibit_posts }}';--}}
			{{--var checkAccess='{{ $user->access_posts }}';--}}
			{{--if(checkUserVip==0) {--}}
			{{--	c5('此功能目前開放給連續兩個月以上的VIP會員使用');--}}
			{{--	return false;--}}
			{{--}else if(checkProhibit==1){--}}
			{{--	c5('您好，您目前被站方禁止發言，若有疑問請點右下角，聯繫站長Line@');--}}
			{{--	return false;--}}
			{{--}else if(checkAccess==1){--}}
			{{--	c5('您好，您目前被站方限制使用討論區，若有疑問請點右下角，聯繫站長Line@');--}}
			{{--	return false;--}}
			{{--}--}}
		});

		$(document).keydown(function (event) {
			if (event.keyCode == 8 || event.keyCode == 46) {
				if($("#contents").val().length ==0){
					$('#tagAcc').text('');
				}
			}
		});

		function reposts() {
			$('#repostLink').hide();
			$('#ptitle').hide();
			$('#pcontents').hide();
		}

		function recover_post(pid)
		{
			c4('確定要回復嗎?');
			$(".n_left").on('click', function() {
				$.ajax({
					url: '/dashboard/posts_recover?{{ csrf_token() }}={{now()->timestamp}}',
					method: 'POST',
					data: {
						_token: "{{ csrf_token() }}",
						pid: pid
					},
					success: function(data) {
						if(data.postType=='main'){
							c5(data.msg);
							window.location.href=data.redirectTo;
						}
						else
							c5(data.msg);
					}
				});
			});
		}



		function postVerifyStatus(pid) {
			var verify_str=$('.verify_text').text();
			if(verify_str=='通過' || verify_str=='尚未審核'){
				verify_str='通過審核';
			}
			c4('確定要'+verify_str+'嗎?');
			$(".n_left").on('click', function() {
				$.ajax({
					url: '/dashboard/essence_verify_status?{{ csrf_token() }}={{now()->timestamp}} ',
					method: 'POST',
					data: {
						_token: "{{ csrf_token() }}",
						'user_id': "{{ auth()->user()->id }}",
						'pid': pid
					},
					dataType: 'json',
					success: function(data) {
						c5(data.msg);
						window.reload();
					}
				});
			});
		}

	</script>
@endsection