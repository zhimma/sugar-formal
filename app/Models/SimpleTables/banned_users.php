<?php

namespace App\Models\SimpleTables;

use Illuminate\Database\Eloquent\Model;

class banned_users extends Model
{
    //
    protected $table = 'banned_users';
    public function __construct(array $attributes = []){
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        if(env("APP_ENV", "local") != "local"){
            $this->connection = 'mysql_fp';
        }
    }
}
