<?php

namespace App\Models\IntensiveCached;

use App\Models\User as OriginalUser;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

/**
 * Class User
 * 實驗性功能，尚未實際使用。
 */
class User extends OriginalUser
{
    use Cachable;
}