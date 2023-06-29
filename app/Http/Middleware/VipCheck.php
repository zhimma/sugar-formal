<?php

namespace App\Http\Middleware;

use App\Http\Controllers\LineNotify;
use App\Models\lineNotifyChatSet;
use App\Services\AdminService;
use App\Services\VipLogService;
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
    public function __construct(Guard $auth, VipLogService $logService)
    {
        $this->auth = $auth;
        $this->logService = $logService;
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

        //曾經綁定過,但現在不是VIP帳號, 則ＬineNotify強制解綁
        if (!empty($user->line_notify_token) && !$user->isVipOrIsVvip()) {
            lineNotifyChatSet::where('user_id', $user->id)->delete();
            app(LineNotify::class)->lineNotifyRevoke($user->id, $user->line_notify_token);
        }

        // Check VIP expiry.
        if ($user->isVip()) {
            $userVIP = $user->getVipData(true);
            if (isset($userVIP)) {
                $expiry = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $userVIP->expiry);

                //藍新舊會員通知 過期則通知專屬頁面付費方案轉綠界
                $send_msg = '';
                if ($userVIP->business_id == '761404') {
                    $send_msg = 1;
                    $msg = AdminCommonText::getCommonTextByAlias('vipForNewebPay');
                    if (!Message::where(['from_id' => 1049, 'to_id' => $user->id])->where('content', 'like', '系統通知: 舊會員專屬優惠通知%')->first()) {
                        Message::post(1049, $user->id, $msg, true, 1);
                    }
                }

                if ($now > $expiry && $userVIP->expiry != '0000-00-00 00:00:00') {
                    \App\Models\VipLog::addToLog($user->id, 'Expired, system auto cancellation.', 'XXXXXXXXX', 0, 0);
                    $userVIP->removeVIP();
                    if ($send_msg == 1) {
                        //vipForNewebPay msg
                        $msg = AdminCommonText::getCommonTextByAlias('vipForNewebPay');
                        Message::post(1049, $user->id, $msg, true, 1);
                    }
                }
            }

            // 轉換性別為男生時取消原女免費 VIP
            if (view()->shared('isFreeVip')) {
                if ($user->engroup == 1) {
                    $userVIP = $user->getVipData(true);
                    \App\Models\VipLog::addToLog($user->id, 'Gender changed, free VIP checking and cancellation function triggered.', 'XXXXXXXXX', 0, 0);
                    $userVIP->removeVIP();
                }
            }
        }

        //加值服務到期判斷
        if(view()->shared('valueAddedServices')['hideOnline'] == 1){
            $userValueAddedService = \App\Models\ValueAddedService::getData($user->id,'hideOnline');
            $expiry = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $userValueAddedService->expiry);
            if($now > $expiry && $userValueAddedService->expiry != '0000-00-00 00:00:00') {
                \App\Models\ValueAddedService::removeValueAddedService($user->id, $userValueAddedService->service_name);
                \App\Models\ValueAddedServiceLog::addToLog($user->id, $userValueAddedService->service_name,'Expired, system auto cancellation.', $userValueAddedService->order_id, $userValueAddedService->txn_id,0);
            }

            //fix hideOnlineData if data empty
            $countHideOnlineData = \App\Models\hideOnlineData::where('user_id', $user->id)->withTrashed()->get()->count();
            if($countHideOnlineData == 0){
                \App\Models\ValueAddedService::addHideOnlineData($user->id);
            }else{
                //更新上線時間
                $HideOnlineData = \App\Models\hideOnlineData::where('user_id', $user->id)->where('deleted_at', null)->get()->first();
                if($user->hide_online_time != $HideOnlineData->login_time) {
                    User::where('id', $user->id)->update(['hide_online_time' => $HideOnlineData->login_time]);
                }
            }

        }elseif(view()->shared('valueAddedServices')['hideOnline'] == 0){
            User::where('id', $user->id)->update(['is_hide_online' => 0]);
        }

        //加值服務_vvip
//        if(view()->shared('valueAddedServices')['VVIP'] == 1) {
//            $userValueAddedService = \App\Models\ValueAddedService::getData($user->id, 'VVIP');
//            $expiry = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $userValueAddedService->expiry);
//            if ($now > $expiry && $userValueAddedService->expiry != '0000-00-00 00:00:00') {
//                \App\Models\ValueAddedService::removeValueAddedService($user->id, $userValueAddedService->service_name);
//                \App\Models\ValueAddedServiceLog::addToLog($user->id, $userValueAddedService->service_name, 'Expired, system auto cancellation.', $userValueAddedService->order_id, $userValueAddedService->txn_id, 0);
//            }
//
//            if($user->is_vvip==0 && $user->passVVIP()){
//                User::where('id', $user->id)->update(['is_vvip' => 1]);
//            }
//        }elseif(view()->shared('valueAddedServices')['VVIP'] == 0) {
//            User::where('id', $user->id)->update(['is_vvip' => 0]);
//        }

        if($user->isVVIP()){

            if($user->is_vvip==0) {
                User::where('id', $user->id)->update(['is_vvip' => 1]);
            }

            if($user->isVip()){
                \App\Models\VipLog::addToLog($user->id, 'User is VVIP, cancel VIP.', 'XXXXXXXXX', 0, 0);
                $userVIP = $user->getVipData(true);
                if($userVIP ?? false) {
                    logger('Upgrade VVIP, User ' . $user->id . ' VIP orderID '.$userVIP->order_id.' cancellation initiated.');
                    $vip = Vip::findByIdWithDateDesc($user->id);
                    $this->logService->cancelLog($vip);
                    $this->logService->writeLogToDB();
                    $file = $this->logService->writeLogToFile();
                    logger('$before_cancelVip: '.$vip->updated_at);
                    if( strpos(\Storage::disk('local')->get($file[0]), $file[1]) !== false) {
                        logger('Upgrade VVIP, User ' . $user->id . ' VIP orderID '.$userVIP->order_id.' cancellation finished.');
                    }
                }
                $userVIP->removeVIP();
            }

        }else{
            User::where('id', $user->id)->update(['is_vvip' => 0]);
        }

        return $next($request);
    }
}
