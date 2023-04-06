<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BackendUserDetails extends Model
{
    protected $table = 'backend_user_details';
    protected $fillable = [
        'is_waiting_for_more_data',
        'remain_login_times_of_wait_for_more_data',
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

    public function check_extend_login_time_admin_action_log()
    {
        return $this->hasMany(AdminActionLog::class, 'target_id', 'user_id')->where('act','等待更多資料(發回)')->orderByDesc('created_at');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public static function check_extend_by_login_time($user_id, $times, $operator_id, $ip){
        $backend_user_detail = BackendUserDetails::first_or_new($user_id);
        $backend_user_detail->remain_login_times_of_wait_for_more_data = $backend_user_detail->remain_login_times_of_wait_for_more_data + $times;
        $backend_user_detail->save();

        $log = new AdminActionLog();
        $log->operator = $operator_id;
        $log->target_id = $user_id;
        $log->act = '等待更多資料(發回)';
        $log->ip = $ip;
        $log->save();

        return $backend_user_detail;
    }

    public static function apply_video_verify($user_id)
    {
        $backend_user_detail = BackendUserDetails::first_or_new($user_id);
        $backend_user_detail->is_need_video_verify = 1;
        $backend_user_detail->need_video_verify_date = Carbon::now();
        $backend_user_detail->save();
    }

    public static function cancel_video_verify($user_id)
    {
        $backend_user_detail = BackendUserDetails::first_or_new($user_id);
        if($backend_user_detail->is_need_video_verify == 1)
        {
            $backend_user_detail->video_verify_fail_count = $backend_user_detail->video_verify_fail_count + 1;
        }
        $backend_user_detail->save();
    }

    public static function reset_cancel_video_verify($user_id)
    {
        $backend_user_detail = BackendUserDetails::first_or_new($user_id);
        $backend_user_detail->video_verify_fail_count = 0;
        $backend_user_detail->login_times_after_need_video_verify_date = 0;
        $backend_user_detail->save();
    }

    public static function login_update($user_id)
    {
        $backend_user_detail = BackendUserDetails::first_or_new($user_id);
        if($backend_user_detail->remain_login_times_of_wait_for_more_data > 0)
        {
            $backend_user_detail->remain_login_times_of_wait_for_more_data = $backend_user_detail->remain_login_times_of_wait_for_more_data - 1;
        }
        if($backend_user_detail->is_need_video_verify == 1)
        {
            $backend_user_detail->login_times_after_need_video_verify_date = $backend_user_detail->login_times_after_need_video_verify_date + 1;
        }
        $backend_user_detail->save();
    }

    public static function need_reverify($user_id)
    {
        $backend_user_detail = BackendUserDetails::first_or_new($user_id);
        if($backend_user_detail->is_need_reverify == 0)
        {
            $backend_user_detail->is_need_reverify = 1;
        }
        $backend_user_detail->save();
    }

    public static function reset_video_verify($user_id)
    {
        $backend_user_detail = BackendUserDetails::first_or_new($user_id);
        $backend_user_detail->is_need_video_verify = 0;
        $backend_user_detail->video_verify_fail_count = 0;
        $backend_user_detail->login_times_after_need_video_verify_date = 0;
        $backend_user_detail->is_need_reverify = 0;
        $backend_user_detail->save();
    }
}
