@extends('admin.main')
@section('app-content')
<script src="/js/jquery.twzipcode.min.js" type="text/javascript"></script>
<style>
.table > tbody > tr > td, .table > tbody > tr > th{
    vertical-align: middle;
}
.table > tbody > tr > th{
    text-align: center;
}
</style>
<body style="padding: 15px;">
<h1>會員照片管理</h1>
<form action="{{ route('users/pictures') }}" method="POST">
    {!! csrf_field() !!}
    <table class="table-hover table table-bordered" style="width: 50%;">
        <tr>
            <th>更新時間</th>
            <td>
                <input type="radio" name="days" value="" @if(!isset($days) || $days == null) checked @endif>不限</input>
                <input type="radio" name="days" value="3" @if(isset($days) && $days == 3) checked @endif>3天內</input>
                <input type="radio" name="days" value="7" @if(isset($days) && $days == 7) checked @endif>7天內</input>
                <input type="radio" name="days" value="15" @if(isset($days) && $days == 15) checked @endif>15天內</input>
                <input type="radio" name="days" value="30" @if(isset($days) && $days == 30) checked @endif>30天內</input>
            </td>
        </tr>
        <tr>
            <th>性別</th>
            <td>
                <input type="radio" name="en_group" value="1" @if(isset($en_group) && $en_group == 1) checked @endif>男</input>
                <input type="radio" name="en_group" value="2" @if(isset($en_group) && $en_group == 2) checked @endif>女</input>
            </td>
        </tr>
        <tr>
            <th>地區</th>
            <td class="twzipcode">
                <div class="twzip" data-role="county" data-name="city" data-value="@if(isset($city)) $city @endif"></div>
                <div class="twzip" data-role="district" data-name="area" data-value="@if(isset($area)) $area @endif"></div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <button type="submit" class="btn btn-primary">查詢</button>
            </td>
        </tr>
    </table>
</form>
@if(isset($pics) || isset($avatars))
<form action="{{ route('users/pictures/modify') }}" id="modify" method="post">
    {!! csrf_field() !!}
    <table class="table-hover table table-bordered">
        <tr>
            <td>會員名稱</td>
            <td>照片</td>
            <td>更新時間</td>
            <td>
                <button class="btn btn-warning" onclick="$('#modify').submit()" name="hide" value="1">不顯示</button>
                <button class="btn btn-danger" onclick="$('#modify').submit()" name='delete' value="1">刪除</button>
            </td>
        </tr>
        @if(isset($pics))
            @foreach ($pics as $pic)
                <tr>
                    <td>{{ $userNames[$pic->member_id] }}</td>
                    <td><img src="{{ url($pic->pic) }}" width="150px"></td>
                    <td>{{ $pic->updated_at }}</td>
                    <td>
                        <input type="hidden" name="type" value="pic">
                        <input type="checkbox" name="id" value="{{ $pic->id }}">
                    </td>
                </tr>
            @endforeach
        @endif
        @if(isset($avatars))
            @foreach ($avatars as $avatar)
                <tr>
                    <td>{{ $userNames[$avatar->user_id] }}</td>
                    <td><img src="{{ url($avatar->pic) }}" width="150px"></td>
                    <td>{{ $avatar->updated_at }}</td>
                    <td>
                        <input type="hidden" name="type" value="avatar">
                        <input type="checkbox" name="user_id" value="{{ $avatar->id }}">
                    </td>
                </tr>
            @endforeach
        @endif
    </table>
</form>
@endif
</body>
<script>
    $('.twzipcode').twzipcode({
        'detect': true, 'css': ['form-control twzip', 'form-control twzip', 'zipcode']
    });
</script>
@stop