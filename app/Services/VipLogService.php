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

    public function cancelLog($user) {
        $this->user_id = $user->member_id;
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
            $today = '01';
            $fileName = 'RP_'. $this->business_id . '_' . Carbon::now()->addMonth()->startOfMonth()->format('Ymd') .'.dat';
        }
        $fileContent = $this->business_id . ',' . $this->user_id . ',' . $this->order_id . ',,,' . intval($this->amount) . ',' . $today . ',' . $this->action . ',' . $this->status . ',' . $this->mode;

        Storage::append($fileName, $fileContent);
        return array($fileName, $fileContent);
    }

    public function customLogToFile($user_id, $order_id, $day, $action){
        $fileName = 'RP_761404_'. \Carbon\Carbon::today()->format('Ymd') .'.dat';
        $fileContent = '761404,' . $user_id . ',' . $order_id . ',,,888,' . $day . ',' . $action . ',01,0';

        return Storage::append($fileName, $fileContent);
    }

    public function writeLogToDB() {
        if(Carbon::now()->format('d') <= 28){
            $today = Carbon::now()->format('d');
            $fileName = 'RP_'. $this->business_id . '_' . Carbon::now()->format('Ymd') .'.dat';
        }
        else{
            $today = '01';
            $fileName = 'RP_'. $this->business_id . '_' . Carbon::now()->addMonth()->startOfMonth()->format('Ymd') .'.dat';
        }
        $fileContent = $this->business_id . ',' . $this->user_id . ',' . $this->order_id . ',,,' . intval($this->amount) . ',' . $today . ',' . $this->action . ',' . $this->status . ',' . $this->mode;
        $log = new \App\Models\VipLogs;
        $log->filename = $fileName;
        $log->content = $fileContent;
        $log->save();
    }
}
