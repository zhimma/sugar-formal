<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InboxRefuseSet extends Model
{
    protected $table = 'inbox_refuse_set';

    protected $promaryKey = 'id';

    protected $fillable = [
        'user_id',
        'isrefused_vip_user',
        'isrefused_common_user',
        'isrefused_warned_user',

        //值為-1時為不選擇 值為0時會過濾掉pr值null與pr值無
        'refuse_pr',

        'refuse_canned_message_pr',
        'refuse_register_days',
        'updated_at',
    ];
}
