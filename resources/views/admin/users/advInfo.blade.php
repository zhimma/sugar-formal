@include('partials.header')

<body style="padding: 15px;">
<h1>{{ $user->name }}的所有資料<a href="edit/{{ $user->id }}" class='text-white btn btn-primary'>修改</a></h1>
<h4>基本資料</h4>
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
<h4>詳細資料</h4>
<table class='table table-hover table-bordered'>	
	<tr>
		<th>會員ID</th>
		<td>{{ $userMeta->user_id }}</td>
		<th>手機</th>
		<td>{{ $userMeta->phone }}</td>
		<th>是否已啟動</th>
		<td>@if($userMeta->is_active == 1) 是 @else 否 @endif</td>
		<th rowspan='3'>照片</th>
		<td rowspan='3'>@if($userMeta->pic) <img src="{{$userMeta->pic}}" width='150px'> @else 無 @endif</td>
	</tr>
	<tr>
		<th>縣市</th>
		<td>@if($userMeta->city=='0') 無 @else {{ $userMeta->city }} {{ $userMeta->area }} @endif</td>
		<th>拒絕查詢的縣市</th>
		<td>@if($userMeta->blockcity=='0') 無 @else {{ $userMeta->blockcity }} {{ $userMeta->blockarea }} @endif</td>
		<th>預算</th>
		<td>{{ $userMeta->budget }}</td>
	</tr>
	<tr>
		<th>生日</th>
		<td>{{ date('Y-m-d', strtotime($userMeta->birthdate)) }}</td>
		<th>身高</th>
		<td>{{ $userMeta->height }}</td>
		<th>職業</th>
		<td>{{ $userMeta->occupation }}</td>
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
		<th>關於我</th>
		<td colspan='3'>{{ $userMeta->about }}</td>
		<th>期待的約會模式</th>
		<td colspan='3'>{{ $userMeta->style }}</td>
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
		<th>職業</th>
		<td>{{ $userMeta->job }}</td>
		<th>資產</th>
		<td>{{ $userMeta->domain }}</td>
		<th>年收</th>
		<td>{{ $userMeta->income }}</td>
		<th>信息通知</th>
		<td>{{ $userMeta->notifmessage }}</td>
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
<h4>所有訊息</h4>
<table class="table table-hover table-bordered">
<form action="{{ route('users/message/modify') }}" method="post">
    {!! csrf_field() !!}
	<input type="hidden" name="delete" id="delete" value="1">
	<tr>
		<td>發送給</td>
		<td>內容</td>
		<td>發送時間</td>
        <td style="text-align: center; vertical-align: middle"><button type="submit" class="btn btn-danger delete-btn">刪除選取</button></td>
	</tr>
	@forelse ($userMessage as $uM)
		<tr>
			<td>{{ $to_ids[$uM->to_id] }}</td>
			<td>{{ $uM->content }}</td>
			<td>{{ $uM->created_at }}</td>
            <td style="text-align: center; vertical-align: middle">
                <input type="checkbox" name="msg_id[]" value="{{ $uM->id }}" class="form-control">
            </td>
		</tr>
    @empty
        沒有訊息
    @endforelse
</form>
</table>
{!! $userMessage->links() !!}
<h4>現有生活照</h4>
<?php $pics = \App\Models\MemberPic::getSelf($user->id); ?>
<table class="table table-hover table-bordered" style="width: 50%;">
	@forelse ($pics as $pic)
		<tr>
			<td>
				<input type="hidden" name="userId" value="{{$user->id}}">
				<input type="hidden" name="imgId" value="{{$pic->id}}">
				<div style="width:400px">
					<img src="{{$pic->pic}}" />
				</div>
			</td>
		</tr>
	@empty
		此會員目前沒有生活照
	@endforelse
</table>
</body>
<script>
jQuery(document).ready(function(){
    $('.delete-btn').on('click',function(e){
        if(!confirm('確定要刪除選取的訊息?')){
            e.preventDefault();
        }
    });
});
</script>
</html>