<?php
namespace App\Services;
use App\Services\UserService;
use App\Repositories\RealAuthUserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ImageController;

class RealAuthAdminService {
    public function __construct(
        UserService $userService,
        RealAuthUserRepository $realAuthUserRepo,
        ImageController $img_ctrl
    ) {
        $this->user_service = $userService;
        $this->rau_repo = $realAuthUserRepo;
        $this->rau_repo->riseByUserEntry($this->user());        
        $this->img_ctrl = $img_ctrl;
    } 
    
    public function init() 
    {
        $this->rau_repo->init();   
        $this->img_ctrl(true);
    }
    
    public function img_ctrl($value_or_reset=false) 
    {
        if($value_or_reset===true) {
            $this->img_ctrl = null;  
        }
        else if($value_or_reset!==false) {
            $this->img_ctrl = $value_or_reset;
        }

        return $this->img_ctrl;
    }   
    
    public function riseByUserService(UserService $userService) 
    {
        return $this->riseByUserEntry($userService->model);
    }
    
    public function riseByUserId($user_id) 
    {
        $user_entry = $this->user()->find($user_id);
        return $this->riseByUserEntry($user_entry);
    }
    
    public function riseByUserEntry($user_entry) 
    {
        $this->rau_repo()->riseByUserEntry($user_entry);
        
        if($this->user_service->model->id!=$user_entry->id) {
            $this->user_service->riseByUserEntry($user_entry);
        }
        
        return $this;    
    }
    
    public function slotByApplyEntry($apply_entry) 
    {
        $this->rau_repo()->slotByApplyEntry($apply_entry);
        return $this;
    }
    
    public function user() 
    {
        return $this->rau_repo()->user();
    }

    public function user_service() 
    {
        return $this->user_service;
    }
    
    public function rau_repo() 
    {
        return $this->rau_repo;
    } 
    
    public function ra_repo() 
    {
        return $this->rau_repo()->real_auth_repo();
    }     
    
    public function question_entry() 
    {
        return $this->ra_repo()->question_entry();
    }
    
    public function apply_entry($value_or_reset=false) 
    {
        return $this->rau_repo()->apply_entry($value_or_reset);
    }
    
    public function patch_entry($value_or_reset=false) 
    {
        return $this->rau_repo()->patch_entry($value_or_reset);
    }    
    
    public function modify_entry($value_or_reset=false) 
    {
        return $this->rau_repo()->modify_entry($value_or_reset);
    }      
    
    public function apply_list($value_or_reset=false) 
    {
        return $this->rau_repo()->apply_list($value_or_reset);
    }
    
    public function modify_list($value_or_reset=false) 
    {
        return $this->rau_repo()->modify_list($value_or_reset);
    }    
 
    public function questionTypeToKey($question_type=null) 
    {
        return $this->ra_repo()->questionTypeToKey($question_type);
    }
 
    public function saveUserAuthStatusByAuthTypeId($status,$auth_type_id) 
    {
        $user = $this->user();
        switch($auth_type_id) {
            case 1:
                $user->self_auth_status = $status;
            break;
            case 2:
                $user->beauty_auth_status  = $status;            
            break;
            case 3:
                $user->famous_auth_status   = $status;            
            break;
        } 

        return $user->save();
    }
    
    public function saveAuthStatusByAuthTypeId($status,$auth_type_id) 
    {
        $rs = $this->saveUserAuthStatusByAuthTypeId($status,$auth_type_id);
        if($rs) {
            $apply_entry = $this->getApplyByAuthTypeId($auth_type_id);
            
            if($apply_entry->status!=$status) {
                $apply_entry->status = $status;
                $apply_entry->status_at = Carbon::now();
                $apply_rs = $apply_entry->save();                
            
                if($apply_rs) {
                    switch($status) {
                        case 1:
                            if($apply_entry->unchecked_modify->count()) {
                                $apply_entry->unchecked_modify()->where('apply_status_shot',1)->update(['status'=>2,'status_at'=>Carbon::now()]);
                                $apply_entry->unchecked_modify()->update(['status'=>1,'status_at'=>Carbon::now()]);
                            }
                            else {
                               if($auth_type_id==2 || $auth_type_id==3) {
                                   $b_auth_reply_auto_new_modify = $apply_entry->real_auth_user_modify()->create(['item_id'=>5,'apply_status_shot'=>$apply_entry->status,'has_reply'=>1,'from_auto'=>1,'status'=>1,'status_at'=>Carbon::now()]);

                               if($b_auth_reply_auto_new_modify) {
                                        $apply_entry->reply_modify_id = $b_auth_reply_auto_new_modify->id;
                                    }                               
                               } 
                            }
                            
                            $apply_entry->refresh();
                            $apply_entry->height_modify_id = $apply_entry->latest_working_height_modify->id??null;
                            $apply_entry->weight_modify_id = $apply_entry->latest_working_weight_modify->id??null;
                            $apply_entry->exchange_period_modify_id = $apply_entry->latest_working_exchange_period_modify->id??null;
                            $apply_entry->video_modify_id = $apply_entry->latest_working_video_modify->id??null;
                            if(!($b_auth_reply_auto_new_modify??null)) $apply_entry->reply_modify_id = $apply_entry->latest_working_reply_modify->id??null;
                            $apply_entry->save();                    
                        break;
                        case 2:
                            $apply_entry->unchecked_modify()->update(['status'=>2,'status_at'=>Carbon::now()]);                   
                            $apply_entry->real_auth_user_modify()->delete();
                        break;                        
                    }
                }
            }

        }
        
        return $rs;
    }  

