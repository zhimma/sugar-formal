@include('partials.header')

<body style="padding: 15px;">
<h1>{{ $user->name }}的所有資料</h1>
<h4>基本資料<a href="advInfo/{{ $user->id }}" target='_blank' class='text-white btn btn-primary'>修改</a></h4>
<table class='table table-hover table-bordered '>	
	<tr>
		<th>會員ID</th>
		<th>暱稱</th>
		<th>標題</th>
		<th>男/女</th>
		<th>Email</th>
		<th>建立時間</th>
		<th>更新時間</th>
		<th>上次登入</th>
	</tr>
	<tr>
		<td>{{ $user->id }}</td>
		<td>{{ $user->name }}</td>
		<td>{{ $user->title }}</td>
		<td>@if($user->engroup==1) 男 @else 女 @endif</td>
		<td>{{ $user->email }}</td>
		<td>{{ $user->created_at }}</td>
		<td>{{ $user->updated_at }}</td>
		<td>{{ $user->last_login }}</td>
	</tr>
</table>
<h4>詳細資料<a href="advInfo/{{ $user->id }}" target='_blank' class='text-white btn btn-primary'>修改</a></h4>
<table class='table table-hover table-bordered'>	
	<tr>
		<th>會員ID</th>
		<td>{{ $userMeta->user_id }}</td>
		<th>手機</th>
		<td>{{ $userMeta->phone }}</td>
		<th>是否已啟動</th>
		<td>@if($userMeta->is_active == 1) 是 @else 否 @endif</td>
		<th rowspan='2'>照片</th>
		<td rowspan='2'>@if($userMeta->pic) <img src="{{$userMeta->pic}}" width='150px'> @else 無 @endif</td>
	</tr>
	<tr>
		<th>所在城市</th>
		<td>{{ $userMeta->city }}</td>
		<th>拒絕查詢城市</th>
		<td>@if($userMeta->blockcity==0) 無 @else $userMeta->blockcity @endif</td>
		<th>預算</th>
		<td>{{ $userMeta->budget }}</td>
	</tr>
	<tr>
		<th>所在地區</th>
		<td>{{ $userMeta->area }}</td>
		<th>拒絕查詢地區</th>
		<td>@if($userMeta->blockarea==0) 無 @else $userMeta->blockarea @endif</td>
		<th>生日</th>
		<td>{{ $userMeta->birthdate }}</td>
		<th>身高</th>
		<td>{{ $userMeta->height }}</td>
	</tr>
	<tr>
		<th>體重</th>
		<td>{{ $userMeta->weight }}</td>
		<th>罩杯</th>
		<td>{{ $userMeta->cup }}</td>
		<th>體型</th>
		<td>{{ $userMeta->body }}</td>
		<th>現況</th>
		<td>{{ $userMeta->situation }}</td>
	</tr>
	<tr>
		<th>職業</th>
		<td>{{ $userMeta->occupation }}</td>
		<th>關於我</th>
		<td colspan='2'>{{ $userMeta->about }}</td>
		<th>期待的約會模式</th>
		<td colspan='2'>{{ $userMeta->style }}</td>
	</tr>
	<tr>
		<th>教育</th>
		<td>{{ $userMeta->education }}</td>
		<th>婚姻</th>
		<td>{{ $userMeta->marriage }}</td>
		<th>喝酒</th>
		<td>{{ $userMeta->drinking }}</td>
		<th>抽菸</th>
		<td>{{ $userMeta->smoking }}</td>
	</tr>
	<tr>
		<th>隱藏地區</th>
		<td>@if($userMeta->isHideArea==1) 是 @else 否 @endif</td>
		<th>隱藏罩杯</th>
		<td>@if($userMeta->isHideCup==1) 是 @else 否 @endif</td>
		<th>隱藏體重</th>
		<td>@if($userMeta->isHideWeight==1) 是 @else 否 @endif</td>
		<th>隱藏職業</th>
		<td>@if($userMeta->isHideOccupation==1) 是 @else 否 @endif</td>	
	</tr>
	<tr>	
		<th>產業1</th>
		<td>{{ $userMeta->domainType }}</td>
		<th>封鎖的產業1</th>
		<td>{{ $userMeta->blockdomainType }}</td>
		<th>產業2</th>
		<td>{{ $userMeta->domain }}</td>
		<th>封鎖的產業2</th>
		<td>{{ $userMeta->blockdomain }}</td>
	</tr>
	<tr>
		<th>工作</th>
		<td>{{ $userMeta->job }}</td>
		<th>資產</th>
		<td>{{ $userMeta->domain }}</td>
		<th>收入</th>
		<td>{{ $userMeta->income }}</td>
		<th>訊息通知</th>
		<td>{{ $userMeta->notifmessage }}</td>
	</tr>
	<tr>
		<th>收件夾顯示方式</th>
		<td>{{ $userMeta->notifhistory }}</td>
		<th>建立時間</th>
		<td>{{ $userMeta->created_at }}</td>
		<th>更新時間</th>
		<td>{{ $userMeta->updated_at }}</td>
		<td></td>
		<td></td>
	</tr>
