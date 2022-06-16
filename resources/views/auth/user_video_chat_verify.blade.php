@extends('new.layouts.website')
@section('app-content')
    <script src="https://sdk.amazonaws.com/js/aws-sdk-2.1155.0.min.js"></script>
    <script src="https://unpkg.com/amazon-kinesis-video-streams-webrtc/dist/kvs-webrtc.min.js"></script>
    {{--<script src="/new/js/aws-sdk-2.1143.0.min.js"></script>--}}
    <div class="container matop70">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <div class="row">
            <div id="app">
                <video-chat 
                    :allusers="{{ $users }}" 
                    :authUserId="{{ auth()->id() }}" 
                    user_permission = "normal"
                    ice_server_json="" 
                />
            </div>
        </div>
    </div>
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
        });
    </script>
@stop