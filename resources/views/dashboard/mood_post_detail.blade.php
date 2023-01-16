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
	<style>
		.zap_photo>li{width: 23%;}
		.zap_photo_bb>li{
			height: 150px;
			background: #e1e1e1;
			border: none;
		}
		.zap_photo_aa>li{
			height: 150px;
			background: #e1e1e1;
			border: none;
		}
		.bot_nnew {
			width: calc(100% - 92px);
		}

		/*.blbg_new_2{width:100%; height:100%;position: fixed;top: 0px;left: 0;background: rgba(0,0,0,0.5);z-index: 1;display:none;}*/
		.blbg_new_2{width:100%; height:100%;width: 100%;height: 100%;position: fixed;top: 0px;left: 0;background: rgba(0,0,0,0.5);z-index: 9;display:none;}

		@media (max-width: 450px){
			.new_pot001, .new_po000{
				height: 260px;
			}
		}
		#images_upload2{
			display: block;
			position:absolute;
			margin-top:-20px;
			margin-left: -31px;
		}
		@media (max-width: 450px){
			#images_upload2{
				margin-left: -23px;
			}
		}

		.fileuploader-theme-thumbnails .fileuploader-items .fileuploader-items-list{
			margin: -16px 0 0 -16px;
			overflow: auto;
			max-height: 70vh;
			width: 100%;
			margin-left: -9px;
		}
		.fileuploader-theme-thumbnails{
			background: white !important;
		}
		.fileuploader-items{
			margin-bottom: -15px;
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
						<span>心情文章</span>
						<font>Article on Mood</font>
						@php
							$previous_url='/dashboard/viewuser_vvip/'.$postDetail->uid;
							if(str_contains($_SERVER['HTTP_REFERER'], 'viewuser_vvip/')){
								$previous_url= $previous_url.'#moodArticle_'.$postDetail->pid;
								session()->put('viewuser_vvip_page_position', 'moodArticle_'.$postDetail->pid);
							}
							else{
								session()->forget('viewuser_vvip_page_position');
							}
						@endphp
						<a href="{{ $previous_url }}" class="toug_back btn_img">
							<div class="btn_back"></div>
						</a>
						{{--						<a href="{{url()->previous()}}" class="toug_back"><img src="/posts/images/back_icon.png">返回</a>--}}
					</div>
					<div class="t_xqheight">
						<div class="toug_xq" style="position: relative; {{ $postDetail->uid==1049 ? 'background:#ddf3ff;' : ''}} @if($postDetail->top==1) background:#ffcf869e !important; @endif">
							<div class="tougao_xnew">
								<a href="/dashboard/viewuser/{{$postDetail->uid}}">
									<div class="tou_img_1">
										<div class="tou_tx_img"><img src="@if(file_exists( public_path().$postDetail->umpic ) && $postDetail->umpic != ""){{$postDetail->umpic}} @elseif($postDetail->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
										<span>{{ $postDetail->uname }}<i class="tou_fi">{{ date('Y-m-d H:i',strtotime($postDetail->pcreated_at)) }}</i></span>
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
										<a onclick="postDelete({{ $postDetail->pid }})" class="sc_cc"><img src="/posts/images/del_03n.png">刪除</a>
										<a id="repostLink" href="/mood/postsEdit/{{ $postDetail->pid }}/all" class="sc_cc"><img src="/posts/images/xiugai.png">修改</a>
									@endif
									@if($postDetail->pdeleted_at != null && auth()->user()->id == 1049)
										<a onclick="recover_post({{ $postDetail->pid }});" class="sc_cc">回復文章</a>
									@endif
								</div>
							@endif
							<div id="ptitle" class="xq_text" style="word-break: break-all;">{{ $postDetail->ptitle }}</div>
							<div id="pcontents" class="xq_text01">{!! \App\Models\Posts::showContent($postDetail->pcontents) !!}</div>
							<ul class="zap_photo zap_photo_bb" style="margin-top: 10px;">
								@if($postDetail->pimages)
									@foreach(json_decode($postDetail->pimages, true) as $key => $image)
										@if($key<3 ||($key==3 && count(json_decode($postDetail->pimages, true))==4))
											<li><img src="{{ $image }}"></li>
										@elseif($key==3)
											<li><img src="{{ $image }}"> <em>+{{ count(json_decode($postDetail->pimages, true))-4 }}</em></li>
										@elseif($loop->iteration >=4)
											<li style="display: none;"><img src="{{ $image }}"></li>
										@endif
									@endforeach
								@endif
							</ul>
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
														@if(auth()->user()->id ==1049 || $reply->uid == auth()->user()->id)
															<a class="dropdown-item" href="/mood/postsEdit/{{ $reply->pid }}/contents"><span class="iconfont icon-xiugai_nn"></span>修改</a>
															<a class="dropdown-item" onclick="postDelete({{ $reply->pid }})"><span class="iconfont icon-lajitong"></span>刪除</a>
														@endif
													</div>
												</div>
												<p>{!! \App\Models\Posts::showContent($reply->pcontents) !!}</p>
												<div class="zap_bb zapbot">
													<ul class="zap_photo zap_photo_aa">
														@if($reply->pimages)
															@foreach(json_decode($reply->pimages, true) as $key => $image)
																@if($key<3 ||($key==3 && count(json_decode($reply->pimages, true))==4))
																	<li><img src="{{ $image }}"></li>
																@elseif($key==3)
																	<li><img src="{{ $image }}"> <em>+{{ count(json_decode($reply->pimages, true))-4 }}</em></li>
																@elseif($loop->iteration >=4)
																	<li style="display: none;"><img src="{{ $image }}"></li>
																@endif
															@endforeach
														@endif
													</ul>
												</div>
											</div>
										</div>
										</div>
										<!--  -->
										@php
											$subDetails = \App\Models\PostsMood::selectraw('users.id as uid, users.name as uname, users.engroup as uengroup, posts_mood.tag_user_id as tagid, posts_mood.is_anonymous as panonymous, posts_mood.views as uviews, user_meta.pic as umpic, posts_mood.id as pid, posts_mood.title as ptitle, posts_mood.contents as pcontents, posts_mood.updated_at as pupdated_at,  posts_mood.created_at as pcreated_at')
														->LeftJoin('users', 'users.id','=','posts_mood.user_id')
														->join('user_meta', 'users.id','=','user_meta.user_id')
														->where('posts_mood.reply_id', $reply->pid)->get();
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
																			@if(auth()->user()->id == 1049 || $subReply->uid == auth()->user()->id)
																				<a class="dropdown-item" href="/mood/postsEdit/{{ $subReply->pid }}/contents"><span class="iconfont icon-xiugai_nn"></span>修改</a>
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
																	<p style="word-break: break-all;">
																		<a href="/dashboard/viewuser/{{$tag_userid}}">
																			<span class="blue">{{ $tag_username }}</span>
																		</a> {!! \App\Models\PostsMood::showContent($subReply->pcontents) !!}
																	</p>
																	<div class="zap_bb zapbot">
																		<ul class="zap_photo zap_photo_aa">
																			@if($reply->pimages)
																				@foreach(json_decode($reply->pimages, true) as $key => $image)
																					@if($key<3 ||($key==3 && count(json_decode($reply->pimages, true))==4))
																						<li><img src="{{ $image }}"></li>
																					@elseif($key==3)
																						<li><img src="{{ $image }}"> <em>+{{ count(json_decode($reply->pimages, true))-4 }}</em></li>
																					@elseif($loop->iteration >=4)
																						<li style="display: none;"><img src="{{ $image }}"></li>
																					@endif
																				@endforeach
																			@endif
																		</ul>
																	</div>
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
		{{--<form action="/mood/posts_reply" id="posts" method="POST" enctype="multipart/form-data">
		<div class="botfasont">
			<div class="container">
				<div class="row">
					<div class="col-sm-12 col-xs-12 col-md-10" style=" float: right ;">
						<div class="bot_wid">
							<div class="bot_wid_nr">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
								<input type="hidden" name="article_id" value="{{ $postDetail->pid }}">
								<input type="hidden" name="reply_id" id ="reply_id" value="{{ $postDetail->pid }}">
								<input type="hidden" name="tag_user_id" id ="tag_user_id" value="">
								<div id="images_upload" class="bot_cirleft" style="border: none;margin-right:6px;"></div>
								<div class="bot_nnew">
									<div id="tagAcc" class="blue" style=" padding-top: 2px; margin-left:8px;"></div>
									<textarea id="contents" name="contents" rows="1" class="select_xx05 bot_input" placeholder="回應此篇文章"></textarea>
								</div>
								<button id="response_send" type="submit" class="bot_cir_1" style="border: none;"></button>
								<input type="file" id="file" name="images" style="display: none;" data-fileuploader-files='' multiple accept=".png, .jpg, .jpeg">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		</form>--}}

		<!--弹框-->
		<div class="blbg_new" onclick="gmBtn1()" style="display: none;"></div>
		<div class="bl bl_tab bl_tab_01" id="tab_title" style="display: none;">
			<div class="bltitle_a"><span class="font14" style="text-align:center;float:none !important">提示</span></div>
			<div class="n_blnr02 matop10">
				<div class="n_fengs" style="text-align:center;width:100%;">{{ Session::get('message') }}</div>
			</div>
			<a onclick="gmBtn1()" class="bl_gb01"><img src="/posts/images/gb_icon.png"></a>
		</div>

		<!--照片查看-->
		<div class="big_img">
			<!-- 自定义分页器 -->
			<div class="swiper-num">
				<span class="active"></span>/
				<span class="total"></span>
			</div>
			<div class="swiper-container2">
				<div class="swiper-wrapper">
				</div>
			</div>
			<div class="swiper-pagination2"></div>

		</div>
		<link type="text/css" rel="stylesheet" href="/new/css/app.css">
		<link rel="stylesheet" type="text/css" href="/new/css/swiper.min.css" />
		<script type="text/javascript" src="/new/js/swiper.min.js"></script>
		<script>
			$(document).ready(function () {
				/*调起大图 S*/
				var mySwiper = new Swiper('.swiper-container2',{
					pagination : '.swiper-pagination2',
					paginationClickable:true,
					onInit: function(swiper){//Swiper初始化了
						// var total = swiper.bullets.length;
						var active =swiper.activeIndex;
						$(".swiper-num .active").text(active);
						// $(".swiper-num .total").text(total);
					},
					onSlideChangeEnd: function(swiper){
						var active =swiper.realIndex +1;
						$(".swiper-num .active").text(active);
					}
				});

				$(".zap_photo li").on("click",
						function () {
							var imgBox = $(this).parent(".zap_photo").find("li");
							var i = $(imgBox).index(this);
							$(".big_img .swiper-wrapper").html("")

							for (var j = 0, c = imgBox.length; j < c ; j++) {
								$(".big_img .swiper-wrapper").append('<div class="swiper-slide"><div class="cell"><img src="' + imgBox.eq(j).find("img").attr("src") + '" / ></div></div>');
							}
							mySwiper.updateSlidesSize();
							mySwiper.updatePagination();
							$(".big_img").css({
								"z-index": 1001,
								"opacity": "1"
							});
							//分页器
							var num = $(".swiper-pagination2 span").length;
							$(".swiper-num .total").text(num);
							// var active =$(".swiper-pagination2").index(".swiper-pagination-bullet-active");
							$(".swiper-num .active").text(i + 1);
							// console.log(active)

							mySwiper.slideTo(i, 0, false);
							return false;
						});
				$(".swiper-container2").click(function(){
					$(this).parent(".big_img").css({
						"z-index": "-1",
						"opacity": "0"
					});
				});

			});
			/*调起大图 E*/
		</script>
		<script>
			function show_images_upload(){
				$(".blbg_new_2").show();
				$("#show_images_upload").show();
			}
			function show_images_upload_close(){
				$(".blbg_new_2").hide();
				$("#show_images_upload").hide();
			}

			function readyNumber() {

				$('textarea').each(function () {
					this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
				}).on('input', function () {
					this.style.height = 'auto';
					this.style.height = (this.scrollHeight) + 'px';

					var textAreaHeight = parseInt(this.scrollHeight);
					if($("#tagAcc").text()!==''){
						textAreaHeight = parseInt(this.scrollHeight+22);
					}
					if(textAreaHeight<50){
						textAreaHeight=50;
					}
					$(".bot_nnew").css('height',textAreaHeight + 'px');
					$("#response_send").css('margin-top',(textAreaHeight-34) + 'px');
					$("#images_upload").css('margin-top',(textAreaHeight-34) + 'px');
					$("#images_upload2").css('margin-top',(textAreaHeight-60) + 'px');
				})
			}

			readyNumber();

			function gmBtn1(){
				$(".blbg_new").hide();
				$(".bl").hide();

				$(".blbg_new_2").hide();
				$('.bl_tab_aa').hide();
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
						url: '/mood/posts_delete?{{ csrf_token() }}={{now()->timestamp}} ',
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

			function recover_post(pid)
			{
				c4('確定要回復嗎?');
				$(".n_left").on('click', function() {
					$.ajax({
						url: '/mood/posts_recover?{{ csrf_token() }}={{now()->timestamp}}',
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
		</script>
@stop