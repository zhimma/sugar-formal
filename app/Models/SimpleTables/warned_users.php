<?php

namespace App\Models\SimpleTables;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class warned_users extends Model
{
    // use SoftDeletes;

    //
    protected $table = 'warned_users';
}
