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
                        <li><a href="/dashboard/viewuser/{{$user->id}}" class="g_pwicon_t5 "><span>自我預覽</span></a></li>
                        <li><a href="{!! url('dashboard') !!}" class="g_pwicon_t "><span>基本資料</span></a></li>
                        <li><a href="{!! url('dashboard_img') !!}" class="g_pwicon_t2"><span>照片管理</span></a></li>
                        <li><a href="{!! url('dashboard/account_manage') !!}" class="g_pwicon_t3 "><span>帳號設定</span></a></li>
{{--                        <li><a href="{!! url('dashboard/vipSelect') !!}" class="g_pwicon_t4 g_hicon4"><span>升級付費</span></a></li>--}}
                    </div>

                    <div class="new_viphig">
                        <div class="wlujing">
                            <img src="/new/images/dd.png"><span><a href="">升级付费</a></span><font>-</font>
                            <img src="/new/images/dd.png"><span><a href="">VVIP專區</a></span><font>-</font>
                            <img src="/new/images/dd.png"><span><a href="">VVIP專屬功能</a></span>
                        </div>
                        <div class="vip_bt">VVIP功能特色說明</div>

                        <div class="vvip_page vvip_page01">
                            <div class="quxiao_qb">
                                <h2 class="qxfa_title">a: 搜索頁特色介紹</h2>
                                <div class="qx_fbt">您的資料將置頂於每個搜尋頁面上，並有特殊顏色，與其他會員會有明顯區隔</div>
                                <div class="qx_imgpic">
                                    <img src="/new/images/fangan_qx_1.png">
                                </div>

                            </div>
                            <a onclick="goToVvipPage('vvip_page02')" class="qxz_fenye">下一頁</a>
                        </div>

                        <div class="vvip_page vvip_page02" style="display: none;">
                            <div class="quxiao_qb">
                                <h2 class="qxfa_title">b: 收件夾特色介紹</h2>
                                <div class="qx_fbt">
                                    女會員有單獨的 VVIP 收件夾，並置頂於收件夾第一列</div>
                                <div class="qx_imgpic">
                                    <img src="/new/images/fangan_qx_2a.png">
                                </div>

                            </div>
                            <div class="fenye mabot30">
                                <a onclick="goToVvipPage('vvip_page01')">上一頁</a>
                                <span class="new_page">2/8</span>
                                <a onclick="goToVvipPage('vvip_page03')">下一頁</a>
                            </div>
                        </div>

                        <div class="vvip_page vvip_page03" style="display: none;">
                            <div class="quxiao_qb">
                                <h2 class="qxfa_title">c: 特別會員頁</h2>
                                <div class="qx_fbt">
                                    您將有與其他會員不同的特殊頁面。
                                    <!-- <b>當女會員點入您的個人資料頁時，將有別於其他會員</b>
                                    <h2 class="matop10">1：有著粉色的背景色；</h2>
                                    <h2>2：並在暱稱上方有明顯的 VVIP Tag ；</h2>
                                    <h2>3：當女會員點了Tag後會顯示：您是站方特別認證的高資產、高收入VVIP會員，更加深了優質女會員對您的青睞。</h2> -->
                                </div>
                                <div class="qx_imgpic">
                                    <img src="/new/images/fangan_qx_3.png">
                                </div>

                            </div>


                            <div class="fenye mabot30">
                                <a onclick="goToVvipPage('vvip_page02')">上一頁</a>
                                <span class="new_page">3/8</span>
                                <a onclick="goToVvipPage('vvip_page04')">下一頁</a>
                            </div>
                        </div>

                        <div class="vvip_page vvip_page04" style="display: none;">
                            <div class="quxiao_qb">
                                <h2 class="qxfa_title">d: VVIP訊息專屬特別提示</h2>
                                <div class="qx_fbt">VVIP每天可以選擇一則訊息，這則訊息會在女會員專屬頁面提示為「優先看」</div>
                                <div class="qx_lunbo">
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
                                    <div class="swiper">
                                        <div class="swiper-container">
                                            <div class="swiper-wrapper">
                                                <div class="swiper-slide">
                                                    <div class="qx_xzpic">
                                                        <div class="qx_lunbo_font"><b>1：</b>VVIP每天可以選擇一則Baby訊息</div>
                                                        <img src="/new/images/fangan_qx_4.png">
                                                    </div>
                                                </div>
                                                <div class="swiper-slide">
                                                    <div class="qx_xzpic">
                                                        <div class="qx_lunbo_font"><b>2：</b>這則訊息會在女會員的會員專屬頁上提示。”您有一封來自 VVIP  Daddy 的訊息”</div>
                                                        <img src="/new/images/fangan_qx_5.png">
                                                    </div>
                                                </div>
                                                <div class="swiper-slide">
                                                    <div class="qx_xzpic">
                                                        <div class="qx_lunbo_font"><b>3：</b>女會員點下訊息通知後，會提示如下圖</div>
                                                        <img src="/new/images/fangan_qx_6.png">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Add Pagination -->
                                        </div>
                                        <div class="swiper-pagination" style="margin: 0 auto;"></div>
                                    </div>

                                </div>
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
                                </script>
                                <!-- <script>
                                        var swiper = new Swiper('.swiper-container', {
                                          spaceBetween: 30,
                                          pagination: {
                                            el: '.swiper-pagination',
                                            clickable: true,
                                          },
                                        });
                                 </script> -->
                            </div>
                            <div class="fenye mabot30">
                                <a onclick="goToVvipPage('vvip_page03')">上一頁</a>
                                <span class="new_page">4/8</span>
                                <a onclick="goToVvipPage('vvip_page05')">下一頁</a>
                            </div>
                        </div>
                        <div class="vvip_page vvip_page05" style="display: none;">
                            <div class="quxiao_qb">
                                <h2 class="qxfa_title">e: 專屬客服</h2>
                                <div class="qx_fbt">
                                    vvip 將有最快速的客服服務，現場客服無法處理的問題會快速直達站長。
                                </div>
                                <div class="qx_imgpic matop15">
                                    <img src="/new/images/fangan_qx_14.png">
                                </div>
                            </div>

                            <div class="fenye mabot30">
                                <a onclick="goToVvipPage('vvip_page04')">上一頁</a>
                                <span class="new_page">5/8</span>
                                <a onclick="goToVvipPage('vvip_page06')">下一頁</a>
                            </div>
                        </div>

                        <div class="vvip_page vvip_page06" style="display: none;">
                            <div class="quxiao_qb">
                                <h2 class="qxfa_title">f: 站方認證高級女會員</h2>
                                <div class="qx_fbt">
                                    站方為您把關，以真人視訊方式進行驗證
                                </div>
                                <div class="qx_imgpic matop15">
                                    <img src="/new/images/fangan_qx_15.png">
                                </div>
                            </div>
                            <div class="fenye mabot30">
                                <a onclick="goToVvipPage('vvip_page05')">上一頁</a>
                                <span class="new_page">6/8</span>
                                <a onclick="goToVvipPage('vvip_page07')">下一頁</a>
                            </div>
                        </div>

                        <div class="vvip_page vvip_page07" style="display: none;">
                            <div class="quxiao_qb">
                                <h2 class="qxfa_title">g: 名人專屬</h2>
                                <div class="qx_fbt">
                                    站方認證的特殊/具知名度女會員，將依照女會員的意願，優先開放給 vvip
                                </div>
                                <div class="qx_imgpic matop15">
                                    <img src="/new/images/fangan_qx_16.png">
                                </div>
                            </div>
                            <div class="fenye mabot30">
                                <a onclick="goToVvipPage('vvip_page06')">上一頁</a>
                                <span class="new_page">7/8</span>
                                <a onclick="goToVvipPage('vvip_page08')">下一頁</a>
                            </div>
                        </div>

                        <div class="vvip_page vvip_page08" style="display: none;">
                            <div class="quxiao_qb">
                                <!-- <h2 class="qxfa_title">i:名人專屬</h2> -->
                                <div class="qx_fbt">
                                    <div style="width: 100%; text-align: center; display: table; padding-top:30px;  font-size: 22px;">尚有其他功能開發中</div>
                                    <div style="width: 100%; text-align: center; display: table; font-size:30px; color: #77dbd8;line-height:40px; font-weight: bold;"> coming soon</div>

                                </div>
                                <div class="qx_imgpic matop15">
                                    <img src="/new/images/fangan_8x.png">
                                </div>
                            </div>
                            <a onclick="goToVvipPage('vvip_page07')" class="qxz_fenye">上一頁</a>
                        </div>
                    </div>

                    <div class="vv_tit"><img src="/new/images/ggx.png">選擇方案<img src="/new/images/ggx.png"></div>
                    <div class="vv_sez ga_w">
                        <li>
                            <a @if($user->canVVIP()) href="{{ url('/dashboard/vvipSelectA') }}" @else class="cantVVIP" @endif>
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
{{--                    <div class="zhapian g_pnr">--}}
{{--                        <div class="vip_title vip_title_f"><img src="/new/images/v1_01.png">VVIP權益</div>--}}

