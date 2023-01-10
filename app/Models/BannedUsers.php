<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Outl1ne\ScoutBatchSearchable\BatchSearchable;

class BannedUsers extends Model
{
    use HasFactory, BatchSearchable;

    protected $table = 'banned_users';
}
