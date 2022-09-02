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
                        <li><a href="{!! url('dashboard/account_manage') !!}" class="g_pwicon_t3 "><span>帳號設定</span></a></li>
                        <li><a href="{!! url('dashboard/vipSelect') !!}" class="g_pwicon_t4 g_hicon4"><span>升級付費</span></a></li>
                    </div>
                    <div class="new_viphig">
                        <div class="n_sjvip" id="vip">
                            <div class="vipline"><img src="/new/images/VIP_05.png"></div>
                            <div class="dq_fangan">VVIP會費自動續約扣款</div>
                            <div class="new_vvip" style="margin-bottom: 10px;">
                                <ul>
                                    <li class="f_w60" style="margin: 0 auto; display: table; float: none;" >
                                        <div class="vipcion"><img src="/new/images/bicontop.png"></div>
                                        <div class="new_fa">每季支付</div>
                                        <div class="new_fanext">
                                            <h2>NT$10000元</h2>
                                            <h2>每季自動扣款</h2>
                                        </div>
                                        <form id="PayVVIP" class="m-form m-form--fit" action="{{ route('valueAddedService_ec') }}" method=post>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                            <input type="hidden" name="userId" value="{{$user->id}}">
                                            <input type="hidden" name="payment" value="cc_quarterly_payment">
                                            <input type="hidden" name="service_name" value="VVIP">
                                            <input type="hidden" name="amount" value="10000">
                                            <button type="submit" class="new_gvip_input cc_quarterly_payment paySubmit" style="border-style: none; outline: none;">信用卡</button>
                                        </form>
{{--                                        <a class="new_gvip_input" onclick="cl()">信用卡</a>--}}
                                    </li>

                                </ul>
                            </div>


                            <div class="vipline matop20"><img src="/new/images/VIP_05.png"></div>


                            <!-- <div class="vipline"><img src="images/VIP_05.png"></div> -->
                            <div class="vipbongn">
                                <h2>VVIP功能</h2>
                                <h3><span>●</span>搜尋頁置頂，並有特殊背景色</h3>
                                <h3><span>●</span>女會員有單獨 VVIP 收件夾</h3>
                                <h3><span>●</span>VVIP 訊息專屬特別提示給女會員</h3>
                                <h3><span>●</span>基本資料頁會有 VVIP Tag 以及說明，與特殊背景色</h3>
                                <h3><span>●</span>優先預覽「審查期」的新進女會員。(剛註冊完 12 小時的女會員屬於審查期，其他會員都無法看到相關資料)</h3>
                                <h3><span>●</span>享有1 對 1 邀請功能。當您發出一對一邀請，三天內Baby將不再收到其他新的男會員的訊息。</h3>
                                <h3><span>●</span>專屬客服回答問題，您不會在收到罐頭回應!</h3>

                            </div>
                            <div class="n_vipbotf">本筆款項在信用卡帳單顯示為 信宏資產管理公司</div>


                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="bl bl_tab " id="passPayTip">
        <div class="bltitle"><font>提示</font></div>
        <div class="new_yk new_poptk">
            <h2>信用卡VVIP 付費須知</h2>
            <h3><span>●</span><font>您選擇的是信用卡刷卡季付。金額是每季 10000 元。</font></h3>
            <h3><span>●</span><font>加入VVIP後，將於每三個月於第一次刷卡日期自動扣款，至取消為止。</font></h3>
            <h3><span>●</span><font>升級VVIP後，將取消目前的VIP付費。改以VVIP付費。</font></h3>
            <h3><span>●</span><font>您申請每季自動扣款並完成繳費，經確認繳費程序完成且已成功開啟本站相關服務設定，即視同您已經開始使用所購買之本站服務。</font></h3>
            <h4 style="background: #FFFF00;"><span>★</span><font>取消 VVIP 時間需要七個工作天，如下個月不續約請提前取消，以免權益受損！如同意以上所有條款請點確認，不同意點取消。</font></h4>
            <div class="n_bbutton">
                <span><a class="n_left">確定</a></span>
                <span><a class="n_right" onclick="$('.blbg').click();">取消</a></span>
            </div>
        </div>
        <a id="" onclick="$('.blbg').click();" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
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

        $('.paySubmit').on('click', function(event) {
            @if($user->valueAddedServiceStatus('VVIP') == 1)
            c5('您目前已在VVIP付費期間');
            return false;
            @endif
            $('#passPayTip').show();
            $(".blbg").show();
            $('body').css("overflow", "hidden");
            $(".n_left").on('click', function() {
                $('#PayVVIP').submit();
            });
            return false;
        });
    </script>
@stop
