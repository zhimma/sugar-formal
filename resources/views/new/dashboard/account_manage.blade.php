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
                        <li><a href="{!! url('/dashboard/account_manage') !!}" class="g_pwicon_t3 g_hicon3"><span>帳號設定</span></a></li>
{{--                        <li><a href="{!! url('dashboard/vipSelect') !!}" class="g_pwicon_t4"><span>升級付費</span></a></li>--}}
                    </div>
                    <div class="gg_zh01 matop-50">
                        <a href="javascript:void(0)" class="gg_zh_li" onclick="checkChangeName();"><span><img src="/new/images/zh01.png"></span>
                            <font>修改暱稱申請</font>
                        </a>
                        <a href="javascript:void(0)" class="gg_zh_li" onclick="checkChangeGender();"><span><img src="/new/images/zh02.png"></span>
                            <font>變更帳號類型</font>
                        </a>
                        <a href="{!! url('/dashboard/password') !!}" class="gg_zh_li"><span><img src="/new/images/zh03.png"></span>
                            <font>更改密碼</font>
                        </a>
                        <a href="{!! url('/dashboard/openCloseAccount') !!}" class="gg_zh_li"><span><img src="/new/images/lightPinkKey.png"></span>
                            <font>帳號開啟/關閉</font>
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

                        @if($user->engroup==2)
                        <a href="javascript:void(0)" class="gg_zh_li" onclick="checkExchangePeriod();">
                            <span><img src="/new/images/zh06.png"></span>
                            <font>包養關係</font>
                        </a>
                        @endif

                        <a href="javascript:void(0)" class="gg_zh_li" onclick="checkHideOnline();"><span><img src="/new/images/zh07.png"></span>
                            <font>隱藏付費設定</font>
                        </a>

                        <a href=" {{route('viewChatNotice')}}" class="gg_zh_li"><span><img src="/new/images/zh08.png"></span>
                            <font>收件夾通知設定</font>
                        </a>

                        <a href="/member_auth/" class="gg_zh_li"><span><img src="/new/images/zh09.png"></span>
                            <font>手機驗證</font>
                        </a>
                        
                        @if($user->engroup==2)
                            <a @if($isAdvAuthUsable??false) href="/advance_auth/" @endif class="gg_zh_li" onclick="checkAdvAuth()"><span><img src="/new/images/zh10.png"></span>
                                <font>進階驗證</font>
                            </a>
                            <a id="apply_video_record_verify" class="gg_zh_li"><span><img src="/new/images/zh11.png"></span>
                                <font>申請視訊錄影驗證</font>
                            </a>
                        @endif

                        @if($user->engroup==2)
                            <a href="{{route('real_auth')}}" class="gg_zh_li"><span><img src="/new/images/zh11.png"></span>
                                    <font>本人認證</font>
                            </a>
                            <a href="javascript:void(0)" class="gg_zh_li" onclick="hasPassAuthCheck();"><span><img src="/new/images/zh11.png"></span>
                                <font>tag預覽設定</font>
                            </a>
                        @endif

                    </div>

                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        function checkChangeName() {

            @if($user->meta_()->name_change==1)
                c5('您已申請過，無法再修改喔！');
                return false;
            @endif

            window.location.replace("/dashboard/account_name_modify");
            return true;
        }

        function checkChangeGender() {

            @if($user->engroup_change >= 1)
            c5('您已申請過，無法再修改喔！');
            return false;
            @endif

            window.location.replace("/dashboard/account_gender_change");
            return true;
        }

        function checkExchangePeriod() {
            @if($user->meta_()->exchange_period_change >= 1)
                c5('您已申請過，無法再修改喔！');
                return false;
            @endif
            window.location.replace("/dashboard/account_exchange_period");
            return true;
        }

        function checkHideOnline() {

            @if($user->valueAddedServiceStatus('hideOnline') != 1)
            c5('您尚未購買隱藏付費功能');
            return false;
            @endif

            window.location.replace("/dashboard/account_hide_online");
            return true;
        }
        
        function checkAdvAuth() {
            var advAuthStr = '';
            @if(!$isAdvAuthUsable??false)
                @if($user->isForbidAdvAuth())
                advAuthStr = '{!!$userForbidMsg!!}';
                @elseif($user->isDuplicateAdvAuth())
                advAuthStr = '{!!$userWrongMsg!!}';                
                @elseif($user->isPauseAdvAuth())
                advAuthStr='{!!$userPauseMsg!!}';
                @elseif(!$user->isAdvanceAuth() &&  $is_pause_api??false)
                advAuthStr='{{$apiPauseMsg}}';
                @endif
            @endif
            {{$is_pause_api}}
            if(advAuthStr!='') {
                c5html(advAuthStr);
                return false;
            }
        }

        function hasPassAuthCheck() {
            @if($user->self_auth_status!=1 && $user->beauty_auth_status!=1)
                c5('您好，您目前尚未通過認證');
                return false;
            @endif
            window.location.replace("/dashboard/tag_display_settings");
            return true;
        }

        $('#apply_video_record_verify').click(function(){  
            @if((!($user->backend_user_details->first()->is_need_video_verify ?? false)) && $user->video_verify_auth_status == 0 && (!($user->warned_users->adv_auth ?? false)))
                c8html_custom('您好，您申請的視訊驗證功能，通過驗證後，將大大提高您的照片真實性，並驗證與您個人資料相符。同時，您將獲得官方認證的標籤 ✅，表示您的身分受官方認證。<br><br>通過驗證，能提升其他會員對您資料的信任度，吸引更多真實、的會員與您互動，提高交友的品質和成功率。<br><br>請放心，我們將嚴格保護您的隱私和個人資料，在視訊驗證過程中採取相應的安全措施。驗證結果僅用於確認照片真實性和授予官方認證標籤，不會被用於其他任何目的。', '申請驗證', '取消驗證');
                $("#tab08 .tab_confirm_btn").on('click', function() {
                    @if($user->isAdvanceAuth())
                        $.ajax({
                            url: '{{ route("apply_video_record_verify") }}',
                            type: 'GET',
                            data: {
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function(data) {
                                if(data.status == 'success'){
                                    c5('已申請，站方會再跟您約驗證時間，再請注意來訊。');
                                }
                            }
                        });
                    @else
                        c5html("請先通過 進階驗證(<a href='/advance_auth'><span style='color:red'>點此前往</span></a>)");
                    @endif
                    c8_gmBtnNoReload();
                });
            @else
                @if($user->video_verify_auth_status)
                    c5('已通過');
                @else
                    @if($user->backend_user_details->first()->has_upload_video_verify ?? false)
                        c5('您好，您於 {{Carbon\Carbon::parse($user->backend_user_details->first()->need_video_verify_date)->format('Y-m-d')}} 時於本站申請 視訊錄影認證，目前已完成視訊錄影，待站方審核通知。');
                    @elseif($user->backend_user_details->first()->video_verify_fail_count>=3)
                        c5html('您連續三次視訊驗證失敗，暫時停止視訊驗證，若有問題請與站長聯絡 <a href="https://lin.ee/rLqcCns"><img src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png" alt="加入好友" height="26" border="0" style="all: initial;all: unset;height: 26px; float: unset;vertical-align:middle !important;"></a>');
                    @elseif($user->warned_users->video_auth ?? false)
                        c5('您好，您目前被站方警示，請留意下次上線時站方將撥打給你進行視訊驗證。');
                    @elseif($user->warned_users->adv_auth ?? false)
                        c5('你好，您目前被站方警示，請進行進階驗證(<a href="/advance_auth"><span style="color:red">點此前往</span></a>)。');
                    @else
                        c5('已申請，站方會再跟您約驗證時間，再請注意來訊。');
                    @endif
                @endif
            @endif
        });
    </script>
@stop