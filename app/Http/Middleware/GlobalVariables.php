<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\RealAuthPageService;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Support\Facades\Config;

class GlobalVariables
{
    protected $auth,$rap_service;

    public function __construct(Auth $auth, RealAuthPageService $rap_service)
    {
        $this->auth = $auth;
        $this->rap_service = $rap_service;
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
        $user = $this->auth->user();
        \View::share('user', $user);
        if(isset($user)) {
            $expiresAt = \Carbon\Carbon::now()->addMinutes(5);
            \Cache::put('user-is-online-' . \Auth::user()->id, true, $expiresAt);
            $valueAddedServices = array();
            //$valueAddedServices['hideOnline'] = 0;
            $valueAddedServices['hideOnline'] = $user->valueAddedServiceStatus('hideOnline');
            $valueAddedServices['VVIP'] = $user->valueAddedServiceStatus('VVIP');
            $isFreeVip = false;
            if ($user->isVip() || $user->isVVIP()) {
                \View::share('isVip', true);
                $isFreeVip = $user->isFreeVip();

                if($user->isVVIP()){
                    $vipData = $user->VVIP->first();
                }else {
                    $vipData = $user->vip->first();
                }
                // 全域 VIP 資料
                \View::share('vipData', $vipData);
                if (isset($vipData->updated_at)) {    //有的優選資格被拔掉的會員不會有 updated_at 的值
                    $now = \Carbon\Carbon::now();
                    $vipDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $vipData->updated_at);
                    //檢查會員 VIP 是否為綠界，若為綠界，則檢查是否為下一週期前七天內取消，若是，則設定變數
                    if ($vipData->business_id == '3137610' && $now->diffInDays($vipDate) <= 7) {
                        \View::share('vipLessThan7days', true);
                        \View::share('vipRenewDay', $vipDate->day);
                        \View::share('vipNextMonth', $vipDate->addMonth());
                    }
                }
            }
            else{
                \View::share('isVip', false);
            }
            
            if($this->rap_service->riseByUserEntry($user)->isAllowUseVideoChat()) {
                $self_auth_video_allusers = \DB::table('role_user')->leftJoin('users', 'role_user.user_id', '=', 'users.id')->where('users.id', '<>', $user->id)->get();
                \View::share('self_auth_video_allusers',$self_auth_video_allusers);
            }            
            
            \View::share('rap_service',$this->rap_service);
            \View::share('valueAddedServices', $valueAddedServices);
            \View::share('user_meta', $user->meta);
            \View::share('isFreeVip', $isFreeVip);
            \View::composer(['new.dashboard', 'new.dashboard.viewuser'], function($view) use ($user) {
                $view->with('isAdminWarned', $user->isAdminWarned());
            });
        }
        return $next($request);
    }
}
