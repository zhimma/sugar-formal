@extends('new.layouts.website')

@section('app-content')
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="g_password g_pnr">
                    <div class="g_pwicon">
                        <li><a href="{!! url('dashboard') !!}" class="g_pwicon_t "><span>基本資料</span></a></li>
                        <li><a href="{!! url('dashboard_img') !!}" class="g_pwicon_t2"><span>照片管理</span></a></li>
                        <li><a href="{!! url('/dashboard/account_manage') !!}" class="g_pwicon_t3 g_hicon3"><span>更改帳號</span></a></li>
                        <li><a href="{!! url('/dashboard/new_vip') !!}" class="g_pwicon_t4"><span>VIP</span></a></li>
                    </div>
                    <div class="gg_zh">
                        <div class="gg_mm"><span><i></i>會員帳號開啟/關閉</span><img src="/new/images/darkPinkKey.png"></div>

                        <div class="gg_nr01">
                            <div class="gg_input01">
                                <form method="POST" id="change_account_status" action="/dashboard/checkAccountAuth">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="de_input01 dlmarbot ">
                                        <div class="de_img"><img src="/new/images/lo_03.png"></div>
                                        <input name="email" type="email" autocomplete="off" id="email" class="d_input" placeholder="帳號 (您的Email)" values="" required="" data-parsley-id="4">
                                    </div>
                                    <div class="de_input01 dlmarbot m-loader m-loader--right m-loader--light">
                                        <div class="de_img"><img src="/new/images/lo_11.png"></div>
                                        <input name="password" type="password" class="d_input" id="password" placeholder="密碼" required="" data-parsley-id="6">
                                    </div>
                                    <button type="submit" class="dlbut g_inputt40" style="border-style: none;">確認帳號</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('javascript')
    <script>
        @if(Session::has('message'))
        c2('{{Session::get('message')}}');
        @endif
    </script>
@stop