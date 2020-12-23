<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Log;
use App\Models\Vip;
use App\Models\User;
use App\Models\Message;
use App\Models\AdminCommonText;

class VipCheck
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
        $now = new \DateTime(\Carbon\Carbon::now()->toDateTimeString());
        $user = $this->auth->user();
        // Check VIP expiry.
        if ($user->isVip()) {
            $userVIP = $user->getVipData(true);
            $expiry = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $userVIP->expiry);

            //藍新舊會員通知 過期則通知專屬頁面付費方案轉綠界
            $send_msg ='';
            if($userVIP->business_id == '761404'){
                $send_msg = 1;
            }
            if($now > $expiry && $userVIP->expiry != '0000-00-00 00:00:00'){
                \App\Models\VipLog::addToLog($user->id, 'Expired, system auto cancellation.', 'XXXXXXXXX', 0, 0);
                $userVIP->removeVIP();
                if($send_msg==1){
                    //vipForNewebPay msg
                    $msg = AdminCommonText::getCommonTextByAlias('vipForNewebPay');
                    Message::post(1049, $user->id, $msg, true, 1);
                }
            }
        }

        // 轉換性別為男生時取消原女免費 VIP
        if($user->isFreeVip()){
            if($user->engroup == 1) {
                $userVIP = $user->getVipData(true);
                \App\Models\VipLog::addToLog($user->id, 'Gender changed, free VIP checking and cancellation function triggered.', 'XXXXXXXXX', 0, 0);
                $userVIP->removeVIP();
            }
        }

        //加值服務到期判斷
        if($user->valueAddedServiceStatus('hideOnline')==1){
            $userValueAddedService = \App\Models\ValueAddedService::getData($user->id,'hideOnline');
            $expiry = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $userValueAddedService->expiry);
            if($now > $expiry && $userValueAddedService->expiry != '0000-00-00 00:00:00') {
                \App\Models\ValueAddedService::removeValueAddedService($user->id, $userValueAddedService->service_name);
                \App\Models\ValueAddedServiceLog::addToLog($user->id, $userValueAddedService->service_name,'Expired, system auto cancellation.', $userValueAddedService->order_id, $userValueAddedService->txn_id,0);
            }

        }
        return $next($request);
    }
}
