<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPayFailNotify extends Model
{
    use HasFactory;

    protected $table = 'order_pay_fail_notify';

    protected $fillable = [
        'order_id',
        'user_id',
        'last_pay_fail_date',
        'status'
    ];

    public static function addToData($user_id, $order_id, $last_pay_fail_date) {
        $data = new OrderPayFailNotify();
        $data->user_id = $user_id;
        $data->order_id = $order_id;
        $data->last_pay_fail_date = $last_pay_fail_date;
        $data->save();
    }

    public static function isExists($user_id, $order_id, $last_pay_fail_date) {
        return OrderPayFailNotify::where('user_id', $user_id)
            ->where('order_id', $order_id)
            ->where('last_pay_fail_date', $last_pay_fail_date)
            ->exists();
    }

}
