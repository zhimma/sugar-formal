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
                        <li><a href="@if($user->isVVIP()) /dashboard/viewuser_vvip/{{$user->id}} @else /dashboard/viewuser/{{$user->id}} @endif" class="g_pwicon_t5 "><span>自我預覽</span></a></li>
                        <li><a href="{!! url('dashboard') !!}" class="g_pwicon_t "><span>基本資料</span></a></li>
                        <li><a href="{!! url('dashboard_img') !!}" class="g_pwicon_t2"><span>照片管理</span></a></li>
                        <li><a href="{!! url('/dashboard/account_manage') !!}" class="g_pwicon_t3 g_hicon3"><span>帳號設定</span></a></li>
{{--                        <li><a href="{!! url('dashboard/vipSelect') !!}" class="g_pwicon_t4"><span>升級付費</span></a></li>--}}
                    </div>
                    <div class="gg_zh">
                        <div class="gg_mm"><span><i></i>結束交付帳號</span><img src="/new/images/rzh05.png"></div>
                        <div class="gg_nr01">
                            <div class="gg_input01">
                                <form method="POST" id="consign_cancel" action="/dashboard/consignCancel">
                                    {!! csrf_field() !!}
                                <div class="de_input01 dlmarbot"><input name="account" id="account" type="text" class="zcinput" placeholder="請輸入對方的帳號"></div>
                                <div class="de_input01 dlmarbot"><input name="password" id="password" type="password" class="zcinput" placeholder="請輸入您的密碼"></div>
                                <a class="dlbut g_inputt40" onclick="submit()">開啟帳號</a>
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
        @if(!Session::has('message'))
        c5('48小時之後，雙方帳號都會開啟，並且雙方將會收到一封系統通知信。');
        @endif

        function submit(){

            @if($user->meta_()->isConaign==0 && $user->meta_()->consign_expiry_date > \Carbon\Carbon::now() )
                let expiry_time = '{{$user->meta_()->consign_expiry_date}}';
                c5('您已申請結束交付帳號，雙方帳號將於 ' + expiry_time + ' 後啟用。');
            return false;
            @endif

            if($('#account').val()==''){
                c5('請輸入對方的帳號');
            }else if($('#password').val()=='') {
                c5('請輸入您的密碼');
            }else{
                c4('確定開啟帳號嗎？');
                $('.n_left').on('click', function(event) {
                    $('#consign_cancel').submit();
                });
            }
        }

        @if(Session::has('message'))
        c5('{{Session::get('message')}}');
        @endif

    </script>
@stop
