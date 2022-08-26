<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VvipSubOptionLifeCare extends Model
{
    use HasFactory;

    protected $table = 'vvip_sub_option_life_care';

    public $type = 'life_care';
}
