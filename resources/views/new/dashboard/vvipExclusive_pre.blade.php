@extends('new.layouts.website')

@section('app-content')
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="g_password">
                    <div class="g_pwicon">
                        <li><a href="@if($user->isVVIP()) /dashboard/viewuser_vvip/{{$user->id}} @else /dashboard/viewuser/{{$user->id}} @endif" class="g_pwicon_t5 "><span>自我預覽</span></a></li>
                        <li><a href="{!! url('dashboard') !!}" class="g_pwicon_t "><span>基本資料</span></a></li>
                        <li><a href="{!! url('dashboard_img') !!}" class="g_pwicon_t2"><span>照片管理</span></a></li>
                        <li><a href="{!! url('dashboard/account_manage') !!}" class="g_pwicon_t3 "><span>帳號設定</span></a></li>
                    </div>
                    <div class="zhapian vvip_hg">

                        <div class="wlujing">
                            <img src="/new/images/dd.png"><span><a href="">升级付费</a></span><font>-</font>
                            <img src="/new/images/dd.png"><span><a href="">VVIP專區</a></span><font>-</font>
                            <img src="/new/images/dd.png"><span><a href="">VVIP專屬功能</a></span>
                        </div>

                        <div class="vip_bt xq_v_bt">VVIP專屬功能</div>
                        <div class="l_zgongn">
                            <div class="lu_l"><a href="{!! url('dashboard/vvipExclusive') !!}"><img src="/new/images/zs_1.png" >VVIP功能特色說明</a></div>
                            <div class="lu_l"><img src="/new/images/zs_2.png" >選拔甜心活動</div>
                            <div class="lu_l"><img src="/new/images/zs_3.png" >一對一邀請</div>
                        </div>


                    </div>

                </div>
            </div>
        </div>
    </div>


@stop

@section('javascript')
    <link rel="stylesheet" href="/new/css/swiper.min1.css">
    <style>
        .swiper {width:400px; margin: 0 auto; height:auto; position: relative;z-index: 1; margin-top:10px;border-radius: 5px; overflow: hidden;}
        .swiper-container {width:100%;}
        .swiper-slide{height:10px}
        .swiper-slide-active { height:auto}

        @media (max-width:480px){
            .swiper {width:300px;height:auto;}
        }
        @media (max-width:375px){
            .swiper {width:290px;height:auto;}
        }

        @media (max-width:360px){
            .swiper { width:270px;height:auto;}
        }
        @media (max-width:330px){
            .swiper { width:240px;height:auto;}
        }
    </style>
    <!-- Swiper JS -->
    <script src="/new/js/swiper.min2.js"></script>
    <!-- Initialize Swiper -->
    <script>

        var swiper = new Swiper('.swiper-container', {
            pagination: '.swiper-pagination',
            paginationType: 'fraction',

            slidesPerView: 1,
            paginationClickable: true,
            spaceBetween: 30,
            keyboardControl: true,
        });

        $('.new_viphig').hide();
        $('.page01').show();

        $('.tour').click(function() {
            var id = $(this).attr('id');
            $('.new_viphig').hide();
            $('.' + id).show();
            $('html, body').animate({ scrollTop: 0 }, 'slow');
        });

    </script>
@stop
