let date = new Date(Date.parse(e.message.created_at));
let hours = date.getHours();
let minutes = date.getMinutes();
let ele = '<div class="show">' +
    '<div class="msg msg1">' +
        '<img src="@if(file_exists( public_path().$user->meta->pic ) && $user->meta->pic != ""){{$user->meta->pic}} @elseif($user->engroup==2)/new/images/female.png @else/new/images/male.png @endif">' +
        '<p>' +
            @if(!is_null(json_decode($message['pic'],true))) +
                '<i class="msg_input"></i>' +
                '<span id="page" class="marl5">' +
                    '<span class="justify-content-center">' +
                        '<span class="gutters-10 pswp--loaded" data-pswp="">' +
                            '<span style="width: 150px;">' +
                                @foreach(json_decode($message['pic'],true) as $key => $pic)
                                    @if(isset($pic['file_path']))
                                        <a href="{{$pic['file_path'] }}" target="_blank" data-pswp-index="{{ $key }}" class="pswp--item"> +
                                            <img src="{{ $pic['file_path'] }}" class="n_pic_lt"> +
                                        </a> +
                                    @else +
                                        {{ logger("Message pic failed, user id: " . $user->id) }}
                                        {{ logger("to id: " . $to->id) }}
                                    @endif +
                                @endforeach
                            '</span>' +
                        '</span>' +
                    '</span>' +
                    '<font class="sent_ri dr_l @if(!$isVip) novip @endif">' +
                        '<span>' + ("0" + hours).slice(-2) + ':' + ("0" + minutes).slice(-2) + '</span>' +
                        @if(!$isVip && $message['from_id'] == $user->id)
                            '<span style="color:lightgrey;">已讀/未讀</span>' +
                            '<img src="/new/images/icon_35.png" style="position: absolute;float: left;left: 10px; top:20px;-moz-transform:rotate(-25deg);-webkit-transform:rotate(-30deg);">' +
                        @else
                            '<span id="is_read">未讀</span>' +
                        @endif
                    '</font>' +
                '</span>' +
            @else
                '<i class="msg_input"></i>' + e.message.content +
                '<font class="sent_ri dr_l @if(!$isVip) novip @endif">' +
                    '<span>' + ("0" + hours).slice(-2) + ':' + ("0" + minutes).slice(-2) + '</span>' +
                    @if(!$isVip)
                        '<span style="color:lightgrey;">已讀/未讀</span>' +
                        '<img src="/new/images/icon_35.png" style="position: absolute;float: left;left: 10px; top:20px;-moz-transform:rotate(-25deg);-webkit-transform:rotate(-30deg);">' +
                    @else
                        '<span id="is_read">未讀</span>' +
                    @endif
                '</font>' +
            @endif
        '</p>' +
    '</div>' +
'</div>';

$(ele).insertAfter($(".matopj10")[0]);