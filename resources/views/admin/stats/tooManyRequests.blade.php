@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
<h1>異常連線記錄</h1>
共{{ count($results) }}筆資料
<table class='table table-bordered table-hover'>
	<tr>
		<th>IP</th>
		<th>會員</th>
		<th>類型</th>
		<th>記錄時間</th>
    </tr>
	@forelse ($results as $r)
	<tr>
		<td>{{ $r->ip }}</td>
		<td>@if($r->user_id) <a href="{{ route('users/advInfo', $r->user_id) }}" target="_blank">{{ $r->name }}</a>
			@else 無 @endif</td>
		<td>
            @if($r->is_pseudo)
				每 15 分鐘超過 400 次請求，純記錄
			@else
				每分鐘超過 50 次請求，已被阻擋
			@endif
        </td>
		<td>{{ $r->created_at }}</td>
	</tr>
	@empty
	<tr>
	找不到資料
	</tr>
	@endforelse
</table>
</body>
</html>
@stop