@extends('new.layouts.website')
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<link rel="stylesheet" href="/new/css/iconfont.css">
@section('app-content')
    <style>
        .table>tbody>tr>td{
            border-top: unset;
            border-bottom: 1px solid #ddd;
        }
        .on {min-height: 24px;}
		
		.ta_l{ border-bottom: #eee 1px solid; display: table; margin-bottom: 6px; padding-bottom: 6px; line-height: 24px;}
		.tu_dfont{
			width: calc(100% - 100px);
			 float: left;
			 max-height: 45px;
			 word-break: break-all;
			 text-overflow: ellipsis;
			 display: -webkit-box;
			 -webkit-box-orient: vertical;
			 -webkit-line-clamp: 2;
			 overflow: hidden;
		}	

		.tabbox_h2 .check{
			float:left;margin-right:15px;margin-top:5px;
		}	
		.fr_nbj{ float: right;}
		@media (max-width:320px) {
			.fr_nbj{ float: none;}
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
                        <div class="tabbox_new_dt"><span>VIP狀態</span></div>
                        <div class="tabbox_new_dd">
                            @if($user->isVip())
                                <h2 class="tabbox_h2">{!! $vipStatus !!}</h2>
                            @else
                                <h2 class="tabbox_h2">您目前還不是VIP<a class="zs_buttonn" href="{{url('/dashboard/new_vip')}}">立即成為VIP</a></h2>
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

                    <div class="sys_aa">
                        <div class="tabbox_new_dt"><span>收件夾通訊人數</span></div>
                        <div class="tabbox_new_dd">
                            <h2 class="tabbox_hsz">@if(empty($msgMemberCount))0 @else{{$msgMemberCount}}@endif</h2>
                        </div>
                    </div>
                    <div class="sys_aa">
                        <div class="tabbox_new_dt"><span>收件夾總訊息數</span></div>
                        <div class="tabbox_new_dd">
                            <h2 class="tabbox_hsz">{{ $allMessage }}</h2>
                        </div>
                    </div>
                    <div class="sys_aa">
                        <div class="tabbox_new_dt tabbox_new_ss"><span>系統來訊通知</span>
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
								{{ strip_tags($amsg->content)}}
								</span>
								<a class="zs_buttonn1 right"  href="{{route("chat2WithUser",$amsg->from_id)}}">前往查看</a>
							</h2>
							@endforeach
						@else
							<h2 class="tabbox_h2 ta_l"><span class="tu_dfont">暫無系統信</span></h2>
						@endif						
                        </div>
                    </div>

                    @if($isBannedStatus != '')
                    <div class="sys_aa">
                        <div class="tabbox_new_dt"><span>封鎖紀錄</span></div>
                        <div class="tabbox_new_dd">
                            <h2 class="tabbox_h3">{!! $isBannedStatus !!}</h2>
                        </div>
                    </div>
                    @endif

                    @if($adminWarnedStatus != '' || $isWarnedStatus != '')
                    <div class="sys_aa">
                        <div class="tabbox_new_dt"><span>警示紀錄</span></div>
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

                    <div class="sys_aa">
                        <dt class="tabbox_new_ss"><span class="sys_log1 xs_wi90 open_reportedRecord">檢舉處理狀況</span>
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
                                                <td style="font-size: 15px;color: #999999;">{!! $row['content'] !!}</td>
                                                <td style="font-size: 15px;color: #999999;">{!! $row['status'] !!}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
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
                            @if($myFav->count()>0 && $user->isVip())
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
                                @if($user->isVip())
                                    @if($myFav->count()>0)
                                        <div class="ys_dt">僅顯示一周內上線的會員</div>
                                        <ul>
                                            @foreach($myFav as $row)
                                                <li data-recordtype="myFavRecord" data-rowid="{{ $row->rowid }}">
                                                    <h2><span>會員暱稱</span><font><a href="{{url('/dashboard/viewuser/' . $row->member_fav_id . '?time=' . \Carbon\Carbon::now()->timestamp)}}">{{$row->name}}</a></font></h2>
                                                    <h2><span>會員標題</span><font class="xss_he">{{$row->title}}</font></h2>
                                                    <h2><span>最後上線時間</span><font>{{ substr($row->last_login,0,16)}}</font></h2>
                                                    <h2><span>是否來看過我</span><font>@if($row->vid !='')是，{{substr($row->visited_created_at,0,16)}}@endif</font></h2>
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
                            @if($otherFav->count()>0 && $user->isVip())
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
                                @if($user->isVip())
                                    @if($otherFav->count()>0)
                                        <div class="ys_dt">僅顯示一周內上線的會員</div>
                                        <ul>
                                            @foreach($otherFav as $row)
                                                <li data-recordtype="myFavRecord2" data-rowid="{{ $row->rowid }}">
                                                    <h2><span>會員暱稱</span><font><a href="{{url('/dashboard/viewuser/' . $row->member_id . '?time=' . \Carbon\Carbon::now()->timestamp)}}">{{$row->name}}</a></font></h2>
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
                        <a class="sl_bllbut" href="/dashboard/chat/chatNotice">好，我想即時接收聊天訊息</a>
                        <a class="sl_bllbut01" onclick="lineNotifyPopUp_close()">不想即時收到訊息</a>
                    </div>
                </div>
            </div>
            <a onclick="lineNotifyPopUp_close()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
        </div>
    </div>
    @php session()->put('alreadyPopUp_lineNotify', $user->line_notify_alert.'_Y') @endphp

@stop

@section('javascript')
<script type="text/javascript">
    $(function() {
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
				$(this).closest('.sys_aa').find('.tabbox_h2').prepend(li_check);
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
            var tab = $(this).closest('.sys_aa').find('.tab_jianju');
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
												+'</span><a class="zs_buttonn1 right" style="display:none;"  href="{{route("chat2WithUser",$admin->id)}}">前往查看</a></h2>';										
										
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
        var hasReportedRecord='{{$reportedStatus ? 1 : 0}}';
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
        c5html('iPhone 的 Safari 在 Line 的綁定容易出問題。如果您在綁定過程中失敗，請改用 Google Chrome 嘗試看看。如果還是出問題，<a href="https://lin.ee/rLqcCns" target="_blank">請點此&nbsp;<img src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png" alt="加入好友" height="36" border="0" style="height: 36px; float: unset;"></a>&nbsp;或點右下聯絡我們加站長line。');
        $(".n_bllbut").on('click', function() {
            var lineClientId = '{{config('line.line_notify.client_id')}}';
            var callbackUrl = '{{config('line.line_notify.callback_url')}}';
            var URL = '{{config('line.line_notify.authorize_url')}}?';
            URL += 'response_type=code';
            URL += '&client_id='+lineClientId;
            URL += '&redirect_uri='+callbackUrl;
            URL += '&scope=notify';
            URL += '&state={{csrf_token()}}';
            window.location.href = URL;
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

    $('#collapseExample').collapse('show');

    $('#collapseExample').collapse('show',{
        toggle: true

    });

    $('#collapseExample').on('hidden.bs.collapse', function () {
        // do something…
        $('.collapse_word').html('[＋]　展開');
    });
    $('#collapseExample').on('shown.bs.collapse', function () {
        // do something…
        $('.collapse_word').html('[－]　收起');
    });

    $( document ).ready(function() {
        //
        $('#collapseExample').collapse('show');
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

    @if($evaluation_30days_unread_count && $user->notice_has_new_evaluation)
        $('#show_new_evalucation_popup').show();
        $(".blbg").show()
    @endif

    function new_evaluation_popup_close() {
        $('#show_new_evalucation_popup').hide();
        $("#blbg").hide();
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
            url: '{{ route('closeNoticeNewEvaluation') }}',
            data: { id : "{{ $user->id }}", _token:"{{ csrf_token() }}"},
            success: function(xhr, status, error){
                $('#show_new_evalucation_popup').hide();
                $("#blbg").hide();
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
<script type="text/javascript">
    $(function() {
		@if(isset($admin_msgs) && count($admin_msgs))
		    $('.btn_admin_msgs').show();
		@endif
	});

</script>

@stop
