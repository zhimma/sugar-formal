<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VvipSubOptionEntrepreneur extends Model
{
    use HasFactory;

    protected $table = 'vvip_sub_option_entrepreneur';

    public $type = 'entrepreneur';
}
