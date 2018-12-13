@include('partials.header')

<body style="padding: 15px;">
<h1>{{ $id1->name }} 與 {{ $id2->name }} 的所有訊息</h1>
<table class="table table-hover table-bordered">
	<tr>
		<td>發訊</td>
		<td>收訊</td>
		<td>內容</td>
		<td>發送時間</td>
	</tr>
	@forelse ($messages as $m)
		<tr>
			<td>@if($m['from_id'] == $id1['id']) {{ $id1->name }} @else {{ $id2->name }} @endif</td>
			<td>@if($m['from_id'] != $id1['id']) {{ $id1->name }} @else {{ $id2->name }} @endif</td>
			<td>{{ $m->content }}</td>
			<td>{{ $m->created_at }}</td>
		</tr>
    @empty
        沒有訊息
    @endforelse
</table>
{!! $messages->links() !!}
</body>
</html>