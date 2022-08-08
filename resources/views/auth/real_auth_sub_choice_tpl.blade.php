@if($service->questionTypeToKey($sub_choice_entry->type)==4)
<input type="text" disabled="disabled" 
    class="re_rinput ga_tabbot {{$question_entry->required?'required':null}}  {{$service->getQuValueAttrByEntry($question_entry,$sub_choice_entry->id,'form_org_ans_reply_sub_choice__'.$question_entry->id.'__'.$sub_choice_entry->id.'_')}}" 
    data-form_org_ans="{{$service->getQuValueAttrByEntry($question_entry,$sub_choice_entry->id)}}"
    style="width: 100%;"  
    name="reply[sub_choice][{{$question_entry->id}}][{{$sub_choice_entry->id}}]" 
    id="choice_{{$sub_choice_entry->id}}" 
    placeholder="{{$sub_choice_entry->placeholder}}" 
    
    value="{{$service->getQuValueAttrByEntry($question_entry,$sub_choice_entry->id)}}"
>
@endif