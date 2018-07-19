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
          <div class="m-login__signup">
                <div class="m-login__head">
                    <h3 class="m-login__title">
                    註冊
                    </h3>
                    <div class="m-login__desc">
                        請記住您的密碼，不要留下真名
                    </div>
                </div>
                <form class="m-login__form m-form" method="POST" action="/register">
                    {!! csrf_field() !!}
                    <div class="form-group m-form__group">
                        <input class="form-control m-input" type="email" placeholder="E-mail 信箱(也是您未來的的帳號)" name="email" autocomplete="off">
                    </div>
                    <div class="form-group m-form__group">
                        <input class="form-control m-input" type="password" placeholder="密碼" name="password">
                    </div>
                    <div class="form-group m-form__group">
                        <input class="form-control m-input m-login__form-input--last" type="password" placeholder="密碼確認" name="password_confirmation">
                    </div>
                    <div class="form-group m-form__group">
                        <input class="form-control m-input" type="text" placeholder="暱稱" id="name" name="name" value="{{old('name')}}">
                    </div>
                    <div class="form-group m-form__group">
                        <input class="form-control m-input" type="text" placeholder="標題" name="title">
                    </div>
                    <div class="row form-group m-form__group m-login__form-sub" style="background-color: #f7f6f9; border-radius: 40px; padding: 1.5rem 1.5rem">
                        <div class="m-radio-list">
                            <label >
                                帳號類型（Daddy / Baby)
                            </label>
                            <label class="m-radio">
                                <input class="form-control" type="radio" name="engroup" value="1">甜心大哥/大姐<span></span>
                            </label>
                            <span class="sugar-radio-helper">
                            你願意用禮物、美食、旅行等種種方式，寵愛對方，為了得到他的陪伴
                            </span>
                            <label class="m-radio">
                                <input lass="form-control" type="radio" name="engroup" value="2">甜心寶貝<span></span>
                            </label>
                            <span class="sugar-radio-helper">
                            你想得到寵愛，陪伴甜心大哥/大姊
                            </span>
                        </div>
                    </div>
                    <div class="row form-group m-form__group m-login__form-sub">
                        <div class="col m--align-left">
                            <label class="m-checkbox m-checkbox--focus">
                                <input type="checkbox" name="agree">
                                我同意台灣甜心的
                                <a href="terms.php" class="m-link m-link--danger">
                                    條款隱私
                                </a>和
                                <a href="privacy.php" class="m-link m-link--danger">
                                    隱私
                                </a>
                                政策.
                                <span></span>
                            </label>
                            <span class="m-form__help"></span>
                        </div>
                    </div>
                    <div class="m-login__form-action">
                        <button type="submit" id="m_login_signup_submit" class="btn btn-danger m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn">
                            註冊
                        </button>
                        &nbsp;&nbsp;
                       <a href="{{ URL::previous() }}" class="btn btn-outline-danger m-btn m-btn--pill m-btn--custom  m-login__btn">
                            取消
                        </a>
                    </div>
                </form>
            </div>
        </div>
            </div>
            @include('partials.footer')
        </div>
    </div>
        @include('partials.scripts')
        <script src="/js/login.js" type="text/javascript"></script>
</body>
</html>
