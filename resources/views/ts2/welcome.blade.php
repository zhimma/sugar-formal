<?php
$path = 'ts2';
?>
<!doctype html>
<html lang="{{ app()->getLocale() }}" >
<head design-width="750">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <title>甜心花園網</title>
    <link rel="stylesheet" href="{{asset($path.'/css/reset.css')}}" /><!--重置样式-->
    <link rel="stylesheet" href="{{asset($path.'/css/style.css')}}" /><!--页面样式-->
    <link rel="stylesheet" href="{{asset($path.'/css/common.css')}}" /><!--常用样式库-->
    <link rel="stylesheet" href="{{asset($path.'/css/animate.min.css')}}" /><!--CSS3动画库-->
    <script src="{{asset($path.'/js/auto-size.js')}}"></script><!--设置字体大小-->
</head>
<body>
<div class="mobile-wrap center">

    <div class="header">
        <div class="top">
            <div class="fl">
                <h2>甜心花園網</h2>
            </div>
            <div class="fr">
                <h4>台灣</h4>
                <p>
                    <a href="{!! url('register') !!}">註冊</a>
                    <a href="{!! url('login') !!}">登入</a>
                </p>
            </div>
        </div>
        <div class="text">
            <div class="fl">
                <h1>成功人士<br>認識魅力甜心</h1>
                <p>全台最大的Sugar Daddy/Baby包養網站</p>
            </div>
            <div class="fr">
                <img src="{{asset($path.'/img/1b9b9_262x380.png')}}" alt="">
            </div>
        </div>
    </div>

    <div class="my-look">
        <h2>我要找:<span>I'm looking for it</span></h2>
        <ul>
            <li>
                <img src="{{asset($path.'/img/12599_340x175.png')}}" alt="">
            </li>
            <li>
                <img src="{{asset($path.'/img/6d65f_340x175.png')}}" alt="">
            </li>
        </ul>
    </div>

    <div class="kuaisu">
        <ul>
            <li>
                <div class="pict fl">
                    <img src="{{asset($path.'/img/51326_122x122.png')}}" alt="">
                </div>
                <div class="text fr">
                    <h3>快速</h3>
                    <p>本站註冊快速，一分鐘即可完成並開啟您的甜心旅程。</p>
                </div>
            </li>
            <li>
                <div class="pict fr">
                    <img src="{{asset($path.'/img/e7f7f_122x122.png')}}" alt="">
                </div>
                <div class="text fl">
                    <h3>安全</h3>
                    <p>本網站之資料不會提供其他網站。事實上，註冊只需您的電子信箱，不須留下其他資料。</p>
                </div>
            </li>
            <li>
                <div class="pict fl">
                    <img src="{{asset($path.'/img/807a8_122x122.png')}}" alt="">
                </div>
                <div class="text fr">
                    <h3>高品質</h3>
                    <p>本網站為全台最大的Sugar Daddy/Baby包養網站，會員均須經過嚴密審核，保護使用者，並杜絕非法之有心人士。</p>
                </div>
            </li>
        </ul>
    </div>

    <div class="pub-tit"><strong>如果您是<span>富豪新贵</span></strong></div>
    <div class="ruguo">
        <div class="scroll-view swiper-container">
            <div class="pos prev"><img src="{{asset($path.'/img/0373a_50x98.png')}}" alt=""></div>
            <div class="pos next"><img src="{{asset($path.'/img/259eb_50x98.png')}}" alt=""></div>
            <ul class="swiper-wrapper">
                @foreach ($imgUserF as $k => $v)
                    @if(isset($v))
                        <li class="swiper-slide"><img src="https://www.sugar-garden.org/{{$v->pic}}" onerror="this.src='/new/images/icon_03.png'" alt="{{$v->name}}}"></li>
                    @endif
                @endforeach
            </ul>
        </div>
        <div class="look">
            <a href="{!! url('dashboard/search') !!}" class="more">點擊查看全部＞</a>
            <div class="box">
                <ul>
                    <li>
                        <h3>最多甜心寶貝</h3>
                        <p>本網站為全台最大交友包養網站，有最多女性會員。</p>
                    </li>
                    <li>
                        <h3>直接了當</h3>
                        <p>本網站不論男女使用目的明確，不拐彎抹角，節省時間。</p>
                    </li>
                    <li>
                        <h3>保密隱私</h3>
                        <p>註冊會員僅需留下電子信箱，本網站也保證絕不洩漏任何資料。</p>
                    </li>
                </ul>
            </div>
        </div>

    </div>

    <div class="pub-tit"><strong>如果您是<span>魅力寶貝</span></strong></div>
    <div class="ruguo">
        <div class="scroll-view swiper-container">
            <div class="pos prev"><img src="{{asset($path.'/img/0373a_50x98.png')}}" alt=""></div>
            <div class="pos next"><img src="{{asset($path.'/img/259eb_50x98.png')}}" alt=""></div>
            <ul class="swiper-wrapper">
                @foreach ($imgUserM as $k => $v)
                    @if(isset($v))
                        <li class="swiper-slide"><img src="https://www.sugar-garden.org/{{$v->pic}}" onerror="this.src='/new/images/icon_03.png'" alt="{{$v->name}}}"></li>
                    @endif
                @endforeach
            </ul>
        </div>
        <div class="look">
            <a href="{!! url('dashboard/search') !!}" class="more">點擊查看全部＞</a>
            <div class="box">
                <ul>
                    <li>
                        <h3>直接的經濟援助</h3>
                        <p>甜心爹地幫助您不再有經濟煩惱。</p>
                    </li>
                    <li>
                        <h3>圓夢實現</h3>
                        <p>世界上有許多以包養換取金錢，並進一步圓夢的案例。不論出國留學、創業、進修，甜心爹地通通買給你。</p>
                    </li>
                    <li>
                        <h3>小鳥依人</h3>
                        <p>除了經濟援助，還可以受寵於穩重成熟的甜心爹地，給您受關愛、照顧的感覺</p>
                    </li>
                </ul>
            </div>
        </div>

    </div>

    <div class="clear"></div>

    <div class="footer">
        <p>
            <a href="{!! url('notification') !!}">站長開講</a>
            <a href="{!! url('feature') !!}">網站使用</a>
            <a href="{!! url('terms') !!}">使用條款</a>
            <a href="{!! url('contact') !!}">聯絡我們</a>
        </p>
        <b>
            <a href="{!! url('') !!}" class="a1">甜心花園網</a>
            <a href="{!! url('') !!}" class="a2">台灣</a>
        </b>
    </div>

</div><!--mobile_wrap-->

<script src="{{asset($path.'/js/jquery-2.2.4.min.js')}}"></script><!--jQ库-->
<script src="{{asset($path.'/js/swiper-4.2.0.min.js')}}"></script><!--轮播库-->
<script src="{{asset($path.'/js/MobEpp-1.1.1.js')}}"></script><!--封装函数-->
<script>
    var swiper = new Swiper('.swiper-container', {
        loop:true,
        slidesPerView: 'auto',
        loopedSlides: 3,
        navigation: {
            nextEl: '.ruguo .scroll-view .pos.next',
            prevEl: '.ruguo .scroll-view .pos.prev',
        },
    });
</script>
</body>
</html>

