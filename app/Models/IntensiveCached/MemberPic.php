<?

namespace App\Models\IntensiveCached;

use App\Models\MemberPic as OriginalMemberPic;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class MemberPic extends OriginalMemberPic
{
    use Cachable;
}