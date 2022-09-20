<?php

namespace App\Models\IntensiveCached;

use App\Models\SimpleTables\banned_users;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class BannedUser extends banned_users
{
    use Cachable;
}
