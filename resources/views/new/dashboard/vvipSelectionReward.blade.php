@extends('new.layouts.website')

@section('app-content')
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10 g_pnr01">
                <!--  -->
                <div class="v_bg_fb">
                    <div class="v_bimg v_yc_sj" ><img src="/new/images/xb01.png"></div>
                    <div class="v_bimg v_yc_pc" ><img src="/new/images/xb02.png"></div>
                    <div class="v_fb_k">
                        <img src="/new/images/xb03.png" class="v_k1img01">
                        <div class="v_fb_knr">{!! $area1_title->content !!}</div>
                        <img src="/new/images/xb05.png" style="width: 95%; margin: 0 auto; display: table;">
                        <div class="v_fanr02">
                            {!! $area1->content !!}
{{--                            <li>--}}
{{--                                <span class="v_ficon"></span>--}}
{{--                                <span class="v_ftext">徵選活動將以醒目的方式出現，當您發佈的活動通過審核，女方一上線則會看到。<a onclick="fanli()">(點我看範例)</a></span>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <span class="v_ficon"></span>--}}
{{--                                <span class="v_ftext">站方將依照您的徵選條件指定酬金，同意後匯款至指定帳戶即開始徵選活動。匯款後請於專屬頁面填入帳號後五碼。</span>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <span class="v_ficon"></span>--}}
{{--                                <span class="v_ftext">若有女會員應徵，站方將依您的選拔條件進行審核。</span>--}}
{{--                            </li>--}}
                        </div>
                    </div>
                    <img src="/new/images/xb05.png" style="width: 100%; margin: 0 auto; display: table; height: 15px;">

                    <div class="v_fb_k_gz">
                        <img src="/new/images/xb04.png" class="v_k1img01">
                        <div class="v_fanr02">
                            {!! $area2->content !!}
{{--                            <li class="v_fanr02_o">--}}
{{--                                <span class="v_ficon01"></span>--}}
{{--                                <span class="v_ftext">只要女會員通過條件審核，不論是否約會成功，站方將立即發放50%的酬金予女會員。</span>--}}
{{--                            </li>--}}
{{--                            <li class="v_fanr02_o">--}}
{{--                                <span class="v_ficon01"></span>--}}
{{--                                <span class="v_ftext">如果該女會員看過您的資料拒絕與您互動，此筆費用不退還。</span>--}}
{{--                            </li>--}}
{{--                            <li class="v_fanr02_o">--}}
{{--                                <span class="v_ficon01"></span>--}}
{{--                                <span class="v_ftext">通過條件驗證的女會員將出現在您的懸賞資料夾中。</span>--}}
{{--                            </li>--}}
{{--                            <li class="v_fanr02_o">--}}
{{--                                <span class="v_ficon01"></span>--}}
{{--                                <span class="v_ftext">通過條件驗證女會員達到徵選人數後停止該項活動。</span>--}}
{{--                            </li>--}}
                        </div>
                    </div>
                    <img src="/new/images/xb06.png" style="width: 100%; margin: 0 auto; display: table; height: 15px;">

                    <div class="v_fb_k_gz">
                        <img src="/new/images/xb07.png" class="v_k1img01">
                        <div class="v_fanr02">
                            {!! $area3->content !!}
{{--                            <li class="v_fanr02_o">--}}
{{--                                <span class="v_ficon01"></span>--}}
{{--                                <span class="v_ftext">剩餘50% 酬金作為車馬費。只要雙方約見成功或者私下交換聯絡方式，則無條件發放予女方。</span>--}}
{{--                            </li>--}}
{{--                            <li class="v_fanr02_o">--}}
{{--                                <span class="v_ficon01"></span>--}}
{{--                                <span class="v_ftext">若見面後女方並不符合條件，請務必拍照或者用其他方式舉證，否則此筆費用將預設發予女會員</span>--}}
{{--                            </li>--}}
                        </div>
                    </div>
                    <img src="/new/images/xb06.png" style="width: 100%; margin: 0 auto; display: table; height: 15px;">

                    <div class="v_ftextf">
                        {!! $area4->content !!}
{{--                        <h2>任何爭議問題皆以站方解釋為主。本人絕無意見！</h2>--}}
{{--                        <h2>以上文字若有任何一點不同意請點[取消]</h2>--}}
                    </div>

                    <div class="n_txbut matop20">
                        <a href="/dashboard/vvipSelectionRewardApply" class="se_but1 ">我同意</a>
                        <a href="/dashboard/vvipPassSelect" class="se_but2">取消</a>
                    </div>


                </div>

                <!--  -->
            </div>
        </div>
    </div>

    <div class="announce_bg" onclick="gmBtnNoReload()" style="display:none;"></div>
    <div class="bl bl_tab t_op" id="tab_fanli" style="background: transparent; border: none;  ">
        <a onclick="gmBtnNoReload()" class="bl_gbgg"><img src="/new/images/gb_icon.png"></a>
        <img src="/new/images/tc.png" class="hycov_11" style="position: relative;">
    </div>
@stop

@section('javascript')
    <script>

        function fanli(){
            $(".announce_bg").show();
            $("#tab_fanli").show();
        }

        $(document).ready(function() {
            @if(Session::has('message'))
            c5("{{Session::get('message')}}");
            <?php session()->forget('message');?>
            @endif
        });
    </script>
@stop
