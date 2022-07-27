<?php

namespace App\Models;

use App\Models\RealAuthQuestion;
use Illuminate\Database\Eloquent\Model;

class ExchangePeriodName extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'exchange_period_name';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    
    protected $guarded = ['id'];
    

}
