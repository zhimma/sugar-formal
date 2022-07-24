@extends('new.layouts.website')

@section('app-content')

<div class="container matop70">
    <div class="row">
        <div class="col-sm-2 col-xs-2 col-md-2 dinone">
            @include('new.dashboard.panel')
        </div>
        <div class="col-sm-12 col-xs-12 col-md-10">
            <div class="shou"><span class="zq_font1">提報可疑對象</span>
                <font class="zq_font2">Submission</font><a href="/dashboard/suspicious_list?s=false" class="toug_back btn_img">
                    <div class="btn_back"></div>
                </a>
            </div>
            <div class="g_pnr">
                <div class="zhp_list">
                    <form action="/dashboard/suspicious_doPosts?{{ csrf_token() }}={{ \Carbon\Carbon::now()->timestamp }}" id="posts" method="POST"  enctype="multipart/form-data">
                        <!--使用者糾紛有見過面-->
                        <div class="zap_ullist">
                            <div class="zap_ullist_a">
                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                <div class="xinzeng_a">
                                    <h2>提報類型
                                        <span class="xzinput">
                                             <select  name="type" class="x_input_a sec1">
                                                 <option value="">請選擇</option>
                                                 <option value="1">使用者糾紛(有見過面)</option>
                                                 <option value="2">車馬費詐騙(沒見過面)</option>
                                             </select>
                                         </span>
                                    </h2>
                                    <h2>糾紛對象
                                        <span class="xzinput">
                                             <select name="target_user_id" class="x_input_a sec1">
                                                 <option value="">請選擇</option>
                                                 @foreach($message_user_list as $user_id)
                                                     @php
                                                        $user=\App\Models\User::findById($user_id);
                                                     @endphp
                                                     <option value="{{ $user_id }}">{{ $user? $user->name : '' }}</option>
                                                 @endforeach
                                             </select>
                                         </span>
                                    </h2>
                                    @if($suspicious_id)
                                        @php
                                            $suspicious_default=\App\Models\Suspicious::where('id', $suspicious_id)->first();
                                            $str_c=substr($suspicious_default->account_text, 0 , strlen($suspicious_default->account_text)-5) ;
                                            $str_star='';
                                            for($i=1 ;$i<=strlen($suspicious_default->account_text)-5;$i++){
                                                $str_star.='*';
                                            }
                                        @endphp
                                        <h2>銀行帳號<span class="xzinput">{{ $str_star.str_replace($str_c, '', $suspicious_default->account_text) }}</span></h2>
                                        <input name="account_text" hidden value="{{ $suspicious_default->account_text }}">
                                        <input name="cheatPlus_suspiciousID" hidden value="{{ $suspicious_default->id }}">
                                    @else
                                        <h2>銀行帳號<span class="xzinput"><input name="account_text" class="xte_input_a" placeholder="請輸入"></span></h2>
                                    @endif
                                    <h2>舉報原因<span class="xzinput"><textarea name="reason" placeholder="請平鋪直敘事發經過，請勿人身攻擊以及情緒用詞![本欄位最大字數300字]" class="xte_text"></textarea></span>
                                    </h2>
                                </div>

                                <style>
                                    .sec1{color: #999; }
                                    .sec2{color:#666;}
                                </style>
                                <div class="txe_title"><span class="iconfont icon-shangchuan"></span>圖片上傳</div>
                                <div class="te_tlh" style="text-align: left;height: auto;padding:8px 8px;">
                                    <span>上傳圖片必須包含以下資訊</span><br>
                                    <span>1.必須有轉帳紀錄，手機截圖或者轉帳收據</span><br>
                                    <span>2.必須有對話紀錄，其中數字與轉帳金額相符</span><br>
                                    <span>3.對話紀錄必須要能把該位使用者與網站暱稱相連接，例如第一句打招呼訊息</span>
                                </div>
                                <div class="zap_photo mabot_10">
                                    <input type="file" name="images" >
                                </div>
                                <div style="color: red;">
                                    請注意，如有偽造圖片上傳，站方後續審核會導致您喪失會員資格，並依情節輕重永鎖相關帳號並不退費，請勿因非詐騙糾紛上傳虛假資訊。
                                </div>
                                <label style="margin:10px 0px;">
                                    <input type="checkbox" name="agree" style="vertical-align:middle;width:20px;height:20px;"/>
                                    <sapn style="vertical-align:middle;">我知道了</sapn>
                                </label>
                            </div>
                        </div>
                        <!-- end -->
                        <div class="n_txbut">
                            {{--<a onclick="tianjia()" class="se_but1 ">確定</a>--}}
                            <div class="se_but1" onclick="send_posts_btn()">確定</div>
                            <a href="/dashboard/suspicious_list?s=false" class="se_but2">取消</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="/new/css/iconfont.css">
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
            limit: 5,
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
    .toug_back{
        background:#fe92a8;
        border-radius:100em;
        height:21px;
        width:21px;
        line-height:19px;
        color:#ffffff;
        text-align:center;
        float:right;
        font-size:13px;
        margin-top:10px;
        margin-right:16px;
    }

    /*厚康追加項目OP*/
    .toug_back embed{
        font-size:13px;
        font-weight:100;
        text-shadow: 0px 0px 5px rgba(231,92,124,1);
        height:21px;
        width:21px;
        margin-left:3px;
    }



    /* 區塊陰影及高光效果 */
    .toug_back .black-shadow{
        box-shadow: 2px 2px 6px 5px rgba(0,0,0,0.4);
        border-radius:100em;
    }/*外陰影*/

    .toug_back .black-shadow02{
        box-shadow: 0 0 3px 0.3px #FF8080;
        border-radius:100em;
    }/*中粉影*/

    .toug_back .inset-shadow{
        box-shadow:inset 1.5px 1.5px 4px 0px rgba(255,255,255,1);
        border-radius:100em;
    }/*內白影*/

    .toug_back .white-emboss{
        box-shadow: -7px -5px 8px 15px rgba(255,255,255,1);
        border-radius:100em;
    }/*外白影*/

    /* 區塊陰影及高光效果ED */
    /*厚康追加項目ED */

    .toug_back:hover{
        color:#ffffff;
        box-shadow:inset 0px 13px 10px -10px #f83964,inset 0px -10px 10px -20px #f83964;
    }
    .toug_back .inset-shadow:hover{
        box-shadow:inset -2px -2px 8px 1px rgba(255,255,255,0.3);
        border-radius:100em;
    }
    .toug_back img{
        height: 14px;
        vertical-align: middle;
        margin-top: -3px;
        margin-right: 2px;
    }

    .btn_img{width:40px; height:31px;margin:8px -5px 0 0;background:none;padding:0;}
    .btn_img>.btn_back{width:100%; height:100%; background: url("/new/images/fanhui.png") no-repeat 0 0; background-size:100%;}
    .btn_img:hover>.btn_back{background-position:0 -31px;}
    .btn_img:hover{box-shadow:unset;}

    .fileuploader-icon-remove:after{
        display: none;
    }
</style>
<script>
    function send_posts_btn() {

        var type = $("[name='type']").val();
        var target_user_id = $("[name='target_user_id']").val();
        var account_text = $("[name='account_text']").val();
        var reason = $("[name='reason']").val();
        var images_cnt = JSON.parse($("[name='fileuploader-list-images']").val()).length;

        if (type == '') {
            c5('請選擇提報類型！');
            return false;
        }
        if (target_user_id == '') {
            c5('請選擇糾紛對象！');
            return false;
        }
        if (account_text == '') {
            c5('請輸入銀行帳號！');
            return false;
        }
        if (account_text.length<10 || account_text.length>16) {
            c5('請輸入10~16字元的銀行帳號！');
            return false;
        }
        if(reason.length <=0 ){
            c5('請輸入舉報原因！');
            return false;
        }
        if(reason.length >300 ){
            c5('舉報原因最多只可輸入300字！');
            return false;
        }
        if(images_cnt <=0 ){
            c5('請選擇圖片上傳！');
            return false;
        }
        if($("[name='agree']").prop('checked')==false){
            c5('請勾選我知道了！');
            return false;
        }
        $("#posts").submit();
    }
    $("[name='reason']").on('change keyup paste', function() {
        if($(this).val().length >300 ){
            c5('舉報原因最多只可輸入300字！');
            return false;
        }
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

