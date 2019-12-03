@include('partials.header')
@yield("pre-javascript")
@include('partials.scripts')
@yield("javascript")
<style>
.table > tbody > tr > td, .table > tbody > tr > th{
    vertical-align: middle;
}
h3{
	text-align:left;
}
</style>
<body style="padding: 15px;">
	
	<h1>{{ $users[$reported_id]['name'] }} 的所有被檢舉訊息</h1>
	<br>
	<h3>檢舉訊息</h3>
	<table class="table-hover table table-bordered">
		<th>發送者</th>
		<th>收訊者</th>
		<th>內容</th>
		<th>檢舉時間</th>
		@foreach ($reportedUser['messages'] as $message)
			<tr>
				<td>{{ $users[$message->from_id]['name'] }}</td>
				<td>{{ $users[$message->to_id]['name'] }}</td>
				<td>{{ $message->content }}</td>
				<td>{{ $message->created_at }}</td>
			</tr>
		@endforeach
	</table>
	<br>

	<h3>檢舉會員</h3>
	<table class="table-hover table table-bordered">
		<th>被檢舉者</th>
		<th>檢舉者</th>
		<th>檢舉理由</th>
		<th>檢舉時間</th>
		@foreach ($reportedUser['reports'] as $report)
			<tr>
				<td>{{ $users[$report->reported_id]['name'] }}</td>
				<td>{{ $users[$report->member_id]['name'] }}</td>
				<td>{{ $report->content}}</td>
				<td>{{ $report->created_at }}</td>
			</tr>
		@endforeach
	</table>
	<br>

	<h3>檢舉照片</h3>
	<table class="table-hover table table-bordered">
		<th>被檢舉者</th>
		<th>檢舉者</th>
		<th>圖片</th>
		<th>檢舉理由</th>
		<th>檢舉時間</th>
		@foreach ($reportedUser['avatars'] as $avatar)
			<tr>
				<td>{{ $users[$avatar->reported_user_id]['name'] }}</td>
				<td>{{ $users[$avatar->reporter_id]['name'] }}</td>
				<td>{{ $avatar->pic }}</td>
				<td>{{ $avatar->content }}</td>
				<td>{{ $avatar->created_at }}</td>
			</tr>
		@endforeach
	</table>
	<br>
</body>
