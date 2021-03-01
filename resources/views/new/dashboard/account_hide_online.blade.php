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
                        <div class="gg_mm"><span><i></i>搜索排序設定</span><img src="/new/images/rzh07.png"></div>
                        <div class="paix_bg">
                            <div class="paixnr">
                                <h2>上線時，搜索頁面會有一個篩選條件是"登入時間"，會隨著您的上線時間來進行排序</h2>
                                <div class="paix_bg_img"><img src="/new/images/px_img.png"></div>
                                <h3>在您隱藏付費的這段期間可以選擇開啟或是關閉搜索上的登入排序：</h3>
                                <div class="paix_next">
                                    <h4 class="pa_line"><span>開啟：</span><font>搜索頁上在您開啟時，之後將不會再更新您的登入時間直到再度關閉。</font></h4>
                                    <h4><span>關閉：</span><font>搜索頁上會按照您的登入時間排序</font></h4>
                                </div>
                                <div class="flxz">
                                    <form id="switch_from" method="post" action="{{ route('hideOnlineSwitch') }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                    <input type="hidden" name="userId" value="{{$user->id}}">
                                    <span><input name="isHideOnline" type="radio" value="1" @if($user->is_hide_online==1)checked @endif><i>開啟</i></span>
                                    <span><input name="isHideOnline" type="radio" value="0" @if($user->is_hide_online==0)checked @endif><i>關閉</i></span>
                                    @if($user->is_hide_online==1)
                                        <br><span>開啟時間：{{substr($user->hide_online_time,0,11)}}</span>
{{--                                        <span>關閉時間：{{$user->hide_online_time}}</span>--}}
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

{{--        @if($valueAddedServiceStatus != 1)--}}
{{--        c5('您尚未購買隱藏付費功能');--}}
{{--        $('.n_bllbut, .bl_gb, .announce_bg').on('click',function(){--}}
{{--            window.location.href = "/dashboard/account_manage";--}}
{{--        });--}}
{{--        @endif--}}

{{--@if($valueAddedServiceStatus == 1)--}}

{{--@endif--}}

        $('input[type=radio][name=isHideOnline]').change(function() {
            $('#switch_from').submit();
            // let isHideOnline = this.value;
            //
            //
            // if (this.value == '1') {
            //     alert("1");
            // }
            // else if (this.value == '0') {
            //     alert("0");
            // }
        });

        {{--function formSubmit(){--}}

        {{--    @if($user->engroup_change==1)--}}
        {{--            c2('您已申請過，無法再修改喔！');--}}
        {{--            return false;--}}
        {{--    @endif--}}

        {{--    if(!$('.gender').is(':checked')){--}}
        {{--        c2('您尚未選擇類型');--}}
        {{--        return false;--}}
        {{--    }else if($('#reason').val()==''){--}}
        {{--        c2('請輸入欲修改的原因');--}}
        {{--        return false;--}}
        {{--    }else if($('#password').val()==''){--}}
        {{--        c2('請輸入您的密碼');--}}
        {{--        return false;--}}
        {{--    }else if($('input[name=gender]:checked', '#change_gender').val() == '{{$user->engroup}}') {--}}
        {{--        c2('您當前所選類型無需變更');--}}
        {{--        return false;--}}
        {{--    }else{--}}
        {{--        c4('一人只能申請一次變更，並且要通過站長同意，確定變更嗎？');--}}
        {{--    }--}}

        {{--    $('.n_left').on('click', function(event) {--}}
        {{--        $('#change_gender').submit();--}}
        {{--    });--}}
        {{--}--}}

        @if(Session::has('message'))
            c5('{{Session::get('message')}}');
        @endif
    </script>
@stop
