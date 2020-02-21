@extends('layouts.master')
@section('app-content')
<?php
if (isset($cur)) $orderNumber = $cur->id;
else $orderNumber = "";
$code = Config::get('social.payment.code');
?>
<script>
    function vipadditional() {
        $(".vipadd").toggle();
    }

    function getAge(birth) {
        birth = Date.parse(birth.replace('/-/g', "/"));
        var year = 1000 * 60 * 60 * 24 * 365;
        var now = new Date();
        var birthday = new Date(birth);
        var age = parseInt((now - birthday) / year);
        return age;
    }

    function check_engroup() {
        let original_en = "{{ $user->engroup }}";
        let after = $('input[name=engroup]:checked').val();
        let birth = $('select[name=year]').val()+'/'+$('select[name=month]').val()+'/'+$('select[name=day]').val();
        let age = getAge(birth);
        if (original_en != after && after != null) {
            let r = confirm("確定要改變帳號類型(甜心大哥/大姐、甜心寶貝)嗎？");
            //console.log(r);
            if (r) {
                $('#information').submit();
            } else {
                return false;
            }
        }
        if(age < 18){
            alert("您的年齡低於法定18歲，請於基本資料設定修改，否則您的資料將會被限制搜尋。");
            return false;
        }
        $('#information').submit();
    }

    function showDescription() {
        // $('.modal').animate(
        //     {opacity:0}
        //     ,'nornal'
        //     ,function(){ $('.modal').show(); }
        // );
        $('.wrap').fadeToggle(200);
    }

    function hideDescription() {
        // $('.modal').animate(
        //     {opacity:0,top:0}
        //     ,'normal'
        //     ,function(){ $('.modal').hide(); }
        // );
        $('.wrap').fadeToggle(200);
    }
</script>
<style>
    .pics {
        position: absolute;
        padding: 0;
        margin: 0;
        top: 40px;
        left: 0;
        right: 0;
        width: inherit;
        height: 100%;
        border: 2px solid red;
        padding: 10px;
        border-radius: 25px;
        box-shadow: 4px 4px 3px rgba(20%, 20%, 40%, 0.5);
        z-index: 999;
    }
    .upload-p{
        color: red;
        font-weight: bold;
        position: absolute;
        bottom: 5px;
        left: 175px;
    }
    .slimimg{
        width: 500px;
        position: relative;
    }
    .slimimg .imagelabel{
        position: absolute;
        left: 20px;
        font-size: 15px;
        top: 20px;
    }
    .slim {
        width: 70%;
        border-radius: 50%;
    }
    .slimimg  .upload-submit{
        position: absolute;
        left: 185px;
        bottom: -20px;
    }
    @media screen and (max-width: 768px) {
        .slimimg  .upload-submit{
            left: 135px;
        }
        .upload-p{
            left: 126px;
        }
    }

    @media  (max-width: 500px) {
        .slimimg{
            width: 400px;
            position: relative;
        }
         .slimimg  .upload-submit{
            left: 105px;
        }
        .upload-p{
            left: 97px;
        }

    }
    .wrap {
        display: none;
    }

    @if(isset($background) && isset($height))
        .description {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 1050;
            display: inline;
            overflow: hidden;
            outline: 0;
            background-image: url("{{ $background }}");
            background-color: #fff;
            background-repeat: no-repeat;
            background-size: 100%;
            margin: auto;
            width: 315px;
            height: {{ $height }};
            text-align: center;
            border-radius: .5em;
            -webkit-box-shadow: 0px 4px 20px 0px rgba(0,0,0,0.31);
            -moz-box-shadow: 0px 4px 20px 0px rgba(0,0,0,0.31);
            box-shadow: 0px 4px 20px 0px rgba(0,0,0,0.31);
        }
    @endif

    .description2{
        background: #ffffff;
        margin-top: 114px;
        margin-left: auto;
        margin-right: auto;
        padding: 1.5em;
        /*height: 45%;*/
        width: 86%;
        border-radius: .5em;
        -webkit-box-shadow: 0px 3px 22px 0px rgba(122,122,122,0.41);
        -moz-box-shadow: 0px 3px 22px 0px rgba(122,122,122,0.41);
        box-shadow: 0px 3px 22px 0px rgba(122,122,122,0.41);
    }

    .description3{
        background: #F2526C;
        color: white;
        margin-top: 28px;
        margin-left: auto;
        margin-right: auto;
        height: 40px;
        width: 86%;
        border-radius: 20px;
        display: flex;
        align-items:center;
        justify-content: center;
    }

    .description3 > span {
        font-size: large;
        font-weight: bold;
    }

    hr {
        border: 0;
        height: 2px;
        width: 60px;
        /*color: #F2526C;*/
        background: #F2526C;
        /*background-image: linear-gradient(to right, rgba(0,0,0,0), #F2526C, rgba(0,0,0,0));*/
    }

    .description > .description-button{
        float: right;
        text-align: center;
        margin-right: 6px;
        margin-top: 6px;
        z-index: 1050;
    }

    .description > .description-button > button {
        background-color: #4CAF50;
        color: white;
        font-weight: bold;
        border-radius: 50%;
        /* put the height and width of your image here */
        width: 24px;
        height: 24px;
        border: none;
        -webkit-box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.49);
        -moz-box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.49);
        box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.49);
        -webkit-box-shadow: inset 0px 0px 4px 0px rgb(41, 83, 41,0.49);
        -moz-box-shadow: inset 0px 0px 4px 0px rgba(41, 83, 41,0.49);
        box-shadow: inset 0px 0px 4px 0px rgba(41, 83, 41,0.49);
    }
</style>
<script>
    @if(isset($timeSet) && isset($countSet))
        function doCookieSetup(name, value) {
            var expires = new Date();
            //有效時間保存 2 天 2*24*60*60*1000
            expires.setTime(expires.getTime() + 172800000);
            document.cookie = name + "=" + escape(value) + ";expires=" + expires.toGMTString()
        }

        function getCookie(name) {
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
            document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        }

        function getCookieValueByIndex(startIndex) {
            var endIndex = document.cookie.indexOf(";", startIndex);
            if (endIndex == -1) endIndex = document.cookie.length;
            return unescape(document.cookie.substring(startIndex, endIndex));
        }

        function GetDateDiff(startTime, endTime, diffType) { 
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
            var div = document.createElement('div');
            div.appendChild(document.createTextNode(s));
            return div.innerHTML;
        }

        function htmldecode(s){
            var div = document.createElement('div');
            div.innerHTML = s;
            return div.innerText || div.textContent;
        }

        /*取得次數*/
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
                delete_cookie('count');
                delete_cookie('countTime');
            }
            if(GetDateDiff(countTime, now, "minute")<="{{$timeSet}}"){
                if(count>"{{(int)$countSet}}"){
                    console.log(count, "{{$countSet}}"); 
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
                    doCookieSetup('countTime',now);
                    bodyMain.onpaste = function(){
                        count++;
                        console.log(count);
                        doCookieSetup('count',count);
                    }
                }
            }
        });
    @endif
