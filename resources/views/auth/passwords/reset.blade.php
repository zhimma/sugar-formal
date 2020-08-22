@extends('new.layouts.website')

@section('app-content')
    <div class="head_3">
        <div class="container">
            <div class="col-sm-12 col-xs-12 col-md-12">
                <div class="commonMenu">
                    <div class="menuTop">
                        <a href="{!! url('') !!}"><img src="/new/images/icon_41.png" class="logo" /></a>
                        <div class="ndlrfont"><a href="{!! url('/checkAdult') !!}">註冊</a>丨<a href="{!! url('login') !!}">登入</a></div>
                        <!--<span id="menuButton"><img src="images/icon.png" class="he_img"></span>-->
                    </div>
                    <!--<ul id="menuList" class="change marg30">
                        <div class="comt"><img src="images/t.png"></div>
                        <div class="coheight">
                        <div class="heyctop">測試系統賬號</div>
                        <div class="helist">
                            <ul>
                                <li><a href=""><img src="images/icon_38.png">搜索</a></li>
                                <li><a href=""><img src="images/icon_45.png">訊息</a><span>10</span></li>
                                <li><a href=""><img src="images/icon_46.png">名單</a></li>
                                <li><a href=""><img src="images/icon_48.png">我的</a></li>
                            </ul>
                        </div>
                        <a href="" class="tcbut">LOGOUT</a>
                        </div>
                    </ul>-->
                </div>
            </div>
        </div>
    </div>
    <!---->
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12">
                <div class="wd_xsy wpaword">
                    <div class="wxsy_title">忘記密碼</div>
                    <div class="wxsy_k ">
                        <form action="/password/reset" method="post">
                            {!! csrf_field() !!}
                            <input type="hidden" name="token" value="{{ $token }}">
                        <div class="wo_input01 dlmarbot"><input name="email" type="text" class="zcinput" placeholder="帳號 (您的E-mail)" value="{{ old('email') }}"></div>
                        <div class="wo_input01 dlmarbot"><input name="password" id="password" type="password" class="zcinput" placeholder="密碼" required></div>
                        <div class="wo_input01"><input name="password_confirmation" id="password_confirmation" type="password" class="zcinput" placeholder="密碼確認" required></div>
                            <button type="submit" class="dlbut" style="margin-bottom:20px;border-style: none;">更改密碼</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>
        var password = document.getElementById("password")
            , confirm_password = document.getElementById("password_confirmation");

        function validatePassword(){
            if(password.value != confirm_password.value) {
                confirm_password.setCustomValidity("密碼確認與上述密碼不相符");
            } else {
                confirm_password.setCustomValidity('');
            }
        }

        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;
    </script>
@stop


