<? 

namespace App\Models\IntensiveCached;

use App\Models\SetAutoBan;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class AutoBanSetting extends SetAutoBan
{
    use Cachable;
}