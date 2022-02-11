<div wire:poll>
    {{-- Be like water. --}}
    @php
        $date_temp='';
        $weekMap = [
            0 => '日',
            1 => '一',
            2 => '二',
            3 => '三',
            4 => '四',
            5 => '五',
            6 => '六',
        ];
        $now = \Carbon\Carbon::parse(now());
        //echo $now->dayOfWeek;
        if($now->dayOfWeek==0){
            echo '<div class="red">今日晚上23:59 即將重置聊天室</div>';
        }
    @endphp
    @foreach($anonymousChat as $row)
        <div class="@if($row->user_id == auth()->user()->id) show @else send @endif">
            @if($date_temp != substr($row->created_at,0,10))
                @php
                    $date = \Carbon\Carbon::parse($row->created_at);
                @endphp
                <div class="tao_time">{{substr($row->created_at,0,10)}} ({{$weekMap[$date->dayOfWeek]}})</div>
            @endif
            <div class="msg @if($row->user_id == auth()->user()->id) msg1 @endif">

                <img @if($row->engroup==1) src="/new/images/touxiang_wm.png" @else src="/new/images/touxiang_w.png" @endif @if($row->user_id != auth()->user()->id) style="cursor: pointer;" onclick="chat_message({{$row->id}}, {{$row->engroup}}, {{$row->anonymous}})"@endif>
                <p>
                    <span class="nickname @if($row->user_id != auth()->user()->id) left @endif" @if($row->user_id == auth()->user()->id) style="float: right; left: unset; right: 10px;" @endif>{{$row->anonymous}}</span>
                    <i class="msg_input"></i>{{$row->content}}

                    @if(!is_null(json_decode($row->pic,true)))
                        @if(!empty($row->content))
                            <br>
                        @endif
                        <span id="page" @if($row->user_id != auth()->user()->id) class="marl5" @endif>
                            <span class="justify-content-center">
                                <span class="gutters-10" data-pswp>
                                    @foreach(json_decode($row->pic,true) as $key => $pic)
                                        @if(isset($pic['file_path']))
                                            <span style="width: 150px;">
                                                <a href="{{$pic['file_path'] }}" data-fancybox="gallery" target="_blank">
                                                    <img src="{{ $pic['file_path'] }}" style="object-fit: cover;" class="n_pic_lt">
                                                </a>
                                            </span>
                                        @endif
                                    @endforeach
                                </span>
                            </span>
                        </span>
                    @endif

                    @if($row->user_id != auth()->user()->id)
                    <a onclick="show_banned({{$row->id}}, {{$row->anonymous}})" style="cursor: pointer;" title="檢舉"><img src="/new/images/ban.png" class="shdel"></a>
                    @endif
                    <font class="sent_ri @if($row->user_id == auth()->user()->id) dr_l @else dr_r @endif">
                        <span>{{substr($row->created_at,11,5)}}</span>
                    </font>
                </p>
            </div>
        </div>

{{--        <div class="show">--}}
{{--            <div class="msg msg1">--}}
{{--                <img src="/new/images/touxiang_w.png">--}}
{{--                <p>--}}
{{--                    <i class="msg_input"></i>嗨，要能在茫茫--}}
{{--                    <a href=""><img src="/new/images/ban.png" class="shde2"></a>--}}
{{--                    <font class="sent_ri dr_l"><span>20:00</span>--}}
{{--                        --}}{{--                                    <span>已讀/未讀</span><img src="/new/images/icon_35.png">--}}
{{--                    </font>--}}
{{--                </p>--}}
{{--            </div>--}}
{{--        </div>--}}
        @php
            $date_temp = substr($row->created_at,0,10);
        @endphp
    @endforeach


    {{ $anonymousChat->links('livewire::sg-pages2') }}
</div>

