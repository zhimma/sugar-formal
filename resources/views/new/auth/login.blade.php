@extends('new.layouts.website')

@section('app-content')

	<div class="container logtop">
        <div class="row">
            <div class="col-md-12">
                @include('partials.errors')
                @include('partials.message')
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12">
                <form class="" name="login" action="/login" method="POST" class="dengl"  data-parsley-validate novalidate>
                    {!! csrf_field() !!}
                   <div class="dengl_h">登入</div>
                   <div class="de_input">
                        <div class="m-loader m-loader--right m-loader--light">
                            <div class="de_input01 dlmarbot ">
                                <div class="de_img"><img src="/new/images/lo_03.png"></div>
                                <input name="email" type="email" autocomplete="off" class="d_input" placeholder="帳號 (您的Email)" values="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="de_input01 dlmarbot m-loader m-loader--right m-loader--light">
                            <div class="de_img"><img src="/new/images/lo_11.png"></div>
                            <input name="password" type="password"  class="d_input" placeholder="密碼" required>
                        </div>
                        <a href="{!! url('password/reset') !!}" class="dlpassword">忘記密碼 ?</a>
                        <a href="javascript:void(0);" onclick="" class="dlbut btn-login">登入</a>
                        <a href="{!! url('register') !!}" class="dlbut02">還沒有帳號 ?  免費註冊</a>
                   </div>
                </form>
            </div>
        </div>
    </div>
    <script>

        $(".btn-login").click(function(e){
            var email = $("input[name=email]").val();
            var password = $("input[name=password]").val();
            var t = $(this).closest("form");
            if(email.length==0||password.length==0){
                swal({
                    title:'請輸入帳號或密碼',
                    type:'error'
                });
            }else{
                t.submit();
            }
        });
    </script>
    <!-- <script src="/js/login.js" type="text/javascript"></script> -->
@stop