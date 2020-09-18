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
                        <div class="gg_mm"><span><i></i>變更帳號類型</span><img src="/new/images/rzh02.png"></div>
                        <div class="gg_nr01">
                            <form method="POST" id="change_gender" action="/dashboard/changeGender">
                                {!! csrf_field() !!}
                            <div class="leix">
                                <span><div class="le_ce"><input name="gender" class="gender" type="radio" value="1" @if($user->engroup==1)checked="checked"@endif/><i>甜心大哥</i></div></span>
                                <span><div class="le_ce"><input name="gender" class="gender" type="radio" value="2" @if($user->engroup==2)checked="checked"@endif/><i>甜心寶貝</i></div></span>
                                <div class="de_input01"><input name="reason" id="reason" type="text" class="zcinput" placeholder="請輸入修改的原因" required></div>
                            </div>
                            <div class="gg_font">註：每個帳號只能變更一次</div>
                            <a class="dlbut g_inputt40" onclick="submit()">提交申請</a>
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
        function submit(){

            @if($user->engroup_change==1)
                    c2('您已申請過，無法再修改喔！');
                    return false;
            @endif

            if(!$('.gender').is(':checked')){
                c2('您尚未選擇類型');
            }else if($('#reason').val()==''){
                c2('請輸入欲修改的原因');
            }else if($('input[name=gender]:checked', '#change_gender').val() == '{{$user->engroup}}') {
                c2('您當前所選類型無需變更');
            }else{
                c4('一人只能申請一次變更，並且要通過站長同意，確定變更嗎？');
            }

            $('.n_left').on('click', function(event) {
                $('#change_gender').submit();
            });
        }

        @if(Session::has('message'))
        c2('{{Session::get('message')}}');
        @endif
    </script>
@stop
