<?php
$path = 'ts1';
//dd( $infoF_d->meta_()->age() );
?>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" >
<head>
    <meta charset="UTF-8" />
    <title>台灣甜心網</title>
    <meta name="description" content="台灣甜心網">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="stylesheet" href="{{asset($path.'/css/reset.css')}}">
    <link rel="stylesheet" href="{{asset($path.'/css/border.css')}}">
    <link rel="stylesheet" href="{{asset($path.'/css/style.css')}}">
    <link href="https://cdn.bootcss.com/Swiper/3.4.2/css/swiper.min.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/jquery/1.12.0/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/Swiper/3.4.2/js/swiper.min.js"></script>
</head>
<body>
<div class="banner img">
    <a href="{!! url('dashboard/search') !!}">
        <img src="{{asset($path.'/img/banner.jpg')}}" width="100%" alt="台灣甜心網">
    </a>
</div>

<!-- main -->
<div class="main img">
    <div class="section1">
        <img src="{{asset($path.'/img/tit_01.jpg')}}" class="tit1" alt="CONFIDENTIALITY">
        <div class="section1Cont">
            <ul>
                <li class="flex">
                    <span style="background-image: url({{asset($path.'/img/01.jpg')}});"></span>
                    <div class="section1LiRight">
                        <h1>快速</h1>
                        <p>不囉唆不麻煩，用最快的速度進入包養的世界。</p>
                    </div>
                </li>
                <li class="flex">
                    <span style="background-image: url({{asset($path.'/img/02.jpg')}});"></span>
                    <div class="section1LiRight">
                        <h1>安全</h1>
                        <p>不必留下繁瑣的資料，且保證絕對保護會員資料。</p>
                    </div>
                </li>
                <li class="flex">
                    <span style="background-image: url({{asset($path.'/img/03.jpg')}});"></span>
                    <div class="section1LiRight">
                        <h1>高品質</h1>
                        <p>除了網站精緻，會員也精心挑選，不論紳士或女孩皆必須遵守使用規範，使用者品質高。</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <div class="section2">
        <div class="section2Cont">
            <ul>
                <li class="flex"><p>免費註冊只要三分鐘</p></li>
                <li class="flex"><p>上傳照片及個人檔案</p></li>
                <li class="flex"><p>免費註冊只要三分鐘</p></li>
            </ul>
            <a href="{!! url('register') !!}">立即註冊</a>
        </div>
    </div>

    <div class="section3">
        <h1 class="section3Tit2">找尋你的</h1>
        <div class="section3Cont">
            <div class="section3Tit" id="section3Tit">
                <ul class="flex">
                    <li class="active1">甜心寶貝</li>
                    <li>甜心爹地</li>
                </ul>
            </div>
            <div class="section3Detail swiper-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="swiper-container" id="banner1">
                            <div class="swiper-wrapper">
                                @foreach ($imgUserF as $k => $v)
                                    @if(isset($v))
                                        <div class="swiper-slide">
                                            <a href="javascript:;"><img src="https://www.sugar-garden.org/{{$v->pic}}" onerror="this.src='/new/images/icon_03.png'" alt="{{$v->name}}" width="100%"></a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="swiper-pagination p1"></div>
                        </div>
                        <div class="slide1Detail active1">
                            <ul>
                                <li>
                                    <h1>最多女性使用者</h1>
                                    <span></span>
                                    <p>包養網站總是找不到女性對象嗎？很抱歉，這裡不會也這種問題！</p>
                                </li>
                                <li>
                                    <h1>開誠佈公</h1>
                                    <span></span>
                                    <p>直接坦白地說明自己的需求與條件，開誠佈公找尋適合的對象！</p>
                                </li>
                                <li>
                                    <h1>絕對保密</h1>
                                    <span></span>
                                    <p>注重隱私的爹地別擔心，網站絕對不會外流、提供他處任何資料。</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="swiper-slide txdd">
                        <div class="swiper-container" id="banner2">
                            <div class="swiper-wrapper">
                                @foreach ($imgUserM as $k => $v)
                                    @if(isset($v))

                                        <div class="swiper-slide">
                                            <a href="javascript:;">
                                                <img src="https://www.sugar-garden.org/{{$v->pic}}" onerror="this.src='/new/images/icon_03.png'" alt="{{$v->name}}}" width="100%">
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="swiper-pagination p2"></div>
                        </div>
                        <div class="slide1Detail active2">
                            <ul>
                                <li>
                                    <h1>最多女性使用者</h1>
                                    <span></span>
                                    <p>包養網站總是找不到女性對象嗎？很抱歉，這裡不會也這種問題！</p>
                                </li>
                                <li>
                                    <h1>開誠佈公</h1>
                                    <span></span>
                                    <p>直接坦白地說明自己的需求與條件，開誠佈公找尋適合的對象！</p>
                                </li>
                                <li>
                                    <h1>絕對保密</h1>
                                    <span></span>
                                    <p>注重隱私的爹地別擔心，網站絕對不會外流、提供他處任何資料。</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section4">
        <ul>
            <li class="flex">
                <span class="section4Img" style="background-image: url(https://www.sugar-garden.org/{{$infoF_d->meta_()->pic}});"></span>
                <div class="section4Detail">
                    <h1>{{$infoF_d->name}} <span>{{$infoF_d->meta_()->age()}}歲/ {{$infoF_d->meta_()->city}} / {{$infoF_d->meta_()->occupation}}</span></h1>
                    <p>{{$infoF_d->meta_()->about}}</p>
                </div>
            </li>
            <li class="flex">
                <span class="section4Img" style="background-image: url(https://www.sugar-garden.org/{{$infoM_d->meta_()->pic}});"></span>
                <div class="section4Detail">
                    <h1>{{$infoM_d->name}} <span>{{$infoM_d->meta_()->age()}}歲/ {{$infoM_d->meta_()->city}} / {{$infoM_d->meta_()->occupation}}</span></h1>
                    <p>{{$infoM_d->meta_()->about}}</p>
                </div>
            </li>
        </ul>
    </div>
