<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostsMood extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'posts_mood';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'contents',
        'images',
        'anonymous',
        'combine',
        'agreement',
        'tag_user_id',
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
