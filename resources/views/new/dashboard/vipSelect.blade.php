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
                    <div class="awlist">
                        <li>
                            <a href="{!! url('dashboard/new_vip') !!}" class="aw_hdtab">
                                <img src="/new/images/VIPicon_01.png">
                                <h2>VIP</h2>
                            </a>
                        </li>
                        <li>
                            <a href="{!! url('dashboard/valueAddedHideOnline') !!}"class="aw_hdtab">
                                <img src="/new/images/VIPicon_02.png">
                                <h2>付費隱藏</h2>
                            </a>
                        </li>
                    </div>

                </div>
            </div>
        </div>
    </div>

@stop

@section('javascript')
@stop
