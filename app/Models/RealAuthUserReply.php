<?php

namespace App\Models;

use App\Models\User;
use App\Models\RealAuthQuestion;
use App\Models\RealAuthChoice;
use App\Models\RealAuthUserReplyPic;
use App\Models\RealAuthUserApply;
use App\Models\RealAuthUserModify;
use Illuminate\Database\Eloquent\Model;

class RealAuthUserReply extends Model
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
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    } 

    public function real_auth_question()
    {
        return $this->belongsTo(RealAuthQuestion::class, 'question_id', 'id');
    }  

    public function real_auth_choice() 
    {
        return $this->belongsTo(RealAuthChoice::class, 'choice_id', 'id');
    } 
    
    public function real_auth_user_apply() 
    {
        return $this->belongsTo(RealAuthUserApply::class, 'apply_id', 'id');
    }   

    public function real_auth_user_modify() 
    {
        return $this->belongsTo(RealAuthUserModify::class, 'modify_id', 'id');
    } 

    public function real_auth_user_modify_with_trashed() 
    {        
        return $this->real_auth_user_modify()->withTrashed();
    }     

    public function real_auth_user_reply_pic()
    {
        return $this->hasMany(RealAuthUserReplyPic::class, 'reply_id', 'id');
    } 

}
