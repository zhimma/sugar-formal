<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnonymousEvaluationChat extends Model
{
    protected $fillable = ['evaluation_id', 'members', 'deletor','status'];

    public function messages()
    {
        return $this->hasMany(AnonymousEvaluationMessage::class, 'anonymous_evaluation_chat_id', 'id');
    }

    public function evaluation()
    {
        return $this->hasOne(Evaluation::class, 'id', 'evaluation_id');
    }
}
