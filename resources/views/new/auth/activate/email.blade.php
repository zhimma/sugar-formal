@extends('new.layouts.website')

@section('app-content')

	<div class="container matop70">
        <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12">
{{--                <div class="shou shou02 sh_line"><span>Email 認證尚未通過</span>--}}
{{--                    <font>Email confirmation</font>--}}
{{--                </div>--}}
                <div class="email">
                @if(isset($user))
                    <h2>帳號註冊成功</h2>
                    <div class="embg">
                            <div class="embg_1">
                            <h3>您已註冊成功，以下是您所填寫的註冊資料：</h3>
                            <h3>暱稱：{{ $user->name }}</h3>
                            <h3>帳號類型：@if($user->engroup == 2)<b>甜心寶貝</b>@else<b>甜心爹地</b>@endif</h3>
                            <h3>一句話形容自己：{{ $user->title }}</h3>
                            <h3>Email：<span>{{ $user->email }} (若Email填寫錯誤，請重新註冊)</span></h3>
                            </div>
                    </div>
                    <div class="wxsy_title">站長的話</div>
                    <div class="wxsy_k">
                            <div class="wknr">
                                @if(Auth::check())
                                {!! $masterwords !!}
                                @endif

                                <h4>
                                    <a style="font-weight: bold" href="{!! url('notification') !!}">站長開講</a>
                                    <a style="font-weight: bold" href="{!! url('feature') !!}">網站使用</a>
                                    <a style="font-weight: bold" href="http://blog-tw.net/Sugar/%E5%8C%85%E9%A4%8A%EF%BC%8D%E5%A4%A7%E5%8F%94%E7%AF%87/">站長的碎碎念(完整版)</a>
                                </h4>
                            </div>
                    </div>
{{--                    <div class="yx_k">驗證碼已經寄到你的email : <a style="font-weight: bold">{{ $user->email }} (若Email填寫錯誤，請重新註冊)</a></div>--}}
{{--                    <a href="{{ url('activate/send-token') }}" class="vipbut">重新發送</a>--}}
                @elseif(isset($register))
                    <h2>註冊失敗</h2>
                    <div class="yx_k">系統無法找到您所填寫的資料，敬請重新註冊。</div>
                @else
                    <h2>驗證失敗</h2>
                    <div class="yx_k">這個驗證碼已經無效或是您提供了錯誤的驗證碼，請先嘗試登入先前所註冊的Email，若問題仍舊存在，敬請聯絡站長，謝謝。</div>
                @endif
                </div>
            </div>

        </div>
    </div>
@stop