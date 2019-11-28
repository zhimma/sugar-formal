@include('partials.header')

<body style="padding: 15px;">
<h1>
	@if($user['vip'] )<i class="fa fa-diamond" style="font-size: 2rem;"></i>@endif{{ $user->name }}的所有資料
	<a href="edit/{{ $user->id }}" class='text-white btn btn-primary'>修改</a>
	@if($user['isBlocked'])
		<button type="button" id="unblock_user" class='text-white btn @if($user["isBlocked"]) btn-success @else btn-danger @endif' onclick="Release({{ $user['id'] }})" data-id="{{ $user['id'] }}" data-name="{{ $user['name']}}"> 解除封鎖 </button>
	@else 
		<a class="btn btn-danger ban-user" id="block_user" href="#" data-toggle="modal" data-target="#blockade" data-id="{{ $user['id'] }}" data-name="{{ $user['name']}}">封鎖會員</a>
	@endif
	
	@if($user['vip'])
		<button class="btn btn-info" onclick="VipAction({{($user['vip'])?'1':'0' }},{{ $user['id'] }})"> 取消VIP </button>
	@else 
		<button class="btn btn-info" onclick="VipAction({{($user['vip'])?'1':'0' }},{{ $user['id'] }})"> 升級VIP </button>
	@endif
	@if(is_null($userMeta->activation_token))
		<b style="font-size:18px">已開通會員</b>
	@else
		<a href="{{ route('activateUser',$userMeta->activation_token) }}" class="btn btn-success"> 通過認證信 </a>
	@endif
</h1>
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
		<td>回覆收訊者</td>
		<td>封鎖收訊者</td>
        <td style="text-align: center; vertical-align: middle"><button type="submit" class="btn btn-danger delete-btn">刪除選取</button></td>
	</tr>
	@forelse ($userMessage as $key => $message)
		<tr>
			<td>
				<a href="{{ route('admin/showMessagesBetween', [$user->id, $message->to_id]) }}" target="_blank">{{ $to_ids[$message->to_id]['name'] }}@if($to_ids[$message->to_id]['vip'] )<i class="fa fa-diamond"></i>@endif</a>
			</td>
			<td>{{ $message->content }}</td>
			<td>{{ $message->created_at }}</td>
			<td>
				<a href="{{ route('AdminMessengerWithMessageId', [$message->to_id, $message->id]) }}" target="_blank" class='btn btn-dark'>撰寫</a>
			</td>
			<td>
				<a class="btn btn-danger ban-user{{ $key }}" href="#" data-toggle="modal" data-target="#blockade" data-id="{{ route('banUserWithDayAndMessage', [$message->to_id, $message->id]) }}" data-name="{{ $to_ids[$message->to_id]['name']}}">封鎖</a>
			</td>
            <td style="text-align: center; vertical-align: middle">
                <input type="checkbox" name="msg_id[]" value="{{ $message->id }}" class="form-control">
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
<div class="modal fade" id="blockade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">封鎖</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/admin/users/toggleUserBlock" method="POST" id="clickToggleUserBlock">
            	{!! csrf_field() !!}
				<input type="hidden" value="" name="user_id" id="blockUserID">
				<input type="hidden" value="advInfo" name="page">
                <div class="modal-body">
                        封鎖時間
                        <select name="days" class="days">
                            <option value="3">三天</option>
                            <option value="7">七天</option>
                            <option value="15">十五天</option>
                            <option value="30">三十天</option>
                            <option value="X" selected>永久</option>
                        </select>
                        <hr>
                        封鎖原因
                        <a class="text-white btn btn-success advertising">廣告</a>
                        <a class="text-white btn btn-success improper-behavior">非徵求包養行為</a>
                        <a class="text-white btn btn-success improper-words">用詞不當</a>
                        <a class="text-white btn btn-success improper-photo">照片不當</a>
                        <br><br>
                        <textarea class="form-control m-reason" name="msg" id="msg" rows="4" maxlength="200">廣告</textarea>
                </div>
                <div class="modal-footer">
                	<button type="submit" class='btn btn-outline-success ban-user'> 送出 </button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">取消</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div>
	<form action="/admin/users/VIPToggler" method="POST" id="clickVipAction">
		{{ csrf_field() }}
		<input type="hidden" value="" name="user_id" id="vipID">
		<input type="hidden" value="" name="isVip" id="isVip">
		<input type="hidden" value="advInfo" name="page">
	</form>
</div>
<script src="/js/vendors.bundle.js" type="text/javascript"></script>
<script>
jQuery(document).ready(function(){
    $('.delete-btn').on('click',function(e){
        if(!confirm('確定要刪除選取的訊息?')){
            e.preventDefault();
        }
	});

	$('a[data-toggle=modal], button[data-toggle=modal]').click(function () {
		if (typeof $(this).data('id') !== 'undefined') {
			$("#exampleModalLabel").html('封鎖 '+ $(this).data('name'))
			$("#blockUserID").val($(this).data('id'))
		}
	});

	// $('a[data-toggle=modal], button[data-toggle=modal]').click(function () {
	// 	var data_id = '';
	// 	if (typeof $(this).data('id') !== 'undefined') {
	// 		data_id = $(this).data('id');
	// 		$("#exampleModalLabel").html('封鎖 '+ $(this).data('name'))
	// 	}
	// 	$("#send_blockade").attr('href', data_id);
	// });

	$('.advertising').on('click', function(e) {
		$('.m-reason').val('廣告');
	});
	$('.improper-behavior').on('click', function(e) {
		$('.m-reason').val('非徵求包養行為');
	});
	$('.improper-words').on('click', function(e) {
		$('.m-reason').val('用詞不當');
	});
	$('.improper-photo').on('click', function(e) {
		$('.m-reason').val('照片不當');
	});
});
function Release(id) {
	$("#blockUserID").val(id);
	$("#clickToggleUserBlock").submit();
}
function VipAction(isVip, user_id){
	$("#isVip").val(isVip);
	$("#vipID").val(user_id);
	$("#clickVipAction").submit();
}
function setDays(button){
    
    let reason = $(".m-reason").val();
    let days = $(".days").val();
    button.attr('href', button.attr('href') + '/' + days + '&' + reason);
    // if open href in a new windows and continue ban user by message
    // need reset the href from data-id
    window.location.href = button.attr('href');
}
function changeFormContent(form_id , key) {
    let href = $(".ban-user" + key).data('id');
    $("#" + form_id + " button[type='submit']").attr({
        'type': 'button',
        'href': href,
        'onClick' : 'setDays($(this))'
    });    
}

$("#block_user").click(function(){
	var data = $(this).data();
	if(confirm('確定封鎖此會員?')){
		$.ajax({
			type: 'POST',
			url: "/admin/users/block_user",
			data:{
				_token: '{{csrf_token()}}',
				data: data,
			},
			dataType:"json",
			success: function(res){
				alert('封鎖成功');
				location.reload();
			}});
	}
});

$("#unblock_user").click(function(){
	var data = $(this).data();
	if(confirm('確定解除封鎖此會員?')){
		$.ajax({
			type: 'POST',
			url: "/admin/users/unblock_user",
			data:{
				_token: '{{csrf_token()}}',
				data: data,
			},
			dataType:"json",
			success: function(res){
				alert('解除封鎖成功');
				location.reload();
			}});
	}
});
</script>
</html>