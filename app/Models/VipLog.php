<?php

namespace App\Models;

use App\Models\User;
use App\Notifications\MessageEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class VipLog extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'member_vip_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    public function __construct() {

    }

    public static function addToLog($member_id, $member_name, $txn_id, $action, $free) {
        // $user_arr = [
        //     'member_id' => $member_id,
        //     'member_name' => $member_name,
        //     'txn_id' => $txn_id,
        //     'action' => $action,
        //     'created_at' => Carbon::now()
        // ];

        $log = new VipLog();
        $log->member_id = $member_id;
        $log->member_name = $member_name;
        $log->txn_id = $txn_id;
        $log->action = $action;
        $log->free = $free;
        $log->created_at = Carbon::now();
        $log->updated_at = Carbon::now();
        $log->save();
    }
    
    public function isCancel() {
        return stripos($this->member_name,'cancel')!==false;
       
    }

    public function isExtendExpiry() {
        return stripos($this->member_name,'backend_extend_expiry_service')!==false;
    }

    public function isTransfer() {
        if (stripos($this->member_name,'backend_transfer_service to')!==false) {
            return substr(strrchr($this->member_name, "to"), 2);
        } else if (stripos($this->member_name,'backend_transfer_service from')!==false) {
            return substr(strrchr($this->member_name, "from"), 4);
        } else {
            return null;
        }
    }
}
