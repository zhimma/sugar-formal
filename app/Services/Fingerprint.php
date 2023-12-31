<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fingerprint extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fingerprint';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['browser_name', 'browser_version', 'os_name', 'os_version', 'timezone', 'plugins', 'language'];

    public static function isExist($data)
    {
        $result = Fingerprint::where($data)->count();
        //var_dump(Fingerprint::where($data));
        //var_dump($data);die;
        return $result > 0 ? true : false;
    }
}
