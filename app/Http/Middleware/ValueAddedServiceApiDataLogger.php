<?php
namespace App\Http\Middleware;

use App\Models\Blocked;
use App\Models\hideOnlineData;
use App\Models\MemberFav;
use App\Models\Message;
use App\Models\AdminCommonText;
use App\Models\Order;
use App\Models\Tip;
use App\Models\ValueAddedService;
use App\Models\Visited;
//use App\Models\VvipApplication;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Log;

class ValueAddedServiceApiDataLogger{
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
                $infos = new \App\Models\LogValueAddedServiceInfos();
                $infos->user_id = $user->id;
                $infos->service_name = $request->CustomField4;
                $infos->content = $pool;
                $infos->created_at = Carbon::now();
                $infos->save();
                if (isset($payload['RtnCode'])) {
                    if($payload['RtnCode'] == 1) {
                        ValueAddedService::upgrade($user->id, $payload['CustomField4'], $payload['MerchantID'], $payload['MerchantTradeNo'], $payload['TradeAmt'], '', 1, $payload['CustomField3']);

                        if(!\App::environment('local')) {
                            //產生訂單 --正式綠界
                            Order::addEcPayOrder($payload['MerchantTradeNo'], null);
                        }

                        //vvip 申請付款時存入申請表
//                        if (substr($payload['CustomField4'], 0, 4) == 'VVIP' && strlen($payload['CustomField4']) == 6) {
//                            $addData = new VvipApplication;
//                            $addData->user_id = $user->id;
//                            $addData->order_id = $payload['MerchantTradeNo'];
//                            $addData->created_at = Carbon::now();
//                            $addData->save();
//                        }

                        if ($payload['CustomField4'] == 'hideOnline') {
                            //存快照
                            $register_time = $user->created_at;
                            $login_time = Carbon::now();
                            /*每周平均上線次數*/
                            $datetime1 = new \DateTime(now());
                            $datetime2 = new \DateTime($user->created_at);
                            $diffDays = $datetime1->diff($datetime2)->days;
                            $week = ceil($diffDays / 7);
                            if ($week == 0) {
                                $login_times_per_week = 0;
                            } else {
                                $login_times_per_week = round(($user->login_times / $week), 0);
                            }
                            $be_fav_count = MemberFav::where('member_fav_id', $user->id)->get()->count();
                            $fav_count = MemberFav::where('member_id', $user->id)->get()->count();
                            $tip_count = Tip::where('to_id', $user->id)->get()->count();
                            /*七天前*/
                            $date = date('Y-m-d H:m:s', strtotime('-7 days'));
                            /*發信＆回信次數統計*/
                            $messages_all = Message::select('id', 'to_id', 'from_id', 'created_at')->where('to_id', $user->id)->orwhere('from_id', $user->id)->orderBy('id')->get();
                            $countInfo['message_count'] = 0;
                            $countInfo['message_reply_count'] = 0;
                            $countInfo['message_reply_count_7'] = 0;
                            $send = [];
                            $receive = [];
                            foreach ($messages_all as $message) {
                                //user_id主動第一次發信
                                if ($message->from_id == $user->id && array_get($send, $message->to_id) < $message->id) {
                                    $send[$message->to_id][] = $message->id;
                                }
                                //紀錄每個帳號第一次發信給uid
                                if ($message->to_id == $user->id && array_get($receive, $message->from_id) < $message->id) {
                                    $receive[$message->from_id][] = $message->id;
                                }
                                if (!is_null(array_get($receive, $message->to_id))) {
                                    $countInfo['message_reply_count'] += 1;
                                    if ($message->created_at >= $date) {
                                        //計算七天內回信次數
                                        $countInfo['message_reply_count_7'] += 1;
                                    }
                                }
                            }
                            $countInfo['message_count'] = count($send);

                            $messages_7days = Message::select('id', 'to_id', 'from_id', 'created_at')->whereRaw('(to_id =' . $user->id . ' OR from_id=' . $user->id . ')')->where('created_at', '>=', $date)->orderBy('id')->get();
                            $countInfo['message_count_7'] = 0;
                            $send = [];
                            foreach ($messages_7days as $message) {
                                //七天內uid主動第一次發信
                                if ($message->from_id == $user->id && array_get($send, $message->to_id) < $message->id) {
                                    $send[$message->to_id][] = $message->id;
                                }
                            }
                            $countInfo['message_count_7'] = count($send);

                            /*發信次數*/
                            $message_count = $countInfo['message_count'];
                            /*過去7天發信次數*/
                            $message_count_7 = $countInfo['message_count_7'];
                            /*回信次數*/
                            $message_reply_count = $countInfo['message_reply_count'];
                            /*過去7天回信次數*/
                            $message_reply_count_7 = $countInfo['message_reply_count_7'];
                            /*過去7天罐頭訊息比例*/
                            $date_start = date("Y-m-d", strtotime("-6 days", strtotime(date('Y-m-d'))));
                            $date_end = date('Y-m-d');

                            /**
                             * 效能調整：使用左結合以大幅降低處理時間
                             *
                             * @author LZong <lzong.tw@gmail.com>
                             */
                            $query = Message::select('users.email', 'users.name', 'users.title', 'users.engroup', 'users.created_at', 'users.last_login', 'message.id', 'message.from_id', 'message.content', 'user_meta.about')
                                ->join('users', 'message.from_id', '=', 'users.id')
                                ->join('user_meta', 'message.from_id', '=', 'user_meta.user_id')
                                ->leftJoin('banned_users as b1', 'b1.member_id', '=', 'message.from_id')
                                ->leftJoin('banned_users_implicitly as b3', 'b3.target', '=', 'message.from_id')
                                ->leftJoin('warned_users as wu', function ($join) {
                                    $join->on('wu.member_id', '=', 'message.from_id')
                                        ->where('wu.expire_date', '>=', Carbon::now())
                                        ->orWhere('wu.expire_date', null);
                                })
                                ->whereNull('b1.member_id')
                                ->whereNull('b3.target')
                                ->whereNull('wu.member_id')
                                ->where(function ($query) use ($date_start, $date_end) {
                                    $query->where('message.from_id', '<>', 1049)
                                        ->where('message.sys_notice', 0)
                                        ->whereBetween('message.created_at', array($date_start . ' 00:00', $date_end . ' 23:59'));
                                });
                            $query->where('users.email', $user->email);
                            $results_a = $query->distinct('message.from_id')->get();

                            if ($results_a != null) {
                                $msg = array();
                                $from_content = array();
                                $user_similar_msg = array();

                                $messages = Message::select('id', 'content', 'created_at')
                                    ->where('from_id', $user->id)
                                    ->where('sys_notice', 0)
                                    ->whereBetween('created_at', array($date_start . ' 00:00', $date_end . ' 23:59'))
                                    ->orderBy('created_at', 'desc')
                                    ->take(100)
                                    ->get();

                                foreach ($messages as $row) {
                                    array_push($msg, array('id' => $row->id, 'content' => $row->content, 'created_at' => $row->created_at));
                                }

                                array_push($from_content, array('msg' => $msg));

                                $unique_id = array(); //過濾重複ID用
                                //比對訊息
                                foreach ($from_content as $data) {
                                    foreach ($data['msg'] as $word1) {
                                        foreach ($data['msg'] as $word2) {
                                            if ($word1['created_at'] != $word2['created_at']) {
                                                similar_text($word1['content'], $word2['content'], $percent);
                                                if ($percent >= 70) {
                                                    if (!in_array($word1['id'], $unique_id)) {
                                                        array_push($unique_id, $word1['id']);
                                                        array_push($user_similar_msg, array($word1['id'], $word1['content'], $word1['created_at'], $percent));
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            $message_percent_7 = count($user_similar_msg) > 0 ? round((count($user_similar_msg) / count($messages)) * 100) . '%' : '0%';
                            /*瀏覽其他會員次數*/
                            $visit_other_count = Visited::where('member_id', $user->id)->count();
                            /*被瀏覽次數*/
                            $be_visit_other_count = Visited::where('visited_id', $user->id)->count();
                            /*過去7天瀏覽其他會員次數*/
                            $visit_other_count_7 = Visited::where('member_id', $user->id)->where('created_at', '>=', $date)->count();
                            /*過去7天被瀏覽次數*/
                            $be_visit_other_count_7 = Visited::where('visited_id', $user->id)->where('created_at', '>=', $date)->count();

                            /*此會員封鎖多少其他會員*/
                            $bannedUsers = \App\Services\UserService::getBannedId();
                            $blocked_other_count = Blocked::with(['blocked_user'])
                                ->join('users', 'users.id', '=', 'blocked.blocked_id')
                                ->where('blocked.member_id', $user->id)
                                ->whereNotIn('blocked.blocked_id',$bannedUsers)
                                ->whereNotNull('users.id')
                                ->count();

                            /*此會員被多少會員封鎖*/
                            $be_blocked_other_count = Blocked::with(['blocked_user'])
                                ->join('users', 'users.id', '=', 'blocked.member_id')
                                ->where('blocked.blocked_id', $user->id)
                                ->whereNotIn('blocked.member_id',$bannedUsers)
                                ->whereNotNull('users.id')
                                ->count();


                            //寫入hide_online_data

                            //先刪後增 softDelete
                            hideOnlineData::where('user_id', $user->id)->delete();
                            hideOnlineData::insert([
                                'user_id' => $user->id,
                                'created_at' => Carbon::now(),
                                'register_time' => $register_time,
                                'login_time' => $login_time,
                                'login_times_per_week' => $login_times_per_week,
                                'be_fav_count' => $be_fav_count,
                                'fav_count' => $fav_count,
                                'tip_count' => $tip_count,
                                'message_count' => $message_count,
                                'message_count_7' => $message_count_7,
                                'message_reply_count' => $message_reply_count,
                                'message_reply_count_7' => $message_reply_count_7,
                                'message_percent_7' => $message_percent_7,
                                'visit_other_count' => $visit_other_count,
                                'visit_other_count_7' => $visit_other_count_7,
                                'be_visit_other_count' => $be_visit_other_count,
                                'be_visit_other_count_7' => $be_visit_other_count_7,
                                'blocked_other_count' => $blocked_other_count,
                                'be_blocked_other_count' => $be_blocked_other_count
                            ]);
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