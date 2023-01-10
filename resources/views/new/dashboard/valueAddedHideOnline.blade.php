@extends('new.layouts.website')
@section('style')
    <style>
        .barcode_content{
            display: none;
        }
        .barcode_style{
            color: black;
            font-size: 16px;
            width: auto;
            height: auto;
            background-color: white;
            line-height: 2.3;
            padding: 5px;
        }
        .barcode_cvs{
            padding-bottom: 5px;
        }
        .barcode_cvs>img{
            height: 30px;
        }
    </style>
@endsection
@section('app-content')
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="g_password">
                    <div class="g_pwicon">
                        <li><a href="@if($user->isVVIP()) /dashboard/viewuser_vvip/{{$user->id}} @else /dashboard/viewuser/{{$user->id}} @endif" class="g_pwicon_t5 "><span>自我預覽</span></a></li>
                        <li><a href="{!! url('dashboard') !!}" class="g_pwicon_t "><span>基本資料</span></a></li>
                        <li><a href="{!! url('dashboard_img') !!}" class="g_pwicon_t2"><span>照片管理</span></a></li>
                        <li><a href="{!! url('dashboard/account_manage') !!}" class="g_pwicon_t3 "><span>帳號設定</span></a></li>
{{--                        <li><a href="{!! url('dashboard/vipSelect') !!}" class="g_pwicon_t4 g_hicon4"><span>升級付費</span></a></li>--}}
                    </div>
                    <div class="new_viphig">
                        <div class="n_viptitle">
                            <a href="#" onclick='changediv("vip")' id="vip_a" class="n_viphover" target=_parent>付費隱藏</a>

                            <a href="#" onclick='changediv("vip2")' id="vip2_a" target=_parent>取消隱藏</a>
                        </div>
                        <div class="n_sjvip" id="vip" style="display: table;">

                            <div class="vipline"><img src="/new/images/VIP_05.png"></div>
                            <div class="dq_fangan">信用卡隱藏上線功能自動續約扣款
                            </div>
                            <div class="new_viplist" style="margin-bottom: 10px;">
                                <ul>
                                    <li>
                                        <div class="vipcion"><img src="/new/images/bicontop.png"></div>
                                        <div class="new_fa">每季支付</div>
                                        <div class="new_fanext">
                                            <h2>NT$388/月</h2>
                                            <h2 class="new_fontd">每季自動扣款</h2>
                                        </div>
                                        <form id="cc_quarterly_paymentForm" class="m-form m-form--fit" action="{{ route('valueAddedService_ec') }}" method=post>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                            <input type="hidden" name="userId" value="{{$user->id}}">
                                            <input type="hidden" name="payment" value="cc_quarterly_payment">
                                            <input type="hidden" name="service_name" value="hideOnline">
                                            <input type="hidden" name="amount" value="1164">
                                            <input type="hidden" name="remainDays" value="@if($isPaidOnePayment == 1 && $days>0){{$days}}@endif">
                                            <button type="submit" class="new_gvip_input cc_quarterly_payment paySubmit" style="border-style: none; outline: none;">信用卡</button>
                                        </form>
{{--                                        <a class="new_gvip_input" onclick="">信用卡</a>--}}
                                    </li>
                                    <li>
                                        <div class="new_fa">每月支付</div>
                                        <div class="new_fanext">
                                            <h2>NT$688/月</h2>
                                            <h2 class="new_fontd">每月自動扣款</h2>
                                        </div>
                                        <form id="cc_monthly_paymentForm" class="m-form m-form--fit" action="{{ route('valueAddedService_ec') }}" method=post>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                            <input type="hidden" name="userId" value="{{$user->id}}">
                                            <input type="hidden" name="payment" value="cc_monthly_payment">
                                            <input type="hidden" name="service_name" value="hideOnline">
                                            <input type="hidden" name="amount" value="688">
                                            <input type="hidden" name="remainDays" value="@if($isPaidOnePayment == 1 && $days>0){{$days}}@endif">
                                            <button type="submit" class="new_gvip_input cc_monthly_payment paySubmit" style="border-style: none; outline: none;">信用卡</button>
                                        </form>
{{--                                        <a class="new_gvip_input" onclick="">信用卡</a>--}}
                                    </li>
                                </ul>
                            </div>

                            <div class="new_xline"></div>
                            <div class="dq_fangan">單筆付款，隱藏上線功能體驗</div>
                            <div class="new_viplist">
                                <ul>
                                    <li>
                                        <div class="new_fa">單季支付</div>
                                        <div class="new_fanext">
                                            <h2>NT$388/月</h2>
                                            <h2>單季體驗</h2>
                                            <div class="new_abg ne_text">
                                                <span>
                                                    <form id="one_quarter_paymentATMForm" class="m-form m-form--fit" action="{{ route('valueAddedService_ec') }}" method=post>
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                                        <input type="hidden" name="userId" value="{{$user->id}}">
                                                        <input type="hidden" name="payment" value="one_quarter_payment">
                                                        <input type="hidden" name="choosePayment" value="ATM">
                                                        <input type="hidden" name="service_name" value="hideOnline">
                                                        <input type="hidden" name="amount" value="1164">
                                                        @php
                                                            $codeNoPaidGetId = \App\Models\PaymentGetQrcodeLog::codeNoPaidGetId($user->id, 'hideOnline', 'ATM', 'one_quarter_payment');
                                                        @endphp
                                                        @if($codeNoPaidGetId != '')
                                                            @php
                                                                $data = \App\Models\PaymentGetQrcodeLog::findDataById($codeNoPaidGetId);
                                                                if(\App\Services\EnvironmentService::isLocalOrTestMachine()){
                                                                    $envStr = '_test';
                                                                }
                                                                else{
                                                                    $envStr = '';
                                                                }
                                                                $CVSBarCodeURL = Config::get('ecpay.payment' . $envStr . '.CVSBarCodeURL').$data->PaymentNo;

                                                            @endphp
                                                            <div class="barcode_content" id="code_{{$codeNoPaidGetId}}">
                                                                    @if(isset($data))
                                                                    您已經取號過, <br>請轉帳至下列ATM繳費帳號即可
                                                                    <div class="barcode_style">
                                                                            <div>銀行代碼：{{$data->BankCode}}</div>
                                                                            <div>帳號：{{$data->vAccount}}</div>
                                                                        </div>
                                                                    繳費期限：{{$data->ExpireDate}}
                                                                @endif
                                                                </div>
                                                            <div class="new_vpadd payAlert"
                                                                 data-id="{{$codeNoPaidGetId}}"
                                                                 style="border-style: none; outline: none; word-break: break-all; white-space: nowrap;">ATM
                                                                </div>
                                                        @else
                                                            <button type="submit" class="new_vpadd one_quarter_payment paySubmit" style="border-style: none; outline: none;">ATM</button>
                                                        @endif
                                                    </form>
                                                </span>
                                                <span>
                                                    <form id="one_quarter_paymentCreditForm" class="m-form m-form--fit" action="{{ route('valueAddedService_ec') }}" method=post>
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                                        <input type="hidden" name="userId" value="{{$user->id}}">
                                                        <input type="hidden" name="payment" value="one_quarter_payment">
                                                        <input type="hidden" name="choosePayment" value="Credit">
                                                        <input type="hidden" name="service_name" value="hideOnline">
                                                        <input type="hidden" name="amount" value="1164">
                                                        <button type="submit" class="new_vpadd one_quarter_payment paySubmit" style="border-style: none; outline: none;">信用卡</button>
                                                    </form>
{{--                                                    <a class="new_vpadd">WebATM</a>--}}
                                                </span>
                                                <span class="new_w100">
                                                    <form id="one_quarter_paymentCVSForm" class="m-form m-form--fit" action="{{ route('valueAddedService_ec') }}" method=post>
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                                        <input type="hidden" name="userId" value="{{$user->id}}">
                                                        <input type="hidden" name="payment" value="one_quarter_payment">
                                                        <input type="hidden" name="choosePayment" value="CVS">
                                                        <input type="hidden" name="service_name" value="hideOnline">
                                                        <input type="hidden" name="amount" value="1164">
                                                        @php
                                                            $codeNoPaidGetId = \App\Models\PaymentGetQrcodeLog::codeNoPaidGetId($user->id, 'hideOnline', 'CVS', 'one_quarter_payment');
                                                        @endphp
                                                        @if($codeNoPaidGetId != '')
                                                            @php
                                                                $data = \App\Models\PaymentGetQrcodeLog::findDataById($codeNoPaidGetId);
                                                                if(\App\Services\EnvironmentService::isLocalOrTestMachine()){
                                                                    $envStr = '_test';
                                                                }
                                                                else{
                                                                    $envStr = '';
                                                                }
                                                                $CVSBarCodeURL = Config::get('ecpay.payment' . $envStr . '.CVSBarCodeURL').$data->PaymentNo;

                                                            @endphp
                                                            <div class="barcode_content" id="code_{{$codeNoPaidGetId}}">
                                                                    @if(isset($data))
                                                                    您已經取號過, <br>請直接點選下列超商代碼繳費即可
                                                                    <div class="barcode_style">
                                                                            <a href="{{$CVSBarCodeURL}}" target="_blank">{{$data->PaymentNo}}</a>
                                                                        </div>
                                                                    繳費期限：{{$data->ExpireDate}}
                                                                @endif
                                                                </div>
                                                            <div class="new_vpadd payAlert"
                                                                 data-id="{{$codeNoPaidGetId}}"
                                                                 style="border-style: none; outline: none; word-break: break-all; white-space: nowrap;">超商代碼
                                                                </div>
                                                        @else
                                                            <button type="submit" class="new_vpadd one_quarter_payment paySubmit" style="border-style: none; outline: none; word-break: break-all; white-space: nowrap;">超商代碼</button>
                                                        @endif
                                                    </form>
                                                </span>
                                                <span class="new_w100">
                                                    <form id="one_quarter_paymentBARCODEForm" class="m-form m-form--fit" action="{{ route('valueAddedService_ec') }}" method=post>
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                                        <input type="hidden" name="userId" value="{{$user->id}}">
                                                        <input type="hidden" name="payment" value="one_quarter_payment">
                                                        <input type="hidden" name="choosePayment" value="BARCODE">
                                                        <input type="hidden" name="service_name" value="hideOnline">
                                                        <input type="hidden" name="amount" value="1164">
                                                        @php
                                                            $codeNoPaidGetId = \App\Models\PaymentGetQrcodeLog::codeNoPaidGetId($user->id, 'hideOnline', 'BARCODE', 'one_quarter_payment');
                                                        @endphp
                                                        @if($codeNoPaidGetId != '')
                                                            @php
                                                                $data = \App\Models\PaymentGetQrcodeLog::findDataById($codeNoPaidGetId);
                                                            @endphp
                                                            <div class="barcode_content" id="code_{{$codeNoPaidGetId}}">
                                                                    @if(isset($data))
                                                                    您已經取號過, <br>請直接使用下列超商條碼繳費即可
                                                                    <div class="barcode_style">
                                                                            <div class="barcode_cvs"><img src="/new/images/payment_1.jpg"></div>
                                                                            <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($data->Barcode1, 'C39', 1, 33, array(1,1,1), true)}}" alt="barcode" /><br>
                                                                            <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($data->Barcode2, 'C39', 1, 33, array(1,1,1), true)}}" alt="barcode" /><br>
                                                                            <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($data->Barcode3, 'C39', 1, 33, array(1,1,1), true)}}" alt="barcode" />
                                                                        </div>
                                                                    繳費期限：{{$data->ExpireDate}}
                                                                @endif
                                                                </div>
                                                            <div class="new_vpadd payAlert"
                                                                 data-id="{{$codeNoPaidGetId}}"
                                                                 style="border-style: none; outline: none; word-break: break-all; white-space: nowrap;">超商條碼
                                                                </div>
                                                        @else
                                                            <button type="submit" class="new_vpadd one_quarter_payment paySubmit" style="border-style: none; outline: none; word-break: break-all; white-space: nowrap;">超商條碼</button>
                                                        @endif
                                                    </form>
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="new_fa">單月支付</div>
                                        <div class="new_fanext">
                                            <h2>NT$688/月</h2>
                                            <h2>單月體驗</h2>
                                            <div class="new_abg ne_text">
                                                <span>
                                                    <form id="one_month_paymentATMForm" class="m-form m-form--fit" action="{{ route('valueAddedService_ec') }}" method=post>
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                                        <input type="hidden" name="userId" value="{{$user->id}}">
                                                        <input type="hidden" name="payment" value="one_month_payment">
                                                        <input type="hidden" name="choosePayment" value="ATM">
                                                        <input type="hidden" name="service_name" value="hideOnline">
                                                        <input type="hidden" name="amount" value="688">
                                                        @php
                                                            $codeNoPaidGetId = \App\Models\PaymentGetQrcodeLog::codeNoPaidGetId($user->id, 'hideOnline', 'ATM', 'one_month_payment');
                                                        @endphp
                                                        @if($codeNoPaidGetId != '')
                                                            @php
                                                                $data = \App\Models\PaymentGetQrcodeLog::findDataById($codeNoPaidGetId);
                                                                if(\App\Services\EnvironmentService::isLocalOrTestMachine()){
                                                                    $envStr = '_test';
                                                                }
                                                                else{
                                                                    $envStr = '';
                                                                }
                                                                $CVSBarCodeURL = Config::get('ecpay.payment' . $envStr . '.CVSBarCodeURL').$data->PaymentNo;

                                                            @endphp
                                                            <div class="barcode_content" id="code_{{$codeNoPaidGetId}}">
                                                                    @if(isset($data))
                                                                    您已經取號過, <br>請轉帳至下列ATM繳費帳號即可
                                                                    <div class="barcode_style">
                                                                            <div>銀行代碼：{{$data->BankCode}}</div>
                                                                            <div>帳號：{{$data->vAccount}}</div>
                                                                        </div>
                                                                    繳費期限：{{$data->ExpireDate}}
                                                                @endif
                                                                </div>
                                                            <div class="new_vpadd payAlert"
                                                                 data-id="{{$codeNoPaidGetId}}"
                                                                 style="border-style: none; outline: none; word-break: break-all; white-space: nowrap;">ATM
                                                                </div>
                                                        @else
                                                            <button class="new_vpadd one_month_payment paySubmit" style="border-style: none; outline: none;">ATM</button>
                                                        @endif
                                                    </form>
{{--                                                    <a class="new_vpadd">ATM繳費</a>--}}
                                                </span>
                                                <span>
                                                    <form id="one_month_paymentCreditForm" class="m-form m-form--fit" action="{{ route('valueAddedService_ec') }}" method=post>
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                                        <input type="hidden" name="userId" value="{{$user->id}}">
                                                        <input type="hidden" name="payment" value="one_month_payment">
                                                        <input type="hidden" name="choosePayment" value="Credit">
                                                        <input type="hidden" name="service_name" value="hideOnline">
                                                        <input type="hidden" name="amount" value="688">
                                                        <button class="new_vpadd one_month_payment paySubmit" style="border-style: none; outline: none;">信用卡</button>
                                                    </form>
{{--                                                    <a class="new_vpadd">WebATM</a>--}}
                                                </span>
                                                <span class="new_w100">
                                                    <form id="one_month_paymentCVSForm" class="m-form m-form--fit" action="{{ route('valueAddedService_ec') }}" method=post>
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                                        <input type="hidden" name="userId" value="{{$user->id}}">
                                                        <input type="hidden" name="payment" value="one_month_payment">
                                                        <input type="hidden" name="choosePayment" value="CVS">
                                                        <input type="hidden" name="service_name" value="hideOnline">
                                                        <input type="hidden" name="amount" value="688">
                                                        @php
                                                            $codeNoPaidGetId = \App\Models\PaymentGetQrcodeLog::codeNoPaidGetId($user->id, 'hideOnline', 'CVS', 'one_month_payment');
                                                        @endphp
                                                        @if($codeNoPaidGetId != '')
                                                            @php
                                                                $data = \App\Models\PaymentGetQrcodeLog::findDataById($codeNoPaidGetId);
                                                                if(\App\Services\EnvironmentService::isLocalOrTestMachine()){
                                                                    $envStr = '_test';
                                                                }
                                                                else{
                                                                    $envStr = '';
                                                                }
                                                                $CVSBarCodeURL = Config::get('ecpay.payment' . $envStr . '.CVSBarCodeURL').$data->PaymentNo;

                                                            @endphp
                                                            <div class="barcode_content" id="code_{{$codeNoPaidGetId}}">
                                                                    @if(isset($data))
                                                                    您已經取號過, <br>請直接點選下列超商代碼繳費即可
                                                                    <div class="barcode_style">
                                                                            <a href="{{$CVSBarCodeURL}}" target="_blank">{{$data->PaymentNo}}</a>
                                                                        </div>
                                                                    繳費期限：{{$data->ExpireDate}}
                                                                @endif
                                                                </div>
                                                            <div class="new_vpadd payAlert"
                                                                 data-id="{{$codeNoPaidGetId}}"
                                                                 style="border-style: none; outline: none; word-break: break-all; white-space: nowrap;">超商代碼
                                                                </div>
                                                        @else
                                                            <button class="new_vpadd one_month_payment paySubmit" style="border-style: none; outline: none; word-break: break-all; white-space: nowrap;">超商代碼</button>
                                                        @endif
                                                    </form>
                                                </span>
                                                <span class="new_w100">
                                                    <form id="one_month_paymentBARCODEForm" class="m-form m-form--fit" action="{{ route('valueAddedService_ec') }}" method=post>
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                                        <input type="hidden" name="userId" value="{{$user->id}}">
                                                        <input type="hidden" name="payment" value="one_month_payment">
                                                        <input type="hidden" name="choosePayment" value="BARCODE">
                                                        <input type="hidden" name="service_name" value="hideOnline">
                                                        <input type="hidden" name="amount" value="688">
                                                        @php
                                                            $codeNoPaidGetId = \App\Models\PaymentGetQrcodeLog::codeNoPaidGetId($user->id, 'hideOnline', 'BARCODE', 'one_month_payment');
                                                        @endphp
                                                        @if($codeNoPaidGetId != '')
                                                            @php
                                                                $data = \App\Models\PaymentGetQrcodeLog::findDataById($codeNoPaidGetId);
                                                            @endphp
                                                            <div class="barcode_content" id="code_{{$codeNoPaidGetId}}">
                                                                    @if(isset($data))
                                                                    您已經取號過, <br>請直接使用下列超商條碼繳費即可
                                                                    <div class="barcode_style">
                                                                            <div class="barcode_cvs"><img src="/new/images/payment_1.jpg"></div>
                                                                            <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($data->Barcode1, 'C39', 1, 33, array(1,1,1), true)}}" alt="barcode" /><br>
                                                                            <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($data->Barcode2, 'C39', 1, 33, array(1,1,1), true)}}" alt="barcode" /><br>
                                                                            <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($data->Barcode3, 'C39', 1, 33, array(1,1,1), true)}}" alt="barcode" />
                                                                        </div>
                                                                    繳費期限：{{$data->ExpireDate}}
                                                                @endif
                                                                </div>
                                                            <div class="new_vpadd payAlert"
                                                                 data-id="{{$codeNoPaidGetId}}"
                                                                 style="border-style: none; outline: none; word-break: break-all; white-space: nowrap;">超商條碼
                                                                </div>
                                                        @else
                                                            <button class="new_vpadd one_month_payment paySubmit" style="border-style: none; outline: none; word-break: break-all; white-space: nowrap;">超商條碼</button>
                                                        @endif
                                                    </form>
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="vipline matop20"><img src="/new/images/VIP_05.png"></div>

                            <div class="vipbongn">
                                <h2>可獲得以下功能</h2>
                                <h3><span class="left">●</span><var class="newtishi">搜索頁上可自行選擇，是否隱藏最後上線時間</var></h3>
                            </div>
                        </div>
                        <div class="hy_width n_viptop20"  id="vip_cancel" style="display:none">
                            <div class="fi_xq">
                                <img src="/new/images/vip_yc.png" class="fi_xqicon">
                                <div class="fi_text">
                                    <h2>@if($user->valueAddedServiceStatus('hideOnline') != 1)您目前尚未購買付費隱藏@elseif($isPaidCancelNotOnePayment == 1)已經取消隱藏上線功能@elseif($isPaidOnePayment == 1)單次付費隱藏@endif</h2>
                                    <h3>@if(($isPaidCancelNotOnePayment == 1 || $isPaidOnePayment == 1) && $days>0)還剩 {{$days}} 天可使用@endif</h3>
                                    <h4>@if(isset($expiry_time)){{substr($expiry_time,0,10)}}日到期@endif</h4>
                                </div>
                            </div>
                        </div>

                        <div class="de_input n_viptop20 n_viphig"  id="vip2" style="display:none">
                            @if ($user->valueAddedServiceStatus('hideOnline') ==1 && $isPaidCancelNotOnePayment == 0)
                            <form class="m-login__form m-form" method="POST" action="/dashboard/cancelValueAddedService">
                                {!! csrf_field() !!}
                            <div class="de_input01 dlmarbot"><div class="de_img"><img src="/new/images/lo_03.png"></div><input name="email" type="text" class="d_input" placeholder="帳號 (您的Email)"></div>
                            <div class="de_input01 dlmarbot"><div class="de_img"><img src="/new/images/lo_11.png"></div><input name="password" type="password" class="d_input" placeholder="密碼"></div>
                            <input type="hidden" name="service_name" value="hideOnline">
                            <button class="dlbut" type="submit" style="border-style: none;">確認</button>
                            </form>
                            @endif
                        </div>
                        {{-- cancel end --}}

                        <div class="bl bl_tab " id="ff" >
                            <div class="bltitle"><font>上線時不變動搜索排序</font></div>
                            <div class="new_poptk" style="padding: 5%;">
                                <div class="toggle-button-wrapper">
                                    <form id="switch_from" method="post" action="{{ route('hideOnlineSwitch') }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                        <input type="hidden" name="userId" value="{{$user->id}}">
                                        <input type="hidden" id="isHideOnline" name="isHideOnline" value="">
                                        <input id="toggle_input" type="checkbox" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-on="開" data-off="關" data-size="lg">
                                    </form>
                                </div>

                                <h3>如果開啟此功能，那麼在搜索頁上一樣會照登入時間排序，如果關閉此功能，會影響您在如圖所示:</h3>
                                <div class="atk_pic"><img src="/new/images/picjt.png"></div>
                                <div class="n_bbutton">
                                    <span><a class="n_left switch_submit">確定</a></span>
                                    <span><a class="n_right" onclick="$('.blbg').click();">取消</a></span>
                                </div>
                            </div>
                            <a id="" onclick="$('.blbg').click();" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
{{--    @php--}}
{{--    echo $valueAddedServiceStatus.":".$isPaidCancelNotOnePayment.":".$isPaidOnePayment;--}}
{{--    @endphp--}}

