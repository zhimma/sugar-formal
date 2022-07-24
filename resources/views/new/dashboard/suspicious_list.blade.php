@extends('new.layouts.website')

@section('app-content')
<div class="container matop70">
    <div class="row">
        <div class="col-sm-2 col-xs-2 col-md-2 dinone">
            @include('new.dashboard.panel')
        </div>
        <div class="col-sm-12 col-xs-12 col-md-10">
            <div class="shou" style="text-align: center; position: relative;">
                <a href="/dashboard/forum" class="toug_back btn_img" style=" position: absolute; left:-6px;">
                    <div class="btn_back"></div>
                </a>
                <div style="position: absolute; left:45px;">
                    <span>可疑銀行帳號交流區</span>
                </div>
                <a href="/dashboard/suspicious_posts" class="aid_but"><img src="/posts/images/tg_03.png">我要提報</a>
            </div>
            <div class="g_pnr mabot50">
                <div class="zhapian_zl">
                    <input placeholder="搜尋對方暱稱或帳號末五碼" class="zhap_input" value="{{ Request()->get('q') }}"><a class="zhap_search"><img src="/new/images/zhapian_icon1.png">搜尋</a>
                </div>

                <div class="zhp_list">
                    <div class="zhp_ptitle"><img src="/new/images/zhapian_icon2nn.png">搜尋結果</div>
                    <div class="zhap_new">
                        <a id="suspicious_tab1" target=_parent>使用者糾紛<span>有見過面</span></a>
                        <a id="suspicious_tab2" target=_parent>車馬費詐騙<span>沒見過面</span></a>
                    </div>
                    <!--使用者糾紛有見過面-->
                    <div class="zap_ullist" id="ss">
                        @if($suspicious_type1->count()>0)
                            @foreach($suspicious_type1 as $suspicious)
                            <div class="zap_ullist_a">
                                <div class="zap_aa">
                                    <h2>提報者<span class="pa120">{{ $suspicious->reporter_name }}</span></h2>
                                    <h2>提報時間<span>{{ $suspicious->created_at }}</span></h2>
                                    <h2>糾紛對象<span><a href="/dashboard/viewuser/{{$suspicious->user_id}}">{{ $suspicious->reported_name }}</a></span></h2>
                                    @php
                                        $str_c=substr($suspicious->account_text, 0 , strlen($suspicious->account_text)-5) ;
                                        $str_star='';
                                        for($i=1 ;$i<=strlen($suspicious->account_text)-5;$i++){
                                            $str_star.='*';
                                        }
                                        if(is_null($suspicious->reporter_user_id_list)){
                                            $reporter_user_id_list_cnt=0;
                                        }else{
                                            $arr=explode(',',$suspicious->reporter_user_id_list);
                                            foreach ($arr as $key => $value){
                                                if(empty($value)){
                                                    unset($arr[$key]);
                                                }
                                            }
                                            $reporter_user_id_list_cnt=count($arr);
                                        }
                                    @endphp
                                    <h2>銀行帳號<span>{{ $str_star.str_replace($str_c, '', $suspicious->account_text) }}</span></h2>
                                    @if($reporter_user_id_list_cnt)
                                        <h2>被提報次數<span>{{ $reporter_user_id_list_cnt }}</span></h2>
                                    @endif
                                    @if($suspicious->reporter_user_id==auth()->user()->id)
                                        <div class="ap_butnew">
                                            <a onclick="postDelete({{ $suspicious->id }})" class="sc_cc"><img src="/new/images/del_03n.png">刪除</a>
                                            <a href="/dashboard/view_suspicious_edit/{{ $suspicious->id }}" class="sc_cc"><img src="/new/images/xiugai.png">修改</a>
                                        </div>
                                    @endif
                                </div>
                                <div class="zap_bb">
                                    <h2>舉報原因</h2>
                                    <div class="text">
                                        <span style="word-break: break-all;">{{ $suspicious->reason }}</span>
                                        <a>…<em>+全文</em></a>
                                    </div>
                                </div>
                                <div class="zap_bb zapbot">
                                    <h2>照片上傳</h2>
                                    <div class="zap_photo">
                                        @if($suspicious->images)
                                            @foreach(json_decode($suspicious->images, true) as $key => $image)
                                                @if($key<3 ||($key==3 && count(json_decode($suspicious->images, true))==4))
                                                    <li><img src="{{ $image }}"></li>
                                                @elseif($key==3)
                                                    <li><img src="{{ $image }}"> <em>+{{ count(json_decode($suspicious->images, true))-4 }}</em></li>
                                                @elseif($loop->iteration >=4)
                                                    <li style="display: none;"><img src="{{ $image }}"></li>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="ky_sy">
                                    @if(auth()->user()->id!==$suspicious->reporter_user_id  && !in_array(auth()->user()->id, explode(',',$suspicious->reporter_user_id_list)))
                                        <div class="kyzh_b" onclick="zh({{ $suspicious->id }})"><img src="/new/images/qz.png">我也被這個銀行帳號騙過</div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        @else
                            <img src="/new/images/wuziliao.png" class="ziliao_wimg">
                            <div class="zlfoxinx">查無資料</div>
                        @endif
                    </div>
                    <!-- end -->
                    <!--車馬費詐騙沒見過面-->
                    <div class="zap_ullist" id="ss2" style="display: none;">
                        @if($suspicious_type2->count()>0)
                            @foreach($suspicious_type2 as $suspicious)
                            <div class="zap_ullist_a">
                                <div class="zap_aa">
                                    <h2>提報者<span class="pa120">{{ $suspicious->reporter_name }}</span></h2>
                                    <h2>提報時間<span>{{ $suspicious->created_at }}</span></h2>
                                    <h2>糾紛對象<span><a href="/dashboard/viewuser/{{$suspicious->user_id}}">{{ $suspicious->reported_name }}</a></span></h2>
                                    @php
                                        $str_c=substr($suspicious->account_text, 0 , strlen($suspicious->account_text)-5) ;
                                        $str_star='';
                                        for($i=1 ;$i<=strlen($suspicious->account_text)-5;$i++){
                                            $str_star.='*';
                                        }
                                        if(is_null($suspicious->reporter_user_id_list)){
                                            $reporter_user_id_list_cnt=0;
                                        }else{
                                            $arr=explode(',',$suspicious->reporter_user_id_list);
                                            foreach ($arr as $key => $value){
                                                if(empty($value)){
                                                    unset($arr[$key]);
                                                }
                                            }
                                            $reporter_user_id_list_cnt=count($arr);
                                        }
                                    @endphp
                                    <h2>銀行帳號<span>{{ $str_star.str_replace($str_c, '', $suspicious->account_text) }}</span></h2>
                                    @if($reporter_user_id_list_cnt)
                                        <h2>被提報次數<span>{{ $reporter_user_id_list_cnt }}</span></h2>
                                    @endif
                                    @if($suspicious->reporter_user_id==auth()->user()->id)
                                        <div class="ap_butnew">
                                            <a onclick="postDelete({{ $suspicious->id }})" class="sc_cc"><img src="/new/images/del_03n.png">刪除</a>
                                            <a href="/dashboard/view_suspicious_edit/{{ $suspicious->id }}" class="sc_cc"><img src="/new/images/xiugai.png">修改</a>
                                        </div>
                                    @endif
                                </div>
                                <div class="zap_bb">
                                    <h2>舉報原因</h2>
                                    <div class="text">
                                        <span style="word-break: break-all;">{{ $suspicious->reason }}</span>
                                        <a>…<em>+全文</em></a>
                                    </div>
                                </div>
                                <div class="zap_bb zapbot">
                                    <h2>照片上傳</h2>
                                    <ul class="zap_photo">
                                        @if($suspicious->images)
                                            @foreach(json_decode($suspicious->images, true) as $key => $image)
                                                @if($key<3 ||($key==3 && count(json_decode($suspicious->images, true))==4))
                                                    <li><img src="{{ $image }}"></li>
                                                @elseif($key==3)
                                                    <li><img src="{{ $image }}"> <em>+{{ count(json_decode($suspicious->images, true))-4 }}</em></li>
                                                @elseif($loop->iteration >=4)
                                                    <li style="display: none;"><img src="{{ $image }}"></li>
                                                @endif
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                                <div class="ky_sy">
                                    @if(auth()->user()->id!==$suspicious->reporter_user_id  && !in_array(auth()->user()->id, explode(',',$suspicious->reporter_user_id_list)))
                                        <div class="kyzh_b" onclick="zh({{ $suspicious->id }})"><img src="/new/images/qz.png">我也被這個銀行帳號騙過</div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        @else
                            <img src="/new/images/wuziliao.png" class="ziliao_wimg">
                            <div class="zlfoxinx">查無資料</div>
                        @endif
                    </div>
                    <!-- end -->
                </div>
                <div id="suspicious_type1_pagelist" class="fenye matop5 mabot_30">
                    {!! $suspicious_type1->appends(array_merge(request()->input(), ['lists_type1' => $suspicious_type1->currentPage()]))->links('pagination::sg-pages2') !!}
                </div>
                <div id="suspicious_type2_pagelist" class="fenye matop5 mabot_30" style="display: none;">
                    {!! $suspicious_type2->appends(array_merge(request()->input(), ['lists_type2' => $suspicious_type2->currentPage()]))->links('pagination::sg-pages2') !!}

                </div>
            </div>
        </div>
    </div>
