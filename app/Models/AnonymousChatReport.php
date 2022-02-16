<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnonymousChatReport extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'anonymous_chat_id',
        'user_id',
        'reported_user_id',
        'content'
    ];

    protected $table = 'anonymous_chat_report';
}
