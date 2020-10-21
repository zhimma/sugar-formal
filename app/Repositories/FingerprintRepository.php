<?php

namespace App\Repositories;

use App\Models\Fingerprint;

class FingerprintRepository
{
    /**
     * @var model fingerprint
     *
     */
    protected $model;
    protected $fillable = [
    	"IP",
    	"fontlist",
    	"inc",
    	"gpu",
    	"timezone",
    	"resolution",
    	"plugins",
    	"cookies",
    	"localstorage",
    	"gpuimgs",
    	"adblock",
    	"cpu_cores",
    	"canvas_test",
    	"audio",
    	"langsdetected",
    	"agent",
    	"accept",
    	"encoding",
    	"language",
    	"fonts",
    	"WebGL",
    	"browser_fingerprint",
    	"computer_fingerprint_1"
    ];

    public function __construct(Fingerprint $model)
    {
    	$this->model = $model;
    }

    public function insert($data)
    {
		$this->model->insert($data);
    }
}

?>