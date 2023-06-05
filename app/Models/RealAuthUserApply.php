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
                ->oldest();        
    }     

    public function latest_modify() 
    {
        return $this->hasOne(RealAuthUserModify::class,'apply_id','id')
                ->latest();       
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

    public function saveModifyByArr($arr) 
    {
        $data = $arr;
        $apply_entry = $this;
        
        if(!$apply_entry) return;       
        
        $data['apply_status_shot'] = $apply_entry->status;
        
        if(in_array($data['item_id'],[4,5])) {
            if(!$apply_entry->real_auth_user_modify->where('item_id',$data['item_id'])->where('apply_status_shot',0)->count()) {
                $data['is_formal_first'] = 1;
            }
        }
        $rs = $apply_entry->real_auth_user_modify()->create($data);

        if($rs){
            if($rs->item_id!=1)  {
                
                if(!$rs->apply_status_shot
                    || ($rs->apply_status_shot==1 && $rs->status==1 )
                ) {
                    if($rs->new_height) {
                        $apply_entry->height_modify_id = $rs->id;
                    }
                    
                    if($rs->new_weight) {
                        $apply_entry->weight_modify_id = $rs->id;
                    }  

                    if($rs->new_exchange_period ) {
                        $apply_entry->exchange_period_modify_id = $rs->id;
                    }  

                    if($rs->new_mem_pic_num || $rs->new_avatar_num) {
                        $apply_entry->pic_modify_id  = $rs->id;
                    } 

                    if($rs->new_video_record_id ) {
                        $apply_entry->video_modify_id  = $rs->id;
                    } 

                    if($rs->has_reply ) {
                        $apply_entry->reply_modify_id  = $rs->id;
                    }   

                    $apply_entry->save();
                }
               
            }
            return $rs;
        }
    }
    
    public function saveVideoRecordId($vrid)
    {
        $self_auth_apply_entry = $this;
        
        if($self_auth_apply_entry->auth_type_id!=1)  return false;
        
        $latest_vmodify = $self_auth_apply_entry->latest_working_video_modify;

        if ($latest_vmodify) {
            if ($latest_vmodify->new_video_record_id) {
                $vmodify_data['old_video_record_id'] = $latest_vmodify->new_video_record_id;
            }

            if ($self_auth_apply_entry->status == 1) {
                $vmodify_data['status'] = 0;
                $vmodify_data['now_video_record_id'] = $latest_vmodify->new_video_record_id;

                if ($latest_vmodify->new_video_record_id) {
                    $vmodify_data['old_video_record_id'] = $latest_vmodify->new_video_record_id;
                }
            } else {
                $vmodify_data['now_video_record_id'] = $vrid;

            }
        }
        else {
            $vmodify_data['now_video_record_id'] = $vrid;
        }

        $vmodify_data['new_video_record_id'] = $vrid;
        $vmodify_data['item_id'] = 4;

        $modify_rs = $this->saveModifyByArr($vmodify_data);

        if($modify_rs && $self_auth_apply_entry->status!=1) {
            $self_auth_apply_entry->video_modify_id = $modify_rs->id;
            $self_auth_apply_entry->save();
        }

        return $modify_rs;
    }    

}
