<?php

namespace App\Models;

use App\Models\User;
use App\Models\FaqQuestion;
use Illuminate\Database\Eloquent\Model;

class FaqUserReply extends Model
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

    public function faq_question(){
        return $this->belongsTo(FaqQuestion::class, 'question_id', 'id');
    }  

    public function faq_choice() {
        return $this->belongsTo(FaqChoice::class, 'choice_id', 'id');
    }       

}
