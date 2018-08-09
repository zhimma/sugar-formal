<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\User;
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
        $admin = User::where('name', 'like', '%'.'站長'.'%')->get()->first();
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
     * Deletes selected members' messages.
     *
     * @return data set
     */
    public function deleteMessage(Request $request)
    {
        $admin = $this->checkAdmin();
        if (!$admin) {
            return redirect()->back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
        $returnDatas = $this->preData($request->msg_id);
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
        $data = array();
        $admin = $this->checkAdmin();
        if (!$admin) {
            return redirect()->back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
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
        $template = array(
            "head"   =>"你好，由於您在",
            "body"   =>"的訊息不符站方規定，故已修改。"
        );
        $admin = $this->checkAdmin();
        if (!$admin) {
            return redirect()->back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
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
}
