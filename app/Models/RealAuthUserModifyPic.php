<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RealAuthUserModify;
use App\Models\MemberPic;
use Illuminate\Database\Eloquent\SoftDeletes;

class RealAuthUserModifyPic extends Model
{
    use SoftDeletes;

    protected $table = 'real_auth_user_modify_pic';
    
    protected $guarded = ['id'];
    
    public function real_auth_user_modify()
    {
        return $this->belongsTo(RealAuthUserModify::class, 'modify_id', 'id');
    }  

    public function member_pic()
    {
        return $this->belongsTo(MemberPic::class, 'pic', 'pic')->where('pic_cat','member_pic');
    }  

    public function real_auth_user_modify_pic()
    {
        return $this->hasOne(RealAuthUserModifyPic::class, 'id', 'id');
    }     
}
