    <input type="checkbox" 
        name="reply[{{$question_entry->id}}][]" 
        value="{{$choice_entry->id}}" 
        data-form_org_ans="{{$service->getQuValueAttrByEntry($question_entry,$choice_entry->id)}" 
        data-labelauty="{{$choice->name}}" 
        class="{{$service->getQuValueAttrByEntry($question_entry,$choice_entry->id,'form_org_ans_reply_'.$question_entry->id.'_')}}" 
        {{$service->getQuValueAttrByEntry($question_entry,$choice_entry->id,'checked')}} 
    >
