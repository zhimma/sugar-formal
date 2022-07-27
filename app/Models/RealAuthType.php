<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RealAuthQuestion;
use App\Models\RealAuthUserApply;

class RealAuthType extends Model
{

    protected $table = 'real_auth_type';
    
    protected $guarded = ['id'];
    
    public function real_auth_question(){
        return $this->hasMany(RealAuthQuestion::class, 'auth_type_id', 'id');
    }    

    public function question(){
        return $this->real_auth_question();
    }  

    public function real_auth_user_apply() {
        return $this->hasMany(RealAuthUserApply::class, 'auth_type_id', 'id');
    }
    
    public function user_apply() {
        return $this->real_auth_user_apply();
    }    

}
