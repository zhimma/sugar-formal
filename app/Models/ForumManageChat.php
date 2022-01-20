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
}
