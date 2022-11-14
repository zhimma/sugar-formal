<template>
  <div>
    <div class="container">
      <div class="row">
        <div class="col">
          <div class="btn-group" role="group" style="flex-wrap:wrap;display:inline-block;" v-for="user in allusers">
            <button
              type="button"
              class="btn mr-2"
              :key="user.id"
              :class="generateBtnClass(getUserOnlineStatus(user.id))"
              :style="generateBtnStyle(getUserOnlineStatus(user.id))"
              @click="getUserOnlineStatus(user.id) ? placeVideoCall(user.id, user.name) : null"
            >
              {{ user.id }} {{ user.name }}
              <span v-if=getUserOnlineStatus(user.id) class="badge badge-light">上線中</span>
              <span v-else class="badge badge-light">下線</span>
            </button>
            <div style="text-align:center;">
                <a             
                target="_blank"
                :href="generateBtnUserAdvInfoUrl(user.id)"  
                :style="generateBtnStyle(getUserOnlineStatus(user.id))"
                >{{ user.id }} {{ user.name }}</a>
            </div>
          </div>
        </div>
      </div>
      <!--Placing Video Call-->
      <div class="row mt-5" id="video-row">
        <div class="col-12 video-container" v-if="callPlaced">
          <video
            id="user_video_screen"
            ref="userVideo"
            muted
            playsinline
            autoplay
            class="cursor-pointer"
            :class="isFocusMyself === true ? 'user-video' : 'partner-video'"
            @click=""
          />

          <img 
            id="none_partner_video"
            src="/new/images/sg_admin.jpg"
            style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"
          />

          <video
            id="partner_video_screen"
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
          <div class="error_message">
            <strong><li id="error_message" style="color:red;"></li></strong>
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
    <div class="video_chat_mask_bg mask_bg">
        <div class="loading"><span class="loading_text">請稍等,正在上傳視訊<br><br>請勿重新整理<br>或離開本頁面</span></div>
    </div>
    <div class="mask_bg" id="connecting_msg_block">
        <div class="loading"><span class="loading_text">正在嘗試連線視訊<br><br>請勿重新整理<br>或離開本頁面</span></div>
    </div>
    <div class="mask_bg" id="break_by_partner_before_connect_msg_block">
        <div class="loading"><span class="loading_text">失敗！！！<br><br>尚未成功連線<br>但會員已自行結束通話<br>殘存視訊上傳中<br><br>請勿重新整理<br>或離開本頁面</span></div>
    </div>
    <div class="mask_bg" id="break_by_partner_as_even_not_start_msg_block">
        <div class="loading"><span class="loading_text">失敗！！！<br><br>會員已自行結束通話<br>無法接通視訊<br><br>3秒後將自動重新整理頁面</span></div>
    </div>    
    <div class="mask_bg" id="video_error_msg_block">
        <div class="loading"><span class="loading_text"></span></div>
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
      isUploading: {user:false,partner:false},
      isUploaded: {user:false,partner:false},
      isNormalStop:false,
      isPeerError:false,
      uploadedIntervalId:0,
      uploadedResponse:{user:null,partner:null},
      csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    };
  },

  mounted() {
        var log_arr = {
            from_file:'VideoChat.vue'
            ,title:'start mounted@export default@VideoChat.vue'
            ,method:'mounted@export default'
            ,step:'start'
            ,act:'this.initializeChannel(); '
            ,act_step:'before'
        };
        log_video_chat_process(log_arr);  
    this.initializeChannel(); // this initializes laravel echo
        log_arr.title = 'ing mounted@export default@VideoChat.vue';
        log_arr.act_step = 'after';
        log_arr.step = 'ing';
        log_video_chat_process(log_arr);
        log_arr.act = 'this.initializeCallListeners();';
        log_arr.act_step = 'before';
        log_video_chat_process(log_arr);  
    this.initializeCallListeners(); // subscribes to video presence channel and listens to video events
        log_arr.act = 'this.initializeCallListeners();';
        log_arr.act_step = 'after';
        log_arr.step='end';
        log_arr.title = 'end mounted@export default@VideoChat.vue';
        log_video_chat_process(log_arr);  
  },
  computed: {
    incomingCallDialog() {
        var log_arr = {
            from_file:'VideoChat.vue'
            ,title:'start incomingCallDialog@computed@export default@VideoChat.vue'
            ,method:'incomingCallDialog@computed@export default'
            ,step:'start'
            ,topic:'this.videoCallParams.receivingCall &&  this.videoCallParams.caller !== this.authuserid'
            ,topic_step:'before'
            ,data:{receivingCall:this.videoCallParams.receivingCall
                ,caller:this.videoCallParams.caller
                ,authuserid:this.authuserid
            }
        };
        log_video_chat_process(log_arr); 
      if (
        this.videoCallParams.receivingCall &&
        this.videoCallParams.caller !== this.authuserid
      ) {
        log_arr.step = 'end';
        log_arr.topic_step='after true';
        log_arr.title = 'return true end incomingCallDialog@computed@export default@VideoChat.vue';
        log_video_chat_process(log_arr); 
        return true;
      }
      log_arr.step = 'end';
      log_arr.topic_step='after false';
      log_arr.title = 'return false end incomingCallDialog@computed@export default@VideoChat.vue';
      log_video_chat_process(log_arr); 
      return false;
    },

    callerDetails() {
        var log_arr = {
            from_file:'VideoChat.vue'
            ,title:'start callerDetails@computed@export default@VideoChat.vue'
            ,method:'callerDetails@computed@export default'
            ,step:'start'
            ,topic:'this.videoCallParams.caller &&  this.videoCallParams.caller !== this.authuserid'
            ,topic_step:'before'
            ,data:{caller:this.videoCallParams.caller
                ,authuserid:this.authuserid
            }
        };
        log_video_chat_process(log_arr); 
               
      if (
        this.videoCallParams.caller &&
        this.videoCallParams.caller !== this.authuserid
      ) {
        log_arr.step = 'ing';
        log_arr.topic_step='after true';
        log_arr.title = 'ing callerDetails@computed@export default@VideoChat.vue';
        log_arr.act = 'const incomingCaller = this.allusers.filter(';
        log_arr.act_step = 'before';
        log_video_chat_process(log_arr); 
        
        const incomingCaller = this.allusers.filter(
          (user) => user.id === this.videoCallParams.caller
        );
        
        log_arr.act_step = 'after';
        log_arr.step = 'end';
        log_arr.title = 'return {id: this.videoCallParams.caller,name: `${incomingCaller[0].name}`}  end callerDetails@computed@export default@VideoChat.vue';
        log_arr.data = {
          id: this.videoCallParams.caller,
          name: `${incomingCaller[0].name}`,
        };
        log_video_chat_process(log_arr); 

        return {
          id: this.videoCallParams.caller,
          name: `${incomingCaller[0].name}`,
        };
      }
        log_arr.act_step = 'after';
        log_arr.topic_step = 'return null';
        log_arr.step = 'end';
        log_arr.title = 'return null  end callerDetails@computed@export default@VideoChat.vue';
        log_arr.data = null;
        log_video_chat_process(log_arr);       
      
      return null;
    },
  },
  methods: {
    initializeChannel() {
        var log_arr = {
            from_file:'VideoChat.vue'
            ,title:'start initializeChannel@methods@export default@VideoChat.vue'
            ,method:'initializeChannel@methods@export default'
            ,step:'start'
            ,act:'this.videoCallParams.channel = window.Echo.join("presence-video-channel");'
            ,act_step:'before'
        };
        log_video_chat_process(log_arr);
      this.videoCallParams.channel = window.Echo.join("presence-video-channel");
      
      log_arr.topic_step='after';
      log_arr.step = 'end';
      log_arr.act_step='before';
      log_arr.title = 'end initializeChannel@methods@export default@VideoChat.vue';
      log_video_chat_process(log_arr);
    },

    getMediaPermission() {
        var log_arr = {
            from_file:'VideoChat.vue'
            ,title:'start and before reutrn this.getPermissions() end getMediaPermission@methods@export default@VideoChat.vue'
            ,method:'getMediaPermission@methods@export default'
            ,step:'start&end'
        };
        log_video_chat_process(log_arr);     
      return this.getPermissions()
        .then((stream) => {
            var log_arr = {
                from_file:'VideoChat.vue'
                ,title:'then in return this.getPermissions()@getMediaPermission@methods@export default@VideoChat.vue'
                ,method:'then@this.getPermissions()@getMediaPermission@methods@export default'
                ,step:'start'
                ,act:'this.videoCallParams.stream = stream;'
                ,act_step:'before'
            };
            log_video_chat_process(log_arr);             
          this.videoCallParams.stream = stream;
            log_arr.act_step = 'after';
            log_video_chat_process(log_arr);             
            log_arr.topic = 'if (this.$refs.userVideo)';
            log_arr.topic_step = 'before';
            log_arr.act_step = '';
            log_arr.act = '';
            log_video_chat_process(log_arr);             
          if (this.$refs.userVideo) {
            log_arr.topic_step = 'after true';
            log_arr.act_step = 'this.$refs.userVideo.srcObject = stream;';
            log_arr.act = 'before';
            log_video_chat_process(log_arr);
            
            this.$refs.userVideo.srcObject = stream;
            
            log_arr.act = 'after';
            log_video_chat_process(log_arr);          
          }
          
          log_arr.step='end';
          log_video_chat_process(log_arr);
        })
        .catch((error) => {
            var log_arr = {
                from_file:'VideoChat.vue'
                ,title:'catch in return this.getPermissions()@getMediaPermission@methods@export default@VideoChat.vue'
                ,method:'catch@this.getPermissions()@getMediaPermission@methods@export default'
                ,step:'within'
                ,data:{error:error}
            };
            log_video_chat_process(log_arr);                         
          console.log(error);
          $("#error_message").text(error);
        });
    },

    initializeCallListeners() {
        var initializeCallListeners_log_arr = {
            from_file:'VideoChat.vue'
            ,title:'start initializeCallListeners@methods@export default@VideoChat.vue'
            ,method:'initializeCallListeners@methods@export default'
            ,step:'start'
        };
        log_video_chat_process(initializeCallListeners_log_arr); 
        
      this.videoCallParams.channel.here((users) => {
        var log_arr = {
            from_file:'VideoChat.vue'
            ,title:'start this.videoCallParams.channel.here((users) =>@initializeCallListeners@methods@export default@VideoChat.vue'
            ,method:'this.videoCallParams.channel.here((users) =>@initializeCallListeners@methods@export default'
            ,step:'start'
            ,act:'this.videoCallParams.users = users;'
            ,act_step:'before'
        };
        log_video_chat_process(log_arr);             
        this.videoCallParams.users = users;
        log_arr.title = 'end this.videoCallParams.channel.here((users) =>@initializeCallListeners@methods@export default@VideoChat.vue';
        log_arr.act_step='after';
        log_arr.step = 'end';
        log_video_chat_process(log_arr);
      });

      this.videoCallParams.channel.joining(async  (user) => {
        var log_arr = {
            from_file:'VideoChat.vue'
            ,title:'start this.videoCallParams.channel.joining((async  users) =>@initializeCallListeners@methods@export default@VideoChat.vue'
            ,method:'this.videoCallParams.channel.joining(async  (users) =>@initializeCallListeners@methods@export default'
            ,step:'start'
            ,act:'const joiningUserIndex = this.videoCallParams.users.findIndex('
            ,act_step:'before'
            ,data:user
        };
        log_video_chat_process(log_arr);   
        // check user availability
        await fetch('/admin/users/video_chat_get_users')
                .then((response) => {
                    return response.json();
                     
                })
                .then((response)=>{
                    this.allusers = response;
                }
               
               );

        var joiningUserIndex = this.videoCallParams.users.findIndex(
          function(data) {return data.id === user.id;}
        );
        log_arr.step = 'ing';
        log_arr.act_step = 'after';
        log_arr.topic = 'if (joiningUserIndex < 0) ';
        log_arr.topic_step = 'before';
        log_arr.data = {joiningUserIndex:joiningUserIndex};
        log_video_chat_process(log_arr);   
        if (joiningUserIndex < 0) {
          this.videoCallParams.users.push(user);
        }
        
        this.videoCallParams.users.findIndex(
          function(data) {return data.id === user.id;}
        );
        
        log_arr.topic_step = 'after';
        log_arr.act_step = log_arr.act  = '';
        log_arr.step = 'end';
        log_video_chat_process(log_arr);  
        
      });

      this.videoCallParams.channel.leaving((user) => {
        var log_arr = {
            from_file:'VideoChat.vue'
            ,title:'start this.videoCallParams.channel.leaving((user) =>@initializeCallListeners@methods@export default@VideoChat.vue'
            ,method:'this.videoCallParams.channel.leaving((user) =>@initializeCallListeners@methods@export default'
            ,step:'start'
            ,act:'const leavingUserIndex = this.videoCallParams.users.findIndex('
            ,act_step:'before'
            ,data:user
        };
        log_video_chat_process(log_arr); 
        
        const leavingUserIndex = this.videoCallParams.users.findIndex(
          (data) => data.id === user.id
        );
        
        log_arr.act_step = 'after';
        log_arr.step = 'ing';
        log_arr.data = null;
        log_video_chat_process(log_arr)
        
        log_arr.act = 'this.videoCallParams.users.splice(leavingUserIndex, 1);';
        log_arr.act_step = 'before';        
        log_arr.data = {leavingUserIndex:leavingUserIndex};
        log_video_chat_process(log_arr)
        
        this.videoCallParams.users.splice(leavingUserIndex, 1);
        if(this.callPlaced==true) this.callPlaced=false;
        if(this.videoCallParams.receivingCall==true) this.videoCallParams.receivingCall=false;
        log_arr.act_step = 'after';
        log_arr.step = 'end';
        log_video_chat_process(log_arr)
      });
      // listen to incomming call
      this.videoCallParams.channel.listen("StartVideoChat", ({ data }) => {
        
        var log_arr = {url:location.href
                ,from_file:'VideoChat.vue'
                ,title:'this.videoCallParams.channel.listen(StartVideoChat)_start_in_VideoChat.vue'
                ,data:data
                ,method:'initializeCallListeners'
                ,step:'ing'
                ,act:'this.videoCallParams.channel.listen("StartVideoChat", ({ data }) =>'
                ,act_step:'start'
             };
        log_video_chat_process(log_arr);
        if (data.type === "incomingCall" && data.userToCall == this.authuserid) {
          let signal_data = '';

          log_arr.title = '';
          log_arr.ajax_url = '/video/receive-call-user-signal-data';
          log_arr.ajax_step = 'before';
          log_arr.ajax_sdata = {signal_data_id:data.signalData};
          log_arr.topic='data.type === "incomingCall"';
          log_arr.topic_step='after true';
          log_video_chat_process(log_arr );          
          $.ajax({
            async:false,
            type:'get',
            url:'/video/receive-call-user-signal-data',
            data:{
              signal_data_id:data.signalData
            },
            success:function(s_data){

              log_video_chat_process({
                            title:'after_success_ajax_receive-call-user-signal-data_in_VideoChat.vue'
                            ,ajax_rdata:s_data
                            ,ajax_url:'/video/receive-call-user-signal-data'
                            ,ajax_step:'success'
                            ,ajax_sdata:this.data
                         }
              ); 
              signal_data = s_data;
            },
            error:function(xhr) {
              $.post('/video/log_video_chat_process'
                        ,{url:location.href
                            ,title:'after_error_ajax_receive-call-user-signal-data_in_VideoChat.vue'
                            ,ajax_error:xhr
                            ,ajax_url:'/video/receive-call-user-signal-data'
                            ,ajax_step:'error'
                            ,ajax_sdata:this.data
                         }
              );        
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
                
                $('.mask_bg').hide();
                $("#break_by_partner_as_even_not_start_msg_block").show();           
            
                setTimeout(() => {
                                this.callPlaced = false;
                                
                                  if(this.user_permission == 'admin')
                                  {
                                    window.sessionStorage.setItem('endcall_reload',true);
                                  }                    
            
                                log_arr.step = 'end';
                                log_arr.act = 'location.reload();';
                                log_arr.act_step = 'before';
                                log_video_chat_process(log_arr);
                                
                                location.reload();
                            }, 3000);            
                        
            
            }
        } 
      });
        
        initializeCallListeners_log_arr.step='end';
        log_video_chat_process(initializeCallListeners_log_arr);     
    },

    async placeVideoCall(id, name) {
        var pvc_log_arr = {
            from_file:'VideoChat.vue'
            ,title:'start async placeVideoCall(id, name)@methods@export default@VideoChat.vue'
            ,method:'async placeVideoCall(id, name)@methods@export default'
            ,step:'start'
            ,act:'await this.checkDevices();'
            ,act_step:'before'
            ,data:{id:id,name:name}
        };
        log_video_chat_process(pvc_log_arr);      
      await this.checkDevices();
        pvc_log_arr.act_step = 'after';
        pvc_log_arr.step = 'ing';
        pvc_log_arr.topic = 'if(!this.deviceReady)';
        pvc_log_arr.topic_step = 'before';
        pvc_log_arr.data = {this_deviceReady:this.deviceReady};
        log_video_chat_process(pvc_log_arr);      

      if(!this.deviceReady)
      {
        alert('未搜尋到鏡頭或麥克風裝置');
        
        pvc_log_arr.act_step = pvc_log_arr.act  ='';
        pvc_log_arr.step = 'end';
        pvc_log_arr.topic_step = 'after true';
        pvc_log_arr.title = 'not deviceReady';
        log_video_chat_process(pvc_log_arr);
        
        return;
      }
        pvc_log_arr.act_step = pvc_log_arr.act  ='';
        pvc_log_arr.topic_step = 'after false';
        pvc_log_arr.title = 'is deviceReady';
        log_video_chat_process(pvc_log_arr);
     
      this.callPlaced = true;
      this.callPartner = name;
      this.videoCallParams.dialingTo = id;

        pvc_log_arr.act  ='await this.getMediaPermission();';
        pvc_log_arr.act_step = 'before'; 
        pvc_log_arr.topic = pvc_log_arr.topic_step = '';
        pvc_log_arr.title = '';
        log_video_chat_process(pvc_log_arr); 
      
      await this.getMediaPermission();
      
        pvc_log_arr.topic = 'if(this.getUserMediaError)';
        pvc_log_arr.topic_step = 'before';
        pvc_log_arr.act_step = 'after';
        log_video_chat_process(pvc_log_arr); 
        
      if(this.getUserMediaError)
      {
        pvc_log_arr.act  ='alert;this.callPlaced = false;';        
        pvc_log_arr.act_step = 'before';
        pvc_log_arr.topic_step = 'after true';
        pvc_log_arr.title = 'is getUserMediaError';
        log_video_chat_process(pvc_log_arr);          
        
        alert('未取得鏡頭或麥克風裝置權限');
        this.callPlaced = false;
        
        pvc_log_arr.act_step = 'after';
        pvc_log_arr.step = 'end';
        pvc_log_arr.title = 'after this.callPlaced = false;return';
        log_video_chat_process(pvc_log_arr);  
        
        return;
      }
      
         pvc_log_arr.act = 'const iceserver = JSON.parse(this.ice_server_json.trim());';
        pvc_log_arr.act_step   ='before';
        pvc_log_arr.topic_step = 'after false';
        pvc_log_arr.title = 'no getUserMediaError';
        pvc_log_arr.data = {iceserver:JSON.parse(this.ice_server_json.trim())};
        log_video_chat_process(pvc_log_arr);
        
      //console.log("iceserver_json: " + this.ice_server_json);
      const iceserver = JSON.parse(this.ice_server_json.trim());
      //console.log("iceserver: " + iceserver);
      
      pvc_log_arr.act_step = 'after';
      pvc_log_arr.topic = pvc_log_arr.topic_step = '';
      pvc_log_arr.data = null;
      log_video_chat_process(pvc_log_arr);

      pvc_log_arr.act = 'this.videoCallParams.peer1 = new Peer({';
      pvc_log_arr.act_step = 'before';
      log_video_chat_process(pvc_log_arr);
      
      this.videoCallParams.peer1 = new Peer({
        initiator: true,
        trickle: false,
        stream: this.videoCallParams.stream,
        config: {
          iceServers: [iceserver[0],iceserver[1]],
        },
      });
      
       pvc_log_arr.act_step = 'after';
       pvc_log_arr.data = {this_videoCallParams_peer1:this.videoCallParams.peer1};
      log_video_chat_process(pvc_log_arr);

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
          .then(() => {
            var log_arr = {
                from_file:'VideoChat.vue'
                ,title:'then in this.videoCallParams.peer1.on("signal", (data) =>@async placeVideoCall(id, name)@methods@export default@VideoChat.vue'
                ,method:'then@this.videoCallParams.peer1.on("signal", (data) =>@async placeVideoCall(id, name)@methods@export default'
                ,step:'within'
            };
            log_video_chat_process(log_arr);                           
              
          })
          .catch((error) => {
            var log_arr = {
                from_file:'VideoChat.vue'
                ,title:'catch in this.videoCallParams.peer1.on("signal", (data) =>@async placeVideoCall(id, name)@methods@export default@VideoChat.vue'
                ,method:'catch@this.videoCallParams.peer1.on("signal", (data) =>@async placeVideoCall(id, name)@methods@export default'
                ,step:'within'
                ,data:{error:error}
            };
            log_video_chat_process(log_arr);                
            console.log('signal axios error:' + error);
            $("#error_message").text('signal axios error:' + error);
          });
      });

      this.videoCallParams.peer1.on("stream", (stream) => {
        var log_arr = {
            from_file:'VideoChat.vue'
            ,title:'this.videoCallParams.peer1.on("stream", (stream) =>@async placeVideoCall(id, name)@methods@export default@VideoChat.vue'
            ,method:'this.videoCallParams.peer1.on("stream", (stream) =>@async placeVideoCall(id, name)@methods@export default'
            ,step:'start'
            ,data:{stream:stream}
        };
        log_video_chat_process(log_arr);         
        
        console.log("call streaming");
        
        log_arr.title = '';
        log_arr.topic = 'if (this.$refs.partnerVideo)';
        log_arr.topic_step = 'before';
        log_arr.data = {this_refs_partnerVideo:this.$refs.partnerVideo};
        log_video_chat_process(log_arr);         
        
        if (this.$refs.partnerVideo) {
          log_arr.title = 'has this.$refs.partnerVideo';
          log_arr.topic_step='after true';
          log_video_chat_process(log_arr); 
          this.$refs.partnerVideo.srcObject = stream;
        }
        
        log_arr.topic_step='after';
        log_arr.step='end';
        log_video_chat_process(log_arr); 
        
      });

      this.videoCallParams.peer1.on("connect", () => {
        var log_arr = {url:location.href
                ,from_file:'VideoChat.vue'
                ,title:'peer1 connected in this.videoCallParams.peer1.on("connect", () =>@async placeVideoCall(id, name)@methods@export default@VideoChat.vue'
                ,data:{user_permission:this.user_permission}
                ,method:'this.videoCallParams.peer1.on("connect", () =>@async placeVideoCall(id, name)@methods@export default'
                ,step:'start'
                ,topic:'if(this.user_permission == \'admin\')'
                ,topic_step:'before'
             };
        log_video_chat_process(log_arr);
        console.log("peer1 connected");
        if(this.user_permission == 'admin')
        {
            log_arr.title='after peer1 connected and this.user_permission == \'admin\' before ajax in this.videoCallParams.peer1.on("connect", () =>';
            log_arr.topic_step = 'after true';
            log_video_chat_process(log_arr);
          
          $.ajax({
            type:'post',
            url:'/admin/users/video_chat_verify_upload_init',
            data:{
              _token:this.csrf,
              verify_user_id:id
            },
            success:function(data){
                log_arr.title='after_success_ajax_this.videoCallParams.peer1.on("connect", () =>_in_VideoChat.vue';
                log_arr.ajax_url = '/admin/users/video_chat_verify_upload_init';
                log_arr.ajax_step='success_start';
                log_arr.ajax_sdata=this.data;
                log_arr.ajax_rdata=data;
                log_video_chat_process(log_arr);            
              window.sessionStorage.setItem('verify_record_id', data.record_id);
            }
            ,error:function(xhr) {
                log_arr.title='after_error_ajax_this.videoCallParams.peer1.on("connect", () =>_in_VideoChat.vue';
                log_arr.ajax_error = xhr;
                log_arr.ajax_step='error';
                log_video_chat_process(log_arr); 
            }
          });
          
          log_arr.title='before this.startRecording() in this.videoCallParams.peer1.on("connect", () =>_in_VideoChat.vue';
            log_arr.act = 'this.startRecording();';
            log_arr.act_step = 'before';
            log_arr.ajax_error 
            =log_arr.ajax_url
            =log_arr.ajax_rdata
            =log_arr.ajax_step
            =log_arr.ajax_sdata
            = null;
            log_video_chat_process(log_arr);
          
          this.startRecording();
          log_arr.act_step = 'after';
          log_arr.title='after this.startRecording() in this.videoCallParams.peer1.on("connect", () =>_in_VideoChat.vue';
            log_video_chat_process(log_arr);
        }
        this.videoCallParams.connecting_peer = this.videoCallParams.peer1;
         $('#connecting_msg_block').hide();

        log_arr.step = 'end';
        log_arr.act = log_arr.act_step = log_arr.topic = log_arr.topic_step = '';
        log_video_chat_process(log_arr);
      });

      this.videoCallParams.peer1.on("data", (data) => {
        var log_arr = {
            from_file:'VideoChat.vue'
            ,title:'this.videoCallParams.peer1.on("data", (data) =>@async placeVideoCall(id, name)@methods@export default@VideoChat.vue'
            ,method:'this.videoCallParams.peer1.on("data", (data) =>@async placeVideoCall(id, name)@methods@export default'
            ,step:'start'
            ,data:data
            ,act:'this.receive_data(data);'
            ,act_step:'before'
        };
        log_video_chat_process(log_arr);           
        
        console.log('peer1 receive data:');
        this.receive_data(data);
        
        log_arr.act_step = 'after';
        log_arr.step = 'end';
        log_video_chat_process(log_arr);
      });

      this.videoCallParams.peer1.on("error", (err) => {
        var log_arr = {url:location.href
                ,from_file:'VideoChat.vue'
                ,title:'peer1 error in this.videoCallParams.peer1.on("error", (err) =>@async placeVideoCall(id, name)@methods@export default@VideoChat.vue'
                ,data:{err:err}
                ,method:'this.videoCallParams.peer1.on("error", (err) =>@async placeVideoCall(id, name)@methods@export default@VideoChat.vue'
                ,step:'within'
             };
        log_video_chat_process(log_arr);
        console.log('peer1 error');
        console.log(err);
        $("#error_message").text('peer1 error : ' + err);
      
        
        this.isPeerError = true;
        if(this.isNormalStop!=true) {
            if(err.toString().indexOf('OperationError')>=0 && err.toString().indexOf('Transport')>=0 && err.toString().indexOf('channel')>=0 && err.toString().indexOf('closed')>=0) {
                $('.mask_bg').hide();
                $('#break_by_partner_before_connect_msg_block').show();
                return;
            }
            $('.mask_bg').hide();
            $('#video_error_msg_block').show().find('.loading_text').html('錯誤！！！<br><br>連線錯誤：'+err.toString()+((this.mediaRecorder!=null && this.isUploading['partner'])?'<br>殘存檔案上傳中<br><br>請勿重新整理<br>或離開本頁面':''));      
        
            if(this.mediaRecorder==null || !this.isUploading['partner']) {
              setTimeout(() => {
                this.callPlaced = false;
                
                log_arr.step = 'end';
                log_arr.act = 'location.reload();';
                log_arr.act_step = 'before';
                log_video_chat_process(log_arr);
                location.reload();
              }, 3000);            
            }
        }
        
        
      
      });

      this.videoCallParams.peer1.on("close", () => {
        var log_arr = {
            from_file:'VideoChat.vue'
            ,title:'this.videoCallParams.peer1.on("close", () =>@async placeVideoCall(id, name)@methods@export default@VideoChat.vue'
            ,method:'this.videoCallParams.peer1.on("close", () =>@async placeVideoCall(id, name)@methods@export default'
            ,step:'within'
        };
        log_video_chat_process(log_arr);          
        console.log("call closed caller");
      });

      this.videoCallParams.channel.listen("StartVideoChat", ({ data }) => {
        var log_arr = {
            from_file:'VideoChat.vue'
            ,title:'this.videoCallParams.channel.listen("StartVideoChat", ({ data }) =>@async placeVideoCall(id, name)@methods@export default@VideoChat.vue'
            ,method:'this.videoCallParams.channel.listen("StartVideoChat", ({ data }) =>@async placeVideoCall(id, name)@methods@export default'
            ,step:'start'
            ,data:data
            ,topic:'if (data.type === "callAccepted") {'
            ,topic_step:'before'
        };
        log_video_chat_process(log_arr);          
        
        if (data.type === "callAccepted") {
          
          log_arr.topic_step = 'after true';
          log_arr.ajax_url = '/video/receive-accept-call-signal-data';
          log_arr.ajax_step = 'before';
          log_arr.ajax_sdata = {signal_data_id:data.signal};         
          log_video_chat_process(log_arr);
          
          let signal_data = '';
          $.ajax({
            async:false,
            type:'get',
            url:'/video/receive-accept-call-signal-data',
            data:{
              signal_data_id:data.signal
            },
            success:function(s_data){
              log_arr.ajax_rdata = s_data;
              log_arr.ajax_step = 'success';
              log_arr.data = {signal_data:s_data};
              log_video_chat_process(log_arr);
              
              signal_data = s_data;
            },
            error:function(xhr) {
              log_arr.ajax_error = xhr;
              log_arr.ajax_step = 'error';
              log_video_chat_process(log_arr); 
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
            this.videoCallParams.dialingTo = null;
            const updatedSignal = {
              ...signal_data,
              sdp: `${signal_data.sdp}\n`,
            };
            this.videoCallParams.peer1.signal(updatedSignal);
          }
        }
      
        log_arr.step = 'end';
        log_arr.topic_step='after'
        log_video_chat_process(log_arr);          
      });
      if(this.user_permission == 'admin')
      {
        if (!this.mutedVideo) this.toggleMuteVideo();
      }
    },

    async acceptCall() {
        
        var ac_log_arr = {
            from_file:'VideoChat.vue'
            ,title:'start async acceptCall()@methods@export default@VideoChat.vue'
            ,method:'async acceptCall()@methods@export default'
            ,step:'start'
            ,act:'await this.checkDevices();'
            ,act_step:'before'
        };
        log_video_chat_process(ac_log_arr);        
        
      await this.checkDevices();
      
        ac_log_arr.act_step = 'after';
        ac_log_arr.step = 'ing';
        ac_log_arr.topic = 'if(!this.deviceReady)';
        ac_log_arr.topic_step = 'before';
        ac_log_arr.data = {this_deviceReady:this.deviceReady};
        log_video_chat_process(ac_log_arr);       
      
      //console.log('deviceReady:' + this.deviceReady);
      if(!this.deviceReady)
      {
        alert('未搜尋到鏡頭或麥克風裝置');
        
        ac_log_arr.act_step = ac_log_arr.act  ='';
        ac_log_arr.step = 'end';
        ac_log_arr.topic_step = 'after true';
        ac_log_arr.title = 'not deviceReady';
        log_video_chat_process(ac_log_arr);        
        
        return;
      }
      
        ac_log_arr.act_step = ac_log_arr.act  ='';
        ac_log_arr.topic_step = 'after false';
        ac_log_arr.title = 'is deviceReady';
        log_video_chat_process(ac_log_arr);      
      
      this.callPlaced = true;
      this.videoCallParams.callAccepted = true;
      
        ac_log_arr.act  ='await this.getMediaPermission();';
        ac_log_arr.act_step = 'before'; 
        ac_log_arr.topic = ac_log_arr.topic_step = '';
        ac_log_arr.title = '';
        log_video_chat_process(ac_log_arr);       
      
      await this.getMediaPermission();
      
        ac_log_arr.topic = 'if(this.getUserMediaError)';
        ac_log_arr.topic_step = 'before';
        ac_log_arr.act_step = 'after';
        log_video_chat_process(ac_log_arr);       
      
      if(this.getUserMediaError)
      {
          
        ac_log_arr.act  ='alert;this.callPlaced = false;';        
        ac_log_arr.act_step = 'before';
        ac_log_arr.topic_step = 'after true';
        ac_log_arr.title = 'is getUserMediaError';
        log_video_chat_process(ac_log_arr);             
          
        alert('未取得鏡頭或麥克風裝置權限');
        this.callPlaced = false;
        
        ac_log_arr.act_step = 'after';
        ac_log_arr.step = 'end';
        ac_log_arr.title = 'after this.callPlaced = false;return';
        log_video_chat_process(ac_log_arr);          
        
        return;
      }
      
         ac_log_arr.act = 'const iceserver = JSON.parse(this.ice_server_json.trim());';
        ac_log_arr.act_step   ='before';
        ac_log_arr.topic_step = 'after false';
        ac_log_arr.title = 'no getUserMediaError';
        ac_log_arr.data = {iceserver:JSON.parse(this.ice_server_json.trim())};
        log_video_chat_process(ac_log_arr);      
      
      //console.log("iceserver_json: " + this.ice_server_json);
      const iceserver = JSON.parse(this.ice_server_json.trim());
      //console.log("iceserver: " + iceserver);
      
      ac_log_arr.act_step = 'after';
      ac_log_arr.topic = ac_log_arr.topic_step = '';
      ac_log_arr.data = null;
      log_video_chat_process(ac_log_arr);
      
      ac_log_arr.act = 'this.videoCallParams.peer2 = new Peer({';
      ac_log_arr.act_step = 'before';
      log_video_chat_process(ac_log_arr);      
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
      
       ac_log_arr.act_step = 'after';
       ac_log_arr.data = {this_videoCallParams_peer2:this.videoCallParams.peer2};
      log_video_chat_process(ac_log_arr);      

      this.videoCallParams.receivingCall = false;
      this.videoCallParams.peer2.on("signal", (data) => {
        //console.log(data);
        axios
          .post("/video/accept-call", {
            //signal: JSON.stringify(data),
            signal: data,
            to: this.videoCallParams.caller,
          })
          .then(() => {
            var log_arr = {
                from_file:'VideoChat.vue'
                ,title:'then in this.videoCallParams.peer2.on("signal", (data) =>@async acceptCall()@methods@export default@VideoChat.vue'
                ,method:'then@this.videoCallParams.peer2.on("signal", (data) =>@async acceptCall()@methods@export default'
                ,step:'within'
            };
            log_video_chat_process(log_arr);      
          })
          .catch((error) => {
            var log_arr = {
                from_file:'VideoChat.vue'
                ,title:'catch in this.videoCallParams.peer2.on("signal", (data) =>@async placeVideoCall(id, name)@methods@export default@VideoChat.vue'
                ,method:'catch@this.videoCallParams.peer2.on("signal", (data) =>@async placeVideoCall(id, name)@methods@export default'
                ,step:'within'
                ,data:{error:error}
            };
            log_video_chat_process(log_arr);    
            console.log('signal axios error:' + error);
            $("#error_message").text('signal axios error:' + error);
          });
      });

      this.videoCallParams.peer2.on("stream", (stream) => {
        var log_arr = {
            from_file:'VideoChat.vue'
            ,title:'this.videoCallParams.peer2.on("stream", (stream) =>@async placeVideoCall(id, name)@methods@export default@VideoChat.vue'
            ,method:'this.videoCallParams.peer2.on("stream", (stream) =>@async placeVideoCall(id, name)@methods@export default'
            ,step:'start'
            ,data:{stream:stream}
            ,act:'this.videoCallParams.callAccepted = true;this.$refs.partnerVideo.srcObject = stream;'
            ,act_step:'before'
        };
        log_video_chat_process(log_arr);   
        
        this.videoCallParams.callAccepted = true;
        this.$refs.partnerVideo.srcObject = stream;
        
        log_arr.act_step = 'after';
        log_arr.step = 'end';
        log_arr.data = {this_videoCallParams_callAccepted:this.videoCallParams.callAccepted
                        ,this_$refs_partnerVideo_srcObject:this.$refs.partnerVideo.srcObject
                        }
        log_video_chat_process(log_arr);   
        
      });

      this.videoCallParams.peer2.on("connect", () => {
        var log_arr = {
            from_file:'VideoChat.vue'
            ,title:'peer2 connected in this.videoCallParams.peer2.on("connect", () =>@async acceptCall()@methods@export default@VideoChat.vue'
            ,data:{user_permission:this.user_permission}
            ,method:'this.videoCallParams.peer2.on("connect", () =>@async acceptCall()@methods@export default'
            ,step:'start'
            ,topic:'if(this.user_permission == \'admin\')'
            ,topic_step:'before'
            ,data:{user_permission:this.user_permission}
            ,act:'this.videoCallParams.callAccepted = true;'
            ,act_step:'before'
            ,title:"peer2 connected"
        };
        log_video_chat_process(log_arr);
        
        console.log("peer2 connected");
        this.videoCallParams.callAccepted = true;
        
        if(this.user_permission == 'admin')
        {
          log_arr.act_step = 'after';
          log_arr.step = 'ing';
          log_arr.topic_step = 'after true';
          log_arr.ajax_url = '/admin/users/video_chat_verify_upload_init'
          log_arr.ajax_step = 'before'          
          log_arr.ajax_sdata = {
              _token:this.csrf,
              verify_user_id:this.videoCallParams.caller
            };
          log_arr.title = 'after peer2 connected and this.user_permission == \'admin\' before ajax in this.videoCallParams.peer2.on("connect", () =>';
          log_video_chat_process(log_arr);

          $.ajax({
            type:'post',
            url:'/admin/users/video_chat_verify_upload_init',
            data:{
              _token:this.csrf,
              verify_user_id:this.videoCallParams.caller
            },
            success:function(data){
                log_arr.act = 'window.sessionStorage.setItem(\'verify_record_id\', data.record_id);';
                log_arr.act_step = 'before';
                log_arr.title = 'after_success_ajax_this.videoCallParams.peer2.on("connect", () =>_in_VideoChat.vue';
                log_arr.ajax_step='success_start';
                log_arr.ajax_url = this.url;
                log_arr.ajax_sdata=this.data;
                log_arr.ajax_rdata=data;                
                log_arr.data = { data_record_id: data.record_id};
                log_video_chat_process(log_arr); 
              
              window.sessionStorage.setItem('verify_record_id', data.record_id);
            
                log_arr.act_step = 'after';
                log_arr.ajax_step='success_end';
                log_arr.data = { data_record_id: data.record_id,window_sessionStorage_setItem__verify_record_id:window.sessionStorage.getItem('verify_record_id')};
                log_video_chat_process(log_arr); 
            }
            , error:function(xhr) {
                log_arr.title='after_error_ajax_this.videoCallParams.peer2.on("connect", () =>_in_VideoChat.vue';
                log_arr.ajax_error = xhr;
                log_arr.ajax_step='error';
                log_arr.ajax_url = this.url;
                log_video_chat_process(log_arr);                        
            }
          });
          
          log_arr.title='before this.startRecording() in this.videoCallParams.peer2.on("connect", () =>_in_VideoChat.vue';
            log_arr.act = 'this.startRecording();';
            log_arr.act_step = 'before';
            log_arr.ajax_error 
            =log_arr.ajax_url
            =log_arr.ajax_rdata
            =log_arr.ajax_step
            =log_arr.ajax_sdata
            = null;
            log_video_chat_process(log_arr);          
          
          this.startRecording();
          
          log_arr.act_step = 'after';
          log_arr.title='after this.startRecording() in this.videoCallParams.peer2.on("connect", () =>_in_VideoChat.vue';
            log_video_chat_process(log_arr);          
        }
        this.videoCallParams.connecting_peer = this.videoCallParams.peer2;

        $('#connecting_msg_block').hide();
        
        log_arr.step = 'end';
        log_arr.act = log_arr.act_step = log_arr.topic = log_arr.topic_step = '';
        log_video_chat_process(log_arr);      
      });

      this.videoCallParams.peer2.on("data", (data) => {
        var log_arr = {
            from_file:'VideoChat.vue'
            ,title:'this.videoCallParams.peer2.on("data", (data) =>@async acceptCall()@methods@export default@VideoChat.vue'
            ,method:'this.videoCallParams.peer2.on("data", (data) =>@async acceptCall()@methods@export default'
            ,step:'start'
            ,data:data
            ,act:'this.receive_data(data);'
            ,act_step:'before'
        };
        log_video_chat_process(log_arr);           
        
        console.log('peer2 receive data:');
        this.receive_data(data);
        
        log_arr.act_step = 'after';
        log_arr.step = 'end';
        log_video_chat_process(log_arr);        
      });

      this.videoCallParams.peer2.on("error", (err) => {        
        var log_arr = {
                from_file:'VideoChat.vue'
                ,title:'peer2 error in this.videoCallParams.peer2.on("error", (err) =>@async acceptCall()@methods@export default@VideoChat.vue'
                ,data:{err:err,err_toString:err.toString()}
                ,method:'this.videoCallParams.peer2.on("error", (err) =>@async acceptCall()@methods@export default@VideoChat.vue'
                ,step:'within'
             };
        log_video_chat_process(log_arr);
        console.log('peer2 error');
        console.log(err);
        $("#error_message").text('peer2 error : ' + err);
        
        this.isPeerError = true;
        if(this.isNormalStop!=true) {
            if(err.toString().indexOf('OperationError')>=0 && err.toString().indexOf('Transport')>=0 && err.toString().indexOf('channel')>=0 && err.toString().indexOf('closed')>=0) {
                $('.mask_bg').hide();
                $('#break_by_partner_before_connect_msg_block').show();
                return;
            }
            $('.mask_bg').hide();
            $('#video_error_msg_block').show().find('.loading_text').html('錯誤！！！<br><br>連線錯誤：'+err.toString()+((this.mediaRecorder!=null && this.isUploading['partner'])?'<br>殘存檔案上傳中<br><br>請勿重新整理<br>或離開本頁面':''));
        
            if(this.mediaRecorder==null) {
                setTimeout(() => {
                    this.callPlaced = false;
                    
                      if(this.user_permission == 'admin')
                      {
                        window.sessionStorage.setItem('endcall_reload',true);
                      }                    

                    log_arr.step = 'end';
                    log_arr.act = 'location.reload();';
                    log_arr.act_step = 'before';
                    log_video_chat_process(log_arr);
                    location.reload();
                }, 3000);            
            }
        }      
      });

      this.videoCallParams.peer2.on("close", () => {
        
        var log_arr = {
            from_file:'VideoChat.vue'
            ,title:'this.videoCallParams.peer2.on("close", () =>@async acceptCall()@methods@export default@VideoChat.vue'
            ,method:'this.videoCallParams.peer2.on("close", () =>@async acceptCall()@methods@export default'
            ,step:'within'
        };
        log_video_chat_process(log_arr);          
        
        console.log("call closed accepter");
      });

        ac_log_arr.act = 'this.videoCallParams.peer2.signal(this.videoCallParams.callerSignal);';
        ac_log_arr.act_step = 'before';
        ac_log_arr.topic = '';
        ac_log_arr.topic_step = '';
        ac_log_arr.act = 'this.videoCallParams.peer2.signal(this.videoCallParams.callerSignal);';
        ac_log_arr.act_step = 'before';
        ac_log_arr.data = {this_videoCallParams_callerSignal:this.videoCallParams.callerSignal};
        log_video_chat_process(ac_log_arr);

      this.videoCallParams.peer2.signal(this.videoCallParams.callerSignal);

        ac_log_arr.act_step = 'after';
        ac_log_arr.topic = "if(this.user_permission == 'admin')";
        ac_log_arr.topic_step = 'before';
        ac_log_arr.data = {this_videoCallParams_callerSignal:this.videoCallParams.callerSignal};
        log_video_chat_process(ac_log_arr);

      if(this.user_permission == 'admin')
      {
        if (!this.mutedVideo) this.toggleMuteVideo();
      }
      
        ac_log_arr.act = ac_log_arr.act_step = '';
        ac_log_arr.topic = ac_log_arr.topic_step = '';
        ac_log_arr.data = null;
        ac_log_arr.step = 'end';
        log_video_chat_process(ac_log_arr);      
    },

    toggleCameraArea() {
        var tca_log_arr = {
            from_file:'VideoChat.vue'
            ,title:'start toggleCameraArea()@methods@export default@VideoChat.vue'
            ,method:'toggleCameraArea()@methods@export default'
            ,step:'start'
            ,topic:'if (this.videoCallParams.callAccepted) {'
            ,topic_step:'before'
            ,data:{this_videoCallParams_callAccepted:this.videoCallParams.callAccepted,this_isFocusMyself:this.isFocusMyself}
        };
        log_video_chat_process(tca_log_arr);  
        
      if (this.videoCallParams.callAccepted) {
          tca_log_arr.topic_step = 'after true';
          tca_log_arr.step = 'ing';
          tca_log_arr.act = 'this.isFocusMyself = !this.isFocusMyself;';
          tca_log_arr.act_step = 'before';
          log_video_chat_process(tca_log_arr);
          
        this.isFocusMyself = !this.isFocusMyself;
        
          tca_log_arr.act_step = 'after';
          tca_log_arr.data = {this_isFocusMyself:this.isFocusMyself};
          log_video_chat_process(tca_log_arr);
      }
      
      tca_log_arr.topic_step = 'after';
      tca_log_arr.step = 'end';
      log_video_chat_process(tca_log_arr);  
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
        var dc_log_arr = {
            from_file:'VideoChat.vue'
            ,title:'start declineCall()@methods@export default@VideoChat.vue'
            ,method:'declineCall()@methods@export default'
            ,step:'start'
            ,act:'this.videoCallParams.receivingCall = false;'
            ,act_step:'before'
            ,data:{this_videoCallParams_receivingCall:this.videoCallParams.receivingCall}
        };
        log_video_chat_process(dc_log_arr);      
    
        this.videoCallParams.receivingCall = false;

        axios
          .post("/video/decline-call", {
            to: this.videoCallParams.caller,
          })
          .then(() => {
            var log_arr = {
                from_file:'VideoChat.vue'
                ,title:'then in axios@declineCall@methods@export default@VideoChat.vue'
                ,method:'then@axios@declineCall@methods@export default'
                ,step:'within'
            };
            log_video_chat_process(log_arr);      
          })
          .catch((error) => {
            var log_arr = {
                from_file:'VideoChat.vue'
                ,title:'catch in axios@declineCall@methods@export default@VideoChat.vue'
                ,method:'catch@axios@declineCall@methods@export default'
                ,step:'within'
                ,data:{error:error}
            };
            log_video_chat_process(log_arr);    

            $("#error_message").text('decline axios error:' + error);
          });      
      
      //if(this.videoCallParams.caller!=null && this.videoCallParams.caller!=undefined)
      //this.videoCallParams.connecting_peer.send('declineCall_'.this.videoCallParams.caller);
      //location.reload();
      dc_log_arr.act_step = 'after';
      dc_log_arr.step = 'end';
      log_video_chat_process(dc_log_arr);  
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
        var rd_log_arr = {
            from_file:'VideoChat.vue'
            ,title:'start receive_data(data)@methods@export default@VideoChat.vue'
            ,method:'receive_data(data)@methods@export default'
            ,step:'start'
            ,topic:'if(new TextDecoder(\'utf-8\').decode(data) === \'mutedVideo_false\')'
            ,topic_step:'before'
            ,data:{data:data,TextDecoder_utf_8_decode_data:new TextDecoder('utf-8').decode(data)}
        };
        log_video_chat_process(rd_log_arr);      
    
      if(new TextDecoder('utf-8').decode(data) === 'mutedVideo_false')
      {
          rd_log_arr.topic_step = 'after true';
          rd_log_arr.step = 'ing';
          rd_log_arr.act = '$(\'#partner_video_screen\').show();$(\'#none_partner_video\').hide();';
          rd_log_arr.act_step = 'before';
          log_video_chat_process(rd_log_arr);      
      
        console.log('Log_mutedVideo_false');
        $('#partner_video_screen').show();
        $('#none_partner_video').hide();
        
         rd_log_arr.act_step = 'after';
          log_video_chat_process(rd_log_arr);
      }
      else if(new TextDecoder('utf-8').decode(data) === 'mutedVideo_true')
      {
        rd_log_arr.topic = "if(new TextDecoder('utf-8').decode(data) === 'mutedVideo_true')";
        rd_log_arr.topic_step = 'after true';
          rd_log_arr.act = "$('#partner_video_screen').hide();$('#none_partner_video').show();";
          rd_log_arr.act_step = 'before';
          log_video_chat_process(rd_log_arr); 
      
        console.log('Log_mutedVideo_true');
        $('#partner_video_screen').hide();
        $('#none_partner_video').show();
        
        rd_log_arr.act_step = 'after';
          log_video_chat_process(rd_log_arr);
      }
      else if(new TextDecoder('utf-8').decode(data) === 'end_call')
      {
        rd_log_arr.topic = "if(new TextDecoder('utf-8').decode(data) === 'end_call')";
        rd_log_arr.topic_step = 'after true';
          rd_log_arr.act = "if(this.isNormalStop!=true) this.endCall();";
          rd_log_arr.act_step = 'before';
          rd_log_arr.data = {this_isNormalStop:this.isNormalStop};
          log_video_chat_process(rd_log_arr);
      
        console.log('user_end_call');
        if(this.isNormalStop!=true) this.endCall();
        
        rd_log_arr.act_step = 'after';
          log_video_chat_process(rd_log_arr);
        
      }
      
      rd_log_arr.topic_step = 'after';
      rd_log_arr.step = 'end';
      log_video_chat_process(rd_log_arr); 
    },

    stopStreamedVideo(videoElem) {
      var ssv_log_arr = {
            from_file:'VideoChat.vue'
            ,title:'start stopStreamedVideo(videoElem)@methods@export default@VideoChat.vue'
            ,method:'stopStreamedVideo(videoElem)@methods@export default'
            ,step:'start'
            ,act:'const tracks = stream.getTracks();tracks.forEach((track) => {track.stop();});'
            ,act_step:'before'
            ,data:{videoElem:videoElem,stream:videoElem.srcObject,tracks:videoElem.srcObject.getTracks()}
        };
      log_video_chat_process(ssv_log_arr);
      
      const stream = videoElem.srcObject;
      const tracks = stream.getTracks();
      this.isNormalStop = true;
      tracks.forEach((track) => {
        track.stop();
      });
      videoElem.srcObject = null;
      
      ssv_log_arr.act_step = 'after';
      ssv_log_arr.step = 'end';
      ssv_log_arr.data = {videoElem:videoElem,stream:videoElem.srcObject};
      log_video_chat_process(ssv_log_arr);  
    },

    endCall() {
      var ec_log_arr = {
            from_file:'VideoChat.vue'
            ,title:'start endCall()@methods@export default@VideoChat.vue'
            ,method:'endCall()@methods@export default'
            ,step:'start'
            ,data:{}
        };
        log_video_chat_process(ec_log_arr); 
      if(this.isPeerError!=true && this.isNormalStop!=true) {
          $('.mask_bg').hide();
          $('.video_chat_mask_bg').show();
      }
      ec_log_arr.act = "$('.video_chat_mask_bg').show();";
      ec_log_arr.act_step = 'after';
      ec_log_arr.step = 'ing';
      log_video_chat_process(ec_log_arr);
          
      
      // if video or audio is muted, enable it so that the stopStreamedVideo method will work
      if (this.mutedVideo) this.toggleMuteVideo();
      ec_log_arr.act = "if (this.mutedVideo) this.toggleMuteVideo();";
      ec_log_arr.act_step = 'after';
      ec_log_arr.data = {this_mutedVideo:this.mutedVideo};
      log_video_chat_process(ec_log_arr);
      
      
      if (this.mutedAudio) this.toggleMuteAudio();
      ec_log_arr.act = "if (this.mutedAudio) this.toggleMuteAudio();";
      ec_log_arr.act_step = 'after';
      ec_log_arr.data = {this_mutedAudio:this.mutedAudio};
      log_video_chat_process(ec_log_arr);
      
      this.stopStreamedVideo(this.$refs.userVideo);
      ec_log_arr.act = "this.stopStreamedVideo(this.$refs.userVideo);";
      ec_log_arr.act_step = 'after';
      ec_log_arr.topic = 'if (this.authuserid === this.videoCallParams.caller)';
      ec_log_arr.topic_step = 'before';
      ec_log_arr.data = {this_authuserid:this.authuserid,this_videoCallParams_caller:this.videoCallParams.caller};
      log_video_chat_process(ec_log_arr);
      
      if (this.authuserid === this.videoCallParams.caller)
      {
        ec_log_arr.act = "this.videoCallParams.peer1.destroy();";
        ec_log_arr.act_step = 'before';
        ec_log_arr.topic_step = 'after true';
        log_video_chat_process(ec_log_arr);
      
        try
        {
          this.videoCallParams.peer1.destroy();
            ec_log_arr.act_step = 'after success';
            log_video_chat_process(ec_log_arr);
        }
        catch(e)
        {
            ec_log_arr.act_step = 'after catch error';
            ec_log_arr.data = {e:e,e_toString:e.toString()};
            log_video_chat_process(ec_log_arr);            
            
            console.log('peer has destroy');

            if(this.videoCallParams.dialingTo!=null) {
                axios
                  .post("/video/abort-dial-call", {
                    to: this.videoCallParams.dialingTo,
                  })
                  .then(() => {
                    var log_arr = {
                        from_file:'VideoChat.vue'
                        ,title:'then in abort-dial-call axios@endCall@methods@export default@VideoChat.vue'
                        ,method:'then@abort-dial-call axios@endCall@methods@export default'
                        ,step:'within'
                    };
                    log_video_chat_process(log_arr);      
                  })
                  .catch((error) => {
                    var log_arr = {
                        from_file:'VideoChat.vue'
                        ,title:'catch in abort-dial-call axios@endCall@methods@export default@VideoChat.vue'
                        ,method:'catch@abort-dial-call axios@endCall@methods@export default'
                        ,step:'within'
                        ,data:{error:error}
                    };
                    log_video_chat_process(log_arr);    

                    $("#error_message").text('abort-dial-call axios error:' + error);
                  });      
            }
        }
      } 
      else
      {
         ec_log_arr.topic_step = 'after false';
         ec_log_arr.act = "this.videoCallParams.peer2.destroy();";
        ec_log_arr.act_step = 'before';
        log_video_chat_process(ec_log_arr);
      
        try
        {
          this.videoCallParams.peer2.destroy();
          ec_log_arr.act_step = 'after success';
            log_video_chat_process(ec_log_arr);
        }
        catch(e)
        {
            ec_log_arr.act_step = 'after catch error';
            ec_log_arr.data = {e:e,e_toString:e.toString()};
            log_video_chat_process(ec_log_arr);
          console.log('peer has destroy');

            if(this.videoCallParams.dialingTo!=null) {
                axios
                  .post("/video/abort-dial-call", {
                    to: this.videoCallParams.dialingTo,
                  })
                  .then(() => {
                    var log_arr = {
                        from_file:'VideoChat.vue'
                        ,title:'then in abort-dial-call axios@endCall@methods@export default@VideoChat.vue'
                        ,method:'then@abort-dial-call axios@endCall@methods@export default'
                        ,step:'within'
                    };
                    log_video_chat_process(log_arr);      
                  })
                  .catch((error) => {
                    var log_arr = {
                        from_file:'VideoChat.vue'
                        ,title:'catch in abort-dial-call axios@endCall@methods@export default@VideoChat.vue'
                        ,method:'catch@abort-dial-call axios@endCall@methods@export default'
                        ,step:'within'
                        ,data:{error:error}
                    };
                    log_video_chat_process(log_arr);    
                    
                    $("#error_message").text('abort-dial-call axios error:' + error);
                  });      
            }
          
        }
      }
      
      ec_log_arr.topic_step = 'after';
      ec_log_arr.act = 'this.videoCallParams.connecting_peer = null;this.videoCallParams.channel.pusher.channels.channels["presence-presence-video-channel"].disconnect();';
      ec_log_arr.act_step = 'before';
      if(this.videoCallParams.connecting_peer!=null) ec_log_arr.data = {this_videoCallParams_connecting_peer:this.videoCallParams.connecting_peer.toString(),this_videoCallParams_channel_pusher_channels_channels:this.videoCallParams.channel.pusher.channels.channels.toString()};
      log_video_chat_process(ec_log_arr);  
      
      this.videoCallParams.connecting_peer = null;
      this.videoCallParams.channel.pusher.channels.channels[
        "presence-presence-video-channel"
      ].disconnect();
      ec_log_arr.topic= "if(this.user_permission == 'admin')";
      ec_log_arr.topic_step='before';
      ec_log_arr.act_step = 'after';
      log_video_chat_process(ec_log_arr);  
      
      if(this.user_permission == 'admin')
      {
        ec_log_arr.topic_step='after success';
        ec_log_arr.act = 'this.stopRecording();';
        ec_log_arr.act_step = 'before';
        log_video_chat_process(ec_log_arr);
      
        try{this.stopRecording();
            ec_log_arr.act_step = 'after success';
            log_video_chat_process(ec_log_arr);
        }
        catch(e){
            $("#error_message").text(e);
            ec_log_arr.data = {e:e,e_toString:e.toString()};
            ec_log_arr.act_step = 'after catch error';
            log_video_chat_process(ec_log_arr);
        }
      }
      if(this.user_permission == 'admin')
      {
        window.sessionStorage.setItem('endcall_reload',true);
      }
      
        ec_log_arr.data = {this_videoCallParams_callAccepted:this.videoCallParams.callAccepted};
        ec_log_arr.act = ec_log_arr.act_step = null;
        ec_log_arr.topic = 'if(this.videoCallParams.callAccepted!=true) {';
        ec_log_arr.topic_step = 'before';
        log_video_chat_process(ec_log_arr);
      
      if(this.videoCallParams.callAccepted!=true) {
          ec_log_arr.topic_step = 'after true';
          ec_log_arr.act = 'setTimeout(() =>';
          ec_log_arr.act_step = 'before';
          log_video_chat_process(ec_log_arr);
          
          $('.mask_bg').hide();
          $('#video_error_msg_block').show().find('.loading_text').html('已中止視訊<br>請重新操作<br><br>3秒後將自動重新整理頁面');
          
          setTimeout(() => {
            this.callPlaced = false;
            
            ec_log_arr.step = 'end';
            ec_log_arr.act = 'location.reload();';
            ec_log_arr.act_step = 'before';
            log_video_chat_process(ec_log_arr);
            location.reload();
          }, 3000);
      }
      
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
    
    generateBtnUserAdvInfoUrl(userid) {
        return './advInfo/'+userid;
    },    

    //video record
    startRecording() {
      var sr_log_arr = {
            from_file:'VideoChat.vue'
            ,title:'start startRecording()@methods@export default@VideoChat.vue'
            ,method:'startRecording()@methods@export default'
            ,step:'start'
        };
        log_video_chat_process(sr_log_arr);  
        sr_log_arr.step = 'ing';
        
      this.recordedBlobs = [];
      this.recordedBlobs2 = [];
      let options = {mimeType: 'video/webm;codecs=vp9,opus'};
      try {
        if(this.$refs.partnerVideo.srcObject!=null && this.$refs.partnerVideo.srcObject!=undefined) {
            this.mediaRecorder = new MediaRecorder(this.$refs.partnerVideo.srcObject, options);
        }
        else {
            $('.mask_bg').hide();
            $('#video_error_msg_block').show().find('.loading_text').html('失敗！！！<br><br>找不到會員的視訊，請重新操作。');
        
            setTimeout(() => {
                this.callPlaced = false;
                
                if(this.user_permission == 'admin')
                {
                    window.sessionStorage.setItem('endcall_reload',true);
                }                

                sr_log_arr.step = 'end';
                sr_log_arr.act = 'location.reload();';
                sr_log_arr.act_step = 'before';
                log_video_chat_process(sr_log_arr);
                location.reload();
            }, 3000);         
        }

        if(this.$refs.userVideo.srcObject!=null && this.$refs.userVideo.srcObject!=undefined) {
            this.mediaRecorder2 = new MediaRecorder(this.$refs.userVideo.srcObject, options);
        }
        else {
            $('.mask_bg').hide();
            $('#video_error_msg_block').show().find('.loading_text').html('失敗！！！<br><br>找不到站方的視訊，請重新操作。');
        
            setTimeout(() => {
                this.callPlaced = false;

                sr_log_arr.step = 'end';
                sr_log_arr.act = 'location.reload();';
                sr_log_arr.act_step = 'before';
                log_video_chat_process(sr_log_arr);
                
                if(this.user_permission == 'admin')
                {
                    window.sessionStorage.setItem('endcall_reload',true);
                }                  
                
                location.reload();
            }, 3000);        
        }
        
        sr_log_arr.act = 'this.mediaRecorder = new MediaRecorder(this.$refs.partnerVideo.srcObject, options);this.mediaRecorder2 = new MediaRecorder(this.$refs.userVideo.srcObject, options);';
        sr_log_arr.act_step = 'after success';
        log_video_chat_process(sr_log_arr); 
         
      } catch (e) {
      
        sr_log_arr.act = 'this.mediaRecorder = new MediaRecorder(this.$refs.partnerVideo.srcObject, options);this.mediaRecorder2 = new MediaRecorder(this.$refs.userVideo.srcObject, options);';
        sr_log_arr.act_step = 'after catch error';
        sr_log_arr.data = {e:e,e_toString:e.toString()};
        log_video_chat_process(sr_log_arr); 
        
        console.error('Exception while creating MediaRecorder:', e);
        return;
      }
      console.log('Created MediaRecorder', this.mediaRecorder, 'with options', options);
      console.log('Created MediaRecorder2', this.mediaRecorder2, 'with options', options);
      this.mediaRecorder.onstop = (event) => {
        
        var mros_log_arr = {
            from_file:'VideoChat.vue'
            ,title:'start this.mediaRecorder.onstop = (event) =>@startRecording()@methods@export default@VideoChat.vue'
            ,method:'this.mediaRecorder.onstop = (event) =>@startRecording()@methods@export default'
            ,step:'start'
            ,act:"this.downloadRecording(this.recordedBlobs,'partner');"
            ,act_step:'before'
        };
        log_video_chat_process(mros_log_arr);
        
        console.log('Recorder stopped: ', event);
        console.log('Recorded Blobs: ', this.recordedBlobs);
        
        this.downloadRecording(this.recordedBlobs,'partner');
      
        mros_log_arr.act_step = 'after success';
        mros_log_arr.step = 'end';
        log_video_chat_process(mros_log_arr);
        
      };
      this.mediaRecorder2.onstop = (event) => {
      
        var mr2os_log_arr = {
            from_file:'VideoChat.vue'
            ,title:'start this.mediaRecorder.onstop = (event) =>@startRecording()@methods@export default@VideoChat.vue'
            ,method:'this.mediaRecorder.onstop = (event) =>@startRecording()@methods@export default'
            ,step:'start'
            ,act:"this.downloadRecording(this.recordedBlobs2,'user');"
            ,act_step:'before'
        };
        log_video_chat_process(mr2os_log_arr);
        
        console.log('Recorder2 stopped: ', event);
        console.log('Recorded Blobs2: ', this.recordedBlobs2);
        
        this.downloadRecording(this.recordedBlobs2,'user');
      
        mr2os_log_arr.act_step = 'after success';
        mr2os_log_arr.step = 'end';
        log_video_chat_process(mr2os_log_arr);  
            
      };
      this.mediaRecorder.ondataavailable = (event) => {
      
        var mroda_log_arr = {
            from_file:'VideoChat.vue'
            ,title:'start this.mediaRecorder.ondataavailable = (event) =>@startRecording()@methods@export default@VideoChat.vue'
            ,method:'this.mediaRecorder.ondataavailable = (event) =>@startRecording()@methods@export default'
            ,step:'start'
            ,topic:"if (event.data && event.data.size > 0) {"
            ,topic_step:'before'
            ,data:{event:event,event_data:event.data}
        };
        log_video_chat_process(mroda_log_arr);
        
        console.log('handleDataAvailable', event);
        
        if (event.data && event.data.size > 0) {
        
          mroda_log_arr.topic_step = 'after success';
          mroda_log_arr.step = 'ing';
          mroda_log_arr.act = 'this.recordedBlobs.push(event.data);';
          mroda_log_arr.act_step = 'before';
          log_video_chat_process(mroda_log_arr);
          
          this.recordedBlobs.push(event.data);
        
          mroda_log_arr.act_step = 'after success';
          log_video_chat_process(mroda_log_arr);
          
        }
        
          mroda_log_arr.step =  'end';
          mroda_log_arr.topic_step =  'after';
          log_video_chat_process(mroda_log_arr);
      }
      this.mediaRecorder2.ondataavailable = (event) => {
        var mr2oda_log_arr = {
            from_file:'VideoChat.vue'
            ,title:'start this.mediaRecorder2.ondataavailable = (event) =>@startRecording()@methods@export default@VideoChat.vue'
            ,method:'this.mediaRecorder2.ondataavailable = (event) =>@startRecording()@methods@export default'
            ,step:'start'
            ,topic:"if (event.data && event.data.size > 0) {"
            ,topic_step:'before'
            ,data:{event:event,event_data:event.data}
        };
        log_video_chat_process(mr2oda_log_arr);
        
        console.log('handleDataAvailable2', event);
        if (event.data && event.data.size > 0) {
          mr2oda_log_arr.topic_step = 'after success';
          mr2oda_log_arr.step = 'ing';
          mr2oda_log_arr.act = 'this.recordedBlobs2.push(event.data);';
          mr2oda_log_arr.act_step = 'before';
          log_video_chat_process(mr2oda_log_arr);
          
          this.recordedBlobs2.push(event.data);
        
          mr2oda_log_arr.act_step = 'after success';
          log_video_chat_process(mr2oda_log_arr);
        }
        
        mr2oda_log_arr.step =  'end';
        mr2oda_log_arr.topic_step =  'after';
        log_video_chat_process(mr2oda_log_arr);
      }
      
      sr_log_arr.act = 'this.mediaRecorder.start();';
      sr_log_arr.act_step = 'before';
      log_video_chat_process(sr_log_arr);  
      
      this.mediaRecorder.start();
      
      sr_log_arr.act_step = 'after';
      log_video_chat_process(sr_log_arr);
      
      sr_log_arr.act = 'this.mediaRecorder2.start();';
      sr_log_arr.act_step = 'before';
      log_video_chat_process(sr_log_arr);
      
      this.mediaRecorder2.start();
      
      sr_log_arr.act_step = 'after';
      sr_log_arr.step = 'end';
      log_video_chat_process(sr_log_arr);
      
      console.log('MediaRecorder started', this.mediaRecorder);
      console.log('MediaRecorder2 started', this.mediaRecorder2);
    },

    stopRecording() {
      var sr_log_arr = {
            from_file:'VideoChat.vue'
            ,title:'start stopRecording()@methods@export default@VideoChat.vue'
            ,method:'stopRecording()@methods@export default'
            ,step:'start'
            ,act:'this.mediaRecorder.stop();'
            ,act_step:'before'
        };
      log_video_chat_process(sr_log_arr);  
      sr_log_arr.step = 'ing';

      
      if(this.mediaRecorder!=null && this.mediaRecorder.state!='inactive') this.mediaRecorder.stop();
      
      sr_log_arr.act_step = 'after';
      log_video_chat_process(sr_log_arr);
      
      sr_log_arr.act = 'this.mediaRecorder2.stop();';
      sr_log_arr.act_step = 'before';
      log_video_chat_process(sr_log_arr);

      if(this.mediaRecorder2!=null && this.mediaRecorder2.state!='inactive') this.mediaRecorder2.stop();

      sr_log_arr.act_step = 'after';
      log_video_chat_process(sr_log_arr);
    },

    downloadRecording(recordedChunks,who) {
      var dlr_log_arr = {
            from_file:'VideoChat.vue'
            ,title:'start downloadRecording(recordedChunks,who)@methods@export default@VideoChat.vue'
            ,method:'downloadRecording(recordedChunks,who)@methods@export default'
            ,step:'start'
            ,data:{recordedChunks:recordedChunks,who:who,window_sessionStorage_getItem_verify_record_id:window.sessionStorage.getItem('verify_record_id'),Date_now:Date.now()}
        };
      log_video_chat_process(dlr_log_arr);  
      dlr_log_arr.step = 'ing';
      
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

      log_video_chat_process({
        type:'fetch'
        ,title:'before_fetch_video_chat_verify_upload_in_VideoChat.vue_at_downloadRecording'
        ,file_name:'VideoChat.vue'
        ,method:'downloadRecording'
        ,step:'ing'
        ,act:'fetch(\'/admin/users/video_chat_verify_upload\', {'
        ,act_step:'before'
        ,ajax_url:'/admin/users/video_chat_verify_upload'
        ,ajax_step:'before'
        ,ajax_sdata:formDataObj
        ,data:{file_name:file_name,file_size:blob.size}
      });
      this.isUploading[who] = true;
      fetch('/admin/users/video_chat_verify_upload', {
              method: 'POST',
              body: formData
              })
              .then(response => {
                var response_obj =  {
                    ok:response.ok
                    ,status:response.status
                    ,statusText:response.statusText
                    ,type:response.type
                    ,url:response.url
                    ,redirected:response.redirected
                    ,bodyUsed:response.bodyUsed
                };
                
                this.uploadedResponse[who] = response;
                
                log_video_chat_process({
                    type:'fetch'
                    ,title:'then_fetch_video_chat_verify_upload_in_VideoChat.vue_at_downloadRecording'
                    ,file_name:'VideoChat.vue'
                    ,method:'downloadRecording'
                    ,step:'ing'
                    ,act:"fetch('/admin/users/video_chat_verify_upload', {"
                    ,act_step:'then'
                    ,ajax_url:'/admin/users/video_chat_verify_upload'
                    ,ajax_step:'then'
                    ,ajax_sdata:formDataObj
                    ,ajax_rdata:response_obj
                    ,data:{file_name:file_name,file_size:blob.size}
                });

                this.isUploading[who] = false;
                this.isUploaded[who] = true;

                        var error_msg = '';
                        if(this.isUploaded['user']==true && this.isUploaded['partner']==true) {
                            clearInterval(this.uploadedIntervalId);
                            this.callPlaced = false;
                            
                            
                            
                            if(this.uploadedResponse['user']!=null 
                                && this.uploadedResponse['user'].ok==false
                            ) {
                                error_msg = this.uploadedResponse['user'].status+' '+this.uploadedResponse['user'].statusText;
                            }
                            
                            if(this.uploadedResponse['partner']!=null 
                                && this.uploadedResponse['partner'].ok==false
                                && error_msg!=this.uploadedResponse['partner'].status+' '+this.uploadedResponse['partner'].statusText
                            ) {
                                error_msg += '\n\n'+this.uploadedResponse['partner'].status+' '+this.uploadedResponse['partner'].statusText;
                            } 
                            
                            if(error_msg!='') {
                                error_msg+= '視訊影片儲存失敗!\n\n請檢查視訊記錄，若無影片請重新視訊。';
                                alert(error_msg);
                            } 
                            
                            if(this.user_permission == 'admin')
                            {
                                window.sessionStorage.setItem('endcall_reload',true);
                            }                              
                            
                            
                            location.reload();
                        }
                        
                        if(this.isNormalStop!=true && this.isPeerError==true) {
                            this.endCall();
                        }

              })
              .catch(error => {
                var error_obj =  JSON.parse(JSON.stringify(error));
                
                log_video_chat_process({
                    type:'fetch'
                    ,title:'error_fetch_video_chat_verify_upload_in_VideoChat.vue_at_downloadRecording'
                    ,file_name:'VideoChat.vue'
                    ,method:'downloadRecording'
                    ,step:'ing'
                    ,act:"fetch('/admin/users/video_chat_verify_upload', {"
                    ,act_step:'catch'
                    ,ajax_url:'/admin/users/video_chat_verify_upload'
                    ,ajax_step:'catch'
                    ,ajax_sdata:formDataObj
                    ,ajax_error:error_obj
                    ,data:{file_name:file_name,file_size:blob.size,error:error,error_toString:error.toString()}
                });                
                
                var error_msg = '';
                
                if(this.uploadedResponse['user']!=null 
                    && this.uploadedResponse['user'].ok==false
                ) {
                    error_msg = this.uploadedResponse['user'].status+' '+this.uploadedResponse['user'].statusText;
                }
                
                if(this.uploadedResponse['partner']!=null 
                    && this.uploadedResponse['partner'].ok==false
                    && error_msg!=this.uploadedResponse['partner'].status+' '+this.uploadedResponse['partner'].statusText
                ) {
                    error_msg += '\n\n'+this.uploadedResponse['partner'].status+' '+this.uploadedResponse['partner'].statusText;
                } 

                if(error_msg=='') {
                
                    if((who=='user' && this.$refs.userVideo!=undefined && this.$refs.userVideo!=null)
                        || 
                        (who=='partner' && this.$refs.partnerVideo!=undefined && this.$refs.partnerVideo!=null)
                    )
                    error_msg+= '視訊影片儲存失敗'+error.toString();
                }
                
                if(error_msg!='') {
                    error_msg+= '\n\n請檢查視訊記錄，若無影片請重新視訊。';
                    alert(error_msg);
                }

                if(this.user_permission == 'admin')
                {
                    window.sessionStorage.setItem('endcall_reload',true);
                }                  
                
                
                location.reload();
              })
              
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
      var cd_log_arr = {
            from_file:'VideoChat.vue'
            ,title:'start checkDevices()@methods@export default@VideoChat.vue'
            ,method:'checkDevices()@methods@export default'
            ,step:'start'
            ,act:'navigator.mediaDevices.enumerateDevices().then( dev => this.gotDevices(dev)).catch( err => console.warn(err));'
            ,act_step:'before'
            ,data:{navigator_mediaDevices:navigator.mediaDevices}
        };
        log_video_chat_process(cd_log_arr);  
      
      return navigator.mediaDevices.enumerateDevices()
        .then( dev => {this.gotDevices(dev);cd_log_arr.act_step='then';cd_log_arr.data={dev:dev};log_video_chat_process(cd_log_arr); } )
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

.video_chat_mask_bg,.mask_bg {
    width: 100%;
    height: 100%;
    width: 100%;
    height: 100%;
    position: fixed;
    top: 0px;
    left: 0;
    background: rgba(0,0,0,0.8);
    z-index: 30;
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
        font-size: 24px;
        font-weight: bold;
        top: 35%;
        color: #f14a6c;
        z-index:35;
        letter-spacing:1em;
    } 
</style>