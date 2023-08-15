<?php
namespace App\Services;
use App\Models\FaqUserGroup;
use App\Models\FaqUserReply;
use App\Services\UserService;
use App\Services\FaqService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Log;

class FaqUserService {
    public function __construct(
        UserService $userService
        ,FaqService $faqService
    ) {
        $this->faq_service = $faqService;
        $this->riseByUserService($userService);        
        $this->popup_question_list = null;
    } 
    
    public function riseByUserService(UserService $userService) {
        $this->user_service = $userService;
        $this->user = $userService->model??null;
        if(!$this->user->id??null) {
            $this->user = auth()->user();
        }
        return $this;
    }
    
    public function riseByUserEntry($user_entry) {
        $this->riseByUserService($this->user_service->riseByUserEntry($user_entry));

        return $this;    
    }
    
    public function user() {
        return $this->user;
    }

    public function user_service() {
        return $this->user_service;
    }
    
    public function faq_service() {
        return $this->faq_service;
    } 

    public function popup_question_list($value_or_reset=false) {
        if($value_or_reset===true) {
            $this->popup_question_list = null;  
        }
        else if($value_or_reset!==false) {
            $this->popup_question_list = $value_or_reset;
        }
        
        return $this->popup_question_list;
    }

    public function getBaseGroupWhereQuery($query) {

        $gEntry_where_arr[] = ['engroup',$this->user()->engroup];        
        if($this->user()->engroup!=2)
        {
            $gEntry_where_arr[] = ['is_vip',$this->user()->isVipOrIsVvip()];
        }
        $gEntry_where_arr[] = ['act',1];
        $gEntry_where_arr[] = ['has_answer',1];
        $gEntry_where_arr[] = ['act_at','!=',0];    
        return $query->where($gEntry_where_arr)->whereNotNull('act_at');
    }
    
    public function getUserMatchGroupList() {
        $faq_group_query = $this->faq_service()->group_entry();
        $all_group_list = $this->getBaseGroupWhereQuery($faq_group_query)->get();
        $exist_user_gid_list = $this->user()->faq_user_group->pluck('group_id');
        $match_group_arr = [];
        
        foreach($all_group_list as $group) {
            if($exist_user_gid_list->search($group->id)!==false) {
                $match_group_arr[] = $group;
                continue;
            }
            $now_faq_login_num = $this->user()->log_user_login()->where('created_at','>=',$group->act_at)->count();
            if($now_faq_login_num>=$group->faq_login_times) {
                $match_group_arr[] = $group;
            }
        }

        return collect($match_group_arr);
    }
    
    public function getPopupUserGroupList($session_renew=false) {
        $all_gid_list = $this->getUserMatchGroupList()->pluck('id');

        $now_service = $this;
        $user_gid_list = $this->user()
                            ->faq_user_group()
                            ->whereHas('faq_group',function($q) use ($now_service){
                                $now_service->getBaseGroupWhereQuery($q);
                            })
                            ->pluck('group_id');

        if(count($user_gid_list) < count($all_gid_list)) {
            $diff_gid_list = $all_gid_list->diff($user_gid_list)->unique();
            $add_user_group_arr = [];
            foreach($diff_gid_list as $gid ) {
                $add_user_group_arr[] = ['group_id'=>$gid];
            }

            $this->user()->faq_user_group()->createMany($add_user_group_arr);
        }

        $rough_user_glist = $this->user()->faq_user_group()->whereIn('group_id',$all_gid_list)->get();

        $passed_user_glist = $this->user()->faq_user_group->where('is_pass',1);

        $replyed_user_glist = $this->user()->faq_user_group->whereIn('id',$this->getReplyedRecord()); 
        
        if($session_renew==false) {
            $passed_user_glist = $passed_user_glist->diff($replyed_user_glist);
            return $rough_user_glist->diff($passed_user_glist);
        }        
        
        return $rough_user_glist->diff($passed_user_glist)->diff($replyed_user_glist);
    }

