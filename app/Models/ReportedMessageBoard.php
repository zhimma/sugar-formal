<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportedMessageBoard extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reported_message_board';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'message_board_id',
    ];
}
