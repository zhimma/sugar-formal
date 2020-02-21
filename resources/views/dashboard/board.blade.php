@extends('layouts.master')

@section('app-content')

<?php
    $canPostSeconds = \App\Models\Board::getPostSeconds($user->id);
?>

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

                        {{-- @if(!isset($posts)) --}}
                        @if(true)
                            <div class="m-widget3">
                                <h1>留言板功能調整暨重新整理中，不便之處，敬請見諒。</h1>
                            </div>
                        @else
                            <div class="m-widget3">
                                @foreach ($posts as $post)
                                    <?php $postUser = \App\Models\User::findById($post->member_id); ?>
                                    <?php $canPost = \App\Models\Board::canPost($user->id); ?>
                                    <div class="m-widget3__item" @if ($icc == 1) <?php echo 'style="border-bottom: none !important; background-color: rgba(244, 164, 164, 0.7); box-shadow: 0 1px 15px 1px rgba(244, 164, 164, 0.7); padding: 16px 32px; 0px 32px"'; $icc = 0?>@else <?php $icc = 1; echo'style="border-bottom: none !important; padding: 14px 28px 0px 28px;"'; ?> @endif>
                                        <div class="m-widget3__header">
                                            <div class="m-widget3__user-img">
                                                <a href="/user/view/{{$postUser->id}}"><img class="m-widget3__img" src="@if($postUser->meta_()->isAvatarHidden) {{ 'makesomeerror' }} @else {{ $postUser->meta_()->pic }} @endif" onerror="this.src='/img/male-avatar.png'" alt=""></a>
                                            </div>
                                            <div class="m-widget3__info">
                                            <a href="{{ route('chatWithUser', $postUser->id) }}">
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
