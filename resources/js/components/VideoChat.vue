<template>
  <div>
    <div class="container">  
        <table class="display table cell-border" id="data-table">
            <thead>
                <tr>
                    <th class="width2word">會員上線狀態</th>
                    <th>暱稱</th>
                    <th>關於我</th>
                    <th>註冊時間</th>
                    <th>申請類型</th>
                    <th>申請日期</th>
                    <th>驗證狀況</th>
                    <th>通訊紀錄</th>
                    <th>通訊主持人</th>
                    <th>user提出問題</th>
                    <th>清晰頭像設定</th>
                    <th>清晰生活照設定</th>
                    
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr  v-for="user,row_index in getUsers" :key="user.id">
                    <td class="nowrap">
                        <span v-if="getUserOnlineStatus(user.id)" class="badge badge-light">上線中</span>
                        <span v-else-if="user.self_auth_apply.status==1 && user.self_auth_apply.from_auto==1"  class="badge badge-light">已本人認證</span>
                        <span v-else-if="user.self_auth_apply.status==1"  class="badge badge-light">已認證</span>
                        <span v-else class="badge badge-light">下線</span>                    
                    </td>                    
                    <td>
                        <a :href="getAdvInfoRelativeUrl(user.id)" target="_blank">
                        {{ user.name }}
                        </a>
                    </td>
                    <td>{{ user.user_meta.about }}</td>
                    <td  class="nowrap">{{ user.created_at.substring(0,10) }}</td>
                    <td class="nowrap">{{ user.self_auth_apply.from_auto?'美顏':'本人'}}</td>
                    <td class="nowrap">{{ user.self_auth_apply.created_at.substring(0,10)}}</td>                   
                    <td class="nowrap">{{getSelfAuthStatusContent(user.self_auth_apply.status,user.self_auth_apply.latest_video_modify,user.self_auth_apply.from_auto)}}</td> 
                    <td>
                        <div class="video_record_list_item_block" v-for="record in user.video_verify_record">
                            {{getVideoRecordContent(record)}}
                        </div>
                    </td>                       
                    <td>
                        <div class="video_admin_list_item_block" v-for="record in user.video_verify_record">
                            {{record.admin_user==null?'未記錄':record.admin_user.email.substr(0,record.admin_user.email.indexOf('@'))}}
                        </div>
                    </td>
                    <td>
                        <div 
                        :class="generateVideoUserQuestionColorClass(user.video_verify_memo?user.video_verify_memo.user_question_at:'',user.video_verify_memo?user.video_verify_memo.user_question_into_chat_at:'',user.video_verify_memo?user.video_verify_memo.user_question:'',user.self_auth_apply.status)" 
                        ref="userQuestionShowBlock"
                        >{{user.video_verify_memo!=null?user.video_verify_memo.user_question:''}}
                        </div>
                        <div class="video_user_question_edit_block" ref="userQuestionEditBlock">
                            <textarea 
                                :value="user.video_verify_memo!=null?user.video_verify_memo.user_question:''"   
                                ref="userQuestionTextarea"></textarea>
                            <button type="button" class="text-white btn btn-success" :key="user.id"  @click="saveUserQuestion(user.id,row_index)" >送出</button>
                            <button type="button" class="text-white btn btn-danger" onclick="$(this).closest('td').children().hide();$(this).closest('td').find('.video_user_question_edit_btn_block,.video_user_question_show_block').show();">取消</button>
                        </div>
                        <div class="video_user_question_edit_btn_block" ref="userQuestionEditBtnBlock">
                            <button v-if="user.self_auth_apply.status==0" type="button" class="text-white btn btn-primary" onclick="$(this).closest('td').find('textarea').css('height',Math.max($(this).closest('td').find('.video_user_question_show_block').height(),100));$(this).closest('td').children().hide();$(this).closest('td').find('.video_user_question_edit_block').show();">修改</button>
                        </div>
                    </td>
                    <td class="nowrap">
                        <div class="video_user_blurry_avatar_show_block" ref="blurryAvatarShowBlock">
                        {{user.video_verify_memo==null?'':getBlurryContent(user.video_verify_memo.blurryAvatar)}}
                        </div>
                    </td>
                    <td class="nowrap">
                        <div class="video_user_blurry_life_photo_show_block" ref="blurryLifePhotoShowBlock">
                        {{user.video_verify_memo==null?'':getBlurryContent(user.video_verify_memo.blurryLifePhoto)}}
                        </div>                   
                    </td>
                    <td class="operator-col">
                        <button
                          type="button"
                          class="btn mr-2"
                          :key="user.id"
                          :class="generateBtnClass(getUserOnlineStatus(user.id))"
                          :style="generateBtnStyle(getUserOnlineStatus(user.id))"
                          @click="getUserOnlineStatus(user.id) ? placeVideoCall(user) : null"
                        >視訊</button> 
                        <button 
                          v-if="user.self_auth_apply.status==0 && authuserid==1049 && user.video_verify_memo && user.video_verify_memo.user_question"
                          :class="generateAdminChatBtnClass(user.is_admin_chat_channel_open)"
                          @click="isChat( user.id,row_index )">回覆問題</button>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th>會員上線狀態</th>
                    <th>暱稱</th>
                    <th>關於我</th>
                    <th>註冊時間</th>
                    <th>申請類型</th>
                    <th>申請日期</th>
                    <th>驗證狀況</th>
                    <th>通訊紀錄</th>
                    <th>通訊主持人</th>
                    <th>user提出問題</th>
                    <th>清晰頭像設定</th>
                    <th>清晰生活照設定</th>                    
                    <th></th>
                </tr>
            </tfoot>
        </table>
      <!--Placing Video Call-->
      <div class="row mt-5" id="video-row"  v-if="callPlaced">       
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
          <div v-if="videoCallParams.callAccepted" id="video_chat_user_setting_block">
            <div id="user_question_edit_block_with_video">
                <div class="video_memo_edit_title_block">user提出問題</div>
                <div>
                    <textarea
                    v-model="videoChatUserQuestion"
                    ref="videoChatUserQuestionTextarea"                
                    ></textarea>
                </div>
            </div> 
            <div>
                <div class="video_memo_edit_title_block">清晰頭像設定</div>
                <div>
                    <label>
                        <input v-model="videoChatPicBlurryAvatar" name="picBlurryAvatar" type="checkbox" value="VIP" >VIP
                    </label>
                </div>
                <div>
                    <label>
                        <input v-model="videoChatPicBlurryAvatar" name="picBlurryAvatar" type="checkbox" value="general" >試用會員
                    </label>
                </div>
                <div>
                    <label>
                        <input v-model="videoChatPicBlurryAvatar" name="picBlurryAvatar" type="checkbox" value="PR" >pr <input v-model="videoChatAvatarPrValue" type="number" name="avatar_pr_value" min="0" max="100" style="height: 22px;">
                    </label>
                </div>           
            </div>
            <div>
                <div class="video_memo_edit_title_block">清晰生活照設定</div>
                <div>
                    <label>
                        <input v-model="videoChatPicBlurryLifePhoto" name="picBlurryLifePhoto" type="checkbox" value="VIP" >VIP
                    </label>
                </div>
                <div>
                    <label>
                        <input v-model="videoChatPicBlurryLifePhoto" name="picBlurryLifePhoto" type="checkbox" value="general" >試用會員
                    </label>
                </div>
                <div>
                    <label>
                        <input v-model="videoChatPicBlurryLifePhoto" name="picBlurryLifePhoto" type="checkbox" value="PR" >pr <input v-model="videoChatLifePhotoPrValue"  type="number" name="life_photo_pr_value" min="0" max="100" style="height: 22px;">
                    </label>
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
      <div class="row" id="video_income_call_dialog" v-if="incomingCallDialog">
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
   
    <div class="video_chat_call_placed_mask_bg mask_bg" v-if=" callPlaced">
    </div>
    <div class="video_chat_incomingCallDialog_mask_bg mask_bg" v-if="incomingCallDialog">
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
    <div class="mask_bg" id="decline_by_partner__msg_block">
        <div class="loading"><span class="loading_text">失敗！！！<br><br>會員拒絕接受通話<br>無法接通視訊<br><br>3秒後將自動重新整理頁面</span></div>
    </div> 
    <div class="mask_bg" id="partner_leave_page_msg_block">
        <div class="loading"><span class="loading_text">失敗！！！<br><br>會員已離開或重新整理視訊頁面<br>無法接通視訊<br><br>3秒後將自動重新整理頁面</span></div>
    </div>    
    <div class="mask_bg" id="video_error_msg_block">
        <div class="loading"><span class="loading_text"></span></div>
    </div>       
  </div>
