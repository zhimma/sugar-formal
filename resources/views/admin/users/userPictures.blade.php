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
                <input type="radio" name="en_group" value="1">男</input>
                <input type="radio" name="en_group" value="2">女</input>
            </td>
        </tr>
        <tr>
            <th>地區</th>
            <td class="twzipcode">
{{--                <div class="twzip" data-role="county" data-name="city" data-value="@if(isset($city)) {{$city}} @endif"></div>--}}
{{--                <div class="twzip" data-role="district" data-name="area" data-value="@if(isset($area)) {{$area}} @endif"></div>--}}
                <div class="twzip" data-role="county" data-name="city" ></div>
                <div class="twzip" data-role="district" data-name="area"></div>

            </td>
        </tr>
        <tr>
            <th>排序方式</th>
            <td>
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="order_by" value="updated_at" @if(!isset($order_by) || $order_by == 'updated_at') checked @endif style="margin-left: unset;">
                    <label class="form-check-label" for="inlineRadio4">更新時間</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="order_by" value="last_login" @if(isset($order_by) && $order_by == 'last_login') checked @endif style="margin-left: unset;">
                    <label class="form-check-label" for="inlineRadio5">上線時間</label>
                </div>
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
@if(isset($pics))
<form action="{{ route('users/pictures/modify') }}" id="modify" method="post">
    {!! csrf_field() !!}
    <table class="table-hover table table-bordered">
        <tr>
            <td width="12%">會員名稱</td>
            <td width="12%">Email</td>
            <td width="12%">照片</td>
            <td width="12%">更新時間</td>
            <td width="12%">標題(一句話形容自己）</td>
            <td width="14%">關於我</td>
            <td width="12%">期待的約會模式</td>
            <td width="12%">上線時間</td>
            {{-- <td>
                <button class="btn btn-warning" onclick="$('#modify').submit()" @if($hiddenSearch) name="dehide" @else name="hide" @endif  value="1">@if($hiddenSearch) 解除@endif隱藏</button>
                <button class="btn btn-danger" onclick="$('#modify').submit()" name='delete' value="1">刪除</button>
            </td> --}}
        </tr>
        @if(isset($pics))
            @foreach ($pics as $pic)
                <tr>
                    <td>
                        @if (Auth::user()->can('readonly'))
                            <a href="{{ route('users/pictures/editPic_sendMsg/readOnly', $pic->member_id) }}">{{ $pic->name }}</a>
                        @else
                            <a href="advInfo/editPic_sendMsg/{{ $pic->member_id }}">{{ $pic->name }}</a>
                        @endif
                    </td>
                    <td><a href="/admin/users/advInfo/{{ $pic->member_id }}" target="_blank">{{ $pic->email }}</a></td>
                    <td><img src="{{ url($pic->pic) }}" width="150px"></td>
                    <td>{{ $pic->updated_at }}</td>
                    {{-- <td>
                        <input type="hidden" name="type" value="avatar">
                        <input type="checkbox" name="avatar_id[]" value="{{ $avatar->user_id }}">
                    </td> --}}
                    <td>{{ $pic->title }}</td>
                    <td>{{ $pic->about }}</td>
                    <td>{{ $pic->style }}</td>
                    <td>{{ $pic->last_login }}</td>
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
                var start_date = new Date(new Date().setDate(date.getDate() - 30));
                $('#datepicker_1').val(start_date.getFullYear() + '-' + parseInt(start_date.getMonth()+1) + '-' + start_date.getDate());
                $('.datepicker_1').val(start_date.getFullYear() + '-' + parseInt(start_date.getMonth()+1) + '-' + start_date.getDate());
                set_end_date();
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