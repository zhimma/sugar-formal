<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Outl1ne\ScoutBatchSearchable\BatchSearchable;

class BannedUsersImplicitly extends Model
{
    use BatchSearchable;
    //
    protected $table = 'banned_users_implicitly';
}
