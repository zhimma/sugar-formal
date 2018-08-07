<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\Message;


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
     * Deletes selected members' messages.
     *
     * @return data set
     */
    public function deleteMessage(Request $request)
    {
        $admin = User::where('name', 'like', '%'.'站長'.'%')->get()->first();
        if (!$admin) {
            return redirect()->back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
        $msg_ids = array();
        $msgs = array();
        $names = array();
        $from_ids = array();
        $post_times = array();
        foreach ($request->msg_id as $key => $msg_id){
            array_push($msg_ids, $msg_id);
            $m = Message::select('from_id', 'created_at')->where('id', $msg_id)->get()->first();
            $msgs[$msg_id]['from_id'] = $m->from_id;
            $msgs[$msg_id]['post_time'] = $m->created_at;
            $u = User::select('name')->where('id', $m->from_id)->get()->first();
            $msgs[$msg_id]['name'] = $u->name;
        }
        if(Message::whereIn('id', $msg_ids)->delete()){
            $template = array(
                "head"   =>"你好，由於您在",
                "body"   =>"的訊息不符站方規定，故已刪除。"
            );
            //return redirect()->back()->withInput()->with('message', '訊息刪除成功');
            $request->session()->put('message', '訊息刪除成功，將會產生通知訊息發送給各發訊的會員，請檢查訊息內容，若無誤請按下送出。');
            $datas = ['admin' => $admin,
                'msgs' => $msgs,
                'template' => $template];
            return $datas;
        }
        else{
            //return redirect()->back()->withInput()->withErrors(['出現錯誤，訊息刪除失敗']);
            return false;
        }
    }

    /**
     * Edits selected members' messages.
     *
     * @return data set
     */
    public function editMessage(Request $request)
    {
        $admin = User::where('name', 'like', '%'.'站長'.'%')->get()->first();
        if (!$admin) {
            return redirect()->back()->withErrors(['找不到暱稱含有「站長」的使用者！請先新增再執行此步驟']);
        }
        $msg_ids = array();
        $msgs = array();
        $names = array();
        $from_ids = array();
        $post_times = array();
        foreach ($request->msg_id as $key => $msg_id){
            array_push($msg_ids, $msg_id);
            $m = Message::select('from_id', 'created_at')->where('id', $msg_id)->get()->first();
            $msgs[$msg_id]['from_id'] = $m->from_id;
            $msgs[$msg_id]['post_time'] = $m->created_at;
            $u = User::select('name')->where('id', $m->from_id)->get()->first();
            $msgs[$msg_id]['name'] = $u->name;
        }
        if(Message::whereIn('id', $msg_ids)->delete()){
            $template = array(
                "head"   =>"你好，由於您在",
                "body"   =>"的訊息不符站方規定，故已刪除。"
            );
            //return redirect()->back()->withInput()->with('message', '訊息刪除成功');
            $request->session()->put('message', '訊息刪除成功，將會產生通知訊息發送給各發訊的會員，請檢查訊息內容，若無誤請按下送出。');
            $datas = ['admin' => $admin,
                'msgs' => $msgs,
                'template' => $template];
            return $datas;
        }
        else{
            //return redirect()->back()->withInput()->withErrors(['出現錯誤，訊息刪除失敗']);
            return false;
        }
    }
}
