





@include('partials.newheader')
<body class="mainbg">
<div class="infoheader">
    <div class="weui-pt30 weui-pb30 container">
        <nav class="navbar navbar-default" role="navigation">
            <div>
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#example-navbar-collapse">
                        <span class="sr-only">切换导航</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class=" weui-fl logo" href="{!! url('') !!}"><img src="/images/logo.png"></a>
                </div>
                <div class="collapse navbar-collapse weui-fr" id="example-navbar-collapse">
                    <ul class="nav navbar-nav weui-f16">
                        <li><a href="{!! url('register') !!}"><img src="/images/shouye_11.png"> <span class="weui-v_m weui-pl10">注册</span></a></li>
                        <li><a href="{!! url('login') !!}"><img src="/images/shouye_06.png"> <span class="weui-v_m weui-pl10">登入</span></a></li>
                        <li><a href="#"><img src="/images/shouye_08.png"> <span class="weui-v_m weui-pl10"> 台湾</span></a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>  
</div>

<div class="col-md-12">

@include('partials.errors')

@include('partials.message')

</div>
<div class="minh2 minh_login weui-pb30">
    <div class=" container">
        <div class="row">
            <h3 class="">確認帳號密碼</h3>
            <form class="m-login__form m-form" method="POST" action="/dashboard/cancelpay">
            {!! csrf_field() !!}
                <div class="col-lg-9 col-md-9 col-sm-9">
                    <div class="email zzdh weui-pb30 login weui-pt30">
                        <div class=" clearfix weui-pt30 weui-pb30">
                            <div class="col-lg-8 col-md-8 col-sm-8 col-lg-push-2  col-md-push-2  col-sm-push-2">
                                <h3 class="weui-f18">賬號（您的Email地址）</h3>
                                <input type="text" class="form_rester"  type="email" placeholder="帳號 (您的E-mail)" name="email" values="{{ old('email') }}" autocomplete="off">
                                <h3 class="weui-f18 weui-pt20">密碼</h3>
                                <input type="password" class="form_rester" type="password" placeholder="密碼" name="password" id="password">
                                <p class="clearfix weui-pt10 weui-f16">
                                    <span class="weui-fl">
                                        <label><input <input type="checkbox" value="1" style="display: none;"  name="remember"> <i><span class="glyphicon glyphicon-ok"></span></i></label>
                                        <span class="weui-v_m">記住我</span>
                                    </span>
                                    <a href="{!! url('password/reset') !!}" class="weui-fr weui-red01">忘記密碼</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-lg-push-2  col-md-push-2  col-sm-push-2">
                            <div class="weui-f18 rester_bottom weui-pb30">
                                <button type="submit" id="m_login_signin_submit" class="rester_btn weui-ml5 weui-mr5 weui-mb5 weui-t_c btn btn-danger m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary">
                                登錄
                            </button>

                            </div>
                        </div>
                    </div>

                </div>
            </form>
            <div class="col-lg-3 col-md-3 col-sm-3">
                 <div class="login_r">
                     <h3 class="weui-f18 weui-t_c">還沒有帳號 ?    <a href="{!! url('register') !!}" class="weui-ml20 weui-f_b weui-t_d">免費註冊</a></h3>
                     <p class="weui-f16">本站系統改版，如有舊帳號無法登入，帳號資料不正常，請點右下聯絡我們。跟站方聯繫</p>
                 </div>
            </div>
        </div>
    </div>

</div>
 @include('partials.newfooter')
        @include('partials.newscripts')

        <script src="/js/jquery.validate.js" type="text/javascript"></script>
        <script src="/js/login.js" type="text/javascript"></script>


</body>

</html>
