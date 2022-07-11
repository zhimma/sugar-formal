@extends('new.layouts.website')
@section('style')
<style>
    #left_side_content_block {padding-left:3%;}
    #left_side_content_block > div {width:94%;margin:0 auto;}
    #left_side_content_block .shou {margin-bottom:10px;}
    #app div.btn-group {height:50px;background:url('{{asset('new/images/fengsuo.png')}}');}
</style>
<script src="https://sdk.amazonaws.com/js/aws-sdk-2.1.12.min.js"></script>
<script src="https://unpkg.com/amazon-kinesis-video-streams-webrtc/dist/kvs-webrtc.min.js"></script>
<script src="/new/js/aws-sdk-2.1143.0.min.js"></script>
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
                </div>
                <div style="position:relative;" id="video_app_container">
                    <div id="app" style="z-index: 9;">
                        <video-chat 
                            :allusers="{{ $users }}" 
                            :authUserId="{{ auth()->id() }}" 
                            user_permission = "normal"
                            ice_server_json="" 
                        />
                        
                    </div>
                    <div style="flex-wrap: wrap;position: absolute;top: 0;z-index: -1;">
                        <button type="button" class="btn mr-2 btn-secondary disabled" style="padding:0;">           
                            <span class="badge badge-light" style="line-height:normal;letter-spacing:2px;text-align:left;">目前無站方人員<br>暫時無法視訊</span>
                        </button>
                    </div>
                </div>
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
        })
    </script>
@stop