@extends('layouts.master')

@section('app-content')
<script>
    let d = new Date(), count = 0;
    function showMore(){
        count++;
        if(count === 3 && $('.user-list').length > 3){
            $('.showAll').show();
        }
        d.setDate(d.getDate() - 7);
        let date = d.getFullYear() + '-' + ( d.getMonth() + 1 ) + '-' + d.getDate();
        console.log(date);
        $.ajax({
            type: 'POST',
            url: '{{ route('showMoreMessages') }}',
            data: {
                _token:"{{ csrf_token() }}",
                date : date,
                uid : '{{ $user->id }}',
                isVip : '{{ $user->isVip() }}'
            },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(xhr){
                console.log(xhr.msg);
                if(xhr.msg !== ' No data'){
                    fillDatas(xhr.msg);
                }
            },
            error: function(xhr, type){
                alert('Ajax error!')
            }
        });
    }
    function showAll(){
        $("#showMore").hide();
        $.ajax({
            type: 'POST',
            url: '{{ route('showAllMessages') }}',
            data: {
                _token:"{{ csrf_token() }}",
                uid : '{{ $user->id }}',
                isVip : '{{ $user->isVip() }}'
            },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(xhr){
                console.log(xhr.msg);
                if(xhr.msg !== ' No data'){
                    fillDatas(xhr.msg);
                    $("#showAll").hide();
                    $("#tips").hide();
                }
            },
            error: function(xhr, type){
                alert('Ajax error!')
            }
        });
    }
    function fillDatas(data) {
        for(let i = 0 ; i < data.length ; i++){
            let ele;
            if(data[i]['isAdminMessage'] === 1){
                if(data[i]['user_name'].includes('站長')){
                    ele = "<div class='m-widget3__item' id='admin' style='background-color: rgba(164, 164, 164, 0.7); box-shadow: 0 1px 15px 1px rgba(164, 164, 164, 0.7); padding: 14px 28px;'>";
                }
                else{
                    ele = "<div class='m-widget3__item' id='normal' style='background-color: rgba(164, 164, 164, 0.7); box-shadow: 0 1px 15px 1px rgba(164, 164, 164, 0.7); padding: 14px 28px;'>";
                }
            }
            else{
                if(data[i]['user_name'].includes('站長')){
                    ele = "<div class='m-widget3__item' id='admin' style='background-color: rgba(244, 164, 164, 0.7); box-shadow: 0 1px 15px 1px rgba(244, 164, 164, 0.7); padding: 14px 28px;'>";
                }
                else{
                    ele = "<div class='m-widget3__item' id='normal' style='background-color: rgba(244, 164, 164, 0.7); box-shadow: 0 1px 15px 1px rgba(244, 164, 164, 0.7); padding: 14px 28px;'>";
                }
            }

            ele += "<div class='m-widget3__header' style='width:95%'>";
            ele += "<div class='m-widget3__user-img'>";
            if(data[i]['isAvatarHidden'] === 1){
                ele += "<a href='/dashboard/chat/" + data[i]['user_id'] + "'><img class='m-widget3__img' style='max-width:none' src='makesomeerror' onerror=\"this.src='{{ url('/') }}/img/male-avatar.png'\" alt=''></a></div>";
            }
            else{
                ele += "<a href='/dashboard/chat/" + data[i]['user_id'] + "'><img class='m-widget3__img' style='max-width:none' src='" + data[i]['pic'] + "' onerror=\"this.src='/img/male-avatar.png'\" alt=''></a></div>";
            }

            ele += "<div class='m-widget3__info'>";
            ele += "<a href='/dashboard/chat/" + data[i]['user_id'] + "'>";

            if(data[i]['user_name'].includes('站長')){
                ele += "<span class='m-widget3__username' style='color:blue;'>";
            }
            else{
                ele += "<span class='m-widget3__username'>";
            }
            ele += data[i]['user_name'];
            ele += "</span><br>";
            ele += "<span class='m-widget3__time'>";
            ele += "</span>";
            if(data[i]['cntr'] === 1){
                ele += "<br><span class='m-widget3__username' style='color:red'>(此人遭多人檢舉)</span>";
            }
            ele += "</a></div>";
            ele += "<div class='m-widget3__body' style='display:inline-block; word-wrap: break-word; word-break: break-all'>";
            if(data[i]['isAdminMessage'] === 1){
                ele += "<p class='message-text' style='word-wrap: break-word; word-break: break-all'>";
            }
            else{
                ele += "<p class='m-widget3__text' style='word-wrap: break-word; word-break: break-all'>";
            }
            ele += data[i]['content'];
            ele += "</p></div>";

            ele += "<div class='m-widget3__delete'>";
            ele += "<a class='btn btn-danger m-btn m-btn--air m-btn--custom delete-btn' href='{{ url('/') }}/dashboard/chat/deleterow/{{ $user->id }}/" + data[i]['user_id'] + "'>刪除</a>";
            ele += "</div></div></div>";
            $(ele).insertBefore($('.options'));
        }
        adminMessage();
    }

    function adminMessage() {
        $('#admin').each(
            function (){
                $(this).insertBefore($('#normal'));
            }
        );
    }
