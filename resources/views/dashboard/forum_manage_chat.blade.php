<style>
    .icon-shenqing:before {
        content: "\e61a";
    }
	.fileuploader {
		max-width: 560px;
	}
	{{--.fileuploader-input-button{--}}
	{{--	width: 35px !important;--}}
	{{--	height: 35px !important;--}}
	{{--	background: url({{ asset('posts/images/tutj.png')}}) #fff !important;--}}
	{{--	background-size: 35px !important;--}}
	{{--	outline: none !important;--}}
	{{--	border-radius: 100px !important;--}}
	{{--	float: left !important;--}}
	{{--	margin-top: 2px !important;--}}
	{{--	color: #fd5678 !important;--}}
	{{--	box-shadow: 0 0 15px #eaeaea !important;--}}
	{{--	padding: 0px !important;--}}
	{{--}--}}
	{{--.fileuploader-input-button:hover{--}}
	{{--	background:url({{ asset('posts/images/tutj_h.png')}}) #fff !important;--}}
	{{--	background-size:25px; !important;--}}
	{{--	color: #fff; !important;--}}
	{{--	outline: none !important;--}}
	{{--	box-shadow:2px 2px 6px 0 rgba(255,255,255,.8),-4px -4px 6px 0 rgba(254,121,139,.5),inset -4px -4px 6px 0 rgba(254,121,139,.5),inset 4px 4px 6px 0 rgba(254,121,139,.5) !important;--}}
	{{--	/*box-shadow: 0 0 15px #fd5678 !important;*/--}}
	{{--	background-position: center !important;--}}
	{{--	/*background-position:left;*/--}}
	{{--	/*box-shadow:0 8px 25px rgba(0,0,0,.15);*/--}}
	{{--	transform: unset !important;--}}
	{{--}--}}
	{{--.fileuploader-input-button span{--}}
	{{--	display: none;--}}
	{{--}--}}

	.tia_icon{
		position: relative;
		left: -15px;
		top: -30px;
	}
	.fs_icon{
		position: relative;
		top: -15px;
	}
</style>
@extends('new.layouts.website')

		<link rel="stylesheet" href="/posts/css/style.css">
		<link rel="stylesheet" href="/posts/css/font/font_n/iconfont.css">
		<link rel="stylesheet" href="/posts/css/font/iconfont.css">
		<link rel="stylesheet" href="/posts/css/taolunqu/iconfont.css">
		<link href="https://fonts.googleapis.com/css?family=Roboto:400|700" rel="stylesheet">
		<link href="/new/fileuploader/jquery.fileuploader.min.css" media="all" rel="stylesheet">
		<link href="/new/fileuploader/jquery.fileuploader-theme-onebutton.css" media="all" rel="stylesheet">
{{--		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>--}}
{{--<script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>--}}
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
						<span style="margin: 0 auto; position: relative;line-height: 44px;padding-bottom: 3px;">{{$forumInfo->title}}</span>
						<a class="toug_back btn_img01 userlogo xzgn">
							<div class="btn_back">功能選單<img src="/posts/images/jiant_a.png"></div>
						</a>
						<div class="fabiao showslide">
							<a onclick="checkUserVip();">我要發表</a>
							<a href="/dashboard/posts_manage">會員管理</a>
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
{{--					<livewire:posts-manage-chat :checkStatus="$checkStatus"/>--}}

					<div class="taol_lt">
						<!-- 聊天室 -->
						@if($checkStatus->status==0 && $user->id ==$checkStatus->apply_user_id)
						<div class="sqnc">
							<span class="iconfont icon-shenqing"></span>{{$uidInfo->name}}
						</div>
						@endif

						<div class="taol_tab01" >
							<div class="tao_qu" style="padding-bottom: 80px;">
								Coming Soon...
								<div class="tao_time">08-30(二）</div>

								<div class="show maspp0">
									<div class="msg msg1">
										<img src="/posts/images/icon_010.png">
										<div class="msg_p1">
											<i class="msg_input_nn_2"></i>阿龍的聊天室阿龍的聊天室
										</div>
									</div>
								</div>
								<div class="send maspp0">
									<div class="msg">
										<img src="/posts/images/icon_010.png">
										<div class="msg_p"><i class="msg_input_nn"></i>版主好！</div>
									</div>
								</div>
								<div class="send maspp0">
									<div class="msg">
										<img src="/posts/images/icon_010.png">
										<div class="msg_p"><i class="msg_input_nn"></i>疫情終於快走了～</div>
									</div>
								</div>
								<div class="show maspp0">
									<div class="msg msg1">
										<img src="/posts/images/icon_010.png">
										<div class="msg_p1">
											<i class="msg_input_nn_2"></i>疫情期間大辛苦了
										</div>
									</div>
								</div>
								<div class="tao_time matop10">08-30(二）</div>
								<div class="show maspp0">
									<div class="msg msg1">
										<img src="/posts/images/icon_010.png">
										<div class="msg_p1">
											<i class="msg_input_nn_2"></i>疫情期間大辛苦了
										</div>
									</div>
								</div>



								<div class="shenqing">
									@if($checkStatus->status==0 && $user->id ==$checkStatus->apply_user_id)
									<div style=" margin: 0 auto; display: table">
										<a onclick="forum_manage_toggle({{$checkStatus->user_id}}, 1)" class="shenq_button_a">通過</a>
										<a onclick="forum_manage_toggle({{$checkStatus->user_id}}, 2)" class="shenq_button_a">不通過</a>
									</div>
									@elseif($checkStatus->status==0 && $user->id != $checkStatus->apply_user_id)
									<a onclick="forum_manage_toggle({{$checkStatus->apply_user_id}}, 3)" class="shenq_button">取消申請</a>
									@endif
								</div>

							</div>


							<div class="tao_qu_1">
								<form id="" action="" method="post" enctype="multipart/form-data">
{{--								<a href="javascript:void(0);" class="tia_icon"></a>--}}

									<div class="ta_input">
										<div class="ta_input_a">
											{{--<a href="javascript:void(0);" class="ta_yyah" disabled="disabled">--}}
												{{--<img src="/posts/images/yyqh.png">--}}
											{{--</a>--}}
											<input placeholder="请输入内容" class="ta_input_po">
										</div>
									</div>
									<a  href="javascript:void(0);" class="fs_icon"></a>
									<input type="file" id="files" name="files" data-fileuploader-files="">
								</form>
							</div>
						</div>
						<!-- 结束 -->

					</div>


					<!--  -->
				</div>
			</div>
		</div>
		@stop
<script>
	$.noConflict();
	// Code that uses other library's $ can follow here.
</script>
<script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
<script src="/new/fileuploader/jquery.fileuploader.min.js" type="text/javascript"></script>
{{--<link href="{{ asset('css/jquery.fileuploader.min.css') }}" media="all" rel="stylesheet">--}}
{{--<link href="{{ asset('new/css/fileupload.css') }}" media="all" rel="stylesheet">--}}
{{--<link href="{{ asset('css/font/font-fileuploader.css') }}" media="all" rel="stylesheet">--}}

{{--<script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>--}}
{{--<script src="{{ asset('js/jquery.fileuploader.js') }}" type="text/javascript"></script>--}}

<script>
	// $('input[name="files"]').fileuploader({
	// 	theme: 'onebutton'
	// });
	// $('input[name="files"]').fileuploader({
	// 	extensions: null,
	// 	changeInput: ' ',
	// 	theme: 'One-button',
	// 	enableApi: true,
	// 	addMore: true,
	// 	thumbnails: {
	// 		box: '<div class="fileuploader-items">' +
	// 				'<ul class="fileuploader-items-list">' +
	// 				'<li class="fileuploader-thumbnails-input"><div class="fileuploader-thumbnails-input-inner"><i>上傳文件</i></div></li>' +
	// 				'</ul>' +
	// 				'</div>',
	// 		item: '<li class="fileuploader-item">' +
	// 				'<div class="fileuploader-item-inner">' +
	// 				'<div class="type-holder">${extension}</div>' +
	// 				'<div class="actions-holder">' +
	// 				'<button type="button" class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="fileuploader-icon-remove"></i></button>' +
	// 				'</div>' +
	// 				'<div class="thumbnail-holder">' +
	// 				'${image}' +
	// 				'<span class="fileuploader-action-popup"></span>' +
	// 				'</div>' +
	// 				'<div class="content-holder"><h5>${name}</h5><span>${size2}</span></div>' +
	// 				'<div class="progress-holder">${progressBar}</div>' +
	// 				'</div>' +
	// 				'</li>',
	// 		item2: '<li class="fileuploader-item">' +
	// 				'<div class="fileuploader-item-inner">' +
	// 				'<div class="type-holder">${extension}</div>' +
	// 				'<div class="actions-holder">' +
	// 				'<a href="${file}" class="fileuploader-action fileuploader-action-download" title="${captions.download}" download><i class="fileuploader-icon-download"></i></a>' +
	// 				'<button type="button" class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="fileuploader-icon-remove"></i></button>' +
	// 				'</div>' +
	// 				'<div class="thumbnail-holder">' +
	// 				'${image}' +
	// 				'<span class="fileuploader-action-popup"></span>' +
	// 				'</div>' +
	// 				'<div class="content-holder"><h5 title="${name}">${name}</h5><span>${size2}</span></div>' +
	// 				'<div class="progress-holder">${progressBar}</div>' +
	// 				'</div>' +
	// 				'</li>',
	// 		startImageRenderer: true,
	// 		canvasImage: false,
	// 		_selectors: {
	// 			list: '.fileuploader-items-list',
	// 			item: '.fileuploader-item',
	// 			start: '.fileuploader-action-start',
	// 			retry: '.fileuploader-action-retry',
	// 			remove: '.fileuploader-action-remove'
	// 		},
	// 		onItemShow: function(item, listEl, parentEl, newInputEl, inputEl) {
	// 			var plusInput = listEl.find('.fileuploader-thumbnails-input'),
	// 					api = $.fileuploader.getInstance(inputEl.get(0));
	//
	// 			plusInput.insertAfter(item.html)[api.getOptions().limit && api.getChoosedFiles().length >= api.getOptions().limit ? 'hide' : 'show']();
	//
	// 			if(item.format == 'image') {
	// 				item.html.find('.fileuploader-item-icon').hide();
	// 			}
	// 		},
	// 		onItemRemove: function(html, listEl, parentEl, newInputEl, inputEl) {
	// 			var plusInput = listEl.find('.fileuploader-thumbnails-input'),
	// 					api = $.fileuploader.getInstance(inputEl.get(0));
	//
	// 			html.children().animate({'opacity': 0}, 200, function() {
	// 				html.remove();
	//
	// 				if (api.getOptions().limit && api.getChoosedFiles().length - 1 < api.getOptions().limit)
	// 					plusInput.show();
	// 			});
	// 		}
	// 	},
	// 	dragDrop: {
	// 		container: '.fileuploader-thumbnails-input'
	// 	},
	// 	afterRender: function(listEl, parentEl, newInputEl, inputEl) {
	// 		var plusInput = listEl.find('.fileuploader-thumbnails-input'),
	// 				api = $.fileuploader.getInstance(inputEl.get(0));
	//
	// 		plusInput.on('click', function() {
	// 			api.open();
	// 		});
	//
	// 		api.getOptions().dragDrop.container = plusInput;
	// 	},
	//
	// });

	$(document).ready(function() {

		// $('input[name="files"]').fileuploader({
		// 	theme: 'onebutton'
		// });

		@if(Session::has('message'))
		c5('{{Session::get('message')}}');
		<?php session()->forget('message');?>
		@endif
	});

	function forum_manage_toggle(auid, status) {
		var msg, apply_user_id, user_id;
		var fid = '{{$forumInfo->id}}';
		if(status==1){
			user_id = auid;
			apply_user_id = '{{$user->id}}';
			msg='您確定要通過該會員加入嗎?'
		}else if(status==2){
			user_id = auid;
			apply_user_id = '{{$user->id}}';
			msg='您確定要拒絕該會員加入嗎?'
		}else if(status==3){
			user_id = '{{$user->id}}';
			apply_user_id = auid;
			msg='您確定要取消申請嗎?'
		}else{
			return false;
		}
		c4(msg);
		$(".n_left").on('click', function() {
			$.post('{{ route('forum_manage_toggle') }}', {
				uid: user_id,
				auid: apply_user_id,
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
			window.location.href = "/dashboard/forum";
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

		// $(".text>a").click(function(){
		//           var btn = $(this).prev();
		//           if(btn.hasClass('on')){
		//               btn.removeClass("on");
		//               $(this).html("<em></em>");
		//           }else{
		//               btn.addClass("on");
		//               $(this).html("…<em>更多</em>");
		//           }
		//       });

	})
</script>

<script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
<script src="/new/fileuploader/jquery.fileuploader.min.js" type="text/javascript"></script>

<script>
	$.noConflict();
	jQuery( document ).ready(function( $ ) {

		$('input[name="files"]').fileuploader({
			extensions: null,
			changeInput: '<a href="javascript:void(0);" class="tia_icon"></a>',
			theme: 'onebutton',
			enableApi: true,
			addMore: true
		});
	});
</script>