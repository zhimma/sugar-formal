<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVideoVerifyRecord extends Model
{
    protected $table = 'user_video_verify_record';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    } 

    public function admin_user()
    {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }      
}
