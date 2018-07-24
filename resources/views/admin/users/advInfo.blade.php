@include('partials.header')

<body style="padding: 15px;">
<h1>{{ $user->name }}的所有資料</h1>
<h3>基本資料</h3>
<table class='table table-hover table-bordered '>	
	<tr>
		<td>會員ID</td>
		<td>暱稱</td>
		<td>標題</td>
		<td>男/女</td>
		<td>Email</td>
		<td>建立時間</td>
		<td>更新時間</td>
		<td>上次登入</td>
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
<h3>詳細資料</h3>
<table class='table table-hover table-bordered'>	
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
		<td>{{ $userMeta->blockcity }}</td>
		<td>{{ $userMeta->area }}</td>
		<td>{{ $userMeta->blockarea }}</td>
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
		<td>{{ $userMeta->isHideArea }}</td>
		<td>{{ $userMeta->isHideCup }}</td>
		<td>{{ $userMeta->isHideWeight }}</td>
		<td>{{ $userMeta->isHideOccupation }}</td>
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
</table>
</body>
</html>