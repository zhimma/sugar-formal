@extends('new.layouts.website')
@section('style')
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<link rel="stylesheet" href="/new/css/iconfont.css">
<link href="{{ asset('css/jquery.fileuploader.min.css') }}" media="all" rel="stylesheet">
<link href="{{ asset('new/css/fileupload.css') }}" media="all" rel="stylesheet">
<link href="{{ asset('css/font/font-fileuploader.css') }}" media="all" rel="stylesheet">
<script src="{{ asset('js/jquery.fileuploader.js') }}" type="text/javascript"></script>
<script src="{{ asset('new/js/photoswipe-simplify.min.js') }}" charset="utf-8"></script>
<script src="{{ asset('new/js/heic2any.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('new/js/resize_before_upload.js') }}" type="text/javascript"></script>
<style>
.real_auth_bg{width:100%; height:100%;width: 100%;height: 100%;position: fixed;top: 0px;left: 0;background: rgba(0,0,0,0.5);z-index: 9;display:none;}
</style>
<style>
.write_img{background-color:transparent; border-radius: 15px;}
.write_img .img{background-color: #fff;border-radius: 15px}
@media (max-width: 414px){
.dt_pa0 {padding-top: 0;height: 110px;}
}
</style>
@stop
@section('app-content')
		<!---->
		<div class="container matop70">
			<div class="row">
				<div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
				</div>
				<div class="col-sm-12 col-xs-12 col-md-10">
					<div class="shou"><span>名人認證</span>
						<font>Certification</font>
						<a href="" class="toug_back btn_img"><div class="btn_back"></div></a>
					</div>
					<!--  -->
					<div class="gjrz_nr01 ga_bot70">
						<div class="gjr_b"><img src="{{asset('posts/images/gj_bt2.png')}}"></div>
						<div class="renz_n">請提供相關資料</div>
						<form name="famous_auth_form" method="post" action="{{route('famous_auth_save')}}" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        @include('auth.real_auth_question_tpl')
                        {{--
                        @foreach($entry_list as $q_idx=>$question_entry)
					    <div class="gjr_nr02 gir_top20 gir_pa01">
							 <h2 class="gjr_nr02_h2">{{$q_idx+1}}:{{$question_entry->question}}</h2>
							 <div class="gjr_nr02_h2 rzmatop_5">
                                <h2 class="rzmatop_5">
                                @foreach($question_entry->real_auth_choice as $choice_index=> $choice_entry)
                                    @if($choice_index && $choice_entry->type!=$question_entry->real_auth_choice[$choice_index-1]->type)
                                    <span class="ga_or01">-or-</span>    
                                    @endif
                                    @include('auth.real_auth_choice_tpl_'.$service->questionTypeToKey($question_entry->type??$choice_entry->type))

                                @endforeach
                                --}}{{--
								 @if($question_entry->type??null)
                                    @include('auth.real_auth_choice_tpl_'.$service->questionTypeToKey($question_entry->type))
                                 @else
                                    @include('auth.real_auth_choice_tpl')
                                 @endif
                                 --}}{{--
                                </h2>
                             </div>
						</div>                        
                        @endforeach
                        --}}
					    {{--
                        <div class="gjr_nr02 gir_top20 gir_pa01">
							 <h2 class="gjr_nr02_h2">1:FB/IG 超過 5000 人追蹤</h2>
							 <div class="gjr_nr02_h2 rzmatop_5">
								 <h2 class="rzmatop_5"><input class="g_rinput" placeholder="請輸入連結"></h2>
							 </div>
						</div>
					
					
					<div class="gjr_nr02 gir_top20 gir_pa01">
						 <h2 class="gjr_nr02_h2">2:曾參與超過三場以上走秀/演出</h2>
						 <div class="gjr_nr02_h2 rzmatop_5">
							 <h2 class="rzmatop_5"><input class="g_rinput" placeholder="請輸入連結"></h2>
							 <span class="ga_or01">-or-</span>
							 <ul class="n_ulpic" style="margin-bottom:5px;">
							 		<li class="write_img mabot_10 dt_pa0"><b class="img dt_heght gir_border"><img src="{{asset('posts/images/ph_xz01.png')}}" class="hycov"></b></li>
							 </ul>
						 </div>
					</div>
					
					<div class="gjr_nr02 gir_top20 gir_pa01">
						 <h2 class="gjr_nr02_h2">3:公眾人物</h2>
						 <div class="gjr_nr02_h2 rzmatop_5">
							 <h2 class="rzmatop_5"><input class="g_rinput" placeholder="請輸入姓名或外號"></h2>
						 </div>
					</div>
					
					<div class="gjr_nr02 gir_top20 gir_pa01">
						 <h2 class="gjr_nr02_h2">4:公認校花/系花</h2>
						 <div class="gjr_nr02_h2 rzmatop_5">
							 <h2 class="rzmatop_5">
								 <input class="g_rinput rzmabot_10" placeholder="請輸入學校名稱">
								 <input class="g_rinput rzmabot_10" placeholder="請輸入系級">
								 <input class="g_rinput" placeholder="請輸入真實姓名">
							 </h2>
						 </div>
					</div>
					
					<div class="gjr_nr02 gir_top20 gir_pa01">
						 <h2 class="gjr_nr02_h2">5:其他特殊條件</h2>
						 <div class="gjr_nr02_h2 rzmatop_5">
							 <h2 class="rzmatop_5">
								 <textarea placeholder="請輸入" class="g_rtext" ></textarea>
								 <span class="ga_or01">-or-</span>
								 <ul class="n_ulpic" style="margin-bottom:5px;">
								 	<li class="write_img mabot_10 dt_pa0"><b class="img dt_heght gir_border"><img src="{{asset('posts/images/ph_xz01.png')}}" class="hycov"></b></li>
								 </ul>
							 </h2>
						 </div>
					</div>
                        --}}
					
                        <div class="n_txbut g_inputt40">
                              <a href="javascript:void(0);" class="se_but1" onclick="document.famous_auth_form.requestSubmit();">我同意</a>
                              <a href="" class="se_but2">放棄</a>
                        </div>
					</form>
					
					    
					</div>
					<!--  -->
						
				</div>

			</div>
		</div>
@stop
@section('javascript')
<style>
/*
.fileuploader-thumbnails-input {width:100%;height:100%;}
*/
.write_img {float:none !important;}
</style>
<script>
    $(document).ready(function () {
        images_uploader = $('.reply_pic_choice').fileuploader({
            //extensions: ['jpg', 'png', 'jpeg', 'bmp'],
            changeInput: ' ',
            theme: 'thumbnails',
            enableApi: true,
            addMore: true,
            limit: 20,
            thumbnails: {
                box: '<div class="fileuploader-items">' +
                    '<ul class="fileuploader-items-list n_ulpic" style="margin-bottom:5px;">' +
                    '<li class="fileuploader-thumbnails-input write_img mabot_10 dt_pa0">'+
                    '<div class="fileuploader-thumbnails-input-inner img dt_heght gir_border" style="background: url({{asset("alert/images/ph_xz01.png")}}); background-size:100%;background-repeat: no-repeat;"></div>' +
                    '</li></ul>' +
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
                    alert(message);
                },
            },
            dragDrop: {
                container: '.fileuploader-thumbnails-input'
            },
            onRemove: function(item) {
                var isRemovable = true;

                if(item.data.isPreload === true){
                    $.ajax({
                        url: "{{route('famous_auth_pic_delete')}}"  + '?{{csrf_token()}}={{now()->timestamp}}',
                        method: "get",
                        data: {
                            pic: item.data.url
                        },
                        success: function(data){
                            //c2("刪除成功")
                            $(".announce_bg").hide();
                            $("#tab02").hide();
                            if(data.length>100 || data=='' || data==undefined) {
                                c5('刪除已完成，請確認檔案已刪除');
                            } else {
                                c5(data);
                            }
                            // if(data.length>4){
                            //     c2(data);
                            // }else {
                            //     c2(data);
                            // }
                            isRemovable = true
                        },
                        error: function(xhr, status, msg){
                            c5("刪除失敗")
                            isRemovable = false
                        }
                    })
                }

                return isRemovable
            },            
            beforeResize: function(listEl,parentEl, newInputEl, inputEl) {

            },             
            afterResize: function(listEl,parentEl, newInputEl, inputEl) {
            }, 
            beforeSubmit: function(e,cur_uploader_api) {        
                
            },  
            afterSubmit: function(e) {        
                document.famous_auth_form.style.display='block';
            }, 
            beforeSubmitedSuccess:function(data,status,xhr,ajaxObj,cur_uploader_api) {

            },
            afterSubmitedSuccess: function(data,status,xhr,ajaxObj,cur_uploader_api) {
                if(data.msg_code=='success') {
                    
                }
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

        resize_before_upload(images_uploader,400,600,'form','json','c5');
    });
</script>
@stop