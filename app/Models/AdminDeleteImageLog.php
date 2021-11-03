<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminDeleteImageLog extends Model
{
    public $timestamps = false;

    public $table = "admin_delete_images_log";

    public $primaryKey = "id";

    public $fillable = [
        'member_id',
        'member_pic_id',
    ];
}
