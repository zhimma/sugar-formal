<div class="re_bae ga_top10">
    <span class="re_bae_span"><input name="reply[{{$question_entry->id}}]" type="radio" value="1" style="margin-top: 2px; margin-right: 5px;" {{$service->getQuValueAttrByEntry($question_entry,1,'checked')}} {{$question_entry->required?'required':null}}>是</span>
    <span class="re_bae_span"><input name="reply[{{$question_entry->id}}]" type="radio" value="0" style="margin-top: 2px; margin-right: 5px;" {{$service->getQuValueAttrByEntry($question_entry,0,'checked')}} {{$question_entry->required?'required':null}}>否</span>
</div>