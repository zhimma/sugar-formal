<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\PaymentGetQrcodeLog;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class CheckECpay implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60;

    protected $vipData, $userIsVip;

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
        if(\App::environment('local')){
            $envStr = '_test';
        }
        else{
            $envStr = '';
        }
        if($this->vipData->business_id == Config::get('ecpay.payment'.$envStr.'.MerchantID') && substr($this->vipData->order_id,0,2) == 'SG'){
            $ecpay = new \App\Services\ECPay_AllInOne();
            $ecpay->MerchantID = Config::get('ecpay.payment'.$envStr.'.MerchantID');
//            $ecpay->ServiceURL = Config::get('ecpay.payment'.$envStr.'.ServiceURL');//定期定額查詢
            $ecpay->ServiceURL = Config::get('ecpay.payment'.$envStr.'.OrderQueryURL');//訂單查詢
            $ecpay->HashIV = Config::get('ecpay.payment'.$envStr.'.HashIV');
            $ecpay->HashKey = Config::get('ecpay.payment'.$envStr.'.HashKey');
            $ecpay->Query = [
                'MerchantTradeNo' => $this->vipData->order_id,
                'TimeStamp' => 	time()
            ];
            try{
                if(substr($this->vipData->payment,0,4) == 'one_'){ //保留用
                    //單次付費
                    $ecpay->ServiceURL = Config::get('ecpay.payment'.$envStr.'.OrderQueryURL');//訂單查詢
                    $paymentQueryData = $ecpay->QueryTradeInfo();
                    $ecpay->ServiceURL = Config::get('ecpay.payment'.$envStr.'.ServiceURL');//定期定額查詢
                    $paymentData = $ecpay->QueryPeriodCreditCardTradeInfo();
                    // 此函式會產生錯誤，經檢查應為無用函式
                }else {
                    //信用卡定期定額
                    $ecpay->ServiceURL = Config::get('ecpay.payment'.$envStr.'.ServiceURL');//定期定額查詢
                    $paymentData = $ecpay->QueryPeriodCreditCardTradeInfo();
                }
            }
            catch (\Exception $exception){
                Log::info("VIP id: " . $this->vipData->id);
                Log::info("VIP payment: " . $this->vipData->payment);
                Log::error($exception);
            }

            $now = \Carbon\Carbon::now();

            if(substr($this->vipData->payment,0,4) == 'one_'){
                //單次付款檢查
                    if (str_contains($paymentQueryData['PaymentType'], 'CVS') ||
                        str_contains($paymentQueryData['PaymentType'], 'ATM') ||
                        str_contains($paymentQueryData['PaymentType'], 'BARCODE')) {

                        $user = User::findById($this->vipData->member_id);
                        
                        if($this->userIsVip) {
                            //有賦予VIP者再檢查
                            //未完成交易時檢查
                            if ($paymentData['RtnCode'] != 10200047 && $paymentQueryData['TradeStatus'] != 1) {
                                //check取號資料表
                                $checkData = PaymentGetQrcodeLog::where('order_id', $this->vipData->order_id)->first();
                                if(isset($checkData)){
                                    if($now > $checkData->ExpireDate){
                                        //超過期限未完成交易
                                        //取消VIP
                                        $vipData = $user->getVipData(true);
                                        if($vipData){
                                            $vipData->removeVIP();
                                        }
                                        \App\Models\VipLog::addToLog($user->id, 'order_id: '.$this->vipData->order_id.'; 期限內('.$checkData->ExpireDate.')未完成付款：' . $paymentQueryData['PaymentType'], '自動取消', 0, 0);
                                    }
                                }
                            }

                        }else{
                            if ($paymentData['RtnCode'] == 10200047 && $paymentQueryData['TradeStatus'] == 1) {

                                $getOrderDate = Order::where('order_id', $this->vipData->order_id)->first();
                                if(isset($getOrderDate)) {
                                    \App\Models\Vip::select('member_id', 'active')
                                        ->where('member_id', $this->vipData->member_id)
                                        ->update(array('active' => 1, 'expiry' => $getOrderDate->order_expire_date));
                                    \App\Models\VipLog::addToLog($user->id, 'order_id: ' . $this->vipData->order_id . '; 繳款檢查正常回復VIP：' . $paymentQueryData['PaymentType'], '自動回復', 0, 0);
                                }

                            }
                        }
                    }


            }else { //定期定額流程
                try{
                    $last = last($paymentData['ExecLog']);
                }
                catch (\Exception $e){
                    Log::error("ExecLog is null, VIP id: " . $this->vipData->id);
                    return;
                }
                $lastProcessDate = str_replace('%20', ' ', $last['process_date']);
                $lastProcessDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $lastProcessDate);
                // 三個月一期或一個月一期
                try{
                    if(str_contains($this->vipData->payment, 'quarterly')){
                        $days = 94;
                    }
                    else{
                        $days = 31;
                    }
                }
                catch (\Throwable $e){
                    logger("CheckECpay null payment, user id: " . $this->vipData->member_id);
                    $days = 31;
                }

                //付款日期有差異時更新訂單
                $currentOrder = Order::where('order_id', $this->vipData->order_id)->first();
                if(isset($currentOrder)) {
                    $current_order_pay_date = last(json_decode($currentOrder->pay_date));
                    if ($last['RtnCode'] == 1 && $lastProcessDate != $current_order_pay_date[0]) {
                        Order::updateEcPayOrder($this->vipData->order_id);
                    }
                }else{
                    //資料表無此訂單時新增
                    //新增訂單
                    Order::addEcPayOrder($this->vipData->order_id);
                }

                // 最後一次付款成功，但已過期
                if ($last['RtnCode'] == 1 && $lastProcessDate->diffInDays($now) > $days && $this->userIsVip) {
                    Log::info('付費失敗');
                    Log::info($paymentData);

                    $admin = User::findByEmail(Config::get('social.admin.user-email'));
                    $user = User::findById($this->vipData->member_id);
                    if(!$user){
                        logger("Null user found, vip data id: " . $this->vipData->id);
                        return;
                    }
                    $vipData = $user->getVipData(true);
                    if($vipData){
                        $vipData->removeVIP();
                    }

                    if(!\App::environment('local')) {
                        //更新訂單 --正式綠界
                        Order::updateEcPayOrder($this->vipData->order_id);
                    }

                    \App\Models\VipLog::addToLog($user->id, 'Background auto cancel, last process date: ' . $lastProcessDate->format('Y-m-d'), '自動取消', 0, 0);
                    $message = $user->name . "您好，您的 VIP 付費(卡號後四碼 " . $paymentData['card4no'] . ")最後一次付費月份為 " . $lastProcessDate->format('Y 年 m 月') . " ，距今已逾一個月，故停止您的 VIP 權限。優選會員資格一併取消，若有疑問請點右下聯絡我們連絡站長。";
                    \App\Models\Message_new::post($admin->id, $user->id, $message);

                    $str = '末四碼：' . $paymentData['card4no'] . "<br>" .
                        "會員 ID：" . $this->vipData->member_id . "<br>" .
                        "訂單編號：" . $this->vipData->order_id;
                    \Mail::raw($str, function ($message) {
                        $message->from('admin@sugar-garden.org', 'Sugar-garden');
                        $message->to('admin@sugar-garden.org');
                        $message->subject('綠界扣款失敗通知');
                    });
                }
                // 非 VIP，檢查最後一次付款是否成功且在週期內
                else if (!$this->userIsVip && $last['RtnCode'] == 1 && $lastProcessDate->diffInDays($now) < $days) {
                    Log::info('VIP 回復');
                    Log::info($paymentData);

                    $admin = User::findByEmail(Config::get('social.admin.user-email'));
                    $user = User::findById($this->vipData->member_id);
                    \App\Models\Vip::select('member_id', 'active')
                        ->where('member_id', $this->vipData->member_id)
                        ->update(array('active' => 1, 'expiry' => '0000-00-00 00:00:00'));

                    if(!\App::environment('local')) {
                        //更新訂單 --正式綠界
                        Order::updateEcPayOrder($this->vipData->order_id);
                    }

                    \App\Models\VipLog::addToLog($user->id, 'Background auto upgrade, last process date: ' . $lastProcessDate->format('Y-m-d'), '自動回復', 0, 0);
                    $message = $user->name . "您好，由於您的 VIP 付費(卡號後四碼 " . $paymentData['card4no'] . ")曾因扣款失敗被停止 VIP 權限，但最近一次又再次付費成功，月份為 " . $lastProcessDate->format('Y 年 m 月') . "，故回復您的 VIP 權限。若有疑問請點右下聯絡我們連絡站長。";
                    \App\Models\Message_new::post($admin->id, $user->id, $message);

                    $str = '末四碼：' . $paymentData['card4no'] . "<br>" .
                        "會員 ID：" . $this->vipData->member_id . "<br>" .
                        "訂單編號：" . $this->vipData->order_id;
                    \Mail::raw($str, function ($message) {
                        $message->from('admin@sugar-garden.org', 'Sugar-garden');
                        $message->to('admin@sugar-garden.org');
                        $message->subject('VIP 回復通知');
                    });
                }
                // 最後一次付款失敗
                else if ($this->userIsVip && $last['RtnCode'] != 1) {
                    Log::info('付費失敗');
                    Log::info($paymentData);

                    $admin = User::findByEmail(Config::get('social.admin.user-email'));
                    $user = User::findById($this->vipData->member_id);
                    $user->getVipData(true)->removeVIP();

                    if(!\App::environment('local')) {
                        //更新訂單 --正式綠界
                        Order::updateEcPayOrder($this->vipData->order_id);
                    }

                    \App\Models\VipLog::addToLog($user->id, 'Background auto cancel, last process date: ' . $lastProcessDate->format('Y-m-d'), '自動取消', 0, 0);
                    $message = $user->name . "您好，您的 VIP 付費(卡號後四碼 " . $paymentData['card4no'] . ")已於 " . $lastProcessDate->format('Y 年 m 月') . " 扣款失敗，故停止您的 VIP 權限。優選會員資格一併取消，若有疑問請點右下聯絡我們連絡站長。";
                    \App\Models\Message_new::post($admin->id, $user->id, $message);

                    $str = '末四碼：' . $paymentData['card4no'] . "<br>" .
                        "會員 ID：" . $this->vipData->member_id . "<br>" .
                        "訂單編號：" . $this->vipData->order_id;
                    \Mail::raw($str, function ($message) {
                        $message->from('admin@sugar-garden.org', 'Sugar-garden');
                        $message->to('admin@sugar-garden.org');
                        $message->subject('綠界扣款失敗通知');
                    });
                }
            }
        }
    }
}
