<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\lineNotifyChat;
use App\Models\lineNotifyChatSet;
use App\Models\MemberFav;
use App\Models\Message;
use App\Models\Message_new;
use App\Models\AnnouncementRead;
use App\Models\AdminAnnounce;
use App\Models\SimpleTables\banned_users;
use App\Models\SimpleTables\warned_users;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\SetAutoBan;
use App\Models\AdminCommonText;
use App\Services\UserService;
use App\Services\VipLogService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

//use Shivella\Bitly\Facade\Bitly;

class Message_newController extends BaseController {
    public function __construct(UserService $userService) {
        parent::__construct();
        $this->service = $userService;
    }

    // handle delete message
    public function deleteBetween(Request $request) {
        Message::deleteBetween($request->input('uid'), $request->input('sid'));

        return redirect('dashboard/chat');
    }

    public function deleteBetweenGET($uid, $sid) {

        Message::deleteBetween($uid, $sid);

        return redirect('dashboard/chat2/'.csrf_token().Carbon::now()->timestamp);
    }

    public function deleteBetweenGetAll($uid, $sid) {
        $ids = explode(',',$uid);
        foreach($ids as $id){
            Message::deleteBetween($sid,$id);
        }
        return redirect('dashboard/chat2/'.csrf_token().Carbon::now()->timestamp);
    }

    public function delete2Between(Request $request) {
        Message::deleteBetween($request->uid, $request->sid);
        return response()->json(['save' => 'ok']);
        //return redirect('dashboard/chat2/'.csrf_token().Carbon::now()->timestamp);
        //return redirect('dashboard/chat2/{randomNo?}');
    }

    public function deleteAll(Request $request) {
        Message::deleteAll($request->uid);
        return response()->json(['save' => 'ok']);
        //return redirect('dashboard/chat');
        //return redirect('dashboard/chat2/'.csrf_token().Carbon::now()->timestamp);
    }

    public function deleteSingle(Request $request) {
        $uid = $request->uid;
        $sid = $request->sid;
        $ct_time = $request->ct_time;
        $content = $request->content;
        $id = $request->id;

        Message::deleteSingle($uid, $sid, $ct_time, $content);
        return response()->json(['save' => 'ok']);
//        return redirect()->route('chat2WithUser', $sid);
        //return redirect('dashboard/chat/' . $sid);
    }

    public function deleteSingleGET($uid, $sid, $ct_time, $content) {
        Message::deleteSingle($uid, $sid, $ct_time, $content);

        return redirect()->route('chatWithUser', $sid);
        //return redirect('dashboard/chat/' . $sid);
    }

    public function chatSet(Request $request) {
        $user = UserMeta::where('user_id',$request->uid)->first();
        if ($user) {
            $user->update([
                'notifmessage' => $request->notifmessage,
                'notifhistory' => $request->notifhistory
            ]);
            return response()->json(['save' => 'ok']);
        }
    }

    public function viewChatNoticeSet(Request $request) {
        $user = \View::shared('user');
        $line_notify_chat = lineNotifyChat::where('active', 1)->whereIn('gender', [$user->engroup, 0])->orderBy('order')->get();

        $line_notify_chat_set_data = lineNotifyChatSet::select('line_notify_chat_set.*')
            ->leftJoin('line_notify_chat','line_notify_chat.id', 'line_notify_chat_set.line_notify_chat_id')
            ->where('line_notify_chat.active',1)
            ->where('line_notify_chat_set.user_id', $user->id)->where('line_notify_chat_set.deleted_at',null)->get();
        $user_line_notify_chat_set = array();
        foreach ($line_notify_chat_set_data as $row){
            array_push($user_line_notify_chat_set, $row->line_notify_chat_id);
        }

        return view('new.dashboard.chatSet')
            ->with('line_notify_chat', $line_notify_chat)
            ->with('user_line_notify_chat_set', $user_line_notify_chat_set);
    }

