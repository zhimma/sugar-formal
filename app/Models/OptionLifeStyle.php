<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionLifeStyle extends Model
{
    protected $table = 'option_life_style';

    protected $fillable = [
        'option_name',
        'is_custom'
    ];

    public $timestamps = false;
}
