@extends('admin.main')
@section('app-content')

<style>
    body div.top_head_title_info {text-align:center;}
    body div.top_head_title_info div.group_cat_info {margin-top:20px;}
    body div.top_head_title_info div.group_cat_info  a.jump_to_all_handled_group{text-decoration: underline;}
	body table.table-hover tr th.col_user_id {padding-left:106px;padding-right:106px;}
	img.ignore_switch_off {display:none;}
	img.ignore_switch_on {display:inline-block;}
	/*img.ignore_switch_off,img.ignore_switch_on {float:right;}*/
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
    .show {margin-top:50px;maring-bottom:10px;}
    /*table,tr,td,th {border-width:3px; border-style:solid;border-collapse: collapse;border-spacing:0;}*/
    .error {color:red;font-weight:bolder;}
    td, th{ padding:5px;text-align: center;vertical-align: middle;}
    /*th {background-color:#c9c8c7}*/
    /*th.cfp_id_th {background-color:#a3bec2}*/
    table.monlist,table.monlist tr,table.monlist td,table.monlist th {border-width:0px;border-style:none;}
	table.monlist {margin:auto;width:60%;}
	.monlist a.current_month,.monlist a.current_month:visited,.monlist a.current_month:hover,.monlist a.current_month:active{text-decoration: none;}
	.monlist a,.monlist a:visited,.monlist a:hover,.monlist a:active {text-decoration: underline;}
	.download_file a,.download_file a:visited,.download_file a:hover,.download_file a:active {text-decoration: underline;}
	.group_last_time {font-weight:normal;font-size:12px;}
	.ignore_switch_on,.ignore_switch_off,.ignore_cell_on,.ignore_cell_off {height:15px;cursor: pointer;}
	body table tr td.ignore_cell,body table tr td.ignore_cell a,body table tr td.ignore_cell a:visited,body table tr td.ignore_cell a:hover {color:#D0D0D0 !important;}
	body table tr th .btn {width:60px;padding:4px;border-radius:5px;font-size:5px;}
	body table tr th .btn:visited {color:#fff;}
	body table tr th .btn.handling {background-color:#BEBEBE;color:block;cursor:default;}
 	body table tr td.group_last_time,body table tr.banned td.group_last_time,body table tr.implicitlyBanned td.group_last_time,body table tr.isWarned td.group_last_time,body table tr.isClosed td.group_last_time,body table tr.isClosedByAdmin td.group_last_time {background-color:#FF9999 !important;}
 </style>
 <style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
    .content-table { width:100%; table-layout: fixed; }
    .content-table td { word-wrap:break-word; }
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
				url: '/admin/users/accountStatus_admin',
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
				url: '{{ route('ignoreDuplicate') }}',
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
				url: '{{ route('ignoreDuplicate') }}',
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
			var userid_ip_string = id.replace('ignore_cell_off_','');
			var userid_ip_arr = userid_ip_string.split('_');
			var user_id = userid_ip_arr[0];
			var ip = userid_ip_arr[1];
			$.ajax({
				type: 'GET',
				url: '{{ route('ignoreDuplicate') }}',
				data: { value : user_id,op:0,ip:ip},
				success: function(xhr, status, error){
					nowelt.parent().removeClass('ignore_cell');
				},
				error: function(xhr, status, error){
					alert('User Id：'+user_id+' Ip：'+ip+'取消加入略過清單失敗，請重新操作\n錯誤訊息：'+status+' '+error);
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
			var userid_ip_string = id.replace('ignore_cell_on_','');
			var userid_ip_arr = userid_ip_string.split('_');
			var user_id = userid_ip_arr[0];
			var ip = userid_ip_arr[1];
			$.ajax({
				type: 'GET',
				url: '{{ route('ignoreDuplicate') }}',
				data: { value : user_id,op:1,ip:ip},
				success: function(xhr, status, error){
					nowelt.parent().addClass('ignore_cell');
				},
				error: function(xhr, status, error){
					alert('User Id：'+user_id+' Ip：'+ip+'加入略過清單失敗，請重新操作\n錯誤訊息：'+status+' '+error);
					nowelt.show();
					nowelt.parent().find('.ignore_cell_off').hide();
					console.log(xhr);
					console.log(status);
					console.log(error);
				}
			});			
		})		
	})	
</script>
<body style="padding: 15px;">


<div class="top_head_title_info">
<h2>多重登入帳號@if(!request()->only)交叉比對@else{{strtoupper(request()->only)}}分析@endif數據
@if(isset($columnSet) && $columnSet)
<span class="dateInfo">
	@if (request()->only!='cfpid')	
	@if($sdateOfIp)
	{{$sdateOfIp}} ～ 
	@else
	全部直到
	@endif
	{{$end_date}}
	@if(!$sdateOfIp)
	為止
	@endif
	的IP
	@endif
	@if (!request()->only)以及@endif
	@if (request()->only!='ip')	
	@if($sdateOfCfpId)
	{{$sdateOfCfpId}} ～ 
	@else
	全部直到
	@endif
	{{$end_date}}
	@if(!$sdateOfCfpId)
	為止
	@endif
	
	的Cfp Id
	@endif
</span>
@endif 
</h2>
<div class="group_cat_info">
@if(count($groupOrderArr)>1)
從 
<a href="#g{{count($groupOrderArr[0])+1}}" class="jump_to_all_handled_group">第{{count($groupOrderArr[0])+1}}組</a> 
開始為全部被封鎖或帳號被關閉到只剩一個以下的組別
@endif
</div>
</div>
@if(request()->getHttpHost()=='chen.test-tw.icu')
<div>
<input type="button" name="check" value="手動產出數據(僅測試用)" id="checkBtn"  onclick="doCheck();return false;" />
 <script>
	checkBtn = document.getElementById('checkBtn');
	onlyQStr = '{{request()->only}}';
    function doCheck() {
         var sendurl = "./checkDuplicate";
		 
		 var qstr = '';
		 if(onlyQStr) qstr+='?only='+onlyQStr;
        var xhr = new XMLHttpRequest();
		
		xhr.onloadstart = function () {
			checkBtn.value='數據產生中，請稍待';
			checkBtn.disabled = true;
			alert('開始產生數據，結束後將自動重新整理頁面');
        };  
        xhr.onload = function () {

            response = xhr.responseText;

            if (200 <= xhr.status && xhr.status <= 299) {
		
                if(response=='1') {
                    //location.reload();
					location.href=location.pathname+qstr;
                }
                else {
                   alert('執行失敗，錯誤訊息:'+response);
					checkBtn.value='手動產出數據(僅測試用)';
					checkBtn.disabled = false;
                }
            }
            else {
                alert('執行失敗，錯誤代碼:'+xhr.status);
				checkBtn.value='手動產出數據(僅測試用)';
				checkBtn.disabled = false;				
            }

        };  		
        xhr.open("GET", sendurl+qstr);
        xhr.send();
		//alert('開始產生數據，結束後將自動重新整理頁面');
        
      
    }
     
</script>
</div>
@endif
@php $group_count=0;  @endphp
@forelse ($groupOrderArr as $seg_idx=>$grp_seg)
@foreach ($grp_seg as $gidx=>$g)
@php $group_count++;  @endphp
@php //if($group_count>10) break; @endphp
<br><br>
<div class="show"  @if($group_count>=count($groupOrderArr[0]) && !$gidx)id="g{{ $group_count }}"@endif>
	
    <h2>第 {{ $group_count }} 組</h2>
	@if ($groupInfo[$g]['cutData'])
	<div class="attentioninfo">
		<h3>請注意!!!</h3>
		<div>
		本組別( 第{{ $gidx+1 }}組 )表格數據總數為 {{$groupInfo[$g]['maxColIdx']+1}}*{{$groupInfo[$g]['maxRowIdx']+1}}
		<br>將造成瀏覽器無法顯示或網頁請求timeout，因此僅顯示其中部分的 {{$colLimit+1}}*{{$rowLimit+1}} 數據。
		<br>若欲觀看本組別( 第{{ $gidx+1 }}組 )的全部資料，請改瀏覽簡單直列式版本：
		<span class="download_file"><a target="_blank" href="{{request()->url()}}?mon={{request()->mon}}&g={{$g}}&start=ipcfpid&show=text">第{{$gidx+1}}組簡單直列式版本</a></span>
		<br><br>
		</div>
	</div>
	@endif
    <table class="table-hover table table-bordered {{isset($groupInfo[$g]['cutData'])?'ignore_msg':''}}">
        <tr>
            <th class="col_user_id">&nbsp;&nbsp;User id&nbsp;&nbsp;</th>
			<th>{!!str_repeat('&nbsp;',ceil(($max_email_len-5)/2)*2)!!}Email{!!str_repeat('&nbsp;',floor(($max_email_len-5)/2)*2)!!}</th>
			<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;暱稱&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;一句話&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;關於我&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;約會模式&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th>最後上線時間</th>
    @foreach ($colIdxOfCfpId[$g] as $c)
	{{--  @foreach ($columnSet[$g] as $c=> $colName) --}}
            <th class="{{$columnTypeSet[$g][$c] ?? ''}}_th">
				{!!str_repeat('&nbsp;',floor((20-strlen($columnSet[$g][$c]))/2)*2)!!}			
		{{--		<a target="_blank" href="{{'showLog?'.$columnTypeSet[$g][$c].'='.$columnSet[$g][$c].(request()->mon?'&mon='.request()->mon:'')}}">  --}}
					<a target="_blank" href="{{route('getUsersLog')}}?{{$columnTypeSet[$g][$c]}}={{$columnSet[$g][$c]}}"> 
				{{$columnSet[$g][$c]}}
				</a>
				{!!str_repeat('&nbsp;',floor((20-strlen($columnSet[$g][$c]))/2)*2)!!}		
			</th>
    @endforeach
    @foreach ($colIdxOfIp[$g] as $i=>$c)
            <th class="{{$columnTypeSet[$g][$c] ?? ''}}_th {{$i?'':'col_ip_first'}}">
				{!!str_repeat('&nbsp;',floor((20-strlen($columnSet[$g][$c]))/2)*2)!!}			
				<a target="_blank" href="{{route('getUsersLog')}}?ip={{$columnSet[$g][$c]}}">
				{{$columnSet[$g][$c]}}
				</a>
				{!!str_repeat('&nbsp;',floor((20-strlen($columnSet[$g][$c]))/2)*2)!!}		
			</th>
    @endforeach	
			@if (isset($groupInfo[$g]['cutData']) && $groupInfo[$g]['cutData'])
			<td rowspan="101" class="ignore_msg">略...........</td>
			@endif
        </tr>
	{{-- @foreach ($rowSet[$g] as $r=>$user) --}}
		@foreach(array_keys($rowLastLoginArr[$g]) as $r) 
		@php $user = $rowSet[$g][$r]; @endphp
        <tr class="{{$user->tag_class}}">
            <th class="col-1st">
	{{--		<a target="_blank" href="showLog?user_id={{$user->id}}{{request()->mon?'&mon='.request()->mon:''}}">   --}}
				<a target="_blank" class="user_id"  href="{{route('getUsersLog')}}?user_id={{$user->id}}">{{$user->id ?? ''}}@if($user->engroup == 1 && $user->isVip()) <i class="m-nav__link-icon fa fa-diamond"></i> @endif </a>
				@if ($user->email)
				<button type="button" class="btn btn_admin_close  @if($user->account_status_admin==1) btn-danger @else btn-success @endif">站方@if($user->account_status_admin==1)關閉@else開啟@endif</button>
				@if (Auth::user()->can('admin') || Auth::user()->can('juniorAdmin'))
					<a href="{{ route('AdminMessage', $user->id) }}" target="_blank" class='btn btn-dark'>站長訊息</a>
				@elseif (Auth::user()->can('readonly'))
					<a href="{{ route('AdminMessage/readOnly', $user->id) }}" target="_blank" class='btn btn-dark'>站長訊息</a>
				@endif	
				@endif				
				<img src="{{asset("new/images/kai.png")}}" class="ignore_switch_on" style=" {{$user->ignoreEntry?'display:none;':'display:inline-block;'}}"/>			
				<img src="{{asset("new/images/guan.png")}}" class="ignore_switch_off"   style=" {{!$user->ignoreEntry?'display:none;':'display:inline-block;'}}"/>			
			
			</th>
			@php
				$bgColor = null;
				//$user = \App\Models\User::with('vip','aw_relation', 'banned', 'implicitlyBanned')->find($rowName);
				if($user){
					if($user->aw_relation ?? $user->user_meta->isWarned) {
						//$bgColor = '#B0FFB1';
					}
					if($user->banned ?? $user->implicitlyBanned){
						//$bgColor = '#FDFF8C';
					}
				}
			@endphp
			@if($user)
				<th  class="col-2nd"  style="color: {{ $user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">
					<a href="{!!route('users/advInfo',$user->id)!!}"  target="_blank">{{ $user->email }}</a>
				</th>
				<th  class="col-3rd" style="color: {{ $user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">
					<a href="#" class="user user_name" title="{{$user->name}}" onclick="return false;">{{ mb_strlen($user->name)>8?mb_substr($user->name,0,9).'...':$user->name }}</a>
				</th>				
				<th class="col-most" style="color: {{ $user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">
					<a href="#" class="user user_title" title="{{$user->title}}" onclick="return false;">{{ mb_strlen($user->title)>16?mb_substr($user->title,0,17).'...':$user->title }}</a>
				</th>
				<th class="col-most"  style="color: {{ $user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">
					<a href="#" class="user user_meta_about" title="{{$user->user_meta->about}}" onclick="return false;">{{ mb_strlen($user->user_meta->about)>16?mb_substr($user->user_meta->about,0,17).'...':$user->user_meta->about }}</a>
				</th>
				<th class="col-most"  style="color: {{ $user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">
					<a href="#" class="user user_meta_style" title="{{$user->user_meta->style}}" onclick="return false;">{{ mb_strlen($user->user_meta->style)>16?mb_substr($user->user_meta->style,0,17).'...':$user->user_meta->style }}</a>
				</th>
				<th class="col-most"  style="color: {{ $user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">
					{{isset($user->last_login)?date('m/d-H:i',strtotime($user->last_login)):''}}
				</th>
			@else
				<th class="col-2nd"  ></th>
				<th class="col-3rd"></th>
				<th class="col-most" ></th>
				<th class="col-most" ></th>
				<td class="col-most" ></td>
				<td class="col-most" td>
			@endif
			@foreach ($colIdxOfCfpId[$g] as $n)
			{{-- @for ($n=0;$n<count($columnSet[$g]);$n++) --}}
				<td @if($user) style="color: {{ $user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif" @endif class=" @if($groupInfo[$g]['last_time']===$cellValue[$g][$r][$n]->time) group_last_time @endif col-most">
					@if(isset($cellValue[$g][$r][$n]))
					{{$cellValue[$g][$r][$n]->time ? date('m/d-H:i',strtotime($cellValue[$g][$r][$n]->time)): ''}}
	{{--			(<a target="_blank" href="showLog?user_id={{$user->id}}&{{$columnTypeSet[$g][$n]}}={{$columnSet[$g][$n]}}{{request()->mon?'&mon='.request()->mon:''}}">{{$cellValue[$g][$r][$n]->num ?? ''}}次</a>)  --}}
					(<a target="_blank" href="{{route('getUsersLog')}}?user_id={{$user->id}}&{{$columnTypeSet[$g][$n]}}={{$columnSet[$g][$n]}}">{{$cellValue[$g][$r][$n]->num ?? ''}}次</a>)
					@else
						無
					@endif
				</td>
			@endforeach
			@foreach ($colIdxOfIp[$g] as $i=>$n)
				<td @if($user) style="color: {{ $user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif" @endif  class=" @if($groupInfo[$g]['last_time']===$cellValue[$g][$r][$n]->time) group_last_time @endif {{$i?'':'col_ip_first'}} @if($cellValue[$g][$r][$n]->ignoreEntry) ignore_cell  @endif col-most">
					@if(isset($cellValue[$g][$r][$n]))
					{{$cellValue[$g][$r][$n]->time ? date('m/d-H:i',strtotime($cellValue[$g][$r][$n]->time)): ''}}
	{{--			(<a target="_blank" href="showLog?user_id={{$user->id}}&{{$columnTypeSet[$g][$n]}}={{$columnSet[$g][$n]}}{{request()->mon?'&mon='.request()->mon:''}}">{{$cellValue[$g][$r][$n]->num ?? ''}}次</a>)  --}}
					(<a target="_blank" href="{{route('getUsersLog')}}?user_id={{$user->id}}&{{$columnTypeSet[$g][$n]}}={{$columnSet[$g][$n]}}{{request()->mon?'&mon='.request()->mon:''}}">{{$cellValue[$g][$r][$n]->num ?? ''}}次</a>)
					<img src="{{asset('new/images/menu.png')}}" id="ignore_cell_on_{{$user->id}}_{{$columnSet[$g][$n]}}" class="ignore_cell_on" style=" {{$cellValue[$g][$r][$n]->ignoreEntry?'display:none;':''}}"/>
					<img src="{{asset('new/images/ticon_01.png')}}" id="ignore_cell_off_{{$user->id}}_{{$columnSet[$g][$n]}}"  class="ignore_cell_off" style=" {{!$cellValue[$g][$r][$n]->ignoreEntry?'display:none;':''}}"//>
					@else
						無
					@endif
				</td>
			@endforeach			
        </tr>
    @endforeach   
	@if (isset($groupInfo[$g]['cutData']) && $groupInfo[$g]['cutData'])
		<tr class="ignore_msg"><td colspan="101" class="ignore_msg">略...........</td></tr>
	@endif
    </table>
</div>
@endforeach
@empty
    <div>無資料</div>
@endforelse
	
</body>
@stop