</div>
<a href="/dashboard/suspicious_posts">
    <div class="zhp_xz"><img src="/new/images/zhapian_icon3.png"></div>
</a>
@stop
<style>
    .blur_img {
        filter: blur(1px);
        -webkit-filter: blur(1px);
    }
    .g_pnr {
        min-height: 780px;
    }
    @media (max-width: 450px) {
        .g_pnr {
            min-height: 600px;
        }
    }
    @media (max-width: 768px) {
        .g_pnr {
            min-height: 780px;
        }
    }
    
    @media (max-width: 1024px) {
        .g_pnr {
            min-height: 1120px;
        }
    }
    .zhapian_zl {
        width: 700px;
        height: 40px;
        line-height: 40px;
        background: #f8f8f8;
        border-radius: 5px;
        margin: 0 auto;
        display: table;
    }
    @media (max-width: 824px) {
        .zhapian_zl {
            width: 94%;
            margin: 0 auto;
        }
    }
    .zhp_list {
        width: 700px;
        margin: 0 auto;
        display: table;
        border: #ffbfcd 1px solid;
        border-radius: 10px;
        margin-top: 20px;
        padding: 10px;
    }
    @media (max-width: 824px) {
        .zhp_list {
            width: 94%;
        }
    }
    @media (max-width: 320px) {
        .zq_font1 {
            font-size: 18px !important;
        }
    }
    @media (max-width: 320px) {
        .zq_font2 {
            font-size: 13px !important;
        }
    }
    .zhap_input {
        width: 610px;
        float: left;
        border: none;
        background: none;
        padding-left: 15px;
        outline: none;
    }
    @media (max-width: 824px) {
        .zhap_input {
            width: calc(100% - 80px);
        }
    }
    .zhap_search {
        width: 80px;
        background: #fe92a8;
        float: right;
        color: #fff;
        border-radius: 5px;
        font-size: 15px;
        text-align: center;
        cursor: pointer;
        height: 41px;
    }
    @media (max-width: 824px) {
        .zhap_search {
            width: 80px;
        }
    }
    .zhap_search img {
        height: 24px;
    }
    .zhp_ptitle {
        width: 100%;
        background: #fcedf1;
        height: 40px;
        line-height: 40px;
        font-size: 16px;
        font-weight: bold;
        color: #e55073;
        border-radius: 5px;
    }
    .zhp_ptitle img {
        height: 24px;
        vertical-align: middle;
        margin-left: 10px;
        margin-right: 6px;
    }
    .zhp_ullist {
        width: 100%;
        margin: 0 auto;
        display: table;
        margin-top: 15px;
    }
    .zhp_ullist li {
        background: #ffffff;
        border-radius: 5px;
        box-shadow: #fcedf1 0 0 6px;
        padding: 10px 0;
        margin-bottom: 10px;
    }
    .zhp_ullist li h2 {
        width: 95%;
        margin: 0 auto;
        display: table;
        color: #666666;
        border-bottom: #eee 1px dashed;
        padding-bottom: 8px;
    }
    .zhp_ullist li h2 span {
        float: right;
    }
    .zhp_ullist li h3 {
        width: 95%;
        margin: 0 auto;
        display: table;
        color: #e55073;
        padding-top: 8px;
    }
    .ziliao_wimg {
        width: 160px;
        margin: 0 auto;
        display: table;
        padding-top: 20px;
    }
    .zlfoxinx {
        text-align: center;
        color: #999;
        padding-bottom: 30px;
        padding-top: 10px;
    }

    .zhp_xz {
        width: 60px;
        height: 60px;
        background: #000;
        position: fixed;
        bottom: 30px;
        right: 20px;
        display: table;
        border-radius: 100px;
        box-shadow: 0 0 15px #fa879f;
        background-image: linear-gradient(to right, #e8597a 0%, #fa879f 100%);
        cursor: pointer;
    }
    .zhp_xz:hover {
        background-image: linear-gradient(to right, #fa879f 0%, #e8597a 100%);
    }
    .zhp_xz img {
        width: 30px;
        margin: 0 auto;
        display: table;
        vertical-align: middle;
        margin-top: 15px;
    }
</style>
@section('javascript')
<script>
    $('.kyzh_b').click(function(){
        $(this).addClass('kyzh_b_h');
        var that=$(this);
        setTimeout(function(){that.removeClass('kyzh_b_h');}, 200);
    })
    function zh(suspicious_id) {
        $('#suspicious_count').show();
        $('#suspicious_count').attr('href', '/dashboard/suspicious_count/'+ suspicious_id);
        $('#suspicious_create').attr('href', '/dashboard/suspicious_posts?suspicious_id='+ suspicious_id);
        $(".blbg").show()
        $("#tbtk").show()
    }
    function gmBtn1() {
        $(".xz").hide()
        $(".blbg").hide()
    }
    $('.blbg').click(function(){
        $("#tbtk").hide();
    })

</script>

<div class="bl_tab xz" id="tbtk">
        <div class="bltitle"><span>提示</span></div>
    <div class="n_heighnn">
        <div class="n_gd"><div class="n_gd_t"></div></div>
        <div class="yidy_tk" style="text-align: center; color: #8a9fef; font-size: 16px; font-weight: bold; margin-top: 10px;">
            請確認是否為同一位女會員？
        </div>
        <div class="n_bbutton">
            <span><a id="suspicious_count" class="n_left tk_ccs tk_ll5" href="/dashboard/suspicious_count">是，受騙+1</a></span>
            <span><a id="suspicious_create" class="n_right tk_ccs tk_rl5" href="/dashboard/suspicious_posts">否，前往提報</a></span>
        </div>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>

<script>
    function xz() {
        $(".blbg").show()
        $("#sm").show()
    }

    function bt11() {
        $(".xz").hide()
        $("#blbg").hide()
    }
</script>


<script>
    function tianjia() {
        $(".blbg").show()
        $("#tab01").show()
    }

    function xinzeng() {
        $(".xz").hide()
        $("#tab02").show()
    }

    function gmBtn1() {
        $(".blbg").hide()
        $(".bl_tab").hide()
    }
</script>
<script>
    $(".text span").each(function(){
        var  h = $(this).innerHeight();
        if( h > 46){
            $(this).addClass('origin_hide_text');
            $(this).addClass('on');
            $(this).css('height','39px');
        }else{
            $(this).next('a').hide();
        }
    })
    $(function (){
        @if($suspicious_type2->count()>0 && $suspicious_type1->count()==0)
            $(".zhap_new a:last").addClass("zhap_new_hover");
            $(".zap_ullist").hide();
            $(".zap_ullist:last").show();
        @else
            $(".zhap_new a:first").addClass("zhap_new_hover");
            $(".zap_ullist").hide();
            $(".zap_ullist:first").show();
        @endif
        $(".zhap_new a").click(function () {
            $('.zhap_new a:not(this)').removeClass("zhap_new_hover");
            $(this).addClass("zhap_new_hover");
            $('.zap_ullist').hide();
            var i=$(this).index();
            $('.zap_ullist').eq(i).show();
            $('.zap_ullist').eq(i).find(".text span").each(function(){
                var  h = $(this).innerHeight();
                if( h > 39){
                    $(this).addClass('on');
                    $(this).next('a').show();
                }else{
                    // $(this).next('a').remove();
                }
            })

            //內文收合
            $(".text span").each(function(){
                var  h = $(this).innerHeight();
                if( h > 46){
                    $(this).addClass('on');
                    $(this).css('height','39px');
                }else{
                    if($(this).hasClass('origin_hide_text')==false){
                        $(this).next('a').hide();
                    }
                }
            })
        });
        $(".text>a").click(function(){
            var btn = $(this).prev();
            if(btn.hasClass('on')){
                btn.removeClass("on");
                $(this).html("<em>-收起</em>");
            }else{
                btn.addClass("on");
                $(this).html("…<em>+全文</em>");
            }

        });

        $("#suspicious_tab1").click(function(){
            $('#suspicious_type1_pagelist').show();
            $('#suspicious_type2_pagelist').hide();
        });
        $("#suspicious_tab2").click(function(){
            $('#suspicious_type1_pagelist').hide();
            $('#suspicious_type2_pagelist').show();
        });
    })
</script>
<!--照片查看-->
<div class="big_img">
    <!-- 自定义分页器 -->
    <div class="swiper-num">
        <span class="active"></span>/
        <span class="total"></span>
    </div>
    <div class="swiper-container2">
        <div class="swiper-wrapper">
        </div>
    </div>
    <div class="swiper-pagination2"></div>
</div>
<link type="text/css" rel="stylesheet" href="/new/css/app.css">
<link rel="stylesheet" type="text/css" href="/new/css/swiper.min.css" />
<script type="text/javascript" src="/new/js/swiper.min.js"></script>
<script>
    $(document).ready(function () {
        /*调起大图 S*/
        var mySwiper = new Swiper('.swiper-container2',{
            pagination : '.swiper-pagination2',
            paginationClickable:true,
            onInit: function(swiper){//Swiper初始化了
                // var total = swiper.bullets.length;
                var active =swiper.activeIndex;
                $(".swiper-num .active").text(active);
                // $(".swiper-num .total").text(total);
            },
            onSlideChangeEnd: function(swiper){
                var active =swiper.realIndex +1;
                $(".swiper-num .active").text(active);
            }
        });

        $(".zap_photo li").on("click",
            function () {
                var imgBox = $(this).parent(".zap_photo").find("li");
                var i = $(imgBox).index(this);
                $(".big_img .swiper-wrapper").html("")

                for (var j = 0, c = imgBox.length; j < c ; j++) {
                    $(".big_img .swiper-wrapper").append('<div class="swiper-slide"><div class="cell"><img src="' + imgBox.eq(j).find("img").attr("src") + '" / ></div></div>');
                }
                mySwiper.updateSlidesSize();
                mySwiper.updatePagination();
                $(".big_img").css({
                    "z-index": 1001,
                    "opacity": "1"
                });
                //分页器
                var num = $(".swiper-pagination2 span").length;
                $(".swiper-num .total").text(num);
                // var active =$(".swiper-pagination2").index(".swiper-pagination-bullet-active");
                $(".swiper-num .active").text(i + 1);
                // console.log(active)

                mySwiper.slideTo(i, 0, false);
                return false;
            });
        $(".swiper-container2").click(function(){
            $(this).parent(".big_img").css({
                "z-index": "-1",
                "opacity": "0"
            });
        });

    });
    /*调起大图 E*/
</script>
<!--照片查看end-->

<script>
    $(document).ready(function(){
        let urlParams = new URLSearchParams(window.location.search);
        if(!urlParams.has('s')) {
            popSus('');
        }
    });

    $('.zhap_search').on('click', function() {
        window.location.href = '?q=' + $('.zhap_input').val() + '&s=false'
    });

    function postDelete(pid) {
        c4('確定要刪除嗎?');
        $(".n_left").on('click', function() {
            $.ajax({
                url: '/dashboard/suspicious_delete/'+ pid +'?{{ csrf_token() }}={{now()->timestamp}} ',
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    'user_id': "{{ auth()->user()->id }}"
                },
                dataType: 'json',
                success: function(data) {
                    c5(data.msg);
                    window.location.href=data.redirectTo;
                }
            });
        });
    }

</script>
@stop