<style>
	.toug_back:hover{ color:white !important; text-decoration:none !important}
</style>
@extends('new.layouts.website')
<meta charset="utf-8">
		<!--    css-->
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
		</style>
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
						<a href="/dashboard/posts_list" class="toug_back btn_img">
							<div class="btn_back"></div>
						</a>
						{{--						<a href="{{url()->previous()}}" class="toug_back"><img src="/posts/images/back_icon.png">返回</a>--}}
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
								{{--<div class="tog_time">{{ date('Y-m-d H:i',strtotime($postDetail->pcreated_at)) }}</div>--}}
							</div>
							@if($postDetail->uid == auth()->user()->id)
{{--								<div class="ap_but" style="margin-top: 10px; margin-right:5px;">--}}
{{--									<a id="repostLink" href="/dashboard/postsEdit/{{ $postDetail->pid }}/all"><span class="iconfont icon-xiugai_nn"></span>修改</a>--}}
{{--									<a onclick="postDelete({{ $postDetail->pid }})"><span class="iconfont icon-lajitong"></span>刪除</a>--}}
{{--								</div>--}}
								<div class="ap_butnew" style="margin-top: 10px; margin-right:10px;">
									<a onclick="postDelete({{ $postDetail->pid }})" class="sc_cc"><img src="/posts/images/del_03n.png">刪除</a>
									<a id="repostLink" href="/dashboard/postsEdit/{{ $postDetail->pid }}/all" class="sc_cc"><img src="/posts/images/xiugai.png">修改</a>
								</div>
							@endif
							<div id="ptitle" class="xq_text">{{ $postDetail->ptitle }}</div>
							<div id="pcontents" class="xq_text01">{!! \App\Models\Posts::showContent($postDetail->pcontents) !!}</div>
							{{--<div class="xq_textbot"><img src="/posts/images/tg_10.png"></div>--}}
						</div>
						<div class="botline_fnr" style="margin-bottom:0px;"></div>
						{{--<div class="tou_xq">
							<div class="touxqfont"><img src="/posts/images/ncion_13.png">瀏覽<span>{{ $postDetail->uviews }}</span></div>
						</div>--}}
						<!--  -->
						<style>
							.dropup,
							.dropdown {
								position: absolute;
								right: 0;
								top: 0;
							}
						</style>
						@if(count($replyDetail)>0)
							<div class="tgxq_nr bot_tgbot70">
								@foreach($replyDetail as $reply)
									<li>
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
														@if($reply->uid == auth()->user()->id)
															<a class="dropdown-item" href="/dashboard/postsEdit/{{ $reply->pid }}/contents"><span class="iconfont icon-xiugai_nn"></span>修改</a>
															<a class="dropdown-item" onclick="postDelete({{ $reply->pid }})"><span class="iconfont icon-lajitong"></span>刪除</a>
														@endif
													</div>
												</div>
												<p>{!! \App\Models\Posts::showContent($reply->pcontents) !!}</p>
											</div>

										</div>
										<!--  -->
										@php
											$subDetails = \App\Models\Posts::selectraw('users.id as uid, users.name as uname, users.engroup as uengroup, posts.tag_user_id as tagid, posts.is_anonymous as panonymous, posts.views as uviews, user_meta.pic as umpic, posts.id as pid, posts.title as ptitle, posts.contents as pcontents, posts.updated_at as pupdated_at,  posts.created_at as pcreated_at')
														->LeftJoin('users', 'users.id','=','posts.user_id')
														->join('user_meta', 'users.id','=','user_meta.user_id')
														->where('posts.reply_id', $reply->pid)->get();
										@endphp
										@if(count($subDetails)>0)
											<div class="tw_bgxx">
												@foreach($subDetails as $key => $subReply)
													@if($key==0)
														<div class="{{count($subDetails)>1 ? 'two_hf' : 'xxxxno_'. count($subDetails) .'_'.$reply->pid}}">
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
																				<a class="dropdown-item" href="/dashboard/postsEdit/{{ $subReply->pid }}/contents"><span class="iconfont icon-xiugai_nn"></span>修改</a>
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
																	<p><a href="/dashboard/viewuser/{{$tag_userid}}"><span class="blue">{{ $tag_username }}</span></a> {!! \App\Models\Posts::showContent($subReply->pcontents) !!}</p>
																</div>
															</div>
														</div>
													@endif
												@endforeach

												@if(count($subDetails)>1)
													<div id="more_{{ $reply->pid }}" class="more" style="display: none;">
														@foreach($subDetails as $key => $subReply)
															@if($key>=1)
																<div class="two_hf">
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
																						<a class="dropdown-item" href="/dashboard/postsEdit/{{ $subReply->pid }}/contents"><span class="iconfont icon-xiugai_nn"></span>修改</a>
																						<a class="dropdown-item" onclick="postDelete({{ $subReply->pid }});"><span class="iconfont icon-lajitong"></span>刪除</a>
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
																			<p><a href="/dashboard/viewuser/{{$tag_userid}}"><span class="blue">{{ $tag_username }}</span></a> {!! \App\Models\Posts::showContent($subReply->pcontents) !!}</p>
																		</div>
																	</div>
																</div>
															@endif
														@endforeach
													</div>
													<a href="javascript:show({{ $reply->pid }});" id="btn_{{ $reply->pid }}" class="left but_m" style="width: 100%;">展開更多></a>
												@endif
											</div>
										@endif
										<!--  -->
									</li>
								@endforeach
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
								<form action="/dashboard/posts_reply" id="posts" method="POST">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
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
				})
			}

			readyNumber();

			$(document).ready(function(){
				var showMsg = '{{ Session::has('message') }}';
				if(showMsg){
					c5('{{ Session::get("message") }}');
				}
			});

			function gmBtn1(){
				$(".blbg_new").hide();
				$(".bl").hide();
			}

			function show(index) {
				document.getElementById("more_"+index).style.display = "block";
				document.getElementById('btn_'+index).innerHTML = "收起更多>";
				document.getElementById('btn_'+index).href = "javascript:hide("+index+");";
			}

			function hide(index) {
				document.getElementById('more_'+index).style.display = 'none';
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
						url: '/dashboard/posts_delete',
						method: 'POST',
						data: {
							_token: "{{ csrf_token() }}",
							'user_id': "{{ auth()->user()->id }}",
							'pid': pid
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
<style>
	.commonMenu{z-index: 10001;}
	.blbg_new{width:100%; height:100%;width: 100%;height: 100%;position: fixed;top: 0px;left: 0;background: rgba(0,0,0,0.5);z-index: 9;display:none;}
</style>
@stop