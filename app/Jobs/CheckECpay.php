<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

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
            $ecpay->ServiceURL = 'https://payment.ecpay.com.tw/Cashier/QueryCreditCardPeriodInfo';
            $ecpay->HashIV = Config::get('ecpay.payment'.$envStr.'.HashIV');
            $ecpay->HashKey = Config::get('ecpay.payment'.$envStr.'.HashKey');
            $ecpay->Query = [
                'MerchantTradeNo' => $this->vipData->order_id,
                'TimeStamp' => 	time()
            ];
            try{
                $paymentData = $ecpay->QueryPeriodCreditCardTradeInfo();
            }
            catch (\Exception $exception){
                Log::error($exception);
            }

            $last = last($paymentData['ExecLog']);
            $lastProcessDate = str_replace('%20', ' ', $last['process_date']);
            $lastProcessDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $lastProcessDate);
            $now = \Carbon\Carbon::now();
            if($last['RtnCode'] == 1 && $lastProcessDate->diffInDays($now) > 31){
                Log::info('付費失敗');
                Log::info($paymentData);
                $str = '末四碼：' . $paymentData['card4no'] . "<br>" .
                    "會員 ID：" . $this->vipData->member_id . "<br>" .
                    "訂單編號：" . $this->vipData->order_id;
                \Mail::raw($str, function ($message) {
                    $message->from('admin@sugar-garden.org', 'Sugar-garden');
                    $message->to('admin@sugar-garden.org');
                    $message->subject('綠界扣款失敗通知');
                });
            }
            else if($last['RtnCode'] == 0){
                Log::info('付費失敗');
                Log::info($paymentData);
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
