@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
<h1>{{ $name }}的VIP記錄 @if($expiry != "0000-00-00 00:00:00" && isset($expiry)) (到期日: {{ $expiry }}) @endif</h1>
<table class='table table-bordered table-hover'>
	<thead>
		<tr>
			<th>訂單編號</th>
			<th>訂購日期</th>
			<th>到期日</th>
			<th>購買項目</th>
			<th>付費週期</th>
			<th>付費方式</th>
			<th>扣款日期</th>
			<th>金額</th>
			<th>金流平台</th>
		</tr>
	</thead>
	<tbody>
	@forelse ($order as $row)
		<tr>
			<td>{{$row->order_id}}</td>
			<td>{{ substr($row->order_date, 0, 10) }}</td>
			<td>{{ substr($row->order_expire_date, 0, 10) }}</td>
			<td>{{$row->service_name}}</td>
			<td>{{$row->payment}}</td>
			<td>{{$row->payment_type}}</td>
			<td>
				@php
				$payDate = json_decode($row->pay_date, true);
				@endphp
				@foreach($payDate as $key => $value)
					<span class="badge badge-info">{!! substr($value[0], 0, 10) !!}</span>
				@endforeach

			</td>
			<td>{{$row->amount}}</td>
			<td>{{$row->payment_flow}}</td>


		</tr>
	@empty
		<tr>
			<td colspan="9">找不到資料</td>

		</tr>
	@endforelse
	</tbody>
</table>

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