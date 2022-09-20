<?php

namespace App\Models\IntensiveCached;

use App\Models\User as OriginalUser;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class User extends OriginalUser
{
    use Cachable;
}