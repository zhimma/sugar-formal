<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VvipApplication extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'vvip_application';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
