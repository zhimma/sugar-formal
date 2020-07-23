@extends('new.layouts.website')

@section('app-content')
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">

                <div class="zhapian f_page01">
                    <div class="sy_title sy_sj"><img src="/new/images/zpbt_1.png"></div>
                    <div class="sy_title sy_pc"><img src="/new/images/zpbt_2.png"></div>

                    <div class="f_twobt_p sy_pc"><img src="/new/images/nsz_pc1.png"></div>
                    <div class="f_zp_bg">
                        <div class="f_nw">
                            <div class="f_zpfont_f">
                                <div class="sy_font">避免詐騙的黃金 第一守則就是“先拿錢”</div>
                            </div>
                            <img src="/new/images/zhap_02.png" class="zp_sjimg f_zp_sj">
                            <img src="/new/images/zhap_02.png" class="zp_pcimg zp_pc">
                        </div>
                    </div>

                    <div class="f_twobt"><img src="/new/images/nsz_pc2.png"></div>

                    <div class="f_zp_bg">
                        <div class="f_nw">
                            <div class="f_zpfont_f">
                                <div class="sy_font"><span>1.車馬費(站方預設是1500)：</span>第一次約見前收，強烈建議由站方代收。</div>
                            </div>
                            <img src="/new/images/zhap_03.png" class="zp_sjimg f_zp_sj">
                            <img src="/new/images/zhap_03.png" class="zp_pcimg zp_pc">
                        </div>
                    </div>
                    <div class="f_zp_bg">
                        <div class="f_nw">
                            <div class="f_zpfont_f">
                                <div class="sy_font"><span>2.零用金：</span>雙方談妥正式約會的零用金。現金或者轉帳皆可。但是轉帳請一定要透過自己的手機銀行或者ATM確認正常入帳。</div>
                            </div>
                            <img src="/new/images/zhap_04.png" class="zp_sjimg f_zp_sj">
                            <img src="/new/images/zhap_04.png" class="zp_pcimg zp_pc">
                        </div>
                    </div>
                    <div class="f_twobt"><img src="/new/images/nsz_pc3.png"></div>

                    <div class="f_zp_bg">
                        <div class="f_nw">
                            <div class="f_zpfont_f">
                                <div class="sy_font">如果遇到這種情況，請直接檢舉</div>
                            </div>
                            <img src="/new/images/zhap_05.png" class="zp_sjimg f_zp_sj">
                            <img src="/new/images/zhap_05.png" class="zp_pcimg zp_pc">
                        </div>
                    </div>


                    <div class="sy_boticon">
                        <li><a href="javascript:void(0);" class="tour" id="f_page02"><img src="/new/images/zhaicon01.png"><div class="font">詐騙實例<h2>月底結款</h2></div></a></li>
                        <li><a href="javascript:void(0);" class="tour" id="f_page03"><img src="/new/images/zhaicon02.png"><div class="font">詐騙實例<h2>手機轉帳</h2></div></a></li>
                        <li><a href="javascript:void(0);" class="tour" id="f_page04"><img src="/new/images/zhaicon03.png"><div class="font">詐騙實例<h2>騙裸照</h2></div></a></li>
                    </div>
                    <a href="/dashboard/browse" class="zpback" style="background:#fe92a8;text-align:center; margin:0 auto;color: #ffffff;width:260px;height:45px;border-radius: 200px;line-height:45px;font-size:16px;text-align: center; display:table; margin-top:30px;">返回</a>

                </div>

                <div class="zhapian f_page02">
                    <div class="zp_bt"><img src="/new/images/zp_c1.png"></div>
                    <div class="f_zp_bg">
                        <div class="f_nw">
                            <div class="sy_font zp_mt">以下都是曾經發生過的真實案例。為保護當事人所以重新製作。<span>但保證真實。</span></div>
                            <div class="zp_cimg"><img src="/new/images/zp_c5.png"></div>
                        </div>
                    </div>
                    <a href="javascript:void(0);" class="zpback" id="f_page01">返回</a>
                </div>

                <div class="zhapian f_page03">
                    <div class="zp_bt"><img src="/new/images/zp_c2.png"></div>
                    <div class="f_zp_bg">
                        <div class="f_nw">
                            <div class="sy_font zp_mt">以下都是曾經發生過的真實案例。為保護當事人所以重新製作。<span>但保證真實。</span></div>
                            <div class="zp_cimg01"><img src="/new/images/zp_c4.png"></div>
                        </div>
                    </div>
                    <a href="javascript:void(0);" class="zpback" id="f_page01">返回</a>
                </div>

                <div class="zhapian zp_n f_page04">
                    <div class="zp_bt"><img src="/new/images/zp_c3.png"></div>
                    <div class="f_zp_bg">
                        <div class="zp_lz">要點說明</div>
                        <div class="zp_botk">
                            <li><img src="/new/images/zp_c6.png">不要傳清涼照給男會員</li>
                            <li><img src="/new/images/zp_c7.png">不要相信公平起見要交換照片</li>
                            <li><img src="/new/images/zp_c8.png">不要相信傳裸照會轉帳現金給你</li>
                        </div>
                    </div>
                    <a href="javascript:void(0);" class="zpback" id="f_page01">返回</a>
                </div>

                <div class="zhapian zp_n m_page01">
                    <div class="zp_box"><img src="/new/images/sc_bt.png"></div>
                    <div class="zp_bg">
                        <div class="zp_img">
                            <div class="zp_ztext">
                                <img src="/new/images/sc_a.png"  class="zp_ztext_img">
                                <ul>
                                    <li><a href="javascript:void(0);" class="tour" id="m_page02"> <img src="/new/images/sc_a1.png"></a></li>
                                    <li><a href="javascript:void(0);" class="tour" id="m_page03"> <img src="/new/images/sc_a2.png"></a></li>
                                    <li><a href="javascript:void(0);" class="tour" id="m_page04"> <img src="/new/images/sc_a3.png"></a></li>
                                    <li><a href="javascript:void(0);"> <img src="/new/images/sc_a4.png"></a></li>
                                </ul>
                            </div>
                            <div class="zp_btab">
                                <img src="/new/images/sc_dp.png" class="zp_libt">
                                <h2>男生在包養界通常被騙的都是錢，但是比較麻煩的是你想包養，金錢就不能太計較，要怎麼拿捏其中分寸，不是件容易的事情。</h2>
                            </div>
                        </div>
                    </div>
                    <a href="/dashboard/browse" class="zpback zp_bbut">返回</a>
                </div>

                <div class="zhapian zp_n m_page02">
                    <div class="zp_box"><img src="/new/images/sc_bt.png"></div>
                    <div class="zp_bg">

                        <div class="f_tobt">
                            <img src="/new/images/sc_t1.png" class="f_tpc f_zp_sj">
                            <img src="/new/images/sc_t1_s.png" class="f_tpc zp_pc">
                        </div>
                        <div class="zf_nw">
                            <img src="/new/images/sc_01.png" class="zp_sjimg f_zp_sj">
                            <img src="/new/images/sc_01.png" class="zp_pcimg zp_pc f_left">
                            <div class="zpfont_f zp_w95">
                                <div class="zp_btab">
                                    <img src="/new/images/sc_dp.png" class="zp_libt">
                                    <h2>正常的找包養女孩，不會拒絕使用網站的車馬費。會拒絕的通常以下類型</h2>
                                    <div class="zp_bn">
                                        <h3>1：只想騙錢</h3>
                                        <h3>2：對社會信任感低</h3>
                                        <h3>3：比較有個性不喜配合他人</h3>
                                    </div>
                                    <div class="zp_botfont">撇開1不論，2跟3以站長過往找包養的角度來看，都屬於比較難配合的夥伴。站長是會跳過這類人選。當然你想挑戰高難度也無妨，只是可能要有人財兩失的覺悟。(本站也不接受這類被騙車馬費的申訴)</div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <a href="javascript:void(0);" class="zpback zp_bbut" id="m_page01">返回</a>

                </div>

                <div class="zhapian zp_n m_page03">
                    <div class="zp_box"><img src="/new/images/sc_bt.png"></div>
                    <div class="zp_bg">
                        <div class="f_tobt">
                            <img src="/new/images/sc_t2.png" class="f_tpc f_zp_sj">
                            <img src="/new/images/sc_t2_s.png" class="f_tpc zp_pc">
                        </div>
                        <div class="zf_nw">
                            <img src="/new/images/sc_02.png" class="zp_sjimg f_zp_sj">
                            <img src="/new/images/sc_02.png" class="zp_pcimg zp_pc f_left">
                            <div class="zpfont_f zp_w95">
                                <div class="zp_btab">
                                    <img src="/new/images/sc_dp.png" class="zp_libt">
                                    <h2>正常的找包養女孩，在意的是</h2>
                                    <div class="zp_bn">
                                        <h3>1：金錢的穩定供應</h3>
                                        <h3>2：daddy 是否好相處</h3>
                                        <h3>3：其他</h3>
                                    </div>
                                    <div class="zp_botfont">通常絕大多數的女孩都可以接受按周給，<span class="sop_bz">會跟你糾結一定要一次全額付一個月的，或者一定要一筆金額的，站長建議不是老手的直接跳過。</span>因為沒什麼過不去的坎，再怎麼急錢，一周有幾萬入帳也絕對夠他去應付債主/需要用錢的狀況。</div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <a href="javascript:void(0);" class="zpback zp_bbut" id="m_page01">返回</a>

                </div>

                <div class="zhapian zp_n m_page04">
                    <div class="zp_box"><img src="/new/images/sc_bt.png"></div>
                    <div class="zp_bg">

                        <div class="f_tobt">
                            <img src="/new/images/sc_t4.png" class="f_tpc f_zp_sj">
                            <img src="/new/images/sc_t4_s.png" class="f_tpc zp_pc">
                        </div>


                        <div class="zp_img">
                            <div class="zp_ztext">
                                <img src="/new/images/sc_b.png" class="zp_ztext_img zp_p">
                                <img src="/new/images/rt.png" class="zp_photo ">
                                <ul>
                                    <li><a href="javascript:void(0);" class="tour" id="m_page07"> <img src="/new/images/sc_b1.png"></a></li>
                                    <li><a href="javascript:void(0);" class="tour" id="m_page05"> <img src="/new/images/sc_b2.png"></a></li>
                                    <li><a href="javascript:void(0);" class="tour" id="m_page06"> <img src="/new/images/sc_b3.png"></a></li>
                                    <li><a href="javascript:void(0);"> <img src="/new/images/sc_b4.png"></a></li>
                                </ul>
                            </div>
                            <div class="zp_btab">
                                <img src="/new/images/sc_dp.png" class="zp_libt">
                                <h2>八大行業的入侵是網站比較無奈的部分。原因是我們事後審查而不是事前審查<font class="showTipsContent tips2" style="color: #f00;"><img src="/new/images/tips2.jpg" style="display: none">(為什麼)</font>。這樣可以保證男會員的權益以及最多的女會員成功使用。但是付出的代價就是部分八大的訊息會比較煩人。</h2>
                            </div>
                        </div>

                    </div>
                    <a href="javascript:void(0);" class="zpback zp_bbut" id="m_page01">返回</a>

                </div>

                <div class="zhapian zp_n m_page05">
                    <div class="zp_box"><img src="/new/images/sc_bt.png"></div>
                    <div class="zp_bg zpbg">


                        <div class="f_tobt">
                            <img src="/new/images/sc_t3.png" class="f_tpc f_zp_sj">
                            <img src="/new/images/sc_t3_s.png" class="f_tpc zp_pc">
                        </div>

                        <div class="f_nw">
                            <div class="zhp_text"><span>對話不到三次就主動提供你私人聯絡方式的(準確率90%)</span></div>
                            <div class="zhp_cimg"><img src="/new/images/sc_03.png"></div>
                        </div>

                    </div>
                    <div class="b_fenye fypage">
                        <a href="javascript:void(0);" class="tour" id="m_page01">回手冊</a>
                        <a href="javascript:void(0);" class="tour" id="m_page04">上一頁</a>
                    </div>
                </div>

                <div class="zhapian zp_n m_page06">
                    <div class="zp_box"><img src="/new/images/sc_bt.png"></div>
                    <div class="zp_bg zpbg">
                        <div class="f_tobt">
                            <img src="/new/images/sc_t5.png" class="f_tpc f_zp_sj">
                            <img src="/new/images/sc_t5_s.png" class="f_tpc zp_pc">
                        </div>

                        <div class="f_nw">
                            <div class="zhp_text">第一次約見就約旅館的(準確率 98%)</div>
                            <div class="zhp_cimg"><img src="/new/images/sc_04.png"></div>
                        </div>
                    </div>
                    <div class="b_fenye fypage">
                        <a href="javascript:void(0);" class="tour" id="m_page01">回手冊</a>
                        <a href="javascript:void(0);" class="tour" id="m_page04">上一頁</a>
                    </div>

                </div>

                <div class="zhapian zp_n m_page07">
                    <div class="zp_box"><img src="/new/images/sc_bt.png"></div>
                    <div class="zp_bg zpbg">
                        <div class="f_tobt">
                            <img src="/new/images/sc_t6.png" class="f_tpc f_zp_sj">
                            <img src="/new/images/sc_t6_s.png" class="f_tpc zp_pc">
                        </div>
                        <div class="f_nw">
                            <div class="zhp_text">基本資料直接出現通訊軟體的(準確率95%)</div>
                            <div class="zhp_cimg"><img src="/new/images/sc_05.png"></div>
                        </div>

                    </div>
                    <div class="b_fenye fypage">
                        <a href="javascript:void(0);" class="tour" id="m_page01">回手冊</a>
                        <a href="javascript:void(0);" class="tour" id="m_page04">上一頁</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @include('partials.image-zoomin')
@stop

@section('javascript')
    <script>

        //alert(window.location.hash.substr(1));
        if(window.location.hash.substr(1) != ''){
            $('.zhapian').hide();
            @if($user->engroup==2)
            $('.' + window.location.hash.substr(1)).show();
            @else
            $('.m_page01').show();
            @endif
        }else {

            $('.zhapian').hide();
            @if($user->engroup==2)
            $('.f_page01').show();
            @else
            $('.m_page01').show();
            @endif
        }

        $('.tour').click(function() {
           var id = $(this).attr('id');
            $('.zhapian').hide();
            $('.' + id).show();
            $('html,body').animate({ scrollTop: 0 }, 'slow');
        });

        $('.zpback').click(function() {
            $('.zhapian').hide();
            @if($user->engroup==2)
            $('.f_page01').show();
            @else
            $('.m_page01').show();
            @endif
            $('html,body').animate({ scrollTop: 0 }, 'slow');
        });


    </script>

@stop
