@include('partials.header')
@include('partials.message')


<body style="padding: 15px;">
    <link href="{{ asset('css/jquery.fileuploader.min.css') }}" media="all" rel="stylesheet">
    <link href="{{ asset('new/css/fileupload.css') }}" media="all" rel="stylesheet">
    <link href="{{ asset('css/font/font-fileuploader.css') }}" media="all" rel="stylesheet">
    <style>
        .fileuploader-icon-remove:after {
            content: none !important;
        } 

        .announce_bg {
            width: 100%;
            height: 100%;
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0px;
            left: 0;
            background: rgba(0,0,0,0.5);
            z-index: 9;
            display: none;
        } 

.bl_tab_aa {
    width: 100%;
    position: fixed;
    top: 8%;
    z-index: 10;
    display: none;
}  

.bl_tab_bb {
    width: 600px;
    margin: 0 auto;
    display: table;
    background: #e2e8ff;
    border: #8a9fef 2px solid;
    border-radius: 5px;
    position: relative;
}


.new_poptk_nn{width: 96%; padding-bottom: 0; padding-top: 0; margin-top:15px; margin-bottom: 15px;}
.new_pot{width:96%;height:auto;margin: 0 auto;color: #666666;padding-bottom: 20px; padding-top:15px; display: block;}
  

.bl_tab{width:36%;background:#e2e8ff; border:#8a9fef 2px solid;border-radius:5px;position: fixed;left:32%;top: 8%;z-index: 10;display:none;}
.bltitle{width:100%; height:45px; line-height:45px; background:#8a9fef; color:#ffffff; text-align:center; font-size:18px;}
.bltitle span{ float:left; margin-left:15px;}
.blnr{width:95%; margin:0 auto; display:table; /*padding:30px 0 50px 0;color:#666666;*/color:#6783c7;font-size: 18px;}
.bltext{ text-align:center; word-break:break-word;}
.bl_gb{position:absolute; top:9px; right:5px;}
.bl_gb img{width:30px;}
.blinput {
width: 100%;
height: 35px;
line-height: 35px;
border: #cccccc 1px solid;
border-radius: 3px;
}

.n_ulhh{width:45px;height:45px;position:absolute; top:0; left:0; z-index:3}
.n_ulhh img{width:100%}

}
.n_blnr01 {width:90%;margin: 0 auto;color: #666666;padding-bottom: 20px; padding-top: 28px;}
.n_nutext{width:100%; min-height:100px; background:#ffffff; border:none; padding:5px; color:#666666; border-radius:5px;}
.n_blbut{margin-top:15px;}

.n_bbutton{/*width:90%;*/ margin:0 auto; display:table;margin-top:15px}
.n_bbutton span{width:50%; float:left}
.n_left{ float:right;width:120px;height: 40px;background: #8a9ff0;border-radius: 200px;color: #ffffff;text-align: center;line-height: 40px;font-size: 16px; margin-right:11px;}
.n_left:hover{color:#ffffff;box-shadow:inset 0px 15px 10px -10px #4c6ded,inset 0px -10px 10px -20px #4c6ded;}
.n_right{ float:left;width:120px;height: 40px;background: #ffffff; border: #8a9ff0 1px solid;border-radius: 200px;color: #8a9ff0;text-align: center;line-height: 40px;font-size: 16px; margin-left:11px;}
.n_right:hover{color:#ffffff;box-shadow:inset 0px 15px 10px -10px #516cd4,inset 0px -10px 10px -20px #516cd4; background:#8a9ff0}

.n_bllbut{height: 40px;background: #8a9ff0;border-radius: 200px;color: #ffffff !important;text-align: center;line-height: 40px;display: table;margin: 0 auto; padding:0 60px;font-size:16px; margin-top:15px; cursor: pointer;}
.n_bllbut:hover{color:#ffffff;box-shadow:inset 0px 15px 10px -10px #4c6ded,inset 0px -10px 10px -20px #4c6ded;}

.n_fengs{width:95%; margin:0 auto;display:table; line-height:25px;}
.n_fengs img{ height:25px;margin-right:10px;}
.n_fengs span{width: calc(100% - 35px); float:right}

.n_jianj{position:absolute; top:0; z-index:4;width:100%;}
.n_jianj a{ background:#e54f72; line-height:25px; color:#ffffff; border-radius:0 0px 50px 50px; margin:0 auto; padding:0px 20px; display:table}

.n_input{width:100%; display:table}
.n_input dt{width:100%; float:left; margin-bottom:15px;}
.n_input dt span{width:100%; font-size:15px; color:#666666; display:table; line-height:35px; font-weight:normal}
.n_input dt i{ color:#fd5678; font-style:normal}
    
    </style>

    <h4>{{ $user->name }} 與 {{ $admin->name }} 的所有訊息</h1>
    <table class="table table-hover table-bordered" id="table-message">
        <tr>
            <th width="12%">發訊</th>
            <th width="12%">收訊</th>
            <th width="45%">內容</th>
            <th>上傳照片</th>
            <th width="5%">狀態</th>
            <th width="12%">發訊時間</th>
        </tr>
        @forelse ($messages as $message)
            <tr>
                <td>
                    @if($message->from_id == $user->id) 
                        <a href="{{ route('users/advInfo', [$user->id]) }} ">
                            <p @if($user->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                                {{ $user->name }}
                                @if($user->vip)
                                    @if($user->vip=='diamond_black')
                                        <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                    @else
                                        @for($z = 0; $z < $user->vip; $z++)
                                            <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                        @endfor
                                    @endif
                                @endif
                                @for($i = 0; $i < $user->tipcount; $i++)
                                    👍
                                @endfor
                                @if(!is_null($user->isBlocked))
                                    @if(!is_null($user->isBlocked->expire_date))
                                        @if(round((strtotime($user->isBlocked->expire_date) - getdate()[0])/3600/24)>0)
                                            {{ round((strtotime($user->isBlocked->expire_date) - getdate()[0])/3600/24 ) }}天
                                        @else
                                            此會員登入後將自動解除封鎖
                                        @endif
                                    @elseif(isset($user->isBlocked->type))
                                        (隱性)
                                    @else
                                        (永久)
                                    @endif
                                @endif

                                @if($message->is_row_delete_1 == $user->id || $message->is_row_delete_2 == $user->id || $message->is_single_delete_1 == $user->id || $message->is_single_delete_2 == $user->id)
                                    (刪)
                                @endif
                            </p>
                        </a> 
                    @else
                        <a href="{{ route('users/advInfo', [$admin->id]) }} ">
                            <p @if($admin->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                                {{ $admin->name }}
                                @if($admin->vip)
                                    @if($admin->vip=='diamond_black')
                                        <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                    @else
                                        @for($z = 0; $z < $admin->vip; $z++)
                                            <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                        @endfor
                                    @endif
                                @endif
                                @for($i = 0; $i < $admin->tipcount; $i++)
                                    👍
                                @endfor
                                @if(!is_null($admin->isBlocked))
                                    @if(!is_null($admin->isBlocked->expire_date))
                                        @if(round((strtotime($admin->isBlocked->expire_date) - getdate()[0])/3600/24)>0)
                                            {{ round((strtotime($admin->isBlocked->expire_date) - getdate()[0])/3600/24 ) }}天
                                        @else
                                            此會員登入後將自動解除封鎖
                                        @endif
                                    @elseif(isset($admin->isBlocked->type))
                                        (隱性)
                                    @else
                                        (永久)
                                    @endif
                                @endif

                                @if($message->is_row_delete_1 == $admin->id || $message->is_row_delete_2 == $admin->id || $message->is_single_delete_1 == $admin->id || $message->is_single_delete_2 == $admin->id)
                                    (刪)
                                @endif
                            </p>
                        </a>  
                    @endif
                </td>
                <td>
                    @if($message->from_id != $user->id) 
                        <a href="{{ route('users/advInfo', [$user->id]) }} ">
                            <p @if($user->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                                {{ $user->name }}
                                @if($user->vip)
                                    @if($user->vip=='diamond_black')
                                        <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                    @else
                                        @for($z = 0; $z < $user->vip; $z++)
                                            <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                        @endfor
                                    @endif
                                @endif
                                @for($i = 0; $i < $user->tipcount; $i++)
                                    👍
                                @endfor
                                @if(!is_null($user->isBlockedReceiver))
                                    @if(!is_null($user->isBlockedReceiver->expire_date))
                                        @if(round((strtotime($user->isBlockedReceiver->expire_date) - getdate()[0])/3600/24)>0)
                                            {{ round((strtotime($user->isBlockedReceiver->expire_date) - getdate()[0])/3600/24 ) }}天
                                        @else
                                            此會員登入後將自動解除封鎖
                                        @endif
                                    @elseif(isset($user->isBlockedReceiver->type))
                                        (隱性)
                                    @else
                                        (永久)
                                    @endif
                                @endif
                            </p>
                        </a> 
                    @else 
                        <a href="{{ route('users/advInfo', [$admin->id]) }} ">
                            <p @if($admin->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                                {{ $admin->name }}
                                @if($admin->vip)
                                    @if($admin->vip=='diamond_black')
                                        <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                    @else
                                        @for($z = 0; $z < $admin->vip; $z++)
                                            <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                        @endfor
                                    @endif
                                @endif
                                @for($i = 0; $i < $admin->tipcount; $i++)
                                    👍
                                @endfor
                                @if(!is_null($admin->isBlockedReceiver))
                                    @if(!is_null($admin->isBlockedReceiver->expire_date))
                                        @if(round((strtotime($admin->isBlockedReceiver->expire_date) - getdate()[0])/3600/24)>0)
                                            {{ round((strtotime($admin->isBlockedReceiver->expire_date) - getdate()[0])/3600/24 ) }}天
                                        @else
                                            此會員登入後將自動解除封鎖
                                        @endif
                                    @elseif(isset($admin->isBlockedReceiver->type))
                                        (隱性)
                                    @else
                                        (永久)
                                    @endif
                                @endif
                            </p>
                        </a>  
                    @endif
                </td>
                <td>
                    <p style="word-break:break-all;">{{ $message->content }}</p>
                </td>
                <td class="evaluation_zoomIn">
                    @php
                        $messagePics=is_null($message->pic) ? [] : json_decode($message->pic,true);
                    @endphp
                    @if(isset($messagePics))
                        @foreach($messagePics as $messagePic)
                            <li style="float:left;margin:2px 2px;list-style:none;display:block;white-space: nowrap;width: 135px;">
                                <img src="{{ $messagePic['file_path'] }}" style="max-width:130px;max-height:130px;margin-right: 5px;">
                            </li>
                        @endforeach
                    @endif
                </td>
                <td nowrap>{{ $message->unsend?'已收回':'' }}</td>
                <td nowrap>{{ $message->created_at }}</td>
            </tr>
        @empty
            沒有訊息
        @endforelse
    </table>
    <div class='pagination-container' >
        <nav>
            <ul class="pagination">
                <li data-page="prev" ><span> < <span class="sr-only">(current)</span></span></li>
                <li data-page="next" id="prev"><span> > <span class="sr-only">(current)</span></span></li>
            </ul>
        </nav>
    </div>
    @if(request()->from_videoChat==1)
    <h4>視訊驗證user提出問題</h4>
    <div style="margin-bottom:4%;">
        {!!nl2br($user->video_verify_memo?$user->video_verify_memo->user_question:'')!!}
    </div>
    @endif
    <h4>發送站長訊息給 {{$user->name}}(收件者)</h4>
    <form action="{{ route('admin/send', $user->id) }}{{request()->from_videoChat?'?from_videoChat='.request()->from_videoChat:''}}" id='message' method='POST'>
        {!! csrf_field() !!}
        <input type="hidden" value="{{ $admin->id }}" name="admin_id">
        <input type="hidden" value="1" name="chat_with_admin">
        <textarea name="msg" id="msg2" class="form-control" cols="80" rows="5">{{request()->from_videoChat==1 && $user->video_verify_memo?$user->video_verify_memo->user_question:''}}</textarea>
        <br>
        <button type='submit' class='text-white btn btn-primary'>送出</button>
        @if($user->is_admin_chat_channel_open)
        <button type="button" class="btn btn-dark" onclick="closeChat({{$user->id}})">結束對話</button>
        @endif
        <button type='button' class='text-white btn btn-success'  onclick="tab_uploadPic();">上傳</button>
    </form>
<div class="announce_bg" id="announce_bg" onclick="tab_uploadPic_close();"></div>    
<div class="bl_tab_aa" id="tab_uploadPic" style="display: none;">
    <form id="form_uploadPic" action="{{ route('admin/send', $user->id) }}{{request()->from_videoChat?'?from_videoChat='.request()->from_videoChat:''}}"  method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="userId" value="{{ $admin->id }}">
        <input type="hidden" name="from" value="{{ $admin->id }}">
        <input type="hidden" name="to" value="{{ $user->id }}">
        <input type="hidden" name="msg" value="">
        <input type="hidden" name="m_time" @if(isset($m_time)) value="{{ $m_time }}" @else value="" @endif>
        <input type="hidden" name="{{ \Carbon\Carbon::now()->timestamp }}"
            value="{{ \Carbon\Carbon::now()->timestamp }}">
        <input type="hidden" name="parent" class="message_parent" value="">
        <input type="hidden" name="is_truth" id="is_truth_of_form_uploadPic" value="0">
        <input type="hidden" name="client_id" class="client_id" value="">
        <input type="hidden" name="chat_with_admin" value="1">
        <input type="hidden" value="{{ $admin->id }}" name="admin_id">
        <div class="bl_tab_bb">
            <div class="bltitle"><span style="text-align: center; float: none;">上傳照片</span></div>
            <div class="new_pot1 new_poptk_nn new_height_mobile ">
                <div class="fpt_pic">
                    <input id="images" type="file" name="images">
                    <div style="text-align:center;">
                        <span class="alert_tip" style="color:red;"></span>
                    </div>
                    <div class="n_bbutton" style="margin-top:0px;">
                        <a class="n_bllbut" onclick="form_uploadPic_submit()">送出</a>
                    </div>
                </div>
            </div>
            <a onclick="tab_uploadPic_close()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
        </div>
    </form>
</div>    
</body>

<script src="/js/vendors.bundle.js" type="text/javascript"></script>
<link type="text/css" rel="stylesheet" href="/new/css/app.css">
<script>
jQuery(document).ready(function(){
    getPagination('#table-message');
});
    function getPagination(table) {
        var lastPage = 1;
        var trnum = 0;
        var maxRows = 10;
        $('.pagination')
            .find('li')
            .slice(1, -1)
            .remove();

        var totalRows = $(table + ' tbody tr').length;

        if (totalRows <= maxRows) {
            $('.pagination').hide();
        } else {
            $('.pagination').show();
        }

        $(table + ' tr:gt(0)').each(function() {
            trnum++;
            if (trnum > maxRows) {
                $(this).hide();
            }
            if (trnum <= maxRows) {
                $(this).show();
            }
        });
        if (totalRows > maxRows) {
            var pagenum = Math.ceil(totalRows / maxRows);
            for (var i = 1; i <= pagenum; ) {
            $('.pagination #prev')
                .before('<li data-page="' + i + '">\ <span>' + i++ + '<span class="sr-only">(current)</span></span>\ </li>')
                .show();
            }
        }
        $('.pagination [data-page="1"]').addClass('active');
        $(document).on('click', '.pagination li', function(e) {
            e.stopImmediatePropagation();
            e.preventDefault();
            var pageNum = $(this).attr('data-page'); 

            if (pageNum == 'prev') {
            if (lastPage == 1) {
                return;
            }
            pageNum = --lastPage;
            }
            if (pageNum == 'next') {
            if (lastPage == $('.pagination li').length - 2) {
                return;
            }
            pageNum = ++lastPage;
            }

            lastPage = pageNum;
            var trIndex = 0;
            $('.pagination li').removeClass('active');
            $('.pagination [data-page="' + lastPage + '"]').addClass('active');
            limitPagging();
            $(table + ' tr:gt(0)').each(function() {
                trIndex++;
                if (
                    trIndex > maxRows * pageNum ||
                    trIndex <= maxRows * pageNum - maxRows
                ) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        });
        limitPagging();
    }
    function limitPagging(){
        if($('.pagination li').length > 7 ){
                if( $('.pagination li.active').attr('data-page') <= 3 ){
                $('.pagination li:gt(5)').hide();
                $('.pagination li:lt(5)').show();
                $('.pagination [data-page="next"]').show();
            }if ($('.pagination li.active').attr('data-page') > 3){
                $('.pagination li:gt(0)').hide();
                $('.pagination [data-page="next"]').show();
                for( let i = ( parseInt($('.pagination li.active').attr('data-page'))  -2 )  ; i <= ( parseInt($('.pagination li.active').attr('data-page'))  + 2 ) ; i++ ){
                    $('.pagination [data-page="'+i+'"]').show();

                }
            }
        }
    }
    function closeChat(id) {
        $.ajax({
            type: 'POST',
            url: "/admin/users/isChatToggler",
            data:{
                _token: '{{csrf_token()}}',
                user_id: id,
                is_admin_chat_channel_open: 0,
            },
            dataType:"json",
            success: function(res){
                alert('對話已結束')
                location.reload();
        }});
    }
    $('.message_management_btn').on('click', function(){
        $('.main').toggle();
        $('.message').toggle();
        if($(this).text() == '開啟會員對話')
        {
            $(this).text('對話中');
            getPagination('#table-message');
        }
        else if($(this).text() == '對話中')
        {
            $(this).text('開啟會員對話');
        }

    });

    if (window.parent.location.href.match(/from_advInfo=/)){
        if (typeof (history.pushState) != "undefined") {
            var obj = { Title: document.title, Url: window.parent.location.pathname };
            history.pushState(obj, obj.Title, obj.Url);
        } else {
            window.parent.location = window.parent.location.pathname;
        }
    }
</script>
<script src="{{ asset('js/jquery.fileuploader.js') }}" type="text/javascript"></script>
<script>
$(document).ready(function () {
    images_uploader = $('input[name="images"]').fileuploader({
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
            // confirm:function(message, confirm) {
            //     popUpTrueOrFalse(message, function () {
            //         confirm();
            //         gmBtn2();
            //     })
            // }
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
    
    $(".announce_bg").attr('onclick',$(".announce_bg").attr('onclick')+";$('.bl_tab_aa').hide();");
});

    function form_uploadPic_submit()
    {
        var num_of_images=$('.fileuploader-items-list .fileuploader-item').length;
        if(num_of_images==0) {
            $('.alert_tip').text();
            $('.alert_tip').text('請選擇照片');
        }else{
            $('#form_uploadPic').submit();
        }
    }

    function tab_uploadPic() 
    {
        $(".announce_bg").show();
        $("#tab_uploadPic").show();
        $('body').css("overflow", "hidden");
        $('.alert_tip').text('');
    } 

    function tab_uploadPic_close() 
    {
        $(".announce_bg").hide();
        $("#tab_uploadPic").hide();
        $('body').css("overflow", "auto").css("fixed", "");
        $('.alert_tip').text('');
    }    
</script>
    
</html>
