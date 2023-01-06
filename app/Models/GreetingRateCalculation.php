<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GreetingRateCalculation extends Model
{
   
    protected $table = 'greeting_rate_calculations';
    protected $fillable = [
        'infix',
        'postfix'
    ];
}
