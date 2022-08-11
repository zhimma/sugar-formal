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
{{--                        <form id="form_vvipEdit">--}}
                        {!! csrf_field() !!}

                            <!-- 重點資訊 -->
                            <div class="ziliao_1" style="margin-top: 25px;">
                                <div class="zlsapn_1">一、重點資訊<span><img src="/new/images/zhy_5.png">可能是身分、職業背景或是財富</span></div>
                                <div class="xl_system system">
                                    <div class="xl_tishi">
                                        請選擇您最符合您的身分背景：建議 "本區盡量填寫您能提供的額外幫助，會對於甜心寶貝了解您更有幫助"
                                    </div>
                                    <div class="xl_stiname matop20">
                                        @php
                                            //fill exist data
                                            $point1="";
                                            $point1_bottom = "";
                                            $point2="";
                                            $point3="";
                                            $point3_bottom = "";
                                            $point4="";
                                            $point4_bottom = "";
                                            $point5="";
                                            $point5_bottom = "";
                                            $point6="";
                                            $point_other_array = array();

                                            if(isset($vvipInfo) && !empty(json_decode($vvipInfo->point, true))){

                                                foreach( json_decode($vvipInfo->point, true) as $key => $value){
                                                    if($value[0]=="高資產"){
                                                        $point1 = $value[0];
                                                        if(isset($value[1]) && $value[1] != ''){
                                                            $point1_bottom = $value[1];
                                                        }
                                                    }
                                                    else if($value[0]=="高收入"){
                                                        $point2 = $value[0];
                                                    }
                                                    else if($value[0]=="企業負責人"){
                                                        $point3 = $value[0];
                                                        if(isset($value[1]) && $value[1] != ''){
                                                            $point3_bottom = $value[1];
                                                        }
                                                    }
                                                    else if($value[0]=="出手大方"){
                                                        $point4 = $value[0];
                                                        if(isset($value[1]) && $value[1] != ''){
                                                            $point4_bottom = $value[1];
                                                        }
                                                    }
                                                    else if($value[0]=="品味高雅"){
                                                        $point5 = $value[0];
                                                        if(isset($value[1]) && $value[1] != ''){
                                                            $point5_bottom = $value[1];
                                                        }
                                                    }
                                                    else if($value[0]=="人生導師"){
                                                        $point6 = $value[0];
                                                    }
                                                    else{
                                                        array_push($point_other_array, $value[0]);
                                                    }
                                                }
                                            }
                                        @endphp
                                        <dt class="nn_shoc @if($point1=="高資產") nn_shoc_h @endif" data-id="point1"><img src="/new/images/zhy_2.png" class="x_left">高資產<input type="checkbox" id="point1" class="hidden" name="point[0][top]" value="高資產" @if($point1=="高資產") checked="checked" @endif></dt>

                                        <dd style="display:@if($point1=="高資產")block @else none @endif ;" class="shous">
                                            <div id="itemssxN2" class="nn_nrrong">
                                                <font class="left">資產類型：</font>
                                                <ul class="ui-choose ui01" id="uc_1">
                                                    <li @if($point1_bottom=="不動產") class="selected" @endif>不動產</li>
                                                    <li @if($point1_bottom=="證券") class="selected" @endif>證券</li>
                                                    <input type="text" id="uc_1_point1" class="hidden" name="point[0][bottom]" value="{{$point1_bottom}}">
                                                    <input type="text" id="uc_1_point1_input" placeholder="自填" name="point_bottom" class="left cu_input">
                                                </ul>
                                            </div>
                                        </dd>
                                    </div>
                                    <div class="xl_stiname matop10">
                                        <dt class="nn_shoc @if($point2=="高收入") nn_shoc_h @endif" data-id="point2"><img src="/new/images/zhy_2.png" class="x_left">高收入<input type="checkbox" id="point2" class="hidden" name="point[1][top]" value="高收入" @if($point2=="高收入") checked="checked" @endif></dt>
                                        <dd style="display:@if($point2=="高收入")block @else none @endif ;" class="shous">
                                            <div class="nn_nrrong_2">
                                                <font class="left">使用此標籤，年收最低須達 5M</font>
                                            </div>
                                        </dd>
                                    </div>
                                    <div class="xl_stiname matop10">
                                        <dt class="nn_shoc @if($point3=="企業負責人") nn_shoc_h @endif" data-id="point3"><img src="/new/images/zhy_2.png" class="x_left">企業負責人<input type="checkbox" id="point3" class="hidden" name="point[2][top]" value="企業負責人" @if($point3=="企業負責人") checked="checked" @endif></dt>
                                        <dd style="display:@if($point3=="企業負責人")block @else none @endif ;" class="shous">
                                            <div id="itemssxN2" class="nn_nrrong">
                                                <ul class="ui-choose" id="uc_2">
                                                    <li @if($point3_bottom=="董監事") class="selected" @endif>董監事</li>
                                                    <li @if($point3_bottom=="大股東") class="selected" @endif>大股東</li>
                                                    <input type="text" id="uc_2_point3" class="hidden" name="point[2][bottom]" value="@if(isset($vvipInfo)){{$point3_bottom}}@endif">
                                                </ul>

                                            </div>
                                        </dd>
                                    </div>
                                    <div class="xl_stiname matop10">
                                        <dt class="nn_shoc @if($point4=="出手大方") nn_shoc_h @endif" data-id="point4"><img src="/new/images/zhy_2.png" class="x_left">出手大方<input type="checkbox" id="point4" class="hidden" name="point[3][top]" value="出手大方" @if($point4=="出手大方") checked="checked" @endif></dt>
                                        <dd style="display:@if($point4=="出手大方")block @else none @endif ;" class="shous">
                                            <div class="nn_nrrong_2">
                                                <font class="left">選擇此標籤日後如有爭議，站方將使用更多的保證金解決問題</font>
                                                <div style="width: 100%; display: table;">
                                                    <input type="checkbox" name="point[3][bottom]" style="width: 15px; height: 15px; float: left;" @if($point4_bottom=='on') checked="checked" @endif>
                                                    <span class="margin-r" >我同意</span>
                                                </div>
                                            </div>
                                        </dd>
                                    </div>
                                    <div class="xl_stiname matop10">
                                        <dt class="nn_shoc @if($point5=="品味高雅") nn_shoc_h @endif" data-id="point5"><img src="/new/images/zhy_2.png" class="x_left">品味高雅<input type="checkbox" id="point5" class="hidden" name="point[4][top]" value="品味高雅" @if($point5=="品味高雅") checked="checked" @endif></dt>
                                        <dd style="display:@if($point5=="品味高雅")block @else none @endif ;" class="shous">
                                            <div class="nn_nrrong_2">
                                                <font class="left">選擇此標籤，約見<i class="ds">站方認證過</i>的女會員場合必須為高級餐廳</font>
                                                <div style="width: 100%; display: table;">
                                                    <input type="checkbox" name="point[4][bottom]" style="width: 15px; height: 15px; float: left;" @if($point5_bottom=='on') checked="checked" @endif>
                                                    <span class="margin-r" >我同意</span>
                                                </div>
                                            </div>
                                        </dd>
                                    </div>
                                    <div class="xl_stiname matop10">
                                        <dt class="nn_shoc @if($point6=="人生導師") nn_shoc_h @endif" data-id="point6"><img src="/new/images/zhy_2.png" class="x_left">人生導師<input type="checkbox" id="point6" class="hidden" name="point[5][top]" value="人生導師" @if($point6=="人生導師") checked="checked" @endif></dt>
                                    </div>

                                    <div class="miaoshu">
                                        <h2>若上述皆無符合您的相關描述，可於下方自行輸入</h2>
                                        <div class="input_field_1">
                                            @if(!empty($point_other_array))
                                                @foreach($point_other_array as $key => $value)
                                                    @if($key==0)
                                                        <input type="text" placeholder="請輸入至多20個字" name="point[][top]" class="msinput point" maxlength="20" value="{{$value}}">
                                                    @else
                                                        <div class="custom"><input type="text" placeholder="請輸入至多20個字" name="point[][top]" class="msinput point" maxlength="20" value="{{$value}}"><a href="#" class="remove_field_1"><img src="/new/images/del_03n.png"></a></div>
                                                    @endif
                                                @endforeach
                                            @else
                                            <input type="text" placeholder="請輸入至多20個字" name="point[][top]" class="msinput point" maxlength="20">
                                            @endif
                                        </div>
                                    </div>

                                    <a href="javascript:void(0)" type="button" id="add_image_1" class="ms_xinz" name="button"><img src="/new/images/zhy_1.png">新增</a>
                                </div>



                            </div>

                            <!-- 約會傾向 -->
                            <div class="ziliao_1" style="margin-top: 25px;">
                                <div class="zlsapn_1">二、約會傾向</div>
                                <div class="xl_system system">
                                    <div class="qingx">請選擇您期待的約會傾向</div>
                                    <div id="itemssxN2" class="qi_wi00 matop10">
                                        @php
                                            //fill exist data
                                            $date_trend01 = "";
                                            $date_trend02 = "";
                                            $date_trend03 = "";
                                            $date_trend04 = "";
                                            $date_trend05 = "";
                                            $date_trend06 = "";
                                            $date_trend07 = "";
                                            $date_trend08 = "";
                                            $date_trend09 = "";
                                            $date_trend10 = "";
                                            $date_trend11 = "";
                                            $date_trend12 = "";
                                            $date_trend13 = "";
                                            $date_trend_array = array();

                                            if( isset($vvipInfo) && !empty(json_decode($vvipInfo->date_trend, true)) ){
                                                foreach( json_decode($vvipInfo->date_trend, true) as $key => $value){
                                                    if($value[0]=="節省時間，速戰速決"){
                                                        $date_trend01 = $value[0];
                                                    }
                                                    else if($value[0]=="品嘗美食"){
                                                        $date_trend02 = $value[0];
                                                    }
                                                    else if($value[0]=="戶外旅遊"){
                                                        $date_trend03 = $value[0];
                                                    }
                                                    else if($value[0]=="出國旅遊"){
                                                        $date_trend04 = $value[0];
                                                    }
                                                    else if($value[0]=="高爾夫球敘"){
                                                        $date_trend05 = $value[0];
                                                    }
                                                    else if($value[0]=="商務餐宴"){
                                                        $date_trend06 = $value[0];
                                                    }
                                                    else if($value[0]=="聊天傾訴"){
                                                        $date_trend07 = $value[0];
                                                    }
                                                    else if($value[0]=="安靜陪伴"){
                                                        $date_trend08 = $value[0];
                                                    }
                                                    else if($value[0]=="短暫浪漫"){
                                                        $date_trend09 = $value[0];
                                                    }
                                                    else if($value[0]=="男女朋友"){
                                                        $date_trend10 = $value[0];
                                                    }
                                                    else if($value[0]=="親密關係"){
                                                        $date_trend11 = $value[0];
                                                    }
                                                    else if($value[0]=="商務之旅"){
                                                        $date_trend12 = $value[0];
                                                    }
                                                    else if($value[0]=="固定假日陪伴"){
                                                        $date_trend13 = $value[0];
                                                    }
                                                    else{
                                                        array_push($date_trend_array, $value[0]);
                                                    }
                                                }
                                            }
                                        @endphp
                                        <span class="custom_s a1 left @if($date_trend01=="節省時間，速戰速決") cractive_a @endif" data-id="date_trend01">節省時間，速戰速決<input type="checkbox" class="hidden" id="date_trend01" name="date_trend[]" value="節省時間，速戰速決" @if($date_trend01=="節省時間，速戰速決") checked="checked" @endif></span>
                                        <span class="custom_s a1 left @if($date_trend02=="品嘗美食") cractive_a @endif" data-id="date_trend02">品嘗美食<input type="checkbox" class="hidden" id="date_trend02" name="date_trend[]" value="品嘗美食" @if($date_trend02=="品嘗美食") checked="checked" @endif></span>
                                        <span class="custom_s a1 left @if($date_trend03=="戶外旅遊") cractive_a @endif" data-id="date_trend03">戶外旅遊<input type="checkbox" class="hidden" id="date_trend03" name="date_trend[]" value="戶外旅遊" @if($date_trend03=="戶外旅遊") checked="checked" @endif></span>
                                        <span class="custom_s a1 left @if($date_trend04=="出國旅遊") cractive_a @endif" data-id="date_trend04">出國旅遊<input type="checkbox" class="hidden" id="date_trend04" name="date_trend[]" value="出國旅遊" @if($date_trend04=="出國旅遊") checked="checked" @endif></span>
                                        <span class="custom_s a1 left @if($date_trend05=="高爾夫球敘") cractive_a @endif" data-id="date_trend05">高爾夫球敘<input type="checkbox" class="hidden" id="date_trend05" name="date_trend[]" value="高爾夫球敘" @if($date_trend05=="高爾夫球敘") checked="checked" @endif></span>
                                        <span class="custom_s a1 left @if($date_trend06=="商務餐宴") cractive_a @endif" data-id="date_trend06">商務餐宴<input type="checkbox" class="hidden" id="date_trend06" name="date_trend[]" value="商務餐宴" @if($date_trend06=="商務餐宴") checked="checked" @endif></span>
                                        <span class="custom_s a1 left @if($date_trend07=="聊天傾訴") cractive_a @endif" data-id="date_trend07">聊天傾訴<input type="checkbox" class="hidden" id="date_trend07" name="date_trend[]" value="聊天傾訴" @if($date_trend07=="聊天傾訴") checked="checked" @endif></span>
                                        <span class="custom_s a1 left @if($date_trend08=="安靜陪伴") cractive_a @endif" data-id="date_trend08">安靜陪伴<input type="checkbox" class="hidden" id="date_trend08" name="date_trend[]" value="安靜陪伴" @if($date_trend08=="安靜陪伴") checked="checked" @endif></span>
                                        <span class="custom_s a1 left @if($date_trend09=="短暫浪漫") cractive_a @endif" data-id="date_trend09">短暫浪漫<input type="checkbox" class="hidden" id="date_trend09" name="date_trend[]" value="短暫浪漫" @if($date_trend09=="短暫浪漫") checked="checked" @endif></span>
                                        <span class="custom_s a1 left @if($date_trend10=="男女朋友") cractive_a @endif" data-id="date_trend10">男女朋友<input type="checkbox" class="hidden" id="date_trend10" name="date_trend[]" value="男女朋友" @if($date_trend10=="男女朋友") checked="checked" @endif></span>
                                        <span class="custom_s a1 left @if($date_trend11=="親密關係") cractive_a @endif" data-id="date_trend11">親密關係<input type="checkbox" class="hidden" id="date_trend11" name="date_trend[]" value="親密關係" @if($date_trend11=="親密關係") checked="checked" @endif></span>
                                        <span class="custom_s a1 left @if($date_trend12=="商務之旅") cractive_a @endif" data-id="date_trend12">商務之旅<input type="checkbox" class="hidden" id="date_trend12" name="date_trend[]" value="商務之旅" @if($date_trend12=="商務之旅") checked="checked" @endif></span>
                                        <span class="custom_s a1 left @if($date_trend13=="固定假日陪伴") cractive_a @endif" data-id="date_trend13">固定假日陪伴<input type="checkbox" class="hidden" id="date_trend13" name="date_trend[]" value="固定假日陪伴" @if($date_trend13=="固定假日陪伴") checked="checked" @endif></span>
                                    </div>

                                    <div class="miaoshu" style="margin-top: 5px;">
                                        <h2 style=" padding-top: 0;">若上述皆無符合您的相關描述，可於下方自行輸入</h2>
                                        <div class="input_field_2">
                                            @if(!empty($date_trend_array))
                                                @foreach($date_trend_array as $key => $value)
                                                    @if($key==0)
                                                        <input type="text" placeholder="請輸入至多10個字" class="msinput date_trend" name="date_trend[]" maxlength="10" value="{{$value}}">
                                                        @else
                                                        <div class="custom"><input type="text" placeholder="請輸入至多10個字" class="msinput date_trend" name="date_trend[]" maxlength="10" value="{{$value}}"><a href="#" class="remove_field_1"><img src="/new/images/del_03n.png"></a></div>
                                                        @endif
                                                @endforeach
                                            @else
                                            <input type="text" placeholder="請輸入至多10個字" class="msinput date_trend" name="date_trend[]" maxlength="10">
                                            @endif
                                        </div>
                                    </div>
                                    <a href="javascript:void(0)" type="button" id="add_image_2" class="ms_xinz" name="button"><img src="/new/images/zhy_1.png">新增</a>
                                </div>
                            </div>

                            <!-- 背景與資產 -->
                            <div class="ziliao_1" style="margin-top: 25px;">
                                <div class="zlsapn_1">三、背景與資產<span><img src="/new/images/zhy_5.png">至多選四個</span></div>

                                <div class="xl_system system">
                                    <div class="xl_stiname">
                                        @php
                                            //fill exist data
                                            $assets1 = "";
                                            $assets1_01 = "";
                                            $assets1_02 = "";
                                            $assets1_03 = "";
                                            $assets1_04 = "";
                                            $assets1_05 = "";
                                            $assets1_06 = "";
                                            $assets1_07 = "";
                                            $assets1_08 = "";
                                            $assets1_09 = "";
                                            $assets1_10 = "";
                                            $assets1_11 = "";
                                            $assets2 = "";
                                            $assets2_1_name = "";
                                            $assets2_1_content = "";
                                            $assets2_2_name = "";
                                            $assets2_2_content = "";
                                            $assets2_3_name = "";
                                            $assets2_3_content = "";
                                            $assets3 = "";
                                            $assets3_name = "";
                                            $assets3_content = "";
                                            $assets_array = array();
                                            if( isset($vvipInfo) && !empty(json_decode($vvipInfo->assets, true)) ){
                                                foreach( json_decode($vvipInfo->assets, true) as $key => $value){
                                                    //print_r($value[0]);
                                                    if($value[0]=="專業人士"){
                                                        $assets1 = $value[0];
                                                        if(!empty($value[1]) && is_array($value[1])){
                                                            foreach($value[1] as $assets1_value){
                                                                if($assets1_value=="上市公司高管"){
                                                                    $assets1_01 = $assets1_value;
                                                                }
                                                                else if($assets1_value=="上櫃公司高管"){
                                                                    $assets1_02 = $assets1_value;
                                                                }
                                                                else if($assets1_value=="外商公司高管"){
                                                                    $assets1_03 = $assets1_value;
                                                                }
                                                                else if($assets1_value=="律師"){
                                                                    $assets1_04 = $assets1_value;
                                                                }
                                                                else if($assets1_value=="建築師"){
                                                                    $assets1_05 = $assets1_value;
                                                                }
                                                                else if($assets1_value=="會計師"){
                                                                    $assets1_06 = $assets1_value;
                                                                }
                                                                else if($assets1_value=="政治人物"){
                                                                    $assets1_07 = $assets1_value;
                                                                }
                                                                else if($assets1_value=="機師"){
                                                                    $assets1_08 = $assets1_value;
                                                                }
                                                                else if($assets1_value=="精算師"){
                                                                    $assets1_09 = $assets1_value;
                                                                }
                                                                else if($assets1_value=="工程師"){
                                                                    $assets1_10 = $assets1_value;
                                                                }
                                                                else if($assets1_value=="醫生"){
                                                                    $assets1_11 = $assets1_value;
                                                                }
                                                            }
                                                        }
                                                    }
                                                    else if($value[0]=="高資產人士"){
                                                        $assets2 = $value[0];
                                                        if( !empty($value[1]) && is_array($value[1]) ){
                                                            foreach($value[1] as $assets2_key => $assets2_value){
                                                                if( !isset($assets2_value['content']) ){
                                                                    if($assets2_value[0]=="不動產"){
                                                                        $assets2_1_name = $assets2_value[0];
                                                                        $assets2_1_content = $assets2_value[1];
                                                                    }
                                                                    else if($assets2_value[0]=="有價證券"){
                                                                        $assets2_2_name = $assets2_value[0];
                                                                        $assets2_2_content = $assets2_value[1];
                                                                    }
                                                                    else if($assets2_value[0]=="其他"){
                                                                        $assets2_3_name = $assets2_value[0];
                                                                        $assets2_3_content = $assets2_value[1];
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                    else if($value[0]=="企業家"){
                                                        $assets3 = $value[0];
                                                        if(!empty($value[1]) && is_array($value[1])){
                                                            foreach($value[1] as $assets3_key => $assets3_value){
                                                                if(!empty($assets3_value) && is_array($assets3_value)){
                                                                    if(!empty($assets3_value[0])){
                                                                        $assets3_name = $assets3_value[0];
                                                                    }
                                                                    if(!empty($assets3_value[1])){
                                                                        $assets3_content = $assets3_value[1];
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }else{
                                                        array_push($assets_array, $value[0]);
                                                    }
                                                }
                                            }
                                        @endphp
                                        <dt class="nn_shoc @if($assets1=="專業人士") nn_shoc_h @endif" data-id="assets1"><img src="/new/images/zhy_2.png" class="x_left">專業人士<i>（可複選）</i><input type="checkbox" id="assets1" class="hidden assets" name="assets[0][top]" value="專業人士" @if($assets1=="專業人士") checked="checked" @endif></dt>
                                        <dd style="display:@if($assets1=="專業人士")block @else none @endif ;" class="shous">
                                            <div id="itemssxN3" class="nn_nrrong">
                                                <span class="custom_s a1 left @if($assets1_01=="上市公司高管") cractive_a @endif" data-id="assets1_01">上市公司高管<input type="checkbox" class="hidden" id="assets1_01" name="assets[0][bottom][]" value="上市公司高管" @if($assets1_01=="上市公司高管") checked="checked" @endif></span>
                                                <span class="custom_s a1 left @if($assets1_02=="上櫃公司高管") cractive_a @endif" data-id="assets1_02">上櫃公司高管<input type="checkbox" class="hidden" id="assets1_02" name="assets[0][bottom][]" value="上櫃公司高管" @if($assets1_02=="上櫃公司高管") checked="checked" @endif></span>
                                                <span class="custom_s a1 left @if($assets1_03=="外商公司高管") cractive_a @endif" data-id="assets1_03">外商公司高管<input type="checkbox" class="hidden" id="assets1_03" name="assets[0][bottom][]" value="外商公司高管" @if($assets1_03=="外商公司高管") checked="checked" @endif></span>
                                                <span class="custom_s a1 left @if($assets1_04=="律師") cractive_a @endif" data-id="assets1_04">律師<input type="checkbox" class="hidden" id="assets1_04" name="assets[0][bottom][]" value="律師" @if($assets1_04=="律師") checked="checked" @endif></span>
                                                <span class="custom_s a1 left @if($assets1_05=="建築師") cractive_a @endif" data-id="assets1_05">建築師<input type="checkbox" class="hidden" id="assets1_05" name="assets[0][bottom][]" value="建築師" @if($assets1_05=="建築師") checked="checked" @endif></span>
                                                <span class="custom_s a1 left @if($assets1_06=="會計師") cractive_a @endif" data-id="assets1_06">會計師<input type="checkbox" class="hidden" id="assets1_06" name="assets[0][bottom][]" value="會計師" @if($assets1_06=="會計師") checked="checked" @endif></span>
                                                <span class="custom_s a1 left @if($assets1_07=="政治人物") cractive_a @endif" data-id="assets1_07">政治人物<input type="checkbox" class="hidden" id="assets1_07" name="assets[0][bottom][]" value="政治人物" @if($assets1_07=="政治人物") checked="checked" @endif></span>
                                                <span class="custom_s a1 left @if($assets1_08=="機師") cractive_a @endif" data-id="assets1_08">機師<input type="checkbox" class="hidden" id="assets1_08" name="assets[0][bottom][]" value="機師" @if($assets1_08=="機師") checked="checked" @endif></span>
                                                <span class="custom_s a1 left @if($assets1_09=="精算師") cractive_a @endif" data-id="assets1_09">精算師<input type="checkbox" class="hidden" id="assets1_09" name="assets[0][bottom][]" value="精算師" @if($assets1_09=="精算師") checked="checked" @endif></span>
                                                <span class="custom_s a1 left @if($assets1_10=="工程師") cractive_a @endif" data-id="assets1_10">工程師<input type="checkbox" class="hidden" id="assets1_10" name="assets[0][bottom][]" value="工程師" @if($assets1_10=="工程師") checked="checked" @endif></span>
                                                <span class="custom_s a1 left @if($assets1_11=="醫生") cractive_a @endif" data-id="assets1_11">醫生<input type="checkbox" class="hidden" id="assets1_11" name="assets[0][bottom][]" value="醫生" @if($assets1_11=="醫生") checked="checked" @endif></span>
                                            </div>

                                        </dd>
                                    </div>


                                    <div class="xl_stiname matop10">
                                        <dt class="nn_shoc @if($assets2=="高資產人士") nn_shoc_h @endif" data-id="assets2"><img src="/new/images/zhy_2.png" class="x_left">高資產人士<i>（可複選）</i><input type="checkbox" id="assets2" class="hidden assets" name="assets[1][top]" value="高資產人士" @if($assets2=="高資產人士") checked="checked" @endif></dt>
                                        <dd style="display:@if($assets2=="高資產人士")block @else none @endif ;" class="shous">
                                            <div id="itemssxN4" class="nn_nrrong">
                                                <div style="width: 100%; position: relative;">
                                                    <div style="width: 100%; display: table;"><span class="custom_s a1 @if($assets2_1_name=="不動產") cractive_a @endif" data-id="assets2_1">不動產</span><input type="checkbox" class="hidden" id="assets2_1" name="assets[1][bottom][0][name]" value="不動產" @if($assets2_1_name=="不動產") checked="checked" @endif></div>
                                                    <div style="width: 100%; @if($assets2_1_name=="不動產")display:block; @endif"  class="cr_b gk01">
                                                        <div id="js-example-change-value" style="margin-bottom:20px; margin-top:3px;">
                                                            <input type="range" min="0" max="100" value="{{$assets2_1_content}}" data-rangeslider name="assets[1][bottom][0][content]">
                                                            <div class="out"><output></output><span>%</span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="width: 100%; position: relative;">
                                                    <div style="width: 100%; display: table;"><span class="custom_s a2 @if($assets2_2_name=="有價證券") cractive_a @endif" data-id="assets2_2">有價證券</span><input type="checkbox" class="hidden" id="assets2_2" name="assets[1][bottom][1][name]" value="有價證券" @if($assets2_2_name=="有價證券") checked="checked" @endif></div>
                                                    <div style="width: 100%; @if($assets2_2_name=="有價證券")display:block; @endif"  class="cr_b gk02">
                                                        <div id="js-example-destroy"  style="margin-bottom: 15px; margin-top:3px;">
                                                            <input type="range" min="0" max="100" value="{{$assets2_2_content}}" data-rangeslider name="assets[1][bottom][1][content]">
                                                            <div class="out"><output></output><span>%</span></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div style="width: 100%; position: relative;">
                                                    <div style="width: 100%; display: table;"><span class="custom_s a3 @if($assets2_3_name=="其他") cractive_a @endif" data-id="assets2_3">其他</span><input type="checkbox" class="hidden" id="assets2_3" name="assets[1][bottom][2][name]" value="其他" @if($assets2_3_name=="其他") checked="checked" @endif></div>
                                                    <div style="width: 100%; @if($assets2_3_name=="其他")display:block; @endif"  class="cr_b gk03">
                                                        <div id="js-example-destroy"  style="margin-bottom: 10px; margin-top:3px;">
                                                            <input type="range" min="0" max="100" value="{{$assets2_3_content}}" data-rangeslider name="assets[1][bottom][2][content]">
                                                            <div class="out"><output></output><span>%</span></div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>


                                        </dd>
                                    </div>
                                    <div class="xl_stiname matop10">
                                        <dt class="nn_shoc @if($assets3=="企業家") nn_shoc_h @endif" data-id="assets3"><img src="/new/images/zhy_2.png" class="x_left">企業家<input type="checkbox" id="assets3" class="hidden assets" name="assets[2][top]" value="企業家" @if($assets3=="企業家") checked="checked" @endif></dt>
                                        <dd style="display:@if($assets3=="企業家")block @else none @endif ;" class="shous">
                                            <div id="itemssxN5" class="nn_nrrong">
                                                <div class="cr_ad">-先選企業規模</div>
                                                <ul class="ui-choose" id="uc_3">
                                                    <li id="xs1" class="xs1 @if($assets3_name=="大型企業：員工超過200人以上") selected @endif">大型企業<b class="cr_b cr1 cr_b1" @if($assets3_name=="大型企業：員工超過200人以上") style="display: block;" @endif>：員工超過200人以上</b></li>
                                                    <li id="xs2" class="xs2 @if($assets3_name=="中型企業：員工超過100人以上") selected @endif">中型企業<b class="cr_b cr2 cr_b2" @if($assets3_name=="中型企業：員工超過100人以上") style="display: block;" @endif>：員工超過100人以上</b></li>
                                                    <li id="xs3" class="xs3 @if($assets3_name=="小型企業：員工未達100人") selected @endif">小型企業<b class="cr_b cr3 cr_b3" @if($assets3_name=="小型企業：員工未達100人") style="display: block;" @endif>：員工未達100人</b></li>
                                                    <li id="xs4" class="xs4 @if($assets3_name=="高收入企業：年營業額超過一億以上") selected @endif">高收入企業<b class="cr_b cr4 cr_b4" @if($assets3_name=="高收入企業：年營業額超過一億以上") style="display: block;" @endif>：年營業額超過一億以上</b></li>
                                                </ul>
                                                <input type="text" id="assets3_1" class="hidden" name="assets[2][bottom][0][name]" value="{{$assets3_name}}">


                                                <div class="cr_ad">-選擇身份</div>
                                                <ul class="ui-choose" id="uc_4">
                                                    <li @if($assets3_content=="負責人") class="selected" @endif>負責人</li>
                                                    <li @if($assets3_content=="大股東") class="selected" @endif>大股東</li>
                                                    <li @if($assets3_content=="董監事") class="selected" @endif>董監事</li>
                                                </ul>
                                                <input type="text" id="assets3_2" class="hidden" name="assets[2][bottom][0][content]" value="{{$assets3_content}}">


                                            </div>
                                        </dd>
                                    </div>
                                    <div class="miaoshu">
                                        <h2>若上述皆無符合您的相關描述，可於下方自行輸入</h2>
                                        <div class="input_field_3">
                                            @if(!empty($assets_array))
                                                @foreach($assets_array as $key => $value)
                                                    @if($key==0)
                                                        <input type="text" placeholder="請輸入至多20個字" class="msinput assets" maxlength="20" name="assets[][top]" value="{{$value}}">
                                                    @else
                                                        <div class="custom"><input type="text" placeholder="請輸入至多20個字" class="msinput assets" maxlength="20" name="assets[][top]" value="{{$value}}"><a href="#" class="remove_field_1"><img src="/new/images/del_03n.png"></a></div>
                                                    @endif
                                                @endforeach
                                            @else
                                            <input type="text" placeholder="請輸入至多20個字" class="msinput assets" maxlength="20" name="assets[][top]">
                                            @endif
                                        </div>
                                    </div>
                                    <a href="javascript:void(0)" type="button" id="add_image_3" class="ms_xinz" name="button"><img src="/new/images/zhy_1.png">新增</a>
                                </div>



                            </div>
                            <!-- 背景與資產  -->

                            <!-- 四、額外照顧 -->
                            <div class="ziliao_1" style="margin-top: 25px;">
                                <div class="zlsapn_1">四、額外照顧<span><img src="/new/images/zhy_5.png">至多選四個</span></div>

                                <div class="xl_system system">
                                    <div class="xl_stiname">
                                        @php
                                            //fill exist data
                                            $extra_care1 = "";
                                            $extra_care1_01 = "";
                                            $extra_care1_02 = "";
                                            $extra_care1_03 = "";
                                            $extra_care1_04 = "";
                                            $extra_care1_05 = "";
                                            $extra_care1_06 = "";
                                            $extra_care1_07 = "";
                                            $extra_care1_other = "";
                                            $extra_care1_level = "";
                                            $extra_care2 = "";
                                            $extra_care2_01 = "";
                                            $extra_care2_02 = "";
                                            $extra_care2_03 = "";
                                            $extra_care3 = "";
                                            $extra_care3_01 = "";
                                            $extra_care3_02 = "";
                                            $extra_care3_03 = "";
                                            $extra_care3_array = array();
                                            if( isset($vvipInfo) && !empty(json_decode($vvipInfo->extra_care, true)) ){
                                                foreach( json_decode($vvipInfo->extra_care, true) as $key => $value){
                                                    if($value[0]=="專業人脈"){
                                                        $extra_care1 = $value[0];
                                                        //print_r($value[2]);
                                                        if(!empty($value[1]) && is_array($value[1])){
                                                            foreach($value[1] as $extra_care1_key => $extra_care1_value){
                                                                //print_r($extra_care1_value[0]);
                                                                if($extra_care1_value[0]=="金融"){
                                                                    $extra_care1_01 = $extra_care1_value[0];
                                                                }
                                                                else if($extra_care1_value[0]=="法律"){
                                                                    $extra_care1_02 = $extra_care1_value[0];
                                                                }
                                                                else if($extra_care1_value[0]=="電子"){
                                                                    $extra_care1_03 = $extra_care1_value[0];
                                                                }
                                                                else if($extra_care1_value[0]=="影劇"){
                                                                    $extra_care1_04 = $extra_care1_value[0];
                                                                }
                                                                else if($extra_care1_value[0]=="醫療"){
                                                                    $extra_care1_05 = $extra_care1_value[0];
                                                                }
                                                                else if($extra_care1_value[0]=="公關"){
                                                                    $extra_care1_06 = $extra_care1_value[0];
                                                                }
                                                                else if($extra_care1_value[0]=="能源"){
                                                                    $extra_care1_07 = $extra_care1_value[0];
                                                                }else{
                                                                    $extra_care1_other = $extra_care1_value[0];
                                                                }
                                                            }
                                                        }
                                                        if(isset($value[2]) && $value[2] != ''){
                                                            $extra_care1_level = $value[2];
                                                        }
                                                    }
                                                    else if($value[0]=="生活照顧"){
                                                        $extra_care2 = $value[0];
                                                        if(!empty($value[1]) && is_array($value[1])){
                                                            foreach($value[1] as $extra_care2_key => $extra_care2_value){
                                                                //print_r($extra_care1_value[0]);
                                                                if($extra_care2_value[0]=="安排住宿"){
                                                                    $extra_care2_01 = $extra_care2_value[0];
                                                                }
                                                                else if($extra_care2_value[0]=="出遊接送"){
                                                                    $extra_care2_02 = $extra_care2_value[0];
                                                                }
                                                                else if($extra_care2_value[0]=="聊天陪伴"){
                                                                    $extra_care2_03 = $extra_care2_value[0];
                                                                }
                                                            }
                                                        }
                                                    }
                                                    else if($value[0]=="特殊問題處理"){
                                                        $extra_care3 = $value[0];
                                                        if(!empty($value[1]) && is_array($value[1])){
                                                            foreach($value[1] as $extra_care3_key => $extra_care3_value){
                                                                //print_r($extra_care1_value[0]);
                                                                if($extra_care3_value[0]=="債務問題處理"){
                                                                    $extra_care3_01 = $extra_care3_value[0];
                                                                }
                                                                else if($extra_care3_value[0]=="就學/留學幫助"){
                                                                    $extra_care3_02 = $extra_care3_value[0];
                                                                }
                                                                else if($extra_care3_value[0]=="醫療問題協助"){
                                                                    $extra_care3_03 = $extra_care3_value[0];
                                                                }
                                                            }
                                                        }
                                                    }else{
                                                        array_push($extra_care3_array, $value[0]);
                                                    }

                                                }
                                            }
                                        @endphp
                                        <dt class="nn_shoc @if($extra_care1=="專業人脈") nn_shoc_h @endif" data-id="extra_care1"><img src="/new/images/zhy_2.png" class="x_left">專業人脈<input type="checkbox" id="extra_care1" class="hidden extra_care" name="extra_care[0][top]" value="專業人脈" @if($extra_care1=="專業人脈") checked="checked" @endif></dt>
                                        <dd style="display:@if($extra_care1=="專業人脈")block @else none @endif ;" class="shous">
                                            <div id="itemssxN6" class="nn_nrrong">
                                                <span class="custom_s a1 left @if($extra_care1_01=="金融") cractive_a @endif" data-id="extra_care1_01">金融<input type="checkbox" class="hidden" id="extra_care1_01" name="extra_care[0][bottom][]" value="金融" @if($extra_care1_01=="金融") checked="checked" @endif></span>
                                                <span class="custom_s a1 left @if($extra_care1_02=="法律") cractive_a @endif" data-id="extra_care1_02">法律<input type="checkbox" class="hidden" id="extra_care1_02" name="extra_care[0][bottom][]" value="法律" @if($extra_care1_02=="法律") checked="checked" @endif></span>
                                                <span class="custom_s a1 left @if($extra_care1_03=="電子") cractive_a @endif" data-id="extra_care1_03">電子<input type="checkbox" class="hidden" id="extra_care1_03" name="extra_care[0][bottom][]" value="電子" @if($extra_care1_03=="電子") checked="checked" @endif></span>
                                                <span class="custom_s a1 left @if($extra_care1_04=="影劇") cractive_a @endif" data-id="extra_care1_04">影劇<input type="checkbox" class="hidden" id="extra_care1_04" name="extra_care[0][bottom][]" value="影劇" @if($extra_care1_04=="影劇") checked="checked" @endif></span>
                                                <span class="custom_s a1 left @if($extra_care1_05=="醫療") cractive_a @endif" data-id="extra_care1_05">醫療<input type="checkbox" class="hidden" id="extra_care1_05" name="extra_care[0][bottom][]" value="醫療" @if($extra_care1_05=="醫療") checked="checked" @endif></span>
                                                <span class="custom_s a1 left @if($extra_care1_06=="公關") cractive_a @endif" data-id="extra_care1_06">公關<input type="checkbox" class="hidden" id="extra_care1_06" name="extra_care[0][bottom][]" value="公關" @if($extra_care1_06=="公關") checked="checked" @endif></span>
                                                <span class="custom_s a1 left @if($extra_care1_07=="能源") cractive_a @endif" data-id="extra_care1_07">能源<input type="checkbox" class="hidden" id="extra_care1_07" name="extra_care[0][bottom][]" value="能源" @if($extra_care1_07=="能源") checked="checked" @endif></span>

                                                <input type="text" placeholder="輸入人脈屬性(至多8個中文字)" class="msinput" style=" margin-bottom:10px;" name="extra_care[0][bottom][]" value="{{$extra_care1_other}}" maxlength="8">
                                                <div class="cr_ad">-人脈可用程度：<i class="nn_shoc_r">(必填)</i></div>
                                                <h2 class=""><input id="radio-1" name="extra_care[0][level]" type="radio" style="margin-right:4px;"  value="可視情況幫baby安排實習/正式職務" @if($extra_care1_level=="可視情況幫baby安排實習/正式職務") checked="checked" @endif>高：可視情況幫baby安排實習/正式職務</h2>
                                                <h2 class="us_da_1"><input id="radio-2" name="extra_care[0][level]" type="radio"  style="margin-right:4px;" value="只能提供顧問以及諮詢" @if($extra_care1_level=="只能提供顧問以及諮詢") checked="checked" @endif>低：只能提供顧問以及諮詢</h2>

                                            </div>

                                        </dd>
                                    </div>

                                    <div class="xl_stiname matop10">
                                        <dt class="nn_shoc @if($extra_care2=="生活照顧") nn_shoc_h @endif" data-id="extra_care2"><img src="/new/images/zhy_2.png" class="x_left">生活照顧<i>（可複選）</i><input type="checkbox" id="extra_care2" class="hidden extra_care" name="extra_care[1][top]" value="生活照顧" @if($extra_care2=="生活照顧") checked="checked" @endif></dt>
                                        <dd style="display:@if($extra_care2=="生活照顧")block @else none @endif ;" class="shous">
                                            <div id="itemssxN7" class="nn_nrrong">
                                                <span class="custom_s a1 @if($extra_care2_01=="安排住宿") cractive_a @endif" data-id="extra_care2_01">安排住宿<input type="checkbox" class="hidden" id="extra_care2_01" name="extra_care[1][bottom][]" value="安排住宿" @if($extra_care2_01=="安排住宿") checked="checked" @endif></span>
                                                <span class="custom_s a1 @if($extra_care2_02=="出遊接送") cractive_a @endif" data-id="extra_care2_02">出遊接送<input type="checkbox" class="hidden" id="extra_care2_02" name="extra_care[1][bottom][]" value="出遊接送" @if($extra_care2_02=="出遊接送") checked="checked" @endif></span>
                                                <span class="custom_s a1 @if($extra_care2_03=="聊天陪伴") cractive_a @endif" data-id="extra_care2_03">聊天陪伴<input type="checkbox" class="hidden" id="extra_care2_03" name="extra_care[1][bottom][]" value="聊天陪伴" @if($extra_care2_03=="聊天陪伴") checked="checked" @endif></span>
                                            </div>

                                        </dd>
                                    </div>

                                    <div class="xl_stiname matop10">
                                        <dt class="nn_shoc @if($extra_care3=="特殊問題處理") nn_shoc_h @endif" data-id="extra_care3"><img src="/new/images/zhy_2.png" class="x_left">特殊問題處理<i>（可複選）</i><input type="checkbox" id="extra_care3" class="hidden extra_care" name="extra_care[2][top]" value="特殊問題處理" @if($extra_care3=="特殊問題處理") checked="checked" @endif></dt>
                                        <dd style="display:@if($extra_care3=="特殊問題處理")block @else none @endif;" class="shous">
                                            <div id="itemssxN8" class="nn_nrrong">
                                                <span class="custom_s a1 @if($extra_care3_01=="債務問題處理") cractive_a @endif" data-id="extra_care3_01">債務問題處理<input type="checkbox" class="hidden" id="extra_care3_01" name="extra_care[2][bottom][]" value="債務問題處理" @if($extra_care3_01=="債務問題處理") checked="checked" @endif></span>
                                                <span class="custom_s a1 @if($extra_care3_02=="就學/留學幫助") cractive_a @endif" data-id="extra_care3_02">就學/留學幫助<input type="checkbox" class="hidden" id="extra_care3_02" name="extra_care[2][bottom][]" value="就學/留學幫助" @if($extra_care3_02=="就學/留學幫助") checked="checked" @endif></span>
                                                <span class="custom_s a1 @if($extra_care3_03=="醫療問題協助") cractive_a @endif" data-id="extra_care3_03">醫療問題協助<input type="checkbox" class="hidden" id="extra_care3_03" name="extra_care[2][bottom][]" value="醫療問題協助" @if($extra_care3_03=="醫療問題協助") checked="checked" @endif></span>
                                            </div>

                                        </dd>
                                    </div>

                                    <div class="miaoshu">
                                        <h2>若上述皆無符合您的相關描述，可於下方自行輸入</h2>
                                        <div class="input_field_4">
                                            @if(!empty($extra_care3_array))
                                                @foreach($extra_care3_array as $key => $value)
                                                    @if($key==0)
                                                        <input type="text" placeholder="請輸入至多20個字" class="msinput extra_care" name="extra_care[][top]" maxlength="20" value="{{$value}}">
                                                    @else
                                                        <div class="custom"><input type="text" placeholder="請輸入至多20個字" class="msinput extra_care" maxlength="20" name="extra_care[][top]" value="{{$value}}"><a href="#" class="remove_field_1"><img src="/new/images/del_03n.png"></a></div>
                                                    @endif
                                                @endforeach
                                            @else
                                            <input type="text" placeholder="請輸入至多20個字" class="msinput extra_care" name="extra_care[][top]" maxlength="20">
                                            @endif
                                        </div>
                                    </div>
                                    <a href="javascript:void(0)" type="button" id="add_image_4" class="ms_xinz" name="button"><img src="/new/images/zhy_1.png">新增</a>
                                </div>



                            </div>
                            <!-- 額外照顧  -->

                            <!-- 五、您的財富資產 -->
                            <div class="ziliao_1" style="margin-top: 25px;">
                                <div class="zlsapn_1">五、您的財富資產<span><img src="/new/images/zhy_5.png">至多選四個</span></div>

                                <div class="xl_system system">
                                    @php
                                        //fill exist data
                                        //dd(json_decode($vvipInfo->assets_image, true));
                                    @endphp
                                    <div class="qingx">請輸入您最象徵性的財富資產。Ex. ROLEX名錶、獨棟豪宅、BMW/賓士等各式名車等</div>
                                    <div class="red">※優先使用上傳圖檔，如已選擇系統圖片將不會被存取</div>
                                    <div class="miaoshu">
                                        <div class="input_field_5 matop10">
                                            @if(isset($vvipInfo) && !empty(json_decode($vvipInfo->assets_image, true)))
                                                @foreach( json_decode($vvipInfo->assets_image, true) as $key => $value)
                                                    @if($key>0)
                                                        <div class="custom matop10">
                                                    @endif
                                                        <input type="text" placeholder="請輸入至多18個字" class="msinput assets_image_top" name="assets_image[{{$key}}][top]" maxlength="18" value="{{$value[0]}}">
                                                        <ul class="n_ulpic" style="margin-bottom: 0;">
                                                            <input type="file" id="assets_image_{{$key+1}}" name="assets_image_{{$key}}" class="files assets_image"  data-fileuploader-listInput="assets_image_{{$key}}"
                                                                   @if(is_array($value[1]) && isset($value[1][0]) && !is_null($value[1][0]))
                                                                       @if(file_exists(public_path($value[1][0])))
                                                                           @php
                                                                               $preloadedFiles = array();
                                                                               $file = explode('/', $value[1][0]);
                                                                               $file = end($file);
                                                                               $type = explode('.', $file);
                                                                               $type = end($type);
                                                                                $preloadedFiles[] = array(
                                                                                    "name" => $file,
                                                                                    "type" => $type,
                                                                                    "size" => filesize(public_path($value[1][0])),
                                                                                    "file" => $value[1][0],
                                                                                    "local" => $value[1][0],
                                                                                    "data" => array(
                                                                                        "url" => $value[1][0],
                                                                                        "thumbnail" => $value[1][0],
                                                                                        "readerForce" => true
                                                                                    ),
                                                                                );
                                                                            $preloadedFiles = json_encode($preloadedFiles);
                                                                            @endphp
                                                                       data-fileuploader-files="{{$preloadedFiles}}"
                                                                        @endif
                                                                    @endif
                                                            >
                                                        </ul>
                                                    @if($key>0)
                                                        <a href="#" class="remove_field_2"><img src="/new/images/del_03n.png"></a></div>
                                                    @endif
                                                @endforeach
                                            @else
                                            <input type="text" placeholder="請輸入至多18個字" class="msinput assets_image_top" name="assets_image[0][top]" maxlength="18">
                                            <ul class="n_ulpic" style="margin-bottom: 0;">

                                                <input type="file" id="assets_image_1" name="assets_image_0" class="files assets_image" data-fileuploader-listInput="assets_image_0" data-fileuploader-files="">
                                            </ul>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="javascript:void(0)" type="button" id="add_image_5" class="ms_xinz mabot_20 matop20" name="button"><img src="/new/images/zhy_1.png">新增</a>

                                    <div class="dt_izhaopian">
                                        <h2><i></i>您也可以直接選擇系統預設的照片<font>不滿意？往右滑換一組！</font></h2>

                                        <div style="width:100%; margin: 0 auto;">
                                            <div class="swiper-container wip01">
                                                <div class="swiper-wrapper">
                                                    <div class="swiper-slide sild">
                                                        <div class="cbg_ont"><img src="/new/images/zz_1.png" data-id="assets_image" data-input="input_field_5"></div>
                                                    </div>
                                                    <div class="swiper-slide sild">
                                                        <div class="cbg_ont"><img src="/new/images/zz_2.png" data-id="assets_image" data-input="input_field_5"></div>
                                                    </div>
                                                    <div class="swiper-slide sild">
                                                        <div class="cbg_ont"><img src="/new/images/zz_3.png" data-id="assets_image" data-input="input_field_5"></div>
                                                    </div>
                                                    <div class="swiper-slide sild">
                                                        <div class="cbg_ont"><img src="/new/images/zz_4.png" data-id="assets_image" data-input="input_field_5"></div>
                                                    </div>
                                                    <div class="swiper-slide sild">
                                                        <div class="cbg_ont"><img src="/new/images/zz_5.png" data-id="assets_image" data-input="input_field_5"></div>
                                                    </div>
{{--                                                    <div class="swiper-slide sild">--}}
{{--                                                        <div class="cbg_ont"><img src="/new/images/zz_1.png" data-id="assets_image" data-input="add_image_5"></div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="swiper-slide sild">--}}
{{--                                                        <div class="cbg_ont"><img src="/new/images/zz_2.png" data-id="assets_image" data-input="add_image_5"></div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="swiper-slide sild">--}}
{{--                                                        <div class="cbg_ont"><img src="/new/images/zz_3.png" data-id="assets_image" data-input="add_image_5"></div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="swiper-slide sild">--}}
{{--                                                        <div class="cbg_ont"><img src="/new/images/zz_4.png" data-id="assets_image" data-input="add_image_5"></div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="swiper-slide sild">--}}
{{--                                                        <div class="cbg_ont"><img src="/new/images/zz_5.png" data-id="assets_image" data-input="add_image_5"></div>--}}
{{--                                                    </div>--}}
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
                                <div class="xl_system system">
                                    <div class="qingx">輸入您喜愛的生活體驗或事物。Ex. 喜愛的餐廳：高級日式料理、出國旅遊、休閒娛樂：打高爾夫等。</div>
                                    <div class="red">※優先使用上傳圖檔，如已選擇系統圖片將不會被存取</div>
                                    <div class="miaoshu">
                                        <div class="input_field_6 matop10">
                                            @if(isset($vvipInfo) && !empty(json_decode($vvipInfo->life, true)))
                                                @foreach( json_decode($vvipInfo->life, true) as $key => $value)
                                                    @if($key>0)
                                                    <div class="custom matop10">
                                                    @endif
                                                    <input type="text" placeholder="請輸入至多18個字" class="msinput life_top" name="life[{{$key}}][top]" maxlength="18" value="{{$value[0]}}">
                                                    <ul class="n_ulpic" style="margin-bottom: 0;">
                                                        <input type="file" id="life_{{$key+1}}" name="life_{{$key}}" class="files life" data-fileuploader-listInput="life_{{$key}}"
                                                               @if(is_array($value[1]) && isset($value[1][0]) && !is_null($value[1][0]))
                                                                   @if(file_exists(public_path($value[1][0])))
                                                                       @php
                                                                           $preloadedFiles = array();
                                                                           $file = explode('/', $value[1][0]);
                                                                           $file = end($file);
                                                                           $type = explode('.', $file);
                                                                           $type = end($type);
                                                                            $preloadedFiles[] = array(
                                                                                "name" => $file,
                                                                                "type" => $type,
                                                                                "size" => filesize(public_path($value[1][0])),
                                                                                "file" => $value[1][0],
                                                                                "local" => $value[1][0],
                                                                                "data" => array(
                                                                                    "url" => $value[1][0],
                                                                                    "thumbnail" => $value[1][0],
                                                                                    "readerForce" => true
                                                                                ),
                                                                            );
                                                                        $preloadedFiles = json_encode($preloadedFiles);
                                                                       @endphp
                                                                   data-fileuploader-files="{{$preloadedFiles}}"
                                                                    @endif
                                                                @endif
                                                        >
{{--                                                        <input type="hidden" name="life[{{$key}}][preloadedFiles]" value="{{$preloadedFiles}}">--}}
                                                    </ul>
                                                    @if($key>0)
                                                    <a href="#" class="remove_field_2"><img src="/new/images/del_03n.png"></a></div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <input type="text" placeholder="請輸入至多18個字" class="msinput life_top" name="life[0][top]" maxlength="18">
                                                <ul class="n_ulpic" style="margin-bottom: 0;">
                                                    <input type="file" id="life_1" name="life_0" class="files life" data-fileuploader-listInput="life_0" data-fileuploader-files="">
                                                </ul>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="javascript:void(0)" type="button" id="add_image_6" class="ms_xinz mabot_20 matop20" name="button"><img src="/new/images/zhy_1.png">新增</a>

                                    <div class="dt_izhaopian">
                                        <h2><i></i>您也可以直接選擇系統預設的照片<font>不滿意？往右滑換一組！</font></h2>
                                        <div style="width:100%; margin: 0 auto;">
                                            <div class="swiper-container wip02">
                                                <div class="swiper-wrapper">
                                                    <div class="swiper-slide sild">
                                                        <div class="cbg_ont"><img src="/new/images/zz_1.png" data-id="life" data-input="input_field_6"></div>
                                                    </div>
                                                    <div class="swiper-slide sild">
                                                        <div class="cbg_ont"><img src="/new/images/zz_2.png" data-id="life" data-input="input_field_6"></div>
                                                    </div>
                                                    <div class="swiper-slide sild">
                                                        <div class="cbg_ont"><img src="/new/images/zz_3.png" data-id="life" data-input="input_field_6"></div>
                                                    </div>
                                                    <div class="swiper-slide sild">
                                                        <div class="cbg_ont"><img src="/new/images/zz_4.png" data-id="life" data-input="input_field_6"></div>
                                                    </div>
                                                    <div class="swiper-slide sild">
                                                        <div class="cbg_ont"><img src="/new/images/zz_5.png" data-id="life" data-input="input_field_6"></div>
                                                    </div>
                                                    <div class="swiper-slide sild">
                                                        <div class="cbg_ont"><img src="/new/images/zb_9.png" data-id="life" data-input="input_field_6"></div>
                                                    </div>
                                                    <div class="swiper-slide sild">
                                                        <div class="cbg_ont"><img src="/new/images/zb_10.png" data-id="life" data-input="input_field_6"></div>
                                                    </div>
{{--                                                    <div class="swiper-slide sild">--}}
{{--                                                        <div class="cbg_ont"><img src="/new/images/zz_1.png" data-id="life" data-input="add_image_6"></div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="swiper-slide sild">--}}
{{--                                                        <div class="cbg_ont"><img src="/new/images/zz_2.png" data-id="life" data-input="add_image_6"></div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="swiper-slide sild">--}}
{{--                                                        <div class="cbg_ont"><img src="/new/images/zz_3.png" data-id="life" data-input="add_image_6"></div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="swiper-slide sild">--}}
{{--                                                        <div class="cbg_ont"><img src="/new/images/zz_4.png" data-id="life" data-input="add_image_6"></div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="swiper-slide sild">--}}
{{--                                                        <div class="cbg_ont"><img src="/new/images/zz_5.png" data-id="life" data-input="add_image_6"></div>--}}
{{--                                                    </div>--}}
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
                                        @php
                                            //fill exist data
                                            $date_expect01 = "";
                                            $date_expect02 = "";
                                            $date_expect03 = "";
                                            $date_expect04 = "";
                                            $date_expect05 = "";
                                            $date_expect06 = "";
                                            $date_expect07 = "";
                                            $date_expect08 = "";
                                            $date_expect09 = "";
                                            $date_expect10 = "";
                                            $date_expect11 = "";
                                            $date_expect12 = "";
                                            $date_expect13 = "";
                                            $date_expect_array = array();

                                            if( isset($vvipInfo) && !empty(json_decode($vvipInfo->date_expect, true)) ){
                                                foreach( json_decode($vvipInfo->date_expect, true) as $key => $value){
                                                    if($value[0]=="節省時間，速戰速決"){
                                                        $date_expect01 = $value[0];
                                                    }
                                                    else if($value[0]=="品嘗美食"){
                                                        $date_expect02 = $value[0];
                                                    }
                                                    else if($value[0]=="戶外旅遊"){
                                                        $date_expect03 = $value[0];
                                                    }
                                                    else if($value[0]=="高爾夫球敘"){
                                                        $date_expect04 = $value[0];
                                                    }
                                                    else if($value[0]=="商務餐宴"){
                                                        $date_expect05 = $value[0];
                                                    }
                                                    else if($value[0]=="聊天傾訴"){
                                                        $date_expect06 = $value[0];
                                                    }
                                                    else if($value[0]=="安靜陪伴"){
                                                        $date_expect07 = $value[0];
                                                    }
                                                    else if($value[0]=="短暫浪漫"){
                                                        $date_expect08 = $value[0];
                                                    }
                                                    else if($value[0]=="男女朋友"){
                                                        $date_expect09 = $value[0];
                                                    }
                                                    else if($value[0]=="親密關係"){
                                                        $date_expect10 = $value[0];
                                                    }
                                                    else if($value[0]=="商務之旅"){
                                                        $date_expect11 = $value[0];
                                                    }
                                                    else if($value[0]=="固定假日陪伴"){
                                                        $date_expect12 = $value[0];
                                                    }
                                                    else{
                                                        array_push($date_expect_array, $value[0]);
                                                    }
                                                }
                                            }
                                        @endphp
                                        <span class="custom_s a1 left @if($date_expect01=="節省時間，速戰速決") cractive_a @endif" data-id="date_expect01">節省時間，速戰速決<input type="checkbox" class="hidden date_expect" id="date_expect01" name="date_expect[]" value="節省時間，速戰速決" @if($date_expect01=="節省時間，速戰速決") checked="checked" @endif></span>
                                        <span class="custom_s a1 left @if($date_expect02=="品嘗美食") cractive_a @endif" data-id="date_expect02">品嘗美食<input type="checkbox" class="hidden date_expect" id="date_expect02" name="date_expect[]" value="品嘗美食" @if($date_expect02=="品嘗美食") checked="checked" @endif></span>
                                        <span class="custom_s a1 left @if($date_expect03=="戶外旅遊") cractive_a @endif" data-id="date_expect03">戶外旅遊<input type="checkbox" class="hidden date_expect" id="date_expect03" name="date_expect[]" value="戶外旅遊" @if($date_expect03=="戶外旅遊") checked="checked" @endif></span>
                                        <span class="custom_s a1 left @if($date_expect04=="高爾夫球敘") cractive_a @endif" data-id="date_expect04">高爾夫球敘<input type="checkbox" class="hidden date_expect" id="date_expect04" name="date_expect[]" value="高爾夫球敘" @if($date_expect04=="高爾夫球敘") checked="checked" @endif></span>
                                        <span class="custom_s a1 left @if($date_expect05=="商務餐宴") cractive_a @endif" data-id="date_expect05">商務餐宴<input type="checkbox" class="hidden date_expect" id="date_expect05" name="date_expect[]" value="商務餐宴" @if($date_expect05=="商務餐宴") checked="checked" @endif></span>
                                        <span class="custom_s a1 left @if($date_expect06=="聊天傾訴") cractive_a @endif" data-id="date_expect06">聊天傾訴<input type="checkbox" class="hidden date_expect" id="date_expect06" name="date_expect[]" value="聊天傾訴" @if($date_expect06=="商務餐宴") checked="checked" @endif></span>
                                        <span class="custom_s a1 left @if($date_expect07=="安靜陪伴") cractive_a @endif" data-id="date_expect07">安靜陪伴<input type="checkbox" class="hidden date_expect" id="date_expect07" name="date_expect[]" value="安靜陪伴" @if($date_expect07=="商務餐宴") checked="checked" @endif></span>
                                        <span class="custom_s a1 left @if($date_expect08=="短暫浪漫") cractive_a @endif" data-id="date_expect08">短暫浪漫<input type="checkbox" class="hidden date_expect" id="date_expect08" name="date_expect[]" value="短暫浪漫" @if($date_expect08=="商務餐宴") checked="checked" @endif></span>
                                        <span class="custom_s a1 left @if($date_expect09=="男女朋友") cractive_a @endif" data-id="date_expect09">男女朋友<input type="checkbox" class="hidden date_expect" id="date_expect09" name="date_expect[]" value="男女朋友" @if($date_expect09=="商務餐宴") checked="checked" @endif></span>
                                        <span class="custom_s a1 left @if($date_expect10=="親密關係") cractive_a @endif" data-id="date_expect10">親密關係<input type="checkbox" class="hidden date_expect" id="date_expect10" name="date_expect[]" value="親密關係" @if($date_expect10=="商務餐宴") checked="checked" @endif></span>
                                        <span class="custom_s a1 left @if($date_expect11=="商務之旅") cractive_a @endif" data-id="date_expect11">商務之旅<input type="checkbox" class="hidden date_expect" id="date_expect11" name="date_expect[]" value="商務之旅" @if($date_expect11=="商務餐宴") checked="checked" @endif></span>
                                        <span class="custom_s a1 left @if($date_expect12=="固定假日陪伴") cractive_a @endif" data-id="date_expect12">固定假日陪伴<input type="checkbox" class="hidden date_expect" id="date_expect12" name="date_expect[]" value="固定假日陪伴" @if($date_expect12=="商務餐宴") checked="checked" @endif></span>
                                    </div>

                                    <div class="miaoshu matop10">
                                        <div class="input_field_7">
                                            @if(!empty($date_expect_array))
                                                @foreach($date_expect_array as $key => $value)
                                                    @if($key==0)
                                                        <input type="text" placeholder="請輸入至多10個字" class="msinput date_expect date_expect_text" name="date_expect[]" maxlength="10" value="{{$value}}">
                                                    @else
                                                        <div class="custom"><input type="text" placeholder="請輸入至多10個字" class="msinput date_expect date_expect_text" name="date_expect[]" maxlength="10" value="{{$value}}"><a href="#" class="remove_field_1"><img src="/new/images/del_03n.png"></a></div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <input type="text" placeholder="請輸入至多10個字" class="msinput date_expect date_expect_text" name="date_expect[]" maxlength="10">
                                            @endif
                                        </div>
                                    </div>
                                    <a href="javascript:void(0)" type="button" id="add_image_7" class="ms_xinz" name="button"><img src="/new/images/zhy_1.png">新增</a>

                                </div>
                            </div>
                            <!-- 七、期待的約會模式 -->

                            <div class="n_txbut matop40">
                                <a class="n_dlbut vvipInfo_submit">送出</a>
{{--                                <input type="submit" class="n_dlbut" value="送出">--}}
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

    <script>
        $('.vvipInfo_submit').on('click',function (e) {
            $('#form_vvipEdit').submit();
        });
    </script>

    <script src="/new/js/swiper.min.js"></script>
    <script>
        var swiper01 = new Swiper('.wip01', {
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



    <script src="/new/js/ui-choose.js"></script>
    <script>
        $('.ui-choose').ui_choose();
        let uc_01 = $('#uc_1').data('ui-choose');
        uc_01.click = function(index, item) {
            // alert(item.text());
            console.log('click', index, item.text());
            console.log('change', index, item.text());
            $('#uc_1_point1').val(item.text());
            $('#uc_1_point1_input').val('');
        };
        uc_01.change = function(index, item) {
            console.log('change', index, item.text());
        };

        $('#uc_1_point1_input').on("click", function () {
            //alert(1);
            // $('#uc_1_point1').val('');
            // $('#uc_1 li').removeClass( "selected" );
        });
        $('#uc_1_point1_input').on("keyup", function () {
            $('#uc_1_point1').val('');
            $('#uc_1 li').removeClass( "selected" );
            $('#uc_1_point1').val($('#uc_1_point1_input').val());
        });

        let uc_02 = $('#uc_2').data('ui-choose');
        uc_02.click = function(index, item) {
            console.log('click', index, item.text());
            console.log('change', index, item.text());
            $('#uc_2_point3').val(item.text());
        };
        uc_01.change = function(index, item) {
            console.log('change', index, item.text());
        };

        let uc_3 = $('#uc_3').data('ui-choose');
            uc_3.click = function(index, item) {
            console.log('click', index, item.text());
            console.log('change', index, item.text());
            $('#assets3_1').val(item.text());
        };
        uc_3.change = function(index, item) {
            console.log('change', index, item.text());
        };

        var uc_04 = $('#uc_4').data('ui-choose');
        uc_04.click = function(index, item) {
            console.log('click', index, item.text());
            console.log('change', index, item.text());
            $('#assets3_2').val(item.text());
        };
        uc_04.change = function(index, item) {
            console.log('change', index, item.text());
        };

    </script>

    <script src="/new/js/rangeslider.min.js"></script>
    <script>
        $(function(){
            var $document   = $(document);
            var selector    = '[data-rangeslider]';
            var $inputRange = $(selector);

            // Example functionality to demonstrate a value feedback
            // and change the output's value.
            function valueOutput(element) {
                var value = element.value;
                var output = element.parentNode.getElementsByTagName('output')[0];

                output.innerHTML = value;
            }

            // Initial value output
            for (var i = $inputRange.length - 1; i >= 0; i--) {
                valueOutput($inputRange[i]);
            }

            // Update value output
            $document.on('input', selector, function(e) {
                valueOutput(e.target);
            });

            // Initialize the elements
            $inputRange.rangeslider({
                polyfill: false
            });

            // Example functionality to demonstrate programmatic value changes
            $document.on('click', '#js-example-change-value button', function(e) {
                var $inputRange = $('input[type="range"]', e.target.parentNode);
                var value = $('input[type="number"]', e.target.parentNode)[0].value;

                $inputRange
                    .val(value)
                    .change();
            });

            // Example functionality to demonstrate programmatic attribute changes
            $document.on('click', '#js-example-change-attributes button', function(e) {
                var $inputRange = $('input[type="range"]', e.target.parentNode);
                var attributes = {
                    min: $('input[name="min"]', e.target.parentNode)[0].value,
                    max: $('input[name="max"]', e.target.parentNode)[0].value,
                    step: $('input[name="step"]', e.target.parentNode)[0].value
                };

                $inputRange
                    .attr(attributes)
                    .rangeslider('update', true);
            });

            // Example functionality to demonstrate destroy functionality
            $document
                .on('click', '#js-example-destroy button[data-behaviour="destroy"]', function(e) {
                    $('input[type="range"]', e.target.parentNode).rangeslider('destroy');
                })
                .on('click', '#js-example-destroy button[data-behaviour="initialize"]', function(e) {
                    $('input[type="range"]', e.target.parentNode).rangeslider({ polyfill: false });
                });
        });
    </script>

    <script>

        $(document).ready(function() {

            $("#itemssxN2 .a1").on("click", function () {
                $(this).toggleClass('cractive_a');

                if($(this).hasClass('cractive_a')) {
                    $('#' + $(this).data("id")).attr('checked', true);
                }else{
                    $('#' + $(this).data("id")).attr('checked', false);
                }

            });

            $("#itemssxN3 .a1").on("click", function () {
                $(this).toggleClass('cractive_a');
                if($(this).hasClass('cractive_a')) {
                    $('#' + $(this).data("id")).attr('checked', true);
                }else{
                    $('#' + $(this).data("id")).attr('checked', false);
                }
            });

            $("#itemssxN4 .a1").on("click", function () {
                $(this).toggleClass('cractive_a');
                if($(this).hasClass('cractive_a')) {
                    $('#' + $(this).data("id")).attr('checked', true);
                }else{
                    $('#' + $(this).data("id")).attr('checked', false);
                }
            });

            $("#itemssxN4 .a2").on("click", function () {
                $(this).toggleClass('cractive_a');
                if($(this).hasClass('cractive_a')) {
                    $('#' + $(this).data("id")).attr('checked', true);
                }else{
                    $('#' + $(this).data("id")).attr('checked', false);
                }
            });

            $("#itemssxN4 .a3").on("click", function () {
                $(this).toggleClass('cractive_a');
                if($(this).hasClass('cractive_a')) {
                    $('#' + $(this).data("id")).attr('checked', true);
                }else{
                    $('#' + $(this).data("id")).attr('checked', false);
                }
            });

            $('#itemssxN4 .a1').click(function () {//点击按钮
                if ($('.gk01').is(':hidden')) {//如果当前隐藏
                    $('.gk01').show();//点击显示
                } else {//否则
                    $('.gk01').hide();//点击隐藏
                }
            });

            $('#itemssxN4 .a2').click(function () {//点击按钮
                if ($('.gk02').is(':hidden')) {//如果当前隐藏
                    $('.gk02').show();//点击显示
                } else {//否则
                    $('.gk02').hide();//点击隐藏
                }
            });

            $('#itemssxN4 .a3').click(function () {//点击按钮
                if ($('.gk03').is(':hidden')) {//如果当前隐藏
                    $('.gk03').show();//点击显示
                } else {//否则
                    $('.gk03').hide();//点击隐藏
                }
            });

            $("#itemssxN6 .a1").on("click", function () {
                $(this).toggleClass('cractive_a');
                if($(this).hasClass('cractive_a')) {
                    $('#' + $(this).data("id")).attr('checked', true);
                }else{
                    $('#' + $(this).data("id")).attr('checked', false);
                }
            });

            $("#itemssxN7 .a1").on("click", function () {
                $(this).toggleClass('cractive_a');
                if($(this).hasClass('cractive_a')) {
                    $('#' + $(this).data("id")).attr('checked', true);
                }else{
                    $('#' + $(this).data("id")).attr('checked', false);
                }
            });

            $("#itemssxN8 .a1").on("click", function() {
                $(this).toggleClass('cractive_a');
                if($(this).hasClass('cractive_a')) {
                    $('#' + $(this).data("id")).attr('checked', true);
                }else{
                    $('#' + $(this).data("id")).attr('checked', false);
                }
            });

            $("#itemssxN9 .a1").on("click", function () {
                var cnt = $('.date_expect_text').filter(function() {
                    return this.value.trim() != '';
                }).length;
                if( (cnt + $('.date_expect:checked').length ) >= 4 ){
                    c5('至多選四個');
                    return false;
                }

                $(this).toggleClass('cractive_a');
                if($(this).hasClass('cractive_a')) {
                    $('#' + $(this).data("id")).attr('checked', true);
                }else{
                    $('#' + $(this).data("id")).attr('checked', false);
                }
            });

            $(".date_expect_text").on("keyup", function () {
                var cnt = $('.date_expect_text').filter(function() {
                    return this.value.trim() != '';
                }).length;
                if( (cnt + $('.date_expect:checked').length ) >= 4 ){
                    c5('至多選四個');
                    $(".date_expect_text").val('');
                    return false;
                }
            });

            let max = 4;
            let max_fields = 0;
            let x = 1;

            $("#add_image_1").click(function(e) {
                if($('.point:last').val()==''){
                    c5('您尚未輸入文字');
                }else {
                    e.preventDefault();
                    if (max - max_fields >= x) {
                        x++;
                        $(".input_field_1").append('<div class="custom"><input type="text" placeholder="請輸入至多20個字" name="point[][top]" class="msinput point" maxlength="20"><a href="#" class="remove_field_1"><img src="/new/images/del_03n.png"></a></div>'); //add input box
                    } else {
                        alert('');
                    }
                }
            });
            $(".input_field_1").on("click", ".remove_field_1", function(e) {
                e.preventDefault();
                $(this).parent('div').remove();
                x--;
            });

            $("#add_image_2").click(function(e) {
                if($('.date_trend:last').val()==''){
                    c5('您尚未輸入文字');
                }else {
                    e.preventDefault();
                    if (max - max_fields >= x) {
                        x++;
                        $(".input_field_2").append('<div class="custom"><input type="text" placeholder="請輸入至多10個字" class="msinput date_trend" name="date_trend[]" maxlength="10"><a href="#" class="remove_field_1"><img src="/new/images/del_03n.png"></a></div>'); //add input box
                    } else {
                        alert('');
                    }
                }
            });
            $(".input_field_2").on("click", ".remove_field_1", function(e) {
                e.preventDefault();
                $(this).parent('div').remove();
                x--;
            });

            $("#add_image_3").click(function(e) {

                var cnt = $('input[name="assets[][top]"]').filter(function() {
                    return this.value.trim() != '';
                }).length;
                //alert(cnt);
                if( (cnt + $(".assets:checked").length ) >= 4 ){
                    c5('至多選四個');
                    return false;
                }else if($('.assets:last').val()==''){
                    c5('您尚未輸入文字');
                    return false;
                }else {
                    e.preventDefault();
                    if (max - max_fields >= x) {
                        x++;
                        $(".input_field_3").append('<div class="custom"><input type="text" placeholder="請輸入至多20個字" class="msinput assets" maxlength="20" name="assets[][top]"><a href="#" class="remove_field_1"><img src="/new/images/del_03n.png"></a></div>'); //add input box
                    } else {
                        alert('');
                    }
                }
            });
            $(".input_field_3").on("click", ".remove_field_1", function(e) {
                e.preventDefault();
                $(this).parent('div').remove();
                x--;
            });

            $("#add_image_4").click(function(e) {

                var cnt = $('input[name="extra_care[][top]"]').filter(function() {
                    return this.value.trim() != '';
                }).length;
                //alert(cnt);
                if( (cnt + $(".extra_care:checked").length ) >= 4 ){
                    c5('至多選四個');
                    return false;
                }else if($('.extra_care:last').val()==''){
                    c5('您尚未輸入文字');
                    return false;
                }else {
                    e.preventDefault();
                    if (max - max_fields >= x) {
                        x++;
                        $(".input_field_4").append('<div class="custom"><input type="text" placeholder="請輸入至多20個字" class="msinput extra_care" maxlength="20" name="extra_care[][top]"><a href="#" class="remove_field_1"><img src="/new/images/del_03n.png"></a></div>'); //add input box
                    } else {
                        alert('');
                    }
                }
            });
            $(".input_field_4").on("click", ".remove_field_1", function(e) {
                e.preventDefault();
                $(this).parent('div').remove();
                x--;
            });

            $("#add_image_5").click(function(e) {
                var cnt = $('.assets_image_top').filter(function() {
                    return this.value.trim() != '';
                }).length;

                if( cnt >= 4 ){
                    c5('至多選四個');
                    return false;
                }else if($('.assets_image_top:last').val()==''){
                    c5('您尚未輸入文字');
                    return false;
                }else {
                    var current_name = $('.assets_image:last').attr("name");
                    var current_name_id = current_name.replace(/[^0-9]/ig, "");
                    var current_last_id = parseInt(current_name_id) + 1;
                    var now_last_id = current_last_id + 1;
                    e.preventDefault();
                    if (max - max_fields >= x) {
                        x++;
                        $(".input_field_5").append('<div class="custom matop10">' +
                            '<input type="text" placeholder="請輸入至多18個字" class="msinput assets_image_top" name="assets_image[' + current_last_id + '][top]" maxlength="18">' +
                            '<ul class="n_ulpic" style="margin-bottom: 0;">' +
                            '<input type="file" id="assets_image_' + now_last_id + '" name="assets_image_' + current_last_id + '" class="files assets_image" data-fileuploader-files="" data-fileuploader-listInput="assets_image_' + current_last_id + '">' +
                            '</ul><a href="#" class="remove_field_2"><img src="/new/images/del_03n.png"></a></div>'); //add input box
                        uploaderFunction('assets_image_' + now_last_id);
                    } else {
                        alert('');
                    }
                }
            });

            $('.cbg_ont img').click(function(e) {

                // alert($(this).data('id'));
                var current_name = $('.'+ $(this).data('id') +':last').attr("name");
                var current_name_id = current_name.replace(/[^0-9]/ig,"");
                //alert(current_name_id);
                var current_last_id = current_name_id + 1;

                var current_name_value_array = $("input[name='"+$(this).data('id')+"["+current_name_id+"][sys_images]']").map(function(){return $(this).val();}).get();
                // alert($.inArray($(this).attr('src'), current_name_value_array));
                var album_text = [];
                $('.'+ $(this).data('id') +':last').each(function() {
                    var value = $(this).val();
                    if (value) {
                        album_text.push(value);
                    }
                });
                if(album_text.length > 0){
                    c5('只限一張圖片');
                }
                else if( $.inArray( $(this).attr('src'), current_name_value_array ) >= 0){
                    c5('此圖片您已選取');
                }else {
                    $("." + $(this).data('input') +" .append_custom").remove();
                    $("." + $(this).data('input')).append('<div class="custom matop10 append_custom">' +
                        '<img src="' + $(this).attr('src') + '">' +
                        '<input type="text" name="' + $(this).data('id') + '[' + current_name_id + '][sys_images]" class="hidden" value="' + $(this).attr('src') + '">' +
                        '<a href="#" class="remove_field_2"><img src="/new/images/del_03n.png"></a></div>'); //add input box
                }

            });

            $(".input_field_5").on("click", ".remove_field_2", function(e) {
                e.preventDefault();
                $(this).parent('div').remove();
                x--;
            });

            $("#add_image_6").click(function(e) {
                var cnt = $('.life_top').filter(function() {
                    return this.value.trim() != '';
                }).length;

                if( cnt >= 2 ){
                    c5('至多選兩個');
                    return false;
                }else if($('.life_top:last').val()==''){
                    c5('您尚未輸入文字');
                    return false;
                }else {
                    var current_name = $('.life:last').attr("name");
                    var current_name_id = current_name.replace(/[^0-9]/ig, "");
                    var current_last_id = parseInt(current_name_id) + 1;
                    var now_last_id = current_last_id + 1;
                    e.preventDefault();
                    if (max - max_fields >= x) {
                        x++;
                        $(".input_field_6").append('<div class="custom matop10">' +
                            '<input type="text" placeholder="請輸入至多18個字" class="msinput life_top" name="life[' + current_last_id + '][top]" maxlength="18">' +
                            '<ul class="n_ulpic" style="margin-bottom: 0;">' +
                            '<input type="file" id="life_' + now_last_id + '" name="life_' + current_last_id + '" class="files life" data-fileuploader-files="" data-fileuploader-listInput="life_' + current_last_id + '">' +
                            '</ul><a href="#" class="remove_field_2"><img src="/new/images/del_03n.png"></a></div>'); //add input box
                        uploaderFunction('life_' + now_last_id);
                    } else {
                        alert('');
                    }
                }
            });
            $(".input_field_6").on("click", ".remove_field_2", function(e) {
                e.preventDefault();
                $(this).parent('div').remove();
                x--;
            });

            $("#add_image_7").click(function(e) {
                var cnt = $('.date_expect_text').filter(function() {
                    return this.value.trim() != '';
                }).length;
                //alert(cnt);

                if( (cnt + $('.date_expect:checked').length ) >= 4 ){
                    c5('至多選四個');
                    return false;
                }

                if($('.date_expect:last').val()==''){
                    c5('您尚未輸入文字');
                }else {
                    e.preventDefault();
                    if (max - max_fields >= x) {
                        x++;
                        $(".input_field_7").append('<div class="custom"><input type="text" placeholder="請輸入至多10個字" class="msinput date_expect date_expect_text" name="date_expect[]" maxlength="10"><a href="#" class="remove_field_1"><img src="/new/images/del_03n.png"></a></div>'); //add input box
                    } else {
                        alert('');
                    }
                }
            });
            $(".input_field_7").on("click", ".remove_field_1", function(e) {
                e.preventDefault();
                $(this).parent('div').remove();
                x--;
            });
        });

        $(function(){
            $('.xs1').click(function(){	//点击按钮
                if($('.cr_b1').is(':hidden')){	//如果当前隐藏
                    $('.cr_b1').show();	//点击显示
                    $('.cr_b2').hide();
                    $('.cr_b3').hide();
                    $('.cr_b4').hide();
                    document.getElementById("xs1").style = "";
                }else{	//否则
                    $('.cr_b1').hide();	//点击隐藏
                    document.getElementById("xs1").style.background="#f8f8f8";
                    document.getElementById("xs1").style.color="#888888";
                    document.getElementById("xs1").style.border = "#d2d2d2 1px solid";
                }
            })

            $('.xs2').click(function(){	//点击按钮
                if($('.cr_b2').is(':hidden')){	//如果当前隐藏
                    $('.cr_b2').show();	//点击显示
                    $('.cr_b1').hide();
                    $('.cr_b3').hide();
                    $('.cr_b4').hide();
                    document.getElementById("xs2").style = "";
                }else{	//否则
                    $('.cr_b2').hide();	//点击隐藏
                    document.getElementById("xs2").style.background="#f8f8f8";
                    document.getElementById("xs2").style.color="#888888";
                    document.getElementById("xs2").style.border = "#d2d2d2 1px solid";
                }
            })

            $('.xs3').click(function(){	//点击按钮
                if($('.cr_b3').is(':hidden')){	//如果当前隐藏
                    $('.cr_b3').show();	//点击显示
                    $('.cr_b1').hide();
                    $('.cr_b2').hide();
                    $('.cr_b4').hide();
                    document.getElementById("xs3").style = "";
                    s}else{	//否则
                    $('.cr_b3').hide();	//点击隐藏
                    document.getElementById("xs3").style.background="#f8f8f8";
                    document.getElementById("xs3").style.color="#888888";
                    document.getElementById("xs3").style.border = "#d2d2d2 1px solid";
                }
            })

            $('.xs4').click(function(){	//点击按钮
                if($('.cr_b4').is(':hidden')){	//如果当前隐藏
                    $('.cr_b4').show();	//点击显示
                    $('.cr_b1').hide();
                    $('.cr_b2').hide();
                    $('.cr_b3').hide();
                    document.getElementById("xs4").style = "";
                }else{	//否则
                    $('.cr_b4').hide();	//点击隐藏
                    document.getElementById("xs4").style.background="#f8f8f8";
                    document.getElementById("xs4").style.color="#888888";
                    document.getElementById("xs4").style.border = "#d2d2d2 1px solid";
                }
            })

            $(function() {
                //$(".xl_stiname dd").hide();
                $(".xl_stiname dt").click(function() {

                });
            })

            $('.nn_shoc').click(function(e) {
                $(this).toggleClass('nn_shoc_h');
                $(this).next('dd').slideToggle();
                if($(this).hasClass('nn_shoc_h')) {
                    // alert($(this).data("id"));
                    // $("#point1").is(":checked");
                    $('#' + $(this).data("id")).attr('checked', true);
                    // $('.myCheckbox').attr('checked', true);
                }else{
                    $('#' + $(this).data("id")).attr('checked', false);
                }
            });
        })
    </script>

    <link href="{{ asset('css/jquery.fileuploader.min.css') }}" media="all" rel="stylesheet">
    <link href="{{ asset('new/css/fileupload.css') }}" media="all" rel="stylesheet">
    <link href="{{ asset('css/font/font-fileuploader.css') }}" media="all" rel="stylesheet">
    <script src="{{ asset('js/jquery.fileuploader.js') }}" type="text/javascript"></script>
    <style>
        .fileuploader{
            background-color: unset;
            width: unset !important;
        }
{{--        .fileuploader-theme-thumbnails .fileuploader-items-list .fileuploader-item{--}}
{{--            width: 16.6%;--}}
{{--            height: 136px;--}}
{{--            padding-top: unset;--}}
{{--        }--}}
        // .fileuploader-theme-thumbnails .fileuploader-thumbnails-input-inner, .fileuploader-theme-thumbnails .fileuploader-item-inner {
//             width: 80%;
//             height: 80%;
//         }
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
                // editor cropper
                cropper: {
                    // cropper ratio
                    // example: null
                    // example: '1:1'
                    // example: '16:9'
                    // you can also write your own
                    ratio: null,

                    // cropper minWidth in pixels
                    // size is adjusted with the image natural width
                    minWidth: null,

                    // cropper minHeight in pixels
                    // size is adjusted with the image natural height
                    minHeight: null,

                    // show cropper grid
                    showGrid: true
                },

                // editor on save quality (0 - 100)
                // only for client-side resizing
                quality: 70,

            },
            thumbnails: {
                box: '<div class="fileuploader-items">' +
                    '<ul class="fileuploader-items-list">' +
                    // '<li class="fileuploader-thumbnails-input">' +
                    // '<div class="fileuploader-thumbnails-input-inner"><i>+</i></div>' +
                    // '</li>' +
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
                    // c5("優先使用上傳圖檔，如以選擇系統圖片將不會被存取");
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

        function uploaderFunction(id) {
            $('#'+ id).fileuploader({
                extensions: ['jpg', 'png', 'jpeg'],
                changeInput: '<a class="img dt_heght write_img dt_pa0" style="background: #fff !important; border: #fe92a9 1px solid; height: unset;"><img src="/new/images/shangc_zp.png" class="hycov" style="cursor: pointer;"></a>',
                theme: 'thumbnails',
                enableApi: true,
                addMore: true,
                limit: 1,
                editor: {
                    // editor cropper
                    cropper: {
                        // cropper ratio
                        // example: null
                        // example: '1:1'
                        // example: '16:9'
                        // you can also write your own
                        ratio: null,

                        // cropper minWidth in pixels
                        // size is adjusted with the image natural width
                        minWidth: null,

                        // cropper minHeight in pixels
                        // size is adjusted with the image natural height
                        minHeight: null,

                        // show cropper grid
                        showGrid: true
                    },

                    // editor on save quality (0 - 100)
                    // only for client-side resizing
                    quality: 70,

                },
                thumbnails: {
                    box: '<div class="fileuploader-items">' +
                        '<ul class="fileuploader-items-list">' +
                        // '<li class="fileuploader-thumbnails-input">' +
                        // '<div class="fileuploader-thumbnails-input-inner"><i>+</i></div>' +
                        // '</li>' +
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
                        // c5("優先使用上傳圖檔，如以選擇系統圖片將不會被存取");
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
