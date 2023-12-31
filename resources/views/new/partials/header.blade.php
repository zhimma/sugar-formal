<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>甜心花園包養網，台灣人數最多的甜心與糖爹的約會包養網站。</title>
<meta name="Keywords" content="包養|包養網|甜心花園|包養金額|包養管道|包養行情">
<meta name="Description" content="甜心花園網是台灣最大，人數最多的包養媒合網站，本站管理嚴謹，制度完善，遠離詐騙。擁有最多上線人數的甜心與最優質的糖爹，是優秀男女約會交友的第一選擇。">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="turbo-cache-control" content="no-cache">
<script src="https://cdnjs.cloudflare.com/ajax/libs/webfont/1.6.28/webfontloader.js"></script>
<!-- livewire -->
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
<livewire:styles />
<!-- livewire end-->
<script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
@if(view()->shared('rap_service') && view()->shared('rap_service')->riseByUserEntry(view()->shared('user'))->isAllowUseVideoChat() && view()->shared('rap_service')->isUrlNeedEntireSiteVideoChat())
    <script src="/new/js/jquery.lazyload.min.js" type="text/javascript"></script>
    <script src="https://sdk.amazonaws.com/js/aws-sdk-2.1155.0.min.js"></script>
    <script src="https://unpkg.com/amazon-kinesis-video-streams-webrtc/dist/kvs-webrtc.min.js"></script>        
@endif
<script
        src="https://browser.sentry-cdn.com/7.50.0/bundle.tracing.replay.debug.min.js"
        integrity="sha384-pL616vHMifU441jSyUU69aId7J7N832oP9GForODkrZtwczblYdTOcxUJrbzulwf"
        crossorigin="anonymous"
></script>
<script
        src="https://browser.sentry-cdn.com/7.50.0/captureconsole.min.js"
        integrity="sha384-MLpMOdzpoBBVNrHDJSgNvhIy88xHmDN1WlDfXA7eQmiuBAzf0xKhBeZ9WSImjVgk"
        crossorigin="anonymous"
></script>
<script>
    Sentry.init({
        dsn: "https://91838140cc964d4994202d4d3994654a@o1051989.ingest.sentry.io/6090793",
        integrations: [new Sentry.Integrations.CaptureConsole(
            {
                // array of methods that should be captured
                // defaults to ['log', 'info', 'warn', 'error', 'debug', 'assert']
                levels: ['debug'],
            }
        )],
    });
</script>
{!! \Sentry\Laravel\Integration::sentryTracingMeta() !!}
<?php //新樣板css?>
<link href="/new/css/bootstrap.min.css" rel="stylesheet">
<link href="/new/css/bootstrap-theme.min.css" rel="stylesheet">
<link href="/plugins/sweetalert/sweetalert2.css" rel="stylesheet">
<link rel="stylesheet" href="/new/css/style.css">
<link rel="stylesheet" href="/new/css/button_new.css">
<link rel="stylesheet" href="/new/css/style_zero.css">
<link rel="stylesheet" href="/new/css/swiper.min.css">
<link rel="stylesheet" href="/plugins/parsleyjs/parsley.css">
<link rel="stylesheet" href="/new/css/responsive_chat.css">
<?php //新樣板js?>
<script src="/new/js/main.js" type="text/javascript"></script>
<script src="/new/js/loading.js" type="text/javascript"></script>
<script src="/plugins/sweetalert/sweetalert2.js" type="text/javascript"></script>
<link href="/plugins/fileuploader2.2/dist/font/font-fileuploader.css" rel="stylesheet" type="text/css">
<link href="/plugins/fileuploader2.2/dist/jquery.fileuploader.min.css" rel="stylesheet" type="text/css">
<link href="/plugins/fileuploader2.2/css/jquery.fileuploader-theme-thumbnails.css" rel="stylesheet" type="text/css">
<link href="/plugins/hopscotch/css/hopscotch.min.css" rel="stylesheet" type="text/css" />
<link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet">
<link href="/new/css/cover_bootstrap.css" rel="stylesheet"> <!--fix bootstrap.css for bootstrap4.js-->
<link rel="shortcut icon" href="/img/favicon.jpg" />
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-151409328-1"></script>
<script>
    Sentry.init({
        dsn: "https://91838140cc964d4994202d4d3994654a@o1051989.ingest.sentry.io/6090793",
        // this assumes your build process sets "npm_package_version" in the env
        release: "sugar-garden",
        integrations: [new Sentry.Integrations.BrowserTracing()],

        // We recommend adjusting this value in production, or using tracesSampler
        // for finer control
        tracesSampleRate: 0.3,
    });

    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'UA-151409328-1');
