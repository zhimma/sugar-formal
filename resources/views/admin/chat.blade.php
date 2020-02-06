@extends('admin.main')
@section('app-content')

<?php
$block_people =  Config::get('social.block.block-people');
$admin_email = Config::get('social.admin.email');
?>
<body style="padding: 15px;">
<h3 style="text-align:left;">
    站長信箱 @if(isset($to)) - {{$to->name}}@endif @if(isset($to))
        <a class="btn btn-info" href="/admin/chat"> 回去信箱</a>
    @endif
</h3>

<div>
    @if(str_contains(url()->current(), 'admin') && empty($to))
        <?php
            $collection = array();
        ?>
    <div id="user-list" style="display:inline-block; width: 100%;">
        <?php
            $messages = \App\Models\Message::allSendersAdmin($user->id, 1);
            // echo json_encode($messages);
        ?>
        <?php /*$msgUserRead =  \App\Models\Message::getSendersRead($msgUser->id, $user->id);*/
            $userBlockList = \App\Models\Blocked::select('blocked_id')->where('member_id', $user->id)->get()->toArray();
            function search($value, $array) {
                foreach ($array as $key => $val) {
                    if ($val['blocked_id'] === $value) {
                        return true;
                    }
                }
                return false;
            }
        ?>
        @if(!empty($messages))
            @foreach ($messages as $message)
                @if(\App\Models\User::isBanned($message['from_id']) || \App\Models\User::isBanned($message['to_id']))
                    @continue
                @endif
                @if(search($message['from_id'], $userBlockList) || search($message['to_id'], $userBlockList))
                    @continue
                @endif
            <?php

                if($message['to_id'] == $user->id) {
                    $msgUser = \App\Models\User::findById($message['from_id']);
                }
                else if($message['from_id'] == $user->id) {
                    $msgUser =  \App\Models\User::findById($message['to_id']);
                }

                //echo 'message->to_id = '. $message->to_id . ' message->from_id = '. $message->from_id . ' user->id = ' . $user->id;
                $latestMessage = \App\Models\Message::latestMessage($user->id, $msgUser->id);
                // echo '<br/>';
                // echo json_encode($latestMessage);
            ?>
                    @if(!empty($latestMessage))
                        @if(\App\Models\Message::isAdminMessage($latestMessage->content))
                            <div class="box1" @if(str_contains($msgUser->name, '站長')) id='admin' @else id='normal' @endif style="background-color: white; border: 1px solid; margin: 4px;">
                        @else
                            <div class="box1" @if(str_contains($msgUser->name, '站長')) id='admin' @else id='normal' @endif style="background-color: white; border: 1px solid; margin: 4px;">
                        @endif
                            <div  style="padding:5px">
                                <a href="/admin/chat/{{$msgUser->id}}"><img style="max-width:75px;" src="{{$msgUser->meta_()->pic}}" onerror="this.src='/img/male-avatar.png'" alt=""></a>
                                <a href="/admin/chat/{{$msgUser->id}}" class="btn btn-success">
                                    <span class="" @if(str_contains($msgUser->name, '站長')) style='color:blue;' @endif>
                                        {{$msgUser->name}}
                                    </span>
                                    @if(\App\Models\Reported::cntr($msgUser->id) >= $block_people )
                                        <br><span style="color:red">(此人遭多人檢舉)</span>
                                    @endif
                                </a>
                                最新訊息：{{$latestMessage->content}}
                                <!-- <p style="word-wrap: break-word; word-break: break-all">
                                    <button id="msgsnd" type="submit" class="btn btn-info">回覆</button>
                                    <textarea class="form-control" style="display:inline;width: 90%;" rows="1" id="msg" required="" name="msg" maxlength="500"></textarea>
                                </p> -->
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>

        @elseif(isset($to))
        <?php $icc = 1 ?>
        <div class="" style="display:inline-block; vertical-align:top; width: 100%;">

            <?php $messages = \App\Models\Message::allToFromSender($user->id, $to->id) ?>
            <?php /*echo json_encode($messages)*/ ?>

            @foreach ($messages as $message)
                <?php
                    $msgUser = \App\Models\User::findById($message->from_id);
                    \App\Models\Message::read($message, $user->id);
                    //echo $msgUser;

                    if($message->is_single_delete_1 == $user->id || $message->is_single_delete_2 == $user->id) {
                        continue;
                    }
                ?>
                @if(\App\Models\Message::isAdminMessage($message->content))
                <div <?php echo 'style="border-bottom: none !important; background-color: rgba(164, 164, 164, 0.7); box-shadow: 0 1px 15px 1px rgba(164, 164, 164, 0.7); padding: 16px 32px; 0px 32px"'; $icc = 0?>>
                    <div class="m-widget3__header" @if(isset($to))style="width:95%"@endif>
                        <div class="m-widget3__user-img" style="display:inline-block; margin-right: 6%; vertical-align: middle;">
                            <a href="#"><img class="m-widget3__img" style="max-width:4rem" src="<?php echo \App\Models\User::findByEmail($admin_email)->pic; ?>" onerror="this.src='/img/male-avatar.png'" alt=""></a>
                        </div>
                @else
                <div @if ($icc == 1) <?php echo 'style="border-bottom: none !important; background-color: rgba(0, 196, 255, 0.4); box-shadow: 0 1px 15px 1px rgba(0, 196, 255, 0.4); padding: 16px 32px; 0px 32px"'; $icc = 0?>@else <?php $icc = 1; echo'style="border-bottom: none !important; padding: 14px 28px 0px 28px;"'; ?> @endif>
                    <div @if(isset($to))style="width:95%"@endif>
                        <div style="display:inline-block; margin-right: 6%; vertical-align: middle;">
                            <a href="/user/view/{{$msgUser->id}}"><img class="m-widget3__img" style="max-width:4rem" src="{{$msgUser->meta_()->pic}}" onerror="this.src='/img/male-avatar.png'" alt=""></a>
                        </div>
                @endif
                        <div style="display:inline-block; word-wrap: break-word; word-break: break-all">
                            @if(\App\Models\Message::isAdminMessage($message->content))
                                <p class="message-text" style="word-wrap: break-word; word-break: break-all">
                                    {{$message->content}}
                                    <br>
                                <span class="m-widget3__time">
                                    {{ $message->created_at }}
                                </span>
                                </p>
                            @else
                                <p style="word-wrap: break-word; word-break: break-all">
                                    {{$message->content}}
                                    <br>
                                <span class="m-widget3__time">
                                    {{ $message->created_at }}
                                </span>
                                </p>
                            @endif
                        </div>

                        <div>
                            <p>
                                @if(!\App\Models\Message::isAdminMessage($message->content))
                                    @if($user->id == $msgUser->id && $message->read == "Y" )
                                        已讀
                                    @elseif($user->id == $msgUser->id && $message->read == "N" )
                                        未讀
                                    @endif
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="m-form__actions">
                {!! $messages->appends(request()->input())->links() !!}
            </div>

            <form class="form-control" method="POST" action="/admin/chat">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                <input type="hidden" name="userId" value="{{$user->id}}">
                <input type="hidden" name="to" value="{{$to->id}}">
                <textarea class="form-control" rows="4" id="msg" required name="msg" maxlength="500"></textarea>
                <button id="msgsnd" type="submit" class="btn btn-info">回覆</button>&nbsp;&nbsp;
                <button type="reset" class="btn btn-outline-info">取消</button>
            </form>
        </div>
    </div>
@endif

</body>

@stop

@section('javascript')

<script>
$(document).ready(function(){
    $('#msg').on('keyup' , function() {
        if($('#msg').val().length > 0 ){
            $('#msgsnd').prop('disabled', false);
        }
        else {
            $('#msgsnd').prop('disabled', true);
        }
    });
    $("#showhide").click(function(){
        if ($("user-list").isHidden()) {
            $("user-list").show();
        }
        else {
            $("user-list").hide();
        }
    });
    setTimeout(function() {
        window.location.reload();
    }, 300000);
    $('#admin').each(
        function (){
            $(this).insertBefore($('#normal'));
        }
    );
    $('.delete-btn').on('click',function(e){
        if(!confirm('確定要刪除?')){
            e.preventDefault();
        }
    });
    $('.report-btn').on('click',function(e){
        if(!confirm('確定要檢舉?')){
            e.preventDefault();
        }
    });
});
</script>
@stop
