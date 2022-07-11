<?php
namespace App\Services;
use App\Services\UserService;
use App\Repositories\RealAuthUserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Log;
use \FileUploader;

class RealAuthPageService {
    public function __construct(
        UserService $userService,
        RealAuthUserRepository $realAuthUserRepo
    ) {
        
        $this->user_service = $userService;
        $this->rau_repo = $realAuthUserRepo; 
        $this->riseByUserEntry($this->user());
        $this->init();
    } 
    
    public function init() 
    {
        $this->rau_repo->init();
        $this->error_msg('');
        return $this;
    }   

    public function initByUserService($userService) 
    {
        
        $this->user_service = $userService;
        $this->rau_repo->riseByUserEntry($this->user_service->model??null);        
        $this->init();
        return $this;
    }
    
    public function initByUserEntry($userEntry) 
    {
        $this->user_service->riseByUserEntry($userEntry);
        $this->rau_repo->riseByUserEntry($userEntry);        
        $this->init();
        return $this;
    }    
    
    public function riseByUserService(UserService $userService) 
    {
        $this->user_service = $userService;
        $this->rau_repo->riseByUserEntry($this->user_service->model??null);
        return $this;
    }
    
    public function riseByUserEntry($user_entry) 
    {
        $this->rau_repo()->riseByUserEntry($this->user_service->riseByUserEntry($user_entry)->model);
        return $this;    
    }
    
    public function riseByUserId($user_id) 
    {
        $this->rau_repo()->riseByUserId($user_id);
        $this->user_service->riseByUserEntry($this->rau_repo()->user());
        return $this;    
    }    
    
    public function user() 
    {
        return $this->user_service->model??null;

    }

    public function user_service() 
    {
        return $this->user_service;
    }

    public function ra_repo() 
    {
        return $this->rau_repo()->real_auth_repo();
    } 
    
    public function rau_repo() 
    {
        return $this->rau_repo;
    }

    public function apply_entry()
    {
        return $this->rau_repo()->apply_entry();
    }
   
    public function ra_type_list() 
    {
        if(!$this->ra_repo()->type_list())
            $this->ra_repo()->fillTypeList();
        return $this->ra_repo()->type_list();
    }
    
    public function modify_pic_list($value_or_reset=false) 
    {
        return $this->rau_repo()->modify_pic_list($value_or_reset);
    }
    
    public function getEffectWorkingReplyListFromApplyEntry($apply_entry=null) 
    {
        return $this->rau_repo()->getEffectWorkingReplyListFromApplyEntry($apply_entry);
    }
    
    public function getReplyListQuery() 
    {
        return $this->apply_entry()->latest_working_reply_list();
    }
  
    public function error_msg($msg=null) 
    {
        if($msg!==null) $this->_error_msg = $msg;
        return $this->_error_msg;        
    }  

    public function modify_entry($value_or_reset=false)
    {
        return $this->rau_repo()->modify_entry($value_or_reset);
    }
    
    public function modify_pic_entry($value_or_reset=false)
    {
        return $this->rau_repo()->modify_pic_entry($value_or_reset);
    }    

    public function isAllowRealAuthType($value) 
    {
        $type_list = $this->ra_type_list();
        if(!$type_list) $type_list = collect([]);
        if($type_list->where('id',$value)->first()) return true;
        else return false;
    }
    
    public function turnRealAuthTypeIdToName($id) 
    {
        $type_list = $this->ra_type_list();
        $found_type_list = $type_list->where('id',$id);
        
        if($found_type_list->count()) {
            return $found_type_list->first()->name;
        }
    }
    
    public function isAuthHaveProfileProcess($auth_type_id) 
    {
        
        return $auth_type_id==1 || $auth_type_id==2;
    }
    
