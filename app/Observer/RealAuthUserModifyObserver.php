<?php

namespace App\Observer;

use App\Models\RealAuthUserModify;
use App\Services\RealAuthObserveService;

class RealAuthUserModifyObserver
{
	public function __construct(RealAuthObserveService $service) {
		$this->service = $service;
	} 	
	
    public function retrieved(RealAuthUserModify $modify_entry)
    {
    }
    
    public function created(RealAuthUserModify $modify_entry)
    {
        $this->service->riseByModifyEntry($modify_entry)->connectModifyAndPatch();
    }    

    public function saved(RealAuthUserModify $modify_entry)
    {

    }

    public function deleted(RealAuthUserModify $modify_entry)
    {

    }

}