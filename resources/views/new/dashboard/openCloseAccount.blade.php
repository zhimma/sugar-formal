<?
header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
?>
<style>
    .page>li{
        display: none !important;
    }
    .pagination > li > a:focus,
    .pagination > li > a:hover,
    .pagination > li > span:focus,
    .pagination > li > span:hover{
        z-index: 3;
        background-color: #FF8888 !important;
    }
    .sjright{
        right: 40px;
        position: absolute;
    }
    .shou_but{
         margin-top: 8px !important;
         right: 80px;
         position: absolute;
         z-index: 1;
     }
    @media (max-width: 767px){
        .sjright{
            right: 20px;
        }
        .shou_but{
            margin-top: 8px !important;
            right: 40px !important;
            position: absolute;
        }
    }
    .reportFlag{
        background: rgba(255,24,34,0.71);
    }

</style>
@extends('new.layouts.website')

@section('app-content')

    <div class="container matop70 chat">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                @include('new.partials.message')
                <div class="g_pwicon">
                    <li><a href="{!! url('dashboard') !!}" class="g_pwicon_t "><span>基本資料</span></a></li>
                    <li><a href="{!! url('dashboard_img') !!}" class="g_pwicon_t2"><span>照片管理</span></a></li>
                    <li><a href="{!! url('/dashboard/account_manage') !!}" class="g_pwicon_t3 g_hicon3"><span>更改帳號</span></a></li>
                    <li><a href="{!! url('/dashboard/new_vip') !!}" class="g_pwicon_t4"><span>VIP</span></a></li>
                </div>
                <div class="gg_zh">
                    <div class="gg_mm"><span><i></i>會員帳號開啟/關閉</span><img src="/new/images/darkPinkKey.png">
                </div>
                @if($user->accountStatus == 0)
                    @if ($user->isVip())
                        <div class="n_shtab">
                            <?php
                                $dbCloseDay = \App\Models\AccountStatusLog::where('user_id',$user->id)->orderBy('created_at', 'desc')->first();
                                $closeDay = '';
                                $waitDay = '';
                                if(!is_null($dbCloseDay)){
                                    $closeDay = date("Y-m-d",strtotime($dbCloseDay->created_at));
                                    $baseDay = date("Y-m-d",strtotime("+1 month",substr(strtotime($dbCloseDay->created_at), 0 ,10)));
                                    $waitDay = round((strtotime($baseDay)-strtotime($closeDay))/3600/24);
                                }
                            ?>
                            <h2>您已於{{ $closeDay }}將會員帳號關閉，再次開啟此帳號，謝謝。</h2>
                        </div>
                        <div class="sjlist_li">
                            <div class="leftsidebar_box">
                                <form method="POST" action="/dashboard/updateAccountStatus">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="status" value="open">
                                    <div class="n_bbutton" style="text-align: center;">
                                        <button type="submit" class="n_center" style="border-style: none; background: #8a9ff0; color:#ffffff; text-align: center; border-radius: 200px; width:150px; height: 40px;">帳號開啟</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="n_shtab">
                            <?php
                            $dbCloseDay = \App\Models\AccountStatusLog::where('user_id',$user->id)->orderBy('created_at', 'desc')->first();
                            $closeDay = '';
                            $waitDay = '';
                            if(!is_null($dbCloseDay)){
                                $closeDay = date("Y-m-d",strtotime($dbCloseDay->created_at));
                                $baseDay = date("Y-m-d",strtotime("+1 month",substr(strtotime($dbCloseDay->created_at), 0 ,10)));
                                $waitDay = round((strtotime($baseDay)-strtotime($closeDay))/3600/24);
                            }
                            ?>
                            <h2>您已於{{ $closeDay }}將會員帳號關閉，普通會員每個月僅可變更一次，你需再等{{ $waitDay }}天候後才能再次開啟此帳號，造成不便請見諒。</h2>
                        </div>
                        <div class="sjlist_li">
                            <div class="leftsidebar_box">
                                <form method="GET" action="/logout">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="n_bbutton" style="text-align: center;">
                                        <button type="submit" class="n_center" style="border-style: none; background: #8a9ff0; color:#ffffff; text-align: center; border-radius: 200px; width:150px; height: 40px;">帳號登出</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="n_shtab">
                        <h2>普通會員每個月僅可變更一次, VIP則沒有限制, 並請填寫關閉原因。</h2>
                    </div>
                    <div class="sjlist_li">
                        <div class="leftsidebar_box">
                            <dl class="system_log">
                                <dt class="lebox3 lebox_exchange_period_3 tab">遇到騷擾/假包養帳號</dt>
                                <dd class="lebox3_content">
                                    <div class="row mb-4 ">
                                        <div class="sjlist">
                                            <ul style="margin: 0px 15px;">
                                                <?php
                                                $userList = \App\Models\Message::orwhere('from_id', $user->id)
                                                    ->orwhere('to_id', $user->id)
                                                    ->where('content', 'NOT LIKE', '%系統通知%')->distinct()->select('from_id')->get()->toArray();

                                                $accountList = \App\Models\UserMeta::whereIn('user_id', $userList)->where('user_id', '!=', $user->id)->get();
                                                ?>
                                                @foreach($accountList as $account)
                                                @php
                                                    $changeName = DB::table('account_name_change')->where('user_id', $account->user_id)->where('status',1)->orderBy('passed_at', 'desc')->first();

                                                @endphp
                                                <li class="hy_bg01" >
                                                    <div class="si_bg">
                                                        <div class="sjpic"><a href="/dashboard/viewuser/{{ $account->user_id }}"><img src="{{ $account->pic }}"></a></div>
                                                        <div class="sjleft">
                                                            <div class="sjtable"><span>gold daddy</span></div>
                                                            @if(!is_null($changeName))
                                                                <font>此帳號曾於{{  date("Y-m-d",strtotime($changeName->created_at)) }}申請過變更帳號</font>
                                                            @endif
                                                        </div>
                                                        <div class="sjright" style="margin: 0px 40px">
                                                            <h4 class="fengs"><a href="javascript:" class="remove reportUser" data-id="{{ $account->user_id }}"><img src="/new/images/ncion_07.png">檢舉</a></h4>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                </li>
                                            </ul>
                                            <div class="loading warning" id="sjlist_alert_warning" style="display: none;"><span class="loading_text">loading</span></div>
                                            <div class="fengsicon d-none"><img src="/new/images/fs_03.png" class="feng_img"><span>暫無收藏</span></div>
                                            <div class="fenye"></div>
                                        </div>

                                        <div class="col-sm-12 col-lg-12" style="margin-left: 18px;">
                                            <form action="{{ url('/dashboard/updateAccountStatus') }}" method="post" enctype="multipart/form-data">
                                                <!-- name 要與 FileUploader 相同 -->
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="status" value="close">
                                                <input type="hidden" name="userId" value="{{ $user->id }}">
                                                <input type="hidden" name="reasonType" value="1">
                                                <input type="hidden" name="reportedId" value="">
                                                <input type="file" name="image" data-fileuploader-files='' required>
                                                <input type="submit" class="vipbut upload_btn abtn" value="送出" style="border-style: none;  margin-bottom: 10px;">
                                            </form>
                                        </div>
                                    </div>
                                </dd>

                                <dt class="lebox1 lebox_exchange_period_1 tab">網頁介面操作不滿意</dt>
                                <dd class="lebox1_content">
                                    <div class="n_zytab" style="margin-top: 20px;">
                                        <li><span style="margin-left: 20px;">01.整體設計</span><img src="/new/images/01-photo-index-uiux.jpg" style="height: 400px;"></li>
                                        <li style="float: right; "><span style="margin-left: 20px;">05.登入/註冊</span><img src="/new/images/05-photo-login.jpg" style="height: 400px;"></li>
                                        <li><span style="margin-left: 20px;">02.訊息收件夾</span><img src="/new/images/02-photo-chat-uiux.jpg" style="height: 400px;"></li>
                                        <li style="float: right;"><span style="margin-left: 20px;">06.訊息收件夾</span><img src="/new/images/06-photo-chat.jpg" style="height: 400px;"></li>
                                        <li><span style="margin-left: 20px;">03.搜索功能</span><img src="/new/images/03-photo-search-uiux.jpg" style="height: 400px;"></li>
                                        <li style="float: right;"><span style="margin-left: 20px;">07.搜索功能</span><img src="/new/images/07-photo-search.jpg" style="height: 400px;"></li>
                                        <li><span style="margin-left: 20px;">04.瀏覽會員</span><img src="/new/images/04-photo-viewUser-uiux.jpg" style="height: 400px;"></li>
                                        <li style="float: right;"><span style="margin-left: 20px;">08.瀏覽會員</span><img src="/new/images/08-photo-viewUser.jpg" style="height: 400px;"></li>
                                    </div>
                                    <div class="bl bl_tab" id="show_banned" style="display: block; position: unset; width: 80%; margin: 20px auto; border-color: #FF8197; background: #ECBEDB;">
                                        <div class="bltitle" style="background: #FF8197;"><span>網站操作不滿意</span></div>
                                        <div class="n_blnr01" >
                                            <form method="POST" action="/dashboard/updateAccountStatus">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="status" value="close">
                                                <input type="hidden" name="userId" value="{{ $user->id }}">
                                                <input type="hidden" name="reasonType" value="2">
                                                <textarea name="content" cols="" rows="" class="n_nutext" placeholder="請輸入理由" required></textarea>
                                                <div class="n_bbutton" style="text-align: center;">
                                                    <button type="submit" class="n_center" style="border-style: none; background: #FF8197; color:#ffffff; text-align: center; border-radius: 200px; width:150px; height: 40px;">送出</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </dd>
                                <dt class="lebox_alert tab">已找到長期穩定對象</dt>
                                <dd class="lebox_alert_content">
                                    <div class="bl bl_tab" id="show_banned" style="display: block; position: unset; width: 80%; margin: 20px auto;">
                                        <div class="bltitle"><span>已找到長期穩定對象</span></div>
                                        <div class="n_blnr01 ">
                                            <form method="POST" action="/dashboard/updateAccountStatus">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="status" value="close">
                                                <input type="hidden" name="userId" value="{{ $user->id }}">
                                                <input type="hidden" name="reasonType" value="3">
                                                <label><input type="checkbox" name="color1" value="blue" checked>已找到長期</label>
                                                <div class="n_bbutton" style="text-align: center;">
                                                    <button type="submit" class="n_center" style="border-style: none; background: #8a9ff0; color:#ffffff; text-align: center; border-radius: 200px; width:150px; height: 40px;">送出</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </dd>
                                <dt class="lebox2 lebox_exchange_period_2 tab">其他</dt>
                                <dd class="lebox2_content">
                                    <div class="bl bl_tab" id="show_banned" style="display: block; position: unset; width: 80%; margin: 20px auto;">
                                        <div class="bltitle"><span>其他原因</span></div>
                                        <div class="n_blnr01 ">
                                            <form method="POST" action="/dashboard/updateAccountStatus">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="status" value="close">
                                                <input type="hidden" name="userId" value="{{ $user->id }}">
                                                <input type="hidden" name="reasonType" value="4">
                                                <textarea name="content" cols="" rows="" class="n_nutext" placeholder="請輸入原因" required></textarea>
                                                <div class="n_bbutton" style="text-align: center;">
                                                    <button type="submit" class="n_center" style="border-style: none; background: #8a9ff0; color:#ffffff; text-align: center; border-radius: 200px; width:150px; height: 40px;">送出</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div>
