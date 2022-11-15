<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVideoVerifyRecordLog extends Model
{
    protected $table = 'user_video_verify_record_log';
    protected $guarded = ['id'];
    
    public static function addByArr($arr) {
        
        foreach($arr as $k=>$v) {
            if(is_countable($arr[$k]??null))  $arr[$k]=json_encode($v);;
        }
        
        $arr['ip'] = $_SERVER['REMOTE_ADDR'] ;
        //$arr['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        /*
        if(self::where([['user_id',$arr['user_id']],['sid',$arr['sid']],['server',$arr['server']]])->first())
        {
            unset($arr['server']);
        }
        */
        self::create($arr);
    } 
}
