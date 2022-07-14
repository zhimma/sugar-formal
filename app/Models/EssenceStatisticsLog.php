<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EssenceStatisticsLog extends Model
{
    protected $table = 'essence_statistics_log';
    protected $fillable = [
        'user_id',
        'essence_posts_id',
        'message_client_id',
        'message_send_time',
    ];

    public static function addToLog($data) {

        EssenceStatisticsLog::create([
            'user_id'=>$data['user_id'],
            'essence_posts_id'=>$data['essence_posts_id'],
            'message_client_id'=>$data['message_client_id'],
            'message_send_time'=>$data['message_send_time'],
        ]);
    }
}
