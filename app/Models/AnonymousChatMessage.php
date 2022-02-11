<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnonymousChatMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'anonymous_chat_id',
        'user_id',
        'to_user_id',
        'content'
    ];

    protected $table = 'anonymous_chat_message';

}
