@extends('new.layouts.website')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css"
    />
    <style>
        .xin_nleft{
            position: absolute;
            margin-top: -26px;
            margin-left: -26px;
        }
        .fileuploader-icon-remove:after {
            content: none !important;
        }
        .xin_right img {
            /*position: relative;*/
            /*top: 5px;*/
        }
        .removeImg{
            border: unset;
            position: relative;
            float: right;
            left: 5px;
            background: unset;
        }
        .tempImg{
            display: inline-block;

        }
        .tempImg img{
            max-width: 100px;
        }
        .tao_time{ background: #eee; font-size: 12px; padding:5px 5px; margin: 0 auto; display: table; border-radius:100px; color: #999999; margin-bottom: 10px;}

        .msgPics{
            text-align: center;
            position: relative;
        }
        .nickname{
            display: block;
            position: absolute;
            top: -10px;
            color: white;
            border-radius: 10px;
            border: 1px #fe92a8;
            background-color: #fe92a8;
            padding-left: 5px;
            padding-right: 5px;
            left: 5px;
            font-size: 10px;
        }
        .msg>p{
            min-width: 70px;
        }
        .shdel {
            background-color: #ffffff;
            border-radius: 10px;
            /*width: auto !important;*/
            height: auto !important;
            bottom: -7px !important;
            /*border: #fd5678 1px solid;*/

        }

        .shdel>span {
            padding-right: 1px;
            padding-left: 1px;
            font-size: 11px;
            color: #fd5678;
        }
        .shdel.unsend,
        .shdel.unsend>span,
        .shdel.specific_reply {
            width: auto;
        }

        .specific_reply {
            margin-right: 5px;
        }
        .shdel.specific_reply {
            /*right: 20px;*/
            border: #fd5678 1px solid;
            white-space:nowrap;
        }
        .specific_msg_box {
            display: none;
            background-color: #ffdbdb;
            position: relative;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }
        .specific_msg_close {
            width: 20px;
            position: absolute;
            top: 2px;
            right: 0px;
        }

        .specific_msg_close a,
        .specific_msg_close a:visited,
        .specific_msg_close a:hover,
        .specific_msg_close a:active,
        .specific_msg_close a:focus {
            color: #fff;
            font-size: 8px;
            text-decoration: none;
        }
        .specific_msg{
            margin: 5px;
            white-space: nowrap;
            text-overflow: ellipsis;
            width: 300px;
            word-break: break-word;
            overflow: hidden;
        }
        .specific_msg img {
            height: 20px;
            width: 20px;
        }
        .GoDown{
            position: fixed;
            bottom: 60px;
        }
        .msg_has_parent {
            padding-top: 0 !important;
        }
        .msg_has_parent .msg_input {
            top: 28px;
        }
        .show .parent_msg_box {
            background-color: #ffdbdb;
        }

        .send .parent_msg_box {
            background-color: #F0F0F0;
        }
        .parent_msg_box {
            width: calc(100% + 16px);
            /*margin: 0 10px;*/
            margin: 0 0 8px -8px;
            padding: 8px 8px 3px 8px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            color: #ababab;
            border-bottom: #ababab 1px solid;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            word-wrap: break-word;
            word-break: break-all;
        }
        .parent_msg_box img {
            margin-right: 10px;
            height: 20px;
            width: 20px;
            float: initial !important;
        }
        .se_text_bot{
            /*margin-top: 20px;*/
        }
        .msg_input{
            z-index: -1;
        }
        .chat_show_area::-webkit-scrollbar {
            width: 5px;
            height: 8px;
            background-color: #ffe9ee; /* or add it to the track */
        }
        .chat_show_area::-webkit-scrollbar-thumb {
            background: #fe92a8;
        }

        .chat_show_area {
            -ms-overflow-style: -ms-autohiding-scrollbar;
            width: 100%;
            /*瀏覽器滾動條的長度大約是 18px*/
            overflow: auto;
            position: relative;
        }
        .message{
            width: 100%;
        }
    </style>
@endsection

@section('app-content')
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10 right_content">
                <div class="fbuttop"></div>
                <div class="shouxq te_ce">
                    <a href="{{url()->previous()}}" class="fa_adbut left"><img src="/new/images/back_icon.png" class="fa_backicon">返回</a><!-- <img src="images/gg2.png" class="xlimg"> -->
                    <span class="se_rea">匿名聊天室</span>
                </div>

                <div class="message xxi">
{{--                    @php--}}
{{--                    dd($message);--}}
{{--                            @endphp--}}

{{--                    <div class="bangui matopj10">--}}
{{--                        <span><img src="/images/bgui.png"></span>--}}
{{--                        <font>{!! $anonymous_chat_announcement !!}</font>--}}
{{--                    </div>--}}
                <div class="chat_show_area" id="chat_show_area" style="overflow: auto; position: relative; max-height: 580px; display: flex; flex-direction: column;">
                    <livewire:anonymous-chat-show />
                    <div id="GoDown" class="GoDown" style="cursor: pointer;">
                        <img src="/new/images/go_to_end.jpg" style="opacity: 0.4;border: 1px solid #9c9c9c;border-radius: 50px; background-color: #c5c2c3; padding-left: 2px; width: 40px; float: right; margin-right: 10px;">
                    </div>
                </div>

                </div>


                <div class="se_text_bot">
{{--                    <livewire:anonymous-chat-submit />--}}
                    <form id="anonymousChatSubmit" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="xin_left specific_msg_box" id="specific_msg_box">
                            <div class="specific_msg"></div>
                            <div class="specific_msg_close">
                                <a href="javascript:void(0);" onclick="resetSpecificMsgElt();return false;">Ｘ</a>
                            </div>
                        </div>
                        <div class="xin_left">
                            <input class="xin_input" placeholder="請輸入" id="content" name="content" type="text" accept=".png, .jpg, .jpeg">
                            <input type="hidden" name="reply_id" value="" id="reply_id">
                            <input type="file" id="file" name="files" style="display: none;" data-fileuploader-files='' multiple accept=".png, .jpg, .jpeg">
                        </div>
                        <button class="xin_right" id="submit" type="submit" style="border: unset;"><img src="/new/images/fasong.png"></button>
                    </form>

                </div>

            </div>
        </div>
    </div>

    <div class="bl_tab_aa" id="show_banned_ele" style="display: none;">
        <div class="bl_tab_bb">
            <div class="bltitle"><span style="text-align: center; float: none;" id="anonymous_chat_name"></span></div>
            <div class="new_pot new_poptk_nn new_pot001">
                <div class="fpt_pic new_po000">
                    <form id="reportPostForm">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="anonymous_chat_id" id="anonymous_chat_id" value="">
                        <textarea name="content" id="anonymous_chat_report_content" cols="" rows="" class="n_nutext" style="border-style: none;" maxlength="300" placeholder="請輸入檢舉理由" required></textarea>
                        <div class="n_bbutton" style="margin-top:10px;">
                            <div style="display: inline-flex;">
                                <button type="submit" class="n_right" style="border-style: none; background: #8a9ff0; color:#ffffff; float: unset; margin-left: 0px; margin-right: 20px;">送出</button>
                                <button type="reset" class="n_left" style="border: 1px solid #8a9ff0; background: #ffffff; color:#8a9ff0; float: unset; margin-right: 0px;" onclick="show_banned_close()">返回</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <a onclick="show_banned_close()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
        </div>
    </div>

    <div class="bl_tab_aa" id="show_chat_message" style="display: none;">
        <div class="bl_tab_bb">
            <div class="bltitle"><span style="text-align: center; float: none;" id="anonymous_chat_message_name"></span></div>
            <div class="new_pot new_poptk_nn new_pot001">
                <div class="fpt_pic new_po000">
                    <form id="chatMessageForm" action="{{ route('anonymous_chat_message') }}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="anonymous_chat_message_id" id="anonymous_chat_message_id" value="">
                        <textarea name="content" cols="" rows="" class="n_nutext" style="border-style: none;" maxlength="300" placeholder="請輸入內容" required></textarea>
                        <div class="n_bbutton" style="margin-top:10px;">
                            <div style="display: inline-flex;">
                                <button type="submit" class="n_right" style="border-style: none; background: #8a9ff0; color:#ffffff; float: unset; margin-left: 0px; margin-right: 20px;">送出</button>
                                <button type="reset" class="n_left" style="border: 1px solid #8a9ff0; background: #ffffff; color:#8a9ff0; float: unset; margin-right: 0px;" onclick="show_chat_message_close()">返回</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <a onclick="show_chat_message_close()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
        </div>
    </div>


@stop

@section('javascript')

    <link href="{{ asset('css/jquery.fileuploader.min.css') }}" media="all" rel="stylesheet">
    <link href="{{ asset('new/css/fileupload.css') }}" media="all" rel="stylesheet">
    <link href="{{ asset('css/font/font-fileuploader.css') }}" media="all" rel="stylesheet">
    <script src="{{ asset('js/jquery.fileuploader.js') }}" type="text/javascript"></script>
<script>    
    //Layouts
    function messageContentToEnd(){
        var objDiv = document.getElementById("chat_show_area");
        objDiv.scrollTop = objDiv.scrollHeight;
    }
    $(document).ready(function() {

        messageContentToEnd();
        var objDiv = document.getElementById("chat_show_area");

        objDiv.onscroll = function (ev) {
            var checkMoreData = $('#checkMoreData').val();
            if(objDiv.scrollTop == 0 && checkMoreData==1)
            {
                window.livewire.emit('load-more-chat');
            }
        };

        objDiv.onwheel = function(ev) {
            var checkMoreData = $('#checkMoreData').val();
            if(objDiv.scrollTop == 0 && checkMoreData==1 && ev.deltaY < 0)
            {
                window.livewire.emit('load-more-chat');
            }
        };

        // enable fileuploader plugin
        $('#file').fileuploader({
            extensions: ['jpg', 'png', 'jpeg'],
            changeInput: '<a class="xin_nleft "><img src="/new/images/moren_pic.png"></a>',
            theme: 'thumbnails',
            enableApi: true,
            addMore: true,
            limit: 5,
            editor: {
                // editor cropper
                cropper: {
                    // cropper ratio
                    // example: null
                    // example: '1:1'
                    // example: '16:9'
                    // you can also write your own
                    ratio: null,

                    // cropper minWidth in pixels
                    // size is adjusted with the image natural width
                    minWidth: null,

                    // cropper minHeight in pixels
                    // size is adjusted with the image natural height
                    minHeight: null,

                    // show cropper grid
                    showGrid: true
                },

                // editor on save quality (0 - 100)
                // only for client-side resizing
                quality: 70,

            },
            thumbnails: {
                box: '<div class="fileuploader-items">' +
                    '<ul class="fileuploader-items-list">' +
                    // '<li class="fileuploader-thumbnails-input">' +
                    // '<div class="fileuploader-thumbnails-input-inner"><i>+</i></div>' +
                    // '</li>' +
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
                    $(document).scrollTop($(document).height()); //go to end body

                    if($('.fileuploader-items').is(":visible")) {
                        var message_max_height;
                        var message_height = $(window).height() - 42 - $('.shouxq').height();
                        var footer_height;

                        if ($('.bot').is(":visible")) {
                            footer_height = $('.bot').height();
                            $(".GoDown").css('bottom', $('.bot').height() + 102);
                        }else{
                            footer_height = 0;
                            message_height = message_height + 70;
                            $(".GoDown").css('bottom', 60);
                        }

                        if ($('.hetop').is(":visible")) {
                            $('.message').css('padding-left', '30px');
                            $('.bot').show();
                            message_max_height = message_height - footer_height - $('.hetop').height() - 100;
                        } else if ($('.heicon').is(":visible")) {
                            $('.message').css('padding-left', '10px');
                            message_max_height = message_height - footer_height - $('.heicon').height() - 100;
                        }

                        $('.chat_show_area').css('height', message_max_height - $('.fileuploader-items').height());
                        $('.chat_show_area').css('max-height', message_max_height - $('.fileuploader-items').height());
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

                    $(document).scrollTop($(document).height()); //go to end body

                    if(api.getChoosedFiles().length-1 == 0) {
                        var message_max_height;
                        var message_height = $(window).height() - 42 - $('.shouxq').height();
                        var footer_height;

                        if ($('.bot').is(":visible")) {
                            footer_height = $('.bot').height();
                            $(".GoDown").css('bottom', $('.bot').height() + 102);
                        }else{
                            footer_height = 0;
                            message_height = message_height + 70;
                            $(".GoDown").css('bottom', 60);
                        }

                        if ($('.hetop').is(":visible")) {
                            $('.message').css('padding-left', '30px');
                            $('.bot').show();
                            message_max_height = message_height - footer_height - $('.hetop').height() - 120;
                        } else if ($('.heicon').is(":visible")) {
                            $('.message').css('padding-left', '10px');
                            message_max_height = message_height - footer_height - $('.heicon').height() - 120;
                        }

                        $('.chat_show_area').css('height', message_max_height);
                        $('.chat_show_area').css('max-height', message_max_height);
                    }
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



    });

    $('#anonymousChatSubmit').on('submit',function(e){
        e.preventDefault();

        let content = $('#content').val();
        let files = $('#files').val();
        let formData = new FormData(this);

        // alert(files);
        if( content == '' && files == ''){
            c5('請輸入內容');
            return false;
        }else {
            $.ajax({
                url: '{{ route('anonymous_chat_save') }}',
                type: 'POST',
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (result) {
                    if(result.msg=='OK') {
                        // alert($(".chat_show_area").height());
                        $('#content').val('');
                        $('#files').val('');
                        $('#reply_id').val('');
                        $('input[name="fileuploader-list-files"]').val('');
                        // $('.fileuploader-items').html('');
                        resetSpecificMsgElt();
                        $("#anonymousChatSubmit")[0].reset();

                            var message_max_height;
                            var message_height = $(window).height() - 42 - $('.shouxq').height();
                            var footer_height;

                            if ($('.bot').is(":visible")) {
                                footer_height = $('.bot').height();
                                $(".GoDown").css('bottom', $('.bot').height() + 102);
                            }else{
                                footer_height = 0;
                                message_height = message_height + 70;
                                $(".GoDown").css('bottom', 60);
                            }

                            if ($('.hetop').is(":visible")) {
                                $('.message').css('padding-left', '30px');
                                $('.bot').show();
                                message_max_height = message_height - footer_height - $('.hetop').height() - 120;
                            } else if ($('.heicon').is(":visible")) {
                                $('.message').css('padding-left', '10px');
                                message_max_height = message_height - footer_height - $('.heicon').height() - 120;
                            }

                            $('.chat_show_area').css('height', message_max_height);
                            $('.chat_show_area').css('max-height', message_max_height);

                            setTimeout(function() {
                                messageContentToEnd();
                            }, 3200);



                    }else{
                        c5(result.msg);
                    }
                }
            });
        }
    });

    $('#reportPostForm').on('submit',function(e){
        e.preventDefault();

        let content = $('#anonymous_chat_report_content').val();
        let formData = new FormData(this);
        if( content == ''){
            c5('請輸入內容');
            return false;
        }else {
            $.ajax({
                url: '{{ route('anonymous_chat_report') }}',
                type: 'POST',
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (result) {
                    if(result.msg=='OK') {
                        $('#anonymous_chat_report_content').val('');
                        $('#anonymous_chat_id').val('');
                        $(".announce_bg").hide();
                        $("#show_banned_ele").hide();

                        c5('檢舉成功');
                    }else{
                        c5(result.msg);
                    }
                }
            });
        }
    });

    function resetSpecificMsgElt() {
        $('#reply_id').val('');
        $('.specific_msg').html('');
        $('#specific_msg_box').hide();
    }





    function show_banned_close(){
        $(".announce_bg").hide();
        $("#show_banned_ele").hide();
    }


    function show_chat_message_close(){
        $(".announce_bg").hide();
        $("#show_chat_message").hide();
    }

    $('.announce_bg').on('click', function() {
        $("#show_chat_message").hide();
        $("#show_banned_ele").hide();
    });


    var message_max_height;
    var message_height = $(window).height() - $('.se_text_bot').height() - $('.shouxq').height();
    var footer_height = 0;

    if($(window).width()<=912){
        $('.bot').hide();
    }

    if($('.bot').is(":visible")){
        footer_height = $('.bot').height();
        message_height = message_height - 10;
        $(".GoDown").css('bottom', $('.bot').height() + 102);
    }else{
        $(".GoDown").css('bottom', 60);
        $('.se_text_bot').css('position','fixed');
        $('.se_text_bot').css('bottom','5px');
    }

    if($('.hetop').is(":visible")){
        $('.message').css('padding-left','30px');
        message_max_height = message_height - footer_height - $('.hetop').height() - 110;
    }else if($('.heicon').is(":visible")){
        $('.message').css('padding-left','10px');

        message_max_height = message_height - footer_height - $('.heicon').height() - 50;
    }
    // $('.message').css('height',message_max_height);
    // $('.message').css('max-height',message_max_height);
    $('.chat_show_area').css('height',message_max_height);
    $('.chat_show_area').css('max-height',message_max_height);

    $('.se_text').css('width',$('.shouxq').width());
    $(".GoDown").css('width',$('.chat_show_area ').width());



    $(document).ready(function () {
        if($('.chat_show_area').scrollTop()==($('.chat_show_area').prop("scrollHeight") - $('.chat_show_area').height())){
            $(".GoDown").fadeOut( "slow", function() {
                $(".GoDown").hide();
            });
        }
        $(".GoDown").hide();

        $(".GoDown").click(function() {
            $(".chat_show_area").animate({ scrollTop: ($(".chat_show_area").prop("scrollHeight") - $(".chat_show_area").height()) }, "slow", function() {
                $(".GoDown").fadeOut( "slow", function() {
                    $(".GoDown").hide();
                });
            });
        });

        $(".chat_show_area").scroll(function() {
            if($(this).scrollTop() < ($(this).prop("scrollHeight") - $(this).height())) {
                $(".GoDown").fadeIn( "slow", function() {
                    $(".GoDown").show();
                });
            }else if($(this).scrollTop() == ($(this).prop("scrollHeight") - $(this).height())){
                // alert($(this).scrollTop());
                $(".GoDown").fadeOut( "slow", function() {
                    $(".GoDown").hide();
                });
            }
        });

        $(window).resize(function() {
            var message_max_height;
            var message_height = $(window).height() - $('.se_text_bot').height() - $('.shouxq').height();
            var footer_height = 0;

            if($(window).width()<=912){
                $('.bot').hide(); //footer
            }
            if($('.bot').is(":visible")){
                footer_height = $('.bot').height();
                message_height = message_height - 10;
                $(".GoDown").css('bottom', $('.bot').height() + 102);
                $('.se_text_bot').css('position','inherit');
                $('.se_text_bot').css('bottom','5px');
            }else{
                $(".GoDown").css('bottom', 60);
                $('.se_text_bot').css('position','fixed');
                $('.se_text_bot').css('bottom','5px');
            }

            if($('.hetop').is(":visible")){
                $('.message').css('padding-left','30px');
                $('.bot').show();
                message_max_height = message_height - footer_height - $('.hetop').height() - 50;
            }else if($('.heicon').is(":visible")){
                $('.message').css('padding-left','10px');
                message_max_height = message_height - footer_height - $('.heicon').height() - 50;
            }

            $('.chat_show_area').css('height',message_max_height);
            $('.chat_show_area').css('max-height',message_max_height);

            $('.se_text').css('width',$('.shouxq').width());
            $(".GoDown").css('width',$('.chat_show_area').width());

            if($('.chat_show_area').scrollTop()==0){
                $(".GoDown").fadeOut( "slow", function() {
                    $(".GoDown").hide();
                });
            }
        });
    });
</script>

@stack('scripts')
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
@endsection
