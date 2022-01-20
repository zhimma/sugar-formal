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
                        <li><a href="{!! url('/dashboard/vip') !!}" class="g_pwicon_t4 g_hicon4"><span>VIP</span></a></li>
                    </div>
                    <div class="new_viphig">
                    <div class="n_viptitle">
                        <a href="#" onclick='return changediv("vip")' id="vip_a" class="n_viphover" target=_parent>升級VIP</a>

                        <a href="#" onclick='return changediv("vip2")' id="vip2_a" target=_parent>取消VIP</a>
                    </div>
                    <div class="n_sjvip"  id="vip">
                        <div class="vipline"><img src="/new/images/VIP_05.png"></div>
                        <div class="part1">
                        <div class="viplist">
                            <ul>
                                <li>
                                    <div class="vipcent">cooming soon</div>
                                </li>

                                @if($user->engroup==2)
                                <li>
                                    <a href="{!! url('dashboard_img') !!}">
                                    <div class="vipfont viptop15">
                                        <h3>上傳大頭貼</h3>
                                        <h3>+</h3>
                                        <h3>三張生活照</h3>
                                        <h5><b>即可成為VIP</b></h5>
                                    </div>
                                    <div class="vipcion"><img src="/new/images/bicon.png"></div>
                                    </a>
                                </li>
                                @else
                                <li>
                                    <div class="vipfont viptop15">
                                        <h2>888</h2>
                                        <h3>$NTD/每月</h3>
                                    </div>
                                    <div class="vipcion"><img src="/new/images/bicon.png"></div>
                                </li>
                                @endif

                                <li>
                                    <div class="vipcent">cooming soon</div>
                                    <div class="vipcion"></div>
                                </li>
                            </ul>
                        </div>
                        <div class="gvip_input">
                            <span>
{{--                                <a href="" class="n_vip01 v_butleft">購買方式1</a>--}}
                                <form class="m-form m-form--fit" action="{{ route('upgradepay_ec') }}" method=post>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                <input type="hidden" name="userId" value="{{$user->id}}">
                                        <button type="submit" class="gvipbut n_vip01" style="border-style: none;">購買</button>
                                </form>
                            </span>
{{--                            <span>--}}
{{--                                <a href="" class="n_vip01 v_butright">購買方式2</a>--}}
                                <?php
