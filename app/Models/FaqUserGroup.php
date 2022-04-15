<?php

namespace App\Models;

use App\Models\User;
use App\Models\FaqGroup;
use Illuminate\Database\Eloquent\Model;

class FaqUserGroup extends Model
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
    
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }  

    public function faq_group() {
        return $this->belongsTo(FaqGroup::class, 'group_id', 'id');
    }
    
 

}
