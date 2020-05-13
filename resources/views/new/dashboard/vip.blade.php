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
                        <li><a href="{!! url('dashboard') !!}"><img src="/new/images/mm_03.png"><span>基本資料</span></a></li>
                        <li><a href="{!! url('dashboard_img') !!}"><img src="/new/images/mm_05.png"><span>照片管理</span></a></li>
                        <li><a href="{!! url('/dashboard/password') !!}"><img src="/new/images/mm_07.png"><span>更改密碼</span></a></li>
                        <li><a href="{!! url('/dashboard/vip') !!}"><img src="/new/images/mm_18.png"><span>VIP</span></a></li>
                    </div>
                    <div class="n_viptitle">
                        <a href="#" onclick='return changediv("vip")' id="vip_a" class="n_viphover" target=_parent>升级VIP</a>

                        <a href="#" onclick='return changediv("vip2")' id="vip2_a" target=_parent>取消VIP</a>
                    </div>
                    <div class="n_sjvip"  id="vip">
                        <div class="vipline"><img src="/new/images/VIP_05.png"></div>
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
                                        <h5><b>即可免費成為VIP</b></h5>
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
                        <div class="n_vipbut">
                            <span>
{{--                                <a href="" class="n_vip01 v_butleft">購買方式1</a>--}}
                                <form class="m-form m-form--fit" action="{{ route('upgradepay_ec') }}" method=post>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                <input type="hidden" name="userId" value="{{$user->id}}">
                                        <button type="submit" class="n_vip01 v_butleft" style="border-style: none;">購買方式1</button>
                                </form>
                            </span>
                            <span>
{{--                                <a href="" class="n_vip01 v_butright">購買方式2</a>--}}
                                <?php
                                    $orderNumber = \App\Models\Vip::lastid() . $user->id;
                                    $code = Config::get('social.payment.code');
                                ?>
                                <form class="m-form m-form--fit" action="{{ Config::get('social.payment.actionURL') }}" method=post onsubmit="return logFormData(this);">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                    <input type="hidden" name="userId" value="{{ $user->id }}">
                                    <input type=hidden name="ReturnURL" value="{{ Config::get('social.payment.returnURL') }}">
                                    <input type=hidden name="OrderURL" value="{{ Config::get('social.payment.orderURL') }}">
                                    <input type=hidden name="MerchantNumber" value="761404">
                                    <input type=hidden name="OrderNumber"    value="{{ "30".$orderNumber }}">
                                    <input type=hidden name="OrgOrderNumber" value="SG-VIP({{$user->id}})">
                                    <input type=hidden name="ApproveFlag" value="1">
                                    <input type=hidden name="DepositFlag" value="1">
                                    <input type=hidden name="iphonepage" value="0">
                                    <input type=hidden name="Period" value="30">
                                    <input type=hidden name="Amount" value="{{ Config::get('social.payment.vip-amount') }}">
                                    <input type=hidden name="op" value="AcceptPayment">
                                    <input type=hidden name="checksum" value="{{ md5("761404"."30".$orderNumber.$code.Config::get('social.payment.vip-amount')) }}">
                                    <input type=hidden name="Englishmode" value="0">

                                    <button type="submit" class="n_vip01 v_butright" style="border-style: none;">購買方式2</button>
                                </form>

                            </span>
                        </div>
                        <div class="vipline"><img src="/new/images/VIP_05.png"></div>
                        <div class="vipbongn">
                            {!!  $vip_text  !!}
                        </div>
                        <div class="n_vipbotf">本筆款項在信用卡帳單顯示為 信宏資產管理公司</div>

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

@stop

@section('javascript')
    <script>
        // 升級VIP內容
        $('.n_vip01').on('click', function(event) {
            var r = confirm("{{ $upgrade_vip }}");
            if(!r) {
                event.preventDefault();
            }
        });

        // 取消VIP內容
        $('#vip2_a').on('click', function(event) {
            @if(isset($vipLessThan7days) && $vipLessThan7days)
                var r = confirm("取消 VIP 須知。\n●最短租期為「30天」，若住戶申請到退租時間未滿「30天」，則將被收取「30天」的費用。\n★取消 VIP 時間需要七個工作天，如下個月不續約請提前取消，以免權益受損！★\n★★若取消時間低於七個工作天，則下個月將會繼續扣款，並且 VIP 時間延長至下下個月為止。★★\n★★由於您於本日" + "{{ \Carbon\Carbon::now()->toDateString() }}" + "申請取消 VIP 。您每月的 VIP 扣款日期為 " + "{{ $vipRenewDay }}" + " 日。取消扣款作業需七個工作天(申請VIP時有提示)，本月取消作業不及，下個月將會進行最後一次扣款，您的 VIP 時間將會到 " + "{{ $vipNextMonth->toDateString() }}" + "。不便之處敬請見諒。★★");
            @else
                var r = confirm("取消 VIP 須知。\n●最短租期為「30天」，若住戶申請到退租時間未滿「30天」，則將被收取「30天」的費用。\n★取消 VIP 時間需要七個工作天，如下個月不續約請提前取消，以免權益受損！★\n★★若取消時間低於七個工作天，則下個月將會繼續扣款，並且 VIP 時間延長至下下個月為止。★★");
            @endif

            if(!r) {
                event.preventDefault();
            }
        });

        function changediv(id){
            document.getElementById("vip").style.display="none";
            document.getElementById("vip2").style.display="none";
            document.getElementById("vip_a").className="";
            document.getElementById("vip2_a").className="";
            document.getElementById(id).style.display="table";
            document.getElementById(id+"_a").className="n_viphover";
            if(id === 'vip2'){
                @if (!$user->isVip())
                c2('您目前尚未成為VIP會員');
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
