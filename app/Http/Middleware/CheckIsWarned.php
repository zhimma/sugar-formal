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
use App\Models\SetAutoBan;

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
        //正被封鎖
        $isBanned = banned_users::where('member_id',$user->id)
                        ->where('expire_date', null)
                        ->orderBy('id', 'desc')
                        ->union(
                            banned_users::where('member_id',$user->id)
                                ->where('expire_date','>',Carbon::now())
                                ->orderBy('id', 'desc')
                            )->get();
        //正被警示
        $isWarned = warned_users::where('member_id', $user->id)
                        ->where('expire_date', null)
                        ->orderBy('id', 'desc')
                        ->union(
                            warned_users::where('member_id', $user->id)
                                ->Where('expire_date','>',Carbon::now())
                                ->orderBy('id', 'desc')
                        )->get();

        //封鎖 警示 並存時 只保留封鎖 刪除警示
        if(count($isBanned)>0 && count($isWarned)>0){
            warned_users::where('member_id',$user->id)->delete();
        }

        //移除重複資料
        if(count($isBanned)>1){
            $isBanned_now = banned_users::where('member_id',$user->id)
                                ->where(
                                    function($q) {
                                        $q->where('expire_date', null)->
                                            orWhere('expire_date','>',Carbon::now());
                                        })
                                ->orderBy('id', 'desc')->first();
            //delete other
            banned_users::where('member_id',$user->id)->where('id', '<>', $isBanned_now->id)->delete();
        }

        if(count($isWarned)>1){
            $isWarned_now = warned_users::where('member_id',$user->id)
                                ->where(
                                    function($q) {
                                        $q->where('expire_date', null)->
                                            orWhere('expire_date','>',Carbon::now());
                                        })
                                ->orderBy('id', 'desc')->first();
            //delete other
            warned_users::where('member_id',$user->id)->where('id', '<>', $isWarned_now->id)->delete();
        }

        //vip_pass and vip
        //check is ever vip_pass and now not vip
        if(!$user->isVip() && !$user->isFreeVip() && $user->engroup == 1){

            if(count($isBanned)==0){
                //if ever banned by vip_pass then reBanned
                $logBanned = IsBannedLog::where('user_id', $user->id)->where('vip_pass', 1)->orderBy('created_at', 'desc')->first();
                if($logBanned){
                    banned_users::insert([
                        'member_id' => $user->id,
                        'vip_pass' => 1,
                        'reason' => str_replace('(未續費)','', $logBanned->reason).'(未續費)',
                        'message_content' => $logBanned->message_content,
                        'recipient_name' => $logBanned->recipient_name,
                        'message_time' => $logBanned->message_time,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    IsBannedLog::insert([
                        'user_id' => $user->id,
                        'reason' => str_replace('(未續費)','', $logBanned->reason).'(未續費)',
                        'message_content' => $logBanned->message_content,
                        'recipient_name' => $logBanned->recipient_name,
                        'message_time' => $logBanned->message_time,
                        'vip_pass' => 1,
                        'created_at' => now()
                    ]);
                }

            }
            if(count($isWarned)==0){
                
                //刪除自動警示設定名單
                SetAutoBan::where('cuz_user_set',$user->id)->where('set_ban','3')->delete();

                //if ever banned by vip_pass then reBanned
                $logWarned = IsWarnedLog::where('user_id', $user->id)->where('vip_pass', 1)->orderBy('created_at', 'desc')->first();

                if($logWarned){
                    warned_users::insert([
                        'member_id' => $user->id,
                        'vip_pass' => 1,
                        'reason' => str_replace('(未續費)','', $logWarned->reason).'(未續費)',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    IsWarnedLog::insert([
                        'user_id' => $user->id,
                        'reason' => str_replace('(未續費)','', $logWarned->reason).'(未續費)',
                        'vip_pass' => 1,
                        'created_at' => now()
                    ]);
                }
            }
        }else if($user->isVip() && !$user->isFreeVip() && $user->engroup == 1){
            //防呆 當有VIP 則取消VIP_PASS
            banned_users::where('member_id',$user->id)->where('vip_pass', 1)->delete();
            warned_users::where('member_id',$user->id)->where('vip_pass', 1)->delete();
        }

        if($user->meta->isWarned == 1){
            if($auth_status==1 && !$user->isAdminWarned()){
                //取消警示
                UserMeta::where('user_id',$user->id)->where(function ($q) {
                    $q->whereNull('isWarnedType');
                    $q->orwhere('isWarnedType','<>','adv_auth');
                })->update(['isWarned'=>0, 'isWarnedRead'=>0, 'isWarnedTime' => null]);
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
