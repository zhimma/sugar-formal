@extends('admin.main')
@section('app-content')
	<style>
		.table > tbody > tr > td, .table > tbody > tr > th{
			vertical-align: middle;
		}
	</style>
<body style="padding: 15px;">
<h1>留言板管理</h1>
<form method="get" action="{{ route('users/messageBoardList') }}" class="search_form">
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
			<th>預設時間選項</th>
			<td>
				<a class="text-white btn btn-success today">今天</a>
				<a class="text-white btn btn-success last3days">最近3天</a>
				<a class="text-white btn btn-success last10days">最近10天</a>
				<a class="text-white btn btn-success last30days">最近30天</a>
			</td>
		</tr>
	</table>
	<button type="submit" class="btn btn-success">送出</button>
</form><br>
@if(isset($messages))
共 {{ $messages->total() }} 筆資料
<table class='table table-bordered table-hover'>
	<tr>
		<th>會員</th>
		<th>性別</th>
		<th>標題</th>
		<th>內容</th>
		<th>留言時間</th>
		<th>操作</th>
	</tr>
	@forelse ($messages as $m)
	<tr>
		<td><a href="advInfo/{{ $m->user_id }}" target="_blank">{{ $m->name }}</a></td>
		<td>@if($m->engroup == 1) 男 @else 女 @endif</td>
		<td>{{ $m->title }}</td>
		<td>{{ $m->contents }}</td>
		<td>{{ $m->created_at }}</td>
		<td>
			<div style="display:flex;">
				<form method="POST" action="/admin/users/messageBoard/delete/{{ $m->id }}">
					{!! csrf_field() !!}
					<button class="text-white btn btn-danger delete_submit">刪除</button>
				</form>
				<form method="POST" action="/admin/users/messageBoard/hideMsg/{{ $m->id }}">
					{!! csrf_field() !!}
					<input type="hidden" name='hide_by_admin' value="{{ $m->hide_by_admin ?  0 : 1 }}">
					<button class="text-white btn {{ $m->hide_by_admin ?  'btn-success' : 'btn-primary' }}" style="margin-left: 5px;">{{ $m->hide_by_admin ?  '解除' : '' }}隱藏</button>
				</form>
				<div class="text-white btn btn-primary" onclick="showModifyArea()" style="margin-left: 5px;">編輯</div>
			</div>
			<div class="modifyArea" style="margin-top: 10px;display: none;">
				<form method="POST" action="/admin/users/messageBoard/edit/{{ $m->id }}">
					{!! csrf_field() !!}
					<textarea class="form-control m-input" type="textarea" name="contents" rows="5">{{ $m->contents }}</textarea>
					<button type="submit" class="text-white btn btn-primary modify_submit" style="margin-top: 10px;">確認修改</button>
				</form>
			</div>
		</td>
	</tr>
	@empty
	<tr>
	暫無資料
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
				var start_date = new Date(new Date().setDate(date.getDate() - 30));
				$('#datepicker_1').val(start_date.getFullYear() + '-' + padLeft(parseInt(start_date.getMonth()+1),2) + '-' + padLeft(start_date.getDate(),2));
				$('.datepicker_1').val(start_date.getFullYear() + '-' + padLeft(parseInt(start_date.getMonth()+1),2) + '-' + padLeft(start_date.getDate(),2));
				set_end_date();
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

	// 左邊補0
	function padLeft(str,length){
		if(str.length >= length)
			return str;
		else
			return padLeft("0" +str,length);
	}

	function showModifyArea(){
		$('.modifyArea').toggle();
		//$("input[name='phone']").val('');
	}
	$('.modify_submit').on('click',function(e){
		if(!confirm('確定要修改留言內容?')){
			e.preventDefault();
		}
	});

	$('.delete_submit').on('click',function(e){
		if(!confirm('確定要刪除該留言?')){
			e.preventDefault();
		}
	});
</script>
</html>
@stop