<?php
namespace App\Repositories;
use App\Models\RealAuthUserReply;
use App\Repositories\UserRepository;
use App\Repositories\RealAuthRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class RealAuthUserRepository {
    public function __construct(
        UserRepository $userRepo
        ,RealAuthRepository $realAuthRepo
    ) {
        $this->real_auth_repo = $realAuthRepo;
        $this->user_repo =    $userRepo; 
        $this->init();         
    } 
    
    public function init() {
        
        $this->apply_list = null;
        $this->apply_entry = null;
        $this->modify_list = null;
        $this->modify_entry = null;        
    }       
    
    public function user() {
        return $this->user_repo->user();
    }

    public function user_repo() {
        return $this->user_repo;
    }
    
    public function real_auth_repo() {
        return $this->real_auth_repo;
    } 
    
    public function apply_list($value_or_reset=false) {
        if($value_or_reset===true) {
            $this->apply_list = null;  
        }
        else if($value_or_reset!==false) {
            $this->apply_list = $value_or_reset;
        }
        
        if($value_or_reset===false && !$this->apply_list)
        {
            $this->apply_list = $this->user()->real_auth_user_apply;
        }
        
        return $this->apply_list;
    } 
    
    public function reply_list($value_or_reset=false) {
        if($value_or_reset===true) {
            $this->reply_list = null;  
        }
        else if($value_or_reset!==false) {
            $this->reply_list = $value_or_reset;
        }
        
        if($value_or_reset===false && !$this->apply_list)
        {
            $this->reply_list = $this->user()->real_auth_user_reply;
        }
        
        return $this->reply_list;
    }    
    
    public function modify_list($value_or_reset=false) {
        if($value_or_reset===true) {
            $this->modify_list = null;  
        }
        else if($value_or_reset!==false) {
            $this->modify_list = $value_or_reset;
        }
        
        if($value_or_reset===false && !$this->modify_list)
        {
            $this->modify_list = $this->user()->real_auth_user_modify;
        }        
        
        return $this->modify_list;
    } 

    

    public function apply_entry($value_or_reset=false) {
        if($value_or_reset===true) {
            $this->apply_entry = null;  
        }
        else if($value_or_reset!==false) {
            $this->apply_entry = $value_or_reset;
        }
        
        return $this->apply_entry;
    } 

    public function modify_entry($value_or_reset=false) {
        if($value_or_reset===true) {
            $this->modify_entry = null;  
        }
        else if($value_or_reset!==false) {
            $this->modify_entry = $value_or_reset;
        }
        
        return $this->modify_entry;
    }


    public function riseByUser($user_entry) {
        $this->riseByUserEntry($user_entry);
        return $this;    
    }
    
    public function riseByUserEntry($user_entry) {
        if(!$user_entry) $user_entry = new User;
        $this->user_repo->user($user_entry);
        return $this;    
    }
    
    public function slotByApplyEntry($apply_entry) {
        $this->apply_entry($apply_entry);
        return $this;
    }
  
    public function questionTypeToKey($question_type=null) {
        return $this->real_auth_repo()->questionTypeToKey($question_type);
    }
    
    public function saveApply($dataEntry) {
        $real_auth = $dataEntry->real_auth;
        if(!$real_auth) return;
        $apply_data['auth_type_id'] = $real_auth;
        $apply_data['apply_at'] = Carbon::now();
        
        if(!$this->user()->real_auth_user_apply->where('auth_type_id',$apply_data['auth_type_id'] )->where('status',0)->sortByDesc('id')->first()) {
            $apply_entry = $this->user()->real_auth_user_apply()->create($apply_data);
            
            if($apply_entry) return $this->apply_entry($apply_entry);
        }
    }
    
    public function saveReply($dataEntry) {
        
        $reply = $dataEntry->reply??[];
        $real_auth = $dataEntry->real_auth;
        $all_choice_list = $this->getChoiceListByAuthTypeId($real_auth); 
        $choice_list = $this->getChoiceListByAuthTypeId($real_auth);
        $dataArr = [];
        $dataPicArr = [];
        $dataQuestionPicArr = [];
        $totalRs = null;
        //$user_apply_list = $this->apply_list()->sortByDesc('id');
       //$apply_entry = $user_apply_list->where('auth_type_id',$question_entry->auth_type_id)->first();
       $apply_entry = $this->getUncheckedApplyByAuthTypeId($real_auth);
        if(!$apply_entry) {
            $apply_entry = $this->saveApply((object) ['real_auth'=>$real_auth]);
        }
        //if(!$apply_entry) {
            //$apply_entry = $this->user()->real_auth_user_apply()->create(['auth_type_id'=>$real_auth]);
        //} 
        //$data['apply_id'] = $apply_entry->id;
        $ra_repo = $this->real_auth_repo();
        foreach($reply as $rk=>$rv) {
            if($rk=='sub_choice') continue;
            $this->_getDbDataArrByQuIdAndAssocValue($rk,$rv,($reply['sub_choice'][$rk]??[]),$dataArr,$dataPicArr,$dataQuestionPicArr,$totalRs);
        }
        
        //foreach(($reply['sub_choice']??[]) as $sub_rk=>$sub_rv) {
            //$this->_getDbDataArrByQuIdAndAssocValue($sub_rk,$sub_rv,[],$dataArr,$dataPicArr,$dataQuestionPicArr,$totalRs);
        //}   
        //$this->user()->real_auth_user_reply()->delete();
        if($dataArr) {
            //$rs = $this->user()->real_auth_user_reply()->createMany($dataArr);
            $rs = $apply_entry->real_auth_user_reply()->createMany($dataArr);
            if($totalRs===null) $totalRs = $rs;
            else $totalRs = $totalRs && $rs;
            if($rs && $rs->count()) {
                foreach($dataPicArr as $pak=>$pav) {
                    $rs->where('pic_choice_id',$pak)->first()->real_auth_user_reply_pic()->createMany($pav);
                }
                
                foreach($dataQuestionPicArr as $qpak=>$qpav) {
                    $rs->where('question_id',$qpak)->first()->real_auth_user_reply_pic()->createMany($qpav);
                }
            }
        }
        return $totalRs;
    }
    
    protected function _getDbDataArrByQuIdAndAssocValue($rk,$rv,$rk_sub_reply,&$dataArr,&$dataPicArr,&$dataQuestionPicArr,&$totalRs) {
        $data_elt = [];
        $ra_repo = $this->real_auth_repo();
        $now_context_reply = [];
        $now_pic_val=null;
        $now_question_pic_val = null;
        $question_entry = $ra_repo->riseByQuestionId($rk)->question_entry;
        $data_elt['question_id'] = $question_entry->id;
        $question_type_key = $this->questionTypeToKey();
        $apply_entry = $this->apply_entry();
        
        if($question_type_key!==false) {
            switch($question_type_key) {
                case 0:
                    if(is_array($rv)) break;
                    $data_elt['reply_bit'] = $rv;
                break;
                case 1:
                    if(is_array($rv)) break;
                    $data_elt['choice_id'] = $rv;
                break;
                case 2:
                    $data_elt['reply_choices'] = implode(',',$rv??[]);
                break;
                case 3:
                case 4:
                    if(is_array($rv) ) {
                        //if(count($rv)>1)
                            $data_elt['context_choices'] = implode(',',array_keys($rv??[]));
                        //else $data_elt['choice_id'] = implode(',',array_keys($rv??[]));
                        //$data_elt['reply_context'] = implode(',',array_values($rv??[]));
                        $data_elt['reply_context'] = json_encode(array_values($rv??[]));
                    }
                break;
                case 5:
                    if(is_array($rv) && $rv && !$question_entry->real_auth_choice->count()) 
                    {
                        $now_question_pic_val = $this->getPicSavingValByFiles($rv);
                    }
                break;
            }

            if($rk_sub_reply) {
                if(is_array($rk_sub_reply) ) {
                    if(!($data_elt['context_choices']??null)) {
                        $data_elt['context_choices'] = '';
                    }
                    if(!($data_elt['reply_context']??null)) {
                        $data_elt['reply_context'] = '';
                    }                    
                    $data_elt['context_choices'] .= implode(',',array_keys($rk_sub_reply??[]));
                    $data_elt['reply_context'] .= json_encode(array_values($rk_sub_reply??[]));
                }      
            }
 
        }
        //print_r($data_elt);
        if(count($data_elt)<2 && is_array($rv) && $question_entry->real_auth_choice->count()) {
            //if(is_array($rv) ) {

            foreach($rv as $rvk=>$rvv) {
                //$choice_entry = $ra_repo->slotByChoiceId($rvk)->choice_entry();
                $choice_entry = $this->getChoiceListByAuthTypeId($question_entry->auth_type_id)->where('id',$rvk)->first();
                switch($this->questionTypeToKey($choice_entry->type)) {
                    case 3:
                    case 4:
                        $now_context_reply[$rvk] = $rvv;
                    break;
                    case 5:
                        if($rvv) {
                            $now_pic_val = $this->getPicSavingValByFiles($rvv);
                            $data_elt['pic_choice_id'] = $rvk;
                        }
                    break;
                }
            }
            
            //if(count($now_context_reply)>1)
                $data_elt['context_choices'] = implode(',',array_keys($now_context_reply??[]));
            //else $data_elt['choice_id'] = implode(',',array_keys($now_context_reply??[]));
            //else $data_elt['choice_id'] = $rvk;
            //$data_elt['reply_context'] = implode(',',array_values($now_context_reply??[]));                        
            $data_elt['reply_context'] = json_encode(array_values($now_context_reply??[]));                        
            

            
            //}                
        }
        if($data_elt) {
            //$now_reply_entry = $question_entry->real_auth_user_reply;
            $now_reply_entry = $apply_entry->real_auth_user_reply->where('question_id',$question_entry->id)->first();
            if($now_reply_entry ??null)
            {
                //if($data_elt['choice_id']??null)
                //{
                    $now_reply_entry->choice_id= $data_elt['choice_id']??null;
                //}
                
                if($data_elt['context_choices']??null)
                {
                    $now_reply_entry->context_choices= $data_elt['context_choices']??null;
                }                    
                
                if($data_elt['pic_choice_id']??null)
                {
                    $now_reply_entry->pic_choice_id= $data_elt['pic_choice_id']??null;
                }
                
                $now_reply_entry->reply_choices = $data_elt['reply_choices']??null;
                $now_reply_entry->reply_bit = $data_elt['reply_bit']??null;
                $now_reply_entry->reply_context = $data_elt['reply_context']??null;
                $now_rs = $now_reply_entry->save();
                //if($question_entry->id==4) print_r($now_reply_entry);
                if($totalRs===null) $totalRs = $now_rs;
                else $totalRs = $totalRs && $now_rs;
                if($now_rs && $now_pic_val) {
                    if($now_pic_val) {
                        $now_reply_entry->real_auth_user_reply_pic()->createMany($now_pic_val);
                    }
                    if($now_question_pic_val) {
                        $now_reply_entry->real_auth_user_reply_pic()->createMany($now_question_pic_val);
                    }
                }
            }
            else {
                $dataArr[] = $data_elt;
                if($now_pic_val) {
                    $dataPicArr[$ra_repo->choice_entry()->id] = $now_pic_val;
                } 
                if($now_question_pic_val) {
                    $dataQuestionPicArr[$ra_repo->question_entry()->id] = $now_question_pic_val;
                }                      
            }       
        }

        return $data_elt;
    }

    
    public function getPicSavingValByFiles($files) {
        $images_ary=array();
       
        foreach ($files as $key => $file) {
            $now = Carbon::now()->format('Ymd');
            $input['imagename'] = $now . rand(100000000,999999999) . '.' . $file->getClientOriginalExtension();

            $rootPath = public_path('/img/Message');
            $tempPath = $rootPath . '/' . substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/';

            if(!is_dir($tempPath)) {
                File::makeDirectory($tempPath, 0777, true);
            }

            $destinationPath = '/img/Message/'. substr($input['imagename'], 0, 4) . '/' . substr($input['imagename'], 4, 2) . '/'. substr($input['imagename'], 6, 2) . '/' . $input['imagename'];

            $img = Image::make($file->getRealPath());
            $img->resize(400, 600, function ($constraint) {
                $constraint->aspectRatio();
            })->save($tempPath . $input['imagename']);

            $now_images_idx = count($images_ary);
            $images_ary[$now_images_idx]['pic_origin_name']= $file->getClientOriginalName();
            $images_ary[$now_images_idx]['pic']= $destinationPath;            
        }

        return $images_ary;
    }
   
    public function getUncheckedApplyByAuthTypeId($auth_type_id) 
    {
        $apply_entry = null;
        $user = $this->user();
        switch($auth_type_id) {
            case 1:
                $apply_entry = $user->self_auth_unchecked_apply;
            break;
            case 2:
                $apply_entry = $user->beauty_auth_unchecked_apply;
            break;
            case 3:
                $apply_entry = $user->famous_auth_unchecked_apply;
            break;
            
        }
        
        if($apply_entry) $this->apply_entry($apply_entry);
        
        return $apply_entry;
   }
   
    public function getWorkingApplyByAuthTypeId($auth_type_id) 
    {
        $apply_entry = null;
        $user = $this->user();
        switch($auth_type_id) {
            case 1:
                $apply_entry = $user->real_auth_user_apply->where('auth_type_id',1)->sortByDesc('id')->first();
            break;
            case 2:
                $apply_entry = $user->beauty_auth_working_apply;
            break;
            case 3:
                $apply_entry = $user->famous_auth_working_apply;
            break;
            
        }
        
        if($apply_entry) $this->apply_entry($apply_entry);
        
        return $apply_entry;
   }   
   
   public function getChoiceListByAuthTypeId($auth_type_id) 
   {
       static $auth_type_choice_list = null;
       if(!($auth_type_choice_list[$auth_type_id]??null)) {
            $auth_type_choice_list[$auth_type_id] =
                $this->real_auth_repo()->choice_list(
                    $this->real_auth_repo()->choice_entry()
                        ->whereHas('real_auth_question',function($q) use ($auth_type_id){
                            $q->where('auth_type_id',$auth_type_id);
                        })->get());           
       }
       return $auth_type_choice_list[$auth_type_id]??null;
   }
    
    
    public function deleteReplyPicByPic($pic) 
    {
        $rs = null;
        $success_num = 0;
        $fullPath = public_path($pic);
        
        if(File::exists($fullPath))
            unlink($fullPath);
        $reply_list = $this->user()->real_auth_user_reply()->whereHas('real_auth_user_reply_pic',function($q) use ($pic) {$q->where('pic',$pic);})->get();        

        if($reply_list->count()) {
            foreach($reply_list as $reply_entry) {
                
                $now_rs = $reply_entry->real_auth_user_reply_pic()->where('pic',$pic)->delete();
                if($now_rs) $success_num++;
                if($rs===null) {
                    $rs = $now_rs;
                }
                else {
                    $rs = $rs && $now_rs;
                }
            }
            
            if($success_num) {
                if(File::exists($fullPath))
                    unlink($fullPath);                
            }
        }   
        return $rs;
    }

    public function passGroup() {
        return $this->real_auth_service()->group_entry()->real_auth_user_group->where('user_id',$this->user()->id)->first()->update(['is_pass'=>1]);        
    }

    public function getAuthStatusByAuthTypeId($auth_type_id) {
        $status = null;
        $user = $this->user();
        switch($auth_type_id) {
            case 1:
                $status = $user->self_auth_status ;
            break;
            case 2:
                $status = $user->beauty_auth_status;
            break;
            case 3:
                $status = $user->famous_auth_status ;
            break;
        }
        
        return $status;
    }

    public function getAuthStatusApplyIdByAuthTypeId($auth_type_id) {
        $apply_id = null;
        $user = $this->user();
        switch($auth_type_id) {
            case 1:
                $apply_id = $user->self_auth_apply_id ;
            break;
            case 2:
                $apply_id = $user->beauty_auth_apply_id;
            break;
            case 3:
                $apply_id = $user->famous_auth_apply_id ;
            break;
        }
        
        return $apply_id;
    } 

    public function isPassedByAuthTypeId($auth_type_id) {
        return !!($this->getAuthStatusByAuthTypeId($auth_type_id)==1);
    }    
    
}
