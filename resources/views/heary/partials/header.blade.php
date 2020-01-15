@if (Auth::user() && Request::path() != '/activate' && Request::path() != '/activate/send-token')
    <?php $user = Auth::user() ?>
    <header class="header headerbg weui-pb10 weui-pt10">
        <nav class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#example-navbar-collapse" aria-expanded="true"> 
                        <span class="sr-only">切换引導列</span> 
                        <span class="icon-bar"></span> 
                        <span class="icon-bar"></span> 
                        <span class="icon-bar"></span> 
                    </button>
                    <a class=" weui-fl weui-pl10 weui-pt5" href="{!! url('') !!}"><img src="/images/homeicon.png"></a>
                </div>
                <div class="navbar-collapse weui-fr collapse show" id="example-navbar-collapse" style="">
                    <div class="weui-fr  weui-white coleft">
                        <div class="conterleft" style="padding-top: 5px;">
                            <a href="/dashboard"><i><img src="/images/male-avatar-01.png" onerror="this.src='images/male-avatar-01.png'"></i><span>個人資料</span></a>
                        </div>
                        <div class="conterleft">
                            <a href="{!! url('dashboard/search') !!}"><i class="icon iconfont icon-sousuo coicon"></i><span>搜尋</span></a>
                        </div>
                        <div class="conterleft">
                            <a href="/dashboard/chat"><i class="icon iconfont icon-shoujianxiang coicon"></i><span>收件夾</span></a>
                        </div>
                        <div class="conterleft">
                            <a onclick="cl()"><i><img src="/images/vip.png"></i><span>VIP</span></a>
                        </div>
                        <div class="conterleft">
                            <a href="/logout"><i class="icon iconfont icon-liuyan coicon"></i><span>登出</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <div class="blbg" onclick="gmBtn1()"></div>
    <div class="bl bl_tab" id="tab01">
        <img src="/images/jt.png" class="jt">
        <div class="bl_icon">
            <li>
                <a href="/dashboard/upgrade_esafe"><img src="/images/icon_12.png">
                    <font>升級VIP</font>
                </a>
            </li>
            <li>
                <a href="/dashboard/history"><img src="/images/icon_13.png">
                    <font>足跡</font>
                </a>
            </li>
            <li>
                <a href="/dashboard/fav"><img src="/images/icon_14.png">
                    <font>收藏</font>
                </a>
            </li>
            <li>
                <a href="/dashboard/block"><img src="/images/icon_16.png">
                    <font>封鎖</font>
                </a>
            </li>
        </div>
        <!--    <a id="" onclick="gmBtn1()" class="bl_gb"><img src="style/gb_icon.png"></a>-->
    </div>

    <script>
        function cl() {
            $(".blbg").show()
            $("#tab01").show()
        }

        function gmBtn1() {
            $(".blbg").hide()
            $(".bl").hide()

        }
    </script>
@else
    <header class="clearfix weui-pl32 weui-pr30 weui-pt20 headerbg weui-pb15">
        <a href="" class="weui-fl weui-ml30 logo"><img src="/img/homeicon.png"></a>
        <ul class="weui-fl navtop weui-ml30">
            <li class="weui-fl"><a href="/contact" class="weui-white">關於我們</a></li>
        </ul>
        <a href="/login" class="weui-fr weui-white weui-mr30 weui-pt10">登入</a> 
        <a href="/register" class="weui-fr weui-white weui-mr30 weui-pt10">註冊</a>
    </header>
@endif