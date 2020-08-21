

@include('partials.newheader')
<style type="text/css">
    .error{
        color: red;
    }
</style>
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
                    <a class=" weui-fl logo" href="#"><img src="images/logo.png"></a>
                </div>
                <div class="collapse navbar-collapse weui-fr" id="example-navbar-collapse">
                    <ul class="nav navbar-nav weui-f16">
                        <li><a href="{!! url('register') !!}"><img src="images/shouye_11.png"> <span class="weui-v_m weui-pl10">注册</span></a></li>
                        <li><a href="{!! url('login') !!}"><img src="images/shouye_06.png"> <span class="weui-v_m weui-pl10">登入</span></a></li>
                        <li><a href="#"><img src="images/shouye_08.png"> <span class="weui-v_m weui-pl10"> 台湾</span></a></li>
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


<div class="container">
    <form id="regform" class="m-login__form m-form" method="POST" action="/register">
        {!! csrf_field() !!}
        <div class="minh2 weui-pb30"> 
            <div class="email zzdh weui-pb30">
                <h3 class=" weui-t_c weui-red01 weui-f_b weui-f24 weui-pt30 weui-pb20">註冊</h3>
                <div class=" clearfix">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-lg-push-2  col-md-push-2  col-sm-push-2">
                        <h3 class="weui-f18">賬號（您的Email地址）</h3>
                        <input  class="form_rester"type="email" placeholder="E-mail 信箱(也是您未來的的帳號)" required="required"  name="email" value="{{old('email')}}" autocomplete="off" >
                        <h3 class="weui-f18">密碼</h3>
                        <input type="password" class="form_rester" placeholder="密碼" required="required" name="password" >
                        <h3 class="weui-f18">密碼確認</h3>
                        <input type="password" class="form_rester" type="password" placeholder="密碼確認" required="required" name="password_confirmation" >
                        <h3 class="weui-f18">昵稱</h3>
                        <input type="text" class="form_rester" value="{{old('name')}}" placeholder="暱稱" required="required" name="name" >
                        <h3 class="weui-f18">標題</h3>
                        <input type="text" class="form_rester" placeholder="標題" name="title"  value="{{old('title')}}">
                        <h3 class="weui-f18">帳號類型（Daddy / Baby)</h3>
                        <div class="clearfix radio_box">
                            <label>
                           <input type="radio"  name="engroup" value="1" class="weui-fl">
                           <dl>
                               <dt class="weui-f16">甜心大哥/大姐</dt>
                               <dd>你願意用禮物、美食、旅行等種種方式，寵愛對方，為了得到他的陪伴</dd>
                           </dl>
                           </label>
                        </div>
                        <div class="clearfix radio_box">
                            <label>
                           <input type="radio" name="engroup" value="2" class="weui-fl">
                           <dl>
                               <dt class="weui-f16">甜心寶貝</dt>
                               <dd>你想得到寵愛，陪伴甜心大哥/大姊</dd>
                           </dl>
                           </label>
                        </div>
                        <p class="">
                            <label><input type="checkbox" name="agree" value="1" style="display: none;" required="required"><i><span class="glyphicon glyphicon-ok"></span></i>
                            我同意台灣甜心的
                                <a href="terms.php" class="m-link m-link--danger">
                                    條款隱私
                                </a>和
                                <a href="privacy.php" class="m-link m-link--danger">
                                    隱私
                                </a>
                                政策.
                            </label>
                        </p>
                    </div>
                </div>
                
            </div>
               <div class="weui-t_c weui-f18 rester_bottom weui-pb30 m-login__form-action">
                    <button type="submit" id="m_login_signup_submit"  class="rester_btn weui-ml5 weui-mr5 weui-mb5 m-login__btn btn btn-danger m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn">註冊</button>
                    <a href="{{ URL::previous() }}" class="rester_btn rester_btn01 weui-ml5 weui-mr5 weui-mb5">取消</a>
               </div>
        </div>
    </form>
</div>
   @include('partials.newfooter')
        @include('partials.newscripts')

        <script src="/js/jquery.validate.js" type="text/javascript"></script>
        <script src="/js/login.js" type="text/javascript"></script>


</body>

</html>