        <table class="table-bordered table-hover center-block text-center table-faq-list" style="width: 100%;">
        <tr>
            <th width="8%" class="text-center nowrap">組別</th>
            <th width="20%" class="text-center nowrap">題目</th>
            <th width="5%" class="text-center nowrap">類型</th>
            <th class="text-center nowrap">選項</th>
            <th class="text-center nowrap">正解</th>
            <th width="5%" class="text-center">會員上線第幾次跳</th>
            <th width="8%" class="text-center">建立時間</th>
            <th width="8%" class="text-center">更新時間</th>
            <th width="14%" class="text-center">操作</th>
        </tr>
        @foreach($entry_list as $entry)
            @if($service->isGroupMatchEngroupVip($entry->faq_group,$tpl_engroup,$tpl_is_vip))
                <tr class="template faq_question_type_{{$service->questionTypeToKey($entry->type)}} {{$service->getQuAnsStateClassByEntry($entry)}}">
                    <td class="{{!($entry->faq_group->act??null)?'group_not_act':''}}">{{ $entry->faq_group->name }}</td>
                    <td>{{$entry->question}}</td>
                    <td>{{$entry->type}}</td>
                    <td>
                        {!!$service->slotByQuestionId($entry->id)->getChoiceLayout()!!}
                    </td>
                    <td>{!!$service->getAnswerLayout()!!}</td>
                    <td>{{ $entry->faq_group->faq_login_times }}</td>
                    <td class="created_at">{{ $entry->created_at }}</td>
                    <td class="updated_at">{{ $entry->updated_at }}</td>
                    <td>                        
                        <a class='text-white btn btn-primary' href="{{ route('admin/faq/edit', $entry->id) }}">修改</a>
                        <a class="text-white btn {{$service->isQuestionDeletableByEntry($entry)?'btn-danger':'btn-secondary'}} {{$service->isQuestionDelPassedAlertByEntry($entry)?'passed_alert_btn':''}}" href="javascript:void(0)" 
                        data-del_passed_alert="{{(int) $service->isQuestionDelPassedAlertByEntry($entry)}}" data-id="{{$entry->id}}"
                        onclick="{{!$service->isQuestionDeletableByEntry($entry)?'alert("仍有選項且已有會員通過此題目，\n故無法刪除此題目。\n若確定要刪除此題目，\n請先刪光此題目的選項。");':'deleteRow(this )';}}">刪除</a>
                        @if($service->isCustomChoiceByQuEntry($entry))
                        <a href="{{ route('admin/faq_choice', $entry->id) }}" class='new text-white btn btn-success'>管理選項</a>
                        @else
                            @if($service->questionTypeToKey($entry->type)=='0')
                            <a href="javascript:void(0)" class='new text-white btn btn-success edit_ans'  data-toggle="modal" data-target="#ans_taf_modal" data-id="{{ $entry->id }}" data-name="{{ $entry->question}}"  data-answer="{{$entry->answer_bit}}">選擇正解</a> 
                            @elseif($service->questionTypeToKey($entry->type)=='3')
                            <a href="javascript:void(0)" class='new text-white btn btn-success edit_ans'  data-toggle="modal" data-target="#ans_txt_modal" data-id="{{ $entry->id }}" data-name="{{ $entry->question}}" data-answer="{{$entry->answer_context}}">編輯正解</a> 
                            @endif
                        @endif
                    </td>
                </tr>
            @endif
        @endforeach
    </table>