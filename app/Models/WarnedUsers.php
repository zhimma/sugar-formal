<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class WarnedUsers extends Model
{
    use HasFactory, Searchable;

    protected $table = 'warned_users';
}
