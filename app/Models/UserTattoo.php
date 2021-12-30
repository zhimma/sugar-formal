<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTattoo extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_tattoo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = '';

    protected $guarded = ['id'];

}