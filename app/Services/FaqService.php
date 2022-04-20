<?php
namespace App\Services;
use App\Models\User;
use App\Models\FaqGroup;
use App\Models\FaqGroupActLog;
use App\Models\FaqQuestion;
use App\Models\FaqChoice;
use App\Models\FaqUserGroup;
use App\Models\FaqUserReply;
use App\Models\FaqSetting;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Log;

class FaqService {
    protected $_rised_from = '';
    protected $_error_msg = '';
    public function __construct() {
        $this->init();
        $this->question_type_list = collect(['是非','單選','多選','問答']);
        $this->group_target_code_list = collect(['1_1'=>'male_vip_faq','1_0'=>'male_faq','2_-1'=>'female_faq']);
        $this->default_count_down_time = 30;
    } 

    public function init() {
        $this->group_entry = new FaqGroup;
        $this->question_entry = new FaqQuestion;
        $this->choice_entry = new FaqChoice;
        $this->group_act_log_entry = new FaqGroupActLog;
        $this->setting_entry = new FaqSetting;
        $this->group_list = null;
        $this->question_list = null;
        $this->choice_list = null;
        $this->rised_from('');
        $this->error_msg('');
        return $this;
    }
    
    public function group_entry($init=false) {
        if($init) {
            $this->init();
        }
        
        if($this->rised_from()=='choice' && ($this->choice_entry->id??null) ) {
            $this->question_entry = $this->choice_entry()->faq_question??new FaqQuestion;
            $this->group_entry = $this->question_entry()->faq_group??new FaqGroup;
        } 

        if($this->rised_from()=='question' && ($this->question_entry->id??null) ) {
            $this->group_entry = $this->question_entry()->faq_group??new FaqGroup;
        }         
        
        return $this->group_entry;
    }
    
    public function question_entry($init=false) {
        if($init) {
            $this->question_entry = new FaqQuestion;
            $this->choice_list = null;
        }        
        
        if($this->rised_from()=='choice' && ($this->choice_entry->id??null) ) {
            $this->question_entry = $this->choice_entry()->faq_question??new FaqQuestion;
        }
        
        return $this->question_entry;
    }

    public function choice_entry($init=false) {
        if($init) {
            $this->choice_entry = new FaqChoice;
        }      
        
        return $this->choice_entry;
    } 
    
    public function setting_entry() {
        return $this->setting_entry;
    }
    
    public function group_act_log_entry($init=false) {
        if($init) {
            $this->group_act_log_entry = new FaqGroupActLog;
        }          
        return $this->group_act_log_entry;
    }   

    public function group_list() {
        return $this->group_list;
    }
    
    public function question_list() {
        return $this->question_list;
    }
    
    public function choice_list() {
        return $this->choice_list;
    }
    
    public function question_type_list() {
        return $this->question_type_list;
    }
    
    public function group_target_code_list() {
        return $this->group_target_code_list;
    }
    
    public function default_count_down_time() {
        return $this->default_count_down_time;
    }

    public function rised_from($from=null) {
        if($from!==null) $this->_rised_from = $from;
        return $this->_rised_from;
    }
    
    public function error_msg($msg=null) {
        if($msg!==null) $this->_error_msg = $msg;
        return $this->_error_msg;        
    }

    public function riseByGroupId($id) {
        $this->init()->group_entry = $this->group_entry->findOrNew($id);
        $this->rised_from('group');
        return $this;
    }
    
    public function riseByQuestionId($id) {
        $this->init()->question_entry = $this->question_entry->findOrNew($id);
        $this->rised_from('question');
        return $this;
    }

    public function riseByChoiceId($id) {
        $this->init()->choice_entry = $this->choice_entry->findOrNew($id);
        $this->rised_from('choice');
        return $this;
    } 
 
    public function slotByGroupId($id) {
         if($this->rised_from()!='group' || $this->group_entry()->id!=$id )
            $this->group_entry = $this->group_entry->findOrNew($id);
        return $this;
    }
    
    public function slotByQuestionId($id) {
        if($this->rised_from()!='question' || $this->question_entry()->id!=$id)
            $this->question_entry = $this->question_entry->findOrNew($id);
        return $this;
    }

    public function slotByChoiceId($id) {
        if($this->rised_from()!='choice' || $this->choice_entry()->id!=$id)
            $this->choice_entry = $this->choice_entry->findOrNew($id);
        return $this;
    }  

    public function riseByGroupEntry($entry) {
        $this->init()->group = $entry;
        $this->rised_from('group');
        return $this;
    }
    
