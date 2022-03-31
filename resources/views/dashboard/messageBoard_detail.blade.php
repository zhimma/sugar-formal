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

<script src="{{ mix('/js/app.js') }}"></script>
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
	<div id="app">
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
					<div v-html="itemHeader"></div>
					
					<div class="liuy_nr">
							<div v-html="itemContent"></div>
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
	<script type="application/javascript">
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
	<script type="application/javascript">
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

		function messageBoard_reported() {
			c4('確定要檢舉該留言訊息嗎?');
			$(".n_left").on('click', function() {
				let msg_id='{{$postDetail->mid}}';
				$.post('{{ route('reportMessageBoardAJAX') }}', {
					msg_id: msg_id,
					_token: '{{ csrf_token() }}'
				}, function (result) {
					$("#tab04").hide();
					show_pop_message(result.msg);
				});
			});
		}

		function block_user() {
			c4('確定要封鎖嗎?');
			$(".n_left").on('click', function() {
				let uid='{{ $user->id }}';
				let to='{{$postDetail->uid}}';
				if(uid != to){
					$.post('{{ route('postBlockAJAX') }}', {
						uid: uid,
						sid: to,
						_token: '{{ csrf_token() }}'
					}, function () {
						$("#tab04").hide();
						show_pop_message('封鎖成功');
					});
				}else{
					$("#tab04").hide();
					show_pop_message('不可封鎖自己');
				}
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

	
	<script>
    const vm = new Vue({
            el: '#app',
            data () {
                return {
					"itemHeader":'<div class="liuyan_xqlist"><a href="#"><div class="liuyan_img01"><img class="hycov"></div></a><div class="liuyan_text"><a href="#"></a> , <span class="liu_dq"></span></div></div>',
					'itemContent':'<div class="liuy_nr"><div class="liuy_font"><div class="liuy_font_1"><div class="liu_yf"><h2></h2></div></div><p></p><div class="liu_iy"><img src="/new/images/photo_1.png"></div></div><ul class="liuyan_photo"><li class="liuy_ph3-4"><img class="hycov"></li></ul></div>',
					// "listOther":'loading...',
					// 'listMyself':'loading...'
				}
            },
        async mounted () {
			let pid={{$pid}};
                await axios
                .post('/MessageBoard/getItemHeader', { pid:pid })
                .then(response => {
					console.log(response,'getItemHeader');
                    this.itemHeader = response.data.ssrData;
                })
                .catch(function (error) { // 请求失败处理
                    console.log(error);
                });
   
                axios
                .post('/MessageBoard/getItemContent',{ pid:pid })

                .then(response => {
					console.log(response);
                    this.itemContent = response.data.ssrData;
                })
                .catch(function (error) { // 请求失败处理
                    console.log(error);
                });
        }
        });
	</script>
@stop
