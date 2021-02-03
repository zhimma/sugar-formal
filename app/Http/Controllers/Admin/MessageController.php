<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MessageController extends \App\Http\Controllers\BaseController {

//    // handle delete message
//    public function deleteBetween($uid, $sid) {
//        Message::deleteBetween($uid, $sid);
//
//        return redirect('dashboard/chat');
//    }
//
//    public function deleteAll($uid) {
//        Message::deleteAll($uid);
//
//        return redirect('dashboard/chat');
//    }
//
//    public function deleteSingle($uid, $sid, $ct_time, $content) {
//        Message::deleteSingle($uid, $sid, $ct_time, $content);
//
//        return redirect('dashboard/chat/' . $sid);
//    }
//
//    public function reportMessage(Request $request){
//        Message::reportMessage($request->id, $request->content);
//        return redirect('dashboard/chat/' . $request->sid)->with('message', '成功檢舉該筆訊息');
//    }
//
//    public function showReportMessagePage($id, $sid) {
//        $user = Auth::user();
//        return view('dashboard.reportMessage')->with('id', $id)->with('sid', $sid)->with('user', $user);
//    }

    public function postChat(Request $request)
    {
        $payload = $request->all();
        Message::post(auth()->id(), $payload['to'], $payload['msg']);
        return back();
    }

    public function chatview(Request $request)
    {
        $user = $request->user();
        if ($user) {
            return view('admin.chat')->with('user', $user);
        }
    }

}
