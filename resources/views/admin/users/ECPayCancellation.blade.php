@extends('admin.main')
@section('app-content')
<style>
	.table > tbody > tr > td, .table > tbody > tr > th{
		vertical-align: middle;
	}
	.table > tbody > tr > th{
		text-align: center;
	}
</style>
<body style="padding: 15px;">
<h1>綠界 VIP 付費取消資料</h1>
<form method="POST" action="{{ route('users/VIP/ECCancellations') }}" class="search_form">
	{!! csrf_field() !!}
	<table class="table-hover table table-bordered" style="width: 50%;">
		<tr>
			<th>月份</th>
			<td>
				<input type='text' id="datepicker_1" name="yearMonth" data-date-format='yyyy-mm' value="{{ old('yearMonth', $thisYearMonth) }}" class="form-control">
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<button type="button" class="btn btn-primary" onclick="$('.search_form').submit()">送出</button>
			</td>
		</tr>
	</table>
</form><br>
@if(isset($contents))
<table class='table table-bordered table-hover'>
	<tr>
		<th>會員 ID</th>
		<th>訂單編號</th>
		<th>取消日期</th>
	</tr>
	@forelse ($contents as $c)
	<tr>
		<td><a href="{{ route('users/advInfo', $c[1]) }}" target="_blank">{{ $c[1] }}</a></td>
		<td>{{ $c[2] }}</td>
		<td>{{ old('yearMonth', $thisYearMonth) }}-{{ $c[6] }}</td>
	</tr>
	@empty
	<tr>
	找不到資料
	</tr>
	@endforelse
</table>
@endif
</body>
<script>
	jQuery(document).ready(function() {
		jQuery("#datepicker_1").datepicker(
				{
					dateFormat: 'yy-mm',
					todayHighlight: !0,
					orientation: "bottom left",
					templates: {
						leftArrow: '<i class="la la-angle-left"></i>',
						rightArrow: '<i class="la la-angle-right"></i>'
					}
				}
		).val();
	});
</script>
</html>
@stop