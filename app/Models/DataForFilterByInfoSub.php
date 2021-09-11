<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DataForFilterByInfo;

class DataForFilterByInfoSub extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'data_for_filter_by_info_sub';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    
    protected $guarded = ['id'];
	
    public function data(){
        return $this->belongsTo(DataForFilterByInfo::class, 'data_id', 'id');
    }	

}
