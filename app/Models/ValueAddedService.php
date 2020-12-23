<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;

class ValueAddedService extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'member_value_added_service';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'service_name',
        'business_id',
        'order_id',
        'txn_id',
        'amount',
        'expiry',
        'active',
        'payment',
        'created_at'
    ];

    /*
    |--------------------------------------------------------------------------
    | relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class, 'member_id', 'id');
    }

    public static function getData($member_id,$service_name)
    {
        return ValueAddedService::where('member_id', $member_id)->where('service_name', $service_name)->where('active', 1)->orderBy('created_at', 'desc')->first();
    }

    public static function status($member_id,$service_name)
    {
        $status = ValueAddedService::where('member_id', $member_id)->where('service_name', $service_name)->first();

        if($status == NULL) return 0;

        if($status->active==1){
            if($status->expiry == '0000-00-00 00:00:00' || $status->expiry >= Carbon::now()){
                return 1;
            }
        }
        return 0;
    }

    public static function isPaidOnePayment($member_id,$service_name)
    {
        $status = ValueAddedService::where('member_id', $member_id)->where('service_name', $service_name)->where('payment','like','one_%')->first();

        if($status == NULL) return 0;

        if($status->active==1){
            if($status->expiry >= Carbon::now()){
                return 1;
            }
        }
        return 0;
    }

    public static function isPaidCancelNotOnePayment($member_id,$service_name)
    {
        $status = ValueAddedService::where('member_id', $member_id)->where('service_name', $service_name)->where('payment','like','cc_%')->first();

        if($status == NULL) return 0;

        if($status->active==1){
            if($status->expiry >= Carbon::now()){
                return 1;
            }
        }
        return 0;
    }

    public static function upgrade($member_id, $service_name, $business_id, $order_id, $amount, $txn_id, $active, $payment = null)
    {
        $valueAddedServiceData = ValueAddedService::findByIdAndServiceNameWithDateDesc($member_id,$service_name);

        if(!isset($valueAddedServiceData)){

            //新建資料從當前計算
            if($payment=='one_quarter_payment'){
                $expiry = Carbon::now()->addMonthsNoOverflow(3);
            }else if($payment=='one_month_payment'){
                $expiry = Carbon::now()->addMonthsNoOverflow(1);
            }

            $valueAddedService = new ValueAddedService();
            $valueAddedService->member_id = $member_id;
            $valueAddedService->service_name = $service_name;
            $valueAddedService->txn_id = $txn_id;
            $valueAddedService->business_id = $business_id;
            $valueAddedService->order_id = $order_id;
            $valueAddedService->amount = $amount;
            $valueAddedService->active = $active;
            $valueAddedService->payment = $payment;

            //單次付款到期日
            if(isset($expiry)){
                $valueAddedService->expiry = $expiry;
            }

            $valueAddedService->save();

//            if($service_name=='hideOnline'){
//                User::where('id',$member_id)->update(['is_hide_online' => 1, 'hide_online_time' => Carbon::now()]);
//            }

        }else{

            //舊資料更新 從原expiry計算
            if($payment == 'one_quarter_payment'){
                if($valueAddedServiceData->expiry < Carbon::now()) {
                    $expiry = Carbon::now()->addMonthsNoOverflow(3);
                }else{
                    $expiry = Carbon::createFromFormat('Y-m-d H:i:s', $valueAddedServiceData->expiry)->addMonthsNoOverflow(3);
                }
            }else if($payment == 'one_month_payment'){
                if($valueAddedServiceData->expiry < Carbon::now()) {
                    $expiry = Carbon::now()->addMonths(1);
                }else{
                    $expiry = Carbon::createFromFormat('Y-m-d H:i:s', $valueAddedServiceData->expiry)->addMonthsNoOverflow(1);
                }
            }

            $valueAddedServiceData->order_id = $order_id;
            $valueAddedServiceData->service_name = $service_name;
            $valueAddedServiceData->txn_id = $txn_id;
            $valueAddedServiceData->business_id = $business_id;
            $valueAddedServiceData->amount = $amount;
            $valueAddedServiceData->active = $active;
            $valueAddedServiceData->payment = $payment;

            //單次付款到期日
            if(isset($expiry)){
                $valueAddedServiceData->expiry = $expiry;
            }

            $valueAddedServiceData->save();

//            if($service_name=='hideOnline'){
//                User::where('id',$member_id)->update(['is_hide_online' => 1, 'hide_online_time' => Carbon::now()]);
//            }
        }
    }

    public static function findByIdAndServiceNameWithDateDesc($member_id,$service_name) {
        return ValueAddedService::where('member_id', $member_id)->where('service_name', $service_name)->orderBy('created_at', 'desc')->first();
    }

    public static function cancel($member_id, $service_name)
    {
        //$curUser = User::findById($member_id);
        $user = ValueAddedService::where('member_id', $member_id)
            ->where('service_name', $service_name)
            ->orderBy('created_at', 'desc')->get();

        if($user[0]->expiry == '0000-00-00 00:00:00'){
            $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user[0]->updated_at);
            $day = $date->day;
            $now = \Carbon\Carbon::now();
            $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $now->year.'-'.$now->month.'-'.$day.' 00:00:00');
            if($now->day >= $day){
                // addMonthsNoOverflow(): 避免如 10/31 加了一個月後變 12/01 的情形出現
                if($user[0]->payment=='cc_quarterly_payment'){
                    $nextMonth = $now->addMonthsNoOverflow(3);
                }else {
                    $nextMonth = $now->addMonthsNoOverflow(1);
                }

                $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $nextMonth->year.'-'.$nextMonth->month.'-'.$day.' 00:00:00');
            }
            // 如果是使用綠界付費，且取消日距預計下次扣款日小於七天，則到期日再加一個月
            if($user[0]->business_id == '3137610' && $now->diffInDays($date) <= 7) {

                if($user[0]->payment=='cc_quarterly_payment'){
                    $date = $date->addMonthNoOverflow(3);
                }else {
                    $date = $date->addMonthNoOverflow(1);
                }

            }

            foreach ($user as $u){
                $u->expiry = $date->toDateTimeString();
                $u->save();
            }
            ValueAddedServiceLog::addToLog($member_id, $service_name,'Cancelled ,expiry: ' . $date, $user[0]->order_id, $user[0]->txn_id,0);

            return true;
        }

    }

    public static function removeValueAddedService($member_id, $service_name)
    {
        return ValueAddedService::where('member_id', $member_id)
            ->where('service_name', $service_name)
            ->update(array('active' => 0, 'expiry' => null));
    }

}
