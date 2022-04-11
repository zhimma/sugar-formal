<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorIDUser extends Model
{
    //
    protected $table = 'visitor_id_user';

    public function visitorID(){
        return $this->hasOne(VisitorID::class, 'id', 'visitor_id');
    }
}
