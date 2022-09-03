<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ValueAddedService;
use App\Services\EnvironmentService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class PaymentService
{
    public function caculatesRefund($type)
    {
        $refund = 0;

        //vvip72小時內未匯款退費
        if($type == 'vvip_without_remittance')
        {
            $refund = 9888-4000;
        }

        //vip依照剩餘天數退費
        if($type == 'vip_refund')
        {
            $user = auth()->user();
            $vip = $user->vip->first();

            if(EnvironmentService::isLocalOrTestMachine()){
                $envStr = '_test';
            }
            else{
                $envStr = '';
            }
            if(substr($vip->payment,0,3) == 'cc_' && $vip->business_id == Config::get('ecpay.payment'.$envStr.'.MerchantID')){
                $ecpay = new \App\Services\ECPay_AllInOne();
                $ecpay->MerchantID = Config::get('ecpay.payment'.$envStr.'.MerchantID');
                $ecpay->ServiceURL = Config::get('ecpay.payment'.$envStr.'.ServiceURL');//定期定額查詢
                $ecpay->HashIV = Config::get('ecpay.payment'.$envStr.'.HashIV');
                $ecpay->HashKey = Config::get('ecpay.payment'.$envStr.'.HashKey');
                $ecpay->Query = [
                    'MerchantTradeNo' => $vip->order_id,
                    'TimeStamp' => 	time()
                ];
                $paymentData = $ecpay->QueryPeriodCreditCardTradeInfo(); //信用卡定期定額
                $last = last($paymentData['ExecLog']);
                $lastProcessDate_o='';
                if($last['RtnCode']==1) {
                    $lastProcessDate = str_replace('%20', ' ', $last['process_date']);
                    $lastProcessDate_o = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $lastProcessDate);
                    $lastProcessDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $lastProcessDate);

                    //計算下次扣款日
                    if($vip->payment == 'cc_quarterly_payment'){
                        $periodRemained = 92;
                    }else {
                        $periodRemained = 30;
                    }
                    $current_vip_days = $lastProcessDate_o->diffInDays(Carbon::now());
                    $current_vip_remain_days = $periodRemained - $current_vip_days;
                    $refund = ( $paymentData['amount'] / $periodRemained ) * $current_vip_remain_days;
                }
            }else{
                if($vip->payment=='one_month_payment'){
                    $used_vipDays = $vip->updated_at->diffInDays(Carbon::now());
                    $current_vip_remain_days = Carbon::now()->diffInDays($vip->expiry) - $used_vipDays;
                    $refund = ( $vip->amount / 30 ) * $current_vip_remain_days;
                }elseif($vip->payment=='one_quarter_payment'){
                    $current_vip_remain_days = Carbon::now()->diffInDays($vip->expiry);
                    $refund = ( $vip->amount / 92 ) * $current_vip_remain_days;
                }
            }
        }

        return $refund;
    }
}