    public function savePatchByMsgEntryAndReqArr($msg,$req_arr)
    {
        $auth_type_id = $req_arr['auth_type_id']??null;
        $item_id = $req_arr['item_id']??null;

        if(!$auth_type_id) return;
        if(!$item_id) return;
        
        $user = $this->user();      
        $apply_entry = $this->getApplyByAuthTypeId($auth_type_id);
        
        if(!$apply_entry) return;        
        
        $data['auth_type_id'] = $auth_type_id;
        $data['message_id'] = $msg->id;
        $data['item_id'] = $item_id;
        $data['apply_id_shot'] = $apply_entry->id;
        $data['apply_status_shot'] = $apply_entry->status;
        
        $rs = $user->real_auth_user_patch()->create($data);
        
        if($rs) {
            return $this->patch_entry($rs);
        } 
            
    }

    public function passApplyByAuthTypeId($auth_type_id) 
    {

        if(!$this->isPassedByAuthTypeId($auth_type_id)) {
            
            if($auth_type_id==2 && !$this->isPassedByAuthTypeId(1)) {
                $first_rs = $this->saveAuthStatusByAuthTypeId(1,1);
                if($first_rs) {
                    $rs = $this->saveAuthStatusByAuthTypeId(1,$auth_type_id);
                }
            }
            else $rs = $this->saveAuthStatusByAuthTypeId(1,$auth_type_id);
        }
        
        return intval(!!$this->isPassedByAuthTypeId($auth_type_id));
    }
    
    public function cancelPassByAuthTypeId($auth_type_id) {

        if($this->isPassedByAuthTypeId($auth_type_id)) {
            $apply_entry = $this->getApplyByAuthTypeId($auth_type_id);
            
            if($apply_entry) {
                if($auth_type_id==1 && $this->isPassedByAuthTypeId(2)) {
                    $first_rs = $this->saveAuthStatusByAuthTypeId(2,2);
                    
                    if($first_rs) {
                        $rs = $this->saveAuthStatusByAuthTypeId(2,$auth_type_id);
                    }
                }
                else $rs = $this->saveAuthStatusByAuthTypeId(2,$auth_type_id);
                
            }
            else 
                $rs = $this->saveAuthStatusByAuthTypeId(0,$auth_type_id);
        }
        return intval(!($this->isPassedByAuthTypeId($auth_type_id))); 
    } 

