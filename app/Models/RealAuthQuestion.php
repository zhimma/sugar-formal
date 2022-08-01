<?php

namespace App\Models;

use App\Models\RealAuthChoice;
use App\Models\RealAuthUserReply;
use Illuminate\Database\Eloquent\Model;

class RealAuthQuestion extends Model
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
    protected $fillable = [];
    
    protected $guarded = ['id'];
    
    public function real_auth_choice() {
        return $this->hasMany(RealAuthChoice::class,'question_id','id');
    }  

    public function real_auth_user_reply() {
        return $this->hasMany(RealAuthUserReply::class,'question_id','id');
    }    
    
    public function user_reply() {
        return $this->real_auth_user_reply();
    }        
}
