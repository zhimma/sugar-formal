<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class CheckECpayForValueAddedService implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60;

    protected $valueAddedServiceData;

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
        if(\App::environment('local')){
            $envStr = '_test';
        }
        else{
            $envStr = '';
        }
        if($this->valueAddedServiceData->business_id == Config::get('ecpay.payment'.$envStr.'.MerchantID')){
            $ecpay = new \App\Services\ECPay_AllInOne();
            $ecpay->MerchantID = Config::get('ecpay.payment'.$envStr.'.MerchantID');
            $ecpay->ServiceURL = Config::get('ecpay.payment'.$envStr.'.ServiceURL');//定期定額查詢
            $ecpay->HashIV = Config::get('ecpay.payment'.$envStr.'.HashIV');
            $ecpay->HashKey = Config::get('ecpay.payment'.$envStr.'.HashKey');
            $ecpay->Query = [
                'MerchantTradeNo' => $this->valueAddedServiceData->order_id,
                'TimeStamp' => 	time()
            ];
            try{
                if(substr($this->valueAddedServiceData->payment,0,4) == 'one_'){ //保留用
                    // $paymentData = $ecpay->QueryTradeInfo();
                    // 此函式會產生錯誤，經檢查應為無用函式
                }else {
                    $paymentData = $ecpay->QueryPeriodCreditCardTradeInfo(); //信用卡定期定額
                }
            }
            catch (\Exception $exception){
                Log::info("valueAddedService id: " . $this->valueAddedServiceData->id);
                Log::info("valueAddedService payment: " . $this->valueAddedServiceData->payment);
                Log::error($exception);
            }

            if(substr($this->valueAddedServiceData->payment,0,4) == 'one_'){
                //保留用
            }else { //定期定額流程
                try{
                    $last = last($paymentData['ExecLog']);
                }
                catch (\Exception $e){
                    Log::error("ExecLog is null, valueAddedServiceData id: " . $this->valueAddedServiceData->id);
                    return;
                }
                $lastProcessDate = str_replace('%20', ' ', $last['process_date']);
                $lastProcessDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $lastProcessDate);
                // 三個月一期或一個月一期
                if(str_contains($this->valueAddedServiceData->payment, 'quarterly')){
                    $days = 94;
                }
                else{
                    $days = 31;
                }
                $now = \Carbon\Carbon::now();

                //付款日期有差異時更新訂單
                $currentOrder = Order::where('order_id', $this->valueAddedServiceData->order_id)->first();
                if(isset($currentOrder)) {
                    $current_order_pay_date = last(json_decode($currentOrder->pay_date));
                    if ($last['RtnCode'] == 1 && $lastProcessDate != $current_order_pay_date[0]) {
                        Order::updateEcPayOrder($this->valueAddedServiceData->order_id);
                    }
                }else{
                    //資料表無此訂單時新增
                    //新增訂單
                    Order::addEcPayOrder($this->valueAddedServiceData->order_id);
                }

                if ($last['RtnCode'] == 1 && $lastProcessDate->diffInDays($now) > $days) {
                    Log::info('付費失敗');
                    Log::info($paymentData);

                    $admin = User::findByEmail(Config::get('social.admin.email'));
                    $user = User::findById($this->valueAddedServiceData->member_id);
                    \App\Models\ValueAddedService::removeValueAddedService($user->id, $this->valueAddedServiceData->service_name);

                    //更新訂單 --正式綠界
                    Order::updateEcPayOrder($this->valueAddedServiceData->order_id);

                    \App\Models\ValueAddedServiceLog::addToLog($user->id, $this->valueAddedServiceData->service_name,'Auto cancel', $this->valueAddedServiceData->order_id, $this->valueAddedServiceData->txn_id,0);
                    if($this->valueAddedServiceData->service_name=='hideOnline'){
                        $service_name_tw = '隱藏付費';
                    }
                    $message = $user->name . "您好，您的 ".$service_name_tw." (卡號後四碼 " . $paymentData['card4no'] . ")最後一次付費月份為 " . $lastProcessDate->format('Y 年 m 月') . " ，距今已逾一個月，故停止您的 ".$service_name_tw." 權限。若有疑問請點右下聯絡我們連絡站長。";
                    \App\Models\Message::post($admin->id, $user->id, $message,true, 1);

                    $str = '末四碼：' . $paymentData['card4no'] . "<br>" .
                        "會員 ID：" . $this->valueAddedServiceData->member_id . "<br>" .
                        "訂單編號：" . $this->valueAddedServiceData->order_id;
                    \Mail::raw($str, function ($message) {
                        $message->from('admin@sugar-garden.org', 'Sugar-garden');
                        $message->to('admin@sugar-garden.org');
                        $message->subject('綠界扣款失敗通知');
                    });
                } else if ($last['RtnCode'] != 1) {
                    Log::info('付費失敗');
                    Log::info($paymentData);

                    $admin = User::findByEmail(Config::get('social.admin.email'));
                    $user = User::findById($this->valueAddedServiceData->member_id);
                    \App\Models\ValueAddedService::removeValueAddedService($user->id, $this->valueAddedServiceData->service_name);

                    //更新訂單 --正式綠界
                    Order::updateEcPayOrder($this->valueAddedServiceData->order_id);

                    \App\Models\ValueAddedServiceLog::addToLog($user->id, $this->valueAddedServiceData->service_name,'Auto cancel', $this->valueAddedServiceData->order_id, $this->valueAddedServiceData->txn_id,0);
                    if($this->valueAddedServiceData->service_name=='hideOnline'){
                        $service_name_tw = '隱藏付費';
                    }
                    $message = $user->name . "您好，您的 ".$service_name_tw." (卡號後四碼 " . $paymentData['card4no'] . ")已於 " . $lastProcessDate->format('Y 年 m 月') . " 扣款失敗，故停止您的 ".$service_name_tw." 權限。若有疑問請點右下聯絡我們連絡站長。";
                    \App\Models\Message::post($admin->id, $user->id, $message,true, 1);

                    $str = '末四碼：' . $paymentData['card4no'] . "<br>" .
                        "會員 ID：" . $this->valueAddedServiceData->member_id . "<br>" .
                        "訂單編號：" . $this->valueAddedServiceData->order_id;
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
