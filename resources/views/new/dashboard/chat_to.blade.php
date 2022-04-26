<script>
    function realtime_to(e){
        $('.announce_bg').hide();        
        let m = e.message;
        var msg_elt = $('#unsend_form_' + m['id']);
        if(!msg_elt.length) msg_elt = $('#unsend_form_client_' + m['client_id']);        
        if(m['created_at']) {
            let date = new Date(Date.parse(m['created_at']));
            let hours = date.getHours();
            let minutes = date.getMinutes();
            dateString = date.getFullYear() + "-" + ("0" + (date.getMonth() + 1)).slice(-2) + "-" + ("0" + (date.getDate())).slice(-2) ;
            timeString = ("0" + hours).slice(-2) + ':' + ("0" + minutes).slice(-2) ;
        }
        
        if(!msg_elt.length) realtime_to_msg(e);
        
        msg_elt = $('#unsend_form_' + m['id']);
        if(!msg_elt.length) msg_elt = $('#unsend_form_client_' + m['client_id']); 
        
        var msg_speak_mark_elt = msg_elt.find('.msg_input');
        var msg_time_elt = null;
        var msg_pic_elt = null;
        var msg_pic_elt_origin = null;
        if(msg_elt.length) {
            msg_time_elt = msg_elt.find('.sent_ri span').first();
            msg_pic_elt = msg_elt.find('.marl5 .justify-content-center .pswp--loaded span');
            msg_pic_elt_origin = msg_elt.find('.marl5 .justify-content-center .photoOrigin span');
        }

        if(!!msg_time_elt && msg_time_elt.length) {
            msg_time_elt.html(timeString);
        }
          
        if(m['pic']) {
            if(!msg_pic_elt || !msg_pic_elt.length) {
                msg_speak_mark_elt.after(
                    '<span id="page" class="marl5">' +
                        '<span class="justify-content-center">' +
                            '<span class="'
                            +((m['client_id']==undefined)?'':('zoomInPhoto_'+m['client_id'])) 
                            +((m['id']==undefined)?'':(' zoomInPhoto_official_'+m['id'])) 
                            +'  gutters-10 pswp--loaded" data-pswp="">' +
                                '<span style="width: 150px;"></span>' +
                            '</span>'+
                            '<span class="photoOrigin_'+m['client_id']+' gutters-10">' +
                                '<span style="width: 150px;"></span>' +
                            '</span>'+
                        '</span>' +
                    '</span>'
                );
                
                msg_pic_elt = msg_elt.find('.marl5 .justify-content-center .pswp--loaded span');
                msg_pic_elt_origin = msg_elt.find('.marl5 .justify-content-center .photoOrigin span');
            }
            
            let pics = JSON.parse(m['pic']);            
            msg_pic_elt.html('');
            pics.forEach( function (pic, key, pics)  {
                if(key==0){
                    msg_pic_elt.html(msg_pic_elt.html() + '<a href="' + pic['file_path'] + '" target="_blank" data-pswp-index="' + key +'" class="pswp--item">' +
                        '<img src="' + pic['file_path'] + '" class="n_pic_lt n_pic_lt_'+m['client_id']+'"></a>'
                    );
                }else{
                    msg_pic_elt.html(msg_pic_elt.html() + '<a href="' + pic['file_path'] + '" target="_blank" data-pswp-index="' + key +'" class="pswp--item">' +
                        '<img src="' + pic['file_path'] + '" class="n_pic_lt"></a>'
                    );
                }
            });

            msg_pic_elt_origin.html('');
            pics.forEach( function (pic, key, pics)  {
                msg_pic_elt_origin.html(msg_pic_elt_origin.html() + '<a class="pswp--item">' +
                    '<img src="' + pic['file_path'] + '" class="n_pic_lt"></a>'
                )
                ;
            });


            var a_item = $('a.pswp--item');
            for (var i = 0; i < a_item.length; i++) {
                var now_a_item = a_item.eq(i);
                var now_a_item_parent = now_a_item.parent();
                var now_a_item_html = now_a_item.get(0).outerHTML;
                now_a_item.clone().appendTo(now_a_item_parent[0]);
                now_a_item.remove();
            }
            photoswipeSimplify.init({
                history: false,
                focus: false,
            });      
  
    document.querySelector('.pswp').addEventListener('pswpTap', function (e)
    {
        if(zoonIn_m_client) {
            $('.zoomInPhoto_'+zoonIn_m_client).hide();
            $('.photoOrigin_'+zoonIn_m_client).show();            
        }
        else if(zoonIn_m_official) {
            $('.zoomInPhoto_official_'+zoonIn_m_official).hide();
            $('.photoOrigin_official_'+zoonIn_m_official).show();             
        }

        $('.atkbut').hide();
    }, true);  
        } 
    }
    
    function realtime_to_msg(e){
        if($('.fenye .new_page').length>0 && $('.fenye .new_page').html()!='第 1 頁') {
            location.href="{{route('chat2WithUser',$to->id)}}";
            return;
        }
        $('.announce_bg').hide();
        let nowDate = new Date();
        let m = e.message;
        let dateString = nowDate.getFullYear() + "-" + ("0" + (nowDate.getMonth() + 1)).slice(-2) + "-" + ("0" + (nowDate.getDate())).slice(-2) ;
        let timeString = '';
        if(m['created_at']) {
            let date = new Date(Date.parse(m['created_at']));
            let hours = date.getHours();
            let minutes = date.getMinutes();
            dateString = date.getFullYear() + "-" + ("0" + (date.getMonth() + 1)).slice(-2) + "-" + ("0" + (date.getDate())).slice(-2) ;
            timeString = ("0" + hours).slice(-2) + ':' + ("0" + minutes).slice(-2) ;
        }
        if(m['parent_msg_sender_id']=={{$user->id}}) 
        {
            if(m['parent_msg_sender_blurryAvatar']) m['parent_msg_sender_blurryAvatar']=0;
            if(m['parent_msg_sender_isAvatarHidden']) m['parent_msg_sender_isAvatarHidden']=0;
        }

        let ele = 
        '<form method="post" class="unsend_form unsend_form_'+m['id']+' unsend_form_client_'+m['client_id']+'"  id="unsend_form_'+(m['id']?'':'client_')+(m['id']?m['id']:m['client_id'])+'" action="{{route('unsendChat')}}">'+           
            '<div class="show">' +
                '<div class="msg msg1">' +
                    '<img src="@if(file_exists( public_path().$user->meta->pic ) && $user->meta->pic != ""){{$user->meta->pic}} @elseif($user->engroup==2)/new/images/female.png @else/new/images/male.png @endif">' +
                    '<p onclick="msg_click_event('+ "'"+m['client_id']+"'"+')" class="'+(m['parent_message']?'msg_has_parent':'')+' userlogo_'+ m['client_id'] +'">';
                    if(m['parent_message']) {
                        ele = ele
                        + '<span class="parent_msg_box">'
                        +'<img class="'+(m['parent_msg_sender_blurryAvatar']?'blur_img':'')+'" src="'+m['parent_msg_sender_pic']+'">';
                        if(m['parent_message']['content']) ele+=m['parent_message']['content'];
                        if(m['parent_message']['pic']) {
                            let parent_pics = JSON.parse(m['parent_message']['pic']);
                            ele = ele  +
                            '<img src="' + parent_pics[0]['file_path'] + '" class="n_pic_lt">'
                            ;                            
                        }
                        
                        ele+='</span>';
                        ;
                    } 
                    
                        
                        if(m['pic']){
                            let pics = JSON.parse(m['pic']);
                            

                            ele = ele + '<i class="msg_input"></i>' +
                            '<span id="page" class="marl5">' +
                                '<span class="justify-content-center">' +
                                    '<span class="zoomInPhoto_'+m['client_id']+' gutters-10 pswp--loaded" data-pswp="" style="display: none;">' +
                                        '<span style="width: 150px;">' ;
                                        if(Number.isInteger(m['pic'])) {
                                            ele = ele + '<img src="{{asset("new/owlcarousel/assets/ajax-loader.gif")}}" >';
                                        }
                                        else {                       
                                            pics.forEach( function (pic, key, pics)  {
                                                ele = ele + '<a href="' + pic['file_path'] + '" target="_blank" data-pswp-index="' + key +'" class="pswp--item">' +
                                                '<img src="' + pic['file_path'] + '" class="n_pic_lt '+(key==0?('n_pic_lt_'+m['client_id']):'')+'"></a>'
                                                ;
                                            });
                                        }
                                        ele = ele + '</span>' +
                                    '</span>' +
                                    '<span class="gutters-10 photoOrigin photoOrigin_'+m['client_id']+'">' +
                                        '<span style="width: 150px;">' ;
                                        if(Number.isInteger(m['pic'])) {
                                            ele = ele + '<img src="{{asset("new/owlcarousel/assets/ajax-loader.gif")}}" >';
                                        }
                                        else {
                                            pics.forEach( function (pic, key, pics)  {
                                                ele = ele + '<a class="pswp--item">' +
                                                    '<img src="' + pic['file_path'] + '" class="n_pic_lt"></a>'
                                                ;
                                            });
                                        }
                                        ele = ele + '</span>' +
                                    '</span>' +
                                '</span>' +
                                '<font class="sent_ri dr_l @if(!$isVip) novip @endif">' +
                                    '<span>' + (timeString?timeString:'&nbsp;') + '</span>' +
                                    @if(!$isVip)
                                        '<span style="color:lightgrey;">已讀/未讀</span>' +
                                        '<img src="/new/images/icon_35.png" style="position: absolute;float: left;left: 10px; top:20px;-moz-transform:rotate(-25deg);-webkit-transform:rotate(-30deg);">' +
                                    @else
                                        '<span id="is_read" class="is_read ' + m['id'] +(m['client_id']?'client_'+m['client_id']:'') +'">未讀</span>' +
                                    @endif
                                '</font>' +
                            '</span>'+
                            '<font class="atkbut at_right showslide_'+ m['client_id']+'">'+
                                '<a href="javascript:void(0)" class="specific_reply_doer" onclick="specific_reply_doer(this);return false;" data-id="'+ m['id']+'" data-client_id="'+m['client_id']+'">'+
                                    '<span class="he_yuan"><img src="/new/images/ba_03.png" class="he_left_img"></span><i class="he_li30">回覆</i>'+
                                '</a>'+
                                '<a href="javascript:void(0)" onclick="chatUnsend(this);return false;" data-id="'+m['id']+'" data-client_id="'+m['client_id']+'">'+
                                    '<span class="he_yuan"><img src="/new/images/ba_05.png" class="he_left_img"></span><i class="he_li30">收回</i>'+
                                    '@if(!$isVip)<img src="/new/images/icon_36.png" class="img_vip">@endif'+
                                '</a>'+
                                '<a href="javascript:void(0)" ';
                                if(m['id']!=undefined) ele = ele +' data-id="'+m['id']+'"';
                                if(m['client_id']!=undefined) ele = ele +'  data-client_id="'+m['client_id']+'"';
                                if(m['from_id']!=undefined) ele = ele+' data-is_received_msg="'+((m['from_id']!= {{$user->id}})?1:0)+'"';                                
                                ele = ele +' onclick="zoomInPic(this);">'+
                                    '<span class="he_yuan"><img src="/new/images/ba_010.png" class="he_left_img"></span><i class="he_li30">放大</i>'+
                                '</a>'+
                            '</font>';
                        }
                        else{
                            ele = ele + '<i class="msg_input"></i><span class="msg_content">' + m['content'] +
                            '</span><font class="sent_ri dr_l @if(!$isVip) novip @endif">' +
                                '<span>' + (timeString?timeString:'&nbsp;') + '</span>' +
                                @if(!$isVip)
                                    '<span style="color:lightgrey;">已讀/未讀</span>' +
                                    '<img src="/new/images/icon_35.png" style="position: absolute;float: left;left: 10px; top:20px;-moz-transform:rotate(-25deg);-webkit-transform:rotate(-30deg);">' +
                                @else
                                    '<span id="is_read" class="is_read ' + m['id'] +(m['client_id']?'client_'+m['client_id']:'') + '">未讀</span>' +
                                @endif
                            '</font>'+
                            '<font class="atkbut at_right showslide_'+ m['client_id']+'">'+
                                '<a href="javascript:void(0)" class="specific_reply_doer" onclick="specific_reply_doer(this);return false;" data-id="'+ m['id']+'" data-client_id="'+m['client_id']+'">'+
                                    '<span class="he_yuan"><img src="/new/images/ba_03.png" class="he_left_img"></span><i class="he_li30">回覆</i>'+
                                '</a>'+
                                '<a href="javascript:void(0)" onclick="chatUnsend(this);return false;" data-id="'+m['id']+'" data-client_id="'+m['client_id']+'">'+
                                    '<span class="he_yuan"><img src="/new/images/ba_05.png" class="he_left_img"></span><i class="he_li30">收回</i>'+
                                    '@if(!$isVip)<img src="/new/images/icon_36.png" class="img_vip">@endif'+
                                '</a>'+
                            '</font>';
                        }
                    ele = ele + '</p>' +
                '</div>' +
            '</div>'+
        '</div>';
        
        if($(".sebg.matopj10").length == 0  || $(".sebg.matopj10").first().text().trim()!=dateString){
            
            $(".message.pad_bot").prepend('<div class="sebg matopj10">' + dateString+ '</div>');
        }
        $(ele).insertAfter($(".matopj10")[0]);
        $('div.message').scrollTop(0);
    }
</script>