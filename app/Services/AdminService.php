<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vip;
use App\Models\UserMeta;
use App\Models\Message;
use App\Models\SimpleTables\banned_users;
use PhpParser\Node\Expr\Cast\Object_;


class AdminService
{
    /**
     * User model
     * @var User
     */
    public $model;

    /**
     * User Meta model
     * @var UserMeta
     */
    protected $userMeta;

    /**
     * Role Service
     * @var RoleService
     */
    protected $role;

    /**
     * Check admin user existence.
     *
     * @return $admin or false
     */
    public function checkAdmin(){
        $banned_users = banned_users::select('member_id')->get();
        $admin = User::where('name', 'like', '%'.'站長'.'%')
                       ->whereNotIn('id', $banned_users)
                       ->get()->first();
        if ($admin){
            return $admin;
        }
        else{
            return false;
        }
    }

    /**
     * Search advanced member data. (Advanced)
     *
     * @return $users data
     */
    public function advSearch(Request $request)
    {
        if( $request->email && $request->name ){
            $users = User::where('email', 'like', '%' . $request->email . '%')
                ->where('name', 'like', '%' . $request->name . '%');
        }
        else if( $request->email ){
            $users = User::where('email', 'like', '%' . $request->email . '%');
        }
        else if ( $request->name ){
            $users = User::where('name', 'like', '%' . $request->name . '%');
        }
        else{
            return redirect(route('users/advSearch'));
        }
        if($request->time =='created_at'){
            $users = $users->orderBy('created_at', 'desc');
        }
        if($request->time =='login_time'){
            $users = $users->orderBy('last_login', 'desc');
        }
        $users = $users->get();
        foreach ($users as $user){
            $user['isBlocked'] = banned_users::where('member_id', 'like', $user->id)->get()->first() == true  ? true : false;
            $user['vip'] = $user->isVip() ? '是' : '否';
        }
        if($request->member_type =='vip'){
            $users = collect($users)->sortBy('vip', true,true)->reverse()->toArray();
        }
        if($request->member_type =='banned'){
            $users = collect($users)->sortBy('isBlocked')->reverse()->toArray();
        }
        return $users;
    }

    /**
     * Search members' messages.
     *
     * @return result
     */
    public function searchMessage(Request $request){
            if ( $request->msg && $request->date_start && $request->date_end ) {
                $results = Message::where('content', 'like', '%' . $request->msg . '%')
                    ->whereBetween('created_at', array($request->date_start . ' 00:00', $request->date_end . ' 23:59'));
            } else if ( $request->msg ) {
                $results = Message::where('content', 'like', '%' . $request->msg . '%');
            } else if ( $request->date_start && $request->date_end ) {
                $results = Message::whereBetween('created_at', array($request->date_start . ' 00:00', $request->date_end . ' 23:59'));
            }
            else{
                return null;
            }

            if($request->time =='created_at'){
                $users = $users->orderBy('created_at', 'desc');
            }
            if($request->time =='login_time'){
                $users = $users->orderBy('last_login', 'desc');
            }
            $results = $results->get();
            return $results;
    }

    /**
     * Search members' messages and orders by send time.
     *
     * @return datas
     */
    public function searchMessageBySendTime(Request $request){
        try {
            if ( $request->msg && $request->date_start && $request->date_end ) {
                $results = Message::where('content', 'like', '%' . $request->msg . '%')
                    ->whereBetween('created_at', array($request->date_start . ' 00:00', $request->date_end . ' 23:59'));
            } else if ( $request->msg ) {
                $results = Message::where('content', 'like', '%' . $request->msg . '%');
            } else if ( $request->date_start && $request->date_end ) {
                $results = Message::whereBetween('created_at', array($request->date_start . ' 00:00', $request->date_end . ' 23:59'));
            }
            else{
                $results = null;
            }
        }
        finally{
            if($results != null){
                $return = $this->fillMessageDatas($results);
                $results = $return['results'];
                if($request->member_type =='vip'){
                    $results = collect($results)->sortBy('vip', true,true)->reverse()->toArray();
                }
                if($request->member_type =='banned'){
                    $results = collect($results)->sortBy('isBlocked')->reverse()->toArray();
                }
            }
            $datas = [
                'results' => $results,
                'users' => isset($return['users']) ? $return['users'] : null,
                'msg' => isset($request->msg) ? $request->msg : null,
                'date_start' => isset($request->date_start) ? $request->date_start : null,
                'date_end' => isset($request->date_end) ? $request->date_end : null
            ];
            return $datas;
        }
    }

