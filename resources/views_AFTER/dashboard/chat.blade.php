@extends('layouts.newmaster')

@section('app-content')

<?php
$block_people =  Config::get('social.block.block-people');
$admin_email = Config::get('social.admin.email');

if (isset($to)) $orderNumber = $to->id;
else $orderNumber = "";
$code = Config::get('social.payment.code');
$umeta = $user->meta_();

?>
        <div class="photo weui-t_c">
            <img src="{{$umeta->pic}}">
            <p class="weui-pt20 weui-f18">{{$user->name}}</p>
            @if ((isset($cur) && $cur->isVip()) || $user->isVip()) 
                <p class="weui-pt10 m_p">
                    <span class="weui-pl10 weui-pr10">
                        <img src="/images/sousuo_03.png">
                        <span class="weui-v_m gj">高级会员</span>
                    </span>
                    <!-- <span class="weui-pl10 weui-pr10">
                        <img src="//images/sousuo_06.png">
                        <span class="weui-v_m bzj"> 保证金</span>
                    </span>
                    <span class="weui-pl10 weui-pr10">
                        <img src="//images/sousuo_08.png">
                        <span class="weui-v_m bwj"> 百万级</span>
                    </span> -->
                </p>
            @endif
        </div>
    </div>
</div>


<div class="container weui-pb30">

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
               
                @endif
            </span>
            <span style="text-align:right;" class="m-portlet__head-text">
                @if(isset($to))
                   @if(!\App\Models\Tip::isComment($user->id, $to->id) && $user->isVip() && \App\Models\Tip::isCommentNoEnd($user->id, $to->id))
                        @include('partials.nwetip-comment')
                   @else
                        @include('partials.newtip-invite')
                   @endif
               @endif
            </span>
        </div>
    </div>
</div>

<style type="text/css">
    input[type="checkbox"] {display:none;}
</style>
@if(str_contains(url()->current(), 'dashboard') && empty($to))
        <?php
            $collection = array();
            if($user->isVip())
                $messages = \App\Models\Message::allSenders($user->id, 1);
            else $messages = \App\Models\Message::allSenders($user->id, 0);

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
    <div class="container weui-pt30">
        <div class="weui-p20 m_p0">
            <div class="row">
                <!-- <div class="col-lg-1 col-md-1 col-sm-1 weui-pt10 weui-t_c check">
                    <label><input type="checkbox"><i><span class="glyphicon glyphicon-ok"></span></i></label>
                </div> -->
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <form method="post" action="{{ route('deleteAll', ['uid' => $user->id]) }}" style="display:inline-block;">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="submit" class="btn btn-danger weui-f16 weui-box_s delete-btn" value="刪除全部">
                    </form>
                    <!-- <input type="button" class="btn btn-danger1 weui-f16 weui-box_s weui-ml10" value="回復"> -->
                </div>
            </div>
        </div>

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
                if(\App\Models\Message::onlyShowVip($user, $msgUser)) {
                    continue;
                }

                //echo 'message->to_id = '. $message->to_id . ' message->from_id = '. $message->from_id . ' user->id = ' . $user->id;
                $msgFromUser = \App\Models\User::findById($message['from_id']);
                $msgToUser = \App\Models\User::findById($message['to_id']);
                $latestMessage = \App\Models\Message::latestMessage($user->id, $msgUser->id);
                $lastSender = \App\Models\Message::getLastSender($user->id, $msgUser->id);

                $count = \App\Models\Message::where([['to_id', $user->id],['from_id', $msgUser->id]])->orWhere([['to_id', $msgUser->id],['from_id', $user->id]])->orderBy('created_at', 'desc')->count();
                // echo '<br/>';
                // echo json_encode($latestMessage);
            ?>
                    @if(!empty($latestMessage))
                        <!-- @if(\App\Models\Message::isAdminMessage($latestMessage->content))
                        <div class="m-widget3__item" @if(str_contains($msgUser->name, '站長')) id='admin' @else id='normal' @endif style="background-color: rgba(164, 164, 164, 0.7); box-shadow: 0 1px 15px 1px rgba(164, 164, 164, 0.7); padding: 14px 28px;">

                        @else
                        <div class="m-widget3__item" @if(str_contains($msgUser->name, '站長')) id='admin' @else id='normal' @endif style="background-color: rgba(244, 164, 164, 0.7); box-shadow: 0 1px 15px 1px rgba(244, 164, 164, 0.7); padding: 14px 28px;">

                        @endif
                         -->
                        <div class="weui-p20 list">
                            <div class="row">
                                <!-- <div class="col-lg-1 col-md-1 col-sm-1 weui-pt30 weui-t_c check">
                                    <label class="weui-mt5"><input type="checkbox"><i><span class="glyphicon glyphicon-ok"></span></i></label>
                                </div> -->
                                <div class="col-lg-9 col-md-9 col-sm-8">
                                    <div class="media">
                                        <div class="media-left">
                                            <a href="/dashboard/chat/{{$msgUser->id}}">
                                                <img class="media-object photoimg weui-bod_r50" src="@if($msgUser->meta_()->isAvatarHidden) {{ 'makesomeerror' }} @else {{$msgUser->meta_()->pic}} @endif" onerror="this.src='/img/male-avatar.png'" alt="">
                                            </a>
                                        </div>
                                        <div class="media-body weui-pt10 weui-pl10">
                                            <a href="/dashboard/chat/{{$msgUser->id}}">
                                                <h4 class="media-heading weui-f16"> {{$msgUser->name}}</h4>
                                            </a>
                                            <div class="weui-c_6 weui-lh30">
                                                @if(\App\Models\Message::isAdminMessage($latestMessage->content))
                                                    <?php echo \App\Models\Message::cutLargeString(nl2br($latestMessage->content)); ?>
                                                @else
                                                    <?php echo \App\Models\Message::cutLargeString($latestMessage->content); ?>
                                                @endif
                                                <br>
                                                {{$latestMessage->created_at}}
                                                @if(\App\Models\Reported::cntr($msgUser->id) >= $block_people ) <br><span class="m-widget3__username" style="color:red">(此人遭多人檢舉)</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-3">
                                    <div class="weui-pb5 weui-pt15">与TA往来信件<a href="#" class="weui-red01 weui-f_b">{{$count}}</a>封</div>
                                    <form method="post" action="{{ route('deleteBetween', ['uid' => $user->id, 'sid' => $msgUser->id]) }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        <input type="submit" class="btn btn-danger weui-f16 weui-box_s delete-btn" value="刪除">
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        
        <!-- <nav aria-label="Page navigation" class="weui-t_c weui-pb15">
            <ul class="pagination">
                <li>
                    <a href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="active"><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
                <li>
                    <a href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav> -->
    </div>

    
