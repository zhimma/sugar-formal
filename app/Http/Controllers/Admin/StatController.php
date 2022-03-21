<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Models\MemberPic;
use App\Models\Message;
use App\Models\Order;
use App\Models\Reported;
use App\Models\ReportedAvatar;
use App\Models\ReportedPic;
use App\Models\SetAutoBan;
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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StatController extends \App\Http\Controllers\BaseController
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
            $user = User::select('name','engroup')->where('id', $result->member_id)->get()->first();
            if($user == null){
                $results[$key]['name'] = '無資料';
            }
            else{
                $results[$key]['name'] = $user->name;
                if($user->engroup == 1){
                    $results[$key]['engroup'] = '男';
                }
                else{
                    $results[$key]['engroup'] = '女';
                }
            }
            $results[$key]['times'] = date_diff( $start, $end );
        }
        return view('admin.stats.vip', ['results' => $results]);
    }

    public function vipPaid()
    {
        $results = Vip::join('users', 'users.id', '=','member_vip.member_id')->where('free', 0)->where('active', 1)->orderBy('last_login', 'DESC')->get();
        $ecpay = collect();
        $ezpay = collect();
        foreach ($results as $key => $result){
            if($result->engroup == 1){
                $result->engroup = '男';
            }
            else{
                $result->engroup = '女';
            }

            if($result->business_id == '761404'){
                $ezpay->push($result);
            }
            if($result->business_id == '3137610' && !str_contains($result->payment, "one_")){
                $ecpay->push($result);
            }
        }

        return view('admin.stats.vipPaid',
            [
                'ecpay' => $ecpay,
                'ezpay' => $ezpay,
            ]);
    }

    public function vipLog($id)
    {
        $results = VipLog::where('member_id', $id)->get();
        $name = User::where('id', $id)->get()->first()->name;
        $expiry = Vip::where('member_id', $id)->orderBy('created_at', 'asc')->get()->first();
        $order = order::where('user_id', $id)->orderBy('order_date','desc')->get();
        return view('admin.stats.vipLog', [
            'results' => $results,
            'name' => $name,
            'expiry' => isset($expiry)?substr($expiry->expiry, 0, 10):'',
            'order' => $order
        ]);
    }
    public function cronLog(){
        $data = \DB::table('log_vip_crontab')->orderBy('id', 'desc')->paginate(20);
        foreach ($data as &$d){
            if($d->user_id == 0){
                $d->user_id = '無';
            }
            $d->date = substr($d->date, 0, 10);
            $d->content = str_replace("\n", "<br>", $d->content);
            $d->created_at = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $d->created_at)->addHours(14);
        }
        return view('admin.stats.cronLog')->with('data', $data);
    }
    public function datFileLog(){
        $data = \DB::table('log_dat_file')->orderBy('id', 'desc')->paginate(21);
        foreach ($data as &$d){
            if($d->upload_check == 0){
                $d->upload_check = '上傳';
            }
            else{
                $d->upload_check = '檢查';
            }
            $d->created_at = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $d->created_at)->addHours(14);
        }
        return view('admin.stats.datFileLog')->with('data', $data);
    }

    public function set_autoBan(){
        if(request()->ip_expire=='1' && request()->ip) {
            $uq = SetAutoBan::where('type', 'ip');
            if(request()->ip) $uq->where('content', request()->ip);
            $uq->update(['expiry'=>date('Y-m-d H:i:s')]);
            return redirect()->route('stats/set_autoBan');
        }
        SetAutoBan::where('type', 'ip')->where('expiry', '0000-00-00 00:00:00')
			->update(['expiry'=>\Carbon\Carbon::now()->addMonths(1)->format('Y-m-d H:i:s')]);
        $data = SetAutoBan::orderBy('id', 'desc')->get();
        return view('admin.stats.set_autoBan')->with('data', $data);
    }

    public function set_autoBan_add(Request $request){
        if(isset($request->content)){
            $user = User::findByEmail($request->cuz_email_set);
            if($user){
                SetAutoBan::setAutoBanAdd($request->type, $request->content, $request->set_ban, $user->id);
//                DB::table('set_auto_ban')->insert(['type' => $request->type, 'content' => $request->content, 'set_ban' => $request->set_ban, 'cuz_user_set' => $user->id,'expiry'=>$expiry]);
            }else{
                SetAutoBan::setAutoBanAdd($request->type, $request->content, $request->set_ban);
//                DB::table('set_auto_ban')->insert(['type' => $request->type, 'content' => $request->content, 'set_ban' => $request->set_ban,'expiry'=>$expiry]);
            }
        }
        $data = SetAutoBan::orderBy('id', 'desc')->get();
        return view('admin.stats.set_autoBan')->with('data', $data);
    }

    public function set_autoBan_del(Request $request){
        SetAutoBan::where('id', '=', $request->id)->delete();
        $data = SetAutoBan::orderBy('id', 'desc')->get();
        return view('admin.stats.set_autoBan')->with('data', $data);
    }

    /**
     * 1: 男 VIP 人數
     * 2: 30 天內有上線的女  VIP 人數
     * 3: 30 天內男 VIP 發訊總數 / 獲得回應比例
     * 4: 30 天內普通男會員發訊總數 / 獲得回應比例
     * 5: 車馬費邀請總數 / 有回應的比例
     * 6: 一個月內上站男會員總數
     * 7: 優選會員(男)人數
     * 8: 30 天內優選會員(男) 發訊總數/獲得回應比例:
     * 9: 三天內男 VIP 發訊總數/獲得回應比例:
     * 10: 三天內普通(男)會員發訊總數/獲得回應比例:
     * 11: 三天內優選會員(男) 發訊總數/獲得回應比例:
     */
    public function other(Request $request){
        if($request->isMethod("GET")){
            return view('admin.stats.other');
        }
        else{
            $last30days = Carbon::now()->subDays(30);
            switch ($request->number){
                case 1:
                    $maleVip = User::select('id')->where('engroup', 1)->whereIn('id', function($query){
                        $query->select('member_id')
                            ->from(with(new Vip)->getTable())
                            ->where('active', 1)
                            ->where('free', 0)
                            ->where('expiry', '<', Carbon::now());;
                    })->get()->count();
                return $maleVip;
                case 2:
                    $femaleVipLastLoginIn30DaysCount = User::where('engroup', 2)
                        ->where('last_login', '>', $last30days)
                        ->whereIn('id', function($query){
                            $query->select('member_id')
                                ->from(with(new Vip)->getTable())
                                ->where('active', 1);
                        })->get()->count();
                    return $femaleVipLastLoginIn30DaysCount;
                case 3:
                    // 30 天內男 VIP 發訊總數
                    $maleVipMessages = \DB::select('SELECT count(*) as count FROM message m
                        INNER JOIN users u ON m.from_id = u.id
                        INNER JOIN member_vip v ON u.id = v.member_id
                        WHERE v.active = 1 AND u.engroup = 1
                        AND m.created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)');
                    // 獲得回應數
                    $maleVipMessagesReplied =
                        \DB::select('SELECT count(*) as count FROM 
                            (SELECT m.* FROM message m
                            WHERE m.created_at > DATE_SUB(NOW(), INTERVAL 30 DAY) ) m
                        WHERE m.from_id IN (
                                SELECT m.to_id FROM message m
                                INNER JOIN users u ON m.from_id = u.id
                                INNER JOIN member_vip v ON u.id = v.member_id
                                WHERE v.active = 1 AND u.engroup = 1
                                AND m.created_at > DATE_SUB(NOW(), INTERVAL 30 DAY) 
                            ) 
                        AND m.to_id IN (
                                SELECT m.from_id FROM message m
                                INNER JOIN users u ON m.from_id = u.id
                                INNER JOIN member_vip v ON u.id = v.member_id
                                WHERE v.active = 1 AND u.engroup = 1
                                AND m.created_at > DATE_SUB(NOW(), INTERVAL 30 DAY) 
                            ) 
                        AND m.created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)');
                    return $maleVipMessages[0]->count . " / " . $maleVipMessagesReplied[0]->count;
                case 4:
                    $maleVips = User::select('id')->where('engroup', 1)->whereIn('id', function($query){
                        $query->select('member_id')
                            ->from(with(new Vip)->getTable())
                            ->where('active', 1);
                    })->get();
                    $maleVip = array();
                    foreach ($maleVips as $vip){
                        array_push($maleVip, $vip->id);
                    }
                    $maleVip = implode (", ", $maleVip);
                    // 所有普通男會員訊息數
                    $maleNonVipMessages = \DB::select('SELECT count(*) as count FROM message m
                        INNER JOIN users u ON m.from_id = u.id
                        WHERE u.engroup = 1
                        AND u.id NOT IN (' . $maleVip . ')
                        AND m.created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)');
                    // 所有普通男會員訊息數獲得回應數
                    $maleNonVipMessagesReplied =
                        \DB::select('SELECT count(*) as count FROM 
                            (SELECT m.* FROM message m
                            WHERE  m.created_at > DATE_SUB(NOW(), INTERVAL 30 DAY) ) m
                        WHERE from_id IN (
                                SELECT m.to_id FROM message m
                                INNER JOIN users u ON m.from_id = u.id
                                WHERE u.engroup = 1
                                AND u.id NOT IN (' . $maleVip . ')
                                AND m.created_at > DATE_SUB(NOW(), INTERVAL 30 DAY) 
                            ) 
                        AND to_id IN (
                                SELECT m.from_id FROM message m
                                INNER JOIN users u ON m.from_id = u.id
                                WHERE u.engroup = 1
                                AND u.id NOT IN (' . $maleVip . ')
                                AND m.created_at > DATE_SUB(NOW(), INTERVAL 30 DAY) 
                            ) 
                        AND m.created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)');
                    return $maleNonVipMessages[0]->count . " / " . $maleNonVipMessagesReplied[0]->count;
                case 5:
                    $tipsAll = Message::where('content', 'like', '%已經向 您 發動車馬費邀請%')->get();
                    $tipsReplied = 0;
                    foreach ($tipsAll as $tip){
                        $isReplied = \DB::select('SELECT count(*) as count FROM message m WHERE from_id = '. $tip->to_id .' AND m.created_at > "'. $tip->created_at .'"');
                        if($isReplied[0]->count > 0){
                            $tipsReplied++;
                        }
                    }
                    $tipsAllCount = $tipsAll->count();
                    return $tipsAllCount . " / " . $tipsReplied;
                case 6:
                    $maleUserLastLoginIn30Days = User::select('id')->where('engroup', 1)->where('last_login', '>', $last30days)->get()->count();
                    return $maleUserLastLoginIn30Days;
                case 7:
                    $allVips = User::where('engroup', 1)->whereIn('id', function($query){
                        $query->select('member_id')
                            ->from(with(new Vip)->getTable())
                            ->where('active', 1);
                    })->get();
                    $recommendedUsers = 0;
                    foreach ($allVips as $vip){
                        $recommendedData = \App\Services\UserService::checkRecommendedUser($vip);
                        if(isset($recommendedData['description'])){
                            $recommendedUsers++;
                        }
                    }
                    return $recommendedUsers;
                case 8:
                    // 先取得優選會員
                    $allVips = User::where('engroup', 1)->whereIn('id', function($query){
                        $query->select('member_id')
                            ->from(with(new Vip)->getTable())
                            ->where('active', 1);
                    })->get();
                    $recommendedUsersIDs = array();
                    foreach ($allVips as $vip){
                        $recommendedData = \App\Services\UserService::checkRecommendedUser($vip);
                        if(isset($recommendedData['description'])){
                            array_push($recommendedUsersIDs, $vip->id);
                        }
                    }
                    // 再算訊息數及回覆數
                    // 所有男會員訊息數
                    $idString = implode (", ", $recommendedUsersIDs);
                    $recommendedUsersMessages = \DB::select('SELECT count(*) as count FROM message m
                        WHERE m.from_id IN (' . $idString . ')
                        AND m.created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)');
                    // 所有男會員訊息數獲得回應數
                    $recommendedUsersMessagesReplied =
                        \DB::select('SELECT count(*) as count FROM 
                                (SELECT m.* FROM message m
                                WHERE m.to_id IN (' . $idString . ')
                                AND m.created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)) m
                        WHERE from_id IN (
                                SELECT m.to_id FROM message m
                                WHERE m.from_id IN (' . $idString . ')
                                AND m.created_at > DATE_SUB(NOW(), INTERVAL 30 DAY) 
                            ) 
                        AND to_id IN (
                                SELECT m.from_id FROM message m
                                WHERE m.from_id IN (' . $idString . ')
                                AND m.created_at > DATE_SUB(NOW(), INTERVAL 30 DAY) 
                            ) 
                        AND m.created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)');
                    return $recommendedUsersMessages[0]->count . " / " . $recommendedUsersMessagesReplied[0]->count;
                case 9:
                    // 3 天內男 VIP 發訊總數
                    $maleVipMessages = \DB::select('SELECT count(*) as count FROM message m
                            INNER JOIN users u ON m.from_id = u.id
                            INNER JOIN member_vip v ON u.id = v.member_id
                            WHERE v.active = 1 AND u.engroup = 1
                            AND m.created_at > DATE_SUB(NOW(), INTERVAL 3 DAY)');
                    // 獲得回應數
                    $maleVipMessagesReplied =
                        \DB::select('SELECT count(*) as count FROM 
                                (SELECT m.* FROM message m
                                WHERE m.created_at > DATE_SUB(NOW(), INTERVAL 3 DAY) ) m
                            WHERE m.from_id IN (
                                    SELECT m.to_id FROM message m
                                    INNER JOIN users u ON m.from_id = u.id
                                    INNER JOIN member_vip v ON u.id = v.member_id
                                    WHERE v.active = 1 AND u.engroup = 1
                                    AND m.created_at > DATE_SUB(NOW(), INTERVAL 3 DAY) 
                                ) 
                            AND to_id IN (
                                    SELECT m.from_id FROM message m
                                    INNER JOIN users u ON m.from_id = u.id
                                    INNER JOIN member_vip v ON u.id = v.member_id
                                    WHERE v.active = 1 AND u.engroup = 1
                                    AND m.created_at > DATE_SUB(NOW(), INTERVAL 3 DAY) 
                                ) 
                            AND m.created_at > DATE_SUB(NOW(), INTERVAL 3 DAY)');
                    return $maleVipMessages[0]->count . " / " . $maleVipMessagesReplied[0]->count;
                case 10:
                    $maleVips = User::select('id')->where('engroup', 1)->whereIn('id', function($query){
                        $query->select('member_id')
                            ->from(with(new Vip)->getTable())
                            ->where('active', 1);
                    })->get();
                    $maleVip = array();
                    foreach ($maleVips as $vip){
                        array_push($maleVip, $vip->id);
                    }
                    $maleVip = implode (", ", $maleVip);
                    // 所有普通男會員訊息數
                    $maleNonVipMessages = \DB::select('SELECT count(*) as count FROM message m
                        INNER JOIN users u ON m.from_id = u.id
                        WHERE u.engroup = 1
                        AND u.id NOT IN (' . $maleVip . ')
                        AND m.created_at > DATE_SUB(NOW(), INTERVAL 3 DAY)');
                    // 所有普通男會員訊息數獲得回應數
                    $maleNonVipMessagesReplied =
                        \DB::select('SELECT count(*) as count FROM 
                            (SELECT m.* FROM message m
                            WHERE m.created_at > DATE_SUB(NOW(), INTERVAL 3 DAY) ) m
                        WHERE from_id IN (
                                SELECT m.to_id FROM message m
                                INNER JOIN users u ON m.from_id = u.id
                                WHERE u.engroup = 1
                                AND u.id NOT IN (' . $maleVip . ')
                                AND m.created_at > DATE_SUB(NOW(), INTERVAL 3 DAY) 
                            ) 
                        AND to_id IN (
                                SELECT m.from_id FROM message m
                                INNER JOIN users u ON m.from_id = u.id
                                WHERE u.engroup = 1
                                AND u.id NOT IN (' . $maleVip . ')
                                AND m.created_at > DATE_SUB(NOW(), INTERVAL 3 DAY) 
                            ) 
                        AND m.created_at > DATE_SUB(NOW(), INTERVAL 3 DAY)');
                    return $maleNonVipMessages[0]->count . " / " . $maleNonVipMessagesReplied[0]->count;
                case 11:
                    // 先取得優選會員
                    $allVips = User::where('engroup', 1)->whereIn('id', function($query){
                        $query->select('member_id')
                            ->from(with(new Vip)->getTable())
                            ->where('active', 1);
                    })->get();
                    $recommendedUsersIDs = array();
                    foreach ($allVips as $vip){
                        $recommendedData = \App\Services\UserService::checkRecommendedUser($vip);
                        if(isset($recommendedData['description'])){
                            array_push($recommendedUsersIDs, $vip->id);
                        }
                    }
                    // 再算訊息數及回覆數
                    // 所有男會員訊息數
                    $idString = implode (", ", $recommendedUsersIDs);
                    $recommendedUsersMessages = \DB::select('SELECT count(*) as count FROM message m
                        WHERE m.from_id IN (' . $idString . ')
                        AND m.created_at > DATE_SUB(NOW(), INTERVAL 3 DAY)');
                    // 所有男會員訊息數獲得回應數
                    $recommendedUsersMessagesReplied =
                        \DB::select('SELECT count(*) as count FROM 
                                (SELECT m.* FROM message m
                                WHERE m.to_id IN (' . $idString . ')
                                AND m.created_at > DATE_SUB(NOW(), INTERVAL 3 DAY)) m
                        WHERE from_id IN (
                                SELECT m.to_id FROM message m
                                WHERE m.from_id IN (' . $idString . ')
                                AND m.created_at > DATE_SUB(NOW(), INTERVAL 3 DAY) 
                            ) 
                        AND m.created_at > DATE_SUB(NOW(), INTERVAL 3 DAY)');
                    return $recommendedUsersMessages[0]->count . " / " . $recommendedUsersMessagesReplied[0]->count;
            }
        }
    }
}
