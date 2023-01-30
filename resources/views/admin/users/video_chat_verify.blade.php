@extends('admin.main')
@section('app-content')
        <script src="https://sdk.amazonaws.com/js/aws-sdk-2.1155.0.min.js"></script>
        <script src="https://unpkg.com/amazon-kinesis-video-streams-webrtc/dist/kvs-webrtc.min.js"></script>       
        {{--<script src="/new/js/aws-sdk-2.1143.0.min.js"></script>--}}
        <script>
            let video_verify_loading_pic = new Image();
            video_verify_loading_pic.src="{{asset('/new/images/loading.svg')}}";    
        </script>
        <style>
            #data-table th {white-space:nowrap;}

            .nowrap {
                white-space:nowrap;
            }
            
            .width2word {
                width:2.5em !important;
            }
            
            .video_user_question_edit_block {display:none;}
            #user_question_edit_block_with_video textarea {
                width:250px;
                height:70px;
            }

            #video_chat_user_setting_block {
                position:absolute;
                top:-100px;
                width:100%;
            }
            
            #video-row,#video_income_call_dialog {
              z-index:29;
              background-color:white;
              position:fixed;
            }  
            
            #video-row {
              top: calc((100% - 450px) / 2);
              left:  calc((100% - 700px) / 2);
            } 

            #video_income_call_dialog {
              top: calc((100% - 75px) / 2);
              left:  calc((100% - 227px) / 2);
            } 

            .video_chat_call_placed_mask_bg,.video_chat_incomingCallDialog_mask_bg {
                display:block !important;
                z-index:28 !important;
            }
            
            #video_chat_user_setting_block > div {display:inline-block;margin-left:2em;vertical-align:top;}
            #video_chat_user_setting_block > div > div > label > input {margin-right:0.5em;}
            .video_record_list_item_block {white-space:nowrap;}
            .video_user_question_show_block {margin-bottom:10px;word-break: break-all;word-wrap:break-word;white-space:pre-line;}
            .video_memo_edit_title_block {font-weight:bolder;}
            .not_into_chat_yet {background-color:yellow;padding:5px;}
            .operator-col button {margin-bottom:5px;}
        </style>
          
    <div style="padding: 15px;">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <h1>視訊驗證</h1>
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
    </div>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
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
            kinesis_init().then(function(result){
                $('#app video-chat').attr('ice_server_json',JSON.stringify(ice_servers));
                new Vue({
                    el:'#app'
                });
            });
        }
        
       function video_beforeunload_act()
        {
            axios
              .post("/video/unloading-video-page", {})
              .then(() => {
                var log_arr = {
                    from_file:'VideoVerifyUser.vue'
                    ,title:'then in unloading-video-page axios@video_chat_verify.tpl'
                    ,method:'then@unloading-video-page axios@video_chat_verify.tpl'
                    ,step:'within'
                };
                log_video_chat_process(log_arr);      
              })
              .catch((error) => {
                var log_arr = {
                    from_file:'VideoVerifyUser.vue'
                    ,title:'catch in unloading-video-page axios@video_chat_verify.tpl'
                    ,method:'catch@unloading-video-page axios@video_chat_verify.tpl'
                    ,step:'within'
                    ,data:{error:error}
                };
                log_video_chat_process(log_arr);    

                $("#error_message").text('loading-video-page axios error:' + error);
              });     
        }          
        
        $(document).ready(function(){
            
            start_video_chat();
        });
    </script>
    <script>
    window.history.replaceState( {} , $('title').html(), location.pathname+'?{{csrf_token()}}='+(new Date().getTime()) );
    </script>     
@stop