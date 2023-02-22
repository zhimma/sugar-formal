<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserTinySettingTo extends Model
{
    //
    protected $table = 'user_tiny_setting_to';

    protected $guarded = ['id'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    } 

    
    public function to()
    {
        return $this->belongsTo(User::class, 'to_id', 'id');
    } 

}