    public function riseByQuestionEntry($entry) {
        $this->init()->question = $entry;
        $this->rised_from('question');
        return $this;
    }

    public function riseByChoiceEntry($entry) {
        $this->init()->choice_entry = $entry;
        $this->rised_from('choice');
        return $this;
    } 
 
    public function slotByGroupEntry($entry) {
         if($this->rised_from()!='group' || $this->group_entry()->id!=$entry->id )
            $this->group_entry = $entry;
        return $this;
    }
    
    public function slotByQuestionEntry($entry) {
        if($this->rised_from()!='question' || $this->question_entry()->id!=$entry->id)
            $this->question_entry = $entry;
        return $this;
    }

    public function slotByChoiceEntry($entry) {
        if($this->rised_from()!='choice' || $this->choice_entry()->id!=$entry->id)
            $this->choice_entry = $entry;
        return $this;
    }       

    public function fillGroupList($filter_entry=null) {
        $filter_item = null;
        $engroup_filter = null;
        $isvip_filter = null;
        $filter_arr = [];
        if($filter_entry->engroupvip??null) {
            $filter_item = explode('_',$filter_entry->engroupvip);
            $engroup_filter = $filter_item[0]??null;
            $isvip_filter = $filter_item[1]??null;
            
            if($engroup_filter) $filter_arr[] = ['engroup',$engroup_filter];
            if($isvip_filter!==null && $isvip_filter!=-1) $filter_arr[] = ['is_vip',$isvip_filter];
        }
        
        $list_query = $this->group_entry()->orderBy('id');
        
        if($filter_arr) $list_query->where($filter_arr);
        
        $this->group_list = $list_query->get();
        return $this;        
    }

    public function fillQuestionList() {
        $this->question_list = $this->question_entry()->orderBy('group_id')->orderBy('id')->get();
        return $this;
    }

    public function fillChoiceList($question_id=null) {
        $query = $this->choice_entry()->orderBy('id');
        if(!$question_id) $question_id = $this->question_entry()->id;
        if($question_id) $query = $query->where('question_id',$question_id);
        $this->choice_list = $query->get();
        return $this;
    }     
    
    public function saveQuestion($dataEntry,$is_new=false) {
        if($is_new) {
            $question = $this->question_entry(true);
        }
        else {
            if(!$dataEntry->id??null) return false;
            $question = $this->slotByQuestionId($dataEntry->id)->question_entry();
        }
        
        $question->group_id = $dataEntry->group_id;
        $question->question = $dataEntry->question;
        $question->type = $dataEntry->type;
        return $question->save();
    }
    
    public function saveRegularAns($dataEntry) {
        if(!$dataEntry->question_id??null) {
            if($this->question_entry()->id??null)
                $question = $this->question_entry();
            else  return false;
        }
        else $question = $this->slotByQuestionId($dataEntry->question_id)->question_entry();
        $group=$this->group_entry();
        $old_act = $group->act;
        
        if($this->isCustomChoiceByQuEntry($question)) return false;
        if($this->isTafChoiceByQuEntry($question)) $question->answer_bit=$dataEntry->answer;
        else if($this->isTxtAnsByQuEntry($question)) $question->answer_context=$dataEntry->answer;
        else return false;
        $rs = $question->save();
        $group->renewHasAnswer();
        if($rs && ($question->answer_bit!==null || $question->answer_context) ) 
        {
            if($group->act && (!$old_act || !$group->act_at)) {
                $group->act_at=Carbon::now();
            }
        }
        return $rs;
    }    

    public function addQuestion($dataEntry) {
        return $this->saveQuestion($dataEntry,true);
    }
    
    public function delQuestion() {
        if(!$this->isQuestionDeletableByEntry($this->question_entry()))  return false;
        $now_group_entry = $this->question_entry()->faq_group;
        $question_id = $this->question_entry()->id;
        $rs = $this->question_entry()->delete();
        
        if($rs) {
            $now_group_entry->renewHasAnswer();

            $this->choice_entry()->where('question_id',$question_id)->delete();
        }
        
        return $rs;
    }      
    
