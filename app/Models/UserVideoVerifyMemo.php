<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVideoVerifyMemo extends Model
{
    protected $table = 'user_video_verify_memo';

    protected $guarded = ['id'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }     
}
