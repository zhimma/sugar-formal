@if(Session::has('original_user'))
    <li class="m-nav__item m-dropdown m-dropdown--large m-dropdown--arrow m-dropdown--align-center m-dropdown--mobile-full-width m-dropdown--skin-light	m-list-search m-list-search--skin-light"
        id="m_quicksearch">
        <a href="{{ route('escape') }}" class="m-nav__link m-dropdown__toggle">
            回到原使用者
        </a>
    </li>
@endif
<li class="m-nav__item m-dropdown m-dropdown--large m-dropdown--arrow m-dropdown--align-center m-dropdown--mobile-full-width m-dropdown--skin-light	m-list-search m-list-search--skin-light"
    id="m_quicksearch">
    <a href="{!! url('dashboard/search') !!}" class="m-nav__link m-dropdown__toggle">
		<span class="m-nav__link-icon">
			<i class="flaticon-search-1"></i>
            <!-- <img src="/img/sugar_0926-07.png" alt="" width="23" height="23"> -->
		</span>
        <!-- <img src="/img/sugar_0926-07.png" alt="">搜尋 -->
    </a>
</li>

<li class="m-nav__item m-topbar__notifications m-topbar__notifications--img m-dropdown m-dropdown--large m-dropdown--header-bg-fill m-dropdown--arrow m-dropdown--align-center	m-dropdown--mobile-full-width">
    <a href="{!! url('dashboard/chat') !!}" class="m-nav__link m-dropdown__toggle" id="m_topbar_notification_icon">
        <span class="m-nav__link-badge"><span
                    class="m-badge m-badge--danger">@if(!$user->isVip()) @if(\App\Models\Message::unread($user->id) >= 10) {{ \App\Models\Message::unread($user->id)-10 }}  @else {{ \App\Models\Message::unread($user->id) }} @endif
                /{{ \App\Models\Message::unread($user->id) }} @else{{ \App\Models\Message::unread($user->id) }}@endif</span></span>
        <span class="m-nav__link-icon">
			<i class="flaticon-chat-1"></i>
		</span>
    </a>
</li>
@if(!$user->isVip())
    <li class="m-nav__item m-topbar__notifications m-topbar__notifications--img m-dropdown m-dropdown--large m-dropdown--header-bg-fill m-dropdown--arrow m-dropdown--align-center	m-dropdown--mobile-full-width" style="padding: 0 0 0 0; margin: 0 0 0 -8px">
        <a href="#" class="m-nav__link notice__toggle" id="notVIP">
            <img src="/img/question.png" class="question" style="padding: 14px 0 -10px 0; width: 20px">
        </a>
        <div class="notice m-dropdown__wrapper m-dropdown__wrapper_q">
            <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust m-dropdown__arrow_q"></span>
            <div class="m-dropdown__inner">
                <div class="m-dropdown__body">
                    <div class="m-dropdown__content">
                        男生版
                        非 VIP 會員最多一次跟十位會員通信，加入 VIP 即可解除此限制。

                        女生版
                        非 VIP 會員最多一次跟十位會員通信，只要常常上線就可以獲得 VIP 權限。
                    </div>
                    <div class="buttons">
                        <a href="" class="btn btn-success">確定</a>
                        <a href="" class="btn btn-warning">不再通知</a>
                    </div>
                </div>
            </div>
        </div>
    </li>
@endif
<li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light"
    data-dropdown-toggle="click">
    <a href="#" class="m-nav__link m-dropdown__toggle">
		<span class="m-nav__link-icon">
	        <i class="flaticon-user"></i>
		</span>
        <span class="m-topbar__username m--hide">
		{{ $user->name }} @if ($user->isVip()) (VIP) @endif
		</span>
    </a>
    <div class="m-dropdown__wrapper">
        <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
        <div class="m-dropdown__inner">
            <div class="m-dropdown__header m--align-center">
                <div class="m-card-user m-card-user--skin-dark">
                    <div class="m-card-user__pic">
                        <img src="" class="m--img-rounded m--marginless" alt=""/>
                    </div>
                    <div class="m-card-user__details">
						<span class="m-card-user__name m--font-weight-500">
	                        {{ $user->name }}
						</span>
                    </div>
                </div>
            </div>
            <div class="m-dropdown__body">
                <div class="m-dropdown__content">
                    <ul class="m-nav m-nav--skin-light">
                        <li class="m-nav__section m--hide">
							<span class="m-nav__section-text">
							</span>
                        </li>
                        <li class="m-nav__section m--hide">
							<span class="m-nav__section-text">
							</span>
                        </li>

                        <li class="m-nav__item">
                            <a href="{!! url('dashboard') !!}" class="m-nav__link">
                                <i class="m-nav__link-icon flaticon-profile-1"></i>
                                <span class="m-nav__link-title">
									<span class="m-nav__link-wrap">
										<span class="m-nav__link-text">
											個人資料
										</span>
									</span>
								</span>
                            </a>
                        </li>
                        @if ($user->isVip())
                            <li class="m-nav__item">
                                <a href="{!! url('dashboard/history') !!}" class="m-nav__link">
                                    <i class="m-nav__link-icon flaticon-share"></i>
                                    <span class="m-nav__link-title">
									<span class="m-nav__link-wrap">
										<span class="m-nav__link-text">
											足跡
										</span>
									</span>
								</span>
                                </a>
                            </li>
                        @endif
                        <li class="m-nav__item">
                            <a href="{!! url('dashboard/board') !!}" class="m-nav__link">
                                <i class="m-nav__link-icon flaticon-graphic-2"></i>
                                <span class="m-nav__link-title">
									<span class="m-nav__link-wrap">
										<span class="m-nav__link-text">
										留言版
										</span>
									</span>
								</span>
                            </a>
                        </li>
                        @if (!$user->isVip())
                            <li class="m-nav__item">
                                <a href="{!! url('dashboard/upgrade') !!}" class="m-nav__link">
                                    <i class="m-nav__link-icon fa fa-diamond"></i>
                                    <span class="m-nav__link-title">
									<span class="m-nav__link-wrap">
										<span class="m-nav__link-text">
	                                        升級 VIP
										</span>
									</span>
								</span>
                                </a>
                            </li>
                        @else
                            <li class="m-nav__item">
                                <a href="{!! url('dashboard/cancel') !!}" class="m-nav__link">
                                    <i class="m-nav__link-icon fa fa-diamond"></i>
                                    <span class="m-nav__link-title">
									<span class="m-nav__link-wrap">
										<span class="m-nav__link-text">
	                                        取消 VIP
										</span>
									</span>
								</span>
                                </a>
                            </li>
                        @endif
                        <li class="m-nav__separator m-nav__separator--fit">
                        </li>
                        <li class="m-nav__item">
                            <a href="{!! url('logout') !!}"
                               class="btn m-btn--pill    m-btn m-btn--custom btn-outline-danger m-btn--bolder">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</li>
<script>
    setInterval(function () {
        $(".question").addClass("m-animate-shake")
    }, 3e3);
    setInterval(function () {
        $(".question").removeClass("m-animate-shake")
    }, 6e3);
    $('.notice__toggle').click(function (){ $('.notice').toggle("slow") });
</script>
<style>
    .buttons{
        text-align: center;
    }
</style>