<?

namespace App\Models\IntensiveCached;

use App\Models\Message_new as OriginalMessage;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Message extends OriginalMessage
{
    use Cachable;
}