@stop

@section('javascript')
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
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

                $('.vipbongn').hide();
                // $('.n_vipbotf').hide();
                @if ($user->valueAddedServiceStatus('hideOnline') == 0)
                c5('您目前尚未購買付費隱藏');
                @elseif($user->valueAddedServiceStatus('hideOnline') == 1 && $isPaidCancelNotOnePayment == 0 && $isPaidOnePayment == 0)
                $('#vip_cancel').hide();
                $('#vip2').show();
                @elseif( $user->valueAddedServiceStatus('hideOnline') == 1 && ($isPaidCancelNotOnePayment == 1 || $isPaidOnePayment == 1) )
                $('#vip_cancel').show();
                $('#vip2').hide();
                @endif
            }else{
                $('.vipbongn').show();
                // $('.n_vipbotf').show();
            }
            return false;
        }

        $(document).ready(function() {
            @if(Session::has('message'))
            c5("{{Session::get('message')}}");
            <?php session()->forget('message');?>
            @endif
        });

        if(window.location.hash.substr(1) != '' && window.location.hash.substr(1)=='valueAddedServiceCanceled'){
            changediv('vip2');
        }

        $('.payAlert').on('click', function(event) {;
            c5('');
            $('.bltext').append($('#code_' + $(this).data('id')).html());
            return false;
        });

        $('.paySubmit').on('click', function(event) {
                var id,choosePayment;
                if($(this).hasClass("cc_monthly_payment")) {
                    @if($user->valueAddedServiceStatus('hideOnline') == 1 && $isPaidOnePayment != 1)
                        c5('您目前已在付費隱藏期間');
                        return false;
                    @else
                        //c4("您確定要購買嗎?");
                        common_confirm('{!! $cc_monthly_payment !!}',"{{$cc_monthly_payment_red}}" +
                            "如同意以上所有條款請點確認，不同意點取消。");
                        id = 'cc_monthly_payment';
                        choosePayment='';
                        $(".n_left").on('click', function() {
                            $('#'+id+choosePayment+'Form').submit();
                        });
                    @endif

                }else if($(this).hasClass("cc_quarterly_payment")){
                    @if($user->valueAddedServiceStatus('hideOnline') == 1 && $isPaidOnePayment != 1)
                        c5('您目前已在付費隱藏期間');
                        return false;
                    @else
                        common_confirm('{!! $cc_quarterly_payment !!}',"{{$cc_quarterly_payment_red}}" +
                        "如同意以上所有條款請點確認，不同意點取消。");
                        id = 'cc_quarterly_payment';
                        choosePayment='';
                        $(".n_left").on('click', function() {
                            $('#'+id+choosePayment+'Form').submit();
                        });
                    @endif

                }else if($(this).hasClass("one_month_payment")){
                    //定期定額會員無法購買單次方案
                    @if($user->valueAddedServiceStatus('hideOnline') == 1 && $isPaidOnePayment != 1)
                        c5('您目前已在付費隱藏期間');
                        return false;
                    @else
                        //c4("您確定要購買嗎?");
                        common_confirm('{!! $one_month_payment !!}',"{{$one_month_payment_red}}" +
                            "如同意以上所有條款請點確認，不同意點取消。");
                        id = 'one_month_payment';
                        choosePayment=$(this).parent().find("input[name='choosePayment']").val();

                        if(choosePayment == 'ATM' || choosePayment == 'CVS' || choosePayment == 'BARCODE'){
                            $(".n_left").on('click', function () {
                                common_confirm("{{$atm_cvs_notice}}","{{$atm_cvs_notice_red}}");
                                $(".n_left").on('click', function () {
                                    $('#' + id + choosePayment + 'Form').submit();
                                });
                            });
                        }else {
                            $(".n_left").on('click', function () {
                                $('#' + id + choosePayment + 'Form').submit();
                            });
                        }

                    @endif
                }else if($(this).hasClass("one_quarter_payment")){
                    //定期定額會員無法購買單次方案
                    @if($user->valueAddedServiceStatus('hideOnline') == 1 && $isPaidOnePayment != 1)
                        c5('您目前已在付費隱藏期間');
                        return false;
                    @else
                        //c4("您確定要購買嗎?");
                        common_confirm('{!! $one_quarter_payment !!}',"{{$one_quarter_payment_red}}" +
                            "如同意以上所有條款請點確認，不同意點取消。");
                        id = 'one_quarter_payment';
                        choosePayment=$(this).parent().find("input[name='choosePayment']").val();

                        if(choosePayment == 'ATM' || choosePayment == 'CVS' || choosePayment == 'BARCODE'){
                            $(".n_left").on('click', function () {
                                common_confirm("{{$atm_cvs_notice}}","{{$atm_cvs_notice_red}}");
                                $(".n_left").on('click', function () {
                                    $('#' + id + choosePayment + 'Form').submit();
                                });
                            });
                        }else {
                            $(".n_left").on('click', function () {
                                $('#' + id + choosePayment + 'Form').submit();
                            });
                        }

                    @endif
                }

            // $(".n_left").on('click', function() {
            //     $('#'+id+choosePayment+'Form').submit();
            // });
            return false;
                // if(!r) {
                //     event.preventDefault();
                // }
        });


        // 取消VIP內容
        $('#vip2_a').on('click', function(event) {
            changediv('vip');
            @if($user->valueAddedServiceStatus('hideOnline') == 1)
                @if($isPaidOnePayment == 0 && $isPaidCancelNotOnePayment == 0)
                    c4("您目前是定期付費隱藏，確定要取消此功能嗎?");
                @elseif($isPaidCancelNotOnePayment == 1 || $isPaidOnePayment == 1)
                    changediv('vip2');
                @endif
                $(".n_left").on('click', function() {
                    $(".blbg").hide();
                    $('#tab04').hide();
                    changediv('vip2');
                });
                // if(!r) {
                //    changediv('vip');
                //     // event.preventDefault();
                // }
            @endif
        });


        function ff() {
            $(".blbg").show();
            $("#ff").show();
            $('body').css("overflow", "hidden");
        }

        $( document ).ready(function() {

            @if($user->meta_()->isHideOnline == 1)
            $('#toggle_input').prop('checked', true).change();
            $('#isHideOnline').val(1);
            @else
            $('#toggle_input').prop('checked', false).change();
            $('#isHideOnline').val(0);
            @endif

            $('#toggle_input').change(function() {
                if($(this).prop('checked')==true){
                    $('#isHideOnline').val(1);
                }else{
                    $('#isHideOnline').val(0);
                }
            });

        });

        $('.switch_submit').on('click', function() {
            $('#switch_from').submit();
        });

    </script>
    <style>
        .btn-lg {
            padding: 5px 10px;
            font-size: unset;
            line-height: 1.3333333;
            border-radius: 6px;
        }
    </style>
{{--    <style type="text/css">--}}
{{--        #toggle-button {--}}
{{--            display: none;--}}
{{--        }--}}

{{--        .button-label {--}}
{{--            position: relative;--}}
{{--            display: inline-block;--}}
{{--            width: 70px;--}}
{{--            height: 30px;--}}
{{--            background-color: #81ce27;--}}
{{--            border-radius: 30px;--}}
{{--            overflow: hidden;--}}
{{--        }--}}

{{--        .circle { cursor: pointer;--}}
{{--            position: absolute;--}}
{{--            top: 1PX;--}}
{{--            left: 0;--}}
{{--            width: 28px;--}}
{{--            height: 28px;--}}
{{--            border-radius: 50%;--}}
{{--            background-color: #fff;--}}
{{--        }--}}

{{--        .button-label .text {--}}
{{--            line-height: 30px;--}}
{{--            font-size: 14px;--}}
{{--        }--}}

{{--        .on {--}}
{{--            color: #fff;--}}
{{--            display: none;--}}
{{--            text-indent: 10px;--}}
{{--        }--}}

{{--        .off {--}}
{{--            color: #fff;--}}
{{--            display: inline-block;--}}
{{--            text-indent: 34px;--}}
{{--        }--}}

{{--        .button-label .circle {--}}
{{--            left: 0;--}}
{{--            transition: all 0.3s;--}}
{{--        }--}}

{{--        #toggle-button:checked+label.button-label .circle {--}}
{{--            left: 40px;--}}
{{--        }--}}

{{--        #toggle-button:checked+label.button-label .on {--}}
{{--            display: inline-block;--}}
{{--        }--}}

{{--        #toggle-button:checked+label.button-label .off {--}}
{{--            display: none;--}}
{{--        }--}}

{{--        #toggle-button:checked+label.button-label {--}}
{{--            background-color: #e2200f;--}}
{{--        }--}}
{{--    </style>--}}
@stop
