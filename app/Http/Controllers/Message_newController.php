<?php

namespace App\Http\Controllers;

use App\Models\Blocked;
use App\Models\EssenceStatisticsLog;
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
use App\Models\ValueAddedService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Services\AdminService;
use DB;
use Session;
use App\Models\InboxRefuseSet;
use App\Models\Pr_log;
use YlsIdeas\FeatureFlags\Facades\Features;
use App\Models\ExchangePeriodName;
use App\Models\MessageErrorLog;

class Message_newController extends BaseController {
    public function __construct(UserService $userService) {
        parent::__construct();
        $this->service = $userService;
        $this->middleware('throttle:400,1');
        $this->middleware('pseudoThrottle:250,1');
    }

    // handle delete message
    public function deleteBetween(Request $request) {
        Message::deleteBetween($request->input('uid'), $request->input('sid'));

        return redirect('dashboard/chat');
    }

    public function deleteBetweenGET($uid, $sid) {

        Message::deleteBetween($uid, $sid);

        return redirect('dashboard/chat2/');
    }

    public function deleteBetweenGetAll($uid, $sid) {
        $ids = explode(',',$uid);
        foreach($ids as $id){
            Message::deleteBetween($sid,$id);
        }
        return redirect('dashboard/chat2/');
    }

    public function delete2Between(Request $request) {
        Message::deleteBetween($request->uid, $request->sid);
        return response()->json(['save' => 'ok']);
        //return redirect('dashboard/chat2/');
        //return redirect('dashboard/chat2/{randomNo?}');
    }

    public function deleteAll(Request $request) {
        Message::deleteAll($request->uid);
        return response()->json(['save' => 'ok']);
        //return redirect('dashboard/chat');
        //return redirect('dashboard/chat2/');
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
        $inbox_refuse_set = InboxRefuseSet::where('user_id', $user->id)->first();
        if(!$inbox_refuse_set)
        {
            $inbox_refuse_set = new InboxRefuseSet;
        }
        return view('new.dashboard.chatSet')
            ->with('line_notify_chat', $line_notify_chat)
            ->with('user_line_notify_chat_set', $user_line_notify_chat_set)
            ->with('can_message_alert', $user->can_message_alert)
            ->with('inbox_refuse_set', $inbox_refuse_set);
    }

