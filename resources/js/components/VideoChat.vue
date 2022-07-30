<template>
  <div>
    <div class="container">
      <div class="row">
        <div class="col">
          <div class="btn-group" role="group" style="flex-wrap:wrap">
            <button
              type="button"
              class="btn mr-2"
              v-for="user in allusers"
              :key="user.id"
              :class="generateBtnClass(getUserOnlineStatus(user.id))"
              :style="generateBtnStyle(getUserOnlineStatus(user.id))"
              @click="getUserOnlineStatus(user.id) ? placeVideoCall(user.id, user.name) : null"
            >
              {{ user.id }} {{ user.name }}
              <span v-if=getUserOnlineStatus(user.id) class="badge badge-light">上線中</span>
              <span v-else class="badge badge-light">下線</span>
            </button>
          </div>
        </div>
      </div>
      <!--Placing Video Call-->
      <div class="row mt-5" id="video-row">
        <div class="col-12 video-container" v-if="callPlaced">
          <video
            ref="userVideo"
            muted
            playsinline
            autoplay
            class="cursor-pointer"
            :class="isFocusMyself === true ? 'user-video' : 'partner-video'"
            @click=""
          />
          <video
            ref="partnerVideo"
            playsinline
            autoplay
            class="cursor-pointer"
            :class="isFocusMyself === true ? 'partner-video' : 'user-video'"
            @click=""
            v-if="videoCallParams.callAccepted"
          />
          <div class="partner-video" v-else>
            <div v-if="callPartner" class="column items-center q-pt-xl">
              <div class="col q-gutter-y-md text-center">
                <p class="q-pt-md">
                  <strong>{{ callPartner }}</strong>
                </p>
                <p>撥打中...</p>
              </div>
            </div>
          </div>
          <div class="action-btns">
            <button v-if="this.user_permission == 'admin'" type="button" class="btn btn-info" @click="toggleMuteAudio">
              {{ mutedAudio ? "關閉靜音" : "開啟靜音" }}
            </button>
            <button
              v-if="this.user_permission == 'admin'"
              type="button"
              class="btn btn-primary mx-4"
              @click="toggleMuteVideo"
            >
              {{ mutedVideo ? "顯示畫面" : "隱藏畫面" }}
            </button>
            <button type="button" class="btn btn-danger" @click="endCall">
              結束視訊通話
            </button>
          </div>
        </div>
      </div>
      <!-- End of Placing Video Call  -->

      <!-- Incoming Call  -->
      <div class="row" v-if="incomingCallDialog">
        <div class="col">
          <p>
            來自 <strong>{{ callerDetails.id }} {{ callerDetails.name }} 的通話要求</strong>
          </p>
          <div class="btn-group" role="group">
            <button
              type="button"
              class="btn btn-danger"
              data-dismiss="modal"
              @click="declineCall"
            >
              拒絕
            </button>
            <button
              type="button"
              class="btn btn-success ml-5"
              @click="acceptCall"
            >
              接受
            </button>
          </div>
        </div>
      </div>
      <!-- End of Incoming Call  -->
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
        callerSignal: null,
        callAccepted: false,
        channel: null,
        peer1: null,
        peer2: null,
      },
      mediaRecorder: null,
      mediaRecorder2: null,
      recordedBlobs: [],
      recordedBlobs2: [],
      csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    };
  },

  mounted() {
    this.initializeChannel(); // this initializes laravel echo
    this.initializeCallListeners(); // subscribes to video presence channel and listens to video events
  },
  computed: {
    incomingCallDialog() {
      if (
        this.videoCallParams.receivingCall &&
        this.videoCallParams.caller !== this.authuserid
      ) {
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
        if (data.type === "incomingCall") {
          let signal_data = '';
          $.ajax({
            async:false,
            type:'get',
            url:'/video/receive-call-user-signal-data',
            data:{
              signal_data_id:data.signalData
            },
            success:function(s_data){
              signal_data = s_data;
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
      });
    },

    async placeVideoCall(id, name) {
      await this.checkDevices();
      //console.log('deviceReady:' + this.deviceReady);
      if(!this.deviceReady)
      {
        alert('未搜尋到鏡頭或麥克風裝置');
        return;
      }
      this.callPlaced = true;
      this.callPartner = name;
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
      this.videoCallParams.peer1 = new Peer({
        initiator: true,
        trickle: false,
        stream: this.videoCallParams.stream,
        config: {
          iceServers: [iceserver[0],iceserver[1]],
        },
      });

      this.videoCallParams.peer1.on("signal", (data) => {
        //console.log(data);
        // send user call signal
        axios
          .post("/video/call-user", {
            user_to_call: id,
            //signal_data: JSON.stringify(data),
            signal_data: data,
            from: this.authuserid,
          })
          .then(() => {})
          .catch((error) => {
            console.log('signal axios error:' + error);
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
        if(this.user_permission == 'admin')
        {
          $.ajax({
            type:'post',
            url:'/admin/users/video_chat_verify_upload_init',
            data:{
              _token:this.csrf,
              verify_user_id:id
            },
            success:function(data){
              window.sessionStorage.setItem('verify_record_id', data.record_id);
            }
          });
          this.startRecording();
        }
      });

      this.videoCallParams.peer1.on("error", (err) => {
        console.log('peer1 error');
        console.log(err);
      });

      this.videoCallParams.peer1.on("close", () => {
        console.log("call closed caller");
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
            }
          });
          //console.log('ajaxoutput ' + signal_data);
          signal_data = JSON.parse(signal_data);
          //console.log(signal_data);
          if (signal_data.renegotiate) {
            console.log("renegotating");
          }
          if (signal_data.sdp) {
            this.videoCallParams.callAccepted = true;
            const updatedSignal = {
              ...signal_data,
              sdp: `${signal_data.sdp}\n`,
            };
            this.videoCallParams.peer1.signal(updatedSignal);
          }
        }
      });
      if(this.user_permission == 'admin')
      {
        if (!this.mutedVideo) this.toggleMuteVideo();
      }
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
          })
          .then(() => {})
          .catch((error) => {
            console.log('signal axios error:' + error);
          });
      });

      this.videoCallParams.peer2.on("stream", (stream) => {
        this.videoCallParams.callAccepted = true;
        this.$refs.partnerVideo.srcObject = stream;
      });

      this.videoCallParams.peer2.on("connect", () => {
        console.log("peer2 connected");
        this.videoCallParams.callAccepted = true;
        if(this.user_permission == 'admin')
        {
          $.ajax({
            type:'post',
            url:'/admin/users/video_chat_verify_upload_init',
            data:{
              _token:this.csrf,
              verify_user_id:this.videoCallParams.caller
            },
            success:function(data){
              window.sessionStorage.setItem('verify_record_id', data.record_id);
            }
          });
          this.startRecording();
        }
      });

      this.videoCallParams.peer2.on("error", (err) => {
        console.log('peer2 error');
        console.log(err);
      });

      this.videoCallParams.peer2.on("close", () => {
        console.log("call closed accepter");
      });

      this.videoCallParams.peer2.signal(this.videoCallParams.callerSignal);
      if(this.user_permission == 'admin')
      {
        if (!this.mutedVideo) this.toggleMuteVideo();
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

    declineCall() {
      this.videoCallParams.receivingCall = false;
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
      } else {
        this.$refs.userVideo.srcObject.getVideoTracks()[0].enabled = false;
        this.mutedVideo = true;
      }
    },

    stopStreamedVideo(videoElem) {
      const stream = videoElem.srcObject;
      const tracks = stream.getTracks();
      tracks.forEach((track) => {
        track.stop();
      });
      videoElem.srcObject = null;
    },

    endCall() {
      // if video or audio is muted, enable it so that the stopStreamedVideo method will work
      if (this.mutedVideo) this.toggleMuteVideo();
      if (this.mutedAudio) this.toggleMuteAudio();
      this.stopStreamedVideo(this.$refs.userVideo);
      if (this.authuserid === this.videoCallParams.caller)
      {
        try
        {
          this.videoCallParams.peer1.destroy();
        }
        catch(e)
        {
          console.log('peer has destroy');
        }
      } 
      else
      {
        try
        {
          this.videoCallParams.peer2.destroy();
        }
        catch(e)
        {
          console.log('peer has destroy');
        }
      }
      this.videoCallParams.channel.pusher.channels.channels[
        "presence-presence-video-channel"
      ].disconnect();
      if(this.user_permission == 'admin')
      {
        try{this.stopRecording();}
        catch(e){console.log(e);}
      }
      if(this.user_permission == 'admin')
      {
        window.sessionStorage.setItem('endcall_reload',true);
      }
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
        this.mediaRecorder = new MediaRecorder(this.$refs.partnerVideo.srcObject, options);
        this.mediaRecorder2 = new MediaRecorder(this.$refs.userVideo.srcObject, options);
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
        console.log('Recorder2 stopped: ', event);
        console.log('Recorded Blobs2: ', this.recordedBlobs2);
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
      console.log('MediaRecorder started', this.mediaRecorder);
      console.log('MediaRecorder2 started', this.mediaRecorder2);
    },

    stopRecording() {
      this.mediaRecorder.stop();
      this.mediaRecorder2.stop();
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
        .then( dev => this.gotDevices(dev))
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

/* Mobiel Styles */
@media only screen and (max-width: 768px) {
  .video-container {
    height: 50vh;
  }
}
</style>