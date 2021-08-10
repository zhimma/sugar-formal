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
                        <li><a href="{!! url('/dashboard/account_manage') !!}" class="g_pwicon_t3 g_hicon3"><span>帳號設定</span></a></li>
                        <li><a href="{!! url('dashboard/vipSelect') !!}" class="g_pwicon_t4"><span>升級付費</span></a></li>
                    </div>
                    <div class="gg_zh">
                    <div class="gg_mm"><span><i></i>更改密碼</span><img src="/new/images/rzh03.png"></div>
{{--                    <div class="de_input g_inputt">--}}
                    <div class="gg_input matop-50">
                        <form method="POST" name="cpassword" id="registration" action="/dashboard/changepassword">
                            {!! csrf_field() !!}
                        <div class="de_input01 dlmarbot"><input name="old_password" id="old_password" type="password" class="zcinput" placeholder="現在的密碼" data-parsley-required data-parsley-required-message="請輸入現在的密碼"></div>
                        <div class="de_input01 dlmarbot"><input name="password" id="password" type="password" class="zcinput" placeholder="新密碼" data-parsley-required data-parsley-required-message="請輸入新密碼" data-parsley-minlength="6" data-parsley-minlength-message="密碼欄位請輸入大於6個位元(含以上)"></div>
                        <div class="de_input01 dlmarbot"><input name="password_confirmation" id="password_confirmation" type="password" class="zcinput" placeholder="確認密碼" data-parsley-required data-parsley-required-message="請再次輸入新密碼（新密碼與再次輸入新密碼要檢查是否一致）" data-parsley-minlength="6" data-parsley-minlength-message="密碼欄位請輸入大於6個位元(含以上)" ></div>
                        <button class="dlbut g_inputt40" type="submit" style="border-style: none;">更新資料</button>
                        <button type="reset" class="zcbut matop20">取消</button>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('javascript')

    <script>
        $(document).ready(function() {
            $("form[name=cpassword]").parsley().on('form:validate', function (formInstance) {

            })
            .on('form:error', function () {
                var error = $('ul.parsley-errors-list li');
                var msg=[];
                for (var i = 0; i <error.length; i++) {
                    msg[i]=error.eq(i).html();
                    break;
                }
                msg = Array.from(new Set(msg));
                // ResultData({
                //   msg: msg
                // });
                c5(msg);
                $(".btn-register").removeAttr('disabled', 'disabled')
            })
            .on('form:success', function () {
                return true;
            });
        });

        @if(Session::has('message'))
        c5('{{Session::get('message')}}');
        @endif

    </script>
@stop
