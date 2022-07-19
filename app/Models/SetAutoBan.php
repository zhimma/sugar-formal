<?php

namespace App\Models;

use App\Models\LogUserLogin;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\Message;
use App\Models\SimpleTables\warned_users;
use App\Models\SimpleTables\banned_users;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Jobs\AutoBanCaller;
use App\Jobs\LogoutAutoBan;
use Carbon\Carbon;
use App\Services\ImagesCompareService;

class SetAutoBan extends Model
{
    use SoftDeletes;
    //
    protected $table = 'set_auto_ban';
	
	public $timestamps = false;

    //自動封鎖 用後台設定的關鍵字查詢
    public static function auto_ban($uid)
    {
        Log::info('start_SetAutoBan_auto_ban');
        AutoBanCaller::dispatch($uid)->onConnection('database-long')->onQueue('long-jobs')->delay(SetAutoBan::_getDelayTime());
    }
    
    public static function autoBan($uid)
    {
        $user = User::findById($uid);
        try {
            if(isset($user) && $user->can('admin')){
                return;
            }
        }
        catch (\Exception $e){

        }
        $auto_ban = SetAutoBan::select('type', 'set_ban', 'id', 'content', 'expired_days')->orderBy('id', 'desc')->get();
        foreach ($auto_ban as $ban_set) {
            $content = $ban_set->content;
            $violation = false;
            switch ($ban_set->type) {
                case 'name':
                    if(User::where('id', $uid)->where('name','like','%'.$content.'%')->first() != null) $violation = true;
                    break;
                case 'email':
                    if(User::where('id', $uid)->where('email','like','%'.$content.'%')->first() != null) $violation = true;
                    break;
                case 'title':
                    if(User::where('id', $uid)->where('title','like','%'.$content.'%')->first() != null) $violation = true;
                    break;
                case 'about':
                    if(UserMeta::where('user_id',$uid)->where('about','like','%'.$content.'%')->first() != null) $violation = true;
                    break;
                case 'style':
                    if(UserMeta::where('user_id',$uid)->where('style','like','%'.$content.'%')->first() != null) $violation = true;
                    break;
                case 'allcheck':
                    //全檢查判斷user與user_meta的內容 若有一個違規 就設定true
                    if( (User::where('id', $uid)->where(function($query)use($content){
                                $query->where('name', 'like', '%'.$content.'%')
                                    ->orwhere('title', 'like', '%'.$content.'%');
                            })->first() != null )
                        OR (UserMeta::where('user_id', $uid)->where(function($query)use($content){
                                $query->where('about', 'like', '%'.$content.'%')
                                    ->orwhere('style', 'like', '%'.$content.'%');
                            })->first() != null ) ){
                        $violation = true;
                    }
                    break;

                //20220629新增圖片檔名
                case 'picname':
                    if(UserMeta::where('user_id',$uid)->where('pic_original_name','like','%'.$content.'%')->first() != null) $violation = true;
                    
                    //有一筆違規就可以封鎖了
                    if(MemberPic::where('member_id',$uid)->where('original_name','like','%'.$content.'%')->first() != null) $violation = true;
                    break;
                //20220629新增圖片檔名   

                case 'pic':
                    $ban_encode_entry = ImagesCompareService::getCompareEncodeByPic($content);
                    if($ban_encode_entry??null) {
                        if(($user->meta->pic??null) && $ban_encode_entry->file_md5==ImagesCompareService::getCompareEncodeByPic($user->meta->pic)->file_md5) {
                            $violation = true;
                        }
                        
                        if(!$violation) {
                            $memPics = $user->pic_withTrashed()->pluck('pic')->all();
                            $memPicMd5s =  ImagesCompareService::getFileMd5ArrByPicArr($memPics); 
                            if(in_array($memPics,$memPicMd5s)) $violation = true;
                        }
                        
                        if(!$violation) {
                            $delAvatars = $user->avatar_deleted()->pluck('pic')->all();
                            $delAvatarMd5s =  ImagesCompareService::getFileMd5ArrByPicArr($delAvatars); 
                            if(in_array($delAvatars,$delAvatarMd5s)) $violation = true;
                        }
                    }
                break;                       
                default:
                    break;
            }

            if($violation){
                if($ban_set->set_ban == 1 && banned_users::where('member_id', $uid)->first() == null){
                    //直接封鎖
                    $userBanned = new banned_users;
                    if($user->engroup==2 ) {
                       if(!($user->advance_auth_status??null)) {
                           $userBanned->adv_auth=1;
                       }
                       else $userBanned=null;
                    } 
                    
                    if($userBanned) {
                        $userBanned->member_id = $uid;
                        $userBanned->reason = "系統原因($ban_set->id)";
                        $userBanned->save();
                        //寫入log
                        DB::table('is_banned_log')->insert(['user_id' => $uid, 'reason' => "系統原因($ban_set->id)"]);
                    }
                }
                elseif($ban_set->set_ban == 2 && BannedUsersImplicitly::where('target', $uid)->first() == null){
                    //隱性封鎖
                    BannedUsersImplicitly::insert(['fp' => 'Line 79, BannedInUserInfo, ban_set ID: ' . $ban_set->id . ', content: ' . $content, 'user_id' => 0, 'target' => $uid]);
                }
                elseif($ban_set->set_ban == 3 && warned_users::where('member_id', $uid)->first() == null){
                    //警示會員
                    $userWarned = new warned_users;
                    $userWarned->member_id = $uid;
                    $userWarned->reason = "系統原因($ban_set->id)";
                    if($ban_set->expired_days !=0)
                    {
                        $userWarned->expire_date = Carbon::now()->addDays($ban_set->expired_days);
                    }
                    $userWarned->save();
                    //寫入log
                    DB::table('is_warned_log')->insert(['user_id' => $uid, 'reason' => "系統原因($ban_set->id)"]);
                    // UserMeta::where('user_id', $uid)->update(['isWarned' => 1]);
                }
                return;
            }
        }
    }

