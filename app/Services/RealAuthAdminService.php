<?php
namespace App\Services;
use App\Services\UserService;
use App\Repositories\RealAuthUserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Log;

class RealAuthAdminService {
    public function __construct(
        UserService $userService,
        RealAuthUserRepository $realAuthUserRepo
    ) {
        $this->user_service = $userService;
        $this->rau_repo = $realAuthUserRepo;
        $this->rau_repo->riseByUserEntry($this->user());        

    } 
    
    public function init() {
        $this->rau_repo->init();
        $this->error_msg('');        
    }
    
    public function riseByUserService(UserService $userService) {
        return $this->riseByUserEntry($userService->model);
        /*
        $this->user_service = $userService;
        $this->user = $userService->model??null;
        if(!$this->user->id??null) {
            $this->user = auth()->user();
        }
        return $this;
        */
    }
    
    public function riseByUserId($user_id) {
        $user_entry = $this->user()->find($user_id);
        return $this->riseByUserEntry($user_entry);
    }
    
    public function riseByUserEntry($user_entry) {
        $this->rau_repo()->riseByUserEntry($user_entry);
        if($this->user_service->model->id!=$user_entry->id) {
            $this->user_service->riseByUserEntry($user_entry);
        }
        return $this;    
    }
    
    public function slotByApplyEntry($apply_entry) {
        $this->rau_repo()->slotByApplyEntry($apply_entry);
        return $this;
    }
    
    public function user() {
        return $this->rau_repo()->user();
    }

    public function user_service() {
        return $this->user_service;
    }
    
    public function rau_repo() {
        return $this->rau_repo;
    } 
    
    public function ra_repo() {
        return $this->rau_repo()->real_auth_repo();
    }     
    
    public function question_entry() {
        return $this->ra_repo()->question_entry();
    }
    
    public function apply_entry($value_or_reset=false) {
        return $this->rau_repo()->apply_entry($value_or_reset);
    }
    
    public function modify_entry($value_or_reset=false) {
        return $this->rau_repo()->modify_entry($value_or_reset);
    }      
    
    public function apply_list($value_or_reset=false) {
        return $this->rau_repo()->apply_list($value_or_reset);
    }
    
    public function modify_list($value_or_reset=false) {
        return $this->rau_repo()->modify_list($value_or_reset);
    }    
 
    public function questionTypeToKey($question_type=null) {
        return $this->ra_repo()->questionTypeToKey($question_type);
    }
 
