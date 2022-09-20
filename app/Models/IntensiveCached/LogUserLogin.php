<?

namespace App\Models\IntensiveCached;

use App\Models\LogUserLogin as OriginalLogUserLogin;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class LogUserLogin extends OriginalLogUserLogin
{
    use Cachable;
}