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
                        <li><a href="{!! url('dashboard') !!}"><img src="/new/images/mm_03.png"><span>基本資料</span></a></li>
                        <li><a href="{!! url('dashboard_img') !!}"><img src="/new/images/mm_05.png"><span>照片管理</span></a></li>
                        <li><a href="{!! url('/dashboard/password') !!}"><img src="/new/images/mm_17.png"><span>更改密碼</span></a></li>
                        <li><a href="{!! url('/dashboard/vip') !!}"><img src="/new/images/mm_09.png"><span>VIP</span></a></li>
                    </div>
                    <div class="de_input g_inputt">
                        <form method="POST" id="registration" action="/dashboard/changepassword">
                            {!! csrf_field() !!}
                        <div class="de_input01 dlmarbot"><input name="old_password" id="old_password" type="password" class="zcinput" placeholder="現在的密碼" required></div>
                        <div class="de_input01 dlmarbot"><input name="password" id="password" type="password" class="zcinput" placeholder="新密碼" required minlength="6"></div>
                        <div class="de_input01 dlmarbot"><input name="password_confirmation" id="password_confirmation" type="password" class="zcinput" placeholder="確認密碼" required required minlength="6"></div>
                        <button class="dlbut g_inputt40" type="submit" style="border-style: none;">更新資料</button>
                        <button type="reset" class="zcbut matop20">取消</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('javascript')

    <script>
        @if(Session::has('message') && Session::get('message') == '確認新密碼不符合，請重新操作')
            c2('確認新密碼不符合，請重新操作');
        @endif

        @if(Session::has('message') && Session::get('message') == '更新成功')
            c2('更新成功');
        @endif

        @if(Session::has('message') && Session::get('message') == '原密碼有誤，請重新操作')
            c2('原密碼有誤，請重新操作');
        @endif
    </script>
@stop
