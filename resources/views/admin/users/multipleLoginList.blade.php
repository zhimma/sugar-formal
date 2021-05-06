@extends('admin.main')
@section('app-content')
<style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
    table {width:100%; table-layout: fixed;}
    table td {word-wrap:break-word;}
</style>
<body style="padding: 15px;">
<h1>多重登入名單</h1>
共 {{ $results->count() }} 筆資料
<table class='table table-bordered table-hover'>
	<tr>
        <td style="width: 10%!important;">原會員 ID</td>
		<td>原會員 Email(暱稱)</td>
        <td>原會員關於我</td>
        <td>原會員期待的約會模式</td>
        <td>原會員上次登入時間</td>
        <td>新會員 Email(暱稱)</td>
        <td>新會員關於我</td>
        <td>新會員期待的約會模式</td>
        <td>建立時間</td>
	</tr>
	@forelse($results as $result)
        @php
            $bgColor = null;
        @endphp
        <tr>
            <td @if($result->original_user)
                    @if($result->original_user->aw_relation)
                        @php $bgColor = '#B0FFB1'; @endphp
                    @endif
                    @if($result->original_user->banned or $result->original_user->implicitlyBanned)
                        @php $bgColor = '#FDFF8C'; @endphp
                    @endif
                @endif
                @if($bgColor) style="background-color: {{ $bgColor }}" @endif
            >{{ $result->original_id }}</td>
            @if($result->original_user)
                <td @if($bgColor) style="background-color: {{ $bgColor }}" @endif><a href="advInfo/{{ $result->original_id }}" target="_blank" style="color: {{ $result->original_user->engroup == 1 ? 'blue' : 'red' }}">{{ $result->original_user->email }}<br>{{ $result->original_user->name }}</a></td>
                <td style="color: {{ $result->original_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">{{ $result->original_user->user_meta->about }}</td>
                <td style="color: {{ $result->original_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">{{ $result->original_user->user_meta->style }}</td>
                <td style="color: {{ $result->original_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">{{ $result->original_user->last_login }}</td>
            @else
                <td>資料已刪除</td>
                <td>資料已刪除</td>
                <td>資料已刪除</td>
                <td>資料已刪除</td>
            @endif
            @php
                $bgColor = null;
            @endphp
            @if($result->new_user)
                @if($result->new_user->aw_relation)
                    @php $bgColor = '#B0FFB1'; @endphp
                @endif
                @if($result->new_user->banned or $result->new_user->implicitlyBanned)
                    @php $bgColor = '#FDFF8C'; @endphp
                @endif
                <td @if($bgColor) style="background-color: {{ $bgColor }}" @endif><a href="advInfo/{{ $result->new_id }}" target="_blank" style="color: {{ $result->new_user->engroup == 1 ? 'blue' : 'red' }}">{{ $result->new_user->email }}<br>{{ $result->new_user->name }}</a></td>
                <td style="color: {{ $result->new_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">{{ $result->new_user->user_meta->about }}</td>
                <td style="color: {{ $result->new_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">{{ $result->new_user->user_meta->style }}</td>
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
</body>
@stop