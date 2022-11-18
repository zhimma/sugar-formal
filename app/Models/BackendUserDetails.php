<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AdminActionLog;

class BackendUserDetails extends Model
{
    protected $table = 'backend_user_details';
    protected $fillable = [
        'user_check_step2_wait_login_times',
    ];

    public static function first_or_new($user_id){
        $backend_user_detail = BackendUserDetails::where('user_id', $user_id)->first();
        if(!($backend_user_detail ?? false))
        {
            $backend_user_detail = new BackendUserDetails;
            $backend_user_detail->user_id = $user_id;
            $backend_user_detail->save();
        }
        return $backend_user_detail;
    }

    public static function check_extend($user_id, $times, $operator_id, $ip){
        $backend_user_detail = BackendUserDetails::first_or_new($user_id);
        $backend_user_detail->user_check_step2_wait_login_times = $backend_user_detail->user_check_step2_wait_login_times + $times;
        $backend_user_detail->save();

        $log = new AdminActionLog();
        $log->operator = $operator_id;
        $log->target_id = $user_id;
        $log->act = '會員檢查等待更多資料';
        $log->ip = $ip;
        $log->save();

        return $backend_user_detail;
    }
}