//                                    $orderNumber = \App\Models\Vip::lastid() . $user->id;
//                                    $code = Config::get('social.payment.code');
                                ?>
{{--                                <form class="m-form m-form--fit" action="{{ Config::get('social.payment.actionURL') }}" method=post onsubmit="return logFormData(this);">--}}
{{--                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" >--}}
{{--                                    <input type="hidden" name="userId" value="{{ $user->id }}">--}}
{{--                                    <input type=hidden name="ReturnURL" value="{{ Config::get('social.payment.returnURL') }}">--}}
{{--                                    <input type=hidden name="OrderURL" value="{{ Config::get('social.payment.orderURL') }}">--}}
{{--                                    <input type=hidden name="MerchantNumber" value="761404">--}}
{{--                                    <input type=hidden name="OrderNumber"    value="{{ "30".$orderNumber }}">--}}
{{--                                    <input type=hidden name="OrgOrderNumber" value="SG-VIP({{$user->id}})">--}}
{{--                                    <input type=hidden name="ApproveFlag" value="1">--}}
{{--                                    <input type=hidden name="DepositFlag" value="1">--}}
{{--                                    <input type=hidden name="iphonepage" value="0">--}}
{{--                                    <input type=hidden name="Period" value="30">--}}
{{--                                    <input type=hidden name="Amount" value="{{ Config::get('social.payment.vip-amount') }}">--}}
{{--                                    <input type=hidden name="op" value="AcceptPayment">--}}
{{--                                    <input type=hidden name="checksum" value="{{ md5("761404"."30".$orderNumber.$code.Config::get('social.payment.vip-amount')) }}">--}}
{{--                                    <input type=hidden name="Englishmode" value="0">--}}

{{--                                    <button type="submit" class="gvipbut n_vip01" style="border-style: none;">購買方式2</button>--}}
{{--                                </form>--}}

{{--                            </span>--}}
                            {{-- <a href="javascript:void(0);" class="gvipbut" id="pay_back">補刷卡</a> --}}
                        </div>
                        <div class="vipline"><img src="/new/images/VIP_05.png"></div>
                        <div class="vipbongn">
                            {!!  $vip_text  !!}
{{--                            <h2>VIP功能</h2>--}}
{{--                            <h3><span>●</span>解鎖信箱限制</h3>--}}
{{--                            <h3><span>●</span>解鎖發訊限制</h3>--}}
{{--                            <h3><span>●</span>解鎖足跡功能</h3>--}}
{{--                            <h3><span>●</span>解鎖進階搜尋功能</h3>--}}
{{--                            <h3><span>●</span>解鎖車馬費評價功能</h3>--}}
{{--                            <h3><span>●</span>可以看進階資料</h3>--}}
{{--                            <h3><span>●</span>可以看已讀未讀</h3>--}}
{{--                            <h3><span>●</span>擁有 VIP title 並取得優選會原累積資格</h3>--}}
{{--                            <h3><span>●</span>加入 VIP 以後可以隨時手動取消沒有任何限制，但金流需要七個工作天操作，所以需在下個月扣款前七個工作天取消，次月才不會扣款</h3>--}}
                        </div>
                        <div class="n_vipbotf">本筆款項在信用卡帳單顯示為 信宏資產管理公司</div>
                        </div>
                        <div class="part2" style="display: none;">
                        <div class="bka">您好，這是VIP款項補繳頁面<br>請選擇補繳月費後刷卡即可</div>

                        <div class="bka_input01">
                            <input name="qty" type="number" class="bk_input" id="qty" placeholder="輸入次數"><span>次×888/月=</span>
                            <span class="bk_input bka_cor">888</span><span>元</span>
                        </div>
{{--                        <a href="" class="dlbut">確定</a>--}}
                            <form id="payback_form" class="m-form m-form--fit" action="{{ route('payback_ec') }}" method=post>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                <input type="hidden" name="userId" value="{{$user->id}}">
                                <input type="hidden" name="amount" id="amount" value="">
{{--                                <button type="submit" class="dlbut" style="border-style: none;">確定</button>--}}
                                <a href="javascript:void(0);" class="dlbut" onclick="payback_submit()">確定</a>
                            </form>

                        <div class="vipline matop10"><img src="/new/images/VIP_05.png"></div>
                        <div class="vipbongn">
                            <h2>注意事項</h2>
                            <h3><span>●</span><div class="bka_span">刷卡前請點網站右下連絡我們，與站長確認您的補繳金額，以及可獲得的權益。</div></h3>
                            <h3><span>●</span><div class="bka_span">雙方line對話或者email往來做為交易依據，此處刷卡後不接受退款要求。</div></h3>
                            <h3><span>●</span><div class="bka_span">如不同意切勿於此頁面刷卡。</div></h3>
                        </div>
                        </div>
                    </div>
                    <div class="hy_width n_viptop20"  id="vip_cancel" style="display:none">
                        <div class="fi_xq">
                            <img src="/new/images/vip_xq.png" class="fi_xqicon">
                            <div class="fi_text">
                                <h2>@if(!$user->isVip())您目前尚未成為VIP會員 @elseif($user->isVipNotCanceledORCanceledButNotExpire() == false)已經取消VIP @endif</h2>
                                <h3>
                                    @if($user->isVipNotCanceledORCanceledButNotExpire() == false && $days>0)
                                        還剩{{$days}}天可使用
                                    @endif
                                </h3>
                                <h4>@if(isset($expiry_time)){{substr($expiry_time,0,10)}}日到期@endif</h4>
                            </div>
                        </div>
                    </div>

                    <div class="de_input n_viptop20 n_viphig"  id="vip2" style="display:none">
                        @if ($user->isVip())
                        <form class="m-login__form m-form" method="POST" action="/dashboard/cancelpay">
                            {!! csrf_field() !!}
                        <div class="de_input01 dlmarbot"><div class="de_img"><img src="/new/images/lo_03.png"></div><input name="email" type="text" class="d_input" placeholder="帳號 (您的Email)"></div>
                        <div class="de_input01 dlmarbot"><div class="de_img"><img src="/new/images/lo_11.png"></div><input name="password" type="password" class="d_input" placeholder="密碼"></div>
{{--                        <a href="{!! url('/password/reset') !!}" class="dlpassword">忘記密碼 ?</a>--}}
                        <button class="dlbut" type="submit" style="border-style: none;">確認</button>
                        </form>
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
            @if($user->isVipNotCanceledORCanceledButNotExpire() == true)
            c5('您目前已是VIP會員');
            return false;
            @else

            var r = confirm("{{ $upgrade_vip }}");
            
            if(!r) {
                event.preventDefault();
            }
            @endif

        });

        // 取消VIP內容
        $('#vip2_a').on('click', function(event) {
            @if(!$user->isFreeVip())
                @if(isset($vipLessThan7days) && $vipLessThan7days && $user->isVipNotCanceledORCanceledButNotExpire() == true)
                    var r = confirm("{!! $cancel_vip !!}");
                @elseif($user->isVip() && $user->isVipNotCanceledORCanceledButNotExpire() == true)
                    var r = confirm("{!! $cancel_vip !!}");
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
                c5('至少1次');
            }else {
                $('.bka_cor').text($('#qty').val() * 888);
                $('#amount').val($('#qty').val() * 888);
            }
            // alert($('#qty').val());
        });
        function payback_submit(){
            if($('#qty').val() < 1){
                c5('請輸入次數');
            }else{
                $('#payback_form').submit();
            }

        }
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
                c5('您目前尚未成為VIP會員');
                @elseif($user->isFreeVip())
                show_pop_message('您是免費VIP，刪除您的大頭照或生活照少於三張就會取消VIP');
                @elseif($user->isVipNotCanceledORCanceledButNotExpire() == false)
                $('#vip_cancel').show();
                $('#vip2').hide();
                @endif
            }
            return false;
        }

        // function cl() {
        //     $(".blbg").show()
        //     $("#tab01").show()
        // }
        // function gmBtn1(){
        //     $(".blbg").hide()
        //     $(".gtab").hide()
        // }

        function logFormData(form){
            let data = $(form).serialize();
            $.ajax({
                type: 'POST',
                url: '{{ route('upgradepayLog') }}',
                data: {
                    _token:"{{ csrf_token() }}",
                    data : data
                },
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(xhr, status, error){
                    console.log(xhr);
                    console.log(error);
                },
                error: function(xhr, status, error){
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
            return true;
        }

    </script>
@stop
