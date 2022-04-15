<?php

namespace App\Models;

use App\Models\FaqQuestion;
use App\Models\FaqChoice;
use App\Models\FaqGroupActLog;
use App\Models\FaqUserGroup;
use Illuminate\Database\Eloquent\Model;

class FaqGroup extends Model
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
    
    public function faq_question(){
        return $this->hasMany(FaqQuestion::class, 'group_id', 'id');
    }
    
    public function faq_choice()
    {
        return $this->hasManyThrough(FaqChoice::class, FaqQuestion::class, 'group_id', 'question_id');
    }  

    public function answer_choice()
    {
        return $this->faq_choice()->where('is_answer',1);
    }     

    public function faq_group_act_log(){
        return $this->hasMany(FaqGroupActLog::class, 'group_id', 'id');
    }      

    public function faq_user_group(){
        return $this->hasMany(FaqUserGroup::class, 'group_id', 'id');
    } 

    public function isRealHasAnswer() {
        $this->load('faq_question','answer_choice');
        return $this->answer_choice->count() 
                || $this->faq_question->whereNotNull('answer_bit')->count() 
                || $this->faq_question->whereNotNull('answer_context')->count();
    }
    
    public function renewHasAnswer() {
        if($this->isRealHasAnswer()) {
            $this->has_answer = 1;
        }
        else {
            $this->has_answer = 0;
        }
        return $this->save();
    }

}
