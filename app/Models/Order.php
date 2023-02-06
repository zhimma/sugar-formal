<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use App\Services\EnvironmentService;

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
                $order->order_date = $paymentData['PaymentDate'];

                $order->service_name = $service_name;
                $order->payment_flow = 'ecpay';
                $order->payment = $payment;
                $order->payment_type = $paymentData['PaymentType'];
                $dateArray = array();
                if($paymentPeriodInfo['ExecLog']==''){
                    $dd = str_replace('%20', ' ', $paymentData['PaymentDate']);
                    $dd = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $dd)->toDateTimeString();
                    array_push($dateArray, array($dd));

                }else{
                    foreach($paymentPeriodInfo['ExecLog'] as $data){
                        if($data['RtnCode']==1) {
                            $dd = str_replace('%20', ' ', $data['process_date']);
                            $dd = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $dd)->toDateTimeString();
                            array_push($dateArray, array($dd));
                        }
                    }

                    $last = last($paymentPeriodInfo['ExecLog']);
                    $lastProcessDate = str_replace('%20', ' ', $last['process_date']);
                    $lastProcessDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $lastProcessDate);
                    $lastProcessDateDiffDays = $lastProcessDate->diffInDays(Carbon::now());

                    if($paymentPeriodInfo['card4no']) {
                        $order->card4no = $paymentPeriodInfo['card4no'];
                        $order->card6no = $paymentPeriodInfo['card6no'];
                    }

                }
                $order->pay_date = json_encode($dateArray);



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
                        if($paymentData['CustomField2'] != '' && $paymentData['CustomField2']>0 && ($service_name == 'VIP' || $service_name == 'hideOnline')){
                            $temp_day = $order->order_expire_date;
                            $order->order_expire_date = $temp_day->addDays($paymentData['CustomField2']);
                        }
                    } elseif ($payment == 'cc_monthly_payment' && $lastProcessDate != '' && $paymentPeriodInfo['ExecStatus'] == 0) {
                        //ExecStatus=0 定期定額取消直接補上到期日
                        $order->order_expire_date = $lastProcessDate->addMonthsNoOverflow(1);
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
                $order->created_at = Carbon::now();
                if($paymentData['CustomField2'] != '' && ($paymentData['CustomField4'] == 'VIP' || $paymentData['CustomField4'] == 'hideOnline')) {
                    $order->remain_days = $paymentData['CustomField2'];
                }
                $order->save();

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
            $order->save();
            return true;
        }

        return false;
    }

    public static function addFunPointPayOrder($order_id, $order_expire_date = null){

        if($order_id != '' && substr($order_id,0,2) == 'SG') {
            //正式訂單查詢
            $ecpay = new \App\Services\ECPay_AllInOne();
            $ecpay->MerchantID = '1010336';
            $ecpay->ServiceURL = 'https://payment.funpoint.com.tw/Cashier/QueryTradeInfo/V5';
            $ecpay->HashIV = '7h5B9EIcEWEFIkPW';
            $ecpay->HashKey = 'xcmzAyKJM7I8gssu';
            $ecpay->Query = [
                'MerchantTradeNo' => $order_id,
                'TimeStamp' => time()
            ];
            $paymentData = $ecpay->QueryTradeInfo();

            $ecpay->ServiceURL = 'https://payment.funpoint.com.tw/Cashier/QueryCreditCardPeriodInfo';//定期定額查詢
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
                $order->order_date = $paymentData['PaymentDate'];

                $order->service_name = $service_name;
                $order->payment_flow = 'funpoint';
                $order->payment = $payment;
                $order->payment_type = $paymentData['PaymentType'];
                $dateArray = array();
                if($paymentPeriodInfo['ExecLog']==''){
                    $dd = str_replace('%20', ' ', $paymentData['PaymentDate']);
                    $dd = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $dd)->toDateTimeString();
                    array_push($dateArray, array($dd));

                }else{
                    foreach($paymentPeriodInfo['ExecLog'] as $data){
                        if($data['RtnCode']==1) {
                            $dd = str_replace('%20', ' ', $data['process_date']);
                            $dd = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $dd)->toDateTimeString();
                            array_push($dateArray, array($dd));
                        }
                    }

                    $last = last($paymentPeriodInfo['ExecLog']);
                    $lastProcessDate = str_replace('%20', ' ', $last['process_date']);
                    $lastProcessDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $lastProcessDate);
                    $lastProcessDateDiffDays = $lastProcessDate->diffInDays(Carbon::now());

                    if($paymentPeriodInfo['card4no']) {
                        $order->card4no = $paymentPeriodInfo['card4no'];
                        $order->card6no = $paymentPeriodInfo['card6no'];
                    }

                }
                $order->pay_date = json_encode($dateArray);

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
                        if($paymentData['CustomField2'] != '' && $paymentData['CustomField2']>0 && ($service_name == 'VIP' || $service_name == 'hideOnline')){
                            $temp_day = $order->order_expire_date;
                            $order->order_expire_date = $temp_day->addDays($paymentData['CustomField2']);
                        }
                    } elseif ($payment == 'cc_monthly_payment' && $lastProcessDate != '' && $paymentPeriodInfo['ExecStatus'] == 0) {
                        //ExecStatus=0 定期定額取消直接補上到期日
                        $order->order_expire_date = $lastProcessDate->addMonthsNoOverflow(1);
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
                $order->created_at = Carbon::now();
                if($paymentData['CustomField2'] != '' && ($paymentData['CustomField4'] == 'VIP' || $paymentData['CustomField4'] == 'hideOnline')) {
                    $order->remain_days = $paymentData['CustomField2'];
                }
                $order->save();

                return true;
            }

        }

        return false;
    }

    public static function updateEcPayOrder($order_id){

        if($order_id != '' && substr($order_id,0,2) == 'SG') {
            //正式綠界訂單查詢
            $ecpay = new \App\Services\ECPay_AllInOne();
            $ecpay->MerchantID = '3137610';
            $ecpay->ServiceURL = 'https://payment.ecpay.com.tw/Cashier/QueryTradeInfo/V5';
            $ecpay->HashIV = 'KOBKiDuvxIvjCSBz';
            $ecpay->HashKey = 'BOerb1FcOOjccN8o';
            $ecpay->Query = [
                'MerchantTradeNo' => $order_id,
                'TimeStamp' => time()
            ];
            $paymentData = $ecpay->QueryTradeInfo();

            $ecpay->ServiceURL = 'https://payment.ecpay.com.tw/Cashier/QueryCreditCardPeriodInfo';//定期定額查詢
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
                $lastProcessDate = '';
                $lastProcessDateDiffDays = '';
                $card4no = '';
                $card6no = '';
                if($paymentPeriodInfo['ExecLog']==''){
                    $dd = str_replace('%20', ' ', $paymentData['PaymentDate']);
                    $dd = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $dd)->toDateTimeString();
                    array_push($dateArray, array($dd));

                }else{
                    foreach($paymentPeriodInfo['ExecLog'] as $data){
                        if($data['RtnCode']==1) {
                            $dd = str_replace('%20', ' ', $data['process_date']);
                            $dd = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $dd)->toDateTimeString();
                            array_push($dateArray, array($dd));
                        }
                    }

                    $last = last($paymentPeriodInfo['ExecLog']);
                    $lastProcessDate = str_replace('%20', ' ', $last['process_date']);
                    $lastProcessDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $lastProcessDate);
                    $lastProcessDateDiffDays = $lastProcessDate->diffInDays(Carbon::now());

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

                Order::where('order_id', $order_id)->update(['order_expire_date' => $order_expire_date, 'pay_date' => json_encode($dateArray), 'card4no' => $card4no, 'card6no' => $card6no]);

            }

        }

        return false;
    }

    public static function updateFunPointPayOrder($order_id){

        if($order_id != '' && substr($order_id,0,2) == 'SG') {
            //正式訂單查詢
            $ecpay = new \App\Services\ECPay_AllInOne();
            $ecpay->MerchantID = '1010336';
            $ecpay->ServiceURL = 'https://payment.funpoint.com.tw/Cashier/QueryTradeInfo/V5';
            $ecpay->HashIV = '7h5B9EIcEWEFIkPW';
            $ecpay->HashKey = 'xcmzAyKJM7I8gssu';
            $ecpay->Query = [
                'MerchantTradeNo' => $order_id,
                'TimeStamp' => time()
            ];
            $paymentData = $ecpay->QueryTradeInfo();

            $ecpay->ServiceURL = 'https://payment.funpoint.com.tw/Cashier/QueryCreditCardPeriodInfo';//定期定額查詢
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
                $lastProcessDate = '';
                $lastProcessDateDiffDays = '';
                $card4no = '';
                $card6no = '';
                if($paymentPeriodInfo['ExecLog']==''){
                    $dd = str_replace('%20', ' ', $paymentData['PaymentDate']);
                    $dd = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $dd)->toDateTimeString();
                    array_push($dateArray, array($dd));

                }else{
                    foreach($paymentPeriodInfo['ExecLog'] as $data){
                        if($data['RtnCode']==1) {
                            $dd = str_replace('%20', ' ', $data['process_date']);
                            $dd = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $dd)->toDateTimeString();
                            array_push($dateArray, array($dd));
                        }
                    }

                    $last = last($paymentPeriodInfo['ExecLog']);
                    $lastProcessDate = str_replace('%20', ' ', $last['process_date']);
                    $lastProcessDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $lastProcessDate);
                    $lastProcessDateDiffDays = $lastProcessDate->diffInDays(Carbon::now());

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

                Order::where('order_id', $order_id)->update(['order_expire_date' => $order_expire_date, 'pay_date' => json_encode($dateArray), 'card4no' => $card4no, 'card6no' => $card6no]);

            }

        }

        return false;
    }

    public static function findByOrderId($order_id)
    {
        return Order::where('order_id', $order_id)->first();
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
