@extends('new.layouts.website')
@section('style')
<style>
    .n_gg_mm span {
        position: relative;
    }
</style>
@stop
@section('app-content')
    <div class="container matop70 chat">
        <div class="row">
        <div class="col-sm-2 col-xs-2 col-md-2 dinone">
            @include('new.dashboard.panel')
        </div>
        <div class="col-sm-12 col-xs-12 col-md-10 g_pnr_gq">
            <div class="g_password g_pnr">
                <div class="g_pwicon">
                    <li><a href="@if($user->isVVIP()) /dashboard/viewuser_vvip/{{$user->id}} @else /dashboard/viewuser/{{$user->id}} @endif" class="g_pwicon_t5 "><span>自我預覽</span></a></li>
                    <li><a href="{!! url('dashboard') !!}" class="g_pwicon_t "><span>基本資料</span></a></li>
                    <li><a href="{!! url('dashboard_img') !!}" class="g_pwicon_t2"><span>照片管理</span></a></li>
                    <li><a href="{!! url('/dashboard/account_manage') !!}" class="g_pwicon_t3 g_hicon3"><span>帳號設定</span></a></li>
                </div>
                <form id="chatSetForm" action="{{ route('tagDisplaySet') }}" method="post">
                    {{ csrf_field() }}
                    <div class="gg_zh">
                        <dt id="line_notify_expand" class="n_gg_mm"><span><i></i>Tag預覽顯示設定</span><!--<img src="/new/images/shed_icon.png">--></dt>
                        <dd style="display: block;">
                            <div class="tuba">若您有通過本人驗證，則會獲得站方認證的 Tag，且站方預設僅開放給 VVIP，以及 PR 值超過80的VIP Daddy，您可自行於下方調整，開放給VIP或PR多少以上的會員看</div>
                            <div class="tu_bd">
                                <div class="ngg_a_tab">
                                    @if($user->self_auth_status)
                                        <div class="ti_ktx na_top10 ga_ti_ft"><font class="na_nb">a.<i class="ga_i">本人驗證</i></font></div>
                                        <div class="ti_xcheck naa_dd">
                                            <span><input type="checkbox" name="self_auth_vip_show" class="ti_ceckys" value="VIP" @if($data['self_auth'] && $data['self_auth']->vip_show) checked @endif>VIP</span>
                                            <span>
                                                <input type="checkbox" name="self_auth_pr_show" class="ti_ceckys" value="PR" @if($data['self_auth'] && $data['self_auth']->more_than_pr_show) checked @endif>PR
                                            </span>
                                            <input type="number" name="self_auth_pr_value" value="{{ ($data['self_auth'] && $data['self_auth']->more_than_pr_show) ?  $data['self_auth']->more_than_pr_show :60 }}" min="0" max="100" required style="height: 22px;margin-left: -16px;line-height:normal;">
                                        </div>
                                    @endif
                                    @if($user->beauty_auth_status)
                                        <div class="ti_ktx na_top25 ga_ti_ft"><font class="na_nb">b.<i class="ga_i">美顏驗證</i></font></div>
                                        <div class="ti_xcheck naa_dd">
                                            <span><input type="checkbox" name="beauty_auth_vip_show" class="ti_ceckys" value="VIP" @if($data['beauty_auth'] && $data['beauty_auth']->vip_show) checked @endif>VIP</span>
                                            <span>
                                                <input type="checkbox" name="beauty_auth_pr_show" class="ti_ceckys" value="PR" @if($data['beauty_auth'] && $data['beauty_auth']->more_than_pr_show) checked @endif>PR
                                            </span>
                                            <input type="number" name="beauty_auth_pr_value" value="{{ ($data['beauty_auth'] && $data['beauty_auth']->more_than_pr_show) ?  $data['beauty_auth']->more_than_pr_show :60 }}" min="0" max="100" required style="height: 22px;margin-left: -16px;line-height:normal;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </dd>
                    </div>
                    <a class="dengl_but matop10 mabot_30 form_submit">更新資料</a>
                </form>
            </div>
        </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>

        $(".sidebar_box dd").show();
        $('.sys_log dt').toggleClass('on');

        @if(Session::has('message'))
        c5("{{Session::get('message')}}");
        <?php session()->forget('message');?>
        @endif

        $('.sys_log dt').on('click', function() {
            
            if($(this).hasClass('on')){
                $(this).removeClass('on');
                $(this).toggleClass('off');
            }else{
                $(this).removeClass('off');
                $(this).toggleClass('on');
            }

            $(this).next('dd').slideToggle();
        });

        $(".line_notify").on('click', function() {
            @if($user->isVipOrIsVvip())
            show_line_notify_set_alert();
            @else
            show_onlyForVipPleaseUpgrade();
            @endif

            $(".n_bllbut").on('click', function() {
                var lineClientId = '{{config('line.line_notify.client_id')}}';
                var callbackUrl = '{{config('line.line_notify.callback_url')}}';
                var URL = '{{config('line.line_notify.authorize_url')}}?';
                URL += 'response_type=code';
                URL += '&client_id='+lineClientId;
                URL += '&redirect_uri='+callbackUrl;
                URL += '&scope=notify';
                URL += '&state={{csrf_token()}}';
                URL += '&response_mode=form_post';
                window.open(URL, '_blank');
            });
        });

        $(".line_notify_cancel").on('click', function() {
            c4('確定要解除LINE綁定通知嗎?');
            var URL = '{{route('lineNotifyCancel')}}';
            $(".n_left").on('click', function() {
                $("#tab04").hide();
                $(".blbg").hide();
                window.location.href = URL;
            });
        });

        $('.form_submit').on('click', function() {
            /*var user_line_token = '{{$user->line_notify_token}}';
            if(user_line_token.length==0){
                $('input:checkbox').prop('checked', false);
                c5('請先綁定Line');
            }else {
                $('#chatSetForm').submit();
            }*/
            window.sessionStorage.setItem('isUpdated',true);
            $('#chatSetForm').submit();
        });

        $(function() {
            $(".gg_zh dd").hide();
            if(window.sessionStorage.getItem('isUpdated')){
                if(window.sessionStorage.getItem('line_notify_expand'))
                {
                    $('#line_notify_expand').toggleClass('on');
                    $('#line_notify_expand').next('dd').slideToggle();
                }
                if(window.sessionStorage.getItem('refuse_inbox_expand'))
                {
                    $('#refuse_inbox_expand').toggleClass('on');
                    $('#refuse_inbox_expand').next('dd').slideToggle();
                }
            }
            else{
                
                window.sessionStorage.removeItem('line_notify_expand');
                window.sessionStorage.removeItem('refuse_inbox_expand');
            }
            window.sessionStorage.removeItem('isUpdated');
            
        })
            
        $('#line_notify_expand').click(function(e) {
            if(window.sessionStorage.getItem('line_notify_expand'))
            {
                window.sessionStorage.removeItem('line_notify_expand');
            }
            else
            {
                window.sessionStorage.setItem('line_notify_expand',true);
            }
            $(this).toggleClass('on');
            $(this).next('dd').slideToggle();
        });

        $('#refuse_inbox_expand').click(function(e) {
            if(window.sessionStorage.getItem('refuse_inbox_expand'))
            {
                window.sessionStorage.removeItem('refuse_inbox_expand');
            }
            else
            {
                window.sessionStorage.setItem('refuse_inbox_expand',true);
            }
            $(this).toggleClass('on');
            $(this).next('dd').slideToggle();
        });
    </script>
@stop