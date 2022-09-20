<?php

namespace App\Models\IntensiveCached;

use App\Models\SimpleTables\warned_users;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class WarnedUser extends warned_users
{
    use Cachable;
}
