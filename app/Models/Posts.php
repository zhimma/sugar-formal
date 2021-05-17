<?php

namespace App\Models;

use Auth;
use App\Models\User;
use App\Models\Blocked;
use App\Models\SimpleTables\banned_users;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\MessageEmail;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Posts extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'contents',
        'anonymous',
        'combine',
        'agreement',
    ];

    public static function showContent($content)
    {
        $pattern = array(
            '/ /',//半角下空格
            '/　/',//全角下空格
            '/\r\n/',//window 下换行符
            '/\n/',//Linux && Unix 下换行符
        );
        $replace = array('&nbsp;','&nbsp;','<br />','<br />');
        return preg_replace($pattern, $replace,$content );
    }
    
}
