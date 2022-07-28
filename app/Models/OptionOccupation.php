<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionOccupation extends Model
{
    protected $table = 'option_occupation';

    protected $fillable = [
        'option_name',
        'is_custom'
    ];

    public $timestamps = false;
}
