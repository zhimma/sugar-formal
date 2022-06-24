<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EssencePostsRewardLog extends Model
{
    protected $table = 'essence_posts_reward_log';
    protected $fillable = [
        'post_id',
        'user_id',
        'title',
        'contents',
        'category',
        'share_with',
        'verify_time',
        'expire_origin',
        'expiry',
    ];

    public static function addToLog($posts_detail, $expire_origin, $expire_date) {

        EssencePostsRewardLog::create([
            'post_id'=>$posts_detail->id,
            'user_id'=>$posts_detail->user_id,
            'title'=>$posts_detail->title,
            'contents'=>$posts_detail->contents,
            'category'=>$posts_detail->category,
            'share_with'=>$posts_detail->share_with,
            'verify_time'=>$posts_detail->verify_time,
            'expire_origin'=>$expire_origin,
            'expiry'=>$expire_date,
        ]);
    }
}
