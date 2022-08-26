@extends('new.layouts.website')

@section('style')
    <link rel="stylesheet" href="/new/css/swiper.min2.css">
    <link rel="stylesheet" href="/new/css/rangeslider.css">
    <style>
        .out{ border-radius: 10px; padding: 0 10px;position: absolute; right: 0; top: 0; background: #eee;line-height: 35px;}
        .out span{ float: left;line-height: 35px;color: #555; }
        output{display: block;padding-top:0px;font-size: 14px; float: left;line-height: 35px;color: #555;}
        .swiper-container {}
        .swiper-slide {justify-content: center;align-items: center;display: flex;padding:10px 0;}
        .sild img {width: 100%;}
        .swiper-button-next.swiper-button-disabled, .swiper-button-prev.swiper-button-disabled{opacity: .6;}
        .swiper-button-next, .swiper-button-prev{margin-top: -15px;width: 20px; height: 41px;background-size:20px 41px;top: 47%;}
        .swiper-button-next, .swiper-container-rtl .swiper-button-prev {
            background-image:url(/new/images/a_a2.png);
            right:6px !important;
            left: auto;
        }
        .swiper-button-prev, .swiper-container-rtl .swiper-button-next {
            background-image:url(/new/images/a_a1.png);
            left:6px !important;
            right: auto;
        }
        .cbg_ont img{border-radius: 0;box-shadow:none;}
        @media (max-width: 450px){
            .swiper-button-next, .swiper-button-prev{margin-top: -15px;width: 15px; height: 31px;background-size:15px 31px;}
            .swiper-button-next, .swiper-container-rtl .swiper-button-prev {
                background-image:url(/new/images/a_a2.png);
                right:6px !important;
                left: auto;
            }
            .swiper-button-prev, .swiper-container-rtl .swiper-button-next {
                background-image:url(/new/images/a_a1.png);
                left:6px !important;
                right: auto;
            }
        }
        .hidden {
            display: none;
        }
        .append_custom{
            width: unset;
            padding: 10px;
            display: inline-table;
        }
    </style>
@endsection

@section('app-content')
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div style="margin-top: 10px;">
                    <div class="g_pwicon">
                        <li><a href="/dashboard/viewuser/{{$user->id}}" class="g_pwicon_t5 "><span>自我預覽</span></a></li>
                        <li><a href="{!! url('dashboard') !!}" class="g_pwicon_t "><span>基本資料</span></a></li>
                        <li><a href="{!! url('dashboard_img') !!}" class="g_pwicon_t2"><span>照片管理</span></a></li>
                        <li><a href="{!! url('dashboard/account_manage') !!}" class="g_pwicon_t3 "><span>帳號設定</span></a></li>
                    </div>

                    <div class="wlujing">
                        <img src="/new/images/dd.png"><span><a href="">升级付费</a></span><font>-</font>
                        <img src="/new/images/dd.png"><span><a href="">VVIP專區</a></span><font>-</font>
                        <img src="/new/images/dd.png"><span><a href="">VVIP會員必填</a></span>
                    </div>

                    <div class="zhapian vvip_hg">
                        <div class="vip_bt xq_v_bt">VVIP會員必填</div>
                        <form id="form_vvipEdit" action="{{ route('vvipInfoEdit') }}" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!}

                            <!-- 重點資訊 -->
                            <div class="ziliao_1" style="margin-top: 25px;">
                                <div class="zlsapn_1">一、重點資訊<span><img src="/new/images/zhy_5.png">可能是身分、職業背景或是財富</span></div>
                                <div class="xl_system system">
                                    <div class="xl_tishi">
                                        請選擇您最符合您的身分背景：建議 "本區盡量填寫您能提供的額外幫助，會對於甜心寶貝了解您更有幫助"
                                    </div>

                                    @foreach($point_information as $option)
                                        <div class="xl_stiname matop20">
                                            <dt class="nn_shoc option_point_information @if($option->xref_id ?? false) nn_shoc_h @endif" value={{$option->id}}><img src="/new/images/zhy_2.png" class="x_left">{{$option->option_name}}</dt>
                                            <dd style="display: @if($option->xref_id ?? false) block @else none @endif ;" class="shous">
                                                @if($option->option_name == '高資產')
                                                    <div id="itemssxN2_high_assets" class="nn_nrrong">
                                                        <font class="left">資產類型：</font>
                                                        <ul class="ui-choose ui01" id="uc_1">
                                                            @foreach($high_assets as $sub_option)
                                                                <li @if($sub_option->xref_id ?? false) class="selected" @endif value={{$sub_option->id}}>{{$sub_option->option_name}}</li>
                                                            @endforeach
                                                            <input type="text" id="uc_1_point1_input" placeholder="自填" name="high_assets_other" class="left cu_input">
                                                        </ul>
                                                    </div>
                                                @elseif($option->option_name == '高收入')
                                                    <div class="nn_nrrong_2">
                                                        <font class="left">使用此標籤，年收最低須達 5M</font>
                                                    </div>
                                                @elseif($option->option_name == '企業負責人')
                                                    <div id="itemssxN2_ceo_title" class="nn_nrrong">
                                                        <ul class="ui-choose" id="uc_2">
                                                            @foreach($ceo_title as $sub_option)
                                                                <li @if($sub_option->xref_id ?? false) class="selected" @endif value={{$sub_option->id}}>{{$sub_option->option_name}}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @elseif($option->option_name == '出手大方')
                                                    <div class="nn_nrrong_2">
                                                        <font class="left">選擇此標籤日後如有爭議，站方將使用更多的保證金解決問題</font>
                                                        {{--
                                                        <div style="width: 100%; display: table;">
                                                            <input type="checkbox" style="width: 15px; height: 15px; float: left;">
                                                            <span class="margin-r" >我同意</span>
                                                        </div>
                                                        --}}
                                                    </div>
                                                @elseif($option->option_name == '品味高雅')
                                                    <div class="nn_nrrong_2">
                                                        <font class="left">選擇此標籤，約見<i class="ds">站方認證過</i>的女會員場合必須為高級餐廳</font>
                                                        {{--
                                                        <div style="width: 100%; display: table;">
                                                            <input type="checkbox" style="width: 15px; height: 15px; float: left;">
                                                            <span class="margin-r" >我同意</span>
                                                        </div>
                                                        --}}
                                                    </div>
                                                @elseif($option->option_name == '人生導師')
                                                @endif
                                            </dd>
                                        </div>
                                    @endforeach

                                    <div class="miaoshu">
                                        <h2>若上述皆無符合您的相關描述，可於下方自行輸入</h2>
                                        <div class="input_field_1">
                                            {{--<input type="text" placeholder="請輸入至多20個字" class="msinput point" maxlength="20">--}}
                                        </div>
                                    </div>
                                    
                                    <a href="javascript:void(0)" type="button" id="add_image_1" class="ms_xinz"><img src="/new/images/zhy_1.png">新增</a>
                                </div>
                            </div>

                            <!-- 約會傾向 -->
                            <div class="ziliao_1" style="margin-top: 25px;">
                                <div class="zlsapn_1">二、約會傾向</div>
                                <div class="xl_system system">
                                    <div class="qingx">請選擇您期待的約會傾向</div>
                                    <div id="itemssxN2_date_trend" class="qi_wi00 matop10">
                                        @foreach($date_trend as $option)
                                            <span class="custom_s a1 left option_date_trend @if($option->xref_id ?? false) cractive_a @endif" value={{$option->id}}>{{$option->option_name}}</span>
                                        @endforeach
                                        <div class="miaoshu" style="margin-top: 5px;">
                                            <h2 style=" padding-top: 0;">若上述皆無符合您的相關描述，可於下方自行輸入</h2>
                                            <div class="input_field_2">
                                                {{--<input type="text" placeholder="請輸入至多10個字" class="msinput date_trend" maxlength="10">--}}
                                            </div>
                                        </div>
                                        <a href="javascript:void(0)" type="button" id="add_image_2" class="ms_xinz"><img src="/new/images/zhy_1.png">新增</a>
                                    </div>
                                </div>
                            </div>
                            <!-- 約會傾向 -->

                            <!-- 背景與資產 -->
                            <div class="ziliao_1" style="margin-top: 25px;">
                                <div class="zlsapn_1">三、背景與資產<span><img src="/new/images/zhy_5.png">至多選四個</span></div>
                                <div class="xl_system system">
                                    @foreach($background_and_assets as $option)
                                        <div class="xl_stiname matop10">
                                            <dt class="nn_shoc option_background_and_assets @if($option->xref_id ?? false) nn_shoc_h @endif" value={{$option->id}}>
                                            <img src="/new/images/zhy_2.png" class="x_left">
                                                {{$option->option_name}}
                                                @if(in_array($option->option_name, array("專業人士", "高資產人士")))
                                                    <i>（可複選）</i>
                                                @endif
                                            </dt>
                                            <dd style="display: @if($option->xref_id ?? false) block @else none @endif ;" class="shous">
                                                @if($option->option_name == '專業人士')
                                                    <div id="itemssxN3" class="nn_nrrong">
                                                        @foreach($professional as $sub_option)
                                                            <span class="custom_s a1 left @if($sub_option->xref_id ?? false) cractive_a @endif" value={{$sub_option->id}}>{{$sub_option->option_name}}</span>
                                                        @endforeach
                                                    </div>
                                                @elseif($option->option_name == '高資產人士')
                                                    <div id="itemssxN4" class="nn_nrrong">
                                                        @foreach($high_net_worth as $sub_option)
                                                            <div style="width: 100%; display: table;">
                                                                <span class="custom_s a1 @if($sub_option->xref_id ?? false) cractive_a @endif" value={{$sub_option->id}}>
                                                                    {{$sub_option->option_name}}
                                                                </span>
                                                            </div>
                                                            <div style="width: 100%; @if($sub_option->xref_id ?? false)display:block; @endif" class="cr_b gk01">
                                                                <div class="js-slider-change-value" style="margin-bottom:20px; margin-top:3px;">
                                                                    <input class="data-rangeslider" type="range" min="0" max="100" value={{$sub_option->option_remark ?? 20}}>
                                                                    <div class="out">
                                                                        <output></output>
                                                                        <span>%</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @elseif($option->option_name == '企業家')
                                                    <div id="itemssxN5" class="nn_nrrong">
                                                        <div class="cr_ad">-先選企業規模</div>
                                                            <ul class="ui-choose" id="uc_3">
                                                                @foreach($entrepreneur as $sub_option)
                                                                    @php
                                                                        if($sub_option->xref_id ?? false)
                                                                        {
                                                                            $entrepreneur_ceo_title = $sub_option->option_remark;
                                                                        }
                                                                    @endphp
                                                                    <li class="xs1 @if($sub_option->xref_id ?? false) selected @endif " value={{$sub_option->id}}>
                                                                        {{$sub_option->option_name}}
                                                                        <b class="cr_b cr1 cr_b1" @if($sub_option->xref_id ?? false) style="display: block;" @endif>
                                                                            ：{{$sub_option->option_content}}
                                                                        </b>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                            <input type="text" class="hidden">
                                                        <div class="cr_ad">-選擇身份</div>
                                                        <ul class="ui-choose" id="uc_4">
                                                            @foreach(DB::table('vvip_sub_option_ceo_title')->where('is_custom', 0)->get() as $second_sub_option)
                                                            <li class=" @if($second_sub_option->id == ($entrepreneur_ceo_title ?? false)) selected @endif " value={{$second_sub_option->id}}>{{$second_sub_option->option_name}}</li>
                                                            @endforeach
                                                        </ul>
                                                        <input type="text" class="hidden">
                                                    </div>
                                                @endif
                                            </dd>
                                        </div>
                                    @endforeach
                                    <div class="miaoshu">
                                        <h2>若上述皆無符合您的相關描述，可於下方自行輸入</h2>
                                        <div class="input_field_3">
                                            {{--<input type="text" placeholder="請輸入至多20個字" class="msinput assets" maxlength="20">--}}
                                        </div>
                                    </div>
                                    <a href="javascript:void(0)" type="button" id="add_image_3" class="ms_xinz"><img src="/new/images/zhy_1.png">新增</a>
                                </div>
                            </div>
                            <!-- 背景與資產  -->

                            <!-- 四、額外照顧 -->
                            <div class="ziliao_1" style="margin-top: 25px;">
                                <div class="zlsapn_1">四、額外照顧<span><img src="/new/images/zhy_5.png">至多選四個</span></div>
                                <div class="xl_system system">
                                    @foreach($extra_care as $option)
                                        <div class="xl_stiname matop10">
                                            <dt class="nn_shoc option_extra_care @if($option->xref_id ?? false) nn_shoc_h @endif" value={{$option->id}}>
                                                <img src="/new/images/zhy_2.png" class="x_left">
                                                {{$option->option_name}}
                                                @if(in_array($option->option_name, array("生活照顧", "特殊問題處理")))
                                                    <i>（可複選）</i>
                                                @endif
                                            </dt>
                                            <dd style="display: @if($option->xref_id ?? false) block @else none @endif ;" class="shous">
                                                @if($option->option_name == '專業人脈')
                                                    <div id="itemssxN6" class="nn_nrrong">
                                                        @foreach($professional_network as $sub_option)
                                                            @php
                                                                if($sub_option->xref_id ?? false)
                                                                {
                                                                    $depth = $sub_option->option_remark;
                                                                }
                                                            @endphp
                                                            <span class="custom_s a1 left @if($sub_option->xref_id ?? false) cractive_a @endif" value={{$sub_option->id}}>{{$sub_option->option_name}}</span>
                                                        @endforeach
                                                        <input placeholder="輸入人脈屬性(至多8個中文字)" class="msinput" style=" margin-bottom:10px;">
                                                        <div class="cr_ad">-人脈可用程度：<i class="nn_shoc_r">(必填)</i></div>
                                                        <h2 class="us_da_1"><input type="radio" @if(($depth ?? false) == 'high') checked @endif name="network_depth" style="margin-right:4px;" value='high'>高：可視情況幫baby安排實習/正式職務</h2>
                                                        <h2 class="us_da_1"><input type="radio" @if(($depth ?? false) == 'low') checked @endif name="network_depth" style="margin-right:4px;" value='low'>低：只能提供顧問以及諮詢</h2>
                                                    </div>
                                                @elseif($option->option_name == '生活照顧')
                                                    <div id="itemssxN7_life_care" class="nn_nrrong">
                                                        @foreach($life_care as $sub_option)
                                                            <span class="custom_s a1 left @if($sub_option->xref_id ?? false) cractive_a @endif" value={{$sub_option->id}}>{{$sub_option->option_name}}</span>
                                                        @endforeach
                                                    </div>
                                                @elseif($option->option_name == '特殊問題處理')
                                                    <div id="itemssxN7_special_problem_handling" class="nn_nrrong">
                                                        @foreach($special_problem_handling as $sub_option)
                                                            <span class="custom_s a1 left @if($sub_option->xref_id ?? false) cractive_a @endif" value={{$sub_option->id}}>{{$sub_option->option_name}}</span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </dd>
                                        </div>
                                    @endforeach
                                    <div class="miaoshu">
                                        <h2>若上述皆無符合您的相關描述，可於下方自行輸入</h2>
                                        <div class="input_field_4">
                                            {{--<input type="text" placeholder="請輸入至多20個字" class="msinput extra_care" maxlength="20">--}}
                                        </div>
                                    </div>
                                    <a href="javascript:void(0)" type="button" id="add_image_4" class="ms_xinz"><img src="/new/images/zhy_1.png">新增</a>
                                </div>
                            </div>
                            <!-- 額外照顧  -->

                            <!-- 五、您的財富資產 -->
                            <div class="ziliao_1" style="margin-top: 25px;">
                                <div class="zlsapn_1">五、您的財富資產<span><img src="/new/images/zhy_5.png">至多選四個</span></div>

                                <div id="assets_image_input_field" class="xl_system system">
                                    <div class="qingx">請輸入您最象徵性的財富資產。Ex. ROLEX名錶、獨棟豪宅、BMW/賓士等各式名車等</div>
                                    <div class="red">※優先使用上傳圖檔，如已選擇系統圖片將不會被存取</div>
                                    <div class="miaoshu">
                                        <div class="input_field_5 matop10 image_input_field">
                                        </div>
                                    </div>
                                    <a href="javascript:void(0)" type="button" id="add_image_5" class="ms_xinz mabot_20 matop20"><img src="/new/images/zhy_1.png">新增</a>

                                    <div class="dt_izhaopian">
                                        <h2><i></i>您也可以直接選擇系統預設的照片<font>不滿意？往右滑換一組！</font></h2>

                                        <div style="width:100%; margin: 0 auto;">
                                            <div class="swiper-container wip01">
                                                <div class="swiper-wrapper">
                                                    @foreach($assets_image as $option)
                                                        <div class="swiper-slide sild">
                                                            <div class="cbg_ont"><img src={{$option->option_name}} value={{$option->id}}></div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="swiper-button-next next01" style="right:15px;;"></div>
                                                <div class="swiper-button-prev prev01" style="left: 10px;"></div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <!-- 您的財富資產  -->

                            <!-- 六、您的品質生活 -->
                            <div class="ziliao_1" style="margin-top: 25px;">
                                <div class="zlsapn_1">六、您的品質生活<span><img src="/new/images/zhy_5.png">至多選兩個</span></div>
                                
                                <div id="life_image_input_field" class="xl_system system">
                                    <div class="qingx">輸入您喜愛的生活體驗或事物。Ex. 喜愛的餐廳：高級日式料理、出國旅遊、休閒娛樂：打高爾夫等。</div>
                                    <div class="red">※優先使用上傳圖檔，如已選擇系統圖片將不會被存取</div>
                                    <div class="miaoshu">
                                        <div class="input_field_6 matop10 image_input_field">
                                        </div>
                                    </div>
                                    <a href="javascript:void(0)" type="button" id="add_image_6" class="ms_xinz mabot_20 matop20"><img src="/new/images/zhy_1.png">新增</a>

                                    <div class="dt_izhaopian">
                                        <h2><i></i>您也可以直接選擇系統預設的照片<font>不滿意？往右滑換一組！</font></h2>
                                        <div style="width:100%; margin: 0 auto;">
                                            <div class="swiper-container wip02">
                                                <div class="swiper-wrapper">
                                                    @foreach($quality_life_image as $option)
                                                        <div class="swiper-slide sild">
                                                            <div class="cbg_ont"><img src={{$option->option_name}} value={{$option->id}}></div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="swiper-button-next next01" style="right:15px;;"></div>
                                                <div class="swiper-button-prev prev01" style="left: 10px;"></div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <!--您的財富資產  -->

                            <!-- 七、期待的約會模式 -->
                            <div class="ziliao_1" style="margin-top: 25px;">
                                <div class="zlsapn_1">七、期待的約會模式<span><img src="/new/images/zhy_5.png">至多選四個</span></div>
                                <div class="xl_system system">
                                    <div class="qingx">請選擇您期待的約會傾向</div>
                                    <div id="itemssxN9" class="qi_wi00 matop10">
                                        @foreach($expect_date as $option)
                                        <span class="custom_s a1 left option_expect_date @if($option->xref_id ?? false) cractive_a @endif" value={{$option->id}}>{{$option->option_name}}</span>
                                        @endforeach
                                        <div class="miaoshu matop10">
                                            <div class="input_field_7">
                                                {{--<input type="text" placeholder="請輸入至多10個字" class="msinput date_expect" maxlength="10">--}}
                                            </div>
                                        </div>
                                        <a href="javascript:void(0)" type="button" id="add_image_7" class="ms_xinz"><img src="/new/images/zhy_1.png">新增</a>
                                    </div>
                                </div>
                            </div>
                            <!-- 七、期待的約會模式 -->

                            {{--複選選項--}}
                            <input id="point_information" type="hidden" name="point_information" value="">
                            <input id="date_trend" type="hidden" name="date_trend" value="">
                            <input id="background_and_assets" type="hidden" name="background_and_assets" value="">
                            <input id="extra_care" type="hidden" name="extra_care" value="">
                            <input id="expect_date" type="hidden" name="expect_date" value="">

                            <input id="point_information_other" type="hidden" name="point_information_other" value="">
                            <input id="date_trend_other" type="hidden" name="date_trend_other" value="">
                            <input id="background_and_assets_other" type="hidden" name="background_and_assets_other" value="">
                            <input id="extra_care_other" type="hidden" name="extra_care_other" value="">
                            <input id="expect_date_other" type="hidden" name="expect_date_other" value="">

                            <input id="high_assets" type="hidden" name="high_assets" value="">
                            <input id="ceo_title" type="hidden" name="ceo_title" value="">
                            <input id="professional" type="hidden" name="professional" value="">
                            <input id="high_net_worth" type="hidden" name="high_net_worth" value="">
                            <input id="entrepreneur" type="hidden" name="entrepreneur" value="">
                            <input id="professional_network" type="hidden" name="professional_network" value="">
                            <input id="life_care" type="hidden" name="life_care" value="">
                            <input id="special_problem_handling" type="hidden" name="special_problem_handling" value="">

                            <input id="system_image_assets" type="hidden" name="system_image_assets" value="">
                            <input id="system_image_life" type="hidden" name="system_image_life" value="">

                            {{--複選選項--}}

                            <div class="n_txbut matop40">
                                <a class="n_dlbut vvipInfo_submit">送出</a>
                                <a class="n_zcbut">取消</a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')

    <script src="/new/js/swiper.min.js"></script>
    <script src="/new/js/ui-choose.js"></script>
    <script src="/new/js/rangeslider.min.js"></script>
    <link href="{{ asset('css/jquery.fileuploader.min.css') }}" media="all" rel="stylesheet">
    <link href="{{ asset('new/css/fileupload.css') }}" media="all" rel="stylesheet">
    <link href="{{ asset('css/font/font-fileuploader.css') }}" media="all" rel="stylesheet">
    <script src="{{ asset('js/jquery.fileuploader.js') }}" type="text/javascript"></script>

    <script>
        $(document).ready(function(){
            @foreach($assets_image as $option)
                @if($option->xref_id ?? false)
                    $('.input_field_5').first().append('<div class="system_image matop10">' +
                        '<input type="text" placeholder="請輸入至多18個字" class="msinput assets_image_top" maxlength="18" value={{$option->option_remark}}>' +
                        '<ul class="n_ulpic" style="margin-bottom: 0;">' +
                            '<img class="sys-img" src={{$option->option_name}} value={{$option->id}}>' +
                        '</ul><a href="#" class="remove_field_2"><img src="/new/images/del_03n.png"></a></div>');
                @endif
            @endforeach

            @foreach($quality_life_image as $option)
                @if($option->xref_id ?? false)
                    $('.input_field_6').first().append('<div class="system_image matop10">' +
                        '<input type="text" placeholder="請輸入至多18個字" class="msinput life_top" maxlength="18" value={{$option->option_remark}}>' +
                        '<ul class="n_ulpic" style="margin-bottom: 0;">' +
                            '<img class="sys-img" src={{$option->option_name}} value={{$option->id}}>' +
                        '</ul><a href="#" class="remove_field_2"><img src="/new/images/del_03n.png"></a></div>');
                @endif
            @endforeach
        });

        $('.vvipInfo_submit').on('click',function (e) {

            let option_array = [];
            let option_count = 0;

            //重點資訊
            option_array = [];
            $('.option_point_information.nn_shoc_h').each(function(){
                option_array.push($(this).attr('value'));
            });
            option_array = JSON.stringify(option_array);
            $('#point_information').val(option_array);

            //重點資訊-自填
            option_array = [];
            option_array.push($('.input_field_1').children().first().val());
            $('.input_field_1').children('.custom').each(function(){
                option_array.push($(this).children('.msinput').val());
            });
            option_array = JSON.stringify(option_array);
            $('#point_information_other').val(option_array);

            //約會傾向
            option_array = [];
            $('.option_date_trend.cractive_a').each(function(){
                option_array.push($(this).attr('value'));
            });
            option_array = JSON.stringify(option_array);
            $('#date_trend').val(option_array);

            //約會傾向-自填
            option_array = [];
            option_array.push($('.input_field_2').children().first().val());
            $('.input_field_2').children('.custom').each(function(){
                option_array.push($(this).children('.msinput').val());
            });
            option_array = JSON.stringify(option_array);
            $('#date_trend_other').val(option_array);

            //背景與資產
            option_array = [];
            option_count = 0;
            $('.option_background_and_assets.nn_shoc_h').each(function(){
                option_array.push($(this).attr('value'));
                option_count = option_count + 1;
            });
            option_array = JSON.stringify(option_array);
            $('#background_and_assets').val(option_array);

            //背景與資產-自填
            option_array = [];
            option_array.push($('.input_field_3').children().first().val());
            if($('.input_field_3').children().first().val() != '')
            {
                option_count = option_count + 1;
            }
            $('.input_field_3').children('.custom').each(function(){
                option_array.push($(this).children('.msinput').val());
                if($(this).children('.msinput').val() != '')
                {
                    option_count = option_count + 1;
                }
            });
            option_array = JSON.stringify(option_array);
            $('#background_and_assets_other').val(option_array);

            //檢查背景與資產數量
            if(option_count > 4)
            {
                c5('背景與資產至多選擇四個')
                    return false;
            }

            //額外照顧
            option_array = [];
            option_count = 0;
            $('.option_extra_care.nn_shoc_h').each(function(){
                option_array.push($(this).attr('value'));
                option_count = option_count + 1;
            });
            option_array = JSON.stringify(option_array);
            $('#extra_care').val(option_array);

            //額外照顧-自填
            option_array = [];
            option_array.push($('.input_field_4').children().first().val());
            if($('.input_field_4').children().first().val() != '')
            {
                option_count = option_count + 1;
            }
            $('.input_field_4').children('.custom').each(function(){
                option_array.push($(this).children('.msinput').val());
                if($(this).children('.msinput').val() != '')
                {
                    option_count = option_count + 1;
                }
            });
            option_array = JSON.stringify(option_array);
            $('#extra_care_other').val(option_array);

            //檢查額外照顧數量
            if(option_count > 4)
            {
                c5('額外照顧至多選擇四個')
                    return false;
            }

            //期待的約會模式
            option_array = [];
            option_count = 0;
            $('.option_expect_date.cractive_a').each(function(){
                option_array.push($(this).attr('value'));
                option_count = option_count + 1;
            });
            option_array = JSON.stringify(option_array);
            $('#expect_date').val(option_array);

            //期待的約會模式-自填
            option_array = [];
            option_array.push($('.input_field_7').children().first().val());
            if($('.input_field_7').children().first().val() != '')
            {
                option_count = option_count + 1;
            }
            $('.input_field_7').children('.custom').each(function(){
                option_array.push($(this).children('.msinput').val());
                if($(this).children('.msinput').val() != '')
                {
                    option_count = option_count + 1;
                }
            });
            option_array = JSON.stringify(option_array);
            $('#expect_date_other').val(option_array);

            //檢查期待的約會模式數量
            if(option_count > 4)
            {
                c5('期待的約會模式至多選擇四個')
                    return false;
            }

            //重點資訊-高資產
            $('#high_assets').val($('#itemssxN2_high_assets .ui-choose .selected').first().val());

            //檢查是否填寫高資產細項
            if($('#itemssxN2_high_assets').parent().prev().hasClass("nn_shoc_h"))
            {
                if($('#high_assets').val() == '' && $('#uc_1_point1_input').val() == '')
                {
                    c5('請選擇高資產')
                    return false;
                }
            }

            //重點資訊-企業負責人
            $('#ceo_title').val($('#itemssxN2_ceo_title .ui-choose .selected').first().val());

            //檢查是否填寫企業負責人細項
            if($('#itemssxN2_ceo_title').parent().prev().hasClass("nn_shoc_h"))
            {
                if($('#ceo_title').val() == '')
                {
                    c5('請選擇企業負責人')
                    return false;
                }
            }

            //背景與資產-專業人士
            option_array = [];
            $('#itemssxN3').children('.cractive_a').each(function(){
                option_array.push($(this).attr('value'));
            });
            option_array = JSON.stringify(option_array);
            $('#professional').val(option_array);

            //檢查是否填寫專業人士細項
            if($('#itemssxN3').parent().prev().hasClass("nn_shoc_h"))
            {
                if(option_array == '[]')
                {
                    c5('請選擇專業人士')
                    return false;
                }
            }

            //背景與資產-高資產人士
            option_array = [];
            $('#itemssxN4').find('.cractive_a').each(function(){
               option_array.push([$(this).attr('value'), $(this).parent().next().find('output').html()]);
            });
            option_array = JSON.stringify(option_array);
            $('#high_net_worth').val(option_array);

            //檢查是否填寫高資產人士細項
            if($('#itemssxN4').parent().prev().hasClass("nn_shoc_h"))
            {
                if(option_array == '[]')
                {
                    c5('請選擇高資產人士')
                    return false;
                }
            }

            //背景與資產-企業家
            option_array = [];
            $('#itemssxN5').find('.selected').each(function(){
                option_array.push($(this).val());
            });

            //檢查是否填寫細項
            if($('#itemssxN5').parent().prev().hasClass("nn_shoc_h"))
            {
                if(option_array.length < 2)
                {
                    c5('請選擇企業家')
                    return false;
                }
            }
            option_array = JSON.stringify(option_array);
            $('#entrepreneur').val(option_array);

            //額外照顧-專業人脈
            option_array = [];
            option_array.push($('#itemssxN6').children(".cractive_a").attr('value'));
            option_array.push($('#itemssxN6').children(".msinput").val());
            option_array.push($("[name='network_depth']:checked").val());

            //檢查是否填寫專業人脈細項
            if($('#itemssxN6').parent().prev().hasClass("nn_shoc_h"))
            {
                if(option_array[2] == null || (option_array[0] == null && option_array[1] == ''))
                {
                    c5('請選擇專業人脈')
                    return false;
                }
            }
            option_array = JSON.stringify(option_array);
            $('#professional_network').val(option_array);

            //額外照顧-生活照顧
            option_array = [];
            $('#itemssxN7_life_care').children('.cractive_a').each(function(){
                option_array.push($(this).attr('value'));
            });
            option_array = JSON.stringify(option_array);
            $('#life_care').val(option_array);

            //檢查是否填寫生活照顧細項
            if($('#itemssxN7_life_care').parent().prev().hasClass("nn_shoc_h"))
            {
                if(option_array == '[]')
                {
                    c5('請選擇生活照顧')
                    return false;
                }
            }

            //額外照顧-特殊問題處理
            option_array = [];
            $('#itemssxN7_special_problem_handling').children('.cractive_a').each(function(){
                option_array.push($(this).attr('value'));
            });
            option_array = JSON.stringify(option_array);
            $('#special_problem_handling').val(option_array);

            //檢查是否填寫生活照顧細項
            if($('#itemssxN7_special_problem_handling').parent().prev().hasClass("nn_shoc_h"))
            {
                if(option_array == '[]')
                {
                    c5('請選擇特殊問題處理')
                    return false;
                }
            }

            //您的財富資產-預設圖片
            option_array = [];
            $('.input_field_5').first().children('.system_image').each(function(){
                option_array.push([$(this).find('.sys-img').attr('value'),$(this).children('input').val()]);
            });
            option_array = JSON.stringify(option_array);
            $('#system_image_assets').val(option_array);

            //您的品質生活-預設圖片
            option_array = [];
            $('.input_field_6').first().children('.system_image').each(function(){
                option_array.push([$(this).find('.sys-img').attr('value'),$(this).children('input').val()]);
            });
            option_array = JSON.stringify(option_array);
            $('#system_image_life').val(option_array);
            


            $('#form_vvipEdit').submit();
        });
    </script>

    <script>
        var swiper01 = new Swiper('.wip01', {
            slidesPerView:5,
            spaceBetween:10,
            slidesPerGroup:5,
            loop: true,
            loopFillGroupWithBlank: true,

            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev',
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                450: {
                    slidesPerView:5,
                    spaceBetween:6,
                }
            }
        });

        var swiper02 = new Swiper('.wip02', {
            slidesPerView:5,
            spaceBetween:10,
            slidesPerGroup:5,
            loop: true,
            loopFillGroupWithBlank: true,

            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev',
            // init: false,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                450: {
                    slidesPerView:5,
                    spaceBetween:6,
                }
            }
        });
    </script>

    <script>
        $('.ui-choose').ui_choose();

        let uc_01 = $('#uc_1').data('ui-choose');
        uc_01.click = function(index, item) {
            $('#uc_1_point1_input').val('');
        };
        $('#uc_1_point1_input').on("keyup", function () {
            $('#uc_1 li').removeClass( "selected" );
        });

        $(function(){
            $('.xs1').click(function(){
                if($(this).children('.cr_b1').is(':hidden')){	
                    $('.cr_b1').hide();
                    $(this).children('.cr_b1').show();
                    $(this).style = "";
                }else{
                    $(this).children('.cr_b1').hide();
                    $(this).style.background="#f8f8f8";
                    $(this).style.color="#888888";
                    $(this).style.border = "#d2d2d2 1px solid";
                }
            })

            $('.nn_shoc').click(function(e) {
                $(this).toggleClass('nn_shoc_h');
                $(this).next('dd').slideToggle();
            });
        })
    </script>

    <script>
        $(function(){

            // Example functionality to demonstrate a value feedback
            // and change the output's value.
            function valueOutput(element) {
                var value = element.value;
                var output = element.parentNode.getElementsByTagName('output')[0];

                output.innerHTML = value;
            }

            // Initial value output
            $('.data-rangeslider').each(function(){
                $(this).next().children('output').first().html($(this).val());
            });

            // Update value output
            $(document).on('input', '.data-rangeslider', function(e) {
                valueOutput(e.target);
            });

            // Initialize the elements
            $('.data-rangeslider').rangeslider({
                polyfill: false
            });

            // Example functionality to demonstrate programmatic value changes
            $(document).on('click', '.js-slider-change-value button', function(e) {
                var $inputRange = $('input[type="range"]', e.target.parentNode);
                var value = $('input[type="number"]', e.target.parentNode)[0].value;

                $inputRange.val(value).change();
            });
        });
    </script>

    <script>

        $(document).ready(function() {

            $("#itemssxN2_date_trend .a1").on("click", function () {
                $(this).toggleClass('cractive_a');
            });

            $("#itemssxN3 .a1").on("click", function () {
                $(this).toggleClass('cractive_a');
            });

            $("#itemssxN4 .a1").on("click", function () {
                $(this).toggleClass('cractive_a');
            });

            $('#itemssxN4 .a1').click(function () {
                if ($(this).parent().next('.gk01').is(':hidden')) {
                    $(this).parent().next('.gk01').show();
                } else {
                    $(this).parent().next('.gk01').hide();
                }
            });

            $("#itemssxN6 .a1").on("click", function () {
                $("#itemssxN6 .a1").removeClass("cractive_a")
                $("#itemssxN6").children('.msinput').val('');
                $(this).toggleClass('cractive_a');
            });

            $("#itemssxN6").children('.msinput').on("keyup", function () {
                $("#itemssxN6 .a1").removeClass("cractive_a")
            });

            $("#itemssxN7_life_care .a1").on("click", function () {
                $(this).toggleClass('cractive_a');
            });

            $("#itemssxN7_special_problem_handling .a1").on("click", function () {
                $(this).toggleClass('cractive_a');
            });

            $("#itemssxN9 .a1").on("click", function () {
                $(this).toggleClass('cractive_a');
            });

            $("#add_image_1").click(function(e) {
                if($('.point:last').val()==''){
                    c5('您尚未輸入文字');
                }else {
                    e.preventDefault();
                    $(".input_field_1").append('<div class="custom"><input type="text" placeholder="請輸入至多20個字" class="msinput point" maxlength="20"><a href="#" class="remove_field_1"><img src="/new/images/del_03n.png"></a></div>'); //add input box
                }
            });
            $(".input_field_1").on("click", ".remove_field_1", function(e) {
                e.preventDefault();
                $(this).parent('div').remove();
            });

            $("#add_image_2").click(function(e) {
                if($('.date_trend:last').val()==''){
                    c5('您尚未輸入文字');
                }else {
                    e.preventDefault();
                    $(".input_field_2").append('<div class="custom"><input type="text" placeholder="請輸入至多10個字" class="msinput date_trend" maxlength="10"><a href="#" class="remove_field_1"><img src="/new/images/del_03n.png"></a></div>'); //add input box
                }
            });
            $(".input_field_2").on("click", ".remove_field_1", function(e) {
                e.preventDefault();
                $(this).parent('div').remove();
            });

            $("#add_image_3").click(function(e) {
                if($('.assets:last').val()==''){
                    c5('您尚未輸入文字');
                    return false;
                }else {
                    e.preventDefault();
                    $(".input_field_3").append('<div class="custom"><input type="text" placeholder="請輸入至多20個字" class="msinput assets" maxlength="20"><a href="#" class="remove_field_1"><img src="/new/images/del_03n.png"></a></div>'); //add input box
                }
            });
            $(".input_field_3").on("click", ".remove_field_1", function(e) {
                e.preventDefault();
                $(this).parent('div').remove();
            });

            $("#add_image_4").click(function(e) {
                if($('.extra_care:last').val()==''){
                    c5('您尚未輸入文字');
                    return false;
                }else {
                    e.preventDefault();
                    $(".input_field_4").append('<div class="custom"><input type="text" placeholder="請輸入至多20個字" class="msinput extra_care" maxlength="20"><a href="#" class="remove_field_1"><img src="/new/images/del_03n.png"></a></div>'); //add input box
                }
            });
            $(".input_field_4").on("click", ".remove_field_1", function(e) {
                e.preventDefault();
                $(this).parent('div').remove();
            });

            $("#add_image_7").click(function(e) {
                if($('.date_expect:last').val()==''){
                    c5('您尚未輸入文字');
                }else {
                    e.preventDefault();
                    $(".input_field_7").append('<div class="custom"><input type="text" placeholder="請輸入至多10個字" class="msinput date_expect date_expect_text" maxlength="10"><a href="#" class="remove_field_1"><img src="/new/images/del_03n.png"></a></div>'); //add input box
                }
            });
            $(".input_field_7").on("click", ".remove_field_1", function(e) {
                e.preventDefault();
                $(this).parent('div').remove();
            });

            let add_assets_image_id = 0;
            let add_life_image_id = 0;

            $("#add_image_5").click(function(e) {
                add_assets_image_id = add_assets_image_id + 1;
                if($('.assets_image_top:last').val()==''){
                    c5('您尚未輸入文字');
                    return false;
                }else {
                    e.preventDefault();
                    $(".input_field_5").append('<div class="custom matop10">' +
                        '<input type="text" placeholder="請輸入至多18個字" class="msinput assets_image_top" name="assets_image_content[' + add_assets_image_id + ']" maxlength="18">' +
                        '<ul class="n_ulpic" style="margin-bottom: 0;">' +
                            '<input type="file" class="files assets_image" data-fileuploader-files="" data-fileuploader-listInput="assets_image[' + add_assets_image_id + ']">' +
                        '</ul><a href="#" class="remove_field_2"><img src="/new/images/del_03n.png"></a></div>');

                        uploaderFunction($(".input_field_5").find('.assets_image').last());
                }
            });
            
            $(".input_field_5").on("click", ".remove_field_2", function(e) {
                e.preventDefault();
                $(this).parent('div').remove();
            });

            $("#add_image_6").click(function(e) {
                add_life_image_id = add_life_image_id + 1;
                if($('.life_top:last').val()==''){
                    c5('您尚未輸入文字');
                    return false;
                }else {
                    e.preventDefault();
                    $(".input_field_6").append('<div class="custom matop10">' +
                        '<input type="text" placeholder="請輸入至多18個字" class="msinput life_top" name="life_image_content[' + add_life_image_id + ']" maxlength="18">' +
                        '<ul class="n_ulpic" style="margin-bottom: 0;">' +
                            '<input type="file" class="files life" data-fileuploader-files="" data-fileuploader-listInput="quality_life_image[' + add_life_image_id + ']">' +
                        '</ul><a href="#" class="remove_field_2"><img src="/new/images/del_03n.png"></a></div>');

                    uploaderFunction($(".input_field_6").find('.life').last());
                }
            });
            $(".input_field_6").on("click", ".remove_field_2", function(e) {
                e.preventDefault();
                $(this).parent('div').remove();
            });

            $('#assets_image_input_field').find('.cbg_ont').children('img').click(function(e) {
                if($('.assets_image_top:last').val()=='')
                {
                    c5('您尚未輸入文字');
                    return false;
                }
                else
                {
                    $('.input_field_5').first().append('<div class="system_image matop10">' +
                        '<input type="text" placeholder="請輸入至多18個字" class="msinput assets_image_top" maxlength="18">' +
                        '<ul class="n_ulpic" style="margin-bottom: 0;">' +
                            '<img class="sys-img" src=' + $(this).attr('src') + ' value=' + $(this).attr('value') + '>' +
                        '</ul><a href="#" class="remove_field_2"><img src="/new/images/del_03n.png"></a></div>');
                }
            });

            $('#life_image_input_field').find('.cbg_ont').children('img').click(function(e) {
                if($('.life_top:last').val()=='')
                {
                    c5('您尚未輸入文字');
                    return false;
                }
                else
                {
                    $('.input_field_6').first().append('<div class="system_image matop10">' +
                        '<input type="text" placeholder="請輸入至多18個字" class="msinput life_top" maxlength="18">' +
                        '<ul class="n_ulpic" style="margin-bottom: 0;">' +
                            '<img class="sys-img" src=' + $(this).attr('src') + ' value=' + $(this).attr('value') + '>' +
                        '</ul><a href="#" class="remove_field_2"><img src="/new/images/del_03n.png"></a></div>');
                }
            });
        });
    </script>

    <style>
        .fileuploader{
            background-color: unset;
            width: unset !important;
        }
        .fileuploader-icon-remove:after {
            content: unset !important;
        }
    </style>
    <script>

        //default
        $('.files').fileuploader({
            extensions: ['jpg', 'png', 'jpeg'],
            changeInput: '<a class="img dt_heght write_img dt_pa0" style="background: #fff !important; border: #fe92a9 1px solid; height: unset;"><img src="/new/images/shangc_zp.png" class="hycov" style="cursor: pointer;"></a>',
            theme: 'thumbnails',
            enableApi: true,
            addMore: true,
            limit: 1,
            editor: {
                cropper: {
                    ratio: null,
                    minWidth: null,
                    minHeight: null,
                    showGrid: true
                },
                quality: 70,

            },
            thumbnails: {
                box: '<div class="fileuploader-items">' +
                    '<ul class="fileuploader-items-list">' +
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
                onItemShow: function (item, listEl, parentEl, newInputEl, inputEl) {
                    var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                        api = $.fileuploader.getInstance(inputEl.get(0));

                    plusInput.insertAfter(item.html)[api.getOptions().limit && api.getChoosedFiles().length >= api.getOptions().limit ? 'hide' : 'show']();

                    if (item.format == 'image') {
                        item.html.find('.fileuploader-item-icon').hide();
                    }
                },
                onItemRemove: function (html, listEl, parentEl, newInputEl, inputEl) {
                    var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                        api = $.fileuploader.getInstance(inputEl.get(0));

                    html.children().animate({'opacity': 0}, 200, function () {
                        html.remove();

                        if (api.getOptions().limit && api.getChoosedFiles().length - 1 < api.getOptions().limit)
                            plusInput.show();
                    });
                }
            },
            dragDrop: {
                container: '.fileuploader-thumbnails-input'
            },
            afterRender: function (listEl, parentEl, newInputEl, inputEl) {
                var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                    api = $.fileuploader.getInstance(inputEl.get(0));

                plusInput.on('click', function () {
                    api.open();
                });

                api.getOptions().dragDrop.container = plusInput;
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
                    filesLimit: function (options) {
                        return '最多上傳 ${limit} 張圖片.'
                    },
                    filesType: '檔名: ${name} 不支援此格式, 只允許 ${extensions} 檔案類型上傳.',
                    fileSize: '${name} 檔案太大, 請確認容量需小於 ${fileMaxSize}MB.',
                    filesSizeAll: '上傳的所有檔案過大, 請確認未超過 ${maxSize} MB.',
                    fileName: '${name} 已有選取相同名稱的檔案.',
                }
            }
        });

        function uploaderFunction(object) {
            object.fileuploader({
                extensions: ['jpg', 'png', 'jpeg'],
                changeInput: '<a class="img dt_heght write_img dt_pa0" style="background: #fff !important; border: #fe92a9 1px solid; height: unset;"><img src="/new/images/shangc_zp.png" class="hycov" style="cursor: pointer;"></a>',
                theme: 'thumbnails',
                enableApi: true,
                addMore: true,
                limit: 1,
                editor: {
                    cropper: {
                        ratio: null,
                        minWidth: null,
                        minHeight: null,
                        showGrid: true
                    },
                    quality: 70,

                },
                thumbnails: {
                    box: '<div class="fileuploader-items">' +
                        '<ul class="fileuploader-items-list">' +
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
                    onItemShow: function (item, listEl, parentEl, newInputEl, inputEl) {
                        var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                            api = $.fileuploader.getInstance(inputEl.get(0));

                        plusInput.insertAfter(item.html)[api.getOptions().limit && api.getChoosedFiles().length >= api.getOptions().limit ? 'hide' : 'show']();

                        if (item.format == 'image') {
                            item.html.find('.fileuploader-item-icon').hide();
                        }
                    },
                    onItemRemove: function (html, listEl, parentEl, newInputEl, inputEl) {
                        var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                            api = $.fileuploader.getInstance(inputEl.get(0));

                        html.children().animate({'opacity': 0}, 200, function () {
                            html.remove();

                            if (api.getOptions().limit && api.getChoosedFiles().length - 1 < api.getOptions().limit)
                                plusInput.show();
                        });
                    }
                },
                dragDrop: {
                    container: '.fileuploader-thumbnails-input'
                },
                afterRender: function (listEl, parentEl, newInputEl, inputEl) {
                    var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                        api = $.fileuploader.getInstance(inputEl.get(0));

                    plusInput.on('click', function () {
                        api.open();
                    });

                    api.getOptions().dragDrop.container = plusInput;
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
                        filesLimit: function (options) {
                            return '最多上傳 ${limit} 張圖片.'
                        },
                        filesType: '檔名: ${name} 不支援此格式, 只允許 ${extensions} 檔案類型上傳.',
                        fileSize: '${name} 檔案太大, 請確認容量需小於 ${fileMaxSize}MB.',
                        filesSizeAll: '上傳的所有檔案過大, 請確認未超過 ${maxSize} MB.',
                        fileName: '${name} 已有選取相同名稱的檔案.',
                    }
                }
            });
        }
    </script>
@stop
