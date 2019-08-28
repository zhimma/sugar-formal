<?php

namespace App\Services;

use App\Models\ReportedPic;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vip;
use App\Models\UserMeta;
use App\Models\Message;
use App\Models\MemberPic;
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
            if($user['vip'] == '是'){
                $user['vip_data'] = Vip::select('id', 'expiry')
                    ->where('member_id', $user->id)
                    ->orderBy('created_at', 'asc')
                    ->get()->first();
            }
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
            $result['isBlockedReceiver'] = banned_users::where('member_id', 'like', $result->to_id)->get()->first();
            // $result['vip'] = Vip::where('member_id', 'like', $result->from_id)->get()->first();
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
            $vip = Vip::where('member_id', 'like', $id)->get()->first();
            if($name != null){
                $users[$id]['name'] = $name->name;
                $users[$id]['vip'] = $vip;
            }
            else{
                $users[$id] = '資料庫沒有資料';
            }
        }
        return ['results' => $results,
                'users' => $users];
    }

    public function fillReportedDatas($results){
        $results = $results->get();
        $member_id = array();
        $reported_id = array();
        foreach ($results as $result){
            if(!in_array($result->member_id, $member_id)) {
                array_push($member_id, $result->member_id);
            }
            if(!in_array($result->reported_id, $reported_id)) {
                array_push($reported_id, $result->reported_id);
            }
            $result['isBlocked'] = banned_users::where('member_id', 'like', $result->member_id)->get()->first();
            $result['isBlockedReceiver'] = banned_users::where('member_id', 'like', $result->reported_id)->get()->first();
        }
        $users = array();
        foreach ($member_id as $id){
            $users[$id] = array();
        }
        foreach ($reported_id as $id){
            if(!in_array($id, $users)){
                $users[$id] = array();
            }
        }
        foreach ($users as $id => $user){
            $name = User::select('name')
                ->where('id', '=', $id)
                ->get()->first();
            if($name != null){
                $users[$id]['name'] = $name->name;
                $users[$id]['vip'] = (Vip::where('member_id', 'like', $id)->get()->first()) ? true : false;
            }
            else{
                $users[$id] = '資料庫沒有資料';
            }
        }
        return ['results' => $results,
            'users' => $users];
    }

    public function fillReportedAvatarDatas($results){
        $reporter_id = array();
        $reported_user_id = array();
        foreach ($results as $result){
            if(!in_array($result->reporter_id, $reporter_id)) {
                array_push($reporter_id, $result->reporter_id);
            }
            if(!in_array($result->reported_user_id, $reported_user_id)) {
                array_push($reported_user_id, $result->reported_user_id);
            }
            $result['isBlocked'] = banned_users::where('member_id', 'like', $result->reporter_id)->get()->first();
            $result['isBlockedReceiver'] = banned_users::where('member_id', 'like', $result->reported_user_id)->get()->first();
            
            $result['pic'] = UserMeta::select('pic')->where('user_id', $result->reported_user_id)->get()->first();
            $result['pic'] = $result['pic']->pic;
        }
        $users = array();
        foreach ($reporter_id as $id){
            $users[$id] = array();
        }
        foreach ($reported_user_id as $id){
            if(!in_array($id, $users)){
                $users[$id] = array();
            }
        }
        foreach ($users as $id => $user){
            $name = User::select('name')
                ->where('id', '=', $id)
                ->get()->first();
            if($name != null){
                $users[$id]['name'] = $name->name;
                $users[$id]['vip'] = (Vip::where('member_id', 'like', $id)->get()->first()) ? true : false;
            }
            else{
                $users[$id] = '資料庫沒有資料';
            }
        }
        return ['results' => $results,
            'users' => $users];
    }

    public function fillReportedPicDatas($results){
        $reporter_id = array();
        $reported_user_id = array();
        foreach ($results as $result){
            if(!in_array($result->reporter_id, $reporter_id)) {
                array_push($reporter_id, $result->reporter_id);
            }
            $temp = MemberPic::select('member_id', 'pic')->where('id', $result->reported_pic_id)->get()->first();
            if(isset($temp)){
                $result['reported_user_id'] = $temp->member_id;
                $result['pic'] = $temp->pic;
                if(!in_array($temp->member_id, $reported_user_id)) {
                    array_push($reported_user_id, $temp->member_id);
                }
                $result['isBlocked'] = banned_users::where('member_id', 'like', $temp->member_id)->get()->first();
                if(Vip::where('member_id', 'like', $result->reporter_id)->get()->first()){
                    $result['vip'] = '是';
                }
                else{
                    $result['vip'] = '否';
                }
            }
            else{
                $result['reported_user_id'] = null;
                $result['pic'] = '照片已刪除或該筆資料不存在。';
                $result['isBlocked'] = '';
                $result['vip'] = '照片已刪除或該筆資料不存在。';
            }

        }
        $users = array();
        foreach ($reporter_id as $id){
            $users[$id] = array();
        }
        foreach ($reported_user_id as $id){
            if(!in_array($id, $users)){
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

    public function picPreData($ids, $type){
        if($type == 'pic'){
            $infos = array();
            foreach ($ids as $key => $id){
                $p = MemberPic::select('member_id', 'created_at')->where('id', $id)->get()->first();
                $infos[$id]['post_time'] = $p->created_at;
                $u = User::select('id', 'name')->where('id', $p->member_id)->get()->first();
                $infos[$id]['user_id'] = $u->id;
                $infos[$id]['user_name'] = $u->name;
            }
            $datas = ['pic_ids' => $ids,
                'infos' => $infos];
            return $datas;
        }
        else if($type == 'avatar'){
            $infos = array();
            foreach ($ids as $key => $id){
                $infos[$id]['post_time'] = '';
                $u = User::select('name')->where('id', $id)->get()->first();
                $infos[$id]['user_id'] = $id;
                $infos[$id]['user_name'] = $u->name;
            }
            $datas = ['pic_ids' => $ids,
                'infos' => $infos];
            return $datas;
        }
        return false;
    }

    public function deletePicture(Request $request)
    {
        $admin = $this->checkAdmin();
        if(!$admin){
            return false;
        }
        if($request->pic_id == null && $request->avatar_id == null){
            return null;
        }
        $pic_ids = is_array($request->pic_id) ? $request->pic_id : array($request->pic_id);
        $avatar_ids = is_array($request->avatar_id) ? $request->avatar_id : array($request->avatar_id);
        if($pic_ids[0] != null){
            $returnDatas1 = $this->picPreData($pic_ids, 'pic');
            $picInfos = $returnDatas1['infos'];
            $pic_ids = $returnDatas1['pic_ids'];
            if(MemberPic::whereIn('id', $pic_ids)->delete()){

            }
            else{
                //return redirect()->back()->withInput()->withErrors(['出現錯誤，訊息刪除失敗']);
                return false;
            }
        }
        if($avatar_ids[0] != null){
            $returnDatas2 = $this->picPreData($avatar_ids, 'avatar');
            $avatarInfos = $returnDatas2['infos'];
            $avatar_ids = $returnDatas2['pic_ids'];
            foreach( $avatar_ids as $user_id){
                $u = UserMeta::select('id', 'pic')->where('user_id', $user_id)->get()->first();
                $u->pic = null;
                $u->save();
            }
        }
        $template = [
            "pic" => [
                "head"   =>"您好，由於您在",
                "body"   =>"上傳的照片不適合網站主旨，故已刪除。請重新上傳。如有疑慮請與站長聯絡。"
            ],
            "avatar" => [
                "head"   =>"您好，由於您",
                "body"   =>"的大頭照不適合網站主旨，故已刪除。請重新上傳。如有疑慮請與站長聯絡。"
            ]
        ];
        //return redirect()->back()->withInput()->with('message', '訊息刪除成功');
        $request->session()->put('message', '照片刪除成功，將會產生通知訊息發送給各發訊的會員，請檢查訊息內容，若無誤請按下送出。');
        $datas = ['admin' => $admin,
            'msgs' => isset($picInfos) ? $picInfos : 0,
            'msgs2' => isset($avatarInfos) ? $avatarInfos : 0,
            'template' => $template];
        return $datas;
    }

    public function hidePicture(Request $request)
    {
        $admin = $this->checkAdmin();
        if(!$admin){
            return false;
        }
        if($request->pic_id == null && $request->avatar_id == null){
            return null;
        }
        $pic_ids = is_array($request->pic_id) ? $request->pic_id : array($request->pic_id);
        $avatar_ids = is_array($request->avatar_id) ? $request->avatar_id : array($request->avatar_id);
        if($pic_ids[0] != null){
            $returnDatas1 = $this->picPreData($pic_ids, 'pic');
            $picInfos = $returnDatas1['infos'];
            $pic_ids = $returnDatas1['pic_ids'];
            foreach( $pic_ids as $pic){
                $u = MemberPic::select('id', 'isHidden')->where('id', $pic)->get()->first();
                $u->isHidden = 1;
                $u->save();
            }
        }
        if($avatar_ids[0] != null){
            $returnDatas2 = $this->picPreData($avatar_ids, 'avatar');
            $avatarInfos = $returnDatas2['infos'];
            $avatar_ids = $returnDatas2['pic_ids'];
            foreach( $avatar_ids as $user_id){
                $u = UserMeta::select('id', 'isAvatarHidden')->where('user_id', $user_id)->get()->first();
                $u->isAvatarHidden = 1;
                $u->save();
            }
        }
        $template = [
            "pic" => [
                "head"   =>"您好，由於您在",
                "body"   =>"上傳的照片不適合網站主旨，故已隱藏。請重新上傳。如有疑慮請與站長聯絡。"
            ],
            "avatar" => [
                "head"   =>"您好，由於您",
                "body"   =>"的大頭照不適合網站主旨，故已隱藏。請重新上傳。如有疑慮請與站長聯絡。"
            ]
        ];
        //return redirect()->back()->withInput()->with('message', '訊息刪除成功');
        $request->session()->put('message', '照片隱藏成功，將會產生通知訊息發送給各發訊的會員，請檢查訊息內容，若無誤請按下送出。');
        $datas = ['admin' => $admin,
            'msgs' => isset($picInfos) ? $picInfos : 0,
            'msgs2' => isset($avatarInfos) ? $avatarInfos : 0,
            'template' => $template];
        return $datas;
    }

    public function deHidePicture(Request $request)
    {
        $admin = $this->checkAdmin();
        if(!$admin){
            return false;
        }
        if($request->pic_id == null && $request->avatar_id == null){
            return null;
        }
        $pic_ids = is_array($request->pic_id) ? $request->pic_id : array($request->pic_id);
        $avatar_ids = is_array($request->avatar_id) ? $request->avatar_id : array($request->avatar_id);
        if($pic_ids[0] != null){
            $returnDatas1 = $this->picPreData($pic_ids, 'pic');
            $picInfos = $returnDatas1['infos'];
            $pic_ids = $returnDatas1['pic_ids'];
            foreach( $pic_ids as $pic){
                $u = MemberPic::select('id', 'isHidden')->where('id', $pic)->get()->first();
                $u->isHidden = 0;
                $u->save();
            }
        }
        if($avatar_ids[0] != null){
            $returnDatas2 = $this->picPreData($avatar_ids, 'avatar');
            $avatarInfos = $returnDatas2['infos'];
            $avatar_ids = $returnDatas2['pic_ids'];
            foreach( $avatar_ids as $user_id){
                $u = UserMeta::select('id', 'isAvatarHidden')->where('user_id', $user_id)->get()->first();
                $u->isAvatarHidden = 0;
                $u->save();
            }
        }
        $template = [
            "pic" => [
                "head"   =>"您好，您在",
                "body"   =>"上傳的照片已解除隱藏。"
            ],
            "avatar" => [
                "head"   =>"您好，您",
                "body"   =>"的大頭照已解除隱藏。"
            ]
        ];
        //return redirect()->back()->withInput()->with('message', '訊息刪除成功');
        $request->session()->put('message', '照片解除隱藏成功。');
        $datas = ['admin' => $admin,
            'msgs' => isset($picInfos) ? $picInfos : 0,
            'msgs2' => isset($avatarInfos) ? $avatarInfos : 0,
            'template' => $template];
        return $datas;
    }
}
