<li>
    <input type="radio" name="reply" value="1" required data-labelauty="是" {{$faqUserService->getQuValueAttrByEntry($question_entry,1,'checked')}}  {{$faqUserService->getQuDisabledAttrByEntry($question_entry)}} >
</li>
<li>
    <input type="radio" name="reply" value="0" data-labelauty="否" {{$faqUserService->getQuValueAttrByEntry($question_entry,0,'checked')}}  {{$faqUserService->getQuDisabledAttrByEntry($question_entry)}}>
</li>