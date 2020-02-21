		<div class="head_3 head hetop">
			<div class="container">
				<div class="col-sm-12 col-xs-12 col-md-12">
					<a href="{!! url('') !!}" >
						<img src="/new/images/icon_41.png" class="logo" />
					</a>
					@if (Auth::user() && Request::path() != '/activate' && Request::path() != '/activate/send-token')
						@if(Session::has('original_user'))
							<div class="ndlrfont">
								<a href="{{ route('escape') }}" class="m-nav__link m-dropdown__toggle">
									回到原使用者
								</a></div>
						@endif
					    @if(!str_contains(url()->current(), 'dashboard') && !str_contains(url()->current(), 'contact') && !str_contains(url()->current(), 'notification') && !str_contains(url()->current(), 'feature') && !str_contains(url()->current(), 'terms') && !str_contains(url()->current(), 'activate') && Auth::user() /*&& Request::path() != '/activate' && Request::path() != '/activate/send-token'*/)
						<div class="ndlrfont">
							<a href="{!! url('dashboard/search') !!}"><img src="/new/images/icon_38.png"></a>
							<span class="getNum">
								<a href="{!! url('dashboard/chat2/'.csrf_token().\Carbon\Carbon::now()->timestamp) !!}">
									<img src="/new/images/icon_45.png">
								</a>
								<span>{{ \App\Models\Message::unread($user->id) }}</span>
							</span>
							<a href="{!! url('dashboard') !!}"><img src="/new/images/icon_48.png"></a>
						</div>
						@endif
					@else
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
							@if (Auth::user() && Request::path() != '/activate' && Request::path() != '/activate/send-token')
							<span id="menuButton"><img src="/new/images/icon.png" class="he_img"></span>
							@else
							<div class="ndlrfont"><a href="{!! url('/checkAdult') !!}">註冊</a>丨<a href="{!! url('login') !!}">登入</a></div>
							@endif
						</div>
						@if (Auth::user() && Request::path() != '/activate' && Request::path() != '/activate/send-token')
						<ul id="menuList" class="change marg30">
                            <div class="comt"><img src="/new/images/t.png"></div>
                            <div class="coheight">
{{--							<div class="heyctop">@if (str_contains(url()->current(), 'dashboard')) {{ $user->name }} @elseif (isset($cur)) {{ $cur->name }} @endif @if (((isset($cur) && $cur->isVip() && $cur->engroup == '1')) || ($user->isVip() && str_contains(url()->current(), 'dashboard'))) (VIP) @endif</div>--}}
							<div class="heyctop">{{ $user->name }}@if($user->isVip()) (VIP) @endif</div>
							<div class="helist">
								<ul>
									<li>
										<a href="{!! url('dashboard') !!}"><img src="/new/images/icon_48.png">個人資料</a>
									</li>
									<li>
										<a href="{!! url('dashboard/search') !!}"><img src="/new/images/icon_38.png">搜索</a>
									</li>
									<li>
										<a href="{!! url('dashboard/chat2/'.csrf_token().\Carbon\Carbon::now()->timestamp) !!}"><img src="/new/images/icon_45.png">收件夾</a><span>{{ \App\Models\Message::unread($user->id) }}</span>
									</li>
									<li>
					                   <a href="{!! url('dashboard/browse') !!}"><img src="/new/images/icon_46.png">瀏覽資料</a>
					                </li>
								</ul>
							</div>
							<a href="{!! url('logout') !!}" class="tcbut">LOGOUT</a>
                            </div>
						</ul>
						@endif
					</div>
				</div>
			</div>
		</div>