    public function isInRealAuthProcess($not_check_passed=false) 
    {
        if(!$not_check_passed && $this->isPassedByAuthTypeId(1)) return false;
        $real_auth_arg = request()->real_auth;
        $real_auth_type = session()->get('real_auth_type');        
        return $real_auth_arg && $real_auth_type
                && $real_auth_arg==$real_auth_type
                && $this->isAuthHaveProfileProcess($real_auth_arg)
        ;
    }

    public function returnInWrongRealAuthProcess() 
    {
        $real_auth_arg = request()->real_auth;
        if(!$real_auth_arg) {
            session()->forget('real_auth_type');
            return;
        }
        $real_auth_type = session()->get('real_auth_type');

        if( $real_auth_arg && !$real_auth_type
            || ($this->isInRealAuthProcess()  && $real_auth_arg!=$real_auth_type)
            || !$this->isAllowRealAuthType($real_auth_arg)
            || !$this->isAllowRealAuthType($real_auth_type)
        )
        {
            session()->forget('real_auth_type');
            return redirect()->route('real_auth');
        }        
    }

    public function getApplyByAuthTypeId($auth_type_id) 
    {
        return $this->rau_repo()->getApplyByAuthTypeId($auth_type_id);
    }
    
    public function getUncheckedApplyByAuthTypeId($auth_type_id) 
    {
        return $this->rau_repo()->getUncheckedApplyByAuthTypeId($auth_type_id);
    }
    
    public function getBeautyAuthQuestionList() 
    {
        return $this->ra_repo()->getQuestionListByAuthType(2);
    }
    
    public function getFamousAuthQuestionList()
    {
        return $this->ra_repo()->getQuestionListByAuthType(3);
    }
   
    public function questionTypeToKey($question_type=null) 
    {
        return $this->ra_repo()->questionTypeToKey($question_type);
    }
    
    public function getQuActualUncheckedPicNumByEntry($question_entry)
    {
        return $this->rau_repo()->getQuActualUncheckedPicNumByEntry($question_entry);
    }

    public function getQuUploaderPreFilesByEntry($question_entry) 
    {
        return $this->rau_repo()->getQuUploaderPreFilesByEntry($question_entry);
    }

    public function getQuValueAttrByEntry($question_entry,$value=null,$default_attr=null) 
    {
        $rau_repo = $this->rau_repo();
        $user= $this->user();

        $question_id = $question_entry->id;

        $apply_entry = $rau_repo->getApplyByAuthTypeId($question_entry->auth_type_id);
        
        $modify_entry = null;
        
        if($apply_entry) {

            $modify_entry = $apply_entry->latest_working_reply_modify;
            
            if($apply_entry->status==2 && ($modify_entry->apply_status_shot??null)!=2) {
                $modify_entry = null;
            }

        }

        $reply_entry = null;
        
        if($modify_entry) {
            $reply_entry = $modify_entry->real_auth_user_reply->where('question_id',$question_id)->first()??null;
        }
        
        $request_value_arr = request()->reply[$question_id]??null;
        $attr = '';
        $reply_record = [];
        $reply_arr = [];

         if($reply_entry)  {
            if($reply_entry->reply_choices ) $reply_arr = explode(',',$reply_entry->reply_choices);
            if($reply_entry->choice_id ) $reply_arr[]=$reply_entry->choice_id;
            if($reply_entry->reply_bit!==null) $reply_arr[] = $reply_entry->reply_bit;

            $reply_context_arr = [];

            if($reply_entry->reply_context) {

                $context_arr = json_decode($reply_entry->reply_context,true );
                
                if(!$context_arr) $context_arr[] = $reply_entry->reply_context;
                
                foreach(explode(',',$reply_entry->context_choices) as $ck=>$cv) {
                    $reply_context_arr['choice'.$cv] =  $context_arr[$ck];
                }
            }                  
        }        
        
        if(!$request_value_arr) {
            
            if(in_array($value,$reply_arr)) {
                $attr = $value;
                if($default_attr!==null) $attr= $default_attr;
            }
            
            if($reply_context_arr['choice'.$value]??null) {
                $attr = $reply_context_arr['choice'.$value];
                if($default_attr!==null) $attr= $default_attr;
            }
        }
        else {
            
            if(in_array($value,$request_value_arr)) {
                
                $attr = $value;
                
                if($default_attr!==null) $attr= $default_attr;                
            }
            
            if($request_value_arr[$value]??null) {
                
                $attr = $request_value_arr[$value];
                
                if($default_attr!==null) $attr= $default_attr;
            }
        }

        return $attr;

    }

