@extends('new.layouts.website')
@section('style')
    <style>
        /* 5-18 */
        .taolunq {width: 94%;margin: 0 auto;border-radius:10px;padding: 15px 0;background-size: 100%;margin-bottom:25px;}
        .taolunq_1{width: 100%; margin: 0 auto; display: table;}
        .tal_w1{width:200px; float: left;background: linear-gradient(to TOP, #87bbe6, #b4e4ff); height: 45px; border-radius: 10px; text-align: center; line-height: 45px;
            position: relative;cursor: pointer;box-shadow: 0 6px 6px rgba(153, 201, 238, 0.6);}
        .wsp{width: 54px; height: 45px;background:url(/new/images/yuan_1.png) no-repeat left top; background-size: 100%; position: absolute; left: 0; top:0 }
        .wsp_r{width: 54px; height: 45px;background:url(/new/images/yuan_2.png) no-repeat top;background-size: 100%;position: absolute; right: 0;top:0}
        .wsp_font1{ font-size: 20px;font-weight: bold; /* color:#3a96ea; */color:#fff;text-shadow:0 3px 8px rgb(0 104 190 / 80%);}
        .wsp_font2{ font-size: 20px;font-weight: bold;/* color:#ff718d; */color:#fff;text-shadow:0 3px 8px rgb(255 71 110 / 90%);}
        .wsp_font3{ font-size: 20px;font-weight: bold;/* color:#ffa3b5; */color:#fff;text-shadow:0 3px 8px rgb(255 71 110 / 90%);}
        .tal_w2{width:200px; float: left;background: linear-gradient(to TOP, #ff8fa5, #ffe1eb); height: 45px; border-radius: 10px; text-align: center; line-height: 45px;
            position: relative;cursor: pointer;box-shadow: 0 6px 6px rgba(255, 37, 81, 0.3);}
        .tal_w3{width:200px; float: left;background: linear-gradient(to TOP, #ffb8c6, #fff1f6); height: 45px; border-radius: 10px; text-align: center; line-height: 45px;
            position: relative;cursor: pointer;box-shadow: 0 6px 6px rgba(255, 37, 81, 0.2);}
        .ta_mar30{ margin-left: 15px; margin-right:15px;}
        .tal_w1:active {box-shadow: 0 0px 20px rgba(173,194,213,0.5);}
        .tal_w2:active {box-shadow: 0 0px 20px rgba(173,194,213,0.5);}
        .tal_w3:active {box-shadow: 0 0px 20px rgba(173,194,213,0.5);}

        @media (max-width: 912px) {
            .tal_w1{width:26%;}
            .tal_w2{width:38%;}
            .tal_w3{width:32%;}
            .ta_mar30{ margin-left: 2%; margin-right:2%;}
            .wsp_font1{ font-size: 18px;font-weight: bold;}
            .wsp_font2{ font-size: 18px;font-weight: bold;}
            .wsp_font3{ font-size: 18px;font-weight: bold;}
        }
        @media (max-width: 360px) {
            .wsp_font1{ font-size: 16px;font-weight: bold;}
            .wsp_font2{ font-size: 16px;font-weight: bold;}
            .wsp_font3{ font-size: 16px;font-weight: bold;}
        }

        .talist{width: 100%; display: table; margin-top: 20px;}
        .talist li{width: 48%; float: left; border-bottom: #ececec solid 1px; padding-bottom: 15px; margin-bottom: 20px;}
        .talist li:nth-child(even){ float: right;}

        .ta_img{width: 100%; height: 200px;justify-content: center;align-items: center;overflow: hidden; display: flex; border-radius: 20px; box-sizing: 2px 2px 2px rgba(0,0,0,0.1);
            background: #eee; position: relative; cursor: pointer;}
        .ta_img img{width: 100%;}
        .ta_img:active{box-shadow: 0 0px 20px rgba(173,194,213,0.7);}

        .ta_bof{ position: absolute; left: 0;width: 100%; top: 70px;}
        .ta_bof img{ margin: 0 auto; display: table; width: 60px; height: 60px; border-radius: 100px; cursor: pointer;}
        .ta_bof img:active {box-shadow: 0 0px 20px rgba(283,84,114,0.5) inset;}
        .ta_font01{font-size: 18px; line-height: 35px; overflow: hidden;text-overflow: ellipsis;display: -webkit-box;
            -webkit-line-clamp:1;-webkit-box-orient: vertical; height: 35px;width: 100%; margin-top: 6px;}
        .ta_font02{font-size: 15px; line-height: 30px; overflow: hidden; color: #999;}
        .ta_font02 span{ float: left;}
        .ta_yuan{width: 5px; height: 5px; background: #999; border-radius: 100px; display: table; margin-top: 13px; margin-left: 6px; margin-right: 6px;}

        @media (max-width: 768px) {
            .ta_img{width: 100%; height: 200px;}
            .talist li{width: 100%; float: left;}
        }
    </style>
    <style>
        .video-wrapper {
            position: relative;
            max-width: 640px;
            margin: 20px auto;
        }

        .video-wrapper iframe {
            width: 100%;
            height: 360px;
        }

        .play-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80px;
            height: 80px;
            background-color: rgba(0, 0, 0, 0.6);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        .play-button::before {
            content: "\f04b";
            font-family: FontAwesome;
            font-size: 40px;
            color: #fff;
        }

        .views-count {
            text-align: center;
            margin-top: 10px;
            font-size: 16px;
        }
    </style>
    <style>
        video {
            height: auto;
            width: 100%;
            object-fit: fill;
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
                <div class="shou">
                    <span>精華文章專區</span>
                    <font>Article</font>
                </div>
                <div class="fadeinboxs"></div>
                <div class="taolunq">
                    <div class="taolunq_1">
                        <a href="/dashboard/essence_list?s=admin">
                            <div class="tal_w1"><span class="wsp"></span><span class="wsp_r"></span><span class="wsp_font1">站長文章</span></div>
                        </a>
                        {{--<div class="tal_w2 ta_mar30"><span class="wsp"></span><span class="wsp_r"></span><span class="wsp_font2">優選會員文章</span></div>--}}
                        <a href="/dashboard/essence_list?s=normal">
                            <div class="tal_w3 ta_mar30"><span class="wsp"></span><span class="wsp_r"></span><span class="wsp_font3">一般會員文章</span></div>
                        </a>
                    </div>
                    <div class="talist">
                        <li>
                            <div class="ta_img">
                                <div class="video-wrapper">
                                    <video id="video" src="/video/essence_01.mp4" controls  poster="/video/essence_01.png"></video>
                                    {{--<div class="play-button"></div>--}}
                                </div>
                            </div>
                            <div class="ta_font01">渣男速速退散！一秒變身「閃雷」甜心寶貝～</div>
                            <div class="ta_font02"><span>May 31</span>{{--<span class="ta_yuan"></span><span>1k view</span>--}}</div>
                        </li>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
    </script>
@endsection
