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
                        Vip::upgrade($user->id, $payload['MerchantID'], $payload['MerchantTradeNo'], $payload['TradeAmt'], $payload['CheckMacValue'], 1, 0);
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