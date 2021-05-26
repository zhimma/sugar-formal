		<div class="head_3 head hetop">
			<div class="container">
				<div class="col-sm-12 col-xs-12 col-md-12">
					<a href="{!! url('') !!}" >
						<img src="/new/images/icon_41.png" class="logo" />
					</a>
					@if (isset($user) && !str_contains(url()->current(), 'activate') && !str_contains(url()->current(), 'member_auth') && $user->meta->is_active ==1)
						@if(Session::has('original_user'))
							<div class="ndlrfont">
								<a href="{{ route('escape') }}" class="m-nav__link m-dropdown__toggle">
									回到原使用者
								</a></div>
						@endif
					    @if(!str_contains(url()->current(), 'dashboard') && !str_contains(url()->current(), 'contact') && !str_contains(url()->current(), 'notification') && !str_contains(url()->current(), 'feature') && !str_contains(url()->current(), 'terms') && !str_contains(url()->current(), 'activate') && Auth::user() && $user->meta->is_active ==1)
						<div class="ndlrfont">
							<a href="{!! url('dashboard/search') !!}"><img src="/new/images/icon_38.png"></a>
{{--							@if($user->meta_()->isConsign == 0 && ($user->meta_()->consign_expiry_date == null||$user->meta_()->consign_expiry_date <= \Carbon\Carbon::now()))--}}
							<span class="getNum">
								<a href="{!! url('dashboard/chat2/'.csrf_token().\Carbon\Carbon::now()->timestamp) !!}">
									<img src="/new/images/icon_45.png">
								</a>
								<span>{{ $unread }}</span>
							</span>
{{--							@endif--}}
							<a href="{!! url('dashboard/personalPage') !!}"><img src="/new/images/icon_48.png"></a>
						</div>
						@endif
					@elseif(isset($user) && Auth::user() && $user->meta_()->is_active ==0)
						<div class="ndlrfont"><a href="{!! url('logout') !!}">登出</a></div>
					@elseif(!str_contains(url()->current(), 'member_auth'))
						<div class="ndlrfont"><a href="{!! url('/checkAdult') !!}">註冊</a>丨<a href="{!! url('login') !!}">登入</a></div>
					@endif
				</div>
			</div>
		</div>
		<div class="head heicon">
			<div class="container">
				<div class="col-sm-12 col-xs-12 col-md-12">
					<div class="commonMenu">
						<div class="menuTop">
							<a href="{!! url('') !!}" >
								<img src="/new/images/icon_41.png" class="logo" />
							</a>
							@if (isset($user) && !str_contains(url()->current(), 'activate') && $user->meta->is_active ==1)
							<span id="menuButton"><img src="/new/images/icon.png" class="he_img"></span>
							@elseif(isset($user) && Auth::user() && $user->meta_()->is_active ==0)
								<div class="ndlrfont"><a href="{!! url('logout') !!}">登出</a></div>
							@else
							<div class="ndlrfont"><a href="{!! url('/checkAdult') !!}">註冊</a>丨<a href="{!! url('login') !!}">登入</a></div>
							@endif
						</div>
						@if (isset($user) && !str_contains(url()->current(), 'activate') && $user->meta->is_active ==1)
						<ul id="menuList" class="change marg30" style="z-index: 10;">
                            <div class="comt"><img src="/new/images/t.png"></div>
                            <div class="coheight">
							<div class="heyctop">{{ $user->name }}@if($user->isVip()) (VIP) @endif @if(view()->shared('valueAddedServices')['hideOnline'] ?? 0 == 1)<br>(隱藏)@endif</div>
								<div class="juanzhou">
									<div class="helist">
										<ul>
											<li>
												<a href="{!! url('dashboard/personalPage') !!}"><img src="/new/images/icon_48.png">專屬頁面</a>
											</li>
											<li>
												<a href="{!! url('dashboard') !!}"><img src="/new/images/icon_48.png">個人資料</a>
											</li>
		{{--									@if($user->meta_()->isConsign == 0 && ($user->meta_()->consign_expiry_date == null||$user->meta_()->consign_expiry_date <= \Carbon\Carbon::now()))--}}
											<li>
												<a href="{!! url('dashboard/search') !!}"><img src="/new/images/icon_38.png">搜索</a>
											</li>
											<li>
												<a href="{!! url('dashboard/chat2/'.csrf_token().\Carbon\Carbon::now()->timestamp) !!}"><img src="/new/images/icon_45.png">收件夾</a><span>{{ $unread ?? 0 }}</span>
											</li>
											@if($user->engroup == 1)
												<li>
													<a href="/dashboard/posts_list"><img src="/new/images/tlq.png">討論區</a>
												</li>
											@endif
		{{--									@endif--}}
											<li>
											   <a href="{!! url('dashboard/browse') !!}"><img src="/new/images/icon_46.png">瀏覽資料</a>
											</li>
											<li>
												<a href="{!! url('dashboard/vipSelect') !!}"><img src="/new/images/us2.png">升級付費</a>
											</li>
										</ul>
									</div>
									<a href="{!! url('logout') !!}" class="tcbut">LOGOUT</a>
								</div>
                            </div>
						</ul>
						@endif
					</div>
				</div>
			</div>
		</div>

