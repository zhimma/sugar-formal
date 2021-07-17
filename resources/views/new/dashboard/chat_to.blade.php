<script>
    function realtime_to(e){
        e = e.message;
        if(e.too_many){
            c5(e.content);
            return 0;
        }
        let date = new Date(Date.parse(e['created_at']));
        let hours = date.getHours();
        let minutes = date.getMinutes();
        let ele = '<div class="show">' +
            '<div class="msg msg1">' +
                '<img src="@if(file_exists( public_path().$user->meta->pic ) && $user->meta->pic != ""){{$user->meta->pic}} @elseif($user->engroup==2)/new/images/female.png @else/new/images/male.png @endif">' +
                '<p>';
                    if(e['pic']){
                        let pics = JSON.parse(e['pic']);
                        ele = ele + '<i class="msg_input"></i>' +
                        '<span id="page" class="marl5">' +
                            '<span class="justify-content-center">' +
                                '<span class="gutters-10 pswp--loaded" data-pswp="">' +
                                    '<span style="width: 150px;">' +
                                        pics.forEach( function (pic, key, pics)  {
                                            ele = ele + '<a href="' + pic['file_path'] + '" target="_blank" data-pswp-index="' + key +'" class="pswp--item">' +
                                            '<img src="' + pic['file_path'] + '" class="n_pic_lt">' +
                                            '</a>';
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
                                    // todo: 待實做
                                    '<span id="is_read">未讀</span>' +
                                @endif
                            '</font>' +
                        '</span>';
                    }
                    else{
                        ele = ele + '<i class="msg_input"></i>' + e['content'] +
                        '<font class="sent_ri dr_l @if(!$isVip) novip @endif">' +
                            '<span>' + ("0" + hours).slice(-2) + ':' + ("0" + minutes).slice(-2) + '</span>' +
                            @if(!$isVip)
                                '<span style="color:lightgrey;">已讀/未讀</span>' +
                                '<img src="/new/images/icon_35.png" style="position: absolute;float: left;left: 10px; top:20px;-moz-transform:rotate(-25deg);-webkit-transform:rotate(-30deg);">' +
                            @else
                                // todo: 待實做
                                '<span id="is_read">未讀</span>' +
                            @endif
                        '</font>';
                    }
                ele = ele + '</p>' +
            '</div>' +
        '</div>';

        $(ele).insertAfter($(".matopj10")[0]);
    }
</script>