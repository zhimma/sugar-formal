@foreach($entry_list->whereNull('parent_id') as $q_idx=>$question_entry)
<div class="gjr_nr02 gir_top20 gir_pa01">
    <h2 class="gjr_nr02_h2">{{$q_idx+1}}:{{$question_entry->question}}{{$question_entry->required?'(必填)':''}}</h2>
    @if(in_array($service->questionTypeToKey($question_entry->type),[3,4]) ||  $question_entry->real_auth_choice->whereNull('parent_id')->unique('type')->count()>1) 
    <div class="gjr_nr02_h2 rzmatop_5">
    @endif
    <h2 class="rzmatop_5">
        @if($service->questionTypeToKey($question_entry->type)!==false) 
            @include('auth.real_auth_choice_tpl_'.$service->questionTypeToKey($question_entry->type),['tpl_question_entry'=>$question_entry])
        @else
            @foreach($question_entry->real_auth_choice->whereNull('parent_id') as $choice_index=> $choice_entry)
                @if($choice_index && $choice_entry->type!=$question_entry->real_auth_choice[$choice_index-1]->type)
                <span class="ga_or01">-or-</span>    
                @endif
                @if($choice_entry->type || $choice_entry->type)
                    @include('auth.real_auth_choice_tpl_'.$service->questionTypeToKey($question_entry->type===null?$choice_entry->type:$question_entry->type),['tpl_question_entry'=>$question_entry,'tpl_choice_entry'=>$choice_entry,'tpl_choice_index'=>$choice_index])
                @endif
            @endforeach
        @endif
         </h2>
    @if(in_array($service->questionTypeToKey($question_entry->type),[3,4])) 
    </div>
    @endif

    @include('auth.real_auth_sub_question_tpl')
</div>                        
@endforeach