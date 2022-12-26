<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnonymousEvaluationChatReport extends Model
{
    protected $table = 'anonymous_evaluation_chats_report';
    protected $fillable = ['message_id', 'user_id', 'accused_user_id','content'];

    public function message()
    {
        return $this->hasOne(AnonymousEvaluationMessage::class, 'id', 'message_id');
    }

    public function whistleblower(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function accused(){
        return $this->hasOne(User::class, 'id', 'accused_user_id');
    }
}
