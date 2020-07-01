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
                                <img src="/new/images/1_m.png" class="zp_sjimg zp_sj">
                                <img src="/new/images/1_m.png" class="zp_pcimg zp_pc">
                            @endif
                            <div class="zpfont_f">
                                @if($user->engroup==2)
                                <img src="/new/images/dpicon.png">
                                <h2><span>●</span><var>強烈建議上傳四張照片取得VIP權限</var></h2>
                                <h2><span>●</span><var>建議挑選VIP會員，VIP會員的時間越久越好。(如何看VIP會員的時間)</var></h2>
                                <h2><span>●</span><var>車馬費邀請次數越多越好(如何看男會員的車馬費邀請次數)</var></h2>
                                @else
                                    <img src="/new/images/dpicon_m.png">
                                    <h2><span class="sop_f">●</span><var>新進甜心就是最近剛註冊的女會員</var></h2>
                                    <h2><span class="sop_f">●</span><var>盡量避開八大行業(如何判斷八大行業"<a href="{{ url('/dashboard/anti_fraud_manual#m_page04') }}"><font color="red">請點我</font></a>")</var></h2>
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
                                <h2><span>●</span><var>數字有基本共識後，請對方刷站內車馬費。如果對方願刷又不是VIP，站長強烈建議你要提高警覺，千萬不要接受"任何"後付款的條件。</var></h2>
                                <h2><span>●</span><var>此時不建議給Line</var></h2>
                                @else
                                <img src="/new/images/dpicon_m.png">
                                <h2><span class="sop_f">●</span><var>第一次約見給車馬費是禮貌，強烈建議使用站方的車馬費。或者也可以見面後給。</var></h2>
                                <h2><span class="sop_f">●</span><var>如果女生堅持一定要先刷，請提高警覺。<font class="sop_bz">站方不對此做任何保證。</font></var></h2>
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
                                <h2><span>●</span><var>此步驟並非絕對必要，但根據統計，男會員有車馬費的邀約。詐騙機率低於 1%。</var></h2>
                                <h2><span>●</span><var>切勿接受手機截圖的轉帳，聲稱已轉帳第二天才會入帳之類，很容易假造。</var></h2>
                                @else
                                    <img src="/new/images/dpicon_m.png">
                                    <h2><span class="sop_f">●</span><var>本站統計：刷車馬費的邀約成功率 xx%，VIP邀約成功率 xx%。普通會員邀約成功率 xx%。</var></h2>
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
                                <h2><span>●</span><var>假設見面感覺不錯願意嘗試長期關係可以進入第三步驟</var></h2>
                                <h2><span>●</span><var>此時可以考慮給 Line，你加他或者用二維碼(重要)</var></h2>
                                @else
                                <img src="/new/images/dpicon_m.png">
                                <h2><span class="sop_f">●</span><var>如果看了有喜歡，女方也不反對，避免夜長夢多，站長強烈建議當天立刻進入下一個步驟。</var></h2>
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
                                <h2><span>●</span><var>零用金先拿(黃金守則不接受任何條件)</var></h2>
                                <h2><span>●</span><var>零用金可以先拿一周(也就是一個月的1/4)</var></h2>
                                    <div class="zongjie">
                                        <div class="tf">總結</div>
                                        <h2>這是花園網強烈建議的約會流程。凡不按照這個約會流程走的建議諸位甜心提高12萬分的警覺。或者點右下聯絡我們加站長的Line諮詢。</h2>
                                    </div>
                                @else
                                    <img src="/new/images/dpicon_m.png">
                                    <h2><span class="sop_f">●</span><var>零用金務必先給，喜歡後給的要去怪詐騙網蟲。這些人把後給的名聲弄得太糟了(出門約會一定要準備足夠現金)。</var></h2>
                                    <h2><span class="sop_f">●</span><var>一開始可以按次給(感覺比較差)或者按周給(每個月的1/4)。</var></h2>
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
                            <li class="@if($user->engroup==2)zp_line zp_line03 @else m_zp_line m_zp_line03 @endif"><img src="@if($user->engroup==2)/new/images/5_3.png @else/new/images/micon3.png @endif"><span>我想了解更多<i>@if($user->engroup==2)開始使用網站 @else 網站特色@endif</i></span></li>
                        </a>
                        <a href="http://blog-tw.net/Sugar/" target="_blank" class="link_page link_blank">
                            <li><img src="@if($user->engroup==2)/new/images/5_4.png @else/new/images/micon4.png @endif"><span>我想了解更多<i>站長的經驗分享</i></span></li>
                        </a>
		            </div>
                    <div class="fenye">
                        <a class="prev">上一頁</a>
                        <a class="next">下一頁</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
            } else if (id == 'step05') {
                $('.prev').show();
                $('.next').hide();
            } else {
                $('.prev').show();
                $('.next').show();
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
        }else if(prev_id=='step05'){
            $('.prev').show();
            $('.next').hide();
        }else{
            $('.prev').show();
            $('.next').show();
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
        }else if(next_id=='step05'){
            $('.prev').show();
            $('.next').hide();
        }else{
            $('.prev').show();
            $('.next').show();
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
                    _token: '{{csrf_token()}}'
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

</script>
@stop
