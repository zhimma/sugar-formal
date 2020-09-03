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
                        <li><a href="{!! url('/dashboard/account_manage') !!}" class="g_pwicon_t3 g_hicon3"><span>更改帳號</span></a></li>
                        <li><a href="{!! url('/dashboard/vip') !!}" class="g_pwicon_t4"><span>VIP</span></a></li>
                    </div>
                    <div class="gg_zh01 matop-50">
                        <a href="{!! url('/dashboard/account_name_modify') !!}" class="gg_zh_li"><span><img src="/new/images/zh01.png"></span>
                            <font>修改暱稱申請</font>
                        </a>
                        <a href="{!! url('/dashboard/account_gender_change') !!}" class="gg_zh_li"><span><img src="/new/images/zh02.png"></span>
                            <font>變更帳號類型</font>
                        </a>
                        <a href="{!! url('/dashboard/password') !!}" class="gg_zh_li"><span><img src="/new/images/zh03.png"></span>
                            <font>更改密碼</font>
                        </a>
{{--                        @php--}}
{{--                        //檢查是否有申請交付--}}
{{--                        $check_user = DB::table('account_consign')->whereNull('cancel_id')->where('a_user_id',$user->id)->orWhere('b_user_id',$user->id)->first();--}}
{{--                        @endphp--}}
{{--                        @if(isset($check_user->id) && ($user->meta_()->isConsign==1 || $user->meta_()->consign_expiry_date > \Carbon\Carbon::now()))--}}
{{--                            <a href="{!! url('/dashboard/account_consign_cancel') !!}" class="gg_zh_li"><span><img src="/new/images/zh04.png"></span>--}}
{{--                                <font>結束交付帳號</font>--}}
{{--                            </a>--}}
{{--                        @else--}}
{{--                            <a href="{!! url('/dashboard/account_consign_add') !!}" class="gg_zh_li"><span><img src="/new/images/zh05.png"></span>--}}
{{--                                <font>交付帳號</font>--}}
{{--                            </a>--}}
{{--                        @endif--}}



                    </div>

                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
@stop