<<<<<<< HEAD
@extends('layouts.master')

@section('app-content')

<?php $icc = 1; ?>

<div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    收藏會員
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="m-widget3">
                            <?php $visitors = \App\Models\MemberFav::findBySelf($user->id) ?>
                            @foreach ($visitors as $visitor)
                                <?php $favUser = \App\Models\User::findById($visitor->member_fav_id) ?>
                            <div class="m-widget3__item" @if ($icc == 1) <?php echo 'style="border-bottom: none !important; background-color: rgba(244, 164, 164, 0.7); box-shadow: 0 1px 15px 1px rgba(244, 164, 164, 0.7); padding: 16px 32px; 0px 32px"'; $icc = 0?>@else <?php $icc = 1; echo'style="border-bottom: none !important; padding: 14px 28px 0px 28px;"'; ?> @endif>
                                <div class="m-widget3__header">
                                    <div class="m-widget3__user-img">
                                        <a href="/user/view/{{$favUser->id}}"><img class="m-widget3__img" src="@if($favUser->meta_()->isAvatarHidden) {{ 'makesomeerror' }} @else {{ $favUser->meta_()->pic }} @endif" onerror="this.src='/img/male-avatar.png'" alt=""></a>
                                    </div>
                                    <div class="m-widget3__info">
                                        <span class="m-widget3__username">
                                        {{ $favUser->name }} @if ($favUser->isVip()) (VIP) @endif
                                        </span><br>
                                        <span class="m-widget3__time">
                                        {{ $visitor->created_at }}
                                        </span>
                                    </div>
                                </div>
                                <div class="m-widget3__body">
                                </div>
                            </div>

                            @endforeach
                        </div>
                    </div>

@stop
=======
@extends('layouts.master')

@section('app-content')

<?php $icc = 1; ?>

<div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    收藏會員
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="m-widget3">
                            <?php $visitors = \App\Models\MemberFav::findBySelf($user->id) ?>
                            @foreach ($visitors as $visitor)
                                <?php $favUser = \App\Models\User::findById($visitor->member_fav_id) ?>
                            <div class="m-widget3__item" @if ($icc == 1) <?php echo 'style="border-bottom: none !important; background-color: rgba(244, 164, 164, 0.7); box-shadow: 0 1px 15px 1px rgba(244, 164, 164, 0.7); padding: 16px 32px; 0px 32px"'; $icc = 0?>@else <?php $icc = 1; echo'style="border-bottom: none !important; padding: 14px 28px 0px 28px;"'; ?> @endif>
                                <div class="m-widget3__header">
                                    <div class="m-widget3__user-img">
                                        <a href="/user/view/{{$favUser->id}}"><img class="m-widget3__img" src="@if($favUser->meta_()->isAvatarHidden) {{ 'makesomeerror' }} @else {{ $favUser->meta_()->pic }} @endif" onerror="this.src='/img/male-avatar.png'" alt=""></a>
                                    </div>
                                    <div class="m-widget3__info">
                                        <span class="m-widget3__username">
                                        {{ $favUser->name }} @if ($favUser->isVip()) (VIP) @endif
                                        </span><br>
                                        <span class="m-widget3__time">
                                        {{ $visitor->created_at }}
                                        </span>
                                    </div>
                                </div>
                                <div class="m-widget3__body">
                                </div>
                            </div>

                            @endforeach
                        </div>
                    </div>

@stop
>>>>>>> simon_foreign_area
