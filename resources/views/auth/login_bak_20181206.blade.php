<<<<<<< HEAD
@include('partials.header')
<body class="m--skin- m-header--fixed m-header--fixed-mobile m-footer--push" >
    <div class="m-grid m-grid--hor m-grid--root m-page">
        @include('layouts.navigation')
        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--singin m-login--2 m-login-2--skin-2" style="background-color: #f7eeeb">
            <div class="m-grid__item m-grid__item--fluid m-login__wrapper">
            <div class="m-login__container">
            <div class="m-login__logo">
                <a href="#">
                    <img src="img/logo.png" width="30%">
                </a>
            </div>
                                       <div class="row">
                <div class="col-md-12">
                    @include('partials.errors')
                    @include('partials.message')
                </div>
            </div>
            <div class="m-login__signin">
                <div class="m-login__head">
                    <h3 class="m-login__title">
                        登錄
                    </h3>
                </div>
                <form class="m-login__form m-form" method="POST" action="/login">
                    {!! csrf_field() !!}
                    <div class="form-group m-form__group">
                        <input class="form-control m-input" type="email" placeholder="帳號 (您的E-mail)" name="email" values="{{ old('email') }}" autocomplete="off">
                    </div>
                    <div class="form-group m-form__group">
                        <input class="form-control m-input m-login__form-input--last" type="password" placeholder="密碼" name="password" id="password">
                    </div>
                    <div class="row m-login__form-sub">
                   	<div class="col m--align-left m-login__form-left">
                            <label class="m-checkbox  m-checkbox--focus">
                                <input type="checkbox" name="remember">
                                記住我
                                <span></span>
                            </label>
                        </div>
                        <div class="col m--align-right m-login__form-right">
                            <a href="{!! url('password/reset') !!}" id="m_login_forget_password" class="m-link">
                                    忘記密碼 ?
                            </a>
                        </div>
                    </div>
                    <div class="m-login__form-action">
                        <button type="submit" id="m_login_signin_submit" class="btn btn-danger m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary">
                            登錄
                        </button>
                    </div>
                </form>
            </div>
          <div class="m-login__account">
                <span class="m-login__account-msg">
                還沒有帳號 ?
                </span>
                &nbsp;&nbsp;
                <a href="{!! url('register') !!}" id="m_login_signup" class="m-link m-link--light m-login__account-link">
                免費註冊
                </a>
            </div>
            <div class="">
                <p style="color:red; font-size:14px; font-weight:bold;">本站系統改版，如有舊帳號無法登入，帳號資料不正常，請點右下聯絡我們。跟站方聯繫</p>
            </div>
        </div>
            </div>
        </div>
        @include('partials.footer')
    </div>
        @include('partials.scripts')
        <script src="/js/login.js" type="text/javascript"></script>
</body>
</html>
=======
@include('partials.header')
<body class="m--skin- m-header--fixed m-header--fixed-mobile m-footer--push" >
    <div class="m-grid m-grid--hor m-grid--root m-page">
        @include('layouts.navigation')
        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--singin m-login--2 m-login-2--skin-2" style="background-color: #f7eeeb">
            <div class="m-grid__item m-grid__item--fluid m-login__wrapper">
            <div class="m-login__container">
            <div class="m-login__logo">
                <a href="#">
                    <img src="img/logo.png" width="30%">
                </a>
            </div>
                                       <div class="row">
                <div class="col-md-12">
                    @include('partials.errors')
                    @include('partials.message')
                </div>
            </div>
            <div class="m-login__signin">
                <div class="m-login__head">
                    <h3 class="m-login__title">
                        登錄
                    </h3>
                </div>
                <form class="m-login__form m-form" method="POST" action="/login">
                    {!! csrf_field() !!}
                    <div class="form-group m-form__group">
                        <input class="form-control m-input" type="email" placeholder="帳號 (您的E-mail)" name="email" values="{{ old('email') }}" autocomplete="off">
                    </div>
                    <div class="form-group m-form__group">
                        <input class="form-control m-input m-login__form-input--last" type="password" placeholder="密碼" name="password" id="password">
                    </div>
                    <div class="row m-login__form-sub">
                   	<div class="col m--align-left m-login__form-left">
                            <label class="m-checkbox  m-checkbox--focus">
                                <input type="checkbox" name="remember">
                                記住我
                                <span></span>
                            </label>
                        </div>
                        <div class="col m--align-right m-login__form-right">
                            <a href="{!! url('password/reset') !!}" id="m_login_forget_password" class="m-link">
                                    忘記密碼 ?
                            </a>
                        </div>
                    </div>
                    <div class="m-login__form-action">
                        <button type="submit" id="m_login_signin_submit" class="btn btn-danger m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary">
                            登錄
                        </button>
                    </div>
                </form>
            </div>
          <div class="m-login__account">
                <span class="m-login__account-msg">
                還沒有帳號 ?
                </span>
                &nbsp;&nbsp;
                <a href="{!! url('register') !!}" id="m_login_signup" class="m-link m-link--light m-login__account-link">
                免費註冊
                </a>
            </div>
            <div class="">
                <p style="color:red; font-size:14px; font-weight:bold;">本站系統改版，如有舊帳號無法登入，帳號資料不正常，請點右下聯絡我們。跟站方聯繫</p>
            </div>
        </div>
            </div>
        </div>
        @include('partials.footer')
    </div>
        @include('partials.scripts')
        <script src="/js/login.js" type="text/javascript"></script>
</body>
</html>
>>>>>>> simon_foreign_area
