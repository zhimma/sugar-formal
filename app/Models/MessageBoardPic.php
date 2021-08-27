<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageBoardPic extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'message_board_pic';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'msg_board_id',
        'member_id',
        'pic',
        'pic_origin_name',
    ];
}
