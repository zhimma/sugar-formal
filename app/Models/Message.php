<?php

namespace App\Models;

use Auth;
use App\Models\User;
use App\Models\Blocked;
use App\Models\SimpleTables\banned_users;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\MessageEmail;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Message extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'message';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'from_id',
        'to_id',
        'content',
        'read'
    ];

    // handle delete Message
    public static function deleteBetween($uid, $sid) {
        $message = Message::where([['to_id', $uid], ['from_id', $sid]])->orWhere([['to_id', $sid], ['from_id', $uid]])->orderBy('created_at', 'desc')->first();

        if(!isset($message)){
            return false;
        }
        if($message->is_row_delete_1 == 0) {
            Message::deleteRowMessage($uid, $sid, 0);
        }
        else if($message->is_row_delete_2 == 0) {
            Message::deleteRowMessage($uid, $sid, 1);
        }
    }

    public static function deleteAll($uid) {
        $message = Message::where([['to_id', $uid], ['from_id', '!=', $uid]])->orWhere([['from_id', $uid], ['to_id', '!=',$uid]])->orderBy('created_at', 'desc')->get();

        for($i = 0; $i < $message->count(); $i++) {
            //echo $message[$i]->temp_id . '<br/>';
            //echo $uid . '<br/>';
            if($message[$i]->temp_id != $uid) {
                $message[$i]->all_delete_count += 1;
                $message[$i]->temp_id = $uid;
                $message[$i]->save();
            }
        }
    }

    public static function deleteSingleMessageFromDB($uid, $sid, $ct_time, $content) {
        return Message::where([['to_id', $uid], ['from_id', $sid], ['created_at', $ct_time], ['content', $content]])->orWhere([['to_id', $sid], ['from_id', $uid], ['created_at', $ct_time], ['content', $content]])->delete();
    }

    public static function deleteAllMessagesFromDB($uid, $sid) {
        return Message::where([['to_id', $uid],['from_id', $sid]])->orWhere([['to_id', $sid],['from_id', $uid]])->delete();
    }

    public static function deleteRowMessagesFromDB($uid, $sid) {
        return Message::where('is_row_delete_1', '!=', 0)->where('is_row_delete_2', '!=', 0)->where([['to_id', $uid], ['from_id', $sid]])->orWhere([['to_id', $sid], ['from_id', $uid]])->delete();
    }

    public static function deleteRowMessage($uid, $sid, $step) {
        $message = Message::where([['to_id', $uid], ['from_id', $sid]])->orWhere([['to_id', $sid], ['from_id', $uid]])->get();

        for($i = 0 ; $i < $message->count() ; $i++) {
            if($step == 0) {
                $message[$i]->is_row_delete_1 = $uid;
            }
            else if($step == 1) {
                $message[$i]->is_row_delete_2 = $uid;
                Message::deleteRowMessagesFromDB($uid, $sid);
            }
            $message[$i]->save();
        }
    }

    public static function deleteSingleMessage($message, $uid, $sid, $ct_time, $content, $step) {
        if($step == 0) {
            $message->is_single_delete_1 = $uid;
        }
        else if($step == 1) {
            $message->is_single_delete_2 = $uid;
            Message::deleteSingleMessageFromDB($uid, $sid, $ct_time, $content);
        }
        $message->save();
    }

    public static function deleteSingle($uid, $sid, $ct_time, $content) {
        $message = Message::where([['to_id', $uid], ['from_id', $sid], ['created_at', $ct_time], ['content', $content]])->orWhere([['to_id', $sid], ['from_id', $uid], ['created_at', $ct_time], ['content', $content]])->first();
        //echo json_encode($message);
        //echo 'uid = ' . $uid . ' sid = ' . $sid;
        if(!isset($message)){
            return false;
        }
        if($message->is_single_delete_1 == 0) {
            Message::deleteSingleMessage($message, $uid, $sid, $ct_time, $content, 0);
        }
        else if($message->is_single_delete_2 == 0) {
            Message::deleteSingleMessage($message, $uid, $sid, $ct_time, $content, 1);
        }
    }

    public static function reportMessage($id, $content) {
        $message = Message::where('id', $id)->first();
        $message->isReported = 1;
        $message->reportContent = $content;
        $message->save();
    }

    public static function isAdminMessage($content) {
        if($content == '系統通知: 車馬費邀請') return true;
        return false;
    }

    // show message setting
    public static function onlyShowVip($user, $msgUser, $isVip = false) {
        //return $user->isVip() && !$msgUser->isVip() && $user->meta_()->notifhistory == '顯示VIP會員信件';
        return $isVip && !$msgUser->isVip() && $user->meta_('notifhistory')->notifhistory == '顯示VIP會員信件';
    }

    public static function showNoVip($user, $msgUser, $isVip = false) {
        //return $user->isVip() && !$msgUser->isVip() && ($user->meta_()->notifhistory == '顯示普通會員信件' || $user->meta_()->notifhistory == '');
        return $isVip && !$msgUser->isVip() && ($user->meta_('notifhistory')->notifhistory == '顯示普通會員信件' || $user->meta_()->notifhistory == '');
    }

    public static function getLastSender($uid, $sid) {
        $lastSender = Message::latestMessage($uid, $sid);

        return $lastSender;
    }

    public static function chatArray($uid, $messages, $isVip) {
        $saveMessages = [];
        $tempMessages = [];
        $noVipCount = 0;
        $isAllDelete = true;
        //$msgShow = User::findById($uid)->meta_()->notifhistory;
        $user = \Auth::user();
        $banned_users = \App\Models\SimpleTables\banned_users::select('member_id')->get();
        foreach($messages as $key => $message) {
            if($banned_users->contains('member_id', $message->to_id)){
                unset($messages[$key]);
                continue;
            }
            if($banned_users->contains('member_id', $message->from_id) && $message->from_id != $user->id){
                unset($messages[$key]);
                continue;
            }
            if($message->to_id == $user->id) {
                $msgUser = \App\Models\User::findById($message->from_id);
            }
            else if($message->from_id == $user->id) {
                $msgUser =  \App\Models\User::findById($message->to_id);
            }
            if(\App\Models\Message::onlyShowVip($user, $msgUser, $isVip)) {
                unset($messages[$key]);
                continue;
            }

            // end 1 and 2
            if($message->all_delete_count == 2) {
                Message::deleteAllMessagesFromDB($message->to_id, $message->from_id);
            }
            if($message->all_delete_count == 1 && ($message->is_row_delete_1 == $message->to_id || $message->is_row_delete_2 == $message->to_id || $message->is_row_delete_1 == $message->from_id || $message->is_row_delete_2 == $message->from_id)) {
                Message::deleteAllMessagesFromDB($message->to_id, $message->from_id);
            }

            // delete row messages
            if($message->is_row_delete_1 == $uid || $message->is_row_delete_2 == $uid) {
                unset($messages[$key]);
                continue;
            }

            // delete all messages
            if($uid == $message->temp_id && $message->all_delete_count == 1 && $isAllDelete == true) {
                unset($messages[$key]);
                continue;
            }

            // add messages to array
            if(!in_array(['to_id' => $message->to_id, 'from_id' => $message->from_id], $tempMessages) && !in_array(['to_id' => $message->from_id, 'from_id' => $message->to_id], $tempMessages)) {
                array_push($tempMessages, ['to_id' => $message->to_id, 'from_id' => $message->from_id]);
                array_push($saveMessages, ['to_id' => $message->to_id, 'from_id' => $message->from_id, 'temp_id' => $message->temp_id,'all_delete_count' => $message->all_delete_count, 'is_row_delete_1' => $message->is_row_delete_1, 'is_row_delete_2' => $message->is_row_delete_2, 'is_single_delete_1' => $message->is_single_delete_1, 'is_single_delete_2' => $message->is_single_delete_2]);
                $noVipCount++;
            }

            if($isVip == 0 && $noVipCount == Config::get('social.limit.show-chat')) {
                break;
            }
        }

        //if($isAllDelete) return NULL;

        return $saveMessages;
    }

    public static function chatArrayAJAX($uid, $messages, $isVip) {
        $saveMessages = [];
        $tempMessages = [];
        $noVipCount = 0;
        $isAllDelete = true;
        //$msgShow = User::findById($uid)->meta_()->notifhistory;
        foreach($messages as $key => $message) {
            // end 1 and 2
            if($message->all_delete_count == 2) {
                Message::deleteAllMessagesFromDB($message->to_id, $message->from_id);
            }
            if($message->all_delete_count == 1 && ($message->is_row_delete_1 == $message->to_id || $message->is_row_delete_2 == $message->to_id || $message->is_row_delete_1 == $message->from_id || $message->is_row_delete_2 == $message->from_id)) {
                Message::deleteAllMessagesFromDB($message->to_id, $message->from_id);
            }

            // delete row messages
            if($message->is_row_delete_1 == $uid || $message->is_row_delete_2 == $uid) {
                unset($messages[$key]);
                continue;
            }

            // delete all messages
            if($uid == $message->temp_id && $message->all_delete_count == 1 && $isAllDelete == true) {
                unset($messages[$key]);
                continue;
            }

            // add messages to array
            if(!in_array(['to_id' => $message->to_id, 'from_id' => $message->from_id], $tempMessages) && !in_array(['to_id' => $message->from_id, 'from_id' => $message->to_id], $tempMessages)) {
                array_push($tempMessages, ['to_id' => $message->to_id, 'from_id' => $message->from_id]);
                array_push($saveMessages, ['to_id' => $message->to_id, 'from_id' => $message->from_id, 'temp_id' => $message->temp_id,'all_delete_count' => $message->all_delete_count, 'is_row_delete_1' => $message->is_row_delete_1, 'is_row_delete_2' => $message->is_row_delete_2, 'is_single_delete_1' => $message->is_single_delete_1, 'is_single_delete_2' => $message->is_single_delete_2]);
                $noVipCount++;
            }

            if($isVip == 0 && $noVipCount == Config::get('social.limit.show-chat')) {
                break;
            }
        }

        //if($isAllDelete) return NULL;

        return $saveMessages;
    }

    public static function allSenders($uid, $isVip)
    {
        $dropTempTables = DB::unprepared(DB::raw("
            DROP TABLE IF EXISTS temp_m;
        "));
        if(!\Schema::hasTable('temp_m')){
            $createTempTables = DB::unprepared(DB::raw("
                SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;
                CREATE TEMPORARY TABLE `temp_m` AS(
                    SELECT `created_at`, `updated_at`, `to_id`, `from_id`, `content`, `read`, `all_delete_count`, `is_row_delete_1`, `is_row_delete_2`, `is_single_delete_1`, `is_single_delete_2`, `temp_id`, `isReported`, `reportContent`
                    FROM `message`
                    WHERE created_at >= '".\Carbon\Carbon::now()->subDays(7)->toDateTimeString()."'
                );
                COMMIT;
            "));
        }
//        $date = \Carbon\Carbon::createFromFormat('Y-m-d', '2018-09-01');
//        $date_s = $date->subDays(30);
//        $createTempTables = DB::unprepared(DB::raw("
//            CREATE TEMPORARY TABLE `temp_m` AS(
//                SELECT `created_at`, `updated_at`, `to_id`, `from_id`, `content`, `read`, `all_delete_count`, `is_row_delete_1`, `is_row_delete_2`, `is_single_delete_1`, `is_single_delete_2`, `temp_id`, `isReported`, `reportContent`
//                FROM `message`
//                WHERE `created_at`
//                BETWEEN '".$date_s->toDateTimeString()."'
//                AND '".$date_s->addDays(30)->toDateTimeString()."'
//            );
//        "));
        if($createTempTables){
            $messages = DB::select(DB::raw("
                select * from `temp_m` 
                WHERE  (`to_id` = $uid and `from_id` != $uid) or (`from_id` = $uid and `to_id` != $uid) 
                order by `created_at` desc 
            "));
        }

        //$messages = Message::where([['to_id', $uid], ['from_id', '!=', $uid]])->orWhere([['from_id', $uid], ['to_id', '!=',$uid]])->orderBy('created_at', 'desc')->get();

        if($isVip == 1)
            $saveMessages = Message::chatArray($uid, $messages, 1);
        else if($isVip == 0) {
            $saveMessages = Message::chatArray($uid, $messages, 0);
        }

        //echo json_encode($saveMessages);

        return $saveMessages;
        //return Message::sortMessages($saveMessages);
        //return Message::where([['to_id', $uid],['from_id', '!=' ,$uid]])->whereRaw('id IN (select MAX(id) FROM message GROUP BY from_id)')->orderBy('created_at', 'desc')->take(Config::get('social.limit.show-chat'))->get();
    }

    public static function allSendersAdmin($uid, $isVip)
    {
        $messages = DB::select(DB::raw("
            select * from `message` 
            WHERE  (`to_id` = $uid and `from_id` != $uid) or (`from_id` = $uid and `to_id` != $uid) 
            order by `created_at` desc 
        "));
        $saveMessages = Message::chatArray($uid, $messages, 1);

        return $saveMessages;
    }

    public static function moreSendersAJAX($uid, $isVip, $date)
    {
        $dropTempTables = DB::unprepared(DB::raw("
            DROP TABLE IF EXISTS temp_m;
        "));
        if(!\Schema::hasTable('temp_m')) {
            $date = \Carbon\Carbon::createFromFormat('Y-m-d', $date);
            $createTempTables = DB::unprepared(DB::raw("
                SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;
                CREATE TEMPORARY TABLE `temp_m` AS(
                    SELECT `created_at`, `updated_at`, `to_id`, `from_id`, `content`, `read`, `all_delete_count`, `is_row_delete_1`, `is_row_delete_2`, `is_single_delete_1`, `is_single_delete_2`, `temp_id`, `isReported`, `reportContent`
                    FROM `message`
                    WHERE `created_at`
                    BETWEEN '" . $date->subDays(7)->toDateTimeString() . "'
                    AND '" . $date->addDays(7)->toDateTimeString() . "'
                );       
                COMMIT;
            "));
        }
//        $createTempTables = DB::unprepared(DB::raw("
//            CREATE TEMPORARY TABLE `temp_m` AS(
//                SELECT `created_at`, `updated_at`, `to_id`, `from_id`, `content`, `read`, `all_delete_count`, `is_row_delete_1`, `is_row_delete_2`, `is_single_delete_1`, `is_single_delete_2`, `temp_id`, `isReported`, `reportContent`
//                FROM `message`
//                WHERE created_at >= '2018-07-01'
//            );
//        "));
        if($createTempTables){
            $messages = DB::select(DB::raw("
                select * from `temp_m` 
                WHERE  (`to_id` = $uid and `from_id` != $uid) or (`from_id` = $uid and `to_id` != $uid) 
                order by `created_at` desc 
            "));
        }

        //$messages = Message::where([['to_id', $uid], ['from_id', '!=', $uid]])->orWhere([['from_id', $uid], ['to_id', '!=',$uid]])->orderBy('created_at', 'desc')->get();

        if($isVip == 1)
            $saveMessages = Message::chatArrayAJAX($uid, $messages, 1);
        else if($isVip == 0) {
            $saveMessages = Message::chatArrayAJAX($uid, $messages, 0);
        }

        //echo json_encode($saveMessages);
        if(count($saveMessages) == 0){
            return 'No data';
        }
        else{
            return Message::sortMessages($saveMessages);
        }
        //return Message::where([['to_id', $uid],['from_id', '!=' ,$uid]])->whereRaw('id IN (select MAX(id) FROM message GROUP BY from_id)')->orderBy('created_at', 'desc')->take(Config::get('social.limit.show-chat'))->get();
    }

    public static function allSendersAJAX($uid, $isVip)
    {
        $messages = Message::where([['to_id', $uid], ['from_id', '!=', $uid]])->orWhere([['from_id', $uid], ['to_id', '!=',$uid]])->orderBy('created_at', 'desc')->get();

        if($isVip == 1)
            $saveMessages = Message::chatArrayAJAX($uid, $messages, 1);
        else if($isVip == 0) {
            $saveMessages = Message::chatArrayAJAX($uid, $messages, 0);
        }

        //echo json_encode($saveMessages);
        if(count($saveMessages) == 0){
            return 'No data';
        }
        else{
            return Message::sortMessages($saveMessages);
        }
        //return Message::where([['to_id', $uid],['from_id', '!=' ,$uid]])->whereRaw('id IN (select MAX(id) FROM message GROUP BY from_id)')->orderBy('created_at', 'desc')->take(Config::get('social.limit.show-chat'))->get();
    }

    public static function sortMessages($messages){
        if ($messages instanceof Illuminate\Database\Eloquent\Collection) {
            $messages = $messages->toArray();
        }
        $user = Auth::user();
        $block_people =  Config::get('social.block.block-people');
        $userBlockList = \App\Models\Blocked::select('blocked_id')->where('member_id', $user->id)->get();
        $banned_users = banned_users::select('member_id')->get();
        $isVip = $user->isVip();
        foreach ($messages as $key => $message){
            if($banned_users->contains('member_id', $message['to_id'])){
                unset($messages[$key]);
                continue;
            }
            if($banned_users->contains('member_id', $message['from_id']) && $message['from_id'] != $user->id){
                unset($messages[$key]);
                continue;
            }
            if($userBlockList->contains('member_id', $message['from_id']) || $userBlockList->contains('member_id', $message['to_id'])){
                unset($messages[$key]);
                continue;
            }
            if($message['to_id'] == $user->id) {
                $msgUser = \App\Models\User::findById($message['from_id']);
            }
            else if($message['from_id'] == $user->id) {
                $msgUser =  \App\Models\User::findById($message['to_id']);
            }
            if(\App\Models\Message::onlyShowVip($user, $msgUser, $isVip)) {
                unset($messages[$key]);
                continue;
            }
            $latestMessage = \App\Models\Message::latestMessage($user->id, $msgUser->id);
            if(!empty($latestMessage)){
                if(\App\Models\Message::isAdminMessage($latestMessage->content)){
                    $messages[$key]['isAdminMessage'] = 1;
                }
                else{
                    $messages[$key]['isAdminMessage'] = 0;
                }
                if(\App\Models\Reported::cntr($msgUser->id) >= $block_people ){
                    $messages[$key]['cntr'] = 1;
                }
                else{
                    $messages[$key]['cntr'] = 0;
                }
            }
            $messages[$key]['user_id'] = $msgUser->id;
            $messages[$key]['user_name'] = $msgUser->name;
            $messages[$key]['isAvatarHidden'] = $msgUser->meta_()->isAvatarHidden;
            $messages[$key]['pic'] = $msgUser->meta_()->pic;
            $messages[$key]['content'] = $latestMessage == null ? '' : $latestMessage->content;
        }
        return array_values($messages);
    }

    public static function search($value, $array) {
        foreach ($array as $key => $val) {
            if ($val['blocked_id'] === $value) {
                return true;
            }
        }
        return false;
    }

    public static function latestMessage($uid, $sid)
    {
        //echo '<br>' . $uid . '             ' . $sid;
        if(Blocked::isBlocked($uid, $sid)) {
            $blockTime = Blocked::getBlockTime($uid, $sid);
            //echo 'blockTime = ' . $blockTime->created_at;
            $latestMessage = Message::where([['to_id', $uid],['from_id', $sid],['created_at', '<=', $blockTime->created_at]])->orWhere([['to_id', $sid],['from_id', $uid],['created_at', '<=', $blockTime->created_at]])->orderBy('created_at', 'desc')->first();
            if($latestMessage == NULL) return NULL;
            return $latestMessage;
        }
        return Message::where([['to_id', $uid],['from_id', $sid]])->orWhere([['to_id', $sid],['from_id', $uid]])->orderBy('created_at', 'desc')->first();
    }

    // public static function allFromSender($uid, $sid)
    // {
    //     return Message::where('to_id', $uid)->orWhere('from_id', $sid)->distinct()->orderBy('created_at', 'desc')->get();
    // }

    public static function allToFromSender($uid, $sid)
    {
        if(Blocked::isBlocked($uid, $sid)) {
            $blockTime = Blocked::getBlockTime($uid, $sid);
            return Message::where([['to_id', $uid],['from_id', $sid],['created_at', '<=', $blockTime->created_at]])->orWhere([['from_id', $uid],['to_id', $sid]])->distinct()->orderBy('created_at', 'desc')->paginate(10);
        }

        return Message::where([['to_id', $uid],['from_id', $sid]])->orWhere([['from_id', $uid],['to_id', $sid]])->distinct()->orderBy('created_at', 'desc')->paginate(10);
    }

    public static function unread($uid)
    {
        // block information
        //
        $user = User::findById($uid);
        $block = Blocked::getAllBlock($uid);
        $banned_users = banned_users::select('member_id')->get();
        $all_msg = Message::where([['to_id', $uid],['from_id', '!=', $uid], ['is_row_delete_1', '=' ,0], ['temp_id', '=', 0]])->where('read', 'N')->whereNotIn('from_id', $banned_users);
        if($user->meta_()->notifhistory == '顯示VIP會員信件') {
            //$allVip = \App\Models\Vip::allVip();
            //$all_msg = $all_msg->whereIn('from_id', $allVip);
            $all_msg = $all_msg->join('member_vip', 'member_vip.member_id', '=', 'message.from_id');
        }
        $unreadCount = 0;
        if($block->count() == 0) return $all_msg->count();
        //echo $block->count();
        //echo 'count = '. $block->count();
        $blocked_ids = array();
        foreach($block as $b) {
            if(!in_array($b->blocked_id, $blocked_ids)){
                array_push($blocked_ids, $b->blocked_id);
            }
        }
        $unreadCount += $all_msg->whereNotIn('from_id', $blocked_ids)->count();
        //for($i = 0 ; $i < $block->count(); $i++) {
        //    if($all_msg->where('from_id', '!=', $block[$i]->blocked_id) != NULL) {
        //        $unreadCount += $all_msg
        //                        ->where('from_id', '!=', $block[$i]->blocked_id)
        //                        ->where('read', 'N')
        //                        ->count();
        //    }
        //}

        return $unreadCount;
        //return Message::where([['to_id', $uid],['from_id', '!=', $uid],['from_id', '!=', $block[$i]->blocked_id]])->where('read', 'N')->count();
    }

    public static function read($message, $uid)
    {
        if ($message->to_id == $uid)
        {
            $message->read = 'Y';
            $message->save();
        }
    }

    public static function post($from_id, $to_id, $msg)
    {
        $message = new Message;
        $message->from_id = $from_id;
        $message->to_id = $to_id;
        $message->content = $msg;
        $message->all_delete_count = 0;
        $message->is_row_delete_1 = 0;
        $message->is_row_delete_2 = 0;
        $message->is_single_delete_1 = 0;
        $message->is_single_delete_2 = 0;
        $message->temp_id = 0;
        $message->save();
        $curUser = User::findById($to_id);
        if ($curUser->meta_()->notifmessage !== '不通知')
        {
        // $curUser->notify(new MessageEmail($from_id, $to_id, $msg));
        }
    }
}
