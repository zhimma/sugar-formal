@extends('admin.main')
@section('app-content')
<style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
    .content-table { width:100%; table-layout: fixed; }
    .content-table td { word-wrap:break-word; }
</style>
<body style="padding: 15px;">
<h1>多重登入名單</h1>
<form action="{{ route('users/multipleLogin') }}" method="post">
    {{ csrf_field() }}
    <table class="table-hover table table-bordered" style="width: 50%;">
        <tr>
            <th colspan="2">
                <label for="msg">原會員登入時間</label>
            </th>
        </tr>
        <tr>
            <th style="width: 15%!important;">開始</th>
            <td>
                <input type="text" id="datepicker_1" name="date_start" data-date-format="yyyy-mm-dd" value="{{ old('date_start') }}" class="form-control">
            </td>
        </tr>
        <tr>
            <th style="width: 15%">結束</th>
            <td>
                <input type="text" id="datepicker_2" name="date_end" data-date-format="yyyy-mm-dd" value="{{ old('date_end') }}" class="form-control">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" class="btn btn-success" value="查詢">
            </td>
        </tr>
    </table>
</form>
共 {{ $new_users->count() }} 筆資料
<table class='table table-bordered table-hover content-table'>
	<tr>
        <td>隱藏</td>
        <td style="width: 10%!important;">原 ID</td>
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
        <tr style="border-top: 3px solid;">
            @if($original_user->original_user)
                @if($original_user->original_user->aw_relation)
                    @php $bgColor = '#B0FFB1'; @endphp
                @endif
                @if($original_user->original_user->banned or $original_user->original_user->implicitlyBanned)
                    @php $bgColor = '#FDFF8C'; @endphp
                @endif
                <td></td>
                <td style="color: {{ $original_user->original_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif"
                >{{ $original_user->original_id }}</td>
                <td style="color: {{ $original_user->original_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif"><a href="advInfo/{{ $original_user->original_id }}" target="_blank" style="color: {{ $original_user->original_user->engroup == 1 ? 'blue' : 'red' }}">{{ $original_user->original_user->email }}</a></td>
                <td style="color: {{ $original_user->original_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">{{ $original_user->original_user->name }}</td>
                <td style="color: {{ $original_user->original_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">{{ $original_user->original_user->user_meta->about }}</td>
                <td style="color: {{ $original_user->original_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">{{ $original_user->original_user->user_meta->style }}</td>
                <td style="color: {{ $original_user->original_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">{{ $original_user->original_user->last_login }}</td>
            @else
                <td></td>
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
                    @if($new_user->new_user)
                        @if($new_user->new_user->aw_relation)
                            @php $bgColor = '#B0FFB1'; @endphp
                        @endif
                        @if($new_user->new_user->banned or $new_user->new_user->implicitlyBanned)
                            @php $bgColor = '#FDFF8C'; @endphp
                        @endif
                        <td></td>
                        <td style="color: {{ $new_user->new_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">{{ $new_user->original_id }}</td>
                        <td style="color: {{ $new_user->new_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif"><a href="advInfo/{{ $new_user->new_id }}" target="_blank" style="color: {{ $new_user->new_user->engroup == 1 ? 'blue' : 'red' }}">{{ $new_user->new_user->email }}</a></td>
                        <td style="color: {{ $new_user->new_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">{{ $new_user->new_user->name }}</td>
                        <td style="color: {{ $new_user->new_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">{{ $new_user->new_user->user_meta->about }}</td>
                        <td style="color: {{ $new_user->new_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">{{ $new_user->new_user->user_meta->style }}</td>
                        <td style="color: {{ $new_user->new_user->engroup == 1 ? 'blue' : 'red' }}; @if($bgColor) background-color: {{ $bgColor }} @endif">{{ $new_user->new_user->last_login }}</td>
                    @else
                        <td></td>
                        <td>{{ $new_user->original_id }}</td>
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
<script>
    jQuery(document).ready(function() {
        jQuery("#datepicker_1").datepicker({
            dateFormat: 'yy-mm-dd',
            todayHighlight: !0,
            orientation: "bottom left",
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        }).val();
        jQuery("#datepicker_2").datepicker({
            dateFormat: 'yy-mm-dd',
            todayHighlight: !0,
            orientation: "bottom left",
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        }).val();
    });
</script>
</body>
@stop