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
}

?>