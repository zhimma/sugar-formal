<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\VipLogService;
use App\Models\Visited;
use App\Models\Board;
use App\Models\Message;
use App\Models\Reported;
use App\Models\User;
use App\Models\Vip;
use App\Models\Tip;
use App\Models\MemberFav;
use App\Models\Blocked;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\FormFilterRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Models\SimpleTables\banned_users;
use Carbon\Carbon;

class PagesController extends \App\Http\Controllers\BaseController
{
    public function __construct(UserService $userService, VipLogService $logService)
    {
        $this->service = $userService;
        $this->logService = $logService;
    }

    public function showECCancellations(Request $request){
        $now = Carbon::now();
        if(isset($request->yearMonth)){
            $now = Carbon::createFromFormat('Y-m', $request->yearMonth);
        }
        $thisYear = $now->year;
        $thisMonth = $now->month;
        $days = $now->daysInMonth;
        $dates = array();
        for( $i = 1 ; $i < $days + 1 ; ++ $i ) {
            $dates[] = \Carbon\Carbon::createFromDate($thisYear, $thisMonth, $i)->format('Ymd');
        }

        $contents = array();
        foreach ($dates as $d){
            if(\Storage::exists('RP_3137610_'.$d.'.dat')){
                $eachLine = explode("\n", \Storage::get('RP_3137610_'.$d.'.dat'));
                foreach ($eachLine as $line){
                    if(str_contains($line, 'elete')){
                        $line = explode(',', $line);
                        array_push($contents, $line);
                    }
                }
            }
        }
        return view('admin.users.ECPayCancellation',
            ['contents' => $contents,
             'thisYearMonth' => $thisYear.'-'.$thisMonth]);
    }

    public function chat(Request $request, $cid)
    {
        $user = $request->user();
        if ($user) {
            if (isset($cid)) {
                return view('admin.chat')
                ->with('user', $user)
                ->with('to', $this->service->find($cid));
            }
            else {
                return view('admin.chat')
                ->with('user', $user);
            }
        }
    }

    public function board(Request $request){
        $messages = Board::select('board.*', 'users.name', 'users.engroup')->join('users', 'users.id', '=', 'board.member_id');
        if(isset($request->date_start) || isset($request->date_end) || isset($request->keyword)){
            $start = isset($request->date_start) ? $request->date_start : '';
            $end = isset($request->date_end) ? $request->date_end : '';
            $keyword = isset($request->keyword) ? $request->keyword : '';
            $messages = $messages->whereDate('board.created_at', '>=', $start)
                ->whereDate('board.created_at', '<=', $end)
                ->where('post', 'like', '%' . $keyword .'%');
        }
        $messages = $messages->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users.board')->with('messages', $messages)
            ->with('date_start', $request->date_start)
            ->with('date_end', $request->date_end)
            ->with('keyword', $request->keyword);
    }
}
