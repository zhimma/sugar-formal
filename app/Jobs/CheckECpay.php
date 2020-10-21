<?php

namespace App\Jobs;

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

    protected $vipData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($vipData)
    {
        //
        $this->vipData = $vipData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        if(env('APP_ENV') == 'local'){
            $envStr = '_test';
        }
        else{
            $envStr = '';
        }
        if($this->vipData->business_id == Config::get('ecpay.payment'.$envStr.'.MerchantID')){
            $ecpay = new \App\Services\ECPay_AllInOne();
            $ecpay->MerchantID = Config::get('ecpay.payment'.$envStr.'.MerchantID');
            $ecpay->ServiceURL = Config::get('ecpay.payment'.$envStr.'.ServiceURL');//定期定額查詢
            $ecpay->HashIV = Config::get('ecpay.payment'.$envStr.'.HashIV');
            $ecpay->HashKey = Config::get('ecpay.payment'.$envStr.'.HashKey');
            $ecpay->Query = [
                'MerchantTradeNo' => $this->vipData->order_id,
                'TimeStamp' => 	time()
            ];
            try{
                if(substr($this->vipData->payment,0,4) == 'one_'){ //保留用
                    // $paymentData = $ecpay->QueryTradeInfo();
                    // 此函式會產生錯誤，經檢查應為無用函式
                }else {
                    $paymentData = $ecpay->QueryPeriodCreditCardTradeInfo(); //信用卡定期定額
                }
            }
            catch (\Exception $exception){
                Log::info("VIP id: " . $this->vipData->id);
                Log::info("VIP payment: " . $this->vipData->payment);
                Log::error($exception);
            }

            if(substr($this->vipData->payment,0,4) == 'one_'){
                //保留用
            }else { //定期定額流程
                try{
                    $last = last($paymentData['ExecLog']);
                }
                catch (\Exception $e){
                    Log::error("ExecLog is null, VIP id: " . $this->vipData->id);
                }
                $lastProcessDate = str_replace('%20', ' ', $last['process_date']);
                $lastProcessDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $lastProcessDate);
                $now = \Carbon\Carbon::now();
                if ($last['RtnCode'] == 1 && $lastProcessDate->diffInDays($now) > 31) {
                    Log::info('付費失敗');
                    Log::info($paymentData);

                    $admin = User::findByEmail(Config::get('social.admin.email'));
                    $user = User::findById($this->vipData->member_id);
                    $user->getVipData(true)->removeVIP();
                    \App\Models\VipLog::addToLog($user->id, 'Auto cancel', '自動取消', 0, 0);
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
                } else if ($last['RtnCode'] != 1) {
                    Log::info('付費失敗');
                    Log::info($paymentData);

                    $admin = User::findByEmail(Config::get('social.admin.email'));
                    $user = User::findById($this->vipData->member_id);
                    $user->getVipData(true)->removeVIP();
                    \App\Models\VipLog::addToLog($user->id, 'Auto cancel', '自動取消', 0, 0);
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
