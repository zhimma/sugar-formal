<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
    ];

    public function users(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function addEcPayOrder($order_id, $order_expire_date = ''){

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
            if(!$checkOrder && isset($paymentData)) {
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
                        $dd = str_replace('%20', ' ', $data['process_date']);
                        $dd = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $dd)->toDateTimeString();
                        array_push($dateArray, array($dd));
                    }

                    $last = last($paymentPeriodInfo['ExecLog']);
                    $lastProcessDate = str_replace('%20', ' ', $last['process_date']);
                    $lastProcessDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $lastProcessDate);
                    $lastProcessDateDiffDays = $lastProcessDate->diffInDays(Carbon::now());
                }
                $order->pay_date = json_encode($dateArray);

                $PaymentDate =str_replace('%20', ' ', $paymentData['PaymentDate']);
                $PaymentDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $PaymentDate);

                if($order_expire_date=='') {
                    if ($payment == 'one_quarter_payment') {
                        $order->order_expire_date = $PaymentDate->addMonthsNoOverflow(3);
                    } elseif ($payment == 'one_month_payment') {
                        $order->order_expire_date = $PaymentDate->addMonthsNoOverflow(1);
                    } elseif ($payment == 'cc_quarterly_payment' && $lastProcessDate != '' && $lastProcessDateDiffDays != '') {
                        if ($lastProcessDateDiffDays > 90) {
                            $order->order_expire_date = $lastProcessDate->addMonthsNoOverflow(3);
                        }
                    } elseif ($payment == 'cc_monthly_payment' && $lastProcessDate != '' && $lastProcessDateDiffDays != '') {
                        if ($lastProcessDateDiffDays > 30) {
                            $order->order_expire_date = $lastProcessDate->addMonthsNoOverflow(1);
                        }
                    } elseif ($payment == '') {
                        if ($lastProcessDateDiffDays > 30) {
                            $order->order_expire_date = $lastProcessDate->addMonthsNoOverflow(1);
                        }
                    }
                }else{
                    $order->order_expire_date = $order_expire_date;
                }

                $order->amount = $paymentData['TradeAmt'];
                $order->created_at = Carbon::now();
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
            if($checkOrder){
                if($paymentData['CustomField3']==''){
                    $payment = 'cc_monthly_payment';//舊訂單歸類定期定額月付
                }else{
                    $payment = $paymentData['CustomField3'];
                }
                //更新扣款日
                $dateArray = array();
                $lastProcessDate = '';
                $lastProcessDateDiffDays = '';
                if($paymentPeriodInfo['ExecLog']==''){
                    $dd = str_replace('%20', ' ', $paymentData['PaymentDate']);
                    $dd = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $dd)->toDateTimeString();
                    array_push($dateArray, array($dd));
                }else{
                    foreach($paymentPeriodInfo['ExecLog'] as $data){
                        $dd = str_replace('%20', ' ', $data['process_date']);
                        $dd = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $dd)->toDateTimeString();
                        array_push($dateArray, array($dd));
                    }

                    $last = last($paymentPeriodInfo['ExecLog']);
                    $lastProcessDate = str_replace('%20', ' ', $last['process_date']);
                    $lastProcessDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $lastProcessDate);
                    $lastProcessDateDiffDays = $lastProcessDate->diffInDays(Carbon::now());
                }
                //更新到期日
                $order_expire_date = null;
                $PaymentDate =str_replace('%20', ' ', $paymentData['PaymentDate']);
                $PaymentDate = \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $PaymentDate);
                if($payment=='one_quarter_payment'){
                    $order_expire_date = $PaymentDate->addMonthsNoOverflow(3);
                }elseif($payment=='one_month_payment'){
                    $order_expire_date = $PaymentDate->addMonthsNoOverflow(1);
                }elseif($payment=='cc_quarterly_payment' && $lastProcessDate != '' && $lastProcessDateDiffDays !=''){
                    if($lastProcessDateDiffDays>90){
                        $order_expire_date = $lastProcessDate->addMonthsNoOverflow(3);
                    }
                }elseif($payment=='cc_monthly_payment' && $lastProcessDate != '' && $lastProcessDateDiffDays !=''){
                    if($lastProcessDateDiffDays>30){
                        $order_expire_date = $lastProcessDate->addMonthsNoOverflow(1);
                    }
                }elseif($payment==''){
                    if($lastProcessDateDiffDays>30){
                        $order_expire_date = $lastProcessDate->addMonthsNoOverflow(1);
                    }
                }

                Order::where('order_id', $order_id)->update(['order_expire_date' => $order_expire_date, 'pay_date' => json_encode($dateArray)]);
            }

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
