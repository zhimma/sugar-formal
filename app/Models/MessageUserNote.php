<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageUserNote extends Model
{
    use SoftDeletes;

    protected $table = 'message_user_note';
}
