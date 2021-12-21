
@include('new.partials.header')
<body class="" >
    @include('new.layouts.navigation')
    @yield("app-content")
    @if(!str_contains(url()->current(), 'post_detail'))
        @include('new.partials.footer')
    @endif
    @include('new.partials.message')
    @include('new.partials.scripts')

    @if(str_contains(url()->current(), 'dashboard'))

        @if(!Session::has('announceClose'))
            <?php
            $user = \Auth::user();
            if(isset($user)){
                //login_times_alert假設設定10次,會員目前登入次數>=10 的話就跳通知提示
                $announceRead = \App\Models\AnnouncementRead::select('announcement_id')->where('user_id', $user->id)->get();
     //           $announcement = \App\Models\AdminAnnounce::where('en_group', $user->engroup)->whereNotIn('id', $announceRead)->whereRaw('(login_times_alert is NULL OR login_times_alert <= '.$user->login_times.')')->orderBy('sequence', 'asc')->get();
                $is_new_7 = false;
                if( \Carbon\Carbon::parse($user->created_at)->diffInDays(\Carbon\Carbon::now())<7) {
                    $is_new_7 = true;
                }     
                $aq = \App\Models\AdminAnnounce::where('en_group', $user->engroup)->whereNotIn('id', $announceRead)->whereRaw('(login_times_alert is NULL OR login_times_alert <= '.$user->login_times.')')->orderBy('sequence', 'asc');
                if(!$is_new_7) $aq = $aq->where('is_new_7','<>',1);
                $announcement = $aq->get(); 
                foreach ($announcement as &$a){
                    if($a->login_times_alert){
                        $read = \App\Models\AnnouncementRead::where('user_id', $user->id)->where('announcement_id', $a->id)->first();
                        if(!$read){
                            $announceRead = new \App\Models\AnnouncementRead();
                            $announceRead->user_id = $user->id;
                            $announceRead->announcement_id = $a->id;
                            $announceRead->save();
                        }
                    }
                
                    $a->content = str_replace(array("\r\n", "\r", "\n"), "<br>", $a->content);
                    $a->content = str_replace('NAME', $user->name, $a->content);
                    $a->content = str_replace('|$report|', $user->name, $a->content);
                    $a->content = str_replace('LINE_ICON', \App\Services\AdminService::$line_icon_html, $a->content);
                    $a->content = str_replace('|$lineIcon|', \App\Services\AdminService::$line_icon_html, $a->content);         
                    $a->content = str_replace('|$responseTime|', date("Y-m-d H:i:s"), $a->content);
                    $a->content = str_replace('|$reportTime|', date("Y-m-d H:i:s"), $a->content);
                    $a->content= str_replace('NOW_TIME', date("Y-m-d H:i:s"), $a->content);                   
                }
                $cc=0;
            }
            ?>
        @endif

    @if(isset($announcement) && count($announcement) > 0 && !Session::has('announceClose'))
        <style type="text/css">
            .new_poptk::-webkit-scrollbar-thumb {
                border-radius: 5px;
                background-color: #fd90b6 !important;
                box-shadow: 0 0 1px rgba(255, 255, 255, .5);
            }

            
            @media (max-height:420px) and (min-width:520px) {
                #new_poptk_content {min-height:65px !important;}
            } 

            @media (max-height:320px) and (min-width:580px) {
                #new_poptk_content {min-height:45px !important;}
            } 

            @media (max-height:420px) and (min-width:700px) {
                #new_poptk_content {min-height:105px !important;}
            }             

            #announcement .item .new_poptk {overflow-x:hidden;width:95% !important;}
        </style>
        <div class="announce_bg" onclick="gmBtnNoReload()" style="display:block;"></div>
        <div class="gg_tab gg_tkimg" id="announcement" style="display: block;">
            <div class="owl-carousel owl-theme">

                @foreach($announcement as $key =>  $a)
                    <?php $cc = $cc+1;?>
                    <div class="item">
                        <div class="ggtitle">站長公告(第{{ $cc }}/{{ count($announcement) }}則)</div>
                        <div class="new_poptk" style="width: 90%; max-height: 350px;">
                            <div id="new_poptk_content" style="min-height: 105px;" @if(!$user->isVip() && $a->isVip==1)class="g_pfont"@endif>
                            {!! nl2br($a->content) !!}
                            @if(!$user->isVip() && $a->isVip==1)
                                <div class="g_picon"><img src="/new/images/viponly.png" style="width: unset;"></div>
                            @endif
                            </div>

