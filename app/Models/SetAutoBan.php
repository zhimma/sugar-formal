<?php

namespace App\Models;

use App\Jobs\AutoBanCaller;
use App\Jobs\BanJob;
use App\Jobs\LogoutAutoBan;
use App\Models\SimpleTables\warned_users;
use App\Services\ImagesCompareService;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SetAutoBan extends Model
{
    use SoftDeletes;

    //
    public $timestamps = false;
    protected $table = 'set_auto_ban';

    public function __construct(array $attributes = [])
    {
        $this->bootIfNotBooted();
        $this->initializeTraits();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->connection = app()->environment('production-misc') ? 'mysql_read' : 'mysql';
    }

    //自動封鎖 用後台設定的關鍵字查詢
    public static function auto_ban($uid)
    {
        Log::info('start_SetAutoBan_auto_ban');
        AutoBanCaller::dispatch($uid)->onConnection('database-long')->onQueue('long-jobs')->delay(SetAutoBan::_getDelayTime());
    }

    private static function _getDelayTime()
    {
        $delay = 0;
        $now = Carbon::now();
        $next = $now->addDay();
        $stime = Carbon::parse($now->format('Y-m-d') . ' 18:00:00');
        $etime = Carbon::parse($next->format('Y-m-d') . ' 01:00:00');
        if ($now->gt($stime) && $now->lt($etime)) $delay = 25200;
        return $delay;
    }

    //發訊後的自動封鎖

    public static function autoBan($uid)
    {
        $user = User::findById($uid);
        try {
            if (isset($user) && $user->can('admin')) {
                return;
            }
        } catch (\Exception $e){

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
                            $memPicMd5s = ImagesCompareService::getFileMd5ArrByPicArr($memPics);
                            if(in_array($memPics,$memPicMd5s)) $violation = true;
                        }

                        if(!$violation) {
                            $delAvatars = $user->avatar_deleted()->pluck('pic')->all();
                            $delAvatarMd5s = ImagesCompareService::getFileMd5ArrByPicArr($delAvatars);
                            if(in_array($delAvatars,$delAvatarMd5s)) $violation = true;
                        }
                    }
                    break;
                default:
                    break;
            }

            if($violation){
                $type = 'profile';
                BanJob::dispatch($uid, $ban_set, $user, $type)->onConnection('ban-job')->onQueue('ban-job');
                return;
            }
        }
    }

    public static function msg_auto_ban($uid, $toid, $msg)
    {
        AutoBanCaller::dispatch($uid, $toid, $msg)->onConnection('database-long')->onQueue('long-jobs')->delay(SetAutoBan::_getDelayTime());
    }

    //登出後的警示

    public static function autoBanMsg($uid, $toid, $msg)
    {
        $user = User::findById($uid);
        try {
            if (isset($user) && $user->can('admin')) {
                return;
            }
        } catch (\Exception $e){

        }
        $auto_ban = SetAutoBan::select('type', 'set_ban', 'id', 'content', 'expired_days')->where('type', 'msg')->orwhere('type', 'allcheck')->orderBy('id', 'desc')->get();
        foreach ($auto_ban as $ban_set) {
            $violation = false;
            if (Message::where('from_id', $uid)->where('to_id', $toid)->where('content', $msg)->where('content', 'like', '%' . $ban_set->content . '%')->first() != null) {
                $violation = true;
            }
            if ($violation) {
                $type = 'message';
                BanJob::dispatch($uid, $ban_set, $user, $type)->onConnection('ban-job')->onQueue('ban-job');
                return;
            }
        }
    }

    //登入時的警示

    public static function logout_warned($uid)
    {
        Log::Info('start_LogoutAutoBan_logout_warned');
        Log::Info($uid);
        if (\App::isProduction()) {
            // LogoutAutoBan::dispatch($uid)->onConnection('sqs')->onQueue('auto-ban')->delay(SetAutoBan::_getDelayTime());
            LogoutAutoBan::dispatchSync($uid);
        } else {
            // LogoutAutoBan::dispatch($uid)->onConnection('sqs')->onQueue('auto-ban-test')->delay(SetAutoBan::_getDelayTime());
            LogoutAutoBan::dispatchSync($uid);
        }
    }

    public static function login_warned($uid)
    {
        $user = User::where('id', $uid)->first();
        $count_of_user_login_with_desktop = LogUserLogin::where('user_id', $uid)
                                                ->where(function($query){
                                                    //Windows
                                                    $query->where('userAgent', 'like', '%' . 'Windows' . '%');
                                                    //Mac
                                                    //$query->orwhere('userAgent', 'like', '%' . 'Macintosh' . '%');
                                                })
                                                ->count();
        if($user->engroup==2 && !$user->isPhoneAuth() && $count_of_user_login_with_desktop>=3 && $user->created_at>\Carbon\Carbon::now()->subDays(10))
        {
            SetAutoBan::mobile_verify_warned($uid);
        }
    }

    public static function mobile_verify_warned($uid)
    {
        $reason = '註冊未滿10天&電腦登入3次';
        $userWarned = new warned_users;
        $userWarned->member_id = $uid;
        $userWarned->type = 'no_mobile_verify';
        $userWarned->reason = $reason;
        $userWarned->save();
        //寫入log
        DB::table('is_warned_log')->insert(['user_id' => $uid, 'reason' => $reason]);
    }

    public static function relieve_mobile_verify_warned($uid)
    {
        if(User::findById($uid)->isPhoneAuth() == true)
        {
            warned_users::where('member_id', $uid)->where('type', 'no_mobile_verify')->delete();
        }
    }

    public static function logoutWarned($uid, $probing = false)
    {
        $new = true;
        if($new) {
            // Log::info('start_LogoutAutoBan_logoutWarned');
            $user = User::find($uid);
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

            $ban_set_type = collect(['name', 'email', 'title']);
            $ban_meta_set_type = collect(['about', 'style']);
            $all_check_rule_sets = SetAutoBan::retrive('allcheck');  

            $ban_set_type->each(function($type) use ($user, $all_check_rule_sets, $probing) {
                $type_rule_sets = SetAutoBan::retrive($type);
                $rule_sets = $type_rule_sets->merge($all_check_rule_sets);
                $rule_sets->each(function($rule_set) use ($user, $type, $probing) {
                    if(str_contains($user->$type, $rule_set->content)) {  
                        if($probing) {
                            echo $rule_set->id . ' ' . $rule_set->type;
                        }            
                        SetAutoBan::banJobDispatcher($user, $rule_set, 'profile');
                    }
                });
            });

            $ban_meta_set_type->each(function($type) use ($user, $all_check_rule_sets, $probing) {
                $type_rule_sets = SetAutoBan::retrive($type);
                $rule_sets = $type_rule_sets->merge($all_check_rule_sets);
                $rule_sets->each(function($rule_set) use ($user, $type, $probing) {
                    if(str_contains($user->user_meta->$type, $rule_set->content)) {  
                        if($probing) {
                            echo $rule_set->id . ' ' . $rule_set->type;
                        }                    
                        SetAutoBan::banJobDispatcher($user, $rule_set, 'profile');
                    }
                });
            });

            $user->log_user_login->each(function ($log) use ($user, $probing) {
                $cfp_id_rule_sets = SetAutoBan::retrive('cfp_id');
                $cfp_id_rule_sets->each(function($rule_set) use ($user, $log, $probing) {
                    if($log->cfp_id == $rule_set->content) {
                        if($probing) {
                            echo $rule_set->id . ' ' . $rule_set->type;
                        }  
                        SetAutoBan::banJobDispatcher($user, $rule_set, 'profile');
                    }
                });

                $user_agent_rule_sets = SetAutoBan::retrive('user_agent');
                $user_agent_rule_sets->each(function($rule_set) use ($user, $log, $probing) {
                    if(str_contains($log->userAgent, $rule_set->content)) {
                        if($probing) {
                            echo $rule_set->id . ' ' . $rule_set->type;
                        }  
                        SetAutoBan::banJobDispatcher($user, $rule_set, 'profile');
                    }
                });
            });

            //20220629新增圖片檔名
            $pic_rule_sets = SetAutoBan::retrive('pic');
            $pic_rule_sets->each(function($rule_set) use ($user, $probing) {
                if(str_contains($user->user_meta->pic_original_name, $rule_set->content)) {
                    if($probing) {
                        echo $rule_set->id . ' ' . $rule_set->type;
                    }  
                    SetAutoBan::banJobDispatcher($user, $rule_set, 'profile');
                }
            });

            //有一筆違規就可以封鎖了 
            $pic_name_rule_sets = SetAutoBan::retrive('picname');
            $any_pic_violated = $user->pics->first(function($pic) use ($user, $pic_name_rule_sets, $probing) {
                return $pic_name_rule_sets->each(function($rule_set) use ($user, $pic, $probing) {
                    if(str_contains($pic->original_name, $rule_set->content)) {
                        if($probing) {
                            echo 'any_pic_violated, ban set:' . $rule_set->id . ' ' . $rule_set->type;
                        }  
                        SetAutoBan::banJobDispatcher($user, $rule_set, 'profile');
                    }
                });
            });

            $ip_rule_sets = SetAutoBan::retrive('ip');
            $auto_ban_rule_sets = $ip_rule_sets->merge($pic_rule_sets);
            
            foreach ($auto_ban_rule_sets as $ban_set) {
                $content = $ban_set->content;
                $violation = false;
                $caused_by = $ban_set->type;
                switch ($ban_set->type) {
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
                        $ip = $user->log_user_login->sortByDesc('created_at')->first();
                        if($ip?->ip == $content) {
                            $violation = true;
                            $ban_set->expiry = \Carbon\Carbon::now()->addMonths(1)->format('Y-m-d H:i:s');
                            $ban_set->updated_at = now();
                            $ban_set->save();						
                        }
                        break;
                    //20220629新增圖片檔名   
                    case 'pic':
                        $ban_encode_entry = ImagesCompareService::getCompareEncodeByPic($content);

                        if(($ban_encode_entry??null) && $ban_encode_entry->file_md5??'') {
                            if(($user->meta->pic??null) && $ban_encode_entry->file_md5==(ImagesCompareService::getCompareEncodeByPic($user->meta->pic)->file_md5??null)) {
                                $violation = true;
                                $caused_by = 'pic';
                            }
                            
                            if(!$violation) {
                                $memPics = $user->pic_withTrashed()->pluck('pic')->all();
                                $memPicMd5s =  ImagesCompareService::getFileMd5ArrByPicArr($memPics); 
                                if(in_array($ban_encode_entry->file_md5,$memPicMd5s)) { 
                                    $violation = true;
                                    $caused_by = 'pic';
                                }
                            }                                           
                            
                            if(!$violation) {
                                $delAvatars = $user->avatar_deleted()->pluck('pic')->all();
                                $delAvatarMd5s =  ImagesCompareService::getFileMd5ArrByPicArr($delAvatars); 
                                if(in_array($ban_encode_entry->file_md5,$delAvatarMd5s)) {
                                    $violation = true;
                                    $caused_by = 'pic';
                                }
                            }
                        }
                    break;
                    default:
                        break;
                }

                if ($violation) {
                    $type = 'profile';                
                    if($probing) {
                        echo $caused_by;
                    }
                    else {
                        logger("User $uid is banned by $caused_by");
                    }
                    BanJob::dispatch($uid, $ban_set, $user, $type)->onConnection('ban-job')->onQueue('ban-job');
                }
            }

            // $content_days = Carbon::now()->subDays(1);
            // $msgs = Message::retrieve($uid, $content_days);
            // $msg_rule_sets = SetAutoBan::retrive('msg');
            // $rule_sets = $msg_rule_sets->merge($all_check_rule_sets);
            // $rule_sets->each(function($rule_set) use ($user, $msgs, $probing) {
            //     $msgs->each(function($msg) use ($user, $rule_set, $probing) {
            //         if(str_contains($msg->content, $rule_set->content)) {
            //             if($probing) {
            //                 echo $rule_set->type;
            //             }
            //             else {
            //                 logger("User $user->id is banned by $rule_set->type");
            //             }
            //             SetAutoBan::banJobDispatcher($user, $rule_set, 'msg');
            //         }
            //     });
            // });

            return 0;
        }
        else {
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

            $ban_set_type = collect(['name', 'email', 'title']);
            $ban_meta_set_type = collect(['about', 'style']);

            $ban_set_type->each(function($type) use ($user) {
                $matched_set = SetAutoBan::where('type', $type)->whereRaw("INSTR('{$user->$type}', content) > 0")->first();
                if($matched_set) {
                    SetAutoBan::banJobDispatcher($user, $matched_set, 'profile');
                }

                $all_check_matched_set = SetAutoBan::where('type', 'allcheck')->whereRaw("INSTR('{$user->$type}', content) > 0")->first();     
                if($all_check_matched_set) {
                    SetAutoBan::banJobDispatcher($user, $matched_set, 'profile');
                }
            });

            $ban_meta_set_type->each(function($type) use ($user) {
                $matched_set = SetAutoBan::where('type', $type)->whereRaw("INSTR('{$user->user_meta->$type}', content) > 0")->first();
                if($matched_set) {
                    SetAutoBan::banJobDispatcher($user, $matched_set, 'profile');
                }

                $all_check_matched_set = SetAutoBan::where('type', 'allcheck')->whereRaw("INSTR('{$user->user_meta->$type}', content) > 0")->first();            
                if($all_check_matched_set) {
                    SetAutoBan::banJobDispatcher($user, $matched_set, 'profile');
                }
            });

            $user->log_user_login->each(function ($log) use ($user) {
                $cfp_id_matched_set = SetAutoBan::where('type', 'cfp_id')->where("content", $log->cfp_id)->first();
                if($cfp_id_matched_set) {
                    SetAutoBan::banJobDispatcher($user, $cfp_id_matched_set, 'profile');
                }

                $user_agent_matched_set = SetAutoBan::where('type', 'userAgent')->whereRaw("INSTR('{$log->userAgent}', content) > 0")->first();
                if($user_agent_matched_set) {
                    SetAutoBan::banJobDispatcher($user, $user_agent_matched_set, 'profile');
                }
            });

            //20220629新增圖片檔名
            $pic_matched_set = SetAutoBan::where('type', 'picname')->whereRaw("INSTR('{$user->user_meta->pic_original_name}', content) > 0")->first();
            if($pic_matched_set) {
                SetAutoBan::banJobDispatcher($user, $pic_matched_set, 'profile');
            }

            //有一筆違規就可以封鎖了 
            $any_pic_violated = $user->pics->first(function($pic) {
                return SetAutoBan::where('type', 'picname')->whereRaw("INSTR('{$pic->original_name}', content) > 0")->first();
            });
            if($any_pic_violated) {
                SetAutoBan::banJobDispatcher($user, $any_pic_violated, 'profile');
            }

            $set_auto_ban = SetAutoBan::select('type', 'set_ban', 'id', 'content','expiry', 'expired_days')->whereNotIn('type', ['name', 'email', 'title', 'about', 'style', 'allcheck', 'msg', 'cfp_id', 'userAgent', 'picname'])->orderBy('id', 'desc')->get();
            
            foreach ($set_auto_ban as $ban_set) {
                $content = $ban_set->content;
                $violation = false;
                $caused_by = $ban_set->type;
                switch ($ban_set->type) {
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
                        $ip = $user->log_user_login->sortByDesc('created_at')->first();
                        if($ip?->ip == $content) {
                            $violation = true;
                            $ban_set->expiry = \Carbon\Carbon::now()->addMonths(1)->format('Y-m-d H:i:s');
                            $ban_set->updated_at = now();
                            $ban_set->save();						
                        }
                        break;
                    //20220629新增圖片檔名   
                    case 'pic':
                        $ban_encode_entry = ImagesCompareService::getCompareEncodeByPic($content);

                        if(($ban_encode_entry??null) && $ban_encode_entry->file_md5??'') {
                            if(($user->meta->pic??null) && $ban_encode_entry->file_md5==(ImagesCompareService::getCompareEncodeByPic($user->meta->pic)->file_md5??null)) {
                                $violation = true;
                                $caused_by = 'pic';
                            }
                            
                            if(!$violation) {
                                $memPics = $user->pic_withTrashed()->pluck('pic')->all();
                                $memPicMd5s =  ImagesCompareService::getFileMd5ArrByPicArr($memPics); 
                                if(in_array($ban_encode_entry->file_md5,$memPicMd5s)) { 
                                    $violation = true;
                                    $caused_by = 'pic';
                                }
                            }                                           
                            
                            if(!$violation) {
                                $delAvatars = $user->avatar_deleted()->pluck('pic')->all();
                                $delAvatarMd5s =  ImagesCompareService::getFileMd5ArrByPicArr($delAvatars); 
                                if(in_array($ban_encode_entry->file_md5,$delAvatarMd5s)) {
                                    $violation = true;
                                    $caused_by = 'pic';
                                }
                            }
                        }
                    break;
                    default:
                        break;
                }

                if ($violation) {
                    $type = 'profile';                
                    if($probing) {
                        echo $caused_by;
                    }
                    else {
                        logger("User $uid is banned by $caused_by");
                    }
                    BanJob::dispatch($uid, $ban_set, $user, $type)->onConnection('ban-job')->onQueue('ban-job');
                }
            }

            // $content_days = Carbon::now()->subDays(1);
            // $msg = Message::select('updated_at', 'from_id', 'content')->where('from_id', $uid)->where('updated_at', '>', $content_days)->get();
            // foreach ($msg as $m) {
            //     $msg_matched_set = SetAutoBan::whereIn('type', ['msg', 'allcheck'])->whereRaw("INSTR('{$m}', content) > 0")->first();
            //     if ($msg_matched_set) {
            //         $type = 'message';
            //         SetAutoBan::banJobDispatcher($user, $msg_matched_set, 'message');
            //     }
            // }

            return 0;
        }
    }

    public static function retrive($type)
    {
        /*
        Cache::forget('auto_ban_set_' . $type);
        return Cache::remember('auto_ban_set' . $type, 3600, function () use ($type) {
            return SetAutoBan::where('type', $type)->get();
        });
        */
        return SetAutoBan::where('type', $type)->get();
    }

    public static function banJobDispatcher($user, $matched_set, $data_type)
    {
        if ($matched_set && $matched_set->id) {
            logger("User $user->id violated auto-ban set: $matched_set->id");
            BanJob::dispatch($user->id, $matched_set, $user, $data_type)->onConnection('ban-job')->onQueue('ban-job');
        } else {
            logger("Ban job dispatcher called but no matched set, user: $user->id.");
        }

        return 0;
    }

    public static function setAutoBanAdd($type, $content, $set_ban, $cuz_user_set = null, $expiry = '0000-00-00 00:00:00', $host = null, $remark = null)
    {
        if ($type == 'ip') {
            $expiry = \Carbon\Carbon::now()->addMonths(1)->format('Y-m-d H:i:s');
        }
        SetAutoBan::insert(['type' => $type, 'content' => $content, 'set_ban' => $set_ban, 'cuz_user_set' => $cuz_user_set, 'expiry' => $expiry, 'host' => $host, 'remark' => $remark, 'created_at' => now(), 'updated_at' => now()]);
        return;
    }

    public static function getRemoteIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public static function local_machine_ban_and_warn($uid, $probing = false)
    {
        //因應效能需求暫時略過未來再關閉
        $bypass = true;

        $ban_list = [];
        $user = User::find($uid);
            try {
                if(isset($user) && $user->can('admin')){
                    return;
                }
            }
            catch (\Exception $e){

            }
            if(!$user || !$uid) {
                logger('SetAutoBan local_machine_ban_and_warn() user not set, referer: ' . \Request::server('HTTP_REFERER'));
                return;
            }

            //執行時間預設是30秒改為無上限
            set_time_limit(-1);
            //執行記憶體改為無上限
            ini_set('memory_limit',-1);

            if(!$bypass){
                $ban_set_type = collect(['name', 'email', 'title']);
                $ban_meta_set_type = collect(['about', 'style']);
                $all_check_rule_sets = SetAutoBan::retrive('allcheck');

                $ban_set_type->each(function($type) use ($user, $all_check_rule_sets, $probing, &$ban_list) {
                    $type_rule_sets = SetAutoBan::retrive($type);
                    $rule_sets = $type_rule_sets->merge($all_check_rule_sets);
                    $rule_sets->each(function($rule_set) use ($user, $type, $probing, &$ban_list) {
                        if (str_contains($user->$type, $rule_set->content)) {
                            if ($probing) {
                                echo $rule_set->id . ' ' . $rule_set->type;
                            }
                            if ($rule_set && $rule_set->id) {
                                $ban_list[] = [$user->id, $rule_set->id, 'profile'];
                            }
                        }
                    });
                });

                $ban_meta_set_type->each(function($type) use ($user, $all_check_rule_sets, $probing, &$ban_list) {
                    $type_rule_sets = SetAutoBan::retrive($type);
                    $rule_sets = $type_rule_sets->merge($all_check_rule_sets);
                    $rule_sets->each(function($rule_set) use ($user, $type, $probing, &$ban_list) {
                        if (str_contains($user->user_meta->$type, $rule_set->content)) {
                            if ($probing) {
                                echo $rule_set->id . ' ' . $rule_set->type;
                            }
                            if ($rule_set && $rule_set->id) {
                                $ban_list[] = [$user->id, $rule_set->id, 'profile'];
                            }
                        }
                    });
                });
            }

            //只取一天內的登入紀錄
            Log::Info('開始檢查User: ' . $user->id . ' CFP_ID 共 ' . $user->log_user_login->where('created_at', '>', Carbon::now()->subDay())->sortByDesc('created_at')->count() . ' 筆登入紀錄');
            $user->log_user_login->where('created_at', '>', Carbon::now()->subDay())->each(function ($log) use ($user, $probing, &$ban_list, $bypass) {
                $cfp_id_rule_sets = SetAutoBan::retrive('cfp_id');
                $cfp_id_rule_sets->each(function($rule_set) use ($user, $log, $probing, &$ban_list) {
                    if($log->cfp_id == $rule_set->content) {
                        if($probing) {
                            echo $rule_set->id . ' ' . $rule_set->type;
                        }
                        if($rule_set && $rule_set->id) {
                            $ban_list[] = [$user->id, $rule_set->id, 'profile'];
                        }
                    }
                });

                if(!$bypass){
                    $user_agent_rule_sets = SetAutoBan::retrive('user_agent');
                    $user_agent_rule_sets->each(function($rule_set) use ($user, $log, $probing, &$ban_list) {
                        if(str_contains($log->userAgent, $rule_set->content)) {
                            if($probing) {
                                echo $rule_set->id . ' ' . $rule_set->type;
                            }
                            if($rule_set && $rule_set->id) {
                                $ban_list[] = [$user->id, $rule_set->id, 'profile'];
                            }
                        }
                    });
                }
            });

            if(!$bypass){
                //20220629新增圖片檔名
                $pic_rule_sets = SetAutoBan::retrive('pic');
                $pic_rule_sets->each(function($rule_set) use ($user, $probing, &$ban_list) {
                    if(str_contains($user->user_meta->pic_original_name, $rule_set->content)) {
                        if($probing) {
                            echo $rule_set->id . ' ' . $rule_set->type;
                        }
                        if($rule_set && $rule_set->id) {
                                $ban_list[] = [$user->id, $rule_set->id, 'profile'];
                            }
                    }
                });

                //有一筆違規就可以封鎖了
                $pic_name_rule_sets = SetAutoBan::retrive('picname');
                $any_pic_violated = $user->pics->first(function($pic) use ($user, $pic_name_rule_sets, $probing, &$ban_list) {
                    return $pic_name_rule_sets->each(function($rule_set) use ($user, $pic, $probing, &$ban_list) {
                        if(str_contains($pic->original_name, $rule_set->content)) {
                            if($probing) {
                                echo 'any_pic_violated, ban set:' . $rule_set->id . ' ' . $rule_set->type;
                            }
                            if($rule_set && $rule_set->id) {
                                $ban_list[] = [$user->id, $rule_set->id, 'profile'];
                            }
                        }
                    });
                });
            }

            /*
            $ip_rule_sets = SetAutoBan::retrive('ip');
            if(!$bypass){
                $auto_ban_rule_sets = $ip_rule_sets->merge($pic_rule_sets);
            }
            else{
                $auto_ban_rule_sets = $ip_rule_sets;
            }


            foreach ($auto_ban_rule_sets as $ban_set) {
                $content = $ban_set->content;
                $violation = false;
                $caused_by = $ban_set->type;
                switch ($ban_set->type) {
                    case 'ip':
                        if($ban_set->expiry=='0000-00-00 00:00:00') {
                            SetAutoBan::ip_update_send('update', $ban_set->id);
                        }
                        if($ban_set->expiry<=\Carbon\Carbon::now()->format('Y-m-d H:i:s')) {
                            SetAutoBan::ip_update_send('delete', $ban_set->id);
                            break;
                        }
                        $ip = $user->log_user_login->where('created_at', '>', Carbon::now()->subDay())->sortByDesc('created_at')->first();
                        if($ip?->ip == $content) {
                            $violation = true;
                            SetAutoBan::ip_update_send('update', $ban_set->id);
                        }
                        break;
                    //20220629新增圖片檔名
                    case 'pic':
                        $ban_encode_entry = ImagesCompareService::getCompareEncodeByPic($content);

                        if(($ban_encode_entry??null) && $ban_encode_entry->file_md5??'') {
                            if(($user->meta->pic??null) && $ban_encode_entry->file_md5==(ImagesCompareService::getCompareEncodeByPic($user->meta->pic)->file_md5??null)) {
                                $violation = true;
                                $caused_by = 'pic';
                            }

                            if (!$violation) {
                                $memPics = $user->pic_withTrashed()->pluck('pic')->all();
                                $memPicMd5s = ImagesCompareService::getFileMd5ArrByPicArr($memPics);
                                if (in_array($ban_encode_entry->file_md5, $memPicMd5s)) {
                                    $violation = true;
                                    $caused_by = 'pic';
                                }
                            }

                            if (!$violation) {
                                $delAvatars = $user->avatar_deleted()->pluck('pic')->all();
                                $delAvatarMd5s = ImagesCompareService::getFileMd5ArrByPicArr($delAvatars);
                                if (in_array($ban_encode_entry->file_md5, $delAvatarMd5s)) {
                                    $violation = true;
                                    $caused_by = 'pic';
                                }
                            }
                        }
                    break;
                    default:
                        break;
                }

                if ($violation) {
                    $type = 'profile';
                    if($probing) {
                        echo $caused_by;
                    }
                    else {
                        logger("User $uid is banned by $caused_by");
                    }
                    $ban_list[] = [$uid, $ban_set->id, $type];

                }
            }
            */

            return $ban_list;
    }

    public static function ip_update_send($type, $id){
        $ip_list = [];
        if($type == 'update')
        {
            $ip_list['type'] = $type;
            $ip_list['id'] = $id;
            $ip_list['expiry'] = \Carbon\Carbon::now()->addMonths(1)->format('Y-m-d H:i:s');
            $ip_list['updated_at'] = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        }
        else if($type == 'delete')
        {
            $ip_list['type'] = $type;
            $ip_list['id'] = $id;
            $ip_list['expiry'] = '';
            $ip_list['updated_at'] = '';
        }


        $link_address = config('localmachine.MISC_LINK_SERVER').'/LocalMachineReceive/BanSetIPUpdate';
        $post_data = [
            'form_params' => [
                'key' => config('localmachine.MISC_KEY'),
                'ip_list' => $ip_list
            ]
        ];
        $client   = new Client();
        $response = $client->request('POST', $link_address, $post_data);
        $contents = $response->getBody()->getContents();
        Log::Info($contents);
    }

    public static function local_machine_ban_and_warn_second($uid, $probing = false)
    {
        $ban_list = [];
        $user = User::find($uid);
        try {
            if(isset($user) && $user->can('admin')){
                return;
            }
        }
        catch (\Exception $e){

        }
        if(!$user || !$uid) {
            logger('SetAutoBan local_machine_ban_and_warn() user not set, referer: ' . \Request::server('HTTP_REFERER'));
            return;
        }

        //執行時間預設是30秒改為無上限
        set_time_limit(-1);
        //執行記憶體改為無上限
        ini_set('memory_limit',-1);

        $ban_set_type = collect(['name', 'email', 'title']);

        $all_check_rule_sets = SetAutoBan::retrive('allcheck');

        $ban_set_type->each(function($type) use ($user, $all_check_rule_sets, $probing, &$ban_list) {
            $type_rule_sets = SetAutoBan::retrive($type);
            $rule_sets = $type_rule_sets->merge($all_check_rule_sets);
            $rule_sets->each(function($rule_set) use ($user, $type, $probing, &$ban_list) {
                if (str_contains($user->$type, $rule_set->content)) {
                    if ($probing) {
                        echo $rule_set->id . ' ' . $rule_set->type;
                    }
                    if ($rule_set && $rule_set->id) {
                        $ban_list[] = [$user->id, $rule_set->id, 'profile'];
                    }
                }
            });
        });

        $ip_rule_sets = SetAutoBan::retrive('ip');
        $auto_ban_rule_sets = $ip_rule_sets;

        foreach ($auto_ban_rule_sets as $ban_set) {
            $content = $ban_set->content;
            $violation = false;
            $caused_by = $ban_set->type;
            switch ($ban_set->type) {
                case 'ip':
                    if($ban_set->expiry=='0000-00-00 00:00:00') {
                        SetAutoBan::ip_update_send('update', $ban_set->id);
                    }
                    if($ban_set->expiry<=\Carbon\Carbon::now()->format('Y-m-d H:i:s')) {
                        SetAutoBan::ip_update_send('delete', $ban_set->id);
                        break;
                    }
                    $ip = $user->log_user_login->where('created_at', '>', Carbon::now()->subDay())->sortByDesc('created_at')->first();
                    if($ip?->ip == $content) {
                        $violation = true;
                        SetAutoBan::ip_update_send('update', $ban_set->id);
                    }
                    break;

                default:
                    break;
            }

            if ($violation) {
                $type = 'profile';
                if($probing) {
                    echo $caused_by;
                }
                else {
                    logger("User $uid is banned by $caused_by");
                }
                $ban_list[] = [$uid, $ban_set->id, $type];

            }
        }

        return $ban_list;
    }

    public static function local_machine_ban_and_warn_check($user, $ip, $probing = false)
    {
        if (!$user) {
            logger("user is not found in db");
            return [];
        }

        [$status, $ban_type] = SetAutoBan::retriveGroupAndCheck(['ip', 'name', 'email', 'title', 'about', 'style'], $user, $ip);

        return [$status, $ban_type];
    }

    public static function retriveGroupAndCheck($typeArr, $user, $get_remote_ip)
    {
        $set_auto_ban_list_by_type = DB::table('set_auto_ban')->leftJoin('users', 'users.id', '=', 'set_auto_ban.cuz_user_set')->whereIn('set_auto_ban.type', $typeArr)->whereNull('set_auto_ban.deleted_at')->get()->toArray();

        $checkStatus = false;
        $ban_type = [];


        // $get_remote_ip = SetAutoBan::getRemoteIp();
        foreach ($set_auto_ban_list_by_type as $row) {

            if ($row->type == 'ip') {
                if ($get_remote_ip == $row->content) {
                    if (Carbon::now() < $row->expiry) {
                        $checkStatus = true;
                        array_push($ban_type, 'ip');
                    }
                }
            }

            if ($row->type == 'style') {
                if (strpos($user->style, $row->content) !== false) {
                    $checkStatus = true;
                    array_push($ban_type, '期待的約會模式');
                }
            }

            if ($row->type == 'about') {
                if (strpos($user->about, $row->content) !== false) {
                    $checkStatus = true;
                    array_push($ban_type, '關於我');
                }
            }

            if ($row->type == 'title') {
                if (str_contains($user->title, $row->content)) {
                    $checkStatus = true;
                    array_push($ban_type, '一句話形容自己');
                }
            }

            if ($row->type == 'name') {
                if (str_contains($user->name, $row->content)) {
                    $checkStatus = true;
                    array_push($ban_type, '暱稱');
                }
            }

            if ($row->type == 'email') {
                if (str_contains($user->email, '@' . $row->content)) {
                    $checkStatus = true;
                    array_push($ban_type, 'email');
                }
            }
        }

        return [$checkStatus, $ban_type];
    }

    /**
     * Get the name of the index associated with the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'autoban_set';
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        // Customize the data array...

        return $array;
    }
}
