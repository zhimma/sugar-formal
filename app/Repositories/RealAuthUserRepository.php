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
use \FileUploader;
use \Exception;

class RealAuthUserRepository 
{
    public function __construct(
        UserRepository $userRepo
        ,RealAuthRepository $realAuthRepo
    ) {
        $this->real_auth_repo = $realAuthRepo;
        $this->user_repo =    $userRepo; 
        $this->init();         
    } 
    
    public function init() 
    {  
        $this->apply_list = null;
        $this->apply_entry = null;
        $this->modify_list = null;
        $this->modify_entry = null;
        $this->modify_pic_list = null;
        $this->modify_pic_entry = null;
        $this->patch_entry = null;
        $this->error_msg('');        
    } 

    public function error_msg($msg=null) 
    {
        if($msg!==null) $this->_error_msg = $msg;
        return $this->_error_msg;        
    }     
    
    public function user($userOrReset=null) 
    {
        return $this->user_repo->user($userOrReset);
    }

    public function user_repo() 
    {
        return $this->user_repo;
    }
    
    public function real_auth_repo() 
    {
        return $this->real_auth_repo;
    } 
    
    public function apply_list($value_or_reset=false) 
    {
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
    
    public function reply_list($value_or_reset=false) 
    {
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
    
    public function modify_list($value_or_reset=false) 
    {
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

    public function modify_pic_list($value_or_reset=false) 
    {
        if($value_or_reset===true) {
            $this->modify_pic_list = null;  
        }
        else if($value_or_reset!==false) {
            $this->modify_pic_list = $value_or_reset;
        }
        
        if($value_or_reset===false && !$this->modify_pic_list)
        {
            $this->modify_pic_list = $this->user()->real_auth_user_modify;
        }        
        
        return $this->modify_pic_list;
    }  

    public function apply_entry($value_or_reset=false) 
    {
        if($value_or_reset===true) {
            $this->apply_entry = null;  
        }
        else if($value_or_reset!==false) {
            $this->apply_entry = $value_or_reset;
        }
        
        return $this->apply_entry;
    } 
    
    public function patch_entry($value_or_reset=false) 
    {
        if($value_or_reset===true) {
            $this->patch_entry = null;  
        }
        else if($value_or_reset!==false) {
            $this->patch_entry = $value_or_reset;
        }
        
        return $this->patch_entry;
    }     

    public function modify_entry($value_or_reset=false) 
    {
        if($value_or_reset===true) {
            $this->modify_entry = null;  
        }
        else if($value_or_reset!==false) {
            $this->modify_entry = $value_or_reset;
        }
        
        return $this->modify_entry;
    }

    public function modify_pic_entry($value_or_reset=false) 
    {
        if($value_or_reset===true) {
            $this->modify_pic_entry = null;  
        }
        else if($value_or_reset!==false) {
            $this->modify_pic_entry = $value_or_reset;
        }
        
        return $this->modify_pic_entry;
    }

    public function riseByUser($user_entry) 
    {
        $this->riseByUserEntry($user_entry);
        return $this;    
    }
    
    public function riseByUserEntry($user_entry) 
    {
        $this->user($user_entry);
        return $this;    
    }
    
    public function riseByUserId($user_id) 
    {
        $user_entry = $this->user()->find($user_id);
        $this->user($user_entry);
        return $this;    
    }    
    
    public function slotByApplyEntry($apply_entry) 
    {
        $this->apply_entry($apply_entry);
        return $this;
    }
  
    public function questionTypeToKey($question_type=null) 
    {
        return $this->real_auth_repo()->questionTypeToKey($question_type);
    }
    
    public function saveApply($dataEntry) 
    {

        $auth_type_id = $dataEntry->auth_type_id;

        if(!$auth_type_id) return;

        $apply_entry = $this->getApplyByAuthTypeId($auth_type_id);
        
        if(!$apply_entry) {

            $apply_data['auth_type_id'] = $auth_type_id;
            
            if($dataEntry->from_auto??null) $apply_data['from_auto'] = $dataEntry->from_auto;

            $apply_entry = $this->user()->real_auth_user_apply()->create($apply_data);
            
            if($apply_entry) return $this->apply_entry($apply_entry);
        }
        else {
            if($auth_type_id ||  property_exists($dataEntry,'auth_type_id')) {
                $apply_entry->auth_type_id = $auth_type_id??null;
            }

            if(($dataEntry->status??null)!==null  ||  property_exists($dataEntry,'status')) {
                $apply_entry->status = $dataEntry->status??null;
            } 

            if(($dataEntry->from_auto??null)!==null  ||  property_exists($dataEntry,'from_auto')) {
                $apply_entry->from_auto = $dataEntry->from_auto??null;
            } 

            if(($dataEntry->status_at??null)  ||  property_exists($dataEntry,'status_at')) {
                $apply_entry->status_at = $dataEntry->status_at??null;
            } 

            if($apply_entry->save()) {
                return $this->apply_entry($apply_entry);
            }
        }
    }
    
    public function saveModifyByArr($arr) 
    {
        $data = $arr;
        $apply_entry = $this->apply_entry();
        
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
            return $this->modify_entry($rs);
        }
    }
    
    public function getEffectWorkingReplyListFromApplyEntry($apply_entry=null) 
    {
        if($apply_entry)
            return $apply_entry->effect_working_modify_list()->with('real_auth_user_reply')->get()->pluck('real_auth_user_reply')->flatten();
        else   return $this->apply_entry()->effect_working_modify_list()->with('real_auth_user_reply')->get()->pluck('real_auth_user_reply')->flatten();
    }
    
    public function getActualUncheckedReplyListFromApplyEntry($apply_entry=null) 
    {
        if(!$apply_entry) {
            $apply_entry = $this->apply_entry();
        }
        if($apply_entry)
            return $apply_entry->actual_unchecked_modify()->with('real_auth_user_reply')->get()->pluck('real_auth_user_reply')->flatten();
    }    
    
    public function getQuUploaderPreFilesById($question_id)
    {
        $question_entry = $this->real_auth_repo()->question_entry()->find($question_id);
    
        return $this->getQuUploaderPreFilesByEntry($question_entry) ;
    }
     
    public function getQuActualUncheckedPicNumByEntry($question_entry)
    {
        $paths = array();
        $pic_num = 0;
        
        $question_id = $question_entry->id;
        $apply_entry = $this->getApplyByAuthTypeId($question_entry->auth_type_id);       
        $reply_entry_list = collect([]);
        
        if($apply_entry) {
            $reply_entry_list  = $this->getActualUncheckedReplyListFromApplyEntry()??collect([]);        
            $reply_entry_list =  $reply_entry_list->where('question_id',$question_id);        
        }  

        if($reply_entry_list->count()) 
        {
            
            foreach($reply_entry_list as $reply_entry) {
                
                $now_pic_num=0;
                
                if($reply_entry->pic_choice_id) 
                {
                    $now_pic_num= $reply_entry->real_auth_user_reply_pic->count();
                    $pic_num+=$now_pic_num;
                } 

                if(!$now_pic_num && $this->questionTypeToKey($question_entry->type)==5) 
                {
                    $pic_num+= $reply_entry->real_auth_user_reply_pic->count();
                }                
            }
        }
        
        return $pic_num;
    }
    
    public function getQuUploaderPreFilesByEntry($question_entry) 
    {
        $paths = array();
        $question_id = $question_entry->id;
        $apply_entry = $this->getApplyByAuthTypeId($question_entry->auth_type_id);
        $reply_entry_list = collect([]);
        
        if($apply_entry) {
            $reply_entry_list  = $this->getEffectWorkingReplyListFromApplyEntry()??collect([]);

            $reply_entry_list =  $reply_entry_list->where('question_id',$question_id);        
        }
        
        $pic_list = null;
        
        if($reply_entry_list->count()) 
        {
            foreach($reply_entry_list as $reply_entry) {

                $pic_list = null;
                
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
        }

        return json_encode($paths);    
    }
        
    
    public function getUploaderOptionArr($preloadedFiles=null)
    {
        $options =  array(
                'limit' => 20,
                'inputNameBrackets'=> true,
                'fileMaxSize' => 8,
                'extensions' => ['jpg', 'jpeg', 'png', 'gif','bmp','heif','heic'],
                //'required' => true,
                'uploadDir' => $this->uploadDir,
                'title' => function(){
                    $now = Carbon::now()->format('Ymd');
                    return $now . rand(100000000,999999999);
                },
                'replace' => false,
                'editor' => true,
                'files' => $preloadedFiles
            );

        return $options;
    }
    
    public function init_upload_path()
    {
        /*
        * !important
        * 為了維持資料庫格式一致, 請避免使用 public_path('/img/Member'), 
        * 在 Linux 和 Windows 顯示上有所差異
        */
        $this->imageRelativePath = '/img/RealAuth/';        
        $this->imageBasePath = public_path().$this->imageRelativePath;
        $this->uploadDir = $this->imageBasePath . Carbon::now()->format('Y/m/d/');
        if(!File::exists($this->uploadDir)) {
            try {
                File::makeDirectory($this->uploadDir, 0777, true);        
            }catch (Exception $e) {
                $this->error_msg($e->getMessage());
                return false;
            }
            
        }
            
        return true;
    }
    
    public function saveReply($dataEntry,$fill_all=false) 
    {
        $init_path_rs = $this->init_upload_path();
   
        if(!$init_path_rs) {
            return false;
        }
        
        $reply = $dataEntry->reply??[];
        $reply_pic = $dataEntry->reply_pic??[];
        $real_auth = $dataEntry->real_auth;
        $all_choice_list = $this->getChoiceListByAuthTypeId($real_auth); 
        $choice_list = $this->getChoiceListByAuthTypeId($real_auth);
        $dataArr = [];
        $dataPicArr = [];
        $dataQuestionPicArr = [];
        $totalRs = null;
        
        foreach($reply_pic as $rpk=>$rpv) {
            $question_preload_files = $this->getQuUploaderPreFilesById($rpk);
            $file_input_name = 'reply_pic_'.$rpk;
            
            if(is_array($rpv)) {
                foreach($rpv as $vk=>$vv) {
                    $file_input_name.= '_'.$vk;
                
                    $fileUploader = new FileUploader($file_input_name, $this->getUploaderOptionArr($question_preload_files));            

                    $upload = $fileUploader->upload();            
                    
                    if($upload['hasWarnings']??false) {
                        $this->error_msg(is_array($upload['warnings'])?implode("\r\r",$upload['warnings']):$upload['warnings']);
                        return false;
                    }    

                    $reply[$rpk][$vk] = $fileUploader->getUploadedFiles();               
                }
            }
            else {
              
                $fileUploader = new FileUploader($file_input_name, $this->getUploaderOptionArr($question_preload_files));            

                $upload = $fileUploader->upload();            
                
                if($upload['hasWarnings']??false) {
                    $this->error_msg(is_array($upload['warnings'])?implode("\r\r",$upload['warnings']):$upload['warnings']);
                    return false;
                }    

                $reply[$rpk] = $fileUploader->getUploadedFiles();               
                            
            }
     }

        
        $apply_entry = $this->getApplyByAuthTypeId($real_auth);
        
        if(!$apply_entry) {
            $apply_entry = $this->saveApply((object)['auth_type_id'=>$real_auth]);
        }
        else if($apply_entry->status==2) {
           $data_arr['status'] = 0; 
           $data_arr['auth_type_id'] = $real_auth; 
           $data_arr['reply_modify_id'] = null;
        
           $rs = $this->saveApply((object) $data_arr);

            if($rs) {
                $rs->real_auth_user_modify()->firstOrNew()->createNewApplyModify(); 
            }
        }            

        $modify_entry = $this->saveModifyByArr(['item_id'=>5,'has_reply'=>1]);

        $ra_repo = $this->real_auth_repo();
        
        foreach($reply as $rk=>$rv) {
            if($rk=='sub_choice') continue;          
            
            $this->_getDbDataArrByQuIdAndReplyValue($rk,$rv,($reply['sub_choice'][$rk]??[]),$dataArr,$dataPicArr,$dataQuestionPicArr,$totalRs);
        }

        if($dataArr) {

            $rs = $modify_entry->real_auth_user_reply()->createMany($dataArr);
            
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
    
    protected function _getDbDataArrByQuIdAndReplyValue($rk,$rv,$rk_sub_reply,&$dataArr,&$dataPicArr,&$dataQuestionPicArr,&$totalRs) 
    {
        $data_elt = [];
        $ra_repo = $this->real_auth_repo();
        $now_context_reply = [];
        $now_pic_val=null;
        $now_question_pic_val = null;
        $question_entry = $ra_repo->riseByQuestionId($rk)->question_entry;
        $data_elt['question_id'] = $question_entry->id;
        $question_type_key = $this->questionTypeToKey();
        $apply_entry = $this->apply_entry();
        $modify_entry = $this->modify_entry();
        
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

                        $data_elt['context_choices'] = implode(',',array_keys($rv??[]));

                        $data_elt['reply_context'] = json_encode(array_values($rv??[]));
                    }
                break;
                case 5:
                    if(is_array($rv) && $rv && !$question_entry->real_auth_choice->count()) 
                    {
                        $now_question_pic_val = $this->getPicSavingValByUploaderFiles($rv);
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

        if(count($data_elt)<2 && is_array($rv) && $question_entry->real_auth_choice->count()) {
            
            foreach($rv as $rvk=>$rvv) {

                $choice_entry = $this->getChoiceListByAuthTypeId($question_entry->auth_type_id)->where('id',$rvk)->first();
                
                switch($this->questionTypeToKey($choice_entry->type)) {
                    case 3:
                    case 4:
                        $now_context_reply[$rvk] = $rvv;
                    break;
                    case 5:
                        if($rvv) {
                            $now_pic_val = $this->getPicSavingValByUploaderFiles($rvv);
                            $data_elt['pic_choice_id'] = $rvk;
                        }
                    break;
                }
            }
            
            $data_elt['context_choices'] = implode(',',array_keys($now_context_reply??[]));                     
            $data_elt['reply_context'] = json_encode(array_values($now_context_reply??[]));                                       
        }
        
        if($data_elt) {

            $now_reply_entry = $modify_entry->real_auth_user_reply->where('question_id',$question_entry->id)->first();
            
            if($now_reply_entry ??null)
            {
                $now_reply_entry->choice_id= $data_elt['choice_id']??null;

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
                    $dataPicArr[$data_elt['pic_choice_id']] = $now_pic_val;
                }
                
                if($now_question_pic_val) {
                    $dataQuestionPicArr[$ra_repo->question_entry()->id] = $now_question_pic_val;
                }                      
            }       
        }

        return $data_elt;
    }

    public function getPicSavingValByUploaderFiles($files) 
    {
        $images_ary=array();
       
        foreach ($files as $key => $file) {

            $destinationPath =$this->imageRelativePath. substr($file['name'], 0, 4) . '/' . substr($file['name'], 4, 2) . '/'. substr($file['name'], 6, 2) . '/' . $file['name'];

            $now_images_idx = count($images_ary);
            $images_ary[$now_images_idx]['pic_origin_name']= $file['old_name'];
            $images_ary[$now_images_idx]['pic']= $destinationPath;          
        }

        return $images_ary;
    }
    
    public function getPicSavingValByFiles($files) 
    {
        $images_ary=array();
       
        foreach ($files as $key => $file) {
            $now = Carbon::now()->format('Ymd');
            $input['imagename'] = $now . rand(100000000,999999999) . '.' . $file->getClientOriginalExtension();

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
   
    public function getApplyByAuthTypeId($auth_type_id) 
    {
        $apply_entry = null;
        $user = $this->user();
        switch($auth_type_id) {
            case 1:
                $apply_entry = $user->self_auth_apply;
            break;
            case 2:
                $apply_entry = $user->beauty_auth_apply;
            break;
            case 3:
                $apply_entry = $user->famous_auth_apply;
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
    
        $reply_list = $this->user()->real_auth_user_modify()->with('real_auth_user_reply')->whereHas('real_auth_user_reply',function($q){$q->whereHas('real_auth_user_reply_pic');})->get()->pluck('real_auth_user_reply')->flatten();

        if($reply_list->count()) {
            foreach($reply_list as $reply_entry) {
                
                $now_reply_pic_query = $reply_entry->real_auth_user_reply_pic()->where('pic',$pic);
                if((clone $now_reply_pic_query)->count())
                    $now_rs = $now_reply_pic_query->delete();
                else continue;
                
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

    public function passGroup() 
    {
        return $this->real_auth_service()->group_entry()->real_auth_user_group->where('user_id',$this->user()->id)->first()->update(['is_pass'=>1]);        
    }

    public function getAuthStatusByAuthTypeId($auth_type_id) 
    {
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

    public function isPassedByAuthTypeId($auth_type_id) 
    {
        
        $status = $this->getAuthStatusByAuthTypeId($auth_type_id);        
        
        return $status==1;
    }    
    
    
    public function isCancelPassedByAuthTypeId($auth_type_id) 
    {
        
        $status = $this->getAuthStatusByAuthTypeId($auth_type_id);        
        $apply_entry = $this->getApplyByAuthTypeId($auth_type_id);
        return $status==2 && $apply_entry;
    }      
}
