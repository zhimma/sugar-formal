



<div class="collapse navbar-collapse weui-fr" id="example-navbar-collapse">
      <ul class="nav navbar-nav weui-f16">
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
    <div class="weui-fr weui-f16 " style="    padding-top: 8px;">
        <a  href="{!! url('dashboard/chat') !!}" class="weui-dnb weui-pl20 ">
            <img src="images/gerenzhongxin_09.png"> <span class="weui-v_m weui-pl5">消息</span>
            <span class="badge badge_red">{{ \App\Models\Message::unread($user->id) }}</span>
        </a>
        <a href="{!! url('dashboard') !!}" class="weui-dnb weui-pl20 ">
            <img src="images/gerenzhongxin_06.png"> <span class="weui-v_m weui-pl5">我的</span>
        </a>
        <a href="{!! url('logout') !!}" class="weui-dnb weui-pl20 ">
        	<img src="images/shouye_06.png" > <span class="weui-v_m weui-pl5">Logout</span> 
        </a>
    </div>
</div>
            

           