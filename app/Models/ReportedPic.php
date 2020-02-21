<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ReportedPic extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reported_pic';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'reporter_id',
        'reported_pic_id'
    ];

    public static function report($reporter_id, $reported_pic_id, $content = null)
    {
        $reported = new ReportedPic;
        $reported->reporter_id = $reporter_id;
        $reported->reported_pic_id = $reported_pic_id;
        $reported->content = $content;
        $reported->save();
    }

    public static function findMember($reporter_id, $reported_pic_id){
        $query = ReportedPic::where('reporter_id', $reporter_id)
                 ->where('reported_pic_id', $reported_pic_id)
                 ->get();
        if(count($query)){
            return true;
        }
        else{
            return false;
        }
    }
}
