<?php

namespace App\Jobs;

use App\Models\IsBannedLog;
use App\Models\Order;
use App\Models\PaymentGetQrcodeLog;
use App\Models\SetAutoBan;
use App\Models\SimpleTables\banned_users;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use App\Services\EnvironmentService;

class CheckECpay implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60;

    protected $vipData, $userIsVip, $job_user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($vipData, $userIsVip = null)
    {
        //
        $this->vipData = $vipData;
        if($userIsVip){
            $this->userIsVip = $userIsVip;
        }
        else{
            $this->userIsVip = $vipData->active;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        if(EnvironmentService::isLocalOrTestMachine()){
            $envStr = '_test';
        }
        else{
            $envStr = '';
        }

        //先檢查訂單
        if( ($this->vipData->business_id == Config::get('ecpay.payment'.$envStr.'.MerchantID') || $this->vipData->business_id == Config::get('funpoint.payment'.$envStr.'.MerchantID'))
            && substr($this->vipData->order_id,0,2) == 'SG') {

            $user = User::findById($this->vipData->member_id);
            if(!$user){
                logger("Null user found, vip data id: " . $this->vipData->id);
                return;
            }

            $now = Carbon::now();
            $OrderDataCheck = null;
            $admin = User::findByEmail(Config::get('social.admin.user-email'));

            $OrderData = Order::findByOrderId($this->vipData->order_id);
            if($OrderData) {
                //定期定額 未過期訂單
                if(($this->vipData->payment=='' || substr($this->vipData->payment,0,3)=='cc_') &&
                    $OrderData->order_expire_date == '') {
                    if ($this->vipData->payment == 'cc_quarterly_payment') {
                        $periodRemained = 92;
                    }
                    else{
                        $periodRemained = 30;
                    }

                    //取本機訂單最後扣款日
                    $lastProcessDate = last(json_decode($OrderData->pay_date));
                    $theActualLastProcessDate = is_string($lastProcessDate[0]) ? Carbon::parse($lastProcessDate[0]) : $lastProcessDate[0];
                    //計算下次扣款日
                    $nextProcessDate = substr($theActualLastProcessDate->addDays($periodRemained), 0, 10);

                    //本機訂單最後扣款日至今天數已超過下次扣款天數 && 扣款日期已過
                    if( $now->diffInDays($theActualLastProcessDate) > $periodRemained ) {
                        if(!(EnvironmentService::isLocalOrTestMachine())) {
                            try {
                                //更新訂單 by payment_flow
                                if ($OrderData->payment_flow == 'ecpay') {
                                    $updateEcPayOrder = Order::updateEcPayOrder($this->vipData->order_id);
                                    if ($updateEcPayOrder) {
                                        //重新查詢訂單並檢查
                                        $OrderDataCheck = Order::findByOrderId($this->vipData->order_id);
                                    }
                                }
                                elseif ($OrderData->payment_flow == 'funpoint') {
                                    $updateFunPointPayOrder = Order::updateFunPointPayOrder($this->vipData->order_id);
                                    if ($updateFunPointPayOrder) {
                                        //重新查詢訂單並檢查
                                        $OrderDataCheck = Order::findByOrderId($this->vipData->order_id);
                                    }
                                }
                            }
                            catch (\Exception $exception) {
                                Log::info("VIP id: " . $this->vipData->id . "；order_id: " . $this->vipData->order_id . "：訂單更新失敗");
                                Log::error($exception);
                            }
                        }
                    }
                }
                //定期定額 有到期日訂單
                elseif(($this->vipData->payment=='' || substr($this->vipData->payment,0,3)=='cc_') &&
                    $OrderData->order_expire_date != '') {
                    $OrderDataCheck = $OrderData;
                }
                ///預先給予權限訂單判斷 有訂單
                elseif($this->vipData->payment_method=='BARCODE' || $this->vipData->payment_method=='CVS' || $this->vipData->payment_method=='ATM') {
                    $preOrderCheck = PaymentGetQrcodeLog::where('order_id', $this->vipData->order_id)->first();
                    if($preOrderCheck) {
                        if (!$this->userIsVip && Carbon::parse($OrderData->order_expire_date)->gt($now)) {
                            \App\Models\Vip::select('member_id', 'active')
                                ->where('member_id', $this->vipData->member_id)
                                ->update(array('active' => 1, 'expiry' => $OrderData->order_expire_date));
                            \App\Models\VipLog::addToLog($user->id, 'order_id: ' . $this->vipData->order_id . '; 繳款檢查正常回復VIP：' . $this->vipData->payment_method, '自動回復', 0, 0);
                        }
                        elseif($this->userIsVip && Carbon::parse($OrderData->order_expire_date) !=  Carbon::parse($this->vipData->expiry) && Carbon::parse($OrderData->order_expire_date)->diffInDays(Carbon::parse($this->vipData->expiry))>20) {
                            \App\Models\Vip::select('member_id', 'active')
                                ->where('member_id', $this->vipData->member_id)
                                ->update(array('expiry' => $OrderData->order_expire_date));
                            \App\Models\VipLog::addToLog($user->id, 'order_id: ' . $this->vipData->order_id . '; VIP訂單檢查：' . $this->vipData->payment_method, '到期日自動調整', 0, 0);
                        }
                    }
                }

            }
            //Order無訂單資料時 從金流新增訂單
            else{
                //預先給予權限訂單判斷 無訂單
                if($this->vipData->payment_method=='BARCODE' || $this->vipData->payment_method=='CVS' || $this->vipData->payment_method=='ATM') {
                    $preOrderCheck = PaymentGetQrcodeLog::where('order_id', $this->vipData->order_id)->first();
                    if($preOrderCheck) {
                        $originExpireDate = $preOrderCheck->ExpireDate;
                        try {
                            $newExpireDate = Carbon::parse($originExpireDate)->addDays(2);
                        }
                        catch (\Exception $exception) {
                            \Sentry\captureException($exception);
                            \Sentry\captureMessage($originExpireDate);
                        }
                        if($this->userIsVip && $now->gt($newExpireDate)) {
                            $checkOrder = false;
                            //反查一次確認付款狀態
                            if (!(EnvironmentService::isLocalOrTestMachine())) {
                                try {
                                    //從ecPay
                                    $checkOrder = Order::addEcPayOrder($this->vipData->order_id);
                                    if (!$checkOrder) {
                                        //從funPoint
                                        $checkOrder = Order::addFunPointPayOrder($this->vipData->order_id);
                                    }
                                    //重新抓訂單
                                    if ($checkOrder) {
                                        $OrderDataCheck = Order::findByOrderId($this->vipData->order_id);
                                    }
                                }
                                catch (\Exception $exception) {
                                    Log::info("VIP id: " . $this->vipData->id . "；order_id: " . $this->vipData->order_id . "：未完成付款");
                                    Log::error($exception);
                                }
                            }
                            //有賦予VIP者再檢查
                            //未完成交易時檢查
                            //超過期限未完成交易
                            //取消VIP
                            if(!$checkOrder) {
                                $vipData = $user->getVipData(true);
                                if ($vipData) {
                                    $vipData->removeVIP();
                                }
                                \App\Models\VipLog::addToLog($user->id, 'order_id: ' . $this->vipData->order_id . '; 期限內(' . $preOrderCheck->ExpireDate . ')未完成付款：' . $this->vipData->payment_method, '自動取消', 0, 0);

//                                if(!User::isBanned($user->id)) {
//                                    //累計三次未繳費加入封鎖
//                                    $getUserQrcodeHistory = PaymentGetQrcodeLog::select('order_id')
//                                        ->where('user_id', $user->id)
//                                        ->where('ExpireDate', '<', $now)
//                                        ->get();
//                                    $getUserPaidOrders = Order::where('user_id', $user->id)
//                                        ->whereIn('order_id', $getUserQrcodeHistory)
//                                        ->get();
//                                    $checkNoPayCounts = count($getUserQrcodeHistory) - count($getUserPaidOrders);
//                                    if ($checkNoPayCounts >= 3) {
//                                        //封鎖
//                                        $userBanned = new banned_users;
//                                        $userBanned->member_id = $user->id;
//                                        $userBanned->reason = "拒往";
//                                        $userBanned->save();
//                                        //寫入log
//                                        IsBannedLog::insert(['user_id' => $user->id, 'reason' => "拒往"]);
//                                        logger("Baned user {$user->id}, reason: 拒往");
//                                        //自動封鎖cfp_id
//                                        foreach($user->cfp as $row) {
//                                            $existData = SetAutoBan::where('type','cfp_id')
//                                                ->where('content', $row->cfp_id)
//                                                ->where('cuz_user_set', $user->id)
//                                                ->first();
//                                            if(!$existData) {
//                                                SetAutoBan::setAutoBanAdd('cfp_id', $row->cfp_id, 1, $user->id);
//                                            }
//                                        }
//                                    }
//                                }
                            }
                        }
                    }
                }
                //其他付款方式 無訂單時
                else {
                    if (!(EnvironmentService::isLocalOrTestMachine())) {
                        try {
                            //從ecPay
                            $addOrder = Order::addEcPayOrder($this->vipData->order_id);
                            if (!$addOrder) {
                                //從funPoint
                                $addOrder = Order::addFunPointPayOrder($this->vipData->order_id);
                            }
                            //重新抓訂單
                            if ($addOrder) {
                                $OrderDataCheck = Order::findByOrderId($this->vipData->order_id);
                            }
                        }
                        catch (\Exception $exception) {
                            Log::info("VIP id: " . $this->vipData->id . "；order_id: " . $this->vipData->order_id . "：查無訂單");
                            Log::error($exception);
                        }
                    }
                }
            }

            //依到期日與否進行檢查 OrderDataCheck
            if($OrderDataCheck){
                //有到期日
                if($OrderDataCheck->order_expire_date != ''){
                    $vipData = $user->getVipData(true);

                    //正常訂單檢查到期日
                    //20天內差異者不異動
                    if(Carbon::parse($OrderDataCheck->order_expire_date) !=  Carbon::parse($this->vipData->expiry) && Carbon::parse($OrderDataCheck->order_expire_date)->diffInDays(Carbon::parse($this->vipData->expiry))>20) {
                        \App\Models\Vip::select('member_id', 'active')
                            ->where('member_id', $this->vipData->member_id)
                            ->update(array('expiry' => $OrderDataCheck->order_expire_date));
                        \App\Models\VipLog::addToLog($user->id, 'order_id: ' . $this->vipData->order_id . '; VIP訂單檢查：' . $OrderDataCheck->payment_type, '到期日自動調整', 0, 0);
                    }
                    //VIP檢查過期
                    if( $this->userIsVip && $now->gt($OrderDataCheck->order_expire_date) && $now->gt($this->vipData->expiry) ){
                        //取消VIP 防呆處理
                        if($vipData){
                            $vipData->removeVIP();
                            \App\Models\VipLog::addToLog($user->id, 'Background auto cancel, order expire date: ' . $OrderDataCheck->order_expire_date, '自動取消', 0, 0);
                        }

                    }
                    //非VIP檢查尚未過期
                    elseif(!$this->userIsVip && Carbon::parse($OrderDataCheck->order_expire_date)->gte($now) ){
                        Log::info('VIP 回復');
                        $expiryDate = $OrderDataCheck->order_expire_date;
                        if(Carbon::parse($this->vipData->expiry)->gt($OrderDataCheck->order_expire_date)){
                            $expiryDate = $this->vipData->expiry;
                        }
                        \App\Models\Vip::where('member_id', $this->vipData->member_id)
                            ->update(array('active' => 1, 'expiry' => $expiryDate));
                        \App\Models\VipLog::addToLog($user->id, 'Background auto upgrade, order expire date: ' . $OrderDataCheck->order_expire_date, '尚未到期，自動回復', 0, 0);
                    }
                }
                //訂單尚未到期
                else{
                    if ($OrderDataCheck->payment == 'cc_quarterly_payment') {
                        $periodRemained = 92;
                    }
                    else{
                        $periodRemained = 30;
                    }

                    $lastProcessDate = last(json_decode($OrderDataCheck->pay_date));
                    $theActualLastProcessDate = is_string($lastProcessDate[0]) ? Carbon::parse($lastProcessDate[0]) : $lastProcessDate[0];

                    //最後一次付款成功，但已過期
                    //等同金流最後一次扣款失敗 但訂單不會抓失敗的日期 故一併判斷為扣款失敗
                    //vip檢查付款日 應付日但未付時判斷
                    if($this->userIsVip && $theActualLastProcessDate->diffInDays($now) > $periodRemained){
                        Log::info('付費失敗');
                        Log::info($OrderDataCheck);

                        $vipData = $user->getVipData(true);
                         if($vipData){
                             $vipData->removeVIP();
                         }

                         \App\Models\VipLog::addToLog($user->id, 'Background auto cancel, last process date: ' . $theActualLastProcessDate->format('Y-m-d'), '自動取消', 0, 0);
                         $message = $user->name . "您好，您的 VIP 付費(卡號後四碼 " . $OrderDataCheck->card4no . ")最後一次付費月份為 " . $theActualLastProcessDate->format('Y 年 m 月') . " ，距今已逾一個月或扣款失敗，故停止您的 VIP 權限。優選會員資格一併取消，若有疑問請點右下聯絡我們連絡站長。";
                         \App\Models\Message_new::post($admin->id, $user->id, $message);

                         $str = '末四碼：' . $OrderDataCheck->card4no . "<br>" .
                             "會員 ID：" . $this->vipData->member_id . "<br>" .
                             "訂單編號：" . $this->vipData->order_id . "<br>" .
                             "金流平台：" . $OrderDataCheck->payment_flow;
                         \Mail::raw($str, function ($message, $payment_flow) {
                             $message->from('admin@sugar-garden.org', 'Sugar-garden');
                             $message->to('admin@sugar-garden.org');
                             $message->subject('扣款失敗通知');
                         });
                    }
                    //非VIP檢查
                    elseif(!$this->userIsVip && $theActualLastProcessDate->diffInDays($now) < $periodRemained) {
                        Log::info('VIP 回復');
                        Log::info($OrderDataCheck);

                        //$OrderDataCheck->order_expire_date is null
                        \App\Models\Vip::where('member_id', $this->vipData->member_id)
                            ->update(array('active' => 1, 'expiry' => '0000-00-00 00:00:00'));

                        \App\Models\VipLog::addToLog($user->id, 'Background auto upgrade, last process date: ' . $theActualLastProcessDate->format('Y-m-d'), '自動回復', 0, 0);
                        $message = $user->name . "您好，由於您的 VIP 付費(卡號後四碼 " . $OrderDataCheck->card4no . ")曾因扣款失敗被停止 VIP 權限，但最近一次又再次付費成功，月份為 " . $theActualLastProcessDate->format('Y 年 m 月') . "，故回復您的 VIP 權限。若有疑問請點右下聯絡我們連絡站長。";
                        \App\Models\Message_new::post($admin->id, $user->id, $message);
                        $str = '末四碼：' . $OrderDataCheck->card4no . "<br>" .
                             "會員 ID：" . $this->vipData->member_id . "<br>" .
                             "訂單編號：" . $this->vipData->order_id;
                        \Mail::raw($str, function ($message) {
                            $message->from('admin@sugar-garden.org', 'Sugar-garden');
                            $message->to('admin@sugar-garden.org');
                            $message->subject('VIP 回復通知');
                        });
                    }

                }
            }

        }


//         if($this->vipData->business_id == Config::get('ecpay.payment'.$envStr.'.MerchantID') && substr($this->vipData->order_id,0,2) == 'SG'){
//             $ecpay = new \App\Services\ECPay_AllInOne();
//             $ecpay->MerchantID = Config::get('ecpay.payment'.$envStr.'.MerchantID');
// //            $ecpay->ServiceURL = Config::get('ecpay.payment'.$envStr.'.ServiceURL');//定期定額查詢
//             $ecpay->ServiceURL = Config::get('ecpay.payment'.$envStr.'.OrderQueryURL');//訂單查詢
//             $ecpay->HashIV = Config::get('ecpay.payment'.$envStr.'.HashIV');
//             $ecpay->HashKey = Config::get('ecpay.payment'.$envStr.'.HashKey');
//             $ecpay->Query = [
//                 'MerchantTradeNo' => $this->vipData->order_id,
//                 'TimeStamp' => 	time()
//             ];
//             try{
//                 if(substr($this->vipData->payment,0,4) == 'one_'){ //保留用
//                     //單次付費
//                     $ecpay->ServiceURL = Config::get('ecpay.payment'.$envStr.'.OrderQueryURL');//訂單查詢
//                     $paymentQueryData = $ecpay->QueryTradeInfo();
//                     $ecpay->ServiceURL = Config::get('ecpay.payment'.$envStr.'.ServiceURL');//定期定額查詢
//                     $paymentData = $ecpay->QueryPeriodCreditCardTradeInfo();
//                     // 此函式會產生錯誤，經檢查應為無用函式
//                 }else {
//                     //信用卡定期定額
//                     $ecpay->ServiceURL = Config::get('ecpay.payment'.$envStr.'.ServiceURL');//定期定額查詢
//                     $paymentData = $ecpay->QueryPeriodCreditCardTradeInfo();
//                 }
//             }
//             catch (\Exception $exception){
//                 Log::info("VIP id: " . $this->vipData->id);
//                 Log::info("VIP payment: " . $this->vipData->payment);
//                 Log::error($exception);
//             }

//             $now = Carbon::now();

//             if(substr($this->vipData->payment,0,4) == 'one_'){
//                 //單次付款檢查
//                 if (str_contains($paymentQueryData['PaymentType'], 'CVS') ||
//                     str_contains($paymentQueryData['PaymentType'], 'ATM') ||
//                     str_contains($paymentQueryData['PaymentType'], 'BARCODE')) {
//                     $user = User::findById($this->vipData->member_id);

//                     if($this->userIsVip && $paymentQueryData['TradeStatus'] != 1) {
//                         //有賦予VIP者再檢查
//                         //未完成交易時檢查
//                         //check取號資料表
//                         $checkData = PaymentGetQrcodeLog::where('order_id', $this->vipData->order_id)->first();
//                         if(isset($checkData)){
//                             if($now->gt($checkData->ExpireDate)){
//                                 //超過期限未完成交易
//                                 //取消VIP
//                                 $vipData = $user->getVipData(true);
//                                 if($vipData){
//                                     $vipData->removeVIP();
//                                 }
//                                 \App\Models\VipLog::addToLog($user->id, 'order_id: '.$this->vipData->order_id.'; 期限內('.$checkData->ExpireDate.')未完成付款：' . $paymentQueryData['PaymentType'], '自動取消', 0, 0);
//                             }
//                         }
//                     }elseif(!$this->userIsVip && $paymentQueryData['TradeStatus'] == 1){
//                         $getOrderDate = Order::where('order_id', $this->vipData->order_id)->first();
//                         if(isset($getOrderDate) && Carbon::parse($getOrderDate->order_expire_date)->gt($now)) {
//                             \App\Models\Vip::select('member_id', 'active')
//                                 ->where('member_id', $this->vipData->member_id)
//                                 ->update(array('active' => 1, 'expiry' => $getOrderDate->order_expire_date));
//                             \App\Models\VipLog::addToLog($user->id, 'order_id: ' . $this->vipData->order_id . '; 繳款檢查正常回復VIP：' . $paymentQueryData['PaymentType'], '自動回復', 0, 0);
//                         }
//                     }elseif($this->userIsVip && $paymentQueryData['TradeStatus'] == 1){
//                         //正常訂單檢查到期日
//                         //20天內差異者不異動
//                         $getOrderDate = Order::where('order_id', $this->vipData->order_id)->first();
//                         if(isset($getOrderDate) && Carbon::parse($getOrderDate->order_expire_date) !=  Carbon::parse($this->vipData->expiry) && Carbon::parse($getOrderDate->order_expire_date)->diffInDays(Carbon::parse($this->vipData->expiry))>20) {
//                             \App\Models\Vip::select('member_id', 'active')
//                                 ->where('member_id', $this->vipData->member_id)
//                                 ->update(array('expiry' => $getOrderDate->order_expire_date));
//                             \App\Models\VipLog::addToLog($user->id, 'order_id: ' . $this->vipData->order_id . '; VIP訂單檢查：' . $paymentQueryData['PaymentType'], '到期日自動調整', 0, 0);
//                         }

//                     }
//                 }
//             }else { //定期定額流程
//                 try{
//                     $last = last($paymentData['ExecLog']);
//                 }
//                 catch (\Exception $e){
//                     Log::error("ExecLog is null, VIP id: " . $this->vipData->id);
//                     return;
//                 }
//                 $lastProcessDate = str_replace('%20', ' ', $last['process_date']);
//                 $lastProcessDate = Carbon::createFromFormat('Y/m/d H:i:s', $lastProcessDate);
//                 // 三個月一期或一個月一期
//                 try{
//                     if(str_contains($this->vipData->payment, 'quarterly')){
//                         $days = 94;
//                     }
//                     else{
//                         $days = 31;
//                     }
//                 }
//                 catch (\Throwable $e){
//                     logger("CheckECpay null payment, user id: " . $this->vipData->member_id);
//                     $days = 31;
//                 }

//                 //付款日期有差異時更新訂單
//                 $currentOrder = Order::where('order_id', $this->vipData->order_id)->first();
//                 if(isset($currentOrder)) {
//                     $current_order_pay_date = last(json_decode($currentOrder->pay_date));
//                     if ($last['RtnCode'] == 1 && $lastProcessDate != $current_order_pay_date[0]) {
//                         Order::updateEcPayOrder($this->vipData->order_id);
//                     }
//                 }else{
//                     //資料表無此訂單時新增
//                     //新增訂單
//                     Order::addEcPayOrder($this->vipData->order_id);
//                 }

//                 // 最後一次付款成功，但已過期
//                 if ($last['RtnCode'] == 1 && $lastProcessDate->diffInDays($now) > $days && $this->userIsVip) {
//                     Log::info('付費失敗');
//                     Log::info($paymentData);

//                     $admin = User::findByEmail(Config::get('social.admin.user-email'));
//                     $user = User::findById($this->vipData->member_id);
//                     if(!$user){
//                         logger("Null user found, vip data id: " . $this->vipData->id);
//                         return;
//                     }
//                     $vipData = $user->getVipData(true);
//                     if($vipData){
//                         $vipData->removeVIP();
//                     }

//                     if(!(EnvironmentService::isLocalOrTestMachine())) {
//                         //更新訂單 --正式綠界
//                         Order::updateEcPayOrder($this->vipData->order_id);
//                     }

//                     \App\Models\VipLog::addToLog($user->id, 'Background auto cancel, last process date: ' . $lastProcessDate->format('Y-m-d'), '自動取消', 0, 0);
//                     $message = $user->name . "您好，您的 VIP 付費(卡號後四碼 " . $paymentData['card4no'] . ")最後一次付費月份為 " . $lastProcessDate->format('Y 年 m 月') . " ，距今已逾一個月，故停止您的 VIP 權限。優選會員資格一併取消，若有疑問請點右下聯絡我們連絡站長。";
//                     \App\Models\Message_new::post($admin->id, $user->id, $message);

//                     $str = '末四碼：' . $paymentData['card4no'] . "<br>" .
//                         "會員 ID：" . $this->vipData->member_id . "<br>" .
//                         "訂單編號：" . $this->vipData->order_id;
//                     \Mail::raw($str, function ($message) {
//                         $message->from('admin@sugar-garden.org', 'Sugar-garden');
//                         $message->to('admin@sugar-garden.org');
//                         $message->subject('綠界扣款失敗通知');
//                     });
//                 }
//                 // 非 VIP，檢查最後一次付款是否成功且定期定額狀態正常
//                 else if (!$this->userIsVip && $last['RtnCode'] == 1 && $paymentData['ExecStatus'] == 1) {
//                     Log::info('VIP 回復');
//                     Log::info($paymentData);

//                     $admin = User::findByEmail(Config::get('social.admin.user-email'));
//                     $user = User::findById($this->vipData->member_id);
//                     \App\Models\Vip::select('member_id', 'active')
//                         ->where('member_id', $this->vipData->member_id)
//                         ->update(array('active' => 1, 'expiry' => '0000-00-00 00:00:00'));

//                     if(!(EnvironmentService::isLocalOrTestMachine())) {
//                         //更新訂單 --正式綠界
//                         Order::updateEcPayOrder($this->vipData->order_id);
//                     }

//                     \App\Models\VipLog::addToLog($user->id, 'Background auto upgrade, last process date: ' . $lastProcessDate->format('Y-m-d'), '自動回復', 0, 0);
//                     $message = $user->name . "您好，由於您的 VIP 付費(卡號後四碼 " . $paymentData['card4no'] . ")曾因扣款失敗被停止 VIP 權限，但最近一次又再次付費成功，月份為 " . $lastProcessDate->format('Y 年 m 月') . "，故回復您的 VIP 權限。若有疑問請點右下聯絡我們連絡站長。";
//                     \App\Models\Message_new::post($admin->id, $user->id, $message);

//                     $str = '末四碼：' . $paymentData['card4no'] . "<br>" .
//                         "會員 ID：" . $this->vipData->member_id . "<br>" .
//                         "訂單編號：" . $this->vipData->order_id;
//                     \Mail::raw($str, function ($message) {
//                         $message->from('admin@sugar-garden.org', 'Sugar-garden');
//                         $message->to('admin@sugar-garden.org');
//                         $message->subject('VIP 回復通知');
//                     });
//                 }
//                 // 最後一次付款失敗
//                 else if ($this->userIsVip && $last['RtnCode'] != 1) {
//                     Log::info('付費失敗');
//                     Log::info($paymentData);

//                     $admin = User::findByEmail(Config::get('social.admin.user-email'));
//                     $user = User::findById($this->vipData->member_id);
//                     $user->getVipData(true)->removeVIP();

//                     if(!(EnvironmentService::isLocalOrTestMachine())) {
//                         //更新訂單 --正式綠界
//                         Order::updateEcPayOrder($this->vipData->order_id);
//                     }

//                     \App\Models\VipLog::addToLog($user->id, 'Background auto cancel, last process date: ' . $lastProcessDate->format('Y-m-d'), '自動取消', 0, 0);
//                     $message = $user->name . "您好，您的 VIP 付費(卡號後四碼 " . $paymentData['card4no'] . ")已於 " . $lastProcessDate->format('Y 年 m 月') . " 扣款失敗，故停止您的 VIP 權限。優選會員資格一併取消，若有疑問請點右下聯絡我們連絡站長。";
//                     \App\Models\Message_new::post($admin->id, $user->id, $message);

//                     $str = '末四碼：' . $paymentData['card4no'] . "<br>" .
//                         "會員 ID：" . $this->vipData->member_id . "<br>" .
//                         "訂單編號：" . $this->vipData->order_id;
//                     \Mail::raw($str, function ($message) {
//                         $message->from('admin@sugar-garden.org', 'Sugar-garden');
//                         $message->to('admin@sugar-garden.org');
//                         $message->subject('綠界扣款失敗通知');
//                     });
//                 }
//             }
//         }elseif($this->vipData->business_id == Config::get('funpoint.payment'.$envStr.'.MerchantID') && substr($this->vipData->order_id,0,2) == 'SG'){
//             $ecpay = new \App\Services\ECPay_AllInOne();
//             $ecpay->MerchantID = Config::get('funpoint.payment'.$envStr.'.MerchantID');
//             $ecpay->ServiceURL = Config::get('funpoint.payment'.$envStr.'.OrderQueryURL');//訂單查詢
//             $ecpay->HashIV = Config::get('funpoint.payment'.$envStr.'.HashIV');
//             $ecpay->HashKey = Config::get('funpoint.payment'.$envStr.'.HashKey');
//             $ecpay->Query = [
//                 'MerchantTradeNo' => $this->vipData->order_id,
//                 'TimeStamp' => 	time()
//             ];
//             try{
//                 if(substr($this->vipData->payment,0,4) == 'one_'){ //保留用
//                     //單次付費
//                     $ecpay->ServiceURL = Config::get('funpoint.payment'.$envStr.'.OrderQueryURL');//訂單查詢
//                     $paymentQueryData = $ecpay->QueryTradeInfo();
//                     $ecpay->ServiceURL = Config::get('funpoint.payment'.$envStr.'.ServiceURL');//定期定額查詢
//                     $paymentData = $ecpay->QueryPeriodCreditCardTradeInfo();
//                     // 此函式會產生錯誤，經檢查應為無用函式
//                 }else {
//                     //信用卡定期定額
//                     $ecpay->ServiceURL = Config::get('funpoint.payment'.$envStr.'.ServiceURL');//定期定額查詢
//                     $paymentData = $ecpay->QueryPeriodCreditCardTradeInfo();
//                 }
//             }
//             catch (\Exception $exception){
//                 Log::info("VIP id: " . $this->vipData->id);
//                 Log::info("VIP payment: " . $this->vipData->payment);
//                 Log::error($exception);
//             }

//             $now = Carbon::now();

//             if(substr($this->vipData->payment,0,4) == 'one_'){
//                 //單次付款檢查
//                 if (str_contains($paymentQueryData['PaymentType'], 'CVS') ||
//                     str_contains($paymentQueryData['PaymentType'], 'ATM') ||
//                     str_contains($paymentQueryData['PaymentType'], 'BARCODE')) {
//                     $user = User::findById($this->vipData->member_id);

//                     if($this->userIsVip && $paymentQueryData['TradeStatus'] != 1) {
//                         //有賦予VIP者再檢查
//                         //未完成交易時檢查
//                         //check取號資料表
//                         $checkData = PaymentGetQrcodeLog::where('order_id', $this->vipData->order_id)->first();
//                         if(isset($checkData)){
//                             if($now->gt($checkData->ExpireDate)){
//                                 //超過期限未完成交易
//                                 //取消VIP
//                                 $vipData = $user->getVipData(true);
//                                 if($vipData){
//                                     $vipData->removeVIP();
//                                 }
//                                 \App\Models\VipLog::addToLog($user->id, 'order_id: '.$this->vipData->order_id.'; 期限內('.$checkData->ExpireDate.')未完成付款：' . $paymentQueryData['PaymentType'], '自動取消', 0, 0);
//                             }
//                         }
//                     }elseif(!$this->userIsVip && $paymentQueryData['TradeStatus'] == 1){
//                         $getOrderDate = Order::where('order_id', $this->vipData->order_id)->first();
//                         if(isset($getOrderDate) && Carbon::parse($getOrderDate->order_expire_date)->gt($now)) {
//                             \App\Models\Vip::select('member_id', 'active')
//                                 ->where('member_id', $this->vipData->member_id)
//                                 ->update(array('active' => 1, 'expiry' => $getOrderDate->order_expire_date));
//                             \App\Models\VipLog::addToLog($user->id, 'order_id: ' . $this->vipData->order_id . '; 繳款檢查正常回復VIP：' . $paymentQueryData['PaymentType'], '自動回復', 0, 0);
//                         }
//                     }elseif($this->userIsVip && $paymentQueryData['TradeStatus'] == 1){
//                         //正常訂單檢查到期日
//                         //20天內差異者不異動
//                         $getOrderDate = Order::where('order_id', $this->vipData->order_id)->first();
//                         if(isset($getOrderDate) && Carbon::parse($getOrderDate->order_expire_date) !=  Carbon::parse($this->vipData->expiry) && Carbon::parse($getOrderDate->order_expire_date)->diffInDays(Carbon::parse($this->vipData->expiry))>20) {
//                             \App\Models\Vip::select('member_id', 'active')
//                                 ->where('member_id', $this->vipData->member_id)
//                                 ->update(array('expiry' => $getOrderDate->order_expire_date));
//                             \App\Models\VipLog::addToLog($user->id, 'order_id: ' . $this->vipData->order_id . '; VIP訂單檢查：' . $paymentQueryData['PaymentType'], '到期日自動調整', 0, 0);
//                         }

//                     }
//                 }
//             }else { //定期定額流程
//                 try{
//                     $last = last($paymentData['ExecLog']);
//                 }
//                 catch (\Exception $e){
//                     Log::error("ExecLog is null, VIP id: " . $this->vipData->id);
//                     return;
//                 }
//                 $lastProcessDate = str_replace('%20', ' ', $last['process_date']);
//                 $lastProcessDate = Carbon::createFromFormat('Y/m/d H:i:s', $lastProcessDate);
//                 // 三個月一期或一個月一期
//                 try{
//                     if(str_contains($this->vipData->payment, 'quarterly')){
//                         $days = 94;
//                     }
//                     else{
//                         $days = 31;
//                     }
//                 }
//                 catch (\Throwable $e){
//                     logger("CheckECpay null payment, user id: " . $this->vipData->member_id);
//                     $days = 31;
//                 }

//                 //付款日期有差異時更新訂單
//                 $currentOrder = Order::where('order_id', $this->vipData->order_id)->first();
//                 if(isset($currentOrder)) {
//                     $current_order_pay_date = last(json_decode($currentOrder->pay_date));
//                     if ($last['RtnCode'] == 1 && $lastProcessDate != $current_order_pay_date[0]) {
//                         Order::updateFunPointPayOrder($this->vipData->order_id);
//                     }
//                 }else{
//                     //資料表無此訂單時新增
//                     //新增訂單
//                     Order::addFunPointPayOrder($this->vipData->order_id);
//                 }

//                 // 最後一次付款成功，但已過期
//                 if ($last['RtnCode'] == 1 && $lastProcessDate->diffInDays($now) > $days && $this->userIsVip) {
//                     Log::info('付費失敗');
//                     Log::info($paymentData);

//                     $admin = User::findByEmail(Config::get('social.admin.user-email'));
//                     $user = User::findById($this->vipData->member_id);
//                     if(!$user){
//                         logger("Null user found, vip data id: " . $this->vipData->id);
//                         return;
//                     }
//                     $vipData = $user->getVipData(true);
//                     if($vipData){
//                         $vipData->removeVIP();
//                     }

//                     if(!(EnvironmentService::isLocalOrTestMachine())) {
//                         //更新訂單 --正式
//                         Order::updateFunPointPayOrder($this->vipData->order_id);
//                     }

//                     \App\Models\VipLog::addToLog($user->id, 'Background auto cancel, last process date: ' . $lastProcessDate->format('Y-m-d'), '自動取消', 0, 0);
//                     $message = $user->name . "您好，您的 VIP 付費(卡號後四碼 " . $paymentData['card4no'] . ")最後一次付費月份為 " . $lastProcessDate->format('Y 年 m 月') . " ，距今已逾一個月，故停止您的 VIP 權限。優選會員資格一併取消，若有疑問請點右下聯絡我們連絡站長。";
//                     \App\Models\Message_new::post($admin->id, $user->id, $message);

//                     $str = '末四碼：' . $paymentData['card4no'] . "<br>" .
//                         "會員 ID：" . $this->vipData->member_id . "<br>" .
//                         "訂單編號：" . $this->vipData->order_id;
//                     \Mail::raw($str, function ($message) {
//                         $message->from('admin@sugar-garden.org', 'Sugar-garden');
//                         $message->to('admin@sugar-garden.org');
//                         $message->subject('FunPointPay 扣款失敗通知');
//                     });
//                 }
//                 // 非 VIP，檢查最後一次付款是否成功且定期定額狀態正常
//                 else if (!$this->userIsVip && $last['RtnCode'] == 1 && $paymentData['ExecStatus'] == 1) {
//                     Log::info('VIP 回復');
//                     Log::info($paymentData);

//                     $admin = User::findByEmail(Config::get('social.admin.user-email'));
//                     $user = User::findById($this->vipData->member_id);
//                     \App\Models\Vip::select('member_id', 'active')
//                         ->where('member_id', $this->vipData->member_id)
//                         ->update(array('active' => 1, 'expiry' => '0000-00-00 00:00:00'));

//                     if(!(EnvironmentService::isLocalOrTestMachine())) {
//                         //更新訂單 --正式
//                         Order::updateFunPointPayOrder($this->vipData->order_id);
//                     }

//                     \App\Models\VipLog::addToLog($user->id, 'Background auto upgrade, last process date: ' . $lastProcessDate->format('Y-m-d'), '自動回復', 0, 0);
//                     $message = $user->name . "您好，由於您的 VIP 付費(卡號後四碼 " . $paymentData['card4no'] . ")曾因扣款失敗被停止 VIP 權限，但最近一次又再次付費成功，月份為 " . $lastProcessDate->format('Y 年 m 月') . "，故回復您的 VIP 權限。若有疑問請點右下聯絡我們連絡站長。";
//                     \App\Models\Message_new::post($admin->id, $user->id, $message);

//                     $str = '末四碼：' . $paymentData['card4no'] . "<br>" .
//                         "會員 ID：" . $this->vipData->member_id . "<br>" .
//                         "訂單編號：" . $this->vipData->order_id;
//                     \Mail::raw($str, function ($message) {
//                         $message->from('admin@sugar-garden.org', 'Sugar-garden');
//                         $message->to('admin@sugar-garden.org');
//                         $message->subject('VIP 回復通知');
//                     });
//                 }
//                 // 最後一次付款失敗
//                 else if ($this->userIsVip && $last['RtnCode'] != 1) {
//                     Log::info('付費失敗');
//                     Log::info($paymentData);

//                     $admin = User::findByEmail(Config::get('social.admin.user-email'));
//                     $user = User::findById($this->vipData->member_id);
//                     $user->getVipData(true)->removeVIP();

//                     if(!(EnvironmentService::isLocalOrTestMachine())) {
//                         //更新訂單 --正式
//                         Order::updateFunPointPayOrder($this->vipData->order_id);
//                     }

//                     \App\Models\VipLog::addToLog($user->id, 'Background auto cancel, last process date: ' . $lastProcessDate->format('Y-m-d'), '自動取消', 0, 0);
//                     $message = $user->name . "您好，您的 VIP 付費(卡號後四碼 " . $paymentData['card4no'] . ")已於 " . $lastProcessDate->format('Y 年 m 月') . " 扣款失敗，故停止您的 VIP 權限。優選會員資格一併取消，若有疑問請點右下聯絡我們連絡站長。";
//                     \App\Models\Message_new::post($admin->id, $user->id, $message);

//                     $str = '末四碼：' . $paymentData['card4no'] . "<br>" .
//                         "會員 ID：" . $this->vipData->member_id . "<br>" .
//                         "訂單編號：" . $this->vipData->order_id;
//                     \Mail::raw($str, function ($message) {
//                         $message->from('admin@sugar-garden.org', 'Sugar-garden');
//                         $message->to('admin@sugar-garden.org');
//                         $message->subject('FunPointPay 扣款失敗通知');
//                     });
//                 }
//             }
//         }
//         if($user) {            
//             $this->job_user = $user;
//         }
    }
}