    public function passModifyBeforeModifyId($modify_id=null) 
    {
        $user = $this->user();
        $meta = $user->meta;

        $modify_list = $user->real_auth_user_modify()->where('apply_status_shot',1)->where('real_auth_user_modify.status',0)->orderByDesc('real_auth_user_modify.id');
        $self_auth_apply_entry = $this->getApplyByAuthTypeId(1);
        $b_auth_apply_entry = $this->getApplyByAuthTypeId(2);
        $f_auth_apply_entry = $this->getApplyByAuthTypeId(3);
        
        if($modify_id) {
            $modify_list = $modify_list->where('real_auth_user_modify.id','<=',$modify_id);
        }
       
        $profile_modify_list = (clone $modify_list)->where('item_id',2);
        $height_modify_list = (clone $profile_modify_list)->whereNotNull('new_height');
        $weight_modify_list = (clone $profile_modify_list)->whereNotNull('new_weight');
        $exchange_period_modify_list = (clone $profile_modify_list)->whereNotNull('new_exchange_period');        
        $video_modify_list = (clone $modify_list)->where('item_id',4)->whereNotNull('new_video_record_id');
        $reply_modify_list = (clone $modify_list)->where('item_id',5)->where('has_reply',1);
        $b_auth_reply_modify_list = (clone $reply_modify_list)->where('auth_type_id',2);
        $f_auth_reply_modify_list = (clone $reply_modify_list)->where('auth_type_id',3);
        $modify_pic_list = null;
        
        if($self_auth_apply_entry)
            $modify_pic_list = $self_auth_apply_entry->real_auth_user_modify_pic()->whereIn('real_auth_user_modify.id',$modify_list->pluck('real_auth_user_modify.id'))->orderBy('real_auth_user_modify_pic.id')->get();
        
        $height_modify = $height_modify_list->first();
        $weight_modify = $weight_modify_list->first();
        $exchange_period_modify = $exchange_period_modify_list->first();
        $video_modify = $video_modify_list->first();
        $b_auth_reply_modify = $b_auth_reply_modify_list->first();
        $f_auth_reply_modify = $f_auth_reply_modify_list->first();
        
        $list_update_arr = ['real_auth_user_modify.status'=>1,'real_auth_user_modify.status_at'=>Carbon::now()];
        
        $rs = false;
        
        if($height_modify && $height_modify->new_height!=$meta->height) {
            $meta->height = $height_modify->new_height;
            $height_rs = $meta->save();
            
            if($height_rs) {
                $rs = true;                
                $height_modify_list->update($list_update_arr);
            }
        }
        
        if($weight_modify && $weight_modify->new_weight!=$meta->weight) {
            $meta->weight = $weight_modify->new_weight;
            $weight_rs = $meta->save();
            
            if($weight_rs) {
                $rs = true;                
                $weight_modify_list->update($list_update_arr);
            }
        }

        if($exchange_period_modify && $exchange_period_modify->new_exchange_period!=$user->exchange_period) {

            $exchange_period_rs = $this->user_service()->AdminCheckExchangePeriodSave((object) ['status'=>1],$this);
            
            if($exchange_period_rs) {
                $rs = true;                
                $exchange_period_modify_list->update($list_update_arr);
            }
        }

        //if($video_modify && $self_auth_apply_entry && $video_modify->new_video_record_id!=$self_auth_apply_entry->video_modify_id) {
        if($video_modify && $self_auth_apply_entry && $video_modify->id!=$self_auth_apply_entry->video_modify_id) {
            //$self_auth_apply_entry->video_modify_id = $video_modify->new_video_record_id;
            $self_auth_apply_entry->video_modify_id = $video_modify->id;
            $video_rs = $self_auth_apply_entry->save();
            
            if($video_rs) {
                $rs = true;                
                $video_modify_list->update($list_update_arr);
            }
        }

        if($b_auth_reply_modify && $b_auth_reply_modify->id!=$b_auth_apply_entry->reply_modify_id) {
            
            $b_auth_working_modify_entry = $b_auth_apply_entry->latest_working_reply_modify;
            $b_auth_working_reply_list = $b_auth_working_modify_entry->real_auth_user_reply;
            
            if($b_auth_working_reply_list->count()) {
                $b_auth_reply_auto_new_modify = $b_auth_apply_entry->real_auth_user_modify()->create(['item_id'=>5,'apply_status_shot'=>$b_auth_apply_entry->status,'has_reply'=>1,'from_auto'=>1,'status'=>1,'status_at'=>Carbon::now()]);                
            }
            else {
                $b_auth_reply_auto_new_modify = $b_auth_apply_entry->latest_actual_unchecked_modify;
            }
            
            foreach($b_auth_working_reply_list as $working_reply_entry) {
                $b_auth_auto_reply_data = [];

                $b_auth_choice_id_reply_entry = (clone $b_auth_reply_modify_list)->whereHas('real_auth_user_reply',function($q) use ($working_reply_entry) {$q->where('question_id',$working_reply_entry->question_id)->whereNotNull('choice_id')->where('choice_id','!=',$working_reply_entry->choice_id);})->with('real_auth_user_reply')->get()->pluck('real_auth_user_reply')->flatten()->where('question_id',$working_reply_entry->question_id)->sortByDesc('id')->first();

                $b_auth_pic_choice_id_reply_entry = (clone $b_auth_reply_modify_list)->whereHas('real_auth_user_reply',function($q) use ($working_reply_entry) {$q->where('question_id',$working_reply_entry->question_id)->whereNotNull('pic_choice_id')->where('pic_choice_id','!=',$working_reply_entry->pic_choice_id);})->with('real_auth_user_reply')->get()->pluck('real_auth_user_reply')->flatten()->where('question_id',$working_reply_entry->question_id)->sortByDesc('id')->first();

                $b_auth_reply_choices_reply_entry = (clone $b_auth_reply_modify_list)->whereHas('real_auth_user_reply',function($q) use ($working_reply_entry) {$q->where('question_id',$working_reply_entry->question_id)->whereNotNull('reply_choices')->where('reply_choices','!=',$working_reply_entry->reply_choices);})->with('real_auth_user_reply')->get()->pluck('real_auth_user_reply')->flatten()->where('question_id',$working_reply_entry->question_id)->sortByDesc('id')->first();

                $b_auth_reply_bit_reply_entry = (clone $b_auth_reply_modify_list)->whereHas('real_auth_user_reply',function($q) use ($working_reply_entry) {$q->where('question_id',$working_reply_entry->question_id)->whereNotNull('reply_bit')->where('reply_bit','!=',$working_reply_entry->reply_bit);})->with('real_auth_user_reply')->get()->pluck('real_auth_user_reply')->flatten()->where('question_id',$working_reply_entry->question_id)->sortByDesc('id')->first();

                $b_auth_reply_context_reply_entry = (clone $b_auth_reply_modify_list)->whereHas('real_auth_user_reply',function($q) use ($working_reply_entry) {$q->where('question_id',$working_reply_entry->question_id)->whereNotNull('reply_context')->where('reply_context','!=',$working_reply_entry->reply_context);})->with('real_auth_user_reply')->get()->pluck('real_auth_user_reply')->flatten()->where('question_id',$working_reply_entry->question_id)->sortByDesc('id')->first();

                $b_auth_auto_reply_data['question_id'] = $working_reply_entry->question_id;
                
                $auto_data_reply_entry = $b_auth_choice_id_reply_entry;
                if(!$auto_data_reply_entry) $auto_data_reply_entry = $working_reply_entry;

                $b_auth_auto_reply_data['choice_id'] = $auto_data_reply_entry->choice_id;
                
                $auto_data_reply_entry = $b_auth_pic_choice_id_reply_entry;
                if(!$auto_data_reply_entry) $auto_data_reply_entry = $working_reply_entry;
                $b_auth_auto_reply_data['pic_choice_id'] = $auto_data_reply_entry->pic_choice_id;
                
                $auto_data_reply_entry = $b_auth_reply_choices_reply_entry;
                if(!$auto_data_reply_entry) $auto_data_reply_entry = $working_reply_entry;
                $b_auth_auto_reply_data['reply_choices'] = $auto_data_reply_entry->reply_choices;
                
                $auto_data_reply_entry = $b_auth_reply_bit_reply_entry;
                if(!$auto_data_reply_entry) $auto_data_reply_entry = $working_reply_entry;
                $b_auth_auto_reply_data['reply_bit'] = $auto_data_reply_entry->reply_bit;
                
                $auto_data_reply_entry = $b_auth_reply_context_reply_entry;
                
                if(!$auto_data_reply_entry) $auto_data_reply_entry = $working_reply_entry;
                
                $b_auth_auto_reply_data['reply_context'] = $auto_data_reply_entry->reply_context;
                $b_auth_auto_reply_data['context_choices'] = $auto_data_reply_entry->context_choices;
            
                $b_auth_reply_auto_new_modify->real_auth_user_reply()->create($b_auth_auto_reply_data);
            }            
            
            if($b_auth_reply_auto_new_modify??null) {
                $b_auth_apply_entry->reply_modify_id = $b_auth_reply_auto_new_modify->id;
                $b_reply_rs = $b_auth_apply_entry->save();
            }
            
            if($b_reply_rs) {
                $rs = true;
                $b_auth_reply_modify_list->update($list_update_arr);  
            }
        }
        
        if($f_auth_reply_modify && $f_auth_reply_modify->id!=$f_auth_apply_entry->reply_modify_id) {
            
            $f_auth_working_modify_entry = $f_auth_apply_entry->latest_working_reply_modify;
            $f_auth_working_reply_list = $f_auth_working_modify_entry->real_auth_user_reply;
                        
            if($f_auth_working_reply_list->count()) {
                $f_auth_reply_auto_new_modify = $f_auth_apply_entry->real_auth_user_modify()->create(['item_id'=>5,'apply_status_shot'=>$f_auth_apply_entry->status,'has_reply'=>1,'from_auto'=>1,'status'=>1,'status_at'=>Carbon::now()]);                
            }
            else {
                $f_auth_reply_auto_new_modify = $f_auth_apply_entry->latest_actual_unchecked_modify;
            }
            
            foreach($f_auth_working_reply_list as $working_reply_entry) {
                $f_auth_auto_reply_data = [];
                
                $f_auth_choice_id_reply_entry = (clone $f_auth_reply_modify_list)->whereHas('real_auth_user_reply',function($q) use ($working_reply_entry) {$q->where('question_id',$working_reply_entry->question_id)->whereNotNull('choice_id')->where('choice_id','!=',$working_reply_entry->choice_id);})->with('real_auth_user_reply')->get()->pluck('real_auth_user_reply')->flatten()->where('question_id',$working_reply_entry->question_id)->sortByDesc('id')->first();

                $f_auth_pic_choice_id_reply_entry = (clone $f_auth_reply_modify_list)->whereHas('real_auth_user_reply',function($q) use ($working_reply_entry) {$q->where('question_id',$working_reply_entry->question_id)->whereNotNull('pic_choice_id')->where('pic_choice_id','!=',$working_reply_entry->pic_choice_id);})->with('real_auth_user_reply')->get()->pluck('real_auth_user_reply')->flatten()->where('question_id',$working_reply_entry->question_id)->sortByDesc('id')->first();

                $f_auth_reply_choices_reply_entry = (clone $f_auth_reply_modify_list)->whereHas('real_auth_user_reply',function($q) use ($working_reply_entry) {$q->where('question_id',$working_reply_entry->question_id)->whereNotNull('reply_choices')->where('reply_choices','!=',$working_reply_entry->reply_choices);})->with('real_auth_user_reply')->get()->pluck('real_auth_user_reply')->flatten()->where('question_id',$working_reply_entry->question_id)->sortByDesc('id')->first();

                $f_auth_reply_bit_reply_entry = (clone $f_auth_reply_modify_list)->whereHas('real_auth_user_reply',function($q) use ($working_reply_entry) {$q->where('question_id',$working_reply_entry->question_id)->whereNotNull('reply_bit')->where('reply_bit','!=',$working_reply_entry->reply_bit);})->with('real_auth_user_reply')->get()->pluck('real_auth_user_reply')->flatten()->where('question_id',$working_reply_entry->question_id)->sortByDesc('id')->first();

                $f_auth_reply_context_reply_entry = (clone $f_auth_reply_modify_list)->whereHas('real_auth_user_reply',function($q) use ($working_reply_entry) {$q->where('question_id',$working_reply_entry->question_id)->whereNotNull('reply_context')->where('reply_context','!=',$working_reply_entry->reply_context);})->with('real_auth_user_reply')->get()->pluck('real_auth_user_reply')->flatten()->where('question_id',$working_reply_entry->question_id)->sortByDesc('id')->first();

                $f_auth_auto_reply_data['question_id'] = $working_reply_entry->question_id;
                
                $auto_data_reply_entry = $f_auth_choice_id_reply_entry;
                
                if(!$auto_data_reply_entry) $auto_data_reply_entry = $working_reply_entry;

                $f_auth_auto_reply_data['choice_id'] = $auto_data_reply_entry->choice_id;
                
                $auto_data_reply_entry = $f_auth_pic_choice_id_reply_entry;
                
                if(!$auto_data_reply_entry) $auto_data_reply_entry = $working_reply_entry;
                
                $f_auth_auto_reply_data['pic_choice_id'] = $auto_data_reply_entry->pic_choice_id;
                
                $auto_data_reply_entry = $f_auth_reply_choices_reply_entry;
                
                if(!$auto_data_reply_entry) $auto_data_reply_entry = $working_reply_entry;
                
                $f_auth_auto_reply_data['reply_choices'] = $auto_data_reply_entry->reply_choices;
                
                $auto_data_reply_entry = $f_auth_reply_bit_reply_entry;
                
                if(!$auto_data_reply_entry) $auto_data_reply_entry = $working_reply_entry;
                
                $f_auth_auto_reply_data['reply_bit'] = $auto_data_reply_entry->reply_bit;
                
                $auto_data_reply_entry = $f_auth_reply_context_reply_entry;
                
                if(!$auto_data_reply_entry) $auto_data_reply_entry = $working_reply_entry;
                
                $f_auth_auto_reply_data['reply_context'] = $auto_data_reply_entry->reply_context;
                $f_auth_auto_reply_data['context_choices'] = $auto_data_reply_entry->context_choices;
            
                $f_auth_reply_auto_new_modify->real_auth_user_reply()->create($f_auth_auto_reply_data);
            }
            
            
            if($f_auth_reply_auto_new_modify) {
                $f_auth_apply_entry->reply_modify_id = $f_auth_reply_auto_new_modify->id;
                $f_reply_rs = $f_auth_apply_entry->save();
            }
            
            if($f_reply_rs??null) {
                $rs = true;
                $f_auth_reply_modify_list->update($list_update_arr);  
            }
        } 

        if($modify_pic_list) {
            foreach($modify_pic_list as $modify_pic) {
                switch($modify_pic->pic_cat) {
                    case 'avatar':
                        $this->img_ctrl()->handleAvatarUploadedFile($user,$modify_pic->pic,$modify_pic->original_name );
                        $this->img_ctrl()->handleAvatarLogFreeVipPicAct($user);
                    break;
                    case 'member_pic':
                        if($modify_pic->old_pic)
                            $this->img_ctrl()->handleDeletePictures($user->pic->where('pic',$modify_pic->old_pic),$user);
                        
                        $this->img_ctrl()->handlePicturesUploadedFile($user,$modify_pic->pic,$modify_pic->original_name );
                        $this->img_ctrl()->handlePicturesLogFreeVipPicAct($user);
                    break;
                }

            }

            $pic_rs = $modify_list->whereHas('real_auth_user_modify_pic',function($q){$q->withTrashed();})->update($list_update_arr);
            
            if($pic_rs) $rs=true;
        }
        return $rs;
    }
    
