<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminMenuItems extends Model
{
    public $table = "admin_menu_items";

    public $primaryKey = "id";

    public $fillable = [
        'title',
        'route_path',
        'status',
        'sort',
    ];
}
