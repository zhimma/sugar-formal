@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
<h1>異動檔手動修改</h1>
<form method="POST" action="{{ route('users/customize_migration_files') }}" class="search_form">
	{!! csrf_field() !!}
	<div class="form-group">
		<table class="table table-hovered table-bordered" style="width: 50%;">
			<tr>
				<th><label for="action">新增/刪除</label></th>
				<td>
					<input type="radio" name="action" value="new">新增
					<input type="radio" name="action" value="delete">刪除
				</td>
			</tr>
			<tr>
				<th><label for="user_id">會員ID</label></th>
				<td>
					<input type="text" name='user_id' style="width:300px;" id="user_id" required>
				</td>
			</tr>
			<tr>
				<th><label for="order_id">訂單編號</label></th>
				<td>
					<input type="text" name='order_id' style="width:300px;" id="order_id" required>
				</td>
			</tr>
			<tr>
				<th><label for="day">日期</label></th>
				<td>
					<input type="number" name='day' style="width:300px;" id="day" value="{{ $date }}" max="28" min="1" required>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="submit" class="btn btn-primary" value="送出">
				</td>
			</tr>
		</table>
	</div>
</form><br>
<h4>本日異動檔內容</h4>
@if(isset($file))
	{!! html_entity_decode($file) !!}
@else
	<p>本日尚未產生任何異動檔記錄</p>
@endif
</body>
</html>
@stop