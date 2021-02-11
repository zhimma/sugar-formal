<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    //
    protected $table = 'evaluation';

    public function user(){
        return $this->hasOne(User::class, 'id', 'from_id');
    }
}
