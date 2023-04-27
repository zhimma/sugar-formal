<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feature extends Model
{
    use HasFactory;    
    
    public $table = "features";

    public $primaryKey = "id";

    public $timestamps = false;

    public $fillable = [
        'key',
        'is_active',
    ];

    public static $rules = [
        'key' => 'required',
    ];
}
