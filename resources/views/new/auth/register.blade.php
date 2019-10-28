@extends('new.layouts.website')

@section('app-content')

	<div class="container matop120">
        <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12">
                <div class="dengl matbot140">
                    <div class="col-md-12">
                        @include('partials.errors')
                        @include('partials.message')
                    </div>
                    <div class="zhuce"><h2>註冊</h2><h3>請記住您的密碼，不要留下真名</h3></div>
                    <form class="de_input " method="POST" action="/register" >
                        {!! csrf_field() !!}
                        <div class="de_input01 dlmarbot">
                            <input name="email" type="text" class="zcinput" placeholder="E-mail 信箱（也是您未來的帳號）" value="{{old('email')}}">
                        </div>
                        <div class="de_input01 dlmarbot">
                            <input name="password" type="password" class="zcinput" placeholder="密碼" value="{{old('password')}}">
                        </div>
                        <div class="de_input01 dlmarbot">
                            <input name="password_confirmation" type="password" class="zcinput" placeholder="密碼確認" value="{{old('password_confirmation')}}">
                        </div>
                        <div class="de_input01 dlmarbot">
                            <input id="name" name="name"  type="text" class="zcinput" placeholder="暱稱" value="{{old('name')}}">
                        </div>
                        <div class="de_input01 dlmarbot">
                            <input name="title" type="text" class="zcinput" placeholder="一句話形容自已"  value="{{old('title')}}">
                        </div>
                        <div class="de_input02">
                            <h2>帳號類型（Daddy / Baby）</h2>
                            <h3><input type="radio"  name="engroup" value="1" id="RadioGroup1_0"><span>甜心大哥</span></h3>
                            <h4>你願意用禮物、美食、旅遊等種種方式，寵愛對方，為了得到他的陪伴</h4>
                            <h3><input type="radio" name="engroup" value="2" id="RadioGroup1_1"><span>甜心寶貝</span></h3>
                            <h4>妳想得到寵愛，陪伴甜心大哥</h4>
                        </div>
                        <div class="decheck"><input  name="agree" type="checkbox" ><span>我同意甜心花園的使用條款和隱私政策</span></div>

                        <a href="javascript:void(0);" class="dlbut btn-register">註冊</a>
                        <a href="" class="zcbut matop20">取消</a>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>

        $(".btn-register").click(function(e){
            var t = $(this).closest("form");
            t.submit();
        });
    </script>
    <!-- <script src="/js/login.js" type="text/javascript"></script> -->
@stop