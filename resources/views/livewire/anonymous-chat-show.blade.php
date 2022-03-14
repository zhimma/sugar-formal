<div wire:poll="checkReport">
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
        <div class="@if($row->user_id == auth()->user()->id) show @else send @endif" id="row_{{$row->id}}">
            @if($date_temp != substr($row->created_at,0,10))
                @php
                    $date = \Carbon\Carbon::parse($row->created_at);
                @endphp
                <div class="tao_time">{{substr($row->created_at,0,10)}} ({{$weekMap[$date->dayOfWeek]}})</div>
            @endif
            <div class="msg @if($row->user_id == auth()->user()->id) msg1 @endif" >

                <img
                    @if($row->anonymous=='站長')
                        src="/new/images/admin-avatar.jpg"
                    @else
                        @if($row->engroup==1) src="/new/images/touxiang_wm.png"
                        @else src="/new/images/touxiang_w.png"
                        @endif
                    @endif
                    @if($row->user_id != auth()->user()->id)
                    style="cursor: pointer;" wire:click="chat_message('{{$row->anonymous}}', {{$row->id}}, {{$row->engroup}})"
                    @endif>
                    @php
                        $reply_data = \App\Models\AnonymousChat::where('id', $row->reply_id)->first();
                    @endphp
                <p @if(isset($row->reply_id)) class="msg_has_parent" @endif @if($row->anonymous=='站長' && $row->user_id != auth()->user()->id)style="background: #ddf3ff;"@endif>
                    <span class="nickname @if($row->user_id != auth()->user()->id) left @endif" @if($row->user_id == auth()->user()->id) style="float: right; left: unset; right: 10px;" @endif>{{$row->anonymous}}</span>
                    @if(isset($row->reply_id))

                        <a class="parent_msg_box" href="#row_{{$row->reply_id}}">
                            @if(!isset($reply_data))
                                此訊息已刪除
                            @elseif(isset($reply_data))

                                @if(isset($reply_data->content))
                                {!! nl2br($reply_data->content) !!}<br>
                                @endif
                                @if(!is_null(json_decode($reply_data['pic'],true)))
                                    <img src="{{ json_decode($reply_data['pic'],true)[0]['file_path'] }}"
                                         class="n_pic_lt">
                                @endif
                            @endif
                        </a>
                    @endif
                    <i class="msg_input" @if($row->anonymous=='站長' && $row->user_id != auth()->user()->id) style="background: url('/new/images/msg-input-blue.png') no-repeat;"@endif></i>{{$row->content}}

                    @if(!is_null(json_decode($row->pic,true)))
                        @if(!empty($row->content))
                            <br>
                        @endif
                        @foreach(json_decode($row->pic,true) as $key => $pic)
                            @if(isset($pic['file_path']))
                                <span style="width: 150px;" class="row_pic">
                                    <a href="{{$pic['file_path'] }}" data-fancybox="gallery_{{$row->id}}" target="_blank">
                                        <img src="{{ $pic['file_path'] }}" style="object-fit: cover; height: 150px;" class="n_pic_lt">
                                    </a>
                                </span>
                            @endif
                        @endforeach
                    @endif

                    <a href="javascript:void(0)" class="specific_reply_doer" wire:click="reply_message('{{$row->content}}', {{$row->id}}, '{{$row->pic}}')"
                       title="回覆" >
                        <span class="shdel specific_reply" @if($row->user_id != auth()->user()->id && $row->anonymous != '站長') style="right:15px; @endif"><span>回覆</span></span>
                    </a>
                    @if($row->user_id != auth()->user()->id && $row->anonymous != '站長')
                    <a href="javascript:void(0);" wire:click="show_banned('{{$row->anonymous}}', {{$row->id}})" class="show_banned" data-id="{{$row->id}}" data-name="{{$row->anonymous}}" style="cursor: pointer;" title="檢舉">
{{--                        <img src="/new/images/ban.png" class="shdel">--}}
                        <span class="shdel"
                              style="border: #fd5678 1px solid; width: auto;"><span>檢舉</span></span>
                    </a>
                    @endif
                    <font class="sent_ri @if($row->user_id == auth()->user()->id) dr_l @else dr_r @endif">
                        <span>{{substr($row->created_at,11,5)}}</span>
                    </font>
                </p>
            </div>
        </div>
        @php
            $date_temp = substr($row->created_at,0,10);
        @endphp
    @endforeach


{{--    {{ $anonymousChat->links('livewire::sg-pages') }}--}}

</div>
<script>


    window.addEventListener('reply_message', event => {

        $('.specific_msg_box').show();


        $('#reply_id').val(event.detail.id);

        let pic_html='';
        if(event.detail.pic.length > 0){
            let obj = JSON.parse(event.detail.pic);

            // alert(obj[0].file_path);
            //
            pic_html = '<img src="'+obj[0].file_path+'">';

        }

        $('.specific_msg').html(event.detail.content + pic_html);
        $('.xin_input').focus();
    })

    window.addEventListener('show_banned', event => {
            $('#anonymous_chat_id').val(event.detail.id);
            $('#anonymous_chat_name').html('檢舉' + event.detail.name);
            $(".announce_bg").show();
            $("#show_banned_ele").show();
        // });
    })

    window.addEventListener('chat_message', event => {
            if(event.detail.self_engroup == event.detail.engroup){
                c5('您好，本站僅限與異性互動！');
                return false;
            }

            if(event.detail.canNotMessage){
                c5('您好，一週僅限發一則私訊！');
                return false;
            }

            $('#anonymous_chat_message_id').val(event.detail.id);
            $('#anonymous_chat_message_name').html('發訊息給 ' + event.detail.name);
            $(".announce_bg").show();
            $("#show_chat_message").show();
    })
</script>
@push('scripts')

@endpush


