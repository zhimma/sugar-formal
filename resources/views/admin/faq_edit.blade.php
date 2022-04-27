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
        .is_new_block {position:relative;top:20px;color:black;font-weight:normal;}
        .cvvs_box {margin-top:5px;}
        .ainput {width:80px;} 

        #table {width:100%;margin:10px;}
        #table th {width:20%;white-space:nowrap;}
        #table td {text-align:left;padding-left:2%;}
        #table td label {margin-right:20px;}
        .group_info {display:none;}
        #male_vip_faq,#female_faq,#male_faq {display:none;}
        #replace_group_id_elt {position:relative;top:-20px;}
        #replace_group_id_elt_block,#replace_group_id_elt {width:1px;height:1px;background-color:transparent;border:none;color:transparent;}        
    </style>
    <body style="padding: 15px;">
    <h1>FAQ修改題目</h1>
    <form action="{{ route('admin/faq/save') }}" method="post">
        {!! csrf_field() !!}
        <table class="table-bordered table-hover center-block text-center" id="table">
            <tr>
                <th class="text-center">對象</th> 
                <td>
                @foreach($group_target_code_list as $gk=>$gv) 
                    <a href="javascript:void(0)" class="new text-white btn {{($gk==$service->getEngroupVipCodeByEntry($entry->faq_group))?'btn-danger org_group_switch':'btn-success'}} switch_group" data-group_target_code="{{$gv}}" onclick="switch_group_act(this);">{{$engroup_vip_words[$gk]}}</a>
                @endforeach                
                </td>
            </tr>            
            
            <tr>
                <th class="text-center">組別</th> 
                <td>
                    @foreach($service->group_target_code_list() as $gk=>$gv) 
                    <div id="{{$gv}}" class="group_id_block" style="{{($gk==$service->getEngroupVipCodeByEntry($entry->faq_group))?'display:block':''}}">                
                    <select name="group_id" onchange="group_id_change_act(this);" required {{($gk!=$entry->faq_group->engroup.'_'.$entry->faq_group->is_vip)?'disabled':''}}>
                    <option value="">請選擇組別</option>
                    @foreach($group_list_set[$gk] as $g)
                    <option value="{{$g->id}}" {{$service->getFormDdlEditAssign($g->id,$entry->group_id,request()->group_id)}}>
                        {{$g->name}}
                    </option>
                    @endforeach
                    </select>
                    @foreach($group_list_set[$gk] as $g)
                    <span class="group_info {{$entry->group_id==$g->id?'org_group_info':null}}" id="group_info_{{$g->id}}" style="{{$entry->group_id==$g->id?'display:inline':null}}" >
                    <span>{{$service->getEngroupVipWord($g->engroup.'_'.$g->is_vip)}}</span>
                    <span>第{{$g->faq_login_times}}次上線</span>
                    </span>
                    @endforeach
                    </div>
                    @endforeach
                    <div id="replace_group_id_elt_block">
                        <input type="radio" name="replace_group_id_elt" id="replace_group_id_elt" value="{{$entry->group_id}}" {{$entry->group_id?'checked':''}} required />
                    </div>                    
                </td>
            </tr>
            <tr>
                <th class="text-center">題目</th>
                
                <td>
                    <textarea name="question" required>{{$entry->question}}</textarea>
                </td>
            </tr>
            <tr>
                <th class="text-center" nowrap>題目類型</th>
                <td>
                {{$entry->type}}
                <input type="hidden" name="type" value="{{$entry->type}}" >
                </td>
            </tr>
            <tr>
                <th class="text-center">操作</th>
                <td>
                    <input type="hidden" name="id" value="{{ $entry->id }}">                
                    <input type="submit" class='text-white btn btn-success' value="送出" onclick="return form_check(this.form);">
                    <input type="reset"  class='text-white btn btn-danger' value="復原" onclick="$('.group_info').hide();$('.org_group_switch').click();$('.org_group_info').show();$('#replace_group_id_elt').val('{{$entry->group_id}}');">                
                </td>
            </tr>
        </table>
    </form>  
    <a href="{{ route('admin/faq') }}{{$service->getEngroupVipQueryString('?')}}" class="text-white btn btn-primary">返回題目</a>    
    </body>
    <script>
        function form_check(form_dom) {
            return true;
            var rs = true;
            var msg = '';

            if(form_dom.group_id.selectedIndex==0) {
                msg+='請選擇組別\n';
                rs = false;
            }
            
            if(form_dom.question.value=='') {
                msg+='請輸入標題\n';
                rs = false;
            }

            if(form_dom.type.value=='' || form_dom.type.value==null || form_dom.type.value==undefined) {
                msg+='請選擇題目類型\n';
                rs = false;
            }

            if(msg!='') {
                alert(msg);
            }

            return rs;
        }

    function group_id_change_act(now_dom) {
        $('#replace_group_id_elt').val(now_dom.value).removeAttr('checked');
        if(now_dom.value){
            $('#replace_group_id_elt').attr('checked','checked');
        }
        $('.group_info').hide();
        $('.group_id_block').find('select').attr('disabled','disabled');
        $(now_dom).removeAttr('disabled');
        $('#group_info_'+now_dom.value).css('display','inline-block').find('select').removeAttr('disabled');        
    }
    
    function switch_group_act(dom) {
        var now_elt = $(dom);
        if(now_elt.hasClass('btn-danger')) return;
        var now_group_target_code = now_elt.data('group_target_code');
        
        $('.group_id_block').hide();
        $('.group_id_block select').attr('disabled','disabled').val('');
        $('.group_info').hide(); 
        $('#'+now_group_target_code).show().find('select').removeAttr('disabled');        
        $('.switch_group').removeClass('btn-danger').addClass('btn-success');
        now_elt.removeClass('btn-success').addClass('btn-danger');
    }    
    </script>    
@stop
