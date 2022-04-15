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
        #table td {text-align:left;}
        #table td label {margin-right:20px;}
        #table th,#table td {padding:10px;}
        #faq_type_required_block {margin-top:-15px;}
        #faq_type_required_block select {
            width:0;
            height:0;
            border-color:transparent;
        }
        .group_info {display:none;} 
        #replace_group_id_elt {position:relative;top:-20px;}
        #replace_group_id_elt_block,#replace_group_id_elt {width:1px;height:1px;background-color:transparent;border:none;color:transparent;}
    </style>
    <body style="padding: 15px;">
    <h1>FAQ新增題目</h1>
    <form action="{{ route('admin/faq/new') }}" method="post">
        {!! csrf_field() !!}
        <table class="table-bordered table-hover center-block text-center" id="table">
            <tr>
                <th class="text-center">對象</th> 
                <td>
                @foreach($group_target_code_list as $gk=>$gv) 
                    <a href="javascript:void(0)" class="new text-white btn {{($gk==request()->engroupvip)?'btn-danger':'btn-success'}} switch_group" data-group_target_code="{{$gv}}" onclick="switch_group_act(this);">{{$engroup_vip_words[$gk]}}</a>
                @endforeach
                
                </td>
            </tr>
            <tr>
                <th class="text-center">組別</th> 
                <td>
                    @foreach($group_target_code_list as $gk=>$gv) 
                    <div id="{{$gv}}" class="group_id_block" style="display:{{$gk==request()->engroupvip?'block':'none'}}">
                        <select name="group_id" onchange="group_id_change_act(this);" required {{$gk==request()->engroupvip?null:'disabled'}}>
                            <option value="">請選擇組別</option>
                            @foreach($group_list_set[$gk] as $g)
                            <option value="{{$g->id}}">{{$g->name}}</option>
                            @endforeach
                        </select>  
                        @foreach($group_list_set[$gk] as $g)
                        <span class="group_info" id="group_info_{{$g->id}}" >
                            <span>{{$service->getEngroupVipWord($g->engroup.'_'.$g->is_vip)}}</span>
                            <span>第{{$g->faq_login_times}}次上線</span>
                        </span>
                        @endforeach
                    </div>
                    @endforeach
                    <div id="replace_group_id_elt_block">
                        <input type="radio" name="replace_group_id_elt" id="replace_group_id_elt" required />
                    </div>
                </td>
            </tr>
            <tr>
                <th class="text-center">題目</th>
                
                <td>
                    <textarea name="question" required></textarea>
                </td>
            </tr>
            <tr>
                <th class="text-center" nowrap>題目類型</th>
                <td>
                    @foreach($question_type_list as $tk=>$t)
                        <label for="question_type_{{$tk}}">
                        <input type="radio" name="type" value="{{$t}}" id="question_type_{{$tk}}" onclick="this.form.type_required.options[{{$tk}}+1].selected=true;">{{$t}} 
                        </label>
                    @endforeach
                    <div id="faq_type_required_block">
                    <select name="type_required" required>
                    <option value=""></option>
                    @foreach($question_type_list as $tk=>$t)
                    <option value="{{$t}}"></option>
                    @endforeach
                    </select> 
                    </div>
                </td>
            </tr>
            <tr>
                <th class="text-center">操作</th>
                <td>
                    
                    <input type="submit" class='text-white btn btn-success' value="送出">
                    <input type="reset"  class='text-white btn btn-danger' value="復原" onclick="$('.group_info').hide();$('#replace_group_id_elt').val('').removeAttr('checked');">                
                </td>
            </tr>
        </table>
    </form> 
    <a href="{{ route('admin/faq') }}{{$service->getEngroupVipQueryString('?',request())}}" class="text-white btn btn-primary">返回題目</a>    
    <script>
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
        var now_group_target_code = now_elt.data('group_target_code');
        
        $('.group_id_block').hide();
        $('.group_id_block select').attr('disabled','disabled').val('');
        $('.group_info').hide(); 
        $('#'+now_group_target_code).show().find('select').removeAttr('disabled');        
        $('.switch_group').removeClass('btn-danger').addClass('btn-success');
        now_elt.removeClass('btn-success').addClass('btn-danger');
    }
    </script>
    </body>
@stop
