<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Models\Board;
use App\Models\MemberPic;
use App\Models\Message;
use App\Models\Reported;
use App\Models\ReportedAvatar;
use App\Models\ReportedPic;
use App\Models\SimpleTables\users;
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
use App\Models\Msglib;
use App\Models\SimpleTables\member_vip;
use App\Models\SimpleTables\banned_users;
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
        return view('admin.users.success')
               ->with('email', $user->email);
    }

    /**
     * Toggle the gender of a specific member.
     *
     * @return \Illuminate\Http\Response
     */
    public function toggleVIP(Request $request){
       
        if($request->isVip == 1){
            $setVip = 0;
        }
        else{
            $setVip = 1;
        }
        $user = Vip::select('member_id', 'active')
                ->where('member_id', $request->user_id)
                ->where('active', $request->isVip)
                ->update(array('active' => $setVip));
        if($user == 0){
            $vip_user = new Vip;
            $vip_user->member_id = $request->user_id;
            $vip_user->active = $setVip;
            $vip_user->created_at =  Carbon::now()->toDateTimeString();
            $vip_user->save();
        }
        VipLog::addToLog($request->user_id, $setVip == 0 ? 'manual_cancel' : 'manual_upgrade', 'Manual Setting', $setVip, 1);
        $user = User::select('id', 'email')
                ->where('id', $request->user_id)
                ->get()->first();
        if(isset($request->page)){
            switch($request->page){
                case 'advInfo':
                    return redirect('admin/users/advInfo/'.$request->user_id);
                break;
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
        if($userBanned){
            $userBanned->delete();
            if(isset($request->page)){
                switch($request->page){
                    case 'advInfo':
                        return redirect('admin/users/advInfo/'.$request->user_id);
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
            $userBanned->save();

            if(isset($request->page)){
                switch($request->page){
                    case 'advInfo':
                        return redirect('admin/users/advInfo/'.$request->user_id);
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
    public function showBanUserDialog(Request $request){
        $admin = $this->admin->checkAdmin();
        if($admin){
            $bannedUser = users::where('id', $request->user_id)->get()->first();
            $msg = Message::where('id', $request->msg_id)->get()->first();
            if(!$bannedUser)
                return back()->withErrors('查無使用者');
            else{
                return view('admin.users.bannedUserDialog')
                    ->with('msg', $msg)
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
        $userMeta = UserMeta::where('user_id', 'like', $id)
                ->get()->first();
        $userMessage = Message::where('from_id', $id)->orderBy('created_at', 'desc')->paginate(config('social.admin.showMessageCount'));
        $to_ids = array();
        foreach($userMessage as $u){
            if(!array_key_exists($u->to_id, $to_ids)){
                $to_ids[$u->to_id] = User::select('name')->where('id', $u->to_id)->get()->first();
                
                if($to_ids[$u->to_id]){
                    $to_ids[$u->to_id]['name'] = $to_ids[$u->to_id]->name;
                }
                else{
                    $to_ids[$u->to_id] = '查無資料';
                }
            }
        }
        foreach($to_ids as $key => $to_id){
            $to_ids[$key]['vip'] =  Vip::select('active')->where('member_id', $key)->where('active', 1)->orderBy('created_at', 'desc')->first() !== null;
        }
        $isVip = $user->isVip();
        $user['vip'] = $isVip;

        $user['isBlocked'] = banned_users::where('member_id', 'like', $user->id)->get()->first() == true  ? true : false;

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
                   ->with('user', $user)
                   ->with('userMessage', $userMessage)
                   ->with('to_ids', $to_ids);
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
        if($request->days){
            $pics = $pics->where('updated_at', '>', Carbon::now()->subDays($request->days));
            $avatars = $avatars->where('updated_at', '>', Carbon::now()->subDays($request->days));
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
            $userNames[$key] = $userNames[$key]->name;
        }
        return view('admin.users.userPictures',
            ['pics' => $pics,
            'avatars' => $avatars,
            'userNames' => $userNames,
            'days' => isset($request->days) ? $request->days : null,
            'en_group' => isset($request->en_group) ? $request->en_group : null,
            'city' => isset($request->city) ? $request->city : null,
            'area' => isset($request->area) ? $request->area : null,
            'hiddenSearch' => isset($request->hidden) ? true : false]);
    }

    public function modifyUserPictures(Request $request)
    {
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
                $userMessage = Message::select('to_id','from_id', DB::raw('count(*) as count'))->join('users','users.id','message.to_id')->groupBy('to_id','from_id');
                if($request->date_start){
                    $userMessage = $userMessage->where('users.last_login', '>', $request->date_start . ' 00:00');
                }
                if($request->date_end){
                    $userMessage = $userMessage->where('users.last_login', '<', $request->date_end . ' 23:59');
                }
                $userMessage = $userMessage->get();
                $users = array();
                $messages = array();

                foreach ($userMessage as $Message) {
                    $messages[$Message->to_id][$Message->from_id] = $Message->count;
                    if(!in_array($Message->to_id, $users)){
                        array_push($users, $Message->to_id);
                    }
                    if(!in_array($Message->from_id, $users)){
                        array_push($users, $Message->from_id);
                    }
                }
                $userData = new User;
                if(!is_null($request->date_start) && !is_null($request->date_end)){
                    $userData = $userData->select('id','name',DB::raw("CASE WHEN last_login BETWEEN '". $request->date_start ." 00:00' AND '".$request->date_end." 23:59'  THEN true ELSE false END AS login"));
                }
                if(!is_null($request->date_start) && is_null($request->date_end)){
                    $userData = $userData->select('id','name',DB::raw("CASE WHEN last_login > '". $request->date_start ." 00:00' THEN true ELSE false END AS 'login'"));
                }
                if(is_null($request->date_start) && !is_null($request->date_end)){
                    $userData = $userData->select('id','name',DB::raw("CASE WHEN last_login < '". $request->date_end ." 23:59' THEN true ELSE false END AS 'login'"));
                }
                $userData = $userData->whereIn('id', $users)->get();
                $isVip =  Vip::select('member_id')->where('active', 1)->orderBy('member_id')->get();
                $datas = array();
                $userVip = array();

                foreach ($userData as $Data) {
                    $datas[$Data->id] = array('name' => $Data->name,'login' => $Data->login );
                }
                
                foreach($isVip as $vip) {
                    $userVip[$vip->member_id] = true;
                }
                
                return view('admin.users.reportedCount')
                    ->with('users', $datas)
                    ->with('msgs', $messages)
                    ->with('vips', $userVip)
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
            $messages = Message::where('isReported', 1)->orderBy('created_at', 'desc');
            $datas = $this->admin->fillMessageDatas($messages);
            return view('admin.users.searchMessage')
                ->with('reported', 1)
                ->with('results', $datas['results'])
                ->with('users', isset($datas['users']) ? $datas['users'] : null)
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
    public function searchMessage(Request $request){
        if($request->time =='send_time'){
            $datas = $this->admin->searchMessageBySendTime($request);
        }
        else{
            
            try {

                //Get messages.
                $results = Message::select('*');
                if ( $request->msg ) {
                    $results = $results->where('content', 'like', '%' . $request->msg . '%');
                }
                if ( $request->date_start && $request->date_end ) {
                    $results = $results->whereBetween('created_at', array($request->date_start . ' 00:00', $request->date_end . ' 23:59'));
                }
                if ( !$request->msg && !$request->date_start && !$request->date_end) {
                    $results = null;
                }
                
            }
            finally{

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
                    $senders = array();
                    foreach ($from_id as $key => $id){
                        $sender = User::where('id', '=', $id)->get()->first();
                        $vip_tmp = $sender->isVip() ? true : false;
                        $senders[$key] = $sender->toArray();
                        $senders[$key]['vip'] = $vip_tmp;
                        $senders[$key]['isBlocked'] = banned_users::where('member_id', 'like', $id)->get()->first() == true ? true : false;
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
                        $name = User::select('name')
                            ->where('id', '=', $id)
                            ->get()->first();
                        if($name != null){
                            $receivers[$id] = $name->name;
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
                ->select('banned_users.*', 'users.name', 'users.email')->orderBy('created_at', 'desc')->get();
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
            return view('admin.users.messenger')
                   ->with('admin', $admin)
                   ->with('user', $user);
        }
        else{
            return back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function showAdminMessengerWithMessageId($id, $mid)
    {
        

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
                $msglib_msg[$key] = str_replace('|$report|',$user->name, $msg['msg']);
                $msglib_msg[$key] = str_replace('|$reported|',$to_user->name, $msglib_msg[$key]);
                $msglib_msg[$key] = str_replace('|$reportTime|',$message_msg[0]->created_at, $msglib_msg[$key]);
                $msglib_msg[$key] = str_replace('|$responseTime|',date("Y-m-d H:i:s"), $msglib_msg[$key]);
            }
        }else{
            foreach($msglib_all as $key=>$msg){
                $msglib_msg[$key] = $msg['msg'];
            }
        }
        if(!$msglib_reported->isEmpty()){
            foreach($msglib_reported as $key=>$msg){
                $msglib_msg2[$key] = str_replace('|$report|',$user->name, $msg['msg']);
                $msglib_msg2[$key] = str_replace('|$reported|',$to_user->name, $msglib_msg2[$key]);
                $msglib_msg2[$key] = str_replace('|$reportTime|',$message_msg[0]->created_at, $msglib_msg2[$key]);
                $msglib_msg2[$key] = str_replace('|$responseTime|',date("Y-m-d H:i:s"), $msglib_msg2[$key]);
            }
        }else{
            foreach($msglib_all as $key=>$msg){
                $msglib_msg2[$key] = $msg['msg'];
            }
        }
        




            return view('admin.users.messenger')
                ->with('admin', $admin)
                ->with('user', $user)
                ->with('to_user', $to_user)
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

    public function showAdminMessengerWithReportedId($id, $mid, $pic_id = null, $isPic= null, $isReported= null)
    { 

        $admin = $this->admin->checkAdmin();
        if ($admin){
            $msglib = Msglib::get();
            $msglib_report = Msglib::selectraw('id, title, msg')->where('kind','=','report')->get();
            $msglib_reported = Msglib::selectraw('id, title, msg')->where('kind','=','reported')->get();
            $msglib = Msglib::get();
            $msglib2 = Msglib::get();
            $msglib3 = Msglib::selectraw('msg')->get();
            $report = Reported::where('member_id', $id)->where('reported_id', $mid)->get()->first();

            $reported = User::where('id', $mid)->get()->first();

            /*被檢舉者 */
            $user = $this->service->find($id);
            $reported = Reported::get()->first();
            // $sender = User::where('id', $reported->reported_id)->get()->first();

            /*檢舉者*/
            $to_user_id = Reported::where('member_id', $id)->get()->first()->reported_id;
            $to_user    = $this->service->find($to_user_id);
            $message_msg = Reported::where('reported_id', $to_user->id)->where('member_id',$user->id)->get();            
            foreach($msglib_report as $key=>$msg){
                $msglib_msg[$key] = str_replace('|$report|',$user->name, $msg['msg']);
                $msglib_msg[$key] = str_replace('|$reported|',$to_user->name, $msglib_msg[$key]);
                $msglib_msg[$key] = str_replace('|$reportTime|',$message_msg[0]->created_at, $msglib_msg[$key]);
                $msglib_msg[$key] = str_replace('|$responseTime|',date("Y-m-d H:i:s"), $msglib_msg[$key]);
            }
    
            foreach($msglib_reported as $key=>$msg){
                $msglib_msg2[$key] = str_replace('|$report|',$user->name, $msg['msg']);
                $msglib_msg2[$key] = str_replace('|$reported|',$to_user->name, $msglib_msg2[$key]);
                $msglib_msg2[$key] = str_replace('|$reportTime|',$message_msg[0]->created_at, $msglib_msg2[$key]);
                $msglib_msg2[$key] = str_replace('|$responseTime|',date("Y-m-d H:i:s"), $msglib_msg2[$key]);
            }
            return view('admin.users.messenger')
                ->with('admin', $admin)
                ->with('user', $user)
                ->with('to_user', $to_user)

                ->with('message', 'REPORTEDUSERONLY')
                ->with('report', $report)
                // ->with('user', $reported)
                ->with('reportedName', $reported->name)

                // ->with('to_user', $reported)

                ->with('isPic', $isPic)
                ->with('isReported', $isReported)
                ->with('isReportedId', $mid)
                ->with('pic_id', $pic_id)
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
            if($request->report_id){
                $m = Reported::where('id', $request->report_id)->get()->first();
                $m->delete();
            }
            if($request->pic_id){
                if(str_contains($request->pic_id, 'avatar')){
                    $a_id = substr($request->pic_id, 6, strlen($request->pic_id));
                    $a = ReportedAvatar::where('id', $a_id)->get()->first();
                    $a->delete();
                }
                else{
                    $p = ReportedPic::where('id', $request->pic_id)->get()->first();
                    $p->delete();
                }
            }
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
            $avatars = ReportedAvatar::select('*');
            $pics = ReportedPic::select('*');
            if($request->date_start){
                $avatars = $avatars->where('created_at', '>', $request->date_start . ' 00:00');
                $pics = $pics->where('created_at', '>', $request->date_start . ' 00:00');
            }
            if($request->date_end){
                $avatars = $avatars->where('created_at', '<', $request->date_end . ' 23:59');
                $pics = $pics->where('created_at', '<', $request->date_end . ' 23:59');
            }
            $avatars = $avatars->orderBy('created_at', 'desc')->get();
            $pics = $pics->orderBy('created_at', 'desc')->get();
            $avatarDatas = $this->admin->fillReportedAvatarDatas($avatars);
            $picDatas = $this->admin->fillReportedPicDatas($pics);

            return view('admin.users.reportedPics')
                ->with('results', $avatarDatas['results'] ? $avatarDatas['results'] : 1)
                ->with('users', isset($avatarDatas['users']) ? $avatarDatas['users'] : null)
                ->with('Presults', $picDatas['results'] ? $picDatas['results'] :null)
                ->with('Pusers', isset($picDatas['users']) ? $picDatas['users'] : null)
                ->with('date_start', isset($request->date_start) ? $request->date_start : null)
                ->with('date_end', isset($request->date_end) ? $request->date_end : null);
        }
        else{
            return view('admin.users.reportedPics')->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
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
}
