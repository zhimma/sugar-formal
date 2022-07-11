@extends('admin.main')
@section('app-content')
    <head>
        <script src="https://sdk.amazonaws.com/js/aws-sdk-2.1.12.min.js"></script>
        <script src="https://unpkg.com/amazon-kinesis-video-streams-webrtc/dist/kvs-webrtc.min.js"></script>
        <script src="/new/js/aws-sdk-2.1143.0.min.js"></script>
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

        $('#video_chat_switch_on').on('click',function(){
            $(this).css({
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
        });
        
        $('#video_chat_switch_off').on('click',function(){
            window.location.reload();
        });

        
    </script>
@stop