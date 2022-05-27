<div class="re_bae ga_top10">
@if($tpl_choice_entry??null)
    <span class="re_bae_span ga_tabbot"><input name="reply[{{$tpl_question_entry->id}}]" type="radio" value="{{$tpl_choice_entry->id}}" style="margin-top: 2px; margin-right: 5px;" {{$service->getQuValueAttrByEntry($tpl_question_entry,$tpl_choice_entry->id,'checked')}} {{$question_entry->required?'required':null}}>{{$tpl_choice_entry->name}}</span>
@else
    @foreach($tpl_question_entry->real_auth_choice->whereNull('parent_id') as $tpl_choice_index=> $tpl_choice_entry)
    <span class="re_bae_span ga_tabbot">
        <input name="reply[{{$tpl_question_entry->id}}]" type="radio" id="choice_{{$tpl_choice_entry->id}}" value="{{$tpl_choice_entry->id}}" style="margin-top: 2px; margin-right: 5px;" {{$service->getQuValueAttrByEntry($tpl_question_entry,$tpl_choice_entry->id,'checked')}} {{$question_entry->required?'required':null}}>{{$tpl_choice_entry->name}}
        @if($tpl_question_entry->real_auth_choice->where('parent_id',$tpl_choice_entry->id)->count())
            @foreach($tpl_question_entry->real_auth_choice->where('parent_id',$tpl_choice_entry->id) as $sub_choice_idx=>$sub_choice_entry)
            @include('auth.real_auth_sub_choice_tpl')    
            @endforeach
        @endif    
    </span>
    @endforeach    
    
@endif
</div>  
{{--
  <input type="radio" name="reply[$tpl_question_entry->id][$choice_entry->id]" value="{{$choice_entry->id}}" data-labelauty="{{$choice_entry->name}}"  {{$service->getQuValueAttrByEntry($tpl_question_entry,$choice_entry->id,'checked')}}>
        {{$choice_entry->name}}
--}}        
