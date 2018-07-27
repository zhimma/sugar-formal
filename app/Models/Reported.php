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
        return Reported::where('reported_id', $uid)->count();
    }

    public static function report($member_id, $reported_id)
    {
        $reported = new Reported;
        $reported->member_id = $member_id;
        $reported->reported_id = $reported_id;
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
