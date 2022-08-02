@extends('new.layouts.website')

@section('app-content')
    <!--{{--
<div class="m-portlet__head">
    <div class="m-portlet__head-caption">
        <div class="m-portlet__head-title">
            <h3 class="m-portlet__head-text">
            {{$message}} <small></small>
            </h3>
            --}}-->
	<div class="container matop70">
        <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12">
                <div class="shou shou02 sh_line">
                    <span>{{$message}}</span>
                </div>
                <div class="email">
                @if($user??null)
                    <div class="m-portlet__body">
                        <p><h3>{{ $user->name }}進階驗證已通過</h3></p>
                        <p>{{$message}} 。現在開始，您的帳號將被標註為本站的<img src="{{asset('new/images/b_6.png')}}" class="adv_auth_icon" />進階驗證會員<img src="{{asset('new/images/b_6.png')}}"  class="adv_auth_icon" />。
                        </p><p>按<a href="{!! url('login') !!}"> 這裡 登入</a>。</p>
                    </div>                
                </div>
            </div>
            <div class="yx_k">驗證碼已經寄到你的email : <a style="font-weight: bold">{{ $user->email }} </a></div>  

                {{-- <p>由於寄信系統維護中，如需重新驗證請直接向站長聯繫</p>
                <a href="{!! url('contact') !!}" style="color: red; font-weight: bold;">點此聯繫站長</a> --}}
                @else
                    <h2>驗證失敗</h2>
                    <div class="yx_k">這個驗證碼已經無效或是您提供了錯誤的驗證碼，請先嘗試登入先前所註冊的Email，若問題仍舊存在，敬請聯絡站長，謝謝。</div>
                @endif
                </div>
            </div>

        </div>
    </div>
</div> 
<div class="m-portlet__body">
    <p><h3></h3></p>
    <p>{{$message}}</p>
    <p>按<a href="{!! url('login') !!}">這裡登入</a>。</p>
</div>    
@endif
@stop
<style type="text/css">
    .vipbut1 {
        height: 40px;
        background: #fe92a8;
        border-radius: 200px;
        color: #ffffff;
        text-align: center;
        line-height: 40px;
        display: table;
        margin: 0 auto;
        margin: 0 5%;
        flex: 1;
        box-shadow: 0 0 20px #ffb6c5;
    }
    .vipbut1_block {
        width: 50%;
        margin: 0 auto;
        display: flex; 
        /*padding: 5% 0;*/
    }
    @media (max-width:736px) {
        .vipbut1_block{ 
            width: 80%;
        }
    }
    @media (max-width:667px) {
        .vipbut1_block{ 
            width: 80%;
        }
    }
    @media (max-width: 450px) {
        .n_embut{
            /*width:156px!important;*/
            margin-left:unset!important;
            margin-right:unset!important;
        }
    }
</style>