</table>
<!--<table class='table table-hover table-bordered'>	
	<tr>
		<td>會員ID</td>
		<td>手機</td>
		<td>是否已啟動</td>
		<td>所在城市</td>
		<td>拒絕查詢城市</td>
		<td>所在地區</td>
		<td>拒絕查詢地區</td>
		<td>預算</td>
	</tr>
	<tr>
		<td>{{ $userMeta->user_id }}</td>
		<td>{{ $userMeta->phone }}</td>
		<td>@if($userMeta->is_active == 1) 是 @else 否 @endif</td>
		<td>{{ $userMeta->city }}</td>
		<td>@if($userMeta->blockcity==0) 無 @else $userMeta->blockcity @endif</td>
		<td>{{ $userMeta->area }}</td>
		<td>@if($userMeta->blockarea==0) 無 @else $userMeta->blockarea @endif</td>
		<td>{{ $userMeta->budget }}</td>
	</tr>
</table>
<table class="table table-hover table-bordered">
	<tr>
		<td>生日</td>
		<td>身高</td>
		<td>體重</td>
		<td>罩杯</td>
		<td>體型</td>
		<td>關於我</td>
		<td>期待的約會模式</td>
	</tr>
	<tr>
		<td>{{ $userMeta->birthdate }}</td>
		<td>{{ $userMeta->height }}</td>
		<td>{{ $userMeta->weight }}</td>
		<td>{{ $userMeta->cup }}</td>
		<td>{{ $userMeta->body }}</td>
		<td>{{ $userMeta->about }}</td>
		<td>{{ $userMeta->style }}</td>
	</tr>
</table>
<table class='table table-hover table-bordered'>
	<tr>
		<td>現況</td>
		<td>職業</td>
		<td>教育</td>
		<td>婚姻</td>
		<td>喝酒</td>
		<td>抽菸</td>
		<td>隱藏地區</td>
		<td>隱藏罩杯</td>
		<td>隱藏體重</td>
		<td>隱藏職業</td>
		<td>照片</td>
	</tr>
	<tr>
		<td>{{ $userMeta->situation }}</td>
		<td>{{ $userMeta->occupation }}</td>
		<td>{{ $userMeta->education }}</td>
		<td>{{ $userMeta->marriage }}</td>
		<td>{{ $userMeta->drinking }}</td>
		<td>{{ $userMeta->smoking }}</td>
		<td>@if($userMeta->isHideArea==1) 是 @else 否 @endif</td>
		<td>@if($userMeta->isHideCup==1) 是 @else 否 @endif</td>
		<td>@if($userMeta->isHideWeight==1) 是 @else 否 @endif</td>
		<td>@if($userMeta->isHideOccupation==1) 是 @else 否 @endif</td>
		<td>{{ $userMeta->pic }}</td>
	</tr>
</table>
<table class="table table-hover table-bordered">
	<tr>
		<td>產業1</td>
		<td>封鎖的產業1</td>
		<td>產業2</td>
		<td>封鎖的產業2</td>
		<td>工作</td>
		<td>資產</td>
		<td>收入</td>
		<td>訊息通知</td>
		<td>收件夾顯示方式</td>
	</tr>
	<tr>
		<td>{{ $userMeta->domainType }}</td>
		<td>{{ $userMeta->blockdomainType }}</td>
		<td>{{ $userMeta->domain }}</td>
		<td>{{ $userMeta->blockdomain }}</td>
		<td>{{ $userMeta->job }}</td>
		<td>{{ $userMeta->assets }}</td>
		<td>{{ $userMeta->income }}</td>
		<td>{{ $userMeta->notifmessage }}</td>
		<td>{{ $userMeta->notifhistory }}</td>
	</tr>
</table>
<table class="table table-hover table-bordered">
	<tr>
		<td>建立時間</td>
		<td>更新時間</td>
	</tr>
	<tr>
		<td>{{ $userMeta->created_at }}</td>
		<td>{{ $userMeta->updated_at }}</td>
	</tr>
</table>-->
</body>
</html>