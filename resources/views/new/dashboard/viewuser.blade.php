@extends('new.layouts.website')

@section('app-content')
    <style>
        .swiper-container {
            width: 100%;
            height: auto;
        }

        .swiper-slide {
            /*width: 100%;*/
            height: 280px;
            margin: 0 auto;
            padding: 0px;
            display: table
        }

        .swiper-slide img {
            /*width: 100%;*/
            max-width: 100%;
            height: 100%;
            display: block;
            margin: 0 auto;
        }

        @media (max-width:767px) {
            .swiper-container {
                width: 100%;
                height: auto;
            }
            .swiper-slide {
                /*width: 100%;*/
                height: 200px !important;
                margin: 0 auto;
                padding: 0px;
                display: table
            }
            .swiper-slide img {
                /*width: 100%;*/
                max-width: 100%;
                height: 100%;
                display: block;
                margin: 0 auto;
            }
        }
        @media (max-width:992px) {
            .swiper-container {
                width: 100%;
                height: auto;
            }
            .swiper-slide {
                /*width: 100%;*/
                height: 280px;
                margin: 0 auto;
                padding: 0px;
                display: table
            }
            .swiper-slide img {
                /*width: 100%;*/
                max-width: 100%;
                height: 100%;
                display: block;
                margin: 0 auto;
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
                                <div class="swiper-slide" data-type="avatar" data-sid="{{$to->id}}" data-pic_id=""><img src="{{$to->meta_()->pic}}"></div>

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
                                <li>
                                    <a onclick="show_chat()"><img src="/new/images/icon_06.png" class="tubiao_i"><span>發信</span></a>
                                </li>
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
                                    <a onclick="show_banned()"><img src="/new/images/icon_10.png" class="tubiao_i"><span>檢舉</span></a>
                                </li>
                                @if($user->isVip())
                                    <?php $isBlocked = \App\Models\Blocked::isBlocked($user->id, $to->id);?>
                                <li>
                                    @if($isBlocked)
                                    <a class="unblock"><img src="/new/images/icon_12_h.png" class="tubiao_i"><span>解除封鎖</span></a>
                                    @else
                                    <a onclick="show_block()"><img src="/new/images/icon_12.png" class="tubiao_i"><span>封鎖</span></a>
                                    @endif
                                </li>
                                @else

                                    <li>
                                        <img src="/new/images/icon_08.png" class="tubiao_i"><span>封鎖</span>
                                        <span><img src="/new/images/icon_36.png" class="tap-vip"></span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="bottub">
                            <ul>
                                <? $data = \App\Services\UserService::checkRecommendedUser($to);
                                echo $data['description'];?>
                                @if(isset($data['description']) && $to->engroup == 2)
                                <li><img src="/new/images/icon_19.png"><span>新進甜心</span></li>
                                @endif
                                @if(isset($data['description']) && $to->engroup == 1)
                                <li><img src="/new/images/icon_21.png"><span>優選會員</span></li>
                                @endif
{{--                            <li><img src="/new/images/icon_23.png"><span>財力認證</span></li>--}}
                                @if($to->isVip())
                                <li><img src="/new/images/icon_25.png"><span>VIP</span></li>
                                @endif
{{--                            <li><img src="/new/images/icon_27.png"><span>警示帳戶</span></li>--}}
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
                                    @endif

                                    @if(!empty($to->meta_()->budget))
                                    <dt>
                                        <span>預算</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->budget}}" disabled="disabled"></span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->age()))
                                    <dt>
                                        <span>年齡</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->age()}}" disabled="disabled"></span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->height))
                                    <dt>
                                        <span>身高（cm）</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->height}}" disabled="disabled"></span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->body) && $to->meta_()->body != null && $to->meta_()->body != 'null')
                                    <dt>
                                        <span>體型</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->body}}" disabled="disabled"></span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->cup) && $to->meta_()->isHideCup == '0')
                                    <dt>
                                        <span>CUP</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->cup}}" disabled="disabled"></span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->about))
                                    <dt>
                                        <span>關於我</span>
                                        <span><div class="select_xx03" >{!! nl2br($to->meta_()->about) !!}</div></span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->style))
                                    <dt>
                                        <span>期待的約會模式</span>
                                        <span><div class="select_xx03" >{!! nl2br($to->meta_()->style) !!}</div></span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->domainType) && $to->meta_()->domainType != null && $to->meta_()->domainType != 'null')
                                    <dt>
                                        <span>產業</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->domainType}}" disabled="disabled"></span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->occupation) && $to->meta_()->isHideOccupation == '0' && $user->isVip())
                                    <dt>
                                        <span>職業</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->occupation}}" disabled="disabled"></span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->education))
                                    <dt>
                                        <span>教育</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->education}}" disabled="disabled"></span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->marriage))
                                    <dt>
                                        <span>婚姻</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->marriage}}" disabled="disabled"></span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->drinking))
                                    <dt>
                                        <span>喝酒</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->drinking}}" disabled="disabled"></span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->smoking))
                                    <dt>
                                        <span>抽煙</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->smoking}}" disabled="disabled"></span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->income))
                                    <dt>
                                        <span>收入</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->income}}" disabled="disabled"></span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta_()->assets))
                                    <dt>
                                        <span>資產</span>
                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="{{$to->meta_()->assets}}" disabled="disabled"></span>
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
                                    <dt><span>帳號建立時間</span>@if($user->isVip())<font>{{substr($to->created_at,0,10)}}</font>@else <img src="/new/images/icon_35.png"> @endif</dt>
                                    <dt><span>登入時間</span>@if($user->isVip())<font>{{substr($to->last_login,0,10)}}</font>@else <img src="/new/images/icon_35.png"> @endif</dt>
                                    <dt><span>被收藏次數</span>@if($user->isVip()) <font>{{$be_fav_count}}</font> @else <img src="/new/images/icon_35.png"> @endif</dt>
                                    <dt><span>收藏會員次數</span>@if($user->isVip())<font>{{$fav_count}}</font>@else <img src="/new/images/icon_35.png"> @endif</dt>
                                    <dt><span>車馬費邀請次數</span>@if($user->isVip())<font>{{$tip_count}}</font>@else <img src="/new/images/icon_35.png"> @endif</dt>
                                    <dt><span>發信次數</span>@if($user->isVip())<font>{{$message_count}}</font>@else <img src="/new/images/icon_35.png"> @endif</dt>
                                    <dt><span>過去7天發信次數</span>@if($user->isVip())<font>{{$message_count_7}}</font>@else <img src="/new/images/icon_35.png"> @endif</dt>
                                    <dt><span>是否封鎖我</span>@if($user->isVip())<font>{{$is_block_mid}}</font>@else <img src="/new/images/icon_35.png"> @endif</dt>
                                    <dt><span>是否看過我</span>@if($user->isVip())<font>{{$is_visit_mid}}</font>@else <img src="/new/images/icon_35.png"> @endif</dt>
                                    <dt><span>瀏覽其他會員次數</span>@if($user->isVip())<font>{{$visit_other_count}}</font>@else <img src="/new/images/icon_35.png"> @endif</dt>
                                    <dt><span>被瀏覽次數</span>@if($user->isVip())<font>{{$be_visit_other_count}}</font>@else <img src="/new/images/icon_35.png"> @endif</dt>
                                    <dt><span>過去7天被瀏覽次數</span>@if($user->isVip())<font>{{$be_visit_other_count_7}}</font>@else <img src="/new/images/icon_35.png"> @endif</dt>
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
                <textarea name="content" cols="" rows="" class="n_nutext" placeholder="請輸入檢舉理由"></textarea>
                <div class="n_bbutton">
                    <button type="submit" class="n_bllbut" style="border-style: none;">送出</button>
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

                <textarea name="content" cols="" rows="" class="n_nutext" placeholder="請輸入檢舉理由" required></textarea>
                <div class="n_bbutton">
                    <button type="submit" class="n_bllbut" style="border-style: none;">送出</button>
                </div>
            </form>
        </div>
        <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>

