<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentFlowChoose extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payment_flow_choose';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'payment_text',
        'payment',
        'status'
    ];

    //付款方式
    const PAYMENT = [
        'funpoint',
        'ecpay',
    ];

}
