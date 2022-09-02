<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VvipSubOptionHighAssets extends Model
{
    use HasFactory;

    protected $table = 'vvip_sub_option_high_assets';

    public $type = 'high_assets';
}
