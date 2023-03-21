<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackUser extends Model
{
    public $table = "track_user";

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
