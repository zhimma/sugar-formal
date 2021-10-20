<?php

namespace App\Http\Middleware;

use App\Models\CFP_User;
use App\Models\IsBannedLog;
use App\Models\IsWarnedLog;
use App\Models\SimpleTables\banned_users;
use App\Models\SimpleTables\warned_users;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\Models\UserMeta;
use Carbon\Carbon;

class CheckIsWarned
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = view()->shared('user');

//        dd($user);
        $auth_status = 0;
        if($user->isPhoneAuth()==1){
            $auth_status = 1;
        }

        //vip_pass and vip
        //check is ever vip_pass and now not vip
        if(!$user->isVip() && !$user->isFreeVip() && $user->engroup == 1){

            //正被封鎖
            $isBanned = banned_users::where('member_id',$user->id)->where('expire_date', null)->orWhere('expire_date','>',Carbon::now() )->where('member_id', $user->id)->first();
            //正被警示
            $isWarned = warned_users::where('member_id', $user->id)->where('expire_date', null)->orWhere('expire_date','>',Carbon::now() )->where('member_id', $user->id)->first();

            if(!$isBanned){
                //if ever banned by vip_pass then reBanned
                $logBanned = IsBannedLog::where('user_id', $user->id)->where('vip_pass', 1)->orderBy('created_at', 'desc')->first();
                if($logBanned){
                    banned_users::insert([
                        'member_id' => $user->id,
                        'vip_pass' => 1,
                        'reason' => str_replace('(未續費)','', $logBanned->reason),
                        'message_content' => $logBanned->message_content,
                        'recipient_name' => $logBanned->recipient_name,
                        'message_time' => $logBanned->message_time,
                        'created_at' => now()
                    ]);

                    IsBannedLog::insert([
                        'user_id' => $user->id,
                        'reason' => str_replace('(未續費)','', $logBanned->reason),
                        'message_content' => $logBanned->message_content,
                        'recipient_name' => $logBanned->recipient_name,
                        'message_time' => $logBanned->message_time,
                        'vip_pass' => 1,
                        'created_at' => now()
                    ]);
                }

            }
            if(!$isWarned){
                //if ever banned by vip_pass then reBanned
                $logWarned = IsWarnedLog::where('user_id', $user->id)->where('vip_pass', 1)->orderBy('created_at', 'desc')->first();

                if($logWarned){
                    warned_users::insert([
                        'member_id' => $user->id,
                        'vip_pass' => 1,
                        'reason' => str_replace('(未續費)','', $logWarned->reason),
                        'created_at' => now()
                    ]);

                    IsWarnedLog::insert([
                        'user_id' => $user->id,
                        'reason' => str_replace('(未續費)','', $logWarned->reason),
                        'vip_pass' => 1,
                        'created_at' => now()
                    ]);
                }
            }
        }

        if($user->meta->isWarned == 1){
            if($auth_status==1 && !$user->isAdminWarned()){
                //取消警示
                UserMeta::where('user_id',$user->id)->update(['isWarned'=>0, 'isWarnedRead'=>0, 'isWarnedTime' => null]);
            }
//            dd($user->meta_()->isWarned);
            return $next($request);
        }

        if($user->meta->isWarned == 0 && $user->WarnedScore() >= 10 && $auth_status == 0 && $user->id != 1049){
            //加入警示
            UserMeta::where('user_id',$user->id)->update(['isWarned'=>1, 'isWarnedRead'=>0, 'isWarnedTime' => Carbon::now()]);

//            return $next($request);
        }


        return $next($request);
    }
}
