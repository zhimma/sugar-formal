<?php

namespace App\Models\SimpleTables;

use Illuminate\Database\Eloquent\Model;

class short_message extends Model
{
    //
    protected $table = 'short_message';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];    
}
