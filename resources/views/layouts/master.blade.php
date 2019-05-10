@include('partials.header')
<style>
    .announce-box {
        background-color: #f7eeeb;
        position: fixed;
        z-index: 999;
        left:0;
        right:0;
        top: 10%;
        bottom: 10%;
        margin-left: 15%;
        margin-right: 15%;
        border-width: 3px;
        border-style: dotted solid dotted;
        border-color: rgba(244, 164, 164, 0.7);
        padding: 5px;
        box-shadow: 0 1px 15px 1px rgba(113, 106, 202, .08);
        word-break: break-word;
    }
    .announce-box-small {
        background-color: #f7eeeb;
        position: fixed;
        z-index: 999;
        left:0;
        right:0;
        top: 25%;
        bottom: 25%;
        margin-left: 25%;
        margin-right: 25%;
        border-width: 3px;
        border-style: dotted solid dotted;
        border-color: rgba(244, 164, 164, 0.7);
        padding: 5px;
        box-shadow: 0 1px 15px 1px rgba(113, 106, 202, .08);
        word-break: break-word;
    }
    .float{
        position: absolute;
        float:left;
    }
    .footer {
        position: absolute;
        width: 133px;
        left:0;
        right:0;
        bottom: 5px;
        margin-left: auto;
        margin-right: auto;
    }
    .center{
        font-size: large;
        text-align: center;
    }
</style>
<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-footer--push" >
<div class="m-grid m-grid--hor m-grid--root m-page">
    @include("layouts.navigation")
    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
        <div class="m-grid__item m-grid__item--fluid m-wrapper">
            <div class="m-content">
                <div class="row">
                    <div class="col-md-12">
                        @include('partials.errors')
                        @include('partials.message')
                    </div>
                </div>
                @if(Session::has('announcement'))
                    @foreach(Session::get('announcement') as $key =>  $a)
                        <div class="announce-box">
                            <div class="btn btn-danger float close-window">X</div>
                            <h3>站長公告(第{{ count(Session::get('announcement')) - $key }}/{{ count(Session::get('announcement')) }}則)</h3>
                            <div class="center">{!! nl2br($a->content) !!}</div>
                            <div class="btn btn-primary footer close-window" onclick="disableAnnounce( {{ $a->id }} )">不再顯示本公告</div>
                        </div>
                    @endforeach
                @endif
                @if(Session::has('male_vip_expire_date'))
                    <div class="announce-box">
                        <div class="btn btn-danger float close-window">X</div>
                        <h3>VIP通知</h3>
                        <div class="center">{!! nl2br(Session::get('male_vip_expire_date')) !!}</div>
                    </div>
                @endif
                @if(Session::has('vip_expire_date'))
                    <div class="announce-box">
                        <div class="btn btn-danger float close-window">X</div>
                        <h3>免費VIP通知</h3>
                        <div class="center">歡迎{{ $user->name }}上線，您現在已是免費VIP，資格持續到{{ Session::get('vip_expire_date') }}。</div>
                    </div>
                @endif
                @if(Session::has('vip_pre_requirements'))
                    <div class="announce-box">
                        <div class="btn btn-danger float close-window">X</div>
                        <h3>免費VIP進度</h3>
                        <div class="center">歡迎{{ $user->name }}上線，您目前還需{{ Session::get('vip_pre_requirements') }}，再持續保持每天登入，就可以取得VIP資格。</div>
                    </div>
                @endif
                @if(Session::has('vip_gain_date'))
                    <div class="announce-box">
                        <div class="btn btn-danger float close-window">X</div>
                        <h3>免費VIP進度</h3>
                        <div class="center">歡迎{{ $user->name }}上線，只要持續保持每天登入，預計在{{ Session::get('vip_gain_date') }}後，您將可以取得VIP資格。</div>
                    </div>
                @endif
                @if(isset($cancel_notice))
                    <div class="announce-box">
                        <div class="btn btn-danger float close-window">X</div>
                        <h2>VIP取消付款成功</h2>
                        <div class="center">{!! nl2br($cancel_notice) !!}</div>
                    </div>
                @endif
                <div class="row">
                    @include('dashboard.panel')
                    <div class="content col-lg-9 col-md-8">
                        <div class="m-portlet m-portlet--full-height m-portlet--tabs  ">
                            @yield("app-content")
                        </div>
                    </div>
                </div>
            </div>
    @include('partials.footer')
    @include('partials.scrollup')
    <script type="text/javascript">
        var _token = '{!! Session::token() !!}';
        var _url = '{!! url("/") !!}';
    </script>
    @yield("pre-javascript")
    @include('partials.scripts')
    @yield("javascript")
</body>
<script>
    let close = document.getElementsByClassName("close-window");
    for(let i = 0, len = close.length; i < len; i++){
        close[i].addEventListener('click', function() {
            close[i].parentNode.parentNode.removeChild(close[i].parentNode);
        })
    }
    function disableAnnounce(aid){
        $.ajax({
            type: 'POST',
            url: '{{ route('announceRead') }}',
            data: { uid: "{{ $user->id }}", aid: aid, _token: "{{ csrf_token() }}"},
            success: function(xhr, status, error){
                console.log(xhr);
                console.log(error);
            },
            error: function(xhr, status, error){
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });
    }
</script>
</html>
