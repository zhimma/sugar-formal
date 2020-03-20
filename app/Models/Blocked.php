<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Blocked extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'blocked';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'blocked_id'
    ];

    public static function getAllBlock($uid) {
        return Blocked::where('member_id', $uid)->get();
    }

    public static function getAllBlockedId($uid) {
        return Blocked::select('blocked_id')->where('member_id', $uid)->get();
    }

    public static function getBlockTime($uid, $sid) {
        $block = Blocked::where([['member_id', $uid],['blocked_id', $sid]])->first();
        //echo $block->created_at;
        return $block;
    }
    /**
     * Find a role by name
     *
     * @param  string $name
     * @return Role
     */
    public static function findBySelf($uid)
    {
        return Visited::where('visited_id', $uid)->orderBy('created_at', 'desc')->get();
    }

    public static function isBlocked($uid, $bid) {
        $isBlocked = Blocked::where([['member_id', $uid],['blocked_id', $bid]])->count();

        if($isBlocked == 1) return true;
        return false;
    }

    public static function unblock($uid, $bid) {
        return Blocked::where([['member_id', $uid],['blocked_id', $bid]])->delete();
    }

    public static function unblockAll($uid) {
        return Blocked::where('member_id', $uid)->delete();
    }

    public static function block($member_id, $blocked_id)
    {
        $blocked = new Blocked;
        $blocked->member_id = $member_id;
        $blocked->blocked_id = $blocked_id;
        $blocked->save();
    }
}