</script>
<link rel="stylesheet" href="/plugins/slim/css/slim.min.css">
<div class="m-portlet__head">
    <div class="m-portlet__head-tools">
        <ul class="nav nav-tabs m-tabs m-tabs-line m-tabs-line--left m-tabs-line--primary" role="tablist">
            @if (!str_contains(url()->current(), 'dashboard'))
                <li class="nav-item m-tabs__item d-none d-md-block">
                    <h4 class="nav-link m-tabs__link">
                        @if(isset($cur)){{ $cur->title }}@endif
                    </h4>
                </li>
            @endif
            @if (str_contains(url()->current(), 'dashboard'))
                <li class="nav-item m-tabs__item">
                    <a class="nav-link m-tabs__link" href="/user/view/{{ $user->id }}">
                        <i class="flaticon-share m--hide"></i>
                        @if(isset($cur) && $user->id == $cur->id)    
                            檢視自己的首頁
                        @else
                            首頁
                        @endif
                    </a>
                </li>
                <li class="nav-item m-tabs__item">
                    <a class="nav-link m-tabs__link {{ empty($tabName) || $tabName == 'm_user_profile_tab_1' ? 'active' : '' }}"
                        data-toggle="tab" href="#m_user_profile_tab_1" role="tab">
                        <i class="flaticon-share m--hide"></i>
                        基本資料
                    </a>
                </li>
                <li class="nav-item m-tabs__item">
                    <a class="nav-link m-tabs__link {{ empty($tabName) || $tabName == 'm_user_profile_tab_4' ? 'active' : '' }}"
                        data-toggle="tab" href="#m_user_profile_tab_4" role="tab">
                        <i class="flaticon-share m--hide"></i>
                        照片管理
                    </a>
                </li>
                <li class="nav-item m-tabs__item">
                    <a class="nav-link m-tabs__link {{ empty($tabName) || $tabName == 'm_user_profile_tab_2' ? 'active' : '' }}"
                        data-toggle="tab" href="#m_user_profile_tab_2" role="tab">
                        更改密碼
                    </a>
                </li>
                <li class="nav-item m-tabs__item">
                    <a class="nav-link m-tabs__link {{ empty($tabName) || $tabName == 'm_user_profile_tab_3' ? 'active' : '' }}"
                        data-toggle="tab" href="#m_user_profile_tab_3" role="tab">
                        設定
                    </a>
                </li>
            @elseif(isset($cur) && $user->id !== $cur->id)
                <li class="nav-item m-tabs__item d-md-none showli">
                    <a class="nav-link m-tabs__link" data-toggle="modal" href="#m_modal_1">
                        發信件
                    </a>
                </li>
                @if (isset($cur) && $user->isVip() && $user->id == $cur->id)
                    <li class="nav-item m-tabs__item d-md-none showli">
                        <form action="{!! url('dashboard/fav') !!}" class="nav-link m-tabs__link" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="userId" value="{{$user->id}}">
                            <input type="hidden" name="to" value="{{$cur->id}}">
                            <button type="submit" style="background: none; border: none; padding: 0">
                                收藏
                            </button>
                        </form>
                    </li>
                    <li class="nav-item m-tabs__item d-md-none showli">
                        <form action="{!! url('dashboard/block') !!}" class="nav-link m-tabs__link" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="userId" value="{{$user->id}}">
                            <input type="hidden" name="to" value="{{$cur->id}}">
                            <button type="submit" style="background: none; border: none; padding: 0">
                                封鎖
                            </button>
                        </form>
                    </li>
                @endif
                <li class="nav-item m-tabs__item d-md-none showli">
                    <form action="{!! url('dashboard/report') !!}" class="nav-link m-tabs__link" method="POST">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="userId" value="{{$user->id}}">
                        <input type="hidden" name="to" value="@if(isset($cur)) {{$cur->id}} @endif">
                        <button type="submit" style="background: none; border: none; padding: 0">
                            檢舉
                        </button>
                    </form>
                </li>

                @if (isset($cur) && $user->isVip() && $user->id != $cur->id)
                    <li class="nav-item m-tabs__item d-md-none">
                        <form action="{!! url('dashboard/fav') !!}" class="m-nav__link nav-link m-tabs__link"
                                method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="userId" value="{{$user->id}}">
                            <input type="hidden" name="to" value="{{$cur->id}}">
                            <button type="submit" style="background: none; border: none; padding: 0">
                                <span class="m-nav__link-text">收藏</span>
                            </button>
                        </form>
                    </li>

                    <?php $isBlocked = \App\Models\Blocked::isBlocked($user->id, $cur->id);?>
                    @if(!$isBlocked)
                        <li class="nav-item m-tabs__item d-md-none">
                            <form action="{!! url('dashboard/block') !!}" class="m-nav__link nav-link m-tabs__link"
                                    method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="userId" value="{{$user->id}}">
                                <input type="hidden" name="to" value="{{$cur->id}}">
                                <button type="submit" style="background: none; border: none; padding: 0">
                                    <span class="m-nav__link-text">封鎖</span>
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item m-tabs__item d-md-none">
                            <form action="{!! url('dashboard/unblock') !!}"
                                    class="m-nav__link nav-link m-tabs__link" method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="userId" value="{{$user->id}}">
                                <input type="hidden" name="to" value="{{$cur->id}}">
                                <button type="submit" style="background: none; border: none; padding: 0">
                                    <span class="m-nav__link-text">解除封鎖</span>
                                </button>
                            </form>
                        </li>
                    @endif
                @endif

                @if ($user->engroup == 1 && isset($cur) && $user->id != $cur->id)
                    @if(!\App\Models\Tip::isComment($user->id, $cur->id) && $user->isVip() && \App\Models\Tip::isCommentNoEnd($user->id, $cur->id))
                        <li class="nav-item m-tabs__item d-md-none">
                            @include('partials.tip-comment')
                        </li>
                    @else
                        <li class="nav-item m-tabs__item d-md-none">
                            @include('partials.tip-invite')
                        </li>
                    @endif
                @endif
            @endif
        </ul>
        @if(isset($cur) && $user->id !== $cur->id && isset($description))
            <img src="{{ $button }}" alt="" height="30px" style="margin: 20px 0 20px 0; float: right; right: 0;" onclick="showDescription()">
            {{-- https://codepen.io/rppld/pen/vOvdyQ  --}}

            <div class="wrap">
                <div class="description">
                    <div class="description-button">
                        <button onclick="hideDescription()" class="description-button">X</button>
                    </div>
                    <div class="description2">
                        <h4>{{ $title }}</h4>
                        <hr>
                        <p>{!! $description !!}</p>
                    </div>
                    <div class="description3">
                        <span>推薦指數</span>{!! $stars !!}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
<div class="tab-content">
    <div class="tab-pane {{ empty($tabName) || $tabName == 'm_user_profile_tab_1' ? 'active' : '' }}"
            id="m_user_profile_tab_1">
        <?php if (!isset($user)) {
            $umeta = null;
        } else {
            $umeta = $user->meta_();
            if(isset($umeta->city)){
                $umeta->city = explode(",",$umeta->city);
                $umeta->area = explode(",",$umeta->area);
            }
        }
        ?>
        <?php if (!isset($cur)) {
            $cmeta = null;
        } else {
            $cmeta = $cur->meta_();
            if(isset($cmeta->city) || isset($cmeta->area)){
                if(str_contains($cmeta->city, ',')){
                    $cmeta->city = explode(",",$cmeta->city);
                }
                if(str_contains($cmeta->area, ',')){
                    $cmeta->area = explode(",",$cmeta->area);
                }
            }
        } 
        ?>
        @if(str_contains(url()->current(), 'dashboard'))
            <form class="m-form m-form--fit m-form--label-align-right" method="POST" name="user_data" action="/dashboard" id="information">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="userId" value="{{$user->id}}">
                @endif
                <?php
                $female = (str_contains(url()->current(), 'dashboard') && $user->engroup == 2) || (isset($cmeta) && $cur->engroup == 2);
                //$female = $user->engroup == 2 && (isset($cmeta) && $cur->engroup == 2);
                //$female = (($user->engroup == 2) && ($cur->engroup == 2)) || ;
                ?>
                <div class="m-portlet__body">
                    <div class="form-group m-form__group m--margin-top-10 m--hide">
                        <div class="alert m-alert m-alert--default" role="alert">
                        </div>
                    </div>

                    @if (str_contains(url()->current(), 'dashboard'))
                        <div class="form-group m-form__group row">
                            <label for="example-text-input" class="col-lg-2 col-md-3 col-form-label">暱稱<span style="color:red">(必填)</span></label>
                            <div class="col-lg-7">
                                <input class="form-control m-input" name="name" type="text" maxlength="10"
                                        value="{{$user->name}}">
                            </div>
                        </div>
                    @endif
                    @if (str_contains(url()->current(), 'dashboard'))
                        <div class="form-group m-form__group row">
                            <label for="title" class="col-lg-2 col-md-3 col-form-label">標題<span style="color:red">(必填)</span></label>
                            <div class="col-lg-7">
                                <input class="form-control m-input" name="title" type="text" maxlength="20"
                                        value="{{$user->title}}">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label for="user_engroup" class="col-lg-2 col-md-3 col-form-label">帳號類型</label>
                            <div class="col-lg-7 form-inline">
                                @if($user->engroup_change == 0)
                                    <input class="form-control m-input" name="engroup" value="1"
                                            @if($user->engroup == 1) checked @endif type="radio"> 甜心大哥/大姐
                                    <input class="form-control m-input" name="engroup" value="2"
                                            @if($user->engroup == 2) checked @endif type="radio"> 甜心寶貝&nbsp;
                                    <a style="font-weight: bold; color: red">(注意：每個帳號僅能變更一次)</a>
                                @elseif($user->engroup_change > 0 && $user->engroup == 2) 甜心寶貝&nbsp;
                                @elseif($user->engroup_change > 0 && $user->engroup == 1) 甜心大哥/大姐
                                @endif
                            </div>
                            @else
                                <div class="form-group m-form__group">
                                    @if (isset($cmeta->pic))
                                        <div class="personal-image">
                                            @if($cmeta->isAvatarHidden == 1)
                                            @else
                                                <img src="{{$cmeta->pic}}"/>
                                                <a href="{{ route('reportPic', [$user->id, 'uid'.$cur->id]) }}">檢舉大頭照</a>
                                            @endif
                                        </div>
                                    @endif
                                    @if(isset($cur))
                                        <?php $pics = \App\Models\MemberPic::getSelf($cur->id) ?>
                                        @foreach ($pics as $pic)
                                            <div class="personal-image">
                                                @if($pic->isHidden == 1)
                                                @else
                                                    <img src="{{$pic->pic}}"/>
                                                    <a href="{{ route('reportPic', [$user->id, $pic->id, $cur->id]) }}">檢舉這張照片</a>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                    @endif
                                </div>
                                @if (!str_contains(url()->current(), 'dashboard'))
                                    <div class="form-group m-form__group row d-md-none">
                                        <span>@if(isset($cmeta)){{$cmeta->title}}@endif</span>
                                    </div>
                                @endif
                                <div class="m-form__seperator m-form__seperator--dashed m-form__seperator--space-2x"></div>
                                    <!--  個人資料首頁 -->
                                @if(str_contains(url()->current(), 'dashboard'))
                                    <div class="form-group m-form__group row">
                                        <label  class="col-form-label col-lg-2 col-sm-12">
                                            隱藏鄉鎮區
                                        </label>
                                        <div class="col-form-label col-lg-2 col-sm-12">
                                            <input type="hidden" name="isHideArea" value="0">
                                            <input type="checkbox" name="isHideArea"
                                                    @if($umeta->isHideArea == true) checked
                                                    @endif value="1">
                                        </div>
                                        <label  class="col-form-label col-lg-2 col-sm-12">
                                            新增縣市
                                        </label>
                                        <div class="col-form-label col-lg-2 col-sm-12">
                                            <button type="button" id="add_county" class="" name="button">+</button>
                                        </div>
                                    </div>
                                    <div id="county">
                                    @if(isset($umeta->city))
                                        @if(is_array($umeta->city))
                                            @foreach($umeta->city as $key => $cityval)
                                                <div class="form-group m-form__group row twzipcode" id="twzipcode">
                                                    <label class="col-form-label col-lg-2 col-sm-12">縣市</label>
                                                    <div class="col-lg-5 col-md-10 col-sm-12">
                                                        <div class="twzip" data-role="county" data-name="@if($key != 0 )city{{$key}}@else{{'city'}}@endif"
                                                                data-value="{{$umeta->city[$key]}}">
                                                        </div>
                                                        <div class="twzip" data-role="district" data-name="@if($key != 0 )area{{$key}}@else{{'area'}}@endif"
                                                                data-value="{{$umeta->area[$key]}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="form-group m-form__group row twzipcode" id="twzipcode">
                                                <label class="col-form-label col-lg-2 col-sm-12">縣市</label>
                                                <div class="col-lg-5 col-md-10 col-sm-12">
                                                    <div class="twzip" data-role="county" data-name="city" data-value="{{$umeta->city}}">
                                                    </div>
                                                    <div class="twzip" data-role="district" data-name="area" data-value="{{$umeta->area}}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <div class="form-group m-form__group row twzipcode" id="twzipcode">
                                            <label class="col-form-label col-lg-2 col-sm-12">縣市</label>
                                            <div class="col-lg-5 col-md-10 col-sm-12">
                                                <div class="twzip" data-role="county" data-name="city"
                                                        data-value="">
                                                </div>
                                                <div class="twzip" data-role="district" data-name="area"
                                                        data-value="">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    </div>
                                @else
                                    <div class="form-group m-form__group row" id="twzipcode">
                                        <label class="col-form-label col-lg-2 col-sm-12">縣市</label>
                                        <div class="col-lg-5 col-md-10 col-sm-12">
                                        @if (isset($cmeta) && !$cmeta->isHideArea)
                                            @if(is_array($cmeta->city))
                                                <input class="form-control m-input" disabled
                                                    value="@foreach($cmeta->city as $key => $cityval){{$cmeta->city[$key]}} - {{$cmeta->area[$key]}}  @endforeach">
                                            @else
                                                <input class="form-control m-input" disabled
                                                    value="{{$cmeta->city}} - {{$cmeta->area}}">
                                            @endif
                                        @else
                                            @if(is_array($cmeta->city))
                                                <input class="form-control m-input" disabled
                                                       value="@foreach($cmeta->city as $key => $cityval){{$cmeta->city[$key]}} - {{$cmeta->area[$key]}}  @endforeach">
                                            @else
                                                <input class="form-control m-input" disabled
                                                       value="@if(isset($cmeta)){{$cmeta->city}}@endif">
                                            @endif
                                        @endif
                                        </div>
                                    </div>
                                @endif
                                            @if ($user->engroup == 2 && str_contains(url()->current(), 'dashboard'))
                                                <div class="form-group m-form__group row twzipcode"><label
                                                            class="col-form-label col-lg-2 col-sm-12">拒絕接受搜索縣市</label>

                                                    <div class="col-lg-5 col-md-10 col-sm-12">
                                                        @if (str_contains(url()->current(), 'dashboard'))
                                                            <div class="twzip" data-role="county"
                                                                    data-name="blockcity"
                                                                    data-value="{{$umeta->blockcity}}">
                                                            </div>
                                                            <div class="twzip" data-role="district"
                                                                    data-name="blockarea"
                                                                    data-value="{{$umeta->blockarea}}">
                                                            </div>
                                                        @else
                                                            <input class="form-control m-input" disabled
                                                                    value="@if(isset($cmeta)){{$cmeta->blockcity}} {{$cmeta->blockarea}}@endif">
                                                        @endif
                                                    </div>

                                                </div>
                                            @endif


                                            <div class="form-group m-form__group row">
                                                @if (str_contains(url()->current(), 'dashboard'))
                                                    <label class="col-form-label col-lg-2 col-sm-12">預算&nbsp;<span
                                                                style="color:red">(必填)</span></label>
                                                @else
                                                    <label class="col-form-label col-lg-2 col-sm-12">預算</label>
                                                @endif
                                                <div class="col-lg-4 col-md-9 col-sm-12">
                                                    @if (str_contains(url()->current(), 'dashboard'))
                                                        <select class="form-control m-bootstrap-select m_selectpicker"
                                                                name="budget">
                                                            <option value="">請選擇</option>
                                                            <option value="基礎"
                                                                    @if($umeta->budget == '基礎') selected @endif>基礎
                                                            </option>
                                                            <option value="進階"
                                                                    @if($umeta->budget == '進階') selected @endif>進階
                                                            </option>
                                                            <option value="高級"
                                                                    @if($umeta->budget == '高級') selected @endif>高級
                                                            </option>
                                                            <option value="最高"
                                                                    @if($umeta->budget == '最高') selected @endif>最高
                                                            </option>
                                                            <option value="可商議"
                                                                    @if($umeta->budget == '可商議') selected @endif>可商議
                                                            </option>
                                                        </select>
                                                    @else
                                                        <input class="form-control m-input" disabled
                                                                value="@if(isset($cmeta)){{$cmeta->budget}}@endif">
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group m-form__group row">
                                                <label class="col-form-label col-lg-2 col-sm-12"> @if(str_contains(url()->current(), 'dashboard'))
                                                        出生日期&nbsp;<span style="color:red">(必填)</span>@else 年齡 @endif
                                                </label>
                                                <div class="col-lg-4 col-md-9 col-sm-12 form-inline">
                                                    @if (str_contains(url()->current(), 'dashboard'))
                                                        <select name="year" class="form-control"></select>年
                                                        <select name="month" class="form-control">
                                                            <option value="1" @if($month == '01') selected @endif>
                                                                1
                                                            </option>
                                                            <option value="2" @if($month == '02') selected @endif>
                                                                2
                                                            </option>
                                                            <option value="3" @if($month == '03') selected @endif>
                                                                3
                                                            </option>
                                                            <option value="4" @if($month == '04') selected @endif>
                                                                4
                                                            </option>
                                                            <option value="5" @if($month == '05') selected @endif>
                                                                5
                                                            </option>
                                                            <option value="6" @if($month == '06') selected @endif>
                                                                6
                                                            </option>
                                                            <option value="7" @if($month == '07') selected @endif>
                                                                7
                                                            </option>
                                                            <option value="8" @if($month == '08') selected @endif>
                                                                8
                                                            </option>
                                                            <option value="9" @if($month == '09') selected @endif>
                                                                9
                                                            </option>
                                                            <option value="10" @if($month == '10') selected @endif>
                                                                10
                                                            </option>
                                                            <option value="11" @if($month == '11') selected @endif>
                                                                11
                                                            </option>
                                                            <option value="12" @if($month == '12') selected @endif>
                                                                12
                                                            </option>
                                                        </select>月
                                                        <select name="day" class="form-control"></select>日
                                                    @else
                                                        <?php
                                                        if (isset($cmeta)) {
                                                            $fromd = new DateTime($cmeta->birthdate);
                                                            $tod = new DateTime();
                                                            $age = $fromd->diff($tod)->y;
                                                        } else {
                                                            $fromd = null;
                                                            $tod = null;
                                                            $age = null;
                                                        }
                                                        ?>
                                                        <input class="form-control m-input" disabled
                                                                value="{{$age}}">
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group m-form__group row">
                                                @if (str_contains(url()->current(), 'dashboard'))
                                                    <label class="col-form-label col-lg-2 col-sm-12">身高
                                                        (cm)&nbsp;<span style="color:red">(必填)</span></label>
                                                @else
                                                    <label class="col-form-label col-lg-2 col-sm-12">身高 (cm)</label>
                                                @endif
                                                <div class="col-lg-7">
                                                    @if (str_contains(url()->current(), 'dashboard'))
                                                        <input class="form-control m-input" name="height"
                                                                type="number" id="input-height"
                                                                value="{{$umeta->height}}">
                                                    @else
                                                        <input class="form-control m-input" disabled
                                                                value="@if(isset($cmeta)){{ $cmeta->height }}@endif">
                                                    @endif
                                                </div>
                                            </div>

                                            @if ($female)
                                                <div class="form-group m-form__group row">
                                                    <label for="weight" class="col-lg-2 col-md-3 col-form-label">體重
                                                        (kg)</label>
                                                    <div class="col-lg-7">
                                                        @if (str_contains(url()->current(), 'dashboard'))
                                                            <input class="form-control m-input twzip" type="number"
                                                                    name="weight" id="input-weight"
                                                                    value="{{$umeta->weight}}">
                                                            <div class="twzip">
                                                                <input type="hidden" name="isHideWeight" value="0">
                                                                <input type="checkbox" name="isHideWeight"
                                                                        @if($umeta->isHideWeight == true) checked
                                                                        @endif value="1">
                                                            <!-- <input class="m-input" type="checkbox" id="isHideWeight" name="isHideWeight" value="{{ $umeta->isHideWeight }}"> -->
                                                                隱藏體重
                                                            </div>
                                                        @elseif (isset($cmeta) && !$cmeta->isHideWeight)
                                                            <input class="form-control m-input" disabled
                                                                    value="{{$cmeta->weight}}">
                                                        @else
                                                            <input class="form-control m-input" disabled value="">
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($female)
                                                <div class="form-group m-form__group row">
                                                    <label class="col-form-label col-lg-2 col-sm-12">Cup</label>
                                                    <div class="col-lg-5 col-md-10 col-sm-12">
                                                        @if (str_contains(url()->current(), 'dashboard'))
                                                            <select class="m-bootstrap-select m_selectpicker twzip"
                                                                    name="cup">
                                                                <option value="">請選擇</option>
                                                                <option value="A"
                                                                        @if($umeta->cup == 'A') selected @endif>A
                                                                </option>
                                                                <option value="B"
                                                                        @if($umeta->cup == 'B') selected @endif>B
                                                                </option>
                                                                <option value="C"
                                                                        @if($umeta->cup == 'C') selected @endif>C
                                                                </option>
                                                                <option value="D"
                                                                        @if($umeta->cup == 'D') selected @endif>D
                                                                </option>
                                                                <option value="E"
                                                                        @if($umeta->cup == 'E') selected @endif>E
                                                                </option>
                                                                <option value="F"
                                                                        @if($umeta->cup == 'F') selected @endif>F
                                                                </option>
                                                            </select>
                                                            <div class="twzip">
                                                                <input type="hidden" name="isHideCup" value="0">
                                                                <input type="checkbox" name="isHideCup"
                                                                        @if($umeta->isHideCup == true) checked
                                                                        @endif value="1">
                                                            <!-- <input class="m-input" type="checkbox" id="isHideCup" name="isHideCup" value="{{$umeta->isHideCup}}">  -->
                                                                隱藏
                                                            </div>
                                                        @elseif (isset($cmeta) && !$cmeta->isHideCup)
                                                            <input class="form-control m-input" disabled
                                                                    value="{{$cmeta->cup}}">
                                                        @else
                                                            <input class="form-control m-input" disabled value="">
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="form-group m-form__group row">
                                                <label class="col-form-label col-lg-2 col-sm-12">體型</label>
                                                <div class="col-lg-3 col-md-9 col-sm-12">
                                                    @if (str_contains(url()->current(), 'dashboard'))
                                                        <select class="form-control m-bootstrap-select m_selectpicker"
                                                                name="body">
                                                            <option value="">請選擇</option>
                                                            <option value="瘦"
                                                                    @if($umeta->body == '瘦') selected @endif>瘦
                                                            </option>
                                                            <option value="標準"
                                                                    @if($umeta->body == '標準') selected @endif>標準
                                                            </option>
                                                            <option value="微胖"
                                                                    @if($umeta->body == '微胖') selected @endif>微胖
                                                            </option>
                                                            <option value="胖"
                                                                    @if($umeta->body == '胖') selected @endif>胖
                                                            </option>
                                                        </select>
                                                    @else
                                                        <input class="form-control m-input" disabled
                                                                value="@if(isset($cmeta)){{$cmeta->body}}@endif">
                                                    @endif
                                                </div>


                                            </div>

                                            <div class="form-group m-form__group row">
                                                @if (str_contains(url()->current(), 'dashboard'))
                                                    <label class="col-form-label col-lg-2 col-sm-12">關於我&nbsp;<span
                                                                style="color:red">(必填)</span></label>
                                                @else
                                                    <label class="col-form-label col-lg-2 col-sm-12">關於我</label>
                                                @endif
                                                <div class="col-lg-8">
                                                    @if (str_contains(url()->current(), 'dashboard'))
                                                        <textarea class="form-control m-input" type="textarea"
                                                                    id="about" name="about" rows="3"
                                                                    maxlength="300">{{$umeta->about}}</textarea>
                                                    @else
                                                        <textarea class="form-control m-input"
                                                                    disabled>@if(isset($cmeta)){{$cmeta->about}}@endif</textarea>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group m-form__group row">
                                                @if (str_contains(url()->current(), 'dashboard'))
                                                    <label class="col-form-label col-lg-2 col-sm-12">期待的約會模式&nbsp;<span
                                                                style="color:red">(必填)</span></label>
                                                @else
                                                    <label class="col-form-label col-lg-2 col-sm-12">期待的約會模式</label>
                                                @endif
                                                <div class="col-lg-8">
                                                    @if (str_contains(url()->current(), 'dashboard'))
                                                        <textarea class="form-control m-input" type="textarea"
                                                                    name="style" rows="3"
                                                                    maxlength="300">{{$umeta->style}}</textarea>
                                                    @else
                                                        <textarea class="form-control m-input" disabled
                                                                    value="">@if(isset($cmeta)){{$cmeta->style}}@endif</textarea>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="m-form__seperator m-form__seperator--dashed m-form__seperator--space-2x"></div>

                                            @if($female)
                                                <div class="form-group m-form__group row">
                                                    <label class="col-form-label col-lg-2 col-sm-12">現況</label>
                                                    <div class="col-lg-4 col-md-9 col-sm-12">
                                                        @if (str_contains(url()->current(), 'dashboard'))
                                                            <select class="form-control m-bootstrap-select m_selectpicker"
                                                                    name="situation">
                                                                <option value="">請選擇</option>
                                                                <option value="學生"
                                                                        @if($umeta->situation == '學生') selected @endif>
                                                                    學生
                                                                </option>
                                                                <option value="待業"
                                                                        @if($umeta->situation == '待業') selected @endif>
                                                                    待業
                                                                </option>
                                                                <option value="休學"
                                                                        @if($umeta->situation == '休學') selected @endif>
                                                                    休學
                                                                </option>
                                                                <option value="打工"
                                                                        @if($umeta->situation == '打工') selected @endif>
                                                                    打工
                                                                </option>
                                                                <option value="上班族"
                                                                        @if($umeta->situation == '上班族') selected @endif>
                                                                    上班族
                                                                </option>
                                                            </select>
                                                        @else
                                                            <input class="form-control m-input" disabled
                                                                    value="@if(isset($cmeta)){{$cmeta->situation}}@endif">
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            @if(!$female)
                                                <div class="form-group m-form__group row">
                                                    <label class="col-form-label col-lg-2 col-sm-12">產業</label>
                                                    <div class="col-lg-7 col-md-9 col-sm-12">
                                                        @if (str_contains(url()->current(), 'dashboard'))
                                                            <select class="form-control m-bootstrap-select m_selectpicker twzip"
                                                                    name="domainType" id="domainType"
                                                                    onchange="setDomain(0);">
                                                                <option value="">請選擇</option>
                                                                <option value="資訊科技"
                                                                        @if($umeta->domainType == '資訊科技') selected @endif>
                                                                    資訊科技
                                                                </option>
                                                                <option value="傳產製造"
                                                                        @if($umeta->domainType == '傳產製造') selected @endif>
                                                                    傳產製造
                                                                </option>
                                                                <option value="工商服務"
                                                                        @if($umeta->domainType == '工商服務') selected @endif>
                                                                    工商服務
                                                                </option>
                                                                <option value="民生服務"
                                                                        @if($umeta->domainType == '民生服務') selected @endif>
                                                                    民生服務
                                                                </option>
                                                                <option value="文教傳播"
                                                                        @if($umeta->domainType == '文教傳播') selected @endif>
                                                                    文教傳播
                                                                </option>
                                                            </select>
                                                            <select class="form-control m-bootstrap-select m_selectpicker twzip"
                                                                    name="domain" id="domain">
                                                                @if(isset($cmeta->domain))
                                                                    <option value="{{ $cmeta->domain }}"
                                                                            selected>{{ $cmeta->domain }}</option>
                                                                @else
                                                                    <option value="" selected>請選擇</option>
                                                                @endif
                                                            </select>
                                                        @else
                                                            <input class="form-control m-input" disabled
                                                                    value="@if(isset($cmeta)){{$cmeta->domainType}}@endif">
                                                            <input class="form-control m-input" disabled
                                                                    value="@if(isset($cmeta)){{$cmeta->domain}}@endif">
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            @if($female)
                                                <div class="form-group m-form__group row">
                                                    <label class="col-form-label col-lg-2 col-sm-12">職業</label>
                                                    <div class="col-lg-7 col-md-9 col-sm-12">
                                                        @if (str_contains(url()->current(), 'dashboard'))
                                                            <select class="form-control m-bootstrap-select m_selectpicker twzip"
                                                                    name="occupation">
                                                                <option value="">請選擇</option>
                                                                <option value="學生"
                                                                        @if($umeta->occupation == '學生') selected @endif>
                                                                    學生
                                                                </option>
                                                                <option value="無業"
                                                                        @if($umeta->occupation == '無業') selected @endif>
                                                                    無業
                                                                </option>
                                                                <option value="人資"
                                                                        @if($umeta->occupation == '人資') selected @endif>
                                                                    人資
                                                                </option>
                                                                <option value="業務銷售"
                                                                        @if($umeta->occupation == '業務銷售') selected @endif>
                                                                    業務銷售
                                                                </option>
                                                                <option value="行銷企劃"
                                                                        @if($umeta->occupation == '行銷企劃') selected @endif>
                                                                    行銷企劃
                                                                </option>
                                                                <option value="行政助理"
                                                                        @if($umeta->occupation == '行政助理') selected @endif>
                                                                    行政助理
                                                                </option>
                                                                <option value="專案管理"
                                                                        @if($umeta->occupation == '專案管理') selected @endif>
                                                                    專案管理
                                                                </option>
                                                                <option value="餐飲類服務業"
                                                                        @if($umeta->occupation == '餐飲類服務業') selected @endif>
                                                                    餐飲類服務業
                                                                </option>
                                                                <option value="旅遊類服務業"
                                                                        @if($umeta->occupation == '旅遊類服務業') selected @endif>
                                                                    旅遊類服務業
                                                                </option>
                                                                <option value="美容美髮美甲芳療"
                                                                        @if($umeta->occupation == '美容美髮美甲芳療') selected @endif>
                                                                    美容美髮美甲芳療
                                                                </option>
                                                                <option value="操作員"
                                                                        @if($umeta->occupation == '操作員') selected @endif>
                                                                    操作員
                                                                </option>
                                                                <option value="文字工作者"
                                                                        @if($umeta->occupation == '文字工作者') selected @endif>
                                                                    文字工作者
                                                                </option>
                                                                <option value="學術研究"
                                                                        @if($umeta->occupation == '學術研究') selected @endif>
                                                                    學術研究
                                                                </option>
                                                                <option value="教育輔導"
                                                                        @if($umeta->occupation == '教育輔導') selected @endif>
                                                                    教育輔導
                                                                </option>
                                                                <option value="金融營業交易"
                                                                        @if($umeta->occupation == '金融營業交易') selected @endif>
                                                                    金融營業交易
                                                                </option>
                                                                <option value="財務會計"
                                                                        @if($umeta->occupation == '財務會計') selected @endif>
                                                                    財務會計
                                                                </option>
                                                                <option value="總機秘書櫃檯"
                                                                        @if($umeta->occupation == '總機秘書櫃檯') selected @endif>
                                                                    總機秘書櫃檯
                                                                </option>
                                                                <option value="法務記帳代書"
                                                                        @if($umeta->occupation == '法務記帳代書') selected @endif>
                                                                    法務記帳代書
                                                                </option>
                                                                <option value="資訊軟體"
                                                                        @if($umeta->occupation == '資訊軟體') selected @endif>
                                                                    資訊軟體
                                                                </option>
                                                                <option value="客服"
                                                                        @if($umeta->occupation == '客服') selected @endif>
                                                                    客服
                                                                </option>
                                                                <option value="貿易船務"
                                                                        @if($umeta->occupation == '貿易船務') selected @endif>
                                                                    貿易船務
                                                                </option>
                                                                <option value="交通運輸物流"
                                                                        @if($umeta->occupation == '交通運輸物流') selected @endif>
                                                                    交通運輸物流
                                                                </option>
                                                                <option value="倉管採購"
                                                                        @if($umeta->occupation == '倉管採購') selected @endif>
                                                                    倉管採購
                                                                </option>
                                                                <option value="設計美術"
                                                                        @if($umeta->occupation == '設計美術') selected @endif>
                                                                    設計美術
                                                                </option>
                                                                <option value="模特演員"
                                                                        @if($umeta->occupation == '模特演員') selected @endif>
                                                                    模特演員
                                                                </option>
                                                                <option value="傳播藝術"
                                                                        @if($umeta->occupation == '傳播藝術') selected @endif>
                                                                    傳播藝術
                                                                </option>
                                                            <!--  <option value="1" @if($umeta->job == '1') selected @endif>其他(自填)</option> -->
                                                            </select>
                                                            <div class="twzip">
                                                                <input type="hidden" name="isHideOccupation"
                                                                        value="0">
                                                                <input type="checkbox" name="isHideOccupation"
                                                                        @if($umeta->isHideOccupation == true) checked
                                                                        @endif value="1">
                                                                隱藏職業
                                                            </div>
                                                        @elseif (isset($cmeta) && (!$cmeta->isHideOccupation))
                                                            <input class="form-control m-input" disabled
                                                                    value="{{$cmeta->occupation}}">
                                                        @else
                                                            <input class="form-control m-input" disabled value="">
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <div class="form-group m-form__group row">
                                                    <label class="col-form-label col-lg-2 col-sm-12">職業</label>
                                                    <div class="col-lg-7 col-md-9 col-sm-12">
                                                        @if (str_contains(url()->current(), 'dashboard'))
                                                            <input class="form-control m-input" name="occupation"
                                                                    value="{{$umeta->occupation}}" maxlength="15">
                                                        @else
                                                            <input class="form-control m-input" name="occupation"
                                                                    disabled value="{{$umeta->occupation}}">
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif


                                            <div class="form-group m-form__group row">
                                                @if (str_contains(url()->current(), 'dashboard'))
                                                    <label class="col-form-label col-lg-2 col-sm-12">教育&nbsp;<span
                                                                style="color:red">(必填)</span></label>
                                                @else
                                                    <label class="col-form-label col-lg-2 col-sm-12">教育</label>
                                                @endif
                                                <div class="col-lg-4 col-md-9 col-sm-12">
                                                    @if (str_contains(url()->current(), 'dashboard'))
                                                        <select class="form-control m-bootstrap-select m_selectpicker"
                                                                name="education">
                                                            <option value="">請選擇</option>
                                                            <option value="國中"
                                                                    @if($umeta->education == '國中') selected @endif>
                                                                國中
                                                            </option>
                                                            <option value="高中"
                                                                    @if($umeta->education == '高中') selected @endif>
                                                                高中
                                                            </option>
                                                            <option value="大學"
                                                                    @if($umeta->education == '大學') selected @endif>
                                                                大學
                                                            </option>
                                                            <option value="研究所"
                                                                    @if($umeta->education == '研究所') selected @endif>
                                                                研究所
                                                            </option>
                                                        </select>
                                                    @else
                                                        <input class="form-control m-input" disabled
                                                                value="@if(isset($cmeta)){{$cmeta->education}}@endif">
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group m-form__group row">
                                                @if (str_contains(url()->current(), 'dashboard'))
                                                    <label class="col-form-label col-lg-2 col-sm-12">婚姻&nbsp;<span
                                                                style="color:red">(必填)</span></label>
                                                @else
                                                    <label class="col-form-label col-lg-2 col-sm-12">婚姻</label>
                                                @endif
                                                <div class="col-lg-4 col-md-9 col-sm-12">
                                                    @if (str_contains(url()->current(), 'dashboard'))
                                                        <select class="form-control m-bootstrap-select m_selectpicker"
                                                                name="marriage">
                                                            <option value="">請選擇</option>
                                                            <option value="已婚"
                                                                    @if($umeta->marriage == '已婚') selected @endif>已婚
                                                            </option>
                                                            <option value="分居"
                                                                    @if($umeta->marriage == '分居') selected @endif>分居
                                                            </option>
                                                            <option value="單身"
                                                                    @if($umeta->marriage == '單身') selected @endif>單身
                                                            </option>
                                                            @if($female)
                                                                <option value="有男友"
                                                                        @if($umeta->marriage == '有男友') selected @endif>
                                                                    有男友
                                                                </option>
                                                            @else
                                                                <option value="有女友"
                                                                        @if($umeta->marriage == '有女友') selected @endif>
                                                                    有女友
                                                                </option>
                                                            @endif
                                                        </select>
                                                    @else
                                                        <input class="form-control m-input" disabled
                                                                value="@if(isset($cmeta)){{$cmeta->marriage}}@endif">
                                                    @endif
                                                </div>
                                            </div>


                                            <div class="form-group m-form__group row">
                                                @if (str_contains(url()->current(), 'dashboard'))
                                                    <label class="col-form-label col-lg-2 col-sm-12">喝酒&nbsp;<span
                                                                style="color:red">(必填)</span></label>
                                                @else
                                                    <label class="col-form-label col-lg-2 col-sm-12">喝酒</label>
                                                @endif
                                                <div class="col-lg-4 col-md-9 col-sm-12">
                                                    @if (str_contains(url()->current(), 'dashboard'))
                                                        <select class="form-control m-bootstrap-select m_selectpicker"
                                                                name="drinking">
                                                            <option value="">請選擇</option>
                                                            <option value="不喝"
                                                                    @if($umeta->drinking == '不喝') selected @endif>不喝
                                                            </option>
                                                            <option value="偶爾喝"
                                                                    @if($umeta->drinking == '偶爾喝') selected @endif>
                                                                偶爾喝
                                                            </option>
                                                            <option value="常喝"
                                                                    @if($umeta->drinking == '常喝') selected @endif>常喝
                                                            </option>
                                                        </select>
                                                    @else
                                                        <input class="form-control m-input" disabled
                                                                value="@if(isset($cmeta)){{ $cmeta->drinking }}@endif">
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group m-form__group row">
                                                @if (str_contains(url()->current(), 'dashboard'))
                                                    <label class="col-form-label col-lg-2 col-sm-12">抽菸&nbsp;<span
                                                                style="color:red">(必填)</span></label>
                                                @else
                                                    <label class="col-form-label col-lg-2 col-sm-12">抽菸</label>
                                                @endif
                                                <div class="col-lg-4 col-md-9 col-sm-12">
                                                    @if (str_contains(url()->current(), 'dashboard'))
                                                        <select class="form-control m-bootstrap-select m_selectpicker"
                                                                name="smoking">
                                                            <option value="">請選擇</option>
                                                            <option value="不抽"
                                                                    @if($umeta->smoking == '不抽') selected @endif>不抽
                                                            </option>
                                                            <option value="偶爾抽"
                                                                    @if($umeta->smoking == '偶爾抽') selected @endif>
                                                                偶爾抽
                                                            </option>
                                                            <option value="常抽"
                                                                    @if($umeta->smoking == '常抽') selected @endif>常抽
                                                            </option>
                                                        </select>
                                                    @else
                                                        <input class="form-control m-input" disabled
                                                                value="@if(isset($cmeta)){{$cmeta->smoking}}@endif">
                                                    @endif
                                                </div>
                                            </div>

                                            @if (!$female)
                                                <div class="form-group m-form__group row">
                                                    <label class="col-form-label col-lg-2 col-sm-12">年收</label>
                                                    <div class="col-lg-5 col-md-10 col-sm-12">
                                                        @if (str_contains(url()->current(), 'dashboard'))
                                                            <select class="form-control m-bootstrap-select m_selectpicker"
                                                                    name="income">
                                                                <option value="">請選擇</option>
                                                                <option value="50萬以下"
                                                                        @if($umeta->income == '50萬以下') selected @endif>
                                                                    50萬以下
                                                                </option>
                                                                <option value="50~100萬"
                                                                        @if($umeta->income == '50~100萬') selected @endif>
                                                                    50~100萬
                                                                </option>
                                                                <option value="100-200萬"
                                                                        @if($umeta->income == '100-200萬') selected @endif>
                                                                    100-200萬
                                                                </option>
                                                                <option value="200-300萬"
                                                                        @if($umeta->income == '200-300萬') selected @endif>
                                                                    200-300萬
                                                                </option>
                                                                <option value="300萬以上"
                                                                        @if($umeta->income == '300萬以上') selected @endif>
                                                                    300萬以上
                                                                </option>

                                                            </select>
                                                        @else
                                                            <input class="form-control m-input" disabled
                                                                    name="income"
                                                                    value="@if(isset($cmeta)){{$cmeta->income}}@endif">
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group row">
                                                    @if (str_contains(url()->current(), 'dashboard'))
                                                        <label class="col-form-label col-lg-2 col-sm-12">資產&nbsp;<span
                                                                    style="color:red">(必填)</span></label>
                                                    @else
                                                        <label class="col-form-label col-lg-2 col-sm-12">資產</label>
                                                    @endif
                                                    <div class="col-lg-5 col-md-10 col-sm-12">
                                                        @if (str_contains(url()->current(), 'dashboard'))
                                                            <input class="form-control m-input" type="number"
                                                                    name="assets" value="{{$umeta->assets}}">
                                                        @else
                                                            <input class="form-control m-input" disabled
                                                                    value="@if(isset($cmeta)){{$cmeta->assets}}@endif">
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            @if(!str_contains(url()->current(), 'dashboard') && !$user->isVip())
                                                <div class="form-group m-form__group row">
                                                    <button id="more-information"
                                                            class="btn btn-danger m-btn m-btn--air m-btn--custom"
                                                            onclick="window.location= '{{ url("dashboard/upgrade") }}'">
                                                        進階資料
                                                    </button>
                                                </div>
                                            @endif

                                            @if ($user->isVip() && (!str_contains(url()->current(), 'dashboard') && isset($cur) && $user->id !== $cur->id))
                                                <div class="form-group m-form__group row">
                                                    <button class="btn btn-danger m-btn m-btn--air m-btn--custom"
                                                            id="vipadditional" onclick="vipadditional()">進階資料
                                                    </button>
                                                </div>

                                                <div class="form-group m-form__group row vipadd"
                                                        style="display:none">
                                                    <label class="col-form-label col-lg-2 col-sm-12">帳號建立時間</label>
                                                    <div class="col-lg-5 col-md-10 col-sm-12">
                                                        <input class="form-control m-input" disabled
                                                                value="{{$cur->created_at}}">
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group row vipadd"
                                                        style="display:none">
                                                    <label class="col-form-label col-lg-2 col-sm-12">登入時間</label>
                                                    <div class="col-lg-5 col-md-10 col-sm-12">
                                                        <input class="form-control m-input" disabled
                                                                value="{{$cur->last_login}}">
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group row vipadd"
                                                        style="display:none">
                                                    <label class="col-form-label col-lg-2 col-sm-12">被收藏次數</label>
                                                    <div class="col-lg-5 col-md-10 col-sm-12">
                                                        <input class="form-control m-input" disabled
                                                                value="{{$cur->favedCount()}}">
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group row vipadd"
                                                        style="display:none">
                                                    <label class="col-form-label col-lg-2 col-sm-12">收藏會員次數</label>
                                                    <div class="col-lg-5 col-md-10 col-sm-12">
                                                        <input class="form-control m-input" disabled
                                                                value="{{$cur->favCount()}}">
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group row vipadd"
                                                        style="display:none">
                                                    <label class="col-form-label col-lg-2 col-sm-12">車馬費邀請次數</label>
                                                    <div class="col-lg-5 col-md-10 col-sm-12">
                                                        <input class="form-control m-input" disabled
                                                                value="{{$cur->tipCount()}}">
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group row vipadd"
                                                        style="display:none">
                                                    <label class="col-form-label col-lg-2 col-sm-12">發信次數</label>
                                                    <div class="col-lg-5 col-md-10 col-sm-12">
                                                        <input class="form-control m-input" disabled
                                                                value="{{$cur->msgCount()}}">
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group row vipadd"
                                                        style="display:none">
                                                    <label class="col-form-label col-lg-2 col-sm-12">過去7天發信次數</label>
                                                    <div class="col-lg-5 col-md-10 col-sm-12">
                                                        <input class="form-control m-input" disabled
                                                                value="{{$cur->msgsevenCount()}}">
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group row vipadd"
                                                        style="display:none">
                                                    <label class="col-form-label col-lg-2 col-sm-12">是否封鎖我</label>
                                                    <div class="col-lg-5 col-md-10 col-sm-12">
                                                        <input class="form-control m-input" disabled
                                                                value="{{$cur->isBlocked($user->id)}}">
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group row vipadd"
                                                        style="display:none">
                                                    <label class="col-form-label col-lg-2 col-sm-12">是否看過我</label>
                                                    <div class="col-lg-5 col-md-10 col-sm-12">
                                                        <input class="form-control m-input" disabled
                                                                value="{{$cur->isSeen($user->id)}}">
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group row vipadd"
                                                        style="display:none">
                                                    <label class="col-form-label col-lg-2 col-sm-12">瀏覽其他會員次數</label>
                                                    <div class="col-lg-5 col-md-10 col-sm-12">
                                                        <input class="form-control m-input" disabled
                                                                value="{{$cur->visitCount()}}">
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group row vipadd"
                                                        style="display:none">
                                                    <label class="col-form-label col-lg-2 col-sm-12">被瀏覽次數</label>
                                                    <div class="col-lg-5 col-md-10 col-sm-12">
                                                        <input class="form-control m-input" disabled
                                                                value="{{$cur->visitedCount()}}">
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group row vipadd"
                                                        style="display:none">
                                                    <label class="col-form-label col-lg-2 col-sm-12">過去7天被瀏覽次數</label>
                                                    <div class="col-lg-5 col-md-10 col-sm-12">
                                                        <input class="form-control m-input" disabled
                                                                value="{{$cur->visitedsevenCount()}}">
                                                    </div>
                                                </div>

                                                @if ($female && $user->isVip() && isset($cur) && ($user->id != $cur->id))
                                                    <div class="form-group m-form__group row vipadd"
                                                            style="display:none">
                                                        <label class="col-form-label col-lg-2 col-sm-12">評價</label>
                                                        <div class="col-lg-5 col-md-10 col-sm-12">
                                                            <?php $comments = \App\Models\Tip::getAllComment($cur->id); ?>
                                                            @foreach($comments as $comment)
                                                                <input class="form-control m-input" disabled
                                                                        value="{{ $comment->message }}">
                                                                <br>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                    </div>
                                    @if (str_contains(url()->current(), 'dashboard'))
                                        <div class="m-portlet__foot m-portlet__foot--fit">
                                            <div class="m-form__actions">
                                                <div class="row">
                                                    <div class="col-lg-2">
                                                    </div>
                                                    <div class="col-lg-7">
                                                        <button type="button"
                                                                class="btn btn-danger m-btn m-btn--air m-btn--custom"
                                                                onclick="check_engroup();">更新資料
                                                        </button>&nbsp;&nbsp;
                                                        <button type="reset"
                                                                class="btn btn-outline-danger m-btn m-btn--air m-btn--custom">
                                                            取消
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                        </div>
            </form>

            @if (str_contains(url()->current(), 'dashboard'))
                <div class="tab-pane {{ empty($tabName) || $tabName == 'm_user_profile_tab_4' ? 'active' : '' }}" id="m_user_profile_tab_4">
                    @if (isset($umeta->pic))
                        <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="/dashboard/header2" enctype="multipart/form-data">
                    @else
                        <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="/dashboard/header" enctype="multipart/form-data">
                    @endif
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="userId" value="{{$user->id}}">
                        @if (isset($umeta->pic))
                            <div class="form-group m-form__group row slimimg" style="margin-bottom: 35px;">
                                <label class="col-form-label col-lg-1 twzip imagelabel" for="image">頭像照 </label>
                                <div class="upload-image slim" data-size="240,240" data-ratio="1:1" data-save-initial-image="true"
                                    data-label="點選圖片上傳，點選「編輯」可自行選擇截切畫面" data-max-file-size="8">
                                    <img src="{{$umeta->pic}}"/>
                                    <input type="file" name="slim[]" id="myCropper" />
                                </div>
                                <button type="submit"
                                        class="btn btn-danger m-btn m-btn--air m-btn--custom upload-submit">上傳</button>
                                @if($user->meta_()->isAvatarHidden == 1)
                                    <p class="upload-p">大頭照已被隱藏</p>
                                @endif
                            </div>
                        @else

                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-lg-1 twzip" for="image">頭像照 </label>
                            <div></div>
                            <label class="custom-file">
                                <input required type="file" id="image" class="custom-file-input" name="image"
                                        onchange="$(this).parent().children().last().text($(this).val().split('\\').pop());">
                                <span class="custom-file-control"></span>
                            </label>
                            <div class="col-lg-6" style="padding-top: 6px">
                                <button type="submit"
                                        class="btn btn-danger m-btn m-btn--air m-btn--custom upload-submit">上傳
                                </button>&nbsp;&nbsp;
                                <button type="reset" class="btn btn-outline-danger m-btn m-btn--air m-btn--custom">
                                    取消
                                </button>
                            </div>
                        </div>
                        @endif
                    </form>

                    <div class="m-form__seperator m-form__seperator--dashed m-form__seperator--space-2x"></div>

                    <form class="m-form m-form--fit m-form--label-align-right" method="POST"
                            action="/dashboard/image" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="userId" value="{{$user->id}}">
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-lg-1 twzip" for="images">生活照 </label>
                            <div class="input_field_weap">
                                <label class="custom-file">
                                    <input type="file" id="images" class="custom-file-input" name="images[]"
                                            onchange="$(this).parent().children().last().text($(this).val().split('\\').pop());">
                                    <span class="custom-file-control"></span>
                                </label>
                                <button type="button" id="add_image" class="" name="button">+</button>
                            </div>
                            <div class="col-lg-6" style="padding-top: 6px">
                                @if(\App\Models\MemberPic::getPicNums($user->id) >= 15)
                                    <button id="image-submit" type="submit"
                                            class="btn btn-danger m-btn m-btn--air m-btn--custom upload-submit"
                                            disabled>上傳
                                    </button>&nbsp;&nbsp;
                                    <button type="reset"
                                            class="btn btn-outline-danger m-btn m-btn--air m-btn--custom" disabled>
                                        取消
                                    </button>
                                @else
                                    <button id="image-submit" type="submit"
                                            class="btn btn-danger m-btn m-btn--air m-btn--custom upload-submit">上傳
                                    </button>&nbsp;&nbsp;
                                    <button type="reset"
                                            class="btn btn-outline-danger m-btn m-btn--air m-btn--custom">取消
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>

                    <div class="m-form__seperator m-form__seperator--dashed m-form__seperator--space-2x"></div>

                    <?php $pics = \App\Models\MemberPic::getSelf($user->id); ?>
                    @foreach ($pics as $pic)
                        <form class="m-form m-form--fit m-form--label-align-right" method="POST"
                                action="/dashboard/imagedel">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="userId" value="{{$user->id}}">
                            <input type="hidden" name="imgId" value="{{$pic->id}}">
                            <div class="form-group m-form__group">
                                <div class="upload-image" style="width:400px">
                                    <img src="{{$pic->pic}}"/>
                                    @if($pic->isHidden == 1) <p
                                            style="color: red; text-align: center; font-weight: bold;">
                                        此照片已被隱藏</p> @endif
                                </div>
                                <button type="submit" class="btn btn-danger m-btn m-btn--air m-btn--custom">刪除
                                </button>
                            </div>
                        </form>
                    @endforeach

                </div>
            @endif

            @if (str_contains(url()->current(), 'dashboard')  || (!str_contains(url()->current(), 'dashboard') && isset($cur) && $user->id == $cur->id))
                <div class="tab-pane" id="m_user_profile_tab_2">
                    <form class="m-form m-form--fit m-form--label-align-right" method="POST"
                            action="/user/password">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="userId" value="{{$user->id}}">
                        <div class="m-portlet__body">
                            <div class="form-group m-form__group m--margin-top-10 m--hide">
                                <div class="alert m-alert m-alert--default" role="alert"></div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-text-input" class="col-lg-3 col-form-label">現在的密碼*</label>
                                <div class="col-lg-7">
                                    <input class="form-control m-input" type="password" name="old_password">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-text-input" class="col-lg-3 col-form-label">密碼*</label>
                                <div class="col-lg-7">
                                    <input class="form-control m-input" name="new_password" type="password">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-text-input" class="col-lg-3 col-form-label">確認密碼*</label>
                                <div class="col-lg-7">
                                    <input class="form-control m-input" name="new_password_confirmation"
                                            type="password">
                                </div>
                            </div>
                            <div class="m-portlet__foot m-portlet__foot--fit">
                                <div class="m-form__actions">
                                    <div class="row">
                                        <div class="col-lg-2"></div>
                                        <div class="col-lg-7">
                                            <button type="submit"
                                                    class="btn btn-danger m-btn m-btn--air m-btn--custom">更新資料
                                            </button>&nbsp;&nbsp;
                                            <button type="reset"
                                                    class="btn btn-outline-danger m-btn m-btn--air m-btn--custom">取消
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
            @if (str_contains(url()->current(), 'dashboard')  || (!str_contains(url()->current(), 'dashboard') && isset($cur) && $user->id == $cur->id))
                <div class="tab-pane" id="m_user_profile_tab_3">
                    <form class="m-form m-form--fit m-form--label-align-right" method="POST"
                            action="/dashboard/settings">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="userId" value="{{$user->id}}">
                        <div class="m-portlet__body">
                            <div class="form-group m-form__group m--margin-top-10 m--hide">
                                <div class="alert m-alert m-alert--default" role="alert">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-text-input"
                                        class="col-lg-2 col-md-3 col-form-label">信息通知</label>
                                <div class="col-lg-7">
                                    <select class="form-control m-bootstrap-select m_selectpicker"
                                            name="notifmessage">
                                        <option value="收到即通知" @if($umeta->notifmessage == '收到即通知') selected @endif>
                                            收到即通知
                                        </option>
                                        <option value="每天通知一次"
                                                @if($umeta->notifmessage == '每天通知一次') selected @endif>每天通知一次
                                        </option>
                                        <option value="不通知" @if($umeta->notifmessage == '不通知') selected @endif>不通知
                                        </option>
                                    </select>
                                </div>
                            </div>
                            @if ($user->isVip())
                                <div class="form-group m-form__group row">
                                    <label for="example-text-input"
                                            class="col-lg-2 col-md-3 col-form-label">足跡通知</label>
                                    <div class="col-lg-7">
                                        <select class="form-control m-bootstrap-select m_selectpicker"
                                                name="notifhistory">
                                            <option value="收到即通知"
                                                    @if($umeta->notifhistory == '收到即通知') selected @endif>收到即通知
                                            </option>
                                            <option value="每天通知一次"
                                                    @if($umeta->notifhistory == '每天通知一次') selected @endif>每天通知一次
                                            </option>
                                            <option value="不通知" @if($umeta->notifhistory == '不通知') selected @endif>
                                                不通知
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group m-form__group row">
                                    <label for="example-text-input"
                                            class="col-lg-2 col-md-3 col-form-label">收信設定</label>
                                    <div class="col-lg-7">
                                        <select class="form-control m-bootstrap-select m_selectpicker"
                                                name="notifhistory">
                                            <option value="顯示普通會員信件"
                                                    @if($umeta->notifhistory == '顯示普通會員信件') selected @endif>顯示普通會員信件
                                            </option>
                                            <option value="顯示VIP會員信件"
                                                    @if($umeta->notifhistory == '顯示VIP會員信件') selected @endif>
                                                顯示VIP會員信件
                                            </option>
                                            <option value="顯示全部會員信件"
                                                    @if($umeta->notifhistory == '顯示全部會員信件') selected @endif>顯示全部會員信件
                                            </option>
                                        </select>
                                    </div>
                                </div>
                        @endif
                        <!--       <div class="form-group m-form__group row">
            <label for="example-text-input" class="col-lg-2 col-md-3 col-form-label">本周推薦通知</label>
            <div class="col-lg-7">
                <input class="form-control m-input" type="password" value="">
            </div>
        </div>
        <div class="form-group m-form__group row">
            <label for="example-text-input" class="col-lg-2 col-md-3 col-form-label">本周新人通知</label>
            <div class="col-lg-7">
                <input class="form-control m-input" type="password" value="">
            </div>
        </div> -->
                            <div class="m-portlet__foot m-portlet__foot--fit">
                                <div class="m-form__actions">
                                    <div class="row">
                                        <div class="col-lg-2"></div>
                                        <div class="col-9">
                                            <button type="submit"
                                                    class="btn btn-danger m-btn m-btn--air m-btn--custom">更新資料
                                            </button>&nbsp;&nbsp;
                                            <button type="reset"
                                                    class="btn btn-outline-danger m-btn m-btn--air m-btn--custom">取消
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endif

            @stop

            @if (str_contains(url()->current(), 'dashboard')  || (!str_contains(url()->current(), 'dashboard') && isset($cur) && $user->id == $cur->id))

@section ('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.0.0/cropper.min.js"></script>
<script src="/plugins/slim/js/slim.kickstart.min.js"></script>
<script>
    var domainJson = ({
        '請選擇': ['請選擇'],
        '資訊科技': ['軟體網路', '電信通訊', '光電光學', '半導體業', '電腦週邊', '電子相關'],
        '傳產製造': ['食品飲料', '紡織相關', '鞋類紡織', '家具家飾', '紙製製造', '印刷相關', '化學製造', '石油製造', '橡膠塑膠', '非金屬製造', '金屬製造', '機械設備', '電力機械', '運輸工具', '儀器醫材', '育樂用品', '其他製造', '物流倉儲', '營建土木', '農林漁牧', '礦業土石'],
        '工商服務': ['法律服務', '會計服務', '顧問研發', '人力仲介', '租賃業', '汽車維修', '徵信保全'],
        '民生服務': ['批發零售', '金融機構', '投資理財', '保險業', '電影業', '旅遊休閒', '美容美髮', '醫療服務', '環境衛生', '住宿服務', '餐飲服務'],
        '文教傳播': ['教育服務', '印刷出版', '藝文相關', '廣播電視', '廣告行銷', '政治社福']
    });

    setDomain(1);

    function setDomain(initial) {
        var domain = eval(domainJson);
        var type = $("#domainType").val();
        //console.log('type is ' + type);
        if (!initial) {
            $("#domain option").remove();
            $("#domain").append('<option value="">請選擇</option>');
        }
        for (var i in domain[type]) {
            //console.log(domain[type][i]);
            if (domain[type][i] != $("#domain option:selected").val()) {
                $("#domain").append('<option value="' + domain[type][i] + '">' + domain[type][i] + '</option>');
                $("#domain").selectpicker('refresh');
            }
        }
    }

    jQuery(document).ready(function () {
        @if(!$user->isAdmin())
            @if (!$umeta->isAllSet())
            alert('請寫上基本資料');
            @elseif (empty($umeta->pic))
            alert('請加上頭像照');
            @elseif ($umeta->age()<18)
            alert('您好，您的年齡低於法定18歲，請至個人基本資料設定修改，否則您的資料將會被限制搜尋。');
            @endif
        @endif
        var BootstrapDatepicker = function () {
                var t = function () {
                    $("#m_datepicker_1, #m_datepicker_1_validate").datepicker({
                        todayHighlight: !0,
                        orientation: "bottom left",
                        templates: {
                            leftArrow: '<i class="la la-angle-left"></i>',
                            rightArrow: '<i class="la la-angle-right"></i>'
                        }
                    }),
                        $("#m_datepicker_1_modal").datepicker({
                            todayHighlight: !0,
                            orientation: "bottom left",
                            templates: {
                                leftArrow: '<i class="la la-angle-left"></i>',
                                rightArrow: '<i class="la la-angle-right"></i>'
                            }
                        }),
                        $("#m_datepicker_2, #m_datepicker_2_validate").datepicker({
                            todayHighlight: !0,
                            orientation: "bottom left",
                            templates: {
                                leftArrow: '<i class="la la-angle-left"></i>',
                                rightArrow: '<i class="la la-angle-right"></i>'
                            }
                        }),
                        $("#m_datepicker_2_modal").datepicker({
                            todayHighlight: !0,
                            orientation: "bottom left",
                            templates: {
                                leftArrow: '<i class="la la-angle-left"></i>',
                                rightArrow: '<i class="la la-angle-right"></i>'
                            }
                        }),
                        $("#m_datepicker_3, #m_datepicker_3_validate").datepicker({
                            todayBtn: "linked",
                            clearBtn: !0,
                            todayHighlight: !0,
                            templates: {
                                leftArrow: '<i class="la la-angle-left"></i>',
                                rightArrow: '<i class="la la-angle-right"></i>'
                            }
                        }),
                        $("#m_datepicker_3_modal").datepicker({
                            todayBtn: "linked",
                            clearBtn: !0,
                            todayHighlight: !0,
                            templates: {
                                leftArrow: '<i class="la la-angle-left"></i>',
                                rightArrow: '<i class="la la-angle-right"></i>'
                            }
                        }),
                        $("#m_datepicker_4_1").datepicker({
                            orientation: "top left",
                            todayHighlight: !0,
                            templates: {
                                leftArrow: '<i class="la la-angle-left"></i>',
                                rightArrow: '<i class="la la-angle-right"></i>'
                            }
                        }),
                        $("#m_datepicker_4_2").datepicker({
                            orientation: "top right",
                            todayHighlight: !0,
                            templates: {
                                leftArrow: '<i class="la la-angle-left"></i>',
                                rightArrow: '<i class="la la-angle-right"></i>'
                            }
                        }),
                        $("#m_datepicker_4_3").datepicker({
                            orientation: "bottom left",
                            todayHighlight: !0,
                            templates: {
                                leftArrow: '<i class="la la-angle-left"></i>',
                                rightArrow: '<i class="la la-angle-right"></i>'
                            }
                        }),
                        $("#m_datepicker_4_4").datepicker({
                            orientation: "bottom right",
                            todayHighlight: !0,
                            templates: {
                                leftArrow: '<i class="la la-angle-left"></i>',
                                rightArrow: '<i class="la la-angle-right"></i>'
                            }
                        }),
                        $("#m_datepicker_5").datepicker({
                            todayHighlight: !0,
                            templates: {
                                leftArrow: '<i class="la la-angle-left"></i>',
                                rightArrow: '<i class="la la-angle-right"></i>'
                            }
                        }),
                        $("#m_datepicker_6").datepicker({
                            todayHighlight: !0,
                            templates: {
                                leftArrow: '<i class="la la-angle-left"></i>',
                                rightArrow: '<i class="la la-angle-right"></i>'
                            }
                        })
                };
                return {
                    init: function () {
                        t()
                    }
                }
            }();
        jQuery(document).ready(
            function () {
                BootstrapDatepicker.init()
            });
        var BootstrapSelect = function () {
            var t = function () {
                $(".m_selectpicker").selectpicker()
            };
            return {
                init: function () {
                    t()
                }
            }
        }();
        jQuery(document).ready(
            function () {
                BootstrapSelect.init()
            });

        $('.twzipcode').twzipcode({
            'detect': true, 'css': ['form-control twzip', 'form-control twzip', 'zipcode'], onCountySelect: function() {
                $("select[name='blockarea']").prepend('<option selected value="">全區</option>');
            }
        });
    });
</script>

<script type="text/javascript">
    jQuery(document).ready(function () {
        //Check File API support
        function uploadFiles() {
            if (window.File && window.FileList && window.FileReader) {
                var filesInput = document.getElementById("images");

                filesInput.addEventListener("change", function (event) {

                    var files = event.target.files; //FileList object
                    var output = document.getElementById("result");
                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];

                        //Only pics
                        if (!file.type.match('image'))
                            continue;

                        var picReader = new FileReader();

                        picReader.addEventListener("load", function (event) {

                            var picFile = event.target;
                            var div = document.createElement("div");

                            div.innerHTML = "<img class='thumbnail' src='" + picFile.result + "'" +
                                "title='" + picFile.name + "'/>";

                            output.insertBefore(div, null);

                        });
                        //Read the image
                        picReader.readAsDataURL(file);
                    }
                });
            }
        }

        // $("#images").on("change", function() {
        //  if($("#images")[0].files.length > 2) {
        //      alert("You can select only 2 images");
        //  } else {
        //     uploadFiles();
        //  }

        var max_fields = <?php echo \App\Models\MemberPic::getPicNums($user->id); ?>; //maximum input boxes allowed
        var wrapper = $(".input_field_weap"); //Fields wrapper
        var add_button = $("#add_image"); //Add button ID

        var x = 1; //initlal text box count
        $(add_button).click(function (e) { //on add input button click
            e.preventDefault();
            if (14 - max_fields >= x) { //max input box allowed
                x++; //text box increment
                $(wrapper).append('<div><label class="custom-file"><input type="file" class="custom-file-input" name="images[]" onchange="$(this).parent().children().last().text($(this).val())"><span class="custom-file-control"></span></label><a href="#" class="remove_field">&nbsp;Remove</a></div>'); //add input box
            } else {
                alert('最多上傳15張');
            }
        });

        $('#images').click(function (e) {
            //e.preventDefault();
            if (max_fields >= 15) {
                alert('最多上傳15張');
                e.preventDefault();
            }
        })

        $(wrapper).on("click", ".remove_field", function (e) { //user click on remove text
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
        });
        let county = $("#county")
        let add_county = $("#add_county")
        $(add_county).click(function(){
            if($(county).find('.twzipcode').length < 3) {
                let county_div = '<div class="form-group m-form__group row twzipcode" >';
                    county_div+= '<label class="col-form-label col-lg-2 col-sm-12">縣市</label>';
                    county_div+= '<div class="col-lg-5 col-md-10 col-sm-12">';
                    county_div+= '<div class="twzip" data-role="county" data-name="city'+$(county).find('.twzipcode').length+'" data-value=""></div>'
                    county_div+= '<div class="twzip" data-role="district" data-name="area'+$(county).find('.twzipcode').length+'" data-value=""></div>'
                    county_div+= '</div></div>'
                $(county).append(county_div)
                $('.twzipcode').twzipcode({
                    'detect': true, 'css': ['form-control twzip', 'form-control twzip', 'zipcode']
                });
            }else{
                alert('最多新增3筆')
            }
        })
    });

    @if (str_contains(url()->current(), 'dashboard'))
    var ysel = document.getElementsByName("year")[0],
        msel = document.getElementsByName("month")[0],
        dsel = document.getElementsByName("day")[0],
        firstTime = 0;
    for (var i = {{ date("Y") }}; i >= 1930; i--) {
        var opt = new Option();
        opt.value = opt.text = i;
        if (opt.value == {{ $year }}) {
            opt.selected = true;
        }
        ysel.add(opt);
    }
    ysel.addEventListener("change", validate_date);
    msel.addEventListener("change", validate_date);

    function validate_date() {
        var y = +ysel.value, m = msel.value, d = dsel.value;
        if (m === "2") {
            var mlength = 28 + (!(y & 3) && ((y % 100) !== 0 || !(y & 15)));
        } else {
            var mlength = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][m - 1];
        }
        dsel.length = 0;
        for (var i = 1; i <= mlength; i++) {
            var opt = new Option();
            opt.value = opt.text = i;
            if (i == d) {
                opt.selected = true;
            }
            if (opt.value == {{ $day }} && firstTime == 0) {
                opt.selected = true;
                firstTime = 1;
            }
            dsel.add(opt);
        }
    }
    validate_date();
    @endif
</script>
@stop

@endif
