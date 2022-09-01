<?php

namespace App\Models;

use Auth;
use App\Models\User;
use App\Models\Blocked;
use App\Models\SimpleTables\banned_users;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\MessageEmail;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * @method static \Illuminate\Database\Eloquent\Builder kind(string $value)
 */
class Msglib extends Model
{
    const KIND_SMSG = 'smsg';
    const KIND_REAL_AUTH = 'real_auth';
    const KIND_DELPIC = 'delpic';
    const KIND_REPORT = 'report';
    const KIND_REPORTED = 'reported';
    const KIND_ANONYMOUS = 'anonymous';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'msglib';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'message',
        'kind'
    ];

    static $date = null;

    // =========================================================================
    // = Scopes
    // =========================================================================

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $value
     * @return void
     * 
     * @throws InvalidArgumentException
     */
    public function scopeKind($query, string $value)
    {
        if (!in_array($value, [
            static::KIND_SMSG,
            static::KIND_REAL_AUTH,
            static::KIND_DELPIC,
            static::KIND_REPORT,
            static::KIND_REPORTED,
            static::KIND_ANONYMOUS,
        ])) {
            throw new InvalidArgumentException('Invalid kind value of "Msglib" model, given: ' . $value);
        }

        $query->where('kind', $value);
    }
}
