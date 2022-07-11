@if($tpl_choice_entry??null)
    <input 
        class="g_rinput {{$service->getQuValueAttrByEntry($tpl_question_entry,$tpl_choice_entry->id,'form_org_ans_reply_'.$question_entry->id.'__'.$tpl_choice_entry->id.'_')}}  @if($tpl_choice_index!=$question_entry->real_auth_choice->count()-1) rzmabot_10 @endif" 
        id="choice_{{$tpl_choice_entry->id}}"
        name="reply[{{$question_entry->id}}][{{$tpl_choice_entry->id}}]" 
        placeholder="{{$tpl_choice_entry->placeholder}}"  
        value="{{$service->getQuValueAttrByEntry($tpl_question_entry,$tpl_choice_entry->id)}}"
        data-form_org_ans="{{$service->getQuValueAttrByEntry($tpl_question_entry,$tpl_choice_entry->id)}}"
    >
@else
    @foreach($tpl_question_entry->real_auth_choice as $tpl_choice_index=> $tpl_choice_entry)
    <input 
        class="g_rinput {{$service->getQuValueAttrByEntry($tpl_question_entry,$tpl_choice_entry->id,'form_org_ans_reply_'.$tpl_question_entry->id.'__'.$tpl_choice_entry->id.'_')}}   @if($tpl_choice_index!=$question_entry->real_auth_choice->count()-1) rzmabot_10 @endif" 
        id="choice_{{$tpl_choice_entry->id}}" 
        name="reply[{{$tpl_question_entry->id}}][{{$tpl_choice_entry->id}}]" 
        placeholder="{{$tpl_choice_entry->placeholder}}" 
        value="{{$service->getQuValueAttrByEntry($tpl_question_entry,$tpl_choice_entry->id)}}"
        data-form_org_ans="{{$service->getQuValueAttrByEntry($tpl_question_entry,$tpl_choice_entry->id)}}"
    >
    @endforeach     
@endif