@elseif(isset($to))


    <?php $icc = 1 ; $messages = \App\Models\Message::allToFromSender($user->id, $to->id) ?>
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
               <!--  @if(\App\Models\Message::isAdminMessage($message->content))
                <div class="m-widget3__item" <?php echo 'style="border-bottom: none !important; background-color: rgba(164, 164, 164, 0.7); box-shadow: 0 1px 15px 1px rgba(164, 164, 164, 0.7); padding: 16px 32px; 0px 32px"'; $icc = 0?>>
                    <div class="m-widget3__header" @if(isset($to))style="width:95%"@endif>
                        <div class="m-widget3__user-img" style="display:inline-block; margin-right: 6%; vertical-align: middle;">
                        <a href="#"><img class="m-widget3__img" style="max-width:4rem" src="<?php echo \App\Models\User::findByEmail($admin_email)->meta_()->pic; ?>" onerror="this.src='/img/male-avatar.png'" alt=""></a>
                        </div>
                @else
                <div class="m-widget3__item" @if ($icc == 1) <?php echo 'style="border-bottom: none !important; background-color: rgba(244, 164, 164, 0.7); box-shadow: 0 1px 15px 1px rgba(244, 164, 164, 0.7); padding: 16px 32px; 0px 32px"'; $icc = 0?>@else <?php $icc = 1; echo'style="border-bottom: none !important; padding: 14px 28px 0px 28px;"'; ?> @endif>
                    <div class="m-widget3__header" @if(isset($to))style="width:95%"@endif>
                        <div class="m-widget3__user-img" style="display:inline-block; margin-right: 6%; vertical-align: middle;">
                            <a href="/user/view/{{$msgUser->id}}"><img class="m-widget3__img" style="max-width:4rem" src="@if($msgUser->meta_()->isAvatarHidden) {{ 'makesomeerror' }} @else {{$msgUser->meta_()->pic}} @endif" onerror="this.src='/img/male-avatar.png'" alt=""></a>
                        </div>
                @endif -->

                @if($message->from_id==$user->id)
                <div class="clearfix weui-pt30">
                     <div class="weui-fl tx">
                        <a href="/user/view/{{$msgUser->id}}">
                            <img  src="@if($msgUser->meta_()->isAvatarHidden) {{ 'makesomeerror' }} @else {{$msgUser->meta_()->pic}} @endif" onerror="this.src='/img/male-avatar.png'" alt="" class="photoimg">
                        </a>
                     </div>
                     <div class="mlbox">
                         <div class="weui-dnb">
                         <h3 class="weui-f18">{{$msgUser->name}}</h3>
                         <div class="mltxt">
                             @if(\App\Models\Message::isAdminMessage($message->content))
                              <p><?php echo nl2br($message->content); ?></p>
                              @else
                               <p>{{$message->content}}</p>
                              @endif
                             <div class="clearfix">
                                 <span class="weui-fl">{{ $message->created_at }}     <span class="weui-blue weui-dnb">@if(!\App\Models\Message::isAdminMessage($message->content))
                                    @if($user->id == $msgUser->id && $message->read == "Y" && $user->isVip())
                                        【已讀】
                                    @elseif($user->id == $msgUser->id && $message->read == "N" && $user->isVip())
                                        【未讀】
                                    @elseif(!$user->isVip())
                                        已讀/未讀 (?)
                                    @endif
                                @endif</span></span>
                                 <div class="weui-fr">
                                     <form method="post" id="chat-{{$message->id}}" style="display: inline-block;" action="{{ route('deleteSingle', ['uid' => $user->id, 'sid' => $to->id, 'ct_time' => $message->created_at, 'content' => $message->content]) }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                       
                                    </form>
                                     <a  href="#" name="submit" onclick="document.getElementById('chat-{{$message->id}}').submit();return false"  class="btn delete-btn" style="background:#f29c9f;">删除</a>
                                        <a href="{!! route('reportMessagePage', ['id' => $message->id, 'sid' => $to->id]) !!}" class="btn m-btn m-btn--air m-btn--custom report-btn" style="background:#32b16c;">檢舉</a>
                                 </div>
                             </div>
                         </div>
                         </div>
                     </div>
                </div>
                @else
                <div class="clearfix weui-pt30">
                     <div class="weui-fr tx">
                        <a href="/user/view/{{$msgUser->id}}">
                            <img  src="@if($msgUser->meta_()->isAvatarHidden) {{ 'makesomeerror' }} @else {{$msgUser->meta_()->pic}} @endif" onerror="this.src='/img/male-avatar.png'" alt="" class="photoimg">
                        </a>
                     </div>
                     <div class="mlbox mlbox_r weui-t_r">
                        <div class=" weui-dnb">
                         <h3 class="weui-f18 weui-t_r">{{$user->name}}</h3>
                         <div class="mltxt mltxt01">
                              @if(\App\Models\Message::isAdminMessage($message->content))
                              <p><?php echo nl2br($message->content); ?></p>
                              @else
                               <p>{{$message->content}}</p>
                              @endif
                             <div class="clearfix">
                                 <span class="weui-fl">{{ $message->created_at }}   <span class="weui-red weui-dnb">
                                 @if(!\App\Models\Message::isAdminMessage($message->content))
                                    @if($user->id == $msgUser->id && $message->read == "Y" && $user->isVip())
                                        【已讀】
                                    @elseif($user->id == $msgUser->id && $message->read == "N" && $user->isVip())
                                        【未讀】
                                    @elseif(!$user->isVip())
                                        已讀/未讀 (?)
                                    @endif
                                @endif  
                                </span></span>
                                 <div class="weui-fr">
                                    <form method="post" id="chat-{{$message->id}}" style="display: inline-block;" action="{{ route('deleteSingle', ['uid' => $user->id, 'sid' => $to->id, 'ct_time' => $message->created_at, 'content' => $message->content]) }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    </form>
                                     <a  href="#" name="submit" onclick="document.getElementById('chat-{{$message->id}}').submit();return false"  class="btn delete-btn" style="background:#f29c9f;">删除</a>
                                        <a href="{!! route('reportMessagePage', ['id' => $message->id, 'sid' => $to->id]) !!}" class="btn m-btn m-btn--air m-btn--custom report-btn" style="background:#32b16c;">檢舉</a>
                                 </div>
                             </div>
                         </div>
                        </div>
                     </div>
                </div>
                @endif

                        
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
        <div class="modal-body">
            <textarea  class="form-control m-input msg" rows="4" id="msg" required name="msg" maxlength="200"></textarea>
        </div>
        <div class="modal-footer">
            <button id="msgsnd" class="btn btn-danger m-btn m-btn--air m-btn--custom msgsnd">回覆</button>&nbsp;&nbsp;
            <button type="reset" class="btn btn-outline-danger m-btn m-btn--air m-btn--custom">取消</button>
        </div>
    </form>

@endif
        

</div>




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
            return false;
        }
    });
    $('.report-btn').on('click',function(e){
        if(!confirm('確定要檢舉?')){
            e.preventDefault();
            return false;
            
        }
    });
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
