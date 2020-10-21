<header class="m-grid__item  m-header "  data-minimize-offset="200" data-minimize-mobile-offset="200" >
				<div class="m-container m-container--fluid m-container--full-height">
					<div class="m-stack m-stack--ver m-stack--desktop">

						<div class="m-stack__item m-brand">
							<div class="m-stack m-stack--ver m-stack--general">
								<div class="m-stack__item m-stack__item--middle m-brand__logo">
									<a href="{!! url('') !!}" class="m-brand__logo-wrapper">
										<img id="icon-header" alt="" src="/img/logo.png" />
									</a>
								</div>
							</div>
						</div>
						<div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
							<button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-dark " id="m_aside_header_menu_mobile_close_btn">
								<i class="la la-close"></i>
							</button>
							<div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-light m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-dark m-aside-header-menu-mobile--submenu-skin-dark "  >
								<ul class="m-menu__nav  m-menu__nav--submenu-arrow ">

									<li class="m-menu__item  m-menu__item--submenu m-menu__item--rel"  data-menu-submenu-toggle="click" aria-haspopup="true">

										<div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left">
											<span class="m-menu__arrow m-menu__arrow--adjust"></span>
											<ul class="m-menu__subnav">
											</ul>
										</div>
									</li>
								</ul>
							</div>
							<div id="m_header_topbar" class="m-topbar m-stack m-stack--ver m-stack--general">
								<div class="m-stack__item m-topbar__nav-wrapper">
									<ul class="m-topbar__nav m-nav m-nav--inline">
                                    @if (Auth::user() && Request::path() != '/activate' && Request::path() != '/activate/send-token')
                                    <?php $user = Auth::user() ?>
                                        @include('partials.user-navigation')
                                    @else
									<li class="m-nav__item m-dropdown m-dropdown--large m-dropdown--header-bg-fill m-dropdown--arrow m-dropdown--align-center	m-dropdown--mobile-full-width" data-dropdown-toggle="click" data-dropdown-persistent="true">
    									<a class="m-nav__link" style="font-size: 18px; color: gray; padding-top: 20px; position: relative;" href="{!! url('register') !!}">註冊</a>
                                    <li class="m-nav__item m-dropdown m-dropdown--large m-dropdown--header-bg-fill m-dropdown--arrow m-dropdown--align-center	m-dropdown--mobile-full-width" data-dropdown-toggle="click" data-dropdown-persistent="true">
									    <a class="m-nav__link" style="font-size: 18px; color: gray; padding-top: 20px; position: relative;" href="{!! url('login') !!}">登入</a>
									</li>
									<li class="m-nav__item m-dropdown m-dropdown--large m-dropdown--header-bg-fill m-dropdown--arrow m-dropdown--align-center	m-dropdown--mobile-full-width" data-dropdown-toggle="click" data-dropdown-persistent="true">
									    <span class="m-nav__link" style="font-size: 18px; color: gray; margin-top: 10px;"><i class="fa fa-globe"></i> 台灣</span>
									</li>
                                    @endif
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</header>
