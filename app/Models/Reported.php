<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Reported extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reported';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'reported_id'
    ];

    /**
     * Find a role by name
     *
     * @param  string $name
     * @return Role
     */
    public static function cntr($uid)
    {
        $reported = Reported::select('id')->where('reported_id', $uid)->count();
        $reported_pic = ReportedPic::select('reported_pic.id')
            ->join('member_pic','member_pic.id','=','reported_pic.reported_pic_id')
            ->where('member_pic.member_id',$uid)
            ->count();
        $reported_avatar = ReportedAvatar::select('id')->where('reported_user_id',$uid)->count();
        $reported_message = Message::select('id')->where('from_id',$uid)->where('isReported',1)->count();
        return $reported + $reported_pic + $reported_avatar + $reported_message;
    }

    public static function report($member_id, $reported_id, $content = null)
    {
        $reported = new Reported;
        $reported->member_id = $member_id;
        $reported->reported_id = $reported_id;
        $reported->content = $content;
        $reported->save();
    }

    public static function findMember($member_id, $reported_id){
        $query = Reported::where('member_id', $member_id)
                 ->where('reported_id', $reported_id)
                 ->get();
        if(count($query)){
            return true;
        }
        else{
            return false;
        }
    }
}
