<?php

namespace App\Models;

use App\Models\User;
use App\Notifications\MessageEmail;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Visited extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'visited';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'visited_id',
        'created_at'
    ];

    public static function unique($array,$key_id = null, $key_create = null) {

        if(null == $key_id){
            return array_unique($array);
        }
        $keys = [];
        $ret = [];
        foreach($array as $elem){
            $arrayKey = (is_array($elem)) ? $elem[$key_id]:$elem->$key_id;
            $diff_days = Visited::getDiffDays($elem[$key_create]);
            //echo $diff_days;

            if(in_array($arrayKey,$keys) || $diff_days > 30){
                continue;
            }
            array_push($keys,$arrayKey);

            $ret[] = $elem;
        }
        return $ret;
    }

    public static function getDiffDays($value) {
        $now = Carbon::now();
        $create_time = Carbon::parse($value);
        $diff_days = $create_time->diffInDays($now);

        return $diff_days;
    }

    public static function findBySelf($uid)
    {
        //加入排除封鎖名單
        $blocks = Blocked::select('blocked_id')->where('member_id', $uid)->get();
        $bannedUsers = \App\Services\UserService::getBannedId();
        return Visited::unique(Visited::where('visited_id', $uid)->whereNotIn('member_id',$blocks)->whereNotIn('member_id',$bannedUsers)->distinct()->orderBy('created_at', 'desc')->get(), "member_id", "created_at");

    }

    public static function visit($member_id, $visited_id)
    {
        $visited = new Visited;
        $visited->member_id = $member_id;
        $visited->visited_id = $visited_id;
        $visited->created_at = Carbon::now();
        $visited->save();
        $curUser = User::findById($visited_id);
        if ($curUser != null && $curUser->meta_()->notifhistory !== '不通知')
        {
        // $curUser->notify(new MessageEmail($member_id, $visited_id, "瀏覽你的資料"));
        }
    }
}
