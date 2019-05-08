<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AnnouncementRead extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'announcement_read';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
}