    public function getSelfAuthApplyMsgBeforeVideo() 
    {
        $real_auth = request()->real_auth;
        $start_msg_str = '';
        
        if($real_auth==1) {
            $start_msg_str = '恭喜您，<br>只要通過最後的視訊驗證即可完成本人認證<br>';
        }
        else if($real_auth==2) {
            $start_msg_str = '加油！<br><br>只剩最後兩個步驟即可完成美顏認證：視訊驗證（本人認證）和填寫美顏認證表。';
        }
        
        return $start_msg_str.' 請點選綠色按鈕開始進行視訊驗證。                     
                        <a href="'.url('user_video_chat_verify').'">
                            <img src="'.asset('/new/images/guan.png').'" class="center line_notify" id="caocao_pic" style=" height: 30px;cursor: pointer;">
                        </a>
                    <br><br>按鈕灰色代表目前無站方人員可視訊，
                    <br>可在<a href="'.url('/dashboard/personalPage').'">專屬頁面</a>看到視訊狀態變綠色之後，
                    <br>即可做視訊完成認證。

                        ';
    }
    
    public function getClearUnloadConfirmJs()
    {
        if($this->isInRealAuthProcess()) {
            return "$('body').attr('onbeforeunload','');";
        }
    }
    
    public function getOnClickAttrForNoUnloadConfirm()
    {
        $attr_str = '';
        if($this->isInRealAuthProcess()) {
           $attr_str=' onclick="'.$this->getClearUnloadConfirmJs().'" ';
        }
         
        return $attr_str;
    }
    
    public function getBeautyAuthProcessPrecheckReturn()
    {
        if(!$this->isPassedByAuthTypeId(1)) {
            return redirect()->route('real_auth');
        }
    }   

    public function applyRealAuthByReq($request,$ignore_is_in_process=false) 
    {

        if($this->isInRealAuthProcess() || $ignore_is_in_process) {
            $data_arr = $request->all();

            $data_arr['auth_type_id'] = $data_arr['real_auth']??null;
            
            if(!$data_arr['auth_type_id']) return;
            
            if(!$this->isAllowRealAuthType($data_arr['auth_type_id'])) return;
            
            if(($data_arr['real_auth']??null) && $data_arr['real_auth']==2) $data_arr['auth_type_id']=1;            
            
            $apply_entry = $this->getApplyByAuthTypeId($data_arr['auth_type_id']);

            if(($data_arr['real_auth']??null) && $data_arr['real_auth']==2 && !$this->getApplyByAuthTypeId(1))
            {
                $data_arr['from_auto'] = 1;
            } 
            
            if(!$apply_entry)
            {

                
                return $this->rau_repo()->saveApply((object) $data_arr);
            }
            else if($apply_entry->status==2) {
               $data_arr['status'] = 0; 
               $data_arr['video_modify_id'] = null;
            
                $rs = $this->rau_repo()->saveApply((object) $data_arr);
            
                if($rs) {
                    $rs->real_auth_user_modify()->firstOrNew()->createNewApplyModify(); 
                }
                
                return $rs;
            }           
        }
    }
    
    public function saveFamousAuthForm($dataEntry) 
    {
        $rs =  $this->rau_repo()->saveReply($dataEntry);
        
        if(!$rs) {
            $this->error_msg($this->rau_repo()->error_msg());
        }
        
        return $rs;
    }
    
