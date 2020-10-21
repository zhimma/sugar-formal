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
            <h2 class=" weui-t_c">網站使用</h2>
            <div class="weui-p20 ">
                 @if(Auth::check() && isset($user))
        {{-- female --}}
        @if($user->engroup == 2)
            <p>網站使用：</p>
			網站的建設方向是要更有效的過濾出真心包養的Daddy，篩選掉無聊的網蟲。
			很多功能規劃中，如果各位有什麼意見歡迎跟站長聯絡提出。<a href="http://www.sugar-garden.org/contact">(聯絡我們)</a><br>
			<br>
			目前已上線的功能
			<br>
			<font color="red">1：成為VIP</font><br>
			站長強烈建議各位寶貝成為網站VIP，因為VIP可以看到一些男會員很重要的進階資訊，可以幫助判斷男會員是真的大方Daddy 或者只是胡說八道。
			<font color="orange">本站寶貝們要成為VIP不用花錢，上傳一張你的頭像照+3張生活照，只要兩天上線一次就可以擁有VIP權力。</font>
			<br><br>
			<font color="red">2：車馬費邀請</font><br>
			站方設計車馬費制度，為了篩選信口開河邀約的Daddy。
			車馬費制度會由站方先跟Daddy收取一筆1788的車馬費費用。雙方約見。
			<font color="orange">只要當天雙方順利見完面，不論結果如何，站方扣除部分手續費後，會將 1500 匯入各位指定的銀行帳戶。</font>
			或者採用西聯匯款(但西聯匯款有一筆高額匯款手續費，可能由女孩們自行吸收)</p>

        {{-- male --}}
        @elseif($user->engroup == 1)
            <p>網站使用：</p>
			網站的建設方向是要更有效的過濾出真心包養的Daddy，篩選掉無聊的網蟲。
			各位大叔可能不知道，一個女會員註冊，會收到數十封的邀約信。
			漂亮一點的信件數量更是驚人。所以本站的規畫就是盡量凸顯真心包養大叔的經濟優勢。
			目前有更進一步的功能規劃中，如果各位有什麼意見歡迎跟站長聯絡提出。<a href="http://www.sugar-garden.org/contact">(聯絡我們)</a><br>
			<br>
			目前已上線的功能
			<br>
			<font color="red">1：加入VIP</font><br>
			站長建議各位大叔加入VIP，目前VIP的比較重要的功能是可以無限制收發信，開啟已讀功能以及可以看進階的統計資料。未來會規畫更具財力顯示的VIP階級。
			另一方面，VIP算是女方最基本的篩選門檻了，有些女生甚至會關掉普通會員的來信。只看VIP會員的來信。
			<br><br>
			<font color="red">2：車馬費邀請</font><br>
			站方設計車馬費制度，為了篩選信口開河邀約的Daddy。也就增加了真心約見daddy的能見度。
			車馬費制度會由站方先跟Daddy收取一筆1788的車馬費費用。雙方約見。
			<font color="orange">只要當天雙方順利見完面，不論結果如何，站方扣除部分手續費後，會將 1500 匯入女方指定的銀行帳戶。</font>
			當然免不了有些女生會想辦法賺車馬費，網站目前的功能是會以曾經約會的會員可以留言評價(需VIP才能看到)，另一方面網站也會管控銀行帳戶，被太多人投訴的女會員，站方會停權。但站長必須說，無法100%杜絕，所以大家在使用車馬費邀約時，站長只能說這是必要的支出之一。</p></p>
        @endif
    @else
        {{-- no login --}}
        <p>請註冊會員，或者參考<a href="http://blog-tw.net/Sugar/">站長的碎碎念</a></p>
    @endif
            </div>
        </div>
    
    </div>


 @include('partials.newfooter')
        @include('partials.newscripts')
</body>
</html>
