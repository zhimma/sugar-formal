@extends('new.layouts.website')
@section('app-content')
    <style>
        .blur_img {
            filter: blur(3px);
            -webkit-filter: blur(3px);
        }
        .swiper-container {
            width: 100%;
            height: 254px;
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
            object-fit: contain;
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
                /*position: unset;*/
            }
        }
        @media (max-width:1366px) {
            .swiper-container {
                width: 100%;
                height: 268px;
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
            
            .metx .tubiao .not_vip li img {height:50px !important;}            
        }
        
        @media (min-width:812px) and (min-height: 375px) {
            .metx .tubiao .not_vip li img {height:50px !important;}            
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
        .hzk {
            margin: 0 auto;
            display: table;
            line-height: 30px;
            color: #999;
            margin-top: 15px;
        }
        .hzk img {
            height: 12px;
            margin: 0 auto;
            display: block;
            cursor: pointer;
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
        .hzk {
            margin: 0 auto;
            display: table;
            line-height: 30px;
            color: #999;
            margin-top: 15px;
        }
        .hzk img {
            height: 12px;
            margin: 0 auto;
            display: block;
            cursor: pointer;
        }

        .re_sc img{
            height: 1.9em;
            margin-right: 3px;
            padding-bottom: 6px !important;
        }

        .zw_dw {
            float: right;
            padding: 0px 15px;
            background: #fd5678;
            height: 24px;
            line-height: 24px;
            color: #ffffff;
            text-align: center;
            border-radius: 100px;
            margin-top: 10px;
            color: #ffffff;
            font-size: 13px;
            cursor: pointer;
        }
        .new_pjckbox{width: 100%; background: rgba(255,255,255,0.4); padding:10px 10px 5px 10px; border-radius: 5px; margin-top: 10px; color: #999999;}
        .new_pjckbox span{width: 100%; padding:0px 0px; display: table; color: #e44e71; line-height:20px;}
        .new_pjckbox span label{ font-weight: normal;}
        .new_pjckbox span input{ margin-right: 3px; margin-top: 3px;display: table;float: left;}


        .zap_photo{width: 100%; overflow: hidden; padding-left: 10px;}
        .zap_photo>li{float: left; width:18%; height:102px;background: #fff9fa;justify-content: center;align-items: center;overflow: hidden;display: flex;
            border: #fe92a8 1px dashed; position: relative; margin: 10px 2% 0 0;cursor: pointer;}
        .zap_photo>li>img{max-width: 100%; max-height: 100%;}
        .zap_photo>li>em{position: absolute; left: 0; top:0; width: 100%; height: 100%; background: rgba(0,0,0,.5); color: #fff; display: flex; align-items: center; justify-content: center;}
        .pjliuyan02 .zap_photo>li{padding: 0;display: flex;width:19%; margin: 10px 1% 0 0; height:140px;background: #f5f5f5; border: none; overflow: hidden;}
        .pjliuyan02 .zap_photo{margin: 0; display: block;}
        /*.pjliuyan02 .zap_photo>li>img{width: 100%; max-height:unset;}*/

        @media (max-width:768px){
            .zap_bb>.text>a>em{padding-left:11px;}
        }
        @media (max-width:450px){
            .zap_bb>.text>a>em{padding-left:7px;}
            .zap_photo>li{width:23%; height: 90px;}
            .zap_photo{padding-left: 0;}
            .pjliuyan02 .zap_photo>li{width:30.33%;margin:12px 1.5% 0 1.5%;height: 100px;}
        }
        .big_img .swiper-pagination-bullet {background:#fff; width: 12px;height: 12px; cursor: pointer;}
        .fileuploader-icon-remove:after {content: none !important;}

        .xin_input_qq{width: calc(100% - 45px);float: right;margin-right:6px; margin-left:0px;outline: none;}


        @media (max-width:450px) {
           .huiyoic{ height:118px;}
        }
        @if(!$user->isVip()&&!$user->isVVIP())
            .tubiao ul li img {height:50px !important;}
        @endif

        @media (min-width:812px) and (min-height: 375px) and (max-width:812px) and (max-height:375px) {
            .bl_tab{
                width: 40%;
                left: 30%;
            }
        }
    </style>
    <style>
        .new_pot1{width:96%;height:auto;margin: 0 auto;color: #666666;display: block; margin-top: 20px; margin-bottom: 20px;}
        .new_pot,.new_pot001, .new_po000 {max-height:500px;}
        .new_pot001, .new_po000 {overflow-y:scroll;}
        .new_pot {overflow-y:hidden;}
        @media (max-width:824px){
            .new_pot1{height:195px;overflow-y: scroll; padding-bottom:15px; }
        }
        @media (max-width:450px){
            .reportPic_aa, .reportPic_new {height:365px !important;;}

        }
        @media (max-width:320px){
            .new_pot1{height:280px;}
        }
        .new_pot1::-webkit-scrollbar {
            /*滚动条整体样式*/
            width :4px;  /*高宽分别对应横竖滚动条的尺寸*/
            height: 1px;
        }

        .new_pot1::-webkit-scrollbar-thumb {
            /*滚动条里面小方块*/
            border-radius: 100px;
            background: #8a9fef;
        }
        .new_pot1::-webkit-scrollbar-track {
            /*滚动条里面轨道*/
            border-radius: 100px;
            background:rgba(255,255,255,0.6);
        }
        @media (max-width:824px) {
            .fpt_pic1{height:170px;padding: 0 5px;}
        }
        @media (max-width:450px) {
            .fpt_pic1{height: 280px;}
        }
        @media (max-width:320px) {
            .fpt_pic1{height: 280px;}
        }
        #onlineStatus2{
            display: none;
            width: 55px;
            height: 22px;
            background: linear-gradient(to TOP,#8dd882,#abd4a5);
            color: #fff;
            line-height: 22px;
            box-shadow: 2px 2px 2px #6aa763;
            border-radius: 100px;
            float: left;
            text-align: center;
            margin-top: 8px;
            margin-left: 5px;
            font-size: 12px;
        }

        #onlineStatusNonVip2{
            display: none;
            width: auto;
            height: 22px;
            background: linear-gradient(to TOP,#ff9225,#ffb86e);
            color: #fff;
            line-height: 22px;
            padding-left: 7px;
            box-shadow: 0px 3px 0px #ff721d;
            border-radius: 100px;
            float: left;
            text-align: center;
            margin-top: 8px;
            margin-left: 5px;
            font-size: 12px;
        }
        .fpt_pic {
            overflow-y: scroll;
            max-height: 480px;            
        }
        .ri_xixn {
            width: 240px;
            margin: 0 auto;
            background: #fff;
            display: table;
            border-radius: 10px;
            /* margin-top: 15px; */
        }
        .eg_o {
            margin-bottom: 0px;
        }
        .ri_xixn_input {
            width: 200px;
            border: none;
            background: transparent;
            height: 35px;
            color: #000;
            padding-left: 10px;
            outline: none;
        }
        .ri_button_a {
            background: #fabbcc;
            border-radius: 3px;
            height: 35px;
            color: #fff;
            float: right;
            width: 40px;
            text-align: center;
            line-height: 35px;
        }
        .ri_button_a:hover {
            background: #ffa9bc;
            color: #fff;
        }
        .metx {
            max-height: 400px;
        }
        .bottub {
            margin-top: -28px;
        }
        @media (max-width: 992px) {
            .bottub {
                margin-top: -26px;
            }
        }

    </style>
    <style>
        .he_tkcn ul a span {text-align:left;font-size:unset;}
        .he_tkcn ul a span.vip_space {display:inline;height:0;float:left;}
        .he_tkcn ul a span.vip_space .tap-vip {top: -31px;left: 16px;}
        .he_tkcn_img {display: unset;margin-top: unset;}
        div.kll {word-break:break-all;}
        /*** iphone12 會吃掉右邊的字  ****/
        button.al_but {width:auto;white-space: nowrap;} 
    </style>
    <style>
        .primessage{
            width: 70px;
        }
    </style>
    <style>
        :root {
            --primary-light: #8abdff;
            --primary: #6d5dfc;
            --primary-dark: #5b0eeb;
            --white: #FFFFFF;
            --greyLight-1: #E4EBF5;
            --greyLight-2: #e3e3e3;
            --greyLight-3: #bec8e4;
            --greyDark: #9baacf;
        }

        .ly_prilist{width:100%; box-shadow: 0 0 20px #eee; border-radius: 10px;padding: 10px 0;margin-top: 15px;}
        .ly_prilist:active {box-shadow: inset 0.2rem 0.2rem 0.5rem var(--greyLight-2), inset -0.2rem -0.2rem 0.5rem var(--white);}
        .ly_prilist_active {box-shadow: inset 0.2rem 0.2rem 0.5rem var(--greyLight-2), inset -0.2rem -0.2rem 0.5rem var(--white);}


        .ly_text{width: 96%;margin: 0 auto;display: table;}
        .ly_text_1{width: 100%;background:linear-gradient(to right,#fff1f1,#fffdfd); color: #fd5a7b;padding: 5px 5px; display: table; height: 30px; line-height: 30px;}
        .ly_lfontleft{width: calc(100% - 90px); float: left; height: 30px;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;;}
        .ly_time{float: right; color: #999999;}
        .liu_text_2{margin-bottom: unset;}
    </style>
    <style>
        .popover{
            padding: unset !important;
        }
    </style>
    @if($user->engroup==2)
    <style>
        li .he_tkcn ul {width:100% !important;}
        #xs,#bxs {white-space:nowrap !important;}
    </style>
    <style>
        .btn_right:hover{color:#ffffff;box-shadow:inset 0px 15px 10px -10px #4c6ded,inset 0px -10px 10px -20px #4c6ded;}
        .btn_left:hover{color:#ffffff;box-shadow:inset 0px 15px 10px -10px #516cd4,inset 0px -10px 10px -20px #516cd4; background:#8a9ff0}    
    
        .btn_left {
            float: right;
            width: 120px;
            height: 40px;
            background: #ffffff;
            border: #8a9ff0 1px solid;
            border-radius: 200px;
            color: #8a9ff0;
            text-align: center;
            line-height: 40px;
            font-size: 16px;
            margin-right: 11px;            
        }
        
        .btn_right {
            float: left;
            width: 120px;
            height: 40px;
            background: #8a9ff0;
            border: #8a9ff0 1px solid;
            border-radius: 200px;
            color: #ffffff;
            text-align: center;
            line-height: 40px;
            font-size: 16px;
            margin-left: 11px;
        } 
        
        @if($user_tiny_setting_to_blurry->value==1)
           .cleared_s {display:none;}
        @elseif($user_tiny_setting_to_blurry->value==-1)
           .blured_s {display:none;}
        @endif

        .blbg_not_blurry {
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0px;
            left: 0;
            background: rgba(0,0,0,0.5);
            z-index: 9;            
        }
        
        #tab_not_blurry .new_poptk {height:auto;}
     
        #tab_not_blurry .new_poptk::-webkit-scrollbar {
            width: 0px;
        }     
     </style>    
    @endif
    <script src="{{asset('/new/js/pick_real_error.js')}}" type="text/javascript"></script>
    <script type="application/javascript">
        function show_Warned() {
            return  c5('您目前被站方警示，無檢舉權限');
        }

        function setTextAreaHeight(rowid) {
            $('#re_content_'+rowid).each(function () {
                this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
            }).on('input', function () {
                var rows =  $('#re_content_'+rowid).val().split("\n").length;

                if((this.scrollHeight)>33){
                    this.style.height = 'auto';
                    this.style.height = 33 + 'px';
                    var textAreaHeight =0;
                    $("#xin_nleft_qq_"+rowid).css('margin-top',textAreaHeight + 'px');
                    $("#re_area_"+rowid).css('margin-top',textAreaHeight + 'px');
                }
                if( rows==1){
                    this.style.height = '33 px';
                }else{
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                    var textAreaHeight = parseInt(this.scrollHeight)-34;
                    $("#xin_nleft_qq_"+rowid).css('margin-top',textAreaHeight + 'px');
                    $("#re_area_"+rowid).css('margin-top',textAreaHeight + 'px');
                }
            })
        }    

        function form_submit(){
            // if( $("input[name='rating']:checked").val() == undefined) {
            //     // c5('請先點擊星等再評價');
            //     $('.alert_tip').text();
            //     $('.alert_tip').text('請先點擊星等再評價');
            //     return false;
            // }else
            if($.trim($(".evaluation_content").val())=='') {
                // c5('請輸入評價內容');
                $('.alert_tip').text();
                $('.alert_tip').text('請輸入評價內容');
                return false;
            }else if($(".evaluation_content").val().length>300) {
                // c5('請輸入評價內容');
                $('.alert_tip').text();
                $('.alert_tip').text('評價至多300個字元');
                return false;
            }else if($("input[name='agree']:checked").val() == undefined) {
                // c5('請勾選同意上述說明');
                $('.alert_tip').text();
                $('.alert_tip').text('請勾選同意上述說明');
                return false;
            } else if ($('.self_illustrate').is(':hidden') && $('#form1').find('.file-type-image').length == 0) {
                $('.alert_tip').text();
                $('.alert_tip').text('請上傳照片');
            }else{
                $('#form1').submit();
            }
            return false;
        }

        function reportPostForm_submit() {
            if($("input[name='agree']:checked").val() == undefined) {
                $('.alert_tip').text();
                $('.alert_tip').text('請勾選同意上述說明');
                return false;
            }else{
                $('#reportPostForm').submit();
            }
        }
       
        function form_evaluation_reply_submit(){
            if($.trim($("#re_content_reply").val())=='') {
                $('.alert_tip').text();
                $('.alert_tip').text('請輸入內容');
            }else{
                $('#form_evaluation_reply').submit();
            }
        }  
        
        function show_banned_close(){
            $(".announce_bg").hide();
            $("#show_banned_ele").hide();
            $('body').css("overflow", "auto");
        } 

        function button() {
            $(".blbg").hide()
            $("#jianju").hide()
            $(".announce_bg").hide();
            $('body').css("overflow", "auto");
        }

        function show_reportPic_close(){
            $(".announce_bg").hide();
            $("#show_reportPic").hide();
            $(".blbg").hide();
            $('body').css("overflow", "auto");
        }            

		function tab_evaluation_close(){
			$(".announce_bg").hide();
			$("#tab_evaluation").hide();
            $('#content').val('');
			$('body').css("overflow", "auto");
		}

		function tab_evaluation_reply_close(){
			$(".announce_bg").hide();
			$("#tab_evaluation_reply").hide();
			$('body').css("overflow", "auto");
		}

		function tab_evaluation_reply_show(id, eid) {
			$(".announce_bg").show();
			//$("#re_content_reply").val('');
			//$("#images_reply").val('');
			$("#tab_evaluation_reply").show();
			$("#tab_evaluation_reply #id_reply").val(id);
			$("#tab_evaluation_reply #eid_reply").val(eid);
			$('body').css("overflow", "hidden");
		}        

        function evaluation_description_close(){
            $(".announce_bg").hide();
            $("#evaluation_description").hide();
            $('body').css("overflow", "auto");
        }
    </script>
    @php
        $isBlurAvatar = \App\Services\UserService::isBlurAvatar($to, $user);
        $isBlurLifePhoto = \App\Services\UserService::isBlurLifePhoto($to, $user);
        $isPersonalTagShow = \App\Services\UserService::isPersonalTagShow($to, $user);
    @endphp
    <div id="app" ontouchstart="" onmouseover="">
    <div class="container matop80">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                @if(isset($to))
                <div class="rightbg">
                    <div class="metx">
                        @if(Request()->get('page_mode')=='edit')
                            <a href="{!! url('dashboard') !!}" class="zh_shed" style="z-index: 6;"></a>
                        @else
                            @php
                                $backUrl= !empty(session()->get('goBackPage')) ? session()->get('goBackPage') : \Illuminate\Support\Facades\URL::previous();
                                if(isset($_SERVER['HTTP_REFERER']) && str_contains($_SERVER['HTTP_REFERER'], 'from_message_board')){
                                    $backUrl=$_SERVER['HTTP_REFERER'];
                                }
                            @endphp
                            <a href="{{ $backUrl }}" {{--href="javascript: history.back()"--}} class="hyneback" style="z-index: 6;"><img src="/new/images/back_icon.png">返回</a>
                        @endif

                        @if(!empty($to->exchange_period) && $to->engroup==2)
                            @php
                                $exchange_period_name = DB::table('exchange_period_name')->where('id',$to->exchange_period)->first();
                            @endphp
                            <span class="rgnback"><img src="/new/images/zx_x.png">{{$exchange_period_name->name}}</span>
                        @endif

                        <div class="swiper-container photo userPicList">
                            <div class="swiper-wrapper">
                                @php
                                    $getAvatarPath=($isBlurAvatar && $to->meta->pic_blur) ? $to->meta->pic_blur : $to->meta->pic;
                                @endphp
                                <div class="swiper-slide @if($isBlurAvatar) blur_img @endif" data-type="avatar" data-sid="{{$to->id}}" data-pic_id=""><img src="@if(file_exists( public_path().$getAvatarPath ) && $getAvatarPath != ""){{$getAvatarPath}} @elseif($to->engroup==2)/new/images/female.png @else/new/images/male.png @endif"></div>
                                {{--<div class="swiper-slide @if($isBlurAvatar) blur_img @endif" data-type="avatar" data-sid="{{$to->id}}" data-pic_id=""><img src="@if(file_exists( public_path().$to->meta->pic ) && $to->meta->pic != ""){{$to->meta->pic}} @elseif($to->engroup==2)/new/images/female.png @else/new/images/male.png @endif"></div>--}}
                                @php
                                    $ImgCount=1;
                                @endphp
                                @foreach($member_pic as $row)
                                    @if(!str_contains($row->pic, 'IDPhoto'))
                                        @php
                                            $ImgCount+=1;
                                            $getLifePhotoPath=($isBlurLifePhoto && $row->pic_blur) ? $row->pic_blur : $row->pic;
                                        @endphp
                                        <div class="swiper-slide @if($isBlurLifePhoto) blur_img @endif" data-type="pic" data-sid="{{$to->id}}" data-pic_id="{{$row->id}}"><img src="{{$getLifePhotoPath}}"></div>
                                        {{--<div class="swiper-slide @if($isBlurLifePhoto) blur_img @endif" data-type="pic" data-sid="{{$to->id}}" data-pic_id="{{$row->id}}"><img src="{{$row->pic}}"></div>--}}
                                    @endif
                                @endforeach
                                @php
                                    //取得由後台刪除的生活照
                                    $illegalRemoveCount=\App\Models\MemberPic::getIllegalLifeImagesCount($user->id);
                                @endphp
                                @for ($i = 0; $i <$illegalRemoveCount ; $i++)
                                    <div class="swiper-slide @if($isBlurLifePhoto) blur_img @endif"><img src="/img/illegal.jpg"></div>
                                @endfor
                            </div>
                            <!-- Add Arrows -->
                            <div class="swiper-button-next" style="width:36px;height:36px;background-size: 100%;opacity: .6;background-image:url(/new/images/jt_04_new.png)"></div>
                            <div class="swiper-button-prev" style="width:36px;height:36px;background-size: 100%;opacity: .6;background-image:url(/new/images/jt_03_new.png)"></div>
                        </div>
                        <!--新改-->
                        @php
                            $isBlocked = \App\Models\Blocked::isBlocked($user->id, $to->id);
                            $data = \App\Services\UserService::checkRecommendedUser($to);
                            $introCount = 0;
                            $introMinDiv = ($user->isVip()||$user->isVVIP())? '111px' : '85px';
                        @endphp
                        <div class="tubiao" data-step="1" data-position="top" data-highlightClass="yindao2" data-tooltipClass="yindao1" data-intro="<ul>
                                @if(isset($data['description']) && $to->engroup == 2)
                                    <li><div style='min-width:{{$introMinDiv}};text-align: center;'><img @if($user->isVip()||$user->isVVIP())width='85px'@endif src='@if($user->isVip()||$user->isVVIP())/new/images/a1.png @else/new/images/b_1.png @endif'></div> <span>註冊未滿30天的新進會員</span></li>
                                @endif
                                @if($to->isVip() && $to->engroup == 1)
                                    <li><div style='min-width: {{$introMinDiv}};text-align: center;'><img @if($user->isVip()||$user->isVVIP())width='65px'@endif src='@if($user->isVip()||$user->isVVIP())/new/images/a4.png @else/new/images/b_4.png @endif'></div> <span>本站付費會員</span></li>
                                @endif
                                @if(isset($data['description']) && $to->engroup == 1)
                                    <li><div style='min-width: {{$introMinDiv}};text-align: center;'><img @if($user->isVip()||$user->isVVIP())width='85px'@endif src='@if($user->isVip()||$user->isVVIP())/new/images/a2.png @else/new/images/b_2.png @endif'></div> <span>長期付費的VIP，或者常用車馬費邀請的男會員</span></li>
                                @endif
                                @if($to->meta->isWarned() == 1 || $to->aw_relation)
                                    <li><div style='min-width: {{$introMinDiv}};text-align: center;'><img @if($user->isVip()||$user->isVVIP())width='85px'@endif src='@if($user->isVip()||$user->isVVIP())/new/images/a5.png @else/new/images/b_5.png @endif'></div> <span>被多人檢舉或被網站評為可疑的會員</span></li>
                                @endif
                                @if($to->isPhoneAuth())
                                    <li><div style='min-width: {{$introMinDiv}};text-align: center;'><img @if($user->isVip()||$user->isVVIP())width='85px'@endif src='@if($user->isVip()||$user->isVVIP())/new/images/a6.png @else/new/images/b_6.png @endif'></div> <span>通過手機認證的會員</span></li>
                                @endif
                                </ul>">
                            <ul @if(!$user->isVip() && !$user->isVVIP())style="margin-top: -5px;"@endif class="@if(!$user->isVip() && !$user->isVVIP()) not_vip  @endif">
                                @php
                                    $blue_tick = 0;
                                    if($to->isAdvanceAuth()){$blue_tick = $blue_tick + 1;}
                                    if(($rap_service->riseByUserEntry($to)->isPassedByAuthTypeId(1) && $isPersonalTagShow) || $to->video_verify_auth_status == 1){$blue_tick = $blue_tick + 1;}
                                @endphp
                                @if($blue_tick == 2)
                                    <img src="/new/images/zz_zss.png" style="border-radius: 100px; box-shadow:1px 2px 10px rgba(77,152,252,1); height:20px; margin-top:6px;">
                                    <img src="/new/images/zz_zss.png" style="border-radius: 100px; box-shadow:1px 2px 10px rgba(77,152,252,1); height:20px; margin-left: -2px; margin-top: 6px">
                                @elseif($blue_tick == 1)
                                    <img src="/new/images/zz_zss.png" style="border-radius: 100px; box-shadow:1px 2px 10px rgba(77,152,252,1); height:20px; margin-top:6px;">
                                @endif
                                @if($rap_service->isNeedShowTagOnPic())
                                    {!!$rap_service->getTagShowOnPicLayoutByLoginedUserIsVip($user->isVipOrIsVvip()) !!}
                                @elseif($to->meta->isWarned() == 1 || $to->aw_relation)
                                    <li>
                                        @if($user->isVip() || $user->isVVIP())
                                            <div class="tagText" data-toggle="popover" data-content="此會員為警示會員，與此會員交流務必提高警覺！">
                                                <img src="/new/images/a5.png">
                                            </div>
                                        @else
                                            <img src="/new/images/b_5.png" style="height: 50px;">
                                        @endif
                                    </li>
                                    @php
                                        $user->isReadIntro = 1;
                                        $introCount++;
                                    @endphp
                                @elseif(isset($data['description']) && $to->engroup == 2)
                                    <li>
                                        @if($user->isVip() || $user->isVVIP())
                                            <div class="tagText" data-toggle="popover" data-content="新進甜心是指註冊未滿30天的新進會員，建議男會員可以多多接觸，不過要注意是否為八大行業人員。" style="width: 100%">
                                                <img src="/new/images/a1.png">
                                            </div>
                                        @else
                                            <img src="/new/images/b_1.png" style="height: 50px;">
                                        @endif
                                        {{--                                        <span>{{$new_sweet}}</span>--}}
                                    </li>
                                    @php
                                        $user->isReadIntro = 1;
                                        $introCount++;
                                    @endphp
                                @elseif(($to->isVip() || $to->isVVIP()) && $to->engroup == 1)
                                    <li>
                                        @if($user->isVip() || $user->isVVIP())
                                            <div class="tagText" data-toggle="popover" data-content="本站的付費會員。" style="width: 100%">
                                                <img src="/new/images/a4.png">
                                            </div>
                                        @else
                                            <img src="/new/images/b_4.png" style="height: 50px;">
                                        @endif
                                        {{--                                        <span>{{$label_vip}}</span>--}}
                                    </li>
                                    @php
                                        $user->isReadIntro = 1;
                                        $introCount++;
                                    @endphp 
                                @elseif($to->isAdvanceAuth() || $to->isPhoneAuth())
                                        @php
                                            $user->isReadIntro = 1;
                                            $introCount++;
                                        @endphp
                                @endif                              
                            </ul>
                        </div>
                        <!--引导弹出层-->
                        <script type="application/javascript" src="/new/intro/intro.js"></script>
                        <link href="/new/intro/introjs.css" rel="stylesheet">
                        <link rel="stylesheet" href="/new/intro/cover.css" rel="stylesheet">
                        <script type="application/javascript">
                            $(function(){
                                @if($introCount == 1)
                                    $('.tubiao').attr('data-tooltipClass', 'yindao1 yd_small')
                                @endif
{{--                                @if($user->intro_login_times >= 2 && $isReadIntro == 0 && $introCount>0)--}}
{{--                                    // $('.metx').css('position','unset');--}}
{{--                                    introJs().setOption('showButtons',true).start();--}}
{{--                                    @php--}}
{{--                                        $user->save();--}}
{{--                                    @endphp--}}
{{--                                @else--}}
{{--                                    // $('.tubiao').attr('style', 'z-index: -1')--}}
{{--                                @endif--}}
                            })
                        </script>

                        <div class="eg_o">
                            <!-- <div class="eg_oleft">
                                <div class="eg_jdt"><img src="images/t⁮o02.png">
                                    <font class="ef_pr">PR:20</font>
                                </div>
                            </div> -->
                            @if($to->engroup == 1)
                                <div class="eg_oright">
                                    <div class="dfzs">
                                        <div class="slzs">大方指數</div>
                                    <div class="vvipjdt" style="float: right;">
{{--                                        @if($pr != false && $pr >= 1)--}}
{{--                                            @php--}}
{{--                                                if($pr==1){$pr = 0;}--}}
{{--                                            @endphp--}}
                                            <div class="progress progress-striped vvipjdt_pre" title="大方指數" @if($pr=='無') onClick="jidutiao()" @endif>
                                                <div class="progress-bar progress_info" role="progressbar" aria-valuenow="{{$pr}}" aria-valuemin="0"
                                                     aria-valuemax="100" style="width:{{$pr}}%;">
{{--                                                    <span class="prfont">PR: {{$pr}}</span>--}}
                                                </div>
                                            </div>
{{--                                        @elseif($pr == false)--}}
{{--                                            <div class="progress progress-striped vvipjdt_pre" title="大方指數" onClick="jidutiao()" style="cursor: pointer;">--}}
{{--                                                <div class="progress-bar progress_info" role="progressbar" aria-valuenow="0" aria-valuemin="0"--}}
{{--                                                     aria-valuemax="100" style="width:0%;">--}}
{{--                                                    <span class="prfont">PR: 無</span>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        @endif--}}
                                    </div>
                                    </div>


                                    {{--                                @for ($i = 1; $i <= 5; $i++)--}}
                                    {{--                                    @if(intval($rating_avg)>=$i)--}}
                                    {{--                                        <img src="/new/images/sxx_1.png">--}}
                                    {{--                                    @elseif(strstr($rating_avg,'.') && ctype_digit($rating_avg)==false)--}}
                                    {{--                                        <img src="/new/images/sxx_2.png">--}}
                                    {{--                                        @break--}}
                                    {{--                                    @endif--}}
                                    {{--                                @endfor--}}
                                    {{--                                @for ($i = 1; $i <= 5-ceil($rating_avg); $i++)--}}
                                    {{--                                    <img src="/new/images/sxx_4.png">--}}
                                    {{--                                @endfor--}}
                                    {{--                                <img src="/new/images/st_o.png"><img src="/new/images/sxx_1.png">--}}
                                    {{--                                <img src="/new/images/sxx_2.png"><img--}}
                                    {{--                                        src="/new/images/sxx_4.png"><img src="/new/images/sxx_4.png">--}}
                                </div>
                            @endif
                        </div>
                        @if(auth()->user()->id != $to->id )
                        <div class="ri_xixn" @if(auth()->user()->engroup == 1) style="margin-top:15px;" @endif>
                            <input placeholder="您尚未留下備註" class="ri_xixn_input" id="massage_user_note_{{$to->id}}" value="{{$note?$note->note:''}}"><a href="" class="ri_button_a" onclick="massage_user_note('{{$to->id}}');">確定</a>
                        </div>
                        @endif
                    </div>
                    <div class="bottub">

                        <ul>
{{--                            @if(!$isBlocked)--}}
                                <li>
                                    @if($to->id==$user->id)
                                        <a onclick="show_chat()"><img src="/new/images/icon_06.png" class="tubiao_i"><span>發信</span></a>
                                    @else
                                        @if($isBlocked)
                                            <a onclick="messenge_show_block()"><img src="/new/images/icon_06.png" class="tubiao_i"><span>發信</span></a>
                                        @else
                                            <a href="/dashboard/chat2/chatShow/{{ $to->id }}?from_viewuser_page=1"><img src="/new/images/icon_06.png" class="tubiao_i"><span>發信</span></a>
                                        @endif
                                    @endif
                                </li>
{{--                            @endif--}}
                            @if($user->isVip() || $user->isVVIP())
                                <li>
                                    @php
                                        $isFav = \App\Models\MemberFav::where('member_id', $user->id)->where('member_fav_id',$to->id)->count();
                                    @endphp
                                    @if($isFav)
                                        <a class="favIcon removeFav"><img src="/new/images/icon_08_.png" class="tubiao_i"><span>移除收藏</span></a>
                                    @else
                                        <a class="favIcon addFav"><img src="/new/images/icon_08.png" class="tubiao_i"><span>收藏</span></a>
                                    @endif
                                </li>
                            @else
                                <li>
                                    <img src="/new/images/icon_08.png" class="tubiao_i"><span>收藏</span>
                                    <span><img src="/new/images/icon_36.png" class="tap-vip"></span>
                                </li>
                            @endif
                            <li class="evaluation" style="position: relative;">
                                <a><img src="/new/images/icon_14.png" class="tubiao_i"><span>評價</span></a>
                                <div class="he_tkcn showslide_evaluation" style="z-index:1">
                                    <ul>
                                        <a class="myself_evaluation">
                                            <img src="/new/images/icon_p1.png" class="he_tkcn_img">本人評價
                                        </a>
                                        <a class="anonymous_evaluation">
                                            <img src="/new/images/icon_p2.png" class="he_tkcn_img">匿名評價
                                        </a>
                                    </ul>
                                </div>
                            </li>
							
                            <li style="position: relative;">
                                <div class="userlogo"><img src="/new/images/icon_15.png" class="tubiao_i"><span>更多</span></div>
                                <div class="he_tkcn showslide" style="z-index:1">
                                    <ul>
                                        @if($user->isVip() || $user->isVVIP())
                                            @if($isBlocked)
                                                <a class="unblock"><img src="/new/images/icon_12_h.png" class="tubiao_i he_tkcn_img"><span>解除封鎖</span></a>
                                            @else
                                                @if($user->id == $to->id)
                                                <a onclick="c5('不可封鎖自己');"><img src="/new/images/icon_12.png" class="tubiao_i he_tkcn_img"><span>封鎖</span></a>
                                                @else
                                                <a onclick="show_block()"><img src="/new/images/icon_12.png" class="tubiao_i he_tkcn_img"><span>封鎖</span></a>
                                                @endif
                                            @endif
                                        @else
                                                <a href="javascript:void(0);">
                                                <img src="/new/images/icon_12.png" class="tubiao_i he_tkcn_img"><span>封鎖</span>
                                                <span class="vip_space"><img src="/new/images/icon_36.png" class="tap-vip"></span>
                                                </a>
                                        @endif
                                        <a href="javascript:void(0);" class="@if($user->isVip() || $user->isVVIP()) skip_search @endif">
                                            <img src="/new/images/icon_17.png" class="he_tkcn_img">
                                            <span>{{$user->search_ignore()->where('ignore_id',$to->id)->count()?'解除略過':'略過'}}</span>
                                            @if(!$user->isVip() && !$user->isVVIP())
                                            <span class="vip_space"><img src="/new/images/icon_36.png" class="tap-vip"></span>
                                            @endif
                                        </a>
                                        @if($isAdminWarned)
                                            <a onclick="show_Warned()"><img src="/new/images/icon_10.png" class="tubiao_i he_tkcn_img"><span>檢舉會員</span></a>
                                        @else
                                            <a onclick="show_banned()"><img src="/new/images/icon_10.png" class="tubiao_i he_tkcn_img"><span>檢舉會員</span></a>
                                        @endif
                                        <a onclick="show_reportPic()"><img src="/new/images/icon_008.png" class="he_tkcn_img">檢舉照片</a>
                                        @if($user->engroup==2)
                                        <a  onclick="{{$user_not_show_not_blurry_popup->value==1?'bxs();':'c_not_blurry()';}}" id="bxs"><img src="/new/images/icon_18.png" class="he_tkcn_img">開放清晰照</a>
                                        <a onclick="{{$user_not_show_to_blurry_popup->value==1?'xs();':'c_not_blurry()';}}" id="xs" style="display: none;"><img src="/new/images/icon_18_1.png" class="he_tkcn_img">取消清晰照</a>
                                     
                                        @endif
                                    </ul>
                                </div>						
							</li>                            
                        </ul>
                    </div>
                    @if($user->engroup==2)
                    <script>
                    $(function() {                
                    @if($user_tiny_setting_to_blurry->value==1)
                        $('#xs').hide();
                        $('#bxs').show();
                        $('.blured_s').show();
                        $('.cleared_s').hide();
                        $('#tab_not_blurry .context').html('此功能會開放您的照片為清晰版給您當前指定的會員，包含大頭照、生活照，您可以隨時取消。');
                        $('#tab_not_blurry .bltitle font').html('開放清晰照說明');
                    @elseif($user_tiny_setting_to_blurry->value==-1)
                        $('#bxs').hide();
                        $('#xs').show();
                        $('.blured_s').hide();
                        $('.cleared_s').show();
                        $('#tab_not_blurry .context').html('此功能會將您的照片轉為模糊版給您當前指定的會員，包含大頭照、生活照，您可以隨時重新開放清晰照。');
                        $('#tab_not_blurry .bltitle font').html('取消清晰照說明');
                    @elseif(!$user_tiny_setting_to_blurry->id)
                        $('#xs').hide();
                        $('#bxs').show();
                        $('.blured_s').show();
                        $('.cleared_s').hide();
                        $('#tab_not_blurry .context').html('此功能會開放您的照片為清晰版給您當前指定的會員，包含大頭照、生活照，您可以隨時取消。');
                        $('#tab_not_blurry .bltitle font').html('開放清晰照說明');
                    @endif
                    });
                        function c_not_blurry() {
                             $("#blbg_not_blurry").show();
                             $("#tab_not_blurry").show();
                             $('body').css("overflow", "hidden");
                        }
                        
                        function gmBtn1_not_blurry(){
                            $("#blbg_not_blurry").hide();
                            $("#tab_not_blurry").hide();
                            $('body').css("overflow", "");        
                                
                        } 

                        function not_show_blurry_switch_popup(catalog)
                        {
                            switch(catalog) {
                                case 'not_blurry_not_show_popup':
                                    $('#bxs').attr('onclick','bxs();');
                                break;
                                case 'to_blurry_not_show_popup':
                                    $('#xs').attr('onclick','xs();');
                                break;
                            }
                            
                            
                             $.ajax({
                                type: 'GET',
                                url: '{{ route('setTinySetting') }}?{{csrf_token()}}={{now()->timestamp}}',
                                data: { catalog: catalog, value: 1},
                                dataType:'json',
                                success: function(data){
                                    if(data.msg!=undefined) {
                                        if(data.msg.indexOf('成功')>=0) {
                                            switch(catalog) {
                                                case 'not_blurry_not_show_popup':                                        
                                                    $('#bxs').attr('onclick','bxs();');
                                                break;
                                                case 'to_blurry_not_show_popup':
                                                    $('#xs').attr('onclick','xs();');
                                                break;
                                            }
                                            
                                            return;
                                        }
                                    }
                                    
                                },
                            });              
                        }                    
                    
                        function bxs(only_toggle=false) {
                            $("#xs").show();
                            $("#bxs").hide();
                            if(only_toggle!=false) return;
                            let bFormData = new FormData();
                            bFormData.append('_token','{{csrf_token()}}');
                            bFormData.append('act','-1');
                            bFormData.append('target','{{$to->id}}');
                            $.ajax({
                                type: 'POST',
                                url: "{{route('setBlurryToUser')}}",
                                data:{
                                    '_token':'{{csrf_token()}}'
                                    ,'act':'-1'
                                    ,'target':'{{$to->id}}'
                                },
                                success: function(res){
                                    if(res!=1) {
                                       c5('開放清晰照失敗!請重新操作。');
                                       xs(true);
                                    }
                                    else {
                                        c5('成功開放清晰照');
                                        $('.cleared_s').show();
                                        $('.blured_s').hide();
                                        $('#tab_not_blurry .context').html('此功能會將您的照片轉為模糊版給您當前指定的會員，包含大頭照、生活照，您可以隨時重新開放清晰照。');
                                        $('#tab_not_blurry .bltitle font').html('取消清晰照說明');  
                                    }
                                },
                                error:function(jqXHR,statusStr,errorStr) {
                                    c5('發生錯誤，解除清晰照失敗!請重新操作。'+statusStr+' '+errorStr);
                                    xs(true);
                                }
                            });                    
                        }
                                
                        function xs(only_toggle=false) {
                            $("#bxs").show();
                            $("#xs").hide();
                            if(only_toggle!=false) return;
                            let bFormData = new FormData();
                            bFormData.append('_token','{{csrf_token()}}');
                            bFormData.append('act','1');
                            bFormData.append('target','{{$to->id}}');
                            $.ajax({
                                type: 'POST',
                                url: "{{route('setBlurryToUser')}}",
                                data:{
                                   '_token': '{{csrf_token()}}'
                                   ,'act':'1'
                                   ,'target':'{{$to->id}}'
                                },
                                success: function(res){
                                    if(res!=1) {
                                       c5('取消清晰照失敗!請重新操作。');
                                       bxs(true);
                                    }
                                    else {
                                        c5('成功取消清晰照');
                                    $('.blured_s').show();
                                    $('.cleared_s').hide();
                                    $('#tab_not_blurry .context').html('此功能會開放您的照片為清晰版給您當前指定的會員，包含大頭照、生活照，您可以隨時取消。');
                                    $('#tab_not_blurry .bltitle font').html('開放清晰照說明');    
                                    }
                                },
                                error:function(jqXHR,statusStr,errorStr) {
                                    c5('發生錯誤，取消清晰照失敗!請重新操作。'+statusStr+' '+errorStr);
                                    bxs(true);
                                }
                            });                         
                        }
                    </script>                 
                    @endif
					<!--新改-->

					<script>
						$('.userlogo').click(function() {
							event.stopPropagation()
                            var on2 = $('.bottub').find('.on2');
                            if(on2.length) {
                                on2.removeClass('on2');
                                $('.bottub').find('.showslide_evaluation').fadeOut();
                            }
							if($(this).hasClass('on1')) {
								$(this).removeClass('on1')
								$('.showslide').fadeOut()
							} else {
								$(this).addClass('on1')
								$('.fadeinboxs').fadeIn()
								$('.showslide').fadeIn()
							}
						})
						$('body').click(function() {
							$('.userlogo').removeClass('on1')
							$('.showslide').fadeOut()
                            $('.evaluation').removeClass('on2')
                            $('.showslide_evaluation').fadeOut()
                            $('.showslide_evaluation2').removeClass('on3')
                            $('.showslide_evaluation2').fadeOut()
                        })
					</script>	

                    <!-- Swiper JS -->
                    <script type="application/javascript" src="/new/js/swiper.min.js"></script>
                    <!-- Initialize Swiper -->
                    <script type="application/javascript">

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
                @if($to->engroup==1)
                    <div class="metx_tab" style="z-index:0">
                        <div class="hdlist1_left">
                            <div class="hdlist1_left_tab xa_psirp">
                                    <div class="hs_tnes">
                                        <img src="/new/images/shouru.png">
                                        <div class="she_fontetex">車馬費預算<span>transport fare</span></div>
                                    </div>
                                    <a class="s_bushi" onclick="jianju_transport_fare()">檢舉</a>
                                    <div class="sh_button_w">
                                        @if(!empty($to->meta->transport_fare_min) && !empty($to->meta->transport_fare_max) && $to->meta->transport_fare_min != -1 && $to->meta->transport_fare_max != -1)
                                            <div class="sh_button_n"> {{round($to->meta->transport_fare_min, -2)}}~{{round($to->meta->transport_fare_max, -2)}}</div>
                                        @else
                                            <div class="sh_button_n"> 最低 / 未填</div>
                                        @endif
                                    </div>
                                    @if($bool_value['transport_fare_warn']??false) <img src="/new/images/cm_icon01.png" class="xz_iconp"> @endif
                            </div>
                        </div>
                        <div class="hdlist2_right">
                                <div class="hdlist2_right_tab xa_psirp">
                                    <div class="hs_tnes">
                                        <img src="/new/images/zichan.png">
                                        <div class="she_fontetex">每月預算<span>month budget</span></div>
                                    </div>
                                    <a class="s_bushizz" onclick="jianju_month_budget()">檢舉</a>
                                    <div class="zc_button_w">
                                        @if(!empty($to->meta->budget_per_month_min) && !empty($to->meta->budget_per_month_max) && $to->meta->budget_per_month_min != -1 && $to->meta->budget_per_month_max != -1)
                                            <div class="zc_button_n"> {{round($to->meta->budget_per_month_min, -3)/10000}}萬~{{round($to->meta->budget_per_month_max, -3)/10000}}萬</div>
                                        @else
                                            <div class="zc_button_n"> 最低 / 未填</div>
                                        @endif
                                    </div>
                                    @if($bool_value['budget_per_month_warn']??false) <img src="/new/images/cm_icon02.png" class="xz_iconp"> @endif
                                </div>
                        </div>
                    </div>       
                @endif

                <!--基本资料-->
                <div class="mintop">
                    <div class="">
                        <div class="ziliao">
                            <div class="ztitle">
								<span>基本資料</span>Basic information								
							</div>
                            <div class="xiliao_input">
                                <div class="xl_input">
                                    <dt>
                                        <span>暱稱</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">
                                                <font class="left">{{$to->name}}</font>
                                                @if($user->isVip() || $user->isVVIP())
                                                    @if($to->isOnline() && $to->is_hide_online==0)
                                                        <font id="onlineStatus2" style="display: block;">上線中</font>
                                                    @endif
                                                @else
                                                    <font id="onlineStatusNonVip2" style="display: block;">上線狀態？</font>
                                                @endif
                                            </div>
                                        </span>
                                    </dt>
                                    <dt>
                                        <span>一句話形容自己</span>
                                        <span>
                                            <div class="select_xx03">{!! nl2br($to->title)!!}</div>
                                        </span>
                                    </dt>

                                    {{--
                                    @if(!empty($to->exchange_period) && $to->engroup==2 && $user->isVipOrIsVvip())
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
                                    --}}

                                    <dt>
                                        <span>地區</span>
                                        <?php
                                        if (!isset($to)) {
                                            $umeta = null;
                                        } else {
                                            $umeta = $to->meta;
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
                                                        @if($to->meta->isHideArea == '0')
                                                        <font class="select_xx senhs right hy_new">{{$umeta->area[$key]}}</font>
                                                        @endif
                                                    </span>
                                                @endforeach
                                            @endif
                                        @else
                                            <span>
                                                <font class="select_xx senhs left hy_new">{{$to->meta->city}}</font>
                                                <font class="select_xx senhs right hy_new">{{$to->meta->area}}</font>
                                            </span>
                                        @endif
                                    </dt>
                                    
                                    @if($to->engroup==2 && isset($to->meta->is_dating_other_county))
                                        <dt>
                                            <span>是否接受約外縣市?</span>
                                            <span>
                                                <div class="select_xx01 senhs hy_new">
                                                @if($to->meta->is_dating_other_county == 1)
                                                是
                                                @elseif($to->meta->is_dating_other_county == 0)
                                                否
                                                @endif
                                                </div>
                                            </span>
                                        </dt>
                                    @endif

                                    {{--@if($to->engroup == 2 && !empty($to->meta->budget))
                                    <dt>
                                        <span>預算</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta->budget}}</div>
                                        </span>
                                    </dt>
                                    @endif--}}

                                    @if(!empty($to->meta->age()))
                                    <dt>
                                        <span>年齡</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta->age()}}</div>
                                        </span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta->height))
                                    <dt>
                                        <span>身高（cm）</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta->height}}</div>
                                        </span>
                                    </dt>
                                    @endif
                                    
                                    @if((!empty($to->meta->weight) && $to->meta->isHideWeight == '0' ))
                                    <dt>
                                        <span>體重（kg）</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta->weight-4}} ~ {{$to->meta->weight}}</div>
                                        </span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta->body) && $to->meta->body != null && $to->meta->body != 'null')
                                    <dt>
                                        <span>體型</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta->body}}</div>
                                        </span>
                                    </dt>
                                    @endif
                                    
                                    @if($to->engroup == 2)
                                        @if((!empty($to->meta->cup) && $to->meta->isHideCup == '0' && ($to->meta->cup == 'A' || $to->meta->cup == 'B' ||$to->meta->cup == 'C' || $to->meta->cup == 'D' || $to->meta->cup == 'E' || $to->meta->cup == 'F')))
                                            <dt>
                                                <span>CUP</span>
                                                <span>
                                                    <div class="select_xx01 senhs hy_new">{{$to->meta->cup}}</div>
                                                </span>
                                            </dt>
                                        @endif
                                        
                                        @if(!empty($to->meta->family_situation) && $to->meta->family_situation != 'null')
                                            <dt>
                                                <span>家庭狀況</span>
                                                <span>
                                                    <div class="select_xx01 senhs hy_new">{{$to->meta->family_situation}}</div>
                                                </span>
                                            </dt>
                                        @endif
                                        
                                        <dt>
                                            <span>關於我</span>
                                            @if(($looking_for_relationships->first()->xref_id ?? false) || ($expect->first()->xref_id ?? false))
                                                <span>
                                                    <div class="ka_n">
                                                        @if(($looking_for_relationships->first()->xref_id ?? false))
                                                            <div class="ka_gx">尋找關係</div>
                                                            <div class="ka_tubicon">
                                                                @if($looking_for_relationships->first()->xref_id ?? false)
                                                                    @foreach($looking_for_relationships as $option)
                                                                        @if($option->xref_id ?? false)
                                                                            <div class="ka_tico_1 show_option_content" data-toggle="popover" data-content="{{ $option->option_content }}"><i>{{$option->option_name}}</i></div>
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    <div class="ka_tico_1"><i>尚未填寫</i></div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                        @if(($expect->first()->xref_id ?? false))
                                                            <div class="ka_gx ka_fwi">對糖爹的期待</div>
                                                            <div class="ka_tubicon">
                                                                @if($expect->first()->xref_id ?? false)
                                                                    @foreach($expect as $option)
                                                                        @if($option->xref_id ?? false)
                                                                            <div class="ka_tico_1"><i>{{$option->option_name}}</i></div>
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    <div class="ka_tico_1"><i>尚未填寫</i></div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                        @if(isset($to->meta->about) && $to->meta->about != '')
                                                            <div class="ka_gx ka_fwi">或是其他你想說的</div>
                                                            <div class="ka_tubicon_text">
                                                                {!! nl2br($to->meta->about ?? '') !!}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </span>
                                            @else
                                                <span>
                                                    <div class="ka_tubicon_text">
                                                        {!! nl2br($to->meta->about ?? '') !!}
                                                    </div>
                                                </span>
                                            @endif
                                        </dt>
                                        <dt>
                                            <span>期待的約會模式</span>
                                            @if(($favorite_food->first()->xref_id ?? false) || ($preferred_date_location->first()->xref_id ?? false) || ($expected_type->first()->xref_id ?? false) || ($frequency_of_getting_along->first()->xref_id ?? false))
                                                <span>
                                                    <div class="ka_n">
                                                        @if(($favorite_food->first()->xref_id ?? false))
                                                            <div class="ka_gx">喜歡的食物</div>
                                                            <div class="ka_tubicon">
                                                                @if($favorite_food->first()->xref_id ?? false)
                                                                    @foreach($favorite_food as $option)
                                                                        @if($option->xref_id ?? false)
                                                                            <div class="ka_tico_1"><i>{{$option->option_name}}</i></div>
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    <div class="ka_tico_1"><i>尚未填寫</i></div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                        @if(($preferred_date_location->first()->xref_id ?? false))
                                                            <div class="ka_gx ka_fwi">偏好約會地點</div>
                                                            <div class="ka_tubicon">
                                                                @if($preferred_date_location->first()->xref_id ?? false)
                                                                    @foreach($preferred_date_location as $option)
                                                                        @if($option->xref_id ?? false)
                                                                            <div class="ka_tico_1"><i>{{$option->option_name}}</i></div>
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    <div class="ka_tico_1"><i>尚未填寫</i></div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                        @if($expected_type->first()->xref_id ?? false)
                                                            <div class="ka_gx ka_fwi">期望模式</div>
                                                            <div class="ka_tubicon">
                                                                @if($expected_type->first()->xref_id ?? false)
                                                                    @foreach($expected_type as $option)
                                                                        @if($option->xref_id ?? false)
                                                                            <div class="ka_tico_1 show_option_content" data-toggle="popover" data-content="{{ $option->option_content }}"><i>{{$option->option_name}}</i></div>
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    <div class="ka_tico_1"><i>尚未填寫</i></div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                        @if($frequency_of_getting_along->first()->xref_id ?? false)
                                                            <div class="ka_gx ka_fwi">相處的頻率與模式</div>
                                                            <div class="ka_tubicon">
                                                                @if($frequency_of_getting_along->first()->xref_id ?? false)
                                                                    @foreach($frequency_of_getting_along as $option)
                                                                        @if($option->xref_id ?? false)
                                                                            <div class="ka_tico_1"><i>{{$option->option_name}}</i></div>
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    <div class="ka_tico_1"><i>尚未填寫</i></div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                        @if(isset($to->meta->style) && $to->meta->style != '')
                                                            <div class="ka_gx ka_fwi">或是其他你想說的</div>
                                                            <div class="ka_tubicon_text">
                                                                {!! nl2br($to->meta->style ?? '') !!}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </span>
                                            @else
                                                <span>
                                                    <div class="ka_tubicon_text">
                                                        {!! nl2br($to->meta->style ?? '') !!}
                                                    </div>
                                                </span>
                                            @endif
                                        </dt>

                                        @if(!empty($to->meta->available_time) && $to->meta->available_time != 'null')
                                            <dt>
                                                <span>有空時段</span>
                                                <span>
                                                    <div class="select_xx01 senhs hy_new">{{$to->meta->available_time}}</div>
                                                </span>
                                            </dt>
                                        @endif

                                        @if($to->tattoo->count())
                                            <dt>
                                                <span>刺青</span>                                    
                                                <span>
                                                    <font class="select_xx senhs left hy_new">{{$to->tattoo->first()->part}}</font>
                                                    <font class="select_xx senhs right hy_new">{{$to->tattoo->first()->range}}</font>
                                                </span>                                    
                                            </dt>
                                        @endif
                                    @endif

                                    @if(!empty($to->meta->about) && $to->engroup==1)
                                    <dt>
                                        <span>關於我</span>
                                        <span>
                                            <div class="select_xx03" >{!! nl2br($to->meta->about) !!}</div>
                                        </span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta->style) && $to->engroup==1)
                                    <dt>
                                        <span>期待的約會模式</span>
                                        <span>
                                            <div class="select_xx03" >{!! nl2br($to->meta->style) !!}</div>
                                        </span>
                                    </dt>
                                    @endif

                                    @if(($relationship_status->first()->xref_id ?? false) && $to->engroup==2)
                                    <dt>
                                        <span>感情狀況</span>
                                        <div class="ka_tubicon ka_n">
                                            <br>
                                            @foreach($relationship_status as $option)
                                                @if($option->xref_id ?? false)
                                                    <div class="ka_tico_1"><i>{{$option->option_name}}</i></div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </dt>
                                    @endif

                                    @if(($personality_traits->first()->xref_id ?? false) && $to->engroup==2)
                                        <dt>
                                            <span>人格特質</span>
                                            <div class="ka_tubicon ka_n">
                                                <br>
                                                @foreach($personality_traits as $option)
                                                    @if($option->xref_id ?? false)
                                                        <div class="ka_tico_1"><i>{{$option->option_name}}</i></div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </dt>
                                    @endif

                                    @if(($life_style->first()->xref_id ?? false) && $to->engroup==2)
                                        <dt>
                                            <span>生活型態</span>
                                            <div class="ka_tubicon ka_n">
                                                <br>
                                                @foreach($life_style as $option)
                                                    @if($option->xref_id ?? false)
                                                        <div class="ka_tico_1"><i>{{$option->option_name}}</i></div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </dt>
                                    @endif

                                    {{--
                                    @if(!empty($to->meta->situation) && $to->meta->situation != null && $to->meta->situation != 'null' && $to->engroup==2)
                                        <dt>
                                            <span>現況</span>
                                            <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta->situation}}</div>
                                        </span>
                                        </dt>
                                    @endif
                                    --}}

                                    @if(!empty($to->meta->domainType) && $to->meta->domainType != null && $to->meta->domainType != 'null'  && $to->engroup==1)
                                    <dt>
                                        <span>產業</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta->domainType}}  @if(!empty($to->meta->domain) && $to->meta->domain != null && $to->meta->domain != 'null'){{$to->meta->domain}}@endif</div>
                                        </span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta->occupation) && $to->meta->isHideOccupation == '0' && ($user->isVip() || $user->isVVIP()) && $to->meta->occupation != 'null' && $to->engroup==1)
                                    <dt>
                                        <span>職業</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta->occupation}}</div>
                                        </span>
                                    </dt>
                                    @endif

                                    @if($to->meta->isHideOccupation == '0' && $user->isVipOrIsVvip() && ($user_option->occupation->option_id ?? false) && $to->engroup==2)
                                    <dt>
                                        <span>工作/學業</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$user_option->occupation->occupation->option_name}}</div>
                                        </span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta->education))
                                    <dt>
                                        <span>教育</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta->education}}</div>
                                        </span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta->marriage) && $to->engroup==1)
                                    <dt>
                                        <span>婚姻</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta->marriage}}</div>
                                        </span>
                                    </dt>
                                    @endif

                                    @if(isset($to->meta->is_pure_dating) && $to->engroup==2)
                                        @if($to->meta->is_pure_dating != -1)
                                        <dt>
                                            <span>希望進一步發展嗎?</span>
                                            <span>
                                                <div class="select_xx01 senhs hy_new">
                                                @if($to->meta->is_pure_dating == 1)
                                                是
                                                @elseif($to->meta->is_pure_dating == 0)
                                                否
                                                @endif
                                                </div>
                                            </span>
                                        </dt>
                                        @endif
                                    @endif

                                    @if(!empty($to->meta->drinking))
                                    <dt>
                                        <span>喝酒</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta->drinking}}</div>
                                        </span>
                                    </dt>
                                    @endif

                                    @if(!empty($to->meta->smoking))
                                    <dt>
                                        <span>抽煙</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta->smoking}}</div>
                                        </span>
                                    </dt>
                                    @endif

                                    {{--
                                    @if(!empty($to->meta->income) && $to->engroup==1)
                                    <dt>
                                        <span>收入</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta->income}}</div>
                                        </span>
                                    </dt>
                                    @endif
                                    --}}

                                    {{--
                                    @if(!empty($to->meta->assets) && $to->engroup==1)
                                    <dt>
                                        <span>資產</span>
                                        <span>
                                            <div class="select_xx01 senhs hy_new">{{$to->meta->assets}}</div>
                                        </span>
                                    </dt>
                                    @endif
                                    --}}



                                </div>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="ziliao">
                            <div class="ztitle"><span>進階資料</span>Advanced materials</div>
                            <div class="xiliao_input">
                                <div class="xl_text">
                                    
                                    <dt><span>註冊時間</span>@if($user->isVip() || $user->isVVIP())<font>{{substr($to->created_at,0,10)}}</font>@else <span class="mtop"><img src="/new/images/icon_35.png"></span> @endif</dt>
                                    <dt><span>最後上線時間</span>
                                        <font  v-if="is_vip">@{{last_login}}</font>
                                        <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span>
                                    </dt>
                                    <dt><span>每周平均上線次數</span>
                                        <span v-if="is_vip"><font>@{{login_times_per_week }}</font></span>
                                        <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span>
                                    </dt>
{{--                                    <dt><span>使用者評價</span>--}}
{{--                                        @if($user->isVipOrIsVvip())--}}
{{--                                            <font>--}}
{{--                                                <div class="star_new">--}}
{{--                                                    @for ($i = 1; $i <= 5; $i++)--}}
{{--                                                        @if(intval($rating_avg)>=$i)--}}
{{--                                                            <img src="/new/images/sxx_1.png">--}}
{{--                                                        @elseif(strstr($rating_avg,'.') && ctype_digit($rating_avg)==false)--}}
{{--                                                            <img src="/new/images/sxx_2.png">--}}
{{--                                                            @break--}}
{{--                                                        @endif--}}
{{--                                                    @endfor--}}
{{--                                                    @for ($i = 1; $i <= 5-ceil($rating_avg); $i++)--}}
{{--                                                        <img src="/new/images/sxx_4.png">--}}
{{--                                                    @endfor--}}
{{--                                                </div>--}}
{{--                                            </font>--}}
{{--                                        @else <img src="/new/images/icon_35.png"> @endif--}}
{{--                                    </dt>--}}
                                    <dt><span>被收藏次數</span>
                                        <span v-if="is_vip">
                                            <font id="be_faved_count" ref="be_faved_count">
                                                @{{be_faved}}
                                            </font>
                                        </span>
                                        <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span>
                                    </dt>
                                    <dt><span>收藏會員次數</span>
                                        <span v-if="is_vip">
                                            <font id="faved_count" ref="faved_count">
                                                @{{faved}}
                                            </font>
                                        </span>
                                        <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span>
                                    </dt>

                                    <dt><span>車馬費邀請次數</span>
                                        <span v-if="is_vip"><font>@{{tip_count}}</font></span>
                                        <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span>
                                    <dt><span>發信次數</span>
                                        <span v-if="is_vip"><font>@{{message_count}}</font></span>
                                        <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span>
                                    <dt><span>過去7天發信次數</span>
                                        <span v-if="is_vip"><font>@{{message_count_7}}</font></span>
                                        <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span>
                                    </dt>
                                    <dt><span>回信次數</span>
                                        <span v-if="is_vip"><font>@{{message_reply_count}}</font></span>
                                        <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span>
                                        </dt>
                                    <dt><span>過去7天回信次數</span>
                                        <span v-if="is_vip"><font>@{{message_reply_count_7}}</font></span>
                                        <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span>
                                    </dt>
                                    <dt><span>過去7天罐頭訊息比例</span>
                                        <span v-if="is_vip"><font>@{{message_percent_7}}</font></span>
                                        <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span>
                                    </dt>
                                    {{-- <dt><span>是否封鎖我</span>
                                        <span v-if="is_vip"><font>@{{is_block_mid}}</font></span>
                                        <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span>
                                    </dt> --}}
                                    <dt><span>是否看過我</span>
                                        <span v-if="is_vip"><font>@{{is_visit_mid}}</font></span>
                                        <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span>
                                    </dt>
                                    <dt><span>瀏覽其他會員次數</span>
                                        <span v-if="is_vip"><font>@{{visit_other_count}}</font></span>
                                        <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span>
                                    </dt>
                                    <dt><span>過去7天瀏覽其他會員次數</span>
                                        <span v-if="is_vip"><font>@{{visit_other_count_7}}</font></span>
                                        <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span>
                                    </dt>
                                    <dt><span>被瀏覽次數</span>
                                        <span v-if="is_vip"><font>@{{be_visit_other_count}}</font></span>
                                        <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span>
                                    </dt>
                                    <dt><span>過去7天被瀏覽次數</span>
                                        <span v-if="is_vip"><font>@{{be_visit_other_count_7}}</font></span>
                                        <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span>
                                    </dt>

                                    <dt><span>封鎖多少會員</span>
                                        <span v-if="is_vip">
                                            <font id="blocked_other_count" ref="blocked_other_count">
                                                @{{blocked_other_count}}
                                            </font>
                                        </span>
                                        <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span>
                                    </dt>
                                    <dt><span>被多少會員封鎖</span>
                                        <span v-if="is_vip">
                                            <font id="be_blocked_other_count" ref="be_blocked_other_count">
                                                @{{be_blocked_other_count}}
                                            </font>
                                        </span>
                                        <span class="mtop" v-else><img src="/new/images/icon_35.png" /></span>
                                    </dt>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="line"></div>

                    <div id="hash_evaluation" class="ziliao ziliao3">
                        <dl class="hy_ceng system">
                            <a class="diwo click_me">請按我</a>
                            <div id="click_me" class="he_tkcn showslide_evaluation2" style="top:unset;z-index:1;margin-top:20px;">
                                <ul>
                                    <a class="myself_evaluation">
                                        <img src="/new/images/icon_p1.png" class="he_tkcn_img">本人評價
                                    </a>
                                    <a class="anonymous_evaluation">
                                        <img src="/new/images/icon_p2.png" class="he_tkcn_img">匿名評價
                                    </a>
                                </ul>
                            </div>
                            <script>
                                $('.click_me').click(function() {
                                    event.stopPropagation()
                                    var on3 = $('.bottub').find('.on3');
                                    if(on3.length) {
                                        on3.removeClass('on3');
                                        $('.bottub').find('.showslide_evaluation2').fadeOut();
                                    }
                                    if($(this).hasClass('on3')) {
                                        $(this).removeClass('on3')
                                        $('.showslide_evaluation2').fadeOut()
                                    } else {
                                        $(this).addClass('on3')
                                        $('.fadeinboxs').fadeIn()
                                        $('.showslide_evaluation2').fadeIn()
                                    }
                                })
                            </script>
                            @if(sizeof($evaluation_data) > 0)
                                <dt class="hypingjia">
                                    <div class="hy_font1">會員評價</div><div class="hy_font2">Evaluation</div>
                                </dt>
                                <dd class="xiliao_input" style="margin-top: -8px;">
                                    <div class="xl_text">
                                        <div class="pjliuyan02 amar15 mohu_li" style=" min-height: auto; margin-bottom: 0;">
                                            @php
                                                // print_r($evaluation_data);
                                                 $showCount = 0;
                                                 $blockMidList = array();
                                                 $isVip=($user->isVip() || $user->isVVIP());
                                            @endphp
                                            @if((!$isVip && $user->id!=$to->id) && sizeof($evaluation_data) > 0)<div class="mohu_icon" id="mohu_icon"><img src="/new/images/icon_36.png"></div>@endif
                                            @if(sizeof($evaluation_data) > 0)
                                                <ul style="width: 100%;" class="showSelfEvaluation_notvip" ></ul>
                                                <ul style="width: 100%;" class="evaluationList {{ !$isVip && $user->id!=$to->id ? 'mohu01':'' }}">
                                                    @foreach( $evaluation_data as $row)
                                                        @php
                                                            $row_user = \App\Models\User::findById($row->from_id);
                                                            $to_user = \App\Models\User::findById($row->to_id);
                                                            $isBlocked = \App\Models\Blocked::isBlocked($row->to_id, $row->from_id);
                                                            $hadWarned = DB::table('is_warned_log')->where('user_id',$row_user->id)->first();
                                                            $warned_users = DB::table('warned_users')->where('member_id',$row_user->id)
                                                                ->where(function($warned_users){
                                                                $warned_users->where('expire_date', '>=', \Carbon\Carbon::now())
                                                                    ->orWhere('expire_date', null); })->first();

                                                            if($isBlocked || isset($hadWarned) || isset($warned_users)) {
                                                                array_push( $blockMidList, $row );
                                                                continue;
                                                            }
                                                            $showCount++;
                                                        @endphp
                                                        @if(!$isBlocked && !isset($hadWarned) && !isset($warned_users))
                                                            <li class="{{ ($row_user->id == $user->id)  ? 'showSelfEvaluation':'' }}">
                                                                <div class="piname">
                                                                    {{-- <span>
                                                                         @for ($i = 1; $i <= 5; $i++)
                                                                             @if($row->rating>=$i)
                                                                                 <img src="/new/images/sxx_1.png">
                                                                             @else
                                                                                 <img src="/new/images/sxx_4.png">
                                                                             @endif
                                                                         @endfor
                                                                     </span>--}}
                                                                    @if ($row->content_violation_processing)
                                                                        <span>匿名評價</span><span style="color: red; font-size:12px;">(站方代發)</span>
                                                                    @else
                                                                        <a href="/dashboard/viewuser/{{ $row_user->id }}">{{ $row->user->name }}</a>
                                                                    @endif
                                                                    {{--                                <font>{{ substr($row->created_at,0,10)}}</font>--}}
                                                                    @if($row_user->id == $user->id)
                                                                        <font class="sc content_delete" data-id="{{ $row->id }}" style="padding: 0px 3px;"><img src="/new/images/del_03.png" style="padding: 0px 0px 1px 5px;">刪除</font>
                                                                    @endif
                                                                    @if ($row->content_violation_processing && $row->to_id==$user->id) <a class="primessage sc" href="{{ route('getAnonymousEvaluationChat',['evaluationid'=>$row->id]) }}">私訊溝通</a> @endif
                                                                </div>
                                                                <div class="con">
                                                                    @if($row->is_check==1)
                                                                        <p class="many-txt" style="color: red;">***此評價目前由站方審核中***</p>
                                                                    @else
                                                                        <p class="many-txt">{!! nl2br($row->content) !!}@if(!is_null($row->admin_comment))<span style="color: red;">{{ ' ('.$row->admin_comment.')' }}</span> @endif</p>
                                                                    @endif
                                                                    @if(!$row->only_show_text)
                                                                        @php
                                                                            $evaluationPics=\App\Models\EvaluationPic::where('evaluation_id',$row->id)->where('member_id',$row->from_id)->get();
                                                                        @endphp
                                                                        @if($row->is_check==0)
                                                                            @if($evaluationPics->count()>0)
                                                                                <ul class="zap_photo {{ $evaluationPics->count()>3 ? 'huiyoic':'' }}">
                                                                                    @foreach($evaluationPics as $evaluationPic)
                                                                                        <li><img src="{{ $evaluationPic->pic }}"></li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                    <h4>
                                                                        <span class="btime">{{ substr($row->created_at,0,10)}}</span>
                                                                        <button type="button" class="al_but show_all_evaluation">[完整評價]</button>
                                                                    </h4>
                                                                </div>
                                                                {{--                                                || $user->id==697--}}
                                                                @if(empty($row->re_content) && $to->id == $user->id)
                                                                    <div class="huf" style="width: 100%;">
                                                                        <form id="form_re_content{{$row->id}}" action="{{ route('evaluation_re_content')."?n=".time() }}" method="post" enctype="multipart/form-data">
                                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                            <span class="huinput">
                                                                    <a id="xin_nleft_qq_{{ $row->id }}" class="xin_nleft_qq" onclick="tab_evaluation_reply_show('{{$row->id}}','{{$to->id}}');"><img src="/new/images/moren_pic.png"></a>
                                                                    <textarea id="re_content_{{ $row->id }}"name="re_content" type="text" class="hf_i xin_input_qq" placeholder="請輸入回覆（最多120個字元）" maxlength="120"></textarea>
                                                                </span>
                                                                            <div id="re_area_{{ $row->id }}" class="re_area">
                                                                                <a class="hf_but" data-id="{{$row->id}}" {{--onclick="form_re_content_submit()"--}}>回覆</a>
                                                                            </div>
                                                                            <input type="hidden" name="id" value={{$row->id}}>
                                                                            <input type="hidden" name="eid" value={{$to->id}}>
                                                                        </form>
                                                                    </div>
                                                                    <script type="application/javascript">
                                                                        setTextAreaHeight('{{ $row->id }}');
                                                                    </script>
                                                                @elseif(!empty($row->re_content))
                                                                    <div class="hu_p">
                                                                        <div class="he_b">
                                                                            <span class="left"><img src="@if(file_exists( public_path().$to->meta->pic ) && $to->meta->pic != ""){{$to->meta->pic}} @elseif($to->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="he_zp">{{$to->name}}</span>
                                                                            @if($to->id==$user->id)
                                                                                <font class="sc re_content_delete" data-id="{{$row->id}}" data-userid="{{ $to->id }}"><img src="/new/images/del_03.png">刪除</font>
                                                                            @endif
                                                                        </div>
                                                                        <div class="he_two">
                                                                            <div class="context">
                                                                                @if($row->is_check==1)
                                                                                    <div id="test" class="context-wrap" style="word-break: break-all;color: red;">***此評價目前由站方審核中***</div>
                                                                                @else
                                                                                    <div id="test" class="context-wrap" style="word-break: break-all;">{!! nl2br($row->re_content) !!}</div>
                                                                                @endif
                                                                                @if(!$row->only_show_text)
                                                                                    @php
                                                                                        $evaluationPics=\App\Models\EvaluationPic::where('evaluation_id',$row->id)->where('member_id',$to->id)->get();
                                                                                    @endphp
                                                                                    @if($row->is_check==0)
                                                                                        @if($evaluationPics->count()>0)
                                                                                            <ul class="zap_photo {{ $evaluationPics->count()>3 ? 'huiyoic':'' }}">
                                                                                                @foreach($evaluationPics as $evaluationPic)
                                                                                                    <li><img src="{{ $evaluationPic->pic }}"></li>
                                                                                                @endforeach
                                                                                            </ul>
                                                                                        @endif
                                                                                    @endif
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="he_twotime">{{ substr($row->re_created_at,0,10)}}<span class="z_more">展開</span></div>
                                                                    </div>
                                                                @endif
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                    @if(sizeof($blockMidList) > 0)
                                                        @php
                                                            //print_r($blockMidList);
                                                        @endphp
                                                        <div style="display: none;" id="plshow">
                                                            @foreach($blockMidList as $row)
                                                                @php
                                                                    //print_r($row->from_id);
                                                                        $row_user = \App\Models\User::findById($row->from_id);
                                                                        $to_user = \App\Models\User::findById($row->to_id);
                                                                        //$isBlocked = \App\Models\Blocked::isBlocked($user->id, $row->from_id);
                                                                        $hadWarned = DB::table('is_warned_log')->where('user_id',$row_user->id)->first();
                                                                        $warned_users = DB::table('warned_users')->where('member_id',$row_user->id)
                                                                            ->where(function($warned_users){
                                                                            $warned_users->where('expire_date', '>=', \Carbon\Carbon::now())
                                                                                ->orWhere('expire_date', null); })->first();
                                                                        $showCount++;
                                                                @endphp
                                                                <li class="{{ ($row_user->id == $user->id)  ? 'showSelfEvaluation_block':'' }}">
                                                                    <div class="kll">
                                                                        <div class="piname">
                                                                            {{--<span>
                                                                                @if(!$warned_users && !$hadWarned)
                                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                                        @if($row->rating>=$i)
                                                                                            <img src="/new/images/sxx_1.png">
                                                                                        @else
                                                                                            <img src="/new/images/sxx_4.png">
                                                                                        @endif
                                                                                    @endfor
                                                                                @endif
                                                                            </span>--}}
                                                                            @if ($row->content_violation_processing)
                                                                                <span>匿名評價</span><span style="color: red; font-size:12px;">(站方代發)</span>
                                                                            @else
                                                                                <a href="/dashboard/viewuser/{{$row_user->id}}">{{$row_user->name}}</a>
                                                                            @endif
                                                                            @if(isset($warned_users) || isset($hadWarned))
                                                                                <img src="/new/images/kul.png" class="sxyh">
                                                                            @else
                                                                                <img src="/new/images/kul02.png" class="sxyh">
                                                                            @endif
                                                                            {{--                                <font>{{ substr($row->created_at,0,10)}}</font>--}}
                                                                            @if($row_user->id==$user->id)
                                                                                <font class="sc content_delete" data-id="{{$row->id}}" style="padding: 0px 3px;"><img src="/new/images/del_03.png" style="padding: 0px 0px 1px 5px;">刪除</font>
                                                                            @endif
                                                                            @if ($row->content_violation_processing && $row->to_id==$user->id) <a class="primessage sc" href="{{ route('getAnonymousEvaluationChat',['evaluationid'=>$row->id]) }}">私訊溝通</a> @endif
                                                                        </div>
                                                                        <div class="con">
                                                                            @if($row->is_check==1)
                                                                                <p class="many-txt" style="color: red;">***此評價目前由站方審核中***</p>
                                                                            @else
                                                                                <p class="many-txt">{!! nl2br($row->content) !!}@if(!is_null($row->admin_comment))<span style="color: red;">{{ ' ('.$row->admin_comment.')' }}</span> @endif</p>
                                                                            @endif
                                                                            @if(!$row->only_show_text)
                                                                                @php
                                                                                    $evaluationPics=\App\Models\EvaluationPic::where('evaluation_id',$row->id)->where('member_id',$row->from_id)->get();
                                                                                @endphp
                                                                                @if($row->is_check==0)
                                                                                    @if($evaluationPics->count()>0)
                                                                                        <ul class="zap_photo {{ $evaluationPics->count()>3 ? 'huiyoic':'' }}">
                                                                                            @foreach($evaluationPics as $evaluationPic)
                                                                                                <li><img src="{{ $evaluationPic->pic }}"></li>
                                                                                            @endforeach
                                                                                        </ul>
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                            <h4>
                                                                                <span class="btime">{{ substr($row->created_at,0,10)}}</span>
                                                                                <button type="button" class="al_but show_all_evaluation">[完整評價]</button>
                                                                            </h4>
                                                                        </div>
                                                                    </div>

                                                                    @if(empty($row->re_content) && $to->id == $user->id)
                                                                        <div class="huf" style="width: 100%;">
                                                                            <form id="form_re_content{{$row->id}}" action="{{ route('evaluation_re_content')."?n=".time() }}" method="post" method="post" enctype="multipart/form-data">
                                                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                                <span class="huinput">
                                                                    <a id="xin_nleft_qq_{{$row->id}}" class="xin_nleft_qq" onclick="tab_evaluation_reply_show('{{$row->id}}','{{$to->id}}');"><img src="/new/images/moren_pic.png"></a>
                                                                    <textarea id="re_content_{{ $row->id }}" name="re_content" type="text" class="hf_i xin_input_qq" placeholder="請輸入回覆（最多120個字元）" maxlength="120"></textarea>
                                                                </span>
                                                                                <div id="re_area_{{$row->id}}" class="re_area">
                                                                                    <a class="hf_but" data-id="{{$row->id}}" {{--onclick="form_re_content_submit()"--}}>回覆</a>
                                                                                </div>
                                                                                <input type="hidden" name="id" value={{$row->id}}>
                                                                                <input type="hidden" name="eid" value={{$to->id}}>
                                                                            </form>
                                                                        </div>
                                                                        <script type="application/javascript">
                                                                            setTextAreaHeight('{{ $row->id }}');
                                                                        </script>
                                                                    @elseif(!empty($row->re_content))
                                                                        <div class="hu_p">
                                                                            <div class="he_b">
                                                                                <span class="left"><img src="@if(file_exists( public_path().$to_user->meta_()->pic ) && $to_user->meta_()->pic != ""){{$to_user->meta_()->pic}} @elseif($to_user->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="he_zp">{{$to_user->name}}</span>
                                                                                @if($to_user->id==$user->id)
                                                                                    <font class="sc re_content_delete" data-id="{{$row->id}}" data-userid="{{ $to->id }}"><img src="/new/images/del_03.png">刪除</font>
                                                                                @endif
                                                                            </div>
                                                                            <div class="he_two">
                                                                                <div class="context">
                                                                                    @if($row->is_check==1)
                                                                                        <div id="test" class="context-wrap" style="word-break: break-all;color: red;">***此評價目前由站方審核中***</div>
                                                                                    @else
                                                                                        <div id="test" class="context-wrap" style="word-break: break-all;">{!! nl2br($row->re_content) !!}</div>
                                                                                    @endif
                                                                                    @if(!$row->only_show_text)
                                                                                        @php
                                                                                            $evaluationPics=\App\Models\EvaluationPic::where('evaluation_id',$row->id)->where('member_id',$to->id)->get();
                                                                                        @endphp
                                                                                        @if($row->is_check==0)
                                                                                            @if($evaluationPics->count()>0)
                                                                                                <ul class="zap_photo {{ $evaluationPics->count()>3 ? 'huiyoic':'' }}">
                                                                                                    @foreach($evaluationPics as $evaluationPic)
                                                                                                        <li><img src="{{ $evaluationPic->pic }}"></li>
                                                                                                    @endforeach
                                                                                                </ul>
                                                                                            @endif
                                                                                        @endif
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            <div class="he_twotime">{{ substr($row->re_created_at,0,10)}}<span class="z_more">展開</span></div>
                                                                        </div>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </div>
                                                        <div class="hzk toggleBlockMid">
                                                            <img src="/new/images/zk_icon.png">
                                                            <h2>部分被封鎖的會員評價已經被隱藏，點此全部顯示</h2>
                                                        </div>
                                                    @endif
                                                </ul>
                                                <div id="evaluation_page" style="text-align: center;">
                                                    {!! $evaluation_data->appends(request()->input())->links('pagination::sg-pages2') !!}
                                                </div>
                                                @if($showCount < 1)
                                                    <div class="pjliuyan02 amar15" style=" min-height: auto; margin-bottom: 0;">
                                                        <div class="huiy_na"><img src="/new/images/pjicon.png" class="feng_img"><span>暫無資料</span></div>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="pjliuyan02 amar15" style=" min-height: auto; margin-bottom: 0;">
                                                    <div class="huiy_na"><img src="/new/images/pjicon.png" class="feng_img"><span>暫無資料</span></div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </dd>
                            @else
                                <dt class="hypingjia" style="background-image: linear-gradient(90deg,#ffced9,#ffeef1)">
                                    <div class="hy_font1">會員評價</div><div class="hy_font2">Evaluation</div>
                                </dt>
                                <div class="pjliuyan02 amar15" style=" min-height: auto; margin-bottom: 10px;">
                                    <div class="huiy_na"><img src="/new/images/pjicon.png" class="feng_img"><span>暫無資料</span></div>
                                </div>
                            @endif
                        </dl>


                    </div>
                    @if($message_board_list->count())
                        <div class="ziliao ziliao3">
                            <dl class="hy_ceng system" style="margin-bottom: 50px;">
                                <dt class="hypingjia">
                                    <div class="hy_font1">留言紀錄</div><div class="hy_font2"> Wishing Board</div>
                                </dt>
                                <dd id="showMoreMsg" class="xiliao_input nerong" style="display: none;" >
                                    <div class="xl_text">
                                        <div class="pjliuyan02 amar15" style=" min-height: auto; margin-bottom: 0;">
                                            <ul>
                                                @foreach($message_board_list as $list)
                                                    <a href="/MessageBoard/post_detail/{{ $list->id }}?from_viewuser_page=1">
                                                        <div class="ly_prilist" >
                                                            <div id="messageBoard_{{ $list->id }}" class="ly_text">
                                                                <div class="ly_text_1"><div class="ly_lfontleft">{{ $list->title }}</div><div class="ly_time">{{ date('Y-m-d', strtotime($list->created_at)) }}</div></div>
                                                                <div class="liu_text_2">{{ $list->contents }}</div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    @endif
                @endif
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
                            <button type="submit" class="n_right" style="border-style: none; background: #8a9ff0; color:#ffffff;float: unset; margin-left: 0px; margin-right: 20px;">送出</button>
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
                        <input type="hidden" name="content_processing_method" value="">
                        <span class="alert_tip" style="color:red;"></span>
                        <input type="file" name="images" >
                        <div class="new_pjckbox">
                            <div class="anonymous_illustrate">
                                ● 請上傳可資證明的圖檔，須為高清檔案<br>
                                ● 若為對話紀錄，須從頭到尾<a style="color:red;">完整截圖，上一句跟著下一句，不可以漏</a><br>
                                ● 對話記錄請<a style="color:red">依照順序上傳，順序錯誤會被退件</a><br>
                                ● 若對話紀錄過多(超過30頁)，那可以只截相關部分，一樣完整截圖，上一句跟著下一句，不可以漏<br>
                            </div>
                            <div class="self_illustrate">
                                ● 請上傳可資證明的圖檔，須為高清檔案<br>
                                ● 若為對話紀錄，須從頭到尾<a style="color:red;">完整截圖，上一句跟著下一句，不可以漏</a><br>
                                ● 若對話紀錄過多(超過30頁)，那可以只截相關部分，一樣完整截圖，上一句跟著下一句，不可以漏<br>
                                評價請以敘述<a class="text-danger" style="color: red;">確實發生的事實</a>為主，不要有主觀判斷，盡量附上截圖佐證。若被評價者來申訴，您又沒有附上截圖，評價在驗證屬實前會被隱藏或撤銷。
                                <span><input type="checkbox" name="agree"><label style="color:black;">我同意上述說明</label></span>
                            </div>
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
            {{--<div class="new_tkfont">您目前未達評價標準<br>不可對此會員評價</div>--}}
            <div class="new_tablema">
                <table>

                    {{--<tr class="phone_auth">
                        <td class="new_baa new_baa1">女生須通過手機驗證</td>
                        <td class="new_baa1">
                            @if($auth_check)
                                <img src="/new/images/ticon_01.png">
                            @else
                                <img src="/new/images/ticon_02.png">
                            @endif
                        </td>
                    </tr>--}}
                    <tr class="advance_auth">
                        <td class="new_baa new_baa1">女生須通過進階驗證</td>
                        <td class="new_baa1">
                            @if($advance_auth_status)
                                <img src="/new/images/ticon_01.png">
                            @else
                                <img src="/new/images/ticon_02.png">
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="new_baa">男方須回覆女方三次以上</td>
                        <td class="">@if(!$isSent3Msg)<img src="/new/images/ticon_02.png">@else<img src="/new/images/ticon_01.png">@endif</td>
                    </tr>
                </table>
            </div>
            <div class="new_tkfont" style="text-align: left;margin-top: 10px;">
                <span>1:請盡量附上證據(對話截圖或其他)，若無相關證據有可能被移除。</span><br>
                <span>2:請平鋪直敘事情經過，<span style="color: red;">禁止人身攻擊，情緒性發言</span>。</span><br>
                <span>3:相關證據請保留兩周，供站方查核</span><br>
                <span>4:匿名評價為站方代會員發表較為嚴重的評價，例如放鴿子，言行前後不一等，心情抒發與好評請直接具名評價。</span>
            </div>
            <div class="n_bbutton" style="margin-top:10px;">
                <div style="display: inline-flex;">
                    @if($advance_auth_status && $isSent3Msg)
                        <div class="n_right enter_tab_evaluation" style="border-style: none; background: #8a9ff0; color:#ffffff; float: unset; margin-left: 0px; margin-right: 20px;" onclick="show_tab_evaluation()">進入評價</div>
                        <div class="n_left" style="border: 1px solid #8a9ff0; background: #ffffff; color:#8a9ff0; float: unset; margin-right: 0px;" onclick="tab_cancel();" >取消</div>
                    @else
                        <div class="n_left" style="border: 1px solid #8a9ff0; color:#ffffff; float: unset; margin-right: 0px;background: rgb(138, 159, 240);" onclick="tab_cancel();" >取消</div>
                    @endif
                </div>
            </div>
        </div>
        <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>

    <div class="bl bl_tab" id="tab_reject_male" style="display: none;">
        <div class="bltitle"><span>提示</span></div>
        <div class="n_blnr01 ">
            {{--<div class="new_tkfont">您目前未達評價標準<br>不可對此會員評價</div>--}}
            <div class="new_tablema">
                <table>

                    <tr class="vipDays">
                        <td class="new_baa new_baa1">男方須為一個月(不含一個月)以上VIP</td>
                        <td class="new_baa1">
                            @if($vipDays>=30)
                                <img src="/new/images/ticon_01.png">
                            @else
                                <img src="/new/images/ticon_02.png">
                            @endif
                        </td>
                    </tr>
                    <tr class="need_vip">
                        <td class="new_baa new_baa1">男生須為VIP</td>
                        <td class="new_baa1">
                            @if($isVip)
                                <img src="/new/images/ticon_01.png">
                            @else
                                <img src="/new/images/ticon_02.png">
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="new_baa">女方須有回覆男方三次以上</td>
                        <td class="">@if(!$isSent3Msg)<img src="/new/images/ticon_02.png">@else<img src="/new/images/ticon_01.png">@endif</td>
                    </tr>
                </table>
            </div>
            <div class="new_tkfont" style="text-align: left;margin-top: 10px;">
                <span>1:請盡量附上證據(對話截圖或其他)，若無相關證據有可能被移除。</span><br>
                <span>2:請平鋪直敘事情經過，<span style="color: red;">禁止人身攻擊，情緒性發言</span>。</span><br>
                <span>3:相關證據請保留兩周，供站方查核</span>
            </div>
            <div class="n_bbutton" style="margin-top:10px;">
                <div style="display: inline-flex;">
                    @if($vipDays>=30 && $isSent3Msg)
                        <div class="n_right enter_tab_evaluation" style="border-style: none; background: #8a9ff0; color:#ffffff; float: unset; margin-left: 0px; margin-right: 20px;" onclick="show_tab_evaluation()">進入評價</div>
                        <div class="n_left" style="border: 1px solid #8a9ff0; background: #ffffff; color:#8a9ff0; float: unset; margin-right: 0px;" onclick="tab_cancel();">取消</div>
                    @else
                        <div class="n_left" style="border: 1px solid #8a9ff0; color:#ffffff; float: unset; margin-right: 0px;background: rgb(138, 159, 240);" onclick="tab_cancel();" >取消</div>
                    @endif
                </div>
            </div>
        </div>
        <a id="" onClick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>

    <div class="bl bl_tab" id="evaluation_description" style="display: none;">
        <div class="bltitle"><span>匿名評價說明</span></div>
        <div class="n_blnr01 ">
            <div class="new_tkfont" style="text-align:left">
                ● 匿名評價將不會出現你的名字<br>
                ● 站方有權決定是否代為發布評價<br>
                <label style="margin-buttom: 0; font-weight: normal; cursor: pointer;">
                    <input type="checkbox" name="message_processing" value="modify_directly" />
                    <span>若評價不符合審查標準，我願意接受站方修改。</span>
                </label>
                <label style="margin-buttom: 0; font-weight: normal; cursor: pointer;">
                    <input type="checkbox" name="message_processing" value="return" />
                    <span>若評價不符合審查標準，我不願意接受站方修改。將直接退件處理。</span>
                </label>
                <span class="evaluation_check_alert_tip" style="color:red;"></span>
            </div>
            <div class="n_bbutton" style="margin-top:10px;">
                <div style="display: inline-flex;">
                <div class="n_right evaluation_check" style="border-style: none; background: #8a9ff0; color:#ffffff; float: unset; margin-left: 0px; margin-right: 20px;">同意</div>
                <button type="reset" class="n_left" style="border: 1px solid #8a9ff0; background: #ffffff; color:#8a9ff0; float: unset; margin-right: 0px;" onclick="evaluation_description_close()">取消</button>
                </div>
            </div>
        </div>
    </div>
    </div>
    @endif

    <!--照片查看-->
    <div class="big_img">
        <!-- 自定义分页器 -->
        <div class="swiper-num">
            <span class="active"></span>/
            <span class="total"></span>
        </div>
        <div class="swiper-container2">
            <div class="swiper-wrapper">
            </div>
        </div>
        <div class="swiper-pagination2"></div>
    </div>
    @if($user->engroup==2)
    <div class="blbg_not_blurry" id="blbg_not_blurry" onclick="gmBtn1_not_blurry();$(this).hide()" style="display: none;"></div>
    <div class="bl bl_tab " id="tab_not_blurry">
        <div class="bltitle"><font>開放清晰照說明</font></div>
        <div class="new_poptk">
            <p class="context" style="-webkit-user-modify: read-only;outline: none;white-space: pre-line; margin: auto;width:80%;padding-left:5px;font-size:18px;">
                此功能會開放您的照片為清晰版給您當前指定的會員，包含大頭照、生活照，您可以隨時取消。
            </p>
            <div class="n_bbutton">
                            <span><a class="btn_left blured_s" onclick="gmBtn1_not_blurry();not_show_blurry_switch_popup('not_blurry_not_show_popup');bxs();">不再提示</a></span>
                            <span><a class="btn_right blured_s" onclick="gmBtn1_not_blurry();bxs();">確定開放</a></span>
                            <span><a class="btn_left cleared_s" onclick="gmBtn1_not_blurry();not_show_blurry_switch_popup('to_blurry_not_show_popup');xs();">不再提示</a></span>
                            <span><a class="btn_right cleared_s" onclick="gmBtn1_not_blurry();xs();">確定取消</a></span>
            </div>
        </div>
        <a onclick="gmBtn1_not_blurry();" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>  
    {{--
    <div class="bl bl_tab " id="tab_not_blurry" style="display: none;">
        <div class="bltitle"><font>開放清晰照說明</font></div>
        <div class="new_poptk">
            <div class="n_heighnn">
                <div class="n_gd"><div class="n_gd_t"></div></div>
                <div class="yidy_tk">
                    <div class="giti">
                        <h2 class="t_list">
                            <img src="{{asset('new/images/ciicon_h.png')}}">
                            <font>此功能會開放您的照片為清晰版給您當前指定的會員，包含大頭照、生活照，您可以隨時取消。</font>
                        </h2>
                    </div>
                </div>      
            </div>
            
                
            <div class="n_bbutton" >     
                <span><a class="n_right" onclick="gmBtn1_not_blurry();not_show_blurry_switch_popup();bxs();">不再提示</a></span>
                <span><a class="n_left" onclick="gmBtn1_not_blurry();bxs();">確定開放</a></span>
            </div>
        </div>
        <a id="" onclick="gmBtn1_not_blurry();" class="bl_gb"><img src="{{asset('new/images/gb_icon.png')}}"></a>
    </div> 
    --}}
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
    let is_warned = {{ $isAdminWarned ? 1 :0 }};
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

   function show_reportPic() {

        if(is_banned){
            return  c5('您目前被站方封鎖，無檢舉權限');
        }
        if(is_warned){
            return  c5('您目前被站方警示，無檢舉權限');
        }

        var uid='{{ $user->id }}';
        var to='{{$to->id}}';
        if(uid != to){
            $(".blbg").show();
            $("#show_reportPic").show();
            $('body').css("overflow", "hidden");
            // alert($('.swiper-slide-active').data('type'));
            $('input[name="picType"]').val($('.swiper-slide-active').data('type'));
            $('input[name="pic_id"]').val($('.swiper-slide-active').data('pic_id'));
        }else{
            c5('不可檢舉自己');
        }
    }     

    $( document ).ready(function() {


        var isVip='{{ $isVip && $user->id!=$to->id }}';

        if(!isVip){
            //非VIP會員只顯示自己的評價, 其餘評價模糊處理
            if($('.evaluationList li').hasClass('showSelfEvaluation')){
                $('.showSelfEvaluation_notvip').html('<li>'+$('.showSelfEvaluation').html() +'</li>');
                $('#mohu_icon').css("top", "78%");
            }
            if($('.evaluationList li').hasClass('showSelfEvaluation_block')){
                $('.showSelfEvaluation_notvip').append('<li>'+$('.showSelfEvaluation_block').html() +'</li>');
                $('#mohu_icon').css("top", "78%");
            }
        }

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

        var vipDiff = parseInt('{{ ($user->isVip() || $user->isVVIP()) ? '6' : '0'}}');

        if(window.matchMedia("(min-width: 992px)").matches && window.matchMedia("(max-width: 1599px)").matches){
            $(".swiper-container").css('height',$(".metx").height()- 106);
        }
        if(window.matchMedia("(min-width: 1600px)").matches){
            $(".swiper-container").css('height',$(".metx").height()- 106);
        }
        if(window.matchMedia("(min-width: 376px)").matches && window.matchMedia("(max-width: 991px)").matches){
            $(".swiper-container").css('height',$(".metx").height() - 106 );
        }
        

        if( /Android|webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            /* if(window.matchMedia("(max-width: 375px)").matches){
                console.log(375)
                $(".swiper-container").css('height',$(".metx").height()- 46);
            }
            if(window.matchMedia("(min-width: 767px)").matches && window.matchMedia("(max-width: 770px)").matches){
                console.log("768px")
                $(".swiper-container").css('height',$(".metx").height()- 46);
            } */
            /* if(window.innerWidth > window.innerHeight){
                console.log("land")
                $(".swiper-container").css('height',$(".metx").height()- 55);
            } */
        } else {
            
        }

        //固定高取得
        var bottom_height=$('.tubiao ul').height();
        //浮動高度
        var img_height = $(".swiper-container").height();
        // alert(img_height);
        $(".swiper-slide img").css('height',img_height - (bottom_height/2) + 25);
        // $(".swiper-slide img").css('height',img_height);
        $('.tubiao').css('top',img_height - (bottom_height/2) + 5);
        $(window).resize(function() {
            // alert($('.tubiao ul').height());
            // var wdth=$(window).width();
            // $("span").text(wdth);
            var img_height = $(".swiper-container").height();
            $(".swiper-slide img").css('height',img_height - (bottom_height/2) + 20);
            // $(".swiper-slide img").css('height',img_height);
            $('.tubiao').css('top',img_height - (bottom_height/2) + 5);
            // alert(img_height - ($('.tubiao ul').height() / 2));
        });

        if( /Android|webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            // $('.metx').css('position','unset');
        }

        $('.content_delete').on( "click", function() {
            c4('確定要刪除嗎?');
            var id = $(this).data('id');
            $(".n_left").on('click', function() {
                $.post('{{ route('evaluation_delete') }}', {
                    id: id,
                    _token: '{{ csrf_token() }}'
                }, function (data) {
                    $("#tab04").hide();
                    show_pop_message('評價已刪除');

                });
            });
        });

        $('.re_content_delete').on( "click", function() {
            c4('確定要刪除嗎?');
            var id = $(this).data('id');
            var userid = $(this).data('userid');
            $(".n_left").on('click', function() {
                $.post('{{ route('evaluation_re_content_delete') }}', {
                    id: id,
                    userid:userid,
                    _token: '{{ csrf_token() }}'
                }, function (data) {
                    $("#tab04").hide();
                    show_pop_message('回覆已刪除');
                });
            });
        });

        $('.hf_but').on( "click", function() {

            if($('#form_re_content'+ $(this).data('id')).find('.hf_i').val() == ''){
                c5('請輸入內容');
            }else{
                $('#form_re_content'+ $(this).data('id')).submit();
            }

        });

        //let button = document.getElementsByTagName('button');
        let button = document.getElementsByClassName('show_all_evaluation');
        let p = document.getElementsByTagName('p');

        for (let i = 0; i < button.length; i++) {
            button[i].onclick = function() {
                if (this.innerHTML == "[完整評價]") {
                    p[i].classList.remove("many-txt");
                    p[i].classList.add("all-txt");
                    this.innerHTML = "[點擊收起]";
                    $(this).parent().siblings('.zap_photo').removeClass('huiyoic');
                } else {
                    p[i].classList.remove("all-txt");
                    p[i].classList.add("many-txt");
                    this.innerHTML = "[完整評價]";
                    $(this).parent().siblings('.zap_photo').addClass('huiyoic');
                }
            }
        }

        $(".z_more").on( "click", function() {
            $(this).parent().prev().find('.context').find("div").first().toggleClass('on context-wrap')
            $(this).html($(this).text() === '展開' ? '收起' : '展開');
            $(this).parent().prev().find('.context').find(".zap_photo").toggleClass('huiyoic');
        });

        $('div.context-wrap').each(function(i) {
            var more_elt = $(this).parents('.hu_p').find('span.z_more');
            var p_elt = $(this).parents('.hu_p').find('ul.zap_photo');
            if (isEllipsisActive(this)) {
                more_elt.removeClass('hide_more');
                more_elt.removeClass('show_more');
                more_elt.addClass('show_more');
            }
            else {
                more_elt.removeClass('show_more');
                more_elt.removeClass('hide_more');
                more_elt.addClass('hide_more');
            }
            
            if(more_elt.hasClass('hide_more')) {
                if (p_elt.length>0 && isEllipsisActive(p_elt)) {
                    more_elt.removeClass('hide_more');
                    more_elt.removeClass('show_more');
                    more_elt.addClass('show_more');
                }
                else {
                    more_elt.removeClass('show_more');
                    more_elt.removeClass('hide_more');
                    more_elt.addClass('hide_more');
                }                            
            }            
        });
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
    
	$(document).ready(function () {
    // $( document ).ready(function() {
        @if(isset($to))
            @if(isset($is_block_mid) && $is_block_mid == '是')
                // ccc('此用戶已關閉資料。');
                // $('.row').css('display','none');
            @elseif($to->accountStatus == 0)
                // ccc('此用戶已關閉資料。');
                // $('.row').css('display','none');
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

    @if(isset($to))
        $(".but_block").on('click', function() {
            let uid='{{ $user->id }}';
            let to='{{$to->id}}';
            if(uid != to){
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
            }else{
                c5('不可封鎖自己');
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
                        show_pop_message('已解除封鎖');
                    });
                });
            }else{
                c5('不可解除封鎖自己');
            }
        });

        $(".favIcon").on('click', function() {
            if($(this).hasClass('removeFav')){
                removeFav();
            }else{
                addFav();
            }
        });

        function addFav(){
            var uid='{{ $user->id }}';
            var to='{{$to->id}}';
            if(uid != to){
                $.post('{{ route('postfavAJAX') }}', {
                    uid: uid,
                    to: to,
                    _token: '{{ csrf_token() }}'
                }, function (data) {
                    var is_success = false;
                    if(data.save=='ok') {
                        is_success = true;
                        c5('收藏成功');
                    }else if(data.save=='error'){
                        c5('收藏失敗');
                    }else if(data.isBlocked){
                        c5('封鎖中無法收藏');
                    }else if(data.isFav){
                        is_success = true;
                        c5('已在收藏名單中');
                    }
                    if(is_success) {
                        $(".favIcon span").text('移除收藏');
                        $(".favIcon img").attr('src','/new/images/icon_08_.png');
                        $(".favIcon").removeClass('addFav').addClass('removeFav');
                    }                                      
                });
            }else{
                c5('不可收藏自己');
            }

        }

        function removeFav(){
            var uid='{{ $user->id }}';
            var to='{{$to->id}}';
            $.post('{{ route('fav/remove_ajax') }}', {
                userId: uid,
                favUserId: to,
                _token: '{{ csrf_token() }}'
            }, function (data) {
                if(data.status==true) {
                    c5('移除成功');
                    $(".favIcon span").text('收藏');
                    $(".favIcon img").attr('src','/new/images/icon_08.png');
                    $(".favIcon").removeClass('removeFav').addClass('addFav');
                }else{
                    c5('移除失敗');
                }

            });

        }

         $("#msgsnd").on('click', function(){

            $.ajax({
                url: '/dashboard/chat2/{{ Carbon\Carbon::now()->timestamp }}?{{csrf_token()}}={{now()->timestamp}}',
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
        // ccc('{{Session::get('message')}}');
    @elseif(Session::has('message') && Session::get('message')!="此用戶已關閉資料。")
        c5('{{Session::get('message')}}');
        @if(Session::get('message') == '評價已完成')
        popEvaluation()
        @endif
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



    @if(isset($to))
        $('.evaluation').on('click', function() {
			console.log('evaluation')
            event.stopPropagation();
            var on1 = $('.bottub').find('.on1');
            
            if(on1.length) {
                on1.removeClass('on1');
                $('.bottub').find('.showslide').fadeOut();
            }
            if ($(this).hasClass('on2')) {
                $(this).removeClass('on2');
                $('.showslide_evaluation').fadeOut();
            } else {
                @if($user->id == $to->id)
                    c5('不可對自己評價');
                @elseif($user->meta->isWarned() == 1 || $isAdminWarned)
                    c5('您目前為警示帳戶，暫不可評價');
                @elseif ($is_banned_v2)
                    c5('您目前為封鎖帳戶，暫不可評價');
                @else
                    $(this).addClass('on2');
                    $('.showslide_evaluation').fadeIn();
                @endif
            }
        });
        $('.click_me').on('click', function() {
            event.stopPropagation();
            @if($user->id == $to->id)
                c5('不可對自己評價');
            @else
                if($('.showslide_evaluation2').css('display')=='block'){
                    $('.showslide_evaluation2').fadeOut();
                }else{
                    $('.showslide_evaluation2').fadeIn();
                }
            @endif
        });

        // 本人評價
        $('.myself_evaluation').click(function() {

            @if(!isset($evaluation_self))
                // $('#tab_evaluation').show();
                // $(".announce_bg").show();
                // $('body').css("overflow", "hidden");
            @else
                c5('您好，對於 {{$to->name}} 您於 {{\Carbon\Carbon::parse($evaluation_self->created_at)->format("Y-m-d")}}已經有過評價，不能重複評價哦!');
                return false;
            @endif
            @if($too_soon_evaluation)
                c5('您好，系統限制30分鐘之內只能給出一個評價');
                return false;
            
            @else
                $('.alert_tip').text('');
                $('.self_illustrate').find('input[name="agree"]').prop('checked', false); // 清除偽裝的犯罪現場
                resetImageUploader(document.querySelector('#form1'));

                $('.vipDays').addClass('hide');
                $('.phone_auth').addClass('hide');
                $('.need_vip').addClass('hide');
                $('.advance_auth').addClass('hide');
                $('.enter_tab_evaluation').removeClass('evaluation_type_anonymous');
                $('.enter_tab_evaluation').removeClass('evaluation_type_myself');
                $('.anonymous_illustrate').hide();
                $('.self_illustrate').show();
                
                $('input[name=content_processing_method]').val('');
                @if($user->engroup==2)
                    $('#tab_reject_female').show();
                    $('.advance_auth').removeClass('hide');
                    $(".announce_bg").show();
                    $('.enter_tab_evaluation').addClass('evaluation_type_myself');
                    @if($auth_check>0)
                        $('.enter_tab_evaluation').show();
                    @else
                        $('.enter_tab_evaluation').hide();
                    @endif
                @elseif($user->engroup==1)
                    $('#tab_reject_male').show();
                    $('.vipDays').removeClass('hide');
                    $(".announce_bg").show();
                    $('.enter_tab_evaluation').addClass('evaluation_type_myself');
                @endif
            @endif
        });

        // 匿名評價
        $('.anonymous_evaluation').click(function() {
            @if(($evaluation_anonymous??null))
                c5('您好，對於 {{$to->name}} 您於 {{\Carbon\Carbon::parse($evaluation_anonymous->created_at)->format("Y-m-d")}}已經有過匿名評價，不能重複評價哦!');
                return false;
            @elseif($too_soon_evaluation)
                c5('您好，系統限制30分鐘之內只能給出一個評價');
                return false;
            @else
                // 首先清除狀態
                $('#evaluation_description').find('input[name="message_processing"]').prop('checked', false);
                $('#evaluation_description').find('.evaluation_check_alert_tip').text('');
                $('.alert_tip').text('');
                resetImageUploader(document.querySelector('#form1'));

                $('.vipDays').addClass('hide');
                $('.phone_auth').addClass('hide');
                $('.need_vip').addClass('hide');
                $('.advance_auth').addClass('hide');
                $('.enter_tab_evaluation').removeClass('evaluation_type_anonymous');
                $('.enter_tab_evaluation').removeClass('evaluation_type_myself');
                
                @if($user->engroup==2)
                    $('#tab_reject_female').show();
                    //$('.new_tkfont').text('您目前未達匿名評價標準，無法使用');
                    $('.advance_auth').removeClass('hide');
                    $(".announce_bg").show();
                    $('.enter_tab_evaluation').addClass('evaluation_type_anonymous');
                    @if($advance_auth_status)
                        $('.enter_tab_evaluation').show();
                    @else
                        $('.enter_tab_evaluation').hide();
                    @endif
                @elseif($user->engroup==1)
                    $('#tab_reject_male').show();
                    //$('.new_tkfont').text('您目前未達匿名評價標準，無法使用');
                    $('.need_vip').removeClass('hide');
                    $(".announce_bg").show();
                    $('.enter_tab_evaluation').addClass('evaluation_type_anonymous');
                @else
                    // 訊息處理選擇
                    // $('#evaluation_description').show();
                    // $(".announce_bg").show();
                @endif
            @endif
        });

        // 處理匿名評價說明
        (function () {
            const $dialog = $('#evaluation_description');

            // 這邊我們處理 checkbox 的二選一切換
            $dialog.find('input[name="message_processing"]').click((event) => {
                $dialog.find('input[name="message_processing"]')
                    .not(event.target)
                    .prop('checked', false);

                $(event.target).prop('checked', true);
            });

            // 這邊我們處理送出按鈕的動作
            $dialog.find('.evaluation_check').click((event) => {
                let value = $dialog.find('input[name="message_processing"]:checked').val();

                if (!value) {
                    $dialog.find('.evaluation_check_alert_tip').text('上面兩個二選一');

                    return false;
                }

                $('input[name=content_processing_method]').val(value);
                $('#evaluation_description').hide();
                $('#tab_evaluation').show();
                $('.anonymous_illustrate').show();
                $('.self_illustrate').hide();
                $('.self_illustrate').find('input[name="agree"]').prop('checked', true); // 偽裝成勾選讓表單驗證通過
                $(".announce_bg").show();
                $('body').css("overflow", "hidden");
            });
        })();
    @endif


    // function form_re_content_submit(){
    //     if($.trim($(".hf_i").val())=='') {
    //         c5('請輸入內容');
    //     }else{
    //         $('#form_re_content').submit();
    //     }
    // }


    $(window).resize(function() {
        $('div.context-wrap').each(function(i) {
            var more_elt = $(this).parents('.hu_p').find('span.z_more');
            var p_elt = $(this).parents('.hu_p').find('ul.zap_photo');
            if (isEllipsisActive(this)) {
                more_elt.removeClass('hide_more');
                more_elt.removeClass('show_more');
                more_elt.addClass('show_more');
            }
            else {
                more_elt.removeClass('show_more');
                more_elt.removeClass('hide_more');
                more_elt.addClass('hide_more');
            }
            
            if(more_elt.hasClass('hide_more')) {
                if (p_elt.length>0 && isEllipsisActive(p_elt)) {
                    more_elt.removeClass('hide_more');
                    more_elt.removeClass('show_more');
                    more_elt.addClass('show_more');
                }
                else {
                    more_elt.removeClass('show_more');
                    more_elt.removeClass('hide_more');
                    more_elt.addClass('hide_more');
                }                            
            }            
        });
    });
    
    $('div.context-wrap').each(function(i) {
        var more_elt = $(this).parents('.hu_p').find('span.z_more');
        var p_elt = $(this).parents('.hu_p').find('ul.zap_photo');
        if (isEllipsisActive(this)) {
            more_elt.removeClass('hide_more');
            more_elt.removeClass('show_more');
            more_elt.addClass('show_more');
        }
        else {
            more_elt.removeClass('show_more');
            more_elt.removeClass('hide_more');
            more_elt.addClass('hide_more');
        }
        
        if(more_elt.hasClass('hide_more')) {
            if (p_elt.length>0 && isEllipsisActive(p_elt)) {
                more_elt.removeClass('hide_more');
                more_elt.removeClass('show_more');
                more_elt.addClass('show_more');
            }
            else {
                more_elt.removeClass('show_more');
                more_elt.removeClass('hide_more');
                more_elt.addClass('hide_more');
            }                            
        }            
    });    

    $('.many-txt').each(function(i) {
        var but_elt = $(this).parents('.con').find('.al_but');
        var photo_elt = $(this).parents('.con').find('.zap_photo');         
        if (isEllipsisActive(this)) {
            but_elt.removeClass('hide_more');
            but_elt.removeClass('show_more');

            but_elt.addClass('show_more');
        }
        else {
            but_elt.removeClass('hide_more');
            but_elt.removeClass('show_more');

            but_elt.addClass('hide_more');
        }
        
        if(but_elt.hasClass('hide_more')) {
            if (photo_elt.length>0 && isEllipsisActive(photo_elt)) {
                but_elt.removeClass('hide_more');
                but_elt.removeClass('show_more');

                but_elt.addClass('show_more');                                    
            }
            else {
                but_elt.removeClass('hide_more');
                but_elt.removeClass('show_more');

                but_elt.addClass('hide_more');
            }                                
        }        
    });

    $(window).resize(function() {
        $('.many-txt').each(function(i) {
            var but_elt = $(this).parents('.con').find('.al_but');
            var photo_elt = $(this).parents('.con').find('.zap_photo');            
            if (isEllipsisActive(this)) {
                but_elt.removeClass('hide_more');
                but_elt.removeClass('show_more');

                but_elt.addClass('show_more');
            }
            else {
                but_elt.removeClass('hide_more');
                but_elt.removeClass('show_more');

                but_elt.addClass('hide_more');
            }
            
            if(but_elt.hasClass('hide_more')) {
                if (photo_elt.length>0 && isEllipsisActive(photo_elt)) {
                    but_elt.removeClass('hide_more');
                    but_elt.removeClass('show_more');

                    but_elt.addClass('show_more');                                    
                }
                else {
                    but_elt.removeClass('hide_more');
                    but_elt.removeClass('show_more');

                    but_elt.addClass('hide_more');
                }                                
            }            
        });
    });

    $(".al_but").on("click", function() {
        if ($(this).hasClass("active")) {
            $(this).text("[完整評價]");
            $(this).removeClass("active");
        } else {
            $(this).text("[點擊收起]");
            $(this).addClass("active");
        }
        return false;
    });
	});


    
    //解衝突，排除mobile無法作用的問題
    // jQuery.noConflict();
    </script>
<script type="application/javascript">
    $(document).on('click', '.toggleBlockMid', function() {
            //do stuff
            if ( $('#plshow').is(':visible') ){
                $('#plshow').hide();
                $('.hzk').find('img').attr("src","/new/images/zk_icon.png");
                $('.hzk').find('h2').text('部分被封鎖的會員評價已經被隱藏，點此全部顯示');
            }else{
                $('#plshow').show(function(){
                    $('div.context-wrap').each(function(i) {
                        var more_elt = $(this).parents('.hu_p').find('span.z_more');
                        var p_elt = $(this).parents('.hu_p').find('ul.zap_photo');
                        if (isEllipsisActive(this)) {
                            more_elt.removeClass('hide_more');
                            more_elt.removeClass('show_more');
                            more_elt.addClass('show_more');
                        }
                        else {
                            more_elt.removeClass('show_more');
                            more_elt.removeClass('hide_more');
                            more_elt.addClass('hide_more');
                        }
                        
                        if(more_elt.hasClass('hide_more')) {
                            if (p_elt.length>0 && isEllipsisActive(p_elt)) {
                                more_elt.removeClass('hide_more');
                                more_elt.removeClass('show_more');
                                more_elt.addClass('show_more');
                            }
                            else {
                                more_elt.removeClass('show_more');
                                more_elt.removeClass('hide_more');
                                more_elt.addClass('hide_more');
                            }                            
                        }
                                               
                    }); 

                    $('.many-txt').each(function(i) {
                        var but_elt = $(this).parents('.con').find('.al_but');
                        var photo_elt = $(this).parents('.con').find('.zap_photo');
                        if (isEllipsisActive(this)) {
                            but_elt.removeClass('hide_more');
                            but_elt.removeClass('show_more');

                            but_elt.addClass('show_more');
                        }
                        else {
                            but_elt.removeClass('hide_more');
                            but_elt.removeClass('show_more');

                            but_elt.addClass('hide_more');
                        }
                        
                        if(but_elt.hasClass('hide_more')) {
                            if (photo_elt.length>0 && isEllipsisActive(photo_elt)) {
                                but_elt.removeClass('hide_more');
                                but_elt.removeClass('show_more');

                                but_elt.addClass('show_more');                                    
                            }
                            else {
                                but_elt.removeClass('hide_more');
                                but_elt.removeClass('show_more');

                                but_elt.addClass('hide_more');
                            }                                
                        }
                    });                     
                });
                $('.hzk').find('img').attr("src","/new/images/zk_iconup.png");
                $('.hzk').find('h2').text('收起');


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
            limit: 15,
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
            $('#content').val('');
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
        if(i>9) {
            nthText = '';
            let i_str_arr = i.toString().split('');
            nthText+= rendorItemNthText.nthEnum[9];
            nthText+= rendorItemNthText.nthEnum[i_str_arr[1]];
        }
        this.setAttribute('data-nth-text', `第${nthText}張`);
    });
}

rendorItemNthText.nthEnum = '一二三四五六七八九十'.split('');
</script>

<link type="text/css" rel="stylesheet" href="/new/css/app.css">
<link rel="stylesheet" type="text/css" href="/new/css/swiper2.min.css"/>
<script type="text/javascript" src="/new/js/swiper.min.js"></script>
<script type="application/javascript">
    $(document).ready(function () {
        /*调起大图 S*/
        var mySwiper = new Swiper('.swiper-container2',{
            pagination : '.swiper-pagination2',
            paginationClickable:true,
            onInit: function(swiper){//Swiper初始化了
                // var total = swiper.bullets.length;
                var active =swiper.activeIndex;
                $(".swiper-num .active").text(active);
                // $(".swiper-num .total").text(total);
            },
            onSlideChangeEnd: function(swiper){
                var active =swiper.realIndex +1;
                $(".swiper-num .active").text(active);
            }
        });
        $(".userPicList img").on("click",
            function () {
                var imgBox = $(this).closest(".userPicList").find(".swiper-slide:not(.swiper-slide-duplicate) img");
                var imgBox_length = imgBox.length;
                var i = $(imgBox).index(this);

                $(".big_img .swiper-wrapper").html("")
                for (var j = 0, c = imgBox_length; j < c ; j++) {
                    var imgSrc = imgBox.eq(j).attr("src");
                    var isBlur = imgBox.eq(j).closest(".swiper-slide").hasClass("blur_img");
                    var cellClass = isBlur ? "cell blur_img" : "cell";
                    $(".big_img .swiper-wrapper").append('<div class="swiper-slide"><div class="' + cellClass + '"><img src="' + imgSrc + '" /></div></div>');
                }
                mySwiper.updateSlidesSize();
                mySwiper.updatePagination();
                $(".big_img").css({
                    "z-index": 1001,
                    "opacity": "1"
                });
                //分页器
                var num = $(".swiper-pagination2 span").length;
                $(".swiper-num .total").text(num);
                $(".swiper-num .active").text(i + 1);

                mySwiper.slideTo(i, 0, false);
                return false;
            });
        $(".zap_photo li").on("click",
            function () {
                var imgBox = $(this).parent(".zap_photo").find("li");
                var i = $(imgBox).index(this);
                $(".big_img .swiper-wrapper").html("")

                for (var j = 0, c = imgBox.length; j < c ; j++) {
                    $(".big_img .swiper-wrapper").append('<div class="swiper-slide"><div class="cell"><img src="' + imgBox.eq(j).find("img").attr("src") + '" / ></div></div>');
                }
                mySwiper.updateSlidesSize();
                mySwiper.updatePagination();
                $(".big_img").css({
                    "z-index": 1001,
                    "opacity": "1"
                });
                //分页器
                var num = $(".swiper-pagination2 span").length;
                $(".swiper-num .total").text(num);
                // var active =$(".swiper-pagination2").index(".swiper-pagination-bullet-active");
                $(".swiper-num .active").text(i + 1);
                // console.log(active)

                mySwiper.slideTo(i, 0, false);
                return false;
            });
        $(".swiper-container2").click(function(){
            $(this).parent(".big_img").css({
                "z-index": "-1",
                "opacity": "0"
            });
        });
        
        @if($user->isVip() || $user->isVVIP())
        $('.he_tkcn .skip_search').click(function () { toogelSearchDiscard('{{$to->id}}',$(this));})
        
        function toogelSearchDiscard(id,qelt,recall=false) {
            if(id==null || id==undefined) return
            if(qelt==null || qelt==undefined) {
                qelt = $('.he_tkcn .skip_search');
            }
            var key_elt = qelt.find('span');
            var url = '';
            var type='';

            var org_str = key_elt.html();
            if(org_str=='略過') {
                url = "{!!url('/dashboard/search_discard/add') !!}?{{csrf_token()}}={{now()->timestamp}}";
                type="post";
                key_elt.html('解除略過');
            }
            else {
                if(!recall) {
                    $('#tab08 .n_bbutton .n_left').off('click',"**");
                    $(document).on('click','#tab08 .n_bbutton .n_left', { id: id, qelt:qelt},function(e){
                        toogelSearchDiscard(e.data.id,e.data.qelt,true);
                        gmBtnNoReload();            
                    });                     
                    c8('確定要解除略過嗎？');
                    return false;
                }
                
                url = "{!!url('/dashboard/search_discard/del') !!}?{{csrf_token()}}={{now()->timestamp}}";
                type="get";
                key_elt.html('略過');
            }
            
            $.ajax({
              type: type,
              url: url,
              data:{ target:id,_token: '{{ csrf_token() }}'},
              success:function(data) {                  
                if(!data || data=='0' || data==undefined || data==null || pick_real_error(data).length>0) {
                    
                    ccc(org_str+'失敗，請重新操作');
                    key_elt.html(org_str);
                }
                else if(data.length>500){
                    ccc(org_str+'異常，請重新操作');
                }
                else {
                    if(org_str=='略過') {
                        c5('略過成功');
                    }
                    else {
                        c5('已解除略過');
                    }
                }
              },
              error:function() {
                  
                  ccc(org_str+'失敗，請重新操作');
                  key_elt.html(org_str);
              }
            });
        } 
        @endif

    });
    /*调起大图 E*/
</script>
<script type="application/javascript">
	$(document).ready(function () {
		$('.userlogo').click(function() {
			event.stopPropagation()
            var on2 = $('.bottub').find('.on2');
            if(on2.length) {
                on2.removeClass('on2');
                $('.bottub').find('.showslide_evaluation').fadeOut();
            }
			if($(this).hasClass('on1')) {
				$(this).removeClass('on1')
				$('.showslide').fadeOut()
			} else {
				$(this).addClass('on1')
				$('.fadeinboxs').fadeIn()
				$('.showslide').fadeIn()
			}
		})
		$('body').click(function() {
			$('.userlogo').removeClass('on1')
			$('.showslide').fadeOut()
            $('.evaluation').removeClass('on2')
            $('.showslide_evaluation').fadeOut()
            $('.showslide_evaluation2').removeClass('on3')
            $('.showslide_evaluation2').fadeOut()
		})
	})
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

    function show_tab_evaluation(){
        @if($user->id == $to->id)
            c5('不可對自己評價');
        @endif

        var type='';
        if($('.enter_tab_evaluation').hasClass('evaluation_type_myself')){
            type='myself';
        }else{
            type='anonymous';
        }

        $('#tab_reject_male, #tab_reject_female').hide();
        $(".announce_bg").show();
        $('body').css("overflow", "hidden");
        if(type=='anonymous'){
            $('#evaluation_description').show();
        }else{
            $('#tab_evaluation').show();
        }
    }

    function tab_cancel(){
        $('#tab_reject_male, #tab_reject_female').hide();
        $(".announce_bg").hide();
        $('body').css("overflow", "auto");
    }
    

    function massage_user_note(sid){
        let massage_user_note_content = $('#massage_user_note_' + sid).val();
        $.post('{{ route('messageUserNoteAJAX') }}', {
            user_id: '{{ $user->id }}',
            target_id: sid,
            massage_user_note_content: massage_user_note_content,
            _token: '{{ csrf_token() }}'
        }, function (data) {
            c5('備註已更新');
        });
        return false;
    }

</script>

<script type="text/javascript">
    $(function() {
        $(".system dd").hide();
        $(".system dt").click(function() {

        });
    });

    $('.system dt').click(function(e) {
        $(this).toggleClass('on');
        $(this).next('dd').slideToggle();
    });

    $( document ).ready(function() {

        var position ='{{ session()->get('viewuser_page_position')}}';
        if(position !=''){
            if (position.indexOf("messageBoard") >= 0){
                $('.hypbg').addClass('on');
                $('#showMoreMsg').show();
            }
            $("html,body").animate({scrollTop: $('#'+position).offset().top}, 1000);
        }

        var evaluate_pagination='{{Request()->has('page')}}';
        if(evaluate_pagination){
            $('.toggleBlockMid').click();
            $("#hash_evaluation .hypingjia").addClass('on');
            $("#hash_evaluation .xiliao_input").show();
            $("html,body").animate({scrollTop: $("#hash_evaluation").offset().top}, 1000);
        }

    });
</script>
@stop
