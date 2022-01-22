<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForumChat extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'forum_id',
        'user_id',
        'color',
        'content',
        'pic'
    ];

    protected $table = 'forum_chat';
}
