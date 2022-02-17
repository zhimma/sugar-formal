<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InboxRefuseSet extends Model
{
    protected $table = 'inbox_refuse_set';

    protected $promaryKey = 'id';

    protected $fillable = [
        'user_id',
        'isRefused_vip_user',
        'isRefused_common_user',
        'isRefused_warned_user',
        'refuse_PR',
        'refuse_canned_message_PR',
        'refuse_register_days',
        'updated_at',
    ];
}
