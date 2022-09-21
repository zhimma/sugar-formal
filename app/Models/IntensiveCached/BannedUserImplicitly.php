<?php

namespace App\Models\IntensiveCached;

use App\Models\BannedUsersImplicitly as OriginalBannedUsersImplicitly;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

/**
 * Class BannedUserImplicitly
 * 實驗性功能，尚未實際使用。
 */
class BannedUserImplicitly extends OriginalBannedUsersImplicitly
{
    use Cachable;
}
