<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RealAuthUserModify;

class RealAuthUserModifyItem extends Model
{

    protected $table = 'real_auth_user_modify_item';
    
    protected $guarded = ['id'];
    
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }     
    
    public function real_auth_user_modify(){
        return $this->hasMany(RealAuthUserModify::class, 'modify_item_id', 'id');
    }    
}
