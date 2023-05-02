<?php
namespace App\Http\Middleware;

use App\Models\Order;
use App\Models\OrderLog;
use App\Models\ValueAddedService;
use App\Models\VvipApplication;
use App\Services\VipLogService;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Log;
use App\Services\EnvironmentService;

class ValueAddedServiceApiDataLogger{
    private $startTime;

    public function __construct(VipLogService $logService){
        $this->logService = $logService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        $this->startTime = microtime(true);
        return $next($request);
    }

    private static function merchantSort($a,$b)
    {
        return strcasecmp($a, $b);
    }

    public function terminate($request, $response){
        if ( env('API_DATALOGGER', true) ) {
            if(!str_contains($request->getContent(), 'BankResponseCode')) {
                $endTime = microtime(true);
                $filename = 'api_datalogger_' . Carbon::now()->format('Y-m-d') . '.log';
                $dataToLog = 'Time: ' . Carbon::now()->toDateTimeString() . "\n";
                $dataToLog .= 'Duration: ' . number_format($endTime - LARAVEL_START, 3) . "\n";
                $dataToLog .= 'IP Address: ' . $request->ip() . "\n";
                $dataToLog .= 'URL: ' . $request->fullUrl() . "\n";
                $dataToLog .= 'Method: ' . $request->method() . "\n";
                $dataToLog .= 'Input: ' . $request->getContent() . "\n";
                \File::append(storage_path('logs' . DIRECTORY_SEPARATOR . $filename), $dataToLog . "\n" . str_repeat("=", 20) . "\n\n");
            }
            else{
                $endTime = microtime(true);
                $filename = 'api_datalogger_prereturn_request_' . Carbon::now()->format('Y-m-d') . '.log';
                $dataToLog = 'Time: ' . Carbon::now()->toDateTimeString() . "\n";
                $dataToLog .= 'Duration: ' . number_format($endTime - LARAVEL_START, 3) . "\n";
                $dataToLog .= 'IP Address: ' . $request->ip() . "\n";
                $dataToLog .= 'URL: ' . $request->fullUrl() . "\n";
                $dataToLog .= 'Method: ' . $request->method() . "\n";
                $dataToLog .= 'Input: ' . $request->getContent() . "\n";
                //$dataToLog .= 'Output: ' . $response->getContent() . "\n";
                \File::append(storage_path('logs' . DIRECTORY_SEPARATOR . $filename), $dataToLog . "\n" . str_repeat("=", 20) . "\n\n");
            }

            if(str_contains($request->getContent(), 'RtnCode')){
                Log::info($request->all());
                $user = \App\Models\User::findById($request->CustomField1);
                if ($user == null)
                {
                    Log::info('EC valueAddService payment failed with user id: ' . $request->CustomField1);
                }
                $payload = $request->all();
                // 變數宣告。
                $arErrors = array();
                $arFeedback = array();
                $szCheckMacValue = '';
                $EncryptType = 1;
                // 重新整理回傳參數。
                foreach ($payload as $keys => $value) {
                    if ($keys != 'CheckMacValue') {
                        if ($keys == 'PaymentType') {
                            $value = str_replace('_CVS', '', $value);
                            $value = str_replace('_BARCODE', '', $value);
                            $value = str_replace('_CreditCard', '', $value);
                        }
                        if ($keys == 'PeriodType') {
                            $value = str_replace('Y', 'Year', $value);
                            $value = str_replace('M', 'Month', $value);
                            $value = str_replace('D', 'Day', $value);
                        }
                        $arFeedback[$keys] = $value;
                    }
                }

                $payloadCheckMacValue = $payload['CheckMacValue'];
                unset($payload['CheckMacValue']);
                uksort($payload, array('\App\Http\Middleware\ValueAddedServiceApiDataLogger','merchantSort'));

                if(EnvironmentService::isLocalOrTestMachine()){
                    $envStr = '_test';
                }
                else{
                    $envStr = '';
                }

                // 組合字串
                $sMacValue = 'HashKey=' . config('ecpay.payment'.$envStr.'.HashKey') ;
                foreach($payload as $key => $value)
                {
                    $sMacValue .= '&' . $key . '=' . $value ;
                }

                $sMacValue .= '&HashIV=' . config('ecpay.payment'.$envStr.'.HashIV') ;

                // URL Encode編碼
                $sMacValue = urlencode($sMacValue);

                // 轉成小寫
                $sMacValue = strtolower($sMacValue);

                // 取代為與 dotNet 相符的字元
                $sMacValue = str_replace('%2d', '-', $sMacValue);
                $sMacValue = str_replace('%5f', '_', $sMacValue);
                $sMacValue = str_replace('%2e', '.', $sMacValue);
                $sMacValue = str_replace('%21', '!', $sMacValue);
                $sMacValue = str_replace('%2a', '*', $sMacValue);
                $sMacValue = str_replace('%28', '(', $sMacValue);
                $sMacValue = str_replace('%29', ')', $sMacValue);

                // 編碼
                $sMacValue = hash('sha256', $sMacValue);

                $CheckMacValue = strtoupper($sMacValue);

                if ($CheckMacValue != $payloadCheckMacValue) {
                    Log::info('CheckMacValue verify fail.');
                    return '0|Error';
                }

                if (sizeof($arErrors) > 0) {
                    Log::info($arErrors);
                    return '0|Error';
                }

                $pool = '';
                $count = 0;
                foreach ($payload as $key => $value){
                    $pool .= 'Row '. $count . ' : ' . $key . ', Value : ' . $value . '
';//換行
                    $count++;
                }
                $infos = new \App\Models\LogValueAddedServiceInfos();
                $infos->user_id = $user->id;
                $infos->service_name = $request->CustomField4;
                $infos->content = $pool;
                $infos->created_at = Carbon::now();
                $infos->save();
                if (isset($payload['RtnCode'])) {
                    if($payload['RtnCode'] == 1) {
                        $remain_days = $payload['CustomField2'];
                        ValueAddedService::upgrade($user->id, $payload['CustomField4'], $payload['MerchantID'], $payload['MerchantTradeNo'], $payload['TradeAmt'], '', 1, $payload['CustomField3'], $remain_days);

                        //VVIP定期定額繳費成功後 存入申請表
                        if($payload['CustomField4'] == 'VVIP'){
                            $addData = new VvipApplication;
                            $addData->user_id = $user->id;
                            $addData->order_id = $payload['MerchantTradeNo'];
                            $addData->plan =$payload['CustomField2'];
                            $addData->created_at = Carbon::now();
                            $addData->save();

                            [$refund, $vip_text] = \App\Services\PaymentService::calculatesRefund($user, 'vip_refund');
                            if($refund) {
                                $record = Order::find($user->vip->first()->order_id);
                                $record->need_to_refund = 1;
                                $record->refund_amount = $refund;
                                $record->saveOrFail();
                            } 
                        }

                        if ($payload['CustomField4'] == 'hideOnline') {
                            ValueAddedService::addHideOnlineData($user->id);
                        }

                        //正式環境用
                        //抓重複付費定期定額訂單寫入取消訂單列表
                        if(!(EnvironmentService::isLocalOrTestMachine())) {
                            //產生訂單 --正式環境訂單
                            if(str_contains($_SERVER["HTTP_REFERER"], 'ecpay')) {
                                Order::addEcPayOrder($payload['MerchantTradeNo'], null);
                            }elseif(str_contains($_SERVER["HTTP_REFERER"], 'funpoint')){
                                Order::addFunPointPayOrder($payload['MerchantTradeNo'], null);
                            }

                            if(str_contains($payload['CustomField3'], 'cc_')) {
                                //正式環境用
                                //抓重複付費定期定額訂單 寫入取消訂單列表
                                //檢查歷史 service_name 訂單
                                Order::orderCheckByUserIdAndServiceName($user->id, $payload['CustomField4']);
                                if($payload['CustomField4'] == 'hideOnline' || $payload['CustomField4'] == 'VVIP'){
                                    $getCurrentData = ValueAddedService::where('member_id', $user->id)
                                        ->where('order_id', $payload['MerchantTradeNo'])
                                        ->where('service_name', $payload['CustomField4'])
                                        ->where('active', 1)
                                        ->first();
                                }

                                if($getCurrentData) {
                                    $needCancelOrder = Order::where('user_id', $user->id)
                                        ->where('order_id', '<>', $getCurrentData->order_id)
                                        ->where('ExecStatus', 1)
                                        ->where('service_name', $payload['CustomField4'])
                                        ->get();

                                    if ($needCancelOrder && count($needCancelOrder) > 0) {
                                        foreach ($needCancelOrder as $row) {
                                            logger($row->order_id . '定期定額繳費中，但 user: ' . $row->user_id . ' 已對' . $row->service_name . ' 重新訂購完成，故此筆寫入取消列表。');
                                            $order = Order::findByOrderId($row->order_id);
                                            $this->logService->cancelLogForOrder($order);
                                            $this->logService->writeLogToDB();
                                            $file = $this->logService->writeLogToFile();
                                            if (strpos(\Storage::disk('local')->get($file[0]), $file[1]) !== false) {
                                                logger($row->order_id . '定期定額繳費中，但 user: ' . $row->user_id . ' 已對' . $row->service_name . ' 重新訂購完成，故此筆寫入取消列表完成。');
                                            }
                                            OrderLog::addToLog($row->user_id, $row->order_id, '定期定額繳費中，但使用者已對 ' . $row->service_name . ' 重新訂購完成，故此筆寫入取消訂單列表。');
                                        }
                                    }
                                }
                            }
                        }

                        return '1|OK';
                    }
                    elseif ($payload['RtnCode'] == '10100073' && $payload['PaymentType'] == 'BARCODE_BARCODE'){
                        Log::info("Barcode info.");
                    }
                    elseif ($payload['RtnCode'] == '2' && str_contains($payload['PaymentType'], 'ATM')){
                        Log::info("ATM info.");
                    }
                    else{
                        Log::info("Error: RtnCode didn't set.");
                        return '0|Error';
                    }
                }
                else{
                    Log::info("Error: No data.");
                    return '0|No data';
                }
            }
        }
        return '0|Error';
    }
}