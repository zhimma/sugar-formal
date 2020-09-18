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
                        <li><a href="{!! url('/dashboard/vip') !!}" class="g_pwicon_t4"><span>VIP</span></a></li>
                    </div>
                    <div class="gg_zh">
                        <div class="gg_mm"><span><i></i>修改暱稱申請</span><img src="/new/images/rzh01.png"></div>

                    <div class="gg_nr01">
                        <div class="gg_input01">
                            <form method="POST" id="change_name" action="/dashboard/changeName">
                                {!! csrf_field() !!}
                            <div class="de_input01"><input name="name" id="name" type="text" class="zcinput" placeholder="請輸入欲修改的暱稱" maxlength="8"></div>
                            <br>
                            <div class="de_input01"><input name="reason" id="reason" type="text" class="zcinput" placeholder="請輸入修改的原因" maxlength="100"></div>
                            <div class="blxg">只能申請改一次，並且要通過站長同意</div>
                            <a class="dlbut g_inputt40 change_name_submit" onclick="formSubmit()">送出修改</a>
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
        function formSubmit(){
            @if($user->meta_()->name_change==1)
                c2('您已申請過，無法再修改喔！');
                return false;
            @endif

            if($('#name').val()==''){
                c2('請輸入欲修改的暱稱');
                return false;
            }else if($('#reason').val()==''){
                c2('請輸入欲修改的原因');
                return false;
            }else {
                c4('暱稱只能申請修改一次，並且要通過站長同意，確定要修改暱稱嗎？');
            }

            $('.n_left').on('click', function(event) {
                $('#change_name').submit();
            });
        }

        @if(Session::has('message'))
        c2('{{Session::get('message')}}');
        @endif

    </script>
@stop
