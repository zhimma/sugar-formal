<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Models\AdminPicturesSimilarActionLog;
use App\Models\AdminActionLog;

class SuspiciousUser extends Model
{
    use SoftDeletes;

    protected $table = 'suspicious_user';

    protected $dates = ['deleted_at'];

    public static function insert_data($operator, $user_id, $reason, $ip)
    {
        $now_time = Carbon::now();

        //先刪後增
        SuspiciousUser::where('user_id', $user_id)->delete();
        SuspiciousUser::insert([
            'admin_id'   => $operator->id,
            'user_id'    => $user_id,
            'reason'     => $reason,
            'created_at' => $now_time
        ]);

        //操作紀錄
        AdminPicturesSimilarActionLog::insert([
            'operator_id'   => $operator->id,
            'operator_role' => $operator->roles->first()->id,
            'target_id'     => $user_id,
            'act'           => '加入可疑名單',
            'reason'        => $reason,
            'ip'            => $ip,
            'created_at'    => $now_time,
            'updated_at'    => $now_time
        ]);
        
        //操作紀錄
        AdminActionLog::insert_log($operator->id, $ip, $user_id, '加入可疑名單', 28);
    }

    public static function delete_data($operator, $user_id, $reason, $ip)
    {
        $now_time = Carbon::now();

        //刪除
        SuspiciousUser::where('user_id', $user_id)->delete();

        //操作紀錄
        AdminPicturesSimilarActionLog::insert([
            'operator_id'   => $operator->id,
            'operator_role' => $operator->roles->first()->id,
            'target_id'     => $user_id,
            'act'           => '刪除可疑名單',
            'reason'        => $reason,
            'ip'            => $ip,
            'created_at'    => $now_time,
            'updated_at'    => $now_time
        ]);

        //操作紀錄
        AdminActionLog::insert_log($operator->id, $ip, $user_id, '刪除可疑名單', 29);
    }
    
    public function admin_user()
    {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }     



}