    public function fillMessageDatas($results){
        $results = $results->orderBy('created_at', 'desc')->get();
        $to_id = array();
        $from_id = array();
        foreach ($results as $result){
            if(!in_array($result->to_id, $to_id)) {
                array_push($to_id, $result->to_id);
            }
            if(!in_array($result->from_id, $from_id)) {
                array_push($from_id, $result->from_id);
            }
            $result['isBlocked'] = banned_users::where('member_id', 'like', $result->from_id)->get()->first();
            if(Vip::where('member_id', 'like', $result->from_id)->get()->first()){
                $result['vip'] = '是';
            }
            else{
                $result['vip'] = '否';
            }
        }
        $users = array();
        foreach ($to_id as $id){
            $users[$id] = array();
        }
        foreach ($from_id as $id){
            if(!in_array($id, $to_id)){
                $users[$id] = array();
            }
        }
        foreach ($users as $id => $user){
            $name = User::select('name')
                ->where('id', '=', $id)
                ->get()->first();
            if($name != null){
                $users[$id] = $name->name;
            }
            else{
                $users[$id] = '資料庫沒有資料';
            }
        }
        return ['results' => $results,
                'users' => $users];
    }

    /**
     * Deletes selected members' messages.
     *
     * @return data set
     */
    public function deleteMessage(Request $request)
    {
        $admin = $this->checkAdmin();
        if(!$admin){
            return false;
        }
        if($request->msg_id == null){
            return null;
        }
        $msg_ids = is_array($request->msg_id) ? $request->msg_id : array($request->msg_id);
        $returnDatas = $this->preData($msg_ids);
        $messages = $returnDatas['msgs'];
        $msg_ids = $returnDatas['msg_ids'];
        if(Message::whereIn('id', $msg_ids)->delete()){
            $template = array(
                "head"   =>"你好，由於您在",
                "body"   =>"的訊息不符站方規定，故已刪除。"
            );
            //return redirect()->back()->withInput()->with('message', '訊息刪除成功');
            $request->session()->put('message', '訊息刪除成功，將會產生通知訊息發送給各發訊的會員，請檢查訊息內容，若無誤請按下送出。');
            $datas = ['admin' => $admin,
                'msgs' => $messages,
                'template' => $template];
            return $datas;
        }
        else{
            //return redirect()->back()->withInput()->withErrors(['出現錯誤，訊息刪除失敗']);
            return false;
        }
    }

    /**
     * Renders messages that needed to be edited.
     *
     * @return data set
     */
    public function renderMessages(Request $request){
        $admin = $this->checkAdmin();
        if(!$admin){
            return false;
        }
        $data = array();
        $data['ids'] = $request->msg_id;
        $data['originalMessage'] = $request->msg;
        $data['admin'] = $admin;
        return $data;
    }

    /**
     * Edits selected members' messages.
     *
     * @return Id
     */
    public function editMessageThenReturnIds(Request $request)
    {
        $message_ids = array();
        foreach ($request->msg_id as $message_id){
            $message = Message::where('id', $message_id)->get()->first();
            $message->content = str_replace($request->originalMessage, $request->replace, $message->content);
            array_push($message_ids, $message->id);
            $message->save();
        }
        return $message_ids;
    }

