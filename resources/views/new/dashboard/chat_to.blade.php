<script>
    function realtime_to(e){
        $('.announce_bg').hide();
        let m = e.message;
        let date = new Date(Date.parse(m['created_at']));
        let hours = date.getHours();
        let minutes = date.getMinutes();
        let ele = 
        '<form method="post" class="unsend_form"  id="unsend_form_'+m['id']+'" action="{{route('unsendChat')}}">'+           
            '<div class="show">' +
                '<div class="msg msg1">' +
                    '<img src="@if(file_exists( public_path().$user->meta->pic ) && $user->meta->pic != ""){{$user->meta->pic}} @elseif($user->engroup==2)/new/images/female.png @else/new/images/male.png @endif">' +
                    '<p class="'+(m['parent_message']?'msg_has_parent':'')+'">';
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
                                    '<span class="gutters-10 pswp--loaded" data-pswp="">' +
                                        '<span style="width: 150px;">' ;
                                            pics.forEach( function (pic, key, pics)  {
                                                ele = ele + '<a href="' + pic['file_path'] + '" target="_blank" data-pswp-index="' + key +'" class="pswp--item">' +
                                                '<img src="' + pic['file_path'] + '" class="n_pic_lt">'
                                                ;
                                            });
                                        ele = ele + '</span>' +
                                    '</span>' +
                                '</span>' +
                                '<font class="sent_ri dr_l @if(!$isVip) novip @endif">' +
                                    '<span>' + ("0" + hours).slice(-2) + ':' + ("0" + minutes).slice(-2) + '</span>' +
                                    @if(!$isVip)
                                        '<span style="color:lightgrey;">已讀/未讀</span>' +
                                        '<img src="/new/images/icon_35.png" style="position: absolute;float: left;left: 10px; top:20px;-moz-transform:rotate(-25deg);-webkit-transform:rotate(-30deg);">' +
                                    @else
                                        '<span id="is_read" class="' + m['id'] + '">未讀</span>' +
                                    @endif
                                '</font>' +
                            '</span>'+
                            '<a href="javascript:void(0)" class="unsend_a" data-id="'+m['id']+'"   onclick="chatUnsend(this);return false;"  title="收回">'+
                                '<span class="shdel unsend"><span>收回</span></span>'+
                            '</a>' +
                            '<a href="javascript:void(0)" class="specific_reply_doer" onclick="return false;" title="回覆" data-id="' + m['id'] + '">'+
                                '<span class="shdel specific_reply"><span>回覆</span></span>'+
                            '</a>'                       
                            ;
                        }
                        else{
                            ele = ele + '<i class="msg_input"></i><span class="msg_content">' + m['content'] +
                            '</span><font class="sent_ri dr_l @if(!$isVip) novip @endif">' +
                                '<span>' + ("0" + hours).slice(-2) + ':' + ("0" + minutes).slice(-2) + '</span>' +
                                @if(!$isVip)
                                    '<span style="color:lightgrey;">已讀/未讀</span>' +
                                    '<img src="/new/images/icon_35.png" style="position: absolute;float: left;left: 10px; top:20px;-moz-transform:rotate(-25deg);-webkit-transform:rotate(-30deg);">' +
                                @else
                                    '<span id="is_read" class="' + m['id'] + '">未讀</span>' +
                                @endif
                            '</font>'+
                            '<a href="javascript:void(0)"  class="unsend_a" data-id="'+m['id']+'"  onclick="chatUnsend(this);return false;"  title="收回">'+
                                '<span class="shdel_word unsend"><span>收回</span></span>'+
                            '</a>'   +
                            '<a href="javascript:void(0)" class="specific_reply_doer" onclick="return false;" title="回覆" data-id="' + m['id'] + '">'+
                                '<span class="shdel_word specific_reply"><span>回覆</span></span>'+
                            '</a>'                      
                            ;
                        }
                    ele = ele + '</p>' +
                '</div>' +
            '</div>'+
        '</div>';
        if($(".sebg.matopj10").length == 0){
            $(".message.pad_bot").prepend('<div class="sebg matopj10">' + date.getFullYear() + "-" + ("0" + (date.getMonth() + 1)).slice(-2) + "-" + ("0" + (date.getDate())).slice(-2) + '</div>');
        }
        $(ele).insertAfter($(".matopj10")[0]);
    }
</script>