    public function getPopupQuestionList() {
        if($this->popup_question_list()) return $this->popup_question_list();
        $question_list_rs = null;
        $replyed_record = $this->getReplyedRecord();
        $wrong_reply_record = $this->getWrongReplyAnsWadRecord();
        $all_pass_flag = $this->getTheAllPassFlag();

        if($all_pass_flag) {
            $question_list_rs = [];
        }
     
        if($question_list_rs===null && count($replyed_record)) 
        {
            $pick_qid_arr = [];
            foreach($replyed_record as $k=>$v) {
                $pick_qid_arr[] = $this->faq_service()->question_entry()->where('id',$k)->first();
            }

            if($pick_qid_arr) {
                $question_list_rs = collect($pick_qid_arr);
            }
        }
        
        if($question_list_rs===null) 
        {
            $popup_user_group_list = $this->getPopupUserGroupList();
            $pick_qid_arr = [];

            foreach($popup_user_group_list as $gEntry) {
                
                $now_question_query = $gEntry->faq_group->faq_question();
                
                $now_all_qid_list = $now_question_query->pluck('id');

                $now_no_ans_taf_qid_list = (clone $now_question_query)->where('type','是非')->whereNull('answer_bit')->pluck('id');

                $now_no_ans_text_qid_list = (clone $now_question_query)->where('type','問答')
                                            ->where(function($q){
                                                $q->whereNull('answer_context')->orWhere('answer_context','');
                                            })
                                            ->pluck('id'); 

                $now_no_ans_choice_qid_list = (clone $now_question_query)->whereIn('type',['單選','多選'])
                                                ->doesntHave('faq_choice_answer')
                                                ->pluck('id'); 

                $now_all_qid_list = $now_all_qid_list->diff($now_no_ans_taf_qid_list)
                                        ->diff($now_no_ans_text_qid_list)
                                        ->diff($now_no_ans_choice_qid_list);

                $now_passed_count = $this->user()->faq_user_reply()->whereIn('question_id',$now_all_qid_list)
                                    ->where('is_pass',1)->count();
                if($now_passed_count) {
                    $gEntry->is_pass=1;
                    $gEntry->save();
                    continue;
                }
                
                $pick_entry = null;
                if(count($now_all_qid_list)) {
                    $now_replyed_qnum = $this->user()->faq_user_reply()->whereIn('question_id',$now_all_qid_list)->Distinct('question_id')->count();
                    if($now_replyed_qnum>=count($now_all_qid_list)) {
                        $pick_qid = $now_all_qid_list->random();
                        $pick_entry = $gEntry->faq_group->faq_question->where('id',$pick_qid)->first();
                    }
                    else {
                        $now_replyed_qid_list = $this->user()->faq_user_reply()->whereIn('question_id',$now_all_qid_list)->pluck('question_id');
                        $pick_entry = $now_question_query->whereNotIn('id',$now_replyed_qid_list)->whereIn('id',$now_all_qid_list)->inRandomorder()->take(1)->first();
                    }
                
                }
                
                if($pick_entry) {
                    $pick_qid_arr[] = $pick_entry;
                    $this->recordQuestionByEntry($pick_entry);
                    $pick_entry = null;
                }
            }
            $question_list_rs = collect($pick_qid_arr??[]);
        }
        $this->popup_question_list($question_list_rs);
        return $this->popup_question_list();
    }
    
    public function questionTypeToKey($question_type=null) {
        return $this->faq_service()->questionTypeToKey($question_type);
    }
    
    public function saveReply($dataEntry) {
        $dataArr['question_id'] = $dataEntry->question_id??null;
        $reply = $dataEntry->reply??null;
        switch($this->questionTypeToKey()) {
            case 0:
                $dataArr['reply_bit'] = $reply;
            break;
            case 1:
                $dataArr['choice_id'] = $reply;
            break;
            case 2:
                $dataArr['reply_choices'] = implode(',',$reply??[]);
            break;
            case 3:
                $dataArr['reply_context'] = $reply;
            break;        
        }
        
        return $this->user()->faq_user_reply()->create($dataArr);
    }

