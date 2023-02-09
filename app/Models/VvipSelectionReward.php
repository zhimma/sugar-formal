<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VvipSelectionReward extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'vvip_selection_reward';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'condition',
        'identify_method',
        'bonus_distribution',
        'limit',
        'expire_date',
        'per_person_price',
        'status',
        'user_note',
        'note',
        'notice_status'
    ];
}
