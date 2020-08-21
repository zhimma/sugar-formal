<?php
$path='cd1';
?>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" >
<head>
    <meta charset="utf-8" />
    <title>甜心包養網</title>
    <meta name="description" content="甜心包養網">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="format-detection" content="telephone=no" />
    <link rel="stylesheet" href="{{asset($path.'/css/reset.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset($path.'/css/swiper.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset($path.'/css/css.css')}}" />
    <script type="text/javascript" src="{{asset($path.'/js/jquery.js')}}"></script>
    <script type="text/javascript" src="{{asset($path.'/js/rem.js')}}"></script>
</head>

<body>
{{--@if (Auth::user() && Request::path() != '/activate' && Request::path() != '/activate/send-token')--}}
{{--    <script type="text/javascript">--}}
{{--        window.location = "{!! url('dashboard/search') !!}";//here double curly bracket--}}
{{--    </script>--}}
{{--@endif--}}
<div class="header">
    <div class="title">
        <a href="{{url()->current()}}"><img src="{{asset($path.'/images/title.png')}}" alt="甜心包養網"/></a>
        <div class="land">
            <a href="{!! url('login') !!}">登錄</a>
            <a href="{!! url('register') !!}">註冊</a>
        </div>
    </div>
    <div class="banner">
        <div class="swiper-container sw2">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="t">
                        <img src="{{asset($path.'/images/Page1.png')}}"/>
                        <p>台灣</p>
                    </div>
                    <span>成功人士認識魅力甜心全台人數最多的Sugar Daddy/Baby 包養網站</span>
                </div>
                <div class="swiper-slide">
                    <div class="t">
                        <img src="{{asset($path.'/images/Page1.png')}}"/>
                        <p>台灣</p>
                    </div>
                    <span>成功人士認識魅力甜心全台人數最多的Sugar Daddy/Baby 包養網站</span>
                </div>
                <div class="swiper-slide">
                    <div class="t">
                        <img src="{{asset($path.'/images/Page1.png')}}"/>
                        <p>台灣</p>
                    </div>
                    <span>成功人士認識魅力甜心全台人數最多的Sugar Daddy/Baby 包養網站</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="main">
    <div class="btn">
        <p>我要找</p>
        <div>
            <a href="{!! url('dashboard/search') !!}">甜心寶貝</a>
            <a href="{!! url('dashboard/search') !!}">甜心爹地</a>
        </div>
    </div>
    <div class="list">
        <ul>
            <li>
                <img src="{{asset($path.'/images/Group3.png')}}" />
                <p>最火速註冊，馬上尋找甜心爹地/寶貝。</p>
            </li>
            <li>
                <img src="{{asset($path.'/images/Group.png')}}" />
                <p>最注重隱私，不需留下私人資料，尋覓對象好輕鬆。</p>
            </li>
            <li>
                <img src="{{asset($path.'/images/Group 4.png')}}" />
                <p>最多人使用，眾多對象讓您尋覓，不怕找不到甜心爹地/寶貝。</p>
            </li>
        </ul>
    </div>
</div>
<div class="box_list">
    <div class="box_tit_box">
        <div class="box_tit">
            <p>選擇當甜心爹地</p>
            <a href="{!! url('dashboard/search') !!}">查看更多</a>
        </div>
    </div>
    <div class="swipers">
        <div class="swiper-container sw">
            <div class="swiper-wrapper">
                @foreach ($imgUserF as $k => $v)
                    @if(isset($v))
                <div class="swiper-slide">
                    <img src="https://www.sugar-garden.org/{{$v->pic}}" onerror="this.src='/new/images/icon_03.png'">
                </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    <ul>
        <li>
            <img src="{{asset($path.'/images/Group5.png')}}" />
            <div>
                <p>找到你的甜心寶貝</p>
                <span>從眾多甜心中找尋專屬於你的寶貝</span>
            </div>
        </li>
        <li>
            <img src="{{asset($path.'/images/Group 9.png')}}" />
            <div>
                <p>不浪費時間</p>
                <span>直接坦白各自的目的，各取所需。</span>
            </div>
        </li>
        <li>
            <img src="{{asset($path.'/images/Group9Copy.png')}}" />
            <div>
                <p>安全保密</p>
                <span>最注重會員隱私，保證資料絕不外流。</span>
            </div>
        </li>
    </ul>
</div>
<div class="box_list">
    <div class="box_tit_box">
        <div class="box_tit">
            <p>選擇當甜心寶貝</p>
            <a href="{!! url('dashboard/search') !!}">查看更多</a>
        </div>
    </div>
    <div class="swipers">
        <div class="swiper-container sw1">
            <div class="swiper-wrapper">
                @foreach ($imgUserM as $k => $v)
                    @if(isset($v))
                        <div class="swiper-slide">
                            <img src="https://www.sugar-garden.org/{{$v->pic}}" onerror="this.src='/new/images/icon_03.png'">
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    <ul>
        <li>
            <img src="{{asset($path.'/images/Group 15.png')}}" />
            <div>
                <p>找到你的甜心爹地</p>
                <span>在眾多紳士當中，找到最適合的甜心爹地。</span>
            </div>
        </li>
        <li>
            <img src="{{asset($path.'/images/Group 14.png')}}" />
            <div>
                <p>滿足經濟需求</p>
                <span>甜心爹地的金援，讓你不再憂心經濟問題。</span>
            </div>
        </li>
        <li>
            <img src="{{asset($path.'/images/Group9Copy1.png')}}" />
            <div>
                <p>不失疼愛</p>
                <span>除了經濟援助，同時獲得甜心爹地的關愛、照顧。</span>
            </div>
        </li>
    </ul>
</div>
<footer>
    <ul>
        <li>
            <a href="{!! url('notification') !!}">【站長開講】</a>
        </li>
        <li>
            <a href="{!! url('feature') !!}">【網站使用】</a>
        </li>
        <li>
            <a href="{!! url('terms') !!}">【使用條款】</a>
        </li>
        <li>
            <a href="{!! url('contact') !!}">【聯絡我們】</a>
        </li>
    </ul>
</footer>
<script type="text/javascript" src="{{asset($path.'/js/swiper.min.js')}}"></script>
<script>
    var swiper = new Swiper('.sw', {
        slidesPerView: 2,
        spaceBetween: 30,
    });
    var swiper = new Swiper('.sw1', {
        slidesPerView: 2,
        spaceBetween: 30,
    });
    var swiper = new Swiper('.sw2', {

    });
</script>
</body>

</html>
