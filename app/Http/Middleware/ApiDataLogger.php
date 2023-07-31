<?php
namespace App\Http\Middleware;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\PaymentGetQrcodeLog;
use App\Models\SimpleTables\banned_users;
use App\Models\SimpleTables\warned_users;
use App\Models\ValueAddedService;
use App\Models\ValueAddedServiceLog;
use App\Models\Vip;
use App\Models\VipLog;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Log;
use App\Services\VipLogService;
use Illuminate\Support\Facades\DB;
use App\Services\EnvironmentService;

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

                if(EnvironmentService::isLocalOrTestMachine()){
                    $envStr = '_test';
                }
                else{
                    $envStr = '';
                }
                // 組合字串 funpoint
                if(str_contains($_SERVER["HTTP_REFERER"], 'ecpay')) {
                    $sMacValue = 'HashKey=' . config('ecpay.payment' . $envStr . '.HashKey');
                }elseif(str_contains($_SERVER["HTTP_REFERER"], 'funpoint')){
                    $sMacValue = 'HashKey=' . config('funpoint.payment' . $envStr . '.HashKey');
                }
                foreach($payload as $key => $value)
                {
                    $sMacValue .= '&' . $key . '=' . $value ;
                }
                if(str_contains($_SERVER["HTTP_REFERER"], 'ecpay')) {
                    $sMacValue .= '&HashIV=' . config('ecpay.payment' . $envStr . '.HashIV');
                }elseif(str_contains($_SERVER["HTTP_REFERER"], 'funpoint')){
                    $sMacValue .= '&HashIV=' . config('funpoint.payment' . $envStr . '.HashIV');
                }

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
                    logger($CheckMacValue);
                    logger($payloadCheckMacValue);
                    return '0|Error';
                }

                if (sizeof($arErrors) > 0) {
                    Log::info($arErrors);
                    return '0|Error';
                }

                $transactionType='';
                if($payload['PaymentType'] == 'Credit_CreditCard')
                    $transactionType='CREDIT'; //信用卡
                elseif(str_contains($payload['PaymentType'], 'ATM'))
                    $transactionType='ATM'; //ATM
                elseif($payload['PaymentType'] == 'BARCODE_BARCODE')
                    $transactionType='BARCODE'; //超商條碼
                elseif (str_contains($payload['PaymentType'], 'CVS'))
                    $transactionType='CVS'; //超商代號
                else
                    $transactionType=$payload['PaymentType']; //寫入回傳的PaymentType

                //存入超商條碼取號紀錄 + ATM
                if( isset($payload['RtnCode']) &&
                    (
                        ($payload['RtnCode'] == '10100073' && ($payload['PaymentType'] == 'BARCODE_BARCODE' || str_contains($payload['PaymentType'], 'CVS'))) ||
                        ($payload['RtnCode'] == '2' && str_contains($payload['PaymentType'], 'ATM') )
                    )
                  ){

                    $PaymentGetQrcode = new PaymentGetQrcodeLog();
                    $PaymentGetQrcode->user_id = $payload['CustomField1'];
                    $PaymentGetQrcode->service_name = $payload['CustomField4'];
                    $PaymentGetQrcode->ExpireDate = $payload['ExpireDate'];
                    $PaymentGetQrcode->order_id = $payload['MerchantTradeNo'];
                    $PaymentGetQrcode->TradeDate = $payload['TradeDate'];
                    $PaymentGetQrcode->payment = $payload['CustomField3'];
                    $PaymentGetQrcode->payment_type = $payload['PaymentType'];
                    if(str_contains($payload['PaymentType'], 'ATM')){
                        $PaymentGetQrcode->BankCode = $payload['BankCode'];
                        $PaymentGetQrcode->vAccount = $payload['vAccount'];
                    }
                    if($payload['PaymentType'] == 'BARCODE_BARCODE' || str_contains($payload['PaymentType'], 'CVS')){
                        $PaymentGetQrcode->PaymentNo = $payload['PaymentNo'];
                        $PaymentGetQrcode->Barcode1 = $payload['Barcode1'];
                        $PaymentGetQrcode->Barcode2 = $payload['Barcode2'];
                        $PaymentGetQrcode->Barcode3 = $payload['Barcode3'];
                    }
                    $PaymentGetQrcode->save();

                    $logStr = '取號完成, order id: ' . $payload['MerchantTradeNo'] . ', payment: ' . $payload['CustomField3'] . ', amount: ' . $payload['TradeAmt'] . ', transactionType: ' . $transactionType;
                    if($payload['CustomField4']=='VIP'){
                        VipLog::addToLog($payload['CustomField1'], $logStr, '', 0, 0);
                    }else{
                        if($payload['CustomField4'] != '') {
                            $logStr = '取號完成, payment: ' . $payload['CustomField3'] . ', service： '.$payload['CustomField4'].', transactionType: ' . $transactionType;
                            ValueAddedServiceLog::addToLog($payload['CustomField1'], $payload['CustomField4'], $logStr, $payload['MerchantTradeNo'], '', 0);
                        }
                    }

                    //預先給予機制
                    //暫時自動發放VIP權限
//                    $transactionType='';
//                    if($payload['PaymentType'] == 'Credit_CreditCard')
//                        $transactionType='CREDIT'; //信用卡
//                    elseif(str_contains($payload['PaymentType'], 'ATM'))
//                        $transactionType='ATM'; //ATM
//                    elseif($payload['PaymentType'] == 'BARCODE_BARCODE')
//                        $transactionType='BARCODE'; //超商條碼
//                    elseif ($payload['PaymentType'] == 'CVS_CVS')
//                        $transactionType='CVS'; //超商代號
//                    else
//                        $transactionType=$payload['PaymentType']; //寫入回傳的PaymentType
//
//                    logger('Middleware ApiDataLogger=> userID:'.$user->id.', 種類:' .$payload['CustomField3'].', 付款方式:' .$transactionType. '(預先給予權限)');
//
//                    $remain_days = $payload['CustomField2'];
//                    Vip::upgrade($user->id, $payload['MerchantID'], $payload['MerchantTradeNo'], $payload['TradeAmt'], '', 1, 0,$payload['CustomField3'],$transactionType,$remain_days);

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

                        logger('Middleware ApiDataLogger=> userID:'.$user->id.'購買項目:'.$payload['CustomField4'].', 種類:' .$payload['CustomField3'].', 付款方式:' .$transactionType);

                        $remain_days = $payload['CustomField2'];

                        Vip::upgrade($user->id, $payload['MerchantID'], $payload['MerchantTradeNo'], $payload['TradeAmt'], '', 1, 0, $payload['CustomField3'], $transactionType, $remain_days);

                        //解除vip_pass紀錄 banned_users warned_users
                        banned_users::where('vip_pass', 1)->where('member_id', $user->id)->delete();
                        warned_users::where('vip_pass', 1)->where('member_id', $user->id)->delete();

                        //產生訂單 --正式環境訂單
                        if(str_contains($_SERVER["HTTP_REFERER"], 'ecpay')) {
                            Order::addEcPayOrder($payload['MerchantTradeNo'], null);
                        }elseif(str_contains($_SERVER["HTTP_REFERER"], 'funpoint')){
                            Order::addFunPointPayOrder($payload['MerchantTradeNo'], null);
                        }

                        //正式環境用
                        //抓重複付費定期定額訂單寫入取消訂單列表
                        if(!EnvironmentService::isLocalOrTestMachine() && str_contains($payload['CustomField3'], 'cc_')){
                            //檢查歷史 service_name 訂單
                            Order::orderCheckByUserIdAndServiceName($user->id, $payload['CustomField4']);
                            //防呆檢查
                            //預防綠界重複回傳產生重複資料
                            if($payload['CustomField4'] == 'VIP'){
                                $getCurrentData = Vip::where('member_id', $user->id)
                                    ->where('order_id', $payload['MerchantTradeNo'])
                                    ->where('active', 1)
                                    ->first();
                            }else if($payload['CustomField4'] == 'hideOnline' || $payload['CustomField4'] == 'VVIP'){
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
    }
}