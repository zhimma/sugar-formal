@extends('new.layouts.website')
@section('app-content')
    <script src="https://sdk.amazonaws.com/js/aws-sdk-2.1.12.min.js"></script>
    <script src="https://unpkg.com/amazon-kinesis-video-streams-webrtc/dist/kvs-webrtc.min.js"></script>
    <script src="/new/js/aws-sdk-2.1143.0.min.js"></script>
    <div class="container matop70">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <div class="row">
            {{--
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            --}}
            
            <div id="app">
                <video-chat 
                    :allusers="{{ $users }}" 
                    :authUserId="{{ auth()->id() }}" 
                    turn_url="{{ env('TURN_SERVER_URL') }}"
                    turn_username="{{ env('TURN_SERVER_USERNAME') }}" 
                    turn_credential="{{ env('TURN_SERVER_CREDENTIAL') }}" 
                />
            </div>
        <div>
    <div>
    <script>
        async function kinesis_init()
        {
            // DescribeSignalingChannel API can also be used to get the ARN from a channel name.
            const channelARN = 'arn:aws:kinesisvideo:us-west-2:123456789012:channel/test-channel/1234567890';

            // AWS Credentials
            const accessKeyId = 'AKIAWHWYD7UVXA6QL2GN';
            const secretAccessKey = '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';
            const region = 'ap-southeast-1';

            console.log('start');

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

            console.log(getSignalingChannelEndpointResponse);
            console.log('end');
            /*
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
            */
        }

        kinesis_init();
        



        /*
        new Vue({
            el:'#app'
        });
        */
    </script>
@stop