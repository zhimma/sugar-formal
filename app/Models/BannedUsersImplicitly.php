<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class BannedUsersImplicitly extends Model
{
    use Searchable;
    //
    protected $table = 'banned_users_implicitly';
}
