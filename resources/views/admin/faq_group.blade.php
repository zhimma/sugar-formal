@extends('admin.main')
@section('app-content')
<link rel="stylesheet" href="{{asset('/css/faq_admin_common.css')}}">
<style>
.table > tbody > tr > td, .table > tbody > tr > th{
    vertical-align: middle;
}
h3{
    text-align: left;
}

#add_group_actor {
    margin-left:5%;
}

#act_all_region,#act_operator_block,#act_picker_block,act_picker_block {
    display:none;
}
#cancel_act_edit {margin-left:10px;}

#act_operator_block button {margin-top:5px;}
tr.no_answer_group_row {background:#fef1f1;}
td.group_not_act {background:#e0e0e0;}
</style>
<body style="padding: 15px;">
    <h1>FAQ組別</h1>
    @foreach($group_target_code_list as $gk=>$gv) 
        <a href="?engroupvip={{$gk}}" class="new text-white btn {{($gk==request()->engroupvip)?'btn-danger':'btn-success'}}" >{{$engroup_vip_words[$gk]}}</a>
    @endforeach    
    <a id="add_group_actor" href="{{ route('admin/faq_group/new/GET') }}{{$default_qstring}}" class='new text-white btn btn-success'>新增FAQ組別</a>    
    <form method="post" action="{{route('admin/faq_group/save_act')}}">
    {!! csrf_field() !!}
    <table class="table-bordered table-hover center-block text-center" style="width: 100%;" id="table">
        <tr>
            <th  class="text-center">名稱</th>
            <th width="5%" class="text-center">男/女</th>
            <th width="7%" class="text-center">會員上線第幾次跳</th>
            <th width="13%" class="text-center" >啟用

                <span id="act_all_region">
                    <input type="checkbox" id="act_all" onclick="toggle_checked_act_all(this);" />                    
                </span>
                <div id="act_edit_entrance_block">
                    <button type="button" id="act_edit_entrance" class='text-white btn btn-primary' onclick="enter_act_edit_mode();">修改</button>
                </div>                
                <div id="act_operator_block">
                    <button type="submit" class='text-white btn btn-primary' >送出</a>
                    <button type="button" id="cancel_act_edit" class='text-white btn btn-info' onclick="cancel_act_edit_mode();">取消</button>
                </div>            
            </th>
            <th width="8%" class="text-center">啟用時間</th>
           <th width="8%" class="text-center" >建立時間</th>
            <th width="8%" class="text-center" >更新時間</th>
            <th width="14%" class="text-center">操作</th>
        </tr>
        @foreach($entry_list as $entry)
                <tr class="template {{$entry->isRealHasAnswer()?'':'no_answer_group_row'}}">
                    <td style="word-break: break-all;">{{$entry->name}}</td>
                    <td>{{$service->getEngroupVipWord($entry->engroup.'_'.$entry->is_vip)}}</td>
                    <td>{{$entry->faq_login_times??0}}</td>
                    <td class="{{ $entry->act?'':'group_not_act' }}">
                        
                        @if($entry->isRealHasAnswer() || $entry->act)
                        <span class="show_act_word_region">{{ $entry->act?'是':'否' }}</span>
                        <div id="act_picker_block">
                            <input type="checkbox" name="act[]" value="{{$entry->id}}" class="group_act" onclick="single_toggle_act_all(this);" {{$entry->act?'checked':''}}  >
                            <input type="hidden" name="list_group_id[]" value="{{$entry->id}}" />
                            @if($entry->act)
                            <input type="hidden" name="old_act[]" value="{{$entry->id}}" />
                            @endif
                        </div>
                        @if(!$entry->isRealHasAnswer()) 
                        <div> 
                            ( 無答案，啟用無效) 
                        </div>
                        @endif                        
                        @else
                            <div>尚無答案</div>
                        @endif
                   </td>
                    <td>{!!nl2br($service->getGroupActAtWordByEntry($entry)) !!}</td>
                    <td class="created_at">{{ $entry->created_at }}</td>
                    <td class="updated_at">{{ $entry->updated_at }}</td>
                    <td>
                        <a class="text-white btn btn-primary" href="{{ route('admin/faq_group/edit', $entry->id) }}">修改</a>
                        <a class="text-white btn btn-danger" href="javascript:void(0)" onclick="deleteRow( {{$entry->id}})">刪除</a>
                    </td>
                </tr>
        @endforeach
    </table> 
    </form>
    <a href="{{ route('admin/faq') }}{{$service->getEngroupVipQueryString('?',request())}}" class="text-white btn btn-primary">返回題目</a>    
</body>
<script>
    function deleteRow(id) {
        let topic_title = $('#main > h1:first').html();
        if(topic_title==undefined || topic_title==null) topic_title = '資料';
        let c = confirm('確定要刪除這筆'+topic_title+'？');
        if(c === true){
            window.location = "{{ route('admin/faq_group/delete') }}/" + id;
        }
        else{
            return 0;
        }
    }
    
    function toggle_checked_act_all(dom) {
        if(dom.checked) {
            $( '.group_act' ).prop( "checked", true );
        }
        else {
            $( '.group_act' ).prop( "checked", false );
        }
    }
    
    function single_toggle_act_all(dom) {
        if(!dom.checked) {
            $('#act_all').prop('checked',false);
        }
    }
    
    function enter_act_edit_mode() {
        $('#act_operator_block,#act_picker_block').show();
        $('#act_all_region').css('display','inline-block');
        $('#act_edit_entrance_block,.show_act_word_region').hide();
    }
    
    function cancel_act_edit_mode() {
        $('#act_operator_block,#act_picker_block,#act_all_region').hide();
        $('#act_edit_entrance_block,.show_act_word_region').show();
    } 

    $(function() {
        if($('.group_act:checked').length>=$('.group_act').length) {
            $('#act_all').attr('checked','checked');
        }
    });     
</script>
@stop
