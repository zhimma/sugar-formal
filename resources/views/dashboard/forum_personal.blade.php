@extends('new.layouts.website')
@section('style')
		<link rel="stylesheet" href="/posts/css/style.css">
		<link rel="stylesheet" href="/posts/css/font/font_n/iconfont.css">
		<link rel="stylesheet" href="/posts/css/font/iconfont.css">
		<link rel="stylesheet" href="/posts/css/taolunqu/iconfont.css">
		<link href="https://fonts.googleapis.com/css?family=Roboto:400|700" rel="stylesheet">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet"
				href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css"
		/>
{{--		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.4.0/css/bootstrap-colorpicker.min.css" integrity="sha512-m/uSzCYYP5f55d4nUi9mnY9m49I8T+GUEe4OQd3fYTpFU9CIaPazUG/f8yUkY0EWlXBJnpsA7IToT2ljMgB87Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />--}}
		<style>
			.toug_but:hover{ color:white !important; text-decoration:none !important}

			.article{
				overflow : hidden;
				text-overflow: ellipsis;
				display: -webkit-box;
				-webkit-line-clamp: 2;
				-webkit-box-orient: vertical;
			}

			@media (max-width:320px) {
				.contents{
					width: 200px !important;
				}
			}
			@media (min-width:321px) and (max-width:375px) {
				.contents{
					width:250px !important;
				}
			}
			@media (min-width:376px) and (max-width:414px) {
				.contents{
					width:300px !important;
				}
			}
			@media (min-width:415px) and (max-width:768px){
				.contents{
					width:520px !important;
				}
			}
			@media (min-width:769px) and (max-width:1024px){
				.contents{
					width:350px !important;
				}
			}
			.read-more:hover {
				color:#e44e71;
			}
			.nickname{
				display: block;
				position: absolute;
				float: left;
				top: -10px;
				color: white;
				border-radius: 10px;
				border: 1px #fe92a8;
				background-color: #fe92a8;
				padding-left: 5px;
				padding-right: 5px;
				left: 5px;
				font-size: 10px;
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


					<div class="shou" style="text-align: left; position: relative; ">
						<a href="/dashboard/forum" class="toug_back btn_img" style=" position: absolute; left: 0;">
							<div class="btn_back"></div>
						</a>
						<span style="margin: 0 auto; position: relative;line-height: 44px;padding-bottom: 3px; left: 40px; font-size: 18px;"><a href="" style="color: #fd5678;">{{$forum->title}}</a></span>
						@if($forum->user_id == $user->id)
							<a class="toug_back btn_img01 userlogo xzgn">
								<div class="btn_back">功能選單<img src="/posts/images/jiant_a.png"></div>
							</a>
							<div class="fabiao showslide" style="text-align: center;">
								<a onclick="checkUserVip();">我要發表</a>
								<a href="/dashboard/forum_manage">會員管理</a>
							</div>
						@else
							<a onclick="checkUserVip();" class="aid_but"><img style="margin-left: 10px;" src="/posts/images/tg_03.png">我要發表</a>
						@endif

					</div>
					<div class="fadeinboxs"></div>
					<script>
						$('.userlogo').click(function(){
							event.stopPropagation()
							if($(this).hasClass('')){
								$(this).removeClass('')
								$('.fadeinboxs').fadeOut()
								$('.showslide').fadeOut()
							}else{
								$(this).addClass('')
								$('.fadeinboxs').fadeIn()
								$('.showslide').fadeIn()
							}
						})
						$('body').click(function(){
							$('.showslide').fadeOut()
							$('.fadeinboxs').fadeOut()
						})

						//切换,第一个盒子和菜单默认显示

					</script>
					<!--  -->

					<div class="taol_lt">
						<!-- 聊天室 -->
						<div class="taol_tab01 sh"  style="display: table;">
							<div class="n_sq_title">
								<li class="na_se lis_sq02" onclick="shenhe()">
									<div class="n_sq_hover"><div class="n_textm"><span class="iconfont icon-liaotian1 no_cx"></span>討論區</div></div>
								</li>
								<li class="lis_sq01" onclick="shenhe1()"><span class="iconfont icon-gonggongliaotianshi-fill no_cx"></span>聊天室</li>
							</div>

							<div class="liaotian_s">
								@if(count($posts_personal_all)>0)
								@foreach( $posts_personal_all as $row)
								<li @if($row->top==1)style="background: oldlace;"@endif @if($row->deleted_by != null) class="huis_01" @elseif($row->forum_status==0) @endif>
									<a @if($user->id == 1049 || $row->deleted_by == null)
										   @if($forum->user_id != $user->id && $user->id != 1049 && $checkForumMangeStatus->forum_status==0) onclick="view_alert()"
										   @else href="/dashboard/forum_post_detail/{{$row->pid}}"
										   @endif
									   @else onclick="delete_alert()"
									   @endif>
										<div class="ta_icon"><img src="/posts/images/tl_icon.png">{{$row->posts_reply_num}}</div>
										<div class="alit_font">{{$row->ptitle}}</div>
										<div class="alit_font01">
											<div class="ta_textbot">{{$row->pcontents}}</div>
											<div class="ta_tetime"><span class="iconfont icon-shijian"></span>{{ substr($row->pupdated_at,0,10)}}</div>
										</div>
									</a>
								</li>
								@endforeach
								@else
									<li style="text-align: center;">
										<img src="/posts/images/bianji.png" class="feng_img"><span>尚無資料</span>
									</li>
								@endif

								@if($posts_personal_all->hasPages())
								<div class="fenye ba_but" style="margin-bottom: 0;">
									{{ $posts_personal_all->links('pagination::sg-pages2') }}
								</div>
								@endif
							</div>

						</div>
						<!-- 结束 -->
						<div class="taol_tab01 sh1"  style="display:none;">
							<div class="n_sq_title">
								<li class="lis_sq03" onclick="shenhe()">
									<span class="iconfont icon-liaotian1 no_cx n_el30"></span>討論區
								</li>
								<li class="na_se lis_sq04" onclick="shenhe1()">
									<div class="n_sq_hover01"><span class="iconfont icon-gonggongliaotianshi-fill no_cx ba_wl10"></span>聊天室</div>
								</li>
							</div>
							@php
                                $forum_id = $forum->id;
                                $color = $lastest_color;
							@endphp
							<div class="tao_qu" style="overflow: unset;">

								@if($forum->user_id == $user->id || $checkForumMangeStatus->chat_status != 0)
									<div style="overflow: auto; position: relative; max-height: 580px;">
										<livewire:forum-chat-show :forum_id="$forum_id"/>
									</div>
								@else
											您目前尚無聊天室權限
								@endif

							</div>
							@if($forum->user_id == $user->id || $checkForumMangeStatus->chat_status != 0)
							<div class="tao_qu_1">
								<livewire:forum-chat-submit :forum_id="$forum_id" :color="$color"/>
							</div>
							@endif

						</div>
						<!-- 討論區 -->

					</div>


					<!--  -->
				</div>
			</div>
		</div>
		@stop

@section('javascript')

<script>

	function delete_alert() {
        c5('此文章已刪除');
	}

	function view_alert() {
		c5('您目前尚無權限');
	}

	function checkUserVip() {

		@if($forum->user_id != $user->id && $user->id != 1049 && $checkForumMangeStatus->forum_status==0)
		c5('您目前尚無討論區權限');
		return false;
		@endif
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
		} else{
			window.location.href = "/dashboard/forum_posts/{{$forum->id}}";
		}
	}

	@if($forum->user_id != $user->id && $user->id != 1049 && $checkForumMangeStatus->forum_status==0)
	$(".sh").hide();
	$(".sh1").show();
	@endif
	function shenhe() {
		@if($forum->user_id != $user->id && $user->id != 1049 && $checkForumMangeStatus->forum_status==0)
		c5('您目前尚無討論區權限');
		@else
		$(".sh").show();
		$(".sh1").hide();
		@endif
	}
	function shenhe1() {
		@if($forum->user_id != $user->id && $user->id != 1049 && $checkForumMangeStatus->chat_status==0)
			c5('您目前尚無聊天室權限');
		@else
			$(".sh").hide();
			$(".sh1").show();
		@endif
	}

	$('.pda_zx').click(function (){
		$(this).children('span').toggleClass('showText')
		$(this).children('a').toggle(0)
	})

	$(".text span").each(function(){
		var  h = $(this).innerHeight();
		if( h > 39){
			$(this).addClass('on');

		}else{
			$(this).next('a').hide();
		}
	})
	$(function (){
		$(".zhap_new a:first").addClass("zhap_new_hover");
		$(".zap_ullist").hide();
		$(".zap_ullist:first").show();
		$(".zhap_new a").click(function () {
			$('.zhap_new a:not(this)').removeClass("zhap_new_hover");
			$(this).addClass("zhap_new_hover");
			$('.zap_ullist').hide();
			var i=$(this).index();
			$('.zap_ullist').eq(i).show();
			$('.zap_ullist').eq(i).find(".text span").each(function(){
				var  h = $(this).innerHeight();
				if( h > 39){
					$(this).addClass('on');
					$(this).next('a').show();
				}else{
					$(this).next('a').remove();
				}
			})
		});


	})
</script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
@stack('scripts')
@endsection
