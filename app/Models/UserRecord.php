<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRecord extends Model
{
    protected $table = 'user_record';

    protected $fillable = [
        'cost_time_of_registering',
        'cost_time_of_first_dataprofile',
        'first_login_after_video_record_verify',
    ];

    public static function first_or_new($user_id){
        $user_record = UserRecord::where('user_id', $user_id)->first();
        if(!($user_record ?? false))
        {
            $user_record = new UserRecord;
            $user_record->user_id = $user_id;
            $user_record->save();
        }
        return $user_record;
    }
}
