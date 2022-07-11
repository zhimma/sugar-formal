{{-- textarea標籤跟內容要在同一行，否則驗證會無效  --}}
<textarea 
    name="reply[{{$tpl_question_entry->id}}][{{$choice_entry->id}}]"  
    placeholder="{{$choice_entry->placeholder}}" 
    class="g_rtext {{$service->getQuValueAttrByEntry($tpl_question_entry,$choice_entry->id,'form_org_ans_reply_'.$tpl_question_entry->id.'__'.$choice_entry->id.'_')}}" 
    data-form_org_ans="{{$service->getQuValueAttrByEntry($tpl_question_entry,$choice_entry->id)}}"
 >{{$service->getQuValueAttrByEntry($tpl_question_entry,$choice_entry->id)}}</textarea>