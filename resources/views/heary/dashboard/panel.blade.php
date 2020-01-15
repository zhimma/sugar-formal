<div class="zlfont">
<img src="{{ $user->meta_()->pic }}" onerror="this.src=&#39;/img/male-avatar.png&#39;" class="zlfont_img">
<span>@if (str_contains(url()->current(), 'dashboard')) {{ $user->name }} @elseif (isset($cur)) {{ $cur->name }} @endif @if (((isset($cur) && $cur->isVip() && $cur->engroup == '1')) || ($user->isVip() && str_contains(url()->current(), 'dashboard'))) (VIP) <img src="/images/05.png" class="weui-v_t">@endif</span>
</div>
<div class="zllist">
<!--   class="zllist_on"  <i></i>  -->
<a href="{!! url('dashboard') !!}" class="info"><span>個人資料修改</span></a>
<a href="{!! url('dashboard/search') !!}" class="search"><span>搜尋</span></a>
<a href="{!! url('dashboard/chat2/'.csrf_token().\Carbon\Carbon::now()->timestamp) !!}"><span>收件夾</span>
<span class="m-nav__link-badge">
<span class="m-badge m-badge--danger">{{ \App\Models\Message::unread($user->id) }}</span>
</span>
</a>
<a href="/dashboard/history" class="footprint"><span>足跡</span></a>
<a href="/dashboard/board" class="board"><span>留言板</span></a>
<a href="/dashboard/cancel" class="cancel"><span>取消 VIP</span></a>
<a href="/dashboard/fav" class="favorite zllist_on"><i></i><span>收藏</span></a>
<a href="/dashboard/block" class="block"><span>我的封鎖名單</span></a>
</div>
