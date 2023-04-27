<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Events\StartVideoChat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\UserVideoVerifyRecord;
use App\Models\UserVideoVerifyRecordLog;
use App\Models\UserVideoVerifyMemo;
use App\Services\RealAuthPageService;
use LZCompressor\LZString;
use App\Models\WebrtcSignalData;
use App\Http\Controllers\Admin\UserController;
use App\Models\BackendUserDetails;
use App\Services\UserService;
use App\Services\VipLogService;
use App\Repositories\SuspiciousRepository;
use App\Models\RealAuthQuestion;
use App\Models\SimpleTables\warned_users;
use App\Services\ShortMessageService;
use App\Models\UserMeta;
use App\Services\ActivateService;
use App\Notifications\ReverifyAdvAuthUserEmail;

class VideoChatController extends BaseController
{   
    public function __construct(UserService $userService, VipLogService $logService, SuspiciousRepository $suspiciousRepo, RealAuthPageService $rap_service, ActivateService $activateService)
    {
        parent::__construct();
        $this->service = $activateService;
    }    
    
    public function callUser(Request $request)
    {
        if(auth()->user()->id) {
            session()->put('sess_user_id', auth()->user()->id);
        }
        $logArr = $this->getDefaultLogArr();
        $logArr['method'] = 'VideoChatController@callUser';
        $logArr['step'] = 'start';
        $logArr['title'] = '';
        $this->logByArr($logArr);

        $signal_data = json_encode($request->signal_data);

        $save_data = new WebrtcSignalData;
        $save_data->signal_data = $signal_data;
        $save_data->save();
                
        $data['userToCall'] = $request->user_to_call;
        $data['signalData'] = $save_data->id;
        $data['from'] = Auth::id();
        $data['type'] = 'incomingCall';
        
        $from_admin = $request->from_admin;
        $verify_user_id = null;
        $admin_id = null;
        
        if($from_admin) {
            $admin_id = auth()->user()->id;
            $verify_user_id = $data['userToCall'];
        }
        else {
            $admin_id = $data['userToCall'];
            $verify_user_id = auth()->user()->id;
        }
        
        $record_id = $this->_video_chat_verify_record_init_core($verify_user_id,$admin_id,$from_admin);        
        
        $data['record_id'] = $record_id;

        $logArr['step'] = 'ending';
        $logArr['title'] = 'before broadcast(new StartVideoChat($data))->toOthers();';
        $logArr['act'] = 'broadcast new StartVideoChat($data)';
        $logArr['act_step'] = 'before';
        $logArr['data'] = $data;
        $this->logByArr($logArr);        
        
        broadcast(new StartVideoChat($data))->toOthers();
    
        $logArr['step'] = 'end';
        $logArr['title'] = 'after broadcast(new StartVideoChat($data))->toOthers();';
        $logArr['act_step'] = 'after';
        $logArr['data'] = $data;
        $this->logByArr($logArr);     
    
        return  $record_id;
    }
    
    
    public function loadingVideoPage(Request $request)
    {
        if(auth()->user()->id) {
            session()->put('sess_user_id', auth()->user()->id);
        }
        $is_log = true;
        if($request->from_file=='VideoVerifyUserEntireSite.vue') {
             $is_log = false;
        }
        
        $logArr = $this->getDefaultLogArr();
        $logArr['method'] = 'VideoChatController@loadingVideoPage';
        $logArr['step'] = 'start';
        $logArr['title'] = '';
        $logArr['from_file'] = $request->from_file;
        if($request->from_url) $logArr['url'] = $request->from_url;
        if($is_log) $this->logByArr($logArr);        

        $data['from'] = Auth::id();
        $data['type'] = 'loadingVideoPage';

        $logArr['step'] = 'ending';
        $logArr['title'] = 'before broadcast(new StartVideoChat($data))->toOthers();';
        $logArr['act'] = 'broadcast new StartVideoChat($data)';
        $logArr['act_step'] = 'before'; 
        $logArr['data'] = $data;
        if($is_log) $this->logByArr($logArr); 
        
        broadcast(new StartVideoChat($data))->toOthers();

        $logArr['step'] = 'end';
        $logArr['title'] = 'after broadcast(new StartVideoChat($data))->toOthers();';
        $logArr['act_step'] = 'after'; 
        $logArr['data'] = $data;
        if($is_log) $this->logByArr($logArr); 
    }     