    //發訊後的自動封鎖
    public static function msg_auto_ban($uid, $toid, $msg)
    {                   
        AutoBanCaller::dispatch($uid, $toid, $msg)->onConnection('database-long')->onQueue('long-jobs')->delay(SetAutoBan::_getDelayTime());                   
    }
    
    public static function autoBanMsg($uid, $toid, $msg)
    {
        $user = User::findById($uid);
        try {
            if(isset($user) && $user->can('admin')){
                return;
            }
        }
        catch (\Exception $e){

        }
        $auto_ban = SetAutoBan::select('type', 'set_ban', 'id', 'content', 'expired_days')->where('type', 'msg')->orwhere('type', 'allcheck')->orderBy('id', 'desc')->get();
        foreach ($auto_ban as $ban_set) {
            $violation = false;
            if (Message::where('from_id', $uid)->where('to_id', $toid)->where('content', $msg)->where('content', 'like', '%' . $ban_set->content . '%')->first() != null) {
                $violation = true;
            }
            if ($violation) {
                if($ban_set->set_ban == 1 && banned_users::where('member_id', $uid)->first() == null) {
                    //直接封鎖
                    $userBanned = new banned_users;
                    $userBanned->member_id = $uid;
                    $userBanned->reason = "系統原因($ban_set->id)";
                    $userBanned->save();
                    //寫入log
                    DB::table('is_banned_log')->insert(['user_id' => $uid, 'reason' => "系統原因($ban_set->id)"]);
                }
                elseif($ban_set->set_ban == 2 && BannedUsersImplicitly::where('target', $uid)->first() == null) {
                    //隱性封鎖
                    BannedUsersImplicitly::insert(['fp' => 'Line 124, BannedInUserInfo, ban_set ID: ' . $ban_set->id . ', content: ' . $ban_set->content, 'user_id' => 0, 'target' => $uid]);
                }
                elseif($ban_set->set_ban == 3 && warned_users::where('member_id', $uid)->first() == null) {
                    //警示會員
                    $userWarned = new warned_users;
                    $userWarned->member_id = $uid;
                    $userWarned->reason = "系統原因($ban_set->id)";

                    if($ban_set->expired_days !=0)
                    {
                        $userWarned->expire_date = Carbon::now()->addDays($ban_set->expired_days);
                    }

                    $userWarned->save();
                    //寫入log
                    DB::table('is_warned_log')->insert(['user_id' => $uid, 'reason' => "系統原因($ban_set->id)"]);
                    // UserMeta::where('user_id', $uid)->update(['isWarned' => 1]);
                }
                return;
            }
        }
    }

