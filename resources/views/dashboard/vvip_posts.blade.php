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
					<div class="shou">
						<span><img src="/posts/images/tg_15.png" style="height: 26px; margin-right: 5px; margin-bottom: 8px;">發表文章</span>
						<font>Publish</font>
						<a href="{{url()->previous()}}" class="toug_back btn_img">
							<div class="btn_back"></div>
						</a>
						{{--						<a href="{{url()->previous()}}" class="toug_back"><img src="/posts/images/back_icon.png">返回</a>--}}
					</div>
					<div class="two_tg">
						<div class="tow_input">
							<form action="/dashboard/doPosts_VVIP?{{ csrf_token() }}={{ \Carbon\Carbon::now()->timestamp }}" id="posts" method="POST" enctype="multipart/form-data">
								<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
								<input type="hidden" name="type" value="main">
								<input name="title" id="title" type="text" class="tw_input" placeholder="#標題">
								<textarea name="contents" id="contents" cols="" rows="" class="tw_textinput" placeholder="#内容" required></textarea>
								{{--<div class="ti_kuang">
									<div class="ti_title">點這裡變更分身</div>
									<h2 class="matop15"><i class='input_style input_radio radio_bg'><input type="radio" name="is_anonymous" value="anonymous"></i>匿名於站內發布</h2>
									<h2><i class='input_style input_radio radio_bg'><input type="radio" name="is_anonymous" value="combine"></i>站內發布與本站帳號連結</h2>
								</div>
								<div class="ticheckbox"><i class='input_style radio_bg'><input type="checkbox" name="agreement" id="agreement"></i>同意站方匿名行銷使用</div>--}}
								<div class="zap_photo mabot_10">
									<input type="file" name="images" >
								</div>
								<div class="dengl_but matop30" onclick="send_posts_btn()" style="margin-top: 10px;">確定</div>
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
		<script src="{{ asset('new/js/heic2any.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('new/js/resize_before_upload.js') }}" type="text/javascript"></script>

		<script type="application/javascript">

			$(document).ready(function () {
				var images_uploader_options = {
					//extensions: ['jpg', 'png', 'jpeg', 'bmp'],
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
						},
						onItemRemove: function(html, listEl, parentEl, newInputEl, inputEl) {
							var plusInput = listEl.find('.fileuploader-thumbnails-input'),
									api = $.fileuploader.getInstance(inputEl.get(0));

							html.children().animate({'opacity': 0}, 200, function() {
								html.remove();

								if (api.getOptions().limit && api.getChoosedFiles().length - 1 < api.getOptions().limit)
									plusInput.show();
							});
						}
					},
					dialogs: {
						alert:function(message) {
							alert(message);
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
								return '最多上傳 ${limit} 張圖片'
							},
							filesType: '檔名: ${name} 不支援此格式, 只允許 ${extensions} 檔案類型上傳.',
							fileSize: '${name} 檔案太大, 請確認容量需小於 ${fileMaxSize}MB.',
							filesSizeAll: '上傳的所有檔案過大, 請確認未超過 ${maxSize} MB.',
							fileName: '${name} 已有選取相同名稱的檔案.',
						}
					}
				};
				images_uploader=$('input[name="images"]').fileuploader(images_uploader_options);
			});

		</script>
		<style>
			.fileuploader-icon-remove:after {content: none !important;}
			.fileuploader-popup .fileuploader-popup-move[data-action=next]:after{
				content: none;
			}
			.fileuploader-popup .fileuploader-popup-move[data-action=prev]:after{
				content: none;
			}
			.fileuploader-icon-download:before, .fileuploader-icon-download:after{
				display: none;
			}
		</style>
	@stop

<style>
	input[type='radio'],input[type='checkbox']{width:18px;height: 18px;vertical-align:middle;opacity: 0;}
</style>
<script>
	$(document).ready(function(){
		$('.input_radio').on('click',function(){
			check('input[type="radio"]','nradio_bg_check');
		});
		$('input[type="checkbox"]').on('click',function(){
			check('input[type="checkbox"]','checkbox_bg_check');
		});

	});
	function check(el,cl){
		$(el).each(function(){
			$(this).parent('i').removeClass(cl);

			var checked = $(this).prop('checked');
			if(checked){
				$(this).parent('i').addClass(cl);
			}
		})
	}

	function send_posts_btn() {

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
		c5('發表成功！');
		$("#posts").submit();
	}

	$(document).on('click','.blbg',function(event) {
		$(".blbg").hide();
		$(".bl").hide();
		$(".gg_tab").hide();
	});
</script>