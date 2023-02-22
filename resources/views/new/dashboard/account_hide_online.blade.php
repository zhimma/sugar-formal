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
                            <div class="gg_mm"><span><i></i>隱藏付費設定</span><img src="/new/images/rzh07.png"></div>
                        <div class="paix_bg">
                            <div class="paixnr">
                                <h3>在您隱藏付費時，預設會關閉您在搜索頁及會員資料頁的上線資訊，您也可以設定隱藏在搜索中，或是暫停隱藏(恢復正常)</h3>
                                <div class="paix_next">
{{--                                    <h4 class="pa_line"><span>開啟：</span><font>搜索頁上會按照您的登入時間排序</font></h4>--}}
{{--                                    <h4 class="pa_line"><span>關閉：</span><font>搜索頁上在您關閉時，之後將不會在更新您的登入時間直到再度開啟。</font></h4>--}}
{{--                                    <h4><span>隱藏：</span><font>完全消失在搜索頁上不被搜索</font></h4>--}}
                                    <h4 class="pa_line"><span>隱藏：</span><font>隱藏時，之後將不會再更新您的登入時間，直到關閉隱藏。</font></h4>
                                    <h4 class="pa_line"><span>消失：</span><font>完全消失在搜索頁上，其他會員無法查詢到您的資料</font></h4>
                                    <h4><span>關閉：</span><font>暫停隱藏功能，恢復正常狀態</font></h4>
                                </div>
                                <div class="flxz">
                                    <form id="switch_from" method="post" action="{{ route('hideOnlineSwitch') }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                    <input type="hidden" name="userId" value="{{$user->id}}">
                                    <span><input name="isHideOnline" type="radio" value="1" @if($user->is_hide_online==1)checked @endif><i>隱藏</i></span>
                                    <span><input name="isHideOnline" type="radio" value="2" @if($user->is_hide_online==2)checked @endif><i>消失</i></span>
                                    <span><input name="isHideOnline" type="radio" value="0" @if($user->is_hide_online==0)checked @endif><i>關閉</i></span>
                                    @if($user->valueAddedServiceStatus('hideOnline') == 1 && $user->is_hide_online == 1)
                                        <br><span>您的上線狀態凍結於{{ substr($hide_online_data->login_time, 0, 11) }}</span>
                                    @elseif($user->valueAddedServiceStatus('hideOnline') == 1 && $user->is_hide_online == 2)
                                        <br><span>您的上線狀態凍結於{{ substr($hide_online_data->login_time, 0, 11) }}，且其他會員無法查詢到您的資料</span>
                                    @elseif($user->valueAddedServiceStatus('hideOnline') == 1 && $user->is_hide_online == 0)
                                        <br><span>您目前沒有啟動隱藏功能</span>
                                    @endif
                                    </form>
                                </div>
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



        $('input[type=radio][name=isHideOnline]').change(function() {
            $('#switch_from').submit();
        });

        @if(Session::has('message'))
            c5('{{Session::get('message')}}');
        @endif
    </script>
@stop
