<?php

namespace App\Models;

use App\Models\User;
use App\Notifications\MessageEmail;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Services\AdminService;

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
        $convert_first = $request->convert_first;

        $a = AdminAnnounce::select('*')->where('id', '=', $request->id)->first();
        $a->en_group = $request->en_group;
        $a->isVip = $request->isVip;
        $a->is_new_7 = $request->is_new_7??0;
        $a->content = $request->content_word;
        if($convert_first) {
            $a->content = str_replace('LINE_ICON', AdminService::$line_icon_html, $a->content);
            $a->content = str_replace('|$lineIcon|', AdminService::$line_icon_html, $a->content);         
            $a->content = str_replace('|$responseTime|', date("Y-m-d H:i:s"), $a->content);
            $a->content = str_replace('|$reportTime|', date("Y-m-d H:i:s"), $a->content);
            $a->content = str_replace('NOW_TIME', date("Y-m-d H:i:s"), $a->content);          
        }
        $a->sequence = $request->sequence;
        $a->login_times_alert = $request->login_times_alert;
        if(is_null($request->login_times_alert) || $request->login_times_alert==0){
            AnnouncementRead::where('announcement_id', $request->id)->delete();
        }
        $a->save();
        return true;
    }

    public static function newAnnouncement(Request $request) {
        $convert_first = $request->convert_first;
        $a = new AdminAnnounce;
        $a->en_group = $request->en_group;
        $a->isVip = $request->isVip;
        $a->is_new_7 = $request->is_new_7??0;
        $a->content = $request->content_word;
        if($convert_first) {
            $a->content = str_replace('LINE_ICON', AdminService::$line_icon_html, $a->content);
            $a->content = str_replace('|$lineIcon|', AdminService::$line_icon_html, $a->content);         
            $a->content = str_replace('|$responseTime|', date("Y-m-d H:i:s"), $a->content);
            $a->content = str_replace('|$reportTime|', date("Y-m-d H:i:s"), $a->content);
            $a->content = str_replace('NOW_TIME', date("Y-m-d H:i:s"), $a->content);          
        }  
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
