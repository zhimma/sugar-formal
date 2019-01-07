@extends('admin.main')
@section('app-content')
<style>
	td{
		vertical-align: middle!important;
	}
</style>
<body style="padding: 15px;">
<h1>異動檔上傳/檢查記錄</h1>
<table class='table table-bordered table-hover'>
	<tr>
		<th>狀態</th>
		<th>本地檔案</th>
		<th>遠端檔案</th>
        <th>結果</th>
		<th>時間</th>
    </tr>
	@forelse ($data as $d)
	<tr>
		<td>{{ $d->upload_check }}</td>
		<td>{{ $d->local_file }}</td>
		<td>{{ $d->remote_file }}</td>
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