    public function passExchangePeriodModify() 
    {
        $user = $this->user();
        $apply_entry = $this->getApplyByAuthTypeId(1);
        
        if($apply_entry) {
            $user->refresh();
            return $apply_entry->unchecked_modify()->where('item_id',2)->where('new_exchange_period',$user->exchange_period)->update(['status'=>1,'status_at'=>Carbon::now()]);
        }
    }    

    public function isExistHangApplyByAuthTypeId($auth_type_id) 
    {
        $user = $this->user();
        $unchecked_apply = $this->getUncheckedApplyByAuthTypeId($auth_type_id);
        $rs = !$this->isPassedByAuthTypeId($auth_type_id)  && $unchecked_apply
                //&& !($auth_type_id==1 && !$unchecked_apply->video_modify_id);
                && !($auth_type_id==1 && !$unchecked_apply->latest_video_modify);

        return $rs;
    }
    
    public function isPassedByAuthTypeId($auth_type_id) 
    {
        return $this->rau_repo()->isPassedByAuthTypeId($auth_type_id);
    }
    
    public function isCancelPassedByAuthTypeId($auth_type_id) 
    {
        return $this->rau_repo()->isCancelPassedByAuthTypeId($auth_type_id);
    }    
    
    public function isPicExistActualUncheckedModify($pic) 
    {
        return $this->isPassedByAuthTypeId(1) && $pic->modify_id && $pic->pic_cat || $pic->actual_unchecked_rau_modify_pic;
    }
    