    public function saveAuthStatusByAuthTypeId($status,$auth_type_id) {
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
  
    public function saveAuthStatusApplyIdByAuthTypeId($apply_id,$auth_type_id) {
        $user = $this->user();
        switch($auth_type_id) {
            case 1:
                $user->self_auth_apply_id = $apply_id;
            break;
            case 2:
                $user->beauty_auth_apply_id  = $apply_id;            
            break;
            case 3:
                $user->famous_auth_apply_id   = $apply_id;            
            break;
        } 

        return $user->save();
    }

    public function passByAuthTypeId($auth_type_id) {
        $apply_entry = $this->getUncheckedApplyByAuthTypeId($auth_type_id);
        $rs = false;
        $user = $this->user();
        if($apply_entry) {
            $rs = $apply_entry->update(['status'=>1]); 
            if($rs) {               
                $this->saveAuthStatusApplyIdByAuthTypeId($apply_entry->id,$auth_type_id);
                $this->user()->real_auth_user_apply()->where([['auth_type_id',$auth_type_id],['status',0],['from_admin',0]])->update(['status'=>9]);
                if($auth_type_id==2) {
                    $this->passByAuthTypeId(1);
                }
            }
        }
        
        $this->saveAuthStatusByAuthTypeId(1,$auth_type_id);
        
        return !!$this->isPassedByAuthTypeId($auth_type_id);
    }
    
    public function cancelPassByAuthTypeId($auth_type_id) {
        $user = $this->user();
        //$old_status = $this->getAuthStatusByAuthTypeId($auth_type_id);
        $old_status_apply_id = $this->getAuthStatusApplyIdByAuthTypeId($auth_type_id);
        $rs = false;
        //if($old_status==1) {
        if($this->isPassedByAuthTypeId($auth_type_id)) {
            $rs = $this->saveAuthStatusByAuthTypeId(2,$auth_type_id);

            if($rs) 
            {
                $data['auth_type_id'] = $auth_type_id;
                $data['status'] = 2;
                $data['from_admin'] = 1;
                $data['from_admin_apply_id'] = $old_status_apply_id;
                $data['from_auto'] = 1;
                $apply_entry = $user->real_auth_user_apply()->create($data);                
                $this->saveAuthStatusApplyIdByAuthTypeId($apply_entry->id,$auth_type_id);
            }
        }
        return !($this->isPassedByAuthTypeId($auth_type_id)); 
    }    

    public function isExistHangApplyByAuthTypeId($auth_type_id) {
        $user = $this->user();
        $rs = !$this->isPassedByAuthTypeId($auth_type_id)  && $this->getUncheckedApplyByAuthTypeId($auth_type_id);
        
        return $rs;
    }
    
    public function isPassedByAuthTypeId($auth_type_id) {
        return $this->rau_repo()->isPassedByAuthTypeId($auth_type_id);
    }
    
    public function getAuthStatusByAuthTypeId($auth_type_id) {
        return $this->rau_repo()->getAuthStatusByAuthTypeId($auth_type_id);
    }  

    public function getAuthStatusApplyIdByAuthTypeId($auth_type_id) {
        return $this->rau_repo()->getAuthStatusApplyIdByAuthTypeId($auth_type_id);
    }      
    
    public function getUncheckedApplyByAuthTypeId($auth_type_id) {
        return $this->rau_repo()->getUncheckedApplyByAuthTypeId($auth_type_id);
    }
    
    public function getStatusActorPrefixByAuthTypeId($auth_type_id) {
        $prefix = '';
        if($this->isExistHangApplyByAuthTypeId($auth_type_id)) {
            $prefix = '通過';
        }
        else if($this->isPassedByAuthTypeId($auth_type_id)) {
            $prefix = '取消';
        }
        else {
            $prefix = '尚未申請';
        }
        return $prefix;
    }
    
    public function getActorClassAttrByAuthTypeId($auth_type_id) {
        $class_attr = '';
        $default_class_arr = [1=>'btn-success',2=>'btn-primary',3=>'btn-warning'];
        
        if($this->isExistHangApplyByAuthTypeId($auth_type_id)) {
            $class_attr = 'real_auth_pass '.$default_class_arr[$auth_type_id];
        }
        else if($this->isPassedByAuthTypeId($auth_type_id)) {
            $class_attr = 'real_auth_cancel_pass btn-danger';
        }
        else {
            $class_attr = 'btn-secondary disabled';
        }
        return $class_attr;
    }    
    
    
    public function getAdminCheckNum() {
        $num = 0;
        
        return $num;
    }
 
    public function getListInAdminCheck() {
        $list = $this->user()->with('real_auth_user_apply','real_auth_user_modify')->whereHas('real_auth_user_apply',function($q) {
            $q->where('status',0);
        })->get()->sortByDesc('real_auth_user_apply.created_at');

        return $list;
    } 
    
    public function getUserUncheckedApplyList() {
        $apply_list = $this->user()->real_auth_user_apply->where('status',0)->where('from_admin',0)->sortBy('auth_type_id');
        $this->apply_entry($apply_list->first());
        return $this->apply_list($apply_list);
    }
    
    public function getApplyUncheckedModifyList() {
        //$modify_list = $this->rau_repo()->apply_entry()->real_auth_user_modify->where('is_check',0)->sortBy('auth_type_id');
        $modify_list = $this->user()->real_auth_user_modify->where('is_check',0)->sortBy('auth_type_id');
        $this->modify_entry($modify_list->first());
        return $this->modify_list($modify_list);
    } 

    public function convertStatusToCompleteWord($status_code) {
        return $status_code?'已完成':'待確認';
    }


    public function getStatusLayout($status_code) {
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
    
    public function getUserReplyLayoutByQuEntry($question_entry) {
        if(!$question_entry) $question_entry = $this->question_entry();
        //$apply_entry = $this->user()->famous_auth_unchecked_apply;
        $apply_entry = $this->rau_repo()->getUncheckedApplyByAuthTypeId($question_entry->auth_type_id);
        $reply_entry = $apply_entry->real_auth_user_reply->where('question_id',$question_entry->id)->first();
        $layout = $reply_arr = [];
        $layout_str = '';
        if($reply_entry)  {
            if($reply_entry->reply_choices ) {
                $reply_arr = explode(',',$reply_entry->reply_choices);
                //$reply_arr = json_decode($reply_entry->reply_context,true);
                $layout = $this->ra_repo()->choice_entry()->whereIn('id',$reply_arr)->pluck('name')->all();
            }
            if($reply_entry->choice_id ) {
                $layout[]=$reply_entry->real_auth_choice->name;
            }//$reply_arr[]=$reply_entry->choice_id;
            if($reply_entry->reply_bit!==null) {
                $layout[] = $reply_entry->reply_bit?'是':'否';
            }

            $reply_context_arr = [];
            if($reply_entry->reply_context) {
                //$context_arr = explode(',',$reply_entry->reply_context );
                $context_arr = json_decode($reply_entry->reply_context,true );
                if(!$context_arr) $context_arr = $reply_entry->reply_context;
                $layout = array_merge($layout,$context_arr);
                //foreach(explode(',',$reply_entry->context_choices) as $ck=>$cv) {
                    //$reply_context_arr['choice'.$cv] =  $context_arr[$ck];
                //}
            }
            
            $layout_str =  implode('、',$layout);

            if($reply_entry->real_auth_user_reply_pic->count()) {
                $layout_str.='<div><div style="display:inline-flex;">';
                foreach($reply_entry->real_auth_user_reply_pic as $pic_entry) {
                    $layout_str.='<div style="width:250px"><img style="width:250px" src="'.asset($pic_entry->pic).'"></div>';
                }
                $layout_str.='</div></div>';
            }
        }  
        


        return $layout_str;
    }
    
    public function getAdminCheckAuthTypeLayoutByApplyEntry($apply_entry=null) {
        if(!$apply_entry) $apply_entry = $this->apply_entry();
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
        
        $layout.=$apply_entry->real_auth_type->name;
        
        if($has_anchor) {
            $layout.='</a>';
        }
        
        return $layout;
    }
    
    public function handleNullWord($value,$null_word,$extra_arr=[]) {
        $word = '';
        if($value===null) $word = $null_word;
        
        foreach($extra_arr as $extra) {
            if($value===$extra) $word=$null_word;
        }
        
        if($word==='') $word = $value;

        return $word;
    }
    
    public function getRowspanAttr($rowspan_num) {
        if($rowspan_num>0) return "rowspan=\"$rowspan_num\"";
    }
    
    public function getBeautyAuthQuestionList() {
        return $this->ra_repo()->getQuestionListByAuthType(2);
    }
    
    public function getFamousAuthQuestionList() {
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
    
}