    public function unloadingVideoPage(Request $request)
    {
        if(auth()->user()->id) {
            session()->put('sess_user_id', auth()->user()->id);
        }
        
        $is_log = true;
        if($request->from_file=='video_verify_user_entire_site.tpl') {
            $is_log = false;
        }
        
        $logArr = $this->getDefaultLogArr();
        $logArr['method'] = 'VideoChatController@unloadingVideoPage';
        $logArr['step'] = 'start';
        $logArr['title'] = '';
        $logArr['from_file'] = $request->from_file;
        if($request->from_url) $logArr['url'] = $request->from_url;
        
        if($is_log) $this->logByArr($logArr);        

        $data['from'] = Auth::id();
        $data['type'] = 'unloadingVideoPage';

        $logArr['step'] = 'ending';
        $logArr['title'] = 'before broadcast(new StartVideoChat($data))->toOthers();';
        $logArr['act'] = 'broadcast new StartVideoChat($data)';
        $logArr['act_step'] = 'before'; 
        $logArr['data'] = $data;
        if($is_log) $this->logByArr($logArr); 
        
        broadcast(new StartVideoChat($data))->toOthers();

        $logArr['step'] = 'end';
        $logArr['title'] = 'after broadcast(new StartVideoChat($data))->toOthers();';
        $logArr['act_step'] = 'after'; 
        $logArr['data'] = $data;
        if($is_log) $this->logByArr($logArr); 
    }       

    public function acceptCall(Request $request)
    {
        if(auth()->user()->id) {
            session()->put('sess_user_id', auth()->user()->id);
        }
        $logArr = $this->getDefaultLogArr();
        $logArr['method'] = 'VideoChatController@acceptCall';
        $logArr['step'] = 'start';
        $logArr['title'] = '';
        $this->logByArr($logArr);        

        $signal = json_encode($request->signal);

        $save_data = new WebrtcSignalData;
        $save_data->signal_data = $signal;
        $save_data->save();
        
        $data['signal'] = $save_data->id;
        $data['to'] = $request->to;
        $data['type'] = 'callAccepted';

        $logArr['step'] = 'ending';
        $logArr['title'] = 'before broadcast(new StartVideoChat($data))->toOthers();';
        $logArr['act'] = 'broadcast new StartVideoChat($data)';
        $logArr['act_step'] = 'before'; 
        $logArr['data'] = $data;
        $this->logByArr($logArr); 
        
        broadcast(new StartVideoChat($data))->toOthers();

        $verify_record_id = $request->verify_record_id;
        $data['action'] = 'acceptCall';
        $this->_video_chat_verify_record_save_core($verify_record_id,$data);

        $logArr['step'] = 'end';
        $logArr['title'] = 'after broadcast(new StartVideoChat($data))->toOthers();';
        $logArr['act_step'] = 'after'; 
        $logArr['data'] = $data;
        $this->logByArr($logArr); 
    }
    
    public function declineCall(Request $request)
    {
        if(auth()->user()->id) {
            session()->put('sess_user_id', auth()->user()->id);
        }
        $logArr = $this->getDefaultLogArr();
        $logArr['method'] = 'VideoChatController@declineCall';
        $logArr['step'] = 'start';
        $logArr['title'] = '';
        $this->logByArr($logArr);        

        $data['to'] = $request->to;
        $data['type'] = 'declineCall';

        $logArr['step'] = 'ending';
        $logArr['title'] = 'before broadcast(new StartVideoChat($data))->toOthers();';
        $logArr['act'] = 'broadcast new StartVideoChat($data)';
        $logArr['act_step'] = 'before'; 
        $logArr['data'] = $data;
        $this->logByArr($logArr); 
        
        broadcast(new StartVideoChat($data))->toOthers();

        $verify_record_id = $request->verify_record_id;
        $data_arr['action'] = $data['type'];
        $this->_video_chat_verify_record_save_core($verify_record_id,$data_arr);

        $logArr['step'] = 'end';
        $logArr['title'] = 'after broadcast(new StartVideoChat($data))->toOthers();';
        $logArr['act_step'] = 'after'; 
        $logArr['data'] = $data;
        $this->logByArr($logArr); 
    }    

    
    public function abortDialCall(Request $request)
    {
        if(auth()->user()->id) {
            session()->put('sess_user_id', auth()->user()->id);
        }
        $logArr = $this->getDefaultLogArr();
        $logArr['method'] = 'VideoChatController@abortDialCall';
        $logArr['step'] = 'start';
        $logArr['title'] = '';
        $this->logByArr($logArr);        

        $data['to'] = $request->to;
        $data['type'] = 'abortDialCall';

        $logArr['step'] = 'ending';
        $logArr['title'] = 'before broadcast(new StartVideoChat($data))->toOthers();';
        $logArr['act'] = 'broadcast new StartVideoChat($data)';
        $logArr['act_step'] = 'before'; 
        $logArr['data'] = $data;
        $this->logByArr($logArr); 
        
        broadcast(new StartVideoChat($data))->toOthers();

        $logArr['step'] = 'end';
        $logArr['title'] = 'after broadcast(new StartVideoChat($data))->toOthers();';
        $logArr['act_step'] = 'after'; 
        $logArr['data'] = $data;
        $this->logByArr($logArr); 
    }  

