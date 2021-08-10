<?php
namespace App\Http\Middleware;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MobileVerifyApiDataLogger{
    private $startTime;

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
        if(\App::environment('local')){
            $envStr = '_test';
        }
        else{
            $envStr = '';
        }
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
                    Log::info('EC mobile auto verify payment failed with user id: ' . $request->CustomField1);
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
                uksort($payload, array('\App\Http\Middleware\MobileVerifyApiDataLogger','merchantSort'));

                // 組合字串
                $sMacValue = 'HashKey=' . Config::get('ecpay.payment'.$envStr.'.HashKey');
                foreach($payload as $key => $value)
                {
                    $sMacValue .= '&' . $key . '=' . $value ;
                }

                $sMacValue .= '&HashIV=' . Config::get('ecpay.payment'.$envStr.'.HashIV');

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
                    return response('0|Error', 200);
                }

                if (sizeof($arErrors) > 0) {
                    Log::info($arErrors);
                    return response('0|Error', 200);
                }

                $pool = '';
                $count = 0;
                foreach ($payload as $key => $value){
                    $pool .= 'Row '. $count . ' : ' . $key . ', Value : ' . $value . '
';//換行
                    $count++;
                }
                $infos = new \App\Models\LogMobilePayInfos();
                $infos->user_id = $user->id;
                $infos->content = $pool;
                $infos->save();
                if (isset($payload['RtnCode'])) {
                    if($payload['RtnCode'] == 1){
                        Log::info('取得卡號前6碼＆後4碼: ' .  array_get($payload,'card6no').array_get($payload,'card4no'));

                        //取得卡號前6碼＆後4碼
                        $credit_card = array_get($payload,'card6no').array_get($payload,'card4no');

                        //經由刷卡通過手機驗證,更新db資料
                        $data = ['active' =>1, 'credit_card' =>$credit_card];
                        DB::table('short_message')->updateOrInsert([
                            'member_id'=>$payload['CustomField1']], $data);

                        return response('1|OK', 200);
                    }
                    else{
                        Log::info("Error: RtnCode didn't set.");
                        return response('0|Error', 200);
                    }
                }
                else{
                    Log::info("Error: No data.");
                    return response('0|No data', 200);
                }
            }
        }
    }
}