    public function getApplyByAuthTypeId($auth_type_id) 
    {
        return $this->rau_repo()->getApplyByAuthTypeId($auth_type_id);
    }
    
    public function getAuthStatusByAuthTypeId($auth_type_id) 
    {
        return $this->rau_repo()->getAuthStatusByAuthTypeId($auth_type_id);
    }  

    public function getUncheckedApplyByAuthTypeId($auth_type_id) 
    {
        return $this->rau_repo()->getUncheckedApplyByAuthTypeId($auth_type_id);
    }
    
    public function getStatusActorPrefixByAuthTypeId($auth_type_id) 
    {
        $prefix = '';
        if($this->isExistHangApplyByAuthTypeId($auth_type_id)
            || $this->isCancelPassedByAuthTypeId($auth_type_id)
        ) {
            $prefix = '通過';
        }
        else if($this->isPassedByAuthTypeId($auth_type_id)) {
            $prefix = '取消';
        }
        else {
            $prefix = '尚無待審';
        }
        return $prefix;
    }
    
    public function getModifyCheckActorPrefix() 
    {
        $prefix = '';
        $user = $this->user();
        
        $auth_type_list = $this->ra_repo()->fillTypeList()->type_list();
        $has_any_pass = false;
        
        foreach($auth_type_list as $auth_type_entry) {
            if($has_any_pass) break;
            $has_any_pass = $this->isPassedByAuthTypeId($auth_type_entry->id);
        }
        
        $user_unchk_modify_list = $user->real_auth_user_modify->where('status',0)->where('apply_status_shot',1);

        
        if(!($has_any_pass && $user_unchk_modify_list->count()) ){
            $prefix = '尚無';
        }
        return $prefix;
    }    
    
    public function getActorClassAttrByAuthTypeId($auth_type_id) 
    {
        $class_attr = '';
        $default_class = 'btn-primary';
        
        if($this->isExistHangApplyByAuthTypeId($auth_type_id)
            || $this->isCancelPassedByAuthTypeId($auth_type_id)
        ) {
            $class_attr = 'real_auth_pass '.$default_class;
        }
        else if($this->isPassedByAuthTypeId($auth_type_id)) {
            $class_attr = 'real_auth_cancel_pass btn-danger';
        }
        else {
            $class_attr = 'btn-secondary disabled';
        }
        return $class_attr;
    }    

    public function getModifyCheckActorClassAttr() 
    {
        $class_attr = '';
        $default_class = 'btn-primary';
        $user = $this->user();
        
        $auth_type_list = $this->ra_repo()->fillTypeList()->type_list();
        $has_any_pass = false;
        
        foreach($auth_type_list as $auth_type_entry) {
            if($has_any_pass) break;
            $has_any_pass = $this->isPassedByAuthTypeId($auth_type_entry->id);
        }
        
        $user_unchk_modify_list = $user->real_auth_user_modify->where('status',0)->where('apply_status_shot',1);

        if($has_any_pass && $user_unchk_modify_list->count() ){
            $class_attr = 'modify_check_pass '.$default_class;
        }
        else {
            $class_attr = 'btn-secondary disabled';
        }
        return $class_attr;
    } 