    //登出後的警示
    public static function logout_warned($uid)
    {
        Log::Info('start_LogoutAutoBan_logout_warned');
        Log::Info($uid);
        if(\App::isProduction()) {
            LogoutAutoBan::dispatch($uid)->onConnection('sqs')->onQueue('auto-ban')->delay(SetAutoBan::_getDelayTime());
        }
        else {
            LogoutAutoBan::dispatch($uid)->onConnection('sqs')->onQueue('auto-ban-test')->delay(SetAutoBan::_getDelayTime());
        }
    }

    //登入時的警示
    public static function login_warned($uid)
    {
        $user = User::where('id', $uid)->first();
        $count_of_user_login_with_desktop = LogUserLogin::where('user_id', $uid)
                                                ->where(function($query){
                                                    $query->where('userAgent', 'like', '%' . 'Windows' . '%');
                                                    $query->orwhere('userAgent', 'like', '%' . 'Macintosh' . '%');
                                                })
                                                ->count();
        if($user->engroup==2 && !$user->isPhoneAuth() && $count_of_user_login_with_desktop>=3 && $user->created_at>\Carbon\Carbon::now()->subDays(10))
        {
            $reason = '尚未進行手機驗證';
            $userWarned = new warned_users;
            $userWarned->member_id = $uid;
            $userWarned->type = 'no_mobile_verify';
            $userWarned->reason = $reason;
            $userWarned->save();
            //寫入log
            DB::table('is_warned_log')->insert(['user_id' => $uid, 'reason' => $reason]);
        }
    }

    public static function relieve_mobile_verify_warned($uid)
    {
        warned_users::where('member_id', $uid)->where('type', 'no_mobile_verify')->delete();
    }

