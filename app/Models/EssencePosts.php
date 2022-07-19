<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EssencePosts extends Model
{
    use SoftDeletes;

    protected $table = 'essence_posts';
    protected $fillable = [
        'category',
        'share_with',
        'title',
        'contents',
        'verify_status',
    ];


    const CATEGORY = [
        1 => '教學經驗文',
        2 => '包養故事文',
        3 => '平台經驗/介紹文',
    ];

    const SHARE_WITH = [
        1 => '男會員',
        2 => '女會員',
    ];

    const STATUS_PENDING = 0;
    const STATUS_FAILED = 1;
    const STATUS_PASSED = 2;

    const VERIFY_STATUS = [
        0 => '待審核',
        1 => '未通過',
        2 => '已通過',
    ];

}
