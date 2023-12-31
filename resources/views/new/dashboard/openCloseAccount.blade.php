@extends('new.layouts.website')

@section('app-content')
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="g_password g_pnr">
                    <div class="g_pwicon">
                        <li><a href="@if($user->isVVIP()) /dashboard/viewuser_vvip/{{$user->id}} @else /dashboard/viewuser/{{$user->id}} @endif" class="g_pwicon_t5 "><span>自我預覽</span></a></li>
                        <li><a href="{!! url('dashboard') !!}" class="g_pwicon_t "><span>基本資料</span></a></li>
                        <li><a href="{!! url('dashboard_img') !!}" class="g_pwicon_t2"><span>照片管理</span></a></li>
                        <li><a href="{!! url('/dashboard/account_manage') !!}" class="g_pwicon_t3 g_hicon3"><span>帳號設定</span></a></li>
{{--                        <li><a href="{!! url('/dashboard/vipSelect') !!}" class="g_pwicon_t4"><span>升級付費</span></a></li>--}}
                    </div>
                    <div class="gg_zh">
                        <div class="gg_mm"><span><i></i>帳號開啟/關閉</span><img src="/new/images/darkPinkKey.png"></div>
                        @if($user->account_status_admin == 0)
                            <div class="sjlist_li">
                                <div class="leftsidebar_box">
                                    <form method="POST" action="/dashboard/updateAccountStatus">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="status" value="open">
                                        <div class="gg_input01">
                                            <div class="de_input01 dlmarbot">
                                                <div class="de_img"><img src="/new/images/lo_03.png"></div>
                                                <input name="email" type="email" autocomplete="off" id="email" class="d_input" placeholder="帳號 (您的Email)" required="" data-parsley-id="4" style="border-radius: 50px;">
                                            </div>
                                            <div class="de_input01 dlmarbot">
                                                <div class="de_img"><img src="/new/images/lo_11.png"></div>
                                                <input name="password" type="password" class="d_input" id="password" placeholder="密碼" required="" data-parsley-id="6" style="border-radius: 50px;">
                                            </div>
                                        </div>
                                        <br>
                                        <button id="closeAccount" class="dlbut n_center" style="border-style: none;">帳號開啟</button>
                                    </form>
                                </div>
                            </div>
                        @elseif($user->accountStatus == 0)
                            @if ($user->isVipOrIsVvip())
                                <div class="n_shtab">
                                    <?php
                                    $dbCloseDay = \App\Models\AccountStatusLog::where('user_id',$user->id)->orderBy('created_at', 'desc')->first();
                                    $closeDay = '';
                                    $waitDay = '';
                                    if(!is_null($dbCloseDay)){
                                        $closeDay = date("Y-m-d",strtotime($dbCloseDay->created_at));
                                        $baseDay = date("Y-m-d",strtotime("+30 days",substr(strtotime($dbCloseDay->created_at), 0 ,10)));
                                        $waitDay = round((strtotime($baseDay)-strtotime($closeDay))/3600/24);
                                    }
                                    ?>
                                    <h2>您已於{{ $closeDay }}將會員帳號關閉，若要使用請先開啟此帳號，謝謝。</h2>
                                </div>
                                <div class="sjlist_li">
                                    <div class="leftsidebar_box">
                                        <form method="POST" action="/dashboard/updateAccountStatus">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="status" value="open">
                                            <div class="gg_input01">
                                                <div class="de_input01 dlmarbot">
                                                    <div class="de_img"><img src="/new/images/lo_03.png"></div>
                                                    <input name="email" type="email" autocomplete="off" id="email" class="d_input" placeholder="帳號 (您的Email)" required="" data-parsley-id="4" style="border-radius: 50px;">
                                                </div>
                                                <div class="de_input01 dlmarbot">
                                                    <div class="de_img"><img src="/new/images/lo_11.png"></div>
                                                    <input name="password" type="password" class="d_input" id="password" placeholder="密碼" required="" data-parsley-id="6" style="border-radius: 50px;">
                                                </div>
                                            </div>
                                            <br>
                                            <button id="closeAccount" class="dlbut n_center" style="border-style: none;">帳號開啟</button>
                                        </form>
                                    </div>
                                </div>
                            @else

                                <?php
                                $dbCloseDay = \App\Models\AccountStatusLog::where('user_id',$user->id)->orderBy('created_at', 'desc')->first();
                                $closeDay = '';
                                $waitDay = '';
                                if(!is_null($dbCloseDay)){
                                    $closeDay = date("Y-m-d",strtotime($dbCloseDay->created_at));
                                    $baseDay = date("Y-m-d",strtotime("+30 days",substr(strtotime($dbCloseDay->created_at), 0 ,10)));
                                    $nowDay = date("Y-m-d");
                                    $waitDay = round((strtotime($baseDay)-strtotime($nowDay))/3600/24);
                                }
                                ?>

                                <!--測試用程式碼-->
                                <!--記得將資料庫users->accountStatus改為0為帳號關閉狀態-->
                                <?php
                                    //$waitDay = 1;
                                ?>
                                <!--測試用程式碼-->

                                @if($waitDay >0)

                                    <!--新增升級解鎖VIP訊息及按鈕 by simon-->
                                    <div class="n_shtab">
                                        <h2>您已於{{ $closeDay }}將會員帳號關閉，試用會員每個月僅可變更一次，你需再等{{ $waitDay }}天後才能再次開啟此帳號，造成不便請見諒，或者點此
                                            <a href="{!! url('dashboard/new_vip') !!}" style="color:blue;">
                                                升級VIP
                                            </a>
                                            即時解鎖。
                                        </h2>
                                    </div>
                                    <!--新增升級解鎖VIP訊息及按鈕 by simon-->

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
                                @else
                                    <div class="n_shtab">
                                        <h2>提醒您：試用會員每個月僅可變更一次，距離您上次關閉帳號已超過一個月，您現在可以隨時再次開啟此帳號。</h2>
                                    </div>
                                    <div class="sjlist_li">
                                        <div class="leftsidebar_box">
                                            <form method="POST" action="/dashboard/updateAccountStatus">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="status" value="open">
                                                <div class="gg_input01">
                                                    <div class="de_input01 dlmarbot">
                                                        <div class="de_img"><img src="/new/images/lo_03.png"></div>
                                                        <input name="email" type="email" autocomplete="off" id="email" class="d_input" placeholder="帳號 (您的Email)" required="" data-parsley-id="4" style="border-radius: 50px;">
                                                    </div>
                                                    <div class="de_input01 dlmarbot">
                                                        <div class="de_img"><img src="/new/images/lo_11.png"></div>
                                                        <input name="password" type="password" class="d_input" id="password" placeholder="密碼" required="" data-parsley-id="6" style="border-radius: 50px;">
                                                    </div>
                                                </div>
                                                <br>
                                                <button id="closeAccount" class="dlbut n_center" style="border-style: none;">帳號開啟</button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @else
                            <form method="POST" id="change_account_status" action="/dashboard/closeAccountReason">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="userId" value="{{ $user->id }}">
                                <input type="hidden" name="status" value="close">
                                <div class="gg_nr01">
                                    <div class="baoy">
                                        <ul>
                                            <li><input name="reasonType" type="radio" value="1" checked><span>遇到騷擾/八大</span></li>
                                            <li><input name="reasonType" type="radio" value="2"><span>網站介面操作不滿意</span></li>
                                            <li><input name="reasonType" type="radio" value="3"><span>已找到長期穩定對象</span></li>
                                            <li><input name="reasonType" type="radio" value="4"><span>其他原因</span></li>
                                        </ul>
                                    </div>
                                    <br>
                                    <div class="gg_input01">
                                        <div class="de_input01 dlmarbot ">
                                            <div class="de_img"><img src="/new/images/lo_03.png"></div>
                                            <input name="email" type="email" autocomplete="off" id="email" class="d_input" placeholder="帳號 (您的Email)" required="" data-parsley-id="4" style="border-radius: 50px;">
                                        </div>
                                        <div class="de_input01 dlmarbot">
                                            <div class="de_img"><img src="/new/images/lo_11.png"></div>
                                            <input name="password" type="password" class="d_input" id="password" placeholder="密碼" required="" data-parsley-id="6" style="border-radius: 50px;">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="blxg">註：試用會員每個月僅可變更一次，VIP則沒有限制，並請填答關閉原因。</div>
                                    <div class="n_txbut g_inputt40" style="margin-top: 40px;">
                                        <button type="submit" class="se_but1" style="border-style: none;">關閉帳號</button>
                                        <button type="reset" class="se_but2">取消</button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('javascript')
    <script>
        @if(Session::has('message'))
        c5('{{Session::get('message')}}');
        @endif

        @if(isset($message))
        c5('{{$message}}');
        @endif

        var needLogout = '{{ (Session::has('needLogOut') && Session::get('needLogOut') == 'Y') ? 'Y' : 'N' }}';
        if(needLogout == 'Y'){
            {{ Session::forget('needLogOut') }}
            window.setTimeout(function() {
                location.href='/logout';
            }, 30000);
        }
    </script>
@stop