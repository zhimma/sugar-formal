<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataForFilterByInfo extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'data_for_filter_by_info';

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

}
