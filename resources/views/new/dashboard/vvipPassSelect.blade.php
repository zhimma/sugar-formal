@extends('new.layouts.website')

@section('app-content')
    <style>
        .xh_left50>a{
            color:#ea748f;
        }
    </style>
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
                            <img src="/new/images/dd.png"><span><a href="">升級付費</a></span><font>-</font>
                            <img src="/new/images/dd.png"><span><a href="">VVIP專區</a></span>
                        </div>
                        <div class="vip_bt xq_v_bt">VVIP專區</div>
                        <div class="zhuanxiangy">
                                    <div class="xh_left50 xh_liner xh_liner_l xhp">
                                        <a @if(view()->shared('valueAddedServices')['VVIP'] == 1) onclick="vvip_alert()" @elseif(($user->passVVIP || $user->cancelVVIP() || $user->applyingVVIP || $user->applyingVVIP_getDeadline() != 0) && !$user->isVVIP()) href="{!! url('dashboard/vvipPassPay') !!}" @endif>
                                        <div class="xh_span"><img src="/new/images/quxiao_3.png"></div>
                                        <h2>VVIP付費專區</h2>
                                        </a>
                                    </div>
                                    <div class="xh_left50 xh_liner_l xhp">
                                        <a href="{!! url('dashboard/vvipInfo') !!}">
                                        <div class="xh_span"><img src="/new/images/quxiao_4.png"></div>
                                        <h2>VVIP必填資料</h2>
                                        </a>
                                    </div>

                                    <div class="xh_left50 xh_liner xhpt">
                                        <a href="{!! url('dashboard/vvipExclusive') !!}">
                                        <div class="xh_span"><img src="/new/images/quxiao_2.png"></div>
                                        <h2>VVIP專屬功能</h2>
                                        </a>
                                    </div>
                                    <div class="xh_left50 xhpt">
                                        <a @if(view()->shared('valueAddedServices')['VVIP'] == 1) href="{!! url('dashboard/vvipCancel') !!}" @else onclick="vvip_cancel_alert()"@endif>
                                        <div class="xh_span"><img src="/new/images/quxiao_1.png"></div>
                                        <h2>取消VVIP</h2>
                                        </a>
                                    </div>
                        </div>



{{--                            @if($user->applyingVVIP_getDeadline() != 0)--}}
{{--                                <style>--}}
{{--                                    .xh_left50{--}}
{{--                                        width: 33.3%;--}}
{{--                                    }--}}
{{--                                </style>--}}
{{--                                <div class="xh_left50 xh_liner">--}}
{{--                                    <a href="{!! url('/dashboard/vvipSelectA#refill') !!}">--}}
{{--                                        <div class="xh_span"><img src="/new/images/quxiao_1.png"></div>--}}
{{--                                        <h2>補證明文件</h2>--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                            @endif    --}}
{{--                            <div class="xh_left50 xh_liner">--}}
{{--                                @if(($user->passVVIP || $user->cancelVVIP() || $user->applyingVVIP || $user->applyingVVIP_getDeadline() != 0) && !$user->isVVIP())--}}
{{--                                <a href="{!! url('dashboard/vvipPassPay') !!}">--}}
{{--                                    <div class="xh_span">--}}
{{--                                        <img src="/new/images/quxiao_3.png">--}}
{{--                                    </div>--}}
{{--                                    <h2>VVIP付費專區</h2>--}}
{{--                                </a>--}}
{{--                                @elseif($user->isVVIP())--}}
{{--                                <a href="{!! url('dashboard/vvipCancel') !!}">--}}
{{--                                    <div class="xh_span"><img src="/new/images/quxiao_1.png"></div>--}}
{{--                                    <h2>取消VVIP</h2>--}}
{{--                                </a>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                            <div class="xh_left50 right">--}}
{{--                                <a href="{!! url('dashboard/vvipExclusive') !!}">--}}
{{--                                <div class="xh_span"><img src="/new/images/quxiao_2.png"></div>--}}
{{--                                <h2>VVIP專屬功能</h2>--}}
{{--                                </a>--}}
{{--                            </div>--}}

                        </div>
{{--                        @if($user->applyingVVIP_getDeadline() != 0)--}}
{{--                            <div class="beiyongjin">  您的申請尚在審核中，當前需補齊證明文件。<br><a href="/dashboard/vvipSelectA#refill" class="red"> [請點此前往上傳資料]</a></div>--}}
{{--                        @endif--}}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('javascript')

    <script>

        function vvip_alert(){
            c5('您已是VVIP付費狀態');
        }

        function vvip_cancel_alert(){
            c5('您尚未成為VVIP付費會員');
        }
    </script>

@stop
