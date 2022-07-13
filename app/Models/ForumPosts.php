<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForumPosts extends Model
{
    use SoftDeletes;


    protected $fillable = [
        'id',
        'forum_id',
        'type',
        'user_id',
        'title',
        'contents',
        'essence_id',
    ];

    protected $table = 'forum_posts';
}
