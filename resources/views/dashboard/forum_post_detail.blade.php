@extends('new.layouts.website')
@section('style')
	<script src="/js/app_1.js" type="text/javascript"></script>
	<link rel="stylesheet" href="/posts/css/style.css">
	<link rel="stylesheet" href="/posts/css/iconfont.css">
	<style>
		.toug_back:hover{ color:white !important; text-decoration:none !important}

		img{
			width: auto;
			height: auto;
			max-width: 100%;
			max-height: 100%;
		}
		.show{
			margin-top: unset !important;
		}
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
						<a href="/dashboard/forum_personal/{{$postDetail->forum_id}}" class="toug_back btn_img">
							<div class="btn_back"></div>
						</a>
					</div>
					<div class="t_xqheight">
						<div class="toug_xq" style="position: relative; {{ $postDetail->uid==1049 ? 'background:#ddf3ff;' : ''}}">
							<div class="tougao_xnew">
								<a href="/dashboard/viewuser/{{$postDetail->uid}}">
									<div class="tou_img_1">
										<div class="tou_tx_img"><img src="@if(file_exists( public_path().$postDetail->umpic ) && $postDetail->umpic != ""){{$postDetail->umpic}} @elseif($postDetail->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
										<span>{{ $postDetail->uname }}<i class="tou_fi">{{ date('Y-m-d H:i',strtotime($postDetail->pcreated_at)) }}</i></span>
									</div>
								</a>
							</div>
							@if(auth()->user()->id == 1049 || $postDetail->uid == auth()->user()->id || $forum->user_id == auth()->user()->id)
								<div class="ap_butnew" style="margin-top: 10px; margin-right:10px;">
									<a onclick="postDelete({{ $postDetail->pid }})" class="sc_cc"><img src="/posts/images/del_03n.png">刪除</a>
									<a id="repostLink" href="/dashboard/forumPostsEdit/{{ $postDetail->pid }}/all" class="sc_cc"><img src="/posts/images/xiugai.png">修改</a>
								</div>
							@endif
							<div id="ptitle" class="xq_text">{{ $postDetail->ptitle }}</div>
							<div id="pcontents" class="xq_text01">{!! \App\Models\Posts::showContent($postDetail->pcontents) !!}</div>
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
						@if(count($replyDetail)>0)
							<div class="tgxq_nr bot_tgbot70">
								@foreach($replyDetail as $reply)
									<li class="{{ $loop->iteration >5 ? 'moreReplyHide' :''}}"   style="{{ $loop->iteration >5? 'display:none' : '' }}">
										<div class="{{ $reply->uid==1049 ?'adminReply':'' }}" style="width: 100%;float: right; padding-top: 18px;">
										<a href="/dashboard/viewuser/{{$reply->uid}}">
											<div class="tg_imgtx"><img src="@if(file_exists( public_path().$reply->umpic ) && $reply->umpic != ""){{$reply->umpic}} @elseif($reply->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
										</a>
										<div class="ta_rightnr">
											<div class="ta_nr">
												<h2><a href="/dashboard/viewuser/{{$reply->uid}}">{{ $reply->uname }}</a><font>{{ date('Y-m-d H:i',strtotime($reply->pcreated_at)) }}</font></h2>
												<div class="dropdown" style="right:10px;">
													<div class="dropdown-toggle pd_dd01" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true">
														<span class="iconfont icon-sandian"></span>
													</div>
													<div class="dropdown-menu dp_hxx" aria-labelledby="dropdownMenuButton">
														<a class="dropdown-item" onclick="postReply('{{ $reply->pid }}','{{ $reply->uname }}','{{ $reply->uid }}');">@ 回覆</a>
														@if(auth()->user()->id == 1049 || $reply->uid == auth()->user()->id)
															<a class="dropdown-item" href="/dashboard/forumPostsEdit/{{ $reply->pid }}/contents"><span class="iconfont icon-xiugai_nn"></span>修改</a>
															<a class="dropdown-item" onclick="postDelete({{ $reply->pid }})"><span class="iconfont icon-lajitong"></span>刪除</a>
														@endif
													</div>
												</div>
												<p>{!! \App\Models\Posts::showContent($reply->pcontents) !!}</p>
											</div>

										</div>
										</div>
										<!--  -->
										@php
											$subDetails = \App\Models\ForumPosts::selectraw('users.id as uid, users.name as uname, users.engroup as uengroup, forum_posts.tag_user_id as tagid, forum_posts.is_anonymous as panonymous, forum_posts.views as uviews, user_meta.pic as umpic, forum_posts.id as pid, forum_posts.title as ptitle, forum_posts.contents as pcontents, forum_posts.updated_at as pupdated_at,  forum_posts.created_at as pcreated_at')
														->LeftJoin('users', 'users.id','=','forum_posts.user_id')
														->join('user_meta', 'users.id','=','user_meta.user_id')
														->where('forum_posts.reply_id', $reply->pid)->get();
										@endphp
										@if(count($subDetails)>0)
											<div class="tw_bgxx" @if(count($subDetails)>1) style="padding: unset;margin-bottom: 18px;" @else  style="margin-bottom: 18px;" @endif>
												@foreach($subDetails as $key => $subReply)
													<div @if($subReply->uid==1049) style="background-color: #ddf3ff" @endif  class="{{ count($subDetails)>1 && $key>0 ? 'needToHide_'.$reply->pid :'' }}" @if(count($subDetails)>1 && $key>0) hidden @endif>
														<div id="more_{{ $reply->pid }}_{{$key}}" class="more_{{ $reply->pid }} {{ count($subDetails)>1 ? 'two_hf':'' }}" @if(count($subDetails)>1) style="margin-top: 0px; padding:5px 10px; padding-top:10px;" @endif>
															<a href="/dashboard/viewuser/{{$subReply->uid}}"><div class="two_tetx"><img src="@if(file_exists( public_path().$subReply->umpic ) && $subReply->umpic != ""){{$subReply->umpic}} @elseif($subReply->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div></a>
															<div class="two_ta_rightnr">
																<div class="two_ta_nr">
																	<h2><a href="/dashboard/viewuser/{{$subReply->uid}}">{{ $subReply->uname }}</a><font>{{ date('Y-m-d H:i',strtotime($subReply->pcreated_at)) }}</font></h2>
																	<div class="dropdown">
																		<div class="dropdown-toggle pd_dd01" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true">
																			<span class="iconfont icon-sandian"></span>
																		</div>
																		<div class="dropdown-menu dp_hxx" aria-labelledby="dropdownMenuButton">
																			<a class="dropdown-item" onclick="postReply('{{ $reply->pid }}','{{ $subReply->uname }}','{{ $subReply->uid }}');">@ 回覆</a>
																			@if($subReply->uid == auth()->user()->id)
																				<a class="dropdown-item" href="/dashboard/forumPostsEdit/{{ $subReply->pid }}/contents"><span class="iconfont icon-xiugai_nn"></span>修改</a>
																				<a class="dropdown-item" onclick="postDelete({{ $subReply->pid }})"><span class="iconfont icon-lajitong"></span>刪除</a>
																			@endif
																		</div>
																	</div>
																	@php
																		if($subReply->tagid){
																			$tagUser=\App\Models\User::find($subReply->tagid);
																			$tag_userid=$tagUser->id;
																			$tag_username=$tagUser->name;
																		}
																		else{
																			$tag_userid=$reply->uid;
																			$tag_username=$reply->uname;
																		}
																	@endphp
																	<p style="word-break: break-all;"><a href="/dashboard/viewuser/{{$tag_userid}}"><span class="blue">{{ $tag_username }}</span></a> {!! \App\Models\Posts::showContent($subReply->pcontents) !!}</p>
																</div>
															</div>
														</div>
													</div>
													@if(count($subDetails)==1 && $subReply->uid==1049)
														<script>
															$('#more_'+'{{ $reply->pid }}_' +'{{ $key }}').parent().parent().css('background-color','#ddf3ff');
														</script>
													@endif
												@endforeach
												@if(count($subDetails)>1)
													<a href="javascript:show({{ $reply->pid }});" id="btn_{{ $reply->pid }}" class="left but_m" style="width: 100%;padding: 6px 10px; {{ $key>0 ? 'hidden':'' }}">展開更多></a>
												@endif
											</div>
										@endif
										<!--  -->
									</li>
								@endforeach
								@if(count($replyDetail)>5)
									<div class="title_dk" onclick="f()"><span class="triangle triangle-top"><i class="tr_l">顯示更早的留言</i></span></div>
								@endif
								<script>
									function f() {
										document.getElementsByClassName('triangle')[0].classList.toggle('triangle-top');
										document.getElementsByClassName('triangle')[0].classList.toggle('triangle-bottom');
										if($(".moreReplyHide").css('display') =='table'){
											$(".moreReplyHide").css('display','none');
											$(".tr_l").text('顯示更早的留言');
										}else{
											$(".moreReplyHide").css('display','table');
											$(".tr_l").text('顯示較少的留言');
										}
									}
								</script>
							</div>
						@else
							<div class="wtl bot_tgbot70">
								<img src="/posts/images/wtl.png">
								<font>無留言回復</font>
							</div>
						@endif
						<!--  -->
					</div>
				</div>
			</div>
		</div>
		<div class="botfasont">
			<div class="container">
				<div class="row">
					<div class="col-sm-12 col-xs-12 col-md-10" style=" float: right ;">
						<div class="bot_wid">
							<div class="bot_wid_nr">
								<form action="/dashboard/forum_posts_reply" id="posts" method="POST">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
									<input type="hidden" name="forum_id" value="{{ $postDetail->forum_id }}">
									<input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
									<input type="hidden" name="reply_id" id ="reply_id" value="{{ $postDetail->pid }}">
									<input type="hidden" name="tag_user_id" id ="tag_user_id" value="">
									<div class="bot_nnew">
										<div id="tagAcc" class="blue" style=" padding-top: 2px; margin-left:8px;"></div>
										<textarea id="contents" name="contents" rows="1" class="select_xx05 bot_input" placeholder="回應此篇文章"></textarea>
									</div>
									<button id="response_send" type="submit" class="bot_cir_1" style="border: none;"></button>
									{{--<button id="response_send" type="submit" class="bot_cir" style="border: none;"><i class="iconfont icon-fasong bot_fs"></i></button>--}}
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!--弹框-->
		<div class="blbg_new" onclick="gmBtn1()" style="display: none;"></div>
		<div class="bl bl_tab bl_tab_01" id="tab_title" style="display: none;">
			<div class="bltitle_a"><span class="font14" style="text-align:center;float:none !important">提示</span></div>
			<div class="n_blnr02 matop10">
				<div class="n_fengs" style="text-align:center;width:100%;">{{ Session::get('message') }}.ddd</div>
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
					url: '/dashboard/forum_posts_delete?{{ csrf_token() }}={{now()->timestamp}} ',
					method: 'POST',
					data: {
						_token: "{{ csrf_token() }}",
						'user_id': "{{ auth()->user()->id }}",
						'pid': pid,
						'fid': {{$postDetail->forum_id}}
					},
					dataType: 'json',
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

		$(document).on('click','.announce_bg, #tab05',function() {
			window.location.reload();
		});

		$('#response_send').on('click', function() {

			var checkUserVip='{{ $checkUserVip }}';
			var checkProhibit='{{ $user->prohibit_posts }}';
			var checkAccess='{{ $user->access_posts }}';
			if(checkUserVip==0) {
				c5('此功能目前開放給連續兩個月以上的VIP會員使用');
				return false;
			}else if(checkProhibit==1){
				c5('您好，您目前被站方禁止發言，若有疑問請點右下角，聯繫站長Line@');
				return false;
			}else if(checkAccess==1){
				c5('您好，您目前被站方限制使用討論區，若有疑問請點右下角，聯繫站長Line@');
				return false;
			}else{
				var form = $(this).closest("form");
				if($("#contents").val().length ==0){
					c5('請輸入文字再送出');
					return false;
				}
				form.submit();
			}
		});

		$('.bot_wid_nr').on('click', function() {

			var checkUserVip='{{ $checkUserVip }}';
			var checkProhibit='{{ $user->prohibit_posts }}';
			var checkAccess='{{ $user->access_posts }}';
			if(checkUserVip==0) {
				c5('此功能目前開放給連續兩個月以上的VIP會員使用');
				return false;
			}else if(checkProhibit==1){
				c5('您好，您目前被站方禁止發言，若有疑問請點右下角，聯繫站長Line@');
				return false;
			}else if(checkAccess==1){
				c5('您好，您目前被站方限制使用討論區，若有疑問請點右下角，聯繫站長Line@');
				return false;
			}
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

	</script>
@endsection