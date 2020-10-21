@extends('layouts.newmaster')

@section('app-content')

<?php
    $canPostSeconds = \App\Models\Board::getPostSeconds($user->id);
?>
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
                                <h3 class="m-portlet__head-text">
                                    留言板
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <?php
                            $icc = 1;
                            $posts = \App\Models\Board::all_();
                            $canPost = true;
                        ?>

                        @if(!isset($posts))
                        @else
                            <div class="m-widget3">
                                @foreach ($posts as $post)
                                    <?php $postUser = \App\Models\User::findById($post->member_id); ?>
                                    <?php $canPost = \App\Models\Board::canPost($user->id); ?>
                                    <div class="m-widget3__item" @if ($icc == 1) <?php echo 'style="border-bottom: none !important; background-color: rgba(244, 164, 164, 0.7); box-shadow: 0 1px 15px 1px rgba(244, 164, 164, 0.7); padding: 16px 32px; 0px 32px"'; $icc = 0?>@else <?php $icc = 1; echo'style="border-bottom: none !important; padding: 14px 28px 0px 28px;"'; ?> @endif>
                                        <div class="m-widget3__header">
                                            <div class="m-widget3__user-img">
                                                <a href="/user/view/{{$postUser->id}}"><img class="photoimg " src="@if($postUser->meta_()->isAvatarHidden) {{ 'makesomeerror' }} @else {{ $postUser->meta_()->pic }} @endif" onerror="this.src='/img/male-avatar.png'" alt=""></a>
                                            </div>
                                            <div class="m-widget3__info">
                                            <a href="/dashboard/chat/{{$postUser->id}}">
                                                <span class="m-widget3__username">
                                                {{ $postUser->name }}
                                                </span><br>
                                                <span class="m-widget3__time">
                                                {{ $post->created_at }}
                                                </span>
                                            </a>
                                            </div>
                                        </div>
                                        <div class="m-widget3__body">
                                            <p class="m-widget3__text">
                                                {{ $post->post }}
                                            </p>
                                        </div>

                                        <div class="m-widget3__delete">
                                            @if($postUser->id == $user->id)
                                                <form method="post" action="{{ route('deleteBoard', ['uid' => $user->id, 'ct_time' => $post->created_at, 'content' => $post->post]) }}">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                                    <input type="submit" class="btn btn-danger m-btn m-btn--air m-btn--custom" value="刪除" />
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="m-portlet__foot m-portlet__foot--fit">
                                <form class="m-form m-form--fit m-form--label-align-right" name="postBoard" onsubmit="return validateEmpty()" method="POST" action="/dashboard/board">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                    <input type="hidden" name="userId" value="{{$user->id}}">
                                        <div class="m-portlet__body">
                                            <div class="form-group m-form__group row">
                                                <div class="col-9">
                                                    @if(!$canPost)
                                                    <textarea class="form-control m-input" rows="3" id="msg" name="msg" disabled> {{ $canPostSeconds }} 秒後即可再次留言</textarea>
                                                    @else
                                                    <textarea class="form-control m-input" rows="3" id="msg" name="msg" maxlength="80"></textarea>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="m-form__actions">
                                                <div class="row">
                                                    <div class="col-10">
                                                        <button id="vipw" type="submit" class="btn btn-danger m-btn m-btn--air m-btn--custom" @if(!$canPost) disabled @endif>留言</button>&nbsp;&nbsp;
                                                        <button type="reset" class="btn btn-outline-danger m-btn m-btn--air m-btn--custom" @if(!$canPost) disabled @endif>取消</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

@stop

@section('javascript')

<script>
		$(document).ready(function(){
		    @if (!$user->isVip())
			$("#vipw").click(function(event)
			{
			    var r = confirm("此功能需VIP權限開通，是否前往儲值?");
			    if (!r)
			    {
			        event.preventDefault();
			        //window.history.back();
			    }
			});
			@endif
		});

        function validateEmpty() {
            var content = document.forms["postBoard"]["msg"].value;

            @if ($user->isVip())
                if(trimfield(content) == null || trimfield(content) == "")
                {
                    alert("請輸入內容");
                    return false;
                }
            @endif
        }

        function trimfield(str)
        {
            return str.replace(/^\s+|\s+$/g,'');
        }
	</script>

@stop
