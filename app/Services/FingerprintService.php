<?php

namespace App\Services;

use App\Models\Fingerprint;
use App\Models\User;

class FingerprintService{

	// Fingerprint Model
	public $model;
    
    public function __construct(Fingerprint $fingerprint){
    	$this->model = $fingerprint;
    }

    public static function isExist($fingerprint)
    {
    	$result = Fingerprint::where($fingerprint)->count();
        return $result > 0 ? true : false;
    }
    
}

?>