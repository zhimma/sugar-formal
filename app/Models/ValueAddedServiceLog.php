<?php

namespace App\Models;

use App\Models\User;
use App\Notifications\MessageEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ValueAddedServiceLog extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'member_value_added_service_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    public function __construct() {

    }

    public static function addToLog($member_id, $service_name, $content, $order_id, $txn_id, $action) {
        $log = new ValueAddedServiceLog();
        $log->member_id = $member_id;
        $log->service_name = $service_name;
        $log->content = $content;
        $log->order_id = $order_id;
        $log->txn_id = $txn_id;
        $log->action = $action;
        $log->save();
    }

    public static function getLatestLog($member_id){
        return self::where('member_id', $member_id)->orderBy('created_at', 'desc')->first();
    }
}
