<?php
namespace App\Services;

use App\Repositories\RealAuthApplyLogRepository;
use App\Repositories\RealAuthModifyPatchRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Log;

class RealAuthObserveService {
    protected $_rised_from = '';
    protected $_error_msg = '';
    public function __construct(
        RealAuthApplyLogRepository $apply_log_repo
        ,RealAuthModifyPatchRepository $modify_patch_repo
    ) 
    {
        $this->apply_log_repo = $apply_log_repo;
        $this->modify_patch_repo = $modify_patch_repo;
    } 
    
    public function apply_log_repo() 
    {

        return $this->apply_log_repo;
    }
    
    public function modify_patch_repo() 
    {

        return $this->modify_patch_repo;
    }    
    
    public function riseByApplyEntry($apply_entry) 
    {
        $this->apply_log_repo()->riseByApplyEntry($apply_entry);
        return $this;
    }
    
    public function riseByApplyId($apply_id) 
    {
        $this->apply_log_repo()->riseByApplyId($apply_id);
        return $this;
    } 
    
    public function riseByModifyEntry($modify_entry) 
    {
        $this->modify_patch_repo()->riseByModifyEntry($modify_entry);
        return $this;
    }
    
    public function riseByModifyId($modify_id) 
    {
        $this->modify_patch_repo()->riseByModifyId($modify_id);
        return $this;
    }     

    public function saveApplyLog() 
    {
        return $this->apply_log_repo()->saveApplyLog();
    }
    
    public function createFirstModify($apply_entry=null) 
    {
        if(!$apply_entry)
            $apply_entry = $this->apply_log_repo()->apply_entry();
        
        if(!$apply_entry) return;
        
        if($apply_entry->height_modify_id 
            || $apply_entry->weight_modify_id 
            || $apply_entry->exchange_period_modify_id  
            || $apply_entry->video_modify_id   
            || $apply_entry->pic_modify_id   
            || $apply_entry->reply_modify_id                 
        ) return;
        
        return $apply_entry->real_auth_user_modify()->firstOrNew()->createNewApplyModify();
    }   

    public function connectModifyAndPatch()
    {
        return $this->modify_patch_repo()->connectModifyAndPatch();
    }
}


