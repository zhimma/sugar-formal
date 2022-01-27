<script>
    function realtime_from(e){
        $('.announce_bg').hide();
        let m = e.message;
        let date = new Date(Date.parse(m['created_at']));
        let hours = date.getHours();
        let minutes = date.getMinutes();
        let ele = 
        '<div class="send" id="chat_msg_'+m['id']+'">' +
            '<div class="msg">' +
            '<a class="chatWith" href="{{ url('/dashboard/viewuser/' . $to->id ) }}">' +
                '<img class="@if($isBlurAvatar) blur_img @endif" src="@if(file_exists( public_path().$to->meta->pic ) && $to->meta->pic != ""){{$to->meta->pic}} @elseif($to->engroup==2)/new/images/female.png @else/new/images/male.png  @endif">' +
            '</a>' +
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
                pics = JSON.parse(m['pic']);
                ele = ele + '<i class="msg_input"></i>' +
                    '<span id="page" class="marl5">' +
                    '<span class="justify-content-center">' +
                    '<span class="gutters-10 pswp--loaded" data-pswp="">' +
                    '<span style="width: 150px;">';
                    pics.forEach( function (pic, key, pics)  {
                        ele = ele + '<a href="' + pic['file_path'] + '" target="_blank" data-pswp-index="' + key +'" class="pswp--item">' +
                            '<img src="' + pic['file_path'] + '" class="n_pic_lt">'
                            ;
                    });
                ele = ele + '</span>' +
                    '</span>' +
                    '</span>' +
                    '<font class="sent_ri dr_r">' +
                    '<span>' + ("0" + hours).slice(-2) + ':' + ("0" + minutes).slice(-2) + '</span>' +
                            '</font>' +
                    '</span>' +
                    '<a href="javascript:void(0)" class="" onclick="banned(\'' + m['id'] + '\',\'{{ $to->id }}\',\'{{ $to->name }}\');" title="檢舉">' +
                        '<span class="shdel" style="border: #fd5678 1px solid; width: auto;"><span>檢舉</span></span>' +
                    '</a>'+
                    '<a href="javascript:void(0)" class="specific_reply_doer" onclick=" return false;" title="回覆" data-id="'+m['id']+'">'+
                        '<span class="shdel specific_reply"><span>回覆</span></span>'+
                    '</a>'                    
                    ;
            }
            else{
                ele = ele + '<i class="msg_input"></i><span class="msg_content">' + m['content'] +
                    '</span><a href="javascript:void(0)" class="" onclick="banned(\'' + m['id'] + '\',\'{{ $to->id }}\',\'{{ $to->name }}\');" title="檢舉">' +
                    '<span class="shdel_word"><span>檢舉</span></span>' +
                    '</a>' +
                    '<a href="javascript:void(0)" class="specific_reply_doer" onclick=" return false;" title="回覆" data-id="'+m['id']+'">'+
                        '<span class="shdel_word specific_reply"><span>回覆</span></span>'+
                    '</a>' +                    
                    '<font class="sent_ri dr_r">' +
                        '<span>' + ("0" + hours).slice(-2) + ':' + ("0" + minutes).slice(-2) + '</span>' +
                    '</font>';
            }
            ele = ele + '</p>' +
                '</div>'+
            '</div>';

        $(ele).insertAfter($(".matopj10")[0]);
    }
</script>