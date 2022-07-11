<?php
namespace App\Repositories;
use App\Models\RealAuthUserModify;
use App\Models\RealAuthUserPatch;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Log;

class RealAuthModifyPatchRepository {
    public function __construct(
        RealAuthUserModify $modify_entry
    ) {        
        $this->riseByModifyEntry($modify_entry);         
    }  

    public function riseByModifyEntry($modify_entry) 
    {
        $this->modify_entry = $modify_entry;
        return $this;
    }
    
    public function riseByModifyId($modify_id) 
    {
        $this->modify_entry = $this->modify_entry->find($modify_id);
        return $this;
    }    
    
    public function modify_entry()
    {
        return $this->modify_entry;
    }
    
    public function user() {
        return $this->modify_entry()->real_auth_user_apply->user;
    }
    
    public function connectModifyAndPatch() {
        $modify_entry = $this->modify_entry();
        $user = $this->user();
        $patch_entry = $user->real_auth_user_patch()
                        ->where([['apply_id_shot',$modify_entry->apply_id],['item_id',$modify_entry->item_id],['apply_status_shot',$modify_entry->apply_status_shot]])
                        ->whereNull('modify_id')->orderByDesc('id')->first();

        if($patch_entry) {
            
            $modify_entry->patch_id_shot = $patch_entry->id;
            $modify_entry->save();
        }
 
    }
    

}
