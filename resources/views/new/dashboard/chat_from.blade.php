<script>
    function realtime_from(e){
        let m = e.message;
        let date = new Date(Date.parse(m['created_at']));
        let hours = date.getHours();
        let minutes = date.getMinutes();
        let ele = '<div class="send">' +
            '<div class="msg">' +
            '<a class="chatWith" href="{{ url('/dashboard/viewuser/' . $to->id ) }}">' +
                '<img class="@if($isBlurAvatar) blur_img @endif" src="@if(file_exists( public_path().$to->meta->pic ) && $to->meta->pic != ""){{$to->meta->pic}} @elseif($to->engroup==2)/new/images/female.png @else/new/images/male.png  @endif">' +
            '</a>' +
            '<p>';
            if(m['pic']){
                pics = JSON.parse(m['pic']);
                ele = ele + '<i class="msg_input"></i>' +
                    '<span id="page" class="marl5">' +
                    '<span class="justify-content-center">' +
                    '<span class="gutters-10 pswp--loaded" data-pswp="">' +
                    '<span style="width: 150px;">';
                    pics.forEach( function (pic, key, pics)  {
                        ele = ele + '<a href="' + pic['file_path'] + '" target="_blank" data-pswp-index="' + key +'" class="pswp--item">' +
                            '<img src="' + pic['file_path'] + '" class="n_pic_lt">' +
                            '</a>';
                    });
                ele = ele + '</span>' +
                    '</span>' +
                    '</span>' +
                    '<font class="sent_ri dr_r">' +
                    '<span>' + ("0" + hours).slice(-2) + ':' + ("0" + minutes).slice(-2) + '</span>' +
                            '</font>' +
                    '</span>' +
                    '<a href="javascript:void(0)" class="" onclick="banned(\'' + m['id'] + '\',\'{{ $to->id }}\',\'{{ $to->name }}\');" title="檢舉">' +
                        '<span class="shdel_word"><span>檢舉</span></span>' +
                    '</a>';
            }
            else{
                ele = ele + '<i class="msg_input"></i>' + m['content'] +
                    '<a href="javascript:void(0)" class="" onclick="banned(\'' + m['id'] + '\',\'{{ $to->id }}\',\'{{ $to->name }}\');" title="檢舉">' +
                    '<span class="shdel_word"><span>檢舉</span></span>' +
                    '</a>' +
                    '<font class="sent_ri dr_r">' +
                        '<span>' + ("0" + hours).slice(-2) + ':' + ("0" + minutes).slice(-2) + '</span>' +
                    '</font>';
            }
            ele = ele + '</p>' +
                '</div>' +
            '</div>';

        $(ele).insertAfter($(".matopj10")[0]);
    }
</script>