    public function getListQueryInAdminCheck($countNum=false) 
    {     
    
        $query = $this->user()
                    ->with('real_auth_user_modify_max_created_at')
                    ->with('real_auth_user_apply')
                    ;
        if($countNum) {
            $query->whereHas('real_auth_user_modify',function($q){
                $q->where('real_auth_user_modify.status',0);
            });
        }
        else {
            $query->whereHas('real_auth_user_modify',function($q){$q->withTrashed();});           
        }

        return $query;
    }     
    
    public function getAdminCheckNum() 
    {
 
        $query = $this->getListQueryInAdminCheck(true);
        
        
        return $query->count();
    }
 
    public function getListInAdminCheck() 
    {
        $list = $this->getListQueryInAdminCheck()
                    ->get()
                    ->sortByDesc('real_auth_user_modify_max_created_at.max_created_at');          

        return $list;
    } 
    
    public function getUserUncheckedApplyList() 
    {
        $apply_list = $this->user()->real_auth_user_apply->where('status',0)->where('from_admin',0)->sortBy('auth_type_id');
        $this->apply_entry($apply_list->first());
        return $this->apply_list($apply_list);
    }
    
    public function getApplyUncheckedModifyList() 
    {
   
        $modify_list = $this->user()->real_auth_user_modify->where('is_check',0)->sortBy('auth_type_id');
        $this->modify_entry($modify_list->first());
        return $this->modify_list($modify_list);
    } 

    public function convertModifyStatusToCompleteWord($modify_entry) 
    {
        //if(!$modify_entry)  echo 'NULL';
        if(!$modify_entry) $modify_entry = $this->modify_entry();
        //if(!$modify_entry)  echo 'NULL';
        if(!$modify_entry) return;
        $apply_entry = $modify_entry->real_auth_user_apply;
        if(!$apply_entry) return;
        $status_word = $modify_entry->status?'已通過':'待確認';

        if(!$modify_entry->status && $modify_entry->apply_status_shot!=1) {
            if(($apply_entry->auth_type_id==1 && $modify_entry->item_id==1 ||  $modify_entry->item_id==4)
                &&  !$modify_entry->now_video_record_id 
            ){
                $status_word = '待視訊';
                if($modify_entry->item_id==1 && $apply_entry->latest_video_modify) $status_word = '待確認';
                if($modify_entry->item_id==4 && $modify_entry->new_video_record_id ) $status_word = '已異動';
            }

            else if($modify_entry->item_id!=1 
                && $modify_entry->apply_status_shot!=1  
            
            ){
                $status_word = '已異動';
            }
        } 
        
        if($modify_entry->status==2) {
            $status_word = '被取消';
        }        
        
        return $status_word;
    }


    public function getStatusLayout($status_code) 
    {
        $layout = '';
        switch($status_code) {
            case 0:
                $layout = '
                        <button type="button" class="btn btn-primary" onclick="checkAction('.$this->user()->id.',1)" >通過</button>
                        <button type="button" class="btn btn-danger reject_button" id="reject_button" data-id="'.$this->user()->id.'" >不通過</button>                
                                ';

            break;
            case 1:
                $layout ='通過';
            break;
            case 2:
                $layout ='不通過';
            break;            
        }
        
        return $layout;
    }
    
    public function getWorkingReplyListByReplyEntry($reply_entry) 
    {
        return $reply_entry->real_auth_user_modify->real_auth_user_apply->working_modify_list()->with('real_auth_user_reply',function($q) use ($reply_entry){$q->where('question_id',$reply_entry->question_id);})->get()->sortByDesc('id')->pluck('real_auth_user_reply')->flatten();    
    }    
    
    public function getWorkingReplyListWithTrashedByReplyEntry($reply_entry) 
    {
        return $reply_entry->real_auth_user_modify_with_trashed->real_auth_user_apply->working_modify_list_with_trashed()->with('real_auth_user_reply',function($q) use ($reply_entry){$q->where('question_id',$reply_entry->question_id);})->get()->sortByDesc('id')->pluck('real_auth_user_reply')->flatten();    
    }
    
    public function getReplyListByReplyEntry($reply_entry,$with_trashed=false) 
    {
        if(!$reply_entry) return;
        
        if($with_trashed) $modify_entry = $reply_entry->real_auth_user_modify_with_trashed;
        else $modify_entry = $reply_entry->real_auth_user_modify;
        
        if($modify_entry && 
            (
                $modify_entry->status==1 
                ||  (
                    !$modify_entry->apply_status_shot 
                    && !$modify_entry->status
                )
            )
            
        ) {

            if($with_trashed) return $this->getWorkingReplyListWithTrashedByReplyEntry($reply_entry);
            else  return $this->getWorkingReplyListByReplyEntry($reply_entry);
        }
        else if($modify_entry && $modify_entry->status==0) {
  
            return $modify_entry->real_auth_user_apply->unchecked_modify()->with('real_auth_user_reply',function($q) use ($reply_entry){$q->where('question_id',$reply_entry->question_id);})->get()->sortByDesc('id')->pluck('real_auth_user_reply')->flatten();    

        } 
    }    
    
