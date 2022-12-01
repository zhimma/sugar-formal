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
        'action_id',
        'ip',
    ];

    public function operator_user()
    {
        return $this->hasOne(User::class, 'id', 'operator');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'target_id');
    }
}
