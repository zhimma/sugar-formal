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
@if (Auth::user()->can('readonly'))
    <form action="{{ route('users/pictures/readOnly') }}" method="POST">
@else
    <form action="{{ route('users/pictures') }}" method="POST">
@endif
    {!! csrf_field() !!}
    <table class="table-hover table table-bordered" style="width: 50%;">
        <tr>
            <th>開始時間</th>
            <td>
                <input type='text' id="datepicker_1" name="date_start" data-date-format='yyyy-mm-dd' value="{{ old('date_start') }}" class="form-control">
            </td>
        <tr>
            <th>結束時間</th>
            <td>
                <input type='text' id="datepicker_2" name="date_end" data-date-format='yyyy-mm-dd' value="{{ old('date_end') }}" class="form-control">
            </td>
        </tr>
        <tr>
            <th>預設時間選項</th>
            <td>
                <a class="text-white btn btn-success today">今天</a>
                <a class="text-white btn btn-success last3days">最近3天</a>
                <a class="text-white btn btn-success last10days">最近10天</a>
                <a class="text-white btn btn-success last15days">最近15天</a>
                <a class="text-white btn btn-success last30days">最近30天</a>
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
                <button type="submit" class="btn btn-primary">查詢</button> 或
                <button type="submit" class="btn btn-info" name="hidden" value="1">隱藏的照片</button>
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
            {{-- <td>
                <button class="btn btn-warning" onclick="$('#modify').submit()" @if($hiddenSearch) name="dehide" @else name="hide" @endif  value="1">@if($hiddenSearch) 解除@endif隱藏</button>
                <button class="btn btn-danger" onclick="$('#modify').submit()" name='delete' value="1">刪除</button>
            </td> --}}
        </tr>
        @if(isset($pics))
            @foreach ($pics as $pic)
                <tr>
                    {{-- <td>{{ $userNames[$pic->member_id] }}</td> --}}
                    <td>
                        <a href="advInfo/editPic_sendMsg/{{ $pic->member_id }}">{{ $userNames[$pic->member_id] }}</a>
                    </td>
                    <td><img src="{{ url($pic->pic) }}" width="150px"></td>
                    <td>{{ $pic->updated_at }}</td>
                    {{-- <td>
                        <input type="hidden" name="type" value="pic">
                        <input type="checkbox" name="pic_id[]" value="{{ $pic->id }}">
                    </td> --}}
                </tr>
            @endforeach
        @endif
        @if(isset($avatars))
            @foreach ($avatars as $avatar)
                <tr>
                    {{-- <td>{{ $userNames[$avatar->user_id] }}</td> --}}
                    <td>
                        @if (Auth::user()->can('readonly'))
                            <a href="{{ route('users/pictures/editPic_sendMsg/readOnly', $avatar->user_id) }}">{{ $userNames[$avatar->user_id] }}</a>
                        @else
                            <a href="advInfo/editPic_sendMsg/{{ $avatar->user_id }}">{{ $userNames[$avatar->user_id] }}</a>
                        @endif
                    </td>
                    <td><img src="{{ url($avatar->pic) }}" width="150px"></td>
                    <td>{{ $avatar->updated_at }}</td>
                    {{-- <td>
                        <input type="hidden" name="type" value="avatar">
                        <input type="checkbox" name="avatar_id[]" value="{{ $avatar->user_id }}">
                    </td> --}}
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
    let date = new Date();
    let year = date.getFullYear();
    let month = date.getMonth() + 1;
    let day = date.getDate();
    let today = new Date(year, month, day);
    let minus_date = new Date(today);
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

        $('.today').click(
            function () {
                $('#datepicker_1').val(year + '-' + str_pad(month) + '-' + str_pad(day));
                $('.datepicker_1').val(year + '-' + str_pad(month) + '-' + str_pad(day));
                set_end_date();
            });
        $('.last3days').click(
            function () {
                var days = 3; // Days you want to subtract
                var date = new Date();
                var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                $('#datepicker_1').val(last.getFullYear() + '-' + str_pad(last.getMonth() + 1) + '-' + str_pad(last.getDate()));
                $('.datepicker_1').val(date.getFullYear() + '-' + str_pad(date.getMonth()) + '-' + str_pad(date.getDate()));
                set_end_date();
            });
        $('.last10days').click(
            function () {
                var days = 10; // Days you want to subtract
                var date = new Date();
                var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                $('#datepicker_1').val(last.getFullYear() + '-' + str_pad(last.getMonth() + 1) + '-' + str_pad(last.getDate()));
                $('.datepicker_1').val(date.getFullYear() + '-' + str_pad(date.getMonth()) + '-' + str_pad(date.getDate()));
                set_end_date();
            });
        $('.last15days').click(
            function () {
                var days = 15; // Days you want to subtract
                var date = new Date();
                var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                $('#datepicker_1').val(last.getFullYear() + '-' + str_pad(last.getMonth() + 1) + '-' + str_pad(last.getDate()));
                $('.datepicker_1').val(date.getFullYear() + '-' + str_pad(date.getMonth()) + '-' + str_pad(date.getDate()));
                set_end_date();
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
    function set_end_date() {
        $('#datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
        $('.datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
    }
    function str_pad(n) {
        return String("00" + n).slice(-2);
    }
</script>
@stop