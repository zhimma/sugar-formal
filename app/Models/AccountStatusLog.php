<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountStatusLog extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'account_status_log';

    public $primaryKey = 'id';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'reasonType',
        'reported_id',
        'content',
        'image',
    ];
}
