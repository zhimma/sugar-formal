@foreach($question_entry->faq_choice as $choice)
<li>
    <input type="radio" name="reply" required value="{{$choice->id}}" data-labelauty="{{$choice->name}}"  {{$faqUserService->getQuValueAttrByEntry($question_entry,$choice->id,'checked')}}  {{$faqUserService->getQuDisabledAttrByEntry($question_entry)}}>
</li>
@endforeach