<?php

namespace App\Http\Middleware;

use Gate;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Vip;
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

        $user_last_login = Carbon::parse($user->last_login);
        $vip_record = Carbon::parse($user->vip_record);

        //if($user->engroup == 1 || $user->engroup == 2 && !Vip::status($user->id)) return $next($request);
        //因為只針對女會員判斷權限，所以先過濾掉男會員
        //若女會員照片條件不符合，則過濾掉，不判斷剩下的規則
        //若女會員是付費VIP(!$user->isFreeVip()，是VIP且非免費)，則過濾掉，不判斷剩下的規則
        if($user->engroup == 1 || $user->engroup == 2 && !$user->existHeaderImage() || !$user->isFreeVip()){
            return $next($request);
        } 

        //如果一般會員(!isVip())失去免費VIP的時間(vip_record)與現在時間的差距小於系統設定的時間長度，則不做任何動作
        //這句是為了避免會員失去免費VIP後，馬上就又可以拿到免費VIP而設的條件
        if($vip_record->diffInSeconds(Carbon::now()) <= Config::get('social.vip.start') && !$user->isVip()) {
        }
        //提供免費VIP的邏輯，使用vip_record記錄提供的時間點
        else if(!$user->isVip()) {
            $user->vip_record = Carbon::now();
            $user->save();
            Vip::upgrade($user->id, '1111000', '0', 0, 'OOOOOOOO', 1, 1);
        }
        //如果免費VIP取得權限的時間點與現在時間的差距，小於系統設定的時間長度(代表他固定上線)，則將該時間點延長(延長VIP時間)
        else if($user->isVip() && $vip_record->diffInSeconds(Carbon::now()) <= Config::get('social.vip.free-days')) {
            $user->vip_record = Carbon::now();
            $user->save();
        }
        //若免費VIP都無法通過上述條件，則取消VIP並記錄下取消的時間點
        else if($user->isVip() && $user->isFreeVip()) {
            Vip::cancel($user->id, 1);
            $user->vip_record = Carbon::now();
            $user->save();
        }

        return $next($request);
    }
}