    public function saveGroup($dataEntry,$is_new=false) {

        if($is_new) {
            $group = $this->group_entry(true);
        }
        else {
            if(!$dataEntry->id??null) return false;
            $group = $this->slotByGroupId($dataEntry->id)->group_entry();
        }
        $old_act = $group->act;
        if(($dataEntry->act??0) && !$group->isRealHasAnswer() ) {
            return false;
        }
        
        $engroup_vip_arr = explode('_',$dataEntry->engroup_vip??'');
        
        $group->name = $dataEntry->name;
        $group->engroup = $engroup_vip_arr[0]??null;
        $group->is_vip = $engroup_vip_arr[1]??null;
        $group->act= $dataEntry->act??0;
        if(!$is_new) {        
            if($group->isRealHasAnswer() && !$old_act && $group->act) $group->act_at= Carbon::now();
            else if(!$group->act) $group->act_at= null;
        }
        $group->faq_login_times = $dataEntry->faq_login_times;
        $rs = $group->save();
        if($rs) {
            if($old_act!=$group->act) {
                $this->logGroupAct($group->act,[$group->id]);
            }
            $group->renewHasAnswer();
            $this->slotByGroupEntry($group);
        }
        return $rs;
    } 
   
    
    public function addGroup($dataEntry) {
        return $this->saveGroup($dataEntry,true);
    }
    
    public function logGroupAct($act,$group_id_arr) {
        $group_act_log_entry = $this->group_act_log_entry(true);
        $add_log_arr = [];
        foreach($group_id_arr as $k=>$v) {
            $add_log_arr[] = ['group_id'=>$v,'act'=>$act,'created_at'=>Carbon::now()];
        }
        
        return $group_act_log_entry->insert($add_log_arr);
        
    }
    
    public function delGroup() {
        $question_id_list = $this->group_entry()->faq_question->pluck('id');
        $rs = $this->group_entry()->delete();
        if($rs && count($question_id_list)) {
            $this->choice_entry()->whereIn('question_id',$question_id_list)->delete();
            $this->question_entry()->whereIn('id',$question_id_list)->delete();
        }
        
        return $rs;
    }

    public function saveChoice($dataEntry,$is_new=false) {
        $group = $this->group_entry();
        $old_act = $group->act;  
        if($is_new) {
            $choice = $this->choice_entry(true);
            $choice_arr = $this->fillChoiceList()->choice_list()->pluck('name')->all();
            $answer_arr = $this->choice_list()->where('is_answer',1)->pluck('name')->all();
        }
        else {
            if(!$dataEntry->id??null) return false;
            $choice = $this->slotByChoiceId($dataEntry->id)->choice_entry();
            $choice_arr = $this->fillChoiceList()->choice_list()->where('id','<>',$choice->id)->pluck('name')->all();
            $answer_arr = $this->choice_list()->where('is_answer',1)->where('id','<>',$choice->id)->pluck('name')->all();
        }
        
        if(in_array($dataEntry->name,$choice_arr)) {
            $this->error_msg('duplicate_name');
            return false;
        } 
        
        if(($dataEntry->is_answer??0) && $this->isAnswerQuotaFull($choice->id)) {
            return false;
        }        
        
        if($dataEntry->question_id??null)
            $choice->question_id = $dataEntry->question_id;
        else $choice->question_id = $this->question_entry()->id;
        
        $choice->name = $dataEntry->name;
        $choice->is_answer = $dataEntry->is_answer??0;
        $rs = $choice->save();
        
        if($rs ) {
            if($choice->is_answer) {
                if($group->act && (!$old_act || !$group->act_at) ) 
                {
                    $group->act_at=Carbon::now();
                }  

                $group->has_answer = 1;
                $group->save();                
            }

            $group->renewHasAnswer();

        }
        
        return $rs;
    }

    public function addChoice($dataEntry) {
        
        return $this->saveChoice($dataEntry,true);
    }
    
    public function delChoice() {
        $now_group_entry = $this->choice_entry()->faq_question->faq_group;
        $rs = $this->choice_entry()->delete();
        if($rs) {
            $now_group_entry->renewHasAnswer();
        }
        return $rs;
    } 

    public function saveSetting($dataEntry) {
        $setting = null;
        $rs = null;
        if($dataEntry->count_down_time??null) {
            $setting = $this->getSettingEntryByName('count_down_time');
            if($setting->value!= intval($dataEntry->count_down_time)) {
                if(!$setting->name) $setting->name='count_down_time';
                $setting->value= intval($dataEntry->count_down_time);
                $rs = $setting->save();
            }
        }
        
        return $rs;
    }
    

    public function getAnsChoiceEntrys() {
        return $this->question_entry()->faq_choice->where('is_answer',1);
    }
    
