<?php

namespace App\Models\IntensiveCached;

use App\Models\BannedUsersImplicitly as OriginalBannedUsersImplicitly;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class BannedUserImplicitly extends OriginalBannedUsersImplicitly
{
    use Cachable;
}
