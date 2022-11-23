<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VipExpiryLog extends Model
{
    protected $table = 'member_vip_expiry_log';
    protected $fillable = [
        'vip_log_id',
        'member_id',
        'payment',
        'is_cancel',
        'expire_origin',
        'expiry',
        'remain_days_origin',
        'remain_days',
    ];

    public static function addToLog($member_id, $vipData, $expire_origin, $expire_date, $remain_days_origin, $remain_days) {
        $user = User::where('id', $member_id)->first();
        $payment = $vipData->payment;
        $is_cancel = 0;
        if(($payment=='cc_quarterly_payment' || $payment=='cc_monthly_payment') && ($expire_origin!='0000-00-00 00:00:00' && $expire_origin!=null)){
            $is_cancel = 1;
        }

        $latest_vip_log = $user->getLatestVipLog();
        VipExpiryLog::create([
            'vip_log_id'=>$latest_vip_log->id,
            'member_id'=>$member_id,
            'payment'=>$payment,
            'is_cancel'=>$is_cancel,
            'expire_origin'=>$expire_origin,
            'expiry'=>$expire_date,
            'remain_days_origin'=>$remain_days_origin,
            'remain_days'=>$remain_days,
        ]);
    }
}
