<?php
header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
?>
@extends('new.layouts.website')

@section('app-content')
@php $user_engroup = $user->engroup; @endphp
    <div id="app" ontouchstart="" onmouseover="">
        <div class="container matop70">
            <div class="row">
                <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                    @include('new.dashboard.panel')
                </div>

                <div class="col-sm-12 col-xs-12 col-md-10">
                    <div class="shou shou02 sh_line"><span>搜索</span>
                        <font>Search</font>
                        <div class="se_button" onClick="button_aa()">
                            <a class="se_button01">
                                <div class="se_button02">
                                    <img src="/new/images/dsearch.png">
                                </div>
                            </a>
                        </div>
                    </div>

                    <form action="{!! url('dashboard/search') !!}" method="post" id="form">
                        <input type="hidden" name="_token" value="{{csrf_token()}}" >
                        
                        <div class="n_search">
                            <div class="n_input">
                                <dt>
                                    <span>地區</span>
                                    <span class="twzipcode" id="twzipcode" style="display:inline-flex">
                                    <div class="select_xx08 left" data-role="county" data-name="county" data-value="@if(!empty($_POST['county'])){{ $_POST['county'] }}@elseif(!empty($_GET['county'])){{ $_GET['county']  }}@elseif(!empty(session()->get('search_page_key.county'))){{ session()->get('search_page_key.county')  }}@endif" style=""></div>
                                    <div class="sew6" style="width:13%"></div>
                                    <div class="select_xx08 right" data-role="district" data-name="district" data-value="@if(!empty($_POST['district'])){{ $_POST['district'] }}@elseif(!empty($_GET['district'])){{ $_GET['district'] }}@elseif(!empty(session()->get('search_page_key.district'))){{ session()->get('search_page_key.district')  }}@endif" style=""></div>
                                    </span>
                                    @if ($user->isVIP())
                                    <span class="twzipcode" id="twzipcode2" style="display:inline-flex">
                                    <div class="select_xx08 left" data-role="county" data-name="county2" data-value="{{ request()->county2??session()->get('search_page_key.county2')  }}" style=""></div>
                                    <div class="sew6" style="width:13%"></div>
                                    <div class="select_xx08 right" data-role="district" data-name="district2" data-value="{{ request()->district2??session()->get('search_page_key.district2')  }}" style=""></div>
                                    </span>
                                    <span class="twzipcode" id="twzipcode3" style="display:inline-flex">
                                    <div class="select_xx08 left" data-role="county" data-name="county3" data-value="{{ request()->county3??session()->get('search_page_key.county3')  }}" style=""></div>
                                    <div class="sew6" style="width:13%"></div>
                                    <div class="select_xx08 right" data-role="district" data-name="district3" data-value="{{ request()->district3??session()->get('search_page_key.district3')  }}" style=""></div>
                                    </span>  
                                    @endif
                                </dt>

                                @if (!$user->isVIP())
                                    <div>
                                        <div class="wuziliao">
                                            <img src="/new/images/fengs_icon.png">
                                        </div>
                                    </div>
                                @endif

                                @if ($user->isVIP())
                                    
                                    <dt>
                                        <div class="n_se left">
                                            <span>順序</span>
                                            <span>
                                                <select name="seqtime" id="seqtime" class="select_xx01">
                                                    <option value="1" @if( !empty( $_POST["seqtime"] ) && $_POST["seqtime"] == 1 ) selected @elseif(!empty( $_GET["seqtime"] ) && $_GET["seqtime"] == 1) selected @elseif(!empty( session()->get('search_page_key.seqtime') ) && session()->get('search_page_key.seqtime') == 1) selected @endif>登入時間</option>
                                                    <option value="2" @if( !empty( $_POST["seqtime"] ) && $_POST["seqtime"] == 2 ) selected @elseif(!empty( $_GET["seqtime"] ) && $_GET["seqtime"] == 2) selected @elseif(!empty( session()->get('search_page_key.seqtime') ) && session()->get('search_page_key.seqtime') == 2) selected @endif>註冊時間</option>
                                                </select>
                                            </span>
                                        </div>

                                        <div class="n_se right">
                                            <span>有無照片</span>
                                            <span>
                                                <select name="pic" id="pic" class="select_xx01">
                                                    <option value="">請選擇</option>
                                                    <option value="1" @if( !empty( $_POST["pic"] ) && $_POST["pic"] == 1 ) selected @elseif(!empty( $_GET["pic"] ) && $_GET["pic"] == 1) selected @elseif(!empty( session()->get('search_page_key.pic') ) && session()->get('search_page_key.pic') == 1) selected @endif>有</option>
                                                </select>
                                            </span>

                                            <!--<span class="line20">
                                                <label class="n_tx"><input type="checkbox" name="pic" value="1" id="Checkbox" @if( !empty( $_POST["pic"] ) && $_POST["pic"] == "1" ) checked @elseif(!empty( $_GET["pic"] ) && $_GET["pic"] == "1") checked @elseif(!empty( session()->get('search_page_key.pic') ) && session()->get('search_page_key.pic') == "1") checked @endif><i>有</i></label>
                                                {{--<label class="n_tx j_lr"><input type="radio" name="pic" value="1" id="pic" @if( !empty( $_POST["pic"] ) && $_POST["pic"] == 1 ) checked @elseif(!empty( $_GET["pic"] ) && $_GET["pic"]== 1) checked @endif><i>有</i></label>--}}
                                                {{--<label class="n_tx j_lr"><input type="radio" name="pic" value="0" id="pic1" @if( !empty( $_POST["pic"] ) && $_POST["pic"] == 0 ) checked @elseif(!empty( $_GET["pic"] ) && $_GET["pic"]== 0) checked @endif><i>無</i></label>--}}
                                            </span>-->

                                        </div>
                                        
                                    </dt>

                                    @if($user_engroup==1)
                                        <dt>
                                            <span>包養關係</span>
                                            <span class="line20">
                                                @php
                                                    $exchange_period_name = DB::table('exchange_period_name')->get();
                                                    $temp_id=0;
                                                @endphp
                                                @foreach($exchange_period_name as $row)
                                                    <label class="n_tx j_lr"><input type="checkbox" name="exchange_period[{{$temp_id}}]" value={{$row->id}} id="" @if( !empty( $_POST["exchange_period"][$temp_id] ) && $_POST["exchange_period"][$temp_id] == $row->id ) checked @elseif(!empty( $_GET["exchange_period"][$temp_id] ) && $_GET["exchange_period"][$temp_id] == $row->id) checked @elseif(!empty( session()->get('search_page_key.exchange_period')[$temp_id] ) && session()->get('search_page_key.exchange_period')[$temp_id] == $row->id) checked @endif><i>{{$row->name}}</i></label>
                                                    @php
                                                        $temp_id=$temp_id+1;
                                                    @endphp
                                                @endforeach
                                            </span>
                                        </dt>
                                    @else
                                        <dt>
                                            <span>PR值(大方指數)</span>
                                            <span class="line20" id="prRange">
                                                <label class="n_tx"><input type="checkbox" name="prRange_none" value="無" id="prRange" @if( !empty( $_POST["prRange_none"] ) && $_POST["prRange_none"] == "無" ) checked @elseif(!empty( $_GET["prRange_none"] ) && $_GET["prRange_none"] == "無") checked @elseif(!empty( session()->get('search_page_key.prRange_none') ) && session()->get('search_page_key.prRange_none') == "無") checked @endif><i>尚無PR值的新會員</i></label>
                                                <label class="n_tx"><input type="checkbox" name="prRange" value="0-100" id="prRange0" @if( !empty( $_POST["prRange"] ) && $_POST["prRange"] == "0-100" ) checked @elseif(!empty( $_GET["prRange"] ) && $_GET["prRange"] == "0-100") checked @elseif(!empty( session()->get('search_page_key.prRange') ) && session()->get('search_page_key.prRange') == "0-100") checked @endif><i>0~100</i></label>
                                                <label class="n_tx"><input type="checkbox" name="prRange" value="25-100" id="prRange1" @if( !empty( $_POST["prRange"] ) && $_POST["prRange"] == "25-100" ) checked @elseif(!empty( $_GET["prRange"] ) && $_GET["prRange"] == "25-100") checked @elseif(!empty( session()->get('search_page_key.prRange') ) && session()->get('search_page_key.prRange') == "25-100") checked @endif><i>25~100</i></label>
                                                <label class="n_tx"><input type="checkbox" name="prRange" value="50-100" id="prRange2" @if( !empty( $_POST["prRange"] ) && $_POST["prRange"] == "50-100" ) checked @elseif(!empty( $_GET["prRange"] ) && $_GET["prRange"] == "50-100") checked @elseif(!empty( session()->get('search_page_key.prRange') ) && session()->get('search_page_key.prRange') == "50-100") checked @endif><i>50~100</i></label>
                                                <label class="n_tx"><input type="checkbox" name="prRange" value="75-100" id="prRange3" @if( !empty( $_POST["prRange"] ) && $_POST["prRange"] == "75-100" ) checked @elseif(!empty( $_GET["prRange"] ) && $_GET["prRange"] == "75-100") checked @elseif(!empty( session()->get('search_page_key.prRange') ) && session()->get('search_page_key.prRange') == "75-100") checked @endif><i>75~100</i></label>
                                            </span>
                                        </dt>
                                    @endif

                                    <div class="btn_more">
                                        進階搜尋
                                        <span class="right"><img src="/new/images/xq_06.png"></span>
                                    </div>

                                    <div class="se_nvd">
                                        <dt>
                                            <span>年齡範圍</span>
                                            <span style="display: inline-flex;">
                                            <input class="select_xx06" name="agefrom" id="agefrom" type="number" min="18" max="80" value="@if(!empty($_POST['agefrom'])){{ $_POST['agefrom'] }}@elseif(!empty($_GET['agefrom'])){{$_GET['agefrom']}}@elseif(!empty(session()->get('search_page_key.agefrom'))){{ session()->get('search_page_key.agefrom')  }}@endif">
                                            <div class="sew6">至</div>
                                            <input class="select_xx06 right" name="ageto" id="ageto" type="number" min="18" max="80" value="@if(!empty($_POST['ageto'])){{$_POST['ageto'] }}@elseif(!empty($_GET['ageto'])){{$_GET['ageto']}}@elseif(!empty(session()->get('search_page_key.ageto'))){{ session()->get('search_page_key.ageto')  }}@endif">
                                            </span>
                                        </dt>

                                        <dt>
                                            <span>身高範圍</span>
                                            <span style="display: inline-flex;">
                                                {{--150  <input id="heightRange" type="text" class="span2" name="heightRange" value="" data-slider-min="150" data-slider-max="180" data-slider-step="5" data-slider-value="[150,180]"/>  180--}}
                                                {{--<input type="range" name="percent" min="50" max="100" value=@if(isset($_POST['percent']))"{{$_POST['percent']}}"@else"70"@endif class="form-control-range range" id="myRange">--}}
                                            <input class="select_xx06" name="heightfrom" id="heightfrom" type="number" min="140" max="210" value="@if(!empty($_POST['heightfrom'])){{ $_POST['heightfrom'] }}@elseif(!empty($_GET['heightfrom'])){{$_GET['heightfrom']}}@elseif(!empty(session()->get('search_page_key.heightfrom'))){{ session()->get('search_page_key.heightfrom')  }}@endif">
                                            <div class="sew6">至</div>
                                            <input class="select_xx06 right" name="heightto" id="heightto" type="number" min="140" max="210" value="@if(!empty($_POST['heightto'])){{$_POST['heightto'] }}@elseif(!empty($_GET['heightto'])){{$_GET['heightto']}}@elseif(!empty(session()->get('search_page_key.heightto'))){{ session()->get('search_page_key.heightto')  }}@endif">
                                            {{--<input id="heightRange" name="heightRange" class="multi-range" type="range" />--}}
                                            </span>
                                        </dt>

                                        <dt>
                                            <!--增加體重搜尋-->
                                            <div class="n_se left">
                                                <span>體重<i class="ssrgf">(僅顯示有填寫者)</i></span>
                                                <select name="weight" id="weight" class="select_xx01">
                                                    <option value="">請選擇</option>
                                                    @for ($i = 1; $i < 21; $i++)
                                                    <option value="{{$i*5}}" @if( !empty( $_POST["weight"] ) && $_POST["weight"] == $i*5 ) selected @elseif(!empty( $_GET["weight"] ) && $_GET["weight"] == $i*5) selected @elseif(!empty( session()->get('search_page_key.weight') ) && session()->get('search_page_key.weight') == $i*5) selected @endif>{{$i*5-4}} ~ {{$i*5}}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            @if ($user_engroup == 1)
                                                <div class="n_se right">
                                                    <span>現況</span>
                                                    <select name="situation" class="select_xx01">
                                                        <option value="">請選擇</option>
                                                        <option value="學生" @if( !empty( $_POST["situation"] ) && $_POST["situation"] == "學生" ) selected @elseif(!empty( $_GET["situation"] ) && $_GET["situation"] == "學生") selected @elseif(!empty( session()->get('search_page_key.situation') ) && session()->get('search_page_key.situation') == "學生") selected @endif>學生</option>
                                                        <option value="待業" @if( !empty( $_POST["situation"] ) && $_POST["situation"] == "待業" ) selected @elseif(!empty( $_GET["situation"] ) && $_GET["situation"] == "待業") selected @elseif(!empty( session()->get('search_page_key.situation') ) && session()->get('search_page_key.situation') == "待業") selected @endif>待業</option>
                                                        <option value="休學" @if( !empty( $_POST["situation"] ) && $_POST["situation"] == "休學" ) selected @elseif(!empty( $_GET["situation"] ) && $_GET["situation"] == "休學") selected @elseif(!empty( session()->get('search_page_key.situation') ) && session()->get('search_page_key.situation') == "休學") selected @endif>休學</option>
                                                        <option value="打工" @if( !empty( $_POST["situation"] ) && $_POST["situation"] == "打工" ) selected @elseif(!empty( $_GET["situation"] ) && $_GET["situation"] == "打工") selected @elseif(!empty( session()->get('search_page_key.situation') ) && session()->get('search_page_key.situation') == "打工") selected @endif>打工</option>
                                                        <option value="上班族" @if( !empty( $_POST["situation"] ) && $_POST["situation"] == "上班族" ) selected @elseif(!empty( $_GET["situation"] ) && $_GET["situation"] == "上班族") selected @elseif(!empty( session()->get('search_page_key.situation') ) && session()->get('search_page_key.situation') == "上班族") selected @endif>上班族</option>
                                                    </select>
                                                </div>
                                            @else
                                                <div class="n_se right">
                                                    <span>婚姻</span>
                                                    <select name="marriage" id="marriage" class="select_xx01">
                                                        <option value="">請選擇</option>
                                                        <option value="已婚" @if( !empty( $_POST["marriage"] ) && $_POST["marriage"] == "已婚" ) selected @elseif(!empty( $_GET["marriage"] ) && $_GET["marriage"] == "已婚") selected @elseif(!empty( session()->get('search_page_key.marriage') ) && session()->get('search_page_key.marriage') == "已婚") selected @endif>已婚</option>
                                                        <option value="分居" @if( !empty( $_POST["marriage"] ) && $_POST["marriage"] == "分居" ) selected @elseif(!empty( $_GET["marriage"] ) && $_GET["marriage"] == "分居") selected @elseif(!empty( session()->get('search_page_key.marriage') ) && session()->get('search_page_key.marriage') == "分居") selected @endif>分居</option>
                                                        <option value="單身" @if( !empty( $_POST["marriage"] ) && $_POST["marriage"] == "單身" ) selected @elseif(!empty( $_GET["marriage"] ) && $_GET["marriage"] == "單身") selected @elseif(!empty( session()->get('search_page_key.marriage') ) && session()->get('search_page_key.marriage') == "單身") selected @endif>單身</option>
                                                        @if( $user_engroup == 2)
                                                            <option value="有女友" @if( !empty( $_POST["marriage"] ) && $_POST["marriage"] == "有女友" ) selected @elseif(!empty( $_GET["marriage"] ) && $_GET["marriage"] == "有女友") selected @elseif(!empty( session()->get('search_page_key.marriage') ) && session()->get('search_page_key.marriage') == "有女友") selected @endif>有女友</option>
                                                        @else
                                                            <option value="有男友" @if( !empty( $_POST["marriage"] ) && $_POST["marriage"] == "有男友" ) selected @elseif(!empty( $_GET["marriage"] ) && $_GET["marriage"] == "有男友") selected @elseif(!empty( session()->get('search_page_key.marriage') ) && session()->get('search_page_key.marriage') == "有男友") selected @endif>有男友</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            @endif
                                        </dt>

                                        <dt>
                                            <div class="n_se left">
                                                <span>預算</span>
                                                <select name="budget" id="budget" class="select_xx01">
                                                    <option value="">請選擇</option>
                                                    <option value="基礎" @if( !empty( $_POST["budget"] ) && $_POST["budget"] == "基礎" ) selected @elseif(!empty( $_GET["budget"] ) && $_GET["budget"] == "基礎") selected @elseif(!empty( session()->get('search_page_key.budget') ) && session()->get('search_page_key.budget') == "基礎") selected @endif>基礎</option>
                                                    <option value="進階" @if( !empty( $_POST["budget"] ) && $_POST["budget"] == "進階" ) selected @elseif(!empty( $_GET["budget"] ) && $_GET["budget"] == "進階") selected @elseif(!empty( session()->get('search_page_key.budget') ) && session()->get('search_page_key.budget') == "進階") selected @endif>進階</option>
                                                    <option value="高級" @if( !empty( $_POST["budget"] ) && $_POST["budget"] == "高級" ) selected @elseif(!empty( $_GET["budget"] ) && $_GET["budget"] == "高級") selected @elseif(!empty( session()->get('search_page_key.budget') ) && session()->get('search_page_key.budget') == "高級") selected @endif>高級</option>
                                                    <option value="最高" @if( !empty( $_POST["budget"] ) && $_POST["budget"] == "最高" ) selected @elseif(!empty( $_GET["budget"] ) && $_GET["budget"] == "最高") selected @elseif(!empty( session()->get('search_page_key.budget') ) && session()->get('search_page_key.budget') == "最高") selected @endif>最高</option>
                                                    <option value="可商議" @if( !empty( $_POST["budget"] ) && $_POST["budget"] == "可商議" ) selected @elseif(!empty( $_GET["budget"] ) && $_GET["budget"] == "可商議") selected @elseif(!empty( session()->get('search_page_key.budget') ) && session()->get('search_page_key.budget') == "可商議") selected @endif>可商議</option>
                                                </select>
                                            </div>
                                            {{--@if ($user->engroup == 2)--}}
                                                {{--<div class="n_se right">--}}
                                                    {{--<span>PR值(大方指數)</span>--}}
                                                    {{--<select name="prRange" class="select_xx01">--}}
                                                        {{--<option value="">請選擇</option>--}}
                                                        {{--<option value="0-100" @if( !empty( $_POST["prRange"] ) && $_POST["prRange"] == "0-100" ) selected @elseif(!empty( $_GET["prRange"] ) && $_GET["prRange"] == "0-100") selected @endif>0~100</option>--}}
                                                        {{--<option value="25-100" @if( !empty( $_POST["prRange"] ) && $_POST["prRange"] == "25-100" ) selected @elseif(!empty( $_GET["prRange"] ) && $_GET["prRange"] == "25-100") selected @endif>25~100</option>--}}
                                                        {{--<option value="50-100" @if( !empty( $_POST["prRange"] ) && $_POST["prRange"] == "50-100" ) selected @elseif(!empty( $_GET["prRange"] ) && $_GET["prRange"] == "50-100") selected @endif>50~100</option>--}}
                                                        {{--<option value="75-100" @if( !empty( $_POST["prRange"] ) && $_POST["prRange"] == "75-100" ) selected @elseif(!empty( $_GET["prRange"] ) && $_GET["prRange"] == "75-100") selected @endif>75~100</option>--}}
                                                    {{--</select>--}}
                                                {{--</div>--}}
                                            {{--@else--}}
                                            <div class="n_se right">
                                                <span>抽菸</span>
                                                <select name="smoking" id="smoking" class="select_xx01">
                                                    <option value="">請選擇</option>
                                                    <option value="不抽" @if( !empty( $_POST["smoking"] ) && $_POST["smoking"] == "不抽" ) selected @elseif(!empty( $_GET["smoking"] ) && $_GET["smoking"] == "不抽") selected @elseif(!empty( session()->get('search_page_key.smoking') ) && session()->get('search_page_key.smoking') == "不抽") selected @endif>不抽</option>
                                                    <option value="偶爾抽" @if( !empty( $_POST["smoking"] ) && $_POST["smoking"] == "偶爾抽" ) selected @elseif(!empty( $_GET["smoking"] ) && $_GET["smoking"] == "偶爾抽") selected @elseif(!empty( session()->get('search_page_key.smoking') ) && session()->get('search_page_key.smoking') == "偶爾抽") selected @endif>偶爾抽</option>
                                                    <option value="常抽" @if( !empty( $_POST["smoking"] ) && $_POST["smoking"] == "常抽" ) selected @elseif(!empty( $_GET["smoking"] ) && $_GET["smoking"] == "常抽") selected @elseif(!empty( session()->get('search_page_key.smoking') ) && session()->get('search_page_key.smoking') == "常抽") selected @endif>常抽</option>
                                                </select>
                                            </div>
                                            {{--@endif--}}
                                        </dt>

                                        @if ($user_engroup == 2)
                                            <dt>
                                                        {{--<div class="n_se left">--}}
                                                        {{--<span>抽菸</span>--}}
                                                        {{--<select name="smoking" id="smoking" class="select_xx01">--}}
                                                        {{--<option value="">請選擇</option>--}}
                                                        {{--<option value="不抽" @if( !empty( $_POST["smoking"] ) && $_POST["smoking"] == "不抽" ) selected @elseif(!empty( $_GET["smoking"] ) && $_GET["smoking"] == "不抽") selected @endif>不抽</option>--}}
                                                        {{--<option value="偶爾抽" @if( !empty( $_POST["smoking"] ) && $_POST["smoking"] == "偶爾抽" ) selected @elseif(!empty( $_GET["smoking"] ) && $_GET["smoking"] == "偶爾抽") selected @endif>偶爾抽</option>--}}
                                                        {{--<option value="常抽" @if( !empty( $_POST["smoking"] ) && $_POST["smoking"] == "常抽" ) selected @elseif(!empty( $_GET["smoking"] ) && $_GET["smoking"] == "常抽") selected @endif>常抽</option>--}}
                                                        {{--</select>--}}
                                                        {{--</div>--}}
                                                <div class="n_se left">
                                                    <span>喝酒</span>
                                                    <select name="drinking" id="drinking" class="select_xx01">
                                                        <option value="">請選擇</option>
                                                        <option value="不喝" @if( !empty( $_POST["drinking"] ) && $_POST["drinking"] == "不喝" ) selected @elseif(!empty( $_GET["drinking"] ) && $_GET["drinking"] == "不喝") selected @elseif(!empty( session()->get('search_page_key.drinking') ) && session()->get('search_page_key.drinking') == "不喝") selected @endif>不喝</option>
                                                        <option value="偶爾喝" @if( !empty( $_POST["drinking"] ) && $_POST["drinking"] == "偶爾喝" ) selected @elseif(!empty( $_GET["drinking"] ) && $_GET["drinking"] == "偶爾喝") selected @elseif(!empty( session()->get('search_page_key.drinking') ) && session()->get('search_page_key.drinking') == "偶爾喝") selected @endif>偶爾喝</option>
                                                        <option value="常喝" @if( !empty( $_POST["drinking"] ) && $_POST["drinking"] == "常喝" ) selected @elseif(!empty( $_GET["drinking"] ) && $_GET["drinking"] == "常喝") selected @elseif(!empty( session()->get('search_page_key.drinking') ) && session()->get('search_page_key.drinking') == "常喝") selected @endif>常喝</option>
                                                    </select>
                                                </div>
                                                <div class="n_se right">
                                                    <span>教育</span>
                                                    <select name="education" class="select_xx01">
                                                        <option value="">請選擇</option>
                                                        <option value="國中" @if( !empty( $_POST["education"] ) && $_POST["education"] == "國中" ) selected @elseif(!empty( $_GET["education"] ) && $_GET["education"] == "國中") selected @elseif(!empty( session()->get('search_page_key.education') ) && session()->get('search_page_key.education') == "國中") selected @endif>國中</option>
                                                        <option value="高中" @if( !empty( $_POST["education"] ) && $_POST["education"] == "高中" ) selected @elseif(!empty( $_GET["education"] ) && $_GET["education"] == "高中") selected @elseif(!empty( session()->get('search_page_key.education') ) && session()->get('search_page_key.education') == "高中") selected @endif>高中</option>
                                                        <option value="大學" @if( !empty( $_POST["education"] ) && $_POST["education"] == "大學" ) selected @elseif(!empty( $_GET["education"] ) && $_GET["education"] == "大學") selected @elseif(!empty( session()->get('search_page_key.education') ) && session()->get('search_page_key.education') == "大學") selected @endif>大學</option>
                                                        <option value="研究所" @if( !empty( $_POST["education"] ) && $_POST["education"] == "研究所" ) selected @elseif(!empty( $_GET["education"] ) && $_GET["education"] == "研究所") selected @elseif(!empty( session()->get('search_page_key.education') ) && session()->get('search_page_key.education') == "研究所") selected @endif>研究所</option>
                                                    </select>
                                                </div>
                                            </dt>
                                        @else
                                            <dt>
                                                <div class="n_se left">
                                                    <span>婚姻</span>
                                                    <select name="marriage" id="marriage" class="select_xx01">
                                                        <option value="">請選擇</option>
                                                        <option value="已婚" @if( !empty( $_POST["marriage"] ) && $_POST["marriage"] == "已婚" ) selected @elseif(!empty( $_GET["marriage"] ) && $_GET["marriage"] == "已婚") selected @elseif(!empty( session()->get('search_page_key.marriage') ) && session()->get('search_page_key.marriage') == "已婚") selected @endif>已婚</option>
                                                        <option value="分居" @if( !empty( $_POST["marriage"] ) && $_POST["marriage"] == "分居" ) selected @elseif(!empty( $_GET["marriage"] ) && $_GET["marriage"] == "分居") selected @elseif(!empty( session()->get('search_page_key.marriage') ) && session()->get('search_page_key.marriage') == "分居") selected @endif>分居</option>
                                                        <option value="單身" @if( !empty( $_POST["marriage"] ) && $_POST["marriage"] == "單身" ) selected @elseif(!empty( $_GET["marriage"] ) && $_GET["marriage"] == "單身") selected @elseif(!empty( session()->get('search_page_key.marriage') ) && session()->get('search_page_key.marriage') == "單身") selected @endif>單身</option>
                                                        @if( $user_engroup == 2)
                                                            <option value="有女友" @if( !empty( $_POST["marriage"] ) && $_POST["marriage"] == "有女友" ) selected @elseif(!empty( $_GET["marriage"] ) && $_GET["marriage"] == "有女友") selected @elseif(!empty( session()->get('search_page_key.marriage') ) && session()->get('search_page_key.marriage') == "有女友") selected @endif>有女友</option>
                                                        @else
                                                            <option value="有男友" @if( !empty( $_POST["marriage"] ) && $_POST["marriage"] == "有男友" ) selected @elseif(!empty( $_GET["marriage"] ) && $_GET["marriage"] == "有男友") selected @elseif(!empty( session()->get('search_page_key.marriage') ) && session()->get('search_page_key.marriage') == "有男友") selected @endif>有男友</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="n_se right">
                                                    <span>教育</span>
                                                    <select name="education" class="select_xx01">
                                                        <option value="">請選擇</option>
                                                        <option value="國中" @if( !empty( $_POST["education"] ) && $_POST["education"] == "國中" ) selected @elseif(!empty( $_GET["education"] ) && $_GET["education"] == "國中") selected @elseif(!empty( session()->get('search_page_key.education') ) && session()->get('search_page_key.education') == "國中") selected @endif>國中</option>
                                                        <option value="高中" @if( !empty( $_POST["education"] ) && $_POST["education"] == "高中" ) selected @elseif(!empty( $_GET["education"] ) && $_GET["education"] == "高中") selected @elseif(!empty( session()->get('search_page_key.education') ) && session()->get('search_page_key.education') == "高中") selected @endif>高中</option>
                                                        <option value="大學" @if( !empty( $_POST["education"] ) && $_POST["education"] == "大學" ) selected @elseif(!empty( $_GET["education"] ) && $_GET["education"] == "大學") selected @elseif(!empty( session()->get('search_page_key.education') ) && session()->get('search_page_key.education') == "大學") selected @endif>大學</option>
                                                        <option value="研究所" @if( !empty( $_POST["education"] ) && $_POST["education"] == "研究所" ) selected @elseif(!empty( $_GET["education"] ) && $_GET["education"] == "研究所") selected @elseif(!empty( session()->get('search_page_key.education') ) && session()->get('search_page_key.education') == "研究所") selected @endif>研究所</option>
                                                    </select>
                                                </div>
                                            </dt>
                                        @endif

                                        <dt>
                                            {{--<div class="n_se right">--}}
                                            {{--<span>喝酒</span>--}}
                                            {{--<select name="drinking" id="drinking" class="select_xx01">--}}
                                            {{--<option value="">請選擇</option>--}}
                                            {{--<option value="不喝" @if( !empty( $_POST["drinking"] ) && $_POST["drinking"] == "不喝" ) selected @elseif(!empty( $_GET["drinking"] ) && $_GET["drinking"] == "不喝") selected @endif>不喝</option>--}}
                                            {{--<option value="偶爾喝" @if( !empty( $_POST["drinking"] ) && $_POST["drinking"] == "偶爾喝" ) selected @elseif(!empty( $_GET["drinking"] ) && $_GET["drinking"] == "偶爾喝") selected @endif>偶爾喝</option>--}}
                                            {{--<option value="常喝" @if( !empty( $_POST["drinking"] ) && $_POST["drinking"] == "常喝" ) selected @elseif(!empty( $_GET["drinking"] ) && $_GET["drinking"] == "常喝") selected @endif>常喝</option>--}}
                                            {{--</select>--}}
                                            {{--</div>--}}
                                        </dt>

                                        <dt>
                                            <!--<div class="n_se right">
                                                <span>順序</span>
                                                <span>
                                                <select name="seqtime" id="seqtime" class="select_xx01">
                                                    <option value="1" @if( !empty( $_POST["seqtime"] ) && $_POST["seqtime"] == 1 ) selected @elseif(!empty( $_GET["seqtime"] ) && $_GET["seqtime"] == 1) selected @elseif(!empty( session()->get('search_page_key.seqtime') ) && session()->get('search_page_key.seqtime') == 1) selected @endif>登入時間</option>
                                                    <option value="2" @if( !empty( $_POST["seqtime"] ) && $_POST["seqtime"] == 2 ) selected @elseif(!empty( $_GET["seqtime"] ) && $_GET["seqtime"] == 2) selected @elseif(!empty( session()->get('search_page_key.seqtime') ) && session()->get('search_page_key.seqtime') == 2) selected @endif>註冊時間</option>
                                                </select>
                                                </span>
                                            </div>-->
                                            {{--<div class="n_se right">--}}
                                            {{--<span>喝酒</span>--}}
                                            {{--<select name="drinking" id="drinking" class="select_xx01">--}}
                                            {{--<option value="">請選擇</option>--}}
                                            {{--<option value="不喝" @if( !empty( $_POST["drinking"] ) && $_POST["drinking"] == "不喝" ) selected @elseif(!empty( $_GET["drinking"] ) && $_GET["drinking"] == "不喝") selected @endif>不喝</option>--}}
                                            {{--<option value="偶爾喝" @if( !empty( $_POST["drinking"] ) && $_POST["drinking"] == "偶爾喝" ) selected @elseif(!empty( $_GET["drinking"] ) && $_GET["drinking"] == "偶爾喝") selected @endif>偶爾喝</option>--}}
                                            {{--<option value="常喝" @if( !empty( $_POST["drinking"] ) && $_POST["drinking"] == "常喝" ) selected @elseif(!empty( $_GET["drinking"] ) && $_GET["drinking"] == "常喝") selected @endif>常喝</option>--}}
                                            {{--</select>--}}
                                            {{--</div>--}}
                                        </dt>

                                        {{--<dt>--}}
                                        {{--<span>順序</span>--}}
                                        {{--<span>--}}
                                        {{--<select name="seqtime" id="seqtime" class="select_xx01">--}}
                                        {{--<option value="1" @if( !empty( $_POST["seqtime"] ) && $_POST["seqtime"] == 1 ) selected @elseif(!empty( $_GET["seqtime"] ) && $_GET["seqtime"] == 1) selected @endif>登入時間</option>--}}
                                        {{--<option value="2" @if( !empty( $_POST["seqtime"] ) && $_POST["seqtime"] == 2 ) selected @elseif(!empty( $_GET["seqtime"] ) && $_GET["seqtime"] == 2) selected @endif>註冊時間</option>--}}
                                        {{--</select>--}}
                                        {{--</span>--}}
                                        {{--</dt>--}}


                                        <dt class="matopj15">
                                            <span>體型<i class="ssrgf">(僅顯示有填寫者)</i></span>
                                            <span class="line20">
                                            <label class="n_tx"><input type="checkbox" name="body[0]" value="瘦" id="radio" @if( !empty( $_POST["body"][0] ) && $_POST["body"][0] == "瘦" ) checked @elseif(!empty( $_GET["body"][0] ) && $_GET["body"][0] == "瘦") checked @elseif(isset( session()->get('search_page_key.body')[0] ) && session()->get('search_page_key.body')[0] == "瘦") checked @endif><i>瘦</i></label>
                                            <label class="n_tx"><input type="checkbox" name="body[1]" value="標準" id="radio1" @if( !empty( $_POST["body"][1] ) && $_POST["body"][1] == "標準" ) checked @elseif(!empty( $_GET["body"][1] ) && $_GET["body"][1] == "標準") checked @elseif(isset( session()->get('search_page_key.body')[1] ) && session()->get('search_page_key.body')[1] == "標準") checked @endif><i>標準</i></label>
                                            <label class="n_tx"><input type="checkbox" name="body[2]" value="微胖" id="radio2" @if( !empty( $_POST["body"][2] ) && $_POST["body"][2] == "微胖" ) checked @elseif(!empty( $_GET["body"][2] ) && $_GET["body"][2] == "微胖") checked @elseif(isset( session()->get('search_page_key.body')[2] ) && session()->get('search_page_key.body')[2] == "微胖") checked @endif><i>微胖</i></label>
                                            <label class="n_tx"><input type="checkbox" name="body[3]" value="胖" id="radio3" @if( !empty( $_POST["body"][3] ) && $_POST["body"][3] == "胖" ) checked @elseif(!empty( $_GET["body"][3] ) && $_GET["body"][3] == "胖") checked @elseif(isset( session()->get('search_page_key.body')[3] ) && session()->get('search_page_key.body')[3] == "胖") checked @endif><i>胖</i></label>
                                            </span>
                                        </dt>

                                        @if ($user_engroup == 1)
                                            <dt class="matopj15">
                                                <span>CUP<i class="ssrgf">(僅顯示有填寫者)</i></span>
                                                <span class="line20">
                                                    <label class="n_tx"><input type="checkbox" name="cup[0]" value="A" id="Check" @if( !empty( $_POST["cup"][0] ) && $_POST["cup"][0] == "A" ) checked @elseif(!empty( $_GET["cup"][0] ) && $_GET["cup"][0] == "A") checked @elseif(isset( session()->get('search_page_key.cup')[0] ) && session()->get('search_page_key.cup')[0] == "A") checked @endif><i>A</i></label>
                                                    <label class="n_tx"><input type="checkbox" name="cup[1]" value="B" id="Check1" @if( !empty( $_POST["cup"][1] ) && $_POST["cup"][1] == "B" ) checked @elseif(!empty( $_GET["cup"][1] ) && $_GET["cup"][1] == "B") checked @elseif(isset( session()->get('search_page_key.cup')[1] ) && session()->get('search_page_key.cup')[1] == "B") checked @endif><i>B</i></label>
                                                    <label class="n_tx"><input type="checkbox" name="cup[2]" value="C" id="Check2" @if( !empty( $_POST["cup"][2] ) && $_POST["cup"][2] == "C" ) checked @elseif(!empty( $_GET["cup"][2] ) && $_GET["cup"][2] == "C") checked @elseif(isset( session()->get('search_page_key.cup')[2] ) && session()->get('search_page_key.cup')[2] == "C") checked @endif><i>C</i></label>
                                                    <label class="n_tx"><input type="checkbox" name="cup[3]" value="D" id="Check3" @if( !empty( $_POST["cup"][3] ) && $_POST["cup"][3] == "D" ) checked @elseif(!empty( $_GET["cup"][3] ) && $_GET["cup"][3] == "D") checked @elseif(isset( session()->get('search_page_key.cup')[3] ) && session()->get('search_page_key.cup')[3] == "D") checked @endif><i>D</i></label>
                                                    <label class="n_tx"><input type="checkbox" name="cup[4]" value="E" id="Check4" @if( !empty( $_POST["cup"][4] ) && $_POST["cup"][4] == "E" ) checked @elseif(!empty( $_GET["cup"][4] ) && $_GET["cup"][4] == "E") checked @elseif(isset( session()->get('search_page_key.cup')[4] ) && session()->get('search_page_key.cup')[4] == "E") checked @endif><i>E</i></label>
                                                    <label class="n_tx"><input type="checkbox" name="cup[5]" value="F" id="Check5" @if( !empty( $_POST["cup"][5] ) && $_POST["cup"][5] == "F" ) checked @elseif(!empty( $_GET["cup"][5] ) && $_GET["cup"][5] == "F") checked @elseif(isset( session()->get('search_page_key.cup')[5] ) && session()->get('search_page_key.cup')[5] == "F") checked @endif><i>F</i></label>
                                                </span>
                                            </dt>
                                            <dt class="matopj15">
                                                <span>有無刺青<i class="ssrgf">(僅顯示有填寫者)</i></span>
                                                <span class="line20">
                                                    <label class="n_tx"><input type="radio" name="tattoo" value="1" id="tattoo1" {{(request()->tattoo==1 || session()->get('search_page_key.tattoo')==1)?'checked':''}}><i>有</i></label>
                                                    <label class="n_tx"><input type="radio" name="tattoo" value="-1" id="tattoo0" {{(request()->tattoo==-1 ||  session()->get('search_page_key.tattoo')==-1)?'checked':''}}><i>無</i></label>
                                                </span>
                                            </dt>                               
                                        @else
                                        @endif

                                        <dt class="b_nsead matopjf10">
                                            <div class="b_nsead_tit"><i>身份選擇</i></div>
                                            <div class="b_nsba">
                                                @if($user_engroup==2)
                                                <li>
                                                    <span>是否為VIP</span>
                                                    <font>
                                                        <label class="n_tx"><input type="checkbox" name="isVip" value="1" id="Checkbox" @if( !empty( $_POST["isVip"] ) && $_POST["isVip"] == "1" ) checked @elseif(!empty( $_GET["isVip"] ) && $_GET["isVip"] == "1") checked @elseif(!empty( session()->get('search_page_key.isVip') ) && session()->get('search_page_key.isVip') == "1") checked @endif><i>是</i></label>

                                                        {{--<label class="ba_tx"><input type="radio" name="isVip" value="1" id="isVip" @if( !empty( $_POST["isVip"] ) && $_POST["isVip"] == 1 ) checked @elseif(!empty( $_GET["isVip"] ) && $_GET["isVip"]== 1) checked @endif><i>是</i></label>--}}
                                                        {{--<label class="ba_tx"><input type="radio" name="isVip" value="0" id="isVip1" @if( !empty( $_POST["isVip"] ) && $_POST["isVip"] == 0 ) checked @elseif(!empty( $_GET["isVip"] ) && $_GET["isVip"]== 0) checked @endif><i>否</i></label>--}}
                                                    </font>
                                                </li>
                                                @endif
                                                <li>
                                                    <span>顯示已封鎖會員</span>
                                                    <font>
                                                        <label class="n_tx"><input type="checkbox" name="isBlocked" value="1" id="isBlocked" @if( !empty( $_POST["isBlocked"] ) && $_POST["isBlocked"] == "1" ) checked @elseif(!empty( $_GET["isBlocked"] ) && $_GET["isBlocked"] == "1") checked @elseif(!empty( session()->get('search_page_key.isBlocked') ) && session()->get('search_page_key.isBlocked') == "1") checked @endif><i>否</i></label>
                                                        <input type="hidden" name="isBlocked" value="1" id="isBlockedHidden">
                                                        {{--<label class="ba_tx"><input type="radio" name="isBlocked" value="2" id="isBlocked" @if( !empty( $_POST["isBlocked"] ) && $_POST["isBlocked"] == 2 ) checked @elseif(!empty( $_GET["isBlocked"] ) && $_GET["isBlocked"]== 2) checked @endif><i>是</i></label>--}}
                                                        {{--<label class="ba_tx"><input type="radio" name="isBlocked" value="1" id="isBlocked1" @if( !empty( $_POST["isBlocked"] ) && $_POST["isBlocked"] == 1 ) checked @elseif(!empty( $_GET["isBlocked"]) && $_GET["isBlocked"] == 1) checked @endif><i>否</i></label>--}}
                                                    </font>
                                                </li>
                                                <li>
                                                    <span>顯示警示帳戶</span>
                                                    <font>
                                                        <label class="n_tx"><input type="checkbox" name="isWarned" value="2" id="isWarned" @if( !empty( $_POST["isWarned"] ) && $_POST["isWarned"] == "2" ) checked @elseif(!empty( $_GET["isWarned"] ) && $_GET["isWarned"] == "2") checked @elseif(!empty( session()->get('search_page_key.isWarned') ) && session()->get('search_page_key.isWarned') == "2") checked @endif><i>是</i></label>

                                                        {{--<label class="ba_tx"><input type="radio" name="isWarned" value="2" id="isWarned" @if( !empty( $_POST["isWarned"] ) && $_POST["isWarned"] == 2 ) checked @elseif(!empty( $_GET["isWarned"] ) && $_GET["isWarned"]== 2) checked @endif><i>是</i></label>--}}
                                                        {{--<label class="ba_tx"><input type="radio" name="isWarned" value="1" id="isWarned1" @if( !empty( $_POST["isWarned"] ) && $_POST["isWarned"] == 1 ) checked @elseif(!empty( $_GET["isWarned"] ) && $_GET["isWarned"]== 1) checked @endif><i>否</i></label>--}}
                                                    </font>
                                                </li>
                                                @if($user_engroup==1)
                                                <li>
                                                    <span>通過進階驗證</span>
                                                    <font>
                                                        <label class="n_tx"><input type="checkbox" name="isAdvanceAuth" value="1" id="isAdvanceAuth" @if( !empty( $_POST["isAdvanceAuth"] ) && $_POST["isAdvanceAuth"] == "1" ) checked @elseif(!empty( $_GET["isAdvanceAuth"] ) && $_GET["isAdvanceAuth"] == "1") checked  @elseif(!empty( session()->get('search_page_key.isAdvanceAuth') ) && session()->get('search_page_key.isAdvanceAuth') == "1") checked @endif><i>是</i></label>
                                                    </font>
                                                </li>
                                                @endif
                                                @if($user_engroup==2)
                                                <li>
                                                    <span>通過手機驗證</span>
                                                    <font>
                                                        <label class="n_tx"><input type="checkbox" name="isPhoneAuth" value="2" id="isPhoneAuth" @if( (request()->isPhoneAuth??null)  && request()->isPhoneAuth== "2" ) checked @elseif(!empty( session()->get('search_page_key.isAdvanceAuth') ) && session()->get('search_page_key.isPhoneAuth') == "2") checked @endif><i>是</i></label>
                                                    </font>
                                                </li>
                                                @endif                                    
                                            </div>
                                        </dt>
                                    </div><!--se_nvd結束-->
                                @endif


                            </div>

                            <div class="n_txbut">
                                <button type="submit" class="se_but1" style="border-style: none;">搜索</button>
                                <button type="reset" class="se_but2 se_but2_hover" id="search_reset">取消</button>
                            </div>



                        </div>
                    </form>

                    <?php
                        
                    ?>
