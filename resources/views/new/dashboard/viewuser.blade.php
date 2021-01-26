@extends('new.layouts.website')
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
@section('app-content')
    <style>
        .swiper-container {
            width: 100%;
            /*height: auto;*/
            /*z-index: unset;*/
        }

        .swiper-slide {
            /*width: 100%;*/
            height: 280px;
            margin: 0 auto;
            padding: 0px;
            display: table;
        }

        .swiper-slide img {
            /*width: 100%;*/
            max-width: 100%;
            /*max-height: 280px;*/
            display: block;
            margin: 0 auto;
        }

        @media (max-width:767px) {
            .swiper-container {
                width: 100%;
                height: 280px;
            }
            .swiper-slide {
                /*width: 100%;*/
                /*height: 200px !important;*/
                margin: 0 auto;
                padding: 0px;
                display: table
            }
            .swiper-slide img {
                /*width: 100%;*/
                max-width: 100%;
                /*max-height: 200px;*/
                display: block;
                margin: 0 auto;
            }

        }
        @media (max-width:768px) {
            .metx{
                position: unset;
            }
        }
        @media (max-width:1366px) {
            .swiper-container {
                width: 100%;
                height: 280px;
            }
            .swiper-slide {
                /*width: 100%;*/
                /*height: 280px;*/
                margin: 0 auto;
                padding: 0px;
                display: table
            }
            .swiper-slide img {
                /*width: 100%;*/
                max-width: 100%;
                /*max-height: 200px;*/
                display: block;
                margin: 0 auto;
            }
        }
        .n_blnr01 {
            padding-top: 20px !important;
        }
        .bottub ul li {
            height: 60px;
        }
        @media (min-width:812px) and (min-height: 375px) and (max-width:812px) and (max-height:375px) {
            .bl_tab{
                width: 40%;
                left: 30%;
            }
        }

        button{
            outline:none;
        }

        label{
            display: inherit;
        }

        .hide {
            display: none;
        }
        .clear {
            float: none;
            clear: both;
        }

        .rating {
            width: 130px;
            unicode-bidi: bidi-override;
            direction: rtl;
            text-align: center;
            position: relative;
        }

        .rating > label {
            float: right;
            display: inline;
            padding: 0;
            margin: 5px;
            position: relative;
            width: 1.1em;
            cursor: pointer;
            color: #000;
        }

        .rating > label:hover,
        .rating > label:hover ~ label,
        .rating > input.radio-btn:checked ~ label {
            color: transparent;
        }

        .rating > label:hover:before,
        .rating > label:hover ~ label:before,
        .rating > input.radio-btn:checked ~ label:before,
        .rating > input.radio-btn:checked ~ label:before {
            /*content: "\2605";*/
            content: url(/new/images/sxx_1.png);
            transform: scale(.8);
            /*background-image: url(/new/images/sxx_1.png);*/
            /*background-size: auto 20px;*/
            position: absolute;
            /*display: inline-block;*/
            left: -11.5px;
            z-index: 1;
            /*color: #FFD700;*/
            /*height: 20px;*/
        }

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
        .xl_text img{
            padding: 6px 0 0 0;
        }
    </style>
    <div class="container matop80">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                @if(isset($to))
                <div class="rightbg">
                    <div class="metx">
                        <div class="swiper-container photo">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide" data-type="avatar" data-sid="{{$to->id}}" data-pic_id=""><img src="@if(file_exists( public_path().$to->meta_()->pic ) && $to->meta_()->pic != ""){{$to->meta_()->pic}} @elseif($to->engroup==2)/new/images/female.png @else/new/images/male.png @endif"></div>

                                @foreach($member_pic as $row)
                                    <div class="swiper-slide" data-type="pic" data-sid="{{$to->id}}" data-pic_id="{{$row->id}}"><img src="{{$row->pic}}"></div>
                                @endforeach
                            </div>
                            <!-- Add Arrows -->
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                        <div class="n_jianj"><a onclick="show_reportPic()">檢舉大頭照</a></div>
                        <div class="tubiao">
                            <ul>
                                @php
                                    $isBlocked = \App\Models\Blocked::isBlocked($user->id, $to->id);
                                    $data = \App\Services\UserService::checkRecommendedUser($to);
                                @endphp
                                @if(isset($data['description']) && $to->engroup == 2)
                                    <li>
                                        <div class="tagText" data-toggle="popover" data-content="新進甜心是指註冊未滿30天的新進會員，建議男會員可以多多接觸，不過要注意是否為八大行業人員。" style="width: 100%">
                                        @if($user->isVip())
                                        <img src="/new/images/a1.png">
                                        @else
                                        <img src="/new/images/b_1.png" style="height: 50px;">
                                        @endif
                                        </div>
{{--                                        <span>{{$new_sweet}}</span>--}}
                                    </li>
                                @endif
                                @if(isset($data['description']) && $to->engroup == 1)
                                    <li>
                                        <div class="tagText" data-toggle="popover" data-content="優質會員是願意長期付費的VIP，或者常用車馬費邀請的男會員，建議女會員優先考慮。" style="width: 100%">
                                        @if($user->isVip())
                                        <img src="/new/images/a2.png">
                                        @else
                                        <img src="/new/images/b_2.png" style="height: 50px;">
                                        @endif
                                        </div>
{{--                                        <span>{{$well_member}}</span>--}}
                                    </li>
                                @endif
                                {{--                            <li><img src="/new/images/icon_23.png"><span>{{$money_cert}}</span></li>--}}
                                @if($to->isVip() && $to->engroup == 1)
                                    <li>
                                        <div class="tagText" data-toggle="popover" data-content="本站的付費會員。" style="width: 100%">
                                        @if($user->isVip())
                                        <img src="/new/images/a4.png">
                                        @else
                                        <img src="/new/images/b_4.png" style="height: 50px;">
                                        @endif
                                        </div>
{{--                                        <span>{{$label_vip}}</span>--}}
                                    </li>
                                @endif
                                {{--                            <li><img src="/new/images/icon_27.png"><span>{{$alert_account}}</span></li>--}}
                                @if($to->meta_()->isWarned == 1 || $to->isAdminWarned())
                                    <li>

                                        <div class="tagText" data-toggle="popover" data-content="此人被多人檢舉！與此會員交流務必提高警覺！">
                                        @if($user->isVip())
                                        <img src="/new/images/a5.png">
                                        @else
                                        <img src="/new/images/b_5.png" style="height: 50px;">
                                        @endif
                                        </div>
                                    </li>
                                @endif
                                @if($to->isPhoneAuth())
                                    <li>
                                        <div class="tagText" data-toggle="popover" data-content="Daddy們對於有通過手機驗證的Baby，會更主動聯絡妳，提升信賴感達55%以上。" style="width: 100%">
                                        @if($user->isVip())
                                        <img src="/new/images/a6.png" class="">
                                        @else
                                        <img src="/new/images/b_6.png" style="height: 50px;">
                                        @endif
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <div class="eg_o">
                            <!-- <div class="eg_oleft">
                                <div class="eg_jdt"><img src="images/t⁮o02.png">
                                    <font class="ef_pr">PR:20</font>
                                </div>
                            </div> -->
                            <div class="eg_oright">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if(intval($rating_avg)>=$i)
                                        <img src="/new/images/sxx_1.png">
                                    @elseif(strstr($rating_avg,'.') && ctype_digit($rating_avg)==false)
                                        <img src="/new/images/sxx_2.png">
                                        @break
                                    @endif
                                @endfor
                                @for ($i = 1; $i <= 5-round($rating_avg); $i++)
                                    <img src="/new/images/sxx_4.png">
                                @endfor
{{--                                <img src="/new/images/st_o.png"><img src="/new/images/sxx_1.png">--}}
{{--                                <img src="/new/images/sxx_2.png"><img--}}
{{--                                        src="/new/images/sxx_4.png"><img src="/new/images/sxx_4.png">--}}
                            </div>
                        </div>

                    </div>
                    <div class="bottub">

                        <ul>
                            @if(!$isBlocked)
                                <li>
                                    <a onclick="show_chat()"><img src="/new/images/icon_06.png" class="tubiao_i"><span>發信</span></a>
                                </li>
                            @endif
                            @if($user->isVip())
                                <li>
                                    <a class="addFav"><img src="/new/images/icon_08.png" class="tubiao_i"><span>收藏</span></a>
                                </li>
                            @else
                                <li>
                                    <img src="/new/images/icon_08.png" class="tubiao_i"><span>收藏</span>
                                    <span><img src="/new/images/icon_36.png" class="tap-vip"></span>
                                </li>
                            @endif
                            <li>
                                @if($user->isAdminWarned())
                                    <a onclick="show_Warned()"><img src="/new/images/icon_10.png" class="tubiao_i"><span>檢舉</span></a>
                                @else
                                    <a onclick="show_banned()"><img src="/new/images/icon_10.png" class="tubiao_i"><span>檢舉</span></a>
                                @endif
                            </li>
                            @if($user->isVip())
                                <li>
                                    @if($isBlocked)
                                        <a class="unblock"><img src="/new/images/icon_12_h.png" class="tubiao_i"><span>解除封鎖</span></a>
                                    @else
                                        @if($user->id == $to->id)
                                        <a onclick="show_message('不可封鎖給自己');"><img src="/new/images/icon_12.png" class="tubiao_i"><span>封鎖</span></a>
                                        @else
                                        <a onclick="show_block()"><img src="/new/images/icon_12.png" class="tubiao_i"><span>封鎖</span></a>
                                        @endif
                                    @endif
                                </li>
                            @else

                                <li>
                                    <img src="/new/images/icon_12.png" class="tubiao_i"><span>封鎖</span>
                                    <span><img src="/new/images/icon_36.png" class="tap-vip"></span>
                                </li>
                            @endif
                            <li class="evaluation">
{{--                                <a href="{{ url('/dashboard/evaluation/'.$to->id) }}"><img src="/new/images/icon_14.png" class="tubiao_i"><span>評價</span></a>--}}
                                <a><img src="/new/images/icon_14.png" class="tubiao_i"><span>評價</span></a>
                            </li>
                        </ul>
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
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->name}}</div>
                                        </span>
                                    </dt>
                                    <dt>
                                        <span>一句話形容自己</span>
                                        <span>
                                            <div class="select_xx03">{!! nl2br($to->title)!!}</div>
                                        </span>
                                    </dt>

                                    @if(!empty($to->exchange_period) && $to->engroup==2 && $user->isVip())
                                        <dt>
                                            <span>包養關係</span>
                                            <span>
                                            <div class="select_xx01 senhs hy_new">
                                                @php
                                                    $exchange_period_name = DB::table('exchange_period_name')->where('id',$to->exchange_period)->first();
                                                @endphp
                                                {{$exchange_period_name->name}}
                                            </div>
                                        </span>
                                        </dt>
                                    @endif

                                    @if($to->meta_()->isHideArea == '0')
                                    <dt>
                                        <span>地區</span>
                                        <?php
                                        if (!isset($to)) {
                                            $umeta = null;
                                        } else {
                                            $umeta = $to->meta_();
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
                                                        <font class="select_xx senhs left hy_new">{{$umeta->city[$key]}}</font>
                                                        <font class="select_xx senhs right hy_new">{{$umeta->area[$key]}}</font>
                                                    </span>
                                                @endforeach
                                            @endif
                                        @else
                                            <span>
                                                <font class="select_xx senhs left hy_new">{{$to->meta_()->city}}</font>
                                                <font class="select_xx senhs right hy_new">{{$to->meta_()->area}}</font>
                                            </span>
                                        @endif
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->budget))
                                    <dt>
                                        <span>預算</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta_()->budget}}</div>
                                        </span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->age()))
                                    <dt>
                                        <span>年齡</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta_()->age()}}</div>
                                        </span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->height))
                                    <dt>
                                        <span>身高（cm）</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta_()->height}}</div>
                                        </span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->body) && $to->meta_()->body != null && $to->meta_()->body != 'null')
                                    <dt>
                                        <span>體型</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta_()->body}}</div>
                                        </span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->cup) && $to->meta_()->isHideCup == '0')
                                    <dt>
                                        <span>CUP</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta_()->cup}}</div>
                                        </span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->about))
                                    <dt>
                                        <span>關於我</span>
                                        <span>
                                            <div class="select_xx03" >{!! nl2br($to->meta_()->about) !!}</div>
                                        </span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->style))
                                    <dt>
                                        <span>期待的約會模式</span>
                                        <span>
                                            <div class="select_xx03" >{!! nl2br($to->meta_()->style) !!}</div>
                                        </span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->situation) && $to->meta_()->situation != null && $to->meta_()->situation != 'null' && $to->engroup==2)
                                        <dt>
                                            <span>現況</span>
                                            <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta_()->situation}}</div>
                                        </span>
                                        </dt>
                                    @endif

                                    @if(!empty($to->meta_()->domainType) && $to->meta_()->domainType != null && $to->meta_()->domainType != 'null' )
                                    <dt>
                                        <span>產業</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta_()->domainType}}  @if(!empty($to->meta_()->domain) && $to->meta_()->domain != null && $to->meta_()->domain != 'null'){{$to->meta_()->domain}}@endif</div>
                                        </span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->occupation) && $to->meta_()->isHideOccupation == '0' && $user->isVip() && $to->meta_()->occupation != 'null')
                                    <dt>
                                        <span>職業</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta_()->occupation}}</div>
                                        </span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->education))
                                    <dt>
                                        <span>教育</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta_()->education}}</div>
                                        </span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->marriage))
                                    <dt>
                                        <span>婚姻</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta_()->marriage}}</div>
                                        </span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->drinking))
                                    <dt>
                                        <span>喝酒</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta_()->drinking}}</div>
                                        </span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->smoking))
                                    <dt>
                                        <span>抽煙</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta_()->smoking}}</div>
                                        </span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->income) && $to->engroup==1)
                                    <dt>
                                        <span>收入</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta_()->income}}</div>
                                        </span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->assets) && $to->engroup==1)
                                    <dt>
                                        <span>資產</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta_()->assets}}</div>
                                        </span>
                                    </dt>
                                    @endif



                                </div>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="ziliao">
                            <div class="ztitle"><span>進階資料</span>Advanced materials</div>
                            <div class="xiliao_input">
                                <div class="xl_text">
                                    <dt><span>註冊時間</span>@if($user->isVip())<font>{{substr($to->created_at,0,10)}}</font>@else <img src="/new/images/icon_35.png"> @endif</dt>
                                    <dt><span>最後上線時間</span>@if($to->valueAddedServiceStatus('hideOnline')==1) <img src="/new/images/no_open.png"> @else @if($user->isVip())  <font>{{substr($to->last_login,0,10)}}</font> @else <img src="/new/images/icon_35.png"> @endif  @endif</dt>
                                    <dt><span>每周平均上線次數</span>@if($to->valueAddedServiceStatus('hideOnline')==1) <img src="/new/images/no_open.png"> @else @if($user->isVip())  <font>{{$login_times_per_week }}</font> @else <img src="/new/images/icon_35.png"> @endif  @endif</dt>
                                    <dt><span>被收藏次數</span>@if($user->isVip()) <font>{{$be_fav_count}}</font> @else <img src="/new/images/icon_35.png"> @endif</dt>
                                    <dt><span>收藏會員次數</span>@if($user->isVip())<font>{{$fav_count}}</font>@else <img src="/new/images/icon_35.png"> @endif</dt>
                                    <dt><span>車馬費邀請次數</span>@if($user->isVip())<font>{{$tip_count}}</font>@else <img src="/new/images/icon_35.png"> @endif</dt>
                                    <dt><span>發信次數</span>@if($user->isVip())<font>{{$message_count}}</font>@else <img src="/new/images/icon_35.png"> @endif</dt>
                                    <dt><span>過去7天發信次數</span>@if($to->valueAddedServiceStatus('hideOnline')==1) <img src="/new/images/no_open.png"> @else @if($user->isVip())  <font>{{$message_count_7}}</font> @else <img src="/new/images/icon_35.png"> @endif @endif </dt>
                                    <dt><span>回信次數</span>@if($user->isVip())<font>{{$message_reply_count}}</font>@else <img src="/new/images/icon_35.png"> @endif</dt>
                                    <dt><span>過去7天回信次數</span>@if($to->valueAddedServiceStatus('hideOnline')==1) <img src="/new/images/no_open.png"> @else @if($user->isVip())  <font>{{$message_reply_count_7}}</font> @else <img src="/new/images/icon_35.png"> @endif @endif </dt>
                                    <dt><span>過去7天罐頭訊息比例</span>@if($to->valueAddedServiceStatus('hideOnline')==1) <img src="/new/images/no_open.png"> @else @if($user->isVip())  <font>{{$message_percent_7}}</font> @else <img src="/new/images/icon_35.png"> @endif @endif </dt>
                                    <dt><span>是否封鎖我</span>@if($user->isVip())<font>{{$is_block_mid}}</font>@else <img src="/new/images/icon_35.png"> @endif</dt>
                                    <dt><span>是否看過我</span>@if($user->isVip())<font>{{$is_visit_mid}}</font>@else <img src="/new/images/icon_35.png"> @endif</dt>
                                    <dt><span>瀏覽其他會員次數</span>@if($user->isVip())<font>{{$visit_other_count}}</font>@else <img src="/new/images/icon_35.png"> @endif</dt>
                                    <dt><span>過去7天瀏覽其他會員次數</span>@if($to->valueAddedServiceStatus('hideOnline')==1) <img src="/new/images/no_open.png"> @else @if($user->isVip())  <font>{{$visit_other_count_7}}</font>  @else <img src="/new/images/icon_35.png"> @endif @endif</dt>
                                    <dt><span>被瀏覽次數</span>@if($user->isVip())<font>{{$be_visit_other_count}}</font>@else <img src="/new/images/icon_35.png"> @endif</dt>
                                    <dt><span>過去7天被瀏覽次數</span>@if($user->isVip())<font>{{$be_visit_other_count_7}}</font>@else <img src="/new/images/icon_35.png"> @endif</dt>
                                    <dt><span>封鎖多少會員</span>@if($user->isVip())<font>{{$blocked_other_count}}</font>@else <img src="/new/images/icon_35.png"> @endif</dt>
                                    <dt><span>被多少會員封鎖</span>@if($user->isVip())<font>{{$be_blocked_other_count}}</font>@else <img src="/new/images/icon_35.png"> @endif</dt>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="line"></div>

                    <div class="ziliao ziliao3">
                        <div class="ztitle"><span>會員評價</span>Evaluation</div>
                        <div class="xiliao_input">
                            <div class="xl_text">
                                <div class="pjliuyan02 amar15">
                                    <ul style="width: 100%;">
                                        @foreach( $evaluation_data as $row)
                                            @php
                                                $row_user = \App\Models\User::findById($row->from_id);
                                                $to_user = \App\Models\User::findById($row->to_id);
                                            @endphp
                                            <li>
                                                <div class="piname">
                                                    <span>
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if($row->rating>=$i)
                                                                <img src="/new/images/sxx_1.png">
                                                            @else
                                                                <img src="/new/images/sxx_4.png">
                                                            @endif
                                                        @endfor
                                                    </span>
                                                    <a href="/dashboard/viewuser/{{$row_user->id}}?time={{ \Carbon\Carbon::now()->timestamp }}">{{$row_user->name}}</a>
                                                    {{--                                <font>{{ substr($row->created_at,0,10)}}</font>--}}
                                                    @if($row_user->id==$user->id)
                                                        <font class="sc content_delete" data-id="{{$row->id}}" style="padding: 0px 3px;"><img src="/new/images/del_03.png" style="padding: 0px 0px 1px 5px;">刪除</font>
                                                    @endif
                                                </div>
                                                <div class="con">
                                                    <p class="many-txt">{!! nl2br($row->content) !!}</p>
                                                    <h4>
                                                        <span class="btime">{{ substr($row->created_at,0,10)}}</span>
                                                        <button type="button" class="al_but">完整評價</button>
                                                    </h4>
                                                </div>

                                                @if(empty($row->re_content) && $to->id == $user->id)
                                                    <div class="huf">
                                                        <form id="form_re_content" action="{{ route('evaluation_re_content')."?n=".time() }}" method="post">
                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                            <span class="huinput">
                                                                <textarea name="re_content" type="text" class="hf_i" placeholder="請輸入回覆（最多120個字符）" maxlength="120"></textarea>
                                                            </span>
                                                            <div class="re_area">
                                                                <a class="hf_but" onclick="form_re_content_submit()">回覆</a>
                                                            </div>
                                                            <input type="hidden" name="id" value={{$row->id}}>
                                                            <input type="hidden" name="eid" value={{$to->id}}>
                                                        </form>
                                                    </div>
                                                @elseif(!empty($row->re_content))
                                                    <div class="hu_p">
                                                        <div class="he_b">
                                                            <span class="left"><img src="@if(file_exists( public_path().$to_user->meta_()->pic ) && $to_user->meta_()->pic != ""){{$to_user->meta_()->pic}} @elseif($to_user->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="he_zp">{{$to_user->name}}</span>
                                                            @if($to_user->id==$user->id)
                                                                <font class="sc re_content_delete" data-id="{{$row->id}}"><img src="/new/images/del_03.png">刪除</font>
                                                            @endif
                                                        </div>
                                                        <div class="he_two">
                                                            <div class="context">
                                                                <div id="test" class="context-wrap" style="word-break: break-all;">{!! nl2br($row->re_content) !!}</div>
                                                            </div>
                                                        </div>
                                                        <div class="he_twotime">{{ substr($row->re_created_at,0,10)}}<span class="z_more">展開</span></div>
                                                    </div>
                                                @endif

                                            </li>
                                        @endforeach

                                    </ul>
                                    <div style="text-align: center;">
                                        {!! $evaluation_data->appends(request()->input())->links('pagination::sg-pages2') !!}
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>

