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
        .msg_content {
            display: block;
        }
        .shdel {
            background-color: #ffffff;
            border-radius: 10px;
            height: auto !important;
            bottom: -7px !important;
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
            border: #fd5678 1px solid;
            white-space:nowrap;
        }
        .specific_msg_box {
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
            padding-bottom: 20px;
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
            margin: 0 0 8px -8px;
            padding: 3px 8px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            color: #ababab;
            border-bottom: #ababab 1px solid;
            cursor: pointer;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            line-clamp: 1;
            -webkit-box-orient: vertical;
            word-wrap: break-word;
            word-break: break-all;
        }
        .parent_msg_box img {
            margin-right: 10px;
            height: 15px;
            width: 15px;
            float: initial !important;
        }
        .msg_input{
            z-index: -1;
        }
        .atkbut{width: 88px;padding:5px 5px; background: #fff6f7;border-radius: 10px;box-shadow: 0 5px 5px #ffc1cd;}
        .at_left{position: absolute;left: 0;bottom:-80px; z-index: 99;}
        .at_right{position: absolute;right: 0;bottom:-80px; z-index: 99;}

        .atkbut a{width: 94%;padding:5px 0px;margin: 0 auto; display: table; display: table; font-size: 14px; border-bottom: #ffccd2 1px solid; position: relative;}
        .atkbut a:hover{background: #fff; border-radius: 5px;}
        .atkbut a:last-child{ border-bottom: 0;}

        .he_yuan{width:30px; height:30px; border-radius: 100px; box-shadow:0 10px 10px rgba(255,203,203,0.8); background: #fff; display: table; float: left; margin-right: 4px;}
        .he_left_img{ height:30px;width: 30px;}
        .he_li30{ line-height: 30px; font-style: normal;}
        .hangup {background: #fe92a8;border:0;color:#fff;border-radius: 50px;width: 200px;height: 50px;}
        .jide_but{position: absolute;left:50%;bottom:15px;transform: translate3d(-50%,0,0);}
        @media (max-width:915px) {
            .jide_but {
                width: 80%;
            }
        }
        .jiesu_dh{ margin: 0 auto; text-align: center;  display: table; padding: 8px 50px; cursor: pointer;
        background: radial-gradient(circle farthest-side at 0 0, rgba(255,119,147,0.9) 0%, rgba(255,181,196,0.7) 100%);border-radius:100px; line-height: 30px;
        color: #ffffff !important; font-weight: bold; font-size: 18px;box-shadow: 0 10px 20px rgba(238,84,114,0.4);}
        .jiesu_dh span{ border-radius: 100px; background: rgba(255,255,255,0.05); box-shadow: 0 5px 5px rgba(224,58,91,0.4);width: 30px; height: 30px; float: left; margin-right: 10px;}
        .jiesu_dh span img{ height: 26px; width: 26px; margin: 0 auto; display: table; margin-top: 2px;}
        .jiesu_dh:active{background: radial-gradient(circle farthest-side at 0 0, rgba(255,181,196,0.7) 0%, rgba(255,119,147,0.9) 100%);}
        .pad_bot {
            padding-bottom: 20px;
        }

        @media (max-width: 450px) {
            .pad_bot {
                padding-bottom: 0px;
            }
        }
        .message::-webkit-scrollbar {
            display: none;
        }

        .message {
            -ms-overflow-style: none;
        }

        .message {
            overflow: -moz-hidden-unscrollable;
            /*注意！若只打 hidden，chrome 的其它 hidden 會出問題*/
            height: 100%;
        }
        .chat_show_area::-webkit-scrollbar {display: none; /* Chrome Safari */}
        @media (max-width:913px) {
            .bot {
                display: none;
            }
        }
    </style>
@endsection

@section('app-content')
<div class="container matop70">
    <div class="row">
        <div class="col-sm-2 col-xs-2 col-md-2 dinone">
            @include('new.dashboard.panel')
        </div>
        <div class="col-sm-12 col-xs-12 col-md-10 right_content" >
            <div class="shouxq te_ce">
                <a href="{{url()->previous()}}" class="fa_adbut left"><img src="/new/images/back_icon.png" class="fa_backicon">返回</a><!-- <img src="images/gg2.png" class="xlimg"> -->
                <span class="se_rea">{{ $roomName }}</span>
            </div>

            <div class="message xxi" style="position: relative; overflow-y:hidden;">
                <div class="chat_show_area pad_bot" style="position: relative; max-height: 580px; min-height: 580px; display: flex; flex-direction: column; overflow: auto;">
                    <div id="GoDown" class="GoDown" style="cursor: pointer;">
                        <img src="/images/arrow_bottom_icon.png" style="opacity: 0.4;border: 1px solid;border-radius: 50px; background-color: lightgray;">
                    </div>
                    <a href="goBottom"></a>
                </div>
                <div class="jide_but">
                    <div class="jiesu_dh" onclick="closeChat()"><span><img src="/new/images/zx_x001.png"></span>結束對話</div>
                </div>
            </div>
            

            <div class="se_text_bot" id="message_input">
           
                <form id="anonymousChatSubmit" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="xin_left">
                        <input class="xin_input" placeholder="請輸入" id="content" name="content" type="text">
                        <input type="file" id="file" name="files" style="display: none;" multiple accept=".png, .jpg, .jpeg">
                    </div>
                    <button class="xin_right" id="submit" type="submit" style="border: unset;"><img src="/new/images/fasong.png"></button>
                </form>

            </div>

        </div>
    </div>
</div>
<div class="bl bl_tab" id="show_banned_ele">
    <div class="bltitle">檢舉</div>
    <div class="n_blnr01">
        <form id="reportPostForm" method="post">
            <input name="messageid" type="hidden" />
            {{ csrf_field() }}
            <div class="blnr bltext">
                <textarea name="content" cols="" rows="" id="reportContent" class="n_nutext" style="border-style: none;" maxlength="300" placeholder="請輸入檢舉理由" required=""></textarea>
            </div>
            <div class="n_bbutton">
                <span><a class="n_left" href="javascript:void(0);" id="submitReport" onclick="$('#reportPostForm').submit();">確認</a></span>
                <span><a onclick="show_banned_close()" class="n_right" href="javascript:void(0)">返回</a></span>
            </div>
        </form>
    </div>
    <a id="" onclick="show_banned_close()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>

@stop

@section('javascript')

    <link href="{{ asset('css/jquery.fileuploader.min.css') }}" media="all" rel="stylesheet">
    <link href="{{ asset('new/css/fileupload.css') }}" media="all" rel="stylesheet">
    <link href="{{ asset('css/font/font-fileuploader.css') }}" media="all" rel="stylesheet">
    <script src="{{ asset('js/jquery.fileuploader.js') }}" type="text/javascript"></script>

<script>
    function show_banned_close(){
        $('#reportPostForm input[name="messageid"]').removeAttr('value');
        $(".announce_bg").hide();
        $("#show_banned_ele").hide();
        $('body').css("overflow", "auto");
    }
    function closeChat() {
        $.ajax({
            type: 'POST',
            url: "{{ route('closeChatRoom',['chatid'=>$chatid]) }}",
            data:{
                _token: '{{csrf_token()}}',
            },
            dataType:"json",
            success: function(res){
                location.href="/dashboard/chat2";
        }});
    }
    function realtime_unsend_self(e){
        var unsend_elt = e.find('.msg');
        
            unsend_elt.after(
                        '<div class="sebg matopj10  unsent_msg">'
                            +'<p>您已收回訊息</p>'                              
                        +'</div>');
            unsend_elt.remove();
            e.removeAttr('data-read');
            e.removeClass('show');
    }
    function message_height_resize () {
        var message_max_height,bl_gb_fixed_top,bl_gb_fixed_right;
            var message_height = $(window).height() - $('#message_input').height() - $('.shouxq').height();
            var footer_height = $('.bot').height();
            if($(window).height()<=601){
                message_max_height = message_height - $('.hetop').height() - 50;
            }else{
                message_max_height = message_height - footer_height - $('.hetop').height() - 30;
                $('.se_text_bot').css('bottom',$('.se_text_bot_add_bottom').height() -70);
            }
         
            $('.message').css('height',message_max_height-40);
            $('.message').css('max-height',message_max_height-40);
            $('.chat_show_area').css('min-height',message_max_height-40);
            $('.chat_show_area').css('max-height',message_max_height-40);
    }
    message_height_resize();
    window.onresize = message_height_resize;
    $(document).ready(function() {
        
        
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
        
        function getMessage(mid){
            let url = '{{ route('getAnonymousEvaluationChatMessage',['chatid'=>$chatid]) }}';
            if(mid!='')
                url+= `/${mid}`
            $.ajax({
                url: url,
                type: 'GET',
                cache:false,
                contentType: false,
                processData: false,
                success: function (result) {
                    if(Object.keys([result.data]).length > 0){
                        let messages = result.data;
                        $.each(messages,function(date,msg){
                            let str,images;
                            if($('.sebg.matopj10[data-date='+date+']').length == 0){
                                $('.message.xxi .chat_show_area').prepend(`<div class="sebg matopj10" data-date="${date}">${date}</div>`);
                            }
                            $.each(msg,function(k,message){
                                str='';
                                images='';
                                // console.log(message);
                                if($('div[data-messageid='+message.id+']').length == 0){
                                    if(message.unsend == 0){
                                        let sendtime = new Date(message.created_at*1000).getHours().toString().padStart(2,0)+':'+new Date(message.created_at*1000).getMinutes().toString().padStart(2,0);
                                        if(message.pictures!=null){
                                            $.each(message.pictures,function(k,v){
                                                images+=`<span style="width: 150px;" class="row_pic">
                                                            <a href="/storage/${v}" data-fancybox="gallery_${message.id}" target="_blank">
                                                                <img src="/storage/${v}" class="n_pic_lt">
                                                            </a>
                                                        </span>`;    
                                            })
                                            
                                        }
                                        let reply = '';
                                        if(message.reply_id!=null){
                                            let avatar = $('div[data-messageid='+message.reply_id+'] .msg > img').prop('outerHTML');
                                            let replyContent = $('div[data-messageid='+message.reply_id+']').find('.msg_content').html();
                                            if(!replyContent) {
                                                replyContent = $('div[data-messageid='+message.reply_id+']').find('.n_pic_lt').prop("outerHTML");
                                                
                                            }
                                            
                                            reply += `<span class="parent_msg_box" data-reply="${message.reply_id}">
                                                        ${avatar}
                                                        ${replyContent}
                                                        </span>`;
                                        }
                                        str+= `<div class="${message.role=="sender"?"show":"send"}" data-messageid="${message.id}" data-read="${message.read}">
                                                    <div class="msg  msg1">
                                                        <img src="/new/images/${message.gender==1?"male":"female"}.png">
                                                        <p tabindex="0" class="${reply!=""?'msg_has_parent':''}">
                                                            ${reply!=""?reply:""}
                                                            <i class="msg_input"></i>
                                                            ${message.content?`<span class="msg_content">${message.content}</span>`:''}
                                                            ${ images }
                                                            <font class="sent_ri  ${message.role=="sender"?"dr_l":"dr_r"}">
                                                                <span>${sendtime}</span>
                                                                <span>${message.role=="sender"?(message.read==1?"已讀":"未讀"):""}</span>
                                                            </font>
                                                        </p>
                                                    </div>
                                                </div>`;
                                    }else{
                                        str+=`<div class="" data-messageid="${message.id}">
                                                <div class="sebg matopj10  unsent_msg">
                                                    <p>${message.content}</p>
                                                </div>
                                            </div>`;
                                    }
                                    $('.sebg.matopj10[data-date='+date+']').after(str);
                                }else{
                                    if(message.status){
                                        $('div[data-messageid='+message.id+']').attr('data-read',message.read);
                                        $('div[data-messageid='+message.id+']').find('font.sent_ri').children('span:eq(1)').html(message.role=="sender"?(message.read==1?"已讀":"未讀"):"");
                                    }else{
                                        str =   `<div class="sebg matopj10  unsent_msg">
                                                    <p>${message.content}</p>
                                                </div>`;
                                        $('div[data-messageid='+message.id+']').html(str);
                                        
                                    }
                                }
                            })
                            $('.parent_msg_box').click(function(){ //點擊回覆的訊息，跳至該訊息處
                                let replyid = $(this).data('reply');
                                document.querySelector(`.chat_show_area`).scrollTop = document.querySelector(`div[data-messageid='${replyid}']`).offsetTop;
                            })
                            $('div.show p').unbind('click').click(function(){ //發送者訊息選單
                                $('.atkbut').remove();
                                let mid = $(this).parents('.show').data('messageid');
                                let str = `<font class="atkbut at_right" data-id="${mid}" tabindex="0">
                                            <a href="javascript:void(0)" class="reply">
                                                <span class="he_yuan"><img src="/new/images/ba_03.png" class="he_left_img"></span><i class="he_li30">回覆</i>
                                            </a>
                                            <a href="javascript:void(0)" class="revoke">
                                                <span class="he_yuan"><img src="/new/images/ba_05.png" class="he_left_img"></span><i class="he_li30">收回</i>
                                            </a>
                                        </font>`;
                                if(event.target.nodeName != 'IMG') {
                                    $(this).find('.msg_content').after(str);
                                }
                                
                                $(this).blur(function(){
                                    setTimeout(function(){
                                        $('.atkbut').remove();
                                    },200);
                                })
                                $('.atkbut .reply').click(function(){ //回覆
                                    let mid = $(this).parent('.atkbut').data('id');
                                    console.log(mid);
                                    let avatar= $(this).parents('.show').find('.msg img').clone().html();
                                    console.log(avatar);
                                    let contentFrom = $(this).parents('.show').find('.msg_content').text();
                                    let str =   `<div class="xin_left specific_msg_box" id="specific_msg_box">
                                                    <div class="specific_msg">${avatar+contentFrom}</div>
                                                    <div class="specific_msg_close">
                                                        <a href="javascript:void(0);">Ｘ</a>
                                                    </div>
                                                    <input type="hidden" name="replyid" value="${mid}"/>
                                                </div>`
                                    $('#anonymousChatSubmit').prepend(str);
                                })
                                $('.specific_msg_close a').on('click',function(){
                                    $('#specific_msg_box').remove();
                                })
                                $('.atkbut .revoke').click(function(){ //收回
                                    let thisMsg = $(this).parents('.show');
                                    let mid = $(this).parent('.atkbut').data('id');
                                    $.post('{{ route('revokeAnonymousEvaluationChatMessage',['chatid'=>$chatid]) }}',{'_token':'{{ csrf_token(); }}','_method':'patch','messageid':mid},function(result){
                                        if(result.msg=="OK"){
                                            realtime_unsend_self(thisMsg)
                                        }
                                    })
                                })
                            })

                            $('div.send p').unbind('click').click(function(){ //接收者訊息選單
                                $('.atkbut').remove();
                                let mid = $(this).parents('.send').data('messageid');
                                let str = `<font class="atkbut at_left" data-id="${mid}" tabindex="0">
                                            <a href="javascript:void(0)" class="accusation">
                                                <span class="he_yuan"><img src="/new/images/ba_05.png" class="he_left_img"></span><i class="he_li30">檢舉</i>
                                            </a>
                                            <a href="javascript:void(0)" class="reply">
                                                <span class="he_yuan"><img src="/new/images/ba_03.png" class="he_left_img"></span><i class="he_li30">回覆</i>
                                            </a>
                                        </font>`;
                                if(event.target.nodeName != 'IMG') {
                                    $(this).find('.msg_content').after(str);
                                }
                                $(this).blur(function(){
                                    setTimeout(function(){
                                        $('.atkbut').remove();
                                    },200);
                                })

                                $('.atkbut .reply').click(function(){ //回覆
                                    let mid = $(this).parent('.atkbut').data('id');
                                    console.log(mid);
                                    let avatar= $(this).parents('.send').find('.msg img').clone().html();
                                    console.log(avatar);
                                    let contentFrom = $(this).parents('.send').find('.msg_content').text();
                                    let str =   `<div class="xin_left specific_msg_box" id="specific_msg_box">
                                                    <div class="specific_msg">${avatar+contentFrom}</div>
                                                    <div class="specific_msg_close">
                                                        <a href="javascript:void(0);">Ｘ</a>
                                                    </div>
                                                    <input type="hidden" name="replyid" value="${mid}"/>
                                                </div>`
                                    $('#anonymousChatSubmit').prepend(str);
                                })
                                $('.specific_msg_close a').on('click',function(){
                                    $('#specific_msg_box').remove();
                                })

                                $('.atkbut .accusation').click(function(){ //檢舉
                                    let mid = $(this).parent('.atkbut').data('id');
                                    $(".banned_name").html('');
                                    $(".banned_name").append("<span>檢舉</span>")
                                    $(".announce_bg").show();
                                    $("#show_banned_ele").show();
                                    $('body').css("overflow", "hidden");
                                    $('#reportPostForm input[name="messageid"]').val(mid);
                                })

                            })


                        })
                    }
                    $('.send[data-read=0]').each(function(k,v){
                        setread($(v).data('messageid'));
                    })
                },
                errors: function (result) {},
                compiled: function (result) {},
            })
        }
        setInterval(function(){
            getMessage(0);
        },1000)

        $('#anonymousChatSubmit').on('submit',function(e){
            e.preventDefault();
            let content = $('#content').val();
            // let files = $('#file').val();
            let files = $('input[name="fileuploader-list-files"]').val();
            let formData = new FormData(this);
            // alert(files);
            
            if( content == '' && files == '[]'){
                console.log('請輸入內容');
                c5('請輸入內容');
                return false;
            }else {
                let dt = new Date();
                $.ajax({
                    url: '{{ route('sendAnonymousEvaluationChatMessage',['chatid'=>$chatid]) }}',
                    type: 'POST',
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        if(result.msg=='OK') {
                            $('#content').val('');
                            $('#files').val('');
                            $('input[name="fileuploader-list-files"]').val('');
                            // $('.fileuploader-items').html('');
                            resetSpecificMsgElt();
                            $("#anonymousChatSubmit")[0].reset();
                            setTimeout(function(){
                                document.querySelector('.chat_show_area').scrollTop = document.querySelector('.chat_show_area').firstElementChild.offsetTop
                            },500);

                        }else{
                            console.log('success 請輸入內容');
                            c5(result.msg);
                        }
                    },
                    error:function(e){
                        switch(e.status){
                            case 422:
                            let str = '';
                                $.each(e.responseJSON.errors,function(k,v){
                                    str+=v.join()+'\n';
                                })
                                console.log('error 請輸入內容');
                                c5(str);
                                break;
                        }
                        
                    }
                });
            }
        });

        $('#reportPostForm').on('submit',function(e){
            e.preventDefault();
            let content = $('#reportContent').val();
            let formData = new FormData(this);

            if( content == '' && files == ''){
                c5('請輸入內容');
                return false;
            }else {
                let dt = new Date();
                $.ajax({
                    url: '{{ route('accusationAnonymousEvaluationChatMessage',['chatid'=>$chatid]) }}',
                    type: 'POST',
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        if(result.msg=='OK') {
                            c5('檢舉成功');
                        }else{
                            c5(result.msg);
                        }
                        $("#reportPostForm")[0].reset();
                        show_banned_close();
                    },
                    error:function(e){
                        switch(e.status){
                            case 422:
                            let str = '';
                                $.each(e.responseJSON.errors,function(k,v){
                                    str+=v.join()+'\n';
                                })
                                c5(str);
                                break;
                            default:
                                alert('發生錯誤');
                                location.reload();
                        }
                    }
                });
            }
        })
        function resetSpecificMsgElt() {
            $('.specific_msg').html('');
            $('#specific_msg_box').hide();
        }

        $(".GoDown").click(function() {
            $(".chat_show_area").animate({ scrollTop: $(document).height() }, "slow", function() {
                $(".GoDown").fadeOut( "slow", function() {
                    $(".GoDown").hide();
                });
            });
        });

        $(".GoDown").hide();
        $(".chat_show_area").scroll(function() {
            if($(this).scrollTop() < 0) {
                $(".GoDown").fadeIn( "slow", function() {
                    $(".GoDown").show();
                });
            }else if($(this).scrollTop()==0){
                $(".GoDown").fadeOut( "slow", function() {
                    $(".GoDown").hide();
                });
            }

            if ($(".dinone").is(":visible")) {
                $(".GoDown").css('left', ($(window).width() - $(".dinone").width() - $(".right_content").width()) / 2 + $(".dinone").width() + $(".right_content").width() - 80);
            } else {
                $(".GoDown").css('left', ($(window).width() - $(".right_content").width()) / 2 + $(".right_content").width() - 80)  ;
            }

        });
        $(window).resize(function() {
            if ($(".dinone").is(":visible")) {
                $(".GoDown").css('left', ($(window).width() - $(".dinone").width() - $(".right_content").width()) / 2 + $(".dinone").width() + $(".right_content").width() - 80);
            } else {
                $(".GoDown").css('left', ($(window).width() - $(".right_content").width()) / 2 + $(".right_content").width() - 80);
            }
        });

        function setread(messageid){
            $.post('{{ route('readAnonymousEvaluationChatMessage',['chatid'=>$chatid]) }}',{'_token':'{{ csrf_token(); }}','_method':'patch','messageid':messageid},function(result){
                if(result.status=="OK"){
                    
                }
            })
        }

        function show_chat_message_close(){
            $(".announce_bg").hide();
            $("#show_chat_message").hide();
        }

        $('.announce_bg').on('click', function() {
            $("#show_chat_message").hide();
            $("#show_banned_ele").hide();
        });
    });

</script>
@stack('scripts')
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
@endsection
