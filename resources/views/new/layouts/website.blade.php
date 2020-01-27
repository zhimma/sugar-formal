@include('new.partials.header')
<body class="" >
    @include('new.layouts.navigation')
    @yield("app-content")
    @include('new.partials.footer')
    @include('new.partials.message')
    @include('new.partials.scripts')

    @if(str_contains(url()->current(), 'dashboard'))
    <?php
    $announceRead = \App\Models\AnnouncementRead::select('announcement_id')->where('user_id', \Auth::user()->id)->get();
    $announcement = \App\Models\AdminAnnounce::where('en_group', \Auth::user()->engroup)->whereNotIn('id', $announceRead)->orderBy('sequence', 'desc')->get();
    foreach ($announcement as &$a){
        $a = str_replace(array("\r\n", "\r", "\n"), "<br>", $a);
    }
    $cc=0;
    ?>
    @if(count($announcement)>0)
        <div class="blbg" onclick="gmBtn1()" style="display:none;"></div>
        <div class="gg_tab" id="" style="display: none;">
            <div class="owl-carousel owl-theme">

                @foreach($announcement as $key =>  $a)
                    <?php $cc = $cc+1;?>
                    <div class="item">
                        <div class="ggtitle">站長公告(第{{ $cc }}/{{ count($announcement) }}則)</div>
                        <div class="ggnr01 ">
                        
                            <div class="gg_nr">
                                <a href="javascript:void(0);" class="gg_page" style="top:50% !important"><img src="/new/images/bk_03.png"></a>
                                {!! nl2br($a->content) !!}
                                <a href="javascript:void(0);" class="gg_pager" style="top:50% !important"><img src="/new/images/bk_05.png" ></a>
                            </div>
                            
                            <div class="gg_bg">
                                
                                <a class="ggbut" href="" onclick="disableAnnounce( {{ $a->id }} )" style="bottom: 10px;">不要顯示本廣告</a>
                                
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
            $(".blbg").hide();
            $(".bl").hide();
            $(".gg_tab").hide();
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
        $(document).ready(function() {
            @if(count($announcement)>0)
            $('.blbg').show();
            $(".gg_tab").show();
            @endif
            $('.owl-carousel').owlCarousel({
                loop: false,
                margin: 0,
                responsiveClass: true,
                autoHeight:true,
                responsive: {
                    0: {
                        items: 1,
                        nav: false,
                        dots:false
                    }
                }
            });
            $(".gg_page").on('click', function () {
                $('.owl-carousel').trigger('prev.owl.carousel');
            });
            $(".gg_pager").on('click', function () {
                $('.owl-carousel').trigger('next.owl.carousel');
            });

        })
    </script>
    @endif
    @yield("javascript")
</body>
</html>