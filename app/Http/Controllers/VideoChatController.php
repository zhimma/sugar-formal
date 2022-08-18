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
use App\Services\RealAuthPageService;
use LZCompressor\LZString;
use App\Models\WebrtcSignalData;

class VideoChatController extends Controller
{
    public function callUser(Request $request)
    {
        Log::Info('callUser start：');
        Log::Info('$_SERVER=');
        Log::Info($_SERVER);
        Log::Info('$request->all()=');
        Log::Info($request->all());
         
        $signal_data = json_encode($request->signal_data);

        $save_data = new WebrtcSignalData;
        $save_data->signal_data = $signal_data;
        $save_data->save();

        $data['userToCall'] = $request->user_to_call;
        $data['signalData'] = $save_data->id;
        $data['from'] = Auth::id();
        $data['type'] = 'incomingCall';
        Log::Info('callUser data');
        Log::Info($data);
        Log::Info('callUser end。');
        broadcast(new StartVideoChat($data))->toOthers();
    }

    public function acceptCall(Request $request)
    {
        Log::Info('acceptCall start：');
        Log::Info('$_SERVER=');
        Log::Info($_SERVER);
        Log::Info('$request->all()=');
        Log::Info($request->all());
        $signal = json_encode($request->signal);

        $save_data = new WebrtcSignalData;
        $save_data->signal_data = $signal;
        $save_data->save();
        
        $data['signal'] = $save_data->id;
        $data['to'] = $request->to;
        $data['type'] = 'callAccepted';
        Log::Info('acceptCall data');
        Log::Info($data);
        Log::Info('acceptCall end。');
        broadcast(new StartVideoChat($data))->toOthers();
    }

    public function receiveCallUserSignalData(Request $request)
    {
        Log::Info('receiveCallUserSignalData start：');
        Log::Info('$_SERVER=');
        Log::Info($_SERVER);
        Log::Info('$request->all()=');
        Log::Info($request->all());
        $id = $request->signal_data_id;
        $signal_data = WebrtcSignalData::where('id', $id)->first()->signal_data;
        
        Log::Info('$signal_data=');
        Log::Info($signal_data);
        Log::Info('receiveCallUserSignalData end。');
        return $signal_data;
    }

    public function receiveAcceptCallSignalData(Request $request)
    {
        Log::Info('receiveAcceptCallSignalData start：');
        Log::Info('$_SERVER=');
        Log::Info($_SERVER);
        Log::Info('$request->all()=');
        Log::Info($request->all());
        $id = $request->signal_data_id;
        $signal_data = WebrtcSignalData::where('id', $id)->first()->signal_data;
        
        Log::Info('$signal_data=');
        Log::Info($signal_data);
        Log::Info('receiveAcceptCallSignalData end。');
        return $signal_data;
    }

    public function video_chat_verify(Request $request)
    {
        Log::Info('video_chat_verify start：');
        Log::Info('$_SERVER=');
        Log::Info($_SERVER);
        Log::Info('$request->all()=');
        Log::Info($request->all());
        $users = User::where('id', '<>', Auth::id())->where('last_login', '>', Carbon::now()->subDay())
                    ->whereHas('self_auth_unchecked_apply')
                    ->get();
        Log::Info('video_chat_verify end。');
        return view('admin.users.video_chat_verify', ['users' => $users]);
    }

    public function user_video_chat_verify(Request $request,RealAuthPageService $rap_service)
    {
        Log::Info('user_video_chat_verify start：');
        Log::Info('$_SERVER=');
        Log::Info($_SERVER);
        Log::Info('$request->all()=');
        Log::Info($request->all());
        if(!$rap_service->riseByUserId(Auth::id())->isAllowUseVideoChat()) {
            if($request->server('HTTP_REFERER'))
                return redirect($request->server('HTTP_REFERER'));
            
            return redirect('/dashboard/personalPage');
        }
        
        $users = DB::table('role_user')->leftJoin('users', 'role_user.user_id', '=', 'users.id')->where('users.id', '<>', Auth::id())->get();
        Log::Info('user_video_chat_verify end。');
        return view('auth.user_video_chat_verify', ['users' => $users]);
    }

    public function videoChatTest(Request $request)
    {
        Log::Info('videoChatTest start：');
        Log::Info('$_SERVER=');
        Log::Info($_SERVER);
        Log::Info('$request->all()=');
        Log::Info($request->all());
        $users = User::where('id', '15600')->orWhere('id', '15599')->orWhere('id', '12374')->get();
        Log::Info('videoChatTest end。');
        return view('video-chat-test', ['users' => $users]);
    }

    public function video_chat_verify_upload_init(Request $request)
    {
        Log::Info(' video_chat_verify_upload_init start：');
        Log::Info('$_SERVER=');
        Log::Info($_SERVER);
        Log::Info('$request->all()=');
        Log::Info($request->all());
        $verify_user_id = $request->verify_user_id;
        Log::Info('$verify_user_id='.$verify_user_id);
        $user_video_verify_record = new UserVideoVerifyRecord;
        $user_video_verify_record->user_id = $verify_user_id;
        $rs = $user_video_verify_record->save();
        Log::Info('$user_video_verify_record=');
        Log::Info($user_video_verify_record);
        Log::Info('user_video_verify_record save rs = ');
        Log::Info($rs);
        Log::Info(' video_chat_verify_upload_init end。');
        return response()->json(['record_id' => $user_video_verify_record->id]);
    }

    public function video_chat_verify_upload(Request $request,RealAuthPageService $rap_service)
    {
        Log::Info('video_chat_verify_upload start：');
        Log::Info('$_SERVER=');
        Log::Info($_SERVER);
        Log::Info('$request->all()=');
        Log::Info($request->all());
        Log::Info(' $_FILES=');
        Log::Info( $_FILES);         
        $path = $request->file('video')->store('video_chat_verify');
        Log::Info(' $path='.$path);
        $who = $request->who;
        Log::Info(' $who='.$who);
        $verify_record_id = $request->verify_record_id;
        Log::Info(' $verify_record_id='.$verify_record_id);
        $user_video_verify_record = UserVideoVerifyRecord::where('id',$verify_record_id)->first();
        Log::Info(' $user_video_verify_record=');
        Log::Info( $user_video_verify_record); 
        
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
            Log::Info(' $user_video_verify_record->save() success');
            if($who == 'partner') {
                $rap_service->saveVideoRecordId($user_video_verify_record->id);
            }
            Log::Info('video_chat_verify_upload $user_video_verify_record->save() success return end。');
            return ['path'=>$path,'upload'=>'success'];
        }

        Log::Info('video_chat_verify_upload  end。');
    }

    public function video_chat_verify_record_list(Request $request)
    {
        $user_video_verify_record = UserVideoVerifyRecord::select('user_video_verify_record.*', 'users.name', 'users.email')
            ->leftJoin('users', 'user_video_verify_record.user_id', '=', 'users.id')
            ->orderBy('user_video_verify_record.created_at', 'desc')
            ->get()
            ->unique('user_id');
        Log::Info($user_video_verify_record);
        return view('admin.users.video_chat_verify_record_list', ['user_video_verify_record' => $user_video_verify_record]);
    }

    public function video_chat_verify_record(Request $request)
    {   
        $user_id = $request->user_id;
        $record = UserVideoVerifyRecord::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();

        return view('admin.users.video_chat_verify_record', ['record' => $record]);
    }
    
}
