@foreach($entry_list->where('parent_id',$question_entry->id) as $sub_q_idx=>$sub_question_entry)
<div class="g_rznz matop15 rzmabot_20">
     <h2>{{$sub_question_entry->question}}</h2>
    @if($service->questionTypeToKey($sub_question_entry->type)!==false) 
        @include('auth.real_auth_choice_tpl_'.$service->questionTypeToKey($sub_question_entry->type),['tpl_question_entry'=>$sub_question_entry])
    @else
        @foreach($sub_question_entry->real_auth_choice->whereNull('parent_id') as $choice_index=> $choice_entry)
            @if($choice_index && $choice_entry->type!=$sub_question_entry->real_auth_choice[$choice_index-1]->type)
            <span class="ga_or01">-or-</span>    
            @endif
            @if($choice_entry->type || $choice_entry->type)
                @include('auth.real_auth_choice_tpl_'.$service->questionTypeToKey($sub_question_entry->type===null?$choice_entry->type:$sub_question_entry->type),['tpl_question_entry'=>$sub_question_entry,'tpl_choice_entry'=>$choice_entry,'tpl_choice_index'=>$choice_index])
            @endif
        @endforeach
    @endif
</div>                        
@endforeach