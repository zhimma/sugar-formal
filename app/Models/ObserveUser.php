<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObserveUser extends Model
{
    public $table = "observe_user";

    public $fillable = [
        'user_id',
        'reason',
        'admin_id'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