</script>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-B9JNVX79DJ"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-B9JNVX79DJ');
</script>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-JD3XWPPFMH"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-JD3XWPPFMH');
</script>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-YV39TMFKMC"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-YV39TMFKMC');
</script>
@if (isset($user))
    <script>
        let cfpLocal = window.localStorage.getItem('cfp');
        if(!cfpLocal){
            const cfp = { hash: "{{ str_random(50) }}" };
            {{-- 若無 CFP，則儲存 CFP，並於資料庫記錄 --}}
            $.ajax({
                type: 'POST',
                url: '{{ route('savecfp') }}?{{csrf_token()}}={{now()->timestamp}}',
                data: {
                    _token:"{{ csrf_token() }}",
                    hash : cfp.hash,
                },
                dataType: 'json',
                success: function(xhr){
                    window.localStorage.setItem('cfp', JSON.stringify(cfp));
                    console.log(xhr.msg);
                }
            });
        }
        else{
            {{-- 若有 CFP，則於背景檢查會員是否有 CFP，若無則於資料庫記錄 --}}
            cfpLocal = JSON.parse(cfpLocal);
            $.ajax({
                type: 'POST',
                url: '{{ route('checkcfp') }}?{{csrf_token()}}={{now()->timestamp}}',
                data: {
                    _token:"{{ csrf_token() }}",
                    hash : cfpLocal.hash,
                },
                dataType: 'json',
                success: function(xhr){
                    console.log(xhr.msg);
                }
            });
        }

        const user = {
            id: '{{ $user->id }}',
            email: '{{ $user->email }}'
        }
        let userLocal = window.localStorage.getItem('user');
        if(!userLocal){
            window.localStorage.setItem('user', JSON.stringify(user));
        }
        else{
            userLocal = JSON.parse(userLocal);
            if(userLocal.id !== user.id){
                $.ajax({
                    type: 'POST',
                    url: '{{ route('multipleLogin') }}?{{csrf_token()}}={{now()->timestamp}}',
                    data: {
                        _token:"{{ csrf_token() }}",
                        original_id : userLocal.id,
                        new_id : user.id
                    },
                    dataType: 'json',
                    success: function(xhr){
                        console.log(xhr.msg);
                    }
                });
            }
        }
        let users = null;
        let users_leaving = null;
        let BreakException = [];
        {{-- Echo.join('Online'); --}}
        @if(($isVip ?? false) || $user->isVip())
            @if(str_contains(url()->current(), 'search') || request()->route()->getName() == 'chat2View')
                {{-- $(document).ready(() => {
                    Echo.join('Online').here((users) => {
                        try {
                            let showedUsers = $(".searchStatus");
                            let chatUsers = $(".sjpic.shanx");
                            let userList;
                            if(showedUsers.length > 0){
                                userList = showedUsers
                            }
                        });
                    }
                }
                let users = null;
                let users_leaving = null;
                let BreakException = [];
            @elseif(str_contains(url()->current(), 'viewuser') || request()->route()->getName() == 'chat2WithUser')
                {{-- Echo.join('Online')
                    .here((users) => {
                        try {
                            users.forEach(function (user) {
                                @if(isset($to))
                                if (user['id'] == '{{ $to->id }}') {
                                    setUserOnlineStatus(1);
                                    throw BreakException;
                                }
                                @endif
                            });
                        } catch (e) {
                            if (e !== BreakException) throw e;
                        }
                    })
                    .joining((user) => {
                        @if(isset($to))
                        if (user.id == '{{ $to->id }}') {
                            setUserOnlineStatus(1);
                            return 0;
                        }
                        @endif
                    })
                    .leaving((user) => {
                        @if(isset($to))
                        if (user.id == '{{ $to->id }}') {
                            setUserOnlineStatus(0);
                            return 0;
                        }
                        @endif
                    }); --}}
            @endif
        @else
            {{-- $(document).ready(() => {setUserOnlineStatus('Non-VIP') }); --}}
        @endif
        function setUserOnlineStatus(status, element_id){
            {{-- onlineStatus: chatWithUser, onlineStatus2: viewuser, onlineStatusChatView: chatview --}}
            if(status === 'Non-VIP'){
                if($('#onlineStatus').length > 0){
                    $('#onlineStatus').addClass("onlineStatusNonVip");
                    $('#onlineStatus').prepend($('<img src="/new/images/wsx.png">'));
                }
                if($('#onlineStatusNonVip2').length > 0){
                    $('#onlineStatusNonVip2').show();
                }
                if(element_id){
                    {{-- $("#" + element_id).find('.onlineStatusChatView').addClass('nonVip');
                    $("#" + element_id).find('.onlineStatusChatView').prepend($('<img src="/new/images/wsx.png">')); --}}
                }
            }
            else if(status){
                if($('#onlineStatus').length > 0){
                    $('#onlineStatus').addClass("onlineStatus");
                }
                if($('#onlineStatus2').length > 0){
                    $('#onlineStatus2').show();
                }
                if(element_id){
                    $("#" + element_id).find('.onlineStatusChatView').addClass('online');
                    $(".searchStatus#" + element_id).addClass('onlineStatusSearch');
                }
            }
            else{
                if($('#onlineStatus').length > 0) {
                    $('#onlineStatus').removeClass('onlineStatus');
                }
                if($('#onlineStatus2').length > 0){
                    $('#onlineStatus2').hide();
                }
                if(element_id){
                    $("#" + element_id).find('.onlineStatusChatView').removeClass('online');
                    $(".searchStatus#" + element_id).removeClass('onlineStatusSearch');
                }
            }
        }
    </script>
    @include('new.partials.unread')
@endif
