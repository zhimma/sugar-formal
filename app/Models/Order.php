<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use App\Services\EnvironmentService;
use App\Services\LineNotifyService as LineNotify;

class Order extends Model
{
    use HasFactory;
    //
    protected $table = 'order';

    protected $fillable = [
        'order_id',
        'user_id',
        'order_date',
        'order_expire_date',
        'service_name',
        'payment_flow',
        'payment',
        'payment_type',
        'pay_date',
        'amount',
        'need_to_refund',
        'refund_amount'
    ];

    protected $primaryKey = 'order_id';

    protected $keyType = 'string';

    public function users(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public static function addEcPayOrder($order_id, $order_expire_date = null){

        if($order_id != '' && substr($order_id,0,2) == 'SG') {
            //正式綠界訂單查詢
            if(EnvironmentService::isLocalOrTestMachine()){
                $envStr = '_test';
            }
            else{
                $envStr = '';
            }
            $envStr = '';
            
            $ecpay = new \App\Services\ECPay_AllInOne();
            $ecpay->MerchantID = Config::get('ecpay.payment' . $envStr . '.MerchantID');
            $ecpay->ServiceURL = Config::get('ecpay.payment' . $envStr . '.OrderQueryURL');
            $ecpay->HashIV = Config::get('ecpay.payment' . $envStr . '.HashIV');
            $ecpay->HashKey = Config::get('ecpay.payment' . $envStr . '.HashKey');
            $ecpay->Query = [
                'MerchantTradeNo' => $order_id,
                'TimeStamp' => time()
            ];
            $paymentData = $ecpay->QueryTradeInfo();

            $ecpay->ServiceURL = Config::get('ecpay.payment' . $envStr . '.ServiceURL');//定期定額查詢
            $paymentPeriodInfo = $ecpay->QueryPeriodCreditCardTradeInfo();

            //check order exist
            $checkOrder = Order::where('order_id', $order_id)->first();
            if(!$checkOrder && isset($paymentData) && $paymentData['TradeStatus']==1) {
                if($paymentData['CustomField4']==''){
                    $service_name = 'VIP';
                }else{
                    $service_name = $paymentData['CustomField4'];
                }
                if($paymentData['CustomField3']==''){
                    $payment = 'cc_monthly_payment';//舊訂單歸類定期定額月付
                }else{
                    $payment = $paymentData['CustomField3'];
                }
                //insert new order
                $order = new Order;
                $order->order_id = $order_id;
                $order->user_id = $paymentData['CustomField1'];
                $order->business_id = $paymentData['MerchantID'];
                $order->order_date = $paymentData['PaymentDate'];

                $order->service_name = $service_name;
                $order->payment_flow = 'ecpay';
                $order->payment = $payment;
                $order->payment_type = $paymentData['PaymentType'];
                $dateArray = array();
                $dateFailArray = array();
                if($paymentPeriodInfo['ExecLog']==''){
                    $dd = str_replace('%20', ' ', $paymentData['PaymentDate']);
                    $dd = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $dd)->toDateTimeString();
                    array_push($dateArray, array($dd));

                }else{
                    foreach($paymentPeriodInfo['ExecLog'] as $data){
                        $dd = str_replace('%20', ' ', $data['process_date']);
                        $dd = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $dd)->toDateTimeString();
                        if($data['RtnCode']==1) {
                            array_push($dateArray, array($dd));
                        }else{
                            array_push($dateFailArray, array($dd));
                        }
                    }

                    if(count($dateFailArray)==0){
                        $dateFailArray = null;
                    }else{
                        $dateFailArray = json_encode($dateFailArray);
                    }

                    $temp = end($dateArray);
                    $lastProcessDate = Carbon::parse($temp[0]);

                    if($paymentPeriodInfo['card4no']) {
                        $order->card4no = $paymentPeriodInfo['card4no'];
                        $order->card6no = $paymentPeriodInfo['card6no'];
                    }

                }
                $order->pay_date = json_encode($dateArray);
                $order->pay_fail = $dateFailArray;

                $PaymentDate =str_replace('%20', ' ', $paymentData['PaymentDate']);
                $PaymentDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $PaymentDate);

                if($order_expire_date=='') {
                    if ($payment == 'one_quarter_payment') {
                        $order->order_expire_date = $PaymentDate->addMonthsNoOverflow(3);
                    } elseif ($payment == 'one_month_payment') {
                        $order->order_expire_date = $PaymentDate->addMonthsNoOverflow(1);
                    } elseif ($payment == 'cc_quarterly_payment' && $lastProcessDate != '' && $paymentPeriodInfo['ExecStatus'] == 0) {
                        //ExecStatus=0 定期定額取消直接補上到期日
                        $order->order_expire_date = $lastProcessDate->addMonthsNoOverflow(3);
                        //到期日加上剩餘天數
                        if($paymentData['CustomField2'] != '' && $paymentData['CustomField2']>0 && ($service_name == 'VIP' || $service_name == 'hideOnline')){
                            $temp_day = $order->order_expire_date;
                            $order->order_expire_date = $temp_day->addDays($paymentData['CustomField2']);
                        }
                    } elseif ($payment == 'cc_monthly_payment' && $lastProcessDate != '' && $paymentPeriodInfo['ExecStatus'] == 0) {
                        //ExecStatus=0 定期定額取消直接補上到期日
                        $order->order_expire_date = $lastProcessDate->addMonthsNoOverflow(1);
                        //到期日加上剩餘天數
                        if($paymentData['CustomField2'] != '' && $paymentData['CustomField2']>0 && ($service_name == 'VIP' || $service_name == 'hideOnline')){
                            $temp_day = $order->order_expire_date;
                            $order->order_expire_date = $temp_day->addDays($paymentData['CustomField2']);
                        }
                    } elseif ($payment == '' && $paymentPeriodInfo['ExecStatus'] == 0) {
                        //舊式定期定額皆為月繳
                        //ExecStatus=0 定期定額取消直接補上到期日
                        $order->order_expire_date = $lastProcessDate->addMonthsNoOverflow(1);
                    }
                }else{
                    $order->order_expire_date = $order_expire_date;
                }

                $order->amount = $paymentData['TradeAmt'];
                $order->ExecStatus = $paymentPeriodInfo['ExecStatus']??null;
                $order->created_at = Carbon::now();
                if($paymentData['CustomField2'] != '' && ($paymentData['CustomField4'] == 'VIP' || $paymentData['CustomField4'] == 'hideOnline')) {
                    $order->remain_days = $paymentData['CustomField2'];
                }

                try {
                    $saved = $order->save();
                } catch (\Exception $e) {
                    Log::error($e);
                    \Sentry::captureMessage("綠界訂單異常。" . $e->getMessage());
                    $lineNotify = new LineNotify;
                    $lineNotify->sendLineNotifyMessage("綠界訂單異常。" . $e->getMessage());
                }

                if($saved) {
                    OrderLog::addToLog($paymentData['CustomField1'], $order_id, '新增訂單');
                }

                return true;
            }

        }

        return false;
    }

    public static function addOtherOrder($order_id, $user_id, $created_at){

        $checkOrder = Order::where('order_id', $order_id)->first();
        if (!isset($checkOrder)) {
            $order = new Order;
            $order->order_id = $order_id;
            $order->user_id = $user_id;
            $order->order_date = $created_at;
            $order->service_name = 'VIP';
            $order->payment = 'cc_monthly_payment';
            $order->payment_flow = 'newebpay';
            $order->payment_type = 'Credit_CreditCard';
            $order->amount = 888;
            $order->created_at = Carbon::now();
            $saved = $order->save();
            if($saved) {
                OrderLog::addToLog($user_id, $order_id, '新增訂單');
            }
            return true;
        }

        return false;
    }

    public static function addFunPointPayOrder($order_id, $order_expire_date = null){

        if($order_id != '' && substr($order_id,0,2) == 'SG') {
            //正式訂單查詢
            if(EnvironmentService::isLocalOrTestMachine()){
                $envStr = '_test';
            }
            else{
                $envStr = '';
            }
            $envStr = '';
            $ecpay = new \App\Services\ECPay_AllInOne();
            $ecpay->MerchantID = Config::get('funpoint.payment'.$envStr.'.MerchantID');
            $ecpay->ServiceURL = Config::get('funpoint.payment'.$envStr.'.OrderQueryURL');
            $ecpay->HashIV = Config::get('funpoint.payment'.$envStr.'.HashIV');
            $ecpay->HashKey = Config::get('funpoint.payment'.$envStr.'.HashKey');
            $ecpay->Query = [
                'MerchantTradeNo' => $order_id,
                'TimeStamp' => time()
            ];
            $paymentData = $ecpay->QueryTradeInfo();

            $ecpay->ServiceURL = Config::get('funpoint.payment'.$envStr.'.ServiceURL');//定期定額查詢
            $paymentPeriodInfo = $ecpay->QueryPeriodCreditCardTradeInfo();

            //check order exist
            $checkOrder = Order::where('order_id', $order_id)->first();
            if(!$checkOrder && isset($paymentData) && $paymentData['TradeStatus']==1) {
                if($paymentData['CustomField4']==''){
                    $service_name = 'VIP';
                }else{
                    $service_name = $paymentData['CustomField4'];
                }
                if($paymentData['CustomField3']==''){
                    $payment = 'cc_monthly_payment';//舊訂單歸類定期定額月付
                }else{
                    $payment = $paymentData['CustomField3'];
                }
                //insert new order
                $order = new Order;
                $order->order_id = $order_id;
                $order->user_id = $paymentData['CustomField1'];
                $order->business_id = $paymentData['MerchantID'];
                $order->order_date = $paymentData['PaymentDate'];

                $order->service_name = $service_name;
                $order->payment_flow = 'funpoint';
                $order->payment = $payment;
                $order->payment_type = $paymentData['PaymentType'];
                $dateArray = array();
                $dateFailArray = array();
                if($paymentPeriodInfo['ExecLog']==''){
                    $dd = str_replace('%20', ' ', $paymentData['PaymentDate']);
                    $dd = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $dd)->toDateTimeString();
                    array_push($dateArray, array($dd));

                }else{
                    foreach($paymentPeriodInfo['ExecLog'] as $data){
                        $dd = str_replace('%20', ' ', $data['process_date']);
                        $dd = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $dd)->toDateTimeString();
                        if($data['RtnCode']==1) {
                            array_push($dateArray, array($dd));
                        }else{
                            array_push($dateFailArray, array($dd));
                        }
                    }

                    if(count($dateFailArray)==0){
                        $dateFailArray = null;
                    }else{
                        $dateFailArray = json_encode($dateFailArray);
                    }

                    $temp = end($dateArray);
                    $lastProcessDate = Carbon::parse($temp[0]);

                    if($paymentPeriodInfo['card4no']) {
                        $order->card4no = $paymentPeriodInfo['card4no'];
                        $order->card6no = $paymentPeriodInfo['card6no'];
                    }

                }
                $order->pay_date = json_encode($dateArray);
                $order->pay_fail = $dateFailArray;

                $PaymentDate =str_replace('%20', ' ', $paymentData['PaymentDate']);
                $PaymentDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $PaymentDate);

                if($order_expire_date=='') {
                    if ($payment == 'one_quarter_payment') {
                        $order->order_expire_date = $PaymentDate->addMonthsNoOverflow(3);
                    } elseif ($payment == 'one_month_payment') {
                        $order->order_expire_date = $PaymentDate->addMonthsNoOverflow(1);
                    } elseif ($payment == 'cc_quarterly_payment' && $lastProcessDate != '' && $paymentPeriodInfo['ExecStatus'] == 0) {
                        //ExecStatus=0 定期定額取消直接補上到期日
                        $order->order_expire_date = $lastProcessDate->addMonthsNoOverflow(3);
                        //到期日加上剩餘天數
                        if($paymentData['CustomField2'] != '' && $paymentData['CustomField2']>0 && ($service_name == 'VIP' || $service_name == 'hideOnline')){
                            $temp_day = $order->order_expire_date;
                            $order->order_expire_date = $temp_day->addDays($paymentData['CustomField2']);
                        }
                    } elseif ($payment == 'cc_monthly_payment' && $lastProcessDate != '' && $paymentPeriodInfo['ExecStatus'] == 0) {
                        //ExecStatus=0 定期定額取消直接補上到期日
                        $order->order_expire_date = $lastProcessDate->addMonthsNoOverflow(1);
                        //到期日加上剩餘天數
                        if($paymentData['CustomField2'] != '' && $paymentData['CustomField2']>0 && ($service_name == 'VIP' || $service_name == 'hideOnline')){
                            $temp_day = $order->order_expire_date;
                            $order->order_expire_date = $temp_day->addDays($paymentData['CustomField2']);
                        }
                    } elseif ($payment == '' && $paymentPeriodInfo['ExecStatus'] == 0) {
                        //舊式定期定額皆為月繳
                        //ExecStatus=0 定期定額取消直接補上到期日
                        $order->order_expire_date = $lastProcessDate->addMonthsNoOverflow(1);
                    }
                }else{
                    $order->order_expire_date = $order_expire_date;
                }

                $order->amount = $paymentData['TradeAmt'];
                $order->ExecStatus = $paymentPeriodInfo['ExecStatus']??null;
                $order->created_at = Carbon::now();
                if($paymentData['CustomField2'] != '' && ($paymentData['CustomField4'] == 'VIP' || $paymentData['CustomField4'] == 'hideOnline')) {
                    $order->remain_days = $paymentData['CustomField2'];
                }

                try {
                    $saved = $order->save();
                } catch (\Exception $e) {
                    Log::error($e);
                    \Sentry::captureMessage("FunPoint 訂單異常。" . $e->getMessage());
                    $lineNotify = new LineNotify;
                    $lineNotify->sendLineNotifyMessage("FunPoint 訂單異常。" . $e->getMessage());
                }

                if($saved) {
                    OrderLog::addToLog($paymentData['CustomField1'], $order_id, '新增訂單');
                }

                return true;
            }

        }

        return false;
    }

    public static function updateEcPayOrder($order_id){

        if($order_id != '' && substr($order_id,0,2) == 'SG') {
            //正式綠界訂單查詢
            $ecpay = new \App\Services\ECPay_AllInOne();
            $ecpay->MerchantID = Config::get('ecpay.payment.MerchantID');
            $ecpay->ServiceURL = Config::get('ecpay.payment.OrderQueryURL');
            $ecpay->HashIV = Config::get('ecpay.payment.HashIV');
            $ecpay->HashKey = Config::get('ecpay.payment.HashKey');
            $ecpay->Query = [
                'MerchantTradeNo' => $order_id,
                'TimeStamp' => time()
            ];
            $paymentData = $ecpay->QueryTradeInfo();

            $ecpay->ServiceURL = Config::get('ecpay.payment.ServiceURL');//定期定額查詢
            $paymentPeriodInfo = $ecpay->QueryPeriodCreditCardTradeInfo();

            //check order exist
            $checkOrder = Order::where('order_id', $order_id)->first();
            if($checkOrder && $paymentData['TradeStatus']==1){
                if($paymentData['CustomField3']==''){
                    $payment = 'cc_monthly_payment';//舊訂單歸類定期定額月付
                }else{
                    $payment = $paymentData['CustomField3'];
                }
                //更新扣款日
                $dateArray = array();
                $dateFailArray = array();
                $lastProcessDate = '';
                $card4no = '';
                $card6no = '';
                if($paymentPeriodInfo['ExecLog']==''){
                    $dd = str_replace('%20', ' ', $paymentData['PaymentDate']);
                    $dd = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $dd)->toDateTimeString();
                    array_push($dateArray, array($dd));

                }else{
                    foreach($paymentPeriodInfo['ExecLog'] as $data){
                        $dd = str_replace('%20', ' ', $data['process_date']);
                        $dd = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $dd)->toDateTimeString();
                        if($data['RtnCode']==1) {
                            array_push($dateArray, array($dd));
                        }else{
                            array_push($dateFailArray, array($dd));
                        }
                    }

                    if ($paymentPeriodInfo['ExecStatus'] == 1) {
                        $last = last($paymentPeriodInfo['ExecLog']);
                        //最後一次扣款失敗
                        if ($last['RtnCode'] != 1 && $paymentData['CustomField4'] == 'VVIP') {
                            //寫入訂單付款失敗通知紀錄 OrderPayFailNotify for VVIP
                            $lastPayFailDate = str_replace('%20', ' ', $last['process_date']);
                            $lastPayFailDate = Carbon::createFromFormat('Y/m/d H:i:s', $lastPayFailDate);
                            if (!OrderPayFailNotify::isExists($paymentData['CustomField1'], $order_id, $lastPayFailDate)) {
                                OrderPayFailNotify::addToData($paymentData['CustomField1'], $order_id, $lastPayFailDate);
                                OrderLog::addToLog($paymentData['CustomField1'], $order_id, '經由訂單更新檢查 VVIP 扣款失敗，加入提醒通知');
                            }
                        }
                    }

                    if(count($dateFailArray)==0){
                        $dateFailArray = null;
                    }else{
                        $dateFailArray = json_encode($dateFailArray);
                    }

                    $temp = end($dateArray);
                    $lastProcessDate = Carbon::parse($temp[0]);

                    if($paymentPeriodInfo['card4no']) {
                        $card4no = $paymentPeriodInfo['card4no'];
                        $card6no = $paymentPeriodInfo['card6no'];
                    }

                }
                //更新到期日
                $order_expire_date = null;
                $PaymentDate =str_replace('%20', ' ', $paymentData['PaymentDate']);
                $PaymentDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $PaymentDate);
                if($payment=='one_quarter_payment'){
                    $order_expire_date = $PaymentDate->addMonthsNoOverflow(3);
                }elseif($payment=='one_month_payment'){
                    $order_expire_date = $PaymentDate->addMonthsNoOverflow(1);
                }elseif($payment=='cc_quarterly_payment' && $lastProcessDate != '' && $paymentPeriodInfo['ExecStatus'] == 0){
                    //ExecStatus=0 定期定額取消直接補上到期日
                    $order_expire_date = $lastProcessDate->addMonthsNoOverflow(3);
                    //到期日加上剩餘天數
                    if($paymentData['CustomField2'] != '' && $paymentData['CustomField2'] >0 && ($paymentData['CustomField4'] == 'VIP' || $paymentData['CustomField4'] == 'hideOnline')){
                        $order_expire_date = $order_expire_date->addDays($paymentData['CustomField2']);
                    }
                }elseif($payment=='cc_monthly_payment' && $lastProcessDate != '' && $paymentPeriodInfo['ExecStatus'] == 0){
                    //ExecStatus=0 定期定額取消直接補上到期日
                    $order_expire_date = $lastProcessDate->addMonthsNoOverflow(1);
                    //到期日加上剩餘天數
                    if($paymentData['CustomField2'] != '' && $paymentData['CustomField2'] >0 && ($paymentData['CustomField4'] == 'VIP' || $paymentData['CustomField4'] == 'hideOnline')){
                        $order_expire_date = $order_expire_date->addDays($paymentData['CustomField2']);
                    }
                }elseif($payment=='' && $paymentPeriodInfo['ExecStatus'] == 0){
                    //舊式定期定額皆為月繳
                    //ExecStatus=0 定期定額取消直接補上到期日
                    $order_expire_date = $lastProcessDate->addMonthsNoOverflow(1);
                }

                $update = Order::where('order_id', $order_id)->update([
                    'business_id' => $paymentData['MerchantID'],
                    'order_expire_date' => $order_expire_date,
                    'pay_date' => json_encode($dateArray),
                    'pay_fail' => $dateFailArray,
                    'card4no' => $card4no,
                    'card6no' => $card6no,
                    'ExecStatus' => $paymentPeriodInfo['ExecStatus']??null
                ]);
                if($update) {
                    OrderLog::addToLog($paymentData['CustomField1'], $order_id, '更新訂單');
                }

            }

        }

        return false;
    }

    public static function updateFunPointPayOrder($order_id){

        if($order_id != '' && substr($order_id,0,2) == 'SG') {
            //正式訂單查詢
            $ecpay = new \App\Services\ECPay_AllInOne();
            $ecpay->MerchantID = Config::get('funpoint.payment.MerchantID');
            $ecpay->ServiceURL = Config::get('funpoint.payment.OrderQueryURL');
            $ecpay->HashIV = Config::get('funpoint.payment.HashIV');
            $ecpay->HashKey = Config::get('funpoint.payment.HashKey');
            $ecpay->Query = [
                'MerchantTradeNo' => $order_id,
                'TimeStamp' => time()
            ];
            $paymentData = $ecpay->QueryTradeInfo();

            $ecpay->ServiceURL = Config::get('funpoint.payment.ServiceURL');//定期定額查詢
            $paymentPeriodInfo = $ecpay->QueryPeriodCreditCardTradeInfo();

            //check order exist
            $checkOrder = Order::where('order_id', $order_id)->first();
            if($checkOrder && $paymentData['TradeStatus']==1){
                if($paymentData['CustomField3']==''){
                    $payment = 'cc_monthly_payment';//舊訂單歸類定期定額月付
                }else{
                    $payment = $paymentData['CustomField3'];
                }
                //更新扣款日
                $dateArray = array();
                $dateFailArray = array();
                $lastProcessDate = '';
                $card4no = '';
                $card6no = '';
                if($paymentPeriodInfo['ExecLog']==''){
                    $dd = str_replace('%20', ' ', $paymentData['PaymentDate']);
                    $dd = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $dd)->toDateTimeString();
                    array_push($dateArray, array($dd));

                }else{
                    foreach($paymentPeriodInfo['ExecLog'] as $data){
                        $dd = str_replace('%20', ' ', $data['process_date']);
                        $dd = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $dd)->toDateTimeString();
                        if($data['RtnCode']==1) {
                            array_push($dateArray, array($dd));
                        }else{
                            array_push($dateFailArray, array($dd));
                        }
                    }

                    if ($paymentPeriodInfo['ExecStatus'] == 1) {
                        $last = last($paymentPeriodInfo['ExecLog']);
                        //最後一次扣款失敗
                        if ($last['RtnCode'] != 1 && $paymentData['CustomField4'] == 'VVIP') {
                            //寫入訂單付款失敗通知紀錄 OrderPayFailNotify for VVIP
                            $lastPayFailDate = str_replace('%20', ' ', $last['process_date']);
                            $lastPayFailDate = Carbon::createFromFormat('Y/m/d H:i:s', $lastPayFailDate);
                            if (!OrderPayFailNotify::isExists($paymentData['CustomField1'], $order_id, $lastPayFailDate)) {
                                OrderPayFailNotify::addToData($paymentData['CustomField1'], $order_id, $lastPayFailDate);
                                OrderLog::addToLog($paymentData['CustomField1'], $order_id, '經由訂單更新檢查 VVIP 扣款失敗，加入提醒通知');
                            }
                        }
                    }

                    if(count($dateFailArray)==0){
                        $dateFailArray = null;
                    }else{
                        $dateFailArray = json_encode($dateFailArray);
                    }

                    $temp = end($dateArray);
                    $lastProcessDate = Carbon::parse($temp[0]);

                    if($paymentPeriodInfo['card4no']) {
                        $card4no = $paymentPeriodInfo['card4no'];
                        $card6no = $paymentPeriodInfo['card6no'];
                    }

                }
                //更新到期日
                $order_expire_date = null;
                $PaymentDate =str_replace('%20', ' ', $paymentData['PaymentDate']);
                $PaymentDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $PaymentDate);
                if($payment=='one_quarter_payment'){
                    $order_expire_date = $PaymentDate->addMonthsNoOverflow(3);
                }elseif($payment=='one_month_payment'){
                    $order_expire_date = $PaymentDate->addMonthsNoOverflow(1);
                }elseif($payment=='cc_quarterly_payment' && $lastProcessDate != '' && $paymentPeriodInfo['ExecStatus'] == 0){
                    //ExecStatus=0 定期定額取消直接補上到期日
                    $order_expire_date = $lastProcessDate->addMonthsNoOverflow(3);
                    if($paymentData['CustomField2'] != '' && ($paymentData['CustomField4'] == 'VIP' || $paymentData['CustomField4'] == 'hideOnline')){
                        $order_expire_date = $order_expire_date->addDays($paymentData['CustomField2']);
                    }
                }elseif($payment=='cc_monthly_payment' && $lastProcessDate != '' && $paymentPeriodInfo['ExecStatus'] == 0){
                    //ExecStatus=0 定期定額取消直接補上到期日
                    $order_expire_date = $lastProcessDate->addMonthsNoOverflow(1);
                    if($paymentData['CustomField2'] != '' && ($paymentData['CustomField4'] == 'VIP' || $paymentData['CustomField4'] == 'hideOnline')){
                        $order_expire_date = $order_expire_date->addDays($paymentData['CustomField2']);
                    }
                }elseif($payment=='' && $paymentPeriodInfo['ExecStatus'] == 0){
                    //舊式定期定額皆為月繳
                    //ExecStatus=0 定期定額取消直接補上到期日
                    $order_expire_date = $lastProcessDate->addMonthsNoOverflow(1);
                }

                $update = Order::where('order_id', $order_id)->update([
                    'business_id' => $paymentData['MerchantID'],
                    'order_expire_date' => $order_expire_date,
                    'pay_date' => json_encode($dateArray),
                    'pay_fail' => $dateFailArray,
                    'card4no' => $card4no,
                    'card6no' => $card6no,
                    'ExecStatus' => $paymentPeriodInfo['ExecStatus']??null
                ]);
                if($update) {
                    OrderLog::addToLog($paymentData['CustomField1'], $order_id, '更新訂單');
                }
            }

        }

        return false;
    }

    public static function findByOrderId($order_id)
    {
        return Order::where('order_id', $order_id)->first();
    }

    public static function orderCheckByUserIdAndServiceName($user_id, $service_name)
    {
        //此功能只檢查需要更新的訂單
        $order = '';
        if($user_id != '' && $service_name != '') {
            $order = Order::where('service_name', $service_name)
                ->where('user_id', $user_id)
                ->where('payment', 'like', 'cc_%')
                ->whereRaw('LENGTH(order_id) = 12')
                ->get();
        }
        if($order && count($order)>0){
            $now = Carbon::now();
            foreach ($order as $row){
                $update = false;

                if($row->ExecStatus==''){
                    $update = true;
                }

                if ($row->payment == 'cc_quarterly_payment') {
                    $periodRemained = 92;
                } else {
                    $periodRemained = 30;
                }
                //取本機訂單最後扣款日
                $lastProcessDate = last(json_decode($row->pay_date));
                $theActualLastProcessDate = is_string($lastProcessDate[0]) ? Carbon::parse($lastProcessDate[0]) : $lastProcessDate[0];

                $lastPayFailDate = '';
                $theActualLastPayFailDateDate = '';
                if($row->pay_fail != '') {
                    $lastPayFailDate = last(json_decode($row->pay_fail));
                    $theActualLastPayFailDateDate = is_string($lastPayFailDate[0]) ? Carbon::parse($lastPayFailDate[0]) : $lastPayFailDate[0];
                }
                if ( (($now->diffInDays($theActualLastProcessDate) > $periodRemained && ($now->diffInDays($theActualLastPayFailDateDate) > $periodRemained && $row->pay_fail != '')) ||
                        ($now->diffInDays($theActualLastProcessDate) > $periodRemained && $row->pay_fail == '')) &&
                    $row->ExecStatus == 1) {
                    $update = true;
                }

                //執行更新動作
                if ($update == true && $row->payment_flow == 'ecpay'){
                    Order::updateEcPayOrder($row->order_id);
                }
                elseif ($update == true && $row->payment_flow == 'funpoint'){
                    Order::updateFunPointPayOrder($row->order_id);
                }
            }
        }
        return false;
    }

    public static function isOverduePayOrder($order_id)
    {
        $now = Carbon::now();
        $order = Order::where('order_id', $order_id)->first();
        if ($order->payment == 'cc_quarterly_payment') {
            $periodRemained = 92;
        } else {
            $periodRemained = 30;
        }
        //取本機訂單最後扣款日
        $lastProcessDate = last(json_decode($order->pay_date));
        $theActualLastProcessDate = is_string($lastProcessDate[0]) ? Carbon::parse($lastProcessDate[0]) : $lastProcessDate[0];
        if ($now->diffInDays($theActualLastProcessDate) > $periodRemained && $order->ExecStatus == 1) {
            return true;
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getQueueableRelations()
    {
        // TODO: Implement getQueueableRelations() method.
    }

    /**
     * @inheritDoc
     */
    public function resolveChildRouteBinding($childType, $value, $field)
    {
        // TODO: Implement resolveChildRouteBinding() method.
    }

}
