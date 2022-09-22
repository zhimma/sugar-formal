<?php

namespace App\Models\IntensiveCached;

use App\Models\Message_new as OriginalMessage;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

/**
 * Class Message
 * 實驗性功能，尚未實際使用。
 */
class Message extends OriginalMessage
{
    use Cachable;
}