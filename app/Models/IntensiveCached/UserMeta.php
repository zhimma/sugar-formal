<?php

namespace App\Models\IntensiveCached;

use App\Models\UserMeta as OriginalUserMeta;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

/**
 * Class UserMeta
 * 實驗性功能，尚未實際使用。
 */
class UserMeta extends OriginalUserMeta
{
    use Cachable;
}