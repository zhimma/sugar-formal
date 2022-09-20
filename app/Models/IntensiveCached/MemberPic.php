<?php

namespace App\Models\IntensiveCached;

use App\Models\MemberPic as OriginalMemberPic;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

/**
 * Class MemberPic
 * 實驗性功能，尚未實際使用。
 */
class MemberPic extends OriginalMemberPic
{
    use Cachable;
}