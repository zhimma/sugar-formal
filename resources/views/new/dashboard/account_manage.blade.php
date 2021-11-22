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
                        <li><a href="/dashboard/viewuser/{{$user->id}}" class="g_pwicon_t5 "><span>自我預覽</span></a></li>
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
                            <font>搜索隱藏設定</font>
                        </a>

                        <a href="{{route('viewChatNotice')}}" class="gg_zh_li"><span><img src="/new/images/zh08.png"></span>
                            <font>Line通知設定</font>
                        </a>

                        <a href="/member_auth/" class="gg_zh_li"><span><img src="/new/images/zh09.png"></span>
                            <font>手機驗證</font>
                        </a>
                        @if($user->engroup==2)
                        <a @if($isAdvAuthUsable??false) href="/advance_auth/" @endif class="gg_zh_li" onclick="checkAdvAuth()"><span><img src="/new/images/zh10.png"></span>
                            <font>進階驗證</font>
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
                advAuthStr='您的進階驗證功能有誤，<a href="https://lin.ee/rLqcCns" target="_blank">請點此 <img src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png" alt="加入好友" height="36" border="0" style="height: 36px; float: unset;"></a> 或點右下聯絡我們加站長 line 與站長聯絡。';    
                @elseif($user->isPauseAdvAuth())
                advAuthStr='{{$userPauseMsg}}';
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
    </script>
@stop