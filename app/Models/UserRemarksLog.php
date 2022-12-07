<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRemarksLog extends Model
{
    protected $table = 'user_remarks_log';

    public static function insert_commit($operator_id, $user_id, $commit)
    {
        $remark = new UserRemarksLog();
        $remark->operator_id = $operator_id;
        $remark->target_user_id = $user_id;
        $remark->commit = $commit;
        $remark->save();
    }

    public function operator_user()
    {
        return $this->hasOne(User::class, 'id', 'operator_id');
    }
}
