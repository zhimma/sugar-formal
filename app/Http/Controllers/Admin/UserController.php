<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Models\Message;
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
            $user['vip_data'] = Vip::select('id', 'free', 'created_at', 'updated_at')
                                ->where('member_id', $user->id)
                                ->orderBy('created_at', 'desc')
                                ->get()->first();
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
        $user = User::select('id', 'email')
                ->where('id', $request->user_id)
                ->get()->first();

        return view('admin.users.success')
               ->with('email', $user->email);
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
            return $this->advSearch($request, 'unban');
        }
        else{
            $userBanned = new banned_users;
            $userBanned->member_id = $request->user_id;
            $userBanned->save();
            return $this->advSearch($request, 'ban');
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

    public function banUserWithDayAndMessage($user_id, $msg_id, $days){
        $userBanned = banned_users::where('member_id', $user_id)
            ->get()->first();
        if(!$userBanned){
            $userBanned = new banned_users;
        }
        $userBanned->member_id = $user_id;
        $userBanned->expire_date = Carbon::now()->addDays($days);
        $userBanned->save();
        $message = Message::where('id', $msg_id)->get()->first();
        $user = User::where('id', $user_id)->get()->first();
        $content = ['hello' => $user->name.'您好，',
                    'notice1' => '您在'.$message->created_at.'所發送的訊息，',
                    'notice2' => '因內容「'.$message->content.'」，',
                    'notice3' => '所以遭封鎖'.$days.'天。'];
        $user->notify(new BannedNotification($content));
        return back()->with('message', '成功封鎖使用者並發送通知信');
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
               ->with('member_type', isset($request->member_type) ? $request->member_type : null)
               ->with('time', isset($request->time) ? $request->time : null);
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
                    $to_ids[$u->to_id] = $to_ids[$u->to_id]->name;
                }
                else{
                    $to_ids[$u->to_id] = '查無資料';
                }
            }
        }
        if(str_contains(url()->current(), 'edit')){
            return view('admin.users.editAdvInfo')
                   ->with('userMeta', $userMeta)
                   ->with('user', $user);
        }
        else{
            return view('admin.users.advInfo')
                   ->with('userMeta', $userMeta)
                   ->with('user', $user)
                   ->with('userMessage', $userMessage)
                   ->with('to_ids', $to_ids);
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

    public function showReportedMessages(){
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
                        $vip_tmp = $sender->isVip() ? '是' : '否';
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
                    return view('admin.users.messenger')->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
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
                return view('admin.users.messenger')->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
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
            return view('admin.users.messenger')->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
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
            return view('admin.users.messenger')->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
    }

    public function showAdminMessengerWithMessageId($id, $mid)
    {
        $admin = $this->admin->checkAdmin();
        if ($admin){
            $user = $this->service->find($id);
            $message = Message::where('id', $mid)->get()->first();
            $sender = User::where('id', $message->from_id)->get()->first();
            return view('admin.users.messenger')
                ->with('admin', $admin)
                ->with('user', $user)
                ->with('message', $message)
                ->with('senderName', $sender->name);
        }
        else{
            return view('admin.users.messenger')->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
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
            $m = Message::where('id', $request->msg_id)->get()->first();
            $m->isReported = 0;
            $m->reportContent = '';
            $m->save();
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
        $a = AdminAnnounce::get()->all();
        return view('admin.adminannouncement')->with('announce', $a);
    }

    /**
     * Saves admin announcement.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveAdminAnnouncement(Request $request)
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
}
