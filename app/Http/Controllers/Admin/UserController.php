<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Reported\ReportedIsWriteRequest;
use App\Http\Requests\UserInviteRequest;
use App\Http\Requests\UserMessageCheck\IndexRequest;
use App\Models\AccountStatusLog;
use App\Models\AdminActionLog;
use App\Models\AdminAnnounce;
use App\Models\AdminCommonText;
use App\Models\AnonymousChat;
use App\Models\AnonymousChatForbid;
use App\Models\AnonymousChatReport;
use App\Models\AnonymousEvaluationChat;
use App\Models\AnonymousEvaluationMessage;
use App\Models\BackendUserDetails;
use App\Models\BannedUsersImplicitly;
use App\Models\BasicSetting;
use App\Models\Blocked;
use App\Models\Board;
use App\Models\CheckPointUser;
use App\Models\ComeFromAdvertise;
use App\Models\DataForFilterByInfo;
use App\Models\DataForFilterByInfoIgnores;
use App\Models\EssenceStatisticsLog;
use App\Models\Evaluation;
use App\Models\EvaluationPic;
use App\Models\ExpectedBanningUsers;
use App\Models\Features;
use App\Models\Forum;
use App\Models\GreetingRateCalculation;
use App\Models\hideOnlineData;
use App\Models\ImagesCompareEncode;
use App\Models\IsBannedLog;
use App\Models\IsWarnedLog;
use App\Models\lineNotifyChatSet;
use App\Models\LogAdvAuthApi;
use App\Models\LogUserLogin;
use App\Models\MasterWords;
use App\Models\MemberPic;
use App\Models\Message;
use App\Models\MessageBoard;
use App\Models\MessageRoomUserXref;
use App\Models\Msglib;
use App\Models\ObserveUser;
use App\Models\Order;
use App\Models\Posts;
use App\Models\Reported;
use App\Models\ReportedAvatar;
use App\Models\ReportedPic;
use App\Models\RoleUser;
use App\Models\SetAutoBan;
use App\Models\SimilarImages;
use App\Models\SimpleTables\banned_users;
use App\Models\SimpleTables\member_vip;
use App\Models\SimpleTables\users;
use App\Models\SimpleTables\warned_users;
use App\Models\SpecialIndustriesTestAnswer;
use App\Models\StayOnlineRecord;
use App\Models\StayOnlineRecordPageName;
use App\Models\SuspiciousUser;
use App\Models\Tip;
use App\Models\TrackUser;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\UserRecord;
use App\Models\UserRemarksLog;
use App\Models\UserVideoVerifyRecord;
use App\Models\ValueAddedService;
use App\Models\ValueAddedServiceLog;
use App\Models\Vip;
use App\Models\VipExpiryLog;
use App\Models\VipLog;
use App\Models\Visited;
use App\Models\VvipApplication;
use App\Models\VvipInfo;
use App\Models\VvipProveImg;
use App\Models\VvipSelectionReward;
use App\Models\VvipSelectionRewardApply;
use App\Observer\BadUserCommon;
use App\Services\AdminService;
use App\Services\EnvironmentService;
use App\Services\FaqService;
use App\Services\FaqUserService;
use App\Services\ImagesCompareService;
use App\Services\MessageService;
use App\Services\RealAuthAdminService;
use App\Services\ShortMessageService;
use App\Services\UserService;
use App\Services\VipLogService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Session;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Message_newController;
use App\Models\SuspiciousUserListTable;

class UserController extends \App\Http\Controllers\BaseController
{
    public function __construct(UserService $userService, AdminService $adminService, RealAuthAdminService $raa_service, MessageService $messageService, FaqUserService $faqUserService, VipLogService $logService)
    {
        $this->service = $userService;
        $this->admin = $adminService;
        $this->raa_service = $raa_service->riseByUserService($this->service);
        $this->messageService = $messageService;
        $this->faqUserService = $faqUserService;
        $this->logService = $logService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$users = $this->service->all();

        return view('admin.users.index');
    }

    /**
     * Display a listing of the resource searched.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        if (!$request->search) {
            return redirect('admin/users/search');
        }

        $users = User::select('id', 'name', 'email', 'engroup')
            ->where('email', 'like', '%' . $request->search . '%')
            ->get();
        foreach ($users as $user) {
            $isVip = $user->isVip();
            if ($user->engroup == 1) {

                $user['gender_ch'] = '男';
            } else {
                $user['gender_ch'] = '女';
            }
            if ($isVip == 1) {
                $user['isVip'] = true;
            } else {
                $user['isVip'] = false;
            }


            if (member_vip::select("order_id")->where('member_id', $user->id)->get()->first()) {
                $user['vip_order_id'] = member_vip::select("order_id")
                    ->where('member_id', $user->id)
                    ->get()->first()->order_id;
            }
            $user['vip_data'] = Vip::select('id', 'free', 'expiry', 'payment_method', 'created_at', 'updated_at')
                ->where('member_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get()->first();
            if (VipLog::select("updated_at")->where('member_id', $user->id)->orderBy('updated_at', 'desc')->get()->first()) {
                $user['updated_at'] = VipLog::select("updated_at")->where('member_id', $user->id)->orderBy('updated_at', 'desc')->get()->first()->updated_at;
            }

            if($user->isVVIP()){
                $user['isVVIP'] = true;
            }else{
                $user['isVVIP'] = false;
            }
            $user['vvip_data'] = ValueAddedService::select('active', 'expiry', 'payment', 'created_at', 'updated_at', 'order_id')
                ->where('member_id', $user->id)
                ->where('active', 1)
                ->where('service_name', 'VVIP')
                ->where(function($query) {
                    $query->where('expiry', '0000-00-00 00:00:00')
                        ->orWhere('expiry', '>=', Carbon::now());}
                )->orderBy('created_at', 'desc')->first();

            if(ValueAddedServiceLog::select("updated_at")->where('member_id', $user->id)->where('service_name', 'VVIP')->orderBy('updated_at', 'desc')->get()->first()){
                $user['vvip_updated_at'] = ValueAddedServiceLog::select("updated_at")->where('member_id', $user->id)->where('service_name', 'VVIP')->orderBy('updated_at', 'desc')->get()->first()->updated_at;
            }
        }
        return view('admin.users.index')
            ->with('users', $users);
    }

    /**
     * Toggle the gender of a specific member.
     *
     * @return \Illuminate\Http\Response
     */
    public function toggleGender(Request $request)
    {
        $user = User::select('id', 'engroup', 'email')
            ->where('id', $request->user_id)
            ->get()->first();
        if ($request->gender_now == 1) {
            $user->engroup = '2';
        } else {
            $user->engroup = '1';
        }
        $user->save();

        //新增Admin操作log
        $this->insertAdminActionLog($request->user_id, $request->gender_now == 1 ? '變更性別(男->女)' : '變更性別(女->男)');

        // 操作紀錄
        \App\Models\AdminPicturesSimilarActionLog::insert([
            'operator_id'   => Auth::user()->id,
            'operator_role' => Auth::user()->roles->first()->id,
            'target_id'     => $request->user_id,
            'act'           => $request->gender_now == 1 ? '變更性別(男->女)' : '變更性別(女->男)',
            'reason'        => $request->reason,
            'days'          => $request->days,
            'ip'            => $request->ip(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        if (isset($request->page)) {
            switch ($request->page) {
                case 'advInfo':
                    return redirect('admin/users/advInfo/' . $request->user_id);
                default:
                    return back()->with('message', '成功變更性別');
                    break;
            }
        } else {
            return view('admin.users.success')
                ->with('email', $user->email);
        }
    }

    public function insertAdminActionLog($targetAccountID, $action, $action_id = 0)
    {
        AdminActionLog::insert_log(Auth::user()->id, array_get($_SERVER, 'REMOTE_ADDR'), $targetAccountID, $action, $action_id = 0);
    }

    public function TogglerIsReal(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        if ($request->is_real == 1) {
            $user->is_real = 0;
        } else {
            $user->is_real = 1;
        }
        $user->save();

        return redirect('admin/users/advInfo/' . $request->user_id);
    }

    /**
     * Toggle the gender of a specific member.
     *
     * @return \Illuminate\Http\Response
     */
    public function toggleVIP(Request $request)
    {
        if ($request->isVip == 1) {
            //關閉VIP權限
            Vip::where('member_id', $request->user_id)->get()->first()->removeVIP();
            $setVip = 0;
        } else {
            //提供VIP權限
            $setVip = 1;
            $tmpsql = Vip::select('expiry')->where('member_id', $request->user_id)->get()->first();
            if (isset($tmpsql)) {
                $user = Vip::select('member_id', 'active')
                    ->where('member_id', $request->user_id)
                    ->update(array(
                        'active' => $setVip,
                        'business_id' => 'BackendFree',
                        'order_id' => 'BackendFree',
                        'expiry' => '0000-00-00 00:00:00',
                        'free' => 0
                    ));
            } else {
                //從來都沒VIP資料的
                $vip_user = new Vip;
                $vip_user->member_id = $request->user_id;
                $vip_user->active = $setVip;
                $vip_user->created_at = Carbon::now()->toDateTimeString();
                $vip_user->save();
            }
        }
        //新增Admin操作log
        $this->insertAdminActionLog($request->user_id, $request->isVip == 1 ? '取消VIP' : '升級VIP');

        VipLog::addToLog($request->user_id, $setVip == 0 ? 'manual_cancel' : 'manual_upgrade', 'Manual Setting', $setVip, 0);
        $user = User::select('id', 'email', 'name')
            ->where('id', $request->user_id)
            ->get()->first();
        if (isset($request->page)) {
            switch ($request->page) {
                case 'advInfo':
                    return redirect('admin/users/advInfo/' . $request->user_id);
                    break;
                case 'back':
                    return back()->with('message', '成功解除' . $user->name . '的權限');
                default:
                    return view('admin.users.success')
                        ->with('email', $user->email);
                    break;
            }
        } else {
            return view('admin.users.success')
                ->with('email', $user->email);
        }
    }

    //隱藏功能
    public function toggleHidden(Request $request)
    {
        // $user = \View::shared('user');   這句是錯，這句是抓當下登入的人，也就是你
        $user = User::find($request->user_id);
        $isHidden = $user->valueAddedServiceStatus('hideOnline');
        if ($isHidden == 1) {
            //關閉隱藏權限
            $sethideOnline = 0;
            $user = ValueAddedService::select('member_id', 'active')
                ->where('member_id', $request->user_id)
                ->where('service_name', 'hideOnline')
                ->update(array(
                    'active' => $sethideOnline,
                    'expiry' => '0000-00-00 00:00:00',
                    'business_id' => '',
                    'order_id' => ''
                ));
            User::where('id', $request->user_id)->update(['is_hide_online' => 0]);
        } else {
            //提供隱藏權限
            $sethideOnline = 1;
            $tmpsql = ValueAddedService::select('expiry')->where('member_id', $request->user_id)->where('service_name', 'hideOnline')->get()->first();
            if (isset($tmpsql)) {
                $user = ValueAddedService::select('member_id', 'active')
                    ->where('member_id', $request->user_id)
                    ->where('service_name', 'hideOnline')
                    ->update(array(
                        'active' => $sethideOnline,
                        'business_id' => 'BackendFree',
                        'order_id' => 'BackendFree',
                        'expiry' => '0000-00-00 00:00:00'
                        //'free' => 0
                    ));
            } else {
                //從來都沒隱藏資料的
                $ValueAddedService = new ValueAddedService;
                $ValueAddedService->member_id = $request->user_id;
                $ValueAddedService->service_name = 'hideOnline';
                $ValueAddedService->active = $sethideOnline;
                $ValueAddedService->business_id = 'BackendFree';
                $ValueAddedService->order_id = 'BackendFree';
                $ValueAddedService->expiry = '0000-00-00 00:00:00';
                $ValueAddedService->save();
            }
            ValueAddedService::addHideOnlineData($request->user_id);
            $checkHideOnlineData = hideOnlineData::where('user_id', $request->user_id)->where('deleted_at', null)->get()->first();
            User::where('id', $request->user_id)->update(['is_hide_online' => 1, 'hide_online_time' => $checkHideOnlineData->login_time]);
        }
        // return view('admin.users.advInfo')
        // ->with('isHidden',$isHidden);
        //新增Admin操作log

        $this->insertAdminActionLog($request->user_id, $request->isHidden == 0 ? '取消隱藏' : '升級隱藏');

        VipLog::addToLog($request->user_id, $sethideOnline == 0 ? 'manual_cancel' : 'manual_upgrade', 'Manual Setting', $sethideOnline, 0);
        $user = User::select('id', 'email', 'name')
            ->where('id', $request->user_id)
            ->get()->first();

        if (isset($request->page)) {
            switch ($request->page) {
                case 'advInfo':
                    return redirect('admin/users/advInfo/' . $request->user_id);
                    break;
                case 'back':
                    return back()->with('message', '成功解除' . $user->name . '的權限');
                default:
                    return view('admin.users.success')
                        ->with('email', $user->email);
                    break;
            }
        } else {
            return view('admin.users.success')
                ->with('email', $user->email);
        }
    }

    public function toggleUserBlock_simple($id)
    {
        $userBanned = banned_users::where('member_id', $id)
            ->orderBy('created_at', 'desc')
            ->get()->first();
        if ($userBanned) {
            $checkLog = DB::table('is_banned_log')->where('user_id', $userBanned->member_id)->where('created_at', $userBanned->created_at)->first();
            if (!$checkLog) {
                //寫入log
                DB::table('is_banned_log')->insert(['user_id' => $userBanned->member_id, 'reason' => $userBanned->reason, 'expire_date' => $userBanned->expire_date, 'vip_pass' => $userBanned->vip_pass, 'adv_auth' => $userBanned->adv_auth, 'created_at' => $userBanned->created_at]);
            }
            $userBanned->delete();

            return view('admin.users.success_only')->with('message', '成功解除封鎖使用者');
        } else {
            $userBanned = new banned_users;
            $userBanned->member_id = $id;
            $userBanned->save();
            //寫入log
            DB::table('is_banned_log')->insert(['user_id' => $id, 'created_at' => Carbon::now()]);

            return view('admin.users.success_only')->with('message', '成功封鎖使用者');
        }
    }

    public function toggleUserWarned(Request $request)
    {
        $now_time = Carbon::now();
        ini_set('max_execution_time', -1);
        $userWarned = warned_users::where('member_id', $request->user_id)
            ->orderBy('created_at', 'desc')
            ->get()->first();

        //勾選加入常用列表後新增
        if ($request->addreason) {
            if (DB::table('reason_list')->where([['type', 'warned'], ['content', $request->reason]])->first() == null) {
                DB::table('reason_list')->insert(['type' => 'warned', 'content' => $request->reason]);
            }
        }

        //輸入新增自動封鎖關鍵字後新增 警示
        if (!empty($request->addautoban)) {
            foreach ($request->addautoban as $value) {
                if (!empty($value)) {
                    if (SetAutoBan::where([['type', 'allcheck'], ['content', $value], ['set_ban', '3']])->first() == null) {

                        //如果選項不是永久時新增天數
                        if ($request->days != 'X') {
                            SetAutoBan::insert(['type' => 'allcheck', 'content' => $value, 'set_ban' => '3', 'cuz_user_set' => $request->user_id, 'expired_days' => $request->days, 'created_at' => $now_time, 'updated_at' => $now_time]);
                        } else {
                            SetAutoBan::insert(['type' => 'allcheck', 'content' => $value, 'set_ban' => '3', 'cuz_user_set' => $request->user_id, 'created_at' => $now_time, 'updated_at' => $now_time]);
                        }
                    }
                }
            }
        }

        if ($userWarned) {
            $checkLog = DB::table('is_warned_log')->where('user_id', $userWarned->member_id)->where('created_at', $userWarned->created_at)->get()->first();
            if (!$checkLog) {
                //寫入log
                DB::table('is_warned_log')->insert(['user_id' => $userWarned->member_id, 'reason' => $userWarned->reason, 'expire_date' => $userWarned->expire_date, 'created_at' => $userWarned->created_at, 'vip_pass' => $userWarned->vip_pass, 'adv_auth' => $userWarned->adv_auth, 'video_auth' => $userWarned->video_auth]);
            }
            $userWarned->delete();
        }
        $userWarned = new warned_users;
        $userWarned->member_id = $request->user_id;
        $userWarned->vip_pass = $request->vip_pass;
        $userWarned->adv_auth = $request->adv_auth;
        $userWarned->video_auth = $request->video_auth ?? 0;
        Log::Info($request->video_auth);
        
        if ($request->days != 'X') {
            $userWarned->expire_date = $now_time->copy()->addDays($request->days);
        }
        $userWarned->reason = $request->reason;

        if (!empty($request->reason)) {
            $userWarned->reason = $request->reason;
        }
        $userWarned->save();
        //先完成警示才能快照warned id
        if(($request->video_auth ?? 0) == 1)
        {
            BackendUserDetails::apply_video_verify($request->user_id);
        }        
        
        BadUserCommon::addRemindMsgFromBadId($request->user_id);
        //寫入log
        DB::table('is_warned_log')->insert(['user_id' => $request->user_id, 'reason' => $request->reason, 'vip_pass' => $request->vip_pass, 'adv_auth' => $request->adv_auth, 'video_auth' => $request->video_auth, 'created_at' => $now_time, 'expire_date' => $now_time->copy()->addDays($request->days)]);
        //新增Admin操作log
        $this->insertAdminActionLog($request->user_id, '站方警示');

        $warned_user = User::findById($request->user_id);
        $line_notify_user_list = lineNotifyChatSet::select('line_notify_chat_set.user_id')
            ->selectRaw('users.line_notify_token')
            ->leftJoin('line_notify_chat', 'line_notify_chat.id', 'line_notify_chat_set.line_notify_chat_id')
            ->leftJoin('users', 'users.id', 'line_notify_chat_set.user_id')
            ->where('line_notify_chat.active', 1)
            ->where('line_notify_chat_set.line_notify_chat_id', 7) //警示會員
            ->where('line_notify_chat_set.deleted_at', null)
            ->whereNotNull('users.line_notify_token')
            ->groupBy('line_notify_chat_set.user_id')->get();
        foreach ($line_notify_user_list as $notify_user) {
            $has_message = Message::where([['to_id', $warned_user->id], ['from_id', $notify_user->user_id]])->orWhere([['to_id', $notify_user->user_id], ['from_id', $warned_user->id]])->get()->count();
            if ($notify_user->line_notify_token != null && $has_message) {
                $url = url('/dashboard/chat2');
                //send notify
                $message = '與您通訊的 ' . $warned_user->name . ' 已經被站方警示。對話記錄將移到警示會員信件夾，請您再去檢查，如果您們已經交換聯絡方式，請多加注意。 ' . $url;
                User::sendLineNotify($notify_user->line_notify_token, $message);
            }
        }

        if (isset($request->page)) {
            switch ($request->page) {
                case 'advInfo':
                    return redirect('admin/users/advInfo/' . $request->user_id);
                case 'noRedirect':
                    echo json_encode(array('code' => '200', 'status' => 'success'));
                    break;
                default:
                    return redirect($request->page);
                    break;
            }
        } else {
            return back()->with('message', '成功加入站方警示');
        }
        //        }


    }

    public function warnBudget(Request $request)
    {
        $reason = '';
        $days = 0;
        if ($request->type == 'month_budget') {
            $reason = '每月預算不實';
            $warn_frequency = DB::table('is_warned_log')->where('user_id', $request->user_id)->where('reason', $reason)->count();
            if ($warn_frequency == 0) {
                $days = 7;
            } else if ($warn_frequency == 1) {
                $days = 20;
            } else {
                $days = 60;
            }
        } else if ($request->type == 'transport_fare') {
            $reason = '車馬費預算不實';
            $warn_frequency = DB::table('is_warned_log')->where('user_id', $request->user_id)->where('reason', $reason)->count();
            if ($warn_frequency == 0) {
                $days = 7;
            } else if ($warn_frequency == 1) {
                $days = 20;
            } else {
                $days = 60;
            }
        }
        $expire_date = Carbon::now()->addDays($days);

        ini_set('max_execution_time', -1);
        $userWarned = warned_users::where('member_id', $request->user_id)
            ->orderBy('created_at', 'desc')
            ->get()->first();

        if ($userWarned) {
            $checkLog = DB::table('is_warned_log')->where('user_id', $userWarned->member_id)->where('created_at', $userWarned->created_at)->get()->first();
            if (!$checkLog) {
                //寫入log
                DB::table('is_warned_log')->insert(['user_id' => $userWarned->member_id, 'reason' => $userWarned->reason, 'created_at' => $userWarned->created_at, 'vip_pass' => $userWarned->vip_pass, 'adv_auth' => $userWarned->adv_auth, 'video_auth' => $userWarned->video_auth]);
            }
            $userWarned->delete();
        }


        $userWarned = new warned_users;
        $userWarned->member_id = $request->user_id;
        $userWarned->expire_date = $expire_date;
        $userWarned->type = $request->type;
        $userWarned->reason = $reason;
        $userWarned->save();


        BadUserCommon::addRemindMsgFromBadId($request->user_id);
        //寫入log
        DB::table('is_warned_log')->insert(['user_id' => $request->user_id, 'reason' => $reason, 'expire_date' => $expire_date, 'created_at' => Carbon::now()]);
        //新增Admin操作log
        $this->insertAdminActionLog($request->user_id, '站方警示');

        $warned_user = User::findById($request->user_id);
        $line_notify_user_list = lineNotifyChatSet::select('line_notify_chat_set.user_id')
            ->selectRaw('users.line_notify_token')
            ->leftJoin('line_notify_chat', 'line_notify_chat.id', 'line_notify_chat_set.line_notify_chat_id')
            ->leftJoin('users', 'users.id', 'line_notify_chat_set.user_id')
            ->where('line_notify_chat.active', 1)
            ->where('line_notify_chat_set.line_notify_chat_id', 7) //警示會員
            ->where('line_notify_chat_set.deleted_at', null)
            ->whereNotNull('users.line_notify_token')
            ->groupBy('line_notify_chat_set.user_id')->get();
        foreach ($line_notify_user_list as $notify_user) {
            $has_message = Message::where([['to_id', $warned_user->id], ['from_id', $notify_user->user_id]])->orWhere([['to_id', $notify_user->user_id], ['from_id', $warned_user->id]])->get()->count();
            if ($notify_user->line_notify_token != null && $has_message) {
                $url = url('/dashboard/chat2');
                //send notify
                $message = '與您通訊的 ' . $warned_user->name . ' 已經被站方警示。對話記錄將移到警示會員信件夾，請您再去檢查，如果您們已經交換聯絡方式，請多加注意。 ' . $url;
                User::sendLineNotify($notify_user->line_notify_token, $message);
            }
        }
    }

    //預算及車馬費警示警示

    public function closeAccountReason(Request $request)
    {
        $getAccount =  AccountStatusLog::leftJoin('users', 'users.id', '=', 'account_status_log.user_id')->groupBy('user_id');
        if (!empty($request->get('account'))) {
            $getAccount->where('users.email', $request->get('account'));
        }
        $getAccount = $getAccount->get();

        $listAccount = array();
        foreach ($getAccount as $key => $list) {
            $data = AccountStatusLog::leftJoin('users', 'users.id', '=', 'account_status_log.user_id')
                ->selectRaw('account_status_log.*, users.id, users.name, users.email, users.engroup, users.accountStatus');

            if (!empty($request->get('date_start'))) {
                $data->where('account_status_log.created_at', '>=', $request->get('date_start'));
            }
            if (!empty($request->get('date_end'))) {
                $data->where('account_status_log.created_at', '<=', $request->get('date_end'));
            }

            if (!empty($request->get('status'))) {
                switch ($request->get('status')) {
                    case 'more3':
                        $data->where('account_status_log.created_at', '<=', date("Y-m-d", strtotime("-3 months", strtotime(Now()))));
                        break;
                    case 'more6':
                        $data->where('account_status_log.created_at', '<=', date("Y-m-d", strtotime("-6 months", strtotime(Now()))));
                        break;
                    case 'more12':
                        $data->where('account_status_log.created_at', '<=', date("Y-m-d", strtotime("-12 months", strtotime(Now()))));
                        break;
                }
            }
            if (!empty($request->get('accountType'))) {
                $vipCondition = explode('_', $request->get('accountType'))[0];
                $isVip = \App\Models\User::findById($list->id)->isVip();
                if ($vipCondition == 'vip') {
                    if (!$isVip) {
                        continue;
                    }
                } else if ($vipCondition == 'notvip') {
                    if ($isVip) {
                        continue;
                    }
                }
                $engroup =  explode('_', $request->get('accountType'))[1];
                $data->where('users.engroup', $engroup);
            }
            if (!empty($request->get('closeReason'))) {
                $data->where('account_status_log.reasonType', $request->get('closeReason'));
            }

            $data = $data->where('account_status_log.user_id', $list->user_id)->orderBy('account_status_log.created_at', 'DESC')->first();
            if (!is_null($data)) {
                $listAccount[$key] = $data;
            }
        }

        $listAccount = collect($listAccount)->sortByDesc('created_at');
        $page = $request->get('page', 1);
        $perPage = 15;
        $listAccount = new LengthAwarePaginator($listAccount->forPage($page, $perPage), $listAccount->count(), $perPage, $page,  ['path' => '/admin/users/closeAccountReason/']);

        return view('admin.users.closeAccountAnalysis', compact('listAccount'));
    }

    //預算及車馬費警示警示

    public function closeAccountDetail(Request $request)
    {
        $account = User::findById($request->get('userID'));
        $data = [];
        if (!is_null($account)) {
            $data = AccountStatusLog::where('user_id', $account->id)->orderBy('created_at', 'DESC')->get();
        }

        return view('admin.users.closeAccountDetail', compact('account', 'data'));
    }

    public function unwarnedUser(Request $request)
    {
        $data = $request->post('data');

        $warned = warned_users::where('member_id', $data['id'])->get();

        if ($warned->count() > 0) {
            foreach ($warned as $r) {
                $checkLog = DB::table('is_warned_log')->where('user_id', $r->member_id)->where('created_at', $r->created_at)->first();
                if (!$checkLog) {
                    //寫入log
                    DB::table('is_warned_log')->insert(['user_id' => $r->member_id, 'reason' => $r->reason, 'vip_pass' => $r->vip_pass, 'adv_auth' => $r->adv_auth, 'video_auth' => $r->video_auth, 'created_at' => $r->created_at]);
                }
                if(($r->video_auth ?? 0) == 1 && isset($r->user?->backend_user_details->sortByDesc('id')->first()?->video_auth_warned_users_shot_id)  && $r->user->backend_user_details->sortByDesc('id')->first()->video_auth_warned_users_shot_id)
                {
                    BackendUserDetails::reset_video_verify($data['id']);
                }
            }
            warned_users::where('member_id', '=', $data['id'])->delete();
        }
        $this->messageService->setMessageHandlingBySenderId($data['id'],0);
        //新增Admin操作log
        $this->insertAdminActionLog($data['id'], '解除站方警示');
        $data = array(
            'code' => '200',
            'status' => 'success'
        );
        echo json_encode($data);
    }

    public function changeExchangePeriod(Request $request)
    {

        users::where('id', $request->input('id'))->update(['exchange_period' => $request->input('exchange_period')]);

        //        $data = array(
        //            'code' => '200',
        //            'status' => 'success'
        //        );

        //        echo json_encode($data);
        return back()->with('message', '成功更新包養關係');
    }

    public function toggleRecommendedUser(Request $request)
    {
        //給優選三個月
        if ($request->Recommended == 1) {
            $user = Vip::select('member_id')
                ->where('member_id', $request->user_id)
                ->update(array('updated_at' => Carbon::now()->subMonths(3)));
        } elseif ($request->Recommended == 0) {
            //取消優選
            if (is_numeric($request->user_id)) {
                DB::select(DB::raw("update member_vip set updated_at = null where member_id = $request->user_id"));
            }
        }
        //新增Admin操作log
        $this->insertAdminActionLog($request->user_id, $request->Recommended == 1 ? '給予優選' : '取消優選');

        if (isset($request->page)) {
            switch ($request->page) {
                case 'advInfo':
                    return redirect('admin/users/advInfo/' . $request->user_id);
                    break;
            }
        }
    }

    public function showWarnedUserDialog(Request $request)
    {
        $admin = $this->admin->checkAdmin();
        if ($admin) {
            $warnedUser = users::where('id', $request->user_id)->get()->first();
            $banReason = DB::table('reason_list')->select('content')->where('type', 'ban')->get();
            if (!$warnedUser)
                return back()->withErrors('查無使用者');
            else {
                return view('admin.users.warnedUserDialog')
                    ->with('banReason', $banReason)
                    ->with('warnedUser', $warnedUser);
            }
        } else {
            return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function showBanUserDialog(Request $request)
    {
        $admin = $this->admin->checkAdmin();
        if ($admin) {
            $bannedUser = users::where('id', $request->user_id)->get()->first();
            $msg = Message::withTrashed()->where(function ($q) {
                $q->where('unsend', 0)->whereNull('deleted_at');
                $q->orwhere('unsend', 1);
            })->where('id', $request->msg_id)->get()->first();
            $banReason = DB::table('reason_list')->select('content')->where('type', 'ban')->get();
            if (!$bannedUser)
                return back()->withErrors('查無使用者');
            else {
                return view('admin.users.bannedUserDialog')
                    ->with('msg', $msg)
                    ->with('banReason', $banReason)
                    ->with('bannedUser', $bannedUser)
                    ->with('isReported', $request->isReported);
            }
        } else {
            return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function banUserWithDayAndMessage(Request $request)
    {
        //todo : banUserWithDays change way.
        $user_id = $request->user_id;
        $msg_id = $request->msg_id;
        $days = $request->days;
        $reason = $request->reason;
        $addreason = $request->addreason;
        //勾選加入常用列表後新增
        if ($addreason) {
            if (DB::table('reason_list')->where([['type', 'ban'], ['content', $reason]])->first() == null) {
                DB::table('reason_list')->insert(['type' => 'ban', 'content' => $reason]);
            }
        }
        $isReported = $request->isReported;

        $userBanned = banned_users::where('member_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->get()->first();
        if (!$userBanned) {
            $userBanned = new banned_users;
        }

        $userBanned->member_id = $user_id;

        if ($days != 'X') {
            $userBanned->expire_date = Carbon::now()->addDays($days);
        } else {
            $userBanned->expire_date = null;
        }


        if ($isReported) {
            $message = Reported::select('reported.content', 'reported.created_at')
                ->join('users', 'reported.reported_id', '=', 'users.id')
                ->where('reported.id', $msg_id)->get()->first();
        } else {
            $message = Message::withTrashed()->where(function ($q) {
                $q->where('unsend', 0)->whereNull('deleted_at');
                $q->orwhere('unsend', 1);
            })->select('message.content', 'message.created_at', 'users.name')
                ->join('users', 'message.to_id', '=', 'users.id')
                ->where('message.id', $msg_id)->get()->first();
        }

        if (isset($message) && $days != 'X') {
            $userBanned->message_content = $message->content;
            $userBanned->message_time = $message->created_at;
            $userBanned->recipient_name = $message->name;
        }
        if (isset($reason)) {
            $userBanned->reason = $reason;
        }
        $userBanned->save();
        //寫入log
        DB::table('is_banned_log')->insert([
            'user_id' => $user_id,
            'reason' => $userBanned->reason,
            'message_content' => $userBanned->message_content,
            'recipient_name' => $userBanned->recipient_name,
            'message_time' => $userBanned->message_time,
            'expire_date' => $userBanned->expire_date,
            'created_at' => Carbon::now()
        ]);
        //$user = User::where('id', $user_id)->get()->first();
        //if($msg_id == 0){
        //    $content = ['hello' => $user->name.'您好，',
        //        'notice1' => '您因遭到檢舉，',
        //        'notice2' => '經管理員檢視，認為確實違反規定，',
        //        'notice3' => '所以遭封鎖'.$days.'天。'];
        //    $user->notify(new BannedNotification($content));
        //    return back()->with('message', '成功封鎖使用者並發送通知信');
        //}
        //else{
        //    $message = Message::where('id', $msg_id)->get()->first();
        //    $content = ['hello' => $user->name.'您好，',
        //        'notice1' => '您在'.$message->created_at.'所發送的訊息，',
        //        'notice2' => '因內容「'.$message->content.'」，',
        //        'notice3' => '所以遭封鎖'.$days.'天。'];
        //    $user->notify(new BannedNotification($content));
        //    return back()->with('message', '成功封鎖使用者並發送通知信');
        //}
        return back()->with('message', '成功封鎖使用者。');
    }

    public function userUnblock(Request $request)
    {
        $userBanned = banned_users::where('member_id', $request->user_id)
            ->orderBy('created_at', 'desc')
            ->get()->first();
        if ($userBanned) {
            $checkLog = DB::table('is_banned_log')->where('user_id', $userBanned->member_id)->where('created_at', $userBanned->created_at)->get()->first();
            if (!$checkLog) {
                //寫入log
                DB::table('is_banned_log')->insert(['user_id' => $userBanned->member_id, 'reason' => $userBanned->reason, 'expire_date' => $userBanned->expire_date, 'vip_pass' => $userBanned->vip_pass, 'adv_auth' => $userBanned->adv_auth, 'created_at' => $userBanned->created_at]);
            }
            $userBanned->delete();
            return redirect()->back()->with('message', '成功解除封鎖使用者');
        } else {
            return redirect()->back()->withErrors(['出現錯誤，無法解除封鎖使用者']);
        }
    }

    public function advIndex(Request $request)
    {
        $users = $this->admin->advSearch($request);
        return view('admin.users.advIndex')
            ->with('users', $users)
            ->with('name', isset($request->name) ? $request->name : null)
            ->with('title', isset($request->title) ? $request->title : null)
            ->with('style', isset($request->style) ? $request->style : null)
            ->with('about', isset($request->about) ? $request->about : null)
            ->with('email', isset($request->email) ? $request->email : null)
            ->with('phone', isset($request->phone) ? $request->phone : null)
            ->with('order_no', isset($request->order_no) ? $request->order_no : null)
            ->with('keyword', isset($request->keyword) ? $request->keyword : null)
            ->with('login_time', isset($request->login_time) ? $request->login_time : null)
            ->with('member_type', isset($request->member_type) ? $request->member_type : null)
            ->with('time', isset($request->time) ? $request->time : null);
        return view('admin.users.advIndex');
    }

    /**
     * Display a listing of the resource searched. (Advanced)
     *
     * @return \Illuminate\Http\Response
     */
    public function advSearch(Request $request, string $message = null)
    {
        $users = $this->admin->advSearch($request);
        $message = isset($message) ? ($message == 'ban' ? '成功封鎖使用者' : '成功解除封鎖使用者') : null;
        $request->session()->put('message', $message);
        return view('admin.users.advIndex')
            ->with('users', $users)
            ->with('name', isset($request->name) ? $request->name : null)
            ->with('title', isset($request->title) ? $request->title : null)
            ->with('style', isset($request->style) ? $request->style : null)
            ->with('about', isset($request->about) ? $request->about : null)
            ->with('email', isset($request->email) ? $request->email : null)
            ->with('phone', isset($request->phone) ? $request->phone : null)
            ->with('order_no', isset($request->order_no) ? $request->order_no : null)
            ->with('keyword', isset($request->keyword) ? $request->keyword : null)
            ->with('member_type', isset($request->member_type) ? $request->member_type : null)
            ->with('time', isset($request->time) ? $request->time : null);
    }

    public function advSearchInfo(Request $request)
    {
        $users = $this->admin->advSearch($request);
        return array('users' => $users);
    }

    /**
     * Display advance information of a member.
     *
     * @return \Illuminate\Http\Response
     */
    public function advInfo(Request $request, $id)
    {
        $operator = Auth::user();
        $service = $this->service->riseByUserEntry($operator);
        $advInfo_check_log = AdminActionLog::where('act','查看會員基本資料')
            ->where('operator',$operator->id)
            ->where('target_id',$id)
            ->orderByDesc('created_at')
            ->first();
        //2小時內如未重複查看時新增log
        if($advInfo_check_log ?? false) {
            if($advInfo_check_log->created_at < Carbon::now()->subHours(2)) {
                $this->insertAdminActionLog($id, '查看會員基本資料');
            }
        } else {
            $this->insertAdminActionLog($id, '查看會員基本資料');
        }


        set_time_limit(900);
        if (!$id) {
            return redirect(route('users/advSearch'));
        }

        $block = $request->block;

        $user = User::where('id', '=', $id)
            ->get()->first();
        if (!isset($user)) {
            if ($block == 'pic')  return;
            return '<h1>會員資料已刪除。</h1>';
        }
        $userMeta = UserMeta::where('user_id', '=', $id)->get()->first();

        if ($block == 'pic') {
            return view('admin.users.advInfoPicBlock')
                ->with('user', $user)
                ->with('userMeta', $userMeta)
                ->with('last_images_compare_encode', ImagesCompareEncode::orderByDesc('id')->firstOrNew())
                ->with('service',$service);
        }
        if ($block == 'userAdvInfo') {
            $userAdvInfo = \App\Models\User::userAdvInfo($user->id);
            return view('admin.users.advInfo_UserAdvInfo')
                ->with('userAdvInfo', $userAdvInfo)
                ->with('service',$service);
        }
        if ($block == 'advInfoLoginLog') {
            $userLogin_log= \App\Models\User::userLoginLog($user->id,$request);
            return view('admin.users.advInfoLoginLog')
                ->with('user', $user)
                ->with('userLogin_log', $userLogin_log)
                ->with('service',$service);
        }
        $userMessage = Message::where('from_id', $id)->orderBy('created_at', 'desc')->paginate(config('social.admin.showMessageCount'));
        if (!empty($request->get('page'))) {
            //新增Admin操作log
            $this->insertAdminActionLog($id, '溜覽所有訊息');
        }
        $to_ids = array();
        foreach ($userMessage as $u) {
            if (!array_key_exists($u->to_id, $to_ids)) {
                $to_ids[$u->to_id] = User::select('name', 'engroup')->where('id', $u->to_id)->get()->first();

                if ($to_ids[$u->to_id]) {
                    $to_ids[$u->to_id]['tipcount'] = Tip::TipCount_ChangeGood($u->to_id);
                    $to_ids[$u->to_id]['vip'] = Vip::vip_diamond($u->to_id);
                    $to_ids[$u->to_id]['name'] = $to_ids[$u->to_id]->name;
                    $to_ids[$u->to_id]['isBlocked'] = banned_users::where('member_id', $u->to_id)->orderBy('created_at', 'desc')->get()->first();
                    $to_ids[$u->to_id]['engroup'] = $to_ids[$u->to_id]->engroup;
                } else {
                    $to_ids[$u->to_id] = array();
                    $to_ids[$u->to_id]['name'] = '查無資料或使用者資料已刪除';
                }
            }
        }

        //groupby $userMessage
        $userMessage_log = Message::withTrashed()->selectRaw("IF(message.to_id='" . $id . "', message.from_id, message.to_id) as ref_user_id, message.room_id, message.to_id, message.from_id, count(*) as toCount")
            ->where('message.from_id', $id)
            ->orWhere('message.to_id', $id)
            ->where("message.created_at", '>=', \Carbon\Carbon::parse("180 days ago")->toDateTimeString())
            ->groupBy("ref_user_id")
            ->orderByRaw("IF(ref_user_id=1049, 1, 0)  desc")
            ->orderBy('message.created_at', 'DESC')
            ->paginate(1000);
        // 給予、取消優選
        $now = \Carbon\Carbon::now();
        $vip_date = Vip::select('id', 'updated_at')->where('member_id', $user->id)->orderBy('updated_at', 'desc')->get()->first();
        if (isset($vip_date->updated_at)) {
            $vip_date = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $vip_date->updated_at);
            $diff_in_months = $vip_date->diffInMonths($now);
            //未滿一個月給予優選
            $user['Recommended'] = $diff_in_months == 0 ? 1 : 0;
        } else {
            //NULL的給予優選
            $user['Recommended'] = 1;
        }
        $isVip = $user->isVip();
        $isHidden = $user->valueAddedServiceStatus('hideOnline');
        $isFreeVip = $user->isFreeVip();
        $user['auth_status'] = 0;
        if ($user->isPhoneAuth() == 1) $user['auth_status'] = 1;
        $user['isvip'] = $isVip;
        $user['isHidden'] = $isHidden;
        $user['isfreevip'] = $isFreeVip;
        $user['tipcount'] = Tip::TipCount_ChangeGood($user->id);
        $user['vip'] = Vip::vip_diamond($user->id);
        $user['isBlocked'] = banned_users::where('member_id', $user->id)->orderBy('created_at', 'desc')->get()->first();
        if (!isset($user['isBlocked'])) {
            $user['isBlocked'] = \App\Models\BannedUsersImplicitly::where('target', $user->id)->get()->first();
            if (isset($user['isBlocked'])) {
                $user['isBlocked']['implicitly'] = 1;
            }
        }

        //        $user['isAdminWarned'] = warned_users::where('member_id',$user->id)
        //            ->where('expire_date','>=',now())
        //            ->orWhere('expire_date',NULL)
        //            ->get()->first();
        $data = warned_users::where('member_id', $user->id)->orderBy('created_at', 'desc')->first();
        if (isset($data) && ($data->expire_date == null || $data->expire_date >= Carbon::now())) {
            $user['isAdminWarned'] = 1;
            $user['adminWarned_expireDate'] = $data->expire_date;
            $user['adminWarned_createdAt'] = $data->created_at;
        } else {
            $user['isAdminWarned'] = 0;
        }

        $banReason = DB::table('reason_list')->select('content')->where('type', 'ban')->get();
        $implicitly_banReason = DB::table('reason_list')->select('content')->where('type', 'implicitly')->get();
        $warned_banReason = DB::table('reason_list')->select('content')->where('type', 'warned')->get();

        //帳號登入紀錄
        $userLogin_log= \App\Models\User::userLoginLog($user->id, $request);

        //個人檢舉紀錄
        $reported = Reported::select('reported.id', 'reported.reported_id as rid', 'reported.content as reason', 'reported.pic as pic', 'reported.created_at as reporter_time', 'u.name', 'u.email', 'u.engroup', 'm.isWarned', 'b.id as banned_id', 'b.expire_date as banned_expire_date', 'w.id as warned_id', 'w.expire_date as warned_expire_date')
            ->leftJoin('users as u', 'u.id', 'reported.reported_id')->where('u.id', '!=', null)
            ->leftJoin('user_meta as m', 'u.id', 'm.user_id')
            ->leftJoin('banned_users as b', 'u.id', 'b.member_id')
            ->leftJoin('warned_users as w', 'u.id', 'w.member_id');
        $reported = $reported->addSelect(DB::raw("'reported' as table_name"));
        $reported = $reported->where('reported.member_id', $user->id)->get();

        $reported_pic = ReportedPic::select('reported_pic.id', 'member_pic.member_id as rid', 'reported_pic.content as reason', 'reported_pic.created_at as reporter_time', 'u.name', 'u.email', 'u.engroup', 'm.isWarned', 'b.id as banned_id', 'b.expire_date as banned_expire_date', 'w.id as warned_id', 'w.expire_date as warned_expire_date');
        $reported_pic = $reported_pic->addSelect(DB::raw("'reported_pic' as table_name"));
        $reported_pic = $reported_pic->join('member_pic', 'member_pic.id', '=', 'reported_pic.reported_pic_id')
            ->leftJoin('users as u', 'u.id', 'member_pic.member_id')->where('u.id', '!=', null)
            ->leftJoin('user_meta as m', 'u.id', 'm.user_id')
            ->leftJoin('banned_users as b', 'u.id', 'b.member_id')
            ->leftJoin('warned_users as w', 'u.id', 'w.member_id')
            ->where('reported_pic.reporter_id', $user->id)->get();

        $reported_avatar = ReportedAvatar::select('reported_avatar.id', 'reported_avatar.reported_user_id as rid', 'reported_avatar.content as reason', 'reported_avatar.pic as pic', 'reported_avatar.created_at as reporter_time', 'u.name', 'u.email', 'u.engroup', 'm.isWarned', 'b.id as banned_id', 'b.expire_date as banned_expire_date', 'w.id as warned_id', 'w.expire_date as warned_expire_date')
            ->leftJoin('users as u', 'u.id', 'reported_avatar.reported_user_id')->where('u.id', '!=', null)
            ->leftJoin('user_meta as m', 'u.id', 'm.user_id')
            ->leftJoin('banned_users as b', 'u.id', 'b.member_id')
            ->leftJoin('warned_users as w', 'u.id', 'w.member_id');
        $reported_avatar = $reported_avatar->addSelect(DB::raw("'reported_avatar' as table_name"));
        $reported_avatar = $reported_avatar->where('reported_avatar.reporter_id', $user->id)->get();

        $reported_message = Message::select('message.id', 'message.from_id as rid', 'message.reportContent as reason', 'message.updated_at as reporter_time', 'u.name', 'u.email', 'u.engroup', 'm.isWarned', 'b.id as banned_id', 'b.expire_date as banned_expire_date', 'w.id as warned_id', 'w.expire_date as warned_expire_date')
            ->leftJoin('users as u', 'u.id', 'message.from_id')->where('u.id', '!=', null)
            ->leftJoin('user_meta as m', 'u.id', 'm.user_id')
            ->leftJoin('banned_users as b', 'u.id', 'b.member_id')
            ->leftJoin('warned_users as w', 'u.id', 'w.member_id');
        $reported_message = $reported_message->addSelect(DB::raw("'message' as table_name"));
        $reported_message = $reported_message->where('message.to_id', $user->id)->where('message.isReported', 1)->get();

        $collections = collect([$reported, $reported_pic, $reported_avatar, $reported_message]);
        $report_all_personal = $collections->collapse()->sortByDesc('reporter_time')->groupBy('rid')->collapse();
        //Log::notice(print_r($report_all_personal,true));

        $reportBySelf = array();
        foreach ($report_all_personal as $row) {
            switch ($row->table_name) {
                case 'reported':
                    $report_type = '會員檢舉';
                    break;
                case 'reported_pic':
                    $report_type = '照片檢舉';
                    break;
                case 'reported_avatar':
                    $report_type = '大頭照檢舉';
                    break;
                case 'message';
                    $report_type = '訊息檢舉';
                    break;
            }
            $r_user = User::findById($row->rid);
            $punishment_status = '';
            if ($row->banned_id && ($row->banned_expire_date > Carbon::now() || $row->banned_expire_date == null)) {
                $punishment_status = 'banning';
            } else if ($row->warned_id && ($row->warned_expire_date > Carbon::now() || $row->warned_expire_date == null)) {
                $punishment_status = 'warning';
            }
            array_push(
                $reportBySelf,
                array(
                    'reporter_id' => $row->rid,
                    'content' => $row->reason,
                    'pic' => is_null($row->pic) ? [] : json_decode($row->pic, true),
                    'created_at' => $row->reporter_time,
                    'tipcount' => Tip::TipCount_ChangeGood($row->rid),
                    'vip' => Vip::vip_diamond($row->rid),
                    'name' => $row->name,
                    'email' => $row->email,
                    'isvip' => $r_user->isVip(),
                    'auth_status' => $r_user->isPhoneAuth(),
                    'report_type' => $report_type,
                    'engroup' => $row->engroup,
                    'punishment_status' => $punishment_status
                )
            );
            continue;
        }

        //被檢舉紀錄
        //檢舉紀錄 reporter_id檢舉者uid  被檢舉者reported_user_id為此頁面主要會員
        $pic_report1 = ReportedAvatar::select(
            'reported_avatar.reporter_id as uid',
            'reported_avatar.reported_user_id as edid',
            'reported_avatar.cancel',
            'reported_avatar.created_at',
            'reported_avatar.content',
            'reported_avatar.pic',
            'b.id as banned_id',
            'b.expire_date as banned_expire_date',
            'w.id as warned_id',
            'w.expire_date as warned_expire_date'
        )
            ->leftJoin('banned_users as b', 'reported_avatar.reporter_id', 'b.member_id')
            ->leftJoin('warned_users as w', 'reported_avatar.reporter_id', 'w.member_id')
            ->where('reported_user_id', $user->id)
            ->where('reporter_id', '!=', $user->id)
            ->groupBy('reporter_id')
            ->get();
        $pic_report2 = ReportedPic::select('reported_pic.reporter_id as uid', 'member_pic.member_id as edid', 'cancel', 'reported_pic.created_at', 'reported_pic.content')->join('member_pic', 'reported_pic.reported_pic_id', '=', 'member_pic.id')->where('member_pic.member_id', $user->id)->where('reported_pic.reporter_id', '!=', $user->id)->groupBy('reported_pic.reporter_id')->get();
        //大頭照與照片合併計算
        $collection = collect([$pic_report1, $pic_report2]);
        $pic_all_report = $collection->collapse()->unique('uid');
        //$pic_all_report->unique()->all();

        $msg_report = Message::select(
            'message.to_id',
            'message.id',
            'message.cancel',
            'message.created_at',
            'message.content',
            'message.from_id',
            'b.id as banned_id',
            'b.expire_date as banned_expire_date',
            'w.id as warned_id',
            'w.expire_date as warned_expire_date'
        )
            ->leftJoin('banned_users as b', 'message.to_id', 'b.member_id')
            ->leftJoin('warned_users as w', 'message.to_id', 'w.member_id')
            ->where('message.from_id', $user->id)
            ->where('message.isReported', 1)
            ->distinct('message.to_id')
            ->get();
        $report = Reported::select(
            'reported.member_id',
            'reported.reported_id',
            'reported.cancel',
            'reported.created_at',
            'reported.content',
            'reported.pic',
            'b.id as banned_id',
            'b.expire_date as banned_expire_date',
            'w.id as warned_id',
            'w.expire_date as warned_expire_date'
        )
            ->leftJoin('banned_users as b', 'reported.member_id', 'b.member_id')
            ->leftJoin('warned_users as w', 'reported.member_id', 'w.member_id')
            ->where('reported.reported_id', $user->id)
            ->where('reported.member_id', '!=', $user->id)
            ->groupBy('reported.member_id')
            ->get();
        $report_all = array();

        foreach ($pic_all_report as $row) {
            $f_user = User::findById($row->uid);
            $punishment_status = '';
            if ($row->banned_id && ($row->banned_expire_date > Carbon::now() || $row->banned_expire_date == null)) {
                $punishment_status = 'banning';
            } else if ($row->warned_id && ($row->warned_expire_date > Carbon::now() || $row->warned_expire_date == null)) {
                $punishment_status = 'warning';
            }
            if (!isset($f_user)) {
                array_push(
                    $report_all,
                    array(
                        'reporter_id' => $row->uid,
                        'reported_id' => $row->edid,
                        'cancel' => $row->cancel,
                        'content' => $row->content,
                        'created_at' => $row->created_at,
                        'tipcount' => Tip::TipCount_ChangeGood($row->uid),
                        'vip' => Vip::vip_diamond($row->uid),
                        'isBlocked' => banned_users::where('member_id', $row->uid)->orderBy('created_at', 'desc')->get()->first(),
                        'name' => "無會員資料，ID: " . $row->uid,
                        'email' => null,
                        'isvip' => null,
                        'auth_status' => null,
                        'report_type' => '照片檢舉',
                        'report_table' => 'reported_avatarpic',
                        'engroup' => null,
                        'punishment_status' => $punishment_status
                    )
                );
                continue;
            }
            $auth_status = 0;
            $report_table = '';
            if ($f_user->isPhoneAuth() == 1) {
                $auth_status = 1;
            }
            array_push(
                $report_all,
                array(
                    'reporter_id' => $row->uid,
                    'reported_id' => $row->edid,
                    'cancel' => $row->cancel,
                    'content' => $row->content,
                    'pic' => is_null($row->pic) ? [] : json_decode($row->pic, true),
                    'created_at' => $row->created_at,
                    'tipcount' => Tip::TipCount_ChangeGood($row->uid),
                    'vip' => Vip::vip_diamond($row->uid),
                    'isBlocked' => banned_users::where('member_id', $row->uid)->orderBy('created_at', 'desc')->get()->first(),
                    'name' => $f_user->name,
                    'email' => $f_user->email,
                    'isvip' => $f_user->isVip(),
                    'auth_status' => $auth_status,
                    'report_type' => '照片檢舉',
                    'report_table' => 'reported_avatarpic',
                    'engroup' => $f_user->engroup,
                    'punishment_status' => $punishment_status
                )
            );
        }
        foreach ($msg_report as $row) {
            $f_user = User::findById($row->to_id);
            $punishment_status = '';
            if ($row->banned_id && ($row->banned_expire_date > Carbon::now() || $row->banned_expire_date == null)) {
                $punishment_status = 'banning';
            } else if ($row->warned_id && ($row->warned_expire_date > Carbon::now() || $row->warned_expire_date == null)) {
                $punishment_status = 'warning';
            }
            if (array_search($row->to_id, array_column($report_all, 'reporter_id')) === false) {
                if (!isset($f_user)) {
                    array_push(
                        $report_all,
                        array(
                            'report_dbid' => $row->id,
                            'reported_id' => $row->from_id,
                            'reporter_id' => $row->to_id,
                            'cancel' => $row->cancel,
                            'content' => $row->content,
                            'created_at' => $row->created_at,
                            'tipcount' => Tip::TipCount_ChangeGood($row->to_id),
                            'vip' => Vip::vip_diamond($row->to_id),
                            'isBlocked' => banned_users::where('member_id', $row->to_id)->orderBy('created_at', 'desc')->get()->first(),
                            'name' => "無會員資料，ID: " . $row->to_id,
                            'email' => null,
                            'isvip' => null,
                            'auth_status' => null,
                            'report_type' => '訊息檢舉',
                            'report_table' => 'message',
                            'engroup' => null,
                            'punishment_status' => $punishment_status
                        )
                    );
                    continue;
                }
                $auth_status = 0;
                if ($f_user->isPhoneAuth() == 1) {
                    $auth_status = 1;
                }


                array_push(
                    $report_all,
                    array(
                        'report_dbid' => $row->id,
                        'reported_id' => $row->from_id,
                        'reporter_id' => $row->to_id,
                        'cancel' => $row->cancel,
                        'content' => $row->content,
                        'created_at' => $row->created_at,
                        'tipcount' => Tip::TipCount_ChangeGood($row->to_id),
                        'vip' => Vip::vip_diamond($row->to_id),
                        'isBlocked' => banned_users::where('member_id', $row->to_id)->orderBy('created_at', 'desc')->get()->first(),
                        'name' => $f_user->name,
                        'email' => $f_user->email,
                        'isvip' => $f_user->isVip(),
                        'auth_status' => $auth_status,
                        'report_type' => '訊息檢舉',
                        'report_table' => 'message',
                        'engroup' => $f_user->engroup,
                        'punishment_status' => $punishment_status
                    )
                );
            }
        }
        foreach ($report as $row) {
            $f_user = User::findById($row->member_id);
            $punishment_status = '';
            if ($row->banned_id && ($row->banned_expire_date > Carbon::now() || $row->banned_expire_date == null)) {
                $punishment_status = 'banning';
            } else if ($row->warned_id && ($row->warned_expire_date > Carbon::now() || $row->warned_expire_date == null)) {
                $punishment_status = 'warning';
            }
            if (array_search($row->member_id, array_column($report_all, 'reporter_id')) === false) {
                if (!isset($f_user)) {
                    array_push(
                        $report_all,
                        array(
                            'reported_id' => $row->reported_id,
                            'reporter_id' => $row->member_id,
                            'cancel' => $row->cancel,
                            'content' => $row->content,
                            'pic' => is_null($row->pic) ? [] : json_decode($row->pic, true),
                            'created_at' => $row->created_at,
                            'tipcount' => Tip::TipCount_ChangeGood($row->member_id),
                            'vip' => Vip::vip_diamond($row->member_id),
                            'isBlocked' => banned_users::where('member_id', $row->member_id)->orderBy('created_at', 'desc')->get()->first(),
                            'name' => "無會員資料，ID: " . $row->member_id,
                            'email' => null,
                            'isvip' => null,
                            'auth_status' => null,
                            'report_type' => '會員檢舉',
                            'report_table' => 'reported',
                            'engroup' => null,
                            'punishment_status' => $punishment_status
                        )
                    );
                    continue;
                }
                $auth_status = 0;
                if ($f_user->isPhoneAuth() == 1) {
                    $auth_status = 1;
                }


                array_push(
                    $report_all,
                    array(
                        'reported_id' => $row->reported_id,
                        'reporter_id' => $row->member_id,
                        'cancel' => $row->cancel,
                        'content' => $row->content,
                        'pic' => is_null($row->pic) ? [] : json_decode($row->pic, true),
                        'created_at' => $row->created_at,
                        'tipcount' => Tip::TipCount_ChangeGood($row->member_id),
                        'vip' => Vip::vip_diamond($row->member_id),
                        'isBlocked' => banned_users::where('member_id', $row->member_id)->orderBy('created_at', 'desc')->get()->first(),
                        'name' => $f_user->name,
                        'email' => $f_user->email,
                        'isvip' => $f_user->isVip(),
                        'auth_status' => $auth_status,
                        'report_type' => '會員檢舉',
                        'report_table' => 'reported',
                        'engroup' => $f_user->engroup,
                        'punishment_status' => $punishment_status
                    )
                );
            }
        }

        //PR
        $pr = DB::table('pr_log')->where('user_id', $user->id)->where('active', 1)->first();
        if (isset($pr)) {
            $pr_created_at = $pr->created_at;
            $query_pr = $pr->pr_log;
            $pr = $pr->pr;
        } else {
            $pr_created_at = '';
            $query_pr = '';
            $pr = '無';
        }

        $evaluation_data = DB::table('evaluation')->where('from_id', $user->id)->get();
        $out_evaluation_data = array();
        foreach ($evaluation_data as $row) {
            $tmp = array();
            $f_user = User::findById($row->to_id);
            if (!$f_user) {
                continue;
            }
            $tmp['id'] = $row->id;
            $tmp['content'] = $row->content . (!is_null($row->admin_comment) ? ('  (' . $row->admin_comment . ')') : '');
            $tmp['re_content'] = $row->re_content;
            $tmp['rating'] = $row->rating;
            $tmp['re_created_at'] = $row->re_created_at;
            $tmp['created_at'] = $row->created_at;
            $tmp['content_violation_processing'] = $row->content_violation_processing;
            $tmp['anonymous_content_status'] = $row->anonymous_content_status;
            $tmp['only_show_text'] = $row->only_show_text;
            $tmp['to_id'] = $f_user->id;
            $tmp['from_id'] = $row->from_id;
            $tmp['to_email'] = $f_user->email;
            $tmp['to_name'] = $f_user->name;
            $tmp['to_isvip'] = $f_user->isVip();
            $tmp['is_check'] = $row->is_check;
            $tmp['evaluation_pic'] = EvaluationPic::where('evaluation_id', $row->id)->where('member_id', $row->from_id)->get();
            $tmp['is_delete'] = $row->deleted_at;
            $auth_status = 0;
            if ($f_user->isPhoneAuth() == 1) {
                $auth_status = 1;
            }
            $tmp['to_auth_status'] = $auth_status;
            array_push($out_evaluation_data, $tmp);
        }

        $evaluation_data = DB::table('evaluation')->where('to_id', $user->id)->get();
        $out_evaluation_data_2 = array();
        foreach ($evaluation_data as $row) {
            $tmp = array();
            $f_user = User::findById($row->from_id);
            if (!$f_user) {
                continue;
            }
            $tmp['id'] = $row->id;
            $tmp['content'] = $row->content . (!is_null($row->admin_comment) ? ('  (' . $row->admin_comment . ')') : '');
            $tmp['re_content'] = $row->re_content;
            $tmp['rating'] = $row->rating;
            $tmp['re_created_at'] = $row->re_created_at;
            $tmp['created_at'] = $row->created_at;
            $tmp['content_violation_processing'] = $row->content_violation_processing;
            $tmp['anonymous_content_status'] = $row->anonymous_content_status;
            $tmp['only_show_text'] = $row->only_show_text;
            $tmp['to_id'] = $f_user->id;
            $tmp['from_id'] = $row->to_id;
            $tmp['to_email'] = $f_user->email;
            $tmp['to_name'] = $f_user->name;
            $tmp['to_isvip'] = $f_user->isVip();
            $tmp['is_check'] = $row->is_check;
            $tmp['evaluation_pic'] = EvaluationPic::where('evaluation_id', $row->id)->where('member_id', $f_user->id)->get();
            $tmp['is_delete'] = $row->deleted_at;
            $auth_status = 0;
            if ($f_user->isPhoneAuth() == 1) {
                $auth_status = 1;
            }
            $tmp['to_auth_status'] = $auth_status;
            array_push($out_evaluation_data_2, $tmp);
        }

        $uid = $user->id;
        //曾被警示
        $isEverWarned = DB::table('is_warned_log')
            ->where('user_id', $user->id)
            ->whereNotIn('created_at', function ($query) use ($uid) {
                $query->select('created_at')
                    ->from(with(new warned_users())->getTable())
                    ->where('member_id', $uid)
                    ->where('expire_date', null)
                    ->orWhere('expire_date', '>', Carbon::now())
                    ->where('member_id', $uid);
            })
            ->orderBy('created_at', 'desc')->paginate(10);

        //曾被封鎖
        $isEverBanned = DB::table('is_banned_log')
            ->where('user_id', $user->id)
            ->whereNotIn('created_at', function ($query) use ($uid) {
                $query->select('created_at')
                    ->from(with(new banned_users())->getTable())
                    ->where('member_id', $uid)
                    ->where('expire_date', null)
                    ->orWhere('expire_date', '>', Carbon::now())
                    ->where('member_id', $uid);
            })
            ->orderBy('created_at', 'desc')->paginate(10);

        //正被警示
        $isWarned = warned_users::where('member_id', $user->id)->where('expire_date', null)->orWhere('expire_date', '>', Carbon::now())->where('member_id', $user->id)->orderBy('created_at', 'desc')->paginate(10);
        $is_warned_of_budget = false;
        if(($isWarned->first()->type??'') == 'month_budget' || ($isWarned->first()->type??'') == 'transport_fare') {
            $is_warned_of_budget = true;
        }
        //正被封鎖
        $isBanned = banned_users::where('member_id', $user->id)->where('expire_date', null)->orWhere('expire_date', '>', Carbon::now())->where('member_id', $user->id)->orderBy('created_at', 'desc')->paginate(10);

        //cfp_id distinct
        $cfp_id = LogUserLogin::select('cfp_id')
            ->selectRaw('MAX(created_at) AS last_tiime')
            ->orderByDesc('last_tiime')
            ->where('user_id', $user->id)
            ->groupBy('cfp_id')
            ->get()
            ->filter(function ($model) {
                if ($model->cfp_id) {
                    $getUseUser = LogUserLogin::where('cfp_id',$model->cfp_id)->distinct('user_id')->groupBy('user_id')->get()->toarray();
                    $getUseUser = array_column($getUseUser,'user_id');
                    $model->UseOnlinePeople = count($getUseUser);
                    $model->UseBlockedPeople = banned_users::whereIn('member_id',$getUseUser)->get()->count();
                }
                return $model;
            });
        //ip distinct
        $ip = LogUserLogin::select('ip')
            ->selectRaw('MAX(created_at) AS last_tiime')
            ->orderByDesc('last_tiime')
            ->where('user_id', $user->id)
            ->groupBy('ip')
            ->get()
            ->filter(function ($model) {
                if ($model->ip) {
                    $getUseUser = LogUserLogin::where('ip',$model->ip)->distinct('user_id')->groupBy('user_id')->get()->toarray();
                    $getUseUser = array_column($getUseUser,'user_id');
                    $model->UseOnlinePeople = count($getUseUser);
                    $model->UseBlockedPeople = banned_users::whereIn('member_id',$getUseUser)->get()->count();
                }
                return $model;
            });

        //userAgent distinct
        $userAgent = LogUserLogin::select('userAgent')->selectRaw('MAX(created_at) AS last_tiime')->orderByDesc('last_tiime')->where('user_id', $user->id)->groupBy('userAgent')->get();


        //$banned_advance_auth_status = DB::table('banned_users')->where('member_id', $id)->where('reason','進階驗證封鎖')->where('message_content','1')->count() > 0 ? 1:0;
        $banned_advance_auth_status = DB::table('banned_users')->where('member_id', $id)->where('adv_auth', 1)->count() > 0 ? 1 : 0;
        // var_dump($banned_advance_auth_count);die();

        //討論區狀態
        $posts_forum = Forum::where('user_id', $user->id)->first();

        //隱藏付費訂單Log
        $hideonline_order = Order::where('user_id', $user->id)->where('service_name', 'hideOnline')->orderBy('order_date', 'desc')->get();

        //使用者紀錄
        $user_record = UserRecord::where('user_id', $user->id)->first();

        //停留時間
        $pageStay = StayOnlineRecord::select(DB::raw("SUM(browse) as browse"), DB::raw("SUM(newer_manual) as newer_manual"))
            ->where('user_id', $id)
            ->where('browse', '>', 0)
            ->orWhere('newer_manual', '>', 0)
            ->where('user_id', $id)
            ->get()
            ->toArray();

        $not_pass_faq_ltime = '';

        if($user->engroup==2) {
            $not_pass_faq_ltime = $this->faqUserService->faq_service()->group_entry()->whereIn('id',$this->faqUserService->riseByUserEntry($user)->getPopupUserGroupList()->pluck('group_id'))->pluck('faq_login_times')->implode(' , ');

            $fnm_step_time_arr = [
                'step_time1_1'=>$user->female_newer_manual_time_list->where('step','1_1')->sum('time')
                ,'step_time1_2'=>$user->female_newer_manual_time_list->where('step','1_2')->sum('time')
                ,'step_time1_3'=>$user->female_newer_manual_time_list->where('step','1_3')->sum('time')
                ,'step_time2_1'=>$user->female_newer_manual_time_list->where('step','2_1')->sum('time')
                ,'step_time2_2'=>$user->female_newer_manual_time_list->where('step','2_2')->sum('time')
                ,'step_time2_3'=>$user->female_newer_manual_time_list->where('step','2_3')->sum('time')
                ,'step_time3_1'=>$user->female_newer_manual_time_list->where('step','3_1')->sum('time')
                ,'step_time3_2'=>$user->female_newer_manual_time_list->where('step','3_2')->sum('time')
                ,'step_time3_3'=>$user->female_newer_manual_time_list->where('step','3_3')->sum('time')
            ];

            $fnm_step1_time_arr = [$fnm_step_time_arr['step_time1_1'],$fnm_step_time_arr['step_time1_2'],$fnm_step_time_arr['step_time1_3']];
            $fnm_step2_time_arr = [$fnm_step_time_arr['step_time2_1'],$fnm_step_time_arr['step_time2_2'],$fnm_step_time_arr['step_time2_3']];
            $fnm_step3_time_arr = [$fnm_step_time_arr['step_time3_1'],$fnm_step_time_arr['step_time3_2'],$fnm_step_time_arr['step_time3_3']];
        }
        $is_test = $request->is_test ?? false;

        $backend_detail = BackendUserDetails::first_or_new($user->id);

        $exif_cat_lang_arr = [
            'FileDateTime'=>'圖檔上傳時間'
            ,'FileSize'=>'圖檔大小'
            ,'Height'=>'高'
            ,'Width'=>'寬'
            ,'ISOSpeedRatings'=>'ISO速度'
            ,'DateTimeOriginal'=>'拍攝日期'
            ,'Model'=>'相機型號'
            ,'Make'=>'相機製造商'
            ,'ResolutionUnit'=>'解析度單位'
            ,'ExifVersion'=>'Exif版本'
            ,'YResolution'=>'垂直解析度'
            ,'XResolution'=>'水平解析度'
        ];

        $step2_admin_log_list = AdminActionLog::where('target_id',$id)
            ->where(function($query) {
                $query->where('action_id', 23);
                $query->orWhere('act','會員檢查 Step2 通過');
            })->orderByDesc('id')->get();

        if (str_contains(url()->current(), 'edit')) {
            $birthday = date('Y-m-d', strtotime($userMeta->birthdate));
            $birthday = explode('-', $birthday);
            $year = $birthday[0];
            $month = $birthday[1];
            $day = $birthday[2];
            return view('admin.users.editAdvInfo')
                ->with('userMeta', $userMeta)
                ->with('user', $user)
                ->with('year', $year)
                ->with('month', $month)
                ->with('day', $day)
                ->with('raa_service',$this->raa_service->riseByUserEntry($user))
                ->with('not_pass_faq_ltime',$not_pass_faq_ltime)
                ->with('fnm_step_time_arr',$fnm_step_time_arr ?? null)
                ->with('fnm_step1_time_arr',$fnm_step1_time_arr ?? null)
                ->with('fnm_step2_time_arr',$fnm_step2_time_arr ?? null)
                ->with('fnm_step3_time_arr',$fnm_step3_time_arr ?? null)
                ->with('exif_cat_lang_arr',$exif_cat_lang_arr)
                ->with('step2_admin_log_list',$step2_admin_log_list);

        } else {
            $user_video_verify_record = UserVideoVerifyRecord::select('user_video_verify_record.*', 'users.name','users.email')
                ->leftJoin('users', 'user_video_verify_record.user_id', '=', 'users.id')
                ->orderBy('user_video_verify_record.created_at','desc')
                ->where('user_video_verify_record.user_id',$user->id)
                ->get();

            return view('admin.users.advInfo')
                ->with('userMeta', $userMeta)
                ->with('banReason', $banReason)
                ->with('warned_banReason', $warned_banReason)
                ->with('warned_info', $warned_banReason)
                ->with('implicitly_banReason', $implicitly_banReason)
                ->with('user', $user)
                ->with('userMessage_log', $userMessage_log)
                ->with('userMessage', $userMessage)
                ->with('to_ids', $to_ids)
                ->with('userLogin_log', $userLogin_log)
                ->with('reportBySelf', $reportBySelf)
                ->with('report_all', $report_all)
                ->with('out_evaluation_data', $out_evaluation_data)
                ->with('out_evaluation_data_2', $out_evaluation_data_2)
                ->with('pr', $pr)
                ->with('pr_log', $query_pr)
                ->with('pr_created_at', $pr_created_at)
                ->with('isEverWarned', $isEverWarned)
                ->with('isEverBanned', $isEverBanned)
                ->with('isWarned', $isWarned)
                ->with('isBanned', $isBanned)
                //->with('isHidden',$isHidden)
                ->with('cfp_id', $cfp_id)
                ->with('ip', $ip)
                ->with('userAgent', $userAgent)
                ->with('banned_advance_auth_status', $banned_advance_auth_status)
                ->with('last_images_compare_encode', ImagesCompareEncode::orderByDesc('id')->firstOrNew())
                ->with('posts_forum', $posts_forum)
                ->with('hideonline_order', $hideonline_order)
                ->with('user_record', $user_record)
                ->with('raa_service',$this->raa_service->riseByUserEntry($user))
                ->with('user_video_verify_record',$user_video_verify_record)
                ->with('is_warned_of_budget', $is_warned_of_budget)
                ->with('pageStay', $pageStay)
                ->with('not_pass_faq_ltime',$not_pass_faq_ltime)
                ->with('backend_detail',$backend_detail)
                ->with('fnm_step_time_arr', $fnm_step_time_arr ?? null)
                ->with('fnm_step1_time_arr', $fnm_step1_time_arr ?? null)
                ->with('fnm_step2_time_arr', $fnm_step2_time_arr ?? null)
                ->with('fnm_step3_time_arr', $fnm_step3_time_arr ?? null)
                ->with('is_test', $is_test)
                ->with('exif_cat_lang_arr',$exif_cat_lang_arr)
                ->with('step2_admin_log_list',$step2_admin_log_list)
                ->with('service',$service);
        }
    }

    public function reportedToggler(Request $request)
    {
        //reporter_id為本頁此會員被檢舉者 reported_id為檢舉者
        //        switch ($request->report_table) {
        //            case 'reported_avatarpic':
        //                if ($request->cancel == 0) {
        //                    ReportedPic::join('member_pic', 'reported_pic.reported_pic_id', '=', 'member_pic.id')
        //                        ->where('member_pic.member_id', $request->reported_id)
        //                        ->where('reported_pic.reporter_id', $request->reporter_id)
        //                        ->getQuery()->update(array('reported_pic.cancel' => 1, 'reported_pic.updated_at' => \Carbon\Carbon::now()));
        //
        //                    ReportedAvatar::where('reporter_id', $request->reporter_id)->where('reported_user_id', $request->reported_id)->update(array('cancel' => 1));
        //                } elseif ($request->cancel == 1) {
        //                    ReportedPic::join('member_pic', 'reported_pic.reported_pic_id', '=', 'member_pic.id')
        //                        ->where('member_pic.member_id', $request->reported_id)
        //                        ->where('reported_pic.reporter_id', $request->reporter_id)
        //                        ->getQuery()->update(array('reported_pic.cancel' => 0, 'reported_pic.updated_at' => \Carbon\Carbon::now()));
        //
        //                    ReportedAvatar::where('reporter_id', $request->reporter_id)->where('reported_user_id', $request->reported_id)->update(array('cancel' => 0));
        //                }
        //                break;
        //            case 'reported':
        //                if ($request->cancel == 0) {
        //                    Reported::where('member_id', $request->reporter_id)->where('reported_id', $request->reported_id)->update(array('cancel' => 1));
        //                } elseif ($request->cancel == 1) {
        //                    Reported::where('member_id', $request->reporter_id)->where('reported_id', $request->reported_id)->update(array('cancel' => 0));
        //                }
        //                break;
        //            case 'message':
        //                if ($request->cancel == 0) {
        //                    Message::where('id', $request->report_dbid)->update(array('cancel' => 1));
        //                } elseif ($request->cancel == 1) {
        //                    Message::where('id', $request->report_dbid)->update(array('cancel' => 0));
        //                }
        //                break;
        //            default:
        //                break;
        //        }

        if ($request->cancel == 0) {
            ReportedPic::join('member_pic', 'reported_pic.reported_pic_id', '=', 'member_pic.id')
                ->where('member_pic.member_id', $request->reported_id)
                ->where('reported_pic.reporter_id', $request->reporter_id)
                ->getQuery()->update(array('reported_pic.cancel' => 1, 'reported_pic.updated_at' => \Carbon\Carbon::now()));

            ReportedAvatar::where('reporter_id', $request->reporter_id)->where('reported_user_id', $request->reported_id)->update(array('cancel' => 1));

            Reported::where('member_id', $request->reporter_id)->where('reported_id', $request->reported_id)->update(array('cancel' => 1));

            Message::where('from_id', $request->reported_id)->where('to_id', $request->reporter_id)->update(array('cancel' => 1));
        } elseif ($request->cancel == 1) {
            ReportedPic::join('member_pic', 'reported_pic.reported_pic_id', '=', 'member_pic.id')
                ->where('member_pic.member_id', $request->reported_id)
                ->where('reported_pic.reporter_id', $request->reporter_id)
                ->getQuery()->update(array('reported_pic.cancel' => 0, 'reported_pic.updated_at' => \Carbon\Carbon::now()));

            ReportedAvatar::where('reporter_id', $request->reporter_id)->where('reported_user_id', $request->reported_id)->update(array('cancel' => 0));

            Reported::where('member_id', $request->reporter_id)->where('reported_id', $request->reported_id)->update(array('cancel' => 0));

            Message::where('from_id', $request->reported_id)->where('to_id', $request->reporter_id)->update(array('cancel' => 0));
        }
        event(new \App\Events\CheckWarnedOfReport($request->reported_id));
        return back();
    }

    //advInfo頁面的切換檢舉是否取消或不計分

    public function editPic_sendMsg(Request $request, $id)
    {
        $admin = $this->admin->checkAdmin();
        if ($admin) {
            $user = $this->service->find($id);
            // $msglib = Msglib::get();
            $userMeta = UserMeta::where('user_id', 'like', $id)->get()->first();
            $msglib = Msglib::selectraw('id, title, msg')->where('kind', '=', 'smsg')->get();
            $msglib_report = Msglib::selectraw('id, title, msg')->where('kind', '=', 'smsg')->get();
            $msglib_msg = collect();
            foreach ($msglib as $m) {
                $m->msg = str_replace('|$report|', $user->name, $m->msg);
                $m->msg = str_replace('NAME', $user->name, $m->msg);
                $m->msg = str_replace('NOW_TIME', date("Y-m-d H:i:s"), $m->msg);
                $m->msg = str_replace('LINE_ICON', AdminService::$line_icon_html, $m->msg);
                $m->msg = str_replace('|$responseTime|', date("Y-m-d H:i:s"), $m->msg);
                $m->msg = str_replace('|$reportTime|', date("Y-m-d H:i:s"), $m->msg);
                $m->msg = str_replace('|$lineIcon|', AdminService::$line_icon_html, $m->msg);
                // $m->msg = str_replace('|$reported|', "|被檢舉者|", $m->msg);
                $msglib_msg->push($m->msg);
            }
            return view('admin.users.editPic_sendMsg')
                ->with('admin', $admin)
                ->with('user', $user)
                ->with('userMeta', $userMeta)
                ->with('from_user', $user)
                ->with('to_user', $admin)
                ->with('msglib', $msglib)
                ->with('msglib2', collect())
                ->with('msglib_report', $msglib_report)
                ->with('msglib_reported', null)
                ->with('msglib_msg', $msglib_msg)
                ->with('message_msg', collect())
                ->with('msglib_msg2', collect())
                ->with('raa_service',$this->raa_service->riseByUserEntry($user));
        } else {
            return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    //advInfo頁面的照片修改與站長訊息發送

    public function editRealAuth_sendMsg(Request $request, $id)
    {
        $raa_service = $this->raa_service;
        $admin = $this->admin->checkAdmin();
        if ($admin) {
            $user = $this->service->find($id);
            $raa_service->riseByUserEntry($user);

            $userMeta = UserMeta::where('user_id', 'like', $id)->get()->first();
            $msglib = Msglib::selectraw('id, title, msg')->where('kind', '=', 'real_auth')->get();
            $msglib_report = Msglib::selectraw('id, title, msg')->where('kind', '=', 'real_auth')->get();
            $msglib_msg = collect();
            foreach ($msglib as $m) {
                $org_msg = $m->msg;
                $m->msg = str_replace('|$report|', $user->name, $m->msg);
                $m->msg = str_replace('NAME', $user->name, $m->msg);
                $m->msg = str_replace('NOW_DATE', date("Y-m-d"), $m->msg);
                $m->msg = str_replace('NOW_TIME', date("Y-m-d H:i:s"), $m->msg);
                $m->msg = str_replace('LINE_ICON', AdminService::$line_icon_html, $m->msg);
                $m->msg = str_replace('|$responseTime|', date("Y-m-d H:i:s"), $m->msg);
                $m->msg = str_replace('|$reportTime|', date("Y-m-d H:i:s"), $m->msg);
                $m->msg = str_replace('|$lineIcon|', AdminService::$line_icon_html, $m->msg);

                $m->msg = str_replace('SELF_AUTH', '本人認證', $m->msg);
                $m->msg = str_replace('BEAUTY_AUTH', '美顏推薦', $m->msg);
                $m->msg = str_replace('FAMOUS_AUTH', '名人認證', $m->msg);
                if(strpos($org_msg,'SELF_AUTH')) {
                    $apply_date_str = $raa_service->getApplyDateVarReplaceByAuthTypeId(1);
                    if($apply_date_str) {
                        $m->msg = str_replace('APPLY_DATE',$apply_date_str , $m->msg);
                    }
                } else if(strpos($org_msg,'BEAUTY_AUTH')) {
                    $apply_date_str = $raa_service->getApplyDateVarReplaceByAuthTypeId(2);
                    if($apply_date_str)
                        $m->msg = str_replace('APPLY_DATE', $apply_date_str, $m->msg);
                } else if(strpos($org_msg,'FAMOUS_AUTH')) {
                    $apply_date_str = $raa_service->getApplyDateVarReplaceByAuthTypeId(3);
                    if($apply_date_str)
                        $m->msg = str_replace('APPLY_DATE', $apply_date_str, $m->msg);
                }

                $msglib_msg->push($m->msg);
            }
            return view('admin.users.editRealAuth_sendMsg')
                ->with('admin', $admin)
                ->with('user', $user)
                ->with('userMeta', $userMeta)
                ->with('from_user', $user)
                ->with('to_user', $admin)
                ->with('msglib', $msglib)
                ->with('msglib2', collect())
                ->with('msglib_report', $msglib_report)
                ->with('msglib_reported', null)
                ->with('msglib_msg', $msglib_msg)
                ->with('message_msg', collect())
                ->with('msglib_msg2', collect());
        } else {
            return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function showUserPictures()
    {
        return view('admin.users.userPictures');
    }

    public function searchUserPictures(Request $request)
    {
        $pics = DB::table('member_pic')
            ->leftJoin('users', 'users.id', '=', 'member_pic.member_id')
            ->leftJoin('user_meta', 'user_meta.user_id', '=', 'member_pic.member_id')
            ->selectRaw('member_pic.id, member_pic.member_id, member_pic.pic, users.name, member_pic.updated_at, users.email, users.title, users.last_login, user_meta.about, user_meta.style')
            ->whereNotNull('member_pic.pic');

        if ($request->hidden) {
            $pics = $pics->where('member_pic.isHidden', 1)->where('user_meta.isAvatarHidden', 1);
        } else {
            $pics = $pics->where('member_pic.isHidden', 0)->where('user_meta.isAvatarHidden', 0);
        }
        if ($request->date_start) {
            $pics = $pics->where('member_pic.updated_at', '>=', $request->date_start);
        }
        if ($request->date_end) {
            $pics = $pics->where('member_pic.updated_at', '<=', $request->date_end . ' 23:59:59');
        }
        if ($request->en_group) {
            $pics = $pics->where('users.engroup', $request->en_group);
        }
        if ($request->city) {
            $pics = $pics->where('user_meta.city', $request->city);
        }
        if ($request->area) {
            $pics = $pics->where('user_meta.area', $request->area);
        }
        if (isset($request->order_by) && $request->order_by == 'updated_at') {
            $pics = $pics->orderBy('member_pic.updated_at', 'desc');
        }
        if (isset($request->order_by) && $request->order_by == 'last_login') {
            $pics = $pics->orderBy('users.last_login', 'desc');
        }
        $pics = $pics->paginate(20);

        $account = array();
        foreach ($pics as $key => $pic) {
            $user = User::where('id', $pic->member_id)->get()->first();
            $userMeta = UserMeta::where('user_id', $pic->member_id)->get()->first();
            if (is_null($user)) {
                continue;
            }
            $account[$key]['user'] = $user;
            $account[$key]['userMeta'] = $userMeta;
            $account[$key]['engroup'] = $user->engroup;
            $account[$key]['isVip'] = $user->isVip();
            $account[$key]['auth_status'] = 0;
            if ($user->isPhoneAuth() == 1) $account[$key]['auth_status'] = 1;
            $account[$key]['tipcount'] = \App\Models\Tip::TipCount_ChangeGood($pic->member_id);
            $account[$key]['vip'] = \App\Models\Vip::vip_diamond($pic->member_id);
            $account[$key]['isBlocked'] = \App\Models\SimpleTables\banned_users::where('member_id', $pic->member_id)->orderBy('created_at', 'desc')->get()->first();
            if (!isset($account[$key]['isBlocked'])) {
                $account[$key]['isBlocked'] = \App\Models\BannedUsersImplicitly::where('target', $pic->member_id)->get()->first();
                if (isset($account[$key]['isBlocked'])) {
                    $account[$key]['isBlocked_implicitly'] = 1;
                }
            }

            $data = \App\Models\SimpleTables\warned_users::where('member_id', $pic->member_id)->orderBy('created_at', 'desc')->first();
            if (isset($data) && ($data->expire_date == null || $data->expire_date >= Carbon::now())) {
                $account[$key]['isAdminWarned'] = 1;
                $account[$key]['adminWarned_expireDate'] = $data->expire_date;
                $account[$key]['adminWarned_createdAt'] = $data->created_at;
            } else {
                $account[$key]['isAdminWarned'] = 0;
            }
        }

        return view(
            'admin.users.userPictures',
            [
                'pics' => $pics,
                'account' => $account,
                'en_group' => isset($request->en_group) ? $request->en_group : null,
                'order_by' => isset($request->order_by) ? $request->order_by : null,
                'city' => isset($request->city) ? $request->city : null,
                'area' => isset($request->area) ? $request->area : null,
                'hiddenSearch' => isset($request->hidden) ? true : false
            ]
        );
    }

    public function modifyUserPictures(Request $request)
    {
        //勾選加入常用列表後新增
        $addreason = $request->addreason;
        $otherReason = $request->otherReason;
        if ($addreason) {
            if (DB::table('reason_list')->where([['type', 'pic'], ['content', $otherReason]])->first() == null) {
                DB::table('reason_list')->insert(['type' => 'pic', 'content' => $otherReason]);
            }
        }
        $msglib_delpic = Msglib::selectraw('id, title, msg')->where('kind', '=', 'delpic')->get();

        if ($request->delete) {
            $datas = $this->admin->deletePicture($request);
            //新增Admin操作log
            $this->insertAdminActionLog($request->avatar_id, '刪除大頭照');

            if ($datas == null) {
                return redirect()->back()->withErrors(['沒有選擇訊息。'])->withInput();
            }
            if (!$datas) {
                return redirect()->back()->withErrors(['出現錯誤，訊息刪除失敗'])->withInput();
            } else {
                $admin = $this->admin->checkAdmin();
                if ($admin) {
                    return view('admin.users.messenger')
                        ->with('admin', $datas['admin'])
                        ->with('msgs', $datas['msgs'])
                        ->with('msgs2', $datas['msgs2'])
                        ->with('msglib_delpic', $msglib_delpic)
                        ->with('template', $datas['template']);
                } else {
                    return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
                }
            }
        } else if ($request->hide) {
            $datas = $this->admin->hidePicture($request);
            if ($datas == null) {
                return redirect()->back()->withErrors(['沒有選擇訊息。'])->withInput();
            }
            if (!$datas) {
                return redirect()->back()->withErrors(['出現錯誤，訊息刪除失敗'])->withInput();
            } else {
                $admin = $this->admin->checkAdmin();
                if ($admin) {
                    return view('admin.users.messenger')
                        ->with('admin', $datas['admin'])
                        ->with('msgs', $datas['msgs'])
                        ->with('msgs2', $datas['msgs2'])
                        ->with('msglib_delpic', $msglib_delpic)
                        ->with('template', $datas['template']);
                } else {
                    return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
                }
            }
        } else if ($request->dehide) {
            $datas = $this->admin->deHidePicture($request);
            if ($datas == null) {
                return redirect()->back()->withErrors(['沒有選擇訊息。'])->withInput();
            }
            if (!$datas) {
                return redirect()->back()->withErrors(['出現錯誤，訊息刪除失敗'])->withInput();
            } else {
                $admin = $this->admin->checkAdmin();
                if ($admin) {
                    return back();
                } else {
                    return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
                }
            }
        } else {
            return redirect()->back()->withErrors(['出現不明錯誤']);
        }
    }

    public function showReportedCountPage()
    {
        $admin = $this->admin->checkAdmin();
        if ($admin) {
            return view('admin.users.reportedCount');
        } else {
            return view('admin.users.reportedCount')->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function showReportedCountList(Request $request)
    {
        $admin = $this->admin->checkAdmin();
        if ($admin) {
            $result = $this->admin->reportedUserDetails($request);

            return view('admin.users.reportedCount')
                ->with('reportedUsers', $result['reportedUsers'])
                ->with('users', $result['users'])
                ->with('date_start', isset($request->date_start) ? $request->date_start : null)
                ->with('date_end', isset($request->date_end) ? $request->date_end : null);
        } else {
            return view('admin.users.reportedUsers')->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function showMessageSearchPage()
    {
        $admin = $this->admin->checkAdmin();
        if ($admin) {
            return view('admin.users.searchMessage');
        } else {
            return view('admin.users.searchMessage')->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function showReportedMessages(Request $request)
    {
        $admin = $this->admin->checkAdmin();
        if ($admin) {

            $date_start = $request->date_start ? $request->date_start : '0000-00-00';
            $date_end = $request->date_end ? $request->date_end . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';

            $messages = Message::withTrashed()->where(function ($q) {
                $q->where('unsend', 0)->whereNull('deleted_at');
                $q->orwhere('unsend', 1);
            })->whereBetween('created_at', array($date_start, $date_end))
                ->where('isReported', 1)
                ->orderBy('created_at', 'desc');
            $datas = $this->admin->fillMessageDatas($messages);
            return view('admin.users.searchMessage')
                ->with('reported', 1)
                ->with('results', $datas['results'])
                ->with('users', isset($datas['users']) ? $datas['users'] : null)
                ->with('reported_id', isset($request->reported_id) ? $request->reported_id : null)
                ->with('msg', isset($datas['msg']) ? $datas['msg'] : null)
                ->with('date_start', isset($datas['date_start']) ? $datas['date_start'] : null)
                ->with('date_end', isset($datas['date_end']) ? $datas['date_end'] : null);
        } else {
            return view('admin.users.searchMessage')->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    /**
     * Search members' messages.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchMessage(Request $request)
    {
        if ($request->time == 'send_time') {
            $datas = $this->admin->searchMessageBySendTime($request);
        } else {
            if (!$request->msg && !$request->date_start && !$request->date_end) {
                $results = null;
            } else {
                $msg = $request->msg ? $request->msg : '';
                $date_start = $request->date_start ? $request->date_start : '0000-00-00';
                $date_end = $request->date_end ? $request->date_end : date('Y-m-d');

                $results = Message::withTrashed()->where(function ($q) {
                    $q->where('unsend', 0)->whereNull('deleted_at');
                    $q->orwhere('unsend', 1);
                })
                    ->select('*')
                    ->where('content', 'like', '%' . $msg . '%')
                    ->whereBetween('created_at', array($date_start . ' 00:00', $date_end . ' 23:59'));
            }
            $senders = array(); //先宣告 否則報錯
            if ($results != null) {
                $temp = $results->get()->toArray();
                //Rearranges the messages query results.
                $results = array();

                array_walk($temp, function (&$value) use (&$results) {
                    $results[$value['id']] = $value;
                });

                //Senders' id.
                $to_id = array();
                //Receivers' id.
                $from_id = array();

                foreach ($results as $result) {
                    if (!in_array($result['to_id'], $to_id)) {
                        array_push($to_id, $result['to_id']);
                    }
                    if (!in_array($result['from_id'], $from_id)) {
                        array_push($from_id, $result['from_id']);
                    }
                }
                //Senders' meta.
                foreach ($from_id as $key => $id) {
                    $sender = User::where('id', '=', $id)->get()->first();
                    // $vip_tmp = $sender->isVip() ? true : false;
                    if (is_null($sender)) {
                        continue;
                    }
                    $senders[$key] = $sender->toArray();
                    $senders[$key]['vip'] = Vip::vip_diamond($id);
                    $senders[$key]['isBlocked'] = banned_users::where('member_id', $id)->orderBy('created_at', 'desc')->get()->first();
                    $senders[$key]['tipcount'] = Tip::TipCount_ChangeGood($id);
                    //被檢舉者近一月曾被不同人檢舉次數
                    $tmp = $this->admin->reports_month($id);
                    $senders[$key]['picsResult'] = $tmp['picsResult'];
                    $senders[$key]['messagesResult'] = $tmp['messagesResult'];
                    $senders[$key]['reportsResult'] = $tmp['reportsResult'];
                }
                //Fills message ids to each sender.
                foreach ($senders as $key => $sender) {

                    $senders[$key]['messages'] = array();
                    foreach ($results as $result) {
                        if ($result['from_id'] == $sender['id']) {
                            array_push($senders[$key]['messages'], $result);
                        }
                    }
                }
                //Receivers' name.
                $receivers = array();
                foreach ($to_id as $id) {
                    $receivers[$id] = array();
                }
                foreach ($receivers as $id => $receiver) {
                    $name = User::select('name', 'engroup')
                        ->where('id', '=', $id)
                        ->get()->first();
                    if ($name != null) {
                        $receivers[$id]['name'] = $name->name;
                        $receivers[$id]['tipcount'] = Tip::TipCount_ChangeGood($id);
                        $receivers[$id]['vip'] = Vip::vip_diamond($id);
                        $receivers[$id]['isBlockedReceiver'] = banned_users::where('member_id', $id)->orderBy('created_at', 'desc')->get()->first();
                        $receivers[$id]['engroup'] = $name->engroup;
                    } else {
                        $receivers[$id] = '資料庫沒有資料';
                    }
                }

                if ($request->time == 'created_at') {
                    $senders = collect($senders)->sortBy('created_at', true, true)->reverse()->toArray();
                }
                if ($request->time == 'login_time') {
                    $senders = collect($senders)->sortBy('last_login', true, true)->reverse()->toArray();
                }
                if ($request->member_type == 'vip') {
                    $senders = collect($senders)->sortBy('vip', true, true)->reverse()->toArray();
                }
                if ($request->member_type == 'banned') {
                    $senders = collect($senders)->sortBy('isBlocked')->reverse()->toArray();
                }
            }
        }
        if (isset($datas)) {
            return view('admin.users.searchMessage')
                ->with('results', $datas['results'])
                ->with('users', isset($datas['users']) ? $datas['users'] : null)
                ->with('msg', isset($datas['msg']) ? $datas['msg'] : null)
                ->with('date_start', isset($datas['date_start']) ? $datas['date_start'] : null)
                ->with('date_end', isset($datas['date_end']) ? $datas['date_end'] : null)
                ->with('time', isset($request->time) ? $request->time : null)
                ->with('member_type', isset($request->member_type) ? $request->member_type : null);
        } else {
            return view('admin.users.searchMessage')
                ->with('senders', $senders)
                ->with('receivers', isset($receivers) ? $receivers : null)
                ->with('msg', isset($request->msg) ? $request->msg : null)
                ->with('date_start', isset($request->date_start) ? $request->date_start : null)
                ->with('date_end', isset($request->date_end) ? $request->date_end : null)
                ->with('time', isset($request->time) ? $request->time : null)
                ->with('member_type', isset($request->member_type) ? $request->member_type : null);
        }
    }

    /**
     * Determines which controller the request should go.
     *
     * @return \Illuminate\Http\Response
     */
    public function modifyMessage(Request $request)
    {
        if ($request->delete == 1 && $request->edit == 0) {
            $datas = $this->admin->deleteMessage($request);
            if ($datas == null) {
                return redirect()->back()->withErrors(['沒有選擇訊息。'])->withInput();
            }
            if (!$datas) {
                return redirect()->back()->withErrors(['出現錯誤，訊息刪除失敗'])->withInput();
            } else {
                $admin = $this->admin->checkAdmin();
                if ($admin) {
                    return view('admin.users.messenger')
                        ->with('admin', $datas['admin'])
                        ->with('msgs', $datas['msgs'])
                        ->with('template', $datas['template']);
                } else {
                    return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
                }
            }
        } else if ($request->edit == 1 && $request->delete == 0) {
            $admin = $this->admin->checkAdmin();
            if ($admin) {
                $data = $this->admin->renderMessages($request);
                return view('admin.users.editMessage')->with('data', $data);
            } else {
                return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
            }
        } else {
            return redirect()->back()->withErrors(['出現不明錯誤']);
        }
    }

    public function deleteMessage(Request $request)
    {
        //        if ($request->delete == 1 && $request->edit == 0) {
        $datas = $this->admin->deleteMessage($request);
        if ($datas == null) {
            return redirect()->back()->withErrors(['沒有選擇訊息。'])->withInput();
        }
        if (!$datas) {
            return redirect()->back()->withErrors(['出現錯誤，訊息刪除失敗'])->withInput();
        } else {
            $admin = $this->admin->checkAdmin();
            if ($admin) {
                return back()->with('message', '刪除成功');
                //                    return view('admin.users.messenger')
                //                        ->with('admin', $datas['admin'])
                //                        ->with('msgs', $datas['msgs'])
                //                        ->with('template', $datas['template']);
            } else {
                return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
            }
        }
        //        } else if ($request->edit == 1 && $request->delete == 0) {
        //            $admin = $this->admin->checkAdmin();
        //            if ($admin) {
        //                $data = $this->admin->renderMessages($request);
        //                return view('admin.users.editMessage')->with('data', $data);
        //            } else {
        //                return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        //            }
        //        } else {
        //            return redirect()->back()->withErrors(['出現不明錯誤']);
        //        }
    }

    public function editMessage(Request $request)
    {
        $messages = $this->admin->editMessageThenReturnIds($request);
        $admin = $this->admin->checkAdmin();
        if ($admin) {
            $datas = $this->admin->sendEditedNotice($request, $messages);
            return view('admin.users.messenger')
                ->with('admin', $datas['admin'])
                ->with('msgs', $datas['msgs'])
                ->with('template', $datas['template']);
        } else {
            return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function handleMessage(Request $request)
    {
        $admin = $this->admin->checkAdmin();

        if (!$admin) {
            return response()->json([
                'message' => '找不到暱稱含有「站長」的使用者！請先新增再執行此步驟'
            ], 403);
        }

        $messageId = $request->id;
        $toId = $request->to_id;
        $message = $this->messageService->getMessageById($messageId);

        $handle = ($request->handle!=null)?$request->handle:intval($message->handle ==0);
        if (!$message) {
            return response()->json([
                'message' => '找不到此訊息'
            ], 403);
        }
        $this->messageService->setMessageHandling($messageId,$handle);

        return response('', 201);
    }

    public function showBannedList()
    {
        $list = banned_users::join('users', 'users.id', '=', 'banned_users.member_id')
            ->select('banned_users.*', 'users.name', 'users.email', 'banned_users.reason')->orderBy('banned_users.created_at', 'desc')->paginate(100);

        //以reason回推出原本自動封鎖id
        $set_auto_ban_id_list = [];
        foreach($list as $banned_user)
        {
            $reason = $banned_user->reason;
            if(strpos($reason, '系統原因(') !== false)
            {
                $temp_str = str_replace('系統原因(', '', $reason);
                if(strpos($temp_str, ')') !== false)
                {
                    $set_auto_ban_id_list[] = $banned_user->set_auto_ban_id =  intval(str_replace(')', '', $temp_str));
                }
                else
                {
                    $banned_user->set_auto_ban_id = 0;
                }
            }
            else
            {
                $banned_user->set_auto_ban_id = 0;
            }
        }
        $set_auto_ban_id_list = array_unique($set_auto_ban_id_list);
        $set_auto_ban_list = SetAutoBan::whereIn('id', $set_auto_ban_id_list)->get()->keyBy('id')->toArray();

        return view('admin.users.bannedList')
                ->with('list', $list)
                ->with('set_auto_ban_list', $set_auto_ban_list)
                ;
    }

    /**
     * Show the form for inviting a customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInvite()
    {
        return view('admin.users.invite');
    }

    /**
     * Show the form for messaging to a member.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAdminMessenger($id)
    {
        $admin = $this->admin->checkAdmin();
        if ($admin) {
            $user = $this->service->find($id);
            $msglib = Msglib::where('kind', 'smsg')->get();
            $msglib_msg = collect();
            foreach ($msglib as $m) {
                $m->msg = str_replace('|$report|', $user->name, $m->msg);
                $m->msg = str_replace('NAME', $user->name, $m->msg);
                $m->msg = str_replace('NOW_TIME', date("Y-m-d H:i:s"), $m->msg);
                $m->msg = str_replace('LINE_ICON', AdminService::$line_icon_html, $m->msg);
                $m->msg = str_replace('|$responseTime|', date("Y-m-d H:i:s"), $m->msg);
                $m->msg = str_replace('|$reportTime|', date("Y-m-d H:i:s"), $m->msg);
                $m->msg = str_replace('|$lineIcon|', AdminService::$line_icon_html, $m->msg);
                $msglib_msg->push($m->msg);
            }
            return view('admin.users.adminMessenger')
                ->with('admin', $admin)
                ->with('user', $user)
                ->with('from_user', $user)
                ->with('msglib', $msglib)
                ->with('msglib_msg', $msglib_msg);
        } else {
            return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function showAdminMessengerWithMessageId($id, $mid)
    {
        $admin = $this->admin->checkAdmin();
        if ($admin) {
            $msglib = Msglib::get();
            $msglib_report = Msglib::selectraw('id, title, msg')->where('kind', '=', 'report')->get();
            $msglib_reported = Msglib::selectraw('id, title, msg')->where('kind', '=', 'reported')->get();
            $msglib_all = Msglib::selectraw('id, title, msg')->get();
            $msglib = Msglib::get();
            $msglib2 = Msglib::get();
            $msglib3 = Msglib::selectraw('msg')->get();
            /*檢舉者 */
            $user = $this->service->find($id);
            $message = Message::withTrashed()->where(function ($q) {
                $q->where('unsend', 0)->whereNull('deleted_at');
                $q->orwhere('unsend', 1);
            })->where('id', $mid)->get()->first();
            $sender = User::where('id', is_null($message) ? '' : $message->from_id)->get()->first();
            /*被檢舉者*/
            $to_user_id = Message::withTrashed()->where(function ($q) {
                $q->where('unsend', 0)->whereNull('deleted_at');
                $q->orwhere('unsend', 1);
            })->where('id', $mid)->get()->first();
            $to_user = $this->service->find(is_null($to_user_id) ? '' : $to_user_id->to_id);
            $message_msg = Message::withTrashed()->where(function ($q) {
                $q->where('unsend', 0)->whereNull('deleted_at');
                $q->orwhere('unsend', 1);
            })->where('to_id', is_null($to_user) ? '' : $to_user->id)->where('from_id', is_null($user) ? '' : $user->id)->get();
            if (!$msglib_reported->isEmpty()) {
                foreach ($msglib_reported as $key => $msg) {
                    $msglib_msg[$key] = str_replace('|$report|', is_null($to_user) ? '' : $to_user->name, $msg['msg']);
                    $msglib_msg[$key] = str_replace('NAME', is_null($to_user) ? '' : $to_user->name, $msglib_msg[$key]);
                    $msglib_msg[$key] = str_replace('|$reported|', is_null($sender) ? '' : $sender->name, $msglib_msg[$key]);
                    $msglib_msg[$key] = str_replace('|$reportTime|', isset($message_msg[0]) ? $message_msg[0]->created_at : null, $msglib_msg[$key]);
                    $msglib_msg[$key] = str_replace('|$responseTime|', date("Y-m-d H:i:s"), $msglib_msg[$key]);
                    $msglib_msg[$key] = str_replace('|$lineIcon|', AdminService::$line_icon_html, $msglib_msg[$key]);
                    $msglib_msg[$key] = str_replace('NOW_TIME', date("Y-m-d H:i:s"), $msglib_msg[$key]);
                    $msglib_msg[$key] = str_replace('LINE_ICON', AdminService::$line_icon_html, $msglib_msg[$key]);
                }
            } else {
                foreach ($msglib_all as $key => $msg) {
                    $msglib_msg[$key] = $msg['msg'];
                }
            }
            if (!$msglib_report->isEmpty()) {
                foreach ($msglib_report as $key => $msg) {
                    $msglib_msg2[$key] = str_replace('NAME', is_null($to_user) ? '' : $to_user->name, $msg['msg']);
                    $msglib_msg2[$key] = str_replace('|$report|', is_null($to_user) ? '' : $to_user->name, $msglib_msg2[$key]);
                    $msglib_msg2[$key] = str_replace('|$reported|', is_null($sender) ? '' : $sender->name, $msglib_msg2[$key]);
                    $msglib_msg2[$key] = str_replace('|$reportTime|', isset($message_msg[0]) ? $message_msg[0]->created_at : null, $msglib_msg2[$key]);
                    $msglib_msg2[$key] = str_replace('|$responseTime|', date("Y-m-d H:i:s"), $msglib_msg2[$key]);
                    $msglib_msg2[$key] = str_replace('|$lineIcon|', AdminService::$line_icon_html, $msglib_msg2[$key]);
                    $msglib_msg2[$key] = str_replace('NOW_TIME', date("Y-m-d H:i:s"), $msglib_msg2[$key]);
                    $msglib_msg2[$key] = str_replace('LINE_ICON', AdminService::$line_icon_html, $msglib_msg2[$key]);
                }
            } else {
                foreach ($msglib_all as $key => $msg) {
                    $msglib_msg2[$key] = $msg['msg'];
                }
            }

            return view('admin.users.messenger')
                ->with('admin', $admin)
                ->with('user', $user)
                ->with('to_user', $to_user)
                ->with('from_user', $sender)
                ->with('message', $message)
                ->with('senderName', is_null($sender) ? '' : $sender->name)
                ->with('msglib', $msglib_reported)
                ->with('msglib2', $msglib_report)
                ->with('msglib_report', $msglib_report)
                ->with('msglib_reported', $msglib_reported)
                ->with('msglib_msg', $msglib_msg)
                ->with('message_msg', $message_msg)
                ->with('msglib_msg2', $msglib_msg2);
        } else {
            return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function showAdminMessageRecord($id)
    {
        $admin = $this->admin->checkAdmin();
        if ($admin) {
            $user = User::where('id', $id)->get()->first();
            if(request()->input('from_advInfo') and request()->input('from_advInfo') == 1 and !$user->is_admin_chat_channel_open) {
                $controller = resolve(self::class);
                $request = new Request();
                $request->replace([
                    "_token" => csrf_token(),
                    "user_id" => $id,
                    "is_admin_chat_channel_open" => !$user->is_admin_chat_channel_open
                ]);
                $controller->TogglerIsChat($request);
                $user->refresh();
            }

            if(request()->input('from_videoChat') and request()->input('from_videoChat') == 1) {
                if(auth()->user()->id!=1049) {
                    return redirect()->route('users/video_chat_verify');
                }

                $this->insertAdminActionLog($user->id, '視訊驗證 - 進入站長與 user 對話');
            }
            $messages = Message::allToFromSenderChatWithAdmin($id, 1049)->orderBy('id', 'asc')->get();

            $admin = User::where('id', 1049)->get()->first();

            $user->tipcount = Tip::TipCount_ChangeGood($user->id);
            $admin->tipcount = Tip::TipCount_ChangeGood($admin->id);

            $user->vip = Vip::vip_diamond($user->id);
            $admin->vip = Vip::vip_diamond($admin->id);

            $user->isBlocked = banned_users::where('member_id', $user->id)->orderBy('created_at', 'desc')->get()->first();
            $user->isBlockedReceiver = banned_users::where('member_id', $user->id)->orderBy('created_at', 'desc')->get()->first();

            $admin->isBlocked = banned_users::where('member_id', $admin->id)->orderBy('created_at', 'desc')->get()->first();
            $admin->isBlockedReceiver = banned_users::where('member_id', $admin->id)->orderBy('created_at', 'desc')->get()->first();


            return view('admin.users.messageRecord')
                ->with('messages', $messages)
                ->with('user', $user)
                ->with('admin', $admin);
        } else {
            return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }

    }

    public function TogglerIsChat(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $user->is_admin_chat_channel_open = $request->is_admin_chat_channel_open;
        $user->save();

        return response()->json($user);
    }

    public function showAdminMessageAllRecord()
    {
        $admin = $this->admin->checkAdmin();
        $chat_with_admin_users =
            User::with('message_sent_to_admin','message_accepted_from_admin')
                ->whereHas('message_sent_to_admin')
                ->orWhereHas('message_accepted_from_admin')
                ->get()
                ->sortByDesc(function ($cas_user, $key) {
                        return max(
                                $cas_user->message_sent_to_admin->sortByDesc('created_at')->first()
                                ,$cas_user->message_accepted_from_admin->sortByDesc('created_at')->first()
                            );
                    })
                ;

        return view('admin.users.messageAllRecordChatWithAdmin')
            ->with('chat_with_admin_users', $chat_with_admin_users)
            ->with('admin',$admin);
    }

    public function getAdminMessageRecordDetailFromRoomId(Request $request)
    {
        $ref_user = null;
        $room_id = $request->cwa_user_id;
        $admin=$this->admin->checkAdmin();

        $room_id = $request->room_id;

        $query = Message::withTrashed()
            ->where('room_id', $room_id)
            ->where('created_at', '>=', \Carbon\Carbon::parse("180 days ago")->toDateTimeString())
            ->orderByDesc('created_at')
            ->where('chat_with_admin',1);

        $messages = $query->get();

        $first_message = $messages->first();

        if($first_message) {
            if($first_message->from_id==$admin->id) {
                $ref_user = $first_message->receiver;

            } else if($first_message->to_id==$admin->id) {
                $ref_user = $first_message->sender;
            } else {
                echo '異常錯誤!非管理者有參與的訊息紀錄';
            }
        }
        return view('admin.users.messageAllRecordChatWithAdminDetail')
            ->with('messages', $messages)
            ->with('ref_user',$ref_user)
            ->with('admin',$admin);;

    }

    public function showAdminMessengerWithReportedId($id, $reported_id, $pic_id = null, $isPic = null, $isReported = null)
    {
        // $isPic 為被檢舉之表格 ID
        $admin = $this->admin->checkAdmin();
        if ($admin) {
            $msglib = Msglib::get();
            $msglib3 = Msglib::selectraw('msg')->get();
            $msglib_report = Msglib::selectraw('id, title, msg')->where('kind', '=', 'report')->get();
            $msglib_reported = Msglib::selectraw('id, title, msg')->where('kind', '=', 'reported')->get();
            $report = Reported::where('member_id', $id)->where('reported_id', $reported_id)->get()->first();
            if (isset($isPic)) {
                $a = ReportedAvatar::where('reporter_id', $id)->where('reported_user_id', $reported_id)->get()->first();
                $b = ReportedPic::where('reporter_id', $id)->get()->first();
                if (isset($a) && isset($b)) {
                    $report = $a->id == $isPic ? $a : $b;
                } else {
                    $report = isset($a) ? $a : $b;
                }
            }
            if (is_null($report)) {
                $report = Reported::where('member_id', $id)->where('reported_id', $reported_id);
                if ($pic_id) {
                    $report->where('id', $pic_id);
                }
                $report = $report->first();
            }
            /*檢舉者*/
            $user = $this->service->find($id);
            /*被檢舉者 */
            $reported = User::where('id', $reported_id)->get()->first();
            foreach ($msglib_reported as $key => $msg) {
                $msglib_msg[$key] = str_replace('|$report|', $user->name, $msg['msg']);
                $msglib_msg[$key] = str_replace('NAME', $user->name, $msglib_msg[$key]);
                if ($reported) {
                    $msglib_msg[$key] = str_replace('|$reported|', $reported->name, $msglib_msg[$key]);
                }
                $msglib_msg[$key] = str_replace('|$reportTime|', isset($report->created_at) ? $report->created_at : null, $msglib_msg[$key]);
                $msglib_msg[$key] = str_replace('|$responseTime|', date("Y-m-d H:i:s"), $msglib_msg[$key]);
                $msglib_msg[$key] = str_replace('|$lineIcon|', AdminService::$line_icon_html, $msglib_msg[$key]);
                $msglib_msg[$key] = str_replace('NOW_TIME', date("Y-m-d H:i:s"), $msglib_msg[$key]);
                $msglib_msg[$key] = str_replace('LINE_ICON', AdminService::$line_icon_html, $msglib_msg[$key]);
            }
            foreach ($msglib_report as $key => $msg) {
                $msglib_msg2[$key] = str_replace('|$report|', $user->name, $msg['msg']);
                $msglib_msg2[$key] = str_replace('NAME', $user->name,  $msglib_msg2[$key]);
                if ($reported) {
                    $msglib_msg2[$key] = str_replace('|$reported|', $reported->name, $msglib_msg2[$key]);
                }
                $msglib_msg2[$key] = str_replace('|$reportTime|', isset($report->created_at) ? $report->created_at : null, $msglib_msg2[$key]);
                $msglib_msg2[$key] = str_replace('|$responseTime|', date("Y-m-d H:i:s"), $msglib_msg2[$key]);
                $msglib_msg2[$key] = str_replace('|$lineIcon|', AdminService::$line_icon_html, $msglib_msg2[$key]);
                $msglib_msg2[$key] = str_replace('NOW_TIME', date("Y-m-d H:i:s"), $msglib_msg2[$key]);
                $msglib_msg2[$key] = str_replace('LINE_ICON', AdminService::$line_icon_html, $msglib_msg2[$key]);
            }
            return view('admin.users.messenger')
                ->with('admin', $admin)
                ->with('message', 'REPORTEDUSERONLY')
                ->with('report', $report)
                ->with('user', $reported)
                ->with('reportedName', isset($reported->name) ? $reported->name : '沒有資料')
                ->with('from_user', $reported)
                ->with('to_user', $user)
                ->with('isPic', $isPic)
                ->with('isReported', $isReported)
                ->with('isReportedId', $reported_id)
                ->with('pic_id', $pic_id)
                ->with('msglib', $msglib_reported)
                ->with('msglib2', $msglib_report)
                ->with('msglib_report', $msglib_report)
                ->with('msglib_reported', $msglib_reported)
                ->with('msglib_msg', isset($msglib_msg) ? $msglib_msg : null)
                ->with('msglib_msg2', isset($msglib_msg2) ? $msglib_msg2 : null);
        } else {
            return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    /**
     * Show the admin messenger after anonymous content checked.
     *
     * @param  string  $id
     * @param  string  $evaluationId
     * @return \Illumninate\Http\Response
     */
    public function showAdminMessengerAfterAnonymousContentChecked($id, $evaluationId)
    {
        if (!$admin = $this->admin->checkAdmin()) {
            return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }

        try {
            $evaluation = Evaluation::withTrashed()->findOrFail($evaluationId);
        } catch (\Exception $e) {
            return '找不到該筆評價資料！';
        }
        $user = $this->service->find($evaluation->from_id);
        $toUser = $this->service->find($evaluation->to_id);

        $msglibs = Msglib::kind(Msglib::KIND_ANONYMOUS)->get();

        $msglibs->each(function (Msglib $msglib) use ($user, $toUser, $evaluation) {
            $msglib->msg = $this->simpleRender($msglib->msg, [
                'NOW_TIME' => date("Y-m-d H:i:s"),
                'TIME' => $evaluation->created_at->format('Y-m-d H:i:s'),
                'TO_NAME' => $toUser->name,
                'NAME' => $user->name,
                'LINE_ICON' => AdminService::$line_icon_html,
            ]);
        });

        return view('admin.users.adminMessenger', [
            'admin' => $admin,
            'user' => $user,
            'from_user' => $user,
            'msglib' => $msglibs,
            'msglib_msg' => $msglibs->pluck('msg'),
        ]);
    }

    /**
     * @param  string  $template
     * @param  array  $data
     * @return string
     */
    private function simpleRender(string $template, array $data): string
    {
        foreach ($data as $key => $value) {
            $template = str_replace($key, $value, $template);
        }

        return $template;
    }

    /**
     * Message to a member.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendAdminMessage(Request $request,RealAuthAdminService $raa_service, $id)
    {
        $payload = $request->all();
        if(!is_null($request->file('images')) && count($request->file('images'))){
            $payload['msg'] = $payload['msg']??'';
        }
        if($request->has('chat_with_admin')) {
            $p_rs = Message::post($payload['admin_id'], $id, $payload['msg'],true,0,null, $payload['chat_with_admin']);
        } else {
            $p_rs = Message::post($payload['admin_id'], $id, $payload['msg']);
        }
        
        if(!is_null($request->file('images')) && count($request->file('images'))){
            $msgController = resolve(Message_newController::class);
            $upload_rs = $msgController->message_pic_save($p_rs->id, $request->file('images'));
            if($upload_rs) {
                $p_rs->refresh();
            }
        }
        
        $raa_service->riseByUserId($id);
        if($p_rs) {
            $raa_service->savePatchByMsgEntryAndReqArr($p_rs,$payload);
        }

        if ($request->rollback == 1) {
            if ($request->msg_id) {
                $m = Message::withTrashed()->where(function ($q) {
                    $q->where('unsend', 0)->whereNull('deleted_at');
                    $q->orwhere('unsend', 1);
                })->where('id', $request->msg_id)->get()->first();
                $m->isReported = 0;
                $m->reportContent = '';
                $m->save();
            }
            // if($request->report_id){
            //     $m = Reported::where('id', $request->report_id)->get()->first();
            //     $m->delete();
            // }
            // if($request->pic_id){
            //     if(str_contains($request->pic_id, 'avatar')){
            //         $a_id = substr($request->pic_id, 6, strlen($request->pic_id));
            //         $a = ReportedAvatar::where('id', $a_id)->get()->first();
            //         $a->delete();
            //     }
            //     else{
            //         $p = ReportedPic::where('id', $request->pic_id)->get()->first();
            //         $p->delete();
            //     }
            // }
        }
        //新增Admin操作log
        $cat_prefix = '';
        if(request()->from_videoChat && auth()->user()->id==1049) {
            $cat_prefix  = '視訊驗證 - ';
        }
        $this->insertAdminActionLog($id, $cat_prefix.'撰寫站長訊息');

        return back()->with('message', '傳送成功');
    }

    /**
     * Messages to members.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendAdminMessageMultiple(Request $request)
    {
        //$payload = $request->all();
        $admin_id = $request->admin_id;
        $to_ids = array();
        $msgs = array();
        foreach ($request->msg as $msg) {
            array_push($msgs, $msg);
        }
        foreach ($request->to as $id) {
            array_push($to_ids, $id);
        }
        //try{
        foreach ($msgs as $key => $msg) {
            Message::post($admin_id, $to_ids[$key], $msg);
        }
        //}
        if (isset($request->back)) {
            return '<h1>傳送成功</h1>';
        }

        return redirect()->route('users/message/search')->with('message', '傳送成功');
    }

    public function showMessagesBetween($id1, $id2)
    {
        $messages = Message::allToFromSenderAdmin($id1, $id2);
        $id1 = User::where('id', $id1)->get()->first();
        $id2 = User::where('id', $id2)->get()->first();

        $id1->tipcount = Tip::TipCount_ChangeGood($id1->id);
        $id2->tipcount = Tip::TipCount_ChangeGood($id2->id);

        $id1->vip = Vip::vip_diamond($id1->id);
        $id2->vip = Vip::vip_diamond($id2->id);

        $id1->isBlocked = banned_users::where('member_id', $id1->id)->orderBy('created_at', 'desc')->get()->first();
        $id1->isBlockedReceiver = banned_users::where('member_id', $id1->id)->orderBy('created_at', 'desc')->get()->first();

        $id2->isBlocked = banned_users::where('member_id', $id2->id)->orderBy('created_at', 'desc')->get()->first();
        $id2->isBlockedReceiver = banned_users::where('member_id', $id2->id)->orderBy('created_at', 'desc')->get()->first();


        return view('admin.users.showMessagesBetween', compact('messages', 'id1', 'id2'));
    }

    /**
     * Show the form for inviting a customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function postInvite(UserInviteRequest $request)
    {
        $result = $this->service->invite($request->except(['_token', '_method']));

        if ($result) {
            return redirect('admin/users')->with('message', 'Successfully invited');
        }

        return back()->with('error', 'Failed to invite');
    }

    public function showUserSwitch()
    {
        return view('admin.users.switch');
    }

    public function switchSearch(Request $request)
    {
        if ($request->email) {
            $user = $this->service->findByEmail($request->email);
        }
        if ($request->name) {
            $user = $this->service->findByName($request->name);
        }
        return view('admin.users.switch')->with('user', $user);
    }

    /**
     * Switch to a different User profile
     *
     * @return \Illuminate\Http\Response
     */
    public function switchToUser($id)
    {
        //新增Admin操作log
        $this->insertAdminActionLog($id, '切換成此會員前台');
        if ($this->service->switchToUser($id)) {
            return redirect('dashboard')->with('message', '成功切換使用者');
        }

        return redirect('dashboard')->with('message', '無法切換使用者');
    }

    /**
     * Switch back to your original user
     *
     * @return \Illuminate\Http\Response
     */
    public function switchUserBack()
    {
        if ($this->service->switchUserBack()) {
            return back()->with('message', '成功切換回原使用者');
        }

        return back()->with('message', '無法切換回原使用者');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->service->find($id);
        return view('admin.users.edit')->with('user', $user);
    }

    /**
     * Save the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function saveAdvInfo(Request $request, $id)
    {
        //$result = $this->service->update($id, $request->except(['_token', '_method']));
        $result = $this->service->update($id, $request->all());

        if ($result) {
            //新增Admin操作log
            $this->insertAdminActionLog($id, '修改會員資本資料');
            return back()->with('message', '成功更新會員資料');
        }

        return back()->withErrors(['無法更新會員資料']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->service->destroy($id);

        if ($result) {
            return redirect('admin/users')->with('message', 'Successfully deleted');
        }

        return redirect('admin/users')->with('message', 'Failed to delete');
    }

    /**
     * Shows admin announcement page.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAdminAnnouncement()
    {
        $a = AdminAnnounce::orderBy('sequence', 'asc')->get()->all();
        return view('admin.adminannouncement')->with('announce', $a);
    }

    public function showFaq(Request $request, FaqService $service)
    {
        $data['service'] = $service->fillQuestionList();
        $data['entry_list'] = $data['service']->question_list;
        $data['count_down_time'] = $data['service']->getCountDownTime();
        return view('admin.faq', $data);
    }

    public function showNewFaq(Request $request, FaqService $service)
    {
        $data['service'] = $service->fillGroupList();
        $data['group_list_set']['1_1'] = $data['service']->getGroupListByEngroupVip('1_1');
        $data['group_list_set']['1_0'] = $data['service']->getGroupListByEngroupVip('1_0');
        $data['group_list_set']['2_-1'] = $data['service']->getGroupListByEngroupVip('2_-1');
        $data['engroup_vip_words'] = $data['service']->getEngroupVipWord();
        $data['group_target_code_list'] = $data['service']->group_target_code_list();
        $data['question_type_list'] = $data['service']->question_type_list();
        return view('admin.faq_new', $data);
    }

    public function newFaq(Request $request, FaqService $service)
    {
        if ($service->addQuestion($request)) {
            $theGroupEntry = $service->question_entry()->faq_group;
            return redirect('admin/faq?engroupvip=' . ($theGroupEntry->engroup) . '_' . $theGroupEntry->is_vip)
                ->with('message', '成功新增FAQ');
        } else {
            return redirect('admin/faq?engroupvip=' . ($service->riseByGroupId($request->group_id)->group_entry()->engroup) . '_' . $service->group_entry()->is_vip)
                ->withErrors(['出現不明錯誤，無法新增FAQ']);
        }
    }

    public function showFaqEdit(FaqService $service, $id)
    {
        $data['service'] = $service->riseByQuestionId($id)->fillGroupList();
        $data['entry'] =  $data['service']->question_entry();
        $data['group_target_code_list'] = $data['service']->group_target_code_list();
        $data['group_list_set']['1_1'] = $data['service']->getGroupListByEngroupVip('1_1');
        $data['group_list_set']['1_0'] = $data['service']->getGroupListByEngroupVip('1_0');
        $data['group_list_set']['2_-1'] = $data['service']->getGroupListByEngroupVip('2_-1');
        $data['engroup_vip_words'] = $data['service']->getEngroupVipWord();
        return view('admin.faq_edit', $data);
    }

    public function saveFaq(Request $request, FaqService $service)
    {
        if ($service->saveQuestion($request)) {
            $theGroupEntry = $service->question_entry()->faq_group;
            return redirect('admin/faq?engroupvip=' . ($theGroupEntry->engroup) . '_' . $theGroupEntry->is_vip)
                ->with('message', '成功修改FAQ');
        } else {
            return redirect('admin/faq?engroupvip=' . ($service->riseByGroupId($request->group_id)->group_entry()->engroup) . '_' . $service->group_entry()->is_vip)
                ->withErrors(['出現不明錯誤，無法修改FAQ']);
        }
    }

    public function saveAnsFromFaq(Request $request, FaqService $service)
    {
        if ($service->riseByQuestionId($request->question_id)->saveRegularAns($request)) {
            $theGroupEntry = $service->question_entry()->faq_group;
            return redirect('admin/faq?engroupvip=' . ($theGroupEntry->engroup) . '_' . $theGroupEntry->is_vip)
                ->with('message', '成功修改FAQ');
        } else {
            return redirect('admin/faq?engroupvip=' . ($service->riseByGroupId($request->group_id)->group_entry()->engroup) . '_' . $service->group_entry()->is_vip)
                ->withErrors(['出現不明錯誤，無法修改FAQ']);
        }
    }

    public function saveSettingFromFaq(Request $request, FaqService $service)
    {
        $rs = $service->saveSetting($request);
        if ($rs) {
            $theGroupEntry = $service->question_entry()->faq_group;
            return back()->with('message', '成功修改FAQ設定');
        } else if ($rs === false) {
            return back()->withErrors(['出現不明錯誤，無法修改FAQ設定']);
        } else {
            return back();
        }
    }

    public function deleteFaq(Request $request, FaqService $service)
    {
        if ($service->riseByQuestionId($request->id)->delQuestion()) {
            return redirect('admin/faq?engroupvip=' . ($service->group_entry()->engroup ?? '1') . '_' . $service->group_entry()->is_vip ?? '1')
                ->with('message', '成功刪除FAQ');
        } else {
            return redirect('admin/faq?engroupvip=' . ($service->group_entry()->engroup ?? '1') . '_' . $service->group_entry()->is_vip ?? '1')
                ->withErrors(['出現不明錯誤，無法刪除FAQ']);
        }
    }

    public function showFaqGroup(Request $request, FaqService $service)
    {
        if (!$request->engroupvip) {
            return redirect('admin/faq_group?engroupvip=1_1');
        }
        $data['service'] = $service->fillGroupList($request);
        $data['entry_list'] = $data['service']->group_list;
        $data['default_qstring'] = $data['service']->getEngroupVipQueryString('?', request());
        $data['engroup_vip_words'] = $data['service']->getEngroupVipWord();
        $data['group_target_code_list'] = $data['service']->group_target_code_list();
        return view('admin.faq_group', $data);
    }

    public function showNewFaqGroup(Request $request, FaqService $service)
    {
        $data['service'] = $service;
        $data['default_qstring'] = $data['service']->getEngroupVipQueryString('?', request());
        return view('admin.faq_group_new', $data);
    }

    public function newFaqGroup(Request $request, FaqService $service)
    {
        if ($service->addGroup($request)) {
            return redirect('admin/faq_group' . $service->getEngroupVipQueryString('?', $service->group_entry()))
                ->with('message', '成功新增FAQ組別');
        } else {
            return redirect('admin/faq_group' . $service->getEngroupVipQueryString('?', $service->group_entry()))
                ->withErrors(['出現不明錯誤，無法新增FAQ組別']);
        }
    }

    public function saveFaqGroupAct(Request $request, FaqService $service)
    {
        $rs = false;
        $act = $request->act ?? [];
        $old_act = $request->old_act ?? [];
        $list_group_id = $request->list_group_id ?? [];
        $to_act = array_diff($act, $old_act);
        $allow_to_act = [];
        $forbid_to_act = [];
        foreach ($to_act as $k => $v) {
            $now_check_group = $service->slotByGroupId($v)->group_entry();
            if (!($now_check_group ?? null)) continue;
            $now_check_group->renewHasAnswer();
            if ($now_check_group->isRealHasAnswer()) {
                $allow_to_act[] = $v;
            } else {
                $forbid_to_act[] = $v;
            }
        }
        $not_act = array_diff($list_group_id, $act);
        $to_not_act = array_intersect($not_act, $old_act);

        $rs = $service->group_entry()->whereIn('id', $allow_to_act)->where('act', 0)->update(['act' => 1, 'act_at' => Carbon::now()]);
        $service->logGroupAct(1, $to_act);
        if (count($to_not_act)) {
            $rs = $service->group_entry()->whereIn('id', $to_not_act)->where('act', 1)->update(['act' => 0, 'act_at' => null]);
            $service->logGroupAct(0, $to_not_act);
        }
        if ($rs) {
            if (!$forbid_to_act) {
                return back()
                    ->with('message', '成功改變FAQ組別的啟用狀態');
            } else {
                return back()
                    ->with('message', '成功改變' . count($allow_to_act) . '組FAQ組別的啟用狀態，另有' . count($forbid_to_act) . '組無法啟用');
            }
        } else {
            return back()
                ->withErrors(['出現不明錯誤，無法改變FAQ組別的啟用狀態']);
        }
    }

    public function showFaqGroupEdit(FaqService $service, $id)
    {
        $data['service'] = $service->riseByGroupId($id);
        $data['entry'] =  $data['service']->group_entry();
        return view('admin.faq_group_edit', $data);
    }

    public function saveFaqGroup(Request $request, FaqService $service)
    {
        if ($service->saveGroup($request)) {
            return redirect('admin/faq_group' . $service->getEngroupVipQueryString('?', $service->group_entry()))
                ->with('message', '成功修改FAQ組別');
        } else {
            return redirect('admin/faq_group' . $service->getEngroupVipQueryString('?', $service->group_entry()))
                ->withErrors(['出現不明錯誤，無法修改FAQ組別']);
        }
    }

    public function deleteFaqGroup(Request $request, FaqService $service)
    {
        if ($service->riseByGroupId($request->id)->delGroup()) {
            return redirect('admin/faq_group' . $service->getEngroupVipQueryString('?', $service->group_entry()))
                ->with('message', '成功刪除FAQ組別');
        } else {
            return redirect('admin/faq_group' . $service->getEngroupVipQueryString('?', $service->group_entry()))
                ->withErrors(['出現不明錯誤，無法刪除FAQ組別']);
        }
    }


    public function showFaqChoice(Request $request, FaqService $service, $id)
    {
        $data['service'] = $service->riseByQuestionId($id)->fillChoiceList();
        $data['entry_list'] = $data['service']->choice_list;
        return view('admin.faq_choice', $data);
    }

    public function showNewFaqChoice(Request $request, FaqService $service, $id)
    {
        $data['service'] = $service->riseByQuestionId($id);
        return view('admin.faq_choice_new', $data);
    }

    public function newFaqChoice(Request $request, FaqService $service, $id)
    {
        if ($service->riseByQuestionId($id)->addChoice($request)) {
            return redirect()->route('admin/faq_choice', $id)
                ->with('message', '成功新增FAQ選項');
        } else {
            if ($service->error_msg() == 'duplicate_name') $error_msg = '選項名稱重複，無法修改FAQ選項';
            else $error_msg = '出現不明錯誤，無法修改FAQ選項';

            return redirect()->route('admin/faq_choice', $id)
                ->withErrors([$error_msg]);
        }
    }

    public function showFaqChoiceEdit(FaqService $service, $id)
    {
        $data['service'] = $service->riseByChoiceId($id);
        $data['entry'] =  $data['service']->choice_entry();
        return view('admin.faq_choice_edit', $data);
    }

    public function saveFaqChoice(Request $request, FaqService $service, $id)
    {
        if ($service->riseByQuestionId($id)->saveChoice($request)) {
            return redirect()->route('admin/faq_choice', $id)
                ->with('message', '成功修改FAQ選項');
        } else {
            if ($service->error_msg() == 'duplicate_name') $error_msg = '選項名稱重複，無法修改FAQ選項';
            else $error_msg = '出現不明錯誤，無法修改FAQ選項';
            return redirect()->route('admin/faq_choice', $id)
                ->withErrors([$error_msg]);
        }
    }

    public function deleteFaqChoice(Request $request, FaqService $service)
    {
        if ($service->riseByChoiceId($request->id)->delChoice()) {
            return redirect()->route('admin/faq_choice', $service->question_entry()->id)
                ->with('message', '成功刪除FAQ選項');
        } else {
            return redirect()->route('admin/faq_choice', $service->question_entry()->id)
                ->withErrors(['出現不明錯誤，無法刪除FAQ選項']);
        }
    }

    public function showAdminCommonText()
    {
        $a = AdminCommonText::orderBy('id', 'asc')->where('status', 1)->get()->all();
        return view('admin.admincommontext')->with('commontext', $a);
    }

    public function saveAdminCommonText(Request $request)
    {

        if (AdminCommonText::checkContent2($request->id, $request->content2) and AdminCommonText::checkContent2($request->id, $request->content)) {
            return back()->withErrors(['請修改後再送出']);
        } elseif ($request->content != $request->content2) {

            $a = AdminCommonText::select('*')->where('id', '=', $request->id)->first();

            $a->content = $request->content2;
            $a->content = str_replace('|$responseTime|', date("Y-m-d H:i:s"), $a->content);
            $a->content = str_replace('|$reportTime|', date("Y-m-d H:i:s"), $a->content);
            $a->content = str_replace('NOW_TIME', date("Y-m-d H:i:s"), $a->content);
            $a->content = str_replace('LINE_ICON', AdminService::$line_icon_html, $a->content);
            $a->content = str_replace('|$lineIcon|', AdminService::$line_icon_html, $a->content);
            $a->save();
            return back()->with('message', '成功修改');
        }
    }

    public function showReadAnnouncementUser($id)
    {
        $a = AdminAnnounce::where('id', $id)->get()->first();
        $results = \App\Models\AnnouncementRead::where('announcement_id', $id)->get();
        foreach ($results as &$result) {
            $user = users::where('id', $result->user_id)->get()->first();
            $result->name = $user->name;
        }
        return view('admin.adminannouncement_read')
            ->with('announce', $a)
            ->with('results', $results);
    }

    public function showAdminAnnouncementEdit($id)
    {
        $a = AdminAnnounce::where('id', $id)->get()->first();
        return view('admin.adminannouncement_edit')->with('announce', $a);
    }

    /**
     * Edits and saves admin announcement.
     *
     * @return \Illuminate\Http\Response
     */
    public function editAdminAnnouncement(Request $request)
    {
        if (AdminAnnounce::editAnnouncement($request)) {
            return redirect('admin/announcement')
                ->with('message', '成功修改站長公告');
        } else {
            return redirect('admin/announcement')
                ->withErrors(['出現不明錯誤，無法修改站長公告']);
        }
    }

    public function showNewAdminAnnouncement(Request $request)
    {
        return view('admin.adminannouncement_new');
    }

    public function saveAdminAnnouncement(Request $request)
    {
        if (AdminAnnounce::saveAnnouncement($request)) {
            return back()->with('message', '成功修改站長公告');
        } else {
            return back()->withErrors(['出現不明錯誤，無法新增站長公告']);
        }
    }

    public function newAdminAnnouncement(Request $request)
    {
        if (AdminAnnounce::newAnnouncement($request)) {
            return redirect('admin/announcement')
                ->with('message', '成功新增站長公告');
        } else {
            return redirect('admin/announcement')
                ->withErrors(['出現不明錯誤，無法新增站長公告']);
        }
    }

    public function deleteAdminAnnouncement(Request $request)
    {
        if (AdminAnnounce::deleteAnnouncement($request)) {
            return redirect('admin/announcement')
                ->with('message', '成功刪除站長公告');
        } else {
            return redirect('admin/announcement')
                ->withErrors(['出現不明錯誤，無法刪除站長公告']);
        }
    }

    /**
     * Shows web  announcement page.
     *
     * @return \Illuminate\Http\Response
     */

    public function showWebAnnouncement()
    {
        $time = \Carbon\Carbon::now();
        $start = date('Y-m-01', strtotime($time->subDay(30)));
        $end = date('Y-m-t', strtotime($time));
        $userBanned = banned_users::select('users.name', 'banned_users.*')
            ->whereBetween('banned_users.created_at', [($start), ($end)])
            ->join('users', 'banned_users.member_id', '=', 'users.id')
            ->orderBy('banned_users.created_at', 'asc')->get();
        $isVip = array();
        foreach ($userBanned as $user) {
            $isVip[$user->member_id] = Vip::select('member_id')->where('member_id', $user->member_id)->get()->first();
        }

        return view('admin.adminannouncement_web')
            ->with('users', $userBanned)
            ->with('isVip', $isVip);
    }

    public function showReportedUsersPage()
    {
        $admin = $this->admin->checkAdmin();
        if ($admin) {
            return view('admin.users.reportedUsers');
        } else {
            return view('admin.users.reportedUsers')->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function showReportedUsersList(Request $request)
    {
        $admin = $this->admin->checkAdmin();
        if ($admin) {
            $users = Reported::select('*');
            if ($request->date_start) {
                $users = $users->where('created_at', '>', $request->date_start . ' 00:00');
            }
            if ($request->date_end) {
                $users = $users->where('created_at', '<', $request->date_end . ' 23:59');
            }
            $users = $users->orderBy('created_at', 'desc');
            $datas = $this->admin->fillReportedDatas($users);

            //被檢舉者的警示符號參數
            foreach ($datas['results'] as $key => $value) {
                $datas['results'][$key]['warnedicon'] = $this->warned_icondata($value['reported_id']);
            }
            //檢舉者的警示符號參數
            if (isset($datas['users'])) {
                foreach ($datas['users'] as $key => $value) {
                    $datas['users'][$key]['warnedicon'] = $this->warned_icondata($key);
                }
            }
            $banReason = DB::table('reason_list')->select('content')->where('type', 'ban')->get();

            return view('admin.users.reportedUsers')
                ->with('results', $datas['results'])
                ->with('users', isset($datas['users']) ? $datas['users'] : null)
                ->with('banReason', $banReason)
                ->with('reported_id', isset($request->reported_id) ? $request->reported_id : null)
                ->with('date_start', isset($request->date_start) ? $request->date_start : null)
                ->with('date_end', isset($request->date_end) ? $request->date_end : null);
        } else {

            return view('admin.users.reportedUsers')->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function warned_icondata($id)
    {
        $userMeta = UserMeta::where('user_id', $id)->get()->first();
        $warned_users = warned_users::where('member_id', $id)->orderBy('created_at', 'desc')->first();
        $f_user = User::findById($id);
        if (isset($warned_users) && ($warned_users->expire_date == null || $warned_users->expire_date >= Carbon::now())) {
            $data['isAdminWarned'] = 1;
        } else {
            $data['isAdminWarned'] = 0;
        }
        $data['auth_status'] = 0;
        if (isset($userMeta)) {
            $data['isWarned'] = $userMeta->isWarned;
        } else {
            $data['isWarned'] = null;
        }
        if($f_user && $f_user->isVipOrIsVvip()) $data['isWarned']  = 0;
        if (isset($f_user)) {
            $data['WarnedScore'] = $f_user->WarnedScore();
            $data['auth_status'] = $f_user->isPhoneAuth();
        } else {
            $data['WarnedScore'] = null;
            $data['auth_status'] = null;
        }
        return $data;
    }

    public function reportedIsWrite(ReportedIsWriteRequest $request)
    {
        $admin = $this->admin->checkAdmin();

        if (!$admin) {
            return response()->json([
                'message' => '找不到暱稱含有「站長」的使用者！請先新增再執行此步驟'
            ], 403);
        }

        $memberId = $request->memberId;
        $reportedId = $request->reportedId;
        $reportedIndexId = $request->reportedIndexId;

        $reported = Reported::where([
            'member_id'     => $memberId,
            'reported_id'   => $reportedId,
            'id'            => $reportedIndexId
        ])->first();

        if (!$reported) {
            return response()->json([
                'message' => '找不到此檢舉'
            ], 403);
        }

        $reported->update([
            'is_write'      => $reported->is_write == 0 ? 1 : 0,
        ]);

        return response('', 201);
    }

    public function messageIsWrite(Request $request)
    {
        $admin = $this->admin->checkAdmin();

        if (!$admin) {
            return response()->json([
                'message' => '找不到暱稱含有「站長」的使用者！請先新增再執行此步驟'
            ], 403);
        }

        $toId = $request->toId;
        $fromId   = $request->fromId;
        $messageIndexId   = $request->messageIndexId;

        $message = Message::where([
            'to_id'     => $toId,
            'from_id'   => $fromId,
            'id'          => $messageIndexId
        ])->first();

        if (!$message) {
            return response()->json([
                'message' => '找不到此檢舉'
            ], 403);
        }

        Message::where([
            'id'          => $messageIndexId
        ])->update([
            'is_write'      => $message->is_write == 0 ? 1 : 0,
        ]);

        return response('', 201);
    }

    public function showReportedPicsPage()
    {
        $admin = $this->admin->checkAdmin();
        if ($admin) {
            return view('admin.users.reportedPics');
        } else {
            return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function searchReportedPics(Request $request)
    {
        $admin = $this->admin->checkAdmin();
        if ($admin) {

            $date_start = $request->date_start ? $request->date_start : '0000-00-00';
            $date_end = $request->date_end ? $request->date_end . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';

            $avatars = ReportedAvatar::whereBetween('created_at', array($date_start, $date_end))
                ->orderBy('created_at', 'desc')->get();
            $pics = ReportedPic::whereBetween('created_at', array($date_start, $date_end))
                ->orderBy('created_at', 'desc')->get();

            $avatarDatas = $this->admin->fillReportedAvatarDatas($avatars);
            $picDatas = $this->admin->fillReportedPicDatas($pics);

            $picReason = DB::table('reason_list')->select('content')->where('type', 'pic')->get();

            //大頭照被檢舉者的警示符號參數
            if (isset($avatarDatas['results'])) {
                foreach ($avatarDatas['results'] as $key => $value) {
                    $avatarDatas['results'][$key]['warnedicon'] = $this->warned_icondata($value['reported_user_id']);
                }
            }

            //大頭照檢舉者的警示符號參數
            if (isset($avatarDatas['users'])) {
                foreach ($avatarDatas['users'] as $key => $value) {
                    $avatarDatas['users'][$key]['warnedicon'] = $this->warned_icondata($key);
                }
            }
            //個人照被檢舉者的警示符號參數
            if (isset($picDatas['results'])) {
                foreach ($picDatas['results'] as $key => $value) {
                    $picDatas['results'][$key]['warnedicon'] = $this->warned_icondata($value['reported_user_id']);
                }
            }
            //個人照檢舉者的警示符號參數
            if (isset($picDatas['users'])) {
                foreach ($picDatas['users'] as $key => $value) {
                    $picDatas['users'][$key]['warnedicon'] = $this->warned_icondata($key);
                }
            }

            return view('admin.users.reportedPics')
                ->with('picReason', $picReason)
                ->with('results', $avatarDatas['results'] ? $avatarDatas['results'] : 1)
                ->with('users', isset($avatarDatas['users']) ? $avatarDatas['users'] : null)
                ->with('Presults', $picDatas['results'] ? $picDatas['results'] : null)
                ->with('Pusers', isset($picDatas['users']) ? $picDatas['users'] : null)
                ->with('reported_id', isset($request->reported_id) ? $request->reported_id : null)
                ->with('date_start', isset($request->date_start) ? $request->date_start : null)
                ->with('date_end', isset($request->date_end) ? $request->date_end : null);
        } else {
            return view('admin.users.reportedPics')->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function showReportedDetails(Request $request)
    {

        if ($this->admin->checkAdmin()) {
            $result = $this->admin->reportedUserDetails($request);

            if ($result)
                return view('admin.users.reportedUserDetails')
                    ->with('reported_id', $request->reported_id)
                    ->with('reportedUser', $result['reportedUsers'])
                    ->with('users', $result['users']);
            else
                return back()->withErrors(['無檢舉資料']);
        }
    }

    public function customizeMigrationFiles(Request $request)
    {
        $file = null;
        if (file_exists(storage_path('app/RP_761404_' . \Carbon\Carbon::today()->format('Ymd') . '.dat'))) {
            $file = \File::get(storage_path('app/RP_761404_' . \Carbon\Carbon::today()->format('Ymd') . '.dat'));
        }
        $date = \Carbon\Carbon::now()->addDay()->day >= 28 ? '01' : \Carbon\Carbon::now()->addDay()->day;

        if ($request->isMethod('get')) {
            return view(
                'admin.users.customizeMigrationFiles',
                [
                    'file' => $file == null ? $file : nl2br($file),
                    'date' => $date
                ]
            );
        } elseif ($request->isMethod('post')) {
            $logging = new \App\Services\VipLogService;
            if ($logging->customLogToFile($request->user_id, $request->order_id, $request->day, $request->action)) {
                return back()->with('message', '異動檔修改成功，請在頁面下方查看結果。');
            } else {
                return back()->withErrors(['發生不明錯誤(Error002).']);
            }
        }
    }

    public function changePassword(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('admin.users.changePassword');
        } elseif ($request->isMethod('post')) {
            $user = User::findByEmail($request->email);
            if (!isset($user)) {
                return view('admin.users.changePassword')->withErrors(['找不到會員，請檢查輸入的Email是否正確']);
            } else {
                $password = $request->password == null ? '123456' : $request->password;
                $user->password = bcrypt($password);
                $user->save();
                return back()->with('message', '會員 ' . $user->name . ' 的密碼已設為:' . $password);
            }
        }
        return view('admin.users.changePassword')->withErrors(['發生不明錯誤(Error001)']);
    }

    public function inactiveUsers(Request $request)
    {
        $users = User::join('user_meta', 'users.id', 'user_meta.user_id')
            ->where('user_meta.is_active', 0);
        if ($request->email != null) {
            $users = $users->where('users.email', $request->email);
        }
        $users = $users->orderBy('users.created_at', 'desc')->paginate(20);
        return view('admin.users.inactiveUsers', [
            'users' => $users
        ]);
    }

    public function activateUser($token)
    {
        $user = UserMeta::where('activation_token', $token)->first();

        if ($user) {
            $user->update([
                'is_active' => true,
                'activation_token' => null,
                'blurryAvatar' => 'general,',
                'blurryLifePhoto' => 'general,'
            ]);
            return back()->with('message', '啟動成功。');
        }

        return back()->withErrors(['啟動失敗。']);
    }

    public function deleteBoard($id)
    {
        $message = Board::where('id', $id)->get()->first();
        if ($message->delete()) {
            return back()->with('message', '成功刪除留言！');
        } else {
            return back()->withErrors(['發生不明錯誤，刪除留言失敗！']);
        }
    }

    public function manualSQL()
    {
        return "<a href='" . route('querier') . "'>執行手動SQL</a>";
    }

    public function querier()
    {
        //INSERT INTO `message`(`created_at`, `to_id`, `from_id`, `content`, `all_delete_count`, `is_row_delete_1`, `is_row_delete_2`, `is_single_delete_1`, `is_single_delete_2`, `temp_id`, `reportContent`) SELECT STR_TO_DATE('2019-08-12 14:00:00','%Y-%m-%d %H:%i:%s'), `from_id`, 1049, '您好，網站目前正在對 VIP 進行調查，請問「小杉大叔」有否在確認包養關係之前跟您索取清涼照？', 0, 0, 0, 0, 0, 0, null FROM `message` WHERE `to_id` = 25889 AND `created_at` > '2019-06-31 23:59:59' GROUP BY `from_id`

        //SELECT `from_id` FROM `message` WHERE `to_id` = 25889 AND `created_at` > '2019-06-31 23:59:59' GROUP BY `from_id`
        $user_ids = Message::select('from_id')->where('to_id', 25889)->where('created_at', '>', '2019-06-31 23:59:59')->groupBy('from_id')->get();
        foreach ($user_ids as $id) {
            $m = new Message;
            $m->to_id = $id->from_id;
            $m->from_id = 1049;
            $m->content = '您好，網站目前正在對 VIP 進行調查，請問「小杉大叔」有否在確認包養關係之前跟您索取清涼照？';
            $m->all_delete_count = 0;
            $m->is_row_delete_1 = 0;
            $m->is_row_delete_2 = 0;
            $m->is_single_delete_1 = 0;
            $m->is_single_delete_2 = 0;
            $m->temp_id = 0;
            $m->created_at = '2019-08-12 14:00:00';
            $m->save();
        }

        return '<h1>操作完成</h1>';
    }

    public function getMessageLib(Request $request)
    {
        $id = $request->post('id');
        $msglib = MsgLib::where('id', $id)->get();

        echo json_encode($msglib, JSON_UNESCAPED_UNICODE);
    }

    public function updateMessageLib(Request $request)
    {
        $formdata = $request->post('formdata');
        $data = explode('&', $formdata);


        $entry = explode('=', $data[0]);

        $id = (int)$entry[0];
        $msg = $entry[1];

        $query = DB::update('update msglib set msg=? where id = ?', [$msg, $id]);

        $data = array(
            'id' => $id,
            'msg' => $msg,
        );
        echo json_encode($formdata, JSON_UNESCAPED_UNICODE);
    }

    public function addMessageLibPage(Request $request, $id = 0)
    {
        if ($id != 0) {
            $msglib = MsgLib::where('id', $id)->first();

            $data = array(
                'page_title' => '編輯訊息範本',
                'msg_id' => $msglib->id,
                'title' => $msglib->title,
                'msg' => $msglib->msg,
                'isEdit' => 1,
            );
        } else {
            $data = array(
                'page_title' => '新增訊息範本',
            );
        }
        return view('admin.users.messenger_create', $data);
    }

    public function addMessageLibRealAuth(Request $request, $id = 0)
    {
        if ($id != 0) {
            $msglib = MsgLib::where('id', $id)->first();
            $data = array(
                'page_title' => '編輯訊息範本',
                'msg_id' => $msglib->id,
                'title' => $msglib->title,
                'msg' => $msglib->msg,
                'isEdit' => 1,
            );
        } else {
            $data = array(
                'page_title' => '新增訊息範本',
            );
        }

        return view('admin.users.messenger_create', $data);
    }


    public function addMessageLibPageReporter(Request $request, $id = 0)
    {
        if ($id != 0) {
            $msglib = MsgLib::where('id', $id)->first();
            $data = array(
                'page_title' => '編輯訊息範本',
                'msg_id' => $msglib->id,
                'title' => $msglib->title,
                'msg' => $msglib->msg,
                'isEdit' => 1,
            );
        } else {
            $data = array(
                'page_title' => '新增訊息範本',
            );
        }
        return view('admin.users.messenger_create', $data);
    }

    public function addMessageLibPageReported(Request $request, $id = 0)
    {
        if ($id != 0) {
            $msglib = MsgLib::where('id', $id)->first();
            $data = array(
                'page_title' => '編輯訊息範本',
                'msg_id' => $msglib->id,
                'title' => $msglib->title,
                'msg' => $msglib->msg,
                'isEdit' => 1,
            );
        } else {
            $data = array(
                'page_title' => '新增訊息範本',
            );
        }
        return view('admin.users.messenger_create', $data);
    }

    public function addMessageLib(Request $request)
    {
        $msg_id = $request->post('msg_id');
        if ($msg_id != '') {
            $kind = $request->post('kind');
            $title = $request->post('title');
            $msg = $request->post('content');
            $data = array(
                'msg_id' => $msg_id,
                'title' => $title,
                'msg' => $msg,
            );
            DB::update('update msglib set title=?, msg=?, kind=? where id=?', [$title, $msg, $kind, $msg_id]);
            return json_encode($data);
        } else {
            $kind = $request->post('kind');
            $title = $request->post('title');
            $msg = $request->post('content');
            $data = array(
                'title' => $title,
                'msg' => $msg,
            );
            DB::insert(
                'insert into msglib (title, msg, kind) values ( ?, ? , ? )',
                [$title, $msg, $kind]
            );
            return json_encode($data);
        }
    }

    public function delMessageLib(Request $request)
    {
        $id = $request->post('id');

        DB::table('msglib')->where('id', '=', $id)->delete();
        $data = array(
            'status' => 'success',
        );
        return json_encode($data);
    }

    public function blockUser(Request $request)
    {
        $data = $request->post('data');
        $ban = banned_users::where('member_id', $data['id'])->get()->toArray();
        // dd($ban);
        if (empty($ban)) {
            if (DB::table('banned_users')->insert(['member_id' => $data['id'], 'reason' => '管理者刪除'])) {
                BadUserCommon::addRemindMsgFromBadId($data['id']);
            }
        }

        $data = array(
            'code' => '200',
            'status' => 'success'
        );
        echo json_encode($data);
    }

    public function unblockUser(Request $request)
    {
        $data = $request->post('data');
        // dd($data);
        $ban = banned_users::where('member_id', $data['id'])->get();
        $banImplicitly = \App\Models\BannedUsersImplicitly::where('target', $data['id'])->get();
        // dd($ban);
        if ($ban->count() > 0) {
            foreach ($ban as $r) {
                $checkLog = DB::table('is_banned_log')->where('user_id', $r->member_id)->where('created_at', $r->created_at)->first();
                if (!$checkLog) {
                    //寫入log
                    DB::table('is_banned_log')->insert(['user_id' => $r->member_id, 'reason' => $r->reason, 'expire_date' => $r->expire_date, 'vip_pass' => $r->vip_pass, 'adv_auth' => $r->adv_auth, 'created_at' => $r->created_at]);
                }
            }
            banned_users::where('member_id', $data['id'])->first()->delete();
            SetAutoBan::where('cuz_user_set', $data['id'])->where('host', null)->delete();
            SetAutoBan::where('cuz_user_set', $data['id'])->where('host', request()->getHttpHost())->delete();
        }
        if ($banImplicitly->count() > 0) {
            \App\Models\BannedUsersImplicitly::where('target', $data['id'])->delete();
            SetAutoBan::where('cuz_user_set', $data['id'])->where('host', null)->delete();
            SetAutoBan::where('cuz_user_set', $data['id'])->where('host', request()->getHttpHost())->delete();
        }
        $this->messageService->setMessageHandlingBySenderId($data['id'],0);
        //新增Admin操作log
        $this->insertAdminActionLog($data['id'], '解除封鎖');

        $data = array(
            'code' => '200',
            'status' => 'success'
        );
        echo json_encode($data);
    }

    public function isWarnedUser(Request $request)
    {

        $id = $request->post('id');
        $status = $request->post('status');
        $isWarnedType = $request->post('isWarnedType');
        $user = User::findById($id);
        if ($status == 1 &&  $isWarnedType == 'adv_auth'  && $user->advance_auth_status) {
            $data = array(
                'code' => '200'
            );
            echo json_encode($data);
            exit;
        }

        $isWarnedTime = null;
        if ($status == 1) {
            $isWarnedTime = Carbon::now();
            /* 2022/09/22 被檢舉者列為警示時被檢舉的訊息改為未處理 */
            \Log::debug('test::'.$id);
            $this->messageService->setMessageHandlingBySenderId($id,1);
        }

        DB::table('user_meta')->where('user_id', $id)->update(['isWarned' => $status, 'isWarnedRead' => 0, 'isWarnedTime' => $isWarnedTime, 'isWarnedType' => $isWarnedType]);
        if ($isWarnedType != 'adv_auth') {
            if ($status == 1) {
                //加入警示流程
                //清除認證資料
                //            DB::table('auth_img')->where('user_id',$id)->delete();
                ShortMessageService::deleteShortMessageByQuery(DB::table('short_message')->where('member_id', $id),true);
                //DB::table('short_message')->where('member_id', $id)->update(['active' =>0]);
            } else if ($status == 0) {

                //取消警示流程
                //加入認證資料 假資料
                if ($user->WarnedScore() >= 10) {

                    if ($user->isPhoneAuth() == 0) {
                        DB::table('short_message')->insert(
                            ['mobile' => '0922222222'
                                , 'member_id' => $id
                                , 'active' => 1
                                ,'auto_created'=>1
                                ,'created_by'=>auth()->id()
                                ,'created_from'=>request()->path()
                                ,'createdate'=>Carbon::now()]
                        );
                    }

                    //                if ($user->isImgAuth() == 0) {
                    //                    DB::table('auth_img')->insert(
                    //                        ['user_id' => $id, 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()]);
                    //                }
                }
            }
            event(new \App\Events\CheckWarnedOfReport($id));
        }
        //新增Admin操作log
        $this->insertAdminActionLog($id, $status == 1 ? '警示用戶'  : '取消警示用戶');

        $data = array(
            'code' => '200',
            'status' => 'success'
        );
        echo json_encode($data);
    }

    public function basicSetting(Request $request)
    {
        $data['basic_setting'] = BasicSetting::get()->first();

        return view('user.basic_setting', $data);
    }

    public function doBasicSetting(Request $request)
    {
        $vipLevel = $request->post('vipLevel');
        $gender = $request->post('gender');
        $timeSet = $request->post('timeSet');
        $countSet = $request->post('countSet');
        BasicSetting::select('vipLevel', 'gender', 'timeSet', 'countSet')
            ->where('vipLevel', $vipLevel)->where('gender', $gender)
            ->update(array('timeSet' => $timeSet, 'countSet' => $countSet));
        return redirect()->route('users/basic_setting');
    }


    public function showMasterwords(Request $request)
    {
        $a = MasterWords::orderBy('sequence', 'asc')->orderBy('updated_at', 'desc')->get()->all();
        return view('admin.adminmasterwords')->with('masterwords', $a);
    }

    public function showNewAdminMasterWords()
    {
        return view('admin.adminmasterwords_new');
    }

    public function newAdminMasterWords(Request $request)
    {
        if (MasterWords::newMasterWords($request)) {
            return redirect('admin/masterwords')
                ->with('message', '成功新增站長的話');
        } else {
            return redirect('admin/masterwords')
                ->withErrors(['出現不明錯誤，無法新增站長的話']);
        }
    }

    public function deleteAdminMasterWords(Request $request)
    {
        if (MasterWords::deleteMasterWords($request)) {
            return redirect('admin/masterwords')
                ->with('message', '成功刪除站長的話');
        } else {
            return redirect('admin/masterwords')
                ->withErrors(['出現不明錯誤，無法刪除站長的話']);
        }
    }

    public function showAdminMasterWordsEdit($id)
    {
        $a = MasterWords::where('id', $id)->get()->first();
        return view('admin.adminmasterwords_edit')->with('masterwords', $a);
    }

    public function saveAdminMasterWords(Request $request)
    {
        if (MasterWords::saveMasterWords($request)) {
            return back()->with('message', '成功修改站長公告');
        } else {
            return back()->withErrors(['出現不明錯誤，無法新增站長公告']);
        }
    }

    public function showReadMasterWords($id)
    {
        $a = MasterWords::where('id', $id)->get()->first();
        // dd($a);
        $results = \App\Models\MasterWordsRead::where('announcement_id', $id)->get();
        // dd($results);
        foreach ($results as &$result) {
            $user = users::where('id', $result->user_id)->get()->first();
            $result->name = $user->name;
        }
        // dd('1');
        return view('admin.adminmasterwords_read')
            ->with('announce', $a)
            ->with('results', $results);
    }

    public function showSuspectedMultiLogin()
    {
        $result = \DB::table('suspected_multi_login')
            ->select('users.email', 'users.last_login', 'users.name', 'suspected_multi_login.*')
            ->join('users', 'users.id', '=', 'suspected_multi_login.user_id')
            ->orderBy('created_at', 'desc')->paginate(20);
        foreach ($result as &$r) {
            $r->count = Message::where('from_id', $r->user_id)->where('created_at', '>=', \Carbon\Carbon::now()->subDays(3))->count();
        }

        foreach ($result as &$r) {
            $users = explode(", ", $r->target);
            foreach ($users as &$u) {
                $u = User::findById($u);
            }
            $r->target = $users;
        }
        return view('admin.users.suspectedMultiLoginList')->with('users', $result);
    }

    public function showImplicitlyBannedUsers(Request $request)
    {
        set_time_limit(300);
        ini_set("memory_limit", "2048M");
        $page = $request->input('page', 1);
        $orderBy = $request->input('orderBy', 'last_login');
        $order = $request->input('order', 'desc');

        $paginate = 100;
        $result = banned_users::select(DB::raw('fingerprint2.fp, banned_users.member_id as user_id, banned_users.created_at as banned_at, "永久" as type1, "" as type2, users.email, users.name, users.title, users.created_at, users.last_login, users.engroup'))
            ->join('fingerprint2', 'fingerprint2.user_id', '=', 'banned_users.member_id')
            ->join('users', 'users.id', '=', 'banned_users.member_id')
            ->where('expire_date', null);
        $result2 = BannedUsersImplicitly::select(DB::raw('fp, target as user_id, banned_users_implicitly.created_at as banned_at, "" as type1, "隱性" as type2, users.email, users.name, users.title, users.created_at, users.last_login, users.engroup'))
            ->join('users', 'users.id', '=', 'banned_users_implicitly.target');
        $result3 = ExpectedBanningUsers::select(DB::raw('fp, target as user_id, "" as banned_at, (SELECT COUNT(*) FROM banned_users b WHERE b.member_id = expected_banning_users.target) as type1, (SELECT COUNT(*) FROM banned_users_implicitly b WHERE b.target = expected_banning_users.target) as type2, users.email, users.name, users.title, users.created_at, users.last_login, users.engroup'))
            ->join('users', 'users.id', '=', 'expected_banning_users.target');
        if ($orderBy == 'type1, type2') {
            $resultMerged = $result->union($result2)
                ->union($result3)
                ->orderBy('type1', $order)
                ->orderBy('type2', $order)
                ->get();
        } else {
            $resultMerged = $result->union($result2)
                ->union($result3)
                ->orderBy($orderBy, $order)
                ->get();
        }

        $offSet = ($page * $paginate) - $paginate;
        $itemsForCurrentPage = array_slice($resultMerged->toArray(), $offSet, $paginate, true);
        $result = new \Illuminate\Pagination\LengthAwarePaginator($itemsForCurrentPage, count($resultMerged), $paginate, $page, ['path' => route('implicitlyBanned', ['orderBy' => $orderBy, 'order' => $order])]);

        $banReason = DB::table('reason_list')->select('content')->where('type', 'ban')->get();

        return view('admin.users.bannedListImplicitly')->with('users', $result)->with('banReason', $banReason);
    }

    public function banningUserImplicitly(Request $request)
    {

        //勾選加入常用列表後新增
        if ($request->addreason) {
            if (DB::table('reason_list')->where([['type', 'implicitly'], ['content', $request->reason]])->first() == null) {
                DB::table('reason_list')->insert(['type' => 'implicitly', 'content' => $request->reason]);
            }
        }

        //輸入新增自動封鎖關鍵字後新增 隱性封鎖
        if (!empty($request->addautoban)) {
            foreach ($request->addautoban as $value) {
                if (!empty($value)) {
                    if (SetAutoBan::where([['type', 'allcheck'], ['content', $value], ['set_ban', '2']])->first() == null) {
                        SetAutoBan::insert(['type' => 'allcheck', 'content' => $value, 'set_ban' => '2', 'cuz_user_set' => $request->user_id, 'created_at' => now(), 'updated_at' => now()]);
                    }
                }
            }
        }

        BannedUsersImplicitly::insert(
            [
                'fp' => $request->fp,
                'user_id' => 0,
                'reason' => $request->reason,
                'target' => $request->user_id
            ]
        );
        ExpectedBanningUsers::where('target', $request->user_id)->delete();

        //新增Admin操作log
        $this->insertAdminActionLog($request->user_id, '隱性封鎖');

        //隱形封鎖/封鎖某位user後，用站長名義寄一封信給一個月內曾經檢舉過這個user的user，
        //"XX您好，您在X月X日檢舉 OO，經站長檢視後，已於X月X日將其封鎖。您可到 瀏覽3:警示會員無法進行檢舉
        $withInOneMonth =  date("Y-m-d H:i:s", strtotime("-1 month"));
        $getList = Reported::where('reported_id',  $request->user_id)->where('created_at', '>=', $withInOneMonth)
            ->selectRaw('reported.*, (select name from users where id = reported.member_id) as userName')
            ->selectRaw('(select name from users where id = reported.reported_id) as reportedName')
            ->groupby('member_id')
            ->get();
        $adminBannedDay =  date('m月d日');
        //logger(($adminBannedDay));
        //logger(($getList));


        foreach ($getList as $account) {
            $userName = $account->userName;
            $userBannedDay = date('m月d日', strtotime($account->created_at));
            $bannedName =  $account->reportedName;
            //dd($userName, $userBannedDay, $bannedName, $adminBannedDay);
            $userNotify = User::id_($account->member_id);
            if ($userNotify != null) {
                //$userNotify->notify(new BannedUserImplicitly($userName, $userBannedDay, $bannedName, $adminBannedDay));
            }
        }

        if (isset($request->page)) {
            switch ($request->page) {
                case 'noRedirect':
                    return json_encode(array('code' => '200', 'status' => 'success'));
                    break;
                default:
                    return redirect($request->page);
                    break;
            }
        }

        return '<script>window.close();</script>';
    }

    public function banningFingnerprint(Request $request)
    {
        \DB::table('banned_fingerprints')->insert(
            [
                'fp' => $request->fp,
                'created_at' => \Carbon\Carbon::now()
            ]
        );

        return back()->with('message', '成功封鎖此指紋');
    }

    public function deleteFingerprintFromExpectedList($fingerprint)
    {
        ExpectedBanningUsers::where('fp', $fingerprint)->delete();

        return back()->with('message', '成功將此指紋從預計封鎖清單中移除');
    }

    public function unbanningFingnerprint(Request $request)
    {
        \DB::table('banned_fingerprints')->where('fp', $request->fp)->delete();

        return back()->with('message', '成功解除封鎖此指紋');
    }

    public function unbanAll(Request $request)
    {
        $implicitly = BannedUsersImplicitly::where('target', $request->user_id)->first();
        $banned = banned_users::where('member_id', $request->user_id)->orderBy('created_at', 'desc')->first();
        if ($implicitly) {
            $implicitly->delete();
        }
        if ($banned) {
            $checkLog = DB::table('is_banned_log')->where('user_id', $banned->member_id)->where('created_at', $banned->created_at)->first();
            if (!$checkLog) {
                //寫入log
                DB::table('is_banned_log')->insert(['user_id' => $banned->member_id, 'reason' => $banned->reason, 'expire_date' => $banned->expire_date, 'vip_pass' => $banned->vip_pass, 'adv_auth' => $banned->adv_auth, 'created_at' => $banned->created_at]);
            }
            $banned->delete();
        }

        if (isset($request->page)) {
            switch ($request->page) {
                default:
                    return redirect($request->page);
                    break;
            }
        }
        return '<script>window.close();</script>';
    }

    public function showFingerprint($fingerprint, Request $request)
    {
        $orderBy = $request->input('orderBy', 'last_login');
        $order = $request->input('order', 'desc');

        $result = banned_users::select(DB::raw('fingerprint2.fp, banned_users.member_id as user_id, banned_users.created_at as banned_at, "永久" as type1, "" as type2, users.email, users.name, users.title, users.created_at, users.last_login, users.engroup'))
            ->join('fingerprint2', 'fingerprint2.user_id', '=', 'banned_users.member_id')
            ->join('users', 'users.id', '=', 'banned_users.member_id')
            ->where('expire_date', null)
            ->where('fp', $fingerprint);
        $result2 = BannedUsersImplicitly::select(DB::raw('fp, target as user_id, banned_users_implicitly.created_at as banned_at, "" as type1, "隱性" as type2, users.email, users.name, users.title, users.created_at, users.last_login, users.engroup'))
            ->join('users', 'users.id', '=', 'banned_users_implicitly.target')
            ->where('fp', $fingerprint);
        $result3 = ExpectedBanningUsers::select(DB::raw('fp, target as user_id, "" as banned_at, (SELECT COUNT(*) FROM banned_users b WHERE b.member_id = expected_banning_users.target) as type1, (SELECT COUNT(*) FROM banned_users_implicitly b WHERE b.target = expected_banning_users.target) as type2,  users.email, users.name, users.title, users.created_at, users.last_login, users.engroup'))
            ->join('users', 'users.id', '=', 'expected_banning_users.target')
            ->where('fp', $fingerprint);
        if ($orderBy == 'type1, type2') {
            $resultMerged = $result->union($result2)
                ->union($result3)
                ->orderBy('type1', $order)
                ->orderBy('type2', $order)
                ->get();
        } else {
            $resultMerged = $result->union($result2)
                ->union($result3)
                ->orderBy($orderBy, $order)
                ->get();
        }

        $isFingerprintBanned = \DB::table('banned_fingerprints')->where('fp', $fingerprint)->get()->count();
        $isFingerprintBanned = $isFingerprintBanned > 0 ? true : false;

        $banReason = DB::table('reason_list')->select('content')->where('type', 'ban')->get();

        return view('admin.users.showFingerprint')
            ->with('users', $resultMerged)
            ->with('fingerprint', $fingerprint)
            ->with('isFingerprintBanned', $isFingerprintBanned)
            ->with('banReason', $banReason);
    }

    public function showLoginLog(Request $request)
    {
        $user = User::findById($request->uid);
        $loginLog = LogUserLogin::where('user_id', $user->id)->where('created_at', 'like', '%' .  $request->date . '%')->orderBy('created_at', 'DESC')->get();

        return view('admin.users.showLoginLog')
            ->with('user', $user)
            ->with('loginLog', $loginLog);
    }

    public function showWarningUsers()
    {
        $result = \DB::table('warning_users')
            ->select('users.email', 'users.last_login', 'users.name', 'warning_users.*')
            ->join('users', 'users.id', '=', 'warning_users.user_id')
            ->orderBy('created_at', 'desc')->paginate(20);
        foreach ($result as &$r) {
            $r->count = Message::where('from_id', $r->user_id)->where('created_at', '>=', \Carbon\Carbon::now()->subDays(3))->count();
        }

        foreach ($result as &$r) {
            $r->target = User::findById($r->target);
        }
        return view('admin.users.warningList')->with('users', $result);
    }

    public function statisticsReply(Request $request)
    {
        if ($request->isMethod('GET')) {
            return view('admin.users.statisticsReply');
        } else {
            $start = $request->date_start;
            $end = $request->date_end;
            $place = 4;

            // 車馬費回覆
            $repliedCount = count($this->service->selectTipMessagesReplied($start, $end));
            $tipMessage = [
                'replied' => $repliedCount,
                'totalInvitation' => $repliedCount + count(\App\Models\Tip::selectTipMessage($start, $end))
            ];
            $count = ['tipMessage' => $tipMessage];

            $tipMessage = $tipMessage['totalInvitation'] != 0 ? $tipMessage['replied'] / $tipMessage['totalInvitation'] : 0;
            $percentage = ['tipMessage' => round($tipMessage, $place)];

            //男會員被回覆比例
            $repliedMsg = $this->service->repliedMessagesProportion($start, $end);

            $normal = count($repliedMsg['messages']['Normal']);
            $vip = count($repliedMsg['messages']['Vip']);
            $recommend = count($repliedMsg['messages']['Recommend']);
            $repliedNormal = count($repliedMsg['replied']['Normal']);
            $repliedVip = count($repliedMsg['replied']['Vip']);
            $repliedRecommend = count($repliedMsg['replied']['Recommend']);

            $count['NormalMale'] = array('messages' => $normal, 'replied' => $repliedNormal);
            $count['VipMale'] = array('messages' => $vip, 'replied' => $repliedVip);
            $count['RecommendMale'] = array('messages' => $recommend, 'replied' => $repliedRecommend);

            $normal = $repliedNormal != 0 ? $normal / $repliedNormal : 0;
            $vip = $repliedVip != 0 ? $normal / $repliedVip : 0;
            $recommend = $repliedRecommend != 0 ? $normal / $repliedRecommend : 0;
            $percentage['NormalMale'] = round($normal, $place);
            $percentage['VipMale'] = round($vip, $place);
            $percentage['RecommendMale'] = round($recommend, $place);

            // 平均收到訊息數
            $TaipeiAndVip = $this->service->averageReceiveMessages(['新北市', '臺北市'], 1, 2);
            $TaipeiAndNotVip = $this->service->averageReceiveMessages(['新北市', '臺北市'], 0, 2);
            $Vip = $this->service->averageReceiveMessages([], 1, 2);
            $NotVip = $this->service->averageReceiveMessages([], 0, 2);


            $count['TaipeiAndVip'] = array('messages' => $TaipeiAndVip['messages'], 'users' => $TaipeiAndVip['users']);
            $count['TaipeiAndNotVip'] = array('messages' => $TaipeiAndNotVip['messages'], 'users' => $TaipeiAndNotVip['users']);
            $count['Vip'] = array('messages' => $Vip['messages'], 'users' => $Vip['users']);
            $count['NotVip'] = array('messages' => $NotVip['messages'], 'users' => $NotVip['users']);

            $TaipeiAndVip = $TaipeiAndVip['users'] != 0 ? $TaipeiAndVip['messages'] / $TaipeiAndVip['users'] : 0;
            $TaipeiAndNotVip = $TaipeiAndNotVip['users'] != 0 ? $TaipeiAndNotVip['messages'] / $TaipeiAndNotVip['users'] : 0;
            $Vip = $Vip['users'] != 0 ? $Vip['messages'] / $Vip['users'] : 0;
            $NotVip = $NotVip['users'] != 0 ? $NotVip['messages'] / $NotVip['users'] : 0;

            $percentage['TaipeiAndVip'] = round($TaipeiAndVip, $place);
            $percentage['TaipeiAndNotVip'] = round($TaipeiAndNotVip, $place);
            $percentage['Vip'] = round($Vip, $place);
            $percentage['NotVip'] = round($NotVip, $place);

            return view('admin.users.statisticsReply')
                ->with('count', $count)
                ->with('percentage', $percentage);
        }
    }


    public function statisticsReply2(Request $request)
    {
    }

    public function getBirthday()
    {
        $users = UserMeta::select('id', 'user_id', 'birthdate_new')->get();
        $count = 0;
        foreach ($users as $user) {
            if ($user->birthdate_new == null) {
                continue;
            }
            if (\DateTime::createFromFormat('Y-m-d H:i:s', $user->birthdate_new) !== false) {
                continue;
            } else if (\DateTime::createFromFormat('Y-m-d', $user->birthdate_new) !== false) {
                continue;
            } else {
                $user->birthdate_new = null;
                $user->save();
                $count++;
            }
        }
        echo $count;
    }

    public function showAdminCheck()
    {
        $item_a = DB::table('account_name_change')->where('status', 0)->count();
        $item_b = DB::table('account_gender_change')->where('status', 0)->count();
        $item_c = DB::table('account_exchange_period')->where('status', 0)->count();
        $item_d = $this->raa_service->getAdminCheckNum();
        $item_e = DB::table('evaluation')
            ->whereNotNull('content_violation_processing')
            ->where('anonymous_content_status', 0)
            ->count();
        return view('admin.adminCheck')
            ->with('item_a', $item_a)
            ->with('item_b', $item_b)
            ->with('item_c', $item_c)
            ->with('item_d', $item_d)
            ->with('item_e', $item_e);
    }

    public function showAdminCheckNameChange()
    {
        $data = User::select('account_name_change.*', 'users.id', 'users.email', 'users.name', 'users.engroup')
            ->join('account_name_change', 'account_name_change.user_id', '=', 'users.id')
            ->orderBy('account_name_change.created_at', 'desc')->get();
        return view('admin.adminCheckNameChange')
            ->with('data', $data);
    }

    public function AdminCheckNameChangeSave(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $reject_content = $request->reject_content;
        DB::table('account_name_change')->where('user_id', $id)
            ->update(['status' => $status, 'passed_at' => now(), 'reject_content' => $reject_content]);

        $current_data = DB::table('account_name_change')->where('user_id', $id)->first();

        //notify
        if ($current_data->reject_content == '') {
            $text = '無法通過您的申請。';
        } else {
            $text = '因 ' . $current_data->reject_content . ' 原因無法通過您的申請。';
        }
        //        $user = User::findById($current_data->user_id);
        if ($status == 1) {
            //暱稱修改
            User::where('id', $current_data->user_id)->update(['name' => $current_data->change_name]);
            UserMeta::where('user_id', $current_data->user_id)->update(['name_change' => 1]);
            $user = User::findById($current_data->user_id);
            $content = $user->name . ' 您好：<br>您在 ' . $current_data->created_at . ' 申請變更暱稱修改，經站長審視已通過您的申請';
        } else {
            UserMeta::where('user_id', $current_data->user_id)->update(['name_change' => 1]);
            $user = User::findById($current_data->user_id);
            $content = $user->name . ' 您好：<br>您在 ' . $current_data->created_at . ' 申請變更暱稱修改，經站長審視，' . $text;
        }
        //        $user->notify(new AccountConsign('修改暱稱申請結果通知',$user->name, $content));

        //站長系統訊息
        Message::post(1049, $user->id, $content, true, 1);

        Session::flash('message', '審核已完成，系統將自動發信通知該會員');

        echo json_encode('ok');
    }

    public function showAdminCheckGenderChange()
    {
        $data = User::select('account_gender_change.*', 'users.id', 'users.email', 'users.name', 'users.engroup')
            ->join('account_gender_change', 'account_gender_change.user_id', '=', 'users.id')
            ->orderBy('account_gender_change.created_at', 'desc')->get();
        return view('admin.adminCheckGenderChange')
            ->with('data', $data);
    }

    public function AdminCheckGenderChangeSave(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $reject_content = $request->reject_content;
        DB::table('account_gender_change')->where('user_id', $id)
            ->update(['status' => $status, 'passed_at' => now(), 'reject_content' => $reject_content]);

        $current_data = DB::table('account_gender_change')->where('user_id', $id)->first();

        //notify
        if ($current_data->reject_content == '') {
            $text = '無法通過您的申請。';
        } else {
            $text = '因 ' . $current_data->reject_content . ' 原因無法通過您的申請。';
        }
        $user = User::findById($current_data->user_id);
        if ($status == 1) {
            $content = $user->name . ' 您好：<br>您在 ' . $current_data->created_at . ' 申請變更帳號類型，經站長審視已通過您的申請';
            //性別修改
            User::where('id', $current_data->user_id)->update(['engroup_change' => 1, 'engroup' => $current_data->change_gender]);
        } else {
            $content = $user->name . ' 您好：<br>您在 ' . $current_data->created_at . ' 申請變更帳號類型，經站長審視，' . $text;
            User::where('id', $current_data->user_id)->update(['engroup_change' => 1]);
        }
        //        $user->notify(new AccountConsign('變更帳號類型結果通知',$user->name, $content));

        //站長系統訊息
        Message::post(1049, $user->id, $content, true, 1);

        Session::flash('message', '審核已完成，系統將自動發信通知該會員');

        echo json_encode('ok');
    }


    public function showAdminCheckExchangePeriod()
    {
        $data = User::select('account_exchange_period.*', 'users.id', 'users.email', 'users.name', 'users.engroup')
            ->join('account_exchange_period', 'account_exchange_period.user_id', '=', 'users.id')
            ->orderBy('account_exchange_period.created_at', 'desc')->get();
        return view('admin.adminCheckExchangePeriod')
            ->with('data', $data);
    }

    public function showAdminCheckRealAuth()
    {
        $data['service'] = $this->raa_service;
        $data['row_list'] = $data['service']->getListInAdminCheck();

        return view('admin.adminCheckRealAuth')
            ->with($data);
    }

    public function showAdminCheckBeautyAuthForm($user_id)
    {
        $data['service'] = $this->raa_service->riseByUserId($user_id);
        $data['user'] = $data['service']->user();

        $data['apply_entry'] = $data['service']->getApplyByAuthTypeId(2);
        $data['entry_list'] =$data['service']->getBeautyAuthQuestionList();

        return view('admin.adminCheckRealAuthForm')
            ->with($data);
    }

    public function showAdminCheckFamousAuthForm($user_id)
    {
        $data['service'] = $this->raa_service->riseByUserId($user_id);
        $data['user'] = $data['service']->user();

        $data['apply_entry'] = $data['service']->getApplyByAuthTypeId(3);
        $data['entry_list'] = $data['service']->getFamousAuthQuestionList();
        return view('admin.adminCheckRealAuthForm')
            ->with($data);
    }

    public function AdminCheckExchangePeriodSave(Request $request)
    {
        $this->service->AdminCheckExchangePeriodSave($request,$this->raa_service);


        //站長系統訊息
        Message::post(1049, $user->id, $content, true, 1);

        Session::flash('message', '審核已完成，系統將自動發信通知該會員');

        echo json_encode('ok');
    }

    public function showAnonymousChatMessage($chat_id)
    {
        $evaluation = User::select(
            'e.to_id',
            'e.from_id',
            'to_user.name as to_user_name',
            'from_user.name as from_user_name'
        )
            ->join('evaluation as e', 'e.from_id', 'users.id')
            ->join('users as to_user', 'e.to_id', 'to_user.id')
            ->join('users as from_user', 'e.from_id', 'from_user.id')
            ->where('e.id', $chat_id)
            ->first();

        $anonymous_evaluation_chat_id = AnonymousEvaluationChat::where('evaluation_id', $chat_id)->pluck('id')->first();
        $messages = AnonymousEvaluationMessage::where('anonymous_evaluation_chat_id', $anonymous_evaluation_chat_id)->get()->map(function($msg) use ($evaluation) {
            $from_user = $msg->user_id;
            if($from_user == $evaluation->to_id) {
                $msg->to_user_name = $evaluation->from_user_name;
                $msg->from_user_name = $evaluation->to_user_name;
                $msg->to_user = $evaluation->from_id;
                $msg->from_user = $evaluation->to_id;

            }else {
                $msg->to_user_name = $evaluation->to_user_name;
                $msg->from_user_name = $evaluation->from_user_name;
                $msg->to_user = $evaluation->to_id;
                $msg->from_user = $evaluation->from_id;
            }
            return $msg;
        });

        return view('admin.users.showAnonymousChatMessage', compact('messages'));
    }

    public function showAdminCheckAnonymousContent(Request $request)
    {
        $show_passed = $request->passed;
        $show_unpassed = $request->unpassed;
        $query = User::select(
            'e.content',
            'e.content_violation_processing',
            'e.anonymous_content_status',
            'e.created_at',
            'e.deleted_at',
            'e.id as evaluation_id',
            'e.to_id',
            'e.only_show_text',
            'users.id',
            'users.email',
            'users.name',
            'users.engroup',
            'users.account_status_admin',
            'users.accountStatus'
        )
            ->join('evaluation as e', 'e.from_id', 'users.id')
            ->whereNotNull('e.content_violation_processing')
            ->orderBy('e.created_at', 'desc');
        /*
        if(!$show_passed) {
            $query->where('anonymous_content_status','<>',1);
        }
        if(!$show_unpassed) {
            $query->where('anonymous_content_status','<>',2);
        }
        */

        $data = $query->get();
        foreach ($data as $key => $row) {
            $data[$key]['pic'] = EvaluationPic::select('pic')->where('evaluation_id', $row['evaluation_id'])->where('member_id', $row['id'])->get();
        }

        return view('admin.adminCheckAnonymousContent')
            ->with('data', $data);
    }

    public function AdminCheckAnonymousContentSave(Request $request)
    {
        $evaluation_id = $request->evaluation_id;
        $status = $request->status;
        $status_reason = $request->status_reason;
        $only_show_text = $request->only_show_text;
        $evaluation_entry = Evaluation::find($evaluation_id);
        if($evaluation_entry) {
            if($evaluation_entry->anonymous_content_status==1 && $status!=1 || ($evaluation_entry->anonymous_content_status==2 && $status!=2)) {
                $evaluation_entry->last_content_status = $evaluation_entry->anonymous_content_status;
                $evaluation_entry->last_status_at = $evaluation_entry->status_at;
                $evaluation_entry->last_status_canceled_at = Carbon::now();
                $evaluation_entry->last_status_reason = $evaluation_entry->status_reason;
                $evaluation_entry->last_status_message_id = $evaluation_entry->status_message_id;
                $evaluation_entry->last_only_show_text = $evaluation_entry->only_show_text;
                $evaluation_entry->anonymous_content_status = $evaluation_entry->status_at = $evaluation_entry->status_reason = $evaluation_entry->status_message_id = $evaluation_entry->only_show_text = null;
                $evaluation_entry->save();
            }

        }

        DB::table('evaluation')->where('id', $evaluation_id)->update(['anonymous_content_status' => $status, 'updated_at' => now(),'status_reason'=>$status_reason,'only_show_text'=>$only_show_text,'status_at'=>Carbon::now()]);
        $evaluation_entry = Evaluation::find($evaluation_id);

        if($evaluation_entry->last_status_message_id) {
            Message::where('id',$evaluation_entry->last_status_message_id)->delete();
        }

        $content = '';
        if($status==1) {
            $content = $evaluation_entry->user->name . ' 您好，您在 ' . substr($evaluation_entry->created_at,0,16) .'對'.$evaluation_entry->receiver->name
                .' 提出的匿名訊息，經站長審核已通過'.($only_show_text?' (僅文字，不附照片) ':'').'。評價已上線，'.$evaluation_entry->receiver->name
                .'可以透過匿名對話向您解釋狀況，您可以自己決定是否回應。有問題可點右下聯絡我們或加站長<a href="https://lin.ee/rLqcCns"><img src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png" alt="加入好友" height="26" border="0" style="all: initial;all: unset;height: 26px; float: unset;"></a>反應';
        } else if($status==2) {
            //230321 不通過直接刪除，讓會員可以再次評價
            $evaluation_entry->delete();
            //230301 先拿掉不通過會自動發送系統訊息的功能
            /* 
            $content = $evaluation_entry->user->name . ' 您好，您在 ' . substr($evaluation_entry->created_at,0,16) .'對'.$evaluation_entry->receiver->name
                .' 提出的匿名訊息，經站長審核，由於證據不足且您拒絕站方修改。故評價已撤回，您可以檢附證據重新評價，有問題可點右下聯絡我們或加站長<a href="https://lin.ee/rLqcCns"><img src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png" alt="加入好友" height="26" border="0" style="all: initial;all: unset;height: 26px; float: unset;"></a>反應';


            if($status_reason) {
                $content = $evaluation_entry->user->name . ' 您好，您在 ' . substr($evaluation_entry->created_at,0,16) .'對'.$evaluation_entry->receiver->name
                    .' 提出的匿名訊息，由於 '.$status_reason.'原因。故評價已撤回，您可以檢附證據重新評價。有問題可點右下聯絡我們或加站長<a href="https://lin.ee/rLqcCns"><img src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png" alt="加入好友" height="26" border="0" style="all: initial;all: unset;height: 26px; float: unset;"></a>反應';
            }
            */

        }
        //站長系統訊息
        if($content) {
            $message_entry = Message::post(1049, $evaluation_entry->user->id, $content);
            $evaluation_entry->status_message_id = $message_entry->id;
            $evaluation_entry->save();
        }
        Session::flash('message', '審核已完成');

        echo json_encode('ok');
    }

    public function showAdminCheckAnonymousBetweenMessages(Request $request,$evaluate_from,$evaluate_to)
    {
        $messages =
            Message::withTrashed()->where('from_id',$evaluate_from)->where('to_id',$evaluate_to)
                ->orWhere(function($q) use ($evaluate_from,$evaluate_to)  {
                    $q->where('to_id',$evaluate_from)->where('from_id',$evaluate_to);
                })
                ->orderByDesc('id')
                ->get();
        $message_1st = $messages->first();
        $ref_user = $evaluator = $ref_user_id = null;
        if($message_1st) {
            if($message_1st->from_id==$evaluate_from){
                $ref_user = $message_1st->receiver;
                $evaluator =  $message_1st->sender;
            } else if($message_1st->to_id==$evaluate_from) {
                $ref_user = $message_1st->sender;
                $evaluator =  $message_1st->receiver;
            }
        }

        if($ref_user) $ref_user_id = $ref_user->id;

        $cwa_user = $evaluator;

        return view('admin.adminCheckAnonymousBetweenMessages')
            ->with('message_1st', $message_1st)
            ->with('evaluate_from',$evaluate_from)
            ->with('evaluate_to',$evaluate_to)
            ->with('ref_user',$ref_user)
            ->with('ref_user_id',$ref_user_id)
            ->with('evaluator',$evaluator)
            ->with('cwa_user',$cwa_user)
            ->with('messages',$messages);

    }

    public function showSpamTextMessage()
    {
        return view('admin.users.searchSpamTextMessage');
    }

    public function searchSpamTextMessage(Request $request)
    {
        $date_start = $request->date_start ? $request->date_start : '0000-00-00';
        $date_end = $request->date_end ? $request->date_end : date('Y-m-d');

        $bannedUsers = UserService::getBannedId();
        $isAdminWarnedList = warned_users::select('member_id')->where('expire_date', '>=', Carbon::now())->orWhere('expire_date', null)->get();

        $query = Message::select('users.email', 'users.name', 'users.title', 'users.engroup', 'users.created_at', 'users.last_login', 'message.id', 'message.from_id', 'message.content', 'user_meta.about')
            ->join('users', 'message.from_id', '=', 'users.id')
            ->join('user_meta', 'message.from_id', '=', 'user_meta.user_id')
            ->where(function ($query) use ($date_start, $date_end, $bannedUsers, $isAdminWarnedList) {
                $query->where('message.from_id', '<>', 1049)
                    ->where('message.sys_notice', 0)
                    ->orWhereNull('message.sys_notice')
                    ->whereNotIn('message.from_id', $bannedUsers)
                    ->whereNotIn('message.from_id', $isAdminWarnedList)
                    ->whereBetween('message.created_at', array($date_start . ' 00:00', $date_end . ' 23:59'));
            });
        if (isset($request->gender)) {
            if ($request->gender != 0) {
                $query->where('users.engroup', $request->gender);
            }
        }
        if (isset($request->search_email)) {
            $search_email = explode(',', $request->search_email);
            if ($search_email) {
                $in_email = array();
                foreach ($search_email as $email) {
                    array_push($in_email, $email);
                }
            } else {
                $in_email = $request->search_email;
            }
            $query->whereIn('users.email', [$in_email]);
        }
        if (isset($request->time) && $request->time == 'created_at') {
            $query->orderBy('users.created_at', 'desc');
        }
        if (isset($request->time) && $request->time == 'last_login') {
            $query->orderBy('users.last_login', 'desc');
        }
        $results_a = $query->distinct('message.from_id')->take($request->users_counts)->get();

        if ($results_a != null) {

            $results = collect([$results_a])->collapse()->unique('from_id');

            //all_user
            $data_all = array();
            foreach ($results as $result) {

                //single user
                $msg = array();
                $from_content = array();
                $user_similar_msg = array();

                $messages = Message::select('id', 'content', 'created_at')
                    ->where('from_id', $result->from_id)
                    ->where(function ($query) {
                        $query->where('sys_notice', 0)
                            ->orWhereNull('sys_notice');
                    })
                    ->whereBetween('created_at', array($date_start . ' 00:00', $date_end . ' 23:59'))
                    ->orderBy('created_at', 'desc')
                    ->take(100)
                    ->get();

                foreach ($messages as $row) {
                    array_push($msg, array('id' => $row->id, 'content' => $row->content, 'created_at' => $row->created_at));
                }

                array_push($from_content,  array('msg' => $msg));

                $unique_id = array(); //過濾重複ID用
                //比對訊息
                foreach ($from_content as $data) {
                    foreach ($data['msg'] as $word1) {
                        foreach ($data['msg'] as $word2) {
                            if ($word1['created_at'] != $word2['created_at']) {
                                similar_text($word1['content'], $word2['content'], $percent);
                                if ($percent >= $request->percent) {
                                    if (!in_array($word1['id'], $unique_id)) {
                                        array_push($unique_id, $word1['id']);
                                        array_push($user_similar_msg, array($word1['id'], $word1['content'], $word1['created_at'], $percent));
                                    }
                                }
                            }
                        }
                    }
                }

                //single user end

                //all_users
                //push_data
                if (count($user_similar_msg) > 0 && round((count($user_similar_msg) / count($messages)) * 100) >= $request->display_percent) {
                    array_push($data_all, array(
                        'user_id' => $result->from_id,
                        'email' => $result->email,
                        'name' => $result->name,
                        'engroup' => $result->engroup,
                        'title' => $result->title,
                        'about' => $result->about,
                        'created_at' => $result->created_at,
                        'last_login' => $result->last_login,
                        'all_msg_counts' => count($messages),
                        'similar_msg' => $user_similar_msg
                    ));
                }
            }
        }

        return view('admin.users.searchSpamTextMessage')
            ->with('data_all', $data_all);
    }

    public function memberList()
    {
        return view('admin.users.memberList');
    }

    public function searchMemberList(Request $request)
    {
        $date_start = $request->date_start ? $request->date_start : '0000-00-00';
        $date_end = $request->date_end ? $request->date_end : date('Y-m-d');

        $query = users::select('users.*', 'user_meta.*')
            ->join('user_meta', 'users.id', '=', 'user_meta.user_id')
            ->where(function ($query) use ($date_start, $date_end) {
                $query->where('users.id', '<>', 1049)
                    ->whereBetween('users.last_login', array($date_start . ' 00:00', $date_end . ' 23:59'));
            });

        if (isset($request->gender)) {
            if ($request->gender != 0) {
                $query->where('users.engroup', $request->gender);
            }
        }

        if (isset($request->time) && $request->time == 'created_at') {
            $query->orderBy('users.created_at', 'desc');
        }

        if (isset($request->time) && $request->time == 'last_login') {
            $query->orderBy('users.last_login', 'desc');
        }

        $results = $query->take($request->users_counts)->get();

        return view('admin.users.memberList')->with('results', $results);
    }


    public function picMemberList(Request $request)
    {
        $req = null;

        if (!$request->query()) {
            if (session()->has('request')) $req = session()->get('request');
            else if (session()->has('request_renew')) $req = session()->get('request_renew');
        }

        Session::forget('request');
        Session::forget('request_renew');

        if ($req && !$request->query()) session()->put('request_renew', $req);

        $data['request'] = $req;
        $data['default_apply_time'] = Carbon::now()->addMinutes(10)->format('H:i');
        if ($req) {
            $newReq = new Request($req);
            return $this->searchPicMemberList($newReq);
        }
        return view('admin.users.picMemberList', $data);
    }

    public function searchPicMemberList(Request $request)
    {
        $data['default_apply_time'] = Carbon::now()->addMinutes(10)->format('H:i');
        $data['request'] = $request;
        $query = $this->_getPicMemberListQuery($request);

        $results = $query->get();

        return view('admin.users.picMemberList', $data)->with('results', $results)
            ->with('comparison', new ImagesCompareService)
            ->with('similarity', new SimilarImages);
    }

    private function _getPicMemberListQuery($request)
    {
        $date_start = $request->date_start ? $request->date_start : '0000-00-00';
        $date_end = $request->date_end ? $request->date_end : date('Y-m-d');

        $query = User::with('meta')
            ->where(function ($query) use ($date_start, $date_end) {
                $query->where('id', '<>', 1049)
                    ->whereBetween('created_at', array($date_start . ' 00:00', $date_end . ' 23:59'));
            });

        if (isset($request->gender)) {
            if ($request->gender != 0) {
                $query->where('engroup', $request->gender);
            }
        }

        if (isset($request->time) && $request->time == 'created_at') {
            $query->orderBy('created_at', 'desc');
        }

        if (isset($request->time) && $request->time == 'last_login') {
            $query->orderBy('last_login', 'desc');
        }

        $query->take($request->users_counts);

        return $query;
    }

    public function applyPicMemberList(Request $request)
    {
        $now = Carbon::now();
        $req = $request->all();
        $job_show_name = '';
        $s_job_show_name = '以圖找圖';
        $c_job_show_name = '站內搜圖';
        $apply_date = $request->apply_date_start ?? date('Y-m-d');
        $apply_time = $request->apply_time_start ?? date('H:i');
        $apply_type = $request->apply_type ?? [];
        if (!$apply_type) {
            return back()->with('request', $req)->withErrors(['設定失敗！未勾選任何一個照片送檢類型。']);
        }
        $apy_datetime = Carbon::parse($apply_date . ' ' . $apply_time);
        $delay = 0;
        if ($apy_datetime < $now) {
            return back()->with('request', $req)->withErrors(['設定失敗！所設定的開始佇列時間早於目前的時間。']);
        } else {
            $delay = $apy_datetime->diffInSeconds($now);
        }
        $query = $this->_getPicMemberListQuery($request);
        $apy_user_entrys = $query->with('pic_withTrashed', 'avatar_deleted')->get();

        $pic_num = 0;
        $pic_s_num = 0;
        $pic_c_num = 0;
        $pic_entrys =  $apy_user_entrys->pluck('meta')
            ->merge($apy_user_entrys->pluck('pic_withTrashed')->flatten())
            ->merge($apy_user_entrys->pluck('avatar_deleted')->flatten());
        foreach ($pic_entrys as $item) {
            if (!$item->pic ?? null) continue;
            $pic_num++;

            if (in_array('s', $apply_type)) {
                if (SimilarImages::where('pic', $item->pic)->where('status', 'success')->count() == 0) {
                    \App\Jobs\SimilarImagesSearcher::dispatch($item->pic)->delay($delay);;
                    $pic_s_num++;
                }
            }

            if (in_array('c', $apply_type)) {
                if ($item->compareImages('UserController@applyPicMemberList', $delay))
                    $pic_c_num++;
            }
        }

        if (count($apply_type) > 1) {
            $job_show_name = '以圖找圖和站內搜圖';
        } else if (count($apply_type) == 1) {
            switch ($apply_type[0]) {
                case 's':
                    $job_show_name = '以圖找圖';
                    break;
                case 'c':
                    $job_show_name = '站內搜圖';
                    break;
            }
        }
        $result_msg = '';
        if ($pic_s_num) {
            $result_msg .= $pic_s_num . '張照片列入' . $s_job_show_name;
        }

        if ($pic_c_num) {
            if ($result_msg) $result_msg .= '、';
            $result_msg .= $pic_c_num . '張照片列入' . $c_job_show_name;
        }

        if ($result_msg) {
            return back()->with('request', $req)->with('message', ' 成功將 ' . $result_msg . ' 送檢佇列');
        } else {
            if ($pic_num)
                return back()->with('request', $req)->with('message', '未做任何處理。' . $pic_num . ' 張照片都已曾做過' . $job_show_name);
            else return back()->with('request', $req);
        }
    }

    public function showSendUserMessage()
    {
        $log_data = DB::table('message_admin_sent_user_log')
            ->select('message.*')
            ->join('message', 'message_admin_sent_user_log.message_id', 'message.id')
            ->orderBy('message_admin_sent_user_log.created_at', 'desc')->get();

        return view('admin.adminSendUserMessage')->with('log_data', $log_data);
    }

    public function sendUserMessageFindUserInfo(Request $request)
    {
        $user = User::findByEmail($request->email);

        if (isset($user)) {
            echo json_encode(['pic' => $user->meta_()->pic, 'name' => $user->name, 'title' => $user->title, 'gender' => $user->engroup, 'status' => 'ok']);
        } else {
            echo json_encode(['status' => 'error']);
        }
        //echo json_encode($user->email);
    }

    public function sendUserMessage(Request $request)
    {
        $from_user = User::findByEmail($request->input('from-email'));
        $to_user = User::findByEmail($request->input('to-email'));

        if (!isset($from_user) || !isset($to_user)) {
            return back()->with('message', '請確認雙方EMAIL是否正確');
        }

        if ($from_user->engroup == $to_user->engroup) {
            return back()->with('message', '相同性別無法傳送訊息');
        }

        Message::post($from_user->id, $to_user->id, $request->input('sendContent'), false, 0);

        //find message id to log
        $lastMessage = Message::latestMessage($from_user->id, $to_user->id);

        DB::table('message_admin_sent_user_log')->insert(['message_id' => $lastMessage->id]);

        $log_data = DB::table('message_admin_sent_user_log')
            ->select('message.*')
            ->join('message', 'message_admin_sent_user_log.message_id', 'message.id')
            ->orderBy('message_admin_sent_user_log.created_at', 'desc')->get();

        return back()->with('message', '訊息發送成功')->with('log_data', $log_data);
    }

    public function adminActionLog(Request $request)
    {
        $operator_list = RoleUser::leftJoin('users', 'users.id', '=', 'role_user.user_id')->get();

        $getLogs = [];
        $test_result = [];
        if (!empty($request->get('date_start')) && !empty($request->get('date_end')) && count($request->get('operator'))) {
            $select_operator = RoleUser::leftJoin('users', 'users.id', '=', 'role_user.user_id')
                ->whereIn('user_id', $request->get('operator'))
                ->get();
            $result = [];
            foreach ($select_operator as $key => $operator) {
                $result[$key] = $operator->toArray();
                $get_operator_by_date = AdminActionLog::selectRaw('LEFT(admin_action_log.created_at,10) as log_by_date, (count(*)) AS count_by_date')->orderBy('admin_action_log.created_at', 'desc')
                    ->where('admin_action_log.operator', $operator->user_id)
                    ->groupBy('log_by_date');
                $get_operator_by_date->where('admin_action_log.created_at', '>=', $request->get('date_start'));
                $get_operator_by_date->where('admin_action_log.created_at', '<=', date("Y-m-d", strtotime("+1 day", strtotime($request->get('date_end')))));
                $result[$key]['operator_by_date'] = $get_operator_by_date->get()->toArray();
                $result[$key]['dataCount'] = count($result[$key]['operator_by_date']);

                $get_test_result = SpecialIndustriesTestAnswer::leftJoin('users','users.id', '=', 'special_industries_test_answer.test_user')
                    ->leftJoin('special_industries_test_topic','special_industries_test_topic.id', '=', 'special_industries_test_answer.test_topic_id')
                    ->leftJoin('special_industries_test_setup','special_industries_test_setup.id', '=', 'special_industries_test_topic.test_setup_id')
                    ->where('test_user', $operator->user_id);
                $get_test_result = $get_test_result->where('special_industries_test_answer.updated_at','>=',$request->get('date_start'));
                $get_test_result = $get_test_result->where('special_industries_test_answer.updated_at','<=',date("Y-m-d", strtotime("+1 day", strtotime($request->get('date_end')))));

                $get_test_result = $get_test_result->select('special_industries_test_answer.*','users.*','special_industries_test_topic.*','special_industries_test_setup.*','special_industries_test_answer.updated_at as filled_time','special_industries_test_answer.id as answer_id')
                    ->orderByDesc('special_industries_test_answer.updated_at')
                    ->get();
                $result[$key]['test_result'] = $get_test_result;
                $result[$key]['test_result_count'] = count($result[$key]['test_result']);
            }
            $getLogs = $result;
        }

        return view('admin.users.showAdminActionLog', compact('operator_list', 'getLogs'));
    }

    public function getEssenceStatisticsRecord(Request $request)
    {
        $getLogs = EssenceStatisticsLog::selectRaw('essence_statistics_log.user_id, users.name AS user_name, users.email AS user_email')
            ->selectRaw('count(*) AS dataCount')
            ->leftJoin('users', 'users.id', '=', 'essence_statistics_log.user_id')
            ->orderBy('essence_statistics_log.created_at', 'desc')
            ->groupBy('essence_statistics_log.user_id')
            ->get();

        $result = [];
        foreach ($getLogs as $key => $log) {
            $result[$key] = $log->toArray();
            $get_operator_by_date = EssenceStatisticsLog::selectRaw('essence_statistics_log.*, users.name AS user_name, users.email AS user_email, essence_posts.title AS essence_posts_title')
                ->leftJoin('essence_posts', 'essence_posts.id', '=', 'essence_statistics_log.essence_posts_id')
                ->leftJoin('users', 'users.id', '=', 'essence_posts.user_id')
                ->where('essence_statistics_log.user_id', $log->user_id)
                ->orderBy('essence_statistics_log.created_at', 'desc')
                ->get();
            $result[$key]['log_list'] = $get_operator_by_date->toArray();
        }
        $getLogs = $result;
        return view('admin.users.showEssenceStatisticsRecord', compact('getLogs'));
    }

    public function showUserPicturesSimple()
    {
        return view('admin.users.userPicturesSimple');
    }

    //    public function adminRole(Request $request)
    //    {
    //        $role_data = DB::table('role_user')
    //            ->select('ru.*','r.*','u.email','u.name as user_name')
    //            ->from('role_user as ru')
    //            ->leftJoin('roles as r','r.id','ru.role_id')
    //            ->leftJoin('users as u','u.id','ru.user_id')
    //            ->whereNotNull('u.id')
    //            ->orderBy('ru.user_id')
    //            ->get();
    //
    //        $permission_data = DB::table('roles')->get();
    //
    //
    //        return view('admin.adminRole', compact('role_data','permission_data'));
    //    }

    //    public function adminRoleEdit(Request $request)
    //    {
    //        $email = $request->input('email');
    //        $role_id = $request->input('permission_id');
    //        $delete_mode = $request->input('delete_mode');
    //        $user = User::where('email', $email)->first();
    //        if($delete_mode=='off') {
    //
    //            if (!$user) {
    //                return back()->with('message', '查無此會員');
    //            } else {
    //                $role_user = DB::table('role_user')->where('user_id', $user->id)->first();
    //                if ($role_user) {
    //                    //update
    //                    DB::table('role_user')->where('user_id', $user->id)->update(['role_id' => $role_id]);
    //                    return back()->with('message', '資料已更新');
    //                } else {
    //                    //insert
    //                    DB::table('role_user')->insert(['user_id' => $user->id, 'role_id' => $role_id]);
    //                    return back()->with('message', '新增成功');
    //                }
    //            }
    //
    //        }else{
    //            DB::table('role_user')->where(['user_id' => $user->id, 'role_id' => $role_id])->delete();
    //            return back()->with('message', '刪除成功');
    //        }
    //    }

    public function searchUserPicturesSimple(Request $request)
    {
        $data = User::with('suspicious')
            ->with('aw_relation')
            ->with('banned')
            ->with('implicitlyBanned')
            ->with('check_point_user');

        if ($request->hidden) {
            $data = $data->with(['pic_orderByDecs' => function ($query) {
                $query->take(3);
            }]);
        } else {
            $data = $data->with(['pic_orderByDecs' => function ($query) {
                $query->where('isHidden', false)->take(3);
            }]);
        }

        $data = $data->whereDoesntHave('suspicious')
            ->whereDoesntHave('banned')
            ->whereDoesntHave('implicitlyBanned')
            ->whereDoesntHave('aw_relation')
            ->whereDoesntHave('user_meta', function ($query) {
                $query->where('isWarned', true);
            })
            ->whereDoesntHave('check_point_name', function ($query) {
                $query->where('name', 'step_1_ischecked');
            })
            ->whereHas('user_meta', function ($query) {
                $query->whereNotNull('smoking')->whereNotNull('drinking')
                    ->whereNotNull('marriage')->whereNotNull('education')->whereNotNull('about')->whereNotNull('style')
                    ->whereNotNull('birthdate')->whereNotNull('area')->whereNotNull('city');
            });


        if ($request->date_start) {
            $datastart = $request->date_start;
            $data = $data->whereHas('user_meta', function ($query) use ($datastart) {
                $query->where('updated_at', '>=', $datastart);
            });
        }

        if ($request->date_end) {
            $dataend = $request->date_end;
            $data = $data->whereHas('user_meta', function ($query) use ($dataend) {
                $query->where('updated_at', '<=', $dataend . ' 23:59:59');
            });
        }

        if ($request->en_group) {
            $data = $data->where('users.engroup', $request->en_group);
        }

        if ($request->city) {
            $city = $request->city;
            $data = $data->whereHas('user_meta', function ($query) use ($city) {
                $query->where('city', $city);
            });
        }

        if ($request->area) {
            $area = $request->area;
            $data = $data->whereHas('user_meta', function ($query) use ($area) {
                $query->where('area', $area);
            });
        }

        if (isset($request->order_by) && $request->order_by == 'last_login') {
            $data = $data->orderBy('users.last_login', 'desc');
        }

        //預設排序
        if ($request->order_by == '') {
            $data = $data->orderBy('users.last_login', 'desc');
        }

        //以更新時間排序
        if (isset($request->order_by) && $request->order_by == 'updated_at') {
            $data = $data->orderBy(UserMeta::select('updated_at')->whereColumn('user_meta.user_id', 'users.id'), 'DESC');
        }

        $data = $data->paginate(15);

        $account = array();
        $user_id_of_page = array();
        foreach ($data as $key => $d) {
            $account[$key]['vip'] = \App\Models\Vip::vip_diamond($d->id);
            $account[$key]['tipcount'] = \App\Models\Tip::TipCount_ChangeGood($d->id);

            $account[$key]['pic'] = array();
            $count = 0;
            foreach ($d->pic_orderByDecs as $pic) {
                $account[$key]['pic'][] = $pic->pic;
                $count = $count + 1;
            }
            $user_id_of_page[] = $d->id;
        }

        //原始程式碼(大爆改...)
        /*
        $pics = MemberPic::select('member_pic.*')->from('member_pic')
            ->leftJoin('users', 'users.id', '=', 'member_pic.member_id')
            ->leftJoin('user_meta', 'user_meta.user_id', '=', 'member_pic.member_id')
            ->leftJoin('suspicious_user', function ($join){
                $join->on('users.id','=','suspicious_user.user_id')
                    ->where('suspicious_user.deleted_at',null);
            })
            ->selectRaw('member_pic.id, member_pic.member_id, member_pic.pic, users.name, member_pic.updated_at, users.email, users.title, users.last_login, user_meta.about, user_meta.style, suspicious_user.user_id as sid')
            ->whereNotNull('member_pic.pic')
            ->whereNotNull('users.id');

        if ($request->hidden) {
            $pics = $pics->where('member_pic.isHidden', 1)->where('user_meta.isAvatarHidden', 1);
        } else {
            $pics = $pics->where('member_pic.isHidden', 0)->where('user_meta.isAvatarHidden', 0);
        }
        if ($request->date_start) {
            $pics = $pics->where('member_pic.updated_at', '>=', $request->date_start);
        }
        if ($request->date_end) {
            $pics = $pics->where('member_pic.updated_at', '<=', $request->date_end . ' 23:59:59');
        }
        if ($request->en_group) {
            $pics = $pics->where('users.engroup', $request->en_group);
        }
        if ($request->city) {
            $pics = $pics->where('user_meta.city', $request->city);
        }
        if ($request->area) {
            $pics = $pics->where('user_meta.area', $request->area);
        }
        if(isset($request->order_by) && $request->order_by=='updated_at'){
            $pics = $pics->orderBy('member_pic.updated_at','desc');
        }
        if(isset($request->order_by) && $request->order_by=='last_login'){
            $pics = $pics->orderBy('users.last_login','desc');
        }

        //預設排序
        if($request->order_by==''){
            $pics = $pics->orderBy('member_pic.updated_at','desc');
        }

        $pics = $pics->paginate(15);

        $account = array();
        foreach ($pics as $key => $pic) {
            $user = User::where('id', $pic->member_id)->get()->first();
            $userMeta = UserMeta::where('user_id', $pic->member_id)->get()->first();
            if(is_null($user)){
                continue;
            }
            $account[$key]['user'] = $user;
            $account[$key]['userMeta'] = $userMeta;
            $account[$key]['engroup'] = $user->engroup;
            $account[$key]['isVip'] = $user->isVip();
            $account[$key]['auth_status'] = 0;
            if ($user->isPhoneAuth() == 1) $account[$key]['auth_status'] = 1;
            $account[$key]['tipcount']= \App\Models\Tip::TipCount_ChangeGood($pic->member_id);
            $account[$key]['vip'] = \App\Models\Vip::vip_diamond($pic->member_id);
            $account[$key]['isBlocked'] = \App\Models\SimpleTables\banned_users::where('member_id', $pic->member_id)->orderBy('created_at', 'desc')->get()->first();
            if (!isset($account[$key]['isBlocked'])) {
                $account[$key]['isBlocked'] = \App\Models\BannedUsersImplicitly::where('target', $pic->member_id)->get()->first();
                if (isset($account[$key]['isBlocked'])) {
                    $account[$key]['isBlocked_implicitly'] = 1;
                }
            }

            $data = \App\Models\SimpleTables\warned_users::where('member_id', $pic->member_id)->orderBy('created_at', 'desc')->first();
            if (isset($data) && ($data->expire_date == null || $data->expire_date >= Carbon::now())) {
                $account[$key]['isAdminWarned'] = 1;
                $account[$key]['adminWarned_expireDate'] = $data->expire_date;
                $account[$key]['adminWarned_createdAt'] = $data->created_at;
            } else {
                $account[$key]['isAdminWarned'] = 0;
            }
        }
        */

        return view(
            'admin.users.userPicturesSimple',
            [
                'data' => $data,
                'account' => $account,
                'user_id_of_page' => $user_id_of_page,
                'en_group' => isset($request->en_group) ? $request->en_group : null,
                'order_by' => isset($request->order_by) ? $request->order_by : null,
                'city' => isset($request->city) ? $request->city : null,
                'area' => isset($request->area) ? $request->area : null,
                'hiddenSearch' => isset($request->hidden) ? true : false
            ]
        );
    }

    public function suspicious_user_toggle(Request $request)
    {
        $sid = $request->sid;
        $uid = $request->uid;
        $reason = $request->reason;
        $admin = Auth::user();
        $ip = array_get($_SERVER, 'REMOTE_ADDR');

        if ($sid == '') {
            SuspiciousUser::insert_data($admin, $uid, $reason, $ip);
            return back()->with('message', '已加入可疑名單');
        } else {
            SuspiciousUser::delete_data($admin, $uid, $reason, $ip);
            return back()->with('message', '已至可疑名單移除');
        }
    }

    public function suspiciousUser(Request $request)
    {

        $query = SuspiciousUser::select('users.*', 'user_meta.pic', 'user_meta.style', 'user_meta.about', 'suspicious_user.admin_id AS suspicious_admin_id', 'suspicious_user.created_at AS suspicious_created_time', 'suspicious_user.reason AS suspicious_reason')
            ->leftJoin('users', 'users.id', 'suspicious_user.user_id')
            ->leftJoin('user_meta', 'user_meta.user_id', 'suspicious_user.user_id')
            ->where('suspicious_user.deleted_at', null)
            ->whereNotNull('users.id')
            ->orderBy('suspicious_user.created_at', 'desc')
            ->paginate(20);
        $suspiciousUser = $query;
        $admin_array = RoleUser::get()->pluck('user_id');
        $adminInfo_array = User::whereIn('id', $admin_array)->get();
        $adminInfo = [];
        foreach($adminInfo_array as $info) {
            $adminInfo[$info->id] = $info;
        }
        $communication_count_weekly_set = intval(DB::table('queue_global_variables')->where('name','suspicious_list_communication_weekly_count_set')->first()->value);
        $country_count_set = intval(DB::table('queue_global_variables')->where('name','suspicious_list_communication_country_count_set')->first()->value);
        return view('admin.users.suspiciousUser', compact('suspiciousUser','adminInfo','communication_count_weekly_set','country_count_set'));
    }

    public function modifyContent(Request $request)
    {
        $evaluation_content = $request->input('evaluation_content');
        $evaluation_content = str_replace('|$responseTime|', date("Y-m-d H:i:s"), $evaluation_content);
        $evaluation_content = str_replace('|$reportTime|', date("Y-m-d H:i:s"), $evaluation_content);
        $evaluation_content = str_replace('NOW_TIME', date("Y-m-d H:i:s"), $evaluation_content);
        $evaluation_content = str_replace('LINE_ICON', AdminService::$line_icon_html, $evaluation_content);
        $evaluation_content = str_replace('|$lineIcon|', AdminService::$line_icon_html, $evaluation_content);
        DB::table('evaluation')->where('id', $request->input('id'))->update(
            ['content' => $evaluation_content]
        );
        return back()->with('message', '評價內容已更新');
    }

    public function adminComment(Request $request)
    {
        DB::table('evaluation')->where('id', $request->input('id'))->update(
            ['admin_comment' => $request->input('admin_comment')]
        );
        return back()->with('message', '站方附註留言已更新');
    }

    public function evaluationDelete(Request $request)
    {
        Evaluation::where('id', $request->id)->delete();
        return back()->with('message', '評價已刪除');
    }

    public function evaluationCheck(Request $request)
    {
        DB::table('evaluation')->where('id', $request->input('id'))->update(['is_check' => $request->get('is_check')]);

        if ($request->get('is_check')) {
            $msg = '評價內容審核中';
            //return redirect('admin/users/message/to/'.$request->get('userid'))->with('message', '評價內容審核中');

        } else {
            $msg = '評價內容審核結束';
            //return back()->with('message', '評價內容審核中');
        }
        return json_encode(array('code' => '200', 'status' => 'success', 'msg' => $msg, 'redirect_to' => '/admin/users/message/to/' . $request->get('userid')));
    }

    public function modifyPhone(Request $request)
    {
        if ((DB::table('short_message')->where('mobile', $request->phone)->where('active', 1)->first() !== null) && !empty($request->phone)) {
            return back()->with('error', '已存在資料,手機號碼重複驗證');
        }

        //        if (DB::table('short_message')->where([['member_id', $request->user_id]])->first() == null) {
        //            DB::table('short_message')->insert(['member_id' =>  $request->user_id, 'mobile' => $request->phone, 'active' =>1]);
        //        }else{
        //            DB::table('short_message')->where('member_id', $request->user_id)->update(['mobile' => $request->phone, 'active' =>1]);
        //        }

        //先刪後增
        ShortMessageService::deleteShortMessageByUserId($request->user_id);
        DB::table('short_message')->insert(['member_id' =>  $request->user_id, 'mobile' => $request->phone, 'active' => 1,'created_by'=>auth()->id(),'created_from'=>$request->path()]);

        UserMeta::where('user_id', $request->user_id)->update(['phone' => $request->phone]);
        event(new \App\Events\CheckWarnedOfReport($request->user_id));

        //驗證成功解除尚未手機驗證警示
        SetAutoBan::relieve_mobile_verify_warned($request->user_id);

        return back()->with('message', $request->pass ? '已通過手機驗證' : '手機已更新');
    }

    public function deletePhone(Request $request)
    {
        //直接刪
        ShortMessageService::deleteShortMessageByUserId($request->user_id);
        //        DB::table('short_message')->where('member_id', $request->user_id)->update(['active' =>0, ]);
        UserMeta::where('user_id', $request->user_id)->update(['phone' => '']);
        event(new \App\Events\CheckWarnedOfReport($request->user_id));
        return back()->with('message', '手機已刪除');
    }

    public function deleteBannedLog(Request $request)
    {
        $ban_id = $request->ban_id;
        //直接刪
        DB::table('is_banned_log')->where('id',$ban_id)->delete();
        return back()->with('message', '該筆過往封鎖紀錄已刪除');
    }

    public function deleteWarnedLog(Request $request)
    {
        $warn_id = $request->warn_id;
        //直接刪
        DB::table('is_warned_log')->where('id',$warn_id)->delete();
        return back()->with('message', '該筆過往警示紀錄已刪除');
    }

    public function searchPhone(Request $request)
    {
        $result = DB::table('short_message')->where('mobile', $request->phone)->where('active', 1)->first();
        $data = array();
        $f_userInfo = null;
        if ($result) {
            $userInfo = User::findById($result->member_id);
            if ($userInfo) {
                $data['user_email'] = $userInfo->email;
                $data['user_info_page'] = '/admin/users/advInfo/' . $userInfo->id;
            }
        } else {
            if(ShortMessageService::isForbiddenByPhoneNumber($request->phone)) {
                $f_userInfo = ShortMessageService::getFirstUserByForbiddenPhoneNumber($request->phone);
                if ($f_userInfo) {
                    $data['user_email'] = $f_userInfo->email;
                    $data['user_info_page'] = '/admin/users/advInfo/' . $f_userInfo->id;
                }
            }
        }
        return response()->json(['hasData' => $result && $request->phone ? 1 : 0,'is_forbidden'=>$f_userInfo?1:0, 'data' => $data]);
    }

    public function modifyEmail(Request $request)
    {
        if (User::where('advance_auth_email', $request->email)->first() && $request->email) {
            return back()->with('error', '已存在資料, Email 重複驗證');
        }
        $user = User::where('id', $request->user_id)->first();

        $token = md5(str_random(40));

        if ($request->pass) {
            // 設定通過認證 Email 的時間
            $user->advance_auth_status = 1;
            $user->advance_auth_email_token = $token;
            $user->advance_auth_email_at = Carbon::now();
            $message = '已通過 Email 驗證';
        } else if ($request->email) {
            // 修改 Email
            $user->advance_auth_email = $request->email;
            $user->advance_auth_email_token = $token;
            $user->advance_auth_email_at = Carbon::now();
            $message = 'Email 已更新';
        } else {
            // 刪除 Email
            $user->advance_auth_status = 0;
            $user->advance_auth_email = null;
            $user->advance_auth_email_token = null;
            $user->advance_auth_email_at = null;
            $message = 'Email 已刪除';
        }
        $user->save();
        // event(new \App\Events\CheckWarnedOfReport($request->user_id));

        return back()->with('message', $message);
    }

    public function searchEmail(Request $request)
    {
        $user = User::where('advance_auth_email', $request->email)->first();
        $data = array();
        if ($user) {
            $data['user_email'] = $user->email;
            $data['user_info_page'] = '/admin/users/advInfo/' . $user->id;
        }
        return response()->json(['hasData' => $user && $request->email ? 1 : 0, 'data' => $data]);
    }

    public function multipleLogin(Request $request)
    {
        if ($request->old_version) {
            $original_users = \App\Models\MultipleLogin::with(['original_user', 'original_user.user_meta', 'original_user.banned', 'original_user.implicitlyBanned', 'original_user.aw_relation'])
                ->join('users', 'users.id', '=', 'multiple_logins.original_id')
                ->groupBy('original_id')->orderBy('users.last_login', 'desc');
            $new_users = \App\Models\MultipleLogin::with(['new_user', 'new_user.user_meta', 'new_user.banned', 'new_user.implicitlyBanned', 'new_user.aw_relation'])
                ->leftJoin('users', 'users.id', '=', 'multiple_logins.new_id')
                ->groupBy('new_id')->orderBy('users.last_login', 'desc');
            if ($request->isMethod("POST")) {
                if ($request->date_start) {
                    $original_users = $original_users->where('users.last_login', ">=", $request->date_start . " 00:00:00");
                    $new_users = $new_users->where('users.last_login', ">=", $request->date_start . " 00:00:00");
                }
                if ($request->date_end) {
                    $original_users = $original_users->where('users.last_login', "<=", $request->date_end . " 23:59:59");
                    $new_users = $new_users->where('users.last_login', "<=", $request->date_end . " 23:59:59");
                }
            }
            $original_users = $original_users->get();
            $new_users = $new_users->get();
            $original_new_map = array();
            foreach ($new_users as $new_user) {
                if (!isset($original_new_map[$new_user->original_id])) {
                    $original_new_map[$new_user->original_id] = array();
                }
                array_push($original_new_map[$new_user->original_id], $new_user);
            }

            if ($request->isMethod('POST')) {
                $request->flash();
                return view('admin.users.multipleLoginList', compact('original_users', 'original_new_map', 'new_users'));
            }
            return view('admin.users.multipleLoginList', compact('original_users', 'original_new_map', 'new_users'));
        }
        $original_users = \App\Models\MultipleLogin::with(['original_user', 'original_user.user_meta', 'original_user.banned', 'original_user.implicitlyBanned', 'original_user.aw_relation'])
            ->join('users', 'users.id', '=', 'multiple_logins.original_id')
            ->groupBy('original_id')->orderBy('users.last_login', 'desc');
        $new_users = \App\Models\MultipleLogin::with(['original_user', 'new_user', 'new_user.user_meta', 'new_user.banned', 'new_user.implicitlyBanned', 'new_user.aw_relation'])
            ->leftJoin('users', 'users.id', '=', 'multiple_logins.new_id')
            ->groupBy('new_id')->orderBy('users.last_login', 'desc');
        if ($request->isMethod("POST")) {
            if ($request->date_start) {
                $original_users = $original_users->where('users.last_login', ">=", $request->date_start . " 00:00:00");
                $new_users = $new_users->where('users.last_login', ">=", $request->date_start . " 00:00:00");
            }
            if ($request->date_end) {
                $original_users = $original_users->where('users.last_login', "<=", $request->date_end . " 23:59:59");
                $new_users = $new_users->where('users.last_login', "<=", $request->date_end . " 23:59:59");
            }
        }
        $original_users = $original_users->get();
        $new_users = $new_users->get();
        $original_new_map = array();
        foreach ($new_users as $new_user) {
            if (!isset($original_new_map[$new_user->original_id])) {
                $original_new_map[$new_user->original_id] = array();
            }
            array_push($original_new_map[$new_user->original_id], $new_user);
        }

        /*
         * $user_set: 每個元素的 key 值為原 user_id，首元素為原 user，
         *            users 將新舊會員合併，各會員登入時間獨立記錄，方便排序，
         *            再將排序後的 users 首個登入時間記在每個元素的 date 裡，再次排序
         */
        $user_set = array();
        $total_users = 0;

        foreach ($original_users as $original_user) {
            if (isset($original_new_map[$original_user->id]) && !in_array($original_user->id, $user_set)) {
                $user_set[$original_user->id] = array();
                array_push($user_set[$original_user->id], $original_user);
                $user_set[$original_user->id]['users'] = array();
                foreach ($original_new_map[$original_user->id] as $new_user) {
                    if ($new_user->new_user) {
                        $user_set[$original_user->id]['users'][$new_user->id]['date'] = $new_user->last_login;
                        $user_set[$original_user->id]['users'][$new_user->id]['new'] = 1;
                        array_push($user_set[$original_user->id]['users'][$new_user->id], $new_user);
                        $total_users++;
                    }
                }
                $user_set[$original_user->id]['users'][$original_user->id]['date'] = $original_user->last_login;
                $user_set[$original_user->id]['users'][$original_user->id]['old'] = 1;
                array_push($user_set[$original_user->id]['users'][$original_user->id], $original_user);
                $total_users++;
                usort($user_set[$original_user->id]['users'], array('\App\Http\Controllers\Admin\UserController', 'sortByDate'));
            }
        }
        foreach ($user_set as &$set) {
            $set['date'] = $set['users'][0]['date'];
        }
        usort($user_set, array('\App\Http\Controllers\Admin\UserController', 'sortByDate'));
        if ($request->isMethod('POST')) {
            $request->flash();
            return view('admin.users.multipleLoginList_new', compact('user_set', 'total_users'));
        }
        return view('admin.users.multipleLoginList_new', compact('user_set', 'total_users'));
    }

    function sortByDate($arr1, $arr2)
    {
        $tmp1 = strtotime($arr1['date']);
        $tmp2 = strtotime($arr2['date']);
        return $tmp2 - $tmp1;
    }

    public function postsList(Request $request)
    {
        $postsList = Posts::Join('users', 'users.id', '=', 'posts.user_id')
            ->selectRaw('posts.*, users.email, users.name, users.prohibit_posts, users.access_posts')
            ->orderBy('posts.created_at', 'desc');

        if (!empty($request->get('account'))) {
            $postsList->where('users.email', 'like', '%' . $request->get('account') . '%');
        }
        if (!empty($request->get('date_start'))) {
            $postsList->where('posts.created_at', '>=', $request->get('date_start'));
        }
        if (!empty($request->get('date_end'))) {
            $postsList->where('posts.created_at', '<=', $request->get('date_end') . ' 23:59:59');
        }
        if (!empty($request->get('type'))) {
            $postsList->where('posts.type', $request->get('type'));
        }


        $postsList = $postsList->withTrashed()->get();

        $page = $request->get('page', 1);
        $perPage = 20;
        $postsList = new LengthAwarePaginator($postsList->forPage($page, $perPage), $postsList->count(), $perPage, $page,   ['path' => '/admin/users/posts/']);
        return view('admin.users.postsManage', compact('postsList'));
    }

    public function postsDelete($id)
    {
        $data = Posts::where('id', $id)->get()->first();
        if ($data->delete()) {
            return back()->with('message', '刪除成功');
        } else {
            return back()->withErrors(['發生不明錯誤，刪除失敗！']);
        }
    }

    public function messageBoardList(Request $request)
    {
        $messages = MessageBoard::select('message_board.*', 'users.name', 'users.engroup')
            ->join('users', 'users.id', '=', 'message_board.user_id');

        if($request->reported_message){
            $messages->join('reported_message_board', 'reported_message_board.message_board_id', '=', 'message_board.id')
                ->whereRaw('reported_message_board.id is not null');
        }
        if (isset($request->date_start) || isset($request->date_end) || isset($request->keyword)) {
            $start = isset($request->date_start) ? $request->date_start : '';
            $end = isset($request->date_end) ? $request->date_end : '';
            $messages = $messages->whereDate('message_board.created_at', '>=', $start)
                ->whereDate('message_board.created_at', '<=', $end);
        }
        $messages = $messages->orderBy('created_at', 'desc')->paginate(50);
        return view('admin.users.messageBoardManage')->with('messages', $messages)
            ->with('date_start', $request->date_start)
            ->with('date_end', $request->date_end);
    }

    public function deleteMessageBoard($id)
    {
        $message = MessageBoard::where('id', $id)->first();
        if ($message->delete()) {
            return back()->with('message', '刪除留言成功！');
        } else {
            return back()->withErrors(['發生不明錯誤，刪除留言失敗！']);
        }
    }

    public function hideMessageBoard(Request $request, $id)
    {
        $message = MessageBoard::where('id', $id)->first();
        $message->hide_by_admin = $request->hide_by_admin;
        $message->save();

        return back()->with('message', $request->hide_by_admin == 1 ? '隱藏留言成功！' : '解除隱藏留言成功！');
    }

    public function editMessageBoard(Request $request, $id)
    {
        $contents = $request->contents;

        $message = MessageBoard::where('id', $id)->first();
        $message->contents = $contents;
        $message->contents = str_replace('|$responseTime|', date("Y-m-d H:i:s"), $message->contents);
        $message->contents = str_replace('|$reportTime|', date("Y-m-d H:i:s"), $message->contents);
        $message->contents = str_replace('NOW_TIME', date("Y-m-d H:i:s"), $message->contents);
        $message->contents = str_replace('LINE_ICON', AdminService::$line_icon_html, $message->contents);
        $message->contents = str_replace('|$lineIcon|', AdminService::$line_icon_html, $message->contents);
        $message->save();

        return back()->with('message', '修改留言成功！');
    }

    public function toggleUser_prohibit_posts(Request $request)
    {
        $user = User::findById($request->uid);
        if ($user) {
            if ($request->prohibit) {
                $user->prohibit_posts = 1;
                $user->save();
                return back()->with('message', $user->name . '禁止發言成功');
            } else {
                $user->prohibit_posts = 0;
                $user->save();
                return back()->with('message', $user->name . '解除禁止發言');
            }
        }
    }

    public function toggleUser_access_posts(Request $request)
    {
        $user = User::findById($request->uid);
        if ($user) {
            if ($request->access) {
                $user->access_posts = 1;
                $user->save();
                return back()->with('message', $user->name . '封鎖進入討論區');
            } else {
                $user->access_posts = 0;
                $user->save();
                return back()->with('message', $user->name . '解除封鎖進入討論區');
            }
        }
    }

    public function showEvaluationPic($eid, $uid)
    {
        $user = User::findById($uid);

        $evaluation = Evaluation::where('id', $eid)->first();
        $evaluation_id = $eid;
        $to_user = User::findById($evaluation->to_id);
        $picList = EvaluationPic::where('evaluation_id', $eid)->where('member_id', $uid)->get();
        return view('admin.users.showEvaluationPic', compact('picList', 'user', 'to_user', 'evaluation_id'));
    }

    public function evaluationPicDelete($picID)
    {
        EvaluationPic::where('id', $picID)->delete();
        return back();
    }

    public function evaluationPicAdd(Request $request)
    {
        $evaluation_id = $request->input('evaluation_id');
        $uid = $request->input('uid');
        $nowCount = EvaluationPic::where('evaluation_id', $evaluation_id)->where('member_id', $uid)->count();

        if ($nowCount + count($request->file('images')) > 5) {
            return back()->with('error', '總共不能上傳超過5張照片');
        }

        if ($files = $request->file('images')) {
            foreach ($files as $file) {
                $now = Carbon::now()->format('Ymd');
                $input['imagename'] = $now . rand(100000000, 999999999) . '.' . $file->getClientOriginalExtension();

                $rootPath = public_path('/img/Evaluation');
                $tempPath = $rootPath . '/' . substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/' . substr($input['imagename'], 6, 2) . '/';

                if (!is_dir($tempPath)) {
                    File::makeDirectory($tempPath, 0777, true);
                }

                $destinationPath = '/img/Evaluation/' . substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/' . substr($input['imagename'], 6, 2) . '/' . $input['imagename'];

                $img = Image::make($file->getRealPath());
                $img->resize(400, 600, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($tempPath . $input['imagename']);

                //新增images到db
                $evaluationPic = new EvaluationPic();
                $evaluationPic->evaluation_id = $evaluation_id;
                $evaluationPic->member_id = $uid;
                $evaluationPic->pic = $destinationPath;
                $evaluationPic->save();
            }
        }
        return back();
    }

    public function getIpUsers(Request $request, $ip)
    {
        $is_test = $request->is_test ?? false;
        ini_set("max_execution_time", '0');
        ini_set('memory_limit', '-1');

        $getIpUsersData = LogUserLogin::selectRaw('g.user_id,g.cfp_id,g.created_date,count(*) as groupCount')
            ->from('log_user_login as g')
            ->leftJoin('users as u', 'u.id', 'g.user_id')
            ->whereNotNull('u.id')
            ->orderBy('u.id')
            ->orderBy('u.last_login', 'DESC')
            ->orderBy('g.created_date', 'DESC');

        $user_id = $request->user_id;
        if ($request->type == 'detail' && $user_id && $request->date) {
            $getIpUsersData = $getIpUsersData->where('g.user_id', $user_id)->where('g.cfp_id', $request->cfp_id)->where('g.created_date', $request->date);
        } else {
            if ($user_id) {
                $getIpUsersData = $getIpUsersData->where('g.user_id', $user_id);
            }
            $getIpUsersData = $getIpUsersData->groupBy('g.user_id', 'g.cfp_id', 'g.created_date');
        }

        if ($ip !== '不指定') {
            $getIpUsersData = $getIpUsersData->where('g.ip', $ip);
        }

        $period = $request->period;
        if ($period) {
            switch ($period) {
                case '10days':
                    $date = date("Y-m-d", strtotime("-10 days"));
                    break;
                case '20days':
                    $date = date("Y-m-d", strtotime("-20 days"));
                    break;
                case '30days':
                    $date = date("Y-m-d", strtotime("-30 days"));
                    break;
                default:
                    $date = date("Y-m-d", strtotime("-90 days"));
                    break;
            }
            $getIpUsersData = $getIpUsersData->where('g.created_date', '>=', $date);
        }

        $assign_user_id=$request->assign_user_id;
        if($assign_user_id){
            $getIpUsersData = $getIpUsersData->where('g.user_id', $assign_user_id);
        }
        $yearMonth=$request->yearMonth;
        if($yearMonth){
            $getIpUsersData = $getIpUsersData->where('g.created_date', 'like', '%' . $yearMonth . '%');
        }
        $cfp_id=$request->cfp_id;
        if($cfp_id){
            $getIpUsersData = $getIpUsersData->where('g.cfp_id', $cfp_id);

        }

        $getIpUsersData_origin=$getIpUsersData->get();
        $getIpUsersData = $getIpUsersData->where('g.log_hide', 0);

        $getIpUsersData = $getIpUsersData->paginate(200);

        $isSetAutoBan_cfp_id=null;
        $isSetAutoBan_ip=null;
        if(Request()->get('cfp_id')){
            $isSetAutoBan_cfp_id = \App\Models\SetAutoBan::whereRaw('(content="'. Request()->get('cfp_id').'" AND expiry >="'. now().'")')->orWhereRaw('(content="'. Request()->get('cfp_id').'" AND expiry="0000-00-00 00:00:00")')->get();
        }else{
            $isSetAutoBan_ip = \App\Models\SetAutoBan::whereRaw('(content="'. $ip.'" AND expiry >="'. now().'")')->orWhereRaw('(content="'. $ip.'" AND expiry="0000-00-00 00:00:00")')->get();
        }
        $male_user_list=User::where('engroup', 1)->whereIn('id', array_keys($getIpUsersData_origin->groupBy('user_id')->toArray()))->get()->pluck('id')->toArray();
        return view('admin.users.ipUsersList')
            ->with('ipUsersData', $getIpUsersData)
            ->with('isSetAutoBan_cfp_id', $isSetAutoBan_cfp_id)
            ->with('isSetAutoBan_ip', $isSetAutoBan_ip)
            ->with('male_user_list', $male_user_list)
            ->with('ip', $ip)
            ->with('recordType', $request->type)
            ->with('is_test', $is_test)
            ->with('service',$this->service->riseByUserEntry(auth()->user()));
    }

    public function getUsersLog(Request $request)
    {
        $whereArr = [];
        $user_id = $request->user_id;
        $ip = $request->ip;
        $cfp_id = $request->cfp_id;
        $qstrArr = [];

        $showLogQuery = [];
        $curLogUser = null;

        if ($user_id) {
            $qstrArr['user_id'] = $user_id;
            $whereArr[] = ['user_id', $user_id];
            $curLogUser = User::find($user_id);
        }

        if ($ip) {
            $qstrArr['ip'] = $ip;
            $whereArr[] = ['ip', $ip];
        }

        if ($cfp_id) {
            $qstrArr['cfp_id'] = $cfp_id;
            $whereArr[] = ['cfp_id', $cfp_id];
        }

        if (!$whereArr) $getUsersLogData = null;
        else
            $getUsersLogData = LogUserLogin::with('user')->where($whereArr)
                ->orderBy('created_at', 'DESC')
                ->paginate(50);
        $getUsersLogData->appends($qstrArr);
        return view('admin.users.getUsersLogList')
            ->with('getUsersLogData', $getUsersLogData)
            ->with('curLogUser', $curLogUser);
    }

    public function logUserLoginHide(Request $request){
        $user_id_list=$request->get('user_id_list').',';
        $log_hide=$request->get('log_hide');
        LogUserLogin::whereIn('user_id', explode(',',$user_id_list))->update(['log_hide'=>$log_hide]);

        return back();
    }

    public function accountStatus_admin(Request $request)
    {
        $uid = $request->input('uid');
        $account_status = $request->input('account_status');
        $user = User::findById($uid);
        $user->account_status_admin = $account_status;
        $user->save();

        return back();
    }

    public function accountStatus_user(Request $request)
    {
        $uid = $request->input('uid');
        $account_status = $request->input('account_status');
        $user = User::findById($uid);
        $user->accountStatus = $account_status;
        $user->save();

        return back();
    }

    public function isEverWarnedOrBannedLog($logType, $user_id)
    {
        $user = User::findById($user_id);
        if ($logType == 'Warned') {
            //曾被警示
            $dataLog = DB::table('is_warned_log')->where('user_id', $user_id)->orderBy('created_at', 'desc')->paginate(10);
        } else {
            //曾被封鎖
            $dataLog = DB::table('is_banned_log')->where('user_id', $user_id)->orderBy('created_at', 'desc')->paginate(10);
        }

        return view('admin.users.isEverWarnedOrBannedLog')->with('user', $user)->with('logType', $logType)->with('dataLog', $dataLog);
    }

    public function showFilterByInfoList(Request $request)
    {
        $admin = $this->admin->checkAdmin();

        if ($admin) {
            $error_msg = [];
            $en_group = $request->en_group;

            $newer_manual_gt_num = $request->newer_manual_gt_num;
            $register_gt_num = $request->register_gt_num;
            $msg_gt_visit_7days = $request->msg_gt_visit_7days;
            $msg_gt_visit = $request->msg_gt_visit;

            $reported_gt_num = $request->reported_gt_num;
            $blocked_gt_num = $request->blocked_gt_num;
            $block_other_gt_num = $request->block_other_gt_num;

            $newerManualGtNum = $request->newerManualGtNum;
            $registerGtNum = $request->registerGtNum;
            $reportedGtNum = $request->reportedGtNum;
            $blockedGtNum = $request->blockedGtNum;
            $blockOtherGtNum = $request->blockOtherGtNum;

            if ($error_msg) {
                return view('admin.users.filterByInfo')->withErrors($error_msg);
            } else {

                $whereArr = [];
                $whereRawArr = [];
                $orwhereArr = [];
                $orwhereRawArr = [];
                $qstrArr[] = [];
                if (isset($en_group)) $qstrArr['en_group'] = $en_group;
                $dateEntry = DataForFilterByInfo::select('created_at', 'updated_at')->first();
                if ($dateEntry) {
                    $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $dateEntry->created_at)->subDays(1)->format('Y-m-d H:i:s');
                    $end_date = $dateEntry->created_at;
                }

                $infoSet = DataForFilterByInfo::select('data_for_filter_by_info.*')
                    ->join('users', 'users.id', '=', 'data_for_filter_by_info.user_id')
                    ->leftJoin('data_for_filter_by_info_ignores', 'data_for_filter_by_info_ignores.user_id', '=', 'data_for_filter_by_info.user_id')
                    ->where('users.engroup', ($en_group ?? 2));

                if ($newer_manual_gt_num) {
                    $qstrArr['newer_manual_gt_num'] = $newer_manual_gt_num;
                    $qstrArr['newerManualGtNum'] = $newerManualGtNum;
                    switch ($newer_manual_gt_num) {
                        case 'and':
                            $whereRawArr[] = '(select SUM(newer_manual) from stay_online_record where stay_online_record.user_id=data_for_filter_by_info.user_id) >='.$newerManualGtNum;
                            break;
                        case 'or':
                            //$orwhereRawArr[] = '(select SUM(newer_manual) from stay_online_record where stay_online_record.user_id=data_for_filter_by_info.user_id) >='.$newerManualGtNum;
                            break;
                    }
                }

                if ($register_gt_num) {
                    $qstrArr['register_gt_num'] = $register_gt_num;
                    $qstrArr['registerGtNum'] = $registerGtNum;
                    $date_default= date('Y-m-d H:i:s', strtotime('-'.$registerGtNum.' days')); // ($registerGtNum ?? 0)
                    switch ($register_gt_num) {
                        case 'and':
                            $whereArr[] = ['users.created_at', '<=', $date_default];
                            break;
                        case 'or':
                            $orwhereArr[] = ['users.created_at', '<=', $date_default];
                            break;
                    }
                }
                if ($msg_gt_visit_7days) {
                    $qstrArr['msg_gt_visit_7days'] = $msg_gt_visit_7days;
                    switch ($msg_gt_visit_7days) {
                        case 'and':
                            $whereRawArr[] = 'message_count_7 > visit_other_count_7';
                            break;
                        case 'or':
                            $orwhereRawArr[] = 'message_count_7 > visit_other_count_7';
                            break;
                    }
                }
                if ($msg_gt_visit) {
                    $qstrArr['msg_gt_visit'] = $msg_gt_visit;
                    switch ($msg_gt_visit) {
                        case 'and':
                            $whereRawArr[] = 'message_count > visit_other_count';
                            break;
                        case 'or':
                            $orwhereRawArr[] = 'message_count > visit_other_count';
                            break;
                    }
                }
                if ($blocked_gt_num) {
                    $qstrArr['blocked_gt_num'] = $blocked_gt_num;
                    $qstrArr['blockedGtNum'] = $blockedGtNum;
                    switch ($blocked_gt_num) {
                        case 'and':
                            $whereArr[] = ['be_blocked_other_count', '>', ($blockedGtNum ?? 0)];
                            break;
                        case 'or':
                            $orwhereArr[] = ['be_blocked_other_count', '>', ($blockedGtNum ?? 0)];
                            break;
                    }
                }
                if ($block_other_gt_num) {
                    $qstrArr['block_other_gt_num'] = $block_other_gt_num;
                    $qstrArr['blockOtherGtNum'] = $blockOtherGtNum;
                    switch ($block_other_gt_num) {
                        case 'and':
                            $whereArr[] = ['blocked_other_count', '>', ($blockOtherGtNum ?? 0)];
                            break;
                        case 'or':
                            $orwhereArr[] = ['blocked_other_count', '>', ($blockOtherGtNum ?? 0)];
                            break;
                    }
                }
                if ($reported_gt_num) {
                    $qstrArr['reported_gt_num'] = $reported_gt_num;
                    $qstrArr['reportedGtNum'] = $reportedGtNum;
                    switch ($reported_gt_num) {
                        case 'and':
                            $whereArr[] = ['be_reported_other_count', '>', ($reportedGtNum ?? 0)];
                            break;
                        case 'or':
                            $orwhereArr[] = ['be_reported_other_count', '>', ($reportedGtNum ?? 0)];
                            break;
                    }
                }

                $infoSet->where(function ($query) use ($whereArr, $whereRawArr, $orwhereArr, $orwhereRawArr) {
                    if ($whereRawArr) {
                        foreach ($whereRawArr as $whereRaw)
                            $query->whereRaw($whereRaw);
                    }
                    if ($whereArr) $query->where($whereArr);
                    if ($orwhereArr) {

                        foreach ($orwhereArr as $orArr)
                            $query->orwhere($orArr[0], $orArr[1], $orArr[2]);
                    }

                    if ($orwhereRawArr) {
                        foreach ($orwhereRawArr as $orwhereRaw)
                            $query->orwhereRaw($orwhereRaw);
                    }
                });

                $infoSet
                    ->orderBy('data_for_filter_by_info_ignores.level')
                    ->orderByDesc('last_login');

                $data = $infoSet->paginate(200);
                $data->appends($qstrArr);

                return view('admin.users.filterByInfo')
                    ->with('data', $data ?? null)
                    ->with('start_date', $start_date ?? null)
                    ->with('end_date', $end_date ?? null);
            }
        } else {
            return view('admin.users.filterByInfo')->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function switchFilterByInfoIgnore(Request $request)
    {
        $user_id = $request->user_id;
        $level = $request->level ?? 0;
        if (!$user_id) return redirect()->back();
        $op = $request->op;
        $ignore = DataForFilterByInfoIgnores::select('*');

        switch ($op) {
            case '1':
                $ignore_entry = $ignore->firstOrNew(['user_id' => $user_id]);
                $ignore_entry->level = $level;
                $ignore_entry->user_id = $user_id;
                $ignore_entry->save();
                break;
            case '0':
                $ignore->where('user_id', $user_id)->delete();
                break;
            default:
                $ignore_entry = $ignore->firstOrNew(['user_id' => $user_id]);

                if ($ignore_entry->id) $ignore_entry->delete();
                else {
                    $ignore_entry->user_id = $user_id;
                    $ignore_entry->level = $level;
                    $ignore_entry->save();
                }
                break;
        }

        return redirect()->back();
    }

    public function UserPicturesSimilar(Request $request)
    {

        $users = User::with('suspicious')
            ->with('aw_relation')
            ->with('banned')
            ->with('implicitlyBanned')
            ->with('check_point_user')
            ->with('backend_user_details');

        $users = $users->selectRaw(
            '*,
            @meta_update := (SELECT `updated_at` FROM `user_meta` WHERE `user_meta`.`user_id` = `users`.`id`) AS `meta_update`,
            @pic_update  := (SELECT max(`updated_at`) FROM `member_pic` WHERE `member_pic`.`member_id` = `users`.`id` AND `member_pic`.`deleted_at` IS NULL) AS `pic_update`,
            @last_update := IF(@meta_update > @pic_update, IFNULL(@meta_update, @pic_update), IFNULL(@pic_update, @meta_update)) AS `last_update`'
        );

        $users = $users->whereDoesntHave('suspicious')
            ->whereDoesntHave('banned')
            ->whereDoesntHave('implicitlyBanned')
            ->whereDoesntHave('aw_relation')
            ->whereDoesntHave('user_meta', function ($query) {
                $query->where('isWarned', true);
            })
            ->whereDoesntHave('check_point_name', function ($query) {
                $query->where('name', 'step_2_ischecked');
            })
            ->whereHas('user_meta', function ($query) {
                $query->where('is_active', true)->whereNotNull('smoking')->whereNotNull('drinking')
                    ->whereNotNull('marriage')->whereNotNull('education')->whereNotNull('about')->whereNotNull('style')
                    ->whereNotNull('birthdate')->whereNotNull('area')->whereNotNull('city');
            })
            ->whereDoesntHave('backend_user_details', function ($query) {
                $query->where('is_waiting_for_more_data', 1)->orWhere('remain_login_times_of_wait_for_more_data', '>', 0);
            });


        // 開始日期
        if ($request->date_start) {

            $users = $users->whereRaw(
                "IF(
                    (SELECT `updated_at` FROM `user_meta` WHERE `user_meta`.`user_id` = `users`.`id`) > (SELECT IF(max(`updated_at`)=NULL,  max(`updated_at`), '0000-00-00') FROM `member_pic` WHERE `member_pic`.`member_id` = `users`.`id` AND `member_pic`.`deleted_at` IS NULL),
                    (SELECT `updated_at` FROM `user_meta` WHERE `user_meta`.`user_id` = `users`.`id`),
                    (SELECT max(`updated_at`) FROM `member_pic` WHERE `member_pic`.`member_id` = `users`.`id` AND `member_pic`.`deleted_at` IS NULL)
                ) >= '$request->date_start'"
            );
        }

        // 結束日期
        if ($request->date_end) {

            $users = $users->whereRaw(
                "IF(
                    (SELECT `updated_at` FROM `user_meta` WHERE `user_meta`.`user_id` = `users`.`id`) > (SELECT IF(max(`updated_at`)=NULL,  max(`updated_at`), '0000-00-00') FROM `member_pic` WHERE `member_pic`.`member_id` = `users`.`id` AND `member_pic`.`deleted_at` IS NULL),
                    (SELECT `updated_at` FROM `user_meta` WHERE `user_meta`.`user_id` = `users`.`id`),
                    (SELECT max(`updated_at`) FROM `member_pic` WHERE `member_pic`.`member_id` = `users`.`id` AND `member_pic`.`deleted_at` IS NULL)
                ) <= '$request->date_end 23:59:59'"
            );
        }

        // 性別
        if ($request->en_group) {

            $users = $users->where('engroup', $request->en_group);
        }

        // 縣市
        if ($request->city) {

            $users = $users->whereHas('meta', function ($query) use ($request) {
                $query->where('city', $request->city);
            });
        }

        // 行政區
        if ($request->area) {

            $users = $users->whereHas('meta', function ($query) use ($request) {
                $query->where('area', $request->area);
            });
        }

        // 照片是否隱藏
        if ($request->hidden) {

            $users = $users->whereHas('meta', function ($query) {
                $query->where('isAvatarHidden', 1);
            });
            //$users = $users->whereHas('pic', function ($query) {
            //    $query->where('isHidden', 1);
            //});
            $users = $users->whereRaw('((select count(*) from `member_pic` where `users`.`id` = `member_pic`.`member_id` and `isHidden` = 1 and `member_pic`.`deleted_at` is null)>0 OR (select count(*) from `member_pic` where `users`.`id` = `member_pic`.`member_id`  and `member_pic`.`deleted_at` is null)=0)');
        } else {

            $users = $users->whereHas('meta', function ($query) {
                $query->where('isAvatarHidden', 0);
            });
            //$users = $users->whereHas('pic', function ($query) {
            //    $query->where('isHidden', 0);
            //});
            $users = $users->whereRaw('((select count(*) from `member_pic` where `users`.`id` = `member_pic`.`member_id` and `isHidden` = 0 and `member_pic`.`deleted_at` is null)>0 OR (select count(*) from `member_pic` where `users`.`id` = `member_pic`.`member_id`  and `member_pic`.`deleted_at` is null)=0)');
        }

        if ($request->order_by == 'last_login') {

            $users = $users->orderBy('last_login', 'desc');
        } else {

            $users = $users->orderBy('last_update', 'desc');
        }

        $users = $users->paginate(15);

        $user_id_of_page = array();

        foreach ($users as $user) {
            $user_id_of_page[] = $user->id;
        }

        return view('admin.users.userPicturesSimilar', [
            'users' => $users,
            'user_id_of_page' => $user_id_of_page
        ])->with('last_images_compare_encode', ImagesCompareEncode::orderByDesc('id')->firstOrNew());
    }

    public function UserPicturesSimilarLog(Request $request)
    {

        $AdminPicturesSimilarActionLogs = \App\Models\AdminPicturesSimilarActionLog::selectRaw(
            '*, 
            max(`created_at`) AS `max_created_at`, 
            (SELECT `last_login` FROM `users` WHERE `id` = `admin_pictures_similar_action_logs`.`target_id`) AS `last_login`'
        )
            ->where('operator_role', 3)
            ->when($request, function ($query) use ($request) {

                // 開始日期
                if ($request->date_start) $query->where('created_at', '>=', $request->date_start);

                // 結束日期
                if ($request->date_end) $query->where('created_at', '<=', "$request->date_end 23:59:59");

                // 性別
                if ($request->en_group) $query->whereHas('target_user', function ($query) use ($request) {
                    $query->where('engroup', $request->en_group);
                });

                // 縣市
                if ($request->city) $query->whereHas('target_user.meta', function ($query) use ($request) {
                    $query->where('city', $request->city);
                });

                // 地區
                if ($request->area) $query->whereHas('target_user.meta', function ($query) use ($request) {
                    $query->where('area', $request->area);
                });
            })
            ->when($request->hidden, function ($query) {
                $query->whereHas('target_user.pic', function ($query) {
                    $query->where('isHidden', 1);
                });
                $query->whereHas('target_user.meta', function ($query) {
                    $query->where('isAvatarHidden', 1);
                });
            }, function ($query) {
                $query->whereHas('target_user.pic', function ($query) {
                    $query->where('isHidden', 0);
                });
                $query->whereHas('target_user.meta', function ($query) {
                    $query->where('isAvatarHidden', 0);
                });
            })
            ->groupBy('target_id')
            ->when($request->order_by == 'last_login', function ($query) {
                $query->orderByDesc('last_login');
            }, function ($query) {
                $query->orderByDesc('max_created_at');
            })
            ->paginate(15);

        return view('admin.users.userPicturesSimilarLog', [
            'AdminPicturesSimilarActionLogs' => $AdminPicturesSimilarActionLogs
        ])->with('last_images_compare_encode', ImagesCompareEncode::orderByDesc('id')->firstOrNew());
    }

    public function UserPicturesSimilarJobCreate(Request $request)
    {
        $job_show_name = '以圖找圖';

        if ($request->type == 'date') {
            $validated = $request->validate([
                'date_start' => ['required', 'date'],
                'date_end' => ['required', 'date'],
            ]);

            if ($validated) {
                $images = MemberPic::withTrashed()->whereBetween('created_at', [$request->date_start, $request->date_end . ' 23:59:59'])->get();
                $imgs_count = $images->count();
                if ($imgs_count > 0) {
                    foreach ($images as $img) {
                        \App\Jobs\SimilarImagesSearcher::dispatch($img->pic);
                    }
                }
            }

            return '成功將 ' . $imgs_count . ' 筆資料列入' . $job_show_name . '送檢佇列';
        }

        if ($request->type == 'all') {
            $UserMetaPics      = \App\Models\UserMeta::select('pic')->whereNotNull('pic');
            $AvatarDeletedPics = \App\Models\AvatarDeleted::select('pic');
            $MemberPics        = \App\Models\MemberPic::withTrashed()->select('pic');

            $Imgs = $UserMetaPics->union($AvatarDeletedPics)->union($MemberPics)->get();
            $Imgs_count = $Imgs->count();

            foreach ($Imgs as $img) {
                \App\Jobs\SimilarImagesSearcher::dispatch($img->pic);
            }

            return '成功將 ' . $Imgs_count . ' 筆資料列入' . $job_show_name . '送檢佇列';
        }

        if ($request->type == 'userAll') {

            $user_id = $request->targetUser;
            $checkUser = User::find($user_id);
            if ($checkUser->engroup != 2) return $job_show_name . '送檢失敗！只有女會員的資料才能列入' . $job_show_name . '送檢佇列';
            $UserMetaPics      = \App\Models\UserMeta::select('pic')->whereNotNull('pic')->where('user_id', $user_id);
            $AvatarDeletedPics = \App\Models\AvatarDeleted::select('pic')->where('user_id', $user_id);
            $MemberPics        = \App\Models\MemberPic::withTrashed()->select('pic')->where('member_id', $user_id);

            $Imgs = $UserMetaPics->union($AvatarDeletedPics)->union($MemberPics)->get();
            $Imgs_count = $Imgs->count();

            foreach ($Imgs as $img) {
                \App\Jobs\SimilarImagesSearcher::dispatchSync($img->pic);
            }

            return back()->with('message', '成功將 ' . $Imgs_count . ' 筆資料列入' . $job_show_name . '送檢佇列');
        }

        return $job_show_name . '沒有指定的方法';
    }

    public function UserImagesCompareJobCreate(Request $request)
    {
        $job_show_name = '站內搜圖';

        if ($request->type == 'date') {
            $validated = $request->validate([
                'date_start' => ['required', 'date'],
                'date_end' => ['required', 'date'],
            ]);

            if ($validated) {
                $images = MemberPic::withTrashed()->whereBetween('created_at', [$request->date_start, $request->date_end . ' 23:59:59'])->get();
                $imgs_count = $images->count();
                if ($imgs_count > 0) {
                    foreach ($images as $img) {
                        $img->compareImages('UserController@UserImagesCompareJobCreate');
                    }
                }
            }

            return '成功將 ' . $imgs_count . ' 筆資料列入' . $job_show_name . '送檢佇列';
        }

        if ($request->type == 'all') {
            $UserMetaPics      = \App\Models\UserMeta::select('pic')->whereNotNull('pic');
            $AvatarDeletedPics = \App\Models\AvatarDeleted::select('pic');
            $MemberPics        = \App\Models\MemberPic::withTrashed()->select('pic');

            $Imgs = $UserMetaPics->union($AvatarDeletedPics)->union($MemberPics)->get();
            $Imgs_count = $Imgs->count();

            foreach ($Imgs as $img) {
                $img->compareImages('UserController@UserImagesCompareJobCreate');
            }

            return '成功將 ' . $Imgs_count . ' 筆資料列入' . $job_show_name . '送檢佇列';
        }

        if ($request->type == 'userAll') {

            $user_id = $request->targetUser;
            $checkUser = User::find($user_id);
            if ($checkUser->engroup != 2) return $job_show_name . '送檢失敗！只有女會員的資料才能列入' . $job_show_name . '送檢佇列';
            $UserMetaPics      = \App\Models\UserMeta::select('pic')->whereNotNull('pic')->where('user_id', $user_id);
            $AvatarDeletedPics = \App\Models\AvatarDeleted::select('pic')->where('user_id', $user_id);
            $MemberPics        = \App\Models\MemberPic::withTrashed()->select('pic')->where('member_id', $user_id);

            $Imgs = $UserMetaPics->union($AvatarDeletedPics)->union($MemberPics)->get();
            $Imgs_count = $Imgs->count();

            foreach ($Imgs as $img) {
                $img->compareImages('UserController@UserImagesCompareJobCreate');
            }

            return back()->with('message', '已立即執行 ' . $Imgs_count . ' 筆資料的' . $job_show_name);
        }

        return $job_show_name . '沒有指定的方法';
    }

    public function admin_user_suspicious_toggle(Request $request)
    {

        if ($request->toggle == 1) {

            DB::beginTransaction();

            try {

                SuspiciousUser::insert_data(Auth::user(), $request->uid, $request->reason, $request->ip());

                DB::commit();

                $msg_type    = 'message';
                $msg_content = '已將該用戶加入至可疑名單內';
            } catch (\Throwable $th) {

                //throw $th;
                DB::rollback();

                $msg_type    = 'error';
                $msg_content = '加入可疑名單失敗';
            }

            return back()->with($msg_type, $msg_content);
        }

        if ($request->toggle == 0) {

            DB::beginTransaction();

            try {

                SuspiciousUser::delete_data(Auth::user(), $request->uid, $request->reason, $request->ip());

                DB::commit();

                $msg_type    = 'message';
                $msg_content = '已將該用戶從可疑名單內移除';
            } catch (\Throwable $th) {

                // throw $th;
                DB::rollback();

                $msg_type    = 'error';
                $msg_content = '移除可疑名單失敗';
            }

            return back()->with($msg_type, $msg_content);
        }

        return back()->with('error', 'unknow controller method');
    }

    public function admin_user_block_toggle(Request $request)
    {

        if ($request->toggle == 1) {

            DB::beginTransaction();

            try {

                // toggleUserBlock Method
                $this->toggleUserBlock($request);

                // 操作紀錄
                \App\Models\AdminPicturesSimilarActionLog::insert([
                    'operator_id'   => Auth::user()->id,
                    'operator_role' => Auth::user()->roles->first()->id,
                    'target_id'     => $request->user_id,
                    'act'           => '加入封鎖名單',
                    'reason'        => $request->reason,
                    'days'          => $request->days,
                    'ip'            => $request->ip(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

                DB::commit();

                $msg_type    = 'message';
                $msg_content = '已將該用戶加入至封鎖名單內';
            } catch (\Throwable $th) {

                //throw $th;
                DB::rollback();

                $msg_type    = 'error';
                $msg_content = '新增封鎖名單失敗';
            }

            return back()->with($msg_type, $msg_content);
        }

        if ($request->toggle == 0) {

            DB::beginTransaction();

            try {

                // toggleUserBlock Method
                $this->toggleUserBlock($request);

                \App\Models\AdminPicturesSimilarActionLog::insert([
                    'operator_id'   => Auth::user()->id,
                    'operator_role' => Auth::user()->roles->first()->id,
                    'target_id'     => $request->user_id,
                    'act'           => '刪除封鎖名單',
                    'ip'            => $request->ip(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

                DB::commit();

                $msg_type    = 'message';
                $msg_content = '已將該用戶從封鎖名單內移除';
            } catch (\Throwable $th) {

                //throw $th;
                DB::rollback();

                $msg_type    = 'error';
                $msg_content = '刪除封鎖名單失敗';
            }

            return back()->with($msg_type, $msg_content);
        }

        return back()->with('error', 'unknow controller method');
    }

    /**
     * Toggle a specific member is blocked or not.
     *
     * @return \Illuminate\Http\Response
     */
    public function toggleUserBlock(Request $request)
    {
        $now_time = Carbon::now();
        ini_set('max_execution_time', -1);
        $userBanned = banned_users::where('member_id', $request->user_id)
            ->orderBy('created_at', 'desc')
            ->get()->first();

        //勾選加入常用列表後新增
        if ($request->addreason) {
            if (DB::table('reason_list')->where([['type', 'ban'], ['content', $request->reason]])->first() == null) {
                DB::table('reason_list')->insert(['type' => 'ban', 'content' => $request->reason]);
            }
        }

        //輸入新增自動封鎖關鍵字後新增
        if (!empty($request->addautoban)) {
            foreach ($request->addautoban as $value) {
                if (!empty($value)) {
                    if (SetAutoBan::where([['type', 'allcheck'], ['content', $value], ['set_ban', '1']])->first() == null) {
                        SetAutoBan::insert(['type' => 'allcheck', 'content' => $value, 'set_ban' => '1', 'cuz_user_set' => $request->user_id, 'created_at' => $now_time, 'updated_at' => $now_time]);
                    }
                }
            }
        }

        //輸入新增圖片檔名自動封鎖關鍵字後新增
        if (!empty($request->addpicautoban)) {
            foreach ($request->addpicautoban as $value) {
                if (!empty($value)) {
                    if (SetAutoBan::where([['type', 'picname'], ['content', $value], ['set_ban', '1']])->first() == null) {
                        SetAutoBan::insert(['type' => 'picname', 'content' => $value, 'set_ban' => '1', 'cuz_user_set' => $request->user_id, 'created_at' => $now_time, 'updated_at' => $now_time]);
                    }
                }
            }
        }

        //新增自動封鎖條件
        //cfp_id
        if (!empty($request->cfp_id)) {
            foreach ($request->cfp_id as $value) {
                if (!empty($value)) {
                    if (SetAutoBan::where([['type', 'cfp_id'], ['content', $value], ['set_ban', '1']])->first() == null) {
                        SetAutoBan::insert(['type' => 'cfp_id', 'content' => $value, 'set_ban' => '1', 'cuz_user_set' => $request->user_id, 'created_at' => $now_time, 'updated_at' => $now_time]);
                    }
                }
            }
        }

        //ip
        if (!empty($request->ip)) {
            foreach ($request->ip as $value) {
                if (!empty($value)) {
                    if (SetAutoBan::where([['type', 'ip'], ['content', $value], ['set_ban', '1']])->first() == null) {
                        SetAutoBan::insert(['type' => 'ip', 'content' => $value, 'set_ban' => '1', 'cuz_user_set' => $request->user_id, 'expiry' => $now_time->copy()->addMonths(1)->format('Y-m-d H:i:s'), 'created_at' => $now_time, 'updated_at' => $now_time]);
                    }
                }
            }
        }

        //userAgent
        if (!empty($request->userAgent)) {
            foreach ($request->userAgent as $value) {
                if (!empty($value)) {
                    if (SetAutoBan::where([['type', 'userAgent'], ['content', $value], ['set_ban', '1']])->first() == null) {
                        SetAutoBan::insert(['type' => 'userAgent', 'content' => $value, 'set_ban' => '1', 'cuz_user_set' => $request->user_id, 'created_at' => $now_time, 'updated_at' => $now_time]);
                    }
                }
            }
        }

        //pic
        if (!empty($request->pic)) {
            foreach ($request->pic as $value) {
                if (!empty($value)) {
                    if (SetAutoBan::where([['type', 'pic'], ['content', $value], ['set_ban', '1']])->first() == null) {
                        SetAutoBan::insert(['type' => 'pic', 'content' => $value, 'set_ban' => '1', 'cuz_user_set' => $request->user_id, 'created_at' => $now_time, 'updated_at' => $now_time]);
                    }
                }
            }
        }


        $blocked_user = User::findById($request->user_id);
        $line_notify_user_list = lineNotifyChatSet::select('line_notify_chat_set.user_id')
            ->selectRaw('users.line_notify_token')
            ->leftJoin('line_notify_chat', 'line_notify_chat.id', 'line_notify_chat_set.line_notify_chat_id')
            ->leftJoin('users', 'users.id', 'line_notify_chat_set.user_id')
            ->where('line_notify_chat.active', 1)
            ->where('line_notify_chat_set.line_notify_chat_id', 11) //封鎖會員
            ->where('line_notify_chat_set.deleted_at', null)
            ->whereNotNull('users.line_notify_token')
            ->get();
        foreach ($line_notify_user_list as $notify_user) {
            $has_message = Message::where([['to_id', $blocked_user->id], ['from_id', $notify_user->user_id]])->orWhere([['to_id', $notify_user->user_id], ['from_id', $blocked_user->id]])->get()->count();
            if ($notify_user->line_notify_token != null && $has_message) {
                $url = url('/dashboard/chat2');
                //send notify
                $message = '與您通訊的 ' . $blocked_user->name . ' 已經被站方封鎖。對話記錄將移到封鎖信件夾，請您再去檢查，如果您們已經交換聯絡方式，請多加注意。' . $url;
                User::sendLineNotify($notify_user->line_notify_token, $message);
            }
        }

        if ($userBanned) {
            $checkLog = DB::table('is_banned_log')->where('user_id', $userBanned->member_id)->where('created_at', $userBanned->created_at)->first();
            if (!$checkLog) {
                //寫入log
                DB::table('is_banned_log')->insert(['user_id' => $userBanned->member_id, 'reason' => $userBanned->reason, 'expire_date' => $userBanned->expire_date, 'vip_pass' => $userBanned->vip_pass, 'adv_auth' => $userBanned->adv_auth, 'created_at' => $userBanned->created_at]);
            }
            $userBanned->delete();
            //新增Admin操作log
            $this->insertAdminActionLog($request->user_id, '解除封鎖');
            if (isset($request->page)) {
                switch ($request->page) {
                    case 'advInfo':
                        return redirect('admin/users/advInfo/' . $request->user_id);
                    case 'noRedirect':
                        echo json_encode(array('code' => '200', 'status' => 'success'));
                        break;
                    default:
                        return redirect($request->page);
                        break;
                }
            } else {
                return $this->advSearch($request, 'unban');
            }
        } else {
            $userBanned = new banned_users;
            $userBanned->member_id = $request->user_id;
            $userBanned->vip_pass = $request->vip_pass;
            $userBanned->adv_auth = $request->adv_auth;
            if ($request->days != 'X') {
                $userBanned->expire_date = $now_time->copy()->addDays($request->days);
            }
            if (!empty($request->msg)) {
                $userBanned->reason = $request->msg;
            } else if (!empty($request->reason)) {
                $userBanned->reason = $request->reason;
            }
            $userBanned->save();
            BadUserCommon::addRemindMsgFromBadId($request->user_id);
            //寫入log
            DB::table('is_banned_log')->insert(['user_id' => $request->user_id, 'reason' => $userBanned->reason, 'expire_date' => $userBanned->expire_date, 'vip_pass' => $userBanned->vip_pass, 'adv_auth' => $userBanned->adv_auth, 'created_at' => $now_time]);
            //新增Admin操作log
            $this->insertAdminActionLog($request->user_id, '封鎖會員');

            if (isset($request->page)) {
                switch ($request->page) {
                    case 'advInfo':
                        return redirect('admin/users/advInfo/' . $request->user_id);
                    case 'noRedirect':
                        echo json_encode(array('code' => '200', 'status' => 'success'));
                        break;
                    case 'member_check_step1':
                        return redirect()->back();
                        break;
                    default:
                        return redirect($request->page);
                        break;
                }
            } else {
                return $this->advSearch($request, 'ban');
            }
        }
    }

    public function forum_toggle(Request $request)
    {
        $uid = $request->uid;
        $status = $request->status;
        $checkData = Forum::where('user_id', $uid)->first();
        if ($checkData) {
            Forum::where('user_id', $uid)->update(['status' => $status, 'updated_at' => Carbon::now()]);
        }
        echo json_encode(['ok']);
    }

    //AnonymousChat
    public function showAnonymousChatPage()
    {
        $admin = $this->admin->checkAdmin();
        if ($admin) {
            return view('admin.users.searchAnonymousChat');
        } else {
            return view('admin.users.searchMessage')->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function searchAnonymousChatPage(Request $request)
    {
        if($request->searchAnonymousChatPage) {
            $msg = isset($request->msg) ? $request->msg : '';
            $date_start = $request->date_start ? $request->date_start : '0000-00-00';
            $date_end = $request->date_end ? $request->date_end : date('Y-m-d');
            $results = AnonymousChat::select(
                'anonymous_chat.*',
                'users.name',
                'users.id as userID',
                'users.engroup',
                'banned_users.member_id as banned_userID',
                'warned_users.member_id as warned_userID',
                'anonymous_chat_forbid.user_id as forbid_userID',
                'banned_users.expire_date as banned_userExpireDate',
                'warned_users.expire_date as warned_userExpireDate',
                'anonymous_chat_forbid.expire_date as forbid_userExpireDate'
            )
                ->leftJoin('users', 'users.id', 'anonymous_chat.user_id')
                ->leftJoin('banned_users', 'banned_users.member_id', 'anonymous_chat.user_id')
                ->leftJoin('warned_users', 'warned_users.member_id', 'anonymous_chat.user_id')
                ->leftJoin('anonymous_chat_forbid', 'anonymous_chat_forbid.user_id', 'anonymous_chat.user_id');
            if(isset($request->msg)) {
                $results = $results->where('anonymous_chat.content', 'like', '%' . $msg . '%');
            }
            if(isset($request->date_start) && isset($request->date_end)) {
                $results = $results->whereBetween('anonymous_chat.created_at', array($date_start . ' 00:00', $date_end . ' 23:59'));
            }
            $results = $results->orderBy('anonymous_chat.created_at', 'desc')
                ->orderBy('anonymous_chat.created_at', 'desc')
                ->withTrashed()
                ->paginate(100);
            $anonymousChatBanReason = DB::table('reason_list')->select('content')->where('type', 'anonymous_chat_ban')->get();
            $anonymousChatWarnedReason = DB::table('reason_list')->select('content')->where('type', 'anonymous_chat_warned')->get();
            $anonymousChatForbidReason = DB::table('reason_list')->select('content')->where('type', 'anonymous_chat_forbid')->get();
            return view('admin.users.searchAnonymousChat')
                ->with('anonymousChatBanReason', $anonymousChatBanReason)
                ->with('anonymousChatWarnedReason', $anonymousChatWarnedReason)
                ->with('anonymousChatForbidReason', $anonymousChatForbidReason)
                ->with('results', $results);
        }elseif($request->searchAnonymousChatReport){
            $msg = isset($request->msg) ? $request->msg : '';
            $date_start = $request->date_start ? $request->date_start : '0000-00-00';
            $date_end = $request->date_end ? $request->date_end : date('Y-m-d');
            $resultsReport = AnonymousChatReport::select(
                'anonymous_chat.*',
                'users.name',
                'users.id as userID',
                'users.engroup',
                'anonymous_chat_report.content as report_content',
                'anonymous_chat_report.user_id as report_user',
                'anonymous_chat_report.created_at as report_time',
                'report_user.name as report_name',
                'anonymous_chat_report.deleted_at as report_deleted_at',
                'anonymous_chat_report.id as report_id',
                'report_user.engroup as report_engroup'
            )
                ->leftJoin('anonymous_chat', 'anonymous_chat.id', 'anonymous_chat_report.anonymous_chat_id')
                ->leftJoin('users', 'users.id', 'anonymous_chat_report.reported_user_id')
                ->leftJoin('users as report_user', 'report_user.id', 'anonymous_chat_report.user_id');
            if(isset($request->msg)) {
                $resultsReport = $resultsReport->where('anonymous_chat.content', 'like', '%' . $msg . '%');
            }
            if(isset($request->date_start) && isset($request->date_end)) {
                $resultsReport = $resultsReport->whereBetween('anonymous_chat_report.created_at', array($date_start . ' 00:00', $date_end . ' 23:59'));
            }
            $resultsReport = $resultsReport->orderBy('anonymous_chat_report.created_at', 'desc')
                ->orderBy('anonymous_chat.user_id', 'desc')
                ->withTrashed()->paginate(100);
            return view('admin.users.searchAnonymousChat')->with('resultsReport', $resultsReport);
        }
    }

    public function searchAnonymousChatReport(Request $request)
    {

        $time = Carbon::now()->startOfWeek()->toDateTimeString();
        $resultsReport = AnonymousChatReport::select(
            'anonymous_chat.*',
            'users.name',
            'users.engroup',
            'anonymous_chat_report.content as report_content',
            'anonymous_chat_report.user_id as report_user',
            'anonymous_chat_report.created_at as report_time',
            'report_user.name as report_name',
            'anonymous_chat_report.deleted_at as report_deleted_at',
            'anonymous_chat_report.id as report_id',
            'report_user.engroup as report_engroup'
        )
            ->selectRaw("(select count(DISTINCT aa.user_id) from anonymous_chat_report as aa where (aa.reported_user_id=users.id)and (aa.created_at >= '$time')) as reported_num")
            ->leftJoin('anonymous_chat', 'anonymous_chat.id', 'anonymous_chat_report.anonymous_chat_id')
            ->leftJoin('users', 'users.id', 'anonymous_chat_report.reported_user_id')
            ->leftJoin('users as report_user', 'report_user.id', 'anonymous_chat_report.user_id')
            ->orderBy('anonymous_chat.user_id', 'desc')
            ->orderBy('report_time', 'desc')
            ->withTrashed()->paginate(100);

        return view('admin.users.searchAnonymousChat')->with('resultsReport', $resultsReport);
    }

    public function deleteAnonymousChatRow(Request $request)
    {
        $id = $request->id;
        AnonymousChat::where('id', $id)->delete();
        echo json_encode(['ok']);
    }

    public function deleteAnonymousChatReportRow(Request $request)
    {
        $report_id = $request->report_id;
        AnonymousChatReport::where('id', $report_id)->delete();
        echo json_encode(['ok']);
    }

    public function deleteAnonymousChatReportAll(Request $request)
    {
        $user_id = $request->user_id;
        AnonymousChatReport::where('reported_user_id', $user_id)->delete();
        echo json_encode(['ok']);
    }

    public function userBlock(Request $request)
    {
        ini_set('max_execution_time', -1);

        $user_id = $request->user_id;
        $name = $request->name;
        $block_type = $request->block_type;

        if($block_type=='anonymous_chat_ban'){
            //勾選加入常用列表後新增
            if ($request->addreason) {
                if (DB::table('reason_list')->where([['type', 'anonymous_chat_ban'], ['content', $request->reason]])->first() == null) {
                    DB::table('reason_list')->insert(['type' => 'anonymous_chat_ban', 'content' => $request->reason]);
                }
            }
            $userBanned = User::isBanned_v2($user_id);
            if(!$userBanned){
                $userBanned = new banned_users;
                $userBanned->member_id = $user_id;
                if ($request->days != 'X') {
                    $userBanned->expire_date = Carbon::now()->addDays($request->days);
                }
                if(!empty($request->reason)) {
                    $userBanned->reason = $request->reason;
                }
                $userBanned->save();
                BadUserCommon::addRemindMsgFromBadId($user_id);
                //寫入log
                IsBannedLog::insert(['user_id' => $user_id, 'reason' => $userBanned->reason, 'expire_date' => $userBanned->expire_date, 'created_at' => Carbon::now()]);
                //新增Admin操作log
                $this->insertAdminActionLog($user_id, '封鎖會員');

                return back()->with('message', '已封鎖 '.$name );
            }
        } else if($block_type=='anonymous_chat_warned'){
            //勾選加入常用列表後新增
            if ($request->addreason) {
                if (DB::table('reason_list')->where([['type', 'anonymous_chat_warned'], ['content', $request->reason]])->first() == null) {
                    DB::table('reason_list')->insert(['type' => 'anonymous_chat_warned', 'content' => $request->reason]);
                }
            }
            $userWarned = User::isWarned($user_id);
            if(!$userWarned) {
                $userWarned = new warned_users;
                $userWarned->member_id = $user_id;
                if ($request->days != 'X') {
                    $userWarned->expire_date = Carbon::now()->addDays($request->days);
                }
                $userWarned->reason = $request->reason;

                if (!empty($request->reason)) {
                    $userWarned->reason = $request->reason;
                }
                $userWarned->save();
                BadUserCommon::addRemindMsgFromBadId($user_id);
                //寫入log
                IsWarnedLog::insert(['user_id' => $user_id, 'reason' => $request->reason, 'created_at' => Carbon::now()]);
                //新增Admin操作log
                $this->insertAdminActionLog($user_id, '站方警示');
                return back()->with('message', '已警示 '.$name );
            }

        } else if($block_type=='anonymous_chat_forbid'){
            //勾選加入常用列表後新增
            if ($request->addreason) {
                if (DB::table('reason_list')->where([['type', 'anonymous_chat_forbid'], ['content', $request->reason]])->first() == null) {
                    DB::table('reason_list')->insert(['type' => 'anonymous_chat_forbid', 'content' => $request->reason]);
                }
            }
            $userForbid = User::isAnonymousChatForbid($user_id);
            if(!$userForbid){
                $userForbid = new AnonymousChatForbid;
                $userForbid->user_id = $user_id;
                $userForbid->anonymous = $request->anonymous;
                if ($request->days != 'X') {
                    $userForbid->expire_date = Carbon::now()->addDays($request->days);
                }
                if (!empty($request->reason)) {
                    $userForbid->reason = $request->reason;
                }
                $userForbid->save();
                //寫入log
                IsWarnedLog::insert(['user_id' => $user_id, 'reason' => $request->reason, 'created_at' => Carbon::now()]);
                //新增Admin操作log
                $this->insertAdminActionLog($user_id, '禁止進入匿名聊天室');
                return back()->with('message', '已禁止 '.$name.' 進入匿名聊天室' );
            }

        }

        return false;
    }

    public function userBlockRemove(Request $request)
    {
        $data = $request->post('data');
        $msg = '';

        if($data['block_type']=='anonymous_chat_forbid') {
            $forbid = AnonymousChatForbid::where('user_id', $data['id'])->get();
            if ($forbid->count() > 0) {
                $delete = AnonymousChatForbid::where('user_id', $data['id'])->delete();
                if($delete) {
                    $msg = '解除禁止進入匿名聊天室';
                }
            }
        }

        if($msg != '') {
            $this->messageService->setMessageHandlingBySenderId($data['id'], 0);
            //新增Admin操作log
            $this->insertAdminActionLog($data['id'], $msg);
            $data = array(
                'code' => '200',
                'status' => 'success'
            );
            echo json_encode($data);
        }else{
            $data = array(
                'code' => '204',
                'status' => 'no content'
            );
            echo json_encode($data);
        }

    }

    //AnonymousChat END

    public function member_profile_check_over(Request $request)
    {
        $users_id = json_decode($request->users_id);
        foreach ($users_id as $user_id) {
            $check_point_user = new CheckPointUser;
            $check_point_user->user_id = $user_id;
            $check_point_user->check_point_id = $request->check_point_id;
            $check_point_user->save();

            $this->insertAdminActionLog($user_id, '會員檢查 Step2 通過', 23);
        }
        return redirect()->back();
    }

    public function ban_information(Request $request)
    {
        $uid = $request->uid;
        $banReason = DB::table('reason_list')->select('content')->where('type', 'ban')->get();
        $cfp_id = LogUserLogin::select('cfp_id')->selectRaw('MAX(created_at) AS last_tiime')->orderByDesc('last_tiime')->where('user_id', $uid)->groupBy('cfp_id')->get();
        $userLogin_log = LogUserLogin::selectRaw('LEFT(created_at,7) as loginMonth, DATE(created_at) as loginDate, user_id as userID, ip, count(*) as dataCount')
            ->where('user_id', $uid)
            ->groupBy(DB::raw("LEFT(created_at,7)"))
            ->orderBy('created_at', 'DESC')->get();
        foreach ($userLogin_log as $key => $value) {
            $dataLog = LogUserLogin::where('user_id', $uid)->where('created_at', 'like', '%' . $value->loginMonth . '%')->orderBy('created_at', 'DESC');
            $userLogin_log[$key]['items'] = $dataLog->get();

            //ip
            $Ip_group = LogUserLogin::where('user_id', $uid)->where('created_at', 'like', '%' . $value->loginMonth . '%')
                ->from('log_user_login as log')
                ->selectRaw('ip, count(*) as dataCount, (select created_at from log_user_login as s where s.user_id=log.user_id and s.ip=log.ip and s.created_at like "%' . $value->loginMonth . '%" order by created_at desc LIMIT 1 ) as loginTime')
                ->groupBy(DB::raw("ip"))->orderBy('loginTime', 'desc')->get();
            $Ip = array();
            foreach ($Ip_group as $Ip_key => $group) {
                $Ip['Ip_group'][$Ip_key] = $group;
                $Ip['Ip_group_items'][$Ip_key] = LogUserLogin::where('user_id', $uid)->where('created_at', 'like', '%' . $value->loginMonth . '%')->where('ip', $group->ip)->orderBy('created_at', 'DESC')->get();
            }
            $userLogin_log[$key]['Ip'] = $Ip;
        }

        $meta = UserMeta::where('user_id', $uid)->first();
        $member_pic = MemberPic::where('member_id', $uid)->get();

        return response()->json([
            'message' => 'success ...',
            'banReason' => $banReason,
            'cfp_id' => $cfp_id,
            'userLogin_log' => $userLogin_log,
            'meta' => $meta,
            'member_pic' => $member_pic
        ]);
    }

    public function little_update_profile(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $user->title = $request->title;
        $user->save();

        $user_meta = UserMeta::where('user_id', $request->user_id)->first();
        $user_meta->about = $request->about;
        $user_meta->style = $request->style;
        $user_meta->save();

        return redirect()->back();
    }

    public function informationStatistics(Request $request)
    {
        //選項
        $form_condition['days'] = $request->days ?? 30;
        $form_condition['percentage'] = $request->percentage ?? 10;
        $form_condition['sex'] = $request->sex ?? 0;
        $form_condition['include_banned_user'] = $request->include_banned_user ?? 0;
        $form_condition['include_closed_user'] = $request->include_closed_user ?? 0;


        //最後登入時間
        $login_date = Carbon::now()->subDays($form_condition['days']);
        //上線總人數
        $statistics_data['login_member_count'] = User::where('last_login', '>', $login_date)->count();
        //付費VIP總人數
        $statistics_data['all_pay_vip_count'] = Order::leftJoin('users', 'user_id', '=', 'users.id')->where('users.last_login', '>', $login_date)->where('service_name', 'VIP')->groupby('users.id')->get()->count();
        //被封鎖總人數
        $statistics_data['all_be_blocked_count'] = Blocked::leftJoin('users', 'blocked_id', '=', 'users.id')->where('users.last_login', '>', $login_date)->groupby('users.id')->get()->count();
        //封鎖他人總人數
        $statistics_data['all_block_other_count'] = Blocked::leftJoin('users', 'member_id', '=', 'users.id')->where('users.last_login', '>', $login_date)->groupby('users.id')->get()->count();
        //付出車馬費總人數
        $statistics_data['all_pay_tip_count'] = Tip::leftJoin('users', 'member_id', '=', 'users.id')->where('users.last_login', '>', $login_date)->groupby('users.id')->get()->count();
        //接收車馬費總人數
        $statistics_data['all_receive_tip_count'] = Tip::leftJoin('users', 'to_id', '=', 'users.id')->where('users.last_login', '>', $login_date)->groupby('users.id')->get()->count();


        //付費VIP統計
        $statistics_data['pay_vip_count'] = Order::leftJoin('users', 'user_id', '=', 'users.id')->where('last_login', '>', $login_date)->where('service_name', 'VIP');
        //被其他使用者封鎖統計
        $statistics_data['be_blocked_count'] = Blocked::leftJoin('users', 'blocked.blocked_id', '=', 'users.id')->where('users.last_login', '>', $login_date);
        //封鎖其他使用者統計
        $statistics_data['block_other_count'] = Blocked::leftJoin('users', 'blocked.member_id', '=', 'users.id')->where('users.last_login', '>', $login_date);
        //付出車馬費統計
        $statistics_data['pay_tip_count'] = Tip::leftJoin('users', 'member_id', '=', 'users.id')->where('users.last_login', '>', $login_date);
        //接收車馬費統計
        $statistics_data['receive_tip_count'] = Tip::leftJoin('users', 'to_id', '=', 'users.id')->where('users.last_login', '>', $login_date);

        //性別
        if ($form_condition['sex'] == 1 || $form_condition['sex'] == 2) {
            $statistics_data['pay_vip_count'] = $statistics_data['pay_vip_count']->where('users.engroup', $form_condition['sex']);
            $statistics_data['be_blocked_count'] = $statistics_data['be_blocked_count']->where('users.engroup', $form_condition['sex']);
            $statistics_data['block_other_count'] = $statistics_data['block_other_count']->where('users.engroup', $form_condition['sex']);
            $statistics_data['pay_tip_count'] = $statistics_data['pay_tip_count']->where('users.engroup', $form_condition['sex']);
            $statistics_data['receive_tip_count'] = $statistics_data['receive_tip_count']->where('users.engroup', $form_condition['sex']);
        }

        //是否包含封鎖帳戶使用者
        if (!($form_condition['include_banned_user'] ?? false)) {
            $statistics_data['pay_vip_count'] = $statistics_data['pay_vip_count']->leftJoin('banned_users', 'users.id', '=', 'banned_users.member_id')
                ->leftJoin('banned_users_implicitly', 'users.id', '=', 'banned_users_implicitly.target')
                ->whereNull('banned_users.id')
                ->whereNull('banned_users_implicitly.id');
            $statistics_data['be_blocked_count'] = $statistics_data['be_blocked_count']->leftJoin('banned_users', 'users.id', '=', 'banned_users.member_id')
                ->leftJoin('banned_users_implicitly', 'users.id', '=', 'banned_users_implicitly.target')
                ->whereNull('banned_users.id')
                ->whereNull('banned_users_implicitly.id');
            $statistics_data['block_other_count'] = $statistics_data['block_other_count']->leftJoin('banned_users', 'users.id', '=', 'banned_users.member_id')
                ->leftJoin('banned_users_implicitly', 'users.id', '=', 'banned_users_implicitly.target')
                ->whereNull('banned_users.id')
                ->whereNull('banned_users_implicitly.id');
            $statistics_data['pay_tip_count'] = $statistics_data['pay_tip_count']->leftJoin('banned_users', 'users.id', '=', 'banned_users.member_id')
                ->leftJoin('banned_users_implicitly', 'users.id', '=', 'banned_users_implicitly.target')
                ->whereNull('banned_users.id')
                ->whereNull('banned_users_implicitly.id');
            $statistics_data['receive_tip_count'] = $statistics_data['receive_tip_count']->leftJoin('banned_users', 'users.id', '=', 'banned_users.member_id')
                ->leftJoin('banned_users_implicitly', 'users.id', '=', 'banned_users_implicitly.target')
                ->whereNull('banned_users.id')
                ->whereNull('banned_users_implicitly.id');
        }

        //是否包含關閉帳戶使用者
        if (!($form_condition['include_closed_user'] ?? false)) {
            $statistics_data['pay_vip_count'] = $statistics_data['pay_vip_count']->where('users.accountStatus', 1)->where('account_status_admin', 1);
            $statistics_data['be_blocked_count'] = $statistics_data['be_blocked_count']->where('users.accountStatus', 1)->where('account_status_admin', 1);
            $statistics_data['block_other_count'] = $statistics_data['block_other_count']->where('users.accountStatus', 1)->where('account_status_admin', 1);
            $statistics_data['pay_tip_count'] = $statistics_data['pay_tip_count']->where('users.accountStatus', 1)->where('account_status_admin', 1);
            $statistics_data['receive_tip_count'] = $statistics_data['receive_tip_count']->where('users.accountStatus', 1)->where('account_status_admin', 1);
        }


        //其他結果
        //最高VIP月份數
        $temp_id = 0;
        $temp_month = 0;
        $statistics_data['max_pay_vip_month'] = 0;
        $count = 0;
        foreach ($statistics_data['pay_vip_count']->clone()->select('users.id', 'order.payment', 'pay_date')->orderby('users.id')->get() as $pay_vip) {
            if ($pay_vip->id != $temp_id) {
                $temp_id = $pay_vip->id;
                $temp_month = 0;
                $count = $count + 1;
            }
            switch ($pay_vip->payment) {
                case 'one_month_payment':
                    $temp_month = $temp_month + 1;
                    break;
                case 'one_quarter_payment':
                    $temp_month = $temp_month + 3;
                    break;
                case 'cc_monthly_payment':
                    $temp_month = $temp_month + (count(json_decode($pay_vip->pay_date)) * 1);
                    break;
                case 'cc_quarterly_payment':
                    $temp_month = $temp_month + (count(json_decode($pay_vip->pay_date)) * 3);
                    break;
            }

            if ($temp_month > $statistics_data['max_pay_vip_month']) {
                $statistics_data['max_pay_vip_month'] = $temp_month;
            }
            $statistics_data['pay_vip_count_list'][$count - 1] = $temp_month;
        }
        rsort($statistics_data['pay_vip_count_list']);


        //被封鎖次數列表
        $statistics_data['be_blocked_count_list'] = $statistics_data['be_blocked_count']->clone()->selectRaw('users.id, count(*) as total')->groupBy('users.id')->orderby('total', 'desc');
        //最高被封鎖次數
        $statistics_data['max_be_blocked_count'] = $statistics_data['be_blocked_count_list']->clone()->first()->total ?? 0;
        $statistics_data['be_blocked_count_list'] = $statistics_data['be_blocked_count_list']->get()->toArray();
        //封鎖次數列表
        $statistics_data['block_other_count_list'] = $statistics_data['block_other_count']->clone()->selectRaw('users.id, count(*) as total')->groupBy('users.id')->orderby('total', 'desc');
        //最高封鎖次數
        $statistics_data['max_block_other_count'] = $statistics_data['block_other_count_list']->clone()->first()->total ?? 0;
        $statistics_data['block_other_count_list'] = $statistics_data['block_other_count_list']->get()->toArray();
        //付出車馬費次數列表
        $statistics_data['pay_tip_count_list'] = $statistics_data['pay_tip_count']->clone()->selectRaw('users.id, count(*) as total')->groupBy('users.id')->orderby('total', 'desc');
        //最高付出車馬費次數
        $statistics_data['max_pay_tip_count'] = $statistics_data['pay_tip_count_list']->clone()->first()->total ?? 0;
        $statistics_data['pay_tip_count_list'] = $statistics_data['pay_tip_count_list']->get()->toArray();
        //接收車馬費次數列表
        $statistics_data['receive_tip_count_list'] = $statistics_data['receive_tip_count']->clone()->selectRaw('users.id, count(*) as total')->groupBy('users.id')->orderby('total', 'desc');
        //最高接收車馬費次數
        $statistics_data['max_receive_tip_count'] = $statistics_data['receive_tip_count_list']->clone()->first()->total ?? 0;
        $statistics_data['receive_tip_count_list'] = $statistics_data['receive_tip_count_list']->get()->toArray();


        //符合人數
        $statistics_data['pay_vip_count'] = round($statistics_data['pay_vip_count']->groupby('users.id')->get()->count() * $form_condition['percentage'] / 100);
        $statistics_data['be_blocked_count'] = round($statistics_data['be_blocked_count']->groupby('users.id')->get()->count() * $form_condition['percentage'] / 100);
        $statistics_data['block_other_count'] = round($statistics_data['block_other_count']->groupby('users.id')->get()->count() * $form_condition['percentage'] / 100);
        $statistics_data['pay_tip_count'] = round($statistics_data['pay_tip_count']->groupby('users.id')->get()->count() * $form_condition['percentage'] / 100);
        $statistics_data['receive_tip_count'] = round($statistics_data['receive_tip_count']->groupby('users.id')->get()->count() * $form_condition['percentage'] / 100);
        //佔總人數比例
        $statistics_data['pay_vip_percentage'] = round($statistics_data['pay_vip_count'] / $statistics_data['login_member_count'] * 100, 2);
        $statistics_data['be_blocked_percentage'] = round($statistics_data['be_blocked_count'] / $statistics_data['login_member_count'] * 100, 2);
        $statistics_data['block_other_percentage'] = round($statistics_data['block_other_count'] / $statistics_data['login_member_count'] * 100, 2);
        $statistics_data['pay_tip_percentage'] = round($statistics_data['pay_tip_count'] / $statistics_data['login_member_count'] * 100, 2);
        $statistics_data['receive_tip_percentage'] = round($statistics_data['receive_tip_count'] / $statistics_data['login_member_count'] * 100, 2);


        //百分比線結果
        $statistics_data['pay_vip_count_result'] = 0;
        $num = 0;
        foreach ($statistics_data['pay_vip_count_list'] as $data) {
            $num = $num + 1;
            if ($num >= $statistics_data['pay_vip_count']) {
                $statistics_data['pay_vip_count_result'] = $data;
                break;
            }
        }
        $statistics_data['be_blocked_count_result'] = $statistics_data['be_blocked_count_list'][$statistics_data['be_blocked_count'] - 1]['total'] ?? 0;
        $statistics_data['block_other_count_result'] = $statistics_data['block_other_count_list'][$statistics_data['block_other_count'] - 1]['total'] ?? 0;
        $statistics_data['pay_tip_count_result'] = $statistics_data['pay_tip_count_list'][$statistics_data['pay_tip_count'] - 1]['total'] ?? 0;
        $statistics_data['receive_tip_count_result'] = $statistics_data['receive_tip_count_list'][$statistics_data['receive_tip_count'] - 1]['total'] ?? 0;


        return view('admin.users.informationStatistics')
            ->with('form_condition', $form_condition)
            ->with('statistics_data', $statistics_data);
    }

    public function advertiseStatistics(Request $request)
    {
        $login_count = ComeFromAdvertise::where('action', 'login')->get()->count();
        $explore_count = ComeFromAdvertise::where('action', 'explore')->get()->count();
        $regist_count = ComeFromAdvertise::where('action', 'regist')->get()->count();
        $complete_regist_count = ComeFromAdvertise::where('action', 'regist')->whereNotNull('user_id')->get()->count();

        return view('admin.users.advertiseStatistics')
            ->with('login_count', $login_count)
            ->with('explore_count', $explore_count)
            ->with('regist_count', $regist_count)
            ->with('complete_regist_count', $complete_regist_count);
    }

    public function user_record_view(Request $request)
    {

        return view('admin.users.user_record_view');
    }

    public function user_regist_time_view(Request $request)
    {
        $user_record = UserRecord::leftJoin('users', 'users.id', '=', 'user_record.user_id')->whereNotNull('user_record.cost_time_of_first_dataprofile')->orderBy('user_record.updated_at', 'desc')->paginate(200);
        return view('admin.users.user_regist_time_view')
            ->with('user_record', $user_record);
    }

    public function user_visited_time_view(Request $request)
    {
        $user_visited_record = Visited::whereNotNull('visited_time')->orderBy('id', 'desc')->paginate(200);
        return view('admin.users.user_visited_time_view')
            ->with('user_visited_record', $user_visited_record);
    }

    public function passRealAuth(Request $request)
    {
        $data = $request->data;
        $user_id = $data['user_id'];
        $auth_type_id = $data['auth_type_id'];
        $raa_service = $this->raa_service->riseByUserId($user_id);

        $latest_modify_id = $data['latest_modify_id']??null;

        if($latest_modify_id && $latest_modify_id< $raa_service->getLatestUncheckedModifyIdByAuthTypeId($auth_type_id)) {
            return 2;
        }

        //通過認證後, 發送站長訊息通知
        if($raa_service->passApplyByAuthTypeId($auth_type_id)){
            $user=User::findById($user_id);

            $auth_tag_success=[];
            if($user && $user->engroup==2){
                if($user->self_auth_status)
                    $auth_tag_success[]='本人認證';
                if ($user->beauty_auth_status)
                    $auth_tag_success[]='美顏推薦';
                if ($user->famous_auth_status)
                    $auth_tag_success[]='名人認證';
            }
            if(count($auth_tag_success)>0){
                $auth_string=count($auth_tag_success) >0 ? implode('&', $auth_tag_success) :'';
                $message= $user->name .'您好，您已通過本站的 '.$auth_string.'，此驗證 tag以及您的照片站方僅預設開放給 vvip，以及 pr 值超過80的vip daddy，如果您想要調整，<a href="/dashboard/tag_display_settings" style="color: red;">請點此自行調整。</a>';
                $admin_id = AdminService::checkAdmin()->id;
                Message::post($admin_id, $user_id, $message);
            }
        }

        return $raa_service->passApplyByAuthTypeId($auth_type_id)?'1':'0';

    }

    public function passRealAuthModify(Request $request)
    {
        $data = $request->data;
        $user_id = $data['user_id'];
        $latest_modify_id = $data['latest_modify_id'];
        $raa_service = $this->raa_service->riseByUserId($user_id);
        return $raa_service->passModifyBeforeModifyId($latest_modify_id)?'1':'0';

    }

    public function cancelPassRealAuth(Request $request)
    {
        $data = $request->data;
        $user_id = $data['user_id'];
        $auth_type_id = $data['auth_type_id'];
        $raa_service = $this->raa_service->riseByUserId($user_id);;
        return $raa_service->cancelPassByAuthTypeId($auth_type_id)?'1':'0';

    }

    public function user_online_time_view(Request $request)
    {
        $user_online_record = StayOnlineRecord::with('user')->selectRaw('user_id,client_storage_record_id,SUM(stay_online_time) AS stay_online_time')->whereNotNull('stay_online_time')->groupBy('user_id','stay_online_record.client_storage_record_id')->orderByDesc('stay_online_record.client_storage_record_id')->paginate(200);
        return view('admin.users.user_online_time_view')
            ->with('user_online_record', $user_online_record);
    }

    public function user_page_online_time_view(Request $request)
    {
        if(!count($request->query())) {
            $user_online_record = null;
        } else {
            $user_online_record = User::selectRaw('*,id as user_id')->orderByDesc('last_login');

            $users = $user_online_record;

            $name = $request->name ? $request->name : "";
            $email = $request->email ? $request->email : "";
            $keyword = $request->keyword ? $request->keyword : "";
            $phone = $request->phone ? $request->phone : "";
            $title = $request->title ? $request->title : "";
            $order_no = $request->order_no ? $request->order_no : "";

            if($email) {
                $users->where('email', 'like', '%' . $email . '%');
            }

            if($name) {
                $users->where('name', 'like', '%' . $name . '%');
            }

            if($keyword){
                $users->whereHas('meta',function($mq) use ($keyword) {
                    $mq->where('about', 'like', '%'.$keyword.'%')
                        ->orWhere('style', 'like', '%'.$keyword.'%');
                });
            }

            if($phone){
                $users = $users->whereHas('short_message',function($smsq) use ($phone) {$smsq->where('mobile', 'like', '%' . $phone . '%')->where('short_message.active',1);});
            }

            if($title){
                $users = $users->where('title', 'like', '%' . $title . '%');
            }

            if($order_no){
                $users = $users->whereHas('order',function($odq) use ($order_no){
                    $odq->where('order_id', 'like', '%' . $order_no . '%');
                });
            }
        }


        if($user_online_record) $user_online_record = $user_online_record->paginate(20,['user_id']);

        return view('admin.users.user_page_online_time_view')
            ->with('user_online_record', $user_online_record??null);
    }

    public function user_page_online_time_view_user_paginate(Request $request)
    {
        $user = null;
        foreach($request->query() as $k=> $v) {
            if(strpos($k,'pageU')!==0) continue;
            else {
                $user_id = str_replace('pageU','',$k);
                if(!$user_id) return;
                $user = User::find($user_id);
                break;
            }
        }

        if($user) {
            return view('admin.users.user_page_online_time_view_user_paginate')
                ->with('uRecord', $user)
                ->with('with_user_page_link_binding_script',1);

        }

    }

    public function stay_online_record_page_name_view()
    {
        $partial_name_list = StayOnlineRecordPageName::where('is_partial',1)->get();

        $record_query = StayOnlineRecord::doesntHave('page_name')->selectRaw("'',url,'',0")->whereNotNull('stay_online_time')->whereNotNull('url')->orderByDesc('id')->distinct('url')
            ->where(function($q){
                $q->whereNull('title')->orWhere('title','');
            });

        foreach($partial_name_list as $k=> $v) {
            $record_query->where('url', 'not like', '%'.$v->url.'%');
        }

        $page_name_list = StayOnlineRecordPageName::select('id','url','name','is_partial')->whereNotNull('url')->where('url','!=','')
            ->union($record_query)
            ->orderByDesc('is_partial')
            ->orderByDesc('id')
            ->orderByRaw('LENGTH(url)')
            ->orderBy('url')
            ->get();
        return view('admin.users.stay_online_record_page_name_view')
            ->with('row_list', $page_name_list);
    }

    public function stay_online_record_page_name_delete($id)
    {
        StayOnlineRecordPageName::where('id',$id)->delete();
        return back()->with('message','頁面名稱刪除成功');
    }

    public function stay_online_record_page_name_form(Request $request)
    {
        $entry = StayOnlineRecordPageName::where('id',$request->id)->firstOrNew();
        return view('admin.users.stay_online_record_page_name_form')
            ->with('entry', $entry);
    }

    public function stay_online_record_page_name_switch(Request $request)
    {
        if(!$request->url)  return back();
        $entry = StayOnlineRecordPageName::where('url',$request->url)->firstOrNew();

        if(!$entry->id) {
            $entry->url = $request->url;
            $entry->save();
        }
        $arr = ['id'=>$entry->id];
        if($request->rtn) $arr['rtn'] = $request->rtn;
        return redirect()->route('admin/stay_online_record_page_name_form',$arr);
    }

    public function stay_online_record_page_name_save(Request $request)
    {
        $entry = StayOnlineRecordPageName::where('id',$request->id)->firstOrNew();
        $entry->url = $request->url;
        $entry->name = $request->name;
        $entry->is_partial = $request->is_partial?1:0;
        $entry->save();

        if($request->id) {
            $route_name = 'admin/stay_online_record_page_name_view';

            if($request->rtn=='record') {
                $route_name = 'admin/user_page_online_time_view';
            }

            return back()->with('message','修改成功');
        } else
            return back()->with('message','新增成功');
    }

    public function messageCheck(IndexRequest $request)
    {
        if (!$this->admin->checkAdmin()) {
            return redirect()->back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }

        if (empty($request->all())) {
            $data = collect();

            return view('admin.users.userMessageCheck', compact('data'));
        }

        // 登入區間
        $startDate = $request->date_start ?? "2022-06-25 14:00:00";
        $endDate = $request->date_end ?? "2022-06-30 15:00:00";

        // 訊息區間
        $messageStartDate = $request->message_date_start ?? "2022-06-01 15:00:00";
        $messageEndDate = $request->message_date_end ?? "2022-06-30 15:00:00";

        $total = $request->total ?? 1; // 發送人數
        $gender = $request->en_group ?? 1; // 性別

        $currentPage = $request->page ?? 1;

        $messageCountByTotal = $request->message_count_by_total ?? 1;

        $data = Message::with(['toUser', 'fromUser'])
            ->whereHas('toUser', function ($query) use ($gender) {
                $query->where('engroup', $gender == 1 ? 2 : 1); // 傳給那些異性
            })
            ->whereHas('fromUser', function ($query) use ($gender, $startDate, $endDate) {
                $query->where('engroup', $gender) // 發送訊息者性別
                ->whereBetween('last_login', [$startDate, $endDate]); // 登入區間
            })
            ->whereBetween('created_at', [$messageStartDate, $messageEndDate]) // 訊息區間
            ->get()
            ->groupBy('from_id')
            ->sortDesc()
            ->filter(function ($item) use ($total) {
                // 清除不滿發送人數
                if ($item->countBy('to_id')->count() >= $total) {
                    return $item;
                }
            })->transform(function ($items, $key) use ($messageCountByTotal) {
                // 取得發送訊息者
                $fromUser = User::find($key);

                $fromUser->toUser = $items->groupBy('to_id')->sortDesc()->transform(function ($items, $key) { // 置入接收者
                    // 取得接收訊息者
                    $toUser = User::find($key);
                    // 置入幾封訊息
                    $toUser->count = $items->count();
                    // 接收者進階驗證
                    $toUser->isAdvAuthUsable = $toUser->isAdvAuthUsable ?: UserService::isAdvAuthUsableByUser($toUser);

                    return $toUser;
                });
                // 發訊息者進階驗證
                $fromUser->isAdvAuthUsable = $fromUser->isAdvAuthUsable ?: UserService::isAdvAuthUsableByUser($fromUser);

                return $fromUser;
            })->filter(function ($model) use($messageCountByTotal) {
                if ($model->toUser->isNotEmpty()) {
                    // 總訊息數
                    $model->messageCount = $model->toUser->sum('count');

                    $model->messageCountByTotal = 0;
                    $model->messageCountByTotalPeople = 0;
                    foreach($model->toUser as $toUser){
                        if($toUser->count >= $messageCountByTotal) $model->messageCountByTotal++;
                        $model->messageCountByTotalPeople++;
                    }

                    return $model;
                }
            });

        $data = forPaginate($data, session('per_page') ?: 15, $currentPage, [
            'path' => route("users.message.check"),
            'query' => $request->all()
        ]);

        return view('admin.users.userMessageCheck', compact('data'));
    }

    public function feature_flags(Request $request){
        $data['features'] = Features::get()->toArray();
        return view('admin.users.feature_flags', $data);
    }

    public function feature_flags_create(Request $request){
        if($request->method()=='GET'){
            return view('admin.users.feature_flags_create');
        }else if($request->method()=='POST'){
            $feature = $request->feature;
            $introduction = $request->introduction ?? '';
            $priority = $request->priority ?? 0;

            $description_data = array(
                'introduction'=>$introduction,
                'priority'=>$priority
            );

            $data = array(
                'key'=>$feature,
                'feature'=>$feature,
                'description'=> json_encode($description_data)
            );

            Features::insert($data);

            return redirect('/admin/global/feature_flags');
        }else{
            return 'method invalid';
        }

    }

    public function feature_flags_edit(Request $request){
        if($request->method()=='GET'){
            $feature_key = $request->feature_key;

            $data['feature'] = Features::where('id',$feature_key)->first();

            return view('admin.users.feature_flags_edit', $data);
        }else if($request->method()=='POST'){
            $feature_id = $request->feature_id;
            $feature = $request->feature;
            $introduction = $request->introduction ?? '';
            $priority = $request->priority ?? 0;

            $description_data = array(
                'introduction'=>$introduction,
                'priority'=>$priority
            );

            $data = array(
                'key'=>$feature,
                'feature'=>$feature,
                'description'=> json_encode($description_data)
            );

            Features::where('id',$feature_id)->update($data);

            return redirect('/admin/global/feature_flags');
        }else{
            return 'method invalid';
        }
    }

    public function feature_flags_update(Request $request){
        $feature_id = $request->feature_id;
        $feature = $request->feature;
        $status =  $request->status;
        if($status=='true'){
            $active_at = now();
        }else{
            $active_at = null;
        }

        Features::where('id',$feature_id)->update(['active_at'=>$active_at]);

    }

    public function feature_flags_delete(Request $request){
        $feature_id = $request->feature_id;
        $status = Features::where('id',$feature_id)->delete();
        if($status){
            $data = array(
                'code'=>200,
                'message'=>'刪除成功'
            );
        }else{
            $data = array(
                'code'=>400,
                'message'=>'刪除失敗'
            );
        }

        return json_encode($data);
    }

    public function advanceVerify(Request $request){
        $user = User::where('id', $request->user_id)->first();

        if($request->pass) {
            $user->advance_auth_status = 1;
            DB::table("banned_users")->where("member_id",$user->id)->where("adv_auth",1)->delete();
            $message = '已通過進階驗證並移除驗證封鎖';
        } else {
            $user->advance_auth_status = 0;
            $message = '已移除進階驗證';
        }

        $user->save();

        return back()->with('message', $message);
    }

    //vvip
    public function viewVvipApplication() {
        $applicationData = VvipApplication::select(
            'users.name',
            'users.email',
            'vvip_application.*',
            'member_value_added_service.service_name',
            'member_value_added_service.active as service_status',
            'member_value_added_service.expiry',
            'vvip_info.status as vvip_info_status',
            'vvip_info.about',
            'vvip_info.user_id as vvip_user_id'
        )
            ->leftJoin('users','users.id','vvip_application.user_id')
            ->leftJoin('member_value_added_service','member_value_added_service.order_id','vvip_application.order_id')
            ->leftJoin('vvip_info', 'vvip_info.user_id', 'vvip_application.user_id')
            ->paginate(20);
        return view('admin.users.vvipApplication', compact('applicationData'));
    }

    public function editVvipApplication(Request $request) {
        Log::Info($request);
        $id = $request->input('id');
        $user_id = $request->input('user_id');
        $user = User::find($user_id);
        $status = $request->input('status');
        $note = $request->input('note');

        if($status == 3){
            $deadline = $request->input('deadline');
            $supplement_notice = $request->supplement_notice;
            if($deadline==''){
                return back()->with('message', '未填寫補件期限');
            }
            VvipApplication::where('id', $id)->update(['status' => $status, 'deadline' => $deadline." 23:59:59", 'note' => $note, 'supplement_notice' => $supplement_notice]);
        }else if($status != ''){
            VvipApplication::where('id', $id)->update(['status' => $status, 'note' => $note]);
            if($status==1){
                //VVIP付款中 經通過則取消原VIP
                $vvipStatus = ValueAddedService::where('service_name', 'VVIP')->where('member_id', $user_id)->where('active', 1)->orderBy('created_at', 'desc')->first();
                if(isset($vvipStatus)){
                    VipLog::addToLog($user_id, 'Upgrade VVIP, system auto cancel VIP pay.', 'XXXXXXXXX', 0, 0);
                    $userVIP = $user->getVipData(true);
                    if($userVIP ?? false) {
                        logger('Upgrade VVIP, User ' . $user_id . ' VIP orderID '.$userVIP->order_id.' cancellation initiated.');
                        $vip = Vip::findByIdWithDateDesc($user_id);
                        $this->logService->cancelLog($vip);
                        $this->logService->writeLogToDB();
                        $file = $this->logService->writeLogToFile();
                        logger('$before_cancelVip: '.$vip->updated_at);
                        if( strpos(\Storage::disk('local')->get($file[0]), $file[1]) !== false) {
                            logger('Upgrade VVIP, User ' . $user_id . ' VIP orderID '.$userVIP->order_id.' cancellation finished.');
                        }
                        $userVIP->removeVIP();
                    }
                }
            }
        }else{
            VvipApplication::where('id', $id)->update(['note' => $note]);
        }

        return back()->with('message', '資料已更新');
    }

    public function vvip_get_prove_img(Request $request) {
        $user_id = $request->user_id;
        $imgData = VvipProveImg::where('user_id',$user_id)->orderBy('created_at','desc')->get();
        return response()->json(array(
            'imgData' => $imgData,
        ), 200);
    }

    public function vvipInfo_admin_edit(Request $request) {
        $user_id = $request->input('user_id');
        $vvipInfo = VvipInfo::where('user_id', $user_id)->first();
        if($vvipInfo) {
//            $vvipInfo = new VvipInfo();
//            $vvipInfo->user_id = $user_id;
            $vvipInfo->about = $request->input('about');
            $vvipInfo->save();
            return back()->with('message', '資料已更新');
        }
        return back()->with('message', '尚未產生會員頁資料');

    }

    public function vvipInfo_status_toggle(Request $request)
    {
        $user_id = $request->user_id;
        $status = $request->status;
        $vvipInfo = VvipInfo::where('user_id', $user_id)->first();
        if($vvipInfo) {
            $vvipInfo->status = $status;
            $vvipInfo->save();
            if($status == 1) {
                $msg = '會員頁已啟用';
            }else if($status == 0){
                $msg = '會員頁已關閉';
            }
        }else{
            $msg = '尚未產生會員頁資料';
        }

        echo json_encode(['msg' => $msg]);
    }

    //    public function viewVvipInvite() {
//        $inviteData = VvipInvite::select('a.name','a.email','vvip_invite.*', 'b.name as invite_name', 'b.email as invite_email')
//            ->leftJoin('users as a','a.id','vvip_invite.user_id')
//            ->leftJoin('users as b','b.id','vvip_invite.invite_user_id')
//            ->paginate(20);
//        return view('admin.users.vvipInvite', compact('inviteData'));
//    }

    public function viewVvipSelectionRewardApply() {
        return view('admin.users.vvipSelectionReward');
    }

    public function getVvipSelectionRewardData(Request $request)
    {
        if ($request->ajax()) {
            $data = VvipSelectionReward::
            select([
                'vvip_selection_reward.*',
                'users.email',
                'users.name',
                'users.is_admin_chat_channel_open'
            ])
                ->selectRaw('(select CAST(count(*) AS UNSIGNED) as num from vvip_selection_reward_apply as aa where aa.vvip_selection_reward_id=vvip_selection_reward.id) as applyCounts')
                ->leftJoin('users','users.id','vvip_selection_reward.user_id');

            return Datatables::eloquent($data)->make(true);
        }
    }

    public function vvipSelectionRewardApplyUpdate(Request $request)
    {
        if ($request->ajax()) {
            $msg = '';
            $success = '';

            if($request->name=='status'){
                $data = VvipSelectionReward::where('id', $request->pk)->first();
                if($request->value == 1 && ($data->limit == '' || $data->limit == 0)){
                    $success = false;
                    $msg = '請先設定核定人數，系統將依人數通知繳款費用';
                    return response()->json(['success' => $success, 'msg' => $msg]);
                }
            } elseif($request->name=='notice_status'){
                $data = VvipSelectionReward::where('id', $request->pk)->first();
                if($data->limit == '' || $data->limit == 0){
                    $success = false;
                    $msg = '請先設定核定人數，系統將依人數通知繳款費用';
                    return response()->json(['success' => $success, 'msg' => $msg]);
                }
            }

            if($request->name=='condition' || $request->name=='identify_method' || $request->name=='bonus_distribution'){
                $array = explode("_", $request->pk);
                $pk = $array[0];
                $key = $array[1];

                $data = VvipSelectionReward::where('id', $pk)->first();

                switch ($request->name) {
                    case 'condition':
                        $jsonData = json_decode($data->condition, JSON_UNESCAPED_UNICODE);
                        break;
                    case 'identify_method':
                        $jsonData = json_decode($data->identify_method, JSON_UNESCAPED_UNICODE);
                        break;
                    case 'bonus_distribution':
                        $jsonData = json_decode($data->bonus_distribution, JSON_UNESCAPED_UNICODE);
                        break;
                }

                $jsonData[$key] = $request->value;
                $newValue = json_encode($jsonData, JSON_UNESCAPED_UNICODE);

                VvipSelectionReward::find($pk)
                    ->update([
                        $request->name => $newValue
                    ]);
                $success = true;
            } elseif($request->name=='expire_date'){
                VvipSelectionReward::find($request->pk)
                    ->update([
                        $request->name => $request->value." 23:59:59"
                    ]);
                $success = true;
            } else {
                VvipSelectionReward::find($request->pk)
                    ->update([
                        $request->name => $request->value
                    ]);
                $success = true;

                //                if($request->name=='notice_status' && $request->value==1){
//                    //通知繳費
//                }
            }

            $currentData = VvipSelectionReward::find($request->pk);
            if( $success == true){
                if($request->name=='status'){
                    switch ($request->value) {
                        case 1:
                            Message::post(1049, $currentData->user_id, '您有申請甜心選拔「'.$currentData->title.'」已通過, 目前徵選活動已開始, 通過條件驗證的女會員將出現在您的徵選收件夾中。', true, 0);
                            break;
                        case 2:
                            Message::post(1049, $currentData->user_id, '您有申請甜心選拔「'.$currentData->title.'」不通過, 如有任何問題請與站長聯絡。', true, 0);
                            break;
                    }
                }
            }


            return response()->json(['success' => $success, 'msg' => $msg]);
        }
    }

    public function vvipSelectionRewardApplyDeleteKey(Request $request)
    {
        if ($request->ajax()) {

            $data = VvipSelectionReward::where('id',$request->pk)->first();

            switch ($request->name) {
                case 'condition':
                    $jsonData = json_decode($data->condition, JSON_UNESCAPED_UNICODE);
                    break;
                case 'identify_method':
                    $jsonData = json_decode($data->identify_method, JSON_UNESCAPED_UNICODE);
                    break;
                case 'bonus_distribution':
                    $jsonData = json_decode($data->bonus_distribution, JSON_UNESCAPED_UNICODE);
                    break;
            }
            unset($jsonData[$request->key]);
            $new_array = array();
            $kk=1;
            foreach ($jsonData as $key=> $value){
                $new_array[$kk] = $value;
                $kk = $kk+1;
            }
            $newValue = json_encode($new_array, JSON_UNESCAPED_UNICODE);
            VvipSelectionReward::find($request->pk)
                ->update([
                    $request->name => $newValue
                ]);

            return response()->json(['success' => true]);
        }
    }

    public function vvipSelectionRewardApplyKeyUpdate(Request $request)
    {
        if ($request->ajax()) {

            $data = VvipSelectionReward::where('id',$request->pk)->first();
            $jsonData = json_decode($data->condition, JSON_UNESCAPED_UNICODE);
            ksort($jsonData);
            $new_array = array();
            $start_pos = $request->start_pos;
            $end_pos = $request->end_pos;
            $kk=1;
            if($start_pos>$end_pos) {
                foreach ($jsonData as $key => $value) {
                    if($kk==$end_pos+1){
                        $new_array[$end_pos+2] = $value;
                    }elseif($kk<$start_pos+1 && $kk >$end_pos+1) {
                        $new_array[$kk+1] = $value;
                    }elseif($kk==$start_pos+1){
                        $new_array[$end_pos+1] = $value;
                    }else{
                        $new_array[$kk] = $value;
                    }
                    $kk = $kk+1;

                }
            }elseif($start_pos<$end_pos){
                foreach ($jsonData as $key => $value) {
                    if($kk>$start_pos+1 && $kk<=$end_pos+1) {
                        $new_array[$kk-1] = $value;
                    }elseif($kk==$start_pos+1){
                        $new_array[$end_pos+1] = $value;
                    }else{
                        $new_array[$kk] = $value;
                    }
                    $kk = $kk+1;

                }
            }
            ksort($new_array);
            $newValue = json_encode($new_array, JSON_UNESCAPED_UNICODE);
            VvipSelectionReward::find($request->pk)
                ->update([
                    $request->name => $newValue
                ]);

            return response()->json(['success' => true]);
        }
    }

    public function vvipSelectionRewardApplyAddData(Request $request)
    {
        if ($request->ajax()) {

            $data = VvipSelectionReward::where('id',$request->pk)->first();
            switch ($request->name) {
                case 'condition':
                    $jsonData = json_decode($data->condition, JSON_UNESCAPED_UNICODE);
                    break;
                case 'identify_method':
                    $jsonData = json_decode($data->identify_method, JSON_UNESCAPED_UNICODE);
                    break;
                case 'bonus_distribution':
                    $jsonData = json_decode($data->bonus_distribution, JSON_UNESCAPED_UNICODE);
                    break;
            }

            $new_array = array();

            $kk=1;
            foreach ($jsonData as $key=> $value){
                $new_array[$kk] = $value;
                $kk = $kk+1;
            }

            $new_array[$kk] = $request->value;
            ksort($new_array);
            $newValue = json_encode($new_array, JSON_UNESCAPED_UNICODE);
            VvipSelectionReward::find($request->pk)
                ->update([
                    $request->name => $newValue
                ]);

            return response()->json(['success' => true]);
        }
    }

    public function viewVvipSelectionRewardApplyList(Request $request) {

        $selectionRewardData = VvipSelectionReward::select(
            'users.name',
            'users.email',
            'vvip_selection_reward.*'
        )
            ->leftJoin('users','users.id','vvip_selection_reward.user_id')
            ->where('vvip_selection_reward.id', $request->id)
            ->first();

        $applicationData = VvipSelectionRewardApply::select(
            'users.name',
            'users.email',
            'vvip_selection_reward_apply.*'
        )
            ->leftJoin('users','users.id','vvip_selection_reward_apply.user_id')
            ->where('vvip_selection_reward_id', $request->id)
            ->get();
        return view('admin.users.vvipSelectionRewardApplyList', compact('applicationData', 'selectionRewardData'));
    }

    public function vvipSelectionRewardApplyListUpdate(Request $request)
    {
        if ($request->ajax()) {
            $msg = '';
            $success = '';
            $data = VvipSelectionRewardApply::find($request->pk);
            //get limit
            $getSelectionRewardData = VvipSelectionReward::find($data->vvip_selection_reward_id);
            //get pass counts
            $passCounts = VvipSelectionRewardApply::select('id')
                ->where('vvip_selection_reward_id', $getSelectionRewardData->id)
                ->where('status', 1)
                ->get()->count();

            if($passCounts >= $getSelectionRewardData->limit && $request->value == 1){
                $success = false;
                $msg = '通過人數已超過核定人數';
            }else {

                VvipSelectionRewardApply::find($request->pk)
                    ->update([
                        $request->name => $request->value
                    ]);
                $success = true;

                if($request->name == 'status' && $request->value == 1) {
                    Message::post($data->user_id, $getSelectionRewardData->user_id,  '您已通過「'.$getSelectionRewardData->title.'」活動選拔，現在您可以與對方交談約見。', true, 1);
                    Message::post($getSelectionRewardData->user_id, $data->user_id,   '對方已通過「'.$getSelectionRewardData->title.'」活動選拔，現在您可以與對方交談約見。', true, 1);
                }

            }

            return response()->json(['success' => $success, 'msg' => $msg]);
        }
    }

    //vvip end

    public function check_extend(Request $request)
    {
        $uid = $request->user_id;
        $operator_id = Auth::user()->id;
        $ip = $request->ip();
        BackendUserDetails::check_extend($uid, $operator_id, $ip);
        $msg_type    = 'message';
        $msg_content = '已延長等待更多資料';
        return back()->with($msg_type, $msg_content);
    }

    public function getMessageFromRoomId(Request $request)
    {
        $room_id = $request->room_id;
        $chat_with_admin = $request->chat_with_admin;
        $query = Message::withTrashed()->select('message.*', 'message.id as mid', 'message.created_at as m_time', 'u.*', 'u.id as u_id', 'b.id as banned_id', 'b.expire_date as banned_expire_date')
            ->leftJoin('users as u', 'u.id', 'message.from_id')
            ->leftJoin('banned_users as b', 'message.from_id', 'b.member_id')
            ->where('room_id', $room_id)
            ->where('message.created_at', '>=', \Carbon\Carbon::parse("180 days ago")->toDateTimeString())
            ->orderBy('message.created_at')
            ->take(1000);

        if($chat_with_admin) {
            $query->where('chat_with_admin',$chat_with_admin);
        }

        $message_detail = $query->get();

        //給getAdminMessageRecordDetailFromRoomId用的
        if($chat_with_admin) {
            return $message_detail;
        }
        $room_users = MessageRoomUserXref::where('room_id', $room_id)->get();
        $users_data = [];
        $users = [];
        foreach($room_users as $room_user) {
            $users[] = $room_user->user_id;
            $users_data[$room_user->user_id]['tipcount'] =  Tip::TipCount_ChangeGood($room_user->user_id) ?? false;
            $users_data[$room_user->user_id]['vip'] =  Vip::vip_diamond($room_user->user_id) ?? false;
        }

        $message_href = route('admin/showMessagesBetween', $users);

        return response()->json([
            'room_id' => $room_id,
            'message_detail' => $message_detail,
            'users_data' => $users_data,
            'message_href' => $message_href
        ], 201);
    }

    public function vipIndex()
    {
        return view('admin.users.searchVip')
            ->with('short_messages',session()->get('short_messages'))
            ->with('forbidden_deleted_from_arr',ShortMessageService::$forbidden_deleted_from_arr);
    }

    public function vipSearch(Request $request)
    {

        if (!$request->search) {
            return redirect('admin/users/vip');
        }

        $users = User::select('id', 'email', 'engroup')
            ->where('email', 'like', '%' . $request->search . '%')
            ->get();
        foreach ($users as $user) {
            $isVip = $user->isVip();
            $user['advance_auth_count'] = LogAdvAuthApi::where('user_id', $user->id)->where('user_fault', 1)->count();

            if ($isVip == 1) {
                $user['isVip'] = true;
            } else {
                $user['isVip'] = false;
            }

            if (member_vip::select("order_id")->where('member_id', $user->id)->get()->first()) {
                $user['vip_order_id'] = member_vip::select("order_id")
                    ->where('member_id', $user->id)
                    ->get()->first()->order_id;
            }
            $user['vip_data'] = Vip::select('id', 'free', 'expiry', 'payment_method', 'created_at', 'updated_at')
                ->where('member_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get()->first();
            if (VipLog::select("updated_at")->where('member_id', $user->id)->orderBy('updated_at', 'desc')->get()->first()) {
                $user['updated_at'] = VipLog::select("updated_at")->where('member_id', $user->id)->orderBy('updated_at', 'desc')->get()->first()->updated_at;
            }
        }
        return view('admin.users.searchVip')
            ->with('users', $users)
            ->with('forbidden_deleted_from_arr',ShortMessageService::$forbidden_deleted_from_arr);
    }
    
    public function short_message_search(Request $request)
    {

        if (!$request->phone_search && !$request->del_all_short_message) {
            return redirect('admin/users/vip');
        }
        
        $phone_search = $request->phone_search;
        
        if($request->del_all_short_message) {
            ShortMessageService::forceDeleteWithTrashedByPhoneNumber($request->del_all_short_message);
        }
        
        $short_messages = ShortMessageService::getWithTrashedByPhoneNumber($phone_search);
        
        return back()->with('short_messages',$short_messages);
    }    

    public function periodExtend(Request $request)
    {
        $extend = $request->extend;
        $user = User::where('id', $request->user_id)->first();
        if($user->isVip()) {
            $vipData = $user->getVipData(true);
            $payment = $vipData->payment;
            $expire_origin = $vipData->expiry;
            $expire_date = date("Y-m-d H:i:s",strtotime("+".$extend." days", strtotime($vipData->expiry)));
            $remain_days_origin = $vipData->remain_days;
            $remain_days = $remain_days_origin + $extend;

            if($payment=='one_quarter_payment' || $payment=='one_month_payment' || is_null($payment)){
                $vipData->expiry= $expire_date;
                $vipData->save();

                VipLog::addToLog($user->id, 'backend_extend_expiry_service: +'.$extend.' days', 'Manual Setting', 1, 0);
                VipExpiryLog::addToLog($user->id, $vipData, $expire_origin, $expire_date, null, null);
            }else if($payment=='cc_quarterly_payment' || $payment=='cc_monthly_payment'){

                if($expire_origin=='0000-00-00 00:00:00') {
                    $vipData->remain_days = $remain_days;
                    $vipData->save();

                    if(!(EnvironmentService::isLocalOrTestMachine())) {
                        $order_user = Vip::select('id', 'expiry', 'created_at', 'updated_at','payment','business_id', 'order_id','remain_days')
                            ->where('member_id', $user->id)
                            ->orderBy('created_at', 'desc')->get();
                        $order = Order::where('order_id', $order_user[0]->order_id)->get()->first();
                        if($order){
                            Order::where('order_id', $order_user[0]->order_id)->update([
                                'remain_days'=> $order->remain_days+ $extend
                            ]);
                        }
                    }
                    VipLog::addToLog($user->id, 'backend_extend_expiry_service: +'.$extend.' remain_days', 'Manual Setting', 1, 0);
                    VipExpiryLog::addToLog($user->id, $vipData, null, null, $remain_days_origin, $remain_days);
                } else {
                    $vipData->expiry= $expire_date;
                    $vipData->save();
                    VipLog::addToLog($user->id, 'backend_extend_expiry_service: +'.$extend.' days', 'Manual Setting', 1, 0);
                    VipExpiryLog::addToLog($user->id, $vipData, $expire_origin, $expire_date, null, null);
                }
            }
        }
        else {
            $vipData = Vip::findByIdWithDateDesc($user->id);
            if($vipData && $vipData->expiry != '0000-00-00 00:00:00'){
                if($vipData->expiry < Carbon::now()){
                    $expire_date = date("Y-m-d H:i:s",strtotime("+".$extend." days"));
                }else{
                    $expire_date = date("Y-m-d H:i:s",strtotime("+".$extend." days", strtotime($vipData->expiry)));
                }
                $vipData->expiry = $expire_date;
                $vipData->active = 1;
                $vipData->save();
            }else if(!$vipData){
                $expire_date = date("Y-m-d H:i:s",strtotime("+".$extend." days"));
                $vip = new Vip();
                $vip->member_id = $user->id;
                $vip->expiry = $expire_date;
                $vip->active = 1;
                $vip->free = 0;
                $vip->save();
            }

            VipLog::addToLog($user->id, 'backend_extend_expiry_service: +'.$extend.' days', 'Manual Setting', 1, 0);
            VipExpiryLog::addToLog($user->id, $user->getVipData(true), null, $expire_date, null, null);
        }

        return response()->json(['msg'=>'新增天數成功!']);
    }

    public function periodTransfer(Request $request)
    {
        $from_user = User::where('id', $request->user_id)->first();
        $to_user = User::where('email', $request->transfer_to)->first();

        $from_user_vipData = $from_user->getVipData(true);
        $to_user_vipData = $to_user->getVipData(true);

        if($from_user->isVip()) {
            $from_user_expire_origin = $from_user_vipData->expiry;
            $from_user_remain_days = $from_user_vipData->remain_days;
            $from_user_payment = $from_user_vipData->payment;

            $to_user_not_vip = is_null($to_user_vipData);
            $same_payment = $from_user_payment == $to_user_vipData?->payment;

            if($to_user_not_vip || $same_payment) {
                if($from_user_expire_origin != '0000-00-00 00:00:00') {
                    VipLog::addToLog($from_user->id, 'backend_transfer_service to '.$to_user->id, 'Manual Setting', 0, 0);
                    VipExpiryLog::addToLog($from_user->id, $from_user_vipData, $from_user_expire_origin, '0000-00-00 00:00:00', null, null);
                } else {
                    VipLog::addToLog($from_user->id, 'backend_transfer_service to '.$to_user->id, 'Manual Setting', 0, 0);
                    VipExpiryLog::addToLog($from_user->id, $from_user_vipData, null, null, $from_user_remain_days, 0);
                }
            }

            if($to_user_not_vip) {
                $vip = new Vip();
                $vip->member_id = $to_user->id;
                $vip->business_id = $from_user_vipData->business_id;
                $vip->order_id = $from_user_vipData->order_id;
                $vip->expiry = $from_user_expire_origin;
                $vip->payment = $from_user_payment;
                $vip->amount = $from_user_vipData->amount;
                $vip->remain_days = $from_user_remain_days;
                $vip->active = 1;
                $vip->free = 0;
                $vip->save();

                VipLog::addToLog($to_user->id, 'upgrade, receive backend_transfer_service from '.$from_user->id, 'Manual Setting', 1, 0);
                VipExpiryLog::addToLog($to_user->id, $to_user->getVipData(true), null, $from_user_expire_origin, null, $from_user_remain_days);

            } else if ($same_payment) {
                $from_user_expiry = $from_user_expire_origin;
                if($from_user_expire_origin == '0000-00-00 00:00:00') {
                    $from_user_expiry = $from_user->ComputeRemainDay();
                }
                $from_user_expiry = Carbon::parse($from_user_expiry);
                $to_user_expire_origin = $to_user_vipData->expiry;
                $to_user_payment = $to_user_vipData->payment;

                if(substr($to_user_payment,0,3) == 'cc_' && $to_user_expire_origin=='0000-00-00 00:00:00') {
                    $from_user_vipDays = $from_user_expiry->diffInDays(Carbon::now());
                    $to_user_remain_days_origin = $to_user_vipData->remain_days;
                    $to_user_remain_days = $to_user_vipData->remain_days + $from_user_vipDays;
                    $to_user_vipData->remain_days = $to_user_remain_days;
                    $to_user_vipData->save();
                    VipLog::addToLog($to_user->id, 'extend_expiry, receive backend_transfer_service from '.$from_user->id, 'Manual Setting', 1, 0);
                    VipExpiryLog::addToLog($to_user->id, $to_user_vipData, null, null, $to_user_remain_days_origin, $to_user_remain_days);
                } else{
                    $from_user_vipDays = $from_user_expiry->diffInSeconds(Carbon::now());
                    $to_user_expire_date = date("Y-m-d H:i:s",strtotime("+".$from_user_vipDays." seconds", strtotime($to_user_expire_origin)));
                    $to_user_vipData->expiry = $to_user_expire_date;
                    $to_user_vipData->save();
                    VipLog::addToLog($to_user->id, 'extend_expiry, receive backend_transfer_service from '.$from_user->id, 'Manual Setting', 1, 0);
                    VipExpiryLog::addToLog($to_user->id, $to_user_vipData, $to_user_expire_origin, $to_user_expire_date, null, null);
                }
            } else {
                return response()->json(['msg'=>'兩位會員皆為vip且型態不同，無法進行此操作']);
            }
        } else {
            return response()->json(['msg'=>'原會員非vip，無法進行此操作']);
        }

        $from_user_vipData->removeVIP();
        return response()->json(['msg'=>'移轉 vip 權限成功!']);
    }

    public function updateVipAdvandceAuthCount(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $advance_auth_count = LogAdvAuthApi::where('user_id', $user->id)->where('user_fault', 1)->count();
        if ($advance_auth_count == 3) {
            LogAdvAuthApi::where('user_id', $user->id)->where('forbid_user', 1)->update([
                'user_fault' => 0,
                'forbid_user' => 0,
                'pass_fault' => 1
            ]);
            return response()->json(['msg'=>'調整進階驗證次數成功!']);
        } else {
            return response()->json(['msg'=>'進階驗證次數小於3次，不需調整']);
        }
    }

    public function wait_for_more_data_list(Request $request)
    {
        $check_extend_list = BackendUserDetails::with('user')
            ->with('check_extend_admin_action_log')
            ->where('is_waiting_for_more_data',1)
            ->get();
        //按照relationship排序
        $check_extend_list =  $check_extend_list->sortByDesc(function($query){
            return $query->check_extend_admin_action_log->first()->created_at ?? false;
        })
            ->all();
        return view('admin.users.wait_for_more_data_list')
            ->with('check_extend_list', $check_extend_list);
    }

    public function check_extend_by_login_time(Request $request)
    {
        $uid = $request->user_id;
        $operator_id = Auth::user()->id;
        $ip = $request->ip();
        BackendUserDetails::check_extend_by_login_time($uid, 3, $operator_id, $ip);
        $msg_type    = 'message';
        $msg_content = '已延長等待更多資料(發回)';
        return back()->with($msg_type, $msg_content);
    }

    public function wait_for_more_data_login_time_list(Request $request)
    {
        $check_extend_list = AdminActionLog::with('operator_user')
            ->with('user')
            ->where('act','等待更多資料(發回)')
            ->orderByDesc('id')
            ->get();
        return view('admin.users.wait_for_more_data_login_time_list')
            ->with('check_extend_list', $check_extend_list);
    }

    public function commitUser(Request $request)
    {
        $operator_id = Auth::user()->id;
        $user_id = $request->user_id;
        $commit = $request->commit;

        UserRemarksLog::insert_commit($operator_id, $user_id, $commit);
        $msg_type    = 'message';
        $msg_content = '已備註成功';
        return back()->with($msg_type, $msg_content);
    }

    public function showAvgMedian(){
        $day_statistic = DB::table('log_system_day_statistic')->where(
            'date', '>=', Carbon::now()->subMonth()->toDateTimeString()
        )->orderby('date', 'desc')->get();

        $calculations = DB::table('greeting_rate_calculations')->orderby('created_at', 'desc')->first();
        $infix = $calculations->infix;
        $greetingRate = UserService::computeGreetingRate($calculations->postfix);
        return view('admin.users.showAvgMedian', compact('day_statistic', 'infix', 'greetingRate'));
    }

    public function modifyGreetingRateCalculations(Request $request){
        $postfix = UserService::toPostfix($request->infix);
        $v = UserService::computeGreetingRate($postfix);
        if($request->save) {
            $calculation = new GreetingRateCalculation;
            $calculation->infix = $request->infix;
            $calculation->postfix = $postfix;
            $calculation->save();
        }

        return response()->json($v);
    }
    public function observe_user(Request $request){
        ObserveUser::updateOrCreate(['user_id'=>$request->user_id], ['reason'=>$request->reason, 'admin_id'=>auth()->user()->id]);
        return back()->with('message', '該帳號已列入觀察名單');
    }
    public function observe_user_remove(Request $request){
        ObserveUser::where('user_id', $request->user_id)->delete();
        return back()->with('message', '該帳號已移除觀察名單');
    }

    public function observe_user_list(Request $request){
        $observeUserList=ObserveUser::selectRaw('observe_user.*, users.name as user_name, users.email as user_email, users.advance_auth_time as advance_auth_time')
            ->leftJoin('users', 'users.id', 'observe_user.user_id')
            ->orderBy('observe_user.created_at','desc');
        if (!empty($request->get('account'))) {
            $observeUserList->where('users.email', '=', $request->get('account'));
        }
        if (!empty($request->get('reason'))) {
            $observeUserList->where('observe_user.reason', 'like', '%' . $request->get('reason') . '%');
        }
        if (!empty($request->get('date_start'))) {
            $observeUserList->where('observe_user.created_at', '>=', $request->get('date_start'));
        }
        if (!empty($request->get('date_end'))) {
            $observeUserList->where('observe_user.created_at', '<=', $request->get('date_end')." 23:59:59");
        }
        $observeUserList=$observeUserList->paginate(50);

        return view('admin.users.observeUserList', compact('observeUserList'));
    }

    public function track_user(Request $request){
        TrackUser::updateOrCreate(['user_id'=>$request->user_id], ['reason'=>$request->reason, 'admin_id'=>auth()->user()->id]);
        return back()->with('message', '已列入列管追蹤名單');
    }
    public function track_user_remove(Request $request){
        TrackUser::where('user_id', $request->user_id)->delete();
        return back()->with('message', '該帳號已移除列管追蹤名單');
    }

    public function track_user_list(Request $request){
        $trackUserList=TrackUser::selectRaw('track_user.*, users.name as user_name, users.email as user_email')
            ->leftJoin('users', 'users.id', 'track_user.user_id')
            ->orderBy('track_user.created_at','desc');
        if (!empty($request->get('account'))) {
            $trackUserList->where('users.email', '=', $request->get('account'));
        }
        if (!empty($request->get('reason'))) {
            $trackUserList->where('track_user.reason', 'like', '%' . $request->get('reason') . '%');
        }
        if (!empty($request->get('date_start'))) {
            $trackUserList->where('track_user.created_at', '>=', $request->get('date_start'));
        }
        if (!empty($request->get('date_end'))) {
            $trackUserList->where('track_user.created_at', '<=', $request->get('date_end')." 23:59:59");
        }
        $trackUserList=$trackUserList->paginate(50);

        return view('admin.users.trackUserList', compact('trackUserList'));
    }

    public function medium_long_term_without_adv_verification_list(Request $request)
    {
        $user_list = SuspiciousUserListTable::where('is_medium_long_term_without_adv_verification', 1)
                                                ->orderByDesc('medium_long_term_without_adv_verification_created_at')
                                                ->get();

        $banned_user_list = banned_users::whereIn('member_id', $user_list->pluck('user_id'))->get()->pluck('member_id')->toArray();

        $communication_count = intval(DB::table('queue_global_variables')->where('name','medium_long_term_without_adv_verification_communication_count_set')->first()->value);
        
        return view('admin.users.medium_long_term_without_adv_verification_list', compact('user_list', 'banned_user_list', 'communication_count'));
    }

    public function medium_long_term_without_adv_verification_user_remove(Request $request)
    {
        SuspiciousUserListTable::remove_medium_long_term_without_adv_verification($request->user_id);
        return back()->with('message', '該帳號已移除名單');
    }

    public function medium_long_term_without_adv_verification_communication_count_set_change(Request $request)
    {
        DB::table('queue_global_variables')->where('name','medium_long_term_without_adv_verification_communication_count_set')->update(['value' => $request->communication_count_set]);
        return back()->with('message', '已修改總通訊人數');
    }

    public function suspicious_list_count_set_change(Request $request)
    {
        DB::table('queue_global_variables')->where('name','suspicious_list_communication_weekly_count_set')->update(['value' => $request->communication_count_weekly_set]);
        DB::table('queue_global_variables')->where('name','suspicious_list_communication_country_count_set')->update(['value' => $request->country_count_set]);
        return back()->with('message', '已修改');
    }
    
}
