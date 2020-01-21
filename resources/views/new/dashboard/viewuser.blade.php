@extends('new.layouts.website')

@section('app-content')
    <style>
        .swiper-container {
            width: 100%;
            height: auto;
        }

        .swiper-slide {
            width: 100%;
            height: 280px;
            margin: 0 auto;
            padding: 0px;
            display: table
        }

        .swiper-slide img {
            width: 100%;
            height: 100%;
        }

        @media (max-width:767px) {
            .swiper-container {
                width: 100%;
                height: auto;
            }
            .swiper-slide {
                width: 100%;
                height: 200px !important;
                margin: 0 auto;
                padding: 0px;
                display: table
            }
            .swiper-slide img {
                width: 100%;
                height: 100%;
            }
        }
        @media (max-width:992px) {
            .swiper-container {
                width: 100%;
                height: auto;
            }
            .swiper-slide {
                width: 100%;
                height: 280px;
                margin: 0 auto;
                padding: 0px;
                display: table
            }
            .swiper-slide img {
                width: 100%;
                height: 100%;
            }
        }


    </style>
    <div class="container matop80">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="rightbg">
                    <div class="metx">
                        <div class="swiper-container photo">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide"><img src="{{$to->meta_()->pic}}"></div>
{{--                                <div class="swiper-slide"><img src="/new/images/icon_04.png"></div>--}}
{{--                                <div class="swiper-slide"><img src="/new/images/icon_03.png"></div>--}}
                            </div>
                            <!-- Add Arrows -->
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                        <div class="n_jianj"><a href="">檢舉大頭照</a></div>
                        <div class="tubiao">
                            <ul>
                                <li>
                                    <a onclick="show_chat()"><img src="/new/images/icon_06.png" class="tubiao_i"><span>發信</span></a>
                                </li>
                                <li>
                                    <a class="addFav"><img src="/new/images/icon_08.png" class="tubiao_i"><span>收藏</span></a>
                                </li>
                                <li>
                                    <a onclick="show_banned()"><img src="/new/images/icon_10.png" class="tubiao_i"><span>檢舉</span></a>
                                </li>
                                <li>
                                    <a onclick="show_block()"><img src="/new/images/icon_12.png" class="tubiao_i"><span>封鎖</span></a>
                                </li>
                            </ul>
                        </div>
                        <div class="bottub">
                            <ul>
                                <?php
                                $now = \Carbon\Carbon::now();
                                $registration_date = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $to->created_at);
                                $diff_in_months = $registration_date->diffInMonths($now);
                                ?>
                                @if($diff_in_months==0)
                                <li><img src="/new/images/icon_19.png"><span>新進甜心</span></li>
                                @endif
                                <li><img src="/new/images/icon_21.png"><span>優選會員</span></li>
                                <li><img src="/new/images/icon_23.png"><span>財力認證</span></li>
                                @if($to->isVip())
                                <li><img src="/new/images/icon_25.png"><span>VIP</span></li>
                                @endif
{{--                                <li><img src="/new/images/icon_27.png"><span>警示帳戶</span></li>--}}
                            </ul>
                        </div>

                    </div>
                    <!-- Swiper JS -->
                    <script src="/new/js/swiper.min.js"></script>
                    <!-- Initialize Swiper -->
                    <script>
                        var swiper = new Swiper('.swiper-container', {
                            pagination: '.swiper-pagination',
                            nextButton: '.swiper-button-next',
                            prevButton: '.swiper-button-prev',
                            slidesPerView: 1,
                            paginationClickable: true,
                            spaceBetween: 30,
                            loop: true
                        });
                    </script>
                </div>
                <!--基本资料-->
                <div class="mintop">
                    <div class="">
                        <div class="ziliao">
                            <div class="ztitle"><span>基本資料</span>Basic information</div>
                            <div class="xiliao_input">
                                <div class="xl_input">
                                    <dt>
                                        <span>暱稱</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->name}}" disabled="disabled"></span>
                                    </dt>
                                    <dt>
                                        <span>一句話形容自己</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->title}}" disabled="disabled"></span>
                                    </dt>
                                    <dt>
                                        <span>地區</span>
{{--                                        <span>--}}
{{--                                            <input name="" type="text" class="select_xx senhs"  placeholder="{{$to->meta_()->city}}" disabled="disabled">--}}
{{--                                            <input name="" type="text" class="select_xx senhs right"  placeholder="{{$to->meta_()->area}}" disabled="disabled">--}}
{{--                                        </span>--}}
                                        <?php
                                        if (!isset($user)) {
                                            $umeta = null;
                                        } else {
                                            $umeta = $user->meta_();
                                            if(isset($umeta->city)){
                                                $umeta->city = explode(",",$umeta->city);
                                                $umeta->area = explode(",",$umeta->area);
                                            }
                                        }
                                        ?>
                                        @if(isset($umeta->city))
                                            @if(is_array($umeta->city))
                                                @foreach($umeta->city as $key => $cityval)
                                                    <span style="margin-top: 2px;">
                                                        <input name="" type="text" class="select_xx senhs"  placeholder="{{$umeta->city[$key]}}" disabled="disabled">
                                                        <input name="" type="text" class="select_xx senhs right"  placeholder="{{$umeta->area[$key]}}" disabled="disabled">
                                                    </span>
                                                @endforeach
                                            @endif
                                        @else
                                            <span>
                                                <input name="" type="text" class="select_xx senhs"  placeholder="{{$to->meta_()->city}}" disabled="disabled">
                                                <input name="" type="text" class="select_xx senhs right"  placeholder="{{$to->meta_()->area}}" disabled="disabled">
                                            </span>
                                        @endif
                                    </dt>

                                    <dt>
                                        <span>預算</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->budget}}" disabled="disabled"></span>
                                    </dt>
                                    <dt>
                                        <span>年齡</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->age()}}" disabled="disabled"></span>
                                    </dt>
                                    <dt>
                                        <span>身高（cm）</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->height}}" disabled="disabled"></span>
                                    </dt>
                                    <dt>
                                        <span>體型</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->body}}" disabled="disabled"></span>
                                    </dt>
                                    <dt>
                                        <span>CUP</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->cup}}" disabled="disabled"></span>
                                    </dt>
                                    <dt>
                                        <span>關於我</span>
                                        <span><div class="select_xx03" >{{$to->meta_()->about}}</div></span>
                                    </dt>
                                    <dt>
                                        <span>期待的約會模式</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->style}}" disabled="disabled"></span>
                                    </dt>
                                    <dt>
                                        <span>產業</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->domainType}}" disabled="disabled"></span>
                                    </dt>
                                    <dt>
                                        <span>職業</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->occupation}}" disabled="disabled"></span>
                                    </dt>
                                    <dt>
                                        <span>教育</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->education}}" disabled="disabled"></span>
                                    </dt>
                                    <dt>
                                        <span>婚姻</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->marriage}}" disabled="disabled"></span>
                                    </dt>
                                    <dt>
                                        <span>喝酒</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->drinking}}" disabled="disabled"></span>
                                    </dt>
                                    <dt>
                                        <span>抽煙</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->smoking}}" disabled="disabled"></span>
                                    </dt>
                                    <dt>
                                        <span>收入</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->income}}" disabled="disabled"></span>
                                    </dt>
                                    <dt>
                                        <span>資產</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->assets}}" disabled="disabled"></span>
                                    </dt>
                                </div>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="ziliao">
                            <div class="ztitle"><span>進階資料</span>Advanced materials</div>
                            <div class="xiliao_input">
                                <div class="xl_text">
                                    <dt><span>帳號建立時間</span><font>{{$to->created_at}}</font></dt>
                                    <dt><span>登入時間</span><font>{{$to->last_login}}</font></dt>
                                    <dt><span>被收藏次數</span>@if($to->isVip()) <font>{{$be_fav_count}}</font> @else <img src="/new/images/icon_35.png"> @endif</dt>
                                    <dt><span>收藏會員次數</span><font>{{$fav_count}}</font></dt>
                                    <dt><span>車馬費邀請次數</span><font>{{$tip_count}}</font></dt>
                                    <dt><span>發信次數</span><font>{{$message_count}}</font></dt>
                                    <dt><span>過去7天發信次數</span><font>{{$message_count_7}}</font></dt>
                                    <dt><span>是否封鎖我</span><font>{{$is_block_mid}}</font></dt>
                                    <dt><span>是否看過我</span><font>{{$is_visit_mid}}</font></dt>
                                    <dt><span>瀏覽其他會員次數</span><font>{{$visit_other_count}}</font></dt>
                                    <dt><span>被瀏覽次數</span><font>{{$be_visit_other_count}}</font></dt>
                                    <dt><span>過去7天被瀏覽次數</span><font>{{$be_visit_other_count_7}}</font></dt>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--基本资料-->
            </div>

        </div>
    </div>


    <div class="bl bl_tab" id="show_chat">
        <div class="bltitle"><span>發送給{{$to->name}}</span></div>
        <div class="n_blnr01 ">

            <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="/dashboard/chat2" id="chatForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                <input type="hidden" name="userId" value="{{$user->id}}">
                <input type="hidden" name="to" value="{{$to->id}}">
                <textarea name="msg" cols="" rows="" class="n_nutext" placeholder="請輸入內容" required></textarea>
                <input type="submit" id="msgsnd" class="n_bllbut" value="發信件" style="border-style: none;">
            </form>

        </div>
        <a id="" onclick="$('.blbg').click();" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>

    <div class="bl bl_tab" id="show_banned">
        <div class="bltitle"><span>檢舉{{$to->name}}</span></div>
        <div class="n_blnr01 ">
            <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ route('reportPost') }}">
                {!! csrf_field() !!}
                <input type="hidden" name="aid" value="{{$user->id}}">
                <input type="hidden" name="uid" value="{{$to->id}}">
            <textarea name="content" cols="" rows="" class="n_nutext" placeholder="請輸入檢舉理由"></textarea>
            <div class="n_bbutton">
                <button type="submit" class="n_bllbut" style="border-style: none;">送出</button>
{{--                <span><a class="n_left" href="">送出</a></span>--}}
{{--                <span><a class="n_right" href="">返回</a></span>--}}
            </div>
            </form>
        </div>
        <a id="" onclick="$('.blbg').click();" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>

@stop

@section('javascript')
<script>
    function show_chat() {
        $(".blbg").show();
        $("#show_chat").show();
    }

    function show_banned() {
        $(".blbg").show();
        $("#show_banned").show();
    }

    $(".but_block").on('click', function() {
        $.post('{{ route('postBlockAJAX') }}', {
            uid: '{{ $user->id }}',
            sid: '{{$to->id}}',
            _token: '{{ csrf_token() }}'
        }, function (data) {
            $("#tab_block").hide();
            show_message('封鎖成功');
            //window.location.reload();
        });
    });

    $(".addFav").on('click', function() {
        $.post('{{ route('postfavAJAX') }}', {
            uid: '{{ $user->id }}',
            to: '{{$to->id}}',
            _token: '{{ csrf_token() }}'
        }, function (data) {
            //$("#tab_block").hide();
            c2('收藏成功');
            //window.location.reload();
        });
    });

     @if (Session::has('message') && Session::get('message') == '檢舉成功')
     c2('檢舉成功');
     @endif

</script>
@stop