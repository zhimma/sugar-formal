@foreach($question_entry->faq_choice as $choice)
<li>
    <input type="checkbox" name="reply[]" value="{{$choice->id}}" data-labelauty="{{$choice->name}}" {{$faqUserService->getQuDisabledAttrByEntry($question_entry)}} {{$faqUserService->getQuValueAttrByEntry($question_entry,$choice->id,'checked')}} >
</li>
@endforeach