    public function receiveCallUserSignalData(Request $request)
    {
        if(auth()->user()->id) {
            session()->put('sess_user_id', auth()->user()->id);
        }
        $logArr = $this->getDefaultLogArr();
        $logArr['method'] = 'VideoChatController@receiveCallUserSignalData';
        $logArr['step'] = 'start';
        $logArr['title'] = '';
        $this->logByArr($logArr);        

        $id = $request->signal_data_id;
        $signal_data = WebrtcSignalData::where('id', $id)->first()->signal_data;

        $logArr['method'] = 'VideoChatController@receiveCallUserSignalData';
        $logArr['step'] = 'end';
        $logArr['title'] = 'before return $signal_data;';
        $logArr['data']['signal_data'] = $signal_data;
        $this->logByArr($logArr);
        
        return $signal_data;
    }

    public function receiveAcceptCallSignalData(Request $request)
    {
        if(auth()->user()->id) {
            session()->put('sess_user_id', auth()->user()->id);
        }
        $logArr = $this->getDefaultLogArr();
        $logArr['method'] = 'VideoChatController@receiveAcceptCallSignalData';
        $logArr['step'] = 'start';
        $logArr['title'] = '';
        $this->logByArr($logArr);        

        $id = $request->signal_data_id;
        $signal_data = WebrtcSignalData::where('id', $id)->first()->signal_data;

        $logArr['step'] = 'end';
        $logArr['title'] = 'before return $signal_data;';
        $logArr['data']['signal_data'] = $signal_data;
        $this->logByArr($logArr);
        
        return $signal_data;
    }

    public function video_chat_verify(Request $request)
    {
        if(auth()->user()->id) {
            session()->put('sess_user_id', auth()->user()->id);
        }
        
        $logArr = $this->getDefaultLogArr();
        $logArr['method'] = 'VideoChatController@video_chat_verify';
        $logArr['step'] = 'start';
        $logArr['title'] = '';
        $this->logByArr($logArr);        

        $users = $this->video_chat_get_users($request,true);

        $logArr['step'] = 'end';
        $logArr['title'] = 'before return view(\'admin.users.video_chat_verify\', [\'users\' => $users]);';
        $logArr['data']['users'] = $users->pluck('id')->all();
        $this->logByArr($logArr);
        
        return view('admin.users.video_chat_verify', ['users' => $users]);
    }

    public function user_video_chat_verify(Request $request,RealAuthPageService $rap_service)
    {
        if(auth()->user()->id) {
            session()->put('sess_user_id', auth()->user()->id);
        }
        $logArr = $this->getDefaultLogArr();
        $logArr['method'] = 'VideoChatController@user_video_chat_verify';
        $logArr['step'] = 'start';
        $logArr['title'] = '';
        $this->logByArr($logArr);        

        if(!$rap_service->riseByUserId(Auth::id())->isAllowUseVideoChat()) {
            if($request->server('HTTP_REFERER'))
                return redirect($request->server('HTTP_REFERER'));
            
            return redirect('/dashboard/personalPage');
        }
        
        $users = DB::table('role_user')->leftJoin('users', 'role_user.user_id', '=', 'users.id')->where('users.id', '<>', Auth::id())->get();
        //$users->isSelfAuthWaitingCheck = $rap_service->isSelfAuthWaitingCheck();
        $logArr['step'] = 'end';
        $logArr['title'] = 'before return view(\'auth.user_video_chat_verify\', [\'users\' => $users]);';
        $logArr['data']['users'] = $users->pluck('id')->all();;
        $this->logByArr($logArr);        
        
        return view('auth.user_video_chat_verify', ['users' => $users,'rap_service'=>$rap_service]);
    }

