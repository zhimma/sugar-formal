@include('partials.header')

<body style="padding: 15px;">
<h1>{{ $id1->name }} 與 {{ $id2->name }} 的所有訊息</h1>
<table class="table table-hover table-bordered">
	<tr>
		<th>發訊</th>
		<th>收訊</th>
		<th>內容</th>
		<th>回覆收訊者</th>
		<th>封鎖收訊者</th>
		<th>發送時間</th>
	</tr>
	@forelse ($messages as $message)
		<tr>
			<td>
				@if($message->from_id == $id1->id) 
					<a href="{{ route('users/advInfo', [$message->from_id]) }} ">
						{{ $id1->name }}——
						@for($i = 0; $i < $id1->tipcount; $i++)
						    👍
						@endfor
						@if($id1->vip)
							<i class="fa fa-diamond"></i>
						@endif
					</a> 
				@else
					<a href="{{ route('users/advInfo', [$message->to_id]) }} ">
						{{ $id2->name }}——
						@for($i = 0; $i < $id2->tipcount; $i++)
						    👍
						@endfor
						@if($id2->vip)
							<i class="fa fa-diamond"></i>
						@endif
					</a>  
				@endif
			</td>
			<td>
				@if($message->from_id != $id1->id) 
					<a href="{{ route('users/advInfo', [$id1->id]) }} ">
						{{ $id1->name }}——
						@for($i = 0; $i < $id1->tipcount; $i++)
						    👍
						@endfor
						@if($id1->vip)
							<i class="fa fa-diamond"></i>
						@endif
					</a> 
				@else 
					<a href="{{ route('users/advInfo', [$message->to_id]) }} ">
						{{ $id2->name }}——
						@for($i = 0; $i < $id2->tipcount; $i++)
						    👍
						@endfor
						@if($id2->vip)
							<i class="fa fa-diamond"></i>
						@endif
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
		</tr>
    @empty
        沒有訊息
    @endforelse
</table>
{!! $messages->links() !!}
</body>
</html>