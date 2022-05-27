 {{--<ul class="n_ulpic" style="margin-bottom:5px;">
    <li class="write_img mabot_10 dt_pa0">
        <b class="img dt_heght gir_border">
        --}}{{--<img src="{{asset('alert/images/ph_xz01.png')}}" class="hycov">--}}
            {{--<input type="file" class="reply_pic_choice" name="reply[{{$question_entry->id}}][{{$tpl_choice_entry->id}}]" id="choice_{{$tpl_choice_entry->id}}" >
        </b>
    </li>
</ul>--}}
@if($tpl_choice_entry??null)
<input type="file" class="reply_pic_choice" name="reply[{{$tpl_question_entry->id}}][{{$tpl_choice_entry->id}}]" id="choice_{{$tpl_choice_entry->id}}" data-fileuploader-files="{{$service->getQuPreFilesByEntry($tpl_question_entry)}}" >
@elseif($tpl_question_entry->real_auth_choice->count())    
    @foreach($tpl_question_entry->real_auth_choice as $tpl_choice_index=> $tpl_choice_entry)
    <input type="file" class="reply_pic_choice" name="reply[{{$tpl_question_entry->id}}][{{$tpl_choice_entry->id}}]" id="choice_{{$tpl_choice_entry->id}}" data-fileuploader-files="{{$service->getQuPreFilesByEntry($tpl_question_entry)}}">
    @endforeach 
@elseif($tpl_question_entry->type)
    <input type="file" class="reply_pic_choice" name="reply[{{$tpl_question_entry->id}}]" id="solo_choice_{{$tpl_question_entry->id}}" data-fileuploader-files="{{$service->getQuPreFilesByEntry($tpl_question_entry)}}">
@endif