    public function chatNoticeSet(Request $request) {
        $user = \View::shared('user');

        //line notify start
        $group_name = $request->input('group_name');
        if(empty($group_name)){
            //全刪除
            lineNotifyChatSet::where('user_id', $user->id)->delete();
        }else {
            //先刪後增
            lineNotifyChatSet::where('user_id', $user->id)->whereNotIn('line_notify_chat_id', $group_name)->delete();
            $line_notify_chat_set_data = lineNotifyChatSet::select('line_notify_chat_set.*')
                ->leftJoin('line_notify_chat', 'line_notify_chat.id', 'line_notify_chat_set.line_notify_chat_id')
                ->where('line_notify_chat.active', 1)
                ->where('line_notify_chat_set.user_id', $user->id)->where('line_notify_chat_set.deleted_at', null)->get();
            $user_line_notify_chat_set = array();
            foreach ($line_notify_chat_set_data as $row) {
                array_push($user_line_notify_chat_set, $row->line_notify_chat_id);
            }
            foreach ($group_name as $v) {
                if (!in_array($v, $user_line_notify_chat_set)) {
                    //不存在則新增
                    lineNotifyChatSet::insert(['user_id' => $user->id, 'line_notify_chat_id' => $v, 'created_at' => \Carbon\Carbon::now()]);
                }
            }
        }
        //line notify end

        return back()->with('message','設定已更新');
    }

    public function reportMessage(Request $request){
        Message::reportMessage($request->id, $request->content);

        return redirect()->route('chatWithUser', $request->sid)->with('message', '成功檢舉該筆訊息');
        //return redirect('dashboard/chat/' . $request->sid)->with('message', '成功檢舉該筆訊息');
    }

    public function showReportMessagePage($id, $sid) {
        $user = Auth::user();
        return view('dashboard.reportMessage')->with('id', $id)->with('sid', $sid)->with('user', $user);
    }


    public function postChat(Request $request, $randomNo = null)
    {
        $banned = banned_users::where('member_id', Auth::user()->id)
            ->whereNotNull('expire_date')
            ->orderBy('expire_date', 'asc')->get()->first();
        if(isset($banned)){
            $date = \Carbon\Carbon::parse($banned->expire_date);
            return view('errors.User-banned-with-message',
                ['banned' => $banned,
                 'days' => $date->diffInDays() + 1]);
        }
        $payload = $request->all();
        if(!isset($payload['msg'])){
            return back()->withErrors(['請勿僅輸入空白！']);
        }
        $user = Auth::user();
        // 非 VIP: 一律限 8 秒發一次。
        // 女會員: 無論是否 VIP，一律限 8 秒發一次。
        if(!$user->isVIP()){
            $m_time = Message::select('created_at')->
            where('from_id', $user->id)->
            orderBy('created_at', 'desc')->first();
            if(isset($m_time)) {
                $diffInSecs = abs(strtotime(date("Y-m-d H:i:s")) - strtotime($m_time->created_at));
                if ($diffInSecs < 8) {
                    return back()->withErrors(['您好，由於系統偵測到您的發訊頻率太高(每 8 秒限一則訊息)。為維護系統運作效率，請降低發訊頻率。']);
                }
            }
        }
        else if($user->engroup == 2) {
            $m_time = Message::select('created_at')->
            where('from_id', $user->id)->
            orderBy('created_at', 'desc')->first();
            if(isset($m_time)) {
                $diffInSecs = abs(strtotime(date("Y-m-d H:i:s")) - strtotime($m_time->created_at));
                if ($diffInSecs < 8) {
                    return back()->withErrors(['您好，由於系統偵測到您的發訊頻率太高(每 8 秒限一則訊息)。為維護系統運作效率，請降低發訊頻率。']);
                }
            }
        }
        Message::post(auth()->id(), $payload['to'], $payload['msg']);

        //line通知訊息
        $to_user = User::findById($payload['to']);
        $line_notify_send = false;
        //收件夾設定通知
        $line_notify_chat_set_data = lineNotifyChatSet::select('line_notify_chat_set.*', 'line_notify_chat.name','line_notify_chat.gender')
            ->leftJoin('line_notify_chat', 'line_notify_chat.id', 'line_notify_chat_set.line_notify_chat_id')
            ->where('line_notify_chat.active', 1)
            ->where('line_notify_chat_set.user_id', $to_user->id)->where('line_notify_chat_set.deleted_at', null)->get();
        if(!empty($line_notify_chat_set_data)){
            $user_meta_data = UserMeta::select('user_meta.isWarned','user_meta.exchange_period_change','exchange_period_name.*')
                ->leftJoin('exchange_period_name','exchange_period_name.id','user_meta.exchange_period_change')
                ->where('user_id', auth()->id())
                ->get()->first();
            foreach($line_notify_chat_set_data as $row){
                if($row->gender==1 && $row->name == $user_meta_data->name){
                        $line_notify_send = true;
                        break;
                }else if($row->gender==2){
                    if($row->name == 'VIP' && $user->isVIP()){
                        $line_notify_send = true;
                        break;
                    }
                    if($row->name == '普通會員' && !$user->isVIP()){
                        $line_notify_send = true;
                        break;
                    }
                }else if($row->gender==0 && $row->name == '警示會員'){
                    //警示會員
                    //站方警示
                    $isAdminWarned = warned_users::where('member_id',auth()->id())->where('expire_date','>=',Carbon::now())->orWhere('expire_date',null)->get();
                    if($user_meta_data->isWarned==1 || !empty($isAdminWarned)){
                        $line_notify_send = true;
                        break;
                    }
                }
                else if($row->gender==0 && $row->name == '收藏會員'){
                    //收藏者通知
                    $line_notify_send = memberFav::where('member_id', $to_user->id)->where('member_fav_id', auth()->id())->first();
                    break;
                }
            }
        }

        if($to_user->line_notify_token != null && $to_user->line_notify_switch == 1 && $line_notify_send){
            $url = url('/dashboard/chat2/chatShow/'.auth()->id());
//            $url = app('bitly')->getUrl($url); //新套件用，如無法使用則先隱藏相關class

            //send notify
            $message = '您有一則訊息來自 '.$user->name.'。'.$url;
            User::sendLineNotify($to_user->line_notify_token, $message);

        }

        //發送訊息後後判斷是否需備自動封鎖
        // SetAutoBan::auto_ban(auth()->id());
        SetAutoBan::msg_auto_ban(auth()->id(), $payload['to'], $payload['msg']);
        return back()->with('message','發送成功');
    }

