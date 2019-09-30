@extends('layouts.master')

@section('app-content')
<script>
    let d = new Date(), count = 0;
    function showMore(){
        $('#showMore').fadeOut(50);
        $('#warning').fadeIn(100);
        $('#showAll').fadeOut(50);
        let wait = document.getElementById("warning");
        let text = wait.innerHTML;
        let length = wait.innerHTML.length + 10;
        let dots = window.setInterval( function() {
            let wait = document.getElementById("warning");
            if ( wait.innerHTML.length > length )
                wait.innerText = text;
            else
                wait.innerText += ".";
        }, 100);
        // count++;
        // if(count === 3 && $('.user-list').length > 3){
        //     $('.showAll').show();
        // }
        //console.log("Query time:" + d);
        let date = d.getFullYear() + '-' + ( d.getMonth() + 1 ) + '-' + d.getDate();
        //console.log(date);
        var isVip = '{{ $isVip }}';
        $.ajax({
            type: 'POST',
            url: '{{ route('showMoreMessages') }}/{{ \Carbon\Carbon::now()->timestamp }}',
            data: {
                _token:"{{ csrf_token() }}",
                date : date,
                uid : '{{ $user->id }}',
                isVip : '{{ $isVip }}',
                deviceInfo: navigator,
                noVipCount : $('.m-widget3__header').length
            },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(xhr){
                console.log(xhr.msg);
                $('#showMore').fadeIn(100);
                $('#warning').fadeOut(100);
                clearInterval(dots);
                $('#showAll').fadeIn(100);
                if(xhr.msg[0] !== 'No data'){
                    let tmp = xhr.msg[xhr.msg.length - 1];
                    d = new Date(tmp);
                    fillDatas(xhr.msg);
                    if(!isVip&&xhr.noVipCount&&$('.m-widget3__header').length>=xhr.noVipCount){
                        $("#showMore").hide();
                        $('#warning').remove();
                        clearInterval(dots);
                        $("#tips").hide();
                        $("#showAll").hide();
                    }
                }
                else{
                    d = new Date(xhr.msg[1]);
                    $("#showMore").hide();
                    $('#warning').remove();
                    clearInterval(dots);
                    $("#tips").hide();
                    $("#showAll").hide();
                }
            },
            error: function(xhr, type){
                alert('訊息讀取出現錯誤！敬請重新整理後再嘗試一次，如本錯誤持續出現，請與站長聯絡，謝謝。');
            }
        });
    }
    function showAll(){
        $('#showMore').fadeOut(50);
        $('#warning').fadeIn(100);
        $('#showAll').fadeOut(50);
        let wait = document.getElementById("warning");
        let text = wait.innerHTML;
        let length = wait.innerHTML.length + 10;
        let dots = window.setInterval( function() {
            let wait = document.getElementById("warning");
            if ( wait.innerHTML.length > length )
                wait.innerText = text;
            else
                wait.innerText += ".";
        }, 100);
        $.ajax({
            type: 'POST',
            url: '{{ route('showAllMessages') }}/{{ \Carbon\Carbon::now()->timestamp }}',
            data: {
                _token:"{{ csrf_token() }}",
                uid : '{{ $user->id }}',
                isVip : '{{ $isVip }}'
            },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(xhr){
                console.log(xhr.msg);
                if(xhr.msg[0] !== ' No data'){
                    fillDatas(xhr.msg, true);
                    $("#showAll").hide();
                    $('#warning').remove();
                    clearInterval(dots);
                    $("#tips").hide();
                }
            },
            error: function(xhr, type){
                alert('訊息讀取出現錯誤！敬請重新整理後再嘗試一次，如本錯誤持續出現，請與站長聯絡，謝謝。');
            }
        });
    }
    function fillDatas(data, isAll = false) {
        data.splice(-1,1);
        let options = $(".options");
        if(isAll){
            $('#user-list').empty();
        }
        var tt=0;
        for(let i = 0 ; i < data.length ; i++){
            let ele;
            if(data[i]['isAdminMessage'] === 1){
                if(data[i]['user_name'].includes('站長')){
                    ele = "<div class='m-widget3__item' id='admin' style='background-color: rgba(164, 164, 164, 0.7); box-shadow: 0 1px 15px 1px rgba(164, 164, 164, 0.7); padding: 14px 28px;position: relative;'>";
                }
                else{
                    ele = "<div class='m-widget3__item' id='normal' style='background-color: rgba(164, 164, 164, 0.7); box-shadow: 0 1px 15px 1px rgba(164, 164, 164, 0.7); padding: 14px 28px;position: relative;'>";
                }
            }
            else{
                if(data[i]['user_name'].includes('站長')){
                    ele = "<div class='m-widget3__item' id='admin' style='background-color: rgba(244, 164, 164, 0.7); box-shadow: 0 1px 15px 1px rgba(244, 164, 164, 0.7); padding: 14px 28px;position: relative;'>";
                }
                else{
                    ele = "<div class='m-widget3__item' id='normal' style='background-color: rgba(244, 164, 164, 0.7); box-shadow: 0 1px 15px 1px rgba(244, 164, 164, 0.7); padding: 14px 28px;position: relative;'>";
                }
            }
            if(data[i]['isPreferred'] === 1){
                ele += "<div class='MW4BW_'>";
                    {{-- @if ($visitor->engroup == 1) <a class="_3BQlNg bgXBUk"  style="color: white; font-weight: bold; font-size: 16px;">&nbsp;VIP&nbsp;</a> @endif --}}
                ele += "<img src='" + data[i]['button'] + "' alt='' height='25px' class='preferred'>";
                ele += "</div>";
            }
            ele += "<div class='m-widget3__header' style='width:95%'>";
            ele += "<div class='m-widget3__user-img'>";
            if(data[i]['isAvatarHidden'] === 1){
                ele += "<a href='/dashboard/chatShow/" + data[i]['user_id'] + "'><img class='m-widget3__img' style='max-width:none' src='makesomeerror' onerror=\"this.src='{{ url('/') }}/img/male-avatar.png'\" alt=''></a></div>";
            }
            else{
                ele += "<a href='/dashboard/chatShow/" + data[i]['user_id'] + "'><img class='m-widget3__img' style='max-width:none' src='" + data[i]['pic'] + "' onerror=\"this.src='/img/male-avatar.png'\" alt=''></a></div>";
            }

            ele += "<div class='m-widget3__info'>";
            ele += "<a href='/dashboard/chatShow/" + data[i]['user_id'] + "'>";

            if(data[i]['user_name'].includes('站長')){
                ele += "<span class='m-widget3__username' style='color:blue;'>";
            }
            else{
                ele += "<span class='m-widget3__username'>";
            }
            ele += data[i]['user_name'];
            ele += "</span>";
            ele += "<span class='m-widget3__time'>";
            //if(data[i]['read']=='N')tt++;
            //ele += (data[i]['read']=='Y') ?" 已讀":" 未讀";
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
            // ele += `
            //     <br>
            //     <span class="m-widget3__time" style="font-size: 0.7rem">
            //         ${data[i]['created_at']}
            //     </span>
            // `;
            ele += "</p></div>";

            ele += "<div class='m-widget3__delete'>";
            ele += "<a class='btn btn-danger m-btn m-btn--air m-btn--custom delete-btn' href='{{ url('/') }}/dashboard/chat/deleterow/{{ $user->id }}/" + data[i]['user_id'] + "'>刪除</a>";
            ele += "</div></div></div>";
            $('#user-list').append(ele);
            if(!isAll){
                $('#tips').remove();
                $('#user-list').append(options);
                $('<p style="color:red;" id="tips">如果發現訊息不完整，請按下全部顯示</p>').insertAfter($('.options'));
            }
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
<style>
    .MW4BW_ {
        position: absolute;
        left: 0px;
        top: 0;
        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -webkit-flex-direction: column;
        -moz-box-orient: vertical;
        -moz-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        -webkit-box-align: start;
        -webkit-align-items: flex-start;
        -moz-box-align: start;
        -ms-flex-align: start;
        align-items: flex-start;
        z-index: 1;
    }
    .container-form {
        display: flex;
        align-items: center;
    }
    /*._3BQlNg {*/
    /*    position: relative;*/
    /*    display: -webkit-box;*/
    /*    display: -webkit-flex;*/
    /*    display: -moz-box;*/
    /*    display: -ms-flexbox;*/
    /*    display: flex;*/
    /*    -webkit-box-align: center;*/
    /*    -webkit-align-items: center;*/
    /*    -moz-box-align: center;*/
    /*    -ms-flex-align: center;*/
    /*    align-items: center;*/
    /*    height: 30px;*/
    /*    padding: 0 3px;*/
    /*    border-top-right-radius: .18rem;*/
    /*    border-bottom-right-radius: .18rem;*/
    /*    border-top-left-radius: .18rem;*/
    /*    border-bottom-left-radius: .18rem;*/
    /*    !*background: currentColor;*!*/
    /*    background: -webkit-linear-gradient(left, #F45670, #FD7087);*/
    /*    background: -o-linear-gradient(right, #F45670, #FD7087);*/
    /*    background: -moz-linear-gradient(right, #F45670, #FD7087);*/
    /*    background: linear-gradient(to right, #F45670, #FD7087);*/
    /*    left: -.05rem;*/
    /*}*/
    /*.preferred{*/
    /*    float: left;*/
    /*}*/
</style>
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
                   @if(!\App\Models\Tip::isComment($user->id, $to->id) && $isVip && \App\Models\Tip::isCommentNoEnd($user->id, $to->id))
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
        <?php if($isVip)
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

                //($isVip && !$msgUser->isVip() && ($user->meta_()->notifhistory == '顯示VIP會員信件' || $user->meta_()->notifhistory == NULL)) || (!$isVip && $msgUser->isVip())
                // 收件通知
//                if(\App\Models\Message::onlyShowVip($user, $msgUser) || \App\Models\Message::showNoVip($user, $msgUser)) {
//                    continue;
//                }

                //echo 'message->to_id = '. $message->to_id . ' message->from_id = '. $message->from_id . ' user->id = ' . $user->id;
                $latestMessage = \App\Models\Message::latestMessage($user->id, $msgUser->id);
                // echo '<br/>';
                // echo json_encode($latestMessage);
            ?>
                    @if(!empty($latestMessage))
                        @if(\App\Models\Message::isAdminMessage($latestMessage->content))
                        <div class="m-widget3__item" @if(str_contains($msgUser->name, '站長')) id='admin' @else id='normal' @endif style="background-color: rgba(164, 164, 164, 0.7); box-shadow: 0 1px 15px 1px rgba(164, 164, 164, 0.7); padding: 14px 28px;position: relative;">

                        @else
                        <div class="m-widget3__item" @if(str_contains($msgUser->name, '站長')) id='admin' @else id='normal' @endif style="background-color: rgba(244, 164, 164, 0.7); box-shadow: 0 1px 15px 1px rgba(244, 164, 164, 0.7); padding: 14px 28px;position: relative;">

                        @endif
                            @if(isset($latestMessage->isPreferred))
                                <div class="MW4BW_">
                                    {{-- @if ($visitor->engroup == 1) <a class="_3BQlNg bgXBUk"  style="color: white; font-weight: bold; font-size: 16px;">&nbsp;VIP&nbsp;</a> @endif --}}
                                    <img src="{{ $latestMessage->button }}" alt="" height="25px" class="preferred">
                                </div>
                            @endif
                            <div class="m-widget3__header" @if(isset($to))style="width:95%"@else style="width:95%" @endif>
                                <div class="m-widget3__user-img">
                                    <a href="{{ route('chatWithUser', $msgUser->id) }}"><img class="m-widget3__img" style="max-width:none" src="@if($msgUser->meta_()->isAvatarHidden) {{ 'makesomeerror' }} @else {{$msgUser->meta_()->pic}} @endif" onerror="this.src='/img/male-avatar.png'" alt=""></a>
                                </div>

                                <div class="m-widget3__info">
                                <a href="{{ route('chatWithUser', $msgUser->id) }}">
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
                <p style="color:red; font-weight: bold; display: none;" id="warning">載入中，請稍候</p>
                <a class="btn btn-danger m-btn m-btn--air m-btn--custom text-white" id="showMore" onclick="showMore();" data-token="{{ csrf_token() }}">顯示往前一個月的訊息</a>
                <a class="btn btn-danger m-btn m-btn--air m-btn--custom text-white" id="showAll" onclick="showAll();" data-token="{{ csrf_token() }}">顯示全部訊息</a>
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
                @if ($isVip && $user->city !== $msgUser->city)

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
                                    @if($user->id == $msgUser->id && $message->read == "Y" && $isVip)
                                        已讀
                                    @elseif($user->id == $msgUser->id && $message->read == "N" && $isVip)
                                        未讀
                                    @elseif(!$isVip)
                                        已讀/未讀 (?)
                                    @endif
                                @endif
                            </p>
                        </div>

                        <div class="m-widget3__delete">
                            <form action="{{ route('deleteSingle') }}" method="post" class="container-form">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                <input type="hidden" name="uid" value="{{ $user->id }}">
                                <input type="hidden" name="sid" value="{{ $to->id }}">
                                <input type="hidden" name="ct_time" value="{{ $message->created_at }}">
                                <input type="hidden" name="content" value="{{ $message->content }}">
                                <button class='btn btn-danger'>刪除</button>
                                <a class='btn btn-danger' href="{!! route('reportMessagePage', ['id' => $message->id, 'sid' => $to->id]) !!}" style="margin-left: 5px;">檢舉</a>
                            </form>
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
                <input type="hidden" name="m_time" @if(isset($m_time)) value="{{ $m_time }}" @else value="" @endif>
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
                                <button id="reset" type="reset" class="btn btn-outline-danger m-btn m-btn--air m-btn--custom">取消</button>
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
    $.ajaxSetup({ cache: false });
    $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
        // you can use originalOptions.type || options.type to restrict specific type of requests
        options.data = jQuery.param($.extend(originalOptions.data||{}, {
            timeStamp: new Date().getTime()
        }));
    });
    d = new Date('{{ \App\Models\Message::$date }}');
    @if(isset($m_time))
        let m_time = '{{ $m_time }}';
    @else
        let m_time = '';
    @endif
    if(m_time){
        let intervalID = setInterval(function() {
            let intervalSecs = 60;
            @if(isset($m_time))
                let m_time = '{{ $m_time }}';
            @else
                let m_time = '';
            @endif
            // Split timestamp into [ Y, M, D, h, m, s ]
            let t = m_time.split(/[- :]/);
            // Apply each element to the Date function
            m_time = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4], t[5]));
            m_time.setHours(m_time.getHours() - 8);
            let now = new Date();
            let diff = now.getTime() - m_time.getTime();
            let diffInSec = Math.floor(diff / 1000);
            let still = intervalSecs - diffInSec;
            let text = document.getElementById('msgsnd').firstChild;
            if(diff < 0 && diffInSec >= intervalSecs){
                $(".tips").remove();
                text.data = '回覆';
                $('#msgsnd').enable(true);
                clearInterval(intervalID);
            }
            else{
                $('#msgsnd').enable(false);
                text.data = '還有' + still + '秒才能回覆';
            }
        },100);
        $("<a href='{!! url('dashboard/upgrade') !!}' style='color: red;' class='tips'>成為VIP即可解除此限制<br></a>").insertBefore('#msgsnd');
    }
    $('.msg').keyup(function() {
        let content = $('.msg').val(), msgsnd = $('.msgsnd');
        if($.trim(content) == "" ){
            $('.alert').remove();
            $("<a style='color: red; font-weight: bold;' class='alert'>請勿僅輸入空白！</a>").insertAfter(this);
            msgsnd.prop('disabled', true);
        }
        else {
            $('.alert').remove();
            msgsnd.prop('disabled', !checkForm());
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
        return checkForm;
    }
    else {
        $('.alert').remove();
        return checkForm;
    }
});
function checkForm(){
    @if(isset($m_time))
        let m_time = '{{ $m_time }}';
    @else
        let m_time = '';
    @endif
    if(m_time) {
        let intervalSecs = 60;
        @if(isset($m_time))
            let m_time = '{{ $m_time }}';
        @else
            let m_time = '';
        @endif
        // Split timestamp into [ Y, M, D, h, m, s ]
        let t = m_time.split(/[- :]/);
        // Apply each element to the Date function
        m_time = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4], t[5]));
        m_time.setHours(m_time.getHours() - 8);
        let now = new Date();
        let diff = now.getTime() - m_time.getTime();
        let diffInSec = Math.floor(diff / 1000);
        return diffInSec >= intervalSecs;
    }
    else{
        return true;
    }
}
</script>

@stop
