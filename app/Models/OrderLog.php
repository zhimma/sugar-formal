<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    use HasFactory;

    protected $table = 'order_log';

    public static function addToLog($user_id, $order_id, $content) {
        $log = new OrderLog();
        $log->user_id = $user_id;
        $log->order_id = $order_id;
        $log->content = $content;
        $log->save();
    }
}
