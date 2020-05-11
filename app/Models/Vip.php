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
        'expiry'
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

    public static function upgrade($member_id, $business_id, $order_id, $amount, $txn_id, $active, $free, $transactionType = null)
    {
        $vipData = Vip::findByIdWithDateDesc($member_id);
        if(!isset($vipData)){
            $vip = new Vip();
            $vip->member_id = $member_id;
            $vip->txn_id = $txn_id;
            $vip->business_id = $business_id;
            $vip->order_id = $order_id;
            $vip->amount = $amount;
            $vip->active = $active;
            $vip->free = $free;
            //$vip->transactionType = $transactionType;
            //$startDate = time();
            //$expiry = date('Y-m-d H:i:s', strtotime('+'.substr($order_id, 0, 2).' day', $startDate));
            //$vip->expiry = $expiry;
            $vip->save();
        }
        else{
            $vipData->order_id = $order_id;
            $vipData->txn_id = $txn_id;
            $vipData->business_id = $business_id;
            $vipData->amount = $amount;
            $vipData->active = $active;
            $vipData->free = $free;
            $vipData->save();
        }

        $admin = User::findByEmail(Config::get('social.admin.email'));

        VipLog::addToLog($member_id, 'upgrade, order id: ' . $order_id, $txn_id, 1, $free);

        $curUser = User::findById($member_id);
        if ($curUser != null)
        {
            $admin->notify(new NewVipEmail($member_id, $business_id, $member_id));
        }
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
        //$curVip = Vip::where('member_id', $member_id)->orderBy('expiry', 'desc')->first();
        //$curVip->expiry =
        $curUser = User::findById($member_id);
        //$curUserName = User::id_($member_id)->meta_();
        $admin = User::findByEmail(Config::get('social.admin.email'));

        VipLog::addToLog($member_id, 'cancel', 'XXXXXXXXX', 0, $free);
        if ($curUser != null) {
            $admin->notify(new CancelVipEmail($member_id, '761404', $member_id));
        }
        $user = Vip::select('id', 'expiry', 'created_at', 'updated_at')
                ->where('member_id', $member_id)
                ->orderBy('created_at', 'desc')->get();
        if($curUser->engroup == 1){
            $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user[0]->updated_at);
            $day = $date->day;
            $now = \Carbon\Carbon::now();
            $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $now->year.'-'.$now->month.'-'.$day.' 00:00:00');
            if($now->day >= $day){
                // addMonthsNoOverflow(): 避免如 10/31 加了一個月後變 12/01 的情形出現
                $nextMonth = $now->addMonthsNoOverflow(1);
                $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $nextMonth->year.'-'.$nextMonth->month.'-'.$day.' 00:00:00');
            }
            // 如果是使用綠界付費，且取消日距預計下次扣款日小於七天，則到期日再加一個月
            if($user[0]->business_id == '3137610' && $now->diffInDays($date) <= 7) {
                $date = $date->addMonthNoOverflow(1);
            }

            foreach ($user as $u){
                $u->expiry = $date->toDateTimeString();
                $u->save();
            }
            return true;
        }
        else if($curUser->engroup == 2 && $free == 0){
            //取消當日+3天的時間
            $date = date('Y-m-d 00:00:00' , mktime(0, 0, 0, date('m'), date('d')+4, date('Y')));
            foreach ($user as $u){
                $u->expiry = $date;
                $u->save();
            }
            return true;
        }
        else if($user[0]->expiry != '0000-00-00 00:00:00'){
            return false;
        }

        //return Vip::where('member_id', $member_id)->delete();
        //VIP取消權限不再用刪除，而是全改為拔active
        return Vip::where('member_id', $member_id)->get()->first()->removeVIP();
    }

    public function compactCancel(){
        return Vip::cancel($this->member_id, $this->free);
    }

    public function removeVIP(){
        $user = Vip::select('member_id', 'active', 'expiry')
            ->where('member_id', $this->member_id)
            ->update(array('active' => 0, 'expiry' => null));
        return $user;
    }

    public static function vip_diamond($id){
        //黑鑽 曾經是 vip現在不是 & 現在是 vip 但已經選擇取消不續約
        $sqltmp = Vip::select('member_id', 'active', 'expiry')->where('member_id', $id)->orderBy('created_at', 'desc')->get()->first();
        if($sqltmp){
            if($sqltmp->active == '0' OR $sqltmp->expiry != '0000-00-00 00:00:00') return 'diamond_black';
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

}
