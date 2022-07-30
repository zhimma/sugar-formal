<?php

namespace App\Observer;

use App\Models\RealAuthUserApply;
use App\Services\RealAuthObserveService;

class RealAuthUserApplyObserver
{
	public function __construct(RealAuthObserveService $service) {
		$this->service = $service;
	} 	
	
    public function retrieved(RealAuthUserApply $apply_entry)
    {
    }
    
    public function created(RealAuthUserApply $apply_entry)
    {
        $this->service->riseByApplyEntry($apply_entry)->saveApplyLog();
        $this->service->createFirstModify();
    }    


    public function saved(RealAuthUserApply $apply_entry)
    {
        $this->service->riseByApplyEntry($apply_entry)->saveApplyLog();
    }


    public function deleted(RealAuthUserApply $apply_entry)
    {

    }

}