    public function videoChatTest(Request $request)
    {
        if(auth()->user()->id) {
            session()->put('sess_user_id', auth()->user()->id);
        }
        
        $logArr = $this->getDefaultLogArr();
        $logArr['method'] = 'VideoChatController@videoChatTest';
        $logArr['step'] = 'start';
        $logArr['title'] = '';
        $this->logByArr($logArr);        

        $users = User::where('id', '15600')->orWhere('id', '15599')->orWhere('id', '12374')->get();

        $logArr['step'] = 'end';
        $logArr['title'] = 'before return view(\'video-chat-test\', [\'users\' => $users]);';
        $logArr['data']['users'] = $users->pluck('id')->all();;
        $this->logByArr($logArr);         
        
        return view('video-chat-test', ['users' => $users]);
    }
    
    public function video_chat_verify_upload_init(Request $request)
    {
        if(auth()->user()->id) {
            session()->put('sess_user_id', auth()->user()->id);
        }
        
        $logArr = $this->getDefaultLogArr();
        $logArr['method'] = 'VideoChatController@video_chat_verify_upload_init';
        $logArr['step'] = 'start';
        $logArr['title'] = '';
        $this->logByArr($logArr);        

        $verify_user_id = $request->verify_user_id;
        
        $verify_record_id = $request->verify_record_id;
        $data['action'] = 'upload_init';
        $this->_video_chat_verify_record_save_core($verify_record_id,$data);        
        
        $logArr['data']['verify_user_id'] = $verify_user_id;
        $logArr['data']['data'] = $data;
        $logArr['data']['verify_record_id'] = $verify_record_id;
        $logArr['step'] = 'end';
        $logArr['act'] = '$user_video_verify_record->save()';
        $logArr['act_step'] = 'after';
        $logArr['title'] = 'before return response()->json([\'record_id\' => $user_video_verify_record->id]); after $rs = $user_video_verify_record->save();';
        $this->logByArr($logArr); 
        
        return response()->json(['record_id' => $verify_record_id]);
    }    

    private function _video_chat_verify_record_init_core($verify_user_id,$admin_id,$from_admin=0)
    {
        if(auth()->user()->id) {
            session()->put('sess_user_id', auth()->user()->id);
        }

        $user_video_verify_record = new UserVideoVerifyRecord;
        $user_video_verify_record->user_id = $verify_user_id;
        $user_video_verify_record->admin_id = $admin_id;               
        $user_video_verify_record->is_caller_admin = $from_admin?1:0;
        $rs = $user_video_verify_record->save();
        if($rs) {
            $memo_entry = UserVideoVerifyMemo::where('user_id',$verify_user_id)->first();
            if(!$memo_entry) {
                $memo_entry = new UserVideoVerifyMemo;
                $memo_entry->user_id = $verify_user_id;
                $memo_entry->last_edit_admin_id = $admin_id; 
                $memo_entry->save();
            }
            return $user_video_verify_record->id;
        }
    }

    public function video_chat_verify_record_init(Request $request)
    {
        if(auth()->user()->id) {
            session()->put('sess_user_id', auth()->user()->id);
        }
        
        $verify_record_id = $request->verify_record_id;
        
        if($verify_record_id) {
            
            return response()->json(['record_id' => $verify_record_id]);
        }
        
        $from_admin = $request->from_admin;
        $user_to_call = $request->user_to_call;
        $verify_user_id = null;
        $admin_id = null;
        
        if($from_admin) {
            $admin_id = auth()->user()->id;
            $verify_user_id = $user_to_call;
        }
        else {
            $admin_id = $user_to_call;
            $verify_user_id = auth()->user()->id;
        }        
        
        
        
        $verify_record_id = $this->_video_chat_verify_record_init_core($verify_user_id,$admin_id,$from_admin);

        return response()->json(['record_id' => $verify_record_id]);
    }

