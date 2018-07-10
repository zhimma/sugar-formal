<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Http\Requests;
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
            return view('dashboard.chat')->with('user', $user);
        }
    }
}
