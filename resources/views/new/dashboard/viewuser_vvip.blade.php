@extends('new.layouts.website')
@section('style')
{{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.2/photoswipe.min.css">--}}
{{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.2/default-skin/default-skin.min.css">--}}
    {{--    <link rel="stylesheet" href="test/prittyprint.css">--}}
{{--    <link rel="stylesheet" href="{{ asset('css/photoswipe/styles.css') }}">--}}
{{--    <link rel="stylesheet" href="{{ asset('css/photoswipe/prittyprint.css') }}">--}}
    {{--    <link rel="stylesheet" href="/new/css/swiper.min2.css">--}}
    <script src="{{asset('/new/js/pick_real_error.js')}}" type="text/javascript"></script>

    <link rel="stylesheet" href="/new/css/swiper.min2.css">
    <style>
        @media (max-width:320px) {
            .shou font{ font-size: 14px;}
        }
        .swiper-container {
            width: 100%;
            margin: 0 auto; overflow: hidden;
        }

        .swiper-slide {
            justify-content: center;
            align-items: center;
            display: flex;
            padding: 10px 0;
        }

        .sild img {
            width: 100%;
        }
        .swiper-button-next, .swiper-button-prev{margin-top: -53px;width: 20px; height: 41px;background-size: 20px 41px;}
        .swiper-button-next, .swiper-container-rtl .swiper-button-prev {
            background-image:url(/new/images/a_21.png);
            right: 10px;
            left: auto;
        }
        .swiper-button-prev, .swiper-container-rtl .swiper-button-next {
            background-image:url(/new/images/a_19.png);
            left: 10px;
            right: auto;
        }
        .shou{width: 93%;}

        .metx_tab {width: 93%; margin: 0 auto; display: table; padding-top:0px; padding-bottom: 0px;}
        @media (max-width:992px) {
        .metx_tab {width:93%; margin: 0 auto; display: table;}	
        }

        .hdlist1_left{width: 48%; float: left; height: auto; box-shadow:0 -3px 10px rgba(255,188,193,0.5); border-radius: 6px;}
        .hdlist1_left_tab{width: 100%; display: table; box-shadow:0px 15px 20px rgba(255,79,92,0.5); padding:15px 0px 0px 0px;background:linear-gradient(110deg, #ff9290, #ffdade); position: relative;
        border-radius: 6px; overflow: hidden;}

        .hs_tnes{width: 100%; display: table; padding: 0 5px;}
        .hs_tnes img{ height:46px; float: left;}
        .she_fontetex{font-size: 16px; font-weight: bold; color: #ffffff; position: absolute; left:52px;}
        .she_fontetex span{ font-size: 12px; display: table; font-weight: normal;}

        .sh_button_w{width: 100%; margin: 0 auto; display: table;margin-top:6px;background: url(/new/images/vvip_b.png); background-size: cover;height: 45px; text-align: center; line-height: 45px; color: #fff; font-weight: bold; font-size:16px;}

        .hdlist2_right{width: 48.5%; float: right; height: auto;  box-shadow:0 -3px 10px rgba(255,188,193,0.5);border-radius: 6px;}
        .hdlist2_right_tab{width: 100%; display: table;box-shadow:0px 15px 20px rgba(255,79,92,0.5); padding:15px 0px 0px 0px;background:linear-gradient(120deg, #ff9290, #ffdade);position: relative;
        border-radius: 6px; overflow: hidden;}
        .s_bushi{ position: absolute; right: 0; border-radius: 100px 0 0 100px; background: rgba(255,255,255,0.6);top:5px; color: #fd6986; font-size:14px; padding:3px 10px;cursor: pointer;}
        .s_bushi:hover{ color: #f65f6e;background: rgba(255,255,255,0.8);}


        @media (max-width:450px) {
        .hdlist2_right_tab {padding:20px 0px 0px 0px;}
        .hdlist1_left_tab{padding:20px 0px 0px 0px;}
        .s_bushi{ font-size:12px;padding:0px 4px;}
        }


        @media (max-width:320px) {
        .hdlist2_right_tab {padding:15px 0px 0px 0px;}
        .hdlist1_left_tab{padding:15px 0px 0px 0px;}
        .hdlist1_left{width: 100%; margin-bottom:20px;}
        .hdlist2_right{width:100%; }
        .s_bushi{ font-size:12px;padding:3px 10px;}
        }

        .fileuploader-icon-remove:after {content: none !important;}
    </style>
@endsection
@section('app-content')

{{--    @php--}}
{{--        $isBlurAvatar = \App\Services\UserService::isBlurAvatar($targetUser, $user);--}}
{{--        $isBlurLifePhoto = \App\Services\UserService::isBlurLifePhoto($targetUser, $user);--}}
{{--    @endphp--}}
    <div id="app"  ontouchstart="" onmouseover="">
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="v_bg_vv">
                    @if(Request()->get('page_mode')=='edit')
                        <a href="{!! url('dashboard') !!}" class="zh_shed_right" style="z-index: 6;"></a>
                    @endif
                    <div class="v_tx">
                        <div class="c_toux"><img src="@if(file_exists( public_path().$targetUser->meta->pic ) && $targetUser->meta->pic != ""){{$targetUser->meta->pic}} @elseif($targetUser->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
                        <div class="c_tfontright">
                            <h1>
                                <span>
                                    {{$targetUser->name}}
                                </span>
                                @if($targetUser->VvipSubOptionEntrepreneur->first() ?? false)
                                <span class="ci_tub">
                                    {{$targetUser->VvipSubOptionEntrepreneur->first()->option_name}} {{$targetUser->VvipSubOptionEntrepreneurCeoTitle->first()->option_name}}
                                </span>
                                @endif
                            </h1>
                        </div>
                    </div>
                    <div class="ci_jianjie"><span>{!! nl2br($targetUser->title)!!}</span></div>
                    <div class="ci_beij" style="margin-top:35px;">
                        <div class="ci_beij_1">
                            <div class="ci_kuang">
                                <div class="ci_bfont">Daddy背景</div><img src="/new/images/zb_7.png" class="ci_imgf">
                            </div>
                            <div class="ci_kborder">
                                <div class="cl_liswidt">
                                    @foreach($targetUser->VvipPointInformation as $option)
                                        <li class="c_mr6 ">
                                            <div class="c_hlist01">
                                                <div class="c_hlist02 c_pr6">
                                                    <div class="c_hfont01">
                                                        {{$option->option_name}}
                                                        @if($option->option_name == '高資產')
                                                            : {{$targetUser->VvipSubOptionHighAssets->first()->option_name}}
                                                        @elseif($option->option_name == '企業負責人')
                                                            : {{$targetUser->VvipSubOptionCeoTitle->first()->option_name}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </div>
                            </div>


                            <div class="ci_kuang" style="margin-top: 15px;">
                                <div class="ci_bfont">Daddy特色</div><img src="/new/images/zb_7.png" class="ci_imgf">
                            </div>
                            <div class="ci_kborder">
                                <div class="cl_liswidt">
                                    @foreach($targetUser->VvipDateTrend as $option)
                                        <li class="c_mr6 ">
                                            <div class="c_hlist01">
                                                <div class="c_hlist02 c_pr6">
                                                    <div class="c_hfont01">{{$option->option_name}}</div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="c_button">

                        <a @if($targetUser->id==$user->id)onclick="show_chat()" @else @if($isBlocked) onclick="messenge_show_block()" @else href="/dashboard/chat2/chatShow/{{ $targetUser->id }}?from_viewuser_page=1" @endif @endif class="c_but01 left">
                            <div class="c_but01_1"><font>發訊互動</font><span><img src="/new/images/zb_3.png"></span></div>
                        </a>

                        @if($user->isVip() || $user->isVVIP())
                            @php
                                $isFav = \App\Models\MemberFav::where('member_id', $user->id)->where('member_fav_id',$targetUser->id)->count();
                            @endphp
                            @if($isFav)
                                <a class="c_but01 right favIcon removeFav">
                                    <div class="c_but01_1">
                                        <font>移除收藏</font>
                                        <span><img src="/new/images/icon_08.png"></span>
                                    </div>
                                </a>
                            @else
                            <a class="c_but01 right favIcon addFav">
                                <div class="c_but01_1">
                                    <font>立即收藏</font>
                                    <span><img src="/new/images/zb_4.png"></span>
                                </div>
                            </a>
                            @endif
                        @endif

                    </div>


                    <!-- 背景與資產 -->
                    <div class="shou" style="border-bottom: none; margin-bottom:5px; margin-top:20px;">
                        <span style="font-size: 18px;">Daddy背景與資產</span>
                        <font>Wealth background</font>
                    </div>
                    @if($targetUser->VvipBackgroundAndAssets->first() ?? false)
                        <div class="nn_zeng">
                            @foreach($targetUser->VvipBackgroundAndAssets as $key => $option)
                                <div class="nn_listleft @if($key % 2 == 0) left @else right @endif matop25">
                                    <div class="nn_li_01"><span>{{$option->option_name}}</span></div>
                                    <div class="nn_li_02">
                                        @if($option->option_name == '專業人士')
                                            @foreach($targetUser->VvipSubOptionProfessional as $key => $sub_option)
                                                @if($key !== 0)
                                                、
                                                @endif
                                                {{$sub_option->option_name}}
                                            @endforeach
                                        @elseif($option->option_name == '高資產人士')
                                            @foreach($targetUser->VvipSubOptionHighNetWorth as $key => $sub_option)
                                                @if($key !== 0)
                                                、
                                                @endif
                                                {{$sub_option->option_name}}
                                                {{DB::table('vvip_sub_option_xref')->where('user_id', $targetUser->id)->where('option_type', 'high_net_worth')->where('option_id', $sub_option->id)->first()->option_remark}}
                                                %
                                            @endforeach
                                        @elseif($option->option_name == '企業家')
                                            {{$targetUser->VvipSubOptionEntrepreneur->first()->option_name}}{{$targetUser->VvipSubOptionEntrepreneurCeoTitle->first()->option_name}}
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="ci_ddbg" style="margin-bottom: -20px;">
                            <div class="ci_lidw"><h2>尚無資料</h2></div>
                        </div>
                    @endif
                    <!-- 背景與資產 -->

                    <!-- 溫情照顧 -->
                    <div class="shou" style="border-bottom: none; margin-bottom:5px; margin-top:20px;">
                        <span style="font-size: 18px;">Daddy溫情照顧</span>
                        <font>Warm care</font>
                    </div>
                    @if($targetUser->VvipExtraCare->first() ?? false)
                        <div class="nn_zeng">
                            @foreach($targetUser->VvipExtraCare as $option)
                                <div class="nzhaog matop13">
                                    <div class="zhg_but"><span>{{ $option->option_name }}</span></div>
                                    <div class="zh_text">
                                        @if($option->option_name == '專業人脈')
                                            @php
                                                $network_depth = DB::table('vvip_sub_option_xref')->where('user_id', $targetUser->id)->where('option_type', 'professional_network')->first()->option_remark;
                                            @endphp
                                            {{$targetUser->VvipSubOptionProfessionalNetwork->first()->option_name}}、
                                            @if($network_depth == 'high')
                                                可視情況幫baby安排實習/正式職務
                                            @elseif($network_depth == 'low')
                                                只能提供顧問以及諮詢
                                            @endif
                                        @elseif($option->option_name == '生活照顧')
                                            @foreach($targetUser->VvipSubOptionLifeCare as $key => $sub_option)
                                                @if($key !== 0)
                                                、
                                                @endif
                                                {{$sub_option->option_name}}
                                            @endforeach
                                        @elseif($option->option_name == '特殊問題處理')
                                            @foreach($targetUser->VvipSubOptionSpecialProblemHandling as $key => $sub_option)
                                                @if($key !== 0)
                                                、
                                                @endif
                                                {{$sub_option->option_name}}
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="ci_ddbg" style="margin-bottom: -20px;">
                            <div class="ci_lidw"><h2>尚無資料</h2></div>
                        </div>
                    @endif
                    <!-- 溫情照顧 -->

                    <!-- Daddy預算 -->
                    <div class="shou" style="border-bottom: none; margin-bottom:5px; margin-top:20px;">
                        <span style="font-size: 18px;">Daddy預算</span>
                        <font>Budget</font>
                    </div>
                    <div class="nn_zeng">
                         
                    </div>
                    <div class="metx_tab"> 
                        <div class="hdlist1_left">
                            <div class="hdlist1_left_tab xa_psirp">
                                <div class="hs_tnes">
                                    <img src="/new/images/shouru.png">
                                    <div class="she_fontetex">車馬費預算<span>income</span></div>
                                </div>
                                <a class="s_bushi" onclick="jianju_transport_fare()">檢舉</a>
                                <div class="sh_button_w">
                                    @if(!empty($targetUser->meta->transport_fare_min) && !empty($targetUser->meta->transport_fare_max) && $targetUser->meta->transport_fare_min != -1 && $targetUser->meta->transport_fare_max != -1)
                                        <span>{{round($targetUser->meta->transport_fare_min, -2)}}~{{round($targetUser->meta->transport_fare_max, -2)}}</span>
                                    @else
                                        <span>未填</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="hdlist2_right">
                            <div class="hdlist2_right_tab xa_psirp">
                                <div class="hs_tnes">
                                    <img src="/new/images/zichan_2.png">
                                    <div class="she_fontetex">每月預算<span>assets</span></div>
                                </div>
                                <a class="s_bushi" onclick="jianju_month_budget()">檢舉</a>
                                <div class="sh_button_w">
                                    @if(!empty($targetUser->meta->budget_per_month_min) && !empty($targetUser->meta->budget_per_month_max) && $targetUser->meta->budget_per_month_min != -1 && $targetUser->meta->budget_per_month_max != -1)
                                        <span>{{round($targetUser->meta->budget_per_month_min, -3)/10000}}萬~{{round($targetUser->meta->budget_per_month_max, -3)/10000}}萬</span>
                                    @else
                                        <span>未填</span>
                                    @endif
                                </div>
                            </div>
                        </div>  
                    </div>  

                    <!-- Daddy預算 -->

                    <!-- 財富資產 -->
                    <div class="shou" style="border-bottom: none; margin-bottom:5px; margin-top: 20px;">
                        <span style="font-size: 18px;">Daddy 財富資產</span>
                        <font>Wealth asset</font>
                    </div>
                    @if($assets_image->first() ?? false)
                        <div style="width: 93%; margin: 0 auto;">
                            <div class="swiper-container wip01">
                                <div class="swiper-wrapper">
                                    @foreach($assets_image as $option)
                                        <div class="swiper-slide sild">
                                            <div class="cbg_ont">
                                                <img src={{$option->option_name}}>
                                                <div class="cb_bg">
                                                    <h2>{{$option->option_remark}}</h2>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="swiper-button-next next01" style="right:15px;"></div>
                                <div class="swiper-button-prev prev01" style="left: 10px;"></div>
                            </div>
                        </div>
                    @else
                        <div class="ci_ddbg" style="margin-bottom: 20px;">
                        <div class="ci_lidw"><h2>尚無資料</h2></div>
                        </div>
                    @endif
                    <!-- 財富資產 -->

                    <!-- Daddy的品質生活 -->
                    <div class="shou" style="border-bottom: none; margin-bottom:5px; margin-top: -30px;">
                        <span style="font-size: 18px;">Daddy的品質生活</span>
                        <font>The quality of life</font>
                    </div>
                    <div class="ci_ddbg">
                        @if($quality_life_image->first() ?? false)
                            @foreach($quality_life_image as $key => $option)
                                <div class="ci_lidw @if($key % 2 == 0) left @else right @endif">
                                    <img src={{$option->option_name}} class="ci_img">
                                    <div class="ci_ifont">
                                        <div class="ci_div01">{{$option->option_second_remark}}
                                            @if($key % 2 == 0)
                                                <img src="/new/images/zb_5.png">
                                            @else
                                                <img src="/new/images/zb_6.png">
                                            @endif
                                        </div>
										<div class="ci_div02">{{$option->option_remark}}</div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="ci_lidw left">尚無資料</div>
                        @endif
                    </div>
                    <!-- Daddy的品質生活 -->

                    <!-- 關於我 -->
                    <div class="shou" style="border-bottom: none; margin-bottom:5px; margin-top:20px;">
                        <span style="font-size: 18px;">關於我</span>
                        <font>About me</font>
                    </div>
                    <div class="ci_ddbg01">
                        <div class="ci_ddbg01_b" style="border-bottom: #d2d2d2 1px solid;">{!! nl2br($targetUser->meta->about) !!}</div>
                        {{--
                        @if(!empty($vvipInfo->about))
                        <h3>{!! nl2br($vvipInfo->about) !!}</h3>
                        @endif
                        --}}
                    </div>
                    <!-- 關於我 -->

                    <!-- 期待的約會模式 -->
                    <div class="dlxbolv">
                        <div class="shou" style="border-bottom: none; margin-bottom:5px; margin-top:20px;">
                            <span style="font-size: 18px;">期待的約會模式</span>
                            <font>Dating patterns</font>
                        </div>
                        <div class="yuehuims">
                            @foreach($targetUser->VvipExpectDate as $key => $option)
                                <a href="javascript:void(0);" class="ck_wileft01 left">
                                    <img src="@if($key==0)/new/images/zb_11.png @elseif($key==1)/new/images/zb_12.png @elseif($key==2)/new/images/zb_15.png @elseif($key==3)/new/images/zb_16.png @endif" class="ck_wileft01_img">
                                    <div class="ck_biaoq">{{$option->option_name}}</div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <!-- 期待的約會模式 -->

                    <div class="ziliao_dbn">
                        <div class="shou" style="border-bottom: none; margin-bottom:5px; margin-top:20px;">
                            <span style="font-size: 18px;">基本資料</span>
                            <font>Basic information</font>
                        </div>
                        <div class="ci_ddbg">
                            <div class="zlys_aa">註冊時間 @if($user->isVip() || $user->isVVIP())<span>{{$targetUser->created_at->toDateString()}}</span>@else <span class="mtop"><img src="/new/images/icon_35.png"></span> @endif</div>
                            <div class="zlys_aa">年齡 <span>{{$targetUser->meta->age()}}</span></div>
                            <div class="zlys_aa">身高 <span>{{$targetUser->meta->height}}cm</span></div>
                            <div class="zlys_aa">婚姻  <span>{{$targetUser->meta->marriage}}</span></div>
                            <div class="zlys_aa">體型 <span>
                                    @if(!empty($targetUser->meta->body) && $targetUser->meta->body != null && $targetUser->meta->body != 'null')
                                    {{$targetUser->meta->body}}
                                    @else
                                    未填
                                    @endif
                                </span></div>
                            <div class="zlys_aa">被收藏次數 <span v-if="is_vip">
                                            <font id="be_faved_count" ref="be_faved_count">
                                                @{{be_faved}}
                                            </font>
                                        </span>
                                <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span></div>
                            <div class="zlys_aa">被封鎖次數 <span v-if="is_vip">
                                            <font id="be_blocked_other_count" ref="be_blocked_other_count">
                                                @{{be_blocked_other_count}}
                                            </font>
                                        </span>
                                <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span></div>
                            {{-- <div class="zlys_aa">是否封鎖我 <span v-if="is_vip"><font>@{{is_block_mid}}</font></span>
                                <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span></div> --}}
                            <div class="zlys_aa">是否看過我 <span v-if="is_vip"><font>@{{is_visit_mid}}</font></span>
                                <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span></div>
                        </div>
                    </div>

                    @if(($targetUser->id==$user->id) || $mood_article_lists->count())
                        <div class="shou" style="border-bottom: none; margin-bottom:5px; margin-top:20px;">
                            <span style="font-size: 18px;">心情文章</span>
                            <font>Article on Mood</font>
                            @if($targetUser->id==$user->id)
                                <a href="/mood/posts" class="c_but_01 left">
                                    <div class="c_but_1">新增<img src="/new/images/z_jih.png"></div>
                                </a>
                            @endif
                        </div>
                        @foreach($mood_article_lists as $key =>$mood_article)
                            <div @if($key<=2) class="db_but01" @else  class="db_but01 plshow showMoreArticle" style="display: none;" @endif>
                                <a href="/mood/post_detail/{{ $mood_article->id }}?from_viewuser_vvip_page=1" ontouchstart="">
                                    <div id="moodArticle_{{ $mood_article->id }}" class="db_but02">
                                        <b class="dl_font" style="word-break: break-all;">{{ $mood_article->title }}</b>
                                        <font class="m_a_created_at" style="float: right;margin-top:2px;color: #999999;">{{ substr($mood_article->created_at,0,10) }}</font>
                                        {{--<a class="db_buta" data-openLink="/mood/postsEdit/{{ $mood_article->id }}/all"><span class="db_icon"><img src="/new/images/z_bianjn.png"></span>編輯</a>--}}
                                    </div>
                                </a>
                            </div>
                        @endforeach
                        @if($mood_article_lists->count())
                            @if($mood_article_lists->count()>3)
                                <div class="hzk_but">
                                    <img src="/new/images/z_jt-down.png" onclick="test(this)">
                                </div>
                            @endif
                        @else
                            <div class="db_but01" style="box-shadow:unset;">暫無資料</div>
                        @endif
                        <!-- js 控制 展开 隐藏div -->
                        <script type="text/javascript">
                            function test(obj) {
                                $(".showMoreArticle").each(function() {
                                    if ($(this).css('display') == "block") {
                                        $(this).css('display','none');
                                        obj.src = "/new/images/z_jt-down.png";
                                    } else {
                                        $(this).css('display','block');
                                        obj.src = "/new/images/z_jt-up.png";
                                    }
                                });
                            }
                        </script>
                    @endif
                    @if($message_board_list->count())
                        <dl class="system" style="margin-top: 30px;">
                            <dt class="hypbg">
                                <span>留言記錄</span><font class="">Record of message</font>
                            </dt>
                            <dd id="showMoreMsg">
                                <ul class="hypbgul">
                                    @foreach($message_board_list as $list)
                                        <div class="jah">
                                            <a href="/MessageBoard/post_detail/{{ $list->id }}?from_viewuser_vvip_page=1">
                                                <div id="messageBoard_{{ $list->id }}" class="ly_text">
                                                    <div class="ly_text_1"><div class="ly_lfontleft">{{ $list->title }}</div><div class="ly_time">{{ date('Y-m-d', strtotime($list->created_at)) }}</div></div>
                                                    <div class="liu_text_2">{{ $list->contents }}</div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </ul>
                            </dd>
                        </dl>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    @if(isset($targetUser))
    <div class="bl bl_tab" id="show_chat_ele">
        <div class="bltitle"><span>發送給{{$targetUser->name}}</span></div>
        <div class="n_blnr01 ">

            <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="/dashboard/chat2/{{ \Carbon\Carbon::now()->timestamp }}" id="chatForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                <input type="hidden" name="userId" id="userId" value="{{$user->id}}">
                <input type="hidden" name="to" id="to" value="{{$targetUser->id}}">
                <input type="hidden" name="{{ \Carbon\Carbon::now()->timestamp }}" value="{{ \Carbon\Carbon::now()->timestamp }}">
                <textarea name="msg" id="msg" cols="" rows="" class="n_nutext" placeholder="請輸入內容" required></textarea>
                <input type="submit" class="n_bllbut msgsnd" value="發信件" style="border-style: none;">
            </form>

        </div>
        <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>

    <div class="bl_tab_aa" id="show_banned_ele" style="display: none;">
        <div class="bl_tab_bb">
            <div class="bltitle"><span style="text-align: center; float: none;">檢舉 {{$targetUser->name}}</span></div>
            <div class="new_pot new_poptk_nn new_pot001">
                <div class="fpt_pic new_po000">
                    <form id="reportPostForm" action="{{ route('reportPost') }}" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="aid" value="{{$user->id}}">
                        <input type="hidden" name="uid" value="{{$targetUser->id}}">
                        <textarea name="content" cols="" rows="" class="n_nutext" style="border-style: none;" maxlength="300" placeholder="{{$report_member}}" required></textarea>
                        <span class="alert_tip" style="color:red;"></span>
                        <input type="file" name="reportedImages">
                        <div class="new_pjckbox">
                            檢舉請盡量附上對話截圖或者可以證明的事項，以減輕站長查證的負擔哦~感謝~~
                            <span><input type="checkbox" name="agree"><label>我同意上述說明</label></span>
                        </div>
                        <div class="n_bbutton" style="margin-top:10px;">
                            <div style="display: inline-flex;">
                            <div class="n_right" onclick="reportPostForm_submit()" style="border-style: none; background: #8a9ff0; color:#ffffff; float: unset; margin-left: 0px; margin-right: 20px;">送出</div>
                            <button type="reset" class="n_left" style="border: 1px solid #8a9ff0; background: #ffffff; color:#8a9ff0; float: unset; margin-right: 0px;" onclick="show_banned_close()">返回</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <a onclick="show_banned_close()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
        </div>
    </div>
    
    @if($targetUser->engroup==1)
        <div class="bl_tab_aa" id="jianju" style="display: none;">
            <div class="bl_tab_bb">
                <div class="bltitle"><span id="jianju_title" style="text-align: center; float: none;">預算不實</span></div>
                <div class="new_pot new_poptk_nn new_pot001">
                    <div class="fpt_pic new_po000">
                        <div class="ju_pjckbox">
                            注意：檢舉預算不實一定要付上證據，例如轉帳截圖，或者對話紀錄，或其他可資證明的方式
                        </div>
                        <form id="budget_jianju_form" action="{{ route('reportPost') }}" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="aid" value="{{$user->id}}">
                            <input type="hidden" name="uid" value="{{$targetUser->id}}">
                            <input type="hidden" id="jianju_content" name="content" value="預算不實">
                            <span class="alert_tip" style="color:red;"></span>
                            <input type="file" id="budget_jianju_file" name="reportedImages">
                            <div class="n_bbutton" style="margin-top:10px;">
                                <div style="display: inline-flex;">
                                    <input type="button" onclick="budget_jianju_submit()" class="n_right" style="border-style: none; background: #8a9ff0; color:#ffffff; float: unset; margin-left: 0px; margin-right: 20px;" value="送出">
                                    <a type="reset" class="n_left" style="border: 1px solid #8a9ff0; background: #ffffff; color:#8a9ff0; float: unset; margin-right: 0px;" href="" onclick="button()">返回</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <a onclick="button()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
            </div>
        </div>         
    @endif

    <div class="bl_tab_aa reportPic_aa" id="show_reportPic" style="display: none;">
        <div class="bl_tab_bb">
            <div class="bltitle"><span>檢舉{{$targetUser->name}}</span></div>
            <div class="new_pot new_poptk_nn new_pot001 reportPic_new">
                <div class="fpt_pic new_po000 reportPic_new">
                    <form id="reportPicNextNewForm"  method="POST" action="{{ route('reportPicNextNew') }}" enctype="multipart/form-data" style="margin-bottom:20px;">
                        {!! csrf_field() !!}
                        <input type="hidden" name="aid" value="{{$user->id}}">
                        <input type="hidden" name="uid" value="{{$targetUser->id}}">
                        <input type="hidden" name="picType" value="">
                        <input type="hidden" name="pic_id" value="">
                        <textarea name="content" cols="" rows="" class="n_nutext" placeholder="{{$report_avatar}}" required></textarea>
                        <input type="file" name="images" class="reportedUserInput">
                        <div class="n_bbutton" style="margin-top:10px;text-align:center;">
                            <button type="submit" class="n_right" style="border-style: none; background: #8a9ff0; color:#ffffff;float: unset; margin-left: 0px; margin-right: 20px;">送出</button>
                            <button type="reset" class="n_left" style="border-style: none;background: #ffffff; color:#8a9ff0; float: unset; margin-right: 0px;" onclick="show_reportPic_close()">返回</button>
                        </div>
                    </form>
                </div>
            </div>
            <a onclick="show_reportPic_close()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
        </div>
    </div>

    <div class="bl bl_tab_aa" id="tab_evaluation" style="display: none;">
        <div class="bl_tab_bb">
            <div class="bltitle"><span style="text-align: center; float: none;">評價 {{$targetUser->name}}</span></div>
            <div class="new_pot new_poptk_nn">
                <div class="fpt_pic">
                    <form id="form1" action="{{ route('evaluation')."?n=".time() }}" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        {{--<div class="pj_add">
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
                        </div>--}}
                        <textarea id="content" name="content" cols="" rows="" class="n_nutext evaluation_content" style="border-style: none;" maxlength="300" placeholder="請輸入內容(至多300個字元)"></textarea>
                        <input type="hidden" name="uid" value={{$user->id}}>
                        <input type="hidden" name="eid" value={{$targetUser->id}}>
                        <span class="alert_tip" style="color:red;"></span>
                        <input type="file" name="images" >
                        <div class="new_pjckbox">
                            評價請以敘述<a class="text-danger" style="color: red;">確實發生的事實</a>為主，不要有主觀判斷，盡量附上截圖佐證。若被評價者來申訴，您又沒有附上截圖，評價在驗證屬實前會被隱藏或撤銷。
                            <span><input type="checkbox" name="agree"><label>我同意上述說明</label></span>
                        </div>
                        <div class="n_bbutton" style="margin-top:0px;">
                            <a class="n_bllbut" onclick="form_submit()">送出</a>
                        </div>
                    </form>
                </div>
            </div>
            <a onclick="tab_evaluation_close()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
        </div>
    </div>

    <div class="bl_tab_aa" id="tab_evaluation_reply" style="display: none;">
        <form id="form_evaluation_reply" action="{{ route('evaluation_re_content')."?n=".time() }}" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="id_reply" name="id" value="">
            <input type="hidden" id="eid_reply" name="eid" value="">
            <div class="bl_tab_bb">
                <div class="bltitle"><span style="text-align: center; float: none;">評價回覆</span></div>
                <div class="new_pot1 new_poptk_nn">
                    <div class="fpt_pic1">
                        <textarea id="re_content_reply" name="re_content" cols="" rows="" class="n_nutext evaluation_content" style="border-style: none;" maxlength="120" placeholder="請輸入回覆（最多120個字元）"></textarea>
                        <span class="alert_tip" style="color:red;"></span>
                        <input id="images_reply" type="file" name="images">
                        <div class="n_bbutton" style="margin-top:0px;">
                            <a class="n_bllbut" onclick="form_evaluation_reply_submit()">送出</a>
                        </div>
                    </div>
                </div>
                <a onclick="tab_evaluation_reply_close()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
            </div>
        </form>
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
                        <td class="">@if(!$isSent3Msg)<img src="/new/images/ticon_02.png">@else<img src="/new/images/ticon_01.png">@endif</td>
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
                        <td class="">@if(!$isSent3Msg)<img src="/new/images/ticon_02.png">@else<img src="/new/images/ticon_01.png">@endif</td>
                    </tr>
                </table>
            </div>
        </div>
        <a id="" onClick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>
    </div>
    @endif

@stop

@section('javascript')
<script>
    //訪問時間紀錄
    var hiddenProperty = 'hidden' in document ? 'hidden' :
        'webkitHidden' in document ? 'webkitHidden' :
        'mozHidden' in document ? 'mozHidden' :
        null;
    if (!document[hiddenProperty])
    {
        visit_time_interval = setInterval("update_visited_time(5)","5000");
    }
    var visibilityChangeEvent = hiddenProperty.replace(/hidden/i, 'visibilitychange');
    var onVisibilityChange = function()
    {
        if (!document[hiddenProperty])
        {
            visit_time_interval = setInterval("update_visited_time(5)","5000");
        }
        else
        {
            clearInterval(visit_time_interval);
        }
    }
    document.addEventListener(visibilityChangeEvent, onVisibilityChange);
    //訪問時間紀錄
</script>

<script type="application/javascript">
    let is_banned = {{ $is_banned ? 1 : 0 }};
    let is_warned = {{ $user->isAdminWarned() ? 1 : 0 }};
    function jidutiao() {
        c5('此會員使用紀錄不足，無法判斷');
    }

    function isEllipsisActive(e) {
        console.log('$(e).attr("class")='+$(e).attr("class"));
        console.log('$(e).innerHeight()='+$(e).innerHeight());
        console.log(' $(e)[0].scrollHeight='+ $(e)[0].scrollHeight);
        return (Math.ceil($(e).innerHeight()==undefined?0:$(e).innerHeight()) < ($(e)[0].scrollHeight==undefined?0:$(e)[0].scrollHeight));
    }

    function show_banned() {

        if(is_banned){
            return  c5('您目前被站方封鎖，無檢舉權限');
        }

        //$(".blbg").show();
        var uid='{{ $user->id }}';
        var to='{{$targetUser->id}}';
        if(uid != to){
            $(".announce_bg").show();
            $("#show_banned_ele").show();
            $('body').css("overflow", "hidden");
        }else{
            c5('不可檢舉自己');
        }
    }

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
            content: function () { return '<div>' + $(this).data('content') + '</div>'; }
        // })
        //     .click(function(e) {
        //     e.preventDefault();
        //     $(this).popover('toggle');
        });


    function show_chat() {
        //$(".blbg").show();
        var uid='{{ $user->id }}';
        var to='{{$targetUser->id}}';
        if(uid != to){
            $(".announce_bg").show();
            $("#show_chat_ele").show();
        }else{
            c5('不可發信給自己');
        }
    }

    function messenge_show_block(){
        c5('封鎖中無法發信');
    }

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
            while (i < cookieLen) {
                var j = i + nameLen;
                if (document.cookie.substring(i, j) == arg) return getCookieValueByIndex(j);
                i = document.cookie.indexOf(" ", i) + 1;
                if (i == 0) break;
            }
            return null;
        }

        function delete_cookie(name) {
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

        function htmlencode(s) {
            //console.log('count6');
            var div = document.createElement('div');
            div.appendChild(document.createTextNode(s));
            return div.innerHTML;
        }

        function htmldecode(s) {
            //console.log('count7');
            var div = document.createElement('div');
            div.innerHTML = s;
            return div.innerText || div.textContent;
        }

        /*取得次數*/
        //console.log('count8');
        var count = getCookie('count');
        if (count == undefined) {
            count = 0;
        }
        /*取得現在時間*/
        var today = new Date();
        var now = today.getFullYear() + '/' + (today.getMonth() + 1) + '/' + today.getDate() + ' ' + today.getHours() + ':' + today.getMinutes() + ':' + today.getSeconds();
        // console.log(now);
        // var now       = 20191216215141;
        /*取得紀錄時間*/
        var countTime = getCookie('countTime');
        console.log(countTime);
        if (countTime == undefined) {
            countTime = now;
        }
        $(document).ready(function () {
            var bodyMain = document.getElementById('msg');
            if (GetDateDiff(countTime, now, "minute") > "{{$timeSet}}") {
                //console.log('count10');
                delete_cookie('count');
                delete_cookie('countTime');
            }
            if (GetDateDiff(countTime, now, "minute") <= "{{$timeSet}}") {
                //console.log('count11');
                if (count >= {{$countSet}}) {
                    // console.log('count12');
                    console.log("countM: {{$countSet}}");
                    //禁止複製
                    bodyMain.oncopy = function () {
                        return false;
                    }
                    //禁止貼上
                    bodyMain.onpaste = function () {
                        return false;
                    }
                } else {
                    // console.log('count13');
                    doCookieSetup('countTime', now);
                    bodyMain.onpaste = function () {
                        count++;
                        console.log("countTime: " + count);
                        doCookieSetup('count', count);
                    }
                }
            }
        });
        @endif

        @if(isset($targetUser))
        $(".but_block").on('click', function () {
            let uid = '{{ $user->id }}';
            let to = '{{$targetUser->id}}';
            if (uid != to) {
                $.post('{{ route('postBlockAJAX') }}', {
                    uid: uid,
                    sid: to,
                    _token: '{{ csrf_token() }}'
                }, function (data) {
                    // if(data.save=='ok') {
                    $("#tab_block").hide();
                    // $(".blbg").hide();
                    show_pop_message('封鎖成功');
                    // }
                });
            } else {
                c5('不可封鎖自己');
            }
        });


        $('.unblock').on('click', function () {
            c4('確定要解除封鎖嗎?');
            var uid = '{{ $user->id }}';
            var to = '{{$targetUser->id}}';
            if (uid != to) {
                $(".n_left").on('click', function () {
                    $.post('{{ route('unblockAJAX') }}', {
                        uid: uid,
                        to: to,
                        _token: '{{ csrf_token() }}'
                    }, function (data) {
                        $("#tab04").hide();
                        show_pop_message('已解除封鎖');
                    });
                });
            } else {
                c5('不可解除封鎖自己');
            }
        });

        $(".favIcon").on('click', function () {
            if ($(this).hasClass('removeFav')) {
                removeFav();
            } else {
                addFav();
            }
        });

        function addFav() {
            var uid = '{{ $user->id }}';
            var to = '{{$targetUser->id}}';
            if (uid != to) {
                $.post('{{ route('postfavAJAX') }}', {
                    uid: uid,
                    to: to,
                    _token: '{{ csrf_token() }}'
                }, function (data) {
                    var is_success = false;
                    if (data.save == 'ok') {
                        is_success = true;
                        c5('收藏成功');
                    } else if (data.save == 'error') {
                        c5('收藏失敗');
                    } else if (data.isBlocked) {
                        c5('封鎖中無法收藏');
                    } else if (data.isFav) {
                        is_success = true;
                        c5('已在收藏名單中');
                    }
                    if (is_success) {
                        $(".favIcon font").text('移除收藏');
                        $(".favIcon img").attr('src', '/new/images/icon_08_.png');
                        $(".favIcon").removeClass('addFav').addClass('removeFav');
                    }
                });
            } else {
                c5('不可收藏自己');
            }

        }

        function removeFav() {
            var uid = '{{ $user->id }}';
            var to = '{{$targetUser->id}}';
            $.post('{{ route('fav/remove_ajax') }}', {
                userId: uid,
                favUserId: to,
                _token: '{{ csrf_token() }}'
            }, function (data) {
                if (data.status == true) {
                    c5('移除成功');
                    $(".favIcon font").text('立即收藏');
                    $(".favIcon img").attr('src', '/new/images/zb_4.png');
                    $(".favIcon").removeClass('removeFav').addClass('addFav');
                } else {
                    c5('移除失敗');
                }

            });

        }

        $("#msgsnd").on('click', function () {

            $.ajax({
                url: '/dashboard/chat2/{{ Carbon\Carbon::now()->timestamp }}?{{csrf_token()}}={{now()->timestamp}}',
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    userId: $("#userId").val(),
                    to: $("#to").val(),
                    msg: $("#msg").val(),
                    {{ \Carbon\Carbon::now()->timestamp }} : "{{ \Carbon\Carbon::now()->timestamp }}"
                },
                success: function (response) {
                    window.location.reload();
                }
            });
        });
        @endif

        @if (Session::has('message') && Session::get('message')=="此用戶已關閉資料。")
        // ccc('{{Session::get('message')}}');
            @php
                session()->forget('message');
            @endphp
        @elseif(Session::has('message') && Session::get('message')!="此用戶已關閉資料。")
            @if (is_array(Session::get('message')))
                c5('{{ implode("", Session::get('message')) }}');
                @php
                    \Sentry\captureMessage(implode("", \Session::get('message')));
                @endphp
            @else
                c5('{{Session::get('message')}}');
            @endif
            @if(Session::get('message') == '評價已完成')
            popEvaluation()
            @endif
            @php
                session()->forget('message');
            @endphp
        @endif

        $(".n_bllbut_tab_other").on('click', function () {
            $('#tab_other').hide();
            if (document.referrer != "") {
                window.history.back();
            } else {
                location.href = '/dashboard/search';
            }
        });
    });
</script>

<script>
    const vm = new Vue({
            el: '#app',
            data () {
                return {
                    "is_vip": "{{($user->isVip() || $user->isVVIP())}}",
                    "faved":"loading...",
                    "be_faved":"loading...",
                    "blocked_other_count":"loading...",
                    "be_blocked_other_count":"loading...",

                    'login_times_per_week':'loading...',
                    'tip_count':'loading...',
                    /* 'is_vip' => 0, */
                    'is_block_mid':'loading...',
                    'is_visit_mid':'loading...',
                    'visit_other_count':'loading...',
                    'visit_other_count_7':'loading...',
                    'be_visit_other_count':'loading...',
                    'be_visit_other_count_7':'loading...',
                    'message_count':'loading...',
                    'message_count_7':'loading...',
                    'message_reply_count':'loading...',
                    'message_reply_count_7':'loading...',
                    'message_percent_7':'loading...',
                    'is_banned':'loading...',
                    'userHideOnlinePayStatus':'loading...',
                    'last_login':'loading...'
                }
            },
        mounted () {
            const uid = window.location.pathname.split('/').pop();
                axios
                .post('/getHideData', {uid, uid})
                .then(response => {
                    let data = response.data;
                    this.be_visit_other_count = data.be_visit_other_count;
                    this.be_visit_other_count_7 = data.be_visit_other_count_7;
                    this.is_banned = data.is_banned;
                    this.is_block_mid = data.is_block_mid;
                    this.is_visit_mid = data.is_visit_mid;
                    this.last_login = data.last_login.substring(0, 10);
                    this.login_times_per_week = data.login_times_per_week;
                    this.message_count = data.message_count;
                    this.message_count_7 = data.message_count_7;
                    this.message_percent_7 = data.message_percent_7;
                    this.message_reply_count = data.message_reply_count;
                    this.message_reply_count_7 = data.message_reply_count_7;
                    this.tip_count = data.tip_count;
                    this.userHideOnlinePayStatus = data.userHideOnlinePayStatus;
                    this.visit_other_count = data.visit_other_count;
                    this.visit_other_count_7 = data.visit_other_count_7;
                   
                })
                .catch(function (error) { // 请求失败处理
                    console.log(error);
                });
       

                axios
                .post('/getFavCount', {uid:uid})
                .then(response => {
                    let data = response.data;
                    this.be_faved = data.be_fav_count;
                    this.faved = data.fav_count;
                })
                .catch(function (error) { // 请求失败处理
                    console.log(error);
                });
   
                axios
                .post('/getBlockUser',{uid:uid})

                .then(response => {
                    let data = response.data;
                    this.be_blocked_other_count = data.be_blocked_other_count;
                    this.blocked_other_count = data.blocked_other_count;
                })
                .catch(function (error) { // 请求失败处理
                    console.log(error);
                });
        }
        });
</script>

<script>
    //訪問時間紀錄
    function update_visited_time(second){
        view_user_visited_id = {{$visited_id}};
        if(view_user_visited_id != 0)
        {
            $.ajax({
                type:'post',
                url:'{{route("update_visited_time")}}',
                data:
                {
                    _token: '{{ csrf_token() }}',
                    view_user_visited_id:view_user_visited_id,
                    stay_second:second
                }
            });
        }
    }
    //訪問時間紀錄
</script>

<script>
    function jianju_transport_fare() {
        if(is_banned)
        {
            return c5('您目前被站方封鎖，無檢舉權限');
        }
        if(is_warned)
        {
            return c5('您目前被站方警示，無檢舉權限');
        }
        @if($user->id != $targetUser->id)
            $("#jianju_title").text('車馬費預算不實');
            $("#jianju_content").val('車馬費預算不實');
            $(".blbg").show()
            $("#jianju").show()
            $('body').css("overflow", "hidden")
        @else
            c5('不可檢舉自己');
        @endif
    }

    function jianju_month_budget() {
        if(is_banned)
        {
            return c5('您目前被站方封鎖，無檢舉權限');
        }
        if(is_warned)
        {
            return c5('您目前被站方警示，無檢舉權限');
        }
        @if($user->id != $targetUser->id)
            $("#jianju_title").text('每月預算不實');
            $("#jianju_content").val('每月預算不實');
            $(".blbg").show()
            $("#jianju").show()
            $('body').css("overflow", "hidden")
        @else
            c5('不可檢舉自己');
        @endif
    }

    function budget_jianju_submit() {
        var fileInput = $('#budget_jianju_file').get(0).files[0];
        if(fileInput)
        {
            button();
		    $("#budget_jianju_form").submit();
        }
        else
        {
            button();
            c5("檢舉預算不實一定要附上證據，例如轉帳截圖、對話記錄等");
        }
    }

    function button() {
        $(".blbg").hide()
        $("#jianju").hide()
        $(".announce_bg").hide();
        $('body').css("overflow", "auto");
    }
</script>
<script src="/new/js/swiper.min.js"></script>
<script>
    var swiper = new Swiper('.wip01', {
        slidesPerView:3,
        spaceBetween:10,
        // slidesPerGroup:3,
        // loop: true,
        // loopFillGroupWithBlank: true,
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        // init: false,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            450: {
                slidesPerView:2,
                spaceBetween:10,
            }
        }
    });
</script>

<link href="{{ asset('css/jquery.fileuploader.min.css') }}" media="all" rel="stylesheet">
<link href="{{ asset('new/css/fileupload.css') }}" media="all" rel="stylesheet">
<link href="{{ asset('css/font/font-fileuploader.css') }}" media="all" rel="stylesheet">
<script src="{{ asset('js/jquery.fileuploader.js') }}" type="text/javascript"></script>
<script src="{{ asset('new/js/heic2any.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('new/js/resize_before_upload.js') }}" type="text/javascript"></script>
<style>

#form1 ul.fileuploader-items-list {
    margin-bottom: -3px;
}

#form1 .fileuploader-item,
#form1 .fileuploader-thumbnails-input {
    margin-bottom: 20px;
}

#form1 .fileuploader-item::after,
#form1 .fileuploader-thumbnails-input::after {
    content: attr(data-nth-text);
    display: block;
    position: absolute;
    left: 0;
    right: 0;
    bottom: -24px;
    height: 20px;
    line-height: 20px;
    color: #555;
    font-size: 14px;
    text-align: center;
    letter-spacing: 0.1em;
}
</style>

<script type="application/javascript">
    $(document).ready(function () {  
        var images_uploader_options = {
            //extensions: ['jpg', 'png', 'jpeg', 'bmp'],
            changeInput: ' ',
            theme: 'thumbnails',
            enableApi: true,
            addMore: true,
            limit: 5,
            thumbnails: {
                box: '<div class="fileuploader-items">' +
                    '<ul class="fileuploader-items-list">' +
                    '<li class="fileuploader-thumbnails-input"><div class="fileuploader-thumbnails-input-inner" style="background: url({{ asset("new/images/addpic.png") }}); background-size:100%"></div></li>' +
                    '</ul>' +
                    '</div>',
                item: '<li class="fileuploader-item">' +
                    '<div class="fileuploader-item-inner">' +
                    '<div class="type-holder">${extension}</div>' +
                    '<div class="actions-holder">' +
                    '<button type="button" class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="fileuploader-icon-remove"></i></button>' +
                    '</div>' +
                    '<div class="thumbnail-holder">' +
                    '${image}' +
                    '<span class="fileuploader-action-popup"></span>' +
                    '</div>' +
                    '<div class="content-holder"><h5>${name}</h5><span>${size2}</span></div>' +
                    '<div class="progress-holder">${progressBar}</div>' +
                    '</div>' +
                    '</li>',
                item2: '<li class="fileuploader-item">' +
                    '<div class="fileuploader-item-inner">' +
                    '<div class="type-holder">${extension}</div>' +
                    '<div class="actions-holder">' +
                    '<a href="${file}" class="fileuploader-action fileuploader-action-download" title="${captions.download}" download><i class="fileuploader-icon-download"></i></a>' +
                    '<button type="button" class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="fileuploader-icon-remove"></i></button>' +
                    '</div>' +
                    '<div class="thumbnail-holder">' +
                    '${image}' +
                    '<span class="fileuploader-action-popup"></span>' +
                    '</div>' +
                    '<div class="content-holder"><h5 title="${name}">${name}</h5><span>${size2}</span></div>' +
                    '<div class="progress-holder">${progressBar}</div>' +
                    '</div>' +
                    '</li>',
                startImageRenderer: true,
                canvasImage: false,
                _selectors: {
                    list: '.fileuploader-items-list',
                    item: '.fileuploader-item',
                    start: '.fileuploader-action-start',
                    retry: '.fileuploader-action-retry',
                    remove: '.fileuploader-action-remove'
                },
                onItemShow: function(item, listEl, parentEl, newInputEl, inputEl) {
                    var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                        api = $.fileuploader.getInstance(inputEl.get(0));

                    plusInput.insertAfter(item.html)[api.getOptions().limit && api.getChoosedFiles().length >= api.getOptions().limit ? 'hide' : 'show']();

                    if(item.format == 'image') {
                        item.html.find('.fileuploader-item-icon').hide();
                    }

                    if (api.getListEl().length > 0) {
                        $('.fileuploader-thumbnails-input-inner').css('background-image', 'url({{ asset("new/images/addpic.png") }})');
                    }

                    rendorItemNthText(parentEl);
                },
                onItemRemove: function(html, listEl, parentEl, newInputEl, inputEl) {
                    var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                        api = $.fileuploader.getInstance(inputEl.get(0));

                    html.children().animate({'opacity': 0}, 200, function() {
                        html.remove();

                        if (api.getOptions().limit && api.getChoosedFiles().length - 1 < api.getOptions().limit) {
                            plusInput.show();
                        }

                        setTimeout(() => rendorItemNthText(parentEl), 100);
                    });

                    if (api.getFiles().length == 1) {
                        $('.fileuploader-thumbnails-input-inner').css('background-image', 'url({{ asset("new/images/addpic.png") }})');
                    }
                }
            },
            dialogs: {
                alert:function(message) {
                    alert(message);
                }
            },
            dragDrop: {
                container: '.fileuploader-thumbnails-input'
            },
            afterRender: function(listEl, parentEl, newInputEl, inputEl) {
                var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                    api = $.fileuploader.getInstance(inputEl.get(0));

                plusInput.on('click', function() {
                    api.open();
                });

                api.getOptions().dragDrop.container = plusInput;
                rendorItemNthText(parentEl);
            },
            editor: {
                cropper: {
                    showGrid: true,
                },
            },
            captions: {
                confirm: '確認',
                cancel: '取消',
                name: '檔案名稱',
                type: '類型',
                size: '容量',
                dimensions: '尺寸',
                duration: '持續時間',
                crop: '裁切',
                rotate: '旋轉',
                sort: '分類',
                download: '下載',
                remove: '刪除',
                drop: '拖曳至此上傳檔案',
                open: '打開',
                removeConfirmation: '確認要刪除檔案嗎?',
                errors: {
                    filesLimit: function(options) {
                        return '最多上傳 ${limit} 張圖片'
                    },
                    filesType: '檔名: ${name} 不支援此格式, 只允許 ${extensions} 檔案類型上傳.',
                    fileSize: '${name} 檔案太大, 請確認容量需小於 ${fileMaxSize}MB.',
                    filesSizeAll: '上傳的所有檔案過大, 請確認未超過 ${maxSize} MB.',
                    fileName: '${name} 已有選取相同名稱的檔案.',
                }
            }
        };
        images_uploader=$('input[name="images"]:not(.reportedUserInput)').fileuploader(images_uploader_options);

        resize_before_upload(images_uploader,1200,1800,'#tab_evaluation,#tab_evaluation_reply');
        var reportedImages_options = images_uploader_options;
        reportedImages_options.limit = 15;
        
        reportedImages_uploader = $('input[name="reportedImages"],input.reportedUserInput').fileuploader(reportedImages_options);
        resize_before_upload(reportedImages_uploader,1200,1800,'#show_banned_ele,#show_reportPic');
        $(".announce_bg").on("click", function() {
            $('.bl_tab_aa').hide();
            $('body').css("overflow", "auto");
        });

    });

    /**
     * @param {jQuery} parentEl
     * @returns {void}
     */
    function resetImageUploader(form) {
        const uploader = $.fileuploader.getInstance($(form).find('input[type="file"]'));

        if (uploader && !uploader.isEmpty()) {
            uploader.reset();
            rendorItemNthText(uploader.getParentEl());
        }
    }

    /**
     * @param {jQuery} parentEl
     * @returns {void}
     */
    function rendorItemNthText(parentEl) {
        parentEl.find('.fileuploader-item, .fileuploader-thumbnails-input').each(function (i) {
            let nthText = rendorItemNthText.nthEnum[i] || 'N';

            this.setAttribute('data-nth-text', `第${nthText}張`);
        });
    }

    rendorItemNthText.nthEnum = '一二三四五六七八九十'.split('');
</script>

<script type="text/javascript">
    $(function() {
        $(".system dd").hide();
        $(".system dt").click(function() {

        });
    })

    $('.system dt').click(function(e) {
        $(this).toggleClass('on');
        $(this).next('dd').slideToggle();
    });
    $( document ).ready(function() {

        var position ='{{ session()->get('viewuser_vvip_page_position')}}';
        if(position !=''){
            if (position.indexOf("moodArticle") >= 0){
                if($('#'+position).is(":hidden")){
                    test($('#'+position));
                    $('.hzk_but img').attr('src',"/new/images/z_jt-up.png");
                }
                $("html,body").animate({scrollTop: $('#'+position).offset().top}, 1000);

            }else if (position.indexOf("messageBoard") >= 0){
                $('.hypbg').addClass('on');
                $('#showMoreMsg').show();
            }
        }
    });
</script>

@stop
