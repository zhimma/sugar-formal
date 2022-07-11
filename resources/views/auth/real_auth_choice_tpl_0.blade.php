<div class="re_bae ga_top10">
    <span class="re_bae_span">
        <input 
            name="reply[{{$question_entry->id}}]" 
            type="radio" 
            value="1" 
            data-form_org_ans="{{$service->getQuValueAttrByEntry($question_entry,1)}}"
            style="margin-top: 2px; margin-right: 5px;" 
            class="{{$service->getQuValueAttrByEntry($question_entry,1,'form_org_ans_reply_'.$question_entry->id.'_')}}" 
            {{$service->getQuValueAttrByEntry($question_entry,1,'checked')}} 
            {{$question_entry->required?'required':null}}
        >是</span>
    <span class="re_bae_span">
        <input 
            name="reply[{{$question_entry->id}}]" 
            type="radio" 
            value="0" 
            data-form_org_ans="{{$service->getQuValueAttrByEntry($question_entry,0)}}"
            style="margin-top: 2px; margin-right: 5px;" 
            class="{{$service->getQuValueAttrByEntry($question_entry,0,'form_org_ans_reply_'.$question_entry->id.'_')}}" 
            {{$service->getQuValueAttrByEntry($question_entry,0,'checked')}} 
            {{$question_entry->required?'required':null}}
        >否</span>
</div>