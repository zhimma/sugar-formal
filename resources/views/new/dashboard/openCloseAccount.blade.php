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
                        <li><a href="{!! url('dashboard') !!}" class="g_pwicon_t "><span>基本資料</span></a></li>
                        <li><a href="{!! url('dashboard_img') !!}" class="g_pwicon_t2"><span>照片管理</span></a></li>
                        <li><a href="{!! url('/dashboard/account_manage') !!}" class="g_pwicon_t3 g_hicon3"><span>更改帳號</span></a></li>
                        <li><a href="{!! url('/dashboard/new_vip') !!}" class="g_pwicon_t4"><span>VIP</span></a></li>
                    </div>
                    <div class="gg_zh">
                        <div class="gg_mm"><span><i></i>帳號開啟/關閉</span><img src="/new/images/darkPinkKey.png"></div>
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
                                    <div class="blxg">註：普通會員每個月僅可變更一次，VIP則沒有限制，並請填答關閉原因。</div>
                                    <button type="submit" class="dlbut g_inputt40" style="border-style: none;">關閉帳號</button>
                                    <button type="reset" class="zcbut">取消</button>
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
        c2('{{Session::get('message')}}');
        @endif

        @if(isset($message))
        c2('{{$message}}');
        @endif

        var needLogout = '{{ (Session::has('needLogOut') && Session::get('needLogOut') == 'Y') ? 'Y' : 'N' }}';
        if(needLogout == 'Y'){
            //alert('logout');
            window.setTimeout(function() {
                location.href='/logout';
            }, 5000);
        }
    </script>
@stop