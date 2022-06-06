<?php
header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
?>
@extends('new.layouts.website')
@section('app-content')
    <style>
        .by{width:85%; margin: 0 auto; display: table;}
        @media (max-width:1024px) {
        .by{width:100%; }
        }
        @media (max-width:797px) {
        .by{width:100%; }
        }
    </style>
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
                        {{--<li><a href="{!! url('dashboard/vipSelect') !!}" class="g_pwicon_t4"><span>升級付費</span></a></li>--}}
                    </div>
                    <div class="gg_zh">
                        <div class="gg_mm"><span><i></i>包養關係</span><img src="/new/images/rzh06.png"></div>
                        <div class="gg_nr01">
                            <div class="ga_dtie ewn_6">
                                {{$user->name}} 您好，自您 {{$user->created_at->toDateString()}} 註冊以來已經是第 {{$user_login_count}} 次光臨本站。本站包養關係有自行調整一次的機會，再麻煩您確認您的包養關係。
                                <span class="hdss_s">包養關係經確認後，如果還要調整，需經站方審核，</span>故請慎重選擇”</span>
                            </div>
                            <form method="POST" id="exchange_period_modify" action="/dashboard/first_exchange_period_modify">
                                {!! csrf_field() !!}
                                <input type="hidden" name="{{ time() }}" value="{{ time() }}">
                                <div class="baoy">
                                    <ul>
                                        @php
                                            $exchange_period_name = DB::table('exchange_period_name')->get();
                                        @endphp
                                        @foreach($exchange_period_name as $row)
                                            <li>
                                                <div><input name="exchange_period" type="radio" value="{{$row->id}}" @if($user->exchange_period == $row->id) checked @endif><span>{{$row->name}}</span></div>
                                                <div class="ew_font">{{$row->remark}}</div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="by">
                                    <div class="de_input01 matop10"><input name="password" id="password" type="password" class="zcinput" placeholder="請輸入您的密碼"></div>
                                </div>
                            </form>
                            @if($user_login_count <= 10)
                                <br>
                                <div class="n_txbut">
                                    <a class="se_but1" onclick="submit()">送出</a>
                                    <a class="se_but2" onclick="next_time()">下次再說</a>
                                </div>
                            @else
                                <a class="dlbut g_inputt40" onclick="submit()">送出</a>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('javascript')

    <script>

        function submit()
        {
            if($('#password').val()=='')
            {
                c5('請輸入您的密碼');
                return false;
            }
            else
            {
                c4('確定要變更包養關係嗎？');
                $('.n_left').on('click', function(event) {
                    $('#exchange_period_modify').submit();
                });
            }
        }

        function next_time()
        {
            $.ajax({
                type:'get',
                url:'/dashboard/first_exchange_period_modify_next_time',
                success:function(){
                    location.href="/dashboard/personalPage"
                }
            });
        }

        @if(Session::has('message'))
            c5('{{Session::get('message')}}');
        @endif

    </script>
@stop
