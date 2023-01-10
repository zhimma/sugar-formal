<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnonymousEvaluationMessage extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'anonymous_evaluation_chat_id', 
        'user_id', 
        'reply_id',
        'read',
        'content',
        'pictures'
    ];
    
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