    public static function logoutWarned($uid)
    {
        Log::info('start_LogoutAutoBan_logoutWarned');
        $user = User::findById($uid);
        try {
            if(isset($user) && $user->can('admin')){
                return;
            }
        }
        catch (\Exception $e){

        }
        if(!$user || !$uid) {
            logger('SetAutoBan logout_warned() user not set, referer: ' . \Request::server('HTTP_REFERER'));
            return;
        }

        //執行時間預設是30秒改為無上限
        set_time_limit(-1);

        $set_auto_ban = SetAutoBan::select('type', 'set_ban', 'id', 'content','expiry', 'expired_days')->orderBy('id', 'desc');
        $auto_ban = $set_auto_ban->get();
        foreach ($auto_ban as $ban_set) {
            $content = $ban_set->content;
            $violation = false;
            switch ($ban_set->type) {
                case 'name':
                    if (User::where('id', $uid)->where('name', 'like', '%' . $content . '%')->first() != null) $violation = true;
                    break;
                case 'email':
                    if (User::where('id', $uid)->where('email', 'like', '%' . $content . '%')->first() != null) $violation = true;
                    break;
                case 'title':
                    if (User::where('id', $uid)->where('title', 'like', '%' . $content . '%')->first() != null) $violation = true;
                    break;
                case 'about':
                    if (UserMeta::where('user_id', $uid)->where('about', 'like', '%' . $content . '%')->first() != null) $violation = true;
                    break;
                case 'style':
                    if (UserMeta::where('user_id', $uid)->where('style', 'like', '%' . $content . '%')->first() != null) $violation = true;
                    break;
                case 'allcheck':
                    //全檢查判斷user與user_meta的內容 若有一個違規 就設定true
                    if ((User::where('id', $uid)->where(function ($query) use ($content) {
                                $query->where('name', 'like', '%' . $content . '%')
                                    ->orwhere('title', 'like', '%' . $content . '%');
                            })->first() != null)
                        or (UserMeta::where('user_id', $uid)->where(function ($query) use ($content) {
                                $query->where('about', 'like', '%' . $content . '%')
                                    ->orwhere('style', 'like', '%' . $content . '%');
                            })->first() != null)) {
                        $violation = true;
                    }
                    break;
                case 'cfp_id':
                    if(LogUserLogin::where('user_id',$uid)->where('cfp_id', $content)->first() != null) $violation = true;
                    break;
                case 'ip':
					if($ban_set->expiry=='0000-00-00 00:00:00') {
						$ban_set->expiry = \Carbon\Carbon::now()->addMonths(1)->format('Y-m-d H:i:s');
                        $ban_set->updated_at = now();
						$ban_set->save();						
					}
					if($ban_set->expiry<=\Carbon\Carbon::now()->format('Y-m-d H:i:s')) {
						$ban_set->delete();
						break;
					}					
                    $ip = LogUserLogin::where('user_id',$uid)->orderBy('created_at','desc')->first();
                    if($ip?->ip == $content) {
						$violation = true;
						$ban_set->expiry = \Carbon\Carbon::now()->addMonths(1)->format('Y-m-d H:i:s');
                        $ban_set->updated_at = now();
						$ban_set->save();						
					}
                    break;
                case 'userAgent':
                    if(LogUserLogin::where('user_id',$uid)->where('userAgent', 'like','%'.$content.'%')->first() != null) $violation = true;
                    break;

                //20220629新增圖片檔名
                case 'picname':
                    Log::info('start_pic_auto_ban');
                    if(UserMeta::where('user_id',$uid)->where('pic_original_name','like','%'.$content.'%')->first() != null) $violation = true;

                    //有一筆違規就可以封鎖了
                    if(MemberPic::where('member_id',$uid)->where('original_name','like','%'.$content.'%')->first() != null) $violation = true;
                    break;
                //20220629新增圖片檔名   

                case 'pic':
                    $ban_encode_entry = ImagesCompareService::getCompareEncodeByPic($content);

                    if(($ban_encode_entry??null) && $ban_encode_entry->file_md5??'') {
                        if(($user->meta->pic??null) && $ban_encode_entry->file_md5==(ImagesCompareService::getCompareEncodeByPic($user->meta->pic)->file_md5??null)) {
                            $violation = true;
                        }
                        
                        if(!$violation) {
                            $memPics = $user->pic_withTrashed()->pluck('pic')->all();
                            $memPicMd5s =  ImagesCompareService::getFileMd5ArrByPicArr($memPics); 
                            if(in_array($ban_encode_entry->file_md5,$memPicMd5s)) $violation = true;                         
                        }                                           
                        
                        if(!$violation) {
                            $delAvatars = $user->avatar_deleted()->pluck('pic')->all();
                            $delAvatarMd5s =  ImagesCompareService::getFileMd5ArrByPicArr($delAvatars); 
                            if(in_array($ban_encode_entry->file_md5,$delAvatarMd5s)) $violation = true;
                        }
                    }
                break;
                default:
                    break;
            }

            if ($violation) {
                if($ban_set->set_ban == 1 && banned_users::where('member_id', $uid)->first() == null) {
                    //直接封鎖
                    $userBanned = new banned_users;
                    if($user->engroup==2 ) {
                       if(!($user->advance_auth_status??null)) {
                           $userBanned->adv_auth=1;
                       }
                       else $userBanned=null;
                    } 
                    if($userBanned) {
                        $userBanned->member_id = $uid;
                        $userBanned->reason = "系統原因($ban_set->id)";
                        $userBanned->save();
                        //寫入log
                        DB::table('is_banned_log')->insert(['user_id' => $uid, 'reason' => "系統原因($ban_set->id)"]);
                    }
                }
                elseif($ban_set->set_ban == 2 && BannedUsersImplicitly::where('target', $uid)->first() == null){
                    //隱性封鎖
                    BannedUsersImplicitly::insert(['fp' => 'Line 79, BannedInUserInfo, ban_set ID: ' . $ban_set->id . ', content: ' . $content, 'user_id' => 0, 'target' => $uid]);
                }
                elseif($ban_set->set_ban == 3) {
                    //警示會員
                    $userWarned = new warned_users;
                    $userWarned->member_id = $uid;
                    $userWarned->reason = "系統原因($ban_set->id)";
                    if($ban_set->expired_days !=0)
                    {
                        $userWarned->expire_date = Carbon::now()->addDays($ban_set->expired_days);
                    }
                    $userWarned->save();
                    //寫入log
                    DB::table('is_warned_log')->insert(['user_id' => $uid, 'reason' => "系統原因($ban_set->id)"]);
                    // UserMeta::where('user_id', $uid)->update(['isWarned' => 1]);
                }
            }
        }
        $msg_auto_ban = $set_auto_ban->where('type', 'msg')->orwhere('type', 'allcheck')->orderBy('id', 'desc')->get();
        $content_days = Carbon::now()->subDays(1);
        $msg = Message::select('updated_at', 'from_id', 'content')->where('from_id', $uid)->where('updated_at', '>', $content_days)->get();
        foreach ($msg_auto_ban as $ban_set)
        {
            foreach ($msg as $m)
            {
                $userBanned = null;
                $violation = false;
                if (strpos($m->content, $ban_set->content) !== false) {
                    $violation = true;
                }
                if ($violation) {
                    if($ban_set->set_ban == 1 && banned_users::where('member_id', $uid)->first() == null) {
                        //直接封鎖
                        $userBanned = new banned_users;
                        if($user->engroup==2 ) {
                           if(!($user->advance_auth_status??null)) {
                               $userBanned->adv_auth=1;
                           }
                           else $userBanned=null;
                        }                         
                        if($userBanned) {
                            $userBanned->member_id = $uid;
                            $userBanned->reason = "系統原因($ban_set->id)";
                            $userBanned->save();
                            //寫入log
                            DB::table('is_banned_log')->insert(['user_id' => $uid, 'reason' => "系統原因($ban_set->id)"]);
                        }
                    }
                    elseif($ban_set->set_ban == 2 && BannedUsersImplicitly::where('target', $uid)->first() == null) {
                        //隱性封鎖
                        BannedUsersImplicitly::insert(['fp' => 'Line 124, BannedInUserInfo, ban_set ID: ' . $ban_set->id . ', content: ' . $ban_set->content, 'user_id' => 0, 'target' => $uid]);
                    }
                    elseif($ban_set->set_ban == 3 && warned_users::where('member_id', $uid)->first() == null) {
                        //警示會員
                        $userWarned = new warned_users;
                        $userWarned->member_id = $uid;
                        $userWarned->reason = "系統原因($ban_set->id)";

                        if($ban_set->expired_days !=0)
                        {
                            $userWarned->expire_date = Carbon::now()->addDays($ban_set->expired_days);
                        }

                        $userWarned->save();
                        //寫入log
                        DB::table('is_warned_log')->insert(['user_id' => $uid, 'reason' => "系統原因($ban_set->id)"]);
                        // UserMeta::where('user_id', $uid)->update(['isWarned' => 1]);
                    }
                }
            }
        }
    }

    public static function setAutoBanAdd($type, $content, $set_ban, $cuz_user_set = null, $expiry = '0000-00-00 00:00:00', $host = null)
    {
        if($type == 'ip'){
            $expiry = \Carbon\Carbon::now()->addMonths(1)->format('Y-m-d H:i:s');
        }
        SetAutoBan::insert(['type' => $type, 'content' => $content, 'set_ban' => $set_ban, 'cuz_user_set' => $cuz_user_set, 'expiry' => $expiry, 'host' => $host, 'created_at' => now(), 'updated_at' => now() ]);
        return;
    }
    
    private static function _getDelayTime() {
        $delay = 0;
        $now=Carbon::now();
        $next = $now->addDay();
        $stime = Carbon::parse($now->format('Y-m-d').' 18:00:00');
        $etime = Carbon::parse($next->format('Y-m-d').' 01:00:00');
        if($now->gt($stime) && $now->lt($etime)) $delay=25200; 
        return $delay;
    }    
}
