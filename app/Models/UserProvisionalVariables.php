<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProvisionalVariables extends Model
{
    protected $table = 'user_provisional_variables';

    protected $fillable = [
        'has_adjusted_period_first_time'
    ];
}
