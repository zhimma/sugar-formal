<?php

namespace App\Models;

use App\Models\User;
use App\Models\RealAuthUserReply;
use App\Models\RealAuthModifyItem;
use App\Models\RealAuthType;
use App\Models\RealAuthUserModifyPic;
use App\Models\RealAuthUserModifyProfile;
use App\Models\RealAuthUserApply;
use App\Models\ExchangePeriodName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RealAuthUserModify extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'real_auth_user_modify';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }  
    
    public function real_auth_type()
    {
        return $this->belongsTo(RealAuthType::class, 'auth_type_id', 'id');
    } 

    public function real_auth_user_apply()
    {
        return $this->belongsTo(RealAuthUserApply::class, 'apply_id', 'id');
    } 

    public function real_auth_modify_item()
    {
        return $this->belongsTo(RealAuthModifyItem::class, 'item_id', 'id');
    }     

    public function real_auth_user_reply()
    {
        return $this->hasMany(RealAuthUserReply::class, 'modify_id', 'id');
    } 
    
    public function real_auth_user_modify_pic()
    {
        return $this->hasMany(RealAuthUserModifyPic::class, 'modify_id', 'id');
    }  

    public function real_auth_user_modify_profile()
    {
        return $this->hasMany(RealAuthUserModifyProfile::class, 'modify_id', 'id');
    }  

    public function new_exchange_period_name()
    {
        return $this->hasOne(ExchangePeriodName::class, 'id', 'new_exchange_period');
    }  

    public function apply_id_self_realte_modify() 
    {
        return $this->hasMany(RealAuthUserModify::class, 'apply_id', 'apply_id');
    }
    
    public function real_auth_user_modify()
    {
        return $this->hasOne(RealAuthUserModify::class,'id','id');
    }
    
    public function real_auth_user_modify_with_trashed()
    {
        return $this->real_auth_user_modify()->withTrashed();
    }
    
    public static function createNewApplyModifyByApplyEntry($apply_entry)
    {
        if(!$apply_entry) return;
        
        $user = $apply_entry->user;
        $meta = $user->meta;
        $data['item_id'] = 1;
        $data['apply_status_shot'] = $apply_entry->status;
        $data['now_exchange_period'] = $user->exchange_period;
        $data['now_height'] = $meta->height;
        $data['now_weight'] = $meta->weight;
        $data['now_avatar_num'] = $meta->pic?1:0;
        $data['now_mem_pic_num'] = $user->pic->count();
        $data['status'] = 0;
        
        $rs = $apply_entry->real_auth_user_modify()->create($data);

        if($rs) {
            $apply_entry->height_modify_id = $rs->id;
            $apply_entry->weight_modify_id = $rs->id;
            $apply_entry->exchange_period_modify_id  = $rs->id;
            $apply_entry->pic_modify_id  = $rs->id;
            $apply_entry->save();
            
            if($user->video_verify_auth_status==1) {
                $video_verify_auth_latest_record = $user->video_verify_record()->where('admin_id',0)->orderByDesc('id')->first();
                
                if($video_verify_auth_latest_record) {
                    $apply_entry->saveVideoRecordId($video_verify_auth_latest_record->id);
                }
            }
            
            return $rs;
        }          
    }
    
    public function createNewApplyModify()
    {
        $apply_entry = $this->real_auth_user_apply;
        
        return $this->createNewApplyModifyByApplyEntry($apply_entry);
    }
}
