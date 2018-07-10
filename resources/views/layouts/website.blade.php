@include('partials.header')
<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-footer--push" >
    <div class="m-grid m-grid--hor m-grid--root m-page">
        @include('layouts.navigation')
        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--singin m-login--2 m-login-2--skin-2" style="background-color: #f7eeeb">
            <div class="m-grid__item m-grid__item--fluid m-login__wrapper">
                <div class="m-login__container">
                    <div class="m-portlet m-portlet--mobile">
                        @yield("app-content")
                    </div>
                </div>
            </div>
            @include('partials.footer')
    </div>
        
        @include('partials.scripts')
</body>
</html>