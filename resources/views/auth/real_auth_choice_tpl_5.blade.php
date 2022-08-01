@if($tpl_choice_entry??null)
    <input type="hidden" name="reply_pic[{{$tpl_question_entry->id}}][{{$tpl_choice_entry->id}}]" >    
    <input type="hidden" id="unchk_pic_num_of_choice_{{$tpl_choice_entry->id}}" value="{{$service->getQuActualUncheckedPicNumByEntry($tpl_question_entry)}}" >
    <input type="file" class="reply_pic_choice" name="reply_pic_{{$tpl_question_entry->id}}_{{$tpl_choice_entry->id}}" id="choice_{{$tpl_choice_entry->id}}" data-fileuploader-files="{{$service->getQuUploaderPreFilesByEntry($tpl_question_entry)}}" >
@elseif($tpl_question_entry->real_auth_choice->count())    
    @foreach($tpl_question_entry->real_auth_choice as $tpl_choice_index=> $tpl_choice_entry)
    <input type="hidden" name="reply_pic[{{$tpl_question_entry->id}}][{{$tpl_choice_entry->id}}]" >    
    <input type="hidden" id="unchk_pic_num_of_choice_{{$tpl_choice_entry->id}}" value="{{$service->getQuActualUncheckedPicNumByEntry($tpl_question_entry)}}">
    <input type="file" class="reply_pic_choice" name="reply_pic_{{$tpl_question_entry->id}}_{{$tpl_choice_entry->id}}" id="choice_{{$tpl_choice_entry->id}}" data-fileuploader-files="{{$service->getQuUploaderPreFilesByEntry($tpl_question_entry)}}">    
    @endforeach 
@elseif($tpl_question_entry->type)
    <input type="hidden" name="reply_pic[{{$tpl_question_entry->id}}]" >
    <input type="hidden" id="unchk_pic_num_of_solo_choice_{{$tpl_question_entry->id}}" value="{{$service->getQuActualUncheckedPicNumByEntry($tpl_question_entry)}}">
    <input type="file" class="reply_pic_choice" name="reply_pic_{{$tpl_question_entry->id}}" id="solo_choice_{{$tpl_question_entry->id}}" data-fileuploader-files="{{$service->getQuUploaderPreFilesByEntry($tpl_question_entry)}}">
@endif