<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MultipleLogin extends Model
{
    //
    public function original_user(){
        return $this->hasOne(User::class, 'id', 'original_id');
    }

    public function new_user(){
        return $this->hasOne(User::class, 'id', 'new_id');
    }
}
