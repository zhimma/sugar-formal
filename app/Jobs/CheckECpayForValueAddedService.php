<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\OrderPayFailNotify;
use App\Models\ValueAddedService;
use App\Models\ValueAddedServiceLog;
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
use romanzipp\QueueMonitor\Traits\IsMonitored;

class CheckECpayForValueAddedService implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, IsMonitored;

    public $timeout = 60;

    protected $valueAddedServiceData, $job_user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($valueAddedServiceData)
    {
        //
        $this->valueAddedServiceData = $valueAddedServiceData;
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

         if( ($this->valueAddedServiceData->business_id == Config::get('ecpay.payment'.$envStr.'.MerchantID') || $this->valueAddedServiceData->business_id == Config::get('funpoint.payment'.$envStr.'.MerchantID'))
             && substr($this->valueAddedServiceData->order_id,0,2) == 'SG') {

             $user = User::findById($this->valueAddedServiceData->member_id);
             if (!$user) {
                 logger("Null user found, valueAddedService data id: " . $this->valueAddedServiceData->id);
                 return;
             }

             $now = Carbon::now();
             $OrderDataCheck = null;
             $admin = User::findByEmail(Config::get('social.admin.user-email'));

             $OrderData = Order::findByOrderId($this->valueAddedServiceData->order_id);

             if ($OrderData) {
                 //定期定額 未過期訂單
                 if (substr($this->valueAddedServiceData->payment, 0, 3) == 'cc_' && $OrderData->order_expire_date == '') {
                     if ($this->valueAddedServiceData->payment == 'cc_quarterly_payment') {
                         $periodRemained = 92;
                     } else {
                         $periodRemained = 30;
                     }

                     //取本機訂單最後扣款日
                     $lastProcessDate = last(json_decode($OrderData->pay_date));
                     $theActualLastProcessDate = is_string($lastProcessDate[0]) ? Carbon::parse($lastProcessDate[0]) : $lastProcessDate[0];

                     //本機訂單最後扣款日至今天數已超過下次扣款天數 && 扣款日期已過
                     if ($now->diffInDays($theActualLastProcessDate) > $periodRemained || $OrderData->ExecStatus == '') {
                         if (!(EnvironmentService::isLocalOrTestMachine())) {
                             try {
                                 //更新訂單 by payment_flow
                                 if ($OrderData->payment_flow == 'ecpay') {
                                     $updateEcPayOrder = Order::updateEcPayOrder($this->valueAddedServiceData->order_id);
                                     if ($updateEcPayOrder) {
                                         //重新查詢訂單並檢查
                                         $OrderDataCheck = Order::findByOrderId($this->valueAddedServiceData->order_id);
                                     }
                                 } elseif ($OrderData->payment_flow == 'funpoint') {
                                     $updateFunPointPayOrder = Order::updateFunPointPayOrder($this->valueAddedServiceData->order_id);
                                     if ($updateFunPointPayOrder) {
                                         //重新查詢訂單並檢查
                                         $OrderDataCheck = Order::findByOrderId($this->valueAddedServiceData->order_id);
                                     }
                                 }
                             } catch (\Exception $exception) {
                                 Log::info("valueAddedService id: " . $this->valueAddedServiceData->id . "；order_id: " . $this->valueAddedServiceData->order_id . "：訂單更新失敗");
                                 Log::error($exception);
                             }
                         }
                     }
                 }
                 //定期定額 有到期日訂單
                 elseif (substr($this->valueAddedServiceData->payment, 0, 3) == 'cc_' && $OrderData->order_expire_date != '') {
                     //檢查到期日日否正確
                     $order_expire_date = Carbon::parse($OrderData->order_expire_date);
                     //取本機訂單最後扣款日
                     $lastProcessDate = last(json_decode($OrderData->pay_date));
                     $theActualLastProcessDate = is_string($lastProcessDate[0]) ? Carbon::parse($lastProcessDate[0]) : $lastProcessDate[0];
                     $periodDays = $theActualLastProcessDate->diffInDays($order_expire_date);
                     if ($this->valueAddedServiceData->payment == 'cc_quarterly_payment') {
                         $periodRemained = 92;
                     } else {
                         $periodRemained = 30;
                     }

                     if($periodDays > $periodRemained || $OrderData->ExecStatus == ''){
                         if (!(EnvironmentService::isLocalOrTestMachine())) {
                             try {
                                 //更新訂單 by payment_flow
                                 if ($OrderData->payment_flow == 'ecpay') {
                                     $updateEcPayOrder = Order::updateEcPayOrder($this->valueAddedServiceData->order_id);
                                     if ($updateEcPayOrder) {
                                         //重新查詢訂單並檢查
                                         $OrderDataCheck = Order::findByOrderId($this->valueAddedServiceData->order_id);
                                     }
                                 } elseif ($OrderData->payment_flow == 'funpoint') {
                                     $updateFunPointPayOrder = Order::updateFunPointPayOrder($this->valueAddedServiceData->order_id);
                                     if ($updateFunPointPayOrder) {
                                         //重新查詢訂單並檢查
                                         $OrderDataCheck = Order::findByOrderId($this->valueAddedServiceData->order_id);
                                     }
                                 }
                             } catch (\Exception $exception) {
                                 Log::info("valueAddedService id: " . $this->valueAddedServiceData->id . "；order_id: " . $this->valueAddedServiceData->order_id . "：訂單更新失敗");
                                 Log::error($exception);
                             }
                         }
                     }else {
                         $OrderDataCheck = $OrderData;
                     }
                 }
             } //Order無訂單資料時 從金流新增訂單
             else {
                 if (!(EnvironmentService::isLocalOrTestMachine())) {
                     try {
                         //從ecPay
                         $addOrder = Order::addEcPayOrder($this->valueAddedServiceData->order_id);
                         if (!$addOrder) {
                             //從funPoint
                             $addOrder = Order::addFunPointPayOrder($this->valueAddedServiceData->order_id);
                         }
                         //重新抓訂單
                         if ($addOrder) {
                             $OrderDataCheck = Order::findByOrderId($this->valueAddedServiceData->order_id);
                         }
                     } catch (\Exception $exception) {
                         Log::info("valueAddedService id: " . $this->valueAddedServiceData->id . "；order_id: " . $this->valueAddedServiceData->order_id . "：查無訂單");
                         Log::error($exception);
                     }
                 }

             }

             //依到期日與否進行檢查 OrderDataCheck
             if ($OrderDataCheck) {
                 $valueAddedServiceData = ValueAddedService::where('service_name', $OrderDataCheck->service_name)
                     ->where('member_id', $this->valueAddedServiceData->member_id)
                     ->where('active', 1)
                     ->orderBy('created_at', 'desc')
                     ->first();
                 //有到期日
                 if ($OrderDataCheck->order_expire_date != '') {
                     //檢查過期
                     if ($now->gt($OrderDataCheck->order_expire_date) && $now->gt($this->valueAddedServiceData->expiry)) {
                         //取消 [service_name] 防呆處理
                         if ($valueAddedServiceData) {
                             ValueAddedService::removeValueAddedService($valueAddedServiceData->member_id, $valueAddedServiceData->service_name);
                             ValueAddedServiceLog::addToLog($user->id, $this->valueAddedServiceData->service_name, 'Auto cancel, order expire date: ' . $OrderDataCheck->order_expire_date, $this->valueAddedServiceData->order_id, $this->valueAddedServiceData->txn_id, 0);
                         }

                     } //檢查尚未過期
                     elseif (!$valueAddedServiceData && Carbon::parse($OrderDataCheck->order_expire_date)->gte($now)) {
                         if ($OrderDataCheck->service_name == 'hideOnline') {
                             Log::info('隱藏付費 hideOnline 回復');
                         }

                         if ($OrderDataCheck->service_name == 'VVIP') {
                             Log::info('VVIP 回復');
                         }

                         $expiryDate = $OrderDataCheck->order_expire_date;
                         if (Carbon::parse($this->valueAddedServiceData->expiry)->gt($OrderDataCheck->order_expire_date)) {
                             $expiryDate = $this->valueAddedServiceData->expiry;
                         }

                         ValueAddedServiceLog::addToLog($user->id, $this->valueAddedServiceData->service_name, 'Auto upgrade, order expire date: ' . $OrderDataCheck->order_expire_date . ' 尚未到期，自動回復', $this->valueAddedServiceData->order_id, $this->valueAddedServiceData->txn_id, 0);
                         ValueAddedService::where('member_id', $this->valueAddedServiceData->member_id)
                             ->where('servive_name', $this->valueAddedServiceData->service_name)
                             ->update(array('active' => 1, 'expiry' => $expiryDate));

                     }
                 }
                 //訂單尚無到期日
                 else {
                     if ($OrderDataCheck->payment == 'cc_quarterly_payment') {
                         $periodRemained = 92;
                     } else {
                         $periodRemained = 30;
                     }

                     $lastProcessDate = last(json_decode($OrderDataCheck->pay_date));
                     $theActualLastProcessDate = is_string($lastProcessDate[0]) ? Carbon::parse($lastProcessDate[0]) : $lastProcessDate[0];

                     //最後一次付款成功，但已過期
                     //等同金流最後一次扣款失敗 但訂單不會抓失敗的日期 故一併判斷為扣款失敗
                     //檢查付款日 應付日但未付時判斷
                     if ($valueAddedServiceData && $theActualLastProcessDate->diffInDays($now) > $periodRemained) {
                         Log::info($OrderDataCheck->payment->service_name . ' 付費失敗');
                         Log::info($OrderDataCheck);
                         ValueAddedService::removeValueAddedService($valueAddedServiceData->member_id, $valueAddedServiceData->service_name);
                         ValueAddedServiceLog::addToLog($user->id, $this->valueAddedServiceData->service_name, 'Auto cancel, last process date: ' . $theActualLastProcessDate->format('Y-m-d') . ' 自動取消', $this->valueAddedServiceData->order_id, $this->valueAddedServiceData->txn_id, 0);

                         if ($this->valueAddedServiceData->service_name == 'hideOnline') {
                             $service_name = '隱藏付費';
                         } else {
                             $service_name = $this->valueAddedServiceData->service_name;
                         }
                         $message = $user->name . "您好，您的 '.$service_name.'付費(卡號後四碼 " . $OrderDataCheck->card4no . ")最後一次付費月份為 " . $theActualLastProcessDate->format('Y 年 m 月') . " ，距今已逾一個月或扣款失敗，故停止您的 '.$service_name.' 權限。若有疑問請點右下聯絡我們連絡站長。";
                         \App\Models\Message_new::post($admin->id, $user->id, $message);

                         $str = '末四碼：' . $OrderDataCheck->card4no . "<br>" .
                             "會員 ID：" . $this->valueAddedServiceData->member_id . "<br>" .
                             "訂單編號：" . $this->valueAddedServiceData->order_id . "<br>" .
                             "服務項目：" . $service_name . "<br>" .
                             "金流平台：" . $OrderDataCheck->payment_flow;
                         \Mail::raw($str, function ($message, $service_name) {
                             $message->from('admin@sugar-garden.org', 'Sugar-garden');
                             $message->to('admin@sugar-garden.org');
                             $message->subject($service_name . '扣款失敗通知');
                         });

                         //寫入訂單付款失敗通知紀錄 OrderPayFailNotify for VVIP
                         if($service_name == 'VVIP' && $OrderDataCheck->pay_fail != ''){
                             $lastPayFailDate = last(json_decode($OrderDataCheck->pay_fail));
                             $theActualLastPayFailDate = is_string($lastPayFailDate[0]) ? Carbon::parse($lastPayFailDate[0]) : $lastPayFailDate[0];
                             if(!OrderPayFailNotify::isExists($this->valueAddedServiceData->member_id, $this->valueAddedServiceData->order_id, $theActualLastPayFailDate)){
                                 OrderPayFailNotify::addToData($this->valueAddedServiceData->member_id, $this->valueAddedServiceData->order_id, $theActualLastPayFailDate);
                             }
                         }
                     }
                     //最後扣款日尚未到期
                     elseif (!$valueAddedServiceData && $theActualLastProcessDate->diffInDays($now) < $periodRemained) {
                         if ($OrderDataCheck->service_name == 'hideOnline') {
                             Log::info('隱藏付費 hideOnline 回復');
                         }
                         if ($OrderDataCheck->service_name == 'VVIP') {
                             Log::info('VVIP 回復');
                         }

                         Log::info($OrderDataCheck);
                         ValueAddedService::where('member_id', $this->valueAddedServiceData->member_id)
                             ->where('servive_name', $this->valueAddedServiceData->service_name)
                             ->update(array('active' => 1, 'expiry' => '0000-00-00 00:00:00'));
                         ValueAddedServiceLog::addToLog($user->id, $this->valueAddedServiceData->service_name, 'Auto upgrade, last process date:: ' . $theActualLastProcessDate->format('Y-m-d') . ' 尚未到期，自動回復', $this->valueAddedServiceData->order_id, $this->valueAddedServiceData->txn_id, 0);

                         if ($this->valueAddedServiceData->service_name == 'hideOnline') {
                             $service_name = '隱藏付費';
                         } else {
                             $service_name = $this->valueAddedServiceData->service_name;
                         }
                         $message = $user->name . "您好，由於您的 '.$service_name.' 付費(卡號後四碼 " . $OrderDataCheck->card4no . ")曾因扣款失敗被停止 '.$service_name.' 權限，但最近一次又再次付費成功，月份為 " . $theActualLastProcessDate->format('Y 年 m 月') . "，故回復您的 '.$service_name.' 權限。若有疑問請點右下聯絡我們連絡站長。";
                         \App\Models\Message_new::post($admin->id, $user->id, $message);
                         $str = '末四碼：' . $OrderDataCheck->card4no . "<br>" .
                             "會員 ID：" . $this->valueAddedServiceData->member_id . "<br>" .
                             "服務項目：" . $service_name . "<br>" .
                             "訂單編號：" . $this->valueAddedServiceData->order_id;
                         \Mail::raw($str, function ($message, $service_name) {
                             $message->from('admin@sugar-garden.org', 'Sugar-garden');
                             $message->to('admin@sugar-garden.org');
                             $message->subject($service_name . ' 回復通知');
                         });
                     }

                 }
             }

         }


        // if($this->valueAddedServiceData->business_id == Config::get('ecpay.payment'.$envStr.'.MerchantID')){
        //     $ecpay = new \App\Services\ECPay_AllInOne();
        //     $ecpay->MerchantID = Config::get('ecpay.payment'.$envStr.'.MerchantID');
        //     $ecpay->ServiceURL = Config::get('ecpay.payment'.$envStr.'.ServiceURL');//定期定額查詢
        //     $ecpay->HashIV = Config::get('ecpay.payment'.$envStr.'.HashIV');
        //     $ecpay->HashKey = Config::get('ecpay.payment'.$envStr.'.HashKey');
        //     $ecpay->Query = [
        //         'MerchantTradeNo' => $this->valueAddedServiceData->order_id,
        //         'TimeStamp' => 	time()
        //     ];
        //     try{
        //         if(substr($this->valueAddedServiceData->payment,0,4) == 'one_'){ //保留用
        //             // $paymentData = $ecpay->QueryTradeInfo();
        //             // 此函式會產生錯誤，經檢查應為無用函式
        //         }else {
        //             $paymentData = $ecpay->QueryPeriodCreditCardTradeInfo(); //信用卡定期定額
        //         }
        //     }
        //     catch (\Exception $exception){
        //         Log::info("valueAddedService id: " . $this->valueAddedServiceData->id);
        //         Log::info("valueAddedService payment: " . $this->valueAddedServiceData->payment);
        //         Log::error($exception);
        //     }

        //     $user = null;

        //     if(substr($this->valueAddedServiceData->payment,0,4) == 'one_'){
        //         //保留用
        //     }else { //定期定額流程
        //         try{
        //             $last = last($paymentData['ExecLog']);
        //         }
        //         catch (\Exception $e){
        //             Log::error("ExecLog is null, valueAddedServiceData id: " . $this->valueAddedServiceData->id);
        //             return;
        //         }
        //         $lastProcessDate = str_replace('%20', ' ', $last['process_date']);
        //         $lastProcessDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $lastProcessDate);
        //         // 三個月一期或一個月一期
        //         if(str_contains($this->valueAddedServiceData->payment, 'quarterly')){
        //             $days = 94;
        //         }
        //         else{
        //             $days = 31;
        //         }
        //         $now = \Carbon\Carbon::now();

        //         //付款日期有差異時更新訂單
        //         $currentOrder = Order::where('order_id', $this->valueAddedServiceData->order_id)->first();
        //         if(isset($currentOrder)) {
        //             $current_order_pay_date = last(json_decode($currentOrder->pay_date));
        //             if ($last['RtnCode'] == 1 && $lastProcessDate != $current_order_pay_date[0]) {
        //                 Order::updateEcPayOrder($this->valueAddedServiceData->order_id);
        //             }
        //         }else{
        //             //資料表無此訂單時新增
        //             //新增訂單
        //             Order::addEcPayOrder($this->valueAddedServiceData->order_id);
        //         }

        //         if ($last['RtnCode'] == 1 && $lastProcessDate->diffInDays($now) > $days) {
        //             Log::info('付費失敗');
        //             Log::info($paymentData);

        //             $admin = User::findByEmail(Config::get('social.admin.user-email'));
        //             $user = User::findById($this->valueAddedServiceData->member_id);
        //             \App\Models\ValueAddedService::removeValueAddedService($user->id, $this->valueAddedServiceData->service_name);

        //             //更新訂單 --正式綠界
        //             Order::updateEcPayOrder($this->valueAddedServiceData->order_id);

        //             \App\Models\ValueAddedServiceLog::addToLog($user->id, $this->valueAddedServiceData->service_name,'Auto cancel', $this->valueAddedServiceData->order_id, $this->valueAddedServiceData->txn_id,0);
        //             if($this->valueAddedServiceData->service_name=='hideOnline'){
        //                 $service_name_tw = '隱藏付費';
        //             }
        //             $message = $user->name . "您好，您的 ".$service_name_tw." (卡號後四碼 " . $paymentData['card4no'] . ")最後一次付費月份為 " . $lastProcessDate->format('Y 年 m 月') . " ，距今已逾一個月，故停止您的 ".$service_name_tw." 權限。若有疑問請點右下聯絡我們連絡站長。";
        //             \App\Models\Message::post($admin->id, $user->id, $message,true, 1);

        //             $str = '末四碼：' . $paymentData['card4no'] . "<br>" .
        //                 "會員 ID：" . $this->valueAddedServiceData->member_id . "<br>" .
        //                 "訂單編號：" . $this->valueAddedServiceData->order_id;
        //             \Mail::raw($str, function ($message) {
        //                 $message->from('admin@sugar-garden.org', 'Sugar-garden');
        //                 $message->to('admin@sugar-garden.org');
        //                 $message->subject('綠界扣款失敗通知');
        //             });
        //         } else if ($last['RtnCode'] != 1) {
        //             Log::info('付費失敗');
        //             Log::info($paymentData);

        //             $admin = User::findByEmail(Config::get('social.admin.user-email'));
        //             $user = User::findById($this->valueAddedServiceData->member_id);
        //             \App\Models\ValueAddedService::removeValueAddedService($user->id, $this->valueAddedServiceData->service_name);

        //             //更新訂單 --正式綠界
        //             Order::updateEcPayOrder($this->valueAddedServiceData->order_id);

        //             \App\Models\ValueAddedServiceLog::addToLog($user->id, $this->valueAddedServiceData->service_name,'Auto cancel', $this->valueAddedServiceData->order_id, $this->valueAddedServiceData->txn_id,0);
        //             if($this->valueAddedServiceData->service_name=='hideOnline'){
        //                 $service_name_tw = '隱藏付費';
        //             }
        //             $message = $user->name . "您好，您的 ".$service_name_tw." (卡號後四碼 " . $paymentData['card4no'] . ")已於 " . $lastProcessDate->format('Y 年 m 月') . " 扣款失敗，故停止您的 ".$service_name_tw." 權限。若有疑問請點右下聯絡我們連絡站長。";
        //             \App\Models\Message::post($admin->id, $user->id, $message,true, 1);

        //             $str = '末四碼：' . $paymentData['card4no'] . "<br>" .
        //                 "會員 ID：" . $this->valueAddedServiceData->member_id . "<br>" .
        //                 "訂單編號：" . $this->valueAddedServiceData->order_id;
        //             \Mail::raw($str, function ($message) {
        //                 $message->from('admin@sugar-garden.org', 'Sugar-garden');
        //                 $message->to('admin@sugar-garden.org');
        //                 $message->subject('綠界扣款失敗通知');
        //             });
        //         }
        //     }

        //     if($user) {                
        //         $this->job_user = $user;
        //     }
        // }
    }
}
