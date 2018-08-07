@extends('admin.main')
@section('app-content')
<style>
.table > tbody > tr > td, .table > tbody > tr > th{
    vertical-align: middle;
}
.table > tbody > tr > th{
    text-align: center;
}
</style>
<body style="padding: 15px;">
<h1>站內訊息管理</h1>
@if (isset($errors))
    @if ($errors->count() > 0)
    @else
        <h3 style="text-align: left;">搜尋</h3>
        <form action="{{ route('users/message/search') }}" id='message' method='POST'>
            {!! csrf_field() !!}
            <table class="table-hover table table-bordered" style="width: 50%;">
                <tr>
                    <th width="15%">
                        <label for="msg">訊息內容</label>
                    </th>
                    <td>
                        <input type="text" name="msg" value="@if(isset($msg)) {{ $msg }}@endif" class="form-control" id="msg">
                    </td>
                </tr>
                <tr>
                    <th>開始時間</th>
                    <td>
                        <input type='text' id="datepicker_1" name="date_start" data-date-format='yyyy-mm-dd' value="@if(isset($date_start)){{ $date_start }}@endif" class="form-control">
                    </td>
                <tr>
                    <th>結束時間</th>
                    <td>
                        <input type='text' id="datepicker_2" name="date_end" data-date-format='yyyy-mm-dd' value="@if(isset($date_end)){{ $date_end }}@endif" class="form-control">
                    </td>
                </tr>
                <tr>
                    <th>預設時間選項</th>
                    <td>
                        <a class="text-white btn btn-success today">今天</a>
                        <a class="text-white btn btn-success last3days">最近3天</a>
                        <a class="text-white btn btn-success last10days">最近10天</a>
                        <a class="text-white btn btn-success last30days">最近30天</a>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><button class='text-white btn btn-primary submit'>送出</button></td>
                </tr>
            </table>
        </form>
        @if(isset($results))
        <form action="{{ route('users/message/modify') }}" method="post">
            {!! csrf_field() !!}
            <input type="hidden" name="msg" value="@if(isset($msg)) {{ $msg }}@endif" class="form-control" id="msg2">
            <input type='hidden' id="datepicker_1" name="date_start" data-date-format='yyyy-mm-dd' value="@if(isset($date_start)){{ $date_start }}@endif" class="form-control">
            <input type='hidden' id="datepicker_2" name="date_end" data-date-format='yyyy-mm-dd' value="@if(isset($date_end)){{ $date_end }}@endif" class="form-control">
            <h3 style="text-align: left;">搜尋結果</h3>
            <table class="table-hover table table-bordered">
                <tr>
                    <td>發送者</td>
                    <td>收訊者</td>
                    <td>內容</td>
                    <td>已讀</td>
                    <td>發送時間</td>
                    <td>
                        <button id="select_all" class="btn btn-primary" onclick="selectAll();return false;">全選</button>
                        <br>
                        <button type="submit" class="btn btn-danger" onclick="$('#delete').val(1);">刪除選取</button>
                        <input type="hidden" name="delete" id="delete" value="0">
                        <button type="submit" class="btn btn-warning" onclick="$('#edit').val(1);">修改選取</button>
                        <input type="hidden" name="edit" id="edit" value="0">
                        <input type="hidden" name="msg" value="{{ $msg }}">
                    </td>
                </tr>
                @forelse ($results as $result)
                    <tr @if($result->isBlocked) style="color: #F00;" @endif>
                        <td>
                            <a href="{{ route('users/advInfo', $result->from_id) }}" target='_blank'>{{ $users[$result->from_id] }}</a>
                            <a href="{{ route('toggleUserBlock', $result->from_id) }}" target="_blank" class='text-white btn @if($result->isBlocked) btn-success @else btn-danger @endif'>@if($result->isBlocked) ◯ @else 🞫 @endif</a>
                        </td>
                        <td>{{ $users[$result->to_id] }}</td>
                        <td width="45%">{{ $result->content }}</td>
                        <td>{{ $result->read }}</td>
                        <td>{{ $result->created_at }}</td>
                        <td style="text-align: center; vertical-align: middle">
                            <input type="checkbox" name="msg_id[]" value="{{ $result->id }}" class="form-control boxes">
                        </td>
                    </tr>
                @empty
                    沒有資料
                @endforelse
            </table>
        </form>
        @endif
    @endif
@endif
</body>
<script>
    let date = new Date();
    let year = date.getFullYear();
    let month = date.getMonth() + 1;
    let day = date.getDate();
    let today = new Date(year, month, day);
    let minus_date = new Date(today);
    jQuery(document).ready(function(){
        jQuery("#datepicker_1").datepicker(
            {
                dateFormat: 'yy-mm-dd',
                todayHighlight: !0,
                orientation: "bottom left",
                templates: {
                    leftArrow: '<i class="la la-angle-left"></i>',
                    rightArrow: '<i class="la la-angle-right"></i>'
                }
            }
        ).val();
        jQuery("#datepicker_2").datepicker(
            {
                dateFormat: 'yy-mm-dd',
                todayHighlight: !0,
                orientation: "bottom left",
                templates: {
                    leftArrow: '<i class="la la-angle-left"></i>',
                    rightArrow: '<i class="la la-angle-right"></i>'
                }
            }
        ).val();

        $('.today').click(
            function(){
                $('#datepicker_1').each(
                    function () {
                        $(this).val(year + '-' + str_pad(month) + '-' + str_pad(day));
                    });
                set_end_date();
            });
        $('.last3days').click(
            function () {
                minus_date.setDate(minus_date.getDate() - 2);
                $('#datepicker_1').each(
                    function () {
                        $(this).val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
                    });
                set_end_date();
                minus_date.setDate(minus_date.getDate() + 2);
            });
        $('.last10days').click(
            function () {
                minus_date.setDate(minus_date.getDate() - 9);
                $('#datepicker_1').each(
                    function () {
                        $(this).val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
                    });
                set_end_date();
                minus_date.setDate(minus_date.getDate() + 9);
            });
        $('.last30days').click(
            function () {
                minus_date.setDate(minus_date.getDate() - 29);
                $('#datepicker_1').each(
                    function () {
                        $(this).val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
                    });
                set_end_date();
                minus_date.setDate(minus_date.getDate() + 29);
            });
        $('.submit').click(
            function () {
                $('#msg2').val($('#msg').val());
                $('#message').submit();
            });
    });

    function selectAll () {
        $('.boxes').each(
            function () {
                if($(this).is(':checked')){
                    $(this).prop("checked", false);
                }
                else{
                    $(this).selected();
                }
            });

    }
    function set_end_date(){
        $('#datepicker_2').each(
            function () {
                $(this).val(year + '-' + str_pad(month) + '-' + str_pad(day));
            });
    }
    function str_pad(n) {
        return String("00" + n).slice(-2);
    }
</script>
@stop