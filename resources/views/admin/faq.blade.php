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

.nowrap {white-space:nowrap;}
.table-faq-list {margin-bottom:5%;margin-top:1%;}
.table-faq-list ul {padding:0;text-align:left;}
.table-faq-list ul li {margin-bottom:5px;margin-left:10px;}
.table-faq-list .faq_question_type_0 ul li {text-align:center;}
.table-faq-list tr.faq_qu_no_ans {background:#fccfe0;}
.table-faq-list tr.faq_qu_mismatch_ans {background:#fbfeec;}
.table-faq-list,.table-faq-list th,.table-faq-list td {border-width:2px;}
.table-faq-list th,.table-faq-list td {padding:5px;}
#faq_new_actor {margin-left:5%;}
.nowrap {white-space:nowrap;}
#count_down_time_block {display:inline-block;margin-left:5%;}
#count_down_time_zone input {width:50px;margin:0 5px;}
table.table-faq-list .group_not_act {background:#BEBEBE;}
#ans_txt_modal_answer {
    border-width:3px;
}

#ans_txt_modal_answer:focus {
    border-width:1px;
}

.passed_alert_btn {
    border:3px solid #6c757d !important;
}
</style>
<body style="padding: 15px;">
    <h1>FAQ機制</h1>
    <a href="javascript:void(0)" class='new text-white btn btn-success' onclick="$('#male_vip_faq').show();$('#female_faq,#male_faq').hide();changeFaqNewActorParam('1_1');">男VIP</a>
    <a href="javascript:void(0)" class='new text-white btn btn-success' onclick="$('#male_faq').show();$('#female_faq,#male_vip_faq').hide();changeFaqNewActorParam('1_0');">男普通</a>
    <a href="javascript:void(0)" class='new text-white btn btn-success' onclick="$('#male_vip_faq,#male_faq').hide();$('#female_faq').show();changeFaqNewActorParam('2_-1');">女會員</a>
    <a href="javascript:void(0)" id="faq_new_actor" data-url="{{ route('admin/faq/new/GET') }}" class='new text-white btn btn-success'>新增題目</a>
    <a href="javascript:void(0)" id="faq_group_list_actor" data-url="{{ route('admin/faq_group') }}" class='new text-white btn btn-success'>管理組別</a>
    <div id="count_down_time_block">
        <form method="post" action="{{route('admin/faq/setting/save')}}">
            {!! csrf_field() !!}
            <span id="count_down_time_zone">答題完畢倒數
                <input type="number" id="count_down_time" name="count_down_time" value="{{$count_down_time}}" min="0" >秒
            </span>
            <button type="submit"  class='new text-white btn btn-success'>儲存</a>
        </form>
    </div>
   <div  id="male_vip_faq">
        <h3>男VIP FAQ</h3> 
        @include('admin.faq_list_tpl',['tpl_engroup'=>1,'tpl_is_vip'=>1])
    </div>
    <div  id="male_faq">
        <h3>男普通會員 FAQ</h3> 
        @include('admin.faq_list_tpl',['tpl_engroup'=>1,'tpl_is_vip'=>0])
    </div>    
    <div id="female_faq">
        <h3>女性FAQ</h3>
        @include('admin.faq_list_tpl',['tpl_engroup'=>2,'tpl_is_vip'=>null])    
    </div>
    <div class="modal fade" id="ans_taf_modal" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" >選擇題目<span></span>的正解</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin/faq/answer/save')}}" method="POST" id="ans_taf_modal_form">
                    {!! csrf_field() !!}
                    <input type="hidden" value="" name="question_id">
                    <div class="modal-body">
                        <label class="for_form_elt_label" for="ans_taf_modal_choice_1"><input type="radio" name="answer" value="1" id="ans_taf_modal_choice_1" ><span>是</span></label>
                        <label class="for_form_elt_label" for="ans_taf_modal_choice_0"><input type="radio" name="answer" value="0" id="ans_taf_modal_choice_0"><span>否</span></label>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class='btn btn-outline-success ban-user'> 送出 </button>
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">取消</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ans_txt_modal" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" >編輯題目<span></span>的正解</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin/faq/answer/save')}}" method="POST" id="ans_txt_modal_form">
                    {!! csrf_field() !!}
                    <input type="hidden" value="" name="question_id">
                    <div class="modal-body">
                        <textarea class="form-control m-reason" name="answer" id="ans_txt_modal_answer" rows="10" col="200"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class='btn btn-outline-success ban-user'> 送出 </button>
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">取消</button>
                    </div>
                </form>
            </div>
        </div>
    </div>      
</body>
<script>
    var request_engroupvip = '{{request()->engroupvip}}';
    $('#male_vip_faq,#female_faq,#male_faq').hide();
    changeFaqNewActorParam('{{request()->engroupvip}}');
    switch(request_engroupvip) {
        case '1_0':
            $('#male_faq').show();
        break;
        case '2_-1':
            $('#female_faq').show();
        break; 
        default:
            $('#male_vip_faq').show();
        break;
    }
    function deleteRow(dom) {
        let now_elt = $(dom);
        let id = now_elt.data('id');
        let del_passed_alert = now_elt.data('del_passed_alert');
        let topic_title = $('#main > h1:first').html();
        let confirm_msg = '確定要刪除這筆'+topic_title+'？';
        if(topic_title==undefined || topic_title==null) topic_title = '資料';
        
        if(del_passed_alert==1) {
            confirm_msg = '\n請注意：\n\n已有會員通過此題目，\n\n若刪除此題目，\n\n此題目的會員通過紀錄將一併失效，\n\n請問：確定無論如何都要刪除此題目嗎？';
        }

        let c = confirm(confirm_msg);
        if(c === true){
            window.location = "{{ route('admin/faq/delete') }}/" + id;
        }
        else{
            return 0;
        }
    }
    
    function changeFaqNewActorParam(engroupvip) {
        var actor_elt = $('#faq_new_actor,#faq_group_list_actor');
        actor_elt.each(function(){
            $(this).attr('href',$(this).data('url')+'?engroupvip='+engroupvip);
        });
        
    
    }
    
	$('a.edit_ans[data-toggle=modal]').click(function () {
        if (typeof $(this).data('id') !== 'undefined') {
			var target_selector = $(this).data('target');
            var target_elt = $(target_selector);
            target_elt.find('.modal-title span').html('「'+$(this).data('name')+'」');
            target_elt.find('input[name=question_id]').val($(this).data('id'));

            switch(target_elt.attr('id')) {
                case 'ans_txt_modal':
                    target_elt.find('.modal-body textarea').html($(this).data('answer'));
                break;
                case 'ans_taf_modal':
                    $('#ans_taf_modal_choice_'+$(this).data('answer')).attr('checked','checked');
                break;                
            }
		}
	});    
</script>
@stop
