<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RealAuthUserReply;

class RealAuthUserReplyPic extends Model
{

    protected $table = 'real_auth_user_reply_pic';
    
    protected $guarded = ['id'];
    
    public function real_auth_user_reply(){
        return $this->belongsTo(RealAuthUserReply::class, 'reply_id', 'id');
    }    
}