{{--                    <!--會員評價-->--}}
{{--                        <div class="ziliao">--}}
{{--                            <div class="ztitle"><span>會員評價</span>Evaluation</div>--}}
{{--                            <div class="pw_body" style="margin-top:40px; margin-bottom:40px">--}}
{{--                                <a class="pjnew_but01" href="{{ url('/dashboard/evaluation/'.$to->id) }}"><img src="/new/images/ly01.png">我要評價</a>--}}
{{--                                <ul>--}}
{{--                                    <div class="huiy_a">--}}
{{--                                        @for ($i = 1; $i <= 5; $i++)--}}
{{--                                            @if(intval($rating_avg)>=$i)--}}
{{--                                                <img src="/new/images/sxx_1.png">--}}
{{--                                            @elseif(strstr($rating_avg,'.') && ctype_digit($rating_avg)==false)--}}
{{--                                                <img src="/new/images/sxx_2.png">--}}
{{--                                                @break--}}
{{--                                            @endif--}}
{{--                                        @endfor--}}
{{--                                        @for ($i = 1; $i <= 5-round($rating_avg); $i++)--}}
{{--                                            <img src="/new/images/sxx_4.png">--}}
{{--                                        @endfor--}}
{{--                                        {{round($rating_avg,1)}}--}}
{{--                                    </div>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    <!--會員評價-->--}}
                @endif
            </div>
        </div>
    </div>

    @if(isset($to))
    <div class="bl bl_tab" id="show_chat">
        <div class="bltitle"><span>發送給{{$to->name}}</span></div>
        <div class="n_blnr01 ">

            <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="/dashboard/chat2/{{ \Carbon\Carbon::now()->timestamp }}" id="chatForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                <input type="hidden" name="userId" id="userId" value="{{$user->id}}">
                <input type="hidden" name="to" id="to" value="{{$to->id}}">
                <input type="hidden" name="{{ \Carbon\Carbon::now()->timestamp }}" value="{{ \Carbon\Carbon::now()->timestamp }}">
                <textarea name="msg" id="msg" cols="" rows="" class="n_nutext" placeholder="請輸入內容" required></textarea>
                <input type="submit" class="n_bllbut msgsnd" value="發信件" style="border-style: none;">
            </form>

        </div>
        <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>

    <div class="bl bl_tab" id="show_banned">
        <div class="bltitle"><span>檢舉{{$to->name}}</span></div>
        <div class="n_blnr01 ">
            <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ route('reportPost') }}">
                {!! csrf_field() !!}
                <input type="hidden" name="aid" value="{{$user->id}}">
                <input type="hidden" name="uid" value="{{$to->id}}">

                <textarea name="content" cols="" rows="" class="n_nutext" placeholder="{{$report_member}}"></textarea>
                <div class="n_bbutton">
                    <button type="submit" class="n_right" style="border-style: none; background: #8a9ff0; color:#ffffff;">送出</button>
                    <button type="reset" class="n_left" style="border-style: none;background: #ffffff; color:#8a9ff0;" onclick="$('#show_banned').hide();$('.announce_bg').hide()">返回</button>

                </div>
            </form>
        </div>
        <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>

    <div class="bl bl_tab" id="show_reportPic">
        <div class="bltitle"><span>檢舉{{$to->name}}</span></div>
        <div class="n_blnr01 ">
            <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ route('reportPicNextNew') }}">
                {!! csrf_field() !!}
                <input type="hidden" name="aid" value="{{$user->id}}">
                <input type="hidden" name="uid" value="{{$to->id}}">
                <input type="hidden" name="picType" value="">
                <input type="hidden" name="pic_id" value="">


                <textarea name="content" cols="" rows="" class="n_nutext" placeholder="{{$report_avatar}}" required></textarea>
                <div class="n_bbutton">
                    <button type="submit" class="n_right" style="border-style: none; background: #8a9ff0; color:#ffffff;">送出</button>
                    <button type="reset" class="n_left" style="border-style: none;background: #ffffff; color:#8a9ff0;" onclick="$('#show_reportPic').hide();$('.blbg').hide()">返回</button>

                </div>
            </form>
        </div>
        <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>

    <div class="bl bl_tab" id="tab_evaluation" style="display: none;">
        <div class="bltitle"><span>評價 {{$to->name}}</span></div>
        <div class="n_blnr01" style="padding-top: 10px !important;">
            <form id="form1" action="{{ route('evaluation')."?n=".time() }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="nhuiy_a" style="margin-bottom: 10px;">
                    <div class="rating">
                        <input id="star5" name="rating" type="radio" value="5" class="radio-btn hide" data-title="5"/>
                        <label for="star5" data-title="5"><img src="/new/images/sxx_4.png" style="transform: scale(.8);"></label>
                        <input id="star4" name="rating" type="radio" value="4" class="radio-btn hide"/>
                        <label for="star4" data-title="4"><img src="/new/images/sxx_4.png" style="transform: scale(.8);"></label>
                        <input id="star3" name="rating" type="radio" value="3" class="radio-btn hide"/>
                        <label for="star3" data-title="3"><img src="/new/images/sxx_4.png" style="transform: scale(.8);" ></label>
                        <input id="star2" name="rating" type="radio" value="2" class="radio-btn hide"/>
                        <label for="star2" data-title="2"><img src="/new/images/sxx_4.png" style="transform: scale(.8);" alt="2"></label>
                        <input id="star1" name="rating" type="radio" value="1" class="radio-btn hide"/>
                        <label for="star1" data-title="1"><img src="/new/images/sxx_4.png" style="transform: scale(.8);" data-toggle="tooltip" data-placement="top" title="1"></label>
                        <div class="clear"></div>
                    </div>
                </div>
                <textarea id="content" name="content" cols="" rows="" class="n_nutext evaluation_content" style="border-style: none;" maxlength="300" placeholder="請輸入內容(至多300個字符)"></textarea>
                    <input type="hidden" name="uid" value={{$user->id}}>
                    <input type="hidden" name="eid" value={{$to->id}}>
                <span class="alert_tip" style="color:red;"></span>
                <div class="n_bbutton" style="margin-top:18px;">
                    <span><a class="n_left" onclick="form_submit()">送出</a></span>
                    <span><a class="n_right" onclick="$('#tab_evaluation').hide();$('.blbg').hide()">返回</a></span>
                </div>
            </form>
        </div>
        <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>

    <div class="bl bl_tab" id="tab_reject_female" style="display: none;">
        <div class="bltitle"><span>提示</span></div>
        <div class="n_blnr01 ">
            <div class="new_tkfont">您目前未達評價標準<br>不可對此會員評價</div>
            <div class="new_tablema">
                <table>

                    <tr>
                        <td class="new_baa new_baa1">女生須通過手機驗證</td>
                        <td class="new_baa1">@if($auth_check>0)<img src="/new/images/ticon_01.png">@else<img src="/new/images/ticon_02.png">@endif</td>
                    </tr>
                    <tr>
                        <td class="new_baa">男方須回覆女方三次以上</td>
                        <td class="">@if(!$user->isSent3Msg($to->id))<img src="/new/images/ticon_02.png">@else<img src="/new/images/ticon_01.png">@endif</td>
                    </tr>
                </table>
            </div>
        </div>
        <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>

    <div class="bl bl_tab" id="tab_reject_male" style="display: none;">
        <div class="bltitle"><span>提示</span></div>
        <div class="n_blnr01 ">
            <div class="new_tkfont">您目前未達評價標準<br>不可對此會員評價</div>
            <div class="new_tablema">
                <table>

                    <tr>
                        <td class="new_baa new_baa1">男方須為一個月(不含一個月)以上VIP</td>
                        <td class="new_baa1">@if($vipDays>=30)<img src="/new/images/ticon_01.png">@else<img src="/new/images/ticon_02.png">@endif</td>
                    </tr>
                    <tr>
                        <td class="new_baa">女方須有回覆男方三次以上</td>
                        <td class="">@if(!$user->isSent3Msg($to->id))<img src="/new/images/ticon_02.png">@else<img src="/new/images/ticon_01.png">@endif</td>
                    </tr>
                </table>
            </div>
        </div>
        <a id="" onClick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>

    @endif
