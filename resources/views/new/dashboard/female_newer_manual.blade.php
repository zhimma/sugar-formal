@extends('new.layouts.website')
@section('style')
<script>

if(location.hash!='' && location.hash.indexOf('#nr_fnm')>=0) {
    location.hash='';
}

if(location.hash=='' ) {
    location.hash= '#{{$no_read_hash_str??''}}{{$show_sop_type}}_1';
}

</script>
@stop
@section('app-content')
    <style>
        .mb-3 { margin-bottom: 16px !important; }

        .zp_tab_f{width: 100%;margin:0 auto;float: none; padding-bottom:20px}
        .zp_tab_f img{width:150px}
        .zp_tab_f h2{ width:100%;padding-top:10px; display:table}
        .zp_tab_f h2 span, .zp_step h2 span{ color:#fd5676; font-size:20px;line-height:30px;}
        .zp_tab_f h2 var, .zp_step h2 var{ font-style:normal;width: calc(100% - 22px);float: right; font-size:18px; font-weight:bold; line-height:30px; }

        .zp_step+.zhp_cimg {margin-top: 0px!important; margin-bottom: 15px!important;}

        @media (max-width:1024px) {
            .zp_top{ margin-top:30px}
        }

        .kuang_qq{width: 100%;background-image: linear-gradient(to top, #ffc7d2 0%, #eeeeee 100%); padding: 10px;color: #fd5676; font-size: 18px;
            margin-bottom: 10px; border-bottom: #fe92a8 1px dashed; line-height: 20px; display: table; font-weight: bold;}
        .kuang_qq span{ height: 20px; width:5px; border-radius: 10px; float: left; background: #fd5676; display: table; }
        .kuang_qq font{width: calc(100% - 15px); float: right;}
        .zongjie h2{width:90%; margin:0 auto; font-size:16px; line-height:25px; font-weight:bold}


        .pagination{ position:static;width:600px !important;height:70px; margin:0 auto !important;background:url(../sop/images/line.jpg) repeat-x center; display:table}
        .pagination a{ float:left; margin-right:10.415%; line-height:70px; color:#ffffff; text-align:center; font-weight:bold; font-size:14px}
        .pagination a:last-child{ margin-right:0; float:right}
        .page_p{ margin-top:25px}

        .pa_cit{width:70px; height:70px; background:#dcdcdc;  border-radius:100px}
        .pa_hover{ background:#fe92a8 !important}

        .zptop{ margin-top:10px}

        @media (max-width:760px) {
            .pagination{ position:static;width:350px !important;height:55px; margin:0 auto !important;background:url(../sop/images/line.jpg) repeat-x center; display:table}
            .pagination a{ float:left; margin-right:5.36%; line-height:55px; color:#ffffff; text-align:center; font-weight:bold; font-size:14px}
            .pagination a:last-child{ margin-right:0; float:right}
            .page_p{ margin-top:25px}

            .pa_cit{width:55px; height:55px; background:#dcdcdc;  border-radius:100px}
        }

        @media (max-width:370px) {
            .pagination{ position:static;width:300px !important;height:45px; margin:0 auto !important;background:url(../sop/images/line.jpg) repeat-x center; display:table}
            .pagination a{margin-right:6.25%;}
            .pagination span{ float:left; margin-right:6.23%; line-height:45px; color:#ffffff; text-align:center; font-weight:bold; font-size:13px}
            .pagination span:last-child{ margin-right:0; float:right}
            .page_p{ margin-top:25px}
            .pa_cit{width:45px; height:45px; background:#dcdcdc;  border-radius:100px; font-size:12px !important; line-height:45px !important}

        }
        .pagination a{ float:left; margin-right:32.415%; line-height:70px; color:#ffffff; text-align:center; font-weight:bold; font-size:14px}

        @media (max-width:760px) {
            .pagination a{ float:left; margin-right:26.36%; line-height:55px; color:#ffffff; text-align:center; font-weight:bold; font-size:14px}
        }

        @media (max-width:370px) {
            .pagination a{margin-right:26.36%;}
        }



        .new_poptk{width:60%;height:auto;margin: 0 auto;color: #666666;padding-bottom: 20px; padding-top:15px; display: block;height:auto;overflow-y: scroll;}
        @media (max-width:1024px){
            .new_poptk{height:auto;overflow-y: scroll;width:90%;}
        }
        @media (max-width:915px){
            .new_poptk{height:300px;overflow-y: scroll;width:80%;}
        }
        @media (max-width:768px){
            .new_poptk{height:400px;overflow-y: scroll;width:80%;}

        }

        @media (max-width:741px){
            .new_poptk{height:200px;overflow-y: scroll;width:80%;}
        }

        @media (max-width:450px){
            .new_poptk{height:auto;}
        }
        .new_ii{ font-style: normal; font-size: 14px; width: 100%; display: table; font-weight: normal; line-height: 25px;}
        .scroll-bottom {
            writing-mode: tb;
            position: fixed;
            background-color: #F9869C;
            border-radius: 11px;
            padding: 5px 6px;
            bottom: 15px;
            z-index: 1;
            color: #fff;
            width: 35px;
            text-align: center;
            left: 50%;
            margin-left: -17px;
            cursor: pointer;
            font-weight:bold;
            box-shadow: 0px 0px 10px #fd5676;
        }
        .scroll-bottom:hover {
            background-color: #fd5676;
        }
    </style>
    <div class="container matop70">
        <div class="scroll-bottom">
            >>
        </div>
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                @if($show_sop_type==1)
                <div id="sop_area_1">
                    <div class="zhapian">
                    <div class="zp_title">新手教學-約見SOP</div>
                    <div class="pagination">
                        <a href="#{{$no_read_hash_str??''}}{{$show_sop_type}}_1" class="pa_cit pa_hover part1" id="step01">STEP1</a>
                        <a href="#{{$no_read_hash_str??''}}{{$show_sop_type}}_2" class="pa_cit part1" id="step02">STEP2</a>
                        <a href="#{{$no_read_hash_str??''}}{{$show_sop_type}}_3" class="pa_cit part1" id="step03">STEP3</a>
                    </div>
                    <div class="zp_bg step01" >
                        <div class="zp_img">
                            <img src="/sop/images/sj1_1.png" class="zp_sjimg zp_sj">
                            <img src="/sop/images/pc_1.png" class="zp_sjimg zp_pc">
                            <div class="kuang_qq"><span></span><font>取得VIP</font></div>
                            <img src="/sop/images/1.png" class="zp_sjimg zp_sj">
                            <img src="/sop/images/1.png" class="zp_pcimg zp_pc">
                            <div class="zpfont_f">
                                <img src="/sop/images/dpicon.png">
                                <h2><span>●</span><var>上傳四張照片，一張大頭貼+三張生活照。可以幫妳免費取得VIP的權限</var></h2>
                                <h2><span>●</span><var>照片標準：可以遮臉，勿放美食、風景照等非人物照片。</var></h2>
                                <div class="zongjie">
                                    <div class="tf">註意事項</div>
                                    <h2>建議不要使用社群軟件（IG FB Line）已有的照片</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="zp_bg step02">
                        <div class="zp_img">
                            <img src="/sop/images/sj1_2.png" class="zp_sjimg zp_sj">
                            <img src="/sop/images/pc_2.png" class="zp_sjimg zp_pc">
                            <img src="/sop/images/2.png" class="zp_sjimg zp_sj">
                            <img src="/sop/images/2.png" class="zp_pcimg zp_pc">
                            <div class="zpfont_f">
                                <img src="/sop/images/dpicon.png">
                                <div class="f_jbfont03 f_jbtop" style="width: 100%;">
                                    <div class="f_jbfont03_bt"><span>成為VIP 時間：</span></div>
                                    <div class="f_text">好的 daddy 通常是長期VIP，一般來說超過三個月的 VIP 都是不錯的對象。</div>
                                    <div class="f_jbfont03_bt"><span>車馬費次數：</span></div>
                                    <div class="f_text">願意支付網站車馬費的通常都是很有誠意的 daddy，建議列入考量。</div>
                                    <div class="f_jbfont03_bt"><span>從哪裡判讀重要資訊：</span></div>
                                    <div class="f_text">1.進階資料<font style="color: #f00; cursor: pointer;"  onclick="dianwo1()">“請點我”</font><br>
                                        2.建議找PR值高的daddy<font style="color: #f00;cursor: pointer;" onclick="dianwo2()">“請點我”</font>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="zp_bg step03">
                        <div class="zp_img">
                            <img src="/sop/images/sj1_3.png" class="zp_sjimg zp_sj">
                            <img src="/sop/images/pc_3.png" class="zp_sjimg zp_pc">
                            <img src="/sop/images/3.png" class="zp_sjimg zp_sj">
                            <img src="/sop/images/3.png" class="zp_pcimg zp_pc">
                            <div class="zpfont_f">
                                <img src="/sop/images/dpicon.png">
                                <h2><span>●</span><var>數字有基本共識後，請對方刷站內車馬費。如果對方不願意刷卡又不是VIP，站長強烈建議你要提高警覺，千萬不要接受”任何”後付款的條件</var></h2>
                                <h2><span>●</span><var>此時不建議給Line</var></h2>
                                <h2><span>●</span><var>如果一定要互加通訊軟件，強烈建議請新辦一個之前沒使用過的通訊軟件<font class="sop_bz">ID千萬不要取跟自已相關的例如自已的英文名字，生日等等</font></var></h2>
                            </div>
                        </div>
                    </div>
                    <div class="fenye">
                        <a class="prev" >上一頁</a>
                        <a class="next" href="#{{$no_read_hash_str??''}}{{$show_sop_type}}_2">下一頁</a>
{{--                        @if($user->isReadManual == 0)--}}
                            <a class="finish" data-sop_manual="one" onclick="location.hash= '#{{$no_read_hash_str??''}}{{$show_sop_type}}_leave';">結束新手教學</a>
{{--                        @endif--}}
                    </div>
                </div>
                </div>
                @elseif($show_sop_type==2)
                <div id="sop_area_2">
                    <div class="zhapian">
                        <div class="zp_title">新手教學-約見SOP</div>
                        <div class="pagination">
                            <a href="#{{$no_read_hash_str??''}}{{$show_sop_type}}_1" class="pa_cit pa_hover part2" id="step01">STEP1</a>
                            <a href="#{{$no_read_hash_str??''}}{{$show_sop_type}}_2" class="pa_cit part2" id="step02">STEP2</a>
                            <a href="#{{$no_read_hash_str??''}}{{$show_sop_type}}_3" class="pa_cit part2" id="step03">STEP3</a>
                        </div>
                        <div class="zp_bg step01">
                            <div class="zp_img">
                                <img src="/sop/images/sj1_4.png" class="zp_sjimg zp_sj">
                                <img src="/sop/images/pc_4.png" class="zp_sjimg zp_pc">
                                <img src="/sop/images/wz_m6-1.png" class="zp_sjimg zp_sj">
                                <img src="/sop/images/wz_m6-1.png" class="zp_pcimg zp_pc">
                                <div class="zpfont_f">
                                    <img src="/sop/images/dpicon.png">
                                    <h2><span>●</span><var>站長強烈建議第一次約見用站內車馬費。根據統計，會刷車馬費的男會員，從未有過詐騙記錄。</var></h2>
                                    <h2><span>●</span><var>切勿接受手機轉帳的截圖，聲稱已轉上或者第二天入賬之類，已有多起假造案例。</var></h2>
                                    <h2><span>●</span><var><font class="sop_bz">系統以及站長信件都是藍底，其他人無法偽造。除了此類信件以外不要相信任何自稱站方或者站長的人。</font></var></h2>
                                </div>
                            </div>
                        </div>
                        <div class="zp_bg step02">
                            <div class="zp_img">
                                <img src="/sop/images/sj1_5.png" class="zp_sjimg zp_sj">
                                <img src="/sop/images/pc_5.png" class="zp_sjimg zp_pc">
                                <div class="kuang_qq"><span></span><font>加站長LINE or 發信到站長信箱</font></div>
                                <img src="/sop/images/zpbt_5.png" class="zp_sjimg zp_sj">
                                <img src="/sop/images/zpbt_5.png" class="zp_pcimg zp_pc">
                                <div class="zpfont_f">
                                    <div class="zongjie">
                                        <div class="tf">提供以下資訊</div>
                                        <h2 style="padding-top: 15px;">1.你的 email 及 對方的暱稱</h2>
                                        <h2 style="padding-top: 5px;">2.跟對方的約定時間地點的對話截圖</h2>
                                        <h2 style="padding-top: 5px;">3.當天消費的發票(記得跟男生要來拍)</h2>
                                        <h2 style="padding-top: 5px;">4.你的銀行帳號(請用打字的方式給予, 勿用截圖的)</h2>
                                        <h2><font class="sop_bz" style="width: 100%; display: table; text-align: center;">站方審核後便會發到你的指定帳戶中</font></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="zp_bg step03">
                            <div class="zp_img">
                                <img src="/sop/images/sj1_6.png" class="zp_sjimg zp_sj">
                                <img src="/sop/images/pc_6.png" class="zp_sjimg zp_pc">
                                <div class="kuang_qq"><span></span><font>A:建議第一次約見在公共場合</font></div>
                                <img src="/sop/images/4.png" class="zp_sjimg zp_sj">
                                <img src="/sop/images/4.png" class="zp_pcimg zp_pc">
                                <div class="zpfont_f"  style="margin-top: 10px;">
                                    <img src="/sop/images/dpicon.png">
                                    <h2><span>●</span><var>假設見面感覺不錯，可以嘗試進入長期關系。</var></h2>
                                    <h2><span>●</span><var>此時可以考慮給Line，你加他或者用二維碼(重要）</var></h2>
                                    <h2><span>●</span><var>此時千萬不要先給其他聯絡方式。</font></var></h2>
                                </div>
                            </div>
                            <div style="width: 95%;  margin: 0 auto;height: 5px; background: #dcdcdc; display: table; margin-top: 20px; margin-bottom: 20px;"></div>
                            <div class="zp_img">
                                <div class="kuang_qq"><span></span><font>B:第二次約見-開始開式約會<i class="new_ii">（或者第一次約見當天直接進入正式約會）</i></font></div>
                                <img src="/sop/images/5.png" class="zp_sjimg zp_sj">
                                <img src="/sop/images/5.png" class="zp_pcimg zp_pc">
                                <div class="zpfont_f" style="margin-top: 10px;">
                                    <img src="/sop/images/dpicon.png">
                                    <h2><span>●</span><var>零用金先拿（黃金守則<font class="showTipsContent tips4" style="color: #f00; cursor: pointer;">“請點我”<img src="/new/images/tips4.png" style="display: none;"></font>，零用金拒絕後付或月結！務必先拿！）</var></h2>
                                    <h2><span>●</span><var>零用金可以先拿 一周（也就是一個月的1/4）</var></h2>
                                    <div class="zongjie">
                                        <div class="tf">總結</div>
                                        <h2>這是花園網強烈建議的約會流程。凡不按照這個約會流程走的建議諸位甜心提高12萬分的警覺。或者點右下聯絡我們加站長的Line諮詢。</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fenye">
                            <a class="prev">上一頁</a>
                            <a class="next" href="#{{$no_read_hash_str??''}}{{$show_sop_type}}_2">下一頁</a>
{{--                            @if($user->isReadManual == 0)--}}
                                <a class="finish" data-sop_manual="two" onclick="location.hash= '#{{$no_read_hash_str??''}}{{$show_sop_type}}_leave';">結束新手教學</a>
{{--                            @endif--}}
                        </div>
                    </div>
                </div>
                @else
                <div id="sop_area_3">
                    <div class="zhapian">
                        <div class="zp_title">進階教學-認識網站進階功能</div>
                        <div class="pagination">
                            <a href="#{{$no_read_hash_str??''}}{{$show_sop_type}}_1" class="pa_cit pa_hover part3" id="step01">Step1</a>
                            <a href="#{{$no_read_hash_str??''}}{{$show_sop_type}}_2" class="pa_cit part3" id="step02">Step2</a>
                            <a href="#{{$no_read_hash_str??''}}{{$show_sop_type}}_3" class="pa_cit part3" id="step03">Step3</a>
                        </div>
                        <div class="zp_bg step01">
                            <div class="zp_img">
                                <img src="/sop/images/sj1_7.png" class="zp_sjimg zp_sj">
                                <img src="/sop/images/pc_7.png" class="zp_sjimg zp_pc">
                                <div class="kuang_qq"><span></span><font>手機驗證</font></div>
                                <div class="zhp_cimg mb-3">
                                    <img src="/sop/images/auth_1.png">
                                </div>
                            </div>
                            <div class="zp_img">
                                <div class="kuang_qq"><span></span><font>進階驗證</font></div>
                                <div class="zhp_cimg mb-3">
                                    <img src="/sop/images/auth_2.png">
                                </div>
                                <div class="zp_tab_f">
                                    <img src="/sop/images/dpicon.png">
                                    <div class="zongjie">
                                        <div class="tf">做驗證的好處?</div>
                                        <h2 style="padding-top: 15px;"><span>●</span><var>提高Daddy對你的信任</var></h2>
                                        <h2 style="padding-top: 5px;"><span>●</span><var>提升約見成功率</var></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="zp_bg step02">
                            <div class="zp_img">
                                <img src="/sop/images/sj1_8.png" class="zp_sjimg zp_sj">
                                <img src="/sop/images/pc_8.png" class="zp_sjimg zp_pc">
                                <div class="kuang_qq"><span></span><font>進行本人/美顏/名人認證</font></div>
                                <img src="/sop/images/auth_3.png" class="zp_ztext_img zp_p">
                                <img src="/sop/images/auth_3.png" class="zp_photo ">
                                <div class="zp_tab_f">
                                    <div class="zongjie">
                                        <div class="tf">獲得站方認證的特殊標記好處有哪些?</div>
                                        <h2 style="padding-top: 15px;"><span>●</span><var>提高Daddy對你的信任</var></h2>
                                        <h2 style="padding-top: 5px;"><span>●</span><var>獲得認證標記後, 幫你更順利找到高優質Daddy</var></h2>
                                        <h2><font class="sop_bz" style="width: 100%; display: table; text-align: center;">※站方認證之申請條件及福利, 請到申請頁面查看</font></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="zp_img">
                                <div class="kuang_qq"><span></span><font>去哪裡做驗證?</font></div>
                                <div class="zp_step">
                                    <h2><span>●</span><var>驗證入口1：專屬頁面</var></h2>
                                </div>
                                <div class="zhp_cimg">
                                    <img src="/sop/images/auth_4.png">
                                </div>
                                <div class="zp_step">
                                    <h2><span>●</span><var>驗證入口2：帳號設定</var></h2>
                                </div>
                                <div class="zhp_cimg">
                                    <img src="/sop/images/auth_5.png">
                                </div>
                            </div>
                        </div>
                        <div class="zp_bg step03">
                            <div class="zp_img">
                                <img src="/sop/images/sj1_9.png" class="zp_sjimg zp_sj">
                                <img src="/sop/images/pc_9.png" class="zp_sjimg zp_pc">
                                <div class="zhp_cimg">
                                    <img src="/sop/images/auth_6.png">
                                </div>

                            </div>
                        </div>
                        <div class="@if($user->engroup==2)zp_tab @else m_zp_tab @endif step03 isReadContent">
                            <a href="/dashboard" class="link_page finish" data-sop_manual="three" onclick="location.hash= '#{{$no_read_hash_str??''}}{{$show_sop_type}}_leave';">
                                <li class="@if($user->engroup==2)zp_line zp_line02 @else m_zp_line m_zp_line02 @endif "><img src="@if($user->engroup==2)/new/images/5_1.png @else/new/images/micon1.png @endif"><span>新手教學結束<i>開始使用網站</i></span></li>
                            </a>
                            <a href="/dashboard/anti_fraud_manual" class="link_page">
                                <li class="@if($user->engroup==2)zp_line02 zp_line03 @else m_zp_line02 m_zp_line03 @endif"><img src="@if($user->engroup==2)/new/images/5_2.png @else/new/images/micon2.png @endif"><span>我想了解更多<i>詐騙避免手冊</i></span></li>
                            </a>
                            <a href="/dashboard/web_manual" class="link_page">
                                <li class="@if($user->engroup==2)zp_line zp_line03 @else m_zp_line m_zp_line03 @endif"><img src="@if($user->engroup==2)/new/images/5_3.png @else/new/images/micon3.png @endif"><span>我想了解更多<i>@if($user->engroup==2)網站進階使用 @else 網站特色@endif</i></span></li>
                            </a>
                            <a href="http://blog-tw.net/Sugar/%e5%8c%85%e9%a4%8a%ef%bc%8d%e5%a4%a7%e5%8f%94%e7%af%87/" target="_blank" class="link_page link_blank">
                                <li><img src="@if($user->engroup==2)/new/images/5_4.png @else/new/images/micon4.png @endif"><span>我想了解更多<i>站長的經驗分享</i></span></li>
                            </a>
                        </div>
                        <div class="fenye">
                            <a class="prev">上一頁</a>
                            <a class="next" href="#{{$no_read_hash_str??''}}{{$show_sop_type}}_2">下一頁</a>
                            {{--                            @if($user->isReadManual == 0)--}}
                            <a class="finish" data-sop_manual="three" onclick="location.hash= '#{{$no_read_hash_str??''}}{{$show_sop_type}}_leave';">結束新手教學</a>
                            {{--                            @endif--}}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @include('partials.image-zoomin')

    <div class="announce_bg" id="announce_bg" onclick="gmBtnNoReload()" style="display:none;"></div>
    <div class="bl bl_tab " id="sopPop1" style="top: 10%;">
        <div class="bltitle"><font>進階資料</font></div>
        <div class="new_poptk">
            <p style="text-align: center; color: #333; font-size: 16px; margin-bottom: 10px;">搜索頁-點擊Daddy頭像-進入基本資料頁</p>
            <img src="/sop/images/zpbt_3.png" style="width:100%;">
        </div>
        <a onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>
    <div class="bl bl_tab " id="sopPop2" style="top: 10%;">
        <div class="bltitle"><font>提示</font></div>
        <div class="new_poptk">

            <img src="/sop/images/zpbt_4.png" style="width:100%;">
        </div>
        <a onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>
@stop
@section('javascript')

<script>
function dianwo1() {
    $("#announce_bg").show()
    $("#sopPop1").show()
    $('body').css("overflow", "hidden")
}
function dianwo2() {
    $("#announce_bg").show()
    $("#sopPop2").show()
    $('body').css("overflow", "hidden")
}
function gmBtn1() {
    $("#announce_bg").hide();
    $("#sopPop1").hide();
    $("#sopPop2").hide();
    $('body').css("overflow", "auto");
}


</script>
<script>
    // 計算瀏覽時間
    var page_id = 'newer_manual';

    var active,active_class;

    @if($user->engroup==1)
        active = 'b_sop mm';
        active_class = '.mm';
    @else
        active = 'pa_hover';
        active_class = '.pa_hover';
    @endif

    $('.zp_bg').hide();
    $('.zp_tab').hide();
    $('.m_zp_tab').hide();

    $('.prev').hide();
    $('.finish').hide();
    $('.pa_cit').removeClass(active);

    $('.step01').show();
    $('#step01').addClass(active);

    $('.pa_cit').click(function () {
        var isRead='{{ $user->is_read_female_manual_part3 }}';
        if($(this).hasClass('part1')){
            isRead = '{{ $user->is_read_female_manual_part1 }}';
        }else if($(this).hasClass('part2')){
            isRead = '{{ $user->is_read_female_manual_part2 }}';
        }
        if(isRead == 1 ) {
            $('.pa_cit').removeClass(active);
            $(this).addClass(active);
            var id = $(this).attr('id');
            var prev_id = 'step0' +(parseInt(id.slice(-1))-1);
            var next_id = 'step0' +(parseInt(id.slice(-1))+1);
            localStorage.setItem(id, 'Y');
            $('.zp_bg').hide();
            $('.zp_tab').hide();
            $('.m_zp_tab').hide();
            $('.' + id).show();
            if($('#' + next_id).length>0) {
                $('.next').attr('href',$('#' + next_id).attr('href')).data('real_href',$('#' + next_id).attr('href'));
            }
            if($('#' + prev_id).length>0) {
                $('.prev').attr('href',$('#' + prev_id).attr('href')).data('real_href',$('#' + prev_id).attr('href'));
            }
            if (id == 'step01') {
                $('.prev').hide();
                $('.next').show();
                $('.finish').hide();
            } else if (id == 'step03') {
                $('.prev').show();
                $('.next').hide();
                $('.finish').show();
            } else {
                $('.prev').show();
                $('.next').show();
                $('.finish').hide();
            }
        }
        else {
            return false;
        }

    });

    $('.prev').click(function() {
        var now_id = $(active_class).attr('id');
        var id = parseInt(now_id.slice(-1))-1;
        var prev_id =  'step0' + id;
        var prev_prev_id =  'step0' + (id-1);
        localStorage.setItem(now_id,'Y');
        $('.next').attr('href',location.hash).data('real_href',location.hash);
        $('.pa_cit').removeClass(active);
        $('#'+ prev_id).addClass(active);
        $('.zp_bg').hide();
        $('.zp_tab').hide();
        $('.m_zp_tab').hide();
        $('.'+ prev_id).show()
        if(prev_id=='step01'){
            $('.prev').hide();
            $('.next').show();
            $('.finish').hide();
        }else if(prev_id=='step03'){
            $('.prev').show();
            $('.next').hide();
            $('.finish').show();
        }else{
            $('.prev').show();
            $('.next').show();
            $('.finish').hide();
        }
        $('html,body').animate({ scrollTop: 0 }, 'slow');
    
        if($(this).data('real_href')!=undefined ) {
            $(this).attr('href',$(this).data('real_href'))
        }

        if($('#'+ prev_prev_id).length>0) {
            $(this).data('real_href',$('#'+ prev_prev_id).attr('href'));    
        }
        
        
    
    });
    $('.next').click(function() {
        var now_id = $(active_class).attr('id');
        var id = parseInt(now_id.slice(-1))+1;
        var next_id =  'step0' + id;
        var next_next_id =  'step0' + (id+1);
        localStorage.setItem(now_id,'Y');
        $('.prev').attr('href',location.hash!=''?location.hash:$('.pa_cit.'+active).attr('href'));
        $('.prev').data('real_href',$('.prev').attr('href'));
        $('.pa_cit').removeClass(active);
        $('#'+ next_id).addClass(active);             
        $('.zp_bg').hide();
        $('.zp_tab').hide();
        $('.m_zp_tab').hide();
        $('.'+ next_id).show();
        if(next_id=='step01'){
            $('.prev').hide();
            $('.next').show();
            $('.finish').hide();
        }else if(next_id=='step03'){
            $('.prev').show();
            $('.next').hide();
            $('.finish').show();
        }else{
            $('.prev').show();
            $('.next').show();
            $('.finish').hide();
        }
        $('html,body').animate({ scrollTop: 0 }, 'slow');

        if($(this).data('real_href')!=undefined ) {
            $(this).attr('href',$(this).data('real_href'))
        }

        $(this).data('real_href',$('#'+ next_next_id).attr('href'));
    });


    let pagePath = '';
    let link_blank = '';
    $('.link_page').on('click',function(){
        pagePath = $(this).attr('href');
        link_blank = $(this).hasClass('link_blank');
    });

    $('.isReadContent').click(function() {
        event.preventDefault();
        localStorage.setItem('stop05','Y');
        var isRead = '{{ $user->isReadManual }}';
        if(isRead == 0 ){
            $.ajax({
                type: 'POST',
                url: "/dashboard/newer_manual/isRead?{{csrf_token()}}={{now()->timestamp}}",
                data:{
                    _token: '{{csrf_token()}}',
                    s{{ str_random() }}: '{{ str_random() }}'
                },
                dataType:"json",
                complete: function () {
                    if(link_blank)
                        window.open(pagePath,'_blank');
                    else
                        window.location.href= pagePath;
                }
            });
        } else {
            if(link_blank)
                window.open(pagePath,'_blank');
            else
                window.location.href= pagePath;
        }
   });

    $('.finish').click(function() {
        var sop_type=$(this).attr('data-sop_manual');
        finish_female_manual(sop_type);
    });

    function finish_female_manual(sop_type){
        $.ajax({
            type: 'POST',
            url: "/dashboard/female_newer_manual/isRead?{{csrf_token()}}={{now()->timestamp}}",
            data:{
                _token: '{{csrf_token()}}',
                sop_type:sop_type
            },
            dataType:"json",
            complete: function () {
                window.location.href = '/dashboard/personalPage';
            }
        });
    }
    
    if(location.hash!='' && location.hash.indexOf('#nr_fnm')<0) {
        $('a.pa_cit[href="'+location.hash+'"]').click();
    }    

    $('.scroll-bottom').click(function(){
        $("html,body").animate({ scrollTop: $('html').scrollTop() + $(window).height() }, 1000);
    });
    $(window).scroll(function(){
        let now = $('html').scrollTop();
        let limit = $(document).height() - $(window).height() - 100;
        if(now < limit) {
            $('.scroll-bottom').show();
        }else {
            $('.scroll-bottom').hide();
        }
    })
</script>
@stop
