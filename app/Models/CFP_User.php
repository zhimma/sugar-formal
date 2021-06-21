<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CFP_User extends Model
{
    //
    protected $table = 'cfp_user';

    public function cfp(){
        return $this->hasOne(CustomFingerPrint::class, 'id', 'cfp_id');
    }
}
