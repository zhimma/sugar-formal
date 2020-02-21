@if(isset($user))

    <div class="leftbg">
        <div class="leftimg">
            <img src="@if(file_exists( public_path().$user->meta_()->pic )){{$user->meta_()->pic}} @else/img/male-avatar.png @endif">
            <h2 style="word-break: break-all;">@if (str_contains(url()->current(), 'dashboard')) {{ $user->name }} @elseif (isset($cur)) {{ $cur->name }} @endif
                @if (((isset($cur) && $cur->isVip() && $cur->engroup == '1')) || ($user->isVip() && str_contains(url()->current(), 'dashboard'))) (VIP) @endif</h2>
        </div>
        <div class="leul">
            <ul>
                <li>
                    <a href="{!! url('dashboard') !!}"><img src="/new/images/icon_48.png">個人資料</a>
                </li>
                <li>
                    <a href="{!! url('dashboard/search') !!}"><img src="/new/images/icon_38.png">搜索</a>
                </li>
                <li>
                    <a href="{!! url('dashboard/chat2/'.csrf_token().\Carbon\Carbon::now()->timestamp) !!}"><img src="/new/images/icon_45.png">收件夾</a><span>{{ \App\Models\Message::unread($user->id) }}</span></li>
                <li>
                   <a href="{!! url('dashboard/browse') !!}"><img src="/new/images/icon_46.png">瀏覽資料</a>
                </li>
                <li>
                    <a href="{!! url('logout') !!}"><img src="/new/images/iconout.png">退出</a>
                </li>
            </ul>
        </div>
    </div>


@endif

