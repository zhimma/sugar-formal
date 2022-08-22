<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionPersonalityTraits extends Model
{
    protected $table = 'option_personality_traits';

    protected $fillable = [
        'option_name',
        'is_custom'
    ];

    public $timestamps = false;
}
