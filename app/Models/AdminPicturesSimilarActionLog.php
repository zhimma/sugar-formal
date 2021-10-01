<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AdminPicturesSimilarActionLog extends Model
{
    use HasFactory;

    public function target_user()
    {
        return $this->belongsTo(User::class, 'target_id', 'id');
    }

    public function operator_user()
    {
        return $this->belongsTo(User::class, 'operator_id', 'id');
    }
    
}