    public function chatNoticeSet(Request $request) {
        $user = \View::shared('user');

        //line notify start
        if($user->line_notify_token!=null){
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
        }
        //line notify end
        
        //罐頭訊息警示 start
        if($user->engroup==1)
        {
            $user->can_message_alert = $request->can_message_alert;
            $user->save();
        }
        //罐頭訊息警示 end

        //拒收站內信start
        if($user->engroup==2)
        {
            $inbox_refuse_set = InboxRefuseSet::where('user_id', $user->id)->first();
            if(!$inbox_refuse_set)
            {
                $inbox_refuse_set = new InboxRefuseSet;
                $inbox_refuse_set->user_id = $user->id;
            }
            if($request->isRefused_vip_user)
                $inbox_refuse_set->isrefused_vip_user = $request->isRefused_vip_user;
            else
                $inbox_refuse_set->isrefused_vip_user = 0;
            if($request->isRefused_common_user)
                $inbox_refuse_set->isrefused_common_user = $request->isRefused_common_user;
            else
                $inbox_refuse_set->isrefused_common_user = 0;
            if($request->isRefused_warned_user)
                $inbox_refuse_set->isrefused_warned_user = $request->isRefused_warned_user;
            else
                $inbox_refuse_set->isrefused_warned_user = 0;
            $inbox_refuse_set->refuse_pr = $request->refuse_PR;
            /*
            $inbox_refuse_set->refuse_canned_message_pr = $request->refuse_canned_message_PR;
            */
            $inbox_refuse_set->refuse_register_days = $request->refuse_register_days;
            $inbox_refuse_set->save();
        }
        //拒收站內信end

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


    public function postChat(Request $request, $isCalledByEvent = false)
    {
//        $banned = banned_users::where('member_id', Auth::user()->id)
//            ->whereNotNull('expire_date')
//            ->orderBy('expire_date', 'asc')->get()->first();
//        if(isset($banned)){
//            $date = \Carbon\Carbon::parse($banned->expire_date);
//            return view('errors.User-banned-with-message',
//                ['banned' => $banned,
//                 'days' => $date->diffInDays() + 1]);
//        }
        $justEchoAndExit = false;
        if($request->ajax() && $request->file()) $justEchoAndExit = true;
        
        $payload = $request->all();
        
        $user = Auth::user();
        
        $error_log_arr = [
            'from_id'=>$payload['from']
            ,'to_id'=>  $payload['to']
            ,'content'=>$payload['msg']
            ,'pic'=>json_encode($request->file('images')??[])
            ,'error_from'=>'Message_newController@postChat'
        ];
        
        if($payload['from']!=$user->id) {
            $already_logout_error_msg='您已登出或基於帳號安全由系統自動登出，請重新登入。';
            Session::flush();
            $request->session()->forget('announceClose');
            Auth::logout();            
            if($isCalledByEvent){
                MessageErrorLog::create(array_merge([
                    'error'=>$already_logout_error_msg
                    ,'error_return_data'=>json_encode(array('error' => 401, 'content' => $already_logout_error_msg))
                ],$error_log_arr));
                return array('error' => 401,
                    'content' => $already_logout_error_msg);
            }  
            MessageErrorLog::create(array_merge([
                'error'=>$already_logout_error_msg
                ,'error_back_url'=>url()->previous()
            ],$error_log_arr));
            return back()->withErrors([$already_logout_error_msg]);             
        }
        
        $to_user = User::findById($payload['to']);
        $forbid_msg_data = UserService::checkNewSugarForbidMsg($to_user,$user);

        if($forbid_msg_data) {
            $new_sugar_error_msg='新進甜心只接收 vip 信件，'.$forbid_msg_data['user_type_str'].'會員要於 '.$forbid_msg_data['end_date'].' 後方可發信給這位女會員';
            if($isCalledByEvent){
                MessageErrorLog::create(array_merge([
                    'error'=>$new_sugar_error_msg
                    ,'error_return_data'=>json_encode(array('error' => 1, 'content' => $new_sugar_error_msg))
                ],$error_log_arr));               
                return array('error' => 1,
                    'content' => $new_sugar_error_msg);
            }  
            MessageErrorLog::create(array_merge([
                'error'=>$new_sugar_error_msg
                ,'error_back_url'=>url()->previous()
            ],$error_log_arr));          
            return back()->withErrors([$new_sugar_error_msg]);           
        }
        
        if(!isset($payload['msg']) && !$request->hasFile('images')){
            if($isCalledByEvent){
                MessageErrorLog::create(array_merge([
                    'error'=>'請勿僅輸入空白！'
                    ,'error_return_data'=>json_encode(array('error' => 1, 'content' => '請勿僅輸入空白！'))
                ],$error_log_arr));                 
                return array('error' => 1,
                    'content' => '請勿僅輸入空白！');
            }
            MessageErrorLog::create(array_merge([
                'error'=>'請勿僅輸入空白'
                ,'error_back_url'=>url()->previous()
            ],$error_log_arr));            
            return back()->withErrors(['請勿僅輸入空白！']);
        }
        //$user = Auth::user();
        // 非 VIP: 一律限 8 秒發一次。
        // 女會員: 無論是否 VIP，一律限 8 秒發一次。
        $m_period = 0;
        $target_str = '';
        if($user->engroup == 2 || !$user->isVipOrIsVvip()) {
            $m_time = Message::select('created_at','to_id')->
            where('from_id', $user->id)->
            orderBy('created_at', 'desc')->first();
            if(isset($m_time)) {
                if($m_time->to_id==$payload['to']) {
                    $target_str = '同一會員';
                    $m_period = 1;
                    $diffInSecs = abs(strtotime(date("Y-m-d H:i:s")) - strtotime($m_time->created_at));
                } 
                else {
                    $target_str = '不同會員';
                    $m_period = 5;
                    $diffInSecs = abs(strtotime(date("Y-m-d H:i:s")) - strtotime($m_time->created_at)); 
                }
                
                if ($diffInSecs < $m_period) {
                    if($isCalledByEvent){
                        MessageErrorLog::create(array_merge([
                            'error'=>'您好，由於系統偵測到您的發訊頻率太高('.$target_str .'每 '.$m_period.' 秒限一則訊息)。為維護系統運作效率，請降低發訊頻率。'
                            ,'error_return_data'=>json_encode(array('error' => 1, 'content' => '您好，由於系統偵測到您的發訊頻率太高('.$target_str .'每 '.$m_period.' 秒限一則訊息)。為維護系統運作效率，請降低發訊頻率。'))
                        ],$error_log_arr));                                                
                        return array('error' => 1,
                                    'content' => '您好，由於系統偵測到您的發訊頻率太高('.$target_str .'每  '.$m_period.'  秒限一則訊息)。為維護系統運作效率，請降低發訊頻率。');
                    }
                    MessageErrorLog::create(array_merge([
                        'error'=>'您好，由於系統偵測到您的發訊頻率太高('.$target_str .'每  '.$m_period.'  秒限一則訊息)。為維護系統運作效率，請降低發訊頻率。'
                        ,'error_back_url'=>url()->previous()
                    ],$error_log_arr));                     
                    return back()->withErrors(['您好，由於系統偵測到您的發訊頻率太高('.$target_str .'每  '.$m_period.'  秒限一則訊息)。為維護系統運作效率，請降低發訊頻率。']);
                }                
            }
        }

        if(!is_null($request->file('images')) && count($request->file('images'))){
            //上傳訊息照片
            $messageInfo = Message::create([
                'room_id' => Message::checkMessageRoomBetween($user->id, $payload['to']),
                'from_id' => $user->id,
                'to_id' => $payload['to'],
                'client_id' => $payload['client_id'],
                'parent_msg' => ($payload['parent']??null),
                'parent_client_id' => ($payload['parent_client']??null),
                'views_count_quota' => ($payload['views_count_quota']??0),
                'show_time_limit' => ($payload['show_time_limit']??0),
                'is_truth' => ($payload['is_truth'] ?? 0)?intval(Message::existIsTrueQuotaByFromUser($user)):0,//$payload['is_truth'] ?? 0,
                'chat_with_admin' => $payload['chat_with_admin']??0,
            ]);
            \Log::info($messageInfo);
            $messagePosted = $this->message_pic_save($messageInfo->id, $request->file('images'));
            $this->addEssenceStatisticsLog(['user_id'=>$user->id, 'to_id'=>$payload['to'], 'message_data'=>$messageInfo]);
        
            $todayTop3Msgs = $user->message_sent()->where('sys_notice','<>', 1)->whereDate('created_at', Carbon::today())->orderBy('created_at')->take(3)->get();
            if($todayTop3Msgs->where('id',$messagePosted->id)->count()) {
                $messagePosted->encodeImages('Message_newController@postChat');
            }
        }else {
            $postArr = $payload;
            $postArr['from_id'] = $user->id;
            $messagePosted = Message::postByArr($postArr);
            $this->addEssenceStatisticsLog(['user_id'=>$user->id, 'to_id'=>$payload['to'], 'message_data'=>$messagePosted]);
        }

        //罐頭訊息
        
        if ($user->engroup == 1) {
            if($messagePosted->pic){

                $isCanPicMessage = UserService::checkCanPicMessageWithGreetingRate($user, $messagePosted);
            }
            else {
                $isCanMessage = UserService::checkCanMessageWithGreetingRate($user, $to_user->id, $payload['msg']);
            }
            
            if ($isCanMessage??null) {
                Message::where('id', $messagePosted->id)->update(['is_can' => 1]);
                if($to_user->show_can_message != 1) {
                    MessageErrorLog::create(array_merge([
                        'error'=>'您好，此位女會員設定屏蔽罐頭訊息，如發罐頭訊息給她會被屏蔽'
                        ,'error_return_data'=>json_encode(array('error' => 2, 'content' => '您好，此位女會員設定屏蔽罐頭訊息，如發罐頭訊息給她會被屏蔽'))
                    ],$error_log_arr));                                        
                    return array('error' => 2,
                        'content' => '您好，此位女會員設定屏蔽罐頭訊息，如發罐頭訊息給她會被屏蔽');
                }
            }
            
            if ($isCanPicMessage??null) {
                $messagePosted->is_can=1;
                $messagePosted->save();
            }            
        }

        //line通知訊息
        //$to_user = User::findById($payload['to']);
        $line_notify_send = false;
        //收件夾設定通知
        $line_notify_chat_set_data = lineNotifyChatSet::select('line_notify_chat_set.*', 'line_notify_chat.name','line_notify_chat.gender')
            ->leftJoin('line_notify_chat', 'line_notify_chat.id', 'line_notify_chat_set.line_notify_chat_id')
            ->where('line_notify_chat.active', 1)
            ->where('line_notify_chat_set.user_id', $to_user->id)
            ->get();

        /*if(!empty($line_notify_chat_set_data)){
            $user_meta_data = UserMeta::select('user_meta.isWarned', 'exchange_period_name.*')
                ->leftJoin('users', 'users.id', 'user_meta.user_id')
                ->leftJoin('exchange_period_name', 'exchange_period_name.id', 'users.exchange_period')
                ->where('user_id', $user->id)
                ->get()->first();
            foreach($line_notify_chat_set_data as $row){
                if($row->gender==1 && $row->name == $user_meta_data->name){
                        $line_notify_send = true;
                        break;
                }
                else if($row->gender==2){
                    if($row->name == 'VIP' && $user->isVIP()){
                        $line_notify_send = true;
                        break;
                    }
                    if($row->name == '普通會員' && !$user->isVIP()){
                        $line_notify_send = true;
                        break;
                    }
                }
                else if($row->gender==0 && $row->name == '警示會員'){
                    //警示會員
                    //站方警示
                    $isAdminWarned = warned_users::where('member_id',$user->id)
                        ->where('expire_date',null)
                        ->orWhere('expire_date','>=',Carbon::now())
                        ->get();
                    if( $user_meta_data->isWarned==1 || count($isAdminWarned)>0 ){
                        $line_notify_send = true;
                        break;
                    }
                }
                else if($row->gender==0 && $row->name == '收藏會員' && $to_user->isVip()){
                    //收藏者通知
                    $line_notify_send = memberFav::where('member_id', $to_user->id)->where('member_fav_id', $user->id)->first();
                    if($line_notify_send) {break;}
                }
                else if($row->gender==0 && $row->name == '誰來看我' && $to_user->isVip()){
                    //誰來看我通知
                    $line_notify_send = Visited::where('visited_id', $to_user->id)->where('member_id', $user->id)->first();
                    if($line_notify_send) {break;}
                }
                else if($row->gender==0 && $row->name == '收藏我的會員' && $to_user->isVip()){
                    //收藏我的會員通知
                    $line_notify_send = memberFav::where('member_id', $user->id)->where('member_fav_id', $to_user->id)->first();
                    if($line_notify_send) {break;}
                }
                else{
                    $line_notify_send = false;
                    continue;
                }
            }

            //站方封鎖
            $banned = banned_users::where('member_id', $user->id)->get()->first();
            if( isset($banned) && ( $banned->expire_date==null || $banned->expire_date >= \Carbon\Carbon::now() )){
                $line_notify_send = false;
            }

            //檢查封鎖
            $checkIsBlock = Blocked::isBlocked($to_user->id, $user->id);
            if($checkIsBlock){
                $line_notify_send = false;
            }

            //對方的過濾篩選條件
            $inbox_refuse_set = InboxRefuseSet::where('user_id', $to_user->id)->first();
            if($inbox_refuse_set)
            {
                if($inbox_refuse_set->isrefused_vip_user)
                {
                    if($user->isVip())
                    {
                        $line_notify_send = false;
                    }
                }
                if($inbox_refuse_set->isrefused_common_user)
                {   
                    if(!$user->isVip())
                    {
                        $line_notify_send = false;
                    }
                }
                if($inbox_refuse_set->isrefused_warned_user)
                {
                    $usermeta = UserMeta::where('user_id',$user->id)->first();
                    if($usermeta->isWarned)
                    {
                        $line_notify_send = false;
                    }
                    $warneduser = DB::table('warned_users')->where('member_id',$user->id)->first();
                    if($warneduser??false)
                    {
                        $line_notify_send = false;
                    }
                }
                if($inbox_refuse_set->refuse_pr != -1)
                {
                    $pr = Pr_log::where('user_id',$user->id)->first();
                    if(!$pr)
                    {
                        $line_notify_send = false;
                    }
                    else
                    {
                        if(!$pr->pr)
                        {
                            $line_notify_send = false;
                        }
                        elseif($pr->pr == '無')
                        {
                            $line_notify_send = false;
                        }
                        else
                        {
                            if($pr->pr < $inbox_refuse_set->refuse_pr)
                            {
                                $line_notify_send = false;
                            }
                        }
                    }
                }
                if($inbox_refuse_set->refuse_canned_message_pr != -1)
                {
                    $can_pr = UserService::computeCanMessagePercent_7($user->id);
                    $can_pr = trim($can_pr,'%');
                    if($can_pr > $inbox_refuse_set->refuse_canned_message_pr)
                    {
                        $line_notify_send = false;
                    }

                }
                if($inbox_refuse_set->refuse_register_days != 0)
                {
                    $rtime = Carbon::now()->subDays($inbox_refuse_set->refuse_register_days);
                    if($user->created_at > $rtime)
                    {
                        $line_notify_send = false;
                    }
                }
            }
            //對方的過濾篩選條件
        }*/

        $line_notify_chat_id_ary=$line_notify_chat_set_data->pluck('line_notify_chat_id')->toArray();
        $line_notify_send = false;
        $into_area='no set';
        if($to_user->engroup==1){
            if(in_array(1, $line_notify_chat_id_ary) && $user->exchange_period==1){
                $line_notify_send = true;
            }
            if(in_array(2, $line_notify_chat_id_ary) && $user->exchange_period==2){
                $line_notify_send = true;
            }
            if(in_array(3, $line_notify_chat_id_ary) && $user->exchange_period==3){
                $line_notify_send = true;
            }
            if(in_array(8, $line_notify_chat_id_ary) && memberFav::where('member_id', $to_user->id)->where('member_fav_id', $user->id)->first()){
                $line_notify_send = true;
            }
        }else{
            if(in_array(5, $line_notify_chat_id_ary) && ($user->isVip() || $user->isVVIP()) ){
                $line_notify_send = true;
            }
            if(in_array(6, $line_notify_chat_id_ary) && ($user->isVip() || $user->isVVIP()) ){
                $line_notify_send = true;
            }
            if(in_array(8, $line_notify_chat_id_ary) && memberFav::where('member_id', $to_user->id)->where('member_fav_id', $user->id)->first()){
                $line_notify_send = true;
            }
        }

        //封鎖帳號＆警示帳號 notify訊息提示
        $line_notify_send_blockOrWarned=false;

        //正被警示
        $isWarned = warned_users::where('member_id', $user->id)->where('expire_date', null)->orWhere('expire_date','>',Carbon::now() )->where('member_id', $user->id)->orderBy('created_at','desc')->first();
        $has_message_after_warned=false;
        if($isWarned){
            $has_message_after_warned = Message::where([['to_id', $to_user->id], ['from_id', $user->id]])->orWhere([['to_id', $user->id], ['from_id', $to_user->id]])->where('created_at','>',$isWarned->created_at)->get()->count();
        }
        //正被封鎖
        $isBanned = banned_users::where('member_id', $user->id)->where('expire_date', null)->orWhere('expire_date','>',Carbon::now() )->where('member_id', $user->id)->orderBy('created_at','desc')->first();
        $has_message_after_banned=false;
        if($isBanned){
            $has_message_after_banned = Message::where([['to_id', $to_user->id], ['from_id', $user->id]])->orWhere([['to_id', $user->id], ['from_id', $to_user->id]])->where('created_at','>',$isBanned->created_at)->get()->count();
        }

        if(in_array(11, $line_notify_chat_id_ary) && ( $has_message_after_banned==1)){
            $line_notify_send_blockOrWarned = true;
            $url = url('/dashboard/chat2');
            $message = '與您通訊的 '.$user->name.' 已經被站方封鎖。對話記錄將移到封鎖信件夾，請您再去檢查，如果您們已經交換聯絡方式，請多加注意。'.$url;
        }
        if(in_array(7, $line_notify_chat_id_ary) && $has_message_after_warned==1){
            $line_notify_send_blockOrWarned = true;
            $url = url('/dashboard/chat2');
            $message = '與您通訊的 '.$user->name.' 已經被站方警示。對話記錄將移到警示會員信件夾，請您再去檢查，如果您們已經交換聯絡方式，請多加注意。 '.$url;
        }
        //該帳號是否被user加入封鎖名單
        $is_blocked_user=Blocked::where('member_id',$to_user->id)->where('blocked_id',$user->id)->get()->count();

        if($to_user->line_notify_token != null && $line_notify_send_blockOrWarned){
            User::sendLineNotify($to_user->line_notify_token, $message);
        }
        else if($to_user->line_notify_token != null && $line_notify_send && $is_blocked_user==0 && !$isBanned){

            $url = url('/dashboard/chat2/chatShow/'.$user->id);
            //$url = app('bitly')->getUrl($url); //新套件用，如無法使用則先隱藏相關class

            //send notify
            $message = '您有一則訊息來自 '.$user->name.'。'.$url;
            User::sendLineNotify($to_user->line_notify_token, $message);
        }

        //發送訊息後後判斷是否需備自動封鎖
        // SetAutoBan::auto_ban(auth()->id());
        //SetAutoBan::msg_auto_ban($user->id, $payload['to'], $payload['msg']);
        if($isCalledByEvent && gettype($isCalledByEvent) == "boolean") {
            return $messagePosted;
        }
        
        if($justEchoAndExit) {
            echo '1';
            exit;
        }          
        
        \App\Events\Chat::dispatch($messagePosted, $request->from, $request->to);

        if ($isCanPicMessage??null) {
                if($to_user->show_can_message != 1) {
                    MessageErrorLog::create(array_merge([
                        'error'=>'您好，此位女會員設定屏蔽罐頭訊息，如發罐頭訊息給她會被屏蔽'
                        ,'error_return_data'=>json_encode(array('is_can' => 1, 'content' => '您好，此位女會員設定屏蔽罐頭訊息，如發罐頭訊息給她會被屏蔽'))
                    ],$error_log_arr));                    
                    return array('is_can' => 1,
                        'content' => '您好，此位女會員設定屏蔽罐頭訊息，如發罐頭訊息給她會被屏蔽');
                }
            }
        MessageErrorLog::create(array_merge([
            'error_back_url'=>url()->previous()
        ],$error_log_arr));       
        return back();
    }

    public function chatview(Request $request)
    {
        $user = $request->user();
        $m_time = '';
        if (isset($user)) {
            if($user->id == 1049) { set_time_limit(0); }
            $this->service->dispatchCheckECPay($this->userIsVip, $this->userIsFreeVip, $this->userVipData);
            $valueAddedServiceData_hideOnline = ValueAddedService::getData($user->id, 'hideOnline');
            if($valueAddedServiceData_hideOnline){
                $this->service->dispatchCheckECPayForValueAddedService('hideOnline', $valueAddedServiceData_hideOnline);
            }
            $valueAddedServiceData_VVIP = ValueAddedService::getData($user->id, 'VVIP');
            if($valueAddedServiceData_VVIP){
                $this->service->dispatchCheckECPayForValueAddedService('VVIP', $valueAddedServiceData_VVIP);
            }
            $isVip = ($user->isVip()||$user->isVVIP());
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

            $letter_vvip = AdminCommonText::where('category_alias','letter_text')->where('alias','vvip')->get()->first();
            /*編輯文案-檢舉大頭照-END*/

            $data_all = Message_new::allSendersAJAX($user->id, $user->isVip(),'all');
            $message_with_user_count = (is_countable($data_all) && array_get($data_all,'0')!=='No data') ? count($data_all) : 0;

            $data['exchange_period_name']   =   ExchangePeriodName::get();
            // $data['total_message']  =   
            // \App\Models\Message::unread($user_id)

            return view('new.dashboard.chat',$data)
                ->with('user', $user)
                ->with('message_with_user_count', $message_with_user_count)
                ->with('m_time', $m_time)
                ->with('isVip', $isVip)
                ->with('vip_member', $vip_member->content)
                ->with('normal_member', $normal_member->content)
                ->with('alert_member', $alert_member->content)
                ->with('letter_normal_member', $letter_normal_member->content)
                ->with('letter_vip', $letter_vip->content)
                ->with('letter_vvip', $letter_vvip->content);
                ;
        }
    }

    public function chatviewMore(Request $request)
    {
        // $user = new User;
        // $can_pr = $user->getSpamMessagePercentIn7Days(15600);
        // dd($can_pr);
        $user = Auth::user();
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
        if(is_array($data) && $data != ['No data'])
        {
            $isUserVip = false; 
            if($user->isVipOrIsVvip()) {
                $isUserVip = true; 
            }
            foreach($data as $key => $item) {
                $visitor = User::find($item['user_id']);
                $data[$key]['isblur'] = \App\Services\UserService::isBlurAvatar($visitor, $user);
                if($isUserVip)
                    $data[$key]['isOnline'] = $visitor->isOnline();
                else $data[$key]['isOnline'] = -1;
            }
            //過濾篩選條件
            $inbox_refuse_set = InboxRefuseSet::where('user_id', $user->id)->first();
            if($inbox_refuse_set)
            {
                if($inbox_refuse_set->isrefused_vip_user)
                {
                    $count = 0;
                    foreach ($data as $d)
                    {
                        if($d['isVip'])
                        {
                            unset($data[$count]);
                        }
                        $count = $count+1;
                    }
                    $data = array_values($data);
                }
                if($inbox_refuse_set->isrefused_common_user)
                {
                    $count = 0;
                    foreach ($data as $d)
                    {
                        if((!$d['isVip']) && (!$d['isVVIP']))
                        {
                            unset($data[$count]);
                        }
                        $count = $count+1;
                    }
                    $data = array_values($data);
                }
                if($inbox_refuse_set->isrefused_warned_user)
                {
                    $count = 0;
                    foreach ($data as $d)
                    {
                        if($d['isWarned'])
                        {
                            unset($data[$count]);
                        }
                        $count = $count+1;
                    }
                    $data = array_values($data);
                }
                if($inbox_refuse_set->refuse_pr != -1)
                {
                    $count = 0;
                    foreach ($data as $d)
                    {
                        if($d['from_id'] == $user_id) {
                            continue;
                        }      
                        $target = Pr_log::where('user_id', $d['user_id'])->first();
                        if(!$target) {
                            continue;
                        }
                        $pr = $target->pr;
                        if(!is_null($pr))
                        {
                            if($pr == '無')
                            {
                                unset($data[$count]);
                            }
                            if($pr < $inbox_refuse_set->refuse_pr)
                            {
                                unset($data[$count]);
                            }
                        }
                        else
                        {
                            unset($data[$count]);
                        }
                        $count = $count+1;
                    }
                    $data = array_values($data);
                }
                /*
                if($inbox_refuse_set->refuse_canned_message_pr != -1)
                {
                    $count = 0;
                    foreach ($data as $d)
                    {
                        if(Features::accessible('inbox-7-days')){
                            $can_pr = UserService::computeCanMessagePercent_7($d['user_id']);
                        }else{
                            $user = new User;
                            $can_pr = $user->getSpamMessagePercentIn7Days($d['user_id']);
                        }                     
                        $can_pr = trim($can_pr,'%');
                        if($can_pr > $inbox_refuse_set->refuse_canned_message_pr)
                        {
                            unset($data[$count]);
                        }
                        $count = $count+1;
                    }
                    $data = array_values($data);
                }
                */
                if($inbox_refuse_set->refuse_register_days != 0)
                {
                    $rtime = Carbon::now()->subDays($inbox_refuse_set->refuse_register_days);
                    $count = 0;
                    foreach ($data as $d)
                    {
                        $registdate = User::where('id',$d['user_id'])->first()->created_at;
                        if($registdate > $rtime)
                        {
                            unset($data[$count]);
                        }
                        $count = $count+1;
                    }
                    $data = array_values($data);
                }
            }
            //過濾篩選條件
        }
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
            $a = str_replace('LINE_ICON', AdminService::$line_icon_html, $a);
            $a = str_replace('|$lineIcon|', AdminService::$line_icon_html, $a);         
            $a = str_replace('|$responseTime|', date("Y-m-d H:i:s"), $a);
            $a = str_replace('|$reportTime|', date("Y-m-d H:i:s"), $a);
            $a = str_replace('NOW_TIME', date("Y-m-d H:i:s"), $a);             
        }
        return response()->json($announcement);
    }

