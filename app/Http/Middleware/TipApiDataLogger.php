<?php
namespace App\Http\Middleware;
use App\Models\Message;
use App\Models\AdminCommonText;
use App\Models\Tip;
use App\Models\Vip;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Log;
use App\Services\VipLogService;

class TipApiDataLogger{
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
                    Log::info('EC tip payment failed with user id: ' . $request->CustomField1);
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
                uksort($payload, array('\App\Http\Middleware\TipApiDataLogger','merchantSort'));

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
                $infos = new \App\Models\LogChatPayInfos();
                $infos->user_id = $user->id;
                $infos->content = $pool;
                $infos->save();
                if (isset($payload['RtnCode'])) {
                    if($payload['RtnCode'] == 1){
                        Tip::upgrade($user->id, $payload['MerchantTradeNo'], '');
                        Message::post($user->id, $payload['CustomField2'], "系統通知: 車馬費邀請");
                        if($user->engroup == 1) {
                            //取資料庫並替換名字
                            $tip_msg1 = AdminCommonText::getCommonText(1);//id2給男會員訊息
                            $tip_msg1 = str_replace('NAME', \App\Models\User::findById($payload['CustomField2'])->name, $tip_msg1);
                            $tip_msg2 = AdminCommonText::getCommonText(2);//id3給女會員訊息
                            $tip_msg2 = str_replace('NAME', $user->name, $tip_msg2);
                            // 給男會員訊息
                            Message::post($payload['CustomField1'], $payload['CustomField2'], $tip_msg1, false);
                            Message::post($payload['CustomField1'], $payload['CustomField2'], $tip_msg2, false);

                            // 給男會員訊息
                            // Message::post($payload['CustomField1'], $payload['CustomField2'], "系統通知: 車馬費邀請\n您已經向 ". \App\Models\User::findById($payload['CustomField2'])->name ." 發動車馬費邀請。\n流程如下\n1:網站上進行車馬費邀請\n2:網站上訊息約見(重要，站方判斷約見時間地點，以網站留存訊息為準)\n3:雙方見面\n\n如果雙方在第二步就約見失敗。\n將扣除手續費 288 元後，1500匯入您指定的帳戶。也可以用現金袋或者西聯匯款方式進行。\n(聯繫我們有站方聯絡方式)\n\n若雙方有見面意願，被女方放鴿子。\n站方會參照女方提出的證據，判斷是否將尾款交付女方。", false);
                            // Message::post($payload['CustomField2'], $payload['CustomField1'], "系統通知: 車馬費邀請\n". $user->name . " 已經向 您 發動車馬費邀請。\n流程如下\n1:網站上進行車馬費邀請\n2:網站上訊息約見(重要，站方判斷約見時間地點，以網站留存訊息為準)\n3:雙方見面(建議約在知名連鎖店丹堤星巴克或者麥當勞之類)\n\n若成功見面男方沒有提出異議，那站方會在發動後 7~14 個工作天\n將 1500 匯入您指定的帳戶。若您不想提供銀行帳戶。\n也可以用現金袋或者西聯匯款方式進行。\n(聯繫我們有站方聯絡方式)\n\n若男方提出當天女方未到場的爭議。請您提出當天消費的發票證明之。\n所以請約在知名連鎖店以利站方驗證。\n", false);
                        }
                        else if($user->engroup == 2) {
                            // 給女會員訊息
                            // Message::post($user->id, $payload['P_OrderNumber'], "系統通知: 車馬費邀請\n". $user->name . " 已經向 您 發動車馬費邀請。\n流程如下\n1:網站上進行車馬費邀請\n2:網站上訊息約見(重要，站方判斷約見時間地點，以網站留存訊息為準)\n3:雙方見面(建議約在知名連鎖店丹堤星巴克或者麥當勞之類)\n\n若成功見面男方沒有提出異議，那站方會在發動後 7~14 個工作天\n將 1500 匯入您指定的帳戶。若您不想提供銀行帳戶。\n也可以用現金袋或者西聯匯款方式進行。\n(聯繫我們有站方聯絡方式)\n\n若男方提出當天女方未到場的爭議。請您提出當天消費的發票證明之。\n所以請約在知名連鎖店以利站方驗證。\n");
                        }
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