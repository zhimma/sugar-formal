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

.fileuploader-items .fileuploader-item .fileuploader-action.fileuploader-action-remove i:after {content:none !important;}
</style>
@stop
@section('app-content')
        <style>
        .write_img{background-color:transparent; border-radius: 15px;}
        .write_img .img{background-color: #fff;border-radius: 15px}
        @media (max-width: 414px){
        .dt_pa0 {padding-top: 0;height: 110px;}
        }
        </style>
		<div class="container matop70">
			<div class="row">
				<div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
				</div>
				<div class="col-sm-12 col-xs-12 col-md-10">
					<div class="shou"><span>名人認證</span>
						<font>Certification</font>
                        <a href="{{request()->server('HTTP_REFERER')?request()->server('HTTP_REFERER'):route('real_auth')}}" class="toug_back btn_img" style=" position: absolute; right:20px;">
                            <div class="btn_back"></div>
                        </a>
					</div>
					<div class="gjrz_nr01 ga_bot70">
						<div class="gjr_b"><img src="{{asset('posts/images/gj_bt2.png')}}"></div>
						<div class="renz_n">請提供相關資料</div>
						<form name="famous_auth_form" method="post" action="{{route('famous_auth_save')}}" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        @include('auth.real_auth_question_tpl')
					
                        <div class="n_txbut g_inputt40">
                              <a href="javascript:void(0);" class="se_but1" onclick="return is_form_have_any_change(this) && document.getElementById('famous_auth_submit').click();">
                              @if($service->isPassedByAuthTypeId(3))
                                送出異動申請
                              @else
                                我同意
                              @endif  
                              </a>
                              <a href="{{route('real_auth')}}" class="se_but2">放棄</a>
                        </div>
                        <input type="submit" style="display:none;" id="famous_auth_submit" />					
                        </form>
					
					    
					</div>
				</div>
			</div>
		</div>
@stop
@section('javascript')
<style>
.write_img {float:none !important;}
</style>
<script>
    function is_form_have_any_change(dom) {
        var nowElt = $(dom);
        var nowFormElt = nowElt.closest('form');
        var check_rs = false;
        var ans_chain_str = '';
        
        $.each(nowFormElt.serializeArray(), function( index, value ) {
                
                if(value.name=='_token') return;
                if(value.name.indexOf('reply_pic_')>=0) return;
                ans_chain_str+=value.value;
                if(check_rs) return;
                var now_form_org_ans = nowFormElt.find('.form_org_ans_'+value.name.replaceAll(']','_').replaceAll('[','_')).data('form_org_ans');
                if(now_form_org_ans==undefined) now_form_org_ans='';
                
                
                if(check_rs==false && now_form_org_ans!=undefined && value.value!=now_form_org_ans) {
                    check_rs = true;
                }
        });
        
        images_uploader.each(function(upl_index,uplder){
            if(check_rs) return;
            var uplder_api = $.fileuploader.getInstance($(uplder));
            if(uplder_api.getChoosedFiles().length>0) {
                check_rs=true;
            }
        });

        if(check_rs==false) {
            c5('無法送出！您尚未'+(ans_chain_str==''?'填寫':'修改')+'任何答案。');
        }

        return check_rs;
    }
    

    $(document).ready(function () {
        images_uploader = $('.reply_pic_choice').fileuploader({
            extensions: ['jpg', 'png', 'jpeg', 'bmp','gif','heif','heic'],
            notResetApiAfterResizePopupMsg: true,
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
                    c5(message);
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
  
                            $(".announce_bg").hide();
                            $("#tab02").hide();
                            if(data.length>100 || data=='' || data==undefined) {
                                c5('刪除已完成，請確認檔案已刪除');
                            } else {
                                c5(data);
                            }

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
                @if($service->isPassedByAuthTypeId(3))
                var option_limit = api.getOptions()['limit'];
                var unchk_pic_num = $('#unchk_pic_num_of_'+inputEl.attr('id')).val();
                 api.setOption('unchk_pic_num',unchk_pic_num);
                var new_limit = option_limit - unchk_pic_num;

                if(new_limit<0) new_limit=0;
                api.setOption('org_limit',option_limit)
                api.setOption('limit',new_limit);
                @endif
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
                    filesLimit: function(options,listEl, parentEl, newInputEl, inputEl) {
                            var filesLimit_error_msg = '最多上傳 ${limit} 張圖片！';
                            @if($service->isPassedByAuthTypeId(3))
                            var api = $.fileuploader.getInstance(inputEl.get(0));
                            var api_options = api.getOptions();
                            var option_limit = api_options['limit'];
                            var unchk_pic_num = api_options['unchk_pic_num'];                           
                            var passed_pic_num = api.getAppendedFiles().length;
                            var allow_pic_num = option_limit-passed_pic_num;
                            var allow_msg_part = '';
                            var choosed_pic_num = api.getChoosedFiles().length-1;
                            
                            if(allow_pic_num<0) allow_pic_num=0;
                            
                            if(allow_pic_num) {
                                allow_msg_part = '所以最多只能再上傳'+allow_pic_num+'張照片。';
                                
                                if(choosed_pic_num) {
                                    allow_msg_part+='您剛剛已經成功選取了'+choosed_pic_num+'張照片，';
                                }
                                
                                if(choosed_pic_num>0 && choosed_pic_num>=allow_pic_num) {
                                    allow_msg_part+='因此無法再選取照片。本次選取照片失敗。請刪除部分照片後再選取或按送出按鈕上傳'+choosed_pic_num+'張照片。';
                                }
                                else {
                                    if(choosed_pic_num) {
                                        allow_msg_part+='再加上本次選取的照片，';
                                    }
                                    allow_msg_part+='您'+(choosed_pic_num?'已經':'')+'選取了超過'+allow_pic_num+'張的照片，因此本次選取照片失敗。請重新選取照片';
                                    
                                    if(choosed_pic_num) {
                                        allow_msg_part+='或按送出按鈕上傳'+choosed_pic_num+'張照片';
                                    }
                                    
                                }
                                
                            }
                            else allow_msg_part = '已到達'+api_options['org_limit']+'張的限制，因此無法再選取照片。請刪除部分照片後再選取。';
                            
                            filesLimit_error_msg = '此欄位目前有'+passed_pic_num+'張通過審核、';
                            if(unchk_pic_num>0) filesLimit_error_msg+=unchk_pic_num+'張審核中，合計共'+(parseInt(passed_pic_num)+parseInt(unchk_pic_num))+'張的照片，'
                            filesLimit_error_msg+=allow_msg_part;
                            
                            @endif                        
                        
                        return filesLimit_error_msg;
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