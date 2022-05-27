<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RealAuthModify;

class RealAuthModifyItem extends Model
{

    protected $table = 'real_auth_modify_item';
    
    protected $guarded = ['id'];
    
    public function real_auth_modify(){
        return $this->hasMany(RealAuthModify::class, 'item_id', 'id');
    }
    
    public function modify(){
        return $this->real_auth_modify();
    }    

    public function real_auth_type(){
        return $this->belongsTo(RealAuthType::class, 'auth_type_id', 'id');
    } 

    public function type(){
        return $this->real_auth_type();
    }       

    public function real_auth_type_show(){
        return $this->belongsTo(RealAuthType::class, 'show_auth_type_id', 'id');
    } 
    
    public function type_show(){
        return $this->real_auth_type_show();
    }        
}
