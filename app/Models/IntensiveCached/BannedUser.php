<?php

namespace App\Models\IntensiveCached;

use App\Models\SimpleTables\banned_users;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

/**
 * Class BannedUser
 * 實驗性功能，尚未實際使用。
 */
class BannedUser extends banned_users
{
    use Cachable;
}
