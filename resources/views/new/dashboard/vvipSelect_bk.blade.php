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
                        <li><a href="{!! url('dashboard') !!}" class="g_pwicon_t "><span>基本資料</span></a></li>
                        <li><a href="{!! url('dashboard_img') !!}" class="g_pwicon_t2"><span>照片管理</span></a></li>
                        <li><a href="{!! url('dashboard/account_manage') !!}" class="g_pwicon_t3 "><span>帳號設定</span></a></li>
                        <li><a href="{!! url('dashboard/vipSelect') !!}" class="g_pwicon_t4 g_hicon4"><span>升級付費</span></a></li>
                    </div>
                    <div class="zhapian g_pnr">
                        <div class="vip_title vip_title_f"><img src="/new/images/v1_01.png">VVIP權益</div>

                        <style>
                            .swiper {width:400px; margin: 0 auto; height:auto; position: relative;z-index: 1; margin-top:20px; border: #dcdcdc 1px solid; border-radius: 5px; overflow: hidden; }
                            .swiper-container {width:100%;height:auto;}
                            .swiper-slide {display: -webkit-box;display: -ms-flexbox;display: -webkit-flex;display: flex;-webkit-box-pack: center;
                                -ms-flex-pack: center;-webkit-justify-content: center;justify-content: center;-webkit-box-align: center;-ms-flex-align: center;-webkit-align-items: center;align-items: center;}

                            @media (max-width:480px){
                                .swiper { width:350px;height:auto;}
                            }

                            @media (max-width:360px){
                                .swiper { width:300px;height:auto;}
                            }
                        </style>
                        <div class="swiper">
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="m_xzpic"><img src="/new/images/fangan_1.png"></div>
                                        <div class="ma_botfanr"><h2><b>a: 搜尋頁置頂，並有特殊背景色</b></h2></div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="m_xzpic"><img src="/new/images/fangan_2.png"></div>
                                        <div class="ma_botfanr"><h2><b>b: 女會員有單獨 VVIP 收件夾</b></h2></div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="m_xzpic"><img src="/new/images/fangan_3.png"></div>
                                        <div class="ma_botfanr"><h2><b>c: VVIP 訊息專屬特別提示給女會員</b></h2></div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="m_xzpic"><img src="/new/images/fangan_4.png"></div>
                                        <div class="ma_botfanr"><h2><b>d: 基本資料頁會有 VVIP Tag 以及說明，與特殊背景色</b></h2></div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="m_xzpic"><img src="/new/images/fangan_5.png"></div>
                                        <div class="ma_botfanr"><h2 class="ma_facent"><b>e: 優先預覽「審查期」的新進女會員。</b>
                                                <p>(剛註冊完 12 小時的女會員屬於審查期，其他會員都無法看到相關資料)</p></h2></div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="m_xzpic"><img src="/new/images/fangan_6.png"></div>
                                        <div class="ma_botfanr"><h2 class="ma_facent"><b>f: 享有1 對 1 邀請功能。</b>
                                                <p>當您看到心儀的女會員，1v1邀請可以讓你成為唯一跟他對談的人!當您看到心儀的女會員，對他發出一對一邀請，三天內她將不再收到其他新的男會員的訊息。</p></h2></div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="m_xzpic"><img src="/new/images/fangan_7.png"></div>
                                        <div class="ma_botfanr"><h2><b>g:專屬客服回答問題，您不會在收到罐頭回應!</b></h2></div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="m_xzpic"><img src="/new/images/fangan_8.png"></div>
                                        <div class="ma_botfanr"><h2 class="ma_facent">尚有其他功能開發中</h2><h3>coming soon</h3></div>
                                    </div>
                                </div>
                                <!-- Add Pagination -->
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                        <!-- Swiper JS -->
                        <link rel="stylesheet" href="/new/css/fa_swiper.min.css">
                        <script src="/new/js/fa_swiper.min.js"></script>
                        <!-- Initialize Swiper -->
                        <script>
                            var swiper = new Swiper('.swiper-container', {
                                spaceBetween: 30,
                                pagination: {
                                    el: '.swiper-pagination',
                                    clickable: true,
                                },
                            });
                        </script>

                        <div class="vv_tit"><img src="/new/images/ggx.png">選擇方案<img src="/new/images/ggx.png"></div>
                        <div class="vv_sez">
                            <li>
                                <a href="{{ url('/dashboard/vvipSelectA') }}">
                                    <h2>A方案</h2>
                                    <img src="/new/images/v1_02.png" class="vvpimg">
                                    <h3>證明文件</h3>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/dashboard/vvipSelectB') }}">
                                    <h2>B方案</h2>
                                    <img src="/new/images/v1_03.png" class="vvpimg">
                                    <h3>提供保證金</h3>
                                </a>
                            </li>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


@stop

@section('javascript')
@stop
