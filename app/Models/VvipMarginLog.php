<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VvipMarginLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance_before',
        'balance_after',
    ];
}
