@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
<h1>VIP會員統計資料</h1>
共{{ $results->count() }}筆資料
<table class='table table-bordered table-hover'>
	<tr>
		<th>會員ID</th>
		<th>名稱</th>
        <th>VIP持續時間</th>
		<th>升級時間(資料建立時間)</th>
    </tr>
	@forelse ($results as $result)
	<tr>
		<td>{{ $result->member_id }}</td>
		<td>
            <a href="{{ route('users/advInfo', $result->member_id)XA }}" target="_blank">{{ $result['name'] }}</a>
        </td>
        <td>@if($result['times']->y != 0) {{ $result['times']->y }}年@endif @if($result['times']->m != 0) {{ $result['times']->m }}個月@endif{{ $result['times']->d }}天</td>
		<td>{{ $result->created_at }}</td>
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