@php

                        
                            // $district = "";
                            // $county = "";
                            // $district2 = "";
                            // $county2 = "";
                            // $district3 = "";
                            // $county3 = "";                        
                            // $cup = "";
                            // $marriage = "";
                            // $budget = "";
                            // $income = "";
                            // $smoking = "";
                            // $drinking = "";
                            // $pic = "";
                            // $ageto = "";
                            // $agefrom = "";
                            // $seqtime = "";
                            // $body = "";
                            // $exchange_period = "";
                            // $umeta = $user->user_meta;
                            // // $isBlocked = 1;
                            // if(isset($umeta->city)){
                            //     $umeta->city = explode(",",$umeta->city);
                            //     $umeta->area = explode(",",$umeta->area);
                            // }
                            // $heightfrom = "";
                            // $heightto = "";
                            // $prRange_none = "";
                            // $prRange = "";
                            // $situation = "";
                            // $education = "";
                            // $isVip = "";
                            // $isWarned = "";
                            // $isPhoneAuth = "";
                            // $tattoo= "";
                            //新增體重
                            // $weight = "";
                        






                    // if (isset($_POST['_token']) || isset($_GET['_token']) || count(session()->get('search_page_key',[]))){
                    try{
                        $umeta = $user->user_meta;
                        if(isset($umeta->city)){
                            $umeta->city = explode(",",$umeta->city);
                            $umeta->area = explode(",",$umeta->area);
                        }

                        $district = search_variable('district',"");
                        $county = search_variable('county',"");
                        $cup = search_variable('cup',"");
                        $marriage = search_variable('marriage',"");
                        $budget = search_variable('budget',"");
                        $income = search_variable('income',"");
                        $smoking = search_variable('smoking',"");
                        $drinking = search_variable('drinking',"");
                        $pic = search_variable('pic',"");
                        $ageto = search_variable('ageto',"");
                        $agefrom = search_variable('agefrom',"");
                        $seqtime = search_variable('seqtime',"");
                        $body = search_variable('body',"");
                        $exchange_period = search_variable('exchange_period',"");
                        $isBlocked = search_variable('isBlocked',1);
                        $heightto = search_variable('heightto',"");
                        $heightfrom = search_variable('heightfrom',"");
                        $prRange_none = search_variable('prRange_none',"");
                        $prRange = search_variable('prRange',"");
                        $situation = search_variable('situation',"");
                        $education = search_variable('education',"");
                        $isVip = search_variable('isVip',"");
                        $isWarned = search_variable('isWarned',"");
                        $isPhoneAuth = search_variable('isPhoneAuth',"");
                        $weight = search_variable('weight',"");

                        $tattoo = search_variable("tattoo","");
                        $county2 = search_variable("county2","");
                        $county3 = search_variable("county3","");
                        $district2 = search_variable("district2","");
                        $district3 = search_variable("district3","");

                        $userIsAdvanceAuth = search_variable("isAdvanceAuth", 0);
                    }
                    catch (\Exception $e){
                        \Illuminate\Support\Facades\Log::info('Search error, $user: ' . $user);
                    }
                        

                    $icc = 1;
                    $userIsVip = $user->isVIP();

                    if(isset($_GET['page'])){
                        $page = $_GET['page'];
                        $page_pre = $page-1>0 ?$page-1: 1;
                        $page_next = $page+1;
                    }else{
                        $page = 1;
                        $page_pre = 1;
                        $page_next = 2;
                    }
                        //is these are correct modification??
                        // $tattoo = request()->tattoo??session()->get('search_page_key.tattoo');
                        // $county2 = request()->county2??session()->get('search_page_key.county2');
                        // $county3 = request()->county3??session()->get('search_page_key.county3');
                        // $district2 = request()->district2??session()->get('search_page_key.district2');
                        // $district3 = request()->district3??session()->get('search_page_key.district3');
                        
                    // }


                    
                    // $userIsAdvanceAuth = $_POST["isAdvanceAuth"] ?? 
                    //                      $_GET["isAdvanceAuth"]  ?? 
                    //                      session()->get('search_page_key.isAdvanceAuth') ?? 0;

                    // vi vendor/laravel/framework/src/Illuminate/Database/Query/Builder.php
                    // addWhereExistsQuery() remove $operator
                    // https://learnku.com/articles/28283?order_by=vote_count&

