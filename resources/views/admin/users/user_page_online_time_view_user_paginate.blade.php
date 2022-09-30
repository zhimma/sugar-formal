<div class="ajax-loader-continer"><img src="{{asset('new/owlcarousel/assets/ajax-loader.gif')}}"></div>
<table  class="table table-bordered  table-hover records_table" style="display: block; max-height: 500px; overflow-x: scroll;">
    <thead>
    <tr class="info">
        <th >網址</th>
        <th width="10%">頁面名稱</th>
        <th width="5%">ip</th>
        <th width="45%">User Agent</th>
        <th width="5%">停留時間(秒)</th>
        <th width="10%" nowrap>開始時間</th>
    </tr>
    </thead>
    <tbody>
    @foreach($uRecord->getUserDescPageStayOnlineRecordsPaginate()->setPath(route('admin/user_page_online_time_view_user_paginate')) as $record)                                        
        <tr  class="@if($record->getUser())
                    {{$record->getUser()->banned?'banned':''}} 
                    {{$record->getUser()->implicitlyBanned?'implicitlyBanned':''}} 
                    {{(isset($record->getUser()->user_meta->isWarned) && $record->getUser()->user_meta->isWarned) || $record->getUser()->aw_relation?'isWarned':''}}
                    {{$record->getUser()->accountStatus===0?'isClosed':''}}
                    {{$record->getUser()->account_status_admin===0?'isClosedByAdmin':''}}
                    {{$record->getUser()->engroup == 1 ? 'male_row' : 'female_row'}}
                    @endif
        ">
            <td class="col-1st">{{ $record['url'] }}</td>
            <td class="col-2nd">
                @if($record->page_name && $record->page_name->name)
                    {{$record->page_name->name}}
                @elseif($record->getPartialUrlPageName())
                    {{$record->getPartialUrlPageName()}}
                
                @elseif($record->title)
                    {{$record->title}}
                
                @else
                    <div style="width:100%;text-align:center;"><a class="text-white btn btn-primary" href="'.route('admin/stay_online_record_page_name_switch',['url'=>$record->url,'rtn'=>'record']).'">設定<a></div>
                @endif
            </td>
            <td class="col-3rd" nowrap>{{$record->ip}}</td>
            <td class="col-most">{{$record->userAgent}}</td>
            <td class="col-most" nowrap>{{$record->stay_online_time}} 秒</td>
            <td class="col-most">{{$record->created_at}}</td>
        </tr>                                            
    @endforeach
    </tbody>
</table>
{!!$uRecord->paginate->appends(request()->input())->links('pagination::bootstrap-4') !!}

@if($with_user_page_link_binding_script??0)
<script>
$('#cell_of_records_table_{{$uRecord->getUser()->id}} .page-link').click(binding_records_tb_page_link);
</script>
@endif