    public function saveBeautyAuthForm($dataEntry) 
    {
               
        $rs =  $this->rau_repo()->saveReply($dataEntry);
    
        if(!$rs) {
            $this->error_msg($this->rau_repo()->error_msg());
        }
        
        return $rs;    
    
    }
    
    public function saveVideoRecordId($vrid) 
    {
        $self_auth_apply_entry = $this->getApplyByAuthTypeId(1);
        $latest_vmodify = $self_auth_apply_entry->latest_working_modify()->where('item_id',4)->first();
        
        if($latest_vmodify)
        {
            if($latest_vmodify->new_video_modify_id )
            {
                $vmodify_data['old_video_record_id'] = $latest_vmodify->new_video_modify_id;
            }
            
            if($self_auth_apply_entry->status==1)
            {
                $vmodify_data['status'] = 0;
                $vmodify_data['now_video_record_id'] = $self_auth_apply_entry->video_modify_id;
                
                if($latest_vmodify->new_video_modify_id)
                {
                    $vmodify_data['old_video_record_id'] = $latest_vmodify->new_video_modify_id;
                }
            }   
            else
            {
                $vmodify_data['now_video_record_id'] = $vrid;
                $self_auth_apply_entry->video_modify_id = $vrid;
                $self_auth_apply_entry->save();
            }    
        }

        $vmodify_data['new_video_record_id'] = $vrid;
        $vmodify_data['item_id '] = 4;
        
        $this->rau_repo()->saveModifyByArr($vmodify_data);
    }
    
    public function saveProfileModifyByReq($request)
    {
        $user = $this->user();
        $apply_entry = $this->getApplyByAuthTypeId(1);
        
        if(!$apply_entry) {
            $apply_data['auth_type_id'] = 1;
            $apply_entry = $this->rau_repo()->saveApply((object)$apply_data);
        }

        $data['item_id'] = 2;

        $data['apply_status_shot']= $apply_entry->status;
        
        if($request->new_exchange_period!==null)
        {
            $data['new_exchange_period']= $request->new_exchange_period;
        }
        else if($request->exchange_period!==null)
        {
            $data['new_exchange_period']= $request->exchange_period;
            $data['old_exchange_period'] = $user->exchange_period;
        }
        
        if($request->new_height!==null) 
        {
            $data['new_height']= $request->new_height;
        }
        else if($request->height!==null) 
        {
            $data['new_height']= $request->height;
            $data['old_height'] = $user->meta->height;
        }
        
        if($request->new_weight!==null) 
        {
            $data['new_weight']= $request->new_weight;
        }
        else if($request->weight!==null) 
        {
            $data['new_weight']= $request->weight;
            $data['old_weight'] = $user->meta->weight;
        }
        
        if($data['new_exchange_period']??null) 
        {
            $user->refresh(); 
            $data['now_exchange_period'] = $user->exchange_period??null;
        }
        
        if($data['new_height']??null) 
        {
            $user->meta->refresh(); 
            $data['now_height'] = $user->meta->height??null;
        }

        if($data['new_weight']??null) 
        {
            $user->meta->refresh(); 
            $data['now_weight'] = $user->meta->weight??null;
        }        
        return $this->rau_repo()->saveModifyByArr($data);

    }
    
    public function savePicModifyByReq($request)
    {
        $user = $this->user();
        $apply_entry = $this->getApplyByAuthTypeId(1);
        if(!$apply_entry) {
            $apply_data['auth_type_id'] = 1;
            $apply_entry = $this->rau_repo()->saveApply((object)$apply_data);
        }

        $data['item_id'] = 3;
        
        $data['apply_status_shot']= $apply_entry->status;
        
        $data['old_avatar_num'] = intval(!!$user->meta->pic);
        $data['old_mem_pic_num'] = $user->pic->count();

        return $this->rau_repo()->saveModifyByArr($data);            
    }    
    
