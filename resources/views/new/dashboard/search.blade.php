@extends('new.layouts.website')

@section('app-content')
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="shou shou02 sh_line"><span>搜索</span>
                    <font>search</font>
                </div>

                <form action="{!! url('dashboard/search') !!}" method="GET">
                    <input type="hidden" name="_token" value="{{csrf_token()}}" >
                <div class="n_search">
                    <div class="n_input">
                        <dt>
                            <span>地區</span>
                            <span>
                                <span class="twzipcode" id="twzipcode" style="display: inline-flex;">
                                <span class="select_xx07 left" data-role="county" data-name="county" style=""></span>
                                <span class="select_xx07 right" data-role="district" data-name="district" style=""></span>
{{--                                    @if ($user->isVip())--}}
{{--                                        <span class="twzip"><input class="m-input" type="checkbox" id="pic" name="pic"> 照片</span>--}}
{{--                                    @endif--}}
                                </span>
{{--                            <select name="" class="select_xx06"><option>連江縣</option><option>B</option></select>--}}
{{--                            <select name="" class="select_xx06 right"><option>南竿鄉</option><option>B</option></select>--}}
                            </span>
                        </dt>
                        <dt>
                            <span>年齡範圍</span>
                            <span style="display: inline-flex;">
                                <input class="select_xx06" name="agefrom" type="number" value="@if(!empty($_GET['agefrom'])){{$_GET['agefrom'] }}@endif">
                                <div class="sew6">至</div>
                                <input class="select_xx06 right" name="ageto" type="number" value="@if(!empty($_GET['ageto'])){{$_GET['ageto'] }}@endif">
                            </span>
                        </dt>
                        <dt>
                            <div class="n_se left">
                                <span>預算</span>
                                <select name="budget" class="select_xx01">
                                    <option value="">請選擇</option>
                                    <option value="基礎" @if( !empty( $_GET["budget"] ) && $_GET["budget"] == "基礎" ) selected @endif>基礎</option>
                                    <option value="進階" @if( !empty( $_GET["budget"] ) && $_GET["budget"] == "進階" ) selected @endif>進階</option>
                                    <option value="高級" @if( !empty( $_GET["budget"] ) && $_GET["budget"] == "高級" ) selected @endif>高級</option>
                                    <option value="最高" @if( !empty( $_GET["budget"] ) && $_GET["budget"] == "最高" ) selected @endif>最高</option>
                                    <option value="可商議" @if( !empty( $_GET["budget"] ) && $_GET["budget"] == "可商議" ) selected @endif>可商議</option>
                                </select>
                            </div>
                            <div class="n_se right">
                                <span>抽菸</span>
                                <select name="" class="select_xx01"><option>是</option><option>否</option></select>
                            </div>
                        </dt>
                        <dt>
                            <span>體型</span>
                            <span class="line20">
                                    <label class="n_tx"><input type="checkbox" name="body[0]" value="瘦" id="radio" @if( !empty( $_GET["body"][0] ) && $_GET["body"][0] == "瘦" ) checked @endif><i>瘦</i></label>
                                    <label class="n_tx"><input type="checkbox" name="body[1]" value="標準" id="radio1" @if( !empty( $_GET["body"][1] ) && $_GET["body"][1] == "標準" ) checked @endif><i>標準</i></label>
                                    <label class="n_tx"><input type="checkbox" name="body[2]" value="微胖" id="radio2" @if( !empty( $_GET["body"][2] ) && $_GET["body"][2] == "微胖" ) checked @endif><i>微胖</i></label>
                                    <label class="n_tx"><input type="checkbox" name="body[3]" value="胖" id="radio3" @if( !empty( $_GET["body"][3] ) && $_GET["body"][3] == "胖" ) checked @endif><i>胖</i></label>
                            </span>
                        </dt>
                        @if ($user->engroup == 1)
                        <dt class="matopj15">
                            <span>CUP</span>
                            <span class="line20">
                                <label class="n_tx"><input type="checkbox" name="cup[0]" value="A" id="Check" @if( !empty( $_GET["cup"][0] ) && $_GET["cup"][0] == "A" ) checked @endif><i>A</i></label>
                                <label class="n_tx"><input type="checkbox" name="cup[1]" value="B" id="Check1" @if( !empty( $_GET["cup"][1] ) && $_GET["cup"][1] == "B" ) checked @endif><i>B</i></label>
                                <label class="n_tx"><input type="checkbox" name="cup[2]" value="C" id="Check2" @if( !empty( $_GET["cup"][2] ) && $_GET["cup"][2] == "C" ) checked @endif><i>C</i></label>
                                <label class="n_tx"><input type="checkbox" name="cup[3]" value="D" id="Check3" @if( !empty( $_GET["cup"][3] ) && $_GET["cup"][3] == "D" ) checked @endif><i>D</i></label>
                                <label class="n_tx"><input type="checkbox" name="cup[4]" value="E" id="Check4" @if( !empty( $_GET["cup"][4] ) && $_GET["cup"][4] == "E" ) checked @endif><i>E</i></label>
                                <label class="n_tx"><input type="checkbox" name="cup[5]" value="F" id="Check5" @if( !empty( $_GET["cup"][5] ) && $_GET["cup"][5] == "F" ) checked @endif><i>F</i></label>
                            </span>
                        </dt>
                        @else
                        <dt class="matopj15">
                            <div class="n_se left">
                                <span>婚姻</span>
                                <select name="marriage" class="select_xx01">
                                    <option value="">請選擇</option>
                                    <option value="已婚" @if( !empty( $_GET["marriage"] ) && $_GET["marriage"] == "已婚" ) selected @endif>已婚</option>
                                    <option value="分居" @if( !empty( $_GET["marriage"] ) && $_GET["marriage"] == "分居" ) selected @endif>分居</option>
                                    <option value="單身" @if( !empty( $_GET["marriage"] ) && $_GET["marriage"] == "單身" ) selected @endif>單身</option>
                                    <option value="有男友" @if( !empty( $_GET["marriage"] ) && $_GET["marriage"] == "有男友" ) selected @endif>有男友</option>
                                </select>
                            </div>
                            <div class="n_se right">
                                <span>喝酒</span>
                                <select name="drinking" class="select_xx01">
                                    <option value="">請選擇</option>
                                    <option value="不喝" @if( !empty( $_GET["drinking"] ) && $_GET["drinking"] == "不喝" ) selected @endif>不喝</option>
                                    <option value="偶爾喝" @if( !empty( $_GET["drinking"] ) && $_GET["drinking"] == "偶爾喝" ) selected @endif>偶爾喝</option>
                                    <option value="常喝" @if( !empty( $_GET["drinking"] ) && $_GET["drinking"] == "常喝" ) selected @endif>常喝</option>
                                </select>
                            </div>
                        </dt>
                        @endif
                        <dt>
                            <span>搜索排列顺序</span>
                            <span>
                                <select name="seqtime" class="select_xx01">
                                    <option value="1" @if( !empty( $_GET["seqtime"] ) && $_GET["seqtime"] == 1 ) selected @endif>登入時間</option>
                                    <option value="2" @if( !empty( $_GET["seqtime"] ) && $_GET["seqtime"] == 2 ) selected @endif>註冊時間</option>
                                </select>
                            </span>
                        </dt>
                    </div>
                    <div class="n_txbut">
                        <button type="submit" class="n_dlbut" style="border-style: none;">搜索</button>
                        <button type="reset" class="n_zcbut">取消</button>
                    </div>

                </div>
                </form>

                <?php
                $district = "";
                $county = "";
                $cup = "";
                $marriage = "";
                $budget = "";
                $income = "";
                $smoking = "";
                $drinking = "";
                $photo = "";
                $ageto = "";
                $agefrom = "";
                $seqtime = "";
                $body = "";
                $umeta = $user->meta_();
                if(isset($umeta->city)){
                    $umeta->city = explode(",",$umeta->city);
                    $umeta->area = explode(",",$umeta->area);
                }

                ?>
                @if (isset($_GET['_token']))
                    <?php
                    if (isset($_GET['district'])) $district = $_GET['district'];
                    if (isset($_GET['county'])) $county = $_GET['county'];
                    if (isset($_GET['cup'])) $cup = $_GET['cup'];
                    if (isset($_GET['marriage'])) $marriage = $_GET['marriage'];
                    if (isset($_GET['budget'])) $budget = $_GET['budget'];
                    if (isset($_GET['income'])) $income = $_GET['income'];
                    if (isset($_GET['smoking'])) $smoking = $_GET['smoking'];
                    if (isset($_GET['drinking'])) $drinking = $_GET['drinking'];
                    if (isset($_GET['pic'])) $photo = $_GET['pic'];
                    if (isset($_GET['ageto'])) $ageto = $_GET['ageto'];
                    if (isset($_GET['agefrom'])) $agefrom = $_GET['agefrom'];
                    if (isset($_GET['seqtime'])) $seqtime = $_GET['seqtime'];
                    if (isset($_GET['seqtime'])) $seqtime = $_GET['seqtime'];
                    if (isset($_GET['body'])) $body = $_GET['body'];
                    ?>
                @endif
                <?php $icc = 1;
                $vis = \App\Models\UserMeta::search($county, $district, $cup, $marriage, $budget, $income, $smoking, $drinking, $photo, $agefrom, $ageto, $user->engroup, $umeta->city, $umeta->area, $umeta->blockdomain, $umeta->blockdomainType, $seqtime, $body, $user->id);
                ?>

                <div class="n_searchtit"><div class="n_seline"><span>搜索结果</span></div></div>
                <div class="n_sepeop">
                    @if (!empty($vis) && isset($vis) && sizeof($vis) > 0)
                            @foreach ($vis as $vi)
                            <?php $visitor = $vi->user();
                                  $umeta = $visitor->meta_();
                                  if(isset($umeta->city)){
                                      $umeta->city = explode(",",$umeta->city);
                                      $umeta->area = explode(",",$umeta->area);
                                  }
                            ?>
                                <li class="nt_fg">
                                    <div class="n_seicon">
                                        <? $data = \App\Services\UserService::checkRecommendedUser($visitor);?>
                                        @if(isset($data['description']) && $visitor->engroup == 2)
                                            <img src="/new/images/b_01.png">
                                        @endif
                                        @if(isset($data['description']) && $visitor->engroup == 1)
                                            <img src="/new/images/b_02.png">
                                        @endif
{{---------財力認證尚未實作-------------- <img src="/new/images/b_03.png">--}}
                                        @if($visitor->isVip())<img src="/new/images/b_04.png">@endif
{{---------警示帳戶尚未實作-------------- <img src="/new/images/b_05.png">--}}
                                    </div>
                                    <a href="/dashboard/viewuser/{{$visitor->id}}?time={{ \Carbon\Carbon::now()->timestamp }}">
                                    <div class="nt_photo"><img src="@if($visitor->meta_()->isAvatarHidden == 1) {{ 'makesomeerror' }} @else {{$visitor->meta_()->pic}} @endif" @if ($visitor->engroup == 1) onerror="this.src='/img/male-avatar.png'" @else onerror="this.src='/img/female-avatar.png'" @endif></div>
                                    <div class="nt_bot nt_bgco">
                                        <h2>{{ $visitor->name }}<span>{{ $visitor->meta_()->age() }}歲</span></h2>
                                        <h3>
                                            @if(!empty($umeta->city))
                                                @foreach($umeta->city as $key => $cityval)
                                                    @if ($loop->first)
                                                        {{$umeta->city[$key]}} {{$umeta->area[$key]}}
                                                    @else
                                                        <span>{{$umeta->city[$key]}} {{$umeta->area[$key]}}</span>
                                                    @endif
                                                @endforeach
                                            @endif
{{--                                            臺北市<span>臺中區</span>--}}

                                            @if($user->isVip())
                                                <span>{{ $visitor->meta_()->occupation }}</span>
                                            @else
                                                <span><span style="padding-left: 5px;">職業</span><img src="/new/images/icon_35.png" class="nt_img"></span>
                                            @endif
                                        </h3>
                                        <h3>最後上線時間：{{substr($visitor->last_login,0,16)}}</h3>
                                    </div>
                                    </a>
                                </li>
                        @endforeach
                    @else
                        <div class="fengsicon search"><img src="/new/images/loupe.png" class="feng_img"><span>沒有資料</span></div>
                    @endif

                </div>
                @if (sizeof($vis)>=12)

                    <div style="text-align: center;">
                        {!! $vis->appends(request()->input())->links('pagination::sg-pages') !!}
                    </div>
{{--                        <div class="fenye mabot30">--}}
{{--                        <a id="prePage" href="{{ $vis->appends(request()->input())->previousPageUrl() }}">上一頁</a>--}}
{{--                        <a id="nextPage" href="{{ $vis->appends(request()->input())->nextPageUrl() }}">下一頁</a>--}}
{{--                    </div>--}}
                @endif
            </div>

        </div>
    </div>
