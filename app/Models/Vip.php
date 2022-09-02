<?php

namespace App\Models;

use App\Models\User;
use App\Notifications\MessageEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Notifications\CancelVipEmail;
use App\Notifications\NewVipEmail;
use Carbon\Carbon;
use App\Services\EnvironmentService;

class Vip extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'member_vip';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'txn_id',
        'expiry',
        'remain_days'
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
    

    public static  function allVip(){
        return Vip::select('member_id')->where('active', 1)->get();
    }

    public static function lastid()
    {
        $lid = Vip::orderBy('created_at', 'desc')->first();
        if ($lid == null) return 0;
        return $lid->id + 1;
    }

    public static function status($member_id)
    {
        $status = Vip::where('member_id', $member_id)->first();

        if($status == NULL) return 0;
        return !$status->free;
    }

    public static function upgrade($member_id, $business_id, $order_id, $amount, $txn_id, $active, $free, $payment = null, $transactionType = null, $remain_days=0)
    {
        $vipData = Vip::findByIdWithDateDesc($member_id);
        $logStrQrcode = '';

        if(!isset($vipData)){

            if(str_contains($transactionType, 'CVS') || str_contains($transactionType, 'ATM') || str_contains($transactionType, 'BARCODE')) {
                //check取號資料表
                $checkData = PaymentGetQrcodeLog::where('order_id', $order_id)->first();
                if(isset($checkData)){
                    $logStrQrcode = '(預先給予權限)';
                }
            }
            //新建資料從當前計算
            if($payment=='one_quarter_payment'){
                $expiry = Carbon::now()->addMonthsNoOverflow(3);
            }else if($payment=='one_month_payment'){
                $expiry = Carbon::now()->addMonthsNoOverflow(1);
            }

            $vip = new Vip();
            $vip->member_id = $member_id;
            $vip->txn_id = $txn_id;
            $vip->business_id = $business_id;
            $vip->order_id = $order_id;
            $vip->amount = $amount;
            $vip->active = $active;
            $vip->free = $free;
            $vip->payment = $payment;
            $vip->payment_method = $transactionType; //付款方法
            $vip->remain_days = $remain_days;

            //單次付款到期日
            if(isset($expiry)){
                $vip->expiry = $expiry;
            }else{
                $vip->expiry = '0000-00-00 00:00:00';
            }

            $vip->save();
        }
        else{

            if(str_contains($transactionType, 'CVS') || str_contains($transactionType, 'ATM') || str_contains($transactionType, 'BARCODE')){
                //check取號資料表
                $checkData = PaymentGetQrcodeLog::where('order_id', $order_id)->first();
                $checkDataByUser = PaymentGetQrcodeLog::where('user_id', $member_id)->first();
                if(isset($checkData) && ($vipData->updated_at > $checkData->TradeDate && $vipData->updated_at < $checkData->ExpireDate) && $vipData->active==1 && $vipData->order_id == $order_id){
                    //自動流程判斷
                    //暫時性發放VIP者到期日不變更
                    $expiry = $vipData->expiry;
                    $logStrQrcode = '(期限內完成付款升級)';
                }elseif(isset($checkDataByUser) && ($vipData->updated_at > $checkData->TradeDate && $vipData->updated_at < $checkData->ExpireDate) && $vipData->active==1){
                    //手動給VIP時的判斷 ATM除外
                    //原先只手動到期日者可能會抓不到
                    $expiry = $vipData->expiry;
                    $logStrQrcode = '(期限內完成付款升級/原手動升級者)';
                }
            }elseif($vipData->order_id != $order_id) {
                //舊資料更新 從原expiry計算
                if ($payment == 'one_quarter_payment') {
                    if ($vipData->expiry < Carbon::now()) {
                        $expiry = Carbon::now()->addMonthsNoOverflow(3);
                    } else {
                        $expiry = Carbon::createFromFormat('Y-m-d H:i:s', $vipData->expiry)->addMonthsNoOverflow(3);
                    }
                } else if ($payment == 'one_month_payment') {
                    if ($vipData->expiry < Carbon::now()) {
                        $expiry = Carbon::now()->addMonths(1);
                    } else {
                        $expiry = Carbon::createFromFormat('Y-m-d H:i:s', $vipData->expiry)->addMonthsNoOverflow(1);
                    }
                }
            }

            $vipData->order_id = $order_id;
            $vipData->txn_id = $txn_id;
            $vipData->business_id = $business_id;
            $vipData->amount = $amount;
            $vipData->active = $active;
            $vipData->free = $free;
            $vipData->payment = $payment;
            $vipData->payment_method = $transactionType; //付款方法
            $vipData->remain_days = $remain_days;

            //單次付款到期日
            if(isset($expiry)){
                $vipData->expiry = $expiry;
            }else{
                $vipData->expiry = '0000-00-00 00:00:00';
            }

            $vipData->save();

        }

        //$admin = User::findByEmail(Config::get('social.admin.notice-email'));
        $logStr = 'upgrade, order id: ' . $order_id . ', payment: ' . $payment . ', amount: ' . $amount . ', transactionType: ' . $transactionType;
        VipLog::addToLog($member_id, $logStr.$logStrQrcode, $txn_id, 1, $free);

//        $curUser = User::findById($member_id);
//        if ($curUser != null)
//        {
            //$admin->notify(new NewVipEmail($member_id, $business_id, $member_id));
//        }

        //開啟討論區權限
        //ForumManage::open_forum_active($member_id);
    }

    public static function findById($member_id) {
        return Vip::where('member_id', $member_id)->first();
    }

    public static function findByIdWithDateDesc($member_id) {
        return Vip::where('member_id', $member_id)->orderBy('created_at', 'desc')->first();
    }

    public static function checkByUserAndTxnId($member_id, $txn_id) {
        return Vip::where('member_id', $member_id)->where('txn_id', $txn_id)->where('created_at', '>', Carbon::now()->addHours(-24)->toDateTimeString())->first();
    }

    public static function cancel($member_id, $free)
    {
        $curUser = User::findById($member_id);
        $admin = User::findByEmail(Config::get('social.admin.notice-email'));
        if ($curUser != null) {
            //$admin->notify(new CancelVipEmail($member_id, '761404', $member_id));
        }
        else{
            return false;
        }
        $user = Vip::select('id', 'expiry', 'created_at', 'updated_at','payment','business_id', 'order_id','remain_days')
                ->where('member_id', $member_id)
                ->orderBy('created_at', 'desc')->get();
        // 取消時，確認沒有設定到期日，才開始動作，否則遇上多次取消，可能會導致到期日被延後的結果
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
                $str = $curUser->name . ' 您好，您已取消本站 VIP 續期。但由於您的扣款時間是每月'. $latestUpdatedAt->day .'號，取消時間低於七個工作天，作業不及。所以本次還是會正常扣款，下一週期就會停止扣款。造成不便敬請見諒。';
            }


            if(!(EnvironmentService::isLocalOrTestMachine())) {
                //訂單更新到期日 //此段在測試機無法測試
                $order = Order::where('order_id', $user[0]->order_id)->get()->first();
                if (strpos($user[0]->order_id, 'SG') !== false && isset($order)) {

                    //此訂單如有剩餘天數則加回到期日
                    if ($order->remain_days > 0) {
                        $expiryDate = $expiryDate->addDays($order->remain_days);
                        Order::where('order_id', $user[0]->order_id)->update(['order_expire_date' => $expiryDate]);
                    }

                } else {

                    Order::addEcPayOrder($user[0]->order_id, $expiryDate);

                }
            }else {
                //測試機更新剩餘天數至到期日
                //此測試訂單如有剩餘天數則加回到期日
                //上正式機前這段請移除
                // if($user[0]->remain_days>0){
                //     $expiryDate = $expiryDate->addDays($user[0]->remain_days);
                // }
            }

            foreach ($user as $u){
                $u->expiry = $expiryDate->startOfDay()->toDateTimeString();
                $u->remain_days=0;
                $u->save();
            }
            VipLog::addToLog($member_id, 'User cancel, expiry: ' . $expiryDate, 'XXXXXXXXX', 0, $free);

            return [true, "str"  => $str ?? null];
        }