@endphp
                    

                    <div class="se_bgdy">
                        <!--<div class="n_searchtit"><div class="n_seline"><span>搜索结果</span></div></div>-->
                        <div class="n_searchtit_a1">

                        </div>

                        <div class="n_sepeop">
                            
                            <a v-html="csrData">
                                {{-- @for($i=0;$i<12;$i++)
                                    <li class="nt_fg"><div class="n_seicon"><a><div class="nt_photo blur_img"></div><div class="nt_bot nt_bgco"><h2>loading...</h2><h3>loading...</h3><h3>最後上線時間：loading... </h3></div></a></div></li>
                                @endfor --}}
                            </a>
                            
                        </div>

                        {{-- <div style="text-align: center;">
                            {!! $vis->appends(request()->input())->links('pagination::sg-pages2') !!}
                        </div> --}}


                        <div style="text-align: center;"><div class="fenye"><span v-if="isPrePageShow"><a :href="'/dashboard/search?page=' + page_pre">上一頁</a></span><span class="new_page" v-if="isNowPageShow">第 <?php echo $page;?> 頁</span> <span v-if="isNextPageShow"><a :href="'/dashboard/search?page=' + page_next">下一頁</a></span></div></div>

                    </div>
                    
                </div>

            </div>
        </div>
    </div>
