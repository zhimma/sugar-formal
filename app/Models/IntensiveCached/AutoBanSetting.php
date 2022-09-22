<?php

namespace App\Models\IntensiveCached;

use App\Models\SetAutoBan;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

/**
 * Class AutoBanSetting
 * 實驗性功能，尚未實際使用。
 */
class AutoBanSetting extends SetAutoBan
{
    use Cachable;
}