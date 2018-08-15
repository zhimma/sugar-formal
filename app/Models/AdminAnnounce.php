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
        $a = AdminAnnounce::where('en_group', '1')->get()->first();
        $a->content = $request->engroup_1;
        $a->updated_at = Carbon::now();
        $a->save();
        $a = AdminAnnounce::where('en_group', '2')->get()->first();
        $a->content = $request->engroup_2;
        $a->updated_at = Carbon::now();
        $a->save();
        return true;
    }
}
