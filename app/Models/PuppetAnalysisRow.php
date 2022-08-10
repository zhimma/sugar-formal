<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PuppetAnalysisRow extends Model
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
        return $this->belongsTo(User::class, 'name', 'id');
    }
}
