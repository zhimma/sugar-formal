@extends('admin.main')
@section('app-content')
<style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
</style>
<body style="padding: 15px;">
<h1>多重登入名單</h1>
共 {{ count($results) }} 筆資料
<table class='table table-bordered table-hover'>
	<tr>
        <td>原會員 ID</td>
		<td>原會員 Email(暱稱)</td>
        <td>新會員 ID</td>
        <td>新會員 Email(暱稱)</td>
        <td>建立時間</td>
	</tr>
	@forelse($results as $result)
    <tr>
        <td>{{ $result->original_id }}</td>
        <td><a href="advInfo/{{ $result->original_id }}" target="_blank">{{ $original_users->where('id', $result->original_id)->first()->email }}({{ $original_users->where('id', $result->original_id)->first()->name }})</a></td>
        <td>{{ $result->new_id }}</td>
        <td><a href="advInfo/{{ $result->new_id }}" target="_blank">{{ $new_users->where('id', $result->new_id)->first()->email }}({{ $new_users->where('id', $result->new_id)->first()->name }})</a></td>
        <td>{{ $result->created_at }}</td>
    </tr>
    @empty
    <tr>
        找不到資料
    </tr>
    @endforelse
</table>
</body>
@stop