<!DOCTYPE html>
<html lang="zh" >
    <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>註冊</title>
    <meta name="Keywords" content="包養，包養網，甜心，甜心寶貝">
    <meta name="Description" content="我們提供都會優質男女交流的機遇。台灣甜心網是台灣，包養，包養網、的媒合網站。">
        <link href="css/vendors.bundle.css" rel="stylesheet" type="text/css" />
    <link href="css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="css/style-new.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="/plugins/parsleyjs/parsley.css">
    <script src="js/jquery.min.js"></script>
        <style>
            .lytitle {
                width: 100%;
                background: #faeded;
                line-height: 50px;
                display: table;
                color: #e65757;
                font-size: 20px;
                font-weight: bold;
            }
            .lytitle i {
                width: 4px;
                height: 20px;
                background: #e65757;
                margin-left: 20px;
                margin-right: 10px;
                display: table;
                float: left;
                margin-top: 15px;
            }
            h3.weui-t_c{margin-top: 0;}
        </style>
 <body >

 
<div class="rester_wrap weui-box_s">
    <header class="header headerbg weui-pb10 weui-pt10">
      <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class=" weui-fl weui-pl10 weui-pt5" href="{!! url('') !!}"><img src="images/homeicon.png"></a> </div>
        </div>
      </nav>
    </header>

    <div class="lytitle"><i></i>註冊新用戶</div>
    <div class="weui-p20">
        <div class="col-md-12">
            @include('partials.errors')
            @include('partials.message')
        </div> 
        <form class="m-login__form m-form" method="POST" action="/register" data-parsley-validate novalidate>
            {!! csrf_field() !!}
            <h3 class="weui-pb15 weui-t_c">帳號類型（Daddy/Baby）</h3>
            <div class="weui-flex remind_bx">
                <div class="weui-flex__item">
                    <div class="circle01 weui-bod_r50">
                        <label for='sex'>
                            <div class="weui-t_c weui-f12 weui-pb5 weui-pt5">
                                <input type="radio"  name="engroup"  required="required"  value="1" id="sex">
                                甜心大哥
                            </div>
                            <p class="weui-orange weui-f12">你願意用禮物、美食、旅行等種種方式，能愛對方，為了得到她的陪伴</p>
                        </label>
                    </div>
                </div>
                <div class="weui-flex__item">
                    <div class="circle01 circle02 weui-bod_r50">
                        <label for='sex1'>
                            <div class="weui-t_c weui-f12 weui-pb5 weui-pt5">
                                <input type="radio"  id="sex1" required  name="engroup" value="2" >
                                甜心寶貝
                            </div>
                            <p class="weui-orange weui-f12">妳想得到真愛，陪伴甜心大哥</p>
                        </label>
                    </div>
                </div>
            </div>
            <h3 class="weui-pt15 weui-pb15 weui-t_c">請記住您的密碼，不要留下真名</h3>

            <div class="weui-mt10">
                <div class="weui-flex form01">
                    <div><img src="images/sjzc2_03.png" width="14"></div>
                    <div class="weui-flex__item weui-pl10">
                        <input type="password" style="display:none" />
                        <input type="email" class="form02" placeholder="E-mail信箱（也是您未來的帳號）"  value="{{old('email')}}" required name="email" autocomplete="off" ></div>
                </div>
            </div>
            <div class="weui-mt10">
                <div class="weui-flex form01">
                    <div><img src="images/sjzc2_07.png" width="14"></div>
                    <div class="weui-flex__item weui-pl10"><input type="password" class="form02" placeholder="密碼" value="{{old('password')}}" name="password" required></div>
                </div>
            </div>

            <div class="weui-mt10">
                <div class="weui-flex form01">
                    <div><img src="images/sjzc2_10.png" width="14"></div>
                    <div class="weui-flex__item weui-pl10"><input type="password" class="form02"  required="required" placeholder="密碼確認" value="{{old('password_confirmation')}}" name="password_confirmation"></div>
                </div>
            </div>
            <div class="weui-mt10">
                <div class="weui-flex form01">
                    <div><img src="images/sjzc2_12.png" width="14"></div>
                    <div class="weui-flex__item weui-pl10"><input type="text" class="form02" placeholder="暱稱" name="name" value="{{old('name')}}"  required="required"></div>
                </div>
            </div>
            <div class="weui-mt10">
                <div class="weui-flex form01">
                    <div><img src="images/sjzc2_16.png" width="14"></div>
                    <div class="weui-flex__item weui-pl10"><input type="text" class="form02" placeholder="一句話形容自己" name="title"  value="{{old('title')}}" required></div>
                </div>
            </div>
            <p class="weui-pt10">
                <input type="checkbox" name="agree"  id="zhengce" required >
                <label for="zhengce" style="margin-bottom:0px;font-weight:100">
                    <span>我同意台灣甜心的使用條款和隱私政策</span>
                </label>
            </p>
            <div class="row weui-pt20">
                <div class=" col-sm-6 col-xs-6 col-md-6 col-lg-6"><button type="submit" id="m_login_signup_submit" class=" btn btn_reater01 btn-block">註冊</button></div>
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6"><a  href="{!! url('') !!}" class="btn btn_reater02 btn-block">取消</a></div>
            </div>
        </form>
    </div>
    <div class="footer weui-t_c weui-white">
    <h3 class="weui-pt30"><img src="images/homeicon.png" class="weui-pt30"></h3>
    <p class="weui-f18 weui-c_9">TAIWAN/台灣</p>
    <div class="container">
      <div class="row">
        <div class="col-md-3"> <a href="/terms" class="weui-white">使用條款/隱私政策</a> </div>
        <div class="col-md-3"> <a href="/feature" class="weui-white">網站使用</a> </div>
        <div class="col-md-3"> <a href="/notification" class="weui-white">站長開講</a> </div>
        <div class="col-md-3"> <a href="/contact" class="weui-white">聯絡我們</a> </div>
      </div>
    </div>
    <p class="weui-c_9">2007 - 2018 官方網站 在一個或多個國家註冊</p>
  </div>
  </div>
<script src="js/vendors.bundle.js" type="text/javascript"></script>
<script src="js/scripts.bundle.js" type="text/javascript"></script>
<script src="js/messages_zh_TW.min.js"></script>
<script src="js/jquery.twzipcode.min.js" type="text/javascript"></script>
<script src="js/login.js" type="text/javascript"></script>
</body>
</html>
