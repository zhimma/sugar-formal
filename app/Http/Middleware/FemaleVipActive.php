<?php

namespace App\Http\Middleware;

use App\Models\VipLog;
use Gate;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Vip;
use App\Models\LogFreeVipPicAct;
use Illuminate\Support\Facades\Config;

class FemaleVipActive
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
        $user = auth()->user();        
        //因為只針對女會員判斷權限，所以先過濾掉男會員
        //若女會員照片條件不符合，則過濾掉，不判斷剩下的規則
        //********* 未做的部分: 三天內上線兩次，但間隔不得小於24小時 *********/
        if($user->engroup == 1){
            return $next($request);
        } 
        $user->load('meta','pic');
        $user_last_login = Carbon::parse($user->last_login);
        $vip_record = Carbon::parse($user->vip_record);
        $isVIP = $user->isVip();
        $existHeaderImage = $user->existHeaderImage();   
        $freeVipCount =  VipLog::where('member_id', $user->id)->where('free',1)->where('action',1)->count();
        
        $log_pic_acts_count = $user->log_free_vip_pic_acts->count();  
        $latest_pic_act_log = $user->log_free_vip_pic_acts()->orderBy('created_at','DESC')->first()??null;
        
        if($latest_pic_act_log) $real_latest_pic_act_log = clone $latest_pic_act_log;
        
        if($latest_pic_act_log && in_array($latest_pic_act_log->sys_react,LogFreeVipPicAct::$replaceByFirstRemindSysReacts) ) {
            $lastPicRecoverLog = $user->log_free_vip_pic_acts()->where([['id','<>',$latest_pic_act_log->id],['created_at','<',$latest_pic_act_log->created_at]])->whereIn('sys_react',LogFreeVipPicAct::$reachRuleSysReacts)->orderBy('created_at', 'DESC')->first();
            $firstRemindingLogQuery = $user->log_free_vip_pic_acts()->where([['created_at','<=',$latest_pic_act_log->created_at]])->where('sys_react','reminding')->orderBy('created_at');
            if($lastPicRecoverLog) $firstRemindingLogQuery->where('created_at','>',($lastPicRecoverLog->created_at??'0000-00-00 00:00:00'));
            $firstRemindingLog =  $firstRemindingLogQuery->first();
            if($firstRemindingLog) $latest_pic_act_log = $firstRemindingLog;
        }

        $last_pic_sys_react = $latest_pic_act_log->sys_react??'';
        $last_pic_act_time =  isset($latest_pic_act_log->created_at)?Carbon::parse($latest_pic_act_log->created_at):'0000-00-00 00:00:00';         
        //剩下的是符合資格的女會員，如果她已經是免費VIP，則檢查現在是否依舊符合資格(照片、固定上線)
        if(view()->shared('isFreeVip')){
            if($existHeaderImage && in_array($real_latest_pic_act_log->sys_react??null,LogFreeVipPicAct::$notReachRuleSysReacts)) {
                LogFreeVipPicAct::create(['user_id'=> $user->id
                     ,'sys_react'=>'auto_remain'
                     ,'shot_vip_record'=>$user->vip_record
                      ,'shot_is_free_vip'=>$user->isFreeVip()
                         ]);                
            }
            
            //如果取得免費VIP權限的時間點與現在時間的差距，小於系統設定的時間長度(代表他固定上線)，同時也符合照片條件，則將現在時間記錄至vip_record(延長VIP時間)
            if( ($vip_record->diffInSeconds(Carbon::now()) <= Config::get('social.vip.free-days'))  && $existHeaderImage 
                    && ($vip_record->diffInSeconds(Carbon::now()) >= 86400 
                            || ($last_pic_sys_react=='recovering'  && $last_pic_act_time->diffInSeconds(Carbon::now()) >= 86400)
                        )                           
            ) {               
                $user->vip_record = Carbon::now();
                $user->save();
            }
            //若無法通過上述條件(沒有固定上線或不符合照片資格)，則取消VIP並記錄下取消的時間點
            //else if($vip_record->diffInSeconds(Carbon::now()) >= 1800 && !$existHeaderImage){
            else if(($last_pic_sys_react=='reminding'
                        && $last_pic_act_time->diffInSeconds(Carbon::now()) >= 1800
                        || $last_pic_sys_react=='not_vip_not_ok'  //不可能發生但為以防萬一仍加入判斷
                        || !$log_pic_acts_count
                        || $last_pic_sys_react!='reminding'
                     )                   
                    && !$existHeaderImage){    
                Vip::where('member_id', $user->id)->get()->first()->removeVIP();
                VipLog::addToLog($user->id, 'User free VIP auto cancelled.', 'XXXXXXXXX', 0, 1);
                $user->vip_record = Carbon::now();
                $user->save();
            }
            //執行完畢
            return $next($request);
        }
        //如果被取消免費 VIP 後，在 30 分鐘內補回照片資格，則還是給予免費 VIP
        //如果一般會員(!$user->isVip())失去免費VIP的時間(vip_record)與現在時間的差距小於系統設定的時間長度，則不做任何動作
        //這句是為了避免會員失去免費VIP後，馬上就又可以拿到免費VIP而設的條件
        //if($vip_record->diffInMinutes(Carbon::now()) > 30 && $vip_record->diffInSeconds(Carbon::now()) <= Config::get('social.vip.start') && !$isVIP) {
        if(($log_pic_acts_count>0 && $last_pic_sys_react=='reminding' && $last_pic_act_time->diffInMinutes(Carbon::now()) > 30 ) && $vip_record->diffInSeconds(Carbon::now()) <= Config::get('social.vip.start') && !$isVIP) {
        }
        //提供免費VIP的主要程式段，若會員非VIP，則提供免費VIP，使用vip_record記錄提供的時間點
        else if(!$isVIP && $existHeaderImage) {
            //if( (isset($vip_record) && $vip_record->diffInSeconds(Carbon::now()) >= 86400) || ($vip_record=='0000-00-00 00:00:00')){
            if( ($log_pic_acts_count>0 && $last_pic_sys_react=='recovering'  && $last_pic_act_time->diffInSeconds(Carbon::now()) >= 86400) 
                    || !$log_pic_acts_count
                    || $last_pic_sys_react!='recovering'
                    || !$freeVipCount
            ){
                $before_upgrade_vip_record = $user->vip_record;
                $before_upgrade_is_free_vip = $user->isFreeVip();
                $user->vip_record = Carbon::now();
                $user->save();
                Vip::upgrade($user->id, '1111000', '0', 0, 'OOOOOOOO', 1, 1);
                
                if(!in_array( $last_pic_sys_react??null,LogFreeVipPicAct::$reachRuleSysReacts)) {
                    LogFreeVipPicAct::create(['user_id'=> $user->id
                         ,'sys_react'=>'auto_upgrade'
                         ,'shot_vip_record'=>$before_upgrade_vip_record
                          ,'shot_is_free_vip'=>$before_upgrade_is_free_vip 
                             ]);
                }
            }

            if($request->session()->exists('success')) {
                $request->session()->put('name', session('name') . "，已獲得免費 VIP");
            }
            //if($vip_record->diffInMinutes(Carbon::now()) <= 30){
            if($last_pic_sys_react=='reminding' && $last_pic_act_time->diffInMinutes(Carbon::now())<= 30){
                \Illuminate\Support\Facades\Log::info('RENEWAL for next line.');
            }
            \Illuminate\Support\Facades\Log::info('Free VIP new upgrade, user ID: ' . $user->id);
        }

        return $next($request);
    }
}
