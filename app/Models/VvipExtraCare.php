<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VvipExtraCare extends VvipParentOption
{
    use HasFactory;

    protected $table = 'vvip_option_extra_care';
}
