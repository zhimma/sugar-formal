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
            // 檢查重複升級
            if(ValueAddedServiceLog::getLatestLog($member_id)->order_id == $order_id){
                ValueAddedServiceLog::addToLog($member_id, $service_name,'Upgrade duplicated.', $order_id, $txn_id, 0);
                return 0;
            }
            // 舊資料更新 從原expiry計算
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
        ValueAddedServiceLog::addToLog($member_id, $service_name,'Upgrade, payment: ' . $payment . ', service: ' . $service_name, $order_id, $txn_id, 0);
    }

    public static function findByIdAndServiceNameWithDateDesc($member_id,$service_name) {
        return ValueAddedService::where('member_id', $member_id)->where('service_name', $service_name)->orderBy('created_at', 'desc')->first();
    }

    public static function cancel($member_id, $service_name)
    {
        $curUser = User::findById($member_id);
        $user = ValueAddedService::where('member_id', $member_id)
            ->where('service_name', $service_name)
            ->orderBy('created_at', 'desc')->get();

        if($user[0]->expiry == '0000-00-00 00:00:00'){
            // 未設定到期日區間
            // 取得現在時間
            $now = \Carbon\Carbon::now();
            // 從最近一筆 VIP 資料取得資料變更日期
            $latestUpdatedAt = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user[0]->updated_at);
            // 確實複製變數，而不單純用 =，避免出現只將記憶體位置指向 $daysDiff, $baseDate，
            // 造成兩個變數實際上指向同一物件的問題發生
            // 將現在時間做為基準日
            $baseDate = clone $now;
            $daysDiff = clone $now;
            $daysDiff = $daysDiff->diffInDays($latestUpdatedAt);
            // 依照付款類形計算不同的取消當下距預計下一週期扣款日的天數
            if($user[0]->payment == 'cc_quarterly_payment'){
                $periodRemained = 92 - ($daysDiff % 92);
            }else {
                $periodRemained = 30 - ($daysDiff % 30);
            }
            // 基準日加上得出的天數再加 1 (不加 1 到期日會少一天)，即為取消後的到期日
            $expiryDate = $baseDate->addDays($periodRemained + 1);
            /**
             * Debugging codes.
             * $output = new \Symfony\Component\Console\Output\ConsoleOutput();
             * $output->writeln('$daysDiff: ' . $daysDiff);
             * $output->writeln('$periodRemained: ' . $periodRemained);
             * $output->writeln('$expiryDate: ' . $expiryDate);
             */
            // 如果是使用綠界付費，且取消日距預計下次扣款日小於七天，則到期日再加一個週期
            // 3137610: 正式商店編號
            // 2000132: 測試商店編號
            if(($user[0]->business_id == '3137610' || $user[0]->business_id == '2000132') && $now->diffInDays($expiryDate) <= 7) {
                // addMonthsNoOverflow(): 避免如 10/31 加了一個月後變 12/01 的情形出現
                if($user[0]->payment=='cc_quarterly_payment'){
                    $expiryDate = $expiryDate->addMonthsNoOverflow(3);
                }else {
                    $expiryDate = $expiryDate->addMonthNoOverflow(1);
                }
                if($service_name == "hideOnline"){
                    $str = $curUser->name . ' 您好，您已取消本站 付費隱藏 續費。但由於您的扣款時間是每月'. $latestUpdatedAt->day .'號，取消時間低於七個工作天，作業不及。所以本次還是會正常扣款，下一週期就會停止扣款。造成不便敬請見諒。';
                }
            }

            foreach ($user as $u){
                $u->expiry = $expiryDate->startOfDay()->toDateTimeString();
                $u->save();
            }

            ValueAddedServiceLog::addToLog($member_id, $service_name,'Cancelled, expiry: ' . $expiryDate, $user[0]->order_id, $user[0]->txn_id,0);
            return [true, "str"  => $str ?? null];
        }
    }

    public static function removeValueAddedService($member_id, $service_name)
    {
        if($service_name=='hideOnline'){
            User::where('id',$member_id)->update(['is_hide_online' => 0]);
        }

        return ValueAddedService::where('member_id', $member_id)
            ->where('service_name', $service_name)
            ->update(array('active' => 0, 'expiry' => null));
    }

}
