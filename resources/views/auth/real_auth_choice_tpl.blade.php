
@foreach($question_entry->real_auth_choice as $choice)
    @if($choice->type??null)
        @include('auth.real_auth_choice_tpl_'.$service->questionTypeToKey($choice->type))
    @endif
@endforeach

