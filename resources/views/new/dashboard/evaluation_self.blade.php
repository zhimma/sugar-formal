@extends('new.layouts.website')

@section('app-content')
    <style>
        .hf_i {
            min-height: 35px;
            line-height:26px;
            transition: width 0.25s;
            resize:none;
            overflow:hidden;
        }
        .re_area{
            position: relative;
            float: right;
        }
        .show_more{
            display: block;
        }
        .hide_more{
            display: none;
        }
        .zap_photo{width: 100%; overflow: hidden; padding-left: 10px;}
        .zap_photo>li{float: left; width:18%; height:102px;background: #fff9fa;justify-content: center;align-items: center;overflow: hidden;display: flex;
            border: #fe92a8 1px dashed; position: relative; margin: 10px 2% 0 0;cursor: pointer;}
        .zap_photo>li>img{max-width: 100%; max-height: 100%;}
        .zap_photo>li>em{position: absolute; left: 0; top:0; width: 100%; height: 100%; background: rgba(0,0,0,.5); color: #fff; display: flex; align-items: center; justify-content: center;}
        .pjliuyan02 .zap_photo>li{padding: 0;display: flex;width:19%; margin: 10px 1% 0 0; height:140px;background: #f5f5f5; border: none; overflow: hidden;}
        .pjliuyan02 .zap_photo{margin: 0; display: block;}
        .pjliuyan02 .zap_photo>li>img{width: 100%; max-height:unset;}

        @media (max-width:768px){
            .zap_bb>.text>a>em{padding-left:11px;}
        }
        @media (max-width:450px){
            .zap_bb>.text>a>em{padding-left:7px;}
            .zap_photo>li{width:23%; height: 90px;}
            .zap_photo{padding-left: 0;}
            .pjliuyan02 .zap_photo>li{width:30.33%;margin:12px 1.5% 0 1.5%;height: 100px;}
        }
    </style>
<div class="container matop70">
    <div class="row">
        <div class="col-sm-2 col-xs-2 col-md-2 dinone">
            @include('new.dashboard.panel')
        </div>
        <div class="col-sm-12 col-xs-12 col-md-10">
            <div class="shou"><span>評價</span>
                <font>Evaluation</font>
                @if(count($evaluation_data)>0)
                <a href="" class="shou_but">全部刪除</a>
                @endif
            </div>

            @if(count($evaluation_data)>0)
            <div class="pjliuyan02">
                <ul>
                    @php
                        $showCount = 0;
                        $blockMidList = array();
                    @endphp
                    @foreach( $evaluation_data as $row)
                        @php
                            $row_user = \App\Models\User::findById($row->from_id);
                            $to_user = \App\Models\User::findById($row->to_id);
                            $isBlocked = \App\Models\Blocked::isBlocked($row->to_id, $row->from_id);
                            $hadWarned = DB::table('is_warned_log')->where('user_id',$to_user->id)->first();
                            $warned_users = DB::table('warned_users')->where('member_id',$to_user->id)
                                ->where(function($warned_users){
                                $warned_users->where('expire_date', '>=', \Carbon\Carbon::now())
                                    ->orWhere('expire_date', null); })->first();
                            if($isBlocked || isset($hadWarned) || isset($warned_users)) {
                                array_push( $blockMidList, $row );

                            }
                            $showCount++;
                        @endphp
                        @if(!$isBlocked && !isset($hadWarned) && !isset($warned_users))
                        <li>
                            <div class="piname">
                                {{--<span>
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if($row->rating>=$i)
                                            <img src="/new/images/sxx_1.png">
                                        @else
                                            <img src="/new/images/sxx_4.png">
                                        @endif
                                    @endfor
                                </span>--}}
                                <a href="/dashboard/viewuser/{{$to_user->id}}?time={{ \Carbon\Carbon::now()->timestamp }}">{{$to_user->name}}</a>
                                @if($to_user->id==$user->id)
                                    <font class="sc content_delete" data-id="{{$row->id}}"><img src="/new/images/del_03.png">刪除</font>
                                @endif
                            </div>
                            <div class="con">
                                @if($row->is_check==1)
                                    <p class="many-txt" style="color: red;">***此評價目前由站方審核中***</p>
                                @else
                                    <p class="many-txt">{!! nl2br($row->content) !!}@if(!is_null($row->admin_comment))<span style="color: red;">{{ ' ('.$row->admin_comment.')' }}</span> @endif</p>
                                @endif
                                @php
                                    $evaluationPics=\App\Models\EvaluationPic::where('evaluation_id',$row->id)->where('member_id',$row->from_id)->get();
                                @endphp
                                <ul class="zap_photo {{ $evaluationPics->count()>3 ? 'huiyoic':'' }}">
                                    @foreach($evaluationPics as $evaluationPic)
                                        <li><img src="{{ $evaluationPic->pic }}"></li>
                                    @endforeach
                                </ul>
                                <h4>
                                    <span class="btime">{{ substr($row->created_at,0,10)}}</span>
                                    <button type="button" class="al_but">完整評價</button>
                                </h4>
                            </div>

                            @if(empty($row->re_content) && $to_user->id == $user->id)
                                <div class="huf">
                                    <form id="form_re_content" action="{{ route('evaluation_re_content')."?n=".time() }}" method="post">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <span class="huinput">
                                                                <textarea name="re_content" type="text" class="hf_i" placeholder="請輸入回覆（最多120個字元）" maxlength="120"></textarea>
                                                            </span>
                                        <div class="re_area">
                                            <a class="hf_but" onclick="form_re_content_submit()">回覆</a>
                                        </div>
                                        <input type="hidden" name="id" value={{$row->id}}>
                                        <input type="hidden" name="eid" value={{$row->to_id}}>
                                    </form>
                                </div>
                            @elseif(!empty($row->re_content))
                                <div class="hu_p">
                                    <div class="he_b">
                                        <span class="left"><img src="@if(file_exists( public_path().$to_user->meta_()->pic ) && $to_user->meta_()->pic != ""){{$to_user->meta_()->pic}} @elseif($to_user->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="he_zp">{{$to_user->name}}</span>
                                        @if($row_user->id==$user->id)
                                            <font class="sc re_content_delete" data-id="{{$row->id}}"><img src="/new/images/del_03.png">刪除</font>
                                        @endif
                                    </div>
                                    <div class="he_two">
                                        <div class="context">
                                            @if($row->is_check==1)
                                                <div id="test" class="context-wrap" style="word-break: break-all;color: red;">***此評價目前由站方審核中***</div>
                                            @else
                                                <div id="test" class="context-wrap" style="word-break: break-all;">{!! nl2br($row->re_content) !!}</div>
                                            @endif
                                            @php
                                                $evaluationPics=\App\Models\EvaluationPic::where('evaluation_id',$row->id)->where('member_id',$row->to_id)->get();
                                            @endphp
                                            <ul class="zap_photo {{ $evaluationPics->count()>3 ? 'huiyoic':'' }}">
                                                @foreach($evaluationPics as $evaluationPic)
                                                    <li><img src="{{ $evaluationPic->pic }}"></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="he_twotime">{{ substr($row->re_created_at,0,10)}}<span class="z_more">展開</span></div>
                                </div>
                            @endif

                        </li>
                        @endif
                    @endforeach
                    @if(sizeof($blockMidList) > 0)
                        <div id="plshow">
                            @foreach($blockMidList as $row)
                                @php
                                    $row_user = \App\Models\User::findById($row->from_id);
                                    $to_user = \App\Models\User::findById($row->to_id);
                                    $isBlocked = \App\Models\Blocked::isBlocked($row->to_id, $row->from_id);
                                    $hadWarned = DB::table('is_warned_log')->where('user_id',$row->to_id)->first();
                                    $warned_users = DB::table('warned_users')->where('member_id',$row->to_id)
                                        ->where(function($warned_users){
                                        $warned_users->where('expire_date', '>=', \Carbon\Carbon::now())
                                            ->orWhere('expire_date', null); })->first();
                                    $showCount++;
                                @endphp
                            @if(!$isBlocked)
                                <li>
                                    <div class="kll">
                                        <div class="piname">
                                                    {{--span>
                                                        @if(!$warned_users && !$hadWarned)
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                @if($row->rating>=$i)
                                                                    <img src="/new/images/sxx_1.png">
                                                                @else
                                                                    <img src="/new/images/sxx_4.png">
                                                                @endif
                                                            @endfor
                                                        @endif
                                                    </span>--}}
                                            <a href="/dashboard/viewuser/{{$row->to_id}}?time={{ \Carbon\Carbon::now()->timestamp }}">{{$to_user->name}}</a>
                                            @if(isset($warned_users) || isset($hadWarned))
                                                <img src="/new/images/kul.png" class="sxyh">
                                            @else
                                                <img src="/new/images/kul02.png" class="sxyh">
                                            @endif
                                            @if($to_user->id==$user->id)
                                                <font class="sc content_delete" data-id="{{$row->id}}"><img src="/new/images/del_03.png">刪除</font>
                                            @endif
                                        </div>
                                        <div class="con">
                                            @if($row->is_check==1)
                                                <p class="many-txt" style="color: red;">***此評價目前由站方審核中***</p>
                                            @else
                                                <p class="many-txt">{!! nl2br($row->content) !!}@if(!is_null($row->admin_comment))<span style="color: red;">{{ ' ('.$row->admin_comment.')' }}</span> @endif</p>
                                            @endif
                                            @php
                                                $evaluationPics=\App\Models\EvaluationPic::where('evaluation_id',$row->id)->where('member_id',$row->from_id)->get();
                                            @endphp
                                            <ul class="zap_photo {{ $evaluationPics->count()>3 ? 'huiyoic':'' }}">
                                                @foreach($evaluationPics as $evaluationPic)
                                                    <li><img src="{{ $evaluationPic->pic }}"></li>
                                                @endforeach
                                            </ul>
                                            <h4>
                                                <span class="btime">{{ substr($row->created_at,0,10)}}</span>
                                                <button type="button" class="al_but">完整評價</button>
                                            </h4>
                                        </div>
                                    </div>

                                    @if(!empty($row->re_content))
                                        <div class="hu_p">
                                            <div class="he_b">
                                                <span class="left"><img src="@if(file_exists( public_path().$row_user->meta_()->pic ) && $row_user->meta_()->pic != ""){{$row_user->meta_()->pic}} @elseif($row_user->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="he_zp">{{$row_user->name}}</span>
                                                @if($row_user->id==$user->id)
                                                    <font class="sc re_content_delete" data-id="{{$row->id}}"><img src="/new/images/del_03.png">刪除</font>
                                                @endif
                                            </div>
                                            <div class="he_two">
                                                <div class="context">
                                                    @if($row->is_check==1)
                                                        <div id="test" class="context-wrap" style="word-break: break-all;color: red;">***此評價目前由站方審核中***</div>
                                                    @else
                                                        <div id="test" class="context-wrap" style="word-break: break-all;">{!! nl2br($row->re_content) !!}</div>
                                                    @endif
                                                    @php
                                                        $evaluationPics=\App\Models\EvaluationPic::where('evaluation_id',$row->id)->where('member_id',$row->from_id)->get();
                                                    @endphp
                                                    <ul class="zap_photo {{ $evaluationPics->count()>3 ? 'huiyoic':'' }}">
                                                        @foreach($evaluationPics as $evaluationPic)
                                                            <li><img src="{{ $evaluationPic->pic }}"></li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="he_twotime">{{ substr($row->re_created_at,0,10)}}<span class="z_more">展開</span></div>
                                        </div>
                                    @endif
                                </li>
                                @endif
                            @endforeach
                        </div>
                    @endif

                </ul>
                <div style="text-align: center;">
                    {!! $evaluation_data->appends(request()->input())->links('pagination::sg-pages2') !!}
                </div>
            </div>
            @else
            <div class="sjlist">
                <div class="fengsicon"><img src="/new/images/pjicon.png" class="feng_img"><span>暫無資料</span></div>
            </div>
            @endif

        </div>
    </div>
</div>

@stop

@section('javascript')
<script>

    $('.shou_but').on('click', function() {
        c4('確定要全部刪除嗎?');
        $(".n_left").on('click', function() {
            $.post('{{ route('evaDeleteAll') }}', {
                from_id: '{{ $user->id }}',
                _token: '{{ csrf_token() }}'
            }, function (data) {
                $("#tab04").hide();
                show_pop_message('已刪除');
            });
        });
        return false;
    });

    $('.content_delete').on( "click", function() {
        c4('確定要刪除嗎?');
        var id = $(this).data('id');
        $(".n_left").on('click', function() {
            $.post('{{ route('evaluation_delete') }}', {
                id: id,
                _token: '{{ csrf_token() }}'
            }, function (data) {
                $("#tab04").hide();
                show_pop_message('評價已刪除');
            });
        });
    });

    function form_re_content_submit(){
        if($.trim($(".hf_i").val())=='') {
            c5('請輸入內容');
        }else{
            $('#form_re_content').submit();
        }
    }

    $('.re_content_delete').on( "click", function() {
        c4('確定要刪除嗎?');
        $(".n_left").on('click', function() {
            $.post('{{ route('evaluation_re_content_delete') }}', {
                id: $('.re_content_delete').data('id'),
                _token: '{{ csrf_token() }}'
            }, function (data) {
                $("#tab04").hide();
                show_pop_message('回覆已刪除');
            });
        });
    });

    let button = document.getElementsByTagName('button');
    let p = document.getElementsByTagName('p');

    for(let i=0; i<button.length; i++) {
        button[i].onclick = function () {
            if(this.innerHTML == "完整評價"){
                p[i].classList.remove("many-txt");
                p[i].classList.add("all-txt");
                this.innerHTML = "點擊收起";
            }
            else{
                p[i].classList.remove("all-txt");
                p[i].classList.add("many-txt");
                this.innerHTML = "完整評價";
            }
        }
    }

    $(".z_more").on( "click", function() {
        $(this).parent().prev().find('.context').find("div").first().toggleClass('on context-wrap')
        $(this).html($(this).text() === '展開' ? '收起' : '展開');
    });

    $('textarea.hf_i').on({input: function(){
            var totalHeight = $(this).prop('scrollHeight') - parseInt($(this).css('padding-top')) - parseInt($(this).css('padding-bottom'));
            $(this).css({'height':totalHeight});
            if(totalHeight>40) {
                $('.re_area').css({'top': totalHeight - 40});
            }
        }
    });

    $('div.context-wrap').each(function(i) {
        if (isEllipsisActive(this)) {
            $(this).parents('.hu_p').find('span.z_more').removeClass('hide_more');
            $(this).parents('.hu_p').find('span.z_more').removeClass('show_more');
            $(this).parents('.hu_p').find('span.z_more').addClass('show_more');
        }
        else {
            $(this).parents('.hu_p').find('span.z_more').removeClass('show_more');
            $(this).parents('.hu_p').find('span.z_more').removeClass('hide_more');
            $(this).parents('.hu_p').find('span.z_more').addClass('hide_more');
        }
    });

    $(window).resize(function() {
        $('div.context-wrap').each(function(i) {
            if (isEllipsisActive(this)) {
                $(this).parents('.hu_p').find('span.z_more').removeClass('hide_more');
                $(this).parents('.hu_p').find('span.z_more').removeClass('show_more');

                $(this).parents('.hu_p').find('span.z_more').addClass('show_more');
            }
            else {
                $(this).parents('.hu_p').find('span.z_more').removeClass('show_more');
                $(this).parents('.hu_p').find('span.z_more').removeClass('hide_more');

                $(this).parents('.hu_p').find('span.z_more').addClass('hide_more');
            }
        });
    });

    $('.many-txt').each(function(i) {
        if (isEllipsisActive(this)) {
            $(this).parents('.con').find('.al_but').removeClass('hide_more');
            $(this).parents('.con').find('.al_but').removeClass('show_more');

            $(this).parents('.con').find('.al_but').addClass('show_more');
        }
        else {
            $(this).parents('.con').find('.al_but').removeClass('hide_more');
            $(this).parents('.con').find('.al_but').removeClass('show_more');

            $(this).parents('.con').find('.al_but').addClass('hide_more');
        }
    });

    $(window).resize(function() {
        $('.many-txt').each(function(i) {
            if (isEllipsisActive(this)) {
                $(this).parents('.con').find('.al_but').removeClass('hide_more');
                $(this).parents('.con').find('.al_but').removeClass('show_more');

                $(this).parents('.con').find('.al_but').addClass('show_more');
            }
            else {
                $(this).parents('.con').find('.al_but').removeClass('hide_more');
                $(this).parents('.con').find('.al_but').removeClass('show_more');

                $(this).parents('.con').find('.al_but').addClass('hide_more');
            }
        });
    });

    function isEllipsisActive(e) {
        return ($(e).innerHeight() < $(e)[0].scrollHeight);
    }
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
<link rel="stylesheet" type="text/css" href="/new/css/swiper2.min.css"/>
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
@stop