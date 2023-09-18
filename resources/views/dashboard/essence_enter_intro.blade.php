@extends('new.layouts.website')
@section('style')
    <link rel="stylesheet" href="/posts/css/style.css">
    <link rel="stylesheet" href="/posts/css/font/font_n/iconfont.css">
    <link rel="stylesheet" href="/posts/css/font/iconfont.css">
    <link rel="stylesheet" href="/posts/css/taolunqu/iconfont.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400|700" rel="stylesheet">
    <script src="/posts/js/jquery-2.1.1.min.js" type="text/javascript"></script>
    <script src="/posts/js/bootstrap.min.js"></script>

    <style>
        .hycov_down{
            width: 28px;
            height: 28px;
        }

        .wt_txb{ position: relative; }

        .ta_sz{ position: absolute; width:15px; height:15px; color: #fff; border-radius: 100px; display: flex; text-align: center; justify-content: center; align-items: center; right: 0; top:0px; background: #69b9ff; font-size: 12px;}
        .ta_sz_ten{ position: absolute; width:20px; height:20px; color: #fff; border-radius: 100px; display: flex; text-align: center; justify-content: center; align-items: center; right: 0; top:0px; background: #69b9ff; font-size: 12px;}
        .ta_sz_hundred{ position: absolute; width:25px; height:25px; color: #fff; border-radius: 100px; display: flex; text-align: center; justify-content: center; align-items: center; right: 0; top:0px; background: #69b9ff; font-size: 13px;}

        .hycov{ border-radius: 100px;}

    </style>
@endsection
@section('app-content')
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <style>
                .btn_img01>.btn_back{width: 100%;background: url(/posts/images/fanhui02.png) no-repeat 0 0;background-size: cover;}
                .btn_img01{width: 130px;}
            </style>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="shou" style="text-align: center; position: relative;">
                    <a href="/dashboard/forum" class="toug_back btn_img" style=" position: absolute; left:-6px;">
                        <div class="btn_back"></div>
                    </a>
                    <div style="position: absolute; left:45px;">
                        <span>精華文章專區</span>
                        <font>Article</font>
                    </div>
                    <a href="/dashboard/essence_main" class="toug_back btn_img01 userlogo xzgn">
                        <div class="btn_back"><img src="/posts/images/tg_03.png">查看所有文章</div>
                    </a>

                </div>
                <div class="fadeinboxs"></div>

                <!--  -->
                <style>
                    .ailefont {
                        position: absolute;
                        left: 40px; z-index: -10;
                    }

                    @media (max-width:450px) {
                        .ailefont {
                            position: absolute;
                            left: 0px;
                        }
                    }
                    :root {
                        --primary-light: #8abdff;
                        --primary: #6d5dfc;
                        --primary-dark: #5b0eeb;
                        --white: #FFFFFF;
                        --greyLight-1: #E4EBF5;
                        --greyLight-2: #c8d0e7;
                        --greyLight-3: #bec8e4;
                        --greyDark: #9baacf;
                        --fens: #ea5575;
                    }
                </style>



                <div class="bti_tit"><img src="/posts/images/wenz.png" style="height:25px; margin-right: 5px;">精華文徵求以下三類文章</div>


                <div class="tougao_u">
                    <div class="tg_title">
                        <div class="tg_title_nr">
                            <span class="ta_saicon"><img src="/posts/images/tg_07.png"></span>
                            <font class="ta_font">教學經驗文</font>
                            <i class="ta_fshuzi">01</i>
                        </div>
                    </div>
                    <div class="ta_fontlist">
                        <h2>分享男會員/女會員在包養經驗的教學文章</h2>
                        <h2>舉凡如何分辨八大行業，如何分辨網蟲，詐騙等等。都是徵文的對象</h2>
                    </div>
                </div>
                <div class="tougao_u">
                    <div class="tg_title">
                        <div class="tg_title_nr">
                            <span class="ta_saicon"><img src="/posts/images/tg_14.png"></span>
                            <font class="ta_font">包養故事文</font>
                            <i class="ta_fshuzi">02</i>
                        </div>
                    </div>
                    <div class="ta_fontlist">
                        <h2>溫馨的，驚悚的，獵奇的，都歡迎分享</h2>
                    </div>
                </div>
                <div class="tougao_u">
                    <div class="tg_title">
                        <div class="tg_title_nr">
                            <span class="ta_saicon"><img src="/posts/images/tg_20.png"></span>
                            <font class="ta_font">平台經驗/介紹文</font>
                            <i class="ta_fshuzi">03</i>
                        </div>
                    </div>
                    <div class="ta_fontlist">
                        <h2>不一定要本站的，他站間的綜合比較也很歡迎。可以提供站方改版的思路</h2>
                    </div>
                </div>


                <div class="youdian">
                    <div class="tib_line">
                        <span class="yd_buicon"><img src="/posts/images/tg_26.png"></span>
                        <font class="yd_font">優點</font>
                    </div>
                    <div class="ta_fontlist">
                        <div class="yd_text">
                            <h2>
                                <div class="yd_tleft"><span class="yd_tleft_span">1</span></div>
                                <div class="yd_tright">
                                    <b>可以顯示帳號</b>
                                    依照會員設定是否公開寫文者暱稱，換句話說閱讀此文章的會員可以直接發訊給發文者交流。(限異性)
                                </div>
                            </h2>
                            <h2>
                                <div class="yd_tleft"><span class="yd_tleft_span">2</span></div>
                                <div class="yd_tright">
                                    <b>給予本站一個月 vip</b>
                                </div>
                            </h2>
                            <h2 style="border-bottom: none;">
                                <div class="yd_tleft"><span class="yd_tleft_span">3</span></div>
                                <div class="yd_tright">
                                    <b>3:pr+10(一個月)</b>

                                </div>
                            </h2>
                        </div>
                    </div>
                </div>


                <div class="yd_tgbut">
                    <a href="/dashboard/essence_posts" class="ya_button"><img src="/posts/images/tg_16.png">我要投稿</a>
                    <a href="/dashboard/essence_list?postType=myself" class="ya_button"><img src="/posts/images/tg_22.png">投稿紀錄</a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
    </script>
@endsection
