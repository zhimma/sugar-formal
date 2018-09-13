<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ReportedAvatar extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reported_avatar';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'reporter_id',
        'reported_user_id'
    ];

    public static function report($reporter_id, $reported_user_id, $content = null)
    {
        $reported = new ReportedAvatar;
        $reported->member_id = $reporter_id;
        $reported->reported_id = $reported_user_id;
        $reported->content = $content;
        $reported->save();
    }

    public static function findMember($reporter_id, $reported_user_id){
        $query = ReportedAvatar::where('reporter_id', $reporter_id)
                 ->where('reported_user_id', $reported_user_id)
                 ->get();
        if(count($query)){
            return true;
        }
        else{
            return false;
        }
    }
}
