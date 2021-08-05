<?php

namespace App\Models;

use Auth;
use App\Models\User;
use App\Models\Blocked;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\MessageEmail;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Services\AdminService;
use Intervention\Image\Facades\Image;
use App\Models\SimpleTables\banned_users;
use App\Models\BannedUsersImplicitly;

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
        'pic',
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
        else if($message->is_row_delete_1 <> 0 && $message->is_row_delete_2 == 0) {
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
                $message[$i]->updated_at = Carbon::now();
            }
            else if($step == 1) {
                $message[$i]->is_row_delete_2 = $uid;
                $message[$i]->updated_at = Carbon::now();
                Message::deleteRowMessagesFromDB($uid, $sid);
            }
            $message[$i]->save();
        }
    }

    public static function deleteSingleMessage($message, $uid, $sid, $ct_time, $content, $step) {
        if($step == 0) {
            $message->is_single_delete_1 = $uid;
            $message->updated_at = Carbon::now();
        }
        else if($step == 1) {
            $message->is_single_delete_2 = $uid;
            $message->updated_at = Carbon::now();
            Message::deleteSingleMessageFromDB($uid, $sid, $ct_time, $content);
        }
        $message->save();
    }

    public static function deleteSingle($uid, $sid, $ct_time, $content) {
        $message = Message::where([['to_id', $uid], ['from_id', $sid], ['created_at', $ct_time], ['content', $content]])
            ->orWhere([['to_id', $sid], ['from_id', $uid], ['created_at', $ct_time], ['content', $content]])
            ->first();

        if($message) {
            if($message->is_single_delete_1 == 0) {
                Message::deleteSingleMessage($message, $uid, $sid, $ct_time, $content, 0);
            }elseif($message->is_single_delete_1 <> 0 && $message->is_single_delete_2 == 0) {
                Message::deleteSingleMessage($message, $uid, $sid, $ct_time, $content, 1);
            }
        }
    }

    public static function reportMessage($id, $content, $images) {
        $message = Message::where('id', $id)->first();
        $message->isReported = 1;
        $message->reportContent = $content;

        if(!is_null($images) && count($images)){

            if($files = $images)
            {
                $images_ary=array();
                foreach ($files as $key => $file) {
                    $now = Carbon::now()->format('Ymd');
                    $input['imagename'] = $now . rand(100000000,999999999) . '.' . $file->getClientOriginalExtension();

                    $rootPath = public_path('/img/Message/Reported');
                    $tempPath = $rootPath . '/' . substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/';

                    if(!is_dir($tempPath)) {
                        File::makeDirectory($tempPath, 0777, true);
                    }

                    $destinationPath = '/img/Message/Reported/'. substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/' . $input['imagename'];

                    $img = Image::make($file->getRealPath());
                    $img->resize(400, 600, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($tempPath . $input['imagename']);

                    //整理images
                    $images_ary[$key]= $destinationPath;
                }
            }
            $message->reportContentPic = json_encode($images_ary);
        }
        $message->save();
    }

    public static function isAdminMessage($content) {
        if(strstr($content, '系統通知: 車馬費邀請') != false) {
            //echo strpos($content, '系統通知: 車馬費邀請');
            return true;
        }
        return false;
    }

    // show message setting
    public static function onlyShowVip($user, $msgUser, $isVip = false) {
        if (!isset($msgUser)){
            return false;
        }
        return $isVip && !$msgUser->isVip() && $user->user_meta->notifhistory == '顯示VIP會員信件';
    }

    public static function showNoVip($user, $msgUser, $isVip = false) {
        return $isVip && !$msgUser->isVip() && ($user->user_meta->notifhistory == '顯示普通會員信件' || $user->user_meta->notifhistory == '');
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
                array_push($saveMessages, ['created_at' => $message->created_at,'to_id' => $message->to_id, 'from_id' => $message->from_id, 'temp_id' => $message->temp_id,'all_delete_count' => $message->all_delete_count, 'is_row_delete_1' => $message->is_row_delete_1,
                    'is_row_delete_2' => $message->is_row_delete_2, 'is_single_delete_1' => $message->is_single_delete_1, 'is_single_delete_2' => $message->is_single_delete_2,'content'=>$message->content]);
                $noVipCount++;
            }

            if($isVip == 0 && $noVipCount == Config::get('social.limit.show-chat')) {
                break;
            }
        }

        return $saveMessages;
    }

    public static function chatArrayAJAX($uid, $messages, $isVip, $noVipCount = 0) {
        $saveMessages = [];
        $tempMessages = [];
        $isAllDelete = true;
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

        return $saveMessages;
    }

    public static function allSenders($uid, $isVip)
    {
        self::$date = \Carbon\Carbon::now()->subDays(7)->toDateTimeString();
        $dropTempTables = DB::unprepared(DB::raw("
            DROP TABLE IF EXISTS temp_m;
        "));

        if(!\Schema::hasTable('temp_m')){
            $admin = User::select('id')->where('email', Config::get('social.admin.email'))->get()->first();
            if(isset($admin)) {
                $createTempTables = DB::unprepared(DB::raw("
                    SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;
                    CREATE TEMPORARY TABLE `temp_m` AS(
                        SELECT `created_at`, `updated_at`, `to_id`, `from_id`, `content`, `read`, `all_delete_count`, `is_row_delete_1`, `is_row_delete_2`, `is_single_delete_1`, `is_single_delete_2`, `temp_id`, `isReported`, `reportContent`
                        FROM `message`
                        WHERE created_at >= '".self::$date."' or  (`from_id`= $admin->id and `to_id` = $uid and `read` = 'N')
                    );
                    COMMIT;
                "));
            }
            else{
                $createTempTables = DB::unprepared(DB::raw("
                    SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;
                    CREATE TEMPORARY TABLE `temp_m` AS(
                        SELECT `created_at`, `updated_at`, `to_id`, `from_id`, `content`, `read`, `all_delete_count`, `is_row_delete_1`, `is_row_delete_2`, `is_single_delete_1`, `is_single_delete_2`, `temp_id`, `isReported`, `reportContent`
                        FROM `message`
                        WHERE created_at >= '".self::$date."'
                    );
                    COMMIT;
                "));
            }
        }

        if($createTempTables){
            $admin = User::select('id')->where('email', Config::get('social.admin.email'))->get()->first();
            if(isset($admin)) {
                $messages = DB::select(DB::raw("
                    select * from `temp_m`
                    WHERE  (`to_id` = $uid and `from_id` != $uid) or (`from_id` = $uid and `to_id` != $uid)
                    order by (
                        CASE
                        WHEN (from_id = $admin->id)
                        THEN
                            1
                        ELSE
                            2
                        END
                    ), `created_at` desc;
                "));
            }
            else{
                $messages = DB::select(DB::raw("
                    select * from `temp_m`
                    WHERE  (`to_id` = $uid and `from_id` != $uid) or (`from_id` = $uid and `to_id` != $uid)
                    order by `created_at` desc;
                "));
            }
        }

        if($isVip == 1)
            $saveMessages = Message::chatArray($uid, $messages, 1);
        else if($isVip == 0) {
            $saveMessages = Message::chatArray($uid, $messages, 0);
        }

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

    public static function daySendersAdmin($uid, $isVip,$day_page){   
        $s = date('Y-m-d H:i:s',strtotime(($day_page-1).'day'));
        $e = date('Y-m-d H:i:s',strtotime($day_page.'day'));
        $messages = DB::select(DB::raw("
            select * from `message` 
            WHERE  ((`to_id` = $uid and `from_id` != $uid) or (`from_id` = $uid and `to_id` != $uid))
            AND created_at BETWEEN '".$s."' AND '".$e."'
            order by `created_at` desc 
        "));
        $saveMessages = Message::chatArray($uid, $messages, 1);
        return $saveMessages;
    }

    public static function ChcekReplyMsg($uid, $sid,$time){
        return  Message::where([['to_id', $sid],['from_id', $uid],['created_at', '>=', $time]])->first() !== null;
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

        if(isset($createTempTables) && $createTempTables){
            $messages = DB::select(DB::raw("
                select * from `temp_m` 
                WHERE  (`to_id` = $uid and `from_id` != $uid) or (`from_id` = $uid and `to_id` != $uid) 
                order by `created_at` desc 
            "));
        }

        if($isVip == 1)
            $saveMessages = Message::chatArrayAJAX($uid, $messages, 1);
        else if($isVip == 0) {
            $saveMessages = Message::chatArrayAJAX($uid, $messages, 0, $noVipCount);
        }

        if(count($saveMessages) == 0){
            return array_values(['No data', self::$date]);
        }
        else{
            return Message::sortMessages($saveMessages);
        }
    }

    public static function allSendersAJAX($uid, $isVip)
    {
        $userBlockList = Blocked::select('blocked_id')->where('member_id', $uid)->get();
        $banned_users = \App\Services\UserService::getBannedId($uid);
        $messages = Message::where([['to_id', $uid], ['from_id', '!=', $uid]])->orWhere([['from_id', $uid], ['to_id', '!=',$uid]])->orderBy('created_at', 'desc');
        $messages->whereNotIn('to_id', $userBlockList);
        $messages->whereNotIn('from_id', $userBlockList);
        $messages->whereNotIn('to_id', $banned_users);
        $messages->whereNotIn('from_id', $banned_users);
        $messages = $messages->get();

        if($isVip == 1)
            $saveMessages = Message::chatArrayAJAX($uid, $messages, 1);
        else if($isVip == 0) {
            $saveMessages = Message::chatArrayAJAX($uid, $messages, 0);
        }

        if(count($saveMessages) == 0){
            return array_values(['No data']);
        }
        else{
            return Message::sortMessages($saveMessages);
        }
    }

    public static function sortMessages($messages){
        if ($messages instanceof Illuminate\Database\Eloquent\Collection) {
            $messages = $messages->toArray();
        }
        $user = Auth::user();
        $block_people =  Config::get('social.block.block-people');
        $userBlockList = \App\Models\Blocked::select('blocked_id')->where('member_id', $user->id)->get();
        $banned_users = \App\Services\UserService::getBannedId($user->id);
        $isVip = $user->isVip();
        foreach ($messages as $key => &$message){
            $to_id = isset($message["to_id"]) ? $message["to_id"] : null;
            $from_id = isset($message["from_id"]) ? $message["from_id"] : null;
            if($banned_users->contains('member_id', $to_id)){
                unset($messages[$key]);
                continue;
            }
            if($banned_users->contains('member_id', $from_id) && $from_id != $user->id){
                unset($messages[$key]);
                continue;
            }
            if($userBlockList->contains('member_id', $from_id) || $userBlockList->contains('member_id', $to_id)){
                unset($messages[$key]);
                continue;
            }
            if($message['to_id'] == $user->id) {
                $msgUser = \App\Models\User::findById($from_id);
            }
            else if($message['from_id'] == $user->id) {
                $msgUser =  \App\Models\User::findById($to_id);
            }
            if(!isset($msgUser)){
                unset($messages[$key]);
                continue;
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
                if(isset($latestMessage->isPreferred)){
                    $message['isPreferred'] = 1;
                    $message['button'] = $latestMessage->button;
                }
            }
            $messages[$key]['user_id'] = $msgUser->id;
            if(isset($latestMessage)){
                $messages[$key]['read'] = $latestMessage->read;
                $messages[$key]['created_at'] = $latestMessage['created_at']->toDateTimeString();
            }
            else{
                $messages[$key]['read'] = '';
                $messages[$key]['created_at'] = '';
            }
            $messages[$key]['user_name'] = $msgUser->name;
            $messages[$key]['isAvatarHidden'] = $msgUser->user_meta->isAvatarHidden;
            $messages[$key]['pic'] = $msgUser->user_meta->pic;
            $messages[$key]['content'] = $latestMessage == null ? '' : $latestMessage->content;
        }
        $messages['date'] = self::$date;
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

    /**
     * 取得最新私人訊息
     * @param  Int $user_id
     * @param  Int $targetUser_id
     */
    public static function latestMessage($user_id, $targetUser_id, $userBlockList = null)
    {
        $uid = $user_id;
        $sid = $targetUser_id;
        if($userBlockList && in_array($sid, $userBlockList->toArray())) {
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

    public static function allToFromSender($uid, $sid, $includeDeleted = false) {
        $user = \View::shared('user');
        if(!$user){
            $user = User::find($uid);
        }
        $isAdminSender = AdminService::checkAdmin()->id==$sid;
		if(!$isAdminSender) $includeDeleted = false;
        if($user->isVip()) {
            self::$date =\Carbon\Carbon::parse("180 days ago")->toDateTimeString();
        }else {
            self::$date = \Carbon\Carbon::parse("30 days ago")->toDateTimeString();
        }
		
		$min_bad_date = self::getNotShowBadUserDate($uid, $sid);
				
        $block = Blocked::where('member_id',$sid)->where('blocked_id', $uid)->get()->first();
        if($isAdminSender)
            $query = Message::whereNotNull('id');
        else
            $query = Message::where('created_at','>=',self::$date);
        $query = $query->where(function ($query) use ($uid,$sid,$isAdminSender,$includeDeleted) {
			$whereArr1 = [['to_id', $uid],['from_id', $sid]];
			$whereArr2 = [['from_id', $uid],['to_id', $sid]];
			if(!$includeDeleted) {
				array_push($whereArr1,['is_single_delete_1','<>',$uid],['is_row_delete_1','<>',$uid]);
				array_push($whereArr2,['is_single_delete_1','<>',$uid],['is_row_delete_1','<>',$uid]);
			}
            $query->where($whereArr1);
			
			if(!$isAdminSender)
                $query->orWhere($whereArr2);
        });
        
        if($isAdminSender) return $query->orderBy('created_at', 'desc')->paginate(10);
        
        if($block) {
            $query = $query->where('from_id', '<>', $block->member_id);
        }
		
		if($min_bad_date) {
			$query = $query->where('created_at', '<', $min_bad_date);
		}

        $query = $query->where('created_at','>=',self::$date)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return $query;
    }

    public static function allToFromSenderAdmin($uid, $sid) {
        self::$date =\Carbon\Carbon::parse("180 days ago")->toDateTimeString();
        $query = Message::where('created_at','>=',self::$date);
        $query = $query->where(function ($query) use ($uid,$sid) {
            $query->where([['to_id', $uid],['from_id', $sid]])
                ->orWhere([['from_id', $uid],['to_id', $sid]]);
        });

        $query = $query->where('created_at','>=',self::$date)
            ->orderBy('created_at', 'asc')
            ->paginate(10);
        return $query;
    }

    public static function unread($uid, $tinker = false)
    {
        $user = \View::shared('user');
        if(!$user){
            $user = User::find($uid);
        }
        if($user->isVip()) {
            self::$date =\Carbon\Carbon::parse("180 days ago")->toDateTimeString();
        }else {
            self::$date = \Carbon\Carbon::parse("30 days ago")->toDateTimeString();
        }
        /**
         * 效能調整：使用左結合取代 where in 以取得更好的效能
         *
         * @author LZong <lzong.tw@gmail.com>
         */
        $query = Message::from('message as m')
                        ->leftJoin('users as u', 'u.id', '=', 'm.from_id')
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
        $all_msg = $query->whereNotNull('u.id')
                        ->whereNotNull('u2.id')
                        ->whereNull('b1.member_id')
                        ->whereNull('b3.target')
                        ->whereNull('b5.blocked_id')
                        ->whereNull('b6.blocked_id')
                        ->whereNull('b7.member_id')
                        ->where(function($query)use($uid){
                            $query->where([
                                ['m.to_id', $uid],
                                ['m.from_id', '!=', $uid],
                                ['m.from_id','!=',AdminService::checkAdmin()->id]
                            ]);
                        })
                        ->where([['m.is_row_delete_1','<>',$uid],['m.is_single_delete_1', '<>' ,$uid], ['m.all_delete_count', '<>' ,$uid],['m.is_row_delete_2', '<>' ,$uid],['m.is_single_delete_2', '<>' ,$uid],['m.temp_id', '=', 0]])
                        ->where('m.read', 'N')
                        ->where([['m.created_at','>=',self::$date]])
                        ->whereRaw('m.created_at < IFNULL(b1.created_at,"2999-12-31 23:59:59")')
                        ->whereRaw('m.created_at < IFNULL(b2.created_at,"2999-12-31 23:59:59")')
                        ->whereRaw('m.created_at < IFNULL(b3.created_at,"2999-12-31 23:59:59")')
                        ->whereRaw('m.created_at < IFNULL(b4.created_at,"2999-12-31 23:59:59")');

		$all_msg = $all_msg->selectRaw('u.engroup AS u_engroup,u2.engroup AS u2_engroup')->get();
		
		foreach($all_msg  as $k=>$msg) {
			if($msg->u_engroup==$msg->u2_engroup)  $all_msg->forget($k);
		}
		
        if($tinker){
            dd($all_msg);
        } 

        $unreadCount = $all_msg->count();

        return $unreadCount;
    }

    public static function read($message, $uid)
    {
        if ($message->to_id == $uid)
        {
            $message->read = 'Y';
            $message->save();
            \App\Events\ChatRead::dispatch($message->id, $message->from_id, $message->to_id);
            \App\Events\ChatReadSelf::dispatch($uid);
        }
    }

    public function compactRead(){
        $this->read($this, $this->to_id);
    }

    public static function allMessage($uid, $tinker = false)
    {
        $admin_id = AdminService::checkAdmin()->id;
        $user = \View::shared('user');
        if(!$user){
            $user = User::find($uid);
        }

        /**
         * 效能調整：使用左結合取代 where in 以取得更好的效能
         *
         * @author LZong <lzong.tw@gmail.com>
         */
        $query = Message::from('message as m')
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

        $all_msg = $query->whereNotNull('u1.id')->whereNotNull('u2.id')
            ->whereNull('b1.member_id')
            ->whereNull('b3.target')
            ->whereNull('b5.blocked_id')
            ->whereNull('b6.blocked_id')
            ->whereNull('b7.member_id')
            ->where(function ($query) use ($uid,$admin_id) {
                $query->where([['m.to_id', $uid], ['m.from_id', '!=', $uid],['m.from_id','!=',$admin_id]])
                    ->orWhere([['m.from_id', $uid], ['m.to_id', '!=',$uid],['m.to_id','!=',$admin_id]]);
            });
        $query = $query->where([['m.is_row_delete_1','<>',$uid],['m.is_single_delete_1', '<>' ,$uid], ['m.all_delete_count', '<>' ,$uid],['m.is_row_delete_2', '<>' ,$uid],['m.is_single_delete_2', '<>' ,$uid],['m.temp_id', '=', 0]]);
        $query->whereRaw('m.created_at < IFNULL(b1.created_at,"2999-12-31 23:59:59")');
        $query->whereRaw('m.created_at < IFNULL(b2.created_at,"2999-12-31 23:59:59")');
        $query->whereRaw('m.created_at < IFNULL(b3.created_at,"2999-12-31 23:59:59")');
        $query->whereRaw('m.created_at < IFNULL(b4.created_at,"2999-12-31 23:59:59")');
        
		$all_msg = $all_msg->selectRaw('u1.engroup AS u1_engroup,u2.engroup AS u2_engroup')->get();
		
		foreach($all_msg  as $k=>$msg) {
			if($msg->u1_engroup==$msg->u2_engroup)  $all_msg->forget($k);
		}
		
        if($tinker){
            /* 除錯用 SQL 
             * select u1.engroup AS u1_engroup,u2.engroup AS u2_engroup from `message` as `m`
            left join `users` as `u1` on `u1`.`id` = `m`.`from_id`
            left join `users` as `u2` on `u2`.`id` = `m`.`to_id`
            left join `banned_users` as `b1` on `b1`.`member_id` = `m`.`from_id`
            left join `banned_users` as `b2` on `b2`.`member_id` = `m`.`to_id`
            left join `banned_users_implicitly` as `b3` on `b3`.`target` = `m`.`from_id`
            left join `banned_users_implicitly` as `b4` on `b4`.`target` = `m`.`to_id`
            left join `blocked` as `b5` on `b5`.`blocked_id` = `m`.`from_id` and `b5`.`member_id` = ?
            left join `blocked` as `b6` on `b6`.`blocked_id` = `m`.`to_id` and `b6`.`member_id` = ?
            left join `blocked` as `b7` on `b7`.`member_id` = `m`.`from_id` and `b7`.`blocked_id` = ?
            where `u1`.`id` is not null
            and `u2`.`id` is not null
            and `b1`.`member_id` is null
            and `b3`.`target` is null
            and `b5`.`blocked_id` is null
            and `b6`.`blocked_id` is null
            and `b7`.`member_id` is null
            and (
                (`m`.`to_id` = ? and `m`.`from_id` != ? and `m`.`from_id` != ?)
                or (`m`.`from_id` = ? and `m`.`to_id` != ? and `m`.`to_id` != ?)
            )
            and (
                `m`.`is_row_delete_1` <> ? and `m`.`is_single_delete_1` <> ?
                and `m`.`all_delete_count` <> ? and `m`.`is_row_delete_2` <> ?
                and `m`.`is_single_delete_2` <> ? and `m`.`temp_id` = ?
            )
            and m.created_at < IFNULL(b1.created_at,"2999-12-31 23:59:59")
            and m.created_at < IFNULL(b2.created_at,"2999-12-31 23:59:59")
            and m.created_at < IFNULL(b3.created_at,"2999-12-31 23:59:59")
            and m.created_at < IFNULL(b4.created_at,"2999-12-31 23:59:59")
            */
            dd($all_msg);
        }

        $allMessageCount = $all_msg->count();

        return $allMessageCount;
    }

    public static function post($from_id, $to_id, $msg, $tip_action = true, $sys_notice = 0)
    {
        $message = new Message;
        $message->from_id = $from_id;
        $message->to_id = $to_id;
        $message->content = $msg;
        $message->all_delete_count = 0;
        $message->is_row_delete_1 = 0;
        $message->is_row_delete_2 = 0;
        if($tip_action == false) {
            $message->is_single_delete_1 = $to_id;
        }
        else{
            $message->is_single_delete_1 = 0;
        }
        $message->is_single_delete_2 = 0;
        $message->temp_id = 0;
        $message->sys_notice = $sys_notice;
        $message->save();
        $curUser = User::findById($to_id);
        if ($curUser->user_meta->notifmessage !== '不通知')
        {
        // $curUser->notify(new MessageEmail($from_id, $to_id, $msg));
        }

        return $message;
    }

    public static function cutLargeString($string) {
        //dd($string);
        $string = strip_tags($string);
        //echo $string;
        if (strlen($string) > 20) {

            // truncate string
            return mb_substr($string, 0, 20, "utf-8") . '...';

            //echo $stringCut;
            // make sure it ends in a word so assassinate doesn't become ass...
            //$string = substr($stringCut, 0, strrpos($stringCut, '')). '...';
        }
        return $string;
    }

    /**
     * 是否回應車馬費邀請的訊息
     * 
     * @param int from_id inviter
     * @param int to_id invited
     * @param date invied date
     *
     * @return bool
     */

    public static function isRepliedInvitation($from_id, $to_id, $invitedDate)
    {
        $message = Message::where('from_id', $to_id)
                ->where('to_id', $from_id)
                ->where('created_at', '>', $invitedDate)
                ->where('content', 'NOT LIKE', '系統通知%');
        return $message ? true : false;
    }

    /**
     * does other side reply the message
     * 
     * @param int from_id The user'id who sends the message.
     * @param int to_id The user'id who receive.
     * @param int msg_id message id
     * 
     * @return bool
     */

    public static function isReplied($from_id, $to_id, $msg_id)
    {
        $message = Message::where('id', '>', $msg_id)
                ->where('from_id', $to_id)
                ->where('to_id', $from_id);
        return $message ? true : false;
    }
	
	public static function getNotShowBadUserDate($uid, $sid) {
		$banned_sender_date = $banned_curuser_date = $bannedim_sender_date = $bannedim_curuser_date = $blockDate = '9999-12-31';
		$banned_sender = banned_users::where('member_id',$sid)->get()->first();
		if($banned_sender) $banned_sender_date = $banned_sender->created_at->toDateTimeString();
		$banned_curuser = banned_users::where('member_id',$uid)->get()->first();
		if($banned_curuser) $banned_curuser_date = $banned_curuser->created_at->toDateTimeString();	
		$bannedim_sender = BannedUsersImplicitly::where('target',$sid)->get()->first();
		if($bannedim_sender) $bannedim_sender_date = $bannedim_sender->created_at->toDateTimeString();
		$bannedim_curuser = BannedUsersImplicitly::where('target',$uid)->get()->first();
		if($bannedim_curuser) $bannedim_curuser_date = $bannedim_curuser->created_at->toDateTimeString();

        if(Blocked::isBlocked($uid, $sid)) {
            $blockTime = Blocked::getBlockTime($uid, $sid);
			$blockDate = $blockTime->created_at->toDateTimeString();
        }
		
		return min($banned_sender_date,$banned_curuser_date,$bannedim_sender_date,$bannedim_curuser_date,$blockDate);		
		
	}
    
}
