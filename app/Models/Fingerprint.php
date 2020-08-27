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
    protected $connection = 'sg_database';
    protected $table = 'fingerprint';
    public static function isExist($data)
    {
        $result = Fingerprint::where($data)->count();
        //var_dump(Fingerprint::where($data));
        //var_dump($data);die;
        return $result > 0 ? true : false;
    }
}
