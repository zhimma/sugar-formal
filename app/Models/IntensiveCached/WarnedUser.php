<?php

namespace App\Models\IntensiveCached;

use App\Models\SimpleTables\warned_users;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

/**
 * Class WarnedUser
 * 實驗性功能，尚未實際使用。
 */
class WarnedUser extends warned_users
{
    use Cachable;
}
