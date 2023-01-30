<script>
    let video_verify_loading_pic_entire_site = new Image();
    video_verify_loading_pic_entire_site.src="{{asset('/new/images/loading.svg')}}";    

    function log_video_chat_process_entire_site(log_arr)
    {
        log_arr.url = location.href;

        fetch('/video/log_video_chat_process', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(log_arr)
                });       
    }
    
    function video_beforeunload_act_entire_site()
    {
        axios
            .post("/video/unloading-video-page", {from_file:'video_verify_user_entire_site.tpl'})
            .then(() => {     
            })
            .catch((error) => {
            var log_arr = {
                from_file:'video_verify_user_entire_site.tpl'
                ,title:'catch in unloading-video-page axios@video_verify_user_entire_site.tpl'
                ,method:'catch@unloading-video-page axios@video_verify_user_entire_site.tpl'
                ,step:'within'
                ,data:{error:error}
            };
            log_video_chat_process_entire_site(log_arr);    

            $("#error_message").text('loading-video-page axios error:' + error);
            });     
    }            
    
</script>
<style>
    #entire_site_video_app > div > .container {
        
        background: white;
        top: 20%;
        left: 2%;
        width: 96%;
        text-align: center;
        border-radius: 5px;                
        
    }
    
    #entire_site_video_app > div > .container > .row {margin:auto;}
    #entire_site_video_app > div > .container > .row:last-child > .col {margin:10px;}
    #entire_site_video_app > div > .container > .row:first-child > .col {margin:0 !important;}
    
    
</style>
<div style="position:relative;" id="real_auth_video_entire_site_container">
    <div id="entire_site_video_app" style="z-index: 39;">
        <video-verify-user-entire-site
            :allusers="{{ view()->shared('self_auth_video_allusers') }}" 
            :authUserId="{{ auth()->id() }}" 
            :authUser="{{ auth()->user()?auth()->user()->load('self_auth_unchecked_apply'):null }}"
            user_permission = "normal"
            is_user_allow_video_chat = "{{view()->shared('user') && view()->shared('rap_service') && view()->shared('rap_service')->riseByUserEntry(view()->shared('user')->refresh())->isAllowUseVideoChat() && view()->shared('rap_service')->isUrlNeedEntireSiteVideoChat()}}"
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

    $(document).ready(function(){
        $.ajax({
            url: '{{route('user_video_chat_verify_allow_check')}}',
            method: 'GET',
            data: {
                user_id : {{ auth()->id() }}
            },
            success: function(data){
                if(data.is_allow)
                {
                    kinesis_init_entire_site().then(function(result){
                        $('#entire_site_video_app video-verify-user-entire-site').attr('ice_server_json',JSON.stringify(ice_servers_entire_site));
                        new Vue({
                            el:'#entire_site_video_app'
                        });
                    })
                }
            }
        })
    });
</script>