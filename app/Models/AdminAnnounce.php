<?php

namespace App\Models;

use App\Models\User;
use App\Notifications\MessageEmail;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminAnnounce extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admin_announcement';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    public static function editAnnouncement(Request $request) {
        //$a = AdminAnnounce::select('*')->where('id', '=', $request->id)->first();
        $a = AdminAnnounce::get()->first();
        $a->content = $request->content;
        $a->updated_at = Carbon::now();
        $a->save();
        return true;
    }
}
