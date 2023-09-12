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

                    <div class="new_viphig">
                        {{--
                        <div class="wlujing">
                            <img src="/new/images/dd.png"><span><a href="">升级付费</a></span><font>-</font>
                            <img src="/new/images/dd.png"><span><a href="">VVIP專區</a></span><font>-</font>
                            <img src="/new/images/dd.png"><span><a href="">VVIP專屬功能</a></span>
                        </div>
                        --}}
                        <div class="vip_bt">VVIP功能特色說明</div>

                        <div class="vvip_page page01">
                            <div class="quxiao_qb">
                                <h2 class="qxfa_title">a: 搜索頁特色介紹</h2>
                                <div class="qx_fbt">您的資料將置頂於每個搜尋頁面上，並有特殊顏色，與其他會員會有明顯區隔</div>
                                <div class="qx_imgpic">
                                    <img src="/new/images/fangan_qx_1.png">
                                </div>
                            </div>
                            <a href="javascript:void(0);" class="qxz_fenye tour" id="page02">下一頁</a>
                        </div>

                        <div class="vvip_page page02" style="display: none;">
                            <div class="quxiao_qb">
                                <h2 class="qxfa_title">b: 收件夾特色介紹</h2>
                                <div class="qx_fbt">女會員有單獨的 VVIP 收件夾，並置頂於收件夾第一列</div>
                                <div class="qx_imgpic">
                                    <img src="/new/images/fangan_qx_2a.png">
                                </div>
                            </div>
                            <div class="fenye mabot30">
                                <a href="javascript:void(0);" class="tour" id="page01">上一頁</a>
                                <span class="new_page">2/5</span>
                                <a href="javascript:void(0);" class="tour" id="page03">下一頁</a>
                            </div>
                        </div>

                        <div class="vvip_page page03" style="display: none;">
                            <div class="quxiao_qb">
                                <h2 class="qxfa_title">c: 特別會員頁</h2>
                                <div class="qx_fbt">您將有與其他會員不同的特殊頁面。
{{--                                    <b>您將有與其他會員不同的特殊頁面。</b>--}}
{{--                                    <h2 class="matop10">1：有著粉色的背景色；</h2>--}}
{{--                                    <h2>2：並在暱稱上方有明顯的 VVIP Tag ；</h2>--}}
{{--                                    <h2>3：當女會員點了Tag後會顯示：您是站方特別認證的高資產、高收入VVIP會員，更加深了優質女會員對您的青睞。</h2>--}}
                                </div>
                                <div class="qx_imgpic">
                                    <img src="/new/images/fangan_qx_3.png">
                                </div>
                            </div>
                            <div class="fenye mabot30">
                                <a href="javascript:void(0);" class="tour" id="page02">上一頁</a>
                                <span class="new_page">3/5</span>
                                <a href="javascript:void(0);" class="tour" id="page07">下一頁</a>
                            </div>
                        </div>

                        {{--
                        <div class="vvip_page page04" style="display: none;">
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
                            </div>
                            <div class="fenye mabot30">
                                <a href="javascript:void(0);" class="tour" id="page03">上一頁</a>
                                <span class="new_page">4/8</span>
                                <a href="javascript:void(0);" class="tour" id="page05">下一頁</a>
                            </div>
                        </div>
                        --}}

                        <!--
                        <div class="vvip_page page05" style="display: none;">
                            <div class="quxiao_qb">
                                <h2 class="qxfa_title">d: 專屬客服</h2>
                                <div class="qx_fbt">
                                    vvip 將有最快速的客服服務，現場客服無法處理的問題會快速直達站長。
                                </div>
                                <div class="qx_imgpic matop15">
                                    <img src="/new/images/fangan_qx_14.png">
                                </div>
                            </div>

{{--                            <div class="quxiao_qb">--}}
{{--                                <h2 class="qxfa_title">e: 優先預覽「審查期」的新進女會員</h2>--}}
{{--                                <div class="qx_fbt">--}}
{{--                                    1: 新進女會員，註冊完 12 小時屬於審查期，審查期只有 VVIP 有辦法看到，並可與其互動。--}}
{{--                                </div>--}}
{{--                                <div class="qx_imgpic matop15">--}}
{{--                                    <img src="/new/images/fangan_qx_7.png">--}}
{{--                                </div>--}}
{{--                                <div class="qx_fbt matop15">2: 新進女會員，註冊完 12~72小時，所有人都可以看到。普通會員無法發訊息給女會員(但可以回訊)。</div>--}}
{{--                                <div class="qx_fbt matop15">普會會員要發訊息會收到提示「Baby 是新進甜心，基於保護甜心立場，以 VIP 會員優先。普通會員在 X 小時後可以發訊給 Baby」</div>--}}
{{--                            </div>--}}

                            <div class="fenye mabot30">
                                <a href="javascript:void(0);" class="tour" id="page03">上一頁</a>
                                <span class="new_page">4/7</span>
                                <a href="javascript:void(0);" class="tour" id="page06">下一頁</a>
                            </div>
                        </div>

                        <div class="vvip_page page06" style="display: none;">
                            <div class="quxiao_qb">
                                <h2 class="qxfa_title">e: 站方認證高級女會員</h2>
                                <div class="qx_fbt">
                                    站方為您把關，以真人視訊方式進行驗證
                                </div>
                                <div class="qx_imgpic matop15">
                                    <img src="/new/images/fangan_qx_15.png">
                                </div>
                            </div>

