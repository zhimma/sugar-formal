<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VvipSubOptionProfessional extends Model
{
    use HasFactory;

    protected $table = 'vvip_sub_option_professional';

    public $type = 'professional';
}