@stop

@section('javascript')
    <style>
        .select_xx07{
            width: 100%;
            border-radius: 4px;
            height: 40px;
            color: #555;
            background: #ffffff;
            font-size: 15px;
        }

        .select_xx01 {
         margin-right: 0%;
        }
        select{
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            padding-left: 10px;
        }
    </style>
    <script src="/js/jquery.twzipcode.min.js" type="text/javascript"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/cropperjs/1.0.0/cropper.min.js"></script>
    <script>
    $(document).ready(function(){
    //var BootstrapDatepicker=function(){var t=function(){$("#m_datepicker_1, #m_datepicker_1_validate").datepicker({todayHighlight:!0,orientation:"bottom left",templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_1_modal").datepicker({todayHighlight:!0,orientation:"bottom left",templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_2, #m_datepicker_2_validate").datepicker({todayHighlight:!0,orientation:"bottom left",templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_2_modal").datepicker({todayHighlight:!0,orientation:"bottom left",templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_3, #m_datepicker_3_validate").datepicker({todayBtn:"linked",clearBtn:!0,todayHighlight:!0,templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_3_modal").datepicker({todayBtn:"linked",clearBtn:!0,todayHighlight:!0,templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_4_1").datepicker({orientation:"top left",todayHighlight:!0,templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_4_2").datepicker({orientation:"top right",todayHighlight:!0,templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_4_3").datepicker({orientation:"bottom left",todayHighlight:!0,templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_4_4").datepicker({orientation:"bottom right",todayHighlight:!0,templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_5").datepicker({todayHighlight:!0,templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_6").datepicker({todayHighlight:!0,templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}})};return{init:function(){t()}}}();jQuery(document).ready(function(){BootstrapDatepicker.init()});
   // var BootstrapSelect=function(){var t=function(){$(".m_selectpicker").selectpicker()};return{init:function(){t()}}}();jQuery(document).ready(function(){BootstrapSelect.init()});
    $('.twzipcode').twzipcode({
    'detect': true, 'css': ['select_xx07', 'select_xx07', 'zipcode'], onCountySelect: function() {
    $("select[name='district']").prepend('<option selected value="">全市</option>');
    }
    });
    $('input[name="zipcode"]').remove();
    });
    </script>
@stop
