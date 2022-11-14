<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminMenuItemFolder extends Model
{
    protected $table = 'admin_menu_item_folder';

    public function items()
    {
        return $this->hasMany(AdminMenuItemXref::class, 'folder_id', 'id');
    }
}
