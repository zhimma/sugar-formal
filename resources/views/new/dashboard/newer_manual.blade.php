@extends('new.layouts.website')

@section('app-content')
    <style>

        @media (hover: none) {
            .fenye a:hover {
                background: #ffffff;
                    color: #fd5678; }
        }
</style>
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="zhapian">
                    <div class="zp_title @if($user->engroup==1)b_sop @endif">新手教學-約見SOP</div>
                    <div class="ppp">
                        <a href="javascript:void(0);" class="pa_cit @if($user->engroup==1)b_sop mm @else pa_hover @endif" id="step01">STEP1</a>
                        <a href="javascript:void(0);" class="pa_cit" id="step02">STEP2</a>
                        <a href="javascript:void(0);" class="pa_cit" id="step03">STEP3</a>
                        <a href="javascript:void(0);" class="pa_cit" id="step04">STEP4</a>
                        <a href="javascript:void(0);" class="pa_cit" id="step05">STEP5</a>
                    </div>
                    <div class="zp_bg step01">
                        <div class="zp_img">
                            @if($user->engroup==2)
                            <img src="/new/images/sj1_1.png" class="zp_sjimg zp_sj">
                            <img src="/new/images/pc_1.png" class="zp_sjimg zp_pc">
                            <img src="/new/images/1.png" class="zp_sjimg zp_sj">
                            <img src="/new/images/1.png" class="zp_pcimg zp_pc">
                            @else
                                <img src="/new/images/m_sj1_1.png" class="zp_sjimg zp_sj">
                                <img src="/new/images/m_pc1.png" class="zp_sjimg zp_pc">
                                <img src="/new/images/1_mm.png" class="zp_sjimg zp_sj">
                                <img src="/new/images/1_mm.png" class="zp_pcimg zp_pc">
                            @endif
                            <div class="zpfont_f">
                                @if($user->engroup==2)
                                <img src="/new/images/dpicon.png">
                                <h2><span>●</span><var>強烈建議上傳四張照片取得VIP權限</var></h2>
                                <h2><span>●</span><var>建議挑選VIP會員，VIP會員的時間越久越好。</var></h2>
                                <h2><span>●</span>
                                    <var>車馬費邀請次數越多越好
                                        <span class="showTipsContent tips1" style="color:red; font-size:18px;"><img src="/new/images/tips1.png" style="display: none">(如何看男會員的車馬費邀請次數)</span>
                                    </var>
                                </h2>
                                @else
                                    <img src="/new/images/dpicon_m.png">
                                    <h2><span class="sop_f">●</span><var>新進甜心就是最近剛註冊的女會員</var></h2>
                                    <h2><span class="sop_f">●</span>

                                        <var>盡量避開八大行業(如何判斷八大行業<font class="showTipsContent tips3" style="color: #f00;"><img src="/new/images/tips3.jpg" style="display: none">"請點我"</font>)</var>
                                    </h2>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="zp_bg step02">
                        <div class="zp_img">
                            @if($user->engroup==2)
                            <img src="/new/images/sj1_2.png" class="zp_sjimg zp_sj">
                            <img src="/new/images/pc_2.png" class="zp_sjimg zp_pc">
                            <img src="/new/images/2.png" class="zp_sjimg zp_sj">
                            <img src="/new/images/2.png" class="zp_pcimg zp_pc">
                            @else
                            <img src="/new/images/m_sj1_2.png" class="zp_sjimg zp_sj">
                            <img src="/new/images/m_pc2.png" class="zp_sjimg zp_pc">
                            <img src="/new/images/2_m.png" class="zp_sjimg zp_sj">
                            <img src="/new/images/2_m.png" class="zp_pcimg zp_pc">
                            @endif
                            <div class="zpfont_f">
                                @if($user->engroup==2)
                                <img src="/new/images/dpicon.png">
                                <h2><span>●</span><var>數字有基本共識後，請對方刷站內車馬費。如果對方不願刷又不是VIP，站長強烈建議你要提高警覺，千萬不要接受”任何”後付款的條件。</var></h2>
                                <h2><span>●</span><var>此時站長強烈不建議給 line。</var></h2>
                                <h2><span>●</span><var>如果一定要互加通訊軟體，強烈建議請新辦一個之前沒使用過的通訊軟體，<font class="sop_bz">ID千萬不要取跟自己相關的例如自己的英文名字，生日等等。</font></var></h2>
                                @else
                                <img src="/new/images/dpicon_m.png">
                                <h2><span class="sop_f">●</span><var>第一次約見給車馬費是禮貌，強烈建議使用站方的車馬費。或者也可以見面後給。</var></h2>
                                <h2><span class="sop_f">●</span><var>如果女生堅持不使用站方的車馬費制度，要匯款進他的帳戶，請提高警覺。這是你們雙方的自由，<font class="sop_bz">站方對此極度不建議，但也不禁止。</font></var></h2>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="zp_bg step03">
                        <div class="zp_img">
                            @if($user->engroup==2)
                            <img src="/new/images/sj1_3.png" class="zp_sjimg zp_sj">
                            <img src="/new/images/pc_3.png" class="zp_sjimg zp_pc">
                            <img src="/new/images/3.png" class="zp_sjimg zp_sj">
                            <img src="/new/images/3.png" class="zp_pcimg zp_pc">
                            @else
                            <img src="/new/images/m_sj1_3.png" class="zp_sjimg zp_sj">
                            <img src="/new/images/m_pc3.png" class="zp_sjimg zp_pc">
                            <img src="/new/images/3_m.png" class="zp_sjimg zp_sj">
                            <img src="/new/images/3_m.png" class="zp_pcimg zp_pc">
                            @endif
                            <div class="zpfont_f">
                                @if($user->engroup==2)
                                <img src="/new/images/dpicon.png">
                                <h2><span>●</span><var>站方強烈建議第一次約見用站內車馬費。根據統計，會刷車馬費的男會員，從未有過詐騙紀錄。</var></h2>
                                <h2><span>●</span><var>切勿接受手機轉帳的截圖，聲稱已轉上或者第二天才入帳之類，已有多起假造案例。</var></h2>
                                <h2><span>●</span><var><font class="sop_bz">系統以及站長信件都是藍底，其他人無法偽造。除了此類信件以外不要相信任何自稱站方或者站長的人。</font></var></h2>
                                @else
                                    <img src="/new/images/dpicon_m.png">
                                    <h2><span class="sop_f">●</span><var>車馬費成功刷付之後，系統會同時寄信給雙方。</var></h2>
                                    <h2><span class="sop_f">●</span><var><font class="sop_bz">系統以及站長信件都是藍底，其他人無法偽造。除了此類信件以外不要相信任何自稱站方或者站長的人。</font></var></h2>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="zp_bg step04">
                        <div class="zp_img">
                            @if($user->engroup==2)
                            <img src="/new/images/sj1_4.png" class="zp_sjimg zp_sj">
                            <img src="/new/images/pc_4.png" class="zp_sjimg zp_pc">
                            <img src="/new/images/4.png" class="zp_sjimg zp_sj">
                            <img src="/new/images/4.png" class="zp_pcimg zp_pc">
                            @else
                                <img src="/new/images/m_sj1_4.png" class="zp_sjimg zp_sj">
                                <img src="/new/images/m_pc4.png" class="zp_sjimg zp_pc">
                                <img src="/new/images/4.png" class="zp_sjimg zp_sj">
                                <img src="/new/images/4.png" class="zp_pcimg zp_pc">
                            @endif
                                <div class="zpfont_f">
                                @if($user->engroup==2)
                                <img src="/new/images/dpicon.png">
                                <h2><span>●</span><var>假設見面感覺不錯，可以嘗試進入長期關係。</var></h2>
                                <h2><span>●</span><var>此時可以考慮給Line，你加他或者用二維碼(重要)。</var></h2>
                                <h2><span>●</span><var>此時尚不要給其它聯絡方式。</var></h2>
                                @else
                                <img src="/new/images/dpicon_m.png">
                                <h2><span class="sop_f">●</span><var>第一次約見盡量約在公開場合。例如星巴克，丹堤等。進可攻退可守，雙方都有意可以續約下一攤吃飯，或者直接進入包養主題。不喜歡就走人。
                                    </var></h2>
                                <h2><span class="sop_f">●</span><var>女方第一次就跟你約旅館的，很大機會是八大行業
                                        <a class="showTipsContent tips3" style="color:#f00"><img src="/new/images/tips3.jpg" style="display: none">(請點我)</a>
                                    </var>
                                </h2>
                                <h2><span class="sop_f">●</span><var>刷卡買點數的，請直接拒絕。</var></h2>
                                <h2><span class="sop_f">●</span><var>到了定點後，不斷變換見面地點的，請直接離開並封鎖。</var></h2>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="zp_bg step05">
                        <div class="zp_img">
                            @if($user->engroup==2)
                            <img src="/new/images/sj1_5.png" class="zp_sjimg zp_sj">
                            <img src="/new/images/pc_5.png" class="zp_sjimg zp_pc">
                            <img src="/new/images/5.png" class="zp_sjimg zp_sj">
                            <img src="/new/images/5.png" class="zp_pcimg zp_pc">
                            @else
                                <img src="/new/images/m_sj1_5.png" class="zp_sjimg zp_sj">
                                <img src="/new/images/m_pc5.png" class="zp_sjimg zp_pc">
                                <img src="/new/images/5.png" class="zp_sjimg zp_sj">
                                <img src="/new/images/5.png" class="zp_pcimg zp_pc">
                            @endif
                            <div class="zpfont_f zp_top">
                                @if($user->engroup==2)
                                <img src="/new/images/dpicon.png">
                                <h2><span>●</span>
                                    <var>零用金先拿（黃金守則<font class="showTipsContent tips4" style="color: #f00;">“請點我”<img src="/new/images/tips4.png" style="display: none;"></font>，零用金拒絕後付或月結！務必先拿！）</var>
                                </h2>
                                <h2><span>●</span><var>零用金可以先拿一周(也就是一個月的1/4)</var></h2>
                                    <div class="zongjie">
                                        <div class="tf">總結</div>
                                        <h2>這是花園網強烈建議的約會流程。凡不按照這個約會流程走的建議諸位甜心提高12萬分的警覺。或者點右下聯絡我們加站長的Line諮詢。</h2>
                                    </div>
                                @else
                                    <img src="/new/images/dpicon_m.png">
                                    <h2><span class="sop_f">●</span><var><font class="sop_bz">零用金務必先給！</font>太多網蟲詐騙都用<font class="sop_bz">月結</font>這招詐騙女會員，故站方不建議這種方式。但提前給會有一定風險，所以請男會員自行拿捏。</var></h2>
                                    <h2><span class="sop_f">●</span><var>出門約會一定要準備足夠現金。</var></h2>
                                    <h2><span class="sop_f">●</span><var>第一個月可以按次給(感覺比較差)或者按周給(每個月的1/4)。</var></h2>
                                    <h2><span class="sop_f">●</span><var>如果女生堅持要一筆錢或者一次付清一個月。網站並不建議，但也不介入。請各位男會員自行評估。</var></h2>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="@if($user->engroup==2)zp_tab @else m_zp_tab @endif step05 isReadContent">
                    	 <a href="/dashboard" class="link_page">
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
                        <a class="next">下一頁</a>
                        @if($user->isReadManual == 0)
                            <a class="finish">結束新手教學</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partials.image-zoomin')
@stop
@section('javascript')
<script>
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

        var isRead = '{{ $user->isReadManual }}';
        if(isRead == 1 ) {
            $('.pa_cit').removeClass(active);
            $(this).addClass(active);
            var id = $(this).attr('id');
            localStorage.setItem(id, 'Y');
            $('.zp_bg').hide();
            $('.zp_tab').hide();
            $('.m_zp_tab').hide();
            $('.' + id).show();
            if (id == 'step01') {
                $('.prev').hide();
                $('.next').show();
                $('.finish').hide();
            } else if (id == 'step05') {
                $('.prev').show();
                $('.next').hide();
                $('.finish').show();
            } else {
                $('.prev').show();
                $('.next').show();
                $('.finish').hide();
            }
        }
    });

    $('.prev').click(function() {
        var now_id = $(active_class).attr('id');
        var prev_id =  'step0' + (now_id.slice(-1) - 1);
        localStorage.setItem(now_id,'Y');
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
        }else if(prev_id=='step05'){
            $('.prev').show();
            $('.next').hide();
            $('.finish').show();
        }else{
            $('.prev').show();
            $('.next').show();
            $('.finish').hide();
        }
        $('html,body').animate({ scrollTop: 0 }, 'slow');
    });
    $('.next').click(function() {
        var now_id = $(active_class).attr('id');
        var id = parseInt(now_id.slice(-1))+1;
        var next_id =  'step0' + id;
        localStorage.setItem(now_id,'Y');
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
        }else if(next_id=='step05'){
            $('.prev').show();
            $('.next').hide();
            $('.finish').show();
        }else{
            $('.prev').show();
            $('.next').show();
            $('.finish').hide();
        }
        $('html,body').animate({ scrollTop: 0 }, 'slow');
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
                url: "/dashboard/newer_manual/isRead",
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
        $.ajax({
            type: 'POST',
            url: "/dashboard/newer_manual/isRead",
            data:{
                _token: '{{csrf_token()}}'
            },
            dataType:"json",
            complete: function () {
                window.location.href = '/dashboard';
            }
        });
    });

</script>
@stop
