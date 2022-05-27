<?php
namespace App\Repositories;
use App\Models\RealAuthType;
use App\Models\RealAuthQuestion;
use App\Models\RealAuthChoice;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Log;

class RealAuthRepository {
    protected $_rised_from = '';
    protected $_error_msg = '';
    public function __construct() {
        $this->init();
        $this->question_type_list = collect(['是非','單選','多選','問答','簡答','上傳']);
    } 

    public function init() {
        $this->type_entry = new RealAuthType;
        $this->question_entry = new RealAuthQuestion;
        $this->choice_entry = new RealAuthChoice;
        $this->type_list = null;
        $this->question_list = null;
        $this->choice_list = null;
        $this->rised_from('');
        $this->error_msg('');
        return $this;
    }
    
    public function type_entry($init=false) {
        if($init) {
            $this->init();
        }
        
        if($this->rised_from()=='choice' && ($this->choice_entry->id??null) ) {
            $this->question_entry = $this->choice_entry()->faq_question??new RealAuthQuestion;
            $this->type_entry = $this->question_entry()->real_auth_type??new RealAuthType;
        } 

        if($this->rised_from()=='question' && ($this->question_entry->id??null) ) {
            $this->type_entry = $this->question_entry()->real_auth_type??new RealAuthType;
        }         
        
        return $this->type_entry;
    }
    
    public function question_entry($init=false) {
        if($init) {
            $this->question_entry = new RealAuthQuestion;
            $this->choice_list = null;
        }        
        
        if($this->rised_from()=='choice' && ($this->choice_entry->id??null) ) {
            $this->question_entry = $this->choice_entry()->faq_question??new RealAuthQuestion;
        }
        
        return $this->question_entry;
    }

    public function choice_entry($init=false) {
        if($init) {
            $this->choice_entry = new RealAuthChoice;
        }      
        
        return $this->choice_entry;
    } 

    public function type_list() {
        return $this->type_list;
    }
    
    public function question_list() {
        return $this->question_list;
    }
    
    public function choice_list($value_or_reset=false) {
        if($value_or_reset===true) {
            $this->choice_list = null;  
        }
        else if($value_or_reset!==false) {
            $this->choice_list = $value_or_reset;
        }     
        
        return $this->choice_list;
    }  
   
    public function question_type_list() {
        return $this->question_type_list;
    }

    public function rised_from($from=null) {
        if($from!==null) $this->_rised_from = $from;
        return $this->_rised_from;
    }
    
    public function error_msg($msg=null) {
        if($msg!==null) $this->_error_msg = $msg;
        return $this->_error_msg;        
    }

    public function riseByTypeId($id) {
        $this->init()->type_entry = $this->type_entry->findOrNew($id);
        $this->rised_from('type');
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
 
    public function slotByTypeId($id) {
         if($this->rised_from()!='type' || $this->type_entry()->id!=$id )
            $this->type_entry = $this->type_entry->findOrNew($id);
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

    public function riseByTypeEntry($entry) {
        $this->init()->type_entry = $entry;
        $this->rised_from('type');
        return $this;
    }
    
    public function riseByQuestionEntry($entry) {
        $this->init()->question_entry = $entry;
        $this->rised_from('question');
        return $this;
    }

    public function riseByChoiceEntry($entry) {
        $this->init()->choice_entry = $entry;
        $this->rised_from('choice');
        return $this;
    } 
 
    public function slotByTypeEntry($entry) {
         if($this->rised_from()!='type' || $this->type_entry()->id!=$entry->id )
            $this->type_entry = $entry;
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
 
    
    public function fillTypeList() {
        $query = $this->type_entry()->orderBy('id');
        $this->type_list = $query->get();
        return $this;
    }
       
    public function fillQuestionList($filter_entry=null) {
        $query = $this->question_entry()->orderBy('id');
        $auth_type_id = $filter_entry->auth_type_id;
        if(!$auth_type_id) $auth_type_id = $this->type_entry()->id;
        if($auth_type_id) $query = $query->where('auth_type_id',$auth_type_id);
        $this->question_list = $query->get();
        return $this;
    }

    public function fillChoiceList($question_id=null) {
        $query = $this->choice_entry()->orderBy('id');
        if(!$question_id) $question_id = $this->question_entry()->id;
        if($question_id) $query = $query->where('question_id',$question_id);
        $this->choice_list = $query->get();
        return $this;
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
        if($type_key==3 || $type_key==4) return true;
        else return false;        
    } 
    
    public function getQuestionListByAuthType($auth_type) {
        $filter_array = ['auth_type_id'=>$auth_type];
        return $this->fillQuestionList((object)$filter_array)->question_list();
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

    public function getQuestionTitleLayout() {
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
            case 'entype_vip':
                return ($entry['engroup']??0).'_'.($entry['is_vip']??-1);
            break;
            default:
                return $entry[$col];
            break;
        }
    }
 
    public function questionTypeToKey($question_type=null) 
    {
        if(!$question_type && $this->question_entry()->id)
            $question_type = $this->question_entry()->type;
        return $this->question_type_list()->search($question_type);
    } 

}


