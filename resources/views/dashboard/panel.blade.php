<?php
if (isset($cur)) $orderNumber = $cur->id;
else $orderNumber = "";
$code = Config::get('social.payment.code');

if(Auth::user()) $login_user = Auth::user();
?>

<div class="col-lg-3 col-md-4 d-none d-md-block">
	<div class="user-panel m-portlet m-portlet--full-height  ">
		<div class="m-portlet__body">
			<div class="m-card-profile">
				<div class="m-card-profile__title m--hide">
					首頁
				</div>
				<div class="m-card-profile__pic">
					<div class="m-card-profile__pic-wrapper">
                        @if (str_contains(url()->current(), 'dashboard'))
                            @if($user->meta_()->isAvatarHidden == 1) <p style="color: red; text-align: center; font-weight: bold;">大頭照已被隱藏</p> @endif
                            <img width="130" height="130" src="{{$user->meta_()->pic}}" alt=""/>
                        @elseif(isset($cur))
                            @if($cur->meta_()->isAvatarHidden == 1)
                            @else
                                <img width="130" height="130" src="{{$cur->meta_()->pic}}" alt=""/>
                            @endif
                        @endif
					</div>
				</div>
				<div class="m-card-profile__details">
					<span class="m-card-profile__name">@if (str_contains(url()->current(), 'dashboard')) {{ $user->name }} @elseif (isset($cur)) {{ $cur->name }} @endif @if (((isset($cur) && $cur->isVip() && $cur->engroup == '1')) || ($user->isVip() && str_contains(url()->current(), 'dashboard'))) (VIP) @endif</span>
				</div>
			</div>

			<ul class="m-nav m-nav--hover-bg m-portlet-fit--sides">
				<li class="m-nav__separator m-nav__separator--fit"></li>
				@if(!isset($cur) && !str_contains(url()->current(), 'dashboard'))
					<div class="m-card-profile__details">
						<span class="m-card-profile__name">錯誤：沒有資料</span>
					</div>
				@elseif ((str_contains(url()->current(), 'dashboard') || $user->id == $cur->id))
					<li class="m-nav__section m--hide">
						<span class="m-nav__section-text">Section</span>
					</li>
					<li class="m-nav__item d-none d-md-block">
						<a href="{!! url('dashboard2') !!}" class="m-nav__link">
							<i class="m-nav__link-icon flaticon-profile-1"></i>
							<span class="m-nav__link-title">
								<span class="m-nav__link-wrap">
									<span class="m-nav__link-text">個人資料</span>
								</span>
							</span>
						</a>
					</li>
					<!--    <li class="m-nav__item d-none d-md-block">
						<a href="/user/view/{{$user->id}}" class="m-nav__link">
							<i class="m-nav__link-icon flaticon-profile-1"></i>
							<span class="m-nav__link-title">
								<span class="m-nav__link-wrap">
									<span class="m-nav__link-text">首頁</span>
								</span>
							</span>
						</a>
					</li> -->
					<li class="m-nav__item">
						<a href="{!! url('dashboard/search2') !!}" class="m-nav__link">
							<i class="m-nav__link-icon flaticon-search-1"></i>
							<span class="m-nav__link-text">搜索</span>
						</a>
					</li>
					<li class="m-nav__item">
						<a href="{!! url('dashboard/chat2/'.csrf_token().\Carbon\Carbon::now()->timestamp) !!}" class="m-nav__link">
							<i class="m-nav__link-icon flaticon-chat-1"></i>
							<span class="m-nav__link-text">收件夾 <span class="m-nav__link-badge"><span class="m-badge m-badge--danger">{{ \App\Models\Message::unread($user->id) }}</span></span>  </span>
						</a>
					</li>
					@if ($user->isVip())
					<li class="m-nav__item">
						<a href="{!! url('dashboard/history') !!}" class="m-nav__link">
						<i class="m-nav__link-icon flaticon-share"></i>
							<span class="m-nav__link-text">足跡</span>
						</a>
					</li>
					@endif
					<li class="m-nav__item">
						<a href="{!! url('dashboard/board') !!}" class="m-nav__link">
							<i class="m-nav__link-icon flaticon-graphic-2"></i>
							<span class="m-nav__link-text">留言版</span>
						</a>
					</li>
					@if (!$user->isVip())
						<li class="m-nav__item">
							<a href="{!! url('dashboard/upgrade_esafe') !!}" class="m-nav__link">
								<i class="m-nav__link-icon fa fa-diamond"></i>
								<span class="m-nav__link-text">升級 VIP</span>
							</a>
						</li>
						<li class="m-nav__item">
							<a href="{!! url('dashboard/cancel') !!}" class="m-nav__link">
								<i class="m-nav__link-icon fa fa-diamond"></i>
								<span class="m-nav__link-text cancelvip">取消 VIP</span>
							</a>
						</li>
					@else
						<li class="m-nav__item">
							<a href="{!! url('dashboard/cancel') !!}" class="m-nav__link">
								<i class="m-nav__link-icon fa fa-diamond"></i>
								<span class="m-nav__link-text cancelvip">取消 VIP</span>
							</a>
						</li>
						<li class="m-nav__item">
							<a href="{!! url('dashboard/fav2') !!}" class="m-nav__link">
								<i class="m-nav__link-icon fa fa-diamond"></i>
								<span class="m-nav__link-text">我的收藏</span>
							</a>
						</li>

						<li class="m-nav__item">
							<a href="{!! url('dashboard/block2') !!}" class="m-nav__link">
								<i class="m-nav__link-icon fa fa-diamond"></i>
								<span class="m-nav__link-text">我的封鎖名單</span>
							</a>
						</li>
					@endif
					<li class="m-nav__item">
						<a href="{!! url('dashboard/announcement') !!}" class="m-nav__link">
							<i class="m-nav__link-icon flaticon-profile-1"></i>
							<span class="m-nav__link-text">網站公告</span>
						</a>
					</li>
				@else
					<!-- $cur->id : /user/view/{$cur->id} -->
					@if(isset($cur) && $login_user->id != $cur->id)
						<li class="m-nav__item">
							<a href="{!! url('dashboard/board') !!}" class="m-nav__link" data-toggle="modal" data-target="#m_modal_1">
								<i class="m-nav__link-icon flaticon-comment"></i>
								<span class="m-nav__link-text">發信件</span>
							</a>
		                </li>
					@endif

				@if (isset($cur) && $user->isVip() && $login_user->id != $cur->id)
				<li class="m-nav__item">
					<form action="{!! url('dashboard/fav') !!}" class="m-nav__link" method="POST">
					<input type="hidden" name="_token" value="{{ csrf_token() }}" >
					<input type="hidden" name="userId" value="{{$user->id}}">
    				<input type="hidden" name="to" value="{{$cur->id}}">
					<button type="submit" style="background: none; border: none; padding: 0">
						<i class="m-nav__link-icon flaticon-profile"></i>
						<span class="m-nav__link-text">收藏</span>
					</button>
					</form>
                </li>

				<?php
					if(isset($cur)){
						$isBlocked = \App\Models\Blocked::isBlocked($user->id, $cur->id);
					}
				?>
				@if(isset($isBlocked) && !$isBlocked)
					<li class="m-nav__item">
						<form action="{!! url('dashboard/block') !!}" class="m-nav__link" method="POST">
						<input type="hidden" name="_token" value="{{ csrf_token() }}" >
						<input type="hidden" name="userId" value="{{$user->id}}">
						<input type="hidden" name="to" value="{{$cur->id}}">
						<button type="submit" style="background: none; border: none; padding: 0">
							<i class="m-nav__link-icon flaticon-alert-off"></i>
							<span class="m-nav__link-text">封鎖</span>
						</button>
						</form>
					</li>
				@elseif(isset($cur))
					<li class="m-nav__item">
						<form action="{!! url('dashboard/unblock') !!}" class="m-nav__link" method="POST">
						<input type="hidden" name="_token" value="{{ csrf_token() }}" >
						<input type="hidden" name="userId" value="{{$user->id}}">
						<input type="hidden" name="to" value="{{$cur->id}}">
						<button type="submit" style="background: none; border: none; padding: 0">
							<i class="m-nav__link-icon flaticon-alert-off"></i>
							<span class="m-nav__link-text">解除封鎖</span>
						</button>
						</form>
					</li>
				@endif
			@endif

			@if(isset($cur) && $login_user->id != $cur->id)
				<li class="m-nav__item">
					<form action="{!! url('dashboard/report') !!}" class="m-nav__link" method="POST">
					<input type="hidden" name="_token" value="{{ csrf_token() }}" >
					<input type="hidden" name="userId" value="{{$user->id}}">
    				<input type="hidden" name="to" value="{{$cur->id}}">
					<button type="submit" style="background: none; border: none; padding: 0" class="report">
						<i class="m-nav__link-icon flaticon-bell"></i>
						<span class="m-nav__link-text">檢舉</span>
					</button>
					</form>
                </li>
			@endif

            @if ($user->engroup == 1 && isset($cur) && $login_user->id != $cur->id)
				@if(!\App\Models\Tip::isComment($user->id, $cur->id) && $user->isVip() && \App\Models\Tip::isCommentNoEnd($user->id, $cur->id))
	                <li class="m-nav__item">
						@include('partials.tip-comment')
	                </li>
				@else
					<li class="m-nav__item">
						@include('partials.tip-invite')
	                </li>
				@endif
            @endif
		@endif
			</ul>

		</div>
	</div>
</div>

<div class="modal fade" id="m_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">發信給 @if(isset($cur)) {{ $cur->name }} @endif</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  <form method="POST" action="/dashboard/chat">
	  <input type="hidden" name="_token" value="{{ csrf_token() }}" >
    <input type="hidden" name="userId" value="{{$user->id}}">
	<input type="hidden" name="to" value="@if(isset($cur)){{$cur->id}}@endif">
       		<textarea class="form-control m-input" name="msg" id="msg" rows="4" maxlength="200"></textarea>
      </div>
      <div class="modal-footer">

        <button type="submit" class="btn btn-danger">送出</button>

		<button type="button" class="btn btn-outline-danger" data-dismiss="modal">取消</button>
		</form>
      </div>
    </div>
  </div>
</div>
<script>
    $('.report').on('click',function(e){
        if(!confirm('確定要檢舉他/她嗎?')){
            e.preventDefault();
        }
    });
</script>