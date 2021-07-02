<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogUserLogin extends Model
{
    public $timestamps = false;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'log_user_login';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'cfp_id', 'userAgent', 'ip', 'created_at'];

    public function setReadOnly() {
        $this->guarded =  ['*'];
    }
}