@stop

@section('javascript')
<style>
    .box {
        width: 100%;
    }
    .ellipsis {
        overflow:hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    .comt{
        top: 8px;
        position: relative;
    }
    .popover  {
        background: #e2e8ff!important;
        color: #6783c7;
    }
    .popover.right .arrow:after {
        border-right-color:#e2e8ff;
    }
    .popover.bottom .arrow:after {
        border-bottom-color:#e2e8ff;
    }

    .reportFlag{background: #b4d7ff!important;}
</style>

<script>
    $('.reportUser').click(function() {
        $('.reportUser').removeClass('reportFlag');
        $(this).addClass('reportFlag');
        $("input[name=reportedId]").val($(this).attr("data-id"));

    });




    $('.lebox1,.lebox2,.lebox3,.lebox_alert').toggleClass('off');
    $('.lebox1,.lebox2,.lebox3,.lebox_alert').next('dd').hide();

    $('.lebox1,.lebox2,.lebox3,.lebox_alert').click(function() {

        $('.tab').removeClass('on').addClass('off');
        $('.tab').next('dd').hide();

        if ($(this).hasClass('off')) {
            $(this).removeClass('off');
            $(this).toggleClass('on');
        }else if($(this).hasClass('on')){
            $(this).removeClass('on');
            $(this).toggleClass('off');
        }
        $(this).next('dd').slideToggle("slow");
    });

</script>
@stop