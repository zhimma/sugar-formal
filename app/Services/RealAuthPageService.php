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
        //$this->user = $this->user_service->model??null;
        $this->rau_repo = $realAuthUserRepo; 
        $this->rau_repo->riseByUserEntry($this->user());
        //$this->ra_repo = $this->rau_repo->real_auth_repo();
        $this->init();
    } 
    
    public function init() {
        $this->rau_repo->init();
        $this->error_msg('');
        //if(($this->rau_repo->user()->id??null)!=)
        //$this->ra_repo = $this->rau_repo->real_auth_repo();
        return $this;
    }   

    public function initByUserService($userService) {
        
        $this->user_service = $userService;
        $this->rau_repo->riseByUserEntry($this->user_service->model??null);        
        $this->init();
        return $this;
    }
    
    public function initByUserEntry($userEntry) {
        //$this->init();
        $this->user_service->riseByUserEntry($userEntry);
        $this->rau_repo->riseByUserEntry($userEntry);        
        $this->init();
        return $this;
    }    
    
    public function riseByUserService(UserService $userService) {
        $this->user_service = $userService;
        $this->rau_repo->riseByUserEntry($this->user_service->model??null);
        return $this;
    }
    
    public function riseByUserEntry($user_entry) {
        $this->rau_repo()->riseByUserEntry($this->user_service->riseByUserEntry($user_entry)->model);

        return $this;    
    }
    
    public function user() {
        return $this->user_service->model??null;
        //return $this->rau_repo()->user();
    }

    public function user_service() {
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
   
    public function ra_type_list() 
    {
        if(!$this->ra_repo()->type_list())
            $this->ra_repo()->fillTypeList();
        return $this->ra_repo()->type_list();
    }
    
    public function reply_list() 
    {
        return $this->user()->real_auth_user_reply;
    }
    
    public function reply_list_query() 
    {
        return $this->user()->real_auth_user_reply();
    }
  
    public function error_msg($msg=null) {
        if($msg!==null) $this->_error_msg = $msg;
        return $this->_error_msg;        
    }    

    public function isAllowRealAuthType($value) {
        $type_list = $this->ra_type_list();
        if(!$type_list) $type_list = collect([]);
        if($type_list->where('id',$value)->first()) return true;
        else return false;
    }
    
    public function turnRealAuthTypeIdToName($id) {
        $type_list = $this->ra_type_list();
        $found_type_list = $type_list->where('id',$id);
        
        if($found_type_list->count()) {
            return $found_type_list->first()->name;
        }
    }
    
    public function isInRealAuthProcess() {
        $real_auth_arg = request()->real_auth;
        $real_auth_type = session()->get('real_auth_type');        
        return $real_auth_arg && $real_auth_type;
    }
    
    public function isInCorrectRealAuthProcess() {
        $real_auth_arg = request()->real_auth;
        $real_auth_type = session()->get('real_auth_type');        
        return $this->isInRealAuthProcess() 
                && $real_auth_arg==$real_auth_type
                && $this->isAllowRealAuthType($real_auth_arg)
                && $this->isAllowRealAuthType($real_auth_type)
                ;
    }    
    
    public function returnInWrongRealAuthProcess() {
        $real_auth_arg = request()->real_auth;
        if(!$real_auth_arg) {
            session()->forget('real_auth_type');
            return;
        }
        $real_auth_type = session()->get('real_auth_type');
        //if($real_auth_arg && !$real_auth_type
        //    || (!$real_auth_arg && $real_auth_type)
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
    
    public function getBeautyAuthQuestionList() {
        return $this->ra_repo()->getQuestionListByAuthType(2);
    }
    
    public function getFamousAuthQuestionList() {
        return $this->ra_repo()->getQuestionListByAuthType(3);
    }
   
    public function questionTypeToKey($question_type=null) {
        return $this->ra_repo()->questionTypeToKey($question_type);
    }
     
    public function passGroup() {
        return $this->real_auth_service()->group_entry()->real_auth_user_group->where('user_id',$this->user()->id)->first()->update(['is_pass'=>1]);        
    }

    public function getQuPreFilesByEntry($question_entry) 
    {
        $paths = array();
        $question_id = $question_entry->id;
        $apply_entry = $this->rau_repo()->getWorkingApplyByAuthTypeId($question_entry->auth_type_id);
        if($apply_entry->from_admin) return;        
        $reply_entry_list  = $this->reply_list()??collect([]);
        $reply_entry_list =  $reply_entry_list->where('question_id',$question_id)->where('apply_id',$apply_entry->id);        
        $pic_list = null;
        if($reply_entry_list->count()) 
        {
            
            $reply_entry = $reply_entry_list->first();
            
            if($reply_entry->pic_choice_id) 
            {
                $pic_list = $reply_entry->real_auth_user_reply_pic;
            } 

            if(!$pic_list && $this->questionTypeToKey($question_entry->type)==5) 
            {
                $pic_list = $reply_entry->real_auth_user_reply_pic;
            }
            
            if($pic_list) 
            {
                foreach($pic_list as $pic) 
                {
                    $path = $pic->pic;
                    $path_slice = explode('/', $path);

                    if(!file_exists(public_path($path)))
                    {
                        $paths[] = array(
                            "name" => end($path_slice), //filename
                            "type" => FileUploader::mime_content_type($path),
                            "size" => 0, //filesize需完整路徑
                            "file" => $path,
                            "relative_file" => public_path($path), // full path for editing files
                            "local" => $path,
                            "data" => array(
                                "readerForce" => true,
                                "isPreload" => true //為預先載入的圖片
                            )
                        );
                    }
                    else{
                        $paths[] = array(
                            "name" => end($path_slice), //filename
                            "type" => FileUploader::mime_content_type($path),
                            "size" => filesize(public_path($path)), //filesize需完整路徑
                            "file" => $path,
                            "relative_file" => public_path($path), // full path for editing files
                            "local" => $path,
                            "data" => array(
                                "readerForce" => true,
                                "isPreload" => true //為預先載入的圖片
                            )
                        );
                    }

                    
                }
            }

        }

        return json_encode($paths);    
    }
    
    public function getQuValueAttrByEntry($question_entry,$value=null,$default_attr=null) 
    {
        $rau_repo = $this->rau_repo();
        $user= $this->user();

        $question_id = $question_entry->id;
        //$reply_entry_list  = $user->real_auth_user_reply??collect([]);     
        //$reply_entry_list =  $reply_entry_list->where('question_id',$question_id);
        $apply_entry = $rau_repo->getWorkingApplyByAuthTypeId($question_entry->auth_type_id);
        if($apply_entry->from_admin) return;
        $reply_entry = null;
        if($apply_entry) {
            $reply_entry = $apply_entry->real_auth_user_reply->where('question_id',$question_id)->first()??null;
        }
        $request_value_arr = request()->reply[$question_id]??null;
        $attr = '';
        $reply_record = [];
        $reply_arr = [];
        //if($reply_entry_list->count()) {
            //$reply_entry = $reply_entry_list->first();
         if($reply_entry)  {
            if($reply_entry->reply_choices ) $reply_arr = explode(',',$reply_entry->reply_choices);
            if($reply_entry->choice_id ) $reply_arr[]=$reply_entry->choice_id;
            if($reply_entry->reply_bit!==null) $reply_arr[] = $reply_entry->reply_bit;
            //if($reply_entry->reply_context && !$reply_entry->reply_choices) $reply_arr[]=$reply_entry->reply_context;
            $reply_context_arr = [];
            if($reply_entry->reply_context) {
                //$context_arr = explode(',',$reply_entry->reply_context );
                $context_arr = json_decode($reply_entry->reply_context,true );
                if(!$context_arr) $context_arr[] = $reply_entry->reply_context;
                foreach(explode(',',$reply_entry->context_choices) as $ck=>$cv) {
                    $reply_context_arr['choice'.$cv] =  $context_arr[$ck];
                    //if($choice_id && $choice_id==$cv)
                }
            }
            
            //if($reply_entry->reply_context && $reply_entry->choice_id) {
                //$reply_context_arr['choice'.$reply_entry->choice_id] =  $reply_entry->reply_context;
            //}            
            
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

    public function saveRealAuthApply($dataEntry) {
        if($this->isInRealAuthProcess() || $dataEntry->real_auth==3)
            return $this->rau_repo()->saveApply($dataEntry);
    }
    
    public function saveFamousAuthForm($dataEntry) {
        return $this->rau_repo()->saveReply($dataEntry);
    }
    
    public function saveBeautyAuthForm($dataEntry) {
        return $this->rau_repo()->saveReply($dataEntry);
    }

    public function deleteBeautyAuthPic($dataEntry) {
        $pic=$dataEntry->pic;     
        return $this->rau_repo()->deleteReplyPicByPic($pic);
    }

    public function deleteFamousAuthPic($dataEntry) {
        $pic=$dataEntry->pic;
        
        return $this->rau_repo()->deleteReplyPicByPic($pic);

    }
    
    public function isPassedByAuthTypeId($auth_type_id) {
        return $this->rau_repo()->isPassedByAuthTypeId($auth_type_id);
    }    
}
