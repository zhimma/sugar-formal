


@include('partials.newheader')
<body class="mainbg">
	<style type="text/css">
		a{    color: #333!important;}
	</style>
    <div class="infoheader">
        <div class="weui-pt30 weui-pb30 container">
            @if(Auth::user())
<nav class="navbar navbar-default" role="navigation">
    <div>
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#example-navbar-collapse">
                <span class="sr-only">切换导航</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class=" weui-fl logo" href="#"><img src="/images/logo.png"></a>
        </div>
        


        <div class="collapse navbar-collapse weui-fr" id="example-navbar-collapse">
              <ul class="nav navbar-nav navbar-nav01 weui-f16">
                <li><a href="{!! url('') !!}">首頁</a></li>
                <li><a href="{!! url('dashboard/search') !!}">搜索</a></li>
                <li><a href="{!! url('dashboard') !!}">個人資料</a></li>
                <li><a href="{!! url('dashboard/chat') !!}">收信夾</a></li>
                <li><a href="{!! url('dashboard/board') !!}">留言板</a></li>
                @if ($user->isVip())
                <li><a href="{!! url('dashboard/history') !!}">足跡</a></li>
                <li><a href="{!! url('dashboard/fav') !!}">我的收藏</a></li>
                @endif
                @if (!$user->isVip())
                <li><a href="{!! url('dashboard/upgrade') !!}">升級VIP</a></li>
                @else
                <li><a href="{!! url('dashboard/cancel') !!}">取消VIP</a></li>
                @endif
            </ul>
            <div class="weui-fr weui-f16 weui-pt15">
                <a  href="{!! url('dashboard/chat') !!}" class="weui-dnb weui-pl20 ">
                    <img src="/images/gerenzhongxin_09.png"> <span class="weui-v_m weui-pl5">消息</span>
                    <span class="badge badge_red">{{ \App\Models\Message::unread($user->id) }}</span>
                </a>
                <a href="{!! url('dashboard') !!}" class="weui-dnb weui-pl20 ">
                    <img src="/images/gerenzhongxin_06.png"> <span class="weui-v_m weui-pl5">我的</span>
                </a>
                <!-- <a href="{!! url('logout') !!}" class="weui-dnb weui-pl20 ">
                    <img src="/images/shouye_06.png" > <span class="weui-v_m weui-pl5">Logout</span> 
                </a> -->
            </div>
        </div>

    </div>
</nav>
@else


<nav class="navbar navbar-default" role="navigation">
    <div>
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#example-navbar-collapse">
                <span class="sr-only">切换导航</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class=" weui-fl logo" href="#"><img src="/images/logo.png"></a>
        </div>
        <div class="collapse navbar-collapse weui-fr" id="example-navbar-collapse">
            <ul class="nav navbar-nav weui-f16">
                <li><a href="{!! url('register') !!}"><img src="/images/shouye_11.png"> <span class="weui-v_m weui-pl10">注册</span></a></li>
                <li><a href="{!! url('login') !!}"><img src="/images/shouye_06.png"> <span class="weui-v_m weui-pl10">登入</span></a></li>
                <li><a href="#"><img src="/images/shouye_08.png"> <span class="weui-v_m weui-pl10"> 台湾</span></a></li>
            </ul>
        </div>
    </div>
</nav>
@endif
        </div>  
    </div>


 <div class="minh2 weui-pb30"> 
        <div class="email zzdh">
            <h2 class=" weui-t_c">站長的話</h2>
            <div class="weui-p20 ">
                 <p>歡迎來到甜心花園，美麗的寶貝們都想找到一個大方多金的 Daddy。網路上，誰都不認識誰，有很多機會，但有更多陷阱。站長跟大家提示最重要的二件事。</p>
                 <p class="weui-pt30">
                 1.最重要的第一守則：不論在任何情況下，一定先拿到費用才開始約會。<br>
在包養網站中，所有詐騙行為第一名就是男方花言巧語哄騙女方先約會，說好約會滿多久以後付款，最後時間快到就失聯。此類詐騙數量占所有詐騙的85%以上，所以記得一定要求先拿零用金。(建議第一次見面請男方使用車馬費邀請)
                 </p>
                 <p class="weui-pt30">
                    2.加入 VIP。<br>
女生加入VIP很簡單，不用付費。只要上傳頭像照+三張生活照。每周上線一次，就可以保持VIP資格。<br>
獲得 VIP 可以看很多男會員的進階資料，對判斷男會員是真的大方Daddy 或者只是胡說八道的騙子很有幫助。
                 </p>
                 <p class="weui-pt30">建議第一次接觸包養的女孩，看完以下文章
                 <br>
                 建議可以進一步參考 <a href="/feature">網站使用</a> / <a href="http://blog-tw.net/Sugar/%E5%8C%85%E9%A4%8A%EF%BC%8D%E5%A4%A7%E5%8F%94%E7%AF%87/" target="_blank">站長的碎碎念(完整版)</a></br>
		                 </p>
                 
                 <!-- <div class="weui-b_t weui-p20 weui-t_c">
                     <span class="weui-v_m weui-red01 weui-pr10">驗證碼已經寄到你的email.</span>
                     <a href="#" class="btn btn-danger weui-f16 weui-box_s btn_m1">重新發送</a>
                 </div> -->
            </div>
        </div>
    
    </div>


 @include('partials.newfooter')
        @include('partials.newscripts')
</body>
</html>