</script>
<?php
$block_people =  Config::get('social.block.block-people');
$admin_email = Config::get('social.admin.email');

if (isset($to)) $orderNumber = $to->id;
else $orderNumber = "";
$code = Config::get('social.payment.code');
?>
<div class="m-portlet__head">
    <div class="m-portlet__head-caption">
        <div class="m-portlet__head-title">
            <h3 style="text-align:left;" class="m-portlet__head-text">
                收件夾 @if(isset($to)) - {{$to->name}}@endif
            </h3>

            <span style="text-align:right;" class="m-portlet__head-text">
                @if(isset($to))
                   <a class="btn btn-danger m-btn m-btn--air m-btn--custom" href="/dashboard/chat"> 回去收件夾</a>
                @else
                   <a class="btn btn-danger m-btn m-btn--air m-btn--custom delete-btn" href="{!! route('deleteAll', ['uid' => $user->id]) !!}">刪除全部</a>
                @endif
            </span>

            <span style="text-align:right;" class="m-portlet__head-text">
                @if(isset($to))
                   @if(!\App\Models\Tip::isComment($user->id, $to->id) && $user->isVip() && \App\Models\Tip::isCommentNoEnd($user->id, $to->id))
                           @include('partials.tip-comment')
                   @else
                           @include('partials.tip-invite')
                   @endif
               @endif
            </span>
        </div>

    </div>


</div>

<div class="m-portlet__body">
    @if(str_contains(url()->current(), 'dashboard') && empty($to))
        <?php
            $collection = array();
        ?>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <div id="user-list" class="m-widget3 col-lg-12" style="display:inline-block">
        <?php if($user->isVip())
                $messages = \App\Models\Message::allSenders($user->id, 1);
            else $messages = \App\Models\Message::allSenders($user->id, 0);

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

                //($user->isVip() && !$msgUser->isVip() && ($user->meta_()->notifhistory == '顯示VIP會員信件' || $user->meta_()->notifhistory == NULL)) || (!$user->isVip() && $msgUser->isVip())
                // 收件通知
