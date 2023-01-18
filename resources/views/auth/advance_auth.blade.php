<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="format-detection" content="telephone=no" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>進階驗證</title>
		<!-- Bootstrap -->
		<link href="/auth/css/bootstrap.min.css" rel="stylesheet">
		<link href="/auth/css/bootstrap-theme.min.css" rel="stylesheet">
		<!-- owl-carousel-->
		<!--    css-->
		<link rel="stylesheet" href="/auth/css/style.css">
		<link rel="stylesheet" href="/auth/css/button_new.css">
		<link rel="stylesheet" href="/auth/css/swiper.min.css">
		<script src="/auth/js/jquery-2.1.1.min.js" type="text/javascript"></script>
        <script src="/auth/js/bootstrap.min.js"></script>
		<script src="/auth/js/main.js" type="text/javascript"></script>
        <script src="/new/js/birthday.js" type="text/javascript"></script>
        @if(view()->shared('user') && view()->shared('rap_service') && view()->shared('rap_service')->riseByUserEntry(view()->shared('user')->refresh())->isAllowUseVideoChat()  || view()->shared('rap_service')->isInRealAuthProcess())   
        <script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
        <script src="https://sdk.amazonaws.com/js/aws-sdk-2.1.12.min.js"></script>
        <script src="https://unpkg.com/amazon-kinesis-video-streams-webrtc/dist/kvs-webrtc.min.js"></script>
        <script src="/new/js/aws-sdk-2.1143.0.min.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">        
        @endif
        <script>           
            $(function(){
                @if(!$user->isAdvanceAuth())
                $.ms_DatePicker();         
                @endif
            });              
        </script>
        @if($rap_service->isInRealAuthProcess())
        <style>
            .sa_video_status {cursor: pointer;}
            .video_status_text_show_elt {float:none !important;}
            #app,#app .btn-success {height:0 !important;width:0 !important;}
            #app {display:none !important;}
            
        </style>        
        <script>
            real_auth_process_check();
            
            function real_auth_process_check()
            {
                $('body').hide();
                $.get( "{{route('check_is_in_real_auth_process')}}"+location.search+"&{{csrf_token()}}="+(new Date().getTime()),function(data){
                    if(data!='1') {
                        window.history.replaceState( {} , $('title').html(), '{{route("real_auth")}}' );
                        location.href='{{route("real_auth")}}';
                    }
                    else {
                        $('body').show();
                    }
                });
            }  
        </script>
        @endif        
        <style>
            .select_xx04,.se_zlman {width:initial !important;}
            .se_zlman {margin-left:3%;margin-right:3%;}
            .birthday_selector em {margin-right:3%;}
            select.select_xx04 {border:none;background:transparent;}
            @media (max-width: 470px) {
                .select_xx04{
                    font-size:12px !important;
                    text-align-last: center;
                    text-align: center;
                    -ms-text-align-last: center;
                    -moz-text-align-last: center;                    
                }
                .se_zlman {margin-left:0 !important;margin-right:0 !important;}
                .birthday_selector em {margin-right:0 !important;}                 
            } 
            
            @media (max-width: 370px) {
                .birthday_selector em {
                    padding-right:10px !important;
                    margin-left:10px !important;
                }
                
                .birthday_selector select.xy_input {
                    margin-left:0 !important;
                }
                
                .birthday_selector select.select_xx04 {
                    padding:0 !important;
                }
            }
            .bolder {
                font-weight:bolder;
            } 
            .red {
                color:red;
            } 

            #tab_confirm.left .bltext,#tab_general_confirm  .bltext {
                text-align: left;
            }
            
            #tab_confirm .bltext .bolder.red ol,#tab_confirm .bltext .bolder.red ol li ,.new_wyz .bolder.red ol,.new_wyz .bolder.red ol li {list-style: inside decimal;}
            
            #tab_confirm .bltext .bolder.red ol li {text-align:left;}
            .margin_top_one_line {margin-top:1em;}
            .xy_input {border-radius:5px;}
            .xy_input.only_show {width:60% !important;color:#666666;}
            .center {text-align:center;}
            img.adv_auth_icon {margin:0;}
            div.blnr {padding:0;}
            @media (hover: none) {
                .n_left:hover {
                    box-shadow:none !important;
                }            
            }
            .n_left:active {
                box-shadow:inset 0px 15px 10px -10px #4c6ded, inset 0px -10px 10px -20px #4c6ded !important;            
            }
            #tab01 a .obvious {color:red;float:none;}
            input.only_show {color:#777;}
            div.has_error input,div.has_error select.select_xx04 {border:2px red solid;background:#FFECEC !important;}               
            #tab_confirm div.n_blnr01  div.blnr {display:initial;}
            #tab_confirm div.n_blnr01 div.blnr a {overflow-wrap:break-word;}
            @media (max-height: 470px) {
                #tab_confirm {
                    top: 3% !important;
                } 
                
                #tab_confirm .n_blnr01 {
                    padding-top:10px !important;
                }
            }  
            
            .i_am_student {margin-top:5%;}
            .i_am_student a:active,.i_am_student a:visited,.i_am_student a:focus {text-decoration:none;}
            .i_am_student .remind-regular {color:blue;font-weight:bolder;font-size:16px;width:initial;float:initial}
        </style>   	
    </head>

	<body style="background:#ffffff">
        @include('new.layouts.navigation')
		<!---->
		<div class="container matop70 nn_yzheight">
			<div class="row">
				<div class="col-sm-2 col-xs-2 col-md-2 dinone">
                    @include('new.dashboard.panel')
				</div>
				<div class="col-sm-12 col-xs-12 col-md-10">
					<div class="dengl matbot140 zh_top">
						<div class="zhuce">
							<h2>進階驗證</h2>
						</div>

                        @if(!$user->isAdvanceAuth() && !$init_check_msg??null)
						<div class="vipbongn new_wyz">
							<h2>驗證說明</h2>
							<h3>
                            
                            @if($is_edu_mode??null)
                            <div>還不能辦個人門號的學生可以透過校內 email 方式通過驗證
                            ，請輸入您的學校 email。
                            </div>
                            
                            @else
                            <div>您好，這是花園網的進階認證，將驗證您的以下資料，必須全部正確才能通過驗證。</div>
                            <div class="bolder red">
                                <ol>
                                <li>輸入資料必須符合該門號的登記資料,否則驗證會失敗</li>
                                <li>預付卡無法驗證</li>
                                <li>身分證字號則只用在本次驗證後刪除，本站不會留存</li>
                                </ol>
                            </div>
                            <div class="i_am_student"><a href="{{url('goto_advance_auth_email')}}{{request()->getQueryString()?'?'.request()->getQueryString():null}}" {!!$rap_service->getOnClickAttrForNoUnloadConfirm() !!}>我是學生未滿20歲，沒有辦個人門號，<span class="remind-regular">請點我</span></a></div>
                            @endif
                            </h3>
						</div>
                        @endif
						<div class="de_input">
							
							@if($user->isAdvanceAuth())
								
                                @if($rap_service->isInRealAuthProcess())
                                    @if(Session::has('message'))
                                        {!!implode('<br>',Session::get('message')??[])!!}                                
                                    @elseif($init_check_msg)
                                    <div class="center">{!!$init_check_msg!!}</div>    
                                    @endif
                                @else
                                <div class="center">已完成驗證，<a href="{!! url('dashboard') !!}" class="red">按此開始使用網站</a></div>    
                                @endif
                            @elseif($init_check_msg)
                                <div class="center">{!!$init_check_msg!!}</div>
							@else
                                @if($is_edu_mode??null)
                                    @include('auth.advance_auth_form_part-email')
                                @else
                                    @include('auth.advance_auth_form_part-pid')
                                @endif
                            @endif
						</div>
					</div>
				</div>
			</div>
		</div>

		@include('/new/partials/footer')
 
    @if(request()->msg=='canceled_ban' && $user->isAdvanceAuth() || Session::has('error_code')  || Session::has('message') || !$user->isAdvanceAuth() || $init_check_msg)
        <style>
        #tab01 .n_bbutton,#tab_general_alert .n_bbutton {width:initial;}
        #tab01 .n_bbutton span,#tab_general_alert .n_bbutton span {float:initial;}
        #tab01 .n_fengs {text-align:center;}
        #tab_general_alert .n_fengs a:hover,#tab_general_alert .n_fengs a:focus {color: #333333;    text-decoration: none;}
        </style>
        <!--弹出-->
        <div class="blbg" onclick="gmBtn1()" ></div>
        @if(!$user->isAdvanceAuth())
        <div class="bl bl_tab" id="tab_confirm">
            <div class="bltitle">提示</div>
            <div class="n_blnr01">
                <div class="blnr bltext">
                </div>
                <div class="n_bbutton">
                    <span><a class="n_left" href="#" onclick="" >同意</a></span>
                    <span><a onclick="gmBtn1()" class="n_right" href="javascript:">不同意</a></span>
                </div>
            </div>
            <a id="" onclick="gmBtn1()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
        </div> 
        @endif
        <div class="bl bl_tab " id="tab_general_alert" >
            <div class="bltitle" style="margin-top: -1px;"><span>提示</span></div>
            <div class="n_blnr01 matop10">
                <div class="n_fengs" >
                </div>
                <div class="n_bbutton">
                    <span><a class="n_left" onclick="gmBtn1()">確定</a></span>
                </div>
            </div>
            <a  onclick="gmBtn1()" class="bl_gb"><img src="/auth/images/gb_icon.png"></a>
        </div>
        <div class="bl bl_tab" id="tab_general_confirm">
            <div class="bltitle">提示</div>
            <div class="n_blnr01">
                <div class="blnr bltext">
                </div>
                <div class="n_bbutton">
                    <span><a class="n_left" href="#" onclick="" ></a></span>
                    <span><a onclick="gmBtn1()" class="n_right" href="javascript:"></a></span>
                </div>
            </div>
            <a id="" onclick="gmBtn1()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
        </div> 
        <div class="bl bl_tab " id="tab01" >
            <div class="bltitle" style="margin-top: -1px;"><span>提示</span></div>
            <div class="n_blnr01 matop10">
                <div class="n_fengs" >
                    @if(Session::has('error_code'))
                        @if(substr(implode('_',Session::get('error_code')??[]),1,1)=='_')請輸入正確@endif
                        @for($i=0;$i<count(Session::get('error_code'));$i++)
                        @if(Session::get('error_code')[$i]!='b18' && Session::get('error_code')[$i]!='pf' && Session::get('error_code')[$i]!='phack')
                        {{$i?'/':''}}{!!Session::get('error_code_msg')[Session::get('error_code')[$i]]!!}
                        @endif
                        @endfor
                        @if(substr(implode('_',Session::get('error_code')??[]),1,1)=='_')<br>@endif
                        @if(in_array('b18',Session::get('error_code')))
                        {!!Session::get('error_code_msg')['b18']??null!!}
                        @endif
                        @if(in_array('pf',Session::get('error_code')))
                        {!!Session::get('error_code_msg')['pf']??null!!}
                        @endif 
                        @if(in_array('phack',Session::get('error_code')))
                        {!!Session::get('error_code_msg')['phack']??null!!}
                        @endif                 
                        @php Session::forget('error_code')  @endphp
                        @php Session::forget('error_code_msg')  @endphp         
                    @elseif(request()->msg=='canceled_ban' && $user->isAdvanceAuth())     
                        您已完成進階驗證，成功解除封鎖/警示
                    @elseif(Session::has('message'))
                        {!!implode('<br>',Session::get('message')??[])!!}
                        @php Session::forget('message')  @endphp
                    @elseif($init_check_msg??null)
                    {!!$init_check_msg!!}
                    @elseif(!$user->isAdvanceAuth() && !Session::has('email_error'))
                        您好，您即將進入本站的進階身分驗證資訊系統。
                        通過驗證將獲得本站的<img src="{{asset('new/images/zz_zss.png')}}" class="adv_auth_icon" style="border-radius: 100px; box-shadow:1px 2px 10px rgba(77,152,252,1); height:20px;margin: 0px 5px;"/>進階驗證標籤<img src="{{asset('new/images/zz_zss.png')}}"  class="adv_auth_icon" style="border-radius: 100px; box-shadow:1px 2px 10px rgba(77,152,252,1); height:20px;margin: 0px 5px;"/>               
                        @if($rap_service->isInRealAuthProcess())
                        ，並可進行與站長的視訊。
                        @endif            
                    @elseif(Session::has('email_error'))
                        {{Session::get('email_error')}}
                    @endif 
                </div>
                <div class="n_bbutton">
                    <span><a class="n_left" onclick="gmBtn1()">確定</a></span>
                </div>
            </div>
            <a  onclick="gmBtn1()" class="bl_gb"><img src="/auth/images/gb_icon.png"></a>
        </div>
        @if($rap_service->isInRealAuthProcess() && $rap_service->isSelfAuthApplyNotVideoYet())
        <div style="position:relative;" id="video_app_after_apply_container">
            <div id="app_after_apply" style="display:none;">
                <video-verify-user-after-apply 
                    :allusers="{{ $users }}" 
                    :authUserId="{{ auth()->id() }}" 
                    user_permission = "normal"
                    ice_server_json="" 
                />
                
            </div>
        </div>
        </div>
        @endif 

        <script>
            $(function(){
                $(".blbg").hide();
                $(".bl").hide();
            });
            function cl() {
                $(".blbg").show();
                $("#tab01").show();
            }
            
            function gmBtn1(){
                @if(!$user->isPhoneAuth() && !($is_edu_mode??null) && !$user->isAdvanceAuth())
                {!!$rap_service->getClearUnloadConfirmJs() !!}
                location.href='{{url("goto_member_auth")}}'+location.search;
                @else
                $(".blbg").hide();
                $(".bl").hide();            
                @endif
            }

        </script>
        @if($is_edu_mode??null)
            @include('auth.advance_auth_js_part-email')
        @else
            @include('auth.advance_auth_js_part-pid')
        @endif

        <script> 
            $(function(){
                cl();                       
            });            
        </script>     
    @endif
    @if($rap_service->isInRealAuthProcess() && !$user->isAdvanceAuth())
        <script>

            active_onbeforeunload_hint();

            function active_onbeforeunload_hint()
            {
                $('body').attr('onbeforeunload',"return '';");
                $('body').attr('onkeydown',"if (window.event.keyCode == 116) $(this).attr('onbeforeunload','');");    
            }
        </script>
    @endif
    @if($rap_service->isInRealAuthProcess() && $rap_service->isSelfAuthApplyNotVideoYet())
        <script>
            let ice_servers;
            async function kinesis_init_after_apply()
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

            kinesis_init_after_apply().then(function(result){
                $('#app_after_apply video-verify-user-after-apply').attr('ice_server_json',JSON.stringify(ice_servers));
                new Vue({
                    el:'#app_after_apply'
                });
            })

        tab01_n_left_onclick_str = $('#tab01 .n_bbutton .n_left').attr('onclick');

        function get_passed_real_auth_confirm_href()
        {
            return "{{url('user_video_chat_verify')}}";
        }
        </script>    
    @endif
    @include('new.partials.video_verify_user_entire_site')            
	@include('new.partials.stay_online_record')
    @include('new.partials.message')
        @php
            $anonymous_chat_announcement = \App\Models\AdminCommonText::where('category_alias', 'anonymous_chat')->where('alias', 'announcement')->first();
            if($anonymous_chat_announcement) {
                $anonymous_chat_announcement = $anonymous_chat_announcement->content;
            }else{
                $anonymous_chat_announcement = '';
            }
        @endphp
        <style>
            @media (max-width:912px) and (max-height: 414px) {

                .tab_anonymousChatAlert {
                    max-height: 300px;
                    overflow-y: scroll;
                }
            }
        </style>
        <div class="bl bl_tab tab_anonymousChatAlert" id="tab_anonymousChatAlert" style="top: 10%;">
            <div class="bltitle">聊天室重要規定</div>
            <div class="n_blnr01 matop10">
                <div class="n_fengs">{!! isset($anonymous_chat_announcement)?$anonymous_chat_announcement:'' !!}
                </div>
                <a class="n_bllbut matop30">進入聊天室</a>
            </div>
            <script>
                function gmBtnNoReload(){
                    $(".announce_bg").hide();
                    $(".blbg").hide();
                    $(".bl").hide();
                    $(".gg_tab").hide();
                    $('body').css("overflow","auto");
                }
            </script>
            <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
        </div>
    </body>
</html>