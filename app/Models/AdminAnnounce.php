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

    public static function saveAnnouncement(Request $request) {
        /*$a = AdminAnnounce::where('en_group', '1')->get()->first();
        $a->content = $request->engroup_1;
        $a->updated_at = Carbon::now();
        $a->save();
        $a = AdminAnnounce::where('en_group', '2')->get()->first();
        $a->content = $request->engroup_2;
        $a->updated_at = Carbon::now();
        $a->save();*/
        $a = AdminAnnounce::select('*')->where('id', '=', $request->id)->first();
        $a->en_group = $request->en_group;
        $a->isVip = $request->isVip;
        $a->is_new_7 = $request->is_new_7??0;
        $a->content = $request->content_word;
        $a->sequence = $request->sequence;
        $a->login_times_alert = $request->login_times_alert;
        $a->save();
        return true;
    }

    public static function newAnnouncement(Request $request) {
        $a = new AdminAnnounce;
        $a->en_group = $request->en_group;
        $a->isVip = $request->isVip;
        $a->is_new_7 = $request->is_new_7??0;
        $a->content = $request->content_word;
        $a->sequence = $request->sequence;
        $a->login_times_alert = $request->login_times_alert;
        $a->save();
        return true;
    }

    public static function deleteAnnouncement(Request $request) {
        $a = AdminAnnounce::select('*')->where('id', '=', $request->id)->delete();
        $b = AnnouncementRead::select('*')->where('announcement_id', '=', $request->id)->delete();
        return $a && $b;
    }
}
