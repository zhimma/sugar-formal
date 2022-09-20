<?

namespace App\Models\IntensiveCached;

use App\Models\UserMeta as OriginalUserMeta;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class UserMeta extends OriginalUserMeta
{
    use Cachable;
}