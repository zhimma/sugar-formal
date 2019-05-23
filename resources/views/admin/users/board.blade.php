@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
<h1>留言板管理</h1>
<form method="POST" action="{{ route('users/board/search') }}" class="search_form">
	{!! csrf_field() !!}
	<table class="table-hover table table-bordered" style="width: 50%;">
		<tr>
			<th>
				<label for="date_start" class="">起始時間</label>
			</th>
			<td>
				<input type='text' id="datepicker_1" name="date_start" data-date-format='yyyy-mm-dd' value="@if(isset($date_start)){{ $date_start }}@endif" class="form-control">
			</td>
		</tr>
		<tr>
			<th>
				<label for="date_end">結束時間</label>
			</th>
			<td>
				<input type='text' id="datepicker_2" name="date_end" data-date-format='yyyy-mm-dd' value="@if(isset($date_end)){{ $date_end }}@endif" class="form-control">
			</td>
		</tr>
		<tr>
			<th>
				<label for="keyword">關鍵字</label>
			</th>
			<td>
				<input type="text" name="keyword" class="form-control">
			</td>
		</tr>
	</table>
	<button type="submit" class="btn btn-success">送出</button>
</form><br>
@if(isset($messages))
<table class='table table-bordered table-hover'>
	<tr>
		<th>內容</th>
		<th>會員</th>
		<th>性別</th>
		<th>留言時間</th>
		<th>操作</th>
	</tr>
	@forelse ($messages as $m)
	<tr>
		<td>{{ $m->post }}</td>
		<td>
            <a href="advInfo/{{ $m->member_id }}" target="_blank">{{ $m->name }}</a>
        </td>
		<td>@if($m->engroup == 1) 男 @else 女 @endif</td>
		<td>{{ $m->created_at }}</td>
		<td>
			<a href="{{ route('users/board/delete', $m->id) }}" class="btn btn-danger text-white" onclick="return confirm('確定要刪除?')">刪除</a>
		</td>
	</tr>
	@empty
	<tr>
	找不到資料
	</tr>
	@endforelse
</table>
@endif
{{ $messages->links() }}
</body>
<script>
	let date = new Date();
	let year = date.getFullYear();
	let month = date.getMonth() + 1;
	let day = date.getDate();
	let today = new Date(year, month, day);
	let minus_date = new Date(today);
	jQuery(document).ready(function(){
		jQuery("#datepicker_1").datepicker(
				{
					dateFormat: 'yy-mm-dd',
					todayHighlight: !0,
					orientation: "bottom left",
					templates: {
						leftArrow: '<i class="la la-angle-left"></i>',
						rightArrow: '<i class="la la-angle-right"></i>'
					}
				}
		).val();
		jQuery("#datepicker_2").datepicker(
				{
					dateFormat: 'yy-mm-dd',
					todayHighlight: !0,
					orientation: "bottom left",
					templates: {
						leftArrow: '<i class="la la-angle-left"></i>',
						rightArrow: '<i class="la la-angle-right"></i>'
					}
				}
		).val();

		$('.today').click(
				function(){
					$('#datepicker_1').val(year + '-' + str_pad(month) + '-' + str_pad(day));
					$('.datepicker_1').val(year + '-' + str_pad(month) + '-' + str_pad(day));
					set_end_date();
				});
		$('.last3days').click(
				function () {
					minus_date.setDate(minus_date.getDate() - 2);
					$('#datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
					$('.datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
					set_end_date();
					minus_date.setDate(minus_date.getDate() + 2);
				});
		$('.last10days').click(
				function () {
					minus_date.setDate(minus_date.getDate() - 9);
					$('#datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
					$('.datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
					set_end_date();
					minus_date.setDate(minus_date.getDate() + 9);
				});
		$('.last30days').click(
				function () {
					minus_date.setDate(minus_date.getDate() - 29);
					$('#datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
					$('.datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
					set_end_date();
					minus_date.setDate(minus_date.getDate() + 29);
				});
	});

	function selectAll () {
		$('.boxes').each(
				function () {
					if($(this).is(':checked')){
						$(this).prop("checked", false);
					}
					else{
						$(this).selected();
					}
				});

	}
	function set_end_date(){
		$('#datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
		$('.datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
	}
	function str_pad(n) {
		return String("00" + n).slice(-2);
	}
</script>
</html>
@stop