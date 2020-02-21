<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Http\Requests;
use Illuminate\Http\Request;

class BoardController extends Controller {

    public function deleteBoard(Request $request) {

        $uid = $request->input('uid');
        $ct_time = $request->input('ct_time');
        $ct_time = $ct_time['date'];
        $content = $request->input('content');

        Board::deleteBoard($uid, $ct_time, $content);

        return redirect('dashboard/board');
    }
}
