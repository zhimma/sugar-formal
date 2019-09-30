@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
<h1>網站公告本月封鎖名單</h1>

共{{ $users->count() }}筆資料
<table class='table table-bordered table-hover'>
	<tr>
		<th>會員ID</th>
		<th>名稱</th>
		<th>封鎖時間</th>
        <th>封鎖原因</th>
	</tr>
	@forelse ($users as $user)
	<tr>
		<td>{{ $user->member_id }}</td>
		<td><a href="{{ route('users/advInfo', $user->member_id) }}" target="_blank">{{ $user->name}} @if(!is_null($isVip[$user->member_id]))<i class="fa fa-diamond"i>@endif</a></td>
		<td>{{ $user->created_at }}</td>
		<td>{{ $user->reason }}</td>
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