{{--                            <div class="quxiao_qb">--}}
{{--                                <h2 class="qxfa_title">f: 享有1對1邀請功能</h2>--}}
{{--                                <div class="qx_fbt">當您看到心儀的女會員，1v1邀請可以讓你成為唯一跟他對談的人!當您看到心儀的女會員，對他發出一對一邀請，三天內她將不再收到其他新的男會員的訊息。</div>--}}
{{--                                <div class="qx_lunbo">--}}
{{--                                    <div class="swiper">--}}
{{--                                        <div class="swiper-container">--}}
{{--                                            <div class="swiper-wrapper">--}}
{{--                                                <div class="swiper-slide">--}}
{{--                                                    <div class="qx_xzpic">--}}
{{--                                                        <div class="qx_lunbo_font"><b>1：</b>當您升級為VVIP後，女會員的基本資料頁上會有1對1邀請icon。</div>--}}
{{--                                                        <img src="/new/images/fangan_qx_8.png">--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <div class="swiper-slide">--}}
{{--                                                    <div class="qx_xzpic">--}}
{{--                                                        <div class="qx_lunbo_font"><b>2：</b>當您點下去以後，會跳出一對一邀請說明，如下圖</div>--}}
{{--                                                        <img src="/new/images/fangan_qx_9.png">--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <div class="swiper-slide">--}}
{{--                                                    <div class="qx_xzpic">--}}
{{--                                                        <div class="qx_lunbo_font"><b>3：</b>一對一邀請發出後，雙方的聊天詳情中，各自收到一封來自站方的系統信，如下圖</div>--}}
{{--                                                        <img src="/new/images/fangan_qx_11.png">--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <div class="swiper-slide">--}}
{{--                                                    <div class="qx_xzpic">--}}
{{--                                                        <div class="qx_lunbo_font"><b>4：</b>一對一邀請發出後，雙方的聊天詳情中，各自收到一封來自站方的系統信，如下圖</div>--}}
{{--                                                        <img src="/new/images/fangan_qx_10.png">--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <div class="swiper-slide">--}}
{{--                                                    <div class="qx_xzpic">--}}
{{--                                                        <div class="qx_lunbo_font"><b>5：</b>三天內女方將不會收到新的男會員訊息。保障您優先的權力。</div>--}}
{{--                                                        <img src="/new/images/fangan_qx_12.png">--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <div class="swiper-slide">--}}
{{--                                                    <div class="qx_xzpic">--}}
{{--                                                        <div class="qx_lunbo_font"><b>6：</b>三天後女方一上線，會立即確認包養關係成功與否。如果成功，則雙方進入帳號交付模式；如果失敗，則恢復原先隱藏女方的所有訊息。</div>--}}
{{--                                                        <img src="/new/images/fangan_qx_13.png">--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="swiper-pagination" style="margin: 0 auto;"></div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

                            <div class="fenye mabot30">
                                <a href="javascript:void(0);" class="tour" id="page05">上一頁</a>
                                <span class="new_page">5/7</span>
                                <a href="javascript:void(0);" class="tour" id="page07">下一頁</a>
                            </div>
                        </div>
                        -->

                        <div class="vvip_page page07" style="display: none;">
                            <div class="quxiao_qb">
                                <h2 class="qxfa_title">d: 名人專屬</h2>
                                <div class="qx_fbt">
                                    站方認證的特殊/具知名度女會員，將依照女會員的意願，優先開放給 vvip
                                </div>
                                <div class="qx_imgpic matop15">
                                    <img src="/new/images/fangan_qx_16.png">
                                </div>
                            </div>

{{--                            <div class="quxiao_qb">--}}
{{--                                <h2 class="qxfa_title">g:專屬客服回答問題，您不會在收到罐頭回應!</h2>--}}
{{--                                <div class="qx_fbt">--}}
{{--                                    當您成為VVIP後，會擁有專屬的客戶服務，幫您直接過濾掉代聊、八大等訊息。--}}
{{--                                </div>--}}
{{--                                <div class="qx_imgpic matop15">--}}
{{--                                    <img src="/new/images/fangan_qx_14.png">--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="fenye mabot30">
                                <a href="javascript:void(0);" class="tour" id="page03">上一頁</a>
                                <span class="new_page">4/5</span>
                                <a href="javascript:void(0);" class="tour" id="page08">下一頁</a>
                            </div>
                        </div>

                        <div class="vvip_page page08" style="display: none;">
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
                            <a href="javascript:void(0);" class="qxz_fenye tour" id="page07">上一頁</a>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>


@stop

@section('javascript')
    <script>
        $('.page01').show();

        $('.tour').click(function() {
            var id = $(this).attr('id');
            $('.vvip_page').hide();
            $('.' + id).show();
            $('html, body').animate({ scrollTop: 0 }, 'slow');
        });

    </script>
@stop
