<div wire:poll>
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
    @endphp
    @foreach($forumManageChatContent as $row)
        <div class="@if($row->from_id == auth()->user()->id || ($row->from_is_forum_manager==$cur_user_is_forum_manager && $cur_forum_owner_id!=$row->from_id ) ||  ($cur_forum_owner_id==$row->from_id && $row->from_is_forum_manager!=$cur_user_is_forum_manager)) show @else send @endif maspp0">
            @if($date_temp != substr($row->created_at,0,10))
                @php
                    $date = \Carbon\Carbon::parse($row->created_at);
                @endphp
                <div class="tao_time">{{substr($row->created_at,0,10)}} ({{$weekMap[$date->dayOfWeek]}})</div>
            @endif
            <div class="msg @if($row->from_id == auth()->user()->id || ($row->from_is_forum_manager==$cur_user_is_forum_manager && $cur_forum_owner_id!=$row->from_id ) ||  ($cur_forum_owner_id==$row->from_id && $row->from_is_forum_manager!=$cur_user_is_forum_manager) || ($cur_forum_owner_id==$row->from_id && $row->from_is_forum_manager!=$cur_user_is_forum_manager)) msg1 @endif">
                @if($row->from_id == auth()->user()->id  || ($row->from_is_forum_manager==$cur_user_is_forum_manager && $cur_forum_owner_id!=$row->from_id ) ||  ($cur_forum_owner_id==$row->from_id && $row->from_is_forum_manager!=$cur_user_is_forum_manager) || ($cur_forum_owner_id==$row->from_id && $row->from_is_forum_manager!=$cur_user_is_forum_manager))
                <img class="chatShowAvatarRight" src="@if(file_exists( public_path().$row->upic ) && $row->upic != ""){{$row->upic}} @elseif($row->engroup==2)/new/images/female.png @else/new/images/male.png @endif">
                @else
                    <a class="chatWith" href="{{ url('/dashboard/viewuser/' . $row->from_id ) }}">
                        <img class="chatShowAvatarLeft" src="@if(file_exists( public_path().$row->upic ) && $row->upic != ""){{$row->upic}} @elseif($row->engroup==2)/new/images/female.png @else/new/images/male.png @endif">
                    </a>
                @endif

                    <div class="@if($row->from_id == auth()->user()->id || ($row->from_is_forum_manager==$cur_user_is_forum_manager && $cur_forum_owner_id!=$row->from_id ) ||  ($cur_forum_owner_id==$row->from_id && $row->from_is_forum_manager!=$cur_user_is_forum_manager)) msg_p1 @else msg_p @endif">
                    @if($row->from_user)
                    <span class="nickname  {{$row->from_id == auth()->user()->id || ($row->from_is_forum_manager==$cur_user_is_forum_manager && $cur_forum_owner_id!=$row->from_id ) ||  ($cur_forum_owner_id==$row->from_id && $row->from_is_forum_manager!=$cur_user_is_forum_manager)?'right':'left'}} ">{{$row->from_user->name}}{{($row->from_is_forum_manager )?'(管理員)':null}} </span>
                    @endif
                    <i class="@if($row->from_id == auth()->user()->id || ($row->from_is_forum_manager==$cur_user_is_forum_manager && $cur_forum_owner_id!=$row->from_id)  || ($cur_forum_owner_id==$row->from_id && $row->from_is_forum_manager!=$cur_user_is_forum_manager) ) msg_input_nn_2 @else msg_input_nn @endif"></i>
                    {{$row->content}}
                    @if(!is_null(json_decode($row->pic,true)))
                        @if(!empty($row->content))
                        <br>
                        @endif
                    <div class="msgPics">
                        <div class="row">
                            @foreach(json_decode($row->pic,true) as $key => $pic)
                                @if(isset($pic['file_path']))
                                <div class="col-xs-6 col-sm-6 col-md-4 col-lg-3" @if($row->from_id == auth()->user()->id || ($row->from_is_forum_manager==$cur_user_is_forum_manager && $cur_forum_owner_id!=$row->from_id ) ||  ($cur_forum_owner_id==$row->from_id && $row->from_is_forum_manager!=$cur_user_is_forum_manager)) style="float: right;" @endif>
                                    <a href="{{$pic['file_path'] }}" data-fancybox="gallery" target="_blank">
                                        <img src="{{ $pic['file_path'] }}" style="height: 50px; width: 50px; object-fit: cover;">
                                    </a>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @php
            $date_temp = substr($row->created_at,0,10);
        @endphp
    @endforeach

    {{ $forumManageChatContent->links('livewire::sg-pages2') }}
</div>
