<?php

namespace App\Models;

use App\Models\FaqGroup;
use Illuminate\Database\Eloquent\Model;

class FaqGroupActLog extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = "faq_group_act_log";
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

}