{{--                            <div class="gg_bg">--}}
{{--                                <a href="javascript:void(0);" class="gg_page"><img src="/new/images/bk_03.png"></a>--}}
{{--                                <a class="ggbut" href="" onclick="disableAnnounce( {{ $a->id }} )" style="bottom: 10px;">不要顯示本公告</a>--}}
{{--                                <a href="javascript:void(0);" class="gg_pager"><img src="/new/images/bk_05.png" ></a>--}}
{{--                            </div>--}}

                            <div class="gongg_bg">
                                <a href="javascript:void(0);" class="gg_page gog_pager"><img src="/new/images/bk_03.png" class="left" style="width: unset;"></a>
                                <div class="n_bbutton_rg">
                                    <span><a href="" class="n_butleft gg_butnew" onclick="disableAnnounce( {{ $a->id }} )">不再顯示</a></span>
                                    <span><a class="n_butright gg_butnew" onclick="announceClose()">關閉</a></span>
                                </div>
                                <a href="javascript:void(0);" class=" gg_pager gog_pager right"><img src="/new/images/bk_05.png" class="right" style="width: unset;"></a>
                            </div>
                        </div>
{{--                        <a id="" onclick="gmBtn1()" class="bl_gb"><img src="/new/images/gb_icon01.png" style="width: 30px;"></a>--}}
                    </div>
                @endforeach

            </div>
        </div>

        <div class="bl bl_tab" id="tab05_new_sugar_chat_set_rs">
            <div class="bltitle">提示</div>
            <div class="n_blnr01 matop10">
            <div class="blnr bltext"></div>
            <a class="n_bllbut matop30" onclick="gmBtnNoReload()">確定</a>
            </div>
            <a id=""  class="bl_gb"><img src="/new/images/gb_icon.png"></a>
        </div>        
        <div class="bl bl_tab" id="tab06_new_sugar_chat_set">	
            <div class="bltitle">提示</div>	
            <div class="n_blnr01">	
                <div class="blnr bltext">是否願意接收普通會員訊息？
                <span id="now_setting" style="display:none;">(目前設定為<span id="now_setting_value"></span>)</span>
                </div>	
                <div class="remove_callback"></div>	
                <div class="n_bbutton">	
                    <span><a class="n_left" style="cursor:pointer">是</a></span>	
                    <span><a class="n_right"  style="cursor:pointer">否</a></span>	
                </div>	
            </div>	
            <a id="" class="bl_gb"><img src="/new/images/gb_icon.png"></a>	
        </div>        
    @endif


    @yield("javascript")

    <link rel="stylesheet" href="/new/owlcarousel/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="/new/owlcarousel/assets/owl.theme.default.min.css">
    <script src="/new/owlcarousel/owl.carousel.js"></script>
    <script>


        function announceClose(){
            $(".announce_bg").hide();
            $("#tab02").hide();
            // $(".bl_gb").hide();
            $(".gg_tab").hide();
            if($('#tab05').is(":visible")){
                $("#announce_bg").show();
            }
            @if(!Session::has('announceClose'))
            @php
                Session::put('announceClose', 1);
            @endphp
            $.ajax({
                type: 'POST',
                url: '{{ route('announceClose') }}',
                data: { _token: "{{ csrf_token() }}"},
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
            @endif
        }

        @if(isset($user))
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
        @endif
        $(document).ready(function() {
{{--            @if(isset($announcement) && count($announcement) > 0)--}}
{{--                $('.announce_bg').show();--}}
{{--                $(".gg_tab").show();--}}
{{--            @endif--}}
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


            $('#tab06_new_sugar_chat_set .bl_gb').on('click',new_sugar_chat_set_close);

            $(document).on('click','#announcement .item .new_poptk div a[href="#new_sugar_chat_set"]',function(){              
                 $.ajax({
                    type: 'GET',
                    url: '{{ route('getTinySetting') }}',
                    data: { catalog: "new_sugar_chat_with_notvip"},
                    success: function(data){
                        if(data!=undefined && data.length>0) {
                            $('#now_setting_value').text(data==0?'否':'是');
                            $('#now_setting').show();
                        }
                    },
                 });
                    
                $('#tab06_new_sugar_chat_set .n_left').on('click',new_sugar_chat_set_yes);
                $('#tab06_new_sugar_chat_set .n_right').on('click',new_sugar_chat_set_no);
                $("#tab02").hide();
                $(".gg_tab").hide();          
                $("#tab06_new_sugar_chat_set").show();	
               
                return false;
            });
            
            function new_sugar_chat_set_no() {
                event.preventDefault();
                 $.ajax({
                    type: 'GET',
                    url: '{{ route('getTinySetting') }}',
                    data: { catalog: "new_sugar_chat_with_notvip"},
                    success: function(data){
                        if(data==1) {
                            new_sugar_chat_set_send(0); 
                        }
                        else {
                           new_sugar_chat_set_close();
                        }
                    },
                    error:function(){
                        $('#tab05_new_sugar_chat_set_rs').text('發生錯誤，請重新操作'+error).show();
                         $('#tab05_new_sugar_chat_set_rs .n_bllbut').on('click',function(){
                            $('#tab05_new_sugar_chat_set_rs').hide();
                        });                        
                    }
                 });
                 
            }
            
            function new_sugar_chat_set_close() {
                $(".announce_bg").eq(1).show();
                $(".blbg").hide();                
                $("#tab02").show();
                $(".gg_tab").show();             
                $('#now_setting_value').text('');
                $('#now_setting').hide();                
                $('#tab06_new_sugar_chat_set .n_right').off('click',new_sugar_chat_set_no);
                $('#tab06_new_sugar_chat_set .n_left').off('click',new_sugar_chat_set_yes);	               
                $("#tab06_new_sugar_chat_set").hide();
                $('#tab05_new_sugar_chat_set_rs').hide();
            }
            
            function new_sugar_chat_set_yes() {
                event.preventDefault();
                new_sugar_chat_set_send(1); 
            }
            
            function new_sugar_chat_set_send(value) {
                 $.ajax({
                    type: 'GET',
                    url: '{{ route('setTinySetting') }}',
                    data: { catalog: "new_sugar_chat_with_notvip", value: value},
                    dataType:'json',
                    success: function(data){
                         if(data.msg!=undefined) {
                            $('#tab06_new_sugar_chat_set').hide();
                            $('#tab05_new_sugar_chat_set_rs').show().find('.bltext').text(data.msg);                            
                            
                            if(data.msg.indexOf('成功')>=0) {

                                $('#tab05_new_sugar_chat_set_rs .n_bllbut,#tab05_new_sugar_chat_set_rs .bl_gb').on('click',function(){
                                    new_sugar_chat_set_close();
                                });                                 
                            }
                            else if(data.msg.indexOf('失敗')>=0){
                                 $('#tab05_new_sugar_chat_set_rs .n_bllbut,#tab05_new_sugar_chat_set_rs .bl_gb').on('click',function(){
                                    $('#tab05_new_sugar_chat_set_rs').hide();
                                });                                
                            }
                         }
                         else {
                            $('#tab05_new_sugar_chat_set_rs').text('發生異常，請重新操作'+data).show();
                            
                             $('#tab05_new_sugar_chat_set_rs ,n_bllbut,#tab05_new_sugar_chat_set_rs .bl_gb').on('click',function(){
                                $('#tab05_new_sugar_chat_set_rs').hide();
                            });                               
                         }
                         
                        
                         
                         
                    },
                    error: function(xhr, status, error){
                      $('#tab05_new_sugar_chat_set_rs').text('發生錯誤，請重新操作'+error).show();
                         $('#tab05_new_sugar_chat_set_rs .n_bllbut,#tab05_new_sugar_chat_set_rs .bl_gb').on('click',function(){
                            $('#tab05_new_sugar_chat_set_rs').hide();
                        });                         
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                    }
                });                
            }

        })
    </script>

    @endif



</body>
</html>