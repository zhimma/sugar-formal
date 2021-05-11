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
共 {{ $original_users->count() + $original_new_map->count() }} 筆資料
<table class='table table-bordered table-hover'>
	<tr>
        <td style="width: 10%!important;">會員 ID</td>
		<td>會員 Email</td>
        <td>會員暱稱</td>
        <td>會員關於我</td>
        <td>會員期待的約會模式</td>
        <td>會員上次登入時間</td>
	</tr>
	@forelse($original_users as $original_user)
        @php
            $bgColor = null;
        @endphp
        <tr>
            @if($original_user->original_user)
                @if($original_user->original_user->aw_relation)
                    @php $bgColor = '#B0FFB1'; @endphp
                @endif
                @if($original_user->original_user->banned or $original_user->original_user->implicitlyBanned)
                    @php $bgColor = '#FDFF8C'; @endphp
                @endif
                <td style="color: {{ $original_user->original_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif"
                >{{ $original_user->original_id }}</td>
                <td style="color: {{ $original_user->original_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif"><a href="advInfo/{{ $original_user->original_id }}" target="_blank" style="color: {{ $original_user->original_user->engroup == 1 ? 'blue' : 'red' }}">{{ $original_user->original_user->email }}</a></td>
                <td style="color: {{ $original_user->original_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">{{ $original_user->original_user->name }}</td>
                <td style="color: {{ $original_user->original_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">{{ $original_user->original_user->user_meta->about }}</td>
                <td style="color: {{ $original_user->original_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">{{ $original_user->original_user->user_meta->style }}</td>
                <td style="color: {{ $original_user->original_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">{{ $original_user->original_user->last_login }}</td>
            @else
                <td>{{ $original_user->original_id }}</td>
                <td>資料已刪除</td>
                <td>資料已刪除</td>
                <td>資料已刪除</td>
                <td>資料已刪除</td>
                <td>資料已刪除</td>
            @endif
        </tr>
            @php
                $bgColor = null;
            @endphp
            @foreach($original_new_map[$original_user->id] as $new_user)
                <tr>
                    @if($new_user && is_object($new_user))
                        @if($new_user->new_user->aw_relation)
                            @php $bgColor = '#B0FFB1'; @endphp
                        @endif
                        @if($new_user->new_user->banned or $new_user->new_user->implicitlyBanned)
                            @php $bgColor = '#FDFF8C'; @endphp
                        @endif
                        <td style="color: {{ $new_user->new_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">{{ $new_user->new_id }}</td>
                        <td style="color: {{ $new_user->new_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif"><a href="advInfo/{{ $new_user->new_id }}" target="_blank" style="color: {{ $new_user->new_user->engroup == 1 ? 'blue' : 'red' }}">{{ $new_user->new_user->email }}</a></td>
                        <td style="color: {{ $new_user->new_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">{{ $new_user->new_user->name }}</td>
                        <td style="color: {{ $new_user->new_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">{{ $new_user->new_user->title }}</td>
                        <td style="color: {{ $new_user->new_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">{{ $new_user->new_user->user_meta->about }}</td>
                        <td style="color: {{ $new_user->new_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">{{ $new_user->new_user->user_meta->style }}</td>
                    @else
                        <td>資料已刪除</td>
                        <td>資料已刪除</td>
                        <td>資料已刪除</td>
                        <td>資料已刪除</td>
                        <td>資料已刪除</td>
                        <td>資料已刪除</td>
                    @endif
                </tr>
            @endforeach
        @empty
        <tr>
            找不到資料
        </tr>
    @endforelse
</table>
</body>
@stop