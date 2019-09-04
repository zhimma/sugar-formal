<?php

namespace App\Models;

use App\Models\User;
use App\Notifications\MessageEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
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

        if($status == NULL) return 1;
        return $status->free;
    }

    public static function upgrade($member_id, $business_id, $order_id, $amount, $txn_id, $active, $free)
    {
        $vip = new Vip();
        $vip->member_id = $member_id;
        $vip->txn_id = $txn_id;
        $vip->business_id = $business_id;
        $vip->order_id = $order_id;
        $vip->amount = $amount;
        $vip->active = $active;
        $vip->free = $free;
        //$startDate = time();
        //$expiry = date('Y-m-d H:i:s', strtotime('+'.substr($order_id, 0, 2).' day', $startDate));
        //$vip->expiry = $expiry;
        $vip->save();

        VipLog::addToLog($member_id, 'upgrade', $txn_id, 1, $free);

        $curUser = User::findById($member_id);
        if ($curUser != null)
        {
            // $curUser->notify(new MessageEmail($member_id, $member_id, "VIP 升級成功！"));
        }
    }

    public static function findById($member_id) {
        return Vip::where('member_id', $member_id)->first();
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

        VipLog::addToLog($member_id, 'cancel', 'XXXXXXXXX', 0, $free);
        if ($curUser != null)
        {
            // $curUser->notify(new MessageEmail($member_id, $member_id, "VIP 取消了！"));
        }
        $user = Vip::select('id', 'expiry', 'created_at')
                ->where('member_id', $member_id)
                ->orderBy('created_at', 'desc')->get();
        if($curUser->engroup == 1){
            $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user[0]->created_at);
            $day = $date->format('d');
            $now = \Carbon\Carbon::now();
            $nextMonth = $now->addMonth();
            $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $nextMonth->year.'-'.$nextMonth->month.'-'.$day.' 00:00:00');
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
}