    public function getReplyValueArrForLayoutByReplyEntry($reply_entry) 
    {
        if(!$reply_entry) return;
        
        $layout = $reply_arr = [];
        
        $reply_list = $this->getReplyListByReplyEntry($reply_entry,true);
        $reply_list_without_trashed = $this->getReplyListByReplyEntry($reply_entry);
        
        $working_reply_list = null;
        
        $modify_entry = $reply_entry->real_auth_user_modify_with_trashed;
        
        if(!(
                !$modify_entry->status
                && !$modify_entry->apply_status_shot
                || $modify_entry->status==1
            )
        ) {
            $working_reply_list = $this->getWorkingReplyListByReplyEntry($reply_entry);
        }
        
        $reply_choices = $reply_entry->reply_choices;
        $reply_choices_entry = $reply_entry;        
        
        if($working_reply_list) {
            $latest_working_reply_choices = $working_reply_list->whereNotNull('reply_choices')->first()->reply_choices??null;
            
            if($reply_choices==$latest_working_reply_choices) {
               $reply_choices_entry = $reply_list->whereNotNull('reply_choices')->first();
               if($reply_choices_entry) $reply_choices = $reply_choices_entry->reply_choices??null; 
            }
        }
        
        if($reply_choices ) {
            $reply_arr = explode(',',$reply_choices_entry->reply_choices);
            $layout = $this->ra_repo()->choice_entry()->whereIn('id',$reply_arr)->pluck('name','id')->all();
        }
        
        
        $reply_bit = $reply_entry->reply_bit;
        $reply_bit_entry = $reply_entry;        
        
        if($working_reply_list) {
            $latest_working_reply_bit = $working_reply_list->whereNotNull('reply_bit')->first()->reply_bit??null;
            
            if($reply_bit==$latest_working_reply_bit) {
               $reply_bit_entry = $reply_list->whereNotNull('reply_bit')->first();
               if($reply_bit_entry) $reply_bit = $reply_bit_entry->reply_bit??null; 
            }
        }        
        
        if($reply_bit!==null) {
            $layout[0] = $reply_bit_entry->reply_bit?'是':'否';
        } 

        $choice_id = $reply_entry->choice_id;
        $choice_id_entry = $reply_entry;
        
        if($working_reply_list) {
            $latest_working_reply_entry = $working_reply_list->whereNotNull('choice_id')->first();
            $latest_working_choice_id = $latest_working_reply_entry->choice_id??null;
            
            if($choice_id==$latest_working_choice_id) {
               $choice_id_entry = $reply_list->whereNotNull('choice_id')->first();
               
               if($choice_id_entry)
                    $choice_id = $choice_id_entry->choice_id??null; 
            }
        }            
        
        if($choice_id ) {
            $layout[$choice_id_entry->choice_id]=$choice_id_entry->real_auth_choice->name;
        }

        $reply_context = $reply_entry->reply_context;
        $reply_context_entry = $reply_entry;
        
        if($working_reply_list) {          
            $latest_working_reply_entry = $working_reply_list->whereNotNull('reply_context')->first();
            $latest_working_reply_context = $latest_working_reply_entry->reply_context??null;

            if($reply_context==$latest_working_reply_context) {
               
               $reply_context_entry = $reply_list->whereNotNull('reply_context')->where('reply_context','!=',$reply_context)->first();
               
               if($reply_context_entry)
                    $reply_context = $reply_context_entry->reply_context??null; 
               else  $reply_context_entry = $reply_entry;

            }
        } 

        $reply_context_arr = [];
        
        if($reply_context) {

            $context_arr = json_decode($reply_context_entry->reply_context,true );
            
            if(!$context_arr) $context_arr = $reply_context_entry->reply_context;
            
            $context_choice_arr = explode(',',$reply_context_entry->context_choices);
            
            $layout_arr = array_combine($context_choice_arr,$context_arr);

            $layout = array_merge($layout,$layout_arr);

        }
        
        $layout_pic_str = '';
        $pic_reply_list = collect([]);
        
        if($reply_list_without_trashed) {

            $pic_reply_list = $reply_list_without_trashed;
        }

        foreach($pic_reply_list as $pic_reply_entry) {    
            $layout_pic_str = '';

            if($pic_reply_entry->real_auth_user_reply_pic->count()) {
                $layout_pic_str.='<div style="display:inline-block;">';

                foreach($pic_reply_entry->real_auth_user_reply_pic as $pic_entry) {
                    $layout_pic_str.='<div style="display:inline-block;width:250px"><img style="width:250px" src="'.asset($pic_entry->pic).'"></div>';
                }

                $layout_pic_str.='</div>';
            } 

            if($layout_pic_str) {

                if($layout[$pic_reply_entry->pic_choice_id]??null) {
                    $layout[$pic_reply_entry->pic_choice_id].= $layout_pic_str;
                }
                else {
                    $layout[$pic_reply_entry->pic_choice_id] = $layout_pic_str;
                }
            }
        }
        
        return $layout;
    }

    public function getUserReplyLayoutByQuEntry($question_entry) 
    {
        
        if(!$question_entry) $question_entry = $this->question_entry();

        $auth_type_id = $question_entry->auth_type_id;
        $apply_entry = $this->getApplyByAuthTypeId($auth_type_id);

        $modify_entry = $apply_entry->latest_working_reply_modify_with_trashed;
        
        $unchk_modify_entry = null;

        $reply_entry = null;
        $unchk_reply_entry = null;
        
        if($modify_entry) {            
            $reply_entry = $modify_entry->real_auth_user_reply->where('question_id',$question_entry->id)->sortByDesc('id')->first();
            
            if($this->isPassedByAuthTypeId($auth_type_id)) {
                $unchk_modify_entry = $apply_entry->latest_unchecked_reply_modify;

                if($unchk_modify_entry) {
                    $unchk_reply_entry = $unchk_modify_entry->real_auth_user_reply->where('question_id',$question_entry->id)->first();
                }
            } 
        }
        
        $layout_value_arr = $this->getReplyValueArrForLayoutByReplyEntry($reply_entry);
        $unchk_layout_value_arr = [];

        if($unchk_reply_entry) $unchk_layout_value_arr = $this->getReplyValueArrForLayoutByReplyEntry($unchk_reply_entry);

        $layout_str = '';
        $unchk_layout_str = '';
        
        $layout_value_arr_keys = array_keys($layout_value_arr??[]);
        $unchk_layout_value_arr_keys = array_keys($unchk_layout_value_arr??[] ); 
        
        $diff_choice_ids = array_diff($unchk_layout_value_arr_keys,$layout_value_arr_keys);
        $choice_id_arr = array_merge($layout_value_arr_keys,$diff_choice_ids);

        sort($choice_id_arr);

        foreach($choice_id_arr as $choice_id) {

            if(($layout_value_arr[$choice_id]??null)!=($unchk_layout_value_arr[$choice_id]??null) && ($unchk_layout_value_arr[$choice_id]??null)) {

                 $layout_str.='<div class="has_unchecked_compare_origin_show">'.($layout_value_arr[$choice_id]??null).'</div>';   
                 $layout_str.='<div class="unchecked_value_show"><div>'.$unchk_layout_value_arr[$choice_id].'</div></div>';
            }
            else if($layout_value_arr[$choice_id]??null) {
                $layout_str.='<div>'.$layout_value_arr[$choice_id].'</div>';
            }
        }
        
        return $layout_str;
    }
    
