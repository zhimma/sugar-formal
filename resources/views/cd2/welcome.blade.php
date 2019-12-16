<?php
$path='cd2';
?>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" >
<head>
    <meta charset="UTF-8">
    <title>甜心包養網</title>
    <meta name="description" content="甜心包養網">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no, email=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <link rel="stylesheet" href="{{asset($path.'/common/index.css')}}" />
    <link rel="stylesheet" href="{{asset($path.'/index.rem.css')}}" />
    <link rel="stylesheet" href="{{asset($path.'/common/swiper.min.css')}}">
    <script src="{{asset($path.'/common/index.js')}}"></script>
</head>

<body>
<div class="box">
    <div class="header">
        <a href="{{url()->current()}}"><img class="header_left" src="{{asset($path.'/images/logo.png')}}" alt="甜心包養網"></a>
        <p>
            <a href="{!! url('register') !!}"><span class="header_right">註冊</span></a>
            <a href="{!! url('login') !!}"><span class="header_right">登入</span></a>
        </p>
        <div class="header_box">
            <p>保密/Secrecy</p>
            <div class="header_pic">
                <p class="header_img header_pic1">
                    <span class="header_zhezhao">隱私</span>
                </p>
                <p class="header_img header_pic2">
                    <span class="header_zhezhao header_zhezhao2">安全</span>
                </p>
                <p class="header_img header_pic3">
                    <span class="header_zhezhao">高品質</span>
                </p>
            </div>
        </div>
    </div>
    <div class="b_box">
        <p class="b_box_title">步骤/Step</p>
        <div class="b_box_containr">
            <dl class="b_box_containr1">
                <img class="b_box_containr1_img1" src="{{asset($path.'/images/icon1.png')}}" alt="">
                <p class="b_box_text1 b_box_text_bottom">註冊到使用全國最快速</p>
                <p class="b_box_text">只需一分鐘，馬上體驗包養交友</p>
            </dl>
            <dl class="b_box_containr2">
                <img class="b_box_containr1_img2" src="{{asset($path.'/images/icon2.png')}}" alt="">
                <p class="b_box_text1">網站不會對外提供任何咨詢</p>
                <p class="b_box_text">也不會有任何外流問題</p>
                <p class="b_box_text">只需要一個電子信箱即可完成注冊</p>

            </dl>
            <dl class="b_box_containr3">
                <img class="b_box_containr1_img" src="{{asset($path.'/images/icon3.png')}}" alt="">
                <p class="b_box_text1">網站有最多Daddy/Baby</p>
                <p class="b_box_text">且經過嚴密的審核機制使用的會員</p>
                <p class="b_box_text">都是最有禮貌的紳士、淑女</p>

            </dl>
        </div>
        <a href="{!! url('register') !!}"><div class="b_box_btn">立即註冊</div></a>
    </div>
    <div class="c_box">
        <p>Sugar Daddy</p>
        <div class="swiper-container">
            <div class="swiper-wrapper">

                @foreach ($imgUserF as $k => $v)
                    @if(isset($v))
                        <div class="swiper-slide">
                            <img class="c_box_pic1" src="https://www.sugar-garden.org/{{$v->pic}}" onerror="this.src='/new/images/icon_03.png'" alt="{{$v->name}}">
                        </div>
                    @endif
                @endforeach

            </div>
        </div>
        <!-- <div class="c_box_pic">
            <img class="c_box_pic1" src="./images/d_img1.png" alt="">
            <img class="c_box_pic2" src="./images/d_img2.png" alt="">
        </div> -->
        <p class="c_box_title">如果你是來尋找Sugar Baby</p>
        <div class="c_box_container">
            <p>1.最多的Sugar Baby</p>
            本網站有最多的Sugar Baby，再次尋覓最適合你的專屬 寶貝。
        </div>
        <div class="c_box_container">
            <p>2.直接了當</p>
            在保養網站上直接坦白目的，各取所需，不浪費彼此的實踐。
        </div>
        <div class="c_box_container">
            <p>3.安全保密</p>
            網站上可以不需要留下真實資料，保障個人隱私。
        </div>
        <a href="{!! url('dashboard/search') !!}"><div class="b_box_btn">尋找我的Sugar Baby</div></a>
    </div>
    <div class="c_box">
        <p>Sugar Baby</p>
        <div class="swiper-container">
            <div class="swiper-wrapper">
                @foreach ($imgUserM as $k => $v)
                    @if(isset($v))
                        <div class="swiper-slide">
                            <img class="c_box_pic1" src="https://www.sugar-garden.org/{{$v->pic}}" onerror="this.src='/new/images/icon_03.png'" alt="{{$v->name}}}">
                        </div>
                    @endif
                @endforeach

            </div>
        </div>
        <!-- <div class="c_box_pic">
            <img class="c_box_pic1" src="./images/baby_img1.png" alt="">
            <img class="c_box_pic2" src="./images/baby_img2.png" alt="">
        </div> -->
        <p>如果你是來尋找Sugar Daddy</p>
        <div class="c_box_container">
            <p>1.包養的經濟援助</p>
            在此你可以得到Sugar Daddy直接的經濟援助、增加收入、 不需要再爲經濟擔心。
        </div>
        <div class="c_box_container">
            <p>2.甜蜜邂逅</p>
            與Sugar Daddy在網站上甜蜜邂逅,獲得Daddy的疼愛、貼 心照顧
        </div>
        <div class="c_box_container">
            <p>3.隱私安全。</p>
            網站不會對外提供資料，注重個人隱私，但也要注意不要輕 易提供資料給他人
        </div>
        <a href="{!! url('dashboard/search') !!}"><div class="b_box_btn">尋找我的Sugar Daddy</div></a>
    </div>
    <div class="d_box">
        <p>他們都在甜心包養</p>
        <img class="d_box_img" src="{{asset($path.'/images/line_bg.png')}}" alt="">
    </div>
    <div class="e_box">
        <p class="e_box_header">
            <img class="e_box_header_img" src="{{asset($path.'/images/logo1.png')}}" alt="">
        </p>
        <p>甜心包養網是世界第一甜蜜定制交友網； </p>
        <p>全球用戶超過1000萬人。</p>
        <p>甜蜜定制，讓成功人士牽手魅力甜心！</p>
        <div class="e_box_bottom">
            <div class="e_box_bottom1">
                <a href="{!! url('terms') !!}">
                <img class="e_box_bottom1_img" src="{{asset($path.'/images/bottom_icon1.png')}}" alt="">
                <p>隱私政策</p>
                </a>
            </div>
            <div class="e_box_bottom1">
                <a href="{!! url('notification') !!}">
                <img class="e_box_bottom1_img" src="{{asset($path.'/images/bottom_icon2.png')}}" alt="">
                <p>站長開講</p>
                </a>
            </div>
            <div class="e_box_bottom1">
                <a href="{!! url('feature') !!}">
                <img class="e_box_bottom1_img" src="{{asset($path.'/images/bottom_icon3.png')}}" alt="">
                <p>網站使用</p>
                </a>
            </div>
            <div class="e_box_bottom1">
                <a href="{!! url('contact') !!}">
                <img class="e_box_bottom1_img" src="{{asset($path.'/images/bottom_icon4.png')}}" alt="">
                <p>聯絡我們</p>
                </a>
            </div>
        </div>

    </div>
</div>
</body>
<!-- jQuery -->
<script src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
<script src="{{asset($path.'/common/swiper.min.js')}}"></script>
<!--轮播库-->
<script>
    var swiper = new Swiper('.swiper-container', {
        // loop: true,
        pagination: '.swiper-pagination',
        paginationClickable: true,
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        slidesPerView: 'auto',
        paginationClickable: true,
    });
</script>

</html>
