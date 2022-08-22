    @if(view()->shared('user') && view()->shared('rap_service') && view()->shared('rap_service')->riseByUserEntry(view()->shared('user')->refresh())->isAllowUseVideoChat() && view()->shared('rap_service')->isUrlNeedEntireSiteVideoChat())
        <style>
            #entire_site_video_app > div > .container {
                
                background: white;
                top: 20%;
                left: 2%;
                width: 96%;
                padding: 10px;
                text-align: center;
                border-radius: 5px;                
                
            }
            
            #entire_site_video_app > div > .container > .row {margin:auto;}
            
          
        </style>
        <div style="position:relative;" id="real_auth_video_entire_site_container">
            <div id="entire_site_video_app" style="z-index: 39;">
                <video-verify-user-entire-site
                    :allusers="{{ view()->shared('self_auth_video_allusers') }}" 
                    :authUserId="{{ auth()->id() }}" 
                    user_permission = "normal"
                    ice_server_json="" 
                />
                
            </div>
        </div>
        <script>
            let ice_servers_entire_site;
            async function kinesis_init_entire_site()
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

                const iceServersEntireSite = [
                    { urls: `stun:stun.kinesisvideo.${region}.amazonaws.com:443` }
                ];

                getIceServerConfigResponse.IceServerList.forEach(iceServer =>
                    iceServersEntireSite.push({
                        urls: iceServer.Uris,
                        username: iceServer.Username,
                        credential: iceServer.Password,
                    }),
                );

                ice_servers_entire_site = iceServersEntireSite;
            }

            kinesis_init_entire_site().then(function(result){
                $('#entire_site_video_app video-verify-user-entire-site').attr('ice_server_json',JSON.stringify(ice_servers_entire_site));
                new Vue({
                    el:'#entire_site_video_app'
                });
            })
        </script>
          

    @endif