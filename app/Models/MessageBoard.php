<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageBoard extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'message_board';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'contents',
    ];
}
