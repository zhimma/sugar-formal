@if($showSlide)
    <div wire:init="showSlideStopPoll">
@else
    <div wire:poll="showSlideStopPoll">
@endif
    <input type="hidden" value="{{$checkMoreData}}" id="checkMoreData">
    @if($checkMoreData==1)
    <div style="margin: 0 auto; width: 100%; align-content: center; text-align: center;">
        <button wire:click="loadMoreChat" wire:loading.attr="disabled" class="new_gvip_input" style="border-style: none; outline: none; margin-top: 10px; margin-bottom: 10px; line-height: unset; height: 30px;">
            <span wire:loading.remove wire:target="loadMoreChat">顯示更多</span>
            <span wire:loading wire:target="loadMoreChat">Loading...</span>
        </button>
    </div>
    @endif
    {{-- Success is as dangerous as failure. --}}
    @php
        $date_temp='';
    @endphp
    @if(!empty($messages))
        @foreach ($messages as $message)
            @php
                $parentMsg = null;
                $msgUser = \App\Models\User::findById($message->from_id);
                \App\Models\Message::readFromDB($message, $user->id);
                if($message->parent_msg??null) $parentMsg = \App\Models\Message::find($message->parent_msg);
                if(!($parentMsg??null) && $message->parent_client_id??null) $parentMsg = \App\Models\Message::where('client_id',$message->parent_client_id)->first();
                if($parentMsg??null) {
                    if($parentMsg->from_id==$user->id) $parentMsgSender=$user;
                    else {
                        $parentMsgSender = \App\Models\User::findById($parentMsg->from_id);
                        $isBlurParentSender = \App\Services\UserService::isBlurAvatar($parentMsgSender, $user);
                    }
                }
            @endphp

            @if($date_temp != substr($message->created_at,0,10))
                    <div class="sebg matopj10">{{substr($message->created_at,0,10)}}</div>
            @endif

            @if($message->unsend==1 )
                    <div class="">
                        <div class="sebg matopj10  unsent_msg">
                            <p>
                                @if($message->from_id == $user->id)
                                    您已收回訊息
                                @else
                                    {{$to->name}}已收回訊息
                                @endif
                            </p>
                        </div>
                    </div>
            @elseif($message->sys_notice==1 || $msgUser->id == 1049)
                <div class="send">
                    <div class="msg">
                        <img src="/new/images/admin-avatar.jpg">
                        <p style="background: #DDF3FF;">
                            @if($message->pic)
                                @foreach(json_decode($message->pic,true) as $key => $pic)
                                    @if(isset($pic['file_path']))
                                        <a class="pswp--item" href="{{$pic['file_path'] }}" target="_blank"
                                           data-pswp-index="{{ $key }}">
                                            <img src="{{ $pic['file_path'] }}" class="n_pic_lt @if($key==0) n_pic_lt_{{ $message->client_id }} n_pic_lt_official_{{ $message->id }}  @endif">
                                        </a> 
                                    @endif  
                                @endforeach            
                            @else
                            <i class="msg_input_blue"></i>
                            {!! nl2br($message->content) !!}
                            <font class="sent_ri @if(!$isVip) novip @endif dr_r">
                                <span>{{ substr($message->created_at,11,5) }}</span>
                            </font>
                            @endif
                        </p>
                    </div>
                </div>
            @elseif( ($message->sys_notice==0 || $message->sys_notice== null) && $message->unsend==0)
                @if($isVip && $message->from_id == $user->id)
                    @if((!isset($admin) || $to->id != $admin->id) && !isset($to->banned )&& !isset($to->implicitlyBanned))
                        <form method="post"
                              class="unsend_form @if($message->id) unsend_form_{{$message->id}} @endif @if($message->client_id) unsend_form_client_{{$message->client_id}} @endif"
                              id="unsend_form_{{$message->id}}"
                              action="{{route('unsendChat')}}">
                            @endif
                            @endif

                            <div class="{{($message->from_id == $user->id)?'show':'send' }} @if($message->id) chat_msg_{{$message->id}} @endif @if($message->client_id) chat_msg_client_{{$message->client_id}} @endif"
                                 @if($message->from_id != $user->id) id="chat_msg_{{$message->id}}" @endif>
                                <div class="msg @if($message->from_id == $user->id) msg1 @endif" style="position: relative;">
                                    @if($message->from_id == $user->id)
                                        <img src="@if(file_exists( public_path().$user->meta->pic ) && $user->meta->pic != ""){{$user->meta->pic}} @elseif($user->engroup==2)/new/images/female.png @else/new/images/male.png @endif">
                                    @else
                                        @if($toUserIsBanned)
                                            <a type="button" style="color: #fd5678;" onclick="c5('{{'此人已被站方封鎖'}}'),setTimeout(function(){window.location.href = ' {{ !empty(session()->get	('goBackPage_chat2')) ? session()->get('goBackPage_chat2') : \Illuminate\Support\Facades\URL::previous() }} '},3000)">{{$to->name}}</a>
                                        @else
                                            @if($to->isVVIP() && $to->VvipInfoStatus())
                                                <a class="chatWith" href="{{ url('/dashboard/viewuser_vvip/' . $msgUser->id ) }}">
                                            @else
                                                <a class="chatWith" href="{{ url('/dashboard/viewuser/' . $msgUser->id ) }}">
                                            @endif
                                        @endif
                                            @php
                                                $isBlurAvatar = \App\Services\UserService::isBlurAvatar($to, $user);
                                                $pic = ($isBlurAvatar)?$msgUser->meta->pic_blur:$msgUser->meta->pic;
                                            @endphp
                                            <img class="@if($isBlurAvatar) blur_img @endif"
                                                 src="@if(file_exists( public_path().$pic ) && $pic != ""){{$pic}} @elseif($msgUser->engroup==2)/new/images/female.png @else/new/images/male.png  @endif">
                                            @if($to->isVVIP())
                                                <img src="/new/images/v1_08.png" class="liaot_tx_l">
                                            @endif
                                                </a>
                                    @endif
                                        <p class="@if($parentMsg??null) msg_has_parent @endif  userlogo_{{ $message->id }}  userlogo_{{ $message->client_id }} " wire:click=showSlide('{{ $message->id }}')>
                                        @if($parentMsg??null)
                                            <span class="parent_msg_box">
                                            @if(($parentMsg['from_id']??null) == $user->id)
                                                <img src="@if(file_exists( public_path().$user->meta->pic ) && $user->meta->pic != ""){{$user->meta->pic}} @elseif($user->engroup==2)/new/images/female.png @else/new/images/male.png @endif">
                                            @else
                                                @php
                                                    $parentPic = ($isBlurParentSender)?$parentMsgSender->meta->pic_blur:$parentMsgSender->meta->pic;
                                                @endphp
                                                <img class="@if($isBlurParentSender??null) blur_img @endif"
                                                     src="@if(file_exists( public_path().$parentPic ) && $parentPic != ""){{$parentPic}} @elseif($parentMsgSender->engroup==2)/new/images/female.png @else/new/images/male.png  @endif">
                                            @endif
                                            @if(!is_null(json_decode($parentMsg['pic'],true)))
                                                <img src="{{ json_decode($parentMsg['pic'],true)[0]['file_path'] }}" class="n_pic_lt">
                                            @endif
                                                {!! nl2br($parentMsg->content) !!}
                                            </span>
                                        @endif
                                    @if(!is_null(json_decode($message->pic,true)))
                                        <script>
                                            @php
                                                $created_at = $message->created_at;
                                            @endphp
                                            @if(\Carbon\Carbon::parse($created_at)->addSeconds($message->show_time_limit)->diffInSeconds(\Carbon\Carbon::now(),false)<=0)
                                            window.setTimeout(function() {
                                                destroy_msg('{{$message->id}}','{{$message->client_id}}');
                                            }, {{\Carbon\Carbon::parse($created_at)->addSeconds($message->show_time_limit)->diffInSeconds(\Carbon\Carbon::now())*1000}});
                                            @endif
                                        </script>
                                        <i class="msg_input"></i>
                                        <span id="page" class="marl5">
                                        <span class="justify-content-center">
                                            <span class="zoomInPhoto_{{ $message->client_id }} zoomInPhoto_official_{{ $message->id }}  gutters-10 pswp--loaded" data-pswp="" style="display: none;">
                                                <span style="width: 150px;">
                                                    @foreach(json_decode($message->pic,true) as $key => $pic)
                                                        @if(isset($pic['file_path']))
                                                            <a class="pswp--item" href="{{$pic['file_path'] }}" target="_blank"
                                                               data-pswp-index="{{ $key }}">
                                                                <img src="{{ $pic['file_path'] }}" class="n_pic_lt @if($key==0) n_pic_lt_{{ $message->client_id }} n_pic_lt_official_{{ $message->id }}  @endif">
                                                            </a>
                                                        @else
                                                            {{ logger("Message pic failed, user id: " . $user->id) }}
                                                            {{ logger("to id: " . $to->id) }}
                                                        @endif
                                                    @endforeach
                                                </span>
                                            </span>
                                            <span class="photoOrigin_{{ $message->client_id }} photoOrigin_official_{{ $message->id }}  gutters-10">
                                                <span style="width: 150px;">
                                                    @foreach(json_decode($message->pic,true) as $key => $pic)
                                                        @if(isset($pic['file_path']))
                                                            <a  class="pswp--item" ><img class="n_pic_lt" src="{{ $pic['file_path'] }}"></a>
                                                        @else
                                                            {{ logger("Message pic failed, user id: " . $user->id) }}
                                                            {{ logger("to id: " . $to->id) }}
                                                        @endif
                                                    @endforeach
                                                </span>
                                            </span>
                                        </span>
                                        <font class="sent_ri @if($message->from_id == $user->id)dr_l @if(!$isVip) novip @endif @else dr_r @endif">
                                            <span>{{ ($message->created_at??null)?substr($message->created_at,11,5):'&nbsp;' }}</span>
                                            @if(!$isVip && $message->from_id == $user->id)
                                                <span style="color:lightgrey;">已讀/未讀</span>
                                                <img src="/new/images/icon_35.png"
                                                     style="position: absolute;float: left;left: 10px; top:20px;-moz-transform:rotate(-25deg);-webkit-transform:rotate(-30deg);">
                                            @else
                                                <span>@if($message->read == "Y" && $message->from_id == $user->id) 已讀
                                                    @elseif($message->read == "N" && $message->from_id == $user->id) 未讀
                                                    @endif</span>
                                            @endif
                                        </font>
                                        </span>
                                        <font class="atkbut {{ $message->from_id == $user->id ? 'at_right':'at_left' }} showslide_{{ $message->id }}">
                                            @if($message->from_id != $user->id)
                                                <a href="javascript:void(0)" onclick="banned('{{$message->id}}','{{$msgUser->id}}','{{$msgUser->name}}');">
                                                    <span class="he_yuan"><img src="/new/images/ba_09.png" class="he_left_img"></span><i class="he_li30">檢舉</i>
                                                </a>
                                            @endif
                                            @if((!isset($admin) || $to->id != $admin->id) && !isset($to->banned )&&!isset($to->implicitlyBanned))
                                                <a href="javascript:void(0)" class="specific_reply_doer" onclick="specific_reply_doer(this);return false;" data-id="{{$message->id}}" data-client_id="{{$message->client_id}}">
                                                    <span class="he_yuan"><img src="/new/images/ba_03.png" class="he_left_img"></span><i class="he_li30">回覆</i>
                                                </a>
                                                @if($message->from_id == $user->id)
                                                    <a href="javascript:void(0)" onclick="chatUnsend(this);return false;" data-id="{{$message->id}}" data-client_id="{{$message->client_id}}">
                                                        <span class="he_yuan"><img src="/new/images/ba_05.png" class="he_left_img"></span><i class="he_li30">收回</i>
                                                        @if(!$isVip)
                                                            <img src="/new/images/icon_36.png" class="img_vip"
                                                            >@endif
                                                    </a>
                                                @endif
                                                <a href="javascript:void(0)" data-id="{{$message->id}}" data-client_id="{{$message->client_id}}"
                                                   @if($message->from_id != $user->id)
                                                   data-views_count="{{$message->views_count}}"  data-views_count_quota="{{$message->views_count_quota}}" data-is_received_msg="{{$message->from_id != $user->id}}"
                                                   @endif
                                                   onclick="zoomInPic(this);">
                                                    <span class="he_yuan"><img src="/new/images/ba_010.png" class="he_left_img"></span><i class="he_li30">放大</i>
                                                </a>
                                            @endif
                                        </font>
                                    @else
                                        <i class="msg_input"></i>
                                        <span class="msg_content">{!! nl2br($message->content) !!}</span>
                                        <font class="atkbut {{ $message->from_id == $user->id ? 'at_right':'at_left' }} showslide_{{ $message->id }}">
                                            @if($message->from_id != $user->id)
                                                <a href="javascript:void(0)" onclick="banned('{{$message->id}}','{{$msgUser->id}}','{{$msgUser->name}}');">
                                                    <span class="he_yuan"><img src="/new/images/ba_09.png" class="he_left_img"></span><i class="he_li30">檢舉</i>
                                                </a>
                                            @endif
                                            @if((!isset($admin) || $to->id != $admin->id) && !isset($to->banned )&&!isset($to->implicitlyBanned))
                                                <a href="javascript:void(0)" class="specific_reply_doer" onclick="specific_reply_doer(this);return false;"  data-id="{{$message->id}}" data-client_id="{{$message->client_id}}">
                                                    <span class="he_yuan"><img src="/new/images/ba_03.png" class="he_left_img"></span><i class="he_li30">回覆</i>
                                                </a>
                                                @if($message->from_id == $user->id)
                                                    <a href="javascript:void(0)" onclick="chatUnsend(this);return false;" data-id="{{$message->id}}" data-client_id="{{$message->client_id}}">
                                                        <span class="he_yuan"><img src="/new/images/ba_05.png" class="he_left_img"></span><i class="he_li30">收回</i>
                                                        @if(!$isVip)
                                                            <img src="/new/images/icon_36.png" class="img_vip">
                                                        @endif
                                                    </a>
                                                @endif
                                            @endif
                                        </font>
                                        <font class="sent_ri @if($message->from_id == $user->id)dr_l @if(!$isVip) novip @endif @else dr_r @endif">
                                            <span>{{ ($message->created_at??null)?substr($message->created_at,11,5):'&nbsp;' }}</span>
                                            @if(!$isVip && $message->from_id == $user->id)
                                                <span style="color:lightgrey;">已讀/未讀</span>
                                                <img src="/new/images/icon_35.png"
                                                     style="position: absolute;float: left;left: 10px; top:20px;-moz-transform:rotate(-25deg);-webkit-transform:rotate(-30deg);">
                                            @else
                                                <span>@if($message->read == "Y" && $message->from_id == $user->id) 已讀
                                                    @elseif($message->read == "N" && $message->from_id == $user->id) 未讀
                                                    @endif</span>
                                            @endif
                                        </font>
                                    @endif

                                        </p>
                                </div>
                            </div>
                            @if($isVip && $message->from_id == $user->id)
                                @if((!isset($admin) || $to->id != $admin->id) && !isset($to->banned )&&
                                !isset($to->implicitlyBanned))
                        </form>
                    @endif
                @endif
            @endif
            @php
                $date_temp = substr($message->created_at,0,10);
            @endphp
        @endforeach
    @endif
{{--    <div style="text-align: center; padding-bottom: 20px;">--}}
{{--        {!! $messages->appends(request()->input())->links('pagination::sg-pages2') !!}--}}
{{--    </div>--}}
</div>
<script>
    window.addEventListener('showSlide', event => {
        event.stopPropagation()
        $('.atkbut').hide();
        if($(this).hasClass('on1')) {
            $(this).removeClass('on1')
            $('.showslide_'+ event.detail.id).fadeOut()

        } else {

            $(this).addClass('on1')
            $('.fadeinboxs').fadeIn()
            $('.showslide_'+ event.detail.id).fadeIn()
        }
    })
</script>
@push('scripts')

@endpush