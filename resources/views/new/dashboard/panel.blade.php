    <div class="leftbg">
        <div class="leftimg"><img src="{{ $user->meta_()->pic }}">
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
                   <a href="{!! url('browse') !!}"><img src="/new/images/icon_46.png">瀏覽資料</a>
                </li>
                <li>
                    <a href="{!! url('logout') !!}"><img src="/new/images/iconout.png">退出</a>
                </li>
            </ul>
        </div>
    </div>

    <?php
    $announceRead = \App\Models\AnnouncementRead::select('announcement_id')->where('user_id', \Auth::user()->id)->get();
    $announcement = \App\Models\AdminAnnounce::where('en_group', \Auth::user()->engroup)->whereNotIn('id', $announceRead)->orderBy('sequence', 'desc')->get();
    foreach ($announcement as &$a){
        $a = str_replace(array("\r\n", "\r", "\n"), "<br>", $a);
    }
    $cc=0;
    ?>
    @if(count($announcement)>0)
    <div class="blbg" onclick="gmBtn1()" style="display:block"></div>
    <div class="gg_tab" id="" style="display: block;">
        <div class="owl-carousel owl-theme">

            @foreach($announcement as $key =>  $a)
                <?php $cc = $cc+1;?>
                <div class="item">
                    <div class="ggtitle">站長公告(第{{ $cc }}/{{ count($announcement) }}則)</div>
                    <div class="ggnr01 ">
                        <div class="gg_nr">{!! nl2br($a->content) !!}</div>
                        <div class="gg_bg">
                            <a href="javascript:void(0);" class="gg_page"><img src="/new/images/bk_03.png"></a>
                            <a class="ggbut" href="" onclick="disableAnnounce( {{ $a->id }} )" style="bottom: 10px;">不要顯示本廣告</a>
                            <a href="javascript:void(0);" class="gg_pager"><img src="/new/images/bk_05.png" ></a>
                        </div>
                    </div>
                    <a id="" onclick="gmBtn1()" class="bl_gb"><img src="/new/images/gb_icon01.png" style="width: 30px;"></a>
                </div>
            @endforeach

        </div>
    </div>
    @endif

    <link rel="stylesheet" href="/new/owlcarousel/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="/new/owlcarousel/assets/owl.theme.default.min.css">
    <script src="/new/owlcarousel/owl.carousel.js"></script>
    <script>

        function gmBtn1(){
            $(".blbg").hide()
            $(".gg_tab").hide()
        }

        function disableAnnounce(aid){
            $.ajax({
                type: 'POST',
                url: '{{ route('announceRead') }}',
                data: { uid: "{{ $user->id }}", aid: aid, _token: "{{ csrf_token() }}"},
                success: function(xhr, status, error){
                    console.log(xhr);
                    console.log(error);
                },
                error: function(xhr, status, error){
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            $('.owl-carousel').owlCarousel({
                loop: false,
                margin: 0,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                        nav: false,
                        dots:false
                    }
                }
            })
            $(".gg_page").on('click', function () {
                $('.owl-carousel').trigger('prev.owl.carousel');
            });
            $(".gg_pager").on('click', function () {
                $('.owl-carousel').trigger('next.owl.carousel');
            });

        })
    </script>