<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpamMessagePercentIn7Days extends Model
{
    protected $table = 'spam_message_percent_in_7_days';

    protected $fillable = [
        'user_id',
        'percent',
        'updated_at'
    ];

    // public $timestamps = false;
}
