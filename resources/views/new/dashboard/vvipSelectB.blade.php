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
                        <li><a href="@if($user->isVVIP()) /dashboard/viewuser_vvip/{{$user->id}} @else /dashboard/viewuser/{{$user->id}} @endif" class="g_pwicon_t5 "><span>自我預覽</span></a></li>
                        <li><a href="{!! url('dashboard') !!}" class="g_pwicon_t "><span>基本資料</span></a></li>
                        <li><a href="{!! url('dashboard_img') !!}" class="g_pwicon_t2"><span>照片管理</span></a></li>
                        <li><a href="{!! url('dashboard/account_manage') !!}" class="g_pwicon_t3 "><span>帳號設定</span></a></li>
{{--                        <li><a href="{!! url('dashboard/vipSelect') !!}" class="g_pwicon_t4 g_hicon4"><span>升級付費</span></a></li>--}}
                    </div>
                    <div class="zhapian vvip_hg">
                        <div class="vip_bt">花園網VVIP方案</div>
                        <div class="vip_bg">
                            <div class="vip_title02">B方案</div>
                            <div class="vip_font">
                                <div class="vip_h3"><i>●</i><font>VVIP 一率季繳 9888 元。</font></div>
                                <div class="vip_h3"><i>●</i><font>無償贈與 5 萬元予站方作為預備金。</font></div>
                                <div class="vip_h3"><i>●</i><font>預備金用途：此帳號所有爭議處理費用皆由預備金扣除。</font></div>
                                <div class="vip_h3"><i>●</i><font>預備金保留：若帳號暫停使用，不支付 VVIP 會費。預備金保留，願意支付 VVIP 會費時會繼續享有 VVIP 權益。</font></div>
                                <div class="vip_h3"><i>●</i><font>帳號/預備金不得轉讓他人使用。</font></div>
                                <div class="vip_h3"><i>●</i><font>預備金退還：若不再使用本站，預備金不退還。</font></div>
                                <div class="vip_h3"><i>●</i><font>預備金不足額：預備金低於 2 萬時須補足到 5 萬，否則取消 VVIP 權限。取消VVIP權限時，預備金不退還。已繳之VVIP費用依照使用比例天數退還。</font></div>
                                <div class="vip_h3"><i>●</i><font>若違反本網站用戶規定，被申訴次數達一定次數，造成站方管理上困難，本網站有權取消用戶 VVIP 之身份。</font></div>
                                <div class="vip_h3"><i>●</i><font>又，上述申訴不僅以次數作為判定標準，亦依情節嚴重性而認定。上述所提及之認定資格在站方，站方亦無說明義務。</font></div>
                                <div class="vip_h3"><i>●</i><font>被申訴次數過多會造成帳號被取消，申請用戶需三思。</font></div>
                                <div class="vip_h3"><i>●</i><font>若本網站自行斟酌後認為您的個人檔案內容或您在本網站中之行為違反使用條款，或您違反本協議，或因任何其他理由，本網站得暫停或終止您在本網站中使用者帳戶，以及您於本網站中全部或部分之使用。本網站亦得隨時移除您使用者帳戶之全部或部分或任何使用者內容。</font></div>
                                <div class="vip_h3"><i>●</i><font>您同意上述終止事項無需事前通知即逕行生效，且本網站不需對您或任何第三方負責。</font></div>
                                <div class="vip_h3"><i>●</i><font>若您欲取消申請、訂閱，您得隨時依照本協議之條款取消您的 VVIP 服務。您必須依照本網站服務內提供之說明取消，取消額外服務之說明如上所述。</font></div>
                                <div class="vip_h3"><i>●</i><font>本網站保留權利更正錯誤（無論是透過更改本網站之服務內資訊、或是將錯誤通知您並提供您機會取消訂購或不經通知隨時更新資訊。）</font></div>
                                <div class="vip_h3" style="color: #f00;background-color: yellow;"><i>★</i><font>以上若有爭議由站方全權決定，站方亦無說明義務，申請人絕無異議。</font></div>
                            </div>

                        </div>
                        <a onclick="cl()" class="zpback zp_bbut mabot50">申請購買</a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="bl bl_tab " id="tab01">
        <div class="bltitle"><font>B方案</font></div>
        <div class="new_poptk" style="width: 90%">
            <div class="viptkft">
                @if($user->isVip() && $vip_text != '')<h2 class="matop00">●{{ $vip_text }}</h2>@endif
                <h2 class="matop00">●VVIP 季費 9888 元。</h2>
                <h2 class="matop00">●刷卡完成後，須於72小時內匯50000元入指定帳戶，否則將取消此次 VVIP 申請。9888 元扣除手續費4000，剩餘刷退。</h2>
                <h3>以上任一點不同意請按取消!</h3>
            </div>
            <div class="n_bbutton">
                <span><a class="n_left">確定</a></span>
                <span><a class="n_right" onclick="$('.blbg').click();">取消</a></span>
            </div>
        </div>
        <a id="" onclick="$('.blbg').click();" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>

    <form id="form2" action="{{ route('valueAddedService_ec') }}" method=post>
        {!! csrf_field() !!}
        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
        <input type="hidden" name="userId" value="{{$user->id}}">
        <input type="hidden" name="payment" value="cc_quarterly_payment">
        <input type="hidden" name="choosePayment" value="Credit">
        <input type="hidden" name="service_name" value="VVIP">
        <input type="hidden" name="plan" value="VVIP_B">
        <input type="hidden" name="amount" value="9888">
    </form>
@stop

@section('javascript')
    <script>

        $('.n_left').on('click',function () {
            $('#form2').submit();
        });

        function cl() {
            $(".blbg").show();
            $("#tab01").show();
            $('body').css("overflow", "hidden");
        }

    </script>
@stop
