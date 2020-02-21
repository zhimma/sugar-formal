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
                    <form class="de_input " name="register" method="POST" action="/register" data-parsley-validate novalidate>
                        {!! csrf_field() !!}
                        <div class="de_input01 dlmarbot">
                            <input type="password" style="display:none" />
                            <input required name="email" type="email" class="zcinput" placeholder="E-mail 信箱（也是您未來的帳號）" value="{{old('email')}}" autocomplete="off">
                        </div>
                        <div class="de_input01 dlmarbot">
                            <input type="password" style="display:none" />
                            <input style="-webkit-text-security: disc;" required id="pw" minlength="6"  data-parsley-minlength="6" name="password" type="password" class="zcinput" placeholder="密碼" value="{{old('password')}}" autocomplete="off">
                        </div>
                        <div class="de_input01 dlmarbot">
                            <input  data-parsley-equalto="#pw" required name="password_confirmation" type="password" class="zcinput" placeholder="密碼確認" value="{{old('password_confirmation')}}" autocomplete="off">
                        </div>
                        <div class="de_input01 dlmarbot">
                            <input required id="name" name="name"  type="text" class="zcinput" placeholder="暱稱 (至多八個字)" value="{{old('name')}}" maxlength="8">
                        </div>
                        <div class="de_input01 dlmarbot">
                            <input required name="title" type="text" class="zcinput" placeholder="一句話形容自已"  value="{{old('title')}}">
                        </div>
                        <div class="de_input02">
                            <h2>帳號類型（Daddy / Baby）</h2>
                            <h3><input required type="radio"  name="engroup" value="1" id="RadioGroup1_0"><span>甜心大哥</span></h3>
                            <h4>你願意用禮物、美食、旅遊等種種方式，寵愛對方，為了得到他的陪伴</h4>
                            <h3><input required type="radio" name="engroup" value="2" id="RadioGroup1_1"><span>甜心寶貝</span></h3>
                            <h4>妳想得到寵愛，陪伴甜心大哥</h4>
                        </div>
                        <div class="decheck"><input required name="agree" type="checkbox" ><span>我同意甜心花園的使用條款和隱私政策</span></div>

                        <a href="javascript:void(0);" class="dlbut btn-register">註冊</a>
                        <a href="" class="zcbut matop20">取消</a>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            window.ParsleyValidator.addMessage('en', 'minlength', '密碼欄位請輸入大於6個位元(含以上)');

            $("form[name=register]").parsley().on('form:validate', function (formInstance) {

            })
            .on('form:error', function () {
                var error = $('ul.parsley-errors-list li');
                var msg=[];
                for (var i = 0; i <error.length; i++) {
                    msg[i]=error.eq(i).html();
                }
                msg = Array.from(new Set(msg));
                // ResultData({
                //   msg: msg
                // });
                c2(msg);
            })
            .on('form:success', function () {
                return true;
            });
            @if (isset($errors)&&($errors->count() > 0))
                var errormsg=[];
                for (var i = 0; i <$('ul.quarx-errors li').length; i++) {
                    errormsg[i]=$('ul.quarx-errors li').eq(i).html();
                }
                errormsg = Array.from(new Set(errormsg));
                // ResultData({
                //     msg: errormsg
                // });
                c2(errormsg);
            @endif
        });
        $('.alert-danger').css('display','none');
        $(".btn-register").click(function(e){
            var t = $(this).closest("form");
            t.submit();
        });
    </script>
    <!-- <script src="/js/login.js" type="text/javascript"></script> -->
@stop