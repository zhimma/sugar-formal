<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class MessageBoard extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'message_board';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'contents',
    ];

    //設定留言存在時間
    const SET_PERIOD = [
        '30minutes'=>'30分鐘',
        '1hours'=>'1小時',
        '3hours'=>'3小時',
        '12hours'=>'12小時',
        '1days'=>'1天',
        '3days'=>'3天',
        '7days'=>'7天',
    ];

    public static function setMessageTime($msg_id, $period){
        $data = MessageBoard::where('id', $msg_id)->first();
        if($data){
            $time='';
            switch ($period) {
                case '30minutes':
                    $time = date("Y-m-d H:i:s",strtotime("+30 minutes", strtotime($data->created_at)));
                    break;
                case '1hours':
                    $time = date("Y-m-d H:i:s",strtotime("+1 hours", strtotime($data->created_at)));
                    break;
                case '3hours':
                    $time = date("Y-m-d H:i:s",strtotime("+3 hours", strtotime($data->created_at)));
                    break;
                case '12hours':
                    $time = date("Y-m-d H:i:s",strtotime("+12 hours", strtotime($data->created_at)));
                    break;
                case '1days':
                    $time = date("Y-m-d H:i:s",strtotime("+1 days", strtotime($data->created_at)));
                    break;
                case '3days':
                    $time = date("Y-m-d H:i:s",strtotime("+3 days", strtotime($data->created_at)));
                    break;
                case '7days':
                    $time = date("Y-m-d H:i:s",strtotime("+7 days", strtotime($data->created_at)));
                    break;
                default:
                    break;
            }

            $data->set_period = $period;
            $data->message_expiry_time = $time;
            $data->save();
        }

        return true;

    }
}
