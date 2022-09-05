@extends('new.layouts.website')
@section('style')
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<script src="https://sdk.amazonaws.com/js/aws-sdk-2.1.12.min.js"></script>
<script src="https://unpkg.com/amazon-kinesis-video-streams-webrtc/dist/kvs-webrtc.min.js"></script>
<script src="/new/js/aws-sdk-2.1143.0.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="/new/css/iconfont.css">
<style>
    #self_auth_state_block .tabbox_h2 .tu_dfont {min-height:30px;width:100%;}
    .sa_video_status {cursor: pointer;display:inline-block;}
    #video_status_text_show_elt {width:calc(100% - 85px);margin-right:20px;}
    #video_status_show_elt {vertical-align:top;}
    #self_auth_state_block .tabbox_h2,#self_auth_state_block .tabbox_h2 .tu_dfont {display:block;}
    #app,#app .btn-success {height:0 !important;width:0 !important;}
    #app {display:none !important;}
</style>
@stop
@section('app-content')
    <style>
        .table>tbody>tr>td{
            border-top: unset;
            border-bottom: 1px solid #ddd;
        }
        .on {min-height: 24px;}
		
		.ta_l{ border-bottom: #eee 1px solid; display: table; margin-bottom: 6px; padding-bottom: 6px; line-height: 24px;}
		.tu_dfont{
			width: calc(100% - 30px);
			 float: left;
			 /*max-height: 45px;*/
			 word-break: break-all;
			 text-overflow: ellipsis;
			 display: -webkit-box;
			 -webkit-box-orient: vertical;
			 /*-webkit-line-clamp: 2;*/
			 overflow: hidden;
		}	

		.tabbox_h2 .check{
			float:left;margin-right:15px;margin-top:5px;
		}	
		.fr_nbj{ float: right;}
		@media (max-width:320px) {
			.fr_nbj{ float: none;}
		}

        .tu_dfont a img{
            all:unset !important;height:26px !important; margin:-7px 5px !important;
        }
    </style>
    <style>
        .pj_add_a{margin: 0 auto;display: table;margin-bottom: 10px;}
        .dongt_fb{width: 100%;margin: 0 auto;display: block;margin-top: 0px;padding: 0 5px;}
        .new_npop{width: 96%; padding-bottom: 0; padding-top: 0; margin-top:15px; margin-bottom: 15px;margin-left: 2%; height: auto;}
        @media (max-width:823px){
            .dongt_fb{height:220px;overflow-y: scroll;}
        }
        @media (max-width:797px){
            .dongt_fb{height: auto;overflow-y: hidden;}
        }
        @media (max-width:736px){
            .dongt_fb{height:180px;overflow-y: scroll;}
        }
        @media (max-width:450px){
            .dongt_fb{height:auto;overflow-y: hidden;}
        }
        .line_img{width: 80%; margin: 0 auto; display: table;}
        .li_span{width: 100%; text-align: center; margin-bottom: 15px; display: table; font-size: 15px;}
        .li_span span{width: 100%; display: table;}

        .sl_bllbut{width:260px;height: 40px;background: #8a9ff0;border-radius:200px;color: #ffffff;text-align: center;line-height: 40px;display: table;
            margin: 0 auto;font-size:16px; margin-top:15px;cursor: pointer;}
        .sl_bllbut:hover{color:#ffffff;box-shadow:inset 0px 15px 10px -10px #4c6ded,inset 0px -10px 10px -20px #4c6ded;}

        .sl_bllbut01{width:260px;height: 40px;background: #fff;border-radius:200px;color: #8a9ff0;text-align: center;
            line-height: 40px;display: table;margin: 0 auto;font-size:16px; margin-top:15px; border: #8a9ff0 1px solid; cursor: pointer;}
        .sl_bllbut01:hover{color:#fff;background: #8a9ff0;box-shadow:inset 0px 15px 10px -10px #4c6ded,inset 0px -10px 10px -20px #4c6ded;}

    </style>
    <style>
        .pj_add_a{margin: 0 auto;display: table;margin-bottom: 10px;}
        .dongt_fb{width: 100%;margin: 0 auto;display: block;margin-top: 0px;padding: 0 5px;}
        .new_npop{width: 96%; padding-bottom: 0; padding-top: 0; margin-top:15px; margin-bottom: 15px;margin-left: 2%; height: auto;}
        @media (max-width:823px){
            .dongt_fb{height:220px;overflow-y: scroll;}
        }
        @media (max-width:797px){
            .dongt_fb{height: auto;overflow-y: hidden;}
        }
        @media (max-width:736px){
            .dongt_fb{height:180px;overflow-y: scroll;}
        }
        @media (max-width:450px){
            .dongt_fb{height:auto;overflow-y: hidden;}
        }
        .line_img{width: 80%; margin: 0 auto; display: table;}
        .li_span{width: 100%; text-align: center; margin-bottom: 15px; display: table; font-size: 15px;}
        .li_span span{width: 100%; display: table;}

        .sl_bllbut{width:260px;height: 40px;background: #8a9ff0;border-radius:200px;color: #ffffff;text-align: center;line-height: 40px;display: table;
            margin: 0 auto;font-size:16px; margin-top:15px;cursor: pointer;}
        .sl_bllbut:hover{color:#ffffff;box-shadow:inset 0px 15px 10px -10px #4c6ded,inset 0px -10px 10px -20px #4c6ded;}

        .sl_bllbut01{width:260px;height: 40px;background: #fff;border-radius:200px;color: #8a9ff0;text-align: center;
            line-height: 40px;display: table;margin: 0 auto;font-size:16px; margin-top:15px; border: #8a9ff0 1px solid; cursor: pointer;}
        .sl_bllbut01:hover{color:#fff;background: #8a9ff0;box-shadow:inset 0px 15px 10px -10px #4c6ded,inset 0px -10px 10px -20px #4c6ded;}

    </style>
    <style>
    #vip_state_block .tu_dfont,#self_auth_state_block .tu_dfont  {width:auto;max-height:unset; -webkit-box-orient: vertical; -webkit-line-clamp:none; -webkit-line-clamp:unset;}
    #vip_state_block .tabbox_new_dt a.zs_buttonn,#self_auth_state_block  .tabbox_new_dt a.zs_buttonn,#adv_auth_state_block .tabbox_new_dt a.zs_buttonn{font-size: 15px; line-height: 30px;font-weight:normal;margin-right:2%; }
    </style>
    <style>
        span.main_word {color:#fd5678;font-weight:bolder;}
        div.one_row_sys_aa {overflow:hidden;}
        div.one_row_sys_aa div.sys_aa {width:49%;}
        div.one_row_sys_aa div.sys_aa_first {float:left;}
        div.one_row_sys_aa div.sys_aa_last {float:right;}
        div.one_row_sys_aa div.tabbox_new_dt span {margin-left:5%;}
        div.one_row_sys_aa .tabbox_new_dd .tabbox_hsz {width:88%;}
        div.sys_remind {
            margin-top:10px;
            border: #ffc8cd 1px solid;
        }
        div.sys_remind .tu_dfont {
            width: calc(100% - 100px);
            max-height: 45px;
            -webkit-line-clamp: 2;
        }
        div.sys_remind > div.tabbox_new_dt.tabbox_new_ss {background:#fff5f6;}
        div.sys_remind > div.tabbox_new_dt.tabbox_new_ss > span {color:#fe5476;}
    </style>
    <style>
        .wifontext{font-size: 16px;/* background: #fff100; */ background:rgba(253,79,119,0.5);  color: #fff; font-size: 20px; font-weight: bold; border-radius: 10px;padding: 10px 15px;
            text-align: left;margin-top: 20px; }
        .cjwt{ margin: 0 auto; display: table;margin-top: 20px; padding: 10px 15px;color: #000; margin-bottom: 15px;}
        @media (min-width:916px){
            .ga_d{display: none;}
        }
    </style>

    <script>
        //廣告頁面登入
        if(window.sessionStorage.getItem('advertise_id'))
        {
            $.ajax({
                type:'GET',
                url:'{{ route('advertise_record_change') }}',
                data:{
                    advertise_id:window.sessionStorage.getItem('advertise_id'),
                    type:'login'
                },
                success:function(){}
            });
        }
    </script>

    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="shou shou02 sh_line"><span>會員專屬資訊</span>
                    <font style="font-size: 14px;">Personal Information</font>
                </div>

                <div class="n_search tabbox_new g_pnr">
                    <div class="sys_aa">
                        <div class="tabbox_new_dt tabbox_new_ss"><span>站長來訊通知</span>
                            @if(isset($admin_msgs) && count($admin_msgs))
                                <div class="right btn01 btn_admin_msgs"><span class="zixu_cs"><img src="/new/images/xiugai1.png">編輯</span></div>
                                <div class="btn02 sx_ment fr_nbj">
                                    <span class="iconfont icon-wancheng zixu_cs1 dtmr20">完成</span>
                                    <span class="iconfont icon-shanchu zixu_cs1">刪除</span>
                                    <label class="iconfont icon-kuang zixu_cs2"  style="margin-top: 2px; margin-right: 3px; float:right ;"><input type="checkbox" class="qxuan">全選</label>
                                </div>
                            @endif
                        </div>
                        <div class="tabbox_new_dd">
                            @if(isset($admin_msgs) && count($admin_msgs))
                                @foreach($admin_msgs as $amsg)
                                    <h2 class="tabbox_h2 ta_l"  data-recordtype="admin_msgs" data-rowid="{{$amsg->id}}" >
								<span class="tu_dfont">
								{!! $amsg->content !!}
								</span>
                                    </h2>
                                @endforeach
                            @else
                                <h2 class="tabbox_h2 ta_l"><span class="tu_dfont">暫無系統信</span></h2>
                            @endif
                        </div>
                    </div>
                    @if($user->engroup==1)
                        @if(!(isset($user->applyVVIP_getData()->created_at) && $user->applyVVIP_getData()->created_at != ''))
                            <div class="sys_aa" id="vip_state_block">
                                <div class="tabbox_new_dt"><span>VIP狀態</span>
                                    @if(!$user->isVip() && !$user->isVVIP())
                                        @if($user->engroup==2)
                                            <a class="zs_buttonn" href="{{url('/dashboard_img')}}">立即上傳照片</a>
                                        @else
                                            <a class="zs_buttonn" href="{{url('/dashboard/new_vip')}}">立即成為VIP</a>
                                        @endif
                                    @endif
                                </div>
                                <div class="tabbox_new_dd">
                                    @if($user->isVip()||$user->isVVIP())
                                        <h2 class="tabbox_h2">{!! $vipStatus !!}</h2>
                                    @else
                                    <h2 class="tabbox_h2"><span class="tu_dfont">{!! $vipStatus??'您目前還不是VIP' !!}</span></h2>
                                    @endif
                                    @php
                                        $essence_posts_reward_log=\App\Models\EssencePostsRewardLog::where('user_id', $user->id)->get();
                                    @endphp
                                    <h2 class="tabbox_h2 ta_l">
                                        @foreach ($essence_posts_reward_log as $reward_log)
                                            <span class="tu_dfont" style="border-top: #eee 1px dashed;">
                                                您的精華文章 {{$reward_log->title}} 已於 {{ substr($reward_log->verify_time,0,10) }} 通過審核，已贈予本站VIP一個月。
                                            </span>
                                        @endforeach
                                    </h2>
                                </div>
                            </div>
                        @endif
                        @if(isset($user->applyVVIP_getData()->created_at) && $user->applyVVIP_getData()->created_at != '')
                            <div class="sys_aa" id="vip_state_block">
                                <div class="tabbox_new_dt"><span>VVIP狀態</span>
                                    @if($user->applyingVVIP_getDeadline() != 0)
                                        <a class="zs_buttonn right" href="/dashboard/vvipSelectA#refill">VVIP立即補件</a>
                                    @endif
                                </div>
                                <div class="tabbox_new_dd">
                                    @if($user->applyingVVIP_getDeadline() != 0)
                                        <h2 class="tabbox_h2" style="color:red;">您於 {{$user->applyVVIP_getData()->created_at}} 申請本站VVIP，站方審核仍需更多財力文件，請您於 {{$user->applyingVVIP_getDeadline()}} 前補交文件</h2>
                                    @endif

                                    @if($user->applyVVIP_getData()->status == 2)
                                        <h2 class="tabbox_h2">您於 {{$user->applyVVIP_getData()->created_at}} 申請本站VVIP，未通過站方審核。</h2>

                                    @elseif($user->cancelVVIP())
                                        <h2 class="tabbox_h2">您於 {{$user->applyVVIP_getData()->created_at}} 申請本站VVIP，已取消申請。</h2>
                                    
                                    @elseif($user->valueAddedServiceStatus('VVIP') == 1 && $user->passVVIP())
                                        <h2 class="tabbox_h2">您已完成VVIP會員費</h2>
                                    @elseif($user->valueAddedServiceStatus('VVIP') == 1 && !$user->passVVIP())
                                        <h2 class="tabbox_h2">
                                            <span class="tu_dfont">
                                                @if($user->applyVVIP_getData()->plan == 'VVIP_A')
                                                    您好，您在 {{$user->applyVVIP_getData()->created_at}} 申請 VVIP 已完成，請於 {{$user->applyVVIP_getData()->created_at->addDays(3)}} 之前， <br>
                                                    將本帳號贈與與本站的入會費 20000 元匯入此帳號 <br>
                                                    國泰世華銀行(013) <br>
                                                    帳號015035004430 <br>
                                                    完成後請保留收據並將帳號後五碼 <a onclick="vvipUserNoteEdit_show()" class='btn btn-primary' style="height: 30px; line-height: 15px;">輸入於此</a><br>
                                                    <font color="red">注意：須於 {{$user->applyVVIP_getData()->created_at->addDays(3)}} 之前匯入，否則將取消此次 VVIP 申請。9888元<br>扣除手續費4000，剩餘刷退。</font>
                                                @elseif($user->applyVVIP_getData()->plan == 'VVIP_B')
                                                    您好，您在 {{$user->applyVVIP_getData()->created_at}} 申請 VVIP 已完成，請於 {{$user->applyVVIP_getData()->created_at->addDays(3)}} 之前， <br>
                                                    將本帳號贈與與本站的入會費 50000 元匯入此帳號 <br>
                                                    國泰世華銀行(013) <br>
                                                    帳號015035004430 <br>
                                                    完成後請保留收據並將帳號後五碼 <a onclick="vvipUserNoteEdit_show()" class='btn btn-primary' style="height: 30px; line-height: 15px;">輸入於此</a><br>
                                                    <font color="red">注意：須於 {{$user->applyVVIP_getData()->created_at->addDays(3)}} 之前匯入，否則將取消此次 VVIP 申請。9888元<br>扣除手續費4000，剩餘刷退。</font>
                                                @endif
                                            </span></h2>
                                    @else
                                        <h2 class="tabbox_h2"><span class="tu_dfont">您尚未購買VVIP會員費</span></h2>
                                    @endif

                                    @if($user->applyingVVIP())
                                        <h2 class="tabbox_h2">您於 {{$user->applyVVIP_getData()->created_at}} 申請本站VVIP，目前還在審核中，最慢於五個工作天通知結果。</h2>
                                    @endif

                                    @if($user->passVVIP())
                                        <h2 class="tabbox_h2">
                                            您於 {{$user->applyVVIP_getData()->created_at}} 申請本站VVIP，恭喜您！已成為本站審核通過的高級VVIP會員。現在就加入VVIP專屬LINE@, 享受您的專屬客服服務!
                                            <a href="https://line.me/ti/p/~@953wkgjq" target="_blank"> <img src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png" alt="加入好友" height="26" border="0" style="height: 26px; float: unset;"></a>
                                        </h2>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endif
                    @if($user->engroup==2)
                        <div class="sys_aa" id="adv_auth_state_block">
                            <div class="tabbox_new_dt"><span>進階驗證</span>
                            @if(!$user->isAdvanceAuth())
                                <a class="zs_buttonn" href="{{url('/advance_auth/')}}">立即驗證</a>
                            @endif
                            </div>
                            <div class="tabbox_new_dd">
                            @if($user->isAdvanceAuth())
                                <h2 class="tabbox_h2">已通過</h2>
                            @else
                                <h2 class="tabbox_h2"><span class="tu_dfont">尚未通過</span></h2>
                            @endif
                            </div>
                        </div> 
                        <div class="sys_aa" id="self_auth_state_block">
                            <div class="tabbox_new_dt"><span>本人認證</span>
                                <a class="zs_buttonn" href="{{route('real_auth')}}">
                                @if($rap_service->isNoProgressByAuthTypeId(1))
                                    立即認證
                                @else
                                    檢視認證
                                @endif
                                </a>
                            </div>
                            <div class="tabbox_new_dd">
                            @if($rap_service->isPassedByAuthTypeId(1))
                                <h2 class="tabbox_h2">已通過</h2>
                            @elseif($rap_service->isSelfAuthWaitingCheck())
                                <h2 class="tabbox_h2"><span class="tu_dfont">站方審核中</span></h2>
                            @elseif($rap_service->isSelfAuthApplyNotVideoYet())
                                <h2 class="tabbox_h2"><span class="tu_dfont"><span class="sa_video_status video_status_text_show_elt" id="video_status_text_show_elt">前往視訊</span><img id="video_status_show_elt" src="{{ asset('/new/images/guan.png') }}" class="sa_video_status video_status_show_elt"  style="cursor: pointer;height: 30px;display:none;"/></span></h2>
                            @else
                                <h2 class="tabbox_h2"><span class="tu_dfont">尚未通過</span></h2>
                            @endif
                            </div>
                        </div>         
                    @endif

                   <div class="sys_aa" id="vip_state_block">
                        <div class="tabbox_new_dt"><span>隱藏狀態</span>
                            @if($user->valueAddedServiceStatus('hideOnline') == 1)
                                <a class="zs_buttonn right" href="/dashboard/account_hide_online">變更隱藏狀態</a>
                            @else
                                <a class="zs_buttonn" href="{{url('/dashboard/valueAddedHideOnline')}}" style="font-size: 12px;">立即購買隱藏功能</a>
                            @endif
                        </div>
                        <div class="tabbox_new_dd">
                            @if($user->valueAddedServiceStatus('hideOnline') == 1)
                                <h2 class="tabbox_h2">{!! $vasStatus !!}</h2>
                            @else
                                <h2 class="tabbox_h2"><span class="tu_dfont">您尚未購買隱藏付費功能</span></h2>
                            @endif
                        </div>
                    </div>                    
                    <div class="sys_aa">
                        <div class="tabbox_new_dt"><span>Line通知設定</span></div>
                        <div class="tabbox_new_dd">
                            @php
                                if($user->line_notify_token==null){
                                    $line_status='您目前尚未綁定Line通知';
                                    $line_action='line_notify';
                                    $pic_status='/new/images/guan.png';
                                }else{
                                    $line_status='綁定中,';
                                    $line_action='line_notify_cancel';
                                    $pic_status='/new/images/kai.png';

                                }
                            @endphp
                            <h2 class="tabbox_h2">{{$line_status}}
                                @if($user->line_notify_token!==null)
                                    <a href="/dashboard/chat/chatNotice"><i style=" color: #fd5678; font-style: normal;">點此可設定</i></a>
                                @endif
                                <img src="{{ $pic_status }}" class="right {{ $line_action }}" id="caocao_pic" style=" height: 30px;cursor: pointer;"/>
                            </h2>
                        </div>
                    </div>


                    <!-- js 控制 展开 隐藏div -->
                    <script>
                        function change_pic() {
                            var imgObj = document.getElementById("caocao_pic");
                            if (imgObj.getAttribute("src", 2) == "/new/images/kai.png") {
                                imgObj.src = "/new/images/guan.png";
                            } else {
                                imgObj.src = "/new/images/kai.png";
                            }
                        }
                    </script>
                    <!-- js 控制 展开 隐藏div -->
                    <div class="one_row_sys_aa">
                        <div class="sys_aa sys_aa_first">
                            <div class="tabbox_new_dt"><span>收件夾通訊人數</span></div>
                            <div class="tabbox_new_dd">
                                <h2 class="tabbox_hsz">@if(empty($msgMemberCount))0 @else{{$msgMemberCount}}@endif</h2>
                            </div>
                        </div>
                        <div class="sys_aa  sys_aa_last">
                            <div class="tabbox_new_dt"><span>收件夾總訊息數</span></div>
                            <div class="tabbox_new_dd">
                                <h2 class="tabbox_hsz">@if(empty($allMessage))0 @else{{$allMessage}}@endif</h2>
                            </div>
                        </div>
                    </div>

                    @if($isBannedStatus != '')
                    <div class="sys_aa">
                        <div class="tabbox_new_dt"><span>封鎖通知</span></div>
                        <div class="tabbox_new_dd">
                            <h2 class="tabbox_h3">{!! $isBannedStatus !!}</h2>
                        </div>
                    </div>
                    @endif

                    @if($adminWarnedStatus != '' || $isWarnedStatus != '')
                    <div class="sys_aa">
                        <div class="tabbox_new_dt"><span>警示通知</span></div>
                        <div class="tabbox_new_dd">
                            @if($adminWarnedStatus!='')
                                <h2 class="tabbox_h3">{!! $adminWarnedStatus !!}</h2>
                            @endif
                            @if($isWarnedStatus!='')
                                <h2 class="tabbox_h3">{!! $isWarnedStatus !!}</h2>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="sys_aa">
                        <div class="tabbox_new_dt"><span>當月網站管理狀況</span></div>
                        <div class="tabbox_new_dd">
                            <h2 class="tabbox_h3">
                                <li><span><img src="/new/images/zs_js_1.png" class="he_img"></span><font>封鎖人數<i>{{$bannedCount}}人</i></font></li>
                                <li><span><img src="/new/images/zs_js_2.png" class="he_img"></span><font>警示人數<i>{{$warnedCount}}人</i></font></li>
                            </h2>
                        </div>
                    </div>

                    @if($forum->deleted_at ?? false)
                        <div class="sys_aa">
                            <dt class="tabbox_new_dt"><span>專屬討論區</span></dt>
                            <dd class="tabbox_new_dd">
                                <div class="tabbox_h4">
                                    您的專屬討論區因沒有完成每週需求量（一個新的主題或三條以上的回覆），已於 {{$forum->deleted_at->toDateString()}} 關閉，若要重新申請須至 {{$forum->deleted_at->addYear()->toDateString()}} 提出
                                </div>
                            </dd>
                        </div>
                    @endif

                    <div class="sys_aa">
                        <dt class="tabbox_new_ss"><span class="sys_log1 xs_wi90 open_reportedRecord">檢舉處理狀況與其他</span>
                            @if($reportedStatus)
                            <div class="right btn01"><span class="zixu_cs"><img src="/new/images/xiugai1.png">編輯</span></div>
                            <div class="btn02 sx_ment">
                                <span class="iconfont icon-wancheng zixu_cs1 dtmr20">完成</span>
                                <span class="iconfont icon-shanchu zixu_cs1">刪除</span>
                                <label class="iconfont icon-kuang zixu_cs2"  style="margin-top: 2px; margin-right: 3px; float:right ;"><input type="checkbox" class="qxuan">全選</label>
                            </div>
                            @endif
                        </dt>
                        <dd class="tabbox_new_dd">
                            @if($reportedStatus)
                                <div class="tabbox_h3">
                                    <table class="tab_jianju">
                                        <tbody>
                                        <tr>
                                            <td width="46%"><span>檢舉記錄</span></td>
                                            <td width="46%"><span>處理情形</span></td>
                                        </tr>
                                        @foreach($reportedStatus as $row)
                                            <tr data-recordtype="reportedRecord"  data-rid="{{$row['id']}}"  data-reportedType="{{$row['reported_type']}}">
                                                <td style="font-size: 15px;color: #999999; word-break: break-word;">{!! $row['content'] !!}</td>
                                                <td style="font-size: 15px;color: #999999; word-break: break-word;">{!! $row['status'] !!}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @elseif(!($admin_msgs_sys??null) || !count($admin_msgs_sys))
                                <h2 class="tabbox_h3">
                                    <img src="/new/images/wuziliao_aaa.png" class="ta_img">
                                    <div class="ta_divfont">暫無紀錄</div>
                                </h2>
                            @endif
                            @if((isset($admin_msgs_sys) && count($admin_msgs_sys)) || $reportedStatus )
                            <div class="tabbox_h3 sys_remind">
                                <div class="tabbox_new_dt tabbox_new_ss"><span>其他</span>
                                @if(isset($admin_msgs_sys) && count($admin_msgs_sys))
                                <div class="right btn01 btn_admin_msgs">{{--<span class="zixu_cs"></span>--}}</div>
                                <div class="btn02 sx_ment fr_nbj">
                                </div>
                                @endif
                                </div>
                                <div class="tabbox_new_dd">
                                @if(isset($admin_msgs_sys) && count($admin_msgs_sys))
                                    @foreach($admin_msgs_sys as $amsg)
                                    <h2 class="tabbox_h2 ta_l no_edit"  data-recordtype="admin_msgs" data-rowid="{{$amsg->id}}" >
                                        <span class="tu_dfont">
                                        {{ strip_tags($amsg->content)}}
                                        </span>
                                        <a class="zs_buttonn1 right"  href="{{route("adminMsgPage")}}">前往查看</a>
                                    </h2>
                                    @endforeach
                                @elseif($reportedStatus)
                                    <h2 class="tabbox_h2 ta_l"><span class="tu_dfont">暫無系統信</span></h2>
                                @endif
                                </div>
                            </div> 
                            @endif
                        </dd>
                    </div>

                    <div class="sys_aa">
                        <div class="tabbox_new_ss"><span  class="sys_log1 xs_wi90">評價紀錄</span>
                            @if($evaluation_30days->count()>0)
                            <div class="right btn01"><span class="zixu_cs"><img src="/new/images/xiugai1.png">編輯</span></div>
                            <div class="btn02 sx_ment">
                                <span class="iconfont icon-wancheng zixu_cs1 dtmr20">完成</span>
                                <span class="iconfont icon-shanchu zixu_cs1">刪除</span>
                                <label class="iconfont icon-kuang zixu_cs2"  style="margin-top: 2px; margin-right: 3px; float:right ;"><input type="checkbox" class="qxuan">全選</label>
                            </div>
                            @endif
                        </div>
                        <dd class="tabbox_new_dd">
                            @if($evaluation_30days->count()>0)
                                <div class="tabbox_h4">
                                    <div class="ys_dt">僅顯示30天內的評價</div>
                                    <ul>
                                        @foreach($evaluation_30days as $evaluation)
                                        <li data-recordtype="evaluationRecord" data-rowid="{{ $evaluation->id }}">
                                            <h2>
                                                <span>會員暱稱</span>
                                                @if( is_null($evaluation->blocked_id))
                                                    <font><a href="/dashboard/viewuser/{{$user->id}}#hash_evaluation">{{ $evaluation->name }}</a></font>
                                                @else
                                                    <font><a href="/dashboard/viewuser/{{$user->id}}#hash_evaluation"><span class="red"> [此評價來自封鎖的會員]</span></a></font>
                                                @endif
                                            </h2>
                                            <h2><span>評價時間</span><font>{{ $evaluation->created_at }}</font></h2>
                                            <h2><span>回覆本評價</span><font><a class="zs_buttonn1" onclick="see_evaluation_popup();">點此查看</a></font></h2>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <h2 class="tabbox_h3">
                                    <img src="/new/images/wuziliao_aaa.png" class="ta_img">
                                    <div class="ta_divfont">暫無紀錄</div>
                                </h2>
                            @endif
                        </dd>
                    </div>

                    <div class="sys_aa">
                        <dt class="tabbox_new_ss"><span  class="sys_log1 xs_wi90">我收藏的會員上線</span>
                            @if($myFav->count()>0 && ($user->isVip()||$user->isVVIP()))
                            <div class="right btn01"><span class="zixu_cs"><img src="/new/images/xiugai1.png">編輯</span></div>
                            <div class="btn02 sx_ment">
                                <span class="iconfont icon-wancheng zixu_cs1 dtmr20">完成</span>
                                <span class="iconfont icon-shanchu zixu_cs1">刪除</span>
                                <label class="iconfont icon-kuang zixu_cs2"  style="margin-top: 2px; margin-right: 3px; float:right ;"><input type="checkbox" class="qxuan">全選</label>
                            </div>
                            @endif
                        </dt>
                        <dd class="tabbox_new_dd">
                            <div class="tabbox_h4">
                                @if($user->isVip()||$user->isVVIP())
                                    @if($myFav->count()>0)
                                        <div class="ys_dt">僅顯示一周內上線的會員</div>
                                        <ul>
                                            @foreach($myFav as $row)
                                                <li data-recordtype="myFavRecord" data-rowid="{{ $row->rowid }}">
                                                    <h2><span>會員暱稱</span><font><a href="{{ url('/dashboard/viewuser/' . $row->member_fav_id) }}">{{$row->name}}</a></font></h2>
                                                    <h2><span>會員標題</span><font class="xss_he">{{$row->title}}</font></h2>
                                                    <h2><span>最後上線時間</span><font>{{ substr($row->last_login,0,16)}}</font></h2>
                                                    <h2><span>是否來看過我</span><font>@if($row->vid !='')是，@if($row->is_hide_online==1 || $row->is_hide_online==2){{substr($row->last_login,0,16)}}@else{{substr($row->visited_created_at,0,16)}}@endif @else 否 @endif</font></h2>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <h2 class="tabbox_h3">
                                            <img src="/new/images/wuziliao_aaa.png" class="ta_img">
                                            <div class="ta_divfont">暫無紀錄</div>
                                        </h2>
                                    @endif
                                @else
                                    <div class="select_xx03">此功能僅開放給VIP<a class="zs_buttonn" href="{{url('/dashboard/new_vip')}}">立即成為VIP</a></div>
                                @endif
                            </div>
                        </dd>
                    </div>

                    <div class="sys_aa">
                        <dt class="tabbox_new_ss"><span class="sys_log1 xs_wi90">收藏我的會員上線</span>
                            @if($otherFav->count()>0 && ($user->isVip()||$user->isVVIP()))
                            <div class="right btn01"><span class="zixu_cs"><img src="/new/images/xiugai1.png">編輯</span></div>
                            <div class="btn02 sx_ment">
                                <span class="iconfont icon-wancheng zixu_cs1 dtmr20">完成</span>
                                <span class="iconfont icon-shanchu zixu_cs1">刪除</span>
                                <label class="iconfont icon-kuang zixu_cs2"  style="margin-top: 2px; margin-right: 3px; float:right ;"><input type="checkbox" class="qxuan">全選</label>
                            </div>
                            @endif
                        </dt>
                        <dd class="tabbox_new_dd">
                            <div class="tabbox_h4">
                                @if($user->isVip()||$user->isVVIP())
                                    @if($otherFav->count()>0)
                                        <div class="ys_dt">僅顯示一周內上線的會員</div>
                                        <ul>
                                            @foreach($otherFav as $row)
                                                <li data-recordtype="myFavRecord2" data-rowid="{{ $row->rowid }}">
                                                    <h2><span>會員暱稱</span><font><a href="{{ url('/dashboard/viewuser/' . $row->member_id) }}">{{$row->name}}</a></font></h2>
                                                    <h2><span>會員標題</span><font class="xss_he">{{$row->title}}</font></h2>
                                                    <h2><span>最後上線時間</span><font>{{ substr($row->last_login,0,16)}}</font></h2>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <h2 class="tabbox_h3">
                                            <img src="/new/images/wuziliao_aaa.png" class="ta_img">
                                            <div class="ta_divfont">暫無紀錄</div>
                                        </h2>
                                    @endif
                                @else
                                    <div class="select_xx03">此功能僅開放給VIP<a class="zs_buttonn" href="{{url('/dashboard/new_vip')}}">立即成為VIP</a></div>
                                @endif
                            </div>
                        </dd>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="evaluation_bg" onclick="gmBtnNoReload()" style="display:none;"></div>
    <div class="bl bl_tab" id="see_evaluation_tab" style="display: none;">
        <div class="bltitle">提示</div>
        <div class="n_blnr01 matop10">
            <div class="blnr bltext">若要回覆本評價，<a style="color: red;" href="/dashboard/viewuser/{{$user->id}}#hash_evaluation">請點此</a></div>
            <a class="n_bllbut matop30" onclick="see_evaluation_tab_close()">關閉</a>
        </div>
        <a onclick="see_evaluation_tab_close();" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>

    <div class="bl bl_tab" id="show_new_evalucation_popup" style="display: none;">
        <div class="bltitle">提示</div>
        <div class="n_blnr01">
            <div class="blnr bltext">您有新評價。 <a style="color: red;" href="/dashboard/viewuser/{{$user->id}}#hash_evaluation">若要查看請點此</a></div>

            <div class="n_bbutton">
                <span><a class="n_left" onclick="noticeAlert_close();">不再提醒</a></span>
                <span><a onclick="new_evaluation_popup_close();" class="n_right" href="javascript:">關閉</a></span>
            </div>
        </div>
        <a onclick="new_evaluation_popup_close();" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>

    <div class="bl_tab_aa link" id="lineNotifyPopUp" style="display: none;">
        <div class="bl_tab_bb">
            <div class="bltitle"><span style="text-align: center; float: none;">用LINE接收讯息</span></div>
            <div class="new_poptk new_npop">
                <div class="dongt_fb mabot20">
                    <div class="pj_add_a">
                        <div class="li_span">
                            <span>透過LINE即時接收聊天與通知訊息，</span>
                            <span>且對方不會知道您的LINE賬號</span>
                        </div>
                        <img src="/new/images/LINE_T.png" class="line_img">
                    </div>
                    <div class="n_bbutton" style="margin-top: 0;">
                        @if($user->isVip()||$user->isVVIP())
                            <a class="sl_bllbut" href="/dashboard/chat/chatNotice">好，我想即時接收聊天訊息</a>
                        @else
                            <a class="sl_bllbut" onclick="lineNotifyPopUp_close();show_onlyForVipPleaseUpgrade();">好，我想即時接收聊天訊息</a>
                        @endif
                        <a class="sl_bllbut01" onclick="lineNotifyPopUp_close()">不想即時收到訊息</a>
                    </div>
                </div>
            </div>
            <a onclick="lineNotifyPopUp_close()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
        </div>
    </div>
    @php session()->put('alreadyPopUp_lineNotify', $user->line_notify_alert.'_Y') @endphp
    
    @if($isForceShowFaqPopup)
    <div class="faq_announce_bg" id="faq_announce_bg" onclick="leave_faq_msg()"></div>
    <div class="bl bl_tab" id="faq_msg_tab" style="display:none;">
        <div class="bltitle">提示</div>
        <div class="n_blnr01 matop10">
        <div class="blnr bltext">恭喜答對！</div>
        <a class="n_bllbut matop30" onclick="leave_faq_msg()">確定</a>
        </div>
        <a id="" onclick="gmBtnNoReload();leave_faq_msg();" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>    
    <div class="faq_blbg"></div>
    <div class="bl_tab dati" id="faq_tab" style=" display: none;">
        <div class="dati_tit">常見問題</div>
        <a id="" class="gub_cld"><img src="{{asset('new/images/cc_02.png')}}"></a>
        <div class="dati_text"><img src="{{asset('new/images/cc_03.png')}}">
        該部分共{{count($faqPopupQuestionList)}}題

        </div>

        <div class="gudont">
            <div class="ga_d"><span>&nbsp;</span></div>
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    @foreach($faqPopupQuestionList as $question_entry)
                    <div class="swiper-slide">

                             <div class="dati_font">
                                <h2>{{$question_entry->question??null}}{{$faqUserService->faq_service()->isCustomChoiceByQuEntry($question_entry)?'('.$question_entry->type.')':''}}</h2>
                                <div>
                                @if($faqUserService->isWrongReplyedQuByEntry($question_entry))
                                    <div class="wifontext">正確答案：<br>●{{$faqUserService->getAnsFillerByWrongReQuEntry($question_entry)}}</div>
                                @else
                                    <form>
                                        @if($faqUserService->questionTypeToKey($question_entry->type)==2)
                                        <div><input type="text" class="faq_replace_required_elt" required oninvalid="this.setCustomValidity('請選取選項')"  oninput="this.setCustomValidity('')"></div>
                                        @endif
                                        <input type="hidden" name="question_id" value="{{$question_entry->id}}" />                               
                                        <ul class="dowebok answer_item">        
                                         @include('new.dashboard.faq_question_tpl_'.$faqUserService->questionTypeToKey($question_entry->type))
                                        </ul>
                                    </form>
                                    <img src="{{asset('new/owlcarousel/assets/ajax-loader.gif')}}" class="loading-in-slide" >
                                @endif
                                </div>
                            </div>
                     </div>
                    @endforeach

                    <div id="faq_count_down_block" class="cjwt" style="font-size: 14px;color: #333333;text-align:center;">
                        <span></span>
                        秒後自動離開
                    </div>
                </div>         
            </div>
             @if(count($faqPopupQuestionList)>1 || (count($faqPopupQuestionList)==1 && !$faqCountDownStartTime))
             <div class="gub_table">
                 <div class="swiper-button-next" ></div>
                 <div class="swiper-button-prev"></div>
                 <div class="swiper-pagination"></div>
             </div>
             @endif
         </div>
    </div>
    @endif
    @if($rap_service->isSelfAuthApplyNotVideoYet())
        <div style="position:relative;" id="video_app_container">
            <div id="app" style="display: none;">
                <video-chat 
                    :allusers="{{ $users }}" 
                    :authUserId="{{ auth()->id() }}" 
                    user_permission = "normal"
                    ice_server_json="" 
                />
                
            </div>
        </div>
    @endif

    @if(isset($user->applyVVIP_getData()->created_at) && $user->applyVVIP_getData()->created_at != '')
        <div class="bl bl_tab" id="show_vvip_user_note">
            <div class="bltitle"><span>回報末五碼</span></div>
            <div class="n_blnr01 ">
                <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url('/dashboard/vvipUserNoteEdit') }}" id="">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                    <input type="hidden" name="id" id="id" value="{{ $user->applyVVIP_getData()->id }}">
                    <textarea onkeyup="value=value.replace(/[^\d]/g,'') " name="user_note" id="user_note" cols="" rows="" class="n_nutext" placeholder="請輸入內容" required></textarea>
                    <input type="submit" class="n_bllbut msgsnd" value="送出" style="border-style: none;">
                </form>
            </div>
            <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
        </div>
    @endif

@stop

@section('javascript')
<script type="text/javascript">

    function vvipUserNoteEdit_show() {
        $('#show_vvip_user_note').show();
        $('.announce_bg').show();

    }

    $(function() {
        $('#user_note').keypress(function(e) {
            var a = [];
            var k = e.which;

            for (i = 48; i < 58; i++)
                a.push(i);

            if (!(a.indexOf(k)>=0))
                e.preventDefault();
        });

        $(".sys_log1").parent('.tabbox_new_ss').next('dd').hide();
        $(".btn01").hide();
        $(".btn02").hide();

        var td_head='<td width="8%" class="w8"></td>';
        var td_check=' <td class="cent"><input type="checkbox"></td>';
        var li_check='<span class="check"><input type="checkbox"></span>';


        $('.sys_log1').click(function() {
            $(this).parent('.tabbox_new_ss').toggleClass('on');
            $(this).parent('.tabbox_new_ss').next('dd').slideToggle();
            var btn_edit= $(this).next('.btn01');
            var btn2 = $(this).siblings(".btn02");
            if(btn_edit.is(':visible')){
                btn_edit.hide();
                $('.tab_jianju tr:first .w8').remove();
                $('.tab_jianju tr:gt(0) .cent').remove();
            }else if(btn_edit.is(':visible') || btn2.is(':visible')){
                btn_edit.hide();
                btn2.hide();
                $('.tab_jianju tr:first .w8').remove();
                $('.tab_jianju tr:gt(0) .cent').remove();
            }else{
                btn_edit.show();
            }
        });
        //編輯
        $(".btn01").click(function(){
            $(this).hide();
            $(this).next(".btn02").show();
            var tab = $(this).closest('.sys_aa').find('.tab_jianju');
			var tu_dfont = $(this).closest('.sys_aa').find('.tu_dfont');
            if(tab.length > 0){
                $(this).closest('.sys_aa').find('.tab_jianju tr:first').prepend(td_head);
                $(this).closest('.sys_aa').find('.tab_jianju tr:gt(0)').prepend(td_check);
            }
            if(tu_dfont.length > 0){
				$(this).closest('.sys_aa').find('.tabbox_h2:not(.no_edit)').prepend(li_check);
				$(this).closest('.sys_aa').find('.zs_buttonn1').hide();
            }			
            var li_one = $(this).closest('.sys_aa').find('.tabbox_h4 li');
            if(li_one.length > 0){
                li_one.prepend(li_check);
            }
            var list =  $(this).closest('.sys_aa').find('.tabbox_h4');
            if(list.length > 0){
                list.addClass('edit');
            }

        });
        //全選/全不选
        $('.qxuan').on('click',function () {
            $(this).parent().toggleClass('zixu_on');
            $(this).parent().toggleClass('on');
            $(this).closest('.sys_aa').find(':checkbox').prop('checked',$(this).prop('checked'));
        });

        //刪除
        $('.icon-shanchu').click(function () {
            $(this).addClass('zixu_on');
            // getCheckBoxVal();
            var chk_value = [];
            var recordType='';//$(this).parent().parent().attr('data-recordtype');
            var top_block = $(this).closest('.sys_aa');
            var sys_remind = top_block.hasClass('sys_remind');
            
            var tab = top_block.find('.tab_jianju');
            if(tab.length > 0){
                tab.find('input[type=checkbox]:checked').each(function(){ //遍历，将所有选中的值放到数组中
                    // $(this).parent().parent().addClass('emma2');
                    recordType=$(this).parent().parent().attr('data-recordtype');
                    var rowid=$(this).parent().parent().attr('data-rid');
                    var reportedType=$(this).parent().parent().attr('data-reportedType');
                    chk_value.push(rowid+'_'+reportedType);
                    //$(this).closest('tr').remove();
                });
                // alert(recordType+'  ,  '+JSON.stringify(chk_value));
                if(chk_value.length==0){
                    c5('你還沒有選擇任何內容！');
                }else{
                    c8('即將刪除'+chk_value.length+'條信息' );
                    $(".n_left").on('click', function() {
                        $.post('{{ route('personalPageHideRecordLog') }}', {
                            type: recordType,
                            deleteItems: chk_value,
                            user_id: '{{ $user->id }}',
                            _token: '{{ csrf_token() }}'
                        });
                        $("#tab08").hide();
                        $(".announce_bg").hide();

                        tab.find('input[type=checkbox]:checked').each(function(){
                            $(this).closest('tr').remove();
							$(this).closest('h2').remove();
                        });
                        c5('刪除成功');
                    });
                }
                //c5(chk_value.length==0 ?'你还没有选择任何内容！': '即将刪除'+chk_value.length+'条信息' );
            }
            var list =  $(this).closest('.sys_aa').find('.tabbox_h4');
			if(list.length == 0) list =  $(this).closest('.sys_aa').find('.tabbox_h2');
            if(list.length > 0){
                list.find('input[type=checkbox]:checked').each(function(){ //遍历，将所有选中的值放到数组中
                    //$(this).parent().parent().addClass('emma');
                    var rowid=$(this).parent().parent().attr('data-rowid');
					if(rowid.length==0)  rowid=$(this).parent().attr('data-rowid');
                    recordType=$(this).parent().parent().attr('data-recordtype');
					if(recordType.length==0) recordType=$(this).parent().attr('data-recordtype');
                    chk_value.push(rowid);
                    //$(this).closest('li').remove();
                });
                // alert(recordType+'  ,  '+JSON.stringify(chk_value));
                // c5(chk_value.length==0 ?'你还没有选择任何内容！': '即将刪除'+chk_value.length+'条信息' );

                if(chk_value.length==0){
                    c5('你還沒有選擇任何內容！');
                }else{
                    c8('即將刪除'+chk_value.length+'條信息' );
                    $(".n_left").on('click', function() {
                        $.post('{{ route('personalPageHideRecordLog') }}', {
                            type: recordType,
                            deleteItems: chk_value,
                            sys_remind:sys_remind?1:0,
                            user_id: '{{ $user->id }}',
                            _token: '{{ csrf_token() }}'
                        },function(data) {
							if(data!='false') {
								if(recordType=='admin_msgs') {
									if(typeof(data)=='string') dataArr = $.parseJSON(data);
									msgs_container = list.parent();
									msgs_container.find('h2').remove();
									msgs_dom_st = '';
									for (var i = 0; i < dataArr.length; i++) {   
										msgs_dom_st+='<h2 class="tabbox_h2 ta_l"  data-recordtype="admin_msgs" data-rowid="'+dataArr[i]["id"]+'" >'
												+'<span class="check"><input type="checkbox"></span>'
												+'<span class="tu_dfont">'
												+dataArr[i]["content"]
												+'</span>{{--<a class="zs_buttonn1 right" style="display:none;"  href="{{route("chat2WithUser",$admin->id)}}">前往查看</a>--}}</h2>';										
										
									}  	
									msgs_container.html(msgs_dom_st);
								}
							}	
						});
                        $("#tab08").hide();
                        $(".announce_bg").hide();

                        list.find('input[type=checkbox]:checked').each(function(){
                            $(this).closest('li').remove();							
                        });
                        c5('刪除成功');
                    });
                }
            }
        });
        //完成
        $('.icon-wancheng').click(function () {
            $(this).addClass('zixu_on');
            $(this).parent().hide();
            $(this).parent().prev(".btn01").show();
            var tab = $(this).closest('.sys_aa').find('.tab_jianju');
			var tu_dfont = $(this).closest('.sys_aa').find('.tu_dfont');
            if(tab.length > 0){
                $(this).closest('.sys_aa').find('.tab_jianju tr:first .w8').remove();
                $(this).closest('.sys_aa').find('.tab_jianju tr:gt(0) .cent').remove();
            }
            if(tu_dfont.length > 0){
				$(this).closest('.sys_aa').find('.check').remove();
				$(this).closest('.sys_aa').find('.zs_buttonn1').show();
            }			
            var li_one = $(this).closest('.sys_aa').find('.tabbox_h4 li');
            if(li_one.length > 0){
                li_one.find('.check').remove();
            }
            var list =  $(this).closest('.sys_aa').find('.tabbox_h4');
            if(list.length > 0){
                list.removeClass('edit');
            }
        });

        //tabbox_new_ss一整條都要可以展開、收起
        $('.tabbox_new_ss').click(function() {
            var sub_width=0;
            if($(this).find('.btn01').css('display') == 'block'){
                sub_width=95;
            }
            if($(this).find('.btn02').css('display') == 'block'){
                sub_width=220;
            }
            $(this).find('.sys_log1').css( { width: 'calc(100% - ' + sub_width + 'px)' } );
        });

        //顯示檢舉處理狀況
        var hasReportedRecord='{{((isset($admin_msgs_sys) && count($admin_msgs_sys)) || $reportedStatus) ? 1 : 0}}';
        if(hasReportedRecord==1){
            $('.open_reportedRecord').click();
        }
    });

</script>
<script>

    $(".reportDelete").on('click', function() {
        var table=$(this).data("table");
        var id=$(this).data("rid");
        if($(this).data("table").length > 0){
            c4('確定要刪除嗎?');
            $(".n_left").on('click', function() {
                $.post('{{ route('report_delete') }}', {
                    // table: table,
                    id: id,
                    uid: '{{$user->id}}',
                    _token: '{{ csrf_token() }}'
                }, function (data) {
                    var obj = JSON.parse(data);
                    // alert(obj.save);
                    $("#tab04").hide();
                    if(obj.save == 'ok') {
                        ccc('紀錄已刪除');
                        $(".n_bllbut_tab_other").on('click', function() {
                            $(".blbg").hide();
                            $(".bl").hide();
                            $(".gg_tab").hide();
                            window.location.reload();
                        });
                    }
                });
            });
        }
    });

    var announcePopUp ='{{$announcePopUp}}';
    var showLineNotifyPop='{{ $showLineNotifyPop }}';
    if(announcePopUp == 'N'){
        if(showLineNotifyPop){
            lineNotifyPopUp();
        }
    }else{

        //popup評價通知優先高於公告
        @if($evaluation_30days_unread_count && $user->notice_has_new_evaluation)
            $('#announcement').hide();
            $('.announce_bg').hide();
            $('#show_new_evalucation_popup').show();
            $(".evaluation_bg").show();
            $("#show_new_evalucation_popup, .evaluation_bg").on('click', function() {

                if($('#show_new_evalucation_popup').css('display') == 'none'){
                    $('#announcement').show();
                    $('.announce_bg').show();
                    $(".evaluation_bg").hide();
                }
            });
        @endif
        $("#announcement, .gg_butnew, .announce_bg").on('click', function() {
            if($('#announcement').css('display') == 'none'){
                if(showLineNotifyPop){
                   lineNotifyPopUp();
                }
            }
        });
    }

    $("#announce_bg").on('click', function() {
        lineNotifyPopUp_close();
    });
    function lineNotifyPopUp() {
        $("#lineNotifyPopUp").show();
        $("#announce_bg").show();
        $('body').css("overflow", "hidden");
    }
    function lineNotifyPopUp_close() {
        $('#lineNotifyPopUp').hide();
        $("#announce_bg").hide();
        $('body').css("overflow", "auto");
    }

    $(".line_notify").on('click', function() {
        @if($user->isVip()||$user->isVVIP())
        show_line_notify_set_alert();
        @else
            show_onlyForVipPleaseUpgrade();
        @endif

        $(".n_bllbut").on('click', function() {
            var lineClientId = '{{config('line.line_notify.client_id')}}';
            var callbackUrl = '{{config('line.line_notify.callback_url')}}';
            var URL = '{{config('line.line_notify.authorize_url')}}?';
            URL += 'response_type=code';
            URL += '&client_id='+lineClientId;
            URL += '&redirect_uri='+callbackUrl;
            URL += '&scope=notify';
            URL += '&state={{csrf_token()}}';
            URL += '&response_mode=form_post';
            window.open(URL, '_blank');
        });
    });

    $(".line_notify_cancel").on('click', function() {
        c4('確定要解除LINE綁定通知嗎?');
        var URL = '{{route('lineNotifyCancel')}}';
        $(".n_left").on('click', function() {
            $("#tab04").hide();
            $(".blbg").hide();
            window.location.href = URL;
        });
    });

    @if (isset($errors) && $errors->count() > 0)
        @foreach ($errors->all() as $error)
            c5('{{ $error }}');
        @endforeach
    @endif

    @if (Session::has('message') && Session::get('message')=="此用戶已關閉資料。")
        ccc('{{Session::get('message')}}');
    @elseif(Session::has('message'))
        c5('{{Session::get('message')}}');
    @endif

    function new_evaluation_popup_close() {
        $('#show_new_evalucation_popup').hide();
        $("#evaluation_bg").hide();
    }

    function see_evaluation_popup() {
        $('#see_evaluation_tab').show();
        // $("#blbg").show();
        $(".announce_bg").show();
    }

    function see_evaluation_tab_close() {
        $('#see_evaluation_tab').hide();
        // $("#blbg").hide();
        $(".announce_bg").hide();
    }

    function noticeAlert_close() {
        $.ajax({
            type: 'POST',
            url: '{{ route('closeNoticeNewEvaluation') }}?{{csrf_token()}}={{now()->timestamp}}',
            data: { id : "{{ $user->id }}", _token:"{{ csrf_token() }}"},
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
        $('#show_new_evalucation_popup').hide();
        $("#evaluation_bg").hide();
    }

</script>
<script type="text/javascript">
    $(function() {
		@if(isset($admin_msgs) && count($admin_msgs))
		    $('.btn_admin_msgs').show();
		@endif
        
        $('.sa_video_status').click(function(){
            location.href="{{url('user_video_chat_verify')}}";
        });        
	});

</script>
@if($isForceShowFaqPopup) 
<link rel="stylesheet" href="{{asset('new/css/jquery-labelauty.css')}}">
<style>
@media (max-width:823px){
#faq_tab { width:60%; left: 20%;}
}	
@media (max-width:568px){
#faq_tab { top:2%;}
}
@media (max-width:450px){
#faq_tab { width:90%; left:5%; top:15%;}
}
</style>
<style>
.swiper-button-next, .swiper-button-prev{position: inherit;top: inherit;bottom: inherit; width:40px;height: 40px;background-size: 40px 40px;margin-top: 0;}
.swiper-button-next, .swiper-container-rtl .swiper-button-prev {background-image: url({{asset('new/images/cc_04.png')}}); right: inherit;left: inherit; outline: none;float: right; cursor: pointer; display: table;}
.swiper-button-prev, .swiper-container-rtl .swiper-button-next {background-image: url({{asset('new/images/cc_05.png')}}); right: inherit;left: inherit; outline: none;;right: auto;float: left; cursor: pointer;display: table;}
.swiper-pagination { z-index: -7;}
.swiper-pagination-current{color: #ff7591;}
.swiper-container {width: 100%;height: auto;margin: 0 auto;}
.swiper-slide {text-align: center;font-size: 18px;background:transparent;display: -webkit-box;display: -ms-flexbox;display: -webkit-flex;
display: flex;-webkit-box-pack: center;-ms-flex-pack: center;-webkit-justify-content: center;justify-content: center;-webkit-box-align: center;
-ms-flex-align: center;-webkit-align-items: center;align-items: center;}
</style>
  <style>
    #faq_tab {z-index:20;}
    #faq_tab *::-webkit-scrollbar {width: 0;height: 0;}
    #faq_tab .swiper-button-next,#faq_tab .swiper-button-prev,#faq_tab .swiper-pagination {display:table-cell;} 
    .faq_blbg,.faq_announce_bg {width:100% !important; height:100% !important;width: 100%;height: 100%;position: fixed;top: 0px;left: 0;background: rgba(0,0,0,0.5);z-index: 19;display:none;}
    #faq_tab .dati_font div form {display:none;}
    #faq_tab .dati_font > div > p {font-size:16px;}
    #faq_count_down_block {display:none;text-align:right;margin-top:10px;}
    #faq_tab .force_show {display:inline !important;}
    .faq_replace_required_elt {width:0;height:0;position:relative;top:45px;color:transparent;border:0px transparent;background-color:transparent;}    
    #faq_tab ul li input[type=radio]:focus, #faq_tab ul li input[type=radio]:focus-visible,.faq_replace_required_elt:focus,.faq_replace_required_elt:focus-visible {outline:none;}
    #faq_announce_bg,#faq_msg_tab {z-index:19;display:none;}
  </style>
<script src="{{asset('new/js/swiper.min.4.4.6.js')}}"></script>
<script src="{{asset('new/js/jquery-labelauty.js')}}"></script>
<script src="{{asset('new/js/is_logout_respose.js')}}"></script>
<script>
    @if(!$faqCountDownStartTime)    
        $('#faq_tab .swiper-wrapper .swiper-slide').last().after('<div class="swiper-slide"><div class="dati_font"><img src="{{asset('new/owlcarousel/assets/ajax-loader.gif')}}"></div></div>');
    @elseif($isFaqDuringCountDown)     
        faq_count_down({{$faqCountDownSeconds}});
    @endif
    if(get_faq_error_state()==1) {
        wdt();
    }  
    else {
        $('body').css("overflow", "hidden");
        $(".faq_blbg").show();      
        $(':input').labelauty();        
    }
    
    faq_add_checking_flag($('#faq_tab .swiper-wrapper .swiper-slide').first());
    
    function show_active_slide_form() {
        var faq_tab_elt = $('#faq_tab');
        var now_slide_elt = faq_tab_elt.find('.swiper-container .swiper-slide-active');
        var now_slide_form_elt = now_slide_elt.find('form');
        if(now_slide_form_elt.length>0) {
            now_slide_form_elt.show();

            if(now_slide_form_elt.find(':disabled').length==0) faq_add_checking_flag(now_slide_elt);

            now_slide_form_elt.next().hide();
        }
        
    }
    
    function faq_prev_step() {
        var now_slide = getFaqActiveSlide();
        var now_slide_form = now_slide.find('form');
        if(now_slide_form.length>0 ) {
            now_slide_form.show().next().hide();
        }
    }
    
    function faq_next_step() {
        @if($faqCountDownStartTime)
            return ;
        @endif
        var nowBlock = getFaqActBlock();
        var nowFormElt = nowBlock.find('form');
        var nextBlock = nowBlock.next();
        var nextFormElt = nextBlock.find('form');

        if(!check_empty()) return;
        
        if(nowBlock.find(':disabled').length>0) {
            faq_show_slide_form(nextFormElt);
            return;
        }        

        if(nowFormElt.length==0 || nowFormElt.find(':disabled').length>0) {
            if(nextBlock.length>0 ) {
                
                if(nextFormElt.length>0) {
                    faq_add_checking_flag(nextBlock);
                    faq_show_slide_form(nextFormElt);
                }
                    
            }
        }

        if(!nowBlock.hasClass('checking')) return;

        if(!nowFormElt.length) {
            return;                    
        }
        
        faq_hide_slide_form(nowFormElt);
        swiper.slidePrev();
        var fdata = new FormData();

        $.each(nowFormElt.serializeArray(), function( index, value ) {
            fdata.append(value.name, value.value);
        });
        fdata.append('_token', '{{ csrf_token() }}');

        $.ajax({
            type: 'POST',
            url: '{{ route("checkFaqAnswer") }}',
            data: fdata,
            dataType:'json',
            contentType: false, 
            processData: false,            
            success: function(data, status, xhr){

                var error_msg = '';
                if(data.error!=undefined || data.exception=='Error') {
                    switch (data.error) {
                        case 'no_question_id':
                            error_msg = '未傳送任何題目資訊，無法處理';
                        break;
                        case 'not_user_question':
                            error_msg = '此題目設定錯誤，無法處理';
                        break;
                        case 'no_answer_setting':
                            error_msg = '此題目尚未設定正解，無法處理';
                        break;
                        default:
                            error_msg = '出現無法處理的未知錯誤，';
                        break;
                            
                    }    
                    error_msg+='<br>點按網頁任一處可離開常見問題';
                    save_faq_error_state();

                     $(".faq_blbg").attr('onclick','wdt()');
                     $("#faq_tab").attr('onclick','wdt()');                
                }
                else if(data.wrong!=undefined) {
                    var answer = data.wrong;
                    ans_list=answer.split("，");
                    error_msg = '<div class="wifontext">正確答案：<br>';
                    for (let i=0; i<ans_list.length; i++) {
                        error_msg += '●'+ans_list[i] + "<br>";
                    }
                    error_msg+='</div><div id="faq_count_down_block" class="cjwt" style="font-size: 14px;color: #333333;text-align:center;">\n' +
                        '                            <span></span>\n' +
                        '                            秒後自動離開\n' +
                        '                        </div>';

                }
                else if(data.text_wrong!=undefined) {
                    var answer = data.text_wrong;
                    ans_list=answer.split("，");
                    error_msg = '<div class="wifontext">正確答案：<br>';
                    for (let i=0; i<ans_list.length; i++) {
                        error_msg += '●'+ans_list[i] + "<br>";
                    }
                    error_msg+='</div><div id="faq_count_down_block" class="cjwt" style="font-size: 14px;color: #333333;text-align:center;">\n' +
                        '                            <span></span>\n' +
                        '                            秒後自動離開\n' +
                        '                        </div>';
                }
                
                if(error_msg!='') {
                    showFaqReplyErrorMsg(error_msg,nowBlock);   
                }
                else if(data.success!=undefined && data.success==1) {
                    nowFormElt.find('input,textarea').attr('disabled',true);
                    faq_add_checking_flag(nextBlock);
                    faq_remove_checking_flag(nowBlock);
                    faq_show_slide_form(nextFormElt);
                    faq_show_slide_form(nowFormElt);

                    if((data.all_pass!=undefined && data.all_pass==1) 
                        || (data.all_finished!=undefined && data.all_finished==1)) {
                        show_faq_msg();
                        wdt();
                    }
                    else swiper.slideNext();
                }
                
                if(data.all_finished!=undefined && data.all_finished==1) {
                    faq_remove_next_slide(nowBlock);
                    faq_count_down({{$faqCountDownTime}});
                    @if(count($faqPopupQuestionList)==1)
                    $(swiper.navigation.nextEl).hide();
                    $(swiper.navigation.prevEl).hide();
                    $(swiper.pagination.el).hide();
                    @endif                    
                }
                
            },
            error: function(xhr, status, error){
                if(error=='Unauthorized') {
                    wdt();
                    $('.bl_tab,.evaluation_bg').hide();
                    $('#tabPopM,#blbg').css('z-index',99);
                    show_pop_message('您已登出或基於帳號安全由系統自動登出，請重新登入');
                    return;                    
                } 
                else if(error=='Internal Server Error')
                    error_msg = '系統出現錯誤，暫時無法處理常見問題';               
                else
                    error_msg = '系統出現問題，暫時無法處理常見問題'; 
                
                error_msg+='<br>點按網頁任一處可離開常見問題';
                    
                save_faq_error_state();
                
                showFaqReplyErrorMsg(error_msg,nowBlock);                

                 $(".faq_blbg").attr('onclick','wdt()');
                 $("#faq_tab").attr('onclick','wdt()');                
            }
        }); 
        return false;
    }
    
    function wdt() {
		 $(".faq_blbg").hide();
         $("#faq_tab").hide();
         $('body').css("overflow", "auto");
        window.history.replaceState( {} , $('title').html(), '{{url("/dashboard/personalPage?")}}{{csrf_token()}}='+(new Date().getTime()) );
    }
    
    function getFaqActiveSlide() {
        return $('#faq_tab .swiper-container .swiper-slide-active');
    }
    
    function getFaqActBlock() {
        return getFaqActiveSlide().prev();
    }
    
    function showFaqReplyErrorMsg(error_msg,nowBlock) {
        var nowFormElt = nowBlock.find('form');        
        
        nowFormElt.parent().html('<p>'+error_msg+'</p>');     
    }
    
    $(function(){   
        window.history.replaceState( {} , $('title').html(), '{{url("/dashboard/personalPage?")}}{{csrf_token()}}='+(new Date().getTime()) );

        $('#faq_tab .gub_cld').on('click',function(){
            return check_empty(true);
        });         

        $.get( "{{route('checkIsForceShowFaq',[csrf_token()=>time()])}}"+(new Date().getTime()), function( data ) {
                if(data!=1) {
                    wdt();
                }
            });    
    });
    if(get_faq_error_state()==1) {
        wdt();
    } else {
        $('#faq_tab').show();
        swiper = swiper_initial({{$faqUserService->getReplyedBreakIndex()}});
    }
   
    function swiper_initial(realindex=0) {
        var swiper_init = new Swiper('.swiper-container', {
          slidesPerView:1,
          centeredSlides: true,
          spaceBetween: 30,
          initialSlide:realindex,
          pagination: {
            el: '.swiper-pagination',
            type: "custom",
            renderCustom: function (swiper_elt,current, total) {
                                  if(total>current)  total=total-1;                            
                              return '<span class="swiper-pagination-current">'
                              +current + '</span> / <span class="swiper-pagination-total">' 
                              + total+'</span>';
                            }
          },
          navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
          },
          on:{init:show_active_slide_form
            ,slidePrevTransitionEnd:faq_prev_step
            ,slideNextTransitionStart:faq_next_step
          }
        }); 
        return swiper_init;
    }

    function faq_count_down(sec) {
        var count_down_block = $('#faq_count_down_block');
        count_down_block.show();
        var count_down_elt = count_down_block.find('span');
        var sec_text = sec;
        count_down_elt.html(sec_text);
        var countInterval = setInterval(function () {
            sec_text = sec_text - 1;
            count_down_elt.html(sec_text); 

            
            if (sec_text < 0) { 
                wdt();
                clearInterval(countInterval);
            };

        }, 1000);
    }
    
    function faq_remove_next_slide(nowBlock) {
          var nowIndex = swiper.realIndex;
          nowBlock.next().remove(); 
          swiper.destroy();
          swiper = swiper_initial(nowIndex);        
    }
    
    function faq_show_slide_form(form_elt) {
        if(form_elt.length>0)
            form_elt.show().next().hide();
    }
    
    function faq_hide_slide_form(form_elt) {
        if(form_elt.length>0)
            form_elt.hide().next().show();
    }    
    
    function faq_add_checking_flag(blockElt) {
        if(blockElt.length==0) return;
        formElt = blockElt.find('form');
        if(formElt.length==0)  return;
        if(formElt.find(':disabled').length>0) return;
        blockElt.addClass('checking');
    }
    
    function faq_remove_checking_flag(blockElt) {
        if(blockElt.length>0) blockElt.removeClass('checking');
    }  

    function save_faq_error_state() {
        sessionStorage.setItem( 'fag_error_state',1);
    }
    
    function get_faq_error_state() {
        return sessionStorage.getItem('fag_error_state');
    }

    function show_faq_msg() {
        $('#faq_announce_bg,#faq_msg_tab').show();
    }
    
    function leave_faq_msg() {
        $('#faq_announce_bg,#faq_msg_tab').hide();
    }
    
    function check_empty(from_close_btn=null) {
        var nowBlock = null;
        if(!from_close_btn)
            nowBlock = getFaqActBlock();
        else
            nowBlock= getFaqActiveSlide();
        var nowFormElt = nowBlock.find('form');
        var nextBlock = nowBlock.next();
        var nextFormElt = nextBlock.find('form');
        
        if(nowFormElt.length) {
            nowFormElt.find('input[type=radio],input[type=checkbox]').css({"width":'1px',"height":'1px','position':'relative','top':'45px'}).addClass('force_show');           
            var chk_elt = nowFormElt.find('input[type=checkbox]:checked');
            var replace_elt = nowFormElt.find('.faq_replace_required_elt');
            if(chk_elt.length>0) {
                replace_elt.get(0).setCustomValidity('');
                chk_elt.each(function() {
                    var old_val = replace_elt.val();
                    replace_elt.val(old_val+this.value);
                    old_val = null;
                });
            }
            
            
            if(!nowFormElt.get(0).checkValidity()) {
                if(!from_close_btn) swiper.slidePrev();
                nowFormElt.get(0).reportValidity();
                return false;
            }
        } 

        return true;
    }
    
</script>
@endif
@if($rap_service->isSelfAuthApplyNotVideoYet())
<script>
    let ice_servers;
    async function kinesis_init()
    {
        // DescribeSignalingChannel API can also be used to get the ARN from a channel name.
        const channelARN = 'arn:aws:kinesisvideo:ap-southeast-1:428876234027:channel/videos/1653476269290';

        // AWS Credentials
        const accessKeyId = 'AKIAWHWYD7UVXA6QL2GN';
        const secretAccessKey = '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';
        const region = 'ap-southeast-1';

        const kinesisVideoClient = new AWS.KinesisVideo({
            region,
            accessKeyId,
            secretAccessKey,
            correctClockSkew: true,
        });

        const getSignalingChannelEndpointResponse = await kinesisVideoClient
            .getSignalingChannelEndpoint({
                ChannelARN: channelARN,
                SingleMasterChannelEndpointConfiguration: {
                    Protocols: ['WSS', 'HTTPS'],
                    Role: KVSWebRTC.Role.VIEWER,
                },
            })
            .promise();
        
        const endpointsByProtocol = getSignalingChannelEndpointResponse.ResourceEndpointList.reduce((endpoints, endpoint) => {
            endpoints[endpoint.Protocol] = endpoint.ResourceEndpoint;
            return endpoints;
        }, {});

        const kinesisVideoSignalingChannelsClient = new AWS.KinesisVideoSignalingChannels({
            region,
            accessKeyId,
            secretAccessKey,
            endpoint: endpointsByProtocol.HTTPS,
            correctClockSkew: true,
        });
        
        const getIceServerConfigResponse = await kinesisVideoSignalingChannelsClient
            .getIceServerConfig({
                ChannelARN: channelARN,
            })
            .promise();

        const iceServers = [
            { urls: `stun:stun.kinesisvideo.${region}.amazonaws.com:443` }
        ];

        getIceServerConfigResponse.IceServerList.forEach(iceServer =>
            iceServers.push({
                urls: iceServer.Uris,
                username: iceServer.Username,
                credential: iceServer.Password,
            }),
        );

        ice_servers = iceServers;
    }

    kinesis_init().then(function(result){
        $('#app video-chat').attr('ice_server_json',JSON.stringify(ice_servers));
        
        new Vue({
            el:'#app'
        });
    })
    
    setTimeout(change_video_status, 3000);
    setInterval(change_video_status, 10000);
    
    function change_video_status() 
    {
        var video_state_intro_block = $('#video_status_text_show_elt');
        var video_status_show_elt = $('.video_status_show_elt');
        if($('#app .btn-success').length) {
            if(video_status_show_elt.eq(0).attr('src')!='{{asset("/new/images/kai-1.png")}}')  video_status_show_elt.attr('src','');
            video_status_show_elt.attr('src','{{asset("/new/images/kai-1.png")}}').show();
            video_state_intro_block.html('現在可以進行視訊驗證，<span style="color:#e44e71;">點此開始驗證</span>');
        }
        else {
            video_status_show_elt.attr('src','{{asset("/new/images/guan.png")}}').show();
            video_state_intro_block.html('視訊審核的站方人員不在線，請稍後再試。');
        }
    }
</script>
@endif
@stop
