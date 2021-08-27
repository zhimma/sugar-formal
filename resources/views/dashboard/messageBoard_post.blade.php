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
		<div class="col-sm-12 col-xs-12 col-md-10 g_pnr">
			<div class="shou">
				<span><img src="/new/images/tg_15.png" style="height: 26px; margin-right: 5px; margin-bottom: 8px;">新增留言</span>
				<font>Wishing</font>
				<a href="/MessageBoard/showList" class="toug_back btn_img">
					<div class="btn_back"></div>
				</a>
			</div>
			<div class="two_tg">
				<div class="tow_input">
					<form action="/MessageBoard/doPosts" id="posts" method="POST" enctype="multipart/form-data">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input name="title" id="title" type="text" class="tw_input" placeholder="#標題">
						<textarea name="contents" id="contents" cols="" rows="" class="tw_textinput" placeholder="#内容"></textarea>
						<input id="images" type="file" name="images" accept="image/*" >
						<div class="dengl_but matop20" onclick="send_posts_submit()">確定</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<link href="{{ asset('css/jquery.fileuploader.min.css') }}" media="all" rel="stylesheet">
<link href="{{ asset('new/css/fileupload.css') }}" media="all" rel="stylesheet">
<link href="{{ asset('css/font/font-fileuploader.css') }}" media="all" rel="stylesheet">
<script src="{{ asset('js/jquery.fileuploader.js') }}" type="text/javascript"></script>
<script>
	function send_posts_submit() {

		var title = $("#title").val();
		if (title == '') {
			c5('您的標題不可以為空！');
			return false;
		}
		var content =$('#contents').val();
		if(content.length <=0 ){
			c5('您的內容不可以為空！');
			return false;
		}
		$("#posts").submit();
	}

	$(document).ready(function () {
		$('input[name="images"]').fileuploader({
			extensions: ['jpg', 'png', 'jpeg', 'bmp'],
			changeInput: ' ',
			theme: 'thumbnails',
			enableApi: true,
			addMore: true,
			limit: 20,
			thumbnails: {
				box: '<div class="fileuploader-items">' +
						'<ul class="fileuploader-items-list">' +
						'<li class="fileuploader-thumbnails-input"><div class="fileuploader-thumbnails-input-inner" style="background: url({{ asset("new/images/addpic.png") }}); background-size:100%"></div></li>' +
						'</ul>' +
						'</div>',
				item: '<li class="fileuploader-item">' +
						'<div class="fileuploader-item-inner">' +
						'<div class="type-holder">${extension}</div>' +
						'<div class="actions-holder">' +
						'<button type="button" class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="fileuploader-icon-remove"></i></button>' +
						'</div>' +
						'<div class="thumbnail-holder">' +
						'${image}' +
						'<span class="fileuploader-action-popup"></span>' +
						'</div>' +
						'<div class="content-holder"><h5>${name}</h5><span>${size2}</span></div>' +
						'<div class="progress-holder">${progressBar}</div>' +
						'</div>' +
						'</li>',
				item2: '<li class="fileuploader-item">' +
						'<div class="fileuploader-item-inner">' +
						'<div class="type-holder">${extension}</div>' +
						'<div class="actions-holder">' +
						'<a href="${file}" class="fileuploader-action fileuploader-action-download" title="${captions.download}" download><i class="fileuploader-icon-download"></i></a>' +
						'<button type="button" class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="fileuploader-icon-remove"></i></button>' +
						'</div>' +
						'<div class="thumbnail-holder">' +
						'${image}' +
						'<span class="fileuploader-action-popup"></span>' +
						'</div>' +
						'<div class="content-holder"><h5 title="${name}">${name}</h5><span>${size2}</span></div>' +
						'<div class="progress-holder">${progressBar}</div>' +
						'</div>' +
						'</li>',
				startImageRenderer: true,
				canvasImage: false,
				_selectors: {
					list: '.fileuploader-items-list',
					item: '.fileuploader-item',
					start: '.fileuploader-action-start',
					retry: '.fileuploader-action-retry',
					remove: '.fileuploader-action-remove'
				},
				onItemShow: function(item, listEl, parentEl, newInputEl, inputEl) {
					var plusInput = listEl.find('.fileuploader-thumbnails-input'),
							api = $.fileuploader.getInstance(inputEl.get(0));

					plusInput.insertAfter(item.html)[api.getOptions().limit && api.getChoosedFiles().length >= api.getOptions().limit ? 'hide' : 'show']();

					if(item.format == 'image') {
						item.html.find('.fileuploader-item-icon').hide();
					}

					if (api.getListEl().length > 0) {
						$('.fileuploader-thumbnails-input-inner').css('background-image', 'url({{ asset("new/images/addpic.png") }})');
					}

					if (api.getOptions().limit && api.getChoosedFiles().length - 1 < api.getOptions().limit){
						plusInput.hide();
					}
				},
				onItemRemove: function(html, listEl, parentEl, newInputEl, inputEl) {
					var plusInput = listEl.find('.fileuploader-thumbnails-input'),
							api = $.fileuploader.getInstance(inputEl.get(0));

					html.children().animate({'opacity': 0}, 200, function() {
						html.remove();

						if (api.getOptions().limit && api.getChoosedFiles().length - 1 < api.getOptions().limit)
							plusInput.show();
					});

					if (api.getFiles().length == 1) {
						$('.fileuploader-thumbnails-input-inner').css('background-image', 'url({{ asset("new/images/addpic.png") }})');
					}
				}
			},
			dialogs: {
				alert:function(message) {
					c5(message);
				},
				confirm: function(message, callback) {
					c6(message) ? callback() : null;
				}
			},
			dragDrop: {
				container: '.fileuploader-thumbnails-input'
			},
			afterRender: function(listEl, parentEl, newInputEl, inputEl) {
				var plusInput = listEl.find('.fileuploader-thumbnails-input'),
						api = $.fileuploader.getInstance(inputEl.get(0));

				plusInput.on('click', function() {
					api.open();
				});

				api.getOptions().dragDrop.container = plusInput;
			},
			editor: {
				cropper: {
					showGrid: true,
				},
			},
			captions: {
				confirm: '確認',
				cancel: '取消',
				name: '檔案名稱',
				type: '類型',
				size: '容量',
				dimensions: '尺寸',
				duration: '持續時間',
				crop: '裁切',
				rotate: '旋轉',
				sort: '分類',
				download: '下載',
				remove: '刪除',
				drop: '拖曳至此上傳檔案',
				open: '打開',
				removeConfirmation: '確認要刪除檔案嗎?',
				errors: {
					filesLimit: function(options) {
						return '最多上傳 ${limit} 張圖片.'
					},
					filesType: '檔名: ${name} 不支援此格式, 只允許 ${extensions} 檔案類型上傳.',
					fileSize: '${name} 檔案太大, 請確認容量需小於 ${fileMaxSize}MB.',
					filesSizeAll: '上傳的所有檔案過大, 請確認未超過 ${maxSize} MB.',
					fileName: '${name} 已有選取相同名稱的檔案.',
				}
			}
		});

		$(".announce_bg").on("click", function() {
			$('.bl_tab_aa').hide();
		});
	});
</script>
<style>
	.blnr{padding-bottom: 14px;}
	.fileuploader-icon-remove:after {content: none !important;}

</style>
@stop