@stop



@section('javascript')
    <script type="application/javascript">
	 $(document).ready(function () {
        $('#search_reset').click(function(){
            $.ajax({
                type: 'POST',
                url: "/dashboard/search_key_reset?{{csrf_token()}}={{now()->timestamp}}",
                async:false,
                data:{
                    _token: '{{csrf_token()}}'
                },
                dataType:"json"
            });
            window.location.replace("/dashboard/search");
        });

        $("#prRange label #prRange0, #prRange label #prRange1, #prRange label #prRange2, #prRange label #prRange3").click(function(){
            if($(this).prop('checked')){
                // alert($(this).val());
                // $('#prRange label input:checkbox').prop('checked',false);
                $('#prRange label #prRange0, #prRange label #prRange1, #prRange label #prRange2, #prRange label #prRange3').prop('checked',false);
                // $('#prRange label #prRange2').prop('checked',false);
                // $('#prRange label #prRange3').prop('checked',false);
                $(this).prop('checked',true);
            }
        });

        //se_but2_hover
        if( /Android|webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            $('.se_but2').removeClass('se_but2_hover');
        }

        @if($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_POST['_token']) || isset($_GET['_token']))

        @else
            //if (!$("input[name='isBlocked']").is(':checked')) {
            //    $('#isBlocked').attr('checked', true);
            //}

            {{-- if (!$("input[name='isWarned']").is(':checked')) {
                $('#isWarned').attr('checked', true);
            } --}}
        @endif

        @if($isBlocked == 1)
            $('#isBlocked').attr('checked', true);
        @endif

        $("input[name='isBlocked']").click(function(){
            if ( ! $("input[name='isBlocked']").is(':checked') ){
                $('#isBlockedHidden').val(2);
            }
        });

        // // if ( ! $("input[name='isVip']").is(':checked') ){
        // //     $('#isVip1').attr('checked', true);
        // // }
        //

        //
        // if ( ! $("input[name='isPhoneAuth']").is(':checked') ){
        //     $('#isPhoneAuth1').attr('checked', true);
        // }

        $('.n_zcbut').click(function(){

            $("#agefrom").attr("value","");
            $("#ageto").attr("value","");

            $("input[type='checkbox']").attr("checked", false);

            @if(!empty($_POST['seqtime']))
            $("#seqtime option[value='{{$_POST['seqtime']}}']").attr('selected', false);
            @elseif(!empty($_GET['seqtime']))
            $("#seqtime option[value='{{$_GET['seqtime']}}']").attr('selected', false);
            @endif
            $("#seqtime option[value='']").attr('selected', true);

            @if(!empty($_POST['marriage']))
            $("#marriage option[value='{{$_POST['marriage']}}']").attr('selected', false);
            @elseif(!empty($_GET['marriage']))
            $("#marriage option[value='{{$_GET['marriage']}}']").attr('selected', false);
            @endif
            $("#marriage option[value='']").attr('selected', true);

            @if(!empty($_POST['smoking']))
            $("#smoking option[value='{{$_POST['smoking']}}']").attr('selected', false);
            @elseif(!empty($_GET['smoking']))
            $("#smoking option[value='{{$_GET['smoking']}}']").attr('selected', false);
            @endif
            $("#smoking option[value='']").attr('selected', true);

            @if(!empty($_POST['drinking']))
            $("#drinking option[value='{{$_POST['drinking']}}']").attr('selected', false);
            @elseif(!empty($_GET['drinking']))
            $("#drinking option[value='{{$_GET['drinking']}}']").attr('selected', false);
            @endif
            $("#drinking option[value='']").attr('selected', true);

            @if(!empty($_POST['budget']))
            $("#budget option[value='{{$_POST['budget']}}']").attr('selected', false);
            @elseif(!empty($_GET['budget']))
            $("#budget option[value='{{$_GET['budget']}}']").attr('selected', false);
            @endif
            $("#budget option[value='']").attr('selected', true);

            //新增體重
            @if(!empty($_POST['weight']))
            $("#weight option[value='{{$_POST['weight']}}']").attr('selected', false);
            @elseif(!empty($_GET['weight']))
            $("#weight option[value='{{$_GET['weight']}}']").attr('selected', false);
            @endif
            $("#weight option[value='']").attr('selected', true);

        });
	 });
    </script>
    <script src="/js/jquery.twzipcode.min.js" type="text/javascript"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/cropperjs/1.0.0/cropper.min.js" type="text/javascript"></script>
    <script type="text/javascript">

        $('.n_search').hide();
        function button_aa() {
            var display =$('.n_search').css('display');
            if(display =='none')
            {
                $(".n_search").show();
            }
            else
            {
                $('.n_search').hide();
            }
        }

        $(document).ready(function(){

            $('.se_nvd').hide();
            $('.btn_more').click(function () {
                $(this).next('.se_nvd').toggle();
                $(this).toggleClass('up');
            });

            //var BootstrapDatepicker=function(){var t=function(){$("#m_datepicker_1, #m_datepicker_1_validate").datepicker({todayHighlight:!0,orientation:"bottom left",templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_1_modal").datepicker({todayHighlight:!0,orientation:"bottom left",templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_2, #m_datepicker_2_validate").datepicker({todayHighlight:!0,orientation:"bottom left",templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_2_modal").datepicker({todayHighlight:!0,orientation:"bottom left",templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_3, #m_datepicker_3_validate").datepicker({todayBtn:"linked",clearBtn:!0,todayHighlight:!0,templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_3_modal").datepicker({todayBtn:"linked",clearBtn:!0,todayHighlight:!0,templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_4_1").datepicker({orientation:"top left",todayHighlight:!0,templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_4_2").datepicker({orientation:"top right",todayHighlight:!0,templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_4_3").datepicker({orientation:"bottom left",todayHighlight:!0,templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_4_4").datepicker({orientation:"bottom right",todayHighlight:!0,templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_5").datepicker({todayHighlight:!0,templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_6").datepicker({todayHighlight:!0,templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}})};return{init:function(){t()}}}();jQuery(document).ready(function(){BootstrapDatepicker.init()});
            // var BootstrapSelect=function(){var t=function(){$(".m_selectpicker").selectpicker()};return{init:function(){t()}}}();jQuery(document).ready(function(){BootstrapSelect.init()});
            $('.twzipcode').eq(0).twzipcode({
                'detect': true, 'css': ['select_xx08','select_xx08'], onCountySelect: function() {
                    $("select[name='district']").prepend('<option selected value="">全部</option>');
                }
            });
            $('input[name="zipcode"]').remove();
            

            $('.twzipcode').eq(1).twzipcode({
                'detect': true, 'css': ['select_xx08','select_xx08'],countyName : 'country2',districtName : 'district2', onCountySelect: function() {
                    $("select[name='district2']").prepend('<option selected value="">全部</option>');
                }
            });
            $('input[name="zipcode"]').remove();
            
            $('.twzipcode').eq(2).twzipcode({
                'detect': true, 'css': ['select_xx08','select_xx08'],countyName : 'country3',districtName : 'district3', onCountySelect: function() {
                    $("select[name='district3']").prepend('<option selected value="">全部</option>');
                }
            });            
            $('input[name="zipcode"]').remove();
            
            // $('[data-toggle="popover"]').popover({
            //     animated: 'fade',
            //     placement: 'bottom',
            //     trigger: 'hover',
            //     html: true,
            //     content: function () { return '<h4' + $(this).data('content') + '</h4>'; }
            // });
        });
        $("img.lazy").lazyload({
            effect : "fadeIn"
        });
    </script>
    <script>
    let perPageCount = 12 //每頁顯示筆數
    let engroup = {!!$user->engroup!!};
    console.log(engroup);
    let csrDataSingle = '';
    if(engroup==1){
        csrDataSingle = '<li class="nt_fg"><div class="n_seicon_bg"><a><div class="nt_photo blur_img"></div><div class="nt_bot vvip_bgco2"><h2>loading...</h2><h3>loading...</h3><h3>最後上線時間：loading... </h3></div></a></div></li>';
    }else if(engroup==2){
        csrDataSingle = '<li class="nt_fg"><div class="n_seicon_bg"><a><div class="nt_photo blur_img"></div><div class="nt_bot nt_bgco"><h2>loading...</h2><h3>loading...</h3><h3>最後上線時間：loading... </h3></div></a></div></li>';
    }else{
        csrDataSingle = '<li class="nt_fg"><div class="n_seicon_bg"><a><div class="nt_photo blur_img"></div><div class="nt_bot vvip_bgco2"><h2>loading...</h2><h3>loading...</h3><h3>最後上線時間：loading... </h3></div></a></div></li>';
    }
    
    let csrDataText = csrDataSingle.repeat(perPageCount);
    // let csrDataText = '';
    // for(let i=0;i<12;i++){
    //     csrDataSingle = '<li class="nt_fg item_'+i+'"><div class="n_seicon"><a><div class="nt_photo blur_img"></div><div class="nt_bot nt_bgco"><h2>loading...</h2><h3>loading...</h3><h3>最後上線時間：loading... </h3></div></a></div></li>';
    //     csrDataText += csrDataSingle
    //     console.log(csrDataText)
    // }
    const vm = new Vue({
            el: '#app',
            data () {
                return {
                    "isShow":true,
                    "allSearchData": [],                   
                    "csrData":csrDataText,
                    "isPrePageShow":false,
                    "isNextPageShow":false,
                    "isNowPageShow":false,
                    "page_next":"{{$page_next}}",
                    "page_pre":"{{$page_pre}}"
                }
            },
        mounted () {

             let post_data = {
                city:"{{$county}}",
                area:"{{$district}}",
                cup:{!! json_encode($cup) !!},
                marriage:"{{$marriage}}",
                budget:"{{$budget}}",
                income:"{{$income}}",
                smoking:"{{$smoking}}",
                drinking:"{{$drinking}}",
                pic:"{{$pic}}",
                agefrom:"{{$agefrom}}",
                ageto:"{{$ageto}}",
                user:{!! $user !!},
                umeta:{!! $umeta !!},
                seqtime:"{{$seqtime}}",
                body:{!! json_encode($body) !!},
                exchange_period:{!! json_encode($exchange_period) !!},
                isBlocked:"{{$isBlocked}}",
                userIsVip:"{{$userIsVip}}",
                heightfrom:"{{$heightfrom}}",
                heightto:"{{$heightto}}",
                prRange_none:"{{$prRange_none}}",
                prRange:"{{$prRange}}",
                situation:"{{$situation}}",
                education:"{{$education}}",
                isVip:"{{$isVip}}",
                isWarned:"{{$isWarned}}",
                isPhoneAuth:"{{$isPhoneAuth}}",
                isAdvanceAuth:"{{$userIsAdvanceAuth ?? null}}",
                userIsAdvanceAuth:"{{$userIsAdvanceAuth}}",  
                page:"{{$page ?? 1}}",
                tattoo:"{{$tattoo ?? null}}",
                city2:"{{$county2  ?? null}}",
                area2:"{{$district2  ?? null}}",
                city3:"{{$county3  ?? null}}",
                area3:"{{$district3  ?? null}}",
                //新增體重
                weight:"{{$weight}}",

                perPageCount:perPageCount
            };
            axios.post('/getSearchData', post_data)
            .then(response => {
                    this.dataList = response.data.dataList;
                    this.user = response.data.user;
                    this.userIsVip = response.data.userIsVip;

                    this.singlePageCount = response.data.singlePageCount;
                    this.allPageDataCount = response.data.allPageDataCount;
                    if(this.allPageDataCount>12){
                        this.isNowPageShow=true;
                        this.isPrePageShow=true;
                        this.isNextPageShow=true;
                        if(this.singlePageCount<12){
                            this.page_next = (Math.floor( this.allPageDataCount / 12 ) + 1);
                        }
                    }
                   
                   
                    let arr = [];
                    if(this.dataList.length>=1){                   
                        let csrdDataPre = '';
                        let csrData = '';
                        
                        let csrDataBg = '';
                        // this.dataList.forEach((row, index) => {
                        //     let rowEngroup = row.rawData.engroup;
                        //     let rowExchangePeriod = row.rawData.exchange_period;
                        //     if(rowEngroup==2){
                        //         if(rowExchangePeriod==2){
                        //             csrDataBg = 'vvip_bg1';
                        //         }else{
                        //             csrDataBg ='vvip_bg2';
                        //         }
                        //     }else{
                        //         csrDataBg ='vvip_bg2';
                        //     }

                            
                        //     csrdDataPre += '<li class="nt_fg '+csrDataBg+'"><div class="n_seicon"><a><div class="nt_photo blur_img"></div><div class="nt_bot nt_bgco"><h2>loading...</h2><h3>loading...</h3><h3>最後上線時間：loading... </h3></div></a></div></li>';
                        //     this.csrData = csrdDataPre;
                        // })
                        this.dataList.forEach((row, index) => {
                            let umeta = row.rawData.user_meta;
                            if(varCheck(umeta.city)){
                                umeta.city = umeta.city.split(",");
                                umeta.area = umeta.area.split(",");
                            }

                          
                            let csrVar = '';
                            let csrVar2 = '';
                            let onerror = '';
                            let ASSET_SUBDOMAIN = '{{env("ASSET_SUBDOMAIN")}}';
                            let rowVisitorIsAdminWarned = row.visitorIsAdminWarned;
                            let umetaIsWarned = umeta.isWarned;

                            let rowEngroup = row.rawData.engroup;
                            let rowExchangePeriod = row.rawData.exchange_period;
                            let rowVisitorCheckRecommendedUser = row.visitorCheckRecommendedUser;

                            let rowVisitorIsVip = row.visitorIsVip;

                            let rowVisitorIsPhoneAuth = row.visitorIsPhoneAuth;
                            let rowVisitorIsAdvanceAuth = row.visitorIsAdvanceAuth;


                            let rowPrLog = row.rawData.pr_log;
                            let rowVisitorIsBlurAvatar = row.visitorIsBlurAvatar;

                            let umetaIsAvatarHidden = umeta.isAvatarHidden;
                            let umetaPic = umeta.pic;
                            let rowID = row.rawData.id;
                            let rowName = row.rawData.name;

                            let rowVisitorAge = row.visitorAge;
                            let rowVisitorIsOnline = row.visitorIsOnline;
                            let rowIsHideOnline = row.rawData.is_hide_online;
                            
                            let umetaIsHideArea = umeta.isHideArea;
                            let umetaArea = umeta.area;
                            
                            let umetaIsHideOccupation = umeta.isHideOccupation;
                            let umetaOccupation = umeta.occupation;
                            let rowVisitorExchangePeriodName = row.visitorExchangePeriodName;
                            let rowVisitorValueAddedServiceStatusHideOnline = row.visitorValueAddedServiceStatusHideOnline;
                            let rowHideOnlineTime = row.rawData.hide_online_time;
                            let rowLastLogin = row.rawData.last_login;
                            
                            // csrData +='<li class="nt_fg">';
                            if(rowEngroup==2){
                                if(rowExchangePeriod==2){
                                    csrData += '<li class="nt_fg vvip_bg1">';
                                }else{
                                    csrData +='<li class="nt_fg vvip_bg2">';
                                }
                            }else{
                                csrData +='<li class="nt_fg vvip_bg2">';
                            }

                            csrData +='<div class="n_seicon">';
                            if(umetaIsWarned==1 || rowVisitorIsAdminWarned==1){
                                csrData +='<div class="hoverTip">';
                                    csrData +='<div class="tagText" data-toggle="popover" data-content="此會員為警示會員，與此會員交流務必提高警覺！">';
                                    if(this.userIsVip==1){
                                        csrData +='<img src="/new/images/a5.png">';
                                    }else{
                                        csrData +='<img src="/new/images/b_5.png">';
                                    }
                                
                                    csrData +='</div>';
                                csrData +='</div>';
                                
                            }else if(varCheck(rowVisitorCheckRecommendedUser['description']) && rowVisitorCheckRecommendedUser['description'] !== null && rowEngroup == 2){
                                csrData +='<div class="hoverTip">';
                                    csrData +='<div class="tagText" data-toggle="popover" data-content="新進甜心是指註冊未滿30天的新進會員，建議男會員可以多多接觸，不過要注意是否為八大行業人員。">';
                                        if(this.userIsVip==1){
                                            csrData +='<img src="/new/images/a1.png">';
                                        }else{
                                            csrData +='<img src="/new/images/b_1.png">';
                                        }

                                    csrData +='</div>';
                                csrData +='</div>';
                            }else if(rowVisitorIsVip && rowEngroup == 1){
                                csrData +='<div class="hoverTip">';
                                    csrData +='<div class="tagText" data-toggle="popover" data-content="本站的付費會員。">';
                                        if(this.userIsVip==1){
                                            csrData +='<img src="/new/images/a4.png">';
                                        }else{
                                            csrData +='<img src="/new/images/b_4.png">';
                                        }
                                    csrData +='</div>';
                                csrData +='</div>';
                            }else{
                                if(this.userIsVip){
                                    csrVar = 'xa_ssbg';
                                }else{
                                    csrVar = '';
                                }
                                if(rowEngroup==2){
                                                           
                                    if(this.userIsVip==1){
                                        csrData +='<div class="hoverTip '+csrVar+'">';
                                        if(rowVisitorIsAdvanceAuth==1){
                                            csrData +='<div class="tagText"  data-toggle="popover" data-content="本站的進階認證會員，本會員通過本站的嚴格驗證，基本資料正確無誤。">';
                                            csrData +='<img src="/new/images/c_03.png">';
                                            csrData +='</div> ';
                                        }else if(rowVisitorIsAdvanceAuth==0){
                                            csrData +='<div class="tagText"  data-toggle="popover" data-content="通過本站手機驗證的會員。">';
                                            csrData +='<img src="/new/images/c_09.png">';
                                            csrData +='</div>  ';
                                            
                                        }
                                        if((rowVisitorIsAdvanceAuth==1 || rowVisitorIsAdvanceAuth==0) && rowVisitorIsPhoneAuth==1){
                                            csrData +='<span>丨</span>';
                                        }

                                        if(rowVisitorIsPhoneAuth==1 ){
                                            csrData +='<div class="tagText"  data-toggle="popover" data-content="通過本站手機驗證的會員。">';
                                            csrData +='<img src="/new/images/c_10.png">';
                                            csrData +='</div>';
                                        }
                                        csrData +='</div>';
                                    }else{
                                        csrData +='<div class="hoverTip '+csrVar+'">';
                                        if(rowVisitorIsAdvanceAuth==1 && rowVisitorIsPhoneAuth==1 ){
                                            csrData +='<div class="tagText"  data-toggle="popover" data-content="本站的進階認證會員，本會員通過本站的嚴格驗證，基本資料正確無誤。">';
                                            csrData +='<img src="/new/images/b_8x.png">';
                                            csrData +='</div> ';
                                        }else if(rowVisitorIsAdvanceAuth==0 && rowVisitorIsPhoneAuth==1 ){
                                            csrData +='<div class="tagText"  data-toggle="popover" data-content="通過本站手機驗證的會員。">';
                                            csrData +='<img src="/new/images/b_5x.png">';
                                            csrData +='</div>  ';
                                        }else{
                                            csrData +='<div class="tagText"  data-toggle="popover" data-content="通過本站手機驗證的會員。">';
                                            csrData +='<img src="/new/images/b_6.png">';
                                            csrData +='</div>  ';  
                                        } 
                                        csrData +='</div>';
                                    }
                                }
                            
                            }
                          
                            if(varCheck(rowPrLog)){
                                csrVar = rowPrLog.pr+"%;"; 
                            }else{
                                csrVar = "0%;";
                            }


                            if(rowEngroup == 1){
                                csrData +='<div class="tixright_a">';
                                    csrData +='<div class="span zi_sc">大方指數</div>';
                                        csrData +='<div class="font">';
                                            csrData +='<div class="vvipjdt tm_new">';
                                                csrData +='<div class="progress progress-striped vvipjdt_pre_a">';
                                                    csrData +='<div class="progress-bar progress_info_a" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:'+csrVar+'"'; 
                                                        csrData +='<span class="prfont_a">PR</span>';
                                                    csrData +='</div>';
                                                csrData +='</div>';
                                            csrData +='</div>';
                                        csrData +='</div>';
                                    csrData +='</div>';
                                
                            }
                            csrData +='</div>';

                        if(rowVisitorIsBlurAvatar==1) csrVar = 'blur_img';
                        if(umetaIsAvatarHidden == 1){
                            csrVar2 = 'makesomeerror';
                        } else {
                            csrVar2 = umetaPic;
                        }
                    
                        if (rowEngroup == 1){
                            onerror="this.src='/new/images/male.png'" ;
                        } else {
                            onerror="this.src='/new/images/female.png'";
                        }
                        csrData +='<a href="/dashboard/viewuser/'+rowID+'">';
                        csrData +='<div class="nt_photo '+csrVar+'"><img class="lazy" src="'+ASSET_SUBDOMAIN+csrVar2+'" data-original="'+csrVar2+'" onerror="'+onerror+'"/></div>';

                        if(rowEngroup == 2)
                        {
                            if(rowExchangePeriod == 2)
                            {
                                csrData +='<div class="nt_bot vvip_bgco1">';
                            }
                            else
                            {
                                csrData +='<div class="nt_bot vvip_bgco2">';
                            }
                        }
                        else
                        {
                            csrData +='<div class="nt_bot nt_bgco">';
                        }
                        // csrData +='<div class="nt_bot nt_bgco">';
                        csrData +='<h2>';
                        csrData +='<font class="left">'+rowName+'<span>'+rowVisitorAge+'歲</span></font>';
                        
                        if(this.userIsVip==1){
                            if(rowVisitorIsOnline==1 && rowIsHideOnline==0){
                                csrData +='<span class="onlineStatusSearch"></span>';
                            }
                        }else{
                            csrData +='<div class="onlineStatusNonVipSearch"><img src="/new/images/wsx.png"></div>';
                        }

                        csrData +='</h2>';
                        csrData +='<h3>';
                            
                        if(umeta.city !== ""){
                            umeta.city.forEach((row, index) => {
                                if (index==0){
                                    csrData +=  umeta.city[index];
                                    if(umetaIsHideArea == 0){
                                        csrData +=  umetaArea[index]+'  ';
                                    }
                                }else{
                                    
                                    csrData +=  '<span>'+umeta.city[index];
                                    if(umetaIsHideArea == 0){
                                        csrData += (umetaArea[index]+'</span>');
                                    }
                                }
                            })
                        }           
                        
                           
                        if(this.userIsVip==1){
                            if(umetaIsHideOccupation==0 && umetaOccupation !== "" && umetaOccupation != 'null' && umetaOccupation != null){
                                csrData +='<span style="margin-left: 0;">'+umetaOccupation+'</span>';
                            }
                        }else{
                            csrData +='<span style="margin-left: 10px;"><span style="padding-left: 5px;">職業</span><img src="/new/images/icon_35.png" class="nt_img"></span>';
                        }
                      
                        if(this.user.engroup==1){
                            csrData +='<i class="j_lxx">丨</i><span>'+rowVisitorExchangePeriodName.name+'</span>';
                            // if(this.userIsVip==1){
                            //     csrData +='<i class="j_lxx">丨</i><span>'+rowVisitorExchangePeriodName.name+'</span>';
                            // }else{
                            //     csrData +='<i class="j_lxx">丨</i><span>包養關係<img src="/new/images/icon_35.png" class="nt_img"></span>';
                            // }
                        }
                        
                        csrData +='</h3>';
                        csrData +='<h3>最後上線時間：';
                        if(rowVisitorValueAddedServiceStatusHideOnline==1 && rowIsHideOnline==1){
                            csrData += rowHideOnlineTime.substr(0, 11);
                        }else{
                            csrData += rowLastLogin.substr(0, 11);
                        }
                    
                        csrData +='</h3>';
                        csrData +='</div>';
                        csrData +='</a>';
                        csrData +='</li>';

                        this.csrData = csrData;
                        });
                    }else{
                        this.csrData = '<div class="fengsicon search"><img src="/new/images/loupe.png" class="feng_img"><span>沒有資料</span></div>';
                    }

                    $(document).ready(function(){
                        $('[data-toggle="popover"]').popover({
                            animated: 'fade',
                            placement: 'bottom',
                            trigger: 'hover',
                            html: true,
                            content: function () { return '<h4>' + $(this).data('content') + '</h4>'; }
                        });
                    });
                })
            .catch(function (error) { // 请求失败处理
                console.log(error);
            });
        }
        });

        function varCheck(variable){
            return (typeof variable !== 'undefined' && typeof variable !== undefined && typeof variable !== 'null' && typeof variable !== null && variable!==undefined && variable !=='undefined' && variable !== null && variable !=='null');
        }
    </script>
@endsection



