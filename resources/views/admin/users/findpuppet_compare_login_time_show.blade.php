@extends('admin.main')
@section('app-content')

<style>
    body div.top_head_title_info {text-align:center;}
    body div.top_head_title_info div.group_cat_info {margin-top:20px;}
    body div.top_head_title_info div.group_cat_info  a.jump_to_all_handled_group{text-decoration: underline;}
	img.ignore_switch_off {display:none;}
	img.ignore_switch_on {display:inline-block;}
	body h2 span.dateInfo {font-size:15px;font-weight:normal;display:block;margin-top:10px;}
	body table.table-hover {width:auto;max-width:none;}

	
	body table.table-hover.table tr.isClosed {background-color:#C9C9C9}
	tr.isClosed th.col-most, tr.isClosed td.col-most {background-color:#C9C9C9 !important;}

	body table.table-hover tr.isWarned {background-color:#B0FFB1;}
	tr.isWarned th.col-most,tr.isWarned td.col-most {background-color:#B0FFB1 !important;}
	
	table.table-hover tr.banned {background-color:#FDFF8C;} 
	table.table-hover tr.implicitlyBanned {background-color:#FDFF8C;}
	tr.banned th.col-most,tr.implicitlyBanned th.col-most,tr.banned td.col-most,tr.implicitlyBanned td.col-most {background-color:#FDFF8C !important;}
	
	tr.isClosedByAdmin {background-color:#969696}
	tr.isClosedByAdmin th.col-1st,tr.isClosedByAdmin th.col-2nd,tr.isClosedByAdmin th.col-3rd,tr.isClosedByAdmin th.col-most,tr.isClosedByAdmin td.col-most {background-color:#969696 !important;}	
	
	tr.banned th.col-1st,tr.implicitlyBanned th.col-1st,tr.banned th.col-2nd,tr.implicitlyBanned th.col-2nd,tr.banned th.col-3rd,tr.implicitlyBanned th.col-3rd {background-color:#FDFF8C !important;}
	
	tr.isClosedByAdmin:not(.banned):not(.implicitlyBanned) th.col-3rd,tr.isClosedByAdmin:not(.isClosed) th.col-3rd,tr.isClosedByAdmin:not(.isWarned) th.col-3rd {background-color:#969696 !important;}
	tr.isWarned:not(.isClosedByAdmin):not(.banned):not(.implicitlyBanned) th.col-3rd {background-color:#B0FFB1 !important;}
	
	tr.isWarned th.col-1st ,tr.isWarned th.col-2nd {background-color:#B0FFB1 !important;}
	
	tr.banned.isWarned:not(.isClosed) th.col-2nd,tr.implicitlyBanned.isWarned:not(.isClosed) th.col-2nd{background-color:#FDFF8C !important;}
	tr.isClosedByAdmin.isWarned:not(.isClosed):not(.banned):not(.implicitlyBanned) th.col-2nd {background-color:#969696 !important;}
	tr.isClosedByAdmin.banned:not(.isClosed):not(.isWarned) th.col-2nd,tr.isClosedByAdmin.implicitlyBanned:not(.isClosed):not(.isWarned) th.col-2nd {background-color:#969696 !important;}
	
	tr.isClosed th.col-1st {background-color:#C9C9C9 !important;}	
	
	body table.table-hover tr td.col_ip_first ,body table.table-hover tr th.col_ip_first {border-left: 6px solid #000;}

	body table tr td,body table tr th {white-space: nowrap;}
	body table tr td.ignore_msg {text-align: left;vertical-align: top;border-bottom:none;}
	tr th a.user {cursor:default;text-decoration: none;}
	tr th a.user:hover {cursor:default;text-decoration: none;}
	h3 {color:red;}
	div.attentioninfo,table{clear:both;}
	a, a:visited, a:hover, a:active {
		/*text-decoration: none;*/
		color: inherit;
	}
    .show {margin-top:50px;margin-bottom:20px;}
    .error {color:red;font-weight:bolder;}
    td, th{ padding:5px;text-align: center;vertical-align: middle;}
    table.monlist,table.monlist tr,table.monlist td,table.monlist th {border-width:0px;border-style:none;}
	table.monlist {margin:auto;width:60%;}
	.monlist a.current_month,.monlist a.current_month:visited,.monlist a.current_month:hover,.monlist a.current_month:active{text-decoration: none;}
	.monlist a,.monlist a:visited,.monlist a:hover,.monlist a:active {text-decoration: underline;}
	.download_file a,.download_file a:visited,.download_file a:hover,.download_file a:active {text-decoration: underline;}
	.group_last_time {font-weight:normal;font-size:12px;}
	.ignore_switch_on,.ignore_switch_off,.ignore_cell_on,.ignore_cell_off {height:15px;cursor: pointer;}
	body table tr td.ignore_cell,body table tr td.ignore_cell a,body table tr td.ignore_cell a:visited,body table tr td.ignore_cell a:hover {color:#B0B0B0 !important;}
	body table tr th .btn {width:60px;padding:4px;border-radius:5px;font-size:5px;}
	body table tr th .btn:visited {color:#fff;}
	body table tr th .btn.handling {background-color:#BEBEBE;color:block;cursor:default;}
 	body table tr td.group_last_time,body table tr.banned td.group_last_time,body table tr.implicitlyBanned td.group_last_time,body table tr.isWarned td.group_last_time,body table tr.isClosed td.group_last_time,body table tr.isClosedByAdmin td.group_last_time {background-color:#FF9999 !important;}
    h2 span.title_info {font-size:15px;}
    div.new_exec_log {margin-top:10px;margin-bottom:10px;border:1px solid #e9ecef;padding:5px;border-radius:10px;}
    div.bolder {font-weight:bolder;}
    #cron_log {margin-left:145px;display:none;}
    #review_cron_log {
        width: 7%;
        padding: 3px;
        color: #fff;
        font-size: 2px;        
    }
 </style>
 <style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
    .content-table { width:100%; table-layout: fixed; }
    .content-table td { word-wrap:break-word; }
</style>
<style>
    #compare_setting_container > span {margin-right:10px;}
</style>
<script type="text/javascript">
    $(function() {
		
		
		$('.btn_admin_close').on('click',function() {
			
			var nowElt = $(this);
			var org_text = nowElt.text();
			if(org_text=='處理中') return false;
			if(nowElt.hasClass('btn-danger')) {
				var org_class = 'btn-danger';
				var next_class = 'btn-success';
				var next_text = '站方開啟';
				var parent_next = 'add';
				
				account_status = 0;
			}
			else if(nowElt.hasClass('btn-success')) {
				var org_class = 'btn-success';
				var next_class = 'btn-danger';
				var next_text = '站方關閉';	
				var parent_next = 'remove';
				account_status = 1;				
			}
			
			if(parent_next=='add') {
				nowElt.parent().parent().addClass('isClosedByAdmin');
			}
			else if(parent_next=='remove') {
				nowElt.parent().parent().removeClass('isClosedByAdmin');
			}
			
			nowElt.removeClass(org_class);
			nowElt.addClass('handling');
			nowElt.text('處理中');				

			var user_id = nowElt.parent().find('a.user_id').text().replace(' ','');
			$.ajax({
				type: 'POST',
				url: '/admin/users/accountStatus_admin?{{csrf_token()}}={{now()->timestamp}}',
				data: { uid : user_id,account_status:account_status,_token: '{{csrf_token()}}'},
				success: function(xhr, status, error){
					nowElt.text(next_text);				
					nowElt.removeClass('handling');
					nowElt.addClass(next_class);
				},
				error: function(xhr, status, error){
					alert('User Id：'+user_id+org_text+'失敗，請重新操作\n錯誤訊息：'+status+' '+error);
					nowElt.text(org_text);
					nowElt.removeClass('handling');
					nowElt.removeClass(next_class);
					nowElt.addClass(org_class);	

					if(parent_next=='add') {
						nowElt.parent().parent().removeClass('isClosedByAdmin');
					}
					else if(parent_next=='remove') {
						nowElt.parent().parent().addClass('isClosedByAdmin');
					}					
				}
			});				
		})		
		
		$('.ignore_switch_off').on('click',function() {
			
			$(this).hide();
			$(this).parent().find('.ignore_switch_on').css("display", "inline-block");
			var user_id = $(this).parent().find('a.user_id').text().replace(' ','');
			$.ajax({
				type: 'GET',
				url: '{{ route('ignoreDuplicate') }}?{{csrf_token()}}={{now()->timestamp}}',
				data: { value : user_id,op:0},
				success: function(xhr, status, error){

				},
				error: function(xhr, status, error){
					alert('User Id：'+user_id+'取消加入略過清單失敗，請重新操作\n錯誤訊息：'+status+' '+error);
				}
			});				
		})
		
		$('.ignore_switch_on').on('click',function() {
			$(this).hide();
			$(this).parent().find('.ignore_switch_off').css("display", "inline-block");
			var user_id = $(this).parent().find('a.user_id').text().replace(' ','');
			$.ajax({
				type: 'GET',
				url: '{{ route('ignoreDuplicate') }}?{{csrf_token()}}={{now()->timestamp}}',
				data: { value : user_id,op:1},
				success: function(xhr, status, error){

				},
				error: function(xhr, status, error){
					alert('User Id：'+user_id+'加入略過清單失敗，請重新操作\n錯誤訊息：'+status+' '+error);
					console.log(xhr);
					console.log(status);
					console.log(error);
				}
			});			
		})	


		$('.ignore_cell_off').on('click',function() {
			
			$(this).hide();
			$(this).parent().find('.ignore_cell_on').show();
			var nowelt = $(this);
			var id = $(this).attr('id');
			var userid_cat_string = id.replace('ignore_cell_off_','');
			var userid_cat_arr = userid_cat_string.split('_');
			var user_id = userid_cat_arr[0];
			var cat = userid_cat_arr[1];
			var cat_type = nowelt.data('cat_type');
            $.ajax({
				type: 'GET',
				url: '{{ route('ignoreDuplicate') }}?{{csrf_token()}}={{now()->timestamp}}',
				data: { value : user_id,op:0,cat:cat,cat_type:cat_type},
				success: function(xhr, status, error){
					nowelt.parent().removeClass('ignore_cell');
				},
				error: function(xhr, status, error){
                    var cat_type='';
                    if(cat.indexOf('.')>=0 ) cat_type='IP';
                    else cat_type='Cfp_Id';
					alert('User Id：'+user_id+' '+cat_type+'：'+cat+'取消加入略過清單失敗，請重新操作\n錯誤訊息：'+status+' '+error);
					nowelt.show();
					$(this).parent().find('.ignore_cell_on').hide();					
				}
			});				
		})
		
		$('.ignore_cell_on').on('click',function() {
			var nowelt = $(this);
			$(this).hide();
			$(this).parent().find('.ignore_cell_off').show();
			var id = $(this).attr('id');
			var userid_cat_string = id.replace('ignore_cell_on_','');
			var userid_cat_arr = userid_cat_string.split('_');
			var user_id = userid_cat_arr[0];
			var cat = userid_cat_arr[1];
			var cat_type = nowelt.data('cat_type');
            $.ajax({
				type: 'GET',
				url: '{{ route('ignoreDuplicate') }}?{{csrf_token()}}={{now()->timestamp}}',
				data: { value : user_id,op:1,cat:cat,cat_type:cat_type},
				success: function(xhr, status, error){
					nowelt.parent().addClass('ignore_cell');
				},
				error: function(xhr, status, error){
                    var cat_type='';
                    if(cat.indexOf('.')>=0 ) cat_type='IP';
                    else cat_type='Cfp_Id';					
                    alert('User Id：'+user_id+' '+cat_type+'：'+cat+'加入略過清單失敗，請重新操作\n錯誤訊息：'+status+' '+error);
					nowelt.show();
					nowelt.parent().find('.ignore_cell_off').hide();
					console.log(xhr);
					console.log(status);
					console.log(error);
				}
			});			
		})	

        $('#review_cron_log').on('click',function(){
            $('#cron_log').toggle();
            return false;
        });
	})	
</script>
<body style="padding: 15px;">


<div class="top_head_title_info">
<h2>
比對上線時間
</h2>

</div>

<form method="post">
{!! csrf_field() !!}
<div class="show" >
	
    <h2>第 {{request()->group_no}} 組</h2>

    <table class="table-hover table table-bordered {{isset($groupInfo[$g]['cutData'])?'ignore_msg':''}}">
        <tr>
            <th class="col_user_id">比較</th>
			<th>為主</th>
			<th>email</th>
            <th>暱稱</th>
			<th>所在地</th>
			<th>上線次數</th>
			<th>CFP組數</th>
			<th>Ip組數</th>           
        </tr>
		@foreach($row_list as $r) 		
        <tr class="{{$service->riseByUserEntry($r->user)->getWarnedBannedClass()}}">
            <th class="col-1st">
                <input type="checkbox" name="target[]" value="{{$r->user->id}}"> 
            </th>
            <th  class="col-2nd">
                <input type="radio" name="master" value="{{$r->user->id}}"> 
            </th>           
			@if($r->user)
            <th class="col-3rd"  style="color: {{ $r->user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">
                <a href="{!!route('users/advInfo',$r->user->id)!!}"  target="_blank">{{ $r->user->email }}</a>
            </th>
            <th  class="col-most" style="color: {{ $r->user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">
                <a href="#" class="user user_name" title="{{$r->user->name}}" onclick="return false;">{{ mb_strlen($r->user->name)>8?mb_substr($r->user->name,0,9).'...':$r->user->name }}</a>
            </th>				
            <th class="col-most" style="color: {{ $r->user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">
                {!! $service->getCityLayoutByUserEntry($r->user) !!}
            </th>
            <th class="col-most"  style="color: {{ $r->user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">
                {{ $r->user->login_times }}
            </th>
            <th class="col-most"  style="color: {{ $r->user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">
                {{ $r->user->log_user_login()->distinct('cfp_id')->count() }}
            </th>
            <th class="col-most"  style="color: {{ $r->user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">
                {{ $r->user->log_user_login()->distinct('ip')->count() }}
            </th>
			@else
            <th class="col-2nd"  ></th>
            <th class="col-3rd"></th>
            <th class="col-most" ></th>
            <th class="col-most" ></th>
            <td class="col-most" ></td>
            <td class="col-most"></td>
            <td class="col-most"></td>
			@endif          	
        </tr>
    @endforeach   
    </table>
    <div id="compare_setting_container">
        <span>
            時間 
            <input type="number" name="compare_interval" min="0" value="30" style="width:80px;" /> 
            分鐘
        </span>
        <span>
            比對天數 
            <select name="before_period">
                <option value="1">一個月</option>
                <option value="2">二個月</option>
                <option value="3">三個月</option>
                <option value="6">六個月</option>
            </select>
            
        </span> 
        <span>
        <input type="submit" />
        </span>
    </div>
</div>

</form>
</body>
@stop