    public function saveRealAuthUserModifyPicByArr($arr)
    {
        $user = $this->user();
        $modify_entry = $this->modify_entry();
        $old_modify_pic_list = collect([]);
        
        if($arr['old_pic']??null) {
            $old_modify_pic_list = $modify_entry->real_auth_user_apply->real_auth_user_modify_pic()->where('old_pic',$arr['old_pic'])->whereHas('real_auth_user_modify',function($q){$q->where('status',0);})->get();
        }
        
        $rs = $modify_entry->real_auth_user_modify_pic()->create($arr);
    
        if($rs) {
            
            if($rs->old_pic) {
                foreach($old_modify_pic_list as $old_modify_pic) {
                    $old_modify_pic->delete();
                }
            }
            
            $user->load('meta','pic');
            
            $modify_entry->now_avatar_num = intval(!!$user->meta->pic);            
            
            if($modify_entry->old_avatar_num!=$modify_entry->now_avatar_num) {
                $modify_entry->new_avatar_num = $modify_entry->now_avatar_num;
            }
            
            $modify_entry->now_mem_pic_num = $user->pic->count();            
            
            if($modify_entry->old_mem_pic_num!=$modify_entry->now_mem_pic_num) {
                $modify_entry->new_mem_pic_num = $modify_entry->now_mem_pic_num ;
            }
            
            $modify_entry->save();
            return $this->modify_pic_entry($rs);
        }
    }
    
    public function updateModifyNewMemPicNum($modify_entry=null)
    {
        if(!$modify_entry)
            $modify_entry = $this->modify_entry();
        if(!$modify_entry) return;
        $user = $modify_entry->real_auth_user_apply->user;
        $pic_num = $user->pic->count();
        $new_mem_pic_num = $pic_num + $modify_entry->real_auth_user_apply->real_auth_user_modify_pic()->where('real_auth_user_modify.status',0)->whereNull('old_pic')->count();
        $modify_entry->new_mem_pic_num = $new_mem_pic_num;
        $rs = $modify_entry->save();
        
        if($rs) {
            $modify_entry->real_auth_user_apply->pic_modify_id = $modify_entry->id;
            $modify_entry->real_auth_user_apply->save();
        }
    }

    public function deleteBeautyAuthPic($dataEntry) 
    {
        $pic=$dataEntry->pic;     
        return $this->rau_repo()->deleteReplyPicByPic($pic);
    }

    public function deleteFamousAuthPic($dataEntry) {
        $pic=$dataEntry->pic;
        
        return $this->rau_repo()->deleteReplyPicByPic($pic);

    }
    
    public function isPassedByAuthTypeId($auth_type_id) 
    {
        return $this->rau_repo()->isPassedByAuthTypeId($auth_type_id);
    }  
    
    public function isNoProgressByAuthTypeId($auth_type_id) 
    {
        $is_passed = $this->isPassedByAuthTypeId($auth_type_id);
        $unchk_apply_entry = $this->getUncheckedApplyByAuthTypeId($auth_type_id);

        if(!$is_passed && !$unchk_apply_entry)
        {
            return true;
        }
        
        return false;
    }  

    public function isSelfAuthApplyNotVideoYet() 
    {
        
        $is_self_auth_passed = $this->rau_repo()->isPassedByAuthTypeId(1);
        
        if($is_self_auth_passed) return false; 

        $unchk_sa_apply_entry = $this->getUncheckedApplyByAuthTypeId(1);
        
        return ($unchk_sa_apply_entry && $unchk_sa_apply_entry->status==0 && !$unchk_sa_apply_entry->video_record_id);        
    }

    public function isSelfAuthWaitingCheck() 
    {
        
        $is_self_auth_passed = $this->rau_repo()->isPassedByAuthTypeId(1);
        
        if($is_self_auth_passed) return false;
        
        $unchk_sa_apply_entry = $this->getUncheckedApplyByAuthTypeId(1);
        
        return ($unchk_sa_apply_entry && $unchk_sa_apply_entry->video_record_id && $unchk_sa_apply_entry->status!=2);
  
    } 
    
