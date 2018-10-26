<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class MemberFav extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'member_fav';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'member_fav_id'
    ];

    public static function fav($member_id, $fav_id)
    {
        $fav = new MemberFav();
        $fav->member_id = $member_id;
        $fav->member_fav_id = $fav_id;
        $fav->save();
    }

    public static function findBySelf($uid)
    {
        return Visited::unique(MemberFav::where([['member_id', $uid],['member_fav_id', '!=', $uid]])->distinct()->orderBy('created_at', 'desc')->get(), "member_fav_id");
    }

    public static function remove($member_id, $fav_id)
    {
        $fav = MemberFav::where('member_id', $member_id)->where('member_fav_id', $fav_id)->get()->first();
        $fav->delete();
    }
}
