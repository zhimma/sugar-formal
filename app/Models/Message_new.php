<?php

namespace App\Models;

use Auth;
use App\Models\User;
use App\Models\Blocked;
use App\Models\SimpleTables\banned_users;
use App\Services\AdminService;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\MessageEmail;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Message_new extends Model
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

    static $date = null;
    
    /*
    |--------------------------------------------------------------------------
    | relationships
    |--------------------------------------------------------------------------
    */
    public function sender()
    {
        return $this->belongsTo(User::class, 'from_id', 'id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'to_id', 'id');
    }

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
        $banned_users = \App\Services\UserService::getBannedId($user->id);
        foreach($messages as $key => &$message) {
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

    public static function chatArrayAJAX($uid, $messages, $isVip, $noVipCount = 0) {
        $saveMessages = [];
        $tempMessages = [];
        $isAllDelete = true;
        //$msgShow = User::findById($uid)->meta_()->notifhistory;
        foreach($messages as $key => $message) {
            if($isVip == 0 && $noVipCount >0 &&$noVipCount >= Config::get('social.limit.show-chat')) {
                break;
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
        }

        //if($isAllDelete) return NULL;

        return $saveMessages;
    }

    public static function newChatArrayAJAX($uid, $messages) {
        $saveMessages = [];
        $tempMessages = [];
        $isAllDelete = true;
        //$msgShow = User::findById($uid)->meta_()->notifhistory;
        foreach($messages as $key => $message) {
            //            if($isVip == 0 && $noVipCount >0 &&$noVipCount >= Config::get('social.limit.show-chat')) {
            //                break;
            //            }
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
                array_push($saveMessages, ['to_id' => $message->to_id, 'from_id' => $message->from_id, 'temp_id' => $message->temp_id,'all_delete_count' => $message->all_delete_count, 'is_row_delete_1' => $message->is_row_delete_1, 'is_row_delete_2' => $message->is_row_delete_2, 'is_single_delete_1' => $message->is_single_delete_1, 'is_single_delete_2' => $message->is_single_delete_2, 'sender' => $message->sender, 'receiver' => $message->receiver, 'content' => $message->content, 'read' => $message->read, 'created_at' => $message->created_at]);
                //$noVipCount++;
            }
        }

        //if($isAllDelete) return NULL;

        return $saveMessages;
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

    public static function moreSendersAJAX($uid, $isVip, $date, $userAgent = null, $noVipCount = 0)
    {
        $dropTempTables = DB::unprepared(DB::raw("
            DROP TABLE IF EXISTS temp_m;
        "));
        $dateDebug = $date;
        if(!\Schema::hasTable('temp_m')) {
            try{
                $date = \Carbon\Carbon::createFromFormat('Y-m-d', $date);
                $dateEnd  = $date->toDateTimeString();
                $monthAgo = $date->subDays(30)->toDateTimeString();
                self::$date = $monthAgo;
                $createTempTables = DB::unprepared(DB::raw("
                    SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;
                    CREATE TEMPORARY TABLE `temp_m` AS(
                        SELECT `created_at`, `updated_at`, `to_id`, `from_id`, `content`, `read`, `all_delete_count`, `is_row_delete_1`, `is_row_delete_2`, `is_single_delete_1`, `is_single_delete_2`, `temp_id`, `isReported`, `reportContent`
                        FROM `message`
                        WHERE `created_at`
                        BETWEEN '" . $monthAgo . "'
                        AND '" . $dateEnd . "'
                    );
                    COMMIT;
                "));
            }
            catch (\Exception $e){
                Log::info('moreSendersAJAX with $date: ' . $dateDebug);
                Log::info('Useragent: ' . $_SERVER['HTTP_USER_AGENT']);
                return false;
            }
        }
        //        $createTempTables = DB::unprepared(DB::raw("
        //            CREATE TEMPORARY TABLE `temp_m` AS(
        //                SELECT `created_at`, `updated_at`, `to_id`, `from_id`, `content`, `read`, `all_delete_count`, `is_row_delete_1`, `is_row_delete_2`, `is_single_delete_1`, `is_single_delete_2`, `temp_id`, `isReported`, `reportContent`
        //                FROM `message`
        //                WHERE created_at >= '2018-07-01'
        //            );
        //        "));
        if(isset($createTempTables) && $createTempTables){
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
            $saveMessages = Message::chatArrayAJAX($uid, $messages, 0, $noVipCount);
        }

        //echo json_encode($saveMessages);
        if(count($saveMessages) == 0){
            return array_values(['No data', self::$date]);
        }
        else{
            return Message::sortMessages($saveMessages);
        }
        //return Message::where([['to_id', $uid],['from_id', '!=' ,$uid]])->whereRaw('id IN (select MAX(id) FROM message GROUP BY from_id)')->orderBy('created_at', 'desc')->take(Config::get('social.limit.show-chat'))->get();
    }

    public static function allSendersAJAX($uid, $isVip, $d = 7,$forEventSenders=false)
    {
		if($forEventSenders) $user = null;
        else 
			$user = \View::shared('user');
        if(!$user){
            $user = User::find($uid);
        }
		if(!$forEventSenders) {
			$banned_users = banned_users::where('member_id', $uid)->first();
			$BannedUsersImplicitly = BannedUsersImplicitly::where('target', $uid)->first();
			if( (isset($banned_users) && ($banned_users->expire_date == null || $banned_users->expire_date >= Carbon::now())) || isset($BannedUsersImplicitly)){
				return false;
			}
		}
		$admin_id = AdminService::checkAdmin()->id;
        /**
         * 效能調整：使用左結合取代 where in 以取得更好的效能
         *
         * @author LZong <lzong.tw@gmail.com>
         */
        $query = Message::with(['sender', 'receiver', 'sender.aw_relation', 'receiver.aw_relation'])
            ->select("m.*")
            ->from('message as m')
            ->leftJoin('users as u1', 'u1.id', '=', 'm.from_id')
            ->leftJoin('users as u2', 'u2.id', '=', 'm.to_id')
            ->leftJoin('banned_users as b1', 'b1.member_id', '=', 'm.from_id')
            ->leftJoin('banned_users as b2', 'b2.member_id', '=', 'm.to_id')
            ->leftJoin('banned_users_implicitly as b3', 'b3.target', '=', 'm.from_id')
            ->leftJoin('banned_users_implicitly as b4', 'b4.target', '=', 'm.to_id')
            ->leftJoin('blocked as b5', function($join) use($uid) {
                $join->on('b5.blocked_id', '=', 'm.from_id')
                    ->where('b5.member_id', $uid); })
            ->leftJoin('blocked as b6', function($join) use($uid) {
                $join->on('b6.blocked_id', '=', 'm.to_id')
                    ->where('b6.member_id', $uid); })
            ->leftJoin('blocked as b7', function($join) use($uid) {
                $join->on('b7.member_id', '=', 'm.from_id')
                    ->where('b7.blocked_id', $uid); });
        $query = $query->whereNotNull('u1.id')
                ->whereNotNull('u2.id')
                ->whereNull('b5.blocked_id')
                ->whereNull('b6.blocked_id')
                ->whereNull('b7.member_id')
                ->where(function ($query) use ($uid,$admin_id) {
                    $query->where([['m.to_id', $uid], ['m.from_id', '!=', $uid],['m.from_id','!=',$admin_id]])
                        ->orWhere([['m.from_id', $uid], ['m.to_id', '!=',$uid],['m.to_id','!=',$admin_id]]);
                });
		if($forEventSenders) 
		{
			self::$date = \Carbon\Carbon::parse(date("Y-m-01"))->toDateTimeString();
		}
        else if($d==7){
            self::$date = \Carbon\Carbon::parse("7 days ago")->toDateTimeString();
        }else if($d==30){
            self::$date = \Carbon\Carbon::parse("30 days ago")->toDateTimeString();
        }else if($d=='all'){
            if($isVip) {
                self::$date =\Carbon\Carbon::parse("180 days ago")->toDateTimeString();
            }else {
                self::$date = \Carbon\Carbon::parse("30 days ago")->toDateTimeString();
            }
        }

        $query->where([['m.created_at','>=',self::$date]]);
        $query->whereRaw('m.created_at < IFNULL(b1.created_at,"2999-12-31 23:59:59")');
        $query->whereRaw('m.created_at < IFNULL(b2.created_at,"2999-12-31 23:59:59")');
        $query->whereRaw('m.created_at < IFNULL(b3.created_at,"2999-12-31 23:59:59")');
        $query->whereRaw('m.created_at < IFNULL(b4.created_at,"2999-12-31 23:59:59")');        
        $query->where([['m.is_row_delete_1','<>',$uid],['m.is_single_delete_1', '<>' ,$uid], ['m.all_delete_count', '<>' ,$uid],['m.is_row_delete_2', '<>' ,$uid],['m.is_single_delete_2', '<>' ,$uid],['m.temp_id', '=', 0]]);
        $query->orderBy('m.created_at', 'desc');
        if($user->id != 1049){
            $query->where(function($query){
                $query->where(DB::raw('(u1.engroup + u2.engroup)'), '<>', '2');
                $query->orWhere(DB::raw('(u1.engroup + u2.engroup)'), '<>', '4');
            });
        }
        $messages = $query->get();
        $mCount = count($messages);
        $mm = [];
        foreach ($messages as $key => $v) {
            if(!isset($mm[$v->from_id])){
                $mm[$v->from_id] = 0;
            }
            if($v->read=='N' && $v->all_delete_count != $uid && $v->is_row_delete_1 != $uid && $v->is_row_delete_2 != $uid && $v->is_single_delete_1 != $uid && $v->is_single_delete_2 != $uid){
                $mm[$v->from_id]++;
            }

        }
        $saveMessages = Message_new::newChatArrayAJAX($uid, $messages);
        if(count($saveMessages) == 0){
            return array_values(['No data']);
        }else{
            return Message_new::sortMessages($saveMessages, null, $mm, $mCount,$forEventSenders?$uid:null);
        }
        //return Message::where([['to_id', $uid],['from_id', '!=' ,$uid]])->whereRaw('id IN (select MAX(id) FROM message GROUP BY from_id)')->orderBy('created_at', 'desc')->take(Config::get('social.limit.show-chat'))->get();
    }


    public static function sortMessages($messages, $userBlockList = null, $mm = [], $mCount = 10,$uid=null){
        if ($messages instanceof Illuminate\Database\Eloquent\Collection) {
            $messages = $messages->toArray();
        }
	
		if($uid)
			$user=User::find($uid);
        else
			$user = Auth::user();
        $block_people =  Config::get('social.block.block-people');
        $isVip = $user->isVip();
        $aa=[];
		$admin_id = AdminService::checkAdmin()->id;
        foreach ($messages as $key => &$message){
			
            if($message['sender']->engroup==$message['receiver']->engroup){
                unset($messages[$key]);
                continue;
            }			
			
            $to_id = isset($message["to_id"]) ? $message["to_id"] : null;
            $from_id = isset($message["from_id"]) ? $message["from_id"] : null;

            if($message['to_id'] == $user->id) {
                $msgUser = $message['sender'];
            }
            else if($message['from_id'] == $user->id) {
                $msgUser =  $message['receiver'];
            }
            unset($message['sender']);
            unset($message['receiver']);
            if(!$msgUser){
                unset($messages[$key]);
                continue;
            }
			/*收信設定  已無用  拿掉 210727
            if(\App\Models\Message::onlyShowVip($user, $msgUser, $isVip)) {
                unset($messages[$key]);
                continue;
            }
			*/
            if(isset($user->id) && isset($msgUser->id)){
                if(\App\Models\Message::isAdminMessage($message["content"])){
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
                $data = \App\Services\UserService::checkRecommendedUser($msgUser);
                if(isset($data['button']) && isset($theMessage)){
                    $message['isPreferred'] = 1;
                    $message['button'] = $data['button'];
                }
                $messages[$key]['user_id'] = $msgUser->id;
                $messages[$key]['created_at'] = $message['created_at']->toDateTimeString();
                $messages[$key]['user_name'] = $msgUser->name;
                $messages[$key]['isAvatarHidden'] = $msgUser->user_meta->isAvatarHidden;
                $messages[$key]['blurry_avatar'] = $msgUser->user_meta->blurryAvatar;
                $messages[$key]['blurry_life_photo'] = $msgUser->user_meta->blurryLifePhoto;
                $messages[$key]['pic'] = $msgUser->user_meta->pic;
                if(!file_exists( public_path().$msgUser->user_meta->pic ) || $msgUser->user_meta->pic==null){
                    if($msgUser->engroup==1) {
                        $messages[$key]['pic'] = '/new/images/male.png';
                    }else{
                        $messages[$key]['pic'] = '/new/images/female.png';
                    }
                }
                $messages[$key]['read_n'] = $mm[$msgUser->id] ?? 0;
                $messages[$key]['isVip'] = $msgUser->isVip();
//                $messages[$key]['isWarned']=$msgUser->meta_()->isWarned;
                if(($msgUser->user_meta->isWarned==1 || $msgUser->aw_relation ) && $msgUser->id != 1049){
                    $messages[$key]['isWarned']=1;
                }else{
                    $messages[$key]['isWarned']=0;
                }
                
                if(($msgUser->banned || $msgUser->implicitlyBanned ) && $msgUser->id != 1049){
                    $messages[$key]['isBanned']=1;
                }else{
                    $messages[$key]['isBanned']=0;
                }  
                
                $messages[$key]['exchange_period']=$msgUser->exchange_period;
                $messages[$key]['mCount']=$mCount;
            }
            else{
                Log::info('Null object found, $user: ' . $user->id);
                if(!isset($user->id)){
                    Log::info('User null.');
                }
                if(!isset($msgUser->id)){
                    Log::info('msgUser null: ' . $to_id . " or " . $from_id);
                }
            }
        }
        //$messages['date'] = self::$date;
//        array_multisort($messages[1]['created_at'],SORT_DESC, SORT_STRING);
        return $messages;
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

        $theMessage = Message::where([['to_id', $uid],['from_id', $sid]])->orWhere([['to_id', $sid],['from_id', $uid]])->orderBy('created_at', 'desc')->first();
        $msgUser = User::findById($sid);
        $data = \App\Services\UserService::checkRecommendedUser($msgUser);
        if(isset($data['button']) && isset($theMessage)){
            $theMessage->isPreferred = 1;
            $theMessage->button = $data['button'];
        }

        return $theMessage;
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
        $banned_users = \App\Services\UserService::getBannedId();
        $query = Message::where(function($query)use($uid)
        {
            $query->where('to_id','=' ,$uid)
                ->where('from_id','<>',$uid);
        });
        $query->where('read', 'N');
//        $query->where([['is_row_delete_1', '<>' ,$uid],['is_single_delete_1', '<>' ,$uid], ['all_delete_count', '<>' ,$uid],['is_row_delete_2', '<>' ,$uid],['is_single_delete_2', '<>' ,$uid]]);
        if(isset($banned_users)) {
            $query->whereNotIn('from_id', $banned_users);
            $query->whereNotIn('to_id', $banned_users);
        }
        if(isset($block)) {
            $query->whereNotIn('from_id', $block);
            $query->whereNotIn('to_id', $block);
        }
        $query->where([['is_row_delete_1','<>',$uid],['is_single_delete_1', '<>' ,$uid], ['all_delete_count', '<>' ,$uid],['is_row_delete_2', '<>' ,$uid],['is_single_delete_2', '<>' ,$uid]]);

        $query->where('created_at','>=',Carbon::parse("180 days ago")->toDateTimeString());
//        $all_msg = Message::where('read', 'N')
//            ->where([['to_id', $uid],['from_id', '!=', $uid], ['is_row_delete_1', '<>' ,$uid],['is_single_delete_1', '<>' ,$uid], ['all_delete_count', '<>' ,$uid],['is_row_delete_2', '<>' ,$uid],['is_single_delete_2', '<>' ,$uid],['temp_id', 0]])
//            if($banned_users) {
//                $all_msg->whereNotIn('from_id', $banned_users)
//                    $all_msg->whereNotIn('to_id', $banned_users)
//                    }
//            $all_msg->whereNotIn('from_id', $block)
//            $all_msg->whereNotIn('to_id', $block)
//            $all_msg->where('created_at','>=',Carbon::parse("180 days ago")->toDateTimeString());
//        if($user->meta_()->notifhistory == '顯示VIP會員信件') {
//            //$allVip = \App\Models\Vip::allVip();
//            //$all_msg = $all_msg->whereIn('from_id', $allVip);
//            $all_msg = $all_msg->join('member_vip', 'member_vip.member_id', '=', 'message.from_id');
//        }
        $unreadCount = 0;
//        if($block->count() == 0) return $all_msg->count();
//        //echo $block->count();
//        //echo 'count = '. $block->count();
//        $blocked_ids = array();
//        foreach($block as $b) {
//            if(!in_array($b->blocked_id, $blocked_ids)){
//                array_push($blocked_ids, $b->blocked_id);
//            }
//        }
//        $unreadCount += $all_msg->whereNotIn('from_id', $blocked_ids)->count();
        $unreadCount = $query->get()->count();

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

        return $message;
    }

    public static function betweenMessages($user_ids)
    {
        return Message_new::whereIn('from_id',$user_ids)
            ->whereIn('to_id', $user_ids)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function allSenders($uid, $isVip, $d = 7, $isCount = true)
    {
        $user = \View::shared('user');
        if(!$user){
            $user = User::find($uid);
        }
        $banned_users = banned_users::where('member_id', $uid)->first();
        $BannedUsersImplicitly = BannedUsersImplicitly::where('target', $uid)->first();
        if( (isset($banned_users) && ($banned_users->expire_date == null || $banned_users->expire_date >= Carbon::now())) || isset($BannedUsersImplicitly)){
            return false;
        }
        $admin_id = AdminService::checkAdmin()->id;
        /**
         * 效能調整：使用左結合取代 where in 以取得更好的效能
         *
         * @author LZong <lzong.tw@gmail.com>
         */
        $query = Message::with(['sender', 'receiver', 'sender.aw_relation', 'receiver.aw_relation'])->select(
            DB::raw('(m.to_id + m.from_id) as to_from_pair')
        )
            ->from('message as m')
			->leftJoin('users as u1', 'u1.id', '=', 'm.from_id')
            ->leftJoin('users as u2', 'u2.id', '=', 'm.to_id')
            ->leftJoin('banned_users as b1', 'b1.member_id', '=', 'm.from_id')
            ->leftJoin('banned_users as b2', 'b2.member_id', '=', 'm.to_id')
            ->leftJoin('banned_users_implicitly as b3', 'b3.target', '=', 'm.from_id')
            ->leftJoin('banned_users_implicitly as b4', 'b4.target', '=', 'm.to_id')
            ->leftJoin('blocked as b5', function($join) use($uid) {
                $join->on('b5.blocked_id', '=', 'm.from_id')
                    ->where('b5.member_id', $uid); })
            ->leftJoin('blocked as b6', function($join) use($uid) {
                $join->on('b6.blocked_id', '=', 'm.to_id')
                    ->where('b6.member_id', $uid); })
            ->leftJoin('blocked as b7', function($join) use($uid) {
                $join->on('b7.member_id', '=', 'm.from_id')
                    ->where('b7.blocked_id', $uid); });
        $query = $query->whereNotNull('u1.id')
            ->whereNotNull('u2.id')
            ->whereNull('b1.member_id')
            ->whereNull('b2.member_id')
            ->whereNull('b3.target')
            ->whereNull('b4.target')
            ->whereNull('b5.blocked_id')
            ->whereNull('b6.blocked_id')
            ->whereNull('b7.member_id')
            ->where(function ($query) use ($uid,$admin_id) {
                $query->where([['m.to_id', $uid], ['m.from_id', '!=', $uid],['m.from_id','!=',$admin_id]])
                    ->orWhere([['m.from_id', $uid], ['m.to_id', '!=',$uid],['m.to_id','!=',$admin_id]]);
            });

        if($user->id != 1049){
            $query->where(function($query){
                $query->where(DB::raw('(u1.engroup + u2.engroup)'), '<>', '2');
                $query->orWhere(DB::raw('(u1.engroup + u2.engroup)'), '<>', '4');
            });
        }

        if($d==7){
            self::$date = \Carbon\Carbon::parse("7 days ago")->toDateTimeString();
        }else if($d==30){
            self::$date = \Carbon\Carbon::parse("30 days ago")->toDateTimeString();
        }else if($d=='curMon') {
            self::$date = \Carbon\Carbon::parse(date("Y-m-01"))->toDateTimeString();
            
        }else if($d=='all'){
            if($isVip) {
                self::$date =\Carbon\Carbon::parse("180 days ago")->toDateTimeString();
            }else {
                self::$date = \Carbon\Carbon::parse("30 days ago")->toDateTimeString();
            }
        }
        $query->where([['m.is_row_delete_1','<>',$uid],['m.is_single_delete_1', '<>' ,$uid], ['m.all_delete_count', '<>' ,$uid],['m.is_row_delete_2', '<>' ,$uid],['m.is_single_delete_2', '<>' ,$uid],['m.temp_id', '=', 0]]);
        $query->where([['m.created_at','>=',self::$date]]);
        $query->whereRaw('m.created_at < IFNULL(b1.created_at,"2999-12-31 23:59:59")');
        $query->whereRaw('m.created_at < IFNULL(b2.created_at,"2999-12-31 23:59:59")');
        $query->whereRaw('m.created_at < IFNULL(b3.created_at,"2999-12-31 23:59:59")');
        $query->whereRaw('m.created_at < IFNULL(b4.created_at,"2999-12-31 23:59:59")');
		$allSenders = $query->get();

        if($isCount) {
            $allSenders = $query->groupBy('to_from_pair')->get()->count();
        }
        return $allSenders;
    }

}
