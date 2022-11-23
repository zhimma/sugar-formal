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
use App\Services\RealAuthPageService;
use LZCompressor\LZString;
use App\Models\WebrtcSignalData;

class VideoChatController extends Controller
{
    
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
    
    
    public function loadingVideoPage(Request $request)
    {
        if(auth()->user()->id) {
            session()->put('sess_user_id', auth()->user()->id);
        }
        $logArr = $this->getDefaultLogArr();
        $logArr['method'] = 'VideoChatController@loadingVideoPage';
        $logArr['step'] = 'start';
        $logArr['title'] = '';
        $this->logByArr($logArr);        

        $data['from'] = Auth::id();
        $data['type'] = 'loadingVideoPage';

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

    public function unloadingVideoPage(Request $request)
    {
        if(auth()->user()->id) {
            session()->put('sess_user_id', auth()->user()->id);
        }
        $logArr = $this->getDefaultLogArr();
        $logArr['method'] = 'VideoChatController@unloadingVideoPage';
        $logArr['step'] = 'start';
        $logArr['title'] = '';
        $this->logByArr($logArr);        

        $data['from'] = Auth::id();
        $data['type'] = 'unloadingVideoPage';

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

        $logArr['step'] = 'end';
        $logArr['title'] = 'before return view(\'auth.user_video_chat_verify\', [\'users\' => $users]);';
        $logArr['data']['users'] = $users->pluck('id')->all();;
        $this->logByArr($logArr);        
        
        return view('auth.user_video_chat_verify', ['users' => $users]);
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

        $user_video_verify_record = new UserVideoVerifyRecord;
        $user_video_verify_record->user_id = $verify_user_id;       
        $rs = $user_video_verify_record->save();

        $logArr['data']['verify_user_id'] = $verify_user_id;
        $logArr['data']['user_video_verify_record'] = $user_video_verify_record;
        $logArr['data']['rs'] = $rs;
        $logArr['step'] = 'end';
        $logArr['act'] = '$user_video_verify_record->save()';
        $logArr['act_step'] = 'after';
        $logArr['title'] = 'before return response()->json([\'record_id\' => $user_video_verify_record->id]); after $rs = $user_video_verify_record->save();';
        $this->logByArr($logArr); 
        
        return response()->json(['record_id' => $user_video_verify_record->id]);
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

        $verify_record_id = $request->verify_record_id;

        $user_video_verify_record = UserVideoVerifyRecord::where('id',$verify_record_id)->first();

        $logArr['data']['path'] = $path;
        $logArr['data']['who'] = $who;
        $logArr['data']['verify_record_id'] = $verify_record_id;
        $logArr['data']['user_video_verify_record'] = $user_video_verify_record;
        $logArr['step'] = 'ing';
        $this->logByArr($logArr);
        
        if($who == 'partner')
        {
            $user_video_verify_record->user_video = $path;
            $rap_service->riseByUserId($user_video_verify_record->user_id);
        }
        elseif($who == 'user')
        {
            $user_video_verify_record->admin_video = $path;
        }
        if($user_video_verify_record->save())
        {
            $logArr['title'] = $request->user()->id.' $user_video_verify_record->save() success';
            $logArr['act'] = '$user_video_verify_record->save()';
            $logArr['act_step'] = 'success';
            $this->logByArr($logArr);
            if($who == 'partner') {
                $rap_service->saveVideoRecordId($user_video_verify_record->id);
            }

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

    public function video_chat_verify_record_list(Request $request)
    {
        $user_video_verify_record = UserVideoVerifyRecord::select('user_video_verify_record.*', 'users.name', 'users.email')
            ->leftJoin('users', 'user_video_verify_record.user_id', '=', 'users.id')
            ->orderBy('user_video_verify_record.created_at', 'desc')
            ->get()
            ->unique('user_id');

        return view('admin.users.video_chat_verify_record_list', ['user_video_verify_record' => $user_video_verify_record]);
    }

    public function video_chat_verify_record(Request $request)
    {   
        $user_id = $request->user_id;
        $record = UserVideoVerifyRecord::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();

        return view('admin.users.video_chat_verify_record', ['record' => $record]);
    }
    
    public function log_video_chat_process(Request $request) 
    {
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
        $users = User::where('id', '<>', Auth::id())->where('last_login', '>', Carbon::now()->subDay())
                    ->select('id','name')
                    ->without('user_meta','vip')
                    ->where('engroup',2)
                    ->whereHas('self_auth_unchecked_apply')
                    ->get();
                    
        if($not_json_output) return $users;
        
        return response()->json($users);
    }
    
}
