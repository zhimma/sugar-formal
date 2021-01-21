<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminActionLog extends Model
{
    public $table = "admin_action_log";

    public $primaryKey = "id";

    public $fillable = [
        'operator',
        'target_id',
        'act',
        'ip',
    ];
}