    public function video_chat_verify_upload(Request $request,RealAuthPageService $rap_service)
    {
        if(auth()->user()->id) {
            session()->put('sess_user_id', auth()->user()->id);
        }
        
        $logArr = $this->getDefaultLogArr();
        $logArr['method'] = 'VideoChatController@video_chat_verify_upload';
        $logArr['step'] = 'start';
        $logArr['title'] = '';
        $this->logByArr($logArr);
      
        $path = $request->file('video')->store('video_chat_verify');

        $who = $request->who;
        $user_question = $request->user_question;
        $blurryAvatar = $request->blurryAvatar;
        $blurryLifePhoto = $request->blurryLifePhoto;

        $verify_record_id = $request->verify_record_id;

        $user_video_verify_record = UserVideoVerifyRecord::where('id',$verify_record_id)->first();

        if($user_question) {
            $user_video_verify_record->user_question = $user_question; 
        }
        
        if($blurryAvatar) {
            $user_video_verify_record->blurryAvatar = $blurryAvatar; 
        }
        
        if($blurryLifePhoto) {
            $user_video_verify_record->blurryLifePhoto = $blurryLifePhoto; 
        }

        $logArr['data']['path'] = $path;
        $logArr['data']['who'] = $who;
        $logArr['data']['verify_record_id'] = $verify_record_id;
        $logArr['data']['user_video_verify_record'] = $user_video_verify_record;
        $logArr['step'] = 'ing';
        $this->logByArr($logArr);

        if($who == 'partner')
        {
            $user_video_verify_record->user_video = $path;
            
        }
        elseif($who == 'user')
        {
            $user_video_verify_record->admin_video = $path;
        }
        if($user_video_verify_record->save())
        {
            if($user_question || $blurryAvatar || $blurryLifePhoto) {
                $this->video_chat_memo_save($request);   
            }
            $logArr['title'] = $request->user()->id.' $user_video_verify_record->save() success';
            $logArr['act'] = '$user_video_verify_record->save()';
            $logArr['act_step'] = 'success';
            $this->logByArr($logArr);
            if($who == 'partner') {
                $rap_service->riseByUserId($user_video_verify_record->user_id);
                $rap_service->saveVideoRecordId($user_video_verify_record->id);
                $action = 'upload_user_video';
                
            }
            else {
                $action = 'upload_admin_video';
            }
            
            if($user_video_verify_record->user_video && $user_video_verify_record->admin_video) $action = 'upload_complete';
            $this->_video_chat_verify_record_save_core($user_video_verify_record->id,['action'=>$action]);

            $logArr['title'] = $request->user()->id.' video_chat_verify_upload $user_video_verify_record->save() success return end。';
            $logArr['step'] = 'end';
            $logArr['act'] = '$user_video_verify_record->save()';
            $logArr['act_step'] = 'end';
            $this->logByArr($logArr);
            return ['path'=>$path,'upload'=>'success'];
        }

        $this->logByArr([
            'user_id
        
                ']);
    }

    private function _video_chat_verify_record_save_core($verify_record_id,$data_arr)
    {
        if(auth()->user()->id) {
            session()->put('sess_user_id', auth()->user()->id);
        }

        $action = $data_arr['action']??null;
        $user_video_verify_record = UserVideoVerifyRecord::where('id',$verify_record_id)->first();
        
        if($user_video_verify_record->user_id == auth()->user()->id)
        {
            if($action) {
                $user_video_verify_record->user_last_action = $action;
                $user_video_verify_record->user_last_action_at = Carbon::now();     
            }
        }
        elseif($user_video_verify_record->admin_id == auth()->user()->id)
        {
            if($action) {
                $user_video_verify_record->admin_last_action = $action;
                $user_video_verify_record->admin_last_action_at = Carbon::now();
            }
        }

        $user_question = $data_arr['user_question']??null;
        $blurryAvatar = $data_arr['blurryAvatar']??null;
        $blurryLifePhoto = $data_arr['blurryLifePhoto']??null;
        
        if($user_question) $user_video_verify_record->user_question = $user_question;
        if($blurryAvatar) $user_video_verify_record->blurryAvatar = $blurryAvatar;
        if($blurryLifePhoto) $user_video_verify_record->blurryLifePhoto = $blurryLifePhoto;

        return $user_video_verify_record->save();

    }      
    
    public function video_chat_verify_record_save(Request $request,RealAuthPageService $rap_service)
    {
        if(auth()->user()->id) {
            session()->put('sess_user_id', auth()->user()->id);
        }

        $verify_record_id = $request->verify_record_id;
        
        if($request->action) $data['action'] = $request->action;
        if($request->user_question) $data['user_question'] = $request->user_question;
        if($request->blurryAvatar) $data['blurryAvatar'] = $request->blurryAvatar;
        if($request->blurryLifePhoto) $data['blurryLifePhoto'] = $request->blurryLifePhoto;        
        
        return $this->_video_chat_verify_record_save_core($verify_record_id,$data);
    }    

    public function video_chat_verify_record_list(Request $request)
    {
        $user_video_verify_record = UserVideoVerifyRecord::select('user_video_verify_record.*', 'users.name', 'users.email')
            ->leftJoin('users', 'user_video_verify_record.user_id', '=', 'users.id')
            ->where('admin_id', '!=', 0)
            ->orderBy('user_video_verify_record.created_at', 'desc')
            ->get()
            ->unique('user_id');

        return view('admin.users.video_chat_verify_record_list', ['user_video_verify_record' => $user_video_verify_record]);
    }

    public function video_chat_verify_record(Request $request)
    {   
        $user_id = $request->user_id;
        $record = UserVideoVerifyRecord::where('user_id', $user_id)->where('admin_id', '!=', 0)->orderBy('created_at', 'desc')->get();

        return view('admin.users.video_chat_verify_record', ['record' => $record]);
    }
    
    public function log_video_chat_process(Request $request) 
    {
        if($request->from_file=='VideoChat.vue'
            && strpos($request->url,'dashboard/personalPage')!==false
        )  return;
        
        if(auth()->user()->id) {
            session()->put('sess_user_id', auth()->user()->id);
        }
        $logArr = $this->getDefaultLogArr();      
        
        $req = $request->all();

        $logArr = array_merge($logArr,$req);
        
        if(($logArr['type']??null)=='controller') $logArr['type'] = 'client';
         if(!($logArr['user_id']??null)) $logArr['user_id'] = auth()->user()->id;             
        return response()->json(intval($this->logByArr($logArr)));
    }
    
    private static function logByArr(&$arr) 
    {
        
        if(is_countable($arr['data']??null)) $arr['data'] = json_encode($arr['data']);
        if(is_countable($arr['ajax_sdata']??null)) $arr['ajax_sdata'] = json_encode($arr['ajax_sdata']);
        if(is_countable($arr['ajax_rdata']??null)) $arr['ajax_rdata'] = json_encode($arr['ajax_rdata']);
         if(is_countable($arr['ajax_error']??null)) $arr['ajax_error'] = json_encode($arr['ajax_error']);

        if(session()->get($arr['sid'].'_userAgent')) {
            $arr['user_agent'] = null;
            unset($arr['user_agent']);
        } 

        if(session()->get(md5($arr['url']).'_'.$arr['sid'].'_server')) {
            $arr['server'] = null;
            unset($arr['server']);
        }           
        
        $rs = UserVideoVerifyRecordLog::addByArr($arr);
        $arr['data'] = [];
        $arr['title'] = '';
        
        if(!session()->get(md5($arr['url']).'_'.$arr['sid'].'_server')) {
            session()->put(md5($arr['url']).'_'.$arr['sid'].'_server',1);
        }
        
        if(!session()->get($arr['sid'].'_userAgent')) {
            session()->put($arr['sid'].'_userAgent',1);
        }          
        
        return $rs;
    }
    
    public  function getDefaultLogArr()
    {
       
        return [
            'user_id'=>auth()->user()->id??(session()->get('sess_user_id')??0)
            ,'type'=>'controller'
            ,'sid'=>session()->getId()
            ,'ip'=>$_SERVER['REMOTE_ADDR'] 
            ,'user_agent'=>$_SERVER['HTTP_USER_AGENT']
            ,'server'=>json_encode($_SERVER)
            ,'file'=>json_encode($_FILES)
            ,'request'=>request()->all()
            ,'url'=>$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
        ];
    }
    
    public function video_chat_get_users(Request $request,$not_json_output=false) 
    {
        $users = User::with(['video_verify_memo','self_auth_apply','self_auth_apply.latest_video_modify','video_verify_record'=>function($q){$q->with('admin_user')->orderByDesc('id');}])->where('id', '<>', Auth::id())->where('last_login', '>', Carbon::now()->subDay())
                    ->select('id','name','created_at')
                    ->without('vip')
                    ->where('engroup',2)
                    ->whereHas('self_auth_apply')
                    ->get();
                    
        if($not_json_output) return $users;
        
        return response()->json($users);
    }

    public function user_video_chat_verify_allow_check(Request $request, RealAuthPageService $rap_service) 
    {   
        $is_allow = $rap_service->riseByUserId(Auth::id())->isAllowUseVideoChat() ?? false;
        return response()->json(['is_allow' => $is_allow]);
    }
    
    public function video_chat_memo_save(Request $request) 
    {
        $who = $request->who;
        $verify_user_id = $request->verify_user_id;
        if(!$verify_user_id) $verify_user_id = $request->user_id;
        if(!$verify_user_id) {
            if($request->verify_record_id) {
                $verify_record_id = $request->verify_record_id;
                $user_video_verify_record = UserVideoVerifyRecord::where('id',$verify_record_id)->first();
                if($user_video_verify_record) {
                    $verify_user_id = $user_video_verify_record->user_id;
                
                    if(!$who) {
                        if($request->user_question) $user_video_verify_record->user_question = $request->user_question;
                        if($request->blurryAvatar) $user_video_verify_record->blurryAvatar = $request->blurryAvatar;
                        if($request->blurryLifePhoto) $user_video_verify_record->blurryLifePhoto = $request->blurryLifePhoto;
                        $user_video_verify_record->save();
                    }
                }
            }
            
        }
        if(!$verify_user_id) return;
            
        $verify_user_entry = User::find($verify_user_id);
        
        if(!$verify_user_entry)  return;
        $memo_entry = $verify_user_entry->video_verify_memo;
        
        $memo_data = ['last_edit_admin_id'=>auth()->user()->id];
        if($request->user_question) {
            if(!$memo_entry || $memo_entry->user_question!=$request->user_question) {
                $memo_data['user_question'] = $request->user_question;
                $memo_data['user_question_at'] = Carbon::now();
            }
        }
        
        $isLogAdminBlurry = false;
        
        if($request->blurryAvatar) {
           $memo_data['blurryAvatar'] = $request->blurryAvatar; 
           if(!$memo_entry || $memo_entry->blurryAvatar!=$request->blurryAvatar) {
               $isLogAdminBlurry = true;
           }
           
        }
        if($request->blurryLifePhoto) {
            $memo_data['blurryLifePhoto'] = $request->blurryLifePhoto;
            if(!$memo_entry || $memo_entry->blurryLifePhoto!=$request->blurryLifePhoto) {
                $isLogAdminBlurry = true;
            }
        }
        $rs = false;
        if($memo_entry) {
            $rs = $verify_user_entry->video_verify_memo()->update($memo_data);                    
        }
        else {
          $rs = $verify_user_entry->video_verify_memo()->create($memo_data);                      
        }
        if($rs) {
           if($isLogAdminBlurry) {
                $uCtrl = app(UserController::class);
                $uCtrl->insertAdminActionLog($verify_user_entry->id, '視訊驗證 - 設定照片'); 
           }
           
           return response()->json(['memo' => $verify_user_entry->video_verify_memo()->firstOrNew()]); 
        }
        
 
    }
    
    public function user_question_into_chat_time_save(Request $request) 
    {
        $verify_user_id = $request->verify_user_id;
        if(!$verify_user_id) $verify_user_id = $request->user_id;

        if(!$verify_user_id) return;
            
        $verify_user_entry = User::find($verify_user_id);
        
        if(!$verify_user_entry)  return;
        
        $rs = false;
        if($verify_user_entry->video_verify_memo) {
            $rs = $verify_user_entry->video_verify_memo()->update(['user_question_into_chat_at'=>Carbon::now()]);               
        }
        
        if($rs) {
            return response()->json(['memo' => $verify_user_entry->video_verify_memo()->firstOrNew()]);
        }
 
    }   
    
    public function video_record_verify(Request $request,RealAuthPageService $rap_service)
    {   
        $questions = RealAuthQuestion::get();
        return view('auth.video_record_verify')->with('questions', $questions);
    }

    public function video_record_verify_upload(Request $request,RealAuthPageService $rap_service)
    {
        $path = $request->file('video')->store('video_chat_verify');
        $user_video_verify_record = new UserVideoVerifyRecord;

        $user_video_verify_record->user_video = $path;
        $user_video_verify_record->user_id = auth()->user()->id;
        $user_video_verify_record->admin_id = 0;
        $user_video_verify_record->save();

        BackendUserDetails::upload_finish(auth()->user()->id);

        return ['path'=>$path,'upload'=>'success'];
    }

    public function apply_video_record_verify(Request $request)
    {
        $user_id = auth()->user()->id;
        $backend_user_detail = BackendUserDetails::first_or_new($user_id);
        $backend_user_detail->is_need_video_verify = 1;
        $backend_user_detail->need_video_verify_date = Carbon::now();
        $backend_user_detail->save();
        return ['status'=>'success'];
    }

    public function hint_to_video_record_verify(Request $request)
    {
        $access = $request->access;
        $user = User::where('id', auth()->user()->id)->first();
        if($user->warned_users->video_auth ?? false)
        {
            BackendUserDetails::need_reverify(auth()->user()->id);
        }
        if($access)
        {
            BackendUserDetails::cancel_video_verify(auth()->user()->id);
            return redirect()->route('video_record_verify');
        }
        else
        {
            BackendUserDetails::cancel_video_verify(auth()->user()->id);
            return redirect()->back();
        }
    }

    public function reset_cancel_video_verify(Request $request)
    {
        BackendUserDetails::reset_cancel_video_verify($request->uid);
        return redirect()->back()->with('message', '成功歸零視訊驗證次數');
    }

    public function video_record_verify_reverify(Request $request)
    {
        Log::Info('start_send_reverify');
        $user_id = auth()->user()->id;
        $user = User::where('id', auth()->user()->id)->first();
        $checkCode = str_pad(rand(0, pow(10, 5) - 1), 5, '0', STR_PAD_LEFT);
        $type = '';

        if($user->meta->phone ?? false && $user->meta->phone != '')
        {
            Log::Info('start_mobile_send_reverify');
            $username = '54666024';
            $password = 'zxcvbnm';
            $Mobile = $user->meta->phone;

            $smbody = "您的驗證碼為$checkCode";
            $smbody = mb_convert_encoding($smbody, "BIG5", "UTF-8");
            $Data = array(
                "username" =>$username, //三竹帳號
                "password" => $password, //三竹密碼
                "dstaddr" =>$Mobile, //客戶手機
                "DestName" => '客戶', //對客戶的稱謂 於三竹後台看的時候用的
                "smbody" =>$smbody, //簡訊內容
            );
            $dataString = http_build_query($Data);
            $url = "http://smexpress.mitake.com.tw:9600/SmSendGet.asp?$dataString";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);

            $type = 'mobile';
        }
        elseif($user->advance_auth_email ?? false)
        {
            Log::Info('start_email_send_reverify');
            $receiver = new User;

            $receiver->email = $user->advance_auth_email;
            $receiver->name = $user->name;

            $receiver->notify(new ReverifyAdvAuthUserEmail($checkCode));

            $type = 'email';
        }

        Log::Info($checkCode);

        return ['checkCode' => $checkCode, 'type' => $type];
    }

    public function video_record_verify_reverify_success(Request $request)
    {
        $user_id = auth()->user()->id;
        BackendUserDetails::check_is_reverify($user_id);
        return ['status' => 'success'];
    }

    public function video_verify_record_list(Request $request)
    {
        $user_video_verify_record = UserVideoVerifyRecord::select('user_video_verify_record.*', 'users.name', 'users.email','users.engroup')
            ->leftJoin('users', 'user_video_verify_record.user_id', '=', 'users.id')
            ->where('admin_id', 0)
            ->orderBy('user_video_verify_record.created_at', 'desc')
            ->get()
            ->unique('user_id');

        return view('admin.users.video_verify_record_list', ['user_video_verify_record' => $user_video_verify_record]);
    }

    public function video_verify_record(Request $request)
    {   
        $user_id = $request->user_id;
        $record = UserVideoVerifyRecord::where('user_id', $user_id)->where('admin_id', 0)->orderBy('created_at', 'desc')->get();

        return view('admin.users.video_verify_record', ['record' => $record]);
    }
    
    public function video_verify_record_pass(Request $request)
    {
        BackendUserDetails::reset_video_verify($request->user_id);

        $user = User::where('id', $request->user_id)->first();
        if($user->warned_users->video_auth ?? false)
        {
            $user->warned_users->delete();
        }
        
        $user->video_verify_auth_status = 1;
        $user->save();

        return redirect()->back()->with('message', '已通過');
    }

    public function video_verify_record_fail(Request $request)
    {
        BackendUserDetails::reset_cancel_video_verify($request->user_id);
        BackendUserDetails::upload_record_fail($request->user_id);

        return redirect()->back()->with('message', '已不通過');
    }

    public function restart_video_verify_record(Request $request)
    {
        $user_id = auth()->user()->id;
        BackendUserDetails::restart_video_verify($user_id);

        return redirect()->back()->with('message', '已重新開啟驗證，站方會再跟您約驗證時間，再請注意來訊。');
    }
}
