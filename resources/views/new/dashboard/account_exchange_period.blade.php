<?
header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
?>
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
                        <div class="gg_mm"><span><i></i>包養關係</span><img src="/new/images/rzh06.png"></div>
                        <div class="gg_nr01">

                            <form method="POST" id="exchange_period_modify" action="/dashboard/exchangePeriodModify">
                                {!! csrf_field() !!}
                                <input type="hidden" name="{{ time() }}" value="{{ time() }}">
                                <div class="baoy">
                                    <ul>
                                        @php
                                            $exchange_period_name = DB::table('exchange_period_name')->get();
                                        @endphp
                                        @foreach($exchange_period_name as $row)
                                            <li>
                                                <input name="exchange_period" type="radio" value="{{$row->id}}" @if($user->exchange_period == $row->id) checked @endif><span>{{$row->name}}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <br>
                                <div class="gg_input01">
                                <div class="de_input01"><input name="reason" id="reason" type="text" class="zcinput" placeholder="請輸入修改的原因" maxlength="100"></div>
                                <br>
                                <div class="de_input01"><input name="password" id="password" type="password" class="zcinput" placeholder="請輸入您的密碼"></div>
                                </div>
                                <br>
                                <div class="blxg">只能申請改一次，並且要通過站長同意</div>
                                <a class="dlbut g_inputt40" onclick="submit()">確定</a>
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

        @php
            $exchange_period_read = DB::table('exchange_period_temp')->where('user_id',$user->id)->first();
            if(!isset($exchange_period_read->id)){
                DB::table('exchange_period_temp')->insert(['user_id'=>$user->id,'created_at'=>\Carbon\Carbon::now()]);
            }

        @endphp

        function submit(){
            if($('#reason').val()==''){
                c2('請輸入欲修改的原因');
                return false;
            }else if($('#password').val()==''){
                c2('請輸入您的密碼');
                return false;
            }else if($('input[name=exchange_period]:checked', '#exchange_period_modify').val() == '{{$user->exchange_period}}') {
                c2('您當前所選項目無需變更');
                return false;
            }else{
                c4('確定要變更包養關係嗎？');
                $('.n_left').on('click', function(event) {
                    $('#exchange_period_modify').submit();
                });
            }
        }

        @if(Session::has('message'))
        c3('{{Session::get('message')}}');
        @endif

    </script>
@stop
