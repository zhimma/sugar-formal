<?php
namespace App\Repositories;
use App\Models\RealAuthUserApply;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Log;

class RealAuthApplyLogRepository {
    public function __construct(
        RealAuthUserApply $apply_entry
    ) {        
        $this->riseByApplyEntry($apply_entry);         
    }  

    public function riseByApplyEntry($apply_entry) 
    {
        $this->apply_entry = $apply_entry;
        return $this;
    }
    
    public function riseByApplyId($apply_id) 
    {
        $this->apply_entry = $this->apply_entry->find($apply_id);
        return $this;
    }    
    
    public function apply_entry()
    {
        return $this->apply_entry;
    }
    
    public function user() {
        return $this->apply_entry()->user;
    }
    
    public function saveApplyLog() {
        $apply_entry = $this->apply_entry();
        $user = $apply_entry->user;
        $umeta = $user->meta;
        $data = $apply_entry->toArray();
        $data['apply_id'] = $data['id'];
        unset($data['id']);

        $data['apply_created_at'] = $data['created_at'];
        $data['apply_updated_at'] = $data['updated_at'];
        unset($data['created_at']);
        unset($data['updated_at']);

        return $apply_entry->real_auth_user_apply_log()->create($data);

    }
}