    public function getAuthTypeLayoutInAdminCheckByModifyEntry($modify_entry=null) 
    {
        if(!$modify_entry) $modify_entry = $this->modify_entry();
        
        if(!$modify_entry) return;
        
        $apply_entry = $modify_entry->real_auth_user_apply;
        
        if(!$apply_entry) return;
        
        $layout = '';
        $has_anchor = false;
        $anchhor_url = '';
        
        if($apply_entry->auth_type_id==2) {
            $has_anchor = true;
            $anchhor_url = route('admin/checkBeautyAuthForm',['user_id'=>$apply_entry->user->id]);
        }
        else if($apply_entry->auth_type_id==3) {
            $has_anchor = true;
            $anchhor_url = route('admin/checkFamousAuthForm',['user_id'=>$apply_entry->user->id]);
        }    

        if($has_anchor) {
            $layout = '<a href="'.$anchhor_url.'" target="_blank">';
        }
        
        if($apply_entry->real_auth_type)
            $layout.=$apply_entry->real_auth_type->name;
        else
            $layout.='資料異常';
        
        if($has_anchor) {
            $layout.='</a>';
        }
        
        if($apply_entry->auth_type_id==1 && $apply_entry->from_auto && $modify_entry->item_id==1)
        {
            $layout.= ' ( 美顏推薦 ) ';
        } 
        
        return $layout;
    }
    
    public function getLatestUncheckedModifyIdByAuthTypeId($auth_type_id)
    {
        $apply_entry = $this->getApplyByAuthTypeId($auth_type_id);
        
        if($apply_entry) {
            $latest_modify =  $apply_entry->latest_unchecked_modify;
            
            if($latest_modify) return $latest_modify->id;
        }
    }
    
    public function handleNullWord($value,$null_word,$extra_arr=[]) 
    {
        $word = '';
        if($value===null) $word = $null_word;
        
        foreach($extra_arr as $extra) {
            if($value===$extra) $word=$null_word;
        }
        
        if($word==='') $word = $value;

        return $word;
    }
    
    public function getRowspanAttr($rowspan_num) 
    {
        if($rowspan_num>0) return "rowspan=\"$rowspan_num\"";
    }
    
    public function getBeautyAuthQuestionList() 
    {
        return $this->ra_repo()->getQuestionListByAuthType(2);
    }
    
    public function getFamousAuthQuestionList() 
    {
        return $this->ra_repo()->getQuestionListByAuthType(3);
    } 

    public function getApplyDateVarReplaceByAuthTypeId($auth_type_id) 
    {
        $apply_entry = $this->getUncheckedApplyByAuthTypeId($auth_type_id);
        $apply_date_str = '';
        if($apply_entry) {
            $apply_time = $apply_entry->created_at;
            
            if($apply_time) {
                $apply_date_str = Carbon::parse($apply_time)->format('Y/m/d');
            }
        }
        
        return $apply_date_str;
    }
    
    public function getActualUncheckedHeightLayout()
    {
        $apply_entry = $this->getApplyByAuthTypeId(1);
        $layout = '';
        $unchecked_height = null;
        
        if($apply_entry && $this->isPassedByAuthTypeId(1)) {
            $height_modify = $apply_entry->latest_unchecked_height_modify;
            if($height_modify) {
                $unchecked_height = $height_modify->new_height;
            }
        }
        
        if($unchecked_height) {
            $layout = '<span class="unchecked_value_show">'.$unchecked_height.'</span>';
        }
        
        return $layout;
    }
    
    public function getActualUncheckedWeightLayout()
    {
        $apply_entry = $this->getApplyByAuthTypeId(1);
        $layout = '';
        $unchecked_weight = null;
        
        if($apply_entry && $this->isPassedByAuthTypeId(1)) {
            $weight_modify = $apply_entry->latest_unchecked_weight_modify;
            if($weight_modify) {
                $unchecked_weight = $weight_modify->new_weight;
            }
        }
        
        if($unchecked_weight) {
            $layout = '<span class="unchecked_value_show">'.$unchecked_weight.'</span>';
        }
        
        return $layout;
    }

    public function getActualUncheckedExchangePeriodLayout()
    {
        $apply_entry = $this->getApplyByAuthTypeId(1);
        $layout = '';
        $unchecked_exchange_period = null;
        
        if($apply_entry && $this->isPassedByAuthTypeId(1)) {
            $exchange_period_modify = $apply_entry->latest_unchecked_exchange_period_modify;
            if($exchange_period_modify) {
                $unchecked_exchange_period = $exchange_period_modify->new_exchange_period_name;
            }
        }
        
        if($unchecked_exchange_period) {
            $layout = '<span class="unchecked_value_show">'.$unchecked_exchange_period->name.'</span>';
        }
        
        return $layout;
    }      
    
}
