{{-- textarea標籤跟內容要在同一行，否則驗證會無效  --}}
<textarea name="reply[{{$tpl_question_entry->id}}][{{$choice_entry->id}}]"  class="g_rtext" 
placeholder="{{$choice_entry->placeholder}}" 
 >{{$service->getQuValueAttrByEntry($tpl_question_entry,$choice_entry->id)}}</textarea>