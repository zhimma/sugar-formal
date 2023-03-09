<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForumManageChat extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'forum_id',
        'from_id',
        'to_id',
        'content',
        'pic',
        'read'
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'forum_manage_chat';

    public static function getManageChat($uid, $sid, $fid) {

        return $query = ForumManageChat::where('forum_id', $fid)
            ->where([['to_id', $uid],['from_id', $sid]])
            ->orWhere([['from_id', $uid],['to_id', $sid]])
            ->orderBy('created_at', 'desc');

    }
    
    public function from_user()
    {
        return $this->belongsTo(User::class, 'from_id', 'id');
    }    
    /*
    public function from_forum_manage()
    {
        //return $this->belongsTo(ForumManage::class, 'from_id', 'user_id')->where('forum_id',$this->forum_id);
        //return $this->hasOneThrough(ForumManage::class,Forum::class,'id','forum_id','forum_id','id')->where('user_id=forum_manage_chat.from_id');        
    }
    
    public function from_forum_manager()
    {
        return $this->from_forum_manage()->where('is_manager',1);
    }    
    
    public function to_forum_manage()
    {
        return $this->belongsTo(ForumManage::class, 'to_id', 'user_id');
    } 

    public function to_forum_manager()
    {
        return $this->to_forum_manage()->where('is_manager',1);
    } 
     */   
}