    public function message_pic_save($msg_id, $images)
    {
        if($files = $images)
        {
            $images_ary=array();
            foreach ($files as $key => $file) {
                $now = Carbon::now()->format('Ymd');
                $input['imagename'] = $now . rand(100000000,999999999) . '.' . $file->getClientOriginalExtension();

                $rootPath = public_path('/img/Message');
                $tempPath = $rootPath . '/' . substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/';

                if(!is_dir($tempPath)) {
                    File::makeDirectory($tempPath, 0777, true);
                }

                $destinationPath = '/img/Message/'. substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/' . $input['imagename'];

                $img = Image::make($file->getRealPath());
                $img->resize(400, 600, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($tempPath . $input['imagename']);

                //整理images
                //$images_ary[$key]= $destinationPath;
                $images_ary[$key]['origin_name']= $file->getClientOriginalName();
                $images_ary[$key]['file_path']= $destinationPath;

            }
            return Message::updateOrCreate(['id'=> $msg_id], ['pic'=>json_encode($images_ary)]);
        }
    }

    public function message_pic_delete($msg_id)
    {
        $messageInfo=Message::find($msg_id);
        if($messageInfo){
            $getPicList= json_decode($messageInfo->pic,true);
            foreach ($getPicList as $key => $pic){
                if (file_exists(public_path().$pic['file_path'])) {
                    unlink(public_path().$pic['file_path']);
                }
            }
            $messageInfo->delete();
        }
    }

    public function deleteMsgByUser($msgid)
    {
        $messageInfo=Message::find($msgid);
        if($messageInfo){
            $getPicList= json_decode($messageInfo->pic,true);
            if(!is_null($getPicList) && count($getPicList)){
                foreach ($getPicList as $key => $pic){
                    if (file_exists(public_path().$pic['file_path'])) {
                        unlink(public_path().$pic['file_path']);
                    }
                }
            }
            $messageInfo->delete();
            return response()->json(['status' => 'ok','msg'=>'刪除成功']);
            //return back()->with('message','刪除成功');

        }else{
            return response()->json(['status' => 'fail','msg'=>'刪除失敗,找不到該訊息']);
            //return back()->withErrors(['刪除失敗,找不到該訊息']);
        }
    }

    public function getUnread($user_id){
        return \App\Models\Message::unread($user_id);
    }
    
    public function unsendChat(Request $request) {
        $isAjax = false;
        if($request->ajax()) $isAjax = true;        
        $payload = $request->all();
        $unsend_id = $payload['unsend_msg']??null;
        $unsend_client_id = $payload['unsend_msg_client']??null;
        $user = Auth::user();

        if(( $user->isVip() || $user->isVVIP() ) &&  !isset($user->banned ) && !isset($user->implicitlyBanned)){
            if($unsend_id)
                $msg = Message::find($unsend_id);
            else if($unsend_client_id)
                $msg = Message::where('client_id',$unsend_client_id)->first();
            if($msg) {
                $msg->unsend = 1;
                $msg->save();
                $msg->delete();
                if($isAjax) {
                    return event(new \App\Events\ChatUnsend($msg));
                }
                else return back();
            }
            else  return back();
        }
        else {
            if($isAjax){
                return array('error' => 1,
                            'content' => '非VIP無法收回訊息。');
            }
            return back()->withErrors(['非VIP無法收回訊息。']);       
        }
        
    }
    
    public function increaseViewsCount(Request $request) {
        $req_id = $request->id;
        $req_client_id = $request->client_id;
        
        if($req_id) {
            $msg = Message::find($req_id);
        }
        else if($req_client_id) {
            $msg = Message::where('client_id',$req_client_id)->first();
        }
        else {
            return;
        }
        
        $msg->views_count = $msg->views_count+1;
        return $msg->save();
    }

    public function addEssenceStatisticsLog($data)
    {
        $data=[
            'user_id'=>$data['user_id'],
            'essence_posts_id'=> session()->get('via_by_essence_article_enter'),
            'message_client_id'=>array_get($data,'message_data.client_id'),
            'message_send_time'=>array_get($data,'message_data.created_at'),
        ];

        $user=User::findById($data['user_id']);
        if($user->engroup==2 && !is_null(session()->get('via_by_essence_article_enter'))){
            EssenceStatisticsLog::addToLog($data);
            session()->forget('via_by_essence_article_enter');

        }
    }

    public function deleteMutipleMessages(Request $request) {

        $user=auth()->user();

        $oneWeekList=Message::showAllMsgWithinTimeRange($user->id, 'oneWeek');
        foreach ($oneWeekList as $key =>$value){
            $item_user=\App\Models\User::findById($value['user_id']);
            $oneWeekList[$key]['isBlurAvatar']= \App\Services\UserService::isBlurAvatar($item_user, $user);
        }

        $twoWeekList = collect($oneWeekList)->filter(function($item){
            return $item['last_msg_created_at']<=date('Y-m-d H:i:s', strtotime('-2 weeks'));
        });
        $oneMonthList = collect($oneWeekList)->filter(function($item){
            return $item['last_msg_created_at']<=date('Y-m-d H:i:s', strtotime('-1 months'));
        });

        return view('/new/dashboard/deleteMutipleMessages',compact('oneWeekList', 'twoWeekList', 'oneMonthList'))
            ->with('user', $user);
    }

    public function deleteBetweenMsg_multiple(Request $request) {
        $uid= auth()->user()->id;
        $sList=$request->sList;
        foreach ($sList as $key => $sid){
            Message::deleteBetween($uid, $sid);
            //logger('deleteBetween=>$uid'.$uid. ' $sid=>'.$sid);
        }
        return response()->json(['save' => 'ok']);
    }

    public function ToggleShowCanMessage(Request $request)
    {
        
        $user = User::where('id', $request->user_id)->first();
        $origin_show_can_message = $user->show_can_message;
        $user->show_can_message = !$origin_show_can_message;
        $user->save();
        
        return http_response_code(204);
    }
}
