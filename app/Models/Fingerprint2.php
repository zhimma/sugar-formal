<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fingerprint2 extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fingerprint2';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = ['browser_name', 'browser_version', 'os_name', 'os_version', 'timezone', 'plugins', 'language'];

    public function __construct(array $attributes = []){
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
    }

    public static function isExist($data)
    {
        $result = Fingerprint::where($data)->count();
        //var_dump(Fingerprint::where($data));
        //var_dump($data);die;
        return $result > 0 ? true : false;
    }
}