    public function checkAnswer($dataEntry) {
        $question_id = $dataEntry->question_id;
        $reply = $dataEntry->reply??'';
        $request = $dataEntry;
        $fuService = $this;
        $faqService = $fuService->faq_service()->riseByQuestionId($question_id);
    
        $now_reply_entry = $fuService->saveReply($request);
        
        if(!$question_id) {
           $error_code = 'no_question_id';
           $now_reply_entry->error_code = $error_code;
           $now_reply_entry->save();
           return ['error'=>$error_code];        
        }
        
        $popupQuestionList = $fuService->getPopupQuestionList();
        $popupUserGroupList = $fuService->getPopupUserGroupList();
        $userQuestionList = $faqService->question_entry()->whereIn('group_id',$popupUserGroupList->pluck('group_id'))->get();

        $user_reply_data = ['question_id'=>$question_id];
        $ans_rs_data = [];
        
        if($userQuestionList->where('id',$question_id)->count()==0) {
            if($this->user()->faq_user_reply->where('question_id',$question_id)->where('is_pass',1)->count())
            {
                $ans_rs_data['success'] = 1;
                $now_reply_entry->is_pass=1;
            }
            else {
                $this->recordReply($reply);
                $error_code = 'not_user_question';
                $now_reply_entry->error_code = $error_code;
                $now_reply_entry->save();                
                return ['error'=>$error_code];
            }
        }
        else {
            switch($faqService->questionTypeToKey()) {
                case 0:
                   if(!!$reply===!!($faqService->question_entry()->answer_bit??null)) {
                       $ans_rs_data['success'] = 1;
                       $now_reply_entry->is_pass=1;
                   }
                   else {
                       if($faqService->question_entry()->answer_bit!==null) {
                           $ans_rs_data['wrong'] = $faqService->question_entry()->answer_bit?'是':'否';
                       }
                       else {
                           $ans_rs_data['error'] = 'no_answer_setting';
                       }
                   }
                break;
                case 1:
                    $choice_list = $faqService->fillChoiceList()->choice_list();
                    $answer_list = $choice_list->where('is_answer',1);
                    if($answer_list->where('id',$reply)->count()) {
                        $fuService->user()->faq_user_reply();
                        $ans_rs_data['success'] = 1;
                        $now_reply_entry->is_pass=1;
                    }
                    else {
                        if(!count($answer_list)) {
                            $ans_rs_data['error'] = 'no_answer_setting';
                        }
                        else {
                             $ans_rs_data['wrong'] = $answer_list->implode('name','或');
                        }
                    }
                break;
                case 2:
                    $check_reply = is_countable($reply)?$reply:[];
                    $answer_list = $faqService->fillChoiceList()->choice_list()->where('is_answer',1);

                    if(!count($answer_list)) {
                        $ans_rs_data['error'] = 'no_answer_setting';
                    }               
                    else if(count($answer_list)==count($check_reply) && !count($answer_list->pluck('id')->diff($check_reply)->all())) {    
                        $ans_rs_data['success'] = 1;
                        $now_reply_entry->is_pass=1;
                    }
                    else {
                        $ans_rs_data['wrong'] = $answer_list->implode('name', '，');
                    }
                break;
                case 3:
                    $answer_context = $faqService->question_entry()->answer_context;
                    if(!$answer_context) {
                        $ans_rs_data['error'] = 'no_answer_setting';
                    }
                    else {
                        similar_text($answer_context, $reply, $percent);
                        if($percent>=70) {
                            $ans_rs_data['success'] = 1;
                            $now_reply_entry->is_pass=1;
                        }
                        else {
                            $ans_rs_data['text_wrong'] = $answer_context;
                        }
                    }
                break;
            }  
            
        }

        if($now_reply_entry->is_pass==1) {
            $now_reply_entry->save();
            $fuService->passGroup();
        }
        
        if($ans_rs_data['error']??null) {
            $now_reply_entry->error_code = $ans_rs_data['error'];
            $now_reply_entry->save();
        }
        $this->recordReply($reply);
        if(($ans_rs_data['wrong']??null)!==null) $this->recordWrongReplyAnsWad($ans_rs_data['wrong']);
        else if(($ans_rs_data['text_wrong']??null)!==null) $this->recordWrongReplyAnsWad($ans_rs_data['text_wrong']);

        $nowWrongReplyRecord = $this->getWrongReplyAnsWadRecord();
        $nowReplyedRecord = $this->getReplyedRecord(); 
        $countReplyedNum = 0;
        $ans_rs_data['nowReplyedRecord'] = $nowReplyedRecord;
        foreach($nowReplyedRecord as $v) {
            if($v!==null) $countReplyedNum++;
        }
        
        if(count($popupQuestionList)==$countReplyedNum) {           
            
            if(count($nowWrongReplyRecord)==0) {
                $this->setTheAllPassFlag();
                $ans_rs_data['all_pass'] = 1;
            }
            else {
                $this->setCountDownStartTime();
                $ans_rs_data['all_finished'] = 1;
            }
        }
        $ans_rs_data['countReplyedNum'] = $countReplyedNum;
        $ans_rs_data['popupQuestionListNum'] = count($popupQuestionList);
        return $ans_rs_data;
    }
    
    public function passGroup() {
        return $this->faq_service()->group_entry()->faq_user_group->where('user_id',$this->user()->id)->first()->update(['is_pass'=>1]);        
    }
    
    public function recordQuestionByEntry($question_entry) {
        if(!($this->getReplyedRecord()[$question_entry->id]??null)) {
            session()->put('replyed_record.'.$question_entry->id,null); 
        }        
        return $this;        
    }
    
