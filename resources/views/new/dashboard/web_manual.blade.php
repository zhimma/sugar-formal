@extends('new.layouts.website')

@section('app-content')
    <div class="container matop70">
        <div class="row">
            <br>
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="zhapian f_page">
                    <div class="f_biaoti"><img src="/new/images/jie_01.png"></div>
                    <div class="f_twobt"><img src="/new/images/jie_02.png"></div>



                    <div class="f_zp_bg">
                        <div class="f_nw">
                            <img src="/new/images/jie_05.png" class="zp_sjimg f_zp_sj">
                            <img src="/new/images/jie_05.png" class="zp_pcimg zp_pc f_left">
                            <div class="ff_f">
                                <div class="f_jbfont">這兩個設定可以讓你的資料不會出現在設定縣市/產業別的男生看到。</div>

                            </div>
                        </div>
                    </div>


                    <div class="f_twobt"><img src="/new/images/jie_03.png"></div>
                    <div class="f_zp_bg">
                        <div class="f_nw">
                            <img src="/new/images/jie_06.png" class="zp_sjimg f_zp_sj">
                            <img src="/new/images/jie_06.png" class="zp_pcimg zp_pc f_left">
                            <div class="ff_f">
                                <div class="f_jbfont">上傳四張照片，一張大頭貼+三張生活照。可以幫你免費取得 VIP 的權限。</div>
                                <div class="f_jbfont02 f_jbtop">
                                    <div class="f_jbfont02_bt"><span>注意事項：</span></div>
                                    <div class="f_text">建議不要使用社群軟體(IG FB Line)已有的照片。</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="f_twobt"><img src="/new/images/jie_04.png"></div>
                    <div class="f_zp_bg">
                        <div class="f_nw">
                            <img src="/new/images/jie_07.png" class="zp_sjimg f_zp_sj">
                            <img src="/new/images/jie_07.png" class="zp_pcimg zp_pc f_left">
                            <div class="ff_f ">
                                <div class="f_jbfont03 f_jbtop">
                                    <div class="f_jbfont03_bt"><span>成為VIP 時間：</span></div>
                                    <div class="f_text">好的 daddy 通常是長期VIP，一般來說超過三個月的 VIP 都是不錯的對象。</div>
                                    <div class="f_jbfont03_bt"><span>車馬費次數：</span></div>
                                    <div class="f_text">願意支付網站車馬費的通常都是很有誠意的 daddy，建議列入考量。</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="/dashboard/browse" class="zpback">返回</a>


                </div>

                <div class="zhapian m_page">
                    <div class="m_biaoti w_tb wx_top"><img src="/new/images/wz_m1.png"></div>

                    <div class="f_zp_bg">
                        <div class="f_nw">

                            <img src="/new/images/wz_m5.png" class="zpn_pcimg zp_pc f_left">
                            <div class="zpfont_f zptop wz_wd">
                                <div class="w_jpage">
                                    <img src="/new/images/wz_m12.png" class="w_jptitle">
                                    <h2>站長在包養界的混超過十年了。不管是妹子，大叔。形形色色的人看過非常非常多。看過很多無奈，所以做這個網站希望大家可以有比較好的交流平台。</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w_box"><img src="/new/images/wz_m3.png"><span>本站絕無人工干預</span></div>

                    <div class="f_zp_bg">
                        <div class="f_nw">

                            <img src="/new/images/wz_m7.png" class="zpn_pcimg zp_pc f_left">
                            <div class="zpfont_f zptop wz_wd">
                                <div class="w_jpage">
                                    <img src="/new/images/wz_m12.png" class="w_jptitle">
                                    <h2>站長以前在包養界打滾時，玩過非常多的網站。慢慢的發現一個事實就是這些包養網站的站務人員，其實自己都下海玩很大。所有優質的妹子基本上都經過他們挑選，好的先包走，次貨才放出來。</h2>
                                    <h2>所以本站堅持不採用事前審核而採用事後的檢舉制度。就是公開透明，絕對不會有人謀不臧的情況發生。</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w_box"><img src="/new/images/wz_m4.png"><span>車馬費機制</span></div>
                    <div class="f_zp_bg">
                        <div class="f_nw">

                            <img src="/new/images/wz_m6.png" class="zpn_pcimg zp_pc f_left">
                            <div class="zpfont_f zptop wz_wd">
                                <div class="w_jpage">
                                    <img src="/new/images/wz_m12.png" class="w_jptitle">
                                    <h2>約見妹子往往是一件困難的事情，車馬費是一個很好展示自己誠意的好工具。據網站統計，使用車馬費邀請的成功率是 xx%，遠高於普通邀約成功率為XX%，建議大家多多使用。</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="/dashboard/browse" class="zpback zp_bbut">返回</a>


                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $('.zhapian').hide();
        @if($user->engroup==2)
        $('.f_page').show();
        @else
        $('.m_page').show();
        @endif
    </script>

@stop

