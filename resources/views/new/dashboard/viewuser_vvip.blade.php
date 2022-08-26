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
    </style>
@endsection
@section('app-content')

{{--    @php--}}
{{--        $isBlurAvatar = \App\Services\UserService::isBlurAvatar($to, $user);--}}
{{--        $isBlurLifePhoto = \App\Services\UserService::isBlurLifePhoto($to, $user);--}}
{{--    @endphp--}}
    <div id="app">
    <div class="container matop80">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="v_bg_vv">
                    @if(Request()->get('page_mode')=='edit')
                        <a href="{!! url('dashboard') !!}" class="zh_shed" style="z-index: 6;"></a>
                    @endif
                    <div class="v_tx">
                        <div class="c_toux"><img src="@if(file_exists( public_path().$to->meta->pic ) && $to->meta->pic != ""){{$to->meta->pic}} @elseif($to->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
                        <div class="c_tfontright">
                            <h1><span>{{$to->name}}</span><span class="ci_tub"></span></h1>
                        </div>
                    </div>
                    <div class="ci_jianjie"><span>{!! nl2br($to->title)!!}</span></div>
                    <div class="ci_beij" style="margin-top:35px;">
                        <div class="ci_beij_1">
                            <div class="ci_kuang">
                                <div class="ci_bfont">Daddy背景</div><img src="/new/images/zb_7.png" class="ci_imgf">
                            </div>
                            <div class="ci_kborder">
                                <div class="cl_liswidt">
                                    @if(isset($vvipInfo) && !empty($user->VvipPointInfos))
                                        @foreach($user->VvipPointInfos as $key => $value)
                                            <li class="c_mr6 ">
                                                <div class="c_hlist01">
                                                    <div class="c_hlist02 c_pr6">
                                                        <div class="c_hfont01">{{ $value->option_name }}</div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @endif
                                </div>
                            </div>


                            <div class="ci_kuang" style="margin-top: 15px;">
                                <div class="ci_bfont">Daddy特色</div><img src="/new/images/zb_7.png" class="ci_imgf">
                            </div>
                            <div class="ci_kborder">
                                <div class="cl_liswidt">
                                    @if(isset($vvipInfo) && !empty($user->VvipDateTrend))
                                        @foreach($user->VvipDateTrend as $key => $value)
                                            <li class="c_mr6 ">
                                                <div class="c_hlist01">
                                                    <div class="c_hlist02 c_pr6">
                                                        <div class="c_hfont01">{{ $value->option_name }}</div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="c_button">

                        <a @if($to->id==$user->id)onclick="show_chat()" @else @if($isBlocked) onclick="messenge_show_block()" @else href="/dashboard/chat2/chatShow/{{ $to->id }}?from_viewuser_page=1" @endif @endif class="c_but01 left">
                            <div class="c_but01_1"><font>發訊互動</font><span><img src="/new/images/zb_3.png"></span></div>
                        </a>

                        @if($user->isVip() || $user->isVVIP())
                            @php
                                $isFav = \App\Models\MemberFav::where('member_id', $user->id)->where('member_fav_id',$to->id)->count();
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
                    @if(isset($vvipInfo) && !empty(json_decode($vvipInfo->assets, true)))
                    <div class="nn_zeng">
                        @foreach( json_decode($vvipInfo->assets, true) as $key => $value)
                            <div class="nn_listleft @if($key==0)left @elseif($key==1)right @elseif($key==2)left matop25 @elseif($key==3)right matop25 @endif">
                                <div class="nn_li_01"><span>{{$value[0]}}</span></div>
                                @if(!empty($value[1]))
                                    <div class="nn_li_02 @if($value[0]=="企業家") append_title @endif">
                                        @if(is_array($value[1]) && !empty($value[1]))
                                            @foreach($value[1] as $key2 => $value2)
                                                @if(is_array($value2) && !empty($value2[0]))
                                                    @if(!is_numeric($value2[0])){{trim(explode("：", $value2[0])[0])}}
                                                    @else{{$value2[0]}}
                                                    @endif
                                                        @if(!empty($value2[1]))
                                                        {{$value2[1]}}
                                                        @if(is_numeric($value2[1]))%@endif
                                                        @if(!$loop->last)、@endif
                                                        @endif
                                                @else
                                                    @if(!is_array($value2))
                                                    {{$value2}}
                                                    @endif
                                                        @if(!$loop->last)、@endif
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @else
                        <div class="ci_ddbg" style="margin-bottom: -20px;">
                            <div class="ci_lidw"><h2>尚無資料</h2></div>
                        </div>
                    @endif

                    <!--  -->
                    <!-- 溫情照顧 -->
                    <div class="shou" style="border-bottom: none; margin-bottom:5px; margin-top:20px;">
                        <span style="font-size: 18px;">Daddy溫情照顧</span>
                        <font>Warm care</font>
                    </div>
                    @if(isset($vvipInfo) && !empty($user->VvipExtraCares))
                    <div class="nn_zeng">
                            @foreach( $user->VvipExtraCares as $key => $value)
                                <div class="nzhaog @if($key != 0)matop13 @endif">
                                    <div class="zhg_but"><span>{{ $value->option_name }}</span></div>
                                    @if(!empty($value->SubOptions))
                                    <div class="zh_text">
                                        @if($value->SubOptions)
                                            @foreach($value->SubOptions as $key2 => $value2)
                                                {{ $value2->option_name }}
                                                @if(!$loop->last)、@endif
                                            @endforeach
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                    </div>
                    @else
                        <div class="ci_ddbg" style="margin-bottom: -20px;">
                            <div class="ci_lidw"><h2>尚無資料</h2></div>
                        </div>
                    @endif

                    <!--  -->

                    <!-- 財富資產 -->
                    <div class="shou" style="border-bottom: none; margin-bottom:5px; margin-top: 20px;">
                        <span style="font-size: 18px;">Daddy 財富資產</span>
                        <font>Wealth asset</font>
                    </div>
                    @if(isset($vvipInfo) && !empty(json_decode($vvipInfo->assets_image, true)))
                    <div style="width: 93%; margin: 0 auto;">

                        <div class="swiper-container wip01">
                            <div class="swiper-wrapper">

                                    @foreach( json_decode($vvipInfo->assets_image, true) as $key => $value)
                                        <div class="swiper-slide sild">
                                            <div class="cbg_ont">
                                                @if(is_array($value[1]) && isset($value[1][0]) && !is_null($value[1][0]))
                                                    <img src="{{$value[1][0]}}">
                                                @else
                                                    <img src="/new/images/zb_17.png">
                                                @endif
                                                <div class="cb_bg">
                                                    <h2>{{$value[0]}}</h2>
{{--                                                    <h3>帶著寶貝到處旅遊</h3>--}}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                        <div class="swiper-button-next next01" style="right:15px;"></div>
                                        <div class="swiper-button-prev prev01" style="left: 10px;"></div>
                            </div>
                        </div>
                    </div>
                    @else
                        <div class="ci_ddbg" style="margin-bottom: 20px;">
                        <div class="ci_lidw"><h2>尚無資料</h2></div>
                        </div>
                    @endif


                    <!-- Daddy的品質生活 -->
                    <div class="shou" style="border-bottom: none; margin-bottom:5px; margin-top: -30px;">
                        <span style="font-size: 18px;">Daddy的品質生活</span>
                        <font>The quality of life</font>
                    </div>
                    <div class="ci_ddbg">
                        @if(isset($vvipInfo) && !empty(json_decode($vvipInfo->life, true)))
                            @foreach( json_decode($vvipInfo->life, true) as $key => $value)
                                <div class="ci_lidw @if($key==0)left @elseif($key==1)right @endif">
                                    @if(is_array($value[1]) && isset($value[1][0]) && !is_null($value[1][0]))
                                        <img src="{{$value[1][0]}}" class="ci_img">
                                    @else
                                        <img src="@if($key==0)/new/images/zb_9.png @else/new/images/zb_10.png @endif" class="ci_img">
                                    @endif

