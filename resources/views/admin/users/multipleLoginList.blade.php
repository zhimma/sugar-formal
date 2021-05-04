@extends('admin.main')
@section('app-content')
<style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
</style>
<body style="padding: 15px;">
<h1>多重登入名單</h1>
共 {{ $results->total() }} 筆資料
<table class='table table-bordered table-hover'>
	<tr>
		<td>原會員 Email(暱稱)</td>
        <td>原會員關於我</td>
        <td>原會員期待的約會模式</td>
        <td>新會員 Email(暱稱)</td>
        <td>新會員關於我</td>
        <td>新會員期待的約會模式</td>
        <td>建立時間</td>
	</tr>
    @php $changeColor = false; @endphp
	@forelse($results as $result)
        @if(!$loop->first && $result->original_id == $results[$loop->index - 1]->original_id)
            @php $changeColor = false; @endphp
        @elseif(!$loop->first)
            @php $changeColor = true; @endphp
        @endif
    <tr @if($changeColor) style="background-color: yellow" @endif>
        @if($result->original_user)
            <td><a href="advInfo/{{ $result->original_id }}" target="_blank" style="color: {{ $result->original_user->engroup == 1 ? 'blue' : 'red' }}">{{ $result->original_user->email }}({{ $result->original_user->name }})</a></td>
            <td style="color: {{ $result->original_user->engroup == 1 ? 'blue' : 'red' }}">{{ $result->original_user->user_meta->about }}</td>
            <td style="color: {{ $result->original_user->engroup == 1 ? 'blue' : 'red' }}">{{ $result->original_user->user_meta->style }}</td>
        @else
            <td>資料已刪除</td>
            <td>資料已刪除</td>
            <td>資料已刪除</td>
        @endif
        @if($result->new_user)
            <td><a href="advInfo/{{ $result->new_id }}" target="_blank" style="color: {{ $result->new_user->engroup == 1 ? 'blue' : 'red' }}">{{ $result->new_user->email }}({{ $result->new_user->name }})</a></td>
            <td style="color: {{ $result->new_user->engroup == 1 ? 'blue' : 'red' }}">{{ $result->new_user->user_meta->about }}</td>
            <td style="color: {{ $result->new_user->engroup == 1 ? 'blue' : 'red' }}">{{ $result->new_user->user_meta->style }}</td>
        @else
            <td>資料已刪除</td>
            <td>資料已刪除</td>
            <td>資料已刪除</td>
        @endif
        <td>{{ $result->created_at }}</td>
    </tr>
    @empty
    <tr>
        找不到資料
    </tr>
    @endforelse
</table>
{!! $results->links() !!}
</body>
@stop