    public function getGroupListByEngroupVip($engroup_vip=null) {
        if(!$engroup_vip) return $this->group_list();
        $now_group_list = $this->group_list();
        $argv = explode('_',$engroup_vip);
        $now_group_list = $now_group_list->where('engroup',$argv[0]);
        if(($argv[1]??-1)!=-1) {
            $now_group_list = $now_group_list->where('is_vip',$argv[1]);
        }
        return $now_group_list;
    }
    
    public function isAnswerQuotaFull($check_ans_id=null) {
        $answer_entrys = $this->getAnsChoiceEntrys();
        if($check_ans_id) $answer_entrys = $answer_entrys->where('id','<>',$check_ans_id);

        $question_type = $this->question_entry()->type;
        switch($this->questionTypeToKey($question_type)) {
            case '1':
                return count($answer_entrys)>=1;
            break;
        }        
    }

    public function isGroupMatchEngroupVip($group_entry,$engroup,$is_vip=-1) {
       $rs = ($group_entry->engroup??null) == $engroup ;
       if($is_vip==-1 || $is_vip===null) return $rs;
       else return ($rs && ($group_entry->is_vip??null)==$is_vip);
    }

    public function isQuestionDeletableByEntry($question_entry) {
        return !($question_entry->faq_user_reply->where('is_pass',1)->count() && $question_entry->faq_choice->count());
    } 

    public function isQuestionDelPassedAlertByEntry($question_entry) {
        return $question_entry->faq_user_reply->where('is_pass',1)->count() && ($question_entry->answer_bit!==null || $question_entry->answer_context);
    }      
    
    public function isQuestionMismatchAnsByEntry($question_entry) {
        $mismatch_ans = false;
        switch($this->questionTypeToKey($question_entry->type)) {
            case '1':
                if(($question_entry->faq_choice??null) && $question_entry->faq_choice->where('is_answer',1)->count()>1) $mismatch_ans = true;
            break;
            case '2':
                if(($question_entry->faq_choice??null) && $question_entry->faq_choice->where('is_answer',1)->count()<=1) $mismatch_ans = true;
            break;           
        }  

        return $mismatch_ans;
    }    
    
    public function isQuestionNoAnsByEntry($question_entry) {
        $is_no_ans = false;
        switch($this->questionTypeToKey($question_entry->type)) {
            case '0':
                if($question_entry->answer_bit===null || $question_entry->answer_bit>1 || $question_entry->answer_bit<0) {
                    $is_no_ans = true;
                }
            break;
            case '1':
            case '2':
                if(!($question_entry->faq_choice??null) || $question_entry->faq_choice->where('is_answer',1)->count()==0) $is_no_ans = true;
            break;
            case '3':
                if($question_entry->answer_context===null) {
                    $is_no_ans = true;
                }
            break;            
        }  

        return $is_no_ans;
    }    
    
    public function isCustomChoiceByQuEntry($question_entry) {
        $type_key = $this->questionTypeToKey($question_entry->type);
        if($type_key==1  || $type_key==2) return true;
        else return false;
    }
    
    public function isTafChoiceByQuEntry($question_entry) {
        $type_key = $this->questionTypeToKey($question_entry->type);
        if($type_key===0) return true;
        else return false;        
    }
    
    public function isTxtAnsByQuEntry($question_entry) {
        $type_key = $this->questionTypeToKey($question_entry->type);
        if($type_key===3) return true;
        else return false;        
    } 
     

    public function getChoiceLayout() {
        $question = $this->question_entry();
        $arr = [];
        if($this->isCustomChoiceByQuEntry($question)) {
            $arr = $this->question_entry()->faq_choice->pluck('name')->all();
        }
        else if($this->isTafChoiceByQuEntry($question)) {

            $arr = ['是','否'];
        }    
        return '<ul><li>'.implode('</li><li>',$arr).'</li></ul>';
    }
    
    public function getAnswerLayout() {
        $question = $this->question_entry();
        $arr = [];
        if($this->isCustomChoiceByQuEntry($question)) {
            $arr = $this->getAnsChoiceEntrys()->pluck('name')->all();
        }
        else if($this->isTafChoiceByQuEntry($question)) {
            
            if($question->answer_bit!==null) {
                $arr = [$question->answer_bit?'是':'否'];
            }
        }
        else if($this->isTxtAnsByQuEntry($question)) {
            $arr = [$question->answer_context];
        }         
        
        
        return '<ul><li>'.implode('</li><li>',$arr).'</li></ul>';
    } 
    
