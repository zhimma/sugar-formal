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
                        <li><a href="{!! url('dashboard') !!}" class="g_pwicon_t "><span>基本資料</span></a></li>
                        <li><a href="{!! url('dashboard_img') !!}" class="g_pwicon_t2"><span>照片管理</span></a></li>
                        <li><a href="{!! url('/dashboard/account_manage') !!}" class="g_pwicon_t3 "><span>更改帳號</span></a></li>
                        <li><a href="{!! url('/dashboard/new_vip') !!}" class="g_pwicon_t4 g_hicon4"><span>VIP</span></a></li>
                    </div>
                    <div class="new_viphig">
                        <div class="n_viptitle">
                            <a href="#" onclick='changediv("vip")' id="vip_a" class="n_viphover" target=_parent>升級VIP</a>

                            <a href="#" onclick='changediv("vip2")' id="vip2_a" target=_parent>取消VIP</a>
                        </div>
                        <div class="n_sjvip"  id="vip">
                            @if($user->engroup==2)
                            <div class="new_syfont">上傳大頭貼+三張生活照,即可免費成為VIP</div>
                            @endif
                            <div class="vipline"><img src="/new/images/VIP_05.png"></div>

                            <div class="dq_fangan">信用卡VIP自動續約扣款</div>
                            <div class="new_viplist" style="margin-bottom: 10px;">
                                <ul>
                                    <li><div class="vipcion"><img src="/new/images/bicontop.png"></div>
                                        <div class="new_fa">每季支付 NT</div>
                                        <div class="new_fanext">
                                            <h2>NT$988/月</h2>
                                            <h2>每季自動扣款</h2>
                                        </div>
{{--                                        <a class="new_gvip_input" onclick="cl()">購買</a>--}}
                                        <form class="m-form m-form--fit" action="{{ route('upgradepay_ec') }}" method=post>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                            <input type="hidden" name="userId" value="{{$user->id}}">
                                            <input type="hidden" name="type" value="cc_quarterly_payment">
                                            <button type="submit" class="new_gvip_input cc_quarterly_payment paySubmit" style="border-style: none; outline: none;">信用卡</button>
                                        </form>
                                    </li>
                                    <li>
                                        <div class="new_fa">每月支付 NT</div>
                                        <div class="new_fanext">
                                            <h2>NT$1388/月</h2>
                                            <h2>每月自動扣款</h2>
                                        </div>
{{--                                        <a class="new_gvip_input"  onclick="c2()">購買</a>--}}
                                        <form class="m-form m-form--fit" action="{{ route('upgradepay_ec') }}" method=post>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                            <input type="hidden" name="userId" value="{{$user->id}}">
                                            <input type="hidden" name="type" value="cc_monthly_payment">
                                            <button type="submit" class="new_gvip_input cc_monthly_payment paySubmit" style="border-style: none; outline: none;">信用卡</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>

                            <div class="new_xline"></div>
                            <div class="dq_fangan">單筆付款，短期VIP體驗</div>
                            <div class="new_viplist">
                                <ul>
                                    <li>
                                        <div class="new_fa">單季支付 NT</div>
                                        <div class="new_fanext">
                                            <h2>NT$988/月</h2>
                                            <h2>單季體驗</h2>
                                            <div class="new_abg">
                                                <span>
                                                    <form class="m-form m-form--fit" action="{{ route('upgradepay_ec') }}" method=post>
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                                        <input type="hidden" name="userId" value="{{$user->id}}">
                                                        <input type="hidden" name="type" value="one_quarter_payment">
                                                        <input type="hidden" name="choosePayment" value="ATM">
                                                        <button type="submit" class="new_vpadd one_quarter_payment paySubmit" style="border-style: none; outline: none;">ATM繳費</button>
                                                    </form>
                                                </span>
                                                <span>
                                                    <form class="m-form m-form--fit" action="{{ route('upgradepay_ec') }}" method=post>
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                                        <input type="hidden" name="userId" value="{{$user->id}}">
                                                        <input type="hidden" name="type" value="one_quarter_payment">
                                                        <input type="hidden" name="choosePayment" value="Credit">
                                                        <button type="submit" class="new_vpadd one_quarter_payment paySubmit" style="border-style: none; outline: none;">信用卡</button>
                                                    </form>
                                                </span>
{{--                                                <span>--}}
{{--                                                    <form class="m-form m-form--fit" action="{{ route('upgradepay_ec') }}" method=post>--}}
{{--                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" >--}}
{{--                                                        <input type="hidden" name="userId" value="{{$user->id}}">--}}
{{--                                                        <input type="hidden" name="type" value="one_quarter_payment">--}}
{{--                                                        <input type="hidden" name="choosePayment" value="BARCODE">--}}
{{--                                                        <button type="submit" class="new_vpadd one_quarter_payment paySubmit" style="border-style: none; outline: none;">超商條碼</button>--}}
{{--                                                    </form>--}}
{{--                                                </span>--}}
                                                <font class="new_w100">
                                                    <form class="m-form m-form--fit" action="{{ route('upgradepay_ec') }}" method=post>
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                                        <input type="hidden" name="userId" value="{{$user->id}}">
                                                        <input type="hidden" name="type" value="one_quarter_payment">
                                                        <input type="hidden" name="choosePayment" value="CVS">
                                                        <button type="submit" class="new_vpadd one_quarter_payment paySubmit" style="border-style: none; outline: none;">超商代碼或條碼</button>
                                                    </form>
                                                </font>

                                            </div>
                                        </div>
{{--                                        <form class="m-form m-form--fit" action="{{ route('upgradepay_ec') }}" method=post>--}}
{{--                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" >--}}
{{--                                            <input type="hidden" name="userId" value="{{$user->id}}">--}}
{{--                                            <input type="hidden" name="type" value="one_quarter_payment">--}}
{{--                                            <button type="submit" class="new_gvip_input one_quarter_payment" style="border-style: none; outline: none;">購買</button>--}}
{{--                                        </form>--}}
                                    </li>
                                    <li>
                                        <div class="new_fa">單月支付 NT</div>
                                        <div class="new_fanext">
                                            <h2>NT$1388/月</h2>
                                            <h2>單月體驗</h2>
                                            <div class="new_abg">
                                                <span>
                                                    <form class="m-form m-form--fit" action="{{ route('upgradepay_ec') }}" method=post>
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                                        <input type="hidden" name="userId" value="{{$user->id}}">
                                                        <input type="hidden" name="type" value="one_month_payment">
                                                        <input type="hidden" name="choosePayment" value="ATM">
                                                        <button class="new_vpadd one_month_payment paySubmit" style="border-style: none; outline: none;">ATM繳費</button>
                                                    </form>
                                                </span>
                                                <span>
                                                    <form class="m-form m-form--fit" action="{{ route('upgradepay_ec') }}" method=post>
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                                        <input type="hidden" name="userId" value="{{$user->id}}">
                                                        <input type="hidden" name="type" value="one_month_payment">
                                                        <input type="hidden" name="choosePayment" value="Credit">
                                                        <button class="new_vpadd one_month_payment paySubmit" style="border-style: none; outline: none;">信用卡</button>
                                                    </form>
                                                </span>
{{--                                                <span>--}}
{{--                                                    <form class="m-form m-form--fit" action="{{ route('upgradepay_ec') }}" method=post>--}}
{{--                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" >--}}
{{--                                                        <input type="hidden" name="userId" value="{{$user->id}}">--}}
{{--                                                        <input type="hidden" name="type" value="one_month_payment">--}}
{{--                                                        <input type="hidden" name="choosePayment" value="BARCODE">--}}
{{--                                                        <button class="new_vpadd one_month_payment paySubmit" style="border-style: none; outline: none;">超商條碼</button>--}}
{{--                                                    </form>--}}
{{--                                                </span>--}}
                                                <font class="new_w100">
                                                    <form class="m-form m-form--fit" action="{{ route('upgradepay_ec') }}" method=post>
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                                        <input type="hidden" name="userId" value="{{$user->id}}">
                                                        <input type="hidden" name="type" value="one_month_payment">
                                                        <input type="hidden" name="choosePayment" value="CVS">
                                                        <button class="new_vpadd one_month_payment paySubmit" style="border-style: none; outline: none;">超商代碼或條碼</button>
                                                    </form>
                                                </font>

                                            </div>
                                        </div>
{{--                                        <form class="m-form m-form--fit" action="{{ route('upgradepay_ec') }}" method=post>--}}
{{--                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" >--}}
{{--                                            <input type="hidden" name="userId" value="{{$user->id}}">--}}
{{--                                            <input type="hidden" name="type" value="one_month_payment">--}}
{{--                                            <button class="new_gvip_input one_month_payment" style="border-style: none; outline: none;">購買</button>--}}
{{--                                        </form>--}}
                                    </li>
                                </ul>
                            </div>
                            <div class="vipline matop20"><img src="/new/images/VIP_05.png"></div>
                        </div>
                        <div class="vipbongn">
                            {!!  $vip_text  !!}
                        </div>
                        <div class="n_vipbotf">本筆款項在信用卡帳單顯示為 信宏資產管理公司</div>
                        {{-- cancel vip --}}
                        <div class="hy_width n_viptop20"  id="vip_cancel" style="display:none">
                            <div class="fi_xq">
                                <img src="/new/images/vip_xq.png" class="fi_xqicon">
                                <div class="fi_text">
                                    <h2>@if(!$user->isVip())您目前尚未成為VIP會員 @elseif($user->isVipNotCanceledNotOnePayment() == false && !$user->isVipOnePaymentNotExpire())已經取消VIP @elseif($user->isVipOnePaymentNotExpire())單次VIP會員@endif</h2>
                                    <h3>
                                        @if(($user->isVipNotCanceledNotOnePayment() == false || $user->isVipOnePaymentNotExpire() )&& $days>0)
                                            還剩{{$days}}天可使用
                                        @endif
                                    </h3>
                                    <h4>@if(isset($expiry_time)){{substr($expiry_time,0,10)}}日到期@endif</h4>
                                </div>
                            </div>
                        </div>

                        <div class="de_input n_viptop20 n_viphig"  id="vip2" style="display:none">
                            @if ($user->isVip() && !$user->isVipOnePaymentNotExpire())
                            <form class="m-login__form m-form" method="POST" action="/dashboard/cancelpay">
                                {!! csrf_field() !!}
                            <div class="de_input01 dlmarbot"><div class="de_img"><img src="/new/images/lo_03.png"></div><input name="email" type="text" class="d_input" placeholder="帳號 (您的Email)"></div>
                            <div class="de_input01 dlmarbot"><div class="de_img"><img src="/new/images/lo_11.png"></div><input name="password" type="password" class="d_input" placeholder="密碼"></div>
                            <button class="dlbut" type="submit" style="border-style: none;">確認</button>
                            </form>
                            @endif
                        </div>
                        {{-- cancel vip end --}}

                    </div>

                </div>
            </div>
        </div>
    </div>

@stop

@section('javascript')
    <script>

        function changediv(id){
            document.getElementById("vip").style.display="none";
            document.getElementById("vip2").style.display="none";
            document.getElementById("vip_a").className="";
            document.getElementById("vip2_a").className="";
            document.getElementById(id).style.display="table";
            document.getElementById(id+"_a").className="n_viphover";
            $('#vip_cancel').hide();
            if(id === 'vip2'){
                @if (!$user->isVip() && !$user->isFreeVip())
                c2('您目前尚未成為VIP會員');
                @elseif($user->isFreeVip())
                c2('您是免費VIP，刪除您的大頭照或生活照少於三張就會取消VIP');
                @elseif(!$user->isVipNotCanceledNotOnePayment() || $user->isVipOnePaymentNotExpire())
                $('#vip_cancel').show();
                $('#vip2').hide();
                @endif
            }
            return false;
        }

        $(document).ready(function() {
            @if(Session::has('message'))
            c5("{{Session::get('message')}}");
            <?php session()->forget('message');?>
            @endif
        });

        if(window.location.hash.substr(1) != '' && window.location.hash.substr(1)=='vipcanceled'){
            changediv('vip2');
        }


        // 升級VIP內容
        $('.n_vip01').on('click', function(event) {
            @if($user->isVipNotCanceledNotOnePayment() == true)
            c2('您目前已是VIP會員');
            return false;
            @else

            var r = confirm("{{ $upgrade_vip }}");
            
            if(!r) {
                event.preventDefault();
            }
            @endif

        });

        $('.paySubmit').on('click', function(event) {
{{--            @if($user->isVipNotCanceledORCanceledButNotExpire())--}}
{{--                c2('您目前已是VIP會員');--}}
{{--                return false;--}}
{{--            @else--}}

                if($(this).hasClass("cc_monthly_payment")) {
                    @if(($user->isVipNotCanceledNotOnePayment() || $user->isVipNotOnePaymentNotExpiry() || $user->isVipOnePaymentNotExpire())  && !$user->isFreeVip())
                        c2('您目前已是VIP會員');
                        return false;
                    @else
                        var r = confirm("{{ $cc_monthly_payment }}");
                    @endif
                }else if($(this).hasClass("cc_quarterly_payment")){
                    @if(($user->isVipNotCanceledNotOnePayment() || $user->isVipNotOnePaymentNotExpiry() || $user->isVipOnePaymentNotExpire())  && !$user->isFreeVip())
                        c2('您目前已是VIP會員');
                        return false;
                    @else
                        var r = confirm("{{ $cc_quarterly_payment }}");
                    @endif
                }else if($(this).hasClass("one_month_payment")){
                    //定期定額會員無法購買單次方案
                    @if($user->isVipNotCanceledNotOnePayment() && !$user->isVipOnePaymentNotExpire() && !$user->isFreeVip())
                        c2('您目前已是VIP會員');
                        return false;
                    @else
                        var r = confirm("{{ $one_month_payment }}");
                    @endif
                }else if($(this).hasClass("one_quarter_payment")){
                    //定期定額會員無法購買單次方案
                    @if($user->isVipNotCanceledNotOnePayment() && !$user->isVipOnePaymentNotExpire() && !$user->isFreeVip())
                        c2('您目前已是VIP會員');
                        return false;
                    @else
                        var r = confirm("{{ $one_quarter_payment }}");
                    @endif
                }

                if(!r) {
                    event.preventDefault();
                }
{{--            @endif--}}
        });


        // 取消VIP內容
        $('#vip2_a').on('click', function(event) {
            @if(!$user->isFreeVip())
                @if(isset($vipLessThan7days) && $vipLessThan7days && $user->isVipNotCanceledNotOnePayment())
                    var r = confirm("{{$cancel_vip}}");
                    // var r= confirm('123');
                @elseif($user->isVip() && $user->isVipNotCanceledNotOnePayment() && !$user->isVipOnePaymentNotExpire())
                    var r = confirm("{{$cancel_vip}}");
            // var r= confirm('123');
                @endif
                if(!r) {
                   changediv('vip');
                    // event.preventDefault();
                }
            @endif
        });

        $('#pay_back').on('click',function(){
           $('.part1').hide();
           $('.part2').show();
           $('#qty').val('');
        });

        $('#qty').on('change',function(){
            if($('#qty').val() < 1){
                c3('至少1次');
            }else {
                $('.bka_cor').text($('#qty').val() * 888);
                $('#amount').val($('#qty').val() * 888);
            }
            // alert($('#qty').val());
        });
        function payback_submit(){
            if($('#qty').val() < 1){
                c3('請輸入次數');
            }else{
                $('#payback_form').submit();
            }
        }

    </script>
@stop
