<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IsWarnedLog extends Model
{
    protected $table = 'is_warned_log';
	
	protected $guarded = ['id'];

    public static function insert_log($user_id)
    {
        
    }
}
