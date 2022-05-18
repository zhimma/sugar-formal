<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Events\StartVideoChat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VideoChatController extends Controller
{
    public function callUser(Request $request)
    {
        $data['userToCall'] = $request->user_to_call;
        $data['signalData'] = $request->signal_data;
        $data['from'] = Auth::id();
        $data['type'] = 'incomingCall';
        broadcast(new StartVideoChat($data))->toOthers();
    }
    public function acceptCall(Request $request)
    {
        $data['signal'] = $request->signal;
        $data['to'] = $request->to;
        $data['type'] = 'callAccepted';
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
}
