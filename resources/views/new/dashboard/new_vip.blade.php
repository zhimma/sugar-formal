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
                            <a  onclick='changediv("vip")' id="vip_a" class="n_viphover" target=_parent>升級VIP</a>

                            <a  onclick='changediv("vip2")' id="vip2_a" target=_parent>取消VIP</a>
                        </div>
                        <div class="n_sjvip"  id="vip">
                            <div class="main_vip">
                            @if($user->engroup==2)
                            <div class="new_syfont">上傳大頭貼+三張生活照,即可免費成為VIP</div>
                            @endif
                            <div class="vipline"><img src="/new/images/VIP_05.png"></div>

                            <div class="dq_fangan">信用卡VIP自動續約扣款</div>
                            <div class="new_viplist" style="margin-bottom: 10px;">
                                <ul>
                                    <li><div class="vipcion"><img src="/new/images/bicontop.png"></div>
                                        <div class="new_fa">每季支付</div>
                                        <div class="new_fanext">
                                            <h2>NT$3988/季</h2>
                                            <h2>每季自動扣款</h2>
                                        </div>
                                        <form id="cc_quarterly_paymentForm" class="m-form m-form--fit" action="{{ route('upgradepay_ec') }}" method=post>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                            <input type="hidden" name="userId" value="{{$user->id}}">
                                            <input type="hidden" name="type" value="cc_quarterly_payment">
                                            <input type="hidden" name="remainDays" value="@if($user->isVipOnePaymentNotExpire() && $days>0){{$days}}@endif">
                                            <input type="hidden" name="paymentFlow" value="cc_quarterly_payment">
                                            <input type="hidden" name="choosePaymentFlow" value="">
                                            <button type="submit" class="new_gvip_input cc_quarterly_payment paySubmit" style="border-style: none; outline: none;">信用卡</button>
                                        </form>
                                    </li>
                                    <li>
                                        <div class="new_fa">每月支付</div>
                                        <div class="new_fanext">
                                            <h2>NT$1899/月</h2>
                                            <h2>每月自動扣款</h2>
                                        </div>
                                        <form id="cc_monthly_paymentForm" class="m-form m-form--fit" action="{{ route('upgradepay_ec') }}" method=post>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                            <input type="hidden" name="userId" value="{{$user->id}}">
                                            <input type="hidden" name="type" value="cc_monthly_payment">
                                            <input type="hidden" name="remainDays"
                                                   value="@if($user->isVipOnePaymentNotExpire() && $days>0){{$days}}@endif">
                                            <input type="hidden" name="choosePaymentFlow" value="">
                                            <button type="submit" class="new_gvip_input cc_monthly_payment paySubmit"
                                                    style="border-style: none; outline: none;">信用卡
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>

                                <div class="new_xline"></div>
                                <div class="dq_fangan">單筆付款，短期VIP體驗</div>
                                <div class="new_viplist">
                                    <ul>
                                        <li>
                                            <div class="new_fa">單季支付</div>
                                            <div class="new_fanext">
                                                <h2>NT$3988/季</h2>
                                                <h2>單季體驗</h2>
                                                <div class="new_abg">
                                                    <span>
                                                        <form id="one_quarter_paymentATMForm" class="m-form m-form--fit"
                                                              action="{{ route('upgradepay_ec') }}" method=post>
                                                            <input type="hidden" name="_token"
                                                                   value="{{ csrf_token() }}">
                                                            <input type="hidden" name="userId" value="{{$user->id}}">
                                                            <input type="hidden" name="type"
                                                                   value="one_quarter_payment">
                                                            <input type="hidden" name="choosePaymentFlow" value="funpoint">
                                                            <input type="hidden" name="choosePayment" value="ATM">
                                                            @php
                                                                $codeNoPaidGetId = \App\Models\PaymentGetQrcodeLog::codeNoPaidGetId($user->id, 'VIP', 'ATM', 'one_quarter_payment');
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
                                                                <button type="submit"
                                                                        class="new_vpadd one_quarter_payment paySubmit"
                                                                        style="border-style: none; outline: none;">ATM</button>
                                                            @endif
                                                        </form>
                                                    </span>
                                                    <span>
                                                    <form id="one_quarter_paymentCreditForm" class="m-form m-form--fit"
                                                          action="{{ route('upgradepay_ec') }}" method=post>
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <input type="hidden" name="userId" value="{{$user->id}}">
                                                        <input type="hidden" name="type" value="one_quarter_payment">
                                                        <input type="hidden" name="choosePayment" value="Credit">
                                                        <input type="hidden" name="choosePaymentFlow" value="">
                                                        <button type="submit"
                                                                class="new_vpadd one_quarter_payment paySubmit"
                                                                style="border-style: none; outline: none;">信用卡</button>
                                                    </form>
                                                </span>
                                                    <span class="new_w100">
                                                        <form id="one_quarter_paymentCVSForm" class="m-form m-form--fit"
                                                              action="{{ route('upgradepay_ec') }}" method=post>
                                                            <input type="hidden" name="_token"
                                                                   value="{{ csrf_token() }}">
                                                            <input type="hidden" name="userId" value="{{$user->id}}">
                                                            <input type="hidden" name="type"
                                                                   value="one_quarter_payment">
                                                            <input type="hidden" name="choosePaymentFlow" value="funpoint">
                                                            <input type="hidden" name="choosePayment" value="CVS">
                                                            @php
                                                                $codeNoPaidGetId = \App\Models\PaymentGetQrcodeLog::codeNoPaidGetId($user->id, 'VIP', 'CVS', 'one_quarter_payment');
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
                                                                <button type="submit"
                                                                        class="new_vpadd one_quarter_payment paySubmit"
                                                                        style="border-style: none; outline: none; word-break: break-all; white-space: nowrap;">超商代碼
                                                                </button>
                                                            @endif
                                                        </form>
                                                    </span>
                                                    <span class="new_w100">
                                                        <form id="one_quarter_paymentBARCODEForm" class="m-form m-form--fit"
                                                              action="{{ route('upgradepay_ec') }}" method=post>
                                                            <input type="hidden" name="_token"
                                                                   value="{{ csrf_token() }}">
                                                            <input type="hidden" name="userId" value="{{$user->id}}">
                                                            <input type="hidden" name="type"
                                                                   value="one_quarter_payment">
                                                            <input type="hidden" name="choosePayment" value="BARCODE">
                                                            @php
                                                                $codeNoPaidGetId = \App\Models\PaymentGetQrcodeLog::codeNoPaidGetId($user->id, 'VIP', 'BARCODE', 'one_quarter_payment');
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
                                                                <button type="submit" class="new_vpadd one_quarter_payment paySubmit"
                                                                     style="border-style: none; outline: none; word-break: break-all; white-space: nowrap;">超商條碼
                                                                </button>
                                                            @endif
                                                        </form>
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="new_fa">單月支付</div>
                                            <div class="new_fanext">
                                                <h2>NT$1899/月</h2>
                                                <h2>單月體驗</h2>
                                                <div class="new_abg">
                                                    <span>
                                                        <form id="one_month_paymentATMForm" class="m-form m-form--fit"
                                                              action="{{ route('upgradepay_ec') }}" method=post>
                                                            <input type="hidden" name="_token"
                                                                   value="{{ csrf_token() }}">
                                                            <input type="hidden" name="userId" value="{{$user->id}}">
                                                            <input type="hidden" name="type" value="one_month_payment">
                                                            <input type="hidden" name="choosePaymentFlow" value="funpoint">
                                                            <input type="hidden" name="choosePayment" value="ATM">
                                                            @php
                                                                $codeNoPaidGetId = \App\Models\PaymentGetQrcodeLog::codeNoPaidGetId($user->id, 'VIP', 'ATM', 'one_month_payment');
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
                                                            <button class="new_vpadd one_month_payment paySubmit"
                                                                    style="border-style: none; outline: none;">ATM</button>
                                                            @endif
                                                        </form>
                                                    </span>
                                                    <span>
                                                    <form id="one_month_paymentCreditForm" class="m-form m-form--fit"
                                                          action="{{ route('upgradepay_ec') }}" method=post>
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <input type="hidden" name="userId" value="{{$user->id}}">
                                                        <input type="hidden" name="type" value="one_month_payment">
                                                        <input type="hidden" name="choosePayment" value="Credit">
                                                        <input type="hidden" name="choosePaymentFlow" value="">
                                                        <button class="new_vpadd one_month_payment paySubmit"
                                                                style="border-style: none; outline: none;">信用卡</button>
                                                    </form>
                                                </span>
                                                    <span class="new_w100">
                                                        <form id="one_month_paymentCVSForm" class="m-form m-form--fit"
                                                              action="{{ route('upgradepay_ec') }}" method=post>
                                                            <input type="hidden" name="_token"
                                                                   value="{{ csrf_token() }}">
                                                            <input type="hidden" name="userId" value="{{$user->id}}">
                                                            <input type="hidden" name="type" value="one_month_payment">
                                                            <input type="hidden" name="choosePaymentFlow" value="funpoint">
                                                            <input type="hidden" name="choosePayment" value="CVS">
                                                            @php
                                                                $codeNoPaidGetId = \App\Models\PaymentGetQrcodeLog::codeNoPaidGetId($user->id, 'VIP', 'CVS', 'one_month_payment');
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
                                                                <button class="new_vpadd one_month_payment paySubmit"
                                                                        style="border-style: none; outline: none; word-break: break-all; white-space: nowrap;">超商代碼
                                                                </button>
                                                            @endif
                                                        </form>
                                                    </span>
                                                    <span class="new_w100">
                                                        <form id="one_month_paymentBARCODEForm" class="m-form m-form--fit"
                                                              action="{{ route('upgradepay_ec') }}" method=post>
                                                            <input type="hidden" name="_token"
                                                                   value="{{ csrf_token() }}">
                                                            <input type="hidden" name="userId" value="{{$user->id}}">
                                                            <input type="hidden" name="type" value="one_month_payment">
                                                            <input type="hidden" name="choosePayment" value="BARCODE">
                                                            @php
                                                                $codeNoPaidGetId = \App\Models\PaymentGetQrcodeLog::codeNoPaidGetId($user->id, 'VIP', 'BARCODE', 'one_month_payment');
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
                                                                <button class="new_vpadd one_month_payment paySubmit"
                                                                        style="border-style: none; outline: none; word-break: break-all; white-space: nowrap;">超商條碼
                                                                </button>
                                                            @endif
                                                        </form>
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="paymentFlowChoose" style="display: none;">
                                <div class="vipline"><img src="/new/images/VIP_05.png"></div>
                                <div class="dq_fangan">請選擇付款方式1 或 2</div>
                                <div class="fk_viplist" style="margin-bottom: 10px;">
                                    <ul>
                                        @foreach(\App\Models\PaymentFlowChoose::where('status', 1)->get() as $paymentInfo)
                                            <li>
                                                <a onclick="chooseFlow('{{ $paymentInfo->payment}}');" class="gg_zh_li"><span><img src="{{ $paymentInfo->icon}}"></span>
                                                    <font>{{ $paymentInfo->payment_text }}</font>
                                                </a>
                                            </li>
                                        @endforeach
                                        {{--<li>
                                            <a onclick="chooseFlow('funpoint');" class="gg_zh_li"><span><img src="/new/images/zh12.png"></span>
                                                <font>付款方式1</font>
                                            </a>
                                        </li>
                                        <li>
                                            <a onclick="chooseFlow('ecpay');" class="gg_zh_li"><span><img src="/new/images/zh13.png"></span>
                                                <font>付款方式2</font>
                                            </a>
                                        </li>--}}
                                    </ul>
                                </div>
                                <input type="hidden" name="temp_form_id" value="">
                            </div>

                            <div class="vipline matop20"><img src="/new/images/VIP_05.png"></div>
                        </div>
                        <div class="vipbongn">
                            {!!  $vip_text  !!}
                        </div>
                        {{--<div class="n_vipbotf">本筆款項在信用卡帳單顯示為 信宏資產管理公司</div>--}}
                        {{-- cancel vip --}}
                        <div class="hy_width n_viptop20"  id="vip_cancel" style="display:none">
                            <div class="fi_xq">
                                <img src="/new/images/vip_xq.png" class="fi_xqicon">
                                <div class="fi_text">
                                    <h2>@if(!$user->isVipOrIsVvip())您目前尚未成為VIP會員 @elseif($user->isVipNotCanceledNotOnePayment() == false && !$user->isVipOnePaymentNotExpire())已經取消VIP @elseif($user->isVipOnePaymentNotExpire())單次VIP會員@endif</h2>
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
                            @if ($user->isVipOrIsVvip() && !$user->isVipOnePaymentNotExpire())
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

        function chooseFlow(paymentFlow){
            $("*[name='choosePaymentFlow']").val(paymentFlow);
            $($("input[name='temp_form_id']").val()).submit();
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
                $('.vipbongn').hide();
                $('.n_vipbotf').hide();
                @if (!$user->isVipOrIsVvip() && !$user->isFreeVip())
                c5('您目前尚未成為VIP會員');
                @elseif($user->isFreeVip())
                show_pop_message('您是免費VIP，刪除您的大頭照或生活照少於三張就會取消VIP');
                @elseif(!$user->isVipNotCanceledNotOnePayment() || $user->isVipOnePaymentNotExpire())
                $('#vip_cancel').show();
                $('#vip2').hide();
                @endif
            }else{
                $('.vipbongn').show();
                $('.n_vipbotf').show();
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
            c5('您目前已是VIP會員');
            return false;
            @else

            var r = confirm("{{ $upgrade_vip }}");
            
            if(!r) {
                event.preventDefault();
            }
            @endif

        });

        $('.payAlert').on('click', function(event) {;
            c5('');
            $('.bltext').append($('#code_' + $(this).data('id')).html());
            return false;
        });

        $('.paySubmit').on('click', function(event) {

            let id,choosePayment;

                if($(this).hasClass("cc_monthly_payment")) {

                    @if($user->isVipNotCanceledNotOnePayment() || $user->isVipNotOnePaymentNotExpiry() /*|| $user->isVipOnePaymentNotExpire()*/ )
                        c5('您目前已是VIP會員');
                        return false;
                    @else
                        common_confirm("{{$cc_monthly_payment}}","{{$cc_monthly_payment_red}}");
                        id = 'cc_monthly_payment';
                        choosePayment='';
                    @endif

                    $(".n_left").on('click', function() {
                        $(".blbg").hide();
                        $('#common_confirm').hide();
                        $('.paymentFlowChoose').show();
                        $('.main_vip').hide();
                        $("input[name='temp_form_id']").val('#'+id+choosePayment+'Form');
                        // $('#'+id+choosePayment+'Form').submit();
                    });

                }else if($(this).hasClass("cc_quarterly_payment")){

                    @if($user->isVipNotCanceledNotOnePayment() || $user->isVipNotOnePaymentNotExpiry() /*|| $user->isVipOnePaymentNotExpire()*/ )
                        c5('您目前已是VIP會員');
                        return false;
                    @else
                        common_confirm("{{$cc_quarterly_payment}}","{{$cc_quarterly_payment_red}}");
                        id = 'cc_quarterly_payment';
                        choosePayment='';
                    @endif

                    $(".n_left").on('click', function() {
                        $(".blbg").hide();
                        $('#common_confirm').hide();
                        $('.paymentFlowChoose').show();
                        $('.main_vip').hide();
                        $("input[name='temp_form_id']").val('#'+id+choosePayment+'Form');
                        // $('#'+id+choosePayment+'Form').submit();
                    });

                }else if($(this).hasClass("one_month_payment")){

                    //定期定額會員無法購買單次方案
                    @if($user->isVipNotCanceledNotOnePayment() && !$user->isVipOnePaymentNotExpire())
                        c5('您目前已是VIP會員');
                        return false;
                    @else
                        common_confirm("{{$one_month_payment}}","{{$one_month_payment_red}}");
                        id = 'one_month_payment';
                        choosePayment=$(this).parent().find("input[name='choosePayment']").val();

                        if(choosePayment == 'ATM' || choosePayment == 'CVS' || choosePayment == 'BARCODE'){
                            $(".n_left").on('click', function () {
                                common_confirm("{{$atm_cvs_notice}}","{{$atm_cvs_notice_red}}");
                                $(".n_left").on('click', function () {
                                    $('#' + id + choosePayment + 'Form').submit();
                                    // if(choosePayment != 'BARCODE'){
                                    //     $(".blbg").hide();
                                    //     $('#common_confirm').hide();
                                    //     $('.paymentFlowChoose').show();
                                    //     $('.main_vip').hide();
                                    //     $("input[name='temp_form_id']").val('#'+id+choosePayment+'Form');
                                    // }else{
                                    //     $('#' + id + choosePayment + 'Form').submit();
                                    // }
                                });
                            });
                        }else {
                            $(".n_left").on('click', function () {
                                if(choosePayment=='Credit'){
                                    $(".blbg").hide();
                                    $('#common_confirm').hide();
                                    $('.paymentFlowChoose').show();
                                    $('.main_vip').hide();
                                    $("input[name='temp_form_id']").val('#'+id+choosePayment+'Form');
                                }else {
                                    $('#' + id + choosePayment + 'Form').submit();
                                }
                            });
                        }
                    @endif


                }else if($(this).hasClass("one_quarter_payment")){
                    //定期定額會員無法購買單次方案
                    @if($user->isVipNotCanceledNotOnePayment() && !$user->isVipOnePaymentNotExpire())
                        c5('您目前已是VIP會員');
                        return false;
                    @else
                        common_confirm("{{$one_quarter_payment}}","{{$one_quarter_payment_red}}");
                        id = 'one_quarter_payment';
                        choosePayment=$(this).parent().find("input[name='choosePayment']").val();

                        if(choosePayment == 'ATM' || choosePayment == 'CVS' || choosePayment == 'BARCODE'){
                            $(".n_left").on('click', function () {
                                common_confirm("{{$atm_cvs_notice}}","{{$atm_cvs_notice_red}}");
                                $(".n_left").on('click', function () {
                                    $('#' + id + choosePayment + 'Form').submit();
                                    // if(choosePayment != 'BARCODE'){
                                    //     $(".blbg").hide();
                                    //     $('#common_confirm').hide();
                                    //     $('.paymentFlowChoose').show();
                                    //     $('.main_vip').hide();
                                    //     $("input[name='temp_form_id']").val('#'+id+choosePayment+'Form');
                                    // }else {
                                    //     $('#' + id + choosePayment + 'Form').submit();
                                    // }
                                });
                            });
                        }else {
                            $(".n_left").on('click', function () {
                                if(choosePayment == 'Credit'){
                                    $(".blbg").hide();
                                    $('#common_confirm').hide();
                                    $('.paymentFlowChoose').show();
                                    $('.main_vip').hide();
                                    $("input[name='temp_form_id']").val('#'+id+choosePayment+'Form');
                                }else {
                                    $('#' + id + choosePayment + 'Form').submit();
                                }
                            });
                        }
                    @endif

                }
            return false;
        });


        // 取消VIP內容
        $('#vip2_a').on('click', function(event) {
            //changediv('vip');
            @if(!$user->isFreeVip())
                    //alert(11);
                @if(isset($vipLessThan7days) && $vipLessThan7days && $user->isVipNotCanceledNotOnePayment())
                    common_confirm("{{$cancel_vip}}");
                    // var r= confirm('123');
                @elseif($user->isVipOrIsVvip() && $user->isVipNotCanceledNotOnePayment() && !$user->isVipOnePaymentNotExpire())
                    common_confirm("{{$cancel_vip}}");
            // var r= confirm('123');
                @endif
                $(".n_left").on('click', function() {
                    $(".blbg").hide();
                    $('#common_confirm').hide();
                    changediv('vip2');
                });
                // if(!r) {
                //    changediv('vip');
                //     // event.preventDefault();
                // }
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

    </script>
@stop
