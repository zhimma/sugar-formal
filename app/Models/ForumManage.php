<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForumManage extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'forum_manage';

    //啟動使用者先前的討論區功能
    public static function open_forum_active($user_id)
    {
        ForumManage::where('user_id',$user_id)->update(['active' => 1]);
    }

    //關閉使用者先前的討論區功能
    public static function close_forum_active($user_id)
    {
        ForumManage::where('user_id',$user_id)->update(['active' => 0]);
    }

    //刪除使用者的討論區功能 刪除後可重新申請
    public static function delete_forum_user_join($user_id)
    {
        ForumManage::where('user_id',$user_id)->delete();
    }
}
