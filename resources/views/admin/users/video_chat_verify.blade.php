@extends('admin.main')
@section('app-content')
    <head>
        <script src="https://sdk.amazonaws.com/js/aws-sdk-2.1155.0.min.js"></script>
        <script src="https://unpkg.com/amazon-kinesis-video-streams-webrtc/dist/kvs-webrtc.min.js"></script>       
        {{--<script src="/new/js/aws-sdk-2.1143.0.min.js"></script>--}}
        <script>
            let video_verify_loading_pic = new Image();
            video_verify_loading_pic.src="{{asset('/new/images/loading.svg')}}";    
        </script>
    </head>
    
    <body style="padding: 15px;">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <h1>視訊驗證</h1>
        <button id="video_chat_switch_on" class="btn" style="background-color: #e7e7e7; color: black; cursor: default; ">ON</button>
        <button id="video_chat_switch_off" class="btn" style="background-color: #f44336; color: white; cursor: not-allowed;">OFF</button>
        <br>
        <br>
        <div class="row">
            <div id="app">
                <video-chat 
                    :allusers="{{ $users }}" 
                    :authUserId="{{ auth()->id() }}" 
                    user_permission = "admin"
                    ice_server_json="" 
                />
            </div>
        </div>
    </body>

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

        function start_video_chat(){
            $('#video_chat_switch_on').css({
                'background-color': '#4CAF50',
                'color': 'white',
                'cursor': 'not-allowed'
            });
            $('#video_chat_switch_off').css({
                'background-color': '#e7e7e7',
                'color': 'black',
                'cursor': 'default'
            });
            kinesis_init().then(function(result){
                $('#app video-chat').attr('ice_server_json',JSON.stringify(ice_servers));
                new Vue({
                    el:'#app'
                });
            });
        }
        
       function video_beforeunload_act()
        {
            console.log('start video_beforeunload_act');
            axios
              .post("/video/unloading-video-page", {})
              .then(() => {
                var log_arr = {
                    from_file:'VideoVerifyUser.vue'
                    ,title:'then in unloading-video-page axios@VideoVerifyUser.vue'
                    ,method:'then@unloading-video-page axios at begining in script'
                    ,step:'within'
                };
                log_video_chat_process(log_arr);      
              })
              .catch((error) => {
                var log_arr = {
                    from_file:'VideoVerifyUser.vue'
                    ,title:'catch in unloading-video-page axios@VideoVerifyUser.vue'
                    ,method:'catch@unloading-video-page axios'
                    ,step:'within'
                    ,data:{error:error}
                };
                log_video_chat_process(log_arr);    

                $("#error_message").text('loading-video-page axios error:' + error);
              });     
        }          

        $('#video_chat_switch_on').on('click',function(){
            start_video_chat();
            $('body').attr('onkeydown',"if (window.event.keyCode == 116) window.sessionStorage.setItem('endcall_reload',true);");    
        });

        $(document).ready(function(){
            if(window.sessionStorage.endcall_reload)
            {
                start_video_chat();
                window.sessionStorage.removeItem('endcall_reload');
                $('body').attr('onkeydown',"if (window.event.keyCode == 116) window.sessionStorage.setItem('endcall_reload',true);");    
            }
        });

        $('#video_chat_switch_off').on('click',function(){
            video_beforeunload_act();
            window.sessionStorage.removeItem('endcall_reload');
              var old_beforeunload = $('body').attr('onbeforeunload');
              $('body').attr('onbeforeunload',old_beforeunload.replace('video_beforeunload_act()',''));            
                
            window.location.reload();
        });

        
    </script>
    <script>
    window.history.replaceState( {} , $('title').html(), location.pathname+'?{{csrf_token()}}='+(new Date().getTime()) );
    </script>     
@stop