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

    public function user(){
        return $this->hasOne(User::class, 'id', 'member_id');
    }

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
        return Visited::withoutGlobalScopes()->select(\DB::raw('v.*, IF(u.is_hide_online = 1 or u.is_hide_online = 2, u.hide_online_time, max(v.created_at)) as latest_visited'))
            ->selectRaw('u2.last_login, u2.is_hide_online')
            ->selectRaw('IF((select count(*) from member_value_added_service as p where p.member_id=v.member_id and p.service_name="hideOnline" and p.active=1 and (p.expiry = "0000-00-00 00:00:00" OR p.expiry >= "'.Carbon::now().'") and u2.is_hide_online=1)>0 , (select login_time from hide_online_data  where hide_online_data.user_id=u2.id and deleted_at is null order by id desc limit 1 ), u2.last_login) as last_login_new')
            ->with(['user'])
            ->implicitWhere('v')
            ->from('visited as v')
            ->leftJoin('users as u', 'u.id', '=', 'v.member_id')
            ->leftJoin('users as u2', 'u2.id', '=', 'v.member_id')
            ->leftJoin('banned_users as b1', 'b1.member_id', '=', 'v.member_id')
            ->leftJoin('banned_users as b2', 'b2.member_id', '=', 'v.member_id')
            ->leftJoin('banned_users_implicitly as b3', 'b3.target', '=', 'v.member_id')
            ->leftJoin('banned_users_implicitly as b4', 'b4.target', '=', 'v.member_id')
            ->leftJoin('hide_online_data', 'hide_online_data.user_id', 'v.member_id')
            ->leftJoin('blocked as b5', function($join) use($uid) {
                $join->on('b5.blocked_id', '=', 'v.member_id')
                    ->where('b5.member_id', $uid); })
            ->leftJoin('blocked as b6', function($join) use($uid) {
                $join->on('b6.blocked_id', '=', 'v.visited_id')
                    ->where('b6.member_id', $uid); })
            ->leftJoin('blocked as b7', function($join) use($uid) {
                $join->on('b7.member_id', '=', 'v.member_id')
                    ->where('b7.blocked_id', $uid); })
            ->leftJoin('blocked as b8', function($join) use($uid) {
                $join->on('b8.member_id', '=', 'v.visited_id')
                    ->where('b8.blocked_id', $uid); })
            ->whereNotNull('u.id')
            ->whereNull('b1.member_id')
            ->whereNull('b2.member_id')
            ->whereNull('b3.target')
            ->whereNull('b4.target')
            ->whereNull('b5.blocked_id')
            ->whereNull('b6.blocked_id')
            ->whereNull('b7.member_id')
            ->whereNull('b8.member_id')
            ->where('u.accountStatus', 1)
            ->where('u.account_status_admin', 1)
            ->where('v.visited_id', $uid)
            ->groupBy('v.member_id')
            ->orderBy('last_login_new' ,'desc')
            ->get();
    }

    public static function visit($member_id, $curUser)
    {
        $visited = new Visited;
        $visited->member_id = $member_id;
        $visited->visited_id = $curUser->id;
        $visited->created_at = Carbon::now();
        $visited->save();
        if ($curUser != null && $curUser->meta->notifhistory !== '不通知')
        {
        // $curUser->notify(new MessageEmail($member_id, $visited_id, "瀏覽你的資料"));
        }
        return $visited->id;
    }
    
    protected static function booted()
    {
        Visited::addGlobalScope('created_at', function ($q) {
            $q->where('visited.created_at', '>', Visited::implicitLimitDate());
        });
    }  

    public function scopeImplicitWhere($q, $alias)
    {
        return $q->where($alias.'.created_at', '>', Visited::implicitLimitDate());
    }  
    
    public static function implicitLimitDate() {
        return Carbon::now()->subMonth()->startOfDay();
    }
}
