<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnonymousChat extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'user_id',
        'content',
        'pic',
        'anonymous'
    ];

    protected $table = 'anonymous_chat';

}
