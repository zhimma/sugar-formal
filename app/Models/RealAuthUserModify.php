<?php

namespace App\Models;

use App\Models\User;
use App\Models\RealAuthModifyItem;
use App\Models\RealAuthType;
//use App\Models\RealAuthUserModifyItem;
use App\Models\RealAuthUserModifyPic;
use App\Models\RealAuthUserModifyProfile;
use Illuminate\Database\Eloquent\Model;

class RealAuthUserModify extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'real_auth_user_modify';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    
    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }  
    
    public function real_auth_type(){
        return $this->belongsTo(RealAuthType::class, 'auth_type_id', 'id');
    } 

    public function real_auth_modify_item(){
        return $this->belongsTo(RealAuthModifyItem::class, 'item_id', 'id');
    }     
    
    public function real_auth_user_modify_pic(){
        return $this->hasMany(RealAuthUserModifyPic::class, 'modify_id', 'id');
    }  

    public function real_auth_user_modify_profile(){
        return $this->hasMany(RealAuthUserModifyProfile::class, 'modify_id', 'id');
    }     
}
