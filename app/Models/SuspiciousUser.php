<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Models\AdminPicturesSimilarActionLog;
use App\Models\AdminActionLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public static function system_insert_data($user_id, $reason)
    {
        $now_time = Carbon::now();

        //先刪後增
        SuspiciousUser::where('user_id', $user_id)->delete();
        SuspiciousUser::insert([
            'admin_id'   => 0,
            'user_id'    => $user_id,
            'reason'     => $reason,
            'created_at' => $now_time
        ]);
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

    public static function check_weekly_communication_count()
    {
        $communication_count_weekly_set = intval(DB::table('queue_global_variables')->where('name','suspicious_list_communication_weekly_count_set')->first()->value);
        $country_count_set = intval(DB::table('queue_global_variables')->where('name','suspicious_list_communication_country_count_set')->first()->value);

        $check_user_list = User::where('engroup', 2)
                            ->where('last_login', '>', Carbon::yesterday())
                            ->where('last_login', '<', Carbon::today())
                            ->where('advance_auth_status', 0)
                            ->get();
        
        $ms_list = $check_user_list->pluck('id');

        $messages_all = Message::withTrashed()
                                ->where(function($query) use($ms_list) {                            
                                    $query->whereIn('to_id', $ms_list)->orwhereIn('from_id', $ms_list);
                                })
                                ->where('from_id','!=',1049)
                                ->where('to_id','!=',1049)
                                ->orderBy('id')
                                ->get();
                                
        foreach($check_user_list as $user)
        {
            //檢查當周通訊人數是否超過
            $messages_members_count = $messages_all->where('to_id', $user->id)->where('created_at', '>', Carbon::now()->subDays(7))->unique('room_id')->count() + $messages_all->where('from_id', $user->id)->where('created_at', '>', Carbon::now()->subDays(7))->unique('room_id')->count();
            if($messages_members_count > $communication_count_weekly_set)
            {
                $user_to_id_list = $messages_all->where('to_id', $user->id)->unique('from_id')->pluck('from_id')->toArray();
                $user_from_id_list = $messages_all->where('from_id', $user->id)->unique('to_id')->pluck('to_id')->toArray();

                $user_communication_id_list = array();
                $user_communication_id_list = array_merge($user_communication_id_list, $user_to_id_list);
                $user_communication_id_list = array_merge($user_communication_id_list, $user_from_id_list);
                $user_communication_id_list = array_unique($user_communication_id_list);

                $city_list = UserMeta::whereIn('user_id', $user_communication_id_list)->get()->pluck('city')->toArray();

                $count_of_city = count(array_unique($city_list));

                if($count_of_city > $country_count_set)
                {
                    SuspiciousUser::system_insert_data($user->id, '通訊地區超過' . $country_count_set . '個');
                }
            }
        }
        
    }


}
