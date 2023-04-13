@extends('new.layouts.website')
@section('style')
    <style>
        #left_side_content_block {padding-left:3%;}
        #left_side_content_block > div {width:94%;margin:0 auto;}
        #left_side_content_block .shou {margin-bottom:10px;}
        #app div.btn-group {height:50px;background:url('{{asset('new/images/fengsuo.png')}}');}
    </style> 
    <style scoped>
        #video-row {
        width: 700px;
        max-width: 90vw;
        }
        
        .video-container {
        width: 700px;
        height: 500px;
        max-width: 90vw;
        max-height: 50vh;
        margin: 0 auto;
        border: 1px solid #0acf83;
        position: relative;
        box-shadow: 1px 1px 11px #9e9e9e;
        background-color: #fff;
        }
        
        .video-container .record-video {
        width: 100%;
        height: 100%;
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        top: 0;
        z-index: 1;
        margin: 0;
        padding: 0;
        }
        
        .video-container .action-btns {
        position: absolute;
        bottom: 20px;
        left: 50%;
        margin-left: -50px;
        z-index: 3;
        display: flex;
        flex-direction: row;
        }
        
        .video-container .error_message {
        position: absolute;
        bottom: 0;
        right: 0;
        z-index: 4;
        display: flex;
        flex-direction: row;
        }
        
        @media only screen and (max-width: 768px) {
        .video-container {
            height: 50vh;
        }
        }
    </style>   
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@section('app-content')
    <div class="container matop70">     
        <div class="row" >
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10" id="left_side_content_block">
                <div class="shou"><span>視訊驗證</span>
                    <a href="{{request()->server('HTTP_REFERER')?request()->server('HTTP_REFERER'):route('real_auth')}}" class="toug_back btn_img" style=" position: absolute; right:20px;">
                        <div class="btn_back"></div>
                    </a>                
                </div>
                <div style="position:relative;" id="video_app_container">
                    <button id="start_record" type="button" class="btn btn-success">
                        開始驗證
                    </button>
                    <div id="vedio_field" style="display:none">
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                </div>
                            </div>
                            <div class="row mt-5" id="video-row">
                                <div class="col-12 video-container">
                                    <video id="record_video_screen">
                                    </video>
                                    <div class="record-video" v-else>
                                        <div v-if="callPartner" class="column items-center q-pt-xl">
                                            <div class="col q-gutter-y-md text-center">
                                                <p class="q-pt-md">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="action-btns">
                                        <button id="end_button" type="button" class="btn btn-danger">
                                            結束
                                        </button>
                                    </div>
                                    <div class="error_message">
                                        <strong><li id="error_message" style="color:red;"></li></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="question_field" style="display:none">
                        @foreach($questions as $question)
                            <div>{{$question->question}}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <script>
        $(function() {
            $(this).hide();
            $('#vedio_field').show();
            $('#question_field').show();
            start_record();
        });

        let deviceReady = false;
        let getUserMediaError = false;
        let recordedBlobs = [];
        let mediaRecorder = null;
        let record_stream = null;

        $('#start_record').click(function(){
            $(this).hide();
            $('#vedio_field').show();
            $('#question_field').show();
            start_record();
        });

        $('#end_button').click(function(){
            $('#start_record').show();
            $('#vedio_field').hide();
            $('#question_field').hide();
            end_record();
        });

        async function start_record() {
            await checkDevices();
            if(!deviceReady){
                $('#start_record').show();
                $('#vedio_field').hide();
                $('#question_field').hide();
                alert('未搜尋到鏡頭或麥克風裝置');
                return;
            }
            await getMediaPermission();
            if(getUserMediaError){    
                $('#start_record').show();
                $('#vedio_field').hide();
                $('#question_field').hide(); 
                alert('未取得鏡頭或麥克風裝置權限');
                return;
            }
            startRecording();
        }

        function end_record() {
            stopRecording();
            const video = document.querySelector('video');
            if(record_stream) {
                const videoStreams = record_stream.getVideoTracks()

                videoStreams.forEach(stream => {
                stream.stop()
                });

                video.src = video.srcObject = null;
            }
            deviceReady = false;
            getUserMediaError = false;
            recordedBlobs = [];
            mediaRecorder = null;
            record_stream = null;
        }

        function checkDevices() {
            return navigator.mediaDevices.enumerateDevices()
                .then( 
                    dev => {
                        gotDevices(dev);
                    }
                )        
                .catch( 
                    err => console.warn(err)
                );
        }

        function gotDevices(deviceInfos) {
            let audioSet = false;
            let videoSet = false;

            for (let i = 0; i !== deviceInfos.length; ++i) {
                const deviceInfo = deviceInfos[i];
                if (deviceInfo.kind === 'audioinput'){
                    audioSet = true;
                }
                else if (deviceInfo.kind === 'videoinput'){
                    videoSet = true;
                }
            }
            deviceReady = (audioSet && videoSet);
        }

        function getMediaPermission() {
            return this.getPermissions().then((stream) => {
                record_stream = stream;
                let inputVideo = document.querySelector('#record_video_screen');
                inputVideo.srcObject = stream;
                inputVideo.play();
            }).catch((error) => {
                $("#error_message").text(error);
            });
        }

        function getPermissions() {
            if (navigator.mediaDevices === undefined) {
                navigator.mediaDevices = {};
            }
            if (navigator.mediaDevices.getUserMedia === undefined) {
                navigator.mediaDevices.getUserMedia = function(constraints) {
                    const getUserMedia =
                        navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
                    if (!getUserMedia) {
                        return Promise.reject(
                            new Error("getUserMedia is not implemented in this browser")
                        );
                    }
                    return new Promise((resolve, reject) => {
                        getUserMedia.call(navigator, constraints, resolve, reject);
                    });
                };
            }
            navigator.mediaDevices.getUserMedia =
                navigator.mediaDevices.getUserMedia ||
                navigator.webkitGetUserMedia ||
                navigator.mozGetUserMedia;

            return new Promise((resolve, reject) => {
                navigator.mediaDevices
                    .getUserMedia({ video: true, audio: true })
                    .then(stream => {
                        resolve(stream);
                    })
                    .catch(err => {
                        getUserMediaError = true;
                        reject(err);
                    });
            });
        }

        function startRecording() {
            recordedBlobs = [];
            let options = {mimeType: 'video/webm;codecs=vp9,opus'};
            mediaRecorder = new MediaRecorder(record_stream, options);
                
            mediaRecorder.onstop = (event) => {
                this.downloadRecording(recordedBlobs);
            };

            mediaRecorder.ondataavailable = (event) => {
                if (event.data && event.data.size > 0) {
                recordedBlobs.push(event.data);
                }
            }
            mediaRecorder.start();
        }

        function stopRecording() {
            mediaRecorder.stop();
        }

        function downloadRecording(recordedChunks) {

            loading();
            let time = Date.now();
            let file_name = 'video';
            file_name = 'record_verify-' + time + '.webm';

            const blob = new Blob(recordedChunks, {'type': 'video/webm'});
            const url = URL.createObjectURL(blob);
            const formData = new FormData();

            formData.append('video', blob, file_name);
            csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            formData.append( "_token", csrf );
            
            fetch('/video_record_verify_upload', {
                    method: 'POST',
                    body: formData
                    })
                    .then(response => { 
                        console.log('upload success');
                        window.location.href = '{{route('chat2View')}}';
                    })
                    .catch(error => {console.log('error');})
            

            //下載至本機
            /*
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = file_name;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
            */
            
        }

    </script>
@stop