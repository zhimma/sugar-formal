<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class BasicSetting extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $timestamps = false;
    protected $table = 'basic_setting';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'updated_at',
        // 'blocked_id。'
    ];

    
}