//                if(\App\Models\Message::onlyShowVip($user, $msgUser) || \App\Models\Message::showNoVip($user, $msgUser)) {
//                    continue;
//                }

                //echo 'message->to_id = '. $message->to_id . ' message->from_id = '. $message->from_id . ' user->id = ' . $user->id;
                $msgFromUser = \App\Models\User::findById($message['from_id']);
                $msgToUser = \App\Models\User::findById($message['to_id']);
                $latestMessage = \App\Models\Message::latestMessage($user->id, $msgUser->id);
                $lastSender = \App\Models\Message::getLastSender($user->id, $msgUser->id);
                // echo '<br/>';
                // echo json_encode($latestMessage);
            ?>
                    @if(!empty($latestMessage))
                        @if(\App\Models\Message::isAdminMessage($latestMessage->content))
                        <div class="m-widget3__item" @if(str_contains($msgUser->name, '站長')) id='admin' @else id='normal' @endif style="background-color: rgba(164, 164, 164, 0.7); box-shadow: 0 1px 15px 1px rgba(164, 164, 164, 0.7); padding: 14px 28px;">

                        @else
                        <div class="m-widget3__item" @if(str_contains($msgUser->name, '站長')) id='admin' @else id='normal' @endif style="background-color: rgba(244, 164, 164, 0.7); box-shadow: 0 1px 15px 1px rgba(244, 164, 164, 0.7); padding: 14px 28px;">

                        @endif
                            <div class="m-widget3__header" @if(isset($to))style="width:95%"@else style="width:95%" @endif>
                                <div class="m-widget3__user-img">
                                    <a href="/dashboard/chat/{{$msgUser->id}}"><img class="m-widget3__img" style="max-width:none" src="@if($msgUser->meta_()->isAvatarHidden) {{ 'makesomeerror' }} @else {{$msgUser->meta_()->pic}} @endif" onerror="this.src='/img/male-avatar.png'" alt=""></a>
                                </div>

                                <div class="m-widget3__info">
                                <a href="/dashboard/chat/{{$msgUser->id}}">
                                    <span class="m-widget3__username" @if(str_contains($msgUser->name, '站長')) style='color:blue;' @endif>
                                    {{$msgUser->name}}
                                    </span><br>
                                    <span class="m-widget3__time">
                                    </span>
                                    @if(\App\Models\Reported::cntr($msgUser->id) >= $block_people ) <br><span class="m-widget3__username" style="color:red">(此人遭多人檢舉)</span>
                                    @endif
                                    </a>
                                </div>
                                <div class="m-widget3__body" style="display:inline-block; word-wrap: break-word; word-break: break-all">
                                    @if(\App\Models\Message::isAdminMessage($latestMessage->content))
                                        <p class="message-text" style="word-wrap: break-word; word-break: break-all">
                                            {{$latestMessage->content}}
                                        </p>
                                    @else
                                        <p class="m-widget3__text" style="word-wrap: break-word; word-break: break-all">
                                            {{$latestMessage->content}}
                                        </p>
                                    @endif
                                </div>

                                <div class="m-widget3__delete">
                                    <a class="btn btn-danger m-btn m-btn--air m-btn--custom delete-btn" href="{!! route('deleteBetween', ['uid' => $user->id, 'sid' => $msgUser->id]) !!}">刪除</a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
            <div class="options">
                <a class="btn btn-danger m-btn m-btn--air m-btn--custom text-white" id="showMore" onclick="showMore();" data-token="{{ csrf_token() }}">顯示更多</a>
                <a class="btn btn-danger m-btn m-btn--air m-btn--custom text-white" id="showAll" onclick="showAll();" data-token="{{ csrf_token() }}">顯示全部</a>
            </div>
        </div>

        @elseif(isset($to))
        <?php $icc = 1 ?>
        <div class="m-widget3 col-lg-12" style="display:inline-block; vertical-align:top;">

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
                @if ($user->isVip() && $user->city !== $msgUser->city)

                @else
                @if(\App\Models\Message::isAdminMessage($message->content))
                <div class="m-widget3__item" <?php echo 'style="border-bottom: none !important; background-color: rgba(164, 164, 164, 0.7); box-shadow: 0 1px 15px 1px rgba(164, 164, 164, 0.7); padding: 16px 32px; 0px 32px"'; $icc = 0?>>
                    <div class="m-widget3__header" @if(isset($to))style="width:95%"@endif>
                        <div class="m-widget3__user-img" style="display:inline-block; margin-right: 6%; vertical-align: middle;">
                            <a href="#"><img class="m-widget3__img" style="max-width:4rem" src="<?php echo \App\Models\User::findByEmail($admin_email)->pic; ?>" onerror="this.src='/img/male-avatar.png'" alt=""></a>
                        </div>
                @else
                <div class="m-widget3__item" @if ($icc == 1) <?php echo 'style="border-bottom: none !important; background-color: rgba(244, 164, 164, 0.7); box-shadow: 0 1px 15px 1px rgba(244, 164, 164, 0.7); padding: 16px 32px; 0px 32px"'; $icc = 0?>@else <?php $icc = 1; echo'style="border-bottom: none !important; padding: 14px 28px 0px 28px;"'; ?> @endif>
                    <div class="m-widget3__header" @if(isset($to))style="width:95%"@endif>
                        <div class="m-widget3__user-img" style="display:inline-block; margin-right: 6%; vertical-align: middle;">
                            <a href="/user/view/{{$msgUser->id}}"><img class="m-widget3__img" style="max-width:4rem" src="@if($msgUser->meta_()->isAvatarHidden) {{ 'makesomeerror' }} @else {{$msgUser->meta_()->pic}} @endif" onerror="this.src='/img/male-avatar.png'" alt=""></a>
                        </div>
                @endif
                        <div class="m-widget3__body" style="display:inline-block; word-wrap: break-word; word-break: break-all">
                            @if(\App\Models\Message::isAdminMessage($message->content))
                                <p class="message-text" style="word-wrap: break-word; word-break: break-all">
                                    {{$message->content}}
                                    <br>
                                <span class="m-widget3__time" style="font-size: 0.7rem">
                                    {{ $message->created_at }}
                                </span>
                                </p>
                            @else
                                <p class="m-widget3__text" style="word-wrap: break-word; word-break: break-all">
                                    {{$message->content}}
                                    <br>
                                <span class="m-widget3__time" style="font-size: 0.7rem">
                                    {{ $message->created_at }}
                                </span>
                                </p>
                            @endif
                        </div>

                        <div class="m-widget3__body">
                            <p class="m-widget3__text">
                                @if(!\App\Models\Message::isAdminMessage($message->content))
                                    @if($user->id == $msgUser->id && $message->read == "Y" && $user->isVip())
                                        已讀
                                    @elseif($user->id == $msgUser->id && $message->read == "N" && $user->isVip())
                                        未讀
                                    @elseif(!$user->isVip())
                                        已讀/未讀 (?)
                                    @endif
                                @endif
                            </p>
                        </div>

                        <div class="m-widget3__delete">
                            <a class='delete-btn' href="{!! route('deleteSingle', ['uid' => $user->id, 'sid' => $to->id, 'ct_time' => $message->created_at, 'content' => $message->content]) !!}">刪除</a>
                            <a class='report-btn' href="{!! route('reportMessagePage', ['id' => $message->id, 'sid' => $to->id]) !!}">檢舉</a>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach


            <div class="m-form__actions">
                {!! $messages->appends(request()->input())->links() !!}
            </div>

            <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="/dashboard/chat" id="chatForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                <input type="hidden" name="userId" value="{{$user->id}}">
                <input type="hidden" name="to" value="{{$to->id}}">
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <div class="col-lg-9">
                            <textarea class="form-control m-input msg" rows="4" id="msg" required name="msg" maxlength="500"></textarea>
                        </div>
                    </div>

                    <div class="m-form__actions">
                        <div class="row">
                            <div class="col-lg-9">
                                <button id="msgsnd" class="btn btn-danger m-btn m-btn--air m-btn--custom msgsnd">回覆</button>&nbsp;&nbsp;
                                <button type="reset" class="btn btn-outline-danger m-btn m-btn--air m-btn--custom">取消</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif



@stop

@section('javascript')
<script>
$(document).ready(function(){
    $('.msg').keyup(function() {
        let content = $('.msg').val(), msgsnd = $('.msgsnd');
        if($.trim(content) == "" ){
            $('.alert').remove();
            $("<a style='color: red; font-weight: bold;' class='alert'>請勿僅輸入空白！</a>").insertAfter(this);
            msgsnd.prop('disabled', true);
        }
        else {
            $('.alert').remove();
            msgsnd.prop('disabled', false);
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
    if($('.user-list').length <= 3){
        $('<p style="color:red;" id="tips">如果發現訊息不完整，請按下全部顯示</p>').insertAfter($('.options'));
    }
    else{
        $('.showAll').hide();
    }
});
$('#chatForm').submit(function () {
    let content = $('.msg').val(), msgsnd = $('.msgsnd');
    if($.trim(content) == "" ){
        $('.alert').remove();
        $("<a style='color: red; font-weight: bold;' class='alert'>請勿僅輸入空白！</a>").insertAfter($('.msg'));
        msgsnd.prop('disabled', true);
        return false;
    }
    else {
        $('.alert').remove();
        return true;
    }
});
</script>

@stop
