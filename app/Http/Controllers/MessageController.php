<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Http\Requests;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MessageController extends Controller {

    // handle delete message
    public function deleteBetween($uid, $sid) {
        Message::deleteBetween($uid, $sid);

        return redirect('dashboard/chat');
    }

    public function deleteAll($uid) {
        Message::deleteAll($uid);

        return redirect('dashboard/chat');
    }

    public function deleteSingle($uid, $sid, $ct_time, $content) {
        Message::deleteSingle($uid, $sid, $ct_time, $content);

        return redirect('dashboard/chat/' . $sid);
    }

    public function reportMessage(Request $request){
        Message::reportMessage($request->id, $request->content);
        return redirect('dashboard/chat/' . $request->sid)->with('message', '成功檢舉該筆訊息');
    }

    public function showReportMessagePage($id, $sid) {
        $user = Auth::user();
        return view('dashboard.reportMessage')->with('id', $id)->with('sid', $sid)->with('user', $user);
    }

    public function postChat(Request $request)
    {
        $payload = $request->all();
        if(!isset($payload['msg'])){
            return back()->withErrors(['請勿僅輸入空白！']);
        }
        Message::post(auth()->id(), $payload['to'], $payload['msg']);
        return back();
    }

    public function chatview(Request $request)
    {
        $user = $request->user();
        if ($user) {
            return view('dashboard.chat')->with('user', $user);
        }
    }

    public function disableNotice(Request $request)
    {
        $user_id = $request->id;
        $user = User::select('id', 'noticeRead')->where('id', $user_id)->get()->first();
        $user->noticeRead = 1;
        if ($user->save()) {
            return response()->json(array(
                'status' => 1,
                'msg' => 'ok',
            ), 200);
        } else {
            return response()->json(array(
                'status' => 2,
                'msg' => 'fail',
            ), 500);
        }
    }

}
