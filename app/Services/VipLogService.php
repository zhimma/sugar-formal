<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;


class VipLogService {

    private $user_id;           // 使用者id
    private $business_id;       // 商家編號
    private $order_id;          // 訂單編號
    private $amount;            // 交易金額
    private $action;            // 動作
    private $status;            // 狀態
    private $mode;              // 交易模式

    public function __construct() {

    }

    public function upgradeLog($payload, $user_id) {
        $this->user_id = $user_id;
        $this->business_id = $payload['P_MerchantNumber'];
        $this->order_id = $payload['P_OrderNumber'];
        $this->amount = $payload['P_Amount'];
        $this->action = 'New';
        $this->status = '01';
        $this->mode = 0;
    }

    public function upgradeLogEC($payload, $user_id) {
        $this->user_id = $user_id;
        $this->business_id = $payload['MerchantID'];
        $this->order_id = $payload['MerchantTradeNo'];
        $this->amount = $payload['TradeAmt'];
        $this->action = 'New';
        $this->status = '01';
        $this->mode = 0;
    }

    public function upgradeLog_esafe($payload, $user_id) {
        $this->user_id = $user_id;
        $this->business_id = $payload['web'];
        $this->order_id = $payload['buysafeno'];
        $this->amount = $payload['MN'];
        $this->action = 'New';
        $this->status = '01';
        $this->mode = 0;
    }

    public function cancelLog($user) {
        $this->user_id = $user->member_id;
        $this->business_id = $user->business_id;
        $this->order_id = $user->order_id;
        $this->amount = $user->amount;
        $this->action = 'Delete';
        $this->status = '01';
        $this->mode = 0;
    }

    public function cancelLogForOrder($user) {
        $this->user_id = $user->user_id;
        $this->business_id = $user->business_id;
        $this->order_id = $user->order_id;
        $this->amount = $user->amount;
        $this->action = 'Delete';
        $this->status = '01';
        $this->mode = 0;
    }

    public function writeLogToFile() {
        if(Carbon::now()->format('d') <= 28){
            $today = Carbon::now()->format('d');
            $fileName = 'RP_'. $this->business_id . '_' . Carbon::now()->format('Ymd') .'.dat';
        }
        else{
            $today = '28';
            // addMonthsNoOverflow(): 避免如 10/31 加了一個月後變 12/01 的情形出現
            $fileName = 'RP_'. $this->business_id . '_' . Carbon::now()->addMonthsNoOverflow(1)->startOfMonth()->format('Ymd') .'.dat';
        }
        $fileContent = $this->business_id . ',' . $this->user_id . ',' . $this->order_id . ',,,' . intval($this->amount) . ',' . $today . ',' . $this->action . ',' . $this->status . ',' . $this->mode;

        Storage::disk('local')->append($fileName, $fileContent);
        return array($fileName, $fileContent);
    }

    public function customLogToFile($user_id, $order_id, $day, $action){
        if(Carbon::now()->format('d') <= 28){
            $fileDate = \Carbon\Carbon::now();
        }
        else{
            $fileDate = \Carbon\Carbon::now()->format('Ym').'28';
        }
        $fileName = 'RP_761404_'. $fileDate->format('Ymd') .'.dat';
        $fileContent = '761404,' . $user_id . ',' . $order_id . ',,,888,' . $day . ',' . $action . ',01,0';

        return Storage::disk('local')->append($fileName, $fileContent);
    }

    public function writeLogToDB() {
        if(Carbon::now()->format('d') <= 28){
            $today = Carbon::now()->format('d');
            $fileName = 'RP_'. $this->business_id . '_' . Carbon::now()->format('Ymd') .'.dat';
        }
        else{
            $today = '28';
            // addMonthsNoOverflow(): 避免如 10/31 加了一個月後變 12/01 的情形出現
            $fileName = 'RP_'. $this->business_id . '_' . Carbon::now()->addMonthsNoOverflow(1)->startOfMonth()->format('Ymd') .'.dat';
        }
        $fileContent = $this->business_id . ',' . $this->user_id . ',' . $this->order_id . ',,,' . intval($this->amount) . ',' . $today . ',' . $this->action . ',' . $this->status . ',' . $this->mode;
        $log = new \App\Models\VipLogs;
        $log->filename = $fileName;
        $log->content = $fileContent;
        $log->save();
    }
}
