<?php

namespace App\Models;

use App\Models\User;
use App\Models\RealAuthType;
use App\Models\RealAuthUserApplyLog;
use App\Models\RealAuthUserReply;
use App\Models\UserVideoVerifyRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RealAuthUserPatch extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $table = 'real_auth_user_patch';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guarded = ['id'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    } 

    public function real_auth_type()
    {
        return $this->belongsTo(RealAuthType::class, 'auth_type_id', 'id');
    }  

    public function real_auth_user_modify() 
    {
        return $this->hasMany(RealAuthUserModify::class,'apply_id','id');
    } 

}