@stop

@section('javascript')
<script>
    $( document ).ready(function() {
        @if($is_block_mid=='是')
        $('.container').hide();
        $('.gg_tab').hide();
        $('.n_right').hide();
        c4('此用戶已關閉資料');
        $(".n_left").on('click', function() {
            $('#tab04').hide();
            if (document.referrer != "") {
                window.history.back();
            }else{
                location.href = '/dashboard/search';
            }
        });
        @endif
    });
    $('#chatForm').submit(function () {
        let content = $('#msg').val(), msgsnd = $('.msgsnd');
        if($.trim(content) == "" ){
            $('.alert').remove();
            $("<a style='color: red; font-weight: bold;' class='alert'>請勿僅輸入空白！</a>").insertAfter($('.msg'));
            msgsnd.prop('disabled', true);
            return checkForm;
        }
        else {
            $('.alert').remove();
            return checkForm;
        }
    });
    @if(isset($timeSet) && isset($countSet))
        function doCookieSetup(name, value) {
            console.log('count1');
            var expires = new Date();
            //有效時間保存 2 天 2*24*60*60*1000
            expires.setTime(expires.getTime() + 172800000);
            document.cookie = name + "=" + escape(value) + ";expires=" + expires.toGMTString()
        }
        function getCookie(name) {
            console.log('count2');
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
            console.log('count3');
            document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        }
        function getCookieValueByIndex(startIndex) {
            console.log('count4');
            var endIndex = document.cookie.indexOf(";", startIndex);
            if (endIndex == -1) endIndex = document.cookie.length;
            return unescape(document.cookie.substring(startIndex, endIndex));
        }
        function GetDateDiff(startTime, endTime, diffType) {
            console.log('count5');
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
            console.log('count6');
            var div = document.createElement('div');
            div.appendChild(document.createTextNode(s));
            return div.innerHTML;
        }
        function htmldecode(s){
            console.log('count7');
            var div = document.createElement('div');
            div.innerHTML = s;
            return div.innerText || div.textContent;
        }
        /*取得次數*/
        // console.log('count8');
        // var count=getCookie('count');
        // if(count==undefined){
        //     count=0;
        // }
        /*取得現在時間*/
        // var today=new Date();
        // var now = today.getFullYear()+'/'+(today.getMonth()+1)+'/'+today.getDate()+' '+today.getHours()+':'+today.getMinutes()+':'+today.getSeconds();
        // // console.log(now);
        // // var now       = 20191216215141;
        // /*取得紀錄時間*/
        // var countTime = getCookie('countTime');
        // console.log(countTime);
        // if(countTime==undefined){
        //     countTime = now;
        // }
        $(document).ready(function(){
            var bodyMain = document.getElementById('msg');
            // if(GetDateDiff(countTime, now, "minute")>"{{$timeSet}}"){
            //     // console.log('count10');
            //     delete_cookie('count');
            //     delete_cookie('countTime');
            // }
            // if(GetDateDiff(countTime, now, "minute")<="{{$timeSet}}"){
                // console.log('count11');
                if("{{$isVip!=1}}" && "{{$engroup!=1}}"){
                    
                    // console.log('count12');
                    // console.log(count, "{{$countSet}}");
                    //禁止複製
                    bodyMain.oncopy = function(){
                        return false;
                    }
                    //禁止貼上
                    bodyMain.onpaste = function(){
                        return false;
                    }
                }
                // else{
                //     // console.log('count13');
                //     doCookieSetup('countTime',now);
                //     bodyMain.onpaste = function(){
                //         count++;
                //         console.log(count);
                //         doCookieSetup('count',count);
                //     }
                // }
            // }
        });
    @endif
