<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SuspiciousUserListTable extends Model
{
    use HasFactory;

    protected $table = 'suspicious_user_list_table';

    protected $fillable = [
        'user_id',
        'is_medium_long_term_without_adv_verification',
        'medium_long_term_without_adv_verification_created_at'
    ];

    public function user() 
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public function is_warned_log() 
    {
        return $this->hasMany(IsWarnedLog::class,'user_id','user_id')->where('adv_auth', 1)->orderByDesc('created_at');
    } 

    public static function check_medium_long_term_without_adv_verification()
    {
        $suspicious_user_list = SuspiciousUserListTable::where('is_medium_long_term_without_adv_verification', 1)
                                                        ->get()
                                                        ->pluck('user_id');
        $insert_user_list = User::where('engroup', 2)
                            ->where('last_login', '>', Carbon::yesterday())
                            ->where('last_login', '<', Carbon::today())
                            ->where('exchange_period', 1)
                            ->where('advance_auth_status', 0)
                            ->whereNotIn('id', $suspicious_user_list)
                            ->get();
        
        $ms_list = $insert_user_list->pluck('id');

        $messages_all = Message::withTrashed()
                                ->where(function($query) use($ms_list) {                            
                                    $query->whereIn('to_id', $ms_list)->orwhereIn('from_id', $ms_list);
                                })
                                ->where('from_id','!=',1049)
                                ->where('to_id','!=',1049)
                                ->orderBy('id')
                                ->get();
        
        $communication_count_set = intval(DB::table('queue_global_variables')->where('name','medium_long_term_without_adv_verification_communication_count_set')->first()->value);
        
        foreach($insert_user_list as $user)
        {
            $messages_members_count = $messages_all->where('to_id', $user->id)->unique('room_id')->count() + $messages_all->where('from_id', $user->id)->unique('room_id')->count();
            if($messages_members_count > $communication_count_set)
            {
                $now = Carbon::now();
                $suspicious_user = SuspiciousUserListTable::firstOrNew(['user_id' => $user->id]);
                $suspicious_user->is_medium_long_term_without_adv_verification = 1;
                $suspicious_user->medium_long_term_without_adv_verification_created_at = $now;
                $suspicious_user->save();
            }
        }
        
        
    }

    public static function remove_medium_long_term_without_adv_verification($user_id)
    {
        $user_remove = SuspiciousUserListTable::where('user_id', $user_id)->first();
        $user_remove->is_medium_long_term_without_adv_verification = 0;
        $user_remove->save();
    }
}
