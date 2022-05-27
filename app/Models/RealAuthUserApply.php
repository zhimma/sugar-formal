<?php

namespace App\Models;

use App\Models\User;
use App\Models\RealAuthType;
use App\Models\RealAuthUserReply;
use Illuminate\Database\Eloquent\Model;

class RealAuthUserApply extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = [];
    
    protected $guarded = ['id'];
    
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    } 

    public function real_auth_type(){
        return $this->belongsTo(RealAuthType::class, 'auth_type_id', 'id');
    }  

    public function real_auth_user_reply() {
        return $this->hasMany(RealAuthUserReply::class,'apply_id','id');
    }     

}
