<?php

namespace App\Models;

use App\Models\RealAuthQuestion;
use Illuminate\Database\Eloquent\Model;

class RealAuthChoice extends Model
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
    
    public function real_auth_question(){
        return $this->belongsTo(RealAuthQuestion::class, 'question_id', 'id');
    }    

    public function question(){
        return $this->real_auth_question();
    } 
}
