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
                        <div class="gg_mm"><span><i></i>交付帳號</span><img src="/new/images/rzh04.png"></div>
                        <div class="gg_nr01">
                            <div class="gg_input01">
                                <form method="POST" id="consign_add" action="/dashboard/consignAdd">
                                    {!! csrf_field() !!}
                                <div class="de_input01 dlmarbot"><input name="account" id="account" type="text" class="zcinput" placeholder="請輸入對方的帳號"></div>
                                <div class="de_input01 dlmarbot"><input name="password" id="password" type="password" class="zcinput" placeholder="請輸入您的密碼"></div>
                                <a class="dlbut g_inputt40" onclick="submit()">關閉帳號</a>
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
        c5('當站方收到雙方申請時，本帳號將會關閉，也無法進入搜索以及訊息頁面，若您們任一方點開啟帳號，則雙方帳號都會開啟，系統也會發一封信通知雙方帳號開啟。');
        @endif

        function submit(){

            @if($user->meta_()->isConaign==0 && $user->meta_()->consign_expiry_date > \Carbon\Carbon::now() )
            let expiry_time = '{{$user->meta_()->consign_expiry_date}}';
            c2('您的帳號將於 ' + expiry_time + ' 後啟用，請於啟用後再提出申請。');
            return false;
            @endif

            if($('#account').val()==''){
                c2('請輸入對方的帳號');
            }else if($('#password').val()=='') {
                c2('請輸入您的密碼');
            }else{
                c4('確定關閉帳號嗎？');
                $('.n_left').on('click', function(event) {
                    $('#consign_add').submit();
                });

            }
        }

        @if(Session::has('message'))
        c3('{{Session::get('message')}}');
        @endif

    </script>
@stop
