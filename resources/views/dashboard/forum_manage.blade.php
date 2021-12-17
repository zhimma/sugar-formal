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
</style>
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


					<div class="shou" style="text-align: center; position: relative;">
						<a href="{{url()->previous()}}" class="toug_back btn_img" style=" position: absolute; left: 0;">
							<div class="btn_back"></div>
						</a>
						<span style="margin: 0 auto; position: relative;line-height: 44px;padding-bottom: 3px;">{{$posts_forum->title}}</span>
						<a class="toug_back btn_img01 userlogo xzgn">
							<div class="btn_back">功能選單<img src="/posts/images/jiant_a.png"></div>
						</a>
						<div class="fabiao showslide">
							<a onclick="checkUserVip();">我要發表</a>
							<a href="/dashboard/forum_manage">會員管理</a>
						</div>
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
						<div class="hygl_b">
							<li class="na_se hyg_wid">
								<div class="n_sq_hover" style="display: table; height: 50px;"><img src="/posts/images/zp_gl.png" class="hy_spangl" style="margin-top: 10px;"></div>
							</li>
							<li class="lis_sq01 hy_tefo" style="margin-left: -15px;">會員管理</li>
						</div>

						<div class="hy_gltabn" >
							<li style="text-align: center;">
								<img src="/posts/images/bianji.png" class="feng_img"><span>Coming Soon...</span>
							</li>

							{{--@if(count($posts_manage_users)>0)--}}
							{{--@foreach($posts_manage_users as $row)--}}
							{{--<li>--}}
							{{--<div class="h3_divtab1"><span>會員暱稱丨 </span><font><a href="/dashboard/viewuser/{{$row->user_id}}">{{$row->name}}</a></font></div>--}}
							{{--<div class="hy_icobutton list1"  id="itemssxN">--}}
							{{--@if($row->status == 0)--}}
							{{--<a href="/dashboard/forum_manage_chat/{{$user->id}}/{{$row->user_id}}" class="hy_icon022 custom_s"><span class="iconfont icon-fangdajing"></span>審核中，點此查看聊天記錄</a>--}}
							{{--@elseif($row->status == 1)--}}
							{{--<font class="hy_icon011 custom_s"><span class="iconfont icon-liaotian1"></span><a href="/dashboard/forum_personal/{{$row->user_id}}">討論區</a></font>--}}
							{{--<font class="hy_icon011 custom_s"><span class="iconfont icon-gonggongliaotianshi-fill"></span><a href="/dashboard/forum_manage_chat/{{$row->user_id}}/{{$user->id}}">聊天室</a></font>--}}
							{{--<font class="hy_icon011 custom_s"><span class="iconfont icon-yichuchengyuan1"></span><a onclick="forum_manage_toggle({{$row->user_id}}, 3)">移除成員</a></font>--}}
							{{--@endif--}}
							{{--</div>--}}
							{{--</li>--}}
							{{--@endforeach--}}
							{{--@else--}}
							{{--<li style="text-align: center;">--}}
							{{--<img src="/posts/images/bianji.png" class="feng_img"><span>尚無資料</span>--}}
							{{--</li>--}}
							{{--@endif--}}
						</div>
{{--						<div class="fenye ba_but" style="margin-top: 10px;">--}}
{{--							{{ $posts_manage_users->links('pagination::sg-pages2') }}--}}
{{--							<a href="">上一頁</a><span class="new_page">1/5</span><a href="">下一頁</a>--}}
{{--						</div>--}}
						<!-- 结束 -->
{{--						<livewire:posts-manage-chat />--}}

					</div>


					<!--  -->
				</div>
			</div>
		</div>
		@stop
<script>
	$(document).ready(function() {
		@if(Session::has('message'))
		c5('{{Session::get('message')}}');
		<?php session()->forget('message');?>
		@endif
	});

	function forum_manage_toggle(auid, status) {
		var msg;
		var fid='{{$posts_forum->id}}';
		if(status==3){
			msg='您確定要移除此會員嗎?'
		}else{
			return false;
		}
		c4(msg);
		$(".n_left").on('click', function() {
			$.post('{{ route('forum_manage_toggle') }}', {
				uid: auid,
				auid: '{{ $user->id }}',
				fid: fid,
				status: status,
				_token: '{{ csrf_token() }}'
			}, function (data) {
				$("#tab04").hide();
				var obj = JSON.parse(data);
				c5(obj.message);
				$(".n_bllbut").on('click', function() {
					location.reload();
				});
			});
		});
	}

	function checkUserVip() {

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
			window.location.href = "/dashboard/forum_posts";
		}
	}

	function shenhe() {
		$(".sh").show()
		$(".sh1").hide()
	}
	function shenhe1() {
		$(".sh").hide()
		$(".sh1").show()
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