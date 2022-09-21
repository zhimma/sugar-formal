<?php

namespace App\Models\IntensiveCached;

use App\Models\LogUserLogin as OriginalLogUserLogin;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

/**
 * Class LogUserLogin
 * 實驗性功能，尚未實際使用。
 */
class LogUserLogin extends OriginalLogUserLogin
{
    use Cachable;
}