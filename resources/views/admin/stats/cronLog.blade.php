@extends('admin.main')
@section('app-content')
<style>
	td{
		vertical-align: middle!important;
	}
</style>
<body style="padding: 15px;">
<h1>VIP排程檢查記錄</h1>
<table class='table table-bordered table-hover'>
	<tr>
		<th>資料日期</th>
		<th>會員ID</th>
        <th>結果</th>
		<th>檢查時間</th>
    </tr>
	@forelse ($data as $d)
	<tr>
		<td>{{ $d->date }}</td>
		<td>
			@if($d->user_id == '無')
				{{ $d->user_id }}
			@else
				<a href="{{ route('stats/vip_log', [$d->user_id]) }}" target="_blank">{{ $d->user_id }}</a>
			@endif
		</td>
        <td>{!! html_entity_decode($d->content) !!}</td>
		<td>{{ $d->created_at }}</td>
	</tr>
	@empty
	<tr>
	找不到資料
	</tr>
	@endforelse
</table>
{{ $data->links() }}
</body>
</html>
@stop