    public function chatview(Request $request)
    {
        $user = $request->user();
        $m_time = '';
        if (isset($user)) {
            $this->service->dispatchCheckECPay($this->userIsVip, $this->userIsFreeVip, $this->userVipData);
            $isVip = $user->isVip();
            /*編輯文案-檢舉大頭照-START*/
            $vip_member = AdminCommonText::where('alias','vip_member')->get()->first();
            /*編輯文案-檢舉大頭照-END*/

            /*編輯文案-檢舉大頭照-START*/
            $normal_member = AdminCommonText::where('alias','normal_member')->get()->first();
            /*編輯文案-檢舉大頭照-END*/

            /*編輯文案-檢舉大頭照-START*/
            $alert_member = AdminCommonText::where('alias','alert_member')->get()->first();
            /*編輯文案-檢舉大頭照-END*/

            /*編輯文案-檢舉大頭照-START*/
            $letter_normal_member = AdminCommonText::where('category_alias','letter_text')->where('alias','normal_member')->get()->first();
            /*編輯文案-檢舉大頭照-END*/

            /*編輯文案-檢舉大頭照-START*/
            $letter_vip = AdminCommonText::where('category_alias','letter_text')->where('alias','vip')->get()->first();
            /*編輯文案-檢舉大頭照-END*/
            return view('new.dashboard.chat')
                ->with('user', $user)
                ->with('m_time', $m_time)
                ->with('isVip', $isVip)
                ->with('vip_member', $vip_member->content)
                ->with('normal_member', $normal_member->content)
                ->with('alert_member', $alert_member->content)
                ->with('letter_normal_member', $letter_normal_member->content)
                ->with('letter_vip', $letter_vip->content);
        }
    }

