    <div class="leftbg">
        <div class="leftimg"><img src="{{ $user->meta_()->pic }}">
            <h2>@if (str_contains(url()->current(), 'dashboard')) {{ $user->name }} @elseif (isset($cur)) {{ $cur->name }} @endif @if (((isset($cur) && $cur->isVip() && $cur->engroup == '1')) || ($user->isVip() && str_contains(url()->current(), 'dashboard'))) (VIP) @endif</h2>
        </div>
        <div class="leul">
            <ul>
                <li>
                    <a href=""><img src="/new/images/icon_38.png">搜索</a>
                </li>
                <li>
                    <a href=""><img src="/new/images/icon_45.png">消息</a><span>10</span></li>
                <li>
                    <a href=""><img src="/new/images/icon_48.png">我的</a>
                </li>
                <li>
                    <a href=""><img src="/new/images/iconout.png">退出</a>
                </li>
            </ul>
        </div>
    </div>