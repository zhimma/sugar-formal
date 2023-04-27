<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;
    
    public $table = "notifications";

    public $primaryKey = "id";

    public $timestamps = true;

    public $fillable = [
        'user_id',
        'flag',
        'uuid',
        'title',
        'details',
        'is_read',
    ];

    public static $rules = [
        'title' => 'required',
        'details' => 'required',
        'flag' => 'required',
        'user_id' => 'required',
    ];


}
