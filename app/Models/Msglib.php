<?php

namespace App\Models;

use Auth;
use App\Models\User;
use App\Models\Blocked;
use App\Models\SimpleTables\banned_users;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\MessageEmail;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Msglib extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'msglib';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'message',
        'kind'
    ];

    static $date = null;

    
}
