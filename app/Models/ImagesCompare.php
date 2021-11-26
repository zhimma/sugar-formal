<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagesCompare extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'images_compare';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = '';

    protected $guarded = ['id'];

}