    public function sendEditedNotice(Request $request, $message_ids){
        $admin = $this->checkAdmin();
        if(!$admin){
            return false;
        }
        $template = array(
            "head"   =>"你好，由於您在",
            "body"   =>"的訊息不符站方規定，故已修改。"
        );
        $message_ids = is_array($message_ids) ? $message_ids : array($message_ids);
        $returnDatas = $this->preData($message_ids);
        //return redirect()->back()->withInput()->with('message', '訊息刪除成功');
        $request->session()->put('message', '訊息修改成功，將會產生通知訊息發送給各發訊的會員，請檢查訊息內容，若無誤請按下送出。');
        $datas = ['admin' => $admin,
                  'msgs' => $returnDatas['msgs'],
                  'template' => $template];
        return $datas;
    }

    /**
     * Renders target users' datas.
     *
     * @return Datas
     */
    public function preData($message_ids){
        $msg_ids = array();
        $msgs = array();
        foreach ($message_ids as $key => $msg_id){
            array_push($msg_ids, $msg_id);
            $m = Message::select('from_id', 'created_at')->where('id', $msg_id)->get()->first();
            $msgs[$msg_id]['from_id'] = $m->from_id;
            $msgs[$msg_id]['post_time'] = $m->created_at;
            $u = User::select('name')->where('id', $m->from_id)->get()->first();
            $msgs[$msg_id]['name'] = $u->name;
        }
        $datas = ['msg_ids' => $msg_ids,
                  'msgs' => $msgs];
        return $datas;
    }

    public function deletePicture(Request $request)
    {
        $admin = $this->checkAdmin();
        if(!$admin){
            return false;
        }
        if($request->msg_id == null){
            return null;
        }
        //NEED A NEW PRE-DATA FUNCTION.
        $msg_ids = is_array($request->msg_id) ? $request->msg_id : array($request->msg_id);
        $returnDatas = $this->preData($msg_ids);
        $messages = $returnDatas['msgs'];
        $msg_ids = $returnDatas['msg_ids'];
        if(Message::whereIn('id', $msg_ids)->delete()){
            $template = array(
                "head"   =>"你好，由於您在",
                "body"   =>"的訊息不符站方規定，故已刪除。"
            );
            //return redirect()->back()->withInput()->with('message', '訊息刪除成功');
            $request->session()->put('message', '訊息刪除成功，將會產生通知訊息發送給各發訊的會員，請檢查訊息內容，若無誤請按下送出。');
            $datas = ['admin' => $admin,
                'msgs' => $messages,
                'template' => $template];
            return $datas;
        }
        else{
            //return redirect()->back()->withInput()->withErrors(['出現錯誤，訊息刪除失敗']);
            return false;
        }
    }

    public function hidePicture(Request $request)
    {
        $admin = $this->checkAdmin();
        if(!$admin){
            return false;
        }
        if($request->msg_id == null){
            return null;
        }
        $msg_ids = is_array($request->msg_id) ? $request->msg_id : array($request->msg_id);
        //NEED A NEW PRE-DATA FUNCTION.
        $returnDatas = $this->preData($msg_ids);
        $messages = $returnDatas['msgs'];
        $msg_ids = $returnDatas['msg_ids'];
        if(Message::whereIn('id', $msg_ids)->delete()){
            $template = array(
                "head"   =>"你好，由於您在",
                "body"   =>"的訊息不符站方規定，故已刪除。"
            );
            //return redirect()->back()->withInput()->with('message', '訊息刪除成功');
            $request->session()->put('message', '訊息刪除成功，將會產生通知訊息發送給各發訊的會員，請檢查訊息內容，若無誤請按下送出。');
            $datas = ['admin' => $admin,
                'msgs' => $messages,
                'template' => $template];
            return $datas;
        }
        else{
            //return redirect()->back()->withInput()->withErrors(['出現錯誤，訊息刪除失敗']);
            return false;
        }
    }
}
