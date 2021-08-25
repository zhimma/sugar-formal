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
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="format-detection" content="telephone=no" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
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
					<span>留言板詳情</span><font>Wishing Board</font>
					<a href="/MessageBoard/showList" class="toug_back btn_img">
						<div class="btn_back"></div>
					</a>
				</div>
				<div class="liuyan_xqlist">
					@php
						$userMeta=\App\Models\UserMeta::findByMemberId($postDetail->uid);
						$msgUser=\App\Models\User::findById($postDetail->uid);
						$isBlurAvatar = \App\Services\UserService::isBlurAvatar($msgUser, $user);
					@endphp
					<a href="/dashboard/viewuser/{{$postDetail->uid}}">
						<div class="liuyan_img01">
							<img class="hycov @if($isBlurAvatar) blur_img @endif" src="@if(file_exists( public_path().$postDetail->umpic ) && $postDetail->umpic != ""){{$postDetail->umpic}} @elseif($postDetail->uengroup==2)/new/images/female.png @else/new/images/male.png @endif">
						</div>
					</a>
					<div class="liuyan_text"><a href="/dashboard/viewuser/{{$postDetail->uid}}">{{ $postDetail->uname }}</a> , {{ $userMeta ? $userMeta->age() : '' }}<span>{{ $postDetail->city.$postDetail->area }}</span></div>
					@if($postDetail->uid!==$user->id)
						<a href="/dashboard/chat2/chatShow/{{ $postDetail->uid }}" class="liuyicon"></a>
					@endif
				</div>
				<div class="liuy_nr">
					<div class="liuy_font">
						<div class="liuy_font_1">
							<div class="liu_yf">{{ $postDetail->mtitle }}<h2>{{ substr($postDetail->mcreated_at,0,10) }}</h2></div>
							@if($postDetail->uid== auth()->user()->id)
								<div class="right">
									<form action="/MessageBoard/delete/{{ $postDetail->mid }}" id="delete_form" method="POST" enctype="multipart/form-data">
										<input type="hidden" name="_token" value="{{ csrf_token() }}">
									</form>
									<a class="sc_cc" onclick="send_delete_submit()"><img src="/new/images/del_03n.png">刪除</a>
									<a href="/MessageBoard/edit/{{ $postDetail->mid }}" class="sc_cc"  style="margin-right: 5px;"><img src="/new/images/xiugai.png">修改</a>
								</div>
							@endif
						</div>
						<p>{!! \App\Models\Posts::showContent($postDetail->mcontents) !!}</p>
						<div class="liu_iy"><img src="/new/images/photo_1.png"></div>
					</div>
					<ul class="liuyan_photo">
						@if(count($images)==1)
							@foreach($images as $key => $image)
								<li class="liuy_ph3-4">
									<img src="{{ $image->pic }}" class="hycov">
								</li>
							@endforeach
						@elseif(count($images)==2)
							@foreach($images as $key => $image)
								@if($key==0)
									<li class="liuy_ph3-3">
										<img src="{{ $image->pic }}" class="hycov">
									</li>
								@endif
								@if($key==1)
									<li class="liuy_ph3-3 right01">
										<img src="{{ $image->pic }}" class="hycov">
									</li>
								@endif
							@endforeach
						@elseif(count($images)==3)
							@foreach($images as $key => $image)
								@if($key==0)
									<li class="liuy_ph3-1">
										<img src="{{ $image->pic }}" class="hycov">
									</li>
								@endif
								@if($key==1)
									<li class="liuy_ph3-2 liu_one">
										<div class="liu_imt liu_one">
											<img src="{{ $image->pic }}" class="hycov">
										</div>
									</li>
								@endif
								@if($key==2)
									<li class="liuy_ph3-2 liu_bot01">
										<div class="liu_imt">
											<img src="{{ $image->pic }}" class="hycov">
										</div>
									</li>
								@endif
							@endforeach
						@else
							@foreach($images as $key => $image)
								@if($key==0)
									<li class="liuy_ph1">
										<img src="{{ $image->pic }}" class="hycov">
									</li>
								@endif
								@if($key==1)
									<li class="liuy_ph2 liu_one">
										<div class="liu_imt liu_one"><img src="{{ $image->pic }}" class="hycov"></div>
									</li>
								@endif
								@if($key==2)
									<li class="liuy_ph2 liu_bot01">
										<div class="liu_imt"><img src="{{ $image->pic }}" class="hycov"></div>
									</li>
								@endif
								@if($key==3)
									<li class="liuy_ph3">
										<img src="{{ $image->pic }}" class="hycov">
										<div class="li_fontx">+{{ count($images)-3 }}</div>
									</li>
								@endif
								@if($key>=4)
									<li style="display: none;">
										<img src="{{ $image->pic }}" class="hycov">
									</li>
								@endif
							@endforeach
						@endif
					</ul>
				</div>
			</div>
		</div>
	</div>
	@include('new.partials.footer')

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

			$(".liuyan_photo li").on("click",
					function () {
						var imgBox = $(this).parent(".liuyan_photo").find("li");
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
	<!--照片查看end-->
	<script>
		$(document).ready(function () {

			@if(Session::has('message'))
			c5("{{Session::get('message')}}");
			<?php session()->forget('message');?>
			@endif
		});

		function send_delete_submit() {
			c4('確定要刪除嗎?');
			$(".n_left").on('click', function() {
				$("#delete_form").submit();
			});
		}
	</script>
	<style>
		.pagination > li > a:focus,
		.pagination > li > a:hover,
		.pagination > li > span:focus,
		.pagination > li > span:hover{
			z-index: 3;
			color: #23527c !important;
			background-color: #f5c2c0 !important;
			border-color: #ddd !important;
			border-color:#ee5472 !important;
			color:white !important;
		}

		.pagination > .active > a,
		.pagination > .active > span,
		.pagination > .active > a:hover,
		.pagination > .active > span:hover,
		.pagination > .active > a:focus,
		.pagination > .active > span:focus {
			z-index: 3;
			color: #23527c !important;
			background-color: #f5c2c0 !important;
			border-color:#ee5472 !important;
			color:white !important;
		}
		.blnr{padding-bottom: 14px;}
	</style>
@stop
