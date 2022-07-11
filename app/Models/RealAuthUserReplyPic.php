<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RealAuthUserReply;
use Illuminate\Database\Eloquent\SoftDeletes;

class RealAuthUserReplyPic extends Model
{
    use SoftDeletes;

    protected $table = 'real_auth_user_reply_pic';
    
    protected $guarded = ['id'];

    public function real_auth_user_apply(){
        return $this->belongsTo(RealAuthUserReply::class, 'apply_id', 'id');
    } 
    
    public function real_auth_user_reply(){
        return $this->belongsTo(RealAuthUserReply::class, 'reply_id', 'id');
    }    
}
