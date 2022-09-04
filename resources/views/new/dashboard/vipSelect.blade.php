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
                    <div class="awlist">
                        <li>
                            @if($user->isVVIP())
                                <a class="aw_hdtab cantVIP">
                                    <img src="/new/images/VIPicon_01.png">
                                    <h2>VIP</h2>
                                </a>
                            @else
                                <a href="{!! url('dashboard/new_vip') !!}" class="aw_hdtab">
                                    <img src="/new/images/VIPicon_01.png">
                                    <h2>VIP</h2>
                                </a>
                            @endif
                        </li>
                        <li>
                            <a href="{!! url('dashboard/valueAddedHideOnline') !!}"class="aw_hdtab">
                                <img src="/new/images/VIPicon_02.png">
                                <h2>付費隱藏<span>上線資訊</span></h2>
                            </a>
                        </li>
                        <li>
                            @if(($user->applyingVVIP()|| $user->applyingVVIP_getDeadline() != 0) && $user->engroup==1 )
                                <a class="aw_hdtab" href="{{ url('/dashboard/vvipPassSelect') }}">
                                    <img src="/new/images/VIPicon_03.png">
                                    <h2>VVIP升級</h2>
                                </a>
                            @elseif($user->engroup==1)
                                <a class="aw_hdtab" href="@if($user->passVVIP()){{ url('/dashboard/vvipPassSelect') }}@else{{ url('/dashboard/vvipSelect') }}@endif">
                                    <img src="/new/images/VIPicon_03.png">
                                    <h2>@if($user->isVVIP()) VVIP專區 @else VVIP升級 @endif</h2>
                                </a>
                            @else
                                <a class="aw_hdtab aw_hdtab_h cantVVIP">
                                    <img src="/new/images/VIPicon_03-h.png">
                                    <h2>VVIP升級</h2>
                                </a>
                            @endif
                        </li>
                    </div>

                </div>
            </div>
        </div>
    </div>

@stop

@section('javascript')
    <script>

        $('.cantVIP').on('click',function () {
            c5('您已經是最高等級VVIP');
        });

        @if($user->engroup == 2)
        $('.cantVVIP').on('click',function () {
            c5('此為男會員的專屬升級方案');
        });
        @endif

    </script>
@stop
