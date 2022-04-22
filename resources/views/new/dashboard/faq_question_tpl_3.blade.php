{{-- textarea標籤跟內容要在同一行，否則驗證會無效  --}}
<textarea name="reply"  class="text_wd" 
placeholder="請輸入內容" 
{{$faqUserService->getQuDisabledAttrByEntry($question_entry)}} 
required >{{$faqUserService->getQuValueAttrByEntry($question_entry,$faqReplyedRecord[$question_entry->id]??null)}}</textarea>