<div class="re_bae ga_top10">
@if($tpl_choice_entry??null)
    <span class="re_bae_span ga_tabbot">
        <label>
            <input 
                name="reply[{{$tpl_question_entry->id}}]" 
                type="radio" 
                value="{{$tpl_choice_entry->id}}" 
                style="margin-top: 2px; margin-right: 5px;" 
                data-form_org_ans="{{$service->getQuValueAttrByEntry($tpl_question_entry,$tpl_choice_entry->id)}}"
                class="{{$service->getQuValueAttrByEntry($tpl_question_entry,$tpl_choice_entry->id,'form_org_ans_reply_'.$tpl_question_entry->id.'_')}}"
                {{$service->getQuValueAttrByEntry($tpl_question_entry,$tpl_choice_entry->id,'checked')}} 
                {{$question_entry->required?'required':null}}
        >{{$tpl_choice_entry->name}}
        </label>
    </span>
@else
    @foreach($tpl_question_entry->real_auth_choice->whereNull('parent_id') as $tpl_choice_index=> $tpl_choice_entry)
    <span class="re_bae_span ga_tabbot">
        <label>
            <input 
                name="reply[{{$tpl_question_entry->id}}]" 
                type="radio" 
                id="choice_{{$tpl_choice_entry->id}}" 
                value="{{$tpl_choice_entry->id}}" 
                style="margin-top: 2px; margin-right: 5px;"  
                class="{{$service->getQuValueAttrByEntry($tpl_question_entry,$tpl_choice_entry->id,'form_org_ans_reply_'.$tpl_question_entry->id.'_')}}"  
                data-form_org_ans="{{$service->getQuValueAttrByEntry($tpl_question_entry,$tpl_choice_entry->id)}}"
                {{$service->getQuValueAttrByEntry($tpl_question_entry,$tpl_choice_entry->id,'checked')}} 
                {{$question_entry->required?'required':null}}
            >{{$tpl_choice_entry->name}}
        </label>
        @if($tpl_question_entry->real_auth_choice->where('parent_id',$tpl_choice_entry->id)->count())
            @foreach($tpl_question_entry->real_auth_choice->where('parent_id',$tpl_choice_entry->id) as $sub_choice_idx=>$sub_choice_entry)
            @include('auth.real_auth_sub_choice_tpl')    
            @endforeach
        @endif    
    </span>
    @endforeach    
    
@endif
</div>         
