<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberPic extends Model
{
    use SoftDeletes;

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

    /*
    |--------------------------------------------------------------------------
    | relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class, 'member_id', 'id');
    }
    
    
    public static function getSelf($uid)
    {
        return MemberPic::where('member_id', $uid)->whereRaw("pic not LIKE '%IDPhoto%'")->get();
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

    public static function getSelfIDPhoto($uid)
    {
        return MemberPic::where('member_id', $uid)->whereRaw("pic LIKE '%IDPhoto%'")->get();
    }
}
