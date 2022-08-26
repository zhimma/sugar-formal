<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VvipSubOptionHighNetWorth extends Model
{
    use HasFactory;

    protected $table = 'vvip_sub_option_high_net_worth';

    public $type = 'high_net_worth';
}
