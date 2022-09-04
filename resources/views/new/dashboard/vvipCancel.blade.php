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
{{--                        <li><a href="{!! url('dashboard/vipSelect') !!}" class="g_pwicon_t4 g_hicon4"><span>升級付費</span></a></li>--}}
                    </div>
                    <div class="zhapian vvip_hg">

                        <div class="vip_bt">取消VVIP</div>
                        <div class="de_input vv_n">
                            @if(isset($vvipData) && $vvipData->expiry != '0000-00-00 00:00:00')
                                <div class="fi_text">
                                    <h3>您已成功取消VVIP，下個月起將不再繼續扣款，目前的付費功能權限可以維持到 {{substr($vvipData->expiry, 0 ,10)}}</h3>
                                </div>
                            @elseif ($user->valueAddedServiceStatus('VVIP') == 1)
                            <form class="m-login__form m-form" method="POST" action="{{ url('/dashboard/cancelValueAddedService') }}">
                                {!! csrf_field() !!}
                            <div class="de_input01 dlmarbot"><div class="de_img"><img src="/new/images/lo_03.png"></div>
                                <input name="email" type="text" class="d_input" placeholder="請輸入您的帳號">
                            </div>
                            <div class="de_input01 dlmarbot"><div class="de_img"><img src="/new/images/lo_11.png"></div>
                                <input name="password" type="password" class="d_input" placeholder="請輸入您的密碼">
                            </div>
                                <input type="hidden" name="service_name" value="VVIP">
                            <button class="dlbut" type="submit" style="border-style: none;">確認</button>
                            </form>
                            @else
                            您尚未有付費狀態
                            @endif
                        </div>
                        <div class="bo_dx"></div>

                        @if($reserve_fund)
                        <div class="beiyongjin"><img src="/new/images/beiyongjin.png">剩餘入會費：<span>{{$reserve_fund}}元</span></div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('javascript')
    <script>
        $(document).ready(function() {
            @if(Session::has('message'))
            c5("{{Session::get('message')}}");
            <?php session()->forget('message');?>
            @endif
        });
    </script>
@stop
