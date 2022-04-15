<?php

namespace App\Models;

use App\Models\FaqGroup;
use App\Models\FaqChoice;
use App\Models\FaqUserReply;
use Illuminate\Database\Eloquent\Model;

class FaqQuestion extends Model
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
    
    public function faq_group(){
        return $this->belongsTo(FaqGroup::class, 'group_id', 'id');
    }
    
    public function faq_choice() {
        return $this->hasMany(FaqChoice::class,'question_id','id');
    } 

    public function faq_choice_answer() {
        return $this->faq_choice()->where('is_answer',1);
    }      

    public function faq_user_reply() {
        return $this->hasMany(FaqUserReply::class,'question_id','id');
    }    
}