</div>

<!-- footer -->
<footer class="img">
    <img src="{{asset($path.'/img/logo.png')}}" alt="">
    <dl>
        <dd>
            <a href="{!! url('terms') !!}">使用條款/</a>
            <a href="{!! url('terms') !!}">隱私政策</a>
        </dd>
        <dd><a href="{!! url('feature') !!}">網站使用</a></dd>
        <dd><a href="{!! url('notification') !!}">站長開講</a></dd>
        <dd><a href="{!! url('contact') !!}">聯絡我們</a></dd>
    </dl>
    <p>2007 - 2018 官方網站 在一個或多個國家註冊</p>
</footer>
</body>
</html>
<script>
    $(function(){

        resize2()

        window.onresize=function() {
            setTimeout(function(){
                window.location.reload();
            },5);
            resize2();
        }

        function resize2(){
            var deviceWidth = document.documentElement.clientWidth;
            if(deviceWidth > 750) deviceWidth = 750;
            document.documentElement.style.fontSize = deviceWidth / 7.5 + 'px';
        }

        // banner
        var mySwiper = new Swiper('#banner1', {
            autoplay: 3000,//可选选项，自动滑动
            loop: true,
            autoplayDisableOnInteraction:false,
            pagination : '.p1',
            observer:true,
            observeParents:true,
            autoHeight: true,
            // calculateHeight:true,
        })

        // banner
        var mySwiper2 = new Swiper('#banner2', {
            autoplay: 3000,//可选选项，自动滑动
            loop: true,
            autoplayDisableOnInteraction:false,
            pagination : '.p2',
            observer:true,
            observeParents:true,
            autoHeight: true,
            // calculateHeight:true,
        })

        var mySwiper = new Swiper('#banner2', {
            autoplay: 3000,//可选选项，自动滑动
            loop: true,
            autoplayDisableOnInteraction:false,
            pagination : '.indexPagination',
            observer:true,
            observeParents:true,
            autoHeight: true,
            // calculateHeight:true,
        })

        var mySwiper3 = new Swiper('.section3Detail', {
            autoplay: false,//可选选项，自动滑动
            loop: true,
            onSlideChangeStart: function(swiper){
                if(swiper.realIndex == 0){
                    $('#section3Tit').find('li:first').addClass('active1').siblings('li').removeClass('active2')
                }else{
                    $('#section3Tit').find('li:last').addClass('active2').siblings('li').removeClass('active1')
                }

            }
        })
        $('#section3Tit').find('li').click(function(){
            if($(this).index() == 0){
                $(this).addClass('active1').siblings('li').removeClass('active2')
            }else{
                $(this).addClass('active2').siblings('li').removeClass('active1')
            }
            mySwiper3.slideTo($(this).index()+1,300, false)
        })
    })
</script>
