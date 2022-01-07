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
				@if(!str_contains(url()->current(), 'dashboard') && !str_contains(url()->current(), 'MessageBoard') && !str_contains(url()->current(), 'contact') && !str_contains(url()->current(), 'notification') && !str_contains(url()->current(), 'feature') && !str_contains(url()->current(), 'terms') && !str_contains(url()->current(), 'activate') && Auth::user() && $user->meta->is_active ==1)
				<div class="ndlrfont">
					<a href="{!! url('dashboard/search') !!}"><img src="/new/images/icon_38.png"></a>
{{--							@if($user->meta_()->isConsign == 0 && ($user->meta_()->consign_expiry_date == null||$user->meta_()->consign_expiry_date <= \Carbon\Carbon::now()))--}}
					<span class="getNum">
						<a href="{!! url('dashboard/chat2/'.csrf_token().\Carbon\Carbon::now()->timestamp) !!}">
							<img src="/new/images/icon_45.png">
						</a>
						<span id="unreadCount">0</span>
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
										<a href="{!! url('dashboard/personalPage') !!}"><img src="/new/images/zsym.png">專屬頁面</a>
									</li>
									<li>
										<a href="/dashboard/viewuser/{{$user->id}}?page_mode=edit"><img src="/new/images/icon_48.png">個人資料</a>
									</li>
{{--									@if($user->meta_()->isConsign == 0 && ($user->meta_()->consign_expiry_date == null||$user->meta_()->consign_expiry_date <= \Carbon\Carbon::now()))--}}
									<li>
										<a href="{!! url('dashboard/search') !!}"><img src="/new/images/icon_38.png">搜索</a>
									</li>
									<li>
										<a href="{!! url('dashboard/chat2/'.csrf_token().\Carbon\Carbon::now()->timestamp) !!}"><img src="/new/images/icon_45.png">收件夾</a><span id="unreadCount2">0</span>
									</li>
									@if($user->engroup == 1)
										@php
											//$ban = \App\Models\SimpleTables\banned_users::where('member_id', $user->id)->first();
											$banImplicitly = \App\Models\BannedUsersImplicitly::where('target', $user->id)->first();
										@endphp
										<li>
											@if($user->isVip())
												<a href="javascript:void(0);" onclick="CheckEnterPopOK()" class="forum_pass"><img src="/new/images/tlq.png">討論區</a>
											@elseif(!$user->isVip())
												<a href="javascript:void(0);" onclick="CheckEnterPop2()"><img src="/new/images/tlq.png">討論區</a>
{{--													@elseif($banImplicitly)--}}
{{--														<a href="javascript:void(0);" onclick="CheckEnterPop()"><img src="/new/images/tlq.png">討論區</a>--}}
{{--													@elseif($user->isEverBanned())--}}
{{--														@php--}}
{{--															//print_r($user->is_banned_log());--}}
{{--                                                              $record = $user->isEverBanned();--}}
{{--                                                              $reason = str_replace('(未續費)','', $record->reason);--}}
{{--                                                              $text = '您於'.substr($record->created_at, 0, 10).'曾被站方因'.$reason.'封鎖，不符合進入討論區資格，若有意見反應，請洽站長Line@';--}}
{{--														@endphp--}}
{{--														<a href="javascript:void(0);" onclick="CheckEnterPopOther('{{$text}}')"><img src="/new/images/tlq.png">討論區</a>--}}
{{--													@elseif($user->isEverWarned())--}}
{{--														@php--}}
{{--															//print_r($user->is_warned_log());--}}
{{--                                                               $record = $user->isEverWarned();--}}
{{--                                                               $reason = str_replace('(未續費)','', $record->reason);--}}
{{--                                                               $text = '您於'.substr($record->created_at, 0, 10).'曾被站方因'.$reason.'警示，不符合進入討論區資格，若有意見反應，請洽站長Line@';--}}
{{--														@endphp--}}
{{--														<a href="javascript:void(0);" onclick="CheckEnterPopOther('{{$text}}')"><img src="/new/images/tlq.png">討論區</a>--}}
{{--													@else--}}
{{--														<a href="javascript:void(0);" onclick="CheckEnterPopOK()" class="forum_pass"><img src="/new/images/tlq.png">討論區</a>--}}
											@endif
										</li>
									@endif
									<li>
										<a href="/MessageBoard/showList"><img src="/new/images/icon_new45.png">留言板</a>
									</li>
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
<script type="application/javascript">
	let scriptText = '<a href="https://lin.ee/rLqcCns"><img src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png" alt="加入好友" height="36" border="0"></a>';
	let vipScriptText = '此功能目前僅開放VIP使用，<a href="/dashboard/new_vip"><span style="color: red;">請點此升級</span></a>';
	function CheckEnterPop() {
		c5('您好，您目前被站方限制使用討論區，若有疑問請點右下角，聯繫站長Line@');
		$('.bltext').append(scriptText);
	}
	function CheckEnterPop2() {
		// c5('您成為VIP未達滿三個月以上');
		c5('此功能目前僅開放VIP使用，');
		$('.bltext').append(vipScriptText);
	}
	function CheckEnterPopOther(text) {
		c5(text);
		$('.bltext').append(scriptText);
	}
	function CheckEnterPopOK() {
		@if(!str_contains(url()->current(), 'dashboard/forum'))
		$(".announce_bg").show();
		$('.tab_postsForumAlert').show();
		$('.n_bllbut').on('click', function() {
			$(".announce_bg").hide();
			$('.tab_postsForumAlert').hide();
			window.location.href = "/dashboard/forum";

		});
		@elseif(str_contains(url()->current(), 'dashboard/forum'))
				window.location.href = "/dashboard/forum";
		@endif
	}
</script>

