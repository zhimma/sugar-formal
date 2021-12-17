@if(isset($user))

    <div class="leftbg">
        <div class="leftimg">
            <img src="@if(file_exists( public_path().$user->meta->pic ) && $user->meta->pic != ""){{$user->meta->pic}} @elseif($user->engroup==2)/new/images/female.png @else/new/images/male.png @endif">
            <h2 style="word-break: break-word;">@if (str_contains(url()->current(), 'dashboard') || str_contains(url()->current(), 'MessageBoard')) {{ $user->name }} @elseif (isset($cur)) {{ $cur->name }} @endif
                @if (((isset($cur) && $cur->isVip() && $cur->engroup == '1')) || isset($user) && ($user->isVip() && str_contains(url()->current(), 'dashboard'))) (VIP) @endif @if((view()->shared('valueAddedServices')['hideOnline'] ?? 0) == 1)<br>(隱藏) @endif</h2>
        </div>
        <div class="leul">
            <ul>
                <li>
                    <a href="{!! url('dashboard/personalPage') !!}"><img src="/new/images/zsym_1.png">專屬頁面</a>
                </li>
                <li>
{{--                    <a href="{!! url('dashboard') !!}"><img src="/new/images/icon_48.png">個人資料</a>--}}
                    <a href="/dashboard/viewuser/{{$user->id}}?page_mode=edit"><img src="/new/images/icon_48.png">個人資料</a>
                </li>
{{--                @if($user->meta->isConsign == 0 && ($user->meta->consign_expiry_date == null||$user->meta->consign_expiry_date <= \Carbon\Carbon::now()))--}}
                <li>
                    <a href="{!! url('dashboard/search') !!}"><img src="/new/images/icon_38.png">搜索</a>
                </li>
                <li>
                    <a href="{!! url('dashboard/chat2/'.csrf_token().\Carbon\Carbon::now()->timestamp) !!}"><img src="/new/images/icon_45.png">收件夾</a><span id="unreadCount">0</span>
                </li>
                @if(isset($user) && $user->engroup == 1)
                    @php
                        $ban = \App\Models\SimpleTables\banned_users::where('member_id', $user->id)->first();
                        $banImplicitly = \App\Models\BannedUsersImplicitly::where('target', $user->id)->first();
                    @endphp
                   <li>
                       @if($ban || $banImplicitly)
                           <a onclick="CheckEnterPop()"><img src="/new/images/tlq.png">討論區</a>
                       @elseif(!$user->isCanPosts_vip())
                           <a onclick="CheckEnterPop2()"><img src="/new/images/tlq.png">討論區</a>
                       @elseif($user->isEverBanned())
                           @php
                               //print_r($user->is_banned_log());
                                 $record = $user->isEverBanned();
                                 $reason = str_replace('(未續費)','', $record->reason);
                                 $text = '您於'.substr($record->created_at, 0, 10).'曾被站方因'.$reason.'警示，不符合進入討論區資格，若有意見反應，請洽站長Line@';
                           @endphp
                           <a onclick="CheckEnterPopOther('{{$text}}')"><img src="/new/images/tlq.png">討論區</a>
                       @elseif($user->isEverWarned())
                           @php
                               //print_r($user->is_warned_log());
                                  $record = $user->isEverWarned();
                                  $reason = str_replace('(未續費)','', $record->reason);
                                  $text = '您於'.substr($record->created_at, 0, 10).'曾被站方因'.$reason.'封鎖，不符合進入討論區資格，若有意見反應，請洽站長Line@';
                           @endphp
                           <a onclick="CheckEnterPopOther('{{$text}}')"><img src="/new/images/tlq.png">討論區</a>
                       @else
                           <a onclick="CheckEnterPopOK()" class="forum_pass"><img src="/new/images/tlq.png">討論區</a>
                       @endif
                   </li>
                @endif
                <li>
                    <a href="/MessageBoard/showList"><img src="/new/images/icon_new45.png">留言板</a>
                </li>
{{--                @endif--}}
                <li>
                   <a href="{!! url('dashboard/browse') !!}"><img src="/new/images/icon_46.png">瀏覽資料</a>
                </li>
                <li>
                    <a href="{!! url('dashboard/vipSelect') !!}"><img src="/new/images/us2.png">升級付費</a>
                </li>
                <li>
                    <a href="{!! url('logout') !!}"><img src="/new/images/iconout.png">退出</a>
                </li>
            </ul>
        </div>
    </div>
    <script>
        let script = '<a href="https://lin.ee/rLqcCns"><img src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png" alt="加入好友" height="36" border="0"></a>';
        function CheckEnterPop() {
            c5('您好，您目前被站方限制使用討論區，若有疑問請點右下角，聯繫站長Line@');
            $('.bltext').append(script);
        }
        function CheckEnterPop2() {
            c5('您成為VIP未達滿三個月以上');
        }
        function CheckEnterPopOther(text) {
            c5(text);
            $('.bltext').append(script);
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

@endif

