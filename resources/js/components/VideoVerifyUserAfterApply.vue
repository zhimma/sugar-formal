<template>
  <div style="margin-top:1em;">
    <div v-if="videoCallParams.users.length>1"  class="video_status_online_intro">
      
        站方人員可從本站任一頁面跟您視訊，請等候站方人員撥打視訊通話給您，謝謝。
        
    </div>
    <div v-if="videoCallParams.users.length<=1" class="video_status_offline_intro">
    但很抱歉可提供視訊審核的站方人員不在線，
    您可以隨時在<a href="/dashboard/personalPage">專屬頁面</a>查看審核狀態。
    </div>  
 </div>
</template>
<script>

import Peer from "simple-peer";
//import { getPermissions } from "../helpers";
import LZString from "../lz-string.js";

export default {
  props: [
    "allusers",
    "authuserid",
    "authuser",
    "user_permission",
    "ice_server_json",
  ],
  data() {
    return {
      isFocusMyself: true,
      callPlaced: false,
      callPartner: null,
      mutedAudio: false,
      mutedVideo: false,
      audioSet: false,
      videoSet: false,
      deviceReady: false,
      getUserMediaError: false,
      videoCallParams: {
        users: [],
        stream: null,
        receivingCall: false,
        caller: null,
        dialingTo: null,
        callerSignal: null,
        callAccepted: false,
        channel: null,
        peer1: null,
        peer2: null,
        connecting_peer: null,
      },
      mediaRecorder: null,
      mediaRecorder2: null,
      recordedBlobs: [],
      recordedBlobs2: [],
      isNormalStop:false,
      isPeerError:false,    
      isAlerted:false,
      csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    };
  },

  mounted() {
        axios
          .post("/video/loading-video-page", {from_file:'VideoVerifyUser.vue'})
          .then(() => {    
          })
          .catch((error) => { 

            $("#error_message").text('loading-video-page axios error:' + error);
          });


        var old_beforeunload = $('body').attr('onbeforeunload');
        if(old_beforeunload==undefined) old_beforeunload = '';
        $('body').attr('onbeforeunload','video_beforeunload_act();');
        
    this.initializeChannel(); // this initializes laravel echo

    this.initializeCallListeners(); // subscribes to video presence channel and listens to video events        
        $('.self_auth_msg_before_video').html($('#app_after_apply').html()); 
  },
  updated: function(){$('.self_auth_msg_before_video').html($('#app_after_apply').html());},
  computed: {
    incomingCallDialog() {
      if (
        this.videoCallParams.receivingCall &&
        this.videoCallParams.caller !== this.authuserid
      ) {
        $('.mask_bg').hide();
      
        if($(".blbg").length>0 ) {
            $(".blbg").hide();
        }
       
        if( $("#tabPopM").length>0 ) {
            $("#tabPopM").hide();
        }        
      
        return true;
      }

      return false;
    },

    callerDetails() {      
      if (
        this.videoCallParams.caller &&
        this.videoCallParams.caller !== this.authuserid
      ) {
        
        const incomingCaller = this.allusers.filter(
          (user) => user.id === this.videoCallParams.caller
        );

        return {
          id: this.videoCallParams.caller,
          name: `${incomingCaller[0].name}`,
        };
      }     
      
      return null;
    },
  },
  methods: {
    initializeChannel() {
        
      this.videoCallParams.channel = window.Echo.join("presence-video-channel");
      
    },

    getMediaPermission() {
        
      return this.getPermissions()
        .then((stream) => {
            
          this.videoCallParams.stream = stream;

          if (this.$refs.userVideo) {
            
            this.$refs.userVideo.srcObject = stream;

          }

        })
        .catch((error) => {          
          console.log(error);
          $("#error_message").text(error);
        });
    },

    initializeCallListeners() {
         
      this.videoCallParams.channel.here((users) => {
        
        this.videoCallParams.users = users;

      });

      this.videoCallParams.channel.joining((user) => {
        // check user availability
        const joiningUserIndex = this.videoCallParams.users.findIndex(
          (data) => data.id === user.id
        );
        
        if (joiningUserIndex < 0) {
          this.videoCallParams.users.push(user);
        }
        
      });

      this.videoCallParams.channel.leaving((user) => {
        
        const leavingUserIndex = this.videoCallParams.users.findIndex(
          (data) => data.id === user.id
        );
        
        
        this.videoCallParams.users.splice(leavingUserIndex, 1);       

      });
      // listen to incomming call
      this.videoCallParams.channel.listen("StartVideoChat", ({ data }) => {
        
        if (data.type === "incomingCall" && data.userToCall == this.authuserid) {
          let signal_data = '';
          
          window.sessionStorage.setItem('verify_record_id', data.record_id);
          
          $.ajax({
            async:false,
            type:'get',
            url:'/video/receive-call-user-signal-data',
            data:{
              signal_data_id:data.signalData
            },
            success:function(s_data){ 
              signal_data = s_data;
            },
            error:function(xhr) {
              
            }
          });
          // add a new line to the sdp to take care of error
          //console.log('ajaxoutput ' + signal_data);
          signal_data = JSON.parse(signal_data);
          //console.log(signal_data);
          const updatedSignal = {
            ...signal_data,
            sdp: `${signal_data.sdp}\n`,
          };
          this.videoCallParams.receivingCall = true;
          this.videoCallParams.caller = data.from;
          this.videoCallParams.callerSignal = updatedSignal;
        }
        else if(data.type === 'abortDialCall' &&  data.to== this.authuserid) {

            this.videoCallParams.receivingCall = false;      
            
            if(this.videoCallParams.peer1!=null || this.videoCallParams.peer2!=null) {
            
                if((this.videoCallParams.peer1 ?? false) && this.videoCallParams.peer1.destroyed==false) {
                    this.videoCallParams.peer1.destroy();
                    this.videoCallParams.peer1 = null;
                }
                
                if((this.videoCallParams.peer2 ?? false) && this.videoCallParams.peer2.destroyed==false) {
                    this.videoCallParams.peer2.destroy();
                    this.videoCallParams.peer2 = null;
                }
                
                if((this.videoCallParams.connecting_peer ?? false) && this.videoCallParams.connecting_peer.destroyed==false) {
                    this.videoCallParams.connecting_peer.destroy();
                    this.videoCallParams.connecting_peer =null;
                }
                $(".blbg").show();
                $("#tabPopM").show();
                $("#tabPopM .bltext").text('無法接通視訊');             
            }
        } 
        else if(data.type ==='loadingVideoPage' || data.type ==='unloadingVideoPage' ) {
            if(data.from==this.videoCallParams.dialingTo  ||  data.from==this.videoCallParams.caller) {
                if(this.callPlaced==true || this.videoCallParams.receivingCall==true) {
                    $('.mask_bg').hide();
                    $('#mnger_leave_page_msg_block').show();
                    setTimeout(() => {
                        this.callPlaced = false;
                        this.videoCallParams.receivingCall=false;
                        if(this.user_permission == 'admin')
                        {
                            window.sessionStorage.setItem('endcall_reload',true);
                        }                
                        
                        location.reload();
                    }, 3000);                     
                }
            }
        }
   
      });
    },

    async placeVideoCall(id, name) {
            
      await this.checkDevices(); 

      if(!this.deviceReady)
      {
        alert('未搜尋到鏡頭或麥克風裝置');
        
        return;
      }
     
      this.callPlaced = true;
      this.callPartner = name;
      this.videoCallParams.dialingTo = id;        
      
      await this.getMediaPermission();
        
      if(this.getUserMediaError)
      {
        alert('未取得鏡頭或麥克風裝置權限');
        this.callPlaced = false;

        return;
      }

      const iceserver = JSON.parse(this.ice_server_json.trim());
      
      this.videoCallParams.peer1 = new Peer({
        initiator: true,
        trickle: false,
        stream: this.videoCallParams.stream,
        config: {
          iceServers: [iceserver[0],iceserver[1]],
        },
      });

      this.videoCallParams.peer1.on("signal", (data) => {
        // send user call signal
        axios
          .post("/video/call-user", {
            user_to_call: id,
            signal_data: data,
            from: this.authuserid,
          })
          .then(() => {                         
              
          })
          .catch((error) => {
            console.log('signal axios error:' + error);
            $("#error_message").text('signal axios error:' + error);
          });
      });

      this.videoCallParams.peer1.on("stream", (stream) => {       
        
        console.log("call streaming");      
        
        if (this.$refs.partnerVideo) {
          this.$refs.partnerVideo.srcObject = stream;
        }
        
      });

      this.videoCallParams.peer1.on("connect", () => {
        
        console.log("peer1 connected");
        this.videoCallParams.connecting_peer = this.videoCallParams.peer1;
        if(this.user_permission == 'normal')
        {
          $('#partner_video_screen').hide();
          $('#none_partner_video').show();
        }

        $('#connecting_msg_block').hide();
      });

      this.videoCallParams.peer1.on("data", (data) => {  
        console.log('peer1 receive data:');
        this.receive_data(data);
      });

      this.videoCallParams.peer1.on("error", (err) => {
        console.log('peer1 error');
        console.log(err);
        $("#error_message").text('peer1 error : ' + err);
        this.isPeerError = true;
        if(this.callPlaced==true) this.callPlaced=false;
        if(this.videoCallParams.receivingCall==true) this.videoCallParams.receivingCall=false;        
        if(err.toString().indexOf('Transport')>=0 && err.toString().indexOf('channel')>=0 && err.toString().indexOf('closed')>=0 ||  err.code=='ERR_DATA_CHANNEL') {
            $("#video_error_msg_block").show();
            setTimeout(() => {
                $(".blbg").show();
                $("#tabPopM").show();
                $("#tabPopM .bltext").text('視訊已關閉。');
              }, 5000);                       
        }
        else if(err.toString().indexOf('Connection')>=0 && err.toString().indexOf('failed')>=0 && err.toString().indexOf('Error:')>=0 || err.code=='ERR_CONNECTION_FAILURE') {
            $("#video_error_msg_block").show();
            $(".blbg").show();
            $("#tabPopM").show();
            $("#tabPopM .bltext").text('連線失敗，已中止視訊');        
        } 
        else if(err.toString().indexOf('Ice')>=0 && err.toString().indexOf('connection')>=0 && err.toString().indexOf('failed')>=0 || err.code=='ERR_ICE_CONNECTION_FAILURE') {
            $("#video_error_msg_block").show();
            if($(".blbg").length>0 && $("#tabPopM").length>0 && $("#tabPopM .bltext").length>0) {
                $(".blbg").css('z-index','40').show();
                $("#tabPopM").css('z-index','40').show();
                $("#tabPopM .bltext").text('視訊連線失敗，已中止視訊');   
            }  
            else if(this.isAlerted!=true) {
                alert('視訊連線失敗，已中止視訊');
                this.isAlerted = true;
                
                location.reload();
            }              
        }
        else {
            $("#video_error_msg_block").show();
            if($(".blbg").length>0 && $("#tabPopM").length>0 && $("#tabPopM .bltext").length>0) {
                $(".blbg").css('z-index','40').show();
                $("#tabPopM").css('z-index','40').show();
                $("#tabPopM .bltext").text('視訊錯誤：'+err.toString());   
            }
            else if(this.isAlerted!=true) {
                alert('視訊錯誤：'+err.toString());
                this.isAlerted = true;
                
                location.reload();
            }              
        }        
      });

      this.videoCallParams.peer1.on("close", () => {
        console.log("call closed caller");
        
        if(this.videoCallParams.receivingCall==true) this.videoCallParams.receivingCall=false;         

        if(this.callPlaced==true  && this.isPeerError!=true) {
        
            setTimeout(() => {
                $(".blbg").show();
                $("#tabPopM").show();
                $("#tabPopM .bltext").text('視訊已關閉。');
              }, 5000);        
            
        }         
        else if(this.callPlaced==true) this.callPlaced=false;
      });

      this.videoCallParams.channel.listen("StartVideoChat", ({ data }) => {   

        if (data.type === "callAccepted") {
          
          let signal_data = '';

          $.ajax({
            async:false,
            type:'get',
            url:'/video/receive-accept-call-signal-data',
            data:{
              signal_data_id:data.signal
            },
            success:function(s_data){ 
              signal_data = s_data;
            },
            error:function(xhr) {
            }
          });

          signal_data = JSON.parse(signal_data);

          if (signal_data.renegotiate) {
            console.log("renegotating");
          }
          if (signal_data.sdp) {
            this.videoCallParams.callAccepted = true;
            this.videoCallParams.dialingTo = null;
            const updatedSignal = {
              ...signal_data,
              sdp: `${signal_data.sdp}\n`,
            };
            this.videoCallParams.peer1.signal(updatedSignal);
          }
        }
        else if(data.type === 'declineCall' &&  data.to== this.authuserid) {
            $(".blbg").show();
            $("#tabPopM").show();
            $("#tabPopM .bltext").text('站方人員忙線中，暫時無法接聽視訊');        
        }       
      
      });
    },

    async acceptCall() {       
        
      await this.checkDevices();     
      
      //console.log('deviceReady:' + this.deviceReady);
      if(!this.deviceReady)
      {
        alert('未搜尋到鏡頭或麥克風裝置');

        return;
      }

      this.callPlaced = true;
      this.videoCallParams.callAccepted = true;

      await this.getMediaPermission();

      if(this.getUserMediaError)
      {
          
        alert('未取得鏡頭或麥克風裝置權限');
        this.callPlaced = false;

        return;
      }

      //console.log("iceserver_json: " + this.ice_server_json);
      const iceserver = JSON.parse(this.ice_server_json.trim());
      //console.log("iceserver: " + iceserver);
    
      $('.mask_bg').hide();
      $('#connecting_msg_block').show();
      
      this.videoCallParams.peer2 = new Peer({
        initiator: false,
        trickle: false,
        stream: this.videoCallParams.stream,
        config: {
          iceServers: [iceserver[0],iceserver[1]],
        },
      });   

      this.videoCallParams.receivingCall = false;
      this.videoCallParams.peer2.on("signal", (data) => {
        //console.log(data);
        axios
          .post("/video/accept-call", {
            //signal: JSON.stringify(data),
            signal: data,
            to: this.videoCallParams.caller,
            verify_record_id:window.sessionStorage.getItem('verify_record_id')
          })
          .then(() => {
    
          })
          .catch((error) => { 
            console.log('signal axios error:' + error);
            $("#error_message").text('signal axios error:' + error);
          });
      });

      this.videoCallParams.peer2.on("stream", (stream) => {
        this.videoCallParams.callAccepted = true;
        this.$refs.partnerVideo.srcObject = stream;  
        
      });

      this.videoCallParams.peer2.on("connect", () => {
        console.log("peer2 connected");
        this.videoCallParams.callAccepted = true;
        this.videoCallParams.connecting_peer = this.videoCallParams.peer2;

        $('#connecting_msg_block').hide();
      });

      this.videoCallParams.peer2.on("data", (data) => {
        console.log('peer2 receive data:');
        this.receive_data(data); 
      });

      this.videoCallParams.peer2.on("error", (err) => {
        console.log('peer2 error');
        console.log(err);
        $("#error_message").text('peer2 error : ' + err);
        this.isPeerError = true;
        if(this.callPlaced==true) this.callPlaced=false;
        if(this.videoCallParams.receivingCall==true) this.videoCallParams.receivingCall=false;        
        if(err.toString().indexOf('Transport')>=0 && err.toString().indexOf('channel')>=0 && err.toString().indexOf('closed')>=0  ||  err.code=='ERR_DATA_CHANNEL') {
            $("#video_error_msg_block").show();
            setTimeout(() => {
                $(".blbg").show();
                $("#tabPopM").show();
                $("#tabPopM .bltext").text('視訊已關閉。');
              }, 5000);                       
        }  
        else if(err.toString().indexOf('Connection')>=0 && err.toString().indexOf('failed')>=0 && err.toString().indexOf('Error:')>=0 || err.code=='ERR_CONNECTION_FAILURE') {
            $("#video_error_msg_block").show();
            $(".blbg").show();
            $("#tabPopM").show();
            $("#tabPopM .bltext").text('連線失敗，已中止視訊');       
        }
        else if(err.toString().indexOf('Ice')>=0 && err.toString().indexOf('connection')>=0 && err.toString().indexOf('failed')>=0 || err.code=='ERR_ICE_CONNECTION_FAILURE') {
            $("#video_error_msg_block").show();
            if($(".blbg").length>0 && $("#tabPopM").length>0 && $("#tabPopM .bltext").length>0) {
                $(".blbg").css('z-index','40').show();
                $("#tabPopM").css('z-index','40').show();
                $("#tabPopM .bltext").text('視訊連線失敗，已中止視訊');   
            } 
            else if(this.isAlerted!=true) {
                alert('視訊連線失敗，已中止視訊');
                this.isAlerted = true;

                location.reload();
            }              
        } 
        else {
            $("#video_error_msg_block").show();
            if($(".blbg").length>0 && $("#tabPopM").length>0 && $("#tabPopM .bltext").length>0) {
                $(".blbg").css('z-index','40').show();
                $("#tabPopM").css('z-index','40').show();
                $("#tabPopM .bltext").text('視訊錯誤：'+err.toString());   
            } 
            else if(this.isAlerted!=true) {
                alert('視訊錯誤：'+err.toString());
                this.isAlerted = true;

                location.reload();
            }              
        }
      });

      this.videoCallParams.peer2.on("close", () => {
        console.log("call closed accepter");
        
        if(this.videoCallParams.receivingCall==true) this.videoCallParams.receivingCall=false;        
        if(this.callPlaced==true && this.isPeerError!=true) {
        
            setTimeout(() => {
                $(".blbg").show();
                $("#tabPopM").show();
                $("#tabPopM .bltext").text('視訊已關閉。');
              }, 5000);        
            
        } 
        else if(this.callPlaced==true) this.callPlaced=false;
      });

      this.videoCallParams.peer2.signal(this.videoCallParams.callerSignal);

      if(this.user_permission == 'normal')
      {
        $('#partner_video_screen').hide();
        $('#none_partner_video').show();
      }
    },

    toggleCameraArea() {
        
      if (this.videoCallParams.callAccepted) {
          
        this.isFocusMyself = !this.isFocusMyself;

      } 
    },

    getUserOnlineStatus(id) {
      const onlineUserIndex = this.videoCallParams.users.findIndex(
        (data) => data.id === id
      );
      if (onlineUserIndex < 0) {
        return false;
      }
      return true;
    },

    async declineCall() {

      this.videoCallParams.receivingCall = false;

      await axios
          .post("/video/decline-call", {
            to: this.videoCallParams.caller,
            verify_record_id:window.sessionStorage.getItem('verify_record_id')
          })
          .then(() => {
          
          })
          .catch((error) => {
            $("#error_message").text('decline axios error:' + error);
          }); 
      
      $('.mask_bg').hide();
      $('#video_only_reload_msg_block').show();      
      
      location.reload();

    },

    toggleMuteAudio() {
      if (this.mutedAudio) {
        this.$refs.userVideo.srcObject.getAudioTracks()[0].enabled = true;
        this.mutedAudio = false;
      } else {
        this.$refs.userVideo.srcObject.getAudioTracks()[0].enabled = false;
        this.mutedAudio = true;
      }
    },

    toggleMuteVideo() {
      if (this.mutedVideo) {
        this.$refs.userVideo.srcObject.getVideoTracks()[0].enabled = true;
        this.mutedVideo = false;
                
        if((this.videoCallParams.connecting_peer ?? false) && this.videoCallParams.connecting_peer.destroyed==false)
        {
          this.videoCallParams.connecting_peer.send('mutedVideo_false');
        }
      } else {
        this.$refs.userVideo.srcObject.getVideoTracks()[0].enabled = false;
        this.mutedVideo = true;

        if((this.videoCallParams.connecting_peer ?? false) && this.videoCallParams.connecting_peer.destroyed==false)
        {
          this.videoCallParams.connecting_peer.send('mutedVideo_true');
        }
      }
    },

    receive_data(data) {    
    
      if(new TextDecoder('utf-8').decode(data) === 'mutedVideo_false')
      {      
      
        console.log('Log_mutedVideo_false');
        $('#partner_video_screen').show();
        $('#none_partner_video').hide();

      }
      else if(new TextDecoder('utf-8').decode(data) === 'mutedVideo_true')
      {
        console.log('Log_mutedVideo_true');
        $('#partner_video_screen').hide();
        $('#none_partner_video').show();
      }
    },

    stopStreamedVideo(videoElem) {
      const stream = videoElem.srcObject;
      const tracks = stream.getTracks();
      this.isNormalStop = true;
      tracks.forEach((track) => {
        track.stop();
      });
      videoElem.srcObject = null; 
    },

    endCall() {
        if(this.isPeerError!=true) {
          $('.mask_bg').hide();
          $('.video_error_msg_block').show();
        }
            
      // if video or audio is muted, enable it so that the stopStreamedVideo method will work
      if (this.mutedVideo) this.toggleMuteVideo();
      if (this.mutedAudio) this.toggleMuteAudio();

      try
      {
        this.videoCallParams.connecting_peer.send('end_call');
      }
      catch(e)
      {
        console.log(e);

            if(this.videoCallParams.dialingTo!=null) {
                axios
                  .post("/video/abort-dial-call", {
                    to: this.videoCallParams.dialingTo,
                  })
                  .then(() => {
    
                  })
                  .catch((error) => {  
                    $("#error_message").text('abort-dial-call axios error:' + error);
                  });      
            }        
      }
      
      $('.mask_bg').hide();
      $('#video_only_reload_msg_block').show();

      setTimeout(() => {
        this.callPlaced = false;

        location.reload();
      }, 3000);
    },

    generateBtnClass(onlinestatus) {
      if(onlinestatus){
        return 'btn-success'
      }
      else{
        return 'btn-secondary disabled'
      }
    },

    generateBtnStyle(onlinestatus) {
      if(onlinestatus){
        return ''
      }
      else{
        return 'display:none;'
      }
    },

    //video record
    startRecording() {
      this.recordedBlobs = [];
      this.recordedBlobs2 = [];
      let options = {mimeType: 'video/webm;codecs=vp9,opus'};
      try {
        if(this.$refs.partnerVideo.srcObject!=null && this.$refs.partnerVideo.srcObject!=undefined) {
        this.mediaRecorder = new MediaRecorder(this.$refs.partnerVideo.srcObject, options);
        }
        else {
            $('.mask_bg').hide();
            $('#video_error_msg_block').show().find('.loading_text').html('視訊連線失敗!<br>找不到相關的視訊，請重新操作。');
        
            setTimeout(() => {
                this.callPlaced = false;
                
                  if(this.user_permission == 'admin')
                  {
                    window.sessionStorage.setItem('endcall_reload',true);
                  }                

                location.reload();
            }, 3000);         
        }

        if(this.$refs.userVideo.srcObject!=null && this.$refs.userVideo.srcObject!=undefined) {
            this.mediaRecorder2 = new MediaRecorder(this.$refs.userVideo.srcObject, options);
        }
        else {
            $('.mask_bg').hide();
            $('#video_error_msg_block').show().find('.loading_text').html('視訊連線失敗!<br>找不到可用視訊，請重新操作。');
        
            setTimeout(() => {
                this.callPlaced = false;
                location.reload();
            }, 3000);        
        }
         
      } catch (e) {
        console.error('Exception while creating MediaRecorder:', e);
        return;
      }
      console.log('Created MediaRecorder', this.mediaRecorder, 'with options', options);
      console.log('Created MediaRecorder2', this.mediaRecorder2, 'with options', options);
      this.mediaRecorder.onstop = (event) => {
        
        console.log('Recorder stopped: ', event);
        console.log('Recorded Blobs: ', this.recordedBlobs);
        
        this.downloadRecording(this.recordedBlobs,'partner');
        
      };
      this.mediaRecorder2.onstop = (event) => {

        this.downloadRecording(this.recordedBlobs2,'user');
           
      };
      this.mediaRecorder.ondataavailable = (event) => {
        
        console.log('handleDataAvailable', event);
        
        if (event.data && event.data.size > 0) {
          
          this.recordedBlobs.push(event.data);
          
        }
      }
      this.mediaRecorder2.ondataavailable = (event) => {
        console.log('handleDataAvailable2', event);
        if (event.data && event.data.size > 0) {
          this.recordedBlobs2.push(event.data);
        }
      }
      
      this.mediaRecorder.start();
      
      this.mediaRecorder2.start();
    },

    stopRecording() {
      if(this.mediaRecorder.state!='inactive') this.mediaRecorder.stop();
      
      if(this.mediaRecorder2.state!='inactive') this.mediaRecorder2.stop();
    },

    downloadRecording(recordedChunks,who) {
      let verify_record_id = window.sessionStorage.getItem('verify_record_id')
      let time = Date.now();
      let file_name = 'video';
      switch (who)
      {
        case 'user':
          file_name = 'admin_verify-' + time + '.webm';
          break;
        case 'partner':
          file_name = 'partner_verify-' + time + '.webm';
          break;
      }
      const blob = new Blob(recordedChunks, {'type': 'video/webm'});
      const url = URL.createObjectURL(blob);
      const formData = new FormData();
      formData.append('video', blob, file_name);
      formData.append('who', who);
      formData.append('verify_record_id', verify_record_id);
      formData.append( "_token", this.csrf );

      const formDataObj = {};
      
      formData.forEach((value, key) => (formDataObj[key] = value));
      
      fetch('/admin/users/video_chat_verify_upload', {
              method: 'POST',
              body: formData
              })
              .then(response => { console.log('upload success');})
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
    },

    checkDevices() {
      return navigator.mediaDevices.enumerateDevices()
        .then( dev => {this.gotDevices(dev); } )        
        .catch( err => console.warn(err));
    },
    
    gotDevices(deviceInfos) {
      //console.log(deviceInfos)
      this.audioSet = false;
      this.videoSet = false;
      for (let i = 0; i !== deviceInfos.length; ++i) {
        const deviceInfo = deviceInfos[i];
        if (deviceInfo.kind === 'audioinput')
        {
          this.audioSet = true;
        }
        else if (deviceInfo.kind === 'videoinput')
        {
          this.videoSet = true;
        }
      }
      //console.log(this.audioSet);
      //console.log(this.videoSet);
      //console.log((this.audioSet && this.videoSet));
      this.deviceReady = (this.audioSet && this.videoSet);
      //console.log(this.deviceReady);
    },

    getPermissions() {
      // Older browsers might not implement mediaDevices at all, so we set an empty object first
      if (navigator.mediaDevices === undefined) {
          navigator.mediaDevices = {};
      }

      // Some browsers partially implement mediaDevices. We can't just assign an object
      // with getUserMedia as it would overwrite existing properties.
      // Here, we will just add the getUserMedia property if it's missing.
      if (navigator.mediaDevices.getUserMedia === undefined) {
          navigator.mediaDevices.getUserMedia = function(constraints) {
              // First get ahold of the legacy getUserMedia, if present
              const getUserMedia =
                  navigator.webkitGetUserMedia || navigator.mozGetUserMedia;

              // Some browsers just don't implement it - return a rejected promise with an error
              // to keep a consistent interface
              if (!getUserMedia) {
                  return Promise.reject(
                      new Error("getUserMedia is not implemented in this browser")
                  );
              }

              // Otherwise, wrap the call to the old navigator.getUserMedia with a Promise
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
                  this.getUserMediaError = true;
                  reject(err);
                  //   throw new Error(`Unable to fetch stream ${err}`);
              });
      });
    },
  },
};
</script>

<style scoped>
#video-row {
  width: 700px;
  max-width: 90vw;
}

#incoming-call-card {
  border: 1px solid #0acf83;
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

.video-container .user-video {
  width: 30%;
  position: absolute;
  left: 10px;
  bottom: 10px;
  border: 1px solid #fff;
  border-radius: 6px;
  z-index: 2;
}

.video-container .partner-video {
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

/* Mobiel Styles */
@media only screen and (max-width: 768px) {
  .video-container {
    height: 50vh;
  }
}

.mask_bg {
    width: 100%;
    height: 100%;
    width: 100%;
    height: 100%;
    position: fixed;
    top: 0px;
    left: 0;
    background: rgba(0,0,0,0.8);
    z-index: 9;
    display: none;
}

    .loading {
        height: 100%;
        background-image: url(/new/images/loading.svg);
        background-repeat: no-repeat;
        background-size: 180px 180px;
        background-position: center;
        text-align: center;
    }
    
    .loading_text {
        position: relative;
        font-size: 20px;
        font-weight: bold;
        top: 35%;
        color: #f14a6c;
        z-index:9;
        letter-spacing:0.2em;
    } 
</style>