<html>
<body>
<style>
	tr td.ignore_msg {text-align: left;vertical-align: top;border-bottom:none;}
	h3 {color:red;}
	a, a:visited, a:hover, a:active {
		/*text-decoration: none;*/
		color: inherit;
	}
    .show {margin-top:50px;maring-bottom:10px;}
    table,tr,td,th {border-width:3px; border-style:solid;border-collapse: collapse;border-spacing:0;}
    .error {color:red;font-weight:bolder;}
    td, th{ padding:5px;text-align: center;vertical-align: middle;}
    th {background-color:#c9c8c7}
    th.cfp_id_th {background-color:#a3bec2}
    table.monlist,table.monlist tr,table.monlist td,table.monlist th {border-width:0px;border-style:none;}
	table.monlist {margin:auto;width:60%;}
	.monlist a.current_month,.monlist a.current_month:visited,.monlist a.current_month:hover,.monlist a.current_month:active{text-decoration: none;}
	.monlist a,.monlist a:visited,.monlist a:hover,.monlist a:active {text-decoration: underline;}
	.download_file a,.download_file a:visited,.download_file a:hover,.download_file a:active {text-decoration: underline;}
 </style>

<div>
<h2>相同IP帳號分析數據</h2>
</div>
@forelse ($columnSet as $g=>$col)
<div class="show">
    <h2>第 {{ $g+1 }} 組</h2>
	@if ($groupInfo[$g]['cutData'])
	<div>
		<h3>請注意!!!</h3>
		<div>
		本組別( 第{{ $g+1 }}組 )表格數據總數為 {{$groupInfo[$g]['maxColIdx']+1}}*{{$groupInfo[$g]['maxRowIdx']+1}}
		<br>將造成瀏覽器無法顯示或網頁請求timeout，因此僅顯示其中部分的 {{$colLimit+1}}*{{$rowLimit+1}} 數據。
		<br>若欲觀看本組別( 第{{ $g+1 }}組 )的全部資料，請改瀏覽簡單直列式版本：
		<span class="download_file"><a target="_blank" href="{{request()->url()}}?mon={{request()->mon}}&g={{$g}}&start=ipcfpid&show=text">第{{$g+1}}組簡單直列式版本</a></span>
		<br><br>
		</div>
	</div>
	@endif
    <table class="{{$groupInfo[$g]['cutData']?'ignore_msg':''}}">
        <tr>
            <th></th>
			<th>Email</th>
			<th>暱稱</th>
			<th>關於我</th>
			<th>約會模式</th>
    @foreach ($col as $c=> $colName)
            <th class="{{$columnTypeSet[$g][$c]}}_th"> 
				
					{{$columnTypeSet[$g][$c]}} ：
				<a target="_blank" href="/showLog?{{$columnTypeSet[$g][$c]}}={{$colName}}{{request()->mon?'&mon='.request()->mon:''}}">{{$colName}}
				</a>
			</th>
    @endforeach
			@if ($groupInfo[$g]['cutData'])
			<td rowspan="101" class="ignore_msg">略...........</td>
			@endif
        </tr>
    @foreach ($rowSet[$g] as $r=>$rowName)
        <tr>
            <th>
			<a target="_blank" href="/showLog?user_id={{$rowName}}{{request()->mon?'&mon='.request()->mon:''}}">
			{{$rowName}}</a>
			</th>
			@php
				$user = \App\Models\User::withOut('vip')->with('aw_relation', 'banned', 'implicitlyBanned')->find($rowName);
				if($user){
					if($user->aw_relation or $user->user_meta->isWarned) {
						$bgColor = '#B0FFB1';
					}
					if($user->banned or $user->implicitlyBanned){
						$bgColor = '#FDFF8C';
					}
				}
			@endphp
			@if($user)
				<th style="color: {{ $user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">
					{{ $user->email }}
				</th>
				<th style="color: {{ $user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">
					{{ $user->name }}
				</th>
				<th style="color: {{ $user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">
					{{ $user->about }}
				</th>
				<th style="color: {{ $user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">
					{{ $user->user_meta->style }}
				</th>
			@else
				<th></th>
				<th></th>
				<th></th>
				<th></th>
			@endif
			@for ($n=0;$n<count($col);$n++)
				<td style="color: {{ $user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">
					@if(isset($cellValue[$g][$r][$n]))
					{{$cellValue[$g][$r][$n]->time}}
					<br>( <a target="_blank" href="/showLog?user_id={{$rowName}}&{{$columnTypeSet[$g][$n]}}={{$columnSet[$g][$n]}}{{request()->mon?'&mon='.request()->mon:''}}">
						{{$cellValue[$g][$r][$n]->num}}次</a> )
					@else
						無
					@endif
					</td>
			@endfor
        </tr>
    @endforeach   
	@if ($groupInfo[$g]['cutData'])
		<tr class="ignore_msg"><td colspan="101" class="ignore_msg">略...........</td></tr>
	@endif
    </table>
</div>
@empty
    <div>無資料</div>
@endforelse
	
</body>
</html>