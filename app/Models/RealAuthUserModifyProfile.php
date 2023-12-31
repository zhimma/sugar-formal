<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RealAuthUserModify;

class RealAuthUserModifyProfile extends Model
{

    protected $table = 'real_auth_user_modify_profile';
    
    protected $guarded = ['id'];
    
    public function real_auth_user_modify(){
        return $this->belongsTo(RealAuthUserModify::class, 'modify_id', 'id');
    }    
}
