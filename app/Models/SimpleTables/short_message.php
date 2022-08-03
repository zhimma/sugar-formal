<?php

namespace App\Models\SimpleTables;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class short_message extends Model
{
    use SoftDeletes;
    //
    protected $table = 'short_message';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];    
}
