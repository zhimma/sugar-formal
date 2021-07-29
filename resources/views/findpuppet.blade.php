@extends('admin.main')
@section('app-content')

<style>
	body table.table-hover tr.banned,table tr.implicitlyBanned {background-color:#FDFF8C;}
	body table.table-hover tr.isWarned {background-color:#B0FFB1;}
	body table.table-hover tr.isClosed {background-color:#C9C9C9;}
	body table.table-hover tr.isClosedByAdmin {background-color:#969696;}
	td.group_last_time {background-color:#FF9999 !important;}
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
 </style>
 <style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
    .content-table { width:100%; table-layout: fixed; }
    .content-table td { word-wrap:break-word; }
</style>
<body style="padding: 15px;">


<div>
<h2> @if(isset($columnSet) && $columnSet) {{$start_date}} ～ {{$end_date}} @endif 相同IP帳號分析數據</h2>
</div>
@forelse ($groupOrderArr as $gidx=>$g)

<div class="show">
	
    <h2>第 {{ $gidx+1 }} 組<span class="group_last_time">最後登入時間：{{date('m/d-H:i',strtotime($groupInfo[$g]['last_time']))}}</span></h2>
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
            <th></th>
			<th>Email</th>
			<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;暱稱&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;一句話&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;關於我&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;約會模式&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    @foreach ($columnSet[$g] as $c=> $colName)
            <th class="{{$columnTypeSet[$g][$c] ?? ''}}_th"> 
				<a target="_blank" href="{{$columnTypeSet[$g][$c]=='ip'?route('get'.ucfirst($columnTypeSet[$g][$c]).'Users',$colName):'#'}}">{{$colName}}
				</a>
			</th>
    @endforeach
			@if (isset($groupInfo[$g]['cutData']) && $groupInfo[$g]['cutData'])
			<td rowspan="101" class="ignore_msg">略...........</td>
			@endif
        </tr>
    @foreach ($rowSet[$g] as $r=>$user)
        <tr class="{{$user->tag_class}}">
            <th>
			<a target="_blank" href="showLog?user_id={{$user->id}}{{request()->mon?'&mon='.request()->mon:''}}">
			{{$user->id ?? ''}}@if($user->engroup == 1 && $user->isVip()) <i class="m-nav__link-icon fa fa-diamond"></i> @endif </a>
			</th>
			@php
				$bgColor = null;
				//$user = \App\Models\User::with('vip','aw_relation', 'banned', 'implicitlyBanned')->find($rowName);
				if($user){
					if($user->aw_relation ?? $user->user_meta->isWarned) {
						$bgColor = '#B0FFB1';
					}
					if($user->banned ?? $user->implicitlyBanned){
						$bgColor = '#FDFF8C';
					}
				}
			@endphp
			@if($user)
				<th style="color: {{ $user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">
					<a href="{!!route('users/advInfo',$user->id)!!}"  target="_blank">{{ $user->email }}</a>
				</th>
				<th style="color: {{ $user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">
					<a href="#" class="user user_name" title="{{$user->name}}" onclick="return false;">{{ mb_strlen($user->name)>8?mb_substr($user->name,0,9).'...':$user->name }}</a>
				</th>				
				<th style="color: {{ $user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">
					<a href="#" class="user user_title" title="{{$user->title}}" onclick="return false;">{{ mb_strlen($user->title)>16?mb_substr($user->title,0,17).'...':$user->title }}</a>
				</th>
				<th style="color: {{ $user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">
					<a href="#" class="user user_meta_about" title="{{$user->user_meta->about}}" onclick="return false;">{{ mb_strlen($user->user_meta->about)>16?mb_substr($user->user_meta->about,0,17).'...':$user->user_meta->about }}</a>
				</th>
				<th style="color: {{ $user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">
					<a href="#" class="user user_meta_style" title="{{$user->user_meta->style}}" onclick="return false;">{{ mb_strlen($user->user_meta->style)>16?mb_substr($user->user_meta->style,0,17).'...':$user->user_meta->style }}</a>
				</th>
			@else
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<td></td>
			@endif
			@for ($n=0;$n<count($columnSet[$g]);$n++)
				<td @if($user) style="color: {{ $user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif" @endif class=" @if($groupInfo[$g]['last_time']===$cellValue[$g][$r][$n]->time) group_last_time @endif">
					@if(isset($cellValue[$g][$r][$n]))
					{{$cellValue[$g][$r][$n]->time ? date('m/d-H:i',strtotime($cellValue[$g][$r][$n]->time)): ''}}
					(<a target="_blank" href="showLog?user_id={{$user->id}}&{{$columnTypeSet[$g][$n]}}={{$columnSet[$g][$n]}}{{request()->mon?'&mon='.request()->mon:''}}">{{$cellValue[$g][$r][$n]->num ?? ''}}次</a>)
					@else
						無
					@endif
				</td>
			@endfor
        </tr>
    @endforeach   
	@if (isset($groupInfo[$g]['cutData']) && $groupInfo[$g]['cutData'])
		<tr class="ignore_msg"><td colspan="101" class="ignore_msg">略...........</td></tr>
	@endif
    </table>
</div>
@empty
    <div>無資料</div>
@endforelse
	
</body>
@stop