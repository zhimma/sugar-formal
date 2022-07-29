<?php

namespace App\Models;

use App\Models\User;
use App\Models\RealAuthType;
use App\Models\RealAuthUserApply;
use App\Models\RealAuthUserReply;
use App\Models\UserVideoVerifyRecord;
use Illuminate\Database\Eloquent\Model;

class RealAuthUserApplyLog extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $table = 'real_auth_user_apply_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $guarded = ['id'];
    
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function real_auth_user_apply(){
        return $this->belongsTo(RealAuthUserApply::class, 'apply_id', 'id');
    }      

    public function real_auth_type(){
        return $this->belongsTo(RealAuthType::class, 'auth_type_id', 'id');
    }  

    public function user_video_verify_record() 
    {      
        return $this->belongsTo(UserVideoVerifyRecord::class, 'video_record_id', 'id');
    } 

    public function real_auth_user_reply() {
        return $this->hasMany(RealAuthUserReply::class,'apply_id','id');
    } 

    public function real_auth_user_modify() {
        return $this->hasMany(RealAuthUserModify::class,'apply_id','id');
    } 

    public function latest_modify() {
        return $this->hasOne(RealAuthUserModify::class,'apply_id','id')
                ->orderByDesc('id')->take(1);        
    } 

    public function latest_unchecked_modify() {
        return $this->hasOne(RealAuthUserModify::class,'apply_id','id')
                ->where(function($q) {$q->whereNull('status')->orWhere('status',0);})
                ->orderByDesc('id')->take(1);        
    }    

}
