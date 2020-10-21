


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
                        <a class=" weui-fl logo" href="#"><img src="/images/logo.png"></a>
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

    <div class="minh2"> 
        <div class="email weui-t_c">
            <h2>Email驗證</h2>
            <div class="weui-pt30 weui-pb30 weui-pl10 weui-pr10">
               <img src="/images/yanz_03.png">
               <span class="weui-v_m weui-f18 weui-dnb">驗證碼已經寄到你的email,請註意查收</span>
                <a class="btn btn-danger" href="{{ url('activate/send-token') }}">重新發送</a>
               <p><a href="{!! url('contact') !!}" style="color: red; font-weight: bold;">如果沒收到認證信/認證失敗，請點此聯繫站長。</a></p>
            </div>
        </div>
    
    </div>

    @include('partials.newfooter')
    @include('partials.newscripts')

</body>

</html>