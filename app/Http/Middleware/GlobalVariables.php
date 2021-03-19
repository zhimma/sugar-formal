<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Support\Facades\Config;

class GlobalVariables
{
    protected $auth;

    public function __construct(Auth $auth)
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
        $user = $this->auth->user();
        \View::share('user', $user);
        if(isset($user)) {
            $valueAddedServices = array();
//            $valueAddedServices['hideOnline'] = 0;
            $valueAddedServices['hideOnline'] = $user->valueAddedServiceStatus('hideOnline');
            $isFreeVip = false;
            if ($user->isVip()) {
                \View::share('isVip', true);
                $isFreeVip = $user->isFreeVip();

                $vipData = $user->vip->sortByDesc("created_at")->first();
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
            \View::share('valueAddedServices', $valueAddedServices);
            $unread = \App\Models\Message::unread($user->id);
            \View::share('unread', $unread);
            \View::share('user_meta', $user->meta);
            \View::share('isFreeVip', $isFreeVip);
            \View::composer(['new.dashboard', 'new.dashboard.viewuser'], function($view) use ($user) {
                $view->with('isAdminWarned', $user->isAdminWarned());
             });

            $allMessage = \App\Models\Message::allMessage($user->id);
            \View::share('allMessage', $allMessage);
        }
        return $next($request);
    }
}
