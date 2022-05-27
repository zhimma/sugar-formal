@if($service->questionTypeToKey($sub_choice_entry->type)==4)
<input type="text" disabled="disabled" class="re_rinput ga_tabbot" style="width: 100%;"  
    name="reply[sub_choice][{{$question_entry->id}}][{{$sub_choice_entry->id}}]" 
    id="choice_{{$sub_choice_entry->id}}" 
    placeholder="{{$sub_choice_entry->placeholder}}" 
    {{$question_entry->required?'required':null}}
    value="{{$service->getQuValueAttrByEntry($question_entry,$sub_choice_entry->id)}}"
>
@endif