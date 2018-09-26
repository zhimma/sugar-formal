<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Models\MemberPic;
use App\Models\Message;
use App\Models\Reported;
use App\Models\ReportedAvatar;
use App\Models\ReportedPic;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\AdminService;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserInviteRequest;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\AdminAnnounce;
use App\Models\VipLog;
use App\Models\Vip;
use App\Models\SimpleTables\member_vip;
use App\Models\SimpleTables\banned_users;
use App\Notifications\BannedNotification;
use Carbon\Carbon;

class StatController extends Controller
{
    public function __construct(UserService $userService, AdminService $adminService)
    {
        $this->service = $userService;
        $this->admin = $adminService;
    }

    /**
     * Display a listing of the resource searched.
     *
     * @return \Illuminate\Http\Response
     */
    public function vip()
    {
        //SELECT * FROM `member_vip_log` WHERE `member_id` NOT IN
        //(SELECT `member_id` FROM `member_vip_log` WHERE `action` = 0) ORDER BY `created_at` DESC
        $results = VipLog::whereNotIn('member_id', function($query){
            $query->select('member_id')
                ->from(with(new VipLog)->getTable())
                ->where('action', 0);
        })->orderBy('created_at', 'ASC')->get();
        $end = date_create();
        foreach ($results as $key => $result){
            $start  = date_create($result->created_at);
            $results[$key]['name'] = User::select('name')->where('id', $result->member_id)->get()->first();
            if($results[$key]['name'] == null){
                $results[$key]['name'] = '無資料';
            }
            else{
                $results[$key]['name'] = $results[$key]['name']->name;
            }
            $results[$key]['times'] = date_diff( $start, $end );
        }
        return view('admin.stats.vip', ['results' => $results]);
    }
    public function vipLog($id)
    {
        $results = VipLog::where('member_id', $id)->get();
        $name = User::where('id', $id)->get()->first()->name;
        return view('admin.stats.vipLog', [
            'results' => $results,
            'name' => $name]);
    }
}