{{--                        <style>--}}
{{--                            .swiper {width:400px; margin: 0 auto; height:auto; position: relative;z-index: 1; margin-top:20px; border: #dcdcdc 1px solid; border-radius: 5px; overflow: hidden; }--}}
{{--                            .swiper-container {width:100%;height:auto;}--}}
{{--                            .swiper-slide {display: -webkit-box;display: -ms-flexbox;display: -webkit-flex;display: flex;-webkit-box-pack: center;--}}
{{--                                -ms-flex-pack: center;-webkit-justify-content: center;justify-content: center;-webkit-box-align: center;-ms-flex-align: center;-webkit-align-items: center;align-items: center;}--}}

{{--                            @media (max-width:480px){--}}
{{--                                .swiper { width:350px;height:auto;}--}}
{{--                            }--}}

{{--                            @media (max-width:360px){--}}
{{--                                .swiper { width:300px;height:auto;}--}}
{{--                            }--}}
{{--                        </style>--}}
{{--                        <div class="swiper">--}}
{{--                            <div class="swiper-container">--}}
{{--                                <div class="swiper-wrapper">--}}
{{--                                    <div class="swiper-slide">--}}
{{--                                        <div class="m_xzpic"><img src="/new/images/fangan_1.png"></div>--}}
{{--                                        <div class="ma_botfanr"><h2><b>a: 搜尋頁置頂，並有特殊背景色</b></h2></div>--}}
{{--                                    </div>--}}
{{--                                    <div class="swiper-slide">--}}
{{--                                        <div class="m_xzpic"><img src="/new/images/fangan_2.png"></div>--}}
{{--                                        <div class="ma_botfanr"><h2><b>b: 女會員有單獨 VVIP 收件夾</b></h2></div>--}}
{{--                                    </div>--}}
{{--                                    <div class="swiper-slide">--}}
{{--                                        <div class="m_xzpic"><img src="/new/images/fangan_3.png"></div>--}}
{{--                                        <div class="ma_botfanr"><h2><b>c: VVIP 訊息專屬特別提示給女會員</b></h2></div>--}}
{{--                                    </div>--}}
{{--                                    <div class="swiper-slide">--}}
{{--                                        <div class="m_xzpic"><img src="/new/images/fangan_4.png"></div>--}}
{{--                                        <div class="ma_botfanr"><h2><b>d: 基本資料頁會有 VVIP Tag 以及說明，與特殊背景色</b></h2></div>--}}
{{--                                    </div>--}}
{{--                                    <div class="swiper-slide">--}}
{{--                                        <div class="m_xzpic"><img src="/new/images/fangan_5.png"></div>--}}
{{--                                        <div class="ma_botfanr"><h2 class="ma_facent"><b>e: 優先預覽「審查期」的新進女會員。</b>--}}
{{--                                                <p>(剛註冊完 12 小時的女會員屬於審查期，其他會員都無法看到相關資料)</p></h2></div>--}}
{{--                                    </div>--}}
{{--                                    <div class="swiper-slide">--}}
{{--                                        <div class="m_xzpic"><img src="/new/images/fangan_6.png"></div>--}}
{{--                                        <div class="ma_botfanr"><h2 class="ma_facent"><b>f: 享有1 對 1 邀請功能。</b>--}}
{{--                                                <p>當您看到心儀的女會員，1v1邀請可以讓你成為唯一跟他對談的人!當您看到心儀的女會員，對他發出一對一邀請，三天內她將不再收到其他新的男會員的訊息。</p></h2></div>--}}
{{--                                    </div>--}}
{{--                                    <div class="swiper-slide">--}}
{{--                                        <div class="m_xzpic"><img src="/new/images/fangan_7.png"></div>--}}
{{--                                        <div class="ma_botfanr"><h2><b>g:專屬客服回答問題，您不會在收到罐頭回應!</b></h2></div>--}}
{{--                                    </div>--}}
{{--                                    <div class="swiper-slide">--}}
{{--                                        <div class="m_xzpic"><img src="/new/images/fangan_8.png"></div>--}}
{{--                                        <div class="ma_botfanr"><h2 class="ma_facent">尚有其他功能開發中</h2><h3>coming soon</h3></div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <!-- Add Pagination -->--}}
{{--                                <div class="swiper-pagination"></div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <!-- Swiper JS -->--}}
{{--                        <link rel="stylesheet" href="/new/css/fa_swiper.min.css">--}}
{{--                        <script src="/new/js/fa_swiper.min.js"></script>--}}
{{--                        <!-- Initialize Swiper -->--}}
{{--                        <script>--}}
{{--                            var swiper = new Swiper('.swiper-container', {--}}
{{--                                spaceBetween: 30,--}}
{{--                                pagination: {--}}
{{--                                    el: '.swiper-pagination',--}}
{{--                                    clickable: true,--}}
{{--                                },--}}
{{--                            });--}}
{{--                        </script>--}}

{{--                        <div class="vv_tit"><img src="/new/images/ggx.png">選擇方案<img src="/new/images/ggx.png"></div>--}}
{{--                        <div class="vv_sez">--}}
{{--                            <li>--}}
{{--                                <a href="{{ url('/dashboard/vvipSelectA') }}">--}}
{{--                                    <h2>A方案</h2>--}}
{{--                                    <img src="/new/images/v1_02.png" class="vvpimg">--}}
{{--                                    <h3>證明文件</h3>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a href="{{ url('/dashboard/vvipSelectB') }}">--}}
{{--                                    <h2>B方案</h2>--}}
{{--                                    <img src="/new/images/v1_03.png" class="vvpimg">--}}
{{--                                    <h3>提供保證金</h3>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </div>--}}
{{--                    </div>--}}

                </div>
            </div>
        </div>
    </div>


@stop

@section('javascript')
    <script>


        function goToVvipPage(pageClass){
           $('.vvip_page').hide();
           $('.'+pageClass).show();
           return true;
        }


        @if(!$user->is6MonthsVip() && !$user->is12MonthsVip())
        $('.cantVVIP').on('click',function () {
            c5('您還需要連續半年的信用卡付費VIP，或累計一年以上的VIP才可申請');
        });
        @elseif($user->isEverWarnedAndBanned())
        $('.cantVVIP').on('click',function () {
            c5('您曾受過警示/封鎖之處分');
        });
        @endif
{{--        @if(!$user->is12MonthsVip())--}}
{{--        $('.cantVVIP').on('click',function () {--}}
{{--            c5('您還需要連續半年的信用卡付費VIP，或累計一年以上的VIP才可申請');--}}
{{--        });--}}


    </script>
@stop
