@extends('new.layouts.website')
@section('style')
<style>
    #left_side_content_block {padding-left:3%;}
    #left_side_content_block > div {width:94%;margin:0 auto;}
    #left_side_content_block .shou {margin-bottom:10px;}
    #app div.btn-group {height:50px;background:url('{{asset('new/images/fengsuo.png')}}');}
</style>
    <script src="https://sdk.amazonaws.com/js/aws-sdk-2.1155.0.min.js"></script>
    <script src="https://unpkg.com/amazon-kinesis-video-streams-webrtc/dist/kvs-webrtc.min.js"></script>
    {{--<script src="/new/js/aws-sdk-2.1143.0.min.js"></script>--}}
<meta name="csrf-token" content="{{ csrf_token() }}">       
@stop
@section('app-content')

    <div class="container matop70">     
        <div class="row" >
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10" id="left_side_content_block">
                <div class="shou"><span>站方視訊</span>
                    <a href="{{request()->server('HTTP_REFERER')?request()->server('HTTP_REFERER'):route('real_auth')}}" class="toug_back btn_img" style=" position: absolute; right:20px;">
                        <div class="btn_back"></div>
                    </a>                
                </div>
                <div style="position:relative;" id="video_app_container">
                    <div id="app" style="z-index: 9;">
                        <video-verify-user 
                            :allusers="{{ $users }}" 
                            :authUserId="{{ auth()->id() }}" 
                            :authUser="{{ auth()->user()->load('self_auth_unchecked_apply.first_modify') }}"
                            :authUserIsSelfAuthWaitingCheck="{{intval(!!$rap_service->isSelfAuthWaitingCheck())}}"
                            user_permission = "normal"
                            ice_server_json="" 
                        />
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let video_verify_loading_pic = new Image();
        video_verify_loading_pic.src="{{asset('/new/images/loading.svg')}}";                

        function log_video_chat_process(log_arr)
        {
            log_arr.url = location.href;
            
            fetch('/video/log_video_chat_process', {
                  method: 'POST',
                  headers: {'Content-Type': 'application/json'},
                  body: JSON.stringify(log_arr)
                  });
                 
        }
        
        function video_beforeunload_act()
        {
            axios
              .post("/video/unloading-video-page", {})
              .then(() => {
                var log_arr = {
                    from_file:'VideoVerifyUser.vue'
                    ,title:'then in unloading-video-page axios@user_video_chat_verify.tpl'
                    ,method:'then@unloading-video-page axios@user_video_chat_verify.tpl'
                    ,step:'within'
                };
                log_video_chat_process(log_arr);      
              })
              .catch((error) => {
                var log_arr = {
                    from_file:'VideoVerifyUser.vue'
                    ,title:'catch in unloading-video-page axios@user_video_chat_verify.tpl'
                    ,method:'catch@unloading-video-page axios@user_video_chat_verify.tpl'
                    ,step:'within'
                    ,data:{error:error}
                };
                log_video_chat_process(log_arr);    

                $("#error_message").text('loading-video-page axios error:' + error);
              });     
        }            
    </script>
    
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
            $('#app video-verify-user').attr('ice_server_json',JSON.stringify(ice_servers));
            new Vue({
                el:'#app'
            });
        })
    </script>
    <script>
    window.history.replaceState( {} , $('title').html(), location.pathname+'?{{csrf_token()}}='+(new Date().getTime()) );
    </script>    
@stop