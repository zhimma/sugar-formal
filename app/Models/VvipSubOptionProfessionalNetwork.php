<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VvipSubOptionProfessionalNetwork extends Model
{
    use HasFactory;

    protected $table = 'vvip_sub_option_professional_network';

    public $type = 'professional_network';
}