//        else if($curUser->engroup == 2 && $free == 0 && $user[0]->expiry == '0000-00-00 00:00:00'){
//            //取消當日+3天的時間
//            $date = date('Y-m-d 00:00:00' , mktime(0, 0, 0, date('m'), date('d')+4, date('Y')));
//            foreach ($user as $u){
//                $u->expiry = $date;
//                $u->save();
//            }
//            VipLog::addToLog($member_id, 'Cancel, expiry: ' . $date, 'XXXXXXXXX', 0, $free);
//            return true;
//        }
        else if($user[0]->expiry != '0000-00-00 00:00:00'){
            VipLog::addToLog($member_id, 'Cancellation bypass, expiry: ' . $user[0]->expiry, 'XXXXXXXXX', 0, $free);
            return false;
        }

        //return Vip::where('member_id', $member_id)->delete();
        //VIP取消權限不再用刪除，而是全改為拔active
        return Vip::where('member_id', $member_id)->get()->first()->removeVIP();
    }

    public function compactCancel(){
        return Vip::cancel($this->member_id, $this->free);
    }

    public function addToLog($action, $content = null, $txn_id = 'XXXXXX'){
        VipLog::addToLog($this->member_id, $content, $txn_id, $action, $this->free);
        return true;
    }

    public function removeVIP(){
        $user = Vip::select('member_id', 'active', 'expiry')
            ->where('member_id', $this->member_id)
            //->where('order_id','!=','BackendFree')
            ->update(array(
                'active' => 0,
                'expiry' => '0000-00-00 00:00:00'
            ));

        //關閉討論區權限
        //ForumManage::close_forum_active($this->member_id);
        ForumManage::delete_forum_user_join($this->member_id);

        return $user;
    }

    public static function vip_diamond($id){
        //黑鑽 曾經是 vip現在不是 & 現在是 vip 但已經選擇取消不續約
        $sqltmp = Vip::select('member_id', 'active', 'expiry', 'payment')->where('member_id', $id)->orderBy('created_at', 'desc')->get()->first();
        if($sqltmp){
            if($sqltmp->active == '0' OR $sqltmp->expiry != '0000-00-00 00:00:00' OR $sqltmp->payment == 'one_month_payment') return 'diamond_black';
            //現在是VIP且無取消續約的 連續續約月數轉換鑽石數
            if($sqltmp->active == '1' OR $sqltmp->expiry = '0000-00-00 00:00:00'){
                $now = \Carbon\Carbon::now();
                $vip_date = Vip::select('id', 'created_at')->where('member_id', $id)->orderBy('created_at', 'desc')->get()->first();
                if(isset($vip_date) && isset($vip_date->created_at)){
                    $vip_date = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $vip_date->created_at);
                    $vip_mon = $vip_date->diffInMonths($now);
                    if($vip_mon<2){
                        $vip_diamond = 1;
                    }elseif(in_array($vip_mon,array(2,3,4))){
                        $vip_diamond = 2;
                    }elseif($vip_mon>=5){
                        $vip_diamond = 3;
                    }
                    return $vip_diamond;
                }
                else{
                    Log::info('VIP created_at is null, user id: ' . $id);
                    return false;
                }
            }
        }
        return null;
    }

    public static function vipMonths($id){
        $vip = Vip::where('member_id',$id)->where('active',1)->where('free',0)->first();
        if(isset($vip)) {
            $months = Carbon::parse($vip->created_at)->diffInMonths(Carbon::now());
            return $months;
        }
        return null;
    }
    
    public function isPaidCanceled() {
        if($this->active==1){
            if($this->expiry >= Carbon::now()){
                return 1;
            }
        }
        return 0;        
    }    

}
