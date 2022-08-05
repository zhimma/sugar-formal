<?php

namespace App\Models;

use App\Models\User;
use App\Models\RealAuthType;
use App\Models\RealAuthUserApplyLog;
use App\Models\RealAuthUserReply;
use App\Models\UserVideoVerifyRecord;
use App\Models\RealAuthUserPatch;
use Illuminate\Database\Eloquent\Model;

class RealAuthUserApply extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    } 

    public function real_auth_type()
    {
        return $this->belongsTo(RealAuthType::class, 'auth_type_id', 'id');
    }  

    public function user_video_verify_record() 
    {      
        return $this->belongsTo(UserVideoVerifyRecord::class, 'video_record_id', 'id');
    } 
    
    public function real_auth_user_apply_log() 
    {
        return $this->hasMany(RealAuthUserApplyLog::class,'apply_id','id');
    }     

    public function real_auth_user_reply() 
    {
        return $this->hasMany(RealAuthUserReply::class,'apply_id','id');
    } 
    
    public function real_auth_user_patch() 
    {
        return $this->hasMany(RealAuthUserPatch::class,'apply_id_shot','id');
    }     

    public function real_auth_user_modify() 
    {
        return $this->hasMany(RealAuthUserModify::class,'apply_id','id');
    }    
    
    public function real_auth_user_modify_pic() 
    {
        return $this->hasManyThrough(RealAuthUserModifyPic::class,RealAuthUserModify::class,'apply_id','modify_id');
    } 
    
    public function actual_unchecked_rau_modify_pic()
    {
        return $this->real_auth_user_modify_pic()->whereHas('real_auth_user_modify',function($q){$q->where([['real_auth_user_modify.status',0],['apply_status_shot',1]]);});
    }     
    
    public function unchecked_modify() 
    {
        return $this->hasMany(RealAuthUserModify::class,'apply_id','id')->where(function($q) {$q->whereNull('status')->orWhere('status',0);});
    } 
    
    public function actual_unchecked_modify() 
    {
        return $this->unchecked_modify()->where('apply_status_shot',1);
    }
    
    public function latest_actual_unchecked_modify() 
    {
        return $this->hasOne(RealAuthUserModify::class,'apply_id','id')->where('real_auth_user_modify.id',$this->actual_unchecked_modify()->orderByDesc('id')->first()->id);
    }    

    public function working_unchecked_modify() 
    {
        return $this->unchecked_modify()->where('apply_status_shot',0);
        ;
    }     

    public function first_modify() 
    {
        return $this->hasOne(RealAuthUserModify::class,'apply_id','id')
                ->orderBy('id')->take(1);        
    } 

    public function latest_modify() 
    {
        return $this->hasOne(RealAuthUserModify::class,'apply_id','id')
                ->orderByDesc('id')->take(1);        
    } 
    
    public function latest_reply_modify() 
    {
        return $this->latest_modify()->where('has_reply',1);       
    }   

    public function latest_video_modify() 
    {
        return $this->latest_modify()
                    ->where('item_id',4)
                    ->whereNotNull('new_video_record_id');
    }     

    public function latest_unchecked_modify() 
    {
        return $this->latest_modify()
                ->where(function($q) {$q->whereNull('status')->orWhere('status',0);})
               ;        
    } 

    public function latest_unchecked_height_modify() 
    {
        return $this->latest_unchecked_modify()
                ->where('item_id',2)
                ->whereNotNull('new_height')
                ;
    }
    
    public function latest_unchecked_weight_modify() 
    {
        return $this->latest_unchecked_modify()
                ->where('item_id',2)
                ->whereNotNull('new_weight')
                ;
    }  

    public function latest_unchecked_exchange_period_modify() 
    {
        return $this->latest_unchecked_modify()
                ->where('item_id',2)
                ->whereNotNull('new_exchange_period')
                ->where('new_exchange_period','!=','');
    }  

    public function latest_unchecked_video_modify() 
    {
        return $this->latest_unchecked_modify()
                ->where('item_id',4)
                ->whereNotNull('new_video_record_id')
                ;
    } 

    public function latest_unchecked_reply_modify() 
    {
        return $this->latest_unchecked_modify()
                ->where('item_id',5)
                ;
    }   

    public function latest_unchecked_pic_modify() 
    {
        return $this->latest_unchecked_modify()
                ->where('item_id',3)
                ;
    }

    public function working_modify_list() 
    {
        if($this->status==1) 
        {
            return $this->real_auth_user_modify()->where('status',1);
        }
        else if(!$this->status)
        {
            return $this->real_auth_user_modify()->whereIn('status',[0,1]);
        }
        else if($this->status==2) {
            return $this->real_auth_user_modify()
                    ->where(function($q){
                        $q->where('status',0)
                            ->where('apply_status_shot',2)
                            ->orWhere('status',1);
                    });            
        }
       
    } 
    
    public function working_modify_list_with_trashed()
    {
        return $this->working_modify_list()->withTrashed();
    }

    public function effect_working_modify_list() 
    {
        return $this->working_modify_list()->whereHas('real_auth_user_apply',function($q){$q->where('status','!=',2);});
    }   

    public function latest_working_modify() 
    {
        if($this->status==1) 
        {
            return $this->latest_modify()->where('status',1);
        }
        else if(!$this->status)
        {
            return $this->latest_modify()->whereIn('status',[0,1]);
        }
        else if($this->status==2) {

            return $this->latest_modify()
                    ->where(function($q){
                        $q->where('status',0)
                            ->where('apply_status_shot',2)
                            ->orWhere('status',1);
                    });            
        }
       
    }   

    public function latest_working_height_modify() 
    {
        return $this->latest_working_modify()
                ->where('item_id',2)
                ->whereNotNull('new_height')
                ->where('new_height','>',0);
    }
    
    public function latest_working_weight_modify() 
    {
        return $this->latest_working_modify()
                ->where('item_id',2)
                ->whereNotNull('new_weight')
                ->where('new_weight','>',0);
    }  

    public function latest_working_exchange_period_modify() 
    {
        return $this->latest_working_modify()
                ->where('item_id',2)
                ->whereNotNull('new_exchange_period')
                ->where('new_exchange_period','!=','');
    }  

    public function latest_working_video_modify() 
    {
        return $this->latest_working_modify()
                ->where('item_id',4)
                ->whereNotNull('new_video_record_id')
                ;
    } 

    public function latest_working_reply_modify() 
    {
        return $this->latest_working_modify()
                ->where('item_id',5)
                ->where('has_reply',1)
                ;
    } 
    
    public function latest_working_reply_modify_with_trashed() 
    {
        return $this->latest_working_reply_modify()->withTrashed()
                ;
    }     
    
    public function latest_working_reply_list() 
    {
        return $this->latest_working_reply_modify()
                ->firstOrNew()
                ->real_auth_user_reply()
                ;
    }     

    public function latest_working_pic_modify() 
    {
        return $this->latest_working_modify()
                ->where('item_id',3)
                ->where('has_pic',1)
                ;
    }      

}
