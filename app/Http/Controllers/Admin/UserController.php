<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Models\Board;
use App\Models\ExpectedBanningUsers;
use App\Models\Fingerprint2;
use App\Models\MemberPic;
use App\Models\Message;
use App\Models\Reported;
use App\Models\ReportedAvatar;
use App\Models\ReportedPic;
use App\Models\SimpleTables\users;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\AdminService;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserInviteRequest;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\AdminAnnounce;
use App\Models\MasterWords;
use App\Models\AdminCommonText;
use App\Models\VipLog;
use App\Models\Vip;
use App\Models\Tip;
use App\Models\Msglib;
use App\Models\BasicSetting;
use App\Models\SimpleTables\member_vip;
use App\Models\SimpleTables\banned_users;
use App\Models\SimpleTables\warned_users;
use App\Models\BannedUsersImplicitly;
use App\Notifications\BannedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct(UserService $userService, AdminService $adminService)
    {
        $this->service = $userService;
        $this->admin = $adminService;
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
        if (! $request->search) {
            return redirect('admin/users/search');
        }
        
        $users = User::select('id', 'name', 'email', 'engroup')
                 ->where('email', 'like', '%'.$request->search.'%')
                 ->get();
        foreach($users as $user){
            $isVip = $user->isVip();        
            if($user->engroup == 1){

                $user['gender_ch'] = '男';
            }
            else{
                $user['gender_ch'] = '女';
            }
            if($isVip == 1){
                $user['isVip'] = true;

            }
            else{
                $user['isVip'] = false;
            }
            if(member_vip::select("order_id")->where('member_id', $user->id)->get()->first()){
                $user['vip_order_id'] = member_vip::select("order_id")
                                        ->where('member_id', $user->id)
                                        ->get()->first()->order_id;
            }
            $user['vip_data'] = Vip::select('id', 'free', 'expiry', 'created_at', 'updated_at')
                                ->where('member_id', $user->id)
                                ->orderBy('created_at', 'desc')
                                ->get()->first();
            if(VipLog::select("updated_at")->where('member_id', $user->id)->orderBy('updated_at', 'desc')->get()->first()){
                $user['updated_at'] = VipLog::select("updated_at")->where('member_id', $user->id)->orderBy('updated_at', 'desc')->get()->first()->updated_at;
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
    public function toggleGender(Request $request){
        $user = User::select('id', 'engroup', 'email')
                ->where('id', $request->user_id)
                ->get()->first();
        if($request->gender_now == 1){
            $user->engroup = '2';    
        }
        else{
            $user->engroup = '1';
        }
        $user->save();
        if(isset($request->page)){
            switch($request->page){
                case 'advInfo':
                    return redirect('admin/users/advInfo/'.$request->user_id);
                default:
                    return view('admin.users.success')
                            ->with('email', $user->email);
                break;
            }
        }else{
            return view('admin.users.success')
               ->with('email', $user->email);
        }
    }

    /**
     * Toggle the gender of a specific member.
     *
     * @return \Illuminate\Http\Response
     */
    public function toggleVIP(Request $request){
        if($request->isVip == 1){
            //關閉VIP權限
            $setVip = 0;
            $user = Vip::select('member_id', 'active')
                    ->where('member_id', $request->user_id)
                    ->update(array('active' => $setVip));
        }else{
            //提供VIP權限
            $setVip = 1;
            $tmpsql = Vip::select('expiry')->where('member_id', $request->user_id)->get()->first();
            if(isset($tmpsql)){
                $user = Vip::select('member_id', 'active')
                    ->where('member_id', $request->user_id)
                    ->update(array('active' => $setVip));
            }else{
                //從來都沒VIP資料的
                $vip_user = new Vip;
                $vip_user->member_id = $request->user_id;
                $vip_user->active = $setVip;
                $vip_user->created_at =  Carbon::now()->toDateTimeString();
                $vip_user->save();
            }
            
        }

        VipLog::addToLog($request->user_id, $setVip == 0 ? 'manual_cancel' : 'manual_upgrade', 'Manual Setting', $setVip, 1);
        $user = User::select('id', 'email', 'name')
                ->where('id', $request->user_id)
                ->get()->first();
        if(isset($request->page)){
            switch($request->page){
                case 'advInfo':
                    return redirect('admin/users/advInfo/'.$request->user_id);
                break;
                case 'back':
                    return back()->with('message', '成功解除' . $user->name . '的權限');
                default:
                    return view('admin.users.success')
                            ->with('email', $user->email);
                break;
            }
        }else{
            return view('admin.users.success')
               ->with('email', $user->email);
        }
        
    }

    /**
     * Toggle a specific member is blocked or not.
     *
     * @return \Illuminate\Http\Response
     */
    public function toggleUserBlock(Request $request){
        $userBanned = banned_users::where('member_id', $request->user_id)
            ->get()->first();

        //勾選加入常用列表後新增
        if($request->addreason){
            if(DB::table('reason_list')->where([['type', 'ban'],['content', $request->reason]])->first() == null){
                DB::table('reason_list')->insert(['type' => 'ban', 'content' => $request->reason]);
            }
        }
        
        //輸入新增自動封鎖關鍵字後新增
        if(!empty($request->addautoban)){
            foreach ($request->addautoban as $value) {
                if(!empty($value)){
                    if(DB::table('set_auto_ban')->where([['type', 'allcheck'],['content', $value],['set_ban', '1']])->first() == null){
                        DB::table('set_auto_ban')->insert(['type' => 'allcheck', 'content' => $value, 'set_ban' => '1', 'cuz_user_set' => $request->user_id]);
                    }
                }
            }
        }
        
        if($userBanned){
            $userBanned->delete();
            if(isset($request->page)){
                switch($request->page){
                    case 'advInfo':
                        return redirect('admin/users/advInfo/'.$request->user_id);
                    default:
                        return redirect($request->page);
                    break;
                }
            }else{
                return $this->advSearch($request, 'unban');
            }
        }
        else{
            $userBanned = new banned_users;
            $userBanned->member_id = $request->user_id;
            if($request->days != 'X'){
                $userBanned->expire_date = Carbon::now()->addDays($request->days);
            }
            if(!empty($request->msg)){
                $userBanned->reason = $request->msg;
            }
            else if(!empty($request->reason)){
                $userBanned->reason = $request->reason;
            }
            $userBanned->save();

            if(isset($request->page)){
                switch($request->page){
                    case 'advInfo':
                        return redirect('admin/users/advInfo/'.$request->user_id);
                    default:
                        return redirect($request->page);
                    break;
                }
            }else{
                return $this->advSearch($request, 'ban');
            }
        }

    }

    public function toggleUserBlock_simple($id){
        $userBanned = banned_users::where('member_id', $id)
            ->get()->first();
        if($userBanned){
            $userBanned->delete();
            return view('admin.users.success_only')->with('message', '成功解除封鎖使用者');
        }
        else{
            $userBanned = new banned_users;
            $userBanned->member_id = $id;
            $userBanned->save();
            return view('admin.users.success_only')->with('message', '成功封鎖使用者');
        }

    }

    public function toggleUserWarned(Request $request){
        $userWarned = warned_users::where('member_id', $request->user_id)
            ->get()->first();

        //勾選加入常用列表後新增
        if($request->addreason){
            if(DB::table('reason_list')->where([['type', 'warned'],['content', $request->reason]])->first() == null){
                DB::table('reason_list')->insert(['type' => 'warned', 'content' => $request->reason]);
            }
        }

        //輸入新增自動封鎖關鍵字後新增 警示
        if(!empty($request->addautoban)){
            foreach ($request->addautoban as $value) {
                if(!empty($value)){
                    if(DB::table('set_auto_ban')->where([['type', 'allcheck'],['content', $value],['set_ban', '3']])->first() == null){
                        DB::table('set_auto_ban')->insert(['type' => 'allcheck', 'content' => $value, 'set_ban' => '3', 'cuz_user_set' => $request->user_id]);
                    }
                }
            }
        }

        if($userWarned){
            $userWarned->delete();
        }
//            if(isset($request->page)){
//                switch($request->page){
//                    case 'advInfo':
//                        return redirect('admin/users/advInfo/'.$request->user_id);
//                    default:
//                        return redirect($request->page);
//                        break;
//                }
//            }else{
//                return back()->with('message', '已解除站方警示');
//            }
//        }
//        else{
            $userWarned = new warned_users;
            $userWarned->member_id = $request->user_id;
            if($request->days != 'X'){
                $userWarned->expire_date = Carbon::now()->addDays($request->days);
            }
            $userWarned->reason = $request->reason;

            if(!empty($request->reason)){
                $userWarned->reason = $request->reason;
            }
            $userWarned->save();

            if(isset($request->page)){
                switch($request->page){
                    case 'advInfo':
                        return redirect('admin/users/advInfo/'.$request->user_id);
                    default:
                        return redirect($request->page);
                        break;
                }
            }else{
                return back()->with('message', '成功加入站方警示');
            }
//        }


    }

    public function unwarnedUser(Request $request){
        $data = $request->post('data');

        $warned = warned_users::where('member_id', $data['id'])->get()->toArray();

        if(count($warned)>0){
            warned_users::where('member_id','=',$data['id'])->delete();
        }

        $data = array(
            'code'=>'200',
            'status'=>'success'
        );
        echo json_encode($data);
    }

    public function toggleRecommendedUser(Request $request){
        //給優選三個月
        if($request->Recommended == 1){
            $user = Vip::select('member_id')
                ->where('member_id', $request->user_id)
                ->update(array('updated_at' => Carbon::now()->subMonths(3)));
        }elseif($request->Recommended == 0){
            //取消優選
            if (is_numeric($request->user_id)){
                DB::select(DB::raw("update member_vip set updated_at = null where member_id = $request->user_id"));
            }
        }
        if(isset($request->page)){
            switch($request->page){
                case 'advInfo':
                    return redirect('admin/users/advInfo/'.$request->user_id);
                break;
            }
        }
    }

    public function showWarnedUserDialog(Request $request){
        $admin = $this->admin->checkAdmin();
        if($admin){
            $warnedUser = users::where('id', $request->user_id)->get()->first();
            $banReason = DB::table('reason_list')->select('content')->where('type', 'ban')->get();
            if(!$warnedUser)
                return back()->withErrors('查無使用者');
            else{
                return view('admin.users.warnedUserDialog')
                    ->with('banReason', $banReason)
                    ->with('warnedUser', $warnedUser);
            }
        }
        else{
            return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function showBanUserDialog(Request $request){
        $admin = $this->admin->checkAdmin();
        if($admin){
            $bannedUser = users::where('id', $request->user_id)->get()->first();
            $msg = Message::where('id', $request->msg_id)->get()->first();
            $banReason = DB::table('reason_list')->select('content')->where('type', 'ban')->get();
            if(!$bannedUser)
                return back()->withErrors('查無使用者');
            else{
                return view('admin.users.bannedUserDialog')
                    ->with('msg', $msg)
                    ->with('banReason', $banReason)
                    ->with('bannedUser', $bannedUser)
                    ->with('isReported', $request->isReported);
            }     
        }
        else{
            return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }


    public function banUserWithDayAndMessage(Request $request){
        //todo : banUserWithDays change way.
        $user_id = $request->user_id;
        $msg_id = $request->msg_id;
        $days = $request->days;
        $reason = $request->reason;
        $addreason = $request->addreason;
        //勾選加入常用列表後新增
        if($addreason){
            if(DB::table('reason_list')->where([['type', 'ban'],['content', $reason]])->first() == null){
                DB::table('reason_list')->insert(['type' => 'ban', 'content' => $reason]);
            }
        }
        $isReported = $request->isReported;

        $userBanned = banned_users::where('member_id', $user_id)
            ->get()->first();
        if(!$userBanned){
            $userBanned = new banned_users;
        }

        $userBanned->member_id = $user_id;
        
        if($days != 'X') {
            $userBanned->expire_date = Carbon::now()->addDays($days);
        }else{
            $userBanned->expire_date = null;
        }

        
        if($isReported){
            $message = Reported::select('reported.content', 'reported.created_at')
            ->join('users', 'reported.reported_id', '=', 'users.id')
            ->where('reported.id', $msg_id)->get()->first();
        }
        else{
            $message = Message::select('message.content', 'message.created_at', 'users.name')
            ->join('users', 'message.to_id', '=', 'users.id')
            ->where('message.id', $msg_id)->get()->first();
        }
        
        if(isset($message) && $days != 'X'){
            $userBanned->message_content = $message->content;
            $userBanned->message_time = $message->created_at;
            $userBanned->recipient_name = $message->name;
        }
        if(isset($reason)){
            $userBanned->reason = $reason;
        }
        $userBanned->save();
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

    public function userUnblock(Request $request){
        $userBanned = banned_users::where('member_id', $request->user_id)
            ->get()->first();
        if($userBanned){
            $userBanned->delete();
            return redirect()->back()->with('message', '成功解除封鎖使用者');
        }
        else{
            return redirect()->back()->withErrors(['出現錯誤，無法解除封鎖使用者']);
        }
    }

    public function advIndex()
    {
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
               ->with('keyword', isset($request->keyword) ? $request->keyword : null)
               ->with('member_type', isset($request->member_type) ? $request->member_type : null)
               ->with('time', isset($request->time) ? $request->time : null);
    }

    public function advSearchInfo(Request $request)
    {
        $users = $this->admin->advSearch($request);
        return array('users'=> $users);
    }

    /**
     * Display advance information of a member.
     *
     * @return \Illuminate\Http\Response
     */
    public function advInfo(Request $request, $id)
    {
        if (! $id) {
            return redirect(route('users/advSearch'));
        }
        $user = User::where('id', 'like', $id)
                ->get()->first();
        if(!isset($user)){
            return '<h1>會員資料已刪除。</h1>';
        }
        $userMeta = UserMeta::where('user_id', 'like', $id)
                ->get()->first();
        $userMessage = Message::where('from_id', $id)->orderBy('created_at', 'desc')->paginate(config('social.admin.showMessageCount'));
        $to_ids = array();
        foreach($userMessage as $u){
            if(!array_key_exists($u->to_id, $to_ids)){
                $to_ids[$u->to_id] = User::select('name','engroup')->where('id', $u->to_id)->get()->first();
                
                if($to_ids[$u->to_id]){
                    $to_ids[$u->to_id]['tipcount'] = Tip::TipCount_ChangeGood($u->to_id);
                    $to_ids[$u->to_id]['vip'] = Vip::vip_diamond($u->to_id);
                    $to_ids[$u->to_id]['name'] = $to_ids[$u->to_id]->name;
                    $to_ids[$u->to_id]['isBlocked'] = banned_users::where('member_id', 'like', $u->to_id)->get()->first();
                    $to_ids[$u->to_id]['engroup'] = $to_ids[$u->to_id]->engroup;
                }
                else{
                    $to_ids[$u->to_id] = array();
                    $to_ids[$u->to_id]['name'] = '查無資料或使用者資料已刪除';
                }
            }
        }

        // 給予、取消優選
        $now = \Carbon\Carbon::now();
        $vip_date = Vip::select('id', 'updated_at')->where('member_id', $user->id)->orderBy('updated_at', 'desc')->get()->first();
        if(isset($vip_date->updated_at)){
            $vip_date = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $vip_date->updated_at);
            $diff_in_months = $vip_date->diffInMonths($now);
            //未滿一個月給予優選
            $user['Recommended'] = $diff_in_months == 0? 1: 0;
        }else{
            //NULL的給予優選
            $user['Recommended'] = 1;
        }
        $isVip = $user->isVip();
        $user['auth_status'] = 0;
        if($user->isPhoneAuth() == 1) $user['auth_status'] = 1;
        $user['isvip'] = $isVip;
        $user['tipcount'] = Tip::TipCount_ChangeGood($user->id);
        $user['vip'] = Vip::vip_diamond($user->id);
        $user['isBlocked'] = banned_users::where('member_id', 'like', $user->id)->get()->first();
        if(!isset($user['isBlocked'])){
            $user['isBlocked'] = \App\Models\BannedUsersImplicitly::where('target', $user->id)->get()->first();
            if(isset($user['isBlocked'])){
                $user['isBlocked']['implicitly'] = 1;
            }
        }

//        $user['isAdminWarned'] = warned_users::where('member_id',$user->id)
//            ->where('expire_date','>=',now())
//            ->orWhere('expire_date',NULL)
//            ->get()->first();
        $data = warned_users::where('member_id', $user->id)->first();
        if(isset($data) && ($data->expire_date==null || $data->expire_date >=  Carbon::now() )){
            $user['isAdminWarned']=1;
        }else{
            $user['isAdminWarned']=0;
        }

        $banReason = DB::table('reason_list')->select('content')->where('type', 'ban')->get();
        $implicitly_banReason = DB::table('reason_list')->select('content')->where('type', 'implicitly')->get();
        $warned_banReason = DB::table('reason_list')->select('content')->where('type', 'warned')->get();
        $fingerprints = Fingerprint2::select('ip', 'fp', 'created_at')->where('user_id', $user->id)->get();

        //檢舉紀錄 reporter_id檢舉者uid  被檢舉者reported_user_id為此頁面主要會員
        $pic_report1 = ReportedAvatar::select('reporter_id as uid','reported_user_id as edid','cancel' ,'created_at', 'content')->where('reported_user_id',$user->id)->where('reporter_id','!=',$user->id)->groupBy('reporter_id')->get();
        $pic_report2 = ReportedPic::select('reported_pic.reporter_id as uid','member_pic.member_id as edid','cancel','reported_pic.created_at', 'content')->join('member_pic','reported_pic.reported_pic_id','=','member_pic.id')->where('member_pic.member_id',$user->id)->where('reported_pic.reporter_id','!=',$user->id)->groupBy('reported_pic.reporter_id')->get();
        //大頭照與照片合併計算
        $collection = collect([$pic_report1, $pic_report2]);
        $pic_all_report = $collection->collapse()->unique('uid');
        //$pic_all_report->unique()->all();

        $msg_report = Message::select('to_id','id' ,'cancel','created_at', 'content')->where('from_id',$user->id)->where('isReported',1)->distinct('to_id')->get();
        $report = Reported::select('member_id','reported_id','cancel','created_at', 'content')->where('reported_id',$user->id)->where('member_id','!=',$user->id)->groupBy('member_id')->get();

        $report_all = array();

        foreach($pic_all_report as $row){
            $f_user = User::findById($row->uid);
            $auth_status=0;
            $report_table = '';
            if($f_user->isPhoneAuth()==1){
                $auth_status=1;
            }
            array_push($report_all,
                array(
                    'reporter_id' => $row->uid,
                    'reported_id' => $row->edid,
                    'cancel' => $row->cancel,
                    'content' => $row->content,
                    'created_at' => $row->created_at,
                    'tipcount' => Tip::TipCount_ChangeGood($row->uid),
                    'vip' => Vip::vip_diamond($row->uid),
                    'isBlocked' => banned_users::where('member_id', 'like', $row->uid)->get()->first(),
                    'name' => $f_user->name,
                    'email' => $f_user->email,
                    'isvip' => $f_user->isVip(),
                    'auth_status' => $auth_status,
                    'report_type' => '照片檢舉',
                    'report_table' => 'reported_avatarpic',
                    'engroup' => $f_user->engroup
                )
            );
        }
        foreach($msg_report as $row){
            $f_user = User::findById($row->to_id);
            $auth_status=0;
            if($f_user->isPhoneAuth()==1){
                $auth_status=1;
            }
            array_push($report_all,
                array(
                    'report_dbid' => $row->id,
                    'reporter_id' => $row->to_id,
                    'cancel' => $row->cancel,
                    'content' => $row->content,
                    'created_at' => $row->created_at,
                    'tipcount' => Tip::TipCount_ChangeGood($row->to_id),
                    'vip' => Vip::vip_diamond($row->to_id),
                    'isBlocked' => banned_users::where('member_id', 'like', $row->to_id)->get()->first(),
                    'name' => $f_user->name,
                    'email' => $f_user->email,
                    'isvip' => $f_user->isVip(),
                    'auth_status' => $auth_status,
                    'report_type' => '訊息檢舉',
                    'report_table' => 'message',
                    'engroup' => $f_user->engroup
                )
            );

        }
        foreach($report as $row){
            $f_user = User::findById($row->member_id);
            $auth_status=0;
            if($f_user->isPhoneAuth()==1){
                $auth_status=1;
            }
            array_push($report_all,
                array(
                    'reported_id' => $row->reported_id,
                    'reporter_id' => $row->member_id,
                    'cancel' => $row->cancel,
                    'content' => $row->content,
                    'created_at' => $row->created_at,
                    'tipcount' => Tip::TipCount_ChangeGood($row->member_id),
                    'vip' => Vip::vip_diamond($row->member_id),
                    'isBlocked' => banned_users::where('member_id', 'like', $row->member_id)->get()->first(),
                    'name' => $f_user->name,
                    'email' => $f_user->email,
                    'isvip' => $f_user->isVip(),
                    'auth_status' => $auth_status,
                    'report_type' => '會員檢舉',
                    'report_table' => 'reported',
                    'engroup' => $f_user->engroup
                )
            );
        }

        if(str_contains(url()->current(), 'edit')){
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
                   ->with('day', $day);
        }
        else{
            return view('admin.users.advInfo')
                   ->with('userMeta', $userMeta)
                   ->with('banReason', $banReason)
                   ->with('warned_banReason', $warned_banReason)
                   ->with('implicitly_banReason', $implicitly_banReason)
                   ->with('user', $user)
                   ->with('userMessage', $userMessage)
                   ->with('to_ids', $to_ids)
                   ->with('fingerprints', $fingerprints)
                   ->with('report_all',$report_all);
        }
    }

    //advInfo頁面的切換檢舉是否取消或不計分
    public function reportedToggler(Request $request){
        //reporter_id為本頁此會員被檢舉者 reported_id為檢舉者
        switch ($request->report_table) {
            case 'reported_avatarpic':
                if($request->cancel==0){
                    ReportedPic::join('member_pic','reported_pic.reported_pic_id','=','member_pic.id')
                    ->where('member_pic.member_id',$request->reported_id)
                    ->where('reported_pic.reporter_id',$request->reporter_id)
                    ->getQuery()->update(array('reported_pic.cancel' => 1,'reported_pic.updated_at' => \Carbon\Carbon::now()));

                    ReportedAvatar::where('reporter_id', $request->reporter_id)->where('reported_user_id', $request->reported_id)->update(array('cancel' => 1));
                }elseif ($request->cancel==1) {
                    ReportedPic::join('member_pic','reported_pic.reported_pic_id','=','member_pic.id')
                    ->where('member_pic.member_id',$request->reported_id)
                    ->where('reported_pic.reporter_id',$request->reporter_id)
                    ->getQuery()->update(array('reported_pic.cancel' => 0,'reported_pic.updated_at' => \Carbon\Carbon::now()));

                    ReportedAvatar::where('reporter_id', $request->reporter_id)->where('reported_user_id', $request->reported_id)->update(array('cancel' => 0));
                }
                break;
            case 'reported':
                if($request->cancel==0){
                    Reported::where('member_id', $request->reporter_id)->where('reported_id', $request->reported_id)->update(array('cancel' => 1));
                }elseif ($request->cancel==1) {
                    Reported::where('member_id', $request->reporter_id)->where('reported_id', $request->reported_id)->update(array('cancel' => 0));
                }
                break;
            case 'message':
                if($request->cancel==0){
                    Message::where('id', $request->report_dbid)->update(array('cancel' => 1));
                }elseif ($request->cancel==1) {
                    Message::where('id', $request->report_dbid)->update(array('cancel' => 0));
                }
                break;
            default:
                break;
        }
        return back();
    }
    
    //advInfo頁面的照片修改與站長訊息發送
    public function editPic_sendMsg(Request $request, $id)
    {
        $admin = $this->admin->checkAdmin();
        if ($admin){
            $user = $this->service->find($id);
            // $msglib = Msglib::get();
            $userMeta = UserMeta::where('user_id', 'like', $id)->get()->first();
            $msglib = Msglib::selectraw('id, title, msg')->where('kind','=','smsg')->get();
            $msglib_report = Msglib::selectraw('id, title, msg')->where('kind','=','smsg')->get();
            $msglib_msg = collect();
            foreach ($msglib as $m){
                // $m->msg = str_replace('|$report|', $user->name, $m->msg);
                $m->msg = str_replace('NAME', $user->name, $m->msg);
                $m->msg = str_replace('NOW_TIME', date("Y-m-d H:i:s"), $m->msg);
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
                   ->with('msglib_msg2', collect());
        }
        else{
            return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }

    }

    public function showUserPictures()
    {
        return view('admin.users.userPictures');
    }

    public function searchUserPictures(Request $request)
    {
        $userNames = array();
        $pics = MemberPic::select('*');
        $avatars = UserMeta::select('user_id', 'pic', 'isAvatarHidden', 'updated_at')->whereNotNull('pic');
        if($request->hidden){
            $pics = $pics->where('isHidden', 1);
            $avatars = $avatars->where('isAvatarHidden', 1);
        }
        else{
            $pics = $pics->where('isHidden', 0);
            $avatars = $avatars->where('isAvatarHidden', 0);
        }
        if($request->date_start){
            $pics = $pics->where('updated_at', '>=', $request->date_start);
            $avatars = $avatars->where('updated_at', '>=', $request->date_start);
        }
        if($request->date_end){
            $pics = $pics->where('updated_at', '<=', $request->date_end.' 23:59:59');
            $avatars = $avatars->where('updated_at', '<=', $request->date_end.' 23:59:59');
        }
        if($request->en_group){
            $users = User::select('id')->where('engroup', $request->en_group)->get();
            $pics = $pics->whereIn('member_id', $users);
            $avatars = $avatars->whereIn('user_id', $users);
        }
        if($request->city){
            $users = UserMeta::select('user_id')->where('city', $request->city)->get();
            $pics = $pics->whereIn('member_id', $users);
            $avatars = $avatars->whereIn('user_id', $users);
        }
        if($request->area){
            $users = UserMeta::select('user_id')->where('area', $request->area)->get();
            $pics = $pics->whereIn('member_id', $users);
            $avatars = $avatars->whereIn('user_id', $users);
        }
        $pics = $pics->get();
        $avatars = $avatars->get();
        foreach ($pics as $pic){
            $userNames[$pic->member_id] = '';
        }
        foreach ($avatars as $avatar){
            $userNames[$avatar->user_id] = '';
        }
        foreach ($userNames as $key => $userName){
            $userNames[$key] = User::findById($key);
            $userNames[$key] = isset($userNames[$key]->name) ? $userNames[$key]->name : '會員資料已刪除';
        }
        return view('admin.users.userPictures',
            ['pics' => $pics,
            'avatars' => $avatars,
            'userNames' => $userNames,
            'en_group' => isset($request->en_group) ? $request->en_group : null,
            'city' => isset($request->city) ? $request->city : null,
            'area' => isset($request->area) ? $request->area : null,
            'hiddenSearch' => isset($request->hidden) ? true : false]);
    }

    public function modifyUserPictures(Request $request) {
        //勾選加入常用列表後新增
        $addreason = $request->addreason;
        $otherReason = $request->otherReason;
        if($addreason){
            if(DB::table('reason_list')->where([['type', 'pic'],['content', $otherReason]])->first() == null){
                DB::table('reason_list')->insert(['type' => 'pic', 'content' => $otherReason]);
            }
        }
        $msglib_delpic = Msglib::selectraw('id, title, msg')->where('kind','=','delpic')->get();

        if($request->delete){
            $datas = $this->admin->deletePicture($request);
            if($datas == null){
                return redirect()->back()->withErrors(['沒有選擇訊息。'])->withInput();
            }
            if(!$datas){
                return redirect()->back()->withErrors(['出現錯誤，訊息刪除失敗'])->withInput();
            }
            else {
                $admin = $this->admin->checkAdmin();
                if($admin){
                    return view('admin.users.messenger')
                        ->with('admin', $datas['admin'])
                        ->with('msgs', $datas['msgs'])
                        ->with('msgs2', $datas['msgs2'])
                        ->with('msglib_delpic', $msglib_delpic)
                        ->with('template', $datas['template']);
                }
                else{
                    return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
                }
            }
        }
        else if($request->hide){
            $datas = $this->admin->hidePicture($request);
            if($datas == null){
                return redirect()->back()->withErrors(['沒有選擇訊息。'])->withInput();
            }
            if(!$datas){
                return redirect()->back()->withErrors(['出現錯誤，訊息刪除失敗'])->withInput();
            }
            else {
                $admin = $this->admin->checkAdmin();
                if($admin){
                    return view('admin.users.messenger')
                        ->with('admin', $datas['admin'])
                        ->with('msgs', $datas['msgs'])
                        ->with('msgs2', $datas['msgs2'])
                        ->with('msglib_delpic', $msglib_delpic)
                        ->with('template', $datas['template']);
                }
                else{
                    return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
                }
            }
        }
        else if($request->dehide){
            $datas = $this->admin->deHidePicture($request);
            if($datas == null){
                return redirect()->back()->withErrors(['沒有選擇訊息。'])->withInput();
            }
            if(!$datas){
                return redirect()->back()->withErrors(['出現錯誤，訊息刪除失敗'])->withInput();
            }
            else {
                $admin = $this->admin->checkAdmin();
                if($admin){
                    return back();
                }
                else{
                    return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
                }
            }
        }
        else{
            return redirect()->back()->withErrors(['出現不明錯誤']);
        }
    }

    public function showReportedCountPage()
    {
        $admin = $this->admin->checkAdmin();
        if ($admin){
            return view('admin.users.reportedCount');
        }
        else{
            return view('admin.users.reportedCount')->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
        
    }
    public function showReportedCountList(Request $request)
    {
        $admin = $this->admin->checkAdmin();
        if ($admin){
            $result = $this->admin->reportedUserDetails($request);

            return view('admin.users.reportedCount')
                ->with('reportedUsers', $result['reportedUsers'])
                ->with('users', $result['users'])
                ->with('date_start', isset($request->date_start) ? $request->date_start : null)
                ->with('date_end', isset($request->date_end) ? $request->date_end : null);
        }
        else{
            return view('admin.users.reportedUsers')->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function showMessageSearchPage()
    {
        $admin = $this->admin->checkAdmin();
        if ($admin){
            return view('admin.users.searchMessage');
        }
        else{
            return view('admin.users.searchMessage')->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function showReportedMessages(Request $request){
        $admin = $this->admin->checkAdmin();
        if ($admin){

            $date_start = $request->date_start ? $request->date_start : '0000-00-00';
            $date_end = $request->date_end ? $request->date_end . ' 23:59:59': date('Y-m-d'). ' 23:59:59';

            $messages = Message::whereBetween('created_at', array($date_start, $date_end))
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
        }
        else{
            return view('admin.users.searchMessage')->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    /**
     * Search members' messages.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchMessage(Request $request) {
        if($request->time =='send_time'){
            $datas = $this->admin->searchMessageBySendTime($request);
        }
        else{
            if ( !$request->msg && !$request->date_start && !$request->date_end) {
                $results = null;
            }
            else {
                $msg = $request->msg ? $request->msg : '';
                $date_start = $request->date_start ? $request->date_start : '0000-00-00';
                $date_end = $request->date_end ? $request->date_end : date('Y-m-d');

                $results = Message::select('*')
                                        ->where('content', 'like', '%' . $msg . '%')
                                        ->whereBetween('created_at', array($date_start . ' 00:00', $date_end . ' 23:59'));
            }
            $senders = array(); //先宣告 否則報錯
            if($results != null){
                $temp = $results->get()->toArray();
                //Rearranges the messages query results.
                $results = array();
                array_walk($temp, function (&$value, &$key) use (&$results) {
                    $results[$value['id']] = $value;
                });
                //Senders' id.
                $to_id = array();
                //Receivers' id.
                $from_id = array();

                foreach ($results as $result){
                    if(!in_array($result['to_id'], $to_id)) {
                        array_push($to_id, $result['to_id']);
                    }
                    if(!in_array($result['from_id'], $from_id)) {
                        array_push($from_id, $result['from_id']);
                    }
                }
                //Senders' meta.
                foreach ($from_id as $key => $id){
                    $sender = User::where('id', '=', $id)->get()->first();
                    // $vip_tmp = $sender->isVip() ? true : false;
                    $senders[$key] = $sender->toArray();
                    $senders[$key]['vip'] = Vip::vip_diamond($id);
                    $senders[$key]['isBlocked'] = banned_users::where('member_id', 'like', $id)->get()->first();
                    $senders[$key]['tipcount'] = Tip::TipCount_ChangeGood($id);
                    //被檢舉者近一月曾被不同人檢舉次數
                    $tmp = $this->admin->reports_month($id);
                    $senders[$key]['picsResult'] = $tmp['picsResult'];
                    $senders[$key]['messagesResult'] = $tmp['messagesResult'];
                    $senders[$key]['reportsResult'] = $tmp['reportsResult'];
                }
                //Fills message ids to each sender.
                foreach ($senders as $key => $sender){

                    $senders[$key]['messages'] = array();
                    foreach ($results as $result) {
                        if($result['from_id'] == $sender['id']){
                            array_push($senders[$key]['messages'], $result);
                        }
                    }
                }
                //Receivers' name.
                $receivers = array();
                foreach ($to_id as $id){
                    $receivers[$id] = array();
                }
                foreach ($receivers as $id => $receiver){
                    $name = User::select('name','engroup')
                        ->where('id', '=', $id)
                        ->get()->first();
                    if($name != null){
                        $receivers[$id]['name'] = $name->name;
                        $receivers[$id]['tipcount'] = Tip::TipCount_ChangeGood($id);
                        $receivers[$id]['vip'] = Vip::vip_diamond($id);
                        $receivers[$id]['isBlockedReceiver'] = banned_users::where('member_id', 'like', $id)->get()->first();
                        $receivers[$id]['engroup'] = $name->engroup;
                    }
                    else{
                        $receivers[$id] = '資料庫沒有資料';
                    }
                }

                if($request->time =='created_at'){
                    $senders = collect($senders)->sortBy('created_at', true,true)->reverse()->toArray();
                }
                if($request->time =='login_time'){
                    $senders = collect($senders)->sortBy('last_login', true,true)->reverse()->toArray();
                }
                if($request->member_type =='vip'){
                    $senders = collect($senders)->sortBy('vip', true,true)->reverse()->toArray();
                }
                if($request->member_type =='banned'){
                    $senders = collect($senders)->sortBy('isBlocked')->reverse()->toArray();
                }
            }
        }
        if(isset($datas)){
            return view('admin.users.searchMessage')
                ->with('results', $datas['results'])
                ->with('users', isset($datas['users']) ? $datas['users'] : null)
                ->with('msg', isset($datas['msg']) ? $datas['msg'] : null)
                ->with('date_start', isset($datas['date_start']) ? $datas['date_start'] : null)
                ->with('date_end', isset($datas['date_end']) ? $datas['date_end'] : null)
                ->with('time', isset($request->time) ? $request->time : null)
                ->with('member_type', isset($request->member_type) ? $request->member_type : null);
        }
        else{
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
        if($request->delete == 1 && $request->edit == 0){
            $datas = $this->admin->deleteMessage($request);
            if($datas == null){
                return redirect()->back()->withErrors(['沒有選擇訊息。'])->withInput();
            }
            if(!$datas){
                return redirect()->back()->withErrors(['出現錯誤，訊息刪除失敗'])->withInput();
            }
            else {
                $admin = $this->admin->checkAdmin();
                if($admin){
                    return view('admin.users.messenger')
                        ->with('admin', $datas['admin'])
                        ->with('msgs', $datas['msgs'])
                        ->with('template', $datas['template']);
                }
                else{
                    return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
                }
            }
        }
        else if($request->edit == 1 && $request->delete == 0){
            $admin = $this->admin->checkAdmin();
            if($admin){
                $data = $this->admin->renderMessages($request);
                return view('admin.users.editMessage')->with('data', $data);
            }
            else{
                return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
            }
        }
        else{
            return redirect()->back()->withErrors(['出現不明錯誤']);
        }
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
        }
        else{
            return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function showBannedList()
    {
        $list = banned_users::join('users', 'users.id', '=', 'banned_users.member_id')
                ->select('banned_users.*', 'users.name', 'users.email','banned_users.reason')->orderBy('created_at', 'desc')->get();
        return view('admin.users.bannedList')->with('list', $list);
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
        if ($admin){
            $user = $this->service->find($id);
            $msglib = Msglib::get();
            $msglib_msg = collect();
            foreach ($msglib as $m){
                $m->msg = str_replace('|$report|', $user->name, $m->msg);
                $m->msg = str_replace('NAME', $user->name, $m->msg);
                $m->msg = str_replace('|$reported|', "|被檢舉者|", $m->msg);
                $msglib_msg->push($m->msg);
            }
            return view('admin.users.messenger')
                   ->with('admin', $admin)
                   ->with('user', $user)
                   ->with('from_user', $user)
                   ->with('to_user', $admin)
                   ->with('msglib', $msglib)
                   ->with('msglib2', collect())
                   ->with('msglib_report', null)
                   ->with('msglib_reported', null)
                   ->with('msglib_msg', $msglib_msg)
                   ->with('message_msg', collect())
                   ->with('msglib_msg2', collect());
        }
        else{
            return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function showAdminMessengerWithMessageId($id, $mid) {
        $admin = $this->admin->checkAdmin();
        if ($admin){
            $msglib = Msglib::get();
            $msglib_report = Msglib::selectraw('id, title, msg')->where('kind','=','report')->get();
            $msglib_reported = Msglib::selectraw('id, title, msg')->where('kind','=','reported')->get();
            $msglib_all = Msglib::selectraw('id, title, msg')->get();
            $msglib = Msglib::get();
            $msglib2 = Msglib::get();
            $msglib3 = Msglib::selectraw('msg')->get();
            /*檢舉者 */
            $user = $this->service->find($id);
            $message = Message::where('id', $mid)->get()->first();
            $sender = User::where('id', $message->from_id)->get()->first();
            /*被檢舉者*/
            $to_user_id = Message::where('id', $mid)->get()->first()->to_id;
            $to_user    = $this->service->find($to_user_id);
            $message_msg = Message::where('to_id', $to_user->id)->where('from_id',$user->id)->get();   
            if(!$msglib_report->isEmpty()){
                foreach($msglib_report as $key=>$msg){
                    $msglib_msg[$key] = str_replace('|$report|', $user->name, $msg['msg']);
                    $msglib_msg[$key] = str_replace('|$reported|', $sender->name, $msglib_msg[$key]);
                    $msglib_msg[$key] = str_replace('|$reportTime|', isset($message_msg[0]) ? $message_msg[0]->created_at : null, $msglib_msg[$key]);
                    $msglib_msg[$key] = str_replace('|$responseTime|', date("Y-m-d H:i:s"), $msglib_msg[$key]);
                }
            }
            else{
                foreach($msglib_all as $key=>$msg){
                    $msglib_msg[$key] = $msg['msg'];
                }
            }
            if(!$msglib_reported->isEmpty()){
                foreach($msglib_reported as $key=>$msg){
                    $msglib_msg2[$key] = str_replace('|$report|', $user->name, $msg['msg']);
                    $msglib_msg2[$key] = str_replace('|$reported|',$sender->name, $msglib_msg2[$key]);
                    $msglib_msg2[$key] = str_replace('|$reportTime|', isset($message_msg[0]) ? $message_msg[0]->created_at : null, $msglib_msg2[$key]);
                    $msglib_msg2[$key] = str_replace('|$responseTime|', date("Y-m-d H:i:s"), $msglib_msg2[$key]);
                }
            }
            else{
                foreach($msglib_all as $key=>$msg){
                    $msglib_msg2[$key] = $msg['msg'];
                }
            }

            return view('admin.users.messenger')
                ->with('admin', $admin)
                ->with('user', $user)
                ->with('to_user', $to_user)
                ->with('from_user', $sender)
                ->with('message', $message)
                ->with('senderName', $sender->name)
                ->with('msglib', $msglib_report)
                ->with('msglib2', $msglib_reported)
                ->with('msglib_report', $msglib_report)
                ->with('msglib_reported', $msglib_reported)
                ->with('msglib_msg', $msglib_msg)
                ->with('message_msg', $message_msg)
                ->with('msglib_msg2', $msglib_msg2);

        }
        else{
            return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function showAdminMessengerWithReportedId($id, $reported_id, $pic_id = null, $isPic = null, $isReported= null) {
        // $isPic 為被檢舉之表格 ID
        $admin = $this->admin->checkAdmin();
        if ($admin){
            $msglib = Msglib::get();
            $msglib3 = Msglib::selectraw('msg')->get();
            $msglib_report = Msglib::selectraw('id, title, msg')->where('kind','=','report')->get();
            $msglib_reported = Msglib::selectraw('id, title, msg')->where('kind','=','reported')->get();
            $report = Reported::where('member_id', $id)->where('reported_id', $reported_id)->get()->first();
            if(isset($isPic)){
                $a = ReportedAvatar::where('reporter_id', $id)->where('reported_user_id', $reported_id)->get()->first();
                $b = ReportedPic::where('reporter_id', $id)->get()->first();
                if(isset($a) && isset($b)){
                    $report = $a->id == $isPic ? $a : $b;
                }
                else{
                    $report = isset($a) ? $a : $b;
                }
            }
            /*檢舉者*/
            $user = $this->service->find($id);
            /*被檢舉者 */
            $reported = User::where('id', $reported_id)->get()->first();
            foreach($msglib_report as $key => $msg){
                $msglib_msg[$key] = str_replace('|$report|', $user->name, $msg['msg']);
                if($reported){
                    $msglib_msg[$key] = str_replace('|$reported|', $reported->name, $msglib_msg[$key]);
                }
                $msglib_msg[$key] = str_replace('|$reportTime|', isset($report->created_at) ? $report->created_at : null, $msglib_msg[$key]);
                $msglib_msg[$key] = str_replace('|$responseTime|',date("Y-m-d H:i:s"), $msglib_msg[$key]);
            }
            foreach($msglib_reported as $key => $msg){
                $msglib_msg2[$key] = str_replace('|$report|', $user->name, $msg['msg']);
                if($reported){
                    $msglib_msg2[$key] = str_replace('|$reported|', $reported->name, $msglib_msg2[$key]);
                }
                $msglib_msg2[$key] = str_replace('|$reportTime|', isset($report->created_at) ? $report->created_at : null, $msglib_msg2[$key]);
                $msglib_msg2[$key] = str_replace('|$responseTime|',date("Y-m-d H:i:s"), $msglib_msg2[$key]);
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
                ->with('msglib', $msglib_report)
                ->with('msglib2', $msglib_reported)
                ->with('msglib_report', $msglib_report)
                ->with('msglib_reported', $msglib_reported)
                ->with('msglib_msg', isset($msglib_msg) ? $msglib_msg : null)
                ->with('msglib_msg2', isset($msglib_msg2) ? $msglib_msg2 : null);
        }
        else{
            return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    /**
     * Message to a member.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendAdminMessage(Request $request, $id)
    {
        $payload = $request->all();
        Message::post($payload['admin_id'], $id, $payload['msg']);
        if($request->rollback == 1){
            if($request->msg_id){
                $m = Message::where('id', $request->msg_id)->get()->first();
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
        foreach ($request->msg as $msg){
            array_push($msgs, $msg);
        }
        foreach ($request->to as $id){
            array_push($to_ids, $id);
        }
        //try{
            foreach ($msgs as $key => $msg) {
                Message::post($admin_id, $to_ids[$key], $msg);
            }
        //}


        return redirect()->route('users/message/search')->with('message', '傳送成功');
    }

    public function showMessagesBetween($id1, $id2)
    {
        $messages = Message::allToFromSender($id1, $id2);
        $id1 = User::where('id', $id1)->get()->first();
        $id2 = User::where('id', $id2)->get()->first();

        $id1->tipcount = Tip::TipCount_ChangeGood($id1->id);
        $id2->tipcount = Tip::TipCount_ChangeGood($id2->id);

        $id1->vip = Vip::vip_diamond($id1->id);
        $id2->vip = Vip::vip_diamond($id2->id);

        $id1->isBlocked = banned_users::where('member_id', 'like', $id1->id)->get()->first();
        $id1->isBlockedReceiver = banned_users::where('member_id', 'like', $id1->id)->get()->first();

        $id2->isBlocked = banned_users::where('member_id', 'like', $id2->id)->get()->first();
        $id2->isBlockedReceiver = banned_users::where('member_id', 'like', $id2->id)->get()->first();


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
         if($request->email){
             $user = $this->service->findByEmail($request->email);
         }
         if($request->name){
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
     * @param  int  $id
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveAdvInfo(Request $request, $id)
    {
        //$result = $this->service->update($id, $request->except(['_token', '_method']));
        $result = $this->service->update($id, $request->all());
        if ($result) {
            return back()->with('message', '成功更新會員資料');
        }

        return back()->withErrors(['無法更新會員資料']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
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

    public function showAdminCommonText()
    {
        $a = AdminCommonText::orderBy('id', 'asc')->where('status', 1)->get()->all();
        return view('admin.admincommontext')->with('commontext', $a);
    }

    public function saveAdminCommonText(Request $request)
    {
        if( AdminCommonText::checkContent2($request->id, $request->content2) AND AdminCommonText::checkContent2($request->id, $request->content) ){
            return back()->withErrors(['請修改後再送出']);
        }elseif(AdminCommonText::checkContent2($request->id, $request->content2)){
            $a = AdminCommonText::select('*')->where('id', '=', $request->id)->first();
            $a->content = $request->content;
            $a->save();
            return back()->with('message', '成功修改');
        }elseif (AdminCommonText::checkContent2($request->id, $request->content)){
            $a = AdminCommonText::select('*')->where('id', '=', $request->id)->first();
            $a->content = $request->content2;
            $a->save();
            return back()->with('message', '成功修改');
        }
    }

    public function showReadAnnouncementUser($id)
    {
        $a = AdminAnnounce::where('id', $id)->get()->first();
        $results = \App\Models\AnnouncementRead::where('announcement_id', $id)->get();
        foreach ($results as &$result){
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
        if(AdminAnnounce::editAnnouncement($request)) {
            return redirect('admin/announcement')
                   ->with('message', '成功修改站長公告');
        }
        else{
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
        if(AdminAnnounce::saveAnnouncement($request)){
            return back()->with('message', '成功修改站長公告');
        }
        else{
            return back()->withErrors(['出現不明錯誤，無法新增站長公告']);
        }
    }

    public function newAdminAnnouncement(Request $request)
    {
        if(AdminAnnounce::newAnnouncement($request)) {
            return redirect('admin/announcement')
                ->with('message', '成功新增站長公告');
        }
        else{
            return redirect('admin/announcement')
                ->withErrors(['出現不明錯誤，無法新增站長公告']);
        }
    }

    public function deleteAdminAnnouncement(Request $request)
    {
        if(AdminAnnounce::deleteAnnouncement($request)) {
            return redirect('admin/announcement')
                ->with('message', '成功刪除站長公告');
        }
        else{
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
        $start= date('Y-m-01',strtotime($time->subDay(30)));
        $end= date('Y-m-t',strtotime($time));
        $userBanned = banned_users::select('users.name','banned_users.*')
                    ->whereBetween('banned_users.created_at',[($start),($end)])
                    ->join('users','banned_users.member_id','=','users.id')
                    ->orderBy('banned_users.created_at','asc')->get();
        $isVip = array();
        foreach($userBanned as $user){
            $isVip[$user->member_id] = Vip::select('member_id')->where('member_id', $user->member_id)->get()->first();
        }
        
        return view('admin.adminannouncement_web')
                ->with('users',$userBanned)
                ->with('isVip',$isVip);
    }

    public function showReportedUsersPage(){
        $admin = $this->admin->checkAdmin();
        if ($admin){
            return view('admin.users.reportedUsers');
        }
        else{
            return view('admin.users.reportedUsers')->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function showReportedUsersList(Request $request){
        $admin = $this->admin->checkAdmin();
        if ($admin){
            $users = Reported::select('*');
            if($request->date_start){
                $users = $users->where('created_at', '>', $request->date_start . ' 00:00');
            }
            if($request->date_end){
                $users = $users->where('created_at', '<', $request->date_end . ' 23:59');
            }
            $users = $users->orderBy('created_at', 'desc');
            $datas = $this->admin->fillReportedDatas($users);
            return view('admin.users.reportedUsers')
                ->with('results', $datas['results'])
                ->with('users', isset($datas['users']) ? $datas['users'] : null)
                ->with('reported_id', isset($request->reported_id) ? $request->reported_id : null)
                ->with('date_start', isset($request->date_start) ? $request->date_start : null)
                ->with('date_end', isset($request->date_end) ? $request->date_end : null);
        }
        else{
            
            return view('admin.users.reportedUsers')->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function showReportedPicsPage(){
        $admin = $this->admin->checkAdmin();
        if ($admin){
            return view('admin.users.reportedPics');
        }
        else{
            return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }
    public function searchReportedPics(Request $request){
        $admin = $this->admin->checkAdmin();
        if ($admin){

            $date_start = $request->date_start ? $request->date_start : '0000-00-00';
            $date_end = $request->date_end ? $request->date_end. ' 23:59:59' : date('Y-m-d'). ' 23:59:59';

            $avatars = ReportedAvatar::whereBetween('created_at', array($date_start, $date_end))
                                        ->orderBy('created_at', 'desc')->get();
            $pics = ReportedPic::whereBetween('created_at', array($date_start, $date_end))
                                ->orderBy('created_at', 'desc')->get();
            
            $avatarDatas = $this->admin->fillReportedAvatarDatas($avatars);
            $picDatas = $this->admin->fillReportedPicDatas($pics);

            $picReason = DB::table('reason_list')->select('content')->where('type', 'pic')->get();

            return view('admin.users.reportedPics')
                ->with('picReason', $picReason)
                ->with('results', $avatarDatas['results'] ? $avatarDatas['results'] : 1)
                ->with('users', isset($avatarDatas['users']) ? $avatarDatas['users'] : null)
                ->with('Presults', $picDatas['results'] ? $picDatas['results'] :null)
                ->with('Pusers', isset($picDatas['users']) ? $picDatas['users'] : null)
                ->with('reported_id', isset($request->reported_id) ? $request->reported_id : null)
                ->with('date_start', isset($request->date_start) ? $request->date_start : null)
                ->with('date_end', isset($request->date_end) ? $request->date_end : null);
        }
        else{
            return view('admin.users.reportedPics')->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function showReportedDetails(Request $request){

        if($this->admin->checkAdmin()){
            $result = $this->admin->reportedUserDetails($request);

            if($result)
                return view('admin.users.reportedUserDetails')
                        ->with('reported_id', $request->reported_id)
                        ->with('reportedUser', $result['reportedUsers'])
                        ->with('users', $result['users']);
            else
                return back()->withErrors(['無檢舉資料']);            
        }    
    }

    public function customizeMigrationFiles(Request $request){
        $file = null;
        if(file_exists(storage_path('app/RP_761404_'.\Carbon\Carbon::today()->format('Ymd').'.dat'))){
            $file = \File::get(storage_path('app/RP_761404_'.\Carbon\Carbon::today()->format('Ymd').'.dat'));
        }
        $date = \Carbon\Carbon::now()->addDay()->day >= 28 ? '01' : \Carbon\Carbon::now()->addDay()->day;

        if ($request->isMethod('get'))
        {
            return view('admin.users.customizeMigrationFiles',
                ['file' => $file == null ? $file : nl2br($file),
                 'date' => $date]);
        }
        elseif($request->isMethod('post')){
            $logging = new \App\Services\VipLogService;
            if($logging->customLogToFile($request->user_id, $request->order_id, $request->day, $request->action)){
                return back()->with('message', '異動檔修改成功，請在頁面下方查看結果。');
            }
            else{
                return back()->withErrors(['發生不明錯誤(Error002).']);
            }
        }
    }

    public function changePassword(Request $request){
        if ($request->isMethod('get')) {
            return view('admin.users.changePassword');
        }
        elseif($request->isMethod('post')){
            $user = User::findByEmail($request->email);
            if(!isset($user)){
                return view('admin.users.changePassword')->withErrors(['找不到會員，請檢查輸入的Email是否正確']);
            }
            else{
                $password = $request->password == null ? '123456' : $request->password;
                $user->password = bcrypt($password);
                $user->save();
                return back()->with('message', '會員 '.$user->name.' 的密碼已設為:'.$password);
            }
        }
        return view('admin.users.changePassword')->withErrors(['發生不明錯誤(Error001)']);
    }

    public function inactiveUsers(Request $request)
    {
        $users = User::join('user_meta', 'users.id', 'user_meta.user_id')
            ->where('user_meta.is_active', 0);
        if($request->email != null){
            $users = $users->where('users.email', $request->email);
        }
        $users = $users->orderBy('users.created_at', 'desc')->paginate(20);
        return view('admin.users.inactiveUsers',[
            'users' => $users
        ]);
    }

    public function activateUser($token)
    {
        $user = UserMeta::where('activation_token', $token)->first();
        if ($user) {
            $user->update([
                'is_active' => true,
                'activation_token' => null
            ]);
            return back()->with('message', '啟動成功。');
        }

        return back()->withErrors(['啟動失敗。']);
    }

    public function deleteBoard($id){
        $message = Board::where('id', $id)->get()->first();
        if($message->delete()){
            return back()->with('message', '成功刪除留言！');
        }
        else{
            return back()->withErrors(['發生不明錯誤，刪除留言失敗！']);
        }
    }

    public function manualSQL(){
        return "<a href='".route('querier')."'>執行手動SQL</a>";
    }
    public function querier(){
        //INSERT INTO `message`(`created_at`, `to_id`, `from_id`, `content`, `all_delete_count`, `is_row_delete_1`, `is_row_delete_2`, `is_single_delete_1`, `is_single_delete_2`, `temp_id`, `reportContent`) SELECT STR_TO_DATE('2019-08-12 14:00:00','%Y-%m-%d %H:%i:%s'), `from_id`, 1049, '您好，網站目前正在對 VIP 進行調查，請問「小杉大叔」有否在確認包養關係之前跟您索取清涼照？', 0, 0, 0, 0, 0, 0, null FROM `message` WHERE `to_id` = 25889 AND `created_at` > '2019-06-31 23:59:59' GROUP BY `from_id`

        //SELECT `from_id` FROM `message` WHERE `to_id` = 25889 AND `created_at` > '2019-06-31 23:59:59' GROUP BY `from_id`
        $user_ids = Message::select('from_id')->where('to_id', 25889)->where('created_at', '>', '2019-06-31 23:59:59')->groupBy('from_id')->get();
        foreach($user_ids as $id){
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
            'id'=>$id,
            'msg'=>$msg,
        );
        echo json_encode($formdata, JSON_UNESCAPED_UNICODE);
    }

    public function addMessageLibPage(Request $request, $id=0)
    {
        if($id!=0){
            $msglib = MsgLib::where('id', $id)->first();
            
            $data = array(
                'page_title'=> '編輯訊息',
                'msg_id'=>$msglib->id,
                'title'=>$msglib->title,
                'msg'=>$msglib->msg,
                'isEdit'=>1,
            );
        }else{
            $data = array(
                'page_title'=> '新增訊息',
            );
        }
        return view('admin.users.messenger_create', $data);
    }

    public function addMessageLibPageReporter(Request $request, $id=0)
    {
        if( $id != 0){
            $msglib = MsgLib::where('id', $id)->first();
            $data = array(
                'page_title'=> '編輯訊息',
                'msg_id'=>$msglib->id,
                'title'=>$msglib->title,
                'msg'=>$msglib->msg,
                'isEdit'=>1,
            );
        }else{
            $data = array(
                'page_title'=> '新增訊息',
            );
        }
        return view('admin.users.messenger_create', $data);
    }

    public function addMessageLibPageReported(Request $request, $id=0)
    {
        if( $id != 0){
            $msglib = MsgLib::where('id', $id)->first();
            $data = array(
                'page_title'=> '編輯訊息',
                'msg_id'=>$msglib->id,
                'title'=>$msglib->title,
                'msg'=>$msglib->msg,
                'isEdit'=>1,
            );
        }else{
            $data = array(
                'page_title'=> '新增訊息',
            );
        }
        return view('admin.users.messenger_create', $data);
    }

    public function addMessageLib(Request $request)
    {
        $msg_id = $request->post('msg_id');
        if($msg_id!=''){
            $kind  = $request->post('kind');
            $title = $request->post('title');
            $msg =   $request->post('content');
            $data = array(
                'msg_id'=>$msg_id,
                'title'=>$title,
                'msg'=>$msg,
            );
            DB::update('update msglib set title=?, msg=?, kind=? where id=?',[$title, $msg, $kind, $msg_id]);
            return json_encode($data);
        }else{
            $kind  = $request->post('kind');
            $title = $request->post('title');
            $msg = $request->post('content');
            $data = array(
                'title'=>$title,
                'msg'=>$msg,
            );
            DB::insert('insert into msglib (title, msg, kind) values ( ?, ? , ? )',
            [$title,$msg,$kind]);
            return json_encode($data);
        }
    }

    public function delMessageLib(Request $request)
    {
        $id = $request->post('id');

        DB::table('msglib')->where('id', '=', $id)->delete();
        $data = array(
            'status'=>'success',
        );
        return json_encode($data);
    }

    public function blockUser(Request $request)
    {
        $data = $request->post('data');
        $ban = banned_users::where('member_id', $data['id'])->get()->toArray();
        // dd($ban);
        if(empty($ban)){
            DB::table('banned_users')->insert(['member_id'=>$data['id'],'reason'=>'管理者刪除']);
        }

        $data = array(
            'code'=>'200',
            'status'=>'success'
        );
        echo json_encode($data);
    }

    public function unblockUser(Request $request){
        $data = $request->post('data');
        // dd($data);
        $ban = banned_users::where('member_id', $data['id'])->get()->toArray();
        $banImplicitly = \App\Models\BannedUsersImplicitly::where('target', $data['id'])->get();
        // dd($ban);
        if(count($ban)>0){
           DB::table('banned_users')->where('member_id','=',$data['id'])->delete();
        }
        if($banImplicitly->count() > 0){
            \App\Models\BannedUsersImplicitly::where('target', $data['id'])->delete();
        }

        $data = array(
            'code'=>'200',
            'status'=>'success'
        );
        echo json_encode($data);
    }

    public function isWarnedUser(Request $request){

        $id = $request->post('id');
        $status = $request->post('status');

        DB::table('user_meta')->where('user_id',$id)->update(['isWarned'=>$status]);

        if($status==1){
            //加入警示流程
            //清除認證資料
//            DB::table('auth_img')->where('user_id',$id)->delete();
            DB::table('short_message')->where('member_id',$id)->delete();
        }else if($status==0) {
            $user = User::findById($id);
            //取消警示流程
            //加入認證資料 假資料
            if($user->WarnedScore()>=10) {

                if ($user->isPhoneAuth() == 0) {
                    DB::table('short_message')->insert(
                        ['member_id' => $id, 'active' => 1]);
                }

//                if ($user->isImgAuth() == 0) {
//                    DB::table('auth_img')->insert(
//                        ['user_id' => $id, 'status' => 1, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()]);
//                }
            }

        }
        $data = array(
            'code'=>'200',
            'status'=>'success'
        );
        echo json_encode($data);
    }

    public function basicSetting(Request $request){
        $data['basic_setting'] = BasicSetting::get()->first();

        return view('user.basic_setting', $data);
    }

    public function doBasicSetting(Request $request){
        $vipLevel = $request->post('vipLevel');
        $gender   = $request->post('gender');
        $timeSet  = $request->post('timeSet');
        $countSet = $request->post('countSet');
        BasicSetting::select('vipLevel', 'gender', 'timeSet', 'countSet')
        ->where('vipLevel', $vipLevel)->where('gender', $gender)
        ->update(array('timeSet' => $timeSet,'countSet' => $countSet));
        return redirect()->route('users/basic_setting');
    }


    public function showMasterwords(Request $request){
        $a = MasterWords::orderBy('sequence', 'asc')->orderBy('updated_at', 'desc')->get()->all();
        return view('admin.adminmasterwords')->with('masterwords', $a);
    }

    public function showNewAdminMasterWords()
    {
        return view('admin.adminmasterwords_new');
    }

    public function newAdminMasterWords(Request $request)
    {
        if(MasterWords::newMasterWords($request)) {
            return redirect('admin/masterwords')
                ->with('message', '成功新增站長的話');
        }
        else{
            return redirect('admin/masterwords')
                ->withErrors(['出現不明錯誤，無法新增站長的話']);
        }
    }

    public function deleteAdminMasterWords(Request $request)
    {
        if(MasterWords::deleteMasterWords($request)) {
            return redirect('admin/masterwords')
                ->with('message', '成功刪除站長的話');
        }
        else{
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
        if(MasterWords::saveMasterWords($request)){
            return back()->with('message', '成功修改站長公告');
        }else{
            return back()->withErrors(['出現不明錯誤，無法新增站長公告']);
        }
    }

    public function showReadMasterWords($id)
    {
        $a = MasterWords::where('id', $id)->get()->first();
        // dd($a);
        $results = \App\Models\MasterWordsRead::where('announcement_id', $id)->get();
        // dd($results);
        foreach ($results as &$result){
            $user = users::where('id', $result->user_id)->get()->first();
            $result->name = $user->name;
        }
        // dd('1');
        return view('admin.adminmasterwords_read')
            ->with('announce', $a)
            ->with('results', $results);
    }
    public function showSuspectedMultiLogin(){
        $result = \DB::table('suspected_multi_login')
            ->select('users.email', 'users.last_login', 'users.name', 'suspected_multi_login.*')
            ->join('users', 'users.id', '=', 'suspected_multi_login.user_id')
            ->orderBy('created_at', 'desc')->paginate(20);
        foreach ($result as &$r){
            $r->count = Message::where('from_id', $r->user_id)->where('created_at', '>=', \Carbon\Carbon::now()->subDays(3))->count();
        }

        foreach ($result as &$r){
            $users = explode(", ", $r->target);
            foreach ($users as &$u){
                $u = User::findById($u);
            }
            $r->target = $users;
        }
        return view('admin.users.suspectedMultiLoginList')->with('users', $result);
    }

    public function showImplicitlyBannedUsers(Request $request){
        set_time_limit(300);
        ini_set("memory_limit","2048M");
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
        if($orderBy == 'type1, type2'){
            $resultMerged = $result->union($result2)
                ->union($result3)
                ->orderBy('type1', $order)
                ->orderBy('type2', $order)
                ->get();
        }
        else{
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

    public function banningUserImplicitly(Request $request){

        //勾選加入常用列表後新增
        if($request->addreason){
            if(DB::table('reason_list')->where([['type', 'implicitly'],['content', $request->reason]])->first() == null){
                DB::table('reason_list')->insert(['type' => 'implicitly', 'content' => $request->reason]);
            }
        }

        //輸入新增自動封鎖關鍵字後新增 隱性封鎖
        if(!empty($request->addautoban)){
            foreach ($request->addautoban as $value) {
                if(!empty($value)){
                    if(DB::table('set_auto_ban')->where([['type', 'allcheck'],['content', $value],['set_ban', '2']])->first() == null){
                        DB::table('set_auto_ban')->insert(['type' => 'allcheck', 'content' => $value, 'set_ban' => '2', 'cuz_user_set' => $request->user_id]);
                    }
                }
            }
        }

        BannedUsersImplicitly::insert(
            ['fp' => $request->fp,
            'user_id' => 0,
            'reason' => $request->reason,
            'target' => $request->user_id]
        );
        ExpectedBanningUsers::where('target', $request->user_id)->delete();

        if(isset($request->page)) {
            switch ($request->page) {
                default:
                    return redirect($request->page);
                    break;
            }
        }
        return '<script>window.close();</script>';
    }

    public function banningFingnerprint(Request $request){
        \DB::table('banned_fingerprints')->insert(
            ['fp' => $request->fp,
             'created_at' => \Carbon\Carbon::now()]
        );

        return back()->with('message', '成功封鎖此指紋');
    }

    public function deleteFingerprintFromExpectedList($fingerprint){
        ExpectedBanningUsers::where('fp', $fingerprint)->delete();

        return back()->with('message', '成功將此指紋從預計封鎖清單中移除');
    }

    public function unbanningFingnerprint(Request $request){
        \DB::table('banned_fingerprints')->where('fp', $request->fp)->delete();

        return back()->with('message', '成功解除封鎖此指紋');
    }

    public function unbanAll(Request $request){
        $implicitly = BannedUsersImplicitly::where('target', $request->user_id)->first();
        $banned = banned_users::where('member_id', $request->user_id)->first();
        if($implicitly){ $implicitly->delete(); }
        if($banned){ $banned->delete(); }

        if(isset($request->page)) {
            switch ($request->page) {
                default:
                    return redirect($request->page);
                    break;
            }
        }
        return '<script>window.close();</script>';
    }

    public function showFingerprint($fingerprint, Request $request){
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
        if($orderBy == 'type1, type2'){
            $resultMerged = $result->union($result2)
                ->union($result3)
                ->orderBy('type1', $order)
                ->orderBy('type2', $order)
                ->get();
        }
        else{
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

    public function showWarningUsers(){
        $result = \DB::table('warning_users')
            ->select('users.email', 'users.last_login', 'users.name', 'warning_users.*')
            ->join('users', 'users.id', '=', 'warning_users.user_id')
            ->orderBy('created_at', 'desc')->paginate(20);
        foreach ($result as &$r){
            $r->count = Message::where('from_id', $r->user_id)->where('created_at', '>=', \Carbon\Carbon::now()->subDays(3))->count();
        }

        foreach ($result as &$r){
            $r->target = User::findById($r->target);
        }
        return view('admin.users.warningList')->with('users', $result);

    }

    public function statisticsReply(Request $request){
        if($request->isMethod('GET')){
            return view('admin.users.statisticsReply');
        }
        else{
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

            $tipMessage = $tipMessage['totalInvitation'] != 0 ? $tipMessage['replied']/$tipMessage['totalInvitation'] : 0;
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

            $normal = $repliedNormal != 0 ? $normal/$repliedNormal : 0;
            $vip = $repliedVip != 0 ? $normal/$repliedVip : 0;
            $recommend = $repliedRecommend != 0 ? $normal/$repliedRecommend : 0;
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

            $TaipeiAndVip = $TaipeiAndVip['users'] != 0 ? $TaipeiAndVip['messages']/$TaipeiAndVip['users'] : 0;
            $TaipeiAndNotVip = $TaipeiAndNotVip['users'] != 0 ? $TaipeiAndNotVip['messages']/$TaipeiAndNotVip['users'] : 0;
            $Vip = $Vip['users'] != 0 ? $Vip['messages']/$Vip['users'] : 0;
            $NotVip = $NotVip['users'] != 0 ? $NotVip['messages']/$NotVip['users'] : 0;
            
            $percentage['TaipeiAndVip'] = round($TaipeiAndVip, $place);
            $percentage['TaipeiAndNotVip'] = round($TaipeiAndNotVip, $place);
            $percentage['Vip'] = round($Vip, $place);
            $percentage['NotVip'] = round($NotVip, $place);

            return view('admin.users.statisticsReply')
                ->with('count', $count)
                ->with('percentage', $percentage);
        }
    }


    public function statisticsReply2(Request $request){
    
    
    }

    public function getBirthday(){
        $users = UserMeta::select('id', 'user_id', 'birthdate_new')->get();
        $count = 0;
        foreach ($users as $user){
            if($user->birthdate_new == null){
                continue;
            }
            if (\DateTime::createFromFormat('Y-m-d H:i:s', $user->birthdate_new) !== false) {
                continue;
            }
            else if (\DateTime::createFromFormat('Y-m-d', $user->birthdate_new) !== false) {
                continue;
            }
            else{
                $user->birthdate_new = null;
                $user->save();
                $count++;
            }
        }
        echo $count;
    }
}