    public function chatviewMore(Request $request)
    {
        $user_id = $request->uid;
        /**
         * function Message_new::allSendersAJAX(){
         *      $saveMessage = Message_new::newChatArrayAJAX(){
         *          foreach(){
         *              if($message->all_delete_count == 2) {
         *                  Message::deleteAllMessagesFromDB($message->to_id, $message->from_id);
         *              }
         *              if($message->all_delete_count == 1 && ($message->is_row_delete_1 == $message->to_id || $message->is_row_delete_2 == $message->to_id || $message->is_row_delete_1 == $message->from_id || $message->is_row_delete_2 == $message->from_id)) {
         *                  Message::deleteAllMessagesFromDB($message->to_id, $message->from_id);
         *              }
         *          }
         *      }
         *      return Message_new::sortMessages($saveMessages,$mm);
         *  }
         */
        $data = Message_new::allSendersAJAX($user_id, $request->isVip,$request->date);
        if (isset($data)) {
            if(!empty($data['date'])){
               //$date = $data['date'];
                unset($data['date']);
                //$data = array_values($data);
            }
            return response()->json(array(
                'status' => 1,
                'msg' => $data,
                'noVipCount' => Config::get('social.limit.show-chat')
            ), 200)
                ->header("Cache-Control", "no-cache, no-store, must-revalidate")
                ->header("Pragma", "no-cache")
                ->header("Last-Modified", gmdate("D, d M Y H:i:s")." GMT")
                ->header("Cache-Control", "post-check=0, pre-check=0", false)
                ->header("Expires", "Fri, 01 Jan 1990 00:00:00 GMT");
        } else {
            return response()->json(array(
                'status' => 2,
                'msg' => 'fail',
            ), 500);
        }
    }

    public function chatviewAll(Request $request)
    {
        $user_id = $request->uid;
        $data = Message::allSendersAJAX($user_id, $request->isVip);
        if (isset($data)) {
            return response()->json(array(
                'status' => 1,
                'msg' => $data
            ), 200)
                ->header("Cache-Control", "no-cache, no-store, must-revalidate")
                ->header("Pragma", "no-cache")
                ->header("Last-Modified", gmdate("D, d M Y H:i:s")." GMT")
                ->header("Cache-Control", "post-check=0, pre-check=0", false)
                ->header("Expires", "Fri, 01 Jan 1990 00:00:00 GMT");
        } else {
            return response()->json(array(
                'status' => 2,
                'msg' => 'fail',
            ), 500);
        }
    }

    public function disableNotice(Request $request)
    {
        $user_id = $request->id;
        $user = User::select('id', 'noticeRead')->where('id', $user_id)->get()->first();
        $user->noticeRead = 1;
        if ($user->save()) {
            return response()->json(array(
                'status' => 1,
                'msg' => 'ok',
            ), 200);
        } else {
            return response()->json(array(
                'status' => 2,
                'msg' => 'fail',
            ), 500);
        }
    }

    public function announceRead(Request $request)
    {
        $user_id = $request->uid;
        $announce_id = $request->aid;
        $exist = AnnouncementRead::where('user_id', $user_id)->where('announcement_id', $announce_id)->get()->first();
        if(!isset($exist)){
            $announceRead = new AnnouncementRead;
            $announceRead->user_id = $user_id;
            $announceRead->announcement_id = $announce_id;
            if ($announceRead->save()) {
                return response()->json(array(
                    'status' => 1,
                    'msg' => 'ok',
                ), 200);
            } else {
                return response()->json(array(
                    'status' => 2,
                    'msg' => 'fail',
                ), 500);
            }
        }
        return response()->json(array(
            'status' => 1,
            'msg' => 'already exists.',
        ), 200);
    }

    public function announcePost(Request $request)
    {
        $user = User::where('id', $request->uid)->first();
        $announceRead = AnnouncementRead::select('announcement_id')->where('user_id',$request->uid)->get();
        $announcement = AdminAnnounce::where('en_group', $user->engroup)->whereNotIn('id', $announceRead)->orderBy('sequence', 'desc')->get();
        //$announcement = $announcement->content;
        //$announcement = str_replace(PHP_EOL, '\n', $announcement);
        foreach ($announcement as &$a){
            $a = str_replace(array("\r\n", "\r", "\n"), "<br>", $a);
        }
        return response()->json($announcement);
    }

}
