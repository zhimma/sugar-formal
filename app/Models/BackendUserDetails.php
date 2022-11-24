<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackendUserDetails extends Model
{
    protected $table = 'backend_user_details';
    protected $fillable = [
        'is_waiting_for_more_data',
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

    public static function check_extend($user_id, $operator_id, $ip){
        $backend_user_detail = BackendUserDetails::first_or_new($user_id);
        $backend_user_detail->is_waiting_for_more_data = 1;
        $backend_user_detail->save();

        $log = new AdminActionLog();
        $log->operator = $operator_id;
        $log->target_id = $user_id;
        $log->act = '會員檢查等待更多資料';
        $log->ip = $ip;
        $log->save();

        return $backend_user_detail;
    }

    public function check_extend_admin_action_log()
    {
        return $this->hasMany(AdminActionLog::class, 'target_id', 'user_id')->where('act','會員檢查等待更多資料')->orderByDesc('created_at');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
