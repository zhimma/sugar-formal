@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
<h1>{{ $name }}的VIP記錄 @if($expiry != "0000-00-00 00:00:00") (到期日: {{ $expiry }}) @endif</h1>
<table class='table table-bordered table-hover'>
	<tr>
		<th>動作</th>
		<th>TXN ID</th>
        <th>Action</th>
        <th>是否免費</th>
		<th>資料建立時間</th>
        <th>資料建立時間</th>
    </tr>
	@forelse ($results as $result)
	<tr>
		<td>{{ $result->member_name }}</td>
		<td>{{ $result->txn_id }}</td>
        <td>{{ $result->action }}</td>
        <td>@if($result->free==1)是 @else否 @endif</td>
		<td>{{ $result->created_at }}</td>
        <td>{{ $result->updated_at }}</td>
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