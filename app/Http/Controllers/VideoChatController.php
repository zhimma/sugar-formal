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
use LZCompressor\LZString;

class VideoChatController extends Controller
{
    public function callUser(Request $request)
    {
        $data['userToCall'] = $request->user_to_call;
        $data['signalData'] = $request->signal_data;
        $data['from'] = Auth::id();
        $data['type'] = 'incomingCall';
        Log::info($data);
        $data = json_encode($data);
        Log::info($data);
        $data = LZString::compress($data);
        Log::info($data);
        broadcast(new StartVideoChat($data))->toOthers();
    }
    public function acceptCall(Request $request)
    {
        $data['signal'] = $request->signal;
        $data['to'] = $request->to;
        $data['type'] = 'callAccepted';
        Log::info($data);
        $data = json_encode($data);
        Log::info($data);
        $data = LZString::compress($data);
        Log::info($data);
        broadcast(new StartVideoChat($data))->toOthers();
    }

    public function video_chat_verify(Request $request)
    {
        $users = User::where('id', '<>', Auth::id())->where('last_login', '>', Carbon::now()->subDay())->get();
        return view('admin.users.video_chat_verify', ['users' => $users]);
    }

    public function user_video_chat_verify(Request $request)
    {
        $users = DB::table('role_user')->leftJoin('users', 'role_user.user_id', '=', 'users.id')->where('users.id', '<>', Auth::id())->get();
        return view('auth.user_video_chat_verify', ['users' => $users]);
    }

    public function videoChatTest(Request $request)
    {
        $users = User::where('id', '15600')->orWhere('id', '15599')->orWhere('id', '12374')->get();
        return view('video-chat-test', ['users' => $users]);
    }

    public function video_chat_verify_upload_init(Request $request)
    {
        $verify_user_id = $request->verify_user_id;
        $user_video_verify_record = new UserVideoVerifyRecord;
        $user_video_verify_record->user_id = $verify_user_id;
        $user_video_verify_record->save();
        return response()->json(['record_id' => $user_video_verify_record->id]);
    }

    public function video_chat_verify_upload(Request $request)
    {
        $path = $request->file('video')->store('video_chat_verify');
        $who = $request->who;
        $verify_record_id = $request->verify_record_id;

        $user_video_verify_record = UserVideoVerifyRecord::where('id',$verify_record_id)->first();
        
        if($who == 'partner')
        {
            $user_video_verify_record->user_video = $path;
        }
        elseif($who == 'user')
        {
            $user_video_verify_record->admin_video = $path;
        }
        $user_video_verify_record->save();

        return ['path'=>$path,'upload'=>'success'];
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