</template>

<script>

    function log_video_chat_process(log_arr)
    {
        log_arr['url'] = location.href;

        fetch('/video/log_video_chat_process', {
              method: 'POST',
              headers: {'Content-Type': 'application/json'},
              body: JSON.stringify(log_arr)
              });              
    }
    

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
      userList:[],
      isFocusMyself: true,
      callPlaced: false,
      callPartner: null,
      callUser:null,
      videoChatUserQuestion:'',
      videoChatPicBlurryAvatar:[],
      videoChatPicBlurryLifePhoto:[],
      videoChatAvatarPrValue : null,
      videoChatLifePhotoPrValue : null,
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

        axios
          .post("/video/loading-video-page", {from_file:'VideoChat.vue',from_url:location.href})
          .then(() => {
            var log_arr = {
                from_file:'VideoChat.vue'
                ,title:'then in loading-video-page axios at begining in script@VideoChat.vue'
                ,method:'then@loading-video-page axios at begining in script'
                ,step:'within'
            };
            log_video_chat_process(log_arr);      
          })
          .catch((error) => {
            var log_arr = {
                from_file:'VideoChat.vue'
                ,title:'catch in loading-video-page axios  at begining in script@VideoChat.vue'
                ,method:'catch@loading-video-page axios  at begining in script'
                ,step:'within'
                ,data:{error:error}
            };
            log_video_chat_process(log_arr);    

            $("#error_message").text('loading-video-page axios error:' + error);
          }); 

        var old_beforeunload = $('body').attr('onbeforeunload');
        if(old_beforeunload==undefined) old_beforeunload = '';
        $('body').attr('onbeforeunload','video_beforeunload_act();');
        
    this.initializeChannel(); // this initializes laravel echo
        log_arr.title = 'ing mounted@export default@VideoChat.vue';
        log_arr.act_step = 'after';
        log_arr.step = 'ing';
        log_video_chat_process(log_arr);
        log_arr.act = 'this.initializeCallListeners();';
        log_arr.act_step = 'before';
        log_video_chat_process(log_arr);  
    this.initializeCallListeners(); // subscribes to video presence channel and listens to video events
        $.noConflict();
        
        log_arr.act = 'this.initializeCallListeners();';
        log_arr.act_step = 'after';
        log_arr.step='end';
        log_arr.title = 'end mounted@export default@VideoChat.vue';
        log_video_chat_process(log_arr);  
  },
  updated: function() {console.log('updated');console.log(this.userList);$('#data-table').DataTable().destroy();this.initDataTable(); },
  computed: {  
    getUsers() {
        let now_vue = this;
        
        let userList = [];
        if(now_vue.userList.length) {
            userList = now_vue.userList;
        }
        else {
            userList = now_vue.allusers;
        }
        console.log('getUsers userList：');
        console.log(userList);
        return userList;
    },    
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
        
        let incomingCallerArr = this.allusers.filter(
          (user) => user.id === this.videoCallParams.caller
        );
        
        if(incomingCallerArr.length==0) {
            incomingCallerArr = this.videoCallParams.users.filter(
              (user) => user.id === this.videoCallParams.caller
            );
        }
        
        const incomingCaller = incomingCallerArr;
        
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
    initDataTable() {
        let table = $('#data-table').DataTable({
            "order": [[6,'asc'],[ 0,'asc' ],[5,'desc']]
          }); 
        return table;
    },
  
    getBlurryImgValue(img_type) {
        let blurry_options = null;
        let values = '';
        let pr_value = 0;
        switch(img_type) {
            case 'avatar':
                blurry_options = this.videoChatPicBlurryAvatar;
                pr_value = this.videoChatAvatarPrValue;
            break;
            case 'pic':
                blurry_options = this.videoChatPicBlurryLifePhoto;
                pr_value = this.videoChatLifePhotoPrValue;
            break;
        }
        console.log('blurry_options=');
        console.log(blurry_options);
        if(blurry_options==null) return;
        if(pr_value=='' || pr_value==null || pr_value==undefined || !(pr_value===pr_value)) pr_value=0;
        for (var i=0; i<blurry_options.length; ++i) {
            let cur_option = blurry_options[i];
            if(cur_option=='PR'){
                values = values + cur_option +'_' + pr_value +',';          
            }
            else {
                if(cur_option=='VIP') {
                    values = cur_option +','+values  ;
                }
                else {
                    if(values.indexOf('VIP')>=0) {
                        values = values.replace('VIP','VIP,'+cur_option);
                    }
                    else {
                        values = cur_option +','+values  ;
                    }
                }
            }
        }
        values = values.replace(',,',',').replace(',,',',').replace(',,',',');
        console.log('values='+values);
        
        return values;
    }, 
    updateUserList(new_value) {     
        this.userList = new_value;
    },
  
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
            ,data:{users:users}
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
        let now_vue = this;
        await fetch('/admin/users/video_chat_get_users')
                .then((response) => {
                    return response.json();
                     
                })
                .then((response)=>{
                    var responseUserIndex = response.findIndex(
                      function(data) {return data.id === user.id;}
                    ); 
                    
                    if(responseUserIndex<0) return;
                    
                    
                    
                    let responseUser = response[responseUserIndex];
                    
                    
                    var joiningUserIndex = now_vue.videoCallParams.users.findIndex(
                      function(data) {return data.id === user.id;}
                    );                    
                    
                   if (joiningUserIndex < 0) {
                      now_vue.videoCallParams.users.push(responseUser);
                    }  

                    now_vue.userList = now_vue.allusers;

                    var joiningUserIndexInList = now_vue.userList.findIndex(
                      function(data) {return data.id === user.id;}
                    );

                    if(joiningUserIndexInList<0) {
                        $('#data-table').DataTable().destroy();
                        Vue.set(this, 'userList', response);
                        now_vue.updateUserList(now_vue.userList);
                    }                    
                }
               
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
          
          if(this.callPlaced==true ) {
            
            axios
              .post("/video/decline-call", {
                to: data.from,
                verify_record_id:data.record_id
              })
              .then(() => {
              })
              .catch((error) => {
                $("#error_message").text('decline axios error:' + error);
              });             
            
            
            alert('\n系統偵測到您正在視訊通話中，\n\n但此時有另一個會員('+data.from+')主動撥打視訊給站方，\n\n因此系統已自動回絕此會員的撥打來電，\n\n並且系統紀錄上將標註為站方未接。');
            return;
          }
          
          
          window.sessionStorage.setItem('verify_record_id', data.record_id);
          
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
        else if(this.isPeerError!=true && ( data.type ==='loadingVideoPage' || data.type ==='unloadingVideoPage' )) {
            if(data.from==this.videoCallParams.dialingTo  ||  data.from==this.videoCallParams.caller) {
                if(this.videoCallParams.callAccepted==true) {
                    this.endCall();
                }
                else if(this.callPlaced==true || this.videoCallParams.receivingCall==true) {
                    $('.mask_bg').hide();
                    $('#partner_leave_page_msg_block').show();
                    setTimeout(() => {
                        this.callPlaced = false;
                        this.videoCallParams.receivingCall=false;
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
        }
      });
        
        initializeCallListeners_log_arr.step='end';
        log_video_chat_process(initializeCallListeners_log_arr);     
    },

    async placeVideoCall(user_obj) {
        let id = user_obj.id;
        let name = user_obj.name;
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
      this.callUser = user_obj;
      this.videoCallParams.dialingTo = id;
      this.videoChatUserQuestion =  this.callUser.video_verify_memo!=null?this.callUser.video_verify_memo.user_question:'';
      
      let blurryAvatar = '';
      let blurryAvatarChkStr = '';
      let blurryLifePhoto = '';
      let blurryLifePhotoChkStr = '';
      
      if(this.callUser.video_verify_memo!=null) {
        blurryAvatar = this.callUser.video_verify_memo.blurryAvatar;
        let underline_pos = blurryAvatar?blurryAvatar.indexOf('_'):-1;
        if(underline_pos>=0) {
            blurryAvatarChkStr = blurryAvatar.substr(0,underline_pos);
            this.videoChatPicBlurryAvatar = blurryAvatarChkStr.split(',');
            console.log(this.videoChatPicBlurryAvatar);
            this.videoChatAvatarPrValue = blurryAvatar.replace(blurryAvatarChkStr+'_','').replace(',','');
            console.log('this.videoChatAvatarPrValue='+this.videoChatAvatarPrValue);
        }
        else {
            this.videoChatPicBlurryAvatar = blurryAvatar?blurryAvatar.split(','):[];
        }

        underline_pos = -1;
        blurryLifePhoto = this.callUser.video_verify_memo.blurryLifePhoto;
        underline_pos = blurryLifePhoto?blurryLifePhoto.indexOf('_'):-1;
        if(underline_pos>=0) {
            blurryLifePhotoChkStr = blurryLifePhoto.substr(0,underline_pos);
            this.videoChatPicBlurryLifePhoto = blurryLifePhotoChkStr.split(',');
            this.videoChatLifePhotoPrValue = blurryLifePhoto.replace(blurryLifePhotoChkStr+'_','').replace(',','');
        }
        else {
            this.videoChatPicBlurryLifePhoto = blurryLifePhoto?blurryLifePhoto.split(','):[];
        }    
      }

        $('.mask_bg').hide();
        
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
            from_admin:1
          })
          .then((data) => {
            window.sessionStorage.setItem('verify_record_id', data.data);

            var log_arr = {
                from_file:'VideoChat.vue'
                ,title:'then in this.videoCallParams.peer1.on("signal", (data) =>@async placeVideoCall(id, name)@methods@export default@VideoChat.vue'
                ,method:'then@this.videoCallParams.peer1.on("signal", (data) =>@async placeVideoCall(id, name)@methods@export default'
                ,step:'within'
                ,data:data
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
        if(this.callPlaced==true) this.callPlaced=false;
        if(this.videoCallParams.receivingCall==true) this.videoCallParams.receivingCall=false;      
        
        this.isPeerError = true;
        if(this.isNormalStop!=true) {
            if(err.toString().indexOf('OperationError')>=0 && err.toString().indexOf('Transport')>=0 && err.toString().indexOf('channel')>=0 && err.toString().indexOf('closed')>=0 ||  err.code=='ERR_DATA_CHANNEL') {
                $('.mask_bg').hide();
                $('#break_by_partner_before_connect_msg_block').show();
                return;
            }
            $('.mask_bg').hide();
            $('#video_error_msg_block').show().find('.loading_text').html('錯誤！！！<br><br>連線錯誤：'+err.toString()+((this.mediaRecorder!=null && this.isUploading['partner'])?'<br>殘存檔案上傳中<br><br>請勿重新整理<br>或離開本頁面':''));      
        
            if(this.mediaRecorder==null || !this.isUploading['partner']) {
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

      this.videoCallParams.peer1.on("close", () => {
        var log_arr = {
            from_file:'VideoChat.vue'
            ,title:'this.videoCallParams.peer1.on("close", () =>@async placeVideoCall(id, name)@methods@export default@VideoChat.vue'
            ,method:'this.videoCallParams.peer1.on("close", () =>@async placeVideoCall(id, name)@methods@export default'
            ,step:'within'
        };
        log_video_chat_process(log_arr);                  
        if(this.isNormalStop!=true && this.callPlaced==true && this.isPeerError!=true && this.videoCallParams.callAccepted==true) {
            this.endCall();
        }
        else {
            if(this.callPlaced==true) this.callPlaced=false;
            if(this.videoCallParams.receivingCall==true) this.videoCallParams.receivingCall=false;      
        }
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
        else if(data.type === 'declineCall' &&  data.to== this.authuserid) {
            $("#decline_by_partner__msg_block").show();

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
      let nowCallId = this.videoCallParams.caller;
      let nowCallerDetail = null;
      let callerUserIndex = this.allusers.findIndex(
        (data) => data.id === nowCallId
      ); 
      if(callerUserIndex>=0) {
        nowCallerDetail = this.allusers[callerUserIndex];
      }
      else {
          callerUserIndex = this.videoCallParams.users.findIndex(
            (data) => data.id === nowCallId
          );
          
         if(callerUserIndex>=0) {
            nowCallerDetail = this.videoCallParams.users[callerUserIndex];
         }
         else return;          
      }
     
     this.videoChatUserQuestion = nowCallerDetail.video_verify_memo!=null?nowCallerDetail.video_verify_memo.user_question:'';
      let blurryAvatar = '';
      let blurryAvatarChkStr = '';
      let blurryLifePhoto = '';
      let blurryLifePhotoChkStr = '';
      
      if(nowCallerDetail.video_verify_memo!=null) {
        blurryAvatar = nowCallerDetail.video_verify_memo.blurryAvatar;
        let underline_pos = blurryAvatar?blurryAvatar.indexOf('_'):-1;
        if(underline_pos>=0) {
            blurryAvatarChkStr = blurryAvatar.substr(0,underline_pos);
            this.videoChatPicBlurryAvatar = blurryAvatarChkStr.split(',');
            console.log(this.videoChatPicBlurryAvatar);
            this.videoChatAvatarPrValue = blurryAvatar.replace(blurryAvatarChkStr+'_','').replace(',','');
            console.log('this.videoChatAvatarPrValue='+this.videoChatAvatarPrValue);
        }
        else {
            this.videoChatPicBlurryAvatar = blurryAvatar?blurryAvatar.split(','):[];
        }

        underline_pos = -1;
        blurryLifePhoto = nowCallerDetail.video_verify_memo.blurryLifePhoto;
        underline_pos = blurryLifePhoto?blurryLifePhoto.indexOf('_'):-1;
        if(underline_pos>=0) {
            blurryLifePhotoChkStr = blurryLifePhoto.substr(0,underline_pos);
            this.videoChatPicBlurryLifePhoto = blurryLifePhotoChkStr.split(',');
            this.videoChatLifePhotoPrValue = blurryLifePhoto.replace(blurryLifePhotoChkStr+'_','').replace(',','');
        }
        else {
            this.videoChatPicBlurryLifePhoto = blurryLifePhoto?blurryLifePhoto.split(','):[];
        } 
      }
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
            verify_record_id:window.sessionStorage.getItem('verify_record_id')
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
              verify_user_id:this.videoCallParams.caller,
              verify_record_id:window.sessionStorage.getItem('verify_record_id')
              
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
        if(this.callPlaced==true) this.callPlaced=false;
        if(this.videoCallParams.receivingCall==true) this.videoCallParams.receivingCall=false;        
        this.isPeerError = true;
        if(this.isNormalStop!=true) {
            if(err.toString().indexOf('OperationError')>=0 && err.toString().indexOf('Transport')>=0 && err.toString().indexOf('channel')>=0 && err.toString().indexOf('closed')>=0  ||  err.code=='ERR_DATA_CHANNEL') {
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
             
        if(this.isNormalStop!=true && this.callPlaced==true && this.isPeerError!=true && this.videoCallParams.callAccepted==true) {
            this.endCall();
        }
        else {      
            if(this.callPlaced==true) this.callPlaced=false;
            if(this.videoCallParams.receivingCall==true) this.videoCallParams.receivingCall=false;      
        }
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

    getSelfAuthStatusContent(status,latest_video_modify,from_auto) {
        let content = '';
        if(status==1) {
            if(from_auto==1) content = '已本人認證';
            else content = '已認證';
        }
        else if(status==2) content = '已取消';
        else if(latest_video_modify!=null && latest_video_modify!=undefined) {
            content = '已視訊';
        }
        else if(status==0) {
            content = '尚未';
        }
        
        return content;
    },

    getVideoRecordContent(record) {
        let content = '';
        let status_str = '';
        let rd_created_at = record.created_at;
         rd_created_at   = new Date(rd_created_at);

        content+=(rd_created_at.getMonth() + 1).toString().padStart(2, '0')+'_'+rd_created_at.getDate().toString().padStart(2, '0')+'_'+rd_created_at.getHours().toString().padStart(2, '0')+'_'+rd_created_at.getMinutes().toString().padStart(2, '0');

        switch (record.admin_last_action) {
            case 'upload_complete':
                status_str = '順利結束';
                break;
            case 'upload_user_video':
                status_str = '僅存會員視訊';
                break;
            case 'upload_admin_video':
                status_str = '僅存站方視訊';
                break;
        } 

        let last_role = '';
        let last_action = '';

        if(status_str=='') {
            if(record.admin_last_action_at >= record.user_last_action_at) {
                last_role = 'admin';
                last_action = record.admin_last_action;
            }
            else {
                last_role = 'user';
                last_action = record.user_last_action;            
            }
            
            if(last_action=='' || last_action==null) {
                if(record.admin_video!='' && record.admin_video!=null && record.user_video!='' && record.user_video!=null) {
                    status_str = '順利結束';
                }
                else {
                    switch(record.is_caller_admin) {
                        case 0:
                            status_str = '站方未接';
                        break;
                        case 1:
                            status_str = '會員未接';
                        break;
                    }                
                }
            

            }
            
            if(status_str=='') {
            
                if(last_action=='declineCall') {
                    status_str = last_role.replace('admin','站方').replace('user','會員')+'拒接';
                }
                if(last_action=='callAccepted') {
                    status_str = '通話中或已中斷';
                }
            }
            
            if(status_str=='') {
                status_str = '已中斷';
            }
            
            content = content + status_str;

            return content;
        }
        
        if(status_str=='') {
            status_str = '已中斷';
        }
        
        content+=status_str;
        return content;
    },
    
    getBlurryContent(blurry) {
        if(blurry==undefined || blurry==null) return;
       
        return blurry.replace('general','試用會員');
    },

    getAdvInfoRelativeUrl(id) {
        return './advInfo/'+id;
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
            verify_record_id:window.sessionStorage.getItem('verify_record_id')
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
            ,data:{videoElem:videoElem!=undefined?videoElem:null,stream:videoElem!=undefined?videoElem.srcObject:null,tracks:videoElem!=undefined?videoElem.srcObject.getTracks():null}
        };
      log_video_chat_process(ssv_log_arr);
      
      
      
        if(typeof videoElem!='undefined' && videoElem!=null) {
            const stream = videoElem.srcObject;
            if(typeof stream!='undefined' && stream!=null) {
                const tracks = stream.getTracks();
            }
        }
        this.isNormalStop = true;
        
        if(typeof stream=='undefined' || typeof tracks=='undefined' || stream==null || tracks==null) { 

            return;
        }
              
      
      
      
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
      
      if(window.sessionStorage.getItem('verify_record_id'))
            this.saveUserMemoWithoutRenewByRecordId(window.sessionStorage.getItem('verify_record_id'));
      
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

    generateAdminChatBtnClass(is_admin_chat_channel_open) {
      let color_class = '';
      if(is_admin_chat_channel_open){
        color_class =  'btn-success';
      }
      else{
        color_class =  'btn-danger'
      }
      
      return 'btn '+color_class+' message_record_btn';
    }, 

    generateVideoUserQuestionColorClass(user_question_at,into_chat_at,user_question,status) {
        let color_class = '';
        let base_class = 'video_user_question_show_block ';
        if(status!=0) return base_class;
        if(user_question=='' || user_question==undefined || user_question==null) return base_class;
        if(into_chat_at==null || into_chat_at==undefined || into_chat_at=='' || user_question_at>into_chat_at) {
            color_class = 'not_into_chat_yet';
        }
         
        return base_class+color_class;
        
    } ,  

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
      
      let verify_record_id = window.sessionStorage.getItem('verify_record_id');
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
      if(this.videoChatUserQuestion==null || this.videoChatUserQuestion==undefined) this.videoChatUserQuestion='';
      formData.append( "user_question",this.videoChatUserQuestion);
      formData.append( "blurryAvatar",this.getBlurryImgValue('avatar'));
      formData.append( "blurryLifePhoto",this.getBlurryImgValue('pic'));
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
    
    updateUserMemoByRowIndex(memo,row_index) {
        let memo_obj = memo;
        let old_arr = this.userList;
        if(old_arr.length==0) old_arr=this.allusers;
        let temp_arr = old_arr.splice(row_index,old_arr.length-1);
        let cur_arr = temp_arr.shift();
        cur_arr.video_verify_memo = memo_obj;
        temp_arr.unshift(cur_arr);
        this.updateUserList(old_arr.concat(temp_arr));    
    },
    
    saveUserMemoWithoutRenewByRecordId(verify_record_id) {
      let vue_obj = this;
      let saveData = {};
      saveData['_token'] = vue_obj.csrf;
      saveData['verify_record_id'] = verify_record_id;
      if(this.videoChatUserQuestion==null || this.videoChatUserQuestion==undefined) this.videoChatUserQuestion='';
      saveData['user_question'] = this.videoChatUserQuestion;
      saveData['blurryAvatar'] = this.getBlurryImgValue('avatar');
      saveData['blurryLifePhoto'] = this.getBlurryImgValue('pic');

          $.ajax({
            type:'post',
            url:'/admin/users/video_chat_memo_save',
            data:saveData,
          });       
    },
  
    saveUserQuestion(verify_user_id,row_index) {
          let vue_obj = this;
          
          $.ajax({
            type:'post',
            url:'/admin/users/video_chat_memo_save',
            data:{
              _token:vue_obj.csrf
              ,verify_user_id:verify_user_id
              ,user_question:vue_obj.$refs.userQuestionTextarea[row_index].value
              
            },
            success:function(data){
                vue_obj.updateUserMemoByRowIndex(data.memo,row_index);

                vue_obj.$refs.userQuestionShowBlock[row_index].style="display:block;";
                vue_obj.$refs.userQuestionEditBlock[row_index].style="display:none;";
                vue_obj.$refs.userQuestionEditBtnBlock[row_index].style="display:block;";
           }
            ,error:function(xhr) {

            }
          });    
    },
   
    isChat(id,row_index) {
        window.open('/admin/users/message/record/' + id + '?from_videoChat=1');
        let csrf_token = this.csrf;
        let vue_obj = this;
        
        $.ajax({
            type: 'POST',
            url: '/admin/users/user_question_into_chat_time_save',
            data:{
                _token: csrf_token,
                verify_user_id: id,
            },
            dataType:"json",
            success: function(data){
                if(data==null || data=='' || data==undefined || data.memo==null || data.memo==undefined) return;
                vue_obj.updateUserMemoByRowIndex(data.memo,row_index);
            }
        });
        
    }
  
  },
};
</script>

<style scoped>
#video-row {
  width: 700px;
  max-width: 90vw;
  padding-top:100px;
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