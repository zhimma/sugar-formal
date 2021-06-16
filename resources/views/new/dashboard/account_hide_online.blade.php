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
                        <li><a href="/dashboard/viewuser/{{$user->id}}" class="g_pwicon_t5 "><span>自我預覽</span></a></li>
                        <li><a href="{!! url('dashboard') !!}" class="g_pwicon_t "><span>基本資料</span></a></li>
                        <li><a href="{!! url('dashboard_img') !!}" class="g_pwicon_t2"><span>照片管理</span></a></li>
                        <li><a href="{!! url('/dashboard/account_manage') !!}" class="g_pwicon_t3 g_hicon3"><span>帳號設定</span></a></li>
{{--                        <li><a href="{!! url('dashboard/vipSelect') !!}" class="g_pwicon_t4"><span>升級付費</span></a></li>--}}
                    </div>
                    <div class="gg_zh">
                        <div class="gg_mm"><span><i></i>搜索排序設定</span><img src="/new/images/rzh07.png"></div>
                        <div class="paix_bg">
                            <div class="paixnr">
                                <h2>上線時，搜索頁面會有一個篩選條件是"登入時間"，會隨著您的上線時間來進行排序</h2>
                                <div class="paix_bg_img"><img src="/new/images/px_img.png"></div>
                                <h3>在您隱藏付費的這段期間可以開啟、關閉搜索上的登入排序或是直接隱藏於搜索頁中</h3>
                                <div class="paix_next">
                                    <h4 class="pa_line"><span>開啟：</span><font>搜索頁上會按照您的登入時間排序</font></h4>
                                    <h4 class="pa_line"><span>關閉：</span><font>搜索頁上在您關閉時，之後將不會在更新您的登入時間直到再度開啟。</font></h4>
                                    <h4><span>隱藏：</span><font>完全消失在搜索頁上不被搜索</font></h4>
                                </div>
                                <div class="flxz">
                                    <form id="switch_from" method="post" action="{{ route('hideOnlineSwitch') }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                    <input type="hidden" name="userId" value="{{$user->id}}">
                                    <span><input name="isHideOnline" type="radio" value="0" @if($user->is_hide_online==0)checked @endif><i>開啟</i></span>
                                    <span><input name="isHideOnline" type="radio" value="1" @if($user->is_hide_online==1)checked @endif><i>關閉</i></span>
                                    <span><input name="isHideOnline" type="radio" value="2" @if($user->is_hide_online==2)checked @endif><i>隱藏</i></span>
                                    @if($user->is_hide_online == 1)
                                        <br><span>關閉時間：{{ substr($user->hide_online_time, 0, 11) }}</span>
                                    @elseif($user->is_hide_online == 2)
                                        <br><span>關閉時間：{{ substr($user->hide_online_hide_time, 0, 11) }}</span>
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
