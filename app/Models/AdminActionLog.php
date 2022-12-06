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

    public function user_meta()
    {
        return $this->hasOne(UserMeta::class, 'user_id', 'target_id');
    }

    public function action_name()
    {
        if($this->action_id == 0)
        {
            return $this->act;
        }
        else
        {
            return AdminActionItem::where('id', $this->action_id)->first()->action_name;
        }
    }

    public static function insert_log($operator_id, $ip, $user_id, $action_name, $action_id = 0)
    {
        AdminActionLog::create([
            'operator'    => $operator_id,
            'target_id'  => $user_id,
            'act'         => $action_name,
            'action_id'     => $action_id,
            'ip'          => $ip
        ]);
    }
}