{{--                                    <img src="/new/images/zb_9.png" class="ci_img">--}}
                                    <div class="ci_ifont">
                                        <div class="ci_div01">{{$value[0]}}</div>
{{--                                        <div class="ci_div01">品尝美酒<img src="/new/images/zb_5.png"></div>--}}
{{--                                        <div class="ci_div02">一个人酿出怎么的酒 ，取决于他的品味</div>--}}
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="ci_lidw left">尚無資料</div>
                        @endif
{{--                        <div class="ci_lidw left">--}}
{{--                            <img src="/new/images/zb_9.png" class="ci_img">--}}
{{--                            <div class="ci_ifont">--}}
{{--                                <div class="ci_div01">品尝美酒<img src="/new/images/zb_5.png"></div>--}}
{{--                                <div class="ci_div02">一个人酿出怎么的酒 ，取决于他的品味</div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="ci_lidw right">--}}
{{--                            <img src="/new/images/zb_10.png" class="ci_img">--}}
{{--                            <div class="ci_ifont">--}}
{{--                                <div class="ci_div01">名表典藏<img src="/new/images/zb_6.png"></div>--}}
{{--                                <div class="ci_div02">人生的第一信念就是准时</div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                    <!--  -->

                    <div class="shou" style="border-bottom: none; margin-bottom:5px; margin-top:20px;">
                        <span style="font-size: 18px;">關於我</span>
                        <font>About me</font>
                    </div>
                    <div class="ci_ddbg01">
                        <div class="ci_ddbg01_b" style="border-bottom: #d2d2d2 1px solid;">Daddy期許的包養條件，請客服了解客人需求後，讓寫手潤稿</div>
                        @if(!empty($vvipInfo->about))
                        <h3>{!! nl2br($vvipInfo->about) !!}</h3>
                        @endif
{{--                        <h3>妳好，我叫Darren，-家位於彰化製造業的經理。</h3>--}}
{{--                        <h3>看到這裡或許你會想「彰化的製造業」?那有保障嗎?能幫忙甜心實現夢想嗎?</h3>--}}
{{--                        <h3>但你知道彰化是水五金的大宗嗎?年產值超過500多億元。</h3>--}}
{{--                        <h3>我不知道其他男生是怎麼認證的,但我對於夢想網對於金牌老爹要求的財力證明,我是直接提出超過三千萬的存款作為依據。</h3>--}}
{{--                        <h3>我40歲、身高170以,中等身材,貨真實且保養得宜的Daddy，歡迎符合我所需條件的「妳」，勇敢地來認識我。</h3>--}}
                    </div>
                    <!--  -->
                    <div class="dlxbolv">

                        <div class="shou" style="border-bottom: none; margin-bottom:5px; margin-top:20px;">
                            <span style="font-size: 18px;">期待的約會模式</span>
                            <font>Dating patterns</font>
                        </div>
                        <div class="yuehuims">
                            @if(isset($vvipInfo) && !empty(json_decode($vvipInfo->date_expect, true)))
                                @foreach( json_decode($vvipInfo->date_expect, true) as $key => $value)
                                    <a href="javascript:void(0);" class="ck_wileft01 left">
                                        <img src="@if($key==0)/new/images/zb_11.png @elseif($key==1)/new/images/zb_12.png @elseif($key==2)/new/images/zb_15.png @elseif($key==3)/new/images/zb_16.png @endif" class="ck_wileft01_img">
                                        <div class="ck_biaoq">{{$value[0]}}</div>
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <!--  -->
                    <div class="ziliao_dbn">
                        <div class="shou" style="border-bottom: none; margin-bottom:5px; margin-top:20px;">
                            <span style="font-size: 18px;">基本資料</span>
                            <font>Basic information</font>
                        </div>
                        <div class="ci_ddbg">
                            <div class="zlys_aa">註冊時間 @if($user->isVip() || $user->isVVIP())<font>{{substr($to->created_at,0,10)}}</font>@else <span class="mtop"><img src="/new/images/icon_35.png"></span> @endif</div>
                            <div class="xzl_left">年齡 <span>{{$to->meta->age()}}</span></div>
                            <div class="xzl_left">身高 <span>{{$to->meta->height}}</span></div>
                            <div class="xzl_left">包月預算 <span>
                                    @if(!empty($to->meta->budget_per_month_min) && !empty($to->meta->budget_per_month_max) && $to->meta->budget_per_month_min != -1 && $to->meta->budget_per_month_max != -1)
                                    {{round($to->meta->budget_per_month_min, -3)/10000}}萬~{{round($to->meta->budget_per_month_max, -3)/10000}}萬
                                    @else
                                    未填
                                    @endif
                                </span></div>
                            <div class="xzl_left">車馬費預算 <span>
                                    @if(!empty($to->meta->transport_fare_min) && !empty($to->meta->transport_fare_max) && $to->meta->transport_fare_min != -1 && $to->meta->transport_fare_max != -1)
                                    {{round($to->meta->transport_fare_min, -2)}}~{{round($to->meta->transport_fare_max, -2)}}
                                    @else
                                    未填
                                    @endif
                                </span></div>
                            <div class="xzl_left">婚姻  <span>{{$to->meta->marriage}}</span></div>
                            <div class="xzl_left">體型 <span>
                                    @if(!empty($to->meta->body) && $to->meta->body != null && $to->meta->body != 'null')
                                    {{$to->meta->body}}
                                    @else
                                    未填
                                    @endif
                                </span></div>
                            <div class="xzl_left">被收藏次數 <span v-if="is_vip">
                                            <font id="be_faved_count" ref="be_faved_count">
                                                @{{be_faved}}
                                            </font>
                                        </span>
                                <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span></div>
                            <div class="xzl_left">被封鎖次數 <span v-if="is_vip">
                                            <font id="be_blocked_other_count" ref="be_blocked_other_count">
                                                @{{be_blocked_other_count}}
                                            </font>
                                        </span>
                                <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span></div>
                            <div class="xzl_left">是否封鎖我 <span v-if="is_vip"><font>@{{is_block_mid}}</font></span>
                                <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span></div>
                            <div class="xzl_left">是否看過我 <span v-if="is_vip"><font>@{{is_visit_mid}}</font></span>
                                <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span></div>
                        </div>
                    </div>
                    <!--  -->
                </div>

            </div>
        </div>
    </div>
    
    @if(isset($to))
    <div class="bl bl_tab" id="show_chat_ele">
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

    <div class="bl_tab_aa" id="show_banned_ele" style="display: none;">
        <div class="bl_tab_bb">
            <div class="bltitle"><span style="text-align: center; float: none;">檢舉 {{$to->name}}</span></div>
            <div class="new_pot new_poptk_nn new_pot001">
                <div class="fpt_pic new_po000">
                    <form id="reportPostForm" action="{{ route('reportPost') }}" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="aid" value="{{$user->id}}">
                        <input type="hidden" name="uid" value="{{$to->id}}">
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
    
    @if($to->engroup==1)
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
                            <input type="hidden" name="uid" value="{{$to->id}}">
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
            <div class="bltitle"><span>檢舉{{$to->name}}</span></div>
            <div class="new_pot new_poptk_nn new_pot001 reportPic_new">
                <div class="fpt_pic new_po000 reportPic_new">
                    <form id="reportPicNextNewForm"  method="POST" action="{{ route('reportPicNextNew') }}" enctype="multipart/form-data" style="margin-bottom:20px;">
                        {!! csrf_field() !!}
                        <input type="hidden" name="aid" value="{{$user->id}}">
                        <input type="hidden" name="uid" value="{{$to->id}}">
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
            <div class="bltitle"><span style="text-align: center; float: none;">評價 {{$to->name}}</span></div>
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
                        <input type="hidden" name="eid" value={{$to->id}}>
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
        var to='{{$to->id}}';
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
        var to='{{$to->id}}';
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

        @if(isset($to))
        $(".but_block").on('click', function () {
            let uid = '{{ $user->id }}';
            let to = '{{$to->id}}';
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
            var to = '{{$to->id}}';
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
            var to = '{{$to->id}}';
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
            var to = '{{$to->id}}';
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
        @elseif(Session::has('message') && Session::get('message')!="此用戶已關閉資料。")
        c5('{{Session::get('message')}}');
        @if(Session::get('message') == '評價已完成')
        popEvaluation()
        @endif
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
        @if($user->id != $to->id)
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
        @if($user->id != $to->id)
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

    if($('.append_title').text() != '') {
        $('.ci_tub').html($('.append_title').text());
    }else{
        $('.ci_tub').hide();
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
@stop