    public function recordReply($reply=null) {
        if($reply!==null && !is_countable($reply)) $reply = (string) $reply;
        if(!($this->getReplyedRecord()[$this->faq_service()->question_entry()->id]??null)) {
            session()->put('replyed_record.'.$this->faq_service()->question_entry()->id,$reply); 
        }    
        return $this;
    } 
    
    public function getReplyedRecord() {
        return session()->get('replyed_record')??[];
    }
    
    public function recordWrongReplyAnsWad($ans_text=null) {
        if(!session()->get('wrong_reply_record.'.$this->faq_service()->question_entry()->id)) {
            session()->put('wrong_reply_record.'.$this->faq_service()->question_entry()->id,$ans_text);
        }
        return $this;
    }
    
    public function getWrongReplyAnsWadRecord() {
        return session()->get('wrong_reply_record')??[];
    } 

    public function setTheAllPassFlag() {
        session()->put('fag_all_pass', 1);        
        return $this;
    }
    
    public function getTheAllPassFlag() {
        return session()->get('fag_all_pass');        
    } 

    public function setReplyErrorState() 
    {
        session()->put('faq_reply_error_state', 1);             
        return $this;
    } 

    public function getReplyErrorState() 
    {
        return session()->get('faq_reply_error_state');        
    }
  
    public function setCountDownStartTime() {
        session()->put('count_down_start_time', Carbon::now());        
        return $this;
    } 

    public function getCountDownStartTime() {
        return session()->get('count_down_start_time');        
        
    }

    public function getCountDownTime() {
        return $this->faq_service()->getCountDownTime();
    }

    public function isDuringCountDown() { 
        return (bool) $this->getCountDownSeconds();
    }
    
    public function isWrongReplyedQuByEntry($question_entry) {
        $wrong_record = $this->getWrongReplyAnsWadRecord();
        return (bool) ($wrong_record[$question_entry->id]??false);
    }    
    
    public function isForceShowFaqPopup() {
        $faqPopupQuestionList = $this->getPopupQuestionList();
        $faqCountDownStartTime = $this->getCountDownStartTime();
        $isFaqDuringCountDown = $this->isDuringCountDown();
        $faqReplyErrorState = $this->getReplyErrorState();
        
        return (count($faqPopupQuestionList) && !$faqCountDownStartTime && !$faqReplyErrorState) || $isFaqDuringCountDown;
    }
    
    public function clearCountDownRecord() {
        session()->forget('replyed_record');
        session()->forget('wrong_reply_record');
        session()->forget('count_down_start_time');
        return $this;
    }
    
    public function getCountDownSeconds() {
        if(!$this->getCountDownStartTime()) return 0;
        else $count_down_start_time = clone $this->getCountDownStartTime();        
        if(!($count_down_start_time??null)) return 0;
        
        $count_down_time = $this->getCountDownTime();
        $diff_seconds = Carbon::now()->diffInSeconds($count_down_start_time->addSeconds($count_down_time),false);
        
        if($diff_seconds<=$count_down_time && $diff_seconds>0) return $diff_seconds;
        
        return 0;
    }
    
    public function getQuDisabledAttrByEntry($question_entry) {
        $replyed_record = $this->getReplyedRecord();
        $wrong_record = $this->getWrongReplyAnsWadRecord();
        $entry_id = $question_entry->id;
        $attr = '';
        if(($replyed_record[$entry_id]??null)!==null) {
            $attr.= 'disabled ';        
        }
        
        return $attr;

    }
    
    public function getQuValueAttrByEntry($question_entry,$value=null,$default_attr=null) {
        $replyed_record = $this->getReplyedRecord();

        $wrong_record = $this->getWrongReplyAnsWadRecord();
        $entry_id = $question_entry->id;
        $attr = '';
        if(array_key_exists($entry_id,$replyed_record)) {
            
            if($value!==null 
                && !array_key_exists($entry_id,$wrong_record)
                && collect($replyed_record[$entry_id])->search($value)!==false
            )   
            {
                if($default_attr) $attr=$default_attr;
                else $attr=$value;
            }
        }
        
        return $attr;

    }  


    
    public function getAnsFillerByWrongReQuEntry($question_entry) {
        $wrong_record = $this->getWrongReplyAnsWadRecord();
        return $wrong_record[$question_entry->id]??null;
    }
    
    public function getReplyedBreakIndex() {
        $faqReplyedRecord = $this->getReplyedRecord();
        $break_index = collect($faqReplyedRecord)->values()->search(null,true);
        if($break_index===false) {
            $record_num = count($faqReplyedRecord);
            if($record_num) $break_index=$record_num-1;
        } 
        
        return $break_index;
    }
}
