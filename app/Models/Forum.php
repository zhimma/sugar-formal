<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Forum extends Model
{
    use SoftDeletes;

    protected $table = 'forum';

    public function posts_of_forum()
    {
        return $this->hasMany(ForumPosts::class, 'forum_id', 'id');
    }
    
    public function forum_manage()
    {
        return $this->hasMany(ForumManage::class, 'forum_id', 'id');
    } 
    
    public function forum_manage_passed()
    {
        return $this->hasMany(ForumManage::class, 'forum_id', 'id')->where('status',1);
    } 

    public function forum_manager()
    {
        return $this->forum_manage_passed()->where('is_manager',1);
    }    

    //警示討論區
    public static function warn_forum($forum_id)
    {
        Forum::where('id',$forum_id)->update(['is_warned' => 1]);
    }

    //關閉討論區
    public static function close_forum($forum_id)
    {
        $forum_data = Forum::where('id',$forum_id);
        $forum_data->update(['status' => 0]);
        $forum_data->delete();
    }
}