@stop

@section('javascript')

<script>

    $( document ).ready(function() {

        // $('.tagText').on('click', function() {
        //    alert($(this).data('content'));
        //    c3($(this).data('content'));
        // });
        $('[data-toggle="popover"]').popover({
            animated: 'fade',
            placement: 'bottom',
            trigger: 'hover',
            html: true,
            content: function () { return '<div' + $(this).data('content') + '</div>'; }
        // })
        //     .click(function(e) {
        //     e.preventDefault();
        //     $(this).popover('toggle');
        });


        if(window.matchMedia("(min-width: 992px)").matches){
            $(".swiper-container").css('height',$(".metx").height()- 70);
        }
        //固定高取得
        var bottom_height=$('.tubiao ul').height();
        //浮動高度
        var img_height = $(".swiper-container").height();
        // alert(img_height);
        $(".swiper-slide img").css('height',img_height - (bottom_height/2));
        // $(".swiper-slide img").css('height',img_height);
        $('.tubiao').css('top',img_height - (bottom_height/2) - 40);
        $(window).resize(function() {
            // alert($('.tubiao ul').height());
            // var wdth=$(window).width();
            // $("span").text(wdth);
            var img_height = $(".swiper-container").height();
            $(".swiper-slide img").css('height',img_height - (bottom_height/2));
            // $(".swiper-slide img").css('height',img_height);
            $('.tubiao').css('top',img_height - (bottom_height/2) - 40);
            // alert(img_height - ($('.tubiao ul').height() / 2));
        });

        if( /Android|webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            $('.metx').css('position','unset');
        }
    });
    // $( document ).ready(function() {
        @if(isset($to))
            @if(isset($is_block_mid) && $is_block_mid == '是')
                ccc('此用戶已關閉資料。');
                $('.row').css('display','none');
            @elseif($to->accountStatus == 0)
                ccc('此用戶已關閉資料。');
                $('.row').css('display','none');
            @endif
        @endif
    // });

    @if (isset($errors) && $errors->count() > 0)
        @foreach ($errors->all() as $error)
            c5('{{ $error }}');
        @endforeach
    @endif
    @if(isset($timeSet) && isset($countSet))
        function doCookieSetup(name, value) {
            //console.log('count1');
            var expires = new Date();
            //有效時間保存 2 天 2*24*60*60*1000
            expires.setTime(expires.getTime() + 172800000);
            document.cookie = name + "=" + escape(value) + ";expires=" + expires.toGMTString()
        }
        function getCookie(name) {
            //console.log('count2');
            var arg = escape(name) + "=";
            var nameLen = arg.length;
            var cookieLen = document.cookie.length;
            var i = 0;
            while (i <cookieLen) {
                var j = i + nameLen;
                if (document.cookie.substring(i, j) == arg) return getCookieValueByIndex(j);
                i = document.cookie.indexOf(" ", i) + 1;
                if (i == 0) break;
            }
            return null;
        }
        function delete_cookie( name ) {
            //console.log('count3');
            document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        }
        function getCookieValueByIndex(startIndex) {
            //console.log('count4');
            var endIndex = document.cookie.indexOf(";", startIndex);
            if (endIndex == -1) endIndex = document.cookie.length;
            return unescape(document.cookie.substring(startIndex, endIndex));
        }
        function GetDateDiff(startTime, endTime, diffType) {
            //console.log('count5');
            //將xxxx-xx-xx的時間格式，轉換為 xxxx/xx/xx的格式
            startTime = startTime.replace(/\-/g, "/");
            endTime = endTime.replace(/\-/g, "/");
            //將計算間隔類性字元轉換為小寫
            diffType = diffType.toLowerCase();
            var sTime = new Date(startTime); //開始時間
            var eTime = new Date(endTime); //結束時間
            //作為除數的數字
            var divNum = 1;
            switch (diffType) {
                case "second":
                    divNum = 1000;
                    break;
                case "minute":
                    divNum = 1000 * 60;
                    break;
                case "hour":
                    divNum = 1000 * 3600;
                    break;
                case "day":
                    divNum = 1000 * 3600 * 24;
                    break;
                default:
                    break;
            }
            return parseInt((eTime.getTime() - sTime.getTime()) / parseInt(divNum));
        }
        function htmlencode(s){
            //console.log('count6');
            var div = document.createElement('div');
            div.appendChild(document.createTextNode(s));
            return div.innerHTML;
        }
        function htmldecode(s){
            //console.log('count7');
            var div = document.createElement('div');
            div.innerHTML = s;
            return div.innerText || div.textContent;
        }
        /*取得次數*/
        //console.log('count8');
        var count=getCookie('count');
        if(count==undefined){
            count=0;
        }
        /*取得現在時間*/
        var today=new Date();
        var now = today.getFullYear()+'/'+(today.getMonth()+1)+'/'+today.getDate()+' '+today.getHours()+':'+today.getMinutes()+':'+today.getSeconds();
        // console.log(now);
        // var now       = 20191216215141;
        /*取得紀錄時間*/
        var countTime = getCookie('countTime');
        console.log(countTime);
        if(countTime==undefined){
            countTime = now;
        }
        $(document).ready(function(){
            var bodyMain = document.getElementById('msg');
            if(GetDateDiff(countTime, now, "minute")>"{{$timeSet}}"){
                //console.log('count10');
                delete_cookie('count');
                delete_cookie('countTime');
            }
            if(GetDateDiff(countTime, now, "minute")<="{{$timeSet}}"){
                //console.log('count11');
                if(count >= {{$countSet}}){
                    // console.log('count12');
                    console.log("countM: {{$countSet}}");
                    //禁止複製
                    bodyMain.oncopy = function(){
                        return false;
                    }
                    //禁止貼上
                    bodyMain.onpaste = function(){
                        return false;
                    }
                }
                else{
                    // console.log('count13');
                    doCookieSetup('countTime',now);
                    bodyMain.onpaste = function(){
                        count++;
                        console.log("countTime: " + count);
                        doCookieSetup('count',count);
                    }
                }
            }
        });
    @endif

    function show_chat() {
        //$(".blbg").show();
        var uid='{{ $user->id }}';
        var to='{{$to->id}}';
        if(uid != to){
            $(".announce_bg").show();
            $("#show_chat").show();
        }else{
            show_message('不可發信給自己');
        }
    }

    function show_banned() {
        //$(".blbg").show();
        var uid='{{ $user->id }}';
        var to='{{$to->id}}';
        if(uid != to){
            $(".announce_bg").show();
            $("#show_banned").show();
        }else{
            show_message('不可檢舉給自己');
        }
    }

    function show_reportPic() {
        $(".blbg").show();
        $("#show_reportPic").show();
        // alert($('.swiper-slide-active').data('type'));
        $('input[name="picType"]').val($('.swiper-slide-active').data('type'));
        $('input[name="pic_id"]').val($('.swiper-slide-active').data('pic_id'));
    }
    @if(isset($to))
        $(".but_block").on('click', function() {
            var uid='{{ $user->id }}';
            var to='{{$to->id}}';
            if(uid != to){
                $.post('{{ route('postBlockAJAX') }}', {
                    uid: uid,
                    to: to,
                    _token: '{{ csrf_token() }}'
                }, function (data) {
                    // if(data.save=='ok') {
                        $("#tab_block").hide();
                        // $(".blbg").hide();
                        show_message('封鎖成功');
                    // }
                });
            }else{
                show_message('不可封鎖自己');
            }
        });


        $('.unblock').on('click', function() {
            c4('確定要解除封鎖嗎?');
            var uid='{{ $user->id }}';
            var to='{{$to->id}}';
            if(uid != to){
                $(".n_left").on('click', function() {
                    $.post('{{ route('unblockAJAX') }}', {
                        uid: uid,
                        to: to,
                        _token: '{{ csrf_token() }}'
                    }, function (data) {
                        $("#tab04").hide();
                        show_message('已解除封鎖');
                    });
                });
            }else{
                show_message('不可解除封鎖自己');
            }
        });

        $(".addFav").on('click', function() {
            var uid='{{ $user->id }}';
            var to='{{$to->id}}';
            if(uid != to){
                $.post('{{ route('postfavAJAX') }}', {
                    uid: uid,
                    to: to,
                    _token: '{{ csrf_token() }}'
                }, function (data) {
                    if(data.save=='ok') {
                        c2('收藏成功');
                    }else if(data.save=='error'){
                        c2('收藏失敗');
                    }else if(data.isBlocked){
                        c2('封鎖中無法收藏');
                    }else if(data.isFav){
                        c2('已在收藏名單中');
                    }
                });
            }else{
                c2('不可收藏自己');
            }
            
        });
         $("#msgsnd").on('click', function(){

            $.ajax({
                url: '/dashboard/chat2/{{ Carbon\Carbon::now()->timestamp }}',
                type: 'POST',
                data: {
                    _token   :"{{ csrf_token() }}",
                    userId   : $("#userId").val(),
                    to       : $("#to").val(),
                    msg      : $("#msg").val(),
                    {{ \Carbon\Carbon::now()->timestamp }} : "{{ \Carbon\Carbon::now()->timestamp }}"
                },
                success: function(response) {
                   window.location.reload();
                }
            });
         });
    @endif

    @if (Session::has('message') && Session::get('message')=="此用戶已關閉資料。")
        ccc('{{Session::get('message')}}');
    @elseif(Session::has('message'))
        c5('{{Session::get('message')}}');
    @endif

    $(".n_bllbut_tab_other").on('click', function() {
        $('#tab_other').hide();
        if (document.referrer != "") {
            window.history.back();
        }else{
            location.href = '/dashboard/search';
        }
    });

    // $.noConflict();
    
    function show_Warned() {
        c5('無法檢舉');
    }

    @if(isset($to))
        $('.evaluation').on('click', function() {
            @if($user->id != $to->id)
                @if($user->meta_()->isWarned == 1 || $user->isAdminWarned())
                    c5('您目前為警示帳戶，暫不可評價');
                @elseif($user->engroup==2 && ($user->isSent3Msg($to->id)==0 || $auth_check==0))
                    // alert(1);
                    $('#tab_reject_female').show();
                    $(".announce_bg").show();
                @elseif($user->engroup==1 && ($user->isSent3Msg($to->id)==0 || $vipDays<=30))
                    //alert(2);
                    $('#tab_reject_male').show();
                    $(".announce_bg").show();
                @elseif(!isset($evaluation_self))
                    $('#tab_evaluation').show();
                    $(".announce_bg").show();
                @else
                    c5('您已評價過');
                @endif
            @else
                c5('不可對自己評價');
            @endif
        });
    @endif

    function form_submit(){
        if( $("input[name='rating']:checked").val() == undefined) {
            // c5('請先點擊星等再評價');
            $('.alert_tip').text();
            $('.alert_tip').text('請先點擊星等再評價');
            return false;
        }else if($.trim($(".evaluation_content").val())=='') {
            // c5('請輸入評價內容');
            $('.alert_tip').text();
            $('.alert_tip').text('請輸入評價內容');
            return false;
        }else if($(".evaluation_content").val().length>300) {
            // c5('請輸入評價內容');
            $('.alert_tip').text();
            $('.alert_tip').text('評價至多300個字符');
            return false;
        }else{
            $('#form1').submit();
        }
        return false;
    }

    $('.content_delete').on( "click", function() {
        c4('確定要刪除嗎?');
        $(".n_left").on('click', function() {
            $.post('{{ route('evaluation_delete') }}', {
                id: $('.content_delete').data('id'),
                _token: '{{ csrf_token() }}'
            }, function (data) {
                $("#tab04").hide();
                c5('評價已刪除');
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
                c5('回覆已刪除');
            });
        });
    });

    $('textarea.hf_i').on({input: function(){
            var totalHeight = $(this).prop('scrollHeight') - parseInt($(this).css('padding-top')) - parseInt($(this).css('padding-bottom'));
            $(this).css({'height':totalHeight});
            if(totalHeight>40) {
                $('.re_area').css({'top': totalHeight - 40});
            }
        }
    });

    let button = document.getElementsByTagName('button');
    let p = document.getElementsByTagName('p');

    for(let i=0; i<button.length; i++) {
        button[i].onclick = function () {
            if(this.innerHTML == "送出") {
                return true;
            }
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

@stop
