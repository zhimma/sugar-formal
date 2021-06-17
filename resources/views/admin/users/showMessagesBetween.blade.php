@include('partials.header')
@include('partials.message')
<body style="padding: 15px;">
<h1>{{ $id1->name }} 與 {{ $id2->name }} 的所有訊息</h1>
<table class="table table-hover table-bordered">
	<form action="{{ route('users/message/delete') }}" method="post">
	    {!! csrf_field() !!}
{{--		<input type="hidden" name="delete" id="delete" value="1">--}}
	<tr>
		<th>發訊</th>
		<th>收訊</th>
		<th>內容</th>
		<th>回覆收訊者</th>
		<th>封鎖收訊者</th>
		<th>發送時間</th>
		<td style="text-align: center; vertical-align: middle"><button type="submit" class="btn btn-danger delete-btn">刪除選取</button></td>
	</tr>
	@forelse ($messages as $message)
		<tr>
			<td>
				@if($message->from_id == $id1->id) 
					<a href="{{ route('users/advInfo', [$id1->id]) }} ">
						<p @if($id1->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
							{{ $id1->name }}
							@if($id1->vip)
							    @if($id1->vip=='diamond_black')
							        <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
							    @else
							        @for($z = 0; $z < $id1->vip; $z++)
							            <img src="/img/diamond.png" style="height: 16px;width: 16px;">
							        @endfor
							    @endif
							@endif
							@for($i = 0; $i < $id1->tipcount; $i++)
							    👍
							@endfor
							@if(!is_null($id1->isBlocked))
							    @if(!is_null($id1->isBlocked->expire_date))
							        @if(round((strtotime($id1->isBlocked->expire_date) - getdate()[0])/3600/24)>0)
							            {{ round((strtotime($id1->isBlocked->expire_date) - getdate()[0])/3600/24 ) }}天
							        @else
							            此會員登入後將自動解除封鎖
							        @endif
								@elseif(isset($id1->isBlocked->type))
									(隱性)
							    @else
							        (永久)
							    @endif
							@endif

							@if($message->is_row_delete_1 == $id1->id || $message->is_row_delete_2 == $id1->id || $message->is_single_delete_1 == $id1->id || $message->is_single_delete_2 == $id1->id)
								(刪)
							@endif
						</p>
					</a> 
				@else
					<a href="{{ route('users/advInfo', [$id2->id]) }} ">
						<p @if($id2->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
							{{ $id2->name }}
							@if($id2->vip)
							    @if($id2->vip=='diamond_black')
							        <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
							    @else
							        @for($z = 0; $z < $id2->vip; $z++)
							            <img src="/img/diamond.png" style="height: 16px;width: 16px;">
							        @endfor
							    @endif
							@endif
							@for($i = 0; $i < $id2->tipcount; $i++)
							    👍
							@endfor
							@if(!is_null($id2->isBlocked))
							    @if(!is_null($id2->isBlocked->expire_date))
							        @if(round((strtotime($id2->isBlocked->expire_date) - getdate()[0])/3600/24)>0)
							            {{ round((strtotime($id2->isBlocked->expire_date) - getdate()[0])/3600/24 ) }}天
							        @else
							            此會員登入後將自動解除封鎖
							        @endif
								@elseif(isset($id2->isBlocked->type))
									(隱性)
							    @else
							        (永久)
							    @endif
							@endif

							@if($message->is_row_delete_1 == $id2->id || $message->is_row_delete_2 == $id2->id || $message->is_single_delete_1 == $id2->id || $message->is_single_delete_2 == $id2->id)
								(刪)
							@endif
						</p>
					</a>  
				@endif
			</td>
			<td>
				@if($message->from_id != $id1->id) 
					<a href="{{ route('users/advInfo', [$id1->id]) }} ">
						<p @if($id1->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
							{{ $id1->name }}
							@if($id1->vip)
							    @if($id1->vip=='diamond_black')
							        <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
							    @else
							        @for($z = 0; $z < $id1->vip; $z++)
							            <img src="/img/diamond.png" style="height: 16px;width: 16px;">
							        @endfor
							    @endif
							@endif
							@for($i = 0; $i < $id1->tipcount; $i++)
							    👍
							@endfor
							@if(!is_null($id1->isBlockedReceiver))
							    @if(!is_null($id1->isBlockedReceiver->expire_date))
							        @if(round((strtotime($id1->isBlockedReceiver->expire_date) - getdate()[0])/3600/24)>0)
							            {{ round((strtotime($id1->isBlockedReceiver->expire_date) - getdate()[0])/3600/24 ) }}天
							        @else
							            此會員登入後將自動解除封鎖
							        @endif
								@elseif(isset($id1->isBlockedReceiver->type))
									(隱性)
							    @else
							        (永久)
							    @endif
							@endif
						</p>
					</a> 
				@else 
					<a href="{{ route('users/advInfo', [$id2->id]) }} ">
						<p @if($id2->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
							{{ $id2->name }}
							@if($id2->vip)
							    @if($id2->vip=='diamond_black')
							        <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
							    @else
							        @for($z = 0; $z < $id2->vip; $z++)
							            <img src="/img/diamond.png" style="height: 16px;width: 16px;">
							        @endfor
							    @endif
							@endif
							@for($i = 0; $i < $id2->tipcount; $i++)
							    👍
							@endfor
							@if(!is_null($id2->isBlockedReceiver))
							    @if(!is_null($id2->isBlockedReceiver->expire_date))
							        @if(round((strtotime($id2->isBlockedReceiver->expire_date) - getdate()[0])/3600/24)>0)
							            {{ round((strtotime($id2->isBlockedReceiver->expire_date) - getdate()[0])/3600/24 ) }}天
							        @else
							            此會員登入後將自動解除封鎖
							        @endif
								@elseif(isset($id2->isBlockedReceiver->type))
									(隱性)
							    @else
							        (永久)
							    @endif
							@endif
						</p>
					</a>  
				@endif
			</td>
			<td>
				{{ $message->content }}
			</td>
			<td>
				<a href="{{ route('AdminMessengerWithMessageId', [$message->to_id, $message->id]) }}" target="_blank" class='btn btn-dark'>撰寫</a>
			</td>
			<td>
				<a class="btn btn-danger ban-user" href="{{ route('banUserWithDayAndMessage', [$message->to_id, $message->id]) }}">封鎖</a>
			</td>
			<td>
				{{ $message->created_at }}
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
{!! $messages->links() !!}
</body>
</html>

<script>
jQuery(document).ready(function() {
	$('.delete-btn').on('click', function (e) {
		if (!confirm('確定要刪除選取的訊息?')) {
			e.preventDefault();
		}
	});
});
</script>