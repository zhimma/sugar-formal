<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PaymentGetQrcodeLog extends Model
{
//    use HasFactory;

    protected $table = 'payment_get_barcode_log';

    public static function codeNoPaidGetId($uid, $service_name, $payment_type, $payment)
    {
        if($payment != '' && $service_name != '') {
            $currentPaymentQrcodeLog = PaymentGetQrcodeLog::where('user_id', $uid)
                ->where('service_name', $service_name)
                ->where('payment_type', 'like', $payment_type.'%')
                ->where('payment', $payment)
                ->orderBy('created_at', 'desc')
                ->first();
            if ($currentPaymentQrcodeLog) {
                $checkIsPaid = Order::findByOrderId($currentPaymentQrcodeLog->order_id);
                if (!$checkIsPaid && $currentPaymentQrcodeLog->ExpireDate > Carbon::now()) {
                    return $currentPaymentQrcodeLog->id;
                }
            }
        }
        return false;
    }

    public static function findDataById($id)
    {
        return PaymentGetQrcodeLog::where('id', $id)->orderBy('created_at', 'desc')->first();
    }
}