    public function isBeautyAuthWaitingCheck() 
    {

        $unchk_apply_entry = $this->getUncheckedApplyByAuthTypeId(2);
        
        return $unchk_apply_entry && $unchk_apply_entry->status!=2;
  
    } 
     

    public function isFamousAuthWaitingCheck() 
    {

        $unchk_apply_entry = $this->getUncheckedApplyByAuthTypeId(3);
        
        return $unchk_apply_entry && $unchk_apply_entry->status!=2;
  
    }  

    public function isApplyEffectByAuthTypeId($auth_type_id) 
    {
        $apply_entry = $this->getApplyByAuthTypeId($auth_type_id);
        return ($apply_entry && $apply_entry->status!=2);
    }
    
    public function getLatestActualUnchekedHeightModifyEntry() 
    {
        $modify_entry = null;
        $apply_entry = $this->getApplyByAuthTypeId(1);
        
        if($apply_entry && $this->isPassedByAuthTypeId(1))
        {
            $modify_entry = $apply_entry->latest_unchecked_height_modify;
        }
        
        return $this->modify_entry($modify_entry);
    }
    
    public function getLatestActualUnchekedWeightModifyEntry() 
    {
        $modify_entry = null;

        $apply_entry = $this->getApplyByAuthTypeId(1);
        
        if($apply_entry && $this->isPassedByAuthTypeId(1))
        {
            $modify_entry = $apply_entry->latest_unchecked_weight_modify;
        }
        
        return $this->modify_entry($modify_entry);
    } 
    
    public function getLatestActualUnchekedExchangePeriodModifyEntry() 
    {
        $modify_entry = null;

        $apply_entry = $this->getApplyByAuthTypeId(1);
        
        if($apply_entry && $this->isPassedByAuthTypeId(1))
        {
            $modify_entry = $apply_entry->latest_unchecked_exchange_period_modify;
        }
        
        return $this->modify_entry($modify_entry);
    }  

    public function getLatestActualUncheckedAvatarModifyEntry()
    {
        $apply_entry = $this->getApplyByAuthTypeId(1);
        $avatar_modify = null;
        
        if($apply_entry && $this->isPassedByAuthTypeId(1)) {
            $avatar_modify = $apply_entry->latest_unchecked_pic_modify()
                            ->whereHas('real_auth_user_modify_pic',function($q){
                                $q->where('pic_cat','avatar');
                            })
                            ->with(['real_auth_user_modify_pic'=>function($q){
                                $q->where('pic_cat','avatar')->orderByDesc('id')->take(1);
                            }])->first();
        }
        
        return $this->modify_entry($avatar_modify);
    } 

    public function getLatestActualUncheckedDistinctOldPicModifyMemPicList()
    {
        $apply_entry = $this->getApplyByAuthTypeId(1);
        $modify_pic_list = collect([]);
        
        if($apply_entry && $this->isPassedByAuthTypeId(1)) {

            $modify_pic_list = $apply_entry->real_auth_user_modify_pic()
                            ->groupBy('old_pic')
                            ->select('old_pic')
                            ->selectRaw('max(real_auth_user_modify_pic.id) as id')
                            ->where('pic_cat','member_pic')
                            ->where('real_auth_user_modify.status',0)
                            ->union($apply_entry->real_auth_user_modify_pic()->where('real_auth_user_modify.status',0)->where('pic_cat','member_pic')->whereNull('old_pic')->select(['old_pic','real_auth_user_modify_pic.id','real_auth_user_modify.apply_id']))
                            ->with('real_auth_user_modify_pic')
                            ->get();
        }

        return $this->rau_repo()->modify_pic_list( $modify_pic_list);
    }     

    public function getProfileWeightWord($weight) 
    {
        return $weight?($weight-4).' ~ '.$weight:0;
    }
    
    public function forgetRealAuthProcess() 
    {
        session()->forget('real_auth_type');
    }


}