    public function getQuertionTitleLayout() {
        return $this->question_entry()->question??'(題目內容尚未設定)';
    }

    public function getFormChkEditAssign($code,$orgi_data=null,$new_data=null) {
        if($new_data &&  $code==$new_data) return 'checked';
        if($code==$orgi_data) return 'checked';
        return;
    }
    
    public function getFormDdlEditAssign($code,$orgi_data=null,$new_data=null) {
        if($new_data &&  $code==$new_data) return 'selected';
        if($code==$orgi_data) return 'selected';
        return;
    }    
    
    public function getFormValEditAssign($orgi_data,$new_data=null) {
        if($new_data!==null) return $new_data;
        if($orgi_data!==null) return $orgi_data ;
    }    
    
    public function getFormColByEntry($entry,$col) {
        switch($col) {
            case 'engroup_vip':
                return ($entry['engroup']??0).'_'.($entry['is_vip']??-1);
            break;
            default:
                return $entry[$col];
            break;
        }
    }
    
    public function getEngroupVipWord($code=null) {
        $assoc = ['1_1'=>'男VIP','1_0'=>'男普通','2_-1'=>'女會員'];
        switch($code) {
            case '1_1':
            case '1_0':          
            case '2_-1':
                return $assoc[$code]; 
            break;
            case null:
            case '':
                return $assoc;
            break;
            default:
                return '';
            break;
        }
    }
    
    public function getEngroupVipCodeByEntry($entry) {
        $engroup = $entry->engroup??null;
        $is_vip = null;
        if($engroup==2) {
            $is_vip = -1;
        }
        else $is_vip = $entry->is_vip??null;
        
        return $engroup.'_'.$is_vip;
    }
    
    public function getIsAnswerWord($code) {
        return ($code??0)?'是':'否';
    }

    public function getGroupActAtWordByEntry($group_entry) {
       $word = '';
       if($group_entry->isRealHasAnswer()) {
           
           if($group_entry->act) {
           
               if($group_entry->act_at??null) {
                   $word = $group_entry->act_at;
               }
           }
           else  {
               $word = '尚未啟用';
           }
       }
       else {
           $act_date_str = '';
           if($group_entry->act && $group_entry->act_at??null) {
               $act_date_str=substr($group_entry->act_at,0,10);
                $act_date_str.='已啟用，';
           }
           if($act_date_str)
                $word = $act_date_str."\n但因無答案\n啟用無效";
            else $word = '尚無答案';
       }
      
       return $word;
    }

    public function getSettingEntryByName($name) {
        $this->setting_entry = $this->setting_entry()->where('name',$name)->firstOrNew();
        return $this->setting_entry;
    }
    
    public function getSettingValueByName($name) {
        return $this->getSettingEntryByName($name)->value;
    }
    
    public function getCountDownTime() {
        return $this->getSettingValueByName('count_down_time')??$this->default_count_down_time();
    }
       
    public function questionTypeToKey($question_type=null) 
    {
        if(!$question_type && $this->question_entry()->id)
            $question_type = $this->question_entry()->type;
        return $this->question_type_list()->search($question_type);
    }    

    public function getIsAnswerOffEltAttr($check_ans_id=null) {
        return $this->isAnswerQuotaFull($check_ans_id)?'disabled':'';
    } 

    public function getIsAnswerOffEltExplain($check_ans_id=null) {
            return $this->isAnswerQuotaFull($check_ans_id)?'( 已有其他選項被勾選為正解，無法再勾選本選項為單選題的正解 )':'';
    }       
    
    public function getEngroupVipQueryString($prefix_str=null,$dataEntry=null) {
        if(!$dataEntry) $dataEntry=$this->group_entry();
        if(!$dataEntry->engroupvip && $dataEntry->engroup===null) return '';
        $param_str = '';
        if($dataEntry->engroupvip) $param_str = $dataEntry->engroupvip;
        else if($dataEntry->engroup) $param_str = $dataEntry->engroup.'_'.($dataEntry->is_vip===null?'-1':$dataEntry->is_vip);
        return $prefix_str.'engroupvip='.$param_str;
    }
    
    public function getQuAnsStateClassByEntry($question_entry) {
        $class_str = '';
        if($this->isQuestionNoAnsByEntry($question_entry))
            $class_str.=' faq_qu_no_ans ';
        
        else if($this->isQuestionMismatchAnsByEntry($question_entry))
            $class_str.=' faq_qu_mismatch_ans ';        
    
        return $class_str;
    }
    
}


