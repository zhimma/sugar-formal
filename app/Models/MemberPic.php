<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class MemberPic extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'member_pic';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'pic'
    ];

    public static function getSelf($uid)
    {
        return MemberPic::where('member_id', $uid)->get();
    }

    public static function getRand()
    {
        $pic = MemberPic::whereIn('member_id', User::where('engroup', 2)->pluck('id')->toArray())->inRandomorder()->first();
        if (isset($pic)) return $pic->pic;
        return "";
    }

    public static function getRandD()
    {
        $pic = MemberPic::whereIn('member_id', User::where('engroup', 1)->pluck('id')->toArray())->inRandomorder()->first();
        if (isset($pic)) return $pic->pic;
        return "";
    }

    public static function getPicNums($uid) {
        return MemberPic::where('member_id', $uid)->count();
    }
}