</script>
<script>
    function show_chat() {
        //$(".blbg").show();
        $(".announce_bg").show();
        $("#show_chat").show();
    }

    function show_banned() {
        //$(".blbg").show();
        $(".announce_bg").show();
        $("#show_banned").show();
    }

    function show_reportPic() {
        $(".blbg").show();
        $("#show_reportPic").show();
        // alert($('.swiper-slide-active').data('type'));
        $('input[name="picType"]').val($('.swiper-slide-active').data('type'));
        $('input[name="pic_id"]').val($('.swiper-slide-active').data('pic_id'));
    }

    $(".but_block").on('click', function() {
        $.post('{{ route('postBlockAJAX') }}', {
            uid: '{{ $user->id }}',
            sid: '{{$to->id}}',
            _token: '{{ csrf_token() }}'
        }, function (data) {
            if(data.save=='ok') {
                $("#tab_block").hide();
                $(".blbg").hide();
                c2('封鎖成功');
            }
        });
    });


    $('.unblock').on('click', function() {
        c4('確定要解除封鎖嗎?');
        var uid='{{ $user->id }}';
        var to='{{$to->id}}';
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
    });

    $(".addFav").on('click', function() {
        $.post('{{ route('postfavAJAX') }}', {
            uid: '{{ $user->id }}',
            to: '{{$to->id}}',
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
    });

     @if (Session::has('message'))
     c2('{{Session::get('message')}}');
     @endif

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
            },
        });
     });

</script>
@stop
