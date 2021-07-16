 <div class="@if($message['from_id'] == $user->id) show @else send @endif">
    <div class="msg @if($message['from_id'] == $user->id) msg1 @endif">
        @if($message['from_id'] == $user->id)
            <img src="@if(file_exists( public_path().$user->meta->pic ) && $user->meta->pic != ""){{$user->meta->pic}} @elseif($user->engroup==2)/new/images/female.png @else/new/images/male.png @endif">
        @else
            <a class="chatWith" href="{{ url('/dashboard/viewuser/' . $msgUser->id ) }}">
            <img class="@if($isBlurAvatar) blur_img @endif" src="@if(file_exists( public_path().$msgUser->meta->pic ) && $msgUser->meta->pic != ""){{$msgUser->meta->pic}} @elseif($msgUser->engroup==2)/new/images/female.png @else/new/images/male.png  @endif">
            </a>
        @endif
        <p>
            @if(!is_null(json_decode($message['pic'],true)))
                <i class="msg_input"></i>
                <span id="page" class="marl5">
                    <span class="justify-content-center">
                        <span class="gutters-10 pswp--loaded" data-pswp="">
                            <span style="width: 150px;">
                                @foreach(json_decode($message['pic'],true) as $key => $pic)
                                    @if(isset($pic['file_path']))
                                        <a href="{{$pic['file_path'] }}" target="_blank" data-pswp-index="{{ $key }}" class="pswp--item">
                                            <img src="{{ $pic['file_path'] }}" class="n_pic_lt">
                                        </a>
                                    @else
                                        {{ logger("Message pic failed, user id: " . $user->id) }}
                                        {{ logger("to id: " . $to->id) }}
                                    @endif
                                @endforeach
                                </span>
                        </span>
                        </span>
                    <font class="sent_ri @if($message['from_id'] == $user->id)dr_l @if(!$isVip) novip @endif @else dr_r @endif">
                        <span>{{ substr($message['created_at'],11,5) }}</span>
                        @if(!$isVip && $message['from_id'] == $user->id)
                            <span style="color:lightgrey;">已讀/未讀</span>
                            <img src="/new/images/icon_35.png" style="position: absolute;float: left;left: 10px; top:20px;-moz-transform:rotate(-25deg);-webkit-transform:rotate(-30deg);">
                        @else
                            <span>@if($message['read'] == "Y" && $message['from_id'] == $user->id) 已讀 @elseif($message['read'] == "N" && $message['from_id'] == $user->id) 未讀 @endif</span>
                        @endif
                    </font>
                </span>
                @if($message['from_id'] != $user->id)
                    <a href="javascript:void(0)" class="" onclick="banned('{{$message['id']}}','{{$msgUser->id}}','{{$msgUser->name}}');" title="檢舉">
                        <span class="shdel" style="border: #fd5678 1px solid; width: auto;"><span>檢舉</span></span>
                    </a>
                @endif
            @else
                <i class="msg_input"></i>{!! nl2br($message['content']) !!}
                @if($message['from_id'] != $user->id)
                    <a href="javascript:void(0)" class="" onclick="banned('{{$message['id']}}','{{$msgUser->id}}','{{$msgUser->name}}');" title="檢舉">
                        <span class="shdel_word"><span>檢舉</span></span>
                    </a>
                @endif
                <font class="sent_ri @if($message['from_id'] == $user->id)dr_l @if(!$isVip) novip @endif @else dr_r @endif">
                    <span>{{ substr($message['created_at'],11,5) }}</span>
                    @if(!$isVip && $message['from_id'] == $user->id)
                        <span style="color:lightgrey;">已讀/未讀</span>
                        <img src="/new/images/icon_35.png" style="position: absolute;float: left;left: 10px; top:20px;-moz-transform:rotate(-25deg);-webkit-transform:rotate(-30deg);">
                    @else
                        <span>@if($message['read'] == "Y" && $message['from_id'] == $user->id) 已讀 @elseif($message['read'] == "N" && $message['from_id'] == $user->id) 未讀 @endif</span>
                    @endif
                </font>
            @endif
        </p>
    </div>
</div>