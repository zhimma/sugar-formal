@extends('layouts.master')

@section('app-content')

<div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    封鎖名單
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="m-widget3">
                            <?php
                                $icc = 1;
                                $blocks = \App\Models\Blocked::getAllBlock($user->id);
                            ?>
                            @foreach ($blocks as $block)
                                <?php $blockedUser = \App\Models\User::findById($block->blocked_id) ?>
                            <div class="m-widget3__item" @if ($icc == 1) <?php echo 'style="border-bottom: none !important; background-color: rgba(244, 164, 164, 0.7); box-shadow: 0 1px 15px 1px rgba(244, 164, 164, 0.7); padding: 16px 32px; 0px 32px"'; $icc = 0?>@else <?php $icc = 1; echo'style="border-bottom: none !important; padding: 14px 28px 0px 28px;"'; ?> @endif>
                                <div class="m-widget3__header">
                                    <div class="m-widget3__user-img">
                                        <a href="/user/view/{{$blockedUser->id}}"><img class="m-widget3__img" src="{{ $blockedUser->meta_()->pic }}" onerror="this.src='/img/male-avatar.png'" alt=""></a>
                                    </div>
                                    <div class="m-widget3__info">
                                    <a href="/user/view/{{$blockedUser->id}}">
                                        <span class="m-widget3__username">
                                        {{ $blockedUser->name }}
                                        </span><br>
                                    </a>
                                    </div>
                                </div>
                            </div>

                            @endforeach
                        </div>
                    </div>

@stop
