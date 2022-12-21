@include('partials.header')
@include('partials.message')
<body style="padding: 15px;">
<h1> 匿名評價溝通 - 對話紀錄</h1>
<table class="table table-hover table-bordered">
	<form action="{{ route('users/message/delete') }}" method="post">
	    {!! csrf_field() !!}
{{--		<input type="hidden" name="delete" id="delete" value="1">--}}
	<tr>
		<th width="12%">發訊</th>
		<th width="12%">收訊</th>
		<th>內容</th>
		<th>上傳照片</th>
		<th>發送時間</th>
        <th width="5%" nowrap>狀態</th>
		
	</tr>
	@forelse ($messages as $message)
		<tr>
			<td>
				<a href="{{ route('users/advInfo', [$message->from_user]) }} ">{{ $message->from_user_name }}
				@if($message->is_row_delete_1 == $message->user_id || $message->is_row_delete_2 == $message->user_id)
					(刪)
				@endif
				</a>
			</td>
			<td>
				<a href="{{ route('users/advInfo', [$message->to_user]) }} ">{{ $message->to_user_name }}</a>
			</td>
			<td>
				<p style="word-break:break-all;">{{ $message->content }}</p>
			</td>
			<td class="evaluation_zoomIn">
				@php
					$messagePics=is_null($message->pictures) ? [] : json_decode($message->pictures,true);
				@endphp
				@if(isset($messagePics))
					@foreach($messagePics as $messagePic)
						<li style="float:left;margin:2px 2px;list-style:none;display:block;white-space: nowrap;width: 135px;">
						
						<img src="/storage/{{ $messagePic }}" style="max-width:130px;max-height:130px;margin-right: 5px;">
						</li>
					@endforeach
				@endif
			</td>
			<td>
				{{ $message->created_at }}
			</td>
            <td nowrap>{{ $message->unsend?'已收回':'' }}</td>
		</tr>
    @empty
        沒有訊息
    @endforelse
	</form>
</table>

</body>
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
</html>

<link type="text/css" rel="stylesheet" href="/new/css/app.css">
<link rel="stylesheet" type="text/css" href="/new/css/swiper2.min.css"/>
<script type="text/javascript" src="/new/js/swiper.min.js"></script>
<script>
jQuery(document).ready(function() {
	$('.delete-btn').on('click', function (e) {
		if (!confirm('確定要刪除選取的訊息?')) {
			e.preventDefault();
		}
	});

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
	$(".evaluation_zoomIn li").on("click",
			function () {
				var imgBox = $(this).parent(".evaluation_zoomIn").find("li");
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
</script>