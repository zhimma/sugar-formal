<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BannedUsersImplicitly;
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
use App\Services\UserService;

use function Clue\StreamFilter\fun;

class Message extends Model
{
    use SoftDeletes;

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
        'read',
        'parent_msg',
        'client_id',
        'parent_client_id'        
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
    
    public function parent_message() 
    {
        return $this->belongsTo(Message::class, 'parent_msg', 'id');
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

    public static function reportMessage($id, $content, $images = null) {
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
        event(new \App\Events\CheckWarnedOfReport($message->from_id));
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
            $admin = User::select('id')->where('email', Config::get('social.admin.user-email'))->get()->first();
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
            $admin = User::select('id')->where('email', Config::get('social.admin.user-email'))->get()->first();
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

    public static function allToFromSender($uid, $sid, $includeDeleted = false, $sys_notice = NULL) {
        $user = \View::shared('user');
        if(!$user){
            $user = User::find($uid);
        }
        $isAdminSender = AdminService::checkAdmin()->id==$sid;
		if(!$isAdminSender) 
        {
            $includeUnsend = $includeDeleted;
            if($user->isVip()) {
                self::$date =\Carbon\Carbon::parse("180 days ago")->toDateTimeString();
            }else {
                self::$date = \Carbon\Carbon::parse("30 days ago")->toDateTimeString();
            }   
           
            $query = Message::where('created_at','>=',self::$date);
            
            if($includeUnsend) { 
                $query->withTrashed()->where(function ($q) {
                    $q->where(function ($q1) {
                        $q1->where('unsend', 0)->whereNull('deleted_at');
                    })
                    ->orWhere(function ($q2) {
                        $q2->where('unsend', 1);
                    });
                });
            }
        }  
        else {
            $query = Message::whereNotNull('id');
        }
		
		$min_bad_date = self::getNotShowBadUserDate($uid, $sid);
				
        $block = Blocked::where('member_id',$sid)->where('blocked_id', $uid)->get()->first();

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
        
        if($isAdminSender) return $query->orderBy('created_at', 'desc')->paginate(10);//->get();
        
        if($block) {
            $query = $query->where('from_id', '<>', $block->member_id);
        }
		
		if($min_bad_date) {
			$query = $query->where('created_at', '<', $min_bad_date);
		}

        if($sys_notice != NULL){
            if($sys_notice == 1){
                $query = $query->where('sys_notice',1);
            }else if($sys_notice == 0){
                $query = $query->where('sys_notice',0)->orWhereNull('sys_notice');
            }
            return $query->orderBy('created_at', 'desc')->get();            
        }
        $query = $query->where('created_at','>=',self::$date)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        return $query;
    }

    public static function allToFromSenderAdmin($uid, $sid) {
        self::$date =\Carbon\Carbon::parse("180 days ago")->toDateTimeString();
        $query = Message::withTrashed()->where('created_at','>=',self::$date);
        $query = $query->where(function ($query) use ($uid,$sid) {
            $query->where([['to_id', $uid],['from_id', $sid]])
                ->orWhere([['from_id', $uid],['to_id', $sid]]);
        });

        $query = $query->where('created_at','>=',self::$date)
            ->orderBy('created_at', 'asc')
            ->paginate(100);
        return $query;
    }

    public static function unread($uid, $tinker = false)
    {
        $user = \View::shared('user');
        if(!$user){
            $user = User::find($uid);
        }
        $banned_users = banned_users::where('member_id', $uid)->first();
        $BannedUsersImplicitly = BannedUsersImplicitly::where('target', $uid)->first();
        if((isset($banned_users) && ($banned_users->expire_date == null || $banned_users->expire_date >= Carbon::now())) || isset($BannedUsersImplicitly)){
            return 0;
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

        //增加篩選過濾條件
        $inbox_refuse_set = InboxRefuseSet::where('user_id', $uid)->first();

        $query = Message::leftJoin('users as u1', 'u1.id', '=', 'message.from_id')
                        ->leftJoin('users as u2', 'u2.id', '=', 'message.to_id')
                        ->leftJoin('banned_users as b1', 'b1.member_id', '=', 'message.from_id')
                        ->leftJoin('banned_users as b2', 'b2.member_id', '=', 'message.to_id')
                        ->leftJoin('banned_users_implicitly as b3', 'b3.target', '=', 'message.from_id')
                        ->leftJoin('banned_users_implicitly as b4', 'b4.target', '=', 'message.to_id')
                        ->leftJoin('blocked as b5', function($join) use($uid) {
                            $join->on('b5.blocked_id', '=', 'message.from_id')
                                ->where('b5.member_id', $uid); })
                        ->leftJoin('blocked as b6', function($join) use($uid) {
                            $join->on('b6.blocked_id', '=', 'message.to_id')
                                ->where('b6.member_id', $uid); })
                        ->leftJoin('blocked as b7', function($join) use($uid) {
                            $join->on('b7.member_id', '=', 'message.from_id')
                                ->where('b7.blocked_id', $uid); });
        
        //增加篩選過濾條件
        if($inbox_refuse_set)
        {
            if($inbox_refuse_set->isrefused_vip_user || $inbox_refuse_set->isrefused_warned_user || $inbox_refuse_set->isrefused_common_user)
            {
                $query = $query->leftJoin('member_vip as vip', function($join) {
                                    $join->on('vip.member_id', '=', 'message.from_id')
                                        ->where('vip.active', 1); })
                                ->leftJoin('user_meta as um', 'um.user_id', '=', 'message.from_id')
                                ->leftJoin('warned_users as w', 'w.member_id', '=', 'message.from_id');
            }
            if($inbox_refuse_set->refuse_pr != -1)
            {
                $query = $query->leftJoin('pr_log as pr', 'pr.user_id', '=', 'message.from_id');
            }
        }
        
        $all_msg = $query->whereNotNull('u1.id')
                        ->whereNotNull('u2.id')
                        ->whereNull('b1.member_id')
                        ->whereNull('b3.target')
                        ->whereNull('b5.blocked_id')
                        ->whereNull('b6.blocked_id')
                        ->whereNull('b7.member_id')
                        ->where(function($query)use($uid){
                            $query->where([
                                ['message.to_id', $uid],
                                ['message.from_id', '!=', $uid],
                                ['message.from_id','!=',AdminService::checkAdmin()->id]
                            ]);
                        })
                        ->where([['message.is_row_delete_1','<>',$uid],['message.is_single_delete_1', '<>' ,$uid], ['message.all_delete_count', '<>' ,$uid],['message.is_row_delete_2', '<>' ,$uid],['message.is_single_delete_2', '<>' ,$uid],['message.temp_id', '=', 0]])
                        ->where('message.read', 'N')
                        ->where([['message.created_at','>=',self::$date]])
                        ->whereRaw('message.created_at < IFNULL(b1.created_at,"2999-12-31 23:59:59")')
                        ->whereRaw('message.created_at < IFNULL(b2.created_at,"2999-12-31 23:59:59")')
                        ->whereRaw('message.created_at < IFNULL(b3.created_at,"2999-12-31 23:59:59")')
                        ->whereRaw('message.created_at < IFNULL(b4.created_at,"2999-12-31 23:59:59")');

        //增加篩選過濾條件
        if($inbox_refuse_set)
        {
            if($inbox_refuse_set->isrefused_vip_user)
            {
                $all_msg = $all_msg->whereNull('vip.id');
            }
            if($inbox_refuse_set->isrefused_common_user)
            {
                $all_msg = $all_msg->whereNotNull('vip.id');
            }
            if($inbox_refuse_set->isrefused_warned_user)
            {
                $all_msg = $all_msg->where('um.isWarned','=',0);
                $all_msg = $all_msg->whereNull('w.id');
            }
            if($inbox_refuse_set->refuse_pr != -1)
            {
                $all_msg = $all_msg->whereNotNull('pr.pr')->where('pr.pr','!=','無')->where('pr.pr','>=',$inbox_refuse_set->refuse_pr);
            }
            if($inbox_refuse_set->refuse_register_days != 0)
            {
                $rtime = Carbon::now()->subDays($inbox_refuse_set->refuse_register_days);
                $all_msg = $all_msg->where('u1.created_at', '<=', $rtime);
            }
        }

        if($user->id != 1049){
            $all_msg = $all_msg->where(function($query){
                $query->where(DB::raw('(u1.engroup + u2.engroup)'), '<>', '2');
                $query->orWhere(DB::raw('(u1.engroup + u2.engroup)'), '<>', '4');
            });
        }

		$all_msg = $all_msg->get();

        if($tinker){
            dd($all_msg);
        } 

        //增加篩選過濾條件
        if($inbox_refuse_set)
        {
            if($inbox_refuse_set->refuse_canned_message_pr != -1)
            {
                $count = 0;
                foreach ($all_msg as $msg)
                {
                    $can_pr = UserService::computeCanMessagePercent_7($msg['from_id']);
                    $can_pr = trim($can_pr,'%');
                    if($can_pr > $inbox_refuse_set->refuse_canned_message_pr)
                    {
                        unset($all_msg[$count]);
                    }
                    $count = $count+1;
                }
            }
        }

        $unreadCount = $all_msg->count();

        return $unreadCount;
    }

    public static function read($message, $uid)
    {
        if ($message->to_id == $uid && $message->read!='Y')
        {
            $message->read = 'Y';
            $message->save();
            \App\Events\ChatRead::dispatch($message->id, $message->from_id, $message->to_id);
            \App\Events\ChatReadSelf::dispatch($uid);
        }
    }

    public function compactRead(){
        $this->read($this, request()->user()->id);
    }

    public static function allMessage($uid, $tinker = false)
    {
        $admin_id = AdminService::checkAdmin()->id;
        $user = \View::shared('user');
        if(!$user){
            $user = User::find($uid);
        }

        $banned_users = banned_users::where('member_id', $uid)->first();
        $BannedUsersImplicitly = BannedUsersImplicitly::where('target', $uid)->first();
        if( (isset($banned_users) && ($banned_users->expire_date == null || $banned_users->expire_date >= Carbon::now())) || isset($BannedUsersImplicitly)){
            return false;
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
        $query = Message::with(['sender', 'receiver', 'sender.aw_relation', 'receiver.aw_relation'])
            ->select("message.*")
            //->from('message as m')
            ->leftJoin('users as u1', 'u1.id', '=', 'message.from_id')
            ->leftJoin('users as u2', 'u2.id', '=', 'message.to_id')
            ->leftJoin('banned_users as b1', 'b1.member_id', '=', 'message.from_id')
            ->leftJoin('banned_users as b2', 'b2.member_id', '=', 'message.to_id')
            ->leftJoin('banned_users_implicitly as b3', 'b3.target', '=', 'message.from_id')
            ->leftJoin('banned_users_implicitly as b4', 'b4.target', '=', 'message.to_id')
            ->leftJoin('blocked as b5', function($join) use($uid) {
                $join->on('b5.blocked_id', '=', 'message.from_id')
                    ->where('b5.member_id', $uid); })
            ->leftJoin('blocked as b6', function($join) use($uid) {
                $join->on('b6.blocked_id', '=', 'message.to_id')
                    ->where('b6.member_id', $uid); })
            ->leftJoin('blocked as b7', function($join) use($uid) {
                $join->on('b7.member_id', '=', 'message.from_id')
                    ->where('b7.blocked_id', $uid); })
            ->whereNotNull('u1.id')
            ->whereNotNull('u2.id')
            ->whereNull('b1.member_id')
            ->whereNull('b2.member_id')
            ->whereNull('b3.target')
            ->whereNull('b4.target')
            ->whereNull('b5.blocked_id')
            ->whereNull('b6.blocked_id')
            ->whereNull('b7.member_id')
            ->where(function ($innerQuery) use ($uid, $admin_id) {
                $innerQuery->where([['message.to_id', $uid], ['message.from_id', '!=', $uid],['message.from_id','!=',$admin_id]])
                    ->orWhere([['message.from_id', $uid], ['message.to_id', '!=',$uid],['message.to_id','!=',$admin_id]]);
            });
        $query->where([['message.created_at','>=',self::$date]]);
        $query->whereRaw('message.created_at < IFNULL(b1.created_at,"2999-12-31 23:59:59")');
        $query->whereRaw('message.created_at < IFNULL(b2.created_at,"2999-12-31 23:59:59")');
        $query->whereRaw('message.created_at < IFNULL(b3.created_at,"2999-12-31 23:59:59")');
        $query->whereRaw('message.created_at < IFNULL(b4.created_at,"2999-12-31 23:59:59")');
        $query->where([['message.is_row_delete_1','<>',$uid],['message.is_single_delete_1', '<>' ,$uid], ['message.all_delete_count', '<>' ,$uid],['message.is_row_delete_2', '<>' ,$uid],['message.is_single_delete_2', '<>' ,$uid],['message.temp_id', '=', 0]]);

        if($user->id != 1049){
            $query->where(function($query){
                $query->where(DB::raw('(u1.engroup + u2.engroup)'), '<>', '2');
                $query->orWhere(DB::raw('(u1.engroup + u2.engroup)'), '<>', '4');
            });
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
            and `b2`.`member_id` is null
            and `b3`.`target` is null
            and `b4`.`target` is null
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
            dd($query);
        }

        return $query->count();
    }

    public static function post($from_id, $to_id, $msg, $tip_action = true, $sys_notice = 0,$parent_msg=null)
    {
        $message = new Message;
        $message->from_id = $from_id;
        $message->to_id = $to_id;
        $message->content = $msg;
        $message->parent_msg = $parent_msg;
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
    

    public static function postByArr($arr)
    {
        $tip_action = array_key_exists('tip_action',$arr)?$arr['tip_action']:true;
        $sys_notice = array_key_exists('sys_notice',$arr)?$arr['sys_notice']:0;
        $message = new Message;
        $message->from_id = $arr['from_id']??null;
        $message->to_id = $arr['to']??null;
        $message->content = array_key_exists('msg',$arr)?$arr['msg']:'';
        $message->parent_msg = array_key_exists('parent',$arr)?$arr['parent']:'';
        $message->client_id = array_key_exists('client_id',$arr)?$arr['client_id']:'';
        $message->parent_client_id = array_key_exists('parent_client',$arr)?$arr['parent_client']:'';

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
        if($message->save()) {
            if($message->client_id??null ) {
                Message::where('parent_client_id',$message->client_id)
                    ->update(['parent_msg'=>$message->id]);
            }
            
            if(!($message->parent_msg??null) && ($message->parent_client_id??null)){
                $parent_entry = Message::where('client_id',$message->parent_client_id)->first();
                if($parent_entry->id??null) {
                    $message->parent_msg = $parent_entry->id;
                    Message::where('parent_client_id',$message->parent_client_id)->update(['parent_msg'=>$parent_entry->id]);
                    Log::info('Message Updated');
                }  
            }            
            
            if(($message->parent_msg??null) && ($message->parent_client_id??null)){
                $parent_entryList = Message::where('parent_msg',$message->parent_msg)->get();
                $parent_client_entryList = Message::where('parent_client_id',$message->parent_client_id)->get();
                if(count($parent_entryList)!=count($parent_client_entryList)) {
                    Message::where('parent_client_id',$message->parent_client_id)->update(['parent_msg'=>$message->parent_msg]);
                }  
            }        
        }
        $curUser = User::findById($message->to_id);
        if (($curUser->user_meta->notifmessage??null) !== '不通知')
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
		if($banned_sender->created_at ?? false) $banned_sender_date = $banned_sender->created_at->toDateTimeString();
		$banned_curuser = banned_users::where('member_id',$uid)->get()->first();
		if($banned_curuser->created_at ?? false) $banned_curuser_date = $banned_curuser->created_at->toDateTimeString();	
		$bannedim_sender = BannedUsersImplicitly::where('target',$sid)->get()->first();
		if($bannedim_sender->created_at ?? false) $bannedim_sender_date = $bannedim_sender->created_at->toDateTimeString();
		$bannedim_curuser = BannedUsersImplicitly::where('target',$uid)->get()->first();
		if($bannedim_curuser->created_at ?? false) $bannedim_curuser_date = $bannedim_curuser->created_at->toDateTimeString();

        if(Blocked::isBlocked($uid, $sid)) {
            $blockTime = Blocked::getBlockTime($uid, $sid);
			if($blockTime->created_at ?? false)
			$blockDate = $blockTime->created_at->toDateTimeString();
        }
		
		return min($banned_sender_date,$banned_curuser_date,$bannedim_sender_date,$bannedim_curuser_date,$blockDate);		
		
	}
    
    protected static function booted()
    {
        Message::addGlobalScope('created_at', function ($q) {
            $q->where('message.created_at', '>', Message::implicitLimitDate());
        });
    }  

    public function scopeImplicitWhere($q, $alias)
    {
        return $q->where($alias.'.created_at', '>', Message::implicitLimitDate());
    } 

    public static function implicitLimitDate() {
        return Carbon::now()->subMonths(3)->startOfDay();
    }    
    
}
