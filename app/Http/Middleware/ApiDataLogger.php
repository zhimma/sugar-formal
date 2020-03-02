<?php
namespace App\Http\Middleware;
use App\Models\Vip;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Log;
use App\Services\VipLogService;

class ApiDataLogger{
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
                    Log::info('EC payment failed with user id: ' . $request->CustomField1);
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
                uksort($payload, array('\App\Http\Middleware\ApiDataLogger','merchantSort'));

                // 組合字串
                $sMacValue = 'HashKey=' . config('ecpay.payment.HashKey') ;
                foreach($payload as $key => $value)
                {
                    $sMacValue .= '&' . $key . '=' . $value ;
                }

                $sMacValue .= '&HashIV=' . config('ecpay.payment.HashIV') ;

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
                $infos = new \App\Models\LogUpgradedInfos();
                $infos->user_id = $user->id;
                $infos->content = $pool;
                $infos->save();
                if (isset($payload['RtnCode'])) {
                    if($payload['RtnCode'] == 1){
                        $pool = '';
                        $count = 0;
                        foreach ($payload as $key => $value){
                            $pool .= 'Row '. $count . ' : ' . $key . ', Value : ' . $value . '
';//換行
                            $count++;
                        }
                        $infos = new \App\Models\LogUpgradedInfosWhenGivingPermission();
                        $infos->user_id = $user->id;
                        $infos->content = $pool;
                        $infos->save();
                        $this->logService->upgradeLogEC($payload, $user->id);
                        $this->logService->writeLogToDB();
                        $this->logService->writeLogToFile();
                        Vip::upgrade($user->id, $payload['MerchantID'], $payload['MerchantTradeNo'], $payload['TradeAmt'], '', 1, 0);
                        return '1|OK';
                    }
                    else{
                        return '0|Error';
                    }
                }
                else{
                    return '0